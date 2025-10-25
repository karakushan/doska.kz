<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Form_Builder_Wp_Post_Types
{
    public static function init()
    {
        add_action('init', array(__CLASS__, 'register_post_types'), 5);
    }
    
    public static function register_post_types()
    {
        if (!is_blog_installed() || post_type_exists('wpfbform')) {
            return;
        }
        
        register_post_type("wpfbform", apply_filters('wpfb_form_register_post_type', array(
            'labels' => array(
                'name' => __('Forms', 'form-builder-wp'),
                'singular_name' => __('Form', 'form-builder-wp'),
                'menu_name' => _x('Forms', 'Admin menu name', 'form-builder-wp'),
                'add_new' => __('Add Form', 'form-builder-wp'),
                'add_new_item' => __('Add New Form', 'form-builder-wp'),
                'edit' => __('Edit', 'form-builder-wp'),
                'edit_item' => __('Edit Form', 'form-builder-wp'),
                'new_item' => __('New Form', 'form-builder-wp'),
                'view' => __('View Form', 'form-builder-wp'),
                'view_item' => __('View Form', 'form-builder-wp'),
                'search_items' => __('Search Forms', 'form-builder-wp'),
                'not_found' => __('No Forms found', 'form-builder-wp'),
                'not_found_in_trash' => __('No Forms found in trash', 'form-builder-wp'),
                'parent' => __('Parent Form', 'form-builder-wp')
            ),
            'description' => __('This is where you can add new form.', 'form-builder-wp'),
            'public' => true,
            'has_archive' => false,
            'show_in_nav_menus' => false,
            'exclude_from_search' => true,
        	'rewrite' => false,
            'show_ui' => true,
            'show_in_menu' => 'form-builder-wp',
            'query_var' => true,
            'capability_type' => 'wpfbform',
        	'map_meta_cap'=> true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array( 'title','editor','elementor')
        )));
    }
}
Form_Builder_Wp_Post_Types::init();