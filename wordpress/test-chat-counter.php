<?php
// Тестовая страница для проверки счетчика чата
require_once('wp-config.php');

// Имитируем авторизованного пользователя
wp_set_current_user(1); // ID администратора

?>
<!DOCTYPE html>
<html>

<head>
    <title>Тест счетчика чата</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Тест счетчика чата</h1>

    <!-- Тестовые элементы с разными вариантами -->
    <div style="margin: 20px 0;">
        <h3>Вариант 1: ID mob-messages</h3>
        <a href="/my-dashboard/?directorypress_action=messages" class="hfb-button icon-align-top" id="mob-messages">
            <i aria-hidden="true" class="dicode-material-icons dicode-material-icons-message-minus-outline"></i>
            Chat
        </a>
    </div>

    <div style="margin: 20px 0;">
        <h3>Вариант 2: Ссылка с directorypress_action=messages</h3>
        <a href="/my-dashboard/?directorypress_action=messages" class="chat-link">
            <i class="icon-chat"></i>
            Сообщения
        </a>
    </div>

    <div style="margin: 20px 0;">
        <h3>Вариант 3: Просто текст Chat</h3>
        <a href="/messages" class="nav-link">Chat</a>
    </div>

    <?php
    // Вызываем нашу функцию
    add_mobile_chat_counter();
    ?>

    <script>
        // Дополнительная отладка
        setTimeout(function() {
            console.log('Все элементы на странице:');
            console.log('mob-messages:', $('#mob-messages').length);
            console.log('directorypress_action=messages:', $('a[href*="directorypress_action=messages"]').length);
            console.log('Chat text:', $('a:contains("Chat")').length);
            console.log('Счетчики:', $('.chat-counter').length);
        }, 3000);
    </script>
</body>

</html>