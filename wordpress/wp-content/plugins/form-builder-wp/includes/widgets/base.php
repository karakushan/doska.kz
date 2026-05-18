<?php

abstract class Form_Builder_Wp_Widget_Base extends \Elementor\Widget_Base {
	
	public function show_in_panel() {
		return 'wpfbform'===get_post_type();;
	}

	protected function _parse_settings(){
		return $this->get_settings_for_display();
	}
	
	public function get_categories() {
		return array('wpfb-form-fields');
	}
	
	protected function _parse_shortcode(){
		$settings = $this->_parse_settings();
		$shortcode_tag = $this->get_name();
		$atts = array();
		foreach (wpfb_form_shortcode_deafult_atts() as $key=>$value){
			if(isset($settings[$key])){
				$atts[] = $key.'="'.( is_array( $settings[$key] ) ? implode( ',', $settings[$key] ) : trim( $settings[$key] ) ).'"';
			}
		}
		
		return "[{$shortcode_tag} ".implode(' ', $atts)."]";
	}
	
	protected function render(){
		echo do_shortcode($this->_parse_shortcode());
	}
	
	public function render_plain_content(){
		echo $this->_parse_shortcode();
	}
}