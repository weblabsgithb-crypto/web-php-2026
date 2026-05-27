<?php
function normalize_number($value)
{
    return str_replace(',', '.', trim((string)$value));
}

function parse_number($value, &$ok)
{
    $normalized = normalize_number($value);
    if ($normalized === '' || !is_numeric($normalized)) {
        $ok = false;
        return 0;
    }
    return (float)$normalized;
}

function random_decimal()
{
    return mt_rand(0, 10000) / 100;
}

$processed = false;
$has_errors = false;
$errors = [];
$report_html = '';

$form_data = [
    'FIO' => isset($_GET['FIO']) ? trim($_GET['FIO']) : '',
    'GROUP' => isset($_GET['GROUP']) ? trim($_GET['GROUP']) : '',
    'A' => random_decimal(),
    'B' => random_decimal(),
    'C' => random_decimal(),
    'result' => '',
    'MAIL' => '',
    'ABOUT' => '',
    'TASK' => 'mean',
    'send_mail' => false,
    'print_version' => 'browser'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_data['FIO'] = trim($_POST['FIO'] ?? '');
    $form_data['GROUP'] = trim($_POST['GROUP'] ?? '');
    $form_data['A'] = trim($_POST['A'] ?? '');
    $form_data['B'] = trim($_POST['B'] ?? '');
    $form_data['C'] = trim($_POST['C'] ?? '');
    $form_data['result'] = trim($_POST['result'] ?? '');
    $form_data['MAIL'] = trim($_POST['MAIL'] ?? '');
    $form_data['ABOUT'] = trim($_POST['ABOUT'] ?? '');
    $form_data['TASK'] = $_POST['TASK'] ?? 'mean';
    $form_data['send_mail'] = isset($_POST['send_mail']);
    $form_data['print_version'] = $_POST['print_version'] ?? 'browser';

    $num_ok = true;
    $a = parse_number($form_data['A'], $num_ok);
    $a_ok = $num_ok;
    $num_ok = true;
    $b = parse_number($form_data['B'], $num_ok);
    $b_ok = $num_ok;
    $num_ok = true;
    $c = parse_number($form_data['C'], $num_ok);
    $c_ok = $num_ok;

    if (!$a_ok) {
        $errors[] = 'Поле A должно содержать число.';
    }
    if (!$b_ok) {
        $errors[] = 'Поле B должно содержать число.';
    }
    if (!$c_ok) {
        $errors[] = 'Поле C должно содержать число.';
    }

    if ($form_data['send_mail']) {
        if ($form_data['MAIL'] === '') {
            $errors[] = 'Укажите e-mail для отправки результата.';
        } elseif (!filter_var($form_data['MAIL'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Указан некорректный e-mail адрес.';
        }
    }

    $allowed_tasks = [
        'triangle_area',
        'triangle_perimeter',
        'parallelepiped_volume',
        'mean',
        'hypotenuse',
        'product'
    ];

    if (!in_array($form_data['TASK'], $allowed_tasks, true)) {
        $errors[] = 'Выбрана некорректная задача.';
    }

    if ($form_data['print_version'] !== 'browser' && $form_data['print_version'] !== 'print') {
        $errors[] = 'Выбран некорректный режим отображения.';
    }

    if (empty($errors)) {
        $processed = true;
        $print_version = $form_data['print_version'] === 'print';
        $task = $form_data['TASK'];
        $user_answer = $form_data['result'];
        $fio = $form_data['FIO'];
        $group = $form_data['GROUP'];
        $about = $form_data['ABOUT'];
        $mail_address = $form_data['MAIL'];
        $send_mail = $form_data['send_mail'];

        $correct_result = 0;
        $task_name = '';

        switch ($task) {
            case 'triangle_area':
                $task_name = 'Площадь треугольника';
                if ($a + $b > $c && $a + $c > $b && $b + $c > $a) {
                    $p = ($a + $b + $c) / 2;
                    $s = $p * ($p - $a) * ($p - $b) * ($p - $c);
                    $correct_result = round(sqrt($s), 2);
                } else {
                    $correct_result = 'Треугольник не существует';
                }
                break;
            case 'triangle_perimeter':
                $correct_result = round($a + $b + $c, 2);
                $task_name = 'Периметр треугольника';
                break;
            case 'parallelepiped_volume':
                $correct_result = round($a * $b * $c, 2);
                $task_name = 'Объём параллелепипеда';
                break;
            case 'mean':
                $correct_result = round(($a + $b + $c) / 3, 2);
                $task_name = 'Среднее арифметическое';
                break;
            case 'hypotenuse':
                $correct_result = round(sqrt($a * $a + $b * $b), 2);
                $task_name = 'Гипотенуза (по катетам A и B)';
                break;
            case 'product':
                $correct_result = round($a * $b * $c, 2);
                $task_name = 'Произведение трёх чисел';
                break;
        }

        $user_answer_normalized = normalize_number($user_answer);
        $user_answer_is_number = ($user_answer_normalized !== '' && is_numeric($user_answer_normalized));
        $user_answer_float = $user_answer_is_number ? (float)$user_answer_normalized : null;

        if ($user_answer === '') {
            $test_result_text = 'Задача самостоятельно решена не была';
            $test_result_class = 'error';
        } elseif (!is_numeric($correct_result)) {
            $test_result_text = 'Ошибка: задача не может быть решена для введённых данных';
            $test_result_class = 'error';
        } elseif (!$user_answer_is_number) {
            $test_result_text = 'Ошибка: ваш ответ должен быть числом';
            $test_result_class = 'error';
        } elseif (abs($user_answer_float - $correct_result) < 0.01) {
            $test_result_text = 'Тест пройден';
            $test_result_class = 'success';
        } else {
            $test_result_text = 'Ошибка: тест не пройден';
            $test_result_class = 'error';
        }

        $report_html .= '<div class="report">';
        $report_html .= '<h2>Отчёт о тестировании</h2>';
        $report_html .= '<p><span class="label">ФИО:</span> ' . htmlspecialchars($fio) . '</p>';
        $report_html .= '<p><span class="label">Группа:</span> ' . htmlspecialchars($group) . '</p>';
        if ($about !== '') {
            $report_html .= '<p><span class="label">О себе:</span> ' . nl2br(htmlspecialchars($about)) . '</p>';
        }
        $report_html .= '<p><span class="label">Тип задачи:</span> ' . htmlspecialchars($task_name) . '</p>';
        $report_html .= '<p><span class="label">Входные данные:</span> A = ' . htmlspecialchars((string)$form_data['A']) . ', B = ' . htmlspecialchars((string)$form_data['B']) . ', C = ' . htmlspecialchars((string)$form_data['C']) . '</p>';
        $report_html .= '<p><span class="label">Ваш ответ:</span> ' . ($user_answer !== '' ? htmlspecialchars($user_answer) : '—') . '</p>';
        $report_html .= '<p><span class="label">Правильный ответ:</span> ' . (is_numeric($correct_result) ? $correct_result : htmlspecialchars($correct_result)) . '</p>';
        $report_html .= '<p class="' . $test_result_class . '">' . htmlspecialchars($test_result_text) . '</p>';

        if ($send_mail && $mail_address !== '') {
            $mail_text = "ФИО: $fio\r\n";
            $mail_text .= "Группа: $group\r\n";
            $mail_text .= "О себе: $about\r\n";
            $mail_text .= "Задача: $task_name\r\n";
            $mail_text .= "Входные данные: A=$a, B=$b, C=$c\r\n";
            $mail_text .= "Ваш ответ: $user_answer\r\n";
            $mail_text .= "Правильный ответ: $correct_result\r\n";
            $mail_text .= "Результат: $test_result_text";
            $mail_subject = 'Результат тестирования — ' . $fio;
            $mail_headers = "From: auto@test.ru\r\nContent-Type: text/plain; charset=utf-8\r\n";

            if (mail($mail_address, $mail_subject, $mail_text, $mail_headers)) {
                $report_html .= '<p class="mail-sent">Результаты теста были автоматически отправлены на e-mail: ' . htmlspecialchars($mail_address) . '</p>';
            } else {
                $report_html .= '<p class="error">Не удалось отправить письмо на e-mail: ' . htmlspecialchars($mail_address) . '</p>';
            }
        }

        $report_html .= '</div>';

        if (!$print_version) {
            $report_html .= '<a href="?FIO=' . urlencode($fio) . '&GROUP=' . urlencode($group) . '" id="back_button">Повторить тест</a>';
        }
    } else {
        $has_errors = true;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $page_title = "Беберин Всеволод Викторович, Группа 241-352, ЛР6 — Тест математических знаний"; ?>
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="header-info">
        <h1>Беберин Всеволод Викторович, Группа 241-352</h1>
        <p>Лабораторная работа №6. Тест математических знаний.</p>
    </div>
</header>

<main>
    <?php if ($processed): ?>
        <?php echo $report_html; ?>
    <?php else: ?>
        <h2>Тест математических знаний</h2>

        <?php if ($has_errors): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form name="test_form" method="post" action="" class="test-form">
            <div class="form-row">
                <label for="fio">ФИО:</label>
                <input type="text" id="fio" name="FIO" value="<?php echo htmlspecialchars((string)$form_data['FIO']); ?>">
            </div>

            <div class="form-row">
                <label for="group">Номер группы:</label>
                <input type="text" id="group" name="GROUP" value="<?php echo htmlspecialchars((string)$form_data['GROUP']); ?>">
            </div>

            <div class="form-row">
                <label for="val_a">Значение A:</label>
                <input type="text" id="val_a" name="A" value="<?php echo htmlspecialchars((string)$form_data['A']); ?>">
            </div>

            <div class="form-row">
                <label for="val_b">Значение B:</label>
                <input type="text" id="val_b" name="B" value="<?php echo htmlspecialchars((string)$form_data['B']); ?>">
            </div>

            <div class="form-row">
                <label for="val_c">Значение C:</label>
                <input type="text" id="val_c" name="C" value="<?php echo htmlspecialchars((string)$form_data['C']); ?>">
            </div>

            <div class="form-row">
                <label for="user_result">Ваш ответ:</label>
                <input type="text" id="user_result" name="result" value="<?php echo htmlspecialchars((string)$form_data['result']); ?>">
            </div>

            <div class="form-row email-row <?php echo $form_data['send_mail'] ? 'visible' : ''; ?>" id="email-block">
                <label for="mail">Ваш e-mail:</label>
                <input type="text" id="mail" name="MAIL" value="<?php echo htmlspecialchars((string)$form_data['MAIL']); ?>">
            </div>

            <div class="form-row">
                <label for="about">Немного о себе:</label>
                <textarea id="about" name="ABOUT" rows="3"><?php echo htmlspecialchars((string)$form_data['ABOUT']); ?></textarea>
            </div>

            <div class="form-row">
                <label for="task">Выберите задачу:</label>
                <select id="task" name="TASK">
                    <option value="triangle_area" <?php echo $form_data['TASK'] === 'triangle_area' ? 'selected' : ''; ?>>Площадь треугольника</option>
                    <option value="triangle_perimeter" <?php echo $form_data['TASK'] === 'triangle_perimeter' ? 'selected' : ''; ?>>Периметр треугольника</option>
                    <option value="parallelepiped_volume" <?php echo $form_data['TASK'] === 'parallelepiped_volume' ? 'selected' : ''; ?>>Объём параллелепипеда</option>
                    <option value="mean" <?php echo $form_data['TASK'] === 'mean' ? 'selected' : ''; ?>>Среднее арифметическое</option>
                    <option value="hypotenuse" <?php echo $form_data['TASK'] === 'hypotenuse' ? 'selected' : ''; ?>>Гипотенуза (по катетам)</option>
                    <option value="product" <?php echo $form_data['TASK'] === 'product' ? 'selected' : ''; ?>>Произведение трёх чисел</option>
                </select>
            </div>

            <div class="form-row checkbox-row">
                <label for="send_mail_cb">Отправить результат теста по e-mail:</label>
                <input type="checkbox" id="send_mail_cb" name="send_mail" <?php echo $form_data['send_mail'] ? 'checked' : ''; ?> onclick="var obj=document.getElementById('email-block'); if(this.checked) obj.classList.add('visible'); else obj.classList.remove('visible');">
            </div>

            <div class="form-row">
                <label for="print_version">Режим отображения:</label>
                <select id="print_version" name="print_version">
                    <option value="browser" <?php echo $form_data['print_version'] === 'browser' ? 'selected' : ''; ?>>Версия для просмотра в браузере</option>
                    <option value="print" <?php echo $form_data['print_version'] === 'print' ? 'selected' : ''; ?>>Версия для печати</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Проверить</button>
        </form>
    <?php endif; ?>
</main>

<footer>
    <?php
    date_default_timezone_set('Europe/Moscow');
    echo 'Лабораторная работа №6. Тест математических знаний. ' . date('d.m.Y H:i:s');
    ?>
</footer>
</body>
</html>
