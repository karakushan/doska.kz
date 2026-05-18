<?php

require_once get_template_directory() . '/includes/actions/term-ru-sync-map.php';

function classiadspro_term_sync_contains_cyrillic($value)
{
	return is_string($value) && $value !== '' && preg_match('/\p{Cyrillic}/u', $value);
}

function classiadspro_term_sync_should_translate_text($value)
{
	if (!is_string($value) || $value === '') {
		return false;
	}

	if (classiadspro_term_sync_contains_cyrillic($value)) {
		return false;
	}

	return (bool) preg_match('/[A-Za-z]/', html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

function classiadspro_term_sync_get_trp_maps()
{
	static $maps = null;

	if (is_array($maps)) {
		return $maps;
	}

	global $wpdb;

	$dictionary_table = $wpdb->prefix . 'trp_dictionary_en_us_ru_ru';
	$reverse_dictionary_table = $wpdb->prefix . 'trp_dictionary_ru_ru_en_us';
	$slug_originals_table = $wpdb->prefix . 'trp_slug_originals';
	$slug_translations_table = $wpdb->prefix . 'trp_slug_translations';

	$maps = array(
		'dictionary' => array(),
		'dictionary_reverse' => array(),
		'slugs' => array(),
		'slugs_reverse' => array(),
	);

	$dictionary_exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $dictionary_table));
	if ($dictionary_exists === $dictionary_table) {
		$rows = $wpdb->get_results(
			"SELECT original, translated
			FROM {$dictionary_table}
			WHERE status > 0
				AND translated IS NOT NULL
				AND translated <> ''",
			ARRAY_A
		);

		foreach ($rows as $row) {
			$original = (string) $row['original'];
			$translated = (string) $row['translated'];

			if ($original === '' || $translated === '') {
				continue;
			}

			if (!isset($maps['dictionary'][$original]) && $translated !== $original) {
				$maps['dictionary'][$original] = $translated;
			}

			if (!isset($maps['dictionary_reverse'][$translated]) && $translated !== $original) {
				$maps['dictionary_reverse'][$translated] = $original;
			}
		}
	}

	$reverse_dictionary_exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $reverse_dictionary_table));
	if ($reverse_dictionary_exists === $reverse_dictionary_table) {
		$rows = $wpdb->get_results(
			"SELECT original, translated
			FROM {$reverse_dictionary_table}
			WHERE status > 0
				AND translated IS NOT NULL
				AND translated <> ''",
			ARRAY_A
		);

		foreach ($rows as $row) {
			$russian = (string) $row['original'];
			$english = (string) $row['translated'];

			if ($russian === '' || $english === '') {
				continue;
			}

			if (!isset($maps['dictionary'][$english]) && $english !== $russian) {
				$maps['dictionary'][$english] = $russian;
			}

			if (!isset($maps['dictionary_reverse'][$russian]) && $english !== $russian) {
				$maps['dictionary_reverse'][$russian] = $english;
			}
		}
	}

	$fallback_languages = array('de_de', 'es_es', 'tr_tr', 'uk');
	$fallback_candidates = array();

	foreach ($fallback_languages as $language_suffix) {
		$en_table = $wpdb->prefix . 'trp_dictionary_en_us_' . $language_suffix;
		$ru_table = $wpdb->prefix . 'trp_dictionary_ru_ru_' . $language_suffix;

		$en_exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $en_table));
		$ru_exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $ru_table));

		if ($en_exists !== $en_table || $ru_exists !== $ru_table) {
			continue;
		}

		$en_rows = $wpdb->get_results(
			"SELECT original, translated
			FROM {$en_table}
			WHERE status > 0
				AND translated IS NOT NULL
				AND translated <> ''",
			ARRAY_A
		);

		$ru_rows = $wpdb->get_results(
			"SELECT original, translated
			FROM {$ru_table}
			WHERE status > 0
				AND translated IS NOT NULL
				AND translated <> ''",
			ARRAY_A
		);

		$translated_to_russian = array();
		foreach ($ru_rows as $row) {
			$russian = (string) $row['original'];
			$translated = (string) $row['translated'];

			if ($russian === '' || $translated === '' || $russian === $translated) {
				continue;
			}

			if (!isset($translated_to_russian[$translated])) {
				$translated_to_russian[$translated] = $russian;
			}
		}

		foreach ($en_rows as $row) {
			$english = (string) $row['original'];
			$translated = (string) $row['translated'];

			if ($english === '' || $translated === '' || !isset($translated_to_russian[$translated])) {
				continue;
			}

			$russian = $translated_to_russian[$translated];

			if ($english === $russian) {
				continue;
			}

			if (!isset($fallback_candidates[$english])) {
				$fallback_candidates[$english] = array();
			}

			if (!isset($fallback_candidates[$english][$russian])) {
				$fallback_candidates[$english][$russian] = 0;
			}

			$fallback_candidates[$english][$russian]++;
		}
	}

	foreach ($fallback_candidates as $english => $russian_candidates) {
		arsort($russian_candidates);
		$top_russian = key($russian_candidates);
		$top_votes = current($russian_candidates);

		if ($top_votes < 2) {
			continue;
		}

		$values = array_values($russian_candidates);
		if (isset($values[1]) && $values[1] === $top_votes) {
			continue;
		}

		if (!isset($maps['dictionary'][$english])) {
			$maps['dictionary'][$english] = $top_russian;
		}

		if (!isset($maps['dictionary_reverse'][$top_russian])) {
			$maps['dictionary_reverse'][$top_russian] = $english;
		}
	}

	$slug_tables_exist = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $slug_originals_table));
	if ($slug_tables_exist === $slug_originals_table) {
		$rows = $wpdb->get_results(
			"SELECT so.original, st.translated
			FROM {$slug_originals_table} so
			INNER JOIN {$slug_translations_table} st ON st.original_id = so.id
			WHERE st.language = 'ru_RU'
				AND st.status > 0
				AND st.translated IS NOT NULL
				AND st.translated <> ''",
			ARRAY_A
		);

		foreach ($rows as $row) {
			$original = (string) $row['original'];
			$translated = urldecode((string) $row['translated']);

			if ($original === '' || $translated === '') {
				continue;
			}

			$maps['slugs'][$original] = $translated;

			if (!isset($maps['slugs_reverse'][$translated])) {
				$maps['slugs_reverse'][$translated] = $original;
			}
		}
	}

	return $maps;
}

