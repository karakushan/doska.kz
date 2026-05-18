<?php
// Security: Validate text domain
if (!isset($text_domain) || empty($text_domain)) {
    $text_domain = 'tpap'; // Default fallback
}
$text_domain = sanitize_text_field($text_domain);

?>
<div class="tpap-dashboard-ai-translations">
    <div class="tpap-dashboard-ai-translations-container">
    <div class="header">
        <h1><?php esc_html_e('AI Translations', $text_domain); ?></h1>
    </div>
    <p class="description">
        <?php esc_html_e('Experience the power of AI for faster, more accurate translations. Choose from multiple AI providers to translate your content efficiently.', $text_domain); ?>
    </p>
    <div class="tpap-dashboard-translations">
        <?php
        $ai_translations = [
            [
                'logo' => 'powered-by-chrome-api.png',
                'alt' => 'Chrome Built-in AI',
                'title' => esc_html__('Chrome Built-in AI', $text_domain),
                'description' => esc_html__('Utilize Chrome\'s built-in AI for seamless translation experience.', $text_domain),
                'icon' => 'chrome-ai-translate.png',
                'url' => 'https://docs.example.net/docs/automatic-translate-addon-for-translatepress-pro/how-to-translate-your-website-content-automatically-via-chrome-ai/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=chrome_ai_translations'
            ]
        ];

        foreach ($ai_translations as $translation) {
            ?>
            <div class="tpap-dashboard-translation-card">
                <div class="logo">
                    <img src="<?php echo esc_url(TPAP_URL . 'assets/images/' . sanitize_file_name($translation['logo'])); ?>" 
                         alt="<?php echo esc_attr(substr(sanitize_text_field($translation['alt']), 0, 100)); ?>">
                </div>
                <h3><?php echo esc_html(substr($translation['title'], 0, 100)); ?></h3>
                <p><?php echo esc_html(substr($translation['description'], 0, 250)); ?></p>
                <div class="play-btn-container">
                    <a href="<?php echo esc_url($translation['url']); ?>" target="_blank">
                        <img src="<?php echo esc_url(TPAP_URL . 'admin/tpa-dashboard/images/' . sanitize_file_name($translation['icon'])); ?>" 
                             alt="<?php echo esc_attr(substr(sanitize_text_field($translation['alt']), 0, 100)); ?>">
                    </a>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    </div>
</div>