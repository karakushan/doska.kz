<?php
/**
 * Firebase Debug Test
 * Детальное тестирование инициализации Firebase
 */

// Find WordPress root
$wp_root = dirname(__FILE__);
for ($i = 0; $i < 5; $i++) {
    $wp_root = dirname($wp_root);
    if (file_exists($wp_root . '/wp-load.php')) {
        break;
    }
}

if (!file_exists($wp_root . '/wp-load.php')) {
    die('Could not find wp-load.php');
}

require_once($wp_root . '/wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized');
}

echo "<h1>🔍 Firebase Detailed Debug Test</h1>";

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get settings
$file_path = get_option('firebase_service_account_file_path', '');
$json_db = get_option('firebase_service_account_json', '');

echo "<h2>Step 1: Load JSON</h2>";
$service_account_json = null;

if (!empty($file_path)) {
    echo "Trying to load from file: <code>" . esc_html($file_path) . "</code><br>";
    if (file_exists($file_path)) {
        echo "✅ File exists<br>";
        if (is_readable($file_path)) {
            echo "✅ File is readable<br>";
            $service_account_json = file_get_contents($file_path);
            if ($service_account_json !== false) {
                echo "✅ File read successfully (" . strlen($service_account_json) . " bytes)<br>";
            } else {
                echo "❌ Failed to read file<br>";
            }
        } else {
            echo "❌ File is not readable<br>";
        }
    } else {
        echo "❌ File does not exist<br>";
    }
}

// Try fallback
if ($service_account_json === null || empty($service_account_json)) {
    echo "Trying fallback from database<br>";
    $service_account_json = $json_db;
    if (!empty($service_account_json)) {
        echo "✅ JSON loaded from database (" . strlen($service_account_json) . " bytes)<br>";
    } else {
        echo "❌ No JSON in database<br>";
    }
}

echo "<h2>Step 2: Parse JSON</h2>";
if (!empty($service_account_json)) {
    $serviceAccount = json_decode($service_account_json, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✅ JSON parsed successfully<br>";
        echo "Project ID: " . (isset($serviceAccount['project_id']) ? esc_html($serviceAccount['project_id']) : "❌ Missing") . "<br>";
        echo "Client Email: " . (isset($serviceAccount['client_email']) ? esc_html($serviceAccount['client_email']) : "❌ Missing") . "<br>";
        echo "Has Private Key: " . (isset($serviceAccount['private_key']) && !empty($serviceAccount['private_key']) ? "✅ Yes" : "❌ No") . "<br>";
    } else {
        echo "❌ JSON parse error: " . json_last_error_msg() . "<br>";
        exit;
    }
} else {
    echo "❌ No JSON to parse<br>";
    exit;
}

echo "<h2>Step 3: Check Autoloader</h2>";
$autoloader = FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'vendor/autoload.php';
if (file_exists($autoloader)) {
    echo "✅ Autoloader exists at: <code>" . esc_html($autoloader) . "</code><br>";
    
    try {
        require_once($autoloader);
        echo "✅ Autoloader loaded<br>";
    } catch (Exception $e) {
        echo "❌ Error loading autoloader: " . esc_html($e->getMessage()) . "<br>";
        exit;
    }
} else {
    echo "❌ Autoloader not found<br>";
    exit;
}

echo "<h2>Step 4: Check Firebase Classes</h2>";
if (class_exists('\Kreait\Firebase\Factory')) {
    echo "✅ Firebase Factory class available<br>";
} else {
    echo "❌ Firebase Factory class not available<br>";
    exit;
}

echo "<h2>Step 5: Initialize Firebase</h2>";
try {
    echo "Creating Firebase Factory...<br>";
    $factory = new \Kreait\Firebase\Factory();
    echo "✅ Factory created<br>";
    
    echo "Setting service account...<br>";
    $factory = $factory->withServiceAccount($serviceAccount);
    echo "✅ Service account set<br>";
    
    echo "Creating Messaging service...<br>";
    $messaging = $factory->createMessaging();
    echo "✅ Messaging service obtained<br>";
    
    echo "<div style='background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; margin-top: 20px;'>";
    echo "<h2 style='margin-top: 0;'>✅ Firebase Successfully Initialized!</h2>";
    echo "<p>All components working correctly.</p>";
    echo "</div>";
    
} catch (\Kreait\Firebase\Exception\ServiceAccount\InvalidKey $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px;'>";
    echo "<h2>❌ Invalid Service Account Key</h2>";
    echo "Error: " . esc_html($e->getMessage()) . "<br>";
    echo "Check that your private_key is valid and complete.<br>";
    echo "</div>";
} catch (\Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px;'>";
    echo "<h2>❌ Firebase Initialization Error</h2>";
    echo "Error Type: " . get_class($e) . "<br>";
    echo "Message: " . esc_html($e->getMessage()) . "<br>";
    echo "File: " . esc_html($e->getFile()) . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto;'>";
    echo esc_html($e->getTraceAsString());
    echo "</pre>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='" . admin_url('admin.php?page=firebase-diagnostics') . "' class='button'>← Back to Diagnostics</a></p>";
?>
