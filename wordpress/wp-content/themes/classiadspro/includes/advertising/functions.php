<?php
/**
 * Advertising Helper Functions
 * 
 * Helper functions for the advertising system
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get advertising prices from settings
 * 
 * @return array Array with keys: 1_day, 3_days, 7_days
 */
function classiadspro_get_advertising_prices() {
    return array(
        '1_day' => get_option('classiadspro_advertising_price_1_day', 10),
        '3_days' => get_option('classiadspro_advertising_price_3_days', 25),
        '7_days' => get_option('classiadspro_advertising_price_7_days', 50),
    );
}

/**
 * Get advertising duration in days
 * 
 * @param string $period Period key (1_day, 3_days, 7_days)
 * @return int Number of days
 */
function classiadspro_get_advertising_duration($period) {
    $durations = array(
        '1_day' => 1,
        '3_days' => 3,
        '7_days' => 7,
    );
    
    return isset($durations[$period]) ? $durations[$period] : 0;
}

/**
 * Check if listing is currently advertised
 * 
 * @param int $listing_id Listing post ID
 * @return bool True if advertised and not expired
 */
function classiadspro_is_listing_advertised($listing_id) {
    $is_advertised = get_post_meta($listing_id, '_is_advertised', true);
    $end_date = get_post_meta($listing_id, '_advertising_end_date', true);
    
    if (!$is_advertised || !$end_date) {
        return false;
    }
    
    return $end_date >= current_time('timestamp');
}

/**
 * Get advertised categories for a listing
 * 
 * @param int $listing_id Listing post ID
 * @return array Array of category IDs (empty means all categories)
 */
function classiadspro_get_advertising_categories($listing_id) {
    $categories = get_post_meta($listing_id, '_advertising_categories', true);
    return is_array($categories) ? $categories : array();
}

/**
 * Check if listing should be displayed in current category
 * 
 * @param int $listing_id Listing post ID
 * @param int $current_category_id Current category ID
 * @return bool True if should be displayed (always true for advertised listings)
 */
function classiadspro_should_display_in_category($listing_id, $current_category_id) {
    // Advertised listings are displayed in all categories
        return true;
}

/**
 * Activate advertising for a listing
 * 
 * @param int $listing_id Listing post ID
 * @param int $duration_days Duration in days
 * @return bool Success status
 */
function classiadspro_activate_advertising($listing_id, $duration_days) {
    $start_date = current_time('timestamp');
    $end_date = $start_date + ($duration_days * DAY_IN_SECONDS);
    
    update_post_meta($listing_id, '_is_advertised', 1);
    update_post_meta($listing_id, '_advertising_start_date', $start_date);
    update_post_meta($listing_id, '_advertising_end_date', $end_date);
    
    do_action('classiadspro_advertising_activated', $listing_id, $duration_days);
    
    return true;
}

/**
 * Deactivate advertising for a listing
 * 
 * @param int $listing_id Listing post ID
 * @param bool $send_notification Whether to send notification to user
 * @return bool Success status
 */
function classiadspro_deactivate_advertising($listing_id, $send_notification = true) {
    update_post_meta($listing_id, '_is_advertised', 0);
    
    if ($send_notification) {
        $listing_author_id = get_post_field('post_author', $listing_id);
        $listing_title = get_the_title($listing_id);
        
        // Send email notification
        $user = get_user_by('id', $listing_author_id);
        if ($user) {
            $subject = 'Срок рекламирования объявления истёк';
            $message = sprintf(
                'Здравствуйте, %s!<br><br>Срок рекламирования вашего объявления "%s" истёк.<br><br>Вы можете продлить рекламирование в личном кабинете.',
                $user->display_name,
                $listing_title
            );
            
            wp_mail($user->user_email, $subject, $message, array('Content-Type: text/html; charset=UTF-8'));
        }
        
        // Send push notification
        if (function_exists('fpn_send_push')) {
            $dashboard_url = directorypress_dashboardUrl();
            fpn_send_push($listing_author_id, array(
                'title' => 'Реклама завершена',
                'body' => sprintf('Срок рекламирования объявления "%s" истёк', $listing_title),
                'notification_type' => 'advertising_expired',
                'action_url' => $dashboard_url,
            ));
        }
    }
    
    do_action('classiadspro_advertising_deactivated', $listing_id);
    
    return true;
}

/**
 * Debug function to check advertised listings
 * 
 * @return array List of advertised listing IDs
 */
function classiadspro_debug_advertised_listings() {
    $args = array(
        'post_type' => 'dp_listing',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_is_advertised',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => '_advertising_end_date',
                'value' => current_time('timestamp'),
                'compare' => '>'
            )
        ),
        'posts_per_page' => -1,
        'fields' => 'ids'
    );
    
    $advertised_ids = get_posts($args);
    error_log('ClassiAdsPro Debug: Advertised listings: ' . implode(', ', $advertised_ids));
    
    return $advertised_ids;
}

/**
 * Get advertising product IDs
 * 
 * @return array Array with keys: 1_day, 3_days, 7_days
 */
function classiadspro_get_advertising_product_ids() {
    return array(
        '1_day' => get_option('classiadspro_advertising_product_1_day', 0),
        '3_days' => get_option('classiadspro_advertising_product_3_days', 0),
        '7_days' => get_option('classiadspro_advertising_product_7_days', 0),
    );
}

