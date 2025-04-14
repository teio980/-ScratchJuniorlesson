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
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lessons` (
  `lesson_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`lesson_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lessons`
--

LOCK TABLES `lessons` WRITE;
/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
INSERT INTO `lessons` VALUES (1,'adf','adf','2025-03-17 15:30:00','2025-03-15 08:07:16'),(2,'ergebfefb','bfdndfndnn','2025-03-20 08:00:00','2025-03-18 12:00:14');
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `massage`
--

DROP TABLE IF EXISTS `massage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `massage` (
  `massage_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_Mail` varchar(50) NOT NULL,
  `massage_Subject` varchar(60) NOT NULL,
  `massage_Content` varchar(550) NOT NULL,
  PRIMARY KEY (`massage_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `massage`
--

LOCK TABLES `massage` WRITE;
/*!40000 ALTER TABLE `massage` DISABLE KEYS */;
INSERT INTO `massage` VALUES (1,'yongloon0927@gmail.com','Hello World','adssdasd'),(2,'yongloon0927@gmail.com','Hello World','adssdaszdfsadd');
/*!40000 ALTER TABLE `massage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (9,3,'Project1.sjr','../phpfile/uploads/Project1.sjr','2025-03-13 09:03:07');
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,'What is Scratch Junior used for?',1,'To create and edit documents','To design 3D models','To program interactive stories and games','To browse the internet',3),(2,'What does the \"Green Flag\" button do in ScratchJr?',4,'Starts the project','Stops the project','Deletes the project','Saves the project',1),(3,'What is the function of the \"Move Right\" block?',2,'Moves the character to the left','Moves the character to the right','Makes the character jump','Stops the character',2),(4,'Which block makes a character jump?',2,'Move Right','Move Left','Jump','Stop',3),(5,'What does the \"Move Left\" block do?',4,'Moves the character to the right','Moves the character up','Moves the character to the left','Stops the character',3),(6,'Which block makes a character say something?',3,'Speak','Jump','Hide','Stop',1),(7,'What does the \"Hide\" block do?',1,'Makes the character invisible','Moves the character up','Stops the project','Deletes the character',1),(8,'What happens when you use the \"Stop\" block?',3,'The project stops completely','The character moves faster','The character jumps','The project restarts',1),(9,'Which block makes the character grow bigger?',4,'Shrink','Enlarge','Jump','Hide',2),(10,'Which block makes the character shrink?',1,'Shrink','Enlarge','Move Right','Speak',1),(11,'What is the function of the \"Wait\" block?',2,'Makes the character move faster','Delays the next action','Stops the project','Speeds up the project',2),(12,'What does the \"Go to Start\" block do?',3,'Moves the character to a random position','Moves the character to its starting position','Deletes the character','Speeds up the project',2),(13,'Which block makes the character rotate?',2,'Move Right','Rotate','Jump','Stop',2),(14,'What does the \"Repeat\" block do?',5,'Stops the project','Makes the action repeat multiple times','Speeds up the project','Moves the character in a random direction',2),(15,'What happens when you use the \"End\" block?',3,'The project restarts','The project stops at the current moment','The character disappears','The character moves faster',2),(16,'Which block makes a character appear again after using the \"Hide\" block?',5,'Jump','Show','Move Right','Stop',2),(17,'Which block is used to change the background?',4,'Change Background','Move Left','Jump','Stop',1),(18,'What does the \"Speed\" block do?',1,'Changes the speed of a character\'s movement','Makes the character jump higher','Stops the project','Deletes the character',1),(19,'What does the \"Send Message\" block do?',2,'Sends a message to another character or scene','Moves the character to a random position','Stops the project','Speeds up the project',1),(20,'Which block waits for a message before starting an action?',3,'Move Right','Wait for Message','Jump','Stop',2),(21,'What does the \"Play Sound\" block do?',3,'Plays a sound or recorded voice','Stops the project','Makes the character move faster','Deletes the character',1),(28,'xc b xcbxfbdfbdgfbhdgndgn',1,'davsc','svsfcv','sbs','sbs',2),(30,'thettyuetuteu',6,'wry','eth','ty','tey',4);
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_answers`
--

DROP TABLE IF EXISTS `student_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_answers` (
  `student_answers_ID` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `student_answer` varchar(50) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `difficulty` int(11) NOT NULL,
  PRIMARY KEY (`student_answers_ID`),
  UNIQUE KEY `student_id` (`student_id`,`question_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`U_ID`) ON DELETE CASCADE,
  CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_answers`
--

LOCK TABLES `student_answers` WRITE;
/*!40000 ALTER TABLE `student_answers` DISABLE KEYS */;
INSERT INTO `student_answers` VALUES (164,6,1,'3',1,1),(165,6,7,'1',1,1),(166,6,10,'1',1,1),(167,6,18,'1',1,1),(168,6,28,'2',1,1),(169,7,1,'3',1,1),(170,7,7,'2',0,1),(171,7,10,'1',1,1),(172,7,18,'1',1,1),(173,7,28,'2',1,1),(189,7,3,'1',0,2),(190,7,4,'1',0,2),(191,7,11,'1',0,2),(192,7,13,'1',0,2),(193,7,19,'1',1,2),(194,6,3,'2',1,2),(195,6,4,'2',0,2),(196,6,11,'4',0,2),(197,6,13,'1',0,2),(198,6,19,'4',0,2);
/*!40000 ALTER TABLE `student_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `U_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_Username` varchar(50) NOT NULL,
  `U_Password` varchar(200) NOT NULL,
  `U_Mail` varchar(100) NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `identity` enum('admin','teacher','student') NOT NULL,
  PRIMARY KEY (`U_ID`),
  UNIQUE KEY `reset_token` (`reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Liaw666','$2y$10$DVZFG8exTYR.xAj7/AXwpOAckaHYWnUxg8WIGeyFTxNPc79yLbl6u','yongloon0927@gmail.com',NULL,NULL,'student'),(2,'Liaw12345','$2y$10$Bge4wWMtU9lKetUdzll2QeZ2Kb4.SrhhyWUaEXysiH5VQXX6Uu6Du','yongloon123@gmail.com',NULL,NULL,'student'),(3,'Liaw0000','$2y$10$S.521YlauzsQsmn2U94Q5.nfETe0dmvoJa6oUW1YgKga1MgY5aG0O','yongloon1234@gmail.com',NULL,NULL,'admin'),(4,'Liaw8899','$2y$10$w4xNlr6UheMqUtIE/ijyuefhJCHQ6hod04HZUxOesr3b8D22lJqoG','yongloon8899@gmail.com',NULL,NULL,'student'),(5,'Liaw123','$2y$10$UmyiyjIkJ528yAF7OWc4.e5TchuE14s/rOwmIzv5L/2wyLx86WsHu','Liaw12345@gamil.com',NULL,NULL,'teacher'),(6,'Kong666','$2y$10$Ln/D0HtuLwdOVaxSkqmMfOvC6u8Gzk3lP4MsCe1hDlLYcGJDyxlE6','kongwenkhang@gmail.com',NULL,NULL,'student'),(7,'Kong2383','$2y$10$S.jmpeWkWJQlUJY2uv3EKObrWLazUWnaGOc/p7wztv1dAdc7C0.uW','kongwenkhangg@gmail.com',NULL,NULL,'student');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-14 14:34:23
