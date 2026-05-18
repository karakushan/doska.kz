<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Form_Builder_Wp_Field {
	
	private $field;
	
	public function __construct($field){
		$this->field = $field;
	}
	
	public function is_required(){
		return isset($this->field['required']) && 'yes'===$this->field['required'];
	}
	
	public function base_type(){
		return str_replace('wpfb_form_', '', $this->field['tag']);
	}
	
	public function get_name(){
		return isset($this->field['control_name']) ? esc_attr(trim($this->field['control_name'])) : '';
	}
	
	public function get_id(){
		return 'wpfb_form_control_'.$this->get_name();
	}
	
	public function get_validator(){
		$validator = isset($this->field['validator']) ? explode(',', $this->field['validator']) : array();
		return $validator;
	}
	
	public function get_attrs(){
		return $this->field;
	}
	
	public function attr($attr=''){
		if(''==$attr || !isset($this->field[$attr]))
			return false; 
		return $this->field[$attr];
	}
}