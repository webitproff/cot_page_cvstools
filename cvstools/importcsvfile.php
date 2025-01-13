<?php
require_once 'dbconfig.php';

if (isset($_POST['importSubmit'])) {
    // Допустимые форматы файлов
    $csvMimes = array(
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
    );

    // Проверка на пустоту и формат файлов
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            // Открытие потока чтения файла
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Пропуск первой строки
            fgetcsv($csvFile);
            // Чтение файла построчно
			
            while (($line_field = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
                $prevQuery = null;
                $queryInsert = null;
                if ($_POST['table'] == 'x92374_pages') {
                    //if (count($line_field) != 2) break;
                    $page_id = $line_field[0];
                    $page_alias = $line_field[1];
                    $page_state = $line_field[2];
                    $page_cat = $line_field[3];
                    $page_title = $line_field[4];
                    $page_desc = $line_field[5];
                    $page_keywords = $line_field[6];
                    $page_metatitle = $line_field[7];
                    $page_metadesc = $line_field[8];
                    $page_text = $line_field[9];
                    $page_parser = $line_field[10];
                    $page_author = $line_field[11];
                    $page_ownerid = $line_field[12];
                    $page_date = $line_field[13];
                    $page_begin = $line_field[14];
                    $page_expire = $line_field[15];
                    $page_updated = $line_field[16];
                    $page_file = $line_field[17];
                    $page_url = $line_field[18];
                    $page_size = $line_field[19];
                    $page_count = $line_field[20];
                    $page_filecount = $line_field[21];
                    $prevQuery = "SELECT page_id FROM x92374_pages WHERE page_id = '" . $page_id . "'";
                    $queryInsert = "INSERT INTO `x92374_pages` (
						`page_id`, 
						`page_alias`, 
						`page_state`, 
						`page_cat`, 
						`page_title`, 
						`page_desc`, 
						`page_keywords`, 
						`page_metatitle`, 
						`page_metadesc`, 
						`page_text`, 
						`page_parser`, 
						`page_author`, 
						`page_ownerid`, 
						`page_date`, 
						`page_begin`, 
						`page_expire`, 
						`page_updated`, 
						`page_file`, 
						`page_url`, 
						`page_size`, 
						`page_count`, 
						`page_filecount`) 
                        VALUES (
						'" . $page_id . "',
				   '" . $page_alias . "',
				   '" . $page_state . "',
				   '" . $page_cat . "',
				   '" . $page_title . "',
				   '" . $page_desc . "',
				   '" . $page_keywords . "',
				   '" . $page_metatitle . "',
				   '" . $page_metadesc . "',
				   '" . $page_text . "',
				   '" . $page_parser . "',
				   '" . $page_author . "',
				   '" . $page_ownerid . "',
				   '" . $page_date . "',
				   '" . $page_begin . "',
				   '" . $page_expire . "',
				   '" . $page_updated . "',
				   '" . $page_file . "',
				   '" . $page_url . "',
				   '" . $page_size . "',
				   '" . $page_count . "',
				   '" . $page_filecount . "')";
                }/*  else {
                    if (count($line_field) != 3) break;
                    $xml_id = $line_field[0];
                    $parent_xml_id = $line_field[1];
                    $name_department = $line_field[2];
                    $prevQuery = "SELECT XML_ID FROM departments WHERE XML_ID = '" . $xml_id . "'";
                    $queryInsert = "INSERT INTO departments (
                        XML_ID,
                        PARENT_XML_ID, 
                        NAME_DEPARTMENT) VALUES ('" . $xml_id . "', '" . $parent_xml_id . "', '" . $name_department . "')";
                } */
                // Обращение к БД
                $prevResult = $db->query($prevQuery);
                if ($prevResult->num_rows != 1) {
                    $db->query($queryInsert);
                }
            }
        }
        // Конец потока чтения файла
        fclose($csvFile);
        // Установка поискового параметра
        $qstring = '?status=succ';
        // Запись в текстовый документ имена загруженных файлов без перезаписи
        file_put_contents('fileslist.txt', $_FILES['file']['name'] . PHP_EOL, FILE_APPEND);
    } else {
        $qstring = '?status=err';
    }
} else {
    $qstring = '?status=invalid_file';
}

// Переход к документу по дефолту
header("Location: index.php" . $qstring);
