<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<button type="button"
    data-type="single"
    class="wpbe-button wpbe-button-blue wpbe-ml10 wpbe-h43 wpbe-float-left wpbe-open-uploader"
    data-target="bulk-edit-image"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>
    <?php esc_html_e('Choose Image', 'ithemeland-bulk-posts-editing-lite'); ?>
</button>
<input id="wpbe-bulk-edit-form-item-image"
    type="hidden"
    class="wpbe-bulk-edit-form-item-image"
    data-field="value"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>
<div id="wpbe-bulk-edit-form-item-image-preview"
    class="wpbe-bulk-edit-form-item-image-preview"></div>