<?php

// field delete form
if( !function_exists('directorypress_fields_delete_form') ){
	function directorypress_fields_delete_form(){
		global $directorypress_object;             	
        // Check for nonce security      
		if ( ! wp_verify_nonce( $_POST['nonce'], 'directorypress-ajax-nonce' ) ) {
			 die ( 'No Kiddies!');
		} 
		$response 	= ''; 
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= '<input type="hidden" name="id" value="'.$id.'" />';
		$response .= $directorypress_object->fields_handler_property->delete_field($id, $action);
		
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_delete_form', 'directorypress_fields_delete_form');
    add_action('wp_ajax_nopriv_directorypress_fields_delete_form', 'directorypress_fields_delete_form');
}

// field delete
if( !function_exists('directorypress_fields_delete_callback') ){
	function directorypress_fields_delete_callback(){
		global $directorypress_object;            	
       // Check for nonce security      
		if ( ! wp_verify_nonce( $_POST['nonce'], 'directorypress-ajax-nonce' ) ) {
			 die ( 'No Kiddies!');
		}
		$id = sanitize_text_field($_POST['id']);
		$action = 'delete';
		$directorypress_object->fields_handler_property->delete_field($id, $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_delete_callback', 'directorypress_fields_delete_callback');
    add_action('wp_ajax_nopriv_directorypress_fields_delete_callback', 'directorypress_fields_delete_callback');
}