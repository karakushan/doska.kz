<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit();

class NewFormItems
{
    public static function general_tab()
    {
        return [
            'post_title' => [
                'name' => '',
                'label' => esc_html__('Post Title', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'text',
                'placeholder' => esc_html__('Post Title ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ],
            'post_slug' => [
                'name' => '',
                'label' => esc_html__('Post Slug', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'text',
                'placeholder' => esc_html__('Post Slug ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ],
            'post_password' => [
                'name' => '',
                'label' => esc_html__('Post Password', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'text',
                'placeholder' => esc_html__('Post Password ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ],
            'post_content' => [
                'name' => '',
                'label' => esc_html__('Description', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'text',
                'placeholder' => esc_html__('Description ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ],
            'post_excerpt' => [
                'name' => '',
                'label' => esc_html__('Short Description', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'textarea',
                'placeholder' => esc_html__('Short Description ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ],
            'menu_order' => [
                'name' => '',
                'label' => esc_html__('Menu Order', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'number',
                'placeholder' => esc_html__('Menu Order ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ],
            'post_parent' => [
                'name' => '',
                'label' => esc_html__('Post Parent', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'post_select',
                'disabled' => true
            ],
            'comment_status' => [
                'name' => '',
                'label' => esc_html__('Comment Status', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'select',
                'options' => [
                    'open' => esc_html__('Open', 'ithemeland-bulk-posts-editing-lite'),
                    'closed' => esc_html__('Closed', 'ithemeland-bulk-posts-editing-lite')
                ],
                'disabled' => true
            ],
            'ping_status' => [
                'name' => '',
                'label' => esc_html__('Allow Pingback', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'select',
                'options' => [
                    'open' => esc_html__('Yes', 'ithemeland-bulk-posts-editing-lite'),
                    'closed' => esc_html__('No', 'ithemeland-bulk-posts-editing-lite')
                ],
                'disabled' => true
            ],
            'post_author' => [
                'name' => '',
                'label' => esc_html__('Author', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'user_select',
                'placeholder' => esc_html__('Username ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ],
            '_thumbnail_id' => [
                'name' => '',
                'label' => esc_html__('Image', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'image_upload',
                'disabled' => true,
                'data_type' => 'meta_field'
            ]
        ];
    }

    public static function taxonomies_tab()
    {
        return [
            'taxonomy' => [
                'name' => '',
                'label' => '',
                'field_type' => 'taxonomy_select',
                'is_taxonomy_group' => true,
                'disabled' => true
            ]
        ];
    }

    public static function type_tab()
    {
        return [
            'post_status' => [
                'name' => '',
                'label' => esc_html__('Post Status', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'select',
                'options' => [],
                'disabled' => true,
                'placeholder' => esc_html__('Select', 'ithemeland-bulk-posts-editing-lite')
            ],
            'post_date' => [
                'name' => '',
                'label' => esc_html__('Date Published', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'datetime',
                'placeholder' => esc_html__('Date Published ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ]
        ];
    }
}
