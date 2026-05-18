<?php
// Security: Validate text domain
if (!isset($text_domain) || empty($text_domain)) {
    $text_domain = 'tpap'; // Default fallback
}
$text_domain = sanitize_text_field($text_domain);


?>
<div class="tpap-dashboard-info">
    <div class="tpap-dashboard-info-links">
        <p>
            <?php esc_html_e('Nulled by Gobyv'); ?>
        </p>
        <a href="<?php echo esc_url('https://example.net/support/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=support&utm_content=dashboard_footer'); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Support', $text_domain); ?></a> |
        <a href="<?php echo esc_url('https://docs.example.net/docs/automatic-translate-addon-for-translatepress-pro/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=dashboard_footer'); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Docs', $text_domain); ?></a>
        <div class="tpap-dashboard-social-icons">
            <?php
            $social_links = [
                ['https://www.facebook.com/coolplugins/', 'facebook.svg', esc_html__('Facebook', $text_domain)],
                ['https://linkedin.com/company/coolplugins', 'linkedin.svg', esc_html__('Linkedin', $text_domain)],
                ['https://x.com/cool_plugins', 'twitter.svg', esc_html__('Twitter', $text_domain)],
                ['https://www.youtube.com/@cool_plugins', 'youtube.svg', esc_html__('YouTube Channel', $text_domain)]
            ];
            
            foreach ($social_links as $link) {
                echo '<a href="' . esc_url($link[0]) . '" target="_blank">
                        <img src="' . esc_url(TPAP_URL . 'admin/tpa-dashboard/images/' . $link[1]) . '" alt="' . esc_attr($link[2]) . '">
                      </a>';
            }
            ?>
        </div>
    </div>
</div>
