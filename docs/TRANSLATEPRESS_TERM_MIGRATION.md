# TranslatePress Term Migration

Документ описує актуальний процес міграції термів `directorypress-category` у схему:

- `ru_RU` = source мова
- `en_US` = переклад
- `de_DE`, `es_ES`, `tr_TR`, `uk` = додаткові переклади

## Де лежить логіка

Основний код:

- [wordpress/wp-content/themes/classiadspro/includes/actions/term-ru-sync.php](/Users/admin/Documents/dev/doska.kz/wordpress/wp-content/themes/classiadspro/includes/actions/term-ru-sync.php)

Жорстка whitelist-карта для безпечної міграції:

- [wordpress/wp-content/themes/classiadspro/includes/actions/term-ru-sync-map.php](/Users/admin/Documents/dev/doska.kz/wordpress/wp-content/themes/classiadspro/includes/actions/term-ru-sync-map.php)

Підключення:

- [wordpress/wp-content/themes/classiadspro/functions.php](/Users/admin/Documents/dev/doska.kz/wordpress/wp-content/themes/classiadspro/functions.php)

## Який режим використовувати

Для цього проєкту основний і рекомендований режим:

- `classiadspro_migrate_terms_ru_source_strict`

Причина:

- історичні таблиці TranslatePress містять змішані та частково неконсистентні `original strings`
- евристичний режим може будувати неправильні пари `en -> ru`
- strict-режим працює тільки по жорстко заданій карті `term_id -> ru name/slug`

## Що саме мігрується

Скрипти працюють тільки з таксономією `directorypress-category` і тільки з даними термів:

- `wp_terms.name`
- `wp_terms.slug`
- `wp_term_taxonomy.description`
- `wp_termmeta`
- Yoast term SEO в `wp_options.option_name = wpseo_taxonomy_meta`
- таблиці TranslatePress `wp_trp_dictionary_*`
- таблиці TranslatePress `wp_trp_slug_originals`
- таблиці TranslatePress `wp_trp_slug_translations`

## Що не мігрується

- сторінки
- пости
- Elementor-контент сторінок
- gettext-рядки теми та плагінів
- довільні таблиці плагінів

## Важливе обмеження

Не можна просто переписати canonical term-дані на російську без синхронізації TranslatePress.

Інакше:

- російський текст почне відображатися у всіх мовах

Саме тому старий режим:

- `?classiadspro_sync_terms_ru=1`

заблокований навмисно.

## Перед запуском

1. Переконатися, що Docker-контейнери запущені.
2. Зробити SQL-бекап БД.
3. Спочатку запускати `dry-run`.
4. Потім лише `apply=1`.
5. Після кожного реального застосування перевіряти фронтенд на `ru`, `en`, `de`, `es`, `tr`, `uk`.

## Бекап бази даних

Повний дамп:

```bash
docker exec doska_mysql mysqldump --default-character-set=utf8mb4 -uwordpress -pwordpress wordpress > backups/translatepress-term-migration-$(date +%Y%m%d-%H%M%S).sql
```

Відновлення:

```bash
cat backups/translatepress-term-migration-YYYYMMDD-HHMMSS.sql | docker exec -i doska_mysql mysql --default-character-set=utf8mb4 -uwordpress -pwordpress wordpress
```

## Основний порядок міграції

Нормальний порядок для цього проєкту:

1. `dry-run` strict-міграції source-термів
2. `apply=1` strict-міграції source-термів
3. `dry-run` синхронізації інших мов
4. `apply=1` синхронізації інших мов
5. перевірка фронтенду

## 1. Strict-міграція в російський source

Це основний режим.

Він:

- переводить canonical `name` і `slug` термів у `ru_RU`
- працює тільки по whitelist-карті з `term-ru-sync-map.php`
- не намагається вгадувати переклади з брудних таблиць TranslatePress
- одночасно створює або оновлює `ru_RU -> en_US`
- одночасно створює або оновлює slug `ru_RU -> en_US`

### Dry-run

```text
http://localhost:8080/wp-admin/?classiadspro_migrate_terms_ru_source_strict=1&taxonomy=directorypress-category
```

### Реальне застосування