function classiadspro_term_sync_translate_string($value, $dictionary)
{
	if (!classiadspro_term_sync_should_translate_text($value)) {
		return $value;
	}

	if (!isset($dictionary[$value])) {
		return $value;
	}

	$translated = (string) $dictionary[$value];

	if ($translated === '' || $translated === $value) {
		return $value;
	}

	return $translated;
}

function classiadspro_term_sync_restore_string($value, $reverse_dictionary)
{
	if (!is_string($value) || $value === '') {
		return $value;
	}

	$candidates = array(
		$value,
		html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
	);

	foreach ($candidates as $candidate) {
		if (isset($reverse_dictionary[$candidate])) {
			$restored = (string) $reverse_dictionary[$candidate];

			if ($restored !== '' && $restored !== $value) {
				return $restored;
			}
		}
	}

	return $value;
}

function classiadspro_term_sync_translate_deep($value, $dictionary)
{
	if (is_string($value)) {
		return classiadspro_term_sync_translate_string($value, $dictionary);
	}

	if (!is_array($value)) {
		return $value;
	}

	foreach ($value as $key => $item) {
		$value[$key] = classiadspro_term_sync_translate_deep($item, $dictionary);
	}

	return $value;
}

function classiadspro_term_sync_restore_deep($value, $reverse_dictionary)
{
	if (is_string($value)) {
		return classiadspro_term_sync_restore_string($value, $reverse_dictionary);
	}

	if (!is_array($value)) {
		return $value;
	}

	foreach ($value as $key => $item) {
		$value[$key] = classiadspro_term_sync_restore_deep($item, $reverse_dictionary);
	}

	return $value;
}

function classiadspro_term_sync_restore_slug($slug, $reverse_slugs)
{
	if (!is_string($slug) || $slug === '') {
		return $slug;
	}

	$candidates = array(
		$slug,
		urldecode($slug),
		rawurldecode($slug),
	);

	$expanded_candidates = array();
	foreach ($candidates as $candidate) {
		$expanded_candidates[] = $candidate;
		$expanded_candidates[] = preg_replace('/-\d+$/', '', $candidate);
	}

	foreach (array_unique($expanded_candidates) as $candidate) {
		if (isset($reverse_slugs[$candidate])) {
			$restored = (string) $reverse_slugs[$candidate];

			if ($restored !== '' && $restored !== $slug) {
				return $restored;
			}
		}
	}

	return $slug;
}

function classiadspro_term_sync_get_raw_terms($taxonomy)
{
	global $wpdb;

	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT t.term_id, t.name, t.slug, tt.description, tt.term_taxonomy_id
			FROM {$wpdb->terms} t
			INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id
			WHERE tt.taxonomy = %s
			ORDER BY t.term_id ASC",
			$taxonomy
		)
	);

	return is_array($rows) ? $rows : array();
}

function classiadspro_term_sync_resolve_ru_source_string($value, $maps, &$pairs)
{
	if (!is_string($value) || $value === '') {
		return $value;
	}

	if (classiadspro_term_sync_contains_cyrillic($value)) {
		$english = classiadspro_term_sync_restore_string($value, $maps['dictionary_reverse']);

		if ($english !== $value && $english !== '') {
			$pairs[$value] = $english;
		}

		return $value;
	}

	$russian = classiadspro_term_sync_translate_string($value, $maps['dictionary']);

	if ($russian !== $value && $russian !== '') {
		$pairs[$russian] = $value;
		return $russian;
	}

	return $value;
}

function classiadspro_term_sync_migrate_value_to_ru_source($value, $maps, &$pairs)
{
	if (is_string($value)) {
		return classiadspro_term_sync_resolve_ru_source_string($value, $maps, $pairs);
	}

	if (!is_array($value)) {
		return $value;
	}

	foreach ($value as $key => $item) {
		$value[$key] = classiadspro_term_sync_migrate_value_to_ru_source($item, $maps, $pairs);
	}

	return $value;
}

