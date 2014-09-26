<?

class WebPage
{
    const CONTENT = "webpage_content";
    const TITLE = "webpage_title";

    private static $instance = null;
    private $data = array();
    private $module = "";
    private $template = "";
    private $isGenerated = false;
    private $layers = array();

    private function __construct()
    {
    }

    public static function gi()
    {
        if (self::$instance == null) {
            self::$instance = new WebPage();
        }
        return self::$instance;
    }

    public function set($field_name, $value)
    {
        $this->data[$field_name] = $value;
    }

    public function get($field_name, $default = "")
    {
        if (isset($this->data[$field_name])) {
            return $this->data[$field_name];
        }
        return $default;
    }

    /**
     * Open new buffer and put it at the top of stack
     *
     * @param string $field_name key
     */
    public function beginSet($field_name)
    {
        array_push($this->layers, $field_name);
        ob_start();
    }

    /**
     * Close last buffer, remove top of stack and save all its data
     */
    public function endSet()
    {
        if (count($this->layers) > 0) {
            $field_name = array_pop($this->layers);
            $this->data[$field_name] = ob_get_clean();
        }
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function hasModule($module)
    {
        return file_exists($this->generateModulePath($module));
    }

    public function hasTemplate($template)
    {
        return file_exists($this->generateTemplatePath($template));
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Redirect to another page
     *
     * @param string $url
     */
    public function redirect($url)
    {
        header("Location: " . $url);
    }

    public function generate()
    {
        if (!$this->isGenerated) {
            $this->isGenerated = true;
            ob_start();
            include($this->generateModulePath($this->module));
            ob_clean();
            include($this->generateTemplatePath($this->template));
        }
    }

    private function generateModulePath($module)
    {
        return DOCUMENT_ROOT."/modules/" . $module . ".php";
    }

    private function generateTemplatePath($template)
    {
        return DOCUMENT_ROOT."/templates/" . $template . ".php";
    }
}