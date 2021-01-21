/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100113
 Source Host           : localhost:3306
 Source Schema         : tp5

 Target Server Type    : MySQL
 Target Server Version : 100113
 File Encoding         : 65001

 Date: 21/01/2021 14:07:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_class_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_class_course`;
CREATE TABLE `yunzhi_class_course`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NULL DEFAULT NULL COMMENT '上课对应的课程id',
  `classroom_id` int(11) NULL DEFAULT NULL COMMENT '上课对应的教室id\r\n',
  `out_time` int(11) NULL DEFAULT NULL COMMENT '上课的截止时间',
  `begin_time` int(11) NULL DEFAULT NULL COMMENT '上课开始的时间',
  `teacher_id` int(11) NULL DEFAULT NULL COMMENT '上课对应的教师id',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `sign_deadline_time` int(11) NULL DEFAULT NULL COMMENT '记录课程的签到截止时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 202 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for yunzhi_class_detail
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_class_detail`;
CREATE TABLE `yunzhi_class_detail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_course_id` int(11) NULL DEFAULT NULL COMMENT '与该条上课信息关联的课程id',
  `student_id` int(11) NULL DEFAULT NULL COMMENT '与该条上课信息其关联的学生id',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '\r\n既是这条数据的创造时间，同时也就代表着该学生的签到时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '数据更新时间，如果没有默认为跟创造时间是相同的\r\n',
  `aod_num` int(11) NULL DEFAULT NULL COMMENT '统计本节课该学生的加减分情况',
  `seat_id` int(11) NULL DEFAULT NULL COMMENT '统计该堂课学生所做的座位',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 88 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for yunzhi_classroom
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_classroom`;
CREATE TABLE `yunzhi_classroom`  (
  `seat_map_id` int(11) NOT NULL,
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '教室编号',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `course_id` int(11) NOT NULL DEFAULT 0,
  `begin_time` int(11) NULL DEFAULT NULL COMMENT '该教室签到起始时间',
  `out_time` int(11) NULL DEFAULT NULL COMMENT '该教室签到截止时间',
  `sign_deadline_time` int(11) NULL DEFAULT NULL,
  `sign_time` int(11) NOT NULL COMMENT '签到时长',
  `sign_begin_time` int(11) NULL DEFAULT NULL COMMENT '负责记录签到开始时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 52 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course`;
CREATE TABLE `yunzhi_course`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `teacher_id` int(11) NOT NULL COMMENT '该课程对应的教师id',
  `student_num` int(11) NULL DEFAULT NULL COMMENT '学生人数',
  `usmix` int(11) NOT NULL DEFAULT 50 COMMENT '签到成绩占比',
  `courseup` int(11) NOT NULL DEFAULT 100 COMMENT '上课表现成绩最大值',
  `begincougrade` int(11) NOT NULL COMMENT '上课表现初始成绩',
  `resigternum` int(11) NOT NULL COMMENT '该课程签到总次数',
  `term_id` int(11) NULL DEFAULT NULL COMMENT '对应的学期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 186 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_course_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course_student`;
CREATE TABLE `yunzhi_course_student`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 220 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for yunzhi_grade
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_grade`;
CREATE TABLE `yunzhi_grade`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NULL DEFAULT NULL COMMENT '学生id',
  `course_id` int(11) NULL DEFAULT NULL COMMENT '课程id',
  `coursegrade` int(11) NULL DEFAULT NULL COMMENT '上课表现成绩',
  `usgrade` int(11) NULL DEFAULT NULL COMMENT '签到成绩',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `allgrade` int(11) NULL DEFAULT NULL COMMENT '总成绩',
  `resigternum` int(11) NULL DEFAULT NULL COMMENT '上课签到次数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 361 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for yunzhi_gradeaod
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_gradeaod`;
CREATE TABLE `yunzhi_gradeaod`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `aod_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '加减分项的名称',
  `aod_num` int(11) NOT NULL COMMENT '加减分数的分值，如果是加分即为正，如果是减分即为负，同时跟aod_state的是相对应的，新增时增加判断',
  `create_time` int(11) NOT NULL COMMENT '加减分项的创建时间',
  `update_time` int(11) NOT NULL COMMENT '加减分项的更新时间',
  `course_id` int(11) NOT NULL COMMENT '通过不同的课程来统计不同的加减分项，便于老师下次可以直接选择加减分项',
  `aod_state` int(11) NULL DEFAULT NULL COMMENT '负责区分加分项还是减分项，如果是加分项即为1，如果是减分项，即为0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for yunzhi_seat
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat`;
CREATE TABLE `yunzhi_seat`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `x` int(11) NOT NULL DEFAULT 0 COMMENT '此座位对应的横坐标',
  `y` int(11) NOT NULL DEFAULT 0 COMMENT '此座位对应座位图中的纵坐标',
  `is_seated` int(1) NOT NULL DEFAULT 0 COMMENT '座位是否被有人坐的判断，如果有人那么为1否则为0',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '座位的创建时间\r\n',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '座位的更新时间',
  `is_seat` int(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '座位或者过道的判断，0表示座位，1表示过道',
  `classroom_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '此座位对应的教室id\r\n',
  `student_id` int(11) NULL DEFAULT NULL COMMENT '此时座位上课学生id，如果为空则为null或0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 244636 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = FIXED;

-- ----------------------------
-- Table structure for yunzhi_seat_aisle
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat_aisle`;
CREATE TABLE `yunzhi_seat_aisle`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `seat_map_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应座位模板的id',
  `x` int(11) NOT NULL DEFAULT 0 COMMENT '对应所在的座位模板的横坐标',
  `y` int(11) NOT NULL DEFAULT 0 COMMENT '对应所在的座位模板的纵坐标',
  `create_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '创建时间',
  `update_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '更新时间',
  `state` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '区分座位或过道，如果为0则为座位，否则为过道',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 248230 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = FIXED;

-- ----------------------------
-- Table structure for yunzhi_seat_map
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat_map`;
CREATE TABLE `yunzhi_seat_map`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `x_map` int(2) UNSIGNED NOT NULL COMMENT '座位模板的行数',
  `y_map` int(2) UNSIGNED NOT NULL COMMENT '座位模板的列数',
  `is_last` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '按段是否为最后一个',
  `is_first` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '判断当前是否为第一个',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 131 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = FIXED;

-- ----------------------------
-- Table structure for yunzhi_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_student`;
CREATE TABLE `yunzhi_student`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `num` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `sex` tinyint(2) NOT NULL DEFAULT 0,
  `email` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户名：用于登录（用户名不可相同）',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码：用于登录',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 126 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_teacher
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_teacher`;
CREATE TABLE `yunzhi_teacher`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `password` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `username` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `num` int(11) NULL DEFAULT NULL COMMENT '教室工号',
  `classroom_id` int(11) NULL DEFAULT NULL COMMENT '微信端扫码之后在下课之前绑定的教室对象id，桥接微信端和web端',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_term
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_term`;
CREATE TABLE `yunzhi_term`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '学期名',
  `ptime` date NOT NULL COMMENT '起始时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `ftime` date NOT NULL COMMENT '学期截止时间',
  `state` tinyint(1) NOT NULL COMMENT '此学期的状态：是否被激活（默认只有一个激活状态）',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 78691 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
