<?php
/**
 * Header Footer Builder Function
 *
 * @package  header-footer-builder
 */

/**
 * Checks if Header is enabled from HFB.
 *
 * @since  1.0.2
 * @return bool True if header is enabled. False if header is not enabled
 */
function hfb_header_enabled() {
	$header_id = Header_Footer_Builder::get_settings( 'type_header', '' );
	$status    = false;

	if ( '' !== $header_id ) {
		$status = true;
	}

	return apply_filters( 'hfb_header_enabled', $status );
}

/**
 * Checks if Footer is enabled from HFB.
 *
 * @since  1.0.2
 * @return bool True if header is enabled. False if header is not enabled.
 */
function hfb_footer_enabled() {
	$footer_id = Header_Footer_Builder::get_settings( 'type_footer', '' );
	$status    = false;

	if ( '' !== $footer_id ) {
		$status = true;
	}

	return apply_filters( 'hfb_footer_enabled', $status );
}

/**
 * Get HFB Header ID
 *
 * @since  1.0.2
 * @return (String|boolean) header id if it is set else returns false.
 */
function get_hfb_header_id() {
	$header_id = Header_Footer_Builder::get_settings( 'type_header', '' );

	if ( '' === $header_id ) {
		$header_id = false;
	}

	return apply_filters( 'get_hfb_header_id', $header_id );
}

/**
 * Get HFB Footer ID
 *
 * @since  1.0.2
 * @return (String|boolean) header id if it is set else returns false.
 */
function get_hfb_footer_id() {
	$footer_id = Header_Footer_Builder::get_settings( 'type_footer', '' );

	if ( '' === $footer_id ) {
		$footer_id = false;
	}

	return apply_filters( 'get_hfb_footer_id', $footer_id );
}

/**
 * Display header markup.
 *
 * @since  1.0.2
 */
function hfb_render_header() {

	if ( false == apply_filters( 'enable_hfb_render_header', true ) ) {
		return;
	}

	?>
		<header id="masthead" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
			<p class="main-title bhf-hidden" itemprop="headline"><a href="<?php echo bloginfo( 'url' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php Header_Footer_Builder::get_header_content(); ?>
		</header>

	<?php

}

/**
 * Display footer markup.
 *
 * @since  1.0.2
 */
function hfb_render_footer() {

	if ( false == apply_filters( 'enable_hfb_render_footer', true ) ) {
		return;
	}

	?>
		<footer itemtype="https://schema.org/WPFooter" itemscope="itemscope" id="colophon" role="contentinfo">
			<?php Header_Footer_Builder::get_footer_content(); ?>
		</footer>
	<?php

}


/**
 * Get HFB Before Footer ID
 *
 * @since  1.0.2
 * @return String|boolean before footer id if it is set else returns false.
 */
function hfb_get_before_footer_id() {

	$before_footer_id = Header_Footer_Builder::get_settings( 'type_before_footer', '' );

	if ( '' === $before_footer_id ) {
		$before_footer_id = false;
	}

	return apply_filters( 'get_hfb_before_footer_id', $before_footer_id );
}

/**
 * Checks if Before Footer is enabled from HFB.
 *
 * @since  1.0.2
 * @return bool True if before footer is enabled. False if before footer is not enabled.
 */
function hfb_is_before_footer_enabled() {

	$before_footer_id = Header_Footer_Builder::get_settings( 'type_before_footer', '' );
	$status           = false;

	if ( '' !== $before_footer_id ) {
		$status = true;
	}

	return apply_filters( 'hfb_before_footer_enabled', $status );
}

/**
 * Display before footer markup.
 *
 * @since  1.0.2
 */
function hfb_render_before_footer() {

	if ( false == apply_filters( 'enable_hfb_render_before_footer', true ) ) {
		return;
	}

	?>
		<div class="hfb-before-footer-wrap">
			<?php Header_Footer_Builder::get_before_footer_content(); ?>
		</div>
	<?php

}
