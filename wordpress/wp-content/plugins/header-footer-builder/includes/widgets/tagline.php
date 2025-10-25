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
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * HFB Site tagline widget
 *
 * HFB widget for site tagline
 *
 * @since 1.3.0
 */
class Tagline extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'hfb-site-tagline';
	}

	/**
	 * Retrieve the widget tagline.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget tagline.
	 */
	public function get_title() {
		return __( 'Site Tagline', 'header-footer-builder' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'hfb-icon-site-tagline';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'hfb-widgets' ];
	}

	/**
	 * Register site tagline controls.
	 *
	 * @since 1.5.7
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_general_content_controls();
	}

	/**
	 * Register site tagline General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_general_fields',
			[
				'label' => __( 'Style', 'header-footer-builder' ),
			]
		);

		$this->add_control(
			'before',
			[
				'label'   => __( 'Before Title Text', 'header-footer-builder' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => '1',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'after',
			[
				'label'   => __( 'After Title Text', 'header-footer-builder' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => '1',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => __( 'Icon', 'header-footer-builder' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => 'true',
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label'     => __( 'Icon Spacing', 'header-footer-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .hfb-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'heading_text_align',
			[
				'label'              => __( 'Alignment', 'header-footer-builder' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'left'    => [
						'title' => __( 'Left', 'header-footer-builder' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'header-footer-builder' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'header-footer-builder' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'header-footer-builder' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .hfb-site-tagline' => 'text-align: {{VALUE}};',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tagline_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .hfb-site-tagline',
			]
		);
		$this->add_control(
			'tagline_color',
			[
				'label'     => __( 'Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .hfb-site-tagline' => 'color: {{VALUE}};',
					'{{WRAPPER}} .hfb-icon i'       => 'color: {{VALUE}};',
					'{{WRAPPER}} .hfb-icon svg'     => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'condition' => [
					'icon[value]!' => '',
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .hfb-icon i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .hfb-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'icons_hover_color',
			[
				'label'     => __( 'Icon Hover Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'icon[value]!' => '',
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .hfb-icon:hover i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .hfb-icon:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render site tagline output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="hfb-site-tagline hfb-site-tagline-wrapper">
			<?php if ( '' !== $settings['icon']['value'] ) { ?>
				<span class="hfb-icon">
					<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>					
				</span>
			<?php } ?>
			<span>
			<?php
			if ( '' !== $settings['before'] ) {
				echo wp_kses_post( $settings['before'] );
			}
			?>
			<?php echo wp_kses_post( get_bloginfo( 'description' ) ); ?>
			<?php
			if ( '' !== $settings['after'] ) {
				echo ' ' . wp_kses_post( $settings['after'] );
			}
			?>
			</span>
		</div>
		<?php
	}

	/**
	 * Render Site Tagline widgets output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' ); #>
		<div class="hfb-site-tagline hfb-site-tagline-wrapper">
			<# if( '' != settings.icon.value ){ #>
				<span class="hfb-icon">
					{{{iconHTML.value}}}
				</span>
			<# } #>
			<span>
			<#if ( '' != settings.before ){#>
				{{{ settings.before}}} 
			<#}#>
			<?php echo wp_kses_post( get_bloginfo( 'description' ) ); ?>
			<# if ( '' != settings.after ){#>
				{{{ settings.after }}}
			<#}#>
			</span>
		</div>
		<?php
	}
}
