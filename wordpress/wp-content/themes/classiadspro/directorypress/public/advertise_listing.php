<?php

/**
 * Advertise Listing Template
 * 
 * Template for choosing advertising options
 * This template is used within the DirectoryPress dashboard structure
 */

global $directorypress_object;

$listing_id = isset($_GET['listing_id']) ? intval($_GET['listing_id']) : 0;
error_log('Advertise listing page loaded. Listing ID: ' . $listing_id);

if (!$listing_id) {
    error_log('No listing ID provided, redirecting to dashboard');
    wp_redirect(directorypress_dashboardUrl());
    exit;
}

$listing = directorypress_get_listing($listing_id);

if (!$listing) {
    error_log('Listing not found for ID: ' . $listing_id);
    wp_redirect(directorypress_dashboardUrl());
    exit;
}

error_log('Listing found: ' . $listing->title());

// Handle form submission
if (isset($_POST['submit_advertising'])) {
    error_log('Form submitted - POST data: ' . print_r($_POST, true));

    if (!wp_verify_nonce($_POST['advertising_nonce'], 'submit_advertising')) {
        error_log('Nonce verification failed');
        $error_url = add_query_arg('error', urlencode('Security check failed'), $_SERVER['REQUEST_URI']);
        wp_redirect($error_url);
        exit;
    }

    error_log('Processing advertising form for listing: ' . $listing_id);

    $period = isset($_POST['advertising_period']) ? sanitize_text_field($_POST['advertising_period']) : '';
    error_log('Selected period: ' . $period);

    if (!in_array($period, array('1_day', '3_days', '7_days'))) {
        error_log('Invalid period selected: ' . $period);
        $error_url = add_query_arg('error', urlencode('Please select advertising period'), $_SERVER['REQUEST_URI']);
        wp_redirect($error_url);
        exit;
    }

    // Get product ID
    $product_ids = classiadspro_get_advertising_product_ids();
    error_log('Available product IDs: ' . print_r($product_ids, true));

    $product_id = isset($product_ids[$period]) ? $product_ids[$period] : 0;
    error_log('Selected product ID for period ' . $period . ': ' . $product_id);

    if (!$product_id || !function_exists('wc_get_product')) {
        error_log('Product ID not found or WooCommerce not available. Product ID: ' . $product_id . ', WC available: ' . (function_exists('wc_get_product') ? 'yes' : 'no'));
        $error_url = add_query_arg('error', urlencode('Error: advertising product not found'), $_SERVER['REQUEST_URI']);
        wp_redirect($error_url);
        exit;
    }

    // Check if product exists
    $product = wc_get_product($product_id);
    if (!$product) {
        error_log('Product with ID ' . $product_id . ' does not exist');
        $error_url = add_query_arg('error', urlencode('Error: advertising product does not exist'), $_SERVER['REQUEST_URI']);
        wp_redirect($error_url);
        exit;
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
        $error_url = add_query_arg('error', urlencode('WooCommerce not available'), $_SERVER['REQUEST_URI']);
        wp_redirect($error_url);
        exit;
    }
}

$prices = classiadspro_get_advertising_prices();
?>

<div class="advertise-listing-wrapper">
    <div class="advertise-listing-container">
        <div class="advertise-listing-header">
            <h2><?php esc_html_e('Advertise Listing', 'classiadspro'); ?></h2>
            <p class="listing-title"><?php echo esc_html($listing->title()); ?></p>

            <?php if ($listing->status != 'active' || $listing->post->post_status != 'publish'): ?>
                <div class="advertising-warning">
                    <div class="warning-icon">⚠️</div>
                    <div class="warning-text">
                        <strong>Warning!</strong> Your listing is not yet active or published.
                        Advertising will be activated automatically after approval and publication of the listing.
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="advertise-listing-content">
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo esc_html(urldecode($_GET['error'])); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" class="advertising-form">
                <?php wp_nonce_field('submit_advertising', 'advertising_nonce'); ?>

                <div class="advertising-section">
                    <h3><?php esc_html_e('Choose Advertising Period', 'classiadspro'); ?></h3>

                    <div class="advertising-periods">
                        <div class="period-option">
                            <label class="period-label">
                                <input type="radio" name="advertising_period" value="1_day" required>
                                <div class="period-card">
                                    <div class="period-duration">1 day</div>
                                    <div class="period-price">$<?php echo number_format($prices['1_day'], 2, '.', ''); ?></div>
                                </div>
                            </label>
                        </div>

                        <div class="period-option">
                            <label class="period-label">
                                <input type="radio" name="advertising_period" value="3_days" required checked>
                                <div class="period-card period-popular">
                                    <span class="popular-badge">Popular</span>
                                    <div class="period-duration">3 days</div>
                                    <div class="period-price">$<?php echo number_format($prices['3_days'], 2, '.', ''); ?></div>
                                </div>
                            </label>
                        </div>

                        <div class="period-option">
                            <label class="period-label">
                                <input type="radio" name="advertising_period" value="7_days" required>
                                <div class="period-card">
                                    <div class="period-duration">7 days</div>
                                    <div class="period-price">$<?php echo number_format($prices['7_days'], 2, '.', ''); ?></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="advertising-actions">
                    <button type="submit" name="submit_advertising" class="btn btn-primary btn-advertising">
                        <?php esc_html_e('Proceed to Payment', 'classiadspro'); ?>
                    </button>

                    <a href="<?php echo esc_url(directorypress_dashboardUrl()); ?>" class="btn btn-secondary">
                        <?php esc_html_e('Cancel', 'classiadspro'); ?>
                    </a>
                </div>

                <!-- Debug information -->
                <div style="margin-top: 20px; padding: 10px; background: #f0f0f0; font-size: 12px;">
                    <strong>Debug Info:</strong><br>
                    Form Action: <?php echo esc_html($_SERVER['REQUEST_URI']); ?><br>
                    Listing ID: <?php echo esc_html($listing_id); ?><br>
                    POST Data: <?php echo isset($_POST) ? 'Available' : 'Not Available'; ?><br>
                    Nonce: <?php echo wp_create_nonce('submit_advertising'); ?><br>
                    Product IDs: <?php
                                    $debug_product_ids = classiadspro_get_advertising_product_ids();
                                    echo esc_html(print_r($debug_product_ids, true));
                                    ?><br>
                    Prices: <?php
                            $debug_prices = classiadspro_get_advertising_prices();
                            echo esc_html(print_r($debug_prices, true));
                            ?>
                </div>
            </form>
        </div>

        <div class="advertising-info">
            <h3><?php esc_html_e('Advertising Benefits', 'classiadspro'); ?></h3>
            <ul>
                <li>✓ Your listing will be shown in the special "Featured" block</li>
                <li>✓ Display in the first 6 positions in your category</li>
                <li>✓ Special "Advertised" badge to attract attention</li>
                <li>✓ Increased views and responses to your listing</li>
            </ul>
        </div>
    </div>
</div>