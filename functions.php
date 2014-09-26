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
function langsnob($num, $word0, $word1, $word2, $wordnum0)
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