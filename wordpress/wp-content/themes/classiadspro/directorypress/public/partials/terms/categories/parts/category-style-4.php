<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	
	echo '<div id="directorypress-category-'.$instance->args['id'].'" class="cat-style-'.$instance->cat_style.'">';
			
		$terms_count = count($terms);
		$terms_number = count($terms);
		$counter = 0;
		$tcounter = 0;
		if($instance->scroll == 1){
			$scroll_attr = $instance->slick_attrs;
			$scroll_class = 'dp-slick-carousel';
		}else{
			$scroll_attr = '';
			$scroll_class = '';
		}
		
		echo '<div class="row directorypress-categories-wrapper  '. $instance->col .' '.$scroll_class . ' clearfix" '.$scroll_attr.'>';
	
			foreach ($terms AS $key=>$term) {
				$tcounter++;
					if ($instance->scroll == 0 && count( get_term_children( $term->term_id, DIRECTORYPRESS_CATEGORIES_TAX ) ) > 0 ) {
						$more_cat_icon = '<i class="fas fa-plus-circle" data-popup-open="' . $term->term_id .'"></i>';
					}else{
						$more_cat_icon = '';
					} 
							
					// term wrapper
					echo '<div class="directorypress-category-item col-lg-' . $instance->col . ' col-md-' . $instance->col_tab . ' col-sm-' . $instance->col_mobile . '">';
						echo '<div id="cat-wrapper-'.$term->term_id.'" class="directorypress-category-holder clearfix">';		
							echo '<div class="directorypress-parent-category"><a href="' . get_term_link($term) . '" title="' . $term->name . '">' . $instance->termIcon($term->term_id) . $term->name .'</a></div>';
						echo '</div>';
					echo '</div>';
						
				$counter++;
							
				if ($counter == $instance->col) {
					$counter = 0;
				}
					
			}
		echo '</div>';		
	echo '</div>';