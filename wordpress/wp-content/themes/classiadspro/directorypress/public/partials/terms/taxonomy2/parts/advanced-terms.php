<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	$terms_count = count($terms);
	$terms_number = count($terms);
	$counter = 0;
	$tcounter = 0;
		foreach ($terms AS $key=>$term) {
			$tcounter++;
						
			
			$term_count = $instance->getCount($term);
			
			echo '<div class="advanced-terms-item">';
				if($instance->params['enable_box_link']){
					echo '<a class="advanced-terms-box-link" href="'.get_term_link($term).'"></a>';
				}	
				echo '<div class="advanced-terms-item-holder">';
					if($instance->params['enable_content_wrapper']){
						echo '<div class="advanced-terms-item-content-wrapper" style="position: '.$instance->params['wrapper_position'].'">';
					}
						if($instance->params['layout'] == 4){
							if(isset($instance->params['count']) && $instance->params['count']){
								if(isset($instance->params['count_with_text']) && $instance->params['count_with_text']){
									$count_text = (isset($instance->params['count_custom_text']) && !empty($instance->params['count_custom_text']))? $instance->params['count_custom_text'] : esc_html__('Listings', 'classiadspro');
									echo '<div class="advanced-terms-item-numbers" style="position: '.$instance->params['counter_position'].'">'.$term_count.'&nbsp;'. $count_text .'</div>';
								}else{
									echo '<div class="advanced-terms-item-numbers" style="position: '.$instance->params['counter_position'].'">'. $term_count .'</div>';
								}
							}
						}
						if($instance->params['layout'] == 1 || $instance->params['layout'] == 4){
							if(isset($instance->params['icon_type']) && $instance->params['icon_type'] == 'font_svg'){
								echo '<div class="advanced-terms-item-icon elementor-animation-'.$instance->params['icon_hover_animation'].'" style="position: '.$instance->params['icon_position'].'">';
									\Elementor\Icons_Manager::render_icon( $instance->params['icon'], [ 'aria-hidden' => 'true' ] );
								echo '</div>';
							}elseif(isset($instance->params['icon_type']) && $instance->params['icon_type'] == 'image'){
								if(isset($instance->params['icon_image']) && $instance->params['icon_image']['url']){
									echo '<div class="advanced-terms-item-icon  elementor-animation-'.$instance->params['icon_hover_animation'].'" style="position: '.$instance->params['icon_position'].'"><img src="'. $instance->params['icon_image']['url'] .'" alt="'.$term->name.'"/></div>';
								}
							}
						}
						if($instance->params['layout'] == 3){
							if(isset($instance->params['count']) && $instance->params['count']){
								if(isset($instance->params['count_with_text']) && $instance->params['count_with_text']){
									$count_text = (isset($instance->params['count_custom_text']) && !empty($instance->params['count_custom_text']))? $instance->params['count_custom_text'] : esc_html__('Listings', 'classiadspro');
									echo '<div class="advanced-terms-item-numbers" style="position: '.$instance->params['counter_position'].'">'.$term_count.'&nbsp;'. $count_text .'</div>';
								}else{
									echo '<div class="advanced-terms-item-numbers" style="position: '.$instance->params['counter_position'].'">'. $term_count .'</div>';
								}
							}
						}
						if(isset($instance->params['prefix_text']) && !empty($instance->params['prefix_text'])){
							echo '<span class="term-prefix-text"  style="position: '.$instance->params['prefix_position'].'">'. $instance->params['prefix_text'] .'</span>';
						}
						echo '<div class="advanced-terms-item-title"  style="position: '.$instance->params['title_position'].'">';
							echo '<a class="elementor-animation-'.$instance->params['hover_animation'].'" href="' . get_term_link($term) . '" title="' . $term->name .$term_count . '"><span class="term-name">' . $term->name .'</span></a>';
						echo '</div>';
						if(isset($instance->params['suffix_text']) && !empty($instance->params['suffix_text'])){
							echo '<span class="term-suffix-text"   style="position: '.$instance->params['suffix_position'].'">'. $instance->params['suffix_text'] .'</span>';
						}
						if($instance->params['layout'] == 2 || $instance->params['layout'] == 3){
							if(isset($instance->params['icon_type']) && $instance->params['icon_type'] == 'font_svg'){
								echo '<div class="advanced-terms-item-icon" style="position: '.$instance->params['icon_position'].'">';
									\Elementor\Icons_Manager::render_icon( $instance->params['icon'], [ 'aria-hidden' => 'true' ] );
								echo '</div>';
							}elseif(isset($instance->params['icon_type']) && $instance->params['icon_type'] == 'image'){
								if(isset($instance->params['icon_image']) && $instance->params['icon_image']['url']){
									echo '<div class="advanced-terms-item-icon" style="position: '.$instance->params['icon_position'].'"><img src="'. $instance->params['icon_image']['url'] .'" alt="'.$term->name.'"/></div>';
								}
							}
						}
						if($instance->params['layout'] == 1 || $instance->params['layout'] == 2){
							if(isset($instance->params['count']) && $instance->params['count']){
								if(isset($instance->params['count_with_text']) && $instance->params['count_with_text']){
									$count_text = (isset($instance->params['count_custom_text']) && !empty($instance->params['count_custom_text']))? $instance->params['count_custom_text'] : esc_html__('Listings', 'classiadspro');
									echo '<div class="advanced-terms-item-numbers" style="position: '.$instance->params['counter_position'].'">'.$term_count.'&nbsp;'. $count_text .'</div>';
								}else{
									echo '<div class="advanced-terms-item-numbers" style="position: '.$instance->params['counter_position'].'">'. $term_count .'</div>';
								}
							}
						}
					
					if($instance->params['enable_content_wrapper']){
						echo '</div>';
					}
				echo '</div>';
						
			echo '</div>';
					
			$counter++;
			if ($counter == $instance->col) {
				$counter = 0;
			}
				
		}
	//echo '</div>';