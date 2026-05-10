<?php

/**
 * Скрипт обработки CSV:
 * - Столбец 1 (индекс 1): конвертация из турецких лир (TRY) в доллары USD (курс: 1 TRY = 0.022 USD)
 * - Столбец 2 (индекс 2): разделитель |, оставляем первый элемент
 * - Столбец 3 (индекс 3): обрезаем от вхождения firstElement до конца, | → " > "
 * - Столбец 5 (индекс 5): генерация username (пробелы→_, транслит, только латиница)
 * - Столбец 7 (индекс 7): изображения через | — разбиваются начиная с индекса 9
 * - Столбец 7 (индекс 7) выхода: username
 * - Столбец 8 (индекс 8) выхода: username@adshelppro.com
 * - Столбцы 9+ (индекс 9+): изображения из исходного столбца 7
 * - Пропускает строки, где столбец 5 или 6 пустой
 *
 * Использование: php script4.php
 */

// Курс: 1 турецкая лира = 0.022 доллара США
define('TRY_TO_USD', 0.022);

$inputFile = "output_sahibinden.com_result.csv";
$outputFile = "output_output_output_output_sahibinden.com_result.csv";

if (!file_exists($inputFile)) {
    echo "Ошибка: файл '$inputFile' не найден.\n";
    exit(1);
}

$inputHandle = fopen($inputFile, 'r');
$outputHandle = fopen($outputFile, 'w');

if (!$inputHandle || !$outputHandle) {
    echo "Ошибка: не удалось открыть файл(ы).\n";
    exit(1);
}

// --- Первый проход: определяем макс. кол-во изображений в столбце 7 ---
$maxImages = 0;

while (($row = fgetcsv($inputHandle)) !== false) {
    // Пропускаем строки с пустым столбцом 5 или 6
    if (empty(trim($row[5] ?? '')) || empty(trim($row[6] ?? ''))) {
        continue;
    }
    $imgValue = isset($row[7]) ? trim($row[7]) : '';
    if ($imgValue !== '') {
        $count = count(explode('|', $imgValue));
        if ($count > $maxImages) {
            $maxImages = $count;
        }
    }
}

rewind($inputHandle);

// --- Второй проход: записываем результат ---
$lineNumber = 0;
$skipped = 0;

