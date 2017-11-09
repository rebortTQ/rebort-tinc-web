<?php
require_once('inc/config.inc.php');
require_once(RAYVAN_LIBS."/classes/rayvan/Protocol/Protocol.class.php");
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanDebug.class.php");

class RayvanFunctions
{
        function __construct(){
        }

        function __destruct()
        {
        }
	function xhsjweb_conf_parse($confPath, &$retArray)
	{
	        $retArray = parse_ini_file($confPath, true);
	}
	
	function get_app_config_path()
	{
		return __DIR__.'/config';
	}
	
	function get_data_from_http(){
	        $body = file_get_contents("php://input");
		
	        $json = json_decode($body);
	        return $json;
	}

	function check_version($version){
		$rc = -1;
	
	        if($version == Protocol::get_version()){
			$rc = 0;
	        }
	
		return $rc;
	}

	function respond_message(&$array_data, $mode){
		$data = null;
		$code = $array_data['code'];

		$exist = array_search($code, $array_data);
		if($exist !== false){
			unset($array_data['code']);
		}else{
			$code = 0;
		}

		if(!empty($array_data)){
	        	$data_json = json_encode($array_data);
	        	$data = json_decode($data_json);
		}

	        $respond_json = new Protocol($data, $code, $mode);
	
	        print_r($respond_json->to_string());
		RayvanDebug::rayvan_web_log('LOG_LEVEL_INFO', 'account', '--respond-json-string:'.$respond_json->to_string());
	}
	
	function build_request_json($array_data){
	        $data_json = json_encode($array_data);
	        $data = json_decode($data_json);
	        $request = new Protocol($data, 0);

	        $request_json = json_decode($request->data);
	        return $request_json;
	}

        public static function is_email($email)
        {
                $RegExp='/^[a-z0-9][a-z\.0-9-_]+@[a-z0-9_-]+(?:\.[a-z]{0,3}\.[a-z]{0,2}|\.[a-z]{0,3}|\.[a-z]{0,2})$/i';
                return preg_match($RegExp, $email)?0:-1;
        }

        public static function is_mobile($phone)
        {
		/* 全球手机号的规则不同，只需匹配到全是数字就行 */
                /* 
		//中国区域的手机号规则
		$RegExp='/^(?:13|15|18)[0-9]{9}$/';
		*/
		$RegExp='/^\d+$/';
                return preg_match($RegExp, $phone)?0:-1;
        }

        public static function account_set_str_get(&$array){
                $sql_set = '';
                foreach($array as $key => $val){
                        if(is_int($val)){
                                $tmp = sprintf("%s=%d", $key, $val);
                                $sql_set .= $tmp.',';
                        }else{
                                $tmp = sprintf("%s='%s'", $key, $val);
                                $sql_set .= $tmp.',';
                        }
                }
                $sql_set = substr($sql_set, 0, -1);

                return $sql_set;
        }

        public static function createAuthCode($length){
		$authCode = '';

                for($i = 0; $i < $length; $i++){
                        $authCode .= chr(mt_rand(48, 57));
                }

		return $authCode;
	}
}
?>
