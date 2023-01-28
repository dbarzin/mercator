-- MySQL dump 10.13  Distrib 8.0.31, for Linux (x86_64)
--
-- Host: localhost    Database: mercator
-- ------------------------------------------------------
-- Server version	8.0.31-0ubuntu0.22.04.1

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
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Activité 1','<p>Description de l\'activité 1</p>','2020-06-10 13:20:42','2020-06-10 13:20:42',NULL),(2,'Activité 2','<p>Description de l\'activité de test</p>','2020-06-10 15:44:26','2020-06-13 04:03:26',NULL),(3,'Activité 3','<p>Description de l\'activité 3</p>','2020-06-13 04:57:08','2020-06-13 04:57:08',NULL),(4,'Activité 4','<p>Description de l\'acivité 4</p>','2020-06-13 04:57:24','2020-06-13 04:57:24',NULL),(5,'Activité principale','<p>Description de l\'activité principale</p>','2020-08-15 04:19:53','2020-08-15 04:19:53',NULL),(6,'AAA','test a1','2021-03-22 19:06:55','2021-03-22 19:07:00','2021-03-22 19:07:00'),(7,'AAA','test AAA','2021-03-22 19:13:43','2021-03-22 19:14:05','2021-03-22 19:14:05'),(8,'AAA','test 2 aaa','2021-03-22 19:14:16','2021-03-22 19:14:45','2021-03-22 19:14:45'),(9,'AAA1','test 3 AAA','2021-03-22 19:14:40','2021-03-22 19:19:09','2021-03-22 19:19:09'),(10,'Activité 0','<p>Description de l\'activité zéro</p>',NULL,'2021-05-15 07:40:16',NULL),(11,'test','dqqsd','2021-08-02 20:03:46','2021-09-22 10:59:48','2021-09-22 10:59:48');
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_operation`
--

LOCK TABLES `activity_operation` WRITE;
/*!40000 ALTER TABLE `activity_operation` DISABLE KEYS */;
INSERT INTO `activity_operation` (`activity_id`, `operation_id`) VALUES (2,3),(1,1),(1,2),(4,3),(3,1),(1,5),(5,1),(10,1),(2,6),(3,4);
/*!40000 ALTER TABLE `activity_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_process`
--

LOCK TABLES `activity_process` WRITE;
/*!40000 ALTER TABLE `activity_process` DISABLE KEYS */;
INSERT INTO `activity_process` (`process_id`, `activity_id`) VALUES (1,1),(1,2),(2,3),(2,4),(3,2),(3,5),(4,5),(5,4),(6,4),(7,3),(8,4),(9,3),(1,10);
/*!40000 ALTER TABLE `activity_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `actor_operation`
--

