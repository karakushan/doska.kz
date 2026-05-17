# TranslatePress Page Migration

Документ описывает миграцию страниц в схему:

- `ru_RU` = source язык
- `en_US` = перевод
- `de_DE`, `es_ES`, `tr_TR`, `uk` = дополнительные переводы

Скрипт находится в:

- [wordpress/wp-content/themes/classiadspro/includes/actions/page-ru-sync.php](/Users/admin/Documents/dev/doska.kz/wordpress/wp-content/themes/classiadspro/includes/actions/page-ru-sync.php)

## Главное отличие от term-миграции

Для страниц запуск выполняется только по явному списку `page_ids`.

Это сделано намеренно, потому что в базе уже есть языковые дубли страниц, например:

- `Home`
- `Головна`
- `Главная`
- `Heim`
- `Hogar`
- `Main`

Массовый запуск по всем страницам небезопасен и может испортить языковые дубли.

## Что мигрируется

Скрипт работает только с `post_type = page` и только по указанным `page_ids`.

Мигрируются:

- `wp_posts.post_title`
- `wp_posts.post_name`
- `wp_posts.post_excerpt`
- `wp_posts.post_content`
- `wp_postmeta`
- Yoast page SEO в `wp_postmeta`
- строки TranslatePress, привязанные к странице через `wp_trp_original_meta.post_parent_id`
- slug-переводы страницы в TranslatePress

## Что не нужно делать

Не запускать page-скрипт сразу по всем страницам.

Сначала выбрать конкретные source-страницы, которые должны стать русскими canonical страницами.

## Перед запуском

1. Сделать бэкап БД.
2. Выбрать конкретные `page_ids`.
3. Сначала запускать `dry-run`.
4. Затем только `apply=1`.
5. Проверить каждую страницу на `ru`, `en`, `de`, `es`, `tr`, `uk`.

## Как узнать page IDs

Пример SQL:

```sql
SELECT ID, post_title, post_name
FROM wp_posts
WHERE post_type = 'page'
  AND post_status NOT IN ('auto-draft', 'trash')
ORDER BY ID;
```

Или через Docker:

```bash
docker exec doska_mysql mysql --default-character-set=utf8mb4 -uwordpress -pwordpress -D wordpress -e "SELECT ID, post_title, post_name FROM wp_posts WHERE post_type='page' AND post_status NOT IN ('auto-draft','trash') ORDER BY ID;"
```

## 1. Миграция в русский source + английский перевод

Этот режим:

- конвертирует данные выбранных страниц в русский source
- обновляет `post_title`, `post_name`, `post_excerpt`, `post_content`
- обновляет `postmeta`, включая Yoast и Elementor-данные
- создаёт или обновляет `ru_RU -> en_US`
- создаёт или обновляет slug `ru_RU -> en_US`

### Dry-run

```text
http://localhost:8080/wp-admin/?classiadspro_migrate_pages_ru_source=1&page_ids=12836,597,8605
```

### Реальное применение

```text
http://localhost:8080/wp-admin/?classiadspro_migrate_pages_ru_source=1&page_ids=12836,597,8605&apply=1
```

### Что смотреть в отчёте

- `Post fields changed`
- `Post meta rows changed`
- `Dictionary ru_RU -> en_US rows changed`
- `Slug ru_RU -> en_US rows changed`

## 2. Синхронизация остальных языков

Этот режим:

- берёт существующие переводы `en_US -> de_DE/es_ES/tr_TR/uk`
- переносит их в `ru_RU -> de_DE/es_ES/tr_TR/uk`
- синхронизирует slug-переводы страниц
- использует строки TranslatePress, привязанные к `post_parent_id`

### Dry-run

```text
http://localhost:8080/wp-admin/?classiadspro_sync_page_other_languages=1&page_ids=12836,597,8605
```

### Реальное применение

```text
http://localhost:8080/wp-admin/?classiadspro_sync_page_other_languages=1&page_ids=12836,597,8605&apply=1
```

## 3. Восстановление английского source

Аварийный режим для отката выбранных страниц обратно в английский source.

### Dry-run

```text
http://localhost:8080/wp-admin/?classiadspro_restore_pages_en=1&page_ids=12836,597,8605
```

### Реальное применение

```text
http://localhost:8080/wp-admin/?classiadspro_restore_pages_en=1&page_ids=12836,597,8605&apply=1
```

## Рекомендуемый порядок запуска

1. Сделать бэкап БД.
2. Выбрать только source-страницы.
3. Запустить `classiadspro_migrate_pages_ru_source` без `apply`.
4. Проверить отчёт.
5. Запустить `classiadspro_migrate_pages_ru_source` с `apply=1`.
6. Проверить `ru` и `en`.
7. Запустить `classiadspro_sync_page_other_languages` без `apply`.
8. Проверить отчёт.
9. Запустить `classiadspro_sync_page_other_languages` с `apply=1`.
10. Проверить `de`, `es`, `tr`, `uk`.

## Что проверить после применения

- заголовок страницы
- slug страницы
- контент страницы
- Elementor-блоки
- внутренние ссылки в контенте
- Yoast title
- Yoast meta description
- переключение языка

## Ограничение

Скрипт не пытается автоматически определить, какая из нескольких однотипных страниц является “главной source-страницей”.

Это решение принимает разработчик вручную через список `page_ids`.
