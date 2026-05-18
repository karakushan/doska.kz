<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php if (!empty($meta_fields)) : ?>
    <?php foreach ($meta_fields as $custom_field) : ?>
        <?php $field_id = "wpbe-bulk-edit-form-custom-field-" . esc_attr($custom_field['key']); ?>
        <div class="wpbe-form-group" data-type="meta_field" data-name="<?php echo esc_attr($custom_field['key']); ?>">
            <div>
                <label><?php echo esc_html($custom_field['title']); ?></label>
                <?php
                if (($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::STRING_TYPE)
                    || in_array($custom_field['main_type'], [
                        wpbel\classes\repositories\Meta_Field::EMAIL,
                        wpbel\classes\repositories\Meta_Field::PASSWORD,
                        wpbel\classes\repositories\Meta_Field::EDITOR,
                        wpbel\classes\repositories\Meta_Field::URL
                    ])
                ) : ?>
                    <select title="<?php esc_attr_e('Select Operator', 'ithemeland-bulk-posts-editing-lite'); ?>" data-field="operator">
                        <?php if (!empty($edit_text_operators)) : ?>
                            <?php foreach ($edit_text_operators as $operator_name => $operator_label) : ?>
                                <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <input type="text" data-field="value" id="<?php echo esc_attr($field_id); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..." title="<?php echo esc_attr($custom_field['title']); ?>">
                    <?php include WPBEL_VIEWS_DIR . "bulk_edit/bulk_edit_form/variable.php"; ?>
                <?php elseif (
                    $custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT &&
                    $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::NUMBER
                ) : ?>
                    <select title="<?php esc_attr_e('Select Operator', 'ithemeland-bulk-posts-editing-lite'); ?>" data-field="operator">
                        <?php if (!empty($edit_number_operators)) : ?>
                            <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <input type="number" class="wpbe-input-md" data-field="value" id="<?php echo esc_attr($field_id); ?>" title="<?php echo esc_attr($custom_field['title']); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> ...">
                <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTAREA) : ?>
                    <select title="<?php esc_attr_e('Select Operator', 'ithemeland-bulk-posts-editing-lite'); ?>" data-field="operator">
                        <?php if (!empty($edit_text_operators)) : ?>
                            <?php foreach ($edit_text_operators as $operator_name => $operator_label) : ?>
                                <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <textarea data-field="value" id="<?php echo esc_attr($field_id); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..."></textarea>
                <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CHECKBOX) : ?>
                    <select id="<?php echo esc_attr($field_id); ?>" data-field="value" class="wpbe-input-md" title="<?php esc_attr_e('Select', 'ithemeland-bulk-posts-editing-lite'); ?> <?php echo esc_attr($custom_field['title']); ?>">
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
                    <input type="text" class="wpbe-input-md wpbe-datepicker" data-field="value" id="<?php echo esc_attr($field_id); ?>" title="<?php echo esc_attr($custom_field['title']); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> ...">
                <?php elseif (in_array($custom_field['main_type'], [
                    wpbel\classes\repositories\Meta_Field::DATE_TIME
                ])) : ?>
                    <input type="text" class="wpbe-input-md wpbe-datepicker" data-field="value" id="<?php echo esc_attr($field_id); ?>" title="<?php echo esc_attr($custom_field['title']); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> ...">
                <?php elseif (in_array($custom_field['main_type'], [
                    wpbel\classes\repositories\Meta_Field::TIME
                ])) : ?>
                    <input type="text" class="wpbe-input-md wpbe-datepicker" data-field="value" id="<?php echo esc_attr($field_id); ?>" title="<?php echo esc_attr($custom_field['title']); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> ...">
                <?php elseif (in_array($custom_field['main_type'], [
                    wpbel\classes\repositories\Meta_Field::IMAGE
                ])) : ?>
                    <button type="button" data-type="single" class="wpbe-button wpbe-button-blue wpbe-float-left wpbe-open-uploader" data-target="#wpbe-bulk-edit-form-custom-field-<?php echo esc_attr($custom_field['key']); ?>">
                        <?php esc_html_e('Choose image', 'ithemeland-bulk-posts-editing-lite') ?>
                    </button>
                    <input type="hidden" data-field="value" class="wpbe-bulk-edit-form-custom-field-<?php echo esc_attr($custom_field['key']); ?>" id="wpbe-bulk-edit-form-custom-field-<?php echo esc_attr($custom_field['key']); ?>">
                    <div id="wpbe-bulk-edit-form-custom-field-<?php echo esc_attr($custom_field['key']); ?>-preview" class="wpbe-bulk-edit-form-item-image-preview"></div>
                <?php elseif (in_array($custom_field['main_type'], [
                    wpbel\classes\repositories\Meta_Field::FILE
                ])) :
                    include WPBEL_VIEWS_DIR . 'bulk_edit/custom_field_files.php';
                endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <div class="wpbe-alert wpbe-alert-warning">
        <span><?php esc_html_e('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', 'ithemeland-bulk-posts-editing-lite'); ?></span>
    </div>
<?php endif; ?>