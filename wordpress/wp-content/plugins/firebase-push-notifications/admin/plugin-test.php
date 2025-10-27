<?php
/**
 * Firebase Plugin Activation Test
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<h1>🔍 Firebase Plugin Activation Test</h1>";

// Test 1: Check if plugin is active
echo "<h2>Test 1: Plugin Activation Status</h2>";
if (is_plugin_active('firebase-push-notifications/firebase-push-notifications.php')) {
    echo "✅ Plugin is active<br>";
} else {
    echo "❌ Plugin is NOT active<br>";
    echo "Available plugins:<br>";
    $plugins = get_plugins();
    foreach ($plugins as $plugin_file => $plugin_data) {
        if (strpos($plugin_file, 'firebase') !== false) {
            echo "- " . $plugin_data['Name'] . " (" . $plugin_file . ")<br>";
        }
    }
}

// Test 2: Check if constants are defined
echo "<h2>Test 2: Plugin Constants</h2>";
if (defined('FIREBASE_PUSH_NOTIFICATIONS_VERSION')) {
    echo "✅ FIREBASE_PUSH_NOTIFICATIONS_VERSION: " . FIREBASE_PUSH_NOTIFICATIONS_VERSION . "<br>";
} else {
    echo "❌ FIREBASE_PUSH_NOTIFICATIONS_VERSION not defined<br>";
}

if (defined('FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR')) {
    echo "✅ FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR: " . FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . "<br>";
} else {
    echo "❌ FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR not defined<br>";
}

// Test 3: Check if classes exist
echo "<h2>Test 3: Plugin Classes</h2>";
if (class_exists('Firebase_Push_Notifications_Plugin')) {
    echo "✅ Firebase_Push_Notifications_Plugin class exists<br>";
} else {
    echo "❌ Firebase_Push_Notifications_Plugin class NOT found<br>";
}

if (class_exists('Firebase_Push_Notifications')) {
    echo "✅ Firebase_Push_Notifications class exists<br>";
} else {
    echo "❌ Firebase_Push_Notifications class NOT found<br>";
}

if (class_exists('FirebaseManager')) {
    echo "✅ FirebaseManager class exists<br>";
} else {
    echo "❌ FirebaseManager class NOT found<br>";
}

// Test 4: Check autoloader
echo "<h2>Test 4: Composer Autoloader</h2>";
$autoloader_path = FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'vendor/autoload.php';
if (file_exists($autoloader_path)) {
    echo "✅ Autoloader exists at: " . $autoloader_path . "<br>";
    
    try {
        require_once($autoloader_path);
        echo "✅ Autoloader loaded successfully<br>";
        
        if (class_exists('\Kreait\Firebase\Factory')) {
            echo "✅ Firebase Factory class available<br>";
        } else {
            echo "❌ Firebase Factory class NOT available<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error loading autoloader: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Autoloader NOT found at: " . $autoloader_path . "<br>";
}

// Test 5: Check WordPress options
echo "<h2>Test 5: WordPress Options</h2>";
$firebase_enabled = get_option('firebase_enabled', false);
echo "firebase_enabled: " . ($firebase_enabled ? "✅ Yes" : "❌ No") . "<br>";

$project_id = get_option('firebase_project_id', '');
echo "firebase_project_id: " . ($project_id ? "✅ " . $project_id : "❌ Not set") . "<br>";

$service_account = get_option('firebase_service_account_json', '');
echo "firebase_service_account_json: " . (!empty($service_account) ? "✅ Set" : "❌ Not set") . "<br>";

// Test 6: Try to get FirebaseManager instance
echo "<h2>Test 6: FirebaseManager Instance</h2>";
if (class_exists('FirebaseManager')) {
    try {
        $firebase_manager = FirebaseManager::getInstance();
        echo "✅ FirebaseManager instance created<br>";
        
        $is_initialized = $firebase_manager->isInitialized();
        echo "Firebase initialized: " . ($is_initialized ? "✅ Yes" : "❌ No") . "<br>";
        
        if (!$is_initialized) {
            $status = $firebase_manager->getInitializationStatus();
            echo "Initialization status:<br>";
            echo "- Enabled: " . ($status['enabled'] ? "✅ Yes" : "❌ No") . "<br>";
            echo "- Service Account Configured: " . ($status['service_account_configured'] ? "✅ Yes" : "❌ No") . "<br>";
            echo "- Service Account Valid: " . ($status['service_account_valid'] ? "✅ Yes" : "❌ No") . "<br>";
            echo "- Composer Autoloader Exists: " . ($status['composer_autoloader_exists'] ? "✅ Yes" : "❌ No") . "<br>";
            echo "- Firebase Classes Available: " . ($status['firebase_classes_available'] ? "✅ Yes" : "❌ No") . "<br>";
            
            if (!empty($status['details'])) {
                echo "Details:<br>";
                foreach ($status['details'] as $detail) {
                    echo "- " . esc_html($detail) . "<br>";
                }
            }
        }
    } catch (Exception $e) {
        echo "❌ Error creating FirebaseManager instance: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ FirebaseManager class not available<br>";
}

echo "<hr>";
echo "<h2>🎯 Summary</h2>";
if (is_plugin_active('firebase-push-notifications/firebase-push-notifications.php')) {
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 4px;'>";
    echo "<strong>✅ Plugin is active and loaded!</strong>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px;'>";
    echo "<strong>❌ Plugin is NOT active!</strong><br>";
    echo "Please activate the plugin in WordPress admin.";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='" . admin_url('plugins.php') . "'>← Go to Plugins</a></p>";
?>
