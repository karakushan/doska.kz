<?php
/**
 * Elementor test Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
use Elementor\Plugin;
class DirectoryPress_Elementor_Category_Widget extends \Elementor\Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
		$this->scripts();
	}
	public function scripts() {
		//if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			//wp_enqueue_style('directorypress_category');
		//}
	}
	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'categories';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Categories', 'DIRECTORYPRESS' );
	}


	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fas fa-folder';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'directorypress' ];
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		//$ordering = directorypress_sorting_options();
		$directories = directorypress_directorytypes_array_options();
		$categories = directorypress_categories_array_options();
		//$locations = directorypress_locations_array_options();
		$packages = directorypress_packages_array_options();
		
		// Setting Section
		$this->start_controls_section(
			'setting_section',
			[
				'label' => esc_html__( 'Setting', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'cat_style',
			[
				'label' => esc_html__('category styles', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => apply_filters("directorypress_categories_styles" , "directorypress_categories_styles_function"),
				'default' => '1',
			]
		);
		$this->add_control(
			'parent',
			[
				'label' => esc_html__('Parent category', 'DIRECTORYPRESS'),
				'description' => esc_html__('ID of parent category (default 0 – this will build whole categories tree starting from the root).', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Inset Id', 'DIRECTORYPRESS' ),
			]
		);
		$this->add_control(
			'depth',
			[
				'label' => esc_html__('Categories sub level', 'DIRECTORYPRESS'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'description' => esc_html__('The max depth of categories tree. When set to 1 – only root categories will be listed.', 'DIRECTORYPRESS'),
				'multiple' => false,
				'options' => [
					'1' => esc_html__( 'Level 1', 'DIRECTORYPRESS' ),
					'2' => esc_html__( 'Level 2', 'DIRECTORYPRESS' ),
				],
				'condition' => [
					'cat_style' => apply_filters("directorypress_categories_depth_conditions", "directorypress_categories_depth_conditions_function"),
				],
				'default' => 1,
			]
		);
		$this->add_control(
			'subcats',
			[
				'label' => esc_html__('Show subcategories items number', 'DIRECTORYPRESS'),
				'description' => esc_html__('This is the number of subcategories those will be displayed in the table, when category item includes more than this number "View all" link appears at the bottom.', 'DIRECTORYPRESS'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'condition' => [
					'depth' => [ '2' ],
				],
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 5,
			]
		);
		
		/* $this->add_control(
			'columns_set1',
			[
				'label' =>__('Categories columns number', 'DIRECTORYPRESS'),
				'description' => esc_html__('Categories list is divided by columns. some styles may not offer more then 3 or 4 columns', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => esc_html__( '1 Column', 'DIRECTORYPRESS' ),
					'2' => esc_html__( '2 Column', 'DIRECTORYPRESS' ),
					'3' => esc_html__( '3 Column', 'DIRECTORYPRESS' ),
					'4' => esc_html__( '4 Column', 'DIRECTORYPRESS' ),
					'5' => esc_html__( '5 Column', 'DIRECTORYPRESS' ),
					'6' => esc_html__( '6 Column', 'DIRECTORYPRESS' ),
					'inline' => esc_html__( 'Inline', 'DIRECTORYPRESS' ),
				],
				'default' => 4,
				//'condition' => [
					//'cat_style' => [ 'default', '3', '6', '7', '10' ],
				//],
			]
		); */
		$this->add_control(
			'columns_set2',
			[
				'label' =>__('Categories columns number', 'DIRECTORYPRESS'),
				'description' => esc_html__('Categories list is divided by columns. some styles may not offer more then 3 or 4 columns', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => esc_html__( '1 Column', 'DIRECTORYPRESS' ),
					'2' => esc_html__( '2 Column', 'DIRECTORYPRESS' ),
					'3' => esc_html__( '3 Column', 'DIRECTORYPRESS' ),
					'4' => esc_html__( '4 Column', 'DIRECTORYPRESS' ),
					'5' => esc_html__( '5 Column', 'DIRECTORYPRESS' ),
					'6' => esc_html__( '6 Column', 'DIRECTORYPRESS' ),
					'inline' => esc_html__( 'Inline', 'DIRECTORYPRESS' ),
				],
				'default' => 4,
				//'condition' => [
					//'cat_style' => [ '1', '2', '5', '8', '9', '11' ],
				//],
			]
		);
		$this->add_control(
			'cat_icon_type',
			[
				'label' => esc_html__('Select Categories icon type', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => esc_html__( 'Font Icons', 'DIRECTORYPRESS' ),
					'2' => esc_html__( 'Image Icons', 'DIRECTORYPRESS' ),
				],
				'default' => 1,
			]
		);
		$this->add_control(
			'count',
			[
				'label' =>  esc_html__('Show category listings count?', 'DIRECTORYPRESS'),
				'description' => esc_html__('Whether to show number of listings assigned with current category in brackets.', 'DIRECTORYPRESS'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => esc_html__( 'No', 'DIRECTORYPRESS' ),
					'1' => esc_html__( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 1,
			]
		);
		$this->add_control(
			'hide_empty',
			[
				'label' =>  esc_html__('Hide Empty Ctegories?', 'DIRECTORYPRESS'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => esc_html__( 'No', 'DIRECTORYPRESS' ),
					'1' => esc_html__( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->end_controls_section(); 
		
		// content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'directorytype',
			[
				'label' => esc_html__( 'Select Directory', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => $directories,
				'default' => 0,
			]
		);
		$this->add_control(
			'uid',
			[
				'label' => esc_html__( 'Unique ID', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'description' => esc_html__( 'Insert unique id if you like to connect this module to a specific module like map or search(optional)', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);
		$this->add_control(
			'categories',
			[
				'label' => esc_html__( 'Select Specific Categories', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $categories,
				'default' => 0,
			]
		);
		$this->add_control(
			'packages',
			[
				'label' => esc_html__( 'Select Packages', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $packages,
				'default' => 0,
			]
		);

		$this->end_controls_section();
		
		// Slider
		$this->start_controls_section(
			'slider_section',
			[
				'label' => esc_html__( 'Slider', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'scroll',
			[
				'label' => esc_html__( 'Turn On Slider', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => esc_html__( 'No', 'DIRECTORYPRESS' ),
					'1' => esc_html__( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
				//'condition' => [
					//'cat_style' => [ '1', '2', '4', '5', '8', '9', '11' ],
				//],
			]
		);
		$this->add_responsive_control(
			'desktop_items',
			[
				'label' => esc_html__( 'Items Per Slide', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'default' => 3,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'gutter',
			[
				'label' => esc_html__( 'Space Between Slides', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'default' => 30,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Turn On Autoplay', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => esc_html__( 'No', 'DIRECTORYPRESS' ),
					'1' => esc_html__( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Turn On Loop', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => esc_html__( 'No', 'DIRECTORYPRESS' ),
					'1' => esc_html__( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'owl_nav',
			[
				'label' => esc_html__( 'Turn On Navigation', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => esc_html__( 'No', 'DIRECTORYPRESS' ),
					'1' => esc_html__( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'scroller_nav_style',
			[
				'label' => esc_html__( 'Navigation Style', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => esc_html__( 'Style 1', 'DIRECTORYPRESS' ),
					'2' => esc_html__( 'Style 2', 'DIRECTORYPRESS' ),
				],
				'default' => 2,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'delay',
			[
				'label' => esc_html__( 'Delay', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		
		$this->end_controls_section();
		
		// Style tab and section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'item_border_title',
			[
				'label' => esc_html__( 'Box Border', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'label' => esc_html__( 'Box Border', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .directorypress-category-holder',
			]
		);
		$this->add_control(
			'item_border_title_hover',
			[
				'label' => esc_html__( 'Box Border Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'box_border_hover',
				'label' => esc_html__( 'Box Border Hover', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .directorypress-category-holder:hover',
			]
		);
		$this->add_responsive_control(
			'box_border_radius',
			[
				'label' => esc_html__( 'Box Border Radius', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-holder' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Item Space between items', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item' => 'padding-left: {{SIZE}}{{UNIT}} !important;padding-right: {{SIZE}}{{UNIT}} !important;margin-bottom: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .directorypress-categories-wrapper' => 'margin-left: -{{SIZE}}{{UNIT}} !important;margin-right: -{{SIZE}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_responsive_control(
			'item_width',
			[
				'label' => esc_html__( 'item width (inline style only)', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item.col-lg-inline' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .directorypress-category-item.col-lg-inline .directorypress-category-holder' => 'width: 100%;',
				],
			]
		);
		$this->add_responsive_control(
			'item_height',
			[
				'label' => esc_html__( 'item height (inline style only)', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item.col-lg-inline' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .directorypress-category-item.col-lg-inline .directorypress-category-holder' => 'height: 100%;',
				],
			]
		);
		$this->add_control(
			'item_title_color',
			[
				'label' => esc_html__( 'Title Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-holder .directorypress-parent-category a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'item_title_color_hover',
			[
				'label' => esc_html__( 'Title Color Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-holder:hover .directorypress-parent-category a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'item_typo_title',
			[
				'label' => esc_html__( 'Item Typography', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'item_typography',
				'label' => esc_html__( 'Typography', 'DIRECTORYPRESS' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .directorypress-category-holder .directorypress-parent-category a',
			]
		);
		$this->add_control(
			'box_shadow_title',
			[
				'label' => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .directorypress-category-holder',
			]
		);
		$this->add_control(
			'box_shadow_title_hover',
			[
				'label' => esc_html__( 'Box Shadow Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_hover',
				'selector' => '{{WRAPPER}} .directorypress-category-holder:hover',
			]
		);
		$this->add_control(
			'box_bg_title',
			[
				'label' => esc_html__( 'Box Background', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'box_background',
				'label' => esc_html__( 'Box Background', 'DIRECTORYPRESS' ),
				'description' => esc_html__( 'Category Box Background', 'DIRECTORYPRESS' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .directorypress-category-holder',
			]
		);
		$this->add_control(
			'box_bg_title_hover',
			[
				'label' => esc_html__( 'Box Background Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'box_background_hover',
				'label' => esc_html__( 'Box Background', 'DIRECTORYPRESS' ),
				'description' => esc_html__( 'Category Box Background', 'DIRECTORYPRESS' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .directorypress-category-holder:hover',
			]
		);
		$this->end_controls_section(); 
		
		// Icon Style
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__( 'Icon', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon_width',
			[
				'label' => esc_html__( 'Icon Width', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item .cat-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_height',
			[
				'label' => esc_html__( 'Icon Height', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item .cat-icon' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_margin_top_bottom',
			[
				'label' => esc_html__( 'Margin Top/Bottom', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'allowed_dimensions' => ['top', 'bottom'],
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item .cat-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_margin_left_right',
			[
				'label' => esc_html__( 'Margin Left/Right', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'allowed_dimensions' => 'horizontal',
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item .cat-icon' => 'margin-right: {{RIGHT}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Font Size', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item .cat-icon.font-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_line_height',
			[
				'label' => esc_html__( 'Line Height', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item .cat-icon.font-icon' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_border_title',
			[
				'label' => esc_html__( 'Icon Box Border', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => esc_html__( 'Icon Box Border', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .directorypress-category-item .cat-icon.font-icon',
			]
		);
		$this->add_control(
			'icon_border_title_hover',
			[
				'label' => esc_html__( 'Icon Box Border Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_border_hover',
				'label' => esc_html__( 'Icon Box Border Hover', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .directorypress-category-item:hover .cat-icon.font-icon',
			]
		);
		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Icon Border Radius', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item .cat-icon.font-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_shadow_title',
			[
				'label' => esc_html__( 'Icon Box Shadow', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_shadow',
				'selector' => '{{WRAPPER}} .directorypress-category-item .cat-icon.font-icon',
			]
		);
		$this->add_control(
			'icon_shadow_title_hover',
			[
				'label' => esc_html__( 'Icon Box Shadow Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_shadow_hover',
				'selector' => '{{WRAPPER}} .directorypress-category-item:hover .cat-icon.font-icon',
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item .cat-icon.font-icon' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Icon Color Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-category-item:hover .cat-icon.font-icon' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'icon_background_title',
			[
				'label' => esc_html__( 'Icon Box Background', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'icon_background',
				'label' => esc_html__( 'Icon Background', 'DIRECTORYPRESS' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .directorypress-category-item .cat-icon.font-icon',
			]
		);
		$this->add_control(
			'icon_background_title_hover',
			[
				'label' => esc_html__( 'Icon Box Background Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'icon_background_hover',
				'label' => esc_html__( 'Icon Background Hover', 'DIRECTORYPRESS' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .directorypress-category-item:hover .cat-icon.font-icon',
			]
		);
		$this->add_control(
			'icon_section_bottom_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->end_controls_section(); 
		// Slider Arrows
		$this->start_controls_section(
			'slider_arrow_section',
			[
				'label' => esc_html__( 'Slider Arrows', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'slider_arrow_icon_pre',
			[
				'label' => esc_html__( 'Previous Arrow Icon', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-left',
					'library' => 'solid',
				],
			]
		);
		$this->add_control(
			'slider_arrow_icon_next',
			[
				'label' => esc_html__( 'Next Arrow Icon', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-right',
					'library' => 'solid',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'slider_arrow_typography',
				'label' => esc_html__( 'Title Typography', 'DIRECTORYPRESS' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .listing-pre, {{WRAPPER}} .listing-next',
			]
		);
		$this->start_controls_tabs( 'slider_arrow_style' );

		$this->start_controls_tab(
			'slider_arrow_field_normal',
			array(
				'label' => esc_html__( 'Normal', 'DIRECTORYPRESS' ),
			)
		);

		$this->add_control(
			'slider_arrow_color',
			[
				'label' => esc_html__( 'Icon Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'color: {{VALUE}}',
					'{{WRAPPER}} .listing-next' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'slider_arrow_css_filters',
				'selector' => '{{WRAPPER}} .listing-pre, {{WRAPPER}} .listing-next',
			]
		);
		$this->add_control(
			'slider_arrow_background_color',
			array(
				'label' => esc_html__( 'Background Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .listing-pre' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .listing-next' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'slider_arrow_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .listing-pre, {{WRAPPER}} .listing-next',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'slider_arrow_field_hover',
			array(
				'label' => esc_html__( 'Hover', 'DIRECTORYPRESS' ),
			)
		);

		$this->add_control(
			'slider_arrow_color_hover',
			[
				'label' => esc_html__( 'Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .listing-pre:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .listing-next:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'slider_arrow_background_color_hover',
			array(
				'label' => esc_html__( 'Background Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .listing-pre:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .listing-next:hover' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'slider_arrow_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .listing-pre:hover, {{WRAPPER}} .listing-next:hover',
			]
		);
		
		$this->add_control(
			'slider_arrow_border_color_hover',
			array(
				'label' => esc_html__( 'Border Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
				'{{WRAPPER}} .listing-pre:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .listing-next:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'slider_arrow_border',
				'label' => esc_html__( 'Border', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .listing-pre, {{WRAPPER}} .listing-next',
			]
		);
		$this->add_control(
			'slider_arrow_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .listing-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'slider_arrow_width',
			[
				'label' => esc_html__( 'Arrow Width', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .listing-next' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'slider_arrow_height',
			[
				'label' => esc_html__( 'Arrow Height', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .listing-next' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_padding',
			[
				'label' => esc_html__( 'Padding', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .listing-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_margin',
			[
				'label' => esc_html__( 'Margin', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .listing-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_position',
			[
				'label' => esc_html__( 'Position', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'DIRECTORYPRESS' ),
					'absolute' => esc_html__( 'Absolute', 'DIRECTORYPRESS' ),
					'static' => esc_html__( 'Static', 'DIRECTORYPRESS' ),
				],
				'default' => 'absolute',
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_top',
			[
				'label' => esc_html__( 'Previous Arrow Position Top', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Position From Top', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'top: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_left',
			[
				'label' => esc_html__( 'Previous Arrow Position Left', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Position From Left', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'left: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_bottom',
			[
				'label' => esc_html__( 'Previous Arrow Position Bottom', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Position From Bottom', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'bottom: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_right',
			[
				'label' => esc_html__( 'Previous Arrow Position Right', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Position From Right', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'right: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_top',
			[
				'label' => esc_html__( 'Next Arrow Position Top', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Position From Top', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-next' => 'top: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_left',
			[
				'label' => esc_html__( 'Next Arrow Position Left', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Position From Left', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-next' => 'left: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_bottom',
			[
				'label' => esc_html__( 'Next Arrow Position Bottom', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Position From Bottom', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-next' => 'bottom: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_right',
			[
				'label' => esc_html__( 'Next Arrow Position Right', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Position From Right', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-next' => 'right: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$columns = $settings['columns_set2'];
		$desktop = $settings['desktop_items']; // default name is always desktop
		$tablet = (isset($settings['desktop_items_tablet']) && !empty($settings['desktop_items_tablet']))? $settings['desktop_items_tablet'] : 2; // _tablet is added to the tablet value
		$mobile = (isset($settings['desktop_items_mobile']) && !empty($settings['desktop_items_mobile']))? $settings['desktop_items_mobile'] : 1;  // _mobile is added to the _mobile value
		
		$instance = array(
				//'custom_home' => 0,
				'directorytype' => $settings['parent'],
				'parent' => $settings['parent'],
				'depth' => $settings['depth'],
				'columns' => $columns,
				'count' => $settings['count'],
				'hide_empty' => $settings['hide_empty'],
				'subcats' => $settings['subcats'],
				'categories' => $settings['categories'],
				'packages' => $settings['packages'],
				'cat_style' => $settings['cat_style'],
				'cat_icon_type' => $settings['cat_icon_type'],
				'scroll' => $settings['scroll'], 
				'desktop_items' => $desktop, 
				'mobile_items' => $mobile, 
				'tab_items' => $tablet,  
				'slider_arrow_position' => $settings['slider_arrow_position'],
				'slider_arrow_icon_pre' => $settings['slider_arrow_icon_pre']['value'],
				'slider_arrow_icon_next' => $settings['slider_arrow_icon_next']['value'],
				'autoplay' => $settings['autoplay'], 
				'loop' => $settings['loop'], 
				'owl_nav' => $settings['owl_nav'], 
				'delay' => $settings['delay'] , 
				'autoplay_speed' => $settings['autoplay_speed'], 
				'gutter' => $settings['gutter']	
		);
		
		$directorypress_handler = new directorypress_categories_handler();
		$directorypress_handler->init($instance);

		echo '<div class="directorypress-elementor-category-widget">';
			echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				directorypress_slik_init();	
			} )( jQuery );
		</script>';
		}
		
		
	}

}