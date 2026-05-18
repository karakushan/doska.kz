<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Formula;
use wpbel\classes\helpers\Others;
use wpbel\classes\helpers\Pagination;
use wpbel\classes\helpers\Render;
use wpbel\classes\helpers\Meta_Fields;
use wpbel\classes\providers\post\PostProvider;
use wpbel\classes\helpers\Post_Helper;
use wpbel\classes\helpers\Setting_Helper;
use wpbel\classes\providers\column\PostColumnProvider;
use wpbel\classes\services\filter\Post_Filter_Service;

class Post
{
    public function get_post_types()
    {
        $types = [
            'post' => esc_html__('Post', 'ithemeland-bulk-posts-editing-lite'),
            'page' => esc_html__('Page', 'ithemeland-bulk-posts-editing-lite'),
        ];
        $custom_types = $this->get_custom_post_types();
        if (!empty($custom_types)) {
            foreach ($custom_types as $type => $label) {
                $types[$type] = $label;
            }
        }
        return $types;
    }

    public function get_posts_rows($post_ids)
    {
        if (!is_array($post_ids)) {
            return false;
        }

        $column_repository = new Column();
        $settings_repository = new Setting();
        $settings = $settings_repository->get_settings();
        $sticky_first_columns = $settings['sticky_first_columns'];
        $column_provider = PostColumnProvider::get_instance();
        $show_id_column = $column_repository::SHOW_ID_COLUMN;
        $next_static_columns = $column_repository::get_static_columns();
        $columns = $column_repository->get_active_columns()['fields'];

        $post_rows = [];
        $includes = [];
        $post_statuses = [];

        if (!empty($post_ids)) {
            foreach ($post_ids as $post_id) {
                $post = $this->get_post(intval($post_id));
                $item_columns = $column_provider->get_item_columns($post, $columns);
                $post_statuses[intval($post_id)] = $post->post_status;
                if (is_array($item_columns) && isset($item_columns['items'])) {
                    $post_rows[intval($post_id)] = $item_columns['items'];
                    $includes[] = $item_columns['includes'];
                } else {
                    $post_rows[intval($post_id)] = $item_columns;
                }
            }
        }

        $result = new \stdClass();
        $result->post_rows = $post_rows;
        $result->post_statuses = $post_statuses;
        $result->includes = $includes;
        $result->status_filters = $this->get_status_filters();
        return $result;
    }

    private function get_status_filters()
    {
        $post_counts_by_status = $this->get_post_counts_group_by_status();
        $post_statuses = $this->get_post_statuses();
        return Render::html(WPBEL_VIEWS_DIR . "bulk_edit/status_filters.php", compact('post_counts_by_status', 'post_statuses'));
    }

    public function get_custom_post_types()
    {
        $args = [
            'public' => true,
            '_builtin' => false
        ];
        $output = 'names';
        return array_diff(get_post_types($args, $output), $this->except_post_types());
    }

    public function get_post_type_name($post_type)
    {
        switch ($post_type) {
            case 'post':
            case 'page':
                $post_type_name = $post_type;
                break;
            case 'custom_posts':
                $custom_post_types = $this->get_custom_post_types();
                $post_type_name = isset($custom_post_types[$post_type]) ? $custom_post_types[$post_type] : '';
                break;
        }

        return $post_type_name;
    }

    public function get_sticky_posts()
    {
        $sticky_posts = get_option('sticky_posts', []);
        return (!is_array($sticky_posts)) ? unserialize($sticky_posts) : $sticky_posts;
    }

    public function get_post($post_id)
    {
        return get_post(intval($post_id));
    }

    public function get_post_ids_by_custom_query($join, $where, $types_in = ['post'])
    {
        global $wpdb;

        $types = array_map('sanitize_key', $types_in);

        if (empty($types)) {
            return '';
        }

        $placeholders = implode(',', array_fill(0, count($types), '%s'));
        $where_sql = (!empty($where)) ? " AND ({$where})" : '';

        $sql = "
            SELECT posts.ID 
            FROM {$wpdb->posts} AS posts 
            {$join} 
            WHERE posts.post_type IN ($placeholders)
            {$where_sql}
        ";

        $query = $wpdb->prepare($sql, ...$types); //phpcs:ignore
        $posts = $wpdb->get_results($query, ARRAY_N); //phpcs:ignore

        $posts = array_unique(Others::array_flatten($posts, 'int'));

        if (($key = array_search(0, $posts)) !== false) {
            unset($posts[$key]);
        }

        return implode(',', $posts);
    }


