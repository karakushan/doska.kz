# 🔧 Исправление ошибки "Service Account JSON невалидный"

## ❌ **Проблема:**
```
❌ Service Account JSON невалидный
Проверьте формат JSON и обязательные поля.

Детали:
Invalid JSON format: Syntax error
Firebase not initialized
```

## 🎯 **Причина:**
Service Account JSON содержит синтаксическую ошибку JSON или был неправильно сохранен в базе данных.

## ✅ **Решение:**

### Способ 1: Автоматическое восстановление (рекомендуется)

#### Шаг 1: Перейти на страницу исправления JSON
1. **В админке WordPress перейдите в:** Firebase Push → Fix JSON
2. **Или перейдите по ссылке:** `/wp-admin/admin.php?page=firebase-fix-json`

#### Шаг 2: Загрузить JSON из бэкапа
1. **На странице "Firebase Service Account JSON Fixer" вы увидите:**
   - Путь к файлу бэкапа
   - Статус файла (✅ File exists)
   - Предпросмотр содержимого JSON

2. **Нажмите кнопку "✅ Load Service Account JSON"**
   - Система загрузит JSON из бэкапа
   - Система проверит валидность JSON
   - Система сохранит JSON в базу данных

#### Шаг 3: Проверка
1. **Вернитесь в Firebase Configuration**
2. **Проверьте диагностическую таблицу**
3. **Firebase должен показывать статус ✅ Инициализирован**

### Способ 2: Ручное исправление

#### Если бэкап недоступен:

1. **Загрузите новый Service Account JSON:**
   - Перейдите в [Firebase Console](https://console.firebase.google.com/)
   - Project Settings → Service Accounts
   - Нажмите "Generate new private key"
   - Скачайте новый JSON файл

2. **Загрузите JSON в конфигурацию:**
   - Firebase Push → Configuration
   - В поле "Service Account JSON" выберите новый файл
   - Нажмите "💾 Сохранить настройки"

3. **Проверьте статус:**
   - Диагностическая таблица должна показать ✅

## 📋 **Формат валидного Service Account JSON:**

```json
{
  "type": "service_account",
  "project_id": "doska-a50b4",
  "private_key_id": "69f034b924dde85e...",
  "private_key": "-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n",
  "client_email": "firebase-adminsdk-fbsvc@doska-a50b4.iam.gserviceaccount.com",
  "client_id": "107059416627249489171",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40doska-a50b4.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
```

## 🔍 **Диагностика:**

### Проверка JSON валидности:

1. **Используйте JSON валидатор:**
   - [jsonlint.com](https://www.jsonlint.com/)
   - [jsonchecker.com](https://jsonchecker.com/)

2. **Проверьте обязательные поля:**
   - ✅ `type`: должно быть `service_account`
   - ✅ `project_id`: ID проекта Firebase
   - ✅ `private_key`: приватный ключ
   - ✅ `client_email`: почта сервиса

3. **Проверьте спецсимволы:**
   - ✅ Все кавычки должны быть двойными (")
   - ✅ Переносы строк должны быть экранированы (\n)
   - ✅ Нет лишних запятых в конце объектов

## 🚀 **После исправления:**

### Ожидаемые изменения:
- ✅ Service Account JSON: ✅ Загружен
- ✅ Firebase инициализирован: ✅ Да
- ✅ Статус Firebase: ✅ Инициализирован

### Проверка функциональности:
1. **Перейдите в Firebase Push → Test Notifications**
2. **Отправьте тестовое уведомление**
3. **Проверьте доставку на браузер**

## 📞 **Если проблема не решается:**

### Проверьте:
1. **Права доступа к файлам:**
   ```bash
   ls -la /backups/doska-a50b4-69f034b924dd.json
   ```

2. **Лог ошибок WordPress:**
   ```bash
   tail -f /var/log/wordpress/debug.log
   ```

3. **Синтаксис JSON онлайн:**
   - Скопируйте содержимое JSON
   - Вставьте в [jsonlint.com](https://www.jsonlint.com/)
   - Проверьте ошибки парсинга

### Возможные ошибки:
- **Syntax error**: Некорректный JSON формат
- **Missing required field**: Отсутствует обязательное поле
- **Invalid characters**: Недопустимые символы в JSON

## ✅ **Статус:**
**Проблема с Service Account JSON решается автоматической загрузкой из бэкапа!**

Используйте встроенный инструмент "Fix JSON" для быстрого исправления. 🎉
