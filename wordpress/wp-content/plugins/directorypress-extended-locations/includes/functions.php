<?php
function DpEl_Templates($template) {
	$templates = array(
		$template
	);

	foreach ($templates AS $template_to_check) {
		if (is_file($template_to_check)) {
			return $template_to_check;
		} elseif (is_file(get_stylesheet_directory() . '/directorypress/public/' . $template_to_check)) { // theme or child theme templates folder
			return get_stylesheet_directory() . '/directorypress/public/' . $template_to_check;
		} elseif (is_file(DPEL_TEMPLATES_PATH . $template_to_check)) { // native plugin's templates folder
			return DPEL_TEMPLATES_PATH . $template_to_check;
		}
	}

	return false;
}

if (!function_exists('dpel_renderTemplate')) {
	function dpel_renderTemplate($template, $args = array(), $return = false) {
		global $directorypress_object;
	
		if ($args) {
			extract($args);
		}
		
		$template = apply_filters('dpel_render_template', $template, $args);
		
		if (is_array($template)) {
			$template_path = $template[0];
			$template_file = $template[1];
			$template = $template_path . $template_file;
		}
		
		$template = DpEl_Templates($template);

		if ($template) {
			if ($return) {
				ob_start();
			}
		
			include($template);
			
			if ($return) {
				$output = ob_get_contents();
				ob_end_clean();
				return $output;
			}
		}
	}
}

remove_filter('setlocation', 'setLocationDefault', 10);
add_filter('setlocation', 'setLocationExtended', 11, 2);
function setLocationExtended($listing) {
		global $wpdb, $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->directorypress_locations_relation} WHERE post_id=".$listing->post->ID, ARRAY_A);
		
		foreach ($results AS $row) {
			if ($row['location_id'] || $row['map_coords_1'] != '0.000000' || $row['map_coords_2'] != '0.000000' || $row['address_line_1'] || $row['zip_or_postal_index']) {
				$location = new directorypress_location($listing->post->ID);
				$location_settings = array(
						'id' => $row['id'],
						'selected_location' => $row['location_id'],
						'address_line_1' => $row['address_line_1'],
						'address_line_2' => $row['address_line_2'],
						'zip_or_postal_index' => $row['zip_or_postal_index'],
						'additional_info' => $row['additional_info'],
				);
				
					$location_settings['manual_coords'] = directorypress_get_input_value($row, 'manual_coords');
					$location_settings['map_coords_1'] = directorypress_get_input_value($row, 'map_coords_1');
					$location_settings['map_coords_2'] = directorypress_get_input_value($row, 'map_coords_2');
					if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_markers_type'] == 'images') {
						
							$location_settings['map_icon_manually_selected'] = false;
							if ($categories = wp_get_object_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('orderby' => 'name'))) {
								$images = get_option('directorypress_categories_marker_images');
								$image_found = false;
								foreach ($categories AS $category_obj) {
									if (!$image_found && isset($images[$category_obj->term_id])) {
										$location_settings['map_icon_file'] = $images[$category_obj->term_id];
										$image_found = true;
									}
									if ($image_found)
										break;
									if ($parent_categories = directorypress_get_term_parents_ids($category_obj->term_id, DIRECTORYPRESS_CATEGORIES_TAX)) {
										foreach ($parent_categories AS $parent_category_id) {
											if (!$image_found && isset($images[$parent_category_id])) {
												$location_settings['map_icon_file'] = $images[$parent_category_id];
												$image_found = true;
											}
											if ($image_found) {
												break;
												break;
											}
										}
									}
								}
							}
						
					} else {
							$location_settings['map_icon_manually_selected'] = false;
							if ($categories = wp_get_object_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('orderby' => 'name'))) {
								$icons = get_option('directorypress_categories_marker_icons');
								$colors = get_option('directorypress_categories_marker_colors');
								$icon_found = false;
								$color_found = false;
								foreach ($categories AS $category_obj) {
									//if (!$icon_found && isset($icons[$category_obj->term_id])) {
										$location_settings['map_icon_file'] = get_listing_category_font_icon($category_obj->term_id);
										$icon_found = true;
									//}
									if (!$color_found && !empty(get_listing_category_color($category_obj->term_id))) {
										$location_settings['map_icon_color'] = get_listing_category_color($category_obj->term_id);
										$color_found = true;
									}
									if ($icon_found && $color_found)
										break;
									if ($parent_categories = directorypress_get_term_parents_ids($category_obj->term_id, DIRECTORYPRESS_CATEGORIES_TAX)) {
										foreach ($parent_categories AS $parent_category_id) {
											//if (!$icon_found && isset($icons[$parent_category_id])) {
												//$location_settings['map_icon_file'] = $icons[$parent_category_id];
												$location_settings['map_icon_file'] = get_listing_category_font_icon($parent_category_id);
												$icon_found = true;
											//}
											//if (!$color_found && !empty(get_listing_category_color($parent_category_id))) {
												$location_settings['map_icon_color'] = get_listing_category_color($parent_category_id);
												$color_found = true;
											//}
											if ($icon_found && $color_found) {
												break;
												break;
											}
										}
									}
									if ($icon_found || $color_found)
										break;
								}
							}
						
					}
				
				
				$location_settings = apply_filters('directorypress_listing_locations', $location_settings, $listing);
				
				$location->create_location_from_array($location_settings);
				
				 $listing->locations[] = $location;
			}
		}
	}

