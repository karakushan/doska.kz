<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link        https://designinvento.net
 * @since      1.0.0
 *
 * @package    Directorypress_Extended_Locations
 * @subpackage Directorypress_Extended_Locations/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Directorypress_Extended_Locations
 * @subpackage Directorypress_Extended_Locations/includes
 * @author     Designinvento <developers@designinvento.net>
 */
class Directorypress_Extended_Locations_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'directorypress-extended-locations',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
