#!/bin/bash
INSTALL_HOME=$(dirname ${PWD})
HOME_PATH=$(dirname ${INSTALL_HOME})
[ $# -gt 0 -a -d "$1" ] && HOME_PATH=$1

setenforce 0

PROJECT_HOME=${HOME_PATH}/rayvan
if [ ! -d "${PROJECT_HOME}" ] ; then
	mkdir -p ${PROJECT_HOME}
	if [ $? -ne 0 ] ; then
		echo "错误: 创建${PROJECT_HOME}失败"
		exit 1
	fi
fi

# nginx
rpm -q nginx
if [ $? -ne 0 ]; then
	echo "错误: nginx未安装"
	exit 1
fi

# web-project
WEB_HOME=${PROJECT_HOME}/web
if [ ! -d "${WEB_HOME}" ] ; then
	cp -r ${INSTALL_HOME}/web ${PROJECT_HOME}
	if [ $? -ne 0 ] ; then
		echo "错误: 拷贝web目录失败, 请手工执行该脚本."
		exit 1
	fi
	chown -R nginx:nginx ${WEB_HOME}
else
	echo "警告:已经存在${WEB_HOME}, 未安装此部分."
	echo "     如果其内容与本系统不符，删除此目录再次执行该程序"
        echo "     或者手工安装web部分，甚至修改相关的配置"
fi
WEB_LOG_PATH=/var/log/rayvan
mkdir -p ${WEB_LOG_PATH} && chown -R nginx:nginx ${WEB_LOG_PATH}
if [ $? -ne 0 ] ; then
	echo "警告:设置web日志路径失败, 请手工设置"
fi

if [ -f "/etc/nginx/conf.d/default.conf" ]; then
	rm -f /etc/nginx/conf.d/default.conf
	if [ $? -ne 0 ]; then
		echo "删除nginx默认配置失败，请重新安装."
		exit 1	
	fi
fi

if [ ! -f "/etc/nginx/conf.d/rayvan-nginx.conf" ]; then
	cp ${WEB_HOME}/configs/rayvan.conf /etc/nginx/conf.d/rayvan-nginx.conf
	if [ $? -ne 0 ] ; then
		echo "错误:设置rayvan-nginx.conf出错."
		exit 1
	fi
else
	echo -e "\t/etc/nginx/conf.d/rayvan-nginx.conf文件已经存在"
fi

echo "-----------------------------------------------------------------"
echo "请根据提示修改配置"

echo -e "\t编辑/etc/php.ini"
echo -e "\t\tinclude_path = \".:${WEB_HOME}/libs\""

echo "web服务默认根目录: /opt/rayvan/web"
echo "如果安装与默认路径不同,请修改如下内容:"

# nginx
echo -e "\t编辑/etc/nginx/conf.d/rayvan-nginx.conf"
echo -e "\t\tssl_certificate ${WEB_HOME}/cert/app_server.pem"
echo -e "\t\tssl_certificate_key ${WEB_HOME}/cert/app_server.key"
echo -e "\t\troot ${WEB_HOME}/rayvan/www"

# web
echo -e "\t编辑${WEB_HOME}/libs/inc/config.inc.php"
echo -e "\t\tGLOBAL_WEB_ROOT=${WEB_HOME}"
echo -e "\t编辑${WEB_HOME}/rayvan/libs/inc/config.inc.php"
echo -e	"\t\tWEB_ROOT=${WEB_HOME}/rayvan"
echo -e	"\t编辑${WEB_HOME}/rayvan/libs/classes/rayvan/config/app.conf"
echo -e	"\t\t[redis]"
echo -e "\t\thost=redis服务器IP地址"
echo -e	"\t\t[vpnServerIp]"
echo -e "\t\thost=rosaVpn服务器IP地址"
echo -e "\t\tgateway=rosaVpn服务器的网关地址（即表示该服务器所以支持的A类地址的网段）"
echo -e "\t\taddressFamily=rosaVpn服务器IP协议(ipv4或ipv6)"

systemctl enable nginx.service && systemctl restart nginx.service
if [ $? -ne 0 ]; then
	echo "警告: 服务启动失败，请手工执行下面命令："
	echo -e "\tsystemctl enable nginx.service"
	echo -e	"\tsystemctl restart nginx.service"
fi

echo -e "web项目已经安装成功, 稍后请重启计算机"
exit 0
