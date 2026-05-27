<?php
/**
 * viewer.php — Модуль для вывода содержимого базы данных в браузер
 * Функция getFriendsList($type, $page) возвращает HTML-код таблицы с пагинацией
 */

/**
 * Формирует HTML-код таблицы контактов с пагинацией
 * @param string $type — тип сортировки: 'byid', 'surname', 'birth'
 * @param int $page — номер страницы пагинации (0-based)
 * @return string — HTML-код
 */
function getFriendsList($type, $page)
{
    // Подключение к БД
    $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (mysqli_connect_errno()) {
        return '<p class="msg-error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</p>';
    }

    mysqli_set_charset($mysqli, 'utf8mb4');

    // Определяем общее количество записей
    $sql_res = mysqli_query($mysqli, 'SELECT COUNT(*) AS cnt FROM contacts');
    if (!$sql_res || mysqli_errno($mysqli)) {
        mysqli_close($mysqli);
        return '<p class="msg-error">Ошибка базы данных</p>';
    }

    $row = mysqli_fetch_assoc($sql_res);
    $TOTAL = (int)$row['cnt'];

    if ($TOTAL === 0) {
        mysqli_close($mysqli);
        return '<p class="msg-error">В таблице нет данных</p>';
    }

    // Параметры пагинации
    $PER_PAGE = 10;
    $PAGES = (int)ceil($TOTAL / $PER_PAGE);
    if ($page >= $PAGES) {
        $page = $PAGES - 1;
    }
    if ($page < 0) {
        $page = 0;
    }
    $offset = $page * $PER_PAGE;

    // Определяем сортировку
    switch ($type) {
        case 'surname':
            $order = 'ORDER BY surname ASC, name ASC';
            break;
        case 'birth':
            $order = 'ORDER BY birth_date ASC';
            break;
        case 'byid':
        default:
            $order = 'ORDER BY id ASC';
            break;
    }

    // Выборка записей
    $sql = 'SELECT * FROM contacts ' . $order . ' LIMIT ' . $PER_PAGE . ' OFFSET ' . $offset;
    $sql_res = mysqli_query($mysqli, $sql);
    if (!$sql_res) {
        mysqli_close($mysqli);
        return '<p class="msg-error">Ошибка выполнения запроса</p>';
    }

    // Формируем таблицу
    $ret = '<table class="contacts-table">';
    $ret .= '<tr>';
    $ret .= '<th>№</th>';
    $ret .= '<th>Фамилия</th>';
    $ret .= '<th>Имя</th>';
    $ret .= '<th>Отчество</th>';
    $ret .= '<th>Пол</th>';
    $ret .= '<th>Дата рождения</th>';
    $ret .= '<th>Телефон</th>';
    $ret .= '<th>Адрес</th>';
    $ret .= '<th>E-mail</th>';
    $ret .= '<th>Комментарий</th>';
    $ret .= '</tr>';

    $num = $offset + 1;
    while ($row = mysqli_fetch_assoc($sql_res)) {
        $ret .= '<tr>';
        $ret .= '<td>' . $num . '</td>';
        $ret .= '<td>' . htmlspecialchars($row['surname']) . '</td>';
        $ret .= '<td>' . htmlspecialchars($row['name']) . '</td>';
        $ret .= '<td>' . htmlspecialchars($row['patronymic']) . '</td>';
        $ret .= '<td>' . ($row['gender'] === 'male' ? 'М' : 'Ж') . '</td>';
        $ret .= '<td>' . ($row['birth_date'] ? date('d.m.Y', strtotime($row['birth_date'])) : '—') . '</td>';
        $ret .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
        $ret .= '<td>' . htmlspecialchars($row['address']) . '</td>';
        $ret .= '<td>' . htmlspecialchars($row['email']) . '</td>';
        $ret .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
        $ret .= '</tr>';
        $num++;
    }

    $ret .= '</table>';

    // Пагинация
    if ($PAGES > 1) {
        $ret .= '<div id="pages">';
        for ($i = 0; $i < $PAGES; $i++) {
            if ($i != $page) {
                $ret .= '<a href="?p=viewer&sort=' . $type . '&pg=' . $i . '">' . ($i + 1) . '</a>';
            } else {
                $ret .= '<span>' . ($i + 1) . '</span>';
            }
        }
        $ret .= '</div>';
    }

    mysqli_close($mysqli);
    return $ret;
}
