-- MySQL dump 10.13  Distrib 5.7.36, for Linux (x86_64)
--
-- Host: localhost    Database: ladybird_smis
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
-- Table structure for table `developers`
--

DROP TABLE IF EXISTS `developers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `developers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `developer_name` varchar(200) NOT NULL,
  `dev_username` varchar(100) NOT NULL,
  `dev_password` varchar(200) NOT NULL,
  `role` int(1) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `developers`
--

LOCK TABLES `developers` WRITE;
/*!40000 ALTER TABLE `developers` DISABLE KEYS */;
INSERT INTO `developers` VALUES (1,'HILARY NGIGE','hilla','@TsP$OrTpTs$pRoq',0,1),(2,'OWEN MALINGU','owen','$OrT@#EO@#EO@#EOrSviswaEBIiSVEooPsaRrTeG',0,1);
/*!40000 ALTER TABLE `developers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requested_user`
--

DROP TABLE IF EXISTS `requested_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requested_user` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(200) DEFAULT NULL,
  `l_name` varchar(200) DEFAULT NULL,
  `phone_no` varchar(100) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `sch_name` varchar(2000) NOT NULL,
  `sch_type` varchar(200) DEFAULT NULL,
  `time_rqst` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `date_rqst` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requested_user`
--

LOCK TABLES `requested_user` WRITE;
/*!40000 ALTER TABLE `requested_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `requested_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school_information`
--

DROP TABLE IF EXISTS `school_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school_information` (
  `school_code` int(15) DEFAULT NULL,
  `school_name` varchar(100) DEFAULT NULL,
  `sch_message_name` varchar(15) DEFAULT NULL,
  `school_motto` mediumtext,
  `school_admin_name` varchar(200) DEFAULT NULL,
  `school_contact` varchar(30) DEFAULT NULL,
  `school_mail` varchar(100) DEFAULT NULL,
  `school_location` varchar(100) DEFAULT NULL,
  `database_name` varchar(30) DEFAULT NULL,
  `activated` int(1) DEFAULT NULL,
  `sch_vision` mediumtext,
  `sch_mission` mediumtext,
  `sch_id` int(10) NOT NULL AUTO_INCREMENT,
  `from_time` varchar(30) DEFAULT NULL,
  `to_time` varchar(30) DEFAULT NULL,
  `po_box` varchar(30) DEFAULT NULL,
  `box_code` varchar(30) DEFAULT NULL,
  `school_profile_image` mediumtext,
  `county` varchar(300) DEFAULT NULL,
  `country` varchar(200) DEFAULT NULL,
  `ct_cg` varchar(5) NOT NULL DEFAULT 'No',
  PRIMARY KEY (`sch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school_information`
--

LOCK TABLES `school_information` WRITE;
/*!40000 ALTER TABLE `school_information` DISABLE KEYS */;
INSERT INTO `school_information` VALUES (35601110,'            TESTIMONY GRAMMAR SCHOOL','Testimony Acad','           EDUCATION IS A RESOURCE','Maria Wakio',' 254720002156','testimony@gmail.com','Busia, Ke','testimonytbl1',1,' STRIVE FOR EXCELLENCE','STRIVE FOR EXCELLENCE',1,'07:36','19:38','853','50400','images/sch_profiles/testimonytbl1/ladybird.png','Busia','Kenya','No'),(100,'  MAKENA SCHOOL','MAKENA_SCH',' OFFERING EDUCATION AT IT BEST','MADAM JUNE','0725093162','info@makenaschool.sc.ke','JUJA KE','makena_sch',1,'undefined ',NULL,2,NULL,NULL,'KALIMONI','330','images/sch_profiles/makena_sch/makenaschoollogo.jpg','Nairobi','Kenya','No');
/*!40000 ALTER TABLE `school_information` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_api`
--

LOCK TABLES `sms_api` WRITE;
/*!40000 ALTER TABLE `sms_api` DISABLE KEYS */;
INSERT INTO `sms_api` VALUES ('6beb219b597a7bbdfb4305f15e2edca0','3466','SchoolSMS','softtech',1);
/*!40000 ALTER TABLE `sms_api` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable_req`
--

DROP TABLE IF EXISTS `timetable_req`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable_req` (
  `tt_name` varchar(300) NOT NULL,
  `ids` int(11) NOT NULL AUTO_INCREMENT,
  `time_request` varchar(100) NOT NULL,
  `date_req` varchar(100) NOT NULL,
  `school_id` int(10) NOT NULL,
  `req_json` varchar(400) DEFAULT NULL,
  `return_json` varchar(400) DEFAULT NULL,
  `status` int(1) NOT NULL COMMENT '1 - attended\r\n0 - unattended',
  PRIMARY KEY (`ids`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable_req`
--

LOCK TABLES `timetable_req` WRITE;
/*!40000 ALTER TABLE `timetable_req` DISABLE KEYS */;
INSERT INTO `timetable_req` VALUES ('rrr',46,'21:07:22','2021-11-04',1,'/var/www/html/timetabled/testimonytbl1/requests/rrr/rrr.json','/var/www/html/timetable/testimonytbl1/rrr/rrr.json',1),('gilber',47,'15:08:39','2021-11-08',1,'/var/www/html/timetabled/testimonytbl1/requests/gilber/gilber.json','/var/www/html/timetable/testimonytbl1/gilber/gilber.json',1),('defaults',49,'13:00:58','2021-11-09',1,'/var/www/html/timetabled/testimonytbl1/requests/defaults/defaults.json','/var/www/html/timetable/testimonytbl1/defaults/defaults.json',1),('inmate',50,'13:16:09','2021-11-09',1,'/var/www/html/timetabled/testimonytbl1/requests/inmate/inmate.json','/var/www/html/timetable/testimonytbl1/inmate/inmate.json',1),('test',51,'12:29:19','2021-11-17',1,'/var/www/html/timetabled/testimonytbl1/requests/test/test.json','/var/www/html/timetable/testimonytbl1/test/test.json',1),('test2',52,'12:40:45','2021-11-17',1,'/var/www/html/timetabled/testimonytbl1/requests/test2/test2.json','/var/www/html/timetable/testimonytbl1/test2/test2.json',1),('test4',53,'12:46:07','2021-11-17',1,'/var/www/html/timetabled/testimonytbl1/requests/test4/test4.json','/var/www/html/timetable/testimonytbl1/test4/test4.json',1);
/*!40000 ALTER TABLE `timetable_req` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_feedback`
--

DROP TABLE IF EXISTS `user_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_feedback` (
  `feedback_id` int(10) NOT NULL AUTO_INCREMENT,
  `from_id` int(10) NOT NULL,
  `feedback` mediumtext NOT NULL,
  `deleted` int(2) NOT NULL,
  PRIMARY KEY (`feedback_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_feedback`
--

LOCK TABLES `user_feedback` WRITE;
/*!40000 ALTER TABLE `user_feedback` DISABLE KEYS */;
INSERT INTO `user_feedback` VALUES (1,1,'The system is a bit sluggish man.',0),(2,1,'Make the system more customizable as posible',0),(3,19,'Hii system ni ya umbwakni manze, Try using a nice system for data purposes',0),(4,19,'We need to record schemes of work in the system',0),(5,8,'Why do we teachers have few options to do in the system..You may add some functionalities like schemes of work or games',0),(6,1,'Weed out all the loosers',0),(7,8,'Can we win prices',0),(8,1,'I\'m color blind, the blue color is really affecting me. Where can I change that.',0),(9,1,'Like it',0),(10,1,'love it!',0),(11,1,'In love',0),(12,1,'In love with it more',0),(13,20,'Love it man',0),(14,2,'I love how the system works..It\'s adorable..',0),(15,24,'Stupid app',0),(16,7,'QWTFG',0),(17,1,'iovv',0),(18,20,'I hate this app',0),(19,1,'Please update the system',0),(20,7,'I like it like that guys',0),(21,1,'The system is good',0),(22,7,'qwerruiopasdfghjklzxcvbnm',0);
/*!40000 ALTER TABLE `user_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_tbl`
--

DROP TABLE IF EXISTS `user_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_tbl` (
  `fullname` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `school_code` int(30) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `address` varchar(50) NOT NULL,
  `nat_id` varchar(30) NOT NULL,
  `tsc_no` varchar(30) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `deleted` int(1) DEFAULT '0',
  `auth` varchar(10) NOT NULL,
  `payroll` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `activated` int(11) DEFAULT '1',
  `user_id` int(30) NOT NULL AUTO_INCREMENT,
  `profile_loc` mediumtext,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tbl`
--

LOCK TABLES `user_tbl` WRITE;
/*!40000 ALTER TABLE `user_tbl` DISABLE KEYS */;
INSERT INTO `user_tbl` VALUES ('HILARY NGIGE ADALA','2021-05-10',35601110,'0743551250','M','Busia, Ke','37367344','HJHKU8798K','hilla','@TsP$OrTpTs$pRoq',0,'1','disabled','olawrence06@gmail.com',1,1,'images/profile_pics/testimonytbl1/1/allstars.jpg'),('DISMAS ALANSA','2021-05-12',35601110,'0704241905','M','Bungoma, Ke','37357344','1987JHH','dismas','@TsP$OrTpTs$pRoq',0,'5','disabled','olawrence060@gmail.com',1,2,'images/profile_pics/testimonytbl1/2/download.jpg'),('LAWRENCE OUMA','2020-02-18',35601110,'0713620728','M','Kitale, Kenya','78278678','6576GVHDSS','lau','@TsP$OrTpTs$pRoq',0,'5','enabled','olawrence00@gmail.com',1,3,''),('WESLEY YOUNG','1992-03-06',35601110,'0715214112','M','Kitale Town','37367343','KHKJHG767','wesley','@TsP$OrTpTs$pRoq',0,'5','disabled','wesyoung@gmail.com',1,5,'images/profile_pics/testimonytbl1/5/main-qimg-6431be.png'),('ALEX OMBUCHA','2018-06-20',35601110,'0712554512','M','Bungoma Twn','54112541','56412KH','ombucha','@TsP$OrTpTs$pRoq',0,'5','enabled','ombucha@gmail.com',1,6,'images/profile_pics/testimonytbl1/6/16295316503404274699349211002792.jpg'),('JAMES ST PATRICK','1990-06-06',35601110,'0713342512','M','Kitale town','14212114','JJH8JH8UH8','james','@TsP$OrTpTs$pRoq',0,'0','enabled','james@st.patrick.com',1,7,NULL),('ESMOND BWIRE','1988-02-19',35601110,'0715123214','M','Busia,Ke','74512474','','essy','oyeYTRaPTRaPrTeG',0,'5','disabled','essy@gmail.com',1,8,'images/profile_pics/testimonytbl1/8/Screen Mirroring.jpg'),('IAN KAIRU','2003-05-06',35601110,'0745454545','M','Kiambu ke','32454587','464908','ian','@TsP$OrTpTs$pRoq',0,'3','disabled','kairu@gmail.com',1,9,'images/profile_pics/testimonytbl1/9/IMG-20190603-WA0006.jpg'),('Cecilia Adongo','1996-02-01',35601110,'0783840449','F','Bumutiru Busia','37367340','491976','Cecil','@TsP$OrTpTs$pRoq',0,'5','disabled','cecilia@adongo.com',1,10,'images/profile_pics/testimonytbl1/10/wp1910345-audi-rs7-wallpapers.jpg'),('MATHIAS ADALA','1974-02-02',35601110,'0745125454','M','Busia','37361545','491976','mathayo','@TsP$OrTpTs$pRoq',0,'5','disabled','mathias@gmail.com',1,11,''),('ANTONY KARANJA','1996-02-02',35601110,'0743225124','M','Kitale, Town','37541521','491976','anto','@TsP$OrTpTs$pRoq',0,'5','disabled','cherylawuor@gmail.com',1,12,''),('TRACY AKOTH','2000-07-04',35601110,'0712525878','F','Kisumu Town','5474151','KJH898079','tracy','sUrTPsaRVEooP@tRrTeG',0,'5','disabled','tracyakoth@gmail.com',1,15,''),('SAMUEL .L. JACKSON','1995-02-07',35601110,'0713620727','M','KILIFI TOWN','27457845','KHGJHKJ','sam','TRaPVEooAErp',0,'5','disabled','samuel@jackson.com',1,16,'images/profile_pics/testimonytbl1/16/BALLSDEEP.png'),('HOMER SIMPSON','1999-06-01',35601110,'0722524578','F','Malibu Beach','687878','3546532','homer','aWaSNeoMAErpoyeYPsaR',0,'5','disabled','malibu@gmail.com',1,17,'images/profile_pics/testimonytbl1/17/IMG-20190523-WA0001.jpg'),('Rihanna Modeo','1998-07-01',35601110,'0784582523','F','Kitale','57854447','Tfhgg644','thug','sUrTaWaSrTuLkMuL',0,'5','disabled','thuglife@gmail.com',1,18,'images/profile_pics/testimonytbl1/18/IMG-20210710-WA0001.jpg'),('John kairu','2001-10-09',35601110,'0716237716','M','Thika','10317310','12345','John','PrG?pRoqpTs$$OrT@TsP',0,'3','disabled','kairuian123@gmail.com',1,19,'images/profile_pics/testimonytbl1/19/16280928705091295203271278390560.jpg'),('KRANIUM WANGENDO','1999-02-02',35601110,'0714547487','F','Kisumu City','35451245','84462','kranium','@TsP$OrTpTs$pRoq',0,'2','disabled','kranium@gmail.com',1,20,'images/profile_pics/testimonytbl1/20/hour.png'),('KAMAU WANGENDO ','2003-06-03',35601110,'0714151216','F','KIAMBU','0544545','454484','wangendo','@TsP$OrTpTs$pRoq',0,'2','disabled','kk@gmail.com',1,26,''),('DAVID BREMMY','2003-09-05',35601110,'0743121211','M','Bidco Thika','5421542','987987','bremmy','@TsP$OrTpTs$pRoq',0,'5',NULL,'hilaryme45@gmail.com',1,28,NULL),('Joseph Gathure','1980-04-01',35601110,'0720123123','M','Mombasa','2234568','sdfs/32kk','jdoe','@TsP$OrTpTs$pRoq',0,'0',NULL,'johndoe@gmail.com',1,29,'images/profile_pics/testimonytbl1/29/logo 2.jpg'),('kimani wamatangi','2000-02-08',35601110,'0712151214','M','kenol','2353253225','','kim','@TsP$OrTpTs$pRoq',0,'3',NULL,'gg@gmail.com',1,30,NULL),('kevin jum','2000-02-08',35601110,'0745343434','M','juji','98887','','kevin','@TsP$OrTpTs$pRoq',0,'5',NULL,'',1,31,NULL),('LILY OUMA','2000-02-15',35601110,'0115086647','F','JUJA','786776','6677878','LILY','@TsP$OrTpTs$pRoq',0,'5',NULL,'',1,32,NULL),('JUNE','2000-01-01',100,'0725093162','F','JUJA','0000000','00000001','june','@TsP$OrTpTs$pRoq',0,'1',NULL,NULL,1,33,NULL),('teacher test','2003-11-17',35601110,'0712345678','F','kimoli','37oooo','','teacher','@TsP$OrTpTs$pRoq',0,'5',NULL,'teachertest@gmail.co,',1,34,NULL),('JUNE AKINYI OKONGO','1970-06-20',100,'0733714015','F','330 KALIMONI','10550828','349288','JUNE','amaRN$9*4!96@TsP$OrTpTs$pRoq',0,'0',NULL,'june1974@gmail.com',1,35,NULL),('THOMAS TOMAS','2003-11-06',35601110,'0715451412','F','Kisumu City','415266532','2542522','hillas','@TsP$OrTpTs$pRoq',0,'0',NULL,'james@gmail.com',1,36,NULL);
/*!40000 ALTER TABLE `user_tbl` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-10 15:18:51
