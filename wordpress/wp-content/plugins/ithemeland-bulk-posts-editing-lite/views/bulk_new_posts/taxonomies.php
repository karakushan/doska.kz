<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$taxonomy_item = $new_form_items['taxonomies']['taxonomy'];
?>

<?php if (!empty($taxonomies)) : ?>
    <?php foreach ($taxonomies as $name => $taxonomy) : ?>
        <div class="wpbe-form-group"
            data-type="taxonomy"
            data-name="<?php echo esc_attr($name); ?>">

            <label for="wpbe-bulk-new-form-post-attr-<?php echo esc_attr($name); ?>">
                <?php echo esc_html($taxonomy['label']); ?>
            </label>

            <?php
            $field_type_path = WPBEL_VIEWS_DIR . 'bulk_new_posts/fields_type/' . $taxonomy_item['field_type'] . '.php';
            if (file_exists($field_type_path)) {
                // Pass taxonomy-specific data to the field template
                $field_data = [
                    'name' => $name,
                    'taxonomy' => $taxonomy,
                    'field_id' => 'wpbe-bulk-new-form-post-attr-' . esc_attr($name),
                    'is_post_tag' => ($name == 'post_tag'),
                    'disabled' => $taxonomy_item['disabled']
                ];
                include $field_type_path;
            }
            ?>

            <?php if ($taxonomy_item['disabled']) : ?>
                <span class="wpbe-alert-pro-description"><?php esc_html_e('Upgrade to pro version!', 'ithemeland-bulk-posts-editing-lite'); ?></span>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <div class="wpbe-alert wpbe-alert-warning">
        <span><?php esc_html_e('There is not any added Custom Taxonomies', 'ithemeland-bulk-posts-editing-lite'); ?></span>
    </div>
<?php endif; ?>