LOCK TABLES `actor_operation` WRITE;
/*!40000 ALTER TABLE `actor_operation` DISABLE KEYS */;
INSERT INTO `actor_operation` (`operation_id`, `actor_id`) VALUES (2,1),(1,1),(1,4),(2,5),(3,6),(5,4);
/*!40000 ALTER TABLE `actor_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `actors`
--

LOCK TABLES `actors` WRITE;
/*!40000 ALTER TABLE `actors` DISABLE KEYS */;
INSERT INTO `actors` (`id`, `name`, `nature`, `type`, `contact`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Jean','Personne','interne','jean@testdomain.org','2020-06-14 11:02:22','2021-05-16 17:37:49',NULL),(2,'Service 1','Groupe','interne',NULL,'2020-06-14 11:02:39','2020-06-17 14:43:42','2020-06-17 14:43:42'),(3,'Service 2','Groupe','Interne',NULL,'2020-06-14 11:02:54','2020-06-17 14:43:46','2020-06-17 14:43:46'),(4,'Pierre','Personne','interne','email : pierre@testdomain.com','2020-06-17 14:44:01','2021-05-16 17:38:19',NULL),(5,'Jacques','personne','interne','Téléphone 1234543423','2020-06-17 14:44:23','2020-06-17 14:44:23',NULL),(6,'Fournisseur 1','entité','externe','Tel : 1232 32312','2020-06-17 14:44:50','2020-06-17 14:44:50',NULL);
/*!40000 ALTER TABLE `actors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `annuaires`
--

LOCK TABLES `annuaires` WRITE;
/*!40000 ALTER TABLE `annuaires` DISABLE KEYS */;
INSERT INTO `annuaires` (`id`, `name`, `description`, `solution`, `created_at`, `updated_at`, `deleted_at`, `zone_admin_id`) VALUES (1,'AD01','<p>Annuaire principal&nbsp;</p>','Acive Directory','2020-07-03 07:49:37','2022-03-22 19:33:39',NULL,1),(2,'Mercator','<p>Cartographie du système d\'information</p>','Logiciel développé maison','2020-07-03 10:24:48','2020-07-13 15:12:59',NULL,1);
/*!40000 ALTER TABLE `annuaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_blocks`
--

LOCK TABLES `application_blocks` WRITE;
/*!40000 ALTER TABLE `application_blocks` DISABLE KEYS */;
INSERT INTO `application_blocks` (`id`, `name`, `description`, `responsible`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Bloc applicatif 1','<p>Description du bloc applicatif</p>','Jean Pierre','2020-06-13 04:09:01','2020-06-13 04:09:01',NULL),(2,'Bloc applicatif 2','<p>Second bloc applicatif.</p>','Marcel pierre','2020-06-13 04:10:52','2020-06-17 16:13:33',NULL),(3,'Bloc applicatif 3','<p>Description du block applicatif 3</p>','Nestor','2020-08-29 12:00:10','2022-03-20 17:53:29',NULL);
/*!40000 ALTER TABLE `application_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_module_application_service`
--

LOCK TABLES `application_module_application_service` WRITE;
/*!40000 ALTER TABLE `application_module_application_service` DISABLE KEYS */;
INSERT INTO `application_module_application_service` (`application_service_id`, `application_module_id`) VALUES (4,1),(4,2),(3,3),(2,4),(1,5),(1,6),(5,2),(5,3),(6,2),(6,3),(7,2),(7,3),(8,2),(8,3),(9,2),(9,3),(10,2),(10,3),(11,2),(11,3);
/*!40000 ALTER TABLE `application_module_application_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_modules`
--

LOCK TABLES `application_modules` WRITE;
/*!40000 ALTER TABLE `application_modules` DISABLE KEYS */;
INSERT INTO `application_modules` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Module 1','<p>Description du module 1</p>','2020-06-13 09:55:34','2020-06-13 09:55:34',NULL),(2,'Module 2','<p>Description du module 2</p>','2020-06-13 09:55:45','2020-06-13 09:55:45',NULL),(3,'Module 3','<p>Description du module 3</p>','2020-06-13 09:56:00','2020-06-13 09:56:00',NULL),(4,'Module 4','<p>Description du module 4</p>','2020-06-13 09:56:10','2020-06-13 09:56:10',NULL),(5,'Module 5','<p>Description du module 5</p>','2020-06-13 09:56:20','2020-06-13 09:56:20',NULL),(6,'Module 6','<p>Description du module 6</p>','2020-06-13 09:56:32','2020-06-13 09:56:32',NULL);
/*!40000 ALTER TABLE `application_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_service_m_application`
--

LOCK TABLES `application_service_m_application` WRITE;
/*!40000 ALTER TABLE `application_service_m_application` DISABLE KEYS */;
INSERT INTO `application_service_m_application` (`m_application_id`, `application_service_id`) VALUES (2,3),(2,4),(1,3),(15,2),(15,3),(1,1),(4,11),(4,5),(2,7),(4,7),(1,10),(16,10),(16,11),(16,5),(16,6),(16,7),(16,9),(16,1),(16,2),(16,3),(16,4),(16,8),(35,11);
/*!40000 ALTER TABLE `application_service_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_services`
--

LOCK TABLES `application_services` WRITE;
/*!40000 ALTER TABLE `application_services` DISABLE KEYS */;
INSERT INTO `application_services` (`id`, `description`, `exposition`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'<p>Descrition du service applicatif 1</p>','cloud','SRV-1','2020-06-13 09:35:31','2021-08-03 18:50:33',NULL),(2,'<p>Description du service 2</p>','local','Service 2','2020-06-13 09:35:48','2020-06-13 09:35:48',NULL),(3,'<p>Description du service 3</p>','local','Service 3','2020-06-13 09:36:04','2020-06-13 09:43:05',NULL),(4,'<p>Description du service 4</p>','local','Service 4','2020-06-13 09:36:17','2020-06-13 09:36:17',NULL),(5,'<p>Service applicatif 4</p>','Extranet','SRV-4','2021-08-02 14:11:43','2021-08-17 08:24:10',NULL),(6,'<p>Service applicatif 4</p>',NULL,'SRV-5','2021-08-02 14:12:19','2021-08-02 14:12:19',NULL),(7,'<p>Service applicatif 4</p>',NULL,'SRV-6','2021-08-02 14:12:56','2021-08-02 14:12:56',NULL),(8,'<p>The service 99</p>','local','SRV-99','2021-08-02 14:13:39','2021-09-07 16:53:36',NULL),(9,'<p>Service applicatif 4</p>',NULL,'SRV-9','2021-08-02 14:14:27','2021-08-02 14:14:27',NULL),(10,'<p>Service applicatif 4</p>','Extranet','SRV-10','2021-08-02 14:15:21','2021-08-17 08:24:20',NULL),(11,'<p>Service applicatif 4</p>','Extranet','SRV-11','2021-08-02 14:16:34','2021-08-17 08:24:28',NULL);
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
INSERT INTO `bays` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `room_id`) VALUES (1,'BAIE 101','<p>Description de la baie 101</p>','2020-06-21 04:56:01','2021-10-19 16:45:21',NULL,7),(2,'BAIE 102','<p>Desciption baie 102</p>','2020-06-21 04:56:20','2020-06-21 04:56:20',NULL,1),(3,'BAIE 103','<p>Descripton baid 103</p>','2020-06-21 04:56:38','2020-06-21 04:56:38',NULL,1),(4,'BAIE 201','<p>Description baie 201</p>','2020-06-21 04:56:55','2020-06-21 04:56:55',NULL,2),(5,'BAIE 301','<p>Baie 301</p>','2020-07-15 18:03:07','2020-07-15 18:03:07',NULL,3),(6,'BAIE 501','<p>Baie 501</p>','2020-07-15 18:10:23','2020-07-15 18:10:23',NULL,5);
/*!40000 ALTER TABLE `bays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `buildings`
--

LOCK TABLES `buildings` WRITE;
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
INSERT INTO `buildings` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `camera`, `badge`) VALUES (1,'ROOM 100','<p>Description de la salle 100</p>','2020-06-21 04:37:21','2023-01-12 17:07:38',NULL,1,0,0),(2,'ROOM 200','<p>Description de la salle 200</p>','2020-06-21 04:37:36','2023-01-12 17:08:17',NULL,1,0,0),(3,'ROOM 300','<p>Description du building 3</p>','2020-06-21 04:37:48','2023-01-12 16:36:36',NULL,2,0,0),(4,'ROOM 400','<p>Description de la salle 400</p>','2020-06-21 04:38:03','2023-01-12 16:37:09',NULL,2,0,0),(5,'ROOM 500','<p>Description de la salle 500</p>','2020-06-21 04:38:16','2023-01-12 16:37:27',NULL,3,0,0),(6,'Test building','<p>Description</p>','2020-07-24 19:12:48','2020-07-24 19:14:08','2020-07-24 19:14:08',4,NULL,NULL),(7,'ROOM 000','<p>Description de la salle triple zéro</p>','2020-08-21 13:10:15','2023-01-12 17:08:01',NULL,1,0,0),(8,'test','<p>test</p>','2020-11-06 13:44:22','2020-11-06 14:26:18','2020-11-06 14:26:18',NULL,1,0),(9,'test2','<p>test2</p>','2020-11-06 13:59:45','2020-11-06 14:06:50','2020-11-06 14:06:50',NULL,NULL,NULL),(10,'test3','<p>fdfsdfsd</p>','2020-11-06 14:07:07','2020-11-06 14:26:18','2020-11-06 14:26:18',NULL,NULL,NULL),(11,'test4',NULL,'2020-11-06 14:25:52','2020-11-06 14:26:18','2020-11-06 14:26:18',NULL,0,0);
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
INSERT INTO `certificate_logical_server` (`certificate_id`, `logical_server_id`) VALUES (4,1),(5,2),(1,1),(2,1),(3,1),(7,1);
/*!40000 ALTER TABLE `certificate_logical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `certificate_m_application`
--

LOCK TABLES `certificate_m_application` WRITE;
/*!40000 ALTER TABLE `certificate_m_application` DISABLE KEYS */;
INSERT INTO `certificate_m_application` (`certificate_id`, `m_application_id`) VALUES (8,4);
/*!40000 ALTER TABLE `certificate_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `certificates`
--

LOCK TABLES `certificates` WRITE;
/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
INSERT INTO `certificates` (`id`, `name`, `type`, `description`, `start_validity`, `end_validity`, `last_notification`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES (1,'CERT01','DES3','<p>Certificat 01</p>','2020-10-27','2022-01-01','2022-09-13 22:48:31','2021-07-14 08:28:47','2022-09-13 20:48:31',NULL,0),(2,'CERT02','AES 256','<p>Certificat numéro 02</p>','2021-07-14','2021-07-17','2022-09-13 22:48:31','2021-07-14 08:33:33','2022-09-13 20:48:31',NULL,0),(3,'CERT03','AES 256','<p>Certificat numéro 3</p>','2021-09-23','2021-11-11','2022-09-13 22:48:31','2021-07-14 10:35:41','2022-09-13 20:48:31',NULL,0),(4,'CERT04','DES3','<p>Certificat interne DES 3</p>',NULL,NULL,NULL,'2021-07-14 10:40:15','2021-07-14 10:40:15',NULL,0),(5,'CERT05','RSA 128','<p>Clé 05 avec RSA</p>',NULL,NULL,NULL,'2021-07-14 10:45:00','2021-07-14 10:45:00',NULL,0),(6,'CERT07','DES3','<p>cert 7</p>',NULL,NULL,NULL,'2021-07-14 12:44:12','2021-07-14 12:44:12',NULL,0),(7,'CERT08','DES3','<p>Master cert 08</p>','2021-06-15','2022-08-11','2022-09-13 22:48:31','2021-08-11 18:33:42','2022-09-13 20:48:31',NULL,0),(8,'CERT09','DES3','<p>Test cert nine</p>','2021-09-25','2021-09-26','2022-09-13 22:48:31','2021-09-23 14:17:20','2022-09-13 20:48:31',NULL,0);
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_entity`
--

LOCK TABLES `database_entity` WRITE;
/*!40000 ALTER TABLE `database_entity` DISABLE KEYS */;
INSERT INTO `database_entity` (`database_id`, `entity_id`) VALUES (1,1),(3,1),(4,1),(5,1),(6,1);
/*!40000 ALTER TABLE `database_entity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_information`
--

LOCK TABLES `database_information` WRITE;
/*!40000 ALTER TABLE `database_information` DISABLE KEYS */;
INSERT INTO `database_information` (`database_id`, `information_id`) VALUES (1,1),(1,2),(1,3),(3,2),(3,3),(5,1),(4,2),(6,2),(6,3),(5,5);
/*!40000 ALTER TABLE `database_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_logical_server`
--

LOCK TABLES `database_logical_server` WRITE;
/*!40000 ALTER TABLE `database_logical_server` DISABLE KEYS */;
INSERT INTO `database_logical_server` (`database_id`, `logical_server_id`) VALUES (6,1);
/*!40000 ALTER TABLE `database_logical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_m_application`
--

LOCK TABLES `database_m_application` WRITE;
/*!40000 ALTER TABLE `database_m_application` DISABLE KEYS */;
INSERT INTO `database_m_application` (`m_application_id`, `database_id`) VALUES (2,3),(3,4),(3,1),(4,5),(15,5),(15,4),(16,1),(35,3),(1,6),(2,6),(3,6),(18,6),(35,6);
/*!40000 ALTER TABLE `database_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `databases`
--

LOCK TABLES `databases` WRITE;
/*!40000 ALTER TABLE `databases` DISABLE KEYS */;
INSERT INTO `databases` (`id`, `name`, `description`, `responsible`, `type`, `security_need_c`, `external`, `created_at`, `updated_at`, `deleted_at`, `entity_resp_id`, `security_need_i`, `security_need_a`, `security_need_t`) VALUES (1,'Database 1','<p>Description Database 1</p>','Paul','MySQL',1,'Interne','2020-06-17 14:18:48','2021-05-14 10:19:45',NULL,2,2,3,4),(3,'Database 2','<p>Description database 2</p>','Paul','MySQL',1,'Interne','2020-06-17 14:19:24','2021-05-14 10:29:47',NULL,1,1,1,1),(4,'MainDB','<p>description de la base de données</p>','Paul','Oracle',2,'Interne','2020-07-01 15:08:57','2021-08-20 01:52:23',NULL,1,2,2,2),(5,'DB Compta','<p>Base de donnée de la compta</p>','Paul','MariaDB',2,'Interne','2020-08-24 15:58:23','2022-03-21 17:13:10',NULL,18,2,2,2),(6,'Data Warehouse','<p>Base de données du datawarehouse</p>','Jean','Oracle',2,'Interne','2021-05-14 10:24:02','2022-03-21 17:13:24',NULL,1,2,2,2);
/*!40000 ALTER TABLE `databases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `dhcp_servers`
--

LOCK TABLES `dhcp_servers` WRITE;
/*!40000 ALTER TABLE `dhcp_servers` DISABLE KEYS */;
INSERT INTO `dhcp_servers` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `address_ip`) VALUES (1,'DHCP_1','<p>Serveur DHCP primaire</p>','2020-07-23 08:34:43','2021-10-19 09:03:07',NULL,'10.10.121.2'),(2,'DHCP_2','<p>Serveur DHCP secondaire</p>','2021-10-19 08:46:52','2021-10-19 09:23:36',NULL,'10.40.6.4');
/*!40000 ALTER TABLE `dhcp_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `dnsservers`
--

LOCK TABLES `dnsservers` WRITE;
/*!40000 ALTER TABLE `dnsservers` DISABLE KEYS */;
INSERT INTO `dnsservers` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `address_ip`) VALUES (1,'DNS_1','<p>Serveur DNS primaire</p>','2020-07-23 08:31:39','2021-11-16 16:55:11',NULL,'10.10.3.4'),(2,'DNS_2','<p>Serveur DNS secondaire</p>','2020-07-23 08:31:50','2021-10-19 09:10:45',NULL,'10.30.2.3'),(3,'DNS_3','<p>Troisième serveur DNS</p>','2021-10-19 08:39:25','2021-10-19 08:41:09',NULL,'10.10.10.1');
/*!40000 ALTER TABLE `dnsservers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `domaine_ad_forest_ad`
--

LOCK TABLES `domaine_ad_forest_ad` WRITE;
/*!40000 ALTER TABLE `domaine_ad_forest_ad` DISABLE KEYS */;
INSERT INTO `domaine_ad_forest_ad` (`forest_ad_id`, `domaine_ad_id`) VALUES (1,1),(2,1),(1,3),(2,5),(1,4);
/*!40000 ALTER TABLE `domaine_ad_forest_ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `domaine_ads`
--

LOCK TABLES `domaine_ads` WRITE;
/*!40000 ALTER TABLE `domaine_ads` DISABLE KEYS */;
INSERT INTO `domaine_ads` (`id`, `name`, `description`, `domain_ctrl_cnt`, `user_count`, `machine_count`, `relation_inter_domaine`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Dom1','<p>Domaine AD1</p>',3,2000,800,'Non','2020-07-03 07:51:06','2020-07-03 07:51:06',NULL),(2,'test domain','<p>this is a test</p>',NULL,NULL,NULL,NULL,'2021-05-27 13:24:52','2021-05-27 13:29:15','2021-05-27 13:29:15'),(3,'Dom2','<p>Second domaine active directory</p>',2,100,1,'Néant','2021-05-27 13:29:43','2021-05-27 13:29:43',NULL),(4,'Dom5','<p>Domaine cinq</p>',NULL,NULL,NULL,NULL,'2021-05-27 13:39:08','2021-05-27 13:39:08',NULL),(5,'Dom4','<p>Domaine quatre</p>',NULL,NULL,NULL,NULL,'2021-05-27 13:39:20','2021-05-27 13:39:20',NULL);
/*!40000 ALTER TABLE `domaine_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entities`
--

LOCK TABLES `entities` WRITE;
/*!40000 ALTER TABLE `entities` DISABLE KEYS */;
INSERT INTO `entities` (`id`, `name`, `security_level`, `contact_point`, `description`, `is_external`, `created_at`, `updated_at`, `deleted_at`, `entity_type`) VALUES (1,'MegaNet System','<p>ISO 27001</p>','<p>Helpdek<br>27, Rue des poire&nbsp;<br>12043 Mire-en-Mare le Bains</p><p>helpdesk@meganet.org</p>','<p>Fournisseur équipement réseau</p>',1,'2020-05-21 02:30:59','2022-06-01 15:54:45',NULL,'Revendeur'),(2,'Entité1','<p>Néant</p>','<ul><li>Commercial</li><li>Service Delivery</li><li>Helpdesk</li></ul>','<p>Entité de tests1</p>',1,'2020-05-21 02:31:17','2022-05-23 15:10:25',NULL,'Revendeur'),(3,'CHdN','3','RSSI du CHdN','<p>Centre Hospitalier du Nord</p>',0,'2020-05-21 02:43:41','2021-05-13 08:20:32','2021-05-13 08:20:32',NULL),(4,'Entité3','<p>ISO 9001</p>','<p>Point de contact de la troisième entité</p>','<p>Description de la troisième entité.</p>',1,'2020-05-21 02:44:03','2022-05-23 15:10:46',NULL,'Producteur'),(5,'entité6','<p>Néant</p>','<p>support_informatque@entite6.fr</p>','<p>Description de l\'entité six</p>',0,'2020-05-21 02:44:18','2022-06-09 16:11:36',NULL,'external department'),(6,'Entité4','<p>ISO 27001</p>','<p>Pierre Pinon<br>Tel: 00 34 392 484 22</p>','<p>Description de l\'entté quatre</p>',0,'2020-05-21 02:45:14','2021-05-23 13:01:17',NULL,NULL),(7,'Entité5','<p>Néant</p>','<p>Servicdesk@entite5.fr</p>','<p>Description de l\'entité 5</p>',0,'2020-05-21 03:38:41','2021-05-23 13:02:16',NULL,NULL),(8,'Entité2','<p>ISO 27001</p>','<p>Point de contact de l\'entité 2</p>','<p>Description de l\'entité 2</p>',1,'2020-05-21 03:54:22','2022-05-23 14:44:34',NULL,'Legal entity'),(9,'NetworkSys','<p>ISO 27001</p>','<p>support@networksys.fr</p>','<p>Description de l’entité NetworkSys</p>',0,'2020-05-21 06:25:15','2022-05-23 14:44:48',NULL,'Internal department'),(10,'Agence eSanté','<p>Néant</p>','<p>helpdesk@esante.lu</p>','<p>Agence Nationale des information partagées dans le domaine de la santé</p><ul><li>a</li><li>b</li><li>c</li></ul><p>+-------+<br>+ TOTO +<br>+-------+</p><p>&lt;&lt;&lt;&lt;&lt;&lt; &gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;</p>',0,'2020-05-21 06:25:26','2021-05-13 08:20:32','2021-05-13 08:20:32',NULL),(11,'Test','4',NULL,'<p>Test</p>',0,'2020-07-02 15:37:29','2020-07-02 15:37:44','2020-07-02 15:37:44',NULL),(12,'Pierre et fils','<p>Certifications :&nbsp;<br>- ISO 9001<br>- ISO 27001<br>- ISO 31000</p>','<p>Paul Pierre<br>Gérant<br>00 33 4943 432 423</p>','<p>Description de l\'entité de test</p>',1,'2020-07-06 13:37:54','2022-05-23 14:45:07',NULL,'Fournisseur'),(13,'Nestor','<p>Haut niveau</p>','<p>Paul, Pierre et Jean</p>','<p>Description de Nestor</p>',1,'2020-08-12 16:11:31','2022-05-23 14:41:44',NULL,'Fournisseur'),(14,'0001',NULL,NULL,'<p>rrzerze</p>',0,'2021-06-15 15:16:31','2021-06-15 15:17:08','2021-06-15 15:17:08',NULL),(15,'002',NULL,NULL,'<p>sdqsfsd</p>',0,'2021-06-15 15:16:41','2021-06-15 15:17:08','2021-06-15 15:17:08',NULL),(16,'003',NULL,NULL,'<p>dsqdsq</p>',0,'2021-06-15 15:16:51','2021-06-15 15:17:08','2021-06-15 15:17:08',NULL),(17,'004',NULL,NULL,'<p>dqqqsdqs</p>',0,'2021-06-15 15:17:01','2021-06-15 15:17:08','2021-06-15 15:17:08',NULL),(18,'Acme corp.','<p>None sorry...</p>','<p>Do not call me, I will call you back.</p>','<p>Looney tunes academy</p>',0,'2021-09-07 18:07:16','2022-07-11 15:58:43',NULL,'Producteur'),(19,'HAL','<p>Top security certification</p>','<p>hal@corp.com</p>','<b>test',0,'2021-09-07 18:08:56','2021-09-07 18:09:17',NULL,NULL),(20,'ATest1',NULL,NULL,NULL,0,'2022-04-25 12:43:46','2022-04-25 12:44:02','2022-04-25 12:44:02',NULL),(21,'ATest2',NULL,NULL,NULL,0,'2022-04-25 12:43:56','2022-04-25 12:44:02','2022-04-25 12:44:02',NULL),(22,'Hacker Studio','<p>All SANS certificates</p>','<p>Do not call us, we will call you back.</p><p>&nbsp;</p>','<b>test',1,'2022-06-02 11:56:32','2022-06-02 11:56:32',NULL,'Fournisseur'),(23,'World compagny','<p>Full protection</p>','<p>ping us at 8.8.8.8</p>','<p>Thebiggest compagny in the world</p>',0,'2022-06-22 17:20:11','2022-06-22 17:20:40',NULL,NULL);
/*!40000 ALTER TABLE `entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entity_m_application`
--

LOCK TABLES `entity_m_application` WRITE;
/*!40000 ALTER TABLE `entity_m_application` DISABLE KEYS */;
INSERT INTO `entity_m_application` (`m_application_id`, `entity_id`) VALUES (2,1),(2,2),(1,2),(1,8),(3,8),(4,8),(4,4),(16,2),(19,2),(19,8),(19,4),(19,6),(19,7),(35,2),(37,18);
/*!40000 ALTER TABLE `entity_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entity_process`
--

LOCK TABLES `entity_process` WRITE;
/*!40000 ALTER TABLE `entity_process` DISABLE KEYS */;
INSERT INTO `entity_process` (`process_id`, `entity_id`) VALUES (1,1),(2,1),(3,1),(1,13),(3,13),(4,1),(7,3),(9,4),(2,8),(4,6),(4,7),(9,5),(1,9),(2,9),(3,9),(4,9),(9,9),(1,12),(1,2),(4,18),(3,19),(1,22);
/*!40000 ALTER TABLE `entity_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `external_connected_entities`
--

LOCK TABLES `external_connected_entities` WRITE;
/*!40000 ALTER TABLE `external_connected_entities` DISABLE KEYS */;
INSERT INTO `external_connected_entities` (`id`, `name`, `description`, `type`, `entity_id`, `network_id`, `src`, `dest`, `contacts`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Entité externe 1','description entité 1','Token',2,1,'8.9.10.11','10.212.32.12','Marcel','2020-07-23 07:59:25','2022-08-11 19:35:57',NULL),(2,'Entité externe 2','description de la connexion','SSL-VPN',4,1,NULL,NULL,'it@external.corp','2021-08-17 17:52:26','2022-08-11 19:17:06',NULL),(3,'Test',NULL,'SSL-VPN',NULL,1,NULL,NULL,NULL,'2022-08-11 19:46:40','2022-08-11 19:47:15',NULL),(4,'Entité externe 3','Support application','site2site VPN',4,1,'8.8.8.8.8','10.23.21.1','David','2022-10-13 16:32:40','2022-10-13 16:32:40',NULL);
/*!40000 ALTER TABLE `external_connected_entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fluxes`
--

LOCK TABLES `fluxes` WRITE;
/*!40000 ALTER TABLE `fluxes` DISABLE KEYS */;
INSERT INTO `fluxes` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `application_source_id`, `service_source_id`, `module_source_id`, `database_source_id`, `application_dest_id`, `service_dest_id`, `module_dest_id`, `database_dest_id`, `crypted`, `bidirectional`, `nature`) VALUES (2,'FluxA','<p>Description du flux A</p>','2020-06-17 14:50:59','2021-09-29 06:02:26',NULL,1,NULL,NULL,NULL,2,NULL,NULL,NULL,0,1,'API'),(3,'FluxC','<p>Flux de test</p>','2020-07-07 13:58:22','2021-09-23 17:04:30',NULL,2,NULL,NULL,NULL,3,NULL,NULL,NULL,1,NULL,'API'),(4,'FluxB','<p>Flux de test 3</p>','2020-07-07 14:01:10','2022-10-13 16:54:12',NULL,NULL,NULL,4,NULL,NULL,NULL,5,NULL,1,1,'API'),(5,'Sync_DB','<p>Description du flux 01</p>','2020-07-23 10:44:35','2022-10-13 16:54:36',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,3,1,0,'API'),(6,'Flux_MOD_01','<p>Fuld module</p>','2020-07-23 10:48:20','2021-09-29 05:59:35',NULL,NULL,NULL,3,NULL,NULL,NULL,2,NULL,0,0,'API'),(7,'Flux_SER_01','Description du flux service 01','2020-07-23 10:51:41','2020-07-23 10:51:41',NULL,NULL,3,NULL,NULL,NULL,4,NULL,NULL,0,NULL,'API'),(8,'Fulx 07','Description du flux 07','2020-09-05 04:56:57','2020-09-05 04:57:36',NULL,NULL,1,NULL,NULL,NULL,2,NULL,NULL,1,NULL,'API'),(9,'FLux DB_02','<p>Description du flux 2</p>','2020-09-05 05:12:05','2023-01-27 16:09:23',NULL,NULL,NULL,4,NULL,NULL,NULL,2,NULL,1,0,'File copy'),(10,'SRV10_to_SRV11','<p>Transfert from SRV10 to SRV11</p>','2021-08-02 15:13:31','2021-08-02 15:13:31',NULL,NULL,10,NULL,NULL,NULL,11,NULL,NULL,0,NULL,'API'),(11,'ALL','<p>Le flux qui répond à tout</p>','2021-08-02 15:13:57','2023-01-27 16:09:06',NULL,18,NULL,NULL,NULL,NULL,NULL,2,NULL,1,1,'Manual'),(12,'SRV6_to_SRV10','<p>service 6 to service 10</p>','2021-08-02 15:14:36','2021-08-02 15:14:36',NULL,NULL,7,NULL,NULL,NULL,10,NULL,NULL,1,NULL,'API'),(13,'Syncy System',NULL,'2021-08-02 18:01:21','2021-08-02 18:01:21',NULL,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'API'),(14,'00001',NULL,'2021-09-01 07:00:09','2021-09-01 07:00:21','2021-09-01 07:00:21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'API'),(15,'0002',NULL,'2021-09-01 07:00:15','2021-09-01 07:00:21','2021-09-01 07:00:21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'API'),(16,'MainFlow','<p>The most critical flow</p>','2022-06-29 14:58:33','2022-06-29 14:58:33',NULL,1,NULL,NULL,NULL,2,NULL,NULL,NULL,0,0,'API'),(17,'TestFlux99','<p>Description du flux 99</p>','2022-07-12 13:15:11','2022-07-12 13:15:26',NULL,NULL,NULL,4,NULL,NULL,5,NULL,NULL,1,0,'API'),(18,'TEST','<p>mod4 → mod5</p>','2023-01-27 15:44:23','2023-01-27 15:46:17','2023-01-27 15:46:17',NULL,NULL,4,NULL,NULL,NULL,5,NULL,0,0,'API'),(19,'Test','<p>Test</p>','2023-01-27 16:04:36','2023-01-27 16:04:36',NULL,1,NULL,NULL,NULL,2,NULL,NULL,NULL,0,0,NULL);
/*!40000 ALTER TABLE `fluxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `forest_ads`
--

LOCK TABLES `forest_ads` WRITE;
/*!40000 ALTER TABLE `forest_ads` DISABLE KEYS */;
INSERT INTO `forest_ads` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `zone_admin_id`) VALUES (1,'AD1','<p>Foret de l\'AD 1</p>','2020-07-03 07:50:07','2020-07-03 07:50:29',NULL,1),(2,'AD2','<p>Foret de l\'AD2</p>','2020-07-03 07:50:19','2020-07-03 07:50:19',NULL,1);
/*!40000 ALTER TABLE `forest_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `gateways`
--

LOCK TABLES `gateways` WRITE;
/*!40000 ALTER TABLE `gateways` DISABLE KEYS */;
INSERT INTO `gateways` (`id`, `name`, `description`, `ip`, `authentification`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'GW01','<p>Gateway 01 vers réseau médor</p>','123.5.6.4/12','Carte à puce','2020-07-13 17:34:45','2020-07-13 17:34:45',NULL),(2,'Workspace One','<p>Test workspace One</p>','10.10.10.1','Token','2021-04-17 19:32:57','2021-04-17 19:40:31','2021-04-17 19:40:31'),(3,'PubicGW','<p>Public Gateway</p>','10.10.10.1','Token','2021-04-17 19:39:04','2021-04-17 19:40:25','2021-04-17 19:40:25'),(4,'PublicGW','<p>Public gateway</p>','8.8.8.8','Token','2021-04-17 19:40:48','2021-04-17 19:48:34',NULL),(5,'GW02','<p>Second gateway</p>','10.20.1.1','Token','2021-05-18 18:27:13','2022-06-02 18:16:26',NULL);
/*!40000 ALTER TABLE `gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `information`
--

LOCK TABLES `information` WRITE;
/*!40000 ALTER TABLE `information` DISABLE KEYS */;
INSERT INTO `information` (`id`, `name`, `description`, `owner`, `administrator`, `storage`, `security_need_c`, `sensitivity`, `constraints`, `created_at`, `updated_at`, `deleted_at`, `security_need_i`, `security_need_a`, `security_need_t`) VALUES (1,'Information 1','<p>Description de l\'information 1</p>','Luc',NULL,'externe',1,'Donnée à caractère personnel','<p>Description des contraintes règlementaires et normatives</p>','2020-06-13 00:06:43','2021-11-04 07:43:27',NULL,3,2,2),(2,'information 2','<p>Description de l\'information</p>','Nestor','Nom de l\'administrateur','externe',2,'Donnée à caractère personnel',NULL,'2020-06-13 00:09:13','2021-08-19 16:42:53',NULL,1,1,1),(3,'information 3','<p>Descripton de l\'information 3</p>','Paul','Jean','Local',4,'Donnée à caractère personnel',NULL,'2020-06-13 00:10:07','2021-09-28 17:42:07',NULL,4,3,4),(4,'Information de test','<p>decription du test</p>','RSSI','Paul','Local',1,'Technical',NULL,'2020-07-01 15:00:37','2021-08-19 16:45:52',NULL,1,1,1),(5,'Données du client','<p>Données d\'identification du client</p>','Nestor','Paul','Local',2,'Donnée à caractère personnel','<p>RGPD</p>','2021-05-14 10:50:09','2022-03-21 17:12:30',NULL,2,2,2);
/*!40000 ALTER TABLE `information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `information_process`
--

LOCK TABLES `information_process` WRITE;
/*!40000 ALTER TABLE `information_process` DISABLE KEYS */;
INSERT INTO `information_process` (`information_id`, `process_id`) VALUES (3,2),(4,3),(4,4),(4,1),(1,4),(2,9),(5,1),(5,2),(5,4),(5,9);
/*!40000 ALTER TABLE `information_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `lan_man`
--

LOCK TABLES `lan_man` WRITE;
/*!40000 ALTER TABLE `lan_man` DISABLE KEYS */;
INSERT INTO `lan_man` (`man_id`, `lan_id`) VALUES (1,1),(2,1),(2,2),(2,3);
/*!40000 ALTER TABLE `lan_man` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `lan_wan`
--

LOCK TABLES `lan_wan` WRITE;
/*!40000 ALTER TABLE `lan_wan` DISABLE KEYS */;
INSERT INTO `lan_wan` (`wan_id`, `lan_id`) VALUES (1,1);
/*!40000 ALTER TABLE `lan_wan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `lans`
--

LOCK TABLES `lans` WRITE;
/*!40000 ALTER TABLE `lans` DISABLE KEYS */;
INSERT INTO `lans` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'LAN_1','Lan principal','2020-07-22 05:42:00','2020-07-22 05:42:00',NULL),(2,'LAN_2','Second LAN','2021-06-23 19:19:38','2021-06-23 19:19:38',NULL),(3,'LAN_0','Lan zero','2021-06-23 19:20:04','2021-06-23 19:20:04',NULL);
/*!40000 ALTER TABLE `lans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_server_m_application`
--

LOCK TABLES `logical_server_m_application` WRITE;
/*!40000 ALTER TABLE `logical_server_m_application` DISABLE KEYS */;
INSERT INTO `logical_server_m_application` (`m_application_id`, `logical_server_id`) VALUES (2,1),(2,2),(3,2),(1,1),(18,4),(15,3),(4,2),(4,5),(18,6),(35,3);
/*!40000 ALTER TABLE `logical_server_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_server_physical_server`
--

LOCK TABLES `logical_server_physical_server` WRITE;
/*!40000 ALTER TABLE `logical_server_physical_server` DISABLE KEYS */;
INSERT INTO `logical_server_physical_server` (`logical_server_id`, `physical_server_id`) VALUES (2,1),(2,2),(1,1),(1,7),(3,8),(4,7),(5,8),(6,6);
/*!40000 ALTER TABLE `logical_server_physical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_servers`
--

LOCK TABLES `logical_servers` WRITE;
/*!40000 ALTER TABLE `logical_servers` DISABLE KEYS */;
INSERT INTO `logical_servers` (`id`, `name`, `description`, `net_services`, `configuration`, `created_at`, `updated_at`, `deleted_at`, `operating_system`, `address_ip`, `cpu`, `memory`, `environment`, `disk`, `install_date`, `update_date`) VALUES (1,'SRV-1','<p>Description du serveur 1</p>','DNS, HTTP, HTTPS','<p>Configuration du serveur 1</p>','2020-07-12 16:57:42','2021-08-17 13:13:21',NULL,'Windows 3.1','10.10.1.1, 10.10.10.1','2','8','PROD',60,NULL,NULL),(2,'SRV-2','<p>Description du serveur 2</p>','HTTPS, SSH','<p>Configuration par défaut</p>','2020-07-30 10:00:16','2021-08-17 18:17:41',NULL,'Windows 10','10.50.1.2','2','5','PROD',100,NULL,NULL),(3,'SRV-3','<p>Description du serveur 3</p>','HTTP, HTTPS',NULL,'2021-08-26 14:33:03','2021-08-26 14:33:38',NULL,'Ubuntu 20.04','10.70.8.3','4','16','PROD',80,NULL,NULL),(4,'SRV-42','<p><i>The Ultimate Question of Life, the Universe and Everything</i></p>',NULL,'<p>Full configuration</p>','2021-11-15 16:03:59','2022-03-20 11:39:54',NULL,'OS 42','10.0.0.42','42','42 G','PROD',42,NULL,NULL),(5,'SRV-4','<p>Description du serveur 4</p>',NULL,NULL,'2022-05-02 16:43:02','2022-05-02 16:49:34',NULL,'Ubunti 22.04 LTS','10.10.3.2','4','2','Dev',NULL,'2022-05-01 20:47:41','2022-05-02 20:47:47'),(6,'SRV-5','<p>Description server 5</p>',NULL,'<p>configuration goes here !</p>','2022-06-27 10:27:02','2022-06-27 10:27:02',NULL,'Ubunti 22.04 LTS','10.10.43.3','18','12','Integration',500,'2022-06-27 14:26:37',NULL);
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
-- Dumping data for table `m_application_process`
--

