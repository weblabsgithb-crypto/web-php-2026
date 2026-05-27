<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $page_title = "Беберин Всеволод Викторович, Группа 241-352, ЛР5 — Таблица умножения"; ?>
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <div class="header-title">Беберин Всеволод Викторов, Группа 241-352 — ЛР5</div>
    <div id="main_menu"><?php
        echo '<a href="?html_type=TABLE';
        if (isset($_GET['content'])) {
            echo '&amp;content=' . htmlspecialchars($_GET['content']);
        }
        echo '"';
        if (isset($_GET['html_type']) && $_GET['html_type'] === 'TABLE') {
            echo ' class="selected"';
        }
        echo '>Табличная верстка</a>';

        echo '<a href="?html_type=DIV';
        if (isset($_GET['content'])) {
            echo '&amp;content=' . htmlspecialchars($_GET['content']);
        }
        echo '"';
        if (isset($_GET['html_type']) && $_GET['html_type'] === 'DIV') {
            echo ' class="selected"';
        }
        echo '>Блочная верстка</a>';
    ?></div>
</header>

<div class="content-wrapper">
    <div id="product_menu"><?php
        $baseLink = '?';
        if (isset($_GET['html_type'])) {
            $baseLink .= 'html_type=' . htmlspecialchars($_GET['html_type']);
        }

        echo '<a href="' . $baseLink . '"';
        if (!isset($_GET['content'])) {
            echo ' class="selected"';
        }
        echo '>Вся таблица умножения</a>';

        for ($i = 2; $i <= 9; $i++) {
            $href = $baseLink;
            $href .= (isset($_GET['html_type']) ? '&amp;' : '') . 'content=' . $i;

            echo '<a href="' . $href . '"';
            if (isset($_GET['content']) && $_GET['content'] == $i) {
                echo ' class="selected"';
            }
            echo '>Таблица умножения на ' . $i . '</a>';
        }
    ?></div>

    <main>
        <?php
        function outNumAsLink($x)
        {
            if ($x >= 2 && $x <= 9) {
                return '<a href="?content=' . $x . '">' . $x . '</a>';
            }
            return (string)$x;
        }

        function outRow($n)
        {
            for ($i = 2; $i <= 9; $i++) {
                echo outNumAsLink($n) . '×' . outNumAsLink($i) . '=' . outNumAsLink($i * $n) . '<br>';
            }
        }

        function outTableForm()
        {
            if (!isset($_GET['content'])) {
                echo '<table class="mult-table">';
                echo '<tr>';
                for ($j = 2; $j <= 9; $j++) {
                    echo '<th>' . outNumAsLink($j) . '</th>';
                }
                echo '</tr>';
                for ($i = 2; $i <= 9; $i++) {
                    echo '<tr>';
                    for ($j = 2; $j <= 9; $j++) {
                        echo '<td>' . outNumAsLink($i) . '×' . outNumAsLink($j) . '=' . outNumAsLink($i * $j) . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                $n = (int)$_GET['content'];
                if ($n < 2 || $n > 9) {
                    $n = 2;
                }
                echo '<table class="mult-table">';
                echo '<tr><th class="single-col">' . outNumAsLink($n) . '</th></tr>';
                for ($i = 2; $i <= 9; $i++) {
                    echo '<tr><td class="single-col">' . outNumAsLink($n) . '×' . outNumAsLink($i) . '=' . outNumAsLink($i * $n) . '</td></tr>';
                }
                echo '</table>';
            }
        }

        function outDivForm()
        {
            if (!isset($_GET['content'])) {
                echo '<div class="block-table">';
                for ($i = 2; $i <= 9; $i++) {
                    echo '<div class="block-row">';
                    for ($j = 2; $j <= 9; $j++) {
                        echo '<span class="expr">' . outNumAsLink($i) . '×' . outNumAsLink($j) . '=' . outNumAsLink($i * $j) . '</span>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            } else {
                $n = (int)$_GET['content'];
                if ($n < 2 || $n > 9) {
                    $n = 2;
                }
                echo '<div class="block-table">';
                echo '<div class="block-row single">';
                for ($i = 2; $i <= 9; $i++) {
                    echo '<span class="expr">' . outNumAsLink($n) . '×' . outNumAsLink($i) . '=' . outNumAsLink($i * $n) . '</span>';
                }
                echo '</div>';
                echo '</div>';
            }
        }

        if (!isset($_GET['html_type']) || $_GET['html_type'] === 'TABLE') {
            outTableForm();
        } else {
            outDivForm();
        }
        ?>
    </main>
</div>
//ПОдвал с детьми
<footer>
<?php
    date_default_timezone_set('Europe/Moscow');

    if (!isset($_GET['html_type']) || $_GET['html_type'] === 'TABLE') {
        $info = 'Табличная верстка. ';
    } else {
        $info = 'Блочная верстка. ';
    }

    if (!isset($_GET['content'])) {
        $info .= 'Таблица умножения полностью. ';
    } else {
        $contentValue = (int)$_GET['content'];
        if ($contentValue < 2 || $contentValue > 9) {
            $contentValue = 2;
        }
        $info .= 'Столбец таблицы умножения на ' . $contentValue . '. ';
    }

    echo $info . date('d.m.Y H:i:s');
?>
</footer>

</body>
</html>
