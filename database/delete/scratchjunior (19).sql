-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-05-22 11:16:26
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
  `identity` enum('admin') NOT NULL DEFAULT 'admin',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`admin_id`, `A_Username`, `A_Password`, `A_Mail`, `identity`, `reset_token`, `reset_token_expires`) VALUES
('T00000001', 'Admin', '$2y$10$S.521YlauzsQsmn2U94Q5.nfETe0dmvoJa6oUW1YgKga1MgY5aG0O', 'yongloon1234@gmail.com', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `class`
--

CREATE TABLE `class` (
  `class_id` varchar(20) NOT NULL,
  `class_code` varchar(7) NOT NULL CHECK (`class_code` regexp '^[A-Z]{3}[0-9]{4}$'),
  `class_name` varchar(255) DEFAULT NULL,
  `class_description` text NOT NULL,
  `max_capacity` int(11) NOT NULL,
  `current_capacity` int(11) NOT NULL DEFAULT 0,
  `class_average` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `class`
--

INSERT INTO `class` (`class_id`, `class_code`, `class_name`, `class_description`, `max_capacity`, `current_capacity`, `class_average`) VALUES
('CLS000001', 'ABC1234', 'AAAA', 'GGG', 88, 2, NULL),
('CLS000002', 'GGG1234', 'AAAA', 'aaa', 50, 0, NULL),
('CLS000003', 'ABC8888', 'AAAA', 'sdvdvfvdxgbdvfb', 33, 0, NULL),
('CLS000004', 'DDC8899', 'ABCDEFG', 'fvsfbdfsbvdskgiueshge', 80, 5, 25.00);

-- --------------------------------------------------------

--
-- 表的结构 `class_work`
--

CREATE TABLE `class_work` (
  `availability_id` varchar(255) NOT NULL,
  `lesson_id` varchar(255) NOT NULL,
  `class_id` varchar(255) DEFAULT NULL,
  `student_work` varchar(255) NOT NULL,
  `expire_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `class_work`
--

INSERT INTO `class_work` (`availability_id`, `lesson_id`, `class_id`, `student_work`, `expire_date`) VALUES
('CW000001', 'LL000001', 'CLS000004', 'Tutorial 03.docx', '2025-05-30 10:00:00'),
('CW000002', 'LL000002', 'CLS000004', 'Lab 8 (3).pdf', '2025-05-30 09:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `content_comments`
--

CREATE TABLE `content_comments` (
  `comment_id` varchar(20) NOT NULL,
  `content_type` enum('lesson','material') NOT NULL,
  `content_id` varchar(255) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `sender_id` varchar(20) NOT NULL,
  `sender_type` enum('teacher','student') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `content_comments`
--

INSERT INTO `content_comments` (`comment_id`, `content_type`, `content_id`, `class_id`, `sender_id`, `sender_type`, `message`, `created_at`, `is_read`) VALUES
('CMT0000001', 'lesson', 'LL000001', 'CLS000004', 'STU2025000002', 'teacher', 'jiji', '2025-05-21 08:03:53', 0);

-- --------------------------------------------------------

--
-- 表的结构 `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `thumbnail_name` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `grading_criteria` text DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `lessons`
--

INSERT INTO `lessons` (`lesson_id`, `title`, `description`, `file_name`, `thumbnail_name`, `category`, `grading_criteria`, `create_time`) VALUES
('LL000001', 'Project - 111111111', '111111111', 'Tutorial 03.docx', '2019400.png', 'Project', 'Completion:20|Presentation:20|11111:5', '2025-05-18 02:34:47'),
('LL000002', 'Assignment - 2222', '22222', 'Lab 8 (3).pdf', 'custom-order-numbers-e1438361586475.png', 'Assignment', '2222:5|Presentation:5', '2025-05-22 07:22:25');

-- --------------------------------------------------------

--
-- 表的结构 `material_class`
--

CREATE TABLE `material_class` (
  `id` int(11) NOT NULL,
  `material_id` varchar(255) NOT NULL,
  `class_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `material_class`
--

INSERT INTO `material_class` (`id`, `material_id`, `class_id`) VALUES
(1, 'M000001', 'CLS000004');

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
(21, 'What does the \"Play Sound\" block do?', 3, 'Plays a sound or recorded voice', 'Stops the project', 'Makes the character move faster', 'Deletes the character', 1),
(22, 'gnhmg,jkh.lj/', 24, 'fhjhk,t', 'rjjry', 'jhfkm', 'etjjetj', 4),
(23, 'ryjrjryj', 18, 'krk,tr,', 'rym,ry', 'r,ht,m', 'rh,mtr,m', 1);

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
  `reset_token_expires` datetime DEFAULT NULL,
  `student_average` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`student_id`, `S_Username`, `S_Password`, `S_Mail`, `identity`, `reset_token`, `reset_token_expires`, `student_average`) VALUES
('STU2025000001', 'Student_1', '', 'Student1@gmail.com', 'student', NULL, NULL, 0.00),
('STU2025000002', 'Kong9999', '$2y$10$g/St7xxOzsZn9ALWq4GQ9.ctBUCQA92nChiXKyPKBmCuSvrvUsZNy', 'kong9999@gmail.com', 'student', NULL, NULL, 0.00),
('STU2025000003', 'Kong2383', '$2y$10$vu4Kyb.gL92Vrci6o.ryGurqpe5nw4IqnGqNnFJE8XuJI6kAOZ28a', 'kongwenkhangg@gmail.com', 'student', NULL, NULL, 0.00),
('STU2025000004', 'Kumbs0524', '$2y$10$1ITSUY0LlHTxVL9k6d2HI.t1WLAfJCAHHJqyjbhwrZilsVJv27Nia', 'kumbs0329@gmail.com', 'student', NULL, NULL, 0.00),
('STU2025000005', 'Ytboyyyy', '$2y$10$B8r3qg34nLZlDDJQqYY2Iu7Jngcw9T0Ayp4W8Gc8g1/dwCgNs2gJO', 'yt123457@gmail.com', 'student', NULL, NULL, 76.00),
('STU2025000006', 'Student_2', '$2y$10$x.XjBt6NGByFXFG3r/5lxunxlwHfRsszHawpYOUIp8o18sSET9iLG', 'Student_2@gmail.com', 'student', NULL, NULL, 0.00),
('STU2025000007', 'Student_3', '$2y$10$CJG5MFWhp1HV/NGhpXHuIeTwS98cdWBtOFjz2WAhhCDWKABis8HkS', 'Student_3@gmail.com', 'student', NULL, NULL, 0.00),
('STU2025000008', 'Student32', '$2y$10$cY.Uyn76QltQ0.lCm0yM5OcTHO5zJJzYzMYn8Tqxj2mH3LGEAjGba', 'Student32@gmail.com', 'student', NULL, NULL, 0.00);

-- --------------------------------------------------------

--
-- 表的结构 `student_answers`
--

CREATE TABLE `student_answers` (
  `student_question_id` varchar(20) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `student_answer` varchar(50) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `difficult` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `student_answers`
--

INSERT INTO `student_answers` (`student_question_id`, `student_id`, `question_id`, `student_answer`, `is_correct`, `difficult`) VALUES
('SQ000001', 'STU2025000005', 1, '3', 1, 1),
('SQ000002', 'STU2025000005', 7, '1', 1, 1),
('SQ000003', 'STU2025000005', 10, '1', 1, 1),
('SQ000004', 'STU2025000005', 18, '1', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `student_change_class`
--

CREATE TABLE `student_change_class` (
  `student_change_class_id` varchar(20) DEFAULT NULL,
  `student_change_class_reason` varchar(500) NOT NULL,
  `student_original_class` varchar(20) NOT NULL,
  `student_prefer_class` varchar(20) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `status_read` enum('read','unread') NOT NULL DEFAULT 'unread',
  `auto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `student_change_class`
--

INSERT INTO `student_change_class` (`student_change_class_id`, `student_change_class_reason`, `student_original_class`, `student_prefer_class`, `student_id`, `status`, `status_read`, `auto_id`) VALUES
('SCC00000022', 'Test', 'DDC8899', 'GGG1234', 'STU2025000008', 'approved', 'unread', 22),
('SCC00000023', 'Test', 'DDC8899', 'GGG1234', 'STU2025000008', 'approved', 'unread', 23),
('SCC00000024', 'aaa', 'DDC8899', 'ABC8888', 'STU2025000008', 'rejected', 'unread', 24),
('SCC00000025', 'aaa111', 'DDC8899', 'GGG1234', 'STU2025000008', 'approved', 'unread', 25),
('SCC00000026', '111aaa', 'DDC8899', 'ABC1234', 'STU2025000008', 'approved', 'unread', 26),
('SCC00000027', 'sdadsdsa', 'GGG1234', 'ABC1234', 'STU2025000008', 'rejected', 'unread', 27),
('SCC00000028', 'FGGSDCJKDFBODF', 'GGG1234', 'ABC1234', 'STU2025000008', 'approved', 'unread', 28),
('SCC00000029', '11111', 'DDC8899', 'ABC1234', 'STU2025000005', 'approved', 'unread', 29),
('SCC00000030', 'sjsjsjsj', 'ABC1234', 'DDC8899', 'STU2025000005', 'approved', 'unread', 30);

-- --------------------------------------------------------

--
-- 表的结构 `student_class`
--

CREATE TABLE `student_class` (
  `student_class_id` varchar(20) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `enroll_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `student_class`
--

INSERT INTO `student_class` (`student_class_id`, `student_id`, `class_id`, `enroll_date`) VALUES
('SC000003', 'STU2025000007', 'CLS000004', '2025-05-08 10:07:46'),
('SC000004', 'STU2025000005', 'CLS000004', '2025-05-07 05:37:21'),
('SC000005', 'STU2025000006', 'CLS000004', '2025-05-08 08:06:47');

-- --------------------------------------------------------

--
-- 表的结构 `student_level`
--

CREATE TABLE `student_level` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `experience` int(11) DEFAULT 0,
  `level` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `student_level`
--

INSERT INTO `student_level` (`id`, `student_id`, `experience`, `level`) VALUES
(30, 'STU2025000002', 57, 2),
(31, 'STU2025000005', 57, 2);

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

-- --------------------------------------------------------

--
-- 表的结构 `student_submit_feedback`
--

CREATE TABLE `student_submit_feedback` (
  `feedback_id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `submit_id` varchar(20) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 10),
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `identity` enum('teacher') NOT NULL DEFAULT 'teacher',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `T_Username`, `T_Password`, `T_Mail`, `identity`, `reset_token`, `reset_token_expires`) VALUES
('STU2025000002', 'Test123', '$2y$10$FcqYSjS0U3EFCsyeTpJlGuTUWJuX5jyC8.DfBR2FNzrFOy/sHR7vK', 'yongloon0927@gmail.com', 'teacher', NULL, NULL);

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
-- 转存表中的数据 `teacher_class`
--

INSERT INTO `teacher_class` (`teacher_class_id`, `teacher_id`, `class_id`) VALUES
('TC000004', 'STU2025000002', 'CLS000004');

-- --------------------------------------------------------

--
-- 表的结构 `teacher_feedback`
--

CREATE TABLE `teacher_feedback` (
  `teacher_feedback_id` varchar(20) NOT NULL,
  `project_id` varchar(20) NOT NULL,
  `teacher_id` varchar(20) NOT NULL,
  `comments` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `teacher_materials`
--

CREATE TABLE `teacher_materials` (
  `material_id` varchar(255) NOT NULL DEFAULT uuid(),
  `teacher_id` varchar(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `teacher_materials`
--

INSERT INTO `teacher_materials` (`material_id`, `teacher_id`, `title`, `description`, `file_name`, `create_time`) VALUES
('M000001', 'STU2025000002', 'AAAAA11', '222222222', 'Lab_04.pdf', '2025-05-22 07:31:00');

--
-- 转储表的索引
--

--
-- 表的索引 `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`);

--
-- 表的索引 `class_work`
--
ALTER TABLE `class_work`
  ADD PRIMARY KEY (`availability_id`);

--
-- 表的索引 `content_comments`
--
ALTER TABLE `content_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `content_idx` (`content_type`,`content_id`,`class_id`);

--
-- 表的索引 `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`);

--
-- 表的索引 `material_class`
--
ALTER TABLE `material_class`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_id` (`material_id`,`class_id`),
  ADD KEY `fk_class` (`class_id`);

--
-- 表的索引 `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `S_Username` (`S_Username`),
  ADD UNIQUE KEY `S_Mail` (`S_Mail`);
ALTER TABLE `student` ADD FULLTEXT KEY `student_id` (`student_id`);

--
-- 表的索引 `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`student_question_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `question_id` (`question_id`);

--
-- 表的索引 `student_change_class`
--
ALTER TABLE `student_change_class`
  ADD PRIMARY KEY (`auto_id`);

--
-- 表的索引 `student_class`
--
ALTER TABLE `student_class`
  ADD PRIMARY KEY (`student_class_id`);

--
-- 表的索引 `student_level`
--
ALTER TABLE `student_level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_level_ibfk_1` (`student_id`);

--
-- 表的索引 `student_submit`
--
ALTER TABLE `student_submit`
  ADD PRIMARY KEY (`submit_id`);

--
-- 表的索引 `student_submit_feedback`
--
ALTER TABLE `student_submit_feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `submit_id` (`submit_id`);

--
-- 表的索引 `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `T_Username` (`T_Username`),
  ADD UNIQUE KEY `T_Mail` (`T_Mail`);

--
-- 表的索引 `teacher_materials`
--
ALTER TABLE `teacher_materials`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `fk_teacher_materials_teacher` (`teacher_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `material_class`
--
ALTER TABLE `material_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- 使用表AUTO_INCREMENT `student_change_class`
--
ALTER TABLE `student_change_class`
  MODIFY `auto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- 使用表AUTO_INCREMENT `student_level`
--
ALTER TABLE `student_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- 使用表AUTO_INCREMENT `student_submit_feedback`
--
ALTER TABLE `student_submit_feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 限制导出的表
--

--
-- 限制表 `material_class`
--
ALTER TABLE `material_class`
  ADD CONSTRAINT `fk_class` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_material` FOREIGN KEY (`material_id`) REFERENCES `teacher_materials` (`material_id`) ON DELETE CASCADE;

--
-- 限制表 `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- 限制表 `student_level`
--
ALTER TABLE `student_level`
  ADD CONSTRAINT `student_level_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE;

--
-- 限制表 `student_submit_feedback`
--
ALTER TABLE `student_submit_feedback`
  ADD CONSTRAINT `student_submit_feedback_ibfk_1` FOREIGN KEY (`submit_id`) REFERENCES `student_submit` (`submit_id`);

--
-- 限制表 `teacher_materials`
--
ALTER TABLE `teacher_materials`
  ADD CONSTRAINT `fk_teacher_materials_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
