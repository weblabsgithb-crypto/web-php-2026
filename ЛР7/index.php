<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Беберин Всеволод Викторович, Группа 241-352, ЛР7 — Сортировка массивов</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function setHTML(element, txt) {
            if ('innerHTML' in element) {
                element.innerHTML = txt;
            } else {
                var range = document.createRange();
                range.selectNodeContents(element);
                range.deleteContents();
                var fragment = range.createContextualFragment(txt);
                element.appendChild(fragment);
            }
        }

        function addElement() {
            var table = document.getElementById('elements');
            var index = table.rows.length;
            var row = table.insertRow(index);

            var cellIndex = row.insertCell(0);
            cellIndex.className = 'index-cell';
            setHTML(cellIndex, index);

            var cellInput = row.insertCell(1);
            cellInput.className = 'input-cell';
            setHTML(cellInput, '<input type="text" name="element' + index + '" class="array-input">');

            document.getElementById('arrLength').value = table.rows.length;
        }

        window.onload = function () {
            document.getElementById('arrLength').value = document.getElementById('elements').rows.length;
        };
    </script>
</head>
<body>
<header>
    <div class="header-title">Беберин Всеволод Викторович, Группа 241-352 — ЛР7</div>
    <div class="header-subtitle">Ввод данных и сортировка массивов</div>
</header>

<main>
    <h1>Сортировка массива</h1>
    <form action="sort.php" method="post" target="_blank" class="array-form">
        <table id="elements" class="elements-table">
            <tr>
                <td class="index-cell">0</td>
                <td class="input-cell"><input type="text" name="element0" class="array-input"></td>
            </tr>
        </table>

        <input type="hidden" id="arrLength" name="arrLength" value="1">

        <div class="form-row">
            <label for="algorithm">Алгоритм сортировки:</label>
            <select name="algorithm" id="algorithm">
                <option value="selection">Сортировка выбором</option>
                <option value="bubble">Пузырьковый алгоритм</option>
                <option value="shell">Алгоритм Шелла</option>
                <option value="gnome">Алгоритм садового гнома</option>
                <option value="quick">Быстрая сортировка</option>
                <option value="native">Встроенная функция PHP sort()</option>
            </select>
        </div>

        <div class="buttons-row">
            <input type="button" value="Добавить еще один элемент" onclick="addElement();" class="action-btn secondary-btn">
            <button type="submit" class="action-btn primary-btn">Сортировать массив</button>
        </div>
    </form>
</main>

<footer>
    <?php
    date_default_timezone_set('Europe/Moscow');
    echo 'Лабораторная работа №7. Сортировка массивов. ' . date('d.m.Y H:i:s');
    ?>
</footer>
</body>
</html>
