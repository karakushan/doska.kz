<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit();

use wpbel\classes\helpers\Operator;

class FilterFormItems
{
    public static function general_tab()
    {
        return [
            'post_ids' => [
                'name' => 'post_ids',
                'label' => esc_html__('Post ID(s)', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => 'post_ids',
                'field_type' => 'text',
                'operators' => [
                    'exact' => esc_html__('Exact', 'ithemeland-bulk-posts-editing-lite')
                ],
                'placeholder' => esc_html__('for example: 1,2,3 or 1-10 or 1,2,3|10-20', 'ithemeland-bulk-posts-editing-lite'),
                'extra_html' => '<label class="wpbe-ml10"><input type="checkbox" id="wpbe-filter-form-post-ids-parent-only" value="yes">' . esc_html__('Only Parent Posts', 'ithemeland-bulk-posts-editing-lite') . '</label>',
                'disabled' => false,
            ],
            'post_title' => [
                'name' => 'post_title',
                'label' => esc_html__('Post Title', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => 'post_title',
                'field_type' => 'text',
                'operators' => Operator::filter_text(),
                'placeholder' => esc_html__('Post Title ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => false,
            ],
            'post_content' => [
                'name' => 'post_content',
                'label' => esc_html__('Post Content', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => 'post_content',
                'field_type' => 'text',
                'operators' => Operator::filter_text(),
                'placeholder' => esc_html__('Post Content ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => false,
            ],
            'post_excerpt' => [
                'name' => '',
                'label' => esc_html__('Post Excerpt', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => 'post_excerpt',
                'field_type' => 'text',
                'operators' => [],
                'placeholder' => esc_html__('Post Excerpt ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ],
            'post_slug' => [
                'name' => '',
                'label' => esc_html__('Post Slug', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => 'post_slug',
                'field_type' => 'text',
                'operators' => [],
                'placeholder' => esc_html__('Post Slug ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ],
            'post_url' => [
                'name' => '',
                'label' => esc_html__('Post URL', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => 'post_url',
                'field_type' => 'text',
                'operators' => [],
                'placeholder' => esc_html__('Post URL ...', 'ithemeland-bulk-posts-editing-lite'),
                'disabled' => true
            ],
            'author' => [
                'name' => '',
                'label' => esc_html__('By Author', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => 'author',
                'field_type' => 'user_select',
                'select2' => true,
                'placeholder' => esc_html__('Username ...', 'ithemeland-bulk-posts-editing-lite'),
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
                'filter_type' => 'taxonomy',
                'field_type' => 'taxonomy_select',
                'operators' => Operator::filter_multi_select(),
                'is_taxonomy_group' => true
            ]
        ];
    }

    public static function type_tab()
    {
        return [
            'post_status' => [
                'name' => 'post_status',
                'label' => esc_html__('Post Status', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => 'post_status',
                'field_type' => 'select',
                'options' => [], // Will be filled from $post_statuses in template
                'disabled' => false
            ],
            'comment_status' => [
                'name' => '',
                'label' => esc_html__('Comment Status', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => '',
                'field_type' => 'select',
                'options' => [],
                'disabled' => true
            ],
            'ping_status' => [
                'name' => '',
                'label' => esc_html__('Allow Pingback', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => '',
                'field_type' => 'select',
                'options' => [],
                'disabled' => true
            ],
            'sticky' => [
                'name' => '',
                'label' => esc_html__('Sticky', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => '',
                'field_type' => 'select',
                'options' => [],
                'disabled' => true
            ],
            'date_published' => [
                'name' => '',
                'label' => esc_html__('Date Published', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => '',
                'field_type' => 'from_to_date',
                'disabled' => true
            ],
            'date_published_gmt' => [
                'name' => '',
                'label' => esc_html__('Date Published GMT', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => '',
                'field_type' => 'from_to_date',
                'disabled' => true
            ],
            'date_modified' => [
                'name' => '',
                'label' => esc_html__('Date Modified', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => '',
                'field_type' => 'from_to_date',
                'disabled' => true
            ],
            'date_modified_gmt' => [
                'name' => '',
                'label' => esc_html__('Date Modified GMT', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => '',
                'field_type' => 'from_to_date',
                'disabled' => true
            ],
            'menu_order' => [
                'name' => '',
                'label' => esc_html__('Menu Order', 'ithemeland-bulk-posts-editing-lite'),
                'filter_type' => '',
                'field_type' => 'from_to_number',
                'disabled' => true
            ]
        ];
    }
}
