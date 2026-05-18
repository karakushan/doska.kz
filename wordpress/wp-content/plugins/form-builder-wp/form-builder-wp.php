<?php

/**
 * Plugin Name:       Form Builder WP
 * Plugin URI:        https://designinvento.net/downloads/form-builder-wp
 * Description:       Most advanced form builder plugin for Elementor page builder.
 * Version:           1.1.8
 * Author:            Designinvento
 * Author URI:        https://designinvento.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       form-builder-wp
 * Domain Path:       /languages
 *
 Elementor tested up to: 3.13.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if(!defined('FORM_BUILDER_WP')){
	define('FORM_BUILDER_WP','form-builder-wp');
}
define( 'FORM_BUILDER_WP_VERSION', '1.1.8' );
define('FORM_BUILDER_WP_PATH', plugin_dir_path(__FILE__));
define('FORM_BUILDER_WP_URL', plugins_url('/', __FILE__));
define('FORM_BUILDER_WP_TEMPLATES_PATH', FORM_BUILDER_WP_PATH . 'public/');
define('FORM_BUILDER_WP_RESOURCES_PATH', FORM_BUILDER_WP_PATH . 'assets/');
define('FORM_BUILDER_WP_RESOURCES_URL', FORM_BUILDER_WP_URL . 'assets/');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-form-builder-wp-activator.php
 */
function activate_form_builder_wp() {
	flush_rewrite_rules();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-form-builder-wp-activator.php';
	Form_Builder_Wp_Activator::activate();
	do_action('wpfb_roles');
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-form-builder-wp-deactivator.php
 */
function deactivate_form_builder_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-form-builder-wp-deactivator.php';
	Form_Builder_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_form_builder_wp' );
register_deactivation_hook( __FILE__, 'deactivate_form_builder_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-form-builder-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_form_builder_wp() {

	$plugin = new Form_Builder_Wp();
	$plugin->run();

}
run_form_builder_wp();
