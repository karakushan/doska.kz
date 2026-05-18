<?php
 
global $DIRECTORYPRESS_ADIMN_SETTINGS;

if (directorypress_has_wc() && directorypress_is_payment_manager_active()) {
	
	add_action('vp_directorypress_option_after_ajax_save', 'woo_save_option', 11, 3);
	function woo_save_option($opts, $old_opts, $status) {
		global $directorypress_object;
	
		if ($status) {
			if (get_option('directorypress_woocommerce_functionality') && !get_option('directorypress_woocommerce_produts_created')) {
				foreach ($directorypress_object->packages->packages_array as $package) {
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
						update_post_meta($post_id, '_regular_price', '');
						update_post_meta($post_id, '_sale_price', '');
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
						update_post_meta($post_id, '_price', '');
						update_post_meta($post_id, '_sold_individually', '');
						update_post_meta($post_id, '_manage_stock', 'no');
						update_post_meta($post_id, '_backorders', 'no');
						update_post_meta($post_id, '_stock', '');
	
						update_post_meta($post_id, '_listings_package', $package->id);
					}
				}
				add_option('directorypress_woocommerce_produts_created', true);
			}
		}
	}
}

if (directorypress_has_wc() && directorypress_is_payment_manager_active()) {
	
	include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-payment-manager-product.php';
	
	global $directorypress_object;

	$directorypress_object->listing_single_product = new directorypress_listing_single_product;

	// Remove listings products from the Shop
	add_action('woocommerce_product_query', 'directorypress_exclude_products_from_shop');
	
	function directorypress_exclude_products_from_shop($q) {
		$tax_query = (array) $q->get('tax_query');

		$tax_query[] = array(
			'taxonomy' => 'product_type',
			'field' => 'slug',
			'terms' => array('listing_single'),
			'operator' => 'NOT IN'
		);
		
		$q->set('tax_query', $tax_query);
	}
	add_action('woocommerce_shortcode_products_query', 'directorypress_exclude_products_from_shortcode');
	function directorypress_exclude_products_from_shortcode($query_args) {
		$query_args['tax_query'] =  array(array( 
            'taxonomy' => 'product_type', 
            'field' => 'slug', 
            'terms' => array('listing_single'), // Don't display products from this category
            'operator' => 'NOT IN'
        )); 
 
		return $query_args;
	}
	
	function directorypress_format_price($price) {
		if ($price == 0) {
			$out = '<span class="directorypress-payments-free">' . __('FREE', 'directorypress-payment-manager') . '</span>';
		} else {
			$out = wc_price($price);
		}
		return $out;
	}
	
	function directorypress_recalcPrice($price) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		// if any services are free for admins - show 0 price
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_payments_free_for_admins'] && current_user_can('manage_options')) {
			return 0;
		} else
			return $price;
	}
	
	function directorypress_get_last_order_of_listing($listing_id, $actions = array('activation', 'renew', 'upgrade', 'raiseup')) {
		global $wpdb;

		$orders = directorypress_get_all_orders_of_listing($listing_id, $actions);
		
		return array_pop($orders);
	}

	function directorypress_get_all_orders_of_listing($listing_id, $actions = array('activation', 'renew', 'upgrade', 'raiseup')) {
		global $wpdb;
		
		$sql_meta_actions = '';
		if (!is_array($actions)) {
			$actions = array($actions);
		}
		if ($actions) {
			$sql_meta_actions = "AND woo_meta2.meta_key = '_directory_action' AND (";
			$meta_actions = array();
			foreach ($actions AS $action) {
				$meta_actions[] = "woo_meta2.meta_value = '" . $action . "'";
			}
			$sql_meta_actions .= implode(' OR ', $meta_actions);
			$sql_meta_actions .= ")";
		}

		$results = $wpdb->get_results(
			$wpdb->prepare("
				SELECT woo_meta.order_item_id AS last_item_id, woo_orders.order_id AS order_id
				FROM {$wpdb->prefix}woocommerce_order_itemmeta AS woo_meta
				LEFT JOIN {$wpdb->prefix}woocommerce_order_items AS woo_orders ON woo_meta.order_item_id = woo_orders.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woo_meta2 ON woo_meta.order_item_id = woo_meta2.order_item_id
				WHERE woo_meta.meta_key = '_directorypress_listing_id'
				" . $sql_meta_actions . "
				AND woo_meta.meta_value = %d
				GROUP BY order_id
				", $listing_id),
		ARRAY_A);

		$orders = array();
		foreach ($results AS $row) {
			$order = wc_get_order($row['order_id']);
			if (is_object($order) && get_class($order) == 'WC_Order') {
				$orders[] = $order;
			}
		}

		return $orders;
	}
	
	function directorypress_get_last_subscription_of_listing($listing_id) {
		global $wpdb;

		$results = $wpdb->get_results($wpdb->prepare("SELECT woo_meta.order_item_id AS last_item_id, woo_orders.order_id AS order_id FROM {$wpdb->prefix}woocommerce_order_itemmeta AS woo_meta LEFT JOIN {$wpdb->prefix}woocommerce_order_items AS woo_orders ON woo_meta.order_item_id = woo_orders.order_item_id WHERE woo_meta.meta_key = '_directorypress_listing_id' AND woo_meta.meta_value = %d", $listing_id), ARRAY_A);
		$orders = array();
		foreach ($results AS $row) {
			$order = wc_get_order($row['order_id']);
			if (is_object($order) && get_class($order) == 'WC_Subscription') {
				$orders[] = wc_get_order($row['order_id']);
			}
		}
	
		return array_pop($orders);
	}

	function directorypress_set_order_address($order, $user_id) {
		$address = array(
			'first_name' => get_user_meta($user_id, 'billing_first_name', true),
			'last_name'  => get_user_meta($user_id, 'billing_last_name', true),
			'company'    => get_user_meta($user_id, 'billing_company', true),
			'email'      => get_user_meta($user_id, 'billing_email', true),
			'phone'      => get_user_meta($user_id, 'billing_phone', true),
			'address_1'  => get_user_meta($user_id, 'billing_address_1', true),
			'address_2'  => get_user_meta($user_id, 'billing_address_2', true),
			'city'       => get_user_meta($user_id, 'billing_city', true),
			'state'      => get_user_meta($user_id, 'billing_state', true),
			'postcode'   => get_user_meta($user_id, 'billing_postcode', true),
			'country'    => get_user_meta($user_id, 'billing_country', true),
		);
		$order->set_address($address, 'billing');
	}
	
	add_action('directorypress_dashboard_links', 'woo_add_orders_dashboard_link');
	function woo_add_orders_dashboard_link() {
		$orders_page_endpoint = get_option('woocommerce_myaccount_orders_endpoint', 'orders');
		$myaccount_page = get_option('woocommerce_myaccount_page_id');
		if ($orders_page_endpoint && $myaccount_page && ($orders_url = wc_get_endpoint_url($orders_page_endpoint, '', get_permalink($myaccount_page)))) {
			$args = array(
			    	'post_status' => 'any',
			    	'post_type' => 'shop_order',
					'posts_per_page' => -1,
					'meta_key' => '_customer_user',
					'meta_value' => get_current_user_id()
			);
			$orders_query = new WP_Query($args);
			wp_reset_postdata();

			echo '<li><a href="' . $orders_url . '">' . __('My orders', 'directorypress-payment-manager'). ' (' . $orders_query->found_posts . ')</a></li>';
		}
	}
	add_filter( 'woocommerce_add_to_cart_validation', 'remove_cart_item_before_add_to_cart', 20, 3 );
	function remove_cart_item_before_add_to_cart( $passed, $product_id, $quantity ) {
		global $woocommerce;
		if( ! WC()->cart->is_empty()){
			$items = $woocommerce->cart->get_cart();
			$product_type = array();
			foreach($items as $item => $values) { 
				$product_id =  wc_get_product( $values['data']->get_id());
				$product_type[] = $product_id->get_id();
				
			} 
			if(in_array('listing_single', $product_type)){
				WC()->cart->empty_cart();
			}
		
		}
		return $passed;
	}
	// Pay order link in listings table
	
	add_action('directorypress_listing_status_option', 'woo_pay_order_link');
	function woo_pay_order_link($listing) {
		global $directorypress_object;

		if ($listing->post->post_author == get_current_user_id() && ($listing->status == 'unpaid' || $listing->status == 'expired')) {
			if (($order = directorypress_get_last_order_of_listing($listing->post->ID)) && !$order->is_paid() && $order->get_status() != 'trash') {
				if ($directorypress_object->listings_packages->can_user_create_listing_in_package($listing->package->id)) {
					echo '<span><a class="label label-primary" href="' . add_query_arg('apply_listing_payment', $listing->post->ID) . '">' . __('apply payment', 'directorypress-payment-manager') . '</a></span>';
				} else {
					$order_url = $order->get_checkout_payment_url();

					echo '<span><a class="label label-primary" href="' . $order_url . '">' . __('pay order', 'directorypress-payment-manager') . '</a></span>';
				}
			} else {
				if ($directorypress_object->listings_packages->can_user_create_listing_in_package($listing->package->id)) {
					echo '<br /><a href="' . add_query_arg('apply_listing_payment', $listing->post->ID) . '">' . __('apply payment', 'directorypress-payment-manager') . '</a>';
				}
			}
		}
	}
	
	add_action('init', 'woo_apply_payment');
	function woo_apply_payment() {
		global $directorypress_object;

		if (isset($_GET['apply_listing_payment']) && is_numeric($_GET['apply_listing_payment'])) {
			$listing_id = directorypress_get_input_value($_GET, 'apply_listing_payment');
			if ($listing_id && directorypress_user_permission_to_edit_listing($listing_id)) {
				$listing = directorypress_get_listing($listing_id);
				if ($listing->status == 'unpaid' || $listing->status == 'expired') {
					if ($directorypress_object->listings_packages->can_user_create_listing_in_package($listing->package->id)) {
						$listing->process_activation(false);
						$directorypress_object->listings_packages->process_listing_creation_for_user($listing->package->id);
						if ($listing->status == 'unpaid')
							directorypress_add_notification(__("Listing was successfully activated.", "directorypress-payment-manager"));
						elseif ($listing->status == 'expired')
							directorypress_add_notification(__("Listing was successfully renewed and activated.", "directorypress-payment-manager"));
						
						wp_redirect(remove_query_arg('apply_listing_payment'));
						die();
					}
				}
			}
		}
	}
	
	add_action('directorypress_listing_info_metabox_html', 'directorypress_last_order_listing_link');
	function directorypress_last_order_listing_link($listing) {
		if ($order = directorypress_get_last_order_of_listing($listing->post->ID)) {
			?>
			<div class="misc-pub-section">
				<?php _e('WC order', 'directorypress-payment-manager'); ?>:
				<?php echo "<a href=". get_edit_post_link($order->get_id()) . ">" . sprintf(__("Order #%d details", "directorypress-payment-manager"), $order->get_id()) . "</a>"; ?>
			</div>
			<?php
		}
	}
	
	// hide meta fields in html-order-item-meta.php
	add_filter('woocommerce_hidden_order_itemmeta', 'directorypress_hide_directory_itemmeta');
	function directorypress_hide_directory_itemmeta($itemmeta) {
		$itemmeta[] = '_directorypress_listing_id';
		$itemmeta[] = '_directory_action';
		$itemmeta[] = '_directorypress_do_subscription';
	
		return $itemmeta;
	}
}

?>