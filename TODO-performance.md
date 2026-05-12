# TODO Performance Audit

Дата аудита: 2026-05-12
Сайт: `adshelppro.com`

## P0 Critical

- [x] Выключить production debug в удалённом `wp-config.php`.
Причина: сейчас `WP_DEBUG=true` и `WP_DEBUG_LOG=true`, из-за этого production пишет в `wp-content/debug.log` и создаёт лишнюю нагрузку на PHP и диск.

- [x] Исправить fatal `Object of class WP_Error could not be converted to string` в [wordpress/wp-content/themes/classiadspro/includes/actions/general.php](/Users/admin/Documents/dev/doska.kz/wordpress/wp-content/themes/classiadspro/includes/actions/general.php:261).
Причина: это реальная критическая ошибка на фронте.

- [x] Исправить конфликт `Cannot redeclare rs_flag_emoji()` в [wordpress/wp-content/themes/classiadspro/functions.php](/Users/admin/Documents/dev/doska.kz/wordpress/wp-content/themes/classiadspro/functions.php:2259).
Статус: в текущем состоянии продакшена конфликтующий `region-selector` отсутствует, функция `rs_flag_emoji()` в активной теме и плагинах не обнаружена, поэтому конфликт не воспроизводится.

## P1 High

- [ ] Стабилизировать `WP Super Cache` и проверить, можно ли перевести его в более быстрый режим.
Текущее состояние: `WP Super Cache` уже работает, cache-файлы создаются, но сейчас это не `mod_rewrite`-режим.
Примечание: 2026-05-12 была выполнена попытка перевода в `mod_rewrite`, но внешний запрос к главной начинал зависать до таймаута. Изменения на сервере откатены, сайт оставлен в рабочем `PHP`-режиме кеширования.

- [x] Проверить и уменьшить число страниц, которые обходят page cache из-за cookies.
Статус: в теме убрано автоматическое выставление `rs_currency` на первом хите. Контрольный запрос к главной без cookie jar больше не получает `Set-Cookie`.

- [ ] Проверить, насколько часто вызывается `wp_cache_flush()` в [wordpress/wp-content/themes/classiadspro/functions.php](/Users/admin/Documents/dev/doska.kz/wordpress/wp-content/themes/classiadspro/functions.php:3315).
Причина: частый flush может сводить пользу object cache почти к нулю.

## P2 Medium

- [ ] Разобраться с ранней загрузкой переводов (`classiadspro`, `classiads-templates`, `DIRECTORYPRESS`, `directorypress-advanced-fields`).
Причина: это не самая критичная проблема, но она создаёт notices и указывает на неправильный lifecycle хуков.

- [ ] Проверить связку `LiteSpeed Cache` + `WP Super Cache` и оставить чёткое разделение ролей.
Рекомендуемая модель на текущем хостинге: `WP Super Cache` для page cache, `LiteSpeed Cache` для object cache и оптимизаций, если они не конфликтуют.

- [x] Проверить наличие и объём `OPcache` на хостинге CityHost.
Статус: проверка выполнена на production `2026-05-12`.
Результат: `Zend OPcache` не загружен ни в `/usr/local/bin/lsphp` (`PHP 8.1.34`), ни в `/opt/alt/php74/usr/bin/lsphp` (`PHP 7.4.33`), ни в CLI-бинарях `php`.
Факты:
`lsphp` читает конфиги из `/opt/alt/php81/etc/php.ini` и `/opt/alt/php81/link/conf/default.ini`;
`alt-php74` читает `/opt/alt/php74/etc/php.ini` и `/opt/alt/php74/link/conf/default.ini`;
в `php -m` / `lsphp -m` модуль `opcache` отсутствует;
в `phpinfo`-выводе нет секции `Zend OPcache` и параметров `opcache.*`.
Вывод: сейчас фронт работает без `OPcache`, и это увеличивает стоимость каждого uncached PHP-запроса.

## P3 Cleanup

- [ ] Очистить или ротировать `wp-content/debug.log` после выключения production debug.
Причина: файл уже разросся и больше не нужен в таком виде после стабилизации.

- [x] Пересмотреть тяжёлые плагины и их фактическую необходимость.
Статус: аудит выполнен на production `2026-05-12`.
Факты:
активных плагинов `49`;
крупнейшие активные по размеру: `elementor` `81M`, `woocommerce` `70M`, `wpforms-lite` `42M`, `updraftplus` `31M`, `duplicator-pro` `29M`, `directorypress` `24M`, `firebase-push-notifications` `23M`, `wordfence` `22M`, `translatepress-multilingual` `10M`, `translatepress-business` `7.3M`, `wp-all-export` `6.8M`;
активен тяжёлый стек каталога: `directorypress` + `directorypress-advanced-fields` + `directorypress-claim-listing` + `directorypress-extended-locations` + `directorypress-frontend` + `directorypress-frontend-messages` + `directorypress-multidirectory` + `directorypress-payment-manager`;
активен тяжёлый стек переводов: `translatepress-multilingual` + `translatepress-business` + `automatic-translate-addon-pro-for-translatepress` + `hhg-for-translatepress` + `auto-translate-dp-listing-pro.php`;
активны сразу два тяжёлых backup/migration-инструмента: `updraftplus` и `duplicator-pro`;
активны security/SEO/admin-нагрузки: `wordfence`, `wordpress-seo`, `advanced-cron-manager`, `wp-maintenance-mode`.
Выводы:
`DirectoryPress` и его аддоны считать бизнес-критичными, отключать только после отдельной функциональной ревизии;
`TranslatePress`-стек считать высоким кандидатом на аудит, потому что он влияет и на фронт, и на записи в БД, и на кеш;
`Wordfence` оставить только если он действительно нужен на shared hosting; это один из первых кандидатов на снижение нагрузки;
`Elementor` оставить, если им реально собираются страницы; если используется только для нескольких шаблонов, это кандидат на сокращение зависимости;
`WP All Import` / `WP All Export` / `wpai-user-add-on` проверять по фактическому использованию; если импорты не выполняются регулярно, это кандидат на отключение вне окон импорта;
`UpdraftPlus` и `Duplicator Pro` не держать активными одновременно без чёткой причины;
`WPForms Lite`, `firebase-push-notifications`, `otter-blocks`, `header-footer-builder`, `header-footer-code-manager`, `ithemeland-bulk-posts-editing-lite`, `woo-order-test` требуют отдельной проверки на реальную необходимость.

- [ ] Повторно замерить `TTFB` для главной, карточки объявления, категории, админки и `wp-login.php` после исправлений.
Цель: подтвердить эффект по фактическим метрикам, а не только по наличию cache-файлов.

## Done

- [x] Подтверждено, что `Memcached` и объектное кеширование работают через `LiteSpeed Cache` drop-in.

- [x] Подтверждено, что `LiteSpeed Cache` не может дать полноценный server-level page cache на текущем `nginx + apache` хостинге без `QUIC.cloud` или LiteSpeed-сервера.

- [x] Подтверждено, что `WP Super Cache` включён и создаёт cache-файлы для фронта.
