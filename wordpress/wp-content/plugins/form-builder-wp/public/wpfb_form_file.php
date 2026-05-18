<?php
$output = $css_class ='';

extract(shortcode_atts(array(
	'control_label'=>'',
	'control_name'=>'',
	'help_text'=>'',
	'required'=>'',
	'attributes'=>'',
	'el_class'=> '',
	'input_css'=>'',
), $atts));

$name = $this->getControlName($control_name);
if(empty($name)){
	echo wpfb_form_require_field_name_notice();
	return;
}
$label = $control_label;

$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );

$output .='<div class="wpfb-form-group wpfb-form-'.$name.'-box '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";
if(!empty($label)){
	$output .='<label class="wpfb-form-label">'.$label.(!empty($required) ? ' <span class="required">*</span>':'').'</label>' . "\n";
}
$output .='<div class="wpfb-form-file">'."\n";
$output .='<label for="wpfb_form_control_'.$name.'">';
$output .= '<span class="wpfb-form-file-button">'."\n";
$output .= '<i>'.__('Browse','form-builder-wp').'</i>'."\n";
$output .= '</span>'."\n";
$output .='<input id="wpfb_form_control_'.$name.'" data-field-name="'.$name.'" name="'.$name.'" class="wpfb-form-value wpfb-form-validate-extension wpfb-form-control-'.$name.' '.(!empty($required) ? ' wpfb-form-required-entry':'').'" type="file">';
$output .= '<input autocomplete="off" class="wpfb-form-control" placeholder="'.esc_attr__('Choose file','form-builder-wp').'" type="text" value="" readonly="readonly">'."\n";
$output .= '</label>' . "\n";				
$output .='</div>';
if(!empty($help_text)){
	$output .='<span class="wpfb-form-help">'.$help_text.'</span>' . "\n";
}
$output .='</div>'."\n";

echo $output;

