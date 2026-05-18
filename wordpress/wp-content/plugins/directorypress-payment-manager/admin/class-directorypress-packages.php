<?php 

class directorypress_packages_manager {
	public function __construct() {
		add_action('admin_menu', array($this, 'menu'));
	}

	public function menu() {
			add_submenu_page('directorypress-admin-panel',
				__('Packages', 'directorypress-payment-manager'),
				__('Packages', 'directorypress-payment-manager'),
				'administrator',
				'directorypress_packages',
				array($this, 'packages_page')
			);
	}
	public function packages_page() {
		$this->packages_list();	
	}
	
	public function packages_list() {
		global $directorypress_object;
		$items = $directorypress_object->packages;
		wp_enqueue_script('jquery-ui-sortable');
		
		$items_list = $this->table($items);
		
		dppm_renderTemplate('partials/packages-list.php', array('items_list' => $items_list));
	}
	public function packages_list_ajax() {
		global $directorypress_object;
		$items = $directorypress_object->packages;
		wp_enqueue_script('jquery-ui-sortable');
		
		$items_list = $this->table($items);
		
		echo $items_list;
		die();
	}
	public function packages_list_order($new_order, $action = '') {
		global $directorypress_object;
		$items = $directorypress_object->packages;

		if ($action == 'reorder') {
			if ($items->saveOrder($new_order)){
				directorypress_add_notification(__('packages order updated!', 'directorypress-payment-manager'), 'updated');
			}
		}
	}
	public function table($items) {
		global $directorypress_object;
		$items_array = array();
		$items = $directorypress_object->packages;
		$output = '';
		
		$output .= '<div class="dp-list-section">';
			foreach ($items->packages_array as $id=>$item) {
				$output .= '<div class="row dp-list-row">';
					$output .= '<input type="hidden" class="package_weight_id" value="'.$item->id.'" />';
					$output .= '<div class="txt-left dp-list-item clearfix">';
						$output .= '<div class="directorypress-fields-order-button">';
							$output .= '<i class="fas fa-arrows-alt"></i>';
						$output .= '</div>';
						$output .= '<div class="directorypress-fields-content">';
							$output .= '<span class="dp-list-label">'. esc_html($item->name) .'</span>';
							$output .= '<div class="directorypress-fields-action-buttons">';
								$output .= '<a class="directorypress-package-action-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#directorypress-backend-offcanvas" data-id="'. esc_attr($item->id) .'" data-action="package_edit" data-title="'. esc_attr__('Edit Package:', 'DIRECTORYPRESS') .' '. esc_attr($item->name) .'">'. esc_html__('edit', 'directorypress-payment-manager').'</a>';
								$output .= ' | ';
								$output .= '<a class="directorypress-package-action-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#directorypress-backend-offcanvas" data-id="'. esc_attr($item->id) .'" data-action="package_upgrade" data-title="'. esc_attr__('Upgrade Package:', 'DIRECTORYPRESS') .' '. esc_attr($item->name) .'">'. esc_html__('Upgrade', 'directorypress-payment-manager').'</a>';
								$output .= ' | ';
								$output .= '<a class="directorypress-package-action-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#directorypress-backend-offcanvas" data-id="'. esc_attr($item->id) .'" data-action="package_info" data-title="'. esc_attr__('Package Info:', 'DIRECTORYPRESS') .' '. esc_attr($item->name) .'">'. esc_html__('Info', 'directorypress-payment-manager').'</a>';
								$output .= ' | ';
								$output .= '<a class="directorypress-package-action-link success" href="#" data-bs-toggle="offcanvas" data-bs-target="#directorypress-backend-offcanvas" data-id="'. esc_attr($item->id) .'" data-action="package_pricing" data-title="'. esc_attr__('Package price:', 'DIRECTORYPRESS') .' '. esc_attr($item->name) .'">'. esc_html__('Set Price', 'directorypress-payment-manager').'</a>';
								$output .= ' | ';
								$output .= '<a class="directorypress-package-action-link delete" href="#" data-bs-toggle="offcanvas" data-bs-target="#directorypress-backend-offcanvas" data-id="'. esc_attr($item->id) .'" data-action="package_delete" data-title="'. esc_attr__('Delete Package:', 'DIRECTORYPRESS') .' '. esc_attr($item->name) .'">'. esc_html__('Delete', 'directorypress-payment-manager').'</a>';
								$output .= ' | ';
								$output .= '<span>'. esc_html__('ID', 'DIRECTORYPRESS') .' : ' . esc_attr($item->id) .'</span>';
								$output .= ' | ';
								$output .= '<span>'. esc_html__('Order', 'DIRECTORYPRESS') .' : '. esc_attr($item->order_num) .'</span>';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			}
		$output .= '</div>';
			
		return $output;
	}
	public function package_pricing($id = null, $action = '', $data = array()) {
		global $directorypress_object;

		$packages = $directorypress_object->packages;
		
		if (!$package = $packages->get_package_by_id($id)){
			$package = new directorypress_package();
		}

		if ($action == 'submit') {
			$validation = new directorypress_form_validation();
			$validation->set_rules('_regular_price', __('Package Price', 'directorypress-payment-manager'), 'required');
			apply_filters('directorypress_package_price_validation', $validation);
			
			if ($validation->run()) {
				
				if($product = $this->get_product_by_package_id($package->id)) {
						
						update_post_meta($product->get_id(), '_regular_price', $data['regular_price']);
						update_post_meta($product->get_id(), '_price', $data['regular_price']);
						
						update_post_meta($product->get_id(), '_sale_price', $data['sale_price']);
						if($data['sale_price']){
							
							update_post_meta($product->get_id(), '_price', $data['sale_price']);
						}
						
						update_post_meta($product->get_id(), '_bumpup_price', $data['bumpup_price']);
					
						directorypress_add_notification(__('Package price updated!', 'directorypress-payment-manager'));
					$values = array();
					$values['regular_price'] = get_post_meta($product->get_id(), '_regular_price', true);
					$values['sale_price'] = get_post_meta($product->get_id(), '_sale_price', true);
					$values['bumpup_price'] = get_post_meta($product->get_id(), '_bumpup_price', true);
					dppm_renderTemplate('partials/pricing.php', array('package' => $package, 'id' => $id, 'values' => $values));
				} else {
					$price = (!empty($data['sale_price']))? $data['sale_price']: $data['regular_price'];
					$post_id = wp_insert_post( array(
					    'post_title' => $package->name,
					    'post_status' => 'publish',
					    'post_type' => "product",
					), true);
					if (!is_wp_error($post_id)) {
						wp_set_object_terms($post_id, 'listing_single', 'product_type');
						update_post_meta($post_id, '_visibility', 'visible');
						update_post_meta($post_id, '_stock_status', 'instock');
						update_post_meta($post_id, 'total_sales', '0');
						update_post_meta($post_id, '_downloadable', 'no');
						update_post_meta($post_id, '_virtual', 'yes');
						update_post_meta($post_id, '_regular_price', $data['regular_price']);
						update_post_meta($post_id, '_sale_price', $data['sale_price']);
						update_post_meta($post_id, '_bumpup_price', $data['bumpup_price']);
						update_post_meta($post_id, '_purchase_note', '');
						update_post_meta($post_id, '_has_featured', 'no');
						update_post_meta($post_id, '_weight', '');
						update_post_meta($post_id, '_length', '');
						update_post_meta($post_id, '_width', '');
						update_post_meta($post_id, '_height', '');
						update_post_meta($post_id, '_sku', '');
						update_post_meta($post_id, '_product_attributes', array());
						update_post_meta($post_id, '_sale_price_dates_from', '');
						update_post_meta($post_id, '_sale_price_dates_to', '');
						update_post_meta($post_id, '_price', $price);
						update_post_meta($post_id, '_sold_individually', '');
						update_post_meta($post_id, '_manage_stock', 'no');
						update_post_meta($post_id, '_backorders', 'no');
						update_post_meta($post_id, '_stock', '');
	
						update_post_meta($post_id, '_listings_package', $package->id);
					}
					//if ($items->create_package_from_array($validation->result_array())) {
						directorypress_add_notification(__('Package created successfully!', 'directorypress-payment-manager'));
					//}
				}
			} else {
				directorypress_add_notification($validation->error_array(), 'error');

				dppm_renderTemplate('partials/pricing.php', array('package' => $package, 'id' => $id));
			}
		} else {
			$values = array('regular_price' => '', 'sale_price' => '', 'bumpup_price' => '',);
			if($product = $this->get_product_by_package_id($package->id)) {
				$values['regular_price'] = get_post_meta($product->get_id(), '_regular_price', true);
				$values['sale_price'] = get_post_meta($product->get_id(), '_sale_price', true);
				$values['bumpup_price'] = get_post_meta($product->get_id(), '_bumpup_price', true);
			}
			dppm_renderTemplate('partials/pricing.php', array('package' => $package, 'id' => $id, 'values' => $values));
		}
	}
	public function get_product_by_package_id($package_id) {
		$result = get_posts(array(
				'post_type' => 'product',
				'posts_per_page' => 1,
				'tax_query' => array(array(
						'taxonomy' => 'product_type',
						'field' => 'slug',
						'terms' => array('listing_single'),
						'operator' => 'IN'
				)),
				'meta_query' => array(
						array(
								'key' => '_listings_package',
								'value' => $package_id,
								'type' => 'numeric'
						)
				)
		));
		if ($result)
			return wc_get_product($result[0]->ID);
	}
	public function configure($id) {
		global $directorypress_object;
		$items = $directorypress_object->packages;
		$item = $items->get_package_by_id($id);
		$featured_package = ($item->featured_package)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$has_bumpup = ($item->can_be_bumpup)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$has_featured = ($item->has_featured)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$has_sticky = ($item->has_sticky)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Id', 'directorypress-payment-manager').'</label><span>'. $item->id.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Name', 'directorypress-payment-manager').'</label><span>'. $item->name.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Expiry', 'directorypress-payment-manager').'</label><span>'. $item->get_active_duration_string().'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Number Of Listings', 'directorypress-payment-manager').'</label><span>'. $item->number_of_listings_in_package.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Bumpup?', 'directorypress-payment-manager').'</label><span>'. $has_bumpup.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Sticky', 'directorypress-payment-manager').'</label><span>'. $has_sticky.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Featured', 'directorypress-payment-manager').'</label><span>'. $has_featured.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Number Of Images', 'directorypress-payment-manager').'</label><span>'. $item->images_allowed.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Number Of Videos', 'directorypress-payment-manager').'</label><span>'. $item->videos_allowed.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Number Of Categories', 'directorypress-payment-manager').'</label><span>'. $item->category_number_allowed.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Number Of Locations', 'directorypress-payment-manager').'</label><span>'. $item->location_number_allowed.'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Is This Featured Package?', 'directorypress-payment-manager').'</label><span>'. $featured_package .'</span>';
		echo '</div>';
		
		die();
	}
	public function add_or_edit_package($id = null, $action = '') {
		global $directorypress_object;

		$items = $directorypress_object->packages;
		
		if (!$item = $items->get_package_by_id($id)){
			$item = new directorypress_package();
		}

		if ($action == 'submit') {
			$validation = new directorypress_form_validation();
			$validation->set_rules('name', __('Package Name', 'directorypress-payment-manager'), 'required');
			$validation->set_rules('who_can_submit', __('User roles to submit listings', 'directorypress-payment-manager'));
			$validation->set_rules('package_duration', __('active interval', 'directorypress-payment-manager'), 'is_natural');
			$validation->set_rules('package_duration_unit', __('active period', 'directorypress-payment-manager'));
			$validation->set_rules('package_no_expiry', __('eternal active period', 'directorypress-payment-manager'), 'is_checked');
			$validation->set_rules('number_of_listings_in_package', __('listings in package', 'directorypress-payment-manager'), 'is_natural');
			$validation->set_rules('number_of_package_renew_allowed', __('package renew allowed', 'directorypress-payment-manager'));
			$validation->set_rules('change_package_id', __('change package ID', 'directorypress-payment-manager'), 'is_natural');
			$validation->set_rules('description', __('Level description', 'directorypress-payment-manager'));
			$validation->set_rules('can_be_bumpup', __('Ability to raise up listings', 'directorypress-payment-manager'), 'is_checked');
			$validation->set_rules('has_sticky', __('Sticky listings', 'directorypress-payment-manager'), 'is_checked');
			$validation->set_rules('has_featured', __('Featured listings', 'directorypress-payment-manager'), 'is_checked');
			$validation->set_rules('category_number_allowed', __('Categories number available', 'directorypress-payment-manager'), 'is_natural');
			$validation->set_rules('featured_package', __('Make This Level Featured/Popular', 'directorypress-payment-manager'), 'is_checked');
			$validation->set_rules('images_allowed', __('Images number available', 'directorypress-payment-manager'), 'is_natural');
			$validation->set_rules('videos_allowed', __('Videos number available', 'directorypress-payment-manager'), 'is_natural');
			$validation->set_rules('selected_categories', __('Assigned categories', 'directorypress-payment-manager'));
			$validation->set_rules('selected_locations', __('Assigned Locations', 'directorypress-payment-manager'));
			$validation->set_rules('fields', __('Assigned content fields', 'directorypress-payment-manager'));
			$validation->set_rules('location_number_allowed', __('Locations number', 'directorypress-payment-manager'), 'is_natural');
			$validation->set_rules('selection_items[]', __('Options', 'directorypress-payment-manager'));
			//$validation->set_rules('icon_selection_items[]', __("Option's Icon", 'directorypress-advanced-fields'), 'required');
			apply_filters('directorypress_package_validation', $validation);
			
			if ($validation->run()) {
				if ($item->id) {
					if ($items->save_package_from_array($id, $validation->result_array())) {
						directorypress_add_notification(__('Package updated successfully!', 'directorypress-payment-manager'));
					}
					$fields = $directorypress_object->fields->fields_array;
					$item->build_package_from_array($validation->result_array());
					dppm_renderTemplate('partials/add-or-edit.php', array('item' => $item, 'id' => $id, 'fields' => $fields));
				} else {
					if ($items->create_package_from_array($validation->result_array())) {
						directorypress_add_notification(__('Package created successfully!', 'directorypress-payment-manager'));
					}
				}
			} else {
				$item->build_package_from_array($validation->result_array());
				directorypress_add_notification($validation->error_array(), 'error');
				//$options = $item->selection_items;
				$fields = $directorypress_object->fields->fields_array;
				dppm_renderTemplate('partials/add-or-edit.php', array('item' => $item, 'id' => $id, 'fields' => $fields));
			}
		} else {
			$fields = $directorypress_object->fields->fields_array;
			//$options = $item->selection_items;
			dppm_renderTemplate('partials/add-or-edit.php', array('item' => $item, 'id' => $id, 'fields' => $fields));
		}
	}
	
	public function delete_package($id, $action = '') {
		global $directorypress_object;

		$items = $directorypress_object->packages;
		if ($item = $items->get_package_by_id($id)) {
			if ($action == 'delete') {
				if ($items->delete_package($id)){
					directorypress_add_notification(__('Package deleted successfully!', 'directorypress-payment-manager'));
				}
			} else{
				echo '<div class="directorypress-delete">';
					echo '<p class="alert alert-warning">'. sprintf(esc_html__('Are you sure you want delete "%s" Package with all listings inside?', 'directorypress-payment-manager'), $item->name).'</p>';
				echo '</div>';
			}
		}
	}
	
	public function package_upgrade_downgrade($id, $action = '') {
		global $directorypress_object;

		$items = $directorypress_object->packages;
		
		if ($action == 'upgrade_downgrade') {
			$results = array();
			$item1 = $items->packages_array[$id];
			foreach ($items->packages_array AS $item2) {
					if (directorypress_get_input_value($_POST, 'package_disabled_' . $item1->id . '_' . $item2->id) || $item1->id == $item2->id)
						$results[$item1->id][$item2->id]['disabled'] = true;
					else
						$results[$item1->id][$item2->id]['disabled'] = false;

					if (directorypress_get_input_value($_POST, 'package_raiseup_' . $item1->id . '_' . $item2->id) || $item1->id == $item2->id)
						$results[$item1->id][$item2->id]['raiseup'] = true;
					else
						$results[$item1->id][$item2->id]['raiseup'] = false;
			}
			$item1->save_upgrade_meta($results[$item1->id]);
			directorypress_add_notification(__('Settings updated successfully!', 'directorypress-payment-manager'));
		}else{	
			dppm_renderTemplate('partials/upgrade-downgrade.php', array('items' => $items, 'id' => $id));
		}
	}
	
	public function displayChooseLevelTable() {
		global $directorypress_object;

		$packages = $directorypress_object->packages;
		//var_dump($packages);
		//$packages_table = new directorypress_choose_packages_table();
		//$packages_table->prepareItems($packages);
		$output = '';
		foreach ($directorypress_object->directorytypes->directorypress_array_of_directorytypes AS $directorytype) {
			$output .= '<div class="dp-list-section">';
				foreach($directorypress_object->packages->packages_array as $id=>$package){
					if(in_array($package->id, $directorytype->packages)){
						$output .= '<div class="row dp-list-row">';
							$output .= '<div class="col-md-10 txt-left dp-list-item"><i class="fas fa-arrows-alt"></i>'.$package->name.'</div>';
							$output .= '<div class="col-md-2 text-right dp-list-item">'. sprintf('<a href="%s">Create %s in this package</a>', esc_url(add_query_arg(array('post_type' => 'dp_listing', 'package_id' => $package->id, 'directory_id' => $directorytype->id), admin_url('post-new.php'))), $directorytype->single) .'</div>';
						$output .= '</div>';
					}else{
						$output .= '<div class="row dp-list-row">';
							$output .= '<div class="col-md-10 txt-left dp-list-item">'.$package->name.'</div>';
							$output .= '<div class="col-md-2 text-right dp-list-item">'. sprintf('<a href="%s">Create %s in this package</a>', esc_url(add_query_arg(array('post_type' => 'dp_listing', 'package_id' => $package->id, 'directory_id' => $directorytype->id), admin_url('post-new.php'))), $directorytype->single) .'</div>';
						$output .= '</div>';
					}
				}
			$output .= '</div>';
		}
		$packages_count = count($directorypress_object->packages->packages_array);

		dppm_renderTemplate('partials/directorypress_select_package_table.php', array('packages_table' => $output, 'packages_count' => $packages_count));
	}
}

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class directorypress_manage_packages_table extends WP_List_Table {

	public function __construct() {
		parent::__construct(array(
				'singular' => __('Package', 'directorypress-payment-manager'),
				'plural' => __('Packages', 'directorypress-payment-manager'),
				'ajax' => false
		));
	}

	public function get_columns($packages = array()) {
		$columns = array(
				'package_name' => __('Title', 'directorypress-payment-manager'),
				'package_duration_unit' => __('Package Expiry', 'directorypress-payment-manager'),
				'featured_package' => __('Package Type', 'directorypress-payment-manager'),
				'id' => __('Package ID', 'directorypress-payment-manager'),
		);
		$columns = apply_filters('directorypress_package_table_header', $columns, $packages);

		return $columns;
	}
	
	public function getItems($packages) {
		$items_array = array();
		foreach ($packages->packages_array as $id=>$package) {
			$items_array[$id] = array(
					'id' => $package->id.'<i class="glyphicon glyphicon-move"></i>',
					'package_name' => $package->name,
					'package_duration_unit' => $package->get_active_duration_string(),
					'featured_package' => $package->featured_package,
			);

			$items_array[$id] = apply_filters('directorypress_package_table_row', $items_array[$id], $package);
		}
		return $items_array;
	}

	public function prepareItems($packages) {
		$this->_column_headers = array($this->get_columns($packages), array(), array());
		
		$this->items = $this->getItems($packages);
	}
	
	public function column_package_name($item) {
		$actions = array(
				'edit' => sprintf('<a href="?page=%s&action=%s&package_id=%d">' . __('Edit', 'directorypress-payment-manager') . '</a>', $_GET['page'], 'edit', $item['id']),
				'upgrade' => sprintf('<a href="?page=%s&action=%s&package_id=%d">' . __('Upgrade', 'directorypress-payment-manager') . '</a>', $_GET['page'], 'upgrade', $item['id']),
				'delete' => sprintf('<a href="?page=%s&action=%s&package_id=%d">' . __('Delete', 'directorypress-payment-manager') . '</a>', $_GET['page'], 'delete', $item['id']),
				);
		return sprintf('%1$s %2$s', sprintf('<a href="?page=%s&action=%s&package_id=%d">' . $item['package_name'] . '</a><input type="hidden" class="package_weight_id" value="%d" />', $_GET['page'], 'edit', $item['id'], $item['id']), $this->row_actions($actions));
	}
	public function column_featured_package($item) {
		if ($item['featured_package'])
			return '<span class="">Featured Package</span>';
		else
			return '<span class="">Normal Package</span>';
	}
	public function column_default($item, $column_name) {
		switch($column_name) {
			default:
				return $item[$column_name];
		}
	}
	
	function no_items() {
		__('No packages found.', 'directorypress-payment-manager');
	}
}