    public function get_posts($args)
    {
        $posts = new \WP_Query($args);
        return $posts;
    }

    public function get_posts_list($post_types, $filter_data = [], $active_page = 1)
    {
        $column_repository = new Column();
        $search_repository = new Search();
        $search_repository->update_current_data([
            'last_filter_data' => $filter_data
        ]);

        $settings_repository = new Setting();
        $settings = $settings_repository->get_settings();
        $settings_sort_by = (isset($settings['default_sort_by'])) ? $settings['default_sort_by'] : '';
        $settings_sort_type = (isset($settings['default_sort'])) ? $settings['default_sort'] : '';
        $current_settings = $settings_repository->get_current_settings();
        $column_name = isset($current_settings['sort_by']) ? $current_settings['sort_by'] : $settings_sort_by;
        $sort_type = isset($current_settings['sort_type']) ? $current_settings['sort_type'] : $settings_sort_type;
        $sticky_first_columns = $current_settings['sticky_first_columns'];
        $args = Setting_Helper::get_arg_order_by(sanitize_text_field($column_name), [
            'order' => sanitize_text_field($sort_type),
            'posts_per_page' => $current_settings['count_per_page'],
            'paged' => $active_page,
            'paginate' => true,
            'post_type' => $post_types,
            'fields' => 'ids',
        ]);

        $filter_service = Post_Filter_Service::get_instance();
        $posts = $filter_service->get_filtered_posts($filter_data, $args);
        $items = $posts['post_ids'];
        $item_provider = PostProvider::get_instance();
        $show_id_column = $column_repository::SHOW_ID_COLUMN;
        $next_static_columns = $column_repository::get_static_columns();
        $columns_title = $column_repository::get_columns_title();
        $columns = $column_repository->get_active_columns()['fields'];
        $sort_type = $current_settings['sort_type'];
        $sort_by = $current_settings['sort_by'];
        $display_full_columns_title = $settings['display_full_columns_title'];
        $posts_list = Render::html(WPBEL_VIEWS_DIR . 'data_table/items.php', compact('item_provider', 'display_full_columns_title', 'items', 'columns', 'sort_type', 'sort_by', 'show_id_column', 'next_static_columns', 'columns_title', 'sticky_first_columns'));
        if (!empty($posts) && !empty($active_page)) {
            $pagination = Pagination::init($active_page, $posts['max_num_pages']);
        }

        $post_counts_by_status = $this->get_post_counts_group_by_status();
        $post_statuses = $this->get_post_statuses();
        $status_filters = Render::html(WPBEL_VIEWS_DIR . "bulk_edit/status_filters.php", compact('post_counts_by_status', 'post_statuses'));

        $result = new \stdClass();
        $result->posts_list = $posts_list;
        $result->pagination = $pagination;
        $result->status_filters = $status_filters;
        $result->count = $posts['found_posts'];
        return $result;
    }

    public function update($post_ids, $data)
    {
        if (empty($post_ids)) {
            return false;
        }

        if (!empty($data)) {
            foreach ($post_ids as $post_id) {
                $result = $this->field_update($post_id, $data);
                if (!$result) {
                    return false;
                }
            }
        }

        return true;
    }

    public function get_taxonomies()
    {
        $taxonomies_value = [];
        $active_post_type = Post_Helper::get_active_post_type();
        $taxonomies = get_object_taxonomies($active_post_type, 'objects');
        $except = Meta_Fields::get_except_taxonomies();
        foreach ($taxonomies as $taxonomy) {
            if (taxonomy_exists($taxonomy->name) && !in_array($taxonomy->name, $except)) {
                $taxonomies_value[$taxonomy->name] = [
                    'label' => $taxonomy->label,
                    'terms' => ($taxonomy->name == 'post_tag') ? [] : get_terms([
                        'taxonomy' => $taxonomy->name,
                        'hide_empty' => false,
                    ]),
                ];
            }
        }
        return $taxonomies_value;
    }

