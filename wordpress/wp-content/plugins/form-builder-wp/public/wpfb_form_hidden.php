<?php
$output ='';
global $wpfb_form;
extract(shortcode_atts(array(
	'control_name'=>'',
	'is_math_fied'=>'',
	'default_value'=>'',
), $atts));
$name = $this->getControlName($control_name);
if(empty($name)){
	echo wpfb_form_require_field_name_notice();
	return;
}
global $wpfb_form;
$default_value = esc_attr($default_value);
$default_value = apply_filters('wpfb_form_hidden_default_value', $default_value,$wpfb_form,$name);
$data_math_field = '';
if('yes'===$is_math_fied){
	$data_math_field = ' data-calculation_value_format="'.esc_attr(apply_filters( 'wpfb_form_calculation_value_format','%v', $wpfb_form, $name )).'" data-field_calculation="'.$default_value.'"';
	$default_value = '';
}
$output .= '<input '.$data_math_field.' type="hidden" class="wpfb-form-value" data-field-name="'.$name.'" id="wpfb_form_control_'.$name.'" name="'.$name.'" value="'.$default_value.'">' . "\n";
echo $output;