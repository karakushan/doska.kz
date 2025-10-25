<?php
use Elementor\Controls_Manager;
class Form_Builder_Wp_Widget_Password extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_password';
	}
	
	public function get_title() {
		return __( 'Password Field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-password';
	}
	
	public function get_keywords() {
		return array('Password');
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
			'confirmation',
			array(
				'label' => __( 'Is confirmation ?', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'form-builder-wp' ),
				'label_on' => __( 'Yes', 'form-builder-wp' ),
			)
		);
		
		$this->add_control(
			'password_field',
			array(
				'label' => __( 'Confirm for field', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' =>array(
					'confirmation'=>'yes'
				),
				'description' => __('Enter password field name to validate match', 'form-builder-wp')
			)
		);
		
		$this->add_control(
			'default_value',
			array(
				'label' => __( 'Default value', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			)
		);
		
		$this->add_control(
			'placeholder',
			array(
				'label' => __( 'Placeholder text', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			)
		);
		
		$this->add_control(
			'icon',
			array(
				'label' => __( 'Icon', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::ICON,
				'default' => '',
				'description'=> __( 'Select icon add-on for this control.', 'form-builder-wp' )
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
			'validator',
			array(
				'label' => __( 'Password validator ?', 'form-builder-wp' ),
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
				'selector' => '{{WRAPPER}} .wpfb-form-control',
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
		$this->add_control(
			'placeholder_align',
			[
				'label'        => __( 'Alignment', 'form-builder-wp' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
						'left'    => [
							'title' => __( 'Left', 'form-builder-wp' ),
							'icon'  => 'eicon-h-align-left',
						],
						'center'  => [
							'title' => __( 'Center', 'form-builder-wp' ),
							'icon'  => 'eicon-h-align-center',
						],
						'right'   => [
							'title' => __( 'Right', 'form-builder-wp' ),
							'icon'  => 'eicon-h-align-right',
						],
				],
				'default'      => 'left',
				'prefix_class' => 'wpfb_placeholder_align-',
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
					'{{WRAPPER}} .wpfb-form-control' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .wpfb-form-control::placeholder, .wpfb-form-control::-webkit-input-placeholder, .wpfb-form-control::-moz-placeholder, .wpfb-form-control:-moz-placeholder' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .wpfb-form-control' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .wpfb-form-control',
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_hover',
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
					'{{WRAPPER}} .wpfb-form-control:disabled,
					{{WRAPPER}} .wpfb-form-control:disabled:hover,
					{{WRAPPER}} .wpfb-form-control:hover,
					{{WRAPPER}} .wpfb-form-control:active,
					{{WRAPPER}} .wpfb-form-control:focus' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .wpfb-form-control:hover::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpfb-form-control:hover:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpfb-form-control:hover::-ms-input-placeholder' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .wpfb-form-control:disabled,
					{{WRAPPER}} .wpfb-form-control:disabled:hover,
					{{WRAPPER}} .wpfb-form-control:hover,
					{{WRAPPER}} .wpfb-form-control:active,
					{{WRAPPER}} .wpfb-form-control:focus' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .wpfb-form-control:disabled,
					{{WRAPPER}} .wpfb-form-control:disabled:hover,
					{{WRAPPER}} .wpfb-form-control:hover,
					{{WRAPPER}} .wpfb-form-control:active,
					{{WRAPPER}} .wpfb-form-control:focus' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'box_shadow_hover',
				'selector' => '
					{{WRAPPER}} .wpfb-form-control:hover,
					{{WRAPPER}} .wpfb-form-control:active,
					{{WRAPPER}} .wpfb-form-control:focus',
			)
		);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'border',
				'selector' => '{{WRAPPER}} .wpfb-form-control',
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
					'{{WRAPPER}} .wpfb-form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpfb-form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpfb-form-control' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpfb-form-control' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-add-on' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		
		$this->end_controls_section();
	}
	
}
function wpfb_form_field_password_validation_filter($result, $field){
	$name = $field->get_name();
	
	$value = isset( $_POST[$name] ) ? (string) $_POST[$name] : '';
	
	$confirmation = $field->attr('confirmation');
	
	if($field->is_required() && ''==$value)
		$result->invalidate($field, wpfb_form_get_message('invalid_required'));
	elseif (''!=$value){
		$cpassword = $field->attr('password_field');
		$cpassword_value = isset($_POST[$cpassword]) ? (string) $_POST[$cpassword] : '';
		if('1'==$field->attr('validator') && strlen($value) < 6){
			$result->invalidate($field, sprintf(wpfb_form_get_message('invalid_min'),6));
		}elseif (!empty($confirmation) && !empty($cpassword) && $cpassword_value!==$value ){
			$result->invalidate($field,wpfb_form_get_message('invalid_cpassword'));
		}
	}
	return $result;

}
add_filter( 'wpfb_form_validate_password', 'wpfb_form_field_password_validation_filter', 10, 2 );


