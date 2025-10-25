<?php

class Form_Builder_Wp_Widget_Checkbox extends Form_Builder_Wp_Widget_Base {

	public function get_name() {
		return 'wpfb_form_checkbox';
	}

	public function get_title() {
		return __( 'Checkbox Field', 'form-builder-wp' );
	}

	public function get_icon() {
		return 'wpfb-form-icon-widget-checkbox';
	}

	public function get_keywords() {
		return array('checkbox');
	}

	protected function register_controls(){
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'form-builder-wp' ),
			)
		);

		$this->add_control(
			'control_label',
			array(
				'label' => __( 'Label', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'control_name',
			array(
				'label' => __( 'Name', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __('Field name is required.  Please enter single word, no spaces, no start with number. Underscores(_) allowed', 'form-builder-wp')
			)
		);

		$this->add_control(
			'help_text',
			array(
				'label' => __( 'Help text', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'description'=>__('This is the help text for this form control.', 'form-builder-wp')
			)
		);

		$this->add_control(
			'required',
			array(
				'label' => __( 'Required ?', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'form-builder-wp' ),
				'label_on' => __( 'Yes', 'form-builder-wp' ),
			)
		);

		$this->add_control(
			'readonly',
			array(
				'label' => __( 'Readonly ?', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'form-builder-wp' ),
				'label_on' => __( 'Yes', 'form-builder-wp' ),
			)
		);

		$this->add_control(
			'attributes',
			array(
				'label' => __( 'Attributes', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __('Add attribute for this form control,eg: <em>onclick="" onchange="" </em> or \'<em>data-*</em>\'  attributes HTML5, not in attributes: <span style="color:#ff0000">type, value, name, required, placeholder, maxlength, id</span>', 'form-builder-wp')
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
			'section_options',
			array(
				'label' => __( 'Options', 'form-builder-wp' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'option_default',
			array(
				'label' => __( 'Deafult', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'form-builder-wp' ),
				'label_on' => __( 'Yes', 'form-builder-wp' ),
			)
		);

		$repeater->add_control(
			'option_label',
			array(
				'label' => __( 'Option Label', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			)
		);

		$repeater->add_control(
			'option_value',
			array(
				'label' => __( 'Option Value', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'options_list',
			array(
				'label' => __( 'Options', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => array(
					array(
						'option_label' => 'Option #1',
						'option_value' => 'value_1',
					),
					array(
						'option_label' => 'Option #2',
						'option_value' => 'value_2',
					),
				),
				'title_field' => '{{{ option_label }}}',
			)
		);
		
		$this->add_control(
			'option_width',
			array(
				'label' => __( 'Option with', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __('Enter option item width (Note: CSS measurement units allowed),ex: 50%', 'form-builder-wp')
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_conditional',
			array(
				'label' => __( 'Conditionals Logic', 'form-builder-wp' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'type',
			array(
				'label' => __( 'If value this element', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'=>array(
					'=' => __('Equals','form-builder-wp'),
					'>' => __('Is greater than','form-builder-wp'),
					'<' => __('Is less than','form-builder-wp'),
					'not_empty' => __('Not empty','form-builder-wp'),
					'is_empty' => __('Is empty','form-builder-wp'),
				)
			)
		);

		$repeater->add_control(
			'value',
			array(
				'label' => __( 'Value', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' =>array(
					'type!'=>array('not_empty','is_empty')
				),
			)
		);

		$repeater->add_control(
			'action',
			array(
				'label' => __( 'Then', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'=>array(
					'show'=>__('Show','form-builder-wp'),
					'hide'=>__('Hide','form-builder-wp')
				)
			)
		);

		$repeater->add_control(
			'element',
			array(
				'label' => __( 'Element(s) name', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder'=>'element_1,element_2'
			)
		);

		$this->add_control(
			'conditional',
			array(
				'label' => __( 'Conditionals Logic', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ type }}}',
				'prevent_empty' => false,
			)
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Field Styling', 'form-builder-wp' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'typography',
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .wpfb-form-checkbox__option-label',
			)
		);
		
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'form-builder-wp' ),
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
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-add-on' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_field_normal',
			array(
				'label' => __( 'Normal', 'form-builder-wp' ),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label' => __( 'Text Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'label_color',
			array(
				'label' => __( 'Label Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-label' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'placeholder_color',
			array(
				'label' => __( 'Placeholder Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label::placeholder, .wpfb-form-checkbox__option-label::-webkit-input-placeholder, .wpfb-form-checkbox__option-label::-moz-placeholder, .wpfb-form-checkbox__option-label:-moz-placeholder' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'icon_color',
			array(
				'label' => __( 'Icon Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-add-on' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'background_color',
			array(
				'label' => __( 'Background Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .wpfb-form-checkbox__option-label',
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_focus',
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
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:disabled,
					{{WRAPPER}} .wpfb-form-checkbox__option-label:disabled:hover,
					{{WRAPPER}} .wpfb-form-checkbox__option-label:hover,
					{{WRAPPER}} .wpfb-form-checkbox__option-label:active' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'placeholder_color_hover',
			array(
				'label' => __( 'Placeholder Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:hover::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:hover:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:hover::-ms-input-placeholder' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'icon_color_hover',
			array(
				'label' => __( 'Icon Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-group:hover .wpfb-form-add-on' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'background_hover_color',
			array(
				'label' => __( 'Background Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' =>array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:disabled,
					{{WRAPPER}} .wpfb-form-checkbox__option-label:disabled:hover,
					{{WRAPPER}} .wpfb-form-checkbox__option-label:hover,
					{{WRAPPER}} .wpfb-form-checkbox__option-label:active' => 'background-color: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'hover_border_color',
			array(
				'label' => __( 'Border Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:disabled,
					{{WRAPPER}} .wpfb-form-checkbox__option-label:disabled:hover,
					{{WRAPPER}} .wpfb-form-checkbox__option-label:hover,
					{{WRAPPER}} .wpfb-form-checkbox__option-label:active' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'box_shadow_hover',
				'selector' => '
					{{WRAPPER}} .wpfb-form-checkbox__option-label:hover,
					{{WRAPPER}} .wpfb-form-checkbox__option-label:active',
			)
		);
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_field_hover',
			array(
				'label' => __( 'Focus', 'form-builder-wp' ),
			)
		);

		$this->add_control(
			'focus_color',
			array(
				'label' => __( 'Text Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:focus' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'placeholder_color_focus',
			array(
				'label' => __( 'Placeholder Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:focus::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:focus:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:focus::-ms-input-placeholder' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'icon_color_focus',
			array(
				'label' => __( 'Icon Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-group:focus .wpfb-form-add-on' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'background_focus_color',
			array(
				'label' => __( 'Background Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' =>array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:focus' => 'background-color: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'focus_border_color',
			array(
				'label' => __( 'Border Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label:focus' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'box_shadow_focus',
				'selector' => '{{WRAPPER}} .wpfb-form-checkbox__option-label:focus',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'border',
				'selector' => '{{WRAPPER}} .wpfb-form-checkbox__option-label',
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
					'{{WRAPPER}} .wpfb-form-checkbox__option-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		
		$this->add_responsive_control(
			'padding',
			array(
				'label' => __( 'Padding', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'margin',
			array(
				'label' => __( 'Margin', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-checkbox__option-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Field Height', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpfb-form-checkbox__option-label' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-add-on' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		
		$this->end_controls_section();

	}

	protected function _parse_settings(){
		$settings = $this->get_settings_for_display();
		$settings['options_list'] = isset($settings['options_list']) ? base64_encode(json_encode($settings['options_list'])) : array();
		$settings['conditional'] = isset($settings['conditional']) ? base64_encode(json_encode($settings['conditional'])) : array();
		return $settings;
	}
}


function wpfb_form_field_checkbox_validation_filter($result, $field){
	$name = $field->get_name();
	if ( isset( $_POST[$name] ) && is_array( $_POST[$name] ) ) {
		foreach ( $_POST[$name] as $key => $value ) {
			if ( '' === $value ) {
				unset( $_POST[$name][$key] );
			}
		}
	}

	$empty = ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name];

	if($field->is_required() && $empty ){
		$result->invalidate($field, wpfb_form_get_message('invalid_required'));
	}
	return $result;

}
add_filter( 'wpfb_form_validate_checkbox', 'wpfb_form_field_checkbox_validation_filter', 10, 2 );
