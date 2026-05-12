<?php
/**
 * Renders the license activation page
 */
function tpap_render_license_page() {
    $text_domain = sanitize_text_field('tpap');
    
    // Security: Properly sanitize email from database
    $purchase_email = get_option('TranslatepressAutomaticTranslateAddonPro_lic_email', get_bloginfo('admin_email'));
    $purchase_email = sanitize_email($purchase_email);
    if (!is_email($purchase_email)) {
        $purchase_email = sanitize_email(get_bloginfo('admin_email'));
    }
    
    // Escape early for security
    $admin_url = esc_url(admin_url('admin-post.php'));
    $purchase_email_escaped = esc_attr($purchase_email);
    ?>
    <div class="tpap-dashboard-license">
        <div class="tpap-dashboard-license-container">
            <div class="header">
                <h1>🔑 Activated by Gobyv</h1>
            </div>
        </div>
    </div>
<?php
}

/**
 * Renders the license information page for Pro users
 * 
 * @param object|null $license_info License information object
 */
function tpap_render_license_page_pro($license_info = null) {
    $text_domain = sanitize_text_field('tpap');
    
    // Early return if invalid license info
    if (!$license_info) {
        $license_info = AutomaticTranslateAddonForTranslatePressBase::GetRegisterInfo();
    }

    if (!is_object($license_info) || !isset($license_info->is_valid) || !isset($license_info->license_title) || !isset($license_info->expire_date)) {
        wp_die(esc_html__('Error: Invalid license information', $text_domain));
        return;
    }
    
    // Security: Additional sanitization for license info properties
    $license_info->license_title = sanitize_text_field($license_info->license_title);
    $license_info->expire_date = sanitize_text_field($license_info->expire_date);
    
    // Security: Properly sanitize license key from database and mask securely
    $license_key = sanitize_text_field(get_option('TranslatepressAutomaticTranslateAddonPro_lic_Key', ''));
    $masked_key = '';
    if (!empty($license_key) && strlen($license_key) >= 8) {
        // Show only first 4 and last 4 characters for better security
        $masked_key = esc_html(substr($license_key, 0, 8) . '-XXXXXXXX-XXXXXXXX-' . substr($license_key, -8));
    } elseif (!empty($license_key)) {
        $masked_key = 'XXXX-XXXX-XXXX-XXXX'; // Fallback mask for shorter keys
    }
    
    $admin_url = esc_url(admin_url('admin-post.php'));
?>
    <div class="tpap-dashboard-license">
        <div class="tpap-dashboard-license-pro-container">
            <div class="tpap-license-header-container" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1>🔒 <?php esc_html_e('Your License Info', $text_domain); ?></h1>
                <?php if (tpap_needs_refresh($license_info)): ?>

                    <button type="button" class="tpap-refresh-btn" id="tpap-refresh-license-btn">
                        <?php esc_html_e('🔄Check license status', $text_domain); ?>

                    </button>
                <?php endif; ?>
            </div>
            <ul>
                <li><strong><?php esc_html_e('Status:', $text_domain); ?></strong> 
                    <span class="validity">

                        <?php if ($license_info->is_valid): ?>

                            <?php if (tpap_is_license_expired($license_info)): ?>

                                <strong>❌ <?php esc_html_e('License Expired', $text_domain); ?></strong>

                            <?php elseif (tpap_is_support_expired($license_info)): ?>

                                <strong>❌ <?php esc_html_e('Support Expired', $text_domain); ?></strong>

                            <?php else: ?>

                                <strong class="valid">✅ <?php esc_html_e('Valid', $text_domain); ?></strong>

                            <?php endif; ?>

                        <?php else: ?>

                            <strong>❌ <?php esc_html_e('Invalid', $text_domain); ?></strong>
                            
                        <?php endif; ?>
                    </span>
                </li>
                <li><strong><?php esc_html_e('License Type:', $text_domain); ?></strong> <span class="license-type"><?php echo esc_html($license_info->license_title); ?></span></li>
                <li><strong><?php esc_html_e('Plugin Updates & Support Validity:', $text_domain); ?></strong> <span class="validity">

                    <?php 
                    $current_time = time();
                 
                    // Handle "No expiry" case for expire_date
                    $expire_date_expired = false;
                    if (strtolower($license_info->expire_date) !== 'no expiry') {
                        $expire_date_timestamp = strtotime($license_info->expire_date);
                        $expire_date_expired = $expire_date_timestamp && $expire_date_timestamp < $current_time;
                    }
                    
                    // Handle "no support" case for support_end
                    if (strtolower($license_info->support_end) === 'no support') {

                        esc_html_e('No Support', $text_domain);

                    } else {

                        // Handle "unlimited" case for support_end
                        $support_end_expired = false;

                        if (strtolower($license_info->support_end) !== 'unlimited') {

                            $support_end_timestamp = strtotime($license_info->support_end);
                            $support_end_expired = $support_end_timestamp && $support_end_timestamp < $current_time;

                        }
                        if ($expire_date_expired) {
                            
                            echo esc_html(tpap_pro_formatLicenseDate($license_info->expire_date));

                        } elseif ($support_end_expired) {                            
                            
                            echo esc_html(tpap_pro_formatLicenseDate($license_info->support_end));
                          
                        } else {

                            echo esc_html(tpap_pro_formatLicenseDate($license_info->expire_date));
                            
                        }
                       
                    }
                    ?>
                    </span>
                </li>
                <li><strong><?php esc_html_e('Your License Key:', $text_domain); ?></strong> 
                    <span class="license-key"><?php echo esc_html($masked_key); ?></span></li>
            </ul>

            <div class="tpap-dashboard-license-pro-container-deactivate-btn">
                <p><?php esc_html_e('Want to deactivate the license for any reason?', $text_domain); ?></p>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="<?php echo esc_attr('tpap_deactivate_license'); ?>" />
                    <?php wp_nonce_field(sanitize_key('tpap-license')); ?>
                    <button type="submit" class="deactivate-btn">
                        <?php esc_html_e('Deactivate License', $text_domain); ?>
                    </button>
                </form>
            </div>
            <?php if (tpap_is_license_expired($license_info)): ?>

            <div class="notice notice-error" style="margin-top: 10px; color: #d63638;">
                <?php tpap_render_expiry_message($license_info, 'license'); ?>
            </div>

            <?php elseif (tpap_is_support_expired($license_info)): ?>

            <div class="notice notice-error" style="margin-top: 10px; color: #d63638;">
                <?php tpap_render_expiry_message($license_info, 'support'); ?>
            </div>

            <?php endif; ?>

            <?php tpap_render_license_help_buttons($text_domain); ?>
        </div>
    </div>
<?php
}

