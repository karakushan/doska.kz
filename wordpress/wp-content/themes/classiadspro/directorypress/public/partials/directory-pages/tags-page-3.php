<?php 
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$public_handler->args['scroll'] = 0 ;
	$public_handler->args['scroller_nav_style'] = 2 ;
	$mapview_toggle_class = isset($_COOKIE['directorypress_mapview'])? $_COOKIE['directorypress_mapview']:'';
	echo '<div class="listings listing-archive archive-layout-style-4">';
			if(!empty($public_handler->archive_top_banner)){
				echo '<div class="archive-banner">'. $public_handler->archive_top_banner .'</div>';
			}
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_main_search']){
				echo '<div class="main-search-bar-container">';
					echo '<div class="main-search-bar">';
							  $public_handler->search_form->display();
					echo '</div>';
					if(!empty($public_handler->archive_below_search_banner)){
						echo '<div class="archive-banner">'. $public_handler->archive_below_search_banner .'</div>';
					}
				echo '</div>';
			}
			directorypress_renderMessages();
			echo '<div class="archive-listings-wrapper">';
				directorypress_display_template('partials/listing/wrapper.php', array('public_handler' => $public_handler));
				echo '<div class="directorypress-content-wrap" id="directorypress-handler-'.$public_handler->hash.'" data-handler-hash="'.$public_handler->hash.'"></div>';
			echo '</div>';
			if(!empty($public_handler->archive_below_listings_banner)){
				echo '<div class="archive-banner">'. $public_handler->archive_below_listings_banner .'</div>';
			}
	echo '</div>';
	if (directorypress_has_map() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_on_excerpt']){
		echo '<div class="map-wrapper '.$mapview_toggle_class.'">';
			echo '<a href="#" class="directorypress-map_toggle_button '.$mapview_toggle_class.'"><span class="fas fa-map-marker" aria-hidden="true"></span></a>';
			$public_handler->map->display(false, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_radius_search_cycle'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_clusters'], true, true, false, '', false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_start_map_zoom'], directorypress_map_name_selected(), false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_draw_panel'], false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_full_screen'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_wheel_zoom'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_dragging_touchscreens'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_center_map_onclick']); 	
		echo '</div>';
	}