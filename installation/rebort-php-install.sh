#!/bin/bash
INSTALL_HOME=$(dirname ${PWD})
HOME_PATH=$(dirname ${INSTALL_HOME})
[ $# -gt 0 -a -d "$1" ] && HOME_PATH=$1

setenforce 0

RPMS_PATH=${INSTALL_HOME}/dependence/rpm
echo "-----------------------------------------------------------------"
echo "检测php环境..."
rpm -qa | grep php
if [ $? -ne 0 ]; then
	echo "警告:没有安装php，直接执行yum install php-5.4.16-42.el7.x86_64"
	exit 1
else
        rpm -q php-5.4.*
        if [ $? -ne 0 ]; then
                echo "错误:安装的php不是php-5.4.x版本，建议卸载后，再重新执行该脚本。"
                exit 1
        fi
fi

echo "-----------------------------------------------------------------"
echo "检查php扩展..."
rpm_list="php-fpm-5.4.16-42.el7.x86_64 
	php-mysql-5.4.16-42.el7.x86_64 
	php-pecl-igbinary-1.2.1-1.el7.x86_64 
	php-pecl-redis-2.2.8-1.el7.x86_64"
for i in ${rpm_list} ; do
	rpm -q ${i}
	if [ $? -ne 0 ]; then
		rpm -ivh ${RPMS_PATH}/${i}.rpm
		if [ $? -ne 0 ]; then
			echo "警告:安装${i}模块失败"
			if [ "${i}" == "php-pecl-igbinary-1.2.1-1.el7.x86_64" ]; then
				echo "错误：你必须先执行 yum install php-pear"
			fi
		fi
	else
		echo "信息:已经安装了${i}模块"
	fi
done

echo "-----------------------------------------------------------------"
echo "请手动配置:"

# php
echo -e "\t编辑/etc/php.ini:"
echo -e	 "\t\tdate.timezone = PRC"

# php-fpm
echo -e "\t编辑/etc/php-fpm.d/www.conf:"
echo -e	"\t\tlisten = /var/run/php-fpm/php-fpm.sock"
echo -e "\t\tuser = nginx"
echo -e "\t\tgroup = nginx"
echo -e	"\t\tcatch_workers_output = yes"
echo -e	"\t\tphp_value[session.save_handler] = redis"
echo -e "\t\tphp_value[session.save_path] = \"tcp://127.0.0.1:6379?auth=123456\""

echo -e	"\t编辑/etc/php-fpm.conf"
echo -e	"\t\tdaemonize = yes"

echo "--------------------------------------------------------------------------------"

systemctl enable php-fpm && systemctl restart php-fpm
if [ $? -ne 0 ]; then 
	echo "警告: php-fpm服务启动失败, 请执行下面命令:"
	echo -e "\tsystemctl enable php-fpm"
	echo -e	"\tsystemctl restart php-fpm"
fi

echo "php安装成功,请执行rebort-web-install.sh"
exit 0
