/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: mercator
-- ------------------------------------------------------
-- Server version	10.11.11-MariaDB-0+deb12u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Activity 1','<p> Description of activity 1 </p>','2020-06-10 13:20:42','2020-06-22 06:12:06','2020-06-22 06:12:06'),
(2,'Activity 2','<p> Description of the test activity </p>','2020-06-10 15:44:26','2020-06-22 06:12:06','2020-06-22 06:12:06'),
(3,'Activity 3','<p> Description of activity 3 </p>','2020-06-13 04:57:08','2020-06-22 06:12:06','2020-06-22 06:12:06'),
(4,'Activity 4','<p> Description of Acitivity 4 </p>','2020-06-13 04:57:24','2020-06-22 06:12:06','2020-06-22 06:12:06'),
(5,'Helpdesk','<p> User support </p>','2020-08-13 05:49:05','2020-08-13 05:49:05',NULL),
(6,'Development','<p> Application development </p>','2020-08-13 05:49:47','2020-08-13 05:49:47',NULL),
(7,'Computer monitoring','<p> Check the proper functioning of computer equipment </p>','2020-08-13 05:52:47','2020-08-13 05:52:47',NULL),
(8,'Application monitoring','<p> Check the proper functioning of computer applications </p>','2020-08-13 05:53:19','2020-08-13 05:53:19',NULL),
(9,'Admission','<p> Admission of patients to the hospital </p>','2020-09-07 07:54:20','2024-10-14 08:01:04',NULL),
(10,'Complaint management','<p> Complaint management process </p>','2023-04-12 07:39:25','2024-10-14 08:00:35',NULL);
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_document`
--

LOCK TABLES `activity_document` WRITE;
/*!40000 ALTER TABLE `activity_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_m_application`
--

LOCK TABLES `activity_m_application` WRITE;
/*!40000 ALTER TABLE `activity_m_application` DISABLE KEYS */;
INSERT INTO `activity_m_application` (`m_application_id`, `activity_id`) VALUES (1,9);
/*!40000 ALTER TABLE `activity_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_operation`
--

LOCK TABLES `activity_operation` WRITE;
/*!40000 ALTER TABLE `activity_operation` DISABLE KEYS */;
INSERT INTO `activity_operation` (`activity_id`, `operation_id`) VALUES (2,3),
(1,1),
(1,2),
(4,3),
(3,1),
(5,6),
(5,7),
(5,8);
/*!40000 ALTER TABLE `activity_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_process`
--

LOCK TABLES `activity_process` WRITE;
/*!40000 ALTER TABLE `activity_process` DISABLE KEYS */;
INSERT INTO `activity_process` (`process_id`, `activity_id`) VALUES (1,1),
(1,2),
(2,3),
(2,4),
(9,5),
(9,6),
(9,7),
(9,8),
(5,9),
(19,9),
(18,9);
/*!40000 ALTER TABLE `activity_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `actor_operation`
--

