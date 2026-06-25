<?php

/**
 * Class and Function List:
 * Function list:
 * - init()
 * - constants()
 * - widgets()
 * - supports()
 * - functions()
 * - language()
 * - add_metaboxes()
 * - admin()
 * - post_types()
 * - pacz_theme_enqueue_scripts()
 * - pacz_preloader_script() 
 */

function classiadspro_load_textdomain()
{
	load_theme_textdomain('classiadspro', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'classiadspro_load_textdomain');

function classiadspro_is_background_or_admin_request()
{
	return (
		is_admin() ||
		wp_doing_ajax() ||
		wp_doing_cron() ||
		(defined('REST_REQUEST') && REST_REQUEST) ||
		(defined('WP_CLI') && WP_CLI)
	);
}

function classiadspro_is_translatepress_editor_request()
{
	if (function_exists('trp_is_translation_editor') && trp_is_translation_editor()) {
		return true;
	}

	return !empty($_REQUEST['trp-edit-translation']) && in_array($_REQUEST['trp-edit-translation'], array('preview', 'true'), true);
}

function classiadspro_is_trp_automatic_slug_translation_disabled()
{
	$settings = get_option('trp_machine_translation_settings', array());

	return !is_array($settings) || ($settings['automatically-translate-slug'] ?? 'no') !== 'yes';
}

function classiadspro_should_disable_trp_slug_collection()
{
	return classiadspro_is_background_or_admin_request() || classiadspro_is_trp_automatic_slug_translation_disabled();
}

/**
 * Avoid TranslatePress slug deadlocks in admin-side DirectoryPress flows.
 *
 * TranslatePress SEO Pack translates internal links by hooking permalink filters.
 * In wp-admin, DirectoryPress screens can trigger several parallel requests that
 * resolve the same listing URLs and race on wp_trp_slug_originals inserts.
 *
 * Keep slug translation enabled on the public site, but disable these hooks for
 * admin, AJAX and REST contexts where translated internal permalinks are not
 * needed and have been causing deadlocks.
 */
function classiadspro_limit_trp_slug_translation_hooks_in_admin($hooks)
{
	if (classiadspro_is_background_or_admin_request()) {
		return array();
	}

	return $hooks;
}
add_filter('trp_translatable_slug_hooks_array', 'classiadspro_limit_trp_slug_translation_hooks_in_admin');

/**
 * Disable TranslatePress SEO Pack automatic slug collection when it cannot be used.
 *
 * The SEO Pack doesn't rely only on permalink hooks. It also hooks into
 * trp_translateable_strings / trp_translateable_information and records
 * original slugs even when automatic slug translation is disabled. That can
 * make normal translated frontend requests race on wp_trp_slug_originals.
 */
function classiadspro_disable_trp_slug_collection_when_unavailable()
{
	if (!classiadspro_should_disable_trp_slug_collection()) {
		return;
	}

	$targets = array(
		'trp_translateable_strings' => 'include_slug_for_machine_translation',
		'trp_translateable_information' => 'save_machine_translated_slug',
	);

	foreach ($targets as $hook_name => $method_name) {
		if (empty($GLOBALS['wp_filter'][$hook_name]) || !($GLOBALS['wp_filter'][$hook_name] instanceof WP_Hook)) {
			continue;
		}

		$hook = $GLOBALS['wp_filter'][$hook_name];
		foreach ($hook->callbacks as $priority => $callbacks) {
			foreach ($callbacks as $callback_data) {
				if (
					empty($callback_data['function']) ||
					!is_array($callback_data['function']) ||
					!is_object($callback_data['function'][0]) ||
					get_class($callback_data['function'][0]) !== 'TRP_IN_SP_Slug_Manager' ||
					$callback_data['function'][1] !== $method_name
				) {
					continue;
				}

				remove_filter($hook_name, $callback_data['function'], $priority);
			}
		}
	}
}
add_action('init', 'classiadspro_disable_trp_slug_collection_when_unavailable', 20);

/**
 * Move DP listing auto-translation out of the save_post request.
 *
 * The custom auto-translate plugin hooks an anonymous callback directly into
 * save_post_dp_listing and performs multiple remote translation calls inline.
 * That expands the critical section of a listing save and increases the chance
 * of TranslatePress slug deadlocks in admin flows.
 *
 * We cannot edit the plugin, so we unhook its closure at runtime and replace it
 * with a single background event that calls the same plugin translation
 * functions after the save request has finished.
 */
function classiadspro_remove_dp_listing_autotranslator_closure($hook_name, $start_line)
{
	if (empty($GLOBALS['wp_filter'][$hook_name]) || !($GLOBALS['wp_filter'][$hook_name] instanceof WP_Hook)) {
		return;
	}

	$target_file = wp_normalize_path(WP_PLUGIN_DIR . '/auto-translate-dp-listing-pro.php');
	$hook = $GLOBALS['wp_filter'][$hook_name];

	foreach ($hook->callbacks as $priority => $callbacks) {
		foreach ($callbacks as $callback_data) {
			if (
				empty($callback_data['function']) ||
				!($callback_data['function'] instanceof Closure)
			) {
				continue;
			}

			try {
				$reflection = new ReflectionFunction($callback_data['function']);
			} catch (ReflectionException $exception) {
				continue;
			}

			$callback_file = wp_normalize_path($reflection->getFileName());
			if ($callback_file !== $target_file || (int) $reflection->getStartLine() !== (int) $start_line) {
				continue;
			}

			remove_action($hook_name, $callback_data['function'], $priority);
		}
	}
}

function classiadspro_detach_sync_dp_listing_autotranslate()
{
	classiadspro_remove_dp_listing_autotranslator_closure('save_post_dp_listing', 291);
	classiadspro_remove_dp_listing_autotranslator_closure('wp_ajax_dp_translate_single_post_ajax', 465);
	classiadspro_remove_dp_listing_autotranslator_closure('wp_ajax_dp_translate_all_step', 499);
}
add_action('init', 'classiadspro_detach_sync_dp_listing_autotranslate', 1);

function classiadspro_schedule_dp_listing_autotranslate($post_id, $post, $update)
{
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
		return;
	}

	if (!$post instanceof WP_Post || $post->post_type !== 'dp_listing') {
		return;
	}

	if (empty(get_option('dp_translator_gemini_key')) || !function_exists('dp_get_translatepress_languages')) {
		return;
	}

	if (in_array($post->post_status, array('auto-draft', 'trash', 'inherit'), true)) {
		return;
	}

	wp_clear_scheduled_hook('classiadspro_run_dp_listing_autotranslate', array($post_id));
	wp_schedule_single_event(time() + 15, 'classiadspro_run_dp_listing_autotranslate', array($post_id));
}
add_action('save_post_dp_listing', 'classiadspro_schedule_dp_listing_autotranslate', 20, 3);

function classiadspro_run_dp_listing_autotranslate($post_id)
{
	$post_id = (int) $post_id;
	if ($post_id <= 0) {
		return;
	}

	$post = get_post($post_id);
	if (!$post instanceof WP_Post || $post->post_type !== 'dp_listing') {
		return;
	}

	if (!in_array($post->post_status, array('publish', 'pending', 'draft', 'future', 'private'), true)) {
		return;
	}

	if (empty(get_option('dp_translator_gemini_key')) || !function_exists('dp_get_translatepress_languages')) {
		return;
	}

	foreach ((array) dp_get_translatepress_languages() as $lang => $label) {
		classiadspro_generate_dp_listing_translation($post_id, $lang, false);
	}
}
add_action('classiadspro_run_dp_listing_autotranslate', 'classiadspro_run_dp_listing_autotranslate');

function classiadspro_normalize_dp_listing_translation_language($language_code)
{
	$language_code = strtolower(str_replace('-', '_', trim((string) $language_code)));
	$aliases = array(
		'ua' => 'uk',
		'uk' => 'uk',
		'uk_ua' => 'uk',
		'ru' => 'ru',
		'ru_ru' => 'ru',
		'tr' => 'tr',
		'tr_tr' => 'tr',
		'en' => 'en',
		'en_us' => 'en',
		'es' => 'es',
		'es_es' => 'es',
		'de' => 'de',
		'de_de' => 'de',
	);

	if (isset($aliases[$language_code])) {
		return $aliases[$language_code];
	}

	$short_code = strtok($language_code, '_');
	return is_string($short_code) && $short_code !== '' ? strtolower($short_code) : $language_code;
}

function classiadspro_request_dp_listing_translation($text, $language_code, $field_type = 'content')
{
	$text = (string) $text;
	if ($text === '') {
		return '';
	}

	$api_key = (string) get_option('dp_translator_gemini_key');
	if ($api_key === '') {
		return new WP_Error('dp_translator_missing_key', 'Gemini API key is missing.');
	}

	$model = (string) get_option('dp_translator_gemini_model', 'gemini-2.5-flash');
	$language_code = classiadspro_normalize_dp_listing_translation_language($language_code);
	$language_names = array(
		'en' => 'English',
		'ru' => 'Russian',
		'uk' => 'Ukrainian',
		'tr' => 'Turkish',
		'de' => 'German',
		'es' => 'Spanish',
	);
	$language_name = isset($language_names[$language_code]) ? $language_names[$language_code] : strtoupper($language_code);

	switch ($field_type) {
		case 'title':
		case 'seo_title':
			$prompt = "Translate this classifieds listing title into {$language_name} (ISO 639-1: {$language_code}). "
				. "Keep brand names, model numbers, and technical codes unchanged. Translate descriptive words naturally. "
				. "Return only the translated title.\n\n{$text}";
			break;

		case 'seo_description':
			$prompt = "Translate this SEO description into {$language_name} (ISO 639-1: {$language_code}). "
				. "Preserve meaning and make the result natural for search snippets. Return only the translated description.\n\n{$text}";
			break;

		case 'term_description':
			$prompt = "Translate this classifieds category description into {$language_name} (ISO 639-1: {$language_code}). "
				. "Preserve the meaning, HTML markup, formatting, links, and a natural directory category tone. "
				. "Return only the translated description.\n\n{$text}";
			break;

		default:
			$prompt = "Translate the following classifieds listing text into {$language_name} (ISO 639-1: {$language_code}). "
				. "Preserve meaning, formatting, and tone. Return only the translated text.\n\n{$text}";
			break;
	}

	$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$api_key}";
	$body = wp_json_encode(
		array(
			'contents' => array(
				array(
					'parts' => array(
						array('text' => $prompt),
					),
				),
			),
			'generationConfig' => array(
				'temperature' => 0,
			),
		)
	);

	$response = wp_remote_post(
		$url,
		array(
			'headers' => array('Content-Type' => 'application/json'),
			'body' => $body,
			'timeout' => 30,
		)
	);

	if (is_wp_error($response)) {
		return $response;
	}

	$data = json_decode(wp_remote_retrieve_body($response), true);
	$status_code = (int) wp_remote_retrieve_response_code($response);

	if ($status_code >= 400 || !empty($data['error'])) {
		$message = isset($data['error']['message']) && is_string($data['error']['message'])
			? trim($data['error']['message'])
			: 'Translation request failed.';

		return new WP_Error('dp_translator_api_error', $message, array('status' => $status_code));
	}

	$translated_text = '';
	if (!empty($data['candidates'][0]['content']['parts']) && is_array($data['candidates'][0]['content']['parts'])) {
		foreach ($data['candidates'][0]['content']['parts'] as $part) {
			if (!empty($part['text']) && is_string($part['text'])) {
				$translated_text .= $part['text'];
			}
		}
	}

	$translated_text = trim($translated_text);
	if ($translated_text === '') {
		return new WP_Error('dp_translator_empty_response', 'Translation service returned an empty response.');
	}

	return $translated_text;
}

function classiadspro_generate_dp_listing_translation($post_id, $language_code, $force = false)
{
	$post_id = (int) $post_id;
	if ($post_id <= 0 || get_post_type($post_id) !== 'dp_listing') {
		return false;
	}

	$storage_key = trim((string) $language_code);
	if ($storage_key === '') {
		return false;
	}

	$machine_language_code = classiadspro_normalize_dp_listing_translation_language($storage_key);
	if ($machine_language_code === '') {
		return false;
	}

	$translations = get_post_meta($post_id, 'translations', true);
	if (!is_array($translations)) {
		$translations = array();
	}

	if (empty($translations[$storage_key]) || !is_array($translations[$storage_key])) {
		$translations[$storage_key] = array();
	}

	$original_title = (string) get_post_field('post_title', $post_id);
	$original_content = (string) get_post_field('post_content', $post_id);
	$seo_title = (string) get_post_meta($post_id, '_yoast_wpseo_title', true);
	if ($seo_title === '') {
		$seo_title = $original_title;
	}

	$seo_description = (string) get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
	if ($seo_description === '') {
		$seo_description = mb_substr(wp_strip_all_tags($original_content), 0, 160);
	}

	$updated = false;
	$errors = array();

	if ($force || empty($translations[$storage_key]['title'])) {
		$translated_title = classiadspro_request_dp_listing_translation($original_title, $machine_language_code, 'title');
		if (is_wp_error($translated_title)) {
			$errors[] = $translated_title->get_error_message();
		} else {
			$translations[$storage_key]['title'] = $translated_title;
			$updated = true;
		}
	}

	if ($force || empty($translations[$storage_key]['content'])) {
		$translated_content = classiadspro_request_dp_listing_translation($original_content, $machine_language_code, 'content');
		if (is_wp_error($translated_content)) {
			$errors[] = $translated_content->get_error_message();
		} else {
			$translations[$storage_key]['content'] = $translated_content;
			$updated = true;
		}
	}

	if (empty($translations[$storage_key]['seo']) || !is_array($translations[$storage_key]['seo'])) {
		$translations[$storage_key]['seo'] = array();
	}

	if ($force || empty($translations[$storage_key]['seo']['title'])) {
		$translated_seo_title = classiadspro_request_dp_listing_translation($seo_title, $machine_language_code, 'seo_title');
		if (is_wp_error($translated_seo_title)) {
			$errors[] = $translated_seo_title->get_error_message();
		} else {
			$translations[$storage_key]['seo']['title'] = $translated_seo_title;
			$updated = true;
		}
	}

	if ($force || empty($translations[$storage_key]['seo']['description'])) {
		$translated_seo_description = classiadspro_request_dp_listing_translation($seo_description, $machine_language_code, 'seo_description');
		if (is_wp_error($translated_seo_description)) {
			$errors[] = $translated_seo_description->get_error_message();
		} else {
			$translations[$storage_key]['seo']['description'] = $translated_seo_description;
			$updated = true;
		}
	}

	if (
		($force || empty($translations[$storage_key]['seo']['keywords'])) &&
		!empty($translations[$storage_key]['title']) &&
		!empty($translations[$storage_key]['content'])
	) {
		$translations[$storage_key]['seo']['keywords'] = dp_generate_keywords_translated(
			array(
				'title' => isset($translations[$storage_key]['title']) ? (string) $translations[$storage_key]['title'] : '',
				'content' => wp_strip_all_tags(isset($translations[$storage_key]['content']) ? (string) $translations[$storage_key]['content'] : ''),
			),
			$machine_language_code,
			8
		);
		$updated = true;
	}

	if ($updated) {
		$translations[$storage_key]['_hashes'] = array(
			'title' => function_exists('dp_translation_hash') ? dp_translation_hash($original_title) : md5(trim(wp_strip_all_tags($original_title))),
			'content' => function_exists('dp_translation_hash') ? dp_translation_hash($original_content) : md5(trim(wp_strip_all_tags($original_content))),
		);

		update_post_meta($post_id, 'translations', $translations);
	}

	if (!empty($errors)) {
		return new WP_Error('dp_translation_failed', reset($errors));
	}

	return $updated;
}

function classiadspro_ajax_translate_single_dp_listing()
{
	check_ajax_referer('dp_translate_single_post_nonce', 'nonce');

	$post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
	if ($post_id <= 0 || !current_user_can('edit_post', $post_id)) {
		wp_send_json_error('Нет прав');
	}

	$errors = array();
	foreach ((array) dp_get_translatepress_languages() as $language_code => $label) {
		$result = classiadspro_generate_dp_listing_translation($post_id, $language_code, true);
		if (is_wp_error($result)) {
			$errors[] = $result->get_error_message();
		}
	}

	if (!empty($errors)) {
		wp_send_json_error(reset($errors));
	}

	wp_send_json_success();
}
add_action('wp_ajax_dp_translate_single_post_ajax', 'classiadspro_ajax_translate_single_dp_listing', 20);

function classiadspro_ajax_translate_all_dp_listings_step()
{
	if (!current_user_can('manage_options')) {
		wp_send_json_error('Нет прав');
	}

	$offset = isset($_POST['offset']) ? (int) $_POST['offset'] : 0;
	$query = new WP_Query(array(
		'post_type' => 'dp_listing',
		'posts_per_page' => 1,
		'offset' => $offset,
		'post_status' => 'publish',
		'fields' => 'ids',
	));

	if (!$query->have_posts()) {
		wp_send_json_success(array('done' => true, 'progress' => 100));
	}

	$post_id = (int) $query->posts[0];
	$errors = array();
	foreach ((array) dp_get_translatepress_languages() as $language_code => $label) {
		$result = classiadspro_generate_dp_listing_translation($post_id, $language_code, true);
		if (is_wp_error($result)) {
			$errors[] = $result->get_error_message();
		}
	}

	if (!empty($errors)) {
		wp_send_json_error(reset($errors));
	}

	$total = wp_count_posts('dp_listing')->publish ?: 1;
	$progress = min(100, round((($offset + 1) / $total) * 100));

	wp_send_json_success(array('done' => false, 'progress' => $progress));
}
add_action('wp_ajax_dp_translate_all_step', 'classiadspro_ajax_translate_all_dp_listings_step', 20);

/**
 * Resolve a stable DirectoryPress dashboard URL even when the plugin-global
 * dashboard page URL was not initialized before header widgets render.
 *
 * DirectoryPress normally populates this on `init`, but some header render
 * paths can ask for the URL earlier and fall back to the directory archive.
 */
function classiadspro_get_directorypress_dashboard_base_url()
{
	static $dashboard_base_url = null;
	global $wpdb;

	if (is_string($dashboard_base_url) && $dashboard_base_url !== '') {
		return $dashboard_base_url;
	}

	$dashboard_base_url = '';

	if (function_exists('directorypress_dashboardUrl')) {
		$candidate = directorypress_dashboardUrl();
		if (is_string($candidate) && $candidate !== '' && $candidate !== '/' && $candidate !== home_url('/')) {
			$dashboard_base_url = $candidate;
			return $dashboard_base_url;
		}
	}

	$dashboard_page_id = (int) $wpdb->get_var(
		"SELECT ID
		FROM {$wpdb->posts}
		WHERE post_content LIKE '%[directorypress-dashboard]%'
			AND post_status = 'publish'
			AND post_type = 'page'
		LIMIT 1"
	);

	if ($dashboard_page_id > 0) {
		$dashboard_base_url = get_permalink($dashboard_page_id);
	}

	if (!$dashboard_base_url && function_exists('directorypress_dashboardUrl')) {
		$dashboard_base_url = directorypress_dashboardUrl();
	}

	if (!$dashboard_base_url) {
		$dashboard_base_url = home_url('/');
	}

	return $dashboard_base_url;
}

function classiadspro_get_directorypress_dashboard_url($args = array())
{
	$base_url = classiadspro_get_directorypress_dashboard_base_url();

	if (!is_array($args) || empty($args)) {
		return $base_url;
	}

	return add_query_arg($args, $base_url);
}

function classiadspro_redirect_directorypress_dashboard_actions()
{
	if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
		return;
	}

	if (empty($_GET['directory_action'])) {
		return;
	}

	$dashboard_actions = array(
		'profile',
		'messages',
		'notification_settings',
		'edit_advert',
		'raiseup_listing',
		'renew_listing',
		'upgrade_listing',
		'claim_listing',
		'process_claim',
		'add_translation',
	);

	$action = sanitize_key(wp_unslash($_GET['directory_action']));
	if (!in_array($action, $dashboard_actions, true)) {
		return;
	}

	$request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
	$current_request_url = home_url('/');
	if (!empty($_SERVER['HTTP_HOST'])) {
		$current_request_url = (is_ssl() ? 'https://' : 'http://') . wp_unslash($_SERVER['HTTP_HOST']) . $request_uri;
	}

	$current_url = $current_request_url;
	$dashboard_url = classiadspro_get_directorypress_dashboard_url(wp_unslash($_GET));

	$current_url_normalized = untrailingslashit($current_url);
	$dashboard_url_normalized = untrailingslashit($dashboard_url);

	if ($current_url_normalized === $dashboard_url_normalized) {
		return;
	}

	$current_path = wp_parse_url($current_url, PHP_URL_PATH);
	$dashboard_path = wp_parse_url($dashboard_url, PHP_URL_PATH);

	if (
		!$dashboard_path ||
		untrailingslashit((string) $current_path) === untrailingslashit((string) $dashboard_path)
	) {
		return;
	}

	wp_safe_redirect($dashboard_url, 302);
	exit;
}
add_action('template_redirect', 'classiadspro_redirect_directorypress_dashboard_actions', 1);
require_once get_template_directory() . '/includes/actions/term-ru-sync.php';

function classiadspro_register_login_menu_widget_override($widgets_manager)
{
	if (
		!class_exists('Header_Footer_Builder') ||
		!class_exists('\Elementor\Widget_Base') ||
		class_exists('\HFB\WidgetsManager\Widgets\Pacz_Elementor_Login')
	) {
		return;
	}

	require_once get_template_directory() . '/includes/elementor/login.php';

	if (
		is_object($widgets_manager) &&
		method_exists($widgets_manager, 'register') &&
		class_exists('\HFB\WidgetsManager\Widgets\Pacz_Elementor_Login')
	) {
		$widgets_manager->register(new \HFB\WidgetsManager\Widgets\Pacz_Elementor_Login());
	}
}
add_action('elementor/widgets/register', 'classiadspro_register_login_menu_widget_override', 0);

// Load Firebase Push Notifications
require_once get_template_directory() . '/includes/actions/firebase.php';

// Load Login Menu Messages Extension (using JavaScript/filters approach instead of class override)
require_once get_template_directory() . '/includes/actions/login-menu-messages.php';
require_once get_template_directory() . '/includes/actions/page-ru-sync.php';
require_once get_template_directory() . '/includes/actions/term-description-cron-translation.php';

// Load Advertising System
if (class_exists('DirectoryPress') && class_exists('WooCommerce')) {
	require_once get_template_directory() . '/includes/advertising/functions.php';
	require_once get_template_directory() . '/includes/advertising/class-advertising-manager.php';
	require_once get_template_directory() . '/includes/advertising/class-advertising-admin.php';
	require_once get_template_directory() . '/includes/advertising/class-advertising-woocommerce.php';
	require_once get_template_directory() . '/includes/advertising/class-advertising-display.php';
	require_once get_template_directory() . '/includes/advertising/class-advertising-cron.php';

	// Create advertise page on theme activation
	add_action('after_switch_theme', 'classiadspro_create_advertise_page');
}

/**
 * Настройка количества рекламируемых товаров в листинге
 * 
 * Фильтр позволяет изменить количество рекламируемых товаров,
 * которые отображаются в блоке "Рекомендуемые объявления"
 * 
 * @param int $count Количество рекламируемых товаров (по умолчанию 3)
 * @return int
 */
function classiadspro_advertised_listings_count($count)
{
	// Вы можете изменить это число на любое другое
	return 3;
}
add_filter('classiadspro_advertised_listings_count', 'classiadspro_advertised_listings_count');

/**
 * Заголовок блока рекламируемых товаров
 * 
 * Фильтр позволяет изменить заголовок блока рекламируемых товаров
 * 
 * @param string $title Заголовок блока
 * @return string
 */
function classiadspro_advertised_listings_title($title)
{
	// Вы можете изменить текст заголовка
	return __('Recommendations', 'classiadspro');
}
add_filter('classiadspro_advertised_listings_title', 'classiadspro_advertised_listings_title');

if (!function_exists('classiadspro_get_listing_url')) {
	function classiadspro_get_listing_url($listing)
	{
		if (is_numeric($listing)) {
			$post_id = (int) $listing;
		} elseif (is_object($listing) && isset($listing->post->ID)) {
			$post_id = (int) $listing->post->ID;
		} else {
			$post_id = 0;
		}

		if (!$post_id) {
			return home_url('/');
		}

		if (function_exists('directorypress_get_archive_page')) {
			$archive_page = directorypress_get_archive_page();
			$post = get_post($post_id);

			if (!empty($archive_page['url']) && $post && !empty($post->post_name)) {
				return trailingslashit($archive_page['url']) . trailingslashit($post->post_name);
			}
		}

		if (function_exists('directorypress_directory_type_of_listing') && function_exists('directorypress_directorytype_url')) {
			$directorytype = directorypress_directory_type_of_listing($post_id);
			$post = get_post($post_id);

			if ($directorytype && $post && !empty($post->post_name)) {
				return directorypress_directorytype_url($post->post_name, $directorytype);
			}
		}

		return get_permalink($post_id);
	}
}

if (!function_exists('classiadspro_is_listing_advertising_available')) {
	function classiadspro_is_listing_advertising_available($listing)
	{
		if (!is_object($listing)) {
			return false;
		}

		$listing_status = isset($listing->status) ? (string) $listing->status : '';
		if ($listing_status === 'active') {
			return true;
		}

		$post_status = '';
		if (isset($listing->post) && $listing->post instanceof WP_Post) {
			$post_status = (string) $listing->post->post_status;
		}

		return $post_status === 'publish';
	}
}

/**
 * Create advertise page
 */
function classiadspro_create_advertise_page()
{
	$page_title = 'Advertise Listing';
	$page_slug = 'advertise-listing';

	// Check if page already exists
	$existing_page = get_page_by_path($page_slug);
	if ($existing_page) {
		return;
	}

	// Create the page
	$page_data = array(
		'post_title' => $page_title,
		'post_name' => $page_slug,
		'post_content' => '',
		'post_status' => 'publish',
		'post_type' => 'page',
		'post_author' => 1,
	);

	$page_id = wp_insert_post($page_data);

	if ($page_id && !is_wp_error($page_id)) {
		// Set the page template
		update_post_meta($page_id, '_wp_page_template', 'page-advertise-listing.php');
	}
}

/**
 * Create WooCommerce products for advertising
 */
function classiadspro_create_advertising_products()
{
	if (!class_exists('WC_Product')) {
		return;
	}

	$prices = classiadspro_get_advertising_prices();
	$periods = array(
		'1_day' => 'Advertise Listing (1 day)',
		'3_days' => 'Advertise Listing (3 days)',
		'7_days' => 'Advertise Listing (7 days)',
	);

	foreach ($periods as $period => $title) {
		$existing_id = get_option('classiadspro_advertising_product_' . $period, 0);

		// Check if product already exists
		if ($existing_id && get_post($existing_id)) {
			continue;
		}

		// Create new product
		$product = new WC_Product_Simple();
		$product->set_name($title);
		$product->set_regular_price($prices[$period]);
		$product->set_virtual(true);
		$product->set_sold_individually(true);
		$product->set_catalog_visibility('hidden');
		$product->save();

		update_option('classiadspro_advertising_product_' . $period, $product->get_id());
	}
}

/**
 * Ensure advertising products exist
 */
function classiadspro_ensure_advertising_products()
{
	if (!class_exists('WC_Product') || !class_exists('DirectoryPress')) {
		return;
	}

	$product_ids = classiadspro_get_advertising_product_ids();

	// Check if any products are missing
	$missing_products = false;
	foreach ($product_ids as $period => $product_id) {
		if (!$product_id || !get_post($product_id)) {
			$missing_products = true;
			break;
		}
	}

	// Create products if any are missing
	if ($missing_products) {
		classiadspro_create_advertising_products();
	}
}

$theme = new Classiadspro_Theme();
$theme->init(array(
	"theme_name" => "Classiadspro",
	"theme_slug" => "classiadspro",
));

