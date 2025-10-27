<?php
/**
 * Test Push Notification Sender
 * Simple tool to send test push notifications
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
if (isset($_POST['send_test_notification'])) {
    $title = sanitize_text_field($_POST['notification_title']);
    $body = sanitize_textarea_field($_POST['notification_body']);
    $user_id = intval($_POST['user_id']);
    
    if (empty($title) || empty($body) || empty($user_id)) {
        $error_message = 'Все поля обязательны для заполнения';
    } else {
        // Get user tokens
        $tokens = get_user_meta($user_id, '_fcm_device_tokens', true);
        
        if (empty($tokens) || !is_array($tokens)) {
            $error_message = 'У пользователя нет зарегистрированных устройств';
        } else {
            // Debug information
            $debug_info = array();
            $debug_info[] = 'FirebaseNotificationHandler exists: ' . (class_exists('FirebaseNotificationHandler') ? 'Yes' : 'No');
            $debug_info[] = 'FirebaseManager exists: ' . (class_exists('FirebaseManager') ? 'Yes' : 'No');
            
            // Load Firebase classes
            if (class_exists('FirebaseNotificationHandler')) {
                $handler = FirebaseNotificationHandler::getInstance();
                
                // Check if Firebase is initialized
                if (class_exists('FirebaseManager')) {
                    $firebase_manager = FirebaseManager::getInstance();
                    $debug_info[] = 'Firebase initialized: ' . ($firebase_manager->isInitialized() ? 'Yes' : 'No');
                    
                    if (!$firebase_manager->isInitialized()) {
                        $error_message = 'Firebase не инициализирован. Проверьте настройки Firebase в админке темы.';
                    } else {
                        // Send test notification
                        $result = $handler->sendNotificationToUser($user_id, $title, $body, array(
                            'type' => 'test',
                            'timestamp' => time()
                        ), 'test');
                        
                        if ($result) {
                            $success_message = 'Тестовое уведомление отправлено успешно!';
                        } else {
                            $error_message = 'Ошибка при отправке уведомления. Debug: ' . implode(', ', $debug_info);
                        }
                    }
                } else {
                    $error_message = 'FirebaseManager не найден';
                }
            } elseif (class_exists('FirebaseManager')) {
                $firebase_manager = FirebaseManager::getInstance();
                $debug_info[] = 'Firebase initialized: ' . ($firebase_manager->isInitialized() ? 'Yes' : 'No');
                
                if (!$firebase_manager->isInitialized()) {
                    $error_message = 'Firebase не инициализирован. Проверьте настройки Firebase в админке темы.';
                } else {
                    // Send test notification
                    $result = $firebase_manager->sendNotificationToUser($user_id, $title, $body, array(
                        'type' => 'test',
                        'timestamp' => time()
                    ), 'test');
                    
                    if ($result) {
                        $success_message = 'Тестовое уведомление отправлено успешно!';
                    } else {
                        $error_message = 'Ошибка при отправке уведомления. Debug: ' . implode(', ', $debug_info);
                    }
                }
            } else {
                $error_message = 'Firebase классы не найдены. Debug: ' . implode(', ', $debug_info);
            }
        }
    }
}

// Get all users with FCM tokens
$users_with_tokens = get_users(array(
    'meta_query' => array(
        array(
            'key' => '_fcm_device_tokens',
            'compare' => 'EXISTS'
        )
    )
));

?>
<!DOCTYPE html>
<html>
<head>
    <title>Тестовые Push Уведомления</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        textarea { height: 100px; resize: vertical; }
        button { background: #007cba; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #005a87; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .user-info { background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .token-count { color: #007cba; font-weight: bold; }
        
        /* Diagnostic styles */
        .diagnostic-info { margin: 20px 0; }
        .diagnostic-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .diagnostic-table th, .diagnostic-table td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #e0e0e0;
        }
        .diagnostic-table th { 
            background: #f8f9fa; 
            font-weight: bold;
            color: #333;
        }
        .diagnostic-table tr:hover { 
            background: #f8f9fa; 
        }
        .diagnostic-warning { 
            background: #fff3cd; 
            color: #856404; 
            padding: 15px; 
            border-radius: 4px; 
            margin: 15px 0;
            border-left: 4px solid #ffc107;
        }
        .diagnostic-success { 
            background: #d4edda; 
            color: #155724; 
            padding: 15px; 
            border-radius: 4px; 
            margin: 15px 0;
            border-left: 4px solid #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Тестовые Push Уведомления</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="success">✅ <?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="error">❌ <?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if (empty($users_with_tokens)): ?>
            <div class="error">
                <h3>⚠️ Нет пользователей с зарегистрированными устройствами</h3>
                <p>Для отправки тестовых уведомлений необходимо, чтобы пользователи:</p>
                <ol>
                    <li>Зашли на сайт и разрешили уведомления в браузере</li>
                    <li>Имели зарегистрированные FCM токены</li>
                </ol>
                <p><strong>Решение:</strong> Попросите пользователей зайти на сайт и разрешить уведомления в браузере.</p>
            </div>
        <?php else: ?>
            <div class="user-info">
                <h3>📱 Пользователи с зарегистрированными устройствами</h3>
                <p>Найдено <span class="token-count"><?php echo count($users_with_tokens); ?></span> пользователей с FCM токенами</p>
            </div>
            
            <form method="post">
                <div class="form-group">
                    <label for="user_id">Выберите пользователя:</label>
                    <select name="user_id" id="user_id" required>
                        <option value="">-- Выберите пользователя --</option>
                        <?php foreach ($users_with_tokens as $user): 
                            $tokens = get_user_meta($user->ID, '_fcm_device_tokens', true);
                            $token_count = is_array($tokens) ? count($tokens) : 0;
                        ?>
                            <option value="<?php echo $user->ID; ?>">
                                <?php echo $user->display_name; ?> (<?php echo $user->user_email; ?>) - <?php echo $token_count; ?> устройств
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="notification_title">Заголовок уведомления:</label>
                    <input type="text" name="notification_title" id="notification_title" 
                           value="Тестовое уведомление" required>
                </div>
                
                <div class="form-group">
                    <label for="notification_body">Текст уведомления:</label>
                    <textarea name="notification_body" id="notification_body" required>Это тестовое push-уведомление от Firebase!</textarea>
                </div>
                
                <button type="submit" name="send_test_notification">
                    📤 Отправить тестовое уведомление
                </button>
            </form>
            
            <hr style="margin: 40px 0;">
            
            <h3>🔍 Диагностика Firebase</h3>
            <div class="diagnostic-info">
                <?php
                // Check Firebase status
                global $pacz_settings;
                $firebase_enabled = isset($pacz_settings['firebase_enabled']) ? $pacz_settings['firebase_enabled'] : false;
                $project_id = isset($pacz_settings['firebase_project_id']) ? $pacz_settings['firebase_project_id'] : '';
                $service_account = isset($pacz_settings['firebase_service_account_json']) ? $pacz_settings['firebase_service_account_json'] : '';
                
                // Check Firebase classes
                $firebase_manager_exists = class_exists('FirebaseManager');
                $firebase_handler_exists = class_exists('FirebaseNotificationHandler');
                
                $firebase_initialized = false;
                if ($firebase_manager_exists) {
                    $firebase_manager = FirebaseManager::getInstance();
                    $firebase_initialized = $firebase_manager->isInitialized();
                }
                ?>
                
                <table class="diagnostic-table">
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
                        <td>Service Account JSON</td>
                        <td><?php echo !empty($service_account) ? '✅ Настроен' : '❌ Не настроен'; ?></td>
                        <td><?php echo !empty($service_account) ? 'Загружен' : 'Не загружен'; ?></td>
                    </tr>
                    <tr>
                        <td>FirebaseManager класс</td>
                        <td><?php echo $firebase_manager_exists ? '✅ Загружен' : '❌ Не найден'; ?></td>
                        <td><?php echo $firebase_manager_exists ? 'Доступен' : 'Недоступен'; ?></td>
                    </tr>
                    <tr>
                        <td>FirebaseNotificationHandler класс</td>
                        <td><?php echo $firebase_handler_exists ? '✅ Загружен' : '❌ Не найден'; ?></td>
                        <td><?php echo $firebase_handler_exists ? 'Доступен' : 'Недоступен'; ?></td>
                    </tr>
                    <tr>
                        <td>Firebase инициализирован</td>
                        <td><?php echo $firebase_initialized ? '✅ Да' : '❌ Нет'; ?></td>
                        <td><?php echo $firebase_initialized ? 'Готов к работе' : 'Не инициализирован'; ?></td>
                    </tr>
                </table>
                
                <?php if (!$firebase_enabled): ?>
                    <div class="diagnostic-warning">
                        <strong>⚠️ Firebase отключен!</strong><br>
                        Перейдите в <a href="<?php echo admin_url('customize.php'); ?>">настройки темы</a> и включите Firebase Push Notifications.
                    </div>
                <?php elseif (empty($project_id)): ?>
                    <div class="diagnostic-warning">
                        <strong>⚠️ Project ID не настроен!</strong><br>
                        Укажите Firebase Project ID в настройках темы.
                    </div>
                <?php elseif (empty($service_account)): ?>
                    <div class="diagnostic-warning">
                        <strong>⚠️ Service Account JSON не загружен!</strong><br>
                        Загрузите Service Account JSON файл в настройках темы.
                    </div>
                <?php elseif (!$firebase_initialized): ?>
                    <div class="diagnostic-warning">
                        <strong>⚠️ Firebase не инициализирован!</strong><br>
                        Проверьте правильность настроек Firebase.
                    </div>
                <?php else: ?>
                    <div class="diagnostic-success">
                        <strong>✅ Firebase настроен корректно!</strong><br>
                        Система готова к отправке push-уведомлений.
                    </div>
                <?php endif; ?>
            </div>
            
            <hr style="margin: 40px 0;">
            
            <h3>📋 Инструкции по тестированию</h3>
            <ol>
                <li><strong>Выберите пользователя</strong> из списка выше</li>
                <li><strong>Введите заголовок и текст</strong> уведомления</li>
                <li><strong>Нажмите "Отправить"</strong> для отправки уведомления</li>
                <li><strong>Проверьте устройство</strong> пользователя на получение уведомления</li>
            </ol>
            
            <h3>🔧 Альтернативные способы тестирования</h3>
            <ul>
                <li><strong>Firebase Console:</strong> <a href="https://console.firebase.google.com/project/doska-a50b4/messaging" target="_blank">https://console.firebase.google.com/project/doska-a50b4/messaging</a></li>
                <li><strong>Прямая отправка:</strong> Используйте FCM токены из админки WordPress</li>
                <li><strong>Программная отправка:</strong> Через наш WordPress интерфейс (выше)</li>
            </ul>
        <?php endif; ?>
        
        <hr style="margin: 40px 0;">
        
        <h3>🔗 Полезные ссылки</h3>
        <ul>
            <li><a href="<?php echo admin_url('profile.php'); ?>">FCM токены в админке WordPress</a></li>
            <li><a href="<?php echo home_url('/my-dashboard/'); ?>">Настройки уведомлений пользователя</a></li>
            <li><a href="https://console.firebase.google.com/project/doska-a50b4/messaging" target="_blank">Firebase Console - Cloud Messaging</a></li>
        </ul>
    </div>
</body>
</html>
