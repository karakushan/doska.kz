<?php

if (!defined('ABSPATH')) {
	exit;
}

const CLASSIADSPRO_DP_CATEGORY_DESCRIPTION_TRANSLATION_CRON = 'classiadspro_translate_directorypress_category_descriptions';
const CLASSIADSPRO_DP_CATEGORY_DESCRIPTION_TRANSLATION_SINGLE_CRON = 'classiadspro_translate_directorypress_category_description';

function classiadspro_dp_category_description_translation_log($term_id, $language, $message)
{
	error_log(sprintf(
		'DirectoryPress category description translation failed. term_id=%d language=%s error=%s',
		(int) $term_id,
		(string) $language,
		(string) $message
	));
}

function classiadspro_dp_category_description_get_trp_settings()
{
	$settings = get_option('trp_settings', array());

	return is_array($settings) ? $settings : array();
}

function classiadspro_dp_category_description_get_default_language()
{
	$settings = classiadspro_dp_category_description_get_trp_settings();

	return !empty($settings['default-language']) && is_string($settings['default-language'])
		? $settings['default-language']
		: '';
}

function classiadspro_dp_category_description_get_target_languages()
{
	$settings = classiadspro_dp_category_description_get_trp_settings();
	$default_language = classiadspro_dp_category_description_get_default_language();
	$languages = array();

	if (!empty($settings['publish-languages']) && is_array($settings['publish-languages'])) {
		$languages = $settings['publish-languages'];
	} elseif (!empty($settings['translation-languages']) && is_array($settings['translation-languages'])) {
		$languages = $settings['translation-languages'];
	} elseif (function_exists('dp_get_translatepress_languages')) {
		$languages = array_keys((array) dp_get_translatepress_languages());
	}

	$languages = array_values(array_unique(array_filter(array_map('strval', $languages))));

	return array_values(array_filter($languages, static function ($language) use ($default_language) {
		return $language !== '' && $language !== $default_language;
	}));
}

function classiadspro_dp_category_description_get_current_language()
{
	if (!empty($GLOBALS['TRP_LANGUAGE']) && is_string($GLOBALS['TRP_LANGUAGE'])) {
		return $GLOBALS['TRP_LANGUAGE'];
	}

	$settings = classiadspro_dp_category_description_get_trp_settings();
	if (!empty($settings['url-slugs']) && is_array($settings['url-slugs'])) {
		$request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
		$request_path = trim((string) wp_parse_url($request_uri, PHP_URL_PATH), '/');
		$segments = $request_path !== '' ? explode('/', $request_path) : array();
		$first_segment = !empty($segments[0]) ? sanitize_title_for_query($segments[0]) : '';

		foreach ($settings['url-slugs'] as $language => $slug) {
			if (is_string($language) && is_string($slug) && sanitize_title_for_query($slug) === $first_segment) {
				return $language;
			}
		}
	}

	return classiadspro_dp_category_description_get_default_language();
}

function classiadspro_dp_category_description_get_trp_query()
{
	if (!class_exists('TRP_Translate_Press') || !class_exists('TRP_Query')) {
		return null;
	}

	$trp = TRP_Translate_Press::get_trp_instance();
	if (!is_object($trp) || !method_exists($trp, 'get_component')) {
		return null;
	}

	$query = $trp->get_component('query');

	return $query instanceof TRP_Query ? $query : null;
}

function classiadspro_dp_category_description_translation_ready()
{
	return (
		get_option('dp_translator_gemini_key') !== '' &&
		function_exists('classiadspro_request_dp_listing_translation') &&
		classiadspro_dp_category_description_get_default_language() !== '' &&
		classiadspro_dp_category_description_get_trp_query() instanceof TRP_Query
	);
}

function classiadspro_dp_category_description_table_exists($table_name)
{
	static $cache = array();
	global $wpdb;

	if (isset($cache[$table_name])) {
		return $cache[$table_name];
	}

	$cache[$table_name] = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table_name)) === $table_name;

	return $cache[$table_name];
}

function classiadspro_dp_category_description_get_dictionary_table($language)
{
	$query = classiadspro_dp_category_description_get_trp_query();
	if (!($query instanceof TRP_Query)) {
		return '';
	}

	$table_name = $query->get_table_name($language);

	return classiadspro_dp_category_description_table_exists($table_name) ? $table_name : '';
}

