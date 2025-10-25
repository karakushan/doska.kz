<?php

/* === backend ajax === */

// field options setting form
if( !function_exists('directorypress_fields_config_form') ){
	function directorypress_fields_config_form(){
		global $directorypress_object;             	
        $response 	= '';
		if ( ! wp_verify_nonce( $_POST['nonce'], 'directorypress-ajax-nonce' ) ) {
           die( esc_html__('No kiddies please!', 'DIRECTORYPRESS'));        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= $directorypress_object->fields_handler_property->field_settings($id, $action);
		
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_config_form', 'directorypress_fields_config_form');
    add_action('wp_ajax_nopriv_directorypress_fields_config_form', 'directorypress_fields_config_form');
}

// field options settings
if( !function_exists('directorypress_fields_options_callback') ){
	function directorypress_fields_options_callback(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_configure_fields_nonce', 'directorypress_configure_fields_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'DIRECTORYPRESS');        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = 'config';
		$directorypress_object->fields_handler_property->field_settings($id, $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_options_callback', 'directorypress_fields_options_callback');
    add_action('wp_ajax_nopriv_directorypress_fields_options_callback', 'directorypress_fields_options_callback');
}