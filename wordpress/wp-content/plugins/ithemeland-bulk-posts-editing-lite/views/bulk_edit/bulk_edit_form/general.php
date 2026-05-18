<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$items = $edit_form_items['general'];

foreach ($items as $name => $item) :
    $field_id = 'wpbe-bulk-edit-form-' . str_replace('_', '-', $name);
    $field_type = isset($item['field_type']) ? $item['field_type'] : 'text';
?>
    <div class="wpbe-form-group" data-name="<?php echo esc_attr($name); ?>" data-type="<?php echo isset($item['data_type']) ? esc_attr($item['data_type']) : 'wp_posts_field'; ?>">
        <div>
            <label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($item['label']); ?></label>

            <?php if (!empty($item['operators'])) : ?>
                <select title="<?php esc_attr_e('Select Operator', 'ithemeland-bulk-posts-editing-lite'); ?>"
                    id="<?php echo esc_attr($field_id); ?>-operator"
                    data-field="operator"
                    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>
                    <?php foreach ($item['operators'] as $operator_name => $operator_label) : ?>
                        <option value="<?php echo esc_attr($operator_name); ?>">
                            <?php echo esc_html($operator_label); ?>
                        </option>
                    <?php endforeach; ?>
                    <?php if (!empty($item['extra_operators'])) : ?>
                        <?php foreach ($item['extra_operators'] as $operator_name => $operator_label) : ?>
                            <option value="<?php echo esc_attr($operator_name); ?>">
                                <?php echo esc_html($operator_label); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            <?php endif; ?>

            <?php
            $field_type_path = WPBEL_VIEWS_DIR . 'bulk_edit/bulk_edit_form/fields_type/' . $field_type . '.php';
            if (file_exists($field_type_path)) {
                include $field_type_path;
            } else {
                include WPBEL_VIEWS_DIR . 'bulk_edit/bulk_edit_form/fields_type/text.php';
            }
            ?>

            <?php if (isset($item['show_variables']) && $item['show_variables']) : ?>
                <?php include WPBEL_VIEWS_DIR . "bulk_edit/bulk_edit_form/variable.php"; ?>
            <?php endif; ?>

            <?php if (isset($item['disabled']) && $item['disabled']) : ?>
                <span class="wpbe-alert-pro-description"><?php esc_html_e('Upgrade to pro version!', 'ithemeland-bulk-posts-editing-lite'); ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>