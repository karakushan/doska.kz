<?php
/*
Plugin Name: iThemeland Wordpress Bulk Posts Editing Lite
Plugin URI: https://www.ithemelandco.com/Plugins/Pro-Bulk-Editing/wordpress-posts-bulk-editing-lite
Description: Editing Date in WordPress is very painful. Be professionals with managing data in the reliable and flexible way by Wordpress Bulk Post Editor.
Author: iThemelandco
Tested up to: WP 6.8.1
Requires PHP: 8.0.3
Tags: wordpress bulk edit,bulk edit,bulk,posts bulk editor
Text Domain: ithemeland-bulk-posts-editing-lite
Domain Path: /languages
Version: 5.0.7
License: GPLv3
Author URI: https://www.ithemelandco.com
*/

use wpbel\classes\bootstrap\WPBEL;

defined('ABSPATH') || exit();

if (defined('WPBEL_NAME')) {
    return false;
}

require_once __DIR__ . '/vendor/autoload.php';

define('WPBEL_ACTIVE', true);
define('WPBEL_NAME', 'ithemeland-bulk-posts-editing-lite');
define('WPBEL_LABEL', 'WPBULKiT - Bulk Edit Wordpress Posts & Pages Lite');
define('WPBEL_DESCRIPTION', 'Be professionals with managing data in the reliable and flexible way!');
define('WPBEL_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WPBEL_PLUGIN_MAIN_PAGE', admin_url('admin.php?page=wpbe'));
define('WPBEL_ADD_ONS_URL', admin_url('admin.php?page=wpbe-add-ons'));
define('WPBEL_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WPBEL_FW_DIR', trailingslashit(WPBEL_DIR . 'framework'));
define('WPBEL_FW_URL', trailingslashit(WPBEL_URL . 'framework'));
define('WPBEL_LIB_DIR', trailingslashit(WPBEL_DIR . 'classes/lib'));
define('WPBEL_VIEWS_DIR', trailingslashit(WPBEL_DIR . 'views'));
define('WPBEL_LANGUAGES_DIR', dirname(plugin_basename(__FILE__)) . '/languages/');
define('WPBEL_ASSETS_DIR', trailingslashit(WPBEL_DIR . 'assets'));
define('WPBEL_ASSETS_URL', trailingslashit(WPBEL_URL . 'assets'));
define('WPBEL_CSS_URL', trailingslashit(WPBEL_ASSETS_URL . 'css'));
define('WPBEL_CSS_CORE_URL', trailingslashit(WPBEL_CSS_URL . 'core'));
define('WPBEL_IMAGES_URL', trailingslashit(WPBEL_ASSETS_URL . 'images'));
define('WPBEL_JS_URL', trailingslashit(WPBEL_ASSETS_URL . 'js'));
define('WPBEL_JS_CORE_URL', trailingslashit(WPBEL_JS_URL . 'core'));
define('WPBEL_VERSION', '5.0.7');
define('WPBEL_PRO_LINK', 'https://ithemelandco.com/plugins/wordpress-bulk-posts-editing?utm_source=free_plugins&amp;utm_medium=plugin_links&amp;utm_campaign=user-lite-buy#pricing');

register_activation_hook(__FILE__, ['\wpbel\classes\bootstrap\WPBEL', 'activate']);
register_deactivation_hook(__FILE__, ['\wpbel\classes\bootstrap\WPBEL', 'deactivate']);

add_action('init', ['wpbel\classes\bootstrap\WPBEL', 'wpbe_wp_init']);

add_action('plugins_loaded', function () {
    if (WPBEL::is_initable()) {
        WPBEL::init();
    } else {
        if (isset($_GET['page']) && $_GET['page'] == 'wpbe' && !defined('WPBE_NAME')) { //phpcs:ignore
            header("Location: " . admin_url('index.php'));
            die();
        }
    }
}, PHP_INT_MAX);
