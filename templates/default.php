<?
$webpage = WebPage::gi();
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title><?=$webpage->get(WebPage::TITLE);?></title>

    <link rel="stylesheet" href="../design/css/style.css" type="text/css">
    <link rel="stylesheet" href="../design/css/style-ui.css" type="text/css">
    <link rel="stylesheet" href="../design/css/jq-ui.css" type="text/css">

    <link rel="icon" href="../design/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../design/images/favicon.ico" type="image/x-icon">

    <script type="text/javascript"  src="http://yandex.st/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript"  src="../js/core.js" defer="defer"></script>
</head>
<body>
<div class="layout">
    <div class="wrapper">
        <header role="banner">
            Blog
        </header>
        <nav role="navigation">
            <a href="">1</a>
            <a href="">2</a>
            <a href="">3</a>
            <a href="">4</a>
        </nav>

        <div class="columns">
            <div class="column">
                <?=$webpage->get(WebPage::CONTENT);?>
            </div>
            <div class="column">
                <div class="widget">

                </div>
                <div class="widget">
                    <h3></h3>
                    <div class="content">

                    </div>
                </div>
                <div class="widget">
                    <h3>Категории</h3>
                    <div class="content">

                    </div>
                </div>
                <div class="widget">
                    <h3>Популярные статьи</h3>
                    <div class="content">

                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div>

            </div>
            <div>

            </div>
        </footer>
    </div>
</div>

</html>