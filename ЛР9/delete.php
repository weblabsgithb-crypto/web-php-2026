<?php
// delete.php — Модуль для удаления записи из базы данных

$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    echo '<p class="msg-error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</p>';
    exit();
}

mysqli_set_charset($mysqli, 'utf8mb4');

// Обработка удаления записи
$deleted_surname = '';
if (isset($_GET['delete_id']) && (int)$_GET['delete_id'] > 0) {
    $delete_id = (int)$_GET['delete_id'];

    // Получаем фамилию перед удалением
    $sql_res = mysqli_query($mysqli, 'SELECT surname FROM contacts WHERE id = ' . $delete_id . ' LIMIT 1');
    if ($sql_res && $row = mysqli_fetch_assoc($sql_res)) {
        $deleted_surname = $row['surname'];
    }

    // Удаляем запись
    mysqli_query($mysqli, 'DELETE FROM contacts WHERE id = ' . $delete_id);
}

echo '<h2>Удаление записи</h2>';

if ($deleted_surname !== '') {
    echo '<p class="msg-ok">Запись с фамилией ' . htmlspecialchars($deleted_surname) . ' удалена</p>';
}

// Выводим список ссылок на записи (фамилия + инициалы)
$sql_res = mysqli_query($mysqli, 'SELECT id, surname, name, patronymic FROM contacts ORDER BY surname ASC, name ASC');

if ($sql_res && mysqli_num_rows($sql_res) > 0) {
    echo '<div class="record-links">';
    while ($row = mysqli_fetch_assoc($sql_res)) {
        $initials = '';
        if ($row['name']) {
            $initials .= mb_strtoupper(mb_substr($row['name'], 0, 1, 'UTF-8'), 'UTF-8') . '.';
        }
        if ($row['patronymic']) {
            $initials .= mb_strtoupper(mb_substr($row['patronymic'], 0, 1, 'UTF-8'), 'UTF-8') . '.';
        }

        $display_name = htmlspecialchars($row['surname']) . ' ' . $initials;
        echo '<a href="?p=delete&delete_id=' . $row['id'] . '" onclick="return confirm(\'Удалить запись: ' . addslashes($row['surname']) . ' ' . addslashes($row['name']) . '?\');">' . $display_name . '</a>';
    }
    echo '</div>';
} else {
    echo '<p class="msg-error">Записей пока нет</p>';
}

mysqli_close($mysqli);
