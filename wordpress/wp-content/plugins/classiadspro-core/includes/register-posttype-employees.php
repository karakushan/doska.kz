<?php

/**
 * @since      1.0.0
 * @package    Classiadspro_Core
 * @subpackage Classiadspro_Core/includes
 * @author     Designinvento <team@designinvento.net>
 */
 

function register_employees_post_type(){
	register_post_type('employees', array(
		'labels' => array(
			'name' => __('Employees','pacz'),
			'singular_name' => __('Team Member','pacz'),
			'add_new' => __('Add New Member','pacz'),
			'add_new_item' => __('Add New Team Member', 'pacz' ),
			'edit_item' => __('Edit Team Member','pacz'),
			'new_item' => __('New Team Member','pacz'),
			'view_item' => __('View Team Member','pacz'),
			'search_items' => __('Search Team Members','pacz'),
			'not_found' =>  __('No Team Member found','pacz'),
			'not_found_in_trash' => __('No Team Members found in Trash','pacz'),
			'parent_item_colon' => '',
			
		),
		'singular_label' => 'employees',
		'public' => true,
		'exclude_from_search' => true,
		'show_ui' => true,
		'menu_icon'=> 'dashicons-groups',
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => false,
		'menu_position' => 100,
		'query_var' => false,
		'show_in_nav_menus' => false,
		'supports' => array('title', 'thumbnail', 'page-attributes', 'revisions')
	));
}
add_action('init','register_employees_post_type');

function edit_employees_columns($employee_columns) {
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		'title' => __('Employee Name', 'pacz'),
		"position" => __('Position', 'pacz' ),
		//"desc" => __('Description', 'pacz' ),
		"thumbnail" => __('Thumbnail', 'pacz' ),
	);

	return $columns;
}
add_filter('manage_edit-employees_columns', 'edit_employees_columns');

function manage_employees_columns($column) {
	global $post;
	
	if ($post->post_type == "employees") {
		switch($column){
			case "position":
				echo get_post_meta($post->ID, '_position', true);
				break;
			//case "desc":
				//echo get_post_meta($post->ID, '_desc', true);
				//break;
			
			case 'thumbnail':
				echo the_post_thumbnail('thumbnail');
				break;
		}
	}
}
add_action('manage_posts_custom_column', 'manage_employees_columns', 10, 2);

function employees_context_fixer() {
	if ( get_query_var( 'post_type' ) == 'employees' ) {
		global $wp_query;
		$wp_query->is_home = false;
		$wp_query->is_404 = true;
		$wp_query->is_single = false;
		$wp_query->is_singular = false;
	}
}
add_action( 'template_redirect', 'employees_context_fixer' );