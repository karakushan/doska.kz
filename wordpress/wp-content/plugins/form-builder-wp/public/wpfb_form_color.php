<?php
$output = $css_class ='';

extract(shortcode_atts(array(
	'control_label'=>'',
	'control_name'=>'',
	'default_value'=>'',
	'placeholder'=>'',
	'help_text'=>'',
	'required'=>'',
	'readonly'=>'',
	'attributes'=>'',
	'el_class'=> '',
	'input_css'=>'',
), $atts));

$name =$this->getControlName($control_name);
if(empty($name)){
	echo wpfb_form_require_field_name_notice();
	return;
}
$default_value = esc_attr($default_value);
global $wpfb_form;
$default_value = apply_filters('wpfb_form_color_default_value', $default_value,$wpfb_form,$name);
$label = $control_label;



$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );

$output .='<div class="wpfb-form-group wpfb-form-minicolors wpfb-form-'.$name.'-box '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";
if(!empty($label)){
	$output .='<label class="wpfb-form-label" for="wpfb_form_control_'.$name.'">'.$label.(!empty($required) ? ' <span class="required">*</span>':'').'</label>' . "\n";
}
$output .='<div class="wpfb-form-input">'."\n";
$output .= '<input type="text" data-field-name="'.$name.'" id="wpfb_form_control_'.$name.'" name="'.$name.'" value="'.$default_value.'" '.(!empty($maxlength) ? ' maxlength="'.$maxlength.'"' : '')
		.' class="wpfb-form-control wpfb-form-control-'.$name.' wpfb-form-value'.(!empty($required) ? ' wpfb-form-required-entry':'').'" '.(!empty($required) ? ' required aria-required="true"':'').' '.(!empty($readonly) ? ' readonly':'').' placeholder="'.$placeholder.'" '.$attributes.'>' . "\n";
$output .='</div>';
if(!empty($help_text)){
	$output .='<span class="wpfb-form-help">'.$help_text.'</span>' . "\n";
}
$output .='</div>'."\n";

echo $output;