function classiadspro_term_sync_resolve_ru_source_slug($slug, $maps, &$pairs)
{
	if (!is_string($slug) || $slug === '') {
		return $slug;
	}

	$decoded_slug = urldecode($slug);

	if (classiadspro_term_sync_contains_cyrillic($decoded_slug)) {
		$english = classiadspro_term_sync_restore_slug($slug, $maps['slugs_reverse']);

		if ($english !== $slug && $english !== '') {
			$pairs[$decoded_slug] = $english;
		}

		return $decoded_slug;
	}

	if (isset($maps['slugs'][$slug]) && $maps['slugs'][$slug] !== '') {
		$russian = $maps['slugs'][$slug];
		$pairs[$russian] = $slug;

		return $russian;
	}

	return $slug;
}

function classiadspro_term_sync_upsert_dictionary_row($table, $original, $translated, $apply)
{
	global $wpdb;

	if (!is_string($original) || !is_string($translated) || $original === '' || $translated === '' || $original === $translated) {
		return false;
	}

	$row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT id, translated
			FROM {$table}
			WHERE original = %s
			ORDER BY id ASC
			LIMIT 1",
			$original
		),
		ARRAY_A
	);

	if ($row) {
		if ((string) $row['translated'] === $translated) {
			return false;
		}

		if ($apply) {
			$wpdb->update(
				$table,
				array(
					'translated' => $translated,
					'status' => 1,
					'block_type' => 0,
				),
				array(
					'id' => (int) $row['id'],
				),
				array('%s', '%d', '%d'),
				array('%d')
			);
		}

		return true;
	}

	if ($apply) {
		$wpdb->insert(
			$table,
			array(
				'original' => $original,
				'translated' => $translated,
				'status' => 1,
				'block_type' => 0,
			),
			array('%s', '%s', '%d', '%d')
		);
	}

	return true;
}

function classiadspro_term_sync_ensure_slug_original_id($slug_originals_table, $slug, $apply)
{
	global $wpdb;

	$id = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT id
			FROM {$slug_originals_table}
			WHERE original = %s
			LIMIT 1",
			$slug
		)
	);

	if ($id) {
		return (int) $id;
	}

	if (!$apply) {
		return 0;
	}

	$wpdb->insert(
		$slug_originals_table,
		array(
			'original' => $slug,
			'type' => 'other',
		),
		array('%s', '%s')
	);

	return (int) $wpdb->insert_id;
}

function classiadspro_term_sync_upsert_slug_translation($slug_originals_table, $slug_translations_table, $source_slug, $translated_slug, $language, $apply)
{
	global $wpdb;

	if (!is_string($source_slug) || !is_string($translated_slug) || $source_slug === '' || $translated_slug === '' || $source_slug === $translated_slug) {
		return false;
	}

	$original_id = classiadspro_term_sync_ensure_slug_original_id($slug_originals_table, $source_slug, $apply);

	if (!$original_id && !$apply) {
		return true;
	}

	if (!$original_id) {
		return false;
	}

	$row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT id, translated
			FROM {$slug_translations_table}
			WHERE original_id = %d
				AND language = %s
			ORDER BY id ASC
			LIMIT 1",
			$original_id,
			$language
		),
		ARRAY_A
	);

	if ($row) {
		if ((string) $row['translated'] === $translated_slug) {
			return false;
		}

		if ($apply) {
			$wpdb->update(
				$slug_translations_table,
				array(
					'translated' => $translated_slug,
					'status' => 1,
				),
				array(
					'id' => (int) $row['id'],
				),
				array('%s', '%d'),
				array('%d')
			);
		}

		return true;
	}

	if ($apply) {
		$wpdb->insert(
			$slug_translations_table,
			array(
				'original_id' => $original_id,
				'translated' => $translated_slug,
				'language' => $language,
				'status' => 1,
			),
			array('%d', '%s', '%s', '%d')
		);
	}

	return true;
}

function classiadspro_term_sync_is_percent_encoded_slug($slug)
{
	return is_string($slug) && $slug !== '' && preg_match('/%[0-9A-Fa-f]{2}/', $slug);
}

function classiadspro_term_sync_get_canonical_english_slug($slug, $maps)
{
	if (!is_string($slug) || $slug === '') {
		return $slug;
	}

	$restored_slug = classiadspro_term_sync_restore_slug($slug, $maps['slugs_reverse']);
	if ($restored_slug !== '' && $restored_slug !== $slug) {
		return $restored_slug;
	}

	if (!classiadspro_term_sync_is_percent_encoded_slug($slug) && !classiadspro_term_sync_contains_cyrillic(urldecode($slug))) {
		return $slug;
	}

	return $slug;
}

function classiadspro_term_sync_collect_strings($value, &$strings)
{
	if (is_string($value)) {
		if ($value !== '') {
			$strings[$value] = true;
		}

		return;
	}

	if (!is_array($value)) {
		return;
	}

	foreach ($value as $item) {
		classiadspro_term_sync_collect_strings($item, $strings);
	}
}

function classiadspro_term_sync_get_dictionary_table_name($from_language, $to_language)
{
	global $wpdb;

	return $wpdb->prefix . 'trp_dictionary_' . strtolower($from_language) . '_' . strtolower($to_language);
}

