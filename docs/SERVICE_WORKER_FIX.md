# 🔧 Исправление проблемы с Service Worker

## ❌ Проблема:
```
A bad HTTP response code (404) was received when fetching the script.
FirebaseError: Messaging: We are unable to register the default service worker. 
Failed to register a ServiceWorker for scope ('http://localhost:8080/firebase-cloud-messaging-push-scope') 
with script ('http://localhost:8080/firebase-messaging-sw.js'): A bad HTTP response code (404) was received when fetching the script.
```

## ✅ Решение:

### 1. **Указать Firebase использовать наш кастомный Service Worker**

```javascript
// Старый код (вызывал ошибку):
messaging.getToken({ vapidKey: firebaseConfig.vapidKey })

// Новый код (исправленный):
messaging.getToken({ 
    vapidKey: firebaseConfig.vapidKey,
    serviceWorkerRegistration: registration
})
```

### 2. **Получить регистрацию Service Worker**

```javascript
function getServiceWorkerRegistration() {
    if ('serviceWorker' in navigator) {
        return navigator.serviceWorker.getRegistration('/wp-content/themes/classiadspro/includes/firebase-push-notifications/assets/js/service-worker.js');
    }
    return Promise.resolve(null);
}
```

### 3. **Обновить инициализацию**

```javascript
function initializeFirebase() {
    // Initialize Firebase app
    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }
    
    // Get messaging instance
    messaging = firebase.messaging();
    
    // Initialize service worker first, then request permission
    initializeServiceWorker().then(function(registration) {
        if (registration) {
            // Request permission and get token
            requestPermission();
        } else {
            console.error('Service Worker registration failed, cannot proceed');
        }
    });
}
```

## 🚀 Теперь можно тестировать:

1. **Откройте тест:**
   ```
   http://localhost/test-firebase-simple.html
   ```

2. **Нажмите "Запросить разрешения"** - должно работать без ошибок

3. **Нажмите "Получить токен"** - должен появиться FCM токен

4. **Проверьте консоль браузера** - не должно быть ошибок 404

## 🔍 Проверка работы:

### Консоль браузера должна показывать:
```
Service Worker registered successfully: http://localhost:8080/wp-content/themes/classiadspro/includes/firebase-push-notifications/assets/js/service-worker.js
Service worker ready for Firebase messaging
FCM Token: [длинная строка]
```

### Не должно быть ошибок:
- ❌ `A bad HTTP response code (404) was received when fetching the script`
- ❌ `Failed to register a ServiceWorker for scope`
- ❌ `firebase-messaging-sw.js: A bad HTTP response code (404)`

## 🎯 Готово!

Теперь Firebase будет использовать наш кастомный Service Worker вместо попытки загрузить несуществующий `firebase-messaging-sw.js`! 🚀
