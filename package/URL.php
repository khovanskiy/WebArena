<?

class URL {

    private static $current = null;
    private $protocol;
    private $host;
    private $port;
    private $module;
    private $params;

    public function __construct($host, $port = 80, $module, $params) {
        $this->port = (int) $port;
        if ($this->port == 443) {
            $this->protocol = "https";
        } else {
            $this->protocol = "http";
        }
        $this->module = $module;
        $this->host = $host;
    }

    /**
     * @return URL
     */
    public static function getCurrent() {
        if (self::$current == null) {
            self::$current = new URL($_SERVER["HTTP_HOST"], $_SERVER["SERVER_PORT"], Request::get("module"), $_GET);
        }
        return self::$current;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return URL
     */
    public function setParam($key, $value) {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return URL
     */
    public function removeParam($key) {
        unset($this->params[$key]);
        return $this;
    }

    /**
     * @return string
     */
    public function relative() {
        $url = "";
        if (!empty($this->module) && $this->module != "index") {
            $url .= "/".$this->module."/";
        }
        if (count($this->params) > 0) {
            $url .= "?";
            foreach ($this->params as $key => $value) {
                $url .= urlencode($key) . "=" . urlencode($value);
            }
        }
        return $url;
    }

    public function absolute() {
        return $this->protocol . "://" . $this->host . $this->relative();
    }
}