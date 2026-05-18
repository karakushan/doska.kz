<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<input type="text"
    class="wpbe-input-md wpbe-datetimepicker"
    id="<?php echo esc_attr($field_id); ?>"
    data-field="value"
    placeholder="<?php echo esc_attr($item['placeholder']); ?>"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>