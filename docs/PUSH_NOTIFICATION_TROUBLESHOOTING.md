# 🔧 Устранение проблем с отправкой push-уведомлений

## ❌ Ошибка: "Ошибка при отправке уведомления"

### 🔍 Диагностика проблемы

#### 1. **Проверьте диагностическую таблицу**
В интерфейсе тестирования (`/wp-admin/` → Firebase Push → Test Notifications) есть диагностическая таблица, которая показывает:

- ✅ **Firebase включен** - должен быть "Да"
- ✅ **Project ID** - должен быть настроен
- ✅ **Service Account JSON** - должен быть загружен
- ✅ **FirebaseManager класс** - должен быть загружен
- ✅ **FirebaseNotificationHandler класс** - должен быть загружен
- ✅ **Firebase инициализирован** - должен быть "Да"

#### 2. **Возможные причины ошибки**

##### 🔴 Firebase не включен
**Симптом:** Firebase включен = "Нет"
**Решение:**
1. Перейдите в `/wp-admin/customize.php`
2. Найдите секцию "Firebase Push Notifications"
3. Включите "Enable Firebase Push Notifications"

##### 🔴 Project ID не настроен
**Симптом:** Project ID = "Не указан"
**Решение:**
1. Перейдите в настройки темы
2. Укажите Firebase Project ID: `doska-a50b4`

##### 🔴 Service Account JSON не загружен
**Симптом:** Service Account JSON = "Не загружен"
**Решение:**
1. Скачайте Service Account JSON из Firebase Console
2. Загрузите его в настройки темы
3. Убедитесь, что файл содержит правильные данные

##### 🔴 Firebase не инициализирован
**Симптом:** Firebase инициализирован = "Нет"
**Решение:**
1. Проверьте правильность Service Account JSON
2. Убедитесь, что все настройки Firebase корректны
3. Проверьте логи ошибок WordPress

### 🛠️ Пошаговое решение

#### Шаг 1: Проверка настроек Firebase
1. **Откройте Firebase Console:** https://console.firebase.google.com/project/doska-a50b4
2. **Перейдите в Project Settings** (шестеренка в левом меню)
3. **Скопируйте Project ID:** `doska-a50b4`

#### Шаг 2: Настройка Service Account
1. **В Firebase Console** → Project Settings → Service Accounts
2. **Нажмите "Generate new private key"**
3. **Скачайте JSON файл**
4. **В WordPress** → Customize → Firebase Push Notifications
5. **Загрузите JSON файл** в поле "Service Account JSON"

#### Шаг 3: Проверка конфигурации
```php
// Проверьте в настройках темы:
Firebase Project ID: doska-a50b4
Firebase API Key: YOUR_API_KEY_REMOVED
Messaging Sender ID: 927038207069
App ID: 1:927038207069:web:38e3755d76e75b379c49b4
```

#### Шаг 4: Проверка пользователей
1. **Убедитесь, что у пользователей есть FCM токены**
2. **Пользователи должны разрешить уведомления в браузере**
3. **FCM токены должны быть сохранены в базе данных**

### 🔧 Дополнительная диагностика

#### Проверка логов ошибок
```bash
# Проверьте логи WordPress
tail -f /var/log/apache2/error.log
tail -f /var/log/nginx/error.log

# Или в WordPress
wp log list --path=/var/www/html
```

#### Проверка Composer зависимостей
```bash
# Убедитесь, что Firebase PHP SDK установлен
composer show kreait/firebase-php
```

#### Проверка прав доступа
```bash
# Проверьте права на файлы
ls -la /var/www/html/wp-content/themes/classiadspro/vendor/
```

### 🚀 Альтернативные способы тестирования

#### 1. Firebase Console (рекомендуется)
1. Перейдите на https://console.firebase.google.com/project/doska-a50b4/messaging
2. Нажмите "Send your first message"
3. Введите заголовок и текст
4. Выберите "Send test message"
5. Введите FCM токен пользователя

#### 2. Прямая отправка через cURL
```bash
curl -X POST https://fcm.googleapis.com/fcm/send \
  -H "Authorization: key=YOUR_SERVER_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "USER_FCM_TOKEN",
    "notification": {
      "title": "Test Notification",
      "body": "This is a test message"
    }
  }'
```

### 📞 Получение помощи

Если проблема не решается:

1. **Проверьте диагностическую таблицу** в интерфейсе тестирования
2. **Скопируйте сообщение об ошибке** полностью
3. **Проверьте логи сервера** на наличие ошибок
4. **Убедитесь, что все настройки Firebase корректны**

### ✅ Проверочный список

- [ ] Firebase включен в настройках темы
- [ ] Project ID указан правильно
- [ ] Service Account JSON загружен
- [ ] Firebase классы загружены
- [ ] Firebase инициализирован
- [ ] У пользователей есть FCM токены
- [ ] Пользователи разрешили уведомления
- [ ] Нет ошибок в логах сервера
