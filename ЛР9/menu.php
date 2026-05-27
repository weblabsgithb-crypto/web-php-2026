<?php
// menu.php — Модуль формирования главного меню

// Параметры подключения к БД
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'labuser');
define('DB_PASS', 'labpass');
define('DB_NAME', 'address_book');

// Если параметр 'p' не задан или некорректен — устанавливаем 'viewer'
$valid_pages = ['viewer', 'add', 'edit', 'delete'];
if (!isset($_GET['p']) || !in_array($_GET['p'], $valid_pages)) {
    $_GET['p'] = 'viewer';
}

$current_page = $_GET['p'];

// Формируем меню
$menu_html = '<div id="menu">';

// Основные пункты меню
$menu_items = [
    'viewer' => 'Просмотр',
    'add'    => 'Добавление записи',
    'edit'   => 'Редактирование записи',
    'delete' => 'Удаление записи'
];

foreach ($menu_items as $page => $label) {
    $selected = ($current_page === $page) ? ' class="selected"' : '';
    $menu_html .= '<a href="?p=' . $page . '"' . $selected . '>' . $label . '</a>';
}

// Подменю для "Просмотр"
if ($current_page === 'viewer') {
    $menu_html .= '<div id="submenu">';

    $sort_options = [
        'byid'   => 'По умолчанию',
        'surname' => 'По фамилии',
        'birth'  => 'По дате рождения'
    ];

    $current_sort = isset($_GET['sort']) && in_array($_GET['sort'], ['byid', 'surname', 'birth'])
        ? $_GET['sort'] : 'byid';

    foreach ($sort_options as $sort => $label) {
        $selected = ($current_sort === $sort) ? ' class="selected"' : '';
        $menu_html .= '<a href="?p=viewer&sort=' . $sort . '"' . $selected . '>' . $label . '</a>';
    }

    $menu_html .= '</div>';
}

$menu_html .= '</div>';

// Возвращает HTML-код главного меню
function getMenu()
{
    global $menu_html;
    return $menu_html;
}
