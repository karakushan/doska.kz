<?php

/**
 * @package    Form_Builder_Wp
 * @subpackage Form_Builder_Wp/public
 * @author     Designinvento <team@designinvento.net>
 */
class Form_Builder_Wp_Public {

	
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {


		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/form-builder-wp-public.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/form-builder-wp-public.js', array( 'jquery' ), $this->version, false );

	}

}
