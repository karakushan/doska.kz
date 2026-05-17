<?php

function classiadspro_page_sync_parse_page_ids()
{
	if (empty($_GET['page_ids'])) {
		return array();
	}

	$raw_ids = explode(',', (string) wp_unslash($_GET['page_ids']));
	$page_ids = array();

	foreach ($raw_ids as $raw_id) {
		$page_id = absint(trim($raw_id));

		if ($page_id > 0) {
			$page_ids[] = $page_id;
		}
	}

	return array_values(array_unique($page_ids));
}

function classiadspro_page_sync_get_pages($page_ids)
{
	if (empty($page_ids)) {
		return array();
	}

	return get_posts(array(
		'post_type' => 'page',
		'post_status' => array('publish', 'private', 'draft', 'pending', 'future'),
		'post__in' => $page_ids,
		'orderby' => 'post__in',
		'numberposts' => -1,
		'suppress_filters' => false,
	));
}

function classiadspro_page_sync_require_page_ids($action_name)
{
	$page_ids = classiadspro_page_sync_parse_page_ids();

	if (!empty($page_ids)) {
		return $page_ids;
	}

	$message = array(
		$action_name . ' requires explicit page_ids.',
		'',
		'Example:',
		'/wp-admin/?' . $action_name . '=1&page_ids=12836,597,8605',
	);

	wp_die('<pre>' . esc_html(implode("\n", $message)) . '</pre>');
}

function classiadspro_page_sync_build_replacements($maps, $direction)
{
	$replacements = array();
	$dictionary = $direction === 'to_ru' ? $maps['dictionary'] : $maps['dictionary_reverse'];
	$slugs = $direction === 'to_ru' ? $maps['slugs'] : $maps['slugs_reverse'];

	foreach ($dictionary as $from => $to) {
		if (!is_string($from) || !is_string($to) || $from === '' || $to === '' || $from === $to) {
			continue;
		}

		$replacements[$from] = $to;

		$decoded_from = html_entity_decode($from, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		if ($decoded_from !== $from && $decoded_from !== '') {
			$replacements[$decoded_from] = $to;
		}
	}

	foreach ($slugs as $from => $to) {
		if (!is_string($from) || !is_string($to) || $from === '' || $to === '' || $from === $to) {
			continue;
		}

		$replacements[$from] = $to;
		$replacements[rawurlencode($from)] = rawurlencode($to);
		$replacements[urlencode($from)] = urlencode($to);
	}

	return $replacements;
}

function classiadspro_page_sync_replace_string($value, $replacements)
{
	if (!is_string($value) || $value === '' || empty($replacements)) {
		return $value;
	}

	return strtr($value, $replacements);
}

function classiadspro_page_sync_replace_deep($value, $replacements)
{
	if (is_string($value)) {
		return classiadspro_page_sync_replace_string($value, $replacements);
	}

	if (!is_array($value)) {
		return $value;
	}

	foreach ($value as $key => $item) {
		$value[$key] = classiadspro_page_sync_replace_deep($item, $replacements);
	}

	return $value;
}

function classiadspro_page_sync_transform_meta_value($meta_value, $replacements)
{
	$unserialized = maybe_unserialize($meta_value);

	if (is_array($unserialized)) {
		return maybe_serialize(classiadspro_page_sync_replace_deep($unserialized, $replacements));
	}

	if (is_string($unserialized) && $unserialized !== $meta_value) {
		return maybe_serialize(classiadspro_page_sync_replace_string($unserialized, $replacements));
	}

	return classiadspro_page_sync_replace_string($meta_value, $replacements);
}

function classiadspro_page_sync_get_postmeta_rows($post_id)
{
	global $wpdb;

	return $wpdb->get_results(
		$wpdb->prepare(
			"SELECT meta_id, meta_key, meta_value
			FROM {$wpdb->postmeta}
			WHERE post_id = %d",
			$post_id
		),
		ARRAY_A
	);
}

function classiadspro_page_sync_get_attached_original_strings($post_id)
{
	global $wpdb;

	$original_strings_table = $wpdb->prefix . 'trp_original_strings';
	$original_meta_table = $wpdb->prefix . 'trp_original_meta';

	$rows = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT DISTINCT os.original
			FROM {$original_strings_table} os
			INNER JOIN {$original_meta_table} om ON om.original_id = os.id
			WHERE om.meta_key = 'post_parent_id'
				AND om.meta_value = %d",
			$post_id
		)
	);

	return array_values(array_filter(array_map('strval', $rows)));
}

