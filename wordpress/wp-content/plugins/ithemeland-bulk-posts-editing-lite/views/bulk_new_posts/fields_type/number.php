<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<input type="number"
    id="<?php echo esc_attr($field_id); ?>"
    data-field="value"
    placeholder="<?php echo esc_attr($item['placeholder']); ?>"
    class="wpbe-input-md"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>