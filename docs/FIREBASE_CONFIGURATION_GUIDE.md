# ⚙️ Настройка Firebase Push Notifications

## 🔧 Инструмент настройки Firebase

### 📍 **Доступ к инструменту:**
1. **WordPress админка:** `/wp-admin/` → Firebase Push → Configuration
2. **Прямая ссылка:** `/wp-admin/admin.php?page=firebase-configuration`

### ✨ **Возможности инструмента:**

#### 📊 **Диагностическая таблица**
- ✅ Статус всех параметров Firebase
- ✅ Визуальные индикаторы (зеленые/красные)
- ✅ Текущие значения настроек
- ✅ Статус инициализации Firebase

#### ⚙️ **Настройка параметров**
- ✅ Включение/отключение Firebase
- ✅ Project ID, API Key, Messaging Sender ID
- ✅ App ID, VAPID Key
- ✅ Service Account JSON

#### 🔗 **Полезные ссылки**
- ✅ Прямые ссылки на Firebase Console
- ✅ Service Accounts, Project Settings
- ✅ Cloud Messaging настройки

## 🎯 **Пошаговая настройка**

### Шаг 1: Включение Firebase
1. **Перейдите в инструмент настройки** (`/wp-admin/` → Firebase Push → Configuration)
2. **Поставьте галочку** "Включить Firebase Push Notifications"
3. **Проверьте Project ID** - должен быть `doska-a50b4`

### Шаг 2: Получение Service Account JSON
1. **Перейдите в Firebase Console:** https://console.firebase.google.com/project/doska-a50b4/settings/serviceaccounts/adminsdk
2. **Нажмите "Generate new private key"**
3. **Скачайте JSON файл**
4. **Скопируйте содержимое файла** в поле "Service Account JSON"

### Шаг 3: Получение VAPID Key
1. **Перейдите в Cloud Messaging:** https://console.firebase.google.com/project/doska-a50b4/settings/cloudmessaging
2. **Найдите секцию "Web configuration"**
3. **Скопируйте "Key pair"** в поле "VAPID Key"

### Шаг 4: Сохранение настроек
1. **Нажмите "Сохранить настройки Firebase"**
2. **Проверьте диагностическую таблицу**
3. **Убедитесь, что все параметры показывают ✅**

## 🔍 **Диагностика проблем**

### ❌ **Firebase не инициализирован**

#### Возможные причины:
1. **Service Account JSON не загружен**
2. **Неверный формат JSON**
3. **Неправильные права доступа**
4. **Ошибки в конфигурации**

#### Решение:
1. **Проверьте Service Account JSON:**
   - Убедитесь, что JSON валидный
   - Проверьте, что файл содержит все необходимые поля
   - Убедитесь, что project_id соответствует вашему проекту

2. **Проверьте логи ошибок:**
   ```bash
   # Проверьте логи WordPress
   tail -f /var/log/apache2/error.log
   ```

3. **Проверьте права доступа:**
   ```bash
   # Убедитесь, что файлы доступны
   ls -la /var/www/html/wp-content/themes/classiadspro/vendor/
   ```

### ❌ **Service Account JSON не загружен**

#### Решение:
1. **Получите новый JSON файл** из Firebase Console
2. **Убедитесь, что скопировали весь файл**
3. **Проверьте, что JSON валидный**

### ❌ **VAPID Key не настроен**

#### Решение:
1. **Перейдите в Cloud Messaging настройки**
2. **Найдите Web configuration**
3. **Скопируйте Key pair**

## 📋 **Проверочный список**

- [ ] Firebase включен в настройках
- [ ] Project ID указан правильно (`doska-a50b4`)
- [ ] API Key загружен
- [ ] Messaging Sender ID указан
- [ ] App ID указан
- [ ] VAPID Key настроен
- [ ] Service Account JSON загружен и валидный
- [ ] Firebase инициализирован (статус ✅)

## 🚀 **После настройки**

1. **Проверьте статус** в диагностической таблице
2. **Перейдите к тестированию** уведомлений
3. **Убедитесь, что у пользователей есть FCM токены**
4. **Отправьте тестовое уведомление**

## 📞 **Поддержка**

Если настройка не работает:

1. **Проверьте диагностическую таблицу** в инструменте настройки
2. **Убедитесь, что все параметры показывают ✅**
3. **Проверьте логи сервера** на ошибки
4. **Убедитесь, что Service Account JSON валидный**

## 🔗 **Полезные ссылки**

- [Firebase Console](https://console.firebase.google.com/project/doska-a50b4)
- [Service Accounts](https://console.firebase.google.com/project/doska-a50b4/settings/serviceaccounts/adminsdk)
- [Project Settings](https://console.firebase.google.com/project/doska-a50b4/settings/general)
- [Cloud Messaging](https://console.firebase.google.com/project/doska-a50b4/settings/cloudmessaging)
