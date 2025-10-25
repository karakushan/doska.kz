<?php
$output = $css_class ='';

extract(shortcode_atts(array(
	'control_label'=>'',
	'control_name'=>'',
	'confirmation'=>'',
	'confirm_field'=>'',
	'default_value'=>'',
	'maxlength'=>'',
	'placeholder'=>'',
	'icon'=>'',
	'help_text'=>'',
	'required'=>'',
	'readonly'=>'',
	'attributes'=>'',
	'el_class'=> '',
	'input_css'=>'',
), $atts));

$name = $this->getControlName($control_name);
if(empty($name)){
	echo wpfb_form_require_field_name_notice();
	return;
}
$default_value = esc_attr($default_value);
global $wpfb_form;
$default_value = apply_filters('wpfb_form_email_default_value', $default_value,$wpfb_form,$name);
$label = $control_label;


$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );

$input_class='';
$icon_html = '';
if(!empty($icon) && $icon != 'None'){
	$input_class = ' wpfb-form-has-add-on';
	$icon_html ='<span class="wpfb-form-add-on"><i class="'.$icon.'"></i></span>'."\n";
}

$output .='<div class="wpfb-form-group wpfb-form-'.$name.'-box '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";
if(!empty($label)){
	$output .='<label class="wpfb-form-label" for="wpfb_form_control_'.$name.'">'.$label.(!empty($required) ? ' <span class="required">*</span>':'').'</label>' . "\n";
}
$output .='<div class="wpfb-form-input '.$input_class.'">'."\n";
$output .= '<input data-field-name="'.$name.'" autocomplete="off" type="email" id="wpfb_form_control_'.$name.'" name="'.$name.'" value="'.$default_value.'" '.(!empty($maxlength) ? ' maxlength="'.$maxlength.'"' : '')
		.' class="wpfb-form-control wpfb-form-control-'.$name.' wpfb-form-value'.(!empty($required) ? ' wpfb-form-required-entry wpfb-form-validate-email ':'').' '.'" '.(!empty($required) ? ' required aria-required="true"':'').' '.($readonly =='yes' ? ' readonly':'').' placeholder="'.$placeholder.'" '.$attributes.'>' . "\n";

$output .=$icon_html;
$output .='</div>';
if(!empty($help_text)){
	$output .='<span class="wpfb-form-help">'.$help_text.'</span>' . "\n";
}
$output .='</div>'."\n";

echo $output;

