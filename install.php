<?php
pdo_query("
CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_bank') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL,
  `api` varchar(512) NOT NULL,
  `price` varchar(100) DEFAULT NULL,
  `icon` varchar(256) DEFAULT NULL,
  `photo` varchar(256) DEFAULT NULL,
  `url` varchar(512) DEFAULT NULL,
  `num` int(10) NOT NULL DEFAULT '0',
  `orderby` int(10) NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `price_tow` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_card') . " (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `bank` int(10) NOT NULL,
  `title` varchar(128) NOT NULL,
  `api` varchar(512) NOT NULL,
  `photo` varchar(128) NOT NULL,
  `desc` varchar(512) DEFAULT NULL,
  `detailed` text  COMMENT '详细信息',
  `recommend` int(10) NOT NULL DEFAULT '1' COMMENT '1不推荐2推荐',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_cash') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1'COMMENT '1未处理2已打款3已拒绝',
  `alipay` varchar(128) NOT NULL,
  `mobile` varchar(16) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_group') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `about` text,
  `price` int(10) NOT NULL DEFAULT '0',
  `mout` varchar(100) NOT NULL,
  `mlevel` int(1) NOT NULL DEFAULT '0',
  `weight` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `orderby` int(10) NOT NULL,
  `mout_tow` varchar(100) NOT NULL,
  `type` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_lend') . " (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `title` varchar(64) NOT NULL COMMENT '标题',
  `icon` varchar(256) DEFAULT NULL COMMENT '图标',
  `url` varchar(512) DEFAULT NULL COMMENT '跳转地址',
  `orderby` int(10) NOT NULL DEFAULT '0',
  `status` int(10) NOT NULL DEFAULT '1' COMMENT '1开启2关闭',
  `category_id` varchar(255) NOT NULL COMMENT '所属分类ID',
  `sort` int(10) NOT NULL COMMENT '排序',
  `success_rate` varchar(64) NOT NULL COMMENT '成功率',
  `brief_introduction` varchar(100) NOT NULL COMMENT '简介',
  `daily_interest_rate` varchar(100) NOT NULL COMMENT '日利率',
  `annual_interest_rate` varchar(64) NOT NULL COMMENT '月利率',
  `quota_min` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '额度（最小）',
  `quota_max` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '额度（最大）',
  `month_term_star` varchar(64) NOT NULL COMMENT '期限（月）开始',
  `month_term_end` varchar(64) NOT NULL COMMENT '期限（月）结束',
  `day_term_star` varchar(64) NOT NULL COMMENT '期限（天）开始',
  `day_term_end` varchar(64) NOT NULL COMMENT '期限（天）结束',
  `audit_method` varchar(64) NOT NULL COMMENT '申请方式',
  `audit_cycle` varchar(64) NOT NULL COMMENT '申请周期',
  `lending_time` varchar(64) NOT NULL COMMENT '放款时间',
  `repayment_method` varchar(255) NOT NULL COMMENT '还款方式',
  `application_process` varchar(255) NOT NULL COMMENT '申请流程',
  `application_conditions` varchar(255) NOT NULL COMMENT '申请条件',
  `detailed_introduction` varchar(255) NOT NULL COMMENT '详情介绍',
  `recommend` int(10) NOT NULL DEFAULT '1' COMMENT '是否推荐1推荐0不推荐',
  `is_new` int(10) NOT NULL DEFAULT '1' COMMENT '是否推荐1最新0不是最新',
  `application_number` int(10) NOT NULL COMMENT '申请人数',
  `true_click` int(10) NOT NULL COMMENT '真实点击数',
  `price` varchar(64) NOT NULL DEFAULT 0 COMMENT '分层比例',
  `price_tow` varchar(64) NOT NULL DEFAULT 0 COMMENT '二级固定分成',
  `trait` varchar(64) NOT NULL COMMENT '网贷特点',
  `percent_price` varchar(100) NOT NULL DEFAULT 0  COMMENT '百分比分成',
  `percent_price_tow` varchar(100) NOT NULL DEFAULT 0  COMMENT '二级百分比分成',

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_logs') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `level` int(1) NOT NULL DEFAULT '0',
  `record` int(10) NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_news') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `title` varchar(256) NOT NULL,
  `thumb` varchar(256) DEFAULT NULL,
  `desc` varchar(512) DEFAULT NULL,
  `click` int(10) NOT NULL,
  `newclass_id` int(10) NOT NULL,
  `weight` int(10) NOT NULL DEFAULT '0' COMMENT '需要权重',
  `create_time` int(10) NOT NULL,
  `sort` int(10) NOT NULL COMMENT '排序',
  `content` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_order') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sn` varchar(24) NOT NULL,
  `uniacid` int(10) NOT NULL,
  `group` int(10) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uid` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '2未支付1已支付',
  `create_time` int(10) NOT NULL,
  `pay_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_record') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uniacid` int(10) NOT NULL,
  `uid` int(10) NOT NULL COMMENT '申请人ID',
  `bank` int(10) NOT NULL COMMENT '银行ID',
  `name` varchar(24) DEFAULT NULL,
  `certify` varchar(24) DEFAULT NULL,
  `mobile` varchar(16) DEFAULT NULL,
  `fan_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总佣金',
  `fan_uid1` int(10) NOT NULL DEFAULT '0' COMMENT '上一级领导',
  `fan_level1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '上一级佣金',
  `fan_uid2` int(10) NOT NULL DEFAULT '0' COMMENT '第二级领导',
  `fan_level2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '第二级佣金',
  `fan_level3` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fan_uid3` int(10) NOT NULL DEFAULT '0',
  `remote` varchar(16) DEFAULT NULL,
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0未返佣 1审核成功 2 审核失败',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1办卡2网贷3是用户会员升级',
  `application_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '下款额度',
  `loan_state` tinyint(3) NOT NULL DEFAULT '1' COMMENT '网贷状态1是固定金额2百分比',
  `percentage_noe` varchar(16) NOT NULL DEFAULT '0' COMMENT '直推百分比',
  `percentage_tow` varchar(16) NOT NULL DEFAULT '0' COMMENT '间推百分比',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_category') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uniacid` int(10) NOT NULL,
  `sort` int(10) NOT NULL COMMENT '排序',
  `name` varchar(24) DEFAULT NULL,
  `status` int(10) NOT NULL DEFAULT '1' COMMENT '1开启0关闭状态',
  `money_max` decimal(10,2) NOT NULL DEFAULT '0.00'COMMENT '最大金额',
  `money_min` decimal(10,2) NOT NULL DEFAULT '0.00'COMMENT '小金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_setting') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `config` text,
  `invite` text,
  `contract_noe` text,
  `contract_tow` text,
  `contract_three` text,
  `contract_four` text,
  `raiders` text COMMENT '办卡攻略' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_user') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uniacid` int(10) NOT NULL,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `group` int(10) NOT NULL DEFAULT '0' COMMENT '用户级别',
  `name` varchar(32) DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(16) DEFAULT NULL COMMENT '手机号码',
  `certify` varchar(24) DEFAULT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '返佣账户金额',
  `leader1` int(10) NOT NULL COMMENT '上级',
  `leader2` int(10) NOT NULL COMMENT '上上级',
  `leader3` int(10) NOT NULL COMMENT '上上上级',
  `openid`  varchar(100) DEFAULT NULL COMMENT '用户openid',
  `alipay` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_newclass') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `name`  varchar(100) DEFAULT NULL COMMENT '分类名称',
  `img`  varchar(100) DEFAULT NULL COMMENT '分类图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS " . tablename('d1sj_card_banner') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `url`  varchar(100) DEFAULT NULL COMMENT '跳转链接',
  `img`  varchar(100) DEFAULT NULL COMMENT 'banner图片',
  `type` int(10) NOT NULL DEFAULT '1' COMMENT '1 新闻 2 网贷 3信用卡',
  `sort` int(10) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `d1sj_card_loan`;
CREATE TABLE `ims_d1sj_card_loan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `name` varchar(30) DEFAULT NULL COMMENT '用户名',
  `phone` varchar(30) DEFAULT NULL COMMENT '联系电话',
  `address` varchar(255) DEFAULT NULL COMMENT '家庭住址',
  `detailed_address` varchar(255) DEFAULT NULL COMMENT '详细住址',
  `work` varchar(200) DEFAULT NULL COMMENT '工作单位',
  `quota` decimal(10,2) DEFAULT '0.00' COMMENT '需求额度',
  `wages` int(2) DEFAULT '1' COMMENT '工资发放形式1打卡2现金',
  `vehicle` int(2) DEFAULT '1' COMMENT '是否有车1没有2有',
  `room` int(2) DEFAULT '1' COMMENT '是否有房1没有2有',
  `policy` int(2) DEFAULT '1' COMMENT '是否有保单1没有2有',
  `status` int(2) DEFAULT '1' COMMENT '是否审核1未审核2通过3驳回',
  `card` varchar(100) DEFAULT '0' COMMENT '身份证号',
  `recommend_id` int(10) DEFAULT '0' COMMENT '推荐人ID',
  `uid` int(10) DEFAULT '0' COMMENT '用户UID',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
");











?>