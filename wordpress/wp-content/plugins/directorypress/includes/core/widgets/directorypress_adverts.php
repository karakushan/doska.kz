<?php

global $directorypress_listings_widget_params;
$directorypress_listings_widget_params = array(
		array(
				'type' => 'checkbox',
				'param_name' => 'is_footer',
				'heading' => esc_html__("Check if its Footer Widget area", "DIRECTORYPRESS"),
				'value' => 0,
				'description' => esc_html__("Otherwise Listing style will be disturded", "DIRECTORYPRESS"),
		),
		array(
				'type' => 'directorytypes',
				'param_name' => 'directorytypes',
				'heading' => esc_html__("Listings of these directorytypes", "DIRECTORYPRESS"),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'listing_post_style',
				'value' => apply_filters("directorypress_listing_widget_grid_styles", "directorypress_listing_widget_grid_styles_function"),
				'heading' => esc_html__('Listing Style', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'is_footer', 'value' => 0),
		),
		//apply_filters("directorypress_listing_widget_settings_filter", "directorypress_listing_widget_settings"),
		array(
				'type' => 'textfield',
				'param_name' => 'listings_grid_columns',
				'value' => 3,
				'heading' => esc_html__('Listing Grid Columns', 'DIRECTORYPRESS'),
				'description' => esc_html__('works only when widget is in footer.', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'is_footer', 'value' => '1'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'number_of_listings',
				'value' => 6,
				'heading' => esc_html__('Number of listings', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'width',
				'value' => 370,
				'heading' => esc_html__('Listing Thumbnail Width', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'height',
				'value' => 250,
				'heading' => esc_html__('Listing Thumbnail Height', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'only_has_sticky_has_featured',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Show only sticky and featured listings?', 'DIRECTORYPRESS'),
				'std' => '0',
				'description' => esc_html__('Whether to show only sticky and featured listings. or show all', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'ordering',
				'param_name' => 'order_by',
				'heading' => esc_html__('Order by', 'DIRECTORYPRESS'),
				'description' => esc_html__('Order listings by any of these parameter.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'order',
				'value' => array(esc_html__('Ascending', 'DIRECTORYPRESS') => 'ASC', esc_html__('Descending', 'DIRECTORYPRESS') => 'DESC'),
				'description' => esc_html__('Direction of sorting.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'hide_content',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Hide content fields data', 'DIRECTORYPRESS'),
				'std' => '1',
		),
		array(
				'type' => 'textfield',
				'param_name' => 'address',
				'heading' => esc_html__('Address', 'DIRECTORYPRESS'),
				'description' => esc_html__('Display listings near this address, recommended to set default radius', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'radius',
				'heading' => esc_html__('Radius', 'DIRECTORYPRESS'),
				'description' => esc_html__('Display listings near provided address within this radius in miles or kilometers.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'author',
				'heading' => esc_html__('Author', 'DIRECTORYPRESS'),
				'description' => esc_html__('Enter exact ID of author or word "related" to get assigned listings of current author (works only on listing page or author page)', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'related_categories',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Use related categories.', 'DIRECTORYPRESS'),
				'description' => esc_html__('Parameter works only on listings and categories pages.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'categoriesfield',
				'param_name' => 'categories',
				//'value' => 0,
				'heading' => esc_html__('Select certain categories', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'related_locations',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Use related locations.', 'DIRECTORYPRESS'),
				'description' => esc_html__('Parameter works only on listings and locations pages.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'locationsfield',
				'param_name' => 'locations',
				//'value' => 0,
				'heading' => esc_html__('Select certain locations', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'related_tags',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Use related tags.', 'DIRECTORYPRESS'),
				'description' => esc_html__('Parameter works only on listings and tags pages.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'include_categories_children',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Include children of selected categories and locations', 'DIRECTORYPRESS'),
				'description' => esc_html__('When enabled - any subcategories or sublocations will be included as well. Related categories and locations also affected.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'package',
				'param_name' => 'packages',
				'heading' => esc_html__('Listings packages', 'DIRECTORYPRESS'),
				'description' => esc_html__('Categories may be dependent from listings packages.', 'DIRECTORYPRESS'),
		),
		/* array(
				'type' => 'textfield',
				'param_name' => 'post__in',
				'heading' => esc_html__('Exact listings', 'DIRECTORYPRESS'),
				'description' => esc_html__('Comma separated string of listings IDs. Possible to display exact listings.', 'DIRECTORYPRESS'),
		), */
		array(
				'type' => 'dropdown',
				'param_name' => 'is_slider_view',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Turn On/Off Slider', 'DIRECTORYPRESS'),
				//'dependency' => array('element' => 'is_footer', 'value' => '0'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'autoplay',
				'heading' => esc_html__("Autoplay ", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => esc_html__("Turn autoplay on or off", "DIRECTORYPRESS"),
				'dependency' => array('element' => 'is_slider_view', 'value' => '1'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'loop',
				'heading' => esc_html__("Slider Loop ", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => esc_html__("Turn loop on or off", "DIRECTORYPRESS"),
				'dependency' => array('element' => 'is_slider_view', 'value' => '1'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'owl_nav',
				'heading' => esc_html__("Slider Navigation ", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => esc_html__("Turn Navigation on or off", "DIRECTORYPRESS"),
				'dependency' => array('element' => 'is_slider_view', 'value' => '1'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'delay',
				'heading' => esc_html__("Slider Animation Delay ", "DIRECTORYPRESS"),
				'value' => 1000,
				'dependency' => array('element' => 'is_slider_view', 'value' => '1'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'autoplay_speed',
				'heading' => esc_html__("Slider autoplay speed ", "DIRECTORYPRESS"),
				'value' => 1000,
				'dependency' => array('element' => 'is_slider_view', 'value' => '1'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'visibility',
				'heading' => esc_html__("Show only on directorytype pages", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => esc_html__("Otherwise it will load plugin's files on all pages.", "DIRECTORYPRESS"),
		),
);

class directorypress_listings_widget extends directorypress_widget {

	public function __construct() {
		global $directorypress_object, $directorypress_listings_widget_params;

		parent::__construct(
				'directorypress_listings_widget', // name for backward compatibility
				__('DIRECTORYPRESS - Listings', 'DIRECTORYPRESS')
		);

		//foreach ($directorypress_object->search_fields->filter_fields_array AS $filter_field) {
			//if (method_exists($filter_field, 'gat_vc_params') && ($field_params = $filter_field->gat_vc_params())) {
				//$directorypress_listings_widget_params = array_merge($directorypress_listings_widget_params, $field_params);
			//}
		//}
		
		$this->convertParams($directorypress_listings_widget_params);
	}
	
	public function render_widget($instance, $args) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS; 
		
		
			$instance['hide_paginator'] = 1;
			$instance['perpage'] = $instance['number_of_listings'];
			$instance['has_sticky_has_featured'] = $instance['only_has_sticky_has_featured'];
			$instance['hide_count'] = 1;
			$instance['hide_order'] = 1;
			$instance['show_views_switcher'] = 0;
			$instance['listings_view_type'] = 'grid';
			$instance['include_get_params'] = 0;
			$instance['listing_image_width'] = 	(isset($instance['width']) && !empty($instance['width']))? $instance['width']:'';
			$instance['listing_image_height'] = (isset($instance['height']) && !empty($instance['height']))? $instance['height']:'';
			$instance['desktop_items'] = 1;
			$instance['tab_landscape_items'] = 1;
			$instance['tab_items'] = 1;
			$instance['gutter'] = 0 ; //cz custom
			$instance['masonry_layout'] = 0;
			$instance['2col_responsive'] = 0;
			$instance['is_widget'] = 1;
			$in_footer = (isset($instance['is_footer']))? $instance['is_footer']: 0;
			if($in_footer){
				$instance['listing_post_style'] = 'footer_widget' ;
				$instance['listings_view_grid_columns'] = $instance['listings_grid_columns'] ;
				$instance['grid_padding'] = 3; //cz custom
				$instance['scroll'] = 0;
			}else{
				$instance['listing_post_style'] = (isset($instance['listing_post_style']) && !empty($instance['listing_post_style']))? $instance['listing_post_style']: 16;	
				$instance['listings_view_grid_columns'] = 1;
				$instance['scroll'] = (isset($instance['is_slider_view']) && !empty($instance['is_slider_view']))? $instance['is_slider_view']: 0;
				$instance['grid_padding'] = 0; //cz custom
			}
			
		// when visibility enabled - show only on directorytype pages
		if (empty($instance['visibility']) || !empty($directorypress_object->public_handlers)) {
			
			$title = apply_filters('widget_title', $instance['title']);
	
			echo wp_kses_post($args['before_widget']);
			if (!empty($title)) {
				echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
			}
			echo '<div class=" directorypress-widget directorypress_recent_listings_widget">';
					$directorypress_handler = new directorypress_listings_handler();
					$directorypress_handler->init($instance);
					echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
			echo wp_kses_post($args['after_widget']);
		}
	}
}
?>