<?php

namespace wpbel\classes\bootstrap;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\framework\analytics\AnalyticsTracker;
use wpbel\framework\onboarding\Onboarding;
use wpbel\classes\helpers\Render;
use wpbel\classes\services\post_delete\PostDeleteService;
use wpbel\classes\services\post_duplicate\PostDuplicateService;
use wpbel\classes\controllers\WPBEL_Ajax;
use wpbel\classes\controllers\WPBEL_Post;
use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Meta_Field;
use wpbel\classes\repositories\Search;
use wpbel\classes\repositories\Setting;
use wpbel\classes\services\background_process\PostBackgroundProcess;
use wpbel\classes\services\scheduler\Post_Scheduler;
use wpbel\classes\services\history\HistoryRedoService;
use wpbel\classes\services\history\HistoryUndoService;

class WPBEL
{
    private static $instance = null;
    private static $is_initable = null;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        if (!current_user_can('edit_posts')) {
            return;
        }

        AnalyticsTracker::register();
        Onboarding::register();
        WPBEL_Post::register_callback();
        WPBEL_Ajax::register_callback();

        add_filter('safe_style_css', function ($styles) {
            $styles[] = 'display';
            return $styles;
        });

        if (is_admin()) {
            add_action('admin_menu', [$this, 'add_menu']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
            WPBEL_Meta_Fields::init();
        }
    }

    public function add_menu()
    {
        if (!defined('WPBE_NAME') && !defined('WBEB_NAME')) {
            if (defined('WBEBL_NAME')) {
                add_submenu_page('wbebl', esc_html__('WPBULKiT', 'ithemeland-bulk-posts-editing-lite'), esc_html__('WPBULKiT', 'ithemeland-bulk-posts-editing-lite'), 'edit_posts', 'wpbe', ['wpbel\classes\controllers\Wordpress_Posts_Bulk_Edit', 'init'], 1);
            } else {
                add_menu_page(esc_html__('WPBULKiT', 'ithemeland-bulk-posts-editing-lite'), wp_kses('WPBULK<span style="color: #627ddd;font-weight: 900;">iT</span>', Sanitizer::allowed_html()), 'edit_posts', 'wpbe', ['wpbel\classes\controllers\Wordpress_Posts_Bulk_Edit', 'init'], WPBEL_IMAGES_URL . 'wpbulkit-icon-wh20.svg', 59);
            }
        }

        // Add "Go Pro" submenu
        // add_submenu_page(
        //     'wpbe',
        //     esc_html__('Go Pro', 'ithemeland-bulk-posts-editing-lite'),
        //     '<img class="wpbe-icon-go-pro" src="' . WPBEL_URL . 'views/go_pro/assets/images/go-pro.png" style="width:20px; height:20px; margin-right:5px; vertical-align:middle;"> ' . esc_html__('Go Pro', 'ithemeland-bulk-posts-editing-lite'),
        //     'manage_options',
        //     'wpbel_go_pro',
        //     [$this, 'wpbe_go_pro_page']
        // );

        // Add "Other Plugins" submenu
        // add_submenu_page(
        //     'wpbe',
        //     esc_html__('Other Plugins', 'ithemeland-bulk-posts-editing-lite'),
        //     esc_html__('Other Plugins', 'ithemeland-bulk-posts-editing-lite'),
        //     'manage_options',
        //     'wpbel_other_plugins',
        //     [$this, 'wpbe_other_plugins_page']
        // );
    }

    public static function wpbe_wp_init()
    {
        if (!self::is_initable()) {
            return false;
        }

        $version = get_option('wpbel-version');
        if (empty($version) || $version != WPBEL_VERSION) {
            self::create_tables();
            self::update_table();

            $column_repository = new Column();
            $column_repository->sync_active_columns();
            update_option('wpbel-version', WPBEL_VERSION);
        }

        WPBEL_Custom_Queries::init();
        PostBackgroundProcess::init();

        if (!defined('DISABLE_WP_CRON') || (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON === false)) {
            Post_Scheduler::init();
        }
    }

