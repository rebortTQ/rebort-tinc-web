<?php
require_once('inc/config.inc.php');
Class RayvanDebug{
	public static $config_log_path = WEB_RAYVAN_LOG_FILE;
	/* 0 - none; 1 - fata ; 2 - error; 3 - warn ; 4 - info; 5 - debug; 6 - verbose */
	public static $config_log_level = WEB_APP_DEBUG_LEVEL;
	public static function rayvan_web_log($log_level, $module, $payload){
		$my_log_level = 0;

		switch($log_level){
		case "LOG_LEVEL_NONE":
			$my_log_level = 0;
			break;
		case "LOG_LEVEL_FATA":
			$my_log_level = 1;
			break;
		case "LOG_LEVEL_ERROR":
			$my_log_level = 2;
			break;
		case "LOG_LEVEL_WARN":
			$my_log_level = 3;
			break;
		case "LOG_LEVEL_INFO":
			$my_log_level = 4;
			break;
		case "LOG_LEVEL_DEBUG":
			$my_log_level = 5;
			break;
		case "LOG_LEVEL_VERBOSE":
			$my_log_level = 6;
			break;
		default:
			break;
		}
	
		if($my_log_level == 0){
		}else{
			if($my_log_level <= RayvanDebug::$config_log_level){
                        	if(file_exists(RayvanDebug::$config_log_path)){
                                	file_put_contents(RayvanDebug::$config_log_path, date('Y-m-d h:i:sa').' ['.$log_level.'] module:'.$module.' '.$payload."\n"."\n", FILE_APPEND);
                        	}else{
                                	file_put_contents(RayvanDebug::$config_log_path, date('Y-m-d h:i:sa').' ['.$log_level.'] module:'.$module.' '.$payload."\n"."\n");
                        	}
			}
		}	
	}
};

?>
