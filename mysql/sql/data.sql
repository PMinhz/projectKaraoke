
CREATE DATABASE IF NOT EXISTS `cuoiky` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `cuoiky`;


CREATE TABLE `User` (
  `id_user` int PRIMARY KEY auto_increment NOT NULL,
  `fullname` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(120) NOT NULL,
  `id_role` varchar(128),
  `id_PhongBan` int, 
  `cmnd` int,
  `sdt` int,
  `email` varchar(128) NOT NULL,
  `address` varchar(128) NOT NULL,
  `active` boolean
);

CREATE TABLE `Role` (
  `id_role` varchar(128) NOT NULL,
  `chucnang` varchar(128) NOT NULL,
  `username` varchar(128) PRIMARY KEY NOT NULL,
  `id_permisson` boolean
);

-- CREATE TABLE `Phongban` (
--   `id_PhongBan` int PRIMARY KEY auto_increment NOT NULL,
--   `Ten_phongban` varchar(128) NOT NULL
-- );


