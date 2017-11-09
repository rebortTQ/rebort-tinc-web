#!/bin/bash
INSTALL_HOME=$(dirname ${PWD})
HOME_PATH=$(dirname ${INSTALL_HOME})

# tinc install help
show_usage(){
        echo -e "Usage:`basename $0` -s [ server_addr ] -g [ server_gateway ] [ -d install_home ]"
        echo -e "   ./`basename $0` -s 192.168.1.187 -g 10.0.0.1"
}

while getopts "hd:s:g:" arg
do
        case "$arg" in
                h)
                        show_usage && exit 0
                        ;;
                d)
			if [ -d "$OPTARG" ]
			then 
                        	HOME_PATH=$OPTARG
			fi
                        ;;
                s)
                        SERVER_ADDR=$OPTARG
                        ;;
                g)
                        SERVER_GATEWAY=$OPTARG
                        NETWORK_TMP=$(echo $OPTARG | cut -d \. -f 1-3)
                        SERVER_SUBNET=${NETWORK_TMP}".0/8"
                        ;;
                *)
                        echo -e "unknown argument" && show_usage && exit 1
                        ;;
        esac
done

if [ ! -n "${SERVER_GATEWAY}" -o ! -n "${SERVER_ADDR}" ]
then
	echo "server_addr or server_gateway was null!"
	exit 1
fi

PROJECT_HOME=${HOME_PATH}/rayvan
WEB_HOME=${PROJECT_HOME}/web

# tinc_home path
TINC_HOME=/usr/local/rebort/tinc
if [ ! -d "${TINC_HOME}" ]; then
	mkdir -p ${TINC_HOME}/sbin
	if [ $? -ne 0 ] ; then
		echo "错误: 创建${TINC_HOME}目录失败"
		exit 1
	else
		cp ${INSTALL_HOME}/dependence/tinc/bin/tincd ${TINC_HOME}/sbin
	fi
fi

######### create tinc server
##### step1 create tinc-server dir
# check tinc config dir
TINC_CONFIG_HOME=/etc/tinc/RosaVpn
if [ ! -d "${TINC_CONFIG_HOME}" ]; then
        mkdir -p ${TINC_CONFIG_HOME}
        if [ $? -ne 0 ] ; then
                echo "错误: 创建${TINC_CONFIG_HOME}目录失败"
		exit 1
        fi
fi
# create tinc files
touch ${TINC_CONFIG_HOME}/tinc.conf
touch ${TINC_CONFIG_HOME}/tinc-up
touch ${TINC_CONFIG_HOME}/tinc-down
chmod +x ${TINC_CONFIG_HOME}/tinc-up
chmod +x ${TINC_CONFIG_HOME}/tinc-down
# create hosts dir
mkdir -p ${TINC_CONFIG_HOME}/hosts
if [ $? -ne 0 ] ; then
	echo "错误: 创建${TINC_CONFIG_HOME}/hosts目录失败"
	exit 1
fi
chmod 777 ${TINC_CONFIG_HOME}/hosts
touch ${TINC_CONFIG_HOME}/hosts/VpnServer

##### step2 config tinc-server files
# config tinc.conf
cat << EOF > ${TINC_CONFIG_HOME}/tinc.conf
Name = VpnServer
Interface = RosaVpn
EOF
# config tinc-up
cat << EOF > ${TINC_CONFIG_HOME}/tinc-up
#!/bin/sh
ifconfig \$INTERFACE ${SERVER_GATEWAY} netmask 255.0.0.0
EOF
# config tinc-down
cat << EOF > ${TINC_CONFIG_HOME}/tinc-down
#!/bin/sh
EOF
# config hosts/VpnServer
cat << EOF > ${TINC_CONFIG_HOME}/hosts/VpnServer
Subnet = ${SERVER_SUBNET}
Address = ${SERVER_ADDR}
EOF

##### step3 create tinc-server secret key
yes | ${TINC_HOME}/sbin/tincd -c /etc/tinc/RosaVpn/ -K

####### rebort_tinc_server
systemctl stop rebort_tinc.service 2>/dev/null && systemctl disable rebort_tinc.service 2>/dev/null
cat << EOF > /etc/systemd/system/rebort_tinc.service
[Unit]
Description=rebort tinc service
Requires=network.target
After=network.target

[Service]
Type=oneshot
ExecStart=${TINC_HOME}/sbin/tincd -c /etc/tinc/RosaVpn/ --pidfile=/var/run/tincd.pid
ExecStop=/bin/kill -9 \`cat /var/run/tincd.pid\`

RemainAfterExit=yes
PrivateTmp=true

[Install]
WantedBy=multi-user.target
EOF

systemctl enable rebort_tinc.service && systemctl start rebort_tinc.service
if [ $? -ne 0 ]; then
        echo "错误: 启动rebort_tinc失败，请重新执行该程序"
        exit 1
fi

echo "安装tinc服务成功,你需要修改以下内容:"
echo -e "\t${WEB_HOME}/rayvan/libs/classes/rayvan/config/app.conf"
echo -e "\t\t[vpnServerIp]"
echo -e "\t\thost=\"${SERVER_ADDR}\""
echo -e "\t\tgateway=\"${SERVER_GATEWAY}\""

exit 0
