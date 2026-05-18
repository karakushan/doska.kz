<?php
$output ='';
extract(shortcode_atts(array(
	'control_name'=>'',
	'link'=>'',
	'link_text'=>'',
	'el_class'=> '',
	'input_css'=>'',
), $atts));

global $wpfb_form;
$css_class = apply_filters( 'wpfb_form_shortcodes_css_class', $el_class, $atts );
$link = explode (",", $link);  
$url = $link[0];

$target = $link[1]? ' target="_blank"' : '';
$nofollow = $link[2]? ' rel="nofollow"' : '';
$output .='<div class="wpfb-form-group wpfb-form-link-box '.$css_class.wpfb_form_shortcode_custom_css_class($input_css,' ').'">'."\n";
$output .='<div class="wpfb-form-link">'."\n";
$output .= (!empty($url))? '<a href="'.$url.'" '. $target .' '. $nofollow .'>'. esc_attr($link_text) .'</a>': '';
$output .='</div>';
$output .='</div>'."\n";
echo $output;