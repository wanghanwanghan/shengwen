/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50632
Source Host           : localhost:3306
Source Database       : shengwen

Target Server Type    : MYSQL
Target Server Version : 50632
File Encoding         : 65001

Date: 2017-06-19 16:02:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zbxl_confirm_type`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_confirm_type`;
CREATE TABLE `zbxl_confirm_type` (
  `confirm_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '认证类型编号',
  `confirm_name` varchar(60) NOT NULL COMMENT '认证类型名称',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`confirm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_confirm_type
-- ----------------------------
INSERT INTO `zbxl_confirm_type` VALUES ('1', '文本无关', null, '2017-01-20 06:17:51', '2017-01-20 06:17:51');
INSERT INTO `zbxl_confirm_type` VALUES ('2', '文本相关', null, '2017-01-20 06:17:56', '2017-01-20 06:17:56');
INSERT INTO `zbxl_confirm_type` VALUES ('3', '动态口令', null, '2017-01-20 06:18:02', '2017-01-20 06:18:02');

-- ----------------------------
-- Table structure for `zbxl_customer_confirm`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_customer_confirm`;
CREATE TABLE `zbxl_customer_confirm` (
  `confirm_num` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '认证编号',
  `confirm_pid` int(10) unsigned NOT NULL COMMENT '客户主键',
  `confirm_res` char(1) NOT NULL COMMENT '客户验证结果',
  `confirm_btw` varchar(255) DEFAULT NULL COMMENT '客服修改的原因',
  `belong_to` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '这条数据分配给某个员工',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`confirm_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_customer_confirm
-- ----------------------------

-- ----------------------------
-- Table structure for `zbxl_customer_info`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_customer_info`;
CREATE TABLE `zbxl_customer_info` (
  `cust_num` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '客户编号',
  `cust_name` varchar(60) NOT NULL COMMENT '客户姓名',
  `cust_id` char(18) NOT NULL COMMENT '客户身份证号',
  `cust_si_id` varchar(60) DEFAULT NULL COMMENT '客户社保编号',
  `cust_phone_num` varchar(60) DEFAULT NULL COMMENT '客户备用手机号',
  `cust_review_num` varchar(60) NOT NULL COMMENT '客户认证手机号',
  `cust_address` varchar(200) DEFAULT NULL COMMENT '客户地址',
  `cust_project` int(10) unsigned NOT NULL COMMENT '客户所属项目',
  `cust_si_type` int(10) unsigned NOT NULL COMMENT '客户所属保险类型',
  `cust_confirm_type` int(10) unsigned NOT NULL COMMENT '客户认证类型',
  `cust_type` char(1) NOT NULL COMMENT 'A类用户还是B类用户',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  `cust_review_flag` char(1) NOT NULL COMMENT '区分第一and第二年审人',
  `cust_register_flag` char(1) NOT NULL COMMENT '0未注册,1已注册',
  `cust_relation_flag` int(10) unsigned NOT NULL COMMENT '没有第二年审人0,如果有就写第一年审人的cust_num',
  `cust_death_flag` char(1) NOT NULL COMMENT '客户去世值是1,未去世值是0',
  PRIMARY KEY (`cust_num`),
  UNIQUE KEY `id` (`cust_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_customer_info
-- ----------------------------

-- ----------------------------
-- Table structure for `zbxl_customer_info_delete_use`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_customer_info_delete_use`;
CREATE TABLE `zbxl_customer_info_delete_use` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cust_num` int(10) unsigned NOT NULL COMMENT '客户编号',
  `cust_name` varchar(60) NOT NULL COMMENT '客户姓名',
  `cust_id` char(18) NOT NULL COMMENT '客户身份证号',
  `cust_si_id` varchar(60) DEFAULT NULL COMMENT '客户社保编号',
  `cust_phone_num` varchar(60) DEFAULT NULL COMMENT '客户备用手机号',
  `cust_review_num` varchar(60) NOT NULL COMMENT '客户认证手机号',
  `cust_address` varchar(200) DEFAULT NULL COMMENT '客户地址',
  `cust_project` int(10) unsigned NOT NULL COMMENT '客户所属项目',
  `cust_si_type` int(10) unsigned NOT NULL COMMENT '客户所属保险类型',
  `cust_confirm_type` int(10) unsigned NOT NULL COMMENT '客户认证类型',
  `cust_type` char(1) NOT NULL COMMENT 'A类用户还是B类用户',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  `cust_review_flag` char(1) NOT NULL COMMENT '区分第一and第二年审人',
  `cust_register_flag` char(1) NOT NULL COMMENT '0未注册,1已注册',
  `cust_relation_flag` int(10) unsigned NOT NULL COMMENT '没有第二年审人0,如果有就写第一年审人的cust_num',
  `cust_death_flag` char(1) NOT NULL COMMENT '客户是否去世',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_customer_info_delete_use
-- ----------------------------

-- ----------------------------
-- Table structure for `zbxl_level`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_level`;
CREATE TABLE `zbxl_level` (
  `level_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限编号',
  `level_name` varchar(60) NOT NULL COMMENT '权限名称',
  `level_parent` int(10) unsigned NOT NULL COMMENT '父级编号',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_level
-- ----------------------------
INSERT INTO `zbxl_level` VALUES ('1', '用户登记', '0', null, '2017-04-20 14:53:48', '2017-04-20 14:53:48');
INSERT INTO `zbxl_level` VALUES ('2', '客服功能', '0', null, '2017-04-20 14:53:58', '2017-04-20 14:53:58');
INSERT INTO `zbxl_level` VALUES ('3', '客户管理', '0', null, '2017-04-21 17:19:35', '2017-04-21 17:19:35');
INSERT INTO `zbxl_level` VALUES ('4', '声纹管理', '0', null, '2017-04-20 14:54:07', '2017-04-20 14:54:07');
INSERT INTO `zbxl_level` VALUES ('5', '系统设置', '0', null, '2017-04-20 14:54:16', '2017-04-20 14:54:16');
INSERT INTO `zbxl_level` VALUES ('6', '超级管理员功能', '0', null, '2017-04-20 14:54:33', '2017-04-20 14:54:33');

-- ----------------------------
-- Table structure for `zbxl_log`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_log`;
CREATE TABLE `zbxl_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'log编号',
  `log_account` varchar(60) NOT NULL COMMENT '员工账号',
  `log_todo` varchar(60) NOT NULL COMMENT '做了什么',
  `log_detail` mediumtext NOT NULL COMMENT '详细日志',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_log
-- ----------------------------

-- ----------------------------
-- Table structure for `zbxl_project`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_project`;
CREATE TABLE `zbxl_project` (
  `project_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '项目编号',
  `project_name` varchar(60) NOT NULL COMMENT '项目名称',
  `project_parent` int(10) unsigned NOT NULL COMMENT '父级编号',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_project
-- ----------------------------
INSERT INTO `zbxl_project` VALUES ('1', '测试', '0', null, '2017-06-19 16:00:42', '2017-06-19 16:00:42');

-- ----------------------------
-- Table structure for `zbxl_si_type`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_si_type`;
CREATE TABLE `zbxl_si_type` (
  `si_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '退休类型编号',
  `si_name` varchar(60) NOT NULL COMMENT '退休类型名称',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`si_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_si_type
-- ----------------------------
INSERT INTO `zbxl_si_type` VALUES ('1', '测试', null, '2017-06-19 16:00:52', '2017-06-19 16:00:52');

-- ----------------------------
-- Table structure for `zbxl_staff_info`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_staff_info`;
CREATE TABLE `zbxl_staff_info` (
  `staff_num` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '员工编号',
  `staff_account` varchar(60) NOT NULL COMMENT '员工账号',
  `staff_password` char(24) NOT NULL COMMENT '员工密码',
  `staff_id` char(18) NOT NULL COMMENT '员工身份证号',
  `staff_name` varchar(60) NOT NULL COMMENT '员工姓名',
  `staff_project` varchar(200) NOT NULL COMMENT '员工所属项目',
  `staff_si_type` varchar(200) NOT NULL COMMENT '员工所属保险类型',
  `staff_level` varchar(200) NOT NULL COMMENT '员工权限',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`staff_num`),
  UNIQUE KEY `account` (`staff_account`) USING BTREE,
  UNIQUE KEY `id` (`staff_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_staff_info
-- ----------------------------
INSERT INTO `zbxl_staff_info` VALUES ('1', 'sw_admin', '21232f297a57a5a743894a0e', '999999999999999999', '超级管理员', '0,1', '0,1', '0,1,2,3,4,5,6', null, '2017-01-21 07:57:09', '2017-06-19 15:51:05');

-- ----------------------------
-- Table structure for `zbxl_staff_mail`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_staff_mail`;
CREATE TABLE `zbxl_staff_mail` (
  `mail_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'mail编号',
  `mail_type` char(1) NOT NULL COMMENT '邮件的类型',
  `mail_target` varchar(30) DEFAULT NULL COMMENT '邮件是给谁的',
  `mail_content` varchar(500) NOT NULL COMMENT 'mail内容',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`mail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_staff_mail
-- ----------------------------

-- ----------------------------
-- Table structure for `zbxl_vocalprint`
-- ----------------------------
DROP TABLE IF EXISTS `zbxl_vocalprint`;
CREATE TABLE `zbxl_vocalprint` (
  `vp_pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vp_id` int(10) unsigned NOT NULL COMMENT '对应的客户主键',
  `vp_action` varchar(30) DEFAULT NULL,
  `vp_ivr_url` varchar(300) DEFAULT NULL COMMENT '录音文件的url',
  `vp_model_url` varchar(300) DEFAULT NULL COMMENT '声纹模型的url',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`vp_pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_vocalprint
-- ----------------------------
