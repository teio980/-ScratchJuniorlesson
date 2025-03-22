-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-03-22 05:51:33
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
  `lesson_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `lessons`
--

INSERT INTO `lessons` (`lesson_id`, `title`, `description`, `expire_date`, `create_time`) VALUES
(1, 'adf', 'adf', '2025-03-17 15:30:00', '2025-03-15 08:07:16');

-- --------------------------------------------------------

--
-- 表的结构 `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `projects`
--

INSERT INTO `projects` (`id`, `lesson_id`, `filename`, `filepath`, `upload_time`) VALUES
(1, 2, 'Project1 - Copy.sjr', '../phpfile/uploads/Project1 - Copy.sjr', '2025-03-15 07:12:50'),
(2, 1, 'Project1.sjr', '../phpfile/uploads/Project1.sjr', '2025-03-15 08:25:32');

-- --------------------------------------------------------

--
-- 表的结构 `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `level` varchar(10) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `questions`
--

INSERT INTO `questions` (`id`, `level`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`) VALUES
(4, 'easy', 'What does the \"Green Flag\" button do in ScratchJr?', 'Starts the project', 'Stops the project', 'Deletes the project', 'Saves the project', 'A'),
(5, 'easy', 'What is the function of the \"Move Right\" block?', 'Moves the character to the left', 'Moves the character to the right', 'Makes the character jump', 'Stops the character', 'B'),
(6, 'easy', 'Which block makes a character jump?', 'Move Right', 'Move Left', 'Jump', 'Stop', 'C'),
(7, 'easy', 'What does the \"Move Left\" block do?', 'Moves the character to the right', 'Moves the character up', 'Moves the character to the left', 'Stops the character', 'C'),
(8, 'easy', 'Which block makes a character say something?', 'Speak', 'Jump', 'Hide', 'Stop', 'A'),
(9, 'easy', 'What does the \"Hide\" block do?', 'Makes the character invisible', 'Moves the character up', 'Stops the project', 'Deletes the character', 'A'),
(10, 'easy', 'What happens when you use the \"Stop\" block?', 'The project stops completely', 'The character moves faster', 'The character jumps', 'The project restarts', 'A'),
(11, 'easy', 'Which block makes the character grow bigger?', 'Shrink', 'Enlarge', 'Jump', 'Hide', 'B'),
(12, 'easy', 'Which block makes the character shrink?', 'Shrink', 'Enlarge', 'Move Right', 'Speak', 'A'),
(13, 'easy', 'What is the function of the \"Wait\" block?', 'Makes the character move faster', 'Delays the next action', 'Stops the project', 'Speeds up the project', 'B'),
(14, 'easy', 'What does the \"Go to Start\" block do?', 'Moves the character to a random position', 'Moves the character to its starting position', 'Deletes the character', 'Speeds up the project', 'B'),
(15, 'easy', 'Which block makes the character rotate?', 'Move Right', 'Rotate', 'Jump', 'Stop', 'B'),
(16, 'easy', 'What does the \"Repeat\" block do?', 'Stops the project', 'Makes the action repeat multiple times', 'Speeds up the project', 'Moves the character in a random direction', 'B'),
(17, 'easy', 'What happens when you use the \"End\" block?', 'The project restarts', 'The project stops at the current moment', 'The character disappears', 'The character moves faster', 'B'),
(18, 'easy', 'Which block makes a character appear again after using the \"Hide\" block?', 'Jump', 'Show', 'Move Right', 'Stop', 'B'),
(19, 'easy', 'Which block is used to change the background?', 'Change Background', 'Move Left', 'Jump', 'Stop', 'A'),
(20, 'easy', 'What does the \"Speed\" block do?', 'Changes the speed of a character\'s movement', 'Makes the character jump higher', 'Stops the project', 'Deletes the character', 'A'),
(21, 'easy', 'What does the \"Send Message\" block do?', 'Sends a message to another character or scene', 'Moves the character to a random position', 'Stops the project', 'Speeds up the project', 'A'),
(22, 'easy', 'Which block waits for a message before starting an action?', 'Move Right', 'Wait for Message', 'Jump', 'Stop', 'B'),
(23, 'easy', 'What does the \"Play Sound\" block do?', 'Plays a sound or recorded voice', 'Stops the project', 'Makes the character move faster', 'Deletes the character', 'A');

--
-- 转储表的索引
--

--
-- 表的索引 `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`);

--
-- 表的索引 `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `lessons`
--
ALTER TABLE `lessons`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
