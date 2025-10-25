<?php

$output = $css_class ='';

extract ( shortcode_atts ( array (
		'control_label' => '',
		'control_name' => '',
		'rate_option'=>'',
		'help_text' => '',
		'conditional'=>'',
		'el_class' => '',
		'input_css'=>'',
), $atts ) );

$name = $this->getControlName($control_name);
if(empty($name)){
	echo wpfb_form_require_field_name_notice();
	return;
}
$label = esc_html($control_label);

$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );

$output .='<div class="wpfb-form-group wpfb-form-'.$name.'-box '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";
if(!empty($label)){
	$output .='<label class="wpfb-form-label">'.$label.(!empty($required) ? ' <span class="required">*</span>':'').'</label>' . "\n";
}
$output .='<div class="wpfb-form-rate '.(!empty($conditional) ? ' wpfb-form-conditional':'').' wpfb-form-control-'.$name.'">'."\n";
$rate_option_64 = base64_decode($rate_option);
$rate_option_arr = json_decode($rate_option_64);

if(is_array($rate_option_arr) && !empty($rate_option_arr)){
	$c = count($rate_option_arr);
	for($i = $c;$i--;$i > 0 ){
		$v = $rate_option_arr[$i];
		$output .='<input data-field-name="'.$name.'" '.(!empty($conditional) ? 'data-conditional-name="'.$name.'" data-conditional="'.esc_attr(base64_decode($conditional)).'"': '' ).' name="'.$name.'" value="'.$v->option_value.'" id="'.sanitize_title($name).'-'.$v->option_value.'" class="wpfb-form-value" type="radio">' . "\n";
		$output .='<label class="wpfb-form-rate-star" data-toggle="tooltip" data-original-title="'.esc_html($v->option_label).'" for="'.sanitize_title($name).'-'.$v->option_value.'"><i class="wpfb-icon-star"></i></label>' . "\n";
	}
}
$output .='</div>';
if(!empty($help_text)){
	$output .='<span class="wpfb-form-help">'.$help_text.'</span>' . "\n";
}
$output .='</div>'."\n";

echo $output;