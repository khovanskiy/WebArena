<?
class Buffer
{
	private static $data = array();
	
	public static function beginRecord($id)
	{
		array_push(self::$data,$id);
		ob_start();
	}
	
	public static function endRecord()
	{
		$buffer = ob_get_clean();
		self::$data[array_pop(self::$data)] = $buffer;
		return $buffer;
	}
	
	public static function size()
	{
		return count(self::$data);
	}
	
	public static function get($id)
	{
		if (isset(self::$data[$id]))
		{
			return self::$data[$id];
		}
		return "";
	}
}