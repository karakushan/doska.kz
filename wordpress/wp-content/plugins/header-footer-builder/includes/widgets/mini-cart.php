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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;
if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * HFB Cart Widget
 *
 * @since 1.4.0
 */
class Mini_Cart extends Widget_Base {

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
		return 'hfb-cart';
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
		return __( 'Mini Cart', 'header-footer-builder' );
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

	/**
	 * Register cart controls.
	 *
	 * @since 1.5.7
	 * @access protected
	 */
	protected function register_controls() {

		$this->content_controls();
		$this->styling_controls();
	}

	/**
	 * Register Menu Cart General Controls.
	 *
	 * @since 1.4.0
	 * @access protected
	 */
	protected function content_controls() {

		$this->start_controls_section(
			'section_general_fields',
			[
				'label' => __( 'Menu Cart', 'header-footer-builder' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' =>esc_html__( 'Icon', 'header-footer-builder' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
                    'value' => '',
				],
			]
		);

		
		$this->add_control(
			'items_count',
			[
				'label'        => __( 'Show Count', 'header-footer-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'header-footer-builder' ),
				'label_off'    => __( 'No', 'header-footer-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'hfb-menu-cart--items-indicator-',
			]
		);
		$this->add_control(
			'show_subtotal',
			[
				'label'        => __( 'Show Amount', 'header-footer-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'header-footer-builder' ),
				'label_off'    => __( 'No', 'header-footer-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'hfb-menu-cart--show-subtotal-',
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'        => __( 'Hide Empty', 'header-footer-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'header-footer-builder' ),
				'label_off'    => __( 'No', 'header-footer-builder' ),
				'return_value' => 'hide',
				'prefix_class' => 'hfb-menu-cart--empty-indicator-',
				'description'  => __( 'This will hide the items count until the cart is empty', 'header-footer-builder' ),
			]
		);
		$this->add_responsive_control(
			'item_display',
			[
				'label' => esc_html__( 'Item Display', 'header-footer-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'block'  => esc_html__( 'Block', 'header-footer-builder' ),
					'inline-block' => esc_html__( 'Inline block', 'header-footer-builder' ),
				],
				'selectors' => [
					'{{WRAPPER}} .hfb--mini-cart-item' => 'display: {{VALUE}};',
				],
			]
        );
		$this->add_responsive_control(
			'item_align',
			[
				'label'              => __( 'Item Alignment', 'header-footer-builder' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'left'   => [
						'title' => __( 'Left', 'header-footer-builder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'header-footer-builder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'header-footer-builder' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'            => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label'              => __( ' Content Alignment', 'header-footer-builder' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'left'   => [
						'title' => __( 'Left', 'header-footer-builder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'header-footer-builder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'header-footer-builder' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'            => '',
				'selectors' => [
					'{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'direction',
			[
				'label'              => __( 'Direction', 'header-footer-builder' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'ltr'   => [
						'title' => __( 'Left to right', 'header-footer-builder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'rtl'  => [
						'title' => __( 'Right to left', 'header-footer-builder' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'            => 'ltr',
				'selectors' => [
					'{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item' => 'direction: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}


	/**
	 * Register Menu Cart Typography Controls.
	 *
	 * @since 1.4.0
	 * @access protected
	 */
	protected function styling_controls() {
		$this->start_controls_section(
			'section_heading_typography',
			[
				'label' => __( 'Cart Item', 'header-footer-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'toggle_button_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'  => '{{WRAPPER}} .hfb--mini-cart-item-toggle .hfb-menu-item-button',
			]
		);
		$this->add_control(
			'item_width',
			[
				'label'     => __( 'With', 'header-footer-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'     => [
					'px' => [
						'min' => 15,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'item_height',
			[
				'label'     => __( 'Height', 'header-footer-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'     => [
					'px' => [
						'min' => 15,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'item_margin',
			[
				'label'              => __( 'Margin', 'header-footer-builder' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em' ],
				'selectors'          => [
					'{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);
		$this->add_responsive_control(
			'item_padding',
			[
				'label'              => __( 'Padding', 'header-footer-builder' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em' ],
				'selectors'          => [
					'{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => esc_html__( 'Item Border', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item',
			]
		);
		$this->add_control(
			'item_border_radius',
			[
				'label'      => __( 'Border Radius', 'header-footer-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'    => '',
					'bottom' => '',
					'left'   => '',
					'right'  => '',
					'unit'   => 'px',
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->start_controls_tabs( 'toggle_button_colors' );

		$this->start_controls_tab(
			'item_normal_colors',
			[
				'label' => __( 'Normal', 'header-footer-builder' ),
			]
		);
		$this->add_control(
			'item_background_color',
			[
				'label'     => __( 'Background Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'item_text_color',
			[
				'label'     => __( 'Text Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .hfb--mini-cart-item-toggle svg' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} .hfb--mini-cart-item',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_hover_colors',
			[
				'label' => __( 'Hover', 'header-footer-builder' ),
			]
		);
		
		$this->add_control(
			'item_background_color_hover',
			[
				'label'     => __( 'Background Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle .hfb-menu-item-button:hover,{{WRAPPER}} .hfb-cart-menu-wrap-default span.hfb-cart-count:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'item_border_color_hover',
			[
				'label'     => __( 'Border Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb-custom-menu-items .hfb--mini-cart .hfb--mini-cart-item:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'item_text_color_hover',
			[
				'label'     => __( 'Text Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle .hfb-menu-item-button:hover,{{WRAPPER}} .hfb-cart-menu-wrap-default span.hfb-cart-count:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label'     => __( 'Icon Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle .hfb-menu-item-button:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .hfb--mini-cart-item-toggle .hfb-menu-item-button:hover svg' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} .hfb--mini-cart-item:hover',
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->add_control(
			'icon_size',
			[
				'label'      => __( 'Icon Size', 'header-footer-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .hfb--mini-cart-item-toggle svg' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator'  => 'before',
			]
		);
		$this->add_control(
			'icon_line_height',
			[
				'label'      => __( 'Icon line-height', 'header-footer-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle i' => 'line-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .hfb--mini-cart-item-toggle svg' => 'line-height: {{SIZE}}{{UNIT}}',
				],
				'separator'  => 'before',
			]
		);
		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'              => __( 'Icon Spacing', 'header-footer-builder' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em' ],
				'selectors'          => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle-button i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .hfb--mini-cart-item-toggle-button svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon',
			[
				'label'     => __( 'Items Count', 'header-footer-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'items_indicator_distance',
			[
				'label'     => __( 'Distance', 'header-footer-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'em',
				],
				'range'     => [
					'em' => [
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle .hfb-menu-item-counter[data-counter]:before' => 'right: -{{SIZE}}{{UNIT}}; top: -{{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'count_colors' );

		$this->start_controls_tab(
			'count_normal_colors',
			[
				'label' => __( 'Normal', 'header-footer-builder' ),
			]
		);

		$this->add_control(
			'counter_text_color',
			[
				'label'     => __( 'Text Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle .hfb-menu-item-counter[data-counter]:before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'counter_background_color',
			[
				'label'     => __( 'Background Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle .hfb-menu-item-counter[data-counter]:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'count_hover_colors',
			[
				'label' => __( 'Hover', 'header-footer-builder' ),
			]
		);

		$this->add_control(
			'counter_text_color_hover',
			[
				'label'     => __( 'Text Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle:hover .hfb-menu-item-counter[data-counter]:before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'counter_background_color_hover',
			[
				'label'     => __( 'Background Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb--mini-cart-item-toggle:hover .hfb-menu-item-counter[data-counter]:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render Menu Cart output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.4.0
	 * @access protected
	 */
	protected function render() {

		if ( null === WC()->cart ) {
			return;
		}

		$settings  = $this->get_settings_for_display();

		?>

		<div class="hfb-custom-menu-items">
			<div class="hfb--mini-cart">
				<div class="hfb--mini-cart-item">
					<div class="hfb--mini-cart-item-toggle hfb-menu-button-wrapper">
						<a id="hfb--mini-cart-item-toggle-button" href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="hfb-menu-item-button hfb-cart-container">
							<?php if ( null !== WC()->cart ) { ?>
								<span class="hfb-button-text hfb-subtotal">
									<?php echo WC()->cart->get_cart_subtotal(); ?>
								</span>
							<?php } ?>
							
							<span class="hfb-menu-item-counter" data-counter="<?php echo ( null !== WC()->cart ) ? WC()->cart->get_cart_contents_count() : ''; ?>"></span>
							<?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</a>	
					</div>         
				</div>
			</div>
		</div> 
		<?php
	}
}
