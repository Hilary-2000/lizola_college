-- MySQL dump 10.13  Distrib 5.7.36, for Linux (x86_64)
--
-- Host: localhost    Database: testimonytbl1
-- ------------------------------------------------------
-- Server version	5.7.36

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
-- Table structure for table `academic_calendar`
--

DROP TABLE IF EXISTS `academic_calendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `academic_calendar` (
  `term` varchar(10) NOT NULL,
  `start_time` date NOT NULL,
  `end_time` date NOT NULL,
  `closing_date` date NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academic_calendar`
--

LOCK TABLES `academic_calendar` WRITE;
/*!40000 ALTER TABLE `academic_calendar` DISABLE KEYS */;
INSERT INTO `academic_calendar` VALUES ('TERM_1','2021-09-01','2021-12-31','2021-11-29',1),('TERM_2','2022-01-01','2022-03-30','2022-03-16',2),('TERM_3','2022-04-04','2022-06-01','2022-06-01',3);
/*!40000 ALTER TABLE `academic_calendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendancetable`
--

DROP TABLE IF EXISTS `attendancetable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendancetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admission_no` int(10) NOT NULL,
  `class` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `signedby` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendancetable`
--

LOCK TABLES `attendancetable` WRITE;
/*!40000 ALTER TABLE `attendancetable` DISABLE KEYS */;
INSERT INTO `attendancetable` VALUES (1,1,'8','2021-09-20','HILARY'),(2,2,'8','2021-09-20','HILARY'),(3,3,'7','2021-09-20','HILARY'),(4,1,'8','2021-09-21','IAN'),(5,2,'8','2021-09-21','IAN'),(6,1,'8','2021-09-22','HILARY'),(7,2,'8','2021-09-22','HILARY'),(8,3,'7','2021-09-22','HILARY'),(9,1,'8','2021-09-23','HILARY'),(10,2,'8','2021-09-23','HILARY'),(11,5,'8','2021-09-23','HILARY'),(12,6,'GRADE1','2021-09-26','HILARY'),(13,1,'8','2021-09-30','HILARY'),(14,2,'8','2021-09-30','HILARY'),(15,5,'8','2021-09-30','HILARY'),(16,1,'8','2021-10-02','HILARY'),(17,2,'8','2021-10-02','HILARY'),(18,5,'8','2021-10-02','HILARY'),(19,1,'8','2021-10-04','HILARY'),(20,2,'8','2021-10-04','HILARY'),(21,5,'8','2021-10-04','HILARY'),(22,7,'8','2021-10-04','HILARY'),(23,3,'7','2021-10-04','HILARY'),(24,4,'7','2021-10-04','HILARY'),(25,6,'GRADE1','2021-10-04','HILARY');
/*!40000 ALTER TABLE `attendancetable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boarding_list`
--

DROP TABLE IF EXISTS `boarding_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boarding_list` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `student_id` int(30) NOT NULL,
  `dorm_id` int(10) NOT NULL,
  `date_of_enrollment` date NOT NULL,
  `deleted` int(1) NOT NULL,
  `activated` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boarding_list`
--

LOCK TABLES `boarding_list` WRITE;
/*!40000 ALTER TABLE `boarding_list` DISABLE KEYS */;
INSERT INTO `boarding_list` VALUES (2,3,1,'2021-09-20',0,1),(4,2,2,'2021-09-24',0,1),(5,5,2,'2021-10-01',0,1),(7,10,1,'2021-10-04',0,1),(8,11,1,'2021-10-04',0,1),(9,12,1,'2021-10-04',0,1),(10,13,2,'2021-10-04',0,1),(12,16,2,'2021-10-13',0,1),(13,28,2,'2021-10-13',0,1),(14,14,1,'2021-10-13',0,1),(15,17,1,'2021-10-13',0,1),(16,18,1,'2021-10-13',0,1);
/*!40000 ALTER TABLE `boarding_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_teacher_tbl`
--

DROP TABLE IF EXISTS `class_teacher_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_teacher_tbl` (
  `class_teacher_id` int(10) NOT NULL,
  `class_assigned` varchar(30) NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_teacher_tbl`
--

LOCK TABLES `class_teacher_tbl` WRITE;
/*!40000 ALTER TABLE `class_teacher_tbl` DISABLE KEYS */;
INSERT INTO `class_teacher_tbl` VALUES (2,'1',1),(3,'2',1),(5,'3',1),(26,'4',1),(6,'5',1),(8,'6',1),(10,'7',1),(12,'8',1);
/*!40000 ALTER TABLE `class_teacher_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dorm_list`
--

DROP TABLE IF EXISTS `dorm_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dorm_list` (
  `dorm_id` int(30) NOT NULL AUTO_INCREMENT,
  `dorm_name` varchar(200) NOT NULL,
  `dorm_capacity` int(30) NOT NULL,
  `dorm_captain` varchar(200) NOT NULL,
  `activated` int(1) NOT NULL,
  `deleted` int(1) NOT NULL,
  PRIMARY KEY (`dorm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dorm_list`
--

LOCK TABLES `dorm_list` WRITE;
/*!40000 ALTER TABLE `dorm_list` DISABLE KEYS */;
INSERT INTO `dorm_list` VALUES (1,'Mt Sinai Dormitory',10,'9',1,0),(2,'Mt Longonot',40,'3',1,0);
/*!40000 ALTER TABLE `dorm_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exam_record_tbl`
--

DROP TABLE IF EXISTS `exam_record_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_record_tbl` (
  `result_id` int(30) NOT NULL AUTO_INCREMENT,
  `exam_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `exam_marks` int(30) NOT NULL,
  `exam_grade` varchar(10) NOT NULL,
  `filled_by` varchar(100) NOT NULL,
  `class name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`result_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam_record_tbl`
--

LOCK TABLES `exam_record_tbl` WRITE;
/*!40000 ALTER TABLE `exam_record_tbl` DISABLE KEYS */;
INSERT INTO `exam_record_tbl` VALUES (1,2,15,1,25,'-','1','5'),(2,2,14,1,20,'-','1','5'),(3,2,13,1,32,'-','1','5'),(4,2,12,1,36,'-','1','5'),(6,2,10,1,33,'-','1','5'),(9,2,15,2,45,'-','1','5'),(11,2,13,2,66,'-','1','5'),(13,2,11,2,33,'-','1','5'),(14,2,10,2,43,'-','1','5'),(15,2,9,2,65,'-','1','5'),(17,2,15,3,20,'-','1','5'),(18,2,14,3,30,'-','1','5'),(19,2,13,3,15,'-','1','5'),(20,2,12,3,20,'-','1','5'),(21,2,11,3,30,'-','1','5'),(22,2,10,3,48,'-','1','5'),(23,2,9,3,40,'-','1','5'),(24,2,8,3,45,'-','1','5'),(25,2,8,2,80,'-','1','5'),(26,2,8,1,44,'-','1','5'),(27,2,11,1,25,'-','1','5'),(28,2,9,1,36,'-','1','5'),(29,2,12,2,54,'-','1','5'),(30,2,14,2,68,'-','1','5'),(31,2,15,5,3,'M.E','1','5'),(32,2,14,5,4,'E.E','1','5'),(33,2,13,5,2,'A.E','1','5'),(34,2,12,5,4,'E.E','1','5'),(35,2,11,5,3,'M.E','1','5'),(36,2,10,5,3,'M.E','1','5'),(37,2,9,5,3,'M.E','1','5'),(38,2,8,5,4,'E.E','1','5'),(39,2,8,4,78,'-','1','5'),(40,2,15,4,78,'-','1','5'),(41,2,14,4,50,'-','1','5'),(42,2,13,4,66,'-','1','5'),(43,2,12,4,70,'-','1','5'),(44,2,11,4,88,'-','1','5'),(45,2,10,4,65,'-','1','5'),(46,2,9,4,84,'-','1','5'),(47,2,15,6,50,'-','1','5'),(48,2,14,6,46,'-','1','5'),(49,2,13,6,40,'-','1','5'),(50,2,12,6,45,'-','1','5'),(51,2,11,6,58,'-','1','5'),(52,2,10,6,63,'-','1','5'),(53,2,9,6,45,'-','1','5'),(54,2,8,6,33,'-','1','5');
/*!40000 ALTER TABLE `exam_record_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exams_tbl`
--

DROP TABLE IF EXISTS `exams_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exams_tbl` (
  `exams_id` int(11) NOT NULL AUTO_INCREMENT,
  `exams_name` varchar(100) NOT NULL,
  `curriculum` varchar(300) NOT NULL,
  `class_sitting` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `subject_done` varchar(500) NOT NULL,
  `target_mean_score` int(11) NOT NULL,
  `deleted` int(1) NOT NULL,
  PRIMARY KEY (`exams_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exams_tbl`
--

LOCK TABLES `exams_tbl` WRITE;
/*!40000 ALTER TABLE `exams_tbl` DISABLE KEYS */;
INSERT INTO `exams_tbl` VALUES (1,'MATH CONTEST','844','(1,2,3,4,5,6,7,8)','2021-09-14','2021-09-16','(2)',77,0),(2,'TESO SOUTH MOCK','844','(5,6,7,8)','2021-10-06','2021-10-12','(1,2,3,4,5,6)',300,0);
/*!40000 ALTER TABLE `exams_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `expid` int(11) NOT NULL AUTO_INCREMENT,
  `exp_name` varchar(100) NOT NULL,
  `exp_category` varchar(300) NOT NULL,
  `unit_name` varchar(30) DEFAULT NULL,
  `exp_quantity` int(30) NOT NULL,
  `exp_unit_cost` int(30) NOT NULL,
  `exp_amount` int(30) NOT NULL DEFAULT '0',
  `expense_date` date NOT NULL,
  `exp_time` varchar(10) NOT NULL,
  `exp_active` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`expid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,'MAIZE','utility','',90,66,5940,'2021-09-13','14:54:42',0),(2,'KRA','taxes','',1,15000,15000,'2021-09-13','14:58:46',0),(3,'Nurse','Medical','',2,2200,4400,'2021-09-13','16:18:46',0),(4,'HONEY','utility','lts',1,600,600,'2021-09-20','09:10:13',0),(5,'Unga','utility','Kgs',100,100,10000,'2021-09-21','17:15:24',0),(6,'Rice','daily-expense','Kgs',20,130,2600,'2021-09-22','08:41:17',0),(7,'Sugar','daily-expense','',1,80,80,'2021-10-04','13:55:55',0),(8,'RIce','daily-expense','Kg',1,80,80,'2021-10-06','10:07:46',0),(9,'WATER','daily-expense','LITRES',12,10,120,'2021-10-12','11:59:52',0),(10,'BREAD','labour','',100,50,5000,'2021-10-12','12:00:23',0),(11,'LICENCE','taxes','',1,1000,1000,'2021-10-12','12:02:38',0),(12,'SERVICE','labour','kgs',100,10,1000,'2021-10-13','11:47:44',0),(13,'KODI','Rent','',1,45000,45000,'2021-10-16','11:52:12',0);
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fees_structure`
--

DROP TABLE IF EXISTS `fees_structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_structure` (
  `expenses` varchar(100) NOT NULL,
  `TERM_1` int(10) NOT NULL,
  `TERM_2` int(10) NOT NULL,
  `TERM_3` int(100) NOT NULL,
  `classes` varchar(100) NOT NULL DEFAULT '0-11',
  `ids` int(100) NOT NULL AUTO_INCREMENT,
  `activated` int(1) NOT NULL DEFAULT '1',
  `roles` varchar(30) NOT NULL,
  PRIMARY KEY (`ids`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees_structure`
--

LOCK TABLES `fees_structure` WRITE;
/*!40000 ALTER TABLE `fees_structure` DISABLE KEYS */;
INSERT INTO `fees_structure` VALUES ('UNIFORM',5000,0,0,'|GRADE1|,|8|,|7|,|6|,|5|,|4|,|3|,|2|,|1|',1,1,'regular'),('TUITION2',20000,18000,15000,'|3|,|2|,|1|',3,1,'regular'),('BOARDING1',5000,4000,3000,'|8|,|7|,|6|,|5|,|4|',4,1,'boarding'),('Lunch',200,200,200,'|GRADE1|,|8|,|7|,|6|,|5|,|4|,|3|,|2|,|1|',5,1,'regular'),('Tuition1',10000,8000,6000,'|GRADE1|,|8|,|7|,|6|,|5|,|4|,|3|,|2|,|1|',6,1,'regular');
/*!40000 ALTER TABLE `fees_structure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finance`
--

DROP TABLE IF EXISTS `finance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `finance` (
  `stud_admin` int(30) NOT NULL,
  `transaction_id` int(30) NOT NULL AUTO_INCREMENT,
  `time_of_transaction` varchar(30) NOT NULL,
  `date_of_transaction` varchar(30) NOT NULL,
  `transaction_code` varchar(100) NOT NULL DEFAULT '0',
  `amount` int(10) NOT NULL DEFAULT '0',
  `balance` int(10) NOT NULL DEFAULT '0',
  `payment_for` varchar(100) NOT NULL,
  `payBy` varchar(100) NOT NULL DEFAULT 'sys',
  `mode_of_pay` varchar(50) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `idsd` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finance`
--

LOCK TABLES `finance` WRITE;
/*!40000 ALTER TABLE `finance` DISABLE KEYS */;
INSERT INTO `finance` VALUES (1,1,'13:37:52','2021-09-13','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(1,2,'14:40:31','2021-09-13','cash',23000,8000,'Tuition1','1','cash',0,'0'),(2,3,'15:19:49','2021-09-13','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(2,4,'16:20:29','2021-09-13','cash',25000,6000,'Tuition1','1','cash',0,'0'),(3,5,'15:24:05','2021-09-13','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(2,6,'00:11:48','2021-09-20','cash',5000,1000,'UNIFORM','1','cash',0,'0'),(1,7,'14:09:55','2021-09-21','Fhkjgdc',20000,-12000,'Tuition1','1','mpesa',0,'0'),(4,8,'05:24:10','2021-09-22','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(4,9,'06:24:44','2021-09-22','FGDFGDF',20000,6000,'Tuition1','1','mpesa',0,'0'),(3,10,'06:25:34','2021-09-22','cash',11000,20000,'Tuition1','1','cash',0,'0'),(5,11,'08:16:43','2021-09-22','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(5,12,'18:10:15','2021-09-22','Dfggdvfrd',10000,16000,'UNIFORM','1','bank',0,'0'),(5,13,'10:46:36','2021-09-24','cash',5000,11000,'UNIFORM','1','cash',0,'0'),(6,14,'09:24:33','2021-09-26','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(6,16,'11:23:50','2021-09-26','cash',10000,3000,'Tuition1','1','cash',0,'0'),(6,18,'10:14:17','2021-10-02','dfgthyhgv',2000,1000,'UNIFORM','1','mpesa',0,'0'),(7,19,'09:53:37','2021-10-02','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(7,20,'10:58:32','2021-10-02','cash',100,19100,'Lunch','1','cash',0,'0'),(5,21,'19:09:17','2021-10-03','cash',9000,200,'Tuition1','1','cash',0,'0'),(7,23,'11:37:32','2021-10-04','cash',19000,100,'Tuition1','1','cash',0,'0'),(8,24,'10:47:53','2021-10-04','KJHKJKJK',1000,0,'admission fees','HILARY NGIGE ADALA','mpesa',0,'0'),(8,25,'11:51:24','2021-10-04','HGJHGJKN99',1000,7200,'Tuition1','1','bank',0,'0'),(9,26,'11:34:20','2021-10-04','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(10,27,'11:36:04','2021-10-04','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(11,28,'11:37:23','2021-10-04','453534ghj',1000,0,'admission fees','HILARY NGIGE ADALA','mpesa',0,'0'),(12,29,'11:38:49','2021-10-04','898798JHK8',1000,0,'admission fees','HILARY NGIGE ADALA','bank',0,'0'),(13,30,'11:41:15','2021-10-04','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(14,31,'11:46:43','2021-10-04','HJHNBJ89978',1000,0,'admission fees','HILARY NGIGE ADALA','mpesa',0,'0'),(15,32,'11:51:38','2021-10-04','PKSJGJ28',1000,0,'admission fees','HILARY NGIGE ADALA','bank',0,'0'),(16,33,'12:21:45','2021-10-04','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(17,34,'12:35:16','2021-10-04','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(18,35,'12:38:30','2021-10-04','JKHKU879',1000,0,'admission fees','HILARY NGIGE ADALA','bank',0,'0'),(17,36,'17:28:18','2021-10-04','cash',12000,8200,'BOARDING1','1','cash',0,'0'),(19,37,'16:45:59','2021-10-05','JHKJH9088',1000,0,'admission fees','HILARY NGIGE ADALA','mpesa',0,'0'),(20,38,'16:47:25','2021-10-05','DSGFDSG32',1000,0,'admission fees','HILARY NGIGE ADALA','bank',0,'0'),(21,39,'16:48:48','2021-10-05','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(22,40,'16:50:40','2021-10-05','cash',500,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(23,41,'16:53:56','2021-10-05','KJHKJHKJ',1000,0,'admission fees','HILARY NGIGE ADALA','bank',0,'0'),(24,42,'16:55:29','2021-10-05','jgfvhg878y',1000,0,'admission fees','HILARY NGIGE ADALA','bank',0,'0'),(25,43,'17:04:56','2021-10-05','FGDFGD43F',1000,0,'admission fees','HILARY NGIGE ADALA','mpesa',0,'0'),(26,44,'17:06:27','2021-10-05','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(27,45,'17:09:28','2021-10-05','HJGJHGJ',1000,0,'admission fees','HILARY NGIGE ADALA','bank',0,'0'),(28,46,'17:12:46','2021-10-05','kjhkjh878jh',1000,0,'admission fees','HILARY NGIGE ADALA','bank',0,'0'),(30,47,'17:18:33','2021-10-05','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(31,48,'17:20:54','2021-10-05','XVCBVC',1000,0,'admission fees','HILARY NGIGE ADALA','mpesa',0,'0'),(32,49,'17:23:24','2021-10-05','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(12,50,'18:23:46','2021-10-09','cash',15000,5200,'Tuition1','1','cash',0,'0'),(33,51,'18:27:02','2021-10-09','cash',1000,0,'admission fees','HILARY NGIGE ADALA','cash',0,'0'),(12,53,'03:32:43','2021-10-12','cash',1000,4200,'UNIFORM','1','cash',0,'0'),(10,76,'16:49:52','2021-10-12','cash',1000,20200,'BOARDING1','1','cash',0,'0'),(12,77,'16:53:00','2021-10-12','cash',1000,3200,'UNIFORM','1','cash',0,'0'),(12,78,'18:02:10','2021-10-12','cash',1000,3200,'UNIFORM','1','cash',0,'0'),(1,83,'19:43:02','2021-12-15','cash',1000,9000,'Lunch','1','cash',0,'0');
/*!40000 ALTER TABLE `finance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_time` varchar(10) NOT NULL,
  `active_time` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,'12:26:47','23:10:19','2021-09-13',1),(2,'10:58:43','20:12:14','2021-09-14',1),(3,'09:12:39','19:21:09','2021-09-15',1),(4,'12:13:00','23:59:59','2021-09-16',1),(5,'00:00:01','00:17:23','2021-09-17',1),(6,'11:19:35','18:58:03','2021-09-18',1),(7,'22:03:44','23:59:59','2021-09-19',1),(8,'00:00:01','21:31:55','2021-09-20',1),(9,'10:29:44','23:59:32','2021-09-21',1),(10,'13:41:28','17:02:29','2021-09-21',9),(11,'00:00:32','23:59:09','2021-09-22',1),(12,'09:04:32','09:11:32','2021-09-22',15),(13,'09:08:43','09:12:22','2021-09-22',12),(14,'09:13:09','09:13:23','2021-09-22',17),(15,'09:13:58','09:57:02','2021-09-22',11),(16,'15:37:58','15:39:12','2021-09-22',16),(17,'15:41:33','15:47:05','2021-09-22',18),(18,'16:04:49','16:05:20','2021-09-22',8),(19,'00:00:09','20:46:05','2021-09-23',1),(20,'09:45:55','17:57:36','2021-09-24',1),(21,'11:07:49','11:09:37','2021-09-25',1),(22,'10:17:15','21:04:12','2021-09-26',1),(23,'11:33:39','11:44:11','2021-09-26',29),(24,'11:38:35','11:51:32','2021-09-26',19),(25,'19:44:34','23:59:03','2021-09-27',1),(26,'19:55:17','20:24:39','2021-09-27',7),(27,'00:00:03','18:12:15','2021-09-28',1),(28,'10:35:20','18:53:00','2021-09-29',1),(29,'02:06:11','22:44:22','2021-09-30',1),(30,'15:14:57','15:17:26','2021-09-30',7),(31,'15:17:46','15:20:52','2021-09-30',17),(32,'10:19:24','19:12:08','2021-10-01',1),(33,'19:12:30','23:45:53','2021-10-01',9),(34,'10:02:47','22:41:57','2021-10-02',1),(35,'10:19:11','19:26:43','2021-10-03',1),(36,'11:04:40','11:07:14','2021-10-03',2),(37,'11:01:27','23:59:31','2021-10-04',1),(38,'00:00:31','22:15:45','2021-10-05',1),(39,'16:34:21','16:46:00','2021-10-05',15),(40,'09:21:07','10:11:46','2021-10-06',1),(41,'09:43:51','10:06:34','2021-10-06',2),(42,'22:12:56','23:59:59','2021-10-06',7),(43,'00:00:01','00:29:47','2021-10-07',7),(44,'15:00:20','18:45:58','2021-10-07',1),(45,'11:17:23','23:07:15','2021-10-09',1),(46,'12:50:14','17:28:13','2021-10-10',1),(47,'03:26:59','23:59:59','2021-10-12',1),(48,'00:00:01','23:59:56','2021-10-13',1),(49,'00:00:56','23:59:52','2021-10-14',1),(50,'00:00:52','01:56:52','2021-10-15',1),(51,'11:22:47','21:59:27','2021-10-16',1),(52,'14:18:28','21:15:14','2021-10-16',2),(53,'16:00:51','16:01:53','2021-10-18',1),(54,'12:27:02','23:59:58','2021-10-20',1),(55,'00:00:00','03:01:57','2021-10-21',1),(56,'22:16:19','23:59:54','2021-10-22',1),(57,'00:00:54','23:59:27','2021-10-23',1),(58,'00:00:27','05:46:27','2021-10-24',1),(59,'12:37:44','23:46:50','2021-10-25',1),(60,'00:41:50','23:59:29','2021-10-26',1),(61,'00:00:29','23:59:20','2021-10-27',1),(62,'00:00:20','23:59:56','2021-10-28',1),(63,'00:00:56','12:52:24','2021-10-29',1),(64,'14:12:27','22:42:04','2021-10-30',1),(65,'12:06:13','23:59:59','2021-10-31',1),(66,'00:00:01','13:06:23','2021-11-01',1),(67,'10:40:59','15:59:36','2021-11-02',1),(68,'14:42:22','14:46:12','2021-11-02',18),(69,'11:39:29','22:34:28','2021-11-23',1),(70,'08:45:28','13:37:24','2021-11-24',1),(71,'15:33:52','17:10:48','2021-11-26',1),(72,'10:31:50','13:46:18','2021-11-27',1),(73,'18:20:52','19:47:17','2021-11-28',1),(74,'14:04:42','22:28:27','2021-11-29',1),(75,'10:19:46','22:02:11','2021-11-30',1),(76,'19:37:12','19:41:22','2021-11-30',2),(77,'15:00:31','15:46:47','2021-12-01',7),(78,'17:30:06','23:59:59','2021-12-01',1),(79,'00:00:59','01:02:39','2021-12-02',1),(80,'15:42:36','16:29:47','2021-12-06',1),(81,'10:02:37','12:29:48','2021-12-07',1),(82,'18:49:06','23:59:50','2021-12-08',1),(83,'00:00:50','12:48:15','2021-12-09',1),(84,'00:58:17','00:58:28','2021-12-10',1),(85,'13:47:45','17:46:29','2021-12-14',1),(86,'13:36:32','19:48:01','2021-12-15',1),(87,'15:33:35','16:35:14','2021-12-16',1),(88,'18:08:03','23:37:37','2021-12-17',1),(89,'09:42:21','09:51:44','2021-12-22',1),(90,'15:57:18','16:20:06','2021-12-26',1),(91,'08:25:45','08:29:13','2021-12-31',1),(92,'09:54:26','12:00:14','2022-01-05',1),(93,'09:44:20','09:51:22','2022-01-07',1),(94,'16:08:43','16:13:20','2022-01-08',1),(95,'16:23:50','23:55:35','2022-01-13',7),(96,'15:48:57','15:49:50','2022-01-15',1),(97,'12:37:46','18:45:44','2022-01-17',1),(98,'00:26:38','01:32:46','2022-01-18',1),(99,'12:31:03','12:41:59','2022-01-19',1),(100,'12:54:25','13:05:22','2022-02-08',1),(101,'14:52:21','15:21:14','2022-02-10',1);
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_information`
--

DROP TABLE IF EXISTS `payroll_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_information` (
  `staff_id` int(20) DEFAULT NULL,
  `payroll_id` int(20) NOT NULL AUTO_INCREMENT,
  `current_balance` int(20) DEFAULT NULL,
  `current_balance_monNyear` varchar(300) DEFAULT NULL,
  `salary_amount` varchar(100) DEFAULT NULL,
  `effect_month` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`payroll_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_information`
--

LOCK TABLES `payroll_information` WRITE;
/*!40000 ALTER TABLE `payroll_information` DISABLE KEYS */;
INSERT INTO `payroll_information` VALUES (2,15,5200,'Apr:2021','25000','Jan:2021'),(5,16,30900,'Aug:2021','45000','Apr:2021'),(29,17,5000,'Jun:2021','15000','Feb:2021'),(3,18,6000,'Sep:2021','10000','May:2021');
/*!40000 ALTER TABLE `payroll_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary_payment`
--

DROP TABLE IF EXISTS `salary_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_payment` (
  `pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_paid` int(50) DEFAULT NULL,
  `amount_paid` int(50) DEFAULT NULL,
  `mode_of_payment` varchar(50) DEFAULT NULL,
  `payment_code` varchar(100) DEFAULT NULL,
  `date_paid` varchar(20) DEFAULT NULL,
  `time_paid` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_payment`
--

LOCK TABLES `salary_payment` WRITE;
/*!40000 ALTER TABLE `salary_payment` DISABLE KEYS */;
INSERT INTO `salary_payment` VALUES (24,2,70000,'bank','FGDFD','2021-09-21','13:15:16'),(25,2,1000,'cash','cash','2021-09-21','13:25:15'),(26,5,100,'bank','kjlkjlk','2021-09-21','13:32:33'),(28,5,100000,'cash','cash','2021-09-21','16:42:37'),(29,29,30000,'cash','cash','2021-09-26','11:48:36'),(30,5,50000,'m-pesa','KJHKJHU99J','2021-10-10','13:33:53'),(31,3,5000,'bank','JKHKJH','2021-10-12','12:09:27'),(32,3,10000,'bank','HGJHGJHJ','2021-10-16','11:53:35'),(33,29,30000,'cash','cash','2021-10-16','14:07:10'),(34,3,20000,'bank','gjhgjyghg','2021-11-30','19:44:21');
/*!40000 ALTER TABLE `salary_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `sett` varchar(200) NOT NULL,
  `valued` mediumtext NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('admissionessentials','BREAD,Golf bat',1),('class','1,2,3,4,5,6,7,8',2),('lastadmgen','34',5);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_api`
--

DROP TABLE IF EXISTS `sms_api`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_api` (
  `sms_api_key` varchar(2000) NOT NULL,
  `patner_id` varchar(2000) NOT NULL,
  `short_code` varchar(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_api`
--

LOCK TABLES `sms_api` WRITE;
/*!40000 ALTER TABLE `sms_api` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_api` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_table`
--

DROP TABLE IF EXISTS `sms_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_table` (
  `send_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_count` int(11) NOT NULL,
  `message_sent_succesfully` int(10) NOT NULL,
  `message_undelivered` int(10) NOT NULL,
  `message_type` varchar(100) NOT NULL,
  `sender_no` int(11) NOT NULL,
  `message_description` varchar(100) NOT NULL,
  `message` varchar(100) NOT NULL,
  `charged` int(11) NOT NULL DEFAULT '0',
  `date_sent` date DEFAULT NULL,
  PRIMARY KEY (`send_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_table`
--

LOCK TABLES `sms_table` WRITE;
/*!40000 ALTER TABLE `sms_table` DISABLE KEYS */;
INSERT INTO `sms_table` VALUES (15,1,1,1,'Multicast',713620727,'Test message 23...','Test message 23',0,'2021-12-16'),(16,1,1,1,'Multicast',704241905,'Hello Hillary see me at my office at 5 after ...','Hello Hillary see me at my office at 5 after classes',0,'2022-01-17');
/*!40000 ALTER TABLE `sms_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_data`
--

DROP TABLE IF EXISTS `student_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_data` (
  `surname` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `second_name` varchar(100) DEFAULT NULL,
  `index_no` int(30) DEFAULT '0',
  `D_O_B` date DEFAULT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `stud_class` varchar(30) DEFAULT NULL,
  `adm_no` int(30) NOT NULL,
  `D_O_A` date DEFAULT NULL,
  `parentName` varchar(100) DEFAULT NULL,
  `parentContacts` varchar(100) DEFAULT NULL,
  `parent_relation` varchar(30) DEFAULT NULL,
  `parent_email` varchar(100) DEFAULT NULL,
  `parent_name2` varchar(100) DEFAULT NULL,
  `parent_contact2` varchar(200) DEFAULT NULL,
  `parent_relation2` varchar(200) DEFAULT NULL,
  `parent_email2` varchar(200) DEFAULT NULL,
  `address` varchar(100) DEFAULT 'N/A',
  `BCNo` varchar(30) DEFAULT '0',
  `student_upi` varchar(30) DEFAULT NULL,
  `admissionessentials` varchar(100) DEFAULT NULL,
  `dormitory` varchar(100) DEFAULT 'none',
  `boarding` varchar(10) DEFAULT 'none',
  `examInterview` varchar(10) DEFAULT 'NO',
  `disabled` varchar(5) DEFAULT 'No',
  `disable_describe` mediumtext,
  `deleted` int(1) DEFAULT '0',
  `activated` int(1) DEFAULT '1',
  `ids` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ids`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_data`
--

LOCK TABLES `student_data` WRITE;
/*!40000 ALTER TABLE `student_data` DISABLE KEYS */;
INSERT INTO `student_data` VALUES ('ADALA','HILARY','NGIGE',0,'2019-09-13','Male','8',1,'2021-09-13','Kevin Masilwa','0720002156','Father','hilaryme45@gmail.com','',NULL,NULL,'','Thika','2147483647','234221','','none','none','NO','No','none',0,1,1),('EGHAN','THOMAS','PATRICK',0,'2012-01-31','Male','8',2,'2021-09-13','Karanja Wanati','0704152145','Mother','hilaryme45@gmail.com','','','','','Thika','659809','234221','BREAD','2','enrolled','NO','No','none',0,1,2),('OYUGI','ELIZABETH','ATIENO',0,'2013-01-29','Female','7',3,'2021-09-13','Justus Murume','0714512145','Father','james@gmail.com','',NULL,NULL,'','Kilifi, Ke','73989783','54578787','BREAD','1','enrolled','NO','No','',0,1,3),('BUKEKO','CHARLES','KHAMALA',0,'2019-06-04','Male','7',4,'2021-09-22','JAMES BUKEKO','0714215455','Father','hilaryme45@gmail.com','','','','','Thika, KIambu','JKH9878','878978','BREAD,Golf bat','none','enroll','NO','No','none',0,1,4),('ACHIENG','SHARON','MANDAU',0,'2017-01-31','Male','8',5,'2021-09-22','THIKA MAIN','0702023350','Father','esmond@gmail.com','',NULL,NULL,'','Toll, Thika road','0','','BREAD,Golf bat','2','enrolled','NO','No','none',0,1,5),('ONYANGO','LAURENCE','OPIYO',0,'2012-01-31','Male','6',6,'2021-09-26','BREMI OPIYO','0705211212','Father','gg@gmail.com','',NULL,NULL,'','KAWANGWARE','0','78687687','BREAD,Golf bat','none','enroll','NO','No','',0,1,6),('ONYANGO','LAURENCE','OTIENO',0,'2019-01-01','Female','8',7,'2021-10-02','terence kinyua','0714124154','Father','james@gmail.com','','','','','Industrial Area','98798','87687','BREAD,Golf bat','none','none','NO','No','none',0,1,7),('NJUGUNA','TIMOTHY','KAMAU',0,'2016-01-04','Female','5',8,'2021-10-04','Jackline mwende','0704241515','Mother','jackie@gmail.com','','','','','Kiambu, Ke','3243223','323234','BREAD,Golf bat','none','none','NO','No','none',0,1,8),('MBAPPE','KILIAN','CHRISTIAN',0,'2019-10-04','Male','5',9,'2021-10-04','JAMES MUGO','0714541415','Father','christian@gmail.com','','','','','Kitale, INC','','353','BREAD,Golf bat','none','none','NO','No','none',0,1,9),('AKOTH','TRACY','OUNDO',0,'2019-10-01','Female','5',10,'2021-10-04','James Omondi','0745414144','Father','esmond@gmail.com','','','','','Busia Ke','423423','24324','BREAD,Golf bat','1','enrolled','NO','No','none',0,1,10),('TUMAINI','SHARON','ATIENO',0,'2018-11-22','Female','5',11,'2021-10-04','0704245167','0721112145','Father','jamesomondi@gmail.com','','','','','NAIROBI, KE','90809809','09809809','BREAD,Golf bat','1','enrolled','NO','No','none',0,1,11),('KHAMALA','CHRISPINUS','ODHIAMBO',0,'2013-01-29','Male','5',12,'2021-10-04','TRACY PANDE','0799741017','Mother','james@gmail.com','','','','','BUSIA KE','232DFDSF3','21321321','BREAD,Golf bat','1','enrolled','NO','No','none',0,1,12),('TSUMA','CEDRIQUE','MUSUNGU',0,'2011-02-08','Male','5',13,'2021-10-04','JAMES OMONDI','0754341451','Father','kamilia@gmail.com','','','','','BUSIA KE','KJHKJNMN','78867687','BREAD,Golf bat','2','enrolled','NO','No','none',0,1,13),('OMONDI','TIMON','KHALWALE',0,'2013-01-29','Male','5',14,'2021-10-04','SAMUEL ETOO','0714541214','Father','omonditimon@gmail.com','','','','','JUJA, KIAMBU','9898798','98978','BREAD,Golf bat','1','enrolled','NO','No','none',0,1,14),('KAMAU','DENZEL','PAULINE',0,'2011-02-01','Male','5',15,'2021-10-04','JANICE OPIYO','0714155454','Father','jamesoundo@gmail.com','','','','','KILIFI, MOMBASA','KJHKJH990','8909809','BREAD,Golf bat','none','none','NO','No','none',0,1,15),('JUMA','SHARON','OMONDI',0,'2010-02-02','Female','4',16,'2021-10-04','KEVIN JUMA','0701141545','Father','kevin@gmail.com','','','','','Busia, Ke','5413213','51231213','BREAD,Golf bat','2','enrolled','NO','No','none',0,1,16),('OMONDI','SARAH','KAPULE',0,'2016-02-09','Female','4',17,'2021-10-04','Janice Modeo','0704241905','Mother','janice@gmail.com','','','','','Limuru, Ke','2423432','2321423','BREAD,Golf bat','1','enrolled','NO','No','none',0,1,17),('AMANDA','JULIUS','MUGANDA',0,'2010-02-02','Male','4',18,'2021-10-04','THOMAS PATRICK','0714512114','Father','','','','','','Busia, Ke','879879','9879879','BREAD,Golf bat','1','enrolled','NO','No','none',0,1,18),('WAKIO','MARIA','NGIGE',0,'2014-01-28','Female','8',19,'2021-10-05','JAMES OUNDO','0704241305','Father','james@gmail.com','','','','','Thika kiambu','','','BREAD,Golf bat','none','none','NO','No','none',0,1,19),('PATRICK','THOMAS','EGHAN',0,'2011-02-01','Female','8',20,'2021-10-05','JOEL ORONDA','0714245141','Father','opuko@thaddeus.com','','','','','KENYATTA UNIVERSITY','','JHKJH98JHK','BREAD,Golf bat','none','enroll','NO','No','none',0,1,20),('OTET','DESMOND','TUTU',0,'2011-02-01','Male','8',21,'2021-10-05','THOMAS ODEDE','0715214145','Father','odude@gmail.com','','','','','KIGANJO THIKA','FDGDF4343','DFGDFGDF','BREAD,Golf bat','none','enroll','NO','No','none',0,1,21),('KIMANI','JOEL','KAMAU',0,'2011-02-01','Male','8',22,'2021-10-05','JOSEPH OJIL','0704124154','Father','jose@gmail.com','','','','','KIMANI WA MATANGI','3FDSFSDF33','GFFGHGF32','BREAD,Golf bat','none','enroll','NO','No','none',0,1,22),('KALE','MADUA','KHALE',0,'2011-03-02','Female','8',23,'2021-10-05','JOPHIL AKEYO','0714542145','Father','kale@gmail.com','','','','','KITALE KE','546546521','SDFGDSGDS3','BREAD,Golf bat','none','enroll','NO','No','none',0,1,23),('OPUKO','THADDEUS','JUDE',0,'2010-02-02','Male','8',24,'2021-10-05','JULIUS MUGANDA','0704512451','Father','julius@gmail.com','','','','','KIAMBU','0','8798798B','BREAD,Golf bat','none','enroll','NO','No','none',0,1,24),('TSINALE','HARRIET','OUKO',0,'2014-01-28','Female','7',25,'2021-10-05','JAMAL KALIWA','0704124514','Father','khalid@gmail.com','','','','','KITUI','98798798','KLJLKJ909','BREAD,Golf bat','none','enroll','NO','No','none',0,1,25),('AMANDA','JULIUS','KHALWALE',0,'2009-02-10','Male','7',26,'2021-10-05','KEVIN MASILWA','0714215411','Father','jiji@gmail.com','','','','','JEVANJEE GARDENS','89798NKN','8787687KJ','BREAD,Golf bat','none','enroll','NO','No','none',0,1,26),('MARLEY','DAMIAN','KIPCHUMBA',0,'2006-03-01','Male','7',27,'2021-10-05','JULIUS YEGO','0714541454','Father','peter@gmail.com','','','','','','','8798IOUKJ8','BREAD,Golf bat','none','enroll','NO','No','none',0,1,27),('BARASA','PETER','CLEVAS',0,'2003-01-28','Male','7',28,'2021-10-05','JAMES MUGO','0714144145','Father','james@gmail.com','','','','','','','54224','BREAD,Golf bat','2','enrolled','NO','No','none',0,1,28),('BALE','GARETH','HUMAN',0,'2006-01-31','Female','7',29,'2021-10-05','JUMANJI','0714541514','Father','','','','','','','','',NULL,'none','none','NO','No',NULL,0,1,29),('OMONDI','LEONARD','MASILWA',0,'2000-06-06','Female','7',30,'2021-10-05','JULIS DEZMORE','0741214144','Father','','','','','','','','','BREAD,Golf bat','none','enroll','NO','No','none',0,1,30),('HANNAH','PAULINE','PETERSON',0,'2010-02-02','Female','7',31,'2021-10-05','PETER KARANJA','0714124544','Father','','','','','','','','','BREAD,Golf bat','none','','NO','No','none',0,1,31),('AKOTH','TRACY','MANDIWA',0,'2001-02-06','Female','7',32,'2021-10-05','HILLARY NGIGE','0715414147','Father','','','','','','','','','BREAD,Golf bat','none','enroll','NO','No','none',0,1,32),('Omondi','Caren','Odhiambo',0,'2000-10-10','Female','5',33,'2021-10-09','Tobias Oketch','0704241905','Father','tobby@gmail.com','','','','','Hurlingham','','4677436','BREAD,Golf bat','none','enroll','NO','No','none',0,1,33),('Opuko','Thaddeus','Jude',0,'2008-10-01','Female','6',34,'2021-10-09','Hillary Ngige','0743551250','Father','hilaryme45@gmail.com','','','','','Kiambu','GGFJGJDJH','57857886',NULL,'none','none','NO','No','',0,1,34),('KHAMALA','JAMES','INDIANA',0,'2019-10-30','Male','8',35,'2021-11-23','JOSEPH PIN','0741414154','Mother','mariawakio@gmail.com','','','','','','GFNFG','FDNGFFG','BREAD,Golf bat','none','none','NO','No','none',0,1,35),('PANDE','TRACY ','OKOTH',0,'2019-11-05','Male','7',36,'2021-11-23','LEONARD DAVINCI','0745457475','Mother','','',NULL,NULL,'','','0','','BREAD,Golf bat','none','none','NO','No','',0,1,36),('ADALA','ESMOND','BWIRE',0,'2019-10-30','Male','7',37,'2021-11-23','KEVIN SANDE','0714547478','Father','sande@gmail.com','',NULL,NULL,'','','KUJHKJH','UJHKJ',NULL,'none','none','NO','No','PAUL OTUOMA',0,1,37),('JUMA','KEVIN','ASIPO',0,'2019-11-24','Male','8',38,'2021-11-24','JUMA JUX','0704241871','Mother','lilian@gmail.com','',NULL,NULL,'','','0','KILN','BREAD,Golf bat','none','none','NO','No','SAMSON OSUMBA',0,1,38),('OPONDO','KIMATHI','TSUMA',0,'2019-10-31','Female','8',39,'2021-11-24','DICKSON KAMAU','0715414254','Father','lilian@gmail.com','KEVIN MUSUNGU','0714541412','Mother','none','KITISURU','412251','4124125','BREAD,Golf bat','none','none','NO','No','none',0,1,39);
/*!40000 ALTER TABLE `student_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table_subject`
--

DROP TABLE IF EXISTS `table_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_subject` (
  `subject_id` int(10) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(100) DEFAULT NULL,
  `timetable_id` varchar(10) DEFAULT NULL,
  `max_marks` int(4) DEFAULT NULL,
  `classes_taught` varchar(100) DEFAULT NULL,
  `teachers_id` varchar(100) DEFAULT NULL,
  `sub_activated` int(1) DEFAULT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table_subject`
--

LOCK TABLES `table_subject` WRITE;
/*!40000 ALTER TABLE `table_subject` DISABLE KEYS */;
INSERT INTO `table_subject` VALUES (1,'ENGLISH','ENG',50,'5,6,7,8','(2:6)|(3:7)|(2:8)|(3:5)',1),(2,'MATHEMATICS','MAT',100,'1,2,3,4,5,6,7,8','(1:6)|(1:8)|(29:1)|(29:2)|(29:3)|(29:4)|(5:5)|(5:7)',1),(3,'KISWAHILI','KIS',100,'1,2,3,4,5,6,7,8','(6:6)|(6:8)|(7:5)|(7:7)',1),(4,'SCIENCE','SCI',100,'5,6,7,8','(1:5)|(1:7)|(5:6)|(5:8)',1),(5,'CRE','CRE',30,'5,6,7,8','(6:5)|(6:7)|(7:6)|(7:8)',1),(6,'SOCIAL STUDIES','SST',70,'5,6,7,8','(2:5)|(3:6)|(2:7)|(3:8)',1);
/*!40000 ALTER TABLE `table_subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblnotification`
--

DROP TABLE IF EXISTS `tblnotification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnotification` (
  `notification_id` int(30) NOT NULL AUTO_INCREMENT,
  `notification_name` varchar(1000) DEFAULT NULL,
  `Notification_content` mediumtext,
  `sender_id` varchar(30) DEFAULT NULL,
  `notification_status` varchar(2) DEFAULT NULL,
  `notification_reciever_id` int(10) DEFAULT NULL,
  `notification_reciever_auth` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblnotification`
--

LOCK TABLES `tblnotification` WRITE;
/*!40000 ALTER TABLE `tblnotification` DISABLE KEYS */;
INSERT INTO `tblnotification` VALUES (1,'Admission of <b>THOMAS PATRICK</b> in your class was successfull','<b>THOMAS PATRICK</b> has been successfully admitted to class: <b>8</b>','Administration System','1',11,'5'),(2,'Admission of <b>ELIZABETH ATIENO</b> in your class was successfull','<b>ELIZABETH ATIENO</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(3,'Thanks for the feedback!','We really value your feedback, we`ll review it and use it to make your experience better as we go.<br><b>Thank you!</b>','Ladybird SMIS','1',1,'1'),(6,'New Message','Thayo man','1','1',9,'all'),(7,'Admission of <b>CHARLES KHAMALA</b> in your class was successfull','<b>CHARLES KHAMALA</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(8,'Admission of <b>SHARON MANDAU</b> in your class was successfull','<b>SHARON MANDAU</b> has been successfully admitted to class: <b>8</b>','Administration System','1',11,'5'),(9,'Thanks for the feedback!','We really value your feedback, we`ll review it and use it to make your experience better as we go.<br><b>Thank you!</b>','Ladybird SMIS','1',18,'2'),(10,'Hello <b>David bremy</b>. Welcome!','Hello <b>David bremy</b>, Welcome to <b>             TESTIMONY GRAMMAR SCHOOL SMIS</b>. <br>You are assigned <b>Class teacher</b> by your administrator.<br>Use the menu on your left to navigate the system and the home button on the top to view your dashboard.','Administration system','1',29,'5'),(11,'Thanks for the feedback!','We really value your feedback, we`ll review it and use it to make your experience better as we go.<br><b>Thank you!</b>','Ladybird SMIS','1',17,'5'),(12,'Admission of <b>LAURENCE OTIENO</b> in your class was successfull','<b>LAURENCE OTIENO</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5'),(13,'Admission of <b>TIMOTHY KAMAU</b> in your class was successfull','<b>TIMOTHY KAMAU</b> has been successfully admitted to class: <b>5</b>','Administration System','0',6,'5'),(14,'Admission of <b>KILIAN CHRISTIAN</b> in your class was successfull','<b>KILIAN CHRISTIAN</b> has been successfully admitted to class: <b>5</b>','Administration System','0',6,'5'),(15,'Admission of <b>TRACY OUNDO</b> in your class was successfull','<b>TRACY OUNDO</b> has been successfully admitted to class: <b>5</b>','Administration System','0',6,'5'),(16,'Admission of <b>SHARON ATIENO</b> in your class was successfull','<b>SHARON ATIENO</b> has been successfully admitted to class: <b>5</b>','Administration System','0',6,'5'),(17,'Admission of <b>CHRISPINUS ODHIAMBO</b> in your class was successfull','<b>CHRISPINUS ODHIAMBO</b> has been successfully admitted to class: <b>5</b>','Administration System','0',6,'5'),(18,'Admission of <b>CEDRIQUE MUSUNGU</b> in your class was successfull','<b>CEDRIQUE MUSUNGU</b> has been successfully admitted to class: <b>5</b>','Administration System','0',6,'5'),(19,'Admission of <b>TIMON KHALWALE</b> in your class was successfull','<b>TIMON KHALWALE</b> has been successfully admitted to class: <b>5</b>','Administration System','0',6,'5'),(20,'Admission of <b>DENZEL PAULINE</b> in your class was successfull','<b>DENZEL PAULINE</b> has been successfully admitted to class: <b>5</b>','Administration System','0',6,'5'),(21,'Admission of <b>SHARON OMONDI</b> in your class was successfull','<b>SHARON OMONDI</b> has been successfully admitted to class: <b>4</b>','Administration System','0',26,'5'),(22,'Admission of <b>SARAH KAPULE</b> in your class was successfull','<b>SARAH KAPULE</b> has been successfully admitted to class: <b>4</b>','Administration System','0',26,'5'),(23,'Admission of <b>JULIUS MUGANDA</b> in your class was successfull','<b>JULIUS MUGANDA</b> has been successfully admitted to class: <b>4</b>','Administration System','0',26,'5'),(24,'Thanks for the feedback!','We really value your feedback, we`ll review it and use it to make your experience better as we go.<br><b>Thank you!</b>','Ladybird SMIS','1',1,'1'),(25,'Thanks for the feedback!','We really value your feedback, we`ll review it and use it to make your experience better as we go.<br><b>Thank you!</b>','Ladybird SMIS','1',15,'5'),(26,'Admission of <b>MARIA NGIGE</b> in your class was successfull','<b>MARIA NGIGE</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5'),(27,'Admission of <b>THOMAS EGHAN</b> in your class was successfull','<b>THOMAS EGHAN</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5'),(28,'Admission of <b>DESMOND TUTU</b> in your class was successfull','<b>DESMOND TUTU</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5'),(29,'Admission of <b>JOEL KAMAU</b> in your class was successfull','<b>JOEL KAMAU</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5'),(30,'Admission of <b>MADUA KHALE</b> in your class was successfull','<b>MADUA KHALE</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5'),(31,'Admission of <b>THADDEUS JUDE</b> in your class was successfull','<b>THADDEUS JUDE</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5'),(32,'Admission of <b>HARRIET OUKO</b> in your class was successfull','<b>HARRIET OUKO</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(33,'Admission of <b>JULIUS KHALWALE</b> in your class was successfull','<b>JULIUS KHALWALE</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(34,'Admission of <b>DAMIAN KIPCHUMBA</b> in your class was successfull','<b>DAMIAN KIPCHUMBA</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(35,'Admission of <b>PETER CLEVAS</b> in your class was successfull','<b>PETER CLEVAS</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(36,'Admission of <b>GARETH HUMAN</b> in your class was successfull','<b>GARETH HUMAN</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(37,'Admission of <b>LEONARD MASILWA</b> in your class was successfull','<b>LEONARD MASILWA</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(38,'Admission of <b>PAULINE PETERSON</b> in your class was successfull','<b>PAULINE PETERSON</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(39,'Admission of <b>TRACY MANDIWA</b> in your class was successfull','<b>TRACY MANDIWA</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(40,'Admission of <b>Caren Odhiambo</b> in your class was successfull','<b>Caren Odhiambo</b> has been successfully admitted to class: <b>5</b>','Administration System','0',6,'5'),(41,'Admission of <b>Thaddeus Jude</b> in your class was successfull','<b>Thaddeus Jude</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5'),(42,'New Message','MBUDAH FORM','1','0',3,'all'),(43,'Admission of <b>JAMES INDIANA</b> in your class was successfull','<b>JAMES INDIANA</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5'),(44,'Admission of <b>TRACY  OKOTH</b> in your class was successfull','<b>TRACY  OKOTH</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(45,'Admission of <b>ESMOND BWIRE</b> in your class was successfull','<b>ESMOND BWIRE</b> has been successfully admitted to class: <b>7</b>','Administration System','0',10,'5'),(46,'Admission of <b>KEVIN ASIPO</b> in your class was successfull','<b>KEVIN ASIPO</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5'),(47,'Admission of <b>KIMATHI TSUMA</b> in your class was successfull','<b>KIMATHI TSUMA</b> has been successfully admitted to class: <b>8</b>','Administration System','0',12,'5');
/*!40000 ALTER TABLE `tblnotification` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-10 15:21:16
