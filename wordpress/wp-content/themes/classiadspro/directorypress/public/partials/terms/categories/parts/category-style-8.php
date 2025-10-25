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
		
		echo '<div class="row directorypress-categories-wrapper '.$scroll_class . ' clearfix" '.$scroll_attr.'>';
	
			foreach ($terms AS $key=>$term) {
				$tcounter++;
					
							
					// term wrapper
					echo '<div class="directorypress-category-item col-lg-' . $instance->col . ' col-md-' . $instance->col_tab . ' col-sm-' . $instance->col_mobile . '">';
						echo '<div id="cat-wrapper-'.$term->term_id.'" class="directorypress-category-holder clearfix">';
							echo '<div class="directorypress-parent-category"><a href="' . get_term_link($term) . '" title="' . $term->name . '">' . $instance->termIcon($term->term_id) .'<span class="categories-name">'. $term->name .'</span><span class="categories-count">'.$instance->renderTermCount($term). '</span></a></div>';
							//echo $instance->_display($term->term_id, $instance->depth);	
						echo '</div>';
					echo '</div>';
						
				$counter++;
							
				if ($counter == $instance->col) {
					$counter = 0;
				}
					
			}
		echo '</div>';		
	echo '</div>';