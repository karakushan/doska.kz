<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\repositories\search\Search_Main;
use wpbel\classes\helpers\Post_Helper;

class Search extends Search_Main
{
    protected $option_values_option_name = "wpbe_filter_option_values";

    public function __construct($post_type = "")
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        $this->set_option_name($post_type);
    }

    private function set_option_name(string $post_type)
    {
        $post_type = sanitize_text_field($post_type);
        $this->filter_profile_option_name = $this->get_filter_profile_option_name($post_type);
        $this->use_always_table = $this->get_filter_profile_use_always_option_name($post_type);
        $this->current_data_option_name = "wpbe_{$post_type}_filter_profile_current_data";
    }

    private function get_filter_profile_option_name(string $post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        return "wpbe_{$post_type}_filter_profile";
    }

    private function get_filter_profile_use_always_option_name(string $post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        return "wpbe_{$post_type}_filter_profile_use_always";
    }

    public function set_default_item()
    {
        $post_repository = new Post();
        $post_types = $post_repository->get_post_types();
        if (!empty($post_types)) {
            foreach ($post_types as $post_type => $label) {
                $post_type = Post_Helper::get_post_type_name($post_type);
                $method = "set_{$post_type}_default_filter";
                $this->{$method}();
            }
        }
    }

    private function set_post_default_filter()
    {
        $default_item['default'] = [
            'name' => esc_html__('All Posts', 'ithemeland-bulk-posts-editing-lite'),
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'default',
            'filter_data' => []
        ];
        $this->update_use_always('default', $this->get_filter_profile_use_always_option_name('post'));
        return update_option($this->get_filter_profile_option_name('post'), $default_item);
    }

    private function set_page_default_filter()
    {
        $default_item['default'] = [
            'name' => esc_html__('All Pages', 'ithemeland-bulk-posts-editing-lite'),
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'default',
            'filter_data' => []
        ];
        $this->update_use_always('default', $this->get_filter_profile_use_always_option_name('page'));
        return update_option($this->get_filter_profile_option_name('page'), $default_item);
    }

    private function set_custom_post_default_filter()
    {
        $default_item['default'] = [
            'name' => esc_html__('All Custom Posts', 'ithemeland-bulk-posts-editing-lite'),
            'date_modified' => gmdate('Y-m-d H:i:s', time()),
            'key' => 'default',
            'filter_data' => []
        ];
        $this->update_use_always('default', $this->get_filter_profile_use_always_option_name('custom_post'));
        return update_option($this->get_filter_profile_option_name('custom_post'), $default_item);
    }


    public function get_option_values()
    {
        return get_option($this->option_values_option_name, []);
    }

    public function update_option_values($data)
    {
        return update_option($this->option_values_option_name, Sanitizer::array($data));
    }
}
