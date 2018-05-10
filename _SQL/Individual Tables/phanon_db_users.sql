-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: phanon_db
-- ------------------------------------------------------
-- Server version	5.7.21-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Joseph Valentin','valejose@isu.edu','$2y$10$5BsnX5c/p5cOu5kA7OLo4./VTgewlPiyTXFuke2/8iVgUQa.nHf/2','wlRSv1NSQsBiWLr9HeHGzA3XlLWgulEiHF2xsVsDWX8lbQvcI0Zenq2OJjsa','2018-05-09 02:26:47','2018-05-09 02:26:47'),(2,'Test Student 1','teststudent1@isu.edu','$2y$10$9.hfij0izg9WjpP3hs0L5O78VvhP5vyHYrit37tzBg4Wn8mQ8FyxS','QHuAkkI4W5UOO3TcnN9vqjRWtuZSLGrSY1s8GCT0FQuIiPgeqHFoc3ExboXk','2018-05-09 02:48:09','2018-05-09 02:48:09'),(3,'Test Student 2','teststudent2@isu.edu','$2y$10$aD1JbA8nbtonQ7XzvGJ1.ugwZVKcty4qYtSiSD4P5q.ygjQeNiVoW','S41rLmqiiRcfyURCjLAxHWEnTeEIk669tcoG8QAk5K9L6xZan39tgWtYuj3s','2018-05-09 03:01:23','2018-05-09 03:01:23'),(4,'Test Student 3','teststudent3@isu.edu','$2y$10$53/tUpwl4908zX.XtZimD.Wp49xNe2vvX2yrcIIqqKdbGFFBenpAC','EaRJV2BS4Ccwf3jGr65TDDWpe87Z6ghnLzUnqbR2Cd5DmA3MjBgFVpp6Mwtd','2018-05-09 03:02:40','2018-05-09 03:02:40');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-09 17:37:21
