<?php
date_default_timezone_set('Europe/Moscow');

function normalize_number($value)
{
    return str_replace(',', '.', trim((string)$value));
}

function arg_is_not_num($arg)
{
    $arg = normalize_number($arg);
    if ($arg === '') {
        return true;
    }
    return !is_numeric($arg);
}

function algorithm_name($code)
{
    $names = [
        'selection' => 'Сортировка выбором',
        'bubble' => 'Пузырьковый алгоритм',
        'shell' => 'Алгоритм Шелла',
        'gnome' => 'Алгоритм садового гнома',
        'quick' => 'Быстрая сортировка',
        'native' => 'Встроенная функция PHP sort()'
    ];
    return $names[$code] ?? 'Неизвестный алгоритм';
}

function render_array_state($arr)
{
    $html = '<div class="array-state">';
    foreach ($arr as $index => $value) {
        $html .= '<div class="arr-element">' . $index . ': ' . htmlspecialchars((string)$value) . '</div>';
    }
    $html .= '</div>';
    return $html;
}

function log_iteration(&$steps, &$iterations, $arr)
{
    $iterations++;
    $steps[] = [
        'iteration' => $iterations,
        'state' => $arr
    ];
}

// Сортировка выбором
function selection_sort_with_steps($arr, &$steps)
{
    $iterations = 0;
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $minIndex = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            $iterations++;
            if ($arr[$j] < $arr[$minIndex]) {
                $minIndex = $j;
            }
            $steps[] = ['iteration' => $iterations, 'state' => $arr];
        }
        if ($minIndex !== $i) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$minIndex];
            $arr[$minIndex] = $temp;
            log_iteration($steps, $iterations, $arr);
        }
    }
    return [$arr, $iterations];
}

// Пузырьковая сортировка
function bubble_sort_with_steps($arr, &$steps)
{
    $iterations = 0;
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - 1 - $i; $j++) {
            $iterations++;
            if ($arr[$j] > $arr[$j + 1]) {
                $temp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $temp;
            }
            $steps[] = ['iteration' => $iterations, 'state' => $arr];
        }
    }
    return [$arr, $iterations];
}

// Сортировка Шелла
function shell_sort_with_steps($arr, &$steps)
{
    $iterations = 0;
    $n = count($arr);
    for ($gap = intdiv($n, 2); $gap > 0; $gap = intdiv($gap, 2)) {
        for ($i = $gap; $i < $n; $i++) {
            $temp = $arr[$i];
            $j = $i;
            while ($j >= $gap && $arr[$j - $gap] > $temp) {
                $iterations++;
                $arr[$j] = $arr[$j - $gap];
                $j -= $gap;
                $steps[] = ['iteration' => $iterations, 'state' => $arr];
            }
            $arr[$j] = $temp;
            log_iteration($steps, $iterations, $arr);
        }
    }
    return [$arr, $iterations];
}

// Сортировка садового гнома
function gnome_sort_with_steps($arr, &$steps)
{
    $iterations = 0;
    $index = 0;
    $n = count($arr);
    while ($index < $n) {
        $iterations++;
        if ($index === 0 || $arr[$index] >= $arr[$index - 1]) {
            $index++;
        } else {
            $temp = $arr[$index];
            $arr[$index] = $arr[$index - 1];
            $arr[$index - 1] = $temp;
            $index--;
        }
        $steps[] = ['iteration' => $iterations, 'state' => $arr];
    }
    return [$arr, $iterations];
}

// Рекурсивная часть быстрой сортировки
function quick_sort_recursive(&$arr, $left, $right, &$steps, &$iterations)
{
    $i = $left;
    $j = $right;
    $pivot = $arr[intdiv($left + $right, 2)];

    while ($i <= $j) {
        while ($arr[$i] < $pivot) {
            $i++;
            log_iteration($steps, $iterations, $arr);
        }
        while ($arr[$j] > $pivot) {
            $j--;
            log_iteration($steps, $iterations, $arr);
        }
        if ($i <= $j) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
            log_iteration($steps, $iterations, $arr);
            $i++;
            $j--;
        }
    }

    if ($left < $j) {
        quick_sort_recursive($arr, $left, $j, $steps, $iterations);
    }
    if ($i < $right) {
        quick_sort_recursive($arr, $i, $right, $steps, $iterations);
    }
}

