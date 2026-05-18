<?php

/**
 * Plugin Name:       DirectoryPress MultiDirectory
 * Plugin URI:        https://designinvento.net/downloads/directorypress-multi-directory-addon/
 * Description:       This Plugin provides multi-directorytype feature for DirectoryPress Plugin.
 * Version:           2.8.9
 * Author:            Designinvento
 * Author URI:        https://help.designinvento.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires Plugins: directorypress
 * Text Domain:       directorypress-multidirectory
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DIRECTORYPRESS_MULTIDIRECTORY_VERSION', '2.8.9' );
define('DPMD_PATH', plugin_dir_path(__FILE__));
define('DPMD_URL', plugins_url('/', __FILE__));
define( 'DPMD_TEMPLATES_PATH', DPMD_PATH . 'public/');


function activate_directorypress_multidirectory() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-multidirectory-activator.php';
	Directorypress_Multidirectory_Activator::activate();
}

function deactivate_directorypress_multidirectory() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-multidirectory-deactivator.php';
	Directorypress_Multidirectory_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_directorypress_multidirectory' );
register_deactivation_hook( __FILE__, 'deactivate_directorypress_multidirectory' );

require plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-multidirectory.php';

function run_directorypress_multidirectory() {

	$multidirectory_instance = new Directorypress_Multidirectory();
	$multidirectory_instance->run();

}
add_action( 'directorypress_loaded', 'run_directorypress_multidirectory' );