function classiadspro_dp_category_description_get_existing_row($description, $language, $refresh = false)
{
	static $cache = array();
	global $wpdb;

	$description = (string) $description;
	$language = (string) $language;
	$cache_key = $language . '|' . md5($description);

	if (!$refresh && array_key_exists($cache_key, $cache)) {
		return $cache[$cache_key];
	}

	$table_name = classiadspro_dp_category_description_get_dictionary_table($language);
	if ($table_name === '') {
		$cache[$cache_key] = null;
		return null;
	}

	$row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT id, original, translated, status, block_type, original_id
			FROM {$table_name}
			WHERE original = %s
			ORDER BY
				CASE WHEN translated IS NOT NULL AND translated <> '' THEN 0 ELSE 1 END,
				id ASC
			LIMIT 1",
			$description
		),
		ARRAY_A
	);

	$cache[$cache_key] = is_array($row) ? $row : null;

	return $cache[$cache_key];
}

function classiadspro_dp_category_description_has_translation($description, $language)
{
	$row = classiadspro_dp_category_description_get_existing_row($description, $language);

	return (
		is_array($row) &&
		isset($row['translated']) &&
		is_string($row['translated']) &&
		trim($row['translated']) !== ''
	);
}

function classiadspro_dp_category_description_save_translation($description, $translated, $language)
{
	$query = classiadspro_dp_category_description_get_trp_query();
	if (!($query instanceof TRP_Query)) {
		return new WP_Error('trp_query_unavailable', 'TranslatePress query component is unavailable.');
	}

	$description = (string) $description;
	$translated = (string) $translated;
	$language = (string) $language;

	if ($description === '' || $translated === '') {
		return new WP_Error('empty_translation', 'Description or translation is empty.');
	}

	$existing_row = classiadspro_dp_category_description_get_existing_row($description, $language);
	if (!empty($existing_row['translated'])) {
		return true;
	}

	$original_ids = $query->original_strings_sync($language, array($description));
	$original_id = !empty($original_ids[$description]->id) ? (int) $original_ids[$description]->id : 0;

	if ($original_id <= 0) {
		return new WP_Error('trp_original_id_missing', 'TranslatePress original string ID was not created.');
	}

	if (!empty($existing_row['id'])) {
		$query->update_strings(
			array(
				array(
					'id' => (int) $existing_row['id'],
					'original' => $description,
					'translated' => $translated,
					'status' => TRP_Query::MACHINE_TRANSLATED,
					'block_type' => TRP_Query::BLOCK_TYPE_REGULAR_STRING,
					'original_id' => $original_id,
				),
			),
			$language
		);
	} else {
		$query->update_strings(
			array(
				array(
					'original' => $description,
					'translated' => $translated,
					'status' => TRP_Query::MACHINE_TRANSLATED,
					'block_type' => TRP_Query::BLOCK_TYPE_REGULAR_STRING,
					'original_id' => $original_id,
				),
			),
			$language,
			array('original', 'translated', 'status', 'block_type', 'original_id')
		);
	}

	classiadspro_dp_category_description_get_existing_row($description, $language, true);

	return true;
}

function classiadspro_dp_category_description_translate_term_language($term_id, $language)
{
	$term_id = (int) $term_id;
	$language = (string) $language;

	if ($term_id <= 0 || $language === '' || !classiadspro_dp_category_description_translation_ready()) {
		return false;
	}

	$term = get_term($term_id, 'directorypress-category');
	if (!($term instanceof WP_Term) || $term->taxonomy !== 'directorypress-category') {
		return false;
	}

	$description = (string) get_term_field('description', $term_id, 'directorypress-category', 'raw');
	$description = trim($description);
	if ($description === '' || classiadspro_dp_category_description_has_translation($description, $language)) {
		return false;
	}

	$translated = classiadspro_request_dp_listing_translation($description, $language, 'term_description');
	if (is_wp_error($translated)) {
		classiadspro_dp_category_description_translation_log($term_id, $language, $translated->get_error_message());
		return false;
	}

	$result = classiadspro_dp_category_description_save_translation($description, $translated, $language);
	if (is_wp_error($result)) {
		classiadspro_dp_category_description_translation_log($term_id, $language, $result->get_error_message());
		return false;
	}

	return true;
}

function classiadspro_dp_category_description_run_single($term_id)
{
	$term_id = (int) $term_id;
	if ($term_id <= 0) {
		return;
	}

	foreach (classiadspro_dp_category_description_get_target_languages() as $language) {
		classiadspro_dp_category_description_translate_term_language($term_id, $language);
	}
}
add_action(CLASSIADSPRO_DP_CATEGORY_DESCRIPTION_TRANSLATION_SINGLE_CRON, 'classiadspro_dp_category_description_run_single');

