<?php

class classiadsproSidebarGenerator {
	var $sidebar_names = array();
	var $footer_sidebar_count = 0;
	var $footer_sidebar_names = array();
	var $sidebar_ids = array();

	function __construct() {

		$this->sidebar_names = array(
			'page'=>esc_html__( 'Pages', 'classiadspro' ),
			'blog'=>esc_html__( 'Blog', 'classiadspro' ),
			'single_post'=>esc_html__( 'Blog Single', 'classiadspro' ),
			'search'=>esc_html__( 'Search', 'classiadspro' ),
			'404'=>esc_html__( '404', 'classiadspro' ),
			'archive'=>esc_html__( 'Archive', 'classiadspro' ),
			'woocommerce'=>esc_html__( 'Woocommerce Shop', 'classiadspro' ),
			'woocommerce_single'=>esc_html__( 'Woocommerce Single', 'classiadspro' ),
			'bbpress'=>esc_html__( 'bbPress', 'classiadspro' ),
			'alsp_listing_single'=>esc_html__( 'Listing Single', 'classiadspro' ),
			'author'=>esc_html__( 'Author Page', 'classiadspro' ),
		);

		$this->sidebar_ids = array(
			'page' => 'sidebar-1',
			'blog' => 'sidebar-2',
			'single_post' => 'sidebar-3',
			'search' => 'sidebar-4',
			'404' => 'sidebar-5',
			'archive' => 'sidebar-6',
			'woocommerce' => 'sidebar-7',
			'woocommerce_single' => 'sidebar-8',
			'bbpress' => 'sidebar-9',
			'alsp_listing_single' => 'sidebar-10',
			'author' => 'sidebar-11',
		);


		$this->footer_sidebar_names = array(
			esc_html__( 'Footer Column One', 'classiadspro' ),
			esc_html__( 'Footer Column Two', 'classiadspro' ),
			esc_html__( 'Footer Column Three', 'classiadspro' ),
			esc_html__( 'Footer Column Four', 'classiadspro' ),
			esc_html__( 'Footer Column Five', 'classiadspro' ),
			esc_html__( 'Footer Column Six', 'classiadspro' ),
		);

	}

	function get_sidebar_id( $key ) {
		return isset( $this->sidebar_ids[ $key ] ) ? $this->sidebar_ids[ $key ] : null;
	}

	function resolve_sidebar( $sidebar ) {
		global $wp_registered_sidebars;

		if ( empty( $sidebar ) ) {
			return $sidebar;
		}

		if ( isset( $wp_registered_sidebars[ $sidebar ] ) ) {
			return $sidebar;
		}

		foreach ( $wp_registered_sidebars as $sidebar_id => $registered_sidebar ) {
			if ( isset( $registered_sidebar['name'] ) && $registered_sidebar['name'] === $sidebar ) {
				return $sidebar_id;
			}
		}

		$legacy_sidebar_names = array(
			'Pages' => 'sidebar-1',
			'Blog' => 'sidebar-2',
			'Blog Single' => 'sidebar-3',
			'Search' => 'sidebar-4',
			'404' => 'sidebar-5',
			'Archive' => 'sidebar-6',
			'Woocommerce Shop' => 'sidebar-7',
			'Woocommerce Single' => 'sidebar-8',
			'bbPress' => 'sidebar-9',
			'Listing Single' => 'sidebar-10',
			'Author Page' => 'sidebar-11',
		);

		if ( isset( $legacy_sidebar_names[ $sidebar ] ) ) {
			return $legacy_sidebar_names[ $sidebar ];
		}

		foreach ( $this->sidebar_names as $sidebar_key => $sidebar_name ) {
			if ( $sidebar_name === $sidebar && isset( $this->sidebar_ids[ $sidebar_key ] ) ) {
				return $this->sidebar_ids[ $sidebar_key ];
			}
		}

		return $sidebar;
	}

	function register_sidebar() {

		$i = 1;

		foreach ( $this->sidebar_names as $name ) {
			register_sidebar( array(
					'name' => $name,
					'id' => 'sidebar-'.$i,
					'description' => $name,
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget' => '</section>',
					'before_title' => '<div class="widgettitle">',
					'after_title' => '</div>',
				) );

			$i++;
		}
		foreach ( $this->footer_sidebar_names as $name ) {
			register_sidebar( array(
					'name' =>  $name,
					'id' => 'sidebar-'.$i,
					'description' => $name,
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget' => '</section>',
					'before_title' => '<div class="widgettitle">',
					'after_title' => '</div>',
				) );
			$i++;
		}

		$i++;

		$custom_sidebars = get_option( 'pacz_settings' );
		$custom_sidebars_array = isset($custom_sidebars['custom-sidebar']) ? $custom_sidebars['custom-sidebar'] : null;
		if ( $custom_sidebars_array != null ) {
			foreach ( $custom_sidebars_array as $key => $value ) {
				register_sidebar( array(
						'name' =>  $value,
						'id' => 'sidebar-'.$i,
						'description' => $value,
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget' => '</section>',
						'before_title' => '<div class="widgettitle">',
						'after_title' => '</div>',
					) );
				$i++;
			}
		}
	}

