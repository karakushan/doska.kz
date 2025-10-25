<?php 
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$public_handler->args['scroll'] = 0 ;
	$public_handler->args['scroller_nav_style'] = 2 ;
	
	// style2 
	echo '<div class="listings listing-archive listing-index archive-style-sidebar">';
			if (directorypress_has_map() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_on_index'] && (isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_map_position']) && $DIRECTORYPRESS_ADIMN_SETTINGS['archive_map_position'] == 1)){
				echo '<div class="map-listings">';
					$public_handler->map->display(false, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_radius_search_cycle'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_clusters'], true, true, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_map_height'], false, 10, directorypress_map_name_selected(), false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_draw_panel'], false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_full_screen'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_wheel_zoom'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_dragging_touchscreens'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_center_map_onclick']); 
				echo '</div>';
			}
			echo '<div class="archive-content-wrapper clearfix">';
				echo '<div class="listing-archive-sidearea clearfix">';
					if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_main_search']){
						echo '<div class="main-search-bar">';
							$public_handler->search_form->display();
						echo '</div>';
					}
					if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_categories_index']){
						directorypress_displayCategoriesTable();
					}
					//if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_locations_index']){
						directorypress_displayLocationsTable();
					//}
				echo '</div>';
				echo '<div class="listing-archive-content clearfix">';
					if (directorypress_has_map() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_on_index'] && (isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_map_position']) && $DIRECTORYPRESS_ADIMN_SETTINGS['archive_map_position'] == 2)){
						echo '<div class="map-listings">';	
							$public_handler->map->display(false, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_radius_search_cycle'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_clusters'], true, true, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_map_height'], false, 10, directorypress_map_name_selected(), false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_draw_panel'], false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_full_screen'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_wheel_zoom'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_dragging_touchscreens'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_center_map_onclick']); 
						echo '</div>';
					}
					directorypress_renderMessages();
					if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_on_index']){
						directorypress_display_template('partials/listing/wrapper.php', array('public_handler' => $public_handler));
						echo '<div class="directorypress-content-wrap" id="directorypress-handler-'.$public_handler->hash.'" data-handler-hash="'.$public_handler->hash.'"></div>';
					}
				echo '</div>';
			echo '</div>';
	echo '</div>';