<?php

class Form_Builder_Wp_Widget_Submit_Button extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_submit_button';
	}
	
	public function get_title() {
		return __( 'Submit button', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-submit-button';
	}
	
	public function get_keywords() {
		return array('submit');
	}
	
	protected function register_controls(){
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'form-builder-wp' ),
			)
		);
		
		$this->add_control(
			'label',
			array(
				'label' => __( 'Label', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default'=>'Submit'
			)
		);
		
		$this->add_responsive_control(
			'align',
			array(
				'label' => __( 'Alignment', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options'=>array(
					'left'=> array(
						'title' => __( 'Left', 'form-builder-wp' ),
						'icon' => 'fa fa-align-left',
					),
					'center'=> array(
						'title' => __( 'Center', 'form-builder-wp' ),
						'icon' => 'fa fa-align-center',
					),
					'right'=> array(
						'title' => __( 'Right', 'form-builder-wp' ),
						'icon' => 'fa fa-align-right',
					),
					'justify'=> array(
						'title' => __( 'Justified', 'form-builder-wp' ),
						'icon' => 'fa fa-align-justify',
					)
				),
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			)
		);
		
		
		$this->add_control(
			'icon',
			array(
				'label' => __( 'Icon', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
			)
		);
		
		$this->add_control(
			'icon_align',
			array(
				'label' => __( 'Icon Position', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left' => __( 'Before', 'form-builder-wp' ),
					'right' => __( 'After', 'form-builder-wp' ),
				),
				'condition' => array(
					'icon!' => '',
				),
			)
		);
		
		$this->add_control(
			'icon_indent',
			array(
				'label' => __( 'Icon Spacing', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'condition' => array(
					'icon!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-submit__icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpfb-form-submit__icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);
		
		$this->add_control(
			'el_class',
			array(
				'label' => __( 'Extra class name', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'separator' => 'before',
				'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'form-builder-wp')
			)
		);
		
	
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Button', 'form-builder-wp' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'typography',
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .wpfb-form-submit',
			)
		);
		
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'form-builder-wp' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label' => __( 'Text Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-submit' => 'color: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'background_color',
			array(
				'label' => __( 'Background Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-submit' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wpfb-form-submit',
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'form-builder-wp' ),
			)
		);

		$this->add_control(
			'hover_color',
			array(
				'label' => __( 'Text Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-submit:disabled,
					{{WRAPPER}} .wpfb-form-submit:disabled:hover,
					{{WRAPPER}} .wpfb-form-submit:hover,
					{{WRAPPER}} .wpfb-form-submit:active,
					{{WRAPPER}} .wpfb-form-submit:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_hover_color',
			array(
				'label' => __( 'Background Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' =>array(
					'{{WRAPPER}} .wpfb-form-submit:disabled,
					{{WRAPPER}} .wpfb-form-submit:disabled:hover,
					{{WRAPPER}} .wpfb-form-submit:hover,
					{{WRAPPER}} .wpfb-form-submit:active,
					{{WRAPPER}} .wpfb-form-submit:focus' => 'background-color: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'button_hover_border_color',
			array(
				'label' => __( 'Border Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-submit:disabled,
					{{WRAPPER}} .wpfb-form-submit:disabled:hover,
					{{WRAPPER}} .wpfb-form-submit:hover,
					{{WRAPPER}} .wpfb-form-submit:active,
					{{WRAPPER}} .wpfb-form-submit:focus' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'box_shadow_hover',
				'selector' => '
					{{WRAPPER}} .wpfb-form-submit:hover,
					{{WRAPPER}} .wpfb-form-submit:active,
					{{WRAPPER}} .wpfb-form-submit:focus',
			)
		);
		$this->add_control(
			'hover_animation',
			array(
				'label' => __( 'Hover Animation', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'border',
				'selector' => '{{WRAPPER}} .wpfb-form-submit',
				'separator' => 'before',
			)
		);
		
		$this->add_control(
			'border_radius',
			array(
				'label' => __( 'Border Radius', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		
		$this->add_responsive_control(
			'text_padding',
			array(
				'label' => __( 'Padding', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'button_size',
			[
				'label' => __( 'Button Size', 'form-builder-wp' ), 
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'' => __( 'Default', 'form-builder-wp' ),
					'custom' => __( 'Custom', 'form-builder-wp' ),
				],
				'default' => [''],
			]
		);
		$this->add_responsive_control(
			'custom_width',
			[
				'label' => __( 'Button Width', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'condition' => [
					'button_size' => [ 'custom' ],
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpfb-form-submit' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Button Height', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpfb-form-submit' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-add-on' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
	}
}