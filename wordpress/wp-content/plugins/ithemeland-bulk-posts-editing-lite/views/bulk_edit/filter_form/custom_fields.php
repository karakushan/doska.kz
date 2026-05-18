<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php
if (!empty($meta_fields)) :
    foreach ($meta_fields as $custom_field) :
        if (in_array($custom_field['key'], ['file', 'image'])) {
            continue;
        }
        $field_id = "wpbe-filter-form-custom-field-" . esc_attr($custom_field['key']) . "";
        $field_type = ($custom_field['main_type'] == 'textinput' && $custom_field['sub_type'] == 'number') ? 'number' : $custom_field['main_type'];
?>
        <div class="wpbe-form-group" data-field-type="<?php echo esc_attr($field_type); ?>" data-filter-type="custom_field" data-name="<?php echo esc_attr($custom_field['key']); ?>">
            <label><?php echo esc_html($custom_field['title']); ?></label>
            <?php if (($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::STRING_TYPE)
                || in_array($custom_field['main_type'], [
                    wpbel\classes\repositories\Meta_Field::TEXT,
                    wpbel\classes\repositories\Meta_Field::EMAIL,
                    wpbel\classes\repositories\Meta_Field::PASSWORD,
                    wpbel\classes\repositories\Meta_Field::TEXTAREA,
                    wpbel\classes\repositories\Meta_Field::EDITOR,
                    wpbel\classes\repositories\Meta_Field::URL
                ])
            ) : ?>
                <select title="<?php esc_attr_e('Select Operator', 'ithemeland-bulk-posts-editing-lite'); ?>" data-field="operator">
                    <?php include WPBEL_VIEWS_DIR . 'bulk_edit/filter_form/operators/text.php'; ?>
                </select>
                <input type="text" data-field="value" id="<?php echo esc_attr($field_id); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..." title="<?php echo esc_attr($custom_field['title']); ?>" <?php if ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CALENDAR) : ?> class="wpbe-datepicker" <?php endif; ?>>
            <?php elseif (
                ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::NUMBER)
                ||
                $custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::NUMBER
            ) : ?>
                <input type="number" class="wpbe-input-md" data-field="from" data-field-type="number" id="<?php echo esc_attr($field_id) . '-from'; ?>" title="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('From', 'ithemeland-bulk-posts-editing-lite'); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('From ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                <input type="number" class="wpbe-input-md" id="<?php echo esc_attr($field_id) . '-to'; ?>" data-field-type="number" title="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('To', 'ithemeland-bulk-posts-editing-lite'); ?>" data-field="to" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('To ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
            <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CHECKBOX) : ?>
                <select id="<?php echo esc_attr($field_id); ?>" class="wpbe-input-md" data-type="checkbox" data-field="value" title="<?php esc_attr_e('Select', 'ithemeland-bulk-posts-editing-lite'); ?> <?php echo esc_attr($custom_field['title']); ?>">
                    <option value=""><?php esc_html_e('Select', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="yes"><?php esc_html_e('Yes', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="no"><?php esc_html_e('No', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                </select>
            <?php elseif (
                in_array($custom_field['main_type'], [
                    wpbel\classes\repositories\Meta_Field::SELECT,
                    wpbel\classes\repositories\Meta_Field::RADIO,
                    wpbel\classes\repositories\Meta_Field::ARRAY_TYPE
                ])
            ) : ?>
                <select id="<?php echo esc_attr($field_id); ?>" class="wpbe-input-md" data-field="value" title="<?php esc_attr_e('Select', 'ithemeland-bulk-posts-editing-lite'); ?> <?php echo esc_attr($custom_field['title']); ?>">
                    <option value=""><?php esc_html_e('Select', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <?php
                    if (!empty($custom_field['key_value'])) :
                        $options = wpbel\classes\helpers\Meta_Field::key_value_field_to_array($custom_field['key_value']);
                        if (!empty($options) && is_array($options)) :
                            foreach ($options as $option_key => $option_value) :
                    ?>
                                <option value="<?php echo esc_attr($option_key) ?>"><?php echo esc_html($option_value); ?></option>
                    <?php
                            endforeach;
                        endif;
                    endif;
                    ?>
                </select>
            <?php elseif (in_array($custom_field['main_type'], [
                wpbel\classes\repositories\Meta_Field::CALENDAR,
                wpbel\classes\repositories\Meta_Field::DATE
            ])) : ?>
                <input type="text" class="wpbe-input-md wpbe-datepicker" data-field="from" data-field-type="date" id="<?php echo esc_attr($field_id) . '-from'; ?>" title="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('From', 'ithemeland-bulk-posts-editing-lite'); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('From ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                <input type="text" class="wpbe-input-md wpbe-datepicker" data-field="to" data-field-type="date" id="<?php echo esc_attr($field_id) . '-to'; ?>" title="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('To', 'ithemeland-bulk-posts-editing-lite'); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('To ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
            <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::DATE_TIME) : ?>
                <input type="text" class="wpbe-input-md wpbe-datetimepicker" data-field="from" data-field-type="date" id="<?php echo esc_attr($field_id) . '-from'; ?>" title="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('From', 'ithemeland-bulk-posts-editing-lite'); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('From ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                <input type="text" class="wpbe-input-md wpbe-datetimepicker" data-field="to" data-field-type="date" id="<?php echo esc_attr($field_id) . '-to'; ?>" title="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('To', 'ithemeland-bulk-posts-editing-lite'); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('To ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
            <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TIME) : ?>
                <input type="text" class="wpbe-input-md wpbe-timepicker" data-field="from" data-field-type="date" id="<?php echo esc_attr($field_id) . '-from'; ?>" title="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('From', 'ithemeland-bulk-posts-editing-lite'); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('From ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                <input type="text" class="wpbe-input-md wpbe-timepicker" data-field="to" data-field-type="date" id="<?php echo esc_attr($field_id) . '-to'; ?>" title="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('To', 'ithemeland-bulk-posts-editing-lite'); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_attr_e('To ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <div class="wpbe-alert wpbe-alert-warning">
        <span><?php esc_html_e('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', 'ithemeland-bulk-posts-editing-lite'); ?></span>
    </div>
<?php endif; ?>