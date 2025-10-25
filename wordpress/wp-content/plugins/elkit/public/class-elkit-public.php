<?php

/**
 * @package    Elkit
 * @subpackage Elkit/public
 * @author     Designinvento <team@designinvento.net>
 */
class Elkit_Public {

	
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {
		wp_enqueue_style( 'md-icons', ELKIT_RESOURCES_URL . 'icons/material-icons/css/material-icons.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, ELKIT_RESOURCES_URL . 'css/elkit-public.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {
		wp_register_script('elkit-public', ELKIT_RESOURCES_URL . 'js/elkit-public.js', array('jquery'));
		wp_register_script('elkit-countdown', ELKIT_RESOURCES_URL . 'js/countdown.min.js', array('jquery'));
		//wp_enqueue_script( 'elkit-public', ELKIT_RESOURCES_URL . 'js/elkit-public.js', array( 'jquery' ), $this->version, false );

	}

}