// Быстрая сортировка
function quick_sort_with_steps($arr, &$steps)
{
    $iterations = 0;
    if (count($arr) > 1) {
        quick_sort_recursive($arr, 0, count($arr) - 1, $steps, $iterations);
    }
    return [$arr, $iterations];
}

// Встроенная сортировка PHP
function native_sort_with_steps($arr, &$steps)
{
    $iterations = 0;
    sort($arr);
    $steps[] = ['iteration' => 1, 'state' => $arr];
    return [$arr, $iterations];
}

if (!isset($_POST['element0'])) {
    $error = 'Массив не задан, сортировка невозможна.';
} else {
    $arrLength = isset($_POST['arrLength']) ? (int)$_POST['arrLength'] : 0;
    $inputValues = [];
    $error = '';

    if ($arrLength <= 0) {
        $error = 'Не передано количество элементов массива.';
    } else {
        for ($i = 0; $i < $arrLength; $i++) {
            $key = 'element' . $i;
            $value = $_POST[$key] ?? '';
            $inputValues[] = $value;
            if (arg_is_not_num($value)) {
                $error = 'Элемент массива с индексом ' . $i . ' содержит нечисловое значение: "' . htmlspecialchars((string)$value) . '".';
                break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результат сортировки массива</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="header-title">Беберин Всеволод Викторович, Группа 241-352 — ЛР7</div>
    <div class="header-subtitle">Обработка и сортировка массива</div>
</header>

<main>
    <h1>Результат сортировки</h1>

    <?php if (!empty($error)): ?>
        <div class="message error-message"><?php echo $error; ?></div>
    <?php else: ?>
        <?php
        $algorithm = $_POST['algorithm'] ?? 'selection';
        $algorithmTitle = algorithm_name($algorithm);
        $arr = [];
        foreach ($inputValues as $value) {
            $arr[] = (float)normalize_number($value);
        }
        ?>

        <section class="info-block">
            <h2><?php echo htmlspecialchars($algorithmTitle); ?></h2>
            <p><strong>Исходный массив:</strong></p>
            <?php echo render_array_state($inputValues); ?>
            <p class="success-text">Проверка входных данных пройдена, сортировка возможна.</p>
        </section>

        <?php
        $steps = [];
        $startTime = microtime(true);

        switch ($algorithm) {
            case 'bubble':
                [$sortedArr, $iterations] = bubble_sort_with_steps($arr, $steps);
                break;
            case 'shell':
                [$sortedArr, $iterations] = shell_sort_with_steps($arr, $steps);
                break;
            case 'gnome':
                [$sortedArr, $iterations] = gnome_sort_with_steps($arr, $steps);
                break;
            case 'quick':
                [$sortedArr, $iterations] = quick_sort_with_steps($arr, $steps);
                break;
            case 'native':
                [$sortedArr, $iterations] = native_sort_with_steps($arr, $steps);
                break;
            case 'selection':
            default:
                [$sortedArr, $iterations] = selection_sort_with_steps($arr, $steps);
                break;
        }

        $elapsed = microtime(true) - $startTime;
        ?>

        <section class="steps-block">
            <h2>Ход сортировки</h2>
            <?php if (empty($steps)): ?>
                <div class="step-item">
                    <div class="step-title">Итераций не зафиксировано</div>
                    <?php echo render_array_state($sortedArr); ?>
                </div>
            <?php else: ?>
                <?php foreach ($steps as $step): ?>
                    <div class="step-item">
                        <div class="step-title">Итерация <?php echo $step['iteration']; ?></div>
                        <?php echo render_array_state($step['state']); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section class="result-block">
            <h2>Результат</h2>
            <p><strong>Отсортированный массив:</strong></p>
            <?php echo render_array_state($sortedArr); ?>
            <p class="finish-text">Сортировка завершена, проведено <?php echo $iterations; ?> итераций. Сортировка заняла <?php echo number_format($elapsed, 6, '.', ''); ?> секунд.</p>
        </section>
    <?php endif; ?>

    <a href="index.php" class="back-btn">Вернуться к форме</a>
</main>

<footer>
    <?php echo 'Лабораторная работа №7. Сортировка массивов. ' . date('d.m.Y H:i:s'); ?>
</footer>
</body>
</html>
