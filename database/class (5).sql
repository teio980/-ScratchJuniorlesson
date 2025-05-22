-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-05-22 08:11:35
-- 服务器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `scratchjunior`
--

-- --------------------------------------------------------

--
-- 表的结构 `class`
--

CREATE TABLE `class` (
  `class_id` varchar(20) NOT NULL,
  `class_code` varchar(7) NOT NULL CHECK (`class_code` regexp '^[A-Z]{3}[0-9]{4}$'),
  `class_name` varchar(255) DEFAULT NULL,
  `class_description` text NOT NULL,
  `max_capacity` int(11) NOT NULL,
  `current_capacity` int(11) NOT NULL DEFAULT 0,
  `class_average` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `class`
--

INSERT INTO `class` (`class_id`, `class_code`, `class_name`, `class_description`, `max_capacity`, `current_capacity`, `class_average`) VALUES
('CLS000001', 'ABC1234', 'AAAA', 'GGG', 88, 2, NULL),
('CLS000002', 'GGG1234', 'AAAA', 'aaa', 50, 0, NULL),
('CLS000003', 'ABC8888', 'AAAA', 'sdvdvfvdxgbdvfb', 33, 0, NULL),
('CLS000004', 'DDC8899', 'ABCDEFG', 'fvsfbdfsbvdskgiueshge', 80, 5, 25.00);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
