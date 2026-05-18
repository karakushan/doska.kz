<?php

class Form_Builder_Wp_Widget_Label extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_label';
	}
	
	public function get_title() {
		return __( 'Label field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-label';
	}
	
	public function get_keywords() {
		return array('Label');
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
				'description'=>__('Allow use math value for this field with other field value. Example enter content value: price_field * 2','form-builder-wp')
			)
		);
		
		$this->add_control(
			'content',
			array(
				'label' => __( 'Text', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'deafult' => 'I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function _parse_shortcode(){
		$settings = $this->_parse_settings();
		$content = isset($settings['content']) ? $settings['content'] : '';
		$atts = array();
		foreach (wpfb_form_shortcode_deafult_atts() as $key=>$value){
			if(isset($settings[$key])){
				$atts[] = $key.'="'.(is_array($settings[$key]) ? implode(',', $settings[$key]) : $settings[$key]).'"';
			}
		}
		return "[wpfb_form_label ".implode(' ', $atts)."]{$content}[/wpfb_form_label]";
	}
}