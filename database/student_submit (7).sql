-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-05-22 08:11:07
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
-- 表的结构 `student_submit`
--

CREATE TABLE `student_submit` (
  `submit_id` varchar(20) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `lesson_id` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `score` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `student_submit`
--

INSERT INTO `student_submit` (`submit_id`, `student_id`, `class_id`, `lesson_id`, `filename`, `filepath`, `upload_time`, `score`) VALUES
('SS000001', 'STU2025000005', 'CLS000004', 'LL000001', 'Project (3).sjr', 'uploads/Project (3).sjr', '2025-05-18 02:15:13', 76.00);

--
-- 转储表的索引
--

--
-- 表的索引 `student_submit`
--
ALTER TABLE `student_submit`
  ADD PRIMARY KEY (`submit_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
