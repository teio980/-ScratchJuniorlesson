-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-04-21 08:11:37
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
-- 表的结构 `class_work`
--

CREATE TABLE `class_work` (
  `availability_id` int(11) NOT NULL,
  `class_id` varchar(255) DEFAULT NULL,
  `student_work` varchar(255) NOT NULL,
  `expire_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `class_work`
--

INSERT INTO `class_work` (`availability_id`, `class_id`, `student_work`, `expire_date`) VALUES
(1, '1234r', '1221206295_FYP_Progress -TFP4224.pdf', '2025-05-03 18:35:00'),
(2, 'wert3', 'Lab 01.pdf', '2025-04-25 18:36:00'),
(3, 'wert3', '1221206295_FYP_Progress -TFP4224.pdf', '2025-04-30 13:52:00'),
(4, '1234r', 'Lab 01.pdf', '2025-04-24 13:52:00');

--
-- 转储表的索引
--

--
-- 表的索引 `class_work`
--
ALTER TABLE `class_work`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `class_id` (`class_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `class_work`
--
ALTER TABLE `class_work`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 限制导出的表
--

--
-- 限制表 `class_work`
--
ALTER TABLE `class_work`
  ADD CONSTRAINT `class_work_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
