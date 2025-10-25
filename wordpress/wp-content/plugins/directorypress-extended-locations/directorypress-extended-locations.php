<?php

/**
 * Plugin Name:       DirectoryPress Extended Location
 * Plugin URI:        https://designinvento.net/downloads/directorypress-extended-locations-addon/
 * Description:       Advanced Location Addon for DirectoryPress Plugin.
 * Version:           1.7.8
 * Author:            Designinvento
 * Author URI:        https://designinvento.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires Plugins: directorypress
 * Text Domain:       directorypress-extended-locations
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'DIRECTORYPRESS_EXTENDED_LOCATIONS_VERSION', '1.7.8' );
define('DPEL_PATH', plugin_dir_path(__FILE__));
define('DPEL_URL', plugins_url('/', __FILE__));
define( 'DPEL_TEMPLATES_PATH', DPEL_PATH . 'public/');

function activate_directorypress_extended_locations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-extended-locations-activator.php';
	Directorypress_Extended_Locations_Activator::activate();
}

function deactivate_directorypress_extended_locations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-extended-locations-deactivator.php';
	Directorypress_Extended_Locations_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_directorypress_extended_locations' );
register_deactivation_hook( __FILE__, 'deactivate_directorypress_extended_locations' );

require plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-extended-locations.php';

function run_directorypress_extended_locations() {

	$directorypress_extended_locations_instance = new Directorypress_Extended_Locations();
	$directorypress_extended_locations_instance->run();

}
add_action( 'directorypress_loaded', 'run_directorypress_extended_locations' );
