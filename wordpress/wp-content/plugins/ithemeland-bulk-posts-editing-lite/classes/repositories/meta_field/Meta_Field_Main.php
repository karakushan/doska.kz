<?php

namespace wpbel\classes\repositories\meta_field;

defined('ABSPATH') || exit(); // Exit if accessed directly

class Meta_Field_Main
{
    public $meta_fields_option_name;

    const TEXTINPUT = "textinput";
    const TEXT = "text";
    const TEXTAREA = "textarea";
    const CHECKBOX = "checkbox";
    const RADIO = "radio";
    const ARRAY_TYPE = "array";
    const CALENDAR = "calendar";
    const EMAIL = "email";
    const PASSWORD = "password";
    const URL = "url";
    const IMAGE = "image";
    const FILE = "file";
    const EDITOR = "editor";
    const SELECT = "select";
    const MULTI_SELECT = "multi_select";
    const TAXONOMY = "taxonomy";
    const COLOR = "color_picker";
    const DATE = "date_picker";
    const DATE_TIME = "date_time_picker";
    const TIME = "time_picker";

    const STRING_TYPE = "string";
    const NUMBER = "number";

    public static function get_fields_name_have_operator()
    {
        add_action('wp_ajax_get_meta_fields_json', 'handle_get_meta_fields_json');
        add_action('wp_ajax_nopriv_get_meta_fields_json', 'handle_get_meta_fields_json');
        return [
            // self::TEXTAREA,
            self::EDITOR,
            self::EMAIL,
            self::PASSWORD,
            self::URL,
            self::ARRAY_TYPE,
        ];
    }

    public static function get_main_types()
    {
        return [
            self::TEXTINPUT => esc_html__('TextInput', 'ithemeland-bulk-posts-editing-lite'),
            self::TEXTAREA => esc_html__('TextArea', 'ithemeland-bulk-posts-editing-lite'),
            self::CHECKBOX => esc_html__('Checkbox', 'ithemeland-bulk-posts-editing-lite'),
            self::RADIO => esc_html__('Radio', 'ithemeland-bulk-posts-editing-lite'),
            self::ARRAY_TYPE => esc_html__('Array', 'ithemeland-bulk-posts-editing-lite'),
            self::CALENDAR => esc_html__('Calendar', 'ithemeland-bulk-posts-editing-lite'),
            self::EMAIL => esc_html__('Email', 'ithemeland-bulk-posts-editing-lite'),
            self::PASSWORD => esc_html__('Password', 'ithemeland-bulk-posts-editing-lite'),
            self::URL => esc_html__('Url', 'ithemeland-bulk-posts-editing-lite'),
            self::IMAGE => esc_html__('Image', 'ithemeland-bulk-posts-editing-lite'),
            self::FILE => esc_html__('File', 'ithemeland-bulk-posts-editing-lite'),
            self::EDITOR => esc_html__('Editor', 'ithemeland-bulk-posts-editing-lite'),
            self::SELECT => esc_html__('Select', 'ithemeland-bulk-posts-editing-lite'),
        ];
    }

    public static function get_supported_acf_field_types()
    {
        return [
            'text',
            'textarea',
            'number',
            'checkbox',
            'radio',
            'email',
            'image',
            'file',
            'select',
            'multi_select',
            'wysiwyg',
            'password',
            'url',
            'taxonomy',
            'date_picker',
            'date_time_picker',
            'time_picker',
            'color_picker',
        ];
    }

    public static function get_sub_types()
    {
        return [
            self::STRING_TYPE => esc_html__('String', 'ithemeland-bulk-posts-editing-lite'),
            self::NUMBER => esc_html__('Number', 'ithemeland-bulk-posts-editing-lite'),
        ];
    }

    public function update(array $meta_fields)
    {
        return update_option($this->meta_fields_option_name, $meta_fields);
    }

    public function get()
    {
        $meta_fields = get_option($this->meta_fields_option_name);
        return !empty($meta_fields) ? $meta_fields : [];
    }
}
