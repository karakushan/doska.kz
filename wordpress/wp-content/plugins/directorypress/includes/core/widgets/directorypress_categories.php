<?php

global $directorypress_categories_widget_params;
$directorypress_categories_widget_params = array(
		array(
				'type' => 'directorytype',
				'param_name' => 'directorytype',
				'heading' => esc_html__("Categories links will redirect to selected directorytype", "DIRECTORYPRESS"),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'style',
				'value' => array(esc_html__('Style 1', 'DIRECTORYPRESS') => '1', esc_html__('Style 2', 'DIRECTORYPRESS') => '2'),
				'heading' => esc_html__('Style', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'parent',
				'heading' => esc_html__('Parent category', 'DIRECTORYPRESS'),
				'description' => esc_html__('ID of parent category (default 0 – this will build categories tree starting from the parent as root).', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'depth',
				'value' => array('1', '2'),
				'heading' => esc_html__('Categories nested level', 'DIRECTORYPRESS'),
				'description' => esc_html__('The max depth of categories tree. When set to 1 – only root categories will be listed.', 'DIRECTORYPRESS'),
			),
		array(
				'type' => 'textfield',
				'param_name' => 'subcats',
				'heading' => esc_html__('Show subcategories items number', 'DIRECTORYPRESS'),
				'description' => esc_html__('This is the number of subcategories those will be displayed in the table, when category item includes more than this number "View all subcategories ->" link appears at the bottom.', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'depth', 'value' => '2'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'count',
				'value' => array(esc_html__('Yes', 'DIRECTORYPRESS') => '1', esc_html__('No', 'DIRECTORYPRESS') => '0'),
				'heading' => esc_html__('Show category listings count?', 'DIRECTORYPRESS'),
				'description' => esc_html__('Whether to show number of listings assigned with current category.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'hide_empty',
				'value' => array(esc_html__('No', 'DIRECTORYPRESS') => '0', esc_html__('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => esc_html__('Hide empty categories?', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'icons',
				'value' => array(esc_html__('Yes', 'DIRECTORYPRESS') => '1', esc_html__('No', 'DIRECTORYPRESS') => '0'),
				'heading' => esc_html__('Show categories icons', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'cat_icon_type',
				'value' => array(esc_html__('Font Icons', 'DIRECTORYPRESS') => '1', esc_html__('Image icons', 'DIRECTORYPRESS') => '2', esc_html__('Svg icons', 'DIRECTORYPRESS') => '3'),
				'heading' => esc_html__('Show categories icons', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'categoriesfield',
				'param_name' => 'categories',
				'heading' => esc_html__('Categories', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'visibility',
				'heading' => esc_html__("Show only on directorytype pages", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => esc_html__("Otherwise it will load plugin's files on all pages.", "DIRECTORYPRESS"),
		),
);

class directorypress_categories_widget extends directorypress_widget {

	public function __construct() {
		global $directorypress_object, $directorypress_categories_widget_params;

		parent::__construct(
				'directorypress_categories_widget',
				__('DIRECTORYPRESS - Categories', 'DIRECTORYPRESS')
		);

		$this->convertParams($directorypress_categories_widget_params);
	}
	
	public function render_widget($instance, $args) {
		global $directorypress_object;
		
		// when visibility enabled - show only on directorytype pages
		if (empty($instance['visibility']) || !empty($directorypress_object->public_handlers)) {
			$instance['menu'] = 0;
			$instance['columns'] = 1;
			$instance['is_widget'] = 1;
			
			$title = apply_filters('widget_title', $instance['title']);
			
			echo wp_kses_post($args['before_widget']);
				if (!empty($title)) {
					if ($instance['style'] == 1){
						echo '<div class="directorypress_category_widget_inner">'. wp_kses_post($args['before_title'] . $title . $args['after_title']) .'</div>';
					}else{
						echo '<div class="directorypress_category_widget_inner style2">'. wp_kses_post($args['before_title'] . $title . $args['after_title']) .'</div>';
					}
				}
				echo '<div class="directorypress-widget directorypress-categories-widget">';
					if ($instance['style'] == 1){
						echo '<div class="directorypress_category_widget_inner">';
					}else{
						echo '<div class="directorypress_category_widget_inner style2">';
					}
						$directorypress_handler = new directorypress_categories_handler();
						$directorypress_handler->init($instance);
						echo wp_kses_post($directorypress_handler->display());
					echo '</div>';
				echo '</div>';
			echo wp_kses_post($args['after_widget']);
				
		}
	}
}
?>