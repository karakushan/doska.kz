<?php
$output = $css_class ='';

extract(shortcode_atts(array(
	'control_label'=>'',
	'control_name'=>'',
	'default_value'=>'',
	'options_list'=>'',
	'help_text'=>'',
	'required'=>'',
	'disabled'=>'',
	'attributes'=>'',
	'conditional'=>'',
	'el_class'=> '',
	'input_css'=>'',
), $atts));
$name = $this->getControlName($control_name);
if(empty($name)){
	echo wpfb_form_require_field_name_notice();
	return;
}
$label = $control_label;
$default_value_arr = (array) explode(',',$default_value);
global $wpfb_form;
$default_value_arr = apply_filters('wpfb_form_select_default_value', $default_value_arr,$wpfb_form,$name);
$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );

$output .='<div class="wpfb-form-group wpfb-form-'.$name.'-box '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";
if(!empty($label)){
	$output .='<label class="wpfb-form-label" for="wpfb_form_control_'.$name.'">'.$label.(!empty($required) ? ' <span class="required">*</span>':'').'</label>' . "\n";
}
$output .='<div class="wpfb-form-select'.(!empty($conditional) ? ' wpfb-form-conditional':'').'">'."\n";
if(!empty($options_list)){
	$options_arr = json_decode(base64_decode($options_list));
	$options_arr = apply_filters('wpfb_form_select_options', $options_arr,$wpfb_form,$name);
	$select_name = ($this->shortcode =='wpfb_form_multiple_select') ? $name.'[]' : $name;
	$output .= '<select data-field-name="'.$name.'" data-name="'.$name.'" '.(!empty($conditional) ? 'data-conditional-name="'.$name.'" data-conditional="'.esc_attr(base64_decode($conditional)).'"': '' ).' '.(!empty($disabled) ? ' disabled':'').'  id="wpfb_form_control_'.$name.'" name="'.$select_name.'" '.(($this->shortcode =='wpfb_form_multiple_select') ? 'multiple' :'' ).' class="wpfb-form-control wpfb-form-control-'.$name.' wpfb-form-value '.(!empty($required) ? ' wpfb-form-required-entry':'').'" '.(!empty($required) ? ' required aria-required="true"':'').' '.$attributes.'>'."\n";
	if(!empty($options_arr)){
		foreach ($options_arr as $option){
			$output .= '<option '.($option->option_default === 'yes' ? 'selected="selected"' :'').' value="'.esc_attr($option->option_value).'">'.esc_html($option->option_label).'</option>';
		}
	}
	$output .='</select><i class="wpfb-icon-caret-down"></i>'."\n";
}
$output .='</div>';
if(!empty($help_text)){
	$output .='<span class="wpfb-form-help">'.$help_text.'</span>' . "\n";
}
$output .='</div>'."\n";

echo $output;