<?php
/**
 * Notification Handler Class
 * Handles WordPress events and triggers Firebase notifications
 */

if (!defined('ABSPATH')) {
    exit;
}

class FirebaseNotificationHandler {
    
    private static $instance = null;
    private $firebase_manager = null;
    
    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->firebase_manager = FirebaseManager::getInstance();
        $this->initHooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function initHooks() {
        // Message notifications
        add_action('difp_save_message', array($this, 'handleNewMessage'), 10, 2);
        
        // Listing expiration notifications
        add_action('directorypress_listing_expired', array($this, 'handleListingExpired'), 10, 1);
        
        // Listing deactivation notifications
        add_action('directorypress_listing_deactivated', array($this, 'handleListingDeactivated'), 10, 1);
        
        // Custom hooks for DirectoryPress events
        add_action('directorypress_listing_status_changed', array($this, 'handleListingStatusChanged'), 10, 3);
        
        // Payment expiration notifications
        add_action('directorypress_payment_expired', array($this, 'handlePaymentExpired'), 10, 1);
    }
    
    /**
     * Handle new message notification
     * 
     * @param int $message_id Message ID
     * @param WP_Post $message Message post object
     */
    public function handleNewMessage($message_id, $message) {
        if (!$this->firebase_manager->isInitialized()) {
            return;
        }
        
        // Get message participants
        $participants = get_post_meta($message_id, '_difp_participants', true);
        if (empty($participants) || !is_array($participants)) {
            return;
        }
        
        $sender_id = $message->post_author;
        
        // Send notification to all participants except sender
        foreach ($participants as $participant_id) {
            if ($participant_id != $sender_id) {
                $sender = get_userdata($sender_id);
                $sender_name = $sender ? $sender->display_name : __('Someone', 'firebase-push-notifications');
                
                $title = sprintf(__('New message from %s', 'firebase-push-notifications'), $sender_name);
                $body = wp_trim_words($message->post_content, 20);
                
                $data = array(
                    'message_id' => $message_id,
                    'sender_id' => $sender_id,
                    'sender_name' => $sender_name,
                    'action_url' => home_url('/my-dashboard/?directory_action=messages')
                );
                
                $this->firebase_manager->sendNotificationToUser(
                    $participant_id,
                    $title,
                    $body,
                    $data,
                    'message'
                );
            }
        }
    }
    
    /**
     * Handle listing expired notification
     * 
     * @param int $listing_id Listing ID
     */
    public function handleListingExpired($listing_id) {
        if (!$this->firebase_manager->isInitialized()) {
            return;
        }
        
        $listing = get_post($listing_id);
        if (!$listing || $listing->post_type !== 'directorypress_listing') {
            return;
        }
        
        $owner_id = $listing->post_author;
        $listing_title = $listing->post_title;
        
        $title = __('Your listing has expired', 'firebase-push-notifications');
        $body = sprintf(__('Your listing "%s" has expired and is no longer visible to users.', 'firebase-push-notifications'), $listing_title);
        
        $data = array(
            'listing_id' => $listing_id,
            'listing_title' => $listing_title,
            'action_url' => home_url('/my-dashboard/?directory_action=edit_listing&listing_id=' . $listing_id)
        );
        
        $this->firebase_manager->sendNotificationToUser(
            $owner_id,
            $title,
            $body,
            $data,
            'ad_expiration'
        );
    }
    
    /**
     * Handle listing deactivated notification
     * 
     * @param int $listing_id Listing ID
     */
    public function handleListingDeactivated($listing_id) {
        if (!$this->firebase_manager->isInitialized()) {
            return;
        }
        
        $listing = get_post($listing_id);
        if (!$listing || $listing->post_type !== 'directorypress_listing') {
            return;
        }
        
        $owner_id = $listing->post_author;
        $listing_title = $listing->post_title;
        
        $title = __('Your listing has been deactivated', 'firebase-push-notifications');
        $body = sprintf(__('Your listing "%s" has been deactivated by administrators.', 'firebase-push-notifications'), $listing_title);
        
        $data = array(
            'listing_id' => $listing_id,
            'listing_title' => $listing_title,
            'action_url' => home_url('/my-dashboard/?directory_action=edit_listing&listing_id=' . $listing_id)
        );
        
        $this->firebase_manager->sendNotificationToUser(
            $owner_id,
            $title,
            $body,
            $data,
            'ad_deactivation'
        );
    }
    
