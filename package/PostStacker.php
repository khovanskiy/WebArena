<?
import("package/Log.php");

class PostStacker
{
    private static $instance = null;
    const MAX_INT = 4;

    /**
     * @return PostStacker
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PostStacker();
        }
        return self::$instance;
    }

    private $consistency = true;
    private $states = array();
    private $shifts = array();
    private $answers = array();

    /**
     * @return int
     */
    private function addState()
    {
        $go = array();
        for ($i = 0; $i < self::MAX_INT + 1; ++$i) {
            $go[$i] = -1;
        }
        array_push($this->states, $go);
        array_push($this->shifts, array());
        array_push($this->answers, null);
        return count($this->states) - 1;
    }

    private function __construct()
    {
        $this->addState();

        $a1111 = array(1, 1, 1, 1);
        $a22 = array(2, 2);
        $a4 = array(4);
        $this->addRule("1", $a4);
        $this->addRule("1, 1", $a22);
        $this->addRule("1, 2", $a22);
        $this->addRule("1, 1, 1, (2, 1)", $a1111);
        $this->addRule("{1, 1, 1, 4}, 1", $a4);
        $this->addRule("{1, 1, 1, 4}, 2", $a4);
        $this->addRule("1, 1, 1, 1", $a1111);
        $this->addRule("1, 1, (2, 1, 1)", $a1111);
        $this->addRule("1, (2, 1, 1, 1)", $a1111);
        $this->addRule("{1, 1, 4}, 1, 1", $a4);
        $this->addRule("{1, 4}, 1, 1, 1", $a4);
        $this->addRule("{1, 4}, 1", $a4);
        $this->addRule("{1, 4}, 2", $a4);
        $this->addRule("2", $a4);
        $this->addRule("2, 1", $a22);
        $this->addRule("2, 2", $a22);
        $this->addRule("{2, 4}, 1", $a4);
        $this->addRule("{2, 4}, 2", $a4);
        $this->addRule("4", $a4);
        $this->addRule("4, 4", $a22);
    }

    /**
     * @return bool
     */
    public function checkConsistency()
    {
        return $this->consistency;
    }

    /**
     * @param string $input
     * @param array $answer
     * @return bool
     */
    public function addRule($input, array $answer)
    {
        $commas = 0;
        $roundBracketBegin = 0;
        $squareBracketBegin = 0;

        $shiftList = array();

        for ($i = 0; $i < strlen($input); $i++) {
            switch ($input[$i]) {
                case ',':
                    $commas++;
                    break;
                case '(':
                    $roundBracketBegin = $commas;
                    break;
                case ')':
                    array_push($shiftList, $roundBracketBegin);
                    array_push($shiftList, $commas);
                    break;
                case '{':
                    $squareBracketBegin = $commas;
                    break;
                case '}':
                    array_push($shiftList, $commas);
                    array_push($shiftList, $squareBracketBegin);
                    break;
            }
        }

        $temp = "";
        for ($i = 0; $i < strlen($input); $i++) {
            if (!in_array($input[$i], array('[', '(', ')', '{', '}', ']'))) {
                $temp .= $input[$i];
            }
        }
        $input = $temp;
        $strings = mb_split(",", $input);

        $stateNumber = 0;
        for ($i = 0; $i < count($strings); $i++) {
            $state = & $this->states[$stateNumber];
            $number = (int)trim($strings[$i]);
            if ($state[$number] == -1) {
                $state[$number] = $this->addState();
            }
            $stateNumber = $state[$number];
        }

        if ($this->answers[$stateNumber] != null) {
            $this->consistency = false;
        }

        $shift = array();
        for ($i = 0; $i < count($shiftList); ++$i) {
            $shift[$i] = $shiftList[$i];
        }

        $this->shifts[$stateNumber] = $shift;
        $this->answers[$stateNumber] = $answer;

        return $this->consistency;
    }

    /**
     * @param array $mass
     * @param int $offset
     * @return array
     */
    public function match(array &$mass, $offset)
    {
        $stateNumber = 0;
        $answerStateNumber = $stateNumber;

        for ($i = $offset; $i < count($mass); $i++) {
            $state = & $this->states[$stateNumber];
            if ($state[$mass[$i]["type"]] == -1) {
                break;
            }
            $stateNumber = $state[$mass[$i]["type"]];
            if ($this->answers[$stateNumber] != null) {
                $answerStateNumber = $stateNumber;
            }
        }

        $shift = & $this->shifts[$answerStateNumber];

        for ($j = 0; $j < count($shift) / 2; ++$j) {
            $begin = $offset + $shift[2 * $j];
            $end = $offset + $shift[2 * $j + 1];
            $tmp = $mass[$begin];
            if ($begin < $end) {
                for ($k = $begin; $k < $end; ++$k) {
                    $mass[$k] = $mass[$k + 1];
                }
            } else {
                for ($k = $begin; $k > $end; --$k) {
                    $mass[$k] = $mass[$k - 1];
                }
            }
            $mass[$end] = $tmp;

        }

        return $this->answers[$answerStateNumber];
    }

    private function write(array &$mass, $key) {
        echo "<ul>";
        for ($i = 0; $i < count($mass) && $i < 5; ++$i) {
            echo "<li>".$mass[$i][$key] . "</li>";
        }
        echo "</ul>";
    }

    public function moveRight(array &$array, $positions)
    {
        $size = count($array);
        for ($i = 0; $i < $positions; $i++) {
            $temp = $array[$size - 1];
            for ($j = $size - 1; $j > 0; $j--) {
                $array[$j] = $array[$j - 1];
            }
            $array[0] = $temp;
        }
    }

    public function moveLeft(array &$array, $positions)
    {
        $size = count($array);
        for ($i = $size; $i > $positions; --$i) {
            $temp = $array[$size - 1];
            for ($j = $size - 1; $j > 0; --$j) {
                $array[$j] = $array[$j - 1];
            }
            $array[0] = $temp;
        }
    }
}