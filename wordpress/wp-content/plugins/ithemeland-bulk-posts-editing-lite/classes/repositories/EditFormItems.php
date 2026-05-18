<?php

namespace wpbel\classes\repositories;

use wpbel\classes\helpers\Operator;

defined('ABSPATH') || exit();

class EditFormItems
{
    public static function general_tab()
    {
        return [
            'post_title' => [
                'name' => 'post_title',
                'label' => esc_html__('Post Title', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'text',
                'operators' => Operator::edit_text(),
                'extra_operators' => ['text_remove_duplicate' => esc_html__('Remove Duplicate', 'ithemeland-bulk-posts-editing-lite')],
                'disabled' => false,
                'show_variables' => true
            ],
            'post_name' => [
                'name' => '',
                'label' => esc_html__('Post Slug', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'text',
                'operators' => [],
                'disabled' => true,
                'show_variables' => true
            ],
            'post_content' => [
                'name' => '',
                'label' => esc_html__('Description', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'text',
                'operators' => [],
                'disabled' => true,
                'show_variables' => true
            ],
            'post_excerpt' => [
                'name' => '',
                'label' => esc_html__('Short Description', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'textarea',
                'operators' => [],
                'disabled' => true,
                'show_variables' => true
            ],
            'menu_order' => [
                'name' => '',
                'label' => esc_html__('Menu Order', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'number',
                'disabled' => true
            ],
            'post_parent' => [
                'name' => 'post_parent',
                'label' => esc_html__('Post Parent', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'post_select',
                'disabled' => false
            ],
            'comment_status' => [
                'name' => 'comment_status',
                'label' => esc_html__('Comment Status', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'select',
                'options' => [
                    'open' => esc_html__('Open', 'ithemeland-bulk-posts-editing-lite'),
                    'closed' => esc_html__('Closed', 'ithemeland-bulk-posts-editing-lite')
                ],
                'disabled' => false
            ],
            'ping_status' => [
                'name' => 'ping_status',
                'label' => esc_html__('Allow Pingback', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'select',
                'options' => [
                    'open' => esc_html__('Yes', 'ithemeland-bulk-posts-editing-lite'),
                    'closed' => esc_html__('No', 'ithemeland-bulk-posts-editing-lite')
                ],
                'disabled' => false
            ],
            'post_author' => [
                'name' => 'post_author',
                'label' => esc_html__('Author', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'user_select',
                'disabled' => false
            ],
            '_thumbnail_id' => [
                'name' => '',
                'label' => esc_html__('Image', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'image_upload',
                'disabled' => true
            ]
        ];
    }

    public static function taxonomies_tab()
    {
        return [
            'taxonomy' => [
                'name' => 'taxonomy',
                'label' => '',
                'field_type' => 'taxonomy_select',
                'operators' => Operator::edit_taxonomy(),
                'is_taxonomy_group' => true,
                'disabled' => false
            ]
        ];
    }

    public static function type_tab()
    {
        return [
            'post_type' => [
                'name' => '',
                'label' => esc_html__('Post Type', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'select',
                'options' => [],
                'disabled' => true,
                'placeholder' => esc_html__('Select Type ...', 'ithemeland-bulk-posts-editing-lite')
            ],
            'post_status' => [
                'name' => 'post_status',
                'label' => esc_html__('Post Status', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'select',
                'options' => [],
                'disabled' => false,
                'placeholder' => esc_html__('Select', 'ithemeland-bulk-posts-editing-lite')
            ],
            'sticky' => [
                'name' => '',
                'label' => esc_html__('Sticky', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'select',
                'options' => [],
                'disabled' => true,
                'placeholder' => esc_html__('Select ...', 'ithemeland-bulk-posts-editing-lite')
            ],
            'post_date' => [
                'name' => '',
                'label' => esc_html__('Date Published', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'datetime',
                'disabled' => true,
                'placeholder' => esc_html__('Date Published ...', 'ithemeland-bulk-posts-editing-lite')
            ],
            'post_url' => [
                'name' => '',
                'label' => esc_html__('Post URL', 'ithemeland-bulk-posts-editing-lite'),
                'field_type' => 'text',
                'disabled' => true,
                'placeholder' => esc_html__('Post URL ...', 'ithemeland-bulk-posts-editing-lite'),
                'data_type' => ''
            ]
        ];
    }
}