function classiadspro_term_sync_get_dictionary_translation($table, $original)
{
	static $cache = array();
	global $wpdb;

	if (!isset($cache[$table])) {
		$cache[$table] = array();
	}

	if (array_key_exists($original, $cache[$table])) {
		return $cache[$table][$original];
	}

	$translated = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT translated
			FROM {$table}
			WHERE original = %s
				AND translated IS NOT NULL
				AND translated <> ''
				AND status > 0
			ORDER BY id ASC
			LIMIT 1",
			$original
		)
	);

	$cache[$table][$original] = is_string($translated) ? $translated : '';

	return $cache[$table][$original];
}

function classiadspro_term_sync_get_slug_translation_for_language($source_slug, $language)
{
	static $cache = array();
	global $wpdb;

	$cache_key = $language . '|' . $source_slug;
	if (array_key_exists($cache_key, $cache)) {
		return $cache[$cache_key];
	}

	$slug_originals_table = $wpdb->prefix . 'trp_slug_originals';
	$slug_translations_table = $wpdb->prefix . 'trp_slug_translations';

	$translated = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT st.translated
			FROM {$slug_originals_table} so
			INNER JOIN {$slug_translations_table} st ON st.original_id = so.id
			WHERE so.original = %s
				AND st.language = %s
				AND st.status > 0
				AND st.translated <> ''
			ORDER BY st.id ASC
			LIMIT 1",
			$source_slug,
			$language
		)
	);

	$cache[$cache_key] = is_string($translated) ? urldecode($translated) : '';

	return $cache[$cache_key];
}

