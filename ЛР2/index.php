<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $page_title = "Беберин Всеволод Викторович, Группа 241-352, ЛР2 — Табулирование функций, вариант 1"; ?>
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Шапка -->
<header>
    <img src="img/logo.png" alt="Логотип университета" class="logo">
    <div class="header-info">
        <h1>Беберин Всеволод Викторович, Группа 241-352</h1>
        <p>Лабораторная работа №2. Табулирование функций. Вариант 1.</p>
    </div>
</header>

<!-- Основной контент -->
<main>
    <h2>Результаты табулирования функции</h2>

    <?php
    // 1. Инициализация переменных
    $x = -10;           // начальное значение аргумента
    $encounting = 100;  // количество вычисляемых значений функции
    $step = 0.5;        // шаг изменения аргумента
    $min_value = -100;  // минимальное значение функции, останавливающее вычисления
    $max_value = 1000;  // максимальное значение функции, останавливающее вычисления
    $type = 'A';        // тип верстки: 'A', 'B', 'C', 'D', 'E'

    // Массивы для хранения результатов
    $args = [];
    $values = [];

    // 2. Цикл вычисления функции (for с break)
    for ($i = 0; $i < $encounting; $i++, $x += $step) {
        $f = 0;

        // Вычисление функции по варианту 1:
        // f(x) = 5, при x < 10
        // f(x) = 3*x/10, при x >= 10

        if ($x < 10) {
            $f = 5;
        } else {
            // Проверка на деление на ноль
            $denominator = 10;
            if ($denominator == 0) {
                $f = 'error';
            } else {
                $f = round(3 * $x / 10, 3);
            }
        }

        // Проверка на выход за пределы диапазона
        if (is_numeric($f) && ($f >= $max_value || $f < $min_value)) {
            break;
        }

        $args[] = $x;
        $values[] = $f;
    }

    // 3. Вычисление статистики
    $numeric_values = array_filter($values, 'is_numeric');
    $count = count($numeric_values);

    if ($count > 0) {
        $min_f = round(min($numeric_values), 3);
        $max_f = round(max($numeric_values), 3);
        $sum_f = round(array_sum($numeric_values), 3);
        $avg_f = round($sum_f / $count, 3);
    } else {
        $min_f = '—';
        $max_f = '—';
        $sum_f = '—';
        $avg_f = '—';
    }

    // 4. Вывод результатов в зависимости от типа верстки
    switch ($type) {
        case 'A':
            // Простая верстка текстом
            echo '<div class="simple-output">';
            for ($i = 0; $i < count($args); $i++) {
                echo 'f(' . $args[$i] . ')=' . $values[$i];
                if ($i < count($args) - 1) {
                    echo '<br>';
                }
            }
            echo '</div>';
            break;

        case 'B':
            // Маркированный список
            echo '<ul class="results-list">';
            for ($i = 0; $i < count($args); $i++) {
                echo '<li>f(' . $args[$i] . ')=' . $values[$i] . '</li>';
            }
            echo '</ul>';
            break;

        case 'C':
            // Нумерованный список
            echo '<ol class="results-list">';
            for ($i = 0; $i < count($args); $i++) {
                echo '<li>f(' . $args[$i] . ')=' . $values[$i] . '</li>';
            }
            echo '</ol>';
            break;

        case 'D':
            // Табличная верстка
            echo '<table class="results">';
            echo '<tr><th>№</th><th>Аргумент (x)</th><th>Значение функции f(x)</th></tr>';
            for ($i = 0; $i < count($args); $i++) {
                echo '<tr><td>' . ($i + 1) . '</td><td>' . $args[$i] . '</td><td>' . $values[$i] . '</td></tr>';
            }
            echo '</table>';
            break;

        case 'E':
            // Блочная верстка
            echo '<div class="block-container">';
            for ($i = 0; $i < count($args); $i++) {
                echo '<div class="result-block">f(' . $args[$i] . ')=' . $values[$i] . '</div>';
            }
            echo '</div>';
            break;

        default:
            echo '<p>Неизвестный тип верстки: ' . htmlspecialchars($type) . '</p>';
            break;
    }
    ?>

    <!-- Статистика -->
    <div class="stats">
        <h3>Статистика</h3>
        <p>Минимальное значение функции: <strong><?php echo $min_f; ?></strong></p>
        <p>Максимальное значение функции: <strong><?php echo $max_f; ?></strong></p>
        <p>Сумма значений функции: <strong><?php echo $sum_f; ?></strong></p>
        <p>Среднее арифметическое: <strong><?php echo $avg_f; ?></strong></p>
    </div>
</main>

<!-- Подвал xD -->
<footer>
    <?php echo 'Тип верстки: ' . htmlspecialchars($type); ?>
</footer>

</body>
</html>
