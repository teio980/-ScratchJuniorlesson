-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-06-11 06:36:17
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
-- 表的结构 `content_comments`
--

CREATE TABLE `content_comments` (
  `comment_id` varchar(20) NOT NULL,
  `availability_id` varchar(255) NOT NULL,
  `sender_id` varchar(20) NOT NULL,
  `sender_type` enum('teacher','student') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `auto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 触发器 `content_comments`
--
DELIMITER $$
CREATE TRIGGER `set_comment_id` BEFORE INSERT ON `content_comments` FOR EACH ROW BEGIN
    DECLARE next_id INT;
    SELECT IFNULL(MAX(auto_id), 0) + 1 INTO next_id FROM `content_comments`;
    SET NEW.comment_id = CONCAT('CMT', LPAD(next_id, 6, '0'));
END
$$
DELIMITER ;

--
-- 转储表的索引
--

--
-- 表的索引 `content_comments`
--
ALTER TABLE `content_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD UNIQUE KEY `auto_id` (`auto_id`),
  ADD KEY `content_idx` (`availability_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `content_comments`
--
ALTER TABLE `content_comments`
  MODIFY `auto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
