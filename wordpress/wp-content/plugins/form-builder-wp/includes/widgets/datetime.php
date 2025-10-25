<?php

class Form_Builder_Wp_Widget_Datetime extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_datetime';
	}
	
	public function get_title() {
		return __( 'Datetime Field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-datetime';
	}
	
	public function get_keywords() {
		return array('Date','Time');
	}
	
	protected function register_controls(){
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'form-builder-wp' ),
			)
		);
		
		$this->add_control(
			'type',
			array(
				'label' => __( 'Type', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'=>__('Date', 'form-builder-wp'),
	                'time'=>__('Time', 'form-builder-wp'),
	                'datetime'=>__('Date & Time', 'form-builder-wp')
				)
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
			'min_date',
			array(
				'label' => __( 'Min Date', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				"description" => sprintf(__('Enter min date for Datepicker.Enter 0 to set min date is today. <a target="_blank" href="%s">See setting value</a>', 'form-builder-wp'),'https://xdsoft.net/jqplugins/datetimepicker/#minDate'),
			)
		);
		
		$this->add_control(
			'max_date',
			array(
				'label' => __( 'Max Date', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				"description" => sprintf(__('Enter min date for Datepicker. <a target="_blank" href="%s">See setting value</a>', 'form-builder-wp'),'https://xdsoft.net/jqplugins/datetimepicker/#maxDate'),
			)
		);
		
		$this->add_control(
			'min_time',
			array(
				'label' => __( 'Min Time', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				"description" => sprintf(__('Enter min time for Datepicker.Enter 0 to set min date is now. <a target="_blank" href="%s">See setting value</a>', 'form-builder-wp'),'https://xdsoft.net/jqplugins/datetimepicker/#minTime'),
			)
		);
		
		$this->add_control(
			'max_time',
			array(
				'label' => __( 'Max Time', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				"description" => sprintf(__('Enter min time for Datepicker. <a target="_blank" href="%s">See setting value</a>', 'form-builder-wp'),'https://xdsoft.net/jqplugins/datetimepicker/#maxTime'),
			)
		);
		
		$this->add_control(
			'default_value',
			array(
				'label' => __( 'Default value', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' =>array(
					'type'=>array('date')
				),
				"description" => sprintf(__('Enter English textual datetime. Example: now, +1 day, +1 week... <a target="_blank" href="%s">See more</a>', 'form-builder-wp'),'http://php.net/manual/en/function.strtotime.php'),
			)
		);
		
		$this->add_control(
			'range_field',
			array(
				'label' => __( 'Min date start by field', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' =>array(
					'type'=>array('date','datetime')
				),
				"description" => __('Enter field name you want set it is min Date for this field', 'form-builder-wp')
			)
		);
		
		$this->add_control(
			'range_field_step',
			array(
				'label' => __( 'Min date start step', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default'=>1,
				'condition' =>array(
					'type'=>array('date','datetime')
				),
				"description" => __('Enter Min date start step. Example, if enter 5 and date start field selected 2019-05-24, min date of this field will 2019-06-29', 'form-builder-wp')
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
	}
	
	protected function _parse_settings(){
		$settings = $this->get_settings_for_display();
		if(''===trim($settings['min_date'])){
			unset($settings['min_date']);
		}
		if(''===trim($settings['max_date'])){
			unset($settings['max_date']);
		}
		if(''===trim($settings['min_time'])){
			unset($settings['min_time']);
		}
		if(''===trim($settings['max_time'])){
			unset($settings['max_time']);
		}
		return $settings;
	}
}
/**
 *
 * @param unknown $result
 * @param Form_Builder_Wp_Field $field
 * @return unknown
 */
function wpfb_form_field_datetime_validation_filter($result, $field){
	$name = $field->get_name();
	$is_datetime = 'datetime'===$field->attr('type') ? true : false;
	$value = isset( $_POST[$name] ) ? trim( strtr( (string) $_POST[$name], "\n", " " ) ) : '';
	if($field->is_required() && ''==$value){
		$result->invalidate($field, wpfb_form_get_message('invalid_required'));
	}elseif (''!=$value && 'time'!==$field->attr('type') && !wpfb_form_is_date($value,$is_datetime)){
		$result->invalidate($field, wpfb_form_get_message('invalid_time'));
	}
	return $result;

}
add_filter( 'wpfb_form_validate_datetime', 'wpfb_form_field_datetime_validation_filter', 10, 2 );

