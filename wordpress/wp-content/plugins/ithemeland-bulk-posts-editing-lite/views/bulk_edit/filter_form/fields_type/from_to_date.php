<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<input type="text" class="wpbe-input-ft wpbe-datepicker wpbe-date-from"
    data-to-id="<?php echo esc_attr($field_id); ?>-to"
    data-field="from"
    id="<?php echo esc_attr($field_id); ?>-from"
    placeholder="<?php echo esc_attr(sprintf('%s From ...', $item['label'])); ?>"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>

<input type="text" class="wpbe-input-ft wpbe-datepicker"
    data-field="to"
    id="<?php echo esc_attr($field_id); ?>-to"
    placeholder="<?php echo esc_attr(sprintf('%s To ...', $item['label'])); ?>"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>