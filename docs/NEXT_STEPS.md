# 🚀 Firebase Push Notifications - Готово к настройке!

## ✅ Что уже сделано:

1. **Ваши данные Firebase добавлены в настройки темы:**
   - Project ID: `doska-a50b4`
   - API Key: `YOUR_API_KEY_REMOVED`
   - Messaging Sender ID: `927038207069`
   - App ID: `1:927038207069:web:38e3755d76e75b379c49b4`

2. **Функционал полностью интегрирован в тему**
3. **JavaScript файлы обновлены с вашими данными**
4. **Создан тестовый файл** `firebase-test.html`

## 🔑 Что нужно сделать сейчас:

### 1. Получить VAPID Key
1. Перейдите в [Firebase Console](https://console.firebase.google.com/)
2. Выберите проект `doska-a50b4`
3. **Cloud Messaging** → **Web Push certificates**
4. Нажмите **Generate key pair** (если не создан)
5. Скопируйте **Key pair**

### 2. Получить Service Account JSON
1. **Project Settings** → **Service accounts**
2. **Generate new private key**
3. Скачайте JSON файл
4. Скопируйте содержимое

### 3. Настроить в WordPress
1. **Appearance** → **Theme Settings**
2. **Firebase Push Notifications**
3. Включите переключатель
4. Вставьте VAPID Key и Service Account JSON
5. Сохраните

## 🧪 Тестирование:

### Вариант 1: Тестовый файл
Откройте `firebase-test.html` в браузере для быстрого тестирования

### Вариант 2: На сайте
1. Войдите как пользователь
2. **My Dashboard** → **Accounts** → **Notification Settings**
3. Разрешите уведомления
4. Протестируйте отправку сообщения

## 📱 Типы уведомлений:

- ✅ **Новые сообщения** - при получении сообщения
- ✅ **Окончание рекламы** - при истечении платного объявления
- ✅ **Деактивация** - при деактивации объявления

## 🔧 Отладка:

Если что-то не работает:
1. Проверьте консоль браузера
2. Убедитесь, что VAPID ключ правильный
3. Проверьте Service Account JSON
4. Убедитесь, что пользователь разрешил уведомления

## 📊 Готово к использованию!

После настройки VAPID ключа и Service Account JSON система будет полностью готова к работе! 🎉
