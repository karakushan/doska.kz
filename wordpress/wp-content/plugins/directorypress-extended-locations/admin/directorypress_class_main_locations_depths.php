<?php 

class directorypress_locations_depths_manager {
	
	public function __construct() {
		add_action('admin_menu', array($this, 'menu'));
	}

	public function menu() {
			add_submenu_page('directorypress-admin-panel',
					__('Locations', 'directorypress-extended-locations'),
					__('Locations', 'directorypress-extended-locations'),
					'administrator',
					'directorypress_locations_depths',
					array($this, 'locations_page')
			);
	}
	
	public function locations_page() {
		$this->locations_levels_list();
	}
	
	public function locations_levels_list() {
		global $directorypress_object;
		$items = $directorypress_object->locations_depths;
		$items_list = $this->table($items);
		dpel_renderTemplate('partials/item_list.php', array('items_list' => $items_list));
	}
	public function locations_levels_list_ajax() {
		global $directorypress_object;
		$items = $directorypress_object->locations_depths;
		$items_list = $this->table($items);
		echo $items_list;
		die();
	}
	public function table($items) {
		global $directorypress_object;
		$items_array = array();
		$items = $directorypress_object->locations_depths;
		$output = '';
		$output .= '<div class="dp-list-section">';
			foreach ($items->location_depths_array as $id=>$item) {
				$output .= '<div class="row dp-list-row">';
					$output .= '<div class="txt-left dp-list-item clearfix">';
						$output .= '<span class="dp-list-label">'. esc_html($item->name) .'</span>';
						$output .= '<div class="directorypress-location-action-buttons">';
							$output .= '<a class="directorypress-location-action-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#directorypress-backend-offcanvas" data-id="'. esc_attr($item->id) .'" data-action="location_edit" data-title="'. esc_attr__('Edit Location Level:', 'DIRECTORYPRESS') .' '. esc_attr($item->name) .'">'. esc_html__('edit', 'directorypress-payment-manager').'</a>';
							$output .= ' | ';
							$output .= '<a class="directorypress-location-action-link delete" href="#" data-bs-toggle="offcanvas" data-bs-target="#directorypress-backend-offcanvas" data-id="'. esc_attr($item->id) .'" data-action="location_delete" data-title="'. esc_attr__('Delete Location Level:', 'DIRECTORYPRESS') .' '. esc_attr($item->name) .'">'. esc_html__('Delete', 'directorypress-payment-manager').'</a>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			}
		$output .= '</div>';
			
		return $output;
	}
	public function configure($id) {
		global $directorypress_object;
		$items = $directorypress_object->locations_depths;
		$item = $items->get_depth_level_by_id($id);
		$visibility = ($item->in_address_line)? 'yes' : 'No';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Name', 'directorypress-extended-locations').'</label><span>'. $item->name.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Included in Address?', 'directorypress-extended-locations').'</label><span>'. $visibility.'</span>';
		echo '</div>';
		
		die();
	}
	public function add_or_edit_location_item($id = null, $action = '') {
		global $directorypress_object;
	
		$items = $directorypress_object->locations_depths;
	
		if (!$item = $items->get_depth_level_by_id($id))
			$item = new directorypress_locations_depth();
	
		if ($action == 'submit') {
			$validation = new directorypress_form_validation();
			$validation->set_rules('name', __('Location Name', 'directorypress-extended-locations'), 'required');
			$validation->set_rules('in_address_line', __('show in address', 'directorypress-extended-locations'), 'is_checked');
	
			if ($validation->run()) {
				if ($item->id) {
					if ($items->save_depth_level_from_array($id, $validation->result_array())) {
						directorypress_add_notification(__('updated successfully!', 'directorypress-extended-locations'));
						$item->build_depth_level_from_array($validation->result_array());
						dpel_renderTemplate('partials/add_edit.php', array('item' => $item, 'item_id' => $id));
					}
				} else {
					if ($items->create_depth_level_from_array($validation->result_array())) {
						directorypress_add_notification(__('created successfully!', 'directorypress-extended-locations'));
					}
				}
			}else{
				$item->build_depth_level_from_array($validation->result_array());
				directorypress_add_notification($validation->error_array(), 'error');
				dpel_renderTemplate('partials/add_edit.php', array('item' => $item, 'item_id' => $id));
			}
			
		} else {
			dpel_renderTemplate('partials/add_edit.php', array('item' => $item, 'item_id' => $id));
		}
	}
	
	public function delete_location_depth_level($id, $action) {
		global $directorypress_object;
	
		$items = $directorypress_object->locations_depths;
		if ($item = $items->get_depth_level_by_id($id)) {
			if ($action == 'delete') {
				if ($items->delete_depth_level($id))
					directorypress_add_notification(__('deleted successfully!', 'directorypress-extended-locations'));
			} else{
				echo '<div class="directorypress-delete">';
					echo '<p class="alert alert-warning">'. esc_html__('Are you sure, This action can not be reversed', 'directorypress-extended-locations').'</p>';
				echo '</div>';
			}
		}
	}
}

?>