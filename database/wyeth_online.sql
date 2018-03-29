-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: rds1-read.mysql.rds.aliyuncs.com
-- Generation Time: 2017-09-20 19:15:51
-- 服务器版本： 5.6.16-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wyeth_online`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `fullname` varchar(100) NOT NULL DEFAULT '',
  `area` varchar(30) NOT NULL,
  `user_type` tinyint(4) NOT NULL,
  `cids` varchar(200) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `advertise`
--

CREATE TABLE `advertise` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '广告的表示',
  `type` int(11) NOT NULL COMMENT '0:广告 1:品牌课 2:活动',
  `brand_id` int(11) NOT NULL COMMENT '品牌ID',
  `position` int(11) NOT NULL COMMENT '高 0：200，1：272，2：360',
  `link` text NOT NULL COMMENT '链接',
  `img` varchar(255) NOT NULL COMMENT '图片',
  `display` int(11) NOT NULL COMMENT '是否有效',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `app_configs`
--

CREATE TABLE `app_configs` (
  `id` int(10) UNSIGNED NOT NULL,
  `module` varchar(100) NOT NULL DEFAULT '',
  `key` varchar(50) NOT NULL DEFAULT '',
  `data` longtext NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '10',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `area_city`
--

CREATE TABLE `area_city` (
  `id` int(10) UNSIGNED NOT NULL,
  `area` varchar(30) NOT NULL,
  `city` varchar(40) NOT NULL,
  `img` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `brand`
--

CREATE TABLE `brand` (
  `id` int(16) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `course`
--

CREATE TABLE `course` (
  `id` int(11) UNSIGNED NOT NULL,
  `cid` smallint(6) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `number` int(11) NOT NULL DEFAULT '0',
  `start_day` date NOT NULL COMMENT '开始日期',
  `start_time` time NOT NULL COMMENT '开始时间',
  `play_status` int(11) NOT NULL DEFAULT '0' COMMENT '0 播放音频 1 问答环节',
  `end_time` time NOT NULL COMMENT '结束时间',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 直播 1 录播',
  `audio` varchar(255) NOT NULL COMMENT '讲师音频 直播',
  `qrcode_type` int(11) NOT NULL DEFAULT '0',
  `signin_status` int(11) NOT NULL COMMENT '是否开启游戏 0不开启  1开启',
  `qrcode` varchar(250) NOT NULL,
  `desc` text NOT NULL COMMENT '说明',
  `notice` text NOT NULL COMMENT '注意事项',
  `attachment` varchar(255) NOT NULL COMMENT '附件，音视频七牛网址',
  `stage` varchar(50) NOT NULL DEFAULT '' COMMENT '课程适合阶段',
  `sign_limit` int(11) NOT NULL DEFAULT '10000' COMMENT '报名限制数量',
  `hot` int(11) NOT NULL DEFAULT '0' COMMENT '课程热度',
  `flowers` int(11) NOT NULL DEFAULT '0',
  `display_status` tinyint(1) NOT NULL COMMENT '状态，0无效，1有效',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '课程价格',
  `display_tags` text NOT NULL COMMENT '显示标签',
  `anchor_uid` int(11) NOT NULL DEFAULT '0' COMMENT '主持人id',
  `teacher_uid` int(11) NOT NULL COMMENT '讲师用户id',
  `teacher_name` varchar(10) NOT NULL DEFAULT '' COMMENT '讲师名字',
  `teacher_avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '讲师头像',
  `teacher_hospital` varchar(100) NOT NULL DEFAULT '' COMMENT '讲师医院',
  `teacher_position` varchar(50) NOT NULL DEFAULT '' COMMENT '讲师职位',
  `teacher_desc` text NOT NULL COMMENT '讲师描述',
  `cat_title` varchar(255) NOT NULL DEFAULT '' COMMENT '套课页面显示标题',
  `cat_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '套课页面显示简介',
  `course_recommend` text NOT NULL COMMENT '该课程下的推荐课程',
  `speak_status` int(1) NOT NULL COMMENT '是否可发言，0不可以，1可以',
  `speak_chance` int(11) NOT NULL DEFAULT '0',
  `reply_notify_status` tinyint(4) NOT NULL DEFAULT '0',
  `notify_title` varchar(255) NOT NULL,
  `notify_content` varchar(200) NOT NULL DEFAULT '' COMMENT '上课通知内容',
  `notify_odate` varchar(20) NOT NULL,
  `notify_address` varchar(255) NOT NULL,
  `notify_remark` varchar(200) NOT NULL DEFAULT '' COMMENT '上课通知备注',
  `notify_url` varchar(255) NOT NULL DEFAULT '' COMMENT '上课通知跳转url',
  `notify_template_id` int(11) NOT NULL DEFAULT '1' COMMENT '模板类型',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '课程状态，1报名中，2直播中，3已结束',
  `user_type` tinyint(4) NOT NULL DEFAULT '0',
  `living_firend_title` varchar(250) NOT NULL,
  `living_firend_subtitle` text NOT NULL,
  `living_share_title` text NOT NULL,
  `living_share_picture` varchar(250) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `firend_title` varchar(250) NOT NULL,
  `firend_subtitle` text NOT NULL,
  `share_title` text NOT NULL,
  `share_picture` varchar(250) NOT NULL,
  `area_city_id` int(11) NOT NULL,
  `sstage` tinyint(4) NOT NULL,
  `smonth` tinyint(4) NOT NULL,
  `sage` tinyint(4) NOT NULL,
  `estage` tinyint(4) NOT NULL,
  `emonth` tinyint(4) NOT NULL,
  `eage` tinyint(4) NOT NULL,
  `stage_from` int(11) NOT NULL,
  `stage_to` int(11) NOT NULL,
  `extend` text NOT NULL COMMENT '扩展',
  `yunqi` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否孕期课',
  `brand` int(11) NOT NULL DEFAULT '0' COMMENT '品牌',
  `audio_detail` varchar(255) NOT NULL DEFAULT '' COMMENT '音频内容',
  `remark` text NOT NULL COMMENT '备注',
  `xxjp_title` varchar(255) NOT NULL DEFAULT '' COMMENT '慧摇自动下行精品课的标题',
  `is_del` tinyint(2) NOT NULL DEFAULT '0' COMMENT '因为去重，被标记删除',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '改课程在全课程列表中保留的课程'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `courseware`
--

CREATE TABLE `courseware` (
  `id` int(11) UNSIGNED NOT NULL,
  `cid` int(11) NOT NULL COMMENT '课程id',
  `img` varchar(200) NOT NULL DEFAULT '' COMMENT '课程图片',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `course_apply`
--

CREATE TABLE `course_apply` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `start_day` date NOT NULL,
  `end_day` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `stage` varchar(50) NOT NULL,
  `teacher_name` varchar(20) NOT NULL,
  `teacher_source` varchar(80) NOT NULL,
  `teacher_position` varchar(80) NOT NULL,
  `teacher_desc` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `refuse_reason` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `area` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `course_bak_bak`
--

CREATE TABLE `course_bak_bak` (
  `id` int(11) UNSIGNED NOT NULL,
  `cid` smallint(6) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `number` int(11) NOT NULL DEFAULT '0',
  `start_day` date NOT NULL COMMENT '开始日期',
  `start_time` time NOT NULL COMMENT '开始时间',
  `play_status` int(11) NOT NULL DEFAULT '0' COMMENT '0 播放音频 1 问答环节',
  `end_time` time NOT NULL COMMENT '结束时间',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 直播 1 录播',
  `audio` varchar(255) NOT NULL COMMENT '讲师音频 直播',
  `qrcode_type` int(11) NOT NULL DEFAULT '0',
  `signin_status` int(11) NOT NULL COMMENT '是否开启游戏 0不开启  1开启',
  `qrcode` varchar(250) NOT NULL,
  `desc` text NOT NULL COMMENT '说明',
  `notice` text NOT NULL COMMENT '注意事项',
  `attachment` varchar(255) NOT NULL COMMENT '附件，音视频七牛网址',
  `stage` varchar(50) NOT NULL DEFAULT '' COMMENT '课程适合阶段',
  `sign_limit` int(11) NOT NULL DEFAULT '10000' COMMENT '报名限制数量',
  `hot` int(11) NOT NULL DEFAULT '0' COMMENT '课程热度',
  `flowers` int(11) NOT NULL DEFAULT '0',
  `display_status` tinyint(1) NOT NULL COMMENT '状态，0无效，1有效',
  `anchor_uid` int(11) NOT NULL DEFAULT '0' COMMENT '主持人id',
  `teacher_uid` int(11) NOT NULL COMMENT '讲师用户id',
  `teacher_name` varchar(10) NOT NULL DEFAULT '' COMMENT '讲师名字',
  `teacher_avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '讲师头像',
  `teacher_hospital` varchar(100) NOT NULL DEFAULT '' COMMENT '讲师医院',
  `teacher_position` varchar(50) NOT NULL DEFAULT '' COMMENT '讲师职位',
  `teacher_desc` text NOT NULL COMMENT '讲师描述',
  `speak_status` int(1) NOT NULL COMMENT '是否可发言，0不可以，1可以',
  `speak_chance` int(11) NOT NULL DEFAULT '0',
  `reply_notify_status` tinyint(4) NOT NULL DEFAULT '0',
  `notify_title` varchar(255) NOT NULL,
  `notify_content` varchar(200) NOT NULL DEFAULT '' COMMENT '上课通知内容',
  `notify_odate` varchar(20) NOT NULL,
  `notify_address` varchar(255) NOT NULL,
  `notify_remark` varchar(200) NOT NULL DEFAULT '' COMMENT '上课通知备注',
  `notify_url` varchar(255) NOT NULL DEFAULT '' COMMENT '上课通知跳转url',
  `notify_template_id` int(11) NOT NULL DEFAULT '1' COMMENT '模板类型',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '课程状态，1报名中，2直播中，3已结束',
  `user_type` tinyint(4) NOT NULL DEFAULT '0',
  `living_firend_title` varchar(250) NOT NULL,
  `living_firend_subtitle` text NOT NULL,
  `living_share_title` text NOT NULL,
  `living_share_picture` varchar(250) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `firend_title` varchar(250) NOT NULL,
  `firend_subtitle` text NOT NULL,
  `share_title` text NOT NULL,
  `share_picture` varchar(250) NOT NULL,
  `area_city_id` int(11) NOT NULL,
  `sstage` tinyint(4) NOT NULL,
  `smonth` tinyint(4) NOT NULL,
  `sage` tinyint(4) NOT NULL,
  `estage` tinyint(4) NOT NULL,
  `emonth` tinyint(4) NOT NULL,
  `eage` tinyint(4) NOT NULL,
  `stage_from` int(11) NOT NULL,
  `stage_to` int(11) NOT NULL,
  `extend` text NOT NULL COMMENT '扩展',
  `yunqi` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否孕期课',
  `brand` int(11) NOT NULL DEFAULT '0' COMMENT '品牌',
  `audio_detail` varchar(255) NOT NULL DEFAULT '' COMMENT '音频内容',
  `remark` text NOT NULL COMMENT '备注',
  `xxjp_title` varchar(255) NOT NULL DEFAULT '' COMMENT '慧摇自动下行精品课的标题',
  `is_del` tinyint(2) NOT NULL DEFAULT '0' COMMENT '因为去重，被标记删除',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '改课程在全课程列表中保留的课程'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `course_cat`
