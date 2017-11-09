<?php
require_once('inc/config.inc.php');
require_once(RAYVAN_LIBS.'/classes/rayvan/RayvanFunctions.class.php');
require_once(RAYVAN_LIBS.'/classes/rayvan/server/RosaVpn/ClientIP.class.php');

class RosaVpnController
{
	private $clientAction;
	private $rayvan_web_conf;
        private $clientType;
        private $clientID;
        private $clientIP;
	function __construct($action, $rayvan_conf, $json){
		$this->clientAction = $action;
		$this->rayvan_web_conf = $rayvan_conf;
		if($json === null){
			if(isset($_REQUEST['clientType'])){
				$this->clientType = $_REQUEST['clientType'];
			}
			if(isset($_REQUEST['clientID'])){
				$this->clientID = $_REQUEST['clientID'];
			}
			if(isset($_REQUEST['clientIP'])){
				$this->clientIP = $_REQUEST['clientIP'];
			}
		}else{
			if(array_key_exists('clientType', $json)){
				$this->clientType = $json->clientType;
			}
			if(array_key_exists('clientID', $json)){
				$this->clientID = $json->clientID;
                        }
			if(array_key_exists('clientIP', $json)){
				$this->clientIP = $json->clientIP;
			}
		}
	}

	function __get($proName){
                return $this->$proName;
        }

	function client_controller(&$array_data, $contro){
		switch($this->clientAction){
                case "clientIPGet":
			$gClient = new ClientIP($contro->clientType, $contro->clientID, $contro->rayvan_web_conf);
        		$gClient->getClientIP($array_data);
                        break;
		case "clientIPDelete":
			$dClient = new ClientIP($contro->clientType, $contro->clientID, $contro->rayvan_web_conf);
        		$dClient->deleteClientIP($contro->clientIP, $array_data);
			break;
		case "findRosaClientIP":
			ClientIP::findRosaClientIP($contro->clientID, $array_data);
			break;
		default:
			/* action错误，未知的action */
			$array_data = Array("code"=>RayvanError::RAYVAN_WEB_VERSION_REQUEST_ERROR); 
			break;
		}
	}
};
?>
