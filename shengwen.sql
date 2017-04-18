/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50632
Source Host           : localhost:3306
Source Database       : shengwen

Target Server Type    : MYSQL
Target Server Version : 50632
File Encoding         : 65001

Date: 2017-04-18 10:42:48
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

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
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`confirm_num`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_customer_confirm
-- ----------------------------
INSERT INTO `zbxl_customer_confirm` VALUES ('1', '1', 'N', '', null, '2017-03-20 16:04:01', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('2', '2', 'N', '', null, '2017-03-21 16:04:02', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('3', '3', 'Y', '通过了', null, '2017-03-22 16:04:03', '2017-04-07 11:55:47');
INSERT INTO `zbxl_customer_confirm` VALUES ('4', '1', 'N', '', null, '2017-03-23 16:04:04', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('5', '2', 'N', '无5', null, '2017-03-24 16:04:05', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('6', '3', 'N', '无6', null, '2017-03-25 16:04:06', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('7', '1', 'N', '无7', null, '2017-03-26 16:04:07', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('8', '2', 'N', '无8', null, '2017-03-27 16:04:08', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('9', '3', 'N', '', null, '2017-03-28 16:04:09', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('10', '1', 'N', '无0', null, '2017-03-29 16:04:10', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('11', '2', 'N', '无1', null, '2017-03-30 16:04:11', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('12', '3', 'N', '无2', null, '2017-03-31 16:04:12', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('13', '1', 'N', '无3', null, '2017-04-01 16:04:13', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('14', '2', 'N', '无4', null, '2017-04-02 16:04:14', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('15', '3', 'N', '给卢局长', null, '2017-04-03 16:04:15', '2017-03-29 14:54:12');
INSERT INTO `zbxl_customer_confirm` VALUES ('16', '1', 'N', '无6', null, '2017-04-04 16:04:16', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('17', '2', 'N', '无7', null, '2017-04-05 16:04:17', '2017-03-27 16:04:07');
INSERT INTO `zbxl_customer_confirm` VALUES ('18', '3', 'N', '3212312123', null, '2017-04-06 16:04:18', '2017-03-29 15:48:50');
INSERT INTO `zbxl_customer_confirm` VALUES ('19', '1', 'N', '23123123123', null, '2017-04-07 16:21:19', '2017-03-29 15:46:00');
INSERT INTO `zbxl_customer_confirm` VALUES ('20', '3', 'N', '啊', null, '2017-04-08 10:36:29', '2017-03-29 10:36:29');
INSERT INTO `zbxl_customer_confirm` VALUES ('21', '6', 'Y', '', null, '2017-04-18 10:37:14', '2017-04-18 10:37:14');

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
  PRIMARY KEY (`cust_num`),
  UNIQUE KEY `id` (`cust_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_customer_info
-- ----------------------------
INSERT INTO `zbxl_customer_info` VALUES ('1', '王瀚', '110104198909013034', '10394837274', '13552259648', '18618457910', '航天桥', '1', '1', '1', 'A', null, '2017-04-17 09:43:50', '2017-04-17 09:37:37', '1', '1', '3');
INSERT INTO `zbxl_customer_info` VALUES ('2', '闫冉', '130503198910010665', '34958736283', '13552259648', '18515367570', '玉泉营', '1', '1', '1', 'B', null, '2017-04-17 13:35:23', '2017-04-05 13:35:23', '1', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('3', '刘畅', '420222195102117227', '', '', '18618457910', '', '1', '1', '1', 'A', null, '2017-04-17 16:08:26', '2017-04-05 16:08:26', '2', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('4', '张贤兵', '420222194901273213', '', '', '13247237116', '', '1', '1', '1', 'A', null, '2017-04-18 10:06:04', '2017-04-18 10:06:04', '1', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('5', '黄为杰', '420222195112210011', '', '', '13297646570', '', '1', '1', '1', 'A', null, '2017-04-18 10:06:41', '2017-04-18 10:06:41', '1', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('6', '朱新意', '420222194810297922', '', '', '18062286862', '', '1', '1', '1', 'A', null, '2017-04-18 10:07:21', '2017-04-18 10:07:21', '1', '0', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_level
-- ----------------------------
INSERT INTO `zbxl_level` VALUES ('1', '用户登记', '0', null, '2017-01-20 14:30:50', '2017-01-20 14:30:50');
INSERT INTO `zbxl_level` VALUES ('2', '声纹管理', '0', null, '2017-01-20 14:31:00', '2017-01-20 14:31:00');
INSERT INTO `zbxl_level` VALUES ('3', '系统设置', '0', null, '2017-01-20 14:31:17', '2017-01-20 14:31:17');
INSERT INTO `zbxl_level` VALUES ('4', '添加主动验证用户', '1', null, '2017-01-20 06:36:30', '2017-01-20 06:36:30');
INSERT INTO `zbxl_level` VALUES ('5', '添加被动验证用户', '1', null, '2017-01-20 06:36:44', '2017-01-20 06:36:44');
INSERT INTO `zbxl_level` VALUES ('6', '无敌权限', '0', null, '2017-01-23 06:51:06', '2017-01-23 06:51:06');

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
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_log
-- ----------------------------
INSERT INTO `zbxl_log` VALUES ('9', 'ivr', '没成功data', '没成功url', null, '2017-02-22 12:25:07', '2017-02-22 12:25:07');
INSERT INTO `zbxl_log` VALUES ('10', 'ivr', '68', 'record/20170222/4e078636-f8b7-11e6-800d-2558b04728bf.wav', null, '2017-02-22 12:28:31', '2017-02-22 12:28:31');
INSERT INTO `zbxl_log` VALUES ('11', 'ivr', '68', 'record/20170222/b65c6792-f8b7-11e6-8026-2558b04728bf.wav', null, '2017-02-22 12:31:26', '2017-02-22 12:31:26');
INSERT INTO `zbxl_log` VALUES ('12', 'test001', '添加新用户', '姓名:测试二年审号:18581681660', null, '2017-02-22 15:22:02', '2017-02-22 15:22:02');
INSERT INTO `zbxl_log` VALUES ('13', 'test001', '添加新用户', '姓名:测试一年审号:13800138000', null, '2017-03-02 10:53:58', '2017-03-02 10:53:58');
INSERT INTO `zbxl_log` VALUES ('14', 'test001', '添加新用户', '姓名:王瀚年审号:18581681660', null, '2017-03-23 10:42:03', '2017-03-23 10:42:03');
INSERT INTO `zbxl_log` VALUES ('15', 'test001', '添加新用户', '姓名:刘备年审号:15629708605', null, '2017-03-28 12:57:17', '2017-03-28 12:57:17');
INSERT INTO `zbxl_log` VALUES ('16', 'test001', '添加新用户', '姓名:关羽年审号:15629710117', null, '2017-03-28 12:58:01', '2017-03-28 12:58:01');
INSERT INTO `zbxl_log` VALUES ('17', 'test001', '修改认证表备注', '把主键是15的备注改成了<哈哈哈修改了>', null, '2017-03-29 13:54:34', '2017-03-29 13:54:34');
INSERT INTO `zbxl_log` VALUES ('18', 'test001', '修改认证表备注', '把主键是15的备注改成了<改回来了>', null, '2017-03-29 13:56:42', '2017-03-29 13:56:42');
INSERT INTO `zbxl_log` VALUES ('19', 'test001', '修改认证表备注', '把主键是15的备注改成了<再改>', null, '2017-03-29 13:57:46', '2017-03-29 13:57:46');
INSERT INTO `zbxl_log` VALUES ('20', 'test001', '修改认证表备注', '把主键是15的备注改成了<oye>', null, '2017-03-29 14:00:05', '2017-03-29 14:00:05');
INSERT INTO `zbxl_log` VALUES ('21', 'test001', '修改认证表备注', '把主键是15的备注改成了<haha>', null, '2017-03-29 14:01:10', '2017-03-29 14:01:10');
INSERT INTO `zbxl_log` VALUES ('22', 'test001', '修改认证表备注', '把主键是15的备注改成了<123>', null, '2017-03-29 14:02:39', '2017-03-29 14:02:39');
INSERT INTO `zbxl_log` VALUES ('23', 'test001', '修改认证表备注', '把主键是15的备注改成了<>', null, '2017-03-29 14:05:01', '2017-03-29 14:05:01');
INSERT INTO `zbxl_log` VALUES ('24', 'test001', '修改认证表备注', '把主键是18的备注改成了<zzzzzzzzzzzzzz>', null, '2017-03-29 14:10:06', '2017-03-29 14:10:06');
INSERT INTO `zbxl_log` VALUES ('25', 'test001', '修改认证表备注', '把主键是15的备注改成了<给卢局长>', null, '2017-03-29 14:54:12', '2017-03-29 14:54:12');
INSERT INTO `zbxl_log` VALUES ('26', 'test001', '修改认证表备注', '把主键是19的备注改成了<啊啊啊>', null, '2017-03-29 15:45:48', '2017-03-29 15:45:48');
INSERT INTO `zbxl_log` VALUES ('27', 'test001', '修改认证表的认证结果', '把主键是19的认证结果改成了<Y>', null, '2017-03-29 15:46:00', '2017-03-29 15:46:00');
INSERT INTO `zbxl_log` VALUES ('28', 'test001', '修改认证表备注', '把主键是19的备注改成了<>', null, '2017-03-29 15:46:00', '2017-03-29 15:46:00');
INSERT INTO `zbxl_log` VALUES ('29', 'test001', '修改认证表的认证结果', '把主键是18的认证结果改成了<Y>', null, '2017-03-29 15:48:50', '2017-03-29 15:48:50');
INSERT INTO `zbxl_log` VALUES ('30', 'test001', '修改认证表备注', '把主键是18的备注改成了<3212312123>', null, '2017-03-29 15:48:50', '2017-03-29 15:48:50');
INSERT INTO `zbxl_log` VALUES ('31', 'test001', '添加新用户', '姓名:王瀚年审号:18618457910', null, '2017-04-05 09:43:50', '2017-04-05 09:43:50');
INSERT INTO `zbxl_log` VALUES ('32', 'test001', '添加新用户', '姓名:闫冉年审号:18515367570', null, '2017-04-05 13:35:23', '2017-04-05 13:35:23');
INSERT INTO `zbxl_log` VALUES ('33', 'test001', '添加新用户', '姓名:刘畅年审号:18618457910', null, '2017-04-05 16:08:26', '2017-04-05 16:08:26');
INSERT INTO `zbxl_log` VALUES ('34', 'test001', '修改认证表的认证结果', '把主键是3的认证结果改成了<Y>', null, '2017-04-07 11:54:02', '2017-04-07 11:54:02');
INSERT INTO `zbxl_log` VALUES ('35', 'test001', '修改认证表备注', '把主键是3的备注改成了<手动通过>', null, '2017-04-07 11:54:02', '2017-04-07 11:54:02');
INSERT INTO `zbxl_log` VALUES ('36', 'test001', '修改认证表的认证结果', '把主键是3的认证结果改成了<Y>', null, '2017-04-07 11:55:47', '2017-04-07 11:55:47');
INSERT INTO `zbxl_log` VALUES ('37', 'test001', '修改认证表备注', '把主键是3的备注改成了<通过了>', null, '2017-04-07 11:55:47', '2017-04-07 11:55:47');
INSERT INTO `zbxl_log` VALUES ('38', 'test001', '修改客户姓名', '主键:1修改内容:王瀚瀚=>王瀚', null, '2017-04-17 09:37:37', '2017-04-17 09:37:37');
INSERT INTO `zbxl_log` VALUES ('39', 'test001', '添加新用户', '姓名:张贤兵年审号:13247237116', null, '2017-04-18 10:06:04', '2017-04-18 10:06:04');
INSERT INTO `zbxl_log` VALUES ('40', 'test001', '添加新用户', '姓名:黄为杰年审号:13297646570', null, '2017-04-18 10:06:41', '2017-04-18 10:06:41');
INSERT INTO `zbxl_log` VALUES ('41', 'test001', '添加新用户', '姓名:朱新意年审号:18062286862', null, '2017-04-18 10:07:21', '2017-04-18 10:07:21');

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_project
-- ----------------------------
INSERT INTO `zbxl_project` VALUES ('1', '邢台', '0', null, '2017-01-16 16:06:01', '2017-01-16 16:06:01');
INSERT INTO `zbxl_project` VALUES ('2', '北京', '0', null, '2017-01-16 16:06:09', '2017-01-16 16:06:09');
INSERT INTO `zbxl_project` VALUES ('3', '大同', '0', null, '2017-01-16 16:06:16', '2017-01-16 16:06:16');
INSERT INTO `zbxl_project` VALUES ('4', '桥东', '1', null, '2017-01-16 16:20:52', '2017-01-16 16:20:52');
INSERT INTO `zbxl_project` VALUES ('5', '桥西', '1', null, '2017-01-16 16:21:00', '2017-01-16 16:21:00');
INSERT INTO `zbxl_project` VALUES ('6', '海淀', '2', null, '2017-01-16 16:21:10', '2017-01-16 16:21:10');
INSERT INTO `zbxl_project` VALUES ('7', '丰台', '2', null, '2017-01-16 16:21:15', '2017-01-16 16:21:15');
INSERT INTO `zbxl_project` VALUES ('8', '燕云台', '5', null, '2017-01-16 16:23:43', '2017-01-16 16:23:43');
INSERT INTO `zbxl_project` VALUES ('9', '钓鱼台', '6', null, '2017-01-16 16:23:59', '2017-01-16 16:23:59');
INSERT INTO `zbxl_project` VALUES ('10', '大陈庄', '4', null, '2017-01-16 16:24:07', '2017-01-16 16:24:07');
INSERT INTO `zbxl_project` VALUES ('11', '保定', '0', null, '2017-01-20 03:03:14', '2017-01-20 03:03:14');
INSERT INTO `zbxl_project` VALUES ('12', '尚都', '3', null, '2017-01-20 03:03:29', '2017-01-20 03:03:29');
INSERT INTO `zbxl_project` VALUES ('13', '澳洲', '0', null, '2017-01-20 03:11:13', '2017-01-20 03:11:13');
INSERT INTO `zbxl_project` VALUES ('14', '悉尼', '13', null, '2017-01-20 03:15:04', '2017-01-20 03:15:04');
INSERT INTO `zbxl_project` VALUES ('15', '墨尔本', '13', null, '2017-01-20 06:21:04', '2017-01-20 06:21:04');
INSERT INTO `zbxl_project` VALUES ('16', '哈哈', '0', null, '2017-01-23 06:13:23', '2017-01-23 06:13:23');
INSERT INTO `zbxl_project` VALUES ('17', '呵呵', '16', null, '2017-01-23 06:39:29', '2017-01-23 06:39:29');
INSERT INTO `zbxl_project` VALUES ('18', '我靠', '17', null, '2017-01-23 06:40:18', '2017-01-23 06:40:18');
INSERT INTO `zbxl_project` VALUES ('19', '湖北', '0', null, '2017-02-04 02:52:49', '2017-02-04 02:52:49');
INSERT INTO `zbxl_project` VALUES ('20', '天门', '19', null, '2017-02-04 02:53:07', '2017-02-04 02:53:07');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_si_type
-- ----------------------------
INSERT INTO `zbxl_si_type` VALUES ('1', '城乡居民', null, '2017-01-20 06:09:54', '2017-01-20 06:09:54');
INSERT INTO `zbxl_si_type` VALUES ('2', '事业机关', null, '2017-01-20 06:10:14', '2017-01-20 06:10:14');
INSERT INTO `zbxl_si_type` VALUES ('3', '企业职工', null, '2017-01-20 06:10:22', '2017-01-20 06:10:22');
INSERT INTO `zbxl_si_type` VALUES ('5', '事业单位', null, '2017-02-04 02:51:53', '2017-02-04 02:51:53');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_staff_info
-- ----------------------------
INSERT INTO `zbxl_staff_info` VALUES ('1', 'test001', 'bec99deaaf19c593613ffbcc', '110104198909013034', '王瀚', '0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15', '0,1,2,3', '0,1,4,5,2,3', null, '2017-01-21 07:57:09', '2017-01-21 07:57:09');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_staff_mail
-- ----------------------------
INSERT INTO `zbxl_staff_mail` VALUES ('1', '1', 'allstaff', '全体员工注意：\r\n今天放假了！', null, '2017-02-17 02:52:21', '2017-02-17 02:52:21');
INSERT INTO `zbxl_staff_mail` VALUES ('2', '2', '2', '北京的员工注意了：\r\n明天也放假！', null, '2017-02-17 02:52:44', '2017-02-17 02:52:44');
INSERT INTO `zbxl_staff_mail` VALUES ('3', '3', '1', '城乡居民的员工注意了：\r\n后天还放假放假！', null, '2017-02-17 02:53:04', '2017-02-17 02:53:04');
INSERT INTO `zbxl_staff_mail` VALUES ('4', '4', 'test001', 'test001你注意了：\r\n你下周才放假！', null, '2017-02-17 02:53:39', '2017-02-17 02:53:39');
INSERT INTO `zbxl_staff_mail` VALUES ('5', '1', 'allstaff', '全体员工注意了：\r\n吃葡萄不吐葡萄皮，不吃葡萄倒吐葡萄皮', null, '2017-02-17 10:55:09', '2017-02-17 10:55:09');
INSERT INTO `zbxl_staff_mail` VALUES ('6', '4', 'wanghan', 'hello', null, '2017-02-17 16:48:52', '2017-02-17 16:48:52');

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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_vocalprint
-- ----------------------------
INSERT INTO `zbxl_vocalprint` VALUES ('1', '1', '登记', 'c:/wanghan.wav', 'c:/wanghan_model.wav', null, '2017-04-17 10:28:17', '2017-04-17 10:28:17');
INSERT INTO `zbxl_vocalprint` VALUES ('2', '1', '验证', 'c:/wanghan.wav', '', null, '2017-04-17 10:31:38', '2017-04-17 10:31:38');
INSERT INTO `zbxl_vocalprint` VALUES ('3', '1', '登记', 'c:\\liuchang.wav', 'c:\\liuchang_model.wav', null, '2017-04-17 15:25:20', '2017-04-17 15:25:20');
INSERT INTO `zbxl_vocalprint` VALUES ('4', '1', '登记', 'c:\\liuchang.wav', 'c:\\liuchang_model.wav', null, '2017-04-17 15:28:24', '2017-04-17 15:28:24');
INSERT INTO `zbxl_vocalprint` VALUES ('5', '1', '登记', 'c:\\liuchang.wav', 'c:\\liuchang_model.wav', null, '2017-04-17 15:29:00', '2017-04-17 15:29:00');
INSERT INTO `zbxl_vocalprint` VALUES ('6', '1', '登记', 'c:\\liuchang.wav', 'c:\\liuchang_model.wav', null, '2017-04-17 15:29:22', '2017-04-17 15:29:22');
INSERT INTO `zbxl_vocalprint` VALUES ('7', '1', '登记', 'C:\\PHPTEST\\vhosts\\zbxl.com\\public\\liuchang.wav', 'c:\\liuchang_model.wav', null, '2017-04-17 15:33:59', '2017-04-17 15:33:59');
INSERT INTO `zbxl_vocalprint` VALUES ('8', '1', '登记', 'c:\\liuchang.wav', 'c:\\liuchang_model.wav', null, '2017-04-17 16:55:00', '2017-04-17 16:55:00');
INSERT INTO `zbxl_vocalprint` VALUES ('9', '1', '登记', 'c:\\liuchang.wav', 'c:\\liuchang_model.wav', null, '2017-04-17 17:01:15', '2017-04-17 17:01:15');
INSERT INTO `zbxl_vocalprint` VALUES ('10', '1', '登记', 'c:\\liuchang.wav', 'c:\\liuchang_model.wav', null, '2017-04-17 17:04:24', '2017-04-17 17:04:24');
INSERT INTO `zbxl_vocalprint` VALUES ('11', '1', '登记', 'c:\\liuchang.wav', '', null, '2017-04-17 17:08:37', '2017-04-17 17:08:37');
INSERT INTO `zbxl_vocalprint` VALUES ('12', '1', '验证', 'c:\\liuchang.wav', '', null, '2017-04-17 17:12:03', '2017-04-17 17:12:03');
INSERT INTO `zbxl_vocalprint` VALUES ('13', '1', '验证', 'c:\\liuchang.wav', '', null, '2017-04-17 17:12:07', '2017-04-17 17:12:07');
INSERT INTO `zbxl_vocalprint` VALUES ('14', '1', '验证', 'c:\\liuc123hang.wav', '', null, '2017-04-17 17:12:15', '2017-04-17 17:12:15');
INSERT INTO `zbxl_vocalprint` VALUES ('15', '1', '登记', 'c:\\liuchang.wav', '', null, '2017-04-18 08:49:41', '2017-04-18 08:49:41');
INSERT INTO `zbxl_vocalprint` VALUES ('16', '1', '轮播', '123.wav', '', null, '2017-04-18 10:18:37', '2017-04-18 10:18:37');
INSERT INTO `zbxl_vocalprint` VALUES ('17', '2', '轮播', '234.wav', '', null, '2017-04-18 10:20:07', '2017-04-18 10:20:07');
INSERT INTO `zbxl_vocalprint` VALUES ('18', '3', '轮播', '345.wav', '', null, '2017-04-18 10:20:14', '2017-04-18 10:20:14');
INSERT INTO `zbxl_vocalprint` VALUES ('19', '4', '轮播', '456.wav', '', null, '2017-04-18 10:20:20', '2017-04-18 10:20:20');
INSERT INTO `zbxl_vocalprint` VALUES ('20', '5', '轮播', '567.wav', '', null, '2017-04-18 10:20:25', '2017-04-18 10:20:25');
INSERT INTO `zbxl_vocalprint` VALUES ('21', '6', '轮播', '678.wav', '', null, '2017-04-18 10:20:32', '2017-04-18 10:20:32');
INSERT INTO `zbxl_vocalprint` VALUES ('22', '6', '轮播', '678.wav', '', null, '2017-04-18 10:20:40', '2017-04-18 10:20:40');
INSERT INTO `zbxl_vocalprint` VALUES ('23', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:16', '2017-04-18 10:23:16');
INSERT INTO `zbxl_vocalprint` VALUES ('24', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:17', '2017-04-18 10:23:17');
INSERT INTO `zbxl_vocalprint` VALUES ('25', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:17', '2017-04-18 10:23:17');
INSERT INTO `zbxl_vocalprint` VALUES ('26', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:18', '2017-04-18 10:23:18');
INSERT INTO `zbxl_vocalprint` VALUES ('27', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:18', '2017-04-18 10:23:18');
INSERT INTO `zbxl_vocalprint` VALUES ('28', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:19', '2017-04-18 10:23:19');
INSERT INTO `zbxl_vocalprint` VALUES ('29', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:20', '2017-04-18 10:23:20');
INSERT INTO `zbxl_vocalprint` VALUES ('30', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:20', '2017-04-18 10:23:20');
INSERT INTO `zbxl_vocalprint` VALUES ('31', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:21', '2017-04-18 10:23:21');
INSERT INTO `zbxl_vocalprint` VALUES ('32', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:22', '2017-04-18 10:23:22');
INSERT INTO `zbxl_vocalprint` VALUES ('33', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:22', '2017-04-18 10:23:22');
INSERT INTO `zbxl_vocalprint` VALUES ('34', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:23', '2017-04-18 10:23:23');
INSERT INTO `zbxl_vocalprint` VALUES ('35', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:24', '2017-04-18 10:23:24');
INSERT INTO `zbxl_vocalprint` VALUES ('36', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:24', '2017-04-18 10:23:24');
INSERT INTO `zbxl_vocalprint` VALUES ('37', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:25', '2017-04-18 10:23:25');
INSERT INTO `zbxl_vocalprint` VALUES ('38', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:26', '2017-04-18 10:23:26');
INSERT INTO `zbxl_vocalprint` VALUES ('39', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:26', '2017-04-18 10:23:26');
INSERT INTO `zbxl_vocalprint` VALUES ('40', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:27', '2017-04-18 10:23:27');
INSERT INTO `zbxl_vocalprint` VALUES ('41', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:27', '2017-04-18 10:23:27');
INSERT INTO `zbxl_vocalprint` VALUES ('42', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:28', '2017-04-18 10:23:28');
INSERT INTO `zbxl_vocalprint` VALUES ('43', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:28', '2017-04-18 10:23:28');
INSERT INTO `zbxl_vocalprint` VALUES ('44', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:29', '2017-04-18 10:23:29');
INSERT INTO `zbxl_vocalprint` VALUES ('45', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:29', '2017-04-18 10:23:29');
INSERT INTO `zbxl_vocalprint` VALUES ('46', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:30', '2017-04-18 10:23:30');
INSERT INTO `zbxl_vocalprint` VALUES ('47', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:30', '2017-04-18 10:23:30');
INSERT INTO `zbxl_vocalprint` VALUES ('48', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:31', '2017-04-18 10:23:31');
INSERT INTO `zbxl_vocalprint` VALUES ('49', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:32', '2017-04-18 10:23:32');
INSERT INTO `zbxl_vocalprint` VALUES ('50', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:32', '2017-04-18 10:23:32');
INSERT INTO `zbxl_vocalprint` VALUES ('51', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:33', '2017-04-18 10:23:33');
INSERT INTO `zbxl_vocalprint` VALUES ('52', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:33', '2017-04-18 10:23:33');
INSERT INTO `zbxl_vocalprint` VALUES ('53', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:34', '2017-04-18 10:23:34');
INSERT INTO `zbxl_vocalprint` VALUES ('54', '6', '轮播', '678.wav', '', null, '2017-04-18 10:23:34', '2017-04-18 10:23:34');
INSERT INTO `zbxl_vocalprint` VALUES ('55', '6', '轮播', '678.wav', '', null, '2017-04-18 10:37:14', '2017-04-18 10:37:14');
