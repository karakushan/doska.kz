<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<textarea id="<?php echo esc_attr($field_id); ?>"
    data-field="value"
    placeholder="<?php echo esc_attr($item['label']) . ' ...'; ?>"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>
</textarea>