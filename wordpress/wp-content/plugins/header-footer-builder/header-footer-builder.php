<?php

/**
 * Plugin Name:       Header Footer Builder
 * Plugin URI:        https://designinvento.net/downloads/header-footer-builder
 * Description:       Header And Footer Builder For Elementor Page Builder
 * Version:           1.0.8
 * Author:            Designinvento
 * Author URI:        https://designinvento.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       header-footer-builder
 * Domain Path:       /languages
 *
 Elementor tested up to: 3.13.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'HEADER_FOOTER_BUILDER_VERSION', '1.0.8' );
define('HEADER_FOOTER_BUILDER_PATH', plugin_dir_path(__FILE__));
define('HEADER_FOOTER_BUILDER_URL', plugins_url('/', __FILE__));
define('HEADER_FOOTER_BUILDER_ASSETS_PATH', HEADER_FOOTER_BUILDER_PATH . 'assets/');
define('HEADER_FOOTER_BUILDER_ASSETS_URL', HEADER_FOOTER_BUILDER_URL . 'assets/');

function activate_header_footer_builder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-header-footer-builder-activator.php';
	Header_Footer_Builder_Activator::activate();
}

function deactivate_header_footer_builder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-header-footer-builder-deactivator.php';
	Header_Footer_Builder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_header_footer_builder' );
register_deactivation_hook( __FILE__, 'deactivate_header_footer_builder' );

require plugin_dir_path( __FILE__ ) . 'includes/class-header-footer-builder.php';

function run_header_footer_builder() {

	$plugin = new Header_Footer_Builder();
	$plugin->run();

}
//run_header_footer_builder();
add_action( 'plugins_loaded', 'run_header_footer_builder' );
