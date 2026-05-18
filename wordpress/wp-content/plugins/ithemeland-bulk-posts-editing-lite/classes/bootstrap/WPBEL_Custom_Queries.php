<?php

namespace wpbel\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Post_Helper;
use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\repositories\Post;

class WPBEL_Custom_Queries
{
    private static $instance;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    public function __construct()
    {
        add_filter('posts_where', [$this, 'general_column_filter'], 10, 2);
        add_filter('posts_where', [$this, 'meta_filter'], 10, 2);
    }

    public function general_column_filter($where, $wp_query)
    {
        global $wpdb;
        if ($search_term = $wp_query->get('wpbe_general_column_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $field = sanitize_text_field($item['field']);
                    $value = is_array($item['value']) ? Sanitizer::array($item['value']) : trim(sanitize_text_field($item['value']));
                    switch ($item['operator']) {
                        case 'like':
                            $custom_where = "(posts.{$field} LIKE '%{$value}%')";
                            break;
                        case 'exact':
                            $custom_where = "(posts.{$field} = '{$value}')";
                            break;
                        case 'not':
                            $custom_where = "(posts.{$field} != '{$value}')";
                            break;
                        case 'begin':
                            $custom_where = "(posts.{$field} LIKE '{$value}%')";
                            break;
                        case 'end':
                            $custom_where = "(posts.{$field} LIKE '%{$value}')";
                            break;
                        case 'in':
                            $custom_where = "(posts.{$field} IN ({$value}))";
                            break;
                        case 'not_in':
                            $custom_where = "(posts.{$field} NOT IN ({$value}))";
                            break;
                        case 'between':
                            $value = (is_numeric($value[1])) ? "{$value[0]} AND {$value[1]}" : "'{$value[0]}' AND '{$value[1]}'";
                            $custom_where = "(posts.{$field} BETWEEN {$value})";
                            break;
                        case '>':
                            $custom_where = "(posts.{$field} > {$value})";
                            break;
                        case '<':
                            $custom_where = "(posts.{$field} < {$value})";
                            break;
                        case '>_with_quotation':
                            $custom_where = "(posts.{$field} > '{$value}')";
                            break;
                        case '<_with_quotation':
                            $custom_where = "(posts.{$field} < '{$value}')";
                            break;
                        default:
                            $custom_where = '';
                            break;
                    }
                    $post_repository = new Post();
                    $posts_ids = $post_repository->get_post_ids_by_custom_query('', $custom_where, [Post_Helper::get_active_post_type()]);
                    if ($field == 'sticky') {
                        $posts_ids_array = explode(',', $posts_ids);
                        $value = (!empty($value) && is_array($value)) ? $value : [];
                        if (is_array($value) && is_array($posts_ids_array)) {
                            if ($item['operator'] == 'yes') {
                                foreach ($posts_ids_array as $key => $post_id) {
                                    if (!in_array($post_id, $value)) {
                                        unset($posts_ids_array[$key]);
                                    }
                                }
                            } else {
                                foreach ($posts_ids_array as $key => $post_id) {
                                    if (in_array($post_id, $value)) {
                                        unset($posts_ids_array[$key]);
                                    }
                                }
                            }
                            $posts_ids = implode(',', $posts_ids_array);
                        }
                    }
                    $ids = (!empty($posts_ids)) ? $posts_ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }

        return $where;
    }

    public function meta_filter($where, $wp_query)
    {
        global $wpdb;
        $post_repository = new Post();
        $join = "LEFT JOIN $wpdb->postmeta AS postmeta ON (posts.ID = postmeta.post_id)";
        if ($search_term = $wp_query->get('wpbe_meta_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $key = sanitize_text_field($item['key']);
                    $value = is_array($item['value']) ? Sanitizer::array($item['value']) : trim(sanitize_text_field($item['value']));
                    switch ($item['operator']) {
                        case 'like':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$value}%')";
                            break;
                        case 'exact':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value = '{$value}')";
                            break;
                        case 'not':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value != '{$value}')";
                            break;
                        case 'begin':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '{$value}%')";
                            break;
                        case 'end':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$value}')";
                            break;
                        case 'in':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value IN ({$value}))";
                            break;
                        case 'between':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value BETWEEN {$value[0]} AND {$value[1]})";
                            break;
                        case 'between_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value BETWEEN '{$value[0]}' AND '{$value[1]}')";
                            break;
                        case '<=':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value <= {$value})";
                            break;
                        case '>=':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value >= {$value})";
                            break;
                        case '<=_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value <= '{$value}')";
                            break;
                        case '>=_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value >= '{$value}')";
                            break;
                    }
                    $post_repository = new Post();
                    $posts_ids = $post_repository->get_post_ids_by_custom_query($join, $custom_where, [Post_Helper::get_active_post_type()]);
                    $ids = (!empty($posts_ids)) ? $posts_ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }
        return $where;
    }
}