	function get_sidebar( $post_id = null ) {
		global $post, $post_id;
		if ( is_active_sidebar("sidebar-6") && is_archive() ) {
			$sidebar = $this->sidebar_names['archive'];
		}elseif (is_home()) {
			$sidebar = $this->sidebar_names['blog'];
		}elseif (is_active_sidebar("sidebar-4") && is_search() ) {
			$sidebar = $this->sidebar_names["search"];
		}elseif (is_active_sidebar("404") && is_404() ) {
			$sidebar = $this->sidebar_names["404"];
		}elseif (is_active_sidebar("sidebar-3") && is_singular( 'post' ) ) {
			$sidebar = $this->sidebar_names['single_post'];
		}elseif (is_active_sidebar($this->get_sidebar_id('alsp_listing_single')) && is_page() && (has_shortcode($post->post_content, 'alsp-listing') ||  (class_exists('DirectoryPress') && directorypress_is_listing_page()))) {
			$sidebar = $this->sidebar_names['alsp_listing_single'];
		}elseif (is_active_sidebar("sidebar-1") && is_page() && !is_home() ) {
			$sidebar = $this->sidebar_names['page'];
		}elseif ( function_exists('is_woocommerce') && is_active_sidebar("sidebar-7") && is_woocommerce() && is_archive()) {
			$sidebar = $this->sidebar_names["woocommerce"];
		}elseif ( function_exists('is_woocommerce') && is_single()) {
			$sidebar = $this->sidebar_names["woocommerce_single"];
		}elseif ( function_exists('is_bbpress') && is_active_sidebar("bbpress") && is_bbpress()) {
			$sidebar = $this->sidebar_names['bbpress'];
		}elseif(is_author()){
			$sidebar = $this->sidebar_names['author'];
		}
		if ( !empty( $post_id ) ) {
			$layout = get_post_meta( $post_id, '_layout', true );
			$custom = get_post_meta( $post_id, '_sidebar', true );
				
			if ( !empty( $custom ) ) {
				$sidebar = $custom;
			}
		}
		
		
		if ( isset( $sidebar ) ) {
			dynamic_sidebar( $this->resolve_sidebar( $sidebar ) );
		}
	}

	function get_footer_sidebar(){
		$post_id = global_get_post_id();
		if($post_id) {
				if($this->footer_sidebar_count == 0) {
					$single_area = get_post_meta($post_id, '_widget_first_col', true);
					if(!empty($single_area)) {
						dynamic_sidebar($single_area);
					} else {
						dynamic_sidebar($this->footer_sidebar_names[$this->footer_sidebar_count]);
					}
				}
				if($this->footer_sidebar_count == 1) {
					$single_area = get_post_meta($post_id, '_widget_second_col', true);
					if(!empty($single_area)) {
						dynamic_sidebar($single_area);
					} else {
						dynamic_sidebar($this->footer_sidebar_names[$this->footer_sidebar_count]);
					}
				}
				if($this->footer_sidebar_count == 2) {
					$single_area = get_post_meta($post_id, '_widget_third_col', true);
					if(!empty($single_area)) {
						dynamic_sidebar($single_area);
					} else {
						dynamic_sidebar($this->footer_sidebar_names[$this->footer_sidebar_count]);
					}
				}
				if($this->footer_sidebar_count == 3) {
					$single_area = get_post_meta($post_id, '_widget_fourth_col', true);
					if(!empty($single_area)) {
						dynamic_sidebar($single_area);
					} else {
						dynamic_sidebar($this->footer_sidebar_names[$this->footer_sidebar_count]);
					}
				}
				if($this->footer_sidebar_count == 4) {
					$single_area = get_post_meta($post_id, '_widget_fifth_col', true);
					if(!empty($single_area)) {
						dynamic_sidebar($single_area);
					} else {
						dynamic_sidebar($this->footer_sidebar_names[$this->footer_sidebar_count]);
					}
				}
				if($this->footer_sidebar_count == 5) {
					$single_area = get_post_meta($post_id, '_widget_sixth_col', true);
					if(!empty($single_area)) {
						dynamic_sidebar($single_area);
					} else {
						dynamic_sidebar($this->footer_sidebar_names[$this->footer_sidebar_count]);
					}
				}
		} else {
			dynamic_sidebar($this->footer_sidebar_names[$this->footer_sidebar_count]);
		}
		$single_area = '';
		$this->footer_sidebar_count++;
	}

}
global $_classiadsproSidebarGenerator;
$_classiadsproSidebarGenerator = new classiadsproSidebarGenerator;

add_action( 'widgets_init', array( $_classiadsproSidebarGenerator, 'register_sidebar' ) );

function pacz_sidebar_generator( $function ) {
	global $_classiadsproSidebarGenerator;
	$args = array_slice( func_get_args(), 1 );
	return call_user_func_array( array( &$_classiadsproSidebarGenerator, $function ), $args );
}