    public function get_post_fields($post_object)
    {
        if (!($post_object instanceof \WP_Post)) {
            return [];
        }

        $post_meta = get_post_meta($post_object->ID);
        $sticky_posts = $this->get_sticky_posts();

        return [
            'id' => $post_object->ID,
            'post_parent' => $post_object->post_parent,
            'post_type' => $post_object->post_type,
            'post_title' => $post_object->post_title,
            'post_name' => $post_object->post_name,
            'post_content' => $post_object->post_content,
            'post_excerpt' => $post_object->post_excerpt,
            'post_password' => $post_object->post_password,
            'post_date' => (!empty($post_object->post_date)) ? $post_object->post_date : '',
            'post_date_gmt' => (!empty($post_object->post_date_gmt)) ? $post_object->post_date_gmt : '',
            'post_modified' => (!empty($post_object->post_modified)) ? $post_object->post_modified : '',
            'post_modified_gmt' => (!empty($post_object->post_modified_gmt)) ? $post_object->post_modified_gmt : '',
            'post_status' => $post_object->post_status,
            '_thumbnail_id' => [
                'id' => get_post_thumbnail_id($post_object->ID),
                'small' => get_the_post_thumbnail($post_object->ID, [40, 40]),
                'big' => get_the_post_thumbnail($post_object->ID, [600, 600]),
            ],
            'sticky' => (is_array($sticky_posts) && in_array($post_object->ID, $sticky_posts)) ? 'yes' : 'no',
            'ping_status' => $post_object->ping_status,
            'menu_order' => $post_object->menu_order,
            'post_author' => $post_object->post_author,
            'comment_status' => $post_object->comment_status,
            'category' => $post_object->post_category,
            'custom_field' => $post_meta,
        ];
    }

    public function create($post_data)
    {
        $post_type = isset($post_data['post_type']) ? sanitize_text_field($post_data['post_type']) : '';
        $title = !empty($post_data['title']) ? sanitize_text_field($post_data['title']) : 'New ' . ucfirst($post_type);
        $slug = isset($post_data['slug']) ? sanitize_text_field($post_data['slug']) : '';
        $password = isset($post_data['password']) ? sanitize_text_field($post_data['password']) : '';
        $description = isset($post_data['description']) ? wp_kses_post($post_data['description']) : '';
        $short_description = isset($post_data['short_description']) ? wp_kses_post($post_data['short_description']) : '';
        $menu_order = isset($post_data['menu_order']) ? intval($post_data['menu_order']) : 0;
        $parent = isset($post_data['parent']) ? intval($post_data['parent']) : 0;
        $comment_status = isset($post_data['comment_status']) ? sanitize_text_field($post_data['comment_status']) : 'open';
        $allow_ping_back = isset($post_data['allow_ping_back']) ? sanitize_text_field($post_data['allow_ping_back']) : 'open';
        $author = isset($post_data['author']) ? intval($post_data['author']) : get_current_user_id();
        $image = isset($post_data['image']) ? sanitize_text_field($post_data['image']) : '';
        //Data and Type
        $post_status = isset($post_data['post_status']) ? sanitize_text_field($post_data['post_status']) : '';
        $date_published = isset($post_data['date_published']) ? sanitize_text_field($post_data['date_published']) : '';

        // Insert post
        $post = wp_insert_post([
            'post_type'      => sanitize_text_field($post_type),
            'post_title'     => $title,
            'post_name'      => $slug,
            'post_content'   => $description,
            'post_excerpt'   => $short_description,
            'menu_order'     => $menu_order,
            'post_parent'    => $parent,
            'comment_status' => $comment_status,
            'ping_status'    => $allow_ping_back,
            'post_password'  => $password,
            'post_author'    => $author,
            'post_status'    => $post_status,
            'post_date'      => $date_published
        ]);

        if ($image > 0) {
            set_post_thumbnail($post, $image);
        }
        $this->set_taxonomies_new_posts($post_data, $post);
        return $post;
    }

