# Отладка проблем с Safari

## Проблема

В Safari токен не сохраняется после авторизации пользователя.

## Шаги для отладки

### 1. Откройте консоль разработчика в Safari

- Safari → Разработка → Показать веб-инспектор
- Перейдите на вкладку "Консоль"

### 2. Проверьте состояние системы

Выполните в консоли:

```javascript
window.firebasePushNotifications.debugState()
```

Это покажет:

- Браузер (должен быть Safari)
- Разрешения на уведомления
- Состояние Firebase
- Наличие сохраненного токена
- Статус авторизации

### 3. Проверьте localStorage

```javascript
// Проверить сохраненный токен
localStorage.getItem('fcm_token')

// Проверить, был ли запрос разрешений
localStorage.getItem('fcm_permission_asked')

// Показать все данные Firebase
Object.keys(localStorage).filter(key => key.includes('fcm'))
```

### 4. Принудительно запросить токен

```javascript
// Если разрешения есть, но токена нет
window.firebasePushNotifications.getToken()
```

### 5. Проверьте сетевые запросы

- Перейдите на вкладку "Сеть" в инспекторе
- Обновите страницу
- Найдите запросы к `admin-ajax.php`
- Проверьте, отправляется ли запрос `save_fcm_token`

## Возможные проблемы и решения

### Проблема 1: Service Worker не работает в Safari

**Симптомы:** В консоли ошибки связанные с Service Worker
**Решение:** Safari использует fallback без Service Worker

### Проблема 2: localStorage блокируется

**Симптомы:** Ошибки при сохранении в localStorage
**Решение:** Проверить настройки приватности Safari

### Проблема 3: VAPID ключ не работает

**Симптомы:** Ошибки при получении токена от Firebase
**Решение:** Проверить конфигурацию Firebase

### Проблема 4: Разрешения не сохраняются

**Симптомы:** Каждый раз запрашиваются разрешения
**Решение:** Проверить настройки сайта в Safari

## Логи для анализа

Включите DEBUG режим и найдите в консоли:

### Успешный сценарий

```
[Firebase Push] Initializing Firebase Push Notifications
[Firebase Push] Browser: Safari
[Firebase Push] Permission granted, checking for existing token
[Firebase Push] Getting FCM token...
[Firebase Push] ✅ Token obtained: abc123...
[Firebase Push] ✅ Token stored in localStorage: abc123...
[Firebase Push] Saving token to server for logged in user
```

### Проблемный сценарий

```
[Firebase Push] ❌ ERROR: Messaging not initialized
[Firebase Push] ❌ No token received
[Firebase Push] ❌ Error storing token: ...
```

## Команды для тестирования

### Очистить все данные и начать заново

```javascript
localStorage.removeItem('fcm_token');
localStorage.removeItem('fcm_permission_asked');
// Обновить страницу
location.reload();
```

### Принудительно запросить разрешения

```javascript
window.firebasePushNotifications.requestPermission();
```

### Проверить конфигурацию Firebase

```javascript
console.log(window.firebasePushNotifications.config);
```

## Специфика Safari

1. **Service Worker ограничения**: Safari имеет ограниченную поддержку Service Workers
2. **Push API**: Требует HTTPS и специальной настройки
3. **localStorage**: Может блокироваться в приватном режиме
4. **Разрешения**: Работают по-другому чем в Chrome/Firefox

## Если ничего не помогает

1. Проверьте настройки Safari → Настройки → Веб-сайты → Уведомления
2. Убедитесь что сайт работает по HTTPS
3. Проверьте что Firebase правильно настроен для Safari
4. Попробуйте в обычном (не приватном) режиме Safari
