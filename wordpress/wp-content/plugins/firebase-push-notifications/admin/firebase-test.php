<?php
/**
 * Firebase Initialization Test
 * Simple test to check Firebase initialization
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized');
}

// Load WordPress
require_once('../../../wp-load.php');

// Load Firebase classes
require_once(FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'includes/class-firebase-manager.php');

echo "<h1>🔍 Firebase Initialization Test</h1>";

// Test 1: Check if FirebaseManager class exists
echo "<h2>Test 1: FirebaseManager Class</h2>";
if (class_exists('FirebaseManager')) {
    echo "✅ FirebaseManager class exists<br>";
} else {
    echo "❌ FirebaseManager class NOT found<br>";
    exit;
}

// Test 2: Get FirebaseManager instance
echo "<h2>Test 2: FirebaseManager Instance</h2>";
try {
    $firebase_manager = FirebaseManager::getInstance();
    echo "✅ FirebaseManager instance created<br>";
} catch (Exception $e) {
    echo "❌ Error creating FirebaseManager instance: " . $e->getMessage() . "<br>";
    exit;
}

// Test 3: Check initialization status
echo "<h2>Test 3: Initialization Status</h2>";
$is_initialized = $firebase_manager->isInitialized();
echo "Firebase initialized: " . ($is_initialized ? "✅ Yes" : "❌ No") . "<br>";

// Test 4: Get detailed status
echo "<h2>Test 4: Detailed Status</h2>";
$status = $firebase_manager->getInitializationStatus();
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Parameter</th><th>Status</th></tr>";
echo "<tr><td>Enabled</td><td>" . ($status['enabled'] ? "✅ Yes" : "❌ No") . "</td></tr>";
echo "<tr><td>Service Account Configured</td><td>" . ($status['service_account_configured'] ? "✅ Yes" : "❌ No") . "</td></tr>";
echo "<tr><td>Service Account Valid</td><td>" . ($status['service_account_valid'] ? "✅ Yes" : "❌ No") . "</td></tr>";
echo "<tr><td>Composer Autoloader Exists</td><td>" . ($status['composer_autoloader_exists'] ? "✅ Yes" : "❌ No") . "</td></tr>";
echo "<tr><td>Firebase Classes Available</td><td>" . ($status['firebase_classes_available'] ? "✅ Yes" : "❌ No") . "</td></tr>";
echo "</table>";

// Test 5: Show details
if (!empty($status['details'])) {
    echo "<h2>Test 5: Details</h2>";
    echo "<ul>";
    foreach ($status['details'] as $detail) {
        echo "<li>" . esc_html($detail) . "</li>";
    }
    echo "</ul>";
}

// Test 6: Check WordPress settings
echo "<h2>Test 6: WordPress Settings</h2>";
global $pacz_settings;
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>firebase_enabled</td><td>" . (isset($pacz_settings['firebase_enabled']) ? ($pacz_settings['firebase_enabled'] ? "✅ Yes" : "❌ No") : "❌ Not set") . "</td></tr>";
echo "<tr><td>firebase_project_id</td><td>" . (isset($pacz_settings['firebase_project_id']) ? esc_html($pacz_settings['firebase_project_id']) : "❌ Not set") . "</td></tr>";
echo "<tr><td>firebase_service_account_json</td><td>" . (isset($pacz_settings['firebase_service_account_json']) && !empty($pacz_settings['firebase_service_account_json']) ? "✅ Set" : "❌ Not set") . "</td></tr>";
echo "</table>";

// Test 7: Check Composer autoloader
echo "<h2>Test 7: Composer Autoloader</h2>";
$autoloaderPath = get_template_directory() . '/vendor/autoload.php';
if (file_exists($autoloaderPath)) {
    echo "✅ Autoloader exists at: " . $autoloaderPath . "<br>";
    
    // Test if we can load it
    try {
        require_once($autoloaderPath);
        echo "✅ Autoloader loaded successfully<br>";
        
        // Test Firebase classes
        if (class_exists('\Kreait\Firebase\Factory')) {
            echo "✅ Firebase Factory class available<br>";
        } else {
            echo "❌ Firebase Factory class NOT available<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error loading autoloader: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Autoloader NOT found at: " . $autoloaderPath . "<br>";
}

echo "<hr>";
echo "<h2>🎯 Summary</h2>";
if ($is_initialized) {
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 4px;'>";
    echo "<strong>✅ Firebase is properly initialized and ready to use!</strong>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px;'>";
    echo "<strong>❌ Firebase is NOT initialized. Check the issues above.</strong>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='" . admin_url('admin.php?page=firebase-configuration') . "'>← Back to Firebase Configuration</a></p>";
?>
