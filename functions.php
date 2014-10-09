<?php
/**
 * Safe package loading
 *
 * @param string $package packages`s name
 */
function import($package)
{
    require_once(DOCUMENT_ROOT."/".$package);
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

function decorateDatetime($datetime) {
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
    return langsnobRu($diff, "дней", "день", "дня", "") . " назад";;
}

function youtube($url) {
    $connection = curl_init();
    parse_str(parse_url($url, PHP_URL_QUERY ), $url_vars);

    $video_id = $url_vars["v"];

    $url = sprintf("http://www.youtube.com/oembed?url=%s&format=json", urlencode($url));
    curl_setopt($connection,CURLOPT_URL, $url);
    curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($connection, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json'
    ));

    $json = curl_exec($connection);
    curl_close($connection);

    $data = json_decode($json, true);

    $result = array();
    $result["content_url"] = $video_id;
    $result["thumbnail_url"] = $data["thumbnail_url"];

    return $result;
}