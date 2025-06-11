-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-06-11 06:36:43
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
-- 表的结构 `teacher_materials`
--

CREATE TABLE `teacher_materials` (
  `material_id` varchar(255) NOT NULL DEFAULT 'uuid()',
  `teacher_id` varchar(20) DEFAULT NULL,
  `class_id` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `auto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `teacher_materials`
--

INSERT INTO `teacher_materials` (`material_id`, `teacher_id`, `class_id`, `title`, `description`, `file_name`, `create_time`, `auto_id`) VALUES
('M000001', 'TCH000001', 'CLS000001', 'AAAAA', '11111', 'Tutorial_04.docx', '2025-06-10 16:23:54', 1),
('M000005', 'TCH000001', 'CLS000001', '21221', '1w2w2e', 'Lab_8_(3).pdf', '2025-06-10 16:27:33', 5),
('M000006', 'TCH000001', 'CLS000002', 'gyguuh', 'huiijko', 'Tutorial_04_!.docx', '2025-06-10 16:28:50', 6),
('M000007', 'TCH000001', 'CLS000001', '11ww1ww1', 'w1w1ew1e', 'Tutorial_02_(2).docx', '2025-06-10 16:29:22', 7),
('M000008', 'TCH000001', 'CLS000001', '2', '12e2edd', 'Lab_7_Question.pdf', '2025-06-10 16:32:01', 8);

--
-- 触发器 `teacher_materials`
--
DELIMITER $$
CREATE TRIGGER `set_material_id` BEFORE INSERT ON `teacher_materials` FOR EACH ROW BEGIN
    DECLARE next_id INT;
    SELECT IFNULL(MAX(auto_id), 0) + 1 INTO next_id FROM `teacher_materials`;
    SET NEW.material_id = CONCAT('M', LPAD(next_id, 6, '0'));
END
$$
DELIMITER ;

--
-- 转储表的索引
--

--
-- 表的索引 `teacher_materials`
--
ALTER TABLE `teacher_materials`
  ADD PRIMARY KEY (`material_id`),
  ADD UNIQUE KEY `auto_id` (`auto_id`),
  ADD KEY `fk_tm_teacher_id` (`teacher_id`),
  ADD KEY `fk_tm_class_id` (`class_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `teacher_materials`
--
ALTER TABLE `teacher_materials`
  MODIFY `auto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 限制导出的表
--

--
-- 限制表 `teacher_materials`
--
ALTER TABLE `teacher_materials`
  ADD CONSTRAINT `fk_teacher_materials_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tm_class_id` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tm_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
