<?
/*
 * Micropost - 0
 * Post - 1
 * Video - 2
 * Image - 3
 */
WebPage::gi()->set(WebPage::TITLE, "Ресурсы для разработчиков");
WebPage::gi()->beginSet(WebPage::CONTENT);

function buildGrid($rows, $offset, $count) {

    if (count($rows) < $offset + $count) {
        $count = count($rows) - $offset;
    }
    if ($count <= 0) {
        return 0;
    }
    if ($count % 2 != 0 && $count != 1) {
        $count--;
    }
    ?>
    <div class="posts_grid clear-fix">
    <?
    for ($i = 0; $i < $count; ++$i) {
        $row = $rows[$offset + $i];
        if ($count != 1) {
            ?>
            <div>
                <div class="post_cell">
                    <? if (!empty($row["thumbnail_url"])) { ?>
                        <div class="inner inner-background" style="background-image: url('<?=$row["thumbnail_url"];?>');">
                    <? } else { ?>
                        <div class="inner">
                    <? } ?>
                        <div class="header"></div>
                        <a href="/post-<?=$row["post_id"];?>">
                            <div class="content">
                                <div class="title"><?=$row["title"];?></div>
                            </div>
                        </a>
                        <div class="footer"><a href=""><?=$row["login"];?></a></div>
                    </div>
                </div>
            </div>
            <?
        } else if ($count == 1) {
            ?>
            <div>
                <div class="post_cell quad_cell">
                    <div class="inner" style="width: 50%">
                        <div class="header"></div>
                        <a href="/post-<?=$row["post_id"];?>">
                            <div class="content">
                                <div class="title"><?=$row["title"];?></div>
                            </div>
                        </a>
                        <div class="footer"><a href=""><?=$row["login"];?></a> <?=$row["post_creation_time"];?></div>
                    </div>
                    <? if (!empty($row["content_url"])) { ?>
                        <? if ($row["content_type"] == 0) { ?>
                            <div class="video">
                                <iframe width="100%" height="100%" src="//www.youtube-nocookie.com/embed/<?=$row["content_url"];?>" frameborder="0" allowfullscreen></iframe>
                            </div>
                        <? } else if ($row["content_type"] == 1) {?>
                            <div class="video">
                                <iframe src="//player.vimeo.com/video/<?=$row["content_url"];?>" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                        <? } ?>
                    <? } else if (!empty($row["thumbnail_url"])) { ?>
                        <div class="thumbnail" style="background-image: url('<?=$row["thumbnail_url"];?>');"></div>
                    <? } else { ?>
                        <div class="text"><?=$row["cached_text"];?></div>
                    <? } ?>
                </div>
            </div>
            <?
        }
    }
    ?>
    </div>
    <?
    return $count;
}
?>
<?
$sth = Database::gi()->execute("select posts.*, posts.creation_time as post_creation_time, users.login from posts, users where posts.user_id =  users.user_id order by posts.creation_time desc");

//$sth = Database::gi()->execute("select posts.*, posts.creation_time as post_creation_time, users.login, ((positive + 1.9208) / (positive + negative) - 1.96 * SQRT((positive * negative) / (positive + negative) + 0.9604) / (positive + negative)) / (1 + 3.8416 / (positive + negative)) AS ci_lower_bound from posts, users where posts.user_id =  users.user_id order by ci_lower_bound desc");

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
$offset = 0;
$read_count = 0;
while (($read_count = buildGrid($rows, $offset, 4)) > 0) {
    $offset += $read_count;
}
?>
<?
WebPage::gi()->endSet();