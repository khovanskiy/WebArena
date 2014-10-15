<?
$url = Request::get("url");
WebPage::gi()->beginSet(WebPage::CONTENT);
?>
<div class="container">
    <div class="default_page" style="padding: 25px;">
        <h2>Переход по внешней ссылке</h2>
        <div style="padding: 5px 0; font-size: 1.4em; color: #666;">
            <p>Внимание! Вы собираетесь перейти по внешней ссылке <strong><?=$url;?></strong></p>

            <p>Мы не несём ответственности за содержимое этой ссылки.</p>

            <p>Если Вы еще не передумали, нажмите на <a href="<?=$url;?>"><?=$url;?></a></p>
        </div>
    </div>
</div>
<?
WebPage::gi()->endSet();