<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Беберин Всеволод Викторович, Группа 241-352, ЛР8 — Результат анализа текста</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Беберин Всеволод Викторович, Группа 241-352</h1>
    <p>Лабораторная работа №8. Результат анализа текста.</p>
</header>

<div class="container">
<?php
// Проверка наличия текста
if (isset($_POST['data']) && mb_strlen($_POST['data'], 'UTF-8') > 0) {
    $text = $_POST['data'];

    // Вывод исходного текста
    echo '<h2>Исходный текст</h2>';
    echo '<div class="src_text">' . htmlspecialchars($text) . '</div>';

    // Анализ текста
    echo '<h2>Информация о тексте</h2>';

    // 1. Количество символов (включая пробелы)
    $total_chars = mb_strlen($text, 'UTF-8');

    // 2. Количество букв
    // 3. Строчные и заглавные буквы
    // 4. Знаки препинания
    // 5. Цифры
    // 6. Слова
    $letter_count = 0;
    $uppercase_count = 0;
    $lowercase_count = 0;
    $punctuation_count = 0;
    $digit_count = 0;

    $punctuation_chars = '.,;:!?—–-"\'()[]{}«»\/@#$%^&*+=~`';

    for ($i = 0; $i < $total_chars; $i++) {
        $char = mb_substr($text, $i, 1, 'UTF-8');

        // Цифры
        if (mb_strpos('0123456789', $char, 0, 'UTF-8') !== false) {
            $digit_count++;
        }

        // Знаки препинания
        if (mb_strpos($punctuation_chars, $char, 0, 'UTF-8') !== false) {
            $punctuation_count++;
        }

        // Буквы
        $lower = mb_strtolower($char, 'UTF-8');
        $upper = mb_strtoupper($char, 'UTF-8');

        if ($lower !== $upper) {
            // Это буква
            $letter_count++;
            if ($char === $upper && $char !== $lower) {
                $uppercase_count++;
            } else {
                $lowercase_count++;
            }
        }
    }

    // Подсчёт слов
    // Разделители: пробелы, знаки препинания, переводы строк
    $words_text = preg_split('/[\s\p{P}]+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
    $word_count = count($words_text);

    // Вхождения каждого символа (без учёта регистра)
    $text_lower = mb_strtolower($text, 'UTF-8');
    $symbs = [];
    $total_chars_lower = mb_strlen($text_lower, 'UTF-8');
    for ($i = 0; $i < $total_chars_lower; $i++) {
        $char = mb_substr($text_lower, $i, 1, 'UTF-8');
        if (isset($symbs[$char])) {
            $symbs[$char]++;
        } else {
            $symbs[$char] = 1;
        }
    }
    ksort($symbs);

    // Вхождения слов (отсортированные по алфавиту)
    $words = [];
    foreach ($words_text as $w) {
        $w_lower = mb_strtolower($w, 'UTF-8');
        if (isset($words[$w_lower])) {
            $words[$w_lower]++;
        } else {
            $words[$w_lower] = 1;
        }
    }
    ksort($words);

    // Вывод результатов в таблице
    echo '<table class="results-table">';

    echo '<tr><th>Количество символов (включая пробелы)</th><td>' . $total_chars . '</td></tr>';
    echo '<tr><th>Количество букв</th><td>' . $letter_count . '</td></tr>';
    echo '<tr><th>Количество заглавных букв</th><td>' . $uppercase_count . '</td></tr>';
    echo '<tr><th>Количество строчных букв</th><td>' . $lowercase_count . '</td></tr>';
    echo '<tr><th>Количество знаков препинания</th><td>' . $punctuation_count . '</td></tr>';
    echo '<tr><th>Количество цифр</th><td>' . $digit_count . '</td></tr>';
    echo '<tr><th>Количество слов</th><td>' . $word_count . '</td></tr>';

    // Вхождения символов
    echo '<tr><th>Вхождения каждого символа (без учёта регистра)</th><td>';
    echo '<table class="results-table" style="margin:0; border:none;">';
    echo '<tr><th style="background-color:#004080;">Символ</th><th style="background-color:#004080;">Кол-во</th></tr>';
    foreach ($symbs as $char => $count) {
        $display_char = ($char === ' ') ? '␣ (пробел)' : htmlspecialchars($char);
        echo '<tr><td>' . $display_char . '</td><td>' . $count . '</td></tr>';
    }
    echo '</table>';
    echo '</td></tr>';

    // Вхождения слов
    echo '<tr><th>Слова и их вхождения (по алфавиту)</th><td>';
    echo '<table class="results-table" style="margin:0; border:none;">';
    echo '<tr><th style="background-color:#004080;">Слово</th><th style="background-color:#004080;">Кол-во</th></tr>';
    foreach ($words as $word => $count) {
        echo '<tr><td>' . htmlspecialchars($word) . '</td><td>' . $count . '</td></tr>';
    }
    echo '</table>';
    echo '</td></tr>';

    echo '</table>';

    // Кнопка «Другой анализ»
    echo '<a href="index.html" class="btn-another">Другой анализ</a>';

} else {
    // Нет текста
    echo '<div class="src_error">Нет текста для анализа</div>';
    echo '<a href="index.html" class="btn-another">Другой анализ</a>';
}
?>
</div>

<footer>
    Лабораторная работа №8. Анализ текста. <?php echo date('d.m.Y H:i:s'); ?>
</footer>

</body>
</html>
