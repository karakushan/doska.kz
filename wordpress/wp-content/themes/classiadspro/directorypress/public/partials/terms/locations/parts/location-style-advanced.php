<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	$terms_count = count($terms);
	$terms_number = count($terms);
	$counter = 0;
	$tcounter = 0;
	echo '<div id="loaction-styles'.$instance->args['id'].'" class="location-style-'.$instance->location_style.' clearfix">';
			
		foreach ($terms AS $key=>$term) {
			$tcounter++;
						
			
			$term_count = $instance->getCount($term);
			
			$instance->has_child = get_terms( DIRECTORYPRESS_LOCATIONS_TAX, array(
				'parent'    => $term->term_id,
				'hide_empty' => false
			) );
			
			echo '<div class="directorypress-advanced-location-item">';
				if($instance->params['enable_box_link']){
					echo '<a class="advanced-location-box-link" href="'.get_term_link($term).'"></a>';
				}	
				echo '<div class="directorypress-advanced-location-item-holder">';
					if($instance->depth > 1 && $instance->has_child){
						echo '<i class="location-plus-icon fas fa-plus-circle" data-popup-open="' . $term->term_id . '"></i>';
					}
					if(isset($instance->params['prefix_text']) && !empty($instance->params['prefix_text'])){
						echo '<span class="location-prefix-text"  style="position: '.$instance->params['prefix_position'].'">'. $instance->params['prefix_text'] .'</span>';
					}
					echo '<div class="directorypress-advanced-parent-location"  style="position: '.$instance->params['location_position'].'">';
						echo '<a class="elementor-animation-'.$instance->params['hover_animation'].'" href="' . get_term_link($term) . '" title="' . $term->name .$term_count . '"><span class="loaction-name">' . $term->name .'</span></a>';
					echo '</div>';
					if(isset($instance->params['suffix_text']) && !empty($instance->params['suffix_text'])){
						echo '<span class="location-suffix-text"   style="position: '.$instance->params['suffix_position'].'">'. $instance->params['suffix_text'] .'</span>';
					}
					if(isset($instance->params['count']) && $instance->params['count']){
						echo '<div class="location-advanced-item-numbers" style="position: '.$instance->params['counter_position'].'">'.$term_count.'&nbsp;'. esc_html__('Listings', 'classiadspro') .'</div>';
					}
					if(isset($instance->params['icon_type']) && $instance->params['icon_type'] == 'font_svg'){
						echo '<div class="location-advanced-item-icon" style="position: '.$instance->params['icon_position'].'">';
							\Elementor\Icons_Manager::render_icon( $instance->params['icon'], [ 'aria-hidden' => 'true' ] );
						echo '</div>';
					}elseif(isset($instance->params['icon_type']) && $instance->params['icon_type'] == 'image'){
						if(isset($instance->params['icon_image']) && $instance->params['icon_image']['url']){
							echo '<div class="location-advanced-item-icon" style="position: '.$instance->params['icon_position'].'"><img src="'. $instance->params['icon_image']['url'] .'" alt="'.$term->name.'"/></div>';
						}
					}
				echo '</div>';
						
			echo '</div>';
					
			$counter++;
			if ($counter == $instance->col) {
				$counter = 0;
			}
				
		}
	echo '</div>';