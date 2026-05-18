<?php
/**
 * Elementor test Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
use Elementor\Plugin;
class Pacz_Elementor_Advanced_Terms_Widget extends \Elementor\Widget_Base {

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
		return 'advanced-terms';
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
		return __( 'Advanced Terms', 'designinvento-elementor-widgets' );
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
		
		// Setting Section
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Setting', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'layout',
			[
				'label' =>  __('Layout', 'designinvento-elementor-widgets'),
				'description' => __('Widget layout', 'designinvento-elementor-widgets'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => __( 'icon/title/counter', 'designinvento-elementor-widgets' ),
					'2' => __( 'title/icon/counter', 'designinvento-elementor-widgets' ),
					'3' => __( 'counter/title/icon', 'designinvento-elementor-widgets' ),
					'4' => __( 'counter/icon/title', 'designinvento-elementor-widgets' ),
				],
				'default' => 1,
			]
		);
		$this->add_responsive_control(
			'count',
			[
				'label' =>  __('Show Terms listing count?', 'designinvento-elementor-widgets'),
				'description' => __('Whether to show number of listings assigned with current Term in brackets.', 'designinvento-elementor-widgets'), 
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
            'count_with_text',
            [
                'label' => esc_html__('Add text with count? ', 'designinvento-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' =>esc_html__( 'Yes', 'designinvento-elementor-widgets' ),
                'label_off' =>esc_html__( 'No', 'designinvento-elementor-widgets' ),
            ]
		);
		$this->add_responsive_control(
			'count_custom_text',
			[
				'label' => esc_html__( 'Count suffix custom text', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 2,
				'description' => esc_html__( 'if empty default text (listings) will be added', 'designinvento-elementor-widgets' ),
				'placeholder' => esc_html__( 'Type here', 'designinvento-elementor-widgets' ),
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
					'{{WRAPPER}} .directorypress-elementor-advanced-terms-widget' => 'min-height: {{SIZE}}{{UNIT}}; width:100% !important',
					'{{WRAPPER}} .directorypress-elementor-advanced-terms-widget' => 'height: {{SIZE}}{{UNIT}}; width:100%',
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
			'terms_taxonomy',
			[
				'label' => __( 'Select A Taxonomy', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => $this->taxonomy(),
				'default' => 'directorypress-category',
			]
		);
		foreach(get_object_taxonomies('dp_listing') AS $key => $tax){
			$con = 'directorypress-category';
			$this->add_control(
				'terms_'. $tax,
				[
					'label' => __( 'Select A Term', 'designinvento-elementor-widgets' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'condition' => [
						 'terms_taxonomy' => $tax
					],

					'multiple' => false,
					'options' => directorypress_terms_options_array($tax),
					'default' => 0,
				]
			);
		}
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
		
		// Style tab and section
		$this->start_controls_section(
			'wrapper_section',
			[
				'label' => __( 'Content Wrapper', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
            'enable_content_wrapper',
            [
                'label' => esc_html__('Content Wrapper ', 'designinvento-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' =>esc_html__( 'Yes', 'designinvento-elementor-widgets' ),
                'label_off' =>esc_html__( 'No', 'designinvento-elementor-widgets' ),
            ]
		);
		$this->start_controls_tabs( 'terms_wrapper_style' );

		$this->start_controls_tab(
			'wrapper_tab_field_normal',
			array(
				'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'wrapper_css_filters',
				'selector' => '{{WRAPPER}} .advanced-terms-item-content-wrapper',
			]
		);
		$this->add_control(
			'wrapper_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-terms-item-content-wrapper' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item-content-wrapper',
			]
		);
		$this->add_responsive_control(
			"wrapper_translateX",
				[
					'label' => esc_html__( 'Offset X', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 200,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item-content-wrapper" => 'transform: translateX({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
		);

			$this->add_responsive_control(
			"wrapper_translateY",
				[
					'label' => esc_html__( 'Offset Y', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 200,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item-content-wrapper" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'wrapper_tab_field_hover',
			array(
				'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
			)
		);
		$this->add_responsive_control(
			'wrapper_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'wrapper_background_color_hover',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-content-wrapper' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-content-wrapper',
			]
		);
		
		$this->add_control(
			'wrapper_border_color_hover',
			array(
				'label' => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-content-wrapper' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			"wrapper_translateX_hover",
				[
					'label' => esc_html__( 'Offset X', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 200,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-content-wrapper" => 'transform: translateX({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
		);

			$this->add_responsive_control(
			"wrapper_translateY_hover",
				[
					'label' => esc_html__( 'Offset Y', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 200,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-content-wrapper" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				"wrapper_transition_hover",
					[
						'label' => esc_html__( 'Transition Speed', 'designinvento-elementor-widgets' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => ['px' ],
						'range' => [
							'px' => [
								'min' => 100,
								'max' => 10000,
								'step' => 1,
							],
						],
						'selectors' => [
							"{{WRAPPER}} .advanced-terms-item .advanced-terms-item-holder .advanced-terms-item-content-wrapper" => 'transition: all {{SIZE}}ms ease;',
						],
					]
			);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item-content-wrapper',
			]
		);
		$this->add_responsive_control(
			'wrapper_border_radius',
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
					'{{WRAPPER}} .advanced-terms-item-content-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wrapper_padding',
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
					'{{WRAPPER}} .advanced-terms-item-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wrapper_margin',
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
					'{{WRAPPER}} .advanced-terms-item-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wrapper_position',
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
			'wrapper_position_top',
			[
				'label' => __( 'Category Position Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-content-wrapper' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'wrapper_position_left',
			[
				'label' => __( 'Category Position Left', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => __( 'Position From Left', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-content-wrapper' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'wrapper_position_bottom',
			[
				'label' => __( 'Position Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'Position From Bottom', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-content-wrapper' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'wrapper_position_right',
			[
				'label' => __( 'Position Right', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-content-wrapper' => 'right: {{VALUE}}px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'title_section',
			[
				'label' => __( 'Title', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'designinvento-elementor-widgets' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .advanced-terms-item-title a',
			]
		);
		$this->start_controls_tabs( 'terms_title_style' );

		$this->start_controls_tab(
			'title_tab_field_normal',
			array(
				'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-title a' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item-title a',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'title_css_filters',
				'selector' => '{{WRAPPER}} .advanced-terms-item-title a',
			]
		);
		$this->add_control(
			'background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-terms-item-title a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item-title',
			]
		);
		$this->add_responsive_control(
			"title_translateX",
				[
					'label' => esc_html__( 'Offset X', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item-title" => 'transform: translateX({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
		);

			$this->add_responsive_control(
			"title_translateY",
				[
					'label' => esc_html__( 'Offset Y', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item-title" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_tab_field_hover',
			array(
				'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Title Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-title a' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-title a',
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
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-title a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'title_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-title',
			]
		);
		
		$this->add_control(
			'title_border_color_hover',
			array(
				'label' => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-title a' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			"title_translateX_hover",
				[
					'label' => esc_html__( 'Offset X', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-title" => 'transform: translateX({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
		);

			$this->add_responsive_control(
			"title_translateY_hover",
				[
					'label' => esc_html__( 'Offset Y', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-title" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				"title_transition_hover",
					[
						'label' => esc_html__( 'Transition Speed', 'designinvento-elementor-widgets' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => ['px' ],
						'range' => [
							'px' => [
								'min' => 100,
								'max' => 10000,
								'step' => 1,
							],
						],
						'selectors' => [
							"{{WRAPPER}} .advanced-terms-item .advanced-terms-item-holder .advanced-terms-item-title" => 'transition: all {{SIZE}}ms ease;',
						],
					]
			);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item-title a',
			]
		);
		$this->add_responsive_control(
			'title_border_radius',
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
					'{{WRAPPER}} .advanced-terms-item-title a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_padding',
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
					'{{WRAPPER}} .advanced-terms-item-title a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_margin',
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
					'{{WRAPPER}} .advanced-terms-item-title a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_display',
			[
				'label' => esc_html__( 'Display', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'block'  => esc_html__( 'Block', 'designinvento-elementor-widgets' ),
					'inline-block' => esc_html__( 'Inline Block', 'designinvento-elementor-widgets' ),
				],
				'default' => 'block',
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-title a' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_position',
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
			'title_position_top',
			[
				'label' => __( 'Category Position Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-title' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'title_position_left',
			[
				'label' => __( 'Category Position Left', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => __( 'Position From Left', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-title' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'title_position_bottom',
			[
				'label' => __( 'Category Position Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'Position From Bottom', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-title' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'title_position_right',
			[
				'label' => __( 'Category Position Right', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-title' => 'right: {{VALUE}}px;',
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
				'selector' => '{{WRAPPER}} .term-prefix-text',
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
					'{{WRAPPER}} .term-prefix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'prefix_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .term-prefix-text',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'prefix_css_filters',
				'selector' => '{{WRAPPER}} .term-prefix-text',
			]
		);
		$this->add_control(
			'prefix_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .term-prefix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'prefix_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .term-prefix-text',
			]
		);
		$this->add_responsive_control(
			"prefix_translateX",
				[
					'label' => esc_html__( 'Offset X', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item .advanced-terms-item-holder .term-prefix-text" => 'transform: translateX({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
		);

			$this->add_responsive_control(
			"prefix_translateY",
				[
					'label' => esc_html__( 'Offset Y', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item .advanced-terms-item-holder .term-prefix-text" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
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
					'{{WRAPPER}} .advanced-terms-item:hover .term-prefix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'prefix_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item:hover .term-prefix-text',
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
					'{{WRAPPER}} .advanced-terms-item:hover .term-prefix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'prefix_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item:hover .term-prefix-text',
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
					'{{WRAPPER}} .advanced-terms-item:hover .term-prefix-text' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			"prefix_translateX_hover",
				[
					'label' => esc_html__( 'Offset X', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}}:hover .advanced-terms-item .advanced-terms-item-holder .term-prefix-text" => 'transform: translateX({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
			"prefix_translateY_hover",
				[
					'label' => esc_html__( 'Offset Y', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}}:hover .advanced-terms-item .advanced-terms-item-holder .term-prefix-text" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);
		$this->add_responsive_control(
			"prefix_transition_hover",
				[
					'label' => esc_html__( 'Transition Speed', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px' ],
					'range' => [
						'px' => [
							'min' => 100,
							'max' => 10000,
							'step' => 1,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item .advanced-terms-item-holder .term-prefix-text" => 'transition: all {{SIZE}}ms ease;',
					],
				]
		);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'prefix_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .term-prefix-text',
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
					'{{WRAPPER}} .term-prefix-text' => 'border-radius: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .term-prefix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .term-prefix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .term-prefix-text' => 'top: {{VALUE}}px;',
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
					'{{WRAPPER}} .term-prefix-text' => 'left: {{VALUE}}px;',
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
					'{{WRAPPER}} .term-prefix-text' => 'bottom: {{VALUE}}px;',
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
					'{{WRAPPER}} .term-prefix-text' => 'right: {{VALUE}}px;',
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
				'selector' => '{{WRAPPER}} .term-suffix-text',
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
					'{{WRAPPER}} .term-suffix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'suffix_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .term-suffix-text',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'suffix_css_filters',
				'selector' => '{{WRAPPER}} .term-suffix-text',
			]
		);
		$this->add_control(
			'suffix_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .term-suffix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'suffix_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .term-suffix-text',
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
					'{{WRAPPER}} .advanced-terms-item:hover .term-suffix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'suffix_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item:hover .term-suffix-text',
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
					'{{WRAPPER}} .advanced-terms-item:hover .term-suffix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'suffix_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-title',
			]
		);
		
		$this->add_control(
			'suffix_border_color_hover',
			array(
				'label' => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-terms-item:hover .term-suffix-text' => 'border-color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .term-suffix-text',
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
					'{{WRAPPER}} .term-suffix-text' => 'border-radius: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .term-suffix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .term-suffix-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .term-suffix-text' => 'top: {{VALUE}}px;',
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
					'{{WRAPPER}} .term-suffix-text' => 'left: {{VALUE}}px;',
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
					'{{WRAPPER}} .term-suffix-text' => 'bottom: {{VALUE}}px;',
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
					'{{WRAPPER}} .term-suffix-text' => 'right: {{VALUE}}px;',
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
				'selector' => '{{WRAPPER}} .advanced-terms-item-icon',
			]
		);
		$this->add_responsive_control(
			'icon__box_width',
			[
				'label' => esc_html__( 'SVG/Image Icon Box Width', 'designinvento-elementor-widgets' ),
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
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-icon' => 'width: {{SIZE}}{{UNIT}};'
				],
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
					'{{WRAPPER}} .advanced-terms-item-icon img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .advanced-terms-item-icon svg' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-terms-item-icon' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'icon_css_filters',
				'selector' => '{{WRAPPER}} .advanced-terms-item-icon',
			]
		);
		$this->add_control(
			'icon_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-terms-item-icon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item-icon',
			]
		);
		$this->add_responsive_control(
			"icon_translateX",
				[
					'label' => esc_html__( 'Offset X', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item-icon" => 'transform: translateX({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
		);

			$this->add_responsive_control(
			"icon_translateY",
				[
					'label' => esc_html__( 'Offset Y', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item-icon" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
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
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-icon' => 'color: {{VALUE}} !important',
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
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-icon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-icon',
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
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-icon' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			"icon_translateX_hover",
				[
					'label' => esc_html__( 'Offset X', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}}:hover .advanced-terms-item-icon" => 'transform: translateX({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
		);

			$this->add_responsive_control(
			"icon_translateY_hover",
				[
					'label' => esc_html__( 'Offset Y', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}}:hover .advanced-terms-item-icon" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);
		$this->add_responsive_control(
			"icon_transition_hover",
				[
					'label' => esc_html__( 'Transition Speed', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px' ],
					'range' => [
						'px' => [
							'min' => 100,
							'max' => 10000,
							'step' => 1,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item-icon" => 'transition: all {{SIZE}}ms ease;',
					],
					//'frontend_available' => true,
				]
		);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item-icon',
			]
		);
		/* $this->add_responsive_control(
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
					'{{WRAPPER}} .advanced-terms-item-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		); */
		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-terms-item-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-terms-item-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-terms-item-icon' => 'top: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-terms-item-icon' => 'left: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-terms-item-icon' => 'bottom: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-terms-item-icon' => 'right: {{VALUE}}px;',
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
				'selector' => '{{WRAPPER}} .advanced-terms-item-numbers',
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
					'{{WRAPPER}} .advanced-terms-item-numbers' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'counter_css_filters',
				'selector' => '{{WRAPPER}} .advanced-terms-item-numbers',
			]
		);
		$this->add_control(
			'counter_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-terms-item-numbers' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item-numbers',
			]
		);
		$this->add_responsive_control(
			"counter_translateX",
				[
					'label' => esc_html__( 'Offset X', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item-numbers" => 'transform: translateX({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
		);

			$this->add_responsive_control(
			"counter_translateY",
				[
					'label' => esc_html__( 'Offset Y', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item-numbers" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
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
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-numbers' => 'color: {{VALUE}} !important',
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
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-numbers' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-numbers',
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
					'{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-numbers' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			"counter_translateX_hover",
				[
					'label' => esc_html__( 'Offset X', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-numbers" => 'transform: translateX({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
		);

			$this->add_responsive_control(
			"counter_translateY_hover",
				[
					'label' => esc_html__( 'Offset Y', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range' => [
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'px' => [
							'min' => -1000,
							'max' => 1000,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-numbers" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);
		$this->add_responsive_control(
			"counter__transition_hover",
				[
					'label' => esc_html__( 'Transition Speed', 'designinvento-elementor-widgets' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px' ],
					'range' => [
						'px' => [
							'min' => 100,
							'max' => 10000,
							'step' => 1,
						],
					],
					'selectors' => [
						"{{WRAPPER}} .advanced-terms-item:hover .advanced-terms-item-numbers" => 'transition: all {{SIZE}}ms ease;',
					],
				]
		);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'counter_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-terms-item-numbers',
			]
		);
		$this->add_control(
			'counter_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-terms-item-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-terms-item-numbers' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'counter_display',
			[
				'label' => esc_html__( 'Display', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'block'  => esc_html__( 'Block', 'designinvento-elementor-widgets' ),
					'inline-block' => esc_html__( 'Inline Block', 'designinvento-elementor-widgets' ),
				],
				'default' => 'block',
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-numbers' => 'display: {{VALUE}};',
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
				'label' => __( 'Position Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-numbers' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position_left',
			[
				'label' => __( 'Position Left', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 0,
				'placeholder' => __( 'Position From Left', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-numbers' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position_bottom',
			[
				'label' => __( 'Position Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => __( 'Position From Bottom', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-numbers' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'counter_position_right',
			[
				'label' => __( 'Position Right', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .advanced-terms-item-numbers' => 'right: {{VALUE}}px;',
				],
			]
		);
		
		$this->end_controls_section();

	}

	public function taxonomy() {
		$get_taxonomies = get_object_taxonomies('dp_listing');
		$options = array();
		foreach ($get_taxonomies AS $taxonomy) {
				$options[$taxonomy] = $taxonomy;
		}
	
		return $options;
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$instance = array(
				'parent' => '',
				'depth' => 1,
				'columns' => 1,
				'count' => $settings['count'],
				'cat_style' => 'advanced-terms',
				'prefix_text' => $settings['prefix_text'],
				'suffix_text' => $settings['suffix_text'],
				'wrapper_position' => $settings['wrapper_position'],
				'title_position' => $settings['title_position'],
				'counter_position' => $settings['counter_position'],
				'prefix_position' => $settings['prefix_position'],
				'suffix_position' => $settings['suffix_position'],
				'icon_type' => $settings['icon_type'],
				'icon' => $settings['icon'],
				'icon_image' => $settings['icon_image'],
				'icon_position' => $settings['icon_position'],
				'hover_animation' => $settings['hover_animation'],
				'icon_hover_animation' => $settings['icon_hover_animation'],
				'enable_box_link' => $settings['enable_box_link'],
				'count_with_text' => $settings['count_with_text'],
				'count_custom_text' => $settings['count_custom_text'],
				'enable_content_wrapper' => $settings['enable_content_wrapper'],
				'layout' => $settings['layout']
		);
		$instance['tax'] = $settings['terms_taxonomy'];
		$instance['max_subterms'] = 0;
		$instance['exact_terms'] = [$settings['terms_'.$settings['terms_taxonomy']]];
		$directorypress_handler = new DirectoryPress_Terms($instance);
		echo '<div class="directorypress-elementor-advanced-terms-widget clearfix">';
			echo $directorypress_handler->display();
			
		echo '</div>';
	}

}