class Classiadspro_Theme
{
	function init($options)
	{
		$this->pacz_constants($options);

		add_action('init', array(
			&$this,
			'pacz_add_metaboxes',
		));

		add_action('after_setup_theme', array(
			&$this,
			'pacz_supports',
		));
		add_action('after_setup_theme', array(
			&$this,
			'pacz_settings',
		));
		add_action('after_setup_theme', array(
			&$this,
			'pacz_functions',
		), 20);
		add_action('after_setup_theme', array(
			&$this,
			'pacz_admin',
		), 20);
	}
	function pacz_settings()
	{
		global $pacz_settings;
		if (class_exists('Classiadspro_Core')) {
			$pacz_settings = get_option('pacz_settings');
		} else {

			$data = '{"last_tab":"","body-layout":"full","grid-width":"1170","content-width":"67","pages-padding":{"1":"70","2":"70"},"archive-pages-padding":{"1":"70","2":"70"},"single-pages-padding":{"1":"70","2":"70"},"body-bg":{"background-color":"#f7f7f7","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"remove-js-css-ver":"1","mobile_front_page":"","pages-layout":"right","page-title-pages":"1","page-bg":{"background-color":"#f7f7f7","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"page-title-bg":{"background-color":"#191a1f","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"page-title-color":"#FFFFFF","breadcrumb":"1","pages-comments":"1","custom-sidebar":[],"error_page":"2","error_page_id":"9807","error-layout":"full","error_page_small_text":"Far far away, behind the word mountains, far from the countries Vokalia and there live the blind texts. Sepraed. they live in Boo marksgrove right at the coast of the Semantics, a large language ocean A small river named Duden flows by their place and su plies it.","search-layout":"full","checkbox_styles":"2","res-nav-width":"1170","preset_headers":"11","_header_style":"block_module","preset_headers_skin":"","header-structure":"standard","header-location":"top","vertical-header-state":"expanded","header-vertical-width":"280","header-padding":"30","header-padding-vertical":"30","header-align":"left","nav-alignment":"right","boxed-header":"1","header-grid":"0","header-grid_postion":"","header-grid-margin-top":"0","_header_search_form":"0","sticky-header":"0","squeeze-sticky-header":"0","sticky_header_offset":"0","header-hover-style":"","header-border-top":"0","header-search":"0","header-search-location":"right","loggedin_menu":"primary-menu","header-bg":{"background-color":"#ffffff","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"theader-bg":{"color":"","alpha":"1","rgba":"rgba(0,0,0,1)"},"header-bottom-border":"","header_shadow":"1","header-toolbar":"0","toolbar-grid":"0","toolbar-custom-menu":"","toolbar_height":"100","toolbar-font":{"font-family":"Lexend Deca","font-options":"","google":"1","font-weight":"400","font-style":"","text-align":"","font-size":"14px"},"toolbar-bg":{"background-color":"#ffffff","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"toolbar-border-top":"1","toolbar-border-bottom-color":"#EEEEEE","main-nav-font":{"font-family":"Lexend Deca","font-options":"Roboto","google":"1","font-weight":"500","font-style":"","text-align":"","font-size":"14px"},"main-nav-item-space":"15","vertical-nav-item-space":"0","main-nav-top-transform":"capitalize","sub-nav-top-size":"14","sub-nav-top-transform":"capitalize","sub-nav-top-weight":"normal","main-nav-top-color":{"regular":"#191a1f","hover":"#eb6752","bg":"","bg-hover":"","bg-active":"#ffffff"},"main-nav-top-color-transparent":{"regular":"#fff","hover":"#eb6752","bg":"","bg-hover":"","bg-active":""},"main-nav-sub-bg":"#FFFFFF","main-nav-sub-color":{"regular":"#191a1f","hover":"#222222","bg":"#ffffff","bg-hover":"#fbf7f6","bg-active":"#f1f1f1"},"navigation-border-top":"1","header-logo-location":"header_section","header-logo-align":"left","logo_dimensions":"50","logo":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"transparent-logo":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo2.png","id":"9570","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo2-150x43.png"},"logo-retina":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"transparent-logo-retina":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo2.png","id":"9570","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo2-150x43.png"},"pacz-logreg-header-btn":"0","pacz-login-slug":"login","pacz-register-slug":"register","pacz-forgot-slug":"forget-password","header-login-reg-location":"header_section","log-reg-btn-align":"right","listing-btn-location":"header_section","listing-btn-align":"right","listing-btn-text":"Post Your Ad","listing_button_padding":{"units":"px","padding-top":"","padding-right":"","padding-bottom":"","padding-left":""},"listing_button_border_width":"0","listing_button_border_radius":{"units":"px","padding-top":"","padding-right":"","padding-bottom":"","padding-left":""},"listing-header-btn-color":{"regular":"#ffffff","hover":"#ffffff","bg":"#191a1f","bg-hover":"#eb6653"},"listing-header-btn-color-transparent":{"regular":"#ffffff","hover":"#ffffff","bg":"#191a1f","bg-hover":"#eb6653"},"header_listing_button_border_color":{"color":"","alpha":"1","rgba":"rgba(0,0,0,1)"},"header_listing_button_border_color_transparent":{"color":"","alpha":"1","rgba":"rgba(0,0,0,1)"},"header_listing_button_border_color_hover":{"color":"","alpha":"1","rgba":"rgba(0,0,0,1)"},"header_listing_button_border_color_hover_transparent":{"color":"","alpha":"1","rgba":"rgba(0,0,0,1)"},"search_keyword_field":"1","search_keyword_ajax_field":"1","search_keyword_categories_field":"1","search_address_field":"1","search_address_locations_field":"1","search_button_icon":"fas fa-search-plus","header_search_button_border_radius":{"units":"px","padding-top":"","padding-right":"","padding-bottom":"","padding-left":""},"header-search-icon-color":"#222222","header-contact-select":"header_toolbar","header-contact-align":"right","header-toolbar-phone":"","header-toolbar-phone-icon":"","header-toolbar-email":"","header-toolbar-email-icon":"","toolbar-text-color":"#546B7E","toolbar-phone-email-icon-color":"#FFFFFF","toolbar-link-color":{"regular":"#546b7e","hover":"#eb6653"},"toolbar-social-link-color":{"regular":"#ffffff","hover":"#eb6653","bg":"","bg-hover":""},"toolbar-social-link-color-bg":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"header-social-select":"disabled","header-social-align":"left","header-social-facebook":"","header-social-twitter":"","header-social-rss":"","header-social-dribbble":"","header-social-pinterest":"","header-social-instagram":"","header-social-google-plus":"","header-social-linkedin":"","header-social-youtube":"","header-social-vimeo":"","header-social-spotify":"","header-social-tumblr":"","header-social-behance":"","header-social-WhatsApp":"","header-social-qzone":"","header-social-vkcom":"","header-social-imdb":"","header-social-renren":"","header-social-weibo":"","checkout-box":"0","checkout-box-location":"disabled","checkout-box-align":"right","header_cart_link_color":{"regular":"#ffffff","hover":"#ffffff","bg":"#eb6653","bg-hover":"#eb6653"},"header-wpml":"0","mobile-header-bg":{"background-color":"#ffffff","background-repeat":"repeat","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"mobile-logo":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"mobile-logo-retina":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"mobile-listing-button":"0","mobile-listing-button-skin":{"regular":"#1c1e21","hover":"#fff","bg":"#F2F3F5","bg-hover":"#eb6653"},"mobile-listing-button-icon":"fas fa-plus","mobile-login-button":"0","mobile-login-button-skin":{"regular":"#1c1e21","hover":"#fff","bg":"#F2F3F5","bg-hover":"#eb6653"},"mobile-login-button-icon":"far fa-user","mobile-search-button":"0","mobile-search-button-skin":{"regular":"#1c1e21","hover":"#fff","bg":"#F2F3F5","bg-hover":"#eb6653"},"mobile-search-button-icon":"fas fa-search","mobile-header-author-bg":{"background-color":"#2081cc","background-repeat":"repeat","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2017\/11\/9-1-150x150.jpg"}},"mobile-header-author-display-name-color":"#333333","mobile-header-author-nickname-color":"#FFFFFF","mobile-header-author-links-color":{"regular":"#393c71","hover":"#393c71"},"mobile-header-menu-icon-color":{"regular":"#1c1e21","hover":"#1c1e21","active":"#eb6653"},"mobile-header-menu-wrapper-bg":{"background-color":"#fff","background-repeat":"repeat","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"mobile-nav-top-color":{"regular":"#333333","hover":"#eb6653","bg":"#fff","bg-hover":"","bg-active":""},"mobile-top-menu-border-color":"#EEEEEE","mobile-nav-sub-menu-color":{"regular":"#333","hover":"#fff","bg":"#f5f5f5","bg-hover":"#555","bg-active":"#333"},"footer":"1","footer-layout":"5","top-footer":"0","footer_form_style":"4","footer_top_logo":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"form_id":"5509","sub-footer":"1","back-to-top":"0","back_to_top_style":"4","footer_sell_btn":"1","sell_btn_text":"Sell","footer-copyright":"All Copyrights reserved @ 2022 - Design by Designinvento","subfooter-logos-src":{"url":"","id":"","height":"","width":"","thumbnail":""},"subfooter-logos-link":"","footer-bg":{"background-color":"#191a1f","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"sub-footer-bg":"#191A1F","top-footer-bg":"#FFFFFF","footer-title-color":"#FFFFFF","footer-txt-color":"#A9A9A9","footer-link-color":{"regular":"#a9a9a9","hover":"#ffffff","active":"eb6653"},"footer-recent-lisitng-border-color":"transparent","sub-footer-border-top":"1","sub-footer-border-top-color":{"color":"#ffffff","alpha":"0.1","rgba":"rgba(255,255,255,0.1)"},"footer-col-border":"0","footer-col-border-color":"#EEEEEE","footer-social-color":{"regular":"#ffffff","hover":"#ffffff","bg":"#24252a","bg-hover":"#eb6653"},"footer-socket-color":"#A9A9A9","footer-social-location":"1","social-facebook":"#","social-twitter":"#","social-rss":"","social-dribbble":"#","social-pinterest":"","social-instagram":"","social-google-plus":"","social-linkedin":"#","social-youtube":"#","social-vimeo":"","social-spotify":"","social-tumblr":"","social-behance":"","social-whatsapp":"","social-wechat":"","social-qzone":"","social-vkcom":"","social-imdb":"","social-renren":"","social-weibo":"","widget-title":{"font-family":"","font-options":"","google":"1","font-weight":"","font-style":"","subsets":"","text-align":"","font-size":"18px"},"sidebar-title-color":"#333333","sidebar-txt-color":"#546B7E","sidebar-link-color":{"regular":"#546b7e","hover":"#546b7e","active":"#eb6653"},"sidebar-widget-background-color":"#FFFFFF","sidebar-widget-border":{"border-top":"","border-right":"","border-bottom":"","border-left":"","border-style":"solid","border-color":""},"sidebar-widget-box-shadow":{"drop-shadow":{"checked":"1","color":"","horizontal":"0","vertical":"0","blur":"0","spread":"0"}},"sidebar-widget-border-radius":"4","body-font":{"font-family":"DM Sans","font-options":"Roboto","google":"1","font-backup":"","font-weight":"400","font-style":"","subsets":"","text-align":"","font-size":"14px"},"heading-font":{"font-family":"DM Sans","font-options":"Roboto","google":"1","font-weight":"700","font-style":"","subsets":"latin","text-align":""},"heading-font-h2":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"700","font-style":"","subsets":"","text-align":""},"heading-font-h3":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"700","font-style":"","subsets":"","text-align":""},"heading-font-h4":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"700","font-style":"","subsets":"","text-align":""},"heading-font-h5":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"700","font-style":"","subsets":"","text-align":""},"heading-font-h6":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"700","font-style":"","subsets":"","text-align":""},"headings_font_family":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"","font-style":"","subsets":"","text-align":""},"buttons_font_family":{"font-family":"DM Sans","font-options":"","google":"1","font-weight":"","font-style":"","subsets":"","text-align":""},"page-title-size":"36","p-text-size":"14","p-line-height":"26","footer-p-text-size":"14","typekit-id":"","typekit-font-family":"","typekit-element-names":"","accent-color":"#EB6653","secondary-color":"","third-color":"","body-txt-color":"#546B7E","heading-color":"#191A1F","link-color":{"regular":"#546b7e","hover":"#546b7e","active":"#eb6653"},"btn-hover":"#EB6653","subs-btn-hover":"#EB6653","breadcrumb-skin":"light","breadcrumb-skin-custom":{"regular":"#ffffff","hover":"#ffffff"},"custom-css":"","custom-js":"","preloader-bg-color":"#FFFFFF","preloader-logo":{"url":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo.png","id":"9558","height":"43","width":"184","thumbnail":"https:\/\/classiads.designinvento.net\/elementor\/classiads-ultra\/wp-content\/uploads\/2022\/11\/Classiads-Logo-150x43.png"},"page-title-blog":"1","blog-featured-image":"1","blog-image-crop":"1","blog-single-image-height":"380","blog-grid-image-width":"370","blog-grid-image-height":"230","blog-single-about-author":"1","blog-single-social-share":"1","blog-single-comments":"1","archive-layout":"right","archive-columns":"1","archive-loop-style":"classic","archive-page-title":"1","single-post-content-box-background":"#FFFFFF","single-post-comments-box-background":"#FFFFFF","single-post-content-box-border":{"border-top":"","border-right":"","border-bottom":"","border-left":"","border-style":"solid","border-color":""},"single-post-content-box-shadow":{"drop-shadow":{"checked":"1","color":"","horizontal":"0","vertical":"0","blur":"0","spread":"0"}},"single-post-content-box-border-radius":"4","woo-shop-layout":"full","woo-shop-columns":"4","woo-loop-thumb-height":"270","woo_loop_image_size":"crop","woo-single-thumb-height":"480","woo_single_image_size":"crop","woo-single-layout":"full","woo-single-related-columns":"4","woo-image-quality":"1","woo-single-title":"1","woo-single-show-title":"1","woo-shop-loop-title":"1","woo-bg":{"background-color":"#ffffff","background-repeat":"repeat","background-size":"","background-attachment":"scroll","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"pacz-woo-loop-product_title":{"font-family":"","font-options":"","google":"1","font-weight":"","font-style":"","text-align":"","font-size":"","line-height":""},"pacz-woo-loop-product_title-color":"#333333","pacz-woo-loop-product_title-color-hover":"","pacz-woo-loop-product_cat":{"font-family":"","font-options":"","google":"1","font-weight":"","font-style":"","text-align":"","font-size":"","line-height":""},"pacz-woo-loop-product_cat-color":"#546B7E","pacz-woo-loop-product_price":{"font-family":"","font-options":"","google":"1","font-weight":"","font-style":"","text-align":"","font-size":"","line-height":""},"pacz-woo-loop-product_price-color":"#EF5D50","pacz-woo-product_sale-tag-color":"#FFFFFF","pacz-woo-product_sale-tag-background-color":"#0B93D7","pacz-woo-product_addtocart-icon-color":"#546B7E","pacz-woo-product_addtocart-icon-color-hover":"#FFFFFF","pacz-woo-product_addtocart-background-color":"#FFFFFF","pacz-woo-product_addtocart-background-color-hover":"#EF5D50","pacz-woo-product_addtocart-border":{"border-top":"2px","border-right":"2px","border-bottom":"2px","border-left":"2px","border-style":"solid","border-color":"#cfd9e0"},"pacz-woo-product_addtocart-border-hover":{"border-top":"","border-right":"","border-bottom":"","border-left":"","border-style":"solid","border-color":""},"pacz-woo-product_addtocart-border-radius":"4","pacz-woo-product_wishlist-icon-color":"#8C969B","pacz-woo-product_wishlist-icon-color-hover":"#EF5D50","pacz-woo-product_wishlist-background-color":"#FFFFFF","pacz-woo-product_wishlist-background-color-hover":"#FFFFFF","pacz-woo-product_wishlist-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid","border-color":"#cfd9e0"},"pacz-woo-product_wishlist-border-hover":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid","border-color":"#ef5d50"},"pacz-woo-product_wishlist-border-radius":"4","product-loop-wrapper-bg":"#FFFFFF","product-loop-wrapper-bg-hover":"#FFFFFF","product-loop-wrapper-border":{"border-top":"","border-right":"","border-bottom":"","border-left":"","border-style":"solid","border-color":""},"product-loop-wrapper-border-hover":{"border-top":"","border-right":"","border-bottom":"","border-left":"","border-style":"solid","border-color":""},"product-loop-wrapper-border-radius":"4","product-loop-wrapper-box-shadow":{"drop-shadow":{"checked":"1","color":"","horizontal":"0","vertical":"0","blur":"0","spread":"0"}},"product-loop-wrapper-box-shadow-hover":{"drop-shadow":{"checked":"1","color":"","horizontal":"0","vertical":"0","blur":"0","spread":"0"}},"product-loop-wrapper_padding":{"units":"px","padding-top":"","padding-right":"","padding-bottom":"","padding-left":""},"product-loop-content_padding":{"units":"px","padding-top":"","padding-right":"","padding-bottom":"","padding-left":""},"redux_font_control":{"convert":""},"typekit-info":"","redux-backup":1}';
			$pacz_settings = json_decode($data, true);
		}
	}
	function pacz_constants($options)
	{
		$theme_data = wp_get_theme("classiadspro");
		$pacz_parent_theme = get_file_data(
			get_template_directory() . '/style.css',
			array('Asset Version'),
			get_template()
		);
		define("PACZ_THEME_DIR", get_template_directory());
		define("PACZ_THEME_DIR_URI", get_template_directory_uri());
		define("PACZ_THEME_NAME", $options["theme_name"]);
		define("PACZ_THEME_VERSION", $theme_data['Version']);
		define("CLASSIADSPRO_THEME_OPTIONS_BUILD", $options["theme_name"] . '_options_build');
		define("PACZ_THEME_SLUG", $options["theme_slug"]);
		define("PACZ_THEME_STYLES_DYNAMIC", PACZ_THEME_DIR_URI . "/styles/dynamic");
		define("PACZ_THEME_STYLES", PACZ_THEME_DIR_URI . "/styles/css");
		define("PACZ_THEME_IMAGES", PACZ_THEME_DIR_URI . "/images");
		define("PACZ_THEME_JS", PACZ_THEME_DIR_URI . "/js");
		define("PACZ_THEME_INCLUDES", PACZ_THEME_DIR . "/includes");
		define("PACZ_THEME_FRAMEWORK", PACZ_THEME_INCLUDES . "/framework");
		define("PACZ_THEME_ACTIONS", PACZ_THEME_INCLUDES . "/actions");
		define("PACZ_THEME_PLUGINS_CONFIG", PACZ_THEME_INCLUDES . "/plugins-config");
		define("PACZ_THEME_PLUGINS_CONFIG_URI", PACZ_THEME_DIR_URI . "/includes/plugins-config");
		define('PACZ_THEME_METABOXES', PACZ_THEME_FRAMEWORK . '/metaboxes');
		define('PACZ_THEME_ADMIN_URI', PACZ_THEME_DIR_URI . '/includes');
		define('PACZ_THEME_ADMIN_ASSETS_URI', PACZ_THEME_DIR_URI . '/includes/assets');
		define('THEME_VERSION', $pacz_parent_theme[0]);
		define("PACZ_THEME_SETTINGS", 'classiads_settings');
			define("PACZ_THEME_DASHBOARD_STRING", 'Classiads Dashboard');
		define('PACZ_THEME_CONTROL_PANEL', PACZ_THEME_FRAMEWORK . '/pacz-panel');
		define('PACZ_THEME_CONTROL_PANEL_URI', PACZ_THEME_DIR_URI . '/includes/framework/pacz-panel');
	}

	function pacz_supports()
	{
		global $pacz_settings;
		$content_width = '';
		if (!isset($content_width)) {
			$content_width = $pacz_settings['grid-width'];
		}

		if (function_exists('add_theme_support')) {
			add_theme_support('automatic-feed-links');
			add_theme_support('editor-style');
			add_theme_support('title-tag');
			add_theme_support('custom-header');
			add_theme_support('custom-background');
			add_theme_support('wc-product-gallery-zoom');
			add_theme_support('wc-product-gallery-lightbox');
			add_theme_support('wc-product-gallery-slider');
			/* Add Woocmmerce support */
			add_theme_support('woocommerce');

			add_theme_support('post-formats', array(
				'image',
				'video',
				'quote',
				'link'
			));
			register_nav_menus(array(
				'primary-menu' => 'Primary Navigation',
				'second-menu' => 'Second Navigation',
				'third-menu' => 'Third Navigation',
				'fourth-menu' => 'Fourth Navigation',
				'fifth-menu' => 'Fifth Navigation',
				'sixth-menu' => 'Sixth Navigation',
				'seventh-menu' => 'Seventh Navigation',
			));

			add_theme_support('post-thumbnails');
		}
	}

	function pacz_functions()
	{

		require_once PACZ_THEME_FRAMEWORK . "/general.php";
		if (class_exists('Classiadspro_Core')) {
			require_once PACZ_THEME_FRAMEWORK . "/options-config.php";
		}
		require_once PACZ_THEME_FRAMEWORK . "/woocommerce.php";
		require_once PACZ_THEME_PLUGINS_CONFIG . "/ajax-search.php";
		require_once PACZ_THEME_PLUGINS_CONFIG . "/wp-nav-custom-walker.php";
		require_once PACZ_THEME_FRAMEWORK . '/sidebar-generator.php';
		require_once PACZ_THEME_PLUGINS_CONFIG . "/pagination.php";
		require_once PACZ_THEME_PLUGINS_CONFIG . "/image-cropping.php";
		require_once PACZ_THEME_PLUGINS_CONFIG . "/tgm-plugin-activation/request-plugins.php";


		require_once PACZ_THEME_PLUGINS_CONFIG . "/love-this.php";
		require_once PACZ_THEME_INCLUDES . "/thirdparty-integration/wpml-fix/pacz-wpml.php";
		if (class_exists('DirectoryPress')) {
			require_once PACZ_THEME_DIR . "/directorypress/functions.php";
		}
		/*
				Theme elements hooks
				*/
		require_once(trailingslashit(get_template_directory()) . "includes/actions/header.php");
		require_once(trailingslashit(get_template_directory()) . "includes/actions/posts.php");
		require_once(trailingslashit(get_template_directory()) . "includes/actions/general.php");

		/* Blog Styles @since V1.0 */
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/classic.php");

		/* Blog Styles @since V1.0 */
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/thumb.php");
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/tile.php");
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/tile-elegant.php");
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/tile-modern.php");
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/scroller.php");
		require_once(trailingslashit(get_template_directory()) . "includes/custom-post/blog-styles/masonry.php");
	}


	function pacz_add_metaboxes()
	{
		require_once PACZ_THEME_FRAMEWORK . '/metabox-generator.php';
		require_once PACZ_THEME_METABOXES . '/metabox-layout.php';
		require_once PACZ_THEME_METABOXES . '/metabox-posts.php';
		require_once PACZ_THEME_METABOXES . '/metabox-employee.php';
		require_once PACZ_THEME_METABOXES . '/metabox-pages.php';
		require_once PACZ_THEME_METABOXES . '/metabox-clients.php';
		require_once PACZ_THEME_METABOXES . '/metabox-testimonials.php';
		include_once PACZ_THEME_METABOXES . '/metabox-skinning.php';
	}

	function pacz_admin()
	{
		if (is_admin()) {

			require_once PACZ_THEME_FRAMEWORK . '/admin.php';
			require_once PACZ_THEME_PLUGINS_CONFIG . '/mega-menu.php';
			require_once PACZ_THEME_CONTROL_PANEL . "/pacz-admin.php";
			require_once PACZ_THEME_FRAMEWORK . '/pacz-panel/index.php';
		}
	}
}

/**
 * Preconnect для Google Fonts - ускоряет загрузку шрифтов
 */
function pacz_google_fonts_preconnect() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}

