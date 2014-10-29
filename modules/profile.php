<?
if (!Account::isAuth()) {
    WebPage::gi()->redirect("/");
}
$login = Request::get("login");
$sth = Database::gi()->execute("select user_id, login from users where login = ? limit 1", array($login));
if ($sth->rowCount() == 0) {
    WebPage::gi()->redirect("/");
}
$current_user = $sth->fetch(PDO::FETCH_ASSOC);
WebPage::gi()->set(WebPage::TITLE, "Профиль пользователя " . $current_user["login"]);
WebPage::gi()->beginSet(WebPage::CONTENT);
?>
<div class="container">
    <div class="default_page" style="padding: 25px;">
        <h1><?=$current_user["login"];?></h1>
    </div>
</div>
<?
WebPage::gi()->endSet();