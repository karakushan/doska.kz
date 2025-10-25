<?php

/**
 * Plugin Name:       Terms Order Wp
 * Plugin URI:        https://designinvento.net/terms-order-wp
 * Description:       A simple plugin to reorder categories and custom post taxonomies.
 * Version:           1.0.5
 * Author:            Designinvento
 * Author URI:        https://designinvento.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       terms-order-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define('TERMS_ORDER_WP_VERSION', '1.0.5');
define('TOWP_PATH', plugin_dir_path(__FILE__));
define('TOWP_URL', plugins_url('/', __FILE__));
define('TOWP_ASSETS_PATH', TOWP_PATH . 'assets/');
define('TOWP_ASSETS_URL', TOWP_URL . 'assets/');

/**
 * Plugin Activation.
 * This action is documented in includes/class-terms-order-wp-activator.php
 */
function activate_terms_order() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-terms-order-wp-activator.php';
	Terms_Order_Wp_Activator::activate();
}

/**
 * Plugin Deactivation
 * This action is documented in includes/class-terms-order-wp-deactivator.php
 */
function deactivate_terms_order() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-terms-order-wp-deactivator.php';
	Terms_Order_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_terms_order' );
register_deactivation_hook( __FILE__, 'deactivate_terms_order' );

/**
 * Plugin Internationalization,
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-terms-order-wp.php';

/**
 * Begins execution of the plugin.
 */
function run_terms_order_wp() {

	$plugin = new Terms_Order_Wp();
	$plugin->run();

}
run_terms_order_wp();