function classiadspro_page_sync_collect_ru_en_pairs_for_page($post, $maps)
{
	$pairs = array();
	$fields = array(
		(string) $post->post_title,
		(string) $post->post_excerpt,
		(string) $post->post_content,
	);

	foreach ($fields as $field) {
		$restored = classiadspro_term_sync_restore_string($field, $maps['dictionary_reverse']);
		if ($restored !== $field && $restored !== '') {
			$pairs[$field] = $restored;
		}
	}

	foreach (classiadspro_page_sync_get_attached_original_strings($post->ID) as $original_string) {
		$restored = classiadspro_term_sync_restore_string($original_string, $maps['dictionary_reverse']);

		if ($restored !== $original_string && $restored !== '') {
			$pairs[$original_string] = $restored;
		}

		$translated = classiadspro_term_sync_translate_string($original_string, $maps['dictionary']);
		if ($translated !== $original_string && $translated !== '') {
			$pairs[$translated] = $original_string;
		}
	}

	return $pairs;
}

function classiadspro_page_sync_get_slug_pairs_for_page($post, $maps)
{
	$pairs = array();
	$current_slug = (string) $post->post_name;

	if ($current_slug === '') {
		return $pairs;
	}

	$decoded_slug = urldecode($current_slug);
	$english_slug = classiadspro_term_sync_restore_slug($current_slug, $maps['slugs_reverse']);

	if ($english_slug !== $current_slug && $english_slug !== '') {
		$pairs[$decoded_slug] = $english_slug;
		return $pairs;
	}

	if (isset($maps['slugs'][$current_slug]) && $maps['slugs'][$current_slug] !== '') {
		$pairs[$maps['slugs'][$current_slug]] = $current_slug;
	}

	return $pairs;
}

function classiadspro_page_sync_update_postmeta_row($meta_id, $meta_value, $apply)
{
	global $wpdb;

	if (!$apply) {
		return true;
	}

	return false !== $wpdb->update(
		$wpdb->postmeta,
		array('meta_value' => $meta_value),
		array('meta_id' => (int) $meta_id),
		array('%s'),
		array('%d')
	);
}

function classiadspro_run_page_ru_source_migration()
{
	if (!is_admin() || !current_user_can('manage_options')) {
		return;
	}

	if (empty($_GET['classiadspro_migrate_pages_ru_source'])) {
		return;
	}

	$page_ids = classiadspro_page_sync_require_page_ids('classiadspro_migrate_pages_ru_source');
	$apply = !empty($_GET['apply']);
	$maps = classiadspro_term_sync_get_trp_maps();
	$replacements = classiadspro_page_sync_build_replacements($maps, 'to_ru');
	$ru_en_table = $GLOBALS['wpdb']->prefix . 'trp_dictionary_ru_ru_en_us';

	$pages = classiadspro_page_sync_get_pages($page_ids);
	$report_lines = array();
	$post_fields_changed = 0;
	$postmeta_changed = 0;
	$dictionary_changed = 0;
	$slug_changed = 0;

	foreach ($pages as $page) {
		$update_args = array('ID' => $page->ID);
		$changes = array();

		$new_title = classiadspro_page_sync_replace_string((string) $page->post_title, $replacements);
		if ($new_title !== $page->post_title) {
			$update_args['post_title'] = $new_title;
			$changes[] = 'title';
		}

		$new_excerpt = classiadspro_page_sync_replace_string((string) $page->post_excerpt, $replacements);
		if ($new_excerpt !== $page->post_excerpt) {
			$update_args['post_excerpt'] = $new_excerpt;
			$changes[] = 'excerpt';
		}

		$new_content = classiadspro_page_sync_replace_string((string) $page->post_content, $replacements);
		if ($new_content !== $page->post_content) {
			$update_args['post_content'] = $new_content;
			$changes[] = 'content';
		}

		$slug_pairs = array();
		$new_slug = classiadspro_term_sync_resolve_ru_source_slug((string) $page->post_name, $maps, $slug_pairs);
		if ($new_slug !== $page->post_name) {
			$update_args['post_name'] = $new_slug;
			$changes[] = 'slug';
		}

		if (count($update_args) > 1) {
			if ($apply) {
				$result = wp_update_post(wp_slash($update_args), true);
				if (is_wp_error($result)) {
					$report_lines[] = sprintf('page %d update error: %s', $page->ID, $result->get_error_message());
				} else {
					$post_fields_changed += count($changes);
					$report_lines[] = sprintf('page %d: %s', $page->ID, implode(', ', $changes));
				}
			} else {
				$post_fields_changed += count($changes);
				$report_lines[] = sprintf('page %d [dry-run]: %s', $page->ID, implode(', ', $changes));
			}
		}

		foreach (classiadspro_page_sync_get_postmeta_rows($page->ID) as $meta_row) {
			$new_meta_value = classiadspro_page_sync_transform_meta_value($meta_row['meta_value'], $replacements);

			if ($new_meta_value === $meta_row['meta_value']) {
				continue;
			}

			classiadspro_page_sync_update_postmeta_row($meta_row['meta_id'], $new_meta_value, $apply);
			$postmeta_changed++;
			$report_lines[] = sprintf('page %d meta%s: %s', $page->ID, $apply ? '' : ' [dry-run]', $meta_row['meta_key']);
		}

		$fresh_page = $apply ? get_post($page->ID) : (object) array_merge((array) $page, $update_args);

		if ($fresh_page) {
			foreach (classiadspro_page_sync_collect_ru_en_pairs_for_page($fresh_page, $maps) as $russian => $english) {
				if (classiadspro_term_sync_upsert_dictionary_row($ru_en_table, $russian, $english, $apply)) {
					$dictionary_changed++;
					$report_lines[] = sprintf('dictionary%s: %s => %s', $apply ? '' : ' [dry-run]', $russian, $english);
				}
			}

			foreach (classiadspro_page_sync_get_slug_pairs_for_page($fresh_page, $maps) as $russian_slug => $english_slug) {
				if (classiadspro_term_sync_upsert_slug_translation($GLOBALS['wpdb']->prefix . 'trp_slug_originals', $GLOBALS['wpdb']->prefix . 'trp_slug_translations', $russian_slug, $english_slug, 'en_US', $apply)) {
					$slug_changed++;
					$report_lines[] = sprintf('slug%s: %s => %s', $apply ? '' : ' [dry-run]', $russian_slug, $english_slug);
				}
			}
		}
	}

	$report = array(
		$apply ? 'Page RU source migration completed.' : 'Page RU source migration dry-run completed.',
		'',
		'Page IDs: ' . implode(', ', $page_ids),
		'Apply mode: ' . ($apply ? 'yes' : 'no'),
		'Post fields changed: ' . $post_fields_changed,
		'Post meta rows changed: ' . $postmeta_changed,
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
		$report[] = '/wp-admin/?classiadspro_migrate_pages_ru_source=1&page_ids=' . rawurlencode(implode(',', $page_ids)) . '&apply=1';
	}

	wp_die('<pre>' . esc_html(implode("\n", $report)) . '</pre>');
}
add_action('admin_init', 'classiadspro_run_page_ru_source_migration');

