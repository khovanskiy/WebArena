<?
WebPage::gi()->set(WebPage::TITLE, "Ресурсы для разработчиков");
WebPage::gi()->beginSet(WebPage::CONTENT);
?>
<div class="container">
    <div class="default_page">
        <div class="form" style="width: 50%; float: left; padding: 25px 10px 25px 25px;">
            <h2>Вход на WebArena</h2>
            <form action="/transition.php?act=auth" method="post">
                <div class="row">
                    <label>Логин</label>
                    <div class="input">
                        <input type="text" name="login"/>
                    </div>
                </div>
                <div class="row">
                    <label>Пароль</label>
                    <div class="input">
                        <input type="password" name="password"/>
                    </div>
                </div>
                <div class="row">
                    <div class="input">
                        <button type="submit">Вход</button>
                    </div>
                </div>
            </form>
        </div>
        <div style="width: 50%; float: left; padding: 25px 25px 25px 10px;">

        </div>
    </div>
</div>
<?
WebPage::gi()->endSet();