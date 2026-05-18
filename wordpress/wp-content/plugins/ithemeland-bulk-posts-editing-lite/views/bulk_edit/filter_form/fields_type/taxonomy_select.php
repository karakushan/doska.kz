<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<select class="<?php echo ($name == 'post_tag') ? 'wpbe-select2-post-tags' : 'wpbe-select2'; ?> wpbe-filter-form-select2-option-values"
    data-field="value"
    data-option-name="<?php echo esc_attr($name); ?>"
    id="wpbe-filter-form-<?php echo esc_attr($name); ?>"
    multiple>
    <?php if (!empty($taxonomy['terms'])) : ?>
        <?php foreach ($taxonomy['terms'] as $term) : ?>
            <option value="<?php echo esc_attr($term->term_id); ?>">
                <?php echo esc_html($term->name); ?>
            </option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>