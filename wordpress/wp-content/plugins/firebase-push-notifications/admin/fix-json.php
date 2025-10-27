<?php
/**
 * Firebase Service Account JSON Fixer
 * Загружает валидный JSON из файла бэкапа
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

echo "<h1>🔧 Firebase Service Account JSON Fixer</h1>";

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized');
}

$backup_file = dirname(dirname(dirname(dirname(__FILE__)))) . '/backups/doska-a50b4-69f034b924dd.json';

echo "<h2>📝 Service Account JSON Fixer</h2>";

if ($_POST && isset($_POST['fix_json'])) {
    // Read backup file
    if (!file_exists($backup_file)) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px;'>";
        echo "❌ Backup file not found: " . esc_html($backup_file);
        echo "</div>";
    } else {
        $json_content = file_get_contents($backup_file);
        $json_data = json_decode($json_content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px;'>";
            echo "❌ Backup file contains invalid JSON: " . json_last_error_msg();
            echo "</div>";
        } else {
            // Validate required fields
            $required_fields = ['type', 'project_id', 'private_key', 'client_email'];
            $missing_fields = array();
            
            foreach ($required_fields as $field) {
                if (!isset($json_data[$field]) || empty($json_data[$field])) {
                    $missing_fields[] = $field;
                }
            }
            
            if (!empty($missing_fields)) {
                echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px;'>";
                echo "❌ Backup file missing required fields: " . implode(', ', $missing_fields);
                echo "</div>";
            } else {
                // Copy backup file to uploads directory
                $uploads_dir = wp_upload_dir();
                $firebase_upload_dir = $uploads_dir['basedir'] . '/firebase-push-notifications';
                
                // Create directory if it doesn't exist
                if (!is_dir($firebase_upload_dir)) {
                    wp_mkdir_p($firebase_upload_dir);
                }
                
                // Generate unique filename
                $filename = 'service-account-backup-' . time() . '.json';
                $file_path = $firebase_upload_dir . '/' . $filename;
                
                // Copy backup file to uploads directory
                if (copy($backup_file, $file_path)) {
                    // Make file readable only by WordPress
                    chmod($file_path, 0600);
                    
                    // Save file path to database
                    update_option('firebase_service_account_file_path', $file_path);
                    
                    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 4px;'>";
                    echo "<strong>✅ Service Account JSON loaded successfully!</strong><br>";
                    echo "Project ID: <code>" . esc_html($json_data['project_id']) . "</code><br>";
                    echo "Client Email: <code>" . esc_html($json_data['client_email']) . "</code><br>";
                    echo "File Path: <code style='font-size: 11px; word-break: break-all;'>" . esc_html($file_path) . "</code>";
                    echo "</div>";
                    
                    // Clear cache if available
                    if (function_exists('wp_cache_flush')) {
                        wp_cache_flush();
                    }
                    
                    echo "<div style='background: #cfe2ff; color: #084298; padding: 15px; border-radius: 4px; margin-top: 15px;'>";
                    echo "ℹ️ <strong>Next steps:</strong><br>";
                    echo "1. Go to <a href='" . admin_url('admin.php?page=firebase-configuration') . "'>Firebase Configuration</a><br>";
                    echo "2. Verify the settings<br>";
                    echo "3. Check that Firebase is initialized";
                    echo "</div>";
                } else {
                    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px;'>";
                    echo "❌ Failed to copy backup file to uploads directory";
                    echo "</div>";
                }
            }
        }
    }
}
?>

<form method="post" style="margin-top: 20px;">
    <div style="background: #fff3cd; color: #664d03; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
        <strong>⚠️ Important!</strong><br>
        This will load the Service Account JSON from the backup file and save it to WordPress options.
    </div>
    
    <table class="form-table">
        <tr>
            <th scope="row">Backup File:</th>
            <td>
                <code><?php echo esc_html($backup_file); ?></code><br>
                <small>
                    <?php 
                    if (file_exists($backup_file)) {
                        echo "✅ File exists (Size: " . filesize($backup_file) . " bytes)";
                    } else {
                        echo "❌ File not found!";
                    }
                    ?>
                </small>
            </td>
        </tr>
        <tr>
            <th scope="row">Action:</th>
            <td>
                <button type="submit" name="fix_json" class="button button-primary">
                    ✅ Load Service Account JSON
                </button>
            </td>
        </tr>
    </table>
</form>

<hr>

<h2>📋 Backup File Content Preview</h2>
<?php
if (file_exists($backup_file)) {
    $json_content = file_get_contents($backup_file);
    $json_data = json_decode($json_content, true);
    
    if ($json_data) {
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">Type:</th>
                <td><code><?php echo esc_html($json_data['type'] ?? 'N/A'); ?></code></td>
            </tr>
            <tr>
                <th scope="row">Project ID:</th>
                <td><code><?php echo esc_html($json_data['project_id'] ?? 'N/A'); ?></code></td>
            </tr>
            <tr>
                <th scope="row">Client Email:</th>
                <td><code><?php echo esc_html($json_data['client_email'] ?? 'N/A'); ?></code></td>
            </tr>
            <tr>
                <th scope="row">Private Key ID:</th>
                <td><code><?php echo esc_html($json_data['private_key_id'] ?? 'N/A'); ?></code></td>
            </tr>
            <tr>
                <th scope="row">Has Private Key:</th>
                <td><?php echo (isset($json_data['private_key']) && !empty($json_data['private_key'])) ? '✅ Yes' : '❌ No'; ?></td>
            </tr>
            <tr>
                <th scope="row">Token URI:</th>
                <td><code><?php echo esc_html($json_data['token_uri'] ?? 'N/A'); ?></code></td>
            </tr>
            <tr>
                <th scope="row">Auth URI:</th>
                <td><code><?php echo esc_html($json_data['auth_uri'] ?? 'N/A'); ?></code></td>
            </tr>
        </table>
        <?php
    } else {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px;'>";
        echo "❌ Backup file contains invalid JSON";
        echo "</div>";
    }
}
?>

<hr>

<p>
    <a href="<?php echo admin_url('admin.php?page=firebase-configuration'); ?>" class="button">
        ← Back to Firebase Configuration
    </a>
</p>
