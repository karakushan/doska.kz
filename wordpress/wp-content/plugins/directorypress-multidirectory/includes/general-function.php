<?php

function DpMd_Templates($template) {
	$custom_template = str_replace('.php', '', $template) . '-custom.php';
	$templates = array(
			$custom_template,
			$template
	);

	foreach ($templates AS $template_to_check) {
		if (is_file($template_to_check)) {
			return $template_to_check;
		} elseif (is_file(get_stylesheet_directory() . '/directorypress/public/' . $template_to_check)) { // theme or child theme templates folder
			return get_stylesheet_directory() . '/directorypress/public/' . $template_to_check;
		} elseif (is_file(DPMD_TEMPLATES_PATH . $template_to_check)) { // native plugin's templates folder
			return DPMD_TEMPLATES_PATH . $template_to_check;
		}
	}

	return false;
}

if (!function_exists('dpmd_renderTemplate')) {
	/**
	 * @param string|array $template
	 * @param array $args
	 * @param bool $return
	 * @return string
	 */
	function dpmd_renderTemplate($template, $args = array(), $return = false) {
		global $directorypress_object;
	
		if ($args) {
			extract($args);
		}
		
		$template = apply_filters('dpmd_render_template', $template, $args);
		
		if (is_array($template)) {
			$template_path = $template[0];
			$template_file = $template[1];
			$template = $template_path . $template_file;
		}
		
		$template = DpMd_Templates($template);

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

// create new directory action
if( !function_exists('dpmd_directory_list') ){
	function dpmd_directory_list(){
		global $directorypress_object;             	
        $response 	= ''; 
		$response .= $directorypress_object->directorytypes_manager->showUpdatedDirectorytypesTable();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpmd_directory_list', 'dpmd_directory_list');
    add_action('wp_ajax_nopriv_dpmd_directory_list', 'dpmd_directory_list');
}
// create new directory action
if( !function_exists('dpmd_create_new_form') ){
	function dpmd_create_new_form(){
		global $directorypress_object;             	
        $response 	= ''; 
		$response .= $directorypress_object->directorytypes_manager->addOrEditDirectory();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpmd_create_new_form', 'dpmd_create_new_form');
    add_action('wp_ajax_nopriv_dpmd_create_new_form', 'dpmd_create_new_form');
}
// create new directory
if( !function_exists('dpmd_create_new_callback') ){
	function dpmd_create_new_callback(){
		global $directorypress_object;              	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_directorytypes_nonce', 'directorypress_directorytypes_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'directorypress-frontend');        
        }
		$id = '';
		$action = 'submit';
		$directorypress_object->directorytypes_manager->addOrEditDirectory($id, $action);
		$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpmd_create_new_callback', 'dpmd_create_new_callback');
    add_action('wp_ajax_nopriv_dpmd_create_new_callback', 'dpmd_create_new_callback');
}

// directory delete action
if( !function_exists('dpmd_delete_form') ){
	function dpmd_delete_form(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = $_POST['id'];
		$action = '';
		$new_id = '';
		$response .= '<input type="hidden" name="id" value="'.$id.'" />';
		$response .= $directorypress_object->directorytypes_manager->deleteDirectory($id, $new_id, $action);
		
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpmd_delete_form', 'dpmd_delete_form');
    add_action('wp_ajax_nopriv_dpmd_delete_form', 'dpmd_delete_form');
}

// directory delete
if( !function_exists('dpmd_delete_callback') ){
	function dpmd_delete_callback(){
		global $directorypress_object;            	
       // $response 	= array(); 
		$id = $_POST['id'];
		$new_id = $_POST['new_directory_id'];
		$action = 'delete';
		$directorypress_object->directorytypes_manager->deleteDirectory($id, $new_id, $action);
		$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpmd_delete_callback', 'dpmd_delete_callback');
    add_action('wp_ajax_nopriv_dpmd_delete_callback', 'dpmd_delete_callback');
}

// directory edit action
if( !function_exists('dpmd_edit_form') ){
	function dpmd_edit_form(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = $_POST['id'];
		$action = '';
		$response .= $directorypress_object->directorytypes_manager->addOrEditDirectory($id, $action);
		
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpmd_edit_form', 'dpmd_edit_form');
    add_action('wp_ajax_nopriv_dpmd_edit_form', 'dpmd_edit_form');
}

// directory edit
if( !function_exists('dpmd_edit_callback') ){
	function dpmd_edit_callback(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_directorytypes_nonce', 'directorypress_directorytypes_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'directorypress-frontend');        
        }
		$id = $_POST['id'];
		$action = 'submit';
		$response = $directorypress_object->directorytypes_manager->addOrEditDirectory($id, $action);
		//$response = directorypress_renderMessages();
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpmd_edit_callback', 'dpmd_edit_callback');
    add_action('wp_ajax_nopriv_dpmd_edit_callback', 'dpmd_edit_callback');
}

// directory configuration
if( !function_exists('dpmd_directory_info') ){
	function dpmd_directory_info(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = $_POST['id'];			
		$response .= $directorypress_object->directorytypes_manager->configure($id);
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_dpmd_directory_info', 'dpmd_directory_info');
    add_action('wp_ajax_nopriv_dpmd_directory_info', 'dpmd_directory_info');
}