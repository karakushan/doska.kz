<?php
/**
 * Elementor Classes.
 *
 * @package header-footer-elementor
 */

namespace HFB\WidgetsManager\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Control_Media;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * HFB Cart Widget
 *
 * @since 1.4.0
 */
class Contact_Info extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'hfb-contact-info';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Contact Info', 'header-footer-builder' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'hfb-icon-menu-cart';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'hfb-widgets' ];
	}

	protected function register_controls(){

        $this->content_options();
		$this->container_styling();
		$this->content_styling();
		$this->label_styling();
		$this->icon_styling();

    }
	protected function content_options() {
		$this->start_controls_section(
            'hfb_header_info',
            [
                'label' => esc_html__('Header Info', 'header-footer-builder'),
            ]
        );
		$this->add_control(
			'icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'header-footer-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'profile_photo',
				'options' => [
					'image'  => esc_html__( 'Image', 'header-footer-builder' ),
					'icon' => esc_html__( 'Font/SVG Icon', 'header-footer-builder' ),
					'disable' => esc_html__( 'Disable', 'header-footer-builder' ),
				],
				'default' => 'icon',
			]
        );
       // $headerinfogroup = new \Elementor\Repeater();
        $this->add_control(
            'icon',
            [
                'label'         => esc_html__('Icon', 'header-footer-builder'),
                'label_block'   => true,
                'type'          => Controls_Manager::ICONS,
				'condition' => [
                    'icon_type' => 'icon',
                ]
            ]
        );
		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'plugin-domain' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
                    'icon_type' => 'image',
                ]
			]
		);
		
        $this->add_control(
            'label',
            [
                'label' => esc_html__('Label', 'header-footer-builder'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
		$this->add_control(
            'text',
            [
                'label' => esc_html__('Text', 'header-footer-builder'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => '00 777 999 0000',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'header-footer-builder' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://designinvento.net/', 'header-footer-builder' ),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();
	}
	protected function container_styling() {
		$this->start_controls_section(
            'container_style_section',
            [
                'label' => esc_html__( 'Container', 'header-footer-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'container_margin',
			[
				'label' => esc_html__( 'Margin', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-contact-info-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-contact-info-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'header-footer-builder' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
				'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'direction',
            [
                'label' => esc_html__( 'Direction', 'header-footer-builder' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'ltr' => [
                        'title' => esc_html__( 'Left to right', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'rtl' => [
                        'title' => esc_html__( 'Right to left', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
				'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container' => 'direction: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_box_border',
				'label' => esc_html__( 'Border', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} .hfb-contact-info-container',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'header-footer-builder' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
		
		// start tab for content
		$this->start_controls_tabs(
            'container_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'container_style_normal',
            [
                'label' => esc_html__( 'Normal', 'header-footer-builder' ),
            ]
        );
		$this->add_control(
            'container_bg',
            [
                'label'     => esc_html__( 'Background Color', 'header-footer-builder' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'container_style_hover',
            [
                'label' => esc_html__( 'Hover', 'header-footer-builder' ),
            ]
        );
		$this->add_control(
            'container_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'header-footer-builder' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'container_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		//end  hover tab
		
		
		$this->end_controls_tabs();
        $this->end_controls_section();
	}
	protected function content_styling() {
		$this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__( 'Content', 'header-footer-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'content_margin',
			[
				'label' => esc_html__( 'Margin', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-contact-info-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-contact-info-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_box_border',
				'label' => esc_html__( 'Border', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} .hfb-contact-info-content',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'content_box_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'header-footer-builder' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		// Display design
		 $this->add_responsive_control(
            'content_display',
            [
                'label' => esc_html__( 'Display', 'header-footer-builder' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'inline-block' => esc_html__( 'Inline Block', 'header-footer-builder' ),
                    'block' => esc_html__( 'Block', 'header-footer-builder' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-content' => 'display: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => esc_html__( 'Typography', 'header-footer-builder' ),
                'selector' => '{{WRAPPER}} .hfb-contact-info-content, {{WRAPPER}} .hfb-contact-info-content a',
            ]
        );
		// start tab for content
		$this->start_controls_tabs(
            'content_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'content_style_normal',
            [
                'label' => esc_html__( 'Normal', 'header-footer-builder' ),
            ]
        );
		$this->add_control(
            'content_text_color',
            [
                'label' => esc_html__( 'Text Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-content, {{WRAPPER}} .hfb-contact-info-content a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_bg',
            [
                'label'     => esc_html__( 'Background Color', 'header-footer-builder' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'content_style_hover',
            [
                'label' => esc_html__( 'Hover', 'header-footer-builder' ),
            ]
        );
		$this->add_control(
            'content_text_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover .hfb-contact-info-content, {{WRAPPER}} .hfb-contact-info-container:hover .hfb-contact-info-content a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'header-footer-builder' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover .hfb-contact-info-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'content_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover .hfb-contact-info-content' => 'border-color: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		//end  hover tab
		
		
		$this->end_controls_tabs();
        

        $this->end_controls_section();
	}
	protected function label_styling() {
		$this->start_controls_section(
            'label_style_section',
            [
                'label' => esc_html__( 'Label', 'header-footer-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'label_margin',
			[
				'label' => esc_html__( 'Margin', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-contact-info-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'label_padding',
			[
				'label' => esc_html__( 'Padding', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-contact-info-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'label_box_border',
				'label' => esc_html__( 'Border', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} .hfb-contact-info-label',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'label_box_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'header-footer-builder' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		// Display design
		 $this->add_responsive_control(
            'label_display',
            [
                'label' => esc_html__( 'Display', 'header-footer-builder' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'inline-block' => esc_html__( 'Inline Block', 'header-footer-builder' ),
                    'block' => esc_html__( 'Block', 'header-footer-builder' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-label' => 'display: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label__typography',
                'label' => esc_html__( 'Typography', 'header-footer-builder' ),
                'selector' => '{{WRAPPER}} .hfb-contact-info-label',
            ]
        );
		
		// start tab for content
		$this->start_controls_tabs(
            'label_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'label_style_normal',
            [
                'label' => esc_html__( 'Normal', 'header-footer-builder' ),
            ]
        );
		$this->add_control(
            'label_bg',
            [
                'label'     => esc_html__( 'Background Color', 'header-footer-builder' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-label' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'label_color',
            [
                'label' => esc_html__( 'Label Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-label' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'label_style_hover',
            [
                'label' => esc_html__( 'Hover', 'header-footer-builder' ),
            ]
        );
		$this->add_control(
            'label_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'header-footer-builder' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover .hfb-contact-info-label' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'label_color_hover',
            [
                'label' => esc_html__( 'Label Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover  .hfb-contact-info-label' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'label_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover .hfb-contact-info-label' => 'border-color: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		//end  hover tab
		
		
		$this->end_controls_tabs();
		$this->end_controls_section();
	}
	protected function icon_styling() {
		$this->start_controls_section(
            'icon_style_section',
            [
                'label' => esc_html__( 'Icon', 'header-footer-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		// Display design
		 $this->add_responsive_control(
            'icon_display',
            [
                'label' => esc_html__( 'Display', 'header-footer-builder' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'inline-block' => esc_html__( 'Inline Block', 'header-footer-builder' ),
                    'block' => esc_html__( 'Block', 'header-footer-builder' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-icon' => 'display: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
			'icon_box_margin',
			[
				'label' => esc_html__( 'Icon Box Margin', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'top' => '',
					'right' => '10',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .hfb-contact-info-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};text-align:center;vertical-align:middle;',
				],
			]
        );
		$this->add_responsive_control(
			'icon_box_padding',
			[
				'label' => esc_html__( 'icon box Padding', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-contact-info-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
            'icon_box_size',
            [
                'label' => esc_html__( 'Icon Box Size', 'header-footer-builder' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'header-footer-builder' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .hfb-contact-info-icon svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
					'{{WRAPPER}} .hfb-contact-info-icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'icon_line_height',
            [
                'label' => esc_html__( 'Icon Line Height', 'header-footer-builder' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-icon' => 'line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_box_border',
				'label' => esc_html__( 'Icon Box Border', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} .hfb-contact-info-icon',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'icon_box_border_radius',
            [
                'label' => esc_html__( 'Icon Box Border radius', 'header-footer-builder' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		// start tab for content
		$this->start_controls_tabs(
            'icon_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'icon_style_normal',
            [
                'label' => esc_html__( 'Normal', 'header-footer-builder' ),
            ]
        );
		$this->add_responsive_control(
            'icon_box_bg_color',
            [
                'label' => esc_html__( 'Icon Box Background Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-contact-info-icon svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'icon_style_hover',
            [
                'label' => esc_html__( 'Hover', 'header-footer-builder' ),
            ]
        );
		$this->add_responsive_control(
            'icon_box_bg_color_hover',
            [
                'label' => esc_html__( 'Icon Box Background Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover .hfb-contact-info-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'icon_box_border_color_hover',
            [
                'label' => esc_html__( 'Icon Box Border Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover .hfb-contact-info-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'header-footer-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-contact-info-container:hover .hfb-contact-info-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-contact-info-container:hover .hfb-contact-info-icon svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		
		$this->end_controls_tab();
		//end  hover tab
		
		
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

    protected function render( ) {
        echo '<div class="hfb-contact-info">';
            $this->render_raw();
        echo '</div>';
    }

    protected function render_raw( ) {
        $settings = $this->get_settings_for_display();
         
		echo '<div class="hfb-contact-info-container">';
			if(!empty($settings['text'])){
				
				echo '<div class="hfb-contact-info-label">'. esc_html($settings['label']) .'</div>';
				if($settings['icon_type'] == 'icon' && $settings['icon']){
					echo '<div class="hfb-contact-info-icon">';
						Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
					echo '</div>';
				}elseif($settings['icon_type'] == 'image' && !empty($settings['image']['url'])){
					
					$this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
					$this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
					$this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );
					$this->add_render_attribute( 'image', 'class', 'hfb-contact-info-image' );
					echo '<div class="hfb-contact-info-icon">';
						echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'full', 'image' );
					echo '</div>';
				}
				echo '<div class="hfb-contact-info-content">';
					if ( ! empty( $settings['link']['url'] ) ){
						$this->add_link_attributes( 'link', $settings['link'] );
						echo '<a'. $this->get_render_attribute_string( 'link' ) .'>';
					}
					echo esc_html($settings['text']);
					if ( ! empty( $settings['link']['url'] ) ){
						echo '</a>';
					}
				echo '</div>';
			}
		echo '</div>';
    }
}