function classiadspro_run_term_ru_source_migration()
{
	if (!is_admin() || !current_user_can('manage_options')) {
		return;
	}

	if (empty($_GET['classiadspro_migrate_terms_ru_source'])) {
		return;
	}

	$taxonomy = isset($_GET['taxonomy']) ? sanitize_key(wp_unslash($_GET['taxonomy'])) : 'directorypress-category';
	$apply = !empty($_GET['apply']);

	if (!taxonomy_exists($taxonomy)) {
		wp_die(esc_html(sprintf('Taxonomy "%s" does not exist.', $taxonomy)));
	}

	$maps = classiadspro_term_sync_get_trp_maps();

	if (empty($maps['dictionary']) && empty($maps['dictionary_reverse'])) {
		wp_die('TranslatePress translation maps were not found.');
	}

	global $wpdb;

	$ru_en_table = $wpdb->prefix . 'trp_dictionary_ru_ru_en_us';
	$slug_originals_table = $wpdb->prefix . 'trp_slug_originals';
	$slug_translations_table = $wpdb->prefix . 'trp_slug_translations';
	$termmeta_table = $wpdb->termmeta;
	$yoast_option_name = 'wpseo_taxonomy_meta';
	$yoast_meta = get_option($yoast_option_name, array());

	$terms = classiadspro_term_sync_get_raw_terms($taxonomy);

	$terms_processed = 0;
	$term_fields_changed = 0;
	$termmeta_changed = 0;
	$yoast_changed = 0;
	$dictionary_changed = 0;
	$slug_translations_changed = 0;
	$report_lines = array();

	foreach ($terms as $term) {
		$terms_processed++;
		$string_pairs = array();
		$slug_pairs = array();
		$update_args = array();
		$changes = array();

		$new_name = classiadspro_term_sync_resolve_ru_source_string($term->name, $maps, $string_pairs);
		if ($new_name !== $term->name) {
			$update_args['name'] = $new_name;
			$changes[] = 'name';
		}

		$new_description = classiadspro_term_sync_resolve_ru_source_string($term->description, $maps, $string_pairs);
		if ($new_description !== $term->description) {
			$update_args['description'] = $new_description;
			$changes[] = 'description';
		}

		$new_slug = classiadspro_term_sync_resolve_ru_source_slug($term->slug, $maps, $slug_pairs);
		if ($new_slug !== $term->slug) {
			$update_args['slug'] = $new_slug;
			$changes[] = 'slug';
		}

		if (!empty($update_args)) {
			if ($apply) {
				$result = wp_update_term($term->term_id, $taxonomy, $update_args);

				if (is_wp_error($result)) {
					$report_lines[] = sprintf('term %d update error: %s', $term->term_id, $result->get_error_message());
				} else {
					$term_fields_changed += count($changes);
					$report_lines[] = sprintf('term %d: %s', $term->term_id, implode(', ', $changes));
				}
			} else {
				$term_fields_changed += count($changes);
				$report_lines[] = sprintf('term %d [dry-run]: %s', $term->term_id, implode(', ', $changes));
			}
		}

		$meta_rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_id, meta_key, meta_value
				FROM {$termmeta_table}
				WHERE term_id = %d",
				$term->term_id
			),
			ARRAY_A
		);

		foreach ($meta_rows as $meta_row) {
			$original_value = maybe_unserialize($meta_row['meta_value']);
			$migrated_value = classiadspro_term_sync_migrate_value_to_ru_source($original_value, $maps, $string_pairs);

			if ($migrated_value === $original_value) {
				continue;
			}

			if ($apply) {
				$wpdb->update(
					$termmeta_table,
					array(
						'meta_value' => maybe_serialize($migrated_value),
					),
					array(
						'meta_id' => (int) $meta_row['meta_id'],
					),
					array('%s'),
					array('%d')
				);
			}

			$termmeta_changed++;
			$report_lines[] = sprintf('term %d meta%s: %s', $term->term_id, $apply ? '' : ' [dry-run]', $meta_row['meta_key']);
		}

		if (
			isset($yoast_meta[$taxonomy][$term->term_id]) &&
			is_array($yoast_meta[$taxonomy][$term->term_id])
		) {
			$original_yoast = $yoast_meta[$taxonomy][$term->term_id];
			$migrated_yoast = classiadspro_term_sync_migrate_value_to_ru_source($original_yoast, $maps, $string_pairs);

			if ($migrated_yoast !== $original_yoast) {
				if ($apply) {
					$yoast_meta[$taxonomy][$term->term_id] = $migrated_yoast;
				}

				$yoast_changed++;
				$report_lines[] = sprintf('term %d yoast%s', $term->term_id, $apply ? '' : ' [dry-run]');
			}
		}

		$final_term = $term;
		if ($apply && !empty($changes)) {
			$reloaded_term = get_term($term->term_id, $taxonomy);
			if ($reloaded_term && !is_wp_error($reloaded_term)) {
				$final_term = $reloaded_term;
			}
		} elseif (!$apply && !empty($update_args)) {
			$final_term = (object) array_merge((array) $term, $update_args);
		}

		if (is_object($final_term)) {
			$final_slug = isset($final_term->slug) ? urldecode((string) $final_term->slug) : '';
			$final_name = isset($final_term->name) ? (string) $final_term->name : '';
			$final_description = isset($final_term->description) ? (string) $final_term->description : '';

			$english_name = classiadspro_term_sync_restore_string($final_name, $maps['dictionary_reverse']);
			if ($english_name !== $final_name && $english_name !== '') {
				$string_pairs[$final_name] = $english_name;
			}

			$english_description = classiadspro_term_sync_restore_string($final_description, $maps['dictionary_reverse']);
			if ($english_description !== $final_description && $english_description !== '') {
				$string_pairs[$final_description] = $english_description;
			}

			$english_slug = classiadspro_term_sync_restore_slug(isset($final_term->slug) ? (string) $final_term->slug : '', $maps['slugs_reverse']);
			if ($final_slug !== '' && $english_slug !== '' && $english_slug !== (string) $final_term->slug) {
				$slug_pairs[$final_slug] = $english_slug;
			}
		}

		foreach ($string_pairs as $russian => $english) {
			if (classiadspro_term_sync_upsert_dictionary_row($ru_en_table, $russian, $english, $apply)) {
				$dictionary_changed++;
				$report_lines[] = sprintf('dictionary%s: %s => %s', $apply ? '' : ' [dry-run]', $russian, $english);
			}
		}

		foreach ($slug_pairs as $russian_slug => $english_slug) {
			if (classiadspro_term_sync_upsert_slug_translation($slug_originals_table, $slug_translations_table, $russian_slug, $english_slug, 'en_US', $apply)) {
				$slug_translations_changed++;
				$report_lines[] = sprintf('slug%s: %s => %s', $apply ? '' : ' [dry-run]', $russian_slug, $english_slug);
			}
		}
	}

	if ($apply && $yoast_changed > 0) {
		update_option($yoast_option_name, $yoast_meta, false);
	}

	$report = array(
		$apply ? 'Term RU source migration completed.' : 'Term RU source migration dry-run completed.',
		'',
		'Taxonomy: ' . $taxonomy,
		'Apply mode: ' . ($apply ? 'yes' : 'no'),
		'Terms processed: ' . $terms_processed,
		'Term fields changed: ' . $term_fields_changed,
		'Term meta rows changed: ' . $termmeta_changed,
		'Yoast term entries changed: ' . $yoast_changed,
		'Dictionary ru_RU -> en_US rows changed: ' . $dictionary_changed,
		'Slug ru_RU -> en_US rows changed: ' . $slug_translations_changed,
		'',
		'Changes:',
	);

	if (empty($report_lines)) {
		$report[] = '- no changes';
	} else {
		foreach ($report_lines as $line) {
			$report[] = '- ' . $line;
		}
	}

	if (!$apply) {
		$report[] = '';
		$report[] = 'Run with apply=1 to write changes.';
		$report[] = '/wp-admin/?classiadspro_migrate_terms_ru_source=1&taxonomy=' . rawurlencode($taxonomy) . '&apply=1';
	}

	wp_die('<pre>' . esc_html(implode("\n", $report)) . '</pre>');
}
add_action('admin_init', 'classiadspro_run_term_ru_source_migration');