function pacz_theme_enqueue_scripts()
{
	if (!is_admin()) {

		// Preconnect для Google Fonts - ускоряет загрузку шрифтов
		add_action('wp_head', 'pacz_google_fonts_preconnect', 1);

		global $pacz_settings;
		$theme_data = wp_get_theme("classiadspro");

		wp_enqueue_script('jquery-ui-tabs');
		wp_register_script('jquery-jplayer', PACZ_THEME_JS . '/jquery.jplayer.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		wp_register_script('instafeed', PACZ_THEME_JS . '/instafeed.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		if (! wp_script_is('bootstrap', 'enqueued')) {
			wp_enqueue_script('bootstrap', PACZ_THEME_JS . '/bootstrap.min.js', array(
				'jquery'
			), $theme_data['Version'], true);
		}
		wp_enqueue_script('masonry', PACZ_THEME_JS . '/masonry.pkgd.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		//if ( ! wp_script_is( 'select2', 'enqueued' ) ) {
		wp_enqueue_script('select2', PACZ_THEME_JS . '/select2.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		//}
		wp_enqueue_script('slick-js', PACZ_THEME_JS . '/slick.min.js', array(
			'jquery'
		), $theme_data['Version'], true);

		wp_enqueue_script('pacz-theme-plugins', PACZ_THEME_JS . '/plugins.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		wp_enqueue_script('pacz-theme-scripts', PACZ_THEME_JS . '/theme-scripts.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		wp_enqueue_script('pacz-slick-triger', PACZ_THEME_JS . '/triger.min.js', array(
			'jquery'
		), $theme_data['Version'], true);
		$custom_js_file = get_stylesheet_directory() . '/custom.js';
		$custom_js_file_uri = get_stylesheet_directory_uri() . '/custom.js';

		if (file_exists($custom_js_file)) {
			wp_enqueue_script('pacz-custom-js', $custom_js_file_uri, array(
				'jquery',
				'imagesloaded',
				'masonry'
			), $theme_data['Version'], true);
		}

		if (is_singular()) {
			wp_enqueue_script('comment-reply');
		}
		global $pacz_settings, $pacz_accent_color, $post, $classiadspro_json, $level_num, $uID;
		$post_id = global_get_post_id();
		$pacz_header_trans_offset = (!empty(get_post_meta($post_id, '_trans_header_offset', true))) ? get_post_meta($post_id, '_trans_header_offset', true) : $pacz_settings['sticky_header_offset'];
		$rtl = (is_rtl()) ? 'true' : 'false';
		wp_localize_script(
			'pacz-theme-scripts',
			'pacz_js',
			array(
				'pacz_images_dir' => PACZ_THEME_IMAGES,
				'pacz_theme_js_path' => PACZ_THEME_JS,
				'pacz_header_toolbar' => (get_post_meta($post_id, '_header_toolbar', true) == 'true') ?  get_post_meta($post_id, '_header_toolbar', true) : $pacz_settings['header-toolbar'],
				'pacz_nav_res_width' => (isset($pacz_settings['res-nav-width'])) ? $pacz_settings['res-nav-width'] : '',
				'pacz_header_sticky' => (get_post_meta($post_id, '_custom_bg', true) == 'true') ? get_post_meta($post_id, 'sticky-header', true) : $pacz_settings['sticky-header'],
				'pacz_grid_width' => esc_attr($pacz_settings['grid-width']),
				//'pacz_preloader_logo' => esc_url($pacz_settings['preloader-logo']['url']),
				'pacz_header_padding' => esc_attr($pacz_settings['header-padding']),
				'pacz_accent_color' => esc_attr($pacz_accent_color),
				'pacz_squeeze_header' => esc_attr($pacz_settings['squeeze-sticky-header']),
				//'pacz_logo_height' => ($pacz_settings['logo']['height']) ? $pacz_settings['logo']['height'] : 50,
				//'pacz_preloader_txt_color' => ($pacz_settings['preloader-txt-color']) ? $pacz_settings['preloader-txt-color'] : '#fff',
				//'pacz_preloader_bg_color' => ($pacz_settings['preloader-bg-color']) ? $pacz_settings['preloader-bg-color'] : '#272e43',
				//'pacz_preloader_bar_color' => (isset($pacz_settings['preloader-bar-color']) && !empty($pacz_settings['preloader-bar-color'])) ? $pacz_settings['preloader-bar-color'] : $pacz_accent_color,
				'pacz_no_more_posts' => esc_html__('No More Posts', 'classiadspro'),
				'pacz_header_structure' => (get_post_meta($post_id, '_custom_bg', true) == 'true') ? get_post_meta($post_id, 'header-structure', true) : $pacz_settings['header-structure'],
				'pacz_boxed_header' => $pacz_settings['boxed-header'],
				'pacz_header_trans_offset' => $pacz_header_trans_offset,
				'pacz_is_rtl' => $rtl
			)
		);

		if (! wp_style_is('bootstrap', 'enqueued')) {
			wp_enqueue_style('bootstrap', PACZ_THEME_STYLES . '/bootstrap.min.css', false, $theme_data['Version'], 'all');
		}
		if (! wp_style_is('slick', 'enqueued')) {
			wp_enqueue_style('slick-css', PACZ_THEME_STYLES . '/slick/slick.css', false, $theme_data['Version'], 'all');
			wp_enqueue_style('slick-theme', PACZ_THEME_STYLES . '/slick/slick-theme.css', false, $theme_data['Version'], 'all');
		}

		//wp_enqueue_style('pacz-styles-default', PACZ_THEME_STYLES . '/styles.css', false, $theme_data['Version'], 'all');
		wp_register_style('material-icons', PACZ_THEME_DIR_URI . '/styles/material-icons/material-icons.min.css');
		wp_enqueue_style('material-icons');
		wp_enqueue_style('select2', PACZ_THEME_STYLES . '/select2.css', false, $theme_data['Version'], 'all');
		wp_enqueue_style('pacz-styles', PACZ_THEME_STYLES . '/pacz-styles.css', false, $theme_data['Version'], 'all');
		//wp_enqueue_style('pacz-blog', PACZ_THEME_STYLES . '/pacz-blog.css', false, $theme_data['Version'], 'all');
		wp_enqueue_style('pacz-post', PACZ_THEME_STYLES . '/post.css', false, $theme_data['Version'], 'all');
		wp_enqueue_style('pacz-font-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap', array(), null, 'all');

		if (!class_exists('Pacz_Static_Files')) {
			$font_family = $pacz_settings['body-font']['font-family'];
			wp_enqueue_style($font_family, 'https://fonts.googleapis.com/css?family=' . $font_family . ':100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic,100,200,300,400,500,600,700,800,900&display=swap', false, false, 'all');
			wp_enqueue_style('pacz-dynamic-css', PACZ_THEME_STYLES . '/classiadspro-dynamic.css', false, $theme_data['Version'], 'all');
			wp_add_inline_style('pacz-dynamic-css', pacz_enqueue_font_icons());
		}

		wp_enqueue_style('pacz-common-shortcode', PACZ_THEME_STYLES . '/shortcode/common-shortcode.css', false, $theme_data['Version'], 'all');
		wp_enqueue_style('pacz-fonticon-custom', PACZ_THEME_STYLES . '/fonticon-custom.min.css', false, $theme_data['Version'], 'all');


		do_action('directorypress_register_listing_styles');
	}
}
add_action('wp_enqueue_scripts', 'pacz_dynamic_css_injection');
add_action('wp_enqueue_scripts', 'pacz_theme_enqueue_scripts', 1);

/**
 * jQuery 3.x compatibility fix for deprecated .load() event handler
 * Fixes "e.indexOf is not a function" error in directorypress-public.js
 */
add_action('wp_enqueue_scripts', 'pacz_jquery_load_fix', 0);
function pacz_jquery_load_fix() {
    $jquery_fix = "
(function() {
    var originalLoad = jQuery.fn.load;
    jQuery.fn.load = function() {
        if (typeof arguments[0] === 'function') {
            // Called as .load(handler) - redirect to .on('load', handler)
            return this.on('load', arguments[0]);
        }
        // Called as AJAX .load(url, ...) - use original
        return originalLoad.apply(this, arguments);
    };
})();
";
    wp_register_script('pacz-jquery-load-fix', '', array('jquery'), false, false);
    wp_enqueue_script('pacz-jquery-load-fix');
    wp_add_inline_script('pacz-jquery-load-fix', $jquery_fix, 'before');
}

/**
 * wpmail_content_type
 * allow html emails
 *
 * @author Joe Sexton <joe@webtipblog.com>
 * @return string
 */
function wpmail_content_type()
{

	return 'text/html';
}

/* header script */

add_action('wp_enqueue_scripts', 'pacz_header_scripts', 1);
function pacz_header_scripts()
{
	echo '<script>
		var classiadspro = {};
		var php = {};
	 </script>';
}

/* footer scripts */
add_action('wp_footer', 'pacz_footer_elements', 1);
function pacz_footer_elements()
{
	global $pacz_settings, $pacz_accent_color, $post, $classiadspro_json, $classiadspro_dynamic_styles;
	$post_id = global_get_post_id();
	if ($post_id) {
		$preloader = get_post_meta($post_id, '_preloader', true);
		if ($preloader == 'true') {
			echo '<div class="pacz-preloader"></div>';
		}
	}

	$classiadspro_custom_js = isset($pacz_settings['custom-js']) ? (string) $pacz_settings['custom-js'] : '';
	if ($classiadspro_custom_js !== '') {
		$classiadspro_custom_js = str_replace(
			"$(this).text($(this).attr('title').replace('+');",
			"$(this).text($(this).attr('title').replace('+', ''));",
			$classiadspro_custom_js
		);
		$classiadspro_custom_js = str_replace(
			"$(this).css('background','none!important');",
			"this.style.setProperty('background', 'none', 'important');",
			$classiadspro_custom_js
		);
	}
?>
	<?php if ($classiadspro_custom_js) : ?>
		<script>
			<?php echo esc_js($classiadspro_custom_js); ?>
		</script>
	<?php endif; ?>

	<?php
	$classiadspro_dynamic_styles_ids = array();
	$classiadspro_dynamic_styles_inject = '';
	if (!empty($classiadspro_dynamic_styles)) {
		$classiadspro_styles_length = count($classiadspro_dynamic_styles);
	} else {
		$classiadspro_styles_length = 0;
	}
	if ($classiadspro_styles_length > 0) {
		foreach ($classiadspro_dynamic_styles as $key => $val) {
			$classiadspro_dynamic_styles_ids[] = $val["id"];
			$classiadspro_dynamic_styles_inject .= $val["inject"];
		};
	}

	?>
	<script>
		window.$ = jQuery
		var dynamic_styles = '<?php echo pacz_clean_init_styles($classiadspro_dynamic_styles_inject); ?>';
		var dynamic_styles_ids = (<?php echo json_encode($classiadspro_dynamic_styles_ids); ?> != null) ? <?php echo json_encode($classiadspro_dynamic_styles_ids); ?> : [];

		var styleTag = document.createElement('style'),
			head = document.getElementsByTagName('head')[0];

		styleTag.type = 'text/css';
		styleTag.setAttribute('data-ajax', '');
		styleTag.innerHTML = dynamic_styles;
		head.appendChild(styleTag);


		$('.pacz-dynamic-styles').each(function() {
			$(this).remove();
		});

		function ajaxStylesInjector() {
			$('.pacz-dynamic-styles').each(function() {
				var $this = $(this),
					id = $this.attr('id'),
					commentedStyles = $this.html();
				styles = commentedStyles
					.replace('<!--', '')
					.replace('-->', '');

				if (dynamic_styles_ids.indexOf(id) === -1) {
					$('style[data-ajax]').append(styles);
					$this.remove();
				}

				dynamic_styles_ids.push(id);
			});
		};
	</script>

<?php }
add_action('after_setup_theme', 'pacz_add_image_size');
function pacz_add_image_size($name = '', $width = '', $height = '', $crop = false)
{
	add_theme_support($name);
	add_image_size($name, $width, $height, $crop);
}

// Looking to send emails in production? Check out our Email API/SMTP product!
function mailtrap($phpmailer) {
	// Check if we're on localhost or running from CLI (cron)
	$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	$is_localhost = in_array($http_host, ['localhost', '127.0.0.1', 'localhost:8000', 'localhost:8080']) 
		|| strpos($http_host, '.local') !== false
		|| strpos($http_host, 'localhost') !== false
		|| (php_sapi_name() === 'cli' && getenv('DOCKER_ENV')); // CLI mode in Docker

	// if (!$is_localhost) {
	// 	return;
	// }

	$phpmailer->isSMTP();
	$phpmailer->Host = 'sandbox.smtp.mailtrap.io';
	$phpmailer->SMTPAuth = true;
	$phpmailer->Port = 2525;
	$phpmailer->Username = '221f66dfa5dc86';
	$phpmailer->Password = '03a18f3433fc8a';
}

// add_action('phpmailer_init', 'mailtrap'); 

/**
 * Handle avatar upload during user registration
 * Hooks into wpfb_form_register_new_user_success action from form-builder-wp plugin
 * 
 * @param int $user_id The newly created user ID
 * @param array $data Form submission data
 */
function classiadspro_handle_registration_avatar($user_id, $data)
{
	// Debug: log all data
	error_log('Registration avatar handler called for user: ' . $user_id);
	error_log('Form data: ' . print_r($data, true));
	error_log('FILES data: ' . print_r($_FILES, true));

	// Check if avatar field exists in form data (processed by Form Builder WP)
	if (empty($data['avatar']) || !is_array($data['avatar'])) {
		error_log('Avatar field not found in form data or not array');
		return;
	}

	$avatar_data = $data['avatar'];
	error_log('Avatar data found: ' . print_r($avatar_data, true));

	// Check if we have file info
	if (empty($avatar_data['file_url'])) {
		error_log('Avatar file_url missing');
		return;
	}

	// Convert file URL to local file path
	$upload_base_url = wp_get_upload_dir()['baseurl'];
	$upload_base_dir = wp_get_upload_dir()['basedir'];

	// Remove base URL and construct file path
	$file_relative_path = str_replace($upload_base_url, '', $avatar_data['file_url']);
	$file_path = $upload_base_dir . $file_relative_path;

	error_log('Avatar file_url: ' . $avatar_data['file_url']);
	error_log('Avatar file_path constructed: ' . $file_path);

	// Check if file exists
	if (!file_exists($file_path)) {
		error_log('Avatar file does not exist: ' . $file_path);
		// Try alternate path construction
		$alt_file_path = $upload_base_dir . '/' . ltrim($file_relative_path, '/');
		if (file_exists($alt_file_path)) {
			$file_path = $alt_file_path;
			error_log('Avatar found at alternate path: ' . $file_path);
		} else {
			return;
		}
	}

	error_log('Avatar file found at: ' . $file_path);

	// Get file info
	$file_info = wp_check_filetype($file_path);
	$file_type = $file_info['type'];

	// Validate file type (only images)
	$allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp');

	if (!in_array($file_type, $allowed_types)) {
		error_log('Avatar: invalid file type - ' . $file_type);
		return;
	}

	// Validate file size (max 5MB)
	$file_size = filesize($file_path);
	$max_size = 5 * 1024 * 1024; // 5MB in bytes
	if ($file_size > $max_size) {
		error_log('Avatar: file too large - ' . $file_size);
		return;
	}

	error_log('Avatar file validation passed. Type: ' . $file_type . ', Size: ' . $file_size);

	// Load WordPress file handling functions
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');

	// Prepare attachment data
	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title' => sprintf('Avatar for user %d', $user_id),
		'post_content' => '',
		'post_status' => 'inherit',
		'post_author' => $user_id,
	);

	// Insert attachment into media library
	$attach_id = wp_insert_attachment($attachment, $file_path);

	if (is_wp_error($attach_id)) {
		error_log('Avatar attachment insert error: ' . $attach_id->get_error_message());
		return;
	}

	// Generate attachment metadata (thumbnails, etc)
	$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
	wp_update_attachment_metadata($attach_id, $attach_data);

	// Get the attachment URL
	$file_url = wp_get_attachment_url($attach_id);

	// Save attachment ID to user meta for our custom avatar
	update_user_meta($user_id, 'avatar_id', $attach_id);
	update_user_meta($user_id, 'user_avatar_url', $file_url);

	// For compatibility with popular avatar plugins
	update_user_meta($user_id, 'wp_user_avatar', $attach_id);
	update_user_meta($user_id, 'simple_local_avatar', $attach_id);

	error_log('Avatar uploaded successfully for user ' . $user_id . ': attachment ID ' . $attach_id . ', URL: ' . $file_url);
}
add_action('wpfb_form_register_new_user_success', 'classiadspro_handle_registration_avatar', 10, 2);

/**
 * Handle document upload during user registration
 * Hooks into wpfb_form_register_new_user_success action from form-builder-wp plugin
 * 
 * @param int $user_id The newly created user ID
 * @param array $data Form submission data
 */
function classiadspro_handle_registration_document($user_id, $data)
{
	// Debug: log document data
	error_log('Registration document handler called for user: ' . $user_id);
	error_log('Form data: ' . print_r($data, true));

	// Check if document field exists in form data (processed by Form Builder WP)
	if (empty($data['document']) || !is_array($data['document'])) {
		error_log('Document field not found in form data or not array');
		return;
	}

	$document_data = $data['document'];
	error_log('Document data found: ' . print_r($document_data, true));

	// Check if we have file info
	if (empty($document_data['file_url'])) {
		error_log('Document file_url missing');
		return;
	}

	// Convert file URL to local file path
	$upload_base_url = wp_get_upload_dir()['baseurl'];
	$upload_base_dir = wp_get_upload_dir()['basedir'];

	// Remove base URL and construct file path
	$file_relative_path = str_replace($upload_base_url, '', $document_data['file_url']);
	$file_path = $upload_base_dir . $file_relative_path;

	error_log('Document file_url: ' . $document_data['file_url']);
	error_log('Document file_path constructed: ' . $file_path);

	// Check if file exists
	if (!file_exists($file_path)) {
		error_log('Document file does not exist: ' . $file_path);
		// Try alternate path construction
		$alt_file_path = $upload_base_dir . '/' . ltrim($file_relative_path, '/');
		if (file_exists($alt_file_path)) {
			$file_path = $alt_file_path;
			error_log('Document found at alternate path: ' . $file_path);
		} else {
			return;
		}
	}

	error_log('Document file found at: ' . $file_path);

	// Get file info
	$file_info = wp_check_filetype($file_path);
	$file_type = $file_info['type'];

	// Validate file type (documents)
	$allowed_types = array(
		'application/pdf',
		'application/msword',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'text/plain',
		'image/jpeg',
		'image/jpg',
		'image/png',
		'application/vnd.ms-excel',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
	);

	if (!in_array($file_type, $allowed_types)) {
		error_log('Document: invalid file type - ' . $file_type);
		return;
	}

	// Validate file size (max 10MB)
	$file_size = filesize($file_path);
	$max_size = 10 * 1024 * 1024; // 10MB in bytes
	if ($file_size > $max_size) {
		error_log('Document: file too large - ' . $file_size);
		return;
	}

	error_log('Document file validation passed. Type: ' . $file_type . ', Size: ' . $file_size);

	// Load WordPress file handling functions
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');

	// Prepare attachment data
	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title' => sprintf('Registration document for user %d', $user_id),
		'post_content' => '',
		'post_status' => 'inherit',
		'post_author' => $user_id,
	);

	// Insert attachment into media library
	$attach_id = wp_insert_attachment($attachment, $file_path);

	if (is_wp_error($attach_id)) {
		error_log('Document attachment insert error: ' . $attach_id->get_error_message());
		return;
	}

	// Generate attachment metadata
	$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
	wp_update_attachment_metadata($attach_id, $attach_data);

	// Get the attachment URL
	$file_url = wp_get_attachment_url($attach_id);

	// Save attachment ID to user meta
	update_user_meta($user_id, 'registration_document_id', $attach_id);
	update_user_meta($user_id, 'registration_document_url', $file_url);

	// Set user as not verified by default
	update_user_meta($user_id, 'user_verified', '0');

	error_log('Document uploaded successfully for user ' . $user_id . ': attachment ID ' . $attach_id . ', URL: ' . $file_url);
}
add_action('wpfb_form_register_new_user_success', 'classiadspro_handle_registration_document', 10, 2);

/**
 * Auto-verify new users if the admin setting is enabled
 * Hooks into wpfb_form_register_new_user_success at priority 12
 * Runs after document upload but before welcome email
 * 
 * @param int $user_id The newly created user ID
 * @param array $data Form submission data
 */
function classiadspro_auto_verify_user($user_id, $data)
{
	global $pacz_settings;
	if (empty($pacz_settings)) {
		$pacz_settings = get_option('pacz_settings');
	}

	$auto_verify = isset($pacz_settings['auto_verify_users']) ? $pacz_settings['auto_verify_users'] : false;

	if ($auto_verify) {
		update_user_meta($user_id, 'user_verified', '1');
		error_log('Auto-verified user ' . $user_id . ' (auto_verify_users is enabled)');
		classiadspro_send_verification_email($user_id);
	}
}
add_action('wpfb_form_register_new_user_success', 'classiadspro_auto_verify_user', 12, 2);

/**
 * Send welcome email to newly registered user
 * Hooks into wpfb_form_register_new_user_success action
 * Sends welcome email using template from theme settings
 * 
 * @param int $user_id The newly created user ID
 * @param array $data Form submission data
 */
function classiadspro_send_welcome_email($user_id, $data)
{
	// Get user data
	$user = get_userdata($user_id);

	if (!$user) {
		error_log('Welcome email: User not found - ID ' . $user_id);
		return;
	}

	// Get site info
	$site_name = get_bloginfo('name');
	$site_url = get_home_url();
	$login_url = wp_login_url();
	$dashboard_url = trailingslashit($site_url) . 'my-dashboard/';

	// Get email settings from theme options
	global $pacz_settings;
	if (empty($pacz_settings)) {
		$pacz_settings = get_option('pacz_settings');
	}

	// Get email subject and message from settings, or use defaults
	$email_subject = isset($pacz_settings['registration_email_subject']) && !empty($pacz_settings['registration_email_subject']) 
		? $pacz_settings['registration_email_subject'] 
		: 'Welcome to {site_name}';

	$email_message = isset($pacz_settings['registration_email_message']) && !empty($pacz_settings['registration_email_message']) 
		? $pacz_settings['registration_email_message'] 
		: '';

	// If no custom message in settings, use default template
	if (empty($email_message)) {
		$email_message = '<html><body>
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f5f5f5;">
<tr><td style="padding: 40px 0;">
<table cellpadding="0" cellspacing="0" border="0" width="600" align="center" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
<tr style="background-color: #191a1f;">
<td style="padding: 30px 30px; text-align: center; border-radius: 8px 8px 0 0;">
<h1 style="color: #ffffff; margin: 0; font-size: 24px;">Welcome, {user_name}!</h1>
</td>
</tr>
<tr>
<td style="padding: 40px 30px;">
<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">
Thank you for registering with us! Your account has been successfully created.
</p>
<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">
Here is your account information:
</p>
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin: 20px 0; border: 1px solid #eeeeee; border-radius: 4px;">
<tr style="background-color: #f9f9f9;">
<td style="padding: 15px; font-weight: bold; color: #191a1f; width: 40%;">Username:</td>
<td style="padding: 15px; color: #666666;">{user_login}</td>
</tr>
<tr>
<td style="padding: 15px; font-weight: bold; color: #191a1f; background-color: #f9f9f9;">Email:</td>
<td style="padding: 15px; color: #666666; background-color: #f9f9f9;">{user_email}</td>
</tr>
</table>
<div style="margin: 25px 0; padding: 20px; background-color: #fff8e6; border-left: 4px solid #EB6653; border-radius: 4px;">
<p style="margin: 0; font-size: 13px; font-weight: bold; color: #EB6653;">⚠️ ACCOUNT VERIFICATION REQUIRED</p>
<p style="margin: 10px 0 0 0; font-size: 13px; line-height: 1.6; color: #333333;">
Your account is currently under verification. You will receive a confirmation email once your account is verified.
</p>
</div>
<div style="margin: 25px 0; padding: 20px; background-color: #e8f4f8; border-left: 4px solid #2081cc; border-radius: 4px;">
<p style="margin: 0; font-size: 13px; font-weight: bold; color: #2081cc;">📋 POSTING LISTINGS</p>
<p style="margin: 10px 0 0 0; font-size: 13px; line-height: 1.6; color: #333333;">
After your account is verified, you will be able to post listings and manage your ads from your dashboard. Please note that all listings must comply with our community guidelines.
</p>
</div>
<p style="margin: 30px 0; text-align: center;">
<a href="{dashboard_url}" style="display: inline-block; padding: 12px 30px; background-color: #EB6653; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Your Dashboard</a>
</p>
<p style="margin: 30px 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">
If you have any questions or need assistance, please don\'t hesitate to contact us.
</p>
<p style="margin: 0; font-size: 13px; color: #999999; border-top: 1px solid #eeeeee; padding-top: 20px;">
Best regards,<br>
The {site_name} Team<br>
<a href="{site_url}" style="color: #EB6653; text-decoration: none;">{site_url}</a>
</p>
</td>
</tr>
</table>
</td></tr>
</table>
</body></html>';
	}

	// Replace variables in subject
	$subject = str_replace(
		array('{site_name}', '{user_name}', '{user_login}'),
		array($site_name, $user->display_name, $user->user_login),
		$email_subject
	);

	// Replace variables in message
	$message = str_replace(
		array('{user_name}', '{user_login}', '{user_email}', '{site_name}', '{site_url}', '{dashboard_url}', '{login_url}'),
		array(esc_html($user->display_name), esc_html($user->user_login), esc_html($user->user_email), esc_html($site_name), esc_url($site_url), esc_url($dashboard_url), esc_url($login_url)),
		$email_message
	);

	// If user is already verified (auto-verify enabled), replace verification warning with verified notice
	if (get_user_meta($user_id, 'user_verified', true) === '1') {
		$message = str_replace(
			'<div style="margin: 25px 0; padding: 20px; background-color: #fff8e6; border-left: 4px solid #EB6653; border-radius: 4px;">'
			. '<p style="margin: 0; font-size: 13px; font-weight: bold; color: #EB6653;">⚠️ ACCOUNT VERIFICATION REQUIRED</p>'
			. '<p style="margin: 10px 0 0 0; font-size: 13px; line-height: 1.6; color: #333333;">'
			. 'Your account is currently under verification. You will receive a confirmation email once your account is verified.'
			. '</p>'
			. '</div>',
			'<div style="margin: 25px 0; padding: 20px; background-color: #e8f5e9; border-left: 4px solid #28a745; border-radius: 4px;">'
			. '<p style="margin: 0; font-size: 13px; font-weight: bold; color: #28a745;">✓ ACCOUNT VERIFIED</p>'
			. '<p style="margin: 10px 0 0 0; font-size: 13px; line-height: 1.6; color: #333333;">'
			. 'Your account has been automatically verified. You can start posting listings right away!'
			. '</p>'
			. '</div>',
			$message
		);

		$message = str_replace(
			'After your account is verified, you will be able to post listings and manage your ads from your dashboard. Please note that all listings must comply with our community guidelines.',
			'You can now post listings and manage your ads from your dashboard. Please note that all listings must comply with our community guidelines.',
			$message
		);
	}

	// Prepare email
	$to = $user->user_email;

	// Set email headers
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_option('siteurl') . ' <' . get_option('admin_email') . '>',
	);

	// Send email
	$sent = wp_mail($to, $subject, $message, $headers);

	if ($sent) {
		error_log('Welcome email sent to ' . $user->user_email . ' for user ID ' . $user_id);
	} else {
		error_log('Failed to send welcome email to ' . $user->user_email . ' for user ID ' . $user_id);
	}
}
add_action('wpfb_form_register_new_user_success', 'classiadspro_send_welcome_email', 15, 2);

/**
 * Filter to use custom avatar instead of Gravatar
 * Replaces default WordPress avatar with user uploaded photo
 * Works in admin panel and frontend
 * 
 * @param string $avatar The default avatar HTML
 * @param mixed $id_or_email User ID, email, or user object
 * @param int $size Avatar size in pixels
 * @param string $default Default avatar URL
 * @param string $alt Alt text for avatar
 * @return string Modified avatar HTML
 */
function classiadspro_custom_avatar($avatar, $id_or_email, $size, $default, $alt)
{
	$user_id = 0;

	// Get user ID from different input types
	if (is_numeric($id_or_email)) {
		$user_id = (int) $id_or_email;
	} elseif (is_string($id_or_email)) {
		$user = get_user_by('email', $id_or_email);
		if ($user) {
			$user_id = $user->ID;
		}
	} elseif (is_object($id_or_email)) {
		if (isset($id_or_email->user_id)) {
			$user_id = (int) $id_or_email->user_id;
		} elseif (isset($id_or_email->ID)) {
			$user_id = (int) $id_or_email->ID;
		}
	}

	if (!$user_id) {
		return $avatar;
	}

	// Get custom avatar attachment ID
	$custom_avatar_id = get_user_meta($user_id, 'avatar_id', true);

	if (!$custom_avatar_id) {
		return $avatar;
	}

	// Get avatar image URL
	$custom_avatar_url = wp_get_attachment_image_url($custom_avatar_id, array($size, $size));

	if (!$custom_avatar_url) {
		return $avatar;
	}

	// Build custom avatar HTML
	$avatar = sprintf(
		'<img alt="%s" src="%s" class="avatar avatar-%d photo" height="%d" width="%d" loading="lazy" decoding="async" />',
		esc_attr($alt),
		esc_url($custom_avatar_url),
		(int) $size,
		(int) $size,
		(int) $size
	);

	return $avatar;
}
add_filter('get_avatar', 'classiadspro_custom_avatar', 10, 5);

/**
 * Add custom avatar field to user profile in admin
 * Allows admins to view and change user avatar
 * 
 * @param WP_User $user Current user object
 */
function classiadspro_admin_avatar_field($user)
{
	$avatar_id = get_user_meta($user->ID, 'avatar_id', true);
	$avatar_url = get_user_meta($user->ID, 'user_avatar_url', true);
?>
	<h3><?php _e('Profile Photo', 'classiadspro'); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="custom_avatar"><?php _e('Current Avatar', 'classiadspro'); ?></label></th>
			<td>
				<?php if ($avatar_id): ?>
					<?php echo wp_get_attachment_image($avatar_id, array(150, 150), false, array('style' => 'border-radius: 50%;')); ?>
					<p>
						<button type="button" class="button" id="remove_avatar_button"><?php _e('Remove Avatar', 'classiadspro'); ?></button>
					</p>
					<input type="hidden" name="remove_avatar" id="remove_avatar" value="0" />
				<?php else: ?>
					<p><?php _e('No custom avatar uploaded yet.', 'classiadspro'); ?></p>
				<?php endif; ?>

				<p class="description"><?php _e('Avatar uploaded during registration. To change it, user needs to upload a new one through the registration form.', 'classiadspro'); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="new_avatar_upload"><?php _e('Upload New Avatar', 'classiadspro'); ?></label></th>
			<td>
				<input type="file" name="new_avatar" id="new_avatar_upload" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" />
				<p class="description"><?php _e('Upload new avatar (JPG, PNG, GIF, WEBP - max 5MB)', 'classiadspro'); ?></p>
			</td>
		</tr>
	</table>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#remove_avatar_button').on('click', function() {
				if (confirm('<?php _e('Are you sure you want to remove the avatar?', 'classiadspro'); ?>')) {
					$('#remove_avatar').val('1');
					$(this).closest('tr').find('img').fadeOut();
					$(this).parent().html('<p><?php _e('Avatar will be removed on save.', 'classiadspro'); ?></p>');
				}
			});
		});
	</script>
<?php
}
// add_action('show_user_profile', 'classiadspro_admin_avatar_field');
// add_action('edit_user_profile', 'classiadspro_admin_avatar_field');

/**
 * Save custom avatar from admin profile
 * Handles avatar upload and removal from user profile page
 * 
 * @param int $user_id User ID
 */
function classiadspro_admin_save_avatar_field($user_id)
{
	if (!current_user_can('edit_user', $user_id)) {
		return false;
	}

	// Handle avatar removal
	if (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] == '1') {
		$old_avatar_id = get_user_meta($user_id, 'avatar_id', true);
		if ($old_avatar_id) {
			wp_delete_attachment($old_avatar_id, true);
		}
		delete_user_meta($user_id, 'avatar_id');
		delete_user_meta($user_id, 'user_avatar_url');
		delete_user_meta($user_id, 'wp_user_avatar');
		delete_user_meta($user_id, 'simple_local_avatar');
		return;
	}

	// Handle new avatar upload
	if (empty($_FILES['new_avatar']) || empty($_FILES['new_avatar']['name'])) {
		return;
	}

	$avatar_file = $_FILES['new_avatar'];

	// Validate file upload
	if ($avatar_file['error'] !== UPLOAD_ERR_OK) {
		return;
	}

	// Verify this is a valid uploaded file
	if (!is_uploaded_file($avatar_file['tmp_name'])) {
		return;
	}

	// Validate file type
	$allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp');
	if (!in_array($avatar_file['type'], $allowed_types)) {
		return;
	}

	// Validate file size (max 5MB)
	$max_size = 5 * 1024 * 1024;
	if ($avatar_file['size'] > $max_size) {
		return;
	}

	// Load WordPress file handling functions
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');

	// Delete old avatar if exists
	$old_avatar_id = get_user_meta($user_id, 'avatar_id', true);
	if ($old_avatar_id) {
		wp_delete_attachment($old_avatar_id, true);
	}

	// Upload new avatar
	$upload_overrides = array(
		'test_form' => false,
		'test_type' => true,
	);

	$uploaded_file = wp_handle_upload($avatar_file, $upload_overrides);

	if (isset($uploaded_file['error'])) {
		return;
	}

	$file_path = $uploaded_file['file'];
	$file_url = $uploaded_file['url'];
	$file_type = $uploaded_file['type'];

	// Create attachment
	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title' => sprintf('Avatar for user %d', $user_id),
		'post_content' => '',
		'post_status' => 'inherit',
		'post_author' => $user_id,
	);

	$attach_id = wp_insert_attachment($attachment, $file_path);

	if (is_wp_error($attach_id)) {
		return;
	}

	// Generate metadata
	$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
	wp_update_attachment_metadata($attach_id, $attach_data);

	// Update user meta
	update_user_meta($user_id, 'avatar_id', $attach_id);
	update_user_meta($user_id, 'user_avatar_url', $file_url);
	update_user_meta($user_id, 'wp_user_avatar', $attach_id);
	update_user_meta($user_id, 'simple_local_avatar', $attach_id);
}
// add_action('personal_options_update', 'classiadspro_admin_save_avatar_field');
// add_action('edit_user_profile_update', 'classiadspro_admin_save_avatar_field');

/**
 * Add avatar column to users list in admin
 * Shows custom avatar in users table
 * 
 * @param array $columns Existing columns
 * @return array Modified columns
 */
function classiadspro_add_avatar_column($columns)
{
	$columns['avatar'] = __('Avatar', 'classiadspro');
	return $columns;
}
// add_filter('manage_users_columns', 'classiadspro_add_avatar_column');

/**
 * Display avatar in users list column
 * 
 * @param string $output Column output
 * @param string $column_name Column name
 * @param int $user_id User ID
 * @return string Column content
 */
function classiadspro_show_avatar_column($output, $column_name, $user_id)
{
	if ($column_name === 'avatar') {
		$avatar_id = get_user_meta($user_id, 'avatar_id', true);
		if ($avatar_id) {
			return wp_get_attachment_image($avatar_id, array(32, 32), false, array('style' => 'border-radius: 50%;'));
		} else {
			return get_avatar($user_id, 32);
		}
	}
	return $output;
}
// add_filter('manage_users_custom_column', 'classiadspro_show_avatar_column', 10, 3);

