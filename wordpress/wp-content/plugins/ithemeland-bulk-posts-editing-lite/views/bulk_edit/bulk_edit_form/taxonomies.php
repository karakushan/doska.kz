<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$taxonomy_item = $edit_form_items['taxonomies']['taxonomy'];
?>

<?php if (!empty($taxonomies)) : ?>
    <?php foreach ($taxonomies as $name => $taxonomy) : ?>
        <div class="wpbe-form-group"
            data-type="taxonomy"
            data-name="<?php echo esc_attr($name); ?>">

            <label for="wpbe-bulk-edit-form-post-attr-<?php echo esc_attr($name); ?>">
                <?php echo esc_html($taxonomy['label']); ?>
            </label>

            <?php if (!empty($taxonomy_item['operators'])) : ?>
                <select id="wpbe-bulk-edit-form-post-attr-operator-<?php echo esc_attr($name); ?>"
                    title="<?php esc_attr_e('Select Operator', 'ithemeland-bulk-posts-editing-lite'); ?>"
                    data-field="operator">
                    <?php foreach ($taxonomy_item['operators'] as $operator_name => $operator_label) : ?>
                        <option value="<?php echo esc_attr($operator_name); ?>">
                            <?php echo esc_html($operator_label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <?php
            $field_type_path = WPBEL_VIEWS_DIR . 'bulk_edit/bulk_edit_form/fields_type/' . $taxonomy_item['field_type'] . '.php';
            if (file_exists($field_type_path)) {
                // Pass taxonomy-specific data to the field template
                $field_data = [
                    'name' => $name,
                    'taxonomy' => $taxonomy,
                    'field_id' => 'wpbe-bulk-edit-form-post-attr-' . esc_attr($name),
                    'is_post_tag' => ($name == 'post_tag')
                ];
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