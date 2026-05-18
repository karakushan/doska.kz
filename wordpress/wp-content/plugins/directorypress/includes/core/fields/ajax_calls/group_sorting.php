<?php
// groups order
if( !function_exists('directorypress_fields_group_reorder') ){
	function directorypress_fields_group_reorder(){
		global $directorypress_object;            	
       // $response 	= array(); 
		$new_order = sanitize_text_field($_POST['new_order']);
		$action = 'reorder';
		$directorypress_object->fields_handler_property->fields_list_order(esc_attr($new_order), $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_reorder', 'directorypress_fields_group_reorder');
    add_action('wp_ajax_nopriv_directorypress_fields_group_reorder', 'directorypress_fields_group_reorder');
}