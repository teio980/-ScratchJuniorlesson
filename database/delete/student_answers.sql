-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 12:16 PM
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
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `student_answers_ID` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `student_answer` varchar(50) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`student_answers_ID`, `student_id`, `question_id`, `student_answer`, `is_correct`) VALUES
(5, 2, 1, '3', 0),
(6, 2, 7, '1', 0),
(7, 2, 10, '1', 0),
(8, 2, 18, '1', 0),
(15, 2, 3, '2', 1),
(16, 2, 4, '3', 1),
(17, 2, 11, '1', 0),
(18, 2, 13, '1', 0),
(19, 2, 19, '1', 1),
(20, 2, 6, '2', 0),
(21, 2, 8, '1', 1),
(22, 2, 12, '1', 0),
(23, 2, 15, '1', 0),
(24, 2, 20, '1', 0),
(25, 2, 21, '1', 1),
(42, 1, 29, '4', 1),
(43, 1, 1, '3', 1),
(44, 1, 7, '1', 1),
(45, 1, 10, '1', 1),
(46, 1, 18, '1', 1),
(47, 1, 28, '2', 1),
(48, 1, 3, '2', 1),
(49, 1, 4, '3', 1),
(50, 1, 11, '2', 1),
(51, 1, 13, '1', 0),
(52, 1, 19, '4', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`student_answers_ID`),
  ADD UNIQUE KEY `student_id` (`student_id`,`question_id`),
  ADD KEY `question_id` (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `student_answers_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

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
