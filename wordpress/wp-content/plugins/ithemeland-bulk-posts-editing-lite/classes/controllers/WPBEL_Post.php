<?php

namespace wpbel\classes\controllers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\flush_message\Flush_Message;
use wpbel\classes\helpers\Post_Helper;
use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Setting;
use wpbel\classes\services\export\Export_Service;

class WPBEL_Post
{
    private static $instance;

    public static function register_callback()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('admin_post_wpbe_switcher', [$this, 'switcher']);
        add_action('admin_post_wpbe_settings', [$this, 'settings']);
        add_action('admin_post_wpbe_column_manager_new_preset', [$this, 'column_manager_new_preset']);
        add_action('admin_post_wpbe_column_manager_edit_preset', [$this, 'column_manager_edit_preset']);
        add_action('admin_post_wpbe_column_manager_delete_preset', [$this, 'column_manager_delete_preset']);
        add_action('admin_post_wpbe_load_column_profile', [$this, 'load_column_profile']);
        add_action('admin_post_wpbe_export_posts', [$this, 'export_posts']);
    }

    public function switcher()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wpbe_post_nonce')) {
            die('403 Forbidden');
        }

        if (isset($_POST['post_type'])) {
            Post_Helper::set_post_type(sanitize_text_field(wp_unslash($_POST['post_type'])));
        }
        $url = (!empty($_POST['item_id'])) ? add_query_arg(['id' => intval($_POST['item_id'])], WPBEL_PLUGIN_MAIN_PAGE) : '';
        $this->redirect([], $url);
    }

    public function settings()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wpbe_post_nonce')) {
            die('403 Forbidden');
        }

        $setting_repository = new Setting();
        $setting_repository->update(Sanitizer::array($_POST['settings'])); //phpcs:ignore

        $this->redirect([
            'message' => esc_html__('Success !', 'ithemeland-bulk-posts-editing-lite'),
            'type' => 'success'
        ]);
    }

    public function column_manager_new_preset()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wpbe_post_nonce')) {
            die('403 Forbidden');
        }

        if (isset($_POST['save_preset']) && !empty($_POST['field_name']) && is_array($_POST['field_name']) && !empty($_POST['preset_name'])) {
            $column_repository = new Column();
            $fields = $column_repository->get_columns();
            if (!empty($fields)) {
                $preset['name'] = sanitize_text_field(wp_unslash($_POST['preset_name']));
                $preset['date_modified'] = gmdate('Y-m-d H:i:s', time());
                $preset['key'] = 'preset-' . wp_rand(1000000, 9999999);
                if (!empty($_POST['field_name']) && is_array($_POST['field_name'])) {
                    for ($i = 0; $i < count($_POST['field_name']); $i++) {
                        if (isset($fields[$_POST['field_name'][$i]])) {
                            $field_name = sanitize_text_field(wp_unslash($_POST['field_name'][$i]));
                            $preset["fields"][$field_name] = [
                                'name' => $field_name,
                                'label' => (!empty($_POST['field_label'][$i])) ? sanitize_text_field(wp_unslash($_POST['field_label'][$i])) : $field_name,
                                'title' => (!empty($_POST['field_title'][$i])) ? sanitize_text_field(wp_unslash($_POST['field_title'][$i])) : $field_name,
                                'editable' => $fields[$field_name]['editable'],
                                'content_type' => $fields[$field_name]['content_type'],
                                'update_type' => $fields[$field_name]['update_type'],
                                'background_color' => (!empty($_POST['field_background_color'][$i])) ? sanitize_text_field(wp_unslash($_POST['field_background_color'][$i])) : '',
                                'text_color' => (!empty($_POST['field_text_color'][$i])) ? sanitize_text_field(wp_unslash($_POST['field_text_color'][$i])) : '',
                            ];
                            if (isset($fields[$field_name]['sortable'])) {
                                $preset["fields"][$field_name]['sortable'] = $fields[$field_name]['sortable'];
                            }
                            if (isset($fields[$field_name]['options'])) {
                                $preset["fields"][$field_name]['options'] = $fields[$field_name]['options'];
                            }
                            if (isset($fields[$field_name]['field_type'])) {
                                $preset["fields"][$field_name]['field_type'] = $fields[$field_name]['field_type'];
                            }
                            $preset['checked'][] = $field_name;
                        }
                    }
                    $column_repository->update($preset);
                }
            }
        }

        $this->redirect([
            'message' => esc_html__('Success !', 'ithemeland-bulk-posts-editing-lite'),
            'type' => 'success'
        ]);
    }

    public function column_manager_edit_preset()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wpbe_post_nonce')) {
            die('403 Forbidden');
        }

        if (isset($_POST['edit_preset']) && isset($_POST['preset_name']) && isset($_POST['preset_key'])) {
            $column_repository = new Column();
            $fields = $column_repository->get_columns();
            if (!empty($fields)) {
                $preset["fields"] = [];
                $preset['name'] = sanitize_text_field(wp_unslash($_POST['preset_name']));
                $preset['date_modified'] = gmdate('Y-m-d H:i:s', time());
                $preset['key'] = sanitize_text_field(wp_unslash($_POST['preset_key']));
                if (!empty($_POST['field_name']) && is_array($_POST['field_name'])) {
                    for ($i = 0; $i < count($_POST['field_name']); $i++) {
                        if (isset($fields[$_POST['field_name'][$i]])) {
                            $field_name = sanitize_text_field(wp_unslash($_POST['field_name'][$i]));
                            $preset["fields"][$field_name] = [
                                'name' => $field_name,
                                'label' => (!empty($_POST['field_label'][$i])) ? sanitize_text_field(wp_unslash($_POST['field_label'][$i])) : $field_name,
                                'title' => (!empty($_POST['field_title'][$i])) ? sanitize_text_field(wp_unslash($_POST['field_title'][$i])) : $field_name,
                                'editable' => $fields[$field_name]['editable'],
                                'content_type' => $fields[$field_name]['content_type'],
                                'update_type' => $fields[$field_name]['update_type'],
                                'background_color' => (!empty($_POST['field_background_color'][$i])) ? sanitize_text_field(wp_unslash($_POST['field_background_color'][$i])) : '',
                                'text_color' => (!empty($_POST['field_text_color'][$i])) ? sanitize_text_field(wp_unslash($_POST['field_text_color'][$i])) : '',
                            ];
                            if (isset($fields[$field_name]['sortable'])) {
                                $preset["fields"][$field_name]['sortable'] = $fields[$field_name]['sortable'];
                            }
                            if (isset($fields[$field_name]['options'])) {
                                $preset["fields"][$field_name]['options'] = $fields[$field_name]['options'];
                            }
                            if (isset($fields[$field_name]['field_type'])) {
                                $preset["fields"][$field_name]['field_type'] = $fields[$field_name]['field_type'];
                            }
                            $preset['checked'][] = $field_name;
                        }
                    }
                    $column_repository->update($preset);
                    $column_repository->set_active_columns($preset['key'], $preset['fields']);
                }
            }
        }

        $this->redirect([
            'message' => esc_html__('Success !', 'ithemeland-bulk-posts-editing-lite'),
            'type' => 'success'
        ]);
    }

    public function column_manager_delete_preset()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wpbe_post_nonce')) {
            die('403 Forbidden');
        }

        $column_repository = new Column();
        if (isset($_POST['delete_key'])) {
            if ($column_repository->get_active_columns()['name'] == $_POST['delete_key']) {
                $column_repository->delete_active_columns();
            }
            $column_repository->delete(sanitize_text_field(wp_unslash($_POST['delete_key'])));
            $column_repository->set_default_active_columns();
        }

        $this->redirect([
            'message' => esc_html__('Success !', 'ithemeland-bulk-posts-editing-lite'),
            'type' => 'success'
        ]);
    }

    public function load_column_profile()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wpbe_post_nonce')) {
            die('403 Forbidden');
        }

        if (isset($_POST['preset_key']) && !empty($_POST["columns"])) {
            $preset_key = sanitize_text_field(wp_unslash($_POST['preset_key']));
            $checked_columns = Sanitizer::array($_POST["columns"]); //phpcs:ignore
            if (!empty($checked_columns) && is_array($checked_columns)) {
                $checked_columns = array_combine($checked_columns, $checked_columns);
                $column_repository = new Column();
                $columns = [];
                $fields = $column_repository->get_columns();
                $preset_columns = $column_repository->get_preset($preset_key);
                if (!empty($checked_columns) && is_array($checked_columns)) {
                    if (!empty($preset_columns['fields'])) {
                        foreach ($preset_columns['fields'] as $column_key => $preset_column) {
                            if (isset($checked_columns[$column_key])) {
                                $columns[$column_key] = $preset_column;
                                unset($checked_columns[$column_key]);
                            }
                        }
                    }
                    if (!empty($checked_columns)) {
                        foreach ($checked_columns as $column_item) {
                            if (isset($fields[$column_item])) {
                                $checked_column = [
                                    'name' => $column_item,
                                    'label' => $fields[$column_item]['label'],
                                    'title' => $fields[$column_item]['label'],
                                    'editable' => $fields[$column_item]['editable'],
                                    'content_type' => $fields[$column_item]['content_type'],
                                    'background_color' => '#fff',
                                    'text_color' => '#444',
                                ];
                                if (isset($fields[$column_item]['sortable'])) {
                                    $checked_column['sortable'] = ($fields[$column_item]['sortable']);
                                }
                                if (isset($fields[$column_item]['update_type'])) {
                                    $checked_column['update_type'] = ($fields[$column_item]['update_type']);
                                }
                                if (isset($fields[$column_item]['options'])) {
                                    $checked_column['options'] = $fields[$column_item]['options'];
                                }
                                if (isset($fields[$column_item]['field_type'])) {
                                    $checked_column['field_type'] = $fields[$column_item]['field_type'];
                                }
                                $columns[$column_item] = $checked_column;
                            }
                        }
                    }
                }
                $column_repository->set_active_columns($preset_key, $columns);
            }
        }
        $this->redirect();
    }

    public function export_posts()
    {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'wpbe_post_nonce')) {
            die('403 Forbidden');
        }

        if (empty($_POST['posts'])) {
            return false;
        }

        $export_service = new Export_Service();
        return $export_service->export([
            'export_type' => (!empty($_POST['export_type'])) ? sanitize_text_field(wp_unslash($_POST['export_type'])) : 'csv',
            'posts' => sanitize_text_field(wp_unslash($_POST['posts'])),
            'item_ids' => (!empty($_POST['item_ids'])) ? array_map('intval', $_POST['item_ids']) : [],
            'fields' => (!empty($_POST['fields'])) ? sanitize_text_field(wp_unslash($_POST['fields'])) : 'all',
        ]);
    }

    private function redirect($notice = [], $url = null)
    {
        if (!empty($notice) && isset($notice['message'])) {
            $flush_message_repository = new Flush_Message();
            $flush_message_repository->set($notice);
        }

        $url = (!empty($url)) ? $url : WPBEL_PLUGIN_MAIN_PAGE;
        wp_safe_redirect($url);
        die();
    }
}
