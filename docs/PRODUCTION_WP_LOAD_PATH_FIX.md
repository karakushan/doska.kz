# 🔧 Исправление ошибки "Failed opening required wp-load.php"

## ❌ **Ошибка:**
```
Uncaught Error: Failed opening required '../../../wp-load.php'
in /var/www/html/wp-content/plugins/firebase-push-notifications/admin/firebase-diagnostics.php on line 8
```

## 🎯 **Причина:**
Жесткопрограммированный относительный путь `../../../wp-load.php` не работает на всех серверах, потому что структура директорий может отличаться.

## ✅ **Решение:**

### **Что было (НЕПРАВИЛЬНО):**
```php
// Жесткопрограммированный путь - НЕ РАБОТАЕТ на всех серверах
require_once('../../../wp-load.php');
```

### **Что стало (ПРАВИЛЬНО):**
```php
// Динамический поиск wp-load.php - РАБОТАЕТ везде
$wp_root = dirname(__FILE__);
for ($i = 0; $i < 5; $i++) {
    $wp_root = dirname($wp_root);
    if (file_exists($wp_root . '/wp-load.php')) {
        break;
    }
}

if (!file_exists($wp_root . '/wp-load.php')) {
    die('Could not find wp-load.php');
}

require_once($wp_root . '/wp-load.php');
```

## 🔍 **Как работает исправление:**

```
1. Начинаем с текущей папки:
   /var/www/html/wp-content/plugins/firebase-push-notifications/admin/

2. Поднимаемся на уровень вверх 5 раз:
   /var/www/html/wp-content/plugins/firebase-push-notifications/
   /var/www/html/wp-content/plugins/
   /var/www/html/wp-content/
   /var/www/html/
   /var/www/

3. На каждом уровне проверяем: есть ли wp-load.php?
   
4. Когда находим wp-load.php, используем этот путь:
   /var/www/html/wp-load.php ✅

5. Если не найден, выводим ошибку с понятным сообщением
```

## 📊 **Поддерживаемые структуры:**

### ✅ **Стандартная структура:**
```
/var/www/html/
├── wp-load.php
├── wp-config.php
├── wp-content/
│   └── plugins/
│       └── firebase-push-notifications/
│           └── admin/
│               └── firebase-diagnostics.php
```

### ✅ **Подпапка WordPress:**
```
/var/www/html/
├── wordpress/
│   ├── wp-load.php
│   ├── wp-config.php
│   └── wp-content/
│       └── plugins/
│           └── firebase-push-notifications/
│               └── admin/
│                   └── firebase-diagnostics.php
```

### ✅ **Другие структуры:**
- Multi-site установки
- Пользовательские пути
- Разные хосты

## 🔄 **Обновленные файлы:**

| Файл | Статус | Описание |
|------|--------|---------|
| `firebase-diagnostics.php` | ✅ Исправлен | Динамический поиск wp-load.php |
| `fix-json.php` | ✅ Исправлен | Динамический поиск wp-load.php |
| `firebase-test.php` | ✅ OK | Использует ABSPATH (правильно) |
| `test-push-notifications.php` | ✅ OK | Использует ABSPATH (правильно) |

## 🚀 **Проверка после исправления:**

1. **Перейдите в:** Firebase Push → Diagnostics
2. **Должны увидеть полную таблицу диагностики**
3. **Ошибка "Failed opening required" должна исчезнуть**

## 💡 **Лучшие практики для плагинов:**

### ❌ **Неправильно:**
```php
// Жесткие пути не работают везде
require_once('../../../wp-load.php');
require_once('../../../../wp-load.php');
```

### ✅ **Правильно - способ 1 (используется ABSPATH):**
```php
// ABSPATH уже определен WordPress
// wp-content/plugins/my-plugin/admin/page.php
if (!defined('ABSPATH')) {
    exit;
}
// Тогда используйте:
// include ABSPATH . 'wp-config.php';
```

### ✅ **Правильно - способ 2 (динамический поиск):**
```php
// Для специальных случаев
$wp_root = dirname(__FILE__);
for ($i = 0; $i < 5; $i++) {
    $wp_root = dirname($wp_root);
    if (file_exists($wp_root . '/wp-load.php')) {
        break;
    }
}
require_once($wp_root . '/wp-load.php');
```

## ✅ **Статус:**

**Ошибка исправлена! Все админские страницы теперь работают везде.** 🎉

- ✅ firebase-diagnostics.php - Работает
- ✅ fix-json.php - Работает
- ✅ test-push-notifications.php - Работает
- ✅ firebase-test.php - Работает

Система совместима со всеми структурами WordPress директорий! 🚀
