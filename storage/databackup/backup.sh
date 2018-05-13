#!/bin/bash

###################################
#####crontab设置说明###############
###################################
# .---------------- 分钟 (0 - 59)
# | .------------- 小时 (0 - 23)
# | | .---------- 日期 (1 - 31)
# | | | .------- 月 (1 - 12) 或者 jan,feb,mar,apr ...
# | | | | .---- 周 (0 - 6) (Sunday=0 or 7) 或者 sun,mon,tue,wed,thu,fri,sat
# | | | | |
# * * * * * username 要执行的命令或者shell

###################################
#####本shell功能说明###############
###################################
#把数据库，录音文件和模板进行备份
#备份文件进行30世代管理，备份到第31世代，删除最早的第1世代

###################################
#####本shell执行说明###############
###################################
#不能用windows编辑后直接上传到服务器执行，特别注意
#安装sshpass，scp，rsh和rsh-server，/etc/securetty里添加rexec，rsh，rlogin。手动scp一次建立服务器间关系后
#放到linux任意文件夹下，修改好参数和crontab，直接执行
#wget http://sourceforge.net/projects/sshpass/files/latest/download -O sshpass.tar.gz
#./configure
#make install

###################################
#####各个命令参数一览##############
###################################
#开始时间
start_time=`date +%Y-%m-%d_%H:%M:%S`

#备份文件夹，文件都放在这里，并且进行世代管理
basedir='/home/wwwroot/default/shengwen/storage/databackup/'

#日志文件
log=${basedir}'backup.log'

#mysql
user='root'
passwd='root'
database='shengwen'
basedir_for_mysql=${basedir}

#mongo
mongo_bin_dir='/usr/local/MongoDB/bin/'

#shengwen
basedir_for_app='/home/wwwroot/default/shengwen/VoiceAndModel/'

#laravel.log
#laravel_log_file=''
#gzip ${laravel_log_file}

#数据文件不但要在本地存储，还要放到另一个服务器上
need_send_data='1'
server_user=root
server_passwd='zbxlbj@2017&*('
server_ip='180.76.154.129'
server_post='22'
server_dir='/mnt/211_data/'

#套接字出错，只要211需要设置成1，其他服务器设置成0
mysql_sock='1'

###################################
#####mysql数据备份#################
###################################
if [ ${mysql_sock} -eq '0' ]; then
	
	mysqldump -u${user} -p${passwd} ${database} | gzip > ${basedir_for_mysql}`date +%Y%m%d`.sql.gz
	
else

	mysqldump --socket='/var/lib/mysql/mysql.sock' -u${user} -p${passwd} ${database} | gzip > ${basedir_for_mysql}`date +%Y%m%d`.sql.gz

fi

###################################
#####Mongo数据备份#################
###################################
cd ${mongo_bin_dir}

./mongodump > /dev/null 2>&1

tar zcvf ${basedir}`date +%Y%m%d`.mongo.tar.gz dump > /dev/null 2>&1

rm -rf dump > /dev/null 2>&1

###################################
#####录音文件和模板备份############
###################################
cd ${basedir_for_app}

tar zcvf ${basedir}`date +%Y%m%d`.voice.tar.gz * > /dev/null 2>&1

###################################
#####备份文件世代管理##############
###################################
cd ${basedir}

mkdir `date +%Y%m%d`

chmod 777 `date +%Y%m%d`

mv `date +%Y%m%d`* `date +%Y%m%d` > /dev/null 2>&1

if [ `ls -1t | egrep -v sh | wc -l` -gt 30 ]; then
	
	rm -rf `ls -1t | tail -1`
	
fi

###################################
#####备份文件发送##################
###################################
if [ ${need_send_data} -eq '1' ]; then
	
	cd ${basedir}`date +%Y%m%d`
	
	rsh -l root ${server_ip} "mkdir ${server_dir}`date +%Y%m%d`"
	
	/usr/local/bin/sshpass -p "${server_passwd}" scp -P ${server_post} *.sql.gz "${server_user}@${server_ip}:${server_dir}`date +%Y%m%d`"
	
	/usr/local/bin/sshpass -p "${server_passwd}" scp -P ${server_post} *.mongo.tar.gz "${server_user}@${server_ip}:${server_dir}`date +%Y%m%d`"
	
	/usr/local/bin/sshpass -p "${server_passwd}" scp -P ${server_post} *.voice.tar.gz "${server_user}@${server_ip}:${server_dir}`date +%Y%m%d`"
	
fi

###################################
#####执行结果写入log###############
###################################
echo '#####分割线#####分割线#####分割线#####' >> ${log}
echo '#######################' >> ${log}
echo '#####备份开始##########' >> ${log}
echo '#######################' >> ${log}
echo ${start_time}  >> ${log}

echo '#######################' >> ${log}
echo '#####备份确认##########' >> ${log}
echo '#######################' >> ${log}

cd ${basedir}`date +%Y%m%d`

if [ ! -f "`date +%Y%m%d`.sql.gz" ]; then
	
	echo 'mysql_database:failed' >> ${log}
	echo `ls -l *.sql.gz` >> ${log}
	
else
	
	echo 'mysql_database:success' >> ${log}
	echo `ls -l *.sql.gz` >> ${log}
	
fi

if [ ! -f "`date +%Y%m%d`.mongo.tar.gz" ]; then
	
	echo 'mongo_database:failed' >> ${log}
	echo `ls -l *.mongo.tar.gz` >> ${log}
	
else
	
	echo 'mongo_database:success' >> ${log}
	echo `ls -l *.mongo.tar.gz` >> ${log}
	
fi

if [ ! -f "`date +%Y%m%d`.voice.tar.gz" ]; then
	
	echo 'voice_and_model:failed' >> ${log}
	echo `ls -l *.voice.tar.gz` >> ${log}
	
else
	
	echo 'voice_and_model:success' >> ${log}
	echo `ls -l *.voice.tar.gz` >> ${log}
	
fi

echo '#######################' >> ${log}
echo '#####远程数据确认######' >> ${log}
echo '#######################' >> ${log}

rsh -l root ${server_ip} "ls -l ${server_dir}`date +%Y%m%d`" >> ${log}

echo '#######################' >> ${log}
echo '#####备份结束##########' >> ${log}
echo '#######################' >> ${log}
echo `date +%Y-%m-%d_%H:%M:%S`  >> ${log}

exit 0