<?php

class Form_Builder_Wp_Widget_Hidden extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_hidden';
	}
	
	public function get_title() {
		return __( 'Hidden field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-hidden';
	}
	
	public function get_keywords() {
		return array('Hidden');
	}
	
	protected function register_controls(){
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'form-builder-wp' ),
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
			'is_math_fied',
			array(
				'label' => __( 'Is Math field ?', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'form-builder-wp' ),
				'label_on' => __( 'Yes', 'form-builder-wp' ),
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
		
		$this->end_controls_section();
	}
}