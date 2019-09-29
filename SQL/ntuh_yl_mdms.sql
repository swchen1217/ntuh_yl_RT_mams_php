-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2019-09-29 05:31:42
-- 伺服器版本： 10.1.40-MariaDB
-- PHP 版本： 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `ntuh.yl_mdms`
--

-- --------------------------------------------------------

--
-- 資料表結構 `device_tb`
--

CREATE TABLE `device_tb` (
  `DID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `LastModified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `device_tb`
--

INSERT INTO `device_tb` (`DID`, `category`, `model`, `number`, `user`, `position`, `status`, `LastModified`) VALUES
('MDMS.D0001', 'C2', 'a', 'a01', '-', '*3N', '2', '2019-09-15 13:51:52'),
('MDMS.D0003', 'C3', 'a', 'a02', '456', 'I-02', '-1', '2019-07-15 15:03:00'),
('MDMS.D0005', 'C3', 'a', 'a05', '6764845', 'E8-876', '1', '2019-08-28 21:44:32'),
('MDMS.D0006', 'C5', 'b', 'b01', '-', '*5A', '2', '2019-08-28 23:48:12'),
('MDMS.D0008', 'C6', 'c', 'c01', '1616979', 'Y2-568', '1', '2019-08-28 01:29:40'),
('MDMS.D0007', '', '', '', '', '', '-1', '2019-08-28 21:30:00'),
('MDMS.D0010', 'C8', 'e', 'e04', '', '', '-1', '2019-08-28 21:38:00');

-- --------------------------------------------------------

--
-- 資料表結構 `position_item_tb`
--

CREATE TABLE `position_item_tb` (
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `item` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `position_item_tb`
--

INSERT INTO `position_item_tb` (`type`, `item`) VALUES
('2AICU', '01'),
('2AICU', '02'),
('2BICU', '27'),
('2BICU', '28'),
('3NICU', '01'),
('3NICU', '02'),
('NICU', '01'),
('NICU', '02'),
('2AICU', '03'),
('2AICU', '05'),
('2AICU', '06'),
('2AICU', '07'),
('2AICU', '08'),
('2AICU', '09'),
('2AICU', '10'),
('2AICU', '11'),
('2AICU', '12'),
('2AICU', '13'),
('2AICU', '15'),
('2AICU', '16'),
('2AICU', '17'),
('2AICU', '18'),
('2AICU', '19'),
('2AICU', '20'),
('2AICU', '21'),
('2AICU', '22'),
('2AICU', '23'),
('2AICU', '25'),
('2AICU', '26'),
('2AICU', '01'),
('2AICU', '51'),
('2AICU', '52'),
('2AICU', '53'),
('2AICU', '55'),
('2AICU', '56'),
('2AICU', '57'),
('2BICU', '27'),
('2BICU', '28'),
('2BICU', '29'),
('2BICU', '30'),
('2BICU', '31'),
('2BICU', '32'),
('2BICU', '33'),
('2BICU', '35'),
('2BICU', '36'),
('2BICU', '37');

-- --------------------------------------------------------

--
-- 資料表結構 `system_tb`
--

CREATE TABLE `system_tb` (
  `id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `system_tb`
--

INSERT INTO `system_tb` (`id`, `value`) VALUES
('position_item_tb_LastModified', '2019-07-19 21:45:00');

-- --------------------------------------------------------

--
-- 資料表結構 `user_tb`
--

CREATE TABLE `user_tb` (
  `account` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `permission` int(30) NOT NULL,
  `email` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `user_tb`
--

INSERT INTO `user_tb` (`account`, `password`, `name`, `permission`, `email`, `created`) VALUES
('admin', 'c7bcf309dc0573c824ce89a3379ee942', '管理員', 9, 'admin@gmail.com', '2019-09-20 12:34:56'),
('swchen1217', '2b10c420ba6d38e3bd164ae6623935e4', '陳思惟', 2, 'swchen1217@gmail.com', '2019-09-28 00:00:00'),
('user1', 'a55424f743a51fe0e9316ed10d7e72fe', 'user1', 1, 'user@gmail.com', '2019-09-01 01:23:45');

-- --------------------------------------------------------

--
-- 資料表結構 `user_tmppw_tb`
--

CREATE TABLE `user_tmppw_tb` (
  `account` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `tmppw` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `application_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
