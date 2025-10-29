<?php

/**
 * Test script for guest notifications functionality
 * 
 * Usage: Add ?test_guest_notifications=1 to any page URL when logged in as admin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Only run with test parameter - user check will be done later
if (!isset($_GET['test_guest_notifications'])) {
    return;
}

// Hook into wp_footer to add test interface
add_action('wp_footer', function () {
    // Check if user is admin
    if (!current_user_can('manage_options')) {
        return;
    }
?>
    <div id="firebase-test-panel" style="
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: white;
        border: 2px solid #0073aa;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10000;
        max-width: 300px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    ">
        <h3 style="margin: 0 0 15px 0; color: #0073aa;">🔔 Firebase Test Panel</h3>

        <div style="margin-bottom: 15px;">
            <strong>Статистика:</strong><br>
            <?php
            // Get statistics
            $users_with_tokens = get_users(array(
                'meta_query' => array(
                    array(
                        'key' => '_fcm_device_tokens',
                        'compare' => 'EXISTS'
                    )
                )
            ));

            $user_tokens_count = 0;
            foreach ($users_with_tokens as $user) {
                $tokens = get_user_meta($user->ID, '_fcm_device_tokens', true);
                if (is_array($tokens)) {
                    $user_tokens_count += count($tokens);
                }
            }

            $guest_tokens = get_option('firebase_guest_tokens', array());
            $guest_tokens_count = is_array($guest_tokens) ? count($guest_tokens) : 0;
            ?>
            Пользователи: <?php echo count($users_with_tokens); ?> (<?php echo $user_tokens_count; ?> токенов)<br>
            Гости: <?php echo $guest_tokens_count; ?> токенов<br>
            Всего: <?php echo $user_tokens_count + $guest_tokens_count; ?> устройств
        </div>

        <button onclick="testGuestNotification()" style="
            background: #0073aa;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        ">
            Тест уведомления
        </button>

        <button onclick="showGuestTokens()" style="
            background: #666;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        ">
            Показать токены
        </button>

        <div id="test-results" style="margin-top: 15px; font-size: 12px;"></div>
    </div>

    <script>
        function testGuestNotification() {
            const resultsDiv = document.getElementById('test-results');
            resultsDiv.innerHTML = 'Отправка тестового уведомления...';

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'test_guest_notification',
                        nonce: '<?php echo wp_create_nonce('firebase_test_nonce'); ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resultsDiv.innerHTML = `
                    <div style="color: green;">✅ Успешно!</div>
                    <div>Отправлено: ${data.data.success}</div>
                    <div>Ошибок: ${data.data.failed}</div>
                    <div>Всего: ${data.data.total}</div>
                `;
                    } else {
                        resultsDiv.innerHTML = `<div style="color: red;">❌ Ошибка: ${data.data}</div>`;
                    }
                })
                .catch(error => {
                    resultsDiv.innerHTML = `<div style="color: red;">❌ Ошибка: ${error.message}</div>`;
                });
        }

        function showGuestTokens() {
            const resultsDiv = document.getElementById('test-results');
            resultsDiv.innerHTML = 'Загрузка токенов...';

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'show_guest_tokens',
                        nonce: '<?php echo wp_create_nonce('firebase_test_nonce'); ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let html = '<div><strong>Гостевые токены:</strong></div>';
                        if (data.data.length === 0) {
                            html += '<div>Нет гостевых токенов</div>';
                        } else {
                            data.data.forEach((token, index) => {
                                html += `
                            <div style="margin: 5px 0; padding: 5px; background: #f5f5f5; border-radius: 3px; font-size: 10px;">
                                <strong>${index + 1}.</strong> ${token.token.substring(0, 20)}...<br>
                                <small>IP: ${token.ip_address} | ${token.created_at}</small>
                            </div>
                        `;
                            });
                        }
                        resultsDiv.innerHTML = html;
                    } else {
                        resultsDiv.innerHTML = `<div style="color: red;">❌ Ошибка: ${data.data}</div>`;
                    }
                })
                .catch(error => {
                    resultsDiv.innerHTML = `<div style="color: red;">❌ Ошибка: ${error.message}</div>`;
                });
        }
    </script>
<?php
});

// AJAX handler for test notification
add_action('wp_ajax_test_guest_notification', function () {
    check_ajax_referer('firebase_test_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    $firebase_manager = FirebaseManager::getInstance();
    if (!$firebase_manager->isInitialized()) {
        wp_send_json_error('Firebase not initialized');
    }

    $result = $firebase_manager->sendNotificationToAll(
        'Тестовое уведомление',
        'Это тестовое push-уведомление для проверки работы системы. Время: ' . date('H:i:s'),
        array(
            'action_url' => home_url(),
            'test' => true
        ),
        'test'
    );

    wp_send_json_success($result);
});

// AJAX handler for showing guest tokens
add_action('wp_ajax_show_guest_tokens', function () {
    check_ajax_referer('firebase_test_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    $guest_tokens = get_option('firebase_guest_tokens', array());
    wp_send_json_success($guest_tokens);
});
