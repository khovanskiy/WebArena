<?
import("package/DBObject.php");

class Account extends DBObject {

    private $login;
    private $permissions = 0;
    private static $current = null;

    public static function find($login, $password)
    {
        $sth = Database::gi()->execute("select login, permissions from users where login = ?, password = ?", array($login, $password));
        if ($sth->rowCount() == 0) {
            return null;
        }
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $account = new Account();
        $account->login = $row["login"];
        $account->permissions = $row["permissions"];
        return $account;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public static function getCurrent()
    {
        return self::$current;
    }

    public static function isAuth()
    {
        return self::$current != null;
    }

    public static function access($flags)
    {
        return Account::isAuth() && Account::getCurrent()->permissions($flags);
    }

    public function permissions($flags)
    {
        return ($this->permissions & $flags) == $flags;
    }
} 