function classiadspro_run_page_additional_language_sync()
{
	if (!is_admin() || !current_user_can('manage_options')) {
		return;
	}

	if (empty($_GET['classiadspro_sync_page_other_languages'])) {
		return;
	}

	$page_ids = classiadspro_page_sync_require_page_ids('classiadspro_sync_page_other_languages');
	$apply = !empty($_GET['apply']);
	$maps = classiadspro_term_sync_get_trp_maps();
	$settings = get_option('trp_settings', array());
	$languages = isset($settings['translation-languages']) && is_array($settings['translation-languages']) ? $settings['translation-languages'] : array();
	$languages = array_values(array_filter($languages, function ($language) {
		return $language !== 'ru_RU' && $language !== 'en_US';
	}));

	if (empty($languages)) {
		wp_die('No additional target languages found in TranslatePress settings.');
	}

	$pages = classiadspro_page_sync_get_pages($page_ids);
	$report_lines = array();
	$dictionary_changed = 0;
	$slug_changed = 0;
	$slug_originals_table = $GLOBALS['wpdb']->prefix . 'trp_slug_originals';
	$slug_translations_table = $GLOBALS['wpdb']->prefix . 'trp_slug_translations';

	foreach ($pages as $page) {
		$attached_strings = classiadspro_page_sync_get_attached_original_strings($page->ID);
		$attached_strings[] = (string) $page->post_title;
		$attached_strings[] = (string) $page->post_excerpt;

		foreach (array_unique(array_filter($attached_strings, 'is_string')) as $russian_string) {
			if ($russian_string === '' || !classiadspro_term_sync_contains_cyrillic($russian_string)) {
				continue;
			}

			$english_string = classiadspro_term_sync_restore_string($russian_string, $maps['dictionary_reverse']);
			if ($english_string === '' || $english_string === $russian_string) {
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
					$report_lines[] = sprintf('%s%s page %d: %s => %s', $language, $apply ? '' : ' [dry-run]', $page->ID, $russian_string, $translated_value);
				}
			}
		}

		$russian_slug = urldecode((string) $page->post_name);
		$english_slug = classiadspro_term_sync_restore_slug((string) $page->post_name, $maps['slugs_reverse']);

		if ($russian_slug !== '' && $english_slug !== '' && $english_slug !== (string) $page->post_name) {
			foreach ($languages as $language) {
				$translated_slug = classiadspro_term_sync_get_slug_translation_for_language($english_slug, $language);

				if ($translated_slug === '') {
					continue;
				}

				if (classiadspro_term_sync_upsert_slug_translation($slug_originals_table, $slug_translations_table, $russian_slug, $translated_slug, $language, $apply)) {
					$slug_changed++;
					$report_lines[] = sprintf('%s slug%s page %d: %s => %s', $language, $apply ? '' : ' [dry-run]', $page->ID, $russian_slug, $translated_slug);
				}
			}
		}
	}

	$report = array(
		$apply ? 'Page additional language sync completed.' : 'Page additional language sync dry-run completed.',
		'',
		'Page IDs: ' . implode(', ', $page_ids),
		'Apply mode: ' . ($apply ? 'yes' : 'no'),
		'Languages: ' . implode(', ', $languages),
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
		$report[] = '/wp-admin/?classiadspro_sync_page_other_languages=1&page_ids=' . rawurlencode(implode(',', $page_ids)) . '&apply=1';
	}

	wp_die('<pre>' . esc_html(implode("\n", $report)) . '</pre>');
}
add_action('admin_init', 'classiadspro_run_page_additional_language_sync');

