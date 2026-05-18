<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-warning-pro-alert">
    <i class="wpbe-icon-warning"></i> <!-- Warning icon -->
    <span class="warning-message"><?php esc_html_e('This option is not available in Free Version, Please upgrade to Pro Version.', 'ithemeland-bulk-posts-editing-lite') ?></span>
    <a href="<?php echo esc_url(WPBEL_PRO_LINK); ?>"><?php esc_html_e('Download Pro Version', 'ithemeland-bulk-posts-editing-lite'); ?></a>
</div>