# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.16)
# Database: snh48_gm
# Generation Time: 2016-05-17 08:25:36 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table gm_func
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gm_func`;

CREATE TABLE `gm_func` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `caption` varchar(200) NOT NULL,
  `node_url` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `gm_func` WRITE;
/*!40000 ALTER TABLE `gm_func` DISABLE KEYS */;

INSERT INTO `gm_func` (`id`, `parent_id`, `caption`, `node_url`)
VALUES
	(1,0,'系统模块',''),
	(2,1,'用户管理','User'),
	(3,1,'角色管理','Role'),
	(4,1,'模块管理','Fun'),
	(8,7,'留存统计','Liucun'),
	(7,0,'统计模块',''),
	(9,1,'服务器管理','Host');

/*!40000 ALTER TABLE `gm_func` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gm_host
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gm_host`;

CREATE TABLE `gm_host` (
  `id` int(11) NOT NULL COMMENT '服务器ID',
  `name` varchar(200) NOT NULL COMMENT '主机名称',
  `domain` varchar(200) DEFAULT NULL COMMENT '主机地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `gm_host` WRITE;
/*!40000 ALTER TABLE `gm_host` DISABLE KEYS */;

INSERT INTO `gm_host` (`id`, `name`, `domain`)
VALUES
	(11,'xxxxx','http://www.baidu.com');

/*!40000 ALTER TABLE `gm_host` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gm_permission
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gm_permission`;

CREATE TABLE `gm_permission` (
  `rid` int(11) NOT NULL,
  `fid` int(11) NOT NULL COMMENT '功能块',
  `c` tinyint(4) DEFAULT NULL,
  `u` tinyint(4) DEFAULT NULL,
  `d` tinyint(4) DEFAULT NULL,
  UNIQUE KEY `Idx_rf` (`rid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `gm_permission` WRITE;
/*!40000 ALTER TABLE `gm_permission` DISABLE KEYS */;

INSERT INTO `gm_permission` (`rid`, `fid`, `c`, `u`, `d`)
VALUES
	(1001,1,1,1,1),
	(1001,2,1,1,1),
	(1001,3,1,1,1),
	(1001,4,1,1,1),
	(1001,9,1,1,1),
	(1002,1,1,1,1),
	(1002,2,1,1,1),
	(1002,3,1,1,1),
	(1002,4,1,1,1),
	(1005,1,1,1,1),
	(1005,2,1,1,1),
	(1005,3,1,1,1),
	(1005,4,1,1,1),
	(1005,7,1,1,1),
	(1005,8,1,1,1);

/*!40000 ALTER TABLE `gm_permission` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gm_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gm_role`;

CREATE TABLE `gm_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `note` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `gm_role` WRITE;
/*!40000 ALTER TABLE `gm_role` DISABLE KEYS */;

INSERT INTO `gm_role` (`id`, `name`, `note`)
VALUES
	(1001,'高級管理員','管理员分配权限模块'),
	(1002,'管理员','普通业务管理员'),
	(1005,'test','test');

/*!40000 ALTER TABLE `gm_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gm_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gm_user`;

CREATE TABLE `gm_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `reg_time` int(11) NOT NULL,
  `visit_time` int(11) NOT NULL,
  `visit_ip` varchar(20) NOT NULL,
  `rid` int(11) DEFAULT NULL,
  `host` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Idx_Name` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `gm_user` WRITE;
/*!40000 ALTER TABLE `gm_user` DISABLE KEYS */;

INSERT INTO `gm_user` (`id`, `username`, `password`, `email`, `phone`, `active`, `reg_time`, `visit_time`, `visit_ip`, `rid`, `host`)
VALUES
	(1,'admin','e10adc3949ba59abbe56e057f20f883e','test@test.com','18010010010',1,1347261000,1377147449,'127.0.0.1',1001,1),
	(2,'glynn','e10adc3949ba59abbe56e057f20f883e','wgllive@163.com','',1,0,1348290687,'127.0.0.1',1002,0),
	(9,'test','c4ca4238a0b923820dcc509a6f75849b','13012819106','13012819106',1,0,0,'',1005,0);

/*!40000 ALTER TABLE `gm_user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
