#!/bin/bash

INSTALL_HOME=$(dirname ${PWD})
HOME_PATH=$(dirname ${INSTALL_HOME})
[ $# -gt 0 -a -d "$1" ] && HOME_PATH=$1

setenforce 0

rpm -q redis
if [ $? != 0 ]; then
	rpm -ivh ${INSTALL_HOME}/dependence/rpm/jemalloc-3.6.0-1.el7.x86_64.rpm
	rpm -ivh ${INSTALL_HOME}/dependence/rpm/redis-2.8.19-2.el7.x86_64.rpm
	if [ $? -ne 0 ]; then
		echo "错误: redis安装失败."
		exit 1
	fi
else
	echo "信息: 已经安装了redis"
fi

# redis
echo "请根据提示修改配置:"
echo -e	 "\t编辑/etc/redis.conf"
echo -e	 "\t\trequirepass 密码 [默认密码:123456]"
echo -e  "\t\t如果密码非默认密码，则需修改${HOME_PATH}/rayvan/web/rayvan/libs/classes/rayvan/config/app.conf的redis字段 和 /etc/php-fpm.d/www.conf的save_path字段相关密码内容"

systemctl enable redis && systemctl restart redis
if [ $? -ne 0 ]; then
        echo "警告:服务启动失败，请手工执行下面命令："
        echo -e "\t systemctl enable redis"
	echo -e "\t systemctl restart redis"
fi

echo "redis安装成功，稍后请重启机器."
exit 0
