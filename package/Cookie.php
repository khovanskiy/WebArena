<?
class Cookie
{
    public static function set($key, $value, $duration = 604800)
    {
        setcookie($key, $value, time() + $duration);
    }

	public static function get($key, $default = "")
	{
		if (isset($_COOKIE[$key]))
		{
			return $_COOKIE[$key];
		}
		return $default;
	}
}