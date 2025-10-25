<?php
$output = $css_class ='';
extract(shortcode_atts(array(
	'el_class'=> '',
	'input_css'=>'',
), $atts));

$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );

$output .='<div class="wpfb-form-message '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";
$output .='</div>'."\n";

echo $output;

