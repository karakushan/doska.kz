<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://designinvento.net/
 * @since      1.0.0
 *
 * @package    Header_Footer_Builder
 * @subpackage Header_Footer_Builder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Header_Footer_Builder
 * @subpackage Header_Footer_Builder/public
 * @author     Designinvento <help.designinvento@gmail.com>
 */
class Header_Footer_Builder_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Header_Footer_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Header_Footer_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'hfb-menu', HEADER_FOOTER_BUILDER_ASSETS_URL . 'public/css/menu.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Header_Footer_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Header_Footer_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'hfb-menu', HEADER_FOOTER_BUILDER_ASSETS_URL . 'public/js/menu.js', array( 'jquery' ), $this->version, false );

	}

}
