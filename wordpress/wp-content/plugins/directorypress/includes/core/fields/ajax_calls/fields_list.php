<?php
// fields list
if( !function_exists('directorypress_fields_list') ){
	function directorypress_fields_list(){
		if (is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			global $directorypress_object;             	
			$response 	= ''; 
			$response .= $directorypress_object->fields_handler_property->fields_list_ajax();
			echo wp_kses_post($response); 
		}else{
			echo esc_html__('No Kiddies', 'DIRECTORYPRESS');
		}
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_list', 'directorypress_fields_list');
    add_action('wp_ajax_nopriv_directorypress_fields_list', 'directorypress_fields_list');
}