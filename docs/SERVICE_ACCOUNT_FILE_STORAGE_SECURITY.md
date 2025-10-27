# 🔐 Безопасное хранение Service Account JSON

## 📊 **Обновленный подход:**

### ❌ **Старый способ (НЕБЕЗОПАСНЫЙ):**
- ❌ Service Account JSON хранился полностью в базе данных
- ❌ Риск утечки приватного ключа при экспорте БД
- ❌ JSON видел каждый, кто может получить доступ к БД
- ❌ Медленная загрузка больших JSON файлов

### ✅ **Новый способ (БЕЗОПАСНЫЙ):**
- ✅ В базе данных хранится только **путь к файлу**
- ✅ Сам Service Account JSON файл хранится на сервере
- ✅ Файл имеет ограниченные права доступа (0600)
- ✅ JSON загружается только при инициализации Firebase
- ✅ Безопасность: файл защищен от прямого доступа через интернет

## 🎯 **Как это работает:**

### 1. **Загрузка файла**
```
User uploads JSON file from Firebase Console
        ↓
File validation (type, JSON format, required fields)
        ↓
File copies to: /uploads/firebase-push-notifications/service-account-[timestamp].json
        ↓
File permissions set to 0600 (readable only by server)
        ↓
File path saved to database: /path/to/uploads/firebase-push-notifications/service-account-1234567890.json
```

### 2. **Инициализация Firebase**
```
Firebase initialization request
        ↓
Read file path from database
        ↓
Load JSON from file (0600 restricted access)
        ↓
Parse and validate JSON
        ↓
Initialize Firebase with service account
        ↓
Firebase ready for operations
```

### 3. **Хранение в БД**
```
Таблица wp_options:
┌─────────────────────────────────────────┐
│ option_name: firebase_service_account_file_path │
│ option_value: /var/www/uploads/firebase-push-notifications/service-account-1234567890.json │
└─────────────────────────────────────────┘
```

## 🔒 **Преимущества безопасности:**

### **1. Защита от утечек БД:**
- ✅ Если БД скомпрометирована, приватный ключ остается на месте
- ✅ Путь к файлу бесполезен без доступа к файловой системе
- ✅ Двойной уровень защиты (БД + файловая система)

### **2. Файловые права доступа:**
- ✅ Режим 0600 - чтение только владельцем (www-data)
- ✅ Невозможно прочитать через веб-сервер
- ✅ Защищено от прямого доступа через браузер

### **3. Резервное копирование:**
- ✅ БД можно экспортировать/синхронизировать без риска
- ✅ Нет чувствительных данных в экспортах
- ✅ Файл управляется отдельно и безопаснее

### **4. Ротация ключей:**
- ✅ Легко заменить файл на новый
- ✅ Просто загрузите новый JSON
- ✅ Старый файл автоматически заменяется

## 📁 **Структура файлов:**

```
WordPress root
├── wp-content/
│   ├── plugins/firebase-push-notifications/
│   └── uploads/
│       └── firebase-push-notifications/
│           ├── service-account-1734267890.json (0600)
│           ├── service-account-1734267891.json (0600)
│           └── service-account-1734267892.json (0600)
```

## 🚀 **Использование:**

### **Загрузить новый Service Account JSON:**
1. **Firebase Push → Configuration**
2. **Выберите JSON файл из Firebase Console**
3. **Нажмите "Сохранить"**
4. **Файл автоматически:**
   - Скопируется в `/uploads/firebase-push-notifications/`
   - Получит ограниченные права (0600)
   - Путь сохранится в БД

### **Проверить текущий файл:**
```php
// Получить путь к файлу
$file_path = get_option('firebase_service_account_file_path');
echo $file_path; // /var/www/uploads/firebase-push-notifications/service-account-1234567890.json

// Проверить, что файл существует
if (file_exists($file_path) && is_readable($file_path)) {
    echo "✅ File is accessible";
}

// Получить содержимое файла (если нужно)
$json = file_get_contents($file_path);
```

## ✅ **Статус:**

| Параметр | Статус |
|----------|--------|
| Хранение в БД | 🔐 Только путь |
| JSON на диске | 🔐 Защищен (0600) |
| Автоматическая ротация | ✅ Да |
| Резервное копирование | ✅ Безопасно |
| Утечка приватного ключа | ✅ Маловероятно |

## 📚 **Связанные документы:**
- `FIREBASE_CONFIGURATION_GUIDE.md` - Руководство по настройке
- `JSON_SYNTAX_ERROR_FIX.md` - Исправление ошибок JSON
- `SERVICE_ACCOUNT_FILE_UPLOAD.md` - Загрузка файлов
