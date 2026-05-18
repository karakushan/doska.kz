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
use Elementor\Group_Control_Image_Size;
/**
 * Elementor image box widget.
 *
 * Elementor widget that displays an image, a headline and a text.
 *
 * @since 1.0.0
 */
class Elkit_Testimonials_Widget extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
	}
	/**
	 * Get widget name.
	 *
	 * Retrieve image box widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'elkit-testimonials';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image box widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Testimonials', 'elkit' );
	}
	
	public function get_categories() {
		return [ 'elkit' ];
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image box widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-box';
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
		return [ 'image', 'testimonial', 'slider', 'partner' ];
	}
	
	public function get_script_depends() {
		return [ 'elkit-public' ];
	}

	/**
	 * Register image box widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_items',
			[
				'label' => __( 'Items', 'elkit' ),
			]
		);
		$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'elkit' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1' => 'Style 1',
					'2' => 'Style 2',
				],
				'default' => '1',
			]
		); 
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'image',
			[
				'label' => __( 'Thumbnail', 'elkit' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
			]
		);

		$repeater->add_control(
			'item_title',
			[
				'label' => __( 'Title', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Kevin Martin', 'elkit' ),
				'placeholder' => __( 'Enter your title', 'elkit' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'item_job',
			[
				'label' => __( 'Job', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Customer', 'elkit' ),
				'placeholder' => __( 'Enter your Job', 'elkit' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'item_content',
			[
				'label' => __( 'Content', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elkit' ),
				'placeholder' => __( 'Enter your description', 'elkit' ),
				'separator' => 'none',
				'rows' => 10,
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Website', 'elkit' ),
				'type' => \Elementor\Controls_Manager::URL,
				//'dynamic' => [
				//	'active' => true,
				//],
				'placeholder' => __( 'https://your-link.com', 'elkit' ),
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'ratting_image',
			[
				'label' => __( 'Ratting Image', 'elkit' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				//'dynamic' => [
				//	'active' => true,
				//],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'list_items',
			[
				'label' => esc_html__( 'Add Testimonial', 'elkit' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ item_title }}}',
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'elkit' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
			]
		); 

		$this->end_controls_section();
		// Slider
		$this->start_controls_section(
			'slider_section',
			[
				'label' => __( 'Slider', 'elkit' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'items',
			[
				'label' => __( 'Items Per Slide', 'elkit' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				//'label_block' => true,
				'min' => 1,
				'max' => 20,
				'step' => 1,
				'default' => 2,
			]
		);
		$this->add_responsive_control(
			'items_to_scroll',
			[
				'label' => __( 'Items to scroll Per Slide', 'elkit' ),
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
				'label' => __( 'Slide Speed', 'elkit' ),
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
				'label' => __( 'Spacing', 'elkit' ),
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
					'{{WRAPPER}} .elkit-slick-carousel' => 'margin-left: -{{LEFT}}{{UNIT}};margin-right: -{{RIGHT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'elkit' ), 
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'elkit' ),
				'label_off' => esc_html__( 'Off', 'elkit' ),
				'default' => 1,
			]
		);
		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'elkit' ),
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
				'label' => __( 'Loop', 'elkit' ), 
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'elkit' ),
				'label_off' => esc_html__( 'Off', 'elkit' ),
				'default' => 0,
			]
		);
		$this->add_responsive_control(
			'arrows',
			[
				'label' => __( 'Navigation', 'elkit' ), 
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'elkit' ),
				'label_off' => esc_html__( 'Off', 'elkit' ),
				'default' => 0,
			]
		);
		$this->add_control(
			'delay',
			[
				'label' => __( 'Delay', 'elkit' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_item_box_style',
			[
				'label' => __( 'Item Box', 'elkit' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'item_box_padding',
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
					'{{WRAPPER}} .elkit-testimonial-item-holder' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-testimonial-item-holder',
			]
		);
		$this->add_responsive_control(
			'item_box_border_radius',
			[
				'label' => __( 'Border Radius', 'elkit' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elkit-testimonial-item-holder' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->start_controls_tabs( 'item-box-colors' );

		$this->start_controls_tab( 'item-box-color-normal',
			[
				'label' => __( 'Normal', 'elkit' ),
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_box_background',
				'label' => __( 'Background', 'elkit' ),
				'description' => __( 'Background', 'elkit' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elkit-testimonial-item-holder',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-testimonial-item-holder',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'item-box-color-hover',
			[
				'label' => __( 'Hover', 'elkit' ),
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_box_hover_background',
				'label' => __( 'Background', 'elkit' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elkit-testimonial-item-holder:hover',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-testimonial-item-holder:hover',
			]
		);
		$this->add_control(
			'item_border_color_hover',
			array(
				'label' => __( 'Border Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elkit-testimonial-item-holder:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
		// content
		$this->start_controls_section(
			'section_item_content_style',
			[
				'label' => __( 'content', 'elkit' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'content_align',
			[
				'label' => __( 'Alignment', 'elkit' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elkit' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elkit' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elkit' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elkit' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-testimonial-description' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_content_margin',
			[
				'label' => __( 'Margin', 'elkit' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-testimonial-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'item_content_typography',
				'label' => __( 'Title Typography', 'elkit' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elkit-testimonial-description',
			]
		);
		$this->start_controls_tabs( 'item_content_style_tabs' );

		$this->start_controls_tab(
			'item_content_normal_tab',
			array(
				'label' => __( 'Normal', 'elkit' ),
			)
		);

		$this->add_control(
			'item_content_color',
			[
				'label' => __( 'Icon Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elkit-testimonial-description' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_content_hover_tab',
			array(
				'label' => __( 'Hover', 'elkit' ),
			)
		);

		$this->add_control(
			'item_content_color_hover',
			[
				'label' => __( 'Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slick-slide:hover .elkit-testimonial-description' => 'color: {{VALUE}}',
				],
			]
		);	
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		// author/client
		$this->start_controls_section(
			'section_item_client_style',
			[
				'label' => __( 'Client', 'elkit' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'client_align',
			[
				'label' => __( 'Clinet Name Alignment', 'elkit' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elkit' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elkit' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elkit' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elkit' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-testimonial-author' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'client_ratting_align',
			[
				'label' => __( 'Ratting Alignment', 'elkit' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elkit' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elkit' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elkit' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elkit' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-testimonial-ratting' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_cleint_margin',
			[
				'label' => __( 'Client Name/job Margin', 'elkit' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-testimonial-author' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_ratting_margin',
			[
				'label' => __( 'Client ratting Margin', 'elkit' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-testimonial-ratting' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'item_client_typography',
				'label' => __( 'Client Name Typography', 'elkit' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elkit-testimonial-title',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'item_client_job_typography',
				'label' => __( 'Client Job Typography', 'elkit' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .testimonial-author-job',
			]
		);
		$this->start_controls_tabs( 'item_client_style_tabs' );

		$this->start_controls_tab(
			'item_client_normal_tab',
			array(
				'label' => __( 'Normal', 'elkit' ),
			)
		);

		$this->add_control(
			'item_client_color',
			[
				'label' => __( 'client name Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elkit-testimonial-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'item_client_job_color',
			[
				'label' => __( 'job Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .testimonial-author-job' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_client_hover_tab',
			array(
				'label' => __( 'Hover', 'elkit' ),
			)
		);

		$this->add_control(
			'item_client_color_hover',
			[
				'label' => __( 'client name Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slick-slide:hover .elkit-testimonial-title' => 'color: {{VALUE}}',
				],
			]
		);	
		$this->add_control(
			'item_client_job_color_hover',
			[
				'label' => __( 'job Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slick-slide:hover .testimonial-author-job' => 'color: {{VALUE}}',
				],
			]
		);	
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		// Slider Arrows
		$this->start_controls_section(
			'slider_arrow_section',
			[
				'label' => __( 'Slider Arrows', 'elkit' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'slider_arrow_width',
			[
				'label' => esc_html__( 'Width', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_height',
			[
				'label' => esc_html__( 'Height', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'slider_arrow_icon_pre',
			[
				'label' => __( 'Previous Arrow Icon', 'elkit' ),
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
				'label' => __( 'Next Arrow Icon', 'elkit' ),
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
				'label' => __( 'Title Typography', 'elkit' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elkit-slider-arrow-pre, {{WRAPPER}} .elkit-slider-arrow-next',
			]
		);
		$this->start_controls_tabs( 'slider_arrow_style' );

		$this->start_controls_tab(
			'slider_arrow_field_normal',
			array(
				'label' => __( 'Normal', 'elkit' ),
			)
		);

		$this->add_control(
			'slider_arrow_color',
			[
				'label' => __( 'Icon Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'slider_arrow_css_filters',
				'selector' => '{{WRAPPER}} .elkit-slider-arrow-pre, {{WRAPPER}} .elkit-slider-arrow-next',
			]
		);
		$this->add_control(
			'slider_arrow_background_color',
			array(
				'label' => __( 'Background Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'slider_arrow_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-slider-arrow-pre, {{WRAPPER}} .elkit-slider-arrow-next',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'slider_arrow_field_hover',
			array(
				'label' => __( 'Hover', 'elkit' ),
			)
		);

		$this->add_control(
			'slider_arrow_color_hover',
			[
				'label' => __( 'Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-pre:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elkit-slider-arrow-next:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'slider_arrow_background_color_hover',
			array(
				'label' => __( 'Background Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elkit-slider-arrow-pre:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elkit-slider-arrow-next:hover' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'slider_arrow_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-slider-arrow-pre:hover, {{WRAPPER}} .elkit-slider-arrow-next:hover',
			]
		);
		
		$this->add_control(
			'slider_arrow_border_color_hover',
			array(
				'label' => __( 'Border Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
				'{{WRAPPER}} .elkit-slider-arrow-pre:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elkit-slider-arrow-next:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'slider_arrow_border',
				'label' => __( 'Border', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-slider-arrow-pre, {{WRAPPER}} .elkit-slider-arrow-next',
			]
		);
		$this->add_control(
			'slider_arrow_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_padding',
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
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_margin',
			[
				'label' => __( 'Margin', 'elkit' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_position',
			[
				'label' => esc_html__( 'Position', 'elkit' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'elkit' ),
					'absolute' => esc_html__( 'Absolute', 'elkit' ),
					'static' => esc_html__( 'Static', 'elkit' ),
				],
				'default' => 'relative',
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_top',
			[
				'label' => __( 'Previous Arrow Position Top', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'elkit' ),
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_left',
			[
				'label' => __( 'Previous Arrow Position Left', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Left', 'elkit' ),
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_bottom',
			[
				'label' => __( 'Previous Arrow Position Bottom', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Bottom', 'elkit' ),
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_right',
			[
				'label' => __( 'Previous Arrow Position Right', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'elkit' ),
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-pre' => 'right: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_top',
			[
				'label' => __( 'Next Arrow Position Top', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'elkit' ),
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_left',
			[
				'label' => __( 'Next Arrow Position Left', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Left', 'elkit' ),
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_bottom',
			[
				'label' => __( 'Next Arrow Position Bottom', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Bottom', 'elkit' ),
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_right',
			[
				'label' => __( 'Next Arrow Position Right', 'elkit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'elkit' ),
				'selectors' => [
					'{{WRAPPER}} .elkit-slider-arrow-next' => 'right: {{VALUE}}px;',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render image box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$settings = $this->get_settings_for_display();
		$desktop = $settings['items']; // default name is always desktop
		$tablet = (isset($settings['items_tablet']))? $settings['items_tablet'] :$settings['items'] ; // _tablet is added to the tablet value
		$mobile = (isset($settings['items_mobile']))? $settings['items_mobile'] :$settings['items'] ;  // _mobile is added to the _mobile value
		$attrs = 'data-items="'. $desktop .'"';
		$attrs .= 'data-items-tablet="'. $tablet .'"';
		$attrs .= 'data-items-mobile="'. $mobile .'"';
		$attrs .= 'data-slide-to-scroll="'. $settings['items_to_scroll'] .'"';
		$attrs .= 'data-slide-speed="'. $settings['slide_speed'] .'"';
		$attrs .= ($settings['autoplay'])? 'data-autoplay="true"' : 'data-autoplay="false"';
		$attrs .= 'data-center-padding=""';
		$attrs .= 'data-center="false"';
		$attrs .= 'data-autoplay-speed="'. $settings['autoplay_speed'] .'"';
		$attrs .= ($settings['loop'])? 'data-loop="true"' : 'data-loop="false"';
		$attrs .= 'data-list-margin="30"';
		$attrs .= ($settings['arrows'])? 'data-arrow="true"': 'data-arrow="false"';
		$attrs .= 'data-prev-arrow="'. $settings['slider_arrow_icon_pre']['value'] .'"';
		$attrs .= 'data-next-arrow="'. $settings['slider_arrow_icon_next']['value'] .'"';
		$attrs .= 'data-arrow-postion ="'. $settings['slider_arrow_position'] .'"';
		$heading_tag = \Elementor\Utils::validate_html_tag( $settings['title_tag'] );
		//$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $settings['title_tag'] ), $this->get_render_attribute_string( 'title' ), $title );
		echo '<div class="elkit-testimonials-wrapper">';
			if($settings['style'] == 2){
				echo '<div class="elkit-testimonia-slider-nav">';
					foreach ($settings['list_items'] as $key => $item){
						if ( ! empty( $item['image']['url'] ) ) {
							$this->add_render_attribute( 'image', 'src', $item['image']['url'] );
							$this->add_render_attribute( 'image', 'alt', \Elementor\Control_Media::get_image_alt( $item['image'] ) );
							$this->add_render_attribute( 'image', 'title', \Elementor\Control_Media::get_image_title( $item['image'] ) );

						}	
						echo '<div class="slide-item">';
							echo '<figure class="elkit-testimonial-thumbnail">';
								echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'image' );
							echo '</figure>';
						echo '</div>';				
					}
				echo '</div>';
			}
			echo '<div class="elkit-testimonials elkit-testimovial-style-'. $settings['style'] .' elkit-slick-carousel" '. $attrs .'>';
				foreach ($settings['list_items'] as $key => $item){
					if ( ! empty( $item['link']['url'] ) ) {
						$this->add_link_attributes( 'link', $item['link'] );
					}

					if ( ! empty( $item['image']['url'] ) ) {
						$this->add_render_attribute( 'image', 'src', $item['image']['url'] );
						$this->add_render_attribute( 'image', 'alt', \Elementor\Control_Media::get_image_alt( $item['image'] ) );
						$this->add_render_attribute( 'image', 'title', \Elementor\Control_Media::get_image_title( $item['image'] ) );

					}
					if ( ! empty( $item['ratting_image']['url'] ) ) {
						$this->add_render_attribute( 'ratting_image', 'src', $item['ratting_image']['url'] );
						$this->add_render_attribute( 'ratting_image', 'alt', \Elementor\Control_Media::get_image_alt( $item['image'] ) );
						$this->add_render_attribute( 'ratting_image', 'title', \Elementor\Control_Media::get_image_title( $item['image'] ) );

					}
					if($settings['style'] == 1){
						echo '<div class="elkit-testimonial-item">';
							echo '<div class="elkit-testimonial-item-holder">';
								if ( ! empty( $item['image']['url'] ) ) {
									echo '<figure class="elkit-testimonial-thumbnail">';
											echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'image' );
									echo '</figure>';
								}
								echo '<div class="elkit-testimonial-author">';
									echo '<'. $heading_tag .' class="elkit-testimonial-title">';
										echo esc_html($item['item_title']);
										
									echo '</'. $heading_tag .'>';
									if ( ! empty( $item['item_job'] ) ) {
										echo '<div class="testimonial-author-job">' . esc_html($item['item_job']) . '</div>';
									}
									if ( ! empty( $item['link']['url'] ) ) {
										echo '<a class="testimonial-author-link" ' . $this->get_render_attribute_string( 'link' ) . '></a>';
									}
								echo '</div>';
								echo '<div class="elkit-testimonial-content">';
									echo '<div class="elkit-testimonial-description">';
										echo esc_html($item['item_content']);
									echo '</div>';
									echo '<div class="elkit-testimonial-ratting">';
										echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'ratting_image' );
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}elseif($settings['style'] == 2){
						echo '<div class="elkit-testimonial-item">';
							echo '<div class="elkit-testimonial-item-holder">';
								echo '<div class="elkit-testimonial-content">';
									echo '<div class="elkit-testimonial-description">';
										echo esc_html($item['item_content']);
									echo '</div>';
									echo '<div class="elkit-testimonial-ratting">';
										echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'ratting_image' );
									echo '</div>';
								echo '</div>';
								echo '<div class="elkit-testimonial-author">';
									echo '<'. $heading_tag .' class="elkit-testimonial-title">';
										echo esc_html($item['item_title']);
										
									echo '</'. $heading_tag .'>';
									if ( ! empty( $item['item_job'] ) ) {
										echo '<div class="testimonial-author-job">' . esc_html($item['item_job']) . '</div>';
									}
									if ( ! empty( $item['link']['url'] ) ) {
										echo '<a class="testimonial-author-link" ' . $this->get_render_attribute_string( 'link' ) . '></a>';
									}
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
					
				}
			echo '</div>';
		echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				elkit_slik_init();				
			} )( jQuery );
		</script>';
		};
	}
}
