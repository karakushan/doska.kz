<?php

/**
 * Firebase Push Notifications Main Class
 * 
 * @package Firebase_Push_Notifications
 * @author Vitaliy Karakushan
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Firebase Push Notifications class
 */
class Firebase_Push_Notifications
{

    /**
     * Plugin instance
     */
    private static $instance = null;

    /**
     * Firebase Manager instance
     */
    private $firebase_manager = null;

    /**
     * Notification Handler instance
     */
    private $notification_handler = null;

    /**
     * Plugin settings
     */
    private $settings = null;

    /**
     * Get plugin instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->init();
    }

    /**
     * Initialize plugin
     */
    private function init()
    {
        // Load settings
        $this->load_settings();

        // Initialize Firebase Manager
        $this->firebase_manager = FirebaseManager::getInstance();

        // Initialize Notification Handler
        $this->notification_handler = FirebaseNotificationHandler::getInstance();

        // Hook into WordPress
        $this->init_hooks();

        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // AJAX handlers
        add_action('wp_ajax_save_fcm_token', array($this, 'save_fcm_token'));
        add_action('wp_ajax_nopriv_save_fcm_token', array($this, 'save_fcm_token'));
        add_action('wp_ajax_delete_fcm_token', array($this, 'delete_fcm_token'));
        add_action('wp_ajax_nopriv_delete_fcm_token', array($this, 'delete_fcm_token'));
        add_action('wp_ajax_save_notification_preferences', array($this, 'save_notification_preferences'));
        add_action('wp_ajax_get_firebase_config', array($this, 'get_firebase_config_ajax'));
        add_action('wp_ajax_nopriv_get_firebase_config', array($this, 'get_firebase_config_ajax'));

        // Add admin profile hooks
        add_action('show_user_profile', array($this, 'add_firebase_profile_fields'));
        add_action('edit_user_profile', array($this, 'add_firebase_profile_fields'));

        // Hook for syncing guest tokens on login
        add_action('wp_login', array($this, 'sync_guest_token_on_login'), 10, 2);

        // Schedule cleanup of old guest tokens
        add_action('wp', array($this, 'schedule_guest_token_cleanup'));
        add_action('firebase_cleanup_guest_tokens', array($this, 'cleanup_old_guest_tokens'));
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks()
    {
        // Hook into DirectoryPress events
        add_action('directorypress_listing_status_change', array($this, 'handle_listing_status_change'), 10, 2);
        add_action('directorypress_listing_expired', array($this, 'handle_listing_expired'), 10, 1);

        // Hook into messaging events
        add_action('wp_ajax_send_message', array($this, 'handle_new_message'), 5);
        add_action('wp_ajax_nopriv_send_message', array($this, 'handle_new_message'), 5);
    }

    /**
     * Load plugin settings
     */
    private function load_settings()
    {
        $this->settings = array(
            'firebase_enabled' => get_option('firebase_enabled', false),
            'firebase_project_id' => get_option('firebase_project_id', ''),
            'firebase_api_key' => get_option('firebase_api_key', ''),
            'firebase_messaging_sender_id' => get_option('firebase_messaging_sender_id', ''),
            'firebase_app_id' => get_option('firebase_app_id', ''),
            'firebase_vapid_key' => get_option('firebase_vapid_key', ''),
            'firebase_service_account_json' => get_option('firebase_service_account_json', ''),
            'firebase_notification_icon' => get_option('firebase_notification_icon', ''),
            'firebase_notification_badge' => get_option('firebase_notification_badge', ''),
        );
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_scripts()
    {
        if (!$this->settings['firebase_enabled']) {
            return;
        }

        // Firebase SDK
        wp_enqueue_script(
            'firebase-app',
            'https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js',
            array(),
            '10.7.1',
            true
        );

        wp_enqueue_script(
            'firebase-messaging',
            'https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js',
            array('firebase-app'),
            '10.7.1',
            true
        );

        // Firebase initialization script
        wp_enqueue_script(
            'firebase-init',
            FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_URL . 'assets/js/firebase-init.js',
            array('firebase-messaging'),
            FIREBASE_PUSH_NOTIFICATIONS_VERSION,
            true
        );

        // Localize script
        wp_localize_script('firebase-init', 'firebasePushNotifications', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('firebase_push_nonce'),
            'config' => $this->get_firebase_config(),
        ));
    }

    /**
     * Enqueue admin scripts
     */
    public function admin_enqueue_scripts($hook)
    {
        if (strpos($hook, 'firebase') === false) {
            return;
        }

        wp_enqueue_style(
            'firebase-admin',
            FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            FIREBASE_PUSH_NOTIFICATIONS_VERSION
        );

        wp_enqueue_script(
            'firebase-admin',
            FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            FIREBASE_PUSH_NOTIFICATIONS_VERSION,
            true
        );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu()
    {
        add_menu_page(
            __('Firebase Push Notifications', 'firebase-push-notifications'),
            __('Firebase Push', 'firebase-push-notifications'),
            'manage_options',
            'firebase-push-notifications',
            array($this, 'admin_page'),
            'dashicons-bell',
            30
        );

        add_submenu_page(
            'firebase-push-notifications',
            __('Test Notifications', 'firebase-push-notifications'),
            __('Test Notifications', 'firebase-push-notifications'),
            'manage_options',
            'firebase-test-notifications',
            array($this, 'test_notifications_page')
        );

        add_submenu_page(
            'firebase-push-notifications',
            __('Firebase Configuration', 'firebase-push-notifications'),
            __('Configuration', 'firebase-push-notifications'),
            'manage_options',
            'firebase-configuration',
            array($this, 'firebase_config_page')
        );

        add_submenu_page(
            'firebase-push-notifications',
            __('Fix JSON', 'firebase-push-notifications'),
            __('Fix JSON', 'firebase-push-notifications'),
            'manage_options',
            'firebase-fix-json',
            array($this, 'fix_json_page')
        );

        add_submenu_page(
            'firebase-push-notifications',
            __('Diagnostics', 'firebase-push-notifications'),
            __('Diagnostics', 'firebase-push-notifications'),
            'manage_options',
            'firebase-diagnostics',
            array($this, 'diagnostics_page')
        );

        add_submenu_page(
            'firebase-push-notifications',
            __('Debug Test', 'firebase-push-notifications'),
            __('Debug Test', 'firebase-push-notifications'),
            'manage_options',
            'firebase-debug-test',
            array($this, 'debug_test_page')
        );
    }

    /**
     * Admin page
     */
    public function admin_page()
    {
        $firebase_enabled = $this->settings['firebase_enabled'];
        $project_id = $this->settings['firebase_project_id'];

        // Get statistics
        $users_with_tokens = get_users(array(
            'meta_query' => array(
                array(
                    'key' => '_fcm_device_tokens',
                    'compare' => 'EXISTS'
                )
            )
        ));

        $total_tokens = 0;
        foreach ($users_with_tokens as $user) {
            $tokens = get_user_meta($user->ID, '_fcm_device_tokens', true);
            if (is_array($tokens)) {
                $total_tokens += count($tokens);
            }
        }

        // Get guest tokens
        $guest_tokens = get_option('firebase_guest_tokens', array());
        $guest_tokens_count = is_array($guest_tokens) ? count($guest_tokens) : 0;
        $total_tokens += $guest_tokens_count;
?>
        <div class="wrap">
            <h1>🚀 <?php _e('Firebase Push Notifications', 'firebase-push-notifications'); ?></h1>

            <div class="card" style="max-width: 800px;">
                <h2>📊 <?php _e('Statistics', 'firebase-push-notifications'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Firebase Status:', 'firebase-push-notifications'); ?></th>
                        <td>
                            <?php if ($firebase_enabled): ?>
                                <span style="color: green;">✅ <?php _e('Enabled', 'firebase-push-notifications'); ?></span>
                            <?php else: ?>
                                <span style="color: red;">❌ <?php _e('Disabled', 'firebase-push-notifications'); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Project ID:', 'firebase-push-notifications'); ?></th>
                        <td><code><?php echo esc_html($project_id); ?></code></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Users with tokens:', 'firebase-push-notifications'); ?></th>
                        <td><strong><?php echo count($users_with_tokens); ?></strong></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Guest devices:', 'firebase-push-notifications'); ?></th>
                        <td><strong><?php echo $guest_tokens_count; ?></strong></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Total devices:', 'firebase-push-notifications'); ?></th>
                        <td><strong><?php echo $total_tokens; ?></strong></td>
                    </tr>
                </table>
            </div>

            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>🔧 <?php _e('Quick Actions', 'firebase-push-notifications'); ?></h2>
                <p>
                    <a href="<?php echo admin_url('admin.php?page=firebase-configuration'); ?>" class="button button-primary">
                        ⚙️ <?php _e('Firebase Configuration', 'firebase-push-notifications'); ?>
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=firebase-test-notifications'); ?>" class="button">
                        📤 <?php _e('Test Notifications', 'firebase-push-notifications'); ?>
                    </a>
                    <a href="https://console.firebase.google.com/project/<?php echo esc_attr($project_id); ?>/messaging" target="_blank" class="button">
                        🔗 <?php _e('Firebase Console', 'firebase-push-notifications'); ?>
                    </a>
                </p>
            </div>

            <?php if (!$firebase_enabled): ?>
                <div class="notice notice-warning">
                    <p><strong>⚠️ <?php _e('Firebase Push Notifications is disabled!', 'firebase-push-notifications'); ?></strong></p>
                    <p><?php _e('To use the system, you need to enable Firebase in the configuration.', 'firebase-push-notifications'); ?></p>
                    <p><a href="<?php echo admin_url('admin.php?page=firebase-configuration'); ?>" class="button"><?php _e('Go to Configuration', 'firebase-push-notifications'); ?></a></p>
                </div>
            <?php endif; ?>

            <?php if (empty($users_with_tokens)): ?>
                <div class="notice notice-info">
                    <p><strong>ℹ️ <?php _e('No users with registered devices', 'firebase-push-notifications'); ?></strong></p>
                    <p><?php _e('Users need to visit the site and allow notifications in their browser.', 'firebase-push-notifications'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    <?php
    }

    /**
     * Test notifications page
     */
    public function test_notifications_page()
    {
        include FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'admin/test-push-notifications.php';
    }

    /**
     * Firebase configuration page
     */
    public function firebase_config_page()
    {
        include FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'admin/firebase-config.php';
    }

    /**
     * Fix JSON page
     */
    public function fix_json_page()
    {
        include FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'admin/fix-json.php';
    }

    /**
     * Diagnostics page
     */
    public function diagnostics_page()
    {
        include FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'admin/firebase-diagnostics.php';
    }

    /**
     * Debug test page
     */
    public function debug_test_page()
    {
        include FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'admin/firebase-debug-test.php';
    }

    /**
     * Get Firebase configuration
     */
    public function get_firebase_config()
    {
        return array(
            'apiKey' => $this->settings['firebase_api_key'],
            'authDomain' => $this->settings['firebase_project_id'] . '.firebaseapp.com',
            'projectId' => $this->settings['firebase_project_id'],
            'storageBucket' => $this->settings['firebase_project_id'] . '.appspot.com',
            'messagingSenderId' => $this->settings['firebase_messaging_sender_id'],
            'appId' => $this->settings['firebase_app_id'],
            'vapidKey' => $this->settings['firebase_vapid_key'],
        );
    }

    /**
     * Get Firebase configuration via AJAX
     */
    public function get_firebase_config_ajax()
    {
        check_ajax_referer('firebase_push_nonce', 'nonce');

        wp_send_json_success($this->get_firebase_config());
    }

    /**
     * Save FCM token
     */
    public function save_fcm_token()
    {
        check_ajax_referer('firebase_push_nonce', 'nonce');

        $token = sanitize_text_field($_POST['token']);

        if (empty($token)) {
            wp_send_json_error('Token is required');
        }

        if (is_user_logged_in()) {
            // User is logged in - save to user meta
            $user_id = get_current_user_id();

            // Get existing tokens
            $existing_tokens = get_user_meta($user_id, '_fcm_device_tokens', true);
            if (!is_array($existing_tokens)) {
                $existing_tokens = array();
            }

            // Add token if not exists
            if (!in_array($token, $existing_tokens)) {
                $existing_tokens[] = $token;
                update_user_meta($user_id, '_fcm_device_tokens', $existing_tokens);

                // Remove token from guest tokens if it exists there
                $this->remove_guest_token($token);

                wp_send_json_success('Token saved successfully for logged in user');
            } else {
                wp_send_json_success('Token already exists for user');
            }
        } else {
            // User is not logged in - save to guest tokens
            $guest_tokens = get_option('firebase_guest_tokens', array());
            if (!is_array($guest_tokens)) {
                $guest_tokens = array();
            }

            // Add token with timestamp if not exists
            $token_exists = false;
            foreach ($guest_tokens as $guest_token) {
                if ($guest_token['token'] === $token) {
                    $token_exists = true;
                    break;
                }
            }

            if (!$token_exists) {
                $guest_tokens[] = array(
                    'token' => $token,
                    'created_at' => current_time('mysql'),
                    'ip_address' => $this->get_client_ip(),
                    'user_agent' => sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? '')
                );

                update_option('firebase_guest_tokens', $guest_tokens);
                wp_send_json_success('Token saved successfully for guest user');
            } else {
                wp_send_json_success('Token already exists for guest');
            }
        }
    }

    /**
     * Remove token from guest tokens
     */
    private function remove_guest_token($token)
    {
        $guest_tokens = get_option('firebase_guest_tokens', array());
        if (!is_array($guest_tokens)) {
            return;
        }

        $updated_tokens = array();
        foreach ($guest_tokens as $guest_token) {
            if ($guest_token['token'] !== $token) {
                $updated_tokens[] = $guest_token;
            }
        }

        update_option('firebase_guest_tokens', $updated_tokens);
    }

    /**
     * Get client IP address
     */
    private function get_client_ip()
    {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Sync guest token to user when they log in
     */
    public function sync_guest_token_on_login($user_login, $user)
    {
        // This will be called via hook when user logs in
        $guest_tokens = get_option('firebase_guest_tokens', array());
        if (!is_array($guest_tokens) || empty($guest_tokens)) {
            return;
        }

        $user_ip = $this->get_client_ip();
        $user_agent = sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? '');

        // Find tokens that might belong to this user (same IP and user agent)
        $matching_tokens = array();
        $remaining_guest_tokens = array();

        foreach ($guest_tokens as $guest_token) {
            if ($guest_token['ip_address'] === $user_ip && $guest_token['user_agent'] === $user_agent) {
                $matching_tokens[] = $guest_token['token'];
            } else {
                $remaining_guest_tokens[] = $guest_token;
            }
        }

        if (!empty($matching_tokens)) {
            // Get existing user tokens
            $existing_tokens = get_user_meta($user->ID, '_fcm_device_tokens', true);
            if (!is_array($existing_tokens)) {
                $existing_tokens = array();
            }

            // Add matching tokens to user
            foreach ($matching_tokens as $token) {
                if (!in_array($token, $existing_tokens)) {
                    $existing_tokens[] = $token;
                }
            }

            // Update user meta
            update_user_meta($user->ID, '_fcm_device_tokens', $existing_tokens);

            // Update guest tokens (remove the ones we moved)
            update_option('firebase_guest_tokens', $remaining_guest_tokens);

            error_log("Firebase: Synced " . count($matching_tokens) . " guest tokens to user " . $user->ID);
        }
    }

    /**
     * Delete FCM token
     */
    public function delete_fcm_token()
    {
        check_ajax_referer('firebase_push_nonce', 'nonce');

        if (!is_user_logged_in()) {
            wp_die('Unauthorized');
        }

        $token = sanitize_text_field($_POST['token']);
        $user_id = get_current_user_id();

        // Check if admin is deleting token for another user
        if (isset($_POST['user_id']) && current_user_can('manage_options')) {
            $user_id = intval($_POST['user_id']);
        }

        if (empty($token)) {
            wp_send_json_error('Token is required');
        }

        // Get existing tokens
        $existing_tokens = get_user_meta($user_id, '_fcm_device_tokens', true);
        if (!is_array($existing_tokens)) {
            wp_send_json_error('No tokens found');
        }

        // Remove token if exists
        $key = array_search($token, $existing_tokens);
        if ($key !== false) {
            unset($existing_tokens[$key]);
            $existing_tokens = array_values($existing_tokens); // Re-index array

            if (empty($existing_tokens)) {
                delete_user_meta($user_id, '_fcm_device_tokens');
            } else {
                update_user_meta($user_id, '_fcm_device_tokens', $existing_tokens);
            }

            wp_send_json_success('Token deleted successfully');
        } else {
            wp_send_json_error('Token not found');
        }
    }

    /**
     * Save notification preferences
     */
    public function save_notification_preferences()
    {
        check_ajax_referer('firebase_push_nonce', 'nonce');

        if (!is_user_logged_in()) {
            wp_die('Unauthorized');
        }

        $preferences = array(
            'messages' => isset($_POST['messages']) ? (bool) $_POST['messages'] : true,
            'ad_expiration' => isset($_POST['ad_expiration']) ? (bool) $_POST['ad_expiration'] : true,
            'ad_deactivation' => isset($_POST['ad_deactivation']) ? (bool) $_POST['ad_deactivation'] : true,
        );

        update_user_meta(get_current_user_id(), '_notification_preferences', $preferences);

        wp_send_json_success('Preferences saved successfully');
    }

    /**
     * Add Firebase profile fields to admin
     */
    public function add_firebase_profile_fields($user)
    {
        $tokens = get_user_meta($user->ID, '_fcm_device_tokens', true);

        if (empty($tokens) || !is_array($tokens)) {
            return;
        }
    ?>
        <h3><?php _e('Firebase Push Notifications', 'firebase-push-notifications'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Registered Devices', 'firebase-push-notifications'); ?></th>
                <td>
                    <div class="fcm-device-count"><?php echo count($tokens); ?></div>
                    <div class="fcm-tokens-admin">
                        <?php foreach ($tokens as $index => $token): ?>
                            <div class="fcm-token-item">
                                <div class="fcm-token-header">
                                    <strong><?php printf(__('Device %d:', 'firebase-push-notifications'), $index + 1); ?></strong>
                                    <div class="fcm-token-actions">
                                        <button type="button" class="button button-small copy-fcm-token" data-token="<?php echo esc_attr($token); ?>">
                                            <?php _e('Copy', 'firebase-push-notifications'); ?>
                                        </button>
                                        <button type="button" class="button button-small button-link-delete delete-fcm-token" data-token="<?php echo esc_attr($token); ?>" data-user-id="<?php echo $user->ID; ?>">
                                            <?php _e('Delete', 'firebase-push-notifications'); ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="fcm-token-value">
                                    <code><?php echo esc_html(substr($token, 0, 50) . '...'); ?></code>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </td>
            </tr>
        </table>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Copy token functionality
                const copyButtons = document.querySelectorAll('.copy-fcm-token');
                copyButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        const token = this.getAttribute('data-token');
                        navigator.clipboard.writeText(token).then(function() {
                            button.textContent = '<?php _e('Copied!', 'firebase-push-notifications'); ?>';
                            setTimeout(function() {
                                button.textContent = '<?php _e('Copy', 'firebase-push-notifications'); ?>';
                            }, 2000);
                        });
                    });
                });

                // Delete token functionality
                const deleteButtons = document.querySelectorAll('.delete-fcm-token');
                deleteButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        const token = this.getAttribute('data-token');
                        const userId = this.getAttribute('data-user-id');

                        if (!confirm('<?php _e('Are you sure you want to delete this device token? This will stop push notifications on this device.', 'firebase-push-notifications'); ?>')) {
                            return;
                        }

                        button.disabled = true;
                        button.textContent = '<?php _e('Deleting...', 'firebase-push-notifications'); ?>';

                        const formData = new FormData();
                        formData.append('action', 'delete_fcm_token');
                        formData.append('token', token);
                        formData.append('user_id', userId);
                        formData.append('nonce', '<?php echo wp_create_nonce('firebase_push_nonce'); ?>');

                        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const tokenItem = button.closest('.fcm-token-item');
                                    tokenItem.style.opacity = '0.5';
                                    tokenItem.style.transition = 'opacity 0.3s ease';
                                    setTimeout(() => {
                                        tokenItem.remove();
                                        const deviceCountElement = document.querySelector('.fcm-device-count');
                                        if (deviceCountElement) {
                                            deviceCountElement.textContent = parseInt(deviceCountElement.textContent) - 1;
                                        }
                                        if (document.querySelectorAll('.fcm-token-item').length === 0) {
                                            window.location.reload();
                                        }
                                    }, 300);
                                } else {
                                    alert('<?php _e('Error deleting token:', 'firebase-push-notifications'); ?> ' + (data.data || '<?php _e('Unknown error', 'firebase-push-notifications'); ?>'));
                                    button.disabled = false;
                                    button.textContent = '<?php _e('Delete', 'firebase-push-notifications'); ?>';
                                }
                            })
                            .catch(error => {
                                alert('<?php _e('Error deleting token:', 'firebase-push-notifications'); ?> ' + error.message);
                                button.disabled = false;
                                button.textContent = '<?php _e('Delete', 'firebase-push-notifications'); ?>';
                            });
                    });
                });
            });
        </script>
