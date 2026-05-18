<?php

/**
 * Plugin Name:       DirectoryPress Advanced Fields
 * Plugin URI:        https://designinvento.net/downloads/directorypress-advanced-fields
 * Description:       This addon plugin Extends field types for DirectoryPress plugin.
 * Version:           1.1.7
 * Author:            Designinvento
 * Author URI:        https://designinvento.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       directorypress-advanced-fields
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define('DIRECTORYPRESS_ADVANCED_FIELDS_VERSION', '1.1.7');
define('DIRECTORYPRESS_ADVANCED_FIELDS_PATH', plugin_dir_path(__FILE__));
define('DIRECTORYPRESS_ADVANCED_FIELDS_URL', plugins_url('/', __FILE__));
define('DIRECTORYPRESS_ADVANCED_FIELDS_ASSETS_PATH', DIRECTORYPRESS_ADVANCED_FIELDS_PATH . 'assets/');
define('DIRECTORYPRESS_ADVANCED_FIELDS_ASSETS_URL', plugins_url('/', __FILE__) . 'assets/');
define('DIRECTORYPRESS_ADVANCED_FIELDS_TEMPLATES_PATH', DIRECTORYPRESS_ADVANCED_FIELDS_PATH . 'public/');

function activate_directorypress_advanced_fields() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-advanced-fields-activator.php';
	Directorypress_Advanced_Fields_Activator::activate();
}

function deactivate_directorypress_advanced_fields() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-advanced-fields-deactivator.php';
	Directorypress_Advanced_Fields_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_directorypress_advanced_fields' );
register_deactivation_hook( __FILE__, 'deactivate_directorypress_advanced_fields' );

require plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-advanced-fields.php';

function run_directorypress_advanced_fields() {

	$directorypress_advanced_fields_object = new Directorypress_Advanced_Fields();
	$directorypress_advanced_fields_object->run();

}
//if(class_exists('DirectoryPress')){
	run_directorypress_advanced_fields();
//}
//add_action( 'directorypress_after_fields_loaded', 'run_directorypress_advanced_fields' );
