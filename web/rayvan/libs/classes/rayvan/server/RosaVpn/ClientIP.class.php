<?php
require_once('inc/config.inc.php');
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanError.class.php");
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanFunctions.class.php");
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanDebug.class.php");
require_once(RAYVAN_LIBS."/classes/rayvan/provider/RedisConnect.class.php");
require_once(RAYVAN_LIBS.'/classes/rayvan/server/RosaVpn/AddressPoolManager.class.php');
Class ClientIP
{
	const CLIENT_TYPE_APP = 1;
	const CLIENT_TYPE_ROSA = 2;
	private $clientType;
	private $clientID;
	private $rayvan_web_conf;
	function __construct($cType, $cId, $rayvan_conf){
		$this->clientType = $cType;
		$this->clientID = $cId;
		$this->rayvan_web_conf = $rayvan_conf;
	}

        function __destruct(){
        }

	private function getServerIP(){
		$sip = $this->rayvan_web_conf['vpnServer']['host'];

		return $sip;
	}
	private function createClientFile($fileName, $clientIp){
		$content = "Address =".$clientIp.'\n'."Subnet = 10.0.0.0/8".'\n';
		$client_public="-----BEGIN RSA PUBLIC KEY-----
MIIBCgKCAQEAxyvKAoNJHXcWZyk9q1P2s0zzeZTLaMDbKyjp0tOEJlt6+2TP1Hj5
Y9+f83qnTRWz1dydVT71R8VXXCJ8AacX1MNmy6ODU/89gjveFVSl03w7yzPsysh4
3NeMlYv5U0Cw+qkTFpRH9/wYJSiAhpBfjaJdnPFwxpl9XhWOwNdXAwwBSaTpLBeL
1WA01DPaj+vxsyTTIiLVDmo1Wgk0y6AJk3NGVdxsKaBrwU/T5HDEPwUheCmVmzf2
ewVioLUJY7VAODzmKkikBpMBGWXkU+UMOExA3DnQcm0Bf/39y+hL8NVPc1TWgew1
K86+YyiliR1U50mvN0H/O3VO4fqbAh0/TwIDAQAB
-----END RSA PUBLIC KEY-----";
		file_put_contents($fileName, $content.$client_public);
	}
	private function addClientFileToHosts($clientName, $clientIp){
		$rc = -1;
		$this->createClientFile($clientName, $clientIp);
		if(file_exists($clientName)){
			$rc = 0;
		}

		return $rc;
	}
	private function deleteClientFileFromHosts($clientName){
		$retval = -1;
		$cmd = sprintf("rm -f %s", $clientName);
		$msg = exec($cmd, $array_noused, $retval);

		return $retval;
	}

	private function setClientIdData($oldValue, $clientId, $ip){
		$newValue = "";
		$redis_vpn_client_id_list_key = "rosa_vpn_client_id_list";
		global $redis;

		/* value格式-----> appIP=10.0.0.1,rosaIP=10.0.0.2 */
		$array = explode(',', $oldValue, 2);
		$num = count($array);
		if($num == 2){
			if($this->clientType == ClientIP::CLIENT_TYPE_APP){
				$appArray = explode('=', $array[0], 2);
				$newValue = $appArray[0].$ip.','.$array[1];
			}else if($this->clientType == ClientIP::CLIENT_TYPE_ROSA){
				$rosaArray = explode('=', $array[1], 2);
				$newValue = $array[0].','.$rosaArray[0].$ip;
			}else{
			}
			if(!empty($newValue)){
				$redis->__hSet($redis_vpn_client_id_list_key, $clientId, $newValue);
			}
		}else{
		}
	}
	public static function getIpFormClientIdData($value, $type){
		$ip = "";
		/* value格式-----> appIP=10.0.0.1,rosaIP=10.0.0.2 */
		$array = explode(',', $value, 2);
		$num = count($array);
		if($num == 2){
			if($type == ClientIP::CLIENT_TYPE_APP){
				$appArray = explode('=', $array[0], 2);
				$ip = $appArray[1];
			}else if($type == ClientIP::CLIENT_TYPE_ROSA){
				$rosaArray = explode('=', $array[1], 2);
				$ip = $rosaArray[1];
			}else{
			}
		}else{
		}

		return $ip;
	}
	public static function getClientIdData($clientId){
		$value = "";
		$redis_vpn_client_id_list_key = "rosa_vpn_client_id_list";
		global $redis;
		$value = $redis->__hGet($redis_vpn_client_id_list_key, $clientId);

		return $value;
	}
	/* 对外的 */
	public static function findRosaClientIP($rosaID, &$array_data){
	/*
		// 以下的代码只能处理服务器是一台机器 
		$cmd = sprintf("cat /etc/tinc/company/hosts/RosaClient%s | grep Address 
			| sed s/[[:space:]]//g | awk -F '=' '{printf $2}'", $rosaID);
		$ip = exec($cmd, $array_noused, $retval);
		if($retval == 0){
			$array_data = Array("code"=>RayvanError::SUCCESS, "clientIP"=>$ip);	
		}else{
			$array_data = Array("code"=>RayvanError::FIND_ROSAID_COMMAND_EXEC_ERROR, 
			"errorMessage"=>$ip);	
		}
	*/
		$clientIdData = ClientIP::getClientIdData($rosaID);
		if(!empty($clientIdData)){
			$ip = ClientIP::getIpFormClientIdData($clientIdData, ClientIP::CLIENT_TYPE_ROSA);	
			if(!empty($ip)){
				$array_data = Array("code"=>RayvanError::SUCCESS, "clientIP"=>$ip);	
			}else{
				$array_data = Array("code"=>RayvanError::GET_CLIENTIP_ERROR);	
			}
		}else{
			$array_data = Array("code"=>RayvanError::GET_CLIENTIP_ERROR);	
		}
	}
	public function getClientIP(&$array_data){
		$ip = "";
		$clientName = "";
		
		$sip = $this->getServerIP();
		$clientIdData = ClientIP::getClientIdData($this->clientID);
		if(!empty($clientIdData)){
			$ip = ClientIP::getIpFormClientIdData($clientIdData, $this->clientType);	
		}

		if(!empty($ip)){
			$array_data = Array("code"=>RayvanError::SUCCESS, "clientIP"=>$ip, "serverIP"=>$sip);	
		}else{
			$addrPoll = new AddressPoolManager($this->rayvan_web_conf['vpnServer']['addressFamily'], 
				$this->rayvan_web_conf['vpnServer']['gateway']);
			$ip = $addrPoll->getIpFromAddressPool();
			if(!empty($ip)){
				$rc = -1;
				if($this->clientType == ClientIP::CLIENT_TYPE_APP){
					$clientName = "/etc/tinc/RosaVpn/hosts/AppClient".$this->clientID;
				}else if($this->clientType == ClientIP::CLIENT_TYPE_ROSA){
					$clientName = "/etc/tinc/RosaVpn/hosts/RosaClient".$this->clientID;
				}else{
					$array_data = Array("code"=>RayvanError::CLIENT_TYPE_UNKNOWN);
					return;
				}
				$rc = $this->addClientFileToHosts($clientName, $ip);
				if($rc == 0){
					$this->setClientIdData($clientIdData, $this->clientID, $ip);
					$array_data = Array("code"=>RayvanError::SUCCESS, "clientIP"=>$ip, "serverIP"=>$sip);	
				}else{
					$array_data = Array("code"=>RayvanError::CREATE_CLIENT_FILE_ERROR);	
				}
			}else{
				$array_data = Array("code"=>RayvanError::GET_CLIENTIP_ERROR);	
			}
		}
	}
	public function deleteClientIP($ip, &$array_data){
		$clientName = "";
		$addrPoll = new AddressPoolManager($this->rayvan_web_conf['vpnServer']['addressFamily'], 
			$this->rayvan_web_conf['vpnServer']['gateway']);
		$rc = $addrPoll->addIpToAddressPool($ip);

		if($rc == 0){
			if($this->clientType == ClientIP::CLIENT_TYPE_APP){
				$clientName = "/etc/tinc/company/hosts/AppClient".$this->clientID;
			}else if($this->clientType == ClientIP::CLIENT_TYPE_ROSA){
				$clientName = "/etc/tinc/company/hosts/RosaClient".$this->clientID;
			}else{
				$array_data = Array("code"=>RayvanError::CLIENT_TYPE_UNKNOWN);	
			}
		
			$this->deleteClientFileFromHosts($clientName);
			$array_data = Array("code"=>RayvanError::SUCCESS);	
		}else{
			$array_data = Array("code"=>RayvanError::ADD_IP_TO_DELETE_LIST_ERROR);	
		}
	}
}
?>
