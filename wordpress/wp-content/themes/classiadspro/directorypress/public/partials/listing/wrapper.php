<?php	
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
	$listing_style_to_show = $public_handler->listing_view;
	
	$grid_padding = (isset($public_handler->args['grid_padding']))? $public_handler->args['grid_padding'] : $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_grid_padding'];
	$directorypress_grid_margin_bottom = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_grid_margin_bottom'];
	
	$carousel = ($public_handler->args['scroll'] == 1)? 'carousel-active' : 'no-carousel';
	
	$bookmark_page_class = '';
	$bookmark_page_class = (directorypress_is_bookmark_page())? 'infavourites' : '';

	if($listing_style_to_show == 'show_grid_style'){
		$listing_block_classes = 'directorypress-listings-grid '.$bookmark_page_class.' directorypress-listings-grid-'.$public_handler->args['listings_view_grid_columns'];
	}else{
		$listing_block_classes = 'cz-listview';
	}
	$masonry_attr = 'data-masonry-false';
	$masonry_wrapper = '';
	$data_masonry = '';
	if ($listing_style_to_show == 'show_grid_style' && !$public_handler->args['scroll']){
		$masonry_attr = 'data-masonry';
		$masonry_wrapper = 'm-grid';
	}
	
	$grid_wrapper_margin = ($listing_style_to_show == 'show_grid_style')? 'style="margin-left:-'. $grid_padding .'px;margin-right: -'. $grid_padding .'px;"' : '';
	$data_unique_id = ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_grid_masonry_display'])? 'data-uniqid="'. $public_handler->hash .'"' :''; 
	$slick_attrs = '';
	if ($public_handler->args['scroll']){
		$slider_arrow_position = (isset($public_handler->args['slider_arrow_position']))? $public_handler->args['slider_arrow_position']: 'absolute';
		$slider_arrow_icon_pre = (!empty($public_handler->args['slider_arrow_icon_pre']))? $public_handler->args['slider_arrow_icon_pre'] : 'fas fa-angle-left';
		$slider_arrow_icon_next = (!empty($public_handler->args['slider_arrow_icon_next']))? $public_handler->args['slider_arrow_icon_next'] : 'fas fa-angle-right';

		$slick_attrs = 'data-items="'. $public_handler->args['desktop_items'] .'"';
		$slick_attrs .= 'data-items-tablet="'. $public_handler->args['tab_items'] .'"';
		$slick_attrs .= 'data-items-mobile="'. $public_handler->args['mobile_items'] .'"';
		$slick_attrs .= 'data-slide-to-scroll="1"';
		$slick_attrs .= 'data-slide-speed="1000"';
		$slick_attrs .= ($public_handler->args['autoplay'])? 'data-autoplay="true"' : 'data-autoplay="false"';
		$slick_attrs .= 'data-center-padding=""';
		$slick_attrs .= 'data-center="false"';
		$slick_attrs .= 'data-autoplay-speed="'. $public_handler->args['autoplay_speed'] .'"';
		$slick_attrs .= ($public_handler->args['loop'])? 'data-loop="true"' : 'data-loop="false"';
		$slick_attrs .= 'data-list-margin="'.$grid_padding.'"';
		$slick_attrs .= ($public_handler->args['owl_nav'])? 'data-arrow="true"': 'data-arrow="false"';
		$slick_attrs .= 'data-prev-arrow="'. $slider_arrow_icon_pre .'"';
		$slick_attrs .= 'data-next-arrow="'. $slider_arrow_icon_next .'"';
		$slick_attrs .= 'data-arrow-postion ="'. $slider_arrow_position .'"';
	}

	echo '<div class="listing-parent" id="directorypress-handler-'. esc_attr($public_handler->hash) .'" data-handler-hash="'. esc_attr($public_handler->hash) .'">';
		
		echo '<script>
			directorypress_handler_args_array["'. esc_attr($public_handler->hash) .'"] = '. json_encode(array_merge(array('handler' => $public_handler->directorypress_client, 'base_url' => $public_handler->base_url), $public_handler->args)) .';	
		</script>';
		if ($public_handler->do_initial_load){
			echo '<div class="directorypress-container-fluid directorypress-listings-block '. esc_attr($listing_block_classes) .'">';
				do_action('directorypress_listing_sorting_panel', $public_handler, $listing_style_to_show);
				
				if ($public_handler->listings){
					// === БЛОК РЕКЛАМИРУЕМЫХ ТОВАРОВ ===
					$advertised_post_ids = array();
					// Не показываем на главной странице
					$show_advertised = !is_front_page();
					
					if ($show_advertised) {
						$advertised_count = apply_filters('classiadspro_advertised_listings_count', 3);
						
						$advertised_args = array(
							'post_type' => DIRECTORYPRESS_POST_TYPE,
							'post_status' => 'publish',
							'posts_per_page' => $advertised_count,
							'meta_query' => array(
								array(
									'key' => '_is_advertised',
									'value' => '1',
									'compare' => '='
								)
							)
						);
						
						if (!empty($public_handler->args['directorytypes'])) {
							if ($directorytypes_ids = array_filter(explode(',', $public_handler->args['directorytypes']), 'trim')) {
								$advertised_args = directorypress_set_directory_args($advertised_args, $directorytypes_ids);
							}
						}
						
						if (!empty($public_handler->args['categories'])) {
							$category_ids = array_filter(explode(',', $public_handler->args['categories']), 'trim');
							if (!empty($category_ids) && !in_array('0', $category_ids)) {
								$advertised_args['tax_query'] = isset($advertised_args['tax_query']) ? $advertised_args['tax_query'] : array();
								$advertised_args['tax_query'][] = array(
									'taxonomy' => DIRECTORYPRESS_CATEGORIES_TAX,
									'field' => 'term_id',
									'terms' => $category_ids,
									'include_children' => true
								);
							}
						}
						
						if (!empty($public_handler->args['locations'])) {
							$location_ids = array_filter(explode(',', $public_handler->args['locations']), 'trim');
							if (!empty($location_ids) && !in_array('0', $location_ids)) {
								$advertised_args['tax_query'] = isset($advertised_args['tax_query']) ? $advertised_args['tax_query'] : array();
								$advertised_args['tax_query'][] = array(
									'taxonomy' => DIRECTORYPRESS_LOCATIONS_TAX,
									'field' => 'term_id',
									'terms' => $location_ids,
									'include_children' => true
								);
							}
						}
						
						$advertised_query = new WP_Query($advertised_args);
						
						if ($advertised_query->have_posts()) {
							$advertised_title = apply_filters('classiadspro_advertised_listings_title', __('Рекомендуемые объявления', 'classiadspro'));
							
							echo '<div class="advertised-listings-wrapper">';
								echo '<div class="advertised-listings-header">';
									echo '<h3 class="advertised-listings-title">' . esc_html($advertised_title) . '</h3>';
								echo '</div>';
								
								echo '<div class="advertised-listings-content">';
								
								while ($advertised_query->have_posts()) {
									$advertised_query->the_post();
									$listing_id = get_the_ID();
									$advertised_post_ids[] = $listing_id;
									
									if (isset($public_handler->listings[$listing_id])) {
										$advertised_listing = $public_handler->listings[$listing_id];
									} else {
										$advertised_listing = new directorypress_listing();
										$advertised_listing->is_widget = (isset($public_handler->args['is_widget'])) ? $public_handler->args['is_widget'] : 0;
										$advertised_listing->directorypress_init_lpost_listing(get_post());
										$advertised_listing->listing_post_style = $public_handler->args['listing_post_style'];
										$advertised_listing->listing_image_width = $public_handler->args['listing_image_width'];
										$advertised_listing->listing_image_height = $public_handler->args['listing_image_height'];
										$advertised_listing->fchash = $public_handler->hash;
										$advertised_listing->listings_view_type = $public_handler->args['listings_view_type'];
										$advertised_listing->listing_view = $listing_style_to_show;
										$advertised_listing->listing_has_featured_tag_style = $public_handler->args['listing_has_featured_tag_style'];
										$public_handler->listings[$listing_id] = $advertised_listing;
									}
									
									$listing_classes = $public_handler->get_listing_location_class();
									$listing_classes .= ' advertised-listing';
									$listing_classes .= ($listing_style_to_show == 'show_grid_style')? ' listing-post-style-'. $public_handler->args['listing_post_style'] : '';
									$listing_classes .= ($listing_style_to_show == 'show_grid_style')? ' listing-grid-item' : '';
									$listing_classes .= ($advertised_listing->package->has_featured)? ' directorypress-has_featured': '';
									$listing_classes .= ($advertised_listing->package->has_sticky)? ' directorypress-has_sticky': '';
									
									$listing_inline_css = '';
									
									echo '<article id="post-'. esc_attr($listing_id) .'" class="row directorypress-listing clearfix '. esc_attr($listing_classes) .'" '. wp_kses_post($listing_inline_css) .'>';
										echo '<div class="directorypress-listing-item-holder clearfix">';
											$advertised_listing->display($public_handler);
										echo '</div>';
									echo '</article>';
								}
								
								echo '</div>'; // .advertised-listings-content
								
							echo '</div>'; // .advertised-listings-wrapper
							
							wp_reset_postdata();
						}
					}
					// === КОНЕЦ БЛОКА РЕКЛАМИРУЕМЫХ ТОВАРОВ ===
					
					echo '<div id="listing-block-'. esc_attr($public_handler->hash) .'" class="directorypress-listings-block-content '. esc_attr($carousel) .'  '. esc_attr($masonry_wrapper) .' clearfix" '. wp_kses_post($grid_wrapper_margin) .' '. wp_kses_post($data_unique_id) .' '. wp_kses_post($data_masonry) .'>';
						if(!empty($public_handler->args['custom_category_link'])){
							echo '<div class="custom-category-link">';
								echo '<a href="'. esc_url($public_handler->args['custom_category_link']) .'">'. esc_html($public_handler->args['custom_category_link_text']) .'</a>';
							echo '</div>';
						}
						
						if ($public_handler->args['scroll']){
							echo '<div class="dp-slick-carousel owl-on-grid" '. wp_kses_post($slick_attrs) .'>';
						}
						if($listing_style_to_show == 'show_list_style'){
							echo '<div class="listing-list-view-inner-wrap '. esc_attr($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_listview_post_style']) .'">';
						}
							while ($public_handler->query->have_posts()):
								$public_handler->query->the_post();
								
								// Пропускаем товары, которые уже показаны в блоке рекламируемых
								if (in_array(get_the_ID(), $advertised_post_ids)) {
									continue;
								}
								
								if($public_handler->args['scroll']){
									echo '<div class="listing-box">';
								}
									$listing_classes = $public_handler->get_listing_location_class();
									$listing_classes .= ($public_handler->args['scroll'] == 1)? ' listing-scroll': '';
									$listing_classes .= ($public_handler->args['2col_responsive'] && $listing_style_to_show == 'show_grid_style')? ' responsive-2col' : '';
									$listing_classes .= ($listing_style_to_show == 'show_grid_style' && !$public_handler->args['scroll'])? ' m-grid-item': '';
									$listing_classes .= ($listing_style_to_show == 'show_grid_style')? ' listing-post-style-'. $public_handler->args['listing_post_style'] : ' listing-post-style-'. $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_listview_post_style'];
									$listing_classes .= ($listing_style_to_show == 'show_grid_style')? ' listing-grid-item' : '';
									$listing_classes .= ($public_handler->listings[get_the_ID()]->package->has_featured)? ' directorypress-has_featured': '';
									$listing_classes .= ($public_handler->listings[get_the_ID()]->package->has_sticky)? ' directorypress-has_sticky': '';
										
									$listing_inline_css = ($listing_style_to_show == 'show_grid_style')? 'style="padding-left:'. $grid_padding .'px;padding-right:'. $grid_padding .'px; margin-bottom:'. $directorypress_grid_margin_bottom .'px;"' : '';
										
									echo '<article id="post-'. esc_attr(get_the_ID()) .'" class="row directorypress-listing clearfix '. esc_attr($listing_classes) .'" '. wp_kses_post($listing_inline_css) .'>';
										echo '<div class="directorypress-listing-item-holder clearfix">';
											$public_handler->listings[get_the_ID()]->display($public_handler);
										echo '</div>';
									echo '</article>';
								if($public_handler->args['scroll']){
									echo '</div>';
								}
							endwhile;
						if($listing_style_to_show == 'show_list_style'){
							echo '</div>';
						}
						if ($public_handler->args['scroll']){
							echo '</div>';
						}
					echo '</div>';
					if (!$public_handler->args['hide_paginator']){
						directorypress_pagination_display($public_handler->query, $public_handler->hash, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_more_button'], $public_handler);
					}
				}else{
					printf(esc_html__('Found %s %s', "DIRECTORYPRESS"), '<span class="badge text-bg-secondary">'. esc_attr($public_handler->query->found_posts) .'</span>', esc_attr(_n($public_handler->directorypress_get_directoytype_of_listing()->single, $public_handler->directorypress_get_directoytype_of_listing()->plural, $public_handler->query->found_posts)));
				}
			echo '</div>';
		}
	echo '</div>';