class directorypress_choose_packages_table extends WP_List_Table {

	public function __construct() {
		parent::__construct(array(
				'singular' => __('Package', 'directorypress-payment-manager'),
				'plural' => __('Packages', 'directorypress-payment-manager'),
				'ajax' => false
		));
	}

	public function get_columns($packages = array()) {
		$columns = array(
				'id' => __('ID', 'directorypress-payment-manager'),
				'package_name' => __('Name', 'directorypress-payment-manager'),
				'package_duration_unit' => __('Active period', 'directorypress-payment-manager'),
				'featured_package' => __('Package Type', 'directorypress-payment-manager'),
				'create' => ''
		);
		$columns = apply_filters('directorypress_package_table_header', $columns, $packages);
		
		return $columns;
	}

	public function getItems($packages) {
		$items_array = array();
		foreach ($packages->packages_array as $id=>$package) {
			$items_array[$id] = array(
					'id' => $package->id,
					'package_name' => $package->name,
					'package_duration_unit' => $package->get_active_duration_string(),
					'featured_package' => $package->featured_package,
			);

			$items_array[$id] = apply_filters('directorypress_package_table_row', $items_array[$id], $package);
		}
		return $items_array;
	}

	public function prepareItems($packages) {
		$this->_column_headers = array($this->get_columns($packages), array(), array());

		$this->items = $this->getItems($packages);
	}

