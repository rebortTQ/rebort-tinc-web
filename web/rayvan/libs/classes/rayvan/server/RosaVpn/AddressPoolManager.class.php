<?php
require_once('inc/config.inc.php');
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanError.class.php");
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanFunctions.class.php");
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanDebug.class.php");
require_once(RAYVAN_LIBS."/classes/rayvan/provider/RedisConnect.class.php");
Class AddressPoolManager
{
	/* client type */
	const CLIENT_TYPE_APP = 1;
	const CLIENT_TYPE_ROSA = 2;
	/* ip protocol */
	const IP_PROTOCOL_IPV4 = 1;
	const IP_PROTOCOL_IPV6 = 2;
	private $IpProtocol;
	private $gateway;
	function __construct($protocol, $gw){
		$this->gateway = $gw;
		switch($protocol){
		case "ipv4":
			$this->IpProtocol = AddressPoolManager::IP_PROTOCOL_IPV4;
			break;
		case "ipv6":
			$this->IpProtocol = AddressPoolManager::IP_PROTOCOL_IPV6;
			break;
		default:
			break;
		}	
	}

        function __destruct(){
        }

	private function getIPFromDeleteList(){
		$ip = "";
		$redis_vpnip_delete_list_key = "";
		global $redis;
		if($this->IpProtocol == AddressPoolManager::IP_PROTOCOL_IPV4){
			$redis_vpnip_delete_list_key = "rosa_vpn_delete_list_address_ipv4";
		}else if($this->IpProtocol == AddressPoolManager::IP_PROTOCOL_IPV6){
			$redis_vpnip_delete_list_key = "rosa_vpn_delete_list_address_ipv6";
		}else{
		}

		if($redis->__exists($redis_vpnip_delete_list_key) == 1){
			$ip = $redis->__lPop($redis_vpnip_delete_list_key);
		}

		return $ip;
	}
	private function addIPToDeleteList($ip){
		$rc = -1;
		$redis_vpnip_delete_list_key = "";
		global $redis;
		if($this->IpProtocol == AddressPoolManager::IP_PROTOCOL_IPV4){
			$redis_vpnip_delete_list_key = "rosa_vpn_delete_list_address_ipv4";
		}else if($this->IpProtocol == AddressPoolManager::IP_PROTOCOL_IPV6){
			$redis_vpnip_delete_list_key = "rosa_vpn_delete_list_address_ipv6";
		}else{
		}

		if($redis->__exists($redis_vpnip_delete_list_key) == 1){
			$redis->__lPush($redis_vpnip_delete_list_key, $ip);
		}
		return $rc;
	}
	private function addressAutoAddOne_ipv6($ip){
		$nip = "";
		
		return $nip;
	}
	private function addressAutoAddOne_ipv4($ip){
		$nip = "";
		$ipArray = explode('.', $ip, 4);
		$num = count($ipArray);
		if($num == 4){
			if(($ipArray[1] > 254) || ($ipArray[2] > 254) || ($ipArray[3] > 254)){
				/* IP地址错误 */
			}else{
				if(($ipArray[1] == 254) && ($ipArray[2] == 254) && ($ipArray[3] == 254)){
					/* 地址池满了 */
				}else{
					if($ipArray[3] == 254){
						if($ipArray[2] < 254){
							$ipArray[3] = 1;
							$ipArray[2] += 1;
						}
						if($ipArray[2] == 254){
							$ipArray[3] = 1;
							$ipArray[2] = 0;
							$ipArray[1] += 1;
						}
					}else{
						$ipArray[3] += 1;
					}
					$nip = $ipArray[0].'.'.$ipArray[1].'.'.$ipArray[2].'.'.$ipArray[3];	
				}
			}
		}

		return $nip;
	}
	private function addressAutoAddOne($ip){
		$nip = "";
		if($this->IpProtocol == AddressPoolManager::IP_PROTOCOL_IPV4){
			$nip = $this->addressAutoAddOne_ipv4($ip);
		}else if($this->IpProtocol == AddressPoolManager::IP_PROTOCOL_IPV6){
			$nip = $this->addressAutoAddOne_ipv6($ip);
		}else{
		}
		
		return $nip;	
	}
	/*
		$ip                     --->  是从地址池中取出的当前可用IP
		$redis_address_pool_key --->  是redis中当前可用IP的key值
	*/
	private function getIPFromAddressPool_i($redis_address_pool_key){
		$ip = "";
		global $redis;
		
		if($redis->__exists($redis_address_pool_key) == 1){
			$ip = $redis->string_get($redis_address_pool_key);
			if(!empty($ip)){
				/*
				如果取出的当前ip不为空，则需要将ip+1写入redis
				*/
				$nextIP = $this->addressAutoAddOne($ip);
				if(!empty($nextIP)){
					$redis->string_set($redis_address_pool_key, $nextIP);
				}
			}else{
				/* redis中该字段没有值 */
				$ip = $this->addressAutoAddOne($this->gateway);
				$redis->string_set($redis_address_pool_key, $ip);
			}
		}else{
			/* redis中没有当前可用地址的字段 
			如果redis中没有当前可用IP的字段，就创建这个字段其值
			为10.0.0.2。
			*/
			$ip = $this->addressAutoAddOne($this->gateway);
			$redis->string_set($redis_address_pool_key, $ip);
		}

		RayvanDebug::rayvan_web_log('LOG_LEVEL_INFO', 'rosavpn', 
			'func=getIPFromAddressPool_i---end-ip='.$ip);
		return $ip;
	}
	/*  对外的接口  */
	function getIpFromAddressPool(){
		$ip = "";
	
		$redis_address_pool_key = "";
		if($this->IpProtocol == AddressPoolManager::IP_PROTOCOL_IPV4){
			$redis_address_pool_key = "rosa_vpn_current_available_address_ipv4";
		}else if($this->IpProtocol == AddressPoolManager::IP_PROTOCOL_IPV6){
			$redis_address_pool_key = "rosa_vpn_current_available_address_ipv6";
		}else{
		}

		$ip = $this->getIPFromDeleteList();
		if(empty($ip)){
			$ip = $this->getIPFromAddressPool_i($redis_address_pool_key);
		}

		return $ip;
	}
	function addIpToAddressPool($ip){
		$rc = -1;
		$rc = $this->addIPToDeleteList($ip);

		return $rc;
	}
}
?>
