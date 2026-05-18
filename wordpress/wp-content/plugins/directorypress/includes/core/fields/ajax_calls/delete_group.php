<?php
// group delete form
if( !function_exists('directorypress_fields_group_delete_form') ){
	function directorypress_fields_group_delete_form(){
		global $directorypress_object;             	
        $response 	= '';
		if ( ! wp_verify_nonce( $_POST['nonce'], 'directorypress-ajax-nonce' ) ) {
           die( esc_html__('No kiddies please!', 'DIRECTORYPRESS'));        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= '<input type="hidden" name="id" value="'. esc_attr($id) .'" />';
		$response .= $directorypress_object->fields_handler_property->delete_field_group(esc_attr($id), $action);
		
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_delete_form', 'directorypress_fields_group_delete_form');
    add_action('wp_ajax_nopriv_directorypress_fields_group_delete_form', 'directorypress_fields_group_delete_form');
}

// group delete
if( !function_exists('directorypress_fields_group_delete_callback') ){
	function directorypress_fields_group_delete_callback(){
		global $directorypress_object;            	
       // $response 	= array(); 
		if ( ! wp_verify_nonce( $_POST['nonce'], 'directorypress-ajax-nonce' ) ) {
           die( esc_html__('No kiddies please!', 'DIRECTORYPRESS'));        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = 'delete';
		$directorypress_object->fields_handler_property->delete_field_group(esc_attr($id), $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response);
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_delete_callback', 'directorypress_fields_group_delete_callback');
    add_action('wp_ajax_nopriv_directorypress_fields_group_delete_callback', 'directorypress_fields_group_delete_callback');
}