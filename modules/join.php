<?
WebPage::gi()->set(WebPage::TITLE, "Ресурсы для разработчиков");
WebPage::gi()->beginSet(WebPage::CONTENT);
?>
<div class="form">
    <form>
        <div class="row">
            <div class="label">
                Логин
            </div>
            <div class="input">
                <input type="text" name="login"/>
            </div>
        </div>
        <div class="row">
            <div class="label">
                Пароль
            </div>
            <div class="input">
                <input type="password" name="password"/>
            </div>
        </div>
        <div class="row">
            <div class="input">
                <button type="submit">Регистрация</button>
            </div>
        </div>
    </form>
</div>
<?
WebPage::gi()->endSet();