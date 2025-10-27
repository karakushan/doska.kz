# Настройка Firebase Push Notifications

## ✅ Ваши данные Firebase уже добавлены в настройки темы:

- **Project ID**: `doska-a50b4`
- **API Key**: `YOUR_API_KEY_REMOVED`
- **Messaging Sender ID**: `927038207069`
- **App ID**: `1:927038207069:web:38e3755d76e75b379c49b4`

## 🔑 Что нужно получить дополнительно:

### 1. VAPID Key (для веб-push уведомлений)

1. Перейдите в [Firebase Console](https://console.firebase.google.com/)
2. Выберите проект `doska-a50b4`
3. В боковом меню выберите **Cloud Messaging**
4. Нажмите на вкладку **Web Push certificates**
5. Если ключи не созданы, нажмите **Generate key pair**
6. Скопируйте **Key pair** (длинная строка)

### 2. Service Account JSON (для серверной части)

1. В Firebase Console перейдите в **Project Settings** (шестеренка)
2. Выберите вкладку **Service accounts**
3. Нажмите **Generate new private key**
4. Скачайте JSON файл
5. Скопируйте содержимое JSON файла

## ⚙️ Настройка в WordPress:

1. Перейдите в админку WordPress
2. Выберите **Appearance > Theme Settings**
3. Найдите секцию **Firebase Push Notifications**
4. Включите переключатель **Enable Firebase Push Notifications**
5. Заполните поля:
   - **Firebase VAPID Key**: вставьте VAPID ключ
   - **Firebase Service Account JSON**: вставьте содержимое JSON файла
6. Сохраните настройки

## 🧪 Тестирование:

1. Войдите на сайт как зарегистрированный пользователь
2. Перейдите в **My Dashboard > Accounts > Notification Settings**
3. Разрешите уведомления в браузере
4. Настройте типы уведомлений
5. Протестируйте отправку сообщения или создание объявления

## 📱 Поддерживаемые браузеры:

- Chrome 50+
- Firefox 44+
- Safari 16+
- Edge 17+

## 🔧 Отладка:

Если уведомления не работают:

1. Проверьте консоль браузера на ошибки
2. Убедитесь, что Service Account JSON корректный
3. Проверьте, что VAPID ключ правильный
4. Убедитесь, что пользователь разрешил уведомления

## 📊 Логи:

Все уведомления логируются в таблицу `wp_firebase_notifications_log` для отладки.
