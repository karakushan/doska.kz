<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<input type="number" class="wpbe-input-ft"
    id="<?php echo esc_attr($field_id); ?>-from"
    data-field="from"
    placeholder="<?php echo esc_attr(sprintf('%s From ...', $item['label'])); ?>"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>

<input type="number" class="wpbe-input-ft"
    id="<?php echo esc_attr($field_id); ?>-to"
    data-field="to"
    placeholder="<?php echo esc_attr(sprintf('%s To ...', $item['label'])); ?>"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>