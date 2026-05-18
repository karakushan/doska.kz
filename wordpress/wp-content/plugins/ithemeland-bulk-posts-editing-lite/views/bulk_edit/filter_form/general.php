<?php

use wpbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$items = $filter_form_items['general'];

foreach ($items as $name => $item):
    $field_id = 'wpbe-filter-form-' . esc_attr($name);
    $field_type = isset($item['field_type']) ? $item['field_type'] : 'text';
?>
    <div class="wpbe-form-group" data-name="<?php echo esc_attr($name); ?>" data-filter-type="<?php echo esc_attr($item['filter_type']); ?>">
        <label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($item['label']); ?></label>

        <?php if (!empty($item['operators'])): ?>
            <select <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?> id="<?php echo esc_attr($field_id); ?>-operator" data-field="operator" title="<?php esc_attr_e('Select Operator', 'ithemeland-bulk-posts-editing-lite'); ?>">
                <?php foreach ($item['operators'] as $operator_name => $operator_label): ?>
                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <?php
        $field_type_path = WPBEL_VIEWS_DIR . 'bulk_edit/filter_form/fields_type/' . $field_type . '.php';
        if (file_exists($field_type_path)) {
            include $field_type_path;
        } else {
            include WPBEL_VIEWS_DIR . 'bulk_edit/filter_form/fields_type/text.php';
        }
        ?>

        <?php if (isset($item['extra_html'])): ?>
            <?php echo wp_kses($item['extra_html'], Sanitizer::allowed_html()); ?>
        <?php endif; ?>

        <?php if (isset($item['disabled']) && $item['disabled']): ?>
            <span class="wpbe-alert-pro-description"><?php esc_html_e('Upgrade to pro version!', 'ithemeland-bulk-posts-editing-lite'); ?></span>
        <?php endif; ?>
    </div>
<?php endforeach; ?>