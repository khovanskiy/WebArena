<?
class Request
{
	public static function post($key, $default = "")
	{
		if (isset($_POST[$key]))
		{
			return $_POST[$key];
		}
		return $default;
	}
	
	public static function get($key, $default = "", $available_values = null)
	{
		if (isset($_GET[$key]))
		{
            if ($available_values != null && !in_array($_GET[$key], $available_values)) {
                return $default;
            }
			return $_GET[$key];
		}
		return $default;
	}
}