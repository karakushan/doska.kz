<?php

/**
 * Plugin Name:       ClassiadsPro Core
 * Plugin URI:        https://designinvento.net/plugins/classiadspro-core/
 * Description:       Core Plugin for ClassiadsPro.
 * Version:           1.3.1
 * Author:            Designinvento
 * Author URI:        https://designinvento.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pacz
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CLASSIADSPRO_CORE_VERSION', '1.3.1' );
define('PCPT_URL', plugins_url('/', __FILE__));
define('PCPT_PATH', plugin_dir_path(__FILE__));
define( 'PCPT_INCLUDES', PCPT_URL. 'includes/');
define('PCPT_ASSETS_PATH', PCPT_PATH . '/assets/');
define('PCPT_ASSETS_URL', PCPT_URL . 'assets/');

function activate_classiadspro_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-classiadspro-core-activator.php';
	Classiadspro_Core_Activator::activate();
}
function deactivate_classiadspro_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-classiadspro-core-deactivator.php';
	Classiadspro_Core_Deactivator::deactivate();
}


register_activation_hook( __FILE__, 'activate_classiadspro_core' );
register_deactivation_hook( __FILE__, 'deactivate_classiadspro_core' );

require plugin_dir_path( __FILE__ ) . 'includes/class-classiadspro-core.php';

function run_classiadspro_core() {

	$classiadspro_core_object = new Classiadspro_Core();
	$classiadspro_core_object->run();

}

run_classiadspro_core();

