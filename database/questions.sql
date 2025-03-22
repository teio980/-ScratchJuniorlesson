-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-03-22 10:45:16
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
-- 表的结构 `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `difficult` int(11) NOT NULL,
  `option1` varchar(255) NOT NULL,
  `option2` varchar(255) NOT NULL,
  `option3` varchar(255) NOT NULL,
  `option4` varchar(255) NOT NULL,
  `answer` int(11) NOT NULL CHECK (`answer` between 1 and 4)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `questions`
--

INSERT INTO `questions` (`id`, `question`, `difficult`, `option1`, `option2`, `option3`, `option4`, `answer`) VALUES
(1, 'What is Scratch Junior used for?', 1, 'To create and edit documents', 'To design 3D models', 'To program interactive stories and games', 'To browse the internet', 3),
(8, 'What does the \"Green Flag\" button do in ScratchJr?', 0, 'Starts the project', 'Stops the project', 'Deletes the project', 'Saves the project', 1),
(9, 'What is the function of the \"Move Right\" block?', 0, 'Moves the character to the left', 'Moves the character to the right', 'Makes the character jump', 'Stops the character', 2),
(10, 'Which block makes a character jump?', 0, 'Move Right', 'Move Left', 'Jump', 'Stop', 3),
(11, 'What does the \"Move Left\" block do?', 0, 'Moves the character to the right', 'Moves the character up', 'Moves the character to the left', 'Stops the character', 3),
(12, 'Which block makes a character say something?', 0, 'Speak', 'Jump', 'Hide', 'Stop', 1),
(13, 'What does the \"Hide\" block do?', 0, 'Makes the character invisible', 'Moves the character up', 'Stops the project', 'Deletes the character', 1),
(14, 'What happens when you use the \"Stop\" block?', 0, 'The project stops completely', 'The character moves faster', 'The character jumps', 'The project restarts', 1),
(15, 'Which block makes the character grow bigger?', 0, 'Shrink', 'Enlarge', 'Jump', 'Hide', 2),
(16, 'Which block makes the character shrink?', 0, 'Shrink', 'Enlarge', 'Move Right', 'Speak', 1),
(17, 'What is the function of the \"Wait\" block?', 0, 'Makes the character move faster', 'Delays the next action', 'Stops the project', 'Speeds up the project', 2),
(18, 'What does the \"Go to Start\" block do?', 0, 'Moves the character to a random position', 'Moves the character to its starting position', 'Deletes the character', 'Speeds up the project', 2),
(19, 'Which block makes the character rotate?', 0, 'Move Right', 'Rotate', 'Jump', 'Stop', 2),
(20, 'What does the \"Repeat\" block do?', 0, 'Stops the project', 'Makes the action repeat multiple times', 'Speeds up the project', 'Moves the character in a random direction', 2),
(21, 'What happens when you use the \"End\" block?', 0, 'The project restarts', 'The project stops at the current moment', 'The character disappears', 'The character moves faster', 2),
(22, 'Which block makes a character appear again after using the \"Hide\" block?', 0, 'Jump', 'Show', 'Move Right', 'Stop', 2),
(23, 'Which block is used to change the background?', 0, 'Change Background', 'Move Left', 'Jump', 'Stop', 1),
(24, 'What does the \"Speed\" block do?', 0, 'Changes the speed of a character\'s movement', 'Makes the character jump higher', 'Stops the project', 'Deletes the character', 1),
(25, 'What does the \"Send Message\" block do?', 0, 'Sends a message to another character or scene', 'Moves the character to a random position', 'Stops the project', 'Speeds up the project', 1),
(26, 'Which block waits for a message before starting an action?', 0, 'Move Right', 'Wait for Message', 'Jump', 'Stop', 2),
(27, 'What does the \"Play Sound\" block do?', 0, 'Plays a sound or recorded voice', 'Stops the project', 'Makes the character move faster', 'Deletes the character', 1);

--
-- 转储表的索引
--

--
-- 表的索引 `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
