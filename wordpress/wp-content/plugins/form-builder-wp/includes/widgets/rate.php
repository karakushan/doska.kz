<?php

class Form_Builder_Wp_Widget_Rate extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_rate';
	}
	
	public function get_title() {
		return __( 'Rate field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-rate';
	}
	
	public function get_keywords() {
		return array('Rate');
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
		for($i=0;$i<5;$i++){
			$value = $i+1;
			$option = new stdClass();
			$option->label = $value.'/5';
			$option->value = $value;
			$value_arr[] = $option;
		}
		$this->add_control(
			'rate_option',
			array(
				'label' => __( 'Options', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => array(
					array(
						'option_label' => '1/5',
						'option_value' => '1',
					),
					array(
						'option_label' => '2/5',
						'option_value' => '2',
					),
					array(
						'option_label' => '3/5',
						'option_value' => '3',
					),
					array(
						'option_label' => '4/5',
						'option_value' => '4',
					),
					array(
						'option_label' => '5/5',
						'option_value' => '5',
					),
				),
				'title_field' => '{{{ option_label }}}',
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
	}
	
	protected function _parse_settings(){
		$settings = $this->get_settings_for_display();
		$settings['rate_option'] = isset($settings['rate_option']) ? base64_encode(json_encode($settings['rate_option'])) : array();
		$settings['conditional'] = isset($settings['conditional']) ? base64_encode(json_encode($settings['conditional'])) : array();
		return $settings;
	}
}

function wpfb_form_field_rate_validation_filter($result, $field){
	$name = $field->get_name();
	$value = isset( $_POST[$name] ) ? (string) $_POST[$name] : '';
	if($field->is_required() && ''==$value)
		$result->invalidate($field, wpfb_form_get_message('invalid_required'));
	return $result;

}
add_filter( 'wpfb_form_validate_rate', 'wpfb_form_field_rate_validation_filter', 10, 2 );