function classiadspro_run_term_additional_language_sync()
{
	if (!is_admin() || !current_user_can('manage_options')) {
		return;
	}

	if (empty($_GET['classiadspro_sync_term_other_languages'])) {
		return;
	}

	$taxonomy = isset($_GET['taxonomy']) ? sanitize_key(wp_unslash($_GET['taxonomy'])) : 'directorypress-category';
	$apply = !empty($_GET['apply']);
	$maps = classiadspro_term_sync_get_trp_maps();
	$settings = get_option('trp_settings', array());
	$languages = isset($settings['translation-languages']) && is_array($settings['translation-languages']) ? $settings['translation-languages'] : array();

	if (!taxonomy_exists($taxonomy)) {
		wp_die(esc_html(sprintf('Taxonomy "%s" does not exist.', $taxonomy)));
	}

	$languages = array_values(array_filter($languages, function ($language) {
		return $language !== 'ru_RU' && $language !== 'en_US';
	}));

	if (empty($languages)) {
		wp_die('No additional target languages found in TranslatePress settings.');
	}

	global $wpdb;

	$slug_originals_table = $wpdb->prefix . 'trp_slug_originals';
	$slug_translations_table = $wpdb->prefix . 'trp_slug_translations';
	$termmeta_table = $wpdb->termmeta;
	$yoast_option_name = 'wpseo_taxonomy_meta';
	$yoast_meta = get_option($yoast_option_name, array());

	$terms = classiadspro_term_sync_get_raw_terms($taxonomy);

	$terms_processed = 0;
	$dictionary_changed = 0;
	$slug_changed = 0;
	$report_lines = array();

	foreach ($terms as $term) {
		$terms_processed++;
		$strings = array();

		classiadspro_term_sync_collect_strings($term->name, $strings);
		classiadspro_term_sync_collect_strings($term->description, $strings);

		$meta_rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_value
				FROM {$termmeta_table}
				WHERE term_id = %d",
				$term->term_id
			),
			ARRAY_A
		);

		foreach ($meta_rows as $meta_row) {
			classiadspro_term_sync_collect_strings(maybe_unserialize($meta_row['meta_value']), $strings);
		}

		if (
			isset($yoast_meta[$taxonomy][$term->term_id]) &&
			is_array($yoast_meta[$taxonomy][$term->term_id])
		) {
			classiadspro_term_sync_collect_strings($yoast_meta[$taxonomy][$term->term_id], $strings);
		}

		foreach (array_keys($strings) as $russian_string) {
			if (!classiadspro_term_sync_contains_cyrillic($russian_string)) {
				continue;
			}

			$english_string = classiadspro_term_sync_restore_string($russian_string, $maps['dictionary_reverse']);
			if ($english_string === $russian_string || $english_string === '') {
				continue;
			}

			foreach ($languages as $language) {
				$source_table = classiadspro_term_sync_get_dictionary_table_name('en_US', $language);
				$target_table = classiadspro_term_sync_get_dictionary_table_name('ru_RU', $language);
				$translated_value = classiadspro_term_sync_get_dictionary_translation($source_table, $english_string);

				if ($translated_value === '') {
					continue;
				}

				if (classiadspro_term_sync_upsert_dictionary_row($target_table, $russian_string, $translated_value, $apply)) {
					$dictionary_changed++;
					$report_lines[] = sprintf(
						'%s%s: %s => %s',
						$language,
						$apply ? '' : ' [dry-run]',
						$russian_string,
						$translated_value
					);
				}
			}
		}

		$russian_slug = urldecode((string) $term->slug);
		$english_slug = classiadspro_term_sync_restore_slug((string) $term->slug, $maps['slugs_reverse']);

		if ($russian_slug !== '' && $english_slug !== '' && $english_slug !== (string) $term->slug) {
			foreach ($languages as $language) {
				$translated_slug = classiadspro_term_sync_get_slug_translation_for_language($english_slug, $language);

				if ($translated_slug === '') {
					continue;
				}

				if (classiadspro_term_sync_upsert_slug_translation($slug_originals_table, $slug_translations_table, $russian_slug, $translated_slug, $language, $apply)) {
					$slug_changed++;
					$report_lines[] = sprintf(
						'%s slug%s: %s => %s',
						$language,
						$apply ? '' : ' [dry-run]',
						$russian_slug,
						$translated_slug
					);
				}
			}
		}
	}

	$report = array(
		$apply ? 'Additional language sync completed.' : 'Additional language sync dry-run completed.',
		'',
		'Taxonomy: ' . $taxonomy,
		'Apply mode: ' . ($apply ? 'yes' : 'no'),
		'Languages: ' . implode(', ', $languages),
		'Terms processed: ' . $terms_processed,
		'Dictionary rows changed: ' . $dictionary_changed,
		'Slug rows changed: ' . $slug_changed,
		'',
		'Changes:',
	);

	if (empty($report_lines)) {
		$report[] = '- no changes';
	} else {
		foreach ($report_lines as $line) {
			$report[] = '- ' . $line;
		}
	}

	if (!$apply) {
		$report[] = '';
		$report[] = 'Run with apply=1 to write changes.';
		$report[] = '/wp-admin/?classiadspro_sync_term_other_languages=1&taxonomy=' . rawurlencode($taxonomy) . '&apply=1';
	}

	wp_die('<pre>' . esc_html(implode("\n", $report)) . '</pre>');
}
add_action('admin_init', 'classiadspro_run_term_additional_language_sync');

