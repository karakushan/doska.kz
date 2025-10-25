<?php 
	/**
	 * Author Widget Template
	 * @package    DirectoryPress
	 * @subpackage DirectoryPress/public/partials/widgets/author
	 * @author     Designinvento <developers@designinvento.net>
	*/
	
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
	$price_field_id = '';
	$style = isset( $instance['style'] ) ? $instance['style'] : '';
	$field_ids = $wpdb->get_results('SELECT id, type, slug FROM '.$wpdb->prefix.'directorypress_fields');
			foreach( $field_ids as $field_id ) {
				$singlefield_id = $field_id->id;
				if($field_id->type == 'price' && ($field_id->slug == 'price' || $field_id->slug == 'Price') ){			
					$price_field_id = $singlefield_id;
				}				
			}
			
			if(!empty($price_field_id)){
				 if($style == 1 && isset($listing->fields[$price_field_id])){
					echo  '<div class="directorypress-price-style1 clearfix">';
						 $listing->display_content_field($price_field_id);
						
					echo '</div>';
				}elseif($style == 2 && isset($listing->fields[$price_field_id])){
					echo '<div class="directorypress-price-style2-range clearfix">';
						echo '<span class="price-range-string">'. wp_kses_post($listing->fields[$price_field_id]->range_options_out($listing)) .'</span>';
						echo '<span class="price-range-value">'. wp_kses_post($listing->fields[$price_field_id]->renderValueOutput($listing)) .'</span>';
					echo '</div>';
					
				}elseif($style == 3 && isset($listing->fields[$price_field_id])){
					echo  '<div class="directorypress-price-style3 clearfix">';
						$listing->display_content_field($price_field_id);
					echo '</div>';
				}else{
					
				}
			}