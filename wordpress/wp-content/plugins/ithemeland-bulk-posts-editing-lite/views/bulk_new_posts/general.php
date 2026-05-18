<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$items = $new_form_items['general'];

foreach ($items as $name => $item) :
    $field_id = 'wpbe-bulk-new-form-post-' . $item['name'];
    $data_type = isset($item['data_type']) ? $item['data_type'] : 'wp_posts_field';
?>
    <div class="wpbe-form-group" data-name="<?php echo esc_attr($name); ?>" data-type="<?php echo esc_attr($data_type); ?>">
        <?php if ($item['field_type'] !== 'image_upload') : ?>
            <label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($item['label']); ?></label>
        <?php else : ?>
            <label><?php echo esc_html($item['label']); ?></label>
        <?php endif; ?>

        <?php
        $field_type_path = WPBEL_VIEWS_DIR . 'bulk_new_posts/fields_type/' . $item['field_type'] . '.php';
        if (file_exists($field_type_path)) {
            include $field_type_path;
        }
        ?>

        <?php if (isset($item['disabled']) && $item['disabled']) : ?>
            <span class="wpbe-alert-pro-description"><?php esc_html_e('Upgrade to pro version!', 'ithemeland-bulk-posts-editing-lite'); ?></span>
        <?php endif; ?>
    </div>
<?php endforeach; ?>