    // "Go Pro" page callback
    // public function wpbe_go_pro_page()
    // {
    //     include_once WPBEL_VIEWS_DIR . 'go_pro/go_pro.php';
    //     if (!empty($_GET['page']) && $_GET['page'] == 'wpbel_go_pro') {
    //         wp_enqueue_style('wpbe-go-pro', WPBEL_URL . 'views/go_pro/assets/css/style.css', [], WPBEL_VERSION);
    //         wp_enqueue_style('wpbe-main', WPBEL_CSS_URL . 'style.core.css', [], WPBEL_VERSION);
    //     }
    // }

    // "Other Plugins" page callback
    // public function wpbe_other_plugins_page()
    // {
    //     include_once WPBEL_VIEWS_DIR . 'go_pro/other_plugins/other_plugins.php';
    //     if (!empty($_GET['page']) && $_GET['page'] == 'wpbel_other_plugins') {
    //         wp_enqueue_style('wpbe-go-pro', WPBEL_URL . 'views/go_pro/assets/css/style.css', [], WPBEL_VERSION);
    //         wp_enqueue_style('wpbe-main', WPBEL_CSS_URL . 'style.core.css', [], WPBEL_VERSION);
    //     }
    // }

    public function enqueue_scripts($page)
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wpbe') { //phpcs:ignore
            if (defined('WBEBL_NAME')) {
                if (\wbebl\framework\onboarding\Onboarding::is_completed()) {
                    $this->main_enqueue_scripts();
                } else {
                    $this->onboarding_enqueue_scripts();
                }
            } else {
                if (Onboarding::is_completed()) {
                    $this->main_enqueue_scripts();
                } else {
                    $this->onboarding_enqueue_scripts();
                }
            }
        }
    }

    public function main_enqueue_scripts()
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wpbe') { //phpcs:ignore
            $setting_repository = new Setting();
            $meta_field_repository = new Meta_Field();
            $search_repository = new Search();

            // Styles
            wp_enqueue_style('wpbel-reset', WPBEL_CSS_URL . 'reset.css', [], WPBEL_VERSION);
            wp_enqueue_style('wpbel-icomoon', WPBEL_CSS_URL . 'icomoon.css', [], WPBEL_VERSION);
            wp_enqueue_style('wpbel-datepicker', WPBEL_CSS_URL . 'bootstrap-material-datetimepicker.css', [], WPBEL_VERSION);
            wp_enqueue_style('wpbel-select2', WPBEL_CSS_URL . 'select2.css', [], WPBEL_VERSION);
            wp_enqueue_style('wpbel-LineIcons', WPBEL_CSS_URL . 'LineIcons.css', [], WPBEL_VERSION);
            wp_enqueue_style('wpbel-sweetalert', WPBEL_CSS_URL . 'sweetalert.css', [], WPBEL_VERSION);
            wp_enqueue_style('wpbel-jquery-ui', WPBEL_CSS_URL . 'jquery-ui.css', [], WPBEL_VERSION);
            wp_enqueue_style('wpbel-tipsy', WPBEL_CSS_URL . 'jquery.tipsy.css', [], WPBEL_VERSION);
            wp_enqueue_style('wpbel-datetimepicker', WPBEL_CSS_URL . 'jquery.datetimepicker.css', [], WPBEL_VERSION);
            wp_enqueue_style('wpbel-main-core', WPBEL_CSS_URL . 'style.core.css', [], WPBEL_VERSION);
            wp_enqueue_style('wpbel-main', WPBEL_CSS_URL . 'style.css', [], WPBEL_VERSION);
            wp_enqueue_style('wp-color-picker');

            // Scripts
            wp_enqueue_script('wpbel-datetimepicker', WPBEL_JS_URL . 'jquery.datetimepicker.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
            wp_enqueue_script('wpbel-functions-core', WPBEL_JS_URL . 'functions-core.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
            wp_enqueue_script('wpbel-functions', WPBEL_JS_URL . 'functions.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
            wp_enqueue_script('wpbel-select2', WPBEL_JS_URL . 'select2.min.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
            wp_enqueue_script('wpbel-moment', WPBEL_JS_URL . 'moment-with-locales.min.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
            wp_enqueue_script('wpbel-tipsy', WPBEL_JS_URL . 'jquery.tipsy.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
            wp_enqueue_script('wpbel-bootstrap_datepicker', WPBEL_JS_URL . 'bootstrap-material-datetimepicker.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
            wp_enqueue_script('wpbel-sweetalert', WPBEL_JS_URL . 'sweetalert.min.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
            wp_enqueue_script('wpbel-main-core', WPBEL_JS_URL . 'main-core.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
            wp_enqueue_script('wpbel-main', WPBEL_JS_URL . 'main.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
            wp_localize_script('wpbel-main', 'WPBE_DATA', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('wpbe_ajax_nonce'),
                'reserved_field_keys' => $meta_field_repository->get_reserved_field_names(),
                'strings' => [
                    'please_select_one_item' => esc_html__('Please select one post', 'ithemeland-bulk-posts-editing-lite')
                ],
                'filter_option_values' => $search_repository->get_option_values(),
                'wpbe_settings' => $setting_repository->get_settings(),
                'background_process' => [
                    'max_process_count' => [
                        'post_create' => 100,
                        'post_restore' => 100,
                        'post_update' => 10,
                        'post_duplicate_count' => PostDuplicateService::MAX_PROCESS_COUNT,
                        'post_duplicate_ids' => PostDuplicateService::MAX_PROCESS_IDS,
                        'post_delete' => PostDeleteService::MAX_PROCESS_COUNT,
                        'history_undo' => HistoryUndoService::MAX_PROCESS_COUNT,
                        'history_redo' => HistoryRedoService::MAX_PROCESS_COUNT
                    ],
                    'loading_messages' => [
                        'processing' => Render::html(WPBEL_VIEWS_DIR . 'background_process/processing_message.php'),
                        'stopping' => Render::html(WPBEL_VIEWS_DIR . 'background_process/stopping_message.php'),
                    ]
                ],
            ]);
            wp_enqueue_media();
            wp_enqueue_editor();
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('wp-color-picker');
        }
    }

    private function onboarding_enqueue_scripts()
    {
        wp_enqueue_style('wpbel-sweetalert', WPBEL_CSS_URL . 'sweetalert.css', [], WPBEL_VERSION);
        wp_enqueue_script('wpbel-sweetalert', WPBEL_JS_URL . 'sweetalert.min.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore

        if (defined('WBEBL_NAME')) {
            wp_enqueue_style('wpbel-onboarding', WBEBL_FW_URL . 'onboarding/assets/css/onboarding.css', [], WBEBL_VERSION);
            wp_enqueue_script('wpbel-onboarding', WBEBL_FW_URL . 'onboarding/assets/js/onboarding.js', ['jquery'], WBEBL_VERSION); //phpcs:ignore
        } else {
            wp_enqueue_style('wpbel-onboarding', WPBEL_FW_URL . 'onboarding/assets/css/onboarding.css', [], WPBEL_VERSION);
            wp_enqueue_script('wpbel-onboarding', WPBEL_FW_URL . 'onboarding/assets/js/onboarding.js', ['jquery'], WPBEL_VERSION); //phpcs:ignore
        }

        wp_localize_script('wpbel-onboarding', 'ithemeland_onboarding', [
            'nonce' => wp_create_nonce('ithemeland_onboarding_action'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'redirecting_text' => esc_html__('Redirecting...', 'ithemeland-bulk-posts-editing-lite'),
            'skip_text' => esc_html__('Skip', 'ithemeland-bulk-posts-editing-lite')
        ]);
    }

    public static function activate()
    {
        (new Setting('post'))->set_default_settings();
        (new Column('post'))->set_default_columns();
        (new Search('post'))->set_default_item();
    }

    public static function deactivate()
    {
        // 
    }

    public static function is_initable()
    {
        if (defined('WBEBL_NAME')) {
            self::$is_initable = true;
            return true;
        }

        if (!is_null(self::$is_initable)) {
            return self::$is_initable;
        }

        if (defined('WPBE_VERSION') && version_compare(WPBE_VERSION, '2.0.0', '<')) {
            self::$is_initable = false;
            return false;
        }

        self::$is_initable = true;
        return true;
    }

    private static function create_tables()
    {
        global $wpdb;
        $history_table_name = esc_sql($wpdb->prefix . 'itbbc_history');
        $history_items_table_name = esc_sql($wpdb->prefix . 'itbbc_history_items');
        $query = '';
        $history_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_table_name));
        if (!$wpdb->get_var($history_table) == $history_table_name) {  //phpcs:ignore
            $query .= "CREATE TABLE {$history_table_name} (" .  //phpcs:ignore
                "id int(11) NOT NULL AUTO_INCREMENT,
                  user_id int(11) NOT NULL,
                  fields text NOT NULL,
                  operation_type varchar(32) NOT NULL,
                  operation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  reverted tinyint(1) NOT NULL DEFAULT '0',
                  sub_system varchar(64) NOT NULL,
                  PRIMARY KEY (id),
                  INDEX (user_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        }

        $history_items_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_items_table_name));
        if (!$wpdb->get_var($history_items_table) == $history_items_table_name) { //phpcs:ignore
            $query .= "CREATE TABLE {$history_items_table_name} (" . //phpcs:ignore
                "id int(11) NOT NULL AUTO_INCREMENT,
                      history_id int(11) NOT NULL,
                      historiable_id int(11) NOT NULL,
                      field longtext,
                      prev_value longtext,
                      new_value longtext,
                      prev_total_count int(11) NOT NULL DEFAULT 1,
                      new_total_count int(11) NOT NULL DEFAULT 1,
                      PRIMARY KEY (id),
                      INDEX (history_id),
                      INDEX (historiable_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

            $query .= "ALTER TABLE {$history_items_table_name} ADD CONSTRAINT itbbc_history_items_history_id_relation FOREIGN KEY (history_id) REFERENCES {$history_table_name} (id) ON DELETE CASCADE ON UPDATE CASCADE;";
        } else {
            $result = $wpdb->get_results("SELECT DATA_TYPE as itbbc_field_type FROM information_schema.columns WHERE table_name = '{$history_items_table_name}' AND column_name = 'field'"); //phpcs:ignore
            if (!empty($result[0]->wpbe_field_type) && $result[0]->wpbe_field_type != 'longtext') {
                $wpdb->query("ALTER TABLE {$history_items_table_name} MODIFY field longtext"); //phpcs:ignore
            }
        }

        if (!empty($query)) {
            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($query);
        }
    }

    private static function update_table()
    {
        global $wpdb;
        $history_items_table_name = esc_sql($wpdb->prefix . 'itbbc_history_items');
        $result = $wpdb->get_var("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$history_items_table_name}' AND COLUMN_NAME = 'prev_total_count'"); //phpcs:ignore
        if (empty($result)) {
            try {
                $wpdb->query("ALTER TABLE {$history_items_table_name} ADD prev_total_count INT(11) NOT NULL DEFAULT 1"); //phpcs:ignore
                $wpdb->query("ALTER TABLE {$history_items_table_name} ADD new_total_count INT(11) NOT NULL DEFAULT 1"); //phpcs:ignore
            } catch (\Exception $e) {
                update_option('wpbe_update_table_log', $e->getMessage());
            }
        }
    }
}
