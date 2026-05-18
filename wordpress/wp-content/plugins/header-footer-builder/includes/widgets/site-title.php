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

use HFB\WidgetsManager\Widgets_Loader;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * HFB Site title widget
 *
 * HFB widget for site title
 *
 * @since 1.3.0
 */
class Site_Title extends Widget_Base {

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
		return 'hfb-site-title';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Site Title', 'header-footer-builder' );
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
		return 'hfb-icon-site-title';
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
	 * Register site title controls.
	 *
	 * @since 1.5.7
	 * @access protected
	 */
	protected function register_controls() {

		$this->register_general_content_controls();
		$this->register_heading_typo_content_controls();
	}

	/**
	 * Register Advanced Heading General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_general_fields',
			[
				'label' => __( 'General', 'header-footer-builder' ),
			]
		);

		$this->add_control(
			'before',
			[
				'label'   => __( 'Before Title Text', 'header-footer-builder' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'after',
			[
				'label'   => __( 'After Title Text', 'header-footer-builder' ),
				'type'    => Controls_Manager::TEXT,
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

		$this->add_control(
			'custom_link',
			[
				'label'   => __( 'Link', 'header-footer-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'custom'  => __( 'Custom URL', 'header-footer-builder' ),
					'default' => __( 'Default', 'header-footer-builder' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'heading_link',
			[
				'label'       => __( 'Link', 'header-footer-builder' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'header-footer-builder' ),
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					'url' => get_home_url(),
				],
				'condition'   => [
					'custom_link' => 'custom',
				],
			]
		);

		$this->add_control(
			'size',
			[
				'label'   => __( 'Size', 'header-footer-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'header-footer-builder' ),
					'small'   => __( 'Small', 'header-footer-builder' ),
					'medium'  => __( 'Medium', 'header-footer-builder' ),
					'large'   => __( 'Large', 'header-footer-builder' ),
					'xl'      => __( 'XL', 'header-footer-builder' ),
					'xxl'     => __( 'XXL', 'header-footer-builder' ),
				],
			]
		);

		$this->add_control(
			'heading_tag',
			[
				'label'   => __( 'HTML Tag', 'header-footer-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1' => __( 'H1', 'header-footer-builder' ),
					'h2' => __( 'H2', 'header-footer-builder' ),
					'h3' => __( 'H3', 'header-footer-builder' ),
					'h4' => __( 'H4', 'header-footer-builder' ),
					'h5' => __( 'H5', 'header-footer-builder' ),
					'h6' => __( 'H6', 'header-footer-builder' ),
				],
				'default' => 'h2',
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
					'{{WRAPPER}} .hfb-heading' => 'text-align: {{VALUE}};',
				],
				'prefix_class'       => 'hfb%s-heading-align-',
				'frontend_available' => true,
			]
		);
		$this->end_controls_section();
	}


	/**
	 * Register Advanced Heading Typography Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_heading_typo_content_controls() {
		$this->start_controls_section(
			'section_heading_typography',
			[
				'label' => __( 'Title', 'header-footer-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elementor-heading-title, {{WRAPPER}} .hfb-heading a',
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label'     => __( 'Color', 'header-footer-builder' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .hfb-heading-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .hfb-icon i'       => 'color: {{VALUE}};',
					'{{WRAPPER}} .hfb-icon svg'     => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'heading_shadow',
				'selector' => '{{WRAPPER}} .hfb-heading-text',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label'     => __( 'Blend Mode', 'header-footer-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''            => __( 'Normal', 'header-footer-builder' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'difference'  => 'Difference',
					'exclusion'   => 'Exclusion',
					'hue'         => 'Hue',
					'luminosity'  => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .hfb-heading-text' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon',
			[
				'label'     => __( 'Icon', 'header-footer-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'icon[value]!' => '',
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
	 * Render Heading output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();
		$title    = get_bloginfo( 'name' );

		$this->add_inline_editing_attributes( 'heading_title', 'basic' );

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'title', 'class', 'elementor-size-' . $settings['size'] );
		}

		if ( ! empty( $settings['heading_link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['heading_link'] );
		}

		$heading_size_tag = Widgets_Loader::validate_html_tag( $settings['heading_tag'] );
		?>

		<div class="hfb-module-content hfb-heading-wrapper elementor-widget-heading">
		<?php if ( ! empty( $settings['heading_link']['url'] ) && 'custom' === $settings['custom_link'] ) { ?>
					<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'url' ) ); ?>>
				<?php } else { ?>
					<a href="<?php echo get_home_url(); ?>">
				<?php } ?>
			<<?php echo $heading_size_tag; ?> class="hfb-heading elementor-heading-title elementor-size-<?php echo $settings['size']; ?>">
				<?php if ( '' !== $settings['icon']['value'] ) { ?>
					<span class="hfb-icon">
						<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>					
					</span>
				<?php } ?>
					<span class="hfb-heading-text" >
					<?php
					if ( '' !== $settings['before'] ) {
						echo wp_kses_post( $settings['before'] );
					}
					?>
					<?php echo wp_kses_post( $title ); ?>
					<?php
					if ( '' !== $settings['after'] ) {
						echo wp_kses_post( $settings['after'] );
					}
					?>
					</span>			
			</<?php echo $heading_size_tag; ?>>
			</a>		
		</div>
		<?php
	}
		/**
		 * Render site title output in the editor.
		 *
		 * Written as a Backbone JavaScript template and used to generate the live preview.
		 *
		 * @since 1.3.0
		 * @access protected
		 */
	protected function content_template() {
		?>
		<#
		if ( '' == settings.heading_title ) {
			return;
		}
		if ( '' == settings.size ){
			return;
		}
		if ( '' != settings.heading_link.url ) {
			view.addRenderAttribute( 'url', 'href', settings.heading_link.url );
		}
		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );

		var headingSizeTag = settings.heading_tag;

		if ( typeof elementor.helpers.validateHTMLTag === "function" ) { 
			headingSizeTag = elementor.helpers.validateHTMLTag( headingSizeTag );
		} else if( HfbWidgetsData.allowed_tags ) {
			headingSizeTag = HfbWidgetsData.allowed_tags.includes( headingSizeTag.toLowerCase() ) ? headingSizeTag : 'div';
		}

		#>
		<div class="hfb-module-content hfb-heading-wrapper elementor-widget-heading">
				<# if ( '' != settings.heading_link.url ) { #>
					<a {{{ view.getRenderAttributeString( 'url' ) }}} >
				<# } #>
				<{{{ headingSizeTag }}} class="hfb-heading elementor-heading-title elementor-size-{{{ settings.size }}}">
				<# if( '' != settings.icon.value ){ #>
				<span class="hfb-icon">
					{{{ iconHTML.value }}}					
				</span>
				<# } #>
				<span class="hfb-heading-text  elementor-heading-title" data-elementor-setting-key="heading_title" data-elementor-inline-editing-toolbar="basic" >
				<#if ( '' != settings.before ){#>
					{{{ settings.before }}} 
				<#}#>
				<?php echo wp_kses_post( get_bloginfo( 'name' ) ); ?>
				<# if ( '' != settings.after ){#>
					{{{ settings.after }}}
				<#}#>
				</span>
			</{{{ headingSizeTag }}}>
			<# if ( '' != settings.heading_link.url ) { #>
				</a>
			<# } #>
		</div>
		<?php
	}
}
