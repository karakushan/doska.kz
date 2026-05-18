
<?php
// Security: Validate text domain
if (!isset($text_domain) || empty($text_domain)) {
    $text_domain = 'tpap'; // Default fallback
}
$text_domain = sanitize_text_field($text_domain);


?>
    <div class="tpap-dashboard-left-section">
        
        <!-- Welcome Section -->
        <div class="tpap-dashboard-welcome">
            <div class="tpap-dashboard-welcome-text">
                <h2><?php echo esc_html__('Welcome To TranslatePress Addon', $text_domain); ?></h2>
                <p><?php echo esc_html__('Translate WordPress Full Webpage instantly with TranslatePress Addon. One-click, thousands of strings - no extra cost!', $text_domain); ?></p>
                <div class="tpap-dashboard-btns-row">
                    <a href="<?php echo esc_url(admin_url('options-general.php?page=translate-press')); ?>" target="_blank" class="tpap-dashboard-btn primary"><?php echo esc_html__('Website Languages', $text_domain); ?></a>
                    <a href="<?php echo esc_url(site_url('/?trp-edit-translation=true')); ?>" target="_blank" class="tpap-dashboard-btn"><?php echo esc_html__('Translate Site', $text_domain); ?></a>
                </div>
                <a class="tpap-dashboard-docs" href="<?php echo esc_url('https://docs.example.net/docs/automatic-translate-addon-for-translatepress-pro/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=dashboard'); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url(TPAP_URL . 'admin/tpa-dashboard/images/document.svg'); ?>" alt="document"> <?php echo esc_html__('Read Plugin Docs', $text_domain); ?></a>
            </div>
            <div class="tpap-dashboard-welcome-video">
                <a href="<?php echo esc_url('https://docs.example.net/doc/ai-translation-translatepress-video-tutorials/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=dashboard_video'); ?>" target="_blank" class="tpap-dashboard-video-link" rel="noopener noreferrer">
                    <img decoding="async" src="<?php echo esc_url(TPAP_URL . 'admin/tpa-dashboard/images/video.svg'); ?>" class="play-icon" alt="play-icon">
                    <picture>
                        <source srcset="<?php echo esc_url(TPAP_URL . 'admin/tpa-dashboard/images/translatepress-addon-video.png'); ?>" type="image/avif">
                        <img src="<?php echo esc_url(TPAP_URL . 'admin/tpa-dashboard/images/translatepress-addon-video.jpg'); ?>" class="translatepress-addon-video" alt="translatepress addon preview">
                    </picture>
                </a>
            </div>
        </div>

        <!-- Translation Providers -->  
        <div class="tpap-dashboard-translation-providers">
            <h3><?php esc_html_e('Translation Providers', $text_domain); ?></h3>
            <div class="tpap-dashboard-providers-grid">
                
                <?php

                $providers = [
                    [
                        "Chrome Built-in AI", 
                        "powered-by-chrome-api.png", 
                        [
                            esc_html__("Fast AI Translations in Browser", $text_domain), 
                            esc_html__("Unlimited Free Translations", $text_domain), 
                            esc_html__("Use Translation Modals", $text_domain)
                        ], 
                        'https://docs.example.net/docs/automatic-translate-addon-for-translatepress-pro/how-to-translate-your-website-content-automatically-via-chrome-ai/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=dashboard_chrome'
                    ],
                    [
                        "Google Translate", 
                        "powered-by-google.png", 
                        [
                            esc_html__("Unlimited Free Translations", $text_domain), 
                            esc_html__("Fast & No API Key Required", $text_domain)
                        ], 
                        'https://docs.example.net/docs/automatic-translate-addon-for-translatepress-pro/how-to-translate-your-website-content-automatically-via-google/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=dashboard_google'
                    ],
                    [
                        "Yandex Translate", 
                        "powered-by-yandex.png", 
                        [
                            esc_html__("Unlimited Free Translations", $text_domain), 
                            esc_html__("No API & No Extra Cost", $text_domain)
                        ], 
                        'https://docs.example.net/docs/automatic-translate-addon-for-translatepress-pro/how-to-translate-your-website-content-automatically-via-yandex/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=dashboard_yandex'
                    ],
                ];

                foreach ($providers as $index => $provider) {
                    ?>
                    <div class="tpap-dashboard-provider-card">
                        <div class="tpap-dashboard-provider-header">
                            <a href="<?php echo esc_url($provider[3]); ?>" target="_blank" rel="noopener noreferrer">
                                <img src="<?php echo esc_url(TPAP_URL . 'assets/images/' . sanitize_file_name($provider[1])); ?>" 
                                     alt="<?php echo esc_attr(substr($provider[0], 0, 100)); ?>">
                            </a>
                        </div>
                        <h4><?php echo esc_html(substr($provider[0], 0, 100)); ?></h4>
                        <ul>
                            <?php foreach ($provider[2] as $feature) { ?>
                                <li>✅ <?php echo esc_html($feature); ?></li>
                            <?php } ?>
                        </ul>
                        <div class="tpap-dashboard-provider-buttons">
                            <a href="<?php echo esc_url($provider[3]); ?>" class="tpap-dashboard-btn" target="_blank" rel="noopener noreferrer">
                                <?php echo esc_html__('Docs', $text_domain); ?>
                            </a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

