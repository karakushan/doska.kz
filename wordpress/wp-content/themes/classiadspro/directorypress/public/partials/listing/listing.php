<?php
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	$listing_style_to_show = $listing->listing_view;
	add_action('directorypress_listing_grid_thumbnail', 'directorypress_listing_grid_thumbnail', 1);
	if(!function_exists('directorypress_listing_grid_thumbnail')){
		function directorypress_listing_grid_thumbnail($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			$listing_style_to_show = $listing->listing_view;
			
			/* == Listing Image Source == */
			
			if(isset($listing->logo_image) && !empty($listing->logo_image)){
				$image_src_array = wp_get_attachment_image_src($listing->logo_image, 'full');
				$image_src = $image_src_array[0];
			}elseif(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'])){
				$image_src_array = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'];
				$image_src = $image_src_array;
			}else{
				$image_src = DIRECTORYPRESS_RESOURCES_URL.'images/no-thumbnail.jpg';
			}
			
			/* == Listing Image Dimensions == */
			$param = '';
			$width= '';
			$height= '';
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['listing_image_width_height'] == 1){
					if($listing->listing_post_style == 1){
						$width= 360;
						$height= 390;
					}elseif($listing->listing_post_style == 2){
						$width= 350;
						$height= 300;
					}elseif($listing->listing_post_style == 3){
						$width= 360;
						$height= 290;
					}elseif($listing->listing_post_style == 4){
						$width= 350;
						$height= 280;
					}elseif($listing->listing_post_style == 5){
						$width= 370;
						$height= 260;
					}elseif($listing->listing_post_style == 6){
						$width= 370;
						$height= 450;
					}elseif($listing->listing_post_style == 7){
						$width= 370;
						$height= 380;
					}elseif($listing->listing_post_style == 8){
						$width= 370;
						$height= 270;
					}elseif($listing->listing_post_style == 9){
						$width= 350;
						$height= 240;
					}elseif($listing->listing_post_style == 10){
						$width= 370;
						$height= 260;
					}elseif($listing->listing_post_style == 11){
						$width= 270;
						$height= 220;
					}elseif($listing->listing_post_style == 12){
						$width= 270;
						$height= 220;
					}elseif($listing->listing_post_style == 13){
						$width= 270;
						$height= 270;
					}elseif($listing->listing_post_style == 14){
						$width= 290;
						$height= 190;
					}elseif($listing->listing_post_style == 15){
						$width= 270;
						$height= 220;
					}elseif($listing->listing_post_style == 16){
						$width= 270;
						$height= 220;
					}elseif($listing->listing_post_style == 17){
						$width= 270;
						$height= 220;
					}elseif($listing->listing_post_style == 18){
						$width= 270;
						$height= 220;
					}elseif($listing->listing_post_style == 19){
						$width= 270;
						$height= 220;
					}else{
						$width= 370;
						$height= 260;
					}
					
				}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['listing_image_width_height'] == 3){
					$width = '';
					$height = '';
				}else{
					$width = $listing->listing_image_width;
					$height = $listing->listing_image_height;
				}
				
				$param = array(
					'width' => $width,
					'height' => $height,
					'crop' => true
				);
			$src_url = ($DIRECTORYPRESS_ADIMN_SETTINGS['listing_image_width_height'] == 1 || $DIRECTORYPRESS_ADIMN_SETTINGS['listing_image_width_height'] == 2)? bfi_thumb($image_src, $param) : $image_src;
			echo '<a class="listing-thumbnail" href="'.get_permalink().'"><img alt="'.$listing->title().'" src="'. esc_url($src_url) .'" width="'.$width.'" height="'.$height.'" /></a>';
		
		}
	}
	add_action('directorypress_listing_listview_thumbnail', 'directorypress_listing_listview_thumbnail', 1);
	if(!function_exists('directorypress_listing_listview_thumbnail')){
		function directorypress_listing_listview_thumbnail($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			
			/* == Listing Image Source == */
			
			if(isset($listing->logo_image) && !empty($listing->logo_image)){
				$image_src_array = wp_get_attachment_image_src($listing->logo_image, 'full');
				$image_src = $image_src_array[0];
			}elseif(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'])){
				$image_src_array = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'];
				$image_src = $image_src_array;
			}else{
				$image_src = DIRECTORYPRESS_RESOURCES_URL.'images/no-thumbnail.jpg';
			}
			
			/* == Listing Image Dimensions == */
			$param = '';	
			$width= $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_logo_width_listview'];
			$height= $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_logo_height_listview'];
			
			$param = array(
				'width' => $width,
				'height' => $height,
				'crop' => true
			);
			echo '<a class="listing-thumbnail" href="'.get_permalink().'"><img alt="'.$listing->title().'" src="'. bfi_thumb($image_src, $param).'" width="'.$width.'" height="'.$height.'" /></a>';
		}
	}
	add_action('directorypress_listing_grid_featured_tag', 'directorypress_listing_grid_featured_tag', 1);
	if(!function_exists('directorypress_listing_grid_featured_tag')){
		function directorypress_listing_grid_featured_tag($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			$feature_tag_style = (isset($listing->listing_has_featured_tag_style) && !empty($listing->listing_has_featured_tag_style ))? $listing->listing_has_featured_tag_style : $listing->listing_post_style;				
			
			if($feature_tag_style == 1){
				$feature_tag = '<span class="has_featured-tag-1">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 2){
				$feature_tag = '<span class="has_featured-tag-2">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 3){
				$feature_tag = '<span class="has_featured-tag-3"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
			}elseif($feature_tag_style == 4){
				$feature_tag = '<span class="has_featured-tag-4"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
			}elseif($feature_tag_style == 5){
				$feature_tag = '<span class="has_featured-tag-5">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 6){
				$feature_tag = '<span class="has_featured-tag-6">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 7){
				$feature_tag = '<span class="has_featured-tag-7"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
			}elseif($feature_tag_style == 8){
				$feature_tag = '<span class="has_featured-tag-8"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
			}elseif($feature_tag_style == 9){
				$feature_tag = '<span class="has_featured-tag-9"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
			}elseif($feature_tag_style == 10){
				$feature_tag = '<span class="has_featured-tag-10">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 11){
				$feature_tag = '<span class="has_featured-tag-11">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 12){
				$feature_tag = '<span class="has_featured-tag-12">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 13){
				$feature_tag = '<span class="has_featured-tag-13">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 14){
				$feature_tag = '<span class="has_featured-tag-14">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 15){
				$feature_tag = '<span class="has_featured-tag-15">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 16){
				$feature_tag = '<span class="has_featured-tag-15">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 17){
				$feature_tag = '<span class="has_featured-tag-17">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 18){
				$feature_tag = '<span class="has_featured-tag-18">'.esc_html__('Featured', 'classiadspro').'</span>';
			}elseif($feature_tag_style == 19){
				$feature_tag = '<span class="has_featured-tag-19">'.esc_html__('Featured', 'classiadspro').'</span>';
			}else{
				$feature_tag = '<span class="has_featured-tag-default">'.esc_html__('Featured', 'classiadspro').'</span>';
			}
			if ($listing->package->has_featured){
				echo wp_kses_post($feature_tag);
			}
		}
	}
	add_action('directorypress_listing_grid_status_tag', 'directorypress_listing_grid_status_tag', 1);
	if(!function_exists('directorypress_listing_grid_status_tag')){
		function directorypress_listing_grid_status_tag($listing){
			
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object, $wpdb;
			
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			foreach( $field_ids as $field_id ) {
				$singlefield_id = $field_id->id;
				//if($field_id->on_exerpt_page){	
					if($field_id->type == 'status' && (($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list))){			
						$listing->display_content_field($singlefield_id);
					}
				//}
			}
		}
	}
	add_action('directorypress_listing_grid_author', 'directorypress_listing_grid_author', 10, 2);
	if(!function_exists('directorypress_listing_grid_author')){
		function directorypress_listing_grid_author($listing, $size){
			
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			
			$authorID = get_the_author_meta( 'ID', $listing->post->post_author);
			$avatar_id = get_user_meta( $authorID, 'avatar_id', true );
			echo '<div class="listng-author-img">';
				echo '<a href="'. esc_url(directorypress_author_page_url($authorID)) .'">';
					if(!empty($avatar_id) && is_numeric($avatar_id)) {
						$attachment = wp_get_attachment_image_src( $avatar_id, 'full' ); 
						$src = $attachment[0];
						$params = array( 'width' => $size, 'height' => $size, 'crop' => true );
						echo "<img class='directorypress-user-avatar' src='" . bfi_thumb($src, $params ) . "' alt='author' />";
					} else { 
						$avatar_url = get_avatar_url ( get_the_author_meta('user_email', $authorID), $size = $size );
						echo '<img src="'.$avatar_url.'" alt="author" width="'.$size.'" height="'.$size.'" />';		
					}
					if ( directorypress_is_user_online($authorID) ){
						echo '<span class="author-active"></span>';
					} else {
						echo '<span class="author-in-active"></span>';
					}
				echo '</a>';
			echo '</div>';
		}
	}
	add_action('directorypress_listing_grid_category', 'directorypress_listing_grid_category', 10, 1);
	if(!function_exists('directorypress_listing_grid_category')){
		function directorypress_listing_grid_category($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			
			$parent_terms = wp_get_post_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('parent' => 0) );
			$parent_term = (is_array($parent_terms) || is_object($parent_terms))? array_pop($parent_terms): '';
			$parent_term_link = (!empty($parent_term))? '<a class="listing-cat" href="'.get_term_link($parent_term->slug, DIRECTORYPRESS_CATEGORIES_TAX).'" rel="tag">'.$parent_term->name.'</a>': '';
			//$parent = (!empty($parent_term))? $parent_term->term_id : 0;
			$child_term_link = '';
			$seperator = '';
			if($parent_term){
			$child_terms = wp_get_post_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('parent' => $parent_term->term_id ) );
			$child_term = (is_array($child_terms) || is_object($child_terms))? array_pop($child_terms): '';
			$child_term_link = (!empty($child_term))? '<a class="listing-cat" href="'.get_term_link($child_term->slug, DIRECTORYPRESS_CATEGORIES_TAX).'" rel="tag">'.$child_term->name.'</a>': '';
			
			$seperator = (!empty($child_term))? '<span class="cat-seperator fas fa-angle-right"></span>': '';
			}
			echo '<div class="cat-wrapper">';
				echo wp_kses_post($parent_term_link . $seperator . $child_term_link);
			echo '</div>';
		}
	}
	add_action('directorypress_listing_grid_category_icon', 'directorypress_listing_grid_category_icon', 10, 1);
	if(!function_exists('directorypress_listing_grid_category_icon')){
		function directorypress_listing_grid_category_icon($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS;
			$cat_icon = '';
			$cat_color = '';
			$terms = wp_get_post_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX);
			if(is_array($terms)){
				foreach ($terms AS $key=>$term){
					if($DIRECTORYPRESS_ADIMN_SETTINGS['cat_icon_type_on_listing'] == 1){
						if($listing->listing_post_style == 13){
							$cat_color_set = get_listing_category_color($term->term_id);
							if(!empty($cat_color_set)){
								$cat_color = $cat_color_set;
							}elseif(isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_icon_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_icon_color']['color'])){
								$cat_color = $DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_icon_color']['rgba'];
							}else{
								$cat_color = '';
							}
							$icon_file = get_listing_category_font_icon($term->term_id);
							$icon = '<span class="font-icon" style="color:'.$cat_color.'; border-color:'.$cat_color.';"><span class="cat-icon '.$icon_file.'"></span></span>';	
							if($icon_file){
								$cat_icon =  $icon;
							}else{
								$cat_icon = '<span class="font-icon" style="color:'.$cat_color.'; border-color:'.$cat_color.';"><span class="cat-icon directorypress-icon-folder-o"></span></span>';
							}
						}else{
							$cat_color_set = get_listing_category_color($term->term_id);
							if(!empty($cat_color_set)){
								$cat_color = 'style="background-color:'.$cat_color_set.';"';
							}else{
								$cat_color = '';
							}
							$icon_file = get_listing_category_font_icon($term->term_id);
							$icon = '<span class="font-icon" '.$cat_color.'><span class="cat-icon '.$icon_file.'"></span></span>';	
							if($icon_file){
								$cat_icon =  $icon;
							}else{
								$cat_icon = '<span class="font-icon" '.$cat_color.'><span class="cat-icon directorypress-icon-folder-o"></span></span>';
							}
						}
						
					}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['cat_icon_type_on_listing'] == 2){
						$icon_file = get_listing_category_icon_url_for_listing($term->term_id);
						$icon = '<img class="directorypress-cat-icon" src="' . $icon_file . '" alt="listing cat" />';
						if(!empty($icon_file)){
							$cat_icon =  $icon;
						}else{
							$cat_icon = '';
						}
						
					}else{
						
						$cat_color = 'style="background-color:'.$cat_color_set.';"';
						
						$icon_file = get_listing_category_font_icon($term->term_id);
						$icon = '<span class="font-icon" '.$cat_color.'><span class="cat-icon '.$icon_file.'"></span></span>';	
						if($icon_file){
							$cat_icon =  $icon;
						}else{
							$cat_icon = '';
						}
					}
				}
			}
			
			echo wp_kses_post($cat_icon);
		}
	}
	add_action('directorypress_listing_grid_title', 'directorypress_listing_grid_title', 10, 1);
	if(!function_exists('directorypress_listing_grid_title')){
		function directorypress_listing_grid_title($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			
			$title_limit = $DIRECTORYPRESS_ADIMN_SETTINGS['max_title_length'];
			$nofollow = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_nofollow_link']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_nofollow_link'])? 'rel="nofollow"':'';
			
			echo '<header class="directorypress-listing-title">';
				if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_exert_type'] == 'words'){
					echo '<h2><a href="'.get_permalink().'" title="'.esc_attr($listing->title()).'" '.$nofollow.'>';
					echo wp_trim_words($listing->title(), $title_limit, '');
					do_action('directorypress_listing_user_verification_tag', $listing);
					echo'</a></h2>';
				}else{
					echo '<h2><a href="'.get_permalink().'" title="'.esc_attr($listing->title()).'" '.$nofollow.'>';
					echo substr($listing->title(), 0, $title_limit);
					do_action('directorypress_listing_user_verification_tag', $listing);
					echo '</a></h2>';
					
				}
			echo '</header>';
		}
	}
	
	add_action('directorypress_listing_grid_inline_fields', 'directorypress_listing_grid_inline_fields', 10, 1);
	if(!function_exists('directorypress_listing_grid_inline_fields')){
		function directorypress_listing_grid_inline_fields($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object, $wpdb;
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			$include = array(
				'select',
				'radio',
				'digit',
			);
			if(isField_on_exerpt()){
				if(isField_inLine() && isField_not_empty($listing)){
					echo '<div id="fields-block-inline'.$listing->post->ID.'" class="grid-fields-wrapper inline-fields clearfix" data-id="'.$listing->post->ID.'">';
						foreach( $field_ids as $field_id ) {
							$singlefield_id = $field_id->id;
							if((($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list)) && $field_id->is_field_in_line){	
								if(in_array($field_id->type, $include)){			
									$listing->display_content_field($singlefield_id);
								}
							}
						}
					echo '</div>';
				}
			}
		}
	}
	add_action('directorypress_listing_grid_block_fields', 'directorypress_listing_grid_block_fields', 10, 1);
	if(!function_exists('directorypress_listing_grid_block_fields')){
		function directorypress_listing_grid_block_fields($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object, $wpdb;
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			$exclude = array(
				'summary',
				'content',
				'address',
				'category',
				'status'
			);
			if(isField_on_exerpt()  && isField_not_empty($listing)){
				echo '<div class="grid-fields-wrapper block-fields clearfix">';
					foreach( $field_ids as $field_id ) {
						$singlefield_id = $field_id->id;
						if((($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list)) && !$field_id->is_field_in_line){	
							if((!in_array($field_id->type, $exclude)) && ($field_id->type != 'price' && $field_id->slug != 'price')){			
								$listing->display_content_field($singlefield_id);
							}
						}
					}
				echo '</div>';
			}
		}
	}
	add_action('directorypress_listing_grid_tooltip_fields', 'directorypress_listing_grid_tooltip_fields', 10, 1);
	if(!function_exists('directorypress_listing_grid_tooltip_fields')){
		function directorypress_listing_grid_tooltip_fields($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object, $wpdb;
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			$include = array(
				'link',
				'email',
				'text',
			);
			if(isField_on_exerpt() && isField_inLine()){
				echo '<div id="fields-block-inline-tooltip'.$listing->post->ID.'" class="inline-tooltip-fields clearfix" data-id="'.$listing->post->ID.'">';
					foreach( $field_ids as $field_id ) {
						$singlefield_id = $field_id->id;
						if(($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list)){	
							$array = unserialize($field_id->options);
							if(isset($array['is_phone'])){
								$is_phone = $array['is_phone'];
							}else{
								$is_phone = 0;
							}
							if((in_array($field_id->type, $include)) && ($field_id->type == 'text' && $is_phone == 1)){			
								$listing->display_content_field($singlefield_id);
							}
						}
					}
				
				echo '</div>';
			}
		}
	}
	add_action('directorypress_listing_grid_summary_field', 'directorypress_listing_grid_summary_field', 10, 1);
	if(!function_exists('directorypress_listing_grid_summary_field')){
		function directorypress_listing_grid_summary_field($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object, $wpdb;
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			
			if(isField_on_exerpt()){
				echo '<div class="grid-exerpt-field clearfix">';
					foreach( $field_ids as $field_id ) {
						$singlefield_id = $field_id->id;
						if($field_id->type == 'summary' && (($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page == 1) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list == 1))){	
							$listing->display_content_field($singlefield_id);
						}
					}
				echo '</div>';
			}
		}
	}
	add_action('directorypress_listing_grid_address', 'directorypress_listing_grid_address', 10, 1);
	if(!function_exists('directorypress_listing_grid_address')){
		function directorypress_listing_grid_address($listing){
			
			if($listing->locations){
				echo '<p class="listing-location">';
						do_action('location_for_grid_and_list', $listing, true);
				echo '</p>';
			}
		}
	}
	add_action('directorypress_listing_grid_price_field', 'directorypress_listing_grid_price_field', 10, 1);
	if(!function_exists('directorypress_listing_grid_price_field')){
		function directorypress_listing_grid_price_field($listing){
			global $wpdb;
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			echo '<div class="price">';
				foreach( $field_ids as $field_id ) {
					$singlefield_id = $field_id->id;
					if($field_id->type == 'price' && ($field_id->slug == 'price' || $field_id->slug == 'Price') && (($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list))){				
						//if($field_id->on_exerpt_page == 1){
							$listing->display_content_field($singlefield_id);
						//}
					}				
				}	
			echo '</div>';
		}
	}
	
	add_action('directorypress_listing_grid_ratting', 'directorypress_listing_grid_ratting', 10, 1);
	if(!function_exists('directorypress_listing_grid_ratting')){
		function directorypress_listing_grid_ratting($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS, $direviews_plugin;
			
			if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ratings_addon']){
				$rating = (method_exists( $direviews_plugin, 'get_average_rating' ))? $direviews_plugin->get_average_rating( $listing->post->ID) : '';
				if (!empty($rating)){
					echo '<div class="listing-rating grid-rating">';
						echo '<span class="rating-numbers">'.get_average_listing_rating().'</span>';
						echo '<span class="rating-stars">';
							display_average_listing_rating();
						echo '</span>';
					echo '</div>';
				}else{
					echo '<div class="listing-rating grid-rating">';
						echo '<span class="rating-numbers-empty"><i class="far fa-frown-open"></i></span>';
						echo '&nbsp;<span class="review_rate-empty"><a class="" href="'. get_permalink() .'#comments-reviews" data-bs-toggle="tooltip" title="'.esc_html__('Be first to rate', 'classiadspro').'">'.esc_html__('Rate Now', 'classiadspro').'</a></span>';
					echo '</div>';
				}
			}
		}
	}
	add_action('directorypress_listing_grid_bookmark', 'directorypress_listing_grid_bookmark', 10, 4);
	if(!function_exists('directorypress_listing_grid_bookmark')){
		function directorypress_listing_grid_bookmark($listing, $style, $in_favourites_icon = 'dicode-material-icons dicode-material-icons-heart', $not_in_favourites_icon = 'dicode-material-icons dicode-material-icons-heart'){
			global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
			$style_calss = 'style'. $style;
			
			if (directorypress_bookmark_list($listing->post->ID)){
				
				$link = '<a id="'.$listing->post->ID.'" href="javascript:void(0);" class="add_to_favourites btn" data-listingid="'. $listing->post->ID .'" data-bs-toggle="tooltip" title="'. esc_attr__('Remove Bookmarks', 'classiadspro').'" data-in_favourites_icon="'. esc_attr($in_favourites_icon) .'" data-not_in_favourites_icon="'. esc_attr($not_in_favourites_icon) .'">';
					$link .= '<span class="favourite-icon '. esc_attr($style_calss) .' checked '. esc_attr($in_favourites_icon) .'"></span>';
					if($style == 4){
						$link .= '<span class="bookmark-text">'. esc_html__('bookmarked', 'classiadspro') .'</span>';
					}
				$link .= '</a>';
				
			}else{
				$link = '<a id="'.$listing->post->ID.'" href="javascript:void(0);" class="add_to_favourites btn" data-listingid="'.$listing->post->ID.'" data-bs-toggle="tooltip" title="'. esc_attr__('Bookmark', 'classiadspro').'" data-in_favourites_icon="'. esc_attr($in_favourites_icon) .'" data-not_in_favourites_icon="'. esc_attr($not_in_favourites_icon) .'">';
					$link .= '<span class="favourite-icon '. esc_attr($style_calss) .' unchecked '. esc_attr($not_in_favourites_icon) .'"></span>';
					if($style == 4){
						$link .= '<span class="bookmark-text">'. esc_html__('bookmark', 'classiadspro') .'</span>';
					}
				$link .= '</a>';
			}
		
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_favourites_list'] && $directorypress_object->action != 'myfavourites'){
				echo wp_kses_post($link);
			}
		}
	}
	add_action('directorypress_listing_grid_views', 'directorypress_listing_grid_views', 10, 1);
	if(!function_exists('directorypress_listing_grid_views')){
		function directorypress_listing_grid_views($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
			
			echo '<p class="listing-views">'.sprintf(__('Views: %d', 'classiadspro'), (get_post_meta($listing->post->ID, '_total_clicks', true) ? get_post_meta($listing->post->ID, '_total_clicks', true) : 0)).'</p>';
		}
	}
	add_action('directorypress_listing_user_verification_tag', 'directorypress_listing_user_verification_tag_function', 10, 1);
	if(!function_exists('directorypress_listing_user_verification_tag_function')){
		function directorypress_listing_user_verification_tag_function($listing){
			
			$authorID = $listing->post->post_author;
			$mobile = get_user_meta($authorID, 'phone_verification_status', true );
			$email = get_user_meta($authorID, 'email_verification_status', true );
			if($mobile == 'verified' || $email == 'verified'){
				echo '<i class="user-verification-tag verified dicode-material-icons dicode-material-icons-checkbox-marked-circle"></i>';
			}
			
		}
	}
	if($listing_style_to_show == 'show_grid_style'){
			if ($directorypress_object->directorypress_get_property_of_shortcode('directorypress-main', 'is_favourites') && directorypress_bookmark_list($listing->post->ID)){
				echo '<div class="directorypress-remove-from-favourites-list" data-listingid="'.$listing->post->ID.'" title="'.esc_attr(__('Remove from favourites list', 'classiadspro')).'"></div>';
			}
			
				
				
				if($listing->listing_post_style == 1){
					directorypress_display_template('partials/listing/parts/template-grid-1.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 2){
					directorypress_display_template('partials/listing/parts/template-grid-2.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 3){
					directorypress_display_template('partials/listing/parts/template-grid-3.php', array('listing' => $listing));	
				}elseif($listing->listing_post_style == 4){
					directorypress_display_template('partials/listing/parts/template-grid-4.php', array('listing' => $listing));	
				}elseif($listing->listing_post_style == 5){
					directorypress_display_template('partials/listing/parts/template-grid-5.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 6){
					directorypress_display_template('partials/listing/parts/template-grid-6.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 7){
					directorypress_display_template('partials/listing/parts/template-grid-7.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 8){
					directorypress_display_template('partials/listing/parts/template-grid-8.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 9){
					directorypress_display_template('partials/listing/parts/template-grid-9.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 10){
					directorypress_display_template('partials/listing/parts/template-grid-10.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 11){
					directorypress_display_template('partials/listing/parts/template-grid-11.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 12){
					directorypress_display_template('partials/listing/parts/template-grid-12.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 13){
					directorypress_display_template('partials/listing/parts/template-grid-13.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 14){
					directorypress_display_template('partials/listing/parts/template-grid-14.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 15){
					directorypress_display_template('partials/listing/parts/template-grid-15.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 16){
					directorypress_display_template('partials/listing/parts/template-grid-16.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 17){
					directorypress_display_template('partials/listing/parts/template-grid-17.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 18){
					directorypress_display_template('partials/listing/parts/template-grid-18.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 19){
					directorypress_display_template('partials/listing/parts/template-grid-19.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 'mobile_1'){
					directorypress_display_template('partials/listing/parts/template-grid-mobile-1.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 'default'){
					directorypress_display_template('partials/listing/parts/template-grid-default.php', array('listing' => $listing));
				}elseif($listing->listing_post_style == 'footer_widget'){
					directorypress_display_template('partials/listing/parts/template-widget-1.php', array('listing' => $listing));
				}
			
	}elseif($listing_style_to_show == 'show_list_style'){
			if ($directorypress_object->directorypress_get_property_of_shortcode('directorypress-main', 'is_favourites') && directorypress_bookmark_list($listing->post->ID)){
					echo '<div class="directorypress-remove-from-favourites-list" data-listingid="'.$listing->post->ID.'" title="'.esc_attr(__('Remove from favourites list', 'classiadspro')).'"></div>';
			}
			if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_listview_post_style'] == 'listview_default'){
				directorypress_display_template('partials/listing/parts/template-list-1.php', array('listing' => $listing));
			}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_listview_post_style'] == 'listview_ultra'){
				directorypress_display_template('partials/listing/parts/template-list-2.php', array('listing' => $listing));
			}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_listview_post_style'] == 'listview_mod'){
				directorypress_display_template('partials/listing/parts/template-list-3.php', array('listing' => $listing));
			}else{
				
			}
	}