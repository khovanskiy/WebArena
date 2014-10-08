<?
import("package/DBObject.php");

class Account extends DBObject
{
    private $user_id;
    private $login;
    private $permissions = 0;
    private static $current = null;

    /**
     * @param string $login
     * @param string $password
     * @return Account|null
     */
    public static function auth($login, $password)
    {
        $sth = Database::gi()->execute("select user_id, login, permissions from users where login = ? and password = ?", array($login, $password));
        if ($sth->rowCount() == 0) {
            return false;
        }
        $_SESSION["Account"]["login"] = $login;
        $_SESSION["Account"]["password"] = $password;

        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $account = new Account();
        $account->user_id = $row["user_id"];
        $account->login = $row["login"];
        $account->permissions = $row["permissions"];
        self::$current = $account;
        return true;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return Account|null
     */
    public static function getCurrent()
    {
        if (self::$current == null && isset($_SESSION["Account"]["login"])) {
            self::auth($_SESSION[__CLASS__]["login"], $_SESSION["Account"]["password"]);
        }
        return self::$current;
    }

    /**
     * @return bool
     */
    public static function isAuth()
    {
        return self::getCurrent() != null;
    }

    /**
     * @param int $flags
     * @return bool
     */
    public static function access($flags)
    {
        return Account::isAuth() && Account::getCurrent()->permissions($flags);
    }

    /**
     * @param int $flags
     * @return bool
     */
    public function permissions($flags)
    {
        return ($this->permissions & $flags) == $flags;
    }
} 