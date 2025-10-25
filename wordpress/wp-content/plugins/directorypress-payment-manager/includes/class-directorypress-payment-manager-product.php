<?php

add_action('init', 'directorypress_register_listing_single_product_type');
function directorypress_register_listing_single_product_type() {
	class WC_Product_Listing_Single extends WC_Product {

		public $package_id = null;
		public $bumpup_price = 0;
		public $product_type;
		public function __construct($product) {
			
			$this->product_type = 'listing_single';

			parent::__construct($product);

			if (get_post_meta($this->id, '_listings_package', true))
				$this->package_id = get_post_meta($this->id, '_listings_package', true);

			if (get_post_meta($this->id, '_bumpup_price', true))
				$this->bumpup_price = get_post_meta($this->id, '_bumpup_price', true);
		}
		
		/* public function get_name($context = 'view') {
			return __("DirectoryPress Package", "directorypress-payment-manager");
		} */
		
		public function get_virtual($context = 'view') {
			return true;
		}
		
		public function get_downloadable($context = 'view') {
			return true;
		}
	}
}

class directorypress_listing_single_product {
	
	public function __construct() {
		add_filter('product_type_selector', array($this, 'add_listing_single_product'));
		add_action('admin_footer', array($this, 'listing_single_custom_js'));
		add_filter('woocommerce_product_data_tabs', array($this, 'hide_attributes_data_panel'));
		add_action('woocommerce_product_options_pricing', array($this, 'add_bumpup_price'));
		add_filter('woocommerce_product_data_panels', array($this, 'new_product_tab_content'));
		add_action('woocommerce_process_product_meta_listing_single', array($this, 'save_listing_single_tab_content'));
		
		add_filter('directorypress_create_option', array($this, 'create_price'), 10, 2);
		add_filter('directorypress_raiseup_option', array($this, 'bumpup_price'), 10, 2);
		add_filter('directorypress_renew_option', array($this, 'renew_price'), 10, 2);
		add_filter('directorypress_package_upgrade_option', array($this, 'upgrade_price'), 10, 3);

		add_filter('directorypress_submitlisting_package_price', array($this, 'packages_price_front_table_row'), 10, 2);
		
		add_filter('directorypress_package_table_header', array($this, 'packages_price_table_header'));
		add_filter('directorypress_package_table_row', array($this, 'packages_price_table_row'), 10, 1);
		
		add_filter('directorypress_package_upgrade_meta', array($this, 'packages_upgrade_meta'), 10, 2);
		add_action('directorypress_upgrade_meta_html', array($this, 'packages_upgrade_meta_html'), 10, 2);
		
		// Woocommerce Dashboard - Order Details
		add_filter('woocommerce_order_item_permalink', array($this, 'disable_listing_product_permalink'), 10, 3);
		add_action('woocommerce_order_item_meta_start', array($this, 'listing_in_order_table'), 10, 3);
		add_action('woocommerce_after_order_itemmeta', array($this, 'listing_in_order_table'), 10, 3);
		add_filter('woocommerce_display_item_meta', array($this, 'listing_in_subscriptions_table'), 10, 3);
		// Woocommerce Checkout
		add_filter('woocommerce_get_item_data', array($this, 'listing_in_checkout'), 10, 2);
		add_action('woocommerce_before_calculate_totals', array($this, 'checkout_listing_bumpup_price'));
		add_action('woocommerce_before_calculate_totals', array($this, 'checkout_listing_upgrade_price'));
		// Woocommerce add order item meta
		add_action('woocommerce_new_order_item', array($this, 'add_order_item_meta'), 10, 3);
		add_action('woocommerce_order_again_cart_item_data', array($this, 'order_again_cart_item_data'), 10, 3);
		// when guest user creates new profile after he created a listing
		add_filter('woocommerce_new_customer_data', array($this, 'update_user_info'));
		// when guest user logs in after he created a listing
		add_filter('woocommerce_checkout_customer_id', array($this, 'reassign_user'));
		// add subscription meta to order
		//add_action('woocommerce_checkout_update_order_meta', array($this, 'add_subscription_order_meta'), 10, 2);
		// tell WCS, that cart may contain subscription
		add_filter('woocommerce_is_subscription', array($this, 'is_subscription'), 10, 3);
		add_filter('woocommerce_subscriptions_product_period', array($this, 'subscriptions_product_period'), 10, 2);
		add_filter('woocommerce_subscriptions_product_period_interval', array($this, 'subscriptions_product_period_interval'), 10, 2);
		add_filter('woocommerce_cart_subscription_string_details', array($this, 'cart_subscription_string_details'), 10, 2);
		add_action('woocommerce_checkout_order_processed', array($this, 'checkout_order_processed'), 10, 2);
		
		add_filter('directorypress_listing_creation_front', array($this, 'create_activation_order'));
		add_filter('directorypress_listing_renew', array($this, 'renew_listing_order'), 10, 3);
		add_filter('directorypress_listing_bumpup', array($this, 'listing_raiseup_order'), 10, 3);
		add_filter('directorypress_listing_upgrade', array($this, 'listing_upgrade_order'), 10, 3);

		// add subscription button and process subscription
		add_action('directorypress_dashboard_listing_options', array($this, 'add_subscription_button'));
		add_action('directorypress_dashboard_handler_construct', array($this, 'create_subscription_onclick'));
		
		//add_filter('woocommerce_payment_complete_order_status', array($this, 'complete_payment'), 10, 2);
		add_action('woocommerce_order_status_completed', array($this, 'complete_status'), 10);
	}