while (($row = fgetcsv($inputHandle)) !== false) {
    $lineNumber++;

    // === Пропускаем строку, если столбец 5 или 6 пустой ===
    if (empty(trim($row[5] ?? '')) || empty(trim($row[6] ?? ''))) {
        $skipped++;
        continue;
    }

    // === Столбец 1 (индекс 1): конвертация TRY → USD ===
    if (isset($row[1])) {
        // Убираем всё кроме цифр, точки и запятой (символы валюты ₺, TL и т.п.)
        $priceClean = preg_replace('/[^\d,.]/', '', trim($row[1]));
        // Турецкий формат: точка = разделитель тысяч (40.000 = 40000), запятая = десятичный
        $priceClean = str_replace('.', '', $priceClean);   // 40.000 → 40000
        $priceClean = str_replace(',', '.', $priceClean);  // 1500,50 → 1500.50

        if (is_numeric($priceClean) && (float) $priceClean > 0) {
            $row[1] = round((float) $priceClean * TRY_TO_USD, 2);
        }
    }

    // === Столбец 2 (индекс 2): разделитель |, оставляем первый элемент ===
    $firstElement = '';
    if (isset($row[2])) {
        $parts = explode('|', $row[2]);
        $firstElement = trim($parts[0]);
        $row[2] = $firstElement;
    }

    // === Столбец 3 (индекс 3): находим вхождение firstElement и удаляем от него до конца ===
    if (isset($row[3]) && $firstElement !== '') {
        $pos = mb_strpos($row[3], $firstElement);
        if ($pos !== false) {
            $row[3] = trim(mb_substr($row[3], 0, $pos));
        }
    }

    // === Столбец 3: удаляем | в конце и заменяем | на " > " ===
    if (isset($row[3])) {
        $row[3] = rtrim($row[3], '| ');              // убираем | и пробелы в конце
        $row[3] = str_replace('|', ' > ', $row[3]);  // | → " > "
    }

    // === Столбец 5 (индекс 5): генерация username ===
    $original = isset($row[5]) ? trim($row[5]) : '';

    // Пробелы → подчёркивания
    $processed = str_replace(' ', '_', $original);

    // Транслитерация спецсимволов в латиницу
    $translit = [
        // Турецкие
        'Ç' => 'C',
        'ç' => 'c',
        'Ğ' => 'G',
        'ğ' => 'g',
        'İ' => 'I',
        'ı' => 'i',
        'Ö' => 'O',
        'ö' => 'o',
        'Ş' => 'S',
        'ş' => 's',
        'Ü' => 'U',
        'ü' => 'u',
        // Немецкие
        'Ä' => 'A',
        'ä' => 'a',
        'ß' => 'ss',
        // Французские / общие
        'À' => 'A',
        'à' => 'a',
        'Â' => 'A',
        'â' => 'a',
        'É' => 'E',
        'é' => 'e',
        'È' => 'E',
        'è' => 'e',
        'Ê' => 'E',
        'ê' => 'e',
        'Ë' => 'E',
        'ë' => 'e',
        'Î' => 'I',
        'î' => 'i',
        'Ï' => 'I',
        'ï' => 'i',
        'Ô' => 'O',
        'ô' => 'o',
        'Ù' => 'U',
        'ù' => 'u',
        'Û' => 'U',
        'û' => 'u',
        'Ÿ' => 'Y',
        'ÿ' => 'y',
        'Æ' => 'AE',
        'æ' => 'ae',
        'Œ' => 'OE',
        'œ' => 'oe',
        // Испанские / португальские
        'Ñ' => 'N',
        'ñ' => 'n',
        'Ã' => 'A',
        'ã' => 'a',
        'Õ' => 'O',
        'õ' => 'o',
        // Скандинавские
        'Å' => 'A',
        'å' => 'a',
        'Ø' => 'O',
        'ø' => 'o',
        // Польские / чешские / др.
        'Ł' => 'L',
        'ł' => 'l',
        'Ń' => 'N',
        'ń' => 'n',
        'Ś' => 'S',
        'ś' => 's',
        'Ź' => 'Z',
        'ź' => 'z',
        'Ż' => 'Z',
        'ż' => 'z',
        'Č' => 'C',
        'č' => 'c',
        'Ď' => 'D',
        'ď' => 'd',
        'Ň' => 'N',
        'ň' => 'n',
        'Ř' => 'R',
        'ř' => 'r',
        'Š' => 'S',
        'š' => 's',
        'Ť' => 'T',
        'ť' => 't',
        'Ž' => 'Z',
        'ž' => 'z',
        // Кириллица → латиница
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'Yo',
        'Ж' => 'Zh',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'Y',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'Kh',
        'Ц' => 'Ts',
        'Ч' => 'Ch',
        'Ш' => 'Sh',
        'Щ' => 'Shch',
        'Ъ' => '',
        'Ы' => 'Y',
        'Ь' => '',
        'Э' => 'E',
        'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'kh',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'shch',
        'ъ' => '',
        'ы' => 'y',
        'ь' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
    ];
    $processed = strtr($processed, $translit);

    // Оставляем только латинские буквы и подчёркивания
    $processed = preg_replace('/[^a-zA-Z_]/', '', $processed);

    // Приводим к нижнему регистру
    $processed = strtolower($processed);

    // === Формируем выходную строку ===
    // Столбцы 0-6 (индексы 0-6) без изменений
    $newRow = array_slice($row, 0, 7);

    // Столбец 7 (индекс 7): username
    $newRow[] = $processed;

    // Столбец 8 (индекс 8): email
    $newRow[] = $processed . '@adshelppro.com';

    // Столбцы 9+ (индекс 9+): изображения из исходного столбца 7, разбитые по |
    $imgValue = isset($row[7]) ? trim($row[7]) : '';
    if ($imgValue !== '') {
        $images = explode('|', $imgValue);
        foreach ($images as $img) {
            $newRow[] = trim($img);
        }
        // Дополняем пустыми ячейками до maxImages (чтобы CSV был ровный)
        while (count($newRow) < 9 + $maxImages) {
            $newRow[] = '';
        }
    } else {
        // Нет изображений — заполняем пустыми ячейками
        for ($i = 0; $i < $maxImages; $i++) {
            $newRow[] = '';
        }
    }

    fputcsv($outputHandle, $newRow);
}

fclose($inputHandle);
fclose($outputHandle);

echo "Готово! Обработано строк: $lineNumber\n";
echo "Пропущено (пустой столбец 5 или 6): $skipped\n";
echo "Макс. изображений в строке: $maxImages\n";
echo "Результат записан в: $outputFile\n";
