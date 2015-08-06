-- MySQL dump 10.13  Distrib 5.5.42, for osx10.6 (i386)
--
-- Host: localhost    Database: mapping
-- ------------------------------------------------------
-- Server version	5.5.42

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
-- Table structure for table `activity`
--

DROP TABLE IF EXISTS `activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `activity_category_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8_unicode_ci,
  `shortDescription` longtext COLLATE utf8_unicode_ci,
  `isFeatured` tinyint(1) NOT NULL DEFAULT '0',
  `isDistrictWide` tinyint(1) NOT NULL DEFAULT '0',
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AC74095AC32A47EE` (`school_id`),
  KEY `IDX_AC74095A1CC8F7EE` (`activity_category_id`),
  KEY `IDX_AC74095A166D1F9C` (`project_id`),
  CONSTRAINT `FK_AC74095A166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_AC74095A1CC8F7EE` FOREIGN KEY (`activity_category_id`) REFERENCES `activity_category` (`id`),
  CONSTRAINT `FK_AC74095AC32A47EE` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity`
--

LOCK TABLES `activity` WRITE;
/*!40000 ALTER TABLE `activity` DISABLE KEYS */;
INSERT INTO `activity` VALUES (1,27,4,'PhilWP Consulting',NULL,NULL,0,0,NULL,NULL),(2,14,1,'APHD Field Placements',NULL,NULL,0,0,NULL,NULL),(3,14,2,'PCEL Research',NULL,NULL,0,0,NULL,NULL),(4,14,4,'PhilWP Consulting',NULL,NULL,0,0,NULL,NULL),(5,12,1,'APHD Field Placements',NULL,NULL,0,0,NULL,NULL),(6,12,4,'Win/Win Program',NULL,NULL,0,0,NULL,NULL),(7,17,1,'RWL Fieldwork',NULL,NULL,0,0,NULL,NULL),(8,17,1,'TEP Field Placements (Practicum and Student Teacher',NULL,NULL,0,0,NULL,NULL),(9,17,1,'APHD Field Placements',NULL,NULL,0,0,NULL,NULL),(10,17,2,'PLM Math Study',NULL,NULL,0,0,'http://www.gse.upenn.edu/plmstudy',1),(11,17,5,'Jennifer Duffy, Mid-Career \'15, Principal',NULL,NULL,0,0,NULL,NULL),(12,17,4,'Penn Science Across Ages',NULL,NULL,0,0,'http://www.pennscienceacrossages.com',NULL),(13,17,4,'Evidence-based Program for the Integration of Curricula (EPIC)',NULL,NULL,0,0,'http://www2.gse.upenn.edu/child/projects/epic',NULL),(14,17,3,'Literacy PD',NULL,NULL,0,0,NULL,NULL),(15,156,2,'PLM Math Study',NULL,NULL,0,0,'http://www.gse.upenn.edu/plmstudy',1),(16,156,1,'APHD Field Placements',NULL,NULL,0,0,NULL,NULL),(17,150,1,'APHD Field Placements',NULL,NULL,0,0,NULL,NULL),(18,9,1,'APHD Field Placements',NULL,NULL,0,0,NULL,NULL),(19,9,4,'EPIC and ABCS',NULL,NULL,0,0,NULL,NULL),(20,167,4,'PhilWP Consulting',NULL,NULL,0,0,NULL,NULL),(21,29,4,'PhilWP Consulting',NULL,NULL,0,0,NULL,NULL),(22,29,1,'APHD Field Placements',NULL,NULL,0,0,NULL,NULL);
/*!40000 ALTER TABLE `activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_category`
--

DROP TABLE IF EXISTS `activity_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_category`
--

LOCK TABLES `activity_category` WRITE;
/*!40000 ALTER TABLE `activity_category` DISABLE KEYS */;
INSERT INTO `activity_category` VALUES (1,'Training',''),(2,'Research',''),(3,'Professional Development',''),(4,'Consulting',''),(5,'Leadership',''),(6,'Other','');
/*!40000 ALTER TABLE `activity_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_division_or_group`
--

DROP TABLE IF EXISTS `activity_division_or_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_division_or_group` (
  `activity_id` int(11) NOT NULL,
  `division_or_group_id` int(11) NOT NULL,
  PRIMARY KEY (`activity_id`,`division_or_group_id`),
  KEY `IDX_BF67DB2181C06096` (`activity_id`),
  KEY `IDX_BF67DB21ABE8BECF` (`division_or_group_id`),
  CONSTRAINT `FK_BF67DB2181C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_BF67DB21ABE8BECF` FOREIGN KEY (`division_or_group_id`) REFERENCES `division_or_group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_division_or_group`
--

LOCK TABLES `activity_division_or_group` WRITE;
/*!40000 ALTER TABLE `activity_division_or_group` DISABLE KEYS */;
INSERT INTO `activity_division_or_group` VALUES (1,1),(2,2),(3,3),(4,1),(5,2),(7,5),(8,4),(9,2),(11,6),(16,2),(17,2),(18,2),(20,1),(21,1),(22,2);
/*!40000 ALTER TABLE `activity_division_or_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_individual`
--

DROP TABLE IF EXISTS `activity_individual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_individual` (
  `activity_id` int(11) NOT NULL,
  `individual_id` int(11) NOT NULL,
  PRIMARY KEY (`activity_id`,`individual_id`),
  KEY `IDX_D271C21F81C06096` (`activity_id`),
  KEY `IDX_D271C21FAE271C0D` (`individual_id`),
  CONSTRAINT `FK_D271C21F81C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_D271C21FAE271C0D` FOREIGN KEY (`individual_id`) REFERENCES `individual` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_individual`
--

LOCK TABLES `activity_individual` WRITE;
/*!40000 ALTER TABLE `activity_individual` DISABLE KEYS */;
INSERT INTO `activity_individual` VALUES (6,1),(10,2),(12,3),(13,4),(14,5),(15,2),(19,4);
/*!40000 ALTER TABLE `activity_individual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_topic`
--

DROP TABLE IF EXISTS `activity_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_topic` (
  `activity_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (`activity_id`,`topic_id`),
  KEY `IDX_8342E8A881C06096` (`activity_id`),
  KEY `IDX_8342E8A81F55203D` (`topic_id`),
  CONSTRAINT `FK_8342E8A81F55203D` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_8342E8A881C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_topic`
--

LOCK TABLES `activity_topic` WRITE;
/*!40000 ALTER TABLE `activity_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_year`
--

DROP TABLE IF EXISTS `activity_year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_year` (
  `activity_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  PRIMARY KEY (`activity_id`,`year_id`),
  KEY `IDX_B846A8A581C06096` (`activity_id`),
  KEY `IDX_B846A8A540C1FEA7` (`year_id`),
  CONSTRAINT `FK_B846A8A540C1FEA7` FOREIGN KEY (`year_id`) REFERENCES `year` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B846A8A581C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_year`
--

LOCK TABLES `activity_year` WRITE;
/*!40000 ALTER TABLE `activity_year` DISABLE KEYS */;
INSERT INTO `activity_year` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1);
/*!40000 ALTER TABLE `activity_year` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_user`
--

DROP TABLE IF EXISTS `app_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_88BDF3E992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_88BDF3E9A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_user`
--

LOCK TABLES `app_user` WRITE;
/*!40000 ALTER TABLE `app_user` DISABLE KEYS */;
INSERT INTO `app_user` VALUES (1,'mariakel','mariakel','mariakel@gse.upenn.edu','mariakel@gse.upenn.edu',1,'ae9b5by1vxck8gwks8gwkkow0ko00k0','WCMfNqItlXjVQQVkJfRvpYufbb89J9/5HzcD0FpMruG9JO60/Wtn4WlTR+GiNlZ0hvIy124nBrBrU+Fio2dlrg==','2015-08-02 12:08:54',0,0,NULL,NULL,NULL,'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}',0,NULL);
/*!40000 ALTER TABLE `app_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `division_or_group`
--

DROP TABLE IF EXISTS `division_or_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `division_or_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `division_or_group`
--

LOCK TABLES `division_or_group` WRITE;
/*!40000 ALTER TABLE `division_or_group` DISABLE KEYS */;
INSERT INTO `division_or_group` VALUES (1,'Philadelphia Writing Project','group','http://www.gse.upenn.edu/philwp','PhilWP',''),(2,'Applied Psychology and Human Development Division','division','http://www.gse.upenn.edu/aphd','APHD',''),(3,'Penn Center for Educational Leadership','center','http://www.gse.upenn.edu/pcel','PCEL',''),(4,'Teacher Education Program','division','http://www.gse.upenn.edu/tep','TEP',''),(5,'Reading, Writing, Literacy Division','division','http//www.gse.upenn.edu/rwl','RWL',''),(6,'Mid-Career Doctoral Program in Educational Leadership','division','http://www.gse.upenn.edu/midcareer','Mid-Career','');
/*!40000 ALTER TABLE `division_or_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups_activities`
--

DROP TABLE IF EXISTS `groups_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups_activities` (
  `division_or_group_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  PRIMARY KEY (`division_or_group_id`,`activity_id`),
  KEY `IDX_EE39D625ABE8BECF` (`division_or_group_id`),
  KEY `IDX_EE39D62581C06096` (`activity_id`),
  CONSTRAINT `FK_EE39D62581C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_EE39D625ABE8BECF` FOREIGN KEY (`division_or_group_id`) REFERENCES `division_or_group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups_activities`
--

LOCK TABLES `groups_activities` WRITE;
/*!40000 ALTER TABLE `groups_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `individual`
--

DROP TABLE IF EXISTS `individual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `individual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `individual`
--

LOCK TABLES `individual` WRITE;
/*!40000 ALTER TABLE `individual` DISABLE KEYS */;
INSERT INTO `individual` VALUES (1,'Dr. Judy Brody','http://scholar.gse.upenn.edu/brody'),(2,'Dr. Laura Desimone','http://scholar.gse.upenn.edu/desimone'),(3,'NancyLee Bergey','http://scholar.gse.upenn.edu/bergey'),(4,'Dr. John Fantuzzo','http://scholar.gse.upenn.edu/fantuzzo'),(5,'Heidi Gross',NULL);
/*!40000 ALTER TABLE `individual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peoples_activities`
--

DROP TABLE IF EXISTS `peoples_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peoples_activities` (
  `individual_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  PRIMARY KEY (`individual_id`,`activity_id`),
  KEY `IDX_C3D21AAAAE271C0D` (`individual_id`),
  KEY `IDX_C3D21AAA81C06096` (`activity_id`),
  CONSTRAINT `FK_C3D21AAA81C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C3D21AAAAE271C0D` FOREIGN KEY (`individual_id`) REFERENCES `individual` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peoples_activities`
--

LOCK TABLES `peoples_activities` WRITE;
/*!40000 ALTER TABLE `peoples_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `peoples_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `isFeatured` tinyint(1) NOT NULL,
  `isDistrictWide` tinyint(1) NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES (1,'PLM Math Study','This intervention consists of four perceptual learning modules (PLMs) that integrate (1) principles of perceptual learning that accelerate learners’ abilities to recognize and discriminate key structures and relations in complex domains, and (2) adaptive learning algorithms that use a constant stream of performance data, combined with principles of learning and memory, to improve the effectiveness and efficiency of learning by adapting the learning process to each individual.',0,0,'http://www.gse.upenn.edu/plmstudy'),(2,'Ongoing Assessment Project (OGAP)','OGAP is a systematic, intentional, and iterative formative assessment system grounded in the research on how students learn mathematics. The OGAP system is seamlessly integrated into a set of tools, practices, support materials, and in-depth professional development.',0,0,'http://www.ogapmath.com/');
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_division_or_group`
--

DROP TABLE IF EXISTS `project_division_or_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_division_or_group` (
  `project_id` int(11) NOT NULL,
  `division_or_group_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`division_or_group_id`),
  KEY `IDX_28ECC48B166D1F9C` (`project_id`),
  KEY `IDX_28ECC48BABE8BECF` (`division_or_group_id`),
  CONSTRAINT `FK_28ECC48BABE8BECF` FOREIGN KEY (`division_or_group_id`) REFERENCES `division_or_group` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_28ECC48B166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_division_or_group`
--

LOCK TABLES `project_division_or_group` WRITE;
/*!40000 ALTER TABLE `project_division_or_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_division_or_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_individual`
--

DROP TABLE IF EXISTS `project_individual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_individual` (
  `project_id` int(11) NOT NULL,
  `individual_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`individual_id`),
  KEY `IDX_9F358725166D1F9C` (`project_id`),
  KEY `IDX_9F358725AE271C0D` (`individual_id`),
  CONSTRAINT `FK_9F358725AE271C0D` FOREIGN KEY (`individual_id`) REFERENCES `individual` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_9F358725166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_individual`
--

LOCK TABLES `project_individual` WRITE;
/*!40000 ALTER TABLE `project_individual` DISABLE KEYS */;
INSERT INTO `project_individual` VALUES (1,2);
/*!40000 ALTER TABLE `project_individual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_topic`
--

DROP TABLE IF EXISTS `project_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_topic` (
  `project_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`topic_id`),
  KEY `IDX_8E15D785166D1F9C` (`project_id`),
  KEY `IDX_8E15D7851F55203D` (`topic_id`),
  CONSTRAINT `FK_8E15D7851F55203D` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_8E15D785166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_topic`
--

LOCK TABLES `project_topic` WRITE;
/*!40000 ALTER TABLE `project_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_year`
--

DROP TABLE IF EXISTS `project_year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_year` (
  `project_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`year_id`),
  KEY `IDX_8213BB2F166D1F9C` (`project_id`),
  KEY `IDX_8213BB2F40C1FEA7` (`year_id`),
  CONSTRAINT `FK_8213BB2F40C1FEA7` FOREIGN KEY (`year_id`) REFERENCES `year` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_8213BB2F166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_year`
--

LOCK TABLES `project_year` WRITE;
/*!40000 ALTER TABLE `project_year` DISABLE KEYS */;
INSERT INTO `project_year` VALUES (1,1),(2,1);
/*!40000 ALTER TABLE `project_year` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school`
--

DROP TABLE IF EXISTS `school`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gradeLevel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `longitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=306 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school`
--

LOCK TABLES `school` WRITE;
/*!40000 ALTER TABLE `school` DISABLE KEYS */;
INSERT INTO `school` VALUES (2,'John Bartram High School','1010','High School','39.9216617','-75.2338847',''),(3,'West Philadelphia High School','1020','High School','39.9580893','-75.2194876',''),(4,'High School of the Future','1030','High School','39.9750318','-75.2035671',''),(5,'Paul Robeson High School for Human Services','1050','High School','39.9569822','-75.2059018',''),(6,'William L. Sayre High School','1100','High School','39.957475','-75.238568',''),(7,'William T. Tilden School','1130','Middle School','39.921728','-75.232634',''),(8,'Motivation High School','1190','High School','39.9445084','-75.2424503',''),(9,'John Barry School','1200','Elementary/Middle School','39.9643749','-75.238604',''),(10,'William C. Bryant School','1230','Elementary/Middle School','39.9529465','-75.2434277',''),(11,'Joseph W. Catharine School','1250','Elementary School','39.9280147','-75.2395019',''),(12,'Benjamin B. Comegys School','1260','Elementary/Middle School','39.9405617','-75.2163301',''),(13,'Sadie Alexander School','1280','Elementary/Middle School','39.9490401','-75.1811198',''),(14,'Andrew Hamilton School','1290','Elementary/Middle School','39.9543053','-75.2640746',''),(15,'Avery D. Harrington School','1300','Elementary/Middle School','39.947335','-75.230062',''),(16,'Samuel B. Huey School','1330','Elementary/Middle School','39.953327','-75.226859',''),(17,'Henry C. Lea School','1340','Elementary/Middle School','39.9545848','-75.2158867',''),(18,'William C. Longstreth School','1350','Elementary/Middle School','39.940634','-75.2325639',''),(19,'Morton McMichael School','1360','Elementary/Middle School','39.966046','-75.194768',''),(20,'S. Weir Mitchell School','1370','Elementary/Middle School','39.9368143','-75.2236126',''),(21,'Thomas G. Morton School','1380','Elementary School','39.9238448','-75.2274609',''),(22,'Samuel Powel School','1390','Elementary School','39.9603393','-75.1936401',''),(23,'John M. Patterson School','1400','Elementary School','39.916323','-75.236929',''),(24,'James Rhoads School','1410','Elementary/Middle School','39.9673896','-75.2200957',''),(25,'Martha Washington School','1420','Elementary/Middle School','39.9660166','-75.2110142',''),(26,'Penrose School','1440','Elementary/Middle School','39.9078325','-75.2477226',''),(27,'Add B. Anderson School','1460','Elementary/Middle School','39.9467347','-75.2455992',''),(28,'Alain Locke School','1470','Elementary/Middle School','39.9621146','-75.2124045',''),(29,'Rudolph Blankenburg School','1490','Elementary/Middle School','39.9730578','-75.213924',''),(30,'Middle Years Alternative School','1580','Middle School','39.9646988','-75.2158736',''),(31,'South Philadelphia High School','2000','High School','39.9237005','-75.1687789',''),(32,'Benjamin Franklin High School','2010','High School','39.9637778','-75.1625551',''),(33,'High School for Creative and Performing Arts School','2020','High School','39.939394','-75.165266',''),(34,'Julia R. Masterman School','2140','Middle/High School','39.963524','-75.1658022',''),(35,'Furness High School','2160','High School','39.9234734','-75.1508151',''),(36,'D. Newlin Fell School','2190','Elementary/Middle School','39.9156572','-75.1628745',''),(37,'Bache-Martin School','2210','Elementary/Middle School','39.9696032','-75.1738614',''),(38,'F. Amedee Bregy School','2240','Elementary/Middle School','39.9145694','-75.1767179',''),(39,'George W. Childs School','2260','Elementary/Middle School','39.9346511','-75.1704893',''),(40,'Franklin Learning Center','2290','High School','39.9650593','-75.1627975',''),(41,'Stephen Girard School','2320','Elementary School','39.9251971','-75.1761389',''),(42,'General George A. McCall School','2340','Elementary/Middle School','39.9446439','-75.153179',''),(43,'Delaplaine McDaniel School','2370','Elementary/Middle School','39.9295827','-75.1817583',''),(44,'William M. Meredith School','2380','Elementary/Middle School','39.9395638','-75.1510549',''),(45,'Robert Morris School','2390','Elementary/Middle School','39.9754594','-75.1787982',''),(46,'Girard Academic Music Program','2410','Middle/High School','39.9217935','-75.1832671',''),(47,'Edwin M. Stanton School','2450','Elementary/Middle School','39.9404918','-75.1712945',''),(48,'Albert M. Greenfield School','2470','Elementary/Middle School','39.952272','-75.177392',''),(49,'Chester A. Arthur School','2480','Elementary/Middle School','39.9419317','-75.1761496',''),(50,'Laura W. Waring School','2490','Elementary/Middle School','39.964919','-75.1677',''),(51,'Andrew Jackson School','2510','Elementary/Middle School','39.9345379','-75.1636475',''),(52,'Abram S. Jenks School','2520','Elementary School','39.9190872','-75.1686558',''),(53,'Francis S. Key School','2540','Elementary School','39.921209','-75.160378',''),(54,'Elizabeth B. Kirkbride School','2580','Elementary/Middle School','39.9299863','-75.1561993',''),(55,'George W. Nebinger School','2590','Elementary/Middle School','39.9363977','-75.1536269',''),(56,'Academy at Palumbo','2620','High School','39.9399626','-75.1611034',''),(57,'George W. Sharswood School','2630','Elementary/Middle School','39.918648','-75.1508851',''),(58,'Southwark School','2640','Elementary/Middle School','39.9258652','-75.16034',''),(59,'Science Leadership Academy','2650','High School','39.9557355','-75.1761326',''),(60,'Constitution High School','2670','High School','39.9502533','-75.1524094',''),(61,'The Science Leadership Academy at Beeber','2680','High School','39.9864857','-75.2422625',''),(62,'John H. Taggart School','2690','Elementary/Middle School','39.9172969','-75.1545411',''),(63,'Vare-Washington School','2720','Elementary/Middle School','39.9334523','-75.1526377',''),(64,'Community Academy of Philadelphia Charter School ','3301','Elementary/Middle/High School','40.0044849','-75.1066667',''),(65,'Harambee Institute of Science and Technology Charter School','3302','Elementary/Middle School','39.9732781','-75.2519642',''),(66,'World Communications Charter School','3303','Middle/High School','39.9438639','-75.1659814',''),(67,'YouthBuild Philadelphia Charter School','3304','High School','39.972906','-75.158633',''),(68,'Christopher Columbus Charter School','3306','Elementary/Middle School','39.9385794','-75.1585754',''),(69,'Eugenio Maria De Hostos Charter School','3307','Elementary/Middle School','40.0465707','-75.1233062',''),(70,'Belmont Academy Charter School','3308','Elementary School','39.970596','-75.2055749',''),(71,'Imhotep Institute Charter High School','3309','High School','40.049239','-75.1546089',''),(72,'Laboratory Charter School of Communication and Languages','3310','Elementary/Middle School','39.9639661','-75.1437838',''),(73,'Multi-Cultural Academy Charter School','3312','High School','40.0114063','-75.1504244',''),(74,'Preparatory Charter School of Mathematics, Science, Technology and Careers','3313','High School','39.9281737','-75.1864707',''),(75,'West Oak Lane Charter School','3314','Elementary/Middle School','40.059427','-75.1636729',''),(76,'Alliance For Progress Charter School','3315','Elementary/Middle School','39.979872','-75.165402',''),(77,'Charter High School for Architecture and Design Charter','3317','High School','39.948852','-75.151997',''),(78,'Freire Charter School','3318','Middle/High School','39.9524459','-75.1744281',''),(79,'Imani Education Circle Charter School','3320','Elementary/Middle School','40.0334667','-75.1761796',''),(80,'Math, Civics and Sciences Charter School','3321','Elementary/Middle/High School','39.9612128','-75.1613416',''),(81,'Philadelphia Academy Charter School','3322','Elementary/Middle/High School','40.1002547','-75.0085989',''),(82,'Hardy Williams Academy Mastery Charter School','3323','Elementary/Middle School','39.9418478','-75.2255159',''),(83,'Universal Institute Charter School','3326','Elementary/Middle School','39.9409778','-75.1677972',''),(84,'Mathematics, Science, and Technology Community Charter School (MaST)','3328','Elementary/Middle/High School','40.1138808','-75.0050153',''),(85,'Young Scholars Charter School','3329','Middle School','39.967827','-75.14824',''),(86,'Franklin Towne Charter High School','3331','High School','40.0018403','-75.0669552',''),(87,'Mariana Bracetti Academy Charter School','3332','Middle/High School','40.0056356','-75.094381',''),(88,'Nueva Esperanza Academy Charter School','3333','Middle/High School','40.0150602','-75.1332475',''),(89,'New Foundations Charter School','3334','Elementary/Middle/High School','40.03489','-75.0244249',''),(90,'People for People Charter School','3335','Elementary/Middle School','39.968101','-75.160675',''),(91,'Philadelphia Performing Arts Charter School','3336','Elementary/Middle/High School','39.9178897','-75.1715104',''),(92,'Global Leadership Academy Charter School','3337','Elementary/Middle School','39.9731765','-75.2155032',''),(93,'Wakisha Charter School','3339','Middle School','39.974144','-75.150741',''),(94,'Walter D. Palmer Leadership Learning Partners Charter School','3340','Elementary/Middle/High School','39.9683579','-75.1472426',''),(95,'Independence Charter School','3341','Elementary/Middle School','39.9449741','-75.1689433',''),(96,'Delaware Valley Charter High School','3342','High School','40.0329607','-75.1446309',''),(97,'Khepera Charter School','3350','Elementary/Middle School','40.001387','-75.145204',''),(98,'West Philadelphia Achievement Charter School','3357','Elementary School','39.971156','-75.253334',''),(99,'Philadelphia Electrical and Technology Charter School','3358','High School','39.9506467','-75.1650802',''),(100,'Richard Allen Preparatory Charter School','3359','Middle School','39.928324','-75.2184847',''),(101,'Russell Byers Charter School','3360','Elementary School','39.955948','-75.1715509',''),(102,'Mastery Charter School at Lenfest Campus','3361','Middle/High School','39.949591','-75.1471597',''),(103,'Wissahickon Charter School','3362','Elementary/Middle School','40.0161021','-75.173072',''),(104,'First Philadelphia Preparatory Charter School','3364','Elementary/Middle School','40.0073237','-75.0820966',''),(105,'Green Woods Charter School','3365','Elementary/Middle School','40.044164','-75.233859',''),(106,'Maritime Academy Charter School (MACHS)','3366','Middle/High School','40.0078954','-75.0677223',''),(107,'Belmont Charter School','3368','Elementary/Middle School','39.9670452','-75.2048472',''),(108,'KIPP Philadelphia Charter School','3370','Elementary/Middle/High School','39.9955072','-75.1720488',''),(109,'Discovery Charter School','3372','Elementary/Middle School','39.977122','-75.214046',''),(110,'Philadelphia Montessori Charter School','3378','Elementary School','39.9165769','-75.2452738',''),(111,'AD Prima Charter School','3379','Elementary/Middle School','39.984322','-75.247676',''),(112,'New Media Technology Charter School','3380','Middle/High School','40.0726209','-75.1714623',''),(113,'Mastery Charter School at Shoemaker Campus','3383','Middle/High School','39.9761178','-75.228287',''),(114,'Folks Arts Culture Treasures Charter School','3384','Elementary/Middle School','39.9591655','-75.1560982',''),(115,'Mastery Charter School at Thomas Campus','3385','Elementary/Middle/High School','39.9146686','-75.1635482',''),(116,'Northwood Academy Charter School','3386','Elementary/Middle School','40.0203963','-75.0947019',''),(117,'Boys Latin of Philadelphia Charter School','3388','Middle/High School','39.9516659','-75.233437',''),(118,'Keystone Academy Charter School','3389','Elementary/Middle School','40.021842','-75.044761',''),(119,'Truebright Science Academy Charter School','3391','Middle/High School','40.0268659','-75.1171584',''),(120,'Southwest Leadership Academy Charter School','3392','Elementary/Middle School','39.9182764','-75.2426255',''),(121,'Mastery Charter School at Pickett Campus','3393','Middle/High School','40.0320294','-75.1804089',''),(122,'Pan American Academy Charter School','3394','Elementary/Middle School','39.994163','-75.1364291',''),(123,'Antonia Pantoja Charter School','3395','Elementary/Middle School','40.0127545','-75.1309',''),(124,'KIPP West Philadelphia Preparatory Charter School','3396','Middle School','39.9445084','-75.2424503',''),(125,'Eastern University Academy Charter School','3397','Middle/High School','40.0118491','-75.1840612',''),(126,'Arise Academy Charter High School','3398','High School','40.0563251','-75.1566938',''),(127,'Sankofa Freedom Academy Charter School','3399','Elementary/Middle/High School','40.0095795','-75.0880312',''),(128,'Franklin Towne Charter Elementary School','3403','Elementary/Middle School','39.9956963','-75.0748541',''),(129,'Tacony Academy Charter School','3404','Elementary/Middle/High School','40.066659','-75.066754',''),(130,'Aspira Charter School at Stetson','3406','Middle School','39.9991033','-75.1254868',''),(131,'Mastery Charter School at Harrity','3407','Elementary/Middle School','39.9484554','-75.235513',''),(132,'Mastery Charter School at Mann','3408','Elementary School','39.9859892','-75.2313348',''),(133,'Mastery Charter School at Smedley','3409','Elementary School','40.0197036','-75.0739783',''),(134,'Universal Charter School at Bluford','3410','Elementary School','39.9747661','-75.2365746',''),(135,'Universal Charter School at Daroff','3411','Elementary/Middle School','39.9656591','-75.2325294',''),(136,'Young Scholars Charter School at Frederick Douglass','3412','Elementary/Middle School','39.983423','-75.1699807',''),(137,'Aspira Charter School at Olney','3414','High School','40.0291547','-75.1227839',''),(138,'Mastery Charter School at Clymer','3415','Elementary/Middle School','39.9957899','-75.150677',''),(139,'Mastery Charter School at Gratz','3416','Middle/High School','40.0147267','-75.1554058',''),(140,'Universal Charter School at Audenried','3417','High School','39.9344709','-75.1992478',''),(141,'Universal Charter School at Vare','3418','Middle School','39.9265004','-75.1863785',''),(142,'Mosaica Charter School at Birney','3419','Elementary/Middle School','40.0294138','-75.138562',''),(143,'Mastery Charter School at Cleveland','3420','Elementary/Middle School','40.0111429','-75.158829',''),(144,'Universal Charter School at Creighton','3421','Elementary/Middle School','40.0348517','-75.1047019',''),(145,'Philadelphia Charter School for the Arts and Sciences','3422','Elementary/Middle School','40.0248627','-75.0865036',''),(146,'Memphis Street Academy Charter School at J.P. Jones','3423','Middle School','39.9860392','-75.1127106',''),(147,'Universal Charter School at Alcorn','3424','Elementary/Middle School','39.9354325','-75.1973063',''),(148,'Young Scholars Charter School at Kenderton','3425','Elementary/Middle School','40.0051793','-75.1541139',''),(149,'Mastery Charter School at Pastorius ','3426','Elementary/Middle School','40.0480514','-75.1615274',''),(150,'Overbrook High School','4020','High School','39.9804787','-75.2385647',''),(151,'High School of Engineering and Science','4030','High School','39.983465','-75.161094',''),(152,'Murrell Dobbins Career and Technical High School','4060','High School','39.9954915','-75.1669198',''),(153,'Dimner Beeber School','4100','Middle School','39.9864857','-75.2422625',''),(154,'Strawberry Mansion High School','4140','High School','39.990696','-75.184018',''),(155,'James G. Blaine School','4220','Elementary/Middle School','39.9851965','-75.1829393',''),(156,'Lewis C. Cassidy Academics Plus School','4240','Elementary School','39.975518','-75.250774',''),(157,'William Dick School','4270','Elementary/Middle School','39.9870096','-75.1743696',''),(158,'Samuel Gompers School','4280','Elementary School','39.991978','-75.2375431',''),(159,'Edward Heston School','4300','Elementary/Middle School','39.97784','-75.2295562',''),(160,'Robert E. Lamberton School','4320','Elementary/Middle School','39.9756565','-75.2656067',''),(161,'E. Washington Rhodes School','4350','Elementary/Middle School','40.002756','-75.178129',''),(162,'Overbrook School','4370','Elementary School','39.9819002','-75.245613',''),(163,'Thomas M. Peirce School','4380','Elementary School','39.9988929','-75.1685471',''),(164,'Dr. Ethel Allen School','4440','Elementary/Middle School','39.9972949','-75.1836513',''),(165,'Tanner G. Duckrey School','4460','Elementary/Middle School','39.985201','-75.1583',''),(166,'Richard R. Wright School','4470','Elementary School','39.9904386','-75.1769988',''),(167,'Overbrook Educational Center','4480','Elementary/Middle School','39.9738659','-75.2547861',''),(168,'Edward Gideon School','4530','Elementary/Middle School','39.9844403','-75.1801168',''),(169,'William D. Kelley School','4560','Elementary/Middle School','39.980392','-75.1803259',''),(170,'General George G. Meade School','4570','Elementary/Middle School','39.9781115','-75.1645951',''),(171,'Thomas A. Edison High School','5020','High School','40.0118745','-75.1295072',''),(172,'Philadelphia Military Academy','5050','High School','39.985854','-75.1543462',''),(173,'Jules E. Mastbaum Area Vocational Technical High School','5060','High School','39.9923307','-75.1121627',''),(174,'Parkway Northwest High School','5070','High School','40.0711822','-75.1750414',''),(175,'Parkway Center City High School','5080','High School','39.9630133','-75.1593558',''),(176,'Parkway West High School','5090','High School','39.9646988','-75.2158736',''),(177,'William W. Bodine High School','5150','High School','39.9680973','-75.1432012',''),(178,'Penn Treaty High School','5160','Middle/High School','39.9728143','-75.1278609',''),(179,'Julia de Burgos School','5170','Elementary/Middle School','39.992752','-75.138628',''),(180,'Alexander Adaire School','5200','Elementary/Middle School','39.9724922','-75.1303155',''),(181,'Henry A. Brown School','5210','Elementary/Middle School','39.9866409','-75.127404',''),(182,'Russell H. Conwell School','5230','Middle School','39.9944062','-75.1149065',''),(183,'Paul L. Dunbar School','5250','Elementary/Middle School','39.979488','-75.154169',''),(184,'Lewis Elkin School','5260','Elementary School','39.9974897','-75.1212464',''),(185,'Horatio B. Hackett School','5300','Elementary School','39.980964','-75.126855',''),(186,'John F. Hartranft School','5320','Elementary/Middle School','39.9895233','-75.1453736',''),(187,'William H. Hunter School','5330','Elementary/Middle School','39.9869832','-75.1322199',''),(188,'James R. Ludlow School','5340','Elementary/Middle School','39.9728096','-75.1459471',''),(189,'William McKinley School','5350','Elementary/Middle School','39.9827961','-75.1417233',''),(190,'John Moffet School','5370','Elementary School','39.9748689','-75.1360389',''),(191,'Potter-Thomas School','5390','Elementary/Middle School','39.9971363','-75.140626',''),(192,'Richmond School','5400','Elementary School','39.9829518','-75.1107482',''),(193,'Isaac A. Sheppard School','5410','Elementary School','39.9938737','-75.1316625',''),(194,'John Welsh School','5420','Elementary/Middle School','39.986472','-75.139335',''),(195,'Alternative Middle Years at James Martin ','5430','Middle School','39.9856413','-75.0972088',''),(196,'Frances E. Willard School','5440','Elementary School','39.9930952','-75.1158183',''),(197,'William Cramp School','5470','Elementary School','40.0027967','-75.130027',''),(198,'General Philip Kearny School','5480','Elementary/Middle School','39.963871','-75.148157',''),(199,'Cayuga School','5490','Elementary School','40.0176182','-75.1346429',''),(200,'Thurgood Marshall School','5500','Elementary/Middle School','40.0300982','-75.1339369',''),(201,'Kensington International Business, Finance, and Entrepreneurship High School','5510','High School','39.9846114','-75.1269783',''),(202,'Kensington High School for Creative and Performing Arts','5520','High School','39.9778139','-75.1333669',''),(203,'Philip H. Sheridan School','5530','Elementary School','39.9995735','-75.1142465',''),(204,'Kensington Health Sciences Academy','5550','High School','39.9845608','-75.1283399',''),(205,'Spring Garden School','5560','Elementary/Middle School','39.9652504','-75.1561842',''),(206,'John H. Webster School','5590','Elementary School','39.995798','-75.105499',''),(207,'Kensington Urban Education Academy','5600','High School','39.9844221','-75.126538',''),(208,'Building 21','5610','High School','39.9818142','-75.1460377',''),(209,'The U School','5620','High School','39.9818142','-75.1460377',''),(210,'the LINC','5660','High School','40.0065997','-75.1301408',''),(211,'Honorable Luis Munoz-Marin School','5680','Elementary/Middle School','40.0013','-75.135716',''),(212,'Central High School','6010','High School','40.037386','-75.150775',''),(213,'Roxborough High School','6030','High School','40.0373374','-75.2232944',''),(214,'Walter B. Saul High School','6040','High School','40.0494237','-75.2215937',''),(215,'Philadelphia High School for Girls','6050','High School','40.0383435','-75.1460441',''),(216,'Martin Luther King High School','6060','High School','40.0564995','-75.1612929',''),(217,'A. Philip Randolph Career and Technical High School','6090','High School','40.0089007','-75.1794194',''),(218,'Morris E. Leeds School','6100','Middle School','40.0711822','-75.1750414',''),(219,'Anna B. Day School','6200','Elementary/Middle School','40.0586988','-75.1681957',''),(220,'Franklin S. Edmonds School','6210','Elementary School','40.0719828','-75.1698531',''),(221,'Eleanor C. Emlen School','6220','Elementary School','40.054362','-75.177908',''),(222,'Fitler Academics Plus School','6230','Elementary/Middle School','40.026101','-75.166338',''),(223,'Charles W. Henry School','6250','Elementary/Middle School','40.046258','-75.196588',''),(224,'Henry H. Houston School','6260','Elementary/Middle School','40.0584963','-75.1949146',''),(225,'John Story Jenks Academy for Arts and Sciences','6270','Elementary/Middle School','40.0741971','-75.2034245',''),(226,'James Logan School','6300','Elementary School','40.0308909','-75.152143',''),(227,'John F. McCloskey School','6310','Elementary/Middle School','40.081626','-75.175336',''),(228,'Thomas Mifflin School','6320','Elementary/Middle School','40.0135974','-75.1917675',''),(229,'Joseph Pennell School','6340','Elementary School','40.0434674','-75.150624',''),(230,'Samuel Pennypacker School','6350','Elementary School','40.065366','-75.159037',''),(231,'Theodore Roosevelt School','6360','Elementary/Middle School','40.0480661','-75.1746747',''),(232,'Shawmont School','6380','Elementary/Middle School','40.0519128','-75.2388383',''),(233,'Edward T. Steel School','6390','Elementary/Middle School','40.0188694','-75.1569419',''),(234,'Widener Memorial School','6400','Elementary/Middle/High School','40.0390529','-75.146019',''),(235,'Cook-Wissahickon School','6410','Elementary/Middle School','40.022433','-75.2067728',''),(236,'John Wister School','6430','Elementary School','40.032882','-75.166721',''),(237,'Anna L. Lingelbach School','6440','Elementary/Middle School','40.037076','-75.189645',''),(238,'James Dobson School','6450','Elementary/Middle School','40.0314639','-75.2306859',''),(239,'Hill-Freedman World Academy','6460','Middle/High School','40.056368','-75.1653558',''),(240,'John B. Kelly School','6470','Elementary School','40.0250236','-75.1721419',''),(241,'Academy for the Middle Years at Northwest','6480','Middle School','40.0309227','-75.2128664',''),(242,'Lankenau High School','6540','High School','40.062276','-75.2535149',''),(243,'Frankford High School','7010','High School','40.0215538','-75.0852713',''),(244,'Jay Cooke School','7100','Elementary/Middle School','40.0258613','-75.1454038',''),(245,'Warren G. Harding School','7110','Middle School','40.0129995','-75.0746132',''),(246,'Samuel Fels High School','7120','High School','40.036518','-75.092646',''),(247,'General Louis Wagner School','7130','Middle School','40.051748','-75.147679',''),(248,'Juniata Park Academy','7150','Elementary/Middle School','40.0123682','-75.1115682',''),(249,'Clara Barton School','7200','Elementary School','40.0193276','-75.1194313',''),(250,'Laura H. Carnell School','7220','Elementary School','40.0395118','-75.0843543',''),(251,'Ellwood School','7260','Elementary School','40.0547479','-75.1390301',''),(252,'Thomas K. Finletter School','7270','Elementary/Middle School','40.0434108','-75.1189186',''),(253,'Benjamin Franklin School','7280','Elementary/Middle School','40.0421108','-75.1049505',''),(254,'Allen M. Stearne School','7290','Elementary/Middle School','40.0119926','-75.0862388',''),(255,'Francis Hopkinson School','7300','Elementary/Middle School','40.0086619','-75.1024204',''),(256,'Feltonville Intermediate School','7310','Elementary School','40.020294','-75.120991',''),(257,'Julia W. Howe School','7320','Elementary School','40.0411289','-75.1420425',''),(258,'Henry W. Lawton School','7330','Elementary School','40.020608','-75.0589049',''),(259,'James R. Lowell School','7350','Elementary School','40.0409358','-75.1287206',''),(260,'John Marshall School','7360','Elementary School','40.0143735','-75.0872146',''),(261,'Grover Washington, Jr. School','7370','Middle School','40.0354768','-75.1179906',''),(262,'Alexander K. McClure School','7380','Elementary School','40.0151685','-75.1373254',''),(263,'Andrew J. Morrison School','7390','Elementary/Middle School','40.0294746','-75.1295373',''),(264,'Olney School','7400','Elementary/Middle School','40.0306341','-75.1214068',''),(265,'James J. Sullivan School','7430','Elementary School','40.015816','-75.066976',''),(266,'Bayard Taylor School','7440','Elementary School','40.0074635','-75.1378952',''),(267,'William H. Ziegler School','7460','Elementary/Middle School','40.0299647','-75.0758741',''),(268,'Bridesburg School','7470','Elementary/Middle School','40.0004836','-75.0666662',''),(269,'Prince Hall School','7490','Elementary School','40.047703','-75.149578',''),(270,'Feltonville School of Arts and Sciences','7500','Middle School','40.0193879','-75.1216447',''),(271,'Mary M. Bethune School','7510','Elementary/Middle School','40.0029613','-75.1488598',''),(272,'William Rowen School','7530','Elementary School','40.0587481','-75.1481521',''),(273,'Roberto Clemente School','7730','Middle School','40.0065997','-75.1301408',''),(274,'Abraham Lincoln High School','8010','High School','40.0433316','-75.042611',''),(275,'Northeast High School','8020','High School','40.055677','-75.071019',''),(276,'George Washington High School','8030','High School','40.1063806','-75.0261696',''),(277,'Arts Academy at Benjamin Rush','8040','High School','40.0827867','-74.9742007',''),(278,'Swenson Arts and Technology High School','8090','High School','40.0921633','-75.0140277',''),(279,'Woodrow Wilson School','8120','Middle School','40.0523954','-75.0690874',''),(280,'Austin Meehan School','8140','Middle School','40.0457386','-75.0441526',''),(281,'Baldi School','8160','Middle School','40.09202','-75.0527501',''),(282,'John Hancock Demonstration School','8180','Elementary School','40.0677667','-74.9883622',''),(283,'Ethan Allen School ','8200','Elementary/Middle School','40.0298374','-75.0624353',''),(284,'Joseph H. Brown School','8210','Elementary School','40.0439712','-75.0245549',''),(285,'Kennedy C. Crossan School','8230','Elementary School','40.062021','-75.0810956',''),(286,'Hamilton Disston School','8240','Elementary/Middle School','40.028364','-75.0465959',''),(287,'Edwin Forrest School','8250','Elementary School','40.0333235','-75.037756',''),(288,'Fox Chase School','8260','Elementary School','40.075746','-75.082379',''),(289,'Thomas Holme School','8270','Elementary School','40.0533249','-75.0085367',''),(290,'Mayfair School','8300','Elementary/Middle School','40.0394927','-75.0517189',''),(291,'J. Hampton Moore School','8310','Elementary School','40.0495743','-75.0771307',''),(292,'General J. Harry LaBrum School','8320','Middle School','40.0807971','-74.987711',''),(293,'Solomon Solis-Cohen School','8340','Elementary School','40.0447447','-75.0665305',''),(294,'Gilbert Spruance School','8350','Elementary/Middle School','40.0375713','-75.0730765',''),(295,'Rhawnhurst School','8360','Elementary School','40.0581492','-75.0594516',''),(296,'Watson Comly School','8370','Elementary School','40.120962','-75.009109',''),(297,'Louis H. Farrell School','8380','Elementary/Middle School','40.066538','-75.052444',''),(298,'A.L. Fitzpatrick School','8390','Elementary/Middle School','40.080311','-74.9764502',''),(299,'Anne Frank School','8400','Elementary School','40.0884054','-75.0283731',''),(300,'Robert B. Pollock School','8410','Elementary School','40.059787','-75.028322',''),(301,'Stephen Decatur School','8420','Elementary/Middle School','40.0956378','-74.9710861',''),(302,'Joseph Greenberg School','8430','Elementary/Middle School','40.0960206','-75.0573837',''),(303,'William H. Loesche School','8440','Elementary School','40.1135263','-75.0238968',''),(304,'The Workshop School','8560','High School','39.955306','-75.218553',''),(305,'Philadelphia Virtual Academy','8780','Middle/High School','39.961007','-75.162646','');
/*!40000 ALTER TABLE `school` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topic`
--

DROP TABLE IF EXISTS `topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topic`
--

LOCK TABLES `topic` WRITE;
/*!40000 ALTER TABLE `topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topics_activities`
--

DROP TABLE IF EXISTS `topics_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topics_activities` (
  `topic_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  PRIMARY KEY (`topic_id`,`activity_id`),
  KEY `IDX_10FF539C1F55203D` (`topic_id`),
  KEY `IDX_10FF539C81C06096` (`activity_id`),
  CONSTRAINT `FK_10FF539C1F55203D` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_10FF539C81C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topics_activities`
--

LOCK TABLES `topics_activities` WRITE;
/*!40000 ALTER TABLE `topics_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `topics_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `year`
--

DROP TABLE IF EXISTS `year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `isCurrentYear` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `year`
--

LOCK TABLES `year` WRITE;
/*!40000 ALTER TABLE `year` DISABLE KEYS */;
INSERT INTO `year` VALUES (1,'2015-2016',1);
/*!40000 ALTER TABLE `year` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `years_activities`
--

DROP TABLE IF EXISTS `years_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `years_activities` (
  `year_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  PRIMARY KEY (`year_id`,`activity_id`),
  KEY `IDX_BA8C171F40C1FEA7` (`year_id`),
  KEY `IDX_BA8C171F81C06096` (`activity_id`),
  CONSTRAINT `FK_BA8C171F40C1FEA7` FOREIGN KEY (`year_id`) REFERENCES `year` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_BA8C171F81C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `years_activities`
--

LOCK TABLES `years_activities` WRITE;
/*!40000 ALTER TABLE `years_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `years_activities` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-08-06  8:30:42
