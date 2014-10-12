<?php
/**
 * Safe package loading
 *
 * @param string $package packages`s name
 */
function import($package)
{
    require_once(DOCUMENT_ROOT . "/" . $package);
}

/**
 * Word`s declension
 *
 * @param int $num count
 * @param string $word0 Example: 5, 11
 * @param string $word1 Example: 1, 21
 * @param string $word2 Example: 2, 22
 * @param string $wordnum0 Example: 0
 * @return string
 */
function langsnobRu($num, $word0, $word1, $word2, $wordnum0)
{
    if ($num == 0) {
        return $wordnum0;
    }

    switch ($num % 10) {
        case 1 :
            if ($num != 11) {
                return $num . " " . $word1;
            } else {
                return $num . " " . $word0;
            }
            break;
        case 2 :
        case 3 :
        case 4 :
            if ($num != 12 && $num != 13 && $num != 14) {
                return $num . " " . $word2;
            } else {
                return $num . " " . $word0;
            }
            break;
    }
    return $num . " " . $word0;
}

function decorateDatetime($datetime)
{
    $diff = strtotime("now") - strtotime($datetime);
    if ($diff <= 60) {
        return langsnobRu($diff, "секунд", "секунда", "секунды", "") . " назад";
    } else if ($diff <= 3600) {
        $diff = ceil($diff / 60);
        return langsnobRu($diff, "минут", "минута", "минуты", "") . " назад";
    } else if ($diff <= 86400) {
        $diff = ceil($diff / 3600);
        return langsnobRu($diff, "часов", "час", "часа", "") . " назад";
    }
    $diff = ceil($diff / 86400);
    return langsnobRu($diff, "дней", "день", "дня", "") . " назад";
}

function buildGrid(array &$rows, $offset, $counts)
{
    $count = count($counts);
    if ($count + $offset > count($rows)) {
        $count = count($rows) - $offset;
        if ($count == 3) {
            $counts = array(1, 2, 1);
        } else if ($count == 2) {
            $counts = array(2, 2);
        } else if ($count == 1) {
            $counts = array(4);
        }
    }
    if ($count <= 0) {
        return 0;
    }
    ?>
    <div class="posts_grid clear-fix">
    <?
for ($i = 0;
    $i < $count;
    ++$i) {
    $row = $rows[$offset + $i];

if ($counts[$i] != 4) {
    ?>
    <div style="width: <?= ($counts[$i] * 25); ?>%">
    <div class="post_cell">
        <? if (!empty($row["thumbnail_url"])) { ?>
        <div class="inner inner-background" style="background-image: url('<?= $row["thumbnail_url"]; ?>');">
            <? } else { ?>
            <div class="inner">
                <? } ?>
                <div class="header"></div>
                <a href="/post-<?= $row["post_id"]; ?>">
                    <div class="content">
                        <div class="title"><?= $row["title"]; ?></div>
                    </div>
                </a>

                <div class="footer">
                    <div class="author">
                        от <a href="/profile-<?= $row["user_id"]; ?>"><?= $row["login"]; ?></a>
                    </div>
                    <div class="time">
                        <time
                            datetime="<?= $row["post_creation_time"]; ?>"><?= decorateDatetime($row["post_creation_time"]); ?></time>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?
} else {
    ?>
    <div style="width: <?= ($counts[$i] * 25); ?>%">
        <div class="post_cell quad_cell">
            <div class="inner" style="width: 50%">
                <div class="header"></div>
                <a href="/post-<?= $row["post_id"]; ?>">
                    <div class="content">
                        <div class="title"><?= $row["title"]; ?></div>
                    </div>
                </a>

                <div class="footer">
                    <div class="author">
                        от <a href="/profile-<?= $row["user_id"]; ?>"><?= $row["login"]; ?></a>
                    </div>
                    <div class="time">
                        <time
                            datetime="<?= $row["post_creation_time"]; ?>"><?= decorateDatetime($row["post_creation_time"]); ?></time>
                    </div>
                </div>
            </div>
            <? if (!empty($row["content_url"])) { ?>
                <? if ($row["content_type"] == 0) { ?>
                    <div class="video">
                        <iframe width="100%" height="100%"
                                src="//www.youtube-nocookie.com/embed/<?= $row["content_url"]; ?>?rel=0&amp;controls=0&amp;showinfo=0"
                                frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                    </div>
                <? } else if ($row["content_type"] == 1) { ?>
                    <div class="video">
                        <iframe width="100%" height="100%"
                                src="//player.vimeo.com/video/<?= $row["content_url"]; ?>" frameborder="0"
                                allowfullscreen="allowfullscreen"></iframe>
                    </div>
                <? } else if ($row["content_type"] == 2) { ?>
                    <div class="video">
                        <iframe width="100%" height="100%"
                                src="//coub.com/embed/<?= $row["content_url"]; ?>?muted=false&autostart=false&originalSize=false&hideTopBar=true&startWithHD=false"
                                allowfullscreen="allowfullscreen" frameborder="0"></iframe>
                    </div>
                <? } ?>
            <? } else if (!empty($row["thumbnail_url"])) { ?>
                <div class="thumbnail" style="background-image: url('<?= $row["thumbnail_url"]; ?>');"></div>
            <? } else { ?>
                <div class="text"><?= $row["cached_text"]; ?></div>
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