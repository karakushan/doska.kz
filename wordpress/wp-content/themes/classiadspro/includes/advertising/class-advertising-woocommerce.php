<?php
/**
 * Advertising WooCommerce Integration Class
 * 
 * Handles WooCommerce integration for advertising payments
 */

if (!defined('ABSPATH')) {
    exit;
}

class ClassiAdsPro_Advertising_WooCommerce {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Payment complete (automatic payment completion)
        add_action('woocommerce_payment_complete', array($this, 'on_payment_complete'), 10, 1);
        
        // Order status completed (including manual admin changes)
        add_action('woocommerce_order_status_completed', array($this, 'on_payment_complete'), 10, 1);
        
        // Order status changed (handle all status changes)
        add_action('woocommerce_order_status_changed', array($this, 'on_order_status_changed'), 10, 4);
        
        // Cart item data
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_cart_item_data'), 10, 3);
        
        // Cart item name
        add_filter('woocommerce_cart_item_name', array($this, 'modify_cart_item_name'), 10, 3);
        
        // Order item meta
        add_action('woocommerce_add_order_item_meta', array($this, 'add_order_item_meta'), 10, 3);
        
        // Hide cart item meta from display
        add_filter('woocommerce_hidden_order_itemmeta', array($this, 'hide_order_item_meta'));
    }
    
    /**
     * Handle order status changed
     * 
     * Processes advertising activation when order status changes to completed
     * 
     * @param int $order_id Order ID
     * @param string $status_from Previous status
     * @param string $status_to New status
     * @param WC_Order $order Order object
     */
    public function on_order_status_changed($order_id, $status_from, $status_to, $order) {
        // Only process if status changed to completed and wasn't completed before
        if ($status_to === 'completed' && $status_from !== 'completed') {
            $this->process_advertising_activation($order_id);
        }
    }
    
    /**
     * Handle payment complete and order status completed
     * 
     * This method is called both when payment is automatically completed
     * and when order status is manually changed to completed in admin
     * 
     * @param int $order_id Order ID
     */
    public function on_payment_complete($order_id) {
        $this->process_advertising_activation($order_id);
    }
    
    /**
     * Process advertising activation for order
     * 
     * Handles advertising activation with extension logic for existing active advertising
     * 
     * @param int $order_id Order ID
     */
    private function process_advertising_activation($order_id) {
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return;
        }
        
        // Check if advertising was already processed for this order to avoid duplicates
        $advertising_processed = $order->get_meta('_advertising_processed', true);
        if ($advertising_processed) {
            return;
        }
        
        foreach ($order->get_items() as $item_id => $item) {
            $listing_id = wc_get_order_item_meta($item_id, '_advertising_listing_id', true);
            $period = wc_get_order_item_meta($item_id, '_advertising_period', true);
            
            if (!$listing_id || !$period) {
                continue;
            }
            
            // Get duration
            $duration = classiadspro_get_advertising_duration($period);
            
            if (!$duration) {
                continue;
            }
            
            // Check if listing already has active advertising
            $existing_end_date = get_post_meta($listing_id, '_advertising_end_date', true);
            $is_advertised = get_post_meta($listing_id, '_is_advertised', true);
            $current_timestamp = current_time('timestamp');
            
            // If listing has active advertising and end date is in the future, extend it
            if ($is_advertised && $existing_end_date && $existing_end_date >= $current_timestamp) {
                // Add new duration to existing end date
                $new_end_date = $existing_end_date + ($duration * DAY_IN_SECONDS);
                
                // Update end date while keeping original start date and advertising status
                update_post_meta($listing_id, '_advertising_end_date', $new_end_date);
                update_post_meta($listing_id, '_is_advertised', 1);
                
                // Add order note
                $order->add_order_note(sprintf(
                    'Рекламирование продлено для объявления #%d на %d дней. Новый срок окончания: %s',
                    $listing_id,
                    $duration,
                    date_i18n('d.m.Y H:i', $new_end_date)
                ));
            } else {
                // Activate new advertising or reactivate expired
                classiadspro_activate_advertising($listing_id, $duration);
                
                // Add order note
                $order->add_order_note(sprintf(
                    'Рекламирование активировано для объявления #%d на %d дней',
                    $listing_id,
                    $duration
                ));
            }
        }
        
        // Mark advertising as processed for this order
        $order->update_meta_data('_advertising_processed', true);
        $order->save();
    }
    
    /**
     * Add cart item data
     * 
     * @param array $cart_item_data Cart item data
     * @param int $product_id Product ID
     * @param int $variation_id Variation ID
     * @return array Modified cart item data
     */
    public function add_cart_item_data($cart_item_data, $product_id, $variation_id) {
        // Check if this is an advertising product
        $product_ids = classiadspro_get_advertising_product_ids();
        
        if (!in_array($product_id, $product_ids)) {
            return $cart_item_data;
        }
        
        // Get data from transient
        $user_id = get_current_user_id();
        $advertising_data = get_transient('advertising_data_' . $user_id);
        
        if ($advertising_data && is_array($advertising_data)) {
            $cart_item_data['_advertising_listing_id'] = $advertising_data['listing_id'];
            $cart_item_data['_advertising_period'] = $advertising_data['period'];
            
            // Delete transient
            delete_transient('advertising_data_' . $user_id);
        }
        
        return $cart_item_data;
    }
    
    /**
     * Modify cart item name
     * 
     * @param string $name Item name
     * @param array $cart_item Cart item
     * @param string $cart_item_key Cart item key
     * @return string Modified name
     */
    public function modify_cart_item_name($name, $cart_item, $cart_item_key) {
        if (isset($cart_item['_advertising_listing_id'])) {
            $listing_id = $cart_item['_advertising_listing_id'];
            $listing_title = get_the_title($listing_id);
            
            $name .= '<br><small>Объявление: ' . esc_html($listing_title) . '</small>';
        }
        
        return $name;
    }
    
    /**
     * Add order item meta
     * 
     * @param int $item_id Item ID
     * @param array $values Cart item values
     * @param string $cart_item_key Cart item key
     */
    public function add_order_item_meta($item_id, $values, $cart_item_key) {
        if (isset($values['_advertising_listing_id'])) {
            wc_add_order_item_meta($item_id, '_advertising_listing_id', $values['_advertising_listing_id']);
        }
        
        if (isset($values['_advertising_period'])) {
            wc_add_order_item_meta($item_id, '_advertising_period', $values['_advertising_period']);
        }
    }
    
    /**
     * Hide order item meta from display
     * 
     * @param array $hidden_meta Hidden meta keys
     * @return array Modified hidden meta
     */
    public function hide_order_item_meta($hidden_meta) {
        $hidden_meta[] = '_advertising_listing_id';
        $hidden_meta[] = '_advertising_period';
        
        return $hidden_meta;
    }
}

// Initialize
ClassiAdsPro_Advertising_WooCommerce::get_instance();

