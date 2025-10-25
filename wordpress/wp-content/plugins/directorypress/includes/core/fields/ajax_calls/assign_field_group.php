<?php
// Assign field group
if( !function_exists('directorypress_fields_assign_group') ){
	function directorypress_fields_assign_group(){
		global $directorypress_object;            	
        $result = array();
		$do_check = check_ajax_referer('directorypress_fields_nonce', 'directorypress_fields_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'DIRECTORYPRESS');        
        }
		$id = sanitize_text_field($_POST['id']);
		$group_id = sanitize_text_field($_POST['group_id']);
		$action = 'submit';
		if($response = $directorypress_object->fields_handler_property->assign_field_group($id, esc_attr($group_id))){
			$result['type'] = $response[1];
			$result['message'] = $response[0];
		}
		wp_send_json($result);
	}
	add_action('wp_ajax_directorypress_fields_assign_group', 'directorypress_fields_assign_group');
    add_action('wp_ajax_nopriv_directorypress_fields_assign_group', 'directorypress_fields_assign_group');
}