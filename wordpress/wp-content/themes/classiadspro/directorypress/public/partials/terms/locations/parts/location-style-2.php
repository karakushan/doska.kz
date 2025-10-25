<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	$terms_count = count($terms);
	$terms_number = count($terms);
	$counter = 0;
	$tcounter = 0;
	$row = '';
	$gutter = 'padding:'.$instance->location_padding.'px;';
	echo '<div id="loaction-styles'.$instance->args['id'].'" class="'.$row.' location-style'.$instance->location_style.' grid-item directorypress-locations-columns clearfix" style="'.$gutter.'">';
			
		foreach ($terms AS $key=>$term) {
			$tcounter++;
						
			if ($instance->count && $instance->location_style != 2){
				$term_count = ' ('.$instance->getCount($term).')';
			}elseif ($instance->count ){
				$term_count = $instance->getCount($term);
			}else{
				$term_count = '';
			}
			$instance->has_child = get_terms( DIRECTORYPRESS_LOCATIONS_TAX, array(
				'parent'    => $term->term_id,
				'hide_empty' => false
			) );
					
			if($instance->location_style == 7){
				$icon_image = '';
			}else{
				$icon_image = '<span class="location-icon"><i class="fas fa-map-marker-alt"></i></span>';
			}
			
			echo '<div class="directorypress-location-item col-lg-' . $instance->col . ' col-md-' . $instance->col_tab . ' col-sm-' . $instance->col_mobile . '">';
						
				echo '<div class="directorypress-location-item-holder">';
					echo '<div class="directorypress-parent-location">';
						echo '<a href="' . get_term_link($term) . '" title="' . $term->name .$term_count . '">';
							echo '<span class="loaction-name">' . $term->name .'</span>';
							if($instance->count){
								echo '<span class="location-item-numbers">('.$term_count.')</span>';
							}
						echo '</a>';
					echo '</div>';
					if($instance->depth > 1){
						echo wp_kses_post($instance->_display($term->term_id, $instance->depth));
					}
				echo '</div>';	
						
			echo '</div>';
					
			$counter++;
			if ($counter == $instance->col) {
				$counter = 0;
			}
				
		}
	echo '</div>';