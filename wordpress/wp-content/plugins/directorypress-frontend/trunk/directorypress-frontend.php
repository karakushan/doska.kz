<?php

/**
 * Plugin Name:       DirectoryPress Frontend
 * Plugin URI:        https://designinvento.net/downloads/directorypress-frontend-listing-addon/
 * Description:       Frontend Ads listing functionality for DirectoryPress Plugin.
 * Version:           2.8.3
 * Author:            Designinvento
 * Author URI:        https://designinvento.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires Plugins: directorypress
 * Text Domain:       directorypress-frontend
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DIRECTORYPRESS_FRONTEND_VERSION', '2.8.3' );
define('DPFL_PATH', plugin_dir_path(__FILE__));
define('DPFL_URL', plugins_url('/', __FILE__));
define( 'DPFL_TEMPLATES_PATH', DPFL_PATH . 'public/');


function activate_directorypress_frontend() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-frontend-activator.php';
	Directorypress_Frontend_Activator::activate();
}

function deactivate_directorypress_frontend() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-frontend-deactivator.php';
	Directorypress_Frontend_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_directorypress_frontend' );
register_deactivation_hook( __FILE__, 'deactivate_directorypress_frontend' );

require plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-frontend.php';

function run_directorypress_frontend() {
	//global $directorypress_object;
	$directorypress_fsubmit_instance = new Directorypress_Frontend();
	$directorypress_fsubmit_instance->run();
}
add_action( 'directorypress_after_loaded', 'run_directorypress_frontend' );
