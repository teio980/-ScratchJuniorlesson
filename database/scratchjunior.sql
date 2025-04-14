-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: scratchjunior
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `admin_id` varchar(20) NOT NULL,
  `A_Username` varchar(50) NOT NULL,
  `A_Password` varchar(200) NOT NULL,
  `A_Mail` varchar(100) NOT NULL,
  `identity` enum('admin') NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `A_Username` (`A_Username`),
  UNIQUE KEY `A_Mail` (`A_Mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES ('T00000001','Liaw0000','$2y$10$S.521YlauzsQsmn2U94Q5.nfETe0dmvoJa6oUW1YgKga1MgY5aG0O','yongloon1234@gmail.com','admin');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class` (
  `class_id` varchar(20) NOT NULL,
  `class_code` varchar(7) NOT NULL CHECK (`class_code` regexp '^[A-Z]{3}[0-9]{4}$'),
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_code` (`class_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class`
--

LOCK TABLES `class` WRITE;
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
/*!40000 ALTER TABLE `class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `feedback_id` varchar(20) NOT NULL,
  `project_id` varchar(20) NOT NULL,
  `teacher_id` varchar(20) NOT NULL,
  `comments` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lessons` (
  `lesson_id` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lessons`
--

LOCK TABLES `lessons` WRITE;
/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `massage`
--

DROP TABLE IF EXISTS `massage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `massage` (
  `massage_ID` varchar(20) NOT NULL,
  `U_phoneNumber` varchar(50) NOT NULL,
  `massage_Content` varchar(550) NOT NULL,
  PRIMARY KEY (`massage_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `massage`
--

LOCK TABLES `massage` WRITE;
/*!40000 ALTER TABLE `massage` DISABLE KEYS */;
/*!40000 ALTER TABLE `massage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` varchar(20) NOT NULL,
  `lesson_id` varchar(20) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `difficult` int(11) NOT NULL,
  `option1` varchar(255) NOT NULL,
  `option2` varchar(255) NOT NULL,
  `option3` varchar(255) NOT NULL,
  `option4` varchar(255) NOT NULL,
  `answer` int(11) NOT NULL CHECK (`answer` between 1 and 4),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,'What is Scratch Junior used for?',1,'To create and edit documents','To design 3D models','To program interactive stories and games','To browse the internet',3),(2,'What does the \"Green Flag\" button do in ScratchJr?',4,'Starts the project','Stops the project','Deletes the project','Saves the project',1),(3,'What is the function of the \"Move Right\" block?',2,'Moves the character to the left','Moves the character to the right','Makes the character jump','Stops the character',2),(4,'Which block makes a character jump?',2,'Move Right','Move Left','Jump','Stop',3),(5,'What does the \"Move Left\" block do?',4,'Moves the character to the right','Moves the character up','Moves the character to the left','Stops the character',3),(6,'Which block makes a character say something?',3,'Speak','Jump','Hide','Stop',1),(7,'What does the \"Hide\" block do?',1,'Makes the character invisible','Moves the character up','Stops the project','Deletes the character',1),(8,'What happens when you use the \"Stop\" block?',3,'The project stops completely','The character moves faster','The character jumps','The project restarts',1),(9,'Which block makes the character grow bigger?',4,'Shrink','Enlarge','Jump','Hide',2),(10,'Which block makes the character shrink?',1,'Shrink','Enlarge','Move Right','Speak',1),(11,'What is the function of the \"Wait\" block?',2,'Makes the character move faster','Delays the next action','Stops the project','Speeds up the project',2),(12,'What does the \"Go to Start\" block do?',3,'Moves the character to a random position','Moves the character to its starting position','Deletes the character','Speeds up the project',2),(13,'Which block makes the character rotate?',2,'Move Right','Rotate','Jump','Stop',2),(14,'What does the \"Repeat\" block do?',5,'Stops the project','Makes the action repeat multiple times','Speeds up the project','Moves the character in a random direction',2),(15,'What happens when you use the \"End\" block?',3,'The project restarts','The project stops at the current moment','The character disappears','The character moves faster',2),(16,'Which block makes a character appear again after using the \"Hide\" block?',5,'Jump','Show','Move Right','Stop',2),(17,'Which block is used to change the background?',4,'Change Background','Move Left','Jump','Stop',1),(18,'What does the \"Speed\" block do?',1,'Changes the speed of a character\'s movement','Makes the character jump higher','Stops the project','Deletes the character',1),(19,'What does the \"Send Message\" block do?',2,'Sends a message to another character or scene','Moves the character to a random position','Stops the project','Speeds up the project',1),(20,'Which block waits for a message before starting an action?',3,'Move Right','Wait for Message','Jump','Stop',2),(21,'What does the \"Play Sound\" block do?',3,'Plays a sound or recorded voice','Stops the project','Makes the character move faster','Deletes the character',1);
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resetpassword`
--

DROP TABLE IF EXISTS `resetpassword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resetpassword` (
  `resetPassword_id` varchar(20) NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `teacher_id` varchar(20) DEFAULT NULL,
  `T_Mail` varchar(100) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `S_Mail` varchar(100) DEFAULT NULL,
  `admin_id` varchar(20) DEFAULT NULL,
  `A_Mail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`resetPassword_id`),
  UNIQUE KEY `reset_token` (`reset_token`),
  KEY `teacher_id` (`teacher_id`),
  KEY `student_id` (`student_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `resetpassword_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE,
  CONSTRAINT `resetpassword_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  CONSTRAINT `resetpassword_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resetpassword`
--

LOCK TABLES `resetpassword` WRITE;
/*!40000 ALTER TABLE `resetpassword` DISABLE KEYS */;
/*!40000 ALTER TABLE `resetpassword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `student_id` varchar(20) NOT NULL,
  `S_Username` varchar(50) NOT NULL,
  `S_Password` varchar(200) NOT NULL,
  `S_Mail` varchar(100) NOT NULL,
  `identity` enum('student') NOT NULL DEFAULT 'student',
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `S_Username` (`S_Username`),
  UNIQUE KEY `S_Mail` (`S_Mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES ('STU2025000001','Student_1','','Student1@gmail.com','student');
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_class`
--

DROP TABLE IF EXISTS `student_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_class` (
  `student_class_id` varchar(20) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `enroll_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`student_class_id`),
  UNIQUE KEY `student_id` (`student_id`,`class_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `student_class_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  CONSTRAINT `student_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_class`
--

LOCK TABLES `student_class` WRITE;
/*!40000 ALTER TABLE `student_class` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_questions`
--

DROP TABLE IF EXISTS `student_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_questions` (
  `student_question_id` varchar(20) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `question_id` int(11) NOT NULL,
  `student_answer` varchar(50) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `difficult` int(11) DEFAULT NULL,
  PRIMARY KEY (`student_question_id`),
  KEY `student_id` (`student_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `student_questions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  CONSTRAINT `student_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_questions`
--

LOCK TABLES `student_questions` WRITE;
/*!40000 ALTER TABLE `student_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher` (
  `teacher_id` varchar(20) NOT NULL,
  `T_Username` varchar(50) NOT NULL,
  `T_Password` varchar(200) NOT NULL,
  `T_Mail` varchar(100) NOT NULL,
  `identity` enum('teacher') NOT NULL DEFAULT 'teacher',
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `T_Username` (`T_Username`),
  UNIQUE KEY `T_Mail` (`T_Mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher`
--

LOCK TABLES `teacher` WRITE;
/*!40000 ALTER TABLE `teacher` DISABLE KEYS */;
INSERT INTO `teacher` VALUES ('STU2025000002','Test123','$2y$10$FcqYSjS0U3EFCsyeTpJlGuTUWJuX5jyC8.DfBR2FNzrFOy/sHR7vK','yongloon0927@gmail.com','teacher');
/*!40000 ALTER TABLE `teacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_class`
--

DROP TABLE IF EXISTS `teacher_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_class` (
  `teacher_class_id` varchar(20) NOT NULL,
  `teacher_id` varchar(20) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  PRIMARY KEY (`teacher_class_id`),
  UNIQUE KEY `teacher_id` (`teacher_id`,`class_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `teacher_class_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_class`
--

LOCK TABLES `teacher_class` WRITE;
/*!40000 ALTER TABLE `teacher_class` DISABLE KEYS */;
/*!40000 ALTER TABLE `teacher_class` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-14 14:44:25
