<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<select class="wpbe-input-md wpbe-select2-users wpbe-select2"
    id="<?php echo esc_attr($field_id); ?>"
    data-field="value"
    data-placeholder="<?php echo esc_attr($item['placeholder']); ?>"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>
</select>