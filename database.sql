-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: localhost    Database: research_app
-- ------------------------------------------------------
-- Server version	8.0.44-0ubuntu0.24.04.1

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
-- Table structure for table `participants`
--

DROP TABLE IF EXISTS `participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `university` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participants`
--

LOCK TABLES `participants` WRITE;
/*!40000 ALTER TABLE `participants` DISABLE KEYS */;
INSERT INTO `participants` VALUES (1,'irfan','irfan@gmail.com','123456789','Unknown University','Unknown Designation','2025-11-28 05:47:23'),(2,'ahmad','ahmad@gmail.com','03334545678','Unknown University','Unknown Designation','2025-11-28 05:58:26'),(3,'ahmad','ahmad@gmail.com','033345678','Unknown University','Unknown Designation','2025-11-28 06:08:36'),(4,'khan','khan@gmail.com','1234567678','Unknown University','Unknown Designation','2025-11-28 06:10:02'),(5,'khan','khan@gmail.com','1234567678','Unknown University','Unknown Designation','2025-11-28 06:11:35'),(6,'khan','khan@gmail.com','1234567678','Unknown University','Unknown Designation','2025-11-28 06:13:41'),(7,'khan','khan@gmail.com','123456789','Unknown University','Unknown Designation','2025-11-28 06:30:16'),(8,'jan','jani@example.com','12345577','Unknown University','Unknown Designation','2025-11-28 06:44:11'),(9,'gul','gul@gmail.com','03303434345','Unknown University','Unknown Designation','2025-12-01 07:23:03'),(10,'abdul','abdul@gmail.com','03303030303','Unknown University','Unknown Designation','2025-12-01 07:39:32'),(11,'muhammed erfan','erfan@gmail.com','0323020203','Unknown University','Unknown Designation','2025-12-02 07:50:30');
/*!40000 ALTER TABLE `participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `group` varchar(255) DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,'Sustainable Human Resource Management (SHRM)','Ability-enhancing SHRM','SHRM1','Developing ethical brochures and other materials used to attract job applicants.'),(2,'Sustainable Human Resource Management (SHRM)','Ability-enhancing SHRM','SHRM2','Attracting and selecting employees who demonstrate responsible values or behavior.'),(3,'Sustainable Human Resource Management (SHRM)','Ability-enhancing SHRM','SHRM3','Hiring employees who exhibit relatively high levels of moral development.'),(4,'Sustainable Human Resource Management (SHRM)','Ability-enhancing SHRM','SHRM4','Induction programs that emphasize responsible and sustainable values (e.g., dignity, solidarity, and reciprocity).'),(5,'Sustainable Human Resource Management (SHRM)','Ability-enhancing SHRM','SHRM5','Organization-wide training to develop responsible and sustainable behavior.'),(6,'Sustainable Human Resource Management (SHRM)','Ability-enhancing SHRM','SHRM6','Presence of ethical leadership programs and extensive training on ethical and sustainability issues.'),(7,'Sustainable Human Resource Management (SHRM)','Ability-enhancing SHRM','SHRM7','Creating cognitive conflict to stimulate independent decisions in ethically ambiguous situations.'),(8,'Sustainable Human Resource Management (SHRM)','Ability-enhancing SHRM','SHRM8','Developing employee skills in engaging and communicating with multiple stakeholders (e.g., customers, suppliers, government, community, and the public, media).'),(9,'Sustainable Human Resource Management (SHRM)','Motivation-enhancing SHRM','SHRM9','Developing performance goals that focus on means as well as ends, using not only outcome-based but also behavior-based performance evaluations.'),(10,'Sustainable Human Resource Management (SHRM)','Motivation-enhancing SHRM','SHRM10','Linking bonuses and variable pay to ethical, responsible, and sustainable behaviors based on social performance objectives.'),(11,'Sustainable Human Resource Management (SHRM)','Motivation-enhancing SHRM','SHRM11','Promoting awards for good citizenship and moral behavior.'),(12,'Sustainable Human Resource Management (SHRM)','Motivation-enhancing SHRM','SHRM12','Sanctions for managers and employees who breach the organization\'s sustainability standards.'),(13,'Sustainable Human Resource Management (SHRM)','Opportunity-enhancing SHRM','SHRM13','Job design encourages employees to take ethics related decisions.'),(14,'Sustainable Human Resource Management (SHRM)','Opportunity-enhancing SHRM','SHRM14','Presence of employee volunteer programs and/or charitable giving opportunities.'),(15,'Sustainable Human Resource Management (SHRM)','Opportunity-enhancing SHRM','SHRM15','Encouraging members to provide solutions when the organization faces ethical problems.'),(16,'Sustainable Human Resource Management (SHRM)','Opportunity-enhancing SHRM','SHRM16','Involving employee representatives and unions in the design, application, and review of the ethical infrastructure of the company.'),(17,'Sustainable Human Resource Management (SHRM)','Opportunity-enhancing SHRM','SHRM17','The career mechanism is fair, visible to all, and linked to organizational ethical and sustainability standards.'),(18,'Sustainable Human Resource Management (SHRM)','Opportunity-enhancing SHRM','SHRM18','Employee surveys in place to monitor the ethical climate of the organization.'),(19,'Sustainable Human Resource Management (SHRM)','Opportunity-enhancing SHRM','SHRM19','Encouraging the reporting of unethical behavior and supporting whistleblowing on ethical issues.'),(20,'Sustainable Human Resource Management (SHRM)','Opportunity-enhancing SHRM','SHRM20','Presence of policies to increase diversity and equity.'),(21,'Sustainable Human Resource Management (SHRM)','Opportunity-enhancing SHRM','SHRM21','Presence of policies to promote flexible working/work–life balance.'),(22,'Sustainable Human Resource Management (SHRM)','Opportunity-enhancing SHRM','SHRM22','Presence of policies to improve employee well-being.'),(23,'Sustainable Human Resource Management (SHRM)','Sustainable Human Resource Management (General)','SHRM23','Our HR policies promote the long-term development of employees.'),(24,'Sustainable Human Resource Management (SHRM)','Sustainable Human Resource Management (General)','SHRM24','The organization considers employee well-being a top priority.'),(25,'Sustainable Human Resource Management (SHRM)','Sustainable Human Resource Management (General)','SHRM25','We promote diversity and inclusion across all levels of the organization.'),(26,'Sustainable Human Resource Management (SHRM)','Sustainable Human Resource Management (General)','SHRM26','Our HR practices support work-life balance.'),(27,'Sustainable Human Resource Management (SHRM)','Sustainable Human Resource Management (General)','SHRM27','Employees are encouraged to participate in decisions affecting their work.'),(28,'Sustainable Human Resource Management (SHRM)','Sustainable Human Resource Management (General)','SHRM28','HR policies take into account environmental and social sustainability.'),(29,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM29','Our HR policies promote employee well-being and work-life balance.'),(30,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM30','The organisation invests in continuous learning and employee development.'),(31,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM31','Ethical standards are embedded in our recruitment and management practices.'),(32,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM32','We prioritize long-term employee retention over short-term gains.'),(33,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM33','The HR function supports environmental and social sustainability goals.'),(34,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM34','Employees are encouraged to participate in decision-making processes.'),(35,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM35','HR policies prioritise wellbeing/workload balance.'),(36,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM36','Continuous professional development supported.'),(37,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM37','Recruitment & promotion emphasize sustainability/diversity.'),(38,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM38','Flexible/emergency leave policies exist.'),(39,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM39','Green HR/environmental practices included.'),(40,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM40','Early-career faculty mentoring supported.'),(41,'Sustainable Human Resource Management (SHRM)','Long-term & Sustainability-focused HR Strategies','SHRM41','HR policies ensure fairness & inclusion.'),(42,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','General CDC & Reciprocity','CDC 1','We sense new opportunities before competitors.'),(43,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','General CDC & Reciprocity','CDC 2','Our institution responds quickly to external changes.'),(44,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','General CDC & Reciprocity','CDC 3','We are able to realign resources to meet changing needs.'),(45,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','General CDC & Reciprocity','CDC 4','Innovation is encouraged across departments.'),(46,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','General CDC & Reciprocity','CDC 5','I am willing to go beyond my job responsibilities to support my institution.'),(47,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','General CDC & Reciprocity','CDC 6','I feel a strong obligation to reciprocate the institution\'s support.'),(48,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','General CDC & Reciprocity','CDC 7','When treated fairly, I contribute extra effort to change initiatives.'),(49,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','General CDC & Reciprocity','CDC 8','I support organisational change when the institution invests in staff well-being.'),(50,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','Sensing','CDC_S1','University monitors external changes.'),(51,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','Sensing','CDC_S2','Senior management gets timely info.'),(52,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','Sensing','CDC_S3','Mechanisms for scanning student/labour trends.'),(53,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','Seizing','CDC_Z1','University reallocates resources quickly.'),(54,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','Seizing','CDC_Z2','Rapid response teams authorised.'),(55,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','Seizing','CDC_Z3','Fast approval for operational changes.'),(56,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','Reconfiguring','CDC_R1','University reconfigures teaching/research/admin processes.'),(57,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','Reconfiguring','CDC_R2','Cross-functional crisis teams created.'),(58,'Changing Dynamic Capabilities (CDC) & Employee Reciprocity','Reconfiguring','CDC_R3','Staff/workloads reassigned flexibly.'),(59,'Human Resource Analytics (HRA)','Strategic Alignment of HR Analytics','HRA1.1','HR analytics aligns with the institution\'s strategic objectives.'),(60,'Human Resource Analytics (HRA)','Strategic Alignment of HR Analytics','HRA1.2','HR metrics are integrated into institutional performance evaluation.'),(61,'Human Resource Analytics (HRA)','Strategic Alignment of HR Analytics','HRA1.3','HR data is used to inform long-term planning and policy-making.'),(62,'Human Resource Analytics (HRA)','Data-Driven Decision Making','HRA2.1','HR decisions are supported by evidence from data analysis.'),(63,'Human Resource Analytics (HRA)','Data-Driven Decision Making','HRA2.2','HR professionals regularly use analytics reports to guide recommendations.'),(64,'Human Resource Analytics (HRA)','Data-Driven Decision Making','HRA2.3','Predictive analytics are used to forecast staffing and performance trends.'),(65,'Human Resource Analytics (HRA)','Analytical Competence of HR Professionals','HRA3.1','Our HR team has strong data analysis skills.'),(66,'Human Resource Analytics (HRA)','Analytical Competence of HR Professionals','HRA3.2','We invest in training HR staff to improve their analytical capabilities.'),(67,'Human Resource Analytics (HRA)','Analytical Competence of HR Professionals','HRA3.3','HR professionals are confident in using statistical tools and HRIS.'),(68,'Human Resource Analytics (HRA)','Integration with Other Systems','HRA4.1','HR analytics systems are integrated with other institutional data systems.'),(69,'Human Resource Analytics (HRA)','Integration with Other Systems','HRA4.2','HR analytics outputs are shared across departments for decision-making.'),(70,'Human Resource Analytics (HRA)','Integration with Other Systems','HRA4.3','HR dashboards include multi-departmental KPIs.'),(71,'Human Resource Analytics (HRA)','Perceived Effectiveness of HRA','HRA5.1','HR analytics has contributed to better workforce planning.'),(72,'Human Resource Analytics (HRA)','Perceived Effectiveness of HRA','HRA5.2','The use of HR data has led to improved employee outcomes.'),(73,'Human Resource Analytics (HRA)','Perceived Effectiveness of HRA','HRA5.3','HR analytics adds value to institutional performance.'),(74,'Human Resource Analytics (HRA)','Perceived Effectiveness of HRA','HRA5.4','We use data analytics in making HR decisions.'),(75,'Human Resource Analytics (HRA)','Perceived Effectiveness of HRA','HRA5.5','HR analytics help us align HR practices with strategic goals.'),(76,'Human Resource Analytics (HRA)','Perceived Effectiveness of HRA','HRA5.6','Workforce planning is informed by analytics insights.'),(77,'Human Resource Analytics (HRA)','Perceived Effectiveness of HRA','HRA5.7','Our HR department has the skills to apply analytics tools.'),(78,'HR Competencies (HRC)','HR Competencies','HRC1','HR staff skilled in workforce planning & strategy.'),(79,'HR Competencies (HRC)','HR Competencies','HRC2','HR staff competent in data analysis.'),(80,'HR Competencies (HRC)','HR Competencies','HRC3','HR leaders influence strategic decisions.'),(81,'HR Competencies (HRC)','HR Competencies','HRC4','HR staff effective in change management.'),(82,'HR Competencies (HRC)','HR Competencies','HRC5','HR trained in sustainable HR practices.'),(83,'HR Competencies (HRC)','HR Competencies','HRC6','HR involved in crisis-response planning.'),(84,'Knowledge Management (KM)','General/Tacit Networks','KM1','Team members anticipate each other\'s needs without direct communication.'),(85,'Knowledge Management (KM)','General/Tacit Networks','KM2','Knowledge is often shared informally through personal networks.'),(86,'Knowledge Management (KM)','General/Tacit Networks','KM3','Employees understand each other\'s roles and responsibilities well.'),(87,'Knowledge Management (KM)','General/Tacit Networks','KM4','We rely heavily on shared experience and intuition to coordinate tasks.'),(88,'Knowledge Management (KM)','General/Tacit Networks','KM5','Knowledge is transferred through observation and social interaction.'),(89,'Knowledge Management (KM)','General/Tacit Networks','KM6','Our institution effectively captures knowledge from internal sources.'),(90,'Knowledge Management (KM)','General/Tacit Networks','KM7','There are systems to acquire knowledge from external stakeholders.'),(91,'Knowledge Management (KM)','General/Tacit Networks','KM8','Employees are encouraged to share knowledge with colleagues.'),(92,'Knowledge Management (KM)','General/Tacit Networks','KM9','Critical knowledge is stored in an accessible format.'),(93,'Knowledge Management (KM)','General/Tacit Networks','KM10','We use knowledge effectively to improve services.'),(94,'Knowledge Management (KM)','Explicit Coordination','KM11','Our organisation maintains clear documentation for key operational processes.'),(95,'Knowledge Management (KM)','Explicit Coordination','KM12','Knowledge is routinely stored and shared through formal IT systems.'),(96,'Knowledge Management (KM)','Explicit Coordination','KM13','Employees follow standard procedures for capturing and codifying knowledge.'),(97,'Knowledge Management (KM)','Explicit Coordination','KM14','There are structured routines for knowledge transfer across departments.'),(98,'Knowledge Management (KM)','Explicit Coordination','KM15','Policies exist for updating and distributing critical knowledge.'),(99,'Knowledge Management (KM)','Implicit Coordination','KM16','Team members anticipate each other\'s needs without direct communication.'),(100,'Knowledge Management (KM)','Implicit Coordination','KM17','Knowledge is often shared informally through personal networks.'),(101,'Knowledge Management (KM)','Implicit Coordination','KM18','Employees understand each other\'s roles and responsibilities well.'),(102,'Knowledge Management (KM)','Implicit Coordination','KM19','We rely heavily on shared experience and intuition to coordinate tasks.'),(103,'Knowledge Management (KM)','Implicit Coordination','KM20','Knowledge is transferred through observation and social interaction.'),(104,'Knowledge Management (KM)','Knowledge Creation','KM21','Our organization continuously acquires new knowledge relevant to our industry.'),(105,'Knowledge Management (KM)','Knowledge Creation','KM22','Employees frequently share knowledge with one another.'),(106,'Knowledge Management (KM)','Knowledge Creation','KM23','We effectively convert individual knowledge into organizational processes.'),(107,'Knowledge Management (KM)','Knowledge Creation','KM24','We apply existing knowledge to improve performance.'),(108,'Knowledge Management (KM)','Knowledge Creation','KM25','New ideas and innovations are encouraged and supported.'),(109,'Knowledge Management (KM)','Knowledge Creation','KM26','There are formal mechanisms (e.g., databases, training) for knowledge sharing.'),(110,'Knowledge Management (KM)','General KM & ICT Support','KM27','University captures lessons from past disruptions.'),(111,'Knowledge Management (KM)','General KM & ICT Support','KM28','Institutional repositories are updated.'),(112,'Knowledge Management (KM)','General KM & ICT Support','KM29','Staff routinely share knowledge across departments.'),(113,'Knowledge Management (KM)','General KM & ICT Support','KM30','Core processes documented & accessible.'),(114,'Knowledge Management (KM)','General KM & ICT Support','KM31','Incentives for knowledge sharing exist.'),(115,'Knowledge Management (KM)','General KM & ICT Support','KM32','ICT systems support knowledge storage/retrieval.'),(116,'Knowledge Management (KM)','General KM & ICT Support','KM33','Knowledge from past disruptions is applied.'),(117,'Organisational Resilience (OR)','Organisational Resilience','OR1','Our organisation can quickly adapt to unexpected changes in the environment.'),(118,'Organisational Resilience (OR)','Organisational Resilience','OR2','We proactively plan for future disruptions.'),(119,'Organisational Resilience (OR)','Organisational Resilience','OR3','Employees are empowered to respond flexibly in times of crisis.'),(120,'Organisational Resilience (OR)','Organisational Resilience','OR4','Lessons learned from past challenges are integrated into future planning.'),(121,'Organisational Resilience (OR)','Organisational Resilience','OR5','Our organisation maintains strong networks and partnerships that enhance resilience.'),(122,'Organisational Resilience (OR)','Organisational Resilience','OR6','Employees remain productive during crisis.'),(123,'Organisational Resilience (OR)','Organisational Resilience','OR7','We continuously improve after facing challenges.'),(124,'Organisational Resilience (OR)','Organisational Resilience','OR8','University maintains critical operations during disruption.'),(125,'Organisational Resilience (OR)','Organisational Resilience','OR9','University recovers quickly after crises.'),(126,'Organisational Resilience (OR)','Organisational Resilience','OR10','Staff/students adapt quickly to delivery changes.'),(127,'Organisational Resilience (OR)','Organisational Resilience','OR12','Contingency plans exist & are communicated.'),(128,'Organisational Resilience (OR)','Organisational Resilience','OR13','University learns from past disruptions.'),(129,'Organisational Resilience (OR)','Organisational Resilience','OR14','Stakeholders trust university\'s crisis management.'),(130,'Organisational Resilience (OR)','Organisational Resilience','OR15','University maintains research & quality during crises.'),(131,'Organisational Resilience (OR)','Organisational Resilience','OR16','University shows agility under shocks.');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `responses`
--

DROP TABLE IF EXISTS `responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `responses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` int NOT NULL,
  `question_id` varchar(50) NOT NULL,
  `score` tinyint DEFAULT NULL,
  `weight` decimal(3,1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_response` (`session_id`,`question_id`),
  CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `survey_sessions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=684 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `responses`
--

LOCK TABLES `responses` WRITE;
/*!40000 ALTER TABLE `responses` DISABLE KEYS */;
/*!40000 ALTER TABLE `responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey_sessions`
--

DROP TABLE IF EXISTS `survey_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `survey_sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `participant_id` int NOT NULL,
  `is_completed` tinyint(1) DEFAULT '0',
  `current_module` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `participant_id` (`participant_id`),
  CONSTRAINT `survey_sessions_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey_sessions`
--

LOCK TABLES `survey_sessions` WRITE;
/*!40000 ALTER TABLE `survey_sessions` DISABLE KEYS */;
INSERT INTO `survey_sessions` VALUES (1,1,1,6,'2025-11-28 05:47:23'),(2,2,1,6,'2025-11-28 05:58:26'),(3,3,0,1,'2025-11-28 06:08:36'),(4,4,0,1,'2025-11-28 06:10:02'),(5,5,0,1,'2025-11-28 06:11:36'),(6,6,0,1,'2025-11-28 06:13:41'),(7,7,1,6,'2025-11-28 06:30:16'),(8,8,1,6,'2025-11-28 06:44:11'),(9,9,1,6,'2025-12-01 07:23:03'),(10,10,0,1,'2025-12-01 07:39:32'),(11,11,0,1,'2025-12-02 07:50:30');
/*!40000 ALTER TABLE `survey_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'irfan','irfan@example.com','$2y$10$iqfxU1/4wZGOTHQmnLHQe.eubLi76uGWai259lMGb/7rThpNg6RFO','2025-11-26 09:42:54',0),(2,'khan','khan@email.com','$2y$10$AJK0rp9Oo/82QDdpddmaVe4VhhPJKKpTd8S39/G.8Cp94n0pBidB6','2025-11-27 10:43:08',1);
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

-- Dump completed on 2025-12-11 19:30:00