	public function add_listing_single_product($types){
		$types['listing_single'] = __('DirectoryPress Package', 'directorypress-payment-manager');
	
		return $types;
	}
	
	public function listing_single_custom_js() {
		if ('product' != get_post_type())
			return;
	
		?><script type='text/javascript'>
				jQuery(document).ready( function($) {
					$('.options_group.pricing').addClass('show_if_listing_single').show();
					$('.options_group.show_if_downloadable').addClass('hide_if_listing_single').hide();
					$('.general_tab').addClass('active').show();
					$('.listings_tab').removeClass('active');
					$('#general_product_data').show();
					$('#listing_single_product_data').hide();
					$('._tax_status_field').parent().addClass('show_if_listing_single');
					if ($('#product-type option:selected').val() == 'listing_single') {
						$('.show_if_listing_single').show();
						$('.hide_if_listing_single').hide();
					}
				});
			</script><?php
	}
	
	public function hide_attributes_data_panel($tabs) {
		// Other default values for 'attribute' are; general, inventory, shipping, linked_product, variations, advanced
		$tabs['inventory']['class'][] = 'hide_if_listing_single';
		$tabs['shipping']['class'][] = 'hide_if_listing_single';
		$tabs['linked_product']['class'][] = 'hide_if_listing_single';
		$tabs['variations']['class'][] = 'hide_if_listing_single';
		$tabs['attribute']['class'][] = 'hide_if_listing_single';
		$tabs['advanced']['class'][] = 'hide_if_listing_single';
	
		$tabs['listings'] = array(
				'label'	=> __('Package Setting', 'directorypress-payment-manager'),
				'target' => 'listing_single_product_data',
				'class'	=> array('show_if_listing_single', 'show_if_listing_single', 'advanced_options'),
		);
		return $tabs;
	}

	public function add_bumpup_price() {
		woocommerce_wp_text_input(array('id' => '_bumpup_price', 'label' => __('Bump Up Price', 'directorypress-payment-manager') . ' (' . get_woocommerce_currency_symbol() . ')', 'data_type' => 'price', 'wrapper_class' => 'show_if_listing_single'));
	}
	
	public function new_product_tab_content() {
		global $directorypress_object;
		?>
			<div id="listing_single_product_data" class="panel woocommerce_options_panel">
					<div class="options_group">
						<?php
						$options = array();
						foreach ($directorypress_object->packages->packages_array as $package)
							$options[$package->id] = __(esc_attr($package->name), 'directorypress-payment-manager');
	
						woocommerce_wp_radio(array('id' => '_listings_package', 'options' => $options, 'label' => __('Select Package', 'directorypress-payment-manager')));
						?>
					</div>
			</div>
			<?php 
	}

	public function save_listing_single_tab_content($post_id) {
		update_post_meta($post_id, '_listings_package', (isset($_POST['_listings_package']) ? wc_clean($_POST['_listings_package']) : ''));

		update_post_meta($post_id, '_bumpup_price', (isset($_POST['_bumpup_price']) ? wc_clean($_POST['_bumpup_price']) : ''));
	}
	
	public function create_price($link_text, $listing) {
		if ($product = $this->get_product_by_package_id($listing->package->id)) {
			return  $link_text .' - ' . directorypress_format_price(directorypress_recalcPrice($product->get_price()));
		}
	}
	
	public function bumpup_price($link_text, $listing) {
		if ($product = $this->get_product_by_package_id($listing->package->id)) {
			return  $link_text .' - ' . directorypress_format_price(directorypress_recalcPrice($product->bumpup_price));
		}
		return $link_text;
	}
	
	public function renew_price($link_text, $listing) {
		if ($product = $this->get_product_by_package_id($listing->package->id)) {
			return  $link_text .' - ' . directorypress_format_price(directorypress_recalcPrice($product->get_price()));
		}
		return $link_text;
	}
	
	public function upgrade_price($link_text, $old_package, $new_package) {
		return $link_text .' - ' . (isset($old_package->upgrade_meta[$new_package->id]) ? directorypress_format_price(directorypress_recalcPrice($old_package->upgrade_meta[$new_package->id]['price'])) : directorypress_format_price(0));
	}

	public function packages_price_front_table_row($price, $package) {
		if (!($product = $this->get_product_by_package_id($package->id)) || directorypress_recalcPrice($product->get_price()) == 0) {
			$out = '<span class="free-package">'. esc_html__('Free', 'directorypress-payment-manager') .'</span>';
			return $out;
		} else {
			return $product->get_price_html();
		}

	}
	
	public function packages_price_table_header($columns) {
		$directorypress_columns['price'] = __('Price', 'directorypress-payment-manager');
	
		return array_slice($columns, 0, 2, true) + $directorypress_columns + array_slice($columns, 2, count($columns)-2, true);
	}
	
