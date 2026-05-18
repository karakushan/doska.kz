<?php

$output = $css_class ='';

extract ( shortcode_atts ( array (
		'type' => 'slider',
		'control_label' => '',
		'control_name' => '',
		'minimum_value' => '',
		'maximum_value' => '',
		'step' => '',
		'default_value' => '',
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

$default_value = absint($default_value);
global $wpfb_form;
$default_value = apply_filters('wpfb_form_slider_default_value', $default_value,$wpfb_form,$name);
$label = $control_label;

$minmax = absint($minimum_value) + ((absint($maximum_value) - absint($minimum_value)) * 0.3 );

$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );
$output .='<div class="wpfb-form-group wpfb-form-'.$name.'-box '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";
if(!empty($label)){
	$output .='<label class="wpfb-form-label">'.$label.(!empty($required) ? ' <span class="required">*</span>':'').($type == "slider" ? ' (<span class="wpfb-form-slider-value">'.$default_value.'</span>)' : '(<span class="wpfb-form-slider-value-from">0</span> - <span class="wpfb-form-slider-value-to">'.$minmax.'</span>)').'</label>' . "\n";
}
$output .='<div class="wpfb-form-slider '.(!empty($conditional) && $type == "slider" ? ' wpfb-form-conditional':'').' wpfb-form-control-'.$name.'">'."\n";
$output .='<div class="wpfb-form-slider-control" data-min="'.absint($minimum_value).'" data-max="'.absint($maximum_value).'" data-step="'.absint($step).'" data-type="'.$type.'" data-value="'.$default_value.'" data-minmax="'.$minmax.'"></div>';
$output .='<input '.(!empty($conditional)  && $type == "slider" ? 'data-conditional-name="'.$name.'" data-conditional="'.esc_attr(base64_decode($conditional)).'"': '' ).' type="hidden" data-field-name="'.$name.'" class="wpfb-form-value" id="wpfb_form_control_'.$name.'" name="'.$name.'" value="'.($type == "slider" ? $default_value : '0-'.$minmax).'">' . "\n";
$output .='</div>';
if(!empty($help_text)){
	$output .='<span class="wpfb-form-help">'.$help_text.'</span>' . "\n";
}
$output .='</div>'."\n";

echo $output;