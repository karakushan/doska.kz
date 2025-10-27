# 🧪 Инструкция по тестированию Firebase Push Notifications

## ✅ Что исправлено:

1. **Обновлен Firebase SDK** до версии 10.7.1 с совместимостью
2. **Исправлены методы** для новой версии Firebase:
   - `messaging.requestPermission()` → `Notification.requestPermission()`
   - `messaging.onTokenRefresh()` → работает корректно
   - `messaging.getToken()` → добавлен параметр `vapidKey`

## 🚀 Как протестировать:

### 1. **Простой тест (рекомендуется)**
```bash
# Откройте в браузере:
http://localhost/test-firebase-simple.html
```

### 2. **Полный тест**
```bash
# Откройте в браузере:
http://localhost/firebase-test.html
```

### 3. **Тест через WordPress**
1. Войдите на сайт как пользователь
2. Перейдите в **My Dashboard** → **Accounts** → **Notification Settings**
3. Разрешите уведомления в браузере

## 🔧 Что нужно настроить:

### 1. **VAPID Key**
- Получите VAPID ключ из Firebase Console
- Замените `YOUR_VAPID_KEY_HERE` в тестовых файлах
- Настройте в WordPress: **Appearance** → **Theme Settings** → **Firebase Push Notifications**

### 2. **Service Account JSON**
- Получите Service Account JSON из Firebase Console
- Настройте в WordPress: **Appearance** → **Theme Settings** → **Firebase Push Notifications**

## 📱 Тестирование уведомлений:

### 1. **Через Firebase Console**
1. Откройте [Firebase Console](https://console.firebase.google.com/)
2. Выберите проект `doska-a50b4`
3. Перейдите в **Cloud Messaging**
4. Нажмите **"Send your first message"**
5. Выберите **"Single device"**
6. Вставьте FCM токен из браузера
7. Заполните заголовок и текст
8. Нажмите **"Send test message"**

### 2. **Через WordPress события**
- Отправьте сообщение другому пользователю
- Создайте объявление с истекающим сроком
- Проверьте, приходят ли уведомления

## 🔍 Проверка работы:

### 1. **Консоль браузера (F12)**
Должны быть сообщения:
```
Firebase initialized in Service Worker
FCM Token: [длинная строка]
Service Worker registered successfully
```

### 2. **Network tab**
Проверьте запросы:
- `get_firebase_config` - получение конфигурации
- `save_fcm_token` - сохранение токена

### 3. **Application tab**
- **Service Workers** - должен быть зарегистрирован
- **Notifications** - должны быть разрешены

## 🚨 Возможные проблемы:

### 1. **HTTPS требование**
- Некоторые браузеры требуют HTTPS для Service Workers
- Решение: используйте `https://localhost` или настройте SSL

### 2. **CORS ошибки**
- Проверьте, что AJAX запросы проходят успешно
- Убедитесь, что WordPress работает корректно

### 3. **Firebase домен**
- Firebase может блокировать localhost
- Решение: добавьте localhost в разрешенные домены в Firebase Console

## 📋 Чек-лист:

- [ ] Firebase SDK загружается без ошибок
- [ ] Разрешения на уведомления получены
- [ ] FCM токен получен
- [ ] Service Worker зарегистрирован
- [ ] VAPID ключ настроен
- [ ] Service Account JSON настроен
- [ ] Тестовое уведомление приходит
- [ ] Уведомления работают в фоне

## 🎯 Быстрый тест:

1. Откройте `test-firebase-simple.html`
2. Нажмите "Запросить разрешения"
3. Нажмите "Получить токен"
4. Скопируйте токен
5. Отправьте тестовое сообщение через Firebase Console
6. Проверьте, пришло ли уведомление

## 📞 Если что-то не работает:

1. **Проверьте консоль браузера** на ошибки
2. **Убедитесь, что VAPID ключ правильный**
3. **Проверьте Service Account JSON**
4. **Убедитесь, что пользователь разрешил уведомления**
5. **Проверьте, что Firebase проект активен**

---

**Готово!** 🚀 Система push-уведомлений должна работать корректно.
