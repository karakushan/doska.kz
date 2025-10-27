<?php
/**
 * User Settings Functions
 * Handles user notification preferences and settings
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add notification settings to user dashboard
 */
function firebase_add_notification_settings_to_dashboard() {
    add_action('dashboard_panel_html', 'firebase_render_notification_settings_menu');
}

/**
 * Render notification settings menu item
 */
function firebase_render_notification_settings_menu() {
    ?>
    <li class="">
        <a class="parent-menu-link" href="#">
            <i class="dicode-material-icons dicode-material-icons-bell-outline"></i>
            <span><?php _e('Notifications', 'firebase-push-notifications'); ?></span>
        </a>
        <ul class="submenu">
            <li class="">
                <a href="<?php echo directorypress_dashboardUrl(array('directory_action' => 'notification_settings')); ?>" data-bs-target="notification_settings">
                    <i class="fa fa-bell"></i>
                    <?php echo esc_html__('Notification Settings', 'firebase-push-notifications'); ?>
                </a>
            </li>
        </ul>
    </li>
    <?php
}

/**
 * Handle notification settings page
 */
function firebase_handle_notification_settings_page() {
    if (!is_user_logged_in()) {
        return;
    }
    
    $user_id = get_current_user_id();
    
    // Handle form submission
    if (isset($_POST['save_notification_preferences']) && wp_verify_nonce($_POST['_wpnonce'], 'firebase_notification_preferences')) {
        $preferences = array(
            'messages' => isset($_POST['messages']) ? (bool) $_POST['messages'] : false,
            'ad_expiration' => isset($_POST['ad_expiration']) ? (bool) $_POST['ad_expiration'] : false,
            'ad_deactivation' => isset($_POST['ad_deactivation']) ? (bool) $_POST['ad_deactivation'] : false
        );
        
        update_user_meta($user_id, '_notification_preferences', $preferences);
        
        echo '<div class="alert alert-success">' . __('Notification preferences saved successfully!', 'firebase-push-notifications') . '</div>';
    }
    
    // Get current preferences
    $preferences = get_user_meta($user_id, '_notification_preferences', true);
    if (empty($preferences) || !is_array($preferences)) {
        $preferences = array(
            'messages' => true,
            'ad_expiration' => true,
            'ad_deactivation' => true
        );
    }
    
    // Get FCM tokens count
    $tokens = get_user_meta($user_id, '_fcm_device_tokens', true);
    $token_count = is_array($tokens) ? count($tokens) : 0;
    
    ?>
    <div class="firebase-notification-settings">
        <div class="panel-content-header">
            <h3><?php _e('Notification Settings', 'firebase-push-notifications'); ?></h3>
            <p><?php _e('Manage your push notification preferences', 'firebase-push-notifications'); ?></p>
        </div>
        
        <div class="notification-status">
            <div class="status-item">
                <span class="status-label"><?php _e('Registered Devices:', 'firebase-push-notifications'); ?></span>
                <span class="status-value"><?php echo $token_count; ?></span>
            </div>
            <div class="status-item">
                <span class="status-label"><?php _e('Notifications Status:', 'firebase-push-notifications'); ?></span>
                <span class="status-value <?php echo $token_count > 0 ? 'enabled' : 'disabled'; ?>">
                    <?php echo $token_count > 0 ? __('Enabled', 'firebase-push-notifications') : __('Disabled', 'firebase-push-notifications'); ?>
                </span>
            </div>
        </div>
        
        <?php if ($token_count === 0): ?>
            <div class="alert alert-warning">
                <strong><?php _e('Notifications are disabled', 'firebase-push-notifications'); ?></strong><br>
                <?php _e('To receive push notifications, please allow notifications in your browser when prompted.', 'firebase-push-notifications'); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="">
            <?php wp_nonce_field('firebase_notification_preferences'); ?>
            
            <div class="notification-preferences">
                <h4><?php _e('Notification Types', 'firebase-push-notifications'); ?></h4>
                
                <div class="preference-item">
                    <label class="toggle-switch">
                        <input type="checkbox" name="messages" value="1" <?php checked($preferences['messages']); ?> <?php disabled($token_count === 0); ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <div class="preference-info">
                        <h5><?php _e('New Messages', 'firebase-push-notifications'); ?></h5>
                        <p><?php _e('Receive notifications when someone sends you a message', 'firebase-push-notifications'); ?></p>
                    </div>
                </div>
                
                <div class="preference-item">
                    <label class="toggle-switch">
                        <input type="checkbox" name="ad_expiration" value="1" <?php checked($preferences['ad_expiration']); ?> <?php disabled($token_count === 0); ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <div class="preference-info">
                        <h5><?php _e('Ad Expiration', 'firebase-push-notifications'); ?></h5>
                        <p><?php _e('Receive notifications when your paid advertisement expires', 'firebase-push-notifications'); ?></p>
                    </div>
                </div>
                
                <div class="preference-item">
                    <label class="toggle-switch">
                        <input type="checkbox" name="ad_deactivation" value="1" <?php checked($preferences['ad_deactivation']); ?> <?php disabled($token_count === 0); ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <div class="preference-info">
                        <h5><?php _e('Ad Deactivation', 'firebase-push-notifications'); ?></h5>
                        <p><?php _e('Receive notifications when your listing is deactivated by administrators', 'firebase-push-notifications'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="save_notification_preferences" class="btn btn-primary" <?php disabled($token_count === 0); ?>>
                    <?php _e('Save Preferences', 'firebase-push-notifications'); ?>
                </button>
            </div>
        </form>
        
        <?php if ($token_count > 0): ?>
            <div class="notification-help">
                <h4><?php _e('Help', 'firebase-push-notifications'); ?></h4>
                <ul>
                    <li><?php _e('Push notifications will be sent to all your registered devices', 'firebase-push-notifications'); ?></li>
                    <li><?php _e('You can disable specific types of notifications using the toggles above', 'firebase-push-notifications'); ?></li>
                    <li><?php _e('To stop receiving notifications completely, disable them in your browser settings', 'firebase-push-notifications'); ?></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Add notification settings to DirectoryPress dashboard
 */
function firebase_add_to_directorypress_dashboard() {
    add_action('directorypress_dashboard_content_notification_settings', 'firebase_handle_notification_settings_page');
}

/**
 * Get user notification preferences
 * 
 * @param int $user_id User ID
 * @return array Preferences
 */
function firebase_get_user_notification_preferences($user_id) {
    $preferences = get_user_meta($user_id, '_notification_preferences', true);
    
    if (empty($preferences) || !is_array($preferences)) {
        return array(
            'messages' => true,
            'ad_expiration' => true,
            'ad_deactivation' => true
        );
    }
    
    return $preferences;
}

/**
 * Check if user has notifications enabled for specific type
 * 
 * @param int $user_id User ID
 * @param string $notification_type Notification type
 * @return bool
 */
function firebase_is_notification_enabled($user_id, $notification_type) {
    $preferences = firebase_get_user_notification_preferences($user_id);
    
    switch ($notification_type) {
        case 'message':
            return isset($preferences['messages']) ? $preferences['messages'] : true;
        case 'ad_expiration':
            return isset($preferences['ad_expiration']) ? $preferences['ad_expiration'] : true;
        case 'ad_deactivation':
            return isset($preferences['ad_deactivation']) ? $preferences['ad_deactivation'] : true;
        default:
            return true;
    }
}

/**
 * Get user's FCM tokens
 * 
 * @param int $user_id User ID
 * @return array FCM tokens
 */
function firebase_get_user_fcm_tokens($user_id) {
    $tokens = get_user_meta($user_id, '_fcm_device_tokens', true);
    return is_array($tokens) ? $tokens : array();
}

/**
 * Add FCM token to user
 * 
 * @param int $user_id User ID
 * @param string $token FCM token
 * @return bool Success status
 */
function firebase_add_user_fcm_token($user_id, $token) {
    $tokens = firebase_get_user_fcm_tokens($user_id);
    
    if (!in_array($token, $tokens)) {
        $tokens[] = $token;
        return update_user_meta($user_id, '_fcm_device_tokens', $tokens);
    }
    
    return true;
}

/**
 * Remove FCM token from user
 * 
 * @param int $user_id User ID
 * @param string $token FCM token
 * @return bool Success status
 */
function firebase_remove_user_fcm_token($user_id, $token) {
    $tokens = firebase_get_user_fcm_tokens($user_id);
    $tokens = array_diff($tokens, array($token));
    
    return update_user_meta($user_id, '_fcm_device_tokens', $tokens);
}

// Initialize dashboard integration
add_action('init', 'firebase_add_notification_settings_to_dashboard');
add_action('init', 'firebase_add_to_directorypress_dashboard');
