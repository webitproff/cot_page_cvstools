<?php
require_once 'dbconfig.php';
require_once 'handlemessages.php';

// Глобальные переменные
$selected_table = !empty($_GET['table']) ? $_GET['table'] : 'x92374_pages';
$table_columns_query = "SHOW COLUMNS FROM {$selected_table}";
$table_select_query = "SELECT * FROM {$selected_table}";

// Получаем список колонок и данные из выбранной таблицы
$cols = [];
$columns_data = $db->query($table_columns_query);
while ($row = $columns_data->fetch_assoc()) {
    $cols[] = $row['Field'];
}

$select_data = $db->query($table_select_query);

// Выбор данных из таблицы по page_id
$query_row = "SELECT page_id FROM x92374_pages";
$result_row = mysqli_query($db, $query_row);

// Настройки для логирования ошибок
ini_set('log_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// Проверка параметров в URL для отображения сообщений
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $inserted = isset($_GET['inserted']) ? $_GET['inserted'] : 0;  // Количество добавленных строк
    $updated = isset($_GET['updated']) ? $_GET['updated'] : 0;      // Количество обновленных строк
    $processed = isset($_GET['processed']) ? $_GET['processed'] : 0;  // Количество обновленных строк

    // Сообщение в зависимости от статуса
    if ($status == 'succ') {
        echo "<p>Импорт завершен успешно!</p>";
        echo "<p>Добавлено строк: $inserted</p>";
        echo "<p>Обновлено строк: $updated</p>";
        echo "<p>Всего строк: $processed</p>";
    } elseif ($status == 'err') {
        echo "<p>Произошла ошибка при импорте.</p>";
    } elseif ($status == 'invalid_file') {
        echo "<p>Неверный формат файла.</p>";
    }
}
?>

<!-- HTML документ -->
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test CSV MySQL Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <hr>

    <!-- Блок сообщений об ошибках/успехах -->
    <?php if (!empty($statusMsg)) : ?>
        <div class="col-xs-12">
            <div class="alert <?= $statusType ?>"><?= htmlspecialchars($statusMsg) ?></div>
        </div>
    <?php endif; ?>

    <hr>

    <!-- Блок для отображения списка файлов -->
    <ol>
        <?php
        // Чтение и вывод содержимого файла
        $file_contents = file_get_contents('fileslist.txt');
        if ($file_contents) {
            foreach (array_filter(explode("\n", $file_contents), 'strlen') as $value) {
                echo "<li>" . htmlspecialchars($value) . "</li>";
            }
        }
        ?>
    </ol>

    <!-- Контейнер для корректного отображения содержимого -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <!-- Форма для выбора таблицы -->
                <form action="index.php" id="tableForm" method="GET">
                    <legend>Выберите таблицу</legend>
                    <div>
                        <input type="radio" id="table_user" name="table" value="x92374_pages" checked />
                        <label for="user_radio">Статьи</label>
                    </div>
                    <input type="submit" class="btn btn-dark" value="Выбрать">
                </form>

                <!-- Кнопки для появления форм импорта и экспорта -->
                <div class="pt-2 pb-2">
                    <a href="javascript:void(0)" class="btn btn-success" onclick="toggleElVisibility('importFrm');">
                        <i class="plus"></i> Импорт
                    </a>
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="toggleElVisibility('exportFrm');">
                        <i class="exp"></i> Экспорт
                    </a>
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="toggleElVisibility('updateimportFrm');">
                        <i class="exp"></i> updateimportForm
                    </a>
                </div>
            </div>

            <!-- Форма для экспорта -->
            <div class="col-md-12 p-3 d-none" id="exportFrm">
                <form action="exportcsvfile.php" method="POST" name="exportFrm" enctype="multipart/form-data">
                    <div>
                        <input type="radio" name="table" id="radio_x92374_pages" value="x92374_pages" checked />
                        <label for="radio_users">Статьи</label>
                    </div>
                    <input type="submit" class="btn btn-primary" name="exportSubmit" value="Экспорт">
                </form>
            </div>

            <!-- Форма для импорта -->
            <div class="col-md-12 p-3 d-none" id="importFrm">
                <form action="importcsvfile.php" method="post" name="importForm" enctype="multipart/form-data">
                    <input type="file" name="file" class="pb-2">
                    <div>
                        <input type="radio" name="table" id="radio_x92374_pages" value="x92374_pages" checked />
                        <label for="radio_users">Статьи</label>
                    </div>
                    <input type="submit" class="btn btn-success" name="importSubmit" value="Импорт">
                </form>
            </div>

            <!-- Форма для обновленного импорта -->
            <hr>
            <form action="updateimportcsvfile.php" method="post" enctype="multipart/form-data">
                <input type="file" name="file" required>
                <input type="submit" name="updateimportSubmit" value="Импортировать CSV">
            </form>

            <!-- Результирующая таблица -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-wrap">
                    <thead class="thead-dark">
                        <tr>
                            <?php foreach ($cols as $value) {
                                echo "<th>" . htmlspecialchars($value) . "</th>";
                            } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($select_data->num_rows > 0) : ?>
                            <?php while ($row = $select_data->fetch_assoc()) : ?>
                                <tr>
                                    <?php foreach ($cols as $value) {
                                        echo "<td>" . htmlspecialchars($row[$value]) . "</td>";
                                    } ?>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td class="text-center" colspan="<?= count($cols) ?>">No rows found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Скрипт для добавления динамики форм -->
    <script>
        const toggleElVisibility = (selectorId) => document.getElementById(selectorId).classList.toggle('d-none');
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
