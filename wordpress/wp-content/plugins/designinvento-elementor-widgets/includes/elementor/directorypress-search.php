<?php

//namespace HFB\WidgetsManager\Widgets;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;
use Elementor\Plugin;

class Pacz_DirectoryPress_Search_Widget extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
		
	}
	public function scripts() {
		if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			wp_enqueue_style('directorypress-search', 1);
		}
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
		return 'pacz-directorypress-header-search';
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
		return __( 'DirectoryPress Search', 'designinvento-elementor-widgets' );
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
		return 'fab fa-searchengin';
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
		return [ 'hfb-widgets' ];
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
		$locations = directorypress_locations_array_options();
		// Setting Section
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Setting', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'show_keywords_search',
			[
				'label' => __( 'Turn On Keyword Search', 'designinvento-elementor-widgets' ), 
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
		$this->add_control(
			'keywords_ajax_search',
			[
				'label' => __( 'Turn On Ajax Keyword Search', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'designinvento-elementor-widgets' ),
					'1' => __( 'Yes', 'designinvento-elementor-widgets' ),
				],
				'default' => 1,
				'condition' => [
					'show_keywords_search' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'show_categories_search',
			[
				'label' => __( 'Turn On Category Search', 'designinvento-elementor-widgets' ), 
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
		$this->add_control(
			'categories_search_depth',
			[
				'label' => __( 'Category Depth Level', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => __( 'Level 1', 'designinvento-elementor-widgets' ),
					'2' => __( 'Level 2', 'designinvento-elementor-widgets' ),
					//'3' => __( 'Level 3', 'designinvento-elementor-widgets' ),
				],
				'default' => 1,
				'condition' => [
					'show_categories_search' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'show_locations_search',
			[
				'label' => __( 'Turn On Location Search', 'designinvento-elementor-widgets' ), 
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
		$this->add_control(
			'locations_search_depth',
			[
				'label' => __( 'Locations Depth Level', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => __( 'Level 1', 'designinvento-elementor-widgets' ),
					'2' => __( 'Level 2', 'designinvento-elementor-widgets' ),
					//'3' => __( 'Level 3', 'designinvento-elementor-widgets' ),
				],
				'default' => 1,
				'condition' => [
					'show_locations_search' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'show_address_search',
			[
				'label' => __( 'Show Address Search?', 'designinvento-elementor-widgets' ), 
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
		$this->add_control(
			'address',
			[
				'label' => __( 'Default address', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'show_address_search' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'hide_search_button',
			[
				'label' => __( 'Hide Search Button', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'designinvento-elementor-widgets' ),
					'1' => __( 'Yes', 'designinvento-elementor-widgets' ),
				],
				'default' => 0,
			]
		);
		$this->add_responsive_control(
			'gap_in_fields',
			[
				'label' => __( 'Fields Gap', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				]
			]
		);
		$this->add_responsive_control(
			'fields_height',
			[
				'label' => __( 'Fields Height', 'designinvento-elementor-widgets' ),
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
					'{{WRAPPER}} .directorypress-search-holder .form-control' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .directorypress-search-holder .directorypress-autocomplete-dropmenubox-locations input' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__arrow' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .directorypress-search-holder .select2-selection--single' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .search-form-style1 .select2-selection--single' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__rendered' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'fields_margin_top',
			[
				'label' => __( 'Fields Margin Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .search-element-col' => 'margin-top: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'fields_margin_bottom',
			[
				'label' => __( 'Fields Margin Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .search-element-col:not(.directorypress-search-submit-button-wrap)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'keyword_field_width',
			[
				'label' => __( 'Keyword Field Width', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .keyword-search' => 'width: {{SIZE}}% !important;',
				],
			]
		);
		$this->add_responsive_control(
			'location_field_width',
			[
				'label' => __( 'Location Field Width', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .address-search' => 'width: {{SIZE}}% !important;',
				],
			]
		);
		$this->add_responsive_control(
			'button_field_width',
			[
				'label' => __( 'Search Button Width', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-submit-button-wrap' => 'width: {{SIZE}}% !important;',
				],
			]
		);
		$this->add_responsive_control(
			'button_margin_top',
			[
				'label' => __( 'Button Margin Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn' => 'margin-top: {{SIZE}}{{UNIT}};',
				]
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
		$this->add_control(
			'directorytype',
			[
				'label' => __( 'Select Directory', 'designinvento-elementor-widgets' ), 
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
				'label' => __( 'Unique ID', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'description' => __( 'Insert unique id if you like to connect this module to a specific module like map or search(optional)', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);
		$this->add_control(
			'categories',
			[
				'label' => __( 'Select Specific Categories', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $categories,
				'default' => [0],
			]
		);
		$this->add_control(
			'locations',
			[
				'label' => __( 'Select Specific Locations', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $locations,
				'default' => [0],
			]
		);
		$this->end_controls_section();
		
		// Style tab and section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'form_padding',
			[
				'label' => __( 'Search Form Padding', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'form_border',
				'label' => __( 'Search Form Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .directorypress-search-form',
			]
		);
		$this->add_responsive_control(
			'form_border_radius',
			[
				'label' => __( 'Search Form Border Radius', 'designinvento-elementor-widgets' ),
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
					'{{WRAPPER}} .directorypress-search-form' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'form_background',
				'label' => __( 'Search Form Background', 'designinvento-elementor-widgets' ),
				'description' => __( 'Search Form Background', 'designinvento-elementor-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .directorypress-search-form',
			]
		);
		$this->end_controls_section();
		// filed
		$this->start_controls_section(
			'field_section',
			[
				'label' => __( 'Fields', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'field_text_color',
			[
				'label' => __( 'Field Text Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'field_placeholder_color',
			[
				'label' => __( 'Field Placeholder Text Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control::placeholder, .directorypress-search-form .directorypress-search-holder .form-control::-webkit-input-placeholder, .directorypress-search-form .directorypress-search-holder .form-control::-moz-placeholder, .directorypress-search-form .directorypress-search-holder .form-control:-moz-placeholder ' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'field_icon_color',
			[
				'label' => __( 'Field Icon Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-form .directorypress-form-control-feedback' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'field_icon_bg_color',
			[
				'label' => __( 'Field Icon Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-form .directorypress-form-control-feedback' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'field_icon_size',
			[
				'label' => __( 'Field Icon Font Size', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 8,
						'max' => 36,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-form .directorypress-form-control-feedback' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'field_border-radius',
			[
				'label' => __( 'Field Border Radius', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control,.directorypress-search-form .directorypress-search-holder .directorypress-tax-dropdowns-wrap .has-feedback' => 'border-radius: {{SIZE}}{{UNIT}};overflow: hidden;',
				],
			]
		);
		$this->add_control(
			'field_border_title',
			[
				'label' => __( 'Field Border', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'field_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control',
			]
		);
		$this->add_control(
			'field_box_shadow_title',
			[
				'label' => __( 'Field Box Shadow', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'field_box_shadow',
				'label' => __( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control',
			]
		);
		$this->add_control(
			'field_placeholder_typo_control_title',
			[
				'label' => __( 'Field Typography', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'field_placehoder_typography',
				'label' => __( 'Typography', 'designinvento-elementor-widgets' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control',
			]
		);
		$this->add_control(
			'field_label_typo_control_title',
			[
				'label' => __( 'Field Label Typography', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'field_label_typography',
				'label' => __( 'Typography', 'designinvento-elementor-widgets' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .directorypress-search-form label',
			]
		);
		$this->add_control(
			'field_bg_control_title',
			[
				'label' => __( 'Field Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'field_background_color',
			[
				'label' => __( 'Field Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		// button
		$this->start_controls_section(
			'button_section',
			[
				'label' => __( 'Button', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'search_button_type',
			[
				'label' => __( 'Search Button Type', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1'  => __( 'Text + Icon Left', 'designinvento-elementor-widgets' ),
					'2'  => __( 'Text + Icon Right', 'designinvento-elementor-widgets' ),
					'3'  => __( 'Text Only', 'designinvento-elementor-widgets' ),
					'4'  => __( 'Icon Only', 'designinvento-elementor-widgets' ),
				],
				'default' => ['none'],
			]
		);
		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Button Text Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'button_text_color_hover',
			[
				'label' => __( 'Button Text Hover Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn',
			]
		);
		$this->add_responsive_control(
			'search_button_border',
			[
				'label' => __( 'Button Border Radius', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'search_button_height',
			[
				'label' => __( 'Button Height', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}',
					//'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'search_button_icon',
			[
				'label' => __( 'Search Icon', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'exclude_inline_options' => 'svg',
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'solid',
				],
			]
		);
		$this->add_control(
			'button_typo_control_title',
			[
				'label' => __( 'Button Typography', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'designinvento-elementor-widgets' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn',
			]
		);
		$this->add_control(
			'button_background_control_title',
			[
				'label' => __( 'Button Background', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'form_button_background',
				'label' => __( 'Button Background', 'designinvento-elementor-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn',
			]
		);
		$this->add_control(
			'button_background_hover_control_title',
			[
				'label' => __( 'Button Background Hover', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'form_button_background_hover',
				'label' => __( 'Button Background Hover', 'designinvento-elementor-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn:hover',
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
		//print_r($settings['categories']);
		$category = implode(',', $settings['categories']);
		$location = implode(',', $settings['locations']);
		$instance = array(
				'directorytype' => $settings['directorytype'],
				//'columns' => 2,
				'advanced_open' => 0,
				'uid' => $settings['uid'],
				'show_categories_search' =>  $settings['show_categories_search'],
				'categories_search_depth' =>  $settings['categories_search_depth'],
				'category' => $category,
				//'exact_categories' => array(),
				'show_default_filed_label' => 0,
				'show_keywords_search' =>  $settings['show_keywords_search'],
				'keywords_ajax_search' =>  $settings['keywords_ajax_search'],
				'keywords_search_examples' => 0,
				//'what_search' => '',
				'show_radius_search' =>  0,
				//'radius' =>  $settings['radius'],
				'show_locations_search' =>  $settings['show_locations_search'],
				'locations_search_depth' =>  $settings['locations_search_depth'],
				'show_address_search' =>  $settings['show_address_search'],
				'address' => $settings['address'],
				'location' => $location,
				//'exact_locations' => array(),
				'search_fields' => '-1',
				'search_fields_advanced' => '-1',
				'hide_search_button' => $settings['hide_search_button'],
				//'on_row_search_button' => 0,
				//'has_sticky_scroll' => 0,
				//'has_sticky_scroll_toppadding' => 0,
				'gap_in_fields' => $settings['gap_in_fields']['size'],
				'search_button_icon' => $settings['search_button_icon']['value'],
				'search_button_type' => $settings['search_button_type'],
				'keyword_field_width' => $settings['keyword_field_width']['size'],
				'location_field_width' => $settings['location_field_width']['size'],
				//'radius_field_width' => $settings['radius_field_width']['size'],
				'button_field_width' => $settings['button_field_width']['size'],
				//'scroll_to' => 'listings', // '', 'listings', 'map'
				
		);
		
		$directorypress_handler = new directorypress_search_handler();
		$directorypress_handler->init($instance);

		echo '<div class="directorypress-elementor-search-widget">';
			echo $directorypress_handler->display();
			//print_r($settings['search_button_icon']);
		echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				directorypress_select2_init();
				directorypress_process_main_search_fields();
			} )( jQuery );
		</script>';
		};
	}

}