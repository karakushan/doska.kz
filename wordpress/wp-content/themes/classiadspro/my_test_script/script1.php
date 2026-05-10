<?php

/**
 * Скрипт обработки CSV:
 * - Каждый 7-й столбец (индексы 0, 7, 14, 21, ...) содержит строку с разделителем |
 * - Оставляет только первый элемент из этой строки
 * - Остальные 6 столбцов между ними остаются без изменений
 * 
 * Использование: php process_csv.php input.csv output.csv
 */



$inputFile  = "sahibinden.com_result.csv";
$outputFile = "output_sahibinden.com_result.csv";

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

    foreach ($row as $colIndex => &$value) {
        // Каждый 7-й столбец начиная с 0: индексы 0, 7, 14, 21, ...
        if ($colIndex % 7 === 0) {
            $parts = explode('|', $value);
            $value = trim($parts[0]);
        }
    }
    unset($value);

    fputcsv($outputHandle, $row);
}

fclose($inputHandle);
fclose($outputHandle);

echo "Готово! Обработано строк: $lineNumber\n";
echo "Результат записан в: $outputFile\n";
