<?php

namespace wpbel\classes\services\filter;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Post_Helper;
use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\repositories\Post;
use wpbel\classes\repositories\Setting;

class Post_Filter_Service
{
    private static $instance;

    private $field_methods;
    private $query_args;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->field_methods = $this->get_field_methods();
    }

    public function get_filtered_posts($data, $args)
    {
        $this->create_query($data, $args);
        $this->query_args = apply_filters('wpbe_filter_query_args', $this->query_args, $data);
        $post_repository = new Post();
        $query = $post_repository->get_posts($this->query_args);

        return [
            'max_num_pages' => !empty($query->max_num_pages) ? $query->max_num_pages : 0,
            'found_posts' => $query->found_posts,
            'post_ids' => !empty($query->posts) ? $query->posts : [],
        ];
    }

    private function create_query($data, $args)
    {
        $this->query_args = $args;
        $this->set_required_args();

        if (is_array($data) && !empty($data)) {
            if (isset($data['search_type']) && $data['search_type'] == 'quick_search') {
                if (!empty($data['quick_search_text'])) {
                    switch ($data['quick_search_field']) {
                        case 'title':
                            $this->query_args['wpbe_general_column_filter'][] = [
                                'field' => 'post_title',
                                'value' => $data['quick_search_text'],
                                'operator' => $data['quick_search_operator']
                            ];
                            break;
                        case 'id':
                            $ids = Post_Helper::posts_id_parser($data['quick_search_text']);
                            $this->query_args['wpbe_general_column_filter'][] = [
                                'field' => 'ID',
                                'value' => $ids,
                                'operator' => "in"
                            ];
                            break;
                    }
                }
            } else {
                if (empty($data['fields'])) {
                    return;
                }

                foreach ($data['fields'] as $item) {
                    if (!isset($this->field_methods[$item['filter_type']]) || !method_exists($this, $this->field_methods[$item['filter_type']])) {
                        continue;
                    }

                    $method = $this->field_methods[$item['filter_type']];
                    $this->{$method}($item);
                }
            }
        }
    }

    private function set_required_args()
    {
        $settings_repository = new Setting();
        $settings = $settings_repository->get_settings();
        $column_name = (isset($settings['default_sort_by'])) ? $settings['default_sort_by'] : '';
        $sort_type = (isset($settings['default_sort'])) ? $settings['default_sort'] : '';
        if (!isset($this->query_args['post_type'])) {
            $this->query_args['post_type'] = 'post';
        }
        if (!isset($this->query_args['fields'])) {
            $this->query_args['fields'] = 'ids';
        }

        if (!isset($this->query_args['orderby'])) {
            $this->set_orderby($column_name);
        }

        if (!isset($this->query_args['order'])) {
            $this->query_args['order'] = $sort_type;
        }

        if (!isset($this->query_args['posts_per_page'])) {
            $this->query_args['posts_per_page'] = ($settings['count_per_page'] > Setting::MAX_COUNT_PER_PAGE) ? Setting::MAX_COUNT_PER_PAGE : intval($settings['count_per_page']);
        }

        if ($this->query_args['posts_per_page'] > 1) {
            if (!isset($this->query_args['paginate'])) {
                $this->query_args['paginate'] = true;
            }

            if (!isset($this->query_args['paged'])) {
                $this->query_args['paged'] = 1;
            }
        }
    }

    private function set_orderby($orderby)
    {
        switch ($orderby) {
            case 'id':
                $this->query_args['orderby'] = 'ID';
                break;
            case 'title':
                $this->query_args['orderby'] = 'post_title';
                break;
            case 'post_date':
                $this->query_args['orderby'] = 'post_date';
                break;
        }
    }

    private function get_field_methods()
    {
        return [
            'post_ids' => 'post_ids_filter',
            'post_title' => 'post_title_filter',
            'post_content' => 'post_content_filter',
            'post_excerpt' => 'post_excerpt_filter',
            'post_slug' => 'post_slug_filter',
            'post_url' => 'post_url_filter',
            'post_date' => 'post_date_filter',
            'taxonomy' => 'post_taxonomies_filter',
            'menu_order' => 'post_menu_order_filter',
            'post_type' => 'post_type_filter',
            'post_status' => 'post_status_filter',
            'post_author' => 'post_author_filter',
            'post_name' => 'post_name_filter',
            'post_date_gmt' => 'post_date_gmt_filter',
            'post_published' => 'post_published_filter',
            'post_published_gmt' => 'post_published_gmt_filter',
            'comment_status' => 'comment_status_filter',
            'ping_status' => 'ping_status_filter',
            'sticky' => 'sticky_filter',
            'custom_field' => 'post_custom_fields_filter',
        ];
    }

    private function post_ids_filter($item)
    {
        $ids = Post_Helper::posts_id_parser($item['value']);
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'ID',
            'value' => $ids,
            'parent_only' => (isset($item['parent_only']) && $item['parent_only'] == 'yes') ? true : false,
            'operator' => "in"
        ];
    }

    private function post_title_filter($item)
    {
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'post_title',
            'value' => $item['value'],
            'parent_only' => false,
            'operator' => $item['operator']
        ];
    }

    private function post_content_filter($item)
    {
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'post_content',
            'value' => $item['value'],
            'operator' => $item['operator']
        ];
    }

    private function post_excerpt_filter($item)
    {
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'post_excerpt',
            'value' => $item['value'],
            'operator' => $item['operator']
        ];
    }

    private function post_slug_filter($item)
    {
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'post_name',
            'value' => urlencode($item['value']),
            'operator' => $item['operator']
        ];
    }

    private function post_url_filter($item)
    {
        $this->query_args['meta_query'][] =  $this->get_meta_query('_post_url', $item['value'], $item['operator']);
    }

    private function post_date_filter($item)
    {
        $from = (isset($item['value']['from']) && $item['value']['from'] != '') ? gmdate('Y-m-d', strtotime($item['value']['from'])) : null;
        $to = (isset($item['value']['to']) && $item['value']['to'] != '') ? gmdate('Y-m-d', strtotime($item['value']['to'])) : null;

        if (!is_null($from) & !is_null($to)) {
            $value = [$from, $to];
            $operator = 'between';
        } else if (!is_null($from)) {
            $value = $from;
            $operator = '>=';
        } else {
            $value = $to;
            $operator = '<=';
        }

        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'post_date',
            'value' => $value,
            'operator' => $operator,
        ];
    }

    private function post_date_gmt_filter($item)
    {
        $from = (isset($item['value']['from']) && $item['value']['from'] != '') ? gmdate('Y-m-d', strtotime($item['value']['from'])) : null;
        $to = (isset($item['value']['to']) && $item['value']['to'] != '') ? gmdate('Y-m-d', strtotime($item['value']['to'])) : null;

        if (!is_null($from) & !is_null($to)) {
            $value = [$from, $to];
            $operator = 'between';
        } else if (!is_null($from)) {
            $value = $from;
            $operator = '>=';
        } else {
            $value = $to;
            $operator = '<=';
        }

        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'post_date_gmt',
            'value' => $value,
            'operator' => $operator,
        ];
    }

    private function post_published_filter($item)
    {
        $from = (isset($item['value']['from']) && $item['value']['from'] != '') ? gmdate('Y-m-d', strtotime($item['value']['from'])) : null;
        $to = (isset($item['value']['to']) && $item['value']['to'] != '') ? gmdate('Y-m-d', strtotime($item['value']['to'])) : null;

        if (!is_null($from) & !is_null($to)) {
            $value = [$from, $to];
            $operator = 'between';
        } else if (!is_null($from)) {
            $value = $from;
            $operator = '>=';
        } else {
            $value = $to;
            $operator = '<=';
        }

        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'post_published',
            'value' => $value,
            'operator' => $operator,
        ];
    }

    private function post_published_gmt_filter($item)
    {
        $from = (isset($item['value']['from']) && $item['value']['from'] != '') ? gmdate('Y-m-d', strtotime($item['value']['from'])) : null;
        $to = (isset($item['value']['to']) && $item['value']['to'] != '') ? gmdate('Y-m-d', strtotime($item['value']['to'])) : null;

        if (!is_null($from) & !is_null($to)) {
            $value = [$from, $to];
            $operator = 'between';
        } else if (!is_null($from)) {
            $value = $from;
            $operator = '>=';
        } else {
            $value = $to;
            $operator = '<=';
        }

        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'post_published_gmt',
            'value' => $value,
            'operator' => $operator,
        ];
    }

    private function post_taxonomies_filter($item)
    {
        if (!empty($item['value'])) {
            $tax_item = $this->get_tax_query($item['name'], $item['value'], $item['operator']);
            $this->query_args['tax_query'][] = $tax_item;
        }
    }

    private function post_menu_order_filter($item)
    {
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'menu_order',
            'value' => [floatval($item['value']['from']), floatval($item['value']['to'])],
            'operator' => 'between'
        ];
    }

    private function post_type_filter($item)
    {
        $tax_item = $this->get_tax_query('post_type', $item['value'], 'or', 'slug');
        $this->query_args['tax_query'][] = [$tax_item];
    }

    private function post_status_filter($item)
    {
        $this->query_args['post_status'] = (is_array($item['value'])) ? Sanitizer::array($item['value']) : sanitize_text_field($item['value']);
    }

    private function post_author_filter($item)
    {
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'post_author',
            'value' => intval($item['value']),
            'operator' => 'exact'
        ];
    }

    private function post_custom_fields_filter($item)
    {
        switch ($item['field_type']) {
            case 'date':
                $from = (!empty($item['value']['from'])) ? gmdate('Y/m/d', strtotime($item['value']['from'])) : null;
                $to = (!empty($item['value']['to'])) ? gmdate('Y/m/d', strtotime($item['value']['to'])) : null;

                if (!empty($from) & !empty($to)) {
                    $value = [$from, $to];
                    $operator = 'BETWEEN';
                } else if (!empty($from)) {
                    $value = $from;
                    $operator = '>=';
                } else {
                    $value = $to;
                    $operator = '<=';
                }

                if (!empty($value)) {
                    $this->query_args['meta_query'][] = [
                        'key' => $item['name'],
                        'value' => $value,
                        'compare' => $operator,
                        'type' => 'DATE'
                    ];
                }
                break;
            case 'time':
                $from = (!empty($item['value']['from'])) ? gmdate('H:i', strtotime($item['value']['from'])) : null;
                $to = (!empty($item['value']['to'])) ? gmdate('H:i', strtotime($item['value']['to'])) : null;

                if (!empty($from) & !empty($to)) {
                    $value = [$from, $to];
                    $operator = 'BETWEEN';
                } else if (!empty($from)) {
                    $value = $from;
                    $operator = '>=';
                } else {
                    $value = $to;
                    $operator = '<=';
                }

                if (!empty($value)) {
                    $this->query_args['meta_query'][] = [
                        'key' => $item['name'],
                        'value' => $value,
                        'compare' => $operator,
                        'type' => 'TIME'
                    ];
                }
                break;
            case 'number':
                $from = (!empty($item['value']['from'])) ? floatval($item['value']['from']) : null;
                $to = (!empty($item['value']['to'])) ? floatval($item['value']['to']) : null;
                if (!is_null($from) & !is_null($to)) {
                    $this->query_args['meta_query'][] = [
                        'relation' => 'AND',
                        [
                            'key' => $item['name'],
                            'value' => $from,
                            'compare' => '>=',
                            'type' => 'DECIMAL'
                        ],
                        [
                            'key' => $item['name'],
                            'value' => $to,
                            'compare' => '<=',
                            'type' => 'DECIMAL'
                        ]
                    ];
                } else if (!is_null($from)) {
                    $this->query_args['meta_query'][] = [
                        'key' => $item['name'],
                        'value' => $from,
                        'compare' => '>=',
                        'type' => 'DECIMAL'
                    ];
                } else {
                    $this->query_args['meta_query'][] = [
                        'key' => $item['name'],
                        'value' => $to,
                        'compare' => '<=',
                        'type' => 'DECIMAL'
                    ];
                }
                break;
            case 'text':
            case 'email':
            case 'textarea':
            case 'string':
            case 'textinput':
            case 'password':
            case 'url':
                if (!empty($item['value'])) {
                    $this->query_args['meta_query'][] = [
                        'key' => $item['name'],
                        'value' => $item['value'],
                        'compare' => isset($item['operator']) ? $item['operator'] : '=',
                    ];
                }
                break;
            case 'checkbox':
                if (!empty($item['value'])) {
                    switch ($item['value']) {
                        case 'yes':
                            $this->query_args['meta_query'][] = [
                                'key' => $item['name'],
                                'value' => $item['value'],
                                'compare' => '=',
                            ];
                            break;
                        case 'no':
                            $this->query_args['meta_query'][] = [
                                'relation' => 'OR',
                                [
                                    'key' => $item['name'],
                                    'value' => $item['value'],
                                    'compare' => '=',
                                ],
                                [
                                    'key' => $item['name'],
                                    'compare' => 'NOT EXISTS',
                                ]
                            ];
                            break;
                    }
                }
                break;
            case 'select':
                if (!empty($item['value'])) {
                    $this->query_args['meta_query'][] = [
                        'key' => $item['name'],
                        'value' => $item['value'],
                        'compare' => '=',
                    ];
                }
                break;
        }
    }

    private function get_tax_query($taxonomy, $terms, $operator = null, $field = null)
    {
        $field = !empty($field) ? $field : 'term_id';
        $taxonomy = urlencode($taxonomy);

        switch ($operator) {
            case null:
                $tax_item = [
                    'taxonomy' => $taxonomy,
                    'field' => $field,
                    'terms' => $terms,
                    'operator' => 'AND'
                ];
                break;
            case 'or':
                $tax_item = [
                    'taxonomy' => $taxonomy,
                    'field' => $field,
                    'terms' => $terms,
                    'operator' => 'IN'
                ];
                break;
            case 'and':
                $tax_item['relation'] = 'AND';
                if (is_array($terms) && !empty($terms)) {
                    foreach ($terms as $value) {
                        $tax_item[] = [
                            'taxonomy' => $taxonomy,
                            'field' => $field,
                            'terms' => [$value],
                        ];
                    }
                }
                break;
            case 'not_in':
                $tax_item = [
                    'taxonomy' => $taxonomy,
                    'field' => $field,
                    'terms' => $terms,
                    'operator' => 'NOT IN'
                ];
                break;
        }
        return $tax_item;
    }

    private function get_meta_query($meta_key, $value, $operator = null)
    {
        $meta_key = urlencode($meta_key);

        switch ($operator) {
            case null:
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => 'AND'
                ];
                break;
            case 'or':
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => 'IN'
                ];
                break;
            case 'and':
                $meta_query['relation'] = 'AND';
                if (is_array($value) && !empty($value)) {
                    foreach ($value as $value_item) {
                        $meta_query[] = [
                            'key' => $meta_key,
                            'value' => [$value_item],
                        ];
                    }
                }
                break;
            case 'not_in':
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => 'NOT IN'
                ];
                break;
            case 'like':
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => 'LIKE'
                ];
                break;
            case 'exact':
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => '='
                ];
                break;
            case 'not':
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => '!='
                ];
                break;
            case 'begin':
                $meta_query = [
                    'key' => $meta_key,
                    'value' => '^' . $value,
                    'compare' => 'RLIKE'
                ];
                break;
            case 'end':
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value . '$',
                    'compare' => 'RLIKE'
                ];
                break;
            default:
                $meta_query = [
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => $operator
                ];
                break;
        }
        return $meta_query;
    }

    private function post_name_filter($item)
    {
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'post_name',
            'value' => intval($item['value']),
            'operator' => 'exact'
        ];
    }

    private function comment_status_filter($item)
    {
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'comment_status',
            'value' => urlencode($item['value']),
            'operator' => 'exact'
        ];
    }

    private function ping_status_filter($item)
    {
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'ping_status',
            'value' => urlencode($item['value']),
            'operator' => 'exact'
        ];
    }

    private function sticky_filter($item)
    {
        $stickies = get_option('sticky_posts');
        $this->query_args['wpbe_general_column_filter'][] = [
            'field' => 'sticky',
            'value' => $stickies,
            'operator' => $item['sticky']
        ];
    }
}
