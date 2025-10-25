<?php
/**
 * Elementor test Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
use Elementor\Plugin;
class Pacz_Elementor_Advanced_Location_Widget extends \Elementor\Widget_Base {

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
		return 'advanced-locations';
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
		return __( 'Advanced Locations', 'pacz' );
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
		$locations = directorypress_locations_array_options();
		$packages = directorypress_packages_array_options();
		
		// Setting Section
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Setting', 'pacz' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'count',
			[
				'label' =>  __('Show location listing count?', 'pacz'),
				'description' => __('Whether to show number of listings assigned with current location in brackets.', 'pacz'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'pacz' ),
					'1' => __( 'Yes', 'pacz' ),
				],
				'default' => 1,
			]
		);
		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Column Height', 'pacz' ),
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
					'{{WRAPPER}} .directorypress-elementor-locations-widget' => 'min-height: {{SIZE}}{{UNIT}}; width:100% !important',
					'{{WRAPPER}} .directorypress-elementor-locations-widget' => 'height: {{SIZE}}{{UNIT}}; width:100%',
				],
			]
		);
		$this->add_responsive_control(
			'enable_box_link',
			[
				'label' => esc_html__( 'Enable Box Link', 'pacz' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1'  => esc_html__( 'Yes', 'pacz' ),
					'0' => esc_html__( 'No', 'pacz' ),
				],
				'default' => '1',
			]
		);
		$this->end_controls_section(); 
		
		// content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'pacz' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'prefix_text',
			[
				'label' => esc_html__( 'Prefix', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 2,
				'description' => esc_html__( 'Add a Prefix text applicable to custom style only', 'pacz' ),
				'placeholder' => esc_html__( 'Type here', 'pacz' ),
			]
		);
		$this->add_responsive_control(
			'suffix_text',
			[
				'label' => esc_html__( 'Suffix', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 2,
				'description' => esc_html__( 'Add a Suffix text  applicable to custom style only', 'pacz' ),
				'placeholder' => esc_html__( 'Type here', 'pacz' ),
			]
		);
		$this->add_control(
			'locations',
			[
				'label' => __( 'Select A Location', 'pacz' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $locations,
				'default' => 0,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'icon_section',
			[
				'label' => __( 'Icon', 'pacz' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'pacz' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'font_svg'  => esc_html__( 'Font/SVG', 'pacz' ),
					'image' => esc_html__( 'Image', 'pacz' ),
				],
				'default' => 'font_svg',
			]
		);
		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'pacz' ),
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
				'label' => esc_html__( 'Choose Image', 'pacz' ),
				'condition' => ['icon_type' => 'image'],
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
			]
		);

		$this->end_controls_section();
		
		// Style tab and section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Location', 'pacz' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'location_typography',
				'label' => __( 'Title Typography', 'pacz' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				/* 'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-location a',
					'{{WRAPPER}} .location-style7 .directorypress-location-item .directorypress-location-item-holder .directorypress-advanced-parent-location a',
				], */
				'selector' => '{{WRAPPER}} .directorypress-advanced-parent-location a, {{WRAPPER}} .location-style-custom .directorypress-location-item .directorypress-location-item-holder .directorypress-advanced-parent-location a',
			]
		);
		$this->start_controls_tabs( 'location_style' );

		$this->start_controls_tab(
			'tab_field_normal',
			array(
				'label' => __( 'Normal', 'pacz' ),
			)
		);

		$this->add_control(
			'location_color',
			[
				'label' => __( 'Title Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-location a, .directorypress-advanced-parent-location a .location-icon-wrapper' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'location_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .directorypress-advanced-parent-location a',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'location_css_filters',
				'selector' => '{{WRAPPER}} .directorypress-advanced-parent-location a',
			]
		);
		/* $this->add_control(
			'entrance_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'pacz' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				//'prefix_class' => 'animated ',
			]
		); */
		
		$this->add_control(
			'background_color',
			array(
				'label' => __( 'Background Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .directorypress-advanced-parent-location a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'location_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .directorypress-advanced-parent-location',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_hover',
			array(
				'label' => __( 'Hover', 'pacz' ),
			)
		);

		$this->add_control(
			'location_color_hover',
			[
				'label' => __( 'Title Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .location-style-advanced:hover .directorypress-advanced-parent-location a, .directorypress-advanced-parent-location a .location-icon-wrapper' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'location_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-style-advanced:hover .directorypress-advanced-parent-location a',
			]
		);
		$this->add_responsive_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'pacz' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'background_color_hover',
			array(
				'label' => __( 'Background Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .location-style-advanced:hover .directorypress-advanced-parent-location a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'location_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-style-advanced:hover .directorypress-advanced-parent-location',
			]
		);
		
		$this->add_control(
			'location_border_color_hover',
			array(
				'label' => __( 'Border Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
					'{{WRAPPER}} .location-style-advanced:hover .directorypress-advanced-parent-location a' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'pacz' ),
				'selector' => '{{WRAPPER}} .directorypress-advanced-parent-location a',
			]
		);
		$this->add_responsive_control(
			'location_border_radius',
			[
				'label' => __( 'Border Radius', 'pacz' ),
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
					'{{WRAPPER}} .directorypress-advanced-parent-location a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'location_padding',
			[
				'label' => __( 'Padding', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-location a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'location_margin',
			[
				'label' => __( 'Margin', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom'=> '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-location a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'location_position',
			[
				'label' => esc_html__( 'Position', 'pacz' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'pacz' ),
					'absolute' => esc_html__( 'Absolute', 'pacz' ),
					'static' => esc_html__( 'Static', 'pacz' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'location_position_top',
			[
				'label' => __( 'Location Position Top', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-location' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'location_position_left',
			[
				'label' => __( 'Location Position Left', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => __( 'Position From Left', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-location' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'location_position_bottom',
			[
				'label' => __( 'Location Position Bottom', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'Position From Bottom', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-location' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'location_position_right',
			[
				'label' => __( 'Location Position Right', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-location' => 'right: {{VALUE}}px;',
				],
			]
		);
		$this->end_controls_section();
		
		// Prefix Text
		$this->start_controls_section(
			'prefix_section',
			[
				'label' => __( 'Prefix Text', 'pacz' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'prefix_typography',
				'label' => __( 'Title Typography', 'pacz' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				/* 'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-location a',
					'{{WRAPPER}} .location-style7 .directorypress-location-item .directorypress-location-item-holder .directorypress-advanced-parent-location a',
				], */
				'selector' => '{{WRAPPER}} .location-prefix-text, {{WRAPPER}} .location-style-custom .directorypress-location-item .directorypress-location-item-holder .directorypress-advanced-parent-location a',
			]
		);
		$this->start_controls_tabs( 'prefix_style' );

		$this->start_controls_tab(
			'prefix_field_normal',
			array(
				'label' => __( 'Normal', 'pacz' ),
			)
		);

		$this->add_control(
			'prefix_color',
			[
				'label' => __( 'Title Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .location-prefix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'prefix_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-prefix-text',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'prefix_css_filters',
				'selector' => '{{WRAPPER}} .location-prefix-text',
			]
		);
		$this->add_control(
			'prefix_background_color',
			array(
				'label' => __( 'Background Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .location-prefix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'prefix_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-prefix-text',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'prefix_field_hover',
			array(
				'label' => __( 'Hover', 'pacz' ),
			)
		);

		$this->add_control(
			'prefix_color_hover',
			[
				'label' => __( 'Title Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .location-style-advanced:hover .location-prefix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'prefix_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-style-advanced:hover .location-prefix-text',
			]
		);
		$this->add_responsive_control(
			'prefix_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'pacz' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'prefix_background_color_hover',
			array(
				'label' => __( 'Background Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .location-style-advanced:hover .location-prefix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'prefix_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-style-advanced:hover .location-prefix-text',
			]
		);
		
		$this->add_control(
			'prefix_border_color_hover',
			array(
				'label' => __( 'Border Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
					'{{WRAPPER}} .location-style-advanced:hover .location-prefix-text' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'prefix_border',
				'label' => __( 'Border', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-prefix-text',
			]
		);
		$this->add_responsive_control(
			'prefix_border_radius',
			[
				'label' => __( 'Border Radius', 'pacz' ),
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
					'{{WRAPPER}} .location-prefix-text' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_padding',
			[
				'label' => __( 'Padding', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .location-prefix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_margin',
			[
				'label' => __( 'Margin', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .location-prefix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_position',
			[
				'label' => esc_html__( 'Position', 'pacz' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'pacz' ),
					'absolute' => esc_html__( 'Absolute', 'pacz' ),
					'static' => esc_html__( 'Static', 'pacz' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'prefix_position_top',
			[
				'label' => __( 'Position Top', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'From Top', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-prefix-text' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_position_left',
			[
				'label' => __( 'Position Left', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => __( 'From Left', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-prefix-text' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_position_bottom',
			[
				'label' => __( 'Position Bottom', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'From Bottom', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-prefix-text' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'prefix_position_right',
			[
				'label' => __( 'Position Right', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'From Right', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-prefix-text' => 'right: {{VALUE}}px;',
				],
			]
		);
		$this->end_controls_section();
		
		// Suffix Text
		$this->start_controls_section(
			'suffix_section',
			[
				'label' => __( 'Suffix Text', 'pacz' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'suffix_typography',
				'label' => __( 'Title Typography', 'pacz' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				/* 'selectors' => [
					'{{WRAPPER}} .directorypress-advanced-parent-location a',
					'{{WRAPPER}} .location-style7 .directorypress-location-item .directorypress-location-item-holder .directorypress-advanced-parent-location a',
				], */
				'selector' => '{{WRAPPER}} .location-suffix-text, {{WRAPPER}} .location-style-custom .directorypress-location-item .directorypress-location-item-holder .directorypress-advanced-parent-location a',
			]
		);
		$this->start_controls_tabs( 'suffix_style' );

		$this->start_controls_tab(
			'suffix_field_normal',
			array(
				'label' => __( 'Normal', 'pacz' ),
			)
		);

		$this->add_control(
			'suffix_color',
			[
				'label' => __( 'Title Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .location-suffix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'suffix_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-suffix-text',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'suffix_css_filters',
				'selector' => '{{WRAPPER}} .location-suffix-text',
			]
		);
		$this->add_control(
			'suffix_background_color',
			array(
				'label' => __( 'Background Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .location-suffix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'suffix_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-suffix-text',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'suffix_field_hover',
			array(
				'label' => __( 'Hover', 'pacz' ),
			)
		);

		$this->add_control(
			'suffix_color_hover',
			[
				'label' => __( 'Title Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .location-style-advanced:hover .location-suffix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'suffix_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-style-advanced:hover .location-suffix-text',
			]
		);
		$this->add_responsive_control(
			'suffix_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'pacz' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'suffix_background_color_hover',
			array(
				'label' => __( 'Background Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .location-style-advanced:hover .location-suffix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'suffix_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-style-advanced:hover .directorypress-advanced-parent-location',
			]
		);
		
		$this->add_control(
			'suffix_border_color_hover',
			array(
				'label' => __( 'Border Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
					'{{WRAPPER}} .location-style-advanced:hover .location-suffix-text' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'suffix_border',
				'label' => __( 'Border', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-suffix-text',
			]
		);
		$this->add_responsive_control(
			'suffix_border_radius',
			[
				'label' => __( 'Border Radius', 'pacz' ),
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
					'{{WRAPPER}} .location-suffix-text' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_padding',
			[
				'label' => __( 'Padding', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .location-suffix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_margin',
			[
				'label' => __( 'Margin', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .location-suffix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_position',
			[
				'label' => esc_html__( 'Position', 'pacz' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'pacz' ),
					'absolute' => esc_html__( 'Absolute', 'pacz' ),
					'static' => esc_html__( 'Static', 'pacz' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'suffix_position_top',
			[
				'label' => __( 'Position Top', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-suffix-text' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_position_left',
			[
				'label' => __( 'Position Left', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => __( 'Position From Left', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-suffix-text' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_position_bottom',
			[
				'label' => __( 'Position Bottom', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'Position From Bottom', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-suffix-text' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'suffix_position_right',
			[
				'label' => __( 'Position Right', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-suffix-text' => 'right: {{VALUE}}px;',
				],
			]
		);
		$this->end_controls_section();
		
		// Icon styles
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => __( 'Icon', 'pacz' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'label' => __( 'Title Typography', 'pacz' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .location-advanced-item-icon',
			]
		);
		$this->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__( 'SVG/Image Icon Width', 'pacz' ),
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
					'{{WRAPPER}} .location-advanced-item-icon img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .location-advanced-item-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'icon_style' );

		$this->start_controls_tab(
			'icon_field_normal',
			array(
				'label' => __( 'Normal', 'pacz' ),
			)
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-icon' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'icon_css_filters',
				'selector' => '{{WRAPPER}} .location-advanced-item-icon',
			]
		);
		$this->add_control(
			'icon_background_color',
			array(
				'label' => __( 'Background Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .location-advanced-item-icon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-advanced-item-icon',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_field_hover',
			array(
				'label' => __( 'Hover', 'pacz' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => __( 'Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .location-style-advanced:hover .location-advanced-item-icon' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_responsive_control(
			'icon_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'pacz' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'icon_background_color_hover',
			array(
				'label' => __( 'Background Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .location-style-advanced:hover .location-advanced-item-icon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-style-advanced:hover .location-advanced-item-icon',
			]
		);
		
		$this->add_control(
			'icon_border_color_hover',
			array(
				'label' => __( 'Border Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .location-style-advanced:hover .location-advanced-item-icon' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => __( 'Border', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-advanced-item-icon',
			]
		);
		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'pacz' ),
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
					'{{WRAPPER}} .location-advanced-item-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => __( 'Margin', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position',
			[
				'label' => esc_html__( 'Position', 'pacz' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'pacz' ),
					'absolute' => esc_html__( 'Absolute', 'pacz' ),
					'static' => esc_html__( 'Static', 'pacz' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'icon_position_top',
			[
				'label' => __( 'Position Top', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-icon' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_left',
			[
				'label' => __( 'Position Left', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Left', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-icon' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_bottom',
			[
				'label' => __( 'Position Bottom', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Bottom', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-icon' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_right',
			[
				'label' => __( 'Position Right', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __('Position From Right', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-icon' => 'right: {{VALUE}}px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		// Counter
		$this->start_controls_section(
			'counter_section',
			[
				'label' => __( 'Counter', 'pacz' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'counter_typography',
				'label' => __( 'Title Typography', 'pacz' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .location-advanced-item-numbers, {{WRAPPER}} .location-style-custom .directorypress-location-item .directorypress-location-item-holder .directorypress-advanced-parent-location a',
			]
		);
		$this->start_controls_tabs( 'counter_style' );

		$this->start_controls_tab(
			'counter_field_normal',
			array(
				'label' => __( 'Normal', 'pacz' ),
			)
		);

		$this->add_control(
			'counter_color',
			[
				'label' => __( 'Title Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-numbers' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'counter_css_filters',
				'selector' => '{{WRAPPER}} .location-advanced-item-numbers',
			]
		);
		$this->add_control(
			'counter_background_color',
			array(
				'label' => __( 'Background Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .location-advanced-item-numbers' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-advanced-item-numbers',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'counter_field_hover',
			array(
				'label' => __( 'Hover', 'pacz' ),
			)
		);

		$this->add_control(
			'counter_color_hover',
			[
				'label' => __( 'Title Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .location-style-advanced:hover .location-advanced-item-numbers' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_responsive_control(
			'counter_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'pacz' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'counter_background_color_hover',
			array(
				'label' => __( 'Background Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .location-style-advanced:hover .location-advanced-item-numbers' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-style-advanced:hover .location-advanced-item-numbers',
			]
		);
		
		$this->add_control(
			'counter_border_color_hover',
			array(
				'label' => __( 'Border Color', 'pacz' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
					'{{WRAPPER}} .location-style-advanced:hover .location-advanced-item-numbers' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'counter_border',
				'label' => __( 'Border', 'pacz' ),
				'selector' => '{{WRAPPER}} .location-advanced-item-numbers',
			]
		);
		$this->add_control(
			'counter_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'counter_padding',
			[
				'label' => __( 'Padding', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'counter_margin',
			[
				'label' => __( 'Margin', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position',
			[
				'label' => esc_html__( 'Position', 'pacz' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'pacz' ),
					'absolute' => esc_html__( 'Absolute', 'pacz' ),
					'static' => esc_html__( 'Static', 'pacz' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'counter_position_top',
			[
				'label' => __( 'Location Position Top', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-numbers' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position_left',
			[
				'label' => __( 'Location Position Left', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => __( 'Position From Left', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-numbers' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position_bottom',
			[
				'label' => __( 'Location Position Bottom', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'Position From Bottom', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-numbers' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position_right',
			[
				'label' => __( 'Location Position Right', 'pacz' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'pacz' ),
				'selectors' => [
					'{{WRAPPER}} .location-advanced-item-numbers' => 'right: {{VALUE}}px;',
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
				//'sublocations' => '',
				'locations' => $settings['locations'],
				'location_style' => 'advanced',
				'prefix_text' => $settings['prefix_text'],
				'suffix_text' => $settings['suffix_text'],
				'location_position' => $settings['location_position'],
				'counter_position' => $settings['counter_position'],
				'prefix_position' => $settings['prefix_position'],
				'suffix_position' => $settings['suffix_position'],
				'icon_type' => 'image', //$settings['icon'],
				'icon' => $settings['icon'],
				'icon_image' => $settings['icon_image'],
				'icon_position' => $settings['icon_position'],
				//'entrance_animation' => $settings['entrance_animation'],
				'hover_animation' => $settings['hover_animation'],
				'enable_box_link' => $settings['enable_box_link'],
				
		);
		$instance['max_subterms'] = 0;
		
		//$instance['icon_html'] = \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
		$instance['exact_terms'] = $instance['locations'];
		$directorypress_handler = new DirectoryPress_Location_Terms($instance);
		//$directorypress_handler->display();
		//$hover_animation = $instance['hover_animation'];
		echo '<div class="directorypress-elementor-locations-widget clearfix">';
			//if(isset($settings['icon']) && !empty($settings['icon']['value'])){
				//echo '<div class="location-icon-wrapper">';
					//\Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); 
				//echo '</div>';
			//}
			//ob_start();
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