--

CREATE TABLE `course_cat` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `subheader` varchar(255) NOT NULL DEFAULT '' COMMENT '副标题',
  `description` varchar(1000) NOT NULL,
  `img` varchar(200) NOT NULL,
  `show_type` int(11) NOT NULL DEFAULT '0' COMMENT '展示类型0：一体化，1：瀑布流，2：讲师',
  `remark` text NOT NULL COMMENT '备注',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '套课价格',
  `displayorder` mediumint(9) NOT NULL DEFAULT '10'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `course_counters`
--

CREATE TABLE `course_counters` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL COMMENT '项目id，对应cid或者套课cid',
  `item_type` char(32) NOT NULL COMMENT '项目类型：course普通课程 course_cat 套课',
  `course_reg` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '报名',
  `course_cat_reg` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '套课报名',
  `course_review` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '回顾课程',
  `course_living` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '直播课程',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `course_detail`
--

CREATE TABLE `course_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `cid` int(11) NOT NULL,
  `start_day` date NOT NULL,
  `end_day` date NOT NULL,
  `title` varchar(255) NOT NULL,
  `week_other` int(11) NOT NULL COMMENT '其他平台教育人次',
  `week_mudu` int(11) NOT NULL COMMENT '目睹教育人次',
  `month_other` int(11) NOT NULL COMMENT '本月其他平台教育人次',
  `month_mudu` int(11) NOT NULL COMMENT '本月目睹教育人次',
  `ytd_h5` int(11) NOT NULL COMMENT 'ytd H5教育人次',
  `ytd_other` int(11) NOT NULL COMMENT 'ytd 其他平台教育人次',
  `ytd_mudu` int(11) NOT NULL COMMENT 'ytd 目睹教育人次',
  `now_all_sign` int(11) NOT NULL COMMENT '上线至今总报名人次',
  `now_all_edu` int(11) NOT NULL COMMENT '上线至今全平台教育人次',
  `now_h5` int(11) NOT NULL COMMENT '上线至今h5教育人次',
  `now_other` int(11) NOT NULL COMMENT '上线至今其他平台教育人次',
  `ask_lask_week` int(11) NOT NULL COMMENT '上线至今目睹教育人次',
  `ask` int(11) NOT NULL COMMENT '提问数',
  `is_order` varchar(255) NOT NULL COMMENT '签约状态',
  `share` int(11) NOT NULL COMMENT '转发',
  `ext` varchar(255) NOT NULL COMMENT '扩展',
  `ext1` int(11) NOT NULL COMMENT '扩展2(int)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `course_end_access_log`
--

CREATE TABLE `course_end_access_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `course_listen`
--

CREATE TABLE `course_listen` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `cid` int(11) NOT NULL COMMENT '课程id',
  `listen_time` int(11) NOT NULL DEFAULT '1' COMMENT '连续听课(分钟)',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `course_push`
--

