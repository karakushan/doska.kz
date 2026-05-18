<?php
/**
 * Notification Settings Template
 * Template for Firebase push notification settings page
 */

if (!defined('ABSPATH')) {
    exit;
}

// Handle form submission
if (isset($_POST['save_notification_preferences']) && wp_verify_nonce($_POST['_wpnonce'], 'firebase_notification_preferences')) {
    $preferences = array(
        'messages' => isset($_POST['messages']) ? (bool) $_POST['messages'] : false,
        'ad_expiration' => isset($_POST['ad_expiration']) ? (bool) $_POST['ad_expiration'] : false,
        'ad_deactivation' => isset($_POST['ad_deactivation']) ? (bool) $_POST['ad_deactivation'] : false
    );
    
    update_user_meta(get_current_user_id(), '_notification_preferences', $preferences);
    
    echo '<div class="alert alert-success">' . __('Notification preferences saved successfully!', 'firebase-push-notifications') . '</div>';
}

// Get current preferences
$user_id = get_current_user_id();
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
        <div class="fcm-tokens-section">
            <h4><?php _e('Registered FCM Tokens', 'firebase-push-notifications'); ?></h4>
            <p><?php _e('These are the device tokens registered for push notifications:', 'firebase-push-notifications'); ?></p>
            
            <div class="tokens-list">
                <?php foreach ($tokens as $index => $token): ?>
                    <div class="token-item">
                        <div class="token-header">
                            <span class="token-label"><?php printf(__('Device %d:', 'firebase-push-notifications'), $index + 1); ?></span>
                            <div class="token-actions">
                                <button type="button" class="btn-copy-token" data-token="<?php echo esc_attr($token); ?>">
                                    <?php _e('Copy', 'firebase-push-notifications'); ?>
                                </button>
                                <button type="button" class="btn-delete-token" data-token="<?php echo esc_attr($token); ?>" data-index="<?php echo $index; ?>">
                                    <?php _e('Delete', 'firebase-push-notifications'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="token-value" id="token-<?php echo $index; ?>">
                            <?php echo esc_html(substr($token, 0, 50)) . '...'; ?>
                        </div>
                        <div class="token-full" style="display: none;">
                            <?php echo esc_html($token); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="notification-help">
            <h4><?php _e('Help', 'firebase-push-notifications'); ?></h4>
            <ul>
                <li><?php _e('Push notifications will be sent to all your registered devices', 'firebase-push-notifications'); ?></li>
                <li><?php _e('You can disable specific types of notifications using the toggles above', 'firebase-push-notifications'); ?></li>
                <li><?php _e('To stop receiving notifications completely, disable them in your browser settings', 'firebase-push-notifications'); ?></li>
                <li><?php _e('FCM tokens are automatically generated when you allow notifications in your browser', 'firebase-push-notifications'); ?></li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy token functionality
    const copyButtons = document.querySelectorAll('.btn-copy-token');
    
    copyButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const token = this.getAttribute('data-token');
            
            // Copy to clipboard
            navigator.clipboard.writeText(token).then(function() {
                // Show success message
                const originalText = button.textContent;
                button.textContent = '<?php _e('Copied!', 'firebase-push-notifications'); ?>';
                button.style.backgroundColor = '#28a745';
                
                // Reset after 2 seconds
                setTimeout(function() {
                    button.textContent = originalText;
                    button.style.backgroundColor = '';
                }, 2000);
            }).catch(function(err) {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = token;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                // Show success message
                const originalText = button.textContent;
                button.textContent = '<?php _e('Copied!', 'firebase-push-notifications'); ?>';
                button.style.backgroundColor = '#28a745';
                
                // Reset after 2 seconds
                setTimeout(function() {
                    button.textContent = originalText;
                    button.style.backgroundColor = '';
                }, 2000);
            });
        });
    });
    
    // Toggle full token display
    const tokenValues = document.querySelectorAll('.token-value');
    
    tokenValues.forEach(function(element) {
        element.addEventListener('click', function() {
            const tokenFull = this.nextElementSibling;
            if (tokenFull.style.display === 'none') {
                tokenFull.style.display = 'block';
                this.style.display = 'none';
            } else {
                tokenFull.style.display = 'none';
                this.style.display = 'block';
            }
        });
    });
    
    // Delete token functionality
    const deleteButtons = document.querySelectorAll('.btn-delete-token');
    
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const token = this.getAttribute('data-token');
            const tokenIndex = this.getAttribute('data-index');
            
            // Confirm deletion
            if (!confirm('<?php _e('Are you sure you want to delete this device token? This will stop push notifications on this device.', 'firebase-push-notifications'); ?>')) {
                return;
            }
            
            // Disable button during request
            button.disabled = true;
            button.textContent = '<?php _e('Deleting...', 'firebase-push-notifications'); ?>';
            
            // Send AJAX request
            const formData = new FormData();
            formData.append('action', 'delete_fcm_token');
            formData.append('token', token);
            formData.append('nonce', '<?php echo wp_create_nonce('firebase_push_nonce'); ?>');
            
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    // Remove token item from DOM
                    const tokenItem = button.closest('.token-item');
                    tokenItem.style.opacity = '0.5';
                    tokenItem.style.transition = 'opacity 0.3s ease';
                    
                    setTimeout(function() {
                        tokenItem.remove();
                        
                        // Update device count
                        const deviceCountElement = document.querySelector('.status-value');
                        if (deviceCountElement) {
                            const currentCount = parseInt(deviceCountElement.textContent);
                            deviceCountElement.textContent = currentCount - 1;
                        }
                        
                        // Check if no tokens left
                        const remainingTokens = document.querySelectorAll('.token-item');
                        if (remainingTokens.length === 0) {
                            // Reload page to update interface
                            window.location.reload();
                        }
                    }, 300);
                } else {
                    // Show error message
                    alert('<?php _e('Error deleting token:', 'firebase-push-notifications'); ?> ' + (data.data || '<?php _e('Unknown error', 'firebase-push-notifications'); ?>'));
                    
                    // Reset button
                    button.disabled = false;
                    button.textContent = '<?php _e('Delete', 'firebase-push-notifications'); ?>';
                }
            })
            .catch(function(error) {
                // Show error message
                alert('<?php _e('Error deleting token:', 'firebase-push-notifications'); ?> ' + error.message);
                
                // Reset button
                button.disabled = false;
                button.textContent = '<?php _e('Delete', 'firebase-push-notifications'); ?>';
            });
        });
    });
});
</script>
