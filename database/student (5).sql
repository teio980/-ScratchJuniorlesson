-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-06-08 16:47:17
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
-- 表的结构 `student`
--

CREATE TABLE `student` (
  `student_id` varchar(20) NOT NULL,
  `S_Username` varchar(50) NOT NULL,
  `S_Password` varchar(200) NOT NULL,
  `S_Mail` varchar(100) NOT NULL,
  `identity` enum('student') NOT NULL DEFAULT 'student',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`student_id`, `S_Username`, `S_Password`, `S_Mail`, `identity`, `reset_token`, `reset_token_expires`) VALUES
('STU2025000001', 'Student_1', '', 'Student1@gmail.com', 'student', NULL, NULL),
('STU2025000002', 'Kong9999', '$2y$10$g/St7xxOzsZn9ALWq4GQ9.ctBUCQA92nChiXKyPKBmCuSvrvUsZNy', 'kong9999@gmail.com', 'student', NULL, NULL),
('STU2025000003', 'Kong2383', '$2y$10$vu4Kyb.gL92Vrci6o.ryGurqpe5nw4IqnGqNnFJE8XuJI6kAOZ28a', 'kongwenkhangg@gmail.com', 'student', NULL, NULL),
('STU2025000004', 'Kumbs0524', '$2y$10$1ITSUY0LlHTxVL9k6d2HI.t1WLAfJCAHHJqyjbhwrZilsVJv27Nia', 'kumbs0329@gmail.com', 'student', NULL, NULL),
('STU2025000005', 'Ytboyyyy', '$2y$10$B8r3qg34nLZlDDJQqYY2Iu7Jngcw9T0Ayp4W8Gc8g1/dwCgNs2gJO', 'yt123457@gmail.com', 'student', NULL, NULL),
('STU2025000006', 'Student_2', '$2y$10$x.XjBt6NGByFXFG3r/5lxunxlwHfRsszHawpYOUIp8o18sSET9iLG', 'Student_2@gmail.com', 'student', NULL, NULL),
('STU2025000007', 'Student_3', '$2y$10$CJG5MFWhp1HV/NGhpXHuIeTwS98cdWBtOFjz2WAhhCDWKABis8HkS', 'Student_3@gmail.com', 'student', NULL, NULL),
('STU2025000008', 'Student32', '$2y$10$cY.Uyn76QltQ0.lCm0yM5OcTHO5zJJzYzMYn8Tqxj2mH3LGEAjGba', 'Student32@gmail.com', 'student', NULL, NULL);

--
-- 转储表的索引
--

--
-- 表的索引 `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `S_Username` (`S_Username`),
  ADD UNIQUE KEY `S_Mail` (`S_Mail`);
ALTER TABLE `student` ADD FULLTEXT KEY `student_id` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
