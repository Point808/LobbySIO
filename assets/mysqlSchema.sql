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

-- --------------------------------------------------------

--
-- Table structure for table `lsio_idtypes`
--

CREATE TABLE `lsio_idtypes` (
  `id` tinyint(3) UNSIGNED NOT NULL COMMENT 'UNIQUE ID',
  `name` char(8) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'LANG FILE CODE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `lsio_idtypes`
--

INSERT INTO `lsio_idtypes` (`id`, `name`) VALUES
(3, 'PASSPORT'),
(2, 'STATEID'),
(1, 'UNAVAIL');

-- --------------------------------------------------------

--
-- Table structure for table `lsio_sites`
--

CREATE TABLE `lsio_sites` (
  `id` tinyint(3) UNSIGNED NOT NULL COMMENT 'UNIQUE ID',
  `name` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'LOCATION CODE',
  `timezone` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'ISO TIMEZONE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `lsio_sites`
--

INSERT INTO `lsio_sites` (`id`, `name`, `timezone`) VALUES
(1, 'NOSITE', 'UTC'),
(2, 'Default', 'America/New_York');
-- --------------------------------------------------------

--
-- Table structure for table `lsio_users`
--

CREATE TABLE `lsio_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `usertype` int(11) UNSIGNED NOT NULL,
  `timezone` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `lsio_users`
--

INSERT INTO `lsio_users` (`id`, `username`, `password`, `email`, `created`, `firstname`, `lastname`, `usertype`, `timezone`) VALUES
(1, 'admin', '$2a$08$E5C4MP0JtsTmjIDm1aksgOHoascvOVNinOKKxAImrSnwL0zkd9FxO', 'a@b.c', '2015-02-18 19:50:31', 'System', 'Administrator', 1, '');
-- --------------------------------------------------------

--
-- Table structure for table `lsio_usertypes`
--

CREATE TABLE `lsio_usertypes` (
  `id` tinyint(3) UNSIGNED NOT NULL COMMENT 'UNIQUE ID',
  `name` char(8) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'LANG FILE CODE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `lsio_usertypes`
--

INSERT INTO `lsio_usertypes` (`id`, `name`) VALUES
(1, 'ADMIN'),
(3, 'KIOSK'),
(2, 'USER');

-- --------------------------------------------------------

--
-- Table structure for table `lsio_visits`
--

CREATE TABLE `lsio_visits` (
  `id` int(15) UNSIGNED NOT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `escort` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `intime` datetime NOT NULL,
  `outtime` datetime DEFAULT NULL,
  `signature` blob,
  `escort_signature` blob,
  `citizen` tinyint(3) UNSIGNED DEFAULT NULL,
  `id_type` tinyint(3) UNSIGNED DEFAULT NULL,
  `id_checked` tinyint(3) UNSIGNED DEFAULT NULL,
  `initials` varchar(5) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `badge` varchar(15) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `site_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `reason` tinyint(3) UNSIGNED DEFAULT NULL,
  `approved` tinyint(4) DEFAULT '1' COMMENT '0 void, 1 unapproved, 2 approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


--
-- Table structure for table `lsio_visittypes`
--

CREATE TABLE `lsio_visittypes` (
  `id` tinyint(3) UNSIGNED NOT NULL COMMENT 'UNIQUE ID',
  `name` char(8) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'LANG FILE CODE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `lsio_visittypes`
--

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lsio_idtypes`
--
ALTER TABLE `lsio_idtypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `lsio_sites`
--
ALTER TABLE `lsio_sites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `lsio_users`
--
ALTER TABLE `lsio_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_ibfk_1` (`usertype`);

--
-- Indexes for table `lsio_usertypes`
--
ALTER TABLE `lsio_usertypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `lsio_visits`
--
ALTER TABLE `lsio_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_id` (`site_id`),
  ADD KEY `reason` (`reason`),
  ADD KEY `id_checked` (`id_checked`),
  ADD KEY `citizen` (`citizen`),
  ADD KEY `id_type` (`id_type`);

--
-- Indexes for table `lsio_visittypes`
--
ALTER TABLE `lsio_visittypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lsio_idtypes`
--
ALTER TABLE `lsio_idtypes`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'UNIQUE ID', AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `lsio_sites`
--
ALTER TABLE `lsio_sites`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'UNIQUE ID', AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `lsio_users`
--
ALTER TABLE `lsio_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `lsio_usertypes`
--
ALTER TABLE `lsio_usertypes`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'UNIQUE ID', AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `lsio_visits`
--
ALTER TABLE `lsio_visits`
  MODIFY `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `lsio_visittypes`
--
ALTER TABLE `lsio_visittypes`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'UNIQUE ID', AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
