-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 21, 2011 at 06:06 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `informaster`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE IF NOT EXISTS `banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'Unique name of each banks',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Store all existing bank information.' AUTO_INCREMENT=50 ;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `created`, `updated`) VALUES
(1, 'Affin Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(2, 'Affin Islamic Bank Berhad', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(3, 'Alliance Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(4, 'Alliance Islamic Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(5, 'AmBank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(6, 'AmIslamic Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(7, 'Bank Central Asia (BCA)', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(8, 'Bank Indonesia', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(9, 'Bank Internasional Indonesia', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(10, 'Bank Islam', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(11, 'Bank Kerjasama Rakyat', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(12, 'Bank Mayapada Internasional', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(13, 'Bank Muamalat', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(14, 'Bank Negara Indonesia', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(15, 'Bank Rakyat Indonesia', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(16, 'Bank Simpanan Nasional Berhad', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(17, 'Bank Tabungan Negara', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(18, 'Bank of America', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(19, 'CIMB Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(20, 'CIMB Islamic Bank Berhad', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(21, 'Citibank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(22, 'Citibank Berhad', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(23, 'DBS Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(24, 'Deutsche Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(25, 'EON Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(26, 'EONCAP Islamic Bank Berhad', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(27, 'HSBC', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(30, 'Hong Leong Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(31, 'Hong Leong Islamic Bank Berhad', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(32, 'Maybank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(35, 'Maybank Islamic Berhad', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(36, 'OCBC Al-Amin Bank Berhad', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(37, 'OCBC Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(41, 'Public Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(43, 'RHB Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(44, 'RHB Islamic Bank Berhad', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(46, 'Standard Chartered Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(47, 'The Royal Bank of Scotland Berhad', '2010-05-15 18:22:31', '2010-05-15 18:22:31'),
(49, 'United Overseas Bank', '2010-05-15 18:22:31', '2010-05-15 18:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `brought_over_managements`
--

CREATE TABLE IF NOT EXISTS `brought_over_managements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sponsor_member_id` varchar(50) NOT NULL,
  `member_id` varchar(50) NOT NULL,
  `default_period_start` date NOT NULL,
  `default_period_until` date NOT NULL,
  `utilized` enum('Y','N') DEFAULT 'N',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sponsor_member_id` (`sponsor_member_id`),
  KEY `default_period_start` (`default_period_start`),
  KEY `default_period_until` (`default_period_until`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Manage sponsor whether he or she able to benefit from commis' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `brought_over_managements`
--

INSERT INTO `brought_over_managements` (`id`, `sponsor_member_id`, `member_id`, `default_period_start`, `default_period_until`, `utilized`, `created`, `updated`) VALUES
(1, '0107552525', '0107552526', '2011-05-22', '2011-06-21', 'N', '2011-06-21 17:13:08', '2011-06-21 17:13:08'),
(2, '0107552526', '0107552527', '2011-05-22', '2011-06-21', 'N', '2011-06-21 17:13:08', '2011-06-21 17:13:08'),
(3, '0107552527', '0107552528', '2011-05-22', '2011-06-21', 'N', '2011-06-21 17:13:08', '2011-06-21 17:13:08'),
(4, '0107552528', '0107552529', '2011-05-22', '2011-06-21', 'N', '2011-06-21 17:13:08', '2011-06-21 17:13:08'),
(5, '0107552529', '0107552530', '2011-05-22', '2011-06-21', 'N', '2011-06-21 17:13:08', '2011-06-21 17:13:08');

-- --------------------------------------------------------

--
-- Table structure for table `hierarchies`
--

CREATE TABLE IF NOT EXISTS `hierarchies` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `level_0` float NOT NULL COMMENT 'Direct Profit For A Member Once Downline Joined',
  `level_1` float NOT NULL,
  `level_2` float NOT NULL,
  `level_3` float NOT NULL,
  `level_4` float NOT NULL,
  `level_5` float NOT NULL,
  `level_6` float NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Store the basic configuration for hierarchy' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `hierarchies`
--

INSERT INTO `hierarchies` (`id`, `level_0`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`, `level_6`, `created`, `updated`) VALUES
(1, 15, 3.5, 2.5, 0.5, 0.5, 0.5, 0.5, '2010-06-27 11:44:36', '2010-06-27 05:38:30');

-- --------------------------------------------------------

--
-- Stand-in structure for view `hierarchy_managements`
--
CREATE TABLE IF NOT EXISTS `hierarchy_managements` (
`id` int(10) unsigned
,`sponsor_member_id` varchar(50)
,`member_id` varchar(50)
,`created` datetime
);
-- --------------------------------------------------------

--
-- Table structure for table `managements`
--

CREATE TABLE IF NOT EXISTS `managements` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'name of the insurances agent',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `managements`
--


-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'member''s unique id under table member',
  `sponsor_member_id` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `member_id` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'This is the policy/member id generated  by the insurans company',
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `gender` varchar(100) COLLATE utf8_bin NOT NULL,
  `nationality_id` bigint(20) NOT NULL,
  `new_ic_num` varchar(100) COLLATE utf8_bin NOT NULL,
  `birthday` date NOT NULL,
  `marital_status` varchar(100) COLLATE utf8_bin NOT NULL,
  `race` varchar(100) COLLATE utf8_bin NOT NULL,
  `address` varchar(200) COLLATE utf8_bin NOT NULL,
  `address_1` tinytext CHARACTER SET utf8 NOT NULL,
  `address_2` tinytext CHARACTER SET utf8 NOT NULL,
  `address_3` tinytext CHARACTER SET utf8 NOT NULL,
  `city` tinytext CHARACTER SET utf8 NOT NULL,
  `state` tinytext CHARACTER SET utf8 NOT NULL,
  `postal_code` tinytext CHARACTER SET utf8 NOT NULL,
  `contact_number_house` varchar(50) COLLATE utf8_bin NOT NULL,
  `contact_number_hp` varchar(50) COLLATE utf8_bin NOT NULL,
  `email` varchar(200) COLLATE utf8_bin NOT NULL,
  `language` varchar(100) COLLATE utf8_bin NOT NULL COMMENT 'This will stored member''s spoken language',
  `spouse_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `spouse_ic_num` varchar(50) COLLATE utf8_bin NOT NULL,
  `spouse_gender` varchar(100) COLLATE utf8_bin NOT NULL,
  `beneficiary_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `beneficiary_ic_num` varchar(50) COLLATE utf8_bin NOT NULL,
  `beneficiary_gender` varchar(100) COLLATE utf8_bin NOT NULL,
  `beneficiary_relationship` varchar(100) COLLATE utf8_bin NOT NULL,
  `beneficiary_address` varchar(200) COLLATE utf8_bin NOT NULL,
  `beneficiary_number_house` varchar(50) COLLATE utf8_bin NOT NULL,
  `beneficiary_number_hp` varchar(50) COLLATE utf8_bin NOT NULL,
  `bank_id` int(11) DEFAULT NULL COMMENT 'Foreign key to table bank',
  `bank_account_num` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `new_ic_num` (`new_ic_num`),
  KEY `sponsor_member_id` (`sponsor_member_id`),
  KEY `bank_id` (`bank_id`),
  KEY `nationality_id` (`nationality_id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Store all information regarding member' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `sponsor_member_id`, `member_id`, `name`, `gender`, `nationality_id`, `new_ic_num`, `birthday`, `marital_status`, `race`, `address`, `address_1`, `address_2`, `address_3`, `city`, `state`, `postal_code`, `contact_number_house`, `contact_number_hp`, `email`, `language`, `spouse_name`, `spouse_ic_num`, `spouse_gender`, `beneficiary_name`, `beneficiary_ic_num`, `beneficiary_gender`, `beneficiary_relationship`, `beneficiary_address`, `beneficiary_number_house`, `beneficiary_number_hp`, `bank_id`, `bank_account_num`, `created`, `updated`) VALUES
(1, 'P185952152', '0107552525', 'Ryan Roy', 'male', 1, '840913-14-6533', '1984-09-13', 'single', 'malay', '', '51', 'Jalan Limau Besar', 'Bangsar Park', 'Kuala Lumpur', 'Wilayah Persekutuan', '59000', '', '012-2608566', 'phpbug@gmail.com', 'english', '', '', 'male', '', '', 'male', '', '', '', '', NULL, '', '2011-06-21 00:00:00', '2011-06-21 16:45:08'),
(2, '0107552525', '0107552526', 'Ryan Roy', 'male', 1, '840913-14-6533', '1984-09-13', 'single', 'malay', '', '51', 'Jalan Limau Besar', 'Bangsar Park', 'Kuala Lumpur', 'Wilayah Persekutuan', '59000', '', '012-2608566', 'phpbug@gmail.com', 'english', '', '', 'male', '', '', 'male', '', '', '', '', NULL, '', '2011-06-21 00:00:00', '2011-06-21 16:53:48'),
(3, '0107552526', '0107552527', 'Ryan Roy', 'male', 1, '840913-14-6533', '1984-09-13', 'single', 'malay', '', '51', 'Jalan Limau Besar', 'Bangsar Park', 'Kuala Lumpur', 'Wilayah Persekutuan', '59000', '', '012-2608566', 'phpbug@gmail.com', 'english', '', '', 'male', '', '', 'male', '', '', '', '', NULL, '', '2011-06-21 00:00:00', '2011-06-21 16:55:47'),
(4, '0107552527', '0107552528', 'Ryan Roy', 'male', 1, '840913-14-6533', '1984-09-13', 'single', 'malay', '', '51', 'Jalan Limau Besar', 'Bangsar Park', 'Kuala Lumpur', 'Wilayah Persekutuan', '59000', '', '012-2608566', 'phpbug@gmail.com', 'english', '', '', 'male', '', '', 'male', '', '', '', '', NULL, '', '2011-06-21 00:00:00', '2011-06-21 16:56:45'),
(5, '0107552528', '0107552529', 'Ryan Roy', 'male', 1, '840913-14-6533', '1984-09-13', 'single', 'malay', '', '51', 'Jalan Limau Besar', 'Bangsar Park', 'Kuala Lumpur', 'Wilayah Persekutuan', '59000', '', '012-2608566', 'phpbug@gmail.com', 'english', '', '', 'male', '', '', 'male', '', '', '', '', NULL, '', '2011-06-21 00:00:00', '2011-06-21 16:57:28'),
(6, '0107552529', '0107552530', 'Ryan Roy', 'male', 1, '840913-14-6533', '1984-09-13', 'single', 'malay', '', '51', 'Jalan Limau Besar', 'Bangsar Park', 'Kuala Lumpur', 'Wilayah Persekutuan', '59000', '', '012-2608566', 'phpbug@gmail.com', 'english', '', '', 'male', '', '', 'male', '', '', '', '', NULL, '', '2011-06-21 00:00:00', '2011-06-21 16:58:10');

-- --------------------------------------------------------

--
-- Table structure for table `member_commissions`
--

CREATE TABLE IF NOT EXISTS `member_commissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `member_id` varchar(50) NOT NULL COMMENT 'This is the direct profit , of all upline are entitled to get it',
  `level_0` float NOT NULL COMMENT 'This is the direct profit/level 1 in hierachy table',
  `level_1` float NOT NULL,
  `level_2` float NOT NULL,
  `level_3` float NOT NULL,
  `level_4` float NOT NULL,
  `level_5` float NOT NULL,
  `level_6` float NOT NULL,
  `accumulated_profit` float NOT NULL,
  `group_sales_profit` float NOT NULL,
  `miscellaneous` float NOT NULL COMMENT 'miscellaneous etc...',
  `remark` text NOT NULL COMMENT 'comment on why misc is being deducted',
  `default_period_start` date NOT NULL COMMENT 'Period of time is set here in case admin change the time+date , able to  keep track',
  `default_period_until` date NOT NULL COMMENT 'Period of time is set here in case admin change the time+date , able to  keep track',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Commission of each sponsor earned' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `member_commissions`
--

INSERT INTO `member_commissions` (`id`, `member_id`, `level_0`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`, `level_6`, `accumulated_profit`, `group_sales_profit`, `miscellaneous`, `remark`, `default_period_start`, `default_period_until`, `created`, `updated`) VALUES
(1, '0107552525', 400, 200, 0, 0, 0, 0, 0, 0, 0, 0, '', '2011-05-22', '2011-06-21', '2011-06-21 17:54:45', '2011-06-21 17:54:45'),
(2, '0107552527', 200, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '2011-05-22', '2011-06-21', '2011-06-21 17:57:34', '2011-06-21 17:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `nationalities`
--

CREATE TABLE IF NOT EXISTS `nationalities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nationality` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Store all nationality' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `nationalities`
--

INSERT INTO `nationalities` (`id`, `nationality`, `created`, `updated`) VALUES
(1, 'malaysian', '2010-06-22 21:48:05', '2010-06-22 21:48:07'),
(2, 'singaporean', '2010-06-22 21:48:09', '2010-06-22 21:48:10'),
(3, 'indonesian', '2010-06-22 00:00:00', '2010-06-22 00:00:00'),
(4, 'brunei', '2010-06-22 00:00:00', '2010-06-22 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `paid_contributors`
--

CREATE TABLE IF NOT EXISTS `paid_contributors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(50) NOT NULL,
  `sponsor_member_id` varchar(50) NOT NULL,
  `member_id` varchar(50) NOT NULL,
  `default_period_start` date NOT NULL,
  `default_period_until` date NOT NULL,
  `target_month` date NOT NULL,
  `insurance_paid` float NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sponsor_member_id` (`sponsor_member_id`),
  KEY `member_id` (`member_id`),
  KEY `default_period_start` (`default_period_start`),
  KEY `default_period_until` (`default_period_until`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `paid_contributors`
--

INSERT INTO `paid_contributors` (`id`, `level`, `sponsor_member_id`, `member_id`, `default_period_start`, `default_period_until`, `target_month`, `insurance_paid`, `created`, `updated`) VALUES
(1, 'level_0', '0107552525', '0107552526', '2011-05-22', '2011-06-21', '2011-06-21', 100, '2011-06-21 17:54:45', '2011-06-21 17:54:45'),
(2, 'level_0', '0107552525', '0107552526', '2011-05-22', '2011-06-21', '2011-06-21', 100, '2011-06-21 17:56:41', '2011-06-21 17:56:41'),
(3, 'level_0', '0107552525', '0107552526', '2011-05-22', '2011-06-21', '2011-06-21', 100, '2011-06-21 17:57:34', '2011-06-21 17:57:34'),
(4, 'level_0', '0107552527', '0107552528', '2011-05-22', '2011-06-21', '2011-06-21', 100, '2011-06-21 17:57:34', '2011-06-21 17:57:34'),
(5, 'level_1', '0107552525', '0107552528', '2011-05-22', '2011-06-21', '2011-06-21', 100, '2011-06-21 17:57:34', '2011-06-21 17:57:34'),
(6, 'level_0', '0107552525', '0107552526', '2011-05-22', '2011-06-21', '2011-06-21', 100, '2011-06-21 18:03:35', '2011-06-21 18:03:35'),
(7, 'level_0', '0107552527', '0107552528', '2011-05-22', '2011-06-21', '2011-06-21', 100, '2011-06-21 18:03:35', '2011-06-21 18:03:35'),
(8, 'level_1', '0107552525', '0107552528', '2011-05-22', '2011-06-21', '2011-06-21', 100, '2011-06-21 18:03:35', '2011-06-21 18:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `pioneers`
--

CREATE TABLE IF NOT EXISTS `pioneers` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `member_id` varchar(50) DEFAULT NULL COMMENT 'This member id doesn''t link to table member, is a unique id to pioneer only',
  `username` varchar(200) NOT NULL COMMENT 'Store user emai',
  `password` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`),
  FULLTEXT KEY `user` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='To store only pioneer information.' AUTO_INCREMENT=12 ;

--
-- Dumping data for table `pioneers`
--

INSERT INTO `pioneers` (`id`, `member_id`, `username`, `password`, `created`, `updated`) VALUES
(1, 'P671310763', 'eddietan.ifm@gmail.com', '2b8fd8541a5faf512691635f5290493695745bef', '2010-11-30 13:28:23', '2011-05-30 12:37:00'),
(2, 'P784388014', 'eugene_6323@yahoo.com', '9aab54fe21ef0aee2671d91141b9c5a25d296371', '2010-12-12 14:33:25', '2011-05-30 12:32:33'),
(3, 'P908378048', 'mfadil_ifm@live.com', 'daa3008f5fbc0c4182b55d67421261628dc3592a', '2010-12-16 16:01:15', '2011-05-30 12:25:32'),
(11, 'P185952152', 'phpbug@gmail.com', '8ed893e234167433e19e945fd40c6bd942bdc365', '2011-05-07 11:50:46', '2011-05-30 10:59:16'),
(10, 'P104956276', 'lion9696_ifm@live.com', '45ac78359cf4471a3d1f805f116ee6801b7b1c8d', '2011-02-14 15:42:59', '2011-05-30 12:33:15');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 DELAY_KEY_WRITE=1 COMMENT='This table is to store the profile level' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `role`) VALUES
(1, 'superuser'),
(2, 'administrator'),
(3, 'member');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'sale''s unique id under table sale',
  `member_id` bigint(20) DEFAULT NULL COMMENT 'Unique Member Id to member table [ Candidate Key ]',
  `added_by` bigint(20) NOT NULL COMMENT 'If this is added by agent/administrator/superuser it only use user_id',
  `insurance_paid` float DEFAULT NULL,
  `total_payment` float NOT NULL COMMENT 'Total amount paid particularly that month',
  `target_month` date DEFAULT NULL COMMENT 'Member paid in which date',
  `default_period_start` date NOT NULL COMMENT 'To notify the admin , which period of time does the user pay off',
  `default_period_until` date NOT NULL COMMENT 'To notify the admin , which period of time does the user pay off',
  `payment_clear` enum('Y','N') DEFAULT 'N' COMMENT 'Flag to deter payment for member , whether have paid for the current month or not',
  `calculated` enum('Y','N') DEFAULT 'N' COMMENT 'Flag to deter whether calculated or not calculated',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `default_period_start` (`default_period_start`),
  KEY `default_period_until` (`default_period_until`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Store sales coming from a member\\''s downline' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `member_id`, `added_by`, `insurance_paid`, `total_payment`, `target_month`, `default_period_start`, `default_period_until`, `payment_clear`, `calculated`, `created`, `updated`) VALUES
(1, 1, 3, 100, 100, '2011-06-21', '2011-05-22', '2011-06-21', 'Y', 'Y', '2011-06-21 16:58:57', '2011-06-21 18:03:35'),
(2, 2, 3, 100, 100, '2011-06-21', '2011-05-22', '2011-06-21', 'Y', 'Y', '2011-06-21 16:59:13', '2011-06-21 18:03:35'),
(4, 4, 3, 100, 100, '2011-06-21', '2011-05-22', '2011-06-21', 'Y', 'Y', '2011-06-21 16:59:33', '2011-06-21 18:03:35'),
(5, 5, 3, 100, 100, '2011-06-21', '2011-05-22', '2011-06-21', 'Y', 'N', '2011-06-21 16:59:45', '2011-06-21 17:48:40'),
(6, 6, 3, 100, 100, '2011-06-21', '2011-05-22', '2011-06-21', 'Y', 'N', '2011-06-21 17:00:00', '2011-06-21 17:48:40');

-- --------------------------------------------------------

--
-- Table structure for table `sales_settings`
--

CREATE TABLE IF NOT EXISTS `sales_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `default_start_date` varchar(50) NOT NULL,
  `default_until_date` varchar(50) NOT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Setting for commision to be calculated' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sales_settings`
--

INSERT INTO `sales_settings` (`id`, `default_start_date`, `default_until_date`, `updated`, `created`) VALUES
(1, '22', '21', '2010-08-09 12:10:03', '2010-07-29 15:14:28');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `field` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Store all system settings' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `system_settings`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) DEFAULT NULL COMMENT 'Foreign Key To Member',
  `profile_id` bigint(11) DEFAULT NULL,
  `username` varchar(200) NOT NULL COMMENT '''Store user emai',
  `password` varchar(200) NOT NULL,
  `secret_key` varchar(200) NOT NULL COMMENT 'Key to deter unique user from email activation',
  `allow` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`),
  FULLTEXT KEY `user` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='To store admin information' AUTO_INCREMENT=55 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `member_id`, `profile_id`, `username`, `password`, `secret_key`, `allow`, `created`, `updated`) VALUES
(3, 33, 1, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', 1, '2010-06-14 01:55:03', '2010-06-14 01:55:06'),
(15, 355, 3, '', '8cb2237d0679ca88db6464eac60da96345513964', '', 1, '2011-04-11 21:52:35', '2011-04-11 21:52:35'),
(45, 500, 3, '', '8ed893e234167433e19e945fd40c6bd942bdc365', '', 1, '2011-05-31 18:04:01', '2011-05-31 18:04:01'),
(17, 501, 3, '', 'ee8815c1546bf421a1c94364cb562887eed7d9ee', '', 1, '2011-05-04 18:16:47', '2011-05-04 18:16:47'),
(18, 1, 3, '', '0ffdf66ce2641587e2819c935dee14648fee0f25', '', 1, '2011-05-04 19:28:34', '2011-05-04 19:28:34'),
(19, 3, 3, '', '071e045ee5a5eb6b67033bd31951a145dc996eb0', '', 1, '2011-05-08 15:15:36', '2011-05-08 15:15:36'),
(20, 400, 3, '', '85a781cea06be0aa7e6ed9b5305ac337993f539e', '', 1, '2011-05-08 21:43:02', '2011-05-08 21:43:02'),
(21, 309, 3, '', '24a0ed6b22a03ff80e64a800df7c718458e3624a', '', 1, '2011-05-09 00:58:18', '2011-05-09 00:58:18'),
(22, 457, 3, '', 'dc1beee16e1bdc89fe7029c622d3912d6d2418ce', '', 1, '2011-05-09 12:24:05', '2011-05-09 12:24:05'),
(23, 142, 3, '', '0363ea5eee95f308a26cd4577a62560a25ae1e74', '', 1, '2011-05-09 15:16:25', '2011-05-09 15:16:25'),
(24, 492, 3, '', '313b7d229813e7aea310d48aa0f13638b4027c2b', '', 1, '2011-05-09 16:32:38', '2011-05-09 16:32:38'),
(25, 416, 3, '', '441d0cee4d25d3597309840ccb8f71b51a3fbc86', '', 1, '2011-05-09 20:29:28', '2011-05-09 20:29:28'),
(26, 223, 3, '', '8417799d6f652f799e3c93a61353085384cfd1e7', '', 1, '2011-05-10 21:04:11', '2011-05-10 21:04:11'),
(27, 498, 3, '', 'e3d78f3d4a5e6e334a5a8e279ad1c61b3c9e4d04', '', 1, '2011-05-10 21:35:31', '2011-05-10 21:35:31'),
(28, 466, 3, '', 'e9e8be8f69bb99844ef566b6cb6e99c27bbbcea9', '', 1, '2011-05-12 23:26:36', '2011-05-12 23:26:36'),
(29, 434, 3, '', '41ddd7be6492263aba715da18a40071ecba3e576', '', 1, '2011-05-16 15:03:17', '2011-05-16 15:03:17'),
(30, 311, 3, '', '37e76eefdcfeaae824a67ed3c4cc41dec5ff5dd4', '', 1, '2011-05-16 16:17:42', '2011-05-16 16:17:42'),
(31, 473, 3, '', 'dc0563eb88b9644fe5f636d1920f7bd4553ced12', '', 1, '2011-05-16 17:28:42', '2011-05-16 17:28:42'),
(32, 461, 3, '', 'ad7e767f617920ea828e79cf477078cbc970c9b8', '', 1, '2011-05-18 00:27:35', '2011-05-18 00:27:35'),
(33, 462, 3, '', '0d66feeb050d6ef7ae27f703778bfab4e7d09dfb', '', 1, '2011-05-19 09:51:45', '2011-05-19 09:51:45'),
(34, 432, 3, '', '45371ed53fbc2739371a388986075f13451bc3f0', '', 1, '2011-05-19 17:14:47', '2011-05-19 17:14:47'),
(35, 454, 3, '', '50326c58ba4f62a541f45653f504062e4d4d69b7', '', 1, '2011-05-19 21:08:10', '2011-05-19 21:08:10'),
(36, 450, 3, '', '01e116be98600aa7f7735cebe666ff4be920f9df', '', 1, '2011-05-20 20:22:54', '2011-05-20 20:22:54'),
(37, 468, 3, '', '86316aec7554d30b94c6e72251d99b36c43ffc4a', '', 1, '2011-05-21 11:27:46', '2011-05-21 11:27:46'),
(38, 485, 3, '', '6045752cce50890d309d8e46f8ac130194324d13', '', 1, '2011-05-23 11:36:46', '2011-05-23 11:36:46'),
(39, 495, 3, '', 'f59e804887d5d84dc7a26d6ff08d268166700702', '', 1, '2011-05-24 15:27:13', '2011-05-24 15:27:13'),
(40, 513, 3, '', '53c7412d44682794a7bde1e038cf04968182f29c', '', 1, '2011-05-26 09:20:34', '2011-05-26 09:20:34'),
(41, 510, 3, '', 'ff758770fb5f784ad0d6e89cf9ba4feec4271451', '', 1, '2011-05-26 20:18:46', '2011-05-26 20:18:46'),
(42, 512, 3, '', 'ca930c5aa38f954ecfc5e64be8c1274fea518e12', '', 1, '2011-05-27 16:38:49', '2011-05-27 16:38:49'),
(43, 520, 3, '', '2f8d83f7d726dea50af7413eb840744b3eddadcc', '', 1, '2011-05-28 19:47:31', '2011-05-28 19:47:31'),
(44, 477, 3, '', '7670d17881e853f4a983f0b61eed7b7328d35526', '', 1, '2011-05-30 14:57:09', '2011-05-30 14:57:09'),
(46, 484, 3, '', '58446d703483aa7c199782b28c9073159598d4c0', '', 1, '2011-05-31 19:50:19', '2011-05-31 19:50:19'),
(47, 195, 3, '', 'cc899cbe34736e1bec5906c22940748f39ba0942', '', 1, '2011-05-31 21:34:43', '2011-05-31 21:34:43'),
(48, 533, 3, '', '0ff077fc406c63ca88cf66256a0e2facd6f2c07e', '', 1, '2011-06-01 21:45:22', '2011-06-01 21:45:22'),
(49, 509, 3, '', '17c75e4f2ae72f0cd9de71626bfd1f3c751d2fd9', '', 1, '2011-06-01 22:12:35', '2011-06-01 22:12:35'),
(50, 9, 3, '', 'e486ced7a3642bac22e53c639a448f79228c974a', '', 1, '2011-06-06 15:59:18', '2011-06-06 15:59:18'),
(51, 262, 3, '', '863c72eef59dbc9af64066d577c5e8899186262d', '', 1, '2011-06-07 14:09:31', '2011-06-07 14:09:31'),
(52, 535, 3, '', '47805d9c41e1fc937d58eaf735eb865d17e5f350', '', 1, '2011-06-14 21:45:31', '2011-06-14 21:45:31'),
(53, 542, 3, '', '9ae6dc036fce5a53cd8009904a8ede3e68751d03', '', 1, '2011-06-14 23:00:16', '2011-06-14 23:00:16'),
(54, 547, 3, '', 'bf1bcc4da5a1df15be98f852cd2738c74c658937', '', 1, '2011-06-17 10:30:29', '2011-06-17 10:30:29');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_hierarchy_management_reports`
--
CREATE TABLE IF NOT EXISTS `view_hierarchy_management_reports` (
`id` int(10) unsigned
,`sponsor_member_id` varchar(50)
,`sponsor_name` varchar(100)
,`downline` bigint(21)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_member_reports`
--
CREATE TABLE IF NOT EXISTS `view_member_reports` (
`outer_member_id` varchar(50)
,`name` varchar(100)
,`bank_name` varchar(50)
,`bank_account_num` varchar(50)
,`direct_profit` float
,`default_period_start` date
,`default_period_until` date
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_sale_reports`
--
CREATE TABLE IF NOT EXISTS `view_sale_reports` (
`id` bigint(20)
,`member_id` varchar(50)
,`child_name` varchar(100)
,`sponsor_member_id` varchar(50)
,`parent_name` varchar(100)
,`bank_name` varchar(50)
,`bank_account_num` varchar(50)
,`insurance_paid` float
,`total_payment` float
,`target_month` date
,`payment_clear` enum('Y','N')
,`calculated` enum('Y','N')
,`default_period_start` date
,`default_period_until` date
);
-- --------------------------------------------------------

--
-- Structure for view `hierarchy_managements`
--
DROP TABLE IF EXISTS `hierarchy_managements`;

CREATE VIEW `hierarchy_managements` AS select `m`.`id` AS `id`,`m`.`sponsor_member_id` AS `sponsor_member_id`,`m`.`member_id` AS `member_id`,`m`.`created` AS `created` from `members` `m` where ((`m`.`sponsor_member_id` <> '') and (`m`.`member_id` <> ''));

-- --------------------------------------------------------

--
-- Structure for view `view_hierarchy_management_reports`
--
DROP TABLE IF EXISTS `view_hierarchy_management_reports`;

CREATE VIEW `view_hierarchy_management_reports` AS select `h`.`id` AS `id`,`h`.`sponsor_member_id` AS `sponsor_member_id`,(select `members`.`name` AS `name` from `members` where (`members`.`member_id` = `h`.`sponsor_member_id`)) AS `sponsor_name`,count(`h`.`member_id`) AS `downline` from `hierarchy_managements` `h` where (`h`.`sponsor_member_id` in (select distinct `hierarchy_managements`.`sponsor_member_id` AS `sponsor_member_id` from `hierarchy_managements`) and (`h`.`sponsor_member_id` <> '')) group by `h`.`sponsor_member_id`;

-- --------------------------------------------------------

--
-- Structure for view `view_member_reports`
--
DROP TABLE IF EXISTS `view_member_reports`;

CREATE VIEW `view_member_reports` AS select `member_commissions`.`member_id` AS `outer_member_id`,(select `members`.`name` AS `name` from `members` where (`members`.`member_id` = `member_commissions`.`member_id`)) AS `name`,(select `banks`.`name` AS `bank_name` from (`banks` left join `members` on((`members`.`bank_id` = `banks`.`id`))) where (`members`.`member_id` = `member_commissions`.`member_id`)) AS `bank_name`,(select `members`.`bank_account_num` AS `bank_account_num` from `members` where (`members`.`member_id` = `member_commissions`.`member_id`)) AS `bank_account_num`,`member_commissions`.`level_0` AS `direct_profit`,`member_commissions`.`default_period_start` AS `default_period_start`,`member_commissions`.`default_period_until` AS `default_period_until` from `member_commissions`;

-- --------------------------------------------------------

--
-- Structure for view `view_sale_reports`
--
DROP TABLE IF EXISTS `view_sale_reports`;

CREATE VIEW `view_sale_reports` AS select `s`.`id` AS `id`,`m`.`member_id` AS `member_id`,`m`.`name` AS `child_name`,`m`.`sponsor_member_id` AS `sponsor_member_id`,if((isnull(`m`.`sponsor_member_id`) or (`m`.`sponsor_member_id` = '')),'',(select `members`.`name` AS `name` from `members` where (`members`.`member_id` = `m`.`sponsor_member_id`) limit 1)) AS `parent_name`,(select `banks`.`name` AS `name` from `banks` where (`banks`.`id` = `m`.`bank_id`) limit 1) AS `bank_name`,`m`.`bank_account_num` AS `bank_account_num`,`s`.`insurance_paid` AS `insurance_paid`,`s`.`total_payment` AS `total_payment`,`s`.`target_month` AS `target_month`,`s`.`payment_clear` AS `payment_clear`,`s`.`calculated` AS `calculated`,`s`.`default_period_start` AS `default_period_start`,`s`.`default_period_until` AS `default_period_until` from (`sales` `s` left join `members` `m` on((`m`.`id` = `s`.`member_id`)));
