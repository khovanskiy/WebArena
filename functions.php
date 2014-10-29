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

date_default_timezone_set("Europe/Moscow");
function decorateDatetime($datetime)
{
    $diff = strtotime("now - 1 hour") - strtotime($datetime); // workaround
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

/**
 * @param string $text
 * @param int $max_length
 * @return string
 */
function decorateLength($text, $max_length = 200)
{
    $text = strip_tags(trim($text));
    if (mb_strlen($text) > $max_length) {
        $mas = preg_split("/[\.\?\!]/", $text);
        $text = "";
        $i = 0;
        $len = count($mas);
        while (mb_strlen($text) < $max_length && $i < $len) {
            $text .= $mas[$i++] . ". ";
        }
    }
    return $text;
}

/**
 * @param int $positive
 * @param int $negative
 * @return float
 */
function getPopularity($positive, $negative)
{
    if ($positive == 0 && $negative == 0) {
        return 0.0;
    }
    return (($positive + 1.9208) / ($positive + $negative) - 1.96 * sqrt(($positive * $negative) / ($positive + $negative) + 0.9604) / ($positive + $negative)) / (1 + 3.8416 / ($positive + $negative));
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
                <div class="text-preview">
                    <div class="text">
                        <div style="width: 100%; height: 100%; overflow: hidden;">
                            <?= decorateLength($row["cached_text"], 1500); ?>
                        </div>
                    </div>
                    <div class="shadow">

                    </div>
                </div>
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

function updatePostsCache()
{
    $sth = Database::gi()->execute("set @fresh_page:= 0; update posts set posts.fresh_page = (CEIL((@fresh_page:= @fresh_page + 1) / ?)) ORDER BY creation_time DESC;", array(20));
    $sth->closeCursor(); // else PDO error #2014
    //update posts set popularity = ((positive + 1.9208) / (positive + negative) - 1.96 * SQRT((positive * negative) / (positive + negative) + 0.9604) / (positive + negative)) / (1 + 3.8416 / (positive + negative)) where positive <> 0 || negative <> 0
    Database::gi()->execute("set @popular_page:= 0; update posts set posts.popular_page = (CEIL((@popular_page:= @popular_page + 1) / ?)) ORDER BY popularity DESC, creation_time DESC;", array(20));
}

function repeatAndImplode($glue, $part, $count)
{
    if ($count == 0) {
        return "";
    }
    if ($count == 1) {
        return $part;
    }
    $result = $part;
    for ($i = 1; $i < $count; ++$i) {
        $result .= $glue . $part;
    }
    return $result;
}

if (!function_exists('array_column')) {

    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }

        if (!is_array($params[0])) {
            trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
            return null;
        }

        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string)$params[1] : null;

        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int)$params[2];
            } else {
                $paramsIndexKey = (string)$params[2];
            }
        }

        $resultArray = array();

        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string)$row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }
        }
        return $resultArray;
    }
}

function addTextPost($title, $meta_text, $thumbnail_url)
{
    $cached_text = Parsedown::instance()->text($meta_text);
    Database::gi()->execute("insert into posts (user_id, type, creation_time, title, meta_text, cached_text, thumbnail_url) values(?, ?, now(), ?, ?, ?, ?)", array(Account::getCurrent()->getId(), 1, $title, $meta_text, $cached_text, $thumbnail_url));
    $post_id = Database::gi()->lastInsertId("post_id");

    return $post_id;
}

function addTags($post_id, array $tags) {
    Database::gi()->execute("insert IGNORE into tags (tag_id, name) values" . repeatAndImplode(", ", "(null, ?)", count($tags)) . "", $tags);

    $sth = Database::gi()->execute("select tag_id, name from tags where name in (" . repeatAndImplode(",", "?", count($tags)) . ")", $tags);
    $tags_ids = $sth->fetchAll(PDO::FETCH_ASSOC);
    $tags_ids = array_column($tags_ids, "tag_id");

    Database::gi()->execute("insert into posts_tags (post_id, tag_id) values" . repeatAndImplode(", ", "($post_id, ?)", count($tags_ids)) . "", $tags_ids);
}

function getTags($post_id) {
    $sth = Database::gi()->execute("select tags.tag_id, tags.name from posts_tags, tags where posts_tags.tag_id = tags.tag_id and posts_tags.post_id = ?", array($post_id));
    return $sth->fetchAll(PDO::FETCH_ASSOC);
}

function addVideoPost($title, $meta_text, $content_url)
{
    $parser = new OEmbedParser();
    $cached_text = Parsedown::instance()->text($meta_text);
    $video = $parser->parse($content_url);
    Database::gi()->execute("insert into posts (user_id, type, creation_time, title, meta_text, cached_text, thumbnail_url, content_url, content_type) values(?, ?, now(), ?, ?, ?, ?, ?, ?)", array(Account::getCurrent()->getId(), 4, $title, $meta_text, $cached_text, $video->thumbnail_url, $video->content_id, $video->content_type));
    return Database::gi()->lastInsertId("post_id");
}

function parseTags($tags_string)
{
    if (mb_strlen(trim($tags_string)) == 0) {
        return array();
    }
    $tags = mb_split(",", $tags_string);
    for ($i = 0; $i < count($tags); ++$i) {
        $tags[$i] = trim($tags[$i]);
    }
    return $tags;
}

function generateMenu($current, $max) {
    ?>
    <ul class="pages_menu">
    <?
    for ($i = 1; $i <= $max; ++$i) {
        ?>
            <li <?=($i == $current ? 'class="selected"' : '');?>><a href="<?=URL::getCurrent()->setParam("page", $i)->relative();?>"><?=$i;?></a></li>
        <?
    }
    ?>
    </ul>
    <?
}