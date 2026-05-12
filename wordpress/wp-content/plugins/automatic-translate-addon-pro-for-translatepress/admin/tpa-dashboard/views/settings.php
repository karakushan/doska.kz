<?php
// Security: Validate text domain
if (!isset($text_domain) || empty($text_domain)) {
    $text_domain = 'tpap'; // Default fallback
}
$text_domain = sanitize_text_field($text_domain);

// Initialize variables
$settings_updated = false;
$settings_error = false;
$error_message = '';

// Process form submission with proper security checks
if (isset($_SERVER['REQUEST_METHOD']) && sanitize_text_field($_SERVER['REQUEST_METHOD']) === 'POST') {
    
    // First: Check if user has proper capabilities
    if (!current_user_can('manage_options')) {
        $settings_error = true;
        $error_message = esc_html__('You do not have sufficient permissions to perform this action.', 'tpap');
    }
    // Second: Verify nonce for CSRF protection
    elseif (!isset($_POST['tpap_optin_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tpap_optin_nonce'])), 'tpap_save_optin_settings')) {
        $settings_error = true;
        $error_message = esc_html__('Security check failed. Please try again.', 'tpap');
    }
    // Third: Process the form if all security checks pass
    else {
        try {
            // Handle feedback checkbox
            $opt_in_choice = sanitize_text_field(get_option('cpfm_opt_in_choice_cool_translations', ''));
            if (!empty($opt_in_choice) && in_array($opt_in_choice, ['yes', 'true', '1'], true)) {
                $feedback_opt_in = isset($_POST['tpap-dashboard-feedback-checkbox']) ? 'yes' : 'no';
                
                // Sanitize and update the option
                $feedback_opt_in = sanitize_text_field($feedback_opt_in);
                update_option('tpa_feedback_opt_in', $feedback_opt_in);
                
                // Handle cron job based on user preference with security validation
                $cron_hook = sanitize_key('tpap_extra_data_update');
                if ($feedback_opt_in === 'no' && wp_next_scheduled($cron_hook)) {
                    wp_clear_scheduled_hook($cron_hook);
                }
                
                if ($feedback_opt_in === 'yes' && !wp_next_scheduled($cron_hook)) {
                    // Security: Validate cron interval exists
                    $interval = 'every_30_days';
                    $schedules = wp_get_schedules();
                    if (isset($schedules[$interval])) {
                        wp_schedule_event(time(), $interval, $cron_hook);
                        
                        // Security: Validate class and method exist before calling
                        if (class_exists('Tpap_cronjob') && method_exists('Tpap_cronjob', 'tpap_send_data')) {
                            Tpap_cronjob::tpap_send_data();
                        }
                    }
                }
                
                $settings_updated = true;
            }
        } catch (Exception $e) {
            $settings_error = true;
            $error_message = esc_html__('An error occurred while saving settings. Please try again.', 'tpap');
            
            // Log the error for debugging (only in debug mode)
            if ( WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ) {
                error_log('TPAP Settings Error: ' . sanitize_text_field($e->getMessage()));
            }
        }
    }
}
?>
<div class="tpap-dashboard-settings">
    <div class="tpap-dashboard-settings-container">
        <div class="header">
            <h1><?php esc_html_e('Settings', $text_domain); ?></h1>
        </div>
        
        <?php if ($settings_updated): ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e('Settings saved successfully!', 'tpap'); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($settings_error): ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html($error_message); ?></p>
            </div>
        <?php endif; ?>
        
        <p><?php esc_html_e('Configure your AI translation settings to customize how your content is translated.', $text_domain); ?></p>

        <form method="post">
            <?php wp_nonce_field(sanitize_key('tpap_save_optin_settings'), sanitize_key('tpap_optin_nonce')); ?>
            <?php 
            $display_opt_in = sanitize_text_field(get_option('cpfm_opt_in_choice_cool_translations', ''));
            if (!empty($display_opt_in) && in_array($display_opt_in, ['yes', 'true', '1'], true)) : 
            ?>
                <div class="tpap-dashboard-feedback-container">
                    <div class="feedback-row">
                        <input type="checkbox" 
                            id="tpap-dashboard-feedback-checkbox" 
                            name="tpap-dashboard-feedback-checkbox"
                            <?php 
                            $current_feedback_opt_in = sanitize_text_field(get_option('tpa_feedback_opt_in', ''));
                            checked($current_feedback_opt_in, 'yes'); 
                            ?>>
                        <p><?php esc_html_e('Help us make this plugin more compatible with your site by sharing non-sensitive site data.', $text_domain); ?></p>
                        <a href="#" class="tpap-see-terms"><?php esc_html_e('[See terms]', $text_domain); ?></a>
                    </div>
                    <div id="termsBox" style="display: none;padding-left: 20px; margin-top: 10px; font-size: 12px; color: #999;">
                        <p><?php esc_html_e("Opt in to receive email updates about security improvements, new features, helpful tutorials, and occasional special offers. We'll collect:", $text_domain); ?> <a href="https://my.example.net/terms/usage-tracking/" target="_blank"><?php esc_html_e('Click here', $text_domain); ?></a></p>
                        <ul style="list-style-type:auto;">
                            <li><?php esc_html_e('Your website home URL and WordPress admin email.', $text_domain); ?></li>
                            <li><?php esc_html_e('To check plugin compatibility, we will collect the following: list of active plugins and themes, server type, MySQL version, WordPress version, memory limit, site language and database prefix.', $text_domain); ?></li>
                        </ul>
                    </div>
                    <div class="tpap-dashboard-save-settings">
                        <button type="submit" class="tpap-dashboard-btn primary save-settings-btn">
                            <?php esc_html_e('Save', $text_domain); ?>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>