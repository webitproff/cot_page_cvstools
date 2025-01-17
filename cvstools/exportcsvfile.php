<?php
require_once 'dbconfig.php';

// Получаем название таблицы из POST и проверяем его
$table = $_POST['table'];
if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
    die('Неверное имя таблицы');
}

// Будущее название экспортируемого файла
$filename = $table . "_" . date('Y-m-d') . ".csv";
$delimiter = ";"; // разделитель

// Получение столбцов таблицы через SHOW COLUMNS
$query = "SHOW COLUMNS FROM `$table`";
$result = $db->query($query);

// Проверяем на успешность выполнения запроса
if (!$result) {
    die('Ошибка при получении столбцов таблицы');
}

// Получаем имена столбцов
$fields = [];
while ($row = $result->fetch_assoc()) {
    $fields[] = $row['Field'];
}

// Открытие потока записи в память
$f = fopen('php://memory', 'w');

// Запись первой строки столбцов
fputcsv($f, $fields, $delimiter);

// Запрос данных из таблицы
$query = "SELECT * FROM `$table`";
$result = $db->query($query);

// Проверка на наличие данных
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Получаем значения данных
        $lineData = array_values($row);
        fputcsv($f, $lineData, $delimiter);
    }
}

// Индикатор позиции чтения файла
fseek($f, 0);

// Установка правильных заголовков для скачивания CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Отправка файла пользователю
fpassthru($f);
exit();

