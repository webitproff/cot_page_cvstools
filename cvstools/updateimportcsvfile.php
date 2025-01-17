<?php
// Включаем буферизацию вывода, чтобы избежать ошибки с заголовками
ob_start();

require_once 'dbconfig.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка соединения с базой данных
if (!$db) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

if (!empty($_FILES['file']['name'])) {
    echo 'Имя файла: ' . $_FILES['file']['name'] . '<br>';
    echo 'Тип файла: ' . $_FILES['file']['type'] . '<br>';
    echo 'Размер файла: ' . $_FILES['file']['size'] . ' байт<br>';
    echo 'Временный путь: ' . $_FILES['file']['tmp_name'] . '<br>';
} else {
    echo 'Файл не был загружен.<br>';
}

$insertCount = 0; // Счетчик добавленных строк
$updateCount = 0; // Счетчик обновленных строк

if (isset($_POST['updateimportSubmit'])) {
    // Допустимые форматы файлов
    $csvMimes = [
        'text/x-comma-separated-values',
        'text/comma-separated-values',
        'application/octet-stream',
        'application/vnd.ms-excel',
        'application/x-csv',
        'text/x-csv',
        'text/csv',
        'application/csv',
        'application/excel',
        'application/vnd.msexcel',
        'text/plain'
    ];

    // Проверка на пустоту и формат файлов
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes) && is_uploaded_file($_FILES['file']['tmp_name'])) {
        // Открытие потока чтения файла
        $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

        // Пропуск первой строки (если она содержит заголовки)
        fgetcsv($csvFile);

        // Подготовленные запросы для проверки существования страницы, вставки и обновления
        $selectQuery = $db->prepare("SELECT page_id FROM x92374_pages WHERE page_id = ?");
        if (!$selectQuery) {
            die("Ошибка подготовки запроса SELECT: " . $db->error);
        }

        $insertQuery = $db->prepare(
            "INSERT INTO `x92374_pages` (
                `page_id`, `page_alias`, `page_state`, `page_cat`, `page_title`, `page_desc`, `page_keywords`, 
                `page_metatitle`, `page_metadesc`, `page_text`, `page_parser`, `page_author`, `page_ownerid`, 
                `page_date`, `page_begin`, `page_expire`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        if (!$insertQuery) {
            die("Ошибка подготовки запроса INSERT: " . $db->error);
        }

        $updateQuery = $db->prepare(
            "UPDATE x92374_pages SET
                `page_alias` = ?, `page_state` = ?, `page_cat` = ?, `page_title` = ?, `page_desc` = ?, 
                `page_keywords` = ?, `page_metatitle` = ?, `page_metadesc` = ?, `page_text` = ?, 
                `page_parser` = ?, `page_author` = ?, `page_ownerid` = ?, `page_date` = ?, `page_begin` = ?, 
                `page_expire` = ?
            WHERE page_id = ?"
        );
        if (!$updateQuery) {
            die("Ошибка подготовки запроса UPDATE: " . $db->error);
        }

        // Чтение файла построчно
        $linesProcessed = 0; // Счетчик обработанных строк
        while (($line_field = fgetcsv($csvFile, 10000, ",")) !== FALSE) {
            // Пропускаем пустые строки
            if (empty(array_filter($line_field))) {
                continue;
            }

            $linesProcessed++; // Увеличиваем счетчик строк
            // Распаковка данных из CSV строки в переменные
            list(
                $page_id, $page_alias, $page_state, $page_cat, $page_title, $page_desc, $page_keywords, 
                $page_metatitle, $page_metadesc, $page_text, $page_parser, $page_author, $page_ownerid, 
                $page_date, $page_begin, $page_expire
            ) = $line_field;

            // Проверка существования страницы в базе данных
            $selectQuery->bind_param('s', $page_id);
            $selectQuery->execute();
            $result = $selectQuery->get_result();

            // Если страница существует, обновляем ее, если нет - вставляем новую
            if ($result->num_rows > 0) {
                // Обновление записи
                $updateQuery->bind_param(
                    'ssssssssssssssss', // 16 параметров
                    $page_alias, $page_state, $page_cat, $page_title, $page_desc, $page_keywords, 
                    $page_metatitle, $page_metadesc, $page_text, $page_parser, $page_author, $page_ownerid, 
                    $page_date, $page_begin, $page_expire, $page_id
                );
                if ($updateQuery->execute()) {
                    $updateCount++; // Увеличиваем счетчик обновленных строк
                } else {
                    echo 'Ошибка выполнения UPDATE запроса: ' . $updateQuery->error;
                }
            } else {
                // Вставка новой записи
                $insertQuery->bind_param(
                    'ssssssssssssssss', // 16 параметров
                    $page_id, $page_alias, $page_state, $page_cat, $page_title, $page_desc, $page_keywords, 
                    $page_metatitle, $page_metadesc, $page_text, $page_parser, $page_author, $page_ownerid, 
                    $page_date, $page_begin, $page_expire
                );
                if ($insertQuery->execute()) {
                    $insertCount++; // Увеличиваем счетчик добавленных строк
                } else {
                    echo 'Ошибка выполнения INSERT запроса: ' . $insertQuery->error;
                }
            }
        }

        // Закрытие файла
        fclose($csvFile);

        // Запись в лог загруженных файлов
        file_put_contents('fileslist.txt', $_FILES['file']['name'] . PHP_EOL, FILE_APPEND);
        
        // Передача параметров через URL
        $qstring = "?status=succ&inserted={$insertCount}&updated={$updateCount}&processed={$linesProcessed}";

    } else {
        $qstring = '?status=err';
    }
} else {
    $qstring = '?status=invalid_file';
}

header("Location: index.php" . $qstring); // Перенаправление
exit;
