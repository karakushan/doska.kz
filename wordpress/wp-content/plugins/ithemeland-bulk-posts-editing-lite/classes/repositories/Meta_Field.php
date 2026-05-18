<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\meta_field\Meta_Field_Main;
use wpbel\classes\helpers\Post_Helper;

class Meta_Field extends Meta_Field_Main
{
    public function __construct(string $post_type = "")
    {
        $post_type = (!empty($post_type)) ? $post_type : Post_Helper::get_active_post_type();
        $this->set_option_name($post_type);
    }

    private function set_option_name(string $post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        $this->meta_fields_option_name = "wpbe_{$post_type}_meta_fields";
    }

    public function get_reserved_field_names()
    {
        $column_repository = new Column();
        return array_merge($column_repository->get_all_column_keys(), ['id']);
    }
}
