<?php
/**
 * Firebase Manager Class
 * Handles Firebase Cloud Messaging operations
 */

if (!defined('ABSPATH')) {
    exit;
}

class FirebaseManager {
    
    private static $instance = null;
    private $messaging = null;
    private $isInitialized = false;
    
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
        $this->init();
    }
    
    /**
     * Initialize Firebase
     */
    private function init() {
        try {
            // Check if Firebase is enabled
            $firebase_enabled = get_option('firebase_enabled', false);
            if (!$firebase_enabled) {
                error_log('Firebase Push Notifications: Firebase is disabled');
                return;
            }
            
            $service_account_json = null;
            $service_account_file_path = get_option('firebase_service_account_file_path', '');
            
            // Try to load from file first (new method)
            if (!empty($service_account_file_path)) {
                if (file_exists($service_account_file_path) && is_readable($service_account_file_path)) {
                    $service_account_json = file_get_contents($service_account_file_path);
                    if ($service_account_json === false) {
                        error_log('Firebase Push Notifications: Failed to read service account file: ' . $service_account_file_path);
                        $service_account_json = null;
                    }
                } else {
                    error_log('Firebase Push Notifications: Service account file not found or not readable: ' . $service_account_file_path);
                }
            }
            
            // Fallback to legacy database storage if file method failed
            if ($service_account_json === null || empty($service_account_json)) {
                error_log('Firebase Push Notifications: File method failed, trying legacy database storage');
                $service_account_json = get_option('firebase_service_account_json', '');
            }
            
            // Check if service account JSON is provided
            if (empty($service_account_json)) {
                error_log('Firebase Push Notifications: Service account JSON not configured');
                return;
            }
            
            // Parse service account JSON
            $serviceAccount = json_decode($service_account_json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log('Firebase Push Notifications: Invalid service account JSON - ' . json_last_error_msg());
                return;
            }
            
            // Validate required fields in service account
            $requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
            foreach ($requiredFields as $field) {
                if (!isset($serviceAccount[$field]) || empty($serviceAccount[$field])) {
                    error_log('Firebase Push Notifications: Missing required field in service account: ' . $field);
                    return;
                }
            }
            
            // Check if Composer autoloader exists
            $autoloaderPath = FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'vendor/autoload.php';
            if (!file_exists($autoloaderPath)) {
                error_log('Firebase Push Notifications: Composer autoloader not found at: ' . $autoloaderPath);
                return;
            }
            
            // Initialize Firebase
            $factory = new \Kreait\Firebase\Factory();
            $factory = $factory->withServiceAccount($serviceAccount);
            
            $this->messaging = $factory->createMessaging();
            $this->isInitialized = true;
            
            if (!empty($service_account_file_path) && file_exists($service_account_file_path)) {
                error_log('Firebase Push Notifications: Successfully initialized from file: ' . $service_account_file_path);
            } else {
                error_log('Firebase Push Notifications: Successfully initialized from database storage');
            }
            
        } catch (Exception $e) {
            error_log('Firebase Push Notifications initialization error: ' . $e->getMessage());
            error_log('Firebase Push Notifications stack trace: ' . $e->getTraceAsString());
        } catch (Error $e) {
            error_log('Firebase Push Notifications fatal error: ' . $e->getMessage());
            error_log('Firebase Push Notifications stack trace: ' . $e->getTraceAsString());
        }
    }
    
    /**
     * Check if Firebase is initialized
     */
    public function isInitialized() {
        return $this->isInitialized;
    }
    
    /**
     * Get detailed initialization status
     */
    public function getInitializationStatus() {
        $status = array(
            'enabled' => false,
            'service_account_configured' => false,
            'service_account_valid' => false,
            'composer_autoloader_exists' => false,
            'firebase_classes_available' => false,
            'initialization_error' => null,
            'details' => array()
        );
        
        // Check if Firebase is enabled
        $firebase_enabled = get_option('firebase_enabled', false);
        if ($firebase_enabled) {
            $status['enabled'] = true;
        } else {
            $status['details'][] = 'Firebase is disabled in settings';
            return $status;
        }
        
        // Check service account file path and JSON
        $service_account_file_path = get_option('firebase_service_account_file_path', '');
        $service_account_json = null;
        $source = 'none';
        
        // Try file first
        if (!empty($service_account_file_path)) {
            if (file_exists($service_account_file_path) && is_readable($service_account_file_path)) {
                $service_account_json = file_get_contents($service_account_file_path);
                if ($service_account_json !== false) {
                    $source = 'file';
                    $status['details'][] = 'Service account loaded from file: ' . $service_account_file_path;
                } else {
                    $status['details'][] = 'Failed to read service account file: ' . $service_account_file_path;
                }
            } else {
                $status['details'][] = 'Service account file not found or not readable: ' . $service_account_file_path;
            }
        }
        
        // Fallback to database storage
        if ($service_account_json === null || empty($service_account_json)) {
            $service_account_json = get_option('firebase_service_account_json', '');
            if (!empty($service_account_json)) {
                $source = 'database';
                $status['details'][] = 'Service account loaded from database (legacy method)';
            }
        }
        
        if (!empty($service_account_json)) {
            $status['service_account_configured'] = true;
            
            // Validate JSON
            $serviceAccount = json_decode($service_account_json, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $status['service_account_valid'] = true;
                
                // Check required fields
                $requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
                foreach ($requiredFields as $field) {
                    if (!isset($serviceAccount[$field]) || empty($serviceAccount[$field])) {
                        $status['details'][] = 'Missing required field in service account: ' . $field;
                        $status['service_account_valid'] = false;
                    }
                }
            } else {
                $status['details'][] = 'Invalid JSON format: ' . json_last_error_msg();
                $status['service_account_valid'] = false;
            }
        } else {
            $status['details'][] = 'Service account JSON not configured in file or database';
        }
        
        // Check Composer autoloader
        $autoloaderPath = FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'vendor/autoload.php';
        if (file_exists($autoloaderPath)) {
            $status['composer_autoloader_exists'] = true;
        } else {
            $status['details'][] = 'Composer autoloader not found at: ' . $autoloaderPath;
        }
        
        // Check Firebase classes
        if (class_exists('\Kreait\Firebase\Factory')) {
            $status['firebase_classes_available'] = true;
        } else {
            $status['details'][] = 'Firebase PHP SDK classes not available';
        }
        
        // Check if initialized
        if ($this->isInitialized) {
            $status['details'][] = 'Firebase successfully initialized';
        } else {
            $status['details'][] = 'Firebase not initialized';
        }
        
        return $status;
    }
    
    /**
     * Send notification to user
     * 
     * @param int $user_id User ID
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data
     * @param string $notification_type Type of notification
     * @return bool Success status
     */
    public function sendNotificationToUser($user_id, $title, $body, $data = array(), $notification_type = 'general') {
        if (!$this->isInitialized()) {
            return false;
        }
        
        // Check if user has notifications enabled for this type
        if (!$this->isNotificationEnabled($user_id, $notification_type)) {
            return false;
        }
        
        // Get user's FCM tokens
        $tokens = get_user_meta($user_id, '_fcm_device_tokens', true);
        if (empty($tokens) || !is_array($tokens)) {
            return false;
        }
        
        $success_count = 0;
        $failed_tokens = array();
        
        foreach ($tokens as $token) {
            try {
                $message = \Kreait\Firebase\Messaging\CloudMessage::new()
                    ->withNotification(\Kreait\Firebase\Messaging\Notification::create($title, $body))
                    ->withData(array_merge($data, array(
                        'notification_type' => $notification_type,
                        'user_id' => $user_id,
                        'timestamp' => time()
                    )))
                    ->toToken($token);
                
                $result = $this->messaging->send($message);
                $success_count++;
                
                // Log successful notification
                $this->logNotification($user_id, $notification_type, $title, $body, $data, 'sent');
                
            } catch (\Kreait\Firebase\Exception\MessagingException $e) {
                $failed_tokens[] = $token;
                error_log('Firebase Push Notifications: Failed to send to token ' . $token . ' - ' . $e->getMessage());
                
                // Log failed notification
                $this->logNotification($user_id, $notification_type, $title, $body, $data, 'failed');
            }
        }
        
        // Remove failed tokens
        if (!empty($failed_tokens)) {
            $this->removeFailedTokens($user_id, $failed_tokens);
        }
        
        return $success_count > 0;
    }
    
    /**
     * Send notification to topic
     * 
     * @param string $topic Topic name
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data
     * @return bool Success status
     */
    public function sendNotificationToTopic($topic, $title, $body, $data = array()) {
        if (!$this->isInitialized()) {
            return false;
        }
        
        try {
            $message = \Kreait\Firebase\Messaging\CloudMessage::new()
                ->withNotification(\Kreait\Firebase\Messaging\Notification::create($title, $body))
                ->withData(array_merge($data, array(
                    'topic' => $topic,
                    'timestamp' => time()
                )))
                ->toTopic($topic);
            
            $result = $this->messaging->send($message);
            return true;
            
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            error_log('Firebase Push Notifications: Failed to send to topic ' . $topic . ' - ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if notification type is enabled for user
     * 
     * @param int $user_id User ID
     * @param string $notification_type Notification type
     * @return bool
     */
    private function isNotificationEnabled($user_id, $notification_type) {
        $preferences = get_user_meta($user_id, '_notification_preferences', true);
        
        if (empty($preferences) || !is_array($preferences)) {
            // Default to enabled if no preferences set
            return true;
        }
        
        switch ($notification_type) {
            case 'message':
                return isset($preferences['messages']) ? $preferences['messages'] : true;
            case 'ad_expiration':
                return isset($preferences['ad_expiration']) ? $preferences['ad_expiration'] : true;
            case 'ad_deactivation':
                return isset($preferences['ad_deactivation']) ? $preferences['ad_deactivation'] : true;
            default:
                return true;
        }
    }
    
    /**
     * Log notification
     * 
     * @param int $user_id User ID
     * @param string $notification_type Notification type
     * @param string $title Title
     * @param string $body Body
     * @param array $data Data
     * @param string $status Status
     */
    private function logNotification($user_id, $notification_type, $title, $body, $data, $status) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'firebase_notifications_log';
        
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'notification_type' => $notification_type,
                'title' => $title,
                'body' => $body,
                'data' => json_encode($data),
                'status' => $status
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s')
        );
    }
    
    /**
     * Remove failed tokens from user meta
     * 
     * @param int $user_id User ID
     * @param array $failed_tokens Array of failed tokens
     */
    private function removeFailedTokens($user_id, $failed_tokens) {
        $tokens = get_user_meta($user_id, '_fcm_device_tokens', true);
        if (is_array($tokens)) {
            $tokens = array_diff($tokens, $failed_tokens);
            update_user_meta($user_id, '_fcm_device_tokens', $tokens);
        }
    }
    
    /**
     * Subscribe user to topic
     * 
     * @param int $user_id User ID
     * @param string $topic Topic name
     * @return bool Success status
     */
    public function subscribeUserToTopic($user_id, $topic) {
        if (!$this->isInitialized()) {
            return false;
        }
        
        $tokens = get_user_meta($user_id, '_fcm_device_tokens', true);
        if (empty($tokens) || !is_array($tokens)) {
            return false;
        }
        
        try {
            $result = $this->messaging->subscribeToTopic($topic, $tokens);
            return true;
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            error_log('Firebase Push Notifications: Failed to subscribe to topic ' . $topic . ' - ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Unsubscribe user from topic
     * 
     * @param int $user_id User ID
     * @param string $topic Topic name
     * @return bool Success status
     */
    public function unsubscribeUserFromTopic($user_id, $topic) {
        if (!$this->isInitialized()) {
            return false;
        }
        
        $tokens = get_user_meta($user_id, '_fcm_device_tokens', true);
        if (empty($tokens) || !is_array($tokens)) {
            return false;
        }
        
        try {
            $result = $this->messaging->unsubscribeFromTopic($topic, $tokens);
            return true;
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            error_log('Firebase Push Notifications: Failed to unsubscribe from topic ' . $topic . ' - ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get notification statistics
     * 
     * @param int $user_id User ID (optional)
     * @param string $notification_type Notification type (optional)
     * @param int $days Number of days to look back (default: 30)
     * @return array Statistics
     */
    public function getNotificationStats($user_id = null, $notification_type = null, $days = 30) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'firebase_notifications_log';
        
        $where_conditions = array();
        $where_values = array();
        
        if ($user_id) {
            $where_conditions[] = 'user_id = %d';
            $where_values[] = $user_id;
        }
        
        if ($notification_type) {
            $where_conditions[] = 'notification_type = %s';
            $where_values[] = $notification_type;
        }
        
        $where_conditions[] = 'sent_at >= DATE_SUB(NOW(), INTERVAL %d DAY)';
        $where_values[] = $days;
        
        $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
        
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent,
                    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
                    notification_type
                FROM $table_name 
                $where_clause
                GROUP BY notification_type";
        
        if (!empty($where_values)) {
            $results = $wpdb->get_results($wpdb->prepare($sql, $where_values));
        } else {
            $results = $wpdb->get_results($sql);
        }
        
        return $results;
    }
}