    private function set_taxonomies_new_posts($post_data, $post)
    {
        // Set taxonomies 
        if (isset($post_data['taxonomies']) && is_array($post_data['taxonomies'])) {
            foreach ($post_data['taxonomies'] as $taxonomy => $terms) {
                if (!empty($terms)) {
                    // Ensure terms is an array
                    $sanitized_terms = is_array($terms) ? array_map('sanitize_text_field', $terms) : [sanitize_text_field($terms)];

                    if ($taxonomy === 'category' || $taxonomy === 'post_tag') {
                        $term_ids = [];
                        foreach ($sanitized_terms as $term_id) {
                            $term = get_term_by('id', $term_id, $taxonomy);
                            if ($term && !is_wp_error($term)) {
                                $term_ids[] = $term->term_id;  // Collect the term IDs
                            }
                        }
                        // Set the terms for the post 
                        wp_set_post_terms($post, $term_ids, $taxonomy);
                    } else {
                        // For other taxonomies, work with term names and fetch IDs
                        $term_ids = [];
                        foreach ($sanitized_terms as $term_name) {
                            $term = get_term_by('name', $term_name, $taxonomy);
                            if ($term && !is_wp_error($term)) {
                                $term_ids[] = $term->term_id;  // Collect the term IDs
                            }
                        }
                        // Set the terms for the custom taxonomy
                        wp_set_post_terms($post, $term_ids, $taxonomy);
                    }
                }
            }
        }
    }
    public function delete($post_id, $deleteType)
    {
        if (empty($post_id) || empty($deleteType)) {
            return false;
        }
        switch ($deleteType) {
            case 'trash':
            case 'all':
                return wp_trash_post(intval($post_id));
                break;
            case 'permanently':
                return wp_delete_post(intval($post_id), true);
                break;
        }
    }

    public function get_duplicates($wpdb, $post_type, $delete_type, $post_filter)
    {
        $criteria_map = [
            'dupoldest_title'          => ['column' => 'post_title', 'order' => 'DESC'],
            'duplatest_title'          => ['column' => 'post_title', 'order' => 'ASC'],
            'dupoldest_content'        => ['column' => 'post_content', 'order' => 'DESC'],
            'duplatest_content'        => ['column' => 'post_content', 'order' => 'ASC'],
            'dupoldest_title_content'  => ['column' => ['post_title', 'post_content'], 'order' => 'DESC'],
            'duplatest_title_content'  => ['column' => ['post_title', 'post_content'], 'order' => 'ASC'],
        ];

        if (!isset($criteria_map[$delete_type])) {
            return []; // Invalid type, return empty array
        }

        $criteria = $criteria_map[$delete_type];
        $column = $criteria['column'];
        $order = $criteria['order'];

        // Handle single-column and multi-column cases
        if (is_array($column)) {
            $group_by = implode(", ", $column);
            $select_columns = implode(", ", $column);
            $where_clause = implode(" AND ", array_map(fn($col) => "$col = %s", $column));
            $prepare_params = $column;
        } else {
            $group_by = $column;
            $select_columns = $column;
            $where_clause = "$column = %s";
            $prepare_params = [$column];
        }

        // Find duplicate records
        $query = "
            SELECT $select_columns FROM {$wpdb->posts} 
            WHERE post_type = %s AND post_status IN ('publish', 'draft', 'pending') $post_filter
            GROUP BY $group_by HAVING COUNT(*) > 1";
        $duplicates = $wpdb->get_results($wpdb->prepare($query, $post_type), ARRAY_A); //phpcs:ignore

        $post_ids = [];

        foreach ($duplicates as $dup) {
            $query = "
                SELECT ID FROM {$wpdb->posts} 
                WHERE $where_clause AND post_type = %s $post_filter 
                ORDER BY post_date $order";

            $params = array_map(fn($col) => $dup[$col], $prepare_params);
            $params[] = $post_type;

            $posts = $wpdb->get_results($wpdb->prepare($query, ...$params), ARRAY_A); //phpcs:ignore
            array_shift($posts); // Keep the first one
            $post_ids = array_merge($post_ids, array_column($posts, 'ID'));
        }

        return $post_ids;
    }
    public function duplicate($post)
    {
        if (!($post instanceof \WP_Post)) {
            return false;
        }

        global $wpdb;
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $post->post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name . " copy",
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title . " copy",
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );
        $new_post_id = wp_insert_post($args);

