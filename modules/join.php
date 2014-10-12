<?
if (Account::isAuth()) {
    WebPage::gi()->redirect("/");
}
WebPage::gi()->set(WebPage::TITLE, "Регистрация");
WebPage::gi()->beginSet(WebPage::CONTENT);
?>
<div class="container">
    <div class="default_page" style="padding: 25px;">
        <h2>Регистрация нового аккаунта</h2>
        <?
        switch ((int)Request::get("status", 0))
        {
            case 1:
            {
                ?>
                <div class="message error-message">
                    Вы не указали логин
                </div>
                <?
            } break;
            case 2:
            {
                ?>
                <div class="message error-message">
                    Вы не указали адрес электронной почты
                </div>
                <?
            } break;
            case 3:
            {
                ?>
                <div class="message error-message">
                    Вы не указали пароль
                </div>
            <?
            } break;
            case 4:
            {
                ?>
                <div class="message error-message">
                    Пользователь с данной электронной почтой уже зарегистрирован
                </div>
            <?
            } break;
            case 5:
            {
                ?>
                <div class="message error-message">
                    Пользователь с данным логином уже зарегистрирован
                </div>
            <?
            } break;
        }
        ?>
        <div class="form" style="margin-top: 20px;">
            <form action="/transition.php?act=registration" method="post">
                <div class="row">
                    <label>Логин</label>
                    <div class="input">
                        <input type="text" name="login" value="<?=$_SESSION["module-join"]["login"];?>"/>
                    </div>
                </div>
                <div class="row">
                    <label>Пароль</label>
                    <div class="input">
                        <input type="password" name="password"/>
                    </div>
                </div>
                <div class="row">
                    <label>Электронная почта</label>
                    <div class="input">
                        <input type="text" name="email" value="<?=$_SESSION["module-join"]["email"];?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="input">
                        <button type="submit">Зарегистрироваться</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?
WebPage::gi()->endSet();