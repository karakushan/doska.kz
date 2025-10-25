<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	if($instance->cat_style == 'advanced-term-slider'){
		directorypress_display_template('partials/terms/taxonomy/parts/advanced-terms-slider.php', array('instance' => $instance, 'terms' => $terms));
	}elseif($instance->cat_style == 'advanced-terms'){
		directorypress_display_template('partials/terms/taxonomy/parts/advanced-terms.php', array('instance' => $instance, 'terms' => $terms));
	}else{
		directorypress_display_template('partials/terms/taxonomy/parts/terms.php', array('instance' => $instance, 'terms' => $terms));
	}