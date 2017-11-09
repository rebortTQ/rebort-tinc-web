<?php
class Protocol
{
/*
* version :
*       1.0.1 --->  2017-11-09 开始为1.0.1,该版本为初版
*/
	static $version="1.0.1";
	private $mode;
	private $result;
	private $data;

        function __construct($json_data, $code, $mode)
        {
		$respond_array = Array("version"=>self::$version, "module"=>$mode, "result"=>$code, "data"=>$json_data);
		$this->mode = $mode;
		$this->result = $code;
		$this->data = json_encode($respond_array);
        }
        function __destruct()
        {
        }

	function to_string()
	{
		return $this->data;
	}

	function __get($proName)
	{
		return $this->$proName;
	}
	
	public static function get_version()
	{
		return self::$version;
	}
}
