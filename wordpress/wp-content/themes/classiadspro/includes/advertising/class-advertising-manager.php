<?php

/**
 * Advertising Manager Class
 * 
 * Main class for managing advertising functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class ClassiAdsPro_Advertising_Manager
{

    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->init_hooks();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks()
    {
        // Handle advertising action in dashboard
        add_action('wp_loaded', array($this, 'handle_advertising_action'), 999);

        // Add action to handle advertising page display
        add_action('template_redirect', array($this, 'handle_advertising_page_display'), 5);

        // Handle DirectoryPress listing expiration
        add_action('directorypress_listing_expired', array($this, 'on_listing_expired'), 10, 1);

        // Create advertise page on activation
        add_action('after_switch_theme', array($this, 'create_advertise_page'));
    }

    /**
     * Handle advertising action
     */
    public function handle_advertising_action()
    {
        global $directorypress_object;

        // Check if this is an advertising action (either from dashboard or custom page)
        $is_dashboard_action = isset($_GET['directory_action']) && $_GET['directory_action'] === 'advertise_listing';
        $is_custom_page = is_page('advertise-listing') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/advertise-listing/') !== false);

        // Debug logging
        error_log('Advertising action check - Dashboard: ' . ($is_dashboard_action ? 'Yes' : 'No') . ', Custom page: ' . ($is_custom_page ? 'Yes' : 'No'));

        if (!$is_dashboard_action && !$is_custom_page) {
            return;
        }

        if (!is_user_logged_in()) {
            wp_redirect(wp_login_url(get_permalink()));
            exit;
        }

        $listing_id = isset($_GET['listing_id']) ? intval($_GET['listing_id']) : 0;

        if (!$listing_id) {
            wp_redirect(directorypress_dashboardUrl());
            exit;
        }

        // Check if user owns this listing
        if (get_post_field('post_author', $listing_id) != get_current_user_id() && !current_user_can('manage_options')) {
            wp_redirect(directorypress_dashboardUrl());
            exit;
        }

        // Handle form submission
        if (isset($_POST['submit_advertising']) && wp_verify_nonce($_POST['advertising_nonce'], 'submit_advertising')) {
            error_log('Processing advertising form for listing: ' . $listing_id);
            $this->process_advertising_form($listing_id);
            return;
        }

        // Store action for template rendering
        if ($directorypress_object) {
            $directorypress_object->action = 'advertise_listing';
        }
    }

    /**
     * Process advertising form submission
     * 
     * @param int $listing_id Listing ID
     */
    private function process_advertising_form($listing_id)
    {
        error_log('process_advertising_form called for listing: ' . $listing_id);

        $period = isset($_POST['advertising_period']) ? sanitize_text_field($_POST['advertising_period']) : '';

        if (!in_array($period, array('1_day', '3_days', '7_days'))) {
            directorypress_add_notification('Please select advertising period', 'error');
            return;
        }


        // Get product ID
        $product_ids = classiadspro_get_advertising_product_ids();
        $product_id = isset($product_ids[$period]) ? $product_ids[$period] : 0;

        if (!$product_id || !function_exists('wc_get_product')) {
            directorypress_add_notification('Error: advertising product not found', 'error');
            return;
        }

        // Store data in transient for checkout
        set_transient('advertising_data_' . get_current_user_id(), array(
            'listing_id' => $listing_id,
            'period' => $period,
        ), HOUR_IN_SECONDS);

        // Add to cart and redirect to checkout
        if (class_exists('WooCommerce') && function_exists('wc_get_checkout_url') && function_exists('wc_empty_cart')) {
            error_log('Adding to cart - Product ID: ' . $product_id . ', Period: ' . $period);

            wc_empty_cart();

            $cart_item_data = array(
                '_advertising_listing_id' => $listing_id,
                '_advertising_period' => $period,
            );

            $cart_result = WC()->cart->add_to_cart($product_id, 1, 0, array(), $cart_item_data);

            error_log('Cart add result: ' . ($cart_result ? 'Success' : 'Failed'));

            wp_redirect(wc_get_checkout_url());
            exit;
        } else {
            error_log('WooCommerce not available - WooCommerce class: ' . (class_exists('WooCommerce') ? 'Yes' : 'No') . ', wc_get_checkout_url: ' . (function_exists('wc_get_checkout_url') ? 'Yes' : 'No') . ', wc_empty_cart: ' . (function_exists('wc_empty_cart') ? 'Yes' : 'No'));
        }
    }

    /**
     * Handle advertising page display
     * 
     * This method redirects to a custom page template for advertising
     */
    public function handle_advertising_page_display()
    {
        global $directorypress_object;

        // Only redirect if we're in dashboard context, not on custom page
        if (!$directorypress_object || $directorypress_object->action !== 'advertise_listing') {
            return;
        }

        // Check if we're already on the custom page
        if (is_page('advertise-listing') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/advertise-listing/') !== false)) {
            return;
        }

        // Redirect to our custom page template
        $listing_id = isset($_GET['listing_id']) ? intval($_GET['listing_id']) : 0;
        if ($listing_id) {
            $advertise_page_url = add_query_arg('listing_id', $listing_id, home_url('/advertise-listing/'));
            wp_redirect($advertise_page_url);
            exit;
        }
    }

    /**
     * Handle listing expiration
     * 
     * @param int $listing_id Listing ID
     */
    public function on_listing_expired($listing_id)
    {
        $listing_author_id = get_post_field('post_author', $listing_id);
        $listing_title = get_the_title($listing_id);

        if (!$listing_author_id) {
            return;
        }

        // Send push notification
        if (function_exists('fpn_send_push')) {
            $dashboard_url = directorypress_dashboardUrl();

            fpn_send_push($listing_author_id, array(
                'title' => 'Объявление деактивировано',
                'body' => sprintf('Ваше объявление "%s" деактивировано по истечении срока', $listing_title),
                'notification_type' => 'listing_expired',
                'action_url' => $dashboard_url,
            ));
        }
    }
}

// Initialize
ClassiAdsPro_Advertising_Manager::get_instance();
