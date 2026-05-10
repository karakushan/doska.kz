<?php
/**
 * Добавляет локацию для листинга DirectoryPress.
 * Записывает в кастомную таблицу wp_directorypress_locations_relation
 * которую плагин использует для фильтрации.
 *
 * Запуск: wp-admin/?add_tex_location=461&post=18495
 */

add_action('init', function () {

    if (!is_admin()) return;
    if (empty($_GET['add_tex_location'])) return;
    if (!current_user_can('manage_options')) wp_die('Нет доступа.');

    $post_id = intval($_GET['post'] ?? 0);
    $term_id = intval($_GET['add_tex_location']);

    if (!$post_id || !$term_id) {
        wp_die('Ошибка: не указан post или add_tex_location');
    }

    global $wpdb;

    // 1. Устанавливаем taxonomy term
    $tax_result = wp_set_object_terms($post_id, $term_id, 'directorypress-location', false);

    // 2. Обновляем post meta (вспомогательные поля)
    update_post_meta($post_id, '_location_id', $term_id);
    update_post_meta($post_id, 'directorypress-location', $term_id);
    update_post_meta($post_id, '_address_line_1', '');
    update_post_meta($post_id, '_address_line_2', '');
    update_post_meta($post_id, '_zip_or_postal_index', '');
    update_post_meta($post_id, '_additional_info', '');
    update_post_meta($post_id, '_manual_coords', 0);
    update_post_meta($post_id, '_map_coords_1', '');
    update_post_meta($post_id, '_map_coords_2', '');
    update_post_meta($post_id, '_map_zoom', '');

    // 3. ГЛАВНОЕ: записываем в кастомную таблицу DirectoryPress
    //    Без этого фильтр по локации НЕ работает!
    $table = $wpdb->prefix . 'directorypress_locations_relation';

    $existing_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$table} WHERE post_id = %d LIMIT 1",
        $post_id
    ));

    if ($existing_id) {
        // Обновляем существующую запись
        $result = $wpdb->update(
            $table,
            ['location_id' => $term_id],
            ['post_id'     => $post_id],
            ['%d'],
            ['%d']
        );
        $action_done = "ОБНОВЛЕНА запись id=$existing_id";
    } else {
        // Вставляем новую запись
        $result = $wpdb->insert(
            $table,
            [
                'post_id'            => $post_id,
                'location_id'        => $term_id,
                'address_line_1'     => '',
                'address_line_2'     => '',
                'zip_or_postal_index'=> '',
                'additional_info'    => '',
                'manual_coords'      => 0,
                'map_coords_1'       => 0.000000,
                'map_coords_2'       => 0.000000,
                'map_icon_file'      => '',
            ],
            ['%d','%d','%s','%s','%s','%s','%d','%f','%f','%s']
        );
        $action_done = "ВСТАВЛЕНА новая запись, id=" . $wpdb->insert_id;
    }

    // Вывод результата
    echo '<style>body{font-family:monospace;padding:20px;background:#0d1117;color:#c9d1d9;}</style>';
    echo '<h2 style="color:#58a6ff;">✅ Локация установлена</h2>';
    echo "<p><b>post_id:</b> $post_id</p>";
    echo "<p><b>location_id (term_id):</b> $term_id</p>";
    echo "<p><b>Taxonomy:</b> " . (is_wp_error($tax_result) ? '❌ ' . $tax_result->get_error_message() : '✅ OK') . "</p>";
    echo "<p><b>Таблица directorypress_locations_relation:</b> $action_done</p>";
    echo "<p><b>wpdb result:</b> " . var_export($result, true) . "</p>";
    if ($wpdb->last_error) echo "<p style='color:#f85149'><b>SQL ошибка:</b> {$wpdb->last_error}</p>";
    exit;
});
