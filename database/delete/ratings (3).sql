-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-05-08 14:26:00
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
-- 表的结构 `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `submit_id` varchar(255) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `rated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `ratings`
--

INSERT INTO `ratings` (`rating_id`, `submit_id`, `student_id`, `rating`, `rated_at`) VALUES
(7, 'SS000001', 'STU2025000005', 50, '2025-05-08 17:27:46'),
(8, 'SS000002', 'STU2025000005', 27, '2025-05-08 17:28:11'),
(9, 'SS000003', 'STU2025000007', 39, '2025-05-08 19:10:48'),
(10, 'SS000004', 'STU2025000007', 92, '2025-05-08 19:11:28');

--
-- 转储表的索引
--

--
-- 表的索引 `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `submit_id` (`submit_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 限制导出的表
--

--
-- 限制表 `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`submit_id`) REFERENCES `student_submit` (`submit_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
