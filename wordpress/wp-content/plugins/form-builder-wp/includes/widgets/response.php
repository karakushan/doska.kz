<?php

class Form_Builder_Wp_Widget_Response extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_response';
	}
	
	public function get_title() {
		return __( 'Response Message', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-response';
	}
	
	public function get_keywords() {
		return array('Response');
	}
	
	protected function register_controls(){
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'form-builder-wp' ),
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
}