/**
 * Add verification and document fields to user profile in admin
 * Allows admins to view registration document and verify user
 * 
 * @param WP_User $user Current user object
 */
function classiadspro_admin_verification_fields($user)
{
	$document_id = get_user_meta($user->ID, 'registration_document_id', true);
	$avatar_id = get_user_meta($user->ID, 'avatar_id', true);
	$is_verified = get_user_meta($user->ID, 'user_verified', true);
?>
	<h3><?php _e('User Verification', 'classiadspro'); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="user_verified"><?php _e('Account Status', 'classiadspro'); ?></label></th>
			<td>
				<label>
					<input type="checkbox" name="user_verified" id="user_verified" value="1" <?php checked($is_verified, '1'); ?> />
					<span><?php _e('Verified - User can post listings', 'classiadspro'); ?></span>
				</label>
				<p class="description">
					<?php
					if ($is_verified) {
						_e('✓ User account is verified and can post listings', 'classiadspro');
					} else {
						_e('✗ User account is not verified. User cannot post listings until verified.', 'classiadspro');
					}
					?>
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e('User Avatar (Profile Photo)', 'classiadspro'); ?></label></th>
			<td>
				<?php if ($avatar_id): ?>
					<div style="margin-bottom: 20px; padding: 15px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
						<?php
						$avatar_url = wp_get_attachment_url($avatar_id);
						$avatar_title = get_the_title($avatar_id);
						$avatar_type = get_post_mime_type($avatar_id);
						?>

						<!-- Avatar Preview -->
						<div style="margin-bottom: 15px; text-align: center;">
							<a href="<?php echo esc_url($avatar_url); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-block; cursor: pointer;">
								<img src="<?php echo esc_url($avatar_url); ?>"
									alt="<?php echo esc_attr($avatar_title); ?>"
									style="max-width: 400px; max-height: 500px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: opacity 0.3s ease;"
									onmouseover="this.style.opacity='0.8';"
									onmouseout="this.style.opacity='1';" />
							</a>
						</div>

						<!-- Avatar Info -->
						<table style="width: 100%; margin: 15px 0 0 0;">
							<tr style="background-color: #f0f0f0;">
								<td style="padding: 8px; font-weight: bold; width: 30%;"><?php _e('File Name:', 'classiadspro'); ?></td>
								<td style="padding: 8px;"><?php echo esc_html($avatar_title); ?></td>
							</tr>
							<tr>
								<td style="padding: 8px; font-weight: bold; background-color: #f0f0f0;"><?php _e('File Type:', 'classiadspro'); ?></td>
								<td style="padding: 8px;"><?php echo esc_html($avatar_type); ?></td>
							</tr>
							<tr style="background-color: #f0f0f0;">
								<td style="padding: 8px; font-weight: bold;"><?php _e('Uploaded:', 'classiadspro'); ?></td>
								<td style="padding: 8px;">
									<?php
									$attachment = get_post($avatar_id);
									echo esc_html(date_i18n('F d, Y H:i', strtotime($attachment->post_date)));
									?>
								</td>
							</tr>
						</table>

						<!-- Actions -->
						<p style="margin: 15px 0 0 0;">
							<a href="<?php echo esc_url($avatar_url); ?>" target="_blank" class="button button-primary">
								<?php _e('View/Download Avatar', 'classiadspro'); ?>
							</a>
						</p>
					</div>
				<?php else: ?>
					<div style="padding: 20px; background-color: #fff8e6; border: 1px solid #ddd; border-left: 4px solid #ffc107; border-radius: 4px;">
						<p style="margin: 0; color: #856404;">
							⚠️ <?php _e('No avatar uploaded.', 'classiadspro'); ?>
						</p>
					</div>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label><?php _e('Registration Document (Passport Photo)', 'classiadspro'); ?></label></th>
			<td>
				<?php if ($document_id): ?>
					<div style="margin-bottom: 20px; padding: 15px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
						<?php
						$document_url = wp_get_attachment_url($document_id);
						$document_title = get_the_title($document_id);
						$document_type = get_post_mime_type($document_id);
						$is_image = strpos($document_type, 'image/') === 0;
						?>

						<!-- Document Preview -->
						<?php if ($is_image): ?>
							<div style="margin-bottom: 15px; text-align: center;">
								<a href="<?php echo esc_url($document_url); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-block; cursor: pointer;">
									<img src="<?php echo esc_url($document_url); ?>"
										alt="<?php echo esc_attr($document_title); ?>"
										style="max-width: 400px; max-height: 500px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: opacity 0.3s ease;"
										onmouseover="this.style.opacity='0.8';"
										onmouseout="this.style.opacity='1';" />
								</a>
							</div>
						<?php else: ?>
							<div style="margin-bottom: 15px; padding: 20px; background-color: #e8f4f8; border-radius: 4px; text-align: center;">
								<p style="margin: 0; font-size: 48px;">📄</p>
								<p style="margin: 5px 0 0 0; color: #666;"><?php _e('Document preview not available', 'classiadspro'); ?></p>
							</div>
						<?php endif; ?>

						<!-- Document Info -->
						<table style="width: 100%; margin: 15px 0 0 0;">
							<tr style="background-color: #f0f0f0;">
								<td style="padding: 8px; font-weight: bold; width: 30%;"><?php _e('File Name:', 'classiadspro'); ?></td>
								<td style="padding: 8px;"><?php echo esc_html($document_title); ?></td>
							</tr>
							<tr>
								<td style="padding: 8px; font-weight: bold; background-color: #f0f0f0;"><?php _e('File Type:', 'classiadspro'); ?></td>
								<td style="padding: 8px;"><?php echo esc_html($document_type); ?></td>
							</tr>
							<tr style="background-color: #f0f0f0;">
								<td style="padding: 8px; font-weight: bold;"><?php _e('Uploaded:', 'classiadspro'); ?></td>
								<td style="padding: 8px;">
									<?php
									$attachment = get_post($document_id);
									echo esc_html(date_i18n('F d, Y H:i', strtotime($attachment->post_date)));
									?>
								</td>
							</tr>
						</table>

						<!-- Actions -->
						<p style="margin: 15px 0 0 0;">
							<a href="<?php echo esc_url($document_url); ?>" target="_blank" class="button button-primary">
								<?php _e('View/Download Document', 'classiadspro'); ?>
							</a>
						</p>
					</div>
				<?php else: ?>
					<div style="padding: 20px; background-color: #fff8e6; border: 1px solid #ddd; border-left: 4px solid #ffc107; border-radius: 4px;">
						<p style="margin: 0; color: #856404;">
							⚠️ <?php _e('No registration document uploaded.', 'classiadspro'); ?>
						</p>
					</div>
				<?php endif; ?>
			</td>
		</tr>
	</table>
<?php
}
add_action('show_user_profile', 'classiadspro_admin_verification_fields');
add_action('edit_user_profile', 'classiadspro_admin_verification_fields');

/**
 * Save verification status from admin profile
 * Sends email to user when account is verified
 * 
 * @param int $user_id User ID
 */
function classiadspro_admin_save_verification_field($user_id)
{
	if (!current_user_can('edit_user', $user_id)) {
		return false;
	}

	// Get current verification status
	$old_verified = get_user_meta($user_id, 'user_verified', true);
	$new_verified = isset($_POST['user_verified']) ? '1' : '0';

	// Update verification status
	update_user_meta($user_id, 'user_verified', $new_verified);

	// If verification status changed to verified, send email
	if ($old_verified !== '1' && $new_verified === '1') {
		classiadspro_send_verification_email($user_id);
	}
}
add_action('personal_options_update', 'classiadspro_admin_save_verification_field');
add_action('edit_user_profile_update', 'classiadspro_admin_save_verification_field');

/**
 * Send account verification email to user
 * Notifies user that their account has been verified and they can post listings
 * 
 * @param int $user_id User ID
 */
function classiadspro_send_verification_email($user_id)
{
	$user = get_userdata($user_id);

	if (!$user) {
		error_log('Verification email: User not found - ID ' . $user_id);
		return;
	}

	// Get site info
	$site_name = get_bloginfo('name');
	$site_url = get_home_url();
	$dashboard_url = trailingslashit($site_url) . 'my-dashboard/';

	// Get email settings from theme options
	global $pacz_settings;
	if (empty($pacz_settings)) {
		$pacz_settings = get_option('pacz_settings');
	}

	// Get email subject and message from settings, or use defaults
	$email_subject = isset($pacz_settings['verification_email_subject']) && !empty($pacz_settings['verification_email_subject']) 
		? $pacz_settings['verification_email_subject'] 
		: 'Your Account Has Been Verified - {site_name}';

	$email_message = isset($pacz_settings['verification_email_message']) && !empty($pacz_settings['verification_email_message']) 
		? $pacz_settings['verification_email_message'] 
		: '';

	// If no custom message in settings, use default template
	if (empty($email_message)) {
		$email_message = '<html><body>
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f5f5f5;">
<tr><td style="padding: 40px 0;">
<table cellpadding="0" cellspacing="0" border="0" width="600" align="center" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
<tr style="background-color: #28a745;">
<td style="padding: 30px 30px; text-align: center; border-radius: 8px 8px 0 0;">
<h1 style="color: #ffffff; margin: 0; font-size: 24px;">✓ Account Verified!</h1>
</td>
</tr>
<tr>
<td style="padding: 40px 30px;">
<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">
Hello {user_name},
</p>
<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">
Great news! Your account has been verified by our team. You can now post listings and manage your ads on our platform.
</p>
<div style="margin: 25px 0; padding: 20px; background-color: #e8f5e9; border-left: 4px solid #28a745; border-radius: 4px;">
<p style="margin: 0; font-size: 13px; font-weight: bold; color: #28a745;">✓ VERIFICATION COMPLETE</p>
<p style="margin: 10px 0 0 0; font-size: 13px; line-height: 1.6; color: #333333;">
Your account is now fully active. You can start posting listings right away!
</p>
</div>
<div style="margin: 25px 0; padding: 20px; background-color: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
<p style="margin: 0; font-size: 13px; font-weight: bold; color: #1976d2;">📋 YOU CAN NOW:</p>
<ul style="margin: 10px 0 0 0; padding-left: 20px; font-size: 13px; line-height: 1.8; color: #333333;">
<li>Post new listings and manage your ads</li>
<li>Update your profile and account information</li>
<li>Interact with potential buyers/renters</li>
<li>Monitor listing views and inquiries</li>
</ul>
</div>
<p style="margin: 30px 0; text-align: center;">
<a href="{dashboard_url}" style="display: inline-block; padding: 12px 30px; background-color: #28a745; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Your Dashboard</a>
</p>
<p style="margin: 30px 0 20px 0; font-size: 14px; line-height: 1.6; color: #333333;">
If you have any questions or need assistance, please don\'t hesitate to contact us.
</p>
<p style="margin: 0; font-size: 13px; color: #999999; border-top: 1px solid #eeeeee; padding-top: 20px;">
Best regards,<br>
The {site_name} Team<br>
<a href="{site_url}" style="color: #28a745; text-decoration: none;">{site_url}</a>
</p>
</td>
</tr>
</table>
</td></tr>
</table>
</body></html>';
	}

	// Replace variables in subject
	$subject = str_replace(
		array('{site_name}', '{user_name}'),
		array($site_name, $user->display_name),
		$email_subject
	);

	// Replace variables in message
	$message = str_replace(
		array('{user_name}', '{site_name}', '{site_url}', '{dashboard_url}'),
		array(esc_html($user->display_name), esc_html($site_name), esc_url($site_url), esc_url($dashboard_url)),
		$email_message
	);

	// Prepare email
	$to = $user->user_email;

	// Set email headers
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_option('siteurl') . ' <' . get_option('admin_email') . '>',
	);

	// Send email
	$sent = wp_mail($to, $subject, $message, $headers);

	if ($sent) {
		error_log('Verification email sent to ' . $user->user_email . ' for user ID ' . $user_id);
	} else {
		error_log('Failed to send verification email to ' . $user->user_email . ' for user ID ' . $user_id);
	}
}

/**
 * Add verification status column to users list in admin
 * Shows verification status in users table
 * 
 * @param array $columns Existing columns
 * @return array Modified columns
 */
function classiadspro_add_verification_column($columns)
{
	$columns['verified'] = __('Status', 'classiadspro');
	return $columns;
}
add_filter('manage_users_columns', 'classiadspro_add_verification_column');

/**
 * Display verification status in users list column
 * 
 * @param string $output Column output
 * @param string $column_name Column name
 * @param int $user_id User ID
 * @return string Column content
 */
function classiadspro_show_verification_column($output, $column_name, $user_id)
{
	if ($column_name === 'verified') {
		$is_verified = get_user_meta($user_id, 'user_verified', true);
		if ($is_verified) {
			return '<span style="color: #28a745; font-weight: bold;">✓ ' . __('Verified', 'classiadspro') . '</span>';
		} else {
			return '<span style="color: #dc3545; font-weight: bold;">✗ ' . __('Not Verified', 'classiadspro') . '</span>';
		}
	}
	return $output;
}
add_filter('manage_users_custom_column', 'classiadspro_show_verification_column', 10, 3);

/**
 * Check if user account is verified
 * Returns true if user is verified, false otherwise
 * 
 * @param int|null $user_id User ID. If not provided, uses current user ID
 * @return bool True if user is verified
 */
function classiadspro_is_user_verified($user_id = null)
{
	if ($user_id === null) {
		$user_id = get_current_user_id();
	}
	
	if (!$user_id) {
		return false;
	}
	
	$is_verified = get_user_meta($user_id, 'user_verified', true);
	return ($is_verified === '1');
}

/**
 * Check user verification and block package selection if not verified
 * Hooks into directorypress_submit_handler_construct filter
 * 
 * @param object $submit_handler The directorypress_submit_handler instance
 * @return object Modified submit handler
 */
function classiadspro_check_user_verification_for_submit($submit_handler)
{
	if (!is_user_logged_in()) {
		return $submit_handler;
	}

	// Check if user is verified
	if (!classiadspro_is_user_verified()) {
		// If trying to access create form, redirect back to packages page with message
		if (isset($submit_handler->template) && is_array($submit_handler->template)) {
			if (in_array('create_advert.php', $submit_handler->template)) {
				directorypress_add_notification(__('Your account must be verified before you can post listings. Please wait for verification.', 'classiadspro'), 'error');
				wp_redirect(directorypress_submitUrl());
				exit;
			}
		}
	}

	return $submit_handler;
}
add_filter('directorypress_submit_handler_construct', 'classiadspro_check_user_verification_for_submit');

/**
 * Block listing submission via AJAX for unverified users
 */
function classiadspro_block_ajax_listing_submission()
{
	if (!is_user_logged_in()) {
		return;
	}

	if (!classiadspro_is_user_verified() && isset($_POST['action']) && $_POST['action'] === 'dpfl_new_listng_submit') {
		wp_send_json(array(
			'type' => 'error',
			'message' => '<div class="alert alert-danger">' . __('Your account must be verified before you can post listings. Please wait for verification.', 'classiadspro') . '</div>'
		));
	}
}
add_action('wp_ajax_dpfl_new_listng_submit', 'classiadspro_block_ajax_listing_submission', 5);
add_action('wp_ajax_nopriv_dpfl_new_listng_submit', 'classiadspro_block_ajax_listing_submission', 5);

/**
 * Show verification required message before packages
 * Hooks into directorypress_submitlisting_packages_rows_before action
 */
function classiadspro_show_verification_message_before_packages($package, $before_tag = '')
{
	static $message_shown = false;
	
	if ($message_shown) {
		return;
	}

	if (!is_user_logged_in()) {
		return;
	}

	if (!classiadspro_is_user_verified()) {
		$message_shown = true;
		echo '<li class="directorypress-list-group-item" style="background-color: transparent; border: none; padding: 0; margin: 0 0 20px 0;">';
		echo '<div class="directorypress-submit-verification-notice" style="padding: 20px; background-color: #fff8e6; border-left: 4px solid #EB6653; border-radius: 4px;">';
		echo '<p style="margin: 0; font-size: 14px; font-weight: bold; color: #EB6653;">⚠️ ' . esc_html__('ACCOUNT VERIFICATION REQUIRED', 'classiadspro') . '</p>';
		echo '<p style="margin: 10px 0 0 0; font-size: 13px; line-height: 1.6; color: #333333;">';
		echo esc_html__('Your account must be verified before you can post listings.', 'classiadspro');
		echo '</p>';
		echo '</div>';
		echo '</li>';
	}
}
add_action('directorypress_submitlisting_packages_rows_before', 'classiadspro_show_verification_message_before_packages', 5, 2);

/**
 * Alternative: Show verification message at the top of submit page
 */
function classiadspro_show_verification_notice_at_top()
{
	if (!is_user_logged_in() || classiadspro_is_user_verified()) {
		return;
	}

	// Check if we're on submit/pricing page
	global $post;
	if (!$post) {
		return;
	}

	// Check if shortcode is present
	if (has_shortcode($post->post_content, 'directorypress-submit')) {
		directorypress_add_notification(__('Your account must be verified before you can post listings.', 'classiadspro'), 'info');
	}
}
add_action('wp', 'classiadspro_show_verification_notice_at_top', 20);

/**
 * Filter package selection buttons to disable them for unverified users
 * 
 * @param string $button_html The button HTML
 * @param object $package The package object
 * @return string Modified button HTML
 */
function classiadspro_filter_package_selection_button($button_html, $package)
{
	if (!is_user_logged_in()) {
		return $button_html;
	}

	if (!classiadspro_is_user_verified()) {
		// Replace the button with disabled version
		$button_html = str_replace(
			'class="pricing-button"',
			'class="pricing-button disabled" style="pointer-events: none; opacity: 0.6; cursor: not-allowed;" data-bs-toggle="tooltip" title="' . esc_attr__('Account verification required to post listings', 'classiadspro') . '"',
			$button_html
		);
		$button_html = str_replace(
			'href="' . directorypress_submitUrl(array('package' => $package->id)),
			'href="#" onclick="return false;"',
			$button_html
		);
	}

	return $button_html;
}

/**
 * Block package selection via JavaScript for unverified users
 */
function classiadspro_block_package_selection_js()
{
	if (!is_user_logged_in() || classiadspro_is_user_verified()) {
		return;
	}

	?>
	<script type="text/javascript">
		(function($) {
			$(document).ready(function() {
				// Disable all package selection links
				$('.directorypress-submit-section-adv a.pricing-button').each(function() {
					var $link = $(this);
					if (!$link.hasClass('disabled')) {
						$link.addClass('disabled')
							.css({
								'pointer-events': 'none',
								'opacity': '0.6',
								'cursor': 'not-allowed'
							})
							.attr('title', '<?php echo esc_js(__('Account verification required to post listings', 'classiadspro')); ?>')
							.attr('href', '#')
							.on('click', function(e) {
								e.preventDefault();
								return false;
							});
					}
				});

				// Show tooltip on hover
				$('.directorypress-submit-section-adv a.pricing-button.disabled').on('mouseenter', function() {
					$(this).attr('data-bs-toggle', 'tooltip');
				});
			});
		})(jQuery);
	</script>
	<?php
}
add_action('wp_footer', 'classiadspro_block_package_selection_js');

// Collapse Filters by default
// add_action('wp_footer', 'classiadspro_collapse_filters', 999);
/**
 * Collapse filters section and individual filter fields by default on page load
 */
function classiadspro_collapse_filters()
{
?>
	<script>
		(function($) {
			'use strict';

			function initFiltersCollapse() {
				var $searchForm = $('.directorypress-search-form');

				if (!$searchForm.length) {
					return;
				}

				// Проверяем размер экрана
				var isMobile = $(window).width() <= 768;

				// Находим кастомный враппер фильтров (уже добавлен в шаблон)
				var $filtersWrapper = $searchForm.find('.custom-filters-wrapper');
				var $filtersHeader = $searchForm.find('.custom-filters-header');

				if ($filtersWrapper.length && $filtersHeader.length) {
					// Скрываем все внутренние элементы с текстом "Filters" чтобы избежать дублирования
					$filtersWrapper.find('*').each(function() {
						var $element = $(this);
						if ($element.text().trim() === "Filters" && !$element.hasClass('custom-filters-header')) {
							$element.hide();
						}
					});

					// Устанавливаем начальное состояние в зависимости от размера экрана
					if (isMobile) {
						$filtersWrapper.addClass('collapsed').hide();
						$filtersHeader.addClass('collapsed');
					} else {
						$filtersWrapper.removeClass('collapsed').show();
						$filtersHeader.removeClass('collapsed');
						// Показываем все фильтры на десктопе
						$filtersWrapper.find('.search-field-content-wrapper, .field-input-wrapper').show();
					}

					// Обработчик клика по общему заголовку
					$filtersHeader.off('click.filtersToggle').on('click.filtersToggle', function(e) {
						e.preventDefault();
						e.stopPropagation();

						console.log('Filters header clicked'); // Отладка

						var $header = $(this);
						var $wrapper = $searchForm.find('.custom-filters-wrapper'); // Ищем в контексте формы
						var isCollapsed = $wrapper.hasClass('collapsed');

						console.log('Is collapsed:', isCollapsed); // Отладка

						$header.toggleClass('collapsed');
						$wrapper.toggleClass('collapsed');

						if (isCollapsed) {
							// Разворачиваем все фильтры
							$wrapper.stop(true, true).slideDown(300);
							$wrapper.find('.search-field-content-wrapper, .field-input-wrapper').show();
							console.log('Expanding filters'); // Отладка
						} else {
							// Сворачиваем все фильтры
							$wrapper.stop(true, true).slideUp(300);
							console.log('Collapsing filters'); // Отладка
						}

						return false;
					});

					// Дополнительный обработчик для клика в любом месте заголовка
					$filtersHeader.css('cursor', 'pointer');
					console.log('Filters header initialized:', $filtersHeader.length); // Отладка
				}
			}

			// Альтернативный обработчик через делегирование событий
			$(document).on('click', '.custom-filters-header', function(e) {
				e.preventDefault();
				e.stopPropagation();

				console.log('Alternative click handler triggered'); // Отладка

				var $header = $(this);
				var $wrapper = $header.siblings('.custom-filters-wrapper');

				if (!$wrapper.length) {
					$wrapper = $header.next('.custom-filters-wrapper');
				}

				if (!$wrapper.length) {
					$wrapper = $header.parent().find('.custom-filters-wrapper');
				}

				var isCollapsed = $wrapper.hasClass('collapsed');

				$header.toggleClass('collapsed');
				$wrapper.toggleClass('collapsed');

				if (isCollapsed) {
					// Разворачиваем все фильтры
					$wrapper.stop(true, true).slideDown(300);
					$wrapper.find('.search-field-content-wrapper, .field-input-wrapper').show();
				} else {
					// Сворачиваем все фильтры
					$wrapper.stop(true, true).slideUp(300);
				}

				return false;
			});

			$(function() {
				// Small delay to ensure DOM is fully loaded
				setTimeout(function() {
					initFiltersCollapse();
				}, 500);

				// Re-initialize on AJAX content load
				$(document).on('directorypress_content_loaded', function() {
					setTimeout(function() {
						initFiltersCollapse();
					}, 500);
				});

				// Переинициализация при изменении размера окна
				$(window).on('resize', function() {
					setTimeout(function() {
						initFiltersCollapse();
					}, 100);
				});
			});
		})(jQuery);
	</script>
	<?php
}

/**
 * Добавляет счетчик непрочитанных сообщений к мобильной иконке чата
 */
function add_mobile_chat_counter()
{
	// Проверяем что пользователь авторизован
	if (!is_user_logged_in()) {
		return;
	}

	// Используем ту же функцию подсчёта, что и плагин directorypress-frontend-messages
	$unread_count = 0;
	if (function_exists('difp_get_user_message_count')) {
		$unread_count = difp_get_user_message_count('unread');
	}

	if ($unread_count > 0) {
	?>
		<script>
			jQuery(document).ready(function($) {
				console.log('Ищем элемент чата для добавления счетчика...');

				// Функция для добавления счетчика
				function addChatCounter() {
					// Расширенный список селекторов для поиска иконки чата
					var selectors = [
						'#mob-messages',
						'a[href*="directorypress_action=messages"]',
						'a[href*="my-dashboard"][href*="messages"]',
						'a:contains("Chat")',
						'.hfb-button:contains("Chat")',
						'[class*="chat"]',
						'[id*="chat"]',
						'i.dicode-material-icons-message-minus-outline'
					];

					var chatLink = null;

					// Ищем по всем селекторам
					for (var i = 0; i < selectors.length; i++) {
						var found = $(selectors[i]);
						if (found.length > 0) {
							// Пропускаем элементы, у которых уже есть badge (в sidebar menu)
							var hasExistingBadge = found.find('.badge, .bg-danger').length > 0 || 
							                   found.closest('li').find('.badge, .bg-danger').length > 0 ||
							                   found.parent().find('.badge, .bg-danger').length > 0;
							if (!hasExistingBadge) {
								console.log('Найден элемент чата:', selectors[i], found);
								chatLink = found.first();
								break;
							}
						}
					}

					// Если нашли родительский элемент с иконкой, ищем ссылку
					if (chatLink && chatLink.find('a').length > 0) {
						chatLink = chatLink.find('a').first();
					}

					if (chatLink && chatLink.length > 0 && !chatLink.find('.chat-counter').length) {
						console.log('Добавляем счетчик к элементу:', chatLink);
						var counter = $('<span class="chat-counter"><?php echo esc_js($unread_count); ?></span>');
						chatLink.css('position', 'relative').append(counter);
						return true;
					}

					return false;
				}

				// Добавляем счетчик сразу
				var success = addChatCounter();

				if (!success) {
					console.log('Элемент чата не найден, запускаем периодическую проверку...');

					// Проверяем каждые 2 секунды на случай если элемент загружается позже
					var attempts = 0;
					var maxAttempts = 15;
					var interval = setInterval(function() {
						attempts++;
						console.log('Попытка ' + attempts + ' из ' + maxAttempts);

						var success = addChatCounter();

						if (success || attempts >= maxAttempts) {
							clearInterval(interval);
							if (success) {
								console.log('Счетчик успешно добавлен!');
							} else {
								console.log('Не удалось найти элемент чата после ' + maxAttempts + ' попыток');
							}
						}
					}, 2000);
				} else {
					console.log('Счетчик добавлен сразу!');
				}
			});
		</script>

		<style>
			.chat-counter {
				position: absolute;
				top: -8px;
				right: -8px;
				background: #ff4444;
				color: white;
				border-radius: 50%;
				width: 20px;
				height: 20px;
				font-size: 12px;
				font-weight: bold;
				display: flex;
				align-items: center;
				justify-content: center;
				z-index: 999;
				box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
			}

			/* Для случая если ссылка не имеет position: relative */
			a[href*="directorypress_action=messages"] {
				position: relative !important;
			}

			#mob-messages {
				position: relative !important;
			}
		</style>
	<?php
	}
}

// Добавляем в footer для мобильных устройств
add_action('wp_footer', 'add_mobile_chat_counter');
/**
 * Добавляет inline стили для страницы рекламирования
 */
function classiadspro_advertise_page_styles()
{
	if (is_page('advertise-listing') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/advertise-listing/') !== false)) {
	?>
		<style>
			/* Убеждаемся что рекомендуемый пакет выделен */
			.period-popular.period-card {
				background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
				border-color: #28a745 !important;
				color: #fff !important;
				transform: translateY(-3px) !important;
				box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3) !important;
			}

			.period-popular .period-price {
				color: #fff !important;
			}

			.period-popular .popular-badge {
				background: #fff !important;
				color: #28a745 !important;
			}
		</style>
	<?php
	}
}
add_action('wp_head', 'classiadspro_advertise_page_styles');

/**
 * Добавляет inline JavaScript для страницы рекламирования
 */
function classiadspro_advertise_page_scripts()
{
	if (is_page('advertise-listing') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/advertise-listing/') !== false)) {
	?>
		<script>
			jQuery(document).ready(function($) {
				// Убеждаемся что рекомендуемый пакет выбран по умолчанию
				if ($('.feature-form').length > 0) {
					// Принудительно выбираем 3-дневный пакет если ничего не выбрано
					if (!$('input[name="advertising_period"]:checked').length) {
						$('input[name="advertising_period"][value="3_days"]').prop('checked', true);
					}

					// Добавляем визуальное выделение для выбранного пакета
					$('input[name="advertising_period"]:checked').each(function() {
						$(this).siblings('.period-card').addClass('period-selected');
					});
				}
			});
		</script>
<?php
	}
}
add_action('wp_footer', 'classiadspro_advertise_page_scripts');
/**
 * Подключает стили и скрипты для страницы рекламирования из папки assets
 */
function classiadspro_advertise_page_assets()
{
	// Check if we're on advertise listing page or dashboard with advertise listing
	$is_advertise_page = is_page('advertise-listing') ||
		(isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/advertise-listing/') !== false) ||
		(isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'advertise_listing') !== false) ||
		(isset($_GET['listing_id']) && isset($_GET['action']) && $_GET['action'] === 'advertise_listing');

	if ($is_advertise_page) {
		$theme_data = wp_get_theme("classiadspro");

		// Подключаем CSS файл для рекламирования
		wp_enqueue_style(
			'advertise-listing-styles',
			get_template_directory_uri() . '/directorypress/assets/css/advertise-listing.css',
			array(),
			$theme_data['Version'],
			'all'
		);

		// Подключаем JavaScript файл для рекламирования
		wp_enqueue_script(
			'advertise-listing-scripts',
			get_template_directory_uri() . '/directorypress/assets/js/advertise-listing.js',
			array('jquery'),
			$theme_data['Version'],
			true
		);
	}
}
add_action('wp_enqueue_scripts', 'classiadspro_advertise_page_assets');
/**
 * Устанавливает цены рекламирования по умолчанию если они не установлены
 */