LOCK TABLES `actor_operation` WRITE;
/*!40000 ALTER TABLE `actor_operation` DISABLE KEYS */;
INSERT INTO `actor_operation` (`operation_id`, `actor_id`) VALUES (2,1),
(1,1),
(1,4),
(2,5),
(3,6),
(6,7),
(7,7),
(8,7);
/*!40000 ALTER TABLE `actor_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `actors`
--

LOCK TABLES `actors` WRITE;
/*!40000 ALTER TABLE `actors` DISABLE KEYS */;
INSERT INTO `actors` (`id`, `name`, `nature`, `type`, `contact`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Jeans','Person','internal',NULL,'2020-06-14 11:02:22','2020-06-22 06:12:20','2020-06-22 06:12:20'),
(2,'Service 1','Band','internal',NULL,'2020-06-14 11:02:39','2020-06-17 14:43:42','2020-06-17 14:43:42'),
(3,'Service 2','Band','Internal',NULL,'2020-06-14 11:02:54','2020-06-17 14:43:46','2020-06-17 14:43:46'),
(4,'Rock','Person','internal','Stone contact','2020-06-17 14:44:01','2020-06-22 06:12:20','2020-06-22 06:12:20'),
(5,'Jacques','person','internal','Phone 1234543423','2020-06-17 14:44:23','2020-06-22 06:12:20','2020-06-22 06:12:20'),
(6,'Supplier 1','entity','external','Tel: 1232 32312','2020-06-17 14:44:50','2020-06-22 06:12:20','2020-06-22 06:12:20'),
(7,'Helpdesk agent','Band','Internal','80800 - helpdesk.informatique@chem.lu','2020-08-13 06:35:31','2021-01-28 14:08:24',NULL),
(8,'Caregiver','Band','Internal','None','2025-06-10 17:29:28','2025-06-10 17:29:28',NULL),
(9,'Doctor','band','Internal','None','2025-06-10 17:29:47','2025-06-10 17:29:47',NULL),
(10,'Supplier','entity','external','None','2025-06-10 17:30:11','2025-06-10 17:30:11',NULL),
(11,'Administrative agent','person','internal','None','2025-06-10 17:30:41','2025-06-10 17:30:41',NULL),
(12,'Recruiter','person','internal','None','2025-06-10 17:31:12','2025-06-10 17:31:12',NULL);
/*!40000 ALTER TABLE `actors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` (`id`, `user_id`, `firstname`, `lastname`, `type`, `icon_id`, `description`, `local`, `privileged`, `domain_id`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'M01','Marcel','Dupont','System',NULL,'<p> System administrator </p>',0,0,1,'2025-06-12 11:29:56','2025-06-12 11:30:37',NULL),
(2,'P02','Paul','Martin','System',NULL,'<p> System administrator </p>',0,0,1,'2025-06-12 11:30:31','2025-06-12 11:30:31',NULL),
(3,'G03','Gus','Schmidt','Network',NULL,'<p> Network administrator </p>',0,0,1,'2025-06-12 11:31:08','2025-06-12 11:31:08',NULL);
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `annuaires`
--

LOCK TABLES `annuaires` WRITE;
/*!40000 ALTER TABLE `annuaires` DISABLE KEYS */;
INSERT INTO `annuaires` (`id`, `name`, `description`, `solution`, `created_at`, `updated_at`, `deleted_at`, `zone_admin_id`) VALUES (1,'PHONE','<p> Telephone directory </p>','Tasco','2025-06-12 11:25:21','2025-06-12 11:25:21',NULL,1),
(2,'OpenD','<p> LDAP + Kerberos + Owner extensions </p>','Apache','2025-06-12 11:27:40','2025-06-14 05:52:18',NULL,1);
/*!40000 ALTER TABLE `annuaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_blocks`
--

LOCK TABLES `application_blocks` WRITE;
/*!40000 ALTER TABLE `application_blocks` DISABLE KEYS */;
INSERT INTO `application_blocks` (`id`, `name`, `description`, `responsible`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Administration','<p> Administrative applications </p>',NULL,'2025-06-10 09:49:49','2025-06-10 09:49:49',NULL),
(2,'Laboratory','<p> Laboratory applications </p>',NULL,'2025-06-10 09:50:11','2025-06-10 09:50:11',NULL),
(3,'Medical','<p> Medical applications </p>',NULL,'2025-06-10 09:50:25','2025-06-10 09:50:25',NULL),
(4,'Accounting','<p> Accounting software </p>',NULL,'2025-06-10 10:02:16','2025-06-10 10:02:16',NULL),
(5,'Human resources','<p> Human resources management software </p>',NULL,'2025-06-10 10:02:46','2025-06-10 10:02:46',NULL),
(6,'Computer science','<p> IT Department software </p>',NULL,'2025-06-10 10:03:05','2025-06-10 10:03:05',NULL);
/*!40000 ALTER TABLE `application_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_module_application_service`
--

LOCK TABLES `application_module_application_service` WRITE;
/*!40000 ALTER TABLE `application_module_application_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_module_application_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_modules`
--

LOCK TABLES `application_modules` WRITE;
/*!40000 ALTER TABLE `application_modules` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_service_m_application`
--

LOCK TABLES `application_service_m_application` WRITE;
/*!40000 ALTER TABLE `application_service_m_application` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_service_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_services`
--

LOCK TABLES `application_services` WRITE;
/*!40000 ALTER TABLE `application_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `bay_wifi_terminal`
--

LOCK TABLES `bay_wifi_terminal` WRITE;
/*!40000 ALTER TABLE `bay_wifi_terminal` DISABLE KEYS */;
/*!40000 ALTER TABLE `bay_wifi_terminal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `bays`
--

LOCK TABLES `bays` WRITE;
/*!40000 ALTER TABLE `bays` DISABLE KEYS */;
INSERT INTO `bays` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `room_id`) VALUES (1,'R01','<p> Main rack </p>','2025-06-10 08:46:55','2025-06-10 08:46:55',NULL,12),
(2,'R02','<p> rack database / backup </p>','2025-06-11 10:24:04','2025-06-11 10:24:04',NULL,12),
(3,'R03','<p> Mainframe </p>','2025-06-12 17:57:32','2025-06-12 17:57:32',NULL,12);
/*!40000 ALTER TABLE `bays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `buildings`
--

LOCK TABLES `buildings` WRITE;
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
INSERT INTO `buildings` (`id`, `name`, `description`, `attributes`, `created_at`, `updated_at`, `deleted_at`, `site_id`) VALUES (1,'101','<p> Patient welcome </p>','Audience','2025-06-10 08:33:42','2025-06-10 08:33:42',NULL,1),
(2,'102','<p> waiting room </p>','Audience','2025-06-10 08:34:36','2025-06-10 08:34:36',NULL,1),
(3,'103','<p> Consultation room 1 </p>','Public care','2025-06-10 08:35:13','2025-06-10 08:40:25',NULL,1),
(4,'104','<p> Consultation room 2 </p>','Public care','2025-06-10 08:35:34','2025-06-10 08:40:13',NULL,1),
(5,'105','<p> Emergencies </p>','Public care','2025-06-10 08:38:19','2025-06-10 08:39:03',NULL,1),
(6,'201','<p> Laboratory </p>','Restricted','2025-06-10 08:38:51','2025-06-10 08:38:51',NULL,1),
(7,'202','<p> Pharmacy </p>','Restricted','2025-06-10 08:39:35','2025-06-10 08:39:35',NULL,1),
(8,'205','<p> Medical imaging </p>','Audience','2025-06-10 08:42:11','2025-06-10 08:42:11',NULL,1),
(9,'303','<p> Operating room </p>','Restricted care','2025-06-10 08:43:01','2025-06-10 08:43:01',NULL,1),
(10,'304','<p> Intensive care </p>','Restricted care','2025-06-10 08:44:58','2025-06-10 08:44:58',NULL,1),
(11,'401','<p> Administration </p>','Restricted','2025-06-10 08:45:20','2025-06-10 08:45:20',NULL,1),
(12,'403','<p> Technical premises </p>','Restricted','2025-06-10 08:45:44','2025-06-10 08:45:44',NULL,1),
(13,'404','<p> Logistics </p>','Restricted','2025-06-10 08:46:05','2025-06-10 08:46:05',NULL,1),
(14,'402','<p> Direction </p>','Restricted','2025-06-11 10:23:11','2025-06-11 10:23:11',NULL,1),
(15,'302','<p> Technical premises </p>','Restricted','2025-06-14 05:57:11','2025-06-14 05:57:11',NULL,1);
/*!40000 ALTER TABLE `buildings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cartographer_m_application`
--

LOCK TABLES `cartographer_m_application` WRITE;
/*!40000 ALTER TABLE `cartographer_m_application` DISABLE KEYS */;
/*!40000 ALTER TABLE `cartographer_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `certificate_logical_server`
--

LOCK TABLES `certificate_logical_server` WRITE;
/*!40000 ALTER TABLE `certificate_logical_server` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificate_logical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `certificate_m_application`
--

LOCK TABLES `certificate_m_application` WRITE;
/*!40000 ALTER TABLE `certificate_m_application` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificate_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `certificates`
--

LOCK TABLES `certificates` WRITE;
/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `clusters`
--

LOCK TABLES `clusters` WRITE;
/*!40000 ALTER TABLE `clusters` DISABLE KEYS */;
INSERT INTO `clusters` (`id`, `name`, `type`, `description`, `address_ip`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Cluster01','Xzware','<p> Main cluster. </p>','10.10.8.2','2025-06-12 11:51:05','2025-06-12 11:51:05',NULL);
/*!40000 ALTER TABLE `clusters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `container_logical_server`
--

LOCK TABLES `container_logical_server` WRITE;
/*!40000 ALTER TABLE `container_logical_server` DISABLE KEYS */;
/*!40000 ALTER TABLE `container_logical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `container_m_application`
--

LOCK TABLES `container_m_application` WRITE;
/*!40000 ALTER TABLE `container_m_application` DISABLE KEYS */;
/*!40000 ALTER TABLE `container_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `containers`
--

LOCK TABLES `containers` WRITE;
/*!40000 ALTER TABLE `containers` DISABLE KEYS */;
/*!40000 ALTER TABLE `containers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `data_processing`
--

LOCK TABLES `data_processing` WRITE;
/*!40000 ALTER TABLE `data_processing` DISABLE KEYS */;
INSERT INTO `data_processing` (`id`, `name`, `legal_basis`, `description`, `responsible`, `purpose`, `categories`, `recipients`, `transfert`, `retention`, `controls`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'User Account Management','Legal obligation / legitimate interest.','<p> creation, modification and deletion of it accounts for access to internal digital services. </p>','<p> Information Systems Manager. </p>','<p> Information System Access Rights Management. </p>','<p> Staff internal (Employees, Trainees). <br> Technical Provider </p>','<p> Internal it team <br> dpo <br> iam provider </p>','<p> no transfers outside the eu. </p>','<p> 1 Year After End of Contract or Departure of User. </p>',NULL,'2025-06-14 10:55:47','2025-06-16 04:36:28',NULL),
(2,'Traceability, detection and management of cybersecurity incidents','Legal obligation / legitimate interest.','<p> Registration and analysis of incidents or anomalies affecting the security of the IS (eg: attempted intrusion, account compromise). </p>','<p> Responsible for information systems security (RSSI). </p>','<p> Traceability, detection and management of cybersecurity incidents. </p>','<ul> <li> RSSI, DSI (category: <strong> internal service </strong>) </li> <li> Cybersecurity providers (category: <strong> Technical provider </strong>) </li> <li> <li> Competent authorities in the event of notification (Category: <strong> Public authority </strong> </li> </ul> </ul> </ul> </ul> </ul> </ul> </ul> </ul> </ul>','<p> RSSI, DSI, Cybersecurity providers. </p>','<p> None, unless judicial requisition with international cooperation. </p>','<p> Three years after the closure of the incident. </p>',NULL,'2025-06-14 10:56:18','2025-06-14 11:01:37',NULL),
(3,'Information System Analysis (mapping)','Legitimate interest.','<p> Gathering and Structuring Information on Assets, Their Flows and Those Responsible for them, as part of a risk management Approach.Information Systems Manager. </p>','<p> Information Systems Manager. </p>','<p> Referencing and analysis of is components for security, audit and compliance purposes. </p>','<p> in-house <br> Authorized Third-Party Service Provider </p>','<p> it team <br> ciso, auditors <br> external auditors <br> & nbsp; </p>','<p> none. </p>','<p> data retaineed as long as the asset is present in the is. </p>',NULL,'2025-06-14 11:12:15','2025-06-16 04:34:29',NULL),
(4,'Record patient computerized (CPR) Management','Mission of Public Interest','<p> Record, update and consulted patient health data relating to the care provided by the facility (diagnoses, prescriptions, reports, imaging, etc.). </p>','<p> Hospital Director / Medical Director. </p>','<p> Provide medical and administrative care for patients. </p>','<ul> <li> & nbsp; Internal service authorized </li> <li> & nbsp; Internal service </li> <li> Technical provider authorized </li> <li> Public or private organizations authorized </li> <li> Public authority </li> </ul>','<ul> <li> Health professionals (doctors, nurses, medical secretaries) - <strong> category </strong>: </li> <li> Internal administrative services (invoicing, admissions) - <strong> category </strong>: </li> <li> <li> Authorized health data host (HDS) - <strong> category </strong> : & nbsp; </li> <li> Social security organizations, mutuals - <strong> category </strong>: & nbsp; </li> <li> Health authorities (ARS, CNAM, etc.) - <strong> category </strong>: & nbsp; </li> </ul>','<p> All data is hosted at an HDS certified service provider located in Luxembourg or in the EU. </p>','<p> 20 years after the last care (public health code, art. R1112-7) </p>',NULL,'2025-06-14 11:15:41','2025-06-16 04:37:57',NULL);
/*!40000 ALTER TABLE `data_processing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `data_processing_document`
--

LOCK TABLES `data_processing_document` WRITE;
/*!40000 ALTER TABLE `data_processing_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_processing_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `data_processing_information`
--

LOCK TABLES `data_processing_information` WRITE;
/*!40000 ALTER TABLE `data_processing_information` DISABLE KEYS */;
INSERT INTO `data_processing_information` (`data_processing_id`, `information_id`) VALUES (4,19),
(4,8),
(4,15),
(4,4),
(1,10),
(1,13),
(3,18),
(2,18);
/*!40000 ALTER TABLE `data_processing_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `data_processing_m_application`
--

LOCK TABLES `data_processing_m_application` WRITE;
/*!40000 ALTER TABLE `data_processing_m_application` DISABLE KEYS */;
INSERT INTO `data_processing_m_application` (`data_processing_id`, `m_application_id`) VALUES (4,1),
(1,13),
(3,6),
(3,3),
(3,2),
(3,1),
(3,8),
(3,4),
(3,7),
(3,12),
(3,15),
(3,14),
(3,5),
(3,10),
(3,9),
(3,11),
(3,13),
(2,6),
(2,3),
(2,2),
(2,1),
(2,8),
(2,4),
(2,7),
(2,12),
(2,15),
(2,14),
(2,5),
(2,10),
(2,9),
(2,11),
(2,13);
/*!40000 ALTER TABLE `data_processing_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `data_processing_process`
--

LOCK TABLES `data_processing_process` WRITE;
/*!40000 ALTER TABLE `data_processing_process` DISABLE KEYS */;
INSERT INTO `data_processing_process` (`data_processing_id`, `process_id`) VALUES (4,19),
(4,18),
(4,5),
(1,9),
(3,36),
(3,19),
(3,6),
(3,32),
(3,33),
(3,38),
(3,11),
(3,39),
(3,18),
(3,13),
(3,31),
(3,24),
(3,9),
(3,34),
(3,26),
(3,21),
(3,27),
(3,12),
(3,37),
(3,7),
(3,14),
(3,29),
(3,28),
(3,10),
(3,30),
(3,5),
(2,36),
(2,19),
(2,6),
(2,32),
(2,33),
(2,38),
(2,11),
(2,39),
(2,18),
(2,13),
(2,31),
(2,24),
(2,9),
(2,34),
(2,26),
(2,21),
(2,27),
(2,12),
(2,37),
(2,7),
(2,14),
(2,29),
(2,28),
(2,10),
(2,30),
(2,5);
/*!40000 ALTER TABLE `data_processing_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_entity`
--

LOCK TABLES `database_entity` WRITE;
/*!40000 ALTER TABLE `database_entity` DISABLE KEYS */;
INSERT INTO `database_entity` (`database_id`, `entity_id`) VALUES (2,3),
(3,3),
(4,4);
/*!40000 ALTER TABLE `database_entity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_information`
--

LOCK TABLES `database_information` WRITE;
/*!40000 ALTER TABLE `database_information` DISABLE KEYS */;
INSERT INTO `database_information` (`database_id`, `information_id`) VALUES (1,8),
(1,14),
(3,7),
(3,15),
(3,6),
(4,14),
(4,4);
/*!40000 ALTER TABLE `database_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_logical_server`
--

LOCK TABLES `database_logical_server` WRITE;
/*!40000 ALTER TABLE `database_logical_server` DISABLE KEYS */;
INSERT INTO `database_logical_server` (`database_id`, `logical_server_id`) VALUES (2,1),
(1,3),
(3,3),
(1,5);
/*!40000 ALTER TABLE `database_logical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_m_application`
--

LOCK TABLES `database_m_application` WRITE;
/*!40000 ALTER TABLE `database_m_application` DISABLE KEYS */;
INSERT INTO `database_m_application` (`m_application_id`, `database_id`) VALUES (1,1),
(3,2),
(2,3),
(12,3),
(1,4),
(5,4);
/*!40000 ALTER TABLE `database_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `databases`
--

LOCK TABLES `databases` WRITE;
/*!40000 ALTER TABLE `databases` DISABLE KEYS */;
INSERT INTO `databases` (`id`, `name`, `description`, `responsible`, `type`, `security_need_c`, `external`, `created_at`, `updated_at`, `deleted_at`, `entity_resp_id`, `security_need_i`, `security_need_a`, `security_need_t`, `security_need_auth`) VALUES (1,'Medic','<p> Medical database </p>','Paul','Db3',4,'Inerte','2025-06-10 10:06:13','2025-06-12 13:14:11',NULL,15,4,4,4,NULL),
(2,'Biblio','<p> Medical publications database </p>','Paul','Mysql',1,'Inerte','2025-06-12 12:48:43','2025-06-12 13:02:56',NULL,9,1,1,1,NULL),
(3,'Count','<p> Accounting database </p>','Paul','Sop',4,'Inerte','2025-06-12 12:50:49','2025-06-14 05:55:03',NULL,3,4,4,4,NULL),
(4,'DNA','<p> DNA database </p>','Paul','Mysql',4,NULL,'2025-06-12 12:52:36','2025-06-12 12:53:05',NULL,2,4,4,4,NULL);
/*!40000 ALTER TABLE `databases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `dhcp_servers`
--

LOCK TABLES `dhcp_servers` WRITE;
/*!40000 ALTER TABLE `dhcp_servers` DISABLE KEYS */;
/*!40000 ALTER TABLE `dhcp_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `dnsservers`
--

LOCK TABLES `dnsservers` WRITE;
/*!40000 ALTER TABLE `dnsservers` DISABLE KEYS */;
/*!40000 ALTER TABLE `dnsservers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `document_logical_server`
--

LOCK TABLES `document_logical_server` WRITE;
/*!40000 ALTER TABLE `document_logical_server` DISABLE KEYS */;
/*!40000 ALTER TABLE `document_logical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `document_relation`
--

LOCK TABLES `document_relation` WRITE;
/*!40000 ALTER TABLE `document_relation` DISABLE KEYS */;
/*!40000 ALTER TABLE `document_relation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `domaine_ad_forest_ad`
--

LOCK TABLES `domaine_ad_forest_ad` WRITE;
/*!40000 ALTER TABLE `domaine_ad_forest_ad` DISABLE KEYS */;
INSERT INTO `domaine_ad_forest_ad` (`forest_ad_id`, `domaine_ad_id`) VALUES (1,1);
/*!40000 ALTER TABLE `domaine_ad_forest_ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `domaine_ads`
--

LOCK TABLES `domaine_ads` WRITE;
/*!40000 ALTER TABLE `domaine_ads` DISABLE KEYS */;
INSERT INTO `domaine_ads` (`id`, `name`, `description`, `domain_ctrl_cnt`, `user_count`, `machine_count`, `relation_inter_domaine`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Openhost','<p> Active Directory Open Hospital </p>',1,120,30,'N / A','2025-06-12 11:24:48','2025-06-12 11:24:48',NULL);
/*!40000 ALTER TABLE `domaine_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entities`
--

LOCK TABLES `entities` WRITE;
/*!40000 ALTER TABLE `entities` DISABLE KEYS */;
INSERT INTO `entities` (`id`, `name`, `icon_id`, `security_level`, `contact_point`, `description`, `is_external`, `created_at`, `updated_at`, `deleted_at`, `entity_type`, `attributes`, `reference`, `parent_entity_id`) VALUES (1,'Big Health Tech',NULL,'<p> ISO 27001 - HDS </p>','<p> Technical support <br> <a href = \ "mailto: support@bighealthtech.com \"> support@bighealthtech.com </a> <br> --- <br> John Borg & nbsp; <br> Sales Manager <br> <A href = \ "mailto: john@gibhealthTech.com \"> John@gibhealthtech.com </a> <br> +33 45 67 89 01 <br> & nbsp; </p>','<p> Société Édister de l\'O </p>',1,'2025-06-10 16:53:12','2025-06-10 16:54:20',NULL,'Supplier',NULL,NULL,NULL),
(2,'Openhospi-it',NULL,'<p> ISO 27001 </p>','<p> Mail: helpdesks@openhop.net <br> Tel: 88 800 </p>','<p> IT department of open hospital </p>',0,'2025-06-12 12:16:17','2025-06-12 12:25:34',NULL,'Internal',NULL,NULL,3),
(3,'Openhosp',NULL,'<p> Cert-med+</p>','<p> Mail: <a href = \ "mailto: contact@openhosp.net \"> contact@openhosp.net </a> <br> Tel: +33 44 </p>','<p> The Open Hospital </p>',0,'2025-06-12 12:16:56','2025-06-14 05:55:03',NULL,'Internal',NULL,NULL,NULL),
(4,'OpenHosp-Lab',NULL,'<p> none </p>','<p> Mail: <a href = \ "mailto: labox@opennosc.net \"> laboisp.net </a> <br> Tel: 23 45 </p> <p> & nbsp; </p>','<p> Labaratory of Open Hospital </p>',0,'2025-06-12 12:17:53','2025-06-12 12:18:43',NULL,'Internal',NULL,NULL,3),
(5,'Openhospi-dir',NULL,'<p> None </p>','<p> Mail: <a href = \ "mailto: direction@openhosp.net \"> direction@openhosp.net </a> <br> Tel: 57 32 </p>','<p> Direction of Open Hospital </p>',0,'2025-06-12 12:19:31','2025-06-12 12:20:24',NULL,'Internal',NULL,NULL,3),
(6,'OpenHosper',NULL,'<p> None </p>','<p> Mail: <a href = \ "mailto: comminucation@openhosp.net \"> comminucation@openhosp.net </a> <br> Tel: 859 43 </p>','<p> Open Hospital Communication Unit </p>',NULL,'2025-06-12 12:21:18','2025-06-12 12:21:18',NULL,'Internal',NULL,NULL,3),
(7,'OpenHosp-Urg',NULL,'<p> None </p>','<p> Mail: <a href = \ "mailto: emergencies@openhosp.net \"> emergencies@openhosp.net </a> <br> Tel: 11 11 </p>','<p> Open Hospital Emergency Service </p>',NULL,'2025-06-12 12:22:13','2025-06-12 12:22:13',NULL,'Internal',NULL,NULL,3),
(8,'Openhosp-rx',NULL,'<p> None </p>','<p> Mail: <a href = \ "mailto: radiology@openhosp.net \"> radiology@openhosp.net </a> <br> Tel: 57 43 </p>','<p> Radiology service </p>',NULL,'2025-06-12 12:24:23','2025-06-12 12:24:23',NULL,'Internal',NULL,NULL,3),
(9,'Medi+',NULL,'<p> none </p>','<p> Mail: <a href = \ "mailto: support@mediplus.com \"> support@mediplus.com </a> <br> Tel: 12 43 43 </p>','<p> Medical application editor </p>',1,'2025-06-12 12:47:34','2025-06-12 13:02:56',NULL,'Supplier',NULL,NULL,NULL),
(10,'General Sys',NULL,'<p> ISO 27001 - SYS/DSS 32 </p>','<p> Mail: <a href = \ "mailto: contact@general-sys.com \"> contact@general-sys.com </a> <br> Tel: 32 54 65 </p>','<p> Society publisher company </p>',1,'2025-06-12 12:56:14','2025-06-12 12:56:14',NULL,'Supplier',NULL,NULL,NULL),
(11,'Ltr',NULL,'<p> none </p>','<p> Paul Right & Nbsp; <br> Tel: 32 54 32 <br> Mail: Paul@ltr.com </p>','<p> Little Things Right - Consulting </p>',1,'2025-06-12 12:57:43','2025-06-12 12:57:43',NULL,'Supplier',NULL,NULL,NULL),
(12,'Nonesoft',NULL,'<p> none </p>','<p> Mail: <a href = \ "mailto: info@nonesoft.com \"> info@nonesoft.com </a> <br> Tel: 32 432 432 </p>','<p> no more software ltd </p>',1,'2025-06-12 13:01:26','2025-06-12 13:01:26',NULL,'Supplier',NULL,NULL,NULL),
(13,'Hal',NULL,'<p> CSP+, ISO 27001, FDM, RRLF, Fosdem </p>','<p> Mail: <a href = \ "mailto: contact@hal.com \"> contact@hal.com </a> <br> Tel: 32 43 54 </p>','<p> Big it provides </p>',1,'2025-06-12 13:02:39','2025-06-12 13:02:39',NULL,'Supplier',NULL,NULL,NULL),
(14,'Bigbrainlab',NULL,'<p> ISO 27001 </p>','<p> Mail: <a href = \ "mailto: info@bigbrain.com \"> info@bigbrain.com </a> <br> Tel: 99 43 74 </p>','<p> The Big Brain Laboratory </p>',1,'2025-06-12 13:04:14','2025-06-12 13:04:14',NULL,'Supplier',NULL,NULL,NULL),
(15,'Tech24',NULL,'<p> ISO 27001 - HDS </p>','<p> Mail: <a href = \ "mailto: tech@tech24.com \"> tech@tech24.com </a> <br> Phone: 21 45 32 </p>','<p> The Tech 24 Application Provider </p>',1,'2025-06-12 13:14:11','2025-06-12 13:14:11',NULL,'Supplier',NULL,NULL,NULL),
(16,'Ohf',NULL,'<p> ISO 27001 </p>','<p> Mail: <a href = \ "mailto: contact@ohf.net \"> contact@ohf.net </a> <br> Tel: 32 54 23 </p>','<p> Open Hospital Federation </p>',NULL,'2025-06-12 13:24:04','2025-06-12 13:24:04',NULL,'Supplier',NULL,NULL,NULL),
(17,'Openhosp-rh',NULL,'<p> none </p>','<p> Mail: <a href = \ "mailto: rh@openhosp.net \"> rh@openhosp.net </a> <br> Tel: 87 43 54 </p>','<p> Human resources service </p>',NULL,'2025-06-12 17:04:31','2025-06-12 17:04:31',NULL,'Internal',NULL,NULL,3);
/*!40000 ALTER TABLE `entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entity_document`
--

LOCK TABLES `entity_document` WRITE;
/*!40000 ALTER TABLE `entity_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `entity_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entity_m_application`
--

LOCK TABLES `entity_m_application` WRITE;
/*!40000 ALTER TABLE `entity_m_application` DISABLE KEYS */;
INSERT INTO `entity_m_application` (`m_application_id`, `entity_id`) VALUES (3,3),
(4,3),
(5,4),
(6,3),
(7,2),
(8,2),
(9,3),
(10,3),
(12,17),
(11,17),
(13,2),
(14,3),
(15,3),
(2,3),
(1,3);
/*!40000 ALTER TABLE `entity_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entity_process`
--

LOCK TABLES `entity_process` WRITE;
/*!40000 ALTER TABLE `entity_process` DISABLE KEYS */;
INSERT INTO `entity_process` (`process_id`, `entity_id`) VALUES (18,1),
(24,1),
(9,2),
(21,4),
(6,5),
(11,5),
(14,5),
(6,6),
(5,7),
(18,8),
(5,8),
(24,9),
(34,10),
(9,11),
(9,12),
(9,13),
(21,14),
(19,15),
(18,15),
(5,15),
(7,16),
(28,17),
(36,3),
(19,3),
(6,3),
(32,3),
(33,3),
(38,3),
(11,3),
(39,3),
(18,3),
(13,3),
(31,3),
(24,3),
(9,3),
(34,3),
(26,3),
(21,3),
(27,3),
(12,3),
(37,3),
(7,3),
(14,3),
(29,3),
(28,3),
(10,3),
(30,3),
(5,3);
/*!40000 ALTER TABLE `entity_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `external_connected_entities`
--

LOCK TABLES `external_connected_entities` WRITE;
/*!40000 ALTER TABLE `external_connected_entities` DISABLE KEYS */;
/*!40000 ALTER TABLE `external_connected_entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fluxes`
--

LOCK TABLES `fluxes` WRITE;
/*!40000 ALTER TABLE `fluxes` DISABLE KEYS */;
INSERT INTO `fluxes` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `application_source_id`, `service_source_id`, `module_source_id`, `database_source_id`, `application_dest_id`, `service_dest_id`, `module_dest_id`, `database_dest_id`, `crypted`, `bidirectional`, `nature`) VALUES (1,'Medical billing','<p> Sends invoicing to patients </p>','2025-06-12 17:29:15','2025-06-12 17:29:15',NULL,1,NULL,NULL,NULL,2,NULL,NULL,NULL,0,0,'API'),
(2,'Availability','<p> Availability of caregivers </p>','2025-06-12 17:29:45','2025-06-12 17:29:45',NULL,4,NULL,NULL,NULL,1,NULL,NULL,NULL,0,0,'API'),
(3,'Guards','<p> Payment of guards </p>','2025-06-12 17:30:10','2025-06-12 17:30:17',NULL,4,NULL,NULL,NULL,11,NULL,NULL,NULL,0,0,'API'),
(4,'Medical services','<p> Payment of services </p>','2025-06-12 17:30:39','2025-06-12 17:30:39',NULL,1,NULL,NULL,NULL,11,NULL,NULL,NULL,0,0,'API'),
(5,'Recruitment','<p> Recruitment management </p>','2025-06-12 17:31:16','2025-06-12 17:31:16',NULL,12,NULL,NULL,NULL,11,NULL,NULL,NULL,0,0,'API'),
(6,'Recruitment','<p> Management of new employees </p>','2025-06-12 17:34:20','2025-06-12 17:34:20',NULL,12,NULL,NULL,NULL,11,NULL,NULL,NULL,0,0,'API'),
(7,'Synchronization','<p> Add user deletion </p>','2025-06-12 17:45:07','2025-06-12 17:45:07',NULL,11,NULL,NULL,NULL,13,NULL,NULL,NULL,0,0,'API'),
(8,'Images','<p> Add data to the medical folder </p>','2025-06-12 17:46:21','2025-06-12 17:46:21',NULL,9,NULL,NULL,NULL,1,NULL,NULL,NULL,0,0,'API'),
(9,'Prescriptions','<p> Management of medical prescriptions </p>','2025-06-12 17:47:42','2025-06-12 17:47:42',NULL,10,NULL,NULL,NULL,1,NULL,NULL,NULL,0,0,'API');
/*!40000 ALTER TABLE `fluxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `forest_ads`
--

LOCK TABLES `forest_ads` WRITE;
/*!40000 ALTER TABLE `forest_ads` DISABLE KEYS */;
INSERT INTO `forest_ads` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `zone_admin_id`) VALUES (1,'Open Source LDAP','<p> Active Direct Direct of Open Hospital </p>','2025-06-12 11:23:11','2025-06-12 11:28:33',NULL,1);
/*!40000 ALTER TABLE `forest_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `gateways`
--

LOCK TABLES `gateways` WRITE;
/*!40000 ALTER TABLE `gateways` DISABLE KEYS */;
/*!40000 ALTER TABLE `gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `information`
--

LOCK TABLES `information` WRITE;
/*!40000 ALTER TABLE `information` DISABLE KEYS */;
INSERT INTO `information` (`id`, `name`, `description`, `owner`, `administrator`, `storage`, `security_need_c`, `sensitivity`, `constraints`, `retention`, `created_at`, `updated_at`, `deleted_at`, `security_need_i`, `security_need_a`, `security_need_t`, `security_need_auth`) VALUES (1,'Information 1','<p> Information description 1 </p>','Establishment','Administrator name','Storage type',3,'Personal data','<p> Description of regulatory and normative constraints </p>',NULL,'2020-06-13 00:06:43','2020-06-22 06:12:26','2020-06-22 06:12:26',3,3,3,NULL),
(2,'Information 2','<p> Information description </p>',NULL,NULL,NULL,3,NULL,NULL,NULL,'2020-06-13 00:09:13','2020-06-22 06:12:26','2020-06-22 06:12:26',3,3,3,NULL),
(3,'Information 3','<p> Information description 3 </p>','Managers',NULL,NULL,3,NULL,NULL,NULL,'2020-06-13 00:10:07','2020-06-22 06:12:26','2020-06-22 06:12:26',3,3,3,NULL),
(4,'Patient name','<p> Last name and first name of the patient </p>','Establishment','Chem','secure',3,'Personal data','<p> <a href = \ "http: //www.legilux.public.lu/leg/a/archives/2013/0107/index.html \"> Identification of natural persons (law 2013) & nbsp; </a> </p> <p> <p> law of June 19, 2013 relating to the identification of natural persons, in the national personal register, identity, in the municipal registers of natural persons and modifying 1) article 104 of the Civil Code; 2 </p> <p> <a href = \ "http: //www.legilux.public.lu/leg/a/archives/2013/0208/index.html \"> Identification of natural persons-Application methods (Grand-Ducal 2013) & nbsp; </a> </p> <p> Grand-Ducal regulations of November 28, 2013 application of the law of June 19, 2013 relating to the identification of natural persons. Methods of application of the law of June 19, 2013 relating to the identification of phy people </p>',NULL,'2020-07-02 05:58:39','2021-05-19 05:42:48',NULL,3,3,3,NULL),
(5,'Social security number','<p> National identification number at 13 positions. </p>','Establishment','Chem','secure',3,'Personal data','<p> <a href = \ "http: //www.legilux.public.lu/leg/a/archives/2013/0107/a107.pdf#page=2 \"> Law of June 19, 2013 </a> relating to the identification of natural persons, in the national natural persons register, with the communal registers of people Physicals </p> <p> <a href = \ "http: //www.legilux.public.lu/leg/a/archives/2013/0208/a208.pdf#page=2 \"> Grand-ducal regulation of November 28, 2013 </a> fixing & nbsp; Methods of application of the law of June 19, 2013 relating to the identification of natural persons </p>',NULL,'2020-07-02 06:02:03','2021-05-19 05:45:06',NULL,3,3,3,NULL),
(6,'Account number','<p> Banking contact details - IBAN Code </p>','Establishment','Chem','secure',3,'Personal data','<p> General regulations on the protection of personal data (GDPR) </p>',NULL,'2020-07-07 10:48:21','2021-05-25 08:38:11',NULL,3,3,4,NULL),
(7,'Address','<p> Person\'s physical address - Main location </p>','Establishment','Chem','local',3,'Personal data','<p> General regulations on the protection of personal data (GDPR) </p>',NULL,'2020-07-07 10:49:11','2021-05-19 05:42:01',NULL,3,3,3,NULL),
(8,'Diagnosis','<p> Identification of the nature of a situation, an evil, a difficulty, etc. </p> <p> reasoning leading to the identification of a disease. & nbsp;','Healthcare professional','Chem','Secure',3,'medical data','<p> General regulations on the protection of personal data (GDPR) </p>',NULL,'2020-07-07 11:42:36','2021-05-25 08:37:42',NULL,3,3,4,NULL),
(9,'Prescription / prescription','<p> Act by which the doctor, after a diagnosis, describes the treatment that the patient will have to follow. </p>','Healthcare professional','Chem','secure',3,'medical data','<p> General regulations on the protection of personal data (GDPR) </p>',NULL,'2020-07-07 11:42:56','2021-05-19 05:45:34',NULL,3,3,3,NULL),
(10,'IP address','<p> Identification number which is permanently attributed to each device connected to a computer network that uses the Internet Protocol. </p>','Establishment','Chem','local',2,'technical data','<p> General regulations on the protection of personal data (GDPR) </p>',NULL,'2020-07-08 06:19:37','2021-05-25 08:37:26',NULL,3,2,2,NULL),
(11,'Email address','<p> Character chain to send email in a computer mailbox. & nbsp; </p>','Establishment','Chem','local',3,'Personal data','<p> General regulations on personal data protection </p>',NULL,'2020-07-08 06:20:12','2021-05-19 05:43:22',NULL,3,3,3,NULL),
(12,'Internal phone number','<p> Following figures that uniquely identifies a terminal within a telephone network. </p>','Establishment','Chem','local',3,'general data','<p> None </p>',NULL,'2020-07-08 06:21:13','2021-05-19 05:45:23',NULL,3,3,3,NULL),
(13,'Health Professional Name','<p> Last name and first name of a health professional </p>','Establishment','Chem','audience',2,'Personal data','<p> None </p>',NULL,'2020-07-08 06:21:44','2021-05-25 08:38:02',NULL,2,2,2,NULL),
(14,'Medical data','<p> General medical data of a patient file </p>','Establishment','Chem','Database',3,'Medical data',NULL,NULL,'2020-09-04 12:45:08','2021-05-19 05:44:30',NULL,3,3,3,NULL),
(15,'Patient administrative data','<p> Administrative data of the patient and these stays </p>','Establishment','Chem','Database',3,'Personal data',NULL,NULL,'2020-09-04 14:59:33','2021-05-19 05:43:43',NULL,3,3,3,NULL),
(16,'Patient billing data','<p> Patient billing data and these stays </p>','Establishment','Chem','Database',3,'Personal data',NULL,NULL,'2020-09-04 15:00:14','2021-05-19 05:44:20',NULL,3,3,3,NULL),
(17,'Accounting data','<p> Accounting data </p>','Establishment','Chem','Database',3,'Personal data',NULL,NULL,'2020-10-22 09:52:29','2021-05-19 05:44:10',NULL,3,3,3,NULL),
(18,'Technical data','<p> Technical data on the internal functioning of the information system </p>','Establishment','Chem','secure',3,'technical data',NULL,NULL,'2021-10-26 12:17:08','2021-10-26 12:17:08',NULL,3,3,3,NULL),
(19,'Date of birth','<p> Date of birth of a natural person </p>','Establishment','Chem','local',3,'Personal data',NULL,NULL,'2021-10-28 03:19:52','2021-10-28 03:20:16',NULL,3,3,3,NULL),
(20,'Test data','<p> Data used for tests </p>','Establishment','Chem','Database',1,'Test data','<p> cannot contain production data. </p>',NULL,'2023-04-27 07:57:24','2023-04-27 09:30:47',NULL,2,2,2,NULL);
/*!40000 ALTER TABLE `information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `information_process`
--

LOCK TABLES `information_process` WRITE;
/*!40000 ALTER TABLE `information_process` DISABLE KEYS */;
INSERT INTO `information_process` (`information_id`, `process_id`) VALUES (3,1),
(3,2),
(2,1),
(1,1),
(10,9),
(11,9),
(8,5),
(12,6),
(12,9),
(4,19),
(4,5),
(5,5),
(7,5),
(4,11),
(5,11),
(6,11),
(7,11),
(11,11),
(13,11),
(9,5),
(4,21),
(9,21),
(13,21),
(9,19),
(13,19),
(4,18),
(9,18),
(13,18),
(13,12),
(7,6),
(11,6),
(5,28),
(6,28),
(7,28),
(11,28),
(10,10),
(11,10),
(13,10),
(12,10),
(7,29),
(11,29),
(4,29),
(13,29),
(12,29),
(14,19),
(14,24),
(16,11),
(15,11),
(9,32),
(4,32),
(7,36),
(6,36),
(12,36),
(17,11),
(7,7),
(11,7),
(15,7),
(17,7),
(16,7),
(14,7),
(12,7),
(13,26),
(12,26),
(4,27),
(13,27),
(12,27),
(9,27),
(12,30),
(12,38),
(12,37),
(17,14),
(17,28),
(14,32),
(14,21),
(14,5),
(11,34),
(13,34),
(12,34),
(7,14),
(11,14),
(15,14),
(16,14),
(4,14),
(13,14),
(5,14),
(12,14),
(15,39),
(16,39),
(4,39),
(9,39),
(11,31),
(13,31),
(12,31),
(11,33),
(17,33),
(13,33),
(12,33),
(11,13),
(4,13),
(12,13),
(18,9),
(19,19),
(19,11),
(19,18),
(19,21),
(19,14),
(19,28),
(19,5),
(20,9);
/*!40000 ALTER TABLE `information_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `lan_man`
--

LOCK TABLES `lan_man` WRITE;
/*!40000 ALTER TABLE `lan_man` DISABLE KEYS */;
/*!40000 ALTER TABLE `lan_man` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `lan_wan`
--

LOCK TABLES `lan_wan` WRITE;
/*!40000 ALTER TABLE `lan_wan` DISABLE KEYS */;
/*!40000 ALTER TABLE `lan_wan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `lans`
--

LOCK TABLES `lans` WRITE;
/*!40000 ALTER TABLE `lans` DISABLE KEYS */;
/*!40000 ALTER TABLE `lans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_flows`
--

LOCK TABLES `logical_flows` WRITE;
/*!40000 ALTER TABLE `logical_flows` DISABLE KEYS */;
/*!40000 ALTER TABLE `logical_flows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_server_m_application`
--

LOCK TABLES `logical_server_m_application` WRITE;
/*!40000 ALTER TABLE `logical_server_m_application` DISABLE KEYS */;
INSERT INTO `logical_server_m_application` (`m_application_id`, `logical_server_id`) VALUES (2,2),
(3,1),
(4,2),
(5,2),
(7,3),
(8,3),
(9,2),
(10,3),
(12,3),
(11,3),
(13,3),
(3,7),
(1,1);
/*!40000 ALTER TABLE `logical_server_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_server_physical_server`
--

LOCK TABLES `logical_server_physical_server` WRITE;
/*!40000 ALTER TABLE `logical_server_physical_server` DISABLE KEYS */;
INSERT INTO `logical_server_physical_server` (`logical_server_id`, `physical_server_id`) VALUES (4,3),
(5,3),
(6,3);
/*!40000 ALTER TABLE `logical_server_physical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_servers`
--

LOCK TABLES `logical_servers` WRITE;
/*!40000 ALTER TABLE `logical_servers` DISABLE KEYS */;
INSERT INTO `logical_servers` (`id`, `name`, `icon_id`, `type`, `active`, `description`, `net_services`, `configuration`, `created_at`, `updated_at`, `deleted_at`, `operating_system`, `address_ip`, `cpu`, `memory`, `environment`, `disk`, `disk_used`, `install_date`, `update_date`, `attributes`, `patching_frequency`, `next_update`, `cluster_id`, `domain_id`) VALUES (1,'SRV01',NULL,'App',1,'<p> server01 </p>','Ssh',NULL,'2025-06-11 10:42:11','2025-06-18 08:33:37',NULL,'Linux','10.10.25.9','12','64','Prod',512,154,'2025-01-01',NULL,'',NULL,NULL,1,1),
(2,'SRV02',NULL,'App',1,'<p> Application server </p>','SSH, http, https',NULL,'2025-06-12 11:52:23','2025-06-18 08:33:37',NULL,'Linux','10.10.25.24','4','10','Prod',120,80,'2025-01-01',NULL,'',NULL,NULL,1,1),
(3,'SRV03',NULL,'Dev',1,'<p> Development Server </p>','SSH, http, https',NULL,'2025-06-12 11:53:52','2025-06-18 08:33:37',NULL,'Linux','10.10.25.23','4','8','Dev',120,40,'2025-01-01',NULL,'',NULL,NULL,1,1),
(4,'DB01',NULL,'Db',1,'<p> Database Server 01 </p>',NULL,NULL,'2025-06-12 13:07:22','2025-06-14 09:13:04',NULL,'Linux','10.10.25.4',NULL,NULL,'Prod',NULL,NULL,'2025-01-01',NULL,'',NULL,NULL,NULL,1),
(5,'DB02',NULL,'Db',1,'<p> Databse Server 02 </p>',NULL,NULL,'2025-06-12 13:08:16','2025-06-14 09:13:13',NULL,'Linux','10.10.25.7','2','32',NULL,512,120,'2025-01-01',NULL,'',NULL,NULL,NULL,1),
(6,'DB-TST',NULL,'Db',1,'<p> Database test server </p>','SSH, DB',NULL,'2025-06-12 13:09:20','2025-06-18 08:33:37',NULL,'Linux','10.10.25.3','2','10','TEST',1024,130,'2025-01-01',NULL,'',NULL,NULL,1,1),
(7,'SRV-DEV',NULL,'Dev',1,'<p> Development server </p>','Ssh',NULL,'2025-06-12 17:52:19','2025-06-18 08:33:37',NULL,'Linux','10.10.25.8','2','16','Dev',250,50,'2025-01-01',NULL,'',NULL,NULL,1,1);
/*!40000 ALTER TABLE `logical_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_events`
--

LOCK TABLES `m_application_events` WRITE;
/*!40000 ALTER TABLE `m_application_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_application_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_peripheral`
--

LOCK TABLES `m_application_peripheral` WRITE;
/*!40000 ALTER TABLE `m_application_peripheral` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_application_peripheral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_physical_server`
--

LOCK TABLES `m_application_physical_server` WRITE;
/*!40000 ALTER TABLE `m_application_physical_server` DISABLE KEYS */;
INSERT INTO `m_application_physical_server` (`m_application_id`, `physical_server_id`) VALUES (2,1);
/*!40000 ALTER TABLE `m_application_physical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_process`
--

LOCK TABLES `m_application_process` WRITE;
/*!40000 ALTER TABLE `m_application_process` DISABLE KEYS */;
INSERT INTO `m_application_process` (`m_application_id`, `process_id`) VALUES (1,11),
(1,18),
(2,11),
(3,24),
(4,28),
(5,18),
(5,5),
(6,6),
(7,9),
(8,9),
(9,19),
(9,18),
(9,5),
(10,19),
(10,18),
(10,5),
(12,28),
(11,28),
(13,28),
(14,36),
(14,6),
(14,32),
(14,31),
(15,36),
(15,32),
(15,33),
(15,39);
/*!40000 ALTER TABLE `m_application_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_workstation`
--

LOCK TABLES `m_application_workstation` WRITE;
/*!40000 ALTER TABLE `m_application_workstation` DISABLE KEYS */;
INSERT INTO `m_application_workstation` (`m_application_id`, `workstation_id`) VALUES (1,2),
(1,3),
(1,4),
(1,5),
(5,5),
(10,5),
(3,6),
(1,6),
(10,7),
(9,8),
(1,9),
(1,10),
(3,11),
(2,11),
(3,12),
(1,12),
(12,13),
(1,1),
(15,1),
(14,1),
(15,2),
(14,2),
(14,3),
(14,5),
(14,8),
(15,10),
(14,10),
(15,7),
(14,7);
/*!40000 ALTER TABLE `m_application_workstation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_applications`
--

LOCK TABLES `m_applications` WRITE;
/*!40000 ALTER TABLE `m_applications` DISABLE KEYS */;
INSERT INTO `m_applications` (`id`, `name`, `description`, `vendor`, `product`, `security_need_c`, `responsible`, `functional_referent`, `type`, `icon_id`, `technology`, `external`, `users`, `editor`, `created_at`, `updated_at`, `deleted_at`, `entity_resp_id`, `application_block_id`, `documentation`, `security_need_i`, `security_need_a`, `security_need_t`, `security_need_auth`, `version`, `rto`, `rpo`, `install_date`, `update_date`, `attributes`, `patching_frequency`, `next_update`) VALUES (1,'Medical file','<p> Medical file management software </p>',NULL,NULL,4,'Rock','Jeans','Fat Customer',NULL,'Web','Internal','> 100','Tech24','2025-06-10 10:05:14','2025-06-18 08:28:48',NULL,15,3,'// Documentation/file_medical',4,4,4,NULL,NULL,60,240,'2025-01-01',NULL,'',NULL,NULL),
(2,'Compta+','<p> Accounting software </p>',NULL,NULL,3,'Sue','Rock','Software',NULL,'Web','Internal','10',NULL,'2025-06-12 11:56:56','2025-06-12 13:05:59',NULL,13,4,'// Share/Documentation/Compta',3,3,3,NULL,NULL,1440,1440,'2025-01-01',NULL,'',NULL,NULL),
(3,'Biblio+','<p> Application of medical publications management </p>',NULL,NULL,1,'Marc','Marc','Internal',NULL,'Web',NULL,'10',NULL,'2025-06-12 12:46:36','2025-06-12 13:02:56',NULL,9,3,NULL,1,1,1,NULL,NULL,4320,1440,'2025-01-01',NULL,'',NULL,NULL),
(4,'Guard','<p> Hospital guards management </p>',NULL,NULL,2,'David','Julian','Internal',NULL,'Web','Internal','> 100',NULL,'2025-06-12 13:16:32','2025-06-12 13:16:32',NULL,2,5,'// Share/Documentation/Guard',2,2,2,NULL,NULL,1440,1440,'2025-01-01',NULL,'',NULL,NULL),
(5,'Medilab','<p> Laboratory analysis management </p>',NULL,NULL,0,'Sophie',NULL,'Internal',NULL,'Web','Internal','10',NULL,'2025-06-12 13:19:03','2025-06-12 13:19:03',NULL,2,2,'// Share/Documentation/Medilab',0,0,0,NULL,NULL,0,0,'2025-01-01',NULL,'',NULL,NULL),
(6,'Apache','<p> Apache web server </p>',NULL,NULL,2,'Henri',NULL,'Software',NULL,'Software','external','> 100','Apache Fundation','2025-06-12 13:44:03','2025-06-12 13:44:03',NULL,2,6,'/Share/Doc/Website',2,2,2,NULL,NULL,1440,1440,'2025-01-01',NULL,'',NULL,NULL),
(7,'Jdev','<p> Java development application </p>',NULL,NULL,1,'Nicolas','Nicolas','Fat Customer',NULL,'Software','Internal','5','Jdev','2025-06-12 13:50:59','2025-06-12 13:50:59',NULL,2,6,'// Share/Documentation/JDEV',1,1,1,NULL,NULL,1440,1440,'2025-01-01',NULL,'',NULL,NULL),
(8,'Gitlab','<p> IT sources management </p>',NULL,NULL,1,'Nicolas','Nicolas','Software',NULL,'Software','Internal','10','Gitlab','2025-06-12 13:52:22','2025-06-18 08:29:34',NULL,2,6,'// Share/Documentation/Gitlab',1,1,1,NULL,NULL,1440,1440,'2025-01-01',NULL,'',NULL,NULL),
(9,'Rxmaker','<p> Medical imaging application </p>',NULL,NULL,3,'Carole','Sylvie','Internal',NULL,'Software','Internal','10','Big Elec','2025-06-12 13:54:46','2025-06-12 13:54:46',NULL,2,3,'// Documentation/RX',3,3,3,NULL,NULL,120,120,'2025-01-01',NULL,'',NULL,NULL),
(10,'Pharamaamag','<p> Pharmacy management </p>',NULL,NULL,3,'Anne','Anne','Software',NULL,'Fat Customer','Internal','30','Pharamaker','2025-06-12 13:57:51','2025-06-12 13:57:51',NULL,2,3,NULL,3,3,3,NULL,NULL,120,120,'2025-01-01',NULL,'',NULL,NULL),
(11,'Salarypay','<p> Pay management application </p>',NULL,NULL,3,'Speedwell','Speedwell','Internal Dev',NULL,'Web','Internal','10','Openhosp','2025-06-12 15:28:22','2025-06-12 17:08:10',NULL,17,5,'// Documentation/Salarypay',3,2,3,NULL,NULL,2880,240,'2025-01-01',NULL,'',NULL,NULL),
(12,'Jobs','<p> Application of recruitment management </p>',NULL,NULL,3,'Speedwell','Speedwell','Software',NULL,'Web','Internal','10','Openhosp','2025-06-12 17:03:00','2025-06-12 17:06:35',NULL,2,5,NULL,3,3,3,NULL,NULL,1440,1440,'2025-01-01',NULL,'',NULL,NULL),
(13,'Syncad','<p> Active directory synchronization </p>',NULL,NULL,3,'Marc','Julian','Internal Dev',NULL,'Job','Internal','5','Openhosp','2025-06-12 17:33:13','2025-06-12 17:33:13',NULL,2,6,'// Documentation/Jobs',3,3,3,NULL,NULL,1440,1440,'2025-01-01',NULL,'',NULL,NULL),
(14,'LIBEROFICE','<p> Text </p>',NULL,NULL,1,'Carole','Marc',NULL,NULL,NULL,NULL,'> 100','Apache Fundation','2025-06-14 05:50:17','2025-06-14 05:50:17',NULL,2,1,NULL,1,1,1,NULL,NULL,1440,1440,NULL,NULL,'',NULL,NULL),
(15,'Free','<p> Calculation sheet </p>',NULL,NULL,1,'Carole','Marc',NULL,NULL,NULL,NULL,'> 100','Apache Fundation','2025-06-14 05:51:20','2025-06-14 05:51:20',NULL,2,1,NULL,1,1,1,NULL,NULL,1440,1440,NULL,NULL,'',NULL,NULL);
/*!40000 ALTER TABLE `m_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `macro_processuses`
--

LOCK TABLES `macro_processuses` WRITE;
/*!40000 ALTER TABLE `macro_processuses` DISABLE KEYS */;
INSERT INTO `macro_processuses` (`id`, `name`, `description`, `io_elements`, `security_need_c`, `owner`, `created_at`, `updated_at`, `deleted_at`, `security_need_i`, `security_need_a`, `security_need_t`, `security_need_auth`) VALUES (1,'Macro Process 1','<p> Description of the test macro-processor <br> Test only </p>','<ul> <li> Data 1 </li> <li> Data 2 </li> <li> Data 3 </li></ul>',2,'test owner','2020-06-10 07:02:16','2020-06-22 06:07:55','2020-06-22 06:07:55',NULL,NULL,NULL,NULL),
(2,'Maro-processes 2','<p> Description of the macro-processes </p>',NULL,2,NULL,'2020-06-13 01:03:42','2020-06-22 06:07:55','2020-06-22 06:07:55',NULL,NULL,NULL,NULL),
(3,'Care',NULL,NULL,2,NULL,'2020-08-21 08:32:46','2020-08-21 08:44:59','2020-08-21 08:44:59',NULL,NULL,NULL,NULL),
(4,'Human resources','<p> Human resources </p>',NULL,2,NULL,'2020-08-21 08:34:19','2020-08-21 08:41:36','2020-08-21 08:41:36',NULL,NULL,NULL,NULL),
(5,'Operate the hospital','<p> Support process for care and management: all of the processes that contribute to the smooth running of other processes, providing them with the necessary, both material and immaterial resources. </p>','<p> Entrant: <br>- Resource needs <br> outgoing: <br>- Allocation of resources <br>- Reporting on quality of care </p>',3,'Administrative and financial director','2020-08-21 08:38:01','2025-06-14 19:00:27',NULL,3,3,3,NULL),
(6,'Lead the hospital','<p> Process that transcribe the strategy, the objectives and allow you to control the quality approach while ensuring its continuous improvement. </p>','<p> incoming: & nbsp; </p> <ul> <li> Information on the functioning of processes </li> </ul> <p> outgoing: </p> <ul> <li> Reports </li> <li> Dashboards </li> </ul>',3,'Administrative and financial director','2020-08-21 08:43:31','2025-06-14 19:00:10',NULL,3,3,2,NULL),
(7,'Treat the patient','<p> Patient management process in hospitalization, surgery, outpatient and emergencies </p>','<p> incoming: </p> <ul> <li> Patient name </li> <li> Social security number </li> <li> Patient address </li> </ul> <p> outgoing: </p> <ul> <li> Diagnosis </li> <li> PRESCRIPTION </li> </ul>',3,'Medical director','2020-08-21 08:44:47','2025-06-14 19:00:15',NULL,3,3,3,NULL),
(8,'Treat the patient','<p> All clinical support processes: hospital hygiene, laboratory, medication circuit and sterilization </p>','<p> Entrance: <br>- Order <br>- Medical needs <br> Exit: <br>- Provived care <br>- Medication <br>- Support for care </p>',3,'Medical director','2020-09-07 07:22:46','2025-06-14 19:00:21',NULL,3,3,3,NULL),
(9,'Hospital management',NULL,NULL,2,NULL,'2020-09-07 07:28:46','2020-09-07 08:05:55','2020-09-07 08:05:55',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `macro_processuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `man_wan`
--

LOCK TABLES `man_wan` WRITE;
/*!40000 ALTER TABLE `man_wan` DISABLE KEYS */;
/*!40000 ALTER TABLE `man_wan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `mans`
--

LOCK TABLES `mans` WRITE;
/*!40000 ALTER TABLE `mans` DISABLE KEYS */;
/*!40000 ALTER TABLE `mans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `network_switch_physical_switch`
--

LOCK TABLES `network_switch_physical_switch` WRITE;
/*!40000 ALTER TABLE `network_switch_physical_switch` DISABLE KEYS */;
/*!40000 ALTER TABLE `network_switch_physical_switch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `network_switches`
--

LOCK TABLES `network_switches` WRITE;
/*!40000 ALTER TABLE `network_switches` DISABLE KEYS */;
/*!40000 ALTER TABLE `network_switches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `networks`
--

LOCK TABLES `networks` WRITE;
/*!40000 ALTER TABLE `networks` DISABLE KEYS */;
INSERT INTO `networks` (`id`, `name`, `protocol_type`, `responsible`, `responsible_sec`, `security_need_c`, `description`, `created_at`, `updated_at`, `deleted_at`, `security_need_i`, `security_need_a`, `security_need_t`, `security_need_auth`) VALUES (1,'OpenHospi-Inta','TCP/IP','Paul','Jeans',3,'<p> Hospital internal network </p>','2025-06-12 11:41:43','2025-06-12 11:42:02',NULL,3,3,3,NULL);
/*!40000 ALTER TABLE `networks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `operation_task`
--

LOCK TABLES `operation_task` WRITE;
/*!40000 ALTER TABLE `operation_task` DISABLE KEYS */;
INSERT INTO `operation_task` (`operation_id`, `task_id`) VALUES (1,1),
(1,2),
(2,1),
(3,3);
/*!40000 ALTER TABLE `operation_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `operations`
--

LOCK TABLES `operations` WRITE;
/*!40000 ALTER TABLE `operations` DISABLE KEYS */;
INSERT INTO `operations` (`id`, `name`, `description`, `process_id`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Operation 1','<p> Description of the operation </p>',NULL,'2020-06-13 00:02:42','2020-06-22 06:12:11','2020-06-22 06:12:11'),
(2,'Operation 2','<p> Description of the operation </p>',NULL,'2020-06-13 00:02:58','2020-06-22 06:12:11','2020-06-22 06:12:11'),
(3,'Operation 3','<p> Descipement of the operation </p>',NULL,'2020-06-13 00:03:11','2020-06-22 06:12:11','2020-06-22 06:12:11'),
(4,'Test operation','<p> Description Test </p>',NULL,'2020-07-16 06:53:24','2020-07-24 09:42:13','2020-07-24 09:42:13'),
(5,'Helpdesk','<p> IT support to users </p>',NULL,'2020-08-13 05:44:38','2020-08-13 05:48:19','2020-08-13 05:48:19'),
(6,'Assets inventory','<p> Maintaining the IT inventory </p>',NULL,'2020-08-13 06:35:04','2020-08-13 06:37:29',NULL),
(7,'Inventory review','<p> Review of the Inventory of IT assets </p>',NULL,'2020-08-13 06:36:28','2020-08-13 06:36:28',NULL),
(8,'Incident and request encoding','<p> Capture incident resolution requests and service requests </p> <p> Identification (documentation) </p> <p> categorization (routing to the corresponding frontline group) </p> <p> prioritization (Emergency management of the incident or request) </p>',NULL,'2020-09-16 12:19:26','2020-09-16 12:19:26',NULL);
/*!40000 ALTER TABLE `operations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `peripherals`
--

LOCK TABLES `peripherals` WRITE;
/*!40000 ALTER TABLE `peripherals` DISABLE KEYS */;
/*!40000 ALTER TABLE `peripherals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `phones`
--

LOCK TABLES `phones` WRITE;
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;
/*!40000 ALTER TABLE `phones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_links`
--

LOCK TABLES `physical_links` WRITE;
/*!40000 ALTER TABLE `physical_links` DISABLE KEYS */;
INSERT INTO `physical_links` (`id`, `src_port`, `dest_port`, `peripheral_src_id`, `phone_src_id`, `physical_router_src_id`, `physical_security_device_src_id`, `physical_server_src_id`, `physical_switch_src_id`, `storage_device_src_id`, `wifi_terminal_src_id`, `workstation_src_id`, `logical_server_src_id`, `network_switch_src_id`, `router_src_id`, `peripheral_dest_id`, `phone_dest_id`, `physical_router_dest_id`, `physical_security_device_dest_id`, `physical_server_dest_id`, `physical_switch_dest_id`, `storage_device_dest_id`, `wifi_terminal_dest_id`, `workstation_dest_id`, `logical_server_dest_id`, `network_switch_dest_id`, `router_dest_id`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'2025-06-14 05:59:26','2025-06-14 05:59:26',NULL),
(2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,'2025-06-14 05:59:38','2025-06-14 05:59:38',NULL),
(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,'2025-06-14 05:59:55','2025-06-14 05:59:55',NULL),
(4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,'2025-06-14 06:00:06','2025-06-14 06:00:06',NULL),
(5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,'2025-06-14 06:00:18','2025-06-14 06:00:18',NULL),
(6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,'2025-06-14 06:00:31','2025-06-14 06:00:31',NULL),
(7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,'2025-06-14 06:02:12','2025-06-14 06:02:12',NULL),
(8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,'2025-06-14 06:02:28','2025-06-14 06:02:54',NULL),
(9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,'2025-06-14 06:03:18','2025-06-14 06:03:18',NULL),
(10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,NULL,NULL,NULL,'2025-06-14 06:04:06','2025-06-14 06:04:06',NULL),
(11,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,NULL,NULL,NULL,'2025-06-14 06:04:29','2025-06-14 06:05:08',NULL),
(12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,NULL,NULL,NULL,'2025-06-14 06:05:31','2025-06-14 06:05:31',NULL),
(13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,NULL,NULL,NULL,'2025-06-14 06:05:51','2025-06-14 06:05:51',NULL),
(14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,NULL,NULL,NULL,'2025-06-14 06:06:19','2025-06-14 06:06:19',NULL),
(15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,12,NULL,NULL,NULL,'2025-06-14 06:06:37','2025-06-14 06:06:37',NULL),
(16,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-14 06:07:09','2025-06-14 06:07:09',NULL),
(17,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-14 06:07:18','2025-06-14 06:07:18',NULL),
(18,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-14 06:07:33','2025-06-14 06:07:33',NULL),
(19,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-14 06:07:43','2025-06-14 06:07:43',NULL),
(20,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,'2025-06-14 06:07:58','2025-06-14 06:07:58',NULL),
(21,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-14 06:08:11','2025-06-14 06:08:11',NULL),
(22,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,NULL,NULL,NULL,'2025-06-14 06:08:34','2025-06-14 06:08:34',NULL),
(23,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,'2025-06-14 06:08:45','2025-06-14 06:08:45',NULL),
(24,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-14 06:09:15','2025-06-14 06:09:15',NULL),
(25,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-14 06:09:24','2025-06-14 06:09:24',NULL),
(26,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-14 06:09:34','2025-06-14 06:09:34',NULL),
(27,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-14 06:09:43','2025-06-14 06:09:43',NULL);
/*!40000 ALTER TABLE `physical_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_router_router`
--

LOCK TABLES `physical_router_router` WRITE;
/*!40000 ALTER TABLE `physical_router_router` DISABLE KEYS */;
INSERT INTO `physical_router_router` (`router_id`, `physical_router_id`) VALUES (1,1);
/*!40000 ALTER TABLE `physical_router_router` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_router_vlan`
--

LOCK TABLES `physical_router_vlan` WRITE;
/*!40000 ALTER TABLE `physical_router_vlan` DISABLE KEYS */;
/*!40000 ALTER TABLE `physical_router_vlan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_routers`
--

LOCK TABLES `physical_routers` WRITE;
/*!40000 ALTER TABLE `physical_routers` DISABLE KEYS */;
INSERT INTO `physical_routers` (`id`, `description`, `vendor`, `product`, `version`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `name`) VALUES (1,'<p> Main Open Router </p>',NULL,NULL,NULL,'Nore','2025-06-14 05:47:32','2025-06-14 05:47:32',NULL,1,12,1,'R01');
/*!40000 ALTER TABLE `physical_routers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_security_devices`
--

LOCK TABLES `physical_security_devices` WRITE;
/*!40000 ALTER TABLE `physical_security_devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `physical_security_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_servers`
--

LOCK TABLES `physical_servers` WRITE;
/*!40000 ALTER TABLE `physical_servers` DISABLE KEYS */;
INSERT INTO `physical_servers` (`id`, `name`, `icon_id`, `description`, `vendor`, `product`, `version`, `responsible`, `configuration`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `physical_switch_id`, `type`, `address_ip`, `cpu`, `memory`, `disk`, `disk_used`, `operating_system`, `install_date`, `update_date`, `patching_group`, `paching_frequency`, `next_update`, `cluster_id`) VALUES (1,'Mainframe01',NULL,'<p> Super Server 01 </p>',NULL,NULL,NULL,'John','<p> Mac: 123.456.789.065 </p>','2025-06-11 10:25:20','2025-06-12 17:57:42',NULL,1,12,3,NULL,NULL,'10.10.1.1','12','4','1024','532','AS/300',NULL,NULL,NULL,NULL,NULL,NULL),
(2,'BigCluster01',NULL,'<p> Big Cluster Master & Nbsp; </p>',NULL,NULL,NULL,'Nestor',NULL,'2025-06-11 12:57:47','2025-06-18 08:33:37',NULL,1,12,1,NULL,'Hal','10.30.4.5','48','512','1024','304','OS34','2025-01-01 00:00:00',NULL,NULL,NULL,NULL,1),
(3,'Datastore',NULL,'<p> The Database Server </p>',NULL,NULL,NULL,'Paul',NULL,'2025-06-11 12:58:58','2025-06-11 12:58:58',NULL,1,12,2,NULL,'Db','10.50.3.1','4','64','3045','2025','DB23',NULL,NULL,NULL,NULL,NULL,NULL),
(4,'Backup',NULL,'<p> The Backup Server </p>',NULL,NULL,NULL,'John',NULL,'2025-06-11 13:00:19','2025-06-14 18:27:34',NULL,1,12,2,NULL,'Storage','10.10.34.3','4','64','5673','2132','OS34','2025-01-01 00:00:00',NULL,NULL,NULL,NULL,NULL),
(5,'BigCluster02',NULL,'<p> Big Cluster Slavé </p>',NULL,NULL,NULL,NULL,NULL,'2025-06-12 17:54:40','2025-06-18 08:33:37',NULL,1,12,1,NULL,'Hal',NULL,'48','512','1024','394','OS34','2025-01-01 00:00:00',NULL,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `physical_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_switches`
--

LOCK TABLES `physical_switches` WRITE;
/*!40000 ALTER TABLE `physical_switches` DISABLE KEYS */;
INSERT INTO `physical_switches` (`id`, `name`, `description`, `vendor`, `product`, `version`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`) VALUES (1,'SW01','<p> Switch 1st floor </p>',NULL,NULL,NULL,'Nortel','2025-06-14 05:56:03','2025-06-14 05:58:42',NULL,1,1,NULL),
(2,'SW02','<p> Switch 2nd floor </p>',NULL,NULL,NULL,'Nortel','2025-06-14 05:56:19','2025-06-14 05:58:30',NULL,1,6,NULL),
(3,'SW03','<p> Switch 3rd floor </p>',NULL,NULL,NULL,'Nortel','2025-06-14 05:57:18','2025-06-14 05:57:27',NULL,1,15,NULL),
(4,'SW04','<p> Switch 4th floor </p>',NULL,NULL,NULL,'Nortel','2025-06-14 05:58:16','2025-06-14 05:58:16',NULL,1,11,NULL);
/*!40000 ALTER TABLE `physical_switches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `processes`
--

LOCK TABLES `processes` WRITE;
/*!40000 ALTER TABLE `processes` DISABLE KEYS */;
INSERT INTO `processes` (`id`, `name`, `icon_id`, `description`, `owner`, `security_need_c`, `in_out`, `created_at`, `updated_at`, `deleted_at`, `macroprocess_id`, `security_need_i`, `security_need_a`, `security_need_t`, `security_need_auth`) VALUES (1,'Process 1',NULL,'<p> Description of the process 1 </p>','Chem - Billing',3,'<ul> <li> Apples </li> <li> Pears </li> <li> Cerises </li></ul>','2020-06-17 14:36:24','2020-06-22 06:12:00','2020-06-22 06:12:00',NULL,3,3,3,NULL),
(2,'Process 2',NULL,'<p> Description of the 2 </p> process','Chem - Admission',3,NULL,'2020-06-17 14:36:58','2020-06-22 06:12:00','2020-06-22 06:12:00',NULL,3,3,3,NULL),
(3,'Visitors',NULL,NULL,NULL,3,NULL,'2020-06-22 13:49:28','2020-06-22 13:49:46','2020-06-22 13:49:46',NULL,3,3,3,NULL),
(4,'Human Resources',NULL,NULL,NULL,3,NULL,'2020-06-22 13:50:04','2020-08-21 08:34:48','2020-08-21 08:34:48',NULL,3,3,3,NULL),
(5,'EMERGENCIES',NULL,'<p> Home and management of patients addressed to emergencies / who come to the emergency room </p>','Medical director',3,'<p> Entrance: & nbsp; <br>- Patients </p> <p> output: <br>- Favated care </p>','2020-06-22 13:50:19','2025-06-14 19:00:15',NULL,7,3,3,3,NULL),
(6,'Communication unit',NULL,'<p> Internal and external communication management </p>','Administrative and Financial Department',3,'<p> Entrance: <br>- Need for communication <br>- Context of the organization <br> Exit: <br>- Communications <br>- Reporting </p>','2020-06-22 14:43:24','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(7,'Organization and performance',NULL,'<p> Quality/Relations with patients </p> <p> also contains controlling </p>','Administrative and Financial Department',3,'<p> Entrant: <br>- & nbsp; Data on the operation of the hospital </p> <p> outgoing: <br>- Statistics <br>- Risk analyzes <br>- Reports </p>','2020-06-22 14:50:06','2025-06-14 19:00:10',NULL,6,3,3,3,NULL),
(8,'Care',NULL,NULL,NULL,3,NULL,'2020-06-22 14:50:23','2020-08-21 08:46:12','2020-08-21 08:46:12',NULL,3,3,3,NULL),
(9,'Computer science',NULL,'<p> Chem computer service </p>','Chem cio',3,'<p> Entrance: & nbsp; <br>- Information technology use needs <br> output: & nbsp; <br>- Covered needs </p>','2020-06-24 06:20:23','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(10,'Security',NULL,'<p> Physical security and hospital environment </p>','Administrative and Financial Department',3,'<p> Entrance: & nbsp; <br>- Physical and environmental safety needs <br> output: & nbsp; <br>- Applied physical and approximate security </p>','2020-07-31 11:51:06','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(11,'Finances and reception patients',NULL,'<p> is responsible for: </p> <ul> <li> General accounting </li> <li> Supplier accounts </li> <li> Billing </li> <li> Front Office </li> <li> Back Office & amp; Telephony </li> </ul>','Administrative and Financial Department',3,'<p> in: <br>- Budgets <br>- Invoice <br>- Validated offer <br> Out: <br>- Accounting report </p>','2020-07-31 11:53:40','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(12,'Medico-technical',NULL,'<p> Management of medical and care equipment </p>','Administrative and Financial Department',3,'<p> Entrance: <br>- needs of medical equipment <br> output: & nbsp; <br>- Managed equipment </p>','2020-07-31 12:05:45','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(13,'Hotel industry',NULL,'<p> is responsible for & nbsp ;: </p> <ul> <li> Catering </li> <li> Cleaning </li> </ul>','Administrative and Financial Department',3,'<p> Entrance: <br>- Hoteling services needs <br> Output: & nbsp; <br>- Hotel needs covered </p>','2020-07-31 12:06:49','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(14,'Hospital management',NULL,'<p> <strong> The </strong> process </strong> of "<strong> piloting </strong>" contribute to the determination of corporate strategy and the deployment of objectives in the organization. & nbsp; <br> under the responsibility of management, they make it possible to guide and ensure the consistency of <strong> process </strong>','Administrative and Financial Department',3,'<p> in: <br>- Hospital data (datawarehouse) <br> out: <br>- Piloting reports </p>','2020-08-03 07:32:25','2025-06-14 19:00:10',NULL,6,3,3,3,NULL),
(15,'Recruitment',NULL,NULL,NULL,3,NULL,'2020-08-21 08:35:10','2020-08-21 08:41:20','2020-08-21 08:41:20',NULL,3,3,3,NULL),
(16,'Admission',NULL,'<p> Admission and appointment </p>',NULL,3,NULL,'2020-08-21 08:47:46','2020-09-07 07:53:43','2020-09-07 07:53:43',NULL,3,3,3,NULL),
(17,'Bed management',NULL,NULL,NULL,3,NULL,'2020-08-21 08:48:30','2020-09-07 08:02:12','2020-09-07 08:02:12',NULL,3,3,3,NULL),
(18,'Hospitalization',NULL,'<p> Management of patients in hospitalization </p>','Medical director',3,'<p> incoming: <br>- Patients </p> <p> output <br>- FORDIVED CARE </p>','2020-08-21 08:51:29','2025-06-14 19:00:15',NULL,7,3,3,3,NULL),
(19,'Ambulatory',NULL,'<p> Patient management services for examinations, therapies, consultations, care outside hospitalization </p>','Medical director',3,'<p> Entrant: <br>- Patients <br>- Pathologies <br> Exit: <br> Favored care </p>','2020-08-21 08:51:40','2025-06-14 19:00:15',NULL,7,3,3,3,NULL),
(20,'IT security',NULL,'<p> IT security </p>','Chem cio',3,NULL,'2020-08-21 08:54:25','2020-08-24 06:15:57','2020-08-24 06:15:57',NULL,3,3,3,NULL),
(21,'Laboratory',NULL,'<p> Management of medical prescriptions, analyzes and communications of results </p>','Medical director',3,'<p> Entrant: & nbsp; <br>- Prescriptions <br> output: <br>- Analysis results </p>','2020-08-21 08:58:05','2025-06-14 19:00:21',NULL,8,3,3,3,NULL),
(22,'E-learning training',NULL,NULL,NULL,3,NULL,'2020-08-21 09:01:30','2021-02-08 06:20:07','2021-02-08 06:20:07',5,3,3,3,NULL),
(23,'Pharmacy',NULL,NULL,NULL,3,NULL,'2020-08-21 09:15:08','2020-09-07 08:08:45','2020-09-07 08:08:45',NULL,3,3,3,NULL),
(24,'Medical information',NULL,'<p> Medical Information Department </p>','Medical director',3,'<p> Entrance: & nbsp; <br>- Medical codification <br>- Medical acts provided by chem <br> output: & nbsp; <br>- Codified acts </p>','2020-08-21 09:16:07','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(25,'Legal unit',NULL,NULL,'Directorate General',3,NULL,'2020-08-21 09:17:32','2020-09-07 08:01:46','2020-09-07 08:01:46',NULL,3,3,3,NULL),
(26,'Infrastructure and logistics',NULL,'<p> is responsible for: </p> <ul> <li> Building </li> <li> Preventive maintenance </li> <li> Construction </li> <li> Safety & amp; Environment </li></ul>','Administrative and Financial Department',3,'<p> Entrance: <br>- Infrastructure and logistics needs <br> output: <br>- Covered needs </p>','2020-08-21 10:22:58','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(27,'Logistics and catering',NULL,'<p> is responsible for: </p> <ul> <li> Logistics transport and patients </li> <li> Purchases and store </li></ul>','Administrative and Financial Department',3,'<p> Entrance: & nbsp; <br>- Transport and catering needs <br> output: <br>- Besoisn covered </p>','2020-08-21 10:23:50','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(28,'Human resources',NULL,'<p> Human resources management of chem </p>','Human Resources Department',3,'<p> in: <br>- Recruitment request <br>- ... <br> out: & nbsp; <br>- Time management <br>- Staff at the position <br>- Salary sheets </p>','2020-08-21 10:30:24','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(29,'Data protection',NULL,'<p> Ensure good compliance with the General Data Protection Regulations (GDPR) at Chem. </p>','Dpo',3,'<p> in: <br>- CONTAIN OF CHEM </p> <p> out: <br>- Recommendations for conformity </p> <p> & nbsp; </p>','2020-08-24 06:16:37','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(30,'Sterilization',NULL,'<p> sterilization service </p>','Medical director',3,'<p> Entrance: <br>- Sterilization requests <br> Sortié: <br>- Sterilized equipment </p>','2020-09-07 07:23:04','2025-06-14 19:00:21',NULL,8,3,3,3,NULL),
(31,'Hospital hygiene',NULL,'<p> Hospital hygiene </p>','Medical director',2,'<p> Entrance: & nbsp; <br>- Hygiene needs <br> output: <br>- Applied hygiene rules </p>','2020-09-07 07:23:37','2025-06-14 19:00:21',NULL,8,2,2,3,NULL),
(32,'Medicines circuit',NULL,'<p> Medicines management </p>','Medical director',3,'<p> Entrant: & nbsp; <br>- Ordinances <br> Out: <br>- Drugs <br>- Reporting </p>','2020-09-07 07:23:48','2025-06-14 19:00:21',NULL,8,3,3,3,NULL),
(33,'Construction',NULL,'<p> Department of Chem Construction </p>','Administrative and Financial Department',3,'<p> in: <br>- Plan <br>- Change <br> Out: <br>- Field of work </p>','2020-09-07 08:04:54','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(34,'Infrastructure',NULL,'<p> Management of Chem Technical Infrastructure </p>','Administrative and Financial Department',3,'<p> Entrance: <br>- Technical needs <br>- Standards and standards to be observed <br> Output: <br>- Functional technical installations </p>','2020-09-07 08:05:20','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(35,'Finances',NULL,NULL,NULL,3,NULL,'2020-09-07 08:05:32','2020-09-07 08:11:17','2020-09-07 08:11:17',5,3,3,3,NULL),
(36,'Purchases',NULL,'<p> Purchasing service </p>','Administrative and Financial Department',3,'<p> in: <br>- Purchase request </p> <p> out: <br>- Purchase invoices </p>','2020-09-07 08:05:45','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(37,'Cleaning',NULL,'<p> Chem cleaning service </p>','Administrative and Financial Department',3,'<p> Entrance: <br>- Cleaning needs <br> output: & nbsp; <br>- Treaty areas </p>','2020-09-07 08:10:26','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(38,'Waste',NULL,'<p> Waste management of the hospital center </p>','Medical director',3,'<p> Entrance: & nbsp; <br>- Waste <br> Exit: <br>- Recycled waste/Treaties </p>','2020-09-07 08:30:29','2025-06-14 19:00:27',NULL,5,3,3,3,NULL),
(39,'Information management',NULL,'<p> Medical information management </p>','Administrative and Financial Department',3,'<p> in: <br>- International standards <br>- Medical acts carried out <br> out: & nbsp; <br>- Codification of medical acts </p>','2020-09-07 08:53:46','2025-06-14 19:00:10',NULL,6,3,3,3,NULL);
/*!40000 ALTER TABLE `processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `relation_values`
--

LOCK TABLES `relation_values` WRITE;
/*!40000 ALTER TABLE `relation_values` DISABLE KEYS */;
INSERT INTO `relation_values` (`relation_id`, `date_price`, `price`, `created_at`, `updated_at`) VALUES (1,'2025-01-01',65000.00,'2025-06-14 18:30:45','2025-06-14 18:30:45'),
(2,'2025-01-01',125000.00,'2025-06-14 18:32:03','2025-06-14 18:32:03'),
(3,'2025-01-01',12000.00,'2025-06-14 18:33:26','2025-06-14 18:33:26'),
(4,'2025-01-01',75000.00,'2025-06-14 18:35:36','2025-06-14 18:35:36'),
(5,'2025-01-01',20000.00,'2025-06-14 18:36:59','2025-06-14 18:36:59'),
(6,'2025-12-01',65000.00,'2025-06-14 18:38:20','2025-06-14 18:38:20'),
(7,'2025-01-01',125000.00,'2025-06-14 18:39:56','2025-06-14 18:39:56'),
(8,'2025-04-01',25000.00,'2025-06-14 18:44:20','2025-06-14 18:44:20');
/*!40000 ALTER TABLE `relation_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `relations`
--

LOCK TABLES `relations` WRITE;
/*!40000 ALTER TABLE `relations` DISABLE KEYS */;
INSERT INTO `relations` (`id`, `importance`, `name`, `type`, `description`, `created_at`, `updated_at`, `deleted_at`, `source_id`, `destination_id`, `attributes`, `reference`, `responsible`, `order_number`, `active`, `start_date`, `end_date`, `comments`, `security_need_c`, `security_need_i`, `security_need_a`, `security_need_t`, `security_need_auth`) VALUES (1,2,'Medi+ licenses','Supplier','<p> Forunature of Medi Licens+</p>','2025-06-14 18:30:24','2025-06-14 18:30:45',NULL,9,2,'Valid','1234567','Paul','1234567',1,'2025-01-01','2025-12-31',NULL,NULL,NULL,NULL,NULL,NULL),
(2,3,'SUPORT RX','Supplier','<p> Medical imaging support - 24x7 </p>','2025-06-14 18:32:03','2025-06-14 18:32:03',NULL,10,17,'Valid','1235948453','Henri','432043284382',1,'2025-01-01','2025-12-31',NULL,NULL,NULL,NULL,NULL,NULL),
(3,2,'Consulting Mission LTR','Advice','<p> IT advice </p>','2025-06-14 18:33:26','2025-06-14 18:33:26',NULL,11,2,'Valid','43943284320','Rock','32443929432',1,'2025-01-01','2025-04-01',NULL,NULL,NULL,NULL,NULL,NULL),
(4,3,'Pharmaman support','Supplier','<p> Pharmaman software support - 24x7 </p>','2025-06-14 18:35:36','2025-06-14 18:35:36',NULL,1,4,'Valid','43943294329','Sophie','943294329432',1,'2025-01-01','2025-12-31',NULL,NULL,NULL,NULL,NULL,NULL),
(5,3,'Accounting logie','Supplier','<p> Accounting software license </p>','2025-06-14 18:36:59','2025-06-14 18:36:59',NULL,12,3,'Valid','42343243232','Henri','443224432',1,'2025-01-01','2025-12-31',NULL,NULL,NULL,NULL,NULL,NULL),
(6,3,'SUPORT Tech24','Supplier','<p> 24x7 medical file software support </p>','2025-06-14 18:38:20','2025-06-14 18:38:20',NULL,15,3,'Valid','43294329432','Rock','424329439',1,'2025-01-01','2025-12-31',NULL,NULL,NULL,NULL,NULL,NULL),
(7,3,'Mainframe maintenance','Maintenance','<p> 24x7 maintenance of Mainrame </p>','2025-06-14 18:39:56','2025-06-14 18:39:56',NULL,13,2,'Valid','439432943','Paul','1044384833',1,'2025-01-01','2025-12-31',NULL,NULL,NULL,NULL,NULL,NULL),
(8,3,'Aboratory analysis','Partnership','<p> Laboratory analysis </p>','2025-06-14 18:44:20','2025-06-14 18:44:20',NULL,14,4,'Valid','5943548354','Julie','60545965945',1,'2025-04-01','2026-04-01',NULL,NULL,NULL,NULL,NULL,NULL),
(9,3,'Filming','Member','<p> Member of the Open Hospital Federation </p>','2025-06-14 18:45:24','2025-06-14 18:45:36',NULL,3,16,'','5439555453','Nathalie','06544569456',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `routers`
--

LOCK TABLES `routers` WRITE;
/*!40000 ALTER TABLE `routers` DISABLE KEYS */;
INSERT INTO `routers` (`id`, `name`, `description`, `rules`, `created_at`, `updated_at`, `deleted_at`, `ip_addresses`, `type`) VALUES (1,'R01','<p> Principal wheel of Open Hosital </p>',NULL,'2025-06-12 11:45:47','2025-06-14 09:16:07',NULL,'10.10.5.25',NULL);
/*!40000 ALTER TABLE `routers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `security_control_m_application`
--

LOCK TABLES `security_control_m_application` WRITE;
/*!40000 ALTER TABLE `security_control_m_application` DISABLE KEYS */;
INSERT INTO `security_control_m_application` (`security_control_id`, `m_application_id`) VALUES (7,6),
(5,6),
(6,6),
(2,6),
(3,1),
(10,1),
(1,1),
(7,1),
(5,1),
(9,1),
(6,1),
(8,1),
(2,1),
(4,1),
(6,3),
(8,3);
/*!40000 ALTER TABLE `security_control_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `security_control_process`
--

LOCK TABLES `security_control_process` WRITE;
/*!40000 ALTER TABLE `security_control_process` DISABLE KEYS */;
INSERT INTO `security_control_process` (`security_control_id`, `process_id`) VALUES (3,36),
(10,36),
(1,36),
(7,36),
(9,36),
(6,36),
(8,36),
(2,36),
(4,36);
/*!40000 ALTER TABLE `security_control_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `security_controls`
--

LOCK TABLES `security_controls` WRITE;
/*!40000 ALTER TABLE `security_controls` DISABLE KEYS */;
INSERT INTO `security_controls` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Roles based access control (RBAC)','<p> Only authorized professionals can access the data according to their function (e.g. a doctor sees his patients, but not those of others). </p> <p> implemented via Active Directory, SSO, badge, etc. </p>','2025-06-14 11:22:50','2025-06-14 11:26:02',NULL),
(2,'Traceability of access and journalization','<p> All access to medical records and critical systems are recorded. </p> <p> The newspapers are kept, analyzed and regularly audited to detect abuses or incidents. </p>','2025-06-14 11:23:12','2025-06-14 11:26:29',NULL),
(3,'Strong authentication','<p> Use of complex passwords, two factors authentication (2FA) for sensitive access (e.g. remote DPI, administrator access). </p>','2025-06-14 11:23:27','2025-06-14 11:23:27',NULL),
(4,'Automatic sessions locking','<p> The workstations lock automatically after a few minutes of inactivity, in order to protect visible screens in the care areas. </p>','2025-06-14 11:23:45','2025-06-14 11:23:45',NULL),
(5,'Insulation of testing environments','<p> Development or testing environments do not use real patient data, or they are anonymized/pseudonymized. </p>','2025-06-14 11:24:00','2025-06-14 11:25:45',NULL),
(6,'Regular and tested backups','<p> encrypted, daily, deported backups, with regular restoration tests. </p> <p> Objective: resilience in front of claims (fire, ransomware, etc.). </p>','2025-06-14 11:24:15','2025-06-14 11:24:15',NULL),
(7,'Management of authorizations','<p> Habilitations assigned according to the post sheet, revised at each departure or change of function. </p> <p> Monitoring and traceability of access requests. </p>','2025-06-14 11:24:34','2025-06-14 11:26:15',NULL),
(8,'Staff awareness and training','<p> Annual SE security training and the GDPR for all hospital agents. </p> <p> Display of clear instructions on confidentiality in the premises. </p>','2025-06-14 11:24:53','2025-06-14 11:26:22',NULL),
(9,'Continuity and activity plan (PCA/PRA)','<p> written procedures and tested to continue care in the event of computer failure (e.g. paper forms, emergency telephony, access to the DPI in reading alone, etc.). </p>','2025-06-14 11:25:08','2025-06-14 11:25:08',NULL),
(10,'Encryption of posts and exchanges','<p> encryption of hard disks of mobile posts (laptops, tablets). </p> <p> Use of TLS encryption for inter-application and healthy health messaging (MSSANTE). </p>','2025-06-14 11:25:26','2025-06-14 11:25:56',NULL);
/*!40000 ALTER TABLE `security_controls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `security_devices`
--

LOCK TABLES `security_devices` WRITE;
/*!40000 ALTER TABLE `security_devices` DISABLE KEYS */;
INSERT INTO `security_devices` (`id`, `name`, `description`, `vendor`, `product`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Ids-rock','<p> Network intrusion detection equipment </p>',NULL,NULL,NULL,'2025-06-12 11:46:25','2025-06-12 11:46:25',NULL);
/*!40000 ALTER TABLE `security_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sites`
--

LOCK TABLES `sites` WRITE;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
INSERT INTO `sites` (`id`, `name`, `icon_id`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'The Open Hospital',NULL,'<p> Address: <br> 1, rue de la Santé <br> 1024 Bonuses </p>','2025-06-10 08:31:32','2025-06-11 10:23:30',NULL);
/*!40000 ALTER TABLE `sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `storage_devices`
--

LOCK TABLES `storage_devices` WRITE;
/*!40000 ALTER TABLE `storage_devices` DISABLE KEYS */;
INSERT INTO `storage_devices` (`id`, `name`, `type`, `description`, `vendor`, `product`, `version`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `address_ip`) VALUES (1,'Store01','Hal','<p> Big Disk Storage </p>',NULL,NULL,NULL,'2025-06-14 05:46:40','2025-06-14 09:18:32',NULL,1,12,2,'10.10.25.25');
/*!40000 ALTER TABLE `storage_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `subnetworks`
--

LOCK TABLES `subnetworks` WRITE;
/*!40000 ALTER TABLE `subnetworks` DISABLE KEYS */;
INSERT INTO `subnetworks` (`id`, `description`, `address`, `ip_allocation_type`, `responsible_exp`, `dmz`, `wifi`, `name`, `created_at`, `updated_at`, `deleted_at`, `connected_subnets_id`, `gateway_id`, `zone`, `vlan_id`, `network_id`, `default_gateway`) VALUES (1,'<p> Demilitarized area of ​​the hospital </p>','10.10.25.0/24','Static','Paul','Yes','No','OPENHOSP-DMZ','2025-06-12 11:43:52','2025-06-14 09:25:24',NULL,NULL,NULL,'Zone2',4,1,'10.10.25.1'),
(2,'<p> Laboratory network area </p>','10.10.8.0/24','Static','Paul','No','No','OpenHosp-Lab','2025-06-12 11:44:27','2025-06-14 09:24:53',NULL,NULL,NULL,'No',1,1,'10.10.8.1'),
(3,'<p> PC network </p>','10.10.2.0/24','Static','Paul','Yes','No','OPENHOP-LAN','2025-06-14 08:57:49','2025-06-14 09:25:10',NULL,NULL,NULL,'Zone1',3,1,'10.10.2.1'),
(4,'<p> Sub Network administration </p>','10.10.5.0/24','Dynamic','Paul','Yes','No','OpenHosp-Addin','2025-06-14 09:05:42','2025-06-14 09:23:39',NULL,NULL,NULL,'Dmz',2,1,'10.10.5.1');
/*!40000 ALTER TABLE `subnetworks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Task 2','Description of task 2','2020-06-13 00:04:07','2020-06-22 06:12:15','2020-06-22 06:12:15'),
(2,'TAP 1','Description of task 1','2020-06-13 00:04:21','2020-06-22 06:12:15','2020-06-22 06:12:15'),
(3,'Task 3','Description of task 3','2020-06-13 00:04:41','2020-06-22 06:12:15','2020-06-22 06:12:15');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `vlans`
--

LOCK TABLES `vlans` WRITE;
/*!40000 ALTER TABLE `vlans` DISABLE KEYS */;
INSERT INTO `vlans` (`id`, `name`, `description`, `vlan_id`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'VLAN-LAB','<p> VLAN LABORATORY </p>',1,'2025-06-12 11:54:55','2025-06-12 11:54:55',NULL),
(2,'VLAN-Admin','<p> VLAN administration </p>',10,'2025-06-14 09:19:18','2025-06-14 09:19:18',NULL),
(3,'VLAN-PC','<p> VLAN DES PC </p>',15,'2025-06-14 09:19:53','2025-06-14 09:19:53',NULL),
(4,'VLAN-SRV','<p> VLAN Servers </p>',17,'2025-06-14 09:26:07','2025-06-14 09:26:07',NULL);
/*!40000 ALTER TABLE `vlans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `wans`
--

LOCK TABLES `wans` WRITE;
/*!40000 ALTER TABLE `wans` DISABLE KEYS */;
/*!40000 ALTER TABLE `wans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `wifi_terminals`
--

LOCK TABLES `wifi_terminals` WRITE;
/*!40000 ALTER TABLE `wifi_terminals` DISABLE KEYS */;
INSERT INTO `wifi_terminals` (`id`, `name`, `description`, `vendor`, `product`, `version`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `address_ip`) VALUES (1,'WiFi-Dir','<p> WiFi terminal Direction </p>',NULL,NULL,NULL,'Notel','2025-06-12 12:31:17','2025-06-14 09:15:17',NULL,1,13,'10.10.5.11'),
(2,'Wifi-development','<p> Laboratory wifi terminal </p>',NULL,NULL,NULL,'Notel','2025-06-12 12:32:22','2025-06-14 09:15:39',NULL,1,6,'10.10.5.14'),
(3,'WiFi-Guets','<p> Borde WiFi Patients </p>',NULL,NULL,NULL,'Notel','2025-06-12 12:33:16','2025-06-14 09:15:27',NULL,1,1,'10.10.5.13');
/*!40000 ALTER TABLE `wifi_terminals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `workstations`
--

LOCK TABLES `workstations` WRITE;
/*!40000 ALTER TABLE `workstations` DISABLE KEYS */;
INSERT INTO `workstations` (`id`, `name`, `description`, `vendor`, `product`, `version`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `physical_switch_id`, `type`, `icon_id`, `operating_system`, `address_ip`, `cpu`, `memory`, `disk`) VALUES (1,'PC034','<p> PC welcome </p>',NULL,NULL,NULL,'2025-06-14 05:35:44','2025-06-14 08:59:15',NULL,1,1,NULL,'Black',NULL,'Windows','10.10.2.35','i5','4',120),
(2,'PC035','<p> PC consultation </p>',NULL,NULL,NULL,'2025-06-14 05:36:26','2025-06-14 08:59:42',NULL,1,3,NULL,'Black',NULL,'Windows','10.10.2.36','i5','4',120),
(3,'PC037','<p> PC consultation </p>',NULL,NULL,NULL,'2025-06-14 05:37:02','2025-06-14 09:00:03',NULL,1,4,NULL,'Black',NULL,'Windows','10.10.2.37','i5','4',120),
(4,'PC038','<p> PC Emergencies </p>',NULL,NULL,NULL,'2025-06-14 05:37:37','2025-06-14 09:00:16',NULL,1,5,NULL,'Black',NULL,'Windows','10.10.2.38','i5','4',120),
(5,'PC040','<p> PC Labo </p>',NULL,NULL,NULL,'2025-06-14 05:38:30','2025-06-14 09:00:33',NULL,1,6,NULL,'Black',NULL,'Windows','10.10.8.5','i5','4',120),
(6,'PC041','<p> PC Labo </p>',NULL,NULL,NULL,'2025-06-14 05:39:24','2025-06-14 09:00:57',NULL,1,6,NULL,'Black',NULL,'Windows','10.10.8.6','i5','4',120),
(7,'PC043','<p> PC Pharmacy </p>',NULL,NULL,NULL,'2025-06-14 05:40:04','2025-06-14 09:04:09',NULL,1,7,NULL,'Black',NULL,'Windows','10.10.8.7','i5','4',120),
(8,'PC044','<p> pc rx </p>',NULL,NULL,NULL,'2025-06-14 05:40:43','2025-06-14 09:01:37',NULL,1,8,NULL,'Black',NULL,'Windows','10.10.2.40','i5','4',120),
(9,'PC045','<p> PC Mediacal </p>',NULL,NULL,NULL,'2025-06-14 05:41:48','2025-06-14 09:02:06',NULL,1,9,NULL,'Black',NULL,'Windows','10.10.2.43','i5','4',120),
(10,'PC046','<p> MEDICAL PC </p>',NULL,NULL,NULL,'2025-06-14 05:42:30','2025-06-14 09:01:55',NULL,1,10,NULL,'Black',NULL,'Windows','10.10.2.41','i5','4',120),
(11,'PC047','<p> pc admin </p>',NULL,NULL,NULL,'2025-06-14 05:43:16','2025-06-14 09:02:17',NULL,1,11,NULL,'Black',NULL,'Windows','10.10.2.43','i5','4',120),
(12,'PC050','<p> pc dir </p>',NULL,NULL,NULL,'2025-06-14 05:44:20','2025-06-14 09:11:48',NULL,1,14,NULL,'Banana',NULL,'Windows','10.10.2.45','M4','8',120),
(13,'PC049','<p> Technical PC </p>',NULL,NULL,NULL,'2025-06-14 05:45:51','2025-06-14 09:11:19',NULL,1,13,NULL,'Black',NULL,'Windows','10.10.5.12','i5','4',120);
/*!40000 ALTER TABLE `workstations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `zone_admins`
--

LOCK TABLES `zone_admins` WRITE;
/*!40000 ALTER TABLE `zone_admins` DISABLE KEYS */;
INSERT INTO `zone_admins` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Openhospital','<p> Main area of ​​administration of open hospital </p>','2025-06-11 10:21:37','2025-06-12 11:22:07',NULL);
/*!40000 ALTER TABLE `zone_admins` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-18 14:11:18
