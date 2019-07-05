-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- 主機: 127.0.0.1
-- 產生時間： 2019-07-05 09:22:00
-- 伺服器版本: 10.1.36-MariaDB
-- PHP 版本： 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `onlineweb_sql`
--

-- --------------------------------------------------------

--
-- 資料表結構 `onliine_firstplant_data`
--

CREATE TABLE `onliine_firstplant_data` (
  `onfp_sn` int(11) NOT NULL,
  `onfp_add_date` int(11) NOT NULL,
  `onfp_status` tinyint(3) NOT NULL DEFAULT '1',
  `onfp_part_no` varchar(255) NOT NULL COMMENT '品號',
  `onfp_plant_date` int(11) NOT NULL,
  `jsuser_sn` int(11) NOT NULL,
  `onfp_plant_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `onliine_firstplant_data`
--

INSERT INTO `onliine_firstplant_data` (`onfp_sn`, `onfp_add_date`, `onfp_status`, `onfp_part_no`, `onfp_plant_date`, `jsuser_sn`, `onfp_plant_amount`) VALUES
(5, 1561628157, 1, '0', 1561651200, 0, 9999),
(6, 1561942465, 1, '32', 1559577600, 0, 32),
(7, 1562308531, 1, '0', 1562256000, 0, 1000),
(8, 1562308778, 1, '0', 1562256000, 0, 1000),
(9, 1562308920, 1, '0', 1562256000, 0, 5000);

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `onliine_firstplant_data`
--
ALTER TABLE `onliine_firstplant_data`
  ADD PRIMARY KEY (`onfp_sn`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `onliine_firstplant_data`
--
ALTER TABLE `onliine_firstplant_data`
  MODIFY `onfp_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