LOCK TABLES `m_application_process` WRITE;
/*!40000 ALTER TABLE `m_application_process` DISABLE KEYS */;
INSERT INTO `m_application_process` (`m_application_id`, `process_id`) VALUES (2,1),(2,2),(3,2),(1,1),(14,2),(4,3),(12,4),(16,1),(16,2),(16,3),(16,4),(16,9),(19,3),(19,4),(35,4);
/*!40000 ALTER TABLE `m_application_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_workstation`
--

LOCK TABLES `m_application_workstation` WRITE;
/*!40000 ALTER TABLE `m_application_workstation` DISABLE KEYS */;
INSERT INTO `m_application_workstation` (`m_application_id`, `workstation_id`) VALUES (1,1),(3,1),(15,4),(2,6),(2,12),(35,12);
/*!40000 ALTER TABLE `m_application_workstation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_applications`
--

LOCK TABLES `m_applications` WRITE;
/*!40000 ALTER TABLE `m_applications` DISABLE KEYS */;
INSERT INTO `m_applications` (`id`, `name`, `description`, `security_need_c`, `responsible`, `functional_referent`, `type`, `technology`, `external`, `users`, `editor`, `created_at`, `updated_at`, `deleted_at`, `entity_resp_id`, `application_block_id`, `documentation`, `security_need_i`, `security_need_a`, `security_need_t`, `version`, `rto`, `rpo`, `install_date`, `update_date`) VALUES (1,'Application 1','<p>Description de l\'application 1</p>',1,'Jacques, RSSI',NULL,'logiciel','Microsoft',NULL,'> 20',NULL,'2020-06-14 09:20:15','2022-12-17 11:38:11',NULL,23,3,'//Documentation/application1.docx',1,1,1,'1.2',3120,1800,NULL,NULL),(2,'Application 2','<p><i>Description</i> de l\'<strong>application</strong> 2</p>',2,'Pierre, Paul',NULL,'progiciel','martian','SaaS','>100',NULL,'2020-06-14 09:31:16','2022-06-11 11:03:09',NULL,18,1,'None',2,2,2,'1.0',3120,1800,NULL,NULL),(3,'Application 3','<p>Test application 3</p>',1,'RSSI',NULL,'progiciel','Microsoft','Interne','>100',NULL,'2020-06-17 17:33:41','2021-05-15 08:06:53',NULL,12,2,'Aucune',2,3,3,NULL,3120,1800,NULL,NULL),(4,'EG350','<p>Description app4</p>',2,'Pierre',NULL,'logiciel','Microsoft','Internl','>100',NULL,'2020-08-11 14:13:02','2022-06-25 12:31:56',NULL,1,2,'None',2,3,2,'1.0',3120,1800,NULL,NULL),(12,'SuperApp','<p>Super super application !</p>',1,'RSSI',NULL,'Web','Oracle','Interne',NULL,NULL,'2021-04-12 17:10:59','2021-06-23 19:33:15',NULL,1,2,NULL,1,1,1,NULL,3120,1800,NULL,NULL),(14,'Windows Calc','<p>Calculatrice windows</p>',2,'RSSI',NULL,'logiciel','Microsoft','Internl',NULL,NULL,'2021-05-13 08:15:27','2022-03-20 17:53:29',NULL,1,3,NULL,0,0,0,NULL,3120,1800,NULL,NULL),(15,'Compta','<p>Application de comptabilité</p>',3,'RSSI',NULL,'progiciel','Microsoft','Interne','>100',NULL,'2021-05-15 07:53:15','2021-05-15 07:53:15',NULL,1,2,NULL,4,2,3,NULL,3120,1800,NULL,NULL),(16,'Queue Manager','<p>Queue manager</p>',4,'Jacques',NULL,'logiciel','Internal Dev','Interne','>100',NULL,'2021-08-02 15:17:11','2022-06-11 09:49:17',NULL,1,1,'//Portal/QueueManager.doc',4,4,4,NULL,3120,1800,NULL,NULL),(18,'Application 42','<p>The Ultimate Question of Life, the Universe and Everything</p>',-1,'Johan, Marc',NULL,NULL,NULL,NULL,NULL,NULL,'2021-11-15 16:03:20','2022-06-11 10:51:56',NULL,2,NULL,NULL,-1,-1,0,NULL,3120,1800,NULL,NULL),(19,'Windows Word 98','<p>Traitement de texte Word</p>',1,'Johan, Marc',NULL,'progiciel','Microsoft','Interne',NULL,NULL,'2022-06-14 11:52:36','2022-06-14 11:52:58',NULL,18,1,NULL,1,1,1,NULL,3120,1800,'2022-06-14 15:52:14',NULL),(35,'Application 5','<p>Description</p>',0,'',NULL,NULL,'Microsoft','Interne','>100',NULL,'2022-06-28 05:59:28','2022-06-28 06:04:30',NULL,4,2,NULL,0,0,0,'1.5',3120,1800,NULL,NULL),(36,'Messagerie','<p>Internal mail system</p>',3,'RSSI',NULL,'Web','Microsoft','Internl','>100',NULL,'2022-12-17 14:11:18','2022-12-17 14:12:03','2022-12-17 14:12:03',18,1,NULL,3,3,3,'v1.0',3120,1800,NULL,NULL),(37,'Messagerie','<p>Internal mail system</p>',3,'',NULL,'Web','Microsoft','Internl','>100',NULL,'2022-12-17 14:12:12','2022-12-17 14:12:12',NULL,18,1,NULL,3,3,3,'v1.0',3120,1800,NULL,NULL);
/*!40000 ALTER TABLE `m_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `macro_processuses`
--

LOCK TABLES `macro_processuses` WRITE;
/*!40000 ALTER TABLE `macro_processuses` DISABLE KEYS */;
INSERT INTO `macro_processuses` (`id`, `name`, `description`, `io_elements`, `security_need_c`, `owner`, `created_at`, `updated_at`, `deleted_at`, `security_need_i`, `security_need_a`, `security_need_t`) VALUES (1,'Macro-Processus 1','<p>Description du macro-processus de test.</p>','<p>Entrant :</p><ul><li>donnée 1</li><li>donnée 2</li><li>donnée 3</li></ul><p>Sortant :</p><ul><li>donnée 4</li><li>donnée 5</li></ul>',4,'Nestor','2020-06-10 07:02:16','2021-05-14 13:29:36',NULL,3,2,1),(2,'Macro-Processus 2','<p>Description du macro-processus</p>','<p>Valeur de test</p>',1,'Simon','2020-06-13 01:03:42','2021-05-14 07:21:10',NULL,2,3,4),(3,'Valeur de test','<p>Valeur de test</p>','<p>Valeur de test</p>',3,'All','2020-08-09 05:32:37','2020-08-24 14:45:57','2020-08-24 14:45:57',NULL,NULL,NULL),(4,'Proc3','<p>dfsdf</p>','<p>dsfsdf</p>',0,NULL,'2020-08-31 14:13:55','2020-08-31 14:31:29','2020-08-31 14:31:29',NULL,NULL,NULL),(5,'Proc4','<p>dfsdf</p>','<p>dsfsdf</p>',0,NULL,'2020-08-31 14:19:32','2020-08-31 14:31:29','2020-08-31 14:31:29',NULL,NULL,NULL),(6,'Proc5','<p>dfsdf</p>','<p>dsfsdf</p>',0,NULL,'2020-08-31 14:29:20','2020-08-31 14:31:29','2020-08-31 14:31:29',NULL,NULL,NULL),(7,'MP1','<p>sdfsdfs</p>',NULL,0,NULL,'2020-08-31 14:31:40','2020-08-31 14:38:31','2020-08-31 14:38:31',NULL,NULL,NULL),(8,'MP2','<p>sdfsdfs</p>',NULL,0,NULL,'2020-08-31 14:37:39','2020-08-31 14:38:31','2020-08-31 14:38:31',NULL,NULL,NULL),(9,'MP3','<p>sdfsdfs</p>',NULL,0,NULL,'2020-08-31 14:38:06','2020-08-31 14:38:31','2020-08-31 14:38:31',NULL,NULL,NULL),(10,'Macro-Processus 3','<p>Description du troisième macro-processus</p>','<ul><li>un</li><li>deux</li><li>trois</li><li>quatre</li></ul>',2,'Nestor','2020-11-24 08:21:38','2021-05-14 07:20:55',NULL,2,2,2),(11,'Macro-Processus 4','<p>Description du macro processus quatre</p>','<ul><li>crayon</li><li>stylos</li><li>gommes</li></ul>',1,'Pirre','2021-05-14 07:19:51','2021-09-22 11:00:08','2021-09-22 11:00:08',1,1,1);
/*!40000 ALTER TABLE `macro_processuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `man_wan`
--

LOCK TABLES `man_wan` WRITE;
/*!40000 ALTER TABLE `man_wan` DISABLE KEYS */;
INSERT INTO `man_wan` (`wan_id`, `man_id`) VALUES (1,1);
/*!40000 ALTER TABLE `man_wan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `mans`
--

LOCK TABLES `mans` WRITE;
/*!40000 ALTER TABLE `mans` DISABLE KEYS */;
INSERT INTO `mans` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'MAN_1','2020-08-22 04:17:20','2020-08-22 04:17:20',NULL),(2,'MAN_2','2021-05-07 08:14:27','2021-05-07 08:23:23',NULL),(3,'Test1','2022-04-25 12:43:02','2022-04-25 12:52:49','2022-04-25 12:52:49'),(4,'Test2','2022-04-25 12:43:09','2022-04-25 12:52:49','2022-04-25 12:52:49');
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
-- Dumping data for table `network_switches`
--