function classiadspro_run_page_source_restore()
{
	if (!is_admin() || !current_user_can('manage_options')) {
		return;
	}

	if (empty($_GET['classiadspro_restore_pages_en'])) {
		return;
	}

	$page_ids = classiadspro_page_sync_require_page_ids('classiadspro_restore_pages_en');
	$apply = !empty($_GET['apply']);
	$maps = classiadspro_term_sync_get_trp_maps();
	$replacements = classiadspro_page_sync_build_replacements($maps, 'to_en');
	$pages = classiadspro_page_sync_get_pages($page_ids);
	$report_lines = array();
	$post_fields_changed = 0;
	$postmeta_changed = 0;

	foreach ($pages as $page) {
		$update_args = array('ID' => $page->ID);
		$changes = array();

		$new_title = classiadspro_page_sync_replace_string((string) $page->post_title, $replacements);
		if ($new_title !== $page->post_title) {
			$update_args['post_title'] = $new_title;
			$changes[] = 'title';
		}

		$new_excerpt = classiadspro_page_sync_replace_string((string) $page->post_excerpt, $replacements);
		if ($new_excerpt !== $page->post_excerpt) {
			$update_args['post_excerpt'] = $new_excerpt;
			$changes[] = 'excerpt';
		}

		$new_content = classiadspro_page_sync_replace_string((string) $page->post_content, $replacements);
		if ($new_content !== $page->post_content) {
			$update_args['post_content'] = $new_content;
			$changes[] = 'content';
		}

		$restored_slug = classiadspro_term_sync_restore_slug((string) $page->post_name, $maps['slugs_reverse']);
		if ($restored_slug !== $page->post_name) {
			$update_args['post_name'] = $restored_slug;
			$changes[] = 'slug';
		}

		if (count($update_args) > 1) {
			if ($apply) {
				$result = wp_update_post(wp_slash($update_args), true);
				if (is_wp_error($result)) {
					$report_lines[] = sprintf('page %d update error: %s', $page->ID, $result->get_error_message());
				} else {
					$post_fields_changed += count($changes);
					$report_lines[] = sprintf('page %d: %s', $page->ID, implode(', ', $changes));
				}
			} else {
				$post_fields_changed += count($changes);
				$report_lines[] = sprintf('page %d [dry-run]: %s', $page->ID, implode(', ', $changes));
			}
		}

		foreach (classiadspro_page_sync_get_postmeta_rows($page->ID) as $meta_row) {
			$new_meta_value = classiadspro_page_sync_transform_meta_value($meta_row['meta_value'], $replacements);

			if ($new_meta_value === $meta_row['meta_value']) {
				continue;
			}

			classiadspro_page_sync_update_postmeta_row($meta_row['meta_id'], $new_meta_value, $apply);
			$postmeta_changed++;
			$report_lines[] = sprintf('page %d meta%s: %s', $page->ID, $apply ? '' : ' [dry-run]', $meta_row['meta_key']);
		}
	}

	$report = array(
		$apply ? 'Page source restore completed.' : 'Page source restore dry-run completed.',
		'',
		'Page IDs: ' . implode(', ', $page_ids),
		'Apply mode: ' . ($apply ? 'yes' : 'no'),
		'Post fields changed: ' . $post_fields_changed,
		'Post meta rows changed: ' . $postmeta_changed,
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
		$report[] = '/wp-admin/?classiadspro_restore_pages_en=1&page_ids=' . rawurlencode(implode(',', $page_ids)) . '&apply=1';
	}

	wp_die('<pre>' . esc_html(implode("\n", $report)) . '</pre>');
}
add_action('admin_init', 'classiadspro_run_page_source_restore');
