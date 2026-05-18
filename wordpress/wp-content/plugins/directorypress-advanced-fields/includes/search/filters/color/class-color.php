<?php 

class directorypress_field_color_search extends directorypress_field_select_search {
	
	public function display_search($search_form, $defaults = array()) {
		if ($this->search_input_mode =='radiobutton' && count($this->field->selection_items)) {
			$this->field->selection_items = array('' => __('All', 'DIRECTORYPRESS')) + $this->field->selection_items;
		}

		if (is_null($this->value)) {
			if (isset($defaults['field_' . $this->field->slug])) {
				$this->value = $defaults['field_' . $this->field->slug];
				if (!is_array($this->value)) {
					$this->value = array_filter(explode(',', $this->value), 'strlen');
				}
			}
		}
		
		if (!$this->value) {
			$this->value = array('');
		}
		
		$items_count_array = array();
		if ($this->items_count) {
			global $wpdb, $directorypress_object, $sitepress;
			
			$sql = "
					SELECT COUNT(DISTINCT(pm.post_id)) AS count, pm.meta_value FROM {$wpdb->posts} AS po
					LEFT JOIN {$wpdb->postmeta} AS pm ON po.ID = pm.post_id"

					. ($directorypress_object->directorytypes->isMultiDirectory() ? " LEFT JOIN {$wpdb->postmeta} AS pm1 ON po.ID = pm1.post_id " : " ")
					
					. ((function_exists('wpml_object_id_filter') && $sitepress) ? " LEFT JOIN {$wpdb->prefix}icl_translations ON po.ID = {$wpdb->prefix}icl_translations.element_id " : " ")
					
					. "WHERE 
						pm.meta_key = '_field_" . $this->field->id . "'
					AND
						po.post_status = 'publish'"
								
					. ($directorypress_object->directorytypes->isMultiDirectory() ? " AND
						pm1.meta_key = '_directory_id'
					AND
						pm1.meta_value = " . $directorypress_object->current_directorytype->id . " " : " ")
						
					. ((function_exists('wpml_object_id_filter') && $sitepress) ? " AND
						{$wpdb->prefix}icl_translations.language_code = '".ICL_LANGUAGE_CODE."' " : " ")
						
					. "GROUP BY pm.meta_value
			";
			
			$items_count_results = $wpdb->get_results($sql, ARRAY_A);
			
			foreach ($items_count_results AS $items_count) {
				$items_count_array[$items_count['meta_value']] = $items_count['count'];
			}
		}

		$search_field = $this;
		if ($this->search_input_mode == 'checkboxes'){
			include('_html/checkbox.php');
		}elseif($this->search_input_mode =='radiobutton'){
			include('_html/radio.php');
		}elseif($this->search_input_mode =='selectbox'){
			include('_html/selectbox.php');
		}
	}
}
?>