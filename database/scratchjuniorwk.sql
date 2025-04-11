-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 07:39 AM
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
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`lesson_id`, `title`, `description`, `expire_date`, `create_time`) VALUES
(1, 'adf', 'adf', '2025-03-17 15:30:00', '2025-03-15 08:07:16'),
(2, 'ergebfefb', 'bfdndfndnn', '2025-03-20 08:00:00', '2025-03-18 12:00:14');

-- --------------------------------------------------------

--
-- Table structure for table `massage`
--

CREATE TABLE `massage` (
  `massage_ID` int(11) NOT NULL,
  `U_Mail` varchar(50) NOT NULL,
  `massage_Subject` varchar(60) NOT NULL,
  `massage_Content` varchar(550) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `massage`
--

INSERT INTO `massage` (`massage_ID`, `U_Mail`, `massage_Subject`, `massage_Content`) VALUES
(1, 'yongloon0927@gmail.com', 'Hello World', 'adssdasd'),
(2, 'yongloon0927@gmail.com', 'Hello World', 'adssdaszdfsadd');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `lesson_id`, `filename`, `filepath`, `upload_time`) VALUES
(9, 3, 'Project1.sjr', '../phpfile/uploads/Project1.sjr', '2025-03-13 09:03:07');

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
(28, 'xc b xcbxfbdfbdgfbhdgndgn', 1, 'davsc', 'svsfcv', 'sbs', 'sbs', 2),
(29, 'sg', 10, 'gsfgf', 'sfg', 'sfg', 'sfg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `student_answers_ID` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `student_answer` varchar(50) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `difficulty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`student_answers_ID`, `student_id`, `question_id`, `student_answer`, `is_correct`, `difficulty`) VALUES
(58, 6, 1, '3', 1, 1),
(59, 6, 7, '1', 1, 1),
(60, 6, 10, '1', 1, 1),
(61, 6, 18, '1', 1, 1),
(62, 6, 28, '2', 1, 1),
(63, 6, 3, '3', 0, 2),
(64, 6, 4, '2', 0, 2),
(65, 6, 11, '2', 1, 2),
(66, 6, 13, '2', 1, 2),
(67, 6, 19, '2', 0, 2),
(68, 1, 1, '3', 1, 1),
(69, 1, 7, '2', 0, 1),
(70, 1, 10, '1', 1, 1),
(71, 1, 18, '2', 0, 1),
(72, 1, 28, '3', 0, 1),
(73, 7, 1, '2', 0, 1),
(74, 7, 7, '1', 1, 1),
(75, 7, 10, '1', 1, 1),
(76, 7, 18, '1', 1, 1),
(77, 7, 28, '3', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `U_ID` int(11) NOT NULL,
  `U_Username` varchar(50) NOT NULL,
  `U_Password` varchar(200) NOT NULL,
  `U_Mail` varchar(100) NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `identity` enum('admin','teacher','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`U_ID`, `U_Username`, `U_Password`, `U_Mail`, `reset_token`, `reset_token_expires`, `identity`) VALUES
(1, 'Liaw666', '$2y$10$DVZFG8exTYR.xAj7/AXwpOAckaHYWnUxg8WIGeyFTxNPc79yLbl6u', 'yongloon0927@gmail.com', NULL, NULL, 'student'),
(2, 'Liaw12345', '$2y$10$Bge4wWMtU9lKetUdzll2QeZ2Kb4.SrhhyWUaEXysiH5VQXX6Uu6Du', 'yongloon123@gmail.com', NULL, NULL, 'student'),
(3, 'Liaw0000', '$2y$10$S.521YlauzsQsmn2U94Q5.nfETe0dmvoJa6oUW1YgKga1MgY5aG0O', 'yongloon1234@gmail.com', NULL, NULL, 'admin'),
(4, 'Liaw8899', '$2y$10$w4xNlr6UheMqUtIE/ijyuefhJCHQ6hod04HZUxOesr3b8D22lJqoG', 'yongloon8899@gmail.com', NULL, NULL, 'student'),
(5, 'Liaw123', '$2y$10$UmyiyjIkJ528yAF7OWc4.e5TchuE14s/rOwmIzv5L/2wyLx86WsHu', 'Liaw12345@gamil.com', NULL, NULL, 'teacher'),
(6, 'Kong666', '$2y$10$Ln/D0HtuLwdOVaxSkqmMfOvC6u8Gzk3lP4MsCe1hDlLYcGJDyxlE6', 'kongwenkhang@gmail.com', NULL, NULL, 'student'),
(7, 'Kong2383', '$2y$10$S.jmpeWkWJQlUJY2uv3EKObrWLazUWnaGOc/p7wztv1dAdc7C0.uW', 'kongwenkhangg@gmail.com', NULL, NULL, 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`);

--
-- Indexes for table `massage`
--
ALTER TABLE `massage`
  ADD PRIMARY KEY (`massage_ID`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`student_answers_ID`),
  ADD UNIQUE KEY `student_id` (`student_id`,`question_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`U_ID`),
  ADD UNIQUE KEY `reset_token` (`reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `massage`
--
ALTER TABLE `massage`
  MODIFY `massage_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `student_answers_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `U_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`U_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
