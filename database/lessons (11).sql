-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-06-11 06:36:21
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
-- 表的结构 `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` varchar(255) NOT NULL DEFAULT '''''',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `thumbnail_name` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `grading_criteria` text DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `auto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `lessons`
--

INSERT INTO `lessons` (`lesson_id`, `title`, `description`, `file_name`, `thumbnail_name`, `category`, `grading_criteria`, `create_time`, `auto_id`) VALUES
('LL000004', 'Project - scratchjr', 'do with 4 mamber', 'Tutorial_03(1).docx', 'custom-order-numbers-e1438361586475.png', 'Project', 'Completion:9|Presentation:14|Originality:10|Technical:15', '2025-06-10 17:02:28', 4);

--
-- 转储表的索引
--

--
-- 表的索引 `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`),
  ADD UNIQUE KEY `auto_id` (`auto_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `lessons`
--
ALTER TABLE `lessons`
  MODIFY `auto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
