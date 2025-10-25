<?php

/**
 * @package    Classiadspro_Core
 * @subpackage Classiadspro_Core/public
 * @author     Designinvento <team@designinvento.net>
 */
 

class Classiadspro_Core_Public {
	
	private $plugin_name;
	private $version;
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	public function enqueue_styles() {
		wp_register_style('pacz-accordion', PCPT_ASSETS_URL . 'css/accordion.css');
		wp_register_style('pacz-animated-columns', PCPT_ASSETS_URL . 'css/animated-columns.css');
		wp_register_style('pacz-audioplayer', PCPT_ASSETS_URL . 'css/audioplayer.css');
		wp_register_style('pacz-button', PCPT_ASSETS_URL . 'css/button.css');
		wp_register_style('pacz-call-to-action', PCPT_ASSETS_URL . 'css/call-to-action.css');
		wp_register_style('pacz-chart', PCPT_ASSETS_URL . 'css/chart.css');
		wp_register_style('pacz-clients', PCPT_ASSETS_URL . 'css/clients.css');
		wp_register_style('pacz-countdown', PCPT_ASSETS_URL . 'css/countdown.css');
		wp_register_style('pacz-employees', PCPT_ASSETS_URL . 'css/employees.css');
		wp_register_style('pacz-flickr', PCPT_ASSETS_URL . 'css/flickr.css');
		wp_register_style('pacz-flipbox', PCPT_ASSETS_URL . 'css/flipbox.css');
		wp_register_style('pacz-gallery', PCPT_ASSETS_URL . 'css/gallery.css');
		wp_register_style('pacz-iconbox', PCPT_ASSETS_URL . 'css/iconbox.css');
		wp_register_style('pacz-imagebox', PCPT_ASSETS_URL . 'css/imagebox.css');
		wp_register_style('pacz-list', PCPT_ASSETS_URL . 'css/list.css');
		wp_register_style('pacz-messagebox', PCPT_ASSETS_URL . 'css/messagebox.css');
		wp_register_style('pacz-process-steps', PCPT_ASSETS_URL . 'css/process-steps.css');
		wp_register_style('pacz-product-loop', PCPT_ASSETS_URL . 'css/product-loop.css');
		wp_register_style('pacz-progress', PCPT_ASSETS_URL . 'css/progress.css');
		wp_register_style('pacz-skillmeter', PCPT_ASSETS_URL . 'css/skillmeter.css');
		wp_register_style('pacz-slideshow', PCPT_ASSETS_URL . 'css/slideshow.css');
		wp_register_style('pacz-social', PCPT_ASSETS_URL . 'css/social.css');
		wp_register_style('pacz-tables', PCPT_ASSETS_URL . 'css/tables.css');
		wp_register_style('pacz-tabs', PCPT_ASSETS_URL . 'css/tabs.css');
		wp_register_style('pacz-twitter', PCPT_ASSETS_URL . 'css/twitter.css');
		wp_register_style('pacz-typo', PCPT_ASSETS_URL . 'css/typo.css');
		wp_register_style('pacz-videoplayer', PCPT_ASSETS_URL . 'css/videoplayer.css');
		wp_register_style('pacz-windowscroller', PCPT_ASSETS_URL . 'css/windowscroller.css');
		wp_register_style('pacz-testimonial', PCPT_ASSETS_URL . 'css/testimonials.css');
		
	}
	public function enqueue_scripts() {
		wp_register_script('pacz_core_frontend', PCPT_ASSETS_URL . 'js/frontend.js', array('jquery'));
		
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/classiadspro-core-public.js', array( 'jquery' ), $this->version, false );

	}

}