	public function column_create($item) {
		global $directorypress_object;
		
		$out = array();
		foreach ($directorypress_object->directorytypes->directorypress_array_of_directorytypes AS $directorytype) {
			$package = $directorypress_object->packages->get_package_by_id($item['id']);
			if (directorypress_is_user_allowed($package->who_can_submit)) {
				if ($directorytype->packages) {
					if (in_array($item['id'], $directorytype->packages)) {
						$out[] = sprintf('<a href="%s">Create %s in this package</a>', esc_url(add_query_arg(array('post_type' => 'dp_listing', 'package_id' => $item['id'], 'directory_id' => $directorytype->id), admin_url('post-new.php'))), $directorytype->single);
					}
				} else {
					$out[] = sprintf('<a href="%s">Create %s in this package</a>', esc_url(add_query_arg(array('post_type' => 'dp_listing', 'package_id' => $item['id'], 'directory_id' => $directorytype->id), admin_url('post-new.php'))), $directorytype->single);
				}
			}
		}
		return implode('<br />', $out);
	}
	public function column_featured_package($item) {
		if ($item['featured_package'])
			return '<span class="">Featured Package</span>';
		else
			return '<span class="">Normal Package</span>';
	}

	public function column_default($item, $column_name) {
		switch($column_name) {
			default:
				return $item[$column_name];
		}
	}
	
	function no_items() {
		esc_attr__("No Package found. Can't create new Advert.", 'directorypress-payment-manager');
	}
}

?>