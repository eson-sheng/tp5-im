-- 建立数据库
CREATE DATABASE `im` DEFAULT charset=utf8;

-- 测试表建立
DROP TABLE IF EXISTS `test`;
CREATE TABLE IF NOT EXISTS `test`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `title` VARCHAR(128) NOT NULL COMMENT '测试',
    `time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updata_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
    `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '状态(单选):0=未激活,1=激活',
    PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='测试表管理';

-- 用户表建立