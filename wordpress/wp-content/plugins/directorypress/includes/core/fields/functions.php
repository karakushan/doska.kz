<?php

function directorypress_is_field_label_on_grid() {
	global $directorypress_object;
	//$status = 'hide';
	global $wpdb;
	$field_ids = $wpdb->get_results('SELECT id, is_hide_name_on_grid FROM '.$wpdb->prefix.'directorypress_fields');
	foreach( $field_ids as $field_id ) {
		$singlefield_id = $field_id->id;
		if($field_id->is_hide_name_on_grid == 'show_only_label'){	
			$status = 'show_only_label';
		}elseif($field_id->is_hide_name_on_grid == 'show_icon_label'){	
			$status = 'show_icon_label';
		}elseif($field_id->is_hide_name_on_grid == 'show_only_icon'){	
			$status = 'show_only_icon';
		}else{
			$status = 'hide';
		}
												
	}
	return $status;
}

function isField_on_exerpt(){
	global $directorypress_object;
	
	foreach ($directorypress_object->fields->fields_array as $field) {
		if ($field->on_exerpt_page)
			return true;
	}
	return false;
}

function isField_on_exerpt_list(){
	global $directorypress_object;
	
	foreach ($directorypress_object->fields->fields_array as $field) {
		if ($field->on_exerpt_page_list)
			return true;
	}
	return false;
}

function isField_inLine(){
	global $directorypress_object;
	
	foreach ($directorypress_object->fields->fields_array as $field) {
		if ($field->is_field_in_line)
			return true;
	}
	return false;
}

function isField_not_empty($listing){
	global $directorypress_object, $wpdb;
	$field_ids = $wpdb->get_results('SELECT id, type, slug, group_id FROM '. $wpdb->prefix .'directorypress_fields');

	
	foreach ($field_ids as $field) {
    	if(!is_null($listing->fields[$field->id])){
    		if($listing->fields[$field->id]->is_field_not_empty($listing)){
    			return true;
    		}
    	}
	}
	return false;
}


function isField_inBlock(){
	global $directorypress_object;
	
	foreach ($directorypress_object->fields->fields_array as $field) {
		if (!$field->is_field_in_line)
			return true;
	}
	return false;
}

function directorypress_is_field_label_on_list() {
	global $directorypress_object;
	//$status = 'hide';
	global $wpdb;
	$field_ids = $wpdb->get_results('SELECT id, is_hide_name_on_list FROM '.$wpdb->prefix.'directorypress_fields');
	foreach( $field_ids as $field_id ) {
		$singlefield_id = $field_id->id;
		if($field_id->is_hide_name_on_list == 'show_only_label'){	
			$status = 'show_only_label';
		}elseif($field_id->is_hide_name_on_list == 'show_icon_label'){	
			$status = 'show_icon_label';
		}elseif($field_id->is_hide_name_on_list == 'show_only_icon'){	
			$status = 'show_only_icon';
		}else{
			$status = 'hide';
		}
												
	}
	return $status;
}