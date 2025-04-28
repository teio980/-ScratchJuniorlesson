-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 10:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scratchjunior`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
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
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `A_Username`, `A_Password`, `A_Mail`, `identity`, `reset_token`, `reset_token_expires`) VALUES
('T00000001', 'Admin', '$2y$10$S.521YlauzsQsmn2U94Q5.nfETe0dmvoJa6oUW1YgKga1MgY5aG0O', 'yongloon1234@gmail.com', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` varchar(20) NOT NULL,
  `class_code` varchar(7) NOT NULL CHECK (`class_code` regexp '^[A-Z]{3}[0-9]{4}$'),
  `class_name` varchar(255) DEFAULT NULL,
  `class_description` text NOT NULL,
  `max_capacity` int(11) NOT NULL,
  `current_capacity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_code`, `class_name`, `class_description`, `max_capacity`, `current_capacity`) VALUES
('CLS000001', 'ABC1234', 'AAAA', 'GGG', 88, 0),
('CLS000002', 'GGG1234', 'AAAA', 'aaa', 50, 0),
('CLS000003', 'ABC8888', 'AAAA', 'sdvdvfvdxgbdvfb', 33, 0),
('CLS000004', 'DDC8899', 'ABCDEFG', 'fvsfbdfsbvdskgiueshge', 80, 0);

-- --------------------------------------------------------

--
-- Table structure for table `class_work`
--

CREATE TABLE `class_work` (
  `availability_id` varchar(255) NOT NULL,
  `lesson_id` varchar(255) NOT NULL,
  `class_id` varchar(255) DEFAULT NULL,
  `student_work` varchar(255) NOT NULL,
  `expire_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_work`
--

INSERT INTO `class_work` (`availability_id`, `lesson_id`, `class_id`, `student_work`, `expire_date`) VALUES
('CW000001', 'LL000002', 'wert3', 'Lab01.pdf', '2025-04-30 16:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `thumbnail_name` varchar(255) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `lesson_file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`lesson_id`, `title`, `description`, `create_time`, `thumbnail_name`, `thumbnail_path`, `lesson_file_name`, `file_path`) VALUES
('LL000001', 'rtyujhr', 'fgzgx', '2025-04-25 07:24:11', 'time table d4.jpg', '/phpfile/uploads/thumbnail/time table d4.jpg', '1000021589.pdf', '/phpfile/uploads/1000021589.pdf'),
('LL000002', 'aedgdeg', 'egadg', '2025-04-25 07:24:38', 'time table d4.jpg', '/phpfile/uploads/thumbnail/time table d4.jpg', 'Lab01.pdf', '/phpfile/uploads/Lab01.pdf'),
('LL000003', 'agfsdg', 'asfgsdfg', '2025-04-25 07:25:08', 'Screenshot (1).png', '/phpfile/uploads/thumbnail/Screenshot (1).png', '1000021589.pdf', '/phpfile/uploads/1000021589.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
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
-- Dumping data for table `questions`
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
-- Table structure for table `student`
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
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `S_Username`, `S_Password`, `S_Mail`, `identity`, `reset_token`, `reset_token_expires`) VALUES
('STU2025000001', 'Student_1', '', 'Student1@gmail.com', 'student', NULL, NULL),
('STU2025000002', 'Kong9999', '$2y$10$g/St7xxOzsZn9ALWq4GQ9.ctBUCQA92nChiXKyPKBmCuSvrvUsZNy', 'kong9999@gmail.com', 'student', NULL, NULL),
('STU2025000003', 'Kong2383', '$2y$10$vu4Kyb.gL92Vrci6o.ryGurqpe5nw4IqnGqNnFJE8XuJI6kAOZ28a', 'kongwenkhangg@gmail.com', 'student', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
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
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`student_question_id`, `student_id`, `question_id`, `student_answer`, `is_correct`, `difficult`) VALUES
('SQ000001', 'STU2025000003', 1, '3', 1, 1),
('SQ000002', 'STU2025000003', 7, '1', 1, 1),
('SQ000003', 'STU2025000003', 10, '1', 1, 1),
('SQ000004', 'STU2025000003', 18, '1', 1, 1),
('SQ000005', 'STU2025000003', 3, '2', 1, 2),
('SQ000006', 'STU2025000003', 4, '', 0, 2),
('SQ000007', 'STU2025000003', 11, '1', 0, 2),
('SQ000008', 'STU2025000003', 13, '1', 0, 2),
('SQ000009', 'STU2025000003', 19, '3', 0, 2),
('SQ000010', 'STU2025000002', 1, '3', 1, 1),
('SQ000011', 'STU2025000002', 7, '1', 1, 1),
('SQ000012', 'STU2025000002', 10, '1', 1, 1),
('SQ000013', 'STU2025000002', 18, '1', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_class`
--

CREATE TABLE `student_class` (
  `student_class_id` varchar(20) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `enroll_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_class`
--

INSERT INTO `student_class` (`student_class_id`, `student_id`, `class_id`, `enroll_date`) VALUES
('SC000001', 'STU2025000003', 'CLS000001', '2025-04-28 08:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `student_level`
--

CREATE TABLE `student_level` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `experience` int(11) DEFAULT 0,
  `level` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_submit`
--

CREATE TABLE `student_submit` (
  `submit_id` varchar(20) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `lesson_id` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_submit`
--

INSERT INTO `student_submit` (`submit_id`, `student_id`, `class_id`, `lesson_id`, `filename`, `filepath`, `upload_time`) VALUES
('SS000001', 'STU2025000003', 'wert3', 'LL000002', 'Project1 - Copy (1).sjr', 'uploads/Project1 - Copy (1).sjr', '2025-04-25 08:33:18'),
('SS000002', 'STU2025000002', 'wert3', 'LL000002', 'Project1 - Copy (2) (1).sjr', 'uploads/Project1 - Copy (2) (1).sjr', '2025-04-26 05:21:53');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
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
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `T_Username`, `T_Password`, `T_Mail`, `identity`, `reset_token`, `reset_token_expires`) VALUES
('STU2025000002', 'Test123', '$2y$10$FcqYSjS0U3EFCsyeTpJlGuTUWJuX5jyC8.DfBR2FNzrFOy/sHR7vK', 'yongloon0927@gmail.com', 'teacher', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_class`
--

CREATE TABLE `teacher_class` (
  `teacher_class_id` varchar(20) NOT NULL,
  `teacher_id` varchar(20) NOT NULL,
  `class_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_class`
--

INSERT INTO `teacher_class` (`teacher_class_id`, `teacher_id`, `class_id`) VALUES
('TC000004', 'STU2025000002', 'CLS000004');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_feedback`
--

CREATE TABLE `teacher_feedback` (
  `teacher_feedback_id` varchar(20) NOT NULL,
  `project_id` varchar(20) NOT NULL,
  `teacher_id` varchar(20) NOT NULL,
  `comments` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_work`
--
ALTER TABLE `class_work`
  ADD PRIMARY KEY (`availability_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `S_Username` (`S_Username`),
  ADD UNIQUE KEY `S_Mail` (`S_Mail`);
ALTER TABLE `student` ADD FULLTEXT KEY `student_id` (`student_id`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`student_question_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `student_class`
--
ALTER TABLE `student_class`
  ADD PRIMARY KEY (`student_class_id`);

--
-- Indexes for table `student_level`
--
ALTER TABLE `student_level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_level_ibfk_1` (`student_id`);

--
-- Indexes for table `student_submit`
--
ALTER TABLE `student_submit`
  ADD PRIMARY KEY (`submit_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `T_Username` (`T_Username`),
  ADD UNIQUE KEY `T_Mail` (`T_Mail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `student_level`
--
ALTER TABLE `student_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_level`
--
ALTER TABLE `student_level`
  ADD CONSTRAINT `student_level_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
