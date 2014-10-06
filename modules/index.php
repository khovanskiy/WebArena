<?
WebPage::gi()->set(WebPage::TITLE, "Ресурсы для разработчиков");
WebPage::gi()->beginSet(WebPage::CONTENT);

function buildGrid($rows, $offset, $count) {
    if (count($rows) < $offset + $count) {
        $count = $offset + $count - count($rows);
    }
    ?>
    <div class="posts_grid clear-fix">
    <?
    for ($i = 0; $i < $count; ++$i) {
        $row = $rows[$offset + $i];
        ?>
        <div>
            <div class="post_cell">
                <div class="header"></div>
                <div class="content"><?=$row["title"];?></div>
                <div class="footer"><?=$row["login"];?></div>
            </div>
        </div>
        <?
    }
    ?>
    </div>
    <?
    return $count;
}
?>
<?
$sth = Database::gi()->execute("select * from posts, users where posts.user_id =  users.user_id order by posts.creation_time desc");
$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
buildGrid($rows, 0, 2);
buildGrid($rows, 3, 1);
buildGrid($rows, 0, 4);
?>
<?
WebPage::gi()->endSet();