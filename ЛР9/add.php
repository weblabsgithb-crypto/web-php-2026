<?php
// add.php — Модуль для добавления новой записи в базу данных

// Обработка отправки формы
$form_submitted = false;
$form_success = false;

if (isset($_POST['button']) && $_POST['button'] === 'Добавить запись') {
    $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (mysqli_connect_errno()) {
        echo '<p class="msg-error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</p>';
    } else {
        mysqli_set_charset($mysqli, 'utf8mb4');

        $surname = isset($_POST['surname']) ? trim($_POST['surname']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $patronymic = isset($_POST['patronymic']) ? trim($_POST['patronymic']) : '';
        $gender = isset($_POST['gender']) ? $_POST['gender'] : 'male';
        $birth_date = isset($_POST['birth_date']) ? trim($_POST['birth_date']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

        $sql = 'INSERT INTO contacts (surname, name, patronymic, gender, birth_date, phone, address, email, comment)
                VALUES ("' . mysqli_real_escape_string($mysqli, $surname) . '",
                        "' . mysqli_real_escape_string($mysqli, $name) . '",
                        "' . mysqli_real_escape_string($mysqli, $patronymic) . '",
                        "' . mysqli_real_escape_string($mysqli, $gender) . '",
                        ' . ($birth_date !== '' ? '"' . mysqli_real_escape_string($mysqli, $birth_date) . '"' : 'NULL') . ',
                        "' . mysqli_real_escape_string($mysqli, $phone) . '",
                        "' . mysqli_real_escape_string($mysqli, $address) . '",
                        "' . mysqli_real_escape_string($mysqli, $email) . '",
                        "' . mysqli_real_escape_string($mysqli, $comment) . '")';

        $sql_res = mysqli_query($mysqli, $sql);
        if (mysqli_errno($mysqli)) {
            $form_submitted = true;
            $form_success = false;
        } else {
            $form_submitted = true;
            $form_success = true;
        }

        mysqli_close($mysqli);
    }
}
?>

<h2>Добавление записи</h2>

<?php if ($form_submitted): ?>
    <?php if ($form_success): ?>
        <p class="msg-ok">Запись добавлена</p>
    <?php else: ?>
        <p class="msg-error">Ошибка: запись не добавлена</p>
    <?php endif; ?>
<?php endif; ?>

<div class="form-container">
    <form name="form_add" method="post" action="?p=add">
        <label for="surname">Фамилия:</label>
        <input type="text" id="surname" name="surname" value="">

        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" value="">

        <label for="patronymic">Отчество:</label>
        <input type="text" id="patronymic" name="patronymic" value="">

        <label for="gender">Пол:</label>
        <select id="gender" name="gender">
            <option value="male">Мужской</option>
            <option value="female">Женский</option>
        </select>

        <label for="birth_date">Дата рождения:</label>
        <input type="date" id="birth_date" name="birth_date" value="">

        <label for="phone">Телефон:</label>
        <input type="tel" id="phone" name="phone" value="">

        <label for="address">Адрес:</label>
        <input type="text" id="address" name="address" value="">

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" value="">

        <label for="comment">Комментарий:</label>
        <textarea id="comment" name="comment"></textarea>

        <input type="submit" name="button" value="Добавить запись">
    </form>
</div>