	public function packages_price_table_row($package) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if (!($product = $this->get_product_by_package_id($package->id)) || ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_payments_free_for_admins'] && current_user_can('manage_options'))) {
			$price = '<span class="directorypress-payments-free">' . __('FREE', 'directorypress-payment-manager') . '</span>';
		} else {
			$price = $product->get_price_html();
		}
	
		return $price;
	}
	
	public function packages_upgrade_meta($upgrade_meta, $package) {
		global $directorypress_object;
	
		//if (directorypress_get_input_value($_GET, 'page') == 'directorypress_packages') {
			$results = array();
			foreach ($directorypress_object->packages->packages_array AS $_package) {
				if (($price = directorypress_get_input_value($_POST, 'package_price_' . $package->id . '_' . $_package->id)) && is_numeric($price)) {
					$results[$_package->id]['price'] = $price;
				} else {
					$results[$_package->id]['price'] = 0;
				}
			}
	
			foreach ($upgrade_meta AS $package_id=>$meta) {
				if (isset($results[$package_id])) {
					$upgrade_meta[$package_id] = $results[$package_id] + $upgrade_meta[$package_id];
				}
			}
		//}
	
		return $upgrade_meta;
	}
	
	public function packages_upgrade_meta_html($package1, $package2) {
		if (isset($package1->upgrade_meta[$package2->id]) && isset($package1->upgrade_meta[$package2->id]['price'])) {
			$price = $package1->upgrade_meta[$package2->id]['price'];
		} else {
			$price = 0;
		}
	
		echo get_woocommerce_currency_symbol() . '<input type="text" size="4" name="package_price_' . $package1->id . '_' . $package2->id . '" value="' . esc_attr($price) . '" /><br />';
	}

	// Woocommerce Dashboard

	public function disable_listing_product_permalink($permalink, $item, $order) {
		if (method_exists($item, 'get_product_id') && ($product = wc_get_product($item->get_product_id())) && $product->get_type() == 'listing_single') {
			return false;
		}

		return $permalink;
	}

	public function listing_in_order_table($item_id, $item, $order) {
		if (method_exists($item, 'get_product_id') && ($product = wc_get_product($item->get_product_id())) && $product->get_type() == 'listing_single') {
			if ($listing = $this->get_listing_by_item_id($item_id)) {
				$action = wc_get_order_item_meta($item_id, '_directory_action');

				if (is_user_logged_in() && directorypress_user_permission_to_edit_listing($listing->post->ID))
					$listing_link = '<a href="' . directorypress_edit_post_url($listing->post->ID) . '" title="' . esc_attr('edit listing', 'directorypress-payment-manager') . '">' . $listing->title() . '</a>';
				else
					$listing_link = $listing->title();
				?>
					<p>
						<?php echo __('Listing:', 'directorypress-payment-manager') . '&nbsp;' . $listing_link; ?>
						<br />
						<?php if ($action == 'activation'):
						_e('Order for listing activation', 'directorypress-payment-manager'); ?>
						<br />
						<?php endif; ?>
						<?php if ($action == 'renew'):
						_e('Order for listing renewal', 'directorypress-payment-manager'); ?>
						<br />
						<?php endif; ?>
						<?php if ($action == 'raiseup'):
						_e('Order for listing Bump up', 'directorypress-payment-manager'); ?>
						<br />
						<?php endif; ?>
						<?php if ($action == 'upgrade'):
						_e('Order for listing upgrade', 'directorypress-payment-manager'); ?>
						<br />
						<?php endif; ?>
						<?php _e('Status:', 'directorypress-payment-manager');
						if ($listing->status == 'active')
							echo ' <span class="label label-success">' . __('active', 'directorypress-payment-manager') . '</span>';
						elseif ($listing->status == 'expired')
							echo ' <span class="label label-danger">' . __('expired', 'directorypress-payment-manager') . '</span>';
						elseif ($listing->status == 'unpaid')
							echo ' <span class="label label-warning">' . __('unpaid', 'directorypress-payment-manager') . '</span>';
						elseif ($listing->status == 'stopped')
							echo ' <span class="label label-danger">' . __('stopped', 'directorypress-payment-manager') . '</span>';
						?>
						<br />
						<?php _e('Expiration Date:', 'directorypress-payment-manager'); ?>&nbsp;
						<?php if ($listing->package->package_no_expiry) _e('Eternal active period', 'directorypress-payment-manager'); else echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->expiration_date)); ?>
					</p>
					<?php 
			}
		}
	}
	
	public function listing_in_subscriptions_table($html, $item, $args) {
		if (directorypress_get_input_value($_GET, 'post_type') == 'shop_subscription') {
			if (method_exists($item, 'get_product_id') && ($product = wc_get_product($item->get_product_id())) && $product->get_type() == 'listing_single') {
				if ($listing = $this->get_listing_by_item_id($item->get_id())) {
					if ($listing->status == 'active')
						$listing_status = __('active', 'directorypress-payment-manager');
					elseif ($listing->status == 'expired')
						$listing_status = __('expired', 'directorypress-payment-manager');
					elseif ($listing->status == 'unpaid')
						$listing_status = __('unpaid', 'directorypress-payment-manager');
					elseif ($listing->status == 'stopped')
						$listing_status = __('stopped', 'directorypress-payment-manager');
	
					$html .= '<div style="text-align: left;">' .
							__('Directory listing:', 'directorypress-payment-manager') . '&nbsp;' . $listing->title() .
							'<br />' .
							__('Status:', 'directorypress-payment-manager') . '&nbsp;' . $listing_status .
							'<br />' .
							__('Expiration Date:', 'directorypress-payment-manager') . '&nbsp;' .
							(($listing->package->package_no_expiry) ? __('Eternal active period', 'directorypress-payment-manager') : date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->expiration_date)));
							'</div>';
				}
			}
		}
		
		return $html;
	}

	public function update_user_info($customer_data) {
		foreach (WC()->cart->cart_contents as $value) {
			$product = $value['data'];
			if (isset($value['_directorypress_anonymous_user']) && isset($value['_directorypress_listing_id']) && isset($value['_directory_action']) && $value['_directory_action'] == 'activation' && $product->get_type() == 'listing_single') {
				$listing = directorypress_get_listing($value['_directorypress_listing_id']);
				if ($listing) {
					$customer_data['ID'] = $listing->post->post_author;
					return $customer_data;
				}
			}
		}

		return $customer_data;
	}
	
	public function reassign_user($user_id) {
		if ($user_id && get_userdata($user_id) !== false) {
			foreach (WC()->cart->cart_contents as $value) {
				$product = $value['data'];
				if (isset($value['_directorypress_anonymous_user']) && isset($value['_directorypress_listing_id']) && isset($value['_directory_action']) && $value['_directory_action'] == 'activation' && $product->get_type() == 'listing_single') {
					$listing = directorypress_get_listing($value['_directorypress_listing_id']);
					if ($listing && $listing->post->post_author != $user_id) {
						$arg = array(
								'ID' => $listing->post->ID,
								'post_author' => $user_id,
						);
						wp_update_post($arg);
					}
				}
			}
		}
		
		return $user_id;
	}
	
	public function subscriptions_product_period($subscription_period, $product) {
		global $directorypress_object;

		if ($product->get_type() == 'listing_single' && $product->package_id) {
			$package = $directorypress_object->packages->get_package_by_id($product->package_id);
			if (!$package->package_no_expiry) {
				return $package->package_duration_unit;
			}
		}
		
		return $subscription_period;
	}
	
	public function subscriptions_product_period_interval($subscription_period_interval, $product) {
		global $directorypress_object;

		if ($product->get_type() == 'listing_single' && $product->package_id) {
			$package = $directorypress_object->packages->get_package_by_id($product->package_id);
			if (!$package->package_no_expiry) {
				return $package->package_duration;
			}
		}
		
		return $subscription_period_interval;
	}
	
	public function cart_subscription_string_details($subscription_details, $args) {
		global $directorypress_object;
		
		foreach ($args->cart_contents as $value) {
			$product = $value['data'];
			if ($product->get_type() == 'listing_single' && $product->package_id) {
				$package = $directorypress_object->packages->get_package_by_id($product->package_id);
				if (!$package->package_no_expiry) {
					$subscription_details['subscription_interval'] = $package->package_duration;
					$subscription_details['subscription_period'] = $package->package_duration_unit;
				}
			}
		}
		
		return $subscription_details;
	}
	
	public function checkout_order_processed($order_id, $posted_data) {
		global $directorypress_object;
		
		foreach (WC()->cart->recurring_carts as &$recurring_cart) {
			foreach ($recurring_cart->cart_contents as $value) {
				$product = $value['data'];
				if ($product->get_type() == 'listing_single' && $product->package_id) {
					$package = $directorypress_object->packages->get_package_by_id($product->package_id);
					if (!$package->package_no_expiry) {
						$recurring_cart->subscription_period_interval = $package->package_duration;
						$recurring_cart->subscription_period = $package->package_duration_unit;
					}
				}
			}
		}
	}
	
	/*
	 * WC_Subscriptions_Cart::cart_contains_subscription() gives true with this filter,
	 * uses on checkout page and AJAX Apply Coupon function
	 */
	public function is_subscription($is_subscription, $product_id, $product) {
		global $directorypress_object;

		if ($product->get_type() == 'listing_single' && $product->package_id) {
			$package = $directorypress_object->packages->get_package_by_id($product->package_id);
			if (!$package->package_no_expiry) {
				if ($checkboxes = $this->get_subscriptions_checkboxes()) {
					foreach ($checkboxes AS $listing_id=>$is_checked) {
						if ($is_checked) {
							WC()->cart->subscription_period = $package->package_duration_unit;
							WC()->cart->subscription_period_interval = $package->package_duration;
								
							return true;
						} else {
							$last_subscription = directorypress_get_last_subscription_of_listing($listing_id);
							if ($last_subscription && $last_subscription->get_status() != 'active') {
								wp_delete_post($last_subscription->get_id());
							}
						}
					}
				} elseif (directorypress_get_input_value($_REQUEST, 'wc-ajax', 'apply_coupon')) {
					return true;
				}
			}
		}

		return $is_subscription;
	}

	public function listing_in_checkout($item_data, $cart_item) {
		global $directorypress_object;
		
		$product = $cart_item['data'];
		if (isset($cart_item['_directorypress_listing_id']) && $product->get_type() == 'listing_single') {
			$listing = directorypress_get_listing($cart_item['_directorypress_listing_id']);
			if ($listing) {
				$item_data[] = array(
						'name' => __('Listing name', 'directorypress-payment-manager'),
						'value' => $listing->title()
				);
				if (isset($cart_item['_directory_action'])) {
					$item_data[] = array(
							'name' => __('Listing action', 'directorypress-payment-manager'),
							'value' => $cart_item['_directory_action']
					);
				}
				
				$package_no_expiry = $listing->package->package_no_expiry;
				if ($cart_item['_directory_action'] == 'upgrade') {
					$new_package_id = get_post_meta($listing->post->ID, '_new_package_id', true);
					if ($new_package = $directorypress_object->packages->get_package_by_id($new_package_id)) {
						$package_no_expiry = $new_package->package_no_expiry;
					}
				}

				if (
					class_exists('WC_Subscriptions') &&
					is_checkout() &&
					isset($cart_item['_directory_action']) &&
					in_array($cart_item['_directory_action'], array('activation', 'renew', 'upgrade')) &&
					!$package_no_expiry
				) {
					global $DIRECTORYPRESS_ADIMN_SETTINGS;
					$last_subscription = directorypress_get_last_subscription_of_listing($listing->post->ID);
					if ($cart_item['_directory_action'] == 'renew' && $last_subscription && $last_subscription->get_status() == 'active') {
						echo '<div class="directorypress-enable-subsciption-option">';
						echo '<strong>' . __('subscription enabled', 'directorypress-payment-manager') . '</strong>';
						echo '</div>';
					} else {
						$checked = ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_woocommerce_enabled_subscriptions']) ? 1 : 0;

						$checked = $this->is_subscription_checked($listing->post->ID, $checked);
						
						$checked = apply_filters('directorypress_wc_subscriptions_checked', $checked, $item_data, $cart_item);
	
						echo '<div class="directorypress-enable-subsciption-option">';
						echo '<script>
								(function($) {
									"use strict";
									$(function() {
										$(".directorypress_wc_subscription_checkbox_' . $listing->post->ID . '").change(function() {
											$(".directorypress_enable_subscription_' . $listing->post->ID . '").val($(this).is(":checked") ? 1 : 0);
											jQuery(document.body).trigger("update_checkout");
										});
									})
								})(jQuery);
						</script>';
						echo '<label><input type="checkbox" name="directorypress_enable_subscription_checkbox[' . $listing->post->ID . ']" value="1" ' . checked($checked, 1, false) . ' class="directorypress_wc_subscription_checkbox_' . $listing->post->ID . '" />&nbsp;' . __('enable subscription', 'directorypress-payment-manager') . '</label>';
						echo '<input type="hidden" name="directorypress_enable_subscription[' . $listing->post->ID . ']" value="' . $checked . '"  class="directorypress_enable_subscription_' . $listing->post->ID . '" />';
						echo '</div>';
					}
				}
			}
		}
		
		return $item_data;
	}
	
	public function get_subscriptions_checkboxes() {
		if (isset($_POST['post_data'])) {
			parse_str($_POST['post_data'], $post_data);
			if (isset($post_data['directorypress_enable_subscription'])) {
				return $post_data['directorypress_enable_subscription'];
			}
		} elseif (isset($_POST['directorypress_enable_subscription'])) {
			return $_POST['directorypress_enable_subscription'];
		}
		
		return false;
	}

	public function is_subscription_checked($listing_id = null, $checked = 0) {
		if (isset($_POST['post_data'])) {
			parse_str($_POST['post_data'], $post_data);
			if ($listing_id) {
				if (isset($post_data['directorypress_enable_subscription'][$listing_id]) && $post_data['directorypress_enable_subscription'][$listing_id] == 1) {
					$checked = 1;
				} elseif (isset($post_data['directorypress_enable_subscription'][$listing_id]) && $post_data['directorypress_enable_subscription'][$listing_id] == 0) {
					$checked = 0;
				}
			}
			//var_dump($post_data);
			//if ((isset($post_data['directorypress_enable_subscription_' . $package_id]) && $post_data['directorypress_enable_subscription_' . $package_id] == 1) /* || ($last_subscription && $last_subscription->get_status() == 'active') */) {
			//	$checked = 1;
			//} elseif (isset($post_data['directorypress_enable_subscription_' . $package_id]) && $post_data['directorypress_enable_subscription_' . $package_id] == 0) {
			//	$checked = 0;
			//}
			
		}
		
		return $checked;
	}

	/* public function add_subscription_order_meta($order_id, $data) {
		if (class_exists('WC_Subscriptions')) {
			$order = wc_get_order($order_id);
			if (get_class($order) == 'WC_Order') {
				$items = $order->get_items();
				foreach ($items AS $item_id=>$item) {
					if (is_a($item, 'WC_Order_Item_Product') && ($product = wc_get_product($item->get_product_id())) && $product->get_type() == 'listing_single') {
						if ($listing = $this->get_listing_by_item_id($item_id)) {
							if (directorypress_get_input_value($_POST, 'directorypress_enable_subscription_' . $listing->post->ID)) {
								wc_add_order_item_meta($item_id, '_directorypress_do_subscription', true);
							}
						}
					}
				}
			}
		}
	} */
	
	public function checkout_listing_bumpup_price($cart_object) {
		foreach ($cart_object->cart_contents as $value) {
			$product = $value['data'];
			if (isset($value['_directory_action']) && $value['_directory_action'] == 'raiseup' && $product->get_type() == 'listing_single') {
				$value['data']->set_price($value['data']->bumpup_price);
			}
		}
	}

	public function checkout_listing_upgrade_price($cart_object) {
		foreach ($cart_object->cart_contents as $value) {
			$product = $value['data'];
			if (isset($value['_directory_action']) && $value['_directory_action'] == 'upgrade' && $product->get_type() == 'listing_single') {
				$listing = directorypress_get_listing($value['_directorypress_listing_id']);
				$new_package_id = get_post_meta($listing->post->ID, '_new_package_id', true);
				if (
					$listing &&
					isset($listing->package->upgrade_meta[$new_package_id]['price']) &&
					($price = $listing->package->upgrade_meta[$new_package_id]['price']) &&
					directorypress_recalcPrice($price) > 0
				) {
					$value['data']->set_price($price);
				}
			}
		}
	}
	
	public function add_order_item_meta($item_id, $item, $order_id) {
		if (isset($item->legacy_values['_directorypress_listing_id'])) {
			wc_add_order_item_meta($item_id, '_directorypress_listing_id', $item->legacy_values['_directorypress_listing_id']);
		}
		if (isset($item->legacy_values['_directory_action'])) {
			wc_add_order_item_meta($item_id, '_directory_action', $item->legacy_values['_directory_action']);
		}
	}
	
	public function order_again_cart_item_data($cart_item_data, $item, $order) {
		$items = $order->get_items();
		foreach ($items AS $item_id=>$item) {
			if (is_a($item, 'WC_Order_Item_Product') && ($product = wc_get_product($item->get_product_id())) && $product->get_type() == 'listing_single') {
				$listing_id = wc_get_order_item_meta($item_id, '_directorypress_listing_id');
				$action = wc_get_order_item_meta($item_id, '_directory_action');
				
				if ($listing_id && $action) {
					$cart_item_data = $cart_item_data + array(
							'_directorypress_listing_id' => $listing_id,
							'_directory_action' => $action
					);
				}
			}
		}
		
		return $cart_item_data;
	}
	
	public function create_listing_single_order($listing_id, $package_id, $action = 'activation', $redirect = true) {
		if ($product = $this->get_product_by_package_id($package_id)) {
			$options = array(
					'_directorypress_listing_id' => $listing_id,
					'_directory_action' => $action
			);

			if ($action == 'activation' && !is_user_logged_in()) {
				$options['_directorypress_anonymous_user'] = true;
			}

			if (!is_admin() && !defined('DOING_CRON')) {
				if( ! WC()->cart->is_empty()){
					WC()->cart->empty_cart();
				}
				WC()->cart->add_to_cart($product->get_id(), 1, 0, array(), $options);
				if ($redirect && ($checkout_url = wc_get_checkout_url())) {
					$checkout_url = apply_filters("directorypress_wc_create_listing_single_order_url", $checkout_url);
					wp_redirect($checkout_url);
					die();
				}
			} else {
				// on admin dashboard we create new order directly without cart and checkout

				if ($action == 'raiseup') {
					$product->set_price($product->bumpup_price);
				}

				if ($action == 'upgrade') {
					$listing = directorypress_get_listing($listing_id);
					if ($listing && ($price = $listing->package->upgrade_meta[$package_id]['price']) && directorypress_recalcPrice($price) > 0) {
						$product->set_price($price);
					}
				}

				$user_id = get_current_user_id();
				$order = wc_create_order(array('customer_id' => $user_id));

				$order_item_id = $order->add_product($product);
				
				directorypress_set_order_address($order, $user_id);
				
				$order->calculate_totals();
				
				wc_add_order_item_meta($order_item_id, '_directorypress_listing_id', $listing_id);
				wc_add_order_item_meta($order_item_id, '_directory_action', $action);
				
				//if (!defined('DOING_CRON')) {
					//directorypress_add_notification(__('Complete the order on WooCommerce Orders page.', 'directorypress-payment-manager'));
				//}
			}
		}
	}
	
	public function create_listing_single_order_ajax($listing_id, $package_id, $action = 'activation', $redirect = true) {
		if ($product = $this->get_product_by_package_id($package_id)) {
			$options = array(
					'_directorypress_listing_id' => $listing_id,
					'_directory_action' => $action
			);

			if ($action == 'activation' && !is_user_logged_in()) {
				$options['_directorypress_anonymous_user'] = true;
			}

			
			if( ! WC()->cart->is_empty()){
				WC()->cart->empty_cart();
			}
			if(directorypress_recalcPrice($product->get_price()) > 0 && directorypress_recalcPrice($product->get_price()) != ''){
				WC()->cart->add_to_cart($product->get_id(), 1, 0, array(), $options);
				if ($redirect && ($checkout_url = wc_get_checkout_url())) {
					$checkout_url = apply_filters("directorypress_wc_create_listing_single_order_url", $checkout_url);
					return $checkout_url;
				}
			}
			
		}
	}

	public function is_renewal_order($listing) {
		$orders = directorypress_get_all_orders_of_listing($listing->post->ID, 'renew');
		foreach ($orders AS $order) {
			if (!$order->is_paid() && $order->get_status() != 'completed') {
				return true;
			}
		}
		return false;
	}

	public function create_activation_order($listing) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_payments_free_for_admins'] && current_user_can('manage_options')) {
			return $listing;
		}
		if ($listing && ($product = $this->get_product_by_package_id($listing->package->id)) && directorypress_recalcPrice($product->get_price()) > 0) {
			update_post_meta($listing->post->ID, '_listing_status', 'unpaid');
			wp_update_post(array('ID' => $listing->post->ID, 'post_status' => 'pending'));
			//$this->create_listing_single_order($listing->post->ID, $listing->package->id, 'activation');
		}
		return $listing;
	}
	
	public function renew_listing_order($continue, $listing, $continue_invoke_hooks) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_payments_free_for_admins'] && current_user_can('manage_options')) {
			return true;
		}
		if ($continue_invoke_hooks[0]) {
			if ($order = directorypress_get_last_order_of_listing($listing->post->ID)) {
				if (!$order->is_paid() && $order->get_status() != 'completed') {
					$order_url = $order->get_checkout_payment_url();
					if ($order_url && is_user_logged_in()) {
						wp_redirect($order_url);
						die();
					}
					return false;
				}
			}
	
			//if (!$this->is_renewal_order($listing)) {
				if (($product = $this->get_product_by_package_id($listing->package->id)) && directorypress_recalcPrice($product->get_price()) > 0) {
					$this->create_listing_single_order($listing->post->ID, $listing->package->id, 'renew');
					$continue_invoke_hooks[0] = false;
					return false;
				}
			/* } else {
				return false;
			} */
		}

		return $continue;
	}
	
	public function listing_raiseup_order($continue, $listing, $continue_invoke_hooks) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_payments_free_for_admins'] && current_user_can('manage_options')) {
			return true;
		}
		if ($continue_invoke_hooks[0]) {
			if (($product = $this->get_product_by_package_id($listing->package->id)) && directorypress_recalcPrice($product->bumpup_price) > 0) {
				$this->create_listing_single_order($listing->post->ID, $listing->package->id, 'raiseup');
				$continue_invoke_hooks[0] = false;
				return false;
			}
		}
		return $continue;
	}

	public function listing_upgrade_order($continue, $listing, $continue_invoke_hooks) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_payments_free_for_admins'] && current_user_can('manage_options')) {
			return true;
		}
		if ($continue_invoke_hooks[0]) {
			$new_package_id = get_post_meta($listing->post->ID, '_new_package_id', true);
			if ($new_package_id && ($price = $listing->package->upgrade_meta[$new_package_id]['price']) && directorypress_recalcPrice($price) > 0) {
				$this->create_listing_single_order($listing->post->ID, $new_package_id, 'upgrade');
				$continue_invoke_hooks[0] = false;
				return false;
			}
		}
		
		return $continue;
	}

	public function complete_payment($status, $order_id) {
		$this->activate_listing($status, $order_id);
	
		return $status;
	}
	
	public function complete_status($order_id) {
		$this->activate_listing('completed', $order_id);
	}
	
	public function activate_listing($status, $order_id) {
		if ($status == 'completed') {
			$order = wc_get_order($order_id);
			if (get_class($order) == 'WC_Order' || is_subclass_of($order, 'WC_Order')) {
				$items = $order->get_items();
				foreach ($items AS $item_id=>$item) {
					if (is_a($item, 'WC_Order_Item_Product') && ($product = wc_get_product($item->get_product_id())) && $product->get_type() == 'listing_single') {
						if ($listing = $this->get_listing_by_item_id($item_id)) {
							$action = wc_get_order_item_meta($item_id, '_directory_action');
							switch ($action) {
								case "activation":
									$listing->process_activation(false);
									break;
								case "renew":
									$listing->process_activation(false);
									break;
								case "raiseup":
									$listing->process_bumpup(false);
									break;
								case "upgrade":
									$new_package_id = get_post_meta($listing->post->ID, '_new_package_id', true);
									$listing->change_listing_package($new_package_id, false);

									break;
							}
						}
					}
				}
			}
		}
	}
	
	public function add_subscription_button($listing) {
		if (class_exists('WC_Subscriptions')) {
			if (!$listing->package->package_no_expiry) {
				$last_subscription = directorypress_get_last_subscription_of_listing($listing->post->ID);

				if ($listing->status == 'active' && (!$last_subscription || $last_subscription->get_status() == 'trash')) {
					$order = directorypress_get_last_order_of_listing($listing->post->ID, array('activation', 'upgrade'));
					if ($order && $order->is_paid() && $order->get_status() == 'completed') {
						$subscription_link = strip_tags(__('add subscription', 'directorypress-payment-manager'));
						echo '<a href="' . directorypress_dashboardUrl(array('add_subscription' => '1', 'listing_id' => $listing->post->ID)) . '" class="directorypress-btn directorypress-btn-primary directorypress-btn-sm directorypress-dashboard-subscription-btn" title="' . esc_attr($subscription_link) . '"><span class="directorypress-glyphicon directorypress-glyphicon-repeat"></span></a>';
					}
				} elseif ($last_subscription && ($subscription_url = $last_subscription->get_view_order_url())) {
					$subscription_link = strip_tags(__('view subscription', 'directorypress-payment-manager'));
					echo '<a href="' . $subscription_url . '" class="directorypress-btn directorypress-btn-primary directorypress-btn-sm directorypress-dashboard-subscription-btn" title="' . esc_attr($subscription_link) . '"><span class="directorypress-glyphicon directorypress-glyphicon-repeat"></span></a>';
				}
			}
		}
	}
	
	public function create_subscription_onclick($pubic_control) {
		if (class_exists('WC_Subscriptions')) {
			if (directorypress_get_input_value($_GET, 'add_subscription') && ($listing_id = directorypress_get_input_value($_GET, 'listing_id')) && ($listing = directorypress_get_listing($listing_id))) {
				if ($listing->status == 'active' && !$listing->package->package_no_expiry) {
					$last_subscription = directorypress_get_last_subscription_of_listing($listing->post->ID);
				
					if (!$last_subscription || $last_subscription->get_status() == 'trash') {
						$order = directorypress_get_last_order_of_listing($listing_id, array('activation', 'upgrade'));
						if ($order && $order->is_paid() && $order->get_status() == 'completed') {
							$this->cancel_subscriptions($listing);

							$this->create_subscription($order->get_id());
							directorypress_add_notification(__('Subscription was created sucessfully!', 'directorypress-payment-manager'));
							wp_redirect(directorypress_dashboardUrl());
							die();
						}
					} else {
						directorypress_add_notification(sprintf(__('Subscription for this listing already exists. You can manage it <a href="%s">here</a>', 'directorypress-payment-manager'), $last_subscription->get_view_order_url()));
					}
				}
			}
		}
		
		return $pubic_control;
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
	
	function get_listing_by_item_id($item_id) {
		$listing_id = wc_get_order_item_meta($item_id, '_directorypress_listing_id');
		if ($listing_id) {
			$listing = directorypress_get_listing($listing_id);
			if ($listing) {
				return $listing;
			}
		}
	}
	
	function create_subscription($order_id) {
		if (class_exists('WC_Subscriptions')) {
			$order = wc_get_order($order_id);
			$items = $order->get_items();
			foreach ($items AS $item_id=>$item) {
				if (is_a($item, 'WC_Order_Item_Product') && ($product = wc_get_product($item->get_product_id())) && $product->get_type() == 'listing_single') {
					$listing_id = wc_get_order_item_meta($item_id, '_directorypress_listing_id');

					if (($listing = directorypress_get_listing($listing_id)) && !$listing->package->package_no_expiry) {
						$args = array(
								'order_id' => $order_id,
								'customer_id' => $listing->post->post_author,
								'billing_period' => $listing->package->package_duration_unit,
								'billing_interval' => $listing->package->package_duration,
								'start_date' => gmdate('Y-m-d H:i:s'),
								'customer_note' => wcs_get_objects_property($order, 'customer_note'),
						);
						$subscription = wcs_create_subscription($args);
						if (!is_wp_error($subscription)) {
							$subscription_item_id = $subscription->add_product($product);
							
							$payment_gateway = wc_get_payment_gateway_by_order($order);
							$payment_method_meta = apply_filters('woocommerce_subscription_payment_meta', array(), $order);
							if (!empty($payment_gateway) && isset($payment_method_meta[$payment_gateway->id])) {
								$payment_method_meta = $payment_method_meta[$payment_gateway->id];
							}
							
							$subscription->set_payment_method($payment_gateway, $payment_method_meta);
							
							wcs_copy_order_meta($order, $subscription, 'subscription');

							wc_add_order_item_meta($subscription_item_id, '_directorypress_listing_id', $listing_id);
							wc_add_order_item_meta($subscription_item_id, '_directory_action', 'renew');
							$subscription->calculate_totals();

							$dates['next_payment'] = gmdate('Y-m-d H:i:s', $listing->expiration_date);
							$subscription->update_dates($dates);
						}
					}
				}
			}
			WC_Subscriptions_Manager::activate_subscriptions_for_order($order);
		}
	}
	
	function cancel_subscriptions($listing, $notice = '') {
		if (class_exists('WC_Subscriptions')) {
			$orders = directorypress_get_all_orders_of_listing($listing->post->ID);
			if ($orders) {
				foreach ($orders AS $order) {
					$subscriptions = wcs_get_subscriptions_for_order($order);
					foreach ($subscriptions AS $subscription) {
						if ($subscription->has_status('active')) {
							$subscription->cancel_order($notice);
						}
					}
				}
			}
		}
	}
}
?>