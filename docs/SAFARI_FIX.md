# Исправление проблемы с Safari

## Проблема

Safari требует синхронного вызова `Notification.requestPermission()` в обработчике клика пользователя.

## Решение

Изменена логика обработки кликов:

### До исправления

1. Пользователь нажимает "Allow"
2. Диалог закрывается
3. Асинхронно вызывается `requestPermission()`
4. Safari блокирует запрос ❌

### После исправления

1. Пользователь нажимает "Allow"
2. **Синхронно** вызывается `Notification.requestPermission()`
3. Диалог закрывается
4. Safari показывает системный запрос ✅

## Тестирование

### Шаги для проверки

1. Очистить разрешения: `localStorage.removeItem('fcm_permission_asked')`
2. Обновить страницу
3. Нажать "Allow" в кастомном диалоге
4. Должен появиться системный диалог Safari
5. Нажать "Allow" в системном диалоге
6. Токен должен сохраниться

### Ожидаемые логи в консоли

```
[Firebase Push] User clicked Allow, requesting permission synchronously
[Firebase Push] Safari detected, calling requestPermission synchronously
[Firebase Push] Attempting Promise-based requestPermission
[Firebase Push] Permission result: granted
[Firebase Push] Permission granted, checking for existing token
[Firebase Push] Getting FCM token...
[Firebase Push] ✅ Token obtained: abc123...
```

## Специфика Safari

- Требует синхронного вызова в обработчике события
- Поддерживает как Promise, так и callback API
- Может блокировать асинхронные запросы разрешений
