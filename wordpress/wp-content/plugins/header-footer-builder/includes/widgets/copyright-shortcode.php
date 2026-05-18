<?php
/**
 * Calling copyright shortcode.
 *
 * @package Copyright
 * @author Brainstorm Force
 */

namespace HFB\WidgetsManager\Widgets;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Helper class for the Copyright.
 *
 * @since 1.2.0
 */
class Copyright_Shortcode {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_shortcode( 'hfb_current_year', [ $this, 'display_current_year' ] );
		add_shortcode( 'hfb_site_title', [ $this, 'display_site_title' ] );
	}

	/**
	 * Get the hfb_current_year Details.
	 *
	 * @return array $hfb_current_year Get Current Year Details.
	 */
	public function display_current_year() {

		$hfb_current_year = gmdate( 'Y' );
		$hfb_current_year = do_shortcode( shortcode_unautop( $hfb_current_year ) );
		if ( ! empty( $hfb_current_year ) ) {
			return $hfb_current_year;
		}
	}

	/**
	 * Get site title of Site.
	 *
	 * @return string.
	 */
	public function display_site_title() {

		$hfb_site_title = get_bloginfo( 'name' );

		if ( ! empty( $hfb_site_title ) ) {
			return $hfb_site_title;
		}
	}

}

new Copyright_Shortcode();
