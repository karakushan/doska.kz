<?php

/**
 * Скрипт обработки CSV с загрузкой изображений в медиабиблиотеку WordPress.
 * 
 * - Первая строка CSV (заголовки) пропускается
 * - Столбцы 9-13 (считая с 0) содержат URL изображений
 * - Скачивает изображение по URL
 * - Конвертирует в WebP с сжатием
 * - Загружает в медиабиблиотеку WordPress через встроенные функции
 * - Записывает ID вложения вместо URL в ту же ячейку
 * - Остальные столбцы остаются без изменений
 * 
 * Использование: запустить через браузер или CLI на сервере WordPress
 */

// ===================== НАСТРОЙКИ =====================
$WEBP_QUALITY  = 100;   // Качество сжатия WebP (0-100)
$TEMP_DIR      = __DIR__ . '/temp_images';
$IMAGE_COL_FROM = 9;   // Начальный столбец с изображениями (считая с 0)
$IMAGE_COL_TO   = 13;  // Конечный столбец с изображениями (считая с 0)
$Max            = 2;   // Максимум строк для обработки (для тестов)
// =====================================================

// Создаём временную директорию
if (!is_dir($TEMP_DIR)) {
    mkdir($TEMP_DIR, 0755, true);
}

// --- Подключаем WordPress ---
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-admin/includes/image.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-admin/includes/file.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-admin/includes/media.php';

$inputFile  = "many_image_output_output_output_output_sahibinden.com_result.csv";
$outputFile = "id_many_image_output_output_output_output_sahibinden.com_result.csv";

if (!file_exists($inputFile)) {
    echo "Ошибка: файл '$inputFile' не найден.</br>";
    exit(1);
}


/**
 * Скачивает изображение по URL во временный файл.
 */
function downloadImage(string $url, string $tempDir): string|false
{
    $parsedPath = parse_url($url, PHP_URL_PATH);
    $ext = strtolower(pathinfo($parsedPath, PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
        $ext = 'jpg';
    }

    $tempFile = $tempDir . '/' . uniqid('img_') . '.' . $ext;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 5,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    ]);

    $data     = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);
    curl_close($ch);

    if ($httpCode !== 200 || empty($data)) {
        echo "  [ОШИБКА] Не удалось скачать: $url (HTTP $httpCode";
        if ($error) echo ", cURL: $error";
        echo ")</br>";
        return false;
    }

    file_put_contents($tempFile, $data);
    echo "  Скачано: $url -> $tempFile (" . round(strlen($data) / 1024) . " KB)</br>";
    return $tempFile;
}


/**
 * Конвертирует изображение в WebP.
 */
function convertToWebp(string $inputPath, string $tempDir, int $quality = 80): string|false
{
    if (!file_exists($inputPath)) {
        echo "  [ОШИБКА] Файл не найден: $inputPath</br>";
        return false;
    }

    $imageInfo = @getimagesize($inputPath);
    if ($imageInfo === false) {
        echo "  [ОШИБКА] Не является изображением: $inputPath</br>";
        return false;
    }

    $mimeType = $imageInfo['mime'];

    switch ($mimeType) {
        case 'image/jpeg':
            $image = @imagecreatefromjpeg($inputPath);
            break;
        case 'image/png':
            $image = @imagecreatefrompng($inputPath);
            if ($image) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
            break;
        case 'image/gif':
            $image = @imagecreatefromgif($inputPath);
            break;
        case 'image/bmp':
        case 'image/x-ms-bmp':
            $image = @imagecreatefrombmp($inputPath);
            break;
        case 'image/webp':
            $outputPath = $tempDir . '/' . pathinfo($inputPath, PATHINFO_FILENAME) . '_conv.webp';
            copy($inputPath, $outputPath);
            echo "  Уже WebP, скопировано: $outputPath</br>";
            return $outputPath;
        default:
            echo "  [ОШИБКА] Формат '$mimeType' не поддерживается (JPEG, PNG, GIF, BMP, WEBP).</br>";
            return false;
    }

    if (!$image) {
        echo "  [ОШИБКА] Не удалось загрузить изображение: $inputPath</br>";
        return false;
    }

    $outputPath = $tempDir . '/' . pathinfo($inputPath, PATHINFO_FILENAME) . '.webp';
    $result = imagewebp($image, $outputPath, $quality);
    imagedestroy($image);

    if (!$result) {
        echo "  [ОШИБКА] Не удалось сохранить WebP: $outputPath</br>";
        return false;
    }

    echo "  Конвертировано в WebP: $outputPath (качество: $quality)</br>";
    return $outputPath;
}


/**
 * Загружает файл в медиабиблиотеку WordPress.
 */
