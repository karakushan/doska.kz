<?php
/**
 * Firebase Configuration Checker
 * Simple tool to check and configure Firebase settings
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized');
}

// Handle form submission
if (isset($_POST['save_firebase_config'])) {
    $firebase_enabled = isset($_POST['firebase_enabled']) ? true : false;
    $project_id = sanitize_text_field($_POST['firebase_project_id']);
    $api_key = sanitize_text_field($_POST['firebase_api_key']);
    $messaging_sender_id = sanitize_text_field($_POST['firebase_messaging_sender_id']);
    $app_id = sanitize_text_field($_POST['firebase_app_id']);
    $vapid_key = sanitize_text_field($_POST['firebase_vapid_key']);
    
    // Handle Service Account JSON file upload
    $service_account_file_path = get_option('firebase_service_account_file_path', '');
    
    // Check if file was uploaded
    if (isset($_FILES['firebase_service_account_file']) && $_FILES['firebase_service_account_file']['error'] === UPLOAD_ERR_OK) {
        $uploaded_file = $_FILES['firebase_service_account_file'];
        
        // Validate file type
        $file_extension = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
        if ($file_extension === 'json') {
            // Read file content for validation
            $file_content = file_get_contents($uploaded_file['tmp_name']);
            
            // Validate JSON
            $json_data = json_decode($file_content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Create uploads directory for Firebase files
                $uploads_dir = wp_upload_dir();
                $firebase_upload_dir = $uploads_dir['basedir'] . '/firebase-push-notifications';
                
                // Create directory if it doesn't exist
                if (!is_dir($firebase_upload_dir)) {
                    wp_mkdir_p($firebase_upload_dir);
                }
                
                // Generate unique filename
                $filename = 'service-account-' . time() . '.json';
                $file_path = $firebase_upload_dir . '/' . $filename;
                
                // Move uploaded file to uploads directory
                if (move_uploaded_file($uploaded_file['tmp_name'], $file_path)) {
                    // Make file readable only by WordPress
                    chmod($file_path, 0600);
                    
                    $service_account_file_path = $file_path;
                    $success_message = 'Service Account JSON файл загружен успешно!';
                } else {
                    $error_message = 'Ошибка: Не удалось загрузить файл на сервер.';
                }
            } else {
                $error_message = 'Ошибка: Загруженный файл содержит невалидный JSON. ' . json_last_error_msg();
            }
        } else {
            $error_message = 'Ошибка: Файл должен иметь расширение .json';
        }
    }
    
    // Update settings
    update_option('firebase_enabled', $firebase_enabled);
    update_option('firebase_project_id', $project_id);
    update_option('firebase_api_key', $api_key);
    update_option('firebase_messaging_sender_id', $messaging_sender_id);
    update_option('firebase_app_id', $app_id);
    update_option('firebase_vapid_key', $vapid_key);
    update_option('firebase_service_account_file_path', $service_account_file_path);
    
    if (!isset($error_message)) {
        $success_message = 'Настройки Firebase сохранены успешно!';
    }
}

// Get current settings
$firebase_enabled = get_option('firebase_enabled', false);
$project_id = get_option('firebase_project_id', 'doska-a50b4');
$api_key = get_option('firebase_api_key', 'AIzaSyDC0ovBMM_FJEYhFZjgQXAW6-ljtEQRWjo');
$messaging_sender_id = get_option('firebase_messaging_sender_id', '927038207069');
$app_id = get_option('firebase_app_id', '1:927038207069:web:38e3755d76e75b379c49b4');
$vapid_key = get_option('firebase_vapid_key', '');
$service_account_file_path = get_option('firebase_service_account_file_path', '');

// Check Firebase status
$firebase_manager_exists = class_exists('FirebaseManager');
$firebase_initialized = false;
$firebase_status = null;
if ($firebase_manager_exists) {
    $firebase_manager = FirebaseManager::getInstance();
    $firebase_initialized = $firebase_manager->isInitialized();
    $firebase_status = $firebase_manager->getInitializationStatus();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Настройка Firebase</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        textarea { height: 150px; resize: vertical; }
        button { background: #007cba; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #005a87; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .status-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .status-table th, .status-table td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #e0e0e0;
        }
        .status-table th { 
            background: #f8f9fa; 
            font-weight: bold;
            color: #333;
        }
        .status-table tr:hover { 
            background: #f8f9fa; 
        }
        .switch { position: relative; display: inline-block; width: 60px; height: 34px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; }
        .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; }
        input:checked + .slider { background-color: #2196F3; }
        input:checked + .slider:before { transform: translateX(26px); }
        .slider.round { border-radius: 34px; }
        .slider.round:before { border-radius: 50%; }
        
        /* Diagnostic details styles */
        .diagnostic-details { margin: 20px 0; }
        .diagnostic-info { 
            background: #e7f3ff; 
            color: #004085; 
            padding: 15px; 
            border-radius: 4px; 
            margin: 15px 0;
            border-left: 4px solid #007cba;
        }
        .diagnostic-info ul { margin: 10px 0; padding-left: 20px; }
        .diagnostic-info li { margin: 5px 0; }
        .diagnostic-info code { 
            background: #fff; 
            padding: 2px 6px; 
            border-radius: 3px; 
            font-family: monospace;
        }
        
        /* File upload styles */
        input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 2px dashed #007cba;
            border-radius: 4px;
            background: #f8f9fa;
            cursor: pointer;
        }
        
        input[type="file"]:hover {
            border-color: #005a87;
            background: #e7f3ff;
        }
        
        .file-upload-info {
            background: #e7f3ff;
            color: #004085;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid #007cba;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Настройка Firebase Push Notifications</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="success">✅ <?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="error">❌ <?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <div class="warning">
            <h3>⚠️ Важно!</h3>
            <p>Для работы Firebase Push Notifications необходимо:</p>
            <ol>
                <li><strong>Включить Firebase</strong> в настройках ниже</li>
                <li><strong>Загрузить Service Account JSON</strong> из Firebase Console</li>
                <li><strong>Настроить VAPID ключ</strong> для web push</li>
            </ol>
        </div>
        
        <h3>📊 Текущий статус Firebase</h3>
        <table class="status-table">
            <tr>
                <th>Параметр</th>
                <th>Статус</th>
                <th>Значение</th>
            </tr>
            <tr>
                <td>Firebase включен</td>
                <td><?php echo $firebase_enabled ? '✅ Да' : '❌ Нет'; ?></td>
                <td><?php echo $firebase_enabled ? 'Включен' : 'Отключен'; ?></td>
            </tr>
            <tr>
                <td>Project ID</td>
                <td><?php echo !empty($project_id) ? '✅ Настроен' : '❌ Не настроен'; ?></td>
                <td><?php echo esc_html($project_id ?: 'Не указан'); ?></td>
            </tr>
            <tr>
                <td>API Key</td>
                <td><?php echo !empty($api_key) ? '✅ Настроен' : '❌ Не настроен'; ?></td>
                <td><?php echo esc_html($api_key ?: 'Не указан'); ?></td>
            </tr>
            <tr>
                <td>Messaging Sender ID</td>
                <td><?php echo !empty($messaging_sender_id) ? '✅ Настроен' : '❌ Не настроен'; ?></td>
                <td><?php echo esc_html($messaging_sender_id ?: 'Не указан'); ?></td>
            </tr>
            <tr>
                <td>App ID</td>
                <td><?php echo !empty($app_id) ? '✅ Настроен' : '❌ Не настроен'; ?></td>
                <td><?php echo esc_html($app_id ?: 'Не указан'); ?></td>
            </tr>
            <tr>
                <td>VAPID Key</td>
                <td><?php echo !empty($vapid_key) ? '✅ Настроен' : '❌ Не настроен'; ?></td>
                <td><?php echo !empty($vapid_key) ? 'Настроен' : 'Не настроен'; ?></td>
            </tr>
            <tr>
                <td>Service Account JSON</td>
                <td><?php echo !empty($service_account_file_path) ? '✅ Загружен' : '❌ Не загружен'; ?></td>
                <td><?php echo !empty($service_account_file_path) ? 'Загружен' : 'Не загружен'; ?></td>
            </tr>
                    <tr>
                        <td>Firebase инициализирован</td>
                        <td><?php echo $firebase_initialized ? '✅ Да' : '❌ Нет'; ?></td>
                        <td><?php echo $firebase_initialized ? 'Готов к работе' : 'Не инициализирован'; ?></td>
                    </tr>
                </table>
                
                <?php if ($firebase_status && !$firebase_initialized): ?>
                    <h4>🔍 Детальная диагностика</h4>
                    <div class="diagnostic-details">
                        <?php if (!$firebase_status['enabled']): ?>
                            <div class="diagnostic-warning">
                                <strong>❌ Firebase отключен</strong><br>
                                Включите Firebase в настройках ниже.
                            </div>
                        <?php elseif (!$firebase_status['service_account_configured']): ?>
                            <div class="diagnostic-warning">
                                <strong>❌ Service Account JSON не настроен</strong><br>
                                Загрузите Service Account JSON из Firebase Console.
                            </div>
                        <?php elseif (!$firebase_status['service_account_valid']): ?>
                            <div class="diagnostic-warning">
                                <strong>❌ Service Account JSON невалидный</strong><br>
                                Проверьте формат JSON и обязательные поля.<br><br>
                                <a href="<?php echo admin_url('admin.php?page=firebase-fix-json'); ?>" class="button button-primary" target="_blank">
                                    🔧 Загрузить JSON из бэкапа
                                </a>
                            </div>
                        <?php elseif (!$firebase_status['composer_autoloader_exists']): ?>
                            <div class="diagnostic-warning">
                                <strong>❌ Composer autoloader не найден</strong><br>
                                Установите зависимости: <code>composer install</code>
                            </div>
                        <?php elseif (!$firebase_status['firebase_classes_available']): ?>
                            <div class="diagnostic-warning">
                                <strong>❌ Firebase PHP SDK не установлен</strong><br>
                                Установите: <code>composer require kreait/firebase-php</code>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($firebase_status['details'])): ?>
                            <div class="diagnostic-info">
                                <strong>📋 Детали:</strong>
                                <ul>
                                    <?php foreach ($firebase_status['details'] as $detail): ?>
                                        <li><?php echo esc_html($detail); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
        
        <hr style="margin: 40px 0;">
        
        <h3>⚙️ Настройка Firebase</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="firebase_enabled" value="1" <?php checked($firebase_enabled); ?>>
                    Включить Firebase Push Notifications
                </label>
            </div>
            
            <div class="form-group">
                <label for="firebase_project_id">Firebase Project ID:</label>
                <input type="text" name="firebase_project_id" id="firebase_project_id" 
                       value="<?php echo esc_attr($project_id); ?>" required>
                <small>Пример: doska-a50b4</small>
            </div>
            
            <div class="form-group">
                <label for="firebase_api_key">Firebase API Key:</label>
                <input type="text" name="firebase_api_key" id="firebase_api_key" 
                       value="<?php echo esc_attr($api_key); ?>" required>
                <small>Найдите в Firebase Console → Project Settings → General</small>
            </div>
            
            <div class="form-group">
                <label for="firebase_messaging_sender_id">Messaging Sender ID:</label>
                <input type="text" name="firebase_messaging_sender_id" id="firebase_messaging_sender_id" 
                       value="<?php echo esc_attr($messaging_sender_id); ?>" required>
                <small>Найдите в Firebase Console → Project Settings → Cloud Messaging</small>
            </div>
            
            <div class="form-group">
                <label for="firebase_app_id">Firebase App ID:</label>
                <input type="text" name="firebase_app_id" id="firebase_app_id" 
                       value="<?php echo esc_attr($app_id); ?>" required>
                <small>Найдите в Firebase Console → Project Settings → General</small>
            </div>
            
            <div class="form-group">
                <label for="firebase_vapid_key">VAPID Key:</label>
                <input type="text" name="firebase_vapid_key" id="firebase_vapid_key" 
                       value="<?php echo esc_attr($vapid_key); ?>">
                <small>Найдите в Firebase Console → Project Settings → Cloud Messaging → Web configuration</small>
            </div>
            
            <div class="form-group">
                <label for="firebase_service_account_json">Service Account JSON:</label>
                
                <!-- File Upload Option -->
                <div style="margin-bottom: 15px;">
                    <label for="firebase_service_account_file" style="font-weight: bold; color: #007cba;">
                        📁 Загрузите JSON файл:
                    </label>
                    <input type="file" name="firebase_service_account_file" id="firebase_service_account_file" 
                           accept=".json" style="margin-top: 5px;">
                    <small style="display: block; margin-top: 5px; color: #666;">
                        Выберите .json файл, скачанный из Firebase Console
                    </small>
                    
                    <?php if (!empty($service_account_file_path)): ?>
                        <div style="margin-top: 10px; padding: 10px; background: #d4edda; border-radius: 4px; color: #155724;">
                            <strong>✅ Текущий файл:</strong><br>
                            <code style="font-size: 12px; word-break: break-all;"><?php echo esc_html($service_account_file_path); ?></code>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Preview of JSON content -->
                <?php if (!empty($service_account_file_path) && file_exists($service_account_file_path)): ?>
                    <div style="margin-bottom: 15px;">
                        <label for="firebase_service_account_preview" style="font-weight: bold;">
                            📝 Содержимое загруженного файла (только для просмотра):
                        </label>
                        <textarea id="firebase_service_account_preview" 
                                  readonly style="height: 200px; background: #f5f5f5; cursor: not-allowed;"><?php 
                            $content = file_get_contents($service_account_file_path);
                            echo esc_textarea($content); 
                        ?></textarea>
                    </div>
                <?php endif; ?>
                
                <small>
                    <strong>Как получить:</strong><br>
                    1. Перейдите в Firebase Console → Project Settings → Service Accounts<br>
                    2. Нажмите "Generate new private key"<br>
                    3. Скачайте JSON файл и загрузите его выше<br>
                    4. Файл будет сохранен в защищенной папке на сервере
                </small>
            </div>
            
            <button type="submit" name="save_firebase_config">
                💾 Сохранить настройки Firebase
            </button>
        </form>
        
        <hr style="margin: 40px 0;">
        
        <h3>🔗 Полезные ссылки</h3>
        <ul>
            <li><a href="https://console.firebase.google.com/project/doska-a50b4" target="_blank">Firebase Console</a></li>
            <li><a href="https://console.firebase.google.com/project/doska-a50b4/settings/serviceaccounts/adminsdk" target="_blank">Service Accounts</a></li>
            <li><a href="https://console.firebase.google.com/project/doska-a50b4/settings/general" target="_blank">Project Settings</a></li>
            <li><a href="<?php echo admin_url('admin.php?page=firebase-test-notifications'); ?>">Тестирование уведомлений</a></li>
            <li><a href="<?php echo get_template_directory_uri(); ?>/includes/firebase-push-notifications/firebase-test.php" target="_blank">🔍 Детальный тест Firebase</a></li>
        </ul>
        
        <h3>📋 Инструкции по настройке</h3>
        <ol>
            <li><strong>Включите Firebase</strong> - поставьте галочку в первом поле</li>
            <li><strong>Проверьте Project ID</strong> - должен быть "doska-a50b4"</li>
            <li><strong>Получите Service Account JSON:</strong>
                <ul>
                    <li>Перейдите в <a href="https://console.firebase.google.com/project/doska-a50b4/settings/serviceaccounts/adminsdk" target="_blank">Service Accounts</a></li>
                    <li>Нажмите "Generate new private key"</li>
                    <li>Скачайте JSON файл</li>
                    <li>Скопируйте содержимое в поле "Service Account JSON"</li>
                </ul>
            </li>
            <li><strong>Получите VAPID Key:</strong>
                <ul>
                    <li>Перейдите в <a href="https://console.firebase.google.com/project/doska-a50b4/settings/cloudmessaging" target="_blank">Cloud Messaging</a></li>
                    <li>Найдите "Web configuration"</li>
                    <li>Скопируйте "Key pair"</li>
                </ul>
            </li>
            <li><strong>Сохраните настройки</strong> и проверьте статус</li>
        </ol>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('firebase_service_account_file');
        
        if (!fileInput) return;
        
        // Handle file selection with validation
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (file.type !== 'application/json' && !file.name.endsWith('.json')) {
                    alert('Пожалуйста, выберите файл с расширением .json');
                    fileInput.value = '';
                    return;
                }
                
                // Read file content for validation
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const content = e.target.result;
                        const jsonData = JSON.parse(content);
                        
                        // Validate JSON structure
                        if (jsonData.type === 'service_account' && jsonData.project_id) {
                            showFileUploadInfo('✅ Файл валиден! Нажмите "Сохранить" для загрузки.', 'success');
                        } else {
                            showFileUploadInfo('⚠️ Структура JSON может быть неверной. Проверьте, что это Service Account JSON.', 'warning');
                        }
                    } catch (error) {
                        showFileUploadInfo('❌ Невалидный JSON: ' + error.message, 'error');
                        fileInput.value = '';
                    }
                };
                reader.readAsText(file);
            }
        });
        
        // Show file upload info
        function showFileUploadInfo(message, type) {
            // Remove existing info
            const existingInfo = document.querySelector('.file-upload-info');
            if (existingInfo) {
                existingInfo.remove();
            }
            
            // Create new info
            const info = document.createElement('div');
            info.className = 'file-upload-info';
            info.style.marginTop = '10px';
            info.style.padding = '10px';
            info.style.borderRadius = '4px';
            info.style.borderLeft = '4px solid';
            info.style.color = type === 'success' ? '#155724' : type === 'warning' ? '#856404' : '#721c24';
            info.style.backgroundColor = type === 'success' ? '#d4edda' : type === 'warning' ? '#fff3cd' : '#f8d7da';
            info.style.borderLeftColor = type === 'success' ? '#28a745' : type === 'warning' ? '#ffc107' : '#dc3545';
            info.textContent = message;
            
            // Insert after file input
            fileInput.parentNode.insertBefore(info, fileInput.nextSibling);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                if (info.parentNode) {
                    info.remove();
                }
            }, 5000);
        }
    });
    </script>
</body>
</html>
