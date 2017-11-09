<?php
require_once('inc/config.inc.php');
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanFunctions.class.php");
class RedisHandle{
	private $redis_conn;

	function __construct($host, $port, $password){
		$this->connect($host, $port, $password);
	}
	function __destruct(){
		$this->disconnect();
	}

	private function connect($host, $port, $password){
		$this->redis_conn = new Redis();
		$this->redis_conn->connect($host, $port);
		$this->redis_conn->auth($password);
	}
	private function disconnect(){
	}

	function __exists($key){
		/* key存在return 1，否则return 0 */
		return $this->redis_conn->exists($key);
	}

        function __delete($key){
                $this->redis_conn->delete($key);
        }

	function string_set($key, $value){
		return $this->redis_conn->set($key, $value);
	}

	function string_get($key){
		return $this->redis_conn->get($key);
	}
	
	function __expire($key, $time){
		$this->redis_conn->expire($key, $time);
	}

	function array_set($key, $array){
		$this->redis_conn->hmset($key, $array);
	}

	function array_get_all($key){
		return $this->redis_conn->hgetall($key);
	}

	function array_get_by_index($key, $index){
		return $this->redis_conn->hget($key, $index);
	}
	
	function __lPush($key, $value){
		$this->redis_conn->lPush($key, $value);
	}

	function __lPop($key){
		return $this->redis_conn->lPop($key);
	}

	function __hSet($key, $field, $value){
		$this->redis_conn->lPush($key, $field, $value);
	}

	function __hGet($key, $field){
		return $this->redis_conn->hget($key, $field);
	}
}
?>
