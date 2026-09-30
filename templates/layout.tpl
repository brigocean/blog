<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{block name=title}Блог{/block} | Blogy.</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<header class="header">
    <div class="container header__inner">
        <a class="header__logo" href="/">Blogy.</a>
    </div>
</header>
<main class="main">
    <div class="container">
        {block name=content}{/block}
    </div>
</main>
<footer class="footer">
    <div class="container">&copy; {$smarty.now|date_format:'%Y'} Blogy. Все права защищены.</div>
</footer>
</body>
</html>
