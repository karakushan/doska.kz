# 🔄 Firebase PHP SDK v7.23 - API Changes

## 📦 **Установленная версия:**
```json
{
  "kreait/firebase-php": "^7.23"
}
```

## 🔧 **API Изменения в v7.23:**

### **❌ Старый способ (v5-6):**
```php
$factory = new \Kreait\Firebase\Factory();
$firebase = $factory->withServiceAccount($serviceAccount)->create();
$messaging = $firebase->getMessaging();
```

### **✅ Новый способ (v7.23):**
```php
$factory = new \Kreait\Firebase\Factory();
$factory = $factory->withServiceAccount($serviceAccount);
$messaging = $factory->createMessaging();
```

## 📋 **Основные изменения:**

| Компонент | v5-6 | v7.23 |
|-----------|------|-------|
| **Инициализация** | `->create()` | Не используется |
| **Messaging** | `$firebase->getMessaging()` | `$factory->createMessaging()` |
| **Auth** | `$firebase->getAuth()` | `$factory->createAuth()` |
| **Database** | `$firebase->getDatabase()` | `$factory->createDatabase()` |
| **Firestore** | `$firebase->getFirestore()` | `$factory->createFirestore()` |

## 🎯 **Методы Factory в v7.23:**

```php
$factory = new \Kreait\Firebase\Factory();
$factory = $factory->withServiceAccount($serviceAccount);

// Доступные методы:
$messaging = $factory->createMessaging();      // ✅ Messaging API
$auth = $factory->createAuth();               // ✅ Auth API
$database = $factory->createDatabase();       // ✅ Database API
$firestore = $factory->createFirestore();     // ✅ Firestore API
$storage = $factory->createStorage();         // ✅ Storage API
```

## 🔄 **Исправленные файлы:**

| Файл | Изменение |
|------|-----------|
| `class-firebase-manager.php` | ✅ Использует `createMessaging()` |
| `firebase-debug-test.php` | ✅ Использует `createMessaging()` |

## 📝 **Примеры использования Messaging в v7.23:**

### **Отправка сообщения:**
```php
$factory = new \Kreait\Firebase\Factory();
$factory = $factory->withServiceAccount($serviceAccount);
$messaging = $factory->createMessaging();

$message = \Kreait\Firebase\Messaging\Message::new()
    ->withNotification(\Kreait\Firebase\Messaging\Notification::create(
        'Title',
        'Body'
    ))
    ->withData(['key' => 'value']);

$messaging->send($message, $deviceToken);
```

### **Отправка multicast:**
```php
$report = $messaging->sendMulticast($message, [$token1, $token2]);
```

## ✅ **Проверенные компоненты:**

- ✅ Service Account инициализация
- ✅ Messaging API
- ✅ Отправка сообщений
- ✅ Firebase Debug Test

## 📚 **Официальная документация:**

- [Kreait Firebase PHP SDK](https://github.com/kreait/firebase-php)
- [API Documentation](https://kreait-firebase-php.readthedocs.io/)
- [Changelog](https://github.com/kreait/firebase-php/releases)

## ✅ **Статус:**

**Плагин полностью совместим с Firebase PHP SDK v7.23!** 🎉

Все методы обновлены и протестированы.
