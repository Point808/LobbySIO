/* 
 * Copyright (C) 2018 josh.north
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Author:  josh.north
 * Created: Oct 15, 2018
 */

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

DROP TABLE IF EXISTS `lsio_idtypes`;
CREATE TABLE `lsio_idtypes` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'UNIQUE ID',
  `name` char(8) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'LANG FILE CODE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
INSERT INTO `lsio_idtypes` (`id`, `name`) VALUES
(3, 'PASSPORT'),
(2, 'STATEID'),
(1, 'UNAVAIL');
-- --------------------------------------------------------
DROP TABLE IF EXISTS `lsio_sites`;
CREATE TABLE `lsio_sites` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'UNIQUE ID',
  `name` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'LOCATION CODE',
  `timezone` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'ISO TIMEZONE',
  `region` varchar(8) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'US, CAN, EMEA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
INSERT INTO `lsio_sites` (`id`, `name`, `timezone`, `region`) VALUES
(1, 'NOSITE', 'UTC', 'NO'),
(2, 'Default Site', 'America/New_York', 'US');
-- --------------------------------------------------------
DROP TABLE IF EXISTS `lsio_users`;
CREATE TABLE `lsio_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `usertype` int(10) UNSIGNED NOT NULL,
  `timezone` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
INSERT INTO `lsio_users` (`id`, `username`, `password`, `email`, `created`, `firstname`, `lastname`, `usertype`, `timezone`) VALUES
(1, 'admin', '$2a$08$FW0JtSQUEBXxf9aNDioIqeH/FA.ydCPTkgKUZEEWPECQpxwlRxZA.', 'admin@domain.com', '2015-02-18 19:50:31', 'System', 'Administrator', 1, ''),
(2, 'KIOSK', '', '', '2018-10-19 00:00:00', '', '', 3, ''),
(3, 'Default User', '$2a$08$FW0JtSQUEBXxf9aNDioIqeH/FA.ydCPTkgKUZEEWPECQpxwlRxZA.', 'user1@domain.com', '2018-09-23 00:00:00', 'First', 'Last', 2, '');
-- --------------------------------------------------------
DROP TABLE IF EXISTS `lsio_users_sites`;
CREATE TABLE `lsio_users_sites` (
  `sites_id` int(10) UNSIGNED NOT NULL COMMENT 'SITE ID',
  `users_id` int(10) UNSIGNED NOT NULL COMMENT 'USER ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='PERMISSIONS LINK TABLE';
INSERT INTO `lsio_users_sites` (`sites_id`, `users_id`) VALUES
(1, 1),
(2, 1),
(1, 2),
(2, 2),
(1, 3),
(2, 3);
-- --------------------------------------------------------
DROP TABLE IF EXISTS `lsio_usertypes`;
CREATE TABLE `lsio_usertypes` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'UNIQUE ID',
  `name` char(8) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'LANG FILE CODE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
INSERT INTO `lsio_usertypes` (`id`, `name`) VALUES
(1, 'ADMIN'),
(3, 'KIOSK'),
(4, 'SADMIN'),
(2, 'USER');
-- --------------------------------------------------------
DROP TABLE IF EXISTS `lsio_visits`;
CREATE TABLE `lsio_visits` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `escort` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `intime` datetime NOT NULL,
  `outtime` datetime DEFAULT NULL,
  `signature` blob,
  `escort_signature` blob,
  `citizen` tinyint(3) UNSIGNED DEFAULT NULL,
  `id_type` int(10) UNSIGNED DEFAULT NULL,
  `id_checked` tinyint(3) UNSIGNED DEFAULT NULL,
  `initials` varchar(5) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `badge` varchar(15) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `site_id` int(10) UNSIGNED DEFAULT NULL,
  `reason` int(10) UNSIGNED DEFAULT NULL,
  `approved` tinyint(4) DEFAULT '1' COMMENT '0 void, 1 unapproved, 2 approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
-- --------------------------------------------------------
DROP TABLE IF EXISTS `lsio_visittypes`;
CREATE TABLE `lsio_visittypes` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'UNIQUE ID',
  `name` char(8) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'LANG FILE CODE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
INSERT INTO `lsio_visittypes` (`id`, `name`) VALUES
(2, 'ADDEQPT'),
(7, 'INSTHARD'),
(8, 'INSTSOFT'),
(9, 'MAINHARD'),
(10, 'MAINSOFT'),
(4, 'MEETING'),
(1, 'NONEAVA'),
(3, 'REMEQPT'),
(6, 'TESTING'),
(5, 'TOUR');
-- --------------------------------------------------------
ALTER TABLE `lsio_idtypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);
ALTER TABLE `lsio_sites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);
ALTER TABLE `lsio_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_ibfk_1` (`usertype`);
ALTER TABLE `lsio_users_sites`
  ADD UNIQUE KEY `user_site_perm` (`sites_id`,`users_id`) USING BTREE,
  ADD KEY `users_id` (`users_id`);
ALTER TABLE `lsio_usertypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);
ALTER TABLE `lsio_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_id` (`site_id`),
  ADD KEY `reason` (`reason`),
  ADD KEY `id_checked` (`id_checked`),
  ADD KEY `citizen` (`citizen`),
  ADD KEY `id_type` (`id_type`);
ALTER TABLE `lsio_visittypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);
-- --------------------------------------------------------
ALTER TABLE `lsio_idtypes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'UNIQUE ID', AUTO_INCREMENT=100;
ALTER TABLE `lsio_sites`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'UNIQUE ID', AUTO_INCREMENT=100;
ALTER TABLE `lsio_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
ALTER TABLE `lsio_usertypes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'UNIQUE ID', AUTO_INCREMENT=100;
ALTER TABLE `lsio_visits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
ALTER TABLE `lsio_visittypes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'UNIQUE ID', AUTO_INCREMENT=11;
-- --------------------------------------------------------
ALTER TABLE `lsio_users`
  ADD CONSTRAINT `lsio_users_ibfk_1` FOREIGN KEY (`usertype`) REFERENCES `lsio_usertypes` (`id`);
ALTER TABLE `lsio_users_sites`
  ADD CONSTRAINT `lsio_users_sites_ibfk_3` FOREIGN KEY (`sites_id`) REFERENCES `lsio_sites` (`id`),
  ADD CONSTRAINT `lsio_users_sites_ibfk_4` FOREIGN KEY (`users_id`) REFERENCES `lsio_users` (`id`);
ALTER TABLE `lsio_visits`
  ADD CONSTRAINT `lsio_visits_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `lsio_idtypes` (`id`),
  ADD CONSTRAINT `lsio_visits_ibfk_2` FOREIGN KEY (`reason`) REFERENCES `lsio_visittypes` (`id`),
  ADD CONSTRAINT `lsio_visits_ibfk_3` FOREIGN KEY (`site_id`) REFERENCES `lsio_sites` (`id`);
