CREATE DATABASE IF NOT EXISTS devtool DEFAULT CHARACTER SET utf8;
GRANT SELECT, INSERT, UPDATE, DELETE ON devtool.* TO devtool_root@localhost IDENTIFIED BY 'devtool_root';
GRANT SELECT, INSERT, UPDATE, DELETE ON devtool.* TO devtool_root@'%' IDENTIFIED BY 'devtool_root';
FLUSH PRIVILEGES;

USE devtool;

CREATE TABLE IF NOT EXISTS user (
id                          INT UNSIGNED NOT NULL,
name                        VARCHAR(255) NOT NULL, 
access_token                VARCHAR(255) NOT NULL,
updated                     TIMESTAMP NOT NULL,
created                     DATETIME NOT NULL,
PRIMARY KEY (id),
UNIQUE INDEX (name)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS code_pack (
id                          INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_id                     INT UNSIGNED NOT NULL,
path                        VARCHAR(255) NOT NULL,
title                       VARCHAR(255),
description                 VARCHAR(255),
updated                     TIMESTAMP NOT NULL,
created                     DATETIME NOT NULL,
PRIMARY KEY (id),
INDEX (user_id),
UNIQUE INDEX (path)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS code (
id                          INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_id                     INT UNSIGNED NOT NULL,
code_pack_id                INT UNSIGNED NOT NULL,
class                       VARCHAR(255) NOT NULL,
code                        LONGTEXT NOT NULL,
PRIMARY KEY (id),
INDEX (code_pack_id)
)ENGINE=InnoDB;
