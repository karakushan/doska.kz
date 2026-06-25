# AGENTS.md

## Basic

- Використовуй Docker середовище для тестування і виконання команд для WordPress 
- сайт объявлений работает на плагине Directorypress
- для перевода используется TranslatePress
- ты можешь править полько файлы активной темы запрещено править файлы плагина или ядра Вордпресс без прямого указания

## Test Credentials

### Database (MySQL)
- Host: db (inside Docker) / localhost:3306
- Database: wordpress
- User: wordpress
- Password: wordpress
- Root password: rootpassword

### Test Users

#### Admin
- URL: http://localhost:8080/wp-admin/
- Login: admindsk
- Password: test123456

#### Subscriber (verified, auto-verify test)
- Login: test_browser
- Password: Test123456!

### Docker containers
- doska_wordpress (port 8080)
- doska_mysql
- doska_phpmyadmin (port 8081)
- doska_wp_cron
