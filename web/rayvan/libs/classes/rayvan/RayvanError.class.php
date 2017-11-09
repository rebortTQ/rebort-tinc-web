<?php
Class RayvanError
{
	/* 2xx 表示服务端接受了请求 */
	const SUCCESS = 200; /* 成功 */
	const ACCOUNT_REQUEST_SUCCESS = 20300; /* 账号请求成功 */
	const ACCOUNT_IS_LOGGED_IN = 20301; /* 账号已经登录 */
	const ACCOUNT_CANCELED = 20302; /* 账号已注销 */
	
	/* 4xx 表示客户端请求错误 */
	const UNKNOWN_ERROR = 400; /* 失败，原因未知 */
	const RAYVAN_VERSION_UNKNOWN_ERROR = 401; /* 协议未知错误 */
	const RAYVAN_VERSION_ERROR = 40101; /* 协议版本不正确 */
	const RAYVAN_WEB_MODE_WAS_NULL = 40102; /* 协议模块不存在 */
	const RAYVAN_WEB_VERSION_REQUEST_ERROR = 40103; /* 协议请求错误 */

	/* rosaVpn error */
	const ROSA_VPN_ERROR = 406; /* ROSAVPN错误 */
	const GET_CLIENTIP_ERROR = 40601; /* 获取clientIP失败 */
	const ADD_IP_TO_DELETE_LIST_ERROR = 40602; /* 添加IP到deleteList失败 */
	const CLIENT_TYPE_UNKNOWN = 40603; /* 未知的cleint_type */
	const FIND_ROSAID_COMMAND_EXEC_ERROR = 40604; /* 命令执行失败 */
	const CREATE_CLIENT_FILE_ERROR = 40605; /* 创建hosts下的client文件失败 */
	const GET_SUBNET_ERROR = 40606; /* 通过服务器网关地址获取网段失败 */

	/* 5xx 表示服务端处理错误 */
	const SERVICE_UNKNOWN_ERROR = 500; /* 服务器未知错误 */
}
?>
