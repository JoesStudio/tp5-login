SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `###admin`;
CREATE TABLE `###admin` (
  `admin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '用户密码',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `status` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态0禁用1正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '账号创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '信息更新时间',
  `delete_time` int(10) unsigned NULL COMMENT '软删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_name`,`mobile`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表'