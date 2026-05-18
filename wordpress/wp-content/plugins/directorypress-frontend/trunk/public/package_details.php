<?php
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	// active period with builtin gateway
	if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_payments_addon'] != 'directorypress_woo_payment' && $args['show_period']){
		echo '<li class="directorypress-list-group-item">';
			echo esc_html($package->get_active_duration_string());
		echo '</li>';
	}
	if ($args['columns_same_height'] || (!$args['columns_same_height'])){
		// sticksy option
		if ($args['show_has_sticky']){
			
			echo '<li class="directorypress-list-group-item">';
				if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pricing_plan_style'] == 'pplan-style-1'){
					_e('Sticky', 'directorypress-frontend');
				}
				if ($package->has_sticky){
					echo '<i class="dicode-material-icons dicode-material-icons-check"></i>';
				}else{
					echo '<i class="dicode-material-icons dicode-material-icons-close"></i>';
				}
				if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pricing_plan_style'] != 'pplan-style-1'){
					if (!$package->has_sticky){
						echo '<span class="item-not-available">';
					}
						_e('Sticky', 'directorypress-frontend'); 
					if (!$package->has_sticky){
						echo '</span>';
					}
				}
			echo '</li>';
		}
		// has_featured option
		if ($args['show_has_featured']){
			echo '<li class="directorypress-list-group-item">';
				if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pricing_plan_style'] == 'pplan-style-1'){
					_e('Featured', 'directorypress-frontend'); 
				}
				if ($package->has_featured){
					echo '<i class="dicode-material-icons dicode-material-icons-check"></i>';
				}else{
					echo '<i class="dicode-material-icons dicode-material-icons-close"></i>';
				}
				if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pricing_plan_style'] != 'pplan-style-1'){ 
					if (!$package->has_featured){
						echo '<span class="item-not-available">';
					}
						_e('Featured', 'directorypress-frontend');
					if (!$package->has_featured){
						echo '</span>';
					}
				}
			echo '</li>';
		}
		// category option
		if ($args['show_categories']){
			echo '<li class="directorypress-list-group-item">';
					if ($package->category_number_allowed == 1){
						_e('1 category', 'directorypress-frontend');
					}elseif ($package->category_number_allowed != 0){
						printf(esc_html__('Up to %d categories', 'directorypress-frontend'), $package->category_number_allowed);
					}else{
						_e('No categories', 'directorypress-frontend');
					}
				
			echo '</li>';
		}
		// location option
		if ($args['show_locations']){
			echo '<li class="directorypress-list-group-item">';
				if ($package->location_number_allowed == 1){
					_e('1 location', 'directorypress-frontend');
				}elseif ($package->location_number_allowed != 0){
					printf(esc_html__('Up to %d locations', 'directorypress-frontend'), $package->location_number_allowed);
				}else{
					esc_html_e('No locations', 'directorypress-frontend');
				}
			echo '</li>';
		}
		// images option
		if ($args['show_images']){
			echo '<li class="directorypress-list-group-item">';
				if ($package->images_allowed == 1){
					esc_html_e('1 image', 'directorypress-frontend');
				}elseif ($package->images_allowed != 0){
					printf(esc_html__('Up to %d images', 'directorypress-frontend'), $package->images_allowed);
				}else{
					_e('No images', 'directorypress-frontend');
				}
			echo '</li>';
		}
		// videos option
		if ($args['show_videos']){
			echo '<li class="directorypress-list-group-item">';
				if ($package->videos_allowed == 1){
					esc_html_e('1 video', 'directorypress-frontend');
				}elseif ($package->videos_allowed != 0){
					printf(esc_html__('Up to %d videos', 'directorypress-frontend'), $package->videos_allowed);
				}else{
					esc_html_e('No videos', 'directorypress-frontend');
				}
			echo '</li>';
		}
		if($package->selection_items){
			foreach($package->selection_items AS $key=>$item){
				echo '<li class="directorypress-list-group-item">';
					echo esc_html($item);
				echo '</li>';
			}
		}
	}