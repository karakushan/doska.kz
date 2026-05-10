<?php

/**
 * Скрипт генерации SKU для CSV:
 * - Читает входной CSV
 * - Для каждой строки генерирует уникальный SKU по текущей дате/времени
 *   до миллисекунд (формат: SKU-YYYYMMDD-HHiiss-mmm)
 * - Записывает SKU в столбец 14 (индекс 13, считая с 0)
 * - Остальные столбцы не изменяются
 *
 * Использование: php script_sku.php
 */

$inputFile  = "output_output_output_output_sahibinden.com_result.csv";
$outputFile = "sku_output_output_output_output_sahibinden.com_result.csv";

if (!file_exists($inputFile)) {
    echo "Ошибка: файл '$inputFile' не найден.\n";
    exit(1);
}

$inputHandle  = fopen($inputFile, 'r');
$outputHandle = fopen($outputFile, 'w');

if (!$inputHandle || !$outputHandle) {
    echo "Ошибка: не удалось открыть файл(ы).\n";
    exit(1);
}

$lineNumber = 0;

while (($row = fgetcsv($inputHandle)) !== false) {
    $lineNumber++;

    // Генерируем SKU по текущей дате/времени до миллисекунд
    // microtime(true) даёт float: секунды.миллисекунды
    $mt  = microtime(true);
    $ms  = sprintf('%03d', (int)(($mt - floor($mt)) * 1000)); // миллисекунды 000-999
    $sku = 'SKU-' . date('Ymd-His', (int)$mt) . '-' . $ms;

    // Убеждаемся, что строка имеет достаточно столбцов до индекса 13
    while (count($row) < 14) {
        $row[] = '';
    }

    // Записываем SKU в столбец 14 (индекс 13)
    $row[14] = $sku;

    fputcsv($outputHandle, $row);

    echo "Строка $lineNumber: SKU = $sku\n";

    // Микропауза чтобы гарантировать уникальность миллисекунд между строками
    usleep(1000); // 1 мс
}

fclose($inputHandle);
fclose($outputHandle);

echo "\nГотово! Обработано строк: $lineNumber\n";
echo "Результат записан в: $outputFile\n";
