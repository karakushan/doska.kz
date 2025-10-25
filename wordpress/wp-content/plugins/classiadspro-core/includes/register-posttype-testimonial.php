<?php

/**
 * @since      1.0.0
 * @package    Classiadspro_Core
 * @subpackage Classiadspro_Core/includes
 * @author     Designinvento <team@designinvento.net>
 */

function register_testimonials_post_type() {
	register_post_type( 'testimonial', array(
			'labels' => array(
				'name' => __( 'Testimonials', 'pacz_framework' ),
				'singular_name' => __( 'Testimonial', 'pacz_framework' ),
				'add_new' => __( 'Add New Testimonial', 'pacz_framework' ),
				'add_new_item' => __( 'Add New Testimonial', 'pacz_framework'),
				'edit_item' => __( 'Edit Testimonial', 'pacz_framework' ),
				'new_item' => __( 'New Testimonial', 'pacz_framework' ),
				'view_item' => __( 'View Testimonials', 'pacz_framework' ),
				'search_items' => __( 'Search Testimonials', 'pacz_framework' ),
				'not_found' =>  __( 'No Testimonials found', 'pacz_framework' ),
				'not_found_in_trash' => __( 'No Testimonials found in Trash', 'pacz_framework' ),
				'parent_item_colon' => '',

			),
			'singular_label' => 'Testimonials',
			'public' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'menu_icon'=> 'dashicons-awards',
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => false,
			'menu_position' => 100,
			'query_var' => false,
			'show_in_nav_menus' => false,
			'supports' => array('title', 'thumbnail', 'page-attributes', 'revisions')
		) );
}
add_action( 'init', 'register_testimonials_post_type' );

function edit_testimonial_columns( $testimonial_columns ) {
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		'title' => __( 'Testimonial Name', 'pacz_framework' ),
		"quote_author" => __( 'Author', 'pacz_framework' ),
		"desc" => __( 'Description', 'pacz_framework' ),
		"thumbnail" => __( 'Thumbnail', 'pacz_framework' ),
	);

	return $columns;
}
add_filter( 'manage_edit-testimonial_columns', 'edit_testimonial_columns' );

function manage_testimonials_columns( $column ) {
	global $post;

	if ( $post->post_type == "testimonial" ) {
		switch ( $column ) {
		case "quote_author":
			echo get_post_meta( $post->ID, '_author', true );
			break;
		case "desc":
			echo get_post_meta( $post->ID, '_desc', true );
			break;

		case 'thumbnail':
			echo the_post_thumbnail( 'thumbnail' );
			break;
		}
	}
}
add_action( 'manage_posts_custom_column', 'manage_testimonials_columns', 10, 2 );

function testimonials_context_fixer() {
	if ( get_query_var( 'post_type' ) == 'testimonial' ) {
		global $wp_query;
		$wp_query->is_home = false;
		$wp_query->is_404 = true;
		$wp_query->is_single = false;
		$wp_query->is_singular = false;
	}
}
add_action( 'template_redirect', 'testimonials_context_fixer' );