<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $page_title = "Беберин Всеволод Викторович, Группа 241-352, ЛР4 — Пользовательские функции. Вывод таблиц."; ?>
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Шапка -->
<header>
    <img src="img/logo.png" alt="Логотип университета" class="logo">
    <div class="header-info">
        <h1>Беберин Всеволод Викторович, Группа 241-352</h1>
        <p>Лабораторная работа №4. Пользовательские функции. Вывод таблиц.</p>
    </div>
</header>

<!-- Основной контент -->
<main>
    <?php
    // 1. Инициализация переменных
    $columns_count = 4;  // число колонок таблиц

    // Массив структур таблиц (≥10 элементов)
    // Формат: "C1*C2*C3#C4*C5*C6"  (* — разделитель колонок, # — разделитель строк)
    $structures = array(
        'Имя*Возраст*Город*Профессия#Иванов Иван*25*Москва*Инженер#Петрова Анна*30*СПб*Дизайнер',  // 1
        'Товар*Цена*Количество*Скидка#Ноутбук*50000*2*10%#Телефон*30000*5*5%',                     // 2
        'День*Месяц*Год*Событие#1*Сентября*2024*Начало учёбы#1*Января*2025*Новый год',             // 3
        'A*B*C*D#1*2*3*4#5*6*7*8#9*10*11*12',                                                       // 4
        'Пустая строка ниже##Заполненная*строка*с*данными',                                          // 5 — есть пустая строка
        '*Пропущена первая ячейка*2*3#1*2*3*4',                                                      // 6 — пустая первая ячейка
        'Одна колонка заполнена#1***#2***#3***',                                                     // 7 — только первая колонка
        '####',                                                                                      // 8 — только разделители строк (пустые строки)
        '',                                                                                          // 9 — пустая структура (нет строк)
        'X*Y*Z*W#Alpha*Beta*Gamma*Delta#Epsilon*Zeta*Eta*Theta#Iota*Kappa*Lambda*Mu',               // 10
        'Фрукт*Цвет*Вкус*Цена#Яблоко*Зелёный*Сладкий*100#Лимон*Жёлтый*Кислый*80',                    // 11
        '***#***#***'                                                                                // 12 — все ячейки пустые
    );

    // 2. Пользовательские функции

    function getTR($data, $cols)
    {
        $arr = explode('*', $data);

        // Если все ячейки пустые — возвращаем пустую строку
        $hasContent = false;
        foreach ($arr as $cell) {
            if (trim($cell) !== '') {
                $hasContent = true;
                break;
            }
        }
        if (!$hasContent) {
            return '';
        }

        // Дополняем массив до нужного числа колонок пустыми ячейками
        while (count($arr) < $cols) {
            $arr[] = '';
        }
        // Обрезаем, если ячеек больше чем колонок
        $arr = array_slice($arr, 0, $cols);

        $ret = '<tr>';
        for ($i = 0; $i < count($arr); $i++) {
            $ret .= '<td>' . htmlspecialchars($arr[$i]) . '</td>';
        }
        return $ret . '</tr>';
    }

    function outTable($structure, $cols)
    {
        // Проверка на число колонок
        if ($cols <= 0) {
            echo '<p class="warning-msg">Неправильное число колонок</p>';
            return;
        }

        // Разбиваем структуру на строки
        $strings = explode('#', $structure);

        // Если строк нет (пустая структура)
        if (count($strings) === 0 || (count($strings) === 1 && $strings[0] === '')) {
            echo '<p class="warning-msg">В таблице нет строк</p>';
            return;
        }

        // Формируем HTML-код всех строк
        $datas = '';
        $hasRows = false;
        for ($i = 0; $i < count($strings); $i++) {
            $tr = getTR($strings[$i], $cols);
            if ($tr !== '') {
                $datas .= $tr;
                $hasRows = true;
            }
        }

        // Если нет строк с ячейками
        if (!$hasRows) {
            echo '<p class="warning-msg">В таблице нет строк с ячейками</p>';
            return;
        }

        // Выводим таблицу
        echo '<table class="data-table">' . $datas . '</table>';
    }

    // 3. Вывод всех таблиц
    for ($i = 0; $i < count($structures); $i++) {
        echo '<h2>Таблица №' . ($i + 1) . '</h2>';
        outTable($structures[$i], $columns_count);
    }
    ?>
</main>

<!-- Погреб с огуррчиками -->
<footer>
    <?php echo 'Лабораторная работа №4. Пользовательские функции.'; ?>
</footer>

</body>
</html>
