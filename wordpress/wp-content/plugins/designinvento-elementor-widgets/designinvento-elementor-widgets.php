<?php

/**
 * Plugin Name:       Designinvento Elementor Widgets
 * Plugin URI:        https://designinvento.net/plugins/designinvento-elementor-widgets/
 * Description:       Plugin Provides Premium features for All Designinvento Themes
 * Version:           1.1.2
 * Author:            Designinvento
 * Author URI:        https://designinvento.net/
 * Text Domain:       designinvento-elementor-widgets
 * Domain Path:       /languages
 *
 Elementor tested up to: 3.30.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DESIGNINVENTO_ELEMENTOR_WIDGETS_VERSION', '1.1.2' );
define('DEW_URL', plugins_url('/', __FILE__));
define('DEW_PATH', plugin_dir_path(__FILE__));
define( 'DEW_INCLUDES', DEW_URL. 'includes/');
define('DEW_ASSETS_PATH', DEW_PATH . '/assets/');
define('DEW_ASSETS_URL', DEW_URL . 'assets/');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-designinvento-elementor-widgets-activator.php
 */
function activate_designinvento_elementor_widgets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-designinvento-elementor-widgets-activator.php';
	Designinvento_Elementor_Widgets_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-designinvento-elementor-widgets-deactivator.php
 */
function deactivate_designinvento_elementor_widgets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-designinvento-elementor-widgets-deactivator.php';
	Designinvento_Elementor_Widgets_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_designinvento_elementor_widgets' );
register_deactivation_hook( __FILE__, 'deactivate_designinvento_elementor_widgets' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-designinvento-elementor-widgets.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_designinvento_elementor_widgets() {

	$plugin = new Designinvento_Elementor_Widgets();
	$plugin->run();

}
run_designinvento_elementor_widgets();