// location list
if( !function_exists('dpel_location_list') ){
	function dpel_location_list(){
		global $directorypress_object;             	
        $response 	= ''; 
		$response .= $directorypress_object->locations_depths_manager->locations_levels_list_ajax();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpel_location_list', 'dpel_location_list');
    add_action('wp_ajax_nopriv_dpel_location_list', 'dpel_location_list');
}
// create new location action
if( !function_exists('dpel_create_new_form') ){
	function dpel_create_new_form(){
		global $directorypress_object;             	
        $response 	= ''; 
		$response .= $directorypress_object->locations_depths_manager->add_or_edit_location_item();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpel_create_new_form', 'dpel_create_new_form');
    add_action('wp_ajax_nopriv_dpel_create_new_form', 'dpel_create_new_form');
}
// create new location
if( !function_exists('dpel_create_new_callback') ){
	function dpel_create_new_callback(){
		global $directorypress_object;              	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_locations_depths_nonce', 'directorypress_locations_depths_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'directorypress-extended-locations');        
        }
		$id = '';
		$action = 'submit';
		$directorypress_object->locations_depths_manager->add_or_edit_location_item($id, $action);
		$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpel_create_new_callback', 'dpel_create_new_callback');
    add_action('wp_ajax_nopriv_dpel_create_new_callback', 'dpel_create_new_callback');
}

// location delete action
if( !function_exists('dpel_delete_form') ){
	function dpel_delete_form(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = $_POST['id'];
		$action = '';
		$response .= '<input type="hidden" name="id" value="'.$id.'" />';
		$response .= $directorypress_object->locations_depths_manager->delete_location_depth_level($id, $action);
		
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpel_delete_form', 'dpel_delete_form');
    add_action('wp_ajax_nopriv_dpel_delete_form', 'dpel_delete_form');
}

// location delete
if( !function_exists('dpel_delete_callback') ){
	function dpel_delete_callback(){
		global $directorypress_object;            	
       // $response 	= array(); 
		$id = $_POST['id'];
		$action = 'delete';
		$directorypress_object->locations_depths_manager->delete_location_depth_level($id, $action);
		$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpel_delete_callback', 'dpel_delete_callback');
    add_action('wp_ajax_nopriv_dpel_delete_callback', 'dpel_delete_callback');
}

// location edit action
if( !function_exists('dpel_edit_form') ){
	function dpel_edit_form(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = $_POST['id'];
		$action = '';
		$response .= $directorypress_object->locations_depths_manager->add_or_edit_location_item($id, $action);
		
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpel_edit_form', 'dpel_edit_form');
    add_action('wp_ajax_nopriv_dpel_edit_form', 'dpel_edit_form');
}

// location edit
if( !function_exists('dpel_edit_callback') ){
	function dpel_edit_callback(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_locations_depths_nonce', 'directorypress_locations_depths_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'directorypress-extended-locations');        
        }
		$id = $_POST['id'];
		$action = 'submit';
		$directorypress_object->locations_depths_manager->add_or_edit_location_item($id, $action);
		$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpel_edit_callback', 'dpel_edit_callback');
    add_action('wp_ajax_nopriv_dpel_edit_callback', 'dpel_edit_callback');
}

// location configuration
if( !function_exists('dpel_configure') ){
	function dpel_configure(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = $_POST['id'];		
		$response .= $directorypress_object->locations_depths_manager->configure($id);
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpel_configure', 'dpel_configure');
    add_action('wp_ajax_nopriv_dpel_configure', 'dpel_configure');
}