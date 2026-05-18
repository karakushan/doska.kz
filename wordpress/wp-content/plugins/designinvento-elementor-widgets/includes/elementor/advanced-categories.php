<?php
/**
 * @since 1.0.0
 */
use Elementor\Plugin;
class Pacz_Elementor_Advanced_Categories_Widget extends \Elementor\Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
	}
	public function scripts() {
		
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
		return 'advanced-categories';
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
		return __( 'Advanced Categories', 'designinvento-elementor-widgets' );
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
		return 'fas fa-map-marked-alt';
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
		//$directories = directorypress_directorytypes_array_options();
		$categories = directorypress_categories_array_options();
		$packages = directorypress_packages_array_options();
		
		// Setting Section
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Setting', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'count',
			[
				'label' =>  __('Show category listing count?', 'designinvento-elementor-widgets'),
				'description' => __('Whether to show number of listings assigned with current category in brackets.', 'designinvento-elementor-widgets'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'designinvento-elementor-widgets' ),
					'1' => __( 'Yes', 'designinvento-elementor-widgets' ),
				],
				'default' => 1,
			]
		);
		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Column Height', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-elementor-categories-widget' => 'min-height: {{SIZE}}{{UNIT}}; width:100% !important',
					'{{WRAPPER}} .directorypress-elementor-categories-widget' => 'height: {{SIZE}}{{UNIT}}; width:100%',
				],
			]
		);
		$this->add_responsive_control(
			'enable_box_link',
			[
				'label' => esc_html__( 'Enable Box Link', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1'  => esc_html__( 'Yes', 'designinvento-elementor-widgets' ),
					'0' => esc_html__( 'No', 'designinvento-elementor-widgets' ),
				],
				'default' => '1',
			]
		);
		$this->end_controls_section(); 
		
		// content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'prefix_text',
			[
				'label' => esc_html__( 'Prefix', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 2,
				'description' => esc_html__( 'Add a Prefix text applicable to custom style only', 'designinvento-elementor-widgets' ),
				'placeholder' => esc_html__( 'Type here', 'designinvento-elementor-widgets' ),
			]
		);
		$this->add_responsive_control(
			'suffix_text',
			[
				'label' => esc_html__( 'Suffix', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 2,
				'description' => esc_html__( 'Add a Suffix text  applicable to custom style only', 'designinvento-elementor-widgets' ),
				'placeholder' => esc_html__( 'Type here', 'designinvento-elementor-widgets' ),
			]
		);
		$this->add_control(
			'categories',
			[
				'label' => __( 'Select A Category', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $categories,
				'default' => 0,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'icon_section',
			[
				'label' => __( 'Icon', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'font_svg'  => esc_html__( 'Font/SVG', 'designinvento-elementor-widgets' ),
					'image' => esc_html__( 'Image', 'designinvento-elementor-widgets' ),
				],
				'default' => 'font_svg',
			]
		);
		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'condition' => ['icon_type' => 'font_svg'],
				'default' => [
					'value' => '',
					'library' => 'solid',
				],
			]
		);
		$this->add_control(
			'icon_image',
			[
				'label' => esc_html__( 'Choose Image', 'designinvento-elementor-widgets' ),
				'condition' => ['icon_type' => 'image'],
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
			]
		);

		$this->end_controls_section();
		
		// Slider
		$this->start_controls_section(
			'slider_section',
			[
				'label' => __( 'Slider', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'scroll',
			[
				'label' => __( 'Turn On Slider', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'designinvento-elementor-widgets' ),
					'1' => __( 'Yes', 'designinvento-elementor-widgets' ),
				],
				'default' => 0,
				//'condition' => [
					//'cat_style' => [ '1', '2', '4', '5', '8', '9', '11' ],
				//],
			]
		);
		$this->add_control(
			'desktop_items',
			[
				'label' => __( 'Items Per Slide', 'designinvento-elementor-widgets' ),
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
				'label' => __( 'Space Between Slides', 'designinvento-elementor-widgets' ),
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
				'label' => __( 'Turn On Autoplay', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'designinvento-elementor-widgets' ),
					'1' => __( 'Yes', 'designinvento-elementor-widgets' ),
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
				'label' => __( 'Autoplay Speed', 'designinvento-elementor-widgets' ),
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
				'label' => __( 'Turn On Loop', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'designinvento-elementor-widgets' ),
					'1' => __( 'Yes', 'designinvento-elementor-widgets' ),
				],
				'default' => 0,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'arrows',
			[
				'label' => __( 'Turn On Navigation', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'designinvento-elementor-widgets' ),
					'1' => __( 'Yes', 'designinvento-elementor-widgets' ),
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
				'label' => __( 'Navigation Style', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => __( 'Style 1', 'designinvento-elementor-widgets' ),
					'2' => __( 'Style 2', 'designinvento-elementor-widgets' ),
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
				'label' => __( 'Delay', 'designinvento-elementor-widgets' ),
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
				'label' => __( 'category', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'category_typography',
				'label' => __( 'Title Typography', 'designinvento-elementor-widgets' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .directorypress-advanced-parent-category a, {{WRAPPER}} .category-style-custom .directorypress-category-item .directorypress-category-item-holder .directorypress-advanced-parent-category a',
			]
		);
		$this->start_controls_tabs( 'category_style' );

		$this->start_controls_tab(
			'tab_field_normal',
			array(
				'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'category_color',
			[
				'label' => __( 'Title Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category a, .directorypress-advanced-parent-category a .category-icon-wrapper' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'category_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .directorypress-advanced-parent-category a',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'category_css_filters',
				'selector' => '{{WRAPPER}} .directorypress-advanced-parent-category a',
			]
		);
		/* $this->add_control(
			'entrance_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				//'prefix_class' => 'animated ',
			]
		); */
		
		$this->add_control(
			'background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .directorypress-advanced-parent-category a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'category_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .directorypress-advanced-parent-category',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_hover',
			array(
				'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'category_color_hover',
			[
				'label' => __( 'Title Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-style-advanced:hover .directorypress-advanced-parent-category a, .directorypress-advanced-parent-category a .category-icon-wrapper' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'category_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-style-advanced:hover .directorypress-advanced-parent-category a',
			]
		);
		$this->add_responsive_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'background_color_hover',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category-style-advanced:hover .directorypress-advanced-parent-category a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'category_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-style-advanced:hover .directorypress-advanced-parent-category',
			]
		);
		
		$this->add_control(
			'category_border_color_hover',
			array(
				'label' => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
					'{{WRAPPER}} .category-style-advanced:hover .directorypress-advanced-parent-category a' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .directorypress-advanced-parent-category a',
			]
		);
		$this->add_responsive_control(
			'category_border_radius',
			[
				'label' => __( 'Border Radius', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'category_padding',
			[
				'label' => __( 'Padding', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'category_margin',
			[
				'label' => __( 'Margin', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom'=> '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'category_position',
			[
				'label' => esc_html__( 'Position', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'designinvento-elementor-widgets' ),
					'absolute' => esc_html__( 'Absolute', 'designinvento-elementor-widgets' ),
					'static' => esc_html__( 'Static', 'designinvento-elementor-widgets' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'category_position_top',
			[
				'label' => __( 'Category Position Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'category_position_left',
			[
				'label' => __( 'Category Position Left', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => __( 'Position From Left', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'category_position_bottom',
			[
				'label' => __( 'Category Position Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'Position From Bottom', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'category_position_right',
			[
				'label' => __( 'Category Position Right', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category' => 'right: {{VALUE}}px;',
				],
			]
		);
		$this->end_controls_section();
		
		// Prefix Text
		$this->start_controls_section(
			'prefix_section',
			[
				'label' => __( 'Prefix Text', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'prefix_typography',
				'label' => __( 'Title Typography', 'designinvento-elementor-widgets' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				/* 'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category a',
					'{{WRAPPER}} .category-style7 .directorypress-category-item .directorypress-category-item-holder .directorypress-advanced-parent-category a',
				], */
				'selector' => '{{WRAPPER}} .category-prefix-text, {{WRAPPER}} .category-style-custom .directorypress-category-item .directorypress-category-item-holder .directorypress-advanced-parent-category a',
			]
		);
		$this->start_controls_tabs( 'prefix_style' );

		$this->start_controls_tab(
			'prefix_field_normal',
			array(
				'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'prefix_color',
			[
				'label' => __( 'Title Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-prefix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'prefix_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-prefix-text',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'prefix_css_filters',
				'selector' => '{{WRAPPER}} .category-prefix-text',
			]
		);
		$this->add_control(
			'prefix_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category-prefix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'prefix_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-prefix-text',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'prefix_field_hover',
			array(
				'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'prefix_color_hover',
			[
				'label' => __( 'Title Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-style-advanced:hover .category-prefix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'prefix_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-style-advanced:hover .category-prefix-text',
			]
		);
		$this->add_responsive_control(
			'prefix_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'prefix_background_color_hover',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category-style-advanced:hover .category-prefix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'prefix_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-style-advanced:hover .category-prefix-text',
			]
		);
		
		$this->add_control(
			'prefix_border_color_hover',
			array(
				'label' => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
					'{{WRAPPER}} .category-style-advanced:hover .category-prefix-text' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'prefix_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-prefix-text',
			]
		);
		$this->add_responsive_control(
			'prefix_border_radius',
			[
				'label' => __( 'Border Radius', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .category-prefix-text' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_padding',
			[
				'label' => __( 'Padding', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .category-prefix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_margin',
			[
				'label' => __( 'Margin', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .category-prefix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_position',
			[
				'label' => esc_html__( 'Position', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'designinvento-elementor-widgets' ),
					'absolute' => esc_html__( 'Absolute', 'designinvento-elementor-widgets' ),
					'static' => esc_html__( 'Static', 'designinvento-elementor-widgets' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'prefix_position_top',
			[
				'label' => __( 'Position Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'From Top', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-prefix-text' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_position_left',
			[
				'label' => __( 'Position Left', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => __( 'From Left', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-prefix-text' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_position_bottom',
			[
				'label' => __( 'Position Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'From Bottom', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-prefix-text' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_position_right',
			[
				'label' => __( 'Position Right', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'From Right', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-prefix-text' => 'right: {{VALUE}}px;',
				],
			]
		);
		$this->end_controls_section();
		
		// Suffix Text
		$this->start_controls_section(
			'suffix_section',
			[
				'label' => __( 'Suffix Text', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'suffix_typography',
				'label' => __( 'Title Typography', 'designinvento-elementor-widgets' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				/* 'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category a',
					'{{WRAPPER}} .category-style7 .directorypress-category-item .directorypress-category-item-holder .directorypress-advanced-parent-category a',
				], */
				'selector' => '{{WRAPPER}} .category-suffix-text, {{WRAPPER}} .category-style-custom .directorypress-category-item .directorypress-category-item-holder .directorypress-advanced-parent-category a',
			]
		);
		$this->start_controls_tabs( 'suffix_style' );

		$this->start_controls_tab(
			'suffix_field_normal',
			array(
				'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'suffix_color',
			[
				'label' => __( 'Title Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-suffix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'suffix_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-suffix-text',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'suffix_css_filters',
				'selector' => '{{WRAPPER}} .category-suffix-text',
			]
		);
		$this->add_control(
			'suffix_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category-suffix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'suffix_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-suffix-text',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'suffix_field_hover',
			array(
				'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'suffix_color_hover',
			[
				'label' => __( 'Title Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-style-advanced:hover .category-suffix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'suffix_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-style-advanced:hover .category-suffix-text',
			]
		);
		$this->add_responsive_control(
			'suffix_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'suffix_background_color_hover',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category-style-advanced:hover .category-suffix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'suffix_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-style-advanced:hover .directorypress-advanced-parent-category',
			]
		);
		
		$this->add_control(
			'suffix_border_color_hover',
			array(
				'label' => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
					'{{WRAPPER}} .category-style-advanced:hover .category-suffix-text' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'suffix_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-suffix-text',
			]
		);
		$this->add_responsive_control(
			'suffix_border_radius',
			[
				'label' => __( 'Border Radius', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .category-suffix-text' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_padding',
			[
				'label' => __( 'Padding', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .category-suffix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_margin',
			[
				'label' => __( 'Margin', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .category-suffix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_position',
			[
				'label' => esc_html__( 'Position', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'designinvento-elementor-widgets' ),
					'absolute' => esc_html__( 'Absolute', 'designinvento-elementor-widgets' ),
					'static' => esc_html__( 'Static', 'designinvento-elementor-widgets' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'suffix_position_top',
			[
				'label' => __( 'Position Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-suffix-text' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_position_left',
			[
				'label' => __( 'Position Left', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => __( 'Position From Left', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-suffix-text' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_position_bottom',
			[
				'label' => __( 'Position Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'Position From Bottom', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-suffix-text' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_position_right',
			[
				'label' => __( 'Position Right', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-suffix-text' => 'right: {{VALUE}}px;',
				],
			]
		);
		$this->end_controls_section();
		
		// Icon styles
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => __( 'Icon', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'label' => __( 'Title Typography', 'designinvento-elementor-widgets' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				/* 'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category a',
					'{{WRAPPER}} .category-style7 .directorypress-category-item .directorypress-category-item-holder .directorypress-advanced-parent-category a',
				], */
				'selector' => '{{WRAPPER}} .category-advanced-item-icon',
			]
		);
		$this->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__( 'SVG/Image Icon Width', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-icon img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .category-advanced-item-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'icon_style' );

		$this->start_controls_tab(
			'icon_field_normal',
			array(
				'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-icon' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'icon_css_filters',
				'selector' => '{{WRAPPER}} .category-advanced-item-icon',
			]
		);
		$this->add_control(
			'icon_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category-advanced-item-icon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-advanced-item-icon',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_field_hover',
			array(
				'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => __( 'Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-style-advanced:hover .category-advanced-item-icon' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_responsive_control(
			'icon_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'icon_background_color_hover',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category-style-advanced:hover .category-advanced-item-icon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-style-advanced:hover .category-advanced-item-icon',
			]
		);
		
		$this->add_control(
			'icon_border_color_hover',
			array(
				'label' => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
					'{{WRAPPER}} .category-style-advanced:hover .category-advanced-item-icon' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-advanced-item-icon',
			]
		);
		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => __( 'Margin', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position',
			[
				'label' => esc_html__( 'Position', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'designinvento-elementor-widgets' ),
					'absolute' => esc_html__( 'Absolute', 'designinvento-elementor-widgets' ),
					'static' => esc_html__( 'Static', 'designinvento-elementor-widgets' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'icon_position_top',
			[
				'label' => __( 'Position Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-icon' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_left',
			[
				'label' => __( 'Position Left', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Left', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-icon' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_bottom',
			[
				'label' => __( 'Position Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Bottom', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-icon' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_right',
			[
				'label' => __( 'Position Right', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __('Position From Right', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-icon' => 'right: {{VALUE}}px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		// Counter
		$this->start_controls_section(
			'counter_section',
			[
				'label' => __( 'Counter', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'counter_typography',
				'label' => __( 'Title Typography', 'designinvento-elementor-widgets' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				/* 'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-category a',
					'{{WRAPPER}} .category-style7 .directorypress-category-item .directorypress-category-item-holder .directorypress-advanced-parent-category a',
				], */
				'selector' => '{{WRAPPER}} .category-advanced-item-numbers, {{WRAPPER}} .category-style-custom .directorypress-category-item .directorypress-category-item-holder .directorypress-advanced-parent-category a',
			]
		);
		$this->start_controls_tabs( 'counter_style' );

		$this->start_controls_tab(
			'counter_field_normal',
			array(
				'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'counter_color',
			[
				'label' => __( 'Title Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-numbers' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'counter_css_filters',
				'selector' => '{{WRAPPER}} .category-advanced-item-numbers',
			]
		);
		$this->add_control(
			'counter_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category-advanced-item-numbers' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-advanced-item-numbers',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'counter_field_hover',
			array(
				'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'counter_color_hover',
			[
				'label' => __( 'Title Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-style-advanced:hover .category-advanced-item-numbers' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_responsive_control(
			'counter_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'counter_background_color_hover',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category-style-advanced:hover .category-advanced-item-numbers' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-style-advanced:hover .category-advanced-item-numbers',
			]
		);
		
		$this->add_control(
			'counter_border_color_hover',
			array(
				'label' => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
					'{{WRAPPER}} .category-style-advanced:hover .category-advanced-item-numbers' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'counter_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .category-advanced-item-numbers',
			]
		);
		$this->add_control(
			'counter_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'counter_padding',
			[
				'label' => __( 'Padding', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'counter_margin',
			[
				'label' => __( 'Margin', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position',
			[
				'label' => esc_html__( 'Position', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'designinvento-elementor-widgets' ),
					'absolute' => esc_html__( 'Absolute', 'designinvento-elementor-widgets' ),
					'static' => esc_html__( 'Static', 'designinvento-elementor-widgets' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'counter_position_top',
			[
				'label' => __( 'Category Position Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-numbers' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position_left',
			[
				'label' => __( 'Category Position Left', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => __( 'Position From Left', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-numbers' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position_bottom',
			[
				'label' => __( 'Category Position Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'Position From Bottom', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-numbers' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position_right',
			[
				'label' => __( 'Category Position Right', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .category-advanced-item-numbers' => 'right: {{VALUE}}px;',
				],
			]
		);
		
		$this->end_controls_section();

	}

	
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$instance = array(
				'parent' => '',
				'depth' => 1,
				'columns' => 1,
				'count' => $settings['count'],
				//'subcategories' => '',
				'categories' => $settings['categories'],
				'cat_style' => 'advanced',
				'prefix_text' => $settings['prefix_text'],
				'suffix_text' => $settings['suffix_text'],
				'category_position' => $settings['category_position'],
				'counter_position' => $settings['counter_position'],
				'prefix_position' => $settings['prefix_position'],
				'suffix_position' => $settings['suffix_position'],
				'icon_type' => $settings['icon_type'], //$settings['icon'],
				'icon' => $settings['icon'],
				'icon_image' => $settings['icon_image'],
				'icon_position' => $settings['icon_position'],
				//'entrance_animation' => $settings['entrance_animation'],
				'hover_animation' => $settings['hover_animation'],
				'enable_box_link' => $settings['enable_box_link'],
				
		);
		$instance['max_subterms'] = 0;

		$instance['exact_terms'] = $instance['categories'];
		$directorypress_handler = new DirectoryPress_Category_Terms($instance);
		echo '<div class="directorypress-elementor-categories-widget clearfix">';
			echo $directorypress_handler->display();
			
		echo '</div>';
		
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				//directorypress_slik_init();	
			} )( jQuery );
		</script>';
		};
	}

}