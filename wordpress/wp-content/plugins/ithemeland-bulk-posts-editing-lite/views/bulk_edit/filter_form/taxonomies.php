<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$taxonomy_item = $filter_form_items['taxonomies']['taxonomy'];
?>

<?php if (!empty($taxonomies)) : ?>
    <?php foreach ($taxonomies as $name => $taxonomy) : ?>
        <div class="wpbe-form-group"
            data-name="<?php echo esc_attr($name); ?>"
            data-filter-type="<?php echo esc_attr($taxonomy_item['filter_type']); ?>"
            data-field-type="<?php echo esc_attr($taxonomy_item['field_type']); ?>">

            <label for="wpbe-filter-form-<?php echo esc_attr($name); ?>">
                <?php echo esc_html($taxonomy['label']); ?>
            </label>

            <?php if (!empty($taxonomy_item['operators'])) : ?>
                <select id="wpbe-filter-form-<?php echo esc_attr($name); ?>-operator"
                    data-field="operator"
                    title="<?php esc_attr_e('Select Operator', 'ithemeland-bulk-posts-editing-lite'); ?>">
                    <?php foreach ($taxonomy_item['operators'] as $operator_name => $operator_label) : ?>
                        <option value="<?php echo esc_attr($operator_name); ?>">
                            <?php echo esc_html($operator_label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <?php
            $field_type_path = WPBEL_VIEWS_DIR . 'bulk_edit/filter_form/fields_type/' . $taxonomy_item['field_type'] . '.php';
            if (file_exists($field_type_path)) {
                include $field_type_path;
            }
            ?>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <div class="wpbe-alert wpbe-alert-warning">
        <span><?php esc_html_e('There is not any added Custom Taxonomies', 'ithemeland-bulk-posts-editing-lite'); ?></span>
    </div>
<?php endif; ?>