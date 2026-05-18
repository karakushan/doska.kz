<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$items = $edit_form_items['type'];

foreach ($items as $name => $item) :
    $field_id = 'wpbe-bulk-edit-form-' . str_replace('_', '-', $name);
    $data_type = isset($item['data_type']) ? $item['data_type'] : 'wp_posts_field';
?>
    <div class="wpbe-form-group" data-name="<?php echo esc_attr($name); ?>" data-type="<?php echo esc_attr($data_type); ?>">
        <label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($item['label']); ?></label>

        <?php
        // Special handling for post_type and post_status options
        if ($name === 'post_type' && !empty($all_post_types)) {
            $item['options'] = $all_post_types;
        } elseif ($name === 'post_status' && !empty($post_statuses)) {
            $item['options'] = $post_statuses;
        }

        $field_type_path = WPBEL_VIEWS_DIR . 'bulk_edit/bulk_edit_form/fields_type/' . $item['field_type'] . '.php';
        if (file_exists($field_type_path)) {
            include $field_type_path;
        }
        ?>

        <?php if (isset($item['disabled']) && $item['disabled']) : ?>
            <span class="wpbe-alert-pro-description"><?php esc_html_e('Upgrade to pro version!', 'ithemeland-bulk-posts-editing-lite'); ?></span>
        <?php endif; ?>
    </div>
<?php endforeach; ?>