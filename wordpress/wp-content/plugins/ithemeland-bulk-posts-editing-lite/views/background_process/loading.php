<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div id="wpbe-processing-loading" class="wpbe-processing-loading" style="<?php echo ((isset($is_processing) && $is_processing) || (!empty($complete_message))) ? 'display: block;' : 'display: none;'; ?>">
    <div class="wpbe-processing-loading-content">
        <span data-type="result_icon" style="display: none;"><i class=""></i></span>
        <span data-type="message" style="display: none;">

        </span>
        <button type="button" class="wpbe-processing-loading-stop-button" style="display: none;"><?php esc_html_e('Force Stop', 'ithemeland-bulk-posts-editing-lite'); ?></button>
    </div>
</div>