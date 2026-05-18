<?php
// create new field form
if( !function_exists('directorypress_fields_create_new_form') ){
	function directorypress_fields_create_new_form(){
		global $directorypress_object;             	
        if ( ! wp_verify_nonce( $_POST['nonce'], 'directorypress-ajax-nonce' ) ) {
           die( esc_html__('No kiddies please!', 'DIRECTORYPRESS'));        
        }
		
		$response 	= ''; 
		$response .= $directorypress_object->fields_handler_property->add_or_edit_fields();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_create_new_form', 'directorypress_fields_create_new_form');
    add_action('wp_ajax_nopriv_directorypress_fields_create_new_form', 'directorypress_fields_create_new_form');
}
// create new field callback
if( !function_exists('directorypress_fields_create_new_callback') ){
	function directorypress_fields_create_new_callback(){
		global $directorypress_object;              	
        if ( ! wp_verify_nonce( $_POST['nonce'], 'directorypress-ajax-nonce' ) ) {
           die( esc_html__('No kiddies please!', 'DIRECTORYPRESS'));        
        }
		
		$response 	= array(); 
		
		$id = '';
		$action = 'submit';
		$directorypress_object->fields_handler_property->add_or_edit_fields($id, $action);

		$html = directorypress_renderMessages();
		echo wp_kses_post($html); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_create_new_callback', 'directorypress_fields_create_new_callback');
    add_action('wp_ajax_nopriv_directorypress_fields_create_new_callback', 'directorypress_fields_create_new_callback');
}