LOCK TABLES `network_switches` WRITE;
/*!40000 ALTER TABLE `network_switches` DISABLE KEYS */;
INSERT INTO `network_switches` (`id`, `name`, `ip`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Switch de test','123.4.5.6','<p>Test</p>','2020-07-13 17:30:37','2020-07-13 17:30:37',NULL),(2,'Second switch','10.1.1.1','<p>Second commutateur de test</p>','2022-04-25 12:55:44','2022-04-25 12:55:44',NULL);
/*!40000 ALTER TABLE `network_switches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `networks`
--

LOCK TABLES `networks` WRITE;
/*!40000 ALTER TABLE `networks` DISABLE KEYS */;
INSERT INTO `networks` (`id`, `name`, `protocol_type`, `responsible`, `responsible_sec`, `security_need_c`, `description`, `created_at`, `updated_at`, `deleted_at`, `security_need_i`, `security_need_a`, `security_need_t`) VALUES (1,'Réseau 1','TCP','Pierre','Paul',1,'<p>Description du réseau 1</p>','2020-06-23 12:34:14','2021-09-22 10:20:11',NULL,2,3,4),(2,'Réseau 2','TCP','Johan','Jean-Marc',1,'<p>Description du réseau 2</p>','2020-07-01 15:45:41','2021-09-22 10:21:23',NULL,1,1,1),(3,'test',NULL,NULL,NULL,4,'<p>réseau test</p>','2021-09-22 10:30:23','2021-09-22 10:30:29','2021-09-22 10:30:29',4,4,4);
/*!40000 ALTER TABLE `networks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `operation_task`
--

LOCK TABLES `operation_task` WRITE;
/*!40000 ALTER TABLE `operation_task` DISABLE KEYS */;
INSERT INTO `operation_task` (`operation_id`, `task_id`) VALUES (1,1),(1,2),(2,1),(3,3),(4,2),(5,1),(5,2),(5,3);
/*!40000 ALTER TABLE `operation_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `operations`
--

LOCK TABLES `operations` WRITE;
/*!40000 ALTER TABLE `operations` DISABLE KEYS */;
INSERT INTO `operations` (`id`, `name`, `description`, `process_id`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Operation 1','<p>Description de l\'opération</p>',1,'2020-06-13 00:02:42','2022-12-17 14:37:37',NULL),(2,'Operation 2','<p>Description de l\'opération</p>',1,'2020-06-13 00:02:58','2022-12-17 14:37:45',NULL),(3,'Operation 3','<p>Desciption de l\'opération</p>',2,'2020-06-13 00:03:11','2022-12-17 14:35:58',NULL),(4,'Operation 4','<p>Description de l\'opération 4</p>',2,'2020-07-15 14:34:02','2022-12-17 14:37:29',NULL),(5,'Master operation','<p>Opération maitre</p>',1,'2020-08-15 04:01:40','2022-09-15 17:56:33',NULL),(6,'Operation zullu','<p>Opération de mouvement tactique.</p>',2,'2022-07-28 11:58:41','2022-12-17 14:37:54',NULL);
/*!40000 ALTER TABLE `operations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `peripherals`
--

LOCK TABLES `peripherals` WRITE;
/*!40000 ALTER TABLE `peripherals` DISABLE KEYS */;
INSERT INTO `peripherals` (`id`, `name`, `type`, `description`, `responsible`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`) VALUES (1,'PER_01','IBM 3400','<p>important peripheral</p>','Marcel','2020-07-25 06:18:40','2020-07-25 06:19:46',NULL,1,2,NULL),(2,'PER_02','IBM 5600','<p>Description</p>','Nestor','2020-07-25 06:19:18','2020-07-25 06:19:18',NULL,3,5,NULL),(3,'PER_03','HAL 8100','<p>Space device</p>','Niel','2020-07-25 06:19:58','2020-07-25 06:20:18',NULL,3,4,NULL);
/*!40000 ALTER TABLE `peripherals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `phones`
--

LOCK TABLES `phones` WRITE;
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;
INSERT INTO `phones` (`id`, `name`, `description`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `physical_switch_id`) VALUES (1,'Phone 01','<p>Téléphone de test</p>','MOTOROAL 3110','2020-07-21 05:16:46','2020-07-25 07:15:17',NULL,1,1,NULL),(2,'Phone 03','<p>Special AA phone</p>','Top secret red phne','2020-07-21 05:18:01','2020-07-25 07:25:38',NULL,2,4,NULL),(3,'Phone 02','<p>Description phone 02</p>','IPhone 2','2020-07-25 06:52:23','2020-07-25 07:25:19',NULL,2,3,NULL);
/*!40000 ALTER TABLE `phones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_links`
--

LOCK TABLES `physical_links` WRITE;
/*!40000 ALTER TABLE `physical_links` DISABLE KEYS */;
INSERT INTO `physical_links` (`id`, `src_port`, `dest_port`, `peripheral_src_id`, `phone_src_id`, `physical_router_src_id`, `physical_security_device_src_id`, `physical_server_src_id`, `physical_switch_src_id`, `storage_device_src_id`, `wifi_terminal_src_id`, `workstation_src_id`, `peripheral_dest_id`, `phone_dest_id`, `physical_router_dest_id`, `physical_security_device_dest_id`, `physical_server_dest_id`, `physical_switch_dest_id`, `storage_device_dest_id`, `wifi_terminal_dest_id`, `workstation_dest_id`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'2','1',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,'2023-01-11 16:43:55','2023-01-11 19:26:02',NULL),(2,'3','1',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,'2023-01-11 17:27:27','2023-01-11 19:26:21',NULL),(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,NULL,NULL,NULL,NULL,'2023-01-11 17:27:46','2023-01-11 17:27:46',NULL),(4,'1','1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,'2023-01-11 17:28:25','2023-01-11 19:23:56',NULL),(5,'5',NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:28:43','2023-01-12 17:50:04',NULL),(6,'4',NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,'2023-01-11 17:29:05','2023-01-12 17:49:26',NULL),(7,'2','1',NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,'2023-01-11 17:29:37','2023-01-12 17:31:51',NULL),(8,'2','1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'2023-01-11 17:30:13','2023-01-11 19:24:08',NULL),(9,'3','1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,'2023-01-11 17:30:28','2023-01-11 19:24:17',NULL),(10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:30:47','2023-01-11 17:30:47',NULL),(11,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,'2023-01-11 17:31:10','2023-01-11 17:31:10',NULL),(12,'4','1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:48:36','2023-01-11 19:24:29',NULL),(13,'1','1',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:49:06','2023-01-11 19:25:25',NULL),(14,'2','1',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,'2023-01-11 17:49:22','2023-01-11 19:25:37',NULL),(15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,NULL,NULL,NULL,NULL,'2023-01-11 17:49:44','2023-01-11 17:49:44',NULL),(16,'3','1',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,'2023-01-11 17:50:11','2023-01-11 19:25:48',NULL),(17,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,'2023-01-11 17:50:31','2023-01-11 17:51:00',NULL),(18,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2023-01-11 19:26:55','2023-01-11 19:26:55',NULL),(19,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,'2023-01-11 19:27:09','2023-01-11 19:27:09',NULL),(20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,'2023-01-11 19:27:24','2023-01-11 19:27:24',NULL),(21,'6',NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,'2023-01-11 19:29:54','2023-01-12 17:50:20',NULL),(22,'7',NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 19:32:04','2023-01-12 17:50:30',NULL),(23,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'2023-01-11 19:32:57','2023-01-11 19:32:57',NULL),(24,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,'2023-01-11 19:33:15','2023-01-11 19:33:15',NULL),(25,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2023-01-11 19:33:29','2023-01-11 19:33:29',NULL),(26,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,'2023-01-11 19:35:35','2023-01-11 19:35:35',NULL),(27,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,'2023-01-11 19:36:00','2023-01-11 19:36:00',NULL),(28,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 19:36:20','2023-01-11 19:36:20',NULL),(29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'2023-01-11 19:37:04','2023-01-11 19:37:04',NULL),(30,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'2023-01-11 19:37:21','2023-01-11 19:37:21',NULL),(31,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'2023-01-11 19:37:37','2023-01-11 19:37:37',NULL),(32,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'2023-01-11 19:38:16','2023-01-11 19:38:16',NULL),(33,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 19:38:29','2023-01-11 19:38:29',NULL),(34,'8',NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'2023-01-12 17:48:22','2023-01-12 17:50:38',NULL);
/*!40000 ALTER TABLE `physical_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_router_vlan`
--

LOCK TABLES `physical_router_vlan` WRITE;
/*!40000 ALTER TABLE `physical_router_vlan` DISABLE KEYS */;
INSERT INTO `physical_router_vlan` (`physical_router_id`, `vlan_id`) VALUES (1,1),(1,3),(2,3);
/*!40000 ALTER TABLE `physical_router_vlan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_routers`
--

LOCK TABLES `physical_routers` WRITE;
/*!40000 ALTER TABLE `physical_routers` DISABLE KEYS */;
INSERT INTO `physical_routers` (`id`, `description`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `name`) VALUES (1,'<p>Routeur prncipal</p>','Fortinet','2020-07-10 06:58:53','2021-10-12 19:08:21',NULL,1,1,1,'R1'),(2,'<p>Routeur secondaire</p>','CISCO','2020-07-10 07:19:11','2020-07-25 08:28:17',NULL,2,3,5,'R2');
/*!40000 ALTER TABLE `physical_routers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_security_devices`
--

LOCK TABLES `physical_security_devices` WRITE;
/*!40000 ALTER TABLE `physical_security_devices` DISABLE KEYS */;
INSERT INTO `physical_security_devices` (`id`, `name`, `type`, `description`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`) VALUES (1,'Magic Gate','Gate','<p>BIG Magic Gate</p>','2021-05-20 14:40:43','2021-11-13 20:29:45',NULL,1,1,1),(2,'IDS01','Firewall','<p>The magic firewall - PT3743</p>','2021-06-07 14:56:26','2023-01-11 15:40:20',NULL,2,3,5),(3,'Sensor-1','Sensor','<p>Temperature sensor</p>','2021-11-13 20:37:14','2023-01-11 15:40:35',NULL,1,3,3);
/*!40000 ALTER TABLE `physical_security_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_servers`
--

LOCK TABLES `physical_servers` WRITE;
/*!40000 ALTER TABLE `physical_servers` DISABLE KEYS */;
INSERT INTO `physical_servers` (`id`, `name`, `description`, `responsible`, `configuration`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `physical_switch_id`, `type`) VALUES (1,'Serveur A1','<p>Description du serveur A1</p>','Marc','<p>OS: OS2<br>IP : 127.0.0.1<br>&nbsp;</p>','2020-06-21 05:27:02','2021-11-27 11:12:00',NULL,1,2,4,NULL,'System 840'),(2,'Serveur A2','<p>Description du serveur A2</p>','Marc','<p>Configuration du serveur A<br>OS : Linux 23.4<br>RAM: 32G</p>','2020-06-21 05:27:58','2021-11-27 11:12:12',NULL,3,5,6,NULL,'System 840'),(3,'Serveur A3','<p>Serveur mobile</p>','Marc','<p>None</p>','2020-07-14 15:30:48','2021-11-27 11:12:24',NULL,1,1,3,NULL,'System 840'),(4,'ZZ99','<p>Zoro server</p>',NULL,NULL,'2020-07-14 15:37:50','2020-08-25 14:54:58','2020-08-25 14:54:58',3,5,NULL,NULL,NULL),(5,'K01','<p>Serveur K01</p>',NULL,'<p>TOP CPU<br>TOP RAM</p>','2020-07-15 14:37:04','2020-08-29 12:08:09','2020-08-29 12:08:09',1,1,3,NULL,NULL),(6,'Mainframe 01','<p>Central accounting system</p>','Marc','<p>40G RAM<br>360P Disk<br>CICS / Cobol</p>','2020-09-05 08:02:49','2021-11-27 11:11:43',NULL,1,1,1,2,'Type 404'),(7,'Mainframe T1','<p>Mainframe de test</p>','Marc','<p>IDEM prod</p>','2020-09-05 08:22:18','2021-11-27 11:11:01',NULL,2,3,4,2,'HAL 340'),(8,'Serveur A4','<p>Departmental server</p>','Marc','<p>Standard configuration</p>','2021-06-22 15:34:33','2021-11-27 11:12:50',NULL,2,3,5,NULL,'Mini 900/2');
/*!40000 ALTER TABLE `physical_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_switches`
--

LOCK TABLES `physical_switches` WRITE;
/*!40000 ALTER TABLE `physical_switches` DISABLE KEYS */;
INSERT INTO `physical_switches` (`id`, `name`, `description`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`) VALUES (1,'SW01','<p>Master test switch.</p>','Nortel A39','2020-07-17 13:29:09','2023-01-11 15:35:46',NULL,1,2,4),(2,'SW03','<p>Description switch 2</p>','Alcatel 430','2020-07-17 13:31:41','2023-01-11 15:36:27',NULL,1,1,1),(3,'SW02','<p>Desription du premier switch.</p>','Nortel 2300','2020-07-25 05:27:27','2023-01-11 15:36:17',NULL,2,3,5),(4,'SW04','<p>Desciption du switch 3</p>','Alcatel 3500','2020-07-25 07:42:51','2023-01-11 15:36:38',NULL,3,5,6),(5,'AB','<p>Test 2 chars switch</p>',NULL,'2020-08-22 04:19:45','2020-08-27 16:04:20','2020-08-27 16:04:20',NULL,NULL,NULL),(6,'SW05','<p>Description du switch 05</p>',NULL,'2023-01-11 15:38:44','2023-01-11 15:38:44',NULL,1,1,3);
/*!40000 ALTER TABLE `physical_switches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `processes`
--

LOCK TABLES `processes` WRITE;
/*!40000 ALTER TABLE `processes` DISABLE KEYS */;
INSERT INTO `processes` (`id`, `identifiant`, `description`, `owner`, `security_need_c`, `in_out`, `dummy`, `created_at`, `updated_at`, `deleted_at`, `macroprocess_id`, `security_need_i`, `security_need_a`, `security_need_t`) VALUES (1,'Processus 1','<p>Description du processus 1</p>','Ched',3,'<ul><li>pommes</li><li>poires</li><li>cerise</li></ul>',NULL,'2020-06-17 14:36:24','2022-07-29 05:50:09',NULL,1,2,3,1),(2,'Processus 2','<p>Description du processus 2</p>','Ched',3,'<p>1 2 3 4 5 6</p>',NULL,'2020-06-17 14:36:58','2021-09-22 10:59:14',NULL,10,4,2,4),(3,'Processus 3','<p>Description du processus 3</p>','Johan',3,'<p>a,b,c</p><p>d,e,f</p>',NULL,'2020-07-01 15:50:27','2021-08-17 08:22:13',NULL,2,2,3,1),(4,'Processus 4','<p>Description du processus 4</p>','Paul',4,'<ul><li>chaussettes</li><li>pantalon</li><li>chaussures</li></ul>',NULL,'2020-08-18 15:00:36','2021-08-17 08:22:29',NULL,2,2,2,2),(5,'totoat','<p>tto</p>',NULL,1,'<p>sgksdùmfk</p>',NULL,'2020-08-27 13:16:56','2020-08-27 13:17:01','2020-08-27 13:17:01',1,NULL,NULL,NULL),(6,'ptest','<p>description de ptest</p>',NULL,0,'<p>toto titi tutu</p>',NULL,'2020-08-29 11:10:23','2020-08-29 11:10:28','2020-08-29 11:10:28',NULL,NULL,NULL,NULL),(7,'ptest2','<p>fdfsdfsdf</p>',NULL,1,'<p>fdfsdfsd</p>',NULL,'2020-08-29 11:16:42','2020-08-29 11:17:09','2020-08-29 11:17:09',1,NULL,NULL,NULL),(8,'ptest3','<p>processus de test 3</p>','CHEM - Facturation',3,'<p>dsfsdf sdf sdf sd fsd fsd f s</p>',NULL,'2020-08-29 11:19:13','2020-08-29 11:20:59','2020-08-29 11:20:59',1,NULL,NULL,NULL),(9,'Processus 5','<p>Description du cinquième processus</p>','Paul',4,'<ul><li>chat</li><li>chien</li><li>poisson</li></ul>',NULL,'2021-05-14 07:10:02','2021-09-22 10:59:14',NULL,10,3,2,3),(10,'Proc 6',NULL,NULL,0,NULL,NULL,'2021-10-08 19:18:28','2021-10-08 19:28:38','2021-10-08 19:28:38',NULL,0,0,0);
/*!40000 ALTER TABLE `processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `relations`
--

LOCK TABLES `relations` WRITE;
/*!40000 ALTER TABLE `relations` DISABLE KEYS */;
INSERT INTO `relations` (`id`, `importance`, `name`, `type`, `description`, `created_at`, `updated_at`, `deleted_at`, `source_id`, `destination_id`) VALUES (1,1,'Membre','Fourniture de service','<p>Here is the description of this relation</p>','2020-05-20 22:49:47','2021-08-17 08:20:46',NULL,1,6),(2,2,'Membre','Fournisseur de service','<p>Member description</p>','2020-05-20 23:35:11','2021-09-19 11:12:19',NULL,2,6),(3,1,'Fournisseur','Fournisseur de service','<p>description de la relation entre A et le B</p>','2020-05-20 23:39:24','2021-08-17 08:20:59',NULL,7,1),(4,2,'Membre','Fourniture de service','<p>Description du service</p>','2020-05-21 02:23:03','2021-05-23 13:06:05',NULL,2,6),(5,0,'Membre','Fournisseur de service',NULL,'2020-05-21 02:23:35','2021-05-23 13:05:18',NULL,2,6),(6,0,'Fournisseur','fourniture de service',NULL,'2020-05-21 02:24:35','2020-05-21 02:24:35',NULL,7,2),(7,0,'Membre','fourniture de service',NULL,'2020-05-21 02:26:43','2020-05-21 02:26:43',NULL,4,6),(8,3,'Rapporte',NULL,NULL,'2020-05-21 02:32:19','2020-07-05 10:10:01',NULL,1,5),(9,0,'Fournisseur','fourniture de service',NULL,'2020-05-21 02:33:33','2020-05-21 02:33:33',NULL,9,1),(10,2,'Rapporte','Fournisseur de service','<p>Régelement général APD34</p>','2020-05-22 21:21:02','2020-08-24 14:31:29',NULL,1,8),(12,1,'Fournisseur','Fournisseur de service','<p>Analyse de risques</p>','2020-08-24 14:23:30','2020-08-24 14:23:48',NULL,2,4),(13,1,'Fournisseur','Fourniture de service','<p>Description du service</p>','2020-10-14 17:06:24','2021-05-23 13:06:34',NULL,2,12);
/*!40000 ALTER TABLE `relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `routers`
--

LOCK TABLES `routers` WRITE;
/*!40000 ALTER TABLE `routers` DISABLE KEYS */;
INSERT INTO `routers` (`id`, `name`, `description`, `rules`, `created_at`, `updated_at`, `deleted_at`, `ip_addresses`) VALUES (1,'ROUT_00','<p>Description du routeur 1</p>','<p>liste des règles dans //serveur/liste.txt</p>','2020-07-13 17:32:25','2021-09-21 15:00:36',NULL,'10.50.1.1, 10.60.1.1, 10.70.1.1'),(2,'ROUT_01','<p>Description of router 01</p>','<p>list of rules :&nbsp;</p><ul><li>a</li><li>b</li><li>c</li><li>d</li></ul>','2021-09-21 13:47:47','2021-09-21 14:53:35',NULL,'10.10.0.1, 10.20.0.1, 10.30.0.1'),(3,'ROUT_02','<p>This is the second router</p>',NULL,'2021-09-21 13:52:16','2021-09-21 15:01:18',NULL,'10.30.1.1, 10.40.1.1');
/*!40000 ALTER TABLE `routers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `security_devices`
--

LOCK TABLES `security_devices` WRITE;
/*!40000 ALTER TABLE `security_devices` DISABLE KEYS */;
INSERT INTO `security_devices` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'FW01','<p>Firewall proncipal</p>','2020-07-14 17:01:21','2020-07-14 17:01:21',NULL),(2,'FW02','<p>Backup Fireall</p>','2020-07-14 17:02:21','2020-07-14 17:02:21',NULL);
/*!40000 ALTER TABLE `security_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sites`
--

LOCK TABLES `sites` WRITE;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
INSERT INTO `sites` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Site A','<p>Description du site A</p>','2020-06-21 04:36:41','2020-06-21 04:36:41',NULL),(2,'Site B','<p>Description du site B</p>','2020-06-21 04:36:53','2020-06-21 04:36:53',NULL),(3,'Site C','<p>Description du Site C</p>','2020-06-21 04:37:05','2020-06-21 04:37:05',NULL),(4,'Test1','<p>site de test</p>','2020-07-24 19:12:29','2020-07-24 19:12:56','2020-07-24 19:12:56'),(5,'testsite','<p>description here</p>','2021-04-12 15:31:40','2021-04-12 15:32:04','2021-04-12 15:32:04'),(6,'Site Z',NULL,'2021-06-18 05:36:03','2021-10-19 16:51:22','2021-10-19 16:51:22'),(7,'Site 0',NULL,'2021-06-18 05:36:12','2021-08-17 17:52:52','2021-08-17 17:52:52');
/*!40000 ALTER TABLE `sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `storage_devices`
--

LOCK TABLES `storage_devices` WRITE;
/*!40000 ALTER TABLE `storage_devices` DISABLE KEYS */;
INSERT INTO `storage_devices` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `physical_switch_id`) VALUES (1,'DiskServer 1','<p>Description du serveur d stockage 1</p>','2020-06-21 15:30:16','2023-01-11 15:39:40',NULL,1,2,4,NULL),(2,'Oracle Server','<p>Main oracle server</p>','2020-06-21 15:33:51','2020-06-21 15:34:38',NULL,1,2,2,NULL);
/*!40000 ALTER TABLE `storage_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `subnetworks`
--

LOCK TABLES `subnetworks` WRITE;
/*!40000 ALTER TABLE `subnetworks` DISABLE KEYS */;
INSERT INTO `subnetworks` (`id`, `description`, `address`, `ip_allocation_type`, `responsible_exp`, `dmz`, `wifi`, `name`, `created_at`, `updated_at`, `deleted_at`, `connected_subnets_id`, `gateway_id`, `zone`, `vlan_id`, `network_id`, `default_gateway`) VALUES (1,'<p>Description du sous-réseau 1</p>','10.10.0.0 /16','Static','Marc','non','non','Subnet1','2020-06-23 12:35:41','2021-11-16 20:24:29',NULL,NULL,1,'ZONE_ACCUEIL',2,1,'10.10.0.1'),(2,'<p>Description du subnet 2</p>','10.20.0.0/16','Static','Henri','Oui','Oui','Subnet2','2020-07-04 07:35:10','2022-06-02 18:16:26',NULL,NULL,5,'ZONE_WORK',1,1,'10.20.0.1'),(3,'<p>Description du quatrième subnet</p>','10.40.0.0/16','Static','Jean','non','non','Subnet4','2020-11-06 12:56:33','2022-06-02 18:16:26',NULL,2,5,'ZONE_WORK',4,1,'10.40.0.1'),(4,'<p>descrption subnet 3</p>','8.8.8.8 /  255.255.255.0',NULL,NULL,NULL,NULL,'test subnet 3','2021-02-24 11:49:16','2021-02-24 11:49:33','2021-02-24 11:49:33',NULL,NULL,NULL,NULL,NULL,NULL),(5,'<p>Troisième sous-réseau</p>','10.30.0.0/16','Static','Jean','non','non','Subnet3','2021-05-19 14:48:39','2021-08-20 07:57:01',NULL,NULL,1,'ZONE_WORK',3,1,'10.30.0.1'),(6,'<p>Description du cinquième réseau</p>','10.50.0.0/16','Fixed','Jean','Oui','non','Subnet5','2021-08-17 11:35:28','2021-08-26 15:27:41',NULL,NULL,1,'ZONE_BACKUP',5,1,'10.50.0.1'),(7,'<p>Description du sixième sous-réseau</p>','10.60.0.0/16','Fixed','Jean','non','non','Subnet6','2021-08-17 16:32:47','2021-08-26 15:27:57',NULL,2,4,'ZONE_APP',6,2,'10.60.1.1'),(8,'<p>Test</p>',NULL,NULL,NULL,NULL,NULL,'Subnet7','2021-08-18 16:05:50','2021-08-18 16:10:19','2021-08-18 16:10:19',NULL,NULL,NULL,NULL,NULL,NULL),(9,'<p>Sous-réseau numéro sept</p>','10.70.0.0/16','Static','Jean','Oui','Oui','Subnet7','2021-08-18 16:11:10','2021-08-26 15:27:30',NULL,NULL,NULL,'ZONE_BACKUP',5,2,'10.70.0.1'),(10,'<p>Sous réseau démilitarisé</p>','10.70.0.0/32','Fixed','Jean','Oui','non','Subnet8','2021-08-18 16:33:48','2021-08-26 15:28:10',NULL,NULL,1,'ZONE_DMZ',7,1,'10.70.0.1'),(11,'<p>Description subnet 9</p>','10.90.0.0/32',NULL,'Jean','non','non','Subnet9','2021-09-07 16:41:02','2021-09-07 16:41:02',NULL,NULL,NULL,'ZONE_DATA',8,1,'10.90.1.1'),(12,NULL,NULL,NULL,'Jean','Oui','Oui','Réseau d\'administration \"toto\"','2022-07-07 14:40:37','2022-07-07 15:01:07','2022-07-07 15:01:07',NULL,5,'ZONE_APP',2,1,NULL);
/*!40000 ALTER TABLE `subnetworks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` (`id`, `nom`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Tâche 2','Descriptionde la tâche 2','2020-06-13 00:04:07','2020-06-13 00:04:07',NULL),(2,'Tache 1','Description de la tâche 1','2020-06-13 00:04:21','2020-06-13 00:04:21',NULL),(3,'Tâche 3','Description de la tâche 3','2020-06-13 00:04:41','2020-06-13 00:04:41',NULL);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `vlans`
--

LOCK TABLES `vlans` WRITE;
/*!40000 ALTER TABLE `vlans` DISABLE KEYS */;
INSERT INTO `vlans` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'VLAN_2','VLAN Wifi','2020-07-07 14:31:53','2020-07-07 14:39:10',NULL),(2,'VLAN_1','VLAN publc','2020-07-07 14:34:30','2020-07-07 14:38:53',NULL),(3,'VLAN_3','VLAN application','2020-07-07 14:38:41','2020-07-08 19:35:53',NULL),(4,'VLAN_4','Vlan Client','2020-07-08 19:34:11','2020-07-08 19:36:06',NULL),(5,'VLAN_5','Test Production','2020-07-11 17:12:03','2021-08-18 17:35:54',NULL),(6,'VLAN_6','VLAN démilitarisé','2020-07-11 17:14:55','2021-08-18 17:36:12',NULL),(7,'VLAN_7','Description du VLAN 7','2021-09-07 16:35:28','2021-09-07 16:35:28',NULL),(8,'VLAN_8','Description du VLAN 8','2021-09-07 16:36:20','2021-09-07 16:36:20',NULL);
/*!40000 ALTER TABLE `vlans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `wans`
--

LOCK TABLES `wans` WRITE;
/*!40000 ALTER TABLE `wans` DISABLE KEYS */;
INSERT INTO `wans` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'WAN01','2021-05-21 10:58:42','2021-05-21 10:58:42',NULL);
/*!40000 ALTER TABLE `wans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `wifi_terminals`
--

LOCK TABLES `wifi_terminals` WRITE;
/*!40000 ALTER TABLE `wifi_terminals` DISABLE KEYS */;
INSERT INTO `wifi_terminals` (`id`, `name`, `description`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`) VALUES (1,'WIFI_01','<p>Borne wifi 01</p>','Alcatel 3500','2020-07-22 14:44:37','2020-07-22 14:44:37',NULL,1,2),(2,'WIFI_02','<p>Borne Wifi 2</p>','ALCALSYS 3001','2021-06-07 14:37:47','2021-06-07 14:37:47',NULL,2,1),(3,'WIFI_03','<p>Borne Wifi 3</p>','SYSTEL 3310','2021-06-07 14:42:29','2021-06-07 14:43:18',NULL,3,4);
/*!40000 ALTER TABLE `wifi_terminals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `workstations`
--

LOCK TABLES `workstations` WRITE;
/*!40000 ALTER TABLE `workstations` DISABLE KEYS */;
INSERT INTO `workstations` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `physical_switch_id`, `type`, `operating_system`, `address_ip`, `cpu`, `memory`, `disk`) VALUES (1,'Workstation 1','<p>Station de travail compta</p>','2020-06-21 15:09:04','2022-06-27 09:35:27',NULL,1,7,NULL,'ThinThink 460','Windows 11','10.10.43.2','Intel i5','4',120),(2,'Workstation 2','<p>Station de travail accueil</p>','2020-06-21 15:09:54','2021-10-20 07:14:59',NULL,2,3,NULL,'ThinThink 410',NULL,NULL,NULL,NULL,NULL),(3,'Workstation 3','<p>Station de travail back-office</p>','2020-06-21 15:17:57','2021-10-20 07:15:25',NULL,2,4,NULL,'ThinThink 420',NULL,NULL,NULL,NULL,NULL),(4,'Workstation 4','<p>Description goes here</p>','2022-06-27 08:53:58','2022-06-27 09:36:01',NULL,3,2,NULL,'ThinThink 420','Windows 10','10.10.21.3','Intel i7','4',250),(5,'Workstation 5','<p>Description here</p>','2022-06-27 09:36:52','2022-06-27 09:36:52',NULL,1,7,NULL,'ThinThink 420','Windows 10','10.10.43.4','Intel i7','8',250),(6,'Workstation 6','<p>Description of workstation 6</p>','2022-06-27 09:37:54','2022-06-27 09:37:54',NULL,1,2,NULL,'ThinThink 420','Windows 11','10.23.54.3','Intel i7','4',500),(7,'Workstation 7',NULL,'2022-12-22 22:05:24','2022-12-22 22:05:24',NULL,1,7,NULL,'ThinThink 410',NULL,NULL,NULL,NULL,NULL),(8,'Workstation 8',NULL,'2022-12-22 22:05:42','2023-01-11 18:41:37',NULL,3,5,NULL,'ThinThink 460',NULL,NULL,NULL,NULL,NULL),(9,'Workstation 9',NULL,'2022-12-22 22:06:00','2023-01-11 18:41:23',NULL,3,5,NULL,'ThinThink 460',NULL,NULL,NULL,NULL,NULL),(10,'Workstation 10',NULL,'2022-12-22 22:06:17','2023-01-11 18:42:01',NULL,3,5,NULL,'ThinThink 460',NULL,NULL,NULL,NULL,NULL),(11,'Workstation 11',NULL,'2023-01-12 17:47:14','2023-01-12 17:47:14',NULL,1,1,NULL,'ThinThink 420','Windows 11',NULL,'Intel i7',NULL,NULL),(12,'Workstation 12','<p>Description workstation 12</p>','2023-01-14 17:57:50','2023-01-14 17:57:50',NULL,3,5,NULL,'ThinThink 420','Windows 11','10.10.12.1','Intel i7',NULL,NULL),(13,'Workstation 13','<p>Description workstation 13</p>','2023-01-14 17:58:27','2023-01-14 17:58:27',NULL,3,5,NULL,'ThinThink 420','Windows 11',NULL,'Intel i5',NULL,NULL);
/*!40000 ALTER TABLE `workstations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `zone_admins`
--

LOCK TABLES `zone_admins` WRITE;
/*!40000 ALTER TABLE `zone_admins` DISABLE KEYS */;
INSERT INTO `zone_admins` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Enreprise','<p>Zone d\'administration de l\'entreprise</p>','2020-07-03 07:49:03','2021-05-23 13:07:18',NULL);
/*!40000 ALTER TABLE `zone_admins` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-01-27 18:10:40
