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
    error_log('Firebase: Hook triggered - message_id: ' . $message_id . ' | message type: ' . gettype($message) . ' | inserted_message type: ' . gettype($inserted_message));
    
    try {
        // Validate inputs
        if (!$message_id || !$inserted_message || !is_object($inserted_message)) {
            error_log('Firebase: Invalid message data - message_id: ' . $message_id . ' | inserted_message is object: ' . (is_object($inserted_message) ? 'yes' : 'no'));
            return;
        }
        
        // Get sender information from inserted message (more reliable than get_current_user_id)
        $sender_id = intval($inserted_message->post_author);
        
        if (!$sender_id) {
            error_log('Firebase: No sender ID found for message ' . $message_id);
            return;
        }
        
        $sender = get_user_by('id', $sender_id);
        if (!$sender) {
            error_log('Firebase: Sender user not found - ID: ' . $sender_id);
            return;
        }
        
        $sender_name = $sender->display_name ? $sender->display_name : $sender->user_login;
        
        // Get participants to determine recipients
        $recipient_ids = array();
        
        // First, try to get recipients from message_to_id (most reliable for new messages)
        if (!empty($message['message_to_id'])) {
            if (is_array($message['message_to_id'])) {
                foreach ($message['message_to_id'] as $recipient) {
                    $recipient_id = intval($recipient);
                    if ($recipient_id && $recipient_id != $sender_id) {
                        $recipient_ids[] = $recipient_id;
                    }
                }
            } else {
                $recipient_id = intval($message['message_to_id']);
                if ($recipient_id && $recipient_id != $sender_id) {
                    $recipient_ids[] = $recipient_id;
                }
            }
        }
        
        // Fallback: get participants from message meta using function
        if (empty($recipient_ids) && function_exists('difp_get_participants')) {
            $participants = difp_get_participants($message_id);
            if (!empty($participants) && is_array($participants)) {
                foreach ($participants as $participant_id) {
                    $participant_id = intval($participant_id);
                    if ($participant_id && $participant_id != $sender_id) {
                        $recipient_ids[] = $participant_id;
                    }
                }
            }
        }
        
        // Second fallback: get participants directly from post meta
        if (empty($recipient_ids)) {
            $participants_meta = get_post_meta($message_id, '_difp_participants');
            if (!empty($participants_meta) && is_array($participants_meta)) {
                foreach ($participants_meta as $participant_id) {
                    $participant_id = intval($participant_id);
                    if ($participant_id && $participant_id != $sender_id) {
                        $recipient_ids[] = $participant_id;
                    }
                }
            }
        }
        
        // For replies, also check parent message participants
        if (empty($recipient_ids) && !empty($inserted_message->post_parent)) {
            $parent_participants = get_post_meta($inserted_message->post_parent, '_difp_participants');
            if (!empty($parent_participants) && is_array($parent_participants)) {
                foreach ($parent_participants as $participant_id) {
                    $participant_id = intval($participant_id);
                    if ($participant_id && $participant_id != $sender_id) {
                        $recipient_ids[] = $participant_id;
                    }
                }
            }
        }
        
        // Remove duplicates
        $recipient_ids = array_unique($recipient_ids);
        
        if (empty($recipient_ids)) {
            error_log('Firebase: No recipients found for message ' . $message_id . ' from sender ' . $sender_id . 
                     ' | message_to_id: ' . (isset($message['message_to_id']) ? print_r($message['message_to_id'], true) : 'not set') .
                     ' | post_parent: ' . ($inserted_message->post_parent ? $inserted_message->post_parent : 'none'));
            return;
        }
        
        error_log('Firebase: Found ' . count($recipient_ids) . ' recipient(s) for message ' . $message_id . ': ' . implode(', ', $recipient_ids));
        
        // Get message content
        $message_content = isset($message['message_content']) ? $message['message_content'] : $inserted_message->post_content;
        $message_preview = !empty($message_content) ? wp_trim_words(strip_tags($message_content), 20) : 'You have received a new message';
        
        // Build action URL with message ID
        $action_url = home_url('/my-dashboard/?directory_action=messages&difpaction=viewmessage&difp_id=' . intval($message_id));
        
        // Send notification to each recipient
        foreach ($recipient_ids as $recipient_id) {
            $notification_data = array(
                'title' => 'New message from ' . $sender_name,
                'body' => $message_preview,
                'icon' => get_site_icon_url(),
                'badge' => get_site_icon_url(),
                'notification_type' => 'new_message',
                'action_url' => $action_url,
            );
            
            if (function_exists('fpn_send_push')) {
                if (fpn_send_push($recipient_id, $notification_data)) {
                    error_log('Firebase: New message notification sent to user ' . $recipient_id . ' from ' . $sender_name . ' (Message ID: ' . $message_id . ')');
                } else {
                    error_log('Firebase: Failed to send notification to user ' . $recipient_id . ' (Message ID: ' . $message_id . ')');
                }
            } else {
                error_log('Firebase: Function fpn_send_push does not exist');
            }
        }
    } catch (Exception $e) {
        error_log('Firebase: Error sending message notification - ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
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
        
        if (function_exists('fpn_send_push')) {
            if (fpn_send_push($author_id, $notification_data)) {
                error_log('Firebase: Listing expired notification sent to user ' . $author_id);
            }
        }
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
        
        if (function_exists('fpn_send_push')) {
            if (fpn_send_push($author_id, $notification_data)) {
                error_log('Firebase: Listing deactivated notification sent to user ' . $author_id);
            }
        }
    } catch (Exception $e) {
        error_log('Firebase: Error sending listing deactivated notification - ' . $e->getMessage());
    }
}
add_action('directorypress_listing_deactivated', 'classiadspro_firebase_listing_deactivated', 10, 1);
