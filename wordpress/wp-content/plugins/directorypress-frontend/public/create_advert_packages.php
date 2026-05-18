<?php
	global $DIRECTORYPRESS_ADIMN_SETTINGS; 
	if (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pricing_plan_style']) && DpFl_Templates('partials/plans/'. esc_attr($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pricing_plan_style']) .'.php')) {
		dpfl_renderTemplate('partials/plans/'. esc_attr($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pricing_plan_style']) .'.php', array('public_handler' => $public_handler));
	}else{
		dpfl_renderTemplate('partials/plans/style-1.php', array('public_handler' => $public_handler));
	}