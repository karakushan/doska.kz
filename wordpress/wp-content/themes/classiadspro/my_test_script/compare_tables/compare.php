<?php

$file1 = 'output_sahibinden.com_result.csv';   // Путь к первой таблице
$file2 = 'id_sku_many_image_output_output_output_output_sahibinden.com_result.csv';   // Путь ко второй таблице
$fileOut = 'table3.csv';  // Путь к результирующей таблице

$delimiter = ',';         // Разделитель CSV (поменяйте на ',' если нужно)
$enclosure = '"';         // Символ обрамления
// =====================

// --- Чтение таблицы 1: сохраняем ВСЕ строки ---
$table1 = [];
if (($handle = fopen($file1, 'r')) === false) {
    die("Ошибка: не удалось открыть файл «{$file1}»\n");
}
while (($row = fgetcsv($handle, 0, $delimiter, $enclosure)) !== false) {
    if (count($row) === 0) continue;
    $table1[] = $row;
}
fclose($handle);

// --- Чтение таблицы 2: сохраняем полные строки, ключ — столбец 0 ---
$table2Rows = [];
if (($handle = fopen($file2, 'r')) === false) {
    die("Ошибка: не удалось открыть файл «{$file2}»\n");
}
while (($row = fgetcsv($handle, 0, $delimiter, $enclosure)) !== false) {
    if (count($row) === 0) continue;
    $key = trim($row[0]);
    $table2Rows[$key] = $row; // если дубликаты — берётся последняя строка
}
fclose($handle);

// --- Сравнение и запись результата ---
$outHandle = fopen($fileOut, 'w');
if ($outHandle === false) {
    die("Ошибка: не удалось создать файл «{$fileOut}»\n");
}

$matchCount = 0;
foreach ($table1 as $row1) {
    $key = trim($row1[0]);
    if (isset($table2Rows[$key])) {
        $row2 = $table2Rows[$key];

        // Собираем строку результата:
        // [0] совпавший ключ
        // [1] столбец 2 из таблицы 1
        // [2…15] столбцы 1…14 из таблицы 2
        $outRow = [];
        $outRow[] = $key;
        $outRow[] = isset($row1[2]) ? $row1[2] : '';

        for ($i = 1; $i <= 14; $i++) {
            $outRow[] = isset($row2[$i]) ? $row2[$i] : '';
        }

        fputcsv($outHandle, $outRow, $delimiter, $enclosure);
        $matchCount++;
    }
}
fclose($outHandle);

echo "Готово! Найдено совпадений: {$matchCount}\n";
echo "Результат записан в: {$fileOut}\n";
