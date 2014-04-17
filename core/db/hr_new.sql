-- MySQL dump 10.10
--
-- Host: localhost    Database: hr_new
-- ------------------------------------------------------
-- Server version	5.0.15-nt

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
-- Table structure for table `hr_company`
--

DROP TABLE IF EXISTS `hr_company`;
CREATE TABLE `hr_company` (
  `company_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `password` varchar(62) NOT NULL,
  `phone` varchar(20) default NULL,
  `contact_person` varchar(60) default NULL,
  `created_at` datetime default '0000-00-00 00:00:00',
  `changed_at` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_company`
--


/*!40000 ALTER TABLE `hr_company` DISABLE KEYS */;
LOCK TABLES `hr_company` WRITE;
INSERT INTO `hr_company` VALUES (1,'HR.am Armenian Corporate Network','noreply@hr.am','','055-55-55-55','Tatev Abrahamyan','2014-04-05 03:56:49','2014-04-05 03:56:52');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_company` ENABLE KEYS */;

--
-- Table structure for table `hr_industry`
--

DROP TABLE IF EXISTS `hr_industry`;
CREATE TABLE `hr_industry` (
  `industry_id` tinyint(4) unsigned NOT NULL auto_increment,
  `name` varchar(80) NOT NULL,
  PRIMARY KEY  (`industry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_industry`
--


/*!40000 ALTER TABLE `hr_industry` DISABLE KEYS */;
LOCK TABLES `hr_industry` WRITE;
INSERT INTO `hr_industry` VALUES (1,'Accounting/Auditing'),(2,'Information Technologies (IT)');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_industry` ENABLE KEYS */;

--
-- Table structure for table `hr_skill`
--

DROP TABLE IF EXISTS `hr_skill`;
CREATE TABLE `hr_skill` (
  `skill_id` int(11) unsigned NOT NULL auto_increment,
  `industry_id` smallint(6) unsigned NOT NULL,
  `spec_id` smallint(6) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  PRIMARY KEY  (`skill_id`),
  KEY `spec_id_cnt` (`spec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_skill`
--


/*!40000 ALTER TABLE `hr_skill` DISABLE KEYS */;
LOCK TABLES `hr_skill` WRITE;
INSERT INTO `hr_skill` VALUES (1,1,2,'Accountant_1'),(2,1,2,'Accountant_2'),(3,1,4,'Auditor_1'),(4,1,4,'Auditor_2'),(5,1,4,'Auditor_3'),(6,2,6,'MySQL'),(7,2,6,'SQL Server'),(8,2,6,'Oracle'),(9,2,9,'Assembler'),(10,2,9,'Cobol'),(11,2,9,'C++'),(12,2,11,'C++'),(13,2,11,'Visual Basic'),(14,2,11,'Java'),(15,2,11,'J2EE'),(16,2,20,'CSS'),(17,2,20,'HTML'),(18,2,20,'HTML5'),(19,2,20,'CSS3'),(20,2,20,'Photoshop'),(21,2,20,'CorelDraw'),(22,2,11,'MySQL'),(23,2,21,'Java'),(24,2,21,'PHP'),(25,2,21,'HTML'),(26,2,21,'CSS'),(27,2,21,'JavaScript'),(28,2,21,'Apache Web Server'),(29,1,0,'Accounting_1'),(30,1,0,'Accounting_2'),(31,2,0,'IT_Specialist_1'),(32,2,0,'IT_Specialist_2'),(33,2,0,'IT_Specialist_3'),(34,2,0,'OOP Model'),(35,2,0,'Unit Testing');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_skill` ENABLE KEYS */;

--
-- Table structure for table `hr_soft_skill`
--

DROP TABLE IF EXISTS `hr_soft_skill`;
CREATE TABLE `hr_soft_skill` (
  `soft_id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(80) NOT NULL default '',
  PRIMARY KEY  (`soft_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_soft_skill`
--


/*!40000 ALTER TABLE `hr_soft_skill` DISABLE KEYS */;
LOCK TABLES `hr_soft_skill` WRITE;
INSERT INTO `hr_soft_skill` VALUES (1,'Research skills'),(2,'Presentation skills'),(3,'Analytical thinking'),(4,'Organizational skills'),(5,'Managerial skills'),(6,'Negotiation/Communication skills'),(7,'Quick decision making and problem solving skills'),(8,'Sales and marketing skills'),(9,'Understanding of customer requirements'),(10,'Understanding of market dynamics and requirements'),(11,'Ability to work in a team'),(12,'Ability to prioritize and handle multiple tasks'),(13,'Ability to work under pressure'),(14,'Ability to meet deadlines'),(15,'Ability to manage risks'),(16,'Ability to learn quickly'),(17,'Ability to manage meetings'),(18,'Ability to work independently'),(19,'Ability to identify, develop and manage talent'),(20,'Leadership skills'),(21,'Team building skills'),(22,'Result oriented'),(23,'Detail oriented'),(24,'Creative'),(25,'Initiative'),(26,'Dynamic'),(27,'Energetic'),(28,'Flexible'),(29,'Enthusiastic'),(30,'Hard working person'),(31,'Disciplined/Responsible'),(32,'Well-mannered'),(33,'Punctual'),(34,'Sense of humor');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_soft_skill` ENABLE KEYS */;

--
-- Table structure for table `hr_specialization`
--

DROP TABLE IF EXISTS `hr_specialization`;
CREATE TABLE `hr_specialization` (
  `spec_id` smallint(6) unsigned NOT NULL auto_increment,
  `industry_id` tinyint(4) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  PRIMARY KEY  (`spec_id`),
  KEY `ind_cnt` (`industry_id`),
  CONSTRAINT `ind_cnt` FOREIGN KEY (`industry_id`) REFERENCES `hr_industry` (`industry_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_specialization`
--


/*!40000 ALTER TABLE `hr_specialization` DISABLE KEYS */;
LOCK TABLES `hr_specialization` WRITE;
INSERT INTO `hr_specialization` VALUES (1,1,'Account Representative'),(2,1,'Accountant'),(3,1,'Accounting Assistant'),(4,1,'Auditor'),(5,2,'Computer and Information Systems Manager'),(6,2,'Database Administrator'),(7,2,'Information Technology Manager'),(8,2,'Network Architect'),(9,2,'Programmer'),(10,2,'Security Specialist'),(11,2,'Software Engineer'),(12,2,'Software Quality Assurance Analyst'),(13,2,'Support Specialist'),(14,2,'System Administrator'),(15,2,'System Analyst'),(16,2,'System Architect'),(17,2,'System Designer'),(18,2,'Technical Specialist'),(19,2,'Web Administrator'),(20,2,'Web Designer'),(21,2,'Web Developer'),(22,2,'Webmaster');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_specialization` ENABLE KEYS */;

--
-- Table structure for table `hr_university`
--

DROP TABLE IF EXISTS `hr_university`;
CREATE TABLE `hr_university` (
  `univer_id` tinyint(4) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`univer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_university`
--


/*!40000 ALTER TABLE `hr_university` DISABLE KEYS */;
LOCK TABLES `hr_university` WRITE;
INSERT INTO `hr_university` VALUES (1,'Yerevan State University (YSU)'),(2,'Yerevan State Engineering University (SEUA)'),(3,'Yerevan State University of Architecture and Construction'),(4,'Yerevan State Institute of Economy'),(5,'Yerevan State Linguistic University'),(6,'Yerevan State Medical University'),(7,'Armenian State Pedagogical Institute'),(8,'Armenian Agricultural University'),(9,'Yerevan State Conservatory'),(10,'Yerevan State Academy of Fine Arts'),(11,'Yerevan State Institute of the Cinematography and Theatre'),(12,'Armenian State University of Physical Culture'),(13,'Armenian-Russian (Slavonic) University'),(14,'French University Foundation in Armenia'),(15,'European Regional Institute of Information & Communication Technologies in Armenia'),(16,'American University of Armenia'),(17,'Gyumri State Pedagogical Institute'),(18,'Vanadzor State Pedagogical Institute'),(19,'Gavar State University'),(20,'Financial Academy \"MFB\"'),(21,'Eurasia International University'),(22,'Humanitarian Institute of Hrazdan'),(23,'Imastaser Anania Shirakatsi University'),(24,'Mkhitar Gosh University'),(25,'Northern University'),(26,'University of the Traditional Medicine'),(27,'Urartu University'),(28,'Yerevan University after M. Khorenatsi'),(29,'Armenian Academy of Sciences');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_university` ENABLE KEYS */;

--
-- Table structure for table `hr_university_faculty`
--

DROP TABLE IF EXISTS `hr_university_faculty`;
CREATE TABLE `hr_university_faculty` (
  `faculty_id` smallint(6) unsigned NOT NULL auto_increment,
  `univer_id` smallint(6) unsigned NOT NULL,
  `name` varchar(120) default NULL,
  PRIMARY KEY  (`faculty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_university_faculty`
--


/*!40000 ALTER TABLE `hr_university_faculty` DISABLE KEYS */;
LOCK TABLES `hr_university_faculty` WRITE;
INSERT INTO `hr_university_faculty` VALUES (1,1,'History'),(2,1,'Armenian Philology'),(3,1,'Chemistry'),(4,1,'Physics'),(5,1,'Economics'),(6,1,'Mathematics and Mechanics'),(7,1,'Geography and Geology'),(8,1,'Law'),(9,1,'Russian Philology'),(10,1,'Journalism'),(11,1,'Philosophy and Psychology'),(12,1,'Informatics and Applied Mathematics'),(13,1,'Radiophysics'),(14,1,'Sociology'),(15,1,'Romance-Germanic Philology'),(16,1,'International Relations'),(17,1,'Theology'),(18,2,'Chemical Technologies and Environmental Engineering'),(19,2,'Electrical Engineering'),(20,2,'Machine Building'),(21,2,'Mining AND Metallurgy'),(22,2,'Cybernetics'),(23,2,'Power Engineering'),(24,2,'Radio Engineering and Communication Systems'),(25,2,'Computer Systems and Informatics'),(26,2,'Transportation Systems'),(27,2,'Applied Mathematics'),(28,2,'Mechanics and Machine Study'),(29,3,'Design'),(30,3,'Architecture'),(31,3,'Computer Aided Drafting'),(32,3,'Applied Art and Historic-Cultural Tourism'),(33,3,'Industrial and Civil Construction'),(34,3,'Hydraulic Construction and Municipal Services'),(35,3,'Computer Engineering and Management'),(36,3,'Economics'),(37,3,'Cadastre AND Ecology'),(38,4,'Management'),(39,4,'Marketing and Organization of Business'),(40,4,'Regulation of Economy and International Economic Relations'),(41,4,'Finance'),(42,4,'Accounting and Audit'),(43,4,'Informatics and Statistics'),(44,5,'Intercultural Communication'),(45,5,'Russian Language, Literature and Foreign Languages'),(46,5,'Foreign Languages'),(47,6,'General Medicine'),(48,6,'Public Health'),(49,6,'Faculty of Stomatology'),(50,6,'Pharmacy'),(51,6,'Military Medicine'),(52,6,'Postgraduate and Continuing Education'),(53,7,'Philology'),(54,7,'Foreign Languages'),(55,7,'History and Jurisprudence'),(56,7,'Preschool Education'),(57,7,'Psychology and social pedagogy'),(58,7,'Biology, Chemistry and Geography'),(59,7,'Mathematics, Physics AND Informatics'),(60,7,'Art Education'),(61,7,'Culture'),(62,7,'Education Management'),(63,7,'Special Education'),(64,8,'Agronomy'),(65,8,'Foodstuff Technologies'),(66,8,'Hydro Melioration, Land Management and Land Cadastre'),(67,8,'Agriculture Mechanization and Automobile Transportation'),(68,8,'Veterinary Medicine and Sanitary Expertize'),(69,8,'Agribusiness and Marketing'),(70,8,'Economics'),(71,9,'Piano'),(72,9,'Orchestra'),(73,9,'Vocal - theoretical'),(74,10,'Fashion Design'),(75,10,'Interior and Exterior Design'),(76,10,'Graphic Design'),(77,10,'Decorative Design'),(78,11,'Theatre'),(79,11,'Cinema, TV & Animation'),(80,11,'Art History & Art Management'),(81,12,'Sports and Health Recovery'),(82,12,'Training pedagogical'),(83,13,'Mathematics and High Technology'),(84,13,'Economics and Business '),(85,13,'Law and Politics'),(86,13,'Humanities'),(87,13,'Media and Advertising'),(88,14,'Law'),(89,14,'Economics'),(90,14,'Finance and Accounting'),(91,14,'Marketing'),(92,14,'Foreign Languages'),(93,14,'Humanities and Social Sciences'),(94,14,'Sport and physical upbringing'),(95,15,'Information Technologies'),(96,15,'Economy AND Management'),(97,15,'International Relations'),(98,15,'Law'),(99,15,'Tourism'),(100,15,'Linguistics'),(101,15,'Public Health'),(102,15,'Psycology'),(103,16,'Masters in Teaching English as a Foreign Language'),(104,16,'Master of Laws (LL.M.)'),(105,16,'Master of Science in Computer & Information Science (MSc CIS)'),(106,16,'Master of Engineering in Industrial Engineering and Systems Management (ME IESM)'),(107,16,'Master of Business Administration (MBA)'),(108,16,'Master of Public Health (MPH)'),(109,16,'Master of Political Science and International Affairs (MPSIA)'),(110,17,'History and Philology'),(111,17,'Physics AND Mathematics'),(112,17,'Pedagogics'),(113,17,'Foreign Languages'),(114,17,'Biology and Geography'),(115,17,'Physical Training'),(116,18,'Philology'),(117,18,'History and Geography'),(118,18,'Pedagogy'),(119,18,'Biology and Chemistry'),(120,18,'Physics and Mathematics՞'),(121,19,'Philology'),(122,19,'Natural Sciences'),(123,19,'Humanities'),(124,19,'Economics'),(125,20,'Finance'),(126,20,'Accounting'),(127,20,'Information Technologies'),(128,21,'Foreign Languages'),(129,21,'Management & Economics'),(130,21,'Law'),(131,22,'Economics'),(132,22,'Pedagogical'),(133,22,'Juridical'),(134,23,'Philological'),(135,23,'Economical and juridical'),(136,24,'Social Sciencies and Languages'),(137,24,'Computer Systems and Informatics'),(138,25,'Law'),(139,25,'Economics and Management'),(140,25,'Pedagogics'),(141,25,'Foreign Languages'),(142,25,'Journalism'),(143,25,'Philology'),(144,25,'Psychology'),(145,26,'General Medicine'),(146,26,'Stomatology'),(147,27,'General Medicine'),(148,27,'Stomatology'),(149,27,'Pharmacy'),(150,27,'Economics'),(151,27,'Business Administration'),(152,27,'Psychology'),(153,27,'Linguistics'),(154,27,'Pedagogy'),(155,27,'Criminal Law'),(156,27,'Civil Law'),(157,27,'Information Technologies'),(158,27,'Design'),(159,27,'Fashion design'),(160,27,'Painting'),(161,27,'Graphic design'),(162,27,'Oriental Studies'),(163,27,'Diplomacy'),(164,28,'Armenian Language and literature'),(165,28,'English Language and literature'),(166,28,'Informatics and applied mathematics'),(167,28,'Pedagogics and methods'),(168,28,'International economic velations'),(169,28,'Law (jurisprudence)'),(170,29,'Mathematical and Technical Sciences'),(171,29,'Physics and Astrophysics'),(172,29,'Natural Sciences'),(173,29,'Chemistry and Earth Sciences'),(174,29,'Armenology and Social Sciences');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_university_faculty` ENABLE KEYS */;

--
-- Table structure for table `hr_user`
--

DROP TABLE IF EXISTS `hr_user`;
CREATE TABLE `hr_user` (
  `user_id` int(11) unsigned NOT NULL auto_increment,
  `mail` varchar(50) NOT NULL,
  `password` varchar(62) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `location` varchar(100) NOT NULL,
  `linkedin` varchar(100) default NULL,
  `birth_date` date default '0000-00-00',
  `bio` text,
  `picture_key` varchar(10) default NULL,
  `resume_key` varchar(255) default NULL,
  `changed_at` datetime default '0000-00-00 00:00:00',
  `created_at` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_user`
--


/*!40000 ALTER TABLE `hr_user` DISABLE KEYS */;
LOCK TABLES `hr_user` WRITE;
INSERT INTO `hr_user` VALUES (1,'vahan.shamtsyan@gmail.com','.nj2PbKLr6ZaYEGHgM4EPugAgrh2s1ZnfsBIFdBc2mjuydrM0y2YC','Vahan','Shamtsyan','Yerevan, Armenia','http://www.linkedin.com/in/vahanshamtsyan','1986-07-21','Something here about myself!!\r\nTesting here breaks.\r\n\r\nAnd html characters as well <script>alert(1);</script>','9kRUvWxY5S','2HmI_FanclubsCupaaaaaaaaa_aaaaa','2014-04-17 21:21:11','2014-04-04 18:49:52');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_user` ENABLE KEYS */;

--
-- Table structure for table `hr_user_education`
--

DROP TABLE IF EXISTS `hr_user_education`;
CREATE TABLE `hr_user_education` (
  `user_id` int(11) unsigned NOT NULL,
  `univer_id` tinyint(4) unsigned NOT NULL,
  `faculty_id` smallint(6) unsigned default NULL,
  `degree` varchar(20) NOT NULL,
  `changed_at` datetime default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_user_education`
--


/*!40000 ALTER TABLE `hr_user_education` DISABLE KEYS */;
LOCK TABLES `hr_user_education` WRITE;
INSERT INTO `hr_user_education` VALUES (1,2,22,'Bachelor','2014-04-17 21:21:11'),(1,2,22,'Masters','2014-04-17 21:21:11');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_user_education` ENABLE KEYS */;

--
-- Table structure for table `hr_user_experience`
--

DROP TABLE IF EXISTS `hr_user_experience`;
CREATE TABLE `hr_user_experience` (
  `user_id` int(11) unsigned NOT NULL,
  `industry_id` tinyint(4) unsigned NOT NULL,
  `spec_id` smallint(6) unsigned NOT NULL,
  `years` varchar(4) NOT NULL,
  `changed_at` datetime default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_user_experience`
--


/*!40000 ALTER TABLE `hr_user_experience` DISABLE KEYS */;
LOCK TABLES `hr_user_experience` WRITE;
INSERT INTO `hr_user_experience` VALUES (1,2,21,'5+','2014-04-17 21:21:11'),(1,2,11,'2','2014-04-17 21:21:11');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_user_experience` ENABLE KEYS */;

--
-- Table structure for table `hr_user_language`
--

DROP TABLE IF EXISTS `hr_user_language`;
CREATE TABLE `hr_user_language` (
  `user_id` int(11) unsigned NOT NULL,
  `language` varchar(20) NOT NULL,
  `level` varchar(20) NOT NULL,
  `changed_at` datetime default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_user_language`
--


/*!40000 ALTER TABLE `hr_user_language` DISABLE KEYS */;
LOCK TABLES `hr_user_language` WRITE;
INSERT INTO `hr_user_language` VALUES (1,'Spanish','Elementary','2014-04-17 21:21:11'),(1,'Russian','Native','2014-04-17 21:21:11'),(1,'English','Advanced','2014-04-17 21:21:11'),(1,'Armenian','Native','2014-04-17 21:21:11'),(1,'Italian','Elementary','2014-04-17 21:21:11'),(1,'Georgian','Intermediate','2014-04-17 21:21:11'),(1,'Chinese','Native','2014-04-17 21:21:11');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_user_language` ENABLE KEYS */;

--
-- Table structure for table `hr_user_skill`
--

DROP TABLE IF EXISTS `hr_user_skill`;
CREATE TABLE `hr_user_skill` (
  `user_id` int(11) unsigned NOT NULL,
  `skill_id` int(11) unsigned NOT NULL,
  `years` varchar(4) NOT NULL,
  `changed_at` datetime default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_user_skill`
--


/*!40000 ALTER TABLE `hr_user_skill` DISABLE KEYS */;
LOCK TABLES `hr_user_skill` WRITE;
INSERT INTO `hr_user_skill` VALUES (1,34,'3','2014-04-17 21:21:11'),(1,28,'4','2014-04-17 21:21:11'),(1,24,'5+','2014-04-17 21:21:11'),(1,26,'2','2014-04-17 21:21:11'),(1,25,'5+','2014-04-17 21:21:11'),(1,14,'3','2014-04-17 21:21:11'),(1,27,'5+','2014-04-17 21:21:11'),(1,22,'5+','2014-04-17 21:21:11'),(1,15,'3','2014-04-17 21:21:11');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_user_skill` ENABLE KEYS */;

--
-- Table structure for table `hr_user_soft_skill`
--

DROP TABLE IF EXISTS `hr_user_soft_skill`;
CREATE TABLE `hr_user_soft_skill` (
  `user_id` int(11) unsigned NOT NULL default '0',
  `soft_id` smallint(6) NOT NULL,
  `level` varchar(10) NOT NULL,
  `changed_at` datetime default '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_user_soft_skill`
--


/*!40000 ALTER TABLE `hr_user_soft_skill` DISABLE KEYS */;
LOCK TABLES `hr_user_soft_skill` WRITE;
INSERT INTO `hr_user_soft_skill` VALUES (1,5,'Advanced','2014-04-17 21:21:11'),(1,10,'Average','2014-04-17 21:21:11'),(1,6,'Good','2014-04-17 21:21:11'),(1,11,'Advanced','2014-04-17 21:21:11'),(1,13,'Good','2014-04-17 21:21:11'),(1,14,'Average','2014-04-17 21:21:11'),(1,16,'Brilliant','2014-04-17 21:21:11');
UNLOCK TABLES;
/*!40000 ALTER TABLE `hr_user_soft_skill` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

