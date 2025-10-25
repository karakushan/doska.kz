<?php
// fields order
if( !function_exists('directorypress_fields_reorder') ){
	function directorypress_fields_reorder(){
		global $directorypress_object;            	
       $response = array(); 
		if ( !current_user_can( 'manage_options' ) ) {
			$response['type'] = 'error';
			$response['message'] = esc_html__('No Permission!', 'DIRECTORYPRESS'); 
		}
		$new_order = sanitize_text_field($_POST['new_order']);
		$action = 'reorder';
		if($result = $directorypress_object->fields_handler_property->fields_list_order($new_order, $action)){
			$response['type'] = $result[1];
			$response['message'] = $result[0];
		}
		wp_send_json($response);
		
	}
	add_action('wp_ajax_directorypress_fields_reorder', 'directorypress_fields_reorder');
    add_action('wp_ajax_nopriv_directorypress_fields_reorder', 'directorypress_fields_reorder');
}