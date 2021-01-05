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

 Date: 05/01/2021 15:56:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_class_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_class_course`;
CREATE TABLE `yunzhi_class_course`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NULL DEFAULT NULL,
  `classroom_id` int(11) NULL DEFAULT NULL,
  `out_time` int(11) NULL DEFAULT NULL,
  `begin_time` int(11) NULL DEFAULT NULL,
  `teacher_id` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  `create_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yunzhi_class_course
-- ----------------------------
INSERT INTO `yunzhi_class_course` VALUES (1, 1, 35, 1609832100, 1609828654, 4, 1609829579, 1609829579);
INSERT INTO `yunzhi_class_course` VALUES (2, 1, 35, 1609832100, 1609829780, 4, 1609829781, 1609829781);
INSERT INTO `yunzhi_class_course` VALUES (3, 1, 35, 1609839000, 1609833184, 4, 1609833185, 1609833185);
INSERT INTO `yunzhi_class_course` VALUES (4, 1, 35, 1609839000, 1609833260, 4, 1609833261, 1609833261);

-- ----------------------------
-- Table structure for yunzhi_class_detail
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_class_detail`;
CREATE TABLE `yunzhi_class_detail`  (
  `id` int(11) NOT NULL,
  `class_course_id` int(11) NULL DEFAULT NULL COMMENT '与该条上课信息关联的课程id',
  `student_id` int(11) NULL DEFAULT NULL COMMENT '与该条上课信息其关联的学生id',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '\r\n既是这条数据的创造时间，同时也就代表着该学生的签到时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '数据更新时间，如果没有默认为跟创造时间是相同的\r\n',
  `aod_num` int(11) NULL DEFAULT NULL COMMENT '统计本节课该学生的加减分情况',
  `seat_id` int(11) NULL DEFAULT NULL COMMENT '统计该堂课学生所做的座位',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yunzhi_class_detail
-- ----------------------------
INSERT INTO `yunzhi_class_detail` VALUES (1, 3, 95, 0, 0, 2, 1);

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
) ENGINE = MyISAM AUTO_INCREMENT = 36 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yunzhi_classroom
-- ----------------------------
INSERT INTO `yunzhi_classroom` VALUES (102, 35, 'A101', 2021, 2021, 3, 1609833260, 1609839000, 1609834500, 20, 1609833300);

-- ----------------------------
-- Table structure for yunzhi_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course`;
CREATE TABLE `yunzhi_course`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `teacher_id` int(11) NOT NULL,
  `student_num` int(11) NULL DEFAULT NULL,
  `usmix` int(11) NOT NULL DEFAULT 50,
  `courseup` int(11) NOT NULL DEFAULT 100,
  `begincougrade` int(11) NOT NULL,
  `onceusgrade` int(11) NOT NULL DEFAULT 2,
  `resigternum` int(11) NOT NULL,
  `term_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 32 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yunzhi_course
-- ----------------------------
INSERT INTO `yunzhi_course` VALUES (1, 'thinkphp5入门实例', 0, 1603111209, 4, 0, 40, 100, 50, 2, 1, 7);
INSERT INTO `yunzhi_course` VALUES (2, 'angularjs入门实例', 0, 1599894922, 4, 0, 50, 100, 49, 2, 50, 7);
INSERT INTO `yunzhi_course` VALUES (3, '高等数学', 1594643989, 1609833261, 1, 0, 80, 100, 30, 2, 24, 6);
INSERT INTO `yunzhi_course` VALUES (4, '计算机组成原理', 1599895144, 1599895144, 1, 0, 50, 100, 25, 2, 50, 6);
INSERT INTO `yunzhi_course` VALUES (29, '离散数学1', 1608599673, 1608599802, 1, 0, 50, 100, 0, 2, 0, 1);

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
) ENGINE = InnoDB AUTO_INCREMENT = 304 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of yunzhi_course_student
-- ----------------------------
INSERT INTO `yunzhi_course_student` VALUES (298, 1, 92, 0, 0);
INSERT INTO `yunzhi_course_student` VALUES (301, 3, 95, 0, 0);
INSERT INTO `yunzhi_course_student` VALUES (302, 3, 96, 0, 0);
INSERT INTO `yunzhi_course_student` VALUES (303, 3, 97, 0, 0);

-- ----------------------------
-- Table structure for yunzhi_grade
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_grade`;
CREATE TABLE `yunzhi_grade`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NULL DEFAULT NULL,
  `course_id` int(11) NULL DEFAULT NULL,
  `coursegrade` int(11) NULL DEFAULT NULL,
  `usgrade` int(11) NULL DEFAULT NULL,
  `create_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  `allgrade` int(11) NULL DEFAULT NULL,
  `resigternum` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of yunzhi_grade
-- ----------------------------
INSERT INTO `yunzhi_grade` VALUES (1, 95, 3, 30, 100, 2147483647, 1609503828, 86, 2);
INSERT INTO `yunzhi_grade` VALUES (2, 96, 3, 30, 0, 0, 0, 30, 0);
INSERT INTO `yunzhi_grade` VALUES (3, 97, 3, 30, 100, 0, 1609419766, 86, 2);

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
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of yunzhi_gradeaod
-- ----------------------------
INSERT INTO `yunzhi_gradeaod` VALUES (1, '上课睡觉', -2, 2147483647, 2147483647, 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (2, '上课回答问题', 3, 1609402186, 1609402186, 1, 1);
INSERT INTO `yunzhi_gradeaod` VALUES (3, '上课回答问题', 3, 1609402218, 1609402218, 1, 1);
INSERT INTO `yunzhi_gradeaod` VALUES (4, '上课回答问题', 1, 1609403353, 1609403353, 3, 1);
INSERT INTO `yunzhi_gradeaod` VALUES (5, '上课玩手机', -1, 1609409982, 1609409982, 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (6, '上课玩手机', -1, 1609409985, 1609409985, 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (7, '上课玩手机', -2, 1609410016, 1609410016, 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (8, '上课玩手机', -2, 1609410026, 1609410026, 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (9, '上课玩手机', -2, 1609410067, 1609410067, 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (10, '上课说闲话', -2, 1609467089, 1609467089, 3, 0);
INSERT INTO `yunzhi_gradeaod` VALUES (11, '上课说闲话', 1, 1609472582, 1609472582, 3, 1);

-- ----------------------------
-- Table structure for yunzhi_seat
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat`;
CREATE TABLE `yunzhi_seat`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `x` int(11) NOT NULL DEFAULT 0,
  `y` int(11) NOT NULL DEFAULT 0,
  `is_seated` int(1) NOT NULL DEFAULT 0,
  `create_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  `is_seat` int(1) UNSIGNED NOT NULL DEFAULT 0,
  `classroom_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `student_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 241048 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = FIXED;

-- ----------------------------
-- Records of yunzhi_seat
-- ----------------------------
INSERT INTO `yunzhi_seat` VALUES (240896, 2, 2, 0, 1609497202, 1609767962, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240895, 2, 1, 0, 1609497202, 1609683618, 0, 35, 0);
INSERT INTO `yunzhi_seat` VALUES (240894, 2, 0, 0, 1609497202, 1609683618, 0, 35, 0);
INSERT INTO `yunzhi_seat` VALUES (240893, 1, 10, 0, 1609497202, 1609683618, 0, 35, 0);
INSERT INTO `yunzhi_seat` VALUES (240892, 1, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240891, 1, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240890, 1, 7, 0, 1609497202, 1609822816, 0, 35, 91);
INSERT INTO `yunzhi_seat` VALUES (240889, 1, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240888, 1, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240887, 1, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240886, 1, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240885, 1, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240884, 1, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240883, 1, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240882, 0, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240881, 0, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240880, 0, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240879, 0, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240878, 0, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240877, 0, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240876, 0, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240875, 0, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240874, 0, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240873, 0, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240872, 0, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240897, 2, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240898, 2, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240899, 2, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240900, 2, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240901, 2, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240902, 2, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240903, 2, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240904, 2, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240905, 3, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240906, 3, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240907, 3, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240908, 3, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240909, 3, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240910, 3, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240911, 3, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240912, 3, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240913, 3, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240914, 3, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240915, 3, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240916, 4, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240917, 4, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240918, 4, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240919, 4, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240920, 4, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240921, 4, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240922, 4, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240923, 4, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240924, 4, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240925, 4, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240926, 4, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240927, 5, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240928, 5, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240929, 5, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240930, 5, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240931, 5, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240932, 5, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240933, 5, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240934, 5, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240935, 5, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240936, 5, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240937, 5, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240938, 6, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240939, 6, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240940, 6, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240941, 6, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240942, 6, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240943, 6, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240944, 6, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240945, 6, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240946, 6, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240947, 6, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240948, 6, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240949, 7, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240950, 7, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240951, 7, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240952, 7, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240953, 7, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240954, 7, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240955, 7, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240956, 7, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240957, 7, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240958, 7, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240959, 7, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240960, 8, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240961, 8, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240962, 8, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240963, 8, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240964, 8, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240965, 8, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240966, 8, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240967, 8, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240968, 8, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240969, 8, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240970, 8, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240971, 9, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240972, 9, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240973, 9, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240974, 9, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240975, 9, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240976, 9, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240977, 9, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240978, 9, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240979, 9, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240980, 9, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240981, 9, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240982, 10, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240983, 10, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240984, 10, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240985, 10, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240986, 10, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240987, 10, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240988, 10, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240989, 10, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240990, 10, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240991, 10, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240992, 10, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240993, 11, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240994, 11, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240995, 11, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240996, 11, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240997, 11, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240998, 11, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (240999, 11, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241000, 11, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241001, 11, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241002, 11, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241003, 11, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241004, 12, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241005, 12, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241006, 12, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241007, 12, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241008, 12, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241009, 12, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241010, 12, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241011, 12, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241012, 12, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241013, 12, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241014, 12, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241015, 13, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241016, 13, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241017, 13, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241018, 13, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241019, 13, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241020, 13, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241021, 13, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241022, 13, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241023, 13, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241024, 13, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241025, 13, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241026, 14, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241027, 14, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241028, 14, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241029, 14, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241030, 14, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241031, 14, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241032, 14, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241033, 14, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241034, 14, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241035, 14, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241036, 14, 10, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241037, 15, 0, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241038, 15, 1, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241039, 15, 2, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241040, 15, 3, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241041, 15, 4, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241042, 15, 5, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241043, 15, 6, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241044, 15, 7, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241045, 15, 8, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241046, 15, 9, 0, 1609497202, 1609497202, 0, 35, NULL);
INSERT INTO `yunzhi_seat` VALUES (241047, 15, 10, 0, 1609497202, 1609497202, 1, 35, NULL);

-- ----------------------------
-- Table structure for yunzhi_seat_aisle
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat_aisle`;
CREATE TABLE `yunzhi_seat_aisle`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `seat_map_id` int(11) NOT NULL DEFAULT 0,
  `x` int(11) NOT NULL DEFAULT 0,
  `y` int(11) NOT NULL DEFAULT 0,
  `create_time` date NOT NULL DEFAULT '0000-00-00',
  `update_time` date NOT NULL DEFAULT '0000-00-00',
  `state` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 242897 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = FIXED;

