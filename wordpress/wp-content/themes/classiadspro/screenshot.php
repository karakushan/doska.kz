<?php
/**
 * screenshot.php
 * Генерация скриншота страницы через APIFlash с кешированием
 * Используется в <img src="screenshot.php?url=...&device=desktop|mobile">
 */

$api_key = '5919b388037749dfaa5def2262010bda'; // ? смените на свой ключ
$cache_dir = __DIR__ . '/cache/';
if (!is_dir($cache_dir)) mkdir($cache_dir, 0755, true);

// Получаем URL и device
$url = isset($_GET['url']) ? $_GET['url'] : '';
$device = isset($_GET['device']) ? $_GET['device'] : 'desktop';
if (!$url) {
    http_response_code(400);
    echo "URL required";
    exit;
}

// Определяем размеры по device
if ($device === 'mobile') {
    $width = 390;
    $height = 844;
    $mobile_flag = 'true';
} else {
    $width = 1200;
    $height = 800;
    $mobile_flag = 'false';
}

// Кэш-файл
$hash = md5($url . $device);
$cache_file = $cache_dir . $hash . '.webp';

// Если есть кэш, отдаем его
if (file_exists($cache_file) && filemtime($cache_file) > time() - 3600) { // кеш 1 час
    header('Content-Type: image/webp');
    readfile($cache_file);
    exit;
}

// Формируем URL API
$api_url = "https://api.apiflash.com/v1/urltoimage?access_key={$api_key}&url=" . urlencode($url) .
           "&format=webp&width={$width}&height={$height}&wait_until=page_loaded&mobile={$mobile_flag}&quality=90";

// Получаем изображение
$image = @file_get_contents($api_url);
if (!$image) {
    http_response_code(500);
    echo "Failed to generate screenshot";
    exit;
}

// Сохраняем в кэш
file_put_contents($cache_file, $image);

// Отдаем клиенту
header('Content-Type: image/webp');
echo $image;
exit;
