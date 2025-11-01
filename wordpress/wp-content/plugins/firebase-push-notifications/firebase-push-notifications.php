<?php

/**
 * Plugin Name: Firebase Push Notifications
 * Plugin URI: https://github.com/vitaliy-karakushan/firebase-push-notifications
 * Description: Firebase Cloud Messaging integration for WordPress with push notifications support for messages, ad expiration, and ad deactivation.
 * Version: 1.0.0
 * Author: Vitaliy Karakushan
 * Author URI: https://github.com/vitaliy-karakushan
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: firebase-push-notifications
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FIREBASE_PUSH_NOTIFICATIONS_VERSION', '1.0.0');
define('FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_FILE', __FILE__);
define('FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoloader for Composer dependencies
if (file_exists(FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'vendor/autoload.php';
}

// Load plugin classes
$class_files = array(
    'includes/class-firebase-manager.php',
    'includes/class-notification-handler.php',
    'includes/class-firebase-push-notifications.php'
);

foreach ($class_files as $class_file) {
    $file_path = FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . $class_file;
    if (file_exists($file_path)) {
        require_once $file_path;
    } else {
        error_log('Firebase Push Notifications: Class file not found: ' . $file_path);
    }
}

/**
 * Main plugin class
 */
class Firebase_Push_Notifications_Plugin
{

    /**
     * Plugin instance
     */
    private static $instance = null;

    /**
     * Firebase Push Notifications instance
     */
    private $firebase_push_notifications = null;

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
        // Initialize Firebase Push Notifications
        $this->firebase_push_notifications = Firebase_Push_Notifications::getInstance();

        // Plugin activation/deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        // Load text domain
        add_action('plugins_loaded', array($this, 'load_textdomain'));

        // Admin notices
        add_action('admin_notices', array($this, 'admin_notices'));
    }

    /**
     * Plugin activation
     */
    public function activate()
    {
        // Check PHP version
        if (version_compare(PHP_VERSION, '7.4', '<')) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(
                __('Firebase Push Notifications requires PHP 7.4 or higher.', 'firebase-push-notifications'),
                __('Plugin Activation Error', 'firebase-push-notifications'),
                array('back_link' => true)
            );
        }

        // Check WordPress version
        if (version_compare(get_bloginfo('version'), '5.0', '<')) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(
                __('Firebase Push Notifications requires WordPress 5.0 or higher.', 'firebase-push-notifications'),
                __('Plugin Activation Error', 'firebase-push-notifications'),
                array('back_link' => true)
            );
        }

        // Create database tables if needed
        $this->create_tables();

        // Set default options
        $this->set_default_options();

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate()
    {
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Load plugin text domain
     */
    public function load_textdomain()
    {
        load_plugin_textdomain(
            'firebase-push-notifications',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }

    /**
     * Admin notices
     */
    public function admin_notices()
    {
        // Check if Composer dependencies are installed
        if (!file_exists(FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'vendor/autoload.php')) {
?>
            <div class="notice notice-error">
                <p>
                    <strong><?php _e('Firebase Push Notifications:', 'firebase-push-notifications'); ?></strong>
                    <?php _e('Composer dependencies are not installed. Please run "composer install" in the plugin directory.', 'firebase-push-notifications'); ?>
                </p>
            </div>
        <?php
        }

        // Check if Firebase classes are loaded
        if (!class_exists('FirebaseManager')) {
        ?>
            <div class="notice notice-error">
                <p>
                    <strong><?php _e('Firebase Push Notifications:', 'firebase-push-notifications'); ?></strong>
                    <?php _e('Firebase classes are not loaded. Please check plugin files and try reactivating the plugin.', 'firebase-push-notifications'); ?>
                </p>
            </div>
        <?php
            return;
        }

        // Check if Firebase is configured
        $firebase_manager = FirebaseManager::getInstance();
        if (!$firebase_manager->isInitialized()) {
        ?>
            <div class="notice notice-warning">
                <p>
                    <strong><?php _e('Firebase Push Notifications:', 'firebase-push-notifications'); ?></strong>
                    <?php _e('Firebase is not configured. Please go to', 'firebase-push-notifications'); ?>
                    <a href="<?php echo admin_url('admin.php?page=firebase-configuration'); ?>">
                        <?php _e('Firebase Configuration', 'firebase-push-notifications'); ?>
                    </a>
                    <?php _e('to set up Firebase.', 'firebase-push-notifications'); ?>
                </p>
            </div>
<?php
        }
    }

    /**
     * Create database tables
     */
    private function create_tables()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Notification logs table
        $table_name = $wpdb->prefix . 'firebase_notification_logs';

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            notification_type varchar(50) NOT NULL,
            title varchar(255) NOT NULL,
            body text NOT NULL,
            data longtext,
            status varchar(20) NOT NULL,
            sent_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY notification_type (notification_type),
            KEY status (status),
            KEY sent_at (sent_at)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Store database version
        update_option('firebase_push_notifications_db_version', '1.0');
    }

    /**
     * Set default options
     */
    private function set_default_options()
    {
        $default_options = array(
            'firebase_enabled' => false,
            'firebase_project_id' => '',
            'firebase_api_key' => '',
            'firebase_messaging_sender_id' => '',
            'firebase_app_id' => '',
            'firebase_vapid_key' => '',
            'firebase_service_account_json' => '',
            'firebase_notification_icon' => '',
            'firebase_notification_badge' => '',
        );

        foreach ($default_options as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
    }
}

// Initialize plugin
Firebase_Push_Notifications_Plugin::getInstance();

// Load test functionality in development
if (defined('WP_DEBUG') && WP_DEBUG && isset($_GET['test_guest_notifications'])) {
    add_action('init', function () {
        require_once FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'test-guest-notifications.php';
    });
}

/**
 * Helper function to send push notification
 * Usage: fpn_send_push($user_id, $notification_data)
 * 
 * @param int $user_id User ID to send notification to
 * @param array $notification_data {
 *     @type string $title Notification title
 *     @type string $body Notification body text
 *     @type string $icon Icon URL
 *     @type string $badge Badge URL
 *     @type string $notification_type Type of notification
 *     @type string $action_url URL to open on click
 * }
 * @return bool Success or failure
 * 
 * phpcs:disable WordPress.PHP.StrictInArray.MissingTrueStrict
 */
function fpn_send_push($user_id, $notification_data = array())
{
    global $firebase_notifications_instance;

    // Validate input
    if (empty($user_id) || !is_numeric($user_id)) {
        return false;
    }

    // Get the notification handler if not already stored
    if (!isset($firebase_notifications_instance)) {
        if (!class_exists('Firebase_Push_Notifications')) {
            return false;
        }
        $firebase_notifications_instance = Firebase_Push_Notifications::getInstance();
    }

    if (!$firebase_notifications_instance) {
        return false;
    }

    // Check if method exists before calling
    if (!method_exists($firebase_notifications_instance, 'send_push_notification')) {
        error_log('Firebase Push Notifications: Method send_push_notification does not exist');
        return false;
    }

    return $firebase_notifications_instance->send_push_notification($user_id, $notification_data);
}
// phpcs:enable