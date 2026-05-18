<?php
// field search settings form
if( !function_exists('directorypress_fields_search_settings_form') ){
	function directorypress_fields_search_settings_form(){
		global $directorypress_object;             	
        $response 	= '';
		if ( ! wp_verify_nonce( $_POST['nonce'], 'directorypress-ajax-nonce' ) ) {
           die( esc_html__('No kiddies please!', 'DIRECTORYPRESS'));        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= $directorypress_object->search_fields->search_field_settings($id, $action);
		
		echo wp_kses_post($response);
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_search_settings_form', 'directorypress_fields_search_settings_form');
    add_action('wp_ajax_nopriv_directorypress_fields_search_settings_form', 'directorypress_fields_search_settings_form');
}

// field search settings
if( !function_exists('directorypress_fields_search_settings_callback') ){
	function directorypress_fields_search_settings_callback(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_configure_fields_nonce', 'directorypress_configure_fields_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'DIRECTORYPRESS');        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = 'search_config';
		$directorypress_object->search_fields->search_field_settings($id, $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_search_settings_callback', 'directorypress_fields_search_settings_callback');
    add_action('wp_ajax_nopriv_directorypress_fields_search_settings_callback', 'directorypress_fields_search_settings_callback');
}