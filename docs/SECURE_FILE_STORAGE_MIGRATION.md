# 🔐 Миграция на безопасное хранение Service Account JSON

## ✅ **Миграция завершена!**

### 🎯 **Что изменилось:**

#### **Было (НЕБЕЗОПАСНО):**
```
WordPress Database (wp_options)
├── firebase_service_account_json = "{ полный JSON текст с приватным ключом }"
```

#### **Стало (БЕЗОПАСНО):**
```
WordPress Database (wp_options)
├── firebase_service_account_file_path = "/var/www/uploads/firebase-push-notifications/service-account-1234567890.json"

Файловая система (0600 - только WordPress)
├── /var/www/uploads/firebase-push-notifications/service-account-1234567890.json
```

## 🔧 **Как это работает:**

### **1. Загрузка файла**
```
User uploads JSON from Firebase Console
    ↓
Validation (JSON format, required fields)
    ↓
File copies to: /wp-content/uploads/firebase-push-notifications/
    ↓
File permissions: 0600 (readable only by WordPress)
    ↓
File path saves to: wp_options
```

### **2. Использование Firebase**
```
Firebase initialization
    ↓
Read file path from database
    ↓
Load JSON from protected file (0600)
    ↓
Parse and validate
    ↓
Initialize Firebase
```

## 📁 **Структура хранилища:**

```
/wp-content/uploads/
└── firebase-push-notifications/
    ├── service-account-1734267890.json (0600)
    ├── service-account-1734267891.json (0600)
    └── service-account-1734267892.json (0600)
```

## 🚀 **Следующие шаги:**

### **Шаг 1: Активировать плагин**
Убедитесь, что плагин Firebase Push Notifications активирован:
1. WordPress Admin → Плагины
2. Firebase Push Notifications → Активировать

### **Шаг 2: Загрузить Service Account JSON**
1. **Firefox Push → Configuration**
2. **Загрузите JSON файл из Firebase Console**
3. **Нажмите "Сохранить"**

Система автоматически:
- ✅ Скопирует файл в защищенную папку
- ✅ Установит права доступа 0600
- ✅ Сохранит путь к файлу в БД
- ✅ Инициализирует Firebase

### **Шаг 3: Проверить статус**
1. **Firebase Push → Configuration**
2. **Проверить диагностическую таблицу**
3. **Firebase должен показывать ✅ Инициализирован**

## ✅ **Преимущества:**

### **🔒 Безопасность:**
- ✅ Приватный ключ не в БД
- ✅ Файл защищен правами 0600
- ✅ Защита от утечек БД
- ✅ Невозможно прочитать через браузер

### **📊 Производительность:**
- ✅ Быстрая загрузка (путь вместо JSON)
- ✅ Меньше данных в БД
- ✅ Быстрее поиск в БД

### **🔄 Гибкость:**
- ✅ Легко заменить ключ
- ✅ Просто загрузить новый файл
- ✅ Старый файл заменится автоматически

### **💾 Резервная копия:**
- ✅ БД можно безопасно экспортировать
- ✅ Нет чувствительных данных
- ✅ Файл управляется отдельно

## 📋 **Опции в БД:**

### **Старые (удалены):**
```php
// Больше НЕ используются:
get_option('firebase_service_account_json');
```

### **Новые:**
```php
// Используется ВСЕ, но только путь:
$file_path = get_option('firebase_service_account_file_path');
// /var/www/uploads/firebase-push-notifications/service-account-1234567890.json

// Чтение содержимого при необходимости:
$json = file_get_contents($file_path);
$data = json_decode($json, true);
```

## 🔍 **Файловая иерархия:**

```
WordPress Installation
├── wp-config.php
├── wp-content/
│   ├── plugins/
│   │   └── firebase-push-notifications/
│   ├── themes/
│   └── uploads/
│       └── firebase-push-notifications/ (NEW)
│           └── service-account-[timestamp].json (0600)
└── wp-admin/
```

## 🎯 **Использование в коде:**

```php
// Получить путь
$file_path = get_option('firebase_service_account_file_path');

// Проверить существование
if (file_exists($file_path) && is_readable($file_path)) {
    // Загрузить JSON
    $json_content = file_get_contents($file_path);
    $service_account = json_decode($json_content, true);
    
    // Инициализировать Firebase
    $factory = new \Kreait\Firebase\Factory();
    $firebase = $factory->withServiceAccount($service_account)->create();
}
```

## 📚 **Связанные файлы:**

- `class-firebase-manager.php` - Обновлен для загрузки из файла
- `firebase-config.php` - Обновлена форма загрузки
- `fix-json.php` - Копирует файл в защищенную папку
- `SERVICE_ACCOUNT_FILE_STORAGE_SECURITY.md` - Подробно о безопасности

## ⚠️ **Важно:**

### **При обновлении плагина:**
- ✅ Старые файлы остаются на сервере (не удаляются)
- ✅ Путь в БД сохраняется
- ✅ Firebase продолжит работать

### **При деактивации плагина:**
- ⚠️ Файлы НЕ удаляются (безопасно)
- ⚠️ Путь в БД сохраняется
- ⚠️ При переактивации все работает как раньше

### **При удалении плагина:**
- Вы должны вручную удалить папку:
  ```bash
  rm -rf /wp-content/uploads/firebase-push-notifications/
  ```

## ✅ **Статус миграции:**

| Компонент | Статус | Описание |
|-----------|--------|---------|
| FirebaseManager | ✅ Обновлен | Загружает JSON из файла |
| Firebase Config Page | ✅ Обновлена | Сохраняет только путь |
| Fix JSON Tool | ✅ Обновлен | Копирует в protected folder |
| File Permissions | ✅ Установлены | 0600 (только WordPress) |
| Database Cleanup | ⏳ Ручно | Удалить старые значения (опционально) |

## 🚀 **Готово к использованию!**

Система полностью мигрирована на безопасное хранение файлов. 🎉
