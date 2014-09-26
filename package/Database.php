<?

class Database
{
    private static $instance = null;
    private static $pdo = null;

    private static $cache = array();

    private function __construct()
    {
        self::$pdo = new PDO("mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD);
        self::$pdo->query("set names utf8");
    }

    public function query($query)
    {
        return self::$pdo->query($query);
    }

    public function execute($query, $params = array())
    {
        if (isset(self::$cache[$query])) {
            $sth = self::$cache[$query];
        } else {
            self::$cache[$query] = $sth = self::$pdo->prepare($query);
        }
        $sth->execute($params);
        return $sth;
    }

    public static function gi()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
}