<?
WebPage::gi()->set(WebPage::TITLE, "Ресурсы для разработчиков");
WebPage::gi()->beginSet(WebPage::CONTENT);

$sth = Database::gi()->execute("select * from pages where 1 oder by desc");
while ($row = $sth->fetch(PDO::FETCH_ASSOC))
{

}

WebPage::gi()->endSet();