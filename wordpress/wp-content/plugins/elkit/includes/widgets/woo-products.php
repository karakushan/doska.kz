<?php

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

if (! defined( 'ABSPATH' ) ) exit;

class Elkit_Woo_Products extends Widget_Base {

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
		return 'elkit-woo-products';
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
		return __( 'Woo Products', 'elkit' );
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
		return [ 'woocomerce', 'woo', 'product' ];
	}
	
	public function get_script_depends() {
		return [ 'elkit-public' ];
	}
	protected function register_controls() {
		$this->start_controls_section(
			'ekit_section_settings',
			[
				'label' => esc_html__( 'Settings', 'elkit' ),
			]
		);

		$this->add_control(
			'limit',
			[
				'label'   => esc_html__( 'Product Limit', 'elkit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
			]
		);
		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns', 'elkit' ),
				'type'           => Controls_Manager::SELECT,
				'options'        => [
					'100' => '1',
					'50' => '2',
					'33.3333' => '3',
					'25' => '4',
					'20' => '5',
					'16.6666' => '6',
                ],
                'default' => '25',
				'selectors' => [
					'{{WRAPPER}} .pacz-product-loop-item' => 'width: {{VALUE}}%;',
				],
			]
		);
		$this->add_control(
			'pagination',
			[
				'label' => esc_html__( 'Pagination', 'elkit' ),
				'type'  => Controls_Manager::SWITCHER,
			]
        );

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order by', 'elkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'     => esc_html__( 'Date', 'elkit' ),
					'title'    => esc_html__( 'Title', 'elkit' ),
					'category' => esc_html__( 'Category', 'elkit' ),
					'rand'     => esc_html__( 'Random', 'elkit' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'elkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' => esc_html__( 'Descending', 'elkit' ),
					'ASC'  => esc_html__( 'Ascending', 'elkit' ),
				],
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
		$this->add_control(
			'slider',
			[
				'label' => __( 'Slider', 'elkit' ), 
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'elkit' ),
				'label_off' => esc_html__( 'Off', 'elkit' ),
				'default' => 0,
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
				'default' => 3,
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
		$this->add_control(
			'slider_arrow_position',
			[
				'label' => esc_html__( 'Position', 'elkit' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'elkit' ),
					'absolute' => esc_html__( 'Absolute', 'elkit' ),
					'static' => esc_html__( 'Static', 'elkit' )
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

    protected function render(){
		$settings = $this->get_settings();
		
		if($settings['slider']){
			$desktop = $settings['items']; // default name is always desktop
			$tablet = (isset($settings['items_tablet']) && !empty($settings['items_tablet']))? $settings['items_tablet'] : 2; // _tablet is added to the tablet value
			$mobile = (isset($settings['items_mobile']) && !empty($settings['items_mobile']))? $settings['items_mobile'] : 1;  // _mobile is added to the _mobile value
			
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
			
			$slider_class = 'slider';
		}else{
			$attrs = '';
			$slider_class = '';
		}
		
		if($settings['columns'] == '100'){
			$columns = 1;
		}elseif($settings['columns'] == '50'){
			$columns = 2;
		}elseif($settings['columns'] == '33.3333'){
			$columns = 3;
		}elseif($settings['columns'] == '25'){
			$columns = 4;
		}elseif($settings['columns'] == '20'){
			$columns = 5;
		}elseif($settings['columns'] == '16.6666'){
			$columns = 6;
		}else{
			$columns = 3;
		}
		
		echo '<div class="elkit-woo-products-widget '. esc_attr($slider_class) .'" '. $attrs .'>';
			echo do_shortcode('[products limit="'. $settings['limit'] .'" columns="'. $columns .'" order="'. $settings['order'] .'" orderby="'. $settings['orderby'] .'" paginate="'. $settings['pagination'] .'"]');
        echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				Countdown_Timer();				
			} )( jQuery );
		</script>';
		};
    }


}
