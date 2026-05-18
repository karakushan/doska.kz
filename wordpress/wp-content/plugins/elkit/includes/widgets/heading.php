<?php

use Elementor\Plugin;
class Elkit_Elementor_Heading_Widget extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'elkit_heading';
	}
	
	public function get_title() {
		return __( 'Heading', 'elkit' );
	}

	public function get_icon() {
		return 'fab fa-searchengin';
	}

	public function get_categories() {
		return [ 'elkit' ];
	}
	protected function register_controls() {
		
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Setting', 'elkit' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'editor',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => '<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elkit' ) . '</p>',
			]
		);
		$this->add_responsive_control(
			'display',
			[
				'label' => __( 'Display', 'elkit' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'block' => __( 'Block', 'elkit' ),
					'inline-block' => __( 'Inline Block', 'elkit' ),
					'inline' => __( 'Inline', 'elkit' ),
				],
				'default' => 'block',
				'selectors' => [
					'{{WRAPPER}} .wpfb-heading-editor' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'align',
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
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'elkit' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title', 'elkit' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'elkit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'global' => [
					'default' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wpfb-heading-editor > *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => '',
				],
				'selector' => '{{WRAPPER}} .wpfb-heading-editor > *',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .wpfb-heading-editor > *',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => __( 'Background', 'elkit' ),
				'description' => __( 'Background', 'elkit' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpfb-heading-editor',
			]
		);
		
		$this->add_control(
			'blend_mode',
			[
				'label' => __( 'Blend Mode', 'elkit' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Normal', 'elkit' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .wpfb-heading-editor > *' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);
		$this->add_control(
			'padding',
			[
				'label' => __( 'Padding', 'elkit' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpfb-heading-editor' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'margin',
			[
				'label' => __( 'Margin', 'elkit' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpfb-heading-editor > *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .wpfb-heading-editor',
			]
		);
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Radius', 'plugin-domain' ),
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
					'{{WRAPPER}} .wpfb-heading-editor' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
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
		$editor_content = $this->get_settings_for_display( 'editor' );

		$editor_content = $this->parse_text_editor( $editor_content );

		$this->add_render_attribute( 'editor', 'class', [ 'wpfb-heading-editor', 'elementor-clearfix' ] );

		$this->add_inline_editing_attributes( 'editor', 'advanced' );
		?>
		<div <?php echo $this->get_render_attribute_string( 'editor' ); ?>><?php echo $editor_content; ?></div>
		<?php
	}
}