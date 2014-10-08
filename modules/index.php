<?
import("package/PostStacker.php");
/*
 * Post - 1
 * Image - 2
 * Video - 4
 */
WebPage::gi()->set(WebPage::TITLE, "Ресурсы для разработчиков");
WebPage::gi()->beginSet(WebPage::CONTENT);


function buildGrid(array &$rows, $offset, $counts) {
    $count = count($counts);
    if ($count + $offset > count($rows)) {
        $count = count($rows) - $offset;
    }
    if ($count <= 0) {
        return 0;
    }
    ?>
    <div class="posts_grid clear-fix">
    <?
    for ($i = 0; $i < count($counts); ++$i) {
        $row = $rows[$offset + $i];

        if ($counts[$i] != 4) {
            ?>
            <div style="width: <?=($counts[$i] * 25);?>%">
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
                        <div class="footer">
                            <div class="author">
                                от <a href="/profile-<?=$row["user_id"];?>"><?=$row["login"];?></a>
                            </div>
                            <div class="time">
                                <time datetime="<?=$row["post_creation_time"];?>"><?=decorateDatetime($row["post_creation_time"]);?></time>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?
        } else {
            ?>
            <div style="width: <?=($counts[$i] * 25);?>%">
                <div class="post_cell quad_cell">
                    <div class="inner" style="width: 50%">
                        <div class="header"></div>
                        <a href="/post-<?=$row["post_id"];?>">
                            <div class="content">
                                <div class="title"><?=$row["title"];?></div>
                            </div>
                        </a>
                        <div class="footer">
                            <div class="author">
                                от <a href="/profile-<?=$row["user_id"];?>"><?=$row["login"];?></a>
                            </div>
                            <div class="time">
                                <time datetime="<?=$row["post_creation_time"];?>"><?=decorateDatetime($row["post_creation_time"]);?></time>
                            </div>
                        </div>
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

$sth = Database::gi()->execute("select posts.type, posts.*, posts.creation_time as post_creation_time, users.login from posts, users where posts.user_id =  users.user_id order by posts.creation_time desc");

//$types = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
//print_r($types);
//$sth = Database::gi()->execute("select posts.*, posts.creation_time as post_creation_time, users.login, ((positive + 1.9208) / (positive + negative) - 1.96 * SQRT((positive * negative) / (positive + negative) + 0.9604) / (positive + negative)) / (1 + 3.8416 / (positive + negative)) AS ci_lower_bound from posts, users where posts.user_id =  users.user_id order by ci_lower_bound desc");

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

for ($i = 0; $i < count($rows); ++$i) {
    echo $rows[$i]["type"];
}

/*$offset = 0;
$pattern = PostStacker::getInstance()->match($rows, $offset);
var_dump($pattern);
$offset += buildGrid($rows, $offset, $pattern);*/

$offset = 0;
$read_count = 0;

$pattern = PostStacker::getInstance()->match($rows, 0);
while (($read_count = buildGrid($rows, $offset, $pattern)) > 0) {
    $offset += $read_count;
    $pattern = PostStacker::getInstance()->match($rows, $offset);
}
?>

<?
WebPage::gi()->endSet();