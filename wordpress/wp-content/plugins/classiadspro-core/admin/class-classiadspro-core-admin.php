<?php

/**
 * @package    Classiadspro_Core
 * @subpackage Classiadspro_Core/admin
 * @author     Designinvento <team@designinvento.net>
 */
class Classiadspro_Core_Admin {

	private $plugin_name;
	private $version;
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	public function enqueue_styles() {
		
		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/classiadspro-core-admin.css', array(), $this->version, 'all' );

	}
	
	public function enqueue_scripts() {
		if (class_exists('Classiadspro_Theme')) {
			$theme_data = wp_get_theme("classiadspro");
			//wp_enqueue_script('bootstrap', PACZ_THEME_JS . '/bootstrap.min.js', array('jquery') , $theme_data['Version'], true);
			//wp_enqueue_script('select2', PACZ_THEME_JS . '/select2.min.js', array('jquery') , $theme_data['Version'], true);			
		}
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/classiadspro-core-admin.js', array( 'jquery' ), $this->version, false );

	}

}
