<?php

/**
 * @since      1.0.0
 * @package    Classiadspro_Core
 * @subpackage Classiadspro_Core/includes
 * @author     Designinvento <team@designinvento.net>
 */
 
function register_clients_post_type(){
	register_post_type('clients', array(
		'labels' => array(
			'name' => __('Clients','pacz'),
			'singular_name' => __('Client','pacz'),
			'add_new' => __('Add New Client','pacz'),
			'add_new_item' => __('Add New Client', 'pacz' ),
			'edit_item' => __('Edit Client','pacz'),
			'new_item' => __('New Client','pacz'),
			'view_item' => __('View Client','pacz'),
			'search_items' => __('Search Clients','pacz'),
			'not_found' =>  __('No Clients found','pacz'),
			'not_found_in_trash' => __('No Clients found in Trash','pacz'),
			'parent_item_colon' => '',
			
		),
		'singular_label' => 'clients',
		'public' => true,
		'exclude_from_search' => true,
		'show_ui' => true,
		'menu_icon'=> 'dashicons-businessman',
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => false,
		'menu_position' => 100,
		'query_var' => false,
		'show_in_nav_menus' => false,
		'supports' => array('title', 'thumbnail', 'page-attributes', 'revisions')
	));
}
add_action('init','register_clients_post_type');

function clients_context_fixer() {
	if ( get_query_var( 'post_type' ) == 'clients' ) {
		global $wp_query;
		$wp_query->is_home = false;
		$wp_query->is_404 = true;
		$wp_query->is_single = false;
		$wp_query->is_singular = false;
	}
}
add_action( 'template_redirect', 'clients_context_fixer' );


/*-----------------------------------------------------------------------------------*/
/* Manage Client's columns */
/*-----------------------------------------------------------------------------------*/

function edit_clients_columns($clients_columns) {
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		'title' => __('Client Name', 'pacz'),
		"thumbnail" => __('Thumbnail', 'pacz' ),
	);

	return $columns;
}
add_filter('manage_edit-clients_columns', 'edit_clients_columns');

function manage_clients_columns($column) {
	global $post;
	
	if ($post->post_type == "clients") {
		switch($column){
			
			case 'thumbnail':
				echo the_post_thumbnail('thumbnail');
				break;
		}
	}
}
add_action('manage_posts_custom_column', 'manage_clients_columns', 10, 2);