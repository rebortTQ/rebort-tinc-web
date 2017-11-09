#!/bin/bash
INSTALL_HOME=$(dirname ${PWD})
HOME_PATH=$(dirname ${INSTALL_HOME})
[ $# -gt 0 -a -d "$1" ] && HOME_PATH=$1

PROJECT_HOME=${HOME_PATH}/rayvan

# stop all service
setenforce 0
systemctl stop firewalld.service && systemctl disable firewalld.service
if [ $? -ne 0 ]; then
	echo "警告: 防火墙关闭失败，请手工执行下面命令："
        echo -e "\tsystemctl stop firewalld.service" 
	echo -e "\tsystemctl disable firewalld.service"
fi
# selinux
echo -e "\t编辑/etc/selinux/config:"
echo -e "\t\tSELINUX=enforcing 修改为 SELINUX=disabled"

# home path
REBORT_HOME=/usr/local/rebort
if [ ! -d "${REBORT_HOME}" ]; then
	mkdir -p ${REBORT_HOME}
	if [ $? -ne 0 ] ; then
		echo "警告: 创建${REBORT_HOME}目录失败"
	fi
fi

# tinc config path
TINC_CONFIG_HOME=/etc/tinc/RosaVpn
if [ ! -d "${TINC_CONFIG_HOME}" ]; then
	mkdir -p ${TINC_CONFIG_HOME}
	if [ $? -ne 0 ] ; then
		echo "警告: 创建${TINC_CONFIG_HOME}目录失败"
	fi
fi

echo "系统检测完成，请根据上面的提示修改相应的配置文件"
exit 0
