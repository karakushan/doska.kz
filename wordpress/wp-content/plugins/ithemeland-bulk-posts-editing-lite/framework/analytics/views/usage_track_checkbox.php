<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<label>
    <input
        type="checkbox"
        id="wpbel_usage_track"
        name="wpbel_usage_track"
        value="yes"
        <?php checked(1, $option); ?> />
    <span><?php esc_html_e('iThemeland Wordpress Bulk Posts Editing', 'ithemeland-bulk-posts-editing-lite'); ?></span>
    <p class="description">
        <?php echo esc_html($description); ?>
        <a href="https://ithemelandco.com/usage-tracking?utm_source=free_plugins&utm_medium=plugin_links&utm_campaign=telemetry"><?php esc_html_e('Learn More', 'ithemeland-bulk-posts-editing-lite'); ?></a>
    </p>
</label>