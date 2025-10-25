<?php

class Form_Builder_Wp_Widget_Slider extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_slider';
	}
	
	public function get_title() {
		return __( 'Slider Field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-slider';
	}
	
	public function get_keywords() {
		return array('Slider');
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
				'default' => 'slider',
				'options' => array(
					'slider'=>__('Slider', 'form-builder-wp'),
	                'range'=>__('Range', 'form-builder-wp')
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
			'minimum_value',
			array(
				'label' => __( 'Minimum Value', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default'=>0
			)
		);
		
		$this->add_control(
			'maximum_value',
			array(
				'label' => __( 'Maximum Value', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default'=>100
			)
		);
		
		$this->add_control(
			'step',
			array(
				'label' => __( 'Step', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default'=>5
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
		$settings['conditional'] = isset($settings['conditional']) ? base64_encode(json_encode($settings['conditional'])) : array();
		return $settings;
	}
}

function wpfb_form_field_slider_validation_filter($result, $field){
	$name = $field->get_name();
	$value = isset( $_POST[$name] ) ? (string) $_POST[$name] : '';
	if($field->is_required() && ''==$value){
		$result->invalidate($field, wpfb_form_get_message('invalid_required'));
	}
	return $result;

}
add_filter( 'wpfb_form_validate_slider', 'wpfb_form_field_slider_validation_filter', 10, 2 );