function classiadspro_run_term_ru_source_strict_migration()
{
	if (!is_admin() || !current_user_can('manage_options')) {
		return;
	}

	if (empty($_GET['classiadspro_migrate_terms_ru_source_strict'])) {
		return;
	}

	$taxonomy = isset($_GET['taxonomy']) ? sanitize_key(wp_unslash($_GET['taxonomy'])) : 'directorypress-category';
	$apply = !empty($_GET['apply']);

	if (!taxonomy_exists($taxonomy)) {
		wp_die(esc_html(sprintf('Taxonomy "%s" does not exist.', $taxonomy)));
	}

	$map = classiadspro_term_sync_strict_map();
	$maps = classiadspro_term_sync_get_trp_maps();
	$terms = classiadspro_term_sync_get_raw_terms($taxonomy);
	$terms_by_id = array();

	foreach ($terms as $term) {
		$terms_by_id[(int) $term->term_id] = $term;
	}

	$ru_en_table = $GLOBALS['wpdb']->prefix . 'trp_dictionary_ru_ru_en_us';
	$slug_originals_table = $GLOBALS['wpdb']->prefix . 'trp_slug_originals';
	$slug_translations_table = $GLOBALS['wpdb']->prefix . 'trp_slug_translations';

	$terms_processed = 0;
	$term_fields_changed = 0;
	$dictionary_changed = 0;
	$slug_changed = 0;
	$report_lines = array();

	foreach ($map as $term_id => $target) {
		if (!isset($terms_by_id[$term_id])) {
			continue;
		}

		$term = $terms_by_id[$term_id];
		$terms_processed++;
		$update_args = array();
		$changes = array();
		$old_name = (string) $term->name;
		$old_slug = (string) $term->slug;
		$new_name = isset($target['name']) ? (string) $target['name'] : $old_name;
		$ru_slug = isset($target['slug']) ? (string) $target['slug'] : '';
		$canonical_english_slug = classiadspro_term_sync_get_canonical_english_slug($old_slug, $maps);
		$new_slug = $canonical_english_slug !== '' ? $canonical_english_slug : $old_slug;
		$new_description = isset($target['description']) ? (string) $target['description'] : (string) $term->description;

		if ($new_name !== '' && $new_name !== $old_name) {
			$update_args['name'] = $new_name;
			$changes[] = 'name';
		}

		if ($new_slug !== '' && $new_slug !== $old_slug) {
			$update_args['slug'] = $new_slug;
			$changes[] = 'slug';
		}

		if ($new_description !== (string) $term->description) {
			$update_args['description'] = $new_description;
			$changes[] = 'description';
		}

		if (!empty($update_args)) {
			if ($apply) {
				$result = wp_update_term($term_id, $taxonomy, $update_args);

				if (is_wp_error($result)) {
					$report_lines[] = sprintf('term %d update error: %s', $term_id, $result->get_error_message());
					continue;
				}
			}

			$term_fields_changed += count($changes);
			$report_lines[] = sprintf('term %d%s: %s', $term_id, $apply ? '' : ' [dry-run]', implode(', ', $changes));
		}

		$final_name = $new_name !== '' ? $new_name : $old_name;
		$final_slug = $new_slug !== '' ? $new_slug : $old_slug;

		if ($final_name !== '' && $old_name !== '' && $final_name !== $old_name) {
			if (classiadspro_term_sync_upsert_dictionary_row($ru_en_table, $final_name, $old_name, $apply)) {
				$dictionary_changed++;
				$report_lines[] = sprintf('dictionary%s: %s => %s', $apply ? '' : ' [dry-run]', $final_name, $old_name);
			}
		}

		if ($ru_slug !== '' && $final_slug !== '') {
			if (classiadspro_term_sync_upsert_slug_translation($slug_originals_table, $slug_translations_table, $final_slug, $ru_slug, 'ru_RU', $apply)) {
				$slug_changed++;
				$report_lines[] = sprintf('slug ru_RU%s: %s => %s', $apply ? '' : ' [dry-run]', $final_slug, $ru_slug);
			}
		}

		if ($final_slug !== '' && $old_slug !== '' && $final_slug !== $old_slug) {
			if (classiadspro_term_sync_upsert_slug_translation($slug_originals_table, $slug_translations_table, $final_slug, $old_slug, 'en_US', $apply)) {
				$slug_changed++;
				$report_lines[] = sprintf('slug en_US%s: %s => %s', $apply ? '' : ' [dry-run]', $final_slug, $old_slug);
			}
		}
	}

	$report = array(
		$apply ? 'Term RU source strict migration completed.' : 'Term RU source strict migration dry-run completed.',
		'',
		'Taxonomy: ' . $taxonomy,
		'Apply mode: ' . ($apply ? 'yes' : 'no'),
		'Mapped terms processed: ' . $terms_processed,
		'Term fields changed: ' . $term_fields_changed,
		'Dictionary ru_RU -> en_US rows changed: ' . $dictionary_changed,
		'Slug ru_RU -> en_US rows changed: ' . $slug_changed,
		'',
		'Changes:',
	);

	if (empty($report_lines)) {
		$report[] = '- no changes';
	} else {
		foreach ($report_lines as $line) {
			$report[] = '- ' . $line;
		}
	}

	if (!$apply) {
		$report[] = '';
		$report[] = 'Run with apply=1 to write changes.';
		$report[] = '/wp-admin/?classiadspro_migrate_terms_ru_source_strict=1&taxonomy=' . rawurlencode($taxonomy) . '&apply=1';
	}

	wp_die('<pre>' . esc_html(implode("\n", $report)) . '</pre>');
}
add_action('admin_init', 'classiadspro_run_term_ru_source_strict_migration');