function classiadspro_ensure_advertising_prices()
{
	$default_prices = array(
		'classiadspro_advertising_price_1_day' => 10,
		'classiadspro_advertising_price_3_days' => 25,
		'classiadspro_advertising_price_7_days' => 50,
	);

	foreach ($default_prices as $option_name => $default_value) {
		if (get_option($option_name) === false) {
			update_option($option_name, $default_value);
		}
	}

	// Также убеждаемся что продукты WooCommerce созданы
	classiadspro_ensure_advertising_products();
}

// Устанавливаем цены при активации темы
add_action('after_switch_theme', 'classiadspro_ensure_advertising_prices');

// Также устанавливаем при каждой загрузке админки (на случай если опции были удалены)
add_action('admin_init', 'classiadspro_ensure_advertising_prices');
/**
 * Принудительно создает продукты рекламирования и устанавливает цены
 * Вызывается при каждой загрузке страницы рекламирования
 */
function classiadspro_force_setup_advertising()
{
	// Устанавливаем цены
	update_option('classiadspro_advertising_price_1_day', 10);
	update_option('classiadspro_advertising_price_3_days', 25);
	update_option('classiadspro_advertising_price_7_days', 50);

	// Создаем продукты если их нет
	if (class_exists('WC_Product')) {
		classiadspro_create_advertising_products();
	}
}

// Вызываем при загрузке страницы рекламирования
add_action('wp', function () {
	if (is_page('advertise-listing') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/advertise-listing/') !== false)) {
		classiadspro_force_setup_advertising();
	}
});

function rs_get_cookie_domain(){
	$host = $_SERVER['HTTP_HOST'];
	$host = preg_replace('/:\d+$/', '', $host);

	if($host === 'localhost' || strpos($host, '.local') !== false){
		return '';
	}

	if(filter_var($host, FILTER_VALIDATE_IP)){
		return '';
	}

	$parts = explode('.', $host);
	if(count($parts) >= 2){
		return '.' . implode('.', array_slice($parts, -2));
	}

	return '';
}
/* ========================= SHORTCODE: CURRENCY SELECTOR ========================= */

/**
 * Получить список уникальных валют из карты валют
 */
function rs_get_unique_currencies() {
	$map = rs_get_currency_map_with_rates();
	$unique = [];
	$seen_codes = [];

	foreach ($map as $country_code => $currency) {
		$code = $currency['code'];
		// KZT не показываем в селекторе, чтобы валюта домена doska.kz оставалась базовой.
		if ($code === 'KZT') continue;
		if (!in_array($code, $seen_codes)) {
			$seen_codes[] = $code;
			$unique[] = [
				'code' => $code,
				'symbol' => $currency['symbol'],
				'rate' => $currency['rate'],
				'position' => $currency['position'],
				'decimal_sep' => $currency['decimal_sep'],
				'thousand_sep' => $currency['thousand_sep'],
			];
		}
	}

	return $unique;
}

function rs_render_currency_selector($args = []) {
	$currencies = rs_get_unique_currencies();
	if (!$currencies) {
		return '';
	}

	$args = wp_parse_args(
		$args,
		[
			'layout' => wp_is_mobile() ? 'select' : 'dropdown',
			'selected_display' => 'symbol',
			'context_class' => '',
		]
	);

	$current = rs_get_current_currency();
	$current_code = $current['code'];
	$context_class = trim((string) $args['context_class']);
	$selector_classes = 'rs-currency-selector';

	if ($context_class !== '') {
		$selector_classes .= ' ' . $context_class;
	}

	ob_start();

	if ($args['layout'] === 'select') : ?>

		<div class="<?php echo esc_attr($selector_classes . ' rs-currency-selector--mobile'); ?>">
			<?php $select_id = wp_unique_id('rs-currency-select-'); ?>
			<label class="screen-reader-text" for="<?php echo esc_attr($select_id); ?>"><?php esc_html_e('Select currency', 'classiadspro'); ?></label>
			<select id="<?php echo esc_attr($select_id); ?>" class="rs-currency-select" aria-label="<?php esc_attr_e('Select currency', 'classiadspro'); ?>">
				<?php foreach($currencies as $c): ?>
					<option value="<?= esc_attr($c['code']) ?>" <?= selected($c['code'], $current_code, false) ?>>
						<?= esc_html($c['symbol'] . ' ' . $c['code']) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

	<?php
	else : ?>

		<div class="rs-dropdown rs-currency-dropdown <?php echo esc_attr($selector_classes); ?>">
			<button
				type="button"
				class="rs-dropdown-btn rs-currency-btn"
				aria-haspopup="true"
				aria-expanded="false"
				aria-label="<?php echo esc_attr(sprintf(__('Selected currency: %s', 'classiadspro'), $current_code)); ?>"
			>
				<span class="rs-currency-current-symbol"><?= esc_html($current['symbol']) ?></span>
				<?php if ($args['selected_display'] !== 'symbol') : ?>
					<span class="rs-currency-current-code"><?= esc_html($current['code']) ?></span>
				<?php endif; ?>
				<i class="rs-dropdown-caret fas fa-angle-down" aria-hidden="true"></i>
			</button>
			<ul class="rs-dropdown-list rs-currency-list" hidden>
				<?php foreach($currencies as $c): ?>
					<li class="<?= ($c['code'] === $current_code) ? 'rs-active' : '' ?>">
						<button type="button" class="rs-currency-option" data-code="<?= esc_attr($c['code']) ?>" aria-pressed="<?= ($c['code'] === $current_code) ? 'true' : 'false' ?>">
							<span class="rs-currency-symbol"><?= esc_html($c['symbol']) ?></span>
							<span class="rs-currency-code"><?= esc_html($c['code']) ?></span>
							<?php if($c['code'] === $current_code): ?>
								<span class="rs-check" aria-hidden="true">✓</span>
							<?php endif; ?>
						</button>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

	<?php endif;

	return ob_get_clean();
}

add_shortcode('rs_currency_selector', function($atts){
	$atts = shortcode_atts(
		[
			'layout' => wp_is_mobile() ? 'select' : 'dropdown',
			'selected_display' => 'symbol',
			'context_class' => '',
		],
		(array) $atts,
		'rs_currency_selector'
	);

	return rs_render_currency_selector($atts);
});

add_filter('wp_nav_menu_items', function($items, $args){
	$items = do_shortcode($items);

	$patterns = [
		'~<li([^>]*)>\s*<a\b[^>]*>\s*(<div class="rs-dropdown rs-currency-dropdown rs-currency-selector">.*?</div>)\s*</a>\s*</li>~is',
		'~<li([^>]*)>\s*<a\b[^>]*>\s*(<div class="rs-currency-selector rs-currency-selector--mobile">.*?</div>)\s*</a>\s*</li>~is',
	];

	foreach ($patterns as $pattern) {
		$items = preg_replace_callback($pattern, function($matches) {
			$li_attributes = $matches[1];

			if (strpos($li_attributes, 'rs-currency-menu-item') === false) {
				if (preg_match('/class=(["\'])(.*?)\1/i', $li_attributes, $class_matches)) {
					$updated_classes = trim($class_matches[2] . ' rs-currency-menu-item');
					$li_attributes = preg_replace(
						'/class=(["\'])(.*?)\1/i',
						'class=$1' . esc_attr($updated_classes) . '$1',
						$li_attributes,
						1
					);
				} else {
					$li_attributes .= ' class="rs-currency-menu-item"';
				}
			}

			return '<li' . $li_attributes . '>' . $matches[2] . '</li>';
		}, $items);
	}

	return $items;
}, 10, 2);

function classiadspro_inject_currency_switcher_into_header_menu($items, $args) {
	if (is_admin() || wp_is_mobile()) {
		return $items;
	}

	if (
		empty($args->theme_location) ||
		!in_array($args->theme_location, ['primary-menu', 'second-menu', 'third-menu', 'fourth-menu', 'fifth-menu', 'sixth-menu', 'seventh-menu'], true)
	) {
		return $items;
	}

	if (strpos($items, 'rs-currency-menu-item') !== false) {
		return $items;
	}

	$currency_item = '<li class="menu-item rs-currency-menu-item">' . rs_render_currency_selector([
		'layout' => 'dropdown',
		'selected_display' => 'symbol',
		'context_class' => 'rs-currency-selector--compact',
	]) . '</li>';

	$language_pattern = '~(<li[^>]*class="[^"]*\btrp-language-switcher-container\b[^"]*"[^>]*>.*?</li>)~is';
	if (preg_match($language_pattern, $items)) {
		return preg_replace($language_pattern, '$1' . $currency_item, $items, 1);
	}

	$header_items_pattern = '~(<li[^>]*class="[^"]*\b(?:pacz-header-search|logreg-header|listing-btn)\b[^"]*"[^>]*>)~i';
	if (preg_match($header_items_pattern, $items)) {
		return preg_replace($header_items_pattern, $currency_item . '$1', $items, 1);
	}

	return $items . $currency_item;
}
add_filter('wp_nav_menu_items', 'classiadspro_inject_currency_switcher_into_header_menu', 20, 2);

/**
 * Keep TranslatePress menu switchers in sync with published languages.
 *
 * Some menus in this project use a "Current language" parent item plus
 * explicit child items for each published language. If one child item is
 * missing in the menu data, TranslatePress won't render that language in the
 * header switcher even though the language itself is still published.
 */
function classiadspro_get_trp_language_switcher_label($language_code) {
	if (!class_exists('TRP_Translate_Press')) {
		return strtoupper($language_code);
	}

	$trp = TRP_Translate_Press::get_trp_instance();
	if (!is_object($trp) || !method_exists($trp, 'get_component')) {
		return strtoupper($language_code);
	}

	$languages_component = $trp->get_component('languages');
	if (!is_object($languages_component) || !method_exists($languages_component, 'get_wp_languages')) {
		return strtoupper($language_code);
	}

	$wp_languages = $languages_component->get_wp_languages();
	if (!is_array($wp_languages) || empty($wp_languages[$language_code])) {
		return strtoupper($language_code);
	}

	$language = $wp_languages[$language_code];

	if (!empty($language['native_name'])) {
		return $language['native_name'];
	}

	if (!empty($language['english_name'])) {
		return $language['english_name'];
	}

	return !empty($language['name']) ? $language['name'] : strtoupper($language_code);
}

function classiadspro_ensure_trp_language_switcher_posts($language_codes) {
	$language_codes = array_values(array_unique(array_filter(array_map('strval', (array) $language_codes))));
	if (empty($language_codes)) {
		return [];
	}

	$existing_posts = get_posts([
		'post_type'        => 'language_switcher',
		'post_status'      => ['publish', 'draft', 'private'],
		'numberposts'      => -1,
		'suppress_filters' => false,
	]);

	$switchers_by_code = [];
	foreach ($existing_posts as $switcher_post) {
		$code = trim((string) $switcher_post->post_content);
		if ($code !== '') {
			$switchers_by_code[$code] = $switcher_post;
		}
	}

	foreach ($language_codes as $language_code) {
		if (isset($switchers_by_code[$language_code])) {
			continue;
		}

		$new_post_id = wp_insert_post([
			'post_type'    => 'language_switcher',
			'post_status'  => 'publish',
			'post_title'   => classiadspro_get_trp_language_switcher_label($language_code),
			'post_content' => $language_code,
			'post_name'    => sanitize_title('trp-language-' . $language_code),
		], true);

		if (is_wp_error($new_post_id) || !$new_post_id) {
			continue;
		}

		$switchers_by_code[$language_code] = get_post($new_post_id);
	}

	return $switchers_by_code;
}

function classiadspro_sync_trp_menu_switcher_languages() {
	global $wpdb;

	if (!class_exists('TRP_Translate_Press') || !function_exists('wp_get_nav_menus')) {
		return;
	}

	$settings = get_option('trp_settings', []);
	if (empty($settings['publish-languages']) || !is_array($settings['publish-languages'])) {
		return;
	}

	$signature_parts = $settings['publish-languages'];
	sort($signature_parts);
	$sync_signature = md5(wp_json_encode($signature_parts));

	if (get_option('classiadspro_trp_menu_sync_signature') === $sync_signature) {
		return;
	}

	$switchers_by_code = classiadspro_ensure_trp_language_switcher_posts($settings['publish-languages']);

	if (empty($switchers_by_code['current_language'])) {
		return;
	}

	$did_sync = false;
	$menus = wp_get_nav_menus();

	foreach ($menus as $menu) {
		$menu_item_ids = $wpdb->get_col($wpdb->prepare(
			"SELECT p.ID
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->term_relationships} tr ON tr.object_id = p.ID
			INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
			WHERE p.post_type = 'nav_menu_item'
				AND p.post_status IN ('publish', 'draft')
				AND tt.taxonomy = 'nav_menu'
				AND tt.term_id = %d
			ORDER BY p.menu_order ASC, p.ID ASC",
			$menu->term_id
		));

		if (empty($menu_item_ids)) {
			continue;
		}

		$menu_items = get_posts([
			'post_type'        => 'nav_menu_item',
			'post_status'      => ['publish', 'draft'],
			'numberposts'      => -1,
			'post__in'         => $menu_item_ids,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'suppress_filters' => true,
		]);

		if (empty($menu_items)) {
			continue;
		}

		$current_language_item = null;
		$child_items_by_code = [];
		$duplicate_child_ids = [];
		$template_child = null;

		foreach ($menu_items as $menu_item) {
			$menu_item_object = get_post_meta($menu_item->ID, '_menu_item_object', true);
			if ($menu_item_object !== 'language_switcher') {
				continue;
			}

			$parent_item_id = (int) get_post_meta($menu_item->ID, '_menu_item_menu_item_parent', true);
			$object_id = (int) get_post_meta($menu_item->ID, '_menu_item_object_id', true);
			if (!$object_id) {
				continue;
			}

			$switcher_post = get_post($object_id);
			if (!$switcher_post || $switcher_post->post_type !== 'language_switcher') {
				continue;
			}

			$language_code = trim((string) $switcher_post->post_content);
			if ($language_code === 'current_language') {
				$current_language_item = $menu_item;
				continue;
			}

			if (isset($child_items_by_code[$language_code])) {
				$duplicate_child_ids[] = (int) $menu_item->ID;
				continue;
			}

			$child_items_by_code[$language_code] = $menu_item;
			if ($parent_item_id > 0 && $template_child === null) {
				$template_child = $menu_item;
			}
		}

		if (!$current_language_item) {
			continue;
		}

		foreach ($duplicate_child_ids as $duplicate_child_id) {
			wp_delete_post($duplicate_child_id, true);
			$did_sync = true;
		}

		foreach ($settings['publish-languages'] as $language_code) {
			if (isset($child_items_by_code[$language_code]) || empty($switchers_by_code[$language_code])) {
				continue;
			}

			$new_item_id = wp_update_nav_menu_item($menu->term_id, 0, [
				'menu-item-title'     => $switchers_by_code[$language_code]->post_title,
				'menu-item-object-id' => $switchers_by_code[$language_code]->ID,
				'menu-item-object'    => 'language_switcher',
				'menu-item-parent-id' => (int) $current_language_item->ID,
				'menu-item-position'  => (int) $current_language_item->menu_order + 1,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-classes'   => 'trp-language-switcher-container trp-menu-ls-item trp-menu-ls-desktop',
			]);

			if (is_wp_error($new_item_id) || !$new_item_id) {
				continue;
			}

			// Copy menu-specific styling flags from an existing language child if present.
			if ($template_child) {
				$meta_keys_to_copy = [
					'_menu_item_megamenu',
					'_menu_item_megamenu_background',
					'_menu_item_megamenu_styles',
					'_menu_item_megamenu_widgetarea',
					'_menu_item_menu_icon',
				];

				foreach ($meta_keys_to_copy as $meta_key) {
					$meta_value = get_post_meta($template_child->ID, $meta_key, true);
					if ($meta_value !== '') {
						update_post_meta($new_item_id, $meta_key, $meta_value);
					}
				}
			}

			$did_sync = true;
		}
	}

	if ($did_sync) {
		delete_option('classiadspro_trp_menu_sync_signature');
	}

	update_option('classiadspro_trp_menu_sync_signature', $sync_signature, false);
}
add_action('init', 'classiadspro_sync_trp_menu_switcher_languages', 20);

// ==================== CURRENCY CONVERSION BY REGION ====================

/**
 * Карта валют по регионам
 * Базовая валюта - USD
 */
function rs_get_currency_map() {
	// Карта валют по КОДУ СТРАНЫ — страна определяется по куке
	return [
		// Казахстан - тенге
		'kz' => [
			'symbol' => '₸',
			'code' => 'KZT',
			'rate' => 512.00,
			'position' => 4,
			'decimal_sep' => ',',
			'thousand_sep' => ' ',
		],
		// США - доллар
		'us' => [
			'symbol' => '$',
			'code' => 'USD',
			'rate' => 1,
			'position' => 1,
			'decimal_sep' => '.',
			'thousand_sep' => ',',
		],
		// Испания - евро
		'es' => [
			'symbol' => '€',
			'code' => 'EUR',
			'rate' => 0.92,
			'position' => 4,
			'decimal_sep' => ',',
			'thousand_sep' => '.',
		],
		// Германия - евро
		'de' => [
			'symbol' => '€',
			'code' => 'EUR',
			'rate' => 0.92,
			'position' => 4,
			'decimal_sep' => ',',
			'thousand_sep' => '.',
		],
		// Турция - лира
		'tr' => [
			'symbol' => '₺',
			'code' => 'TRY',
			'rate' => 32.50,
			'position' => 4,
			'decimal_sep' => ',',
			'thousand_sep' => '.',
		],
		// Украина - гривна
		'ua' => [
			'symbol' => '₴',
			'code' => 'UAH',
			'rate' => 41.00,
			'position' => 4,
			'decimal_sep' => ',',
			'thousand_sep' => ' ',
		],
	];
}

function rs_get_default_currency_code() {
	return 'TRY';
}

function rs_get_currency_by_code($currency_code) {
	$currency_code = strtoupper(trim((string) $currency_code));
	if ($currency_code === '') {
		return null;
	}

	foreach (rs_get_currency_map_with_rates() as $currency) {
		if (!empty($currency['code']) && strtoupper($currency['code']) === $currency_code) {
			return $currency;
		}
	}

	return null;
}

function rs_set_currency_cookie($currency_code) {
	$currency_code = strtoupper(trim((string) $currency_code));
	if ($currency_code === '') {
		return;
	}

	$expires = time() + MONTH_IN_SECONDS;
	$domain = rs_get_cookie_domain();
	$options = [
		'expires'  => $expires,
		'path'     => '/',
		'secure'   => is_ssl(),
		'httponly' => false,
		'samesite' => 'Lax',
	];

	if ($domain !== '') {
		$options['domain'] = $domain;
	}

	setcookie('rs_currency', $currency_code, $options);
	$_COOKIE['rs_currency'] = $currency_code;
}

function rs_wpsc_currency_cache_key($string) {
	if (empty($_COOKIE['rs_currency'])) {
		return $string;
	}

	return $string . 'rs_currency=' . strtoupper(trim((string) $_COOKIE['rs_currency'])) . ',';
}

if (function_exists('add_cacheaction')) {
	add_cacheaction('wp_cache_get_cookies_values', 'rs_wpsc_currency_cache_key');
}

/**
 * Получить текущую валюту
 */
function rs_get_current_currency() {
	$map = rs_get_currency_map_with_rates();

	if (!empty($_COOKIE['rs_currency'])) {
		$manual_currency = rs_get_currency_by_code($_COOKIE['rs_currency']);
		if ($manual_currency) {
			return $manual_currency;
		}
	}

	$host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
	$domain_map = [
		'doska.kz' => 'kz',
	];
	if ($host !== '') {
		foreach ($domain_map as $domain => $code) {
			if ($host === $domain || strpos($host, '.' . $domain) !== false) {
				if (isset($map[$code])) {
					return $map[$code];
				}
			}
		}
	}

	if (strpos($host, 'localhost') !== false) {
		return $map['tr'];
	}

	$default_currency = rs_get_currency_by_code(rs_get_default_currency_code());
	return $default_currency ?: $map['tr'];
}

function rs_is_api_enabled() {
	$settings = get_option('rs_currency_settings', []);
	return !empty($settings['api_enabled']);
}

function rs_update_exchange_rates() {
	if (!rs_is_api_enabled()) {
		return false;
	}

	$response = wp_remote_get('https://open.er-api.com/v6/latest/USD', [
		'timeout' => 30,
	]);

	if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
		$body = json_decode(wp_remote_retrieve_body($response), true);

		if (isset($body['rates'])) {
			$rates = $body['rates'];

			$saved_rates = get_option('rs_exchange_rates', []);
			$saved_rates['EUR'] = $rates['EUR'] ?? ($saved_rates['EUR'] ?? 0.92);
			$saved_rates['TRY'] = $rates['TRY'] ?? ($saved_rates['TRY'] ?? 32.50);
			$saved_rates['UAH'] = $rates['UAH'] ?? ($saved_rates['UAH'] ?? 41.00);
			$saved_rates['KZT'] = $rates['KZT'] ?? ($saved_rates['KZT'] ?? 512.00);
			$saved_rates['updated'] = time();
			$saved_rates['source'] = 'open.er-api.com';

			update_option('rs_exchange_rates', $saved_rates);
			return true;
		}
	}

	return false;
}

function rs_get_exchange_rate($currency_code) {
	$saved = get_option('rs_exchange_rates', []);

	if (rs_is_api_enabled() && (empty($saved['updated']) || (time() - $saved['updated'] > 86400))) {
		rs_update_exchange_rates();
		$saved = get_option('rs_exchange_rates', []);
	}

	return $saved[$currency_code] ?? 1;
}

/**
 * Получить карту валют с актуальными курсами
 */
function rs_get_currency_map_with_rates() {
	$map = rs_get_currency_map();
	$rates = get_option('rs_exchange_rates', []);
	
	if (!empty($rates)) {
		if (isset($rates['EUR'])) {
			$map['es']['rate'] = $rates['EUR'];
			$map['de']['rate'] = $rates['EUR'];
		}
		if (isset($rates['TRY'])) {
			$map['tr']['rate'] = $rates['TRY'];
		}
		if (isset($rates['UAH'])) {
			$map['ua']['rate'] = $rates['UAH'];
		}
		if (isset($rates['KZT'])) {
			$map['kz']['rate'] = $rates['KZT'];
		}
	}
	
	return $map;
}

/**
 * Конвертация цены
 */
function rs_convert_price($price) {
	if (!is_numeric($price) || $price <= 0) {
		return $price;
	}
	
	$currency = rs_get_current_currency();
	return round($price * $currency['rate'], 2);
}

/**
 * Перевод цены из выбранной пользователем валюты в базовую USD перед сохранением.
 */
function rs_normalize_price_to_base_currency($price) {
	if (!is_numeric($price) || $price <= 0) {
		return $price;
	}

	$currency = rs_get_current_currency();
	$rate = isset($currency['rate']) ? (float) $currency['rate'] : 0.0;

	if ($rate <= 0) {
		return $price;
	}

	return round(((float) $price) / $rate, 6);
}

/**
 * Форматирование цены с символом валюты
 */
function rs_format_price($price, $convert = true) {
	if ($convert) {
		$price = rs_convert_price($price);
	}
	
	$currency = rs_get_current_currency();
	
	$formatted = number_format(
		$price,
		2,
		$currency['decimal_sep'],
		$currency['thousand_sep']
	);
	
	// Убираем лишние нули
	$formatted = rtrim(rtrim($formatted, '0'), $currency['decimal_sep']);
	
	// Добавляем символ валюты
	switch ($currency['position']) {
		case 1:
			return $currency['symbol'] . $formatted;
		case 2:
			return $currency['symbol'] . ' ' . $formatted;
		case 3:
			return $formatted . $currency['symbol'];
		case 4:
		default:
			return $formatted . ' ' . $currency['symbol'];
	}
}

// ==================== HOOKS ====================

// Конвертация цен в полях DirectoryPress
add_filter('directorypress_field_load', function($data, $field, $post_id) {
	if (get_class($field) === 'directorypress_field_price') {
		$currency = rs_get_current_currency();
		
		// Конвертируем значения
		if (isset($data['value']) && is_numeric($data['value']) && $data['value'] > 0) {
			$data['value'] = round($data['value'] * $currency['rate'], 2);
		}
		if (isset($data['value_2']) && is_numeric($data['value_2']) && $data['value_2'] > 0) {
			$data['value_2'] = round($data['value_2'] * $currency['rate'], 2);
		}
		
		// Подменяем символ валюты
		if (!empty($field->has_frontend_currency)) {
			$data['frontend_currency'] = $currency['symbol'];
		}
	}
	return $data;
}, 10, 3);

/**
 * DirectoryPress хранит цену как базовое значение.
 * Пользователь вводит цену в текущей валюте интерфейса, поэтому
 * после стандартного сохранения нормализуем ее обратно в USD.
 */
function rs_normalize_directorypress_price_meta($post_id, $post, $update) {
	if (wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
		return;
	}

	if (empty($_POST) || !is_object($post)) {
		return;
	}

	if (empty($post->post_type) || strpos($post->post_type, 'listing') === false) {
		return;
	}

	if (!function_exists('directorypress_get_input_value')) {
		return;
	}

	global $directorypress_object;

	if (empty($directorypress_object) || empty($directorypress_object->fields) || empty($directorypress_object->fields->fields_array)) {
		return;
	}

	foreach ($directorypress_object->fields->fields_array as $field) {
		if (!is_object($field) || get_class($field) !== 'directorypress_field_price') {
			continue;
		}

		$price_input_name = 'directorypress-field-input-' . $field->id;
		if (!isset($_POST[$price_input_name])) {
			continue;
		}

		$submitted_price = directorypress_get_input_value($_POST, $price_input_name);
		$normalized_price = rs_normalize_price_to_base_currency($submitted_price);
		update_post_meta($post_id, '_field_' . $field->id, $normalized_price);

		$max_price_input_name = 'directorypress-field-input-max-' . $field->id;
		if (isset($_POST[$max_price_input_name])) {
			$submitted_max_price = directorypress_get_input_value($_POST, $max_price_input_name);
			$normalized_max_price = rs_normalize_price_to_base_currency($submitted_max_price);
			update_post_meta($post_id, '_field_' . $field->id . '_max', $normalized_max_price);
		}
	}
}
add_action('save_post', 'rs_normalize_directorypress_price_meta', 100, 3);

// Изменение валюты WooCommerce
add_filter('woocommerce_currency', function($currency) {
	$curr = rs_get_current_currency();
	return $curr['code'];
});

// Изменение символа валюты WooCommerce
add_filter('woocommerce_currency_symbol', function($symbol, $currency_code) {
	$curr = rs_get_current_currency();
	return $curr['symbol'];
}, 10, 2);

// Конвертация цен в корзине WooCommerce
add_filter('woocommerce_product_get_price', function($price, $product) {
	return rs_convert_price($price);
}, 10, 2);

add_filter('woocommerce_product_get_regular_price', function($price, $product) {
	return rs_convert_price($price);
}, 10, 2);

add_filter('woocommerce_product_get_sale_price', function($price, $product) {
	return rs_convert_price($price);
}, 10, 2);

// Конвертация цен для вариаций
add_filter('woocommerce_product_variation_get_price', function($price, $product) {
	return rs_convert_price($price);
}, 10, 2);

function rs_ensure_exchange_rates_event() {
	$next_run = wp_next_scheduled('rs_update_rates_event');

	if (!rs_is_api_enabled()) {
		if ($next_run) {
			wp_clear_scheduled_hook('rs_update_rates_event');
		}
		return;
	}

	// Самовосстановление: если событие застряло в прошлом, пересоздаем его.
	if ($next_run && $next_run < (time() - HOUR_IN_SECONDS)) {
		wp_clear_scheduled_hook('rs_update_rates_event');
		$next_run = false;
	}

	if (!$next_run) {
		wp_schedule_event(time() + MINUTE_IN_SECONDS, 'daily', 'rs_update_rates_event');
	}
}

// Запуск обновления курсов по крону
add_action('init', 'rs_ensure_exchange_rates_event', 20);
add_action('rs_update_rates_event', 'rs_update_exchange_rates');

// Инициализация курсов при первом запуске
add_action('init', function() {
	if (get_option('rs_exchange_rates') === false) {
		rs_update_exchange_rates();
	}
});

// AJAX для ручного обновления курсов (для админки)
add_action('wp_ajax_rs_refresh_rates', function() {
	if (!current_user_can('manage_options')) {
		wp_send_json_error('No permission');
	}

	check_ajax_referer('rs_refresh_rates_nonce');

	$result = rs_update_exchange_rates();

	if ($result) {
		$rates = get_option('rs_exchange_rates', []);
		wp_send_json_success([
			'message' => 'Курсы обновлены',
			'rates' => $rates,
		]);
	} else {
		wp_send_json_error('Ошибка обновления. Проверьте что API включено.');
	}
});

