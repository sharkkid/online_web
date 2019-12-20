-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2019-12-20 05:33:54
-- 伺服器版本： 10.1.38-MariaDB
-- PHP 版本： 5.6.40

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
-- 資料表結構 `onliine_cost_table`
--

CREATE TABLE `onliine_cost_table` (
  `oncost_sn` int(11) NOT NULL,
  `oncost_status` int(1) NOT NULL DEFAULT '1' COMMENT '啟用狀態',
  `oncost_name` varchar(128) CHARACTER SET utf8 NOT NULL COMMENT '大表名稱',
  `oncost_note` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT '大表說明'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 傾印資料表的資料 `onliine_cost_table`
--

INSERT INTO `onliine_cost_table` (`oncost_sn`, `oncost_status`, `oncost_name`, `oncost_note`) VALUES
(1, 1, '苗種', ''),
(2, 1, '瓶苗', '');

-- --------------------------------------------------------

--
-- 資料表結構 `online_cost_data`
--

CREATE TABLE `online_cost_data` (
  `oncoda_sn` int(10) UNSIGNED NOT NULL,
  `oncost_sn` int(128) NOT NULL COMMENT 'FK_屬於哪個大表',
  `oncoda_add_date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `oncoda_mod_date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `oncoda_status` tinyint(3) NOT NULL DEFAULT '1',
  `oncoda_name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '項目名稱',
  `oncoda_unit` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '單位',
  `oncoda_cost` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '成本花費',
  `oncoda_note` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '成本細項說明',
  `oncoda_num` decimal(64,0) NOT NULL COMMENT '數量'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 傾印資料表的資料 `online_cost_data`
--

INSERT INTO `online_cost_data` (`oncoda_sn`, `oncost_sn`, `oncoda_add_date`, `oncoda_mod_date`, `oncoda_status`, `oncoda_name`, `oncoda_unit`, `oncoda_cost`, `oncoda_note`, `oncoda_num`) VALUES
(2, 1, 0, 0, 1, '水草', '包', '100', '', '1'),
(3, 1, 0, 0, 1, '人力', '位', '25000', '', '2'),
(4, 2, 0, 0, 1, '人力', '位', '50000', '', '3');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `onliine_cost_table`
--
ALTER TABLE `onliine_cost_table`
  ADD PRIMARY KEY (`oncost_sn`);

--
-- 資料表索引 `online_cost_data`
--
ALTER TABLE `online_cost_data`
  ADD PRIMARY KEY (`oncoda_sn`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `onliine_cost_table`
--
ALTER TABLE `onliine_cost_table`
  MODIFY `oncost_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `online_cost_data`
--
ALTER TABLE `online_cost_data`
  MODIFY `oncoda_sn` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
