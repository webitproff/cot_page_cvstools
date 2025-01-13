<?php
// Импорт файлов
require_once 'dbconfig.php';
require_once 'handlemessages.php';

// Глобальные переменные
$selected_table = !empty($_GET['table']) ? $_GET['table'] : "x92374_pages";
$table_columns_query = "SHOW COLUMNS FROM {$selected_table}";
$table_select_query = "SELECT * FROM {$selected_table}";

$columns_data = $db->query($table_columns_query);
while ($row = $columns_data->fetch_assoc()) $cols[] = $row['Field'];
$select_data = $db->query($table_select_query);
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
    <!-- Блок сообщений -->
    <?php if (!empty($statusMsg)) : ?>
        <div class="col-xs-12">
            <div class="alert <?= $statusType ?>"><?= $statusMsg ?></div>
        </div>
    <?php endif ?>

    <ol>
        <?php foreach (array_filter(explode("\n", file_get_contents('fileslist.txt')), 'strlen') as $value) {
            echo "<li>" . $value . "</li>";
        } ?>
    </ol>

    <!-- Контейнер для корректного отображения содержимого -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Блок с выбором таблицы с БД для визуализации -->
                <div>
                    <form action="index.php" id="tableForm" method="GET">
                        <legend>Выберите таблицу</legend>
                        <!--  <div>
                            <input type="radio" id="table_department" name="table" value="departments" />
                            <label for="department_radio">Подразделения</label>
                        </div>  -->
                        <div>
                            <input type="radio" id="table_user" name="table" value="x92374_pages" />
                            <label for="user_radio">Статьи</label>
                        </div>
                        <input type="submit" class="btn btn-dark" value="Выбрать">
                    </form>
                </div>
                <!-- Блок с кнопками для появления форм ИМПОРТА и ЭКСПОРТА -->
                <div class="pt-2 pb-2">
                    <a href="javascript:void(0)" class="btn btn-success" onclick="toggleElVisibility('importFrm');"><i class="plus"></i>Импорт</a>
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="toggleElVisibility('exportFrm');"><i class="exp"></i>Экспорт</a>
                </div>
            </div>
            <!-- Форма для экспорта -->
            <div class="col-md-12 p-3 d-none" id="exportFrm">
                <form action="exportcsvfile.php" method="POST" name="exportFrm" enctype="multipart/form-data">
                    <div>
                        <input type="radio" name="table" id="radio_x92374_pages" value="x92374_pages" checked />
                        <label for="radio_users">Статьи</label>
                    </div>
                    <!--<div>
                        <input type="radio" name="table" id="radio_departments" value="departments" />
                        <label for="radio_departments">Подразделения</label>
                    </div> -->
                    <input type="submit" class="btn btn-primary" name="exportSubmit" value="Экспорт">
                </form>
            </div>
            <!-- Форма для импорта -->
            <div class="col-md-12 p-3 d-none" id="importFrm">
                <form action="importcsvfile.php" method="post" name="importForm" enctype="multipart/form-data">
                    <input type="file" name="file" class="pb-2">
                    <div>
                        <input type="radio" name="table"  id="radio_x92374_pages" value="x92374_pages" checked />
                        <label for="radio_users">Статьи</label>
                    </div>
                   <!-- <div>
                        <input type="radio" name="table" id="radio_departments" value="departments" />
                        <label for="radio_departments">Подразделения</label>
                    </div> -->
                    <input type="submit" class="btn btn-success" name="importSubmit" value="Импорт">
                </form>
            </div>
			<div class="table-responsive">

            <!-- Результирующая таблица -->
            <table class="table table-striped table-bordered text-wrap">
                <thead class="thead-dark">
                    <tr>
                        <?php foreach ($cols as $value) echo "<th>" . $value . "</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($select_data->num_rows > 0) : ?>
                        <?php while ($row = $select_data->fetch_assoc()) : ?>
                            <tr>
                                <?php foreach ($cols as $value) echo "<td>" . $row[$value] . "</td>" ?>
                            <?php endwhile ?>
                        <?php else : ?>
                            <td class="text-center" colspan=<?= $columns_data->num_rows ?>>No rows affected</td>
                        <?php endif ?>
                            </tr>
                </tbody>
            </table>
			</div>
        </div>
    </div>

    <!-- Скрипт для добавления динамики форм -->
    <script>
        const toggleElVisibility = (selectorId) => document.getElementById(selectorId).classList.toggle('d-none');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>