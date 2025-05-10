-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-05-10 04:30:53
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
-- 表的结构 `teacher_materials`
--

CREATE TABLE `teacher_materials` (
  `material_id` varchar(255) NOT NULL DEFAULT uuid(),
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `class_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `teacher_materials`
--

INSERT INTO `teacher_materials` (`material_id`, `title`, `description`, `file_name`, `create_time`, `class_id`) VALUES
('M000001', 'AAAAA11', '11111111111', 'Tutorial_02.docx', '2025-05-09 07:08:02', 'CLS000004'),
('M000002', 'AAAAA', 'sssdddd', 'Tutorial_02.docx', '2025-05-09 07:38:46', 'CLS000004');

--
-- 转储表的索引
--

--
-- 表的索引 `teacher_materials`
--
ALTER TABLE `teacher_materials`
  ADD PRIMARY KEY (`material_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
