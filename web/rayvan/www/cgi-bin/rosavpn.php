<?php
require_once('inc/config.inc.php');
require_once(RAYVAN_LIBS.'/classes/SmartySetup.class.php');
require_once(RAYVAN_LIBS.'/classes/rayvan/server/RosaVpn/RosaVpnController.class.php');
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanFunctions.class.php");
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanError.class.php");
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanDebug.class.php");
$smarty = new SmartySetup();

/*
**json:request
**	{"version":"","module":"","action":"",...}
**json:respond
**	{"version":"","module":"","result":"","data":{}}
*/

header("Content-type: text/html; charset=utf-8");
$func = new RayvanFunctions();
$array_data = null;
$RAYVAN_conf = array();
$request_mode = "";

$config_path = $func->get_app_config_path();
$config = $config_path.'/app.conf';
$func->xhsjweb_conf_parse($config, $RAYVAN_conf);

$json_data = $func->get_data_from_http();

function rayvan_web_handle(&$array_data, $request_mode, $rayvan_conf, $json_data){
	$request_action = "";
	if($json_data){
		if(array_key_exists('action', $json_data)){
			$request_action = $json_data->action;
		}else{
			$array_data = Array("code"=>RayvanError::RAYVAN_WEB_VERSION_REQUEST_ERROR); //action为空
		}
	}else{
		if(isset($_REQUEST['action'])){
			$request_action = $_REQUEST['action'];
		}else{
			$array_data = Array("code"=>RayvanError::RAYVAN_WEB_VERSION_REQUEST_ERROR); //action为空
		}
	}

	switch($request_mode){
	case "rosavpn":
		$controller = new RosaVpnController($request_action, $rayvan_conf, $json_data);
		$controller->client_controller($array_data, $controller);
		break;
	default:
		$array_data = Array("code"=>RayvanError::RAYVAN_WEB_VERSION_REQUEST_ERROR); //module错误
		break;
	}
}

if($json_data){
	if(array_key_exists('version', $json_data)){
		$rc = $func->check_version($json_data->version);
		RayvanDebug::rayvan_web_log('LOG_LEVEL_INFO', 'rosavpn', 
			'--request-json-string:'.json_encode($json_data));
		if($rc == 0){
			if(array_key_exists('module', $json_data)){
				$request_mode = $json_data->module;
				rayvan_web_handle($array_data, $request_mode, $RAYVAN_conf, $json_data);
			}else{
				$array_data = Array("code"=>RayvanError::RAYVAN_WEB_MODE_WAS_NULL); //module为空
			}
       		}else{
			$array_data = Array("code"=>RayvanError::RAYVAN_VERSION_ERROR); //版本不对
		}
	}else{
		$array_data = Array("code"=>RayvanError::RAYVAN_WEB_VERSION_REQUEST_ERROR); //version为空
	}
}elseif(isset($_REQUEST['version'])){
	$rc = $func->check_version($_REQUEST['version']);
	if($rc == 0){
		if(isset($_REQUEST['module'])){
			RayvanDebug::rayvan_web_log('LOG_LEVEL_INFO', 'rosavpn', 
'--request-from-version:version='.$_REQUEST['version'].'--module='.$_REQUEST['module'].'--action='.$_REQUEST['action']);
			$request_mode = $_REQUEST['module'];
			rayvan_web_handle($array_data, $request_mode, $RAYVAN_conf, null);
		}else{
			$array_data = Array("code"=>RayvanError::RAYVAN_WEB_MODE_WAS_NULL); //module为空
		}
       	}else{
		$array_data = Array("code"=>RayvanError::RAYVAN_VERSION_ERROR); //版本不对
	}
}else{
	$array_data = Array("code"=>RayvanError::UNKNOWN_ERROR); //未知错误
}

if($array_data){
	$func->respond_message($array_data, $request_mode);
}
?>
