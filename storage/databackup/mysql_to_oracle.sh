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
#把mysql中的数据，复制到oracle里去，字符集utf-8 --> ZHS16GBK

###################################
#####本shell执行说明###############
###################################
#不能用windows编辑后直接上传到服务器执行，特别注意

#mysqldump
sshpass -p root mysqldump -uroot -p shengwen > wanghan.sql

#过滤出insert语句
egrep -i 'INSERT INTO' wanghan.sql > wanghan1.sql

#清空oracle表
php truncateTable.php

#



exit 0