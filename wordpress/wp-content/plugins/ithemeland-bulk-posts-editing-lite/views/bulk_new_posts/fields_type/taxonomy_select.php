<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<select class="<?php echo ($field_data['is_post_tag']) ? 'wpbe-select2-post-tags' : 'wpbe-select2'; ?>"
    data-field="value"
    id="<?php echo esc_attr($field_data['field_id']); ?>"
    multiple
    <?php echo ($field_data['disabled']) ? 'disabled="disabled"' : ''; ?>>
    <?php if (!empty($field_data['taxonomy']['terms'])) : ?>
        <?php foreach ($field_data['taxonomy']['terms'] as $term) : ?>
            <option value="<?php echo ($field_data['name'] == 'category') ? intval($term->term_id) : esc_attr($term->name); ?>">
                <?php echo esc_html($term->name); ?>
            </option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>