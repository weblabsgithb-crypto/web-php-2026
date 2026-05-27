<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $page_title = "Беберин Всеволод Викторович, Группа 241-352, ЛР3 — Виртуальная клавиатура"; ?>
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Шапка -->
<header>
  <img src="img/logo.png" alt="Логотип университета" class="logo">
    <div class="header-info">
        <h1>Беберин Всеволод Викторович, Группа 241-352</h1>
        <p>Лабораторная работа №3. Использование GET-параметров. Виртуальная клавиатура.</p>
    </div>
</header>

<!-- Основной контент -->
<main>
    <h2>Виртуальная клавиатура</h2>

    <?php
    // 1. Инициализация хранилища (результат предыдущей обработки)
    $store = isset($_GET['store']) ? $_GET['store'] : '';

    // 2. Инициализация счётчика нажатий (через параметр clicks)
    $clicks = (isset($_GET['clicks']) && is_numeric($_GET['clicks']))
        ? (int)$_GET['clicks']
        : 0;

    // 3. Обработка нажатия кнопки
    if (isset($_GET['key'])) {
        $key = $_GET['key'];

        // Валидация: разрешаем только цифры 0-9 и 'reset'
        if ($key === 'reset') {
            // при сбросе очищаем только строку, клики НЕ трогаем
            $store = '';
        } elseif (preg_match('/^[0-9]$/', $key)) {
            // добавляем цифру и увеличиваем счётчик кликов
            $store .= $key;
            $clicks++;
        }
    }

    // 4. Вывод окна результата
    echo '<div class="result-display">';
    echo htmlspecialchars($store);
    echo '</div>';
    ?>

    <!-- Клавиатура: кнопки 1-3 -->
    <div class="keyboard">
        <a href="?key=1&store=<?php echo urlencode($store); ?>&clicks=<?php echo $clicks; ?>" class="key">1</a>
        <a href="?key=2&store=<?php echo urlencode($store); ?>&clicks=<?php echo $clicks; ?>" class="key">2</a>
        <a href="?key=3&store=<?php echo urlencode($store); ?>&clicks=<?php echo $clicks; ?>" class="key">3</a>
    </div>
    <!-- Кнопки 4-6 -->
    <div class="keyboard">
        <a href="?key=4&store=<?php echo urlencode($store); ?>&clicks=<?php echo $clicks; ?>" class="key">4</a>
        <a href="?key=5&store=<?php echo urlencode($store); ?>&clicks=<?php echo $clicks; ?>" class="key">5</a>
        <a href="?key=6&store=<?php echo urlencode($store); ?>&clicks=<?php echo $clicks; ?>" class="key">6</a>
    </div>
    <!-- Кнопки 7-9 -->
    <div class="keyboard">
        <a href="?key=7&store=<?php echo urlencode($store); ?>&clicks=<?php echo $clicks; ?>" class="key">7</a>
        <a href="?key=8&store=<?php echo urlencode($store); ?>&clicks=<?php echo $clicks; ?>" class="key">8</a>
        <a href="?key=9&store=<?php echo urlencode($store); ?>&clicks=<?php echo $clicks; ?>" class="key">9</a>
    </div>
    <!-- Кнопки 0 и СБРОС -->
    <div class="keyboard">
        <a href="?key=0&store=<?php echo urlencode($store); ?>&clicks=<?php echo $clicks; ?>" class="key">0</a>
        <!-- СБРОС: передаём старые клики, строку обнуляем в обработчике -->
        <a href="?key=reset&store=&clicks=<?php echo $clicks; ?>" class="key reset-btn">СБРОС</a>
    </div>
</main>

<!-- Подвал -->
<footer>
    <?php echo 'Общее число нажатий: ' . $clicks; ?>
</footer>

</body>
</html>
