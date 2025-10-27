<?php
/**
 * Firebase Push Notifications Hooks
 * 
 * Handle push notifications for various DirectoryPress events
 * Uses fpn_send_push() helper function from Firebase plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Send push notification when new message is sent
 * Hook: difp_action_message_after_send
 * Called from directorypress-frontend-messages plugin
 */
function classiadspro_firebase_message_sent($message_id, $message, $inserted_message) {
    try {
        // Get sender information
        $sender_id = get_current_user_id();
        
        if (!$sender_id) {
            return;
        }
        
        $sender = get_user_by('id', $sender_id);
        $sender_name = $sender ? $sender->display_name : 'Someone';
        
        // Check if this is a reply to a parent message
        $recipient_id = 0;
        $is_reply = isset($message['difp_action']) && $message['difp_action'] === 'reply';
        
        if ($is_reply && isset($message['difp_parent_id'])) {
            // Get the parent message to find the recipient
            $parent_message_id = intval($message['difp_parent_id']);
            $parent_message = get_post($parent_message_id);
            
            if ($parent_message) {
                // The recipient is the author of the parent message
                $recipient_id = intval($parent_message->post_author);
            }
        } else {
            // For new messages, get recipient from message_to_id
            $recipient_id = isset($message['message_to_id']) ? intval($message['message_to_id']) : 0;
        }
        
        if (!$recipient_id) {
            return;
        }
        
        $message_content = isset($message['message_content']) ? $message['message_content'] : '';
        $message_preview = !empty($message_content) ? substr($message_content, 0, 100) : 'You have received a new message';
        
        // Build action URL with message ID
        $action_url = home_url('/my-dashboard/?directory_action=messages&difpaction=viewmessage&difp_id=' . intval($message_id));
        
        $notification_data = array(
            'title' => 'New message from ' . $sender_name,
            'body' => $message_preview,
            'icon' => get_site_icon_url(),
            'badge' => get_site_icon_url(),
            'notification_type' => 'new_message',
            'action_url' => $action_url,
        );
        
        fpn_send_push($recipient_id, $notification_data);
        error_log('Firebase: New message notification sent to user ' . $recipient_id . ' from ' . $sender_name . ' (Message ID: ' . $message_id . ', Reply: ' . ($is_reply ? 'yes' : 'no') . ')');
    } catch (Exception $e) {
        error_log('Firebase: Error sending message notification - ' . $e->getMessage());
    }
}
add_action('difp_action_message_after_send', 'classiadspro_firebase_message_sent', 10, 3);

/**
 * Send push notification for listing expiration
 * Hook: directorypress_listing_expired
 */
function classiadspro_firebase_listing_expired($listing_id) {
    try {
        $listing = get_post($listing_id);
        if (!$listing) {
            return;
        }
        
        $author_id = $listing->post_author;
        $listing_title = get_the_title($listing_id);
        
        $notification_data = array(
            'title' => 'Listing Expired',
            'body' => 'Your listing "' . $listing_title . '" has expired',
            'icon' => get_site_icon_url(),
            'badge' => get_site_icon_url(),
            'notification_type' => 'listing_expired',
            'action_url' => get_edit_post_link($listing_id, 'url'),
        );
        
        fpn_send_push($author_id, $notification_data);
        error_log('Firebase: Listing expired notification sent to user ' . $author_id);
    } catch (Exception $e) {
        error_log('Firebase: Error sending listing expired notification - ' . $e->getMessage());
    }
}
add_action('directorypress_listing_expired', 'classiadspro_firebase_listing_expired', 10, 1);

/**
 * Send push notification for listing deactivation
 * Hook: directorypress_listing_deactivated
 */
function classiadspro_firebase_listing_deactivated($listing_id) {
    try {
        $listing = get_post($listing_id);
        if (!$listing) {
            return;
        }
        
        $author_id = $listing->post_author;
        $listing_title = get_the_title($listing_id);
        
        $notification_data = array(
            'title' => 'Listing Deactivated',
            'body' => 'Your listing "' . $listing_title . '" has been deactivated',
            'icon' => get_site_icon_url(),
            'badge' => get_site_icon_url(),
            'notification_type' => 'listing_deactivated',
            'action_url' => get_edit_post_link($listing_id, 'url'),
        );
        
        fpn_send_push($author_id, $notification_data);
        error_log('Firebase: Listing deactivated notification sent to user ' . $author_id);
    } catch (Exception $e) {
        error_log('Firebase: Error sending listing deactivated notification - ' . $e->getMessage());
    }
}
add_action('directorypress_listing_deactivated', 'classiadspro_firebase_listing_deactivated', 10, 1);