<?php
    }

    /**
     * Handle listing status change
     */
    public function handle_listing_status_change($listing_id, $new_status)
    {
        if ($new_status === 'inactive') {
            $this->notification_handler->handle_listing_deactivation($listing_id);
        }
    }

    /**
     * Handle listing expiration
     */
    public function handle_listing_expired($listing_id)
    {
        $this->notification_handler->handle_listing_expiration($listing_id);
    }

    /**
     * Handle new message
     */
    public function handle_new_message()
    {
        // This will be called before the actual message sending
        // We'll hook into the message creation process
    }

    /**
     * Send notification for new message to recipient
     * 
     * @param int $recipient_id User ID of message recipient
     * @param string $sender_name Name of message sender
     * @param string $message_preview Preview of message content
     */
    public function send_new_message_notification($recipient_id, $sender_name = '', $message_preview = '')
    {
        if (!$this->settings['firebase_enabled']) {
            return false;
        }

        if (!$this->notification_handler) {
            return false;
        }

        $recipient_user = get_user_by('id', $recipient_id);
        if (!$recipient_user) {
            return false;
        }

        $title = 'New Message';
        if (!empty($sender_name)) {
            $title = 'New message from ' . sanitize_text_field($sender_name);
        }

        $body = 'You have received a new message';
        if (!empty($message_preview)) {
            $preview = sanitize_text_field(substr($message_preview, 0, 100));
            $body = $preview;
        }

        $notification_data = array(
            'title' => $title,
            'body' => $body,
            'icon' => get_site_icon_url(),
            'badge' => get_site_icon_url(),
            'notification_type' => 'new_message',
            'action_url' => home_url('/my-dashboard/?directory_action=messages'),
        );

        // Send notification to recipient
        return $this->notification_handler->send_notification_to_user(
            $recipient_id,
            $notification_data
        );
    }

    /**
     * Generic hook to send push notification
     * Used by theme to send custom notifications
     * 
     * @param int $user_id User ID to send notification to
     * @param array $notification_data Notification data array
     * @return bool Success or failure
     */
    public function send_push_notification($user_id, $notification_data = array())
    {
        if (!$this->settings['firebase_enabled']) {
            return false;
        }

        if (!$this->notification_handler) {
            return false;
        }

        $user = get_user_by('id', $user_id);
        if (!$user) {
            return false;
        }

        // Set defaults
        $defaults = array(
            'title' => 'Notification',
            'body' => '',
            'icon' => get_site_icon_url(),
            'badge' => get_site_icon_url(),
            'notification_type' => 'general',
            'action_url' => home_url(),
        );

        $notification_data = array_merge($defaults, (array)$notification_data);

        // Send notification
        return $this->notification_handler->send_notification_to_user(
            $user_id,
            $notification_data
        );
    }

    /**
     * Handle DirectoryPress message sent event
     */
    public function on_directorypress_message_sent($message_id, $recipient_id, $sender_id)
    {
        // Try to get message content
        $message_content = '';

        try {
            // Get current user name
            $sender = get_user_by('id', $sender_id);
            $sender_name = $sender ? $sender->display_name : 'Someone';

            // Try to get message from different possible sources
            if (isset($_POST['message_content'])) {
                $message_content = sanitize_textarea_field($_POST['message_content']);
            }

            // Send notification
            $this->send_new_message_notification($recipient_id, $sender_name, $message_content);
        } catch (Exception $e) {
            error_log('Firebase: Error sending message notification - ' . $e->getMessage());
        }
    }

    /**
     * Handle comment created (for message notifications)
     * This catches DirectoryPress messages that are stored as comments
     */
    public function on_comment_created($comment_id, $comment)
    {
        // Check if this is a DirectoryPress message (comments with type 'message')
        $comment_type = get_comment_meta($comment_id, 'comment_type', true);

        if ($comment_type === 'message' || $comment->comment_type === 'message') {
            $recipient_id = $comment->user_id;
            $sender_id = intval($comment->user_id);

            // Only send if recipient is different from sender
            if ($recipient_id && $recipient_id !== $sender_id) {
                try {
                    $sender = get_user_by('id', $sender_id);
                    $sender_name = $sender ? $sender->display_name : 'Someone';
                    $message_content = $comment->comment_content;

                    $this->send_new_message_notification($recipient_id, $sender_name, $message_content);
                } catch (Exception $e) {
                    error_log('Firebase: Error sending comment notification - ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Schedule guest token cleanup
     */
    public function schedule_guest_token_cleanup()
    {
        if (!wp_next_scheduled('firebase_cleanup_guest_tokens')) {
            wp_schedule_event(time(), 'daily', 'firebase_cleanup_guest_tokens');
        }
    }

    /**
     * Cleanup old guest tokens (older than 30 days)
     */
    public function cleanup_old_guest_tokens()
    {
        $guest_tokens = get_option('firebase_guest_tokens', array());
        if (!is_array($guest_tokens) || empty($guest_tokens)) {
            return;
        }

        $cutoff_date = date('Y-m-d H:i:s', strtotime('-30 days'));
        $cleaned_tokens = array();
        $removed_count = 0;

        foreach ($guest_tokens as $guest_token) {
            if (isset($guest_token['created_at']) && $guest_token['created_at'] > $cutoff_date) {
                $cleaned_tokens[] = $guest_token;
            } else {
                $removed_count++;
            }
        }

        if ($removed_count > 0) {
            update_option('firebase_guest_tokens', $cleaned_tokens);
            error_log("Firebase: Cleaned up {$removed_count} old guest tokens");
        }
    }
}
