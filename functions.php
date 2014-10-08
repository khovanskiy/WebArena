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

function func($array, $offset) {
    if ($offset >= count($array)) {
        return array(0);
    } else {
        $a4 = array(4);
        $a22 = array(2, 2);
        $a1111 = array(1, 1, 1, 1);

        switch($array[$offset]["type"]) {
            case 1:
                if ($offset == count($array) - 1) return $a4;
                    switch ($array[$offset + 1]["type"]) {
                        case 1:
                            if ($offset == count($array) - 2) return $a22;
                            switch ($array[$offset + 2]["type"]) {
                                case 1:
                                    if ($offset == count($array) - 3) return $a22;
                                    switch ($array[$offset + 3]["type"]) {
                                        case 1:
                                            return $a1111;
                                        case 2:
                                            if ($offset == count($array) - 4) return $a22;
                                            if ($array[$offset + 4]["type"] == 1) {
                                                $tmp = $array[$offset + 3];
                                                $array[$offset + 3] = $array[$offset + 4];
                                                $array[$offset + 4] = $tmp;
                                                return $a1111;
                                            } else {
                                                return $a22;
                                            }
                                        case 4:
                                            if ($offset == count($array) - 4) return $a22;
                                            if ($array[$offset + 4]["type"] != 4) {
                                                $tmp = $array[$offset + 3];
                                                $array[$offset + 3] = $array[$offset + 2];
                                                $array[$offset + 2] = $array[$offset + 1];
                                                $array[$offset + 1] = $array[$offset];
                                                $array[$offset] = $tmp;
                                                return $a4;
                                            } else {
                                                return $a22;
                                            }
                                    }
                                case 2:
                                case 4:
                                    if ($offset < count($array) - 4 && $array[$offset + 3]["type"] == 1 && $array[$offset + 4]["type"] == 1) {
                                        $tmp = $array[$offset + 2];
                                        $array[$offset + 2] = $array[$offset + 3];
                                        $array[$offset + 3] = $array[$offset + 4];
                                        $array[$offset + 4] = $tmp;
                                        return $a1111;
                                    } else {
                                        return $a22;
                                    }
                            }
                            break;
                        case 2:
                            return a22;
                        case 4:
                            if ($offset == count($array) - 2) return $a4;
                            if ($array[$offset + 2]["type"] != 4) {
                                $tmp = $array[$offset];
                                $array[$offset] = $array[$offset + 1];
                                $array[$offset + 1] = $tmp;
                            }
                            return $a4;
                    }
                case 2:
                    if ($offset == count($array) - 1) return $a4;
                    switch ($array[$offset + 1]["type"]) {
                        case 1:
                        case 2:
                            return $a22;
                        case 4:
                            if ($offset == count($array) - 2) return $a4;
                            if ($array[$offset + 2]["type"] != 4) {
                                $tmp = $array[$offset];
                                $array[$offset] = $array[$offset + 1];
                                $array[$offset + 1] = $tmp;
                            }
                            return $a4;
                    }
                    break;
                case 4:
                    if ($offset == count($array) - 1) return $a4;
                    switch ((int)$array[$offset + 1]["type"]) {
                        case 1:
                        case 2:
                            echo "2A=4";
                            return $a4;
                        case 4:
                        {
                            if ($offset == count($array) - 2) {
                                return $a4;
                            }
                            switch ($array[$offset + 2]["type"]) {
                                case 1:
                                case 2:
                                    $tmp = $array[$offset + 1];
                                    $array[$offset + 1] = $array[$offset + 2];
                                    $array[$offset + 2] = $tmp;
                                    return $a4;
                                case 4:
                                    return $a22;
                            }
                        } break;
                    }
                    break;
            }
        }
    return null;
}