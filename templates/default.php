<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title><?=WebPage::gi()->get(WebPage::TITLE);?></title>

    <link rel="stylesheet" href="../design/css/style.css" type="text/css">
    <link rel="stylesheet" href="../design/css/style-ui.css" type="text/css">
    <link rel="stylesheet" href="../design/css/jq-ui.css" type="text/css">

    <link rel="icon" href="../design/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../design/images/favicon.ico" type="image/x-icon">

    <script type="text/javascript"  src="http://yandex.st/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript"  src="../js/core.js" defer="defer"></script>
</head>
<body>
<? if (!Account::isAuth()) { ?>
<div class="primary-message clear-fix">
    <div class="container" style="text-align: center;">
        WebArena - это классные фото и видео каждый день! <a href="/join/">Присоединяйтесь к нам!</a> <a href="" class="close">x</a>
    </div>
</div>
<? } ?>
<header class="clear-fix">
    <? if (Account::isAuth()) { ?>
        <div class="container">
            <div class="upper_menu">
                <div class="logo">
                    <a href="/"><span class="fui-radio-checked"></span> WebArena</a>
                </div>
                <nav class="navigate_menu">
                    <ul>
                        <li><a href="/">Лучшее за сегодня</a></li>
                        <li><a href="/">Свежее</a></li>
                    </ul>
                </nav>
                <div class="add_post_action">
                    <a href="/add/"><span class="fui-new"></span> Добавить пост</a>
                </div>
                <div class="profile">
                    <a href="/profile-<?=Account::getCurrent()->getId();?>">
                        <span class="fui-user"></span> <?=Account::getCurrent()->getLogin();?>
                    </a>
                </div>
                <div class="action">
                    <a href="/profile-<?=Account::getCurrent()->getId();?>">
                        <span class="fui-gear"></span>
                    </a>
                </div>
                <div class="action">
                    <a href="/transition.php?act=exit">
                        <span class="fui-power"></span>
                    </a>
                </div>
            </div>
        </div>
    <? } else { ?>
        <div class="container">
            <div class="upper_menu">
                <div class="logo">
                    <a href="/"><span class="fui-radio-checked"></span> WebArena</a>
                </div>
                <nav class="navigate_menu">
                    <ul>
                        <li><a href="/">Лучшее за сегодня</a></li>
                        <li><a href="/">Свежее</a></li>
                    </ul>
                </nav>
                <div class="action" style="width: 100px;">
                    <a href="/auth/">Войти</a>
                </div>
                <div class="action" style="width: 100px;">
                    <a href="/join/">Регистрация</a>>
                </div>
            </div>
        </div>
    <? } ?>
</header>
<div class="content" style="padding-top: 10px;">
    <div class="container">
        <?=WebPage::gi()->get(WebPage::CONTENT);?>
    </div>
</div>
<footer class="clear-fix">
    <div class="container">
        <nav class="fl_l">
            <ul>
                <li><a href="">Контакты</a></li>
                <li><a href="">Блог</a></li>
                <li><a href="">FAQ</a></li>
                <li><a href="">Правила сайта</a></li>
            </ul>
        </nav>
        <div class="copyright">
            Copyright © 2014 WebArena, Inc. All rights reserved.
        </div>
    </div>
</footer>
</body>
</html>