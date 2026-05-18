<?php
$output = $css_class ='';
extract(shortcode_atts(array(
	'captcha_type'=>'2',
	'type'=>'recaptcha',
	'theme'=>'red',
	'language'=>'en',
	'control_label'=>'',
	'control_name'=>'',
	'placeholder'=>'',
	'help_text'=>'',
	'required'=>'1',
	'attributes'=>'',
	'el_class'=> '',
	'input_css'=>'',
), $atts));

$name = $this->getControlName($control_name);
if(empty($name)){
	echo wpfb_form_require_field_name_notice();
	return;
}
global $wpfb_form;
$label = $control_label;
$language = apply_filters('wpfb_form_language_code',$language);



if($captcha_type=='3'){
	$output .= '<input data-sitekey="'.wpfb_form_get_option('recaptcha_public_key').'" type="hidden" id="g-recaptcha-response_'.$name.'" data-wpfbform-recaptcha="recaptcha" name="g-recaptcha-response" value="">';
}else{
	$el_class = $this->getExtraClass($el_class);
	
	$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );
	
	$output .='<div class="wpfb-form-group wpfb-form-'.$name.'-box '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";
	if(!empty($label)){
		$output .='<label class="wpfb-form-label" for="'.$name.'">'.$label.(!empty($required) ? ' <span class="required">*</span>':'').'</label>' . "\n";
	}
	
	$site_key = wpfb_form_get_option('recaptcha_public_key');
	$secret_key	 = wpfb_form_get_option('recaptcha_private_key');
	if ( ! empty( $site_key ) && ! empty( $secret_key ) ) {
		$output .='<div data-sitekey="'.wpfb_form_get_option('recaptcha_public_key').'" data-theme="light" data-size="normal" type="recaptcha" data-wpfbform-recaptcha="recaptcha" class="wpfb-form-recaptcha wpfb-form-recaptcha2" id="'.$name.'"></div>';
	}else{
		$output .= __('To use reCAPTCHA, you need to add the API Key and complete the setup process in Dashboard > WP Form Builder > Settings > Integrations > reCAPTCHA settings.','form-builder-wp');
	}
	if(!empty($help_text)){
		$output .='<span class="help_text">'.$help_text.'</span>' . "\n";
	}
	$output .='</div>'."\n";
}

echo $output;