/**
 * Renders the license help buttons section
 * 
 * @param string $text_domain The text domain for translations
 */
function tpap_render_license_help_buttons($text_domain) {

    ?>
    <div class="tpap-dashboard-license-pro-container-buttons">

        <p><?php esc_html_e('Want to know more about the license key?', $text_domain); ?></p>

        <div class="btns">

            <a href="<?php echo esc_url('https://my.example.net/account/?utm_source=atlt_plugin&utm_medium=inside&utm_campaign=store_site&utm_content=license'); ?>" target="_blank" rel="noopener noreferrer" class="tpap-dashboard-btn">
                <?php esc_html_e('Check Account', $text_domain); ?>
            </a>

            <a href="<?php echo esc_url('https://example.net/support/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=support&utm_content=license'); ?>" target="_blank" rel="noopener noreferrer" class="tpap-dashboard-btn">
                <?php esc_html_e('Contact Support', $text_domain); ?>
            </a>

        </div>

    </div>
    <?php
}

/**
 * Renders the license buttons section
 * @param string $text_domain The text domain for translations
 */
function tpap_render_license_buttons($text_domain) {
    // Security: Sanitize text domain parameter
    $text_domain = sanitize_text_field($text_domain);
    
    // Security: Define allowed external URLs for security
    $account_url = 'https://my.example.net/account/';
    $support_url = 'https://example.net/support/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=support&utm_content=license';
    ?>
    <div class="tpap-dashboard-license-pro-container-buttons">
        <p><?php esc_html_e('Want to know more about the license key?', $text_domain); ?></p>
        <div class="btns">
            <a href="<?php echo esc_url($account_url); ?>" target="_blank" class="tpap-dashboard-btn">
                <?php esc_html_e('Check Account', $text_domain); ?>
            </a>
            <a href="<?php echo esc_url($support_url); ?>" target="_blank" class="tpap-dashboard-btn">
                <?php esc_html_e('Contact Support', $text_domain); ?>
            </a>
        </div>
    </div>
    <?php
}

function tpap_is_license_expired($license_info) {

    return $license_info->is_valid === 'license_expired';
}

function tpap_is_support_expired($license_info) {

    return $license_info->support_end === 'no support' || 
          ($license_info->is_valid === 'support_expired' || 
          (strtolower($license_info->support_end) !== 'unlimited' && 
          strtotime($license_info->support_end) < time()));
}

function tpap_needs_refresh($license_info) {

    return tpap_is_license_expired($license_info) || tpap_is_support_expired($license_info);
}


function tpap_render_expiry_message($license_info, $type = 'license') {
 
    $text_domain = 'tpap';
    
    // Generate version available message using common helper
    $version_available_message = TranslatePress_Addon_Pro::tpapGetVersionAvailableMessage();
    
    if ($license_info->msg === 'limit_reached') {
        $support_link = sprintf('<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', esc_url('https://my.example.net/account/support-tickets/'), esc_html__('clicking here', 'tpap'));
        echo wp_kses_post(sprintf(
            /* translators: %s: link to support ticket page */
            __('There was an issue with your account. Please contact our plugin support team by %s.', 'tpap'),
            $support_link
        ));
        return;
    }

    $message = $type === 'license' 
        ? __('Your license has expired,', 'tpap') 
        : __('Your support has expired,', 'tpap');

    $renew_link = isset($license_info->market) && $license_info->market === 'E'
        ? ''
        : ' <a href="'.esc_url('https://my.example.net/account/subscriptions/').'" target="_blank" rel="noopener noreferrer">'.esc_html__('Renew now', 'tpap').'</a>';

     $final_message = '';
    
    // Add version message if available
    if (!empty($version_available_message)) {

        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        
        $final_message .= wp_kses_post($version_available_message) . ' ';
    }
    
    // Add license expiry message
    $final_message .= esc_html($message) . $renew_link . esc_html__(' to continue receiving updates and priority support.', 'tpap');
    
    echo $final_message;
}

function tpap_pro_formatLicenseDate($dateString) {

if (!empty($dateString) && strtolower($dateString) !== 'no expiry') {
     
        $date = new DateTime($dateString);
        return $date->format('d M Y');
    }
    return $dateString;
}

