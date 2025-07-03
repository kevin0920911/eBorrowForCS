-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2023-12-28 14:23:42
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `ncyucsie`
--

-- --------------------------------------------------------

--
-- 資料表結構 `block`
--

CREATE TABLE `block` (
  `usertype` varchar(5) NOT NULL,
  `serviceTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `block`
--

INSERT INTO `block` (`usertype`, `serviceTime`) VALUES
('one', '2023-12-27 11:15:02');

-- --------------------------------------------------------

--
-- 資料表結構 `borrow`
--

CREATE TABLE `borrow` (
  `classID` varchar(3) NOT NULL,
  `userID` varchar(7) NOT NULL,
  `itemID` varchar(5) NOT NULL,
  `beginTime` varchar(5) DEFAULT NULL,
  `expireTime` varchar(5) DEFAULT NULL,
  `agree` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `borrow`
--

INSERT INTO `borrow` (`classID`, `userID`, `itemID`, `beginTime`, `expireTime`, `agree`) VALUES
('401', '0000000', '40100', 'MON 4', 'MON 9', 1),
('620', '1000000', '62000', 'WED A', 'WED 6', 0),
('416', '1112943', '41600', 'FRI 4', 'FRI 6', 1),
('403', '1000000', '40300', 'MON 6', 'MON 9', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `class`
--

CREATE TABLE `class` (
  `classID` varchar(3) NOT NULL,
  `name` varchar(15) NOT NULL,
  `type` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `class`
--

INSERT INTO `class` (`classID`, `name`, `type`) VALUES
('401', '大一教室', 'normal'),
('402', '大三教室', 'normal'),
('403', '大四教室', 'normal'),
('413', '大二教室', 'normal'),
('415', '電腦教室', 'pro'),
('416', '電子電路實驗室', 'pro'),
('520', '研討室', 'pro'),
('523', '研討室', 'pro'),
('524', '研討室', 'pro'),
('620', '研討室', 'pro'),
('622', '研討室', 'pro');

-- --------------------------------------------------------

--
-- 資料表結構 `item`
--

CREATE TABLE `item` (
  `itemID` varchar(5) NOT NULL,
  `name` varchar(15) NOT NULL,
  `type` varchar(15) NOT NULL,
  `classID` varchar(3) NOT NULL,
  `Quantity` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `item`
--

INSERT INTO `item` (`itemID`, `name`, `type`, `classID`, `Quantity`) VALUES
('40100', '大一教室冷氣遙控器', '冷氣遙控器', '401', 1),
('40200', '大三教室冷氣遙控器', '冷氣遙控器', '402', 1),
('40300', '大四教室冷氣遙控器', '冷氣遙控器', '403', 1),
('41300', '大二教室冷氣遙控器', '冷氣遙控器', '413', 1),
('41500', '電腦教室鑰匙', '鑰匙', '415', 1),
('41600', '電子電路實驗室鑰匙', '鑰匙', '416', 1),
('52000', '研討室鑰匙', '鑰匙', '520', 1),
('52300', '研討室鑰匙', '鑰匙', '523', 1),
('52400', '研討室鑰匙', '鑰匙', '524', 1),
('62000', '研討室鑰匙', '鑰匙', '620', 1),
('62200', '研討室鑰匙', '鑰匙', '622', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `userID` varchar(7) NOT NULL,
  `Account` varchar(50) NOT NULL,
  `usertype` varchar(5) NOT NULL,
  `name` varchar(5) NOT NULL,
  `warn` int(1) DEFAULT NULL,
  `password` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`userID`, `Account`, `usertype`, `name`, `warn`, `password`) VALUES
('0000000', 's0000000@g.ncyu.edu.tw', 'one', 'user', 0, '123456'),
('1000000', 's0000000@g.ncyu.edu.tw', 'one', 'kevin', 0, '123456'),
('1112923', 's1112923@g.ncyu.edu.tw', 'two', '徐聖凱', 0, '123456'),
('1112943', 's1112943@g.ncyu.edu.tw', 'two', '李彥達', 0, '123456'),
('admin', 'admin', 'admin', 'admin', NULL, '123456');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `block`
--
ALTER TABLE `block`
  ADD PRIMARY KEY (`usertype`);

--
-- 資料表索引 `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`classID`);

--
-- 資料表索引 `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`itemID`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
