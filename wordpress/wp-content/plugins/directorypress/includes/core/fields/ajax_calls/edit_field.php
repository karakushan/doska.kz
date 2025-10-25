<?php
// field edit form
if( !function_exists('directorypress_fields_edit_form') ){
	function directorypress_fields_edit_form(){
		global $directorypress_object;             	
        $response 	= '';
		if ( ! wp_verify_nonce( $_POST['nonce'], 'directorypress-ajax-nonce' ) ) {
           die( esc_html__('No kiddies please!', 'DIRECTORYPRESS'));        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= $directorypress_object->fields_handler_property->add_or_edit_fields($id, $action);
		
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_edit_form', 'directorypress_fields_edit_form');
    add_action('wp_ajax_nopriv_directorypress_fields_edit_form', 'directorypress_fields_edit_form');
}

// field edit callback
if( !function_exists('directorypress_fields_edit_callback') ){
	function directorypress_fields_edit_callback(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_fields_nonce', 'directorypress_fields_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'DIRECTORYPRESS');        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = 'submit';
		$response = $directorypress_object->fields_handler_property->add_or_edit_fields($id, $action);
		//$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_edit_callback', 'directorypress_fields_edit_callback');
    add_action('wp_ajax_nopriv_directorypress_fields_edit_callback', 'directorypress_fields_edit_callback');
}