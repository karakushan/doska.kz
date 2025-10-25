<?php
function directorypress_register_post_type() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$args = array(
			'labels' => array(
				'name' => esc_html__('Listings', 'DIRECTORYPRESS'),
				'singular_name' => esc_html__('Listing', 'DIRECTORYPRESS'),
				'add_new' => esc_html__('Create new listing', 'DIRECTORYPRESS'),
				'add_new_item' => esc_html__('Create new listing', 'DIRECTORYPRESS'),
				'edit_item' => esc_html__('Edit listing', 'DIRECTORYPRESS'),
				'new_item' => esc_html__('New listing', 'DIRECTORYPRESS'),
				'view_item' => esc_html__('View listing', 'DIRECTORYPRESS'),
				'search_items' => esc_html__('Search listings', 'DIRECTORYPRESS'),
				'not_found' =>  esc_html__('No listings found', 'DIRECTORYPRESS'),
				'not_found_in_trash' => esc_html__('No listings found in trash', 'DIRECTORYPRESS')
			),
			'has_archive' => true,
			'description' => esc_html__('Listings', 'DIRECTORYPRESS'),
			'public' => true,
			'exclude_from_search' => false,
			'supports' => array('title', 'author', 'comments'),
			'menu_icon' => DIRECTORYPRESS_RESOURCES_URL . 'images/menuicon.png',
		);
		if (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_description']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_description']){
			$args['supports'][] = 'editor';
		}
		if (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_summary']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_summary']){
			$args['supports'][] = 'excerpt';
		}
		register_post_type(DIRECTORYPRESS_POST_TYPE, $args);
		
		register_taxonomy(DIRECTORYPRESS_CATEGORIES_TAX, DIRECTORYPRESS_POST_TYPE, array(
				'hierarchical' => true,
				'has_archive' => true,
				'labels' => array(
					'name' =>  esc_html__('Listing categories', 'DIRECTORYPRESS'),
					'menu_name' =>  esc_html__('Listing categories', 'DIRECTORYPRESS'),
					'singular_name' => esc_html__('Category', 'DIRECTORYPRESS'),
					'add_new_item' => esc_html__('Create category', 'DIRECTORYPRESS'),
					'new_item_name' => esc_html__('New category', 'DIRECTORYPRESS'),
					'edit_item' => esc_html__('Edit category', 'DIRECTORYPRESS'),
					'view_item' => esc_html__('View category', 'DIRECTORYPRESS'),
					'update_item' => esc_html__('Update category', 'DIRECTORYPRESS'),
					'search_items' => esc_html__('Search categories', 'DIRECTORYPRESS'),
				),
			)
		);
		register_taxonomy(DIRECTORYPRESS_LOCATIONS_TAX, DIRECTORYPRESS_POST_TYPE, array(
				'hierarchical' => true,
				'has_archive' => true,
				'labels' => array(
					'name' =>  esc_html__('Listing locations', 'DIRECTORYPRESS'),
					'menu_name' =>  esc_html__('Listing locations', 'DIRECTORYPRESS'),
					'singular_name' => esc_html__('Location', 'DIRECTORYPRESS'),
					'add_new_item' => esc_html__('Create location', 'DIRECTORYPRESS'),
					'new_item_name' => esc_html__('New location', 'DIRECTORYPRESS'),
					'edit_item' => esc_html__('Edit location', 'DIRECTORYPRESS'),
					'view_item' => esc_html__('View location', 'DIRECTORYPRESS'),
					'update_item' => esc_html__('Update location', 'DIRECTORYPRESS'),
					'search_items' => esc_html__('Search locations', 'DIRECTORYPRESS'),
					
				),
			)
		);
		register_taxonomy(DIRECTORYPRESS_TAGS_TAX, DIRECTORYPRESS_POST_TYPE, array(
				'hierarchical' => false,
				'labels' => array(
					'name' =>  esc_html__('Listing tags', 'DIRECTORYPRESS'),
					'menu_name' =>  esc_html__('Listing tags', 'DIRECTORYPRESS'),
					'singular_name' => esc_html__('Tag', 'DIRECTORYPRESS'),
					'add_new_item' => esc_html__('Create tag', 'DIRECTORYPRESS'),
					'new_item_name' => esc_html__('New tag', 'DIRECTORYPRESS'),
					'edit_item' => esc_html__('Edit tag', 'DIRECTORYPRESS'),
					'view_item' => esc_html__('View tag', 'DIRECTORYPRESS'),
					'update_item' => esc_html__('Update tag', 'DIRECTORYPRESS'),
					'search_items' => esc_html__('Search tags', 'DIRECTORYPRESS'),
				),
			)
		);
}