function classiadspro_term_sync_abort_direct_source_translation()
{
	if (!is_admin() || !current_user_can('manage_options')) {
		return;
	}

	if (empty($_GET['classiadspro_sync_terms_ru'])) {
		return;
	}

	$message = array(
		'Direct term source translation is disabled.',
		'',
		'Reason: changing canonical term data to Russian makes every site language show Russian content in TranslatePress.',
		'',
		'Use this recovery action instead:',
		'/wp-admin/?classiadspro_restore_terms_en=1&taxonomy=directorypress-category',
	);

	wp_die('<pre>' . esc_html(implode("\n", $message)) . '</pre>');
}
add_action('admin_init', 'classiadspro_term_sync_abort_direct_source_translation');

function classiadspro_run_term_source_restore()
{
	if (!is_admin() || !current_user_can('manage_options')) {
		return;
	}

	if (empty($_GET['classiadspro_restore_terms_en'])) {
		return;
	}

	$taxonomy = isset($_GET['taxonomy']) ? sanitize_key(wp_unslash($_GET['taxonomy'])) : 'directorypress-category';

	if (!taxonomy_exists($taxonomy)) {
		wp_die(esc_html(sprintf('Taxonomy "%s" does not exist.', $taxonomy)));
	}

	$maps = classiadspro_term_sync_get_trp_maps();

	if (empty($maps['dictionary_reverse']) && empty($maps['slugs_reverse'])) {
		wp_die('TranslatePress reverse translation maps were not found.');
	}

	global $wpdb;

	$termmeta_table = $wpdb->termmeta;
	$yoast_option_name = 'wpseo_taxonomy_meta';
	$yoast_meta = get_option($yoast_option_name, array());
	$yoast_changed = 0;
	$terms_processed = 0;
	$term_fields_restored = 0;
	$termmeta_restored = 0;
	$updated_terms = array();

	$terms = classiadspro_term_sync_get_raw_terms($taxonomy);

	foreach ($terms as $term) {
		$terms_processed++;
		$update_args = array();
		$changes = array();

		$restored_name = classiadspro_term_sync_restore_string($term->name, $maps['dictionary_reverse']);
		if ($restored_name !== $term->name) {
			$update_args['name'] = $restored_name;
			$changes[] = 'name';
		}

		$restored_description = classiadspro_term_sync_restore_string($term->description, $maps['dictionary_reverse']);
		if ($restored_description !== $term->description) {
			$update_args['description'] = $restored_description;
			$changes[] = 'description';
		}

		$restored_slug = classiadspro_term_sync_restore_slug($term->slug, $maps['slugs_reverse']);
		if ($restored_slug !== $term->slug) {
			$update_args['slug'] = $restored_slug;
			$changes[] = 'slug';
		}

		if (!empty($update_args)) {
			$result = wp_update_term($term->term_id, $taxonomy, $update_args);

			if (!is_wp_error($result)) {
				$term_fields_restored += count($changes);
				$updated_terms[] = sprintf('term %d: %s', $term->term_id, implode(', ', $changes));
			}
		}

		$meta_rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_id, meta_key, meta_value
				FROM {$termmeta_table}
				WHERE term_id = %d",
				$term->term_id
			),
			ARRAY_A
		);

		foreach ($meta_rows as $meta_row) {
			$original_value = maybe_unserialize($meta_row['meta_value']);
			$restored_value = classiadspro_term_sync_restore_deep($original_value, $maps['dictionary_reverse']);

			if ($restored_value === $original_value) {
				continue;
			}

			$wpdb->update(
				$termmeta_table,
				array(
					'meta_value' => maybe_serialize($restored_value),
				),
				array(
					'meta_id' => (int) $meta_row['meta_id'],
				),
				array('%s'),
				array('%d')
			);

			$termmeta_restored++;
			$updated_terms[] = sprintf('term %d meta: %s', $term->term_id, $meta_row['meta_key']);
		}

		if (
			isset($yoast_meta[$taxonomy][$term->term_id]) &&
			is_array($yoast_meta[$taxonomy][$term->term_id])
		) {
			$original_yoast = $yoast_meta[$taxonomy][$term->term_id];
			$restored_yoast = classiadspro_term_sync_restore_deep($original_yoast, $maps['dictionary_reverse']);

			if ($restored_yoast !== $original_yoast) {
				$yoast_meta[$taxonomy][$term->term_id] = $restored_yoast;
				$yoast_changed++;
				$updated_terms[] = sprintf('term %d yoast', $term->term_id);
			}
		}
	}

	if ($yoast_changed > 0) {
		update_option($yoast_option_name, $yoast_meta, false);
	}

	$report = array(
		'Term source restore completed.',
		'',
		'Taxonomy: ' . $taxonomy,
		'Terms processed: ' . $terms_processed,
		'Term fields restored: ' . $term_fields_restored,
		'Term meta rows restored: ' . $termmeta_restored,
		'Yoast term entries restored: ' . $yoast_changed,
		'',
		'Updated items:',
	);

	if (empty($updated_terms)) {
		$report[] = '- no changes';
	} else {
		foreach ($updated_terms as $line) {
			$report[] = '- ' . $line;
		}
	}

	wp_die('<pre>' . esc_html(implode("\n", $report)) . '</pre>');
}
add_action('admin_init', 'classiadspro_run_term_source_restore');
