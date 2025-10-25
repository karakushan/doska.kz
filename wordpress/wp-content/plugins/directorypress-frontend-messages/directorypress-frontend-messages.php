<?php

/**
 * Plugin Name:        DirectoryPress Frontend Messages
 * Plugin URI:        https://designinvento.net/downloads/directorypress-frontend-messages-addon/
 * Description:       This addon plugin offer frontend personal messages for directorypress.
 * Version:           5.4.7
 * Author:            Designinvento
 * Author URI:        https://designinvento.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires Plugins: directorypress
 * Text Domain:       directorypress-frontend-messages
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DIRECTORYPRESS_FRONTEND_MESSAGES_VERSION', '5.4.7' );
global $wpdb;
			
define('DIFP_PLUGIN_VERSION', '5.4.7' );
define('DIFP_PLUGIN_FILE',  __FILE__ );
define('DIFP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define('DIFP_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

			
if ( !defined ('DIFP_MESSAGES_TABLE' ) )
define('DIFP_MESSAGES_TABLE',$wpdb->prefix.'difp_messages');
			
if ( !defined ('DIFP_META_TABLE' ) )
	define('DIFP_META_TABLE',$wpdb->prefix.'difp_meta');

function activate_directorypress_frontend_messages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-frontend-messages-activator.php';
	Directorypress_Frontend_Messages_Activator::activate();
}

function deactivate_directorypress_frontend_messages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-frontend-messages-deactivator.php';
	Directorypress_Frontend_Messages_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_directorypress_frontend_messages' );
register_deactivation_hook( __FILE__, 'deactivate_directorypress_frontend_messages' );

require plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-frontend-messages.php';

function run_directorypress_frontend_messages() {

	$plugin = new Directorypress_Frontend_Messages();
	$plugin->run();

}
run_directorypress_frontend_messages();
