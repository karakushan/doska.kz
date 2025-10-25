<?php

function DpPm_Templates($template) {
	$custom_template = str_replace('.tpl.php', '', $template) . '-custom.tpl.php';
	$templates = array(
			$custom_template,
			$template
	);

	foreach ($templates AS $template_to_check) {
		if (is_file($template_to_check)) {
			return $template_to_check;
		} elseif (is_file(get_stylesheet_directory() . '/directorypress/public/' . $template_to_check)) { // theme or child theme templates folder
			return get_stylesheet_directory() . '/directorypress/public/' . $template_to_check;
		} elseif (is_file(DPPM_TEMPLATES_PATH . $template_to_check)) { // native plugin's templates folder
			return DPPM_TEMPLATES_PATH . $template_to_check;
		}
	}

	return false;
}

if (!function_exists('dppm_renderTemplate')) {
	function dppm_renderTemplate($template, $args = array(), $return = false) {
		global $directorypress_object;
	
		if ($args) {
			extract($args);
		}
		
		$template = apply_filters('dppm_render_template', $template, $args);
		
		if (is_array($template)) {
			$template_path = $template[0];
			$template_file = $template[1];
			$template = $template_path . $template_file;
		}
		
		$template = DpPm_Templates($template);

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

// package list
if( !function_exists('dppm_package_list') ){
	function dppm_package_list(){
		global $directorypress_object;             	
        $response 	= ''; 
		$response .= $directorypress_object->packages_manager->packages_list_ajax();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_package_list', 'dppm_package_list');
    add_action('wp_ajax_nopriv_dppm_package_list', 'dppm_package_list');
}

// package order
if( !function_exists('dppm_reorder') ){
	function dppm_reorder(){
		global $directorypress_object;            	
       // $response 	= array(); 
		$new_order = $_POST['new_order'];
		$action = 'reorder';
		$directorypress_object->packages_manager->packages_list_order($new_order, $action);
		$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_reorder', 'dppm_reorder');
    add_action('wp_ajax_nopriv_dppm_reorder', 'dppm_reorder');
}
// create new package action
if( !function_exists('dppm_create_new_form') ){
	function dppm_create_new_form(){
		global $directorypress_object;             	
        $response 	= ''; 
		$response .= $directorypress_object->packages_manager->add_or_edit_package();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_create_new_form', 'dppm_create_new_form');
    add_action('wp_ajax_nopriv_dppm_create_new_form', 'dppm_create_new_form');
}
// create new package
if( !function_exists('dppm_create_new_callback') ){
	function dppm_create_new_callback(){
		global $directorypress_object;              	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_locations_depths_nonce', 'directorypress_locations_depths_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'directorypress-extended-locations');        
        }
		$id = '';
		$action = 'submit';
		$directorypress_object->packages_manager->add_or_edit_package($id, $action);
		$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_create_new_callback', 'dppm_create_new_callback');
    add_action('wp_ajax_nopriv_dppm_create_new_callback', 'dppm_create_new_callback');
}

// package delete action
if( !function_exists('dppm_delete_form') ){
	function dppm_delete_form(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = $_POST['id'];
		$action = '';
		$response .= '<input type="hidden" name="id" value="'.$id.'" />';
		$response .= $directorypress_object->packages_manager->delete_package($id, $action);
		
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_delete_form', 'dppm_delete_form');
    add_action('wp_ajax_nopriv_dppm_delete_form', 'dppm_delete_form');
}

// package delete
if( !function_exists('dppm_delete_callback') ){
	function dppm_delete_callback(){
		global $directorypress_object;            	
       // $response 	= array(); 
		$id = $_POST['id'];
		$action = 'delete';
		$directorypress_object->packages_manager->delete_package($id, $action);
		$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_delete_callback', 'dppm_delete_callback');
    add_action('wp_ajax_nopriv_dppm_delete_callback', 'dppm_delete_callback');
}

// package edit action
if( !function_exists('dppm_edit_form') ){
	function dppm_edit_form(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = $_POST['id'];
		$action = '';
		$response .= $directorypress_object->packages_manager->add_or_edit_package($id, $action);
		
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_edit_form', 'dppm_edit_form');
    add_action('wp_ajax_nopriv_dppm_edit_form', 'dppm_edit_form');
}

// package edit
if( !function_exists('dppm_edit_callback') ){
	function dppm_edit_callback(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_locations_depths_nonce', 'directorypress_locations_depths_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'directorypress-extended-locations');        
        }
		$id = $_POST['id'];
		$action = 'submit';
		$response = $directorypress_object->packages_manager->add_or_edit_package($id, $action);
		//$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_edit_callback', 'dppm_edit_callback');
    add_action('wp_ajax_nopriv_dppm_edit_callback', 'dppm_edit_callback');
}

// package upgrade/downgrade action
if( !function_exists('dppm_upgrade_downgrade_form') ){
	function dppm_upgrade_downgrade_form(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = $_POST['id'];
		$action = '';
		$response .= $directorypress_object->packages_manager->package_upgrade_downgrade($id, $action);
		
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_upgrade_downgrade_form', 'dppm_upgrade_downgrade_form');
    add_action('wp_ajax_nopriv_dppm_upgrade_downgrade_form', 'dppm_upgrade_downgrade_form');
}

// package upgrade/downgrade
if( !function_exists('dppm_upgrade_downgrade_callback') ){
	function dppm_upgrade_downgrade_callback(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_packages_nonce', 'directorypress_packages_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'directorypress-extended-locations');        
        }
		$id = $_POST['id'];
		$action = 'upgrade_downgrade';
		$directorypress_object->packages_manager->package_upgrade_downgrade($id, $action);
		//$directorypress_object->listing_single_product->packages_upgrade_meta($action);
		$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_upgrade_downgrade_callback', 'dppm_upgrade_downgrade_callback');
    add_action('wp_ajax_nopriv_dppm_upgrade_downgrade_callback', 'dppm_upgrade_downgrade_callback');
}

// package configuration
if( !function_exists('dppm_package_info') ){
	function dppm_package_info(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = $_POST['id'];		
		$response .= $directorypress_object->packages_manager->configure($id);
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dppm_package_info', 'dppm_package_info');
    add_action('wp_ajax_nopriv_dppm_package_info', 'dppm_package_info');
}

function directorypress_create_woo_package_product($id = null) {
	if (directorypress_has_wc()) {
	
	//add_action('vp_directorypress_option_after_ajax_save', 'woo_save_option', 11, 3);
		/*$response = array('type' => 'error', 'message' => 'failed');
		global $directorypress_object;
		$packages = $directorypress_object->packages;
		$id = $_POST['id'];
		if (!$package = $packages->get_package_by_id($id)){
			$package = new directorypress_package();
		}
		
		if ($id) {
			//if (get_option('directorypress_woocommerce_functionality') && !get_option('directorypress_woocommerce_produts_created')) {
				//foreach ($directorypress_object->packages->packages_array as $package) {
					/*$post_id = wp_insert_post( array(
					    'post_title' => $package->name,
					    'post_status' => 'publish',
					    'post_type' => "product",
					), true);
					if (!is_wp_error($post_id)) {
						wp_set_object_terms($post_id, 'listing_single', 'product_type');
						update_post_meta($post_id, '_visibility', 'visible');
						update_post_meta($post_id, '_stock_status', 'instock');
						update_post_meta($post_id, 'total_sales', '0');
						update_post_meta($post_id, '_downloadable', 'no');
						update_post_meta($post_id, '_virtual', 'yes');
						update_post_meta($post_id, '_regular_price', '');
						update_post_meta($post_id, '_sale_price', '');
						update_post_meta($post_id, '_purchase_note', '');
						update_post_meta($post_id, '_has_featured', 'no');
						update_post_meta($post_id, '_weight', '');
						update_post_meta($post_id, '_length', '');
						update_post_meta($post_id, '_width', '');
						update_post_meta($post_id, '_height', '');
						update_post_meta($post_id, '_sku', '');
						update_post_meta($post_id, '_product_attributes', array());
						update_post_meta($post_id, '_sale_price_dates_from', '');
						update_post_meta($post_id, '_sale_price_dates_to', '');
						update_post_meta($post_id, '_price', '');
						update_post_meta($post_id, '_sold_individually', '');
						update_post_meta($post_id, '_manage_stock', 'no');
						update_post_meta($post_id, '_backorders', 'no');
						update_post_meta($post_id, '_stock', '');
	
						update_post_meta($post_id, '_listings_package', $package->id);
					}
				//}
				//add_option('directorypress_woocommerce_produts_created', true);
			//}
			$response['type'] = 'success';
			$response['message'] = admin_url('/post.php?post='.$post_id.'&action=edit');*/
		//}
		//wp_send_json($response); 
		
		global $directorypress_object;              	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_packages_nonce', 'directorypress_packages_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'directorypress-extended-locations');        
        }
		$id = $_POST['id'];
		$action = '';
		$directorypress_object->packages_manager->package_pricing($id, $action);
		$response = directorypress_renderMessages();
		echo $response; 
		die();
	}
}
add_action('wp_ajax_directorypress_create_woo_package_product', 'directorypress_create_woo_package_product');
add_action('wp_ajax_nopriv_directorypress_create_woo_package_product', 'directorypress_create_woo_package_product');

function dppm_package_price_callback($id = null) {
	

		
		global $directorypress_object;              	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_packages_nonce', 'directorypress_packages_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'directorypress-extended-locations');        
        }
		$id = $_POST['id'];
		$action = 'submit';
		$data['regular_price'] = $_POST['_regular_price'];
		$data['sale_price'] = $_POST['_sale_price'];
		$data['bumpup_price'] = $_POST['_bumpup_price'];
		//$directorypress_object->packages_manager->package_pricing($id, $action);
		$response = $directorypress_object->packages_manager->package_pricing($id, $action, $data);
		//$response = directorypress_renderMessages();
		echo $response; 
		die();
	
}
add_action('wp_ajax_dppm_package_price_callback', 'dppm_package_price_callback');
add_action('wp_ajax_nopriv_dppm_package_price_callback', 'dppm_package_price_callback');