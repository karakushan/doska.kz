<?php
/**
 * Добавляет "Turkish|" в начало значения второго столбца (индекс 2) CSV файла.
 * Пример: "Antalya|Alanya|Cikcilli" → "Turkish|Antalya|Alanya|Cikcilli"
 */

$input_file  = __DIR__ . '/table3.csv';       // входной файл
$output_file = __DIR__ . '/table3_fixed.csv';  // выходной файл
$column_index = 1;                              // индекс столбца (с 0)
$prefix = 'Turkish|';
$delimiter = ',';

if (!file_exists($input_file)) {
    die("Файл не найден: $input_file\n");
}

$in  = fopen($input_file, 'r');
$out = fopen($output_file, 'w');

// BOM для UTF-8
fwrite($out, "\xEF\xBB\xBF");

$row_num = 0;
$modified = 0;

while (($row = fgetcsv($in, 0, $delimiter)) !== false) {
    $row_num++;

    // Пропускаем заголовок
    if ($row_num === 1) {
        fputcsv($out, $row, $delimiter);
        continue;
    }

    if (isset($row[$column_index]) && !empty(trim($row[$column_index]))) {
        $val = trim($row[$column_index]);
        // Не добавляем если уже начинается с Turkish
        if (stripos($val, 'Turkish') !== 0) {
            $row[$column_index] = $prefix . $val;
            $modified++;
        }
    }

    fputcsv($out, $row, $delimiter);
}

fclose($in);
fclose($out);

echo "Готово! Обработано строк: $row_num, изменено: $modified\n";
echo "Результат: $output_file\n";
