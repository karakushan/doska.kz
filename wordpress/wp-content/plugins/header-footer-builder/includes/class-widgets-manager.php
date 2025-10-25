<?php
/**
 * Widgets loader for Header Footer Builder.
 *
 * @package     HFB
 * @author      HFB
 * @copyright   Copyright (c) 2018, HFB
 * @link        http://brainstormforce.com/
 * @since       HFB 1.2.0
 */

namespace HFB\WidgetsManager;

use Elementor\Plugin;
use Elementor\Utils;

defined( 'ABSPATH' ) or exit;

/**
 * Set up Widgets Loader class
 */
class Widgets_Loader {

	/**
	 * Instance of Widgets_Loader.
	 *
	 * @since  1.2.0
	 * @var null
	 */
	private static $_instance = null;

	/**
	 * Get instance of Widgets_Loader
	 *
	 * @since  1.2.0
	 * @return Widgets_Loader
	 */
	public static function instance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Setup actions and filters.
	 *
	 * @since  1.2.0
	 */
	private function __construct() {
		// Register category.
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_category' ] );

		// Register widgets.
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		
		// Add svg support.
		add_filter( 'upload_mimes', [ $this, 'svg_mime_types' ] );

		// Refresh the cart fragments.
		if ( class_exists( 'woocommerce' ) ) {
			add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'mini_cart' ], 10, 0 );
			add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'mini_cart_count' ] );
		}
	}

	/**
	 * Returns Script array.
	 *
	 * @return array()
	 * @since 1.3.0
	 */
	public static function scripts_style() {
		// widgets style.
		wp_enqueue_style( 'hfb-widgets-style', HEADER_FOOTER_BUILDER_ASSETS_URL . 'public/css/frontend.css', [], HEADER_FOOTER_BUILDER_VERSION );
		wp_register_script( 'hfb-public', HEADER_FOOTER_BUILDER_ASSETS_URL . 'public/js/frontend.js', 'jquery', HEADER_FOOTER_BUILDER_VERSION, true );
	}

	/**
	 * Provide the SVG support for Retina Logo widget.
	 *
	 * @param array $mimes which return mime type.
	 *
	 * @since  1.2.0
	 * @return $mimes.
	 */
	public function svg_mime_types( $mimes ) {
		// New allowed mime types.
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	/**
	 * Register Category
	 *
	 * @since 1.2.0
	 * @param object $this_cat class.
	 */
	public function register_widget_category( $this_cat ) {
		$category = __( 'Header Footer Builder', 'header-footer-builder' );

		$this_cat->add_category(
			'hfb-widgets',
			[
				'title' => $category,
				'icon'  => 'eicon-font',
			]
		);

		return $this_cat;
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// include Widgets files.
		$this->scripts_style();
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/image.php';
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/copyright.php';
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/copyright-shortcode.php';
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/nav-menu.php';
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/menu-walker.php';
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/logo.php';
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/mini-cart.php';
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/social-network.php';
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/search.php';
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/contact-info.php';
		require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/button.php';
		//require_once HEADER_FOOTER_BUILDER_PATH . '/includes/widgets/page-title.php';
		
		// Register Widgets.
		Plugin::instance()->widgets_manager->register( new Widgets\Image() );
		Plugin::instance()->widgets_manager->register( new Widgets\Copyright() );
		Plugin::instance()->widgets_manager->register( new Widgets\Nav_Menu() );
		Plugin::instance()->widgets_manager->register( new Widgets\Logo() );
		Plugin::instance()->widgets_manager->register( new Widgets\Search() );
		Plugin::instance()->widgets_manager->register( new Widgets\Social_Network() );
		Plugin::instance()->widgets_manager->register( new Widgets\Contact_Info() );
		Plugin::instance()->widgets_manager->register( new Widgets\Button() );
		//Plugin::instance()->widgets_manager->register( new Widgets\Page_Title() );
		if ( class_exists( 'woocommerce' ) ) {
			Plugin::instance()->widgets_manager->register( new Widgets\Mini_Cart() );
		}

	}

	public function mini_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session  = new $session_class();
			WC()->session->init();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	public function mini_cart_count( $fragments ) {

		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			return $fragments;
		}

		$cart_badge_count = ( null !== WC()->cart ) ? WC()->cart->get_cart_contents_count() : '';

		if ( null !== WC()->cart ) {

			$fragments['span.hfb-cart-count'] = '<span class="hfb-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';

			$fragments['span.hfb-button-text.hfb-subtotal'] = '<span class="hfb-button-text hfb-subtotal">' . WC()->cart->get_cart_subtotal() . '</span>';
		}

		$fragments['span.hfb-menu-item-counter[data-counter]'] = '<span class="hfb-menu-item-counter" data-counter="' . $cart_badge_count . '"></span>';

		return $fragments;
	}
	
	public static function validate_html_tag( $tag ) {

		// Check if Elementor method exists, else we will run custom validation code.
		if ( method_exists( 'Elementor\Utils', 'validate_html_tag' ) ) {
			return Utils::validate_html_tag( $tag );
		} else {
			$allowed_tags = [ 'article', 'aside', 'div', 'footer', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'main', 'nav', 'p', 'section', 'span' ];
			return in_array( strtolower( $tag ), $allowed_tags ) ? $tag : 'div';
		}
	}
	public static function kses( $raw ) {

		$allowed_tags = array(
			'a'								 => array(
				'class'	 => array(),
				'href'	 => array(),
				'rel'	 => array(),
				'title'	 => array(),
				'target' => array(),
			),
			'abbr'							 => array(
				'title' => array(),
			),
			'b'								 => array(),
			'blockquote'					 => array(
				'cite' => array(),
			),
			'cite'							 => array(
				'title' => array(),
			),
			'code'							 => array(),
			'pre'							 => array(),
			'del'							 => array(
				'datetime'	 => array(),
				'title'		 => array(),
			),
			'dd'							 => array(),
			'div'							 => array(
				'class'	 => array(),
				'title'	 => array(),
				'style'	 => array(),
			),
			'dl'							 => array(),
			'dt'							 => array(),
			'em'							 => array(),
			'strong'						 => array(),
			'h1'							 => array(
				'class' => array(),
			),
			'h2'							 => array(
				'class' => array(),
			),
			'h3'							 => array(
				'class' => array(),
			),
			'h4'							 => array(
				'class' => array(),
			),
			'h5'							 => array(
				'class' => array(),
			),
			'h6'							 => array(
				'class' => array(),
			),
			'i'								 => array(
				'class' => array(),
			),
			'img'							 => array(
				'alt'	 => array(),
				'class'	 => array(),
				'height' => array(),
				'src'	 => array(),
				'width'	 => array(),
			),
			'li'							 => array(
				'class' => array(),
			),
			'ol'							 => array(
				'class' => array(),
			),
			'p'								 => array(
				'class' => array(),
			),
			'q'								 => array(
				'cite'	 => array(),
				'title'	 => array(),
			),
			'span'							 => array(
				'class'	 => array(),
				'title'	 => array(),
				'style'	 => array(),
			),
			'iframe'						 => array(
				'width'			 => array(),
				'height'		 => array(),
				'scrolling'		 => array(),
				'frameborder'	 => array(),
				'allow'			 => array(),
				'src'			 => array(),
			),
			'strike'						 => array(),
			'br'							 => array(),
			'table'							 => array(),
			'thead'							 => array(),
			'tbody'							 => array(),
			'tfoot'							 => array(),
			'tr'							 => array(),
			'th'							 => array(),
			'td'							 => array(),
			'colgroup'						 => array(),
			'col'							 => array(),
			'strong'						 => array(),
			'data-wow-duration'				 => array(),
			'data-wow-delay'				 => array(),
			'data-wallpaper-options'		 => array(),
			'data-stellar-background-ratio'	 => array(),
			'ul'							 => array(
				'class' => array(),
			),
			'svg'   => array(
				'class' => true,
				'aria-hidden' => true,
				'aria-labelledby' => true,
				'role' => true,
				'xmlns' => true,
				'width' => true,
				'height' => true,
				'viewbox' => true, // <= Must be lower case!
			),
			'g'     => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path'  => array( 'd' => true, 'fill' => true,  ),
		);

		if ( function_exists( 'wp_kses' ) ) { // WP is here
			return wp_kses( $raw, $allowed_tags );
		} else {
			return $raw;
		}
	}
}

/**
 * Initiate the class.
 */
Widgets_Loader::instance();
