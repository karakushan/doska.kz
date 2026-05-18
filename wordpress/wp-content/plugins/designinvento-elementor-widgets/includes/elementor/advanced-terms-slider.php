<?php
/**
 * @since 1.0.0
 */
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
class Pacz_Elementor_Advanced_Terms_Slider_Widget extends \Elementor\Widget_Base {
	
	public $selected_taxonomy = 'directorypress-category';
	public $data;
	
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
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
		return 'advanced-term-slider';
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
		return __( 'Advanced Terms Slider', 'designinvento-elementor-widgets' );
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
	
	public function get_script_depends() {
		return [ 'dew_core_frontend' ];
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
		
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Global Setting', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		//print_r($this->get_data()['settings']['terms_taxonomy']);
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
		$this->add_control(
			'count',
			[
				'label' => __( 'Listing Count', 'designinvento-elementor-widgets' ), 
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'designinvento-elementor-widgets' ),
				'label_off' => esc_html__( 'Off', 'designinvento-elementor-widgets' ),
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
					'{{WRAPPER}} .advanced-term-slider-item' => 'min-height: {{SIZE}}{{UNIT}}; width:100% !important',
					'{{WRAPPER}} .advanced-term-slider-item' => 'height: {{SIZE}}{{UNIT}}; width:100%',
				],
			]
		);
		$this->add_control(
			'enable_box_link',
			[
				'label' => esc_html__( 'Enable Box Link', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'designinvento-elementor-widgets' ),
				'label_off' => esc_html__( 'Off', 'designinvento-elementor-widgets' ),
				'default' => 1,
			]
		);
		$this->end_controls_section(); 
		
		// content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Items', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$item = new \Elementor\Repeater();
		$item->add_control(
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
			$item->add_control(
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
		$item->add_responsive_control(
			'prefix_text',
			[
				'label' => esc_html__( 'Prefix', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 2,
				'description' => esc_html__( 'Add a Prefix text applicable to custom style only', 'designinvento-elementor-widgets' ),
				'placeholder' => esc_html__( 'Type here', 'designinvento-elementor-widgets' ),
			]
		);
		$item->add_responsive_control(
			'suffix_text',
			[
				'label' => esc_html__( 'Suffix', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 2,
				'description' => esc_html__( 'Add a Suffix text  applicable to custom style only', 'designinvento-elementor-widgets' ),
				'placeholder' => esc_html__( 'Type here', 'designinvento-elementor-widgets' ),
			]
		);
		
		$item->add_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .advanced-term-slider-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$item->add_control(
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
		$item->add_control(
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
		$item->add_control(
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
		// start tab for content
		$item->start_controls_tabs(
            'item_tabs'
        );

		// start normal tab
        $item->start_controls_tab(
            'item_normal',
            [
                'label' => esc_html__( 'Normal', 'designinvento-elementor-widgets' ),
            ]
        );
		$item->add_control(
			'item_background_title',
			[
				'label' => __( 'Background', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$item->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => __( 'Background', 'designinvento-elementor-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .advanced-term-slider-item',
			]
		);
		$item->add_control(
			'item_shadow_title',
			[
				'label' => __( 'Background', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$item->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .advanced-term-slider-item',
			]
		);
        $item->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => esc_html__( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .advanced-term-slider-item',
			]
		);
		$item->end_controls_tab();
		// end normal tab

		//start hover tab
		$item->start_controls_tab(
            'item_hover',
            [
                'label' => esc_html__( 'Hover', 'designinvento-elementor-widgets' ),
            ]
        );
		$item->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background_hover',
				'label' => __( 'Background', 'designinvento-elementor-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .advanced-term-slider-item:hover',
			]
		);
        $item->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border_hover',
				'label' => esc_html__( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .advanced-term-slider-item:hover',
			]
		);

		$item->end_controls_tab();
		//end hover tab

		$item->end_controls_tabs();


		// set social icon add new control
        $this->add_control(
            'add_items',
            [
                'label' => esc_html__('Add Item', 'designinvento-elementor-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $item->get_controls(),
               // 'title_field' => '{{{ item_label }}}',

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
		$this->add_responsive_control(
			'items',
			[
				'label' => __( 'Items Per Slide', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				//'label_block' => true,
				'min' => 1,
				'max' => 20,
				'step' => 1,
				'default' => 3,
			]
		);
		$this->add_responsive_control(
			'items_to_scroll',
			[
				'label' => __( 'Items to scroll Per Slide', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 1,
				'max' => 20,
				'step' => 1,
				'default' => 1,
			]
		);
		$this->add_control(
			'slide_speed',
			[
				'label' => __( 'Slide Speed', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
			]
		);
		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __( 'Spacing', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => 0,
					'bottom' => 0,
					'left' => 15,
					'right' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .slick-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .advanced-term-slider-item-wrapper' => 'margin-left: -{{LEFT}}{{UNIT}};margin-right: -{{RIGHT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'designinvento-elementor-widgets' ), 
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'designinvento-elementor-widgets' ),
				'label_off' => esc_html__( 'Off', 'designinvento-elementor-widgets' ),
				'default' => 1,
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
			]
		);
		$this->add_control(
			'loop',
			[
				'label' => __( 'Loop', 'designinvento-elementor-widgets' ), 
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'designinvento-elementor-widgets' ),
				'label_off' => esc_html__( 'Off', 'designinvento-elementor-widgets' ),
				'default' => 0,
			]
		);
		$this->add_responsive_control(
			'arrows',
			[
				'label' => __( 'Navigation', 'designinvento-elementor-widgets' ), 
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'designinvento-elementor-widgets' ),
				'label_off' => esc_html__( 'Off', 'designinvento-elementor-widgets' ),
				'default' => 0,
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
		
		// Style tab and section
		$this->start_controls_section(
			'style_section',
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
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-title a',
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
					'{{WRAPPER}} .advanced-term-slider-item-title a, .advanced-term-slider-item-title a .category-icon-wrapper' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-title a',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'title_css_filters',
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-title a',
			]
		);
		$this->add_control(
			'background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-term-slider-item-title a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-title',
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
						"{{WRAPPER}} .advanced-term-slider-item-title" => 'transform: translateX({{SIZE}}{{UNIT}});',
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
						"{{WRAPPER}} .advanced-term-slider-item-title" => 'transform: translateY({{SIZE}}{{UNIT}});',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-title a' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-title a',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-title a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'title_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-title',
			]
		);
		
		$this->add_control(
			'title_border_color_hover',
			array(
				'label' => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-title a' => 'border-color: {{VALUE}};',
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
						"{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-title" => 'transform: translateX({{SIZE}}{{UNIT}});',
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
						"{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-title" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-title a',
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
					'{{WRAPPER}} .advanced-term-slider-item-title a' => 'border-radius: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-term-slider-item-title a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-term-slider-item-title a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-term-slider-item-title' => 'top: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-title' => 'left: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-title' => 'bottom: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-title' => 'right: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-title a',
					'{{WRAPPER}} .category-style7 .directorypress-category-item .directorypress-category-item-holder .advanced-term-slider-item-title a',
				], */
				'selector' => '{{WRAPPER}} .term-prefix-text, {{WRAPPER}} .category-style-custom .directorypress-category-item .directorypress-category-item-holder .advanced-term-slider-item-title a',
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
						"{{WRAPPER}} .advanced-term-slider-item .advanced-term-slider-item-holder .advanced-term-slider-item-title" => 'transform: translateX({{SIZE}}{{UNIT}});',
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
						"{{WRAPPER}} .advanced-term-slider-item .advanced-term-slider-item-holder .advanced-term-slider-item-title" => 'transform: translateY({{SIZE}}{{UNIT}});',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .term-prefix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'prefix_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item:hover .term-prefix-text',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .term-prefix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'prefix_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item:hover .term-prefix-text',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .term-prefix-text' => 'border-color: {{VALUE}};',
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
						"{{WRAPPER}}:hover .advanced-term-slider-item .advanced-term-slider-item-holder .advanced-term-slider-item-title" => 'transform: translateX({{SIZE}}{{UNIT}});',
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
						"{{WRAPPER}}:hover .advanced-term-slider-item .advanced-term-slider-item-holder .advanced-term-slider-item-title" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
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
				/* 'selectors' => [
					'{{WRAPPER}} .advanced-term-slider-item-title a',
					'{{WRAPPER}} .category-style7 .directorypress-category-item .directorypress-category-item-holder .advanced-term-slider-item-title a',
				], */
				'selector' => '{{WRAPPER}} .term-suffix-text, {{WRAPPER}} .category-style-custom .directorypress-category-item .directorypress-category-item-holder .advanced-term-slider-item-title a',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .term-suffix-text' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'suffix_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item:hover .term-suffix-text',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .term-suffix-text' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'suffix_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-title',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .term-suffix-text' => 'border-color: {{VALUE}};',
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
				/* 'selectors' => [
					'{{WRAPPER}} .advanced-term-slider-item-title a',
					'{{WRAPPER}} .category-style7 .directorypress-category-item .directorypress-category-item-holder .advanced-term-slider-item-title a',
				], */
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-icon',
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
					'{{WRAPPER}} .advanced-term-slider-item-icon img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .advanced-term-slider-item-icon svg' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-term-slider-item-icon' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'icon_css_filters',
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-icon',
			]
		);
		$this->add_control(
			'icon_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-term-slider-item-icon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-icon',
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
						"{{WRAPPER}} .advanced-term-slider-item-icon" => 'transform: translateX({{SIZE}}{{UNIT}});',
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
						"{{WRAPPER}} .advanced-term-slider-item-icon" => 'transform: translateY({{SIZE}}{{UNIT}});',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-icon' => 'color: {{VALUE}} !important',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-icon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-icon',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-icon' => 'border-color: {{VALUE}};',
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
						"{{WRAPPER}}:hover .advanced-term-slider-item-icon" => 'transform: translateX({{SIZE}}{{UNIT}});',
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
						"{{WRAPPER}}:hover .advanced-term-slider-item-icon" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-icon',
			]
		);
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
					'{{WRAPPER}} .advanced-term-slider-item-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-term-slider-item-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-term-slider-item-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-term-slider-item-icon' => 'top: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-icon' => 'left: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-icon' => 'bottom: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-icon' => 'right: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-title a',
					'{{WRAPPER}} .category-style7 .directorypress-category-item .directorypress-category-item-holder .advanced-term-slider-item-title a',
				], */
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-numbers, {{WRAPPER}} .category-style-custom .directorypress-category-item .directorypress-category-item-holder .advanced-term-slider-item-title a',
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
					'{{WRAPPER}} .advanced-term-slider-item-numbers' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'counter_css_filters',
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-numbers',
			]
		);
		$this->add_control(
			'counter_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .advanced-term-slider-item-numbers' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-numbers',
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
						"{{WRAPPER}} .advanced-term-slider-item-numbers" => 'transform: translateX({{SIZE}}{{UNIT}});',
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
						"{{WRAPPER}} .advanced-term-slider-item-numbers" => 'transform: translateY({{SIZE}}{{UNIT}});',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-numbers' => 'color: {{VALUE}} !important',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-numbers' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-numbers',
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
					'{{WRAPPER}} .advanced-term-slider-item:hover .advanced-term-slider-item-numbers' => 'border-color: {{VALUE}};',
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
						"{{WRAPPER}} .advanced-term-slider-item-holder:hover .advanced-term-slider-item-numbers" => 'transform: translateX({{SIZE}}{{UNIT}});',
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
						"{{WRAPPER}} .advanced-term-slider-item-holder:hover .advanced-term-slider-item-numbers" => 'transform: translateY({{SIZE}}{{UNIT}});',
					],
					'frontend_available' => true,
				]
			);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'counter_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .advanced-term-slider-item-numbers',
			]
		);
		$this->add_control(
			'counter_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .advanced-term-slider-item-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-term-slider-item-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-term-slider-item-numbers' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .advanced-term-slider-item-numbers' => 'top: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-numbers' => 'left: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-numbers' => 'bottom: {{VALUE}}px;',
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
					'{{WRAPPER}} .advanced-term-slider-item-numbers' => 'right: {{VALUE}}px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		// Slider Arrows
		$this->start_controls_section(
			'slider_arrow_section',
			[
				'label' => __( 'Slider Arrows', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'slider_arrow_width',
			[
				'label' => esc_html__( 'Width', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 48,
				],
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_height',
			[
				'label' => esc_html__( 'Height', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 48,
				],
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'slider_arrow_icon_pre',
			[
				'label' => __( 'Previous Arrow Icon', 'designinvento-elementor-widgets' ),
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
				'label' => __( 'Next Arrow Icon', 'designinvento-elementor-widgets' ),
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
				'label' => __( 'Title Typography', 'designinvento-elementor-widgets' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .pacz-slider-arrow-pre, {{WRAPPER}} .pacz-slider-arrow-next',
			]
		);
		$this->start_controls_tabs( 'slider_arrow_style' );

		$this->start_controls_tab(
			'slider_arrow_field_normal',
			array(
				'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'slider_arrow_color',
			[
				'label' => __( 'Icon Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'slider_arrow_css_filters',
				'selector' => '{{WRAPPER}} .pacz-slider-arrow-pre, {{WRAPPER}} .pacz-slider-arrow-next',
			]
		);
		$this->add_control(
			'slider_arrow_background_color',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'slider_arrow_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .pacz-slider-arrow-pre, {{WRAPPER}} .pacz-slider-arrow-next',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'slider_arrow_field_hover',
			array(
				'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
			)
		);

		$this->add_control(
			'slider_arrow_color_hover',
			[
				'label' => __( 'Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-pre:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pacz-slider-arrow-next:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'slider_arrow_background_color_hover',
			array(
				'label' => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pacz-slider-arrow-pre:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pacz-slider-arrow-next:hover' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'slider_arrow_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .pacz-slider-arrow-pre:hover, {{WRAPPER}} .pacz-slider-arrow-next:hover',
			]
		);
		
		$this->add_control(
			'slider_arrow_border_color_hover',
			array(
				'label' => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
				'{{WRAPPER}} .pacz-slider-arrow-pre:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pacz-slider-arrow-next:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'slider_arrow_border',
				'label' => __( 'Border', 'designinvento-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .pacz-slider-arrow-pre, {{WRAPPER}} .pacz-slider-arrow-next',
			]
		);
		$this->add_control(
			'slider_arrow_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_padding',
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
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_margin',
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
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_position',
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
			'slider_pre_arrow_position_top',
			[
				'label' => __( 'Previous Arrow Position Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_left',
			[
				'label' => __( 'Previous Arrow Position Left', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Left', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_bottom',
			[
				'label' => __( 'Previous Arrow Position Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Bottom', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_right',
			[
				'label' => __( 'Previous Arrow Position Right', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-pre' => 'right: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_top',
			[
				'label' => __( 'Next Arrow Position Top', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_left',
			[
				'label' => __( 'Next Arrow Position Left', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Left', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_bottom',
			[
				'label' => __( 'Next Arrow Position Bottom', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Bottom', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_right',
			[
				'label' => __( 'Next Arrow Position Right', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'designinvento-elementor-widgets' ),
				'selectors' => [
					'{{WRAPPER}} .pacz-slider-arrow-next' => 'right: {{VALUE}}px;',
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
	protected function selected_taxonomy() {
		$this->selected_taxonomy = $this->get_data(['settings'], ['terms_taxonomy']);
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		$desktop = $settings['items']; // default name is always desktop
		$tablet = ($settings['items_tablet'])? $settings['items_tablet'] :$settings['items'] ; // _tablet is added to the tablet value
		$mobile = ($settings['items_mobile'])? $settings['items_mobile'] :$settings['items'] ;  // _mobile is added to the _mobile value
		
		$attrs = 'data-items="'. $desktop .'"';
		$attrs .= ' data-items-tablet="'. $tablet .'"';
		$attrs .= ' data-items-mobile="'. $mobile .'"';
		$attrs .= ' data-slide-to-scroll="'. $settings['items_to_scroll'] .'"';
		$attrs .= ' data-slide-speed="'. $settings['slide_speed'] .'"';
		$attrs .= ($settings['autoplay'])? ' data-autoplay="true"' : ' data-autoplay="false"';
		$attrs .= ' data-center-padding=""';
		$attrs .= ' data-center="false"';
		$attrs .= ' data-autoplay-speed="'. $settings['autoplay_speed'] .'"';
		$attrs .= ($settings['loop'])? ' data-loop="true"' : ' data-loop="false"';
		$attrs .= ' data-list-margin="30"';
		$attrs .= ($settings['arrows'])? ' data-arrow="true"': ' data-arrow="false"';
		$attrs .= ' data-prev-arrow="'. $settings['slider_arrow_icon_pre']['value'] .'"';
		$attrs .= ' data-next-arrow="'. $settings['slider_arrow_icon_next']['value'] .'"';
		$attrs .= ' data-arrow-postion ="'. $settings['slider_arrow_position'] .'"';
		
		echo '<div class="category-style-advanced-wrapper pacz-dp-slick-carousel" '. $attrs .'>';
		foreach ($settings['add_items'] as $key => $item){
			//$terms_control = ''. 
		$instance = array(
				'parent' => '',
				'depth' => 1,
				'columns' => 1,
				'count' => $settings['count'],
				//'subcategories' => '',
				//'terms' => $item['terms'],
				'exact_terms' => [$item['terms_'.$item['terms_taxonomy']]],
				'cat_style' => 'advanced-term-slider',
				'prefix_text' => $item['prefix_text'],
				'suffix_text' => $item['suffix_text'],
				'title_position' => $settings['title_position'],
				'counter_position' => $settings['counter_position'],
				'prefix_position' => $settings['prefix_position'],
				'suffix_position' => $settings['suffix_position'],
				'icon_type' => $item['icon_type'], //$settings['icon'],
				'icon' => $item['icon'],
				'icon_image' => $item['icon_image'],
				'icon_position' => $settings['icon_position'],
				//'entrance_animation' => $settings['entrance_animation'],
				'hover_animation' => $settings['hover_animation'],
				'enable_box_link' => $settings['enable_box_link'],
				'count_with_text' => $settings['count_with_text'],
				'count_custom_text' => $settings['count_custom_text'],
				'enable_content_wrapper' => $settings['enable_content_wrapper'],
				'wrapper_position' => $settings['wrapper_position'],
				'layout' => $settings['layout']
				
		);
		$instance['tax'] = $item['terms_taxonomy'];
		$instance['max_subterms'] = 0;
		
		//$instance['exact_terms'] = [$item['terms']];
		//$instance['item_id'] = $item[ '_id' ];
		$directorypress_handler = new DirectoryPress_Terms($instance);
		echo '<div class="slick-slide elementor-repeater-item-'. $item[ '_id' ] .' clearfix">';
			echo $directorypress_handler->display();
		echo '</div>';	
		
	}
	echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				//directorypress_slik_init();
				pacz_el_widgets_slik_init();				
			} )( jQuery );
		</script>';
		};
	}

}