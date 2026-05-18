<?php

global $directorypress_search_widget_params;
$directorypress_search_widget_params = array(
		array(
				'type' => 'dropdown',
				'param_name' => 'custom_home',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Is it on custom home page?', 'DIRECTORYPRESS'),
				//'description' => esc_html__('When set to Yes - the widget will follow some parameters from Directory Settings and not those listed here.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'directorytype',
				'param_name' => 'directorytype',
				'heading' => esc_html__("Search by directorytype", "DIRECTORYPRESS"),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'uid',
				'heading' => esc_html__("uID", "DIRECTORYPRESS"),
				'description' => esc_html__("Enter unique string to connect search form with another elements on the page.", "DIRECTORYPRESS"),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'columns',
				'value' => array('2', '1'),
				'std' => '2',
				'heading' => esc_html__('Number of columns to arrange search fields', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'advanced_open',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Advanced search panel always open', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'has_sticky_scroll',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Make search form to be has_sticky on scroll', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'has_sticky_scroll_toppadding',
				'value' => 0,
				'heading' => esc_html__('Sticky scroll top padding', 'DIRECTORYPRESS'),
				'description' => esc_html__('Sticky scroll top padding in pixels.', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'has_sticky_scroll', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_keywords_search',
				'value' => array(esc_html__('Yes', 'DIRECTORYPRESS') => '1', esc_html__('No', 'DIRECTORYPRESS') => '0'),
				'heading' => esc_html__('Show keywords search?', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'keywords_ajax_search',
				'value' => array(esc_html__('Yes', 'DIRECTORYPRESS') => '1', esc_html__('No', 'DIRECTORYPRESS') => '0'),
				'heading' => esc_html__('Enable listings autosuggestions by keywords', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'keywords_search_examples',
				'heading' => esc_html__('Keywords examples', 'DIRECTORYPRESS'),
				'description' => esc_html__('Comma-separated list of suggestions to try to search.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'what_search',
				'heading' => esc_html__('Default keywords', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_categories_search',
				'value' => array(esc_html__('Yes', 'DIRECTORYPRESS') => '1', esc_html__('No', 'DIRECTORYPRESS') => '0'),
				'heading' => esc_html__('Show categories search?', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'categories_search_depth',
				'value' => array('1', '2', '3'),
				'std' => '2',
				'heading' => esc_html__('Categories search depth package', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'categoryfield',
				'param_name' => 'category',
				'heading' => esc_html__('Select certain category', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'categoriesfield',
				'param_name' => 'exact_categories',
				'heading' => esc_html__('List of categories', 'DIRECTORYPRESS'),
				'description' => esc_html__('Comma separated string of categories slugs or IDs. Possible to display exact categories.', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_locations_search',
				'value' => array(esc_html__('Yes', 'DIRECTORYPRESS') => '1', esc_html__('No', 'DIRECTORYPRESS') => '0'),
				'heading' => esc_html__('Show locations search?', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'locations_search_depth',
				'value' => array('1', '2', '3'),
				'std' => '2',
				'heading' => esc_html__('Locations search depth package', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'locationfield',
				'param_name' => 'location',
				'heading' => esc_html__('Select certain location', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'locationsfield',
				'param_name' => 'exact_locations',
				'heading' => esc_html__('List of locations', 'DIRECTORYPRESS'),
				'description' => esc_html__('Comma separated string of locations slugs or IDs. Possible to display exact locations.', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_address_search',
				'value' => array(esc_html__('Yes', 'DIRECTORYPRESS') => '1', esc_html__('No', 'DIRECTORYPRESS') => '0'),
				'heading' => esc_html__('Show address search?', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'address',
				'heading' => esc_html__('Default address', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_radius_search',
				'value' => array(esc_html__('Yes', 'DIRECTORYPRESS') => '1', esc_html__('No', 'DIRECTORYPRESS') => '0'),
				'heading' => esc_html__('Show locations radius search?', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'radius',
				'heading' => esc_html__('Default radius search', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'contentfields',
				'param_name' => 'search_fields',
				'heading' => esc_html__('Select certain content fields', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'contentfields',
				'param_name' => 'search_fields_advanced',
				'heading' => esc_html__('Select certain content fields in advanced section', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'colorpicker',
				'param_name' => 'search_bg_color',
				'heading' => esc_html__("Background color", "DIRECTORYPRESS"),
				'value' => get_option('directorypress_search_bg_color'),
		),
		array(
				'type' => 'colorpicker',
				'param_name' => 'search_text_color',
				'heading' => esc_html__("Text color", "DIRECTORYPRESS"),
				'value' => get_option('directorypress_search_text_color'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'search_bg_opacity',
				'heading' => esc_html__("Opacity of search form background, in %", "DIRECTORYPRESS"),
				'value' => 100,
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'search_overlay',
				'value' => array(esc_html__('Yes', 'DIRECTORYPRESS') => '1', esc_html__('No', 'DIRECTORYPRESS') => '0'),
				'heading' => esc_html__('Show background overlay', 'DIRECTORYPRESS'),
				'std' => get_option('directorypress_search_overlay')
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'hide_search_button',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Hide search button', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'on_row_search_button',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Search button on one line with fields', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'scroll_to',
				'value' => array(esc_html__('No scroll', 'DIRECTORYPRESS') => '', esc_html__('Listings', 'DIRECTORYPRESS') => 'listings', esc_html__('Map', 'DIRECTORYPRESS') => 'map'),
				'heading' => esc_html__('Scroll to listings, map or do not scroll after search button was pressed', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'search_visibility',
				'heading' => esc_html__("Show only when there is no any other search form on page", "DIRECTORYPRESS"),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'visibility',
				'heading' => esc_html__("Show only on directorytype pages", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => esc_html__("Otherwise it will load plugin's files on all pages.", "DIRECTORYPRESS"),
		),
);

class directorypress_search_widget extends directorypress_widget {

	public function __construct() {
		global $directorypress_object, $directorypress_search_widget_params;

		parent::__construct(
				'directorypress_search_widget',
				__('Directory - Search', 'DIRECTORYPRESS'),
				__( 'Search Form', 'DIRECTORYPRESS')
		);

		foreach ($directorypress_object->search_fields->filter_fields_array AS $filter_field) {
			if (method_exists($filter_field, 'gat_vc_params') && ($field_params = $filter_field->gat_vc_params())) {
				$directorypress_search_widget_params = array_merge($directorypress_search_widget_params, $field_params);
			}
		}

		$this->convertParams($directorypress_search_widget_params);
	}
	
	public function render_widget($instance, $args) {
		global $directorypress_object;
		
		// when visibility enabled - show only on directorytype pages
		if (empty($instance['visibility']) || !empty($directorypress_object->public_handlers)) {
			// when search_visibility enabled - show only when main search form wasn't displayed
			if (!empty($instance['search_visibility']) && !empty($directorypress_object->public_handlers)) {
				foreach ($directorypress_object->public_handlers AS $shortcode_handlers) {
					foreach ($shortcode_handlers AS $directorypress_handler) {
						if (is_object($directorypress_handler) && $directorypress_handler->search_form) {
							return false;
						}
					}
				}
			}
				
			$title = apply_filters('widget_title', $instance['title']);
				
			// it is auto selection - take current directorytype
			if ($instance['directorytype'] == 0) {
				// probably we are on single listing page - it could be found only after frontend handlers were loaded, so we have to repeat setting
				$directorypress_object->setup_current_page_directorytype();
		
				$instance['directorytype'] = $directorypress_object->current_directorytype->id;
			}
			$instance['form_layout'] = 'vertical';
			$instance['gap_in_fields'] = 0;
			echo wp_kses_post($args['before_widget']);
			if (!empty($title)) {
				echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
			}
			echo '<div class="directorypress-content-wrap directorypress-widget directorypress-search-widget">';
			$directorypress_handler = new directorypress_search_handler();
			$directorypress_handler->init($instance);
			echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
			echo wp_kses_post($args['after_widget']);
		}
	}
}
?>