# ************************************************************
# Sequel Ace SQL dump
# 版本号： 20062
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# 主机: 127.0.0.1 (MySQL 8.0.32)
# 数据库: secret_space
# 生成时间: 2024-01-08 02:39:29 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# 转储表 content
# ------------------------------------------------------------

CREATE TABLE `content` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL COMMENT '用户 ID',
  `secret_id` bigint unsigned NOT NULL COMMENT '密码 ID',
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(10240) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内容',
  `type` int unsigned NOT NULL DEFAULT '0' COMMENT '类型 0 文本 1 音频 2 视频',
  `created_at` datetime NOT NULL DEFAULT '2023-01-01 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '2023-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `INDEX_SECRET_ID` (`secret_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;

INSERT INTO `content` (`id`, `user_id`, `secret_id`, `title`, `content`, `type`, `created_at`, `updated_at`)
VALUES
	(1,186864216584192,1,'Hello','eyJpdiI6ImdWRTlzRVJPWHNGZUFSQnVjMjdRNGc9PSIsInZhbHVlIjoiV1NEQ1BTNkplNG9waHlNM2FlTHUvQT09IiwibWFjIjoiYWM5YjViYTk4YjAxZTAyOGNkMDE2NjRkZDI4Mzg5MTJjYjI5MDc3ZWMyYzg1ZWI1Nzg0NTg1OGE4Yzc1NWFkMCJ9',0,'2023-12-30 18:16:32','2024-01-07 08:44:23'),
	(2,186864216584192,1,'你好','eyJpdiI6IkJnR3JMS0dITDJjbmYyYjZQWk5BV3c9PSIsInZhbHVlIjoiclpqTGd5cmdkS3VYVVRmdStWYXNnQT09IiwibWFjIjoiMWU4YmFhNWZiZTI1Yjk2MjcyNDEzYTFmZmMxNDlhY2I0NTYwZTk2OThiMTE4ZWVmNzFkNzc0Yjc1NWYxYTViYSJ9',0,'2023-12-30 21:35:10','2023-12-30 21:35:10');

/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 secret
# ------------------------------------------------------------

CREATE TABLE `secret` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL COMMENT '用户 ID',
  `secret` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `created_at` datetime NOT NULL DEFAULT '2023-01-01 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '2023-01-01 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_USER_SECRET` (`user_id`,`secret`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `secret` WRITE;
/*!40000 ALTER TABLE `secret` DISABLE KEYS */;

INSERT INTO `secret` (`id`, `user_id`, `secret`, `created_at`, `updated_at`)
VALUES
	(1,186864216584192,'e9510081ac30ffa83f10b68cde1cac07','2023-12-30 17:08:44','2023-12-30 17:08:44'),
	(10,186864216584192,'202cb962ac59075b964b07152d234b70','2023-12-30 20:59:02','2023-12-30 20:59:02'),
	(12,186864216584192,'827ccb0eea8a706c4c34a16891f84e7b','2023-12-30 21:38:16','2023-12-30 21:38:16'),
	(15,186864216584192,'81dc9bdb52d04dc20036dbd8313ed055','2024-01-07 08:44:23','2024-01-07 08:44:23');

/*!40000 ALTER TABLE `secret` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 user
# ------------------------------------------------------------

CREATE TABLE `user` (
  `id` bigint unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '2023-01-01 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '2023-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `created_at`, `updated_at`)
VALUES
	(186864216584192,'2024-01-07 08:39:35','2024-01-07 08:39:35');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