function uploadToWordpress(string $filePath): int|false
{
    if (!file_exists($filePath)) {
        echo "  [ОШИБКА] Файл для загрузки не найден: $filePath</br>";
        return false;
    }

    // Копируем файл, т.к. wp_handle_sideload перемещает оригинал
    $tmpCopy = $filePath . '_upload.webp';
    copy($filePath, $tmpCopy);

    $fileArray = [
        'name'     => basename($filePath),
        'type'     => 'image/webp',
        'tmp_name' => $tmpCopy,
        'error'    => 0,
        'size'     => filesize($tmpCopy),
    ];

    $overrides = [
        'test_form' => false,
        'test_type' => true,
    ];

    $uploaded = wp_handle_sideload($fileArray, $overrides);

    if (isset($uploaded['error'])) {
        echo "  [ОШИБКА] wp_handle_sideload: " . $uploaded['error'] . "</br>";
        @unlink($tmpCopy);
        return false;
    }

    $attachment = [
        'post_mime_type' => $uploaded['type'],
        'post_title'     => sanitize_file_name(pathinfo($uploaded['file'], PATHINFO_FILENAME)),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];

    $attachmentId = wp_insert_attachment($attachment, $uploaded['file']);

    if (is_wp_error($attachmentId)) {
        echo "  [ОШИБКА] wp_insert_attachment: " . $attachmentId->get_error_message() . "</br>";
        return false;
    }

    $attachmentData = wp_generate_attachment_metadata($attachmentId, $uploaded['file']);
    wp_update_attachment_metadata($attachmentId, $attachmentData);

    echo "  Загружено в WordPress: ID=$attachmentId</br>";
    return $attachmentId;
}


// ===================== ОСНОВНОЙ ПРОЦЕСС =====================

$inputHandle  = fopen($inputFile, 'r');
$outputHandle = fopen($outputFile, 'w');

if (!$inputHandle || !$outputHandle) {
    echo "Ошибка: не удалось открыть файл(ы).</br>";
    exit(1);
}

$lineNumber = 0;
$uploaded   = 0;
$errors     = 0;

echo "=== Начало обработки CSV ===</br></br>";

// --- Пропускаем заголовок и записываем его как есть ---
$header = fgetcsv($inputHandle);
if ($header !== false) {
    fputcsv($outputHandle, $header);
    echo "Заголовок пропущен: " . implode(', ', array_slice($header, 0, 5)) . "...</br></br>";
}

while (($row = fgetcsv($inputHandle)) !== false) {
    $lineNumber++;
    if ($lineNumber >= $Max) break;
    echo "Строка $lineNumber:</br>";

    // === Обрабатываем столбцы 9-13 (изображения) ===
    for ($colIndex = $IMAGE_COL_FROM; $colIndex <= $IMAGE_COL_TO; $colIndex++) {
        $imageUrl = isset($row[$colIndex]) ? trim($row[$colIndex]) : '';

        if (empty($imageUrl)) {
            echo "  Столбец $colIndex: пустое значение, пропуск.</br>";
            continue;
        }

        echo "  Столбец $colIndex: обработка '$imageUrl'</br>";

        // 1. Скачиваем если URL, иначе берём локальный путь
        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            $localPath = downloadImage($imageUrl, $TEMP_DIR);
            if ($localPath === false) {
                echo "  [ПРОПУСК] Не удалось скачать.</br>";
                $row[$colIndex] = '';
                $errors++;
                continue;
            }
        } else {
            $localPath = $imageUrl;
        }

        // 2. Конвертируем в WebP
        $webpPath = convertToWebp($localPath, $TEMP_DIR, $WEBP_QUALITY);

        // Удаляем скачанный оригинал
        if ($localPath !== $imageUrl && file_exists($localPath)) {
            @unlink($localPath);
        }

        if ($webpPath === false) {
            echo "  [ПРОПУСК] Не удалось конвертировать.</br>";
            $row[$colIndex] = '';
            $errors++;
            continue;
        }

        // 3. Загружаем в WordPress
        $attachmentId = uploadToWordpress($webpPath);

        // Удаляем WebP временный файл
        if (file_exists($webpPath)) {
            @unlink($webpPath);
        }

        if ($attachmentId === false) {
            echo "  [ПРОПУСК] Не удалось загрузить в WordPress.</br>";
            $row[$colIndex] = '';
            $errors++;
            continue;
        }

        // 4. Записываем ID в ту же ячейку
        $row[$colIndex] = $attachmentId;
        $uploaded++;
        echo "  [OK] Столбец $colIndex: URL заменён на ID: $attachmentId</br>";
    }

    fputcsv($outputHandle, $row);
    echo "</br>";
}

fclose($inputHandle);
fclose($outputHandle);

// Очищаем временную директорию
array_map('unlink', glob("$TEMP_DIR/*"));
@rmdir($TEMP_DIR);

echo "=== Готово! ===</br>";
echo "Обработано строк: $lineNumber</br>";
echo "Загружено изображений: $uploaded</br>";
echo "Ошибок: $errors</br>";
echo "Результат: $outputFile</br>";