        $taxonomies = get_object_taxonomies($post->post_type);
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        $post_meta_info = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d", $post->ID)); //phpcs:ignore
        if (count($post_meta_info) != 0) {
            $sql_query = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) ";
            foreach ($post_meta_info as $meta_info) {
                $meta_key = $meta_info->meta_key;
                if ($meta_key == '_wp_old_slug') continue;
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[] = "SELECT {$new_post_id}, '$meta_key', '$meta_value'";
            }
            $sql_query .= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query); //phpcs:ignore
        }
    }

    public function get_post_statuses()
    {
        $statuses = get_post_statuses();
        $statuses['trash'] = esc_html__('Trash', 'ithemeland-bulk-posts-editing-lite');
        return $statuses;
    }

    public function get_post_counts_group_by_status()
    {
        global $wpdb;
        $output = [];
        $all = 0;
        $active_post_type = Post_Helper::get_active_post_type();
        if (is_null($active_post_type)) {
            return false;
        }

        $sql = $wpdb->prepare("
            SELECT post_status AS status, COUNT(*) AS count
            FROM {$wpdb->posts}
            WHERE post_type = %s AND post_status NOT IN ('auto-draft')
            GROUP BY post_status
        ", $active_post_type);

        $result = $wpdb->get_results($sql, ARRAY_A); //phpcs:ignore

        foreach ((array) $result as $item) {
            $status = $item['status'];
            $count = intval($item['count']);
            $output[$status] = $count;
            if ($status !== 'trash') {
                $all += $count;
            }
        }

        $output['all'] = $all;
        return $output;
    }

    public function get_status_color($status)
    {
        $status_colors = $this->get_status_colors();
        return (isset($status_colors[$status])) ? $status_colors[$status] : null;
    }

    private function except_post_types()
    {
        return [
            'product',
            'product_variation',
            'shop_order',
            'shop_coupon'
        ];
    }

    private function parse_value($post, $value)
    {
        if (!($post instanceof \WP_Post)) {
            return false;
        }

        $parent = $this->get_post(intval($post->post_parent));
        $value = str_replace('{title}', $post->post_title, $value);
        $value = str_replace('{id}', $post->ID, $value);
        $value = str_replace('{menu_order}', $post->menu_order, $value);
        $value = str_replace('{parent_id}', $post->post_parent, $value);
        if ($parent instanceof \WP_Post) {
            $value = str_replace('{parent_title}', $parent->post_title, $value);
        } else {
            $value = str_replace('{parent_title}', '', $value);
        }
        return $value;
    }

    private function field_update($post_id, $data)
    {
        $field_type = sanitize_text_field($data['field_type']);
        $field = sanitize_text_field($data['field']);
        $value = $data['value'];
        $operator = $data['operator'];
        $post = $this->get_post(intval($post_id));

        if (!$post instanceof \WP_Post) {
            return false;
        }

        $updated_data['ID'] = $post->ID;

        if (!is_numeric($value) && !is_array($value)) {
            $data['value'] = $this->parse_value($post, $data['value']);
        }

        if (!empty($data['replace'])) {
            $data['replace'] = $this->parse_value($post, $data['replace']);
        }

        switch ($field_type) {
            case 'taxonomy':
                $this->taxonomy_update($post->ID, $data);
                break;
            case 'custom_field':
                $this->custom_field_update($post->ID, $data);
                break;
            case 'main_field':
                switch ($field) {
                    case 'post_title':
                        if ($operator == 'text_remove_duplicate') {
                            $posts = $this->get_posts([
                                'posts_per_page' => '-1',
                                'post_type' => ['post'],
                                'wpbe_general_column_filter' => [
                                    [
                                        'field' => 'ID',
                                        'value' => intval($post->ID),
                                        'operator' => 'not_in',
                                    ],
                                    [
                                        'field' => 'post_title',
                                        'value' => sanitize_text_field($post->post_title),
                                        'operator' => 'exact',
                                    ],
                                ],
                            ]);
                            if (!empty($posts->posts)) {
                                foreach ($posts->posts as $post) {
                                    wp_delete_post(intval($post->ID));
                                }
                            }
                        } else {
                            $value = $this->set_value_with_operator($post->post_title, $data);
                            $updated_data['post_title'] = sanitize_text_field($value);
                        }
                        break;
                    case 'post_name':
                        $value = $this->set_value_with_operator($post->post_name, $data);
                        $updated_data['post_name'] = $value;
                        break;
                    case 'post_content':
                        $value = $this->set_value_with_operator($post->post_content, $data);
                        $updated_data['post_content'] = $value;
                        break;
                    case 'post_excerpt':
                        $value = $this->set_value_with_operator($post->post_excerpt, $data);
                        $updated_data['post_excerpt'] = $value;
                        break;
                    case 'post_status':
                        $updated_data['post_status'] = $value;
                        break;
                    case 'post_date':
                        $updated_data['post_date'] = $value;
                        break;
                    case 'post_date_gmt':
                        $updated_data['post_date_gmt'] = $value;
                        break;
                    case '_thumbnail_id':
                        (intval($value) != 0) ? set_post_thumbnail($post->ID, intval($value)) : delete_post_thumbnail($post->ID);
                        break;
                    case 'menu_order':
                        $updated_data['menu_order'] = $value;
                        break;
                    case 'post_type':
                        $updated_data['post_type'] = $value;
                        break;
                    case 'post_author':
                        $updated_data['post_author'] = intval($value);
                        break;
                    case 'post_password':
                        $updated_data['post_password'] = $this->set_value_with_operator($post->post_password, $data);
                        break;
                    case 'post_parent':
                        $updated_data['post_parent'] = intval($value);
                        break;
                    case 'ping_status':
                        $updated_data['ping_status'] = $value;
                        break;
                    case 'sticky':
                        $this->update_sticky_post($post->ID, $value);
                        break;
                    case 'comment_status':
                        $updated_data['comment_status'] = $value;
                        break;
                }
                break;
        }

        wp_update_post($updated_data);

        if ($field_type == 'main_field' && in_array($field, ['post_modified', 'post_modified_gmt'])) {
            if ($field == 'post_modified') {
                global $wpdb;

                $wpdb->update( //phpcs:ignore
                    $wpdb->prefix . 'posts',
                    array(
                        'post_modified' => gmdate('Y-m-d H:i', strtotime($value)),
                    ),
                    array('ID' => $post->ID)
                );
            }
            if ($field == 'post_modified_gmt') {
                global $wpdb;

                $wpdb->update( //phpcs:ignore
                    $wpdb->prefix . 'posts',
                    array(
                        'post_modified_gmt' => gmdate('Y-m-d H:i', strtotime($value))
                    ),
                    array('ID' => $post->ID)
                );
            }
        }

        return true;
    }

    public function get_post_ids_with_like_names($post_ids = [])
    {
        global $wpdb;

        $post_id_query = "";
        if (!empty($post_ids)) {
            $placeholders = implode(',', array_fill(0, count($post_ids), '%d'));
            $post_ids = array_map('intval', $post_ids);
            $post_id_query = "AND ID IN ($placeholders)";
        }

        $active_post_type = sanitize_key(Post_Helper::get_active_post_type());

        $sql = "
            SELECT GROUP_CONCAT(ID) as post_ids, COUNT(*) as post_count
            FROM {$wpdb->posts}
            WHERE post_type = %s AND post_status != 'trash' $post_id_query
            GROUP BY post_title
            HAVING post_count > 1
            ORDER BY post_count
        ";

        $args = array_merge([$active_post_type], $post_ids);
        $prepared = $wpdb->prepare($sql, ...$args); //phpcs:ignore

        return $wpdb->get_results($prepared, ARRAY_A); //phpcs:ignore
    }


    private function set_value_with_operator($old_value, $data)
    {
        if (!empty($data['operator'])) {
            $new_val = (isset($data['round_item']) && !empty($data['round_item'])) ? Post_Helper::round($data['value'], $data['round_item']) : $data['value'];
            switch ($data['operator']) {
                case 'text_append':
                    $value = $old_value . $data['value'];
                    break;
                case 'text_prepend':
                    $value = $data['value'] . $old_value;
                    break;
                case 'text_new':
                    $value = $data['value'];
                    break;
                case 'text_delete':
                    $value = str_replace($data['value'], '', $old_value);
                    break;
                case 'text_clear':
                    $value = '';
                    break;
                case 'text_replace':
                    if (!empty($data['value'])) {
                        $value = ($data['sensitive'] == 'yes') ? str_replace($data['value'], $data['replace'], $old_value) : str_ireplace($data['value'], $data['replace'], $old_value);
                    } else {
                        $value = $old_value;
                    }
                    break;
                case 'text_remove_duplicate':
                    $value = $old_value;
                    break;
                case 'taxonomy_append':
                    $value = array_unique(array_merge($old_value, $data['value']));
                    break;
                case 'taxonomy_replace':
                    $value = $data['value'];
                    break;
                case 'taxonomy_delete':
                    $value = array_values(array_diff($old_value, $data['value']));
                    break;
                case 'number_new':
                    $value = $new_val;
                    break;
                case 'number_delete':
                    $value = str_replace($data['value'], '', $old_value);
                    break;
                case 'number_clear':
                    $value = '';
                    break;
                case 'number_formula':
                    $formulaCalculator = new Formula();
                    $data['value'] = $formulaCalculator->calculate($data['value'], ['X' => $old_value]);
                    break;
                case 'increase_by_value':
                    $value = floatval($old_value) + floatval($new_val);
                    break;
                case 'decrease_by_value':
                    $value = floatval($old_value) - floatval($new_val);
                    break;
                case 'increase_by_percent':
                    $value = floatval($old_value) + floatval(floatval($old_value) * floatval($new_val) / 100);
                    break;
                case 'decrease_by_percent':
                    $value = floatval($old_value) - floatval(floatval($old_value) * floatval($new_val) / 100);
                    break;
            }
        } else {
            $value = $data['value'];
        }
        return $value;
    }

    private function taxonomy_update($post_id, $data)
    {
        $old_value = wp_get_post_terms(intval($post_id), $data['field'], array('fields' => 'ids'));
        $value = $this->set_value_with_operator($old_value, $data, $data['operator']);
        return wp_set_post_terms(intval($post_id), $value, $data['field']);
    }

    private function custom_field_update($post_id, $data)
    {
        $old_value = get_post_meta(intval($post_id), $data['field']);
        $old_value = isset($old_value[0]) ? $old_value[0] : '';
        $value = $this->set_value_with_operator($old_value, $data, $data['operator']);
        return update_post_meta(intval($post_id), sanitize_text_field($data['field']), $value);
    }

    private function update_sticky_post($post_id, $value)
    {
        $sticky_posts = $this->get_sticky_posts();
        if ($value == 'yes') {
            if (!in_array($post_id, $sticky_posts)) {
                $sticky_posts[] = intval($post_id);
            }
        } else {
            $key = array_search($post_id, $sticky_posts);
            if (isset($sticky_posts[$key])) {
                unset($sticky_posts[array_search($post_id, $sticky_posts)]);
            }
        }

        return update_option('sticky_posts', $sticky_posts);
    }

    public function get_trash()
    {
        $active_post_type = Post_Helper::get_active_post_type();
        $args = [
            'posts_per_page' => -1,
            'post_type' => [$active_post_type],
            'post_status' => 'trash',
            'fields' => 'ids',
        ];

        $posts = $this->get_posts($args);
        return $posts->posts;
    }

    private function get_status_colors()
    {
        return [
            'draft' => '#a3b7a3',
            'pending' => '#80e045',
            'private' => '#f9c662',
            'publish' => '#6ca9d6',
            'trash' => '#808080',
        ];
    }
}