    /**
     * Handle listing status changed notification
     * 
     * @param int $listing_id Listing ID
     * @param string $old_status Old status
     * @param string $new_status New status
     */
    public function handleListingStatusChanged($listing_id, $old_status, $new_status) {
        if (!$this->firebase_manager->isInitialized()) {
            return;
        }
        
        // Only handle specific status changes
        if ($new_status === 'expired' && $old_status !== 'expired') {
            $this->handleListingExpired($listing_id);
        } elseif ($new_status === 'draft' && $old_status === 'publish') {
            $this->handleListingDeactivated($listing_id);
        }
    }
    
    /**
     * Handle payment expired notification
     * 
     * @param int $listing_id Listing ID
     */
    public function handlePaymentExpired($listing_id) {
        if (!$this->firebase_manager->isInitialized()) {
            return;
        }
        
        $listing = get_post($listing_id);
        if (!$listing || $listing->post_type !== 'directorypress_listing') {
            return;
        }
        
        $owner_id = $listing->post_author;
        $listing_title = $listing->post_title;
        
        $title = __('Payment expired for your listing', 'firebase-push-notifications');
        $body = sprintf(__('Payment for your listing "%s" has expired. Please renew to keep it active.', 'firebase-push-notifications'), $listing_title);
        
        $data = array(
            'listing_id' => $listing_id,
            'listing_title' => $listing_title,
            'action_url' => home_url('/my-dashboard/?directory_action=renew_listing&listing_id=' . $listing_id)
        );
        
        $this->firebase_manager->sendNotificationToUser(
            $owner_id,
            $title,
            $body,
            $data,
            'ad_expiration'
        );
    }
    
    /**
     * Send custom notification
     * 
     * @param int $user_id User ID
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data
     * @param string $notification_type Notification type
     * @return bool Success status
     */
    public function sendCustomNotification($user_id, $title, $body, $data = array(), $notification_type = 'general') {
        if (!$this->firebase_manager->isInitialized()) {
            return false;
        }
        
        return $this->firebase_manager->sendNotificationToUser(
            $user_id,
            $title,
            $body,
            $data,
            $notification_type
        );
    }
    
    /**
     * Send notification to all users
     * 
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data
     * @param string $notification_type Notification type
     * @return int Number of users notified
     */
    public function sendNotificationToAllUsers($title, $body, $data = array(), $notification_type = 'general') {
        if (!$this->firebase_manager->isInitialized()) {
            return 0;
        }
        
        $users = get_users(array('fields' => 'ID'));
        $success_count = 0;
        
        foreach ($users as $user_id) {
            if ($this->firebase_manager->sendNotificationToUser($user_id, $title, $body, $data, $notification_type)) {
                $success_count++;
            }
        }
        
        return $success_count;
    }
    
    /**
     * Send notification to users with specific role
     * 
     * @param string $role User role
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data
     * @param string $notification_type Notification type
     * @return int Number of users notified
     */
    public function sendNotificationToRole($role, $title, $body, $data = array(), $notification_type = 'general') {
        if (!$this->firebase_manager->isInitialized()) {
            return 0;
        }
        
        $users = get_users(array('role' => $role, 'fields' => 'ID'));
        $success_count = 0;
        
        foreach ($users as $user_id) {
            if ($this->firebase_manager->sendNotificationToUser($user_id, $title, $body, $data, $notification_type)) {
                $success_count++;
            }
        }
        
        return $success_count;
    }
    
    /**
     * Send notification to specific user
     * 
     * @param int $user_id User ID
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data
     * @param string $notification_type Notification type
     * @return bool Success status
     */
    public function sendNotificationToUser($user_id, $title, $body, $data = array(), $notification_type = 'general') {
        if (!$this->firebase_manager->isInitialized()) {
            return false;
        }
        
        return $this->firebase_manager->sendNotificationToUser($user_id, $title, $body, $data, $notification_type);
    }
}
