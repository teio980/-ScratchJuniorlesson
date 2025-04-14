-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-04-14 08:30:10
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
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `admin_id` varchar(20) NOT NULL,
  `A_Username` varchar(50) NOT NULL,
  `A_Password` varchar(200) NOT NULL,
  `A_Mail` varchar(100) NOT NULL,
  `identity` enum('admin') NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`admin_id`, `A_Username`, `A_Password`, `A_Mail`, `identity`) VALUES
('T00000001', 'Liaw0000', '$2y$10$S.521YlauzsQsmn2U94Q5.nfETe0dmvoJa6oUW1YgKga1MgY5aG0O', 'yongloon1234@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- 表的结构 `class`
--

CREATE TABLE `class` (
  `class_id` varchar(20) NOT NULL,
  `class_code` varchar(7) NOT NULL CHECK (`class_code` regexp '^[A-Z]{3}[0-9]{4}$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` varchar(20) NOT NULL,
  `project_id` varchar(20) NOT NULL,
  `teacher_id` varchar(20) NOT NULL,
  `comments` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `massage`
--

CREATE TABLE `massage` (
  `massage_ID` varchar(20) NOT NULL,
  `U_phoneNumber` varchar(50) NOT NULL,
  `massage_Content` varchar(550) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `projects`
--

CREATE TABLE `projects` (
  `id` varchar(20) NOT NULL,
  `lesson_id` varchar(20) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'What does the \"Green Flag\" button do in ScratchJr?', 4, 'Starts the project', 'Stops the project', 'Deletes the project', 'Saves the project', 1),
(3, 'What is the function of the \"Move Right\" block?', 2, 'Moves the character to the left', 'Moves the character to the right', 'Makes the character jump', 'Stops the character', 2),
(4, 'Which block makes a character jump?', 2, 'Move Right', 'Move Left', 'Jump', 'Stop', 3),
(5, 'What does the \"Move Left\" block do?', 4, 'Moves the character to the right', 'Moves the character up', 'Moves the character to the left', 'Stops the character', 3),
(6, 'Which block makes a character say something?', 3, 'Speak', 'Jump', 'Hide', 'Stop', 1),
(7, 'What does the \"Hide\" block do?', 1, 'Makes the character invisible', 'Moves the character up', 'Stops the project', 'Deletes the character', 1),
(8, 'What happens when you use the \"Stop\" block?', 3, 'The project stops completely', 'The character moves faster', 'The character jumps', 'The project restarts', 1),
(9, 'Which block makes the character grow bigger?', 4, 'Shrink', 'Enlarge', 'Jump', 'Hide', 2),
(10, 'Which block makes the character shrink?', 1, 'Shrink', 'Enlarge', 'Move Right', 'Speak', 1),
(11, 'What is the function of the \"Wait\" block?', 2, 'Makes the character move faster', 'Delays the next action', 'Stops the project', 'Speeds up the project', 2),
(12, 'What does the \"Go to Start\" block do?', 3, 'Moves the character to a random position', 'Moves the character to its starting position', 'Deletes the character', 'Speeds up the project', 2),
(13, 'Which block makes the character rotate?', 2, 'Move Right', 'Rotate', 'Jump', 'Stop', 2),
(14, 'What does the \"Repeat\" block do?', 5, 'Stops the project', 'Makes the action repeat multiple times', 'Speeds up the project', 'Moves the character in a random direction', 2),
(15, 'What happens when you use the \"End\" block?', 3, 'The project restarts', 'The project stops at the current moment', 'The character disappears', 'The character moves faster', 2),
(16, 'Which block makes a character appear again after using the \"Hide\" block?', 5, 'Jump', 'Show', 'Move Right', 'Stop', 2),
(17, 'Which block is used to change the background?', 4, 'Change Background', 'Move Left', 'Jump', 'Stop', 1),
(18, 'What does the \"Speed\" block do?', 1, 'Changes the speed of a character\'s movement', 'Makes the character jump higher', 'Stops the project', 'Deletes the character', 1),
(19, 'What does the \"Send Message\" block do?', 2, 'Sends a message to another character or scene', 'Moves the character to a random position', 'Stops the project', 'Speeds up the project', 1),
(20, 'Which block waits for a message before starting an action?', 3, 'Move Right', 'Wait for Message', 'Jump', 'Stop', 2),
(21, 'What does the \"Play Sound\" block do?', 3, 'Plays a sound or recorded voice', 'Stops the project', 'Makes the character move faster', 'Deletes the character', 1);

-- --------------------------------------------------------

--
-- 表的结构 `resetpassword`
--

CREATE TABLE `resetpassword` (
  `resetPassword_id` varchar(20) NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `teacher_id` varchar(20) DEFAULT NULL,
  `T_Mail` varchar(100) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `S_Mail` varchar(100) DEFAULT NULL,
  `admin_id` varchar(20) DEFAULT NULL,
  `A_Mail` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `student`
--

CREATE TABLE `student` (
  `student_id` varchar(20) NOT NULL,
  `S_Username` varchar(50) NOT NULL,
  `S_Password` varchar(200) NOT NULL,
  `S_Mail` varchar(100) NOT NULL,
  `identity` enum('student') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`student_id`, `S_Username`, `S_Password`, `S_Mail`, `identity`) VALUES
('STU2025000001', 'Student_1', '', 'Student1@gmail.com', 'student');

-- --------------------------------------------------------

--
-- 表的结构 `student_class`
--

CREATE TABLE `student_class` (
  `student_class_id` varchar(20) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `enroll_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `student_answers`
--

CREATE TABLE `student_answers` (
  `student_answers_ID` varchar(20) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `question_id` int(11) NOT NULL,
  `student_answer` varchar(50) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `difficult` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` varchar(20) NOT NULL,
  `T_Username` varchar(50) NOT NULL,
  `T_Password` varchar(200) NOT NULL,
  `T_Mail` varchar(100) NOT NULL,
  `identity` enum('teacher') NOT NULL DEFAULT 'teacher'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `T_Username`, `T_Password`, `T_Mail`, `identity`) VALUES
('STU2025000002', 'Test123', '$2y$10$FcqYSjS0U3EFCsyeTpJlGuTUWJuX5jyC8.DfBR2FNzrFOy/sHR7vK', 'yongloon0927@gmail.com', 'teacher');

-- --------------------------------------------------------

--
-- 表的结构 `teacher_class`
--

CREATE TABLE `teacher_class` (
  `teacher_class_id` varchar(20) NOT NULL,
  `teacher_id` varchar(20) NOT NULL,
  `class_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转储表的索引
--

--
-- 表的索引 `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `A_Username` (`A_Username`),
  ADD UNIQUE KEY `A_Mail` (`A_Mail`);

--
-- 表的索引 `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `class_code` (`class_code`);

--
-- 表的索引 `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`);

--
-- 表的索引 `massage`
--
ALTER TABLE `massage`
  ADD PRIMARY KEY (`massage_ID`);

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
-- 表的索引 `resetpassword`
--
ALTER TABLE `resetpassword`
  ADD PRIMARY KEY (`resetPassword_id`),
  ADD UNIQUE KEY `reset_token` (`reset_token`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- 表的索引 `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `S_Username` (`S_Username`),
  ADD UNIQUE KEY `S_Mail` (`S_Mail`);

--
-- 表的索引 `student_class`
--
ALTER TABLE `student_class`
  ADD PRIMARY KEY (`student_class_id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`class_id`),
  ADD KEY `class_id` (`class_id`);

--
-- 表的索引 `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`student_answers_ID`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `question_id` (`question_id`);

--
-- 表的索引 `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `T_Username` (`T_Username`),
  ADD UNIQUE KEY `T_Mail` (`T_Mail`);

--
-- 表的索引 `teacher_class`
--
ALTER TABLE `teacher_class`
  ADD PRIMARY KEY (`teacher_class_id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`,`class_id`),
  ADD KEY `class_id` (`class_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 限制导出的表
--

--
-- 限制表 `resetpassword`
--
ALTER TABLE `resetpassword`
  ADD CONSTRAINT `resetpassword_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resetpassword_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resetpassword_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE;

--
-- 限制表 `student_class`
--
ALTER TABLE `student_class`
  ADD CONSTRAINT `student_class_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE;

--
-- 限制表 `student_questions`
--
ALTER TABLE `student_questions`
  ADD CONSTRAINT `student_questions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- 限制表 `teacher_class`
--
ALTER TABLE `teacher_class`
  ADD CONSTRAINT `teacher_class_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