function classiadspro_dp_category_description_run_batch()
{
	if (!classiadspro_dp_category_description_translation_ready()) {
		return;
	}

	$limit = (int) apply_filters('classiadspro_dp_category_description_translation_batch_size', 3);
	if ($limit <= 0) {
		return;
	}

	$terms = get_terms(
		array(
			'taxonomy' => 'directorypress-category',
			'hide_empty' => false,
			'fields' => 'ids',
			'number' => 0,
		)
	);

	if (is_wp_error($terms) || empty($terms)) {
		return;
	}

	$processed = 0;
	$languages = classiadspro_dp_category_description_get_target_languages();

	foreach ($terms as $term_id) {
		$description = trim((string) get_term_field('description', (int) $term_id, 'directorypress-category', 'raw'));
		if ($description === '') {
			continue;
		}

		foreach ($languages as $language) {
			if (classiadspro_dp_category_description_has_translation($description, $language)) {
				continue;
			}

			classiadspro_dp_category_description_translate_term_language((int) $term_id, $language);
			$processed++;

			if ($processed >= $limit) {
				return;
			}
		}
	}
}
add_action(CLASSIADSPRO_DP_CATEGORY_DESCRIPTION_TRANSLATION_CRON, 'classiadspro_dp_category_description_run_batch');

function classiadspro_dp_category_description_add_cron_schedule($schedules)
{
	if (!isset($schedules['classiadspro_15_minutes'])) {
		$schedules['classiadspro_15_minutes'] = array(
			'interval' => 15 * MINUTE_IN_SECONDS,
			'display' => 'Every 15 minutes',
		);
	}

	return $schedules;
}
add_filter('cron_schedules', 'classiadspro_dp_category_description_add_cron_schedule');

function classiadspro_dp_category_description_schedule_batch()
{
	if (!wp_next_scheduled(CLASSIADSPRO_DP_CATEGORY_DESCRIPTION_TRANSLATION_CRON)) {
		wp_schedule_event(time() + 5 * MINUTE_IN_SECONDS, 'classiadspro_15_minutes', CLASSIADSPRO_DP_CATEGORY_DESCRIPTION_TRANSLATION_CRON);
	}
}
add_action('init', 'classiadspro_dp_category_description_schedule_batch');

function classiadspro_dp_category_description_schedule_single($term_id)
{
	$term_id = (int) $term_id;
	if ($term_id <= 0) {
		return;
	}

	$timestamp = wp_next_scheduled(CLASSIADSPRO_DP_CATEGORY_DESCRIPTION_TRANSLATION_CRON);
	if (!$timestamp) {
		classiadspro_dp_category_description_schedule_batch();
		$timestamp = wp_next_scheduled(CLASSIADSPRO_DP_CATEGORY_DESCRIPTION_TRANSLATION_CRON);
	}

	if (!$timestamp || $timestamp <= time()) {
		$timestamp = time() + MINUTE_IN_SECONDS;
	}

	wp_clear_scheduled_hook(CLASSIADSPRO_DP_CATEGORY_DESCRIPTION_TRANSLATION_SINGLE_CRON, array($term_id));
	wp_schedule_single_event($timestamp, CLASSIADSPRO_DP_CATEGORY_DESCRIPTION_TRANSLATION_SINGLE_CRON, array($term_id));
}
add_action('created_directorypress-category', 'classiadspro_dp_category_description_schedule_single', 10, 1);
add_action('edited_directorypress-category', 'classiadspro_dp_category_description_schedule_single', 10, 1);

function classiadspro_dp_category_description_filter_frontend($description, $term_id, $context)
{
	if (
		is_admin() ||
		wp_doing_ajax() ||
		(defined('REST_REQUEST') && REST_REQUEST) ||
		$context !== 'display' ||
		!is_string($description) ||
		trim($description) === ''
	) {
		return $description;
	}

	$raw_description = (string) get_term_field('description', (int) $term_id, 'directorypress-category', 'raw');
	$raw_description = trim($raw_description);
	if ($raw_description === '') {
		return $description;
	}

	$language = classiadspro_dp_category_description_get_current_language();
	if ($language === '' || $language === classiadspro_dp_category_description_get_default_language()) {
		return $description;
	}

	$row = classiadspro_dp_category_description_get_existing_row($raw_description, $language);
	if (!empty($row['translated']) && is_string($row['translated'])) {
		return apply_filters('term_description', $row['translated'], $term_id, 'directorypress-category', $context);
	}

	return $description;
}
add_filter('directorypress-category_description', 'classiadspro_dp_category_description_filter_frontend', 20, 3);
