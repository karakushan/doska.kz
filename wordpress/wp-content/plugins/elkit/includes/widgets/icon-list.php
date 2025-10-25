<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
//use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor icon list widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Elkit_Icon_List_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve icon list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'elkit-icon-list';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve icon list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Icon List', 'elkit' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve icon list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'icon list', 'icon', 'list' ];
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
		return [ 'elkit' ];
	}

	/**
	 * Register icon list widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon List', 'elkit' ),
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'Layout', 'elkit' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'traditional',
				'options' => [
					'traditional' => [
						'title' => esc_html__( 'Default', 'elkit' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => esc_html__( 'Inline', 'elkit' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'render_type' => 'template',
				'classes' => 'elementor-control-start-end',
				'style_transfer' => true,
				'prefix_class' => 'elementor-icon-list--layout-',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'item_title',
			[
				'label' => esc_html__( 'Title', 'elkit' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Item Title', 'elkit' ),
				'default' => esc_html__( 'List Item', 'elkit' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$repeater->add_control(
			'item_content',
			[
				'label' => esc_html__( 'Content', 'elkit' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'placeholder' => esc_html__( 'Item Content', 'elkit' ),
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'item_icon',
			[
				'label' => esc_html__( 'Icon', 'elkit' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-check',
					'library' => 'fa-solid',
				]
			]
		);

		$this->add_control(
			'list_items',
			[
				'label' => esc_html__( 'Items', 'elkit' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_title' => esc_html__( 'List Item #1', 'elkit' ),
						'item_icon' => [
							'value' => 'fas fa-check',
							'library' => 'fa-solid',
						],
					],
					[
						'item_title' => esc_html__( 'List Item #2', 'elkit' ),
						'item_icon' => [
							'value' => 'fas fa-times',
							'library' => 'fa-solid',
						],
					],
					[
						'item_title' => esc_html__( 'List Item #3', 'elkit' ),
						'item_icon' => [
							'value' => 'fas fa-dot-circle',
							'library' => 'fa-solid',
						],
					],
				],
				'title_field' => '{{{ elementor.helpers.renderIcon( this, item_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ item_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_list_items',
			[
				'label' => esc_html__( 'List Item', 'elkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __( 'Padding', 'elkit' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-items .elkit-icon-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'space_between',
			[
				'label' => esc_html__( 'Space Between', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-items .elkit-icon-list-item' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elkit-icon-list-items .elkit-icon-list-item:last-child' => 'margin-bottom: 0',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label' => esc_html__( 'Alignment', 'elkit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elkit' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elkit' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elkit' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => __( 'Border', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-icon-list-item:not(:last-child)',
			]
		);

		$this->end_controls_section();

		// Icon styles
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => __( 'Icon', 'elkit' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__( 'Box Width', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-icon' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elkit-icon-list-item .list_item_content_holder' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
				],
			]
		);

        $this->add_responsive_control(
			'icon_height',
			[
				'label' => esc_html__( 'Box Height', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-icon' => 'Height: {{SIZE}}{{UNIT}};line-height:  {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elkit-icon-list-icon i' => 'line-height:  {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elkit-icon-list-icon svg' => 'max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elkit-icon-list-icon img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'icon_style' );

		$this->start_controls_tab(
			'icon_field_normal',
			array(
				'label' => __( 'Normal', 'elkit' ),
			)
		);
		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elkit-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'icon_css_filters',
				'selector' => '{{WRAPPER}} .elkit-icon-list-icon',
			]
		);
		$this->add_control(
			'icon_background_color',
			array(
				'label' => __( 'Background Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elkit-icon-list-icon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-icon-list-icon',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_field_hover',
			array(
				'label' => __( 'Hover', 'elkit' ),
			)
		);
		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Hover', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-item:hover .elkit-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elkit-icon-list-item:hover .elkit-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'elkit' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->add_control(
			'icon_background_color_hover',
			array(
				'label' => __( 'Background Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elkit-icon-list-item:hover .elkit-icon-list-icon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-icon-list-item:hover .elkit-icon-list-icon',
			]
		);
		
		$this->add_control(
			'icon_border_color_hover',
			array(
				'label' => __( 'Border Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elkit-icon-list-item:hover .elkit-icon-list-icon' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => __( 'Border', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-icon-list-icon',
			]
		);
		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'elkit' ),
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
					'{{WRAPPER}} .elkit-icon-list-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		
		// Counter styles
		$this->start_controls_section(
			'counter_style_section',
			[
				'label' => __( 'Counter', 'elkit' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'counter_width',
			[
				'label' => esc_html__( 'Box Width', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-index' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'counter_height',
			[
				'label' => esc_html__( 'Box Height', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-index' => 'Height: {{SIZE}}{{UNIT}};line-height:  {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'counter_size',
			[
				'label' => esc_html__( 'Font Size', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-index' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'counter_style' );

		$this->start_controls_tab(
			'counter_field_normal',
			array(
				'label' => __( 'Normal', 'elkit' ),
			)
		);
		$this->add_control(
			'counter_color',
			[
				'label' => esc_html__( 'Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-index' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'counter_css_filters',
				'selector' => '{{WRAPPER}} .elkit-icon-list-index',
			]
		);
		$this->add_control(
			'counter_background_color',
			array(
				'label' => __( 'Background Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elkit-icon-list-index' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-icon-list-index',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'counter_field_hover',
			array(
				'label' => __( 'Hover', 'elkit' ),
			)
		);
		$this->add_control(
			'counter_color_hover',
			[
				'label' => esc_html__( 'Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-item:hover .elkit-icon-list-index' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'counter_background_color_hover',
			array(
				'label' => __( 'Background Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elkit-icon-list-item:hover .elkit-icon-list-index' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-icon-list-item:hover .elkit-icon-list-index',
			]
		);
		
		$this->add_control(
			'counter_border_color_hover',
			array(
				'label' => __( 'Border Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elkit-icon-list-item:hover .elkit-icon-list-index' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'counter_border',
				'label' => __( 'Border', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-icon-list-index',
			]
		);
		$this->add_responsive_control(
			'counter_border_radius',
			[
				'label' => __( 'Border Radius', 'elkit' ),
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
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-index' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_item_title_style',
			[
				'label' => esc_html__( 'Title', 'elkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_title_color',
			[
				'label' => esc_html__( 'Title Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-list-item-title' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
			]
		);

		$this->add_control(
			'item_title_color_hover',
			[
				'label' => esc_html__( 'Hover', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-item:hover .elkit-list-item-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_title_indent',
			[
				'label' => esc_html__( 'Text Indent', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-list-item-title' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_title_typography',
				'selector' => '{{WRAPPER}} .elkit-list-item-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'item_title_shadow',
				'selector' => '{{WRAPPER}} .elkit-list-item-title',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_item_content_style',
			[
				'label' => esc_html__( 'Content', 'elkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_content_color',
			[
				'label' => esc_html__( 'Title Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-list-item-content' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
			]
		);

		$this->add_control(
			'item_content_color_hover',
			[
				'label' => esc_html__( 'Hover', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-icon-list-item:hover .elkit-list-item-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_content_indent',
			[
				'label' => esc_html__( 'Text Indent', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-list-item-content' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_content_typography',
				'selector' => '{{WRAPPER}} .elkit-list-item-content',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render icon list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<ul class="elkit-icon-list-items">
			<?php foreach ( $settings['list_items'] as $index => $item ) : ?>
				<li class="elkit-icon-list-item clearfix">
					<?php if ( !empty( $item['item_icon']['value'] )) : ?>
						<div class="elkit-icon-list-icon">
							<span class="elkit-icon-list-index"><?php echo $index+1; ?></span>
							<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
					<?php endif; ?>
					<div class="list_item_content_holder">
						<div class="elkit-list-item-title">
							<?php $this->print_unescaped_setting( 'item_title', 'list_items', $index ); ?>
						</div>
						<div class="elkit-list-item-content">
							<?php $this->print_unescaped_setting( 'item_content', 'list_items', $index ); ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}
