<?php
$output ='';
extract(shortcode_atts(array(
	'control_name'=>'',
	'is_math_fied'=>'',
	'el_class'=> '',
	'input_css'=>'',
), $atts));
$name = $this->getControlName($control_name);
if(empty($name)){
	echo wpfb_form_require_field_name_notice();
	return;
}
global $wpfb_form;
$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );
$data_math_field = '';
if('yes'===$is_math_fied){
	$data_math_field = ' data-calculation_value_format="'.esc_attr(apply_filters( 'wpfb_form_calculation_value_format','%v', $wpfb_form, $name )).'" data-field_calculation="'.esc_attr(wp_strip_all_tags($content,true)).'"';
	$content = '';
}
$output .='<div class="wpfb-form-group wpfb-form-'.$name.'-box '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";
$output .='<div '.$data_math_field.' class="wpfb-form-control-label wpfb-form-control-'.$name.'">'."\n";
$output .= wpfb_form_remove_wpautop($content, true);
$output .='</div>';
$output .='</div>'."\n";
echo $output;