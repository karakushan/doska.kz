<?php

if (PHP_SAPI !== 'cli') {
	http_response_code(403);
	exit(1);
}

$wpLoad = dirname(__DIR__, 3) . '/wp-load.php';

if (!file_exists($wpLoad)) {
	fwrite(STDERR, "wp-load.php not found\n");
	exit(1);
}

ob_start();
require_once $wpLoad;
ob_end_clean();

if (!function_exists('rs_is_api_enabled') || !function_exists('rs_update_exchange_rates')) {
	fwrite(STDERR, "Exchange rate helpers are unavailable\n");
	exit(1);
}

if (!rs_is_api_enabled()) {
	exit(0);
}

if (function_exists('rs_ensure_exchange_rates_event')) {
	rs_ensure_exchange_rates_event();
}

$rates = get_option('rs_exchange_rates', []);
$lastUpdated = isset($rates['updated']) ? (int) $rates['updated'] : 0;

if ($lastUpdated && (time() - $lastUpdated) < (DAY_IN_SECONDS - HOUR_IN_SECONDS)) {
	exit(0);
}

if (!rs_update_exchange_rates()) {
	fwrite(STDERR, "Failed to update exchange rates\n");
	exit(1);
}
