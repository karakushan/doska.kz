<?php
/**
 * HFB_Default_Compat setup
 *
 * @package header-footer-builder
 */

namespace HFB\Themes;

/**
 * HFB theme compatibility.
 */
class HFB_Default_Compat {

	/**
	 *  Initiator
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'hooks' ] );
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function hooks() {
		if ( hfb_header_enabled() ) {
			// Replace header.php template.
			add_action( 'get_header', [ $this, 'override_header' ] );

			// Display HFB's header in the replaced header.
			add_action( 'hfb_header', 'hfb_render_header' );
		}

		if ( hfb_footer_enabled() || hfb_is_before_footer_enabled() ) {
			// Replace footer.php template.
			add_action( 'get_footer', [ $this, 'override_footer' ] );
		}

		if ( hfb_footer_enabled() ) {
			// Display HFB's footer in the replaced header.
			add_action( 'hfb_footer', 'hfb_render_footer' );
		}

		if ( hfb_is_before_footer_enabled() ) {
			add_action( 'hfb_footer_before', [ 'Header_Footer_Builder', 'get_before_footer_content' ] );
		}
	}

	/**
	 * Function for overriding the header in the elmentor way.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function override_header() {
		require HEADER_FOOTER_BUILDER_PATH . 'includes/theme-support/default/hfb-header.php';
		$templates   = [];
		$templates[] = 'header.php';
		// Avoid running wp_head hooks again.
		remove_all_actions( 'wp_head' );
		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

	/**
	 * Function for overriding the footer in the elmentor way.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function override_footer() {
		require HEADER_FOOTER_BUILDER_PATH . 'includes/theme-support/default/hfb-footer.php';
		$templates   = [];
		$templates[] = 'footer.php';
		// Avoid running wp_footer hooks again.
		remove_all_actions( 'wp_footer' );
		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

}

new HFB_Default_Compat();
