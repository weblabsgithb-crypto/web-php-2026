<?php
// index.php — Главный файл записной книжки

// Подключаем модуль меню (всегда)
require 'menu.php';

// Определяем текущую страницу
$current_page = $_GET['p'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Беберин Всеволод Викторович, Группа 241-35295, ЛР9 — Записная книжка</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Беберин Всеволод Викторович, Группа 241-352</h1>
    <p>Лабораторная работа №9. Записная книжка.</p>
</header>

<?php
// Выводим меню
echo getMenu();
?>

<div class="container">
<?php
// Подключаем модуль контента в зависимости от параметра 'p'
if ($current_page === 'viewer') {
    include 'viewer.php';

    // Определяем параметры сортировки и пагинации
    if (!isset($_GET['pg']) || (int)$_GET['pg'] < 0) {
        $_GET['pg'] = 0;
    }

    $valid_sorts = ['byid', 'surname', 'birth'];
    if (!isset($_GET['sort']) || !in_array($_GET['sort'], $valid_sorts)) {
        $_GET['sort'] = 'byid';
    }

    // Выводим таблицу контактов
    echo getFriendsList($_GET['sort'], (int)$_GET['pg']);

} elseif ($current_page === 'add') {
    include 'add.php';

} elseif ($current_page === 'edit') {
    include 'edit.php';

} elseif ($current_page === 'delete') {
    include 'delete.php';

} else {
    echo '<p class="msg-error">Страница не найдена</p>';
}
?>
</div>

<footer>
    Лабораторная работа №9. Записная книжка. <?php echo date('d.m.Y H:i:s'); ?>
</footer>

</body>
</html>