// ==================== ADMIN PAGE ====================

add_action('admin_menu', function() {
	add_menu_page(
		'Курсы валют',
		'Курсы валют',
		'manage_options',
		'rs-exchange-rates',
		'rs_exchange_rates_admin_page',
		'dashicons-money-alt',
		56
	);
});

function rs_exchange_rates_admin_page() {
	if (!current_user_can('manage_options')) {
		return;
	}

	if (isset($_POST['rs_save_rates']) && check_admin_referer('rs_exchange_rates_save')) {
		$settings = [
			'api_enabled' => !empty($_POST['api_enabled']),
		];
		update_option('rs_currency_settings', $settings);

		$saved_rates = get_option('rs_exchange_rates', []);
		if (!empty($_POST['rate_eur'])) {
			$saved_rates['EUR'] = floatval($_POST['rate_eur']);
		}
		if (!empty($_POST['rate_try'])) {
			$saved_rates['TRY'] = floatval($_POST['rate_try']);
		}
		if (!empty($_POST['rate_uah'])) {
			$saved_rates['UAH'] = floatval($_POST['rate_uah']);
		}
		if (!empty($_POST['rate_kzt'])) {
			$saved_rates['KZT'] = floatval($_POST['rate_kzt']);
		}
		if (empty($saved_rates['updated'])) {
			$saved_rates['updated'] = time();
			$saved_rates['source'] = 'manual';
		}
		if (!empty($saved_rates['source']) && $saved_rates['source'] !== 'open.er-api.com') {
			$saved_rates['source'] = 'manual';
		}
		update_option('rs_exchange_rates', $saved_rates);
		rs_ensure_exchange_rates_event();

		echo '<div class="notice notice-success is-dismissible"><p>Настройки сохранены.</p></div>';
	}

	$settings = get_option('rs_currency_settings', ['api_enabled' => true]);
	$rates = get_option('rs_exchange_rates', [
		'EUR' => 0.92,
		'TRY' => 32.50,
		'UAH' => 41.00,
		'KZT' => 512.00,
		'updated' => null,
		'source' => 'manual',
	]);

	$last_updated = !empty($rates['updated'])
		? date('d.m.Y H:i:s', $rates['updated']) . ' (UTC+' . date('P', $rates['updated']) . ')'
		: 'никогда';
	?>
	<div class="wrap">
		<h1>Курсы валют</h1>

		<form method="post" action="">
			<?php wp_nonce_field('rs_exchange_rates_save'); ?>

			<table class="form-table">
				<tr>
					<th scope="row">Автообновление через API</th>
					<td>
						<label>
							<input type="checkbox" name="api_enabled" value="1" <?php checked(!empty($settings['api_enabled'])); ?>>
							Включить автоматическое получение курсов с <code>open.er-api.com</code>
						</label>
						<p class="description">
							При включении курсы обновляются автоматически раз в сутки.<br>
							При выключении используются только ручные значения ниже.
						</p>
					</td>
				</tr>
			</table>

			<hr>

			<h2>Курсы валют (относительно 1 USD)</h2>

			<table class="widefat striped" style="max-width: 500px;">
				<thead>
					<tr>
						<th>Валюта</th>
						<th>Курс</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>EUR</strong> &euro;</td>
						<td>
							<input type="number" name="rate_eur" value="<?php echo esc_attr($rates['EUR'] ?? 0.92); ?>" step="0.0001" min="0" class="regular-text" style="width:150px;">
						</td>
					</tr>
					<tr>
						<td><strong>TRY</strong> &#8378;</td>
						<td>
							<input type="number" name="rate_try" value="<?php echo esc_attr($rates['TRY'] ?? 32.50); ?>" step="0.0001" min="0" class="regular-text" style="width:150px;">
						</td>
					</tr>
					<tr>
						<td><strong>UAH</strong> &#8372;</td>
						<td>
							<input type="number" name="rate_uah" value="<?php echo esc_attr($rates['UAH'] ?? 41.00); ?>" step="0.0001" min="0" class="regular-text" style="width:150px;">
						</td>
					</tr>
					<tr>
						<td><strong>KZT</strong> &#8376;</td>
						<td>
							<input type="number" name="rate_kzt" value="<?php echo esc_attr($rates['KZT'] ?? 512.00); ?>" step="0.0001" min="0" class="regular-text" style="width:150px;">
						</td>
					</tr>
				</tbody>
			</table>

			<p class="description" style="margin-top:10px;">
				Источник: <strong><?php echo esc_html($rates['source'] ?? 'manual'); ?></strong> |
				Последнее обновление: <strong><?php echo esc_html($last_updated); ?></strong>
			</p>

			<?php submit_button('Сохранить настройки', 'primary', 'rs_save_rates'); ?>
		</form>

		<hr>

		<h2>Ручное обновление с API</h2>
		<p>Нажмите кнопку, чтобы принудительно обновить курсы с API (независимо от времени последнего обновления).</p>
		<p>
			<button type="button" id="rs-refresh-btn" class="button button-secondary">
				<span class="dashicons dashicons-update" style="vertical-align:middle;"></span>
				Обновить курсы сейчас
			</button>
			<span id="rs-refresh-status"></span>
		</p>

		<script>
		jQuery(document).ready(function($) {
			$('#rs-refresh-btn').on('click', function() {
				var $btn = $(this);
				var $status = $('#rs-refresh-status');
				$btn.prop('disabled', true);
				$status.text('Обновление...');

				$.post(ajaxurl, {
					action: 'rs_refresh_rates',
					_ajax_nonce: '<?php echo esc_js(wp_create_nonce("rs_refresh_rates_nonce")); ?>'
				}, function(response) {
					$btn.prop('disabled', false);
					if (response.success) {
						$status.html('<span style="color:green;">&#10003; ' + response.data.message + '</span>');
						if (response.data.rates) {
							var r = response.data.rates;
							$('[name="rate_eur"]').val(r.EUR);
							$('[name="rate_try"]').val(r.TRY);
							$('[name="rate_uah"]').val(r.UAH);
							$('[name="rate_kzt"]').val(r.KZT);
						}
					} else {
						$status.html('<span style="color:red;">&#10007; ' + (response.data || 'Ошибка') + '</span>');
					}
				}).fail(function() {
					$btn.prop('disabled', false);
					$status.html('<span style="color:red;">&#10007; Ошибка запроса</span>');
				});
			});
		});
		</script>
	</div>
	<?php
}
/*
add_action('admin_footer', function() {
    global $post;
    if (!$post) return;

    $taxonomies = get_object_taxonomies($post->post_type);

    echo '<pre>';

    foreach ($taxonomies as $taxonomy) {
        echo "TAXONOMY: " . $taxonomy . "\n";

        $terms = wp_get_post_terms($post->ID, $taxonomy);

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                echo " - ID: " . $term->term_id . " | Name: " . $term->name . "\n";
            }
        } else {
            echo " - нет значений\n";
        }

        echo "\n";
    }

    echo '</pre>';
});
*/

add_action('admin_footer', function() {
    global $post;
    if (!$post) return;

    /*echo '<pre>';
    print_r(get_post_meta($post->ID));
    echo '</pre>';
	
	echo '<pre>';
    print_r(wp_get_object_terms($post->ID, 'directorypress-location'));
    echo '</pre>';
	
	echo '<pre>';
	print_r([
		'ID' => $post->ID,
		'type' => $post->post_type,
		'title' => $post->post_title,
		'meta' => get_post_meta($post->ID),
		'taxonomies' => get_object_taxonomies($post->post_type),
		'terms' => wp_get_post_terms($post->ID, get_object_taxonomies($post->post_type)),
	]);
	echo '</pre>';
	*/
});


