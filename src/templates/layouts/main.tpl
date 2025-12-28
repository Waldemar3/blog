<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$pageTitle|default:"Блог"} - Мой Блог</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="/" class="logo">Мой Блог</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="container">
            {block name="content"}{/block}
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; {$smarty.now|date_format:"%Y"} Мой Блог. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>
