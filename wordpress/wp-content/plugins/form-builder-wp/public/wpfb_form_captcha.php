<?php
$output = $css_class = '';

extract ( shortcode_atts ( array (
		'control_label' => '',
		'control_name' => '',
		'placeholder' => '',
		'help_text' => '',
		'required' => '1',
		'attributes' => '',
		'el_class' => '' ,
		'input_css'=>'',
), $atts ) );
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
	$output .='<label class="wpfb-form-label" for="wpfb_form_control_'.$name.'">'.$label.(!empty($required) ? ' <span class="required">*</span>':'').'</label>' . "\n";
}

$output .='<div class="wpfb-form-captcha">'."\n";
$filename = wpfb_form_field_captcha_generate();
$prefix = substr( $filename, 0, strrpos( $filename, '.' ) );
$output .= '<input autocomplete="off" type="text" id="wpfb_form_control_'.$name.'" name="'.$name.'" '
		.' class="wpfb-form-control wpfb-form-control-'.$name.' wpfb-form-value'.(!empty($required) ? ' wpfb-form-required-entry wpfb-form-validate-captcha':'').'" '.(!empty($required) ? ' required aria-required="true"':'').' placeholder="'.$placeholder.'">' . "\n";
$output .= '<div class="wpfb-form-captcha-img">';
$output .='<img class="wpfb-form-captcha-img-'.$name.'" src="'.wpfb_form_field_captcha_img_url($filename).'">';
$output .='<input type="hidden" name="_wpfb_form_captcha_challenge_'.$name.'" value="'.$prefix.'" />';
$output .='</div>';
$output .='</div>';
if(!empty($help_text)){
	$output .='<span class="wpfb-form-help">'.$help_text.'</span>' . "\n";
}
$output .='</div>'."\n";

echo $output;
