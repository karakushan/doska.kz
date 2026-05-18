<?php
use Elementor\Controls_Manager;
class Form_Builder_Wp_Widget_Text extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_text';
	}
	
	public function get_title() {
		return __( 'Text Field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-text';
	}
	
	public function get_keywords() {
		return array('text');
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
			'field_type',
			array(
				'label' => __( 'Field type', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => array(
					'text'=>__('Text','form-builder-wp'),
					'number'=>__('Number','form-builder-wp'),
					'search'=>__('Search','form-builder-wp'),
					'tel'=>__('Tel','form-builder-wp'),
					'time'=>__('Time','form-builder-wp'),
					'url'=>__('Url','form-builder-wp'),
					'week'=>__('Week','form-builder-wp'),
					'datetime'=>__('Datetime','form-builder-wp'),
					'color'=>__('Color','form-builder-wp'),
					'month'=>__('Month','form-builder-wp'),
					'week'=>__('Week','form-builder-wp'),
				),
				'description' => __( 'Select type of field', 'form-builder-wp' )
			)
		);
		
		$this->add_control(
			'is_math_fied',
			array(
				'label' => __( 'Is Math field ?', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'form-builder-wp' ),
				'label_on' => __( 'Yes', 'form-builder-wp' ),
				'condition' =>array(
					'field_type'=>array('text','number')
				),
				'description'=>__('Allow use math value for this field with other field value. Example enter default value: price_field * 2','form-builder-wp')
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
			'minlength',
			array(
				'label' => __( 'Minimum length characters', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			)
		);
		
		$this->add_control(
			'maxlength',
			array(
				'label' => __( 'Maximum length characters', 'form-builder-wp' ),
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
				'label' => __( 'Add validator ?', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple'=>true,
				'label_block'=>true,
				'condition' =>array(
					'readonly!'=>'yes'
				),
				'options'=>wpfb_form_get_validation()
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
		$this->add_control(
			'field_typo_heading',
			[
				'label' => esc_html__( 'Field Typography', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'typography',
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .wpfb-form-control',
			)
		);
		$this->add_control(
			'label_typo_heading',
			[
				'label' => esc_html__( 'Label Typography', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'label_typography',
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .wpfb-form-label',
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
				'label'        => __( 'Input Alignment', 'form-builder-wp' ),
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
					{{WRAPPER}} .wpfb-form-control:active' => 'color: {{VALUE}};',
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
					{{WRAPPER}} .wpfb-form-control:active' => 'background-color: {{VALUE}};',
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
					{{WRAPPER}} .wpfb-form-control:active' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'box_shadow_hover',
				'selector' => '
					{{WRAPPER}} .wpfb-form-control:hover,
					{{WRAPPER}} .wpfb-form-control:active',
			)
		);
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_field_focus',
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
					'{{WRAPPER}} .wpfb-form-control:focus' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .wpfb-form-control:focus::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpfb-form-control:focus:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpfb-form-control:focus::-ms-input-placeholder' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .wpfb-form-control:focus' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .wpfb-form-control:focus' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'box_shadow_focus',
				'selector' => '
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
/**
 *
 * @param Form_Builder_Wp_Validation $result
 * @param Form_Builder_Wp_Field $field
 */
function wpfb_form_field_text_validation_filter($result, $field){
	$name = $field->get_name();
	$value = isset( $_POST[$name] ) ? trim( wp_unslash( strtr( (string) $_POST[$name], "\n", " " ) ) ) : '';
	if($field->is_required() && ''==$value){
		$result->invalidate($field, wpfb_form_get_message('invalid_required'));
	}
	$minlenght = absint($field->attr('minlength'));
	if($minlenght > 0 && strlen($value) < $minlenght){
		$result->invalidate($field, wpfb_form_get_message('invalid_too_short'));
	}
	if ( '' !== $value ) {
		foreach ($field->get_validator() as $validator){
			switch ($validator){
				case 'wpfb-form-validate-date';
					if(!wpfb_form_is_date($value)){
						$result->invalidate($field, wpfb_form_get_message('invalid_date'));
					}
				break;
				case 'wpfb-form-validate-number':
					if(!wpfb_form_is_number($value)){
						$result->invalidate($field,wpfb_form_get_message('invalid_number'));
					}
				break;
				case 'wpfb-form-validate-number2':
					if(!wpfb_form_is_number2($value)){
						$result->invalidate($field, wpfb_form_get_message('invalid_number2'));
					}
				break;
				case 'wpfb-form-validate-digits':
					if(!wpfb_form_is_digits($value)){
						$result->invalidate($field, wpfb_form_get_message('invalid_digits'));
					}
				break;
				case 'wpfb-form-validate-alpha':
					if(!wpfb_form_is_alpha($value)){
						$result->invalidate($field, wpfb_form_get_message('invalid_alpha'));
					}		
				break;
				case 'wpfb-form-validate-alphanum':
					if(!wpfb_form_is_alphanum($value)){
						$result->invalidate($field, wpfb_form_get_message('invalid_alphanum'));
					}
				break;
				case 'wpfb-form-validate-url':
					if(!wpfb_form_is_url($value)){
						$result->invalidate($field, wpfb_form_get_message('invalid_url'));
					}
				break;
				case 'wpfb-form-validate-zip':
					if(!wpfb_form_is_zip($value)){
						$result->invalidate($field,wpfb_form_get_message('invalid_zip'));
					}
				break;
				case 'wpfb-form-validate-fax':
					if(!wpfb_form_is_fax($value)){
						$result->invalidate($field,wpfb_form_get_message('invalid_fax'));
					}
				break;
				default:
					if(!apply_filters('wpfb_form_validation_filter', true, $validator, $value)){
						$result->invalidate($field,apply_filters('wpfb_form_validation_filter_message', '', $validator, $value));
					}
				break;
			}
		}
	}
	return $result;

}
add_filter( 'wpfb_form_validate_text', 'wpfb_form_field_text_validation_filter', 10, 2 );
