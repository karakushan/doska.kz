<?php

/**
 * @package    Directorypress_Extended_Locations
 * @subpackage Directorypress_Extended_Locations/admin
 * @author     Designinvento <developers@designinvento.net>
 */
class Directorypress_Extended_Locations_Admin {
	
	private $plugin_name;
	private $version;
	
	public function __construct() {
		global $directorypress_object;
		if(is_object($directorypress_object)){
			$directorypress_object->locations_depths_manager = new directorypress_locations_depths_manager;
		}
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'), 0);
	}
	
	public function enqueue_styles() {

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/directorypress-extended-locations-admin.css', array(), $this->version, 'all' );

	}
	
	public function enqueue_scripts() {

		wp_enqueue_script( 'dpel-admin-js', DPEL_URL . 'assets/js/directorypress-extended-locations-admin.js', array( 'jquery' ), false, false );

	}

}