CREATE TABLE `course_push` (
  `id` int(10) UNSIGNED NOT NULL,
  `cid` int(11) NOT NULL COMMENT '课程id',
  `type` int(11) NOT NULL COMMENT '推送类型',
  `push_time` datetime NOT NULL COMMENT '推送时间',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '推送状态 0待推送 1已推送',
  `push_num` int(11) NOT NULL COMMENT '预计推送人数',
  `sign_start` datetime NOT NULL COMMENT '报名开始时间',
  `sign_end` datetime NOT NULL COMMENT '报名结束时间',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `course_review`
--

CREATE TABLE `course_review` (
  `id` int(11) UNSIGNED NOT NULL,
  `cid` int(11) NOT NULL COMMENT '课程id',
  `review_type` int(11) NOT NULL DEFAULT '1' COMMENT '课程回顾类型 1音频 2视频',
  `audio` varchar(200) NOT NULL COMMENT '课程音频',
  `audio_duration` int(11) NOT NULL COMMENT '音频时长',
  `video_display` tinyint(1) NOT NULL DEFAULT '0',
  `video_position` int(11) NOT NULL,
  `video` varchar(200) NOT NULL,
  `video_cover` varchar(250) NOT NULL,
  `teacher_avatar` varchar(255) NOT NULL COMMENT '讲师头像 仅作用于精彩问答',
  `guide_title` varchar(255) NOT NULL COMMENT '导语标题',
  `guide` text NOT NULL COMMENT '导语',
  `desc` longtext NOT NULL COMMENT '介绍',
  `q_and_a` text NOT NULL COMMENT '精彩问答',
  `section` text NOT NULL COMMENT '章节要点',
  `content` text NOT NULL COMMENT '文本内容',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态，1有效，0无效',
  `firend_title` varchar(250) NOT NULL,
  `firend_subtitle` text NOT NULL,
  `share_title` text NOT NULL,
  `share_picture` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '结束时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `course_review_questions`
--

CREATE TABLE `course_review_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `is_send` tinyint(4) NOT NULL DEFAULT '0',
  `yjt_qid` int(11) NOT NULL,
  `answer_url` varchar(255) NOT NULL,
  `is_close` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `course_stat`
--

CREATE TABLE `course_stat` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `channel` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `listen` int(11) NOT NULL COMMENT '最多的一次听课时长',
  `reward` tinyint(4) NOT NULL COMMENT '是否已获得听课奖励',
  `sign_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `share_sign_page` tinyint(4) NOT NULL,
  `share_sign_page_clicks` int(11) NOT NULL,
  `in_class_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `out_class_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `in_class_times` int(11) NOT NULL,
  `listen_time` int(11) NOT NULL,
  `share_living_page` tinyint(4) NOT NULL,
  `share_living_page_clicks` int(11) NOT NULL,
  `speak_times` int(11) NOT NULL,
  `teacher_answer_times` int(11) NOT NULL,
  `anchor_answer_times` int(11) NOT NULL,
  `share_review_page` tinyint(4) NOT NULL,
  `share_review_page_clicks` int(11) NOT NULL,
  `device` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `in_review_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `go_review_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '打开回顾页时间,报错页也记录',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `course_tags`
--

CREATE TABLE `course_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `cid` int(10) UNSIGNED NOT NULL,
  `tid` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'tag类型 0:内容tag 1:孕期tag 2:讲师tag',
  `weight` double NOT NULL DEFAULT '1' COMMENT 'tag占course的权重,为0到1之间的值',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `display_tags`
--

CREATE TABLE `display_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `img` text NOT NULL COMMENT '图标url',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `error_log`
--

CREATE TABLE `error_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `url` text NOT NULL COMMENT 'url',
  `msg` text NOT NULL COMMENT 'msg',
  `ua` text NOT NULL COMMENT 'ua',
  `stack` text NOT NULL COMMENT 'stack',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `estimation`
--

CREATE TABLE `estimation` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `mark` tinyint(4) NOT NULL,
  `content` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=TokuDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `group_qrcode`
--

CREATE TABLE `group_qrcode` (
  `id` int(11) UNSIGNED NOT NULL,
  `cid` int(11) NOT NULL COMMENT '课程id',
  `name` varchar(20) NOT NULL COMMENT '群名称',
  `img` varchar(200) NOT NULL DEFAULT '',
  `expired_in` int(10) NOT NULL COMMENT '有效时间',
  `limit_num` int(11) NOT NULL COMMENT '限制加群人数',
  `invite_num` int(11) NOT NULL COMMENT '已邀请加群人数',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `huiyao_xxjp`
--

CREATE TABLE `huiyao_xxjp` (
  `id` int(10) UNSIGNED NOT NULL,
  `cid` int(11) NOT NULL COMMENT '课程id',
  `push_num` int(11) NOT NULL COMMENT '推送人数',
  `push_count` int(11) NOT NULL COMMENT '推送条数',
  `t_date` datetime NOT NULL COMMENT '自动下行的日期',
  `sign_num` int(11) NOT NULL COMMENT '线下报名人数',
  `pv` int(11) NOT NULL COMMENT '打开pv',
  `uv` int(11) NOT NULL COMMENT '打开uv',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `import_course_week_data`
--

CREATE TABLE `import_course_week_data` (
  `id` int(11) NOT NULL,
  `ytd_quan` int(11) NOT NULL,
  `ytd_other` int(11) NOT NULL,
  `ytd_h5` int(11) NOT NULL,
  `all_sign` int(11) NOT NULL,
  `all_quan` int(11) NOT NULL,
  `all_other` int(11) NOT NULL,
  `all_h5` int(11) NOT NULL,
  `question` int(11) NOT NULL,
  `out_date` varchar(255) NOT NULL,
  `is_order` varchar(255) NOT NULL,
  `age` varchar(255) NOT NULL COMMENT '月龄',
  `brand` varchar(255) NOT NULL COMMENT '品牌',
  `update_brand` varchar(255) NOT NULL,
  `week_other` int(11) NOT NULL COMMENT '本周其他平台',
  `week_quest` int(11) NOT NULL COMMENT '本周提问数',
  `month_quan` int(11) NOT NULL DEFAULT '0' COMMENT '本月全平台',
  `month_h5` int(11) NOT NULL DEFAULT '0' COMMENT '本月H5',
  `month_other` int(11) NOT NULL DEFAULT '0' COMMENT '本月其他平台',
  `month_mu` int(11) NOT NULL DEFAULT '0' COMMENT '本月目睹'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `inclass_log`
--

CREATE TABLE `inclass_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `client_id` char(50) NOT NULL DEFAULT '' COMMENT '聊天室client_id',
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `login_at` int(11) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `logout_at` int(11) NOT NULL DEFAULT '0' COMMENT '退出时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '访问状态，1进入页面，2离开页面',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `job_media`
--

CREATE TABLE `job_media` (
  `media_id` char(100) NOT NULL COMMENT '微信mediaid',
  `data` text NOT NULL COMMENT '生成message 所需要的信息',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未处理,  1 已处理',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='语音上传转换任务表';

-- --------------------------------------------------------

--
-- 表的结构 `materiel`
--

CREATE TABLE `materiel` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL DEFAULT '' COMMENT '头图',
  `platform_name` varchar(255) NOT NULL DEFAULT '' COMMENT '平台名称',
  `platform_logo` varchar(255) NOT NULL DEFAULT '' COMMENT '平台logo',
  `position` varchar(255) NOT NULL COMMENT '点位',
  `brand` varchar(255) NOT NULL COMMENT '品牌',
  `group_t` varchar(255) NOT NULL COMMENT '分组',
  `date` varchar(255) NOT NULL COMMENT '日期',
  `link` varchar(255) NOT NULL COMMENT '推送链接',
  `key_word` varchar(255) NOT NULL COMMENT '关键词',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `message`
--

CREATE TABLE `message` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '消息ID',
  `cid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '课程ID',
  `author_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '作者ID',
  `author_type` int(1) UNSIGNED NOT NULL DEFAULT '1',
  `source_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '回复来源消息ID',
  `source_author_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '消息来源作者ID(@对象)',
  `type` int(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1文本/2语音',
  `content` varchar(500) NOT NULL COMMENT '消息内容',
  `state` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0普通/1待回答/2已回答',
  `receive_more` tinyint(4) NOT NULL DEFAULT '0',
  `compere_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '提交问题主持人ID',
  `lecturer_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '需回答讲师ID',
  `submit_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '主持人提交问题时间',
  `answer_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '讲师回答问题时间',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `display` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `message_answers`
--

CREATE TABLE `message_answers` (
  `id` int(10) UNSIGNED NOT NULL,
  `msg_id` int(10) UNSIGNED NOT NULL,
  `yjt_qid` int(10) UNSIGNED NOT NULL,
  `answer_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `microphone_log`
--

CREATE TABLE `microphone_log` (
  `id` int(11) NOT NULL,
  `cid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `is_share` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否是分享后抢话筒0不是/1是',
  `is_use` int(1) NOT NULL DEFAULT '0' COMMENT '是否已使用',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2015_12_18_144747_create_jobs_table', 1),
(4, '2015_12_25_152815_short_url', 1),
(5, '2015_12_25_174413_migration_id', 1),
(6, '2015_12_27_143749_modify_course_table', 1),
(7, '2015_12_27_164100_update_message_table', 1),
(8, '2015_12_28_133607_modify_user_table', 2),
(9, '2015_12_29_133022_create_course_stat_table', 2),
(10, '2015_12_30_140133_update_user_course', 3),
(11, '2015_12_30_151855_create_user_events', 4),
(12, '2015_12_30_201601_create_questionnaire_table', 5),
(13, '2016_01_06_111915_add_column_to_course', 6),
(14, '2016_01_05_150318_user_qrcode', 7),
(15, '2016_01_06_170842_modify_user_qrcode', 7),
(16, '2016_01_04_111540_create_year_messages_table', 8),
(17, '2016_01_07_115507_create_failed_jobs_table', 8),
(18, '2016_01_10_160826_modify_course_table', 9),
(19, '2016_01_11_104836_add_share_to_course_review', 10),
(20, '2016_01_15_145621_add_living_share_to_course', 11),
(21, '2016_01_18_121812_create_tplmsgs_table', 12),
(22, '2016_01_19_133446_update_course_table', 13),
(23, '2016_01_20_160033_modify_user_table', 13),
(24, '2016_01_21_150318_create_area_city', 14),
(25, '2016_01_21_150318_create_course_apply', 14),
(26, '2016_01_21_150318_modify_admin', 14),
(27, '2016_01_21_150318_modify_course', 14),
(28, '2016_01_26_155108_create_user_relations_table', 14),
(29, '2016_01_27_160509_modify_course_table', 14),
(30, '2016_01_27_165739_add_indexs_to_user_relations', 14),
(31, '2016_03_10_084619_add_indexs_to_user_course', 15),
(32, '2016_03_07_162504_add_check_key_to_tplmsgs_table', 16),
(33, '2016_03_10_104523_create_qrcodes_table', 17),
(34, '2016_03_10_114001_add_crm_NeverBuyIMFt_to_user', 17),
(35, '2016_03_10_115224_create_user_in_qrcodes_table', 17),
(36, '2016_03_16_214516_create_message_answers_table', 18),
(37, '2016_02_18_214333_modify_course_apply', 19),
(38, '2016_03_11_022047_create_app_configs_table', 19),
(39, '2016_03_12_215836_create_tags_table', 19),
(40, '2016_03_18_113938_separate_stage_to_six_col', 19),
(41, '2016_03_24_163923_create_course_cat', 19),
(42, '2016_03_28_120251_alter_table_course_add_cid', 19),
(43, '2016_04_04_201712_alter_message_add_receive_more', 20),
(44, '2016_04_16_204212_alter_admin_add_cids', 21),
(45, '2016_05_07_144212_alter_qrcodes_add_stage', 22),
(46, '2016_05_07_161121_create_course_end_access_log', 22),
(47, '2016_05_19_190535_add_flowers_to_course_table', 23),
(48, '2016_06_01_113440_create_estimation_table', 24),
(49, '2016_05_23_192352_create_course_review_questions', 25),
(50, '2016_06_27_110636_add_qrcode_to_course', 26),
(51, '2016_06_22_183908_add_video_to_course_review', 27),
(52, '2016_07_06_102051_add_field_to_qrcodes', 28),
(53, '2016_07_12_104045_update_course_table', 29),
(54, '2016_07_14_161159_add_device_to_course_stat', 30),
(55, '2016_07_18_184311_updata_qrcodes_tabled', 31),
(56, '2016_08_03_115624_create_signin_records_table', 32),
(57, '2016_08_03_115637_create_signin_items_table', 32),
(58, '2016_08_03_115657_create_signin_win_records_table', 32),
(59, '2016_08_03_115822_update_course_table', 32),
(60, '2016_08_04_094631_update_signin_records_table', 32),
(61, '2016_08_12_193505_add_review_type_to_course_review', 33),
(62, '2016_08_17_121017_add_q_and_a_to_course_review', 33),
(63, '2016_08_18_155723_add_section_to_course_review', 33),
(64, '2016_08_23_155840_add_guide_title_course_review', 33),
(65, '2016_08_16_104625_create_signin_info_table', 34),
(66, '2016_09_02_171322_create_user_course_cats_table', 35),
(67, '2016_09_13_113725_add_teacher_avatar_to_course_review', 36),
(68, '2016_09_22_170430_add_audio_to_course', 37),
(69, '2016_09_23_092813_add_play_status_to_course', 37),
(70, '2016_09_30_140607_add_type_to_course', 37),
(71, '2016_10_18_114118_add_go_review_time_to_course_stat', 38),
(72, '2016_10_27_114916_create_recommend_course_table', 39),
(73, '2016_10_27_121019_add_timestamps_to__recommend_course_table', 39),
(74, '2016_10_27_162249_add_openid_to__recommend_course_table', 39),
(75, '2016_10_28_175600_create_course_counters', 40),
(76, '2016_11_01_114808_add_votes_to_recommend_course_table', 41),
(77, '2016_10_25_164911_add_extend_to_course', 42),
(78, '2016_12_19_170438_create_search_records', 43),
(79, '2017_07_01_135509_create_week_data_table', 44),
(80, '2017_07_02_090526_create_week_summary_table', 44),
(81, '2017_07_10_144507_create_teacher_table', 45),
(82, '2017_07_10_205307_create_user_tags_table', 45),
(83, '2017_07_12_140935_add_notify_row_to_course', 46),
(84, '2017_07_12_145510_create_table_course_push', 46),
(85, '2017_07_14_150850_add_column_to_user', 47),
(86, '2017_07_14_150902_create_user_mq_table', 47),
(87, '2017_07_14_160651_modify_tag_table', 47),
(88, '2017_07_14_164813_modify_course_tag', 47),
(89, '2017_07_14_164823_modify_teacher', 47),
(90, '2017_07_15_145513_create_course_detail_table', 47),
(91, '2017_07_17_183702_add_remark_to_course', 48),
(92, '2017_07_18_144826_add_column_to_course_tag', 49),
(93, '2017_07_20_172840_add_column_to_user_tag', 50),
(94, '2017_07_21_100339_add_col_account_id_to_user', 51),
(95, '2017_07_21_141211_add_column_to_teacher', 51),
(96, '2017_07_24_154051_change_img_in_tag', 52),
(97, '2017_07_24_143224_create_table_woaap_qrcodes', 53),
(98, '2017_07_27_095154_create_table_xxjp', 54),
(99, '2017_07_25_191149_add_content_to_course_review', 55),
(100, '2017_07_26_192751_add_duration_to_course_review', 55),
(101, '2017_08_01_194305_modify_type_to_course_review', 56),
(102, '2017_08_01_203414_add_remain_to_user_mq', 56),
(103, '2017_08_03_114639_add_col_t_date_to_huiyao_xxjp', 57),
(104, '2017_08_04_104516_add_push_count_to_huiyao_xxjp', 58),
(105, '2017_08_01_092950_add_col_sign_time_to_course_push', 59),
(106, '2017_08_07_175631_add_xxjp_title_to_course', 60),
(107, '2017_08_08_100051_add_desc_to_user_mq', 60),
(108, '2017_08_08_103526_add_version_to_user', 61),
(109, '2017_08_09_163335_add_cols_to_huiyao_xxjp', 62),
(110, '2017_08_10_213544_create_table_tpl_project', 63),
(111, '2017_08_10_213613_create_table_tpl_project_push', 63),
(112, '2017_08_14_184248_add_icon_to_tag', 64),
(113, '2017_08_15_151313_create_table_course_listen', 65),
(114, '2017_08_15_153058_add_listen_to_course_stat', 65),
(115, '2017_08_16_152747_add_unionid_to_user', 66),
(116, '2017_08_18_162434_create_table_user_course_trace', 67),
(117, '2017_08_20_220846_create_table_task', 68),
(118, '2017_08_21_184401_change_event_in_user_mq', 68),
(119, '2017_08_22_165612_create_user_identify', 69),
(120, '2017_08_23_153059_add_aid_to_user_identify', 70),
(121, '2017_08_29_091635_add_recommend_to_course', 71),
(122, '2017_08_30_094944_create_table_materiel', 72),
(123, '2017_08_31_173347_add_channel_index_to_user', 73),
(124, '2017_08_31_142604_create_table_advertise', 74),
(125, '2017_09_04_090346_add_column_to_course_detail', 75),
(126, '2017_09_06_140551_add_brand_to_user', 76),
(127, '2017_09_08_095453_add_subject_to_advertise', 77),
(128, '2017_09_08_175149_modify_size_to_position_in_advertise', 78),
(129, '2017_09_11_135715_add_cat_title_des_to_course', 79),
(130, '2017_09_12_145226_create_user_buy_courses_table', 80),
(131, '2017_09_12_105436_add_price_to_course', 81),
(132, '2017_09_12_135635_add_price_to_course_cat', 81),
(133, '2017_09_13_103057_add_logo_and_banner_to_materiel', 82),
(134, '2017_09_13_140836_add_trade_id_and_trade_status_to_user_buy_courses', 83),
(135, '2017_09_13_140928_add_subheader_to_course_cat', 83),
(136, '2017_09_13_154746_rename_tid_to_trade_id_in_user_buy_courses', 84),
(137, '2017_09_13_150705_create_table_order', 85),
(138, '2017_09_07_140320_add_show_type_to_course_cat', 86),
(139, '2017_09_14_102146_add_order_to_advertise', 86),
(140, '2017_09_14_183310_change_link_from_string_to_text_in_advertise', 87),
(141, '2017_09_14_185921_create_error_log', 88),
(142, '2017_09_14_185934_add_remark_to_cat', 88),
(143, '2017_09_14_185948_add_display_tag_to_course', 88),
(144, '2017_09_14_190918_create_display_tags', 88),
(145, '2017_09_19_150729_add_col_to_course_push', 89);

-- --------------------------------------------------------

--
-- 表的结构 `online_statistics`
--

CREATE TABLE `online_statistics` (
  `id` int(11) UNSIGNED NOT NULL,
  `cid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `count` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '统计时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE `order` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `order_no` varchar(20) NOT NULL COMMENT '订单号',
  `trade_no` varchar(255) NOT NULL COMMENT '中台订单号',
  `subject` varchar(255) NOT NULL COMMENT '交易名称',
  `mq` int(11) NOT NULL COMMENT '购买多少mq',
  `total_fee` int(11) NOT NULL COMMENT '总金额,分',
  `status` varchar(255) NOT NULL COMMENT '订单状态 wait待支付 success支付成功 fail支付失败',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `qrcodes`
--

CREATE TABLE `qrcodes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `imgurl` varchar(100) NOT NULL,
  `stage` smallint(6) NOT NULL DEFAULT '1',
  `display_channel` text NOT NULL,
  `link` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `questionnaire`
--

CREATE TABLE `questionnaire` (
  `id` int(10) UNSIGNED NOT NULL,
  `cid` int(10) UNSIGNED NOT NULL,
  `openid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `answers` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `recommend_course`
--

CREATE TABLE `recommend_course` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL,
  `openid` varchar(50) NOT NULL,
  `sign_up_course_id` int(10) UNSIGNED NOT NULL,
  `sign_up_course_stage` varchar(20) NOT NULL,
  `recommend_course_id` int(10) UNSIGNED NOT NULL,
  `recommend_course_stage` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `search_records`
--

CREATE TABLE `search_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL COMMENT '搜索用户id',
  `keyword` varchar(255) NOT NULL COMMENT '搜索关键字',
  `result` varchar(255) NOT NULL COMMENT '搜索结果课程id 逗号隔开',
  `click_type` int(11) NOT NULL COMMENT '1 搜索结果课程  2 推荐课程',
  `click_id` int(11) NOT NULL COMMENT '点击课程id',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `sequence`
--

CREATE TABLE `sequence` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` bigint(20) NOT NULL,
  `gmt_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `sequence_opt`
--

CREATE TABLE `sequence_opt` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `value` bigint(20) UNSIGNED NOT NULL,
  `increment_by` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `start_with` bigint(20) UNSIGNED NOT NULL DEFAULT '1',
  `max_value` bigint(20) UNSIGNED NOT NULL DEFAULT '18446744073709551615',
  `cycle` tinyint(5) UNSIGNED NOT NULL DEFAULT '0',
  `gmt_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gmt_modified` timestamp NOT NULL DEFAULT '1970-01-31 16:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `share_log`
--

CREATE TABLE `share_log` (
  `id` int(11) NOT NULL,
  `cid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `short_urls`
--

CREATE TABLE `short_urls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hash` char(12) COLLATE utf8_unicode_ci NOT NULL COMMENT 'hash值',
  `url` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '源url',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `signin_game_configs`
--

CREATE TABLE `signin_game_configs` (
  `id` int(10) UNSIGNED NOT NULL,
  `cid` int(10) UNSIGNED NOT NULL COMMENT '课程ID',
  `platfrom` tinyint(4) NOT NULL COMMENT '所属平台 1 微信  2 QQ',
  `win_num` int(11) NOT NULL COMMENT '获奖人数',
  `fri_share_title` varchar(100) NOT NULL COMMENT '好友分享标题',
  `fri_share_desc` varchar(150) NOT NULL COMMENT '好友分享描述',
  `fri_circle_share_title` varchar(150) NOT NULL COMMENT '朋友圈分享语',
  `share_img` varchar(250) NOT NULL COMMENT '分享图片',
  `brand_img` varchar(250) NOT NULL COMMENT '品牌按钮 图片1',
  `rule_img` varchar(250) NOT NULL COMMENT '游戏规则 图片2',
  `intro_img` varchar(250) NOT NULL COMMENT '产品介绍 图片3',
  `teacher_img` varchar(250) NOT NULL COMMENT '课程讲师介绍 图片4',
  `living_img` varchar(250) NOT NULL COMMENT '签到回直播按钮 图片5',
  `prize_img` varchar(250) NOT NULL COMMENT '奖品图片 图片6',
  `award_img` varchar(250) NOT NULL COMMENT '领奖按钮 图片7',
  `user_info_title` varchar(250) NOT NULL COMMENT '用户信息页头 图片8',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `signin_items`
--

CREATE TABLE `signin_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `start_uid` int(10) UNSIGNED NOT NULL COMMENT '游戏发起人ID',
  `cid` int(10) UNSIGNED NOT NULL COMMENT '课程ID',
  `signin_num` int(11) NOT NULL COMMENT '签到数量',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `signin_records`
--

CREATE TABLE `signin_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `sid` int(11) NOT NULL COMMENT 'signin_items id',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `signin_win_records`
--

CREATE TABLE `signin_win_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `signin_item_id` int(10) UNSIGNED NOT NULL COMMENT '游戏ID',
  `mobile` varchar(30) NOT NULL COMMENT '所属 client id',
  `realname` varchar(50) NOT NULL COMMENT '真实姓名',
  `address` varchar(100) NOT NULL COMMENT '收货地址',
  `remark` varchar(100) NOT NULL COMMENT '备注',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(100) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'tag类型 0:内容tag 1:孕期tag 2:讲师tag',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片url',
  `interest_img` text NOT NULL COMMENT '作为兴趣显示的图标',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tag_question`
--

CREATE TABLE `tag_question` (
  `id` int(11) NOT NULL,
  `keyword` char(30) CHARACTER SET utf8 NOT NULL,
  `question` char(100) CHARACTER SET utf8 NOT NULL,
  `brand` char(20) CHARACTER SET utf8 NOT NULL,
  `answer` char(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `task`
--

CREATE TABLE `task` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL COMMENT '课程id,没有则为0',
  `type` varchar(255) NOT NULL COMMENT '任务类型',
  `mq` int(11) NOT NULL COMMENT '任务增加多少mq',
  `get` int(11) NOT NULL COMMENT '是否领取奖励',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `teacher`
--

CREATE TABLE `teacher` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(10) NOT NULL COMMENT '讲师姓名',
  `avatar` varchar(200) NOT NULL COMMENT '讲师头像',
  `hospital` varchar(100) NOT NULL COMMENT '讲师医院',
  `position` varchar(50) NOT NULL COMMENT '讲师职位',
  `desc` text NOT NULL COMMENT '讲师描述',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '讲师对应的tag的id',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tplmsgs`
--

CREATE TABLE `tplmsgs` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `cid` int(11) NOT NULL,
  `openid` char(32) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `code` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=TokuDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tpl_project`
--

CREATE TABLE `tpl_project` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '推送项目名',
  `notify_title` varchar(255) NOT NULL DEFAULT '' COMMENT '模板消息标题',
  `notify_content` varchar(255) NOT NULL DEFAULT '' COMMENT '模板消息内容',
  `notify_odate` varchar(255) NOT NULL DEFAULT '' COMMENT '模板消息时间',
  `notify_address` varchar(255) NOT NULL DEFAULT '' COMMENT '模板消息地址',
  `notify_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '模板消息备注',
  `notify_url` text NOT NULL COMMENT '模板消息url',
  `notify_template_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '模板消息类型',
  `remark` text NOT NULL COMMENT '备注',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tpl_project_push`
--

CREATE TABLE `tpl_project_push` (
  `id` int(10) UNSIGNED NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '推送项目id',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '模板消息类型',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT 'openid',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '推送是否成功',
  `abtest` varchar(255) NOT NULL DEFAULT '' COMMENT 'abtest',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `channel` char(20) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `openid` char(50) NOT NULL DEFAULT '' COMMENT '微信openid',
  `unionid` char(50) NOT NULL COMMENT '微信unionid',
  `account_id` varchar(255) NOT NULL DEFAULT '' COMMENT '中台account_id',
  `brand` int(11) NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `nickname` char(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(1) NOT NULL COMMENT '性别，1男，2女',
  `avatar` char(200) NOT NULL DEFAULT '' COMMENT '头像',
  `country` char(20) NOT NULL DEFAULT '' COMMENT '国家',
  `province` char(20) NOT NULL DEFAULT '' COMMENT '省',
  `city` char(20) NOT NULL DEFAULT '' COMMENT '市',
  `crm_province` varchar(20) NOT NULL,
  `crm_city` varchar(20) NOT NULL,
  `realname` char(11) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` bigint(11) NOT NULL COMMENT '手机号',
  `baby_birthday` datetime DEFAULT NULL COMMENT '宝宝生日',
  `crm_hasShop` tinyint(4) NOT NULL DEFAULT '0',
  `crm_NeverBuyIMF` tinyint(4) NOT NULL DEFAULT '1',
  `subscribe_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '关注状态，1已关注，0未关注',
  `crm_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否CRM系统用户，1是，0不是',
  `remember_token` char(100) NOT NULL DEFAULT '',
  `mq` int(11) NOT NULL DEFAULT '0' COMMENT '积分值',
  `sign_days` int(11) NOT NULL DEFAULT '0' COMMENT '连续签到天数',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '版本 -1老版本 1新版本'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_buy_courses`
--

CREATE TABLE `user_buy_courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `trade_id` varchar(20) NOT NULL DEFAULT '' COMMENT '订单ID',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `type` int(11) NOT NULL COMMENT '购买类型：1 套课 2 单课',
  `cid` int(11) NOT NULL COMMENT '套课ID或课程ID',
  `trade_status` int(11) NOT NULL DEFAULT '0' COMMENT '交易状态 0：待支付；1：已支付；',
  `mq` int(11) NOT NULL COMMENT '消耗MQ数量',
  `detail` text NOT NULL COMMENT '购买时商品状态',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `user_course`
--

CREATE TABLE `user_course` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL COMMENT 'id',
  `listen_time` int(11) NOT NULL DEFAULT '0',
  `cid` int(11) NOT NULL COMMENT 'id',
  `is_shared` tinyint(1) NOT NULL DEFAULT '0' COMMENT '01',
  `channel` varchar(50) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_course_cats`
--

CREATE TABLE `user_course_cats` (
  `id` int(10) UNSIGNED NOT NULL,
  `catid` int(10) UNSIGNED NOT NULL COMMENT '套课id',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=TokuDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `user_course_trace`
--

CREATE TABLE `user_course_trace` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `cid` int(11) NOT NULL COMMENT '课程id',
  `time` datetime NOT NULL COMMENT '最后更新时间',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `user_events`
--

CREATE TABLE `user_events` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL,
  `user_type` int(10) UNSIGNED NOT NULL,
  `cid` int(10) UNSIGNED NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=TokuDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_friend`
--

CREATE TABLE `user_friend` (
  `id` int(11) UNSIGNED NOT NULL,
  `from_uid` int(11) NOT NULL COMMENT 'uid',
  `to_uid` int(11) NOT NULL COMMENT 'uid',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_friend_log`
--

CREATE TABLE `user_friend_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `from_uid` int(11) NOT NULL COMMENT 'uid',
  `to_uid` int(11) NOT NULL COMMENT 'uid',
  `cid` int(11) NOT NULL COMMENT 'id',
  `from` varchar(50) NOT NULL COMMENT '来源',
  `url` varchar(500) NOT NULL COMMENT '分享url',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_identify`
--

CREATE TABLE `user_identify` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `aid` int(11) NOT NULL COMMENT '活动id',
  `is_member` int(11) NOT NULL COMMENT '是否为vip,不是则为0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `user_in_qrcodes`
--

CREATE TABLE `user_in_qrcodes` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL,
  `qid` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `user_mq`
--

CREATE TABLE `user_mq` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '增加或消费类型',
  `event` varchar(255) NOT NULL COMMENT '积分改变的事件',
  `desc` text NOT NULL COMMENT '消费描述',
  `mq` int(11) NOT NULL COMMENT '增加或减少的积分值',
  `balance` int(11) NOT NULL DEFAULT '0' COMMENT '余额',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `user_qrcode`
--

CREATE TABLE `user_qrcode` (
  `id` int(10) UNSIGNED NOT NULL,
  `img` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `stage` tinyint(4) NOT NULL DEFAULT '0',
  `province` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `word` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_relations`
--

CREATE TABLE `user_relations` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `user_tags`
--

CREATE TABLE `user_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `tid` int(11) NOT NULL COMMENT 'tag id',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'tag类型 0:内容tag 1:孕期tag 2:讲师tag',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `verify_code`
--

CREATE TABLE `verify_code` (
  `id` int(11) UNSIGNED NOT NULL,
  `code` int(11) NOT NULL COMMENT '验证码',
  `mobile` bigint(11) NOT NULL COMMENT '用户手机',
  `expired_in` int(10) NOT NULL COMMENT '失效时间',
  `status` tinyint(11) NOT NULL DEFAULT '0' COMMENT '状态，0未验证，1已验证',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '结束时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `week_data_export`
--

CREATE TABLE `week_data_export` (
  `id` int(10) UNSIGNED NOT NULL,
  `start_day` date NOT NULL,
  `end_day` date NOT NULL,
  `all_course_week_url` varchar(255) NOT NULL,
  `week_new_course_url` varchar(255) NOT NULL,
  `week_summary_url` varchar(255) NOT NULL,
  `all_course_year_url` varchar(255) NOT NULL,
  `all_course_year_short_url` varchar(255) NOT NULL,
  `signup_by_channel_url` varchar(255) NOT NULL,
  `week_diversion_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `week_summary`
--

CREATE TABLE `week_summary` (
  `id` int(10) UNSIGNED NOT NULL,
  `start_day` date NOT NULL COMMENT '时间（周）',
  `end_day` date NOT NULL,
  `index_pv` int(11) NOT NULL,
  `index_uv` int(11) NOT NULL,
  `h5_sign_up` int(11) NOT NULL COMMENT 'h5报名',
  `h5_sign_up_online` int(11) NOT NULL COMMENT 'h5报名-线上',
  `h5_sign_up_offline` int(11) NOT NULL COMMENT 'h5报名-线下',
  `people_times` int(11) NOT NULL COMMENT '教育人次',
  `people_number` int(11) NOT NULL COMMENT '教育人数',
  `people_times_offline` int(11) NOT NULL COMMENT '教育人次-线下',
  `people_number_offline` int(11) NOT NULL COMMENT '教育人数-线下',
  `large_platform_people_times` int(11) NOT NULL COMMENT '大平台教育人次',
  `community_people_times` int(11) NOT NULL COMMENT '社区教育人次',
  `other` int(11) NOT NULL COMMENT '其他',
  `listen_hours` int(11) NOT NULL COMMENT '听课时长',
  `ask_times` int(11) NOT NULL COMMENT '提问人次',
  `week_active` int(11) NOT NULL COMMENT '周活跃',
  `week_active_online` int(11) NOT NULL COMMENT '线上周活跃',
  `new_member` int(11) NOT NULL COMMENT '新会员'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `woaap_qrcodes`
--

CREATE TABLE `woaap_qrcodes` (
  `id` int(10) UNSIGNED NOT NULL,
  `source` varchar(255) NOT NULL COMMENT '来源',
  `params` varchar(255) NOT NULL COMMENT '参数的json字符串',
  `scene_str` varchar(255) NOT NULL COMMENT '微信场景值id',
  `ticket` varchar(255) NOT NULL COMMENT '微信ticket',
  `expire` int(11) NOT NULL COMMENT '过期时间',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `year_messages`
--

CREATE TABLE `year_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL,
  `content` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `__drds__system__lock__`
--

CREATE TABLE `__drds__system__lock__` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '主键',
  `gmt_create` datetime NOT NULL COMMENT '创建时间',
  `gmt_modified` datetime NOT NULL COMMENT '修改时间',
  `name` varchar(255) NOT NULL COMMENT 'name',
  `token` varchar(255) NOT NULL COMMENT 'token',
  `identity` varchar(255) NOT NULL COMMENT 'identity',
  `operator` varchar(255) NOT NULL COMMENT 'operator'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertise`
--
ALTER TABLE `advertise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_configs`
--
ALTER TABLE `app_configs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `app_configs_module_key_index` (`module`,`key`);

--
-- Indexes for table `area_city`
--
ALTER TABLE `area_city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_type_start_day` (`user_type`,`start_day`,`display_status`);

--
-- Indexes for table `courseware`
--
ALTER TABLE `courseware`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_apply`
--
ALTER TABLE `course_apply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_bak_bak`
--
ALTER TABLE `course_bak_bak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_type_start_day` (`user_type`,`start_day`,`display_status`);

--
-- Indexes for table `course_cat`
--
ALTER TABLE `course_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_counters`
--
ALTER TABLE `course_counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_counters_item_id_item_type_unique` (`item_id`,`item_type`);

--
-- Indexes for table `course_detail`
--
ALTER TABLE `course_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_end_access_log`
--
ALTER TABLE `course_end_access_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_end_access_log_uid_cid_index` (`uid`,`cid`);

--
-- Indexes for table `course_listen`
--
ALTER TABLE `course_listen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_listen_uid_cid_index` (`uid`,`cid`);

--
-- Indexes for table `course_push`
--
ALTER TABLE `course_push`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_review`
--
ALTER TABLE `course_review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_review_questions`
--
ALTER TABLE `course_review_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_review_questions_uid_cid_index` (`uid`,`cid`);

--
-- Indexes for table `course_stat`
--
ALTER TABLE `course_stat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`,`cid`),
  ADD KEY `cid` (`cid`,`id`),
  ADD KEY `listen_time` (`listen_time`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `sign_time` (`sign_time`);

--
-- Indexes for table `course_tags`
--
ALTER TABLE `course_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tid` (`tid`),
  ADD KEY `cid` (`cid`);

--
-- Indexes for table `display_tags`
--
ALTER TABLE `display_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `error_log`
--
ALTER TABLE `error_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimation`
--
ALTER TABLE `estimation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_qrcode`
--
ALTER TABLE `group_qrcode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `huiyao_xxjp`
--
ALTER TABLE `huiyao_xxjp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `import_course_week_data`
--
ALTER TABLE `import_course_week_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inclass_log`
--
ALTER TABLE `inclass_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `cid` (`cid`,`login_at`,`logout_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`);

--
-- Indexes for table `job_media`
--
ALTER TABLE `job_media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `materiel`
--
ALTER TABLE `materiel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cid` (`cid`,`author_type`,`type`,`created_at`),
  ADD KEY `cid_2` (`cid`,`lecturer_id`,`submit_time`),
  ADD KEY `cid_3` (`cid`,`source_author_id`),
  ADD KEY `message_source_id_index` (`source_id`);

--
-- Indexes for table `message_answers`
--
ALTER TABLE `message_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_answers_msg_id_yjt_qid_index` (`msg_id`,`yjt_qid`);

--
-- Indexes for table `microphone_log`
--
ALTER TABLE `microphone_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cid` (`cid`,`uid`,`created_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `online_statistics`
--
ALTER TABLE `online_statistics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cid` (`cid`,`time`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_order_no_unique` (`order_no`),
  ADD KEY `order_uid_index` (`uid`),
  ADD KEY `order_order_no_index` (`order_no`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `qrcodes`
--
ALTER TABLE `qrcodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questionnaire`
--
ALTER TABLE `questionnaire`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recommend_course`
--
ALTER TABLE `recommend_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recommend_course_sign_up_course_id_id_index` (`sign_up_course_id`,`id`),
  ADD KEY `recommend_course_uid_index` (`uid`);

--
-- Indexes for table `search_records`
--
ALTER TABLE `search_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_records_id_keyword_created_at_index` (`id`,`keyword`(191),`created_at`);

--
-- Indexes for table `sequence`
--
ALTER TABLE `sequence`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`);

--
-- Indexes for table `sequence_opt`
--
ALTER TABLE `sequence_opt`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`);

--
-- Indexes for table `share_log`
--
ALTER TABLE `share_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `short_urls`
--
ALTER TABLE `short_urls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_urls_hash_unique` (`hash`);

--
-- Indexes for table `signin_game_configs`
--
ALTER TABLE `signin_game_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `signin_items`
--
ALTER TABLE `signin_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `signin_items_start_uid_signin_num_created_at_index` (`start_uid`,`signin_num`,`created_at`);

--
-- Indexes for table `signin_records`
--
ALTER TABLE `signin_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `signin_win_records`
--
ALTER TABLE `signin_win_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`type`);

--
-- Indexes for table `tag_question`
--
ALTER TABLE `tag_question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_uid_index` (`uid`),
  ADD KEY `task_type_index` (`type`(191));

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_name_unique` (`name`);

--
-- Indexes for table `tplmsgs`
--
ALTER TABLE `tplmsgs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `openid` (`openid`),
  ADD KEY `cid` (`cid`,`id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `tpl_project`
--
ALTER TABLE `tpl_project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tpl_project_push`
--
ALTER TABLE `tpl_project_push`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tpl_project_push_pid_index` (`pid`),
  ADD KEY `tpl_project_push_status_index` (`status`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `openid` (`openid`),
  ADD KEY `nickname` (`nickname`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `type` (`type`),
  ADD KEY `user_account_id_index` (`account_id`),
  ADD KEY `baby_birthday` (`baby_birthday`),
  ADD KEY `user_version_index` (`version`),
  ADD KEY `user_channel_index` (`channel`);

--
-- Indexes for table `user_buy_courses`
--
ALTER TABLE `user_buy_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_buy_courses_tid_unique` (`trade_id`);

--
-- Indexes for table `user_course`
--
ALTER TABLE `user_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_course_uid_index` (`uid`),
  ADD KEY `cid_id` (`cid`,`id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `channel` (`channel`),
  ADD KEY `cid_created` (`cid`,`created_at`);

--
-- Indexes for table `user_course_cats`
--
ALTER TABLE `user_course_cats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_course_cats_catid_uid_unique` (`catid`,`uid`);

--
-- Indexes for table `user_course_trace`
--
ALTER TABLE `user_course_trace`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_events`
--
ALTER TABLE `user_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_events_uid_cid_type_index` (`uid`,`cid`,`type`),
  ADD KEY `cid` (`cid`,`type`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `user_friend`
--
ALTER TABLE `user_friend`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `from_uid` (`from_uid`,`to_uid`),
  ADD KEY `from_to_uid` (`from_uid`,`to_uid`);

--
-- Indexes for table `user_friend_log`
--
ALTER TABLE `user_friend_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to_openid` (`to_uid`),
  ADD KEY `from_openid` (`from_uid`);

--
-- Indexes for table `user_identify`
--
ALTER TABLE `user_identify`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_identify_uid_index` (`uid`);

--
-- Indexes for table `user_in_qrcodes`
--
ALTER TABLE `user_in_qrcodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_in_qrcodes_uid_qid_index` (`uid`,`qid`);

--
-- Indexes for table `user_mq`
--
ALTER TABLE `user_mq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_qrcode`
--
ALTER TABLE `user_qrcode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_relations`
--
ALTER TABLE `user_relations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_relations_openid_type_unique` (`openid`,`type`),
  ADD KEY `user_relations_uid_index` (`uid`);

--
-- Indexes for table `user_tags`
--
ALTER TABLE `user_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verify_code`
--
ALTER TABLE `verify_code`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`,`mobile`,`expired_in`);

--
-- Indexes for table `week_data_export`
--
ALTER TABLE `week_data_export`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `week_summary`
--
ALTER TABLE `week_summary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `woaap_qrcodes`
--
ALTER TABLE `woaap_qrcodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `woaap_qrcodes_scene_str_index` (`scene_str`(191));

--
-- Indexes for table `year_messages`
--
ALTER TABLE `year_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `year_messages_uid_index` (`uid`);

--
-- Indexes for table `__drds__system__lock__`
--
ALTER TABLE `__drds__system__lock__`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_NAME` (`name`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
--
-- 使用表AUTO_INCREMENT `advertise`
--
ALTER TABLE `advertise`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- 使用表AUTO_INCREMENT `app_configs`
--
ALTER TABLE `app_configs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=338;
--
-- 使用表AUTO_INCREMENT `area_city`
--
ALTER TABLE `area_city`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- 使用表AUTO_INCREMENT `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=506;
--
-- 使用表AUTO_INCREMENT `courseware`
--
ALTER TABLE `courseware`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4400;
--
-- 使用表AUTO_INCREMENT `course_apply`
--
ALTER TABLE `course_apply`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `course_bak_bak`
--
ALTER TABLE `course_bak_bak`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=496;
--
-- 使用表AUTO_INCREMENT `course_cat`
--
ALTER TABLE `course_cat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- 使用表AUTO_INCREMENT `course_counters`
--
ALTER TABLE `course_counters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=501;
--
-- 使用表AUTO_INCREMENT `course_detail`
--
ALTER TABLE `course_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `course_end_access_log`
--
ALTER TABLE `course_end_access_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=364742;
--
-- 使用表AUTO_INCREMENT `course_listen`
--
ALTER TABLE `course_listen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86024;
--
-- 使用表AUTO_INCREMENT `course_push`
--
ALTER TABLE `course_push`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;
--
-- 使用表AUTO_INCREMENT `course_review`
--
ALTER TABLE `course_review`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;
--
-- 使用表AUTO_INCREMENT `course_review_questions`
--
ALTER TABLE `course_review_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6181;
--
-- 使用表AUTO_INCREMENT `course_stat`
--
ALTER TABLE `course_stat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70983911;
--
-- 使用表AUTO_INCREMENT `course_tags`
--
ALTER TABLE `course_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5520;
--
-- 使用表AUTO_INCREMENT `display_tags`
--
ALTER TABLE `display_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `error_log`
--
ALTER TABLE `error_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `estimation`
--
ALTER TABLE `estimation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4684;
--
-- 使用表AUTO_INCREMENT `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188434;
--
-- 使用表AUTO_INCREMENT `group_qrcode`
--
ALTER TABLE `group_qrcode`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `huiyao_xxjp`
--
ALTER TABLE `huiyao_xxjp`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
--
-- 使用表AUTO_INCREMENT `inclass_log`
--
ALTER TABLE `inclass_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=440981;
--
-- 使用表AUTO_INCREMENT `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `materiel`
--
ALTER TABLE `materiel`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3930;
--
-- 使用表AUTO_INCREMENT `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '消息ID', AUTO_INCREMENT=82067;
--
-- 使用表AUTO_INCREMENT `message_answers`
--
ALTER TABLE `message_answers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3374;
--
-- 使用表AUTO_INCREMENT `microphone_log`
--
ALTER TABLE `microphone_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16831;
--
-- 使用表AUTO_INCREMENT `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;
--
-- 使用表AUTO_INCREMENT `online_statistics`
--
ALTER TABLE `online_statistics`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14403;
--
-- 使用表AUTO_INCREMENT `order`
--
ALTER TABLE `order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- 使用表AUTO_INCREMENT `qrcodes`
--
ALTER TABLE `qrcodes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- 使用表AUTO_INCREMENT `questionnaire`
--
ALTER TABLE `questionnaire`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
--
-- 使用表AUTO_INCREMENT `recommend_course`
--
ALTER TABLE `recommend_course`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4412814;
--
-- 使用表AUTO_INCREMENT `search_records`
--
ALTER TABLE `search_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13917;
--
-- 使用表AUTO_INCREMENT `sequence`
--
ALTER TABLE `sequence`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `sequence_opt`
--
ALTER TABLE `sequence_opt`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `share_log`
--
ALTER TABLE `share_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8134;
--
-- 使用表AUTO_INCREMENT `short_urls`
--
ALTER TABLE `short_urls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1203;
--
-- 使用表AUTO_INCREMENT `signin_game_configs`
--
ALTER TABLE `signin_game_configs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- 使用表AUTO_INCREMENT `signin_items`
--
ALTER TABLE `signin_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1049;
--
-- 使用表AUTO_INCREMENT `signin_records`
--
ALTER TABLE `signin_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=419;
--
-- 使用表AUTO_INCREMENT `signin_win_records`
--
ALTER TABLE `signin_win_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- 使用表AUTO_INCREMENT `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=365;
--
-- 使用表AUTO_INCREMENT `tag_question`
--
ALTER TABLE `tag_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=624;
--
-- 使用表AUTO_INCREMENT `task`
--
ALTER TABLE `task`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219158;
--
-- 使用表AUTO_INCREMENT `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;
--
-- 使用表AUTO_INCREMENT `tplmsgs`
--
ALTER TABLE `tplmsgs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80616592;
--
-- 使用表AUTO_INCREMENT `tpl_project`
--
ALTER TABLE `tpl_project`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `tpl_project_push`
--
ALTER TABLE `tpl_project_push`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285058;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6878819;
--
-- 使用表AUTO_INCREMENT `user_buy_courses`
--
ALTER TABLE `user_buy_courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
--
-- 使用表AUTO_INCREMENT `user_course`
--
ALTER TABLE `user_course`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70372933;
--
-- 使用表AUTO_INCREMENT `user_course_cats`
--
ALTER TABLE `user_course_cats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10898998;
--
-- 使用表AUTO_INCREMENT `user_course_trace`
--
ALTER TABLE `user_course_trace`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `user_events`
--
ALTER TABLE `user_events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3427616;
--
-- 使用表AUTO_INCREMENT `user_friend`
--
ALTER TABLE `user_friend`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203873;
--
-- 使用表AUTO_INCREMENT `user_friend_log`
--
ALTER TABLE `user_friend_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183532;
--
-- 使用表AUTO_INCREMENT `user_identify`
--
ALTER TABLE `user_identify`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=984;
--
-- 使用表AUTO_INCREMENT `user_in_qrcodes`
--
ALTER TABLE `user_in_qrcodes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281067;
--
-- 使用表AUTO_INCREMENT `user_mq`
--
ALTER TABLE `user_mq`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346501;
--
-- 使用表AUTO_INCREMENT `user_qrcode`
--
ALTER TABLE `user_qrcode`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- 使用表AUTO_INCREMENT `user_relations`
--
ALTER TABLE `user_relations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59050;
--
-- 使用表AUTO_INCREMENT `user_tags`
--
ALTER TABLE `user_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=455465;
--
-- 使用表AUTO_INCREMENT `verify_code`
--
ALTER TABLE `verify_code`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30427;
--
-- 使用表AUTO_INCREMENT `week_data_export`
--
ALTER TABLE `week_data_export`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `week_summary`
--
ALTER TABLE `week_summary`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `woaap_qrcodes`
--
ALTER TABLE `woaap_qrcodes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18163;
--
-- 使用表AUTO_INCREMENT `year_messages`
--
ALTER TABLE `year_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4024;
--
-- 使用表AUTO_INCREMENT `__drds__system__lock__`
--
ALTER TABLE `__drds__system__lock__`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
