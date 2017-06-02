/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50632
Source Host           : localhost:3306
Source Database       : shengwen

Target Server Type    : MYSQL
Target Server Version : 50632
File Encoding         : 65001

Date: 2017-06-02 17:36:47
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
  `belong_to` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '这条数据分配给某个员工',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除标志位',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`confirm_num`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_customer_confirm
-- ----------------------------
INSERT INTO `zbxl_customer_confirm` VALUES ('1', '1', 'N', null, '0', null, '2017-05-17 09:49:24', '2017-06-02 16:26:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('2', '1', 'N', null, '0', null, '2017-05-17 09:49:35', '2017-06-02 16:26:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('3', '1', 'N', '123', '0', null, '2017-05-17 09:49:42', '2017-06-02 16:26:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('4', '2', 'N', 'oye', '0', null, '2017-05-17 09:49:47', '2017-06-02 16:30:41');
INSERT INTO `zbxl_customer_confirm` VALUES ('5', '2', 'N', null, '0', null, '2017-05-17 09:49:52', '2017-06-02 16:30:41');
INSERT INTO `zbxl_customer_confirm` VALUES ('6', '2', 'N', null, '0', null, '2017-05-17 09:49:56', '2017-06-02 16:30:41');
INSERT INTO `zbxl_customer_confirm` VALUES ('7', '3', 'N', null, '0', null, '2017-05-17 09:50:02', '2017-06-02 16:30:41');
INSERT INTO `zbxl_customer_confirm` VALUES ('8', '3', 'N', null, '0', null, '2017-05-17 09:50:06', '2017-06-02 16:30:41');
INSERT INTO `zbxl_customer_confirm` VALUES ('9', '3', 'N', null, '0', null, '2017-05-17 09:50:11', '2017-06-02 16:30:41');
INSERT INTO `zbxl_customer_confirm` VALUES ('10', '1', 'N', null, '0', null, '2017-05-17 15:20:52', '2017-06-02 16:26:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('11', '2', 'N', null, '0', null, '2017-05-17 15:20:56', '2017-06-02 16:30:41');
INSERT INTO `zbxl_customer_confirm` VALUES ('12', '3', 'N', null, '0', null, '2017-05-17 15:20:59', '2017-06-02 16:30:41');
INSERT INTO `zbxl_customer_confirm` VALUES ('13', '1', 'N', null, '0', null, '2017-05-17 15:22:01', '2017-06-02 16:26:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('14', '2', 'N', null, '0', null, '2017-05-17 15:22:04', '2017-06-02 16:30:41');
INSERT INTO `zbxl_customer_confirm` VALUES ('15', '3', 'N', null, '0', null, '2017-05-17 15:22:08', '2017-06-02 16:30:41');
INSERT INTO `zbxl_customer_confirm` VALUES ('16', '4', 'N', null, '0', null, '2017-05-17 15:28:56', '2017-06-02 16:30:15');
INSERT INTO `zbxl_customer_confirm` VALUES ('17', '5', 'N', null, '0', null, '2017-05-17 15:29:01', '2017-06-02 16:30:15');
INSERT INTO `zbxl_customer_confirm` VALUES ('18', '6', 'N', null, '0', null, '2017-05-17 15:29:05', '2017-06-02 16:30:15');
INSERT INTO `zbxl_customer_confirm` VALUES ('19', '7', 'N', null, '4', null, '2017-05-17 15:29:10', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('20', '8', 'N', null, '4', null, '2017-05-17 15:29:15', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('21', '9', 'N', null, '4', null, '2017-05-17 15:29:18', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('22', '10', 'N', null, '4', null, '2017-05-17 15:29:22', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('23', '11', 'N', null, '4', null, '2017-05-17 15:29:28', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('24', '12', 'N', null, '4', null, '2017-05-17 15:29:34', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('25', '13', 'N', null, '4', null, '2017-05-17 15:29:38', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('26', '14', 'N', '旧的', '4', null, '2017-05-17 15:29:50', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('27', '15', 'N', '旧的', '4', null, '2017-05-17 15:30:09', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('28', '15', 'N', '新的', '4', null, '2017-05-16 11:13:07', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('29', '14', 'N', '新的', '4', null, '2017-05-20 11:14:32', '2017-06-02 17:25:14');
INSERT INTO `zbxl_customer_confirm` VALUES ('30', '0', '', null, '0', null, '2017-06-02 17:22:31', '2017-06-02 17:22:31');

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_customer_info
-- ----------------------------
INSERT INTO `zbxl_customer_info` VALUES ('1', '王瀚', '110104198909013034', '', '', '13800138009', '', '1', '1', '1', 'A', null, '2017-05-15 16:08:50', '2017-05-11 16:17:25', '1', '0', '2', '0');
INSERT INTO `zbxl_customer_info` VALUES ('2', '刘畅', '130503198910010665', '', '', '13800138009', '', '1', '1', '1', 'A', null, '2017-05-11 16:09:09', '2017-05-11 16:17:25', '2', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('3', '段冉', '210105198909013034', '', '', '13800138066', '', '1', '1', '1', 'A', null, '2017-05-11 16:09:37', '2017-05-11 16:18:29', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('4', '曹操', '420222194901273213', '', '', '13800138000', '', '1', '1', '1', 'A', null, '2017-05-17 15:24:44', '2017-05-17 15:24:44', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('5', '孙权', '420222195112210011', '', '', '13800138001', '', '1', '1', '1', 'A', null, '2017-05-17 15:25:02', '2017-05-17 15:25:02', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('6', '刘备', '420222195507084857', '', '', '13800138002', '', '1', '1', '1', 'A', null, '2017-05-17 15:25:34', '2017-05-17 15:25:34', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('7', '关羽', '420222195102117227', '', '', '13800138003', '', '1', '1', '1', 'A', null, '2017-05-17 15:25:45', '2017-05-17 15:25:45', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('8', '张飞', '42022219550512611X', '', '', '13800138004', '', '1', '1', '1', 'A', null, '2017-05-17 15:25:57', '2017-05-17 15:25:57', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('9', '马超', '42022219480508616X', '', '', '13800138005', '', '1', '1', '1', 'A', null, '2017-05-17 15:26:10', '2017-05-17 15:26:10', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('10', '黄忠', '420222195103176114', '', '', '13800138006', '', '1', '1', '1', 'A', null, '2017-05-17 15:26:27', '2017-05-17 15:26:27', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('11', '徐晃', '420222195512266137', '', '', '13800138007', '', '1', '1', '1', 'A', null, '2017-05-17 15:26:48', '2017-05-17 15:26:48', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('12', '许褚', '420222195103206125', '', '', '13800138008', '', '1', '1', '1', 'A', null, '2017-05-17 15:27:00', '2017-05-17 15:27:00', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('13', '孙坚', '420222195205256115', '', '', '13800138010', '', '1', '1', '1', 'B', null, '2017-05-17 15:27:17', '2017-05-17 15:27:17', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('14', '孙策', '429006195703016402', '', '', '13800138011', '', '1', '1', '1', 'B', null, '2017-05-17 15:27:27', '2017-05-17 15:27:27', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info` VALUES ('15', '周瑜', '429006194303293318', '', '', '13800138012', '', '1', '1', '1', 'B', null, '2017-05-17 15:27:43', '2017-05-17 15:28:16', '1', '0', '0', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_customer_info_delete_use
-- ----------------------------
INSERT INTO `zbxl_customer_info_delete_use` VALUES ('1', '4', '地方', '420222195102117227', '', '', '13800138001', '', '1', '1', '1', 'A', null, '2017-05-11 09:51:13', '2017-05-11 09:51:13', '2', '0', '0', '0');
INSERT INTO `zbxl_customer_info_delete_use` VALUES ('2', '7', '哈哈', '420222195103176114', '', '', '13800138003', '', '1', '1', '1', 'A', null, '2017-05-11 10:49:04', '2017-05-11 11:21:19', '1', '0', '0', '0');
INSERT INTO `zbxl_customer_info_delete_use` VALUES ('3', '2', '刘畅', '420222195112210011', '', '', '13800138000', '', '1', '1', '1', 'A', null, '2017-05-11 09:50:00', '2017-05-11 09:50:00', '2', '0', '0', '0');
INSERT INTO `zbxl_customer_info_delete_use` VALUES ('4', '1', '王瀚', '420222194901273213', '', '', '13800138000', '', '1', '1', '1', 'A', null, '2017-05-11 09:49:33', '2017-05-11 09:50:00', '1', '0', '2', '0');
INSERT INTO `zbxl_customer_info_delete_use` VALUES ('5', '6', '黄蓉', '42022219480508616X', '', '', '13800138008', '', '1', '1', '1', 'B', null, '2017-05-11 10:37:28', '2017-05-11 10:37:28', '2', '0', '0', '0');
INSERT INTO `zbxl_customer_info_delete_use` VALUES ('6', '5', '李灵薇', '42022219550512611X', '', '', '13800138008', '', '1', '1', '1', 'B', null, '2017-05-11 10:37:14', '2017-05-11 12:39:22', '1', '0', '0', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_log
-- ----------------------------
INSERT INTO `zbxl_log` VALUES ('1', 'test001', '添加新用户', '姓名:王瀚年审号:13800138000', null, '2017-05-11 09:49:33', '2017-05-11 09:49:33');
INSERT INTO `zbxl_log` VALUES ('2', 'test001', '添加新用户', '姓名:刘畅年审号:13800138000', null, '2017-05-11 09:50:00', '2017-05-11 09:50:00');
INSERT INTO `zbxl_log` VALUES ('3', 'test001', '添加新用户', '姓名:天圆年审号:13800138001', null, '2017-05-11 09:50:44', '2017-05-11 09:50:44');
INSERT INTO `zbxl_log` VALUES ('4', 'test001', '添加新用户', '姓名:地方年审号:13800138001', null, '2017-05-11 09:51:13', '2017-05-11 09:51:13');
INSERT INTO `zbxl_log` VALUES ('5', 'test001', '添加新用户', '姓名:郭靖年审号:13800138008', null, '2017-05-11 10:37:14', '2017-05-11 10:37:14');
INSERT INTO `zbxl_log` VALUES ('6', 'test001', '添加新用户', '姓名:黄蓉年审号:13800138008', null, '2017-05-11 10:37:28', '2017-05-11 10:37:28');
INSERT INTO `zbxl_log` VALUES ('7', 'test001', '添加新用户', '姓名:哈哈年审号:13800138003', null, '2017-05-11 10:49:04', '2017-05-11 10:49:04');
INSERT INTO `zbxl_log` VALUES ('8', 'test001', '添加新用户', '姓名:呵呵年审号:13800138004', null, '2017-05-11 10:49:37', '2017-05-11 10:49:37');
INSERT INTO `zbxl_log` VALUES ('9', 'test001', '设置客户为去世状态', '主键:7', null, '2017-05-11 11:20:39', '2017-05-11 11:20:39');
INSERT INTO `zbxl_log` VALUES ('10', 'test001', '设置客户为认证状态', '主键:7', null, '2017-05-11 11:21:19', '2017-05-11 11:21:19');
INSERT INTO `zbxl_log` VALUES ('11', 'test001', '删除客户信息', '主键:4', null, '2017-05-11 11:26:09', '2017-05-11 11:26:09');
INSERT INTO `zbxl_log` VALUES ('12', 'test001', '删除客户信息', '主键:7', null, '2017-05-11 11:33:55', '2017-05-11 11:33:55');
INSERT INTO `zbxl_log` VALUES ('13', 'test001', '删除客户信息', '主键:2', null, '2017-05-11 11:36:21', '2017-05-11 11:36:21');
INSERT INTO `zbxl_log` VALUES ('14', 'test001', '删除客户信息', '主键:1', null, '2017-05-11 11:36:31', '2017-05-11 11:36:31');
INSERT INTO `zbxl_log` VALUES ('15', 'test001', '删除客户信息', '主键:6', null, '2017-05-11 12:39:22', '2017-05-11 12:39:22');
INSERT INTO `zbxl_log` VALUES ('16', 'test001', '删除客户信息', '主键:5', null, '2017-05-11 12:39:45', '2017-05-11 12:39:45');
INSERT INTO `zbxl_log` VALUES ('17', 'test001', '添加新用户', '姓名:王瀚年审号:13800138001', null, '2017-05-11 16:08:50', '2017-05-11 16:08:50');
INSERT INTO `zbxl_log` VALUES ('18', 'test001', '添加新用户', '姓名:刘畅年审号:13800138001', null, '2017-05-11 16:09:09', '2017-05-11 16:09:09');
INSERT INTO `zbxl_log` VALUES ('19', 'test001', '添加新用户', '姓名:段冉年审号:13800138002', null, '2017-05-11 16:09:37', '2017-05-11 16:09:37');
INSERT INTO `zbxl_log` VALUES ('20', 'test001', '修改年审号码', '主键:1修改内容:13800138001=>13800138009', null, '2017-05-11 16:14:21', '2017-05-11 16:14:21');
INSERT INTO `zbxl_log` VALUES ('21', 'test001', '修改年审号码', '主键:2修改内容:13800138001=>13800138009', null, '2017-05-11 16:14:21', '2017-05-11 16:14:21');
INSERT INTO `zbxl_log` VALUES ('22', 'test001', '修改年审号码', '主键:1修改内容:13800138009=>13800138001', null, '2017-05-11 16:15:37', '2017-05-11 16:15:37');
INSERT INTO `zbxl_log` VALUES ('23', 'test001', '修改年审号码', '主键:2修改内容:13800138009=>13800138001', null, '2017-05-11 16:15:37', '2017-05-11 16:15:37');
INSERT INTO `zbxl_log` VALUES ('24', 'test001', '修改年审号码', '主键:1修改内容:13800138001=>13800138009', null, '2017-05-11 16:17:25', '2017-05-11 16:17:25');
INSERT INTO `zbxl_log` VALUES ('25', 'test001', '修改年审号码', '主键:2修改内容:13800138001=>13800138009', null, '2017-05-11 16:17:25', '2017-05-11 16:17:25');
INSERT INTO `zbxl_log` VALUES ('26', 'test001', '修改年审号码', '主键:3修改内容:13800138002=>13800138066', null, '2017-05-11 16:18:29', '2017-05-11 16:18:29');
INSERT INTO `zbxl_log` VALUES ('27', 'test001', '修改认证表的认证结果', '把主键是4的认证结果改成了<Y>', null, '2017-05-17 15:04:16', '2017-05-17 15:04:16');
INSERT INTO `zbxl_log` VALUES ('28', 'test001', '修改认证表备注', '把主键是4的备注改成了<oye>', null, '2017-05-17 15:04:16', '2017-05-17 15:04:16');
INSERT INTO `zbxl_log` VALUES ('29', 'test001', '添加新用户', '姓名:曹操年审号:13800138000', null, '2017-05-17 15:24:44', '2017-05-17 15:24:44');
INSERT INTO `zbxl_log` VALUES ('30', 'test001', '添加新用户', '姓名:孙权年审号:13800138001', null, '2017-05-17 15:25:02', '2017-05-17 15:25:02');
INSERT INTO `zbxl_log` VALUES ('31', 'test001', '添加新用户', '姓名:刘备年审号:13800138002', null, '2017-05-17 15:25:34', '2017-05-17 15:25:34');
INSERT INTO `zbxl_log` VALUES ('32', 'test001', '添加新用户', '姓名:关羽年审号:13800138003', null, '2017-05-17 15:25:45', '2017-05-17 15:25:45');
INSERT INTO `zbxl_log` VALUES ('33', 'test001', '添加新用户', '姓名:张飞年审号:13800138004', null, '2017-05-17 15:25:57', '2017-05-17 15:25:57');
INSERT INTO `zbxl_log` VALUES ('34', 'test001', '添加新用户', '姓名:马超年审号:13800138005', null, '2017-05-17 15:26:10', '2017-05-17 15:26:10');
INSERT INTO `zbxl_log` VALUES ('35', 'test001', '添加新用户', '姓名:黄忠年审号:13800138006', null, '2017-05-17 15:26:27', '2017-05-17 15:26:27');
INSERT INTO `zbxl_log` VALUES ('36', 'test001', '添加新用户', '姓名:徐晃年审号:13800138007', null, '2017-05-17 15:26:48', '2017-05-17 15:26:48');
INSERT INTO `zbxl_log` VALUES ('37', 'test001', '添加新用户', '姓名:许褚年审号:13800138008', null, '2017-05-17 15:27:00', '2017-05-17 15:27:00');
INSERT INTO `zbxl_log` VALUES ('38', 'test001', '添加新用户', '姓名:孙坚年审号:13800138010', null, '2017-05-17 15:27:17', '2017-05-17 15:27:17');
INSERT INTO `zbxl_log` VALUES ('39', 'test001', '添加新用户', '姓名:孙策年审号:13800138011', null, '2017-05-17 15:27:27', '2017-05-17 15:27:27');
INSERT INTO `zbxl_log` VALUES ('40', 'test001', '添加新用户', '姓名:孙权年审号:13800138012', null, '2017-05-17 15:27:43', '2017-05-17 15:27:43');
INSERT INTO `zbxl_log` VALUES ('41', 'test001', '修改客户姓名', '主键:15修改内容:孙权=>周瑜', null, '2017-05-17 15:28:16', '2017-05-17 15:28:16');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_staff_info
-- ----------------------------
INSERT INTO `zbxl_staff_info` VALUES ('1', 'test001', 'bec99deaaf19c593613ffbcc', '110104198909013034', '王瀚', '0,1,2,3,11,13,16,19', '0,1,2,3,5', '0,1,2,3,4,5,6', null, '2017-01-21 07:57:09', '2017-04-21 17:30:28');
INSERT INTO `zbxl_staff_info` VALUES ('2', 'test002', 'bec99deaaf19c593613ffbcc', '130503198910010665', '刘畅', '0,1,2,3,11,13,16,19', '0,1,2,3,5', '0,1,2,3,4,5,6', null, '2017-06-02 10:25:07', '2017-06-02 10:25:07');
INSERT INTO `zbxl_staff_info` VALUES ('3', 'test003', 'bec99deaaf19c593613ffbcc', '210105198909013034', '段冉', '0,1,2,3,11,13,16,19', '0,1,2,3,5', '0,1,2,3,4,5,6', null, '2017-06-02 10:25:51', '2017-06-02 10:25:51');
INSERT INTO `zbxl_staff_info` VALUES ('4', 'test004', 'bec99deaaf19c593613ffbcc', '130503198910010666', '刁一娜', '0,1,2,3,11,13,16,19', '0,1,2,3,5', '0,1,2,3,4,5,6', null, '2017-06-02 13:37:05', '2017-06-02 13:37:05');
INSERT INTO `zbxl_staff_info` VALUES ('5', 'test005', 'bec99deaaf19c593613ffbcc', '130503198910010667', '李灵薇', '0,1,2,3,11,13,16,19', '0,1,2,3,5', '0,1,2,3,4,5,6', null, '2017-06-02 13:37:33', '2017-06-02 13:37:33');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zbxl_vocalprint
-- ----------------------------
INSERT INTO `zbxl_vocalprint` VALUES ('1', '1', '登记', '13800138009_110104198909013034_1494482147.wav', '13800138009_110104198909013034.dat', null, '2017-05-11 16:12:22', '2017-05-11 16:12:22');
INSERT INTO `zbxl_vocalprint` VALUES ('2', '2', '登记', '13800138009_130503198910010665_1494482147.wav', '13800138009_130503198910010665.dat', null, '2017-05-11 16:12:30', '2017-05-11 16:12:30');
INSERT INTO `zbxl_vocalprint` VALUES ('3', '3', '登记', '13800138066_210105198909013034_1494482147.wav', '13800138066_210105198909013034.dat', null, '2017-05-11 16:13:15', '2017-05-11 16:13:15');
