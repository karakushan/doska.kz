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
					if ($instance->scroll == 0 && count( get_term_children( $term->term_id, DIRECTORYPRESS_CATEGORIES_TAX ) ) > 0 ) {
						$more_cat_icon = '<i class="fas fa-plus-circle" data-popup-open="' . $term->term_id .'"></i>';
					}else{
						$more_cat_icon = '';
					} 
							
					// term wrapper
					echo '<div class="directorypress-category-item col-lg-' . $instance->col . ' col-md-' . $instance->col_tab . ' col-sm-' . $instance->col_mobile . '">';
						echo '<div id="cat-wrapper-'.$term->term_id.'" class="directorypress-category-holder clearfix">';
							echo '<div class="directorypress-parent-category"><a href="' . get_term_link($term) . '" title="' . $term->name . '">' . $instance->termIcon($term->term_id) .'<span class="categories-name">'. $term->name .'</span><span class="categories-count">'.$instance->renderTermCount($term). '</span></a></div>';
							if($instance->depth > 1){
								echo wp_kses_post($instance->_display($term->term_id, $instance->depth));	
							}
						echo '</div>';
						if($instance->depth > 1){
							// modal
							echo '<div class="directorypress-custom-popup" data-popup="' . $term->term_id . '">';
								echo '<div class="directorypress-custom-popup-inner">';
									echo '<div class="sub-category"><div class="categories-title">'.esc_html__('Select your Category', 'classiadspro').'<a class="directorypress-custom-popup-close" data-popup-close="' . $term->term_id . '" href="#"><i class="far fa-times-circle"></i></a></div><ul class="cat-sub-main-ul clearfix">';			
										wp_list_categories( array(
											'orderby' => 'name',
											'show_count' => true,
											'use_desc_for_title' => false,
											'child_of' => $term->term_id,
											'hide_empty' => false,
											'taxonomy' => DIRECTORYPRESS_CATEGORIES_TAX,
											'title_li' => ''
										) ); 		 
									echo '</ul></div>';
								echo '</div>';
							echo '</div>';
						}
					echo '</div>';
						
				$counter++;
							
				if ($counter == $instance->col) {
					$counter = 0;
				}
					
			}
		echo '</div>';		
	echo '</div>';