-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 11:07 AM
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
-- Table structure for table `student_livechat`
--

CREATE TABLE `student_livechat` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `chat` text NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_livechat`
--

INSERT INTO `student_livechat` (`id`, `student_id`, `chat`, `createtime`) VALUES
(4, 'STU2025000002', 'gfhjfj', '2025-05-10 07:45:31'),
(5, 'STU2025000003', 'dasfdfdf😍🤯', '2025-05-10 07:45:50'),
(6, 'STU2025000003', 'fafafdsf😎🤯', '2025-05-10 08:05:54'),
(7, 'STU2025000003', '😍', '2025-05-10 08:25:52'),
(8, 'STU2025000003', 'gfhjfj', '2025-05-10 08:26:46'),
(9, 'STU2025000003', '🤪', '2025-05-10 08:30:02'),
(10, 'STU2025000003', '😉', '2025-05-10 08:31:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student_livechat`
--
ALTER TABLE `student_livechat`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student_livechat`
--
ALTER TABLE `student_livechat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