```text
http://localhost:8080/wp-admin/?classiadspro_migrate_terms_ru_source_strict=1&taxonomy=directorypress-category&apply=1
```

### Що дивитися у звіті

- `Mapped terms processed`
- `Term fields changed`
- `Dictionary ru_RU -> en_US rows changed`
- `Slug ru_RU -> en_US rows changed`

## 2. Синхронізація інших мов

Цей режим запускати тільки після strict-міграції source-термів.

Він:

- бере існуючі переклади `en_US -> de_DE/es_ES/tr_TR/uk`
- переносить їх у нову схему `ru_RU -> de_DE/es_ES/tr_TR/uk`
- синхронізує slug-и для цих мов

Він не робить машинний переклад. Якщо старого перекладу `en_US -> target language` не існувало, рядок буде пропущений.

### Dry-run

```text
http://localhost:8080/wp-admin/?classiadspro_sync_term_other_languages=1&taxonomy=directorypress-category
```

### Реальне застосування

```text
http://localhost:8080/wp-admin/?classiadspro_sync_term_other_languages=1&taxonomy=directorypress-category&apply=1
```

### Що дивитися у звіті

- `Languages`
- `Dictionary rows changed`
- `Slug rows changed`

## 3. Відновлення англійського source

Це аварійний режим.

Використовувати тільки якщо треба відкотити терми назад у `en_US` source.

### Запуск

```text
http://localhost:8080/wp-admin/?classiadspro_restore_terms_en=1&taxonomy=directorypress-category
```

Він:

- відновлює `name`
- відновлює `description`
- відновлює `slug`
- відновлює `termmeta`
- відновлює Yoast term SEO

## Евристичний режим

У коді ще існує режим:

- `classiadspro_migrate_terms_ru_source`

Але для цього проєкту він не рекомендований як основний.

Причина:

- історичні таблиці TP можуть давати неправильні зіставлення типу `Office -> Ofiice`
- тому для реальної міграції використовується strict-режим

## Відомий кейс зі slug

Під час реальної strict-міграції був зафіксований конфлікт slug для:

- `term_id = 469`

Причина:

- російський slug `продажа` вже був зайнятий іншим термом

Що це означає:

- назва терма могла оновитися
- але конкретний slug WordPress не дозволив зберегти через конфлікт унікальності

Такі кейси потрібно перевіряти окремо після застосування міграції.

## Перевірка після міграції

Після кожного `apply=1` перевірити:

1. `http://localhost:8080/ru/`
2. `http://localhost:8080/en/`
3. `http://localhost:8080/de/`
4. `http://localhost:8080/es/`
5. `http://localhost:8080/tr/`
6. `http://localhost:8080/uk/`

Мінімальний список перевірок:

- список категорій на головній
- archive/category сторінки DirectoryPress
- term description
- term SEO title
- term SEO description
- language switcher
- slug термів у URL

## Коли запускати повторно

Повторний запуск допустимий, якщо:

- додані нові категорії
- whitelist-карту розширено новими `term_id`
- були додані нові переклади `en_US -> other language`
- треба дозаповнити `ru_RU -> other language`

Усі повторні запуски починати з `dry-run`.

## Коли не запускати

Не запускати ці терм-скрипти, якщо:

- потрібна міграція сторінок
- потрібна міграція постів
- треба повністю чистити `wp_trp_original_strings`
- потрібен масовий переклад без уже існуючих записів у TranslatePress

## Типовий сценарій для цього проєкту

1. Зробити бекап БД.
2. Запустити strict `dry-run`.
3. Перевірити звіт.
4. Запустити strict `apply=1`.
5. Перевірити `ru` і `en`.
6. Запустити sync `dry-run` для інших мов.
7. Перевірити звіт.
8. Запустити sync `apply=1`.
9. Перевірити `de`, `es`, `tr`, `uk`.
10. Окремо перевірити терми зі slug-конфліктами.

## Примітки по якості перекладів

- Після синхронізації інших мов у БД можуть залишатися дивні або слабкі переклади.
- Це не обов’язково проблема мігратора.
- Часто це наслідок того, що в самих старих таблицях TranslatePress уже зберігався неякісний переклад.

Якщо потрібно виправити якість `uk/de/es/tr`, це робиться окремим strict-pass для словників перекладу.
