<?php
// edit.php — Модуль для редактирования существующей записи базы данных

$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    echo '<p class="msg-error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</p>';
    exit();
}

mysqli_set_charset($mysqli, 'utf8mb4');

// Обработка отправки формы (изменение записи)
if (isset($_POST['button']) && $_POST['button'] === 'Изменить запись') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $surname = isset($_POST['surname']) ? trim($_POST['surname']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $patronymic = isset($_POST['patronymic']) ? trim($_POST['patronymic']) : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : 'male';
    $birth_date = isset($_POST['birth_date']) ? trim($_POST['birth_date']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    $sql = 'UPDATE contacts SET
            surname = "' . mysqli_real_escape_string($mysqli, $surname) . '",
            name = "' . mysqli_real_escape_string($mysqli, $name) . '",
            patronymic = "' . mysqli_real_escape_string($mysqli, $patronymic) . '",
            gender = "' . mysqli_real_escape_string($mysqli, $gender) . '",
            birth_date = ' . ($birth_date !== '' ? '"' . mysqli_real_escape_string($mysqli, $birth_date) . '"' : 'NULL') . ',
            phone = "' . mysqli_real_escape_string($mysqli, $phone) . '",
            address = "' . mysqli_real_escape_string($mysqli, $address) . '",
            email = "' . mysqli_real_escape_string($mysqli, $email) . '",
            comment = "' . mysqli_real_escape_string($mysqli, $comment) . '"
            WHERE id = ' . $id;

    mysqli_query($mysqli, $sql);
    echo '<p class="msg-ok">Данные изменены</p>';

    // Эмулируем переход по ссылке на изменённую запись
    $_GET['id'] = $id;
}

// Определяем текущую запись
$currentROW = [];

if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
    $sql_res = mysqli_query($mysqli, 'SELECT * FROM contacts WHERE id = ' . (int)$_GET['id'] . ' LIMIT 1');
    if ($sql_res) {
        $currentROW = mysqli_fetch_assoc($sql_res);
    }
}

// Если текущая запись не определена — берём первую
if (!$currentROW) {
    $sql_res = mysqli_query($mysqli, 'SELECT * FROM contacts ORDER BY surname ASC, name ASC LIMIT 1');
    if ($sql_res) {
        $currentROW = mysqli_fetch_assoc($sql_res);
    }
}

// Выводим список ссылок на записи (сортировка по фамилии, затем по имени)
$sql_res = mysqli_query($mysqli, 'SELECT id, surname, name FROM contacts ORDER BY surname ASC, name ASC');

echo '<h2>Редактирование записи</h2>';

if ($sql_res && mysqli_num_rows($sql_res) > 0) {
    echo '<div class="record-links">';
    while ($row = mysqli_fetch_assoc($sql_res)) {
        if ($currentROW && $currentROW['id'] == $row['id']) {
            echo '<div class="current">' . htmlspecialchars($row['surname']) . ' ' . htmlspecialchars($row['name']) . '</div>';
        } else {
            echo '<a href="?p=edit&id=' . $row['id'] . '">' . htmlspecialchars($row['surname']) . ' ' . htmlspecialchars($row['name']) . '</a>';
        }
    }
    echo '</div>';

    // Форма редактирования
    if ($currentROW) {
        echo '<div class="form-container">';
        echo '<form name="form_edit" method="post" action="?p=edit">';

        echo '<label for="surname">Фамилия:</label>';
        echo '<input type="text" id="surname" name="surname" value="' . htmlspecialchars($currentROW['surname']) . '">';

        echo '<label for="name">Имя:</label>';
        echo '<input type="text" id="name" name="name" value="' . htmlspecialchars($currentROW['name']) . '">';

        echo '<label for="patronymic">Отчество:</label>';
        echo '<input type="text" id="patronymic" name="patronymic" value="' . htmlspecialchars($currentROW['patronymic']) . '">';

        echo '<label for="gender">Пол:</label>';
        echo '<select id="gender" name="gender">';
        echo '<option value="male"' . ($currentROW['gender'] === 'male' ? ' selected' : '') . '>Мужской</option>';
        echo '<option value="female"' . ($currentROW['gender'] === 'female' ? ' selected' : '') . '>Женский</option>';
        echo '</select>';

        echo '<label for="birth_date">Дата рождения:</label>';
        echo '<input type="date" id="birth_date" name="birth_date" value="' . htmlspecialchars($currentROW['birth_date'] ?? '') . '">';

        echo '<label for="phone">Телефон:</label>';
        echo '<input type="tel" id="phone" name="phone" value="' . htmlspecialchars($currentROW['phone']) . '">';

        echo '<label for="address">Адрес:</label>';
        echo '<input type="text" id="address" name="address" value="' . htmlspecialchars($currentROW['address']) . '">';

        echo '<label for="email">E-mail:</label>';
        echo '<input type="email" id="email" name="email" value="' . htmlspecialchars($currentROW['email']) . '">';

        echo '<label for="comment">Комментарий:</label>';
        echo '<textarea id="comment" name="comment">' . htmlspecialchars($currentROW['comment'] ?? '') . '</textarea>';

        echo '<input type="hidden" name="id" value="' . $currentROW['id'] . '">';
        echo '<input type="submit" name="button" value="Изменить запись">';
        echo '</form>';
        echo '</div>';
    }
} else {
    echo '<p class="msg-error">Записей пока нет</p>';
}

mysqli_close($mysqli);
