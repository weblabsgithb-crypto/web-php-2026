<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Беберин Всеволод Викторович, 241-352, ЛР7 — Ввод данных и сортировка массивов</title>
    <link rel="stylesheet" href="styles.css">
    <script>
    // Функция добавления элемента в таблицу
    function addElement() {
        var table = document.getElementById('elements');
        var index = table.rows.length;
        var row = table.insertRow(index);

        // Ячейка с номером
        var indexCell = row.insertCell(0);
        indexCell.className = 'index-cell';
        indexCell.textContent = index;

        // Ячейка с полем ввода
        var inputCell = row.insertCell(1);
        inputCell.className = 'input-cell';
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'element' + index;
        inputCell.appendChild(input);

        // Обновляем скрытое поле с количеством элементов
        document.getElementById('arrLength').value = table.rows.length;
    }
    </script>
</head>
<body>

<header>
  <h1>Беберин Всеволод Викторович, Группа 241-352</h1>
  <p>Лабораторная работа №7. Ввод данных и сортировка массивов.</p>
</header>

<div class="input-container">
  <h2>Ввод элементов массива</h2>p
  
  <form name="sort_form" method="post" action="sort.php" target="_blank">
    <!-- Таблица с полями ввода -->
    <table id="elements">
      <tr>
        <td class="index-cell">0</td>
        <td class="input-cell"><input type="text" name="element0"></td>
      </tr>
    </table>

    <!-- Скрытое поле для хранения длины массива -->
    <input type="hidden" id="arrLength" name="arrLength" value="1">
    
    <!-- Селектор алгоритма сортировки -->
    <div class="form-controls">
      <div class="form-row">
        <label for="algorithm">Алгоритм сортировки:</label>
        <select id="algorithm" name="algorithm">
          <option value="selection">Сортировка выбором</option>
          <option value="bubble">Пузырьковый алгоритм</option>
          <option value="shell">Алгоритм Шелла</option>
          <option value="gnome">Алгоритм садового гнома</option>
          <option value="quick">Быстрая сортировка</option>
          <option value="php_sort">Встроенная функция PHP (sort)</option>
        </select>
      </div>

      <!-- Кнопки -->
      <div class="form-row">
        <input type="button" class="btn btn-add" value="Добавить еще один элемент" onClick="addElement();">
        <input type="submit" class="btn btn-sort" value="Сортировать массив">
      </div>
    </div>
    </form>
</div>

<footer>
  date_default_timezone_set('Europe/Moscow');
  Лабораторная работа №7. Ввод данных и сортировка массивов. <?php echo date('d.m.Y H:i:s'); ?>
</footer>

</body>
</html>