add_action('init', function () {

    if (!is_admin())
        return;
    if (empty($_GET['add_tex_location']))
        return;
    if (!current_user_can('manage_options'))
        wp_die('Нет доступа.');

    $post_id    = intval($_GET['post'] ?? 0);
    $good_id    = intval($_GET['good'] ?? 0);
    $term_id    = intval($_GET['add_tex_location']);
    $do_process = intval($_GET['do_process'] ?? 0);

    if (!$post_id || !$term_id) {
        wp_die('Ошибка: не указан post или add_tex_location');
    }

    global $wpdb, $directorypress_object;

    if (!isset($directorypress_object) || !is_object($directorypress_object)) {
        wp_die('DirectoryPress не загружен.');
    }

    $validation_results = null;

    // =====================================================
    // ОБРАБОТКА — только если do_process=1
    // =====================================================
    if ($do_process) {

        // -------------------------------------------------------
        // 1. Симулируем POST данные формы DirectoryPress
        // -------------------------------------------------------
        $_POST['directorypress_location'] = [$term_id];
        $_POST['selected_tax'] = [$term_id];
        $_POST['address_line_1'] = [''];
        $_POST['address_line_2'] = [''];
        $_POST['zip_or_postal_index'] = [''];
        $_POST['additional_info'] = [''];
        $_POST['manual_coords'] = [''];
        $_POST['map_coords_1'] = [''];
        $_POST['map_coords_2'] = [''];
        $_POST['map_zoom'] = '';

        // -------------------------------------------------------
        // 2. Инициализируем листинг
        // -------------------------------------------------------
        $listing = new directorypress_listing();
        $listing->directorypress_init_lpost_listing($post_id);

        if (!$listing->post) {
            wp_die("Пост $post_id не найден");
        }

        $package = $listing->package;
        if (!$package) {
            $packages = $directorypress_object->packages->packages_array;
            $package = reset($packages);
            $listing->package = $package;
        }

        // -------------------------------------------------------
        // 3. Вызываем DirectoryPress save_locations
        // -------------------------------------------------------
        $errors = [];
        $validation_results = $directorypress_object->locations_handler->validate_locations($package, $errors);

        if ($validation_results) {
            $directorypress_object->locations_handler->save_locations($package, $post_id, $validation_results);
        }

        // -------------------------------------------------------
        // 4. Обновляем _listing_status
        // -------------------------------------------------------
        if (!get_post_meta($post_id, '_listing_created', true)) {
            add_post_meta($post_id, '_listing_created', true);
        }
        if (!get_post_meta($post_id, '_order_date', true)) {
            add_post_meta($post_id, '_order_date', time());
        }
        update_post_meta($post_id, '_listing_status', 'active|active');
        if (!metadata_exists('post', $post_id, '_listing_status')) {
            add_post_meta($post_id, '_listing_status', 'active');
        }

        // -------------------------------------------------------
        // 5. ОЧИСТКА ВСЕХ КЭШЕЙ
        // -------------------------------------------------------

        // a) WordPress post cache
        clean_post_cache($post_id);
        wp_cache_delete($post_id, 'posts');
        wp_cache_delete($post_id, 'post_meta');

        // b) Term cache
        $term_taxonomy_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT term_taxonomy_id FROM {$wpdb->term_relationships} WHERE object_id = %d",
            $post_id
        ));
        if ($term_taxonomy_ids) {
            wp_update_term_count_now($term_taxonomy_ids, 'directorypress-location');
            wp_update_term_count_now($term_taxonomy_ids, 'directorypress-category');
        }
        clean_term_cache([$term_id], 'directorypress-location');
        clean_object_term_cache($post_id, 'dp_listing');

        // c) WordPress object cache
        wp_cache_delete($post_id, 'directorypress_listings');
        wp_cache_delete($post_id, 'directorypress_listing');
        wp_cache_delete($post_id, 'dp_listing');
        wp_cache_delete('last_changed', 'posts');
        wp_cache_delete('last_changed', 'terms');
        wp_cache_delete($post_id, 'object_term_cache');

        // d) Transients DirectoryPress
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%transient%directorypress%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%transient%dp_listing%'");

        // e) Кэши популярных плагинов кэширования
        if (function_exists('wp_cache_clear_cache'))
            wp_cache_clear_cache();
        if (function_exists('w3tc_flush_all'))
            w3tc_flush_all();
        if (function_exists('rocket_clean_domain'))
            rocket_clean_domain();
        if (function_exists('wpfc_clear_all_cache'))
            wpfc_clear_all_cache();
        if (class_exists('LiteSpeed_Cache_API') && method_exists('LiteSpeed_Cache_API', 'purge_all')) {
            LiteSpeed_Cache_API::purge_all();
        }
        if (function_exists('litespeed_purge_all'))
            litespeed_purge_all();

        // f) Обновляем post_modified
        $wpdb->update(
            $wpdb->posts,
            [
                'post_modified' => current_time('mysql'),
                'post_modified_gmt' => current_time('mysql', 1),
            ],
            ['ID' => $post_id],
            ['%s', '%s'],
            ['%d']
        );
        clean_post_cache($post_id);

    } // end if ($do_process)

    // -------------------------------------------------------
    // 6. СБОР ДАННЫХ ДЛЯ СРАВНЕНИЯ (всегда выполняется)
    // -------------------------------------------------------
    $table_lr = $wpdb->prefix . 'directorypress_locations_relation';
    $table_pr = $wpdb->prefix . 'directorypress_packages_relation';

    function collect_post_data($pid, $wpdb, $table_lr, $table_pr) {
        $data = [];
        $post = get_post($pid);
        $data['post_status'] = $post ? $post->post_status : 'NOT FOUND';
        $data['post_type'] = $post ? $post->post_type : '';
        $data['post_modified'] = $post ? $post->post_modified : '';

        $data['locations_relation'] = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM {$table_lr} WHERE post_id = %d", $pid), ARRAY_A
        );
        $data['packages_relation'] = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM {$table_pr} WHERE post_id = %d", $pid), ARRAY_A
        );

        $data['tax_location'] = wp_get_object_terms($pid, 'directorypress-location', ['fields' => 'all']);
        $data['tax_category'] = wp_get_object_terms($pid, 'directorypress-category', ['fields' => 'all']);

        $all_meta = get_post_meta($pid);
        $dp_keys = [
            '_listing_status', '_listing_created', '_order_date', '_directory_id',
            '_location_id', '_address_line_1', '_address_line_2',
            '_zip_or_postal_index', '_additional_info',
            '_manual_coords', '_map_coords_1', '_map_coords_2', '_map_zoom',
            '_map_icon_file', '_expiration_date',
            '_attached_image', '_attached_image_as_logo', '_thumbnail_id',
            '_contact_email', '_notice_to_admin',
            'directorypress-location', '_wp_page_template',
        ];
        $data['meta'] = [];
        foreach ($dp_keys as $key) {
            $data['meta'][$key] = isset($all_meta[$key]) ? $all_meta[$key] : '--- ОТСУТСТВУЕТ ---';
        }
        $data['all_meta_keys'] = array_keys($all_meta);
        sort($data['all_meta_keys']);

        // Сохраняем все значения meta для показа в секции "Все meta ключи"
        $data['all_meta_values'] = $all_meta;

        return $data;
    }

    $bad_data = collect_post_data($post_id, $wpdb, $table_lr, $table_pr);
    $good_data = $good_id ? collect_post_data($good_id, $wpdb, $table_lr, $table_pr) : null;
    $cols = $good_data ? 4 : 2;

    // -------------------------------------------------------
    // Режим работы — определяем для отображения
    // -------------------------------------------------------
    $mode_label = $do_process ? '⚙️ ОБРАБОТКА + СРАВНЕНИЕ' : '👁️ ТОЛЬКО СРАВНЕНИЕ (без изменений)';
    $mode_color = $do_process ? '#f0883e' : '#58a6ff';

    // Формируем ссылки переключения режима
    $current_url_params = $_GET;
    $current_url_params['do_process'] = $do_process ? 0 : 1;
    $toggle_url = admin_url('?' . http_build_query($current_url_params));
    $toggle_label = $do_process ? '🔄 Переключить на: только сравнение' : '🔄 Переключить на: обработка + сравнение';

    echo '<style>
    body{font-family:monospace;padding:20px;background:#0d1117;color:#c9d1d9;font-size:13px;}
    h2{color:#58a6ff;margin-top:30px;}
    h3{color:#d2a8ff;border-bottom:1px solid #30363d;padding-bottom:5px;}
    table{border-collapse:collapse;width:100%;margin:10px 0 20px;}
    th,td{border:1px solid #30363d;padding:6px 10px;text-align:left;vertical-align:top;}
    th{background:#161b22;color:#79c0ff;}
    .diff{background:#3b1d1d;}
    .match{background:#1a2e1a;}
    pre{background:#161b22;padding:8px;border-radius:4px;overflow-x:auto;max-height:200px;margin:0;}
    .mode-badge{display:inline-block;padding:6px 14px;border-radius:6px;font-weight:bold;font-size:14px;margin-bottom:10px;}
    .toggle-btn{display:inline-block;padding:6px 14px;border-radius:6px;font-size:13px;
                text-decoration:none;color:#c9d1d9;border:1px solid #30363d;margin-left:10px;
                background:#21262d;transition:background 0.2s;}
    .toggle-btn:hover{background:#30363d;color:#fff;}
    </style>';

    echo '<h2>🔍 Сравнение постов</h2>';
    echo '<p><span class="mode-badge" style="background:' . $mode_color . '22;border:1px solid ' . $mode_color . ';color:' . $mode_color . ';">' . $mode_label . '</span>';
    echo '<a href="' . esc_url($toggle_url) . '" class="toggle-btn">' . $toggle_label . '</a></p>';

    if ($do_process) {
        echo "<p>validate_locations: " . ($validation_results ? '✅ OK' : '❌ FAIL') . "</p>";
    }

    echo '<table><tr><th>Параметр</th><th>❌ Нерабочий (ID: '.$post_id.')</th>';
    if ($good_data) echo '<th>✅ Рабочий (ID: '.$good_id.')</th><th>?</th>';
    echo '</tr>';

    // --- Основные поля ---
    foreach (['post_status', 'post_type', 'post_modified'] as $field) {
        $bv = $bad_data[$field]; $gv = $good_data ? $good_data[$field] : '';
        $m = ($bv === $gv); $c = $m ? 'match' : 'diff';
        echo "<tr class='$c'><td><b>$field</b></td><td>$bv</td>";
        if ($good_data) echo "<td>$gv</td><td>".($m?'✅':'❌')."</td>";
        echo '</tr>';
    }

    // --- Meta ---
    echo '<tr><td colspan="'.$cols.'"><h3>Post Meta</h3></td></tr>';
    foreach ($bad_data['meta'] as $key => $bv) {
        $bs = is_array($bv) ? implode(' | ', $bv) : $bv;
        $gs = ''; $m = true;
        if ($good_data) {
            $gv = $good_data['meta'][$key] ?? '--- ОТСУТСТВУЕТ ---';
            $gs = is_array($gv) ? implode(' | ', $gv) : $gv;
            $m = ($bs === $gs);
        }
        $c = $m ? 'match' : 'diff';
        echo "<tr class='$c'><td><b>$key</b></td><td><pre>".htmlspecialchars($bs)."</pre></td>";
        if ($good_data) echo "<td><pre>".htmlspecialchars($gs)."</pre></td><td>".($m?'✅':'❌')."</td>";
        echo '</tr>';
    }

    // --- locations_relation ---
    echo '<tr><td colspan="'.$cols.'"><h3>directorypress_locations_relation</h3></td></tr>';
    $blr = !empty($bad_data['locations_relation']) ? print_r($bad_data['locations_relation'], true) : 'ПУСТО';
    $glr = $good_data && !empty($good_data['locations_relation']) ? print_r($good_data['locations_relation'], true) : 'ПУСТО';
    $bc = $bad_data['locations_relation']; $gc = $good_data ? $good_data['locations_relation'] : [];
    foreach ($bc as &$r) unset($r['id']); foreach ($gc as &$r) unset($r['id']);
    $m = ($bc === $gc); $c = $m ? 'match' : 'diff';
    echo "<tr class='$c'><td><b>rows</b></td><td><pre>".htmlspecialchars($blr)."</pre></td>";
    if ($good_data) echo "<td><pre>".htmlspecialchars($glr)."</pre></td><td>".($m?'✅':'❌')."</td>";
    echo '</tr>';

    // --- packages_relation ---
    echo '<tr><td colspan="'.$cols.'"><h3>directorypress_packages_relation</h3></td></tr>';
    $bpr = !empty($bad_data['packages_relation']) ? print_r($bad_data['packages_relation'], true) : 'ПУСТО';
    $gpr = $good_data && !empty($good_data['packages_relation']) ? print_r($good_data['packages_relation'], true) : 'ПУСТО';
    $m = ($bad_data['packages_relation'] == ($good_data['packages_relation'] ?? [])); $c = $m ? 'match' : 'diff';
    echo "<tr class='$c'><td><b>rows</b></td><td><pre>".htmlspecialchars($bpr)."</pre></td>";
    if ($good_data) echo "<td><pre>".htmlspecialchars($gpr)."</pre></td><td>".($m?'✅':'❌')."</td>";
    echo '</tr>';

    // --- Taxonomy ---
    foreach (['tax_location' => 'directorypress-location', 'tax_category' => 'directorypress-category'] as $tk => $tl) {
        echo '<tr><td colspan="'.$cols.'"><h3>Taxonomy: '.$tl.'</h3></td></tr>';
        $bt = array_map(function($t){return $t->term_id.':'.$t->name;}, $bad_data[$tk]);
        $gt = $good_data ? array_map(function($t){return $t->term_id.':'.$t->name;}, $good_data[$tk]) : [];
        $m = ($bt === $gt); $c = $m ? 'match' : 'diff';
        echo "<tr class='$c'><td><b>terms</b></td><td>".implode(', ',$bt)."</td>";
        if ($good_data) echo "<td>".implode(', ',$gt)."</td><td>".($m?'✅':'❌')."</td>";
        echo '</tr>';
    }

    // --- Все ключи meta С ЗНАЧЕНИЯМИ ---
    echo '<tr><td colspan="'.$cols.'"><h3>Все meta ключи и значения</h3></td></tr>';
    $ak = array_unique(array_merge($bad_data['all_meta_keys'], $good_data ? $good_data['all_meta_keys'] : []));
    sort($ak);
    $bks = array_flip($bad_data['all_meta_keys']);
    $gks = $good_data ? array_flip($good_data['all_meta_keys']) : [];

    foreach ($ak as $key) {
        $ib = isset($bks[$key]);
        $ig = isset($gks[$key]);

        // Получаем значения
        $bval_raw = $ib && isset($bad_data['all_meta_values'][$key]) ? $bad_data['all_meta_values'][$key] : null;
        $gval_raw = $ig && $good_data && isset($good_data['all_meta_values'][$key]) ? $good_data['all_meta_values'][$key] : null;

        // Форматируем значения для отображения
        $bval_str = '';
        if ($bval_raw !== null) {
            if (is_array($bval_raw)) {
                $parts = [];
                foreach ($bval_raw as $v) {
                    $sv = is_array($v) || is_object($v) ? print_r($v, true) : (string)$v;
                    // Обрезаем слишком длинные значения
                    if (mb_strlen($sv) > 120) $sv = mb_substr($sv, 0, 120) . '…';
                    $parts[] = $sv;
                }
                $bval_str = implode(' | ', $parts);
            } else {
                $bval_str = (string)$bval_raw;
                if (mb_strlen($bval_str) > 120) $bval_str = mb_substr($bval_str, 0, 120) . '…';
            }
        }

        $gval_str = '';
        if ($gval_raw !== null) {
            if (is_array($gval_raw)) {
                $parts = [];
                foreach ($gval_raw as $v) {
                    $sv = is_array($v) || is_object($v) ? print_r($v, true) : (string)$v;
                    if (mb_strlen($sv) > 120) $sv = mb_substr($sv, 0, 120) . '…';
                    $parts[] = $sv;
                }
                $gval_str = implode(' | ', $parts);
            } else {
                $gval_str = (string)$gval_raw;
                if (mb_strlen($gval_str) > 120) $gval_str = mb_substr($gval_str, 0, 120) . '…';
            }
        }

        // Статусы наличия
        $ib_icon = $ib ? '✅' : '❌';
        $ig_icon = $ig ? '✅' : '❌';

        // Сравниваем значения (не только наличие)
        $values_match = ($bval_raw == $gval_raw);
        $presence_match = ($ib === $ig);
        $full_match = $presence_match && $values_match;
        $c = $full_match ? 'match' : 'diff';

        // Формируем вывод: имя ключа + значение
        $bad_cell = $ib_icon . ' <pre style="display:inline;background:transparent;padding:2px 4px;">' . htmlspecialchars($bval_str ?: '—') . '</pre>';
        $good_cell = $ig_icon . ' <pre style="display:inline;background:transparent;padding:2px 4px;">' . htmlspecialchars($gval_str ?: '—') . '</pre>';

        echo "<tr class='$c'><td><b>$key</b></td><td>$bad_cell</td>";
        if ($good_data) echo "<td>$good_cell</td><td>".($full_match?'✅':'❌')."</td>";
        echo '</tr>';
    }

    echo '</table>';
    exit;
});



add_action('init', function () {

    // 🔒 защита: запускается только по URL параметру
    if (!isset($_GET['add_attached_image']) || $_GET['add_attached_image'] != '1') {
        return;
    }

    // путь к CSV
    $csv_file = __DIR__ . '/listings-export-2026-april-25-1028.csv';

    if (!file_exists($csv_file)) {
        die('CSV файл не найден');
    }

    if (($handle = fopen($csv_file, 'r')) === false) {
        die('Не удалось открыть CSV');
    }

    $row = 0;

    while (($data = fgetcsv($handle, 0, ',')) !== false) {

        // пропуск заголовка
        if ($row === 0) {
            $row++;
            continue;
        }

        $post_id = intval($data[0]);

        if (!$post_id) {
            continue;
        }

        // получаем старые изображения (если нужно НЕ затирать)
        $existing = get_post_meta($post_id, '_attached_image', true);

        var_dump($existing);

        // добавляем новое изображение
        $existing = 18752;
		delete_post_meta($post_id, '_attached_image');
        //update_post_meta($post_id, '_attached_image', $existing);
		$existing = get_post_meta($post_id, '_attached_image', true);

        var_dump($existing);
        echo "Updated post ID: {$post_id}<br>";
    }

    fclose($handle);

    exit('Done');

});

add_action('init', function () {

    // 🔒 запуск только по URL
    if (!isset($_GET['trash_posts']) || $_GET['trash_posts'] != '1') {
        return;
    }

    $csv_file = __DIR__ . '/listings-export-2026-april-25-1028.csv';

    if (!file_exists($csv_file)) {
        die('CSV файл не найден');
    }

    $handle = fopen($csv_file, 'r');

    if (!$handle) {
        die('Не удалось открыть CSV');
    }

    $row = 0;

    while (($data = fgetcsv($handle, 0, ',')) !== false) {

        // пропускаем заголовок
        if ($row === 0) {
            $row++;
            continue;
        }

        $post_id = intval($data[0]);

        if (!$post_id) {
            continue;
        }

        // перенос в корзину
        $result = wp_trash_post($post_id);

        if ($result) {
            echo "Trashed post ID: {$post_id}<br>";
        } else {
            echo "Failed: {$post_id}<br>";
        }

        $row++;
    }

    fclose($handle);

    exit('Done');

});

add_action('init', function () {


    if (!isset($_GET['reset_some_meta'])) {
        return;
    }
        $post_id = intval($_GET['reset_some_meta']);
		$post = get_post($post_id);
		/*delete_post_meta($post_id, '_manual_coords');
		delete_post_meta($post_id, '_address_line_1');
		delete_post_meta($post_id, '_address_line_2');
		delete_post_meta($post_id, '_zip_or_postal_index');
		delete_post_meta($post_id, '_additional_info');
		delete_post_meta($post_id, '_map_coords_1');
		delete_post_meta($post_id, '_map_coords_2');
		delete_post_meta($post_id, '_map_zoom');
        //update_post_meta($post_id, '_manual_coords', '');
		//update_post_meta($post_id, '_listing_status', 'active');
		delete_post_meta($post_id, '_elementor_page_assets');
		delete_post_meta($post_id, '_clicks_data');
		update_post_meta($post_id, '_elementor_page_assets', []);
		update_post_meta($post_id, '_clicks_data', [0 => '', date('n-Y') => 4]);
		update_post_meta($post_id, '_total_clicks', 2);
        echo "Updated post ID: {$post_id}<br>";*/
		echo '<pre>';
			print_r([
				'ID' => $post_id,
				'type' => $post->post_type,
				'title' => $post->post_title,
				'meta' => get_post_meta($post_id),
				'taxonomies' => get_object_taxonomies($post->post_type),
				'terms' => wp_get_post_terms($post_id, get_object_taxonomies($post->post_type)),
			]);
		echo '</pre>';
    exit('Done');

});

add_action('init', function () {
    if (!is_admin() || empty($_GET['show_cat_limit']))
        return;
    if (!current_user_can('manage_options'))
        wp_die('Нет доступа.');

    global $wpdb;
    $results = $wpdb->get_results(
        "SELECT id, name, category_number_allowed FROM {$wpdb->prefix}directorypress_packages ORDER BY id"
    );

    echo '<pre style="background:#0d1117;color:#c9d1d9;padding:20px;font-family:monospace;">';
    echo "📦 DirectoryPress Packages — Category Limit\n\n";
    foreach ($results as $row) {
        echo "ID: {$row->id} | Name: {$row->name} | Max categories: {$row->category_number_allowed}\n";
    }
    echo '</pre>';
    exit;
});

 
add_action('pmxi_saved_post', 'dpfix_after_import', 10, 1);

function dpfix_after_import($post_id)
{
    if (get_post_type($post_id) !== 'dp_listing')
        return;

    global $wpdb;
    $tax = 'directorypress-location';

    error_log("DPFIX v4: === СТАРТ post_id=$post_id ===");


    $raw = trim(get_post_meta($post_id, '_location_chain', true));
    error_log("DPFIX v4: _location_chain='$raw'");

    $names = array();
    if (!empty($raw)) {
        if (strpos($raw, '|') !== false) {
            $names = explode('|', $raw);
        } elseif (strpos($raw, '>') !== false) {
            $names = explode('>', $raw);
        } elseif (strpos($raw, ',') !== false) {
            $names = explode(',', $raw);
        } else {
            $names = array($raw);
        }
        $names = array_map('trim', $names);
        $names = array_values(array_filter($names));
    }

    error_log("DPFIX v4: names=" . implode(', ', $names));

    $term_ids = array();
    $parent_id = 0;

    foreach ($names as $i => $name) {
        if (empty($name))
            continue;

        $found_term = null;


        $args = array(
            'taxonomy' => $tax,
            'hide_empty' => false,
            'parent' => $parent_id,
        );
        $children = get_terms($args);

        if (!is_wp_error($children) && !empty($children)) {
            foreach ($children as $child) {
                if (mb_strtolower(trim($child->name)) === mb_strtolower(trim($name))) {
                    $found_term = $child;
                    error_log("DPFIX v4: [$i] '$name' найден среди детей parent=$parent_id → term_id={$child->term_id}");
                    break;
                }
            }
        }

    
        if (!$found_term) {
            $global = get_term_by('name', $name, $tax);
            if ($global && !is_wp_error($global)) {
                $found_term = $global;
                error_log("DPFIX v4: [$i] '$name' найден глобально → term_id={$global->term_id} (parent={$global->parent})");
            }
        }

        if (!$found_term) {
            $by_slug = get_term_by('slug', sanitize_title($name), $tax);
            if ($by_slug && !is_wp_error($by_slug)) {
                $found_term = $by_slug;
                error_log("DPFIX v4: [$i] '$name' найден по slug → term_id={$by_slug->term_id}");
            }
        }

    
        if (!$found_term) {
            error_log("DPFIX v4: [$i] '$name' НЕ найден, создаём (parent=$parent_id)...");

            $result = wp_insert_term($name, $tax, array(
                'parent' => $parent_id,
                'slug' => sanitize_title($name),
            ));

            if (is_wp_error($result)) {
              
                $err_data = $result->get_error_data('term_exists');
                if ($err_data) {
                    $found_term = get_term(intval($err_data), $tax);
                    error_log("DPFIX v4: [$i] '$name' уже существует → term_id=" . intval($err_data));
                } else {
                    error_log("DPFIX v4: [$i] ОШИБКА создания '$name': " . $result->get_error_message());
                    continue;
                }
            } else {
                $found_term = get_term($result['term_id'], $tax);
                error_log("DPFIX v4: [$i] '$name' СОЗДАН → term_id={$result['term_id']}");
            }
        }

        if ($found_term && !is_wp_error($found_term)) {
            $term_ids[] = $found_term->term_id;
            $parent_id = $found_term->term_id;
        }
    }

    error_log("DPFIX v4: term_ids=[" . implode(', ', $term_ids) . "]");

    if (empty($term_ids)) {
        $term_ids = array(461);
        error_log("DPFIX v4: фолбэк на дефолт [461]");
    }

    $leaf = end($term_ids);
    error_log("DPFIX v4: leaf=$leaf");

    $table = $wpdb->prefix . 'directorypress_locations_relation';
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$table} WHERE post_id = %d",
        $post_id
    ));

    if ($exists) {
        $wpdb->update($table, array('location_id' => $leaf), array('post_id' => $post_id));
        error_log("DPFIX v4: locations_relation ОБНОВЛЕНА (id=$exists, location_id=$leaf)");
    } else {
        $wpdb->insert($table, array(
            'post_id' => $post_id,
            'location_id' => $leaf,
            'address_line_1' => '',
            'address_line_2' => '',
            'zip_or_postal_index' => '',
            'additional_info' => '',
            'manual_coords' => 0,
            'map_coords_1' => 0.000000,
            'map_coords_2' => 0.000000,
            'map_icon_file' => '',
        ));
        error_log("DPFIX v4: locations_relation СОЗДАНА (location_id=$leaf)");
    }


    $res = wp_set_object_terms($post_id, $term_ids, $tax);


    if (!get_post_meta($post_id, '_order_date', true)) {
        add_post_meta($post_id, '_order_date', time());
    }
    if (!get_post_meta($post_id, '_listing_created', true)) {
        add_post_meta($post_id, '_listing_created', true);
    }
    if (!get_post_meta($post_id, '_listing_status', true)) {
        add_post_meta($post_id, '_listing_status', 'active');
    }

    update_post_meta($post_id, '_location_id', $leaf);
  

    clean_term_cache($term_ids, $tax);
    clean_object_term_cache($post_id, 'dp_listing');

   
}

add_action('wp_head', function() {
    echo '<script>var odd_even_label = 2;</script>';
}, 1);

function classiadspro_output_pwa_head_tags() {
	$canonical_url = 'https://adshelppro.com';
	$manifest_url = $canonical_url . '/firebase-push.webmanifest';
	$site_icon_url = get_site_icon_url(512);
	if (!$site_icon_url) {
		$site_icon_url = $canonical_url . '/wp-content/uploads/2025/05/cropped-cropped-logo-1.png';
	}
	?>
	<link rel="manifest" href="<?php echo esc_url($manifest_url); ?>">
	<meta name="theme-color" content="#191a1f">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<meta name="apple-mobile-web-app-title" content="AdsHelpPro">
	<link rel="apple-touch-icon" href="<?php echo esc_url($site_icon_url); ?>">
	<?php
}
add_action('wp_head', 'classiadspro_output_pwa_head_tags', 2);

function classiadspro_output_ios_push_hint() {
	if (is_admin()) {
		return;
	}
	$current_user = wp_get_current_user();
	$user_id = $current_user instanceof WP_User ? (int) $current_user->ID : 0;
	?>
	<script>
	(function () {
	  var ua = window.navigator.userAgent || "";
	  var isIOS = /iPad|iPhone|iPod/.test(ua) && !window.MSStream;
	  var isStandalone = window.navigator.standalone === true || (
	    window.matchMedia && window.matchMedia("(display-mode: standalone)").matches
	  );
	  var isServerLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
	  var isLoggedIn = document.body && document.body.classList.contains("logged-in");
	  var hasLoggedInCookie = document.cookie.indexOf("wordpress_logged_in_") !== -1;
	  var storageKey = "classiadspro_ios_push_hint_dismissed_user_<?php echo (int) $user_id; ?>_v5";

	  if (!isIOS || isStandalone || (!isServerLoggedIn && !isLoggedIn && !hasLoggedInCookie)) {
	    return;
	  }

	  try {
	    if (window.localStorage.getItem(storageKey) === "1") {
	      return;
	    }
	  } catch (error) {}

	  function showHint() {
	    window.alert(
	      "\u0423\u0432\u0435\u0434\u043e\u043c\u043b\u0435\u043d\u0438\u044f \u043d\u0430 iPhone\n\n" +
	      "\u0427\u0442\u043e\u0431\u044b \u0432\u043a\u043b\u044e\u0447\u0438\u0442\u044c push-\u0443\u0432\u0435\u0434\u043e\u043c\u043b\u0435\u043d\u0438\u044f, \u0434\u043e\u0431\u0430\u0432\u044c\u0442\u0435 \u0441\u0430\u0439\u0442 \u043d\u0430 \u044d\u043a\u0440\u0430\u043d \u0414\u043e\u043c\u043e\u0439 \u0447\u0435\u0440\u0435\u0437 Share -> Add to Home Screen.\n\n" +
	      "\u041f\u043e\u0441\u043b\u0435 \u044d\u0442\u043e\u0433\u043e \u043e\u0442\u043a\u0440\u043e\u0439\u0442\u0435 \u0441\u0430\u0439\u0442 \u0441 \u0438\u043a\u043e\u043d\u043a\u0438 \u0438 \u0432\u043e\u0439\u0434\u0438\u0442\u0435 \u0441\u043d\u043e\u0432\u0430."
	    );

	    try {
	      window.localStorage.setItem(storageKey, "1");
	    } catch (error) {}
	  }

	  if (document.readyState === "loading") {
	    document.addEventListener("DOMContentLoaded", showHint);
	  } else {
	    showHint();
	  }
	})();
	</script>
	<?php
}
add_action('wp_footer', 'classiadspro_output_ios_push_hint', 99);

add_filter('nonce_life', function() {
    return 12 * HOUR_IN_SECONDS;
});

function classiadspro_refresh_directorypress_ajax_nonce() {
    if (empty($_POST['action']) || $_POST['action'] !== 'directorypress_handler_request') {
        return;
    }

    $nonce = isset($_POST['dp_ajax_nonce']) ? sanitize_text_field(wp_unslash($_POST['dp_ajax_nonce'])) : '';

    if (!wp_verify_nonce($nonce, 'directorypress-ajax-nonce')) {
        $_POST['dp_ajax_nonce'] = wp_create_nonce('directorypress-ajax-nonce');
    }
}
add_action('wp_ajax_directorypress_handler_request', 'classiadspro_refresh_directorypress_ajax_nonce', 1);
add_action('wp_ajax_nopriv_directorypress_handler_request', 'classiadspro_refresh_directorypress_ajax_nonce', 1);

function classiadspro_get_directorypress_category_term_for_seo() {
    if (!function_exists('directorypress_get_term_by_path')) {
        return null;
    }

    $category_path = get_query_var('category-directorypress');
    if (!$category_path) {
        return null;
    }

    $term = directorypress_get_term_by_path($category_path);
    if (!$term || is_wp_error($term) || empty($term->term_id) || empty($term->taxonomy)) {
        return null;
    }

    return $term;
}

function classiadspro_get_directorypress_category_yoast_value($term, $type = 'title') {
    if (!$term || !class_exists('WPSEO_Replace_Vars')) {
        return '';
    }

    $replace_vars = new WPSEO_Replace_Vars();

    if ($type === 'description') {
        $custom_value = class_exists('WPSEO_Taxonomy_Meta')
            ? WPSEO_Taxonomy_Meta::get_term_meta($term->term_id, $term->taxonomy, 'desc')
            : '';

        if ($custom_value === '' && class_exists('WPSEO_Options')) {
            $custom_value = WPSEO_Options::get('metadesc-tax-' . $term->taxonomy);
        }

        if ($custom_value === '') {
            $term_description = term_description($term->term_id, $term->taxonomy);
            $term_description = wp_strip_all_tags($term_description);
            $term_description = trim(preg_replace('/\s+/', ' ', $term_description));

            $custom_value = $term_description;
        }
    } else {
        $custom_value = class_exists('WPSEO_Taxonomy_Meta')
            ? WPSEO_Taxonomy_Meta::get_term_meta($term->term_id, $term->taxonomy, 'title')
            : '';

        if ($custom_value === '' && class_exists('WPSEO_Options')) {
            $custom_value = WPSEO_Options::get('title-tax-' . $term->taxonomy);

            if ($custom_value === '') {
                $custom_value = WPSEO_Options::get_title_default('title-tax-' . $term->taxonomy);
            }
        }

        if ($custom_value === '') {
            $custom_value = $term->name;
        }
    }

    if ($custom_value === '') {
        return '';
    }

    return $replace_vars->replace($custom_value, array(
        'name' => $term->name,
        'term_id' => $term->term_id,
        'taxonomy' => $term->taxonomy,
    ));
}

function classiadspro_directorypress_category_wpseo_title($title) {
    $term = classiadspro_get_directorypress_category_term_for_seo();
    if (!$term) {
        return $title;
    }

    $yoast_title = classiadspro_get_directorypress_category_yoast_value($term, 'title');

    return $yoast_title !== '' ? $yoast_title : $title;
}
add_filter('wpseo_title', 'classiadspro_directorypress_category_wpseo_title', 20);

function classiadspro_directorypress_category_wpseo_metadesc($description) {
    $term = classiadspro_get_directorypress_category_term_for_seo();
    if (!$term) {
        return $description;
    }

    $yoast_description = classiadspro_get_directorypress_category_yoast_value($term, 'description');

    return $yoast_description !== '' ? $yoast_description : $description;
}
add_filter('wpseo_metadesc', 'classiadspro_directorypress_category_wpseo_metadesc', 20);

function classiadspro_translatepress_translate_string($value, $allow_html = false) {
	if (!is_string($value) || $value === '' || is_admin()) {
		return $value;
	}

	static $translation_cache = [];

	$language_suffix = classiadspro_translatepress_get_current_language_suffix();
	$cache_key = $language_suffix . '|' . ($allow_html ? 'html' : 'text') . '|' . md5($value);

	if (array_key_exists($cache_key, $translation_cache)) {
		return $translation_cache[$cache_key];
	}

	$forced_translation = classiadspro_translatepress_get_forced_string_translation($value);
	$translated = is_string($forced_translation) && $forced_translation !== '' ? $forced_translation : $value;

	if ($translated === $value && function_exists('trp_translate')) {
		static $is_translating = false;
		if (!$is_translating) {
			$is_translating = true;
			$trp_translation = trp_translate($value, null, false);
			$is_translating = false;

			if (is_string($trp_translation) && $trp_translation !== '') {
				$translated = $trp_translation;
			}
		}
	}

	if ($translated === $value) {
		$dictionary_translation = classiadspro_translatepress_lookup_listing_dictionary_translation($value);
		if (is_string($dictionary_translation) && $dictionary_translation !== '') {
			$translated = $dictionary_translation;
		}
	}

	$translation_cache[$cache_key] = $allow_html ? $translated : wp_strip_all_tags($translated);

	return $translation_cache[$cache_key];
}

function classiadspro_translatepress_get_forced_string_translation($value) {
	if (!is_string($value) || $value === '') {
		return null;
	}

	$language_suffix = classiadspro_translatepress_get_current_language_suffix();
	$normalized_value = trim($value);

	$overrides = [
		'ru' => [
			'Price' => 'Цена',
			'price' => 'цена',
		],
		'uk' => [
			'Price' => 'Ціна',
			'price' => 'ціна',
		],
	];

	foreach ($overrides as $language_prefix => $translations) {
		if (!str_starts_with($language_suffix, $language_prefix)) {
			continue;
		}

		if (isset($translations[$normalized_value])) {
			return $translations[$normalized_value];
		}
	}

	return null;
}

function classiadspro_translatepress_get_forced_directorypress_field_label($field) {
	if (!is_object($field) || empty($field->type) || !is_string($field->type)) {
		return null;
	}

	$field_slug = '';
	if (!empty($field->slug) && is_string($field->slug)) {
		$field_slug = strtolower(trim($field->slug));
	}

	if ($field->type !== 'price' && $field_slug !== 'price') {
		return null;
	}

	$language_suffix = classiadspro_translatepress_get_current_language_suffix();

	if (str_starts_with($language_suffix, 'ru')) {
		return 'Цена';
	}

	if (str_starts_with($language_suffix, 'uk')) {
		return 'Ціна';
	}

	return 'Price';
}

function classiadspro_translatepress_translate_listing_string($value, $allow_html = false) {
	if (!classiadspro_is_directorypress_listing_request()) {
		return $value;
	}

	return classiadspro_translatepress_translate_string($value, $allow_html);
}

function classiadspro_is_directorypress_listing_request() {
	$post = get_post();
	if ($post && $post->post_type === 'dp_listing') {
		return true;
	}

	if (function_exists('directorypress_is_listing_page') && directorypress_is_listing_page()) {
		return true;
	}

	return classiadspro_get_current_directorypress_listing_post_id() > 0;
}

function classiadspro_prepare_directorypress_listing_shortcode_context() {
	if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
		return;
	}

	$listing_post_id = classiadspro_get_current_directorypress_listing_post_id();
	if ($listing_post_id <= 0) {
		return;
	}

	$current_post = get_post();
	if ($current_post instanceof WP_Post && $current_post->post_type === 'dp_listing') {
		return;
	}

	global $directorypress_object, $wp_query;

	$listing_post = get_post($listing_post_id);
	if ($listing_post instanceof WP_Post) {
		set_query_var('listing-directorypress', $listing_post->post_name);

		if ($wp_query instanceof WP_Query) {
			$wp_query->set('listing-directorypress', $listing_post->post_name);
		}
	}

	if (!function_exists('directorypress_get_listing')) {
		return;
	}

	$listing = directorypress_get_listing($listing_post_id);
	if (!is_object($listing)) {
		return;
	}

	if (is_object($directorypress_object)) {
		$directorypress_object->current_listing = $listing;

		if (empty($directorypress_object->current_directorytype) && !empty($listing->directorytype)) {
			$directorypress_object->current_directorytype = $listing->directorytype;
		}

		if (empty($directorypress_object->current_directorytype) && method_exists($directorypress_object, 'setup_current_page_directorytype')) {
			$directorypress_object->setup_current_page_directorytype(!empty($listing->directorytype) ? $listing->directorytype : null);
		}
	}
}
add_action('wp', 'classiadspro_prepare_directorypress_listing_shortcode_context', 20);

function classiadspro_render_directorypress_listing_shortcode_content($content) {
	if (is_admin() || !classiadspro_is_directorypress_listing_request()) {
		return $content;
	}

	$post = get_post();
	if (!($post instanceof WP_Post) || $post->post_type !== 'page') {
		return $content;
	}

	if (
		!has_shortcode($post->post_content, 'directorypress-listing') &&
		(!defined('DIRECTORYPRESS_LISTING_SHORTCODE') || !has_shortcode($post->post_content, DIRECTORYPRESS_LISTING_SHORTCODE))
	) {
		return $content;
	}

	if (
		!is_string($content) ||
		strpos($content, 'directorypress-single-content-area') !== false
	) {
		return $content;
	}

	$listing_post_id = classiadspro_get_current_directorypress_listing_post_id();
	if ($listing_post_id <= 0) {
		return $content;
	}

	static $is_rendering_listing_shortcode = false;
	if ($is_rendering_listing_shortcode) {
		return $content;
	}

	$is_rendering_listing_shortcode = true;
	$listing_html = do_shortcode('[directorypress-listing listing_id="' . (int) $listing_post_id . '"]');
	$is_rendering_listing_shortcode = false;

	if (!is_string($listing_html) || trim($listing_html) === '') {
		return $content;
	}

	return $content . $listing_html;
}
add_filter('the_content', 'classiadspro_render_directorypress_listing_shortcode_content', 999);

function classiadspro_translatepress_get_current_language_suffix() {
	static $language_suffix = null;

	if (is_string($language_suffix) && $language_suffix !== '') {
		return $language_suffix;
	}

	$language = '';
	$trp_settings = get_option('trp_settings', array());

	if (!empty($GLOBALS['TRP_LANGUAGE']) && is_string($GLOBALS['TRP_LANGUAGE'])) {
		$language = $GLOBALS['TRP_LANGUAGE'];
	}

	if ($language === '' && !empty($trp_settings['url-slugs']) && is_array($trp_settings['url-slugs'])) {
		$request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
		$request_path = trim((string) wp_parse_url($request_uri, PHP_URL_PATH), '/');
		$path_segments = $request_path !== '' ? explode('/', $request_path) : array();
		$first_segment = !empty($path_segments[0]) ? sanitize_title_for_query($path_segments[0]) : '';

		if ($first_segment !== '') {
			foreach ($trp_settings['url-slugs'] as $locale_code => $slug) {
				if (!is_string($locale_code) || !is_string($slug)) {
					continue;
				}

				if (sanitize_title_for_query($slug) === $first_segment) {
					$language = $locale_code;
					break;
				}
			}
		}
	}

	if (
		$language === '' &&
		!empty($trp_settings['default-language']) &&
		(
			empty($trp_settings['add-subdirectory-to-default-language']) ||
			$trp_settings['add-subdirectory-to-default-language'] !== 'yes'
		)
	) {
		$language = $trp_settings['default-language'];
	}

	if ($language === '') {
		if (function_exists('determine_locale')) {
			$language = determine_locale();
		} else {
			$language = get_locale();
		}
	}

	$language = strtolower(str_replace('-', '_', trim((string) $language)));
	$language_suffix = $language !== '' ? $language : 'en_us';

	return $language_suffix;
}

function classiadspro_translatepress_get_dictionary_tables_for_language($language_suffix) {
	global $wpdb;

	static $tables_by_language = [];

	if (isset($tables_by_language[$language_suffix])) {
		return $tables_by_language[$language_suffix];
	}

	$like = $wpdb->esc_like($wpdb->prefix . 'trp_dictionary_') . '%_' . $wpdb->esc_like($language_suffix);
	$tables = $wpdb->get_col($wpdb->prepare('SHOW TABLES LIKE %s', $like));

	$tables_by_language[$language_suffix] = is_array($tables) ? $tables : [];

	return $tables_by_language[$language_suffix];
}

function classiadspro_translatepress_lookup_listing_dictionary_translation($original) {
	global $wpdb;

	if (!is_string($original) || $original === '') {
		return null;
	}

	$language_suffix = classiadspro_translatepress_get_current_language_suffix();
	static $translation_cache = [];
	$cache_key = $language_suffix . '|' . md5($original);

	if (array_key_exists($cache_key, $translation_cache)) {
		return $translation_cache[$cache_key];
	}

	$tables = classiadspro_translatepress_get_dictionary_tables_for_language($language_suffix);

	if (empty($tables)) {
		$translation_cache[$cache_key] = null;
		return null;
	}

	foreach ($tables as $table_name) {
		$translation = $wpdb->get_var($wpdb->prepare(
			"SELECT translated
			FROM {$table_name}
			WHERE status = 1
				AND original = %s
				AND translated <> ''
				AND translated <> original
			ORDER BY id DESC
			LIMIT 1",
			$original
		));

		if (is_string($translation) && $translation !== '' && $translation !== $original) {
			$translation_cache[$cache_key] = $translation;
			return $translation;
		}
	}

	$translation_cache[$cache_key] = null;
	return null;
}

function classiadspro_translatepress_translate_listing_title($title, $listing = null) {
	if (classiadspro_is_translatepress_editor_request()) {
		return $title;
	}

	$post_id = 0;
	if (is_object($listing) && isset($listing->post->ID)) {
		$post_id = (int) $listing->post->ID;
	} elseif (get_post_type() === 'dp_listing') {
		$post_id = (int) get_the_ID();
	} else {
		$post_id = classiadspro_get_current_directorypress_listing_post_id();
	}

	if ($post_id > 0) {
		$saved_translation = classiadspro_get_dp_listing_translation_value($post_id, array('title'));
		if ($saved_translation !== '') {
			return $saved_translation;
		}
	}

	$translated_title = classiadspro_translatepress_translate_listing_string($title, false);

	if (is_object($listing) && isset($listing->post->ID) && $translated_title === $title) {
		$fallback_title = classiadspro_translatepress_lookup_listing_title_by_post_id((int) $listing->post->ID, $title);
		if (is_string($fallback_title) && $fallback_title !== '') {
			return $fallback_title;
		}
	}

	return $translated_title;
}
add_filter('directorypress_post_title', 'classiadspro_translatepress_translate_listing_title', 10, 2);

function classiadspro_translatepress_lookup_listing_title_by_post_id($post_id, $current_title) {
	global $wpdb;

	$post_id = (int) $post_id;
	if ($post_id <= 0 || !is_string($current_title) || $current_title === '') {
		return null;
	}

	$language_suffix = classiadspro_translatepress_get_current_language_suffix();
	static $title_cache = [];
	$cache_key = $language_suffix . '|' . $post_id;

	if (array_key_exists($cache_key, $title_cache)) {
		$cached_candidates = $title_cache[$cache_key];

		if (!empty($cached_candidates['preferred'])) {
			foreach ($cached_candidates['preferred'] as $candidate) {
				if ($candidate !== $current_title) {
					return $candidate;
				}
			}
		}

		if (!empty($cached_candidates['fallback'])) {
			foreach ($cached_candidates['fallback'] as $candidate) {
				if ($candidate !== $current_title) {
					return $candidate;
				}
			}
		}

		return null;
	}

	$tables = classiadspro_translatepress_get_dictionary_tables_for_language($language_suffix);
	if (empty($tables)) {
		$title_cache[$cache_key] = [
			'preferred' => [],
			'fallback' => [],
		];
		return null;
	}

	$preferred_candidates = [];
	$fallback_candidates = [];
	$needle = '%data-trp-post-id%' . $wpdb->esc_like((string) $post_id) . '%';

	foreach ($tables as $table_name) {
		$rows = $wpdb->get_results($wpdb->prepare(
			"SELECT original, translated
			FROM {$table_name}
			WHERE status = 1
				AND (original LIKE %s OR translated LIKE %s)
			ORDER BY id DESC
			LIMIT 20",
			$needle,
			$needle
		), ARRAY_A);

		if (!is_array($rows) || empty($rows)) {
			continue;
		}

		foreach ($rows as $row) {
			foreach (['original', 'translated'] as $column_name) {
				if (empty($row[$column_name]) || !is_string($row[$column_name])) {
					continue;
				}

				$candidate = html_entity_decode($row[$column_name], ENT_QUOTES | ENT_HTML5, 'UTF-8');
				$candidate = trim(wp_strip_all_tags($candidate));

				if ($candidate === '' || $candidate === $current_title) {
					continue;
				}

				if (classiadspro_translatepress_title_matches_language_script($candidate, $language_suffix)) {
					$preferred_candidates[$candidate] = $candidate;
				} else {
					$fallback_candidates[$candidate] = $candidate;
				}
			}
		}
	}

	$title_cache[$cache_key] = [
		'preferred' => array_values($preferred_candidates),
		'fallback' => array_values($fallback_candidates),
	];

	if (!empty($title_cache[$cache_key]['preferred'])) {
		foreach ($title_cache[$cache_key]['preferred'] as $candidate) {
			if ($candidate !== $current_title) {
				return $candidate;
			}
		}
	}

	if (!empty($title_cache[$cache_key]['fallback'])) {
		foreach ($title_cache[$cache_key]['fallback'] as $candidate) {
			if ($candidate !== $current_title) {
				return $candidate;
			}
		}
	}

	return null;
}

function classiadspro_translatepress_title_matches_language_script($value, $language_suffix) {
	if (!is_string($value) || $value === '') {
		return false;
	}

	if (str_starts_with($language_suffix, 'ru') || str_starts_with($language_suffix, 'uk')) {
		return (bool) preg_match('/\p{Cyrillic}/u', $value);
	}

	return false;
}

function classiadspro_translatepress_localize_directorypress_field_definitions() {
	if (is_admin() || classiadspro_is_translatepress_editor_request() || !classiadspro_is_directorypress_listing_request()) {
		return;
	}

	global $directorypress_object;

	if (
		!is_object($directorypress_object) ||
		!isset($directorypress_object->fields) ||
		!is_object($directorypress_object->fields)
	) {
		return;
	}

	static $did_translate = false;
	if ($did_translate) {
		return;
	}
	$did_translate = true;

	if (!empty($directorypress_object->fields->fields_array) && is_array($directorypress_object->fields->fields_array)) {
		foreach ($directorypress_object->fields->fields_array as $field) {
			if (!is_object($field)) {
				continue;
			}

			$forced_field_label = classiadspro_translatepress_get_forced_directorypress_field_label($field);
			if (is_string($forced_field_label) && $forced_field_label !== '') {
				$field->name = $forced_field_label;

				if (!empty($field->field_search_label) && is_string($field->field_search_label)) {
					$field->field_search_label = $forced_field_label;
				}

				continue;
			}

			if (!empty($field->name) && is_string($field->name)) {
				$field->name = classiadspro_translatepress_translate_listing_string($field->name, false);
			}

			if (!empty($field->field_search_label) && is_string($field->field_search_label)) {
				$field->field_search_label = classiadspro_translatepress_translate_listing_string($field->field_search_label, false);
			}

			if (!empty($field->description) && is_string($field->description)) {
				$field->description = classiadspro_translatepress_translate_listing_string($field->description, false);
			}

			if (!empty($field->selection_items) && is_array($field->selection_items)) {
				foreach ($field->selection_items as $selection_key => $selection_item) {
					if (!is_string($selection_item) || $selection_item === '') {
						continue;
					}

					$field->selection_items[$selection_key] = classiadspro_translatepress_translate_listing_string($selection_item, false);
				}
			}
		}
	}

	if (!empty($directorypress_object->fields->fields_groups_array) && is_array($directorypress_object->fields->fields_groups_array)) {
		foreach ($directorypress_object->fields->fields_groups_array as $fields_group) {
			if (!is_object($fields_group) || empty($fields_group->name) || !is_string($fields_group->name)) {
				continue;
			}

			$fields_group->name = classiadspro_translatepress_translate_listing_string($fields_group->name, false);
		}
	}
}
add_action('wp', 'classiadspro_translatepress_localize_directorypress_field_definitions', 20);

function classiadspro_translatepress_translate_term_name($term_name, $taxonomy) {
	if (!is_string($term_name) || $term_name === '' || is_admin() || classiadspro_is_translatepress_editor_request()) {
		return $term_name;
	}

	if (!in_array($taxonomy, array('directorypress-category'), true)) {
		return $term_name;
	}

	static $term_translation_cache = [];
	$language_suffix = classiadspro_translatepress_get_current_language_suffix();
	$cache_key = $language_suffix . '|' . $taxonomy . '|' . md5($term_name);

	if (array_key_exists($cache_key, $term_translation_cache)) {
		return $term_translation_cache[$cache_key];
	}

	$translated = classiadspro_translatepress_translate_string($term_name, false);
	if (is_string($translated) && $translated !== '') {
		$term_translation_cache[$cache_key] = $translated;
		return $translated;
	}

	$term_translation_cache[$cache_key] = $term_name;
	return $term_name;
}

function classiadspro_translatepress_filter_directorypress_term($term) {
	if (!($term instanceof WP_Term)) {
		return $term;
	}

	$term->name = classiadspro_translatepress_translate_term_name($term->name, $term->taxonomy);

	return $term;
}
add_filter('get_term', 'classiadspro_translatepress_filter_directorypress_term');

function classiadspro_translatepress_filter_directorypress_terms($terms, $taxonomies = array()) {
	if (!is_array($terms) || empty($terms)) {
		return $terms;
	}

	foreach ($terms as $index => $term) {
		if ($term instanceof WP_Term) {
			$terms[$index] = classiadspro_translatepress_filter_directorypress_term($term);
		}
	}

	return $terms;
}
add_filter('get_terms', 'classiadspro_translatepress_filter_directorypress_terms', 10, 2);

function classiadspro_translatepress_translate_listing_content($content) {
	if (classiadspro_is_translatepress_editor_request()) {
		return $content;
	}

	$post_id = 0;
	if (get_post_type() === 'dp_listing') {
		$post_id = (int) get_the_ID();
	} elseif (classiadspro_is_directorypress_listing_request()) {
		$post_id = classiadspro_get_current_directorypress_listing_post_id();
	}

	if ($post_id > 0) {
		$saved_translation = classiadspro_get_dp_listing_translation_value($post_id, array('content'));
		if ($saved_translation !== '') {
			return $saved_translation;
		}
	}

	return classiadspro_translatepress_translate_listing_string($content, true);
}
add_filter('the_content', 'classiadspro_translatepress_translate_listing_content', 1);

function classiadspro_translatepress_ui_overrides() {
	return [
		'Home' => 'Главная',
		'Listing' => 'Объявления',
		'Pages' => 'Страницы',
		'Blog' => 'Блог',
		'Contact' => 'Контакты',
		'Login' => 'Вход',
		'login' => 'Вход',
		'Register' => 'Регистрация',
		'Post Free Ad' => 'Разместить объявление',
		'About' => 'О нас',
		'Pricing Plan' => 'Тарифы',
		'All Categories' => 'Все категории',
		'Terms of Services' => 'Условия использования',
		'FAQ' => 'FAQ',
		'Forget Password' => 'Забыли пароль',
		'Normal Package' => 'Обычный пакет',
		'Best Package' => 'Лучший пакет',
		'most popular' => 'самый популярный',
		'View All Listings' => 'Посмотреть все объявления',
		'Our News' => 'Наши новости',
		'Contact Us' => 'Свяжитесь с нами',
	];
}

function classiadspro_is_directorypress_submit_request() {
	$post = get_post();

	if (!$post instanceof WP_Post) {
		return false;
	}

	return has_shortcode($post->post_content, 'directorypress-submit');
}

function classiadspro_translatepress_start_ui_output_buffer() {
	if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST) || is_feed() || is_embed()) {
		return;
	}

	if (classiadspro_is_translatepress_editor_request()) {
		return;
	}

	if (classiadspro_is_directorypress_submit_request()) {
		return;
	}

	ob_start('classiadspro_translatepress_translate_ui_output_buffer');
}
add_action('template_redirect', 'classiadspro_translatepress_start_ui_output_buffer', 0);

function classiadspro_translatepress_translate_ui_output_buffer($html) {
	if (!is_string($html) || $html === '') {
		return $html;
	}

	if (!class_exists('DOMDocument')) {
		return classiadspro_translatepress_translate_ui_output_buffer_fallback($html);
	}

	$dom = new DOMDocument();
	$previous_state = libxml_use_internal_errors(true);
	$loaded = $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	libxml_clear_errors();
	libxml_use_internal_errors($previous_state);

	if (!$loaded) {
		return classiadspro_translatepress_translate_ui_output_buffer_fallback($html);
	}

	$xpath = new DOMXPath($dom);
	$text_nodes = $xpath->query('//text()[normalize-space(.) != ""]');
	$replace_pairs = classiadspro_translatepress_ui_overrides();

	if (!$text_nodes instanceof DOMNodeList || empty($replace_pairs)) {
		return $html;
	}

	foreach ($text_nodes as $text_node) {
		if (!($text_node instanceof DOMText)) {
			continue;
		}

		if (classiadspro_translatepress_text_node_has_excluded_ancestor($text_node)) {
			continue;
		}

		$value = trim($text_node->nodeValue);
		if ($value === '' || !isset($replace_pairs[$value])) {
			continue;
		}

		$text_node->nodeValue = str_replace($value, $replace_pairs[$value], $text_node->nodeValue);
	}

	$result = $dom->saveHTML();
	if (!is_string($result) || $result === '') {
		return $html;
	}

	return preg_replace('/^<\?xml.+?\?>/i', '', $result);
}

function classiadspro_translatepress_translate_ui_output_buffer_fallback($html) {
	$replace_pairs = [];

	foreach (classiadspro_translatepress_ui_overrides() as $original => $translated) {
		$replace_pairs['>' . $original . '<'] = '>' . $translated . '<';
	}

	return strtr($html, $replace_pairs);
}

function classiadspro_translatepress_text_node_has_excluded_ancestor($node) {
	$excluded_classes = [
		'trp-language-switcher-container',
		'trp-menu-ls-item',
	];

	while ($node && $node->parentNode instanceof DOMElement) {
		$node = $node->parentNode;
		$class_name = $node->getAttribute('class');

		if ($class_name === '') {
			continue;
		}

		foreach ($excluded_classes as $excluded_class) {
			if (preg_match('/(^|\s)' . preg_quote($excluded_class, '/') . '(\s|$)/', $class_name)) {
				return true;
			}
		}
	}

	return false;
}

function classiadspro_dom_node_has_ancestor_class($node, $target_classes) {
	if (!$node instanceof DOMNode) {
		return false;
	}

	$target_classes = array_filter(array_map('strval', (array) $target_classes));
	if (empty($target_classes)) {
		return false;
	}

	while ($node && $node->parentNode instanceof DOMElement) {
		$node = $node->parentNode;
		$class_name = $node->getAttribute('class');

		if ($class_name === '') {
			continue;
		}

		foreach ($target_classes as $target_class) {
			if (preg_match('/(^|\s)' . preg_quote($target_class, '/') . '(\s|$)/', $class_name)) {
				return true;
			}
		}
	}

	return false;
}

function classiadspro_translatepress_start_single_listing_output_buffer() {
	if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST) || is_feed() || is_embed()) {
		return;
	}

	if (classiadspro_is_translatepress_editor_request()) {
		return;
	}

	if (classiadspro_is_directorypress_submit_request()) {
		return;
	}

	if (!classiadspro_is_directorypress_listing_request()) {
		return;
	}

	ob_start('classiadspro_translatepress_translate_single_listing_output_buffer');
}
add_action('template_redirect', 'classiadspro_translatepress_start_single_listing_output_buffer', 1);

