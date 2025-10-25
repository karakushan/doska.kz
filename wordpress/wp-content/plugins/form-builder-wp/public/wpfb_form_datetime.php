<?php
$output = $css_class ='';

extract(shortcode_atts(array(
	'type'=>'date',
	'control_label'=>'',
	'control_name'=>'',
	'min_date'=>'false',//Datetime
	'max_date'=>'false',//Datetime
	'min_time'=>'false',//Datetime
	'max_time'=>'false',//Datetime
	'default_value'=>'',
	'range_field_step'=>1,
	'range_field'=>'',
	'placeholder'=>'',
	'help_text'=>'',
	'required'=>'',
	'readonly'=>'',
	'attributes'=>'',
	'el_class'=> '',
	'input_css'=>'',
), $atts));

global $wpfb_form;

$name = $this->getControlName($control_name);

if(empty($name)){
	echo wpfb_form_require_field_name_notice();
	return;
}
	
$label = $control_label;

$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );

$picker_class = $type=='date' ? 'wpfb-form-datepicker' : ($type == 'datetime' ? 'wpfb-form-datetimepicker' : 'wpfb-form-timepicker') ;
$picker_attrs = '';
$date_picker_attrs = ' data-year-start="'. apply_filters('wpfb_form_datepicker_year_start', '1950', $wpfb_form, $name).'" data-year-end="'. apply_filters('wpfb_form_datepicker_year_end', '2050', $wpfb_form, $name).'" data-min-date="'. apply_filters('wpfb_form_datepicker_min_date', $min_date, $wpfb_form, $name).'" data-max-date="'. apply_filters('wpfb_form_datepicker_max_date', $max_date, $wpfb_form, $name).'"';
$time_picker_attrs = ' data-min-time="'. apply_filters('wpfb_form_timepicker_min_time',$min_time, $wpfb_form, $name).'" data-max-time="'. apply_filters('wpfb_form_timepicker_max_time', $max_time, $wpfb_form, $name).'"';
if($type == 'date'){
	$picker_attrs =$date_picker_attrs;
}else if($type=='datetime'){
	$picker_attrs = $date_picker_attrs.' '.$time_picker_attrs;
}else {
	$picker_attrs = $time_picker_attrs;
}

$output .='<div class="wpfb-form-group wpfb-form-'.$name.'-box '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";

if(!empty($label)){
	$output .='<label class="wpfb-form-label" for="wpfb_form_control_'.$name.'">'.$label.(!empty($required) ? ' <span class="required">*</span>':'').'</label>' . "\n";
}
$value = '';
if(!empty($default_value))
	$value = ' value="'.date_i18n(wpfb_form_get_option('date_format','Y/m/d'),strtotime($default_value) ) .'" ';

$output .='<div class="wpfb-form-input wpfb-form-has-add-on">'."\n";
$output .= '<input '.$value.' readonly '.('date'===$type && !empty($range_field) ? ' data-range_field_set_value="'.apply_filters('wpfb_form_datepicker_range_field_set_value', 'yes').'" data-range_field_start_current="'.apply_filters('wpfb_form_datepicker_range_field_start_current', $range_field_step).'" data-range_field="'.$range_field.'" ' : '').' data-field-name="'.$name.'" type="text" id="wpfb_form_control_'.$name.'" '.$picker_attrs.' name="'.$name.'" '
		.' class="wpfb-form-control wpfb-form-value wpfb-form-control-'.$name.' '.$picker_class.' wpfb-form-control'.(!empty($required) ? ' wpfb-form-required-entry':'').'" '.(!empty($required) ? ' required aria-required="true"':'').' '.(!empty($readonly) ? ' readonly':'').' placeholder="'.$placeholder.'" '.$attributes.'>' . "\n";

if($type =='date' || $type=='datetime'){
	$output .='<span class="wpfb-form-add-on"><i class="wpfb-icon-calendar"></i></span>'."\n";
}else{
	$output .='<span class="wpfb-form-add-on"><i class="wpfb-icon-clock-o"></i></span>'."\n";
}

$output .='</div>'."\n";

if(!empty($help_text)){
	$output .='<span class="wpfb-form-help">'.$help_text.'</span>' . "\n";
}
$output .='</div>'."\n";

echo $output;