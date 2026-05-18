<?php

namespace wpbel\classes\providers\column;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\providers\column\ColumnProvider;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Post;
use wpbel\classes\repositories\Setting;
use wpbel\classes\helpers\Meta_Field as Meta_Field_Helper;
use wpbel\classes\helpers\Render;
use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\repositories\Meta_Field;

class PostColumnProvider extends ColumnProvider
{
    const CELL_CONTENT_LIMIT = 30;

    private static $instance;

    private $post_object;
    private $post;
    private $column_data;
    private $column_key;
    private $field_type;
    private $fields_method;
    private $sticky_first_columns;
    private $meta_fields;
    private $decoded_column_key;
    private $users;
    private $display_cell_content;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $setting_repository = new Setting();
        $settings = $setting_repository->get_settings();
        $this->sticky_first_columns = isset($settings['sticky_first_columns']) ? $settings['sticky_first_columns'] : 'yes';
        $this->display_cell_content = (!empty($settings['display_cell_content']) && $settings['display_cell_content'] == 'short') ? 'short' : 'long';
        $meta_field_repository = new Meta_Field();
        $this->meta_fields = $meta_field_repository->get();
        $this->field_type = "";
        $this->fields_method = $this->get_fields_method();
    }

    protected function item_columns($post_object, $columns)
    {
        if ($post_object instanceof \WP_Post) {
            $this->post_object = $post_object;
            $output['includes'] = [];
            $post_repository = new Post();

            $this->post = apply_filters('wpbe_table_column_values', $post_repository->get_post_fields($this->post_object), $post_object->ID, array_keys($columns));
            $output['items'] = '<tr data-item-id="' . esc_attr($this->post['id']) . '" data-item-type="' . esc_attr($this->post['post_type']) . '">';
            $output['items'] .= $this->get_static_columns();

            if (!empty($columns) && is_array($columns)) {
                foreach ($columns as $column_key => $column_data) {
                    $this->column_key = $column_key;
                    $this->column_data = $column_data;
                    if (!isset($this->column_data['name'])) {
                        $this->column_data['name'] = $column_key;
                    }
                    $this->decoded_column_key = urlencode($this->column_key);
                    $field_data = $this->get_field();
                    $output['items'] .= (!empty($field_data['field'])) ? $field_data['field'] : '';
                    if (!empty($field_data['includes']) && is_array($field_data['includes'])) {
                        $column_key = $this->column_key;
                        $decoded_column_key = $this->decoded_column_key;
                        $column_data = $this->column_data;
                        $field_type = $this->field_type;
                        $post = $this->post;
                        foreach ($field_data['includes'] as $include) {
                            if (file_exists($include)) {
                                $output['includes'][] = Render::html($include, compact('post', 'column_key', 'decoded_column_key', 'column_data', 'field_type'));
                            }
                        }
                    }
                }
            }

            $output['items'] .= "</tr>";
            return $output;
        }
    }

    private function get_field()
    {
        $output['field'] = '';
        $output['includes'] = [];
        $this->field_type = '';

        $this->set_post_field();
        $color = $this->get_column_colors_style();

        $sub_name = (!empty($this->column_data['sub_name'])) ? $this->column_data['sub_name'] : '';
        $update_type = (!empty($this->column_data['update_type'])) ? $this->column_data['update_type'] : '';
        $post_title = (!empty($this->post['title'])) ? $this->post['title'] : '';
        $output['field'] .= '<td data-item-id="' . esc_attr($this->post['id']) . '" data-item-title="' . esc_attr($post_title) . '" data-col-title="' . esc_attr($this->column_data['title']) . '" data-field="' . esc_attr($this->column_key) . '" data-field-type="' . esc_attr($this->field_type) . '" data-name="' . esc_attr($this->column_data['name']) . '" data-sub-name="' . esc_attr($sub_name) . '" data-update-type="' . esc_attr($update_type) . '" style="' . esc_attr($color['background']) . ' ' . esc_attr($color['text']) . '"';
        if ($this->column_data['editable'] === true && !in_array($this->column_data['content_type'], ['multi_select', 'multi_select_attribute'])) {
            $output['field'] .= ' data-content-type="' . esc_attr($this->column_data['content_type']) . '" data-action="inline-editable"';
        }
        $output['field'] .= '>';

        if ($this->column_data['editable'] === true) {
            $generated = $this->generate_field();
            if (is_array($generated) && isset($generated['field']) && isset($generated['includes'])) {
                $output['field'] .= $generated['field'];
                $output['includes'][] = $generated['includes'];
            } else {
                $output['field'] .= $generated;
            }
        } else {
            if (isset($this->post[$this->decoded_column_key])) {
                $output['field'] .= (is_array($this->post[$this->decoded_column_key])) ? wp_kses(implode(',', $this->post[$this->decoded_column_key]), Sanitizer::allowed_html()) : wp_kses($this->post[$this->decoded_column_key], Sanitizer::allowed_html());
            } else {
                $output['field'] .= ' ';
            }
        }

        $output['field'] .= '</td>';
        return $output;
    }

    private function get_column_colors_style()
    {
        $color['background'] = (!empty($this->column_data['background_color']) && $this->column_data['background_color'] != '#fff' && $this->column_data['background_color'] != '#ffffff') ? 'background:' . esc_attr($this->column_data['background_color']) . ';' : '';
        $color['text'] = (!empty($this->column_data['text_color'])) ? 'color:' . esc_attr($this->column_data['text_color']) . ';' : '';
        return $color;
    }

    private function set_post_field()
    {
        if (isset($this->column_data['field_type'])) {
            switch ($this->column_data['field_type']) {
                case 'custom_field':
                    $this->field_type = 'custom_field';
                    $this->post[$this->decoded_column_key] = (isset($this->post['custom_field'][$this->decoded_column_key])) ? $this->post['custom_field'][$this->decoded_column_key][0] : '';
                    break;
                case 'taxonomy':
                    $this->post[$this->decoded_column_key] = ($this->decoded_column_key == 'post_tag') ? wp_get_post_terms($this->post['id'], $this->decoded_column_key, ['fields' => 'names']) : wp_get_post_terms($this->post['id'], $this->decoded_column_key, ['fields' => 'ids']);
                    break;
                default:
                    break;
            }
        }
    }

    private function generate_field()
    {
        if (isset($this->fields_method[$this->column_data['content_type']]) && method_exists($this, $this->fields_method[$this->column_data['content_type']])) {
            return $this->{$this->fields_method[$this->column_data['content_type']]}();
        } else {
            return (is_array($this->post[$this->decoded_column_key])) ? implode(',', $this->post[$this->decoded_column_key]) : $this->post[$this->decoded_column_key];
        }
    }

    private function get_fields_method()
    {
        return [
            'text' => 'text_field',
            'password' => 'text_field',
            'email' => 'text_field',
            'url' => 'text_field',
            'textarea' => 'textarea_field',
            'image' => 'image_field',
            'numeric' => 'numeric_field',
            'numeric_without_calculator' => 'numeric_without_calculator_field',
            'checkbox_dual_mode' => 'checkbox_dual_mode_field',
            'checkbox' => 'checkbox_field',
            'radio' => 'radio_field',
            'file' => 'select_custom_field_files_field',
            'select_files' => 'select_files_field',
            'select' => 'select_field',
            'select_user' => 'select_user_field',
            'date' => 'date_picker_field',
            'date_picker' => 'date_picker_field',
            'date_time' => 'date_time_picker_field',
            'date_time_picker' => 'date_time_picker_field',
            'time' => 'time_picker_field',
            'time_picker' => 'time_picker_field',
            'color_picker' => 'color_picker_field',
            'taxonomy' => 'multi_select_field',
            'multi_select' => 'multi_select_field',
        ];
    }

    private function get_static_columns()
    {
        $output = '';
        $output .= $this->get_id_column();
        $static_columns = Column::get_static_columns();
        if (!empty($static_columns)) {
            foreach ($static_columns as $static_column) {
                $full_text = $value = $this->post[$static_column['field']];
                if ($this->display_cell_content == 'short' && strlen($value) > self::CELL_CONTENT_LIMIT) {
                    $value = mb_substr($value, 0, self::CELL_CONTENT_LIMIT) . '...';
                }
                $sticky_class = ($this->sticky_first_columns == 'yes') ? 'wpbe-td-sticky wpbe-td-sticky-title wpbe-gray-bg' : '';
                $output .= '<td class="' . esc_attr($sticky_class) . '" data-update-type="wp_posts_field" data-name="' . esc_attr($static_column['field']) . '" data-item-id="' . esc_attr($this->post['id']) . '" data-item-title="' . esc_attr($this->post[$static_column['field']]) . '" data-col-title="' . esc_attr($static_column['title']) . '" data-field="' . esc_attr($static_column['field']) . '" data-field-type="" data-content-type="text" data-action="inline-editable">';
                $output .= '<span data-action="inline-editable" data-full-text="' . esc_attr($full_text) . '" class="wpbe-td160">' . wp_kses($value, Sanitizer::allowed_html()) . '</span>';
                $output .= '</td>';
            }
        }
        return $output;
    }

    private function get_id_column()
    {
        $output = '';
        if (Column::SHOW_ID_COLUMN === true) {
            $delete_type = 'trash';
            $delete_label = esc_html__('Delete post', 'ithemeland-bulk-posts-editing-lite');
            $restore_button = '';
            $view_button = '';
            $edit_button = '';

            if ($this->post['post_status'] == 'trash') {
                $delete_type = 'permanently';
                $delete_label = esc_html__('Delete permanently', 'ithemeland-bulk-posts-editing-lite');
                $restore_button = '<button type="button" style="height: 28px;" class="wpbe-ml5 wpbe-button-flat wpbe-text-green wpbe-float-right wpbe-restore-item-btn" data-item-id="' . esc_attr($this->post['id']) . '" title="' . esc_html__('Restore', 'ithemeland-bulk-posts-editing-lite') . '"><span class="wpbe-icon-rotate-cw"></span></button>';
            } else {
                $view_button = '<a href="' . esc_url(get_the_permalink($this->post['id'])) . '" target="_blank" style="height: 28px;" title="View on site" class="wpbe-item-view-icon wpbe-ml5 wpbe-float-right"><span style="vertical-align: middle;" class="wpbe-icon-eye1"></span></a>';
                $edit_button = '<a href="' . admin_url("post.php?post=" . intval($this->post['id']) . "&action=edit") . '" style="height: 28px;" target="_blank" class="wpbe-ml5 wpbe-float-right" title="Edit Post"><span style="vertical-align: middle;" class="wpbe-icon-pencil"></span></a>';
            }
            $post_title = (!empty($this->post['title'])) ? $this->post['title'] : '';
            $sticky_class = ($this->sticky_first_columns == 'yes') ? 'wpbe-td-sticky wpbe-td-sticky-id wpbe-gray-bg' : '';
            $output .= '<td data-item-id="' . esc_attr($this->post['id']) . '" data-item-title="' . esc_attr($post_title) . '" data-col-title="ID" class="' . esc_attr($sticky_class) . '">';
            $output .= '<label class="wpbe-td140">';
            $output .= '<input type="checkbox" class="wpbe-check-item" data-item-type="' . esc_attr($this->post['post_type']) . '" value="' . esc_attr($this->post['id']) . '" title="Select Item">';
            $output .= intval($this->post['id']);
            $output .= $restore_button;
            $output .= $view_button;
            $output .= '<button type="button" class="wpbe-ml5 wpbe-button-flat wpbe-text-red wpbe-float-right wpbe-delete-item-btn" data-delete-type="' . esc_attr($delete_type) . '" data-item-id="' . esc_attr($this->post['id']) . '" title="' . $delete_label . '"><span class="wpbe-icon-trash-2"></span></button>';
            $output .= $edit_button;
            $output .= "</label>";
            $output .= "</td>";
        }

        return $output;
    }

    private function text_field()
    {

        $full_text = $value = (is_array($this->post[$this->decoded_column_key])) ? implode(',', $this->post[$this->decoded_column_key]) : $this->post[$this->decoded_column_key];
        if ($this->display_cell_content == 'short' && strlen($value) > self::CELL_CONTENT_LIMIT) {
            $value = mb_substr($value, 0, self::CELL_CONTENT_LIMIT) . '...';
        }

        return "<span data-action='inline-editable' data-full-text='" . esc_attr($full_text) . "' class='wpbe-td160'>" . wp_kses($value, Sanitizer::allowed_html()) . "</span>";
    }

    private function textarea_field()
    {
        $post_title = (!empty($this->post['title'])) ? $this->post['title'] : '';
        return "<button type='button' data-toggle='modal' data-target='#wpbe-modal-text-editor' class='wpbe-button wpbe-button-flat wpbe-load-text-editor' data-item-id='" . esc_attr($this->post['id']) . "' data-item-name='" . esc_attr($post_title) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'><i class='wpbe-icon-edit'></i></button>";
    }

    private function image_field()
    {
        $output = '';
        $image_id = 0;
        if (isset($this->post[$this->decoded_column_key]['id'])) {
            $image_id = intval($this->post[$this->decoded_column_key]['id']);
        }
        if (isset($this->post[$this->decoded_column_key]) && is_numeric($this->post[$this->decoded_column_key])) {
            $image_id = intval($this->post[$this->decoded_column_key]);
        }

        $image_url = wp_get_attachment_image_src($image_id, [40, 40]);
        $full_size = wp_get_attachment_image_src($image_id, 'full');
        $full_size = (!empty($full_size[0])) ? $full_size[0] : esc_url(WPBEL_IMAGES_URL . "/woocommerce-placeholder.png");

        $image = (!empty($image_url[0])) ? "<img src='" . esc_url($image_url[0]) . "' alt='' width='40' height='40' />" : '<button class="wpbe-button wpbe-button-flat"><i class="wpbe-icon-image"></i></button>';
        $output .= "<span data-toggle='modal' class='' data-target='#wpbe-modal-image' data-id='wpbe-" . esc_attr($this->column_key) . "-" . esc_attr($this->post['id']) . "' class='wpbe-image-inline-edit' data-full-image-src='" . esc_url($full_size) . "' data-image-id='" . esc_attr($image_id) . "'>";
        $output .= $image;
        $output .= "</span>";

        return $output;
    }

    private function numeric_field()
    {
        $field_arr = explode('_-_', $this->decoded_column_key);
        $field = (!empty($field_arr[0])) ? $field_arr[0] : $this->decoded_column_key;
        $value = $this->post[$field];

        if (!empty($field_arr[1]) && is_array($value)) {
            if (!empty($value[0])) {
                $decoded = json_decode($value[0], true);
                $value = (is_array($decoded) && isset($decoded[$field_arr[1]])) ? $decoded[$field_arr[1]] : '';
            } else {
                $value = '';
            }
        }
        $post_title = (!empty($this->post['title'])) ? $this->post['title'] : '';
        return "<span data-action='inline-editable' class='wpbe-numeric-content wpbe-td120'>" . esc_html($value) . "</span><button type='button' data-toggle='modal' class='wpbe-calculator' data-field='" . esc_attr($this->column_key) . "' data-item-id='" . esc_attr($this->post['id']) . "' data-item-name='" . esc_attr($post_title) . "' data-field-type='" . esc_attr($this->field_type) . "' data-target='#wpbe-modal-numeric-calculator'></button>";
    }

    private function numeric_without_calculator_field()
    {
        return "<span data-action='inline-editable' class='wpbe-numeric-content wpbe-td120'>" . esc_html($this->post[$this->decoded_column_key]) . "</span>";
    }

    private function checkbox_dual_mode_field()
    {
        $checked =  ($this->post[$this->decoded_column_key] && $this->post[$this->decoded_column_key] !== 'no') ? 'checked="checked"' : '';
        return "<label><input type='checkbox' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->post['id']) . "' value='yes' class='wpbe-dual-mode-checkbox wpbe-inline-edit-action' " . esc_attr($checked) . "><span>" . esc_html__('Yes', 'ithemeland-bulk-posts-editing-lite') . "</span></label>";
    }

    private function checkbox_field()
    {
        $checked = (isset($this->post[$this->decoded_column_key]) && $this->post[$this->decoded_column_key] == 'yes') ? 'checked="checked"' : '';
        $output = "<label><input type='checkbox' name='" . esc_attr($this->decoded_column_key . '-' . $this->post['id']) . "' value='yes' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->post['id']) . "' class='wpbe-dual-mode-checkbox wpbe-inline-edit-action' " . esc_attr($checked) . "> Yes</label>";

        return $output;
    }

    private function radio_field()
    {
        $output = '';
        if (!empty($this->meta_fields[$this->decoded_column_key]) && !empty($this->meta_fields[$this->decoded_column_key]['key_value'])) {
            $options = Meta_Field_Helper::key_value_field_to_array($this->meta_fields[$this->decoded_column_key]['key_value']);
            if (!empty($options) && is_array($options)) {
                $output .= "<select class='wpbe-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-item-id='" . esc_attr($this->post['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' data-field-type='" . esc_attr($this->field_type) . "'>";
                $output .= '<option value="">Select</option>';
                foreach ($options as $option_key => $option_value) {
                    $selected = isset($this->post[$this->decoded_column_key]) && $this->post[$this->decoded_column_key] == $option_key ? 'selected' : '';
                    $output .= "<option value='{$option_key}' $selected>{$option_value}</option>";
                }
                $output .= '</select>';
            }
        }

        return $output;
    }

    private function file_field()
    {
        $file_id = (isset($this->post[$this->decoded_column_key])) ? intval($this->post[$this->decoded_column_key]) : null;
        $file_url = !empty($file_id) ? wp_get_attachment_url($file_id) : 0;
        $file_url = !empty($file_url) ? esc_url($file_url) : '';
        return "<button type='button' data-toggle='modal' data-target='#wpbe-modal-file' class='wpbe-button wpbe-button-flat' data-item-id='" . esc_attr($this->post['id']) . "' data-item-name='" . esc_attr($this->post['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-file-id='" . $file_id . "' data-file-url='" . $file_url . "'><i class='wpbe-icon-edit'></i></button>";
    }

    private function select_custom_field_files_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wpbe-modal-custom-field-files' class='wpbe-button wpbe-button-flat' data-item-id='" . esc_attr($this->post['id']) . "' data-item-name='" . esc_attr($this->post['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'><i class='wpbe-icon-edit'></i></button>";
    }

    private function select_files_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wpbe-modal-select-files' class='wpbe-button wpbe-button-flat' data-item-id='" . esc_attr($this->post['id']) . "' data-item-name='" . esc_attr($this->post['title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'><i class='wpbe-icon-edit'></i></button>";
    }

    private function select_field()
    {
        $output = "<select class='wpbe-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-item-id='" . esc_attr($this->post['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' data-field-type='" . esc_attr($this->field_type) . "'>";
        if (!empty($this->column_data['options'])) {
            foreach ($this->column_data['options'] as $option_key => $option_value) {
                if (is_array($this->post[$this->decoded_column_key])) {
                    $selected = (in_array($option_key, $this->post[$this->decoded_column_key])) ? 'selected' : '';
                } else {
                    $selected = ($option_key == $this->post[$this->decoded_column_key]) ? 'selected' : '';
                }
                $output .= "<option value='{$option_key}' $selected>{$option_value}</option>";
            }
        } else {
            if ($this->column_data['field_type'] == 'custom_field') {
                if (!empty($this->meta_fields[$this->column_data['name']]) && !empty($this->meta_fields[$this->column_data['name']]['key_value'])) {
                    $options = Meta_Field_Helper::key_value_field_to_array($this->meta_fields[$this->column_data['name']]['key_value']);
                    if (!empty($options) && is_array($options)) {
                        foreach ($options as $option_key => $option_value) {
                            $selected = isset($this->post[$this->decoded_column_key]) && $this->post[$this->decoded_column_key] == $option_key ? 'selected' : '';
                            $output .= "<option value='{$option_key}' $selected>{$option_value}</option>";
                        }
                    }
                }
            }
        }
        $output .= '</select>';
        return $output;
    }

    private function multi_select_field()
    {
        $output = '';
        $checked = wp_get_post_terms($this->post['id'], $this->column_key, ['fields' => 'names']);
        $values = '';
        if (!empty($checked) && is_array($checked)) {
            $checked_iteration = 1;
            foreach ($checked as $id => $name) {
                $separate = '';
                if ($this->display_cell_content == 'short') {
                    if ($checked_iteration < 2 && count($checked) > 1) {
                        $separate = ", ";
                    }
                    $values .= '<span class="wpbe-category-item">' . esc_html($name) . $separate . ' </span>';
                    if ($checked_iteration >= 2 && count($checked) > 2) {
                        $values .= '...';
                        break;
                    }
                } else {
                    if ($checked_iteration < count($checked)) {
                        $separate = ", ";
                    }
                    $values .= '<span class="wpbe-category-item">' . esc_html($name) . $separate . ' </span>';
                }

                $checked_iteration++;
            }
        }

        $output = "<span data-toggle='modal' class='wpbe-is-taxonomy-modal wpbe-post-taxonomy' data-target='#wpbe-modal-post-taxonomy' data-item-id='" . esc_attr($this->post['id']) . "'>";
        $output .= (!empty($values)) ? strip_tags(wp_kses($values, Sanitizer::allowed_html()), '<span><ul><label><li>') : 'No items';
        $output .= "</span>";

        return $output;
    }

    private function select_user_field()
    {
        $user_id = intval($this->post['post_author']);
        if (!isset($this->users[$user_id])) {
            $user_object = get_user_by('id', $user_id);
            if (!($user_object instanceof \WP_User)) {
                $current_user = '';
            } else {
                $current_user = $user_object->user_login . ' [#' . $user_id .  ']';
            }
            $this->users[$user_id] = $current_user;
        }
        $post_title = (!empty($this->post['title'])) ? $this->post['title'] : '';
        return "<button type='button' data-toggle='modal' data-target='#wpbe-modal-select-user' data-current-user='" . esc_attr($this->users[$user_id]) . "' class='wpbe-button wpbe-button-flat' data-item-id='" . esc_attr($this->post['id']) . "' data-item-name='" . esc_attr($post_title) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'><i class='wpbe-icon-edit'></i></button>";
    }

    private function date_picker_field()
    {
        $date = (!empty($this->post[$this->decoded_column_key])) ? gmdate('Y/m/d', strtotime($this->post[$this->decoded_column_key])) : '';
        $clear_button = ($this->decoded_column_key != 'date_created') ? "<button type='button' class='wpbe-clear-date-btn wpbe-inline-edit-clear-date' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->post['id']) . "' value=''><img src='" . esc_url(WPBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>" : '';
        return "<input type='text' class='wpbe-datepicker wpbe-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->post['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' value='" . esc_attr($date) . "'>" . wp_kses($clear_button, Sanitizer::allowed_html());
    }

    private function date_time_picker_field()
    {
        $date = (!empty($this->post[$this->decoded_column_key])) ? gmdate('Y/m/d H:i', strtotime($this->post[$this->decoded_column_key])) : '';
        $clear_button = "<button type='button' class='wpbe-clear-date-btn wpbe-inline-edit-clear-date' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->post['id']) . "' value=''><img src='" . esc_url(WPBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>";
        return "<input type='text' class='wpbe-datetimepicker wpbe-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->post['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' value='" . esc_attr($date) . "'>" . wp_kses($clear_button, Sanitizer::allowed_html());
    }

    private function time_picker_field()
    {
        $date = (!empty($this->post[$this->decoded_column_key])) ? gmdate('H:i', strtotime($this->post[$this->decoded_column_key])) : '';
        $clear_button = "<button type='button' class='wpbe-clear-date-btn wpbe-inline-edit-clear-date' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->post['id']) . "' value=''><img src='" . esc_url(WPBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>";
        return "<input type='text' class='wpbe-timepicker wpbe-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->post['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' value='" . esc_attr($date) . "'>" . wp_kses($clear_button, Sanitizer::allowed_html());
    }
}