-- ----------------------------
-- Records of yunzhi_seat_aisle
-- ----------------------------
INSERT INTO `yunzhi_seat_aisle` VALUES (241341, 97, 4, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241340, 97, 4, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241339, 97, 4, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241338, 97, 4, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241337, 97, 4, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241336, 97, 3, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241335, 97, 3, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241334, 97, 3, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241333, 97, 3, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241332, 97, 3, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241331, 97, 2, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241330, 97, 2, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241329, 97, 2, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241328, 97, 2, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241327, 97, 2, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241326, 97, 1, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241325, 97, 1, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241324, 97, 1, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241323, 97, 1, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241322, 97, 1, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241321, 97, 0, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241320, 97, 0, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241319, 97, 0, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241318, 97, 0, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241317, 97, 0, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241316, 96, 4, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241315, 96, 4, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241314, 96, 4, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241313, 96, 4, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241312, 96, 4, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241311, 96, 3, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241310, 96, 3, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241309, 96, 3, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241308, 96, 3, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241307, 96, 3, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241306, 96, 2, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241305, 96, 2, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241304, 96, 2, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241303, 96, 2, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241302, 96, 2, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241301, 96, 1, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241300, 96, 1, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241299, 96, 1, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241298, 96, 1, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241297, 96, 1, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241296, 96, 0, 4, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241295, 96, 0, 3, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241294, 96, 0, 2, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241293, 96, 0, 1, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241292, 96, 0, 0, '2016-09-16', '2016-09-16', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241201, 91, 8, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241200, 91, 8, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241199, 91, 8, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241198, 91, 8, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241197, 91, 8, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241196, 91, 8, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241195, 91, 8, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241194, 91, 8, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241193, 91, 8, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241192, 91, 8, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241191, 91, 7, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241190, 91, 7, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241189, 91, 7, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241188, 91, 7, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241187, 91, 7, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241186, 91, 7, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241185, 91, 7, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241184, 91, 7, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241183, 91, 7, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241182, 91, 7, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241181, 91, 6, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241180, 91, 6, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241179, 91, 6, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241178, 91, 6, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241177, 91, 6, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241176, 91, 6, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241175, 91, 6, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241174, 91, 6, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241173, 91, 6, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241172, 91, 6, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241171, 91, 5, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241170, 91, 5, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241169, 91, 5, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241168, 91, 5, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241167, 91, 5, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241166, 91, 5, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241165, 91, 5, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241164, 91, 5, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241163, 91, 5, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241162, 91, 5, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241161, 91, 4, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241160, 91, 4, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241159, 91, 4, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241158, 91, 4, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241157, 91, 4, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241156, 91, 4, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241155, 91, 4, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241154, 91, 4, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241153, 91, 4, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241152, 91, 4, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241151, 91, 3, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241150, 91, 3, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241149, 91, 3, 7, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (241148, 91, 3, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241147, 91, 3, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241146, 91, 3, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241145, 91, 3, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241144, 91, 3, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241143, 91, 3, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241142, 91, 3, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241141, 91, 2, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241140, 91, 2, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241139, 91, 2, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241138, 91, 2, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241137, 91, 2, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241136, 91, 2, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241135, 91, 2, 3, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (241134, 91, 2, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241133, 91, 2, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241132, 91, 2, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241131, 91, 1, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241130, 91, 1, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241129, 91, 1, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241128, 91, 1, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241127, 91, 1, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241126, 91, 1, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241125, 91, 1, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241124, 91, 1, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241123, 91, 1, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241122, 91, 1, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241121, 91, 0, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241120, 91, 0, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241119, 91, 0, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241118, 91, 0, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241117, 91, 0, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241116, 91, 0, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241115, 91, 0, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241114, 91, 0, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241113, 91, 0, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241112, 91, 0, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241342, 98, 0, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241343, 98, 0, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241344, 98, 0, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241345, 98, 0, 3, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (241346, 98, 0, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241347, 98, 0, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241348, 98, 0, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241349, 98, 0, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241350, 98, 0, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241351, 98, 0, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241352, 98, 0, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241353, 98, 1, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241354, 98, 1, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241355, 98, 1, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241356, 98, 1, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241357, 98, 1, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241358, 98, 1, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241359, 98, 1, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241360, 98, 1, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241361, 98, 1, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241362, 98, 1, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241363, 98, 1, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241364, 98, 2, 0, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (241365, 98, 2, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241366, 98, 2, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241367, 98, 2, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241368, 98, 2, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241369, 98, 2, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241370, 98, 2, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241371, 98, 2, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241372, 98, 2, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241373, 98, 2, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241374, 98, 2, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241375, 98, 3, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241376, 98, 3, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241377, 98, 3, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241378, 98, 3, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241379, 98, 3, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241380, 98, 3, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241381, 98, 3, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241382, 98, 3, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241383, 98, 3, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241384, 98, 3, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241385, 98, 3, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241386, 98, 4, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241387, 98, 4, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241388, 98, 4, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241389, 98, 4, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241390, 98, 4, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241391, 98, 4, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241392, 98, 4, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241393, 98, 4, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241394, 98, 4, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241395, 98, 4, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241396, 98, 4, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241397, 98, 5, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241398, 98, 5, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241399, 98, 5, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241400, 98, 5, 3, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (241401, 98, 5, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241402, 98, 5, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241403, 98, 5, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241404, 98, 5, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241405, 98, 5, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241406, 98, 5, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241407, 98, 5, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241408, 98, 6, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241409, 98, 6, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241410, 98, 6, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241411, 98, 6, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241412, 98, 6, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241413, 98, 6, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241414, 98, 6, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241415, 98, 6, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241416, 98, 6, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241417, 98, 6, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241418, 98, 6, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241419, 98, 7, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241420, 98, 7, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241421, 98, 7, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241422, 98, 7, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241423, 98, 7, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241424, 98, 7, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241425, 98, 7, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241426, 98, 7, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241427, 98, 7, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241428, 98, 7, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241429, 98, 7, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241430, 98, 8, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241431, 98, 8, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241432, 98, 8, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241433, 98, 8, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241434, 98, 8, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241435, 98, 8, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241436, 98, 8, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241437, 98, 8, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241438, 98, 8, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241439, 98, 8, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241440, 98, 8, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241441, 98, 9, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241442, 98, 9, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241443, 98, 9, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241444, 98, 9, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241445, 98, 9, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241446, 98, 9, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241447, 98, 9, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241448, 98, 9, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241449, 98, 9, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241450, 98, 9, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241451, 98, 9, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241452, 98, 10, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241453, 98, 10, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241454, 98, 10, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241455, 98, 10, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241456, 98, 10, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241457, 98, 10, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241458, 98, 10, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241459, 98, 10, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241460, 98, 10, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241461, 98, 10, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241462, 98, 10, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241463, 98, 11, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241464, 98, 11, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241465, 98, 11, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241466, 98, 11, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241467, 98, 11, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241468, 98, 11, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241469, 98, 11, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241470, 98, 11, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241471, 98, 11, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241472, 98, 11, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241473, 98, 11, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241474, 98, 12, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241475, 98, 12, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241476, 98, 12, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241477, 98, 12, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241478, 98, 12, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241479, 98, 12, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241480, 98, 12, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241481, 98, 12, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241482, 98, 12, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241483, 98, 12, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241484, 98, 12, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241485, 98, 13, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241486, 98, 13, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241487, 98, 13, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241488, 98, 13, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241489, 98, 13, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241490, 98, 13, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241491, 98, 13, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241492, 98, 13, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241493, 98, 13, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241494, 98, 13, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241495, 98, 13, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241496, 98, 14, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241497, 98, 14, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241498, 98, 14, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241499, 98, 14, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241500, 98, 14, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241501, 98, 14, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241502, 98, 14, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241503, 98, 14, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241504, 98, 14, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241505, 98, 14, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241506, 98, 14, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241507, 98, 15, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241508, 98, 15, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241509, 98, 15, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241510, 98, 15, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241511, 98, 15, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241512, 98, 15, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241513, 98, 15, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241514, 98, 15, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241515, 98, 15, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241516, 98, 15, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241517, 98, 15, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241518, 99, 0, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241519, 99, 0, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241520, 99, 0, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241521, 99, 0, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241522, 99, 0, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241523, 99, 0, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241524, 99, 0, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241525, 99, 0, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241526, 99, 0, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241527, 99, 0, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241528, 99, 0, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241529, 99, 1, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241530, 99, 1, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241531, 99, 1, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241532, 99, 1, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241533, 99, 1, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241534, 99, 1, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241535, 99, 1, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241536, 99, 1, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241537, 99, 1, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241538, 99, 1, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241539, 99, 1, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241540, 99, 2, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241541, 99, 2, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241542, 99, 2, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241543, 99, 2, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241544, 99, 2, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241545, 99, 2, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241546, 99, 2, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241547, 99, 2, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241548, 99, 2, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241549, 99, 2, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241550, 99, 2, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241551, 99, 3, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241552, 99, 3, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241553, 99, 3, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241554, 99, 3, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241555, 99, 3, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241556, 99, 3, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241557, 99, 3, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241558, 99, 3, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241559, 99, 3, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241560, 99, 3, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241561, 99, 3, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241562, 99, 4, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241563, 99, 4, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241564, 99, 4, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241565, 99, 4, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241566, 99, 4, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241567, 99, 4, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241568, 99, 4, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241569, 99, 4, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241570, 99, 4, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241571, 99, 4, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241572, 99, 4, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241573, 99, 5, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241574, 99, 5, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241575, 99, 5, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241576, 99, 5, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241577, 99, 5, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241578, 99, 5, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241579, 99, 5, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241580, 99, 5, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241581, 99, 5, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241582, 99, 5, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241583, 99, 5, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241584, 99, 6, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241585, 99, 6, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241586, 99, 6, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241587, 99, 6, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241588, 99, 6, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241589, 99, 6, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241590, 99, 6, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241591, 99, 6, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241592, 99, 6, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241593, 99, 6, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241594, 99, 6, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241595, 99, 7, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241596, 99, 7, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241597, 99, 7, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241598, 99, 7, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241599, 99, 7, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241600, 99, 7, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241601, 99, 7, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241602, 99, 7, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241603, 99, 7, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241604, 99, 7, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241605, 99, 7, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241606, 99, 8, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241607, 99, 8, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241608, 99, 8, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241609, 99, 8, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241610, 99, 8, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241611, 99, 8, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241612, 99, 8, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241613, 99, 8, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241614, 99, 8, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241615, 99, 8, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241616, 99, 8, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241617, 99, 9, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241618, 99, 9, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241619, 99, 9, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241620, 99, 9, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241621, 99, 9, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241622, 99, 9, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241623, 99, 9, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241624, 99, 9, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241625, 99, 9, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241626, 99, 9, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241627, 99, 9, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241628, 99, 10, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241629, 99, 10, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241630, 99, 10, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241631, 99, 10, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241632, 99, 10, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241633, 99, 10, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241634, 99, 10, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241635, 99, 10, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241636, 99, 10, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241637, 99, 10, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241638, 99, 10, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241639, 99, 11, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241640, 99, 11, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241641, 99, 11, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241642, 99, 11, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241643, 99, 11, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241644, 99, 11, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241645, 99, 11, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241646, 99, 11, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241647, 99, 11, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241648, 99, 11, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241649, 99, 11, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241650, 99, 12, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241651, 99, 12, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241652, 99, 12, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241653, 99, 12, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241654, 99, 12, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241655, 99, 12, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241656, 99, 12, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241657, 99, 12, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241658, 99, 12, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241659, 99, 12, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241660, 99, 12, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241661, 99, 13, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241662, 99, 13, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241663, 99, 13, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241664, 99, 13, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241665, 99, 13, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241666, 99, 13, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241667, 99, 13, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241668, 99, 13, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241669, 99, 13, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241670, 99, 13, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241671, 99, 13, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241672, 99, 14, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241673, 99, 14, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241674, 99, 14, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241675, 99, 14, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241676, 99, 14, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241677, 99, 14, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241678, 99, 14, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241679, 99, 14, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241680, 99, 14, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241681, 99, 14, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241682, 99, 14, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241683, 99, 15, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241684, 99, 15, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241685, 99, 15, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241686, 99, 15, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241687, 99, 15, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241688, 99, 15, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241689, 99, 15, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241690, 99, 15, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241691, 99, 15, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241692, 99, 15, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241693, 99, 15, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241694, 100, 0, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241695, 100, 0, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241696, 100, 0, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241697, 100, 0, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241698, 100, 0, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241699, 100, 0, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241700, 100, 0, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241701, 100, 0, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241702, 100, 0, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241703, 100, 0, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241704, 100, 0, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241705, 100, 1, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241706, 100, 1, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241707, 100, 1, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241708, 100, 1, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241709, 100, 1, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241710, 100, 1, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241711, 100, 1, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241712, 100, 1, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241713, 100, 1, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241714, 100, 1, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241715, 100, 1, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241716, 100, 2, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241717, 100, 2, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241718, 100, 2, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241719, 100, 2, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241720, 100, 2, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241721, 100, 2, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241722, 100, 2, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241723, 100, 2, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241724, 100, 2, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241725, 100, 2, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241726, 100, 2, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241727, 100, 3, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241728, 100, 3, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241729, 100, 3, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241730, 100, 3, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241731, 100, 3, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241732, 100, 3, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241733, 100, 3, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241734, 100, 3, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241735, 100, 3, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241736, 100, 3, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241737, 100, 3, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241738, 100, 4, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241739, 100, 4, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241740, 100, 4, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241741, 100, 4, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241742, 100, 4, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241743, 100, 4, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241744, 100, 4, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241745, 100, 4, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241746, 100, 4, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241747, 100, 4, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241748, 100, 4, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241749, 100, 5, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241750, 100, 5, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241751, 100, 5, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241752, 100, 5, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241753, 100, 5, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241754, 100, 5, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241755, 100, 5, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241756, 100, 5, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241757, 100, 5, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241758, 100, 5, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241759, 100, 5, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241760, 100, 6, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241761, 100, 6, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241762, 100, 6, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241763, 100, 6, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241764, 100, 6, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241765, 100, 6, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241766, 100, 6, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241767, 100, 6, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241768, 100, 6, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241769, 100, 6, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241770, 100, 6, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241771, 100, 7, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241772, 100, 7, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241773, 100, 7, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241774, 100, 7, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241775, 100, 7, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241776, 100, 7, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241777, 100, 7, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241778, 100, 7, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241779, 100, 7, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241780, 100, 7, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241781, 100, 7, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241782, 100, 8, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241783, 100, 8, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241784, 100, 8, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241785, 100, 8, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241786, 100, 8, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241787, 100, 8, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241788, 100, 8, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241789, 100, 8, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241790, 100, 8, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241791, 100, 8, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241792, 100, 8, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241793, 100, 9, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241794, 100, 9, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241795, 100, 9, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241796, 100, 9, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241797, 100, 9, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241798, 100, 9, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241799, 100, 9, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241800, 100, 9, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241801, 100, 9, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241802, 100, 9, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241803, 100, 9, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241804, 100, 10, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241805, 100, 10, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241806, 100, 10, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241807, 100, 10, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241808, 100, 10, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241809, 100, 10, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241810, 100, 10, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241811, 100, 10, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241812, 100, 10, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241813, 100, 10, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241814, 100, 10, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241815, 100, 11, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241816, 100, 11, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241817, 100, 11, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241818, 100, 11, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241819, 100, 11, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241820, 100, 11, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241821, 100, 11, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241822, 100, 11, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241823, 100, 11, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241824, 100, 11, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241825, 100, 11, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241826, 100, 12, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241827, 100, 12, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241828, 100, 12, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241829, 100, 12, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241830, 100, 12, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241831, 100, 12, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241832, 100, 12, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241833, 100, 12, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241834, 100, 12, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241835, 100, 12, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241836, 100, 12, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241837, 100, 13, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241838, 100, 13, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241839, 100, 13, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241840, 100, 13, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241841, 100, 13, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241842, 100, 13, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241843, 100, 13, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241844, 100, 13, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241845, 100, 13, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241846, 100, 13, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241847, 100, 13, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241848, 100, 14, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241849, 100, 14, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241850, 100, 14, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241851, 100, 14, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241852, 100, 14, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241853, 100, 14, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241854, 100, 14, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241855, 100, 14, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241856, 100, 14, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241857, 100, 14, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241858, 100, 14, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241859, 100, 15, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241860, 100, 15, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241861, 100, 15, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241862, 100, 15, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241863, 100, 15, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241864, 100, 15, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241865, 100, 15, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241866, 100, 15, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241867, 100, 15, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241868, 100, 15, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241869, 100, 15, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241870, 101, 0, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241871, 101, 0, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241872, 101, 0, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241873, 101, 0, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241874, 101, 0, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241875, 101, 0, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241876, 101, 0, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241877, 101, 0, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241878, 101, 0, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241879, 101, 0, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241880, 101, 0, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241881, 101, 1, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241882, 101, 1, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241883, 101, 1, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241884, 101, 1, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241885, 101, 1, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241886, 101, 1, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241887, 101, 1, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241888, 101, 1, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241889, 101, 1, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241890, 101, 1, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241891, 101, 1, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241892, 101, 2, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241893, 101, 2, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241894, 101, 2, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241895, 101, 2, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241896, 101, 2, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241897, 101, 2, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241898, 101, 2, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241899, 101, 2, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241900, 101, 2, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241901, 101, 2, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241902, 101, 2, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241903, 101, 3, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241904, 101, 3, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241905, 101, 3, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241906, 101, 3, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241907, 101, 3, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241908, 101, 3, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241909, 101, 3, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241910, 101, 3, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241911, 101, 3, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241912, 101, 3, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241913, 101, 3, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241914, 101, 4, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241915, 101, 4, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241916, 101, 4, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241917, 101, 4, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241918, 101, 4, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241919, 101, 4, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241920, 101, 4, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241921, 101, 4, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241922, 101, 4, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241923, 101, 4, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241924, 101, 4, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241925, 101, 5, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241926, 101, 5, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241927, 101, 5, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241928, 101, 5, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241929, 101, 5, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241930, 101, 5, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241931, 101, 5, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241932, 101, 5, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241933, 101, 5, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241934, 101, 5, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241935, 101, 5, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241936, 101, 6, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241937, 101, 6, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241938, 101, 6, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241939, 101, 6, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241940, 101, 6, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241941, 101, 6, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241942, 101, 6, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241943, 101, 6, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241944, 101, 6, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241945, 101, 6, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241946, 101, 6, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241947, 101, 7, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241948, 101, 7, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241949, 101, 7, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241950, 101, 7, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241951, 101, 7, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241952, 101, 7, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241953, 101, 7, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241954, 101, 7, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241955, 101, 7, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241956, 101, 7, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241957, 101, 7, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241958, 101, 8, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241959, 101, 8, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241960, 101, 8, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241961, 101, 8, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241962, 101, 8, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241963, 101, 8, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241964, 101, 8, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241965, 101, 8, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241966, 101, 8, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241967, 101, 8, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241968, 101, 8, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241969, 101, 9, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241970, 101, 9, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241971, 101, 9, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241972, 101, 9, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241973, 101, 9, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241974, 101, 9, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241975, 101, 9, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241976, 101, 9, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241977, 101, 9, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241978, 101, 9, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241979, 101, 9, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241980, 101, 10, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241981, 101, 10, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241982, 101, 10, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241983, 101, 10, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241984, 101, 10, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241985, 101, 10, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241986, 101, 10, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241987, 101, 10, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241988, 101, 10, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241989, 101, 10, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241990, 101, 10, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241991, 101, 11, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241992, 101, 11, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241993, 101, 11, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241994, 101, 11, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241995, 101, 11, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241996, 101, 11, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241997, 101, 11, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241998, 101, 11, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (241999, 101, 11, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242000, 101, 11, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242001, 101, 11, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242002, 101, 12, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242003, 101, 12, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242004, 101, 12, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242005, 101, 12, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242006, 101, 12, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242007, 101, 12, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242008, 101, 12, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242009, 101, 12, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242010, 101, 12, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242011, 101, 12, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242012, 101, 12, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242013, 101, 13, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242014, 101, 13, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242015, 101, 13, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242016, 101, 13, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242017, 101, 13, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242018, 101, 13, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242019, 101, 13, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242020, 101, 13, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242021, 101, 13, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242022, 101, 13, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242023, 101, 13, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242024, 101, 14, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242025, 101, 14, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242026, 101, 14, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242027, 101, 14, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242028, 101, 14, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242029, 101, 14, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242030, 101, 14, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242031, 101, 14, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242032, 101, 14, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242033, 101, 14, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242034, 101, 14, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242035, 101, 15, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242036, 101, 15, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242037, 101, 15, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242038, 101, 15, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242039, 101, 15, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242040, 101, 15, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242041, 101, 15, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242042, 101, 15, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242043, 101, 15, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242044, 101, 15, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242045, 101, 15, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242046, 102, 0, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242047, 102, 0, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242048, 102, 0, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242049, 102, 0, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242050, 102, 0, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242051, 102, 0, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242052, 102, 0, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242053, 102, 0, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242054, 102, 0, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242055, 102, 0, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242056, 102, 0, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242057, 102, 1, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242058, 102, 1, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242059, 102, 1, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242060, 102, 1, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242061, 102, 1, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242062, 102, 1, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242063, 102, 1, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242064, 102, 1, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242065, 102, 1, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242066, 102, 1, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242067, 102, 1, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242068, 102, 2, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242069, 102, 2, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242070, 102, 2, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242071, 102, 2, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242072, 102, 2, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242073, 102, 2, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242074, 102, 2, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242075, 102, 2, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242076, 102, 2, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242077, 102, 2, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242078, 102, 2, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242079, 102, 3, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242080, 102, 3, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242081, 102, 3, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242082, 102, 3, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242083, 102, 3, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242084, 102, 3, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242085, 102, 3, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242086, 102, 3, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242087, 102, 3, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242088, 102, 3, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242089, 102, 3, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242090, 102, 4, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242091, 102, 4, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242092, 102, 4, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242093, 102, 4, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242094, 102, 4, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242095, 102, 4, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242096, 102, 4, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242097, 102, 4, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242098, 102, 4, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242099, 102, 4, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242100, 102, 4, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242101, 102, 5, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242102, 102, 5, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242103, 102, 5, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242104, 102, 5, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242105, 102, 5, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242106, 102, 5, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242107, 102, 5, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242108, 102, 5, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242109, 102, 5, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242110, 102, 5, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242111, 102, 5, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242112, 102, 6, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242113, 102, 6, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242114, 102, 6, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242115, 102, 6, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242116, 102, 6, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242117, 102, 6, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242118, 102, 6, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242119, 102, 6, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242120, 102, 6, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242121, 102, 6, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242122, 102, 6, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242123, 102, 7, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242124, 102, 7, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242125, 102, 7, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242126, 102, 7, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242127, 102, 7, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242128, 102, 7, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242129, 102, 7, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242130, 102, 7, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242131, 102, 7, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242132, 102, 7, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242133, 102, 7, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242134, 102, 8, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242135, 102, 8, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242136, 102, 8, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242137, 102, 8, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242138, 102, 8, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242139, 102, 8, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242140, 102, 8, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242141, 102, 8, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242142, 102, 8, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242143, 102, 8, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242144, 102, 8, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242145, 102, 9, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242146, 102, 9, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242147, 102, 9, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242148, 102, 9, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242149, 102, 9, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242150, 102, 9, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242151, 102, 9, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242152, 102, 9, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242153, 102, 9, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242154, 102, 9, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242155, 102, 9, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242156, 102, 10, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242157, 102, 10, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242158, 102, 10, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242159, 102, 10, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242160, 102, 10, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242161, 102, 10, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242162, 102, 10, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242163, 102, 10, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242164, 102, 10, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242165, 102, 10, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242166, 102, 10, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242167, 102, 11, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242168, 102, 11, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242169, 102, 11, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242170, 102, 11, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242171, 102, 11, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242172, 102, 11, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242173, 102, 11, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242174, 102, 11, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242175, 102, 11, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242176, 102, 11, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242177, 102, 11, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242178, 102, 12, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242179, 102, 12, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242180, 102, 12, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242181, 102, 12, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242182, 102, 12, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242183, 102, 12, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242184, 102, 12, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242185, 102, 12, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242186, 102, 12, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242187, 102, 12, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242188, 102, 12, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242189, 102, 13, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242190, 102, 13, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242191, 102, 13, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242192, 102, 13, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242193, 102, 13, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242194, 102, 13, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242195, 102, 13, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242196, 102, 13, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242197, 102, 13, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242198, 102, 13, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242199, 102, 13, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242200, 102, 14, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242201, 102, 14, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242202, 102, 14, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242203, 102, 14, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242204, 102, 14, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242205, 102, 14, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242206, 102, 14, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242207, 102, 14, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242208, 102, 14, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242209, 102, 14, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242210, 102, 14, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242211, 102, 15, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242212, 102, 15, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242213, 102, 15, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242214, 102, 15, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242215, 102, 15, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242216, 102, 15, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242217, 102, 15, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242218, 102, 15, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242219, 102, 15, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242220, 102, 15, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242221, 102, 15, 10, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (242222, 103, 0, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242223, 103, 0, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242224, 103, 0, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242225, 103, 0, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242226, 103, 0, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242227, 103, 0, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242228, 103, 0, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242229, 103, 0, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242230, 103, 0, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242231, 103, 0, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242232, 103, 0, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242233, 103, 0, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242234, 103, 1, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242235, 103, 1, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242236, 103, 1, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242237, 103, 1, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242238, 103, 1, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242239, 103, 1, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242240, 103, 1, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242241, 103, 1, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242242, 103, 1, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242243, 103, 1, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242244, 103, 1, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242245, 103, 1, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242246, 103, 2, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242247, 103, 2, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242248, 103, 2, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242249, 103, 2, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242250, 103, 2, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242251, 103, 2, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242252, 103, 2, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242253, 103, 2, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242254, 103, 2, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242255, 103, 2, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242256, 103, 2, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242257, 103, 2, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242258, 103, 3, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242259, 103, 3, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242260, 103, 3, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242261, 103, 3, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242262, 103, 3, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242263, 103, 3, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242264, 103, 3, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242265, 103, 3, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242266, 103, 3, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242267, 103, 3, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242268, 103, 3, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242269, 103, 3, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242270, 103, 4, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242271, 103, 4, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242272, 103, 4, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242273, 103, 4, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242274, 103, 4, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242275, 103, 4, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242276, 103, 4, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242277, 103, 4, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242278, 103, 4, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242279, 103, 4, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242280, 103, 4, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242281, 103, 4, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242282, 103, 5, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242283, 103, 5, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242284, 103, 5, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242285, 103, 5, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242286, 103, 5, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242287, 103, 5, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242288, 103, 5, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242289, 103, 5, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242290, 103, 5, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242291, 103, 5, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242292, 103, 5, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242293, 103, 5, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242294, 103, 6, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242295, 103, 6, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242296, 103, 6, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242297, 103, 6, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242298, 103, 6, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242299, 103, 6, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242300, 103, 6, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242301, 103, 6, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242302, 103, 6, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242303, 103, 6, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242304, 103, 6, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242305, 103, 6, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242306, 103, 7, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242307, 103, 7, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242308, 103, 7, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242309, 103, 7, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242310, 103, 7, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242311, 103, 7, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242312, 103, 7, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242313, 103, 7, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242314, 103, 7, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242315, 103, 7, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242316, 103, 7, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242317, 103, 7, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242318, 103, 8, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242319, 103, 8, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242320, 103, 8, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242321, 103, 8, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242322, 103, 8, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242323, 103, 8, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242324, 103, 8, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242325, 103, 8, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242326, 103, 8, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242327, 103, 8, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242328, 103, 8, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242329, 103, 8, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242330, 103, 9, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242331, 103, 9, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242332, 103, 9, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242333, 103, 9, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242334, 103, 9, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242335, 103, 9, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242336, 103, 9, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242337, 103, 9, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242338, 103, 9, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242339, 103, 9, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242340, 103, 9, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242341, 103, 9, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242342, 103, 10, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242343, 103, 10, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242344, 103, 10, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242345, 103, 10, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242346, 103, 10, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242347, 103, 10, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242348, 103, 10, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242349, 103, 10, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242350, 103, 10, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242351, 103, 10, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242352, 103, 10, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242353, 103, 10, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242354, 103, 11, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242355, 103, 11, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242356, 103, 11, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242357, 103, 11, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242358, 103, 11, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242359, 103, 11, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242360, 103, 11, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242361, 103, 11, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242362, 103, 11, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242363, 103, 11, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242364, 103, 11, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242365, 103, 11, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242366, 103, 12, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242367, 103, 12, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242368, 103, 12, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242369, 103, 12, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242370, 103, 12, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242371, 103, 12, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242372, 103, 12, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242373, 103, 12, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242374, 103, 12, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242375, 103, 12, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242376, 103, 12, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242377, 103, 12, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242378, 103, 13, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242379, 103, 13, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242380, 103, 13, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242381, 103, 13, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242382, 103, 13, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242383, 103, 13, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242384, 103, 13, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242385, 103, 13, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242386, 103, 13, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242387, 103, 13, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242388, 103, 13, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242389, 103, 13, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242390, 103, 14, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242391, 103, 14, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242392, 103, 14, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242393, 103, 14, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242394, 103, 14, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242395, 103, 14, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242396, 103, 14, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242397, 103, 14, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242398, 103, 14, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242399, 103, 14, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242400, 103, 14, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242401, 103, 14, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242402, 103, 15, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242403, 103, 15, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242404, 103, 15, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242405, 103, 15, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242406, 103, 15, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242407, 103, 15, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242408, 103, 15, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242409, 103, 15, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242410, 103, 15, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242411, 103, 15, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242412, 103, 15, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242413, 103, 15, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242414, 103, 16, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242415, 103, 16, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242416, 103, 16, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242417, 103, 16, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242418, 103, 16, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242419, 103, 16, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242420, 103, 16, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242421, 103, 16, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242422, 103, 16, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242423, 103, 16, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242424, 103, 16, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242425, 103, 16, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242426, 103, 17, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242427, 103, 17, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242428, 103, 17, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242429, 103, 17, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242430, 103, 17, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242431, 103, 17, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242432, 103, 17, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242433, 103, 17, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242434, 103, 17, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242435, 103, 17, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242436, 103, 17, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242437, 103, 17, 11, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (242438, 104, 0, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242439, 104, 0, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242440, 104, 0, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242441, 104, 0, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242442, 104, 0, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242443, 104, 0, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242444, 104, 0, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242445, 104, 0, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242446, 104, 0, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242447, 104, 0, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242448, 104, 0, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242449, 104, 0, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242450, 104, 0, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242451, 104, 0, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242452, 104, 0, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242453, 104, 0, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242454, 104, 0, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242455, 104, 0, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242456, 104, 1, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242457, 104, 1, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242458, 104, 1, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242459, 104, 1, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242460, 104, 1, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242461, 104, 1, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242462, 104, 1, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242463, 104, 1, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242464, 104, 1, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242465, 104, 1, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242466, 104, 1, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242467, 104, 1, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242468, 104, 1, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242469, 104, 1, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242470, 104, 1, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242471, 104, 1, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242472, 104, 1, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242473, 104, 1, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242474, 104, 2, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242475, 104, 2, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242476, 104, 2, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242477, 104, 2, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242478, 104, 2, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242479, 104, 2, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242480, 104, 2, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242481, 104, 2, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242482, 104, 2, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242483, 104, 2, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242484, 104, 2, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242485, 104, 2, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242486, 104, 2, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242487, 104, 2, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242488, 104, 2, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242489, 104, 2, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242490, 104, 2, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242491, 104, 2, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242492, 104, 3, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242493, 104, 3, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242494, 104, 3, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242495, 104, 3, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242496, 104, 3, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242497, 104, 3, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242498, 104, 3, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242499, 104, 3, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242500, 104, 3, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242501, 104, 3, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242502, 104, 3, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242503, 104, 3, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242504, 104, 3, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242505, 104, 3, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242506, 104, 3, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242507, 104, 3, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242508, 104, 3, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242509, 104, 3, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242510, 104, 4, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242511, 104, 4, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242512, 104, 4, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242513, 104, 4, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242514, 104, 4, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242515, 104, 4, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242516, 104, 4, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242517, 104, 4, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242518, 104, 4, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242519, 104, 4, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242520, 104, 4, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242521, 104, 4, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242522, 104, 4, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242523, 104, 4, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242524, 104, 4, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242525, 104, 4, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242526, 104, 4, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242527, 104, 4, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242528, 104, 5, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242529, 104, 5, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242530, 104, 5, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242531, 104, 5, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242532, 104, 5, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242533, 104, 5, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242534, 104, 5, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242535, 104, 5, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242536, 104, 5, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242537, 104, 5, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242538, 104, 5, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242539, 104, 5, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242540, 104, 5, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242541, 104, 5, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242542, 104, 5, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242543, 104, 5, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242544, 104, 5, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242545, 104, 5, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242546, 104, 6, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242547, 104, 6, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242548, 104, 6, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242549, 104, 6, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242550, 104, 6, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242551, 104, 6, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242552, 104, 6, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242553, 104, 6, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242554, 104, 6, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242555, 104, 6, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242556, 104, 6, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242557, 104, 6, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242558, 104, 6, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242559, 104, 6, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242560, 104, 6, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242561, 104, 6, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242562, 104, 6, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242563, 104, 6, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242564, 104, 7, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242565, 104, 7, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242566, 104, 7, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242567, 104, 7, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242568, 104, 7, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242569, 104, 7, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242570, 104, 7, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242571, 104, 7, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242572, 104, 7, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242573, 104, 7, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242574, 104, 7, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242575, 104, 7, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242576, 104, 7, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242577, 104, 7, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242578, 104, 7, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242579, 104, 7, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242580, 104, 7, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242581, 104, 7, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242582, 104, 8, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242583, 104, 8, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242584, 104, 8, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242585, 104, 8, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242586, 104, 8, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242587, 104, 8, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242588, 104, 8, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242589, 104, 8, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242590, 104, 8, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242591, 104, 8, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242592, 104, 8, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242593, 104, 8, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242594, 104, 8, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242595, 104, 8, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242596, 104, 8, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242597, 104, 8, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242598, 104, 8, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242599, 104, 8, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242600, 104, 9, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242601, 104, 9, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242602, 104, 9, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242603, 104, 9, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242604, 104, 9, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242605, 104, 9, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242606, 104, 9, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242607, 104, 9, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242608, 104, 9, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242609, 104, 9, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242610, 104, 9, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242611, 104, 9, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242612, 104, 9, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242613, 104, 9, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242614, 104, 9, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242615, 104, 9, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242616, 104, 9, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242617, 104, 9, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242618, 104, 10, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242619, 104, 10, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242620, 104, 10, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242621, 104, 10, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242622, 104, 10, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242623, 104, 10, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242624, 104, 10, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242625, 104, 10, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242626, 104, 10, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242627, 104, 10, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242628, 104, 10, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242629, 104, 10, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242630, 104, 10, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242631, 104, 10, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242632, 104, 10, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242633, 104, 10, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242634, 104, 10, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242635, 104, 10, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242636, 104, 11, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242637, 104, 11, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242638, 104, 11, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242639, 104, 11, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242640, 104, 11, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242641, 104, 11, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242642, 104, 11, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242643, 104, 11, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242644, 104, 11, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242645, 104, 11, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242646, 104, 11, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242647, 104, 11, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242648, 104, 11, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242649, 104, 11, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242650, 104, 11, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242651, 104, 11, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242652, 104, 11, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242653, 104, 11, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242654, 104, 12, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242655, 104, 12, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242656, 104, 12, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242657, 104, 12, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242658, 104, 12, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242659, 104, 12, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242660, 104, 12, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242661, 104, 12, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242662, 104, 12, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242663, 104, 12, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242664, 104, 12, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242665, 104, 12, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242666, 104, 12, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242667, 104, 12, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242668, 104, 12, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242669, 104, 12, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242670, 104, 12, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242671, 104, 12, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242672, 104, 13, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242673, 104, 13, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242674, 104, 13, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242675, 104, 13, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242676, 104, 13, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242677, 104, 13, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242678, 104, 13, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242679, 104, 13, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242680, 104, 13, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242681, 104, 13, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242682, 104, 13, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242683, 104, 13, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242684, 104, 13, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242685, 104, 13, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242686, 104, 13, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242687, 104, 13, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242688, 104, 13, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242689, 104, 13, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242690, 104, 14, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242691, 104, 14, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242692, 104, 14, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242693, 104, 14, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242694, 104, 14, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242695, 104, 14, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242696, 104, 14, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242697, 104, 14, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242698, 104, 14, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242699, 104, 14, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242700, 104, 14, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242701, 104, 14, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242702, 104, 14, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242703, 104, 14, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242704, 104, 14, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242705, 104, 14, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242706, 104, 14, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242707, 104, 14, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242708, 104, 15, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242709, 104, 15, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242710, 104, 15, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242711, 104, 15, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242712, 104, 15, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242713, 104, 15, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242714, 104, 15, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242715, 104, 15, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242716, 104, 15, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242717, 104, 15, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242718, 104, 15, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242719, 104, 15, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242720, 104, 15, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242721, 104, 15, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242722, 104, 15, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242723, 104, 15, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242724, 104, 15, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242725, 104, 15, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242726, 104, 16, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242727, 104, 16, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242728, 104, 16, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242729, 104, 16, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242730, 104, 16, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242731, 104, 16, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242732, 104, 16, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242733, 104, 16, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242734, 104, 16, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242735, 104, 16, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242736, 104, 16, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242737, 104, 16, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242738, 104, 16, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242739, 104, 16, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242740, 104, 16, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242741, 104, 16, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242742, 104, 16, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242743, 104, 16, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242744, 104, 17, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242745, 104, 17, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242746, 104, 17, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242747, 104, 17, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242748, 104, 17, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242749, 104, 17, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242750, 104, 17, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242751, 104, 17, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242752, 104, 17, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242753, 104, 17, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242754, 104, 17, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242755, 104, 17, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242756, 104, 17, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242757, 104, 17, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242758, 104, 17, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242759, 104, 17, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242760, 104, 17, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242761, 104, 17, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242762, 104, 18, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242763, 104, 18, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242764, 104, 18, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242765, 104, 18, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242766, 104, 18, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242767, 104, 18, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242768, 104, 18, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242769, 104, 18, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242770, 104, 18, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242771, 104, 18, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242772, 104, 18, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242773, 104, 18, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242774, 104, 18, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242775, 104, 18, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242776, 104, 18, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242777, 104, 18, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242778, 104, 18, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242779, 104, 18, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242780, 104, 19, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242781, 104, 19, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242782, 104, 19, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242783, 104, 19, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242784, 104, 19, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242785, 104, 19, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242786, 104, 19, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242787, 104, 19, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242788, 104, 19, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242789, 104, 19, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242790, 104, 19, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242791, 104, 19, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242792, 104, 19, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242793, 104, 19, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242794, 104, 19, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242795, 104, 19, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242796, 104, 19, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242797, 104, 19, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242798, 104, 20, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242799, 104, 20, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242800, 104, 20, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242801, 104, 20, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242802, 104, 20, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242803, 104, 20, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242804, 104, 20, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242805, 104, 20, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242806, 104, 20, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242807, 104, 20, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242808, 104, 20, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242809, 104, 20, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242810, 104, 20, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242811, 104, 20, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242812, 104, 20, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242813, 104, 20, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242814, 104, 20, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242815, 104, 20, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242816, 104, 21, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242817, 104, 21, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242818, 104, 21, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242819, 104, 21, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242820, 104, 21, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242821, 104, 21, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242822, 104, 21, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242823, 104, 21, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242824, 104, 21, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242825, 104, 21, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242826, 104, 21, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242827, 104, 21, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242828, 104, 21, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242829, 104, 21, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242830, 104, 21, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242831, 104, 21, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242832, 104, 21, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242833, 104, 21, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242834, 104, 22, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242835, 104, 22, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242836, 104, 22, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242837, 104, 22, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242838, 104, 22, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242839, 104, 22, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242840, 104, 22, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242841, 104, 22, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242842, 104, 22, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242843, 104, 22, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242844, 104, 22, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242845, 104, 22, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242846, 104, 22, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242847, 104, 22, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242848, 104, 22, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242849, 104, 22, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242850, 104, 22, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242851, 104, 22, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242852, 104, 23, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242853, 104, 23, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242854, 104, 23, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242855, 104, 23, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242856, 104, 23, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242857, 104, 23, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242858, 104, 23, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242859, 104, 23, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242860, 104, 23, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242861, 104, 23, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242862, 104, 23, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242863, 104, 23, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242864, 104, 23, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242865, 104, 23, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242866, 104, 23, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242867, 104, 23, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242868, 104, 23, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242869, 104, 23, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242870, 104, 24, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242871, 104, 24, 1, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242872, 104, 24, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242873, 104, 24, 3, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242874, 104, 24, 4, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242875, 104, 24, 5, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242876, 104, 24, 6, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242877, 104, 24, 7, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242878, 104, 24, 8, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242879, 104, 24, 9, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242880, 104, 24, 10, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242881, 104, 24, 11, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242882, 104, 24, 12, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242883, 104, 24, 13, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242884, 104, 24, 14, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242885, 104, 24, 15, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242886, 104, 24, 16, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242887, 104, 24, 17, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242888, 105, 0, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242889, 105, 0, 1, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (242890, 105, 0, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242891, 105, 1, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242892, 105, 1, 1, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (242893, 105, 1, 2, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242894, 105, 2, 0, '0000-00-00', '0000-00-00', 0);
INSERT INTO `yunzhi_seat_aisle` VALUES (242895, 105, 2, 1, '0000-00-00', '0000-00-00', 1);
INSERT INTO `yunzhi_seat_aisle` VALUES (242896, 105, 2, 2, '0000-00-00', '0000-00-00', 0);

-- ----------------------------
-- Table structure for yunzhi_seat_map
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_seat_map`;
CREATE TABLE `yunzhi_seat_map`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  `x_map` int(2) UNSIGNED NOT NULL,
  `y_map` int(2) UNSIGNED NOT NULL,
  `is_last` tinyint(1) UNSIGNED NULL DEFAULT 1,
  `is_first` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 106 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = FIXED;

-- ----------------------------
-- Records of yunzhi_seat_map
-- ----------------------------
INSERT INTO `yunzhi_seat_map` VALUES (102, 1609491836, 1609496178, 16, 11, 0, 1, '模板95');
INSERT INTO `yunzhi_seat_map` VALUES (103, 1609495418, 1609496178, 18, 12, 0, 0, '模板99');
INSERT INTO `yunzhi_seat_map` VALUES (105, 1609496178, 1609496178, 3, 3, 0, 0, '3');
INSERT INTO `yunzhi_seat_map` VALUES (104, 1609495582, 1609496178, 25, 18, 1, 0, '25');

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
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 98 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yunzhi_student
-- ----------------------------
INSERT INTO `yunzhi_student` VALUES (89, '孟鑫燕', '195717', 0, '1580380768@qq.com', 1608599879, 1608599879, '');
INSERT INTO `yunzhi_student` VALUES (90, '孟鑫燕', '195717', 1, '1580380768@qq.com', 1608600206, 1608600206, NULL);
INSERT INTO `yunzhi_student` VALUES (91, '郝泽龙', '195717', 0, '1580380768@qq.com', 1608601602, 1609819753, NULL);
INSERT INTO `yunzhi_student` VALUES (92, '郝泽龙', '195717', 0, '1580380768@qq.com', 1608601653, 1609819753, NULL);
INSERT INTO `yunzhi_student` VALUES (93, '孟鑫燕', '195717', 0, '1580380768@qq.com', 1608601662, 1608906915, NULL);
INSERT INTO `yunzhi_student` VALUES (94, '赵雯丽', '195717', 1, 'zhaoliu@yunzhi.club', 1608601672, 1608601672, NULL);
INSERT INTO `yunzhi_student` VALUES (87, '郝泽龙', '195717', 0, '1580380768@qq.com', 1605946593, 1609819753, NULL);
INSERT INTO `yunzhi_student` VALUES (88, '赵雯丽', '195713', 0, '1580380768@qq.com', 1605946692, 1605946692, NULL);
INSERT INTO `yunzhi_student` VALUES (86, '段声望', '195717', 1, 'zhaoliu@yunzhi.club', 1605946069, 1608518168, NULL);
INSERT INTO `yunzhi_student` VALUES (84, '郝泽龙', '195717', 0, '1580380768@qq.com', 1605793317, 1609819753, NULL);
INSERT INTO `yunzhi_student` VALUES (85, '许一普', '195759', 0, '15383011312@qq.com', 1605940325, 1605940325, NULL);
INSERT INTO `yunzhi_student` VALUES (95, '郝泽龙', '195717', 0, '1580380768@qq.com', 1609144689, 1609819753, NULL);
INSERT INTO `yunzhi_student` VALUES (96, '张文达', '185717', 1, '1580380768@qq.com', 1609144703, 1609480262, NULL);
INSERT INTO `yunzhi_student` VALUES (97, '赵凯强', '195253', 0, '1580380768@qq.com', 1609144720, 1609144720, NULL);

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
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yunzhi_teacher
-- ----------------------------
INSERT INTO `yunzhi_teacher` VALUES (2, '张老师', '5f383784a8ce262fa222357d503768412ee75518', 'lisi', 1594344872, 1594775272, 11);
INSERT INTO `yunzhi_teacher` VALUES (1, '李老师', '5f383784a8ce262fa222357d503768412ee75518', 'zhangsan', 0, 0, 112);
INSERT INTO `yunzhi_teacher` VALUES (4, '王老师', '5f383784a8ce262fa222357d503768412ee75518', 'wangwu', 1594428961, 1594690704, 113);
INSERT INTO `yunzhi_teacher` VALUES (5, '赵老师', '5f383784a8ce262fa222357d503768412ee75518', 'haolaoshi', 1594632937, 1594687962, 114);
INSERT INTO `yunzhi_teacher` VALUES (6, '何老师', '5f383784a8ce262fa222357d503768412ee75518', 'ligang', 1594642366, 1594643065, 115);
INSERT INTO `yunzhi_teacher` VALUES (8, '陈老师', '5f383784a8ce262fa222357d503768412ee75518', '阿斯顿', 1594696474, 1594696474, 116);
INSERT INTO `yunzhi_teacher` VALUES (19, '郝老师', '5f383784a8ce262fa222357d503768412ee75518', 'haozelong', 1594950661, 1594950661, 117);
INSERT INTO `yunzhi_teacher` VALUES (20, '管理员', '5f383784a8ce262fa222357d503768412ee75518', 'admin', 0, 0, 118);

-- ----------------------------
-- Table structure for yunzhi_term
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_term`;
CREATE TABLE `yunzhi_term`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '学期名',
  `ptime` int(11) NOT NULL COMMENT '起始时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `ftime` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 78682 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yunzhi_term
-- ----------------------------
INSERT INTO `yunzhi_term` VALUES (3, '2017春', 0, 0, 2021, 0, 0);
INSERT INTO `yunzhi_term` VALUES (7, '2020秋', 20201017, 0, 2021, 20201106, 0);
INSERT INTO `yunzhi_term` VALUES (6, '2019春', 20201107, 0, 2021, 0, 1);
INSERT INTO `yunzhi_term` VALUES (5, '2018秋', 0, 0, 2021, 0, 0);
INSERT INTO `yunzhi_term` VALUES (2, '2016秋', 0, 0, 2021, 0, 0);
INSERT INTO `yunzhi_term` VALUES (78681, '2017秋', 0, 0, 2021, 0, 0);
INSERT INTO `yunzhi_term` VALUES (1, '2016春', 0, 0, 2021, 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
