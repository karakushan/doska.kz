<?php
/**
 * Advertising Cron Class
 * 
 * Handles cron tasks for advertising system
 */

if (!defined('ABSPATH')) {
    exit;
}

class ClassiAdsPro_Advertising_Cron {
    
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
        // Register cron schedule
        add_filter('cron_schedules', array($this, 'add_cron_schedules'));
        
        // Schedule cron event
        add_action('wp', array($this, 'schedule_cron_events'));
        
        // Cron hook
        add_action('classiadspro_check_expired_advertising', array($this, 'check_expired_advertising'));
        
        // Deactivation hook
        add_action('switch_theme', array($this, 'deactivate_cron_events'));
    }
    
    /**
     * Add custom cron schedules
     * 
     * @param array $schedules Existing schedules
     * @return array Modified schedules
     */
    public function add_cron_schedules($schedules) {
        if (!isset($schedules['classiadspro_hourly'])) {
            $schedules['classiadspro_hourly'] = array(
                'interval' => HOUR_IN_SECONDS,
                'display' => __('ClassiAdsPro Hourly', 'classiadspro')
            );
        }
        
        return $schedules;
    }
    
    /**
     * Schedule cron events
     */
    public function schedule_cron_events() {
        if (!wp_next_scheduled('classiadspro_check_expired_advertising')) {
            wp_schedule_event(time(), 'classiadspro_hourly', 'classiadspro_check_expired_advertising');
        }
    }
    
    /**
     * Deactivate cron events
     */
    public function deactivate_cron_events() {
        $timestamp = wp_next_scheduled('classiadspro_check_expired_advertising');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'classiadspro_check_expired_advertising');
        }
    }
    
    /**
     * Check and deactivate expired advertising
     */
    public function check_expired_advertising() {
        global $wpdb;
        
        // Query expired advertised listings
        $current_time = current_time('timestamp');
        
        $query = $wpdb->prepare("
            SELECT p.ID 
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_is_advertised'
            INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_advertising_end_date'
            WHERE p.post_type = %s
            AND pm1.meta_value = '1'
            AND CAST(pm2.meta_value AS UNSIGNED) < %d
        ", DIRECTORYPRESS_POST_TYPE, $current_time);
        
        $expired_listings = $wpdb->get_col($query);
        
        if (empty($expired_listings)) {
            return;
        }
        
        // Deactivate each expired listing
        foreach ($expired_listings as $listing_id) {
            $this->deactivate_expired_listing($listing_id);
        }
        
        // Log
        error_log(sprintf('ClassiAdsPro Advertising: Deactivated %d expired listings', count($expired_listings)));
    }
    
    /**
     * Deactivate expired listing
     * 
     * @param int $listing_id Listing ID
     */
    private function deactivate_expired_listing($listing_id) {
        // Deactivate advertising
        classiadspro_deactivate_advertising($listing_id, true);
        
        // Log activity
        do_action('classiadspro_advertising_expired', $listing_id);
    }
}

// Initialize
ClassiAdsPro_Advertising_Cron::get_instance();