function classiadspro_translatepress_translate_single_listing_output_buffer($html) {
	if (!is_string($html) || $html === '' || !class_exists('DOMDocument')) {
		return classiadspro_force_known_listing_label_replacements(classiadspro_override_dp_listing_head_meta_in_html($html));
	}

	$html = classiadspro_override_dp_listing_head_meta_in_html($html);
	$post_id = classiadspro_get_current_directorypress_listing_post_id();
	$has_saved_title_translation = $post_id > 0 && classiadspro_get_dp_listing_translation_value($post_id, array('title')) !== '';
	$has_saved_content_translation = $post_id > 0 && classiadspro_get_dp_listing_translation_value($post_id, array('content')) !== '';

	$dom = new DOMDocument();
	$previous_state = libxml_use_internal_errors(true);
	$loaded = $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	libxml_clear_errors();
	libxml_use_internal_errors($previous_state);

	if (!$loaded) {
		return $html;
	}

	$xpath = new DOMXPath($dom);
	classiadspro_override_dp_listing_head_meta_in_dom($dom, $xpath);
	$text_nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " single-listing ")]//text()[normalize-space(.) != ""]');

	if (!$text_nodes instanceof DOMNodeList) {
		return $html;
	}

	foreach ($text_nodes as $text_node) {
		if (!($text_node instanceof DOMText)) {
			continue;
		}

		$parent_node = $text_node->parentNode;
		if ($parent_node instanceof DOMElement) {
			$tag_name = strtolower($parent_node->tagName);
			if (in_array($tag_name, array('script', 'style'), true)) {
				continue;
			}
		}

		if (
			$has_saved_title_translation &&
			classiadspro_dom_node_has_ancestor_class($text_node, array('directorypress-listing-title'))
		) {
			continue;
		}

		if (
			$has_saved_content_translation &&
			classiadspro_dom_node_has_ancestor_class($text_node, array('directorypress-field-description'))
		) {
			continue;
		}

		$original_text = trim($text_node->nodeValue);
		if ($original_text === '') {
			continue;
		}

		$translated_text = classiadspro_translatepress_translate_string($original_text, false);
		if (!is_string($translated_text) || $translated_text === '' || $translated_text === $original_text) {
			continue;
		}

		$text_node->nodeValue = preg_replace('/' . preg_quote($original_text, '/') . '/u', $translated_text, $text_node->nodeValue, 1);
	}

	$result = $dom->saveHTML();

	$result = is_string($result) && $result !== '' ? preg_replace('/^<\?xml.+?\?>/i', '', $result) : $html;

	return classiadspro_force_known_listing_label_replacements($result);
}

function classiadspro_force_known_listing_label_replacements($html) {
	if (!is_string($html) || $html === '') {
		return $html;
	}

	$language_suffix = classiadspro_translatepress_get_current_language_suffix();
	$price_label = 'Price';

	if (str_starts_with($language_suffix, 'ru')) {
		$price_label = 'Цена';
	} elseif (str_starts_with($language_suffix, 'uk')) {
		$price_label = 'Ціна';
	}

	$replacements = [
		'знаменос:' => $price_label . ':',
		'знаменос' => $price_label,
	];

	return strtr($html, $replacements);
}

function classiadspro_disable_hfb_header_on_mobile($enabled) {
	if (wp_is_mobile()) {
		return false;
	}

	return $enabled;
}
add_filter('hfb_header_enabled', 'classiadspro_disable_hfb_header_on_mobile');

function classiadspro_override_dp_listing_head_meta_in_html($html) {
	if (!is_string($html) || $html === '' || !classiadspro_is_directorypress_listing_request()) {
		return $html;
	}

	$post_id = classiadspro_get_current_directorypress_listing_post_id();
	if ($post_id <= 0) {
		return $html;
	}

	$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('seo', 'title'));
	if ($translated_title === '') {
		$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('title'));
	}

	$translated_description = classiadspro_get_dp_listing_translation_description($post_id);

	if ($translated_title !== '') {
		$title_tag = htmlspecialchars($translated_title . ' | Listings', ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$og_title = htmlspecialchars($translated_title . ' - Listings', ENT_QUOTES | ENT_HTML5, 'UTF-8');

		$html = preg_replace('/<title\b[^>]*>.*?<\/title>/is', '<title>' . $title_tag . '</title>', $html, 1);
		$html = preg_replace_callback(
			'/(<meta\b[^>]*property="og:title"[^>]*content=")[^"]*(")/i',
			static function ($matches) use ($og_title) {
				return $matches[1] . $og_title . $matches[2];
			},
			$html
		);
		$html = preg_replace_callback(
			'/(<meta\b[^>]*name="twitter:title"[^>]*content=")[^"]*(")/i',
			static function ($matches) use ($title_tag) {
				return $matches[1] . $title_tag . $matches[2];
			},
			$html
		);
	}

	if ($translated_description !== '') {
		$description_attr = htmlspecialchars($translated_description, ENT_QUOTES | ENT_HTML5, 'UTF-8');

		$html = preg_replace_callback(
			'/(<meta\b[^>]*name="description"[^>]*content=")[^"]*(")/i',
			static function ($matches) use ($description_attr) {
				return $matches[1] . $description_attr . $matches[2];
			},
			$html
		);
		$html = preg_replace_callback(
			'/(<meta\b[^>]*property="og:description"[^>]*content=")[^"]*(")/i',
			static function ($matches) use ($description_attr) {
				return $matches[1] . $description_attr . $matches[2];
			},
			$html
		);
		$html = preg_replace_callback(
			'/(<meta\b[^>]*name="twitter:description"[^>]*content=")[^"]*(")/i',
			static function ($matches) use ($description_attr) {
				return $matches[1] . $description_attr . $matches[2];
			},
			$html
		);
	}

	return $html;
}

function classiadspro_dom_set_node_text($node, $value) {
	if (!$node instanceof DOMNode || !is_string($value)) {
		return;
	}

	while ($node->firstChild) {
		$node->removeChild($node->firstChild);
	}

	$node->appendChild($node->ownerDocument->createTextNode($value));
}

function classiadspro_dom_set_meta_content_by_query($xpath, $query, $value) {
	if (!$xpath instanceof DOMXPath || !is_string($query) || $query === '' || !is_string($value) || $value === '') {
		return;
	}

	$nodes = $xpath->query($query);
	if (!$nodes instanceof DOMNodeList) {
		return;
	}

	foreach ($nodes as $node) {
		if ($node instanceof DOMElement) {
			$node->setAttribute('content', $value);
		}
	}
}

function classiadspro_override_dp_listing_head_meta_in_dom($dom, $xpath) {
	if (
		!$dom instanceof DOMDocument ||
		!$xpath instanceof DOMXPath ||
		!classiadspro_is_directorypress_listing_request()
	) {
		return;
	}

	$post_id = classiadspro_get_current_directorypress_listing_post_id();
	if ($post_id <= 0) {
		return;
	}

	$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('seo', 'title'));
	if ($translated_title === '') {
		$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('title'));
	}

	$translated_description = classiadspro_get_dp_listing_translation_description($post_id);

	if ($translated_title !== '') {
		$title_nodes = $xpath->query('//title');
		if ($title_nodes instanceof DOMNodeList) {
			foreach ($title_nodes as $title_node) {
				classiadspro_dom_set_node_text($title_node, $translated_title . ' | Listings');
			}
		}

		classiadspro_dom_set_meta_content_by_query($xpath, '//meta[@property="og:title"]', $translated_title . ' - Listings');
		classiadspro_dom_set_meta_content_by_query($xpath, '//meta[@name="twitter:title"]', $translated_title);
	}

	if ($translated_description !== '') {
		classiadspro_dom_set_meta_content_by_query($xpath, '//meta[@name="description"]', $translated_description);
		classiadspro_dom_set_meta_content_by_query($xpath, '//meta[@property="og:description"]', $translated_description);
		classiadspro_dom_set_meta_content_by_query($xpath, '//meta[@name="twitter:description"]', $translated_description);
	}
}

function classiadspro_get_dp_listing_translation_language_candidates() {
	$language_suffix = classiadspro_translatepress_get_current_language_suffix();
	$candidates = array();
	$default_candidates = array();
	$aliases = array(
		'ru_ru' => 'ru',
		'ru' => 'ru',
		'uk_ua' => 'ua',
		'uk' => 'ua',
		'ua' => 'ua',
		'en_us' => 'en',
		'en' => 'en',
		'es_es' => 'es',
		'es' => 'es',
		'de_de' => 'de',
		'de' => 'de',
		'tr_tr' => 'tr',
		'tr' => 'tr',
	);

	if (is_string($language_suffix) && $language_suffix !== '') {
		$candidates[] = strtolower($language_suffix);

		$short_code = strtok($language_suffix, '_');
		if (is_string($short_code) && $short_code !== '') {
			$candidates[] = strtolower($short_code);
		}
	}

	foreach ($candidates as $candidate) {
		if (isset($aliases[$candidate])) {
			$candidates[] = $aliases[$candidate];
		}
	}

	$trp_settings = get_option('trp_settings', array());
	if (!empty($trp_settings['default-language']) && is_string($trp_settings['default-language'])) {
		$default_language = strtolower(str_replace('-', '_', $trp_settings['default-language']));
		$default_candidates[] = $default_language;

		$default_short_code = strtok($default_language, '_');
		if (is_string($default_short_code) && $default_short_code !== '') {
			$default_candidates[] = strtolower($default_short_code);
		}
	}

	foreach ($default_candidates as $candidate) {
		$candidates[] = $candidate;
		if (isset($aliases[$candidate])) {
			$candidates[] = $aliases[$candidate];
		}
	}

	return array_values(array_unique(array_filter(array_map('strval', $candidates))));
}

function classiadspro_get_dp_listing_translation_data($post_id = 0) {
	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
	if ($post_id <= 0 || get_post_type($post_id) !== 'dp_listing') {
		return null;
	}

	$translations = get_post_meta($post_id, 'translations', true);
	if (!is_array($translations) || empty($translations)) {
		return null;
	}

	foreach (classiadspro_get_dp_listing_translation_language_candidates() as $candidate) {
		if (!empty($translations[$candidate]) && is_array($translations[$candidate])) {
			return $translations[$candidate];
		}
	}

	return null;
}

function classiadspro_get_current_directorypress_listing_post_id() {
	$post = get_post();
	if ($post instanceof WP_Post && $post->post_type === 'dp_listing') {
		return (int) $post->ID;
	}

	global $directorypress_object;

	if (
		is_object($directorypress_object) &&
		isset($directorypress_object->current_listing) &&
		is_object($directorypress_object->current_listing) &&
		isset($directorypress_object->current_listing->post->ID)
	) {
		return (int) $directorypress_object->current_listing->post->ID;
	}

	if (
		isset($GLOBALS['directorypress_shortcode_instance']) &&
		is_object($GLOBALS['directorypress_shortcode_instance']) &&
		isset($GLOBALS['directorypress_shortcode_instance']->listing) &&
		is_object($GLOBALS['directorypress_shortcode_instance']->listing) &&
		isset($GLOBALS['directorypress_shortcode_instance']->listing->post->ID)
	) {
		return (int) $GLOBALS['directorypress_shortcode_instance']->listing->post->ID;
	}

	$queried_id = (int) get_queried_object_id();
	if ($queried_id > 0 && get_post_type($queried_id) === 'dp_listing') {
		return $queried_id;
	}

	$request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
	$request_path = trim((string) wp_parse_url($request_uri, PHP_URL_PATH), '/');

	if ($request_path !== '') {
		$path_segments = array_values(array_filter(explode('/', $request_path), 'strlen'));
		$candidate_slug = '';

		if (!empty($path_segments)) {
			$candidate_slug = sanitize_title(end($path_segments));

			$trp_settings = get_option('trp_settings', array());
			if (
				$candidate_slug !== '' &&
				!empty($trp_settings['url-slugs']) &&
				is_array($trp_settings['url-slugs']) &&
				in_array($candidate_slug, array_map('sanitize_title', $trp_settings['url-slugs']), true)
			) {
				$candidate_slug = '';
			}
		}

		if ($candidate_slug !== '') {
			$listing_post = get_page_by_path($candidate_slug, OBJECT, 'dp_listing');
			if ($listing_post instanceof WP_Post) {
				return (int) $listing_post->ID;
			}
		}
	}

	return 0;
}

function classiadspro_get_dp_listing_translation_value($post_id, $path) {
	$translation = classiadspro_get_dp_listing_translation_data($post_id);
	if (!is_array($translation) || empty($path) || !is_array($path)) {
		return '';
	}

	$value = $translation;
	foreach ($path as $segment) {
		if (!is_array($value) || !array_key_exists($segment, $value)) {
			return '';
		}

		$value = $value[$segment];
	}

	return is_string($value) ? trim($value) : '';
}

function classiadspro_get_dp_listing_display_title($post_id, $fallback_title = '') {
	$post_id = (int) $post_id;
	$fallback_title = is_string($fallback_title) ? $fallback_title : '';

	if ($post_id <= 0 || get_post_type($post_id) !== 'dp_listing') {
		return $fallback_title;
	}

	$saved_translation = classiadspro_get_dp_listing_translation_value($post_id, array('title'));
	if ($saved_translation !== '') {
		return $saved_translation;
	}

	$base_title = $fallback_title !== '' ? $fallback_title : get_the_title($post_id);
	if (!is_string($base_title) || $base_title === '') {
		return '';
	}

	$translated_title = classiadspro_translatepress_translate_string($base_title, false);
	if (is_string($translated_title) && $translated_title !== '') {
		return $translated_title;
	}

	$fallback_translation = classiadspro_translatepress_lookup_listing_title_by_post_id($post_id, $base_title);
	if (is_string($fallback_translation) && $fallback_translation !== '') {
		return $fallback_translation;
	}

	return $base_title;
}

function classiadspro_get_dp_listing_translation_description($post_id) {
	$description = classiadspro_get_dp_listing_translation_value($post_id, array('seo', 'description'));
	if ($description !== '') {
		return $description;
	}

	$content = classiadspro_get_dp_listing_translation_value($post_id, array('content'));
	if ($content === '') {
		return '';
	}

	return wp_trim_words(wp_strip_all_tags($content), 35, ' ...');
}

function classiadspro_filter_dp_listing_translated_title($title, $post_id = 0) {
	if (is_admin()) {
		return $title;
	}

	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
	$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('title'));

	return $translated_title !== '' ? $translated_title : $title;
}
add_filter('the_title', 'classiadspro_filter_dp_listing_translated_title', 99, 2);

function classiadspro_filter_dp_listing_document_title($title) {
	if (is_admin() || !classiadspro_is_directorypress_listing_request()) {
		return $title;
	}

	$post_id = classiadspro_get_current_directorypress_listing_post_id();
	$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('seo', 'title'));
	if ($translated_title === '') {
		$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('title'));
	}

	return $translated_title !== '' ? $translated_title : $title;
}
add_filter('pre_get_document_title', 'classiadspro_filter_dp_listing_document_title', 99);

function classiadspro_filter_dp_listing_wpseo_title($title) {
	if (is_admin() || !classiadspro_is_directorypress_listing_request()) {
		return $title;
	}

	$post_id = classiadspro_get_current_directorypress_listing_post_id();
	$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('seo', 'title'));
	if ($translated_title === '') {
		$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('title'));
	}

	return $translated_title !== '' ? $translated_title : $title;
}
add_filter('wpseo_title', 'classiadspro_filter_dp_listing_wpseo_title', 99);

function classiadspro_filter_dp_listing_wpseo_metadesc($description) {
	if (is_admin() || !classiadspro_is_directorypress_listing_request()) {
		return $description;
	}

	$post_id = classiadspro_get_current_directorypress_listing_post_id();
	$translated_description = classiadspro_get_dp_listing_translation_description($post_id);

	return $translated_description !== '' ? $translated_description : $description;
}
add_filter('wpseo_metadesc', 'classiadspro_filter_dp_listing_wpseo_metadesc', 99);

function classiadspro_filter_dp_listing_wpseo_opengraph_title($title) {
	if (is_admin() || !classiadspro_is_directorypress_listing_request()) {
		return $title;
	}

	$post_id = classiadspro_get_current_directorypress_listing_post_id();
	$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('seo', 'title'));
	if ($translated_title === '') {
		$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('title'));
	}

	return $translated_title !== '' ? $translated_title . ' - Listings' : $title;
}
add_filter('wpseo_opengraph_title', 'classiadspro_filter_dp_listing_wpseo_opengraph_title', 99);

function classiadspro_filter_dp_listing_wpseo_opengraph_desc($description) {
	if (is_admin() || !classiadspro_is_directorypress_listing_request()) {
		return $description;
	}

	$post_id = classiadspro_get_current_directorypress_listing_post_id();
	$translated_description = classiadspro_get_dp_listing_translation_description($post_id);

	return $translated_description !== '' ? $translated_description : $description;
}
add_filter('wpseo_opengraph_desc', 'classiadspro_filter_dp_listing_wpseo_opengraph_desc', 99);
add_filter('wpseo_twitter_description', 'classiadspro_filter_dp_listing_wpseo_opengraph_desc', 99);

function classiadspro_filter_dp_listing_excerpt($excerpt, $post = null) {
	if (is_admin() || !classiadspro_is_directorypress_listing_request()) {
		return $excerpt;
	}

	$post_id = 0;
	if ($post instanceof WP_Post) {
		$post_id = (int) $post->ID;
	} elseif (is_numeric($post)) {
		$post_id = (int) $post;
	} else {
		$post_id = classiadspro_get_current_directorypress_listing_post_id();
	}

	$translated_description = classiadspro_get_dp_listing_translation_description($post_id);

	return $translated_description !== '' ? $translated_description : $excerpt;
}
add_filter('get_the_excerpt', 'classiadspro_filter_dp_listing_excerpt', 99, 2);

function classiadspro_filter_directorypress_listing_excerpt_from_content($excerpt, $words_length, $listing) {
	if (is_admin() || !classiadspro_is_directorypress_listing_request() || !is_object($listing) || empty($listing->post->ID)) {
		return $excerpt;
	}

	$translated_description = classiadspro_get_dp_listing_translation_description((int) $listing->post->ID);

	return $translated_description !== '' ? $translated_description : $excerpt;
}
add_filter('directorypress_get_excerpt_from_content', 'classiadspro_filter_directorypress_listing_excerpt_from_content', 99, 3);

function classiadspro_replace_directorypress_listing_opengraph_meta() {
	if (is_admin() || !classiadspro_is_directorypress_listing_request()) {
		return;
	}

	global $wp_filter;

	if (empty($wp_filter['wp_head']) || !isset($wp_filter['wp_head']->callbacks) || !is_array($wp_filter['wp_head']->callbacks)) {
		return;
	}

	foreach ($wp_filter['wp_head']->callbacks as $priority => $callbacks) {
		foreach ($callbacks as $callback_data) {
			if (
				empty($callback_data['function']) ||
				!is_array($callback_data['function']) ||
				!is_object($callback_data['function'][0]) ||
				$callback_data['function'][1] !== 'insert_opengraph_metadat'
			) {
				continue;
			}

			remove_action('wp_head', $callback_data['function'], (int) $priority);
		}
	}
}
add_action('wp', 'classiadspro_replace_directorypress_listing_opengraph_meta', 20);

function classiadspro_output_listing_translation_opengraph_meta() {
	if (is_admin() || !classiadspro_is_directorypress_listing_request()) {
		return;
	}

	$post_id = classiadspro_get_current_directorypress_listing_post_id();
	if ($post_id <= 0) {
		return;
	}

	$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('seo', 'title'));
	if ($translated_title === '') {
		$translated_title = classiadspro_get_dp_listing_translation_value($post_id, array('title'));
	}

	$translated_description = classiadspro_get_dp_listing_translation_description($post_id);
	$permalink = get_permalink($post_id);
	$image = get_the_post_thumbnail_url($post_id, 'full');

	if ($translated_title === '' && $translated_description === '') {
		return;
	}

	echo '<meta property="og:type" content="article" data-classiadspro-og-meta="true" />';

	if ($translated_title !== '') {
		echo '<meta property="og:title" content="' . esc_attr($translated_title . ' - Listings') . '" />';
	}

	if ($translated_description !== '') {
		echo '<meta property="og:description" content="' . esc_attr($translated_description) . '" />';
	}

	if ($permalink) {
		echo '<meta property="og:url" content="' . esc_url($permalink) . '" />';
	}

	echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />';

	if ($image) {
		echo '<meta property="og:image" content="' . esc_url($image) . '" />';
	}
}
add_action('wp_head', 'classiadspro_output_listing_translation_opengraph_meta', -9);

function classiadspro_output_listing_translation_meta_tags() {
	if (is_admin() || !classiadspro_is_directorypress_listing_request()) {
		return;
	}

	$post_id = classiadspro_get_current_directorypress_listing_post_id();
	if ($post_id <= 0) {
		return;
	}

	$seo_title = classiadspro_get_dp_listing_translation_value($post_id, array('seo', 'title'));
	if ($seo_title === '') {
		$seo_title = classiadspro_get_dp_listing_translation_value($post_id, array('title'));
	}

	$seo_description = classiadspro_get_dp_listing_translation_description($post_id);
	$seo_keywords = classiadspro_get_dp_listing_translation_value($post_id, array('seo', 'keywords'));

	if ($seo_description !== '') {
		echo '<meta name="description" content="' . esc_attr($seo_description) . '" />' . "\n";
		echo '<meta name="twitter:description" content="' . esc_attr($seo_description) . '" />' . "\n";
	}

	if ($seo_keywords !== '') {
		echo '<meta name="keywords" content="' . esc_attr($seo_keywords) . '" />' . "\n";
	}

	if ($seo_title !== '') {
		echo '<meta name="twitter:title" content="' . esc_attr($seo_title) . '" />' . "\n";
	}
}
add_action('wp_head', 'classiadspro_output_listing_translation_meta_tags', -8);
