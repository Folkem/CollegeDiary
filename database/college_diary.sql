-- MySQL dump 10.13  Distrib 8.0.22, for Linux (x86_64)
--
-- Host: localhost    Database: college_diary
-- ------------------------------------------------------
-- Server version	8.0.22-0ubuntu0.20.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `call_schedule`
--

DROP TABLE IF EXISTS `call_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `call_schedule` (
  `number` int unsigned NOT NULL AUTO_INCREMENT,
  `start` time NOT NULL,
  `end` time NOT NULL,
  PRIMARY KEY (`number`),
  UNIQUE KEY `start` (`start`),
  UNIQUE KEY `end` (`end`),
  CONSTRAINT `call_schedule_chk_1` CHECK ((`end` > `start`))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `general_information`
--

DROP TABLE IF EXISTS `general_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `general_information` (
  `name` varchar(100) NOT NULL,
  `value` varchar(200) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grades` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_lesson` int unsigned NOT NULL,
  `id_student` int unsigned NOT NULL,
  `grade` varchar(3) DEFAULT NULL,
  `absent` enum('Відсутній','Присутній') NOT NULL DEFAULT 'Присутній',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_lesson` (`id_lesson`,`id_student`),
  KEY `id_student` (`id_student`),
  CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`id_lesson`) REFERENCES `lessons` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`id_student`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grades_chk_1` CHECK (((`grade` is null) or regexp_like(`grade`,_utf8mb3'^(100|[1-9][0-9]|[1-9])$'))),
  CONSTRAINT `grades_chk_2` CHECK (((`absent` = _utf8mb4'Присутній') or (`grade` is null)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_speciality` int unsigned NOT NULL,
  `group_number` char(1) NOT NULL,
  `group_year` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_speciality` (`id_speciality`,`group_number`,`group_year`),
  CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`id_speciality`) REFERENCES `specialities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `groups_chk_1` CHECK (regexp_like(`group_number`,_utf8mb3'^[1-9]$')),
  CONSTRAINT `groups_chk_2` CHECK (regexp_like(`group_year`,_utf8mb3'^[1-9]$'))
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `keywords`
--

DROP TABLE IF EXISTS `keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keywords` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lesson_schedules`
--

DROP TABLE IF EXISTS `lesson_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lesson_schedules` (
  `id_group` int unsigned NOT NULL,
  `id_discipline` int unsigned NOT NULL,
  `week_day` char(1) NOT NULL,
  `id_lesson_number` int unsigned NOT NULL,
  `variant` enum('Чисельник','Знаменник','Постійно') NOT NULL,
  UNIQUE KEY `id_group` (`id_group`,`week_day`,`id_lesson_number`),
  KEY `id_discipline` (`id_discipline`),
  KEY `id_lesson_number` (`id_lesson_number`),
  CONSTRAINT `lesson_schedules_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_schedules_ibfk_2` FOREIGN KEY (`id_discipline`) REFERENCES `work_distribution` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `lesson_schedules_ibfk_3` FOREIGN KEY (`id_lesson_number`) REFERENCES `call_schedule` (`number`) ON UPDATE CASCADE,
  CONSTRAINT `lesson_schedules_chk_1` CHECK (regexp_like(`week_day`,_utf8mb3'^[1-5]$'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lesson_types`
--

DROP TABLE IF EXISTS `lesson_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lesson_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lessons` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_discipline` int unsigned NOT NULL,
  `comment` text,
  `date` date NOT NULL,
  `id_lesson_type` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_discipline` (`id_discipline`),
  KEY `id_lesson_type` (`id_lesson_type`),
  CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`id_discipline`) REFERENCES `work_distribution` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `lessons_ibfk_2` FOREIGN KEY (`id_lesson_type`) REFERENCES `lesson_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `header` varchar(200) NOT NULL,
  `text` mediumtext NOT NULL,
  `date` datetime NOT NULL DEFAULT (_utf8mb4'2021-01-01'),
  `image_path` varchar(256) NOT NULL DEFAULT (_utf8mb4'temp.jpg'),
  PRIMARY KEY (`id`),
  UNIQUE KEY `header` (`header`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `news_comments`
--

DROP TABLE IF EXISTS `news_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_comments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_item` int unsigned NOT NULL,
  `id_user` int unsigned NOT NULL,
  `comment` varchar(256) NOT NULL,
  `date` datetime NOT NULL DEFAULT (now()),
  PRIMARY KEY (`id`),
  KEY `id_item` (`id_item`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `news_comments_ibfk_1` FOREIGN KEY (`id_item`) REFERENCES `news` (`id`),
  CONSTRAINT `news_comments_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `news_keywords`
--

DROP TABLE IF EXISTS `news_keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_keywords` (
  `id_news` int unsigned NOT NULL,
  `id_keyword` int unsigned NOT NULL,
  UNIQUE KEY `id_news` (`id_news`,`id_keyword`),
  KEY `id_keyword` (`id_keyword`),
  CONSTRAINT `news_keywords_ibfk_1` FOREIGN KEY (`id_news`) REFERENCES `news` (`id`),
  CONSTRAINT `news_keywords_ibfk_2` FOREIGN KEY (`id_keyword`) REFERENCES `keywords` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int unsigned NOT NULL,
  `comment` varchar(256) NOT NULL,
  `link` varchar(256) DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT (false),
  `date` datetime NOT NULL DEFAULT (now()),
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_role` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_role` (`user_role`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `specialities`
--

DROP TABLE IF EXISTS `specialities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `specialities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(60) NOT NULL,
  `code` char(3) NOT NULL,
  `short_name` varchar(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `full_name` (`full_name`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `short_name` (`short_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_group` int unsigned NOT NULL,
  `id_student` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_student` (`id_student`),
  UNIQUE KEY `id_group` (`id_group`,`id_student`),
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `students_ibfk_2` FOREIGN KEY (`id_student`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subgroups`
--

DROP TABLE IF EXISTS `subgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subgroups` (
  `id_discipline` int unsigned NOT NULL,
  `id_student` int unsigned NOT NULL,
  `subgroup_number` char(1) NOT NULL,
  PRIMARY KEY (`id_discipline`,`id_student`),
  UNIQUE KEY `id_discipline` (`id_discipline`,`id_student`,`subgroup_number`),
  KEY `id_student` (`id_student`),
  CONSTRAINT `subgroups_ibfk_1` FOREIGN KEY (`id_discipline`) REFERENCES `work_distribution` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subgroups_ibfk_2` FOREIGN KEY (`id_student`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subgroups_chk_1` CHECK (regexp_like(`subgroup_number`,_utf8mb3'^[1-9]$'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `full_name` varchar(300) GENERATED ALWAYS AS (concat(`last_name`,_utf8mb3' ',substr(`first_name`,1,1),_utf8mb3'.',if(((`middle_name` is null) or (`middle_name` = _utf8mb3'')),_utf8mb3'',concat(substr(`middle_name`,1,1),_utf8mb3'.')))) VIRTUAL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int unsigned NOT NULL DEFAULT '2',
  `avatar_path` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_fullname` (`first_name`,`last_name`),
  UNIQUE KEY `email` (`email`),
  KEY `users_fk_role` (`id_role`),
  CONSTRAINT `users_fk_role` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `users_email` CHECK (regexp_like(`email`,_utf8mb3'^[^@]+@[^@]+.[^@]{2,}$'))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `work_distribution`
--

DROP TABLE IF EXISTS `work_distribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_distribution` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `subgroup_number` char(1) DEFAULT NULL,
  `students_count` varchar(2) DEFAULT NULL,
  `id_group` int unsigned NOT NULL,
  `id_teacher` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`subgroup_number`,`id_group`),
  KEY `id_group` (`id_group`),
  KEY `id_teacher` (`id_teacher`),
  CONSTRAINT `work_distribution_ibfk_3` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `work_distribution_ibfk_4` FOREIGN KEY (`id_teacher`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `work_distribution_chk_1` CHECK (((`subgroup_number` is null) or regexp_like(`subgroup_number`,_utf8mb3'^[1-9]{1}$'))),
  CONSTRAINT `work_distribution_chk_2` CHECK (((`students_count` is null) or regexp_like(`students_count`,_utf8mb3'^([1-9]{1}[0-9]{1}|[1-9]{1})$')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-21 14:36:53