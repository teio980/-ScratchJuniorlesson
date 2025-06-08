-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-06-08 16:47:10
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
-- 表的结构 `student_class`
--

CREATE TABLE `student_class` (
  `student_class_id` varchar(20) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `enroll_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `average_score` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `student_class`
--

INSERT INTO `student_class` (`student_class_id`, `student_id`, `class_id`, `enroll_date`, `average_score`) VALUES
('SC000003', 'STU2025000003', 'CLS000004', '2025-05-30 06:27:07', NULL),
('SC000004', 'STU2025000005', 'CLS000004', '2025-05-07 05:37:21', 64.00),
('SC000005', 'STU2025000006', 'CLS000004', '2025-05-08 08:06:47', NULL);

--
-- 转储表的索引
--

--
-- 表的索引 `student_class`
--
ALTER TABLE `student_class`
  ADD PRIMARY KEY (`student_class_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
