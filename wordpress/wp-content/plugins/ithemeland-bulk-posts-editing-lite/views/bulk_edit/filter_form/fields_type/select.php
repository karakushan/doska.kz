<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<select class="wpbe-input-md" id="<?php echo esc_attr($field_id); ?>" data-field="value" <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>
    <option value=""><?php esc_html_e('Select', 'ithemeland-bulk-posts-editing-lite'); ?></option>
    <?php if (!empty($item['options'])) : ?>
        <?php foreach ($item['options'] as $value => $label) : ?>
            <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>