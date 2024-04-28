-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: mercator
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.22.04.1

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
INSERT INTO `activities` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Activité 1','<p>Description de l\'activité</p>','2023-05-01 12:13:01','2023-05-01 12:13:01',NULL);
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
-- Dumping data for table `activity_operation`
--

LOCK TABLES `activity_operation` WRITE;
/*!40000 ALTER TABLE `activity_operation` DISABLE KEYS */;
INSERT INTO `activity_operation` (`activity_id`, `operation_id`) VALUES (2,3),(1,1),(4,3),(3,1),(5,1),(10,1),(1,6),(1,4);
/*!40000 ALTER TABLE `activity_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_process`
--

LOCK TABLES `activity_process` WRITE;
/*!40000 ALTER TABLE `activity_process` DISABLE KEYS */;
INSERT INTO `activity_process` (`process_id`, `activity_id`) VALUES (1,1),(1,2),(2,3),(2,4),(3,2),(3,5),(4,5),(5,4),(6,4),(7,3),(8,4),(9,3),(1,10),(1,13),(2,13),(1,14),(2,14),(1,15),(2,15),(1,16),(2,16),(1,17),(2,17);
/*!40000 ALTER TABLE `activity_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `actor_operation`
--

LOCK TABLES `actor_operation` WRITE;
/*!40000 ALTER TABLE `actor_operation` DISABLE KEYS */;
INSERT INTO `actor_operation` (`operation_id`, `actor_id`) VALUES (2,1),(1,1),(1,4),(2,5),(3,6),(5,4),(6,5),(4,5);
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
INSERT INTO `application_service_m_application` (`m_application_id`, `application_service_id`) VALUES (2,3),(2,4),(1,3),(15,2),(15,3),(1,1),(4,11),(4,5),(4,7),(1,10),(16,10),(16,11),(16,5),(16,6),(16,7),(16,9),(16,1),(16,2),(16,3),(16,4),(16,8),(35,11),(18,11),(18,5);
/*!40000 ALTER TABLE `application_service_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_services`
--

LOCK TABLES `application_services` WRITE;
/*!40000 ALTER TABLE `application_services` DISABLE KEYS */;
INSERT INTO `application_services` (`id`, `description`, `exposition`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'<p>Descrition du service applicatif 1</p>','cloud','SERV-1','2020-06-13 09:35:31','2023-11-30 09:17:57',NULL),(2,'<p>Description du service 2</p>','local','Service 2','2020-06-13 09:35:48','2020-06-13 09:35:48',NULL),(3,'<p>Description du service 3</p>','local','Service 3','2020-06-13 09:36:04','2020-06-13 09:43:05',NULL),(4,'<p>Description du service 4</p>','local','Service 4','2020-06-13 09:36:17','2020-06-13 09:36:17',NULL),(5,'<p>Service applicatif 4</p>','Extranet','SRV-4','2021-08-02 14:11:43','2021-08-17 08:24:10',NULL),(6,'<p>Service applicatif 4</p>',NULL,'SRV-5','2021-08-02 14:12:19','2021-08-02 14:12:19',NULL),(7,'<p>Service applicatif 4</p>',NULL,'SRV-6','2021-08-02 14:12:56','2021-08-02 14:12:56',NULL),(8,'<p>The service 99</p>','local','SRV-99','2021-08-02 14:13:39','2021-09-07 16:53:36',NULL),(9,'<p>Service applicatif 4</p>',NULL,'SRV-9','2021-08-02 14:14:27','2021-08-02 14:14:27',NULL),(10,'<p>Service applicatif 4</p>','Extranet','SRV-10','2021-08-02 14:15:21','2021-08-17 08:24:20',NULL),(11,'<p>Service applicatif 4</p>','Extranet','SRV-11','2021-08-02 14:16:34','2021-08-17 08:24:28',NULL);
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
INSERT INTO `certificate_logical_server` (`certificate_id`, `logical_server_id`) VALUES (4,1),(1,1),(2,1),(3,1),(7,1);
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
-- Dumping data for table `clusters`
--

LOCK TABLES `clusters` WRITE;
/*!40000 ALTER TABLE `clusters` DISABLE KEYS */;
INSERT INTO `clusters` (`id`, `name`, `type`, `description`, `address_ip`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'CLUSTER01','Alice','<p>The big Alice cluster</p>','127.0.0.1','2023-11-03 09:19:01','2024-04-24 15:44:44',NULL),(2,'CLUSTER02','Bob','<p>The Bob Cluster</p>',NULL,'2023-11-03 09:19:23','2024-04-24 15:44:44',NULL),(3,'CLUSTER03','Bob','<p>Description of cluster 03</p>','10.3.2.4','2023-11-03 09:21:46','2024-04-24 15:44:44',NULL),(5,'CLUSTER04','Max','<p>The Max Cluster</p>','10.10.12.5','2024-04-23 02:53:24','2024-04-24 15:44:44',NULL);
/*!40000 ALTER TABLE `clusters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `data_processing`
--

LOCK TABLES `data_processing` WRITE;
/*!40000 ALTER TABLE `data_processing` DISABLE KEYS */;
INSERT INTO `data_processing` (`id`, `name`, `description`, `responsible`, `purpose`, `categories`, `recipients`, `transfert`, `retention`, `controls`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Traitement 1','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>',NULL,'2023-04-30 07:57:34','2023-05-15 08:10:52',NULL),(2,'Traitement 2','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>',NULL,'2023-04-30 17:37:26','2023-04-30 17:37:26',NULL),(3,'deleted','<p>test</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-23 06:35:05','2023-05-23 06:35:22','2023-05-23 06:35:22'),(4,'Traitement 3','<p>Description du traitement 3</p>','<p>Responsable du traitement 3</p>','<p>Décrire ici les finalités du traitement</p>','<p>Décrire ici les catégories de destinataires</p>','<p>Décrire ici les destinataires des données</p>','<p>Décrire ici les transferts de données</p>','<p>Décrire ici les durées de rétention</p>',NULL,'2023-05-23 07:16:23','2023-05-23 07:16:23',NULL);
/*!40000 ALTER TABLE `data_processing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `data_processing_document`
--

LOCK TABLES `data_processing_document` WRITE;
/*!40000 ALTER TABLE `data_processing_document` DISABLE KEYS */;
INSERT INTO `data_processing_document` (`data_processing_id`, `document_id`) VALUES (1,16),(2,17),(2,19),(1,30);
/*!40000 ALTER TABLE `data_processing_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `data_processing_information`
--

LOCK TABLES `data_processing_information` WRITE;
/*!40000 ALTER TABLE `data_processing_information` DISABLE KEYS */;
INSERT INTO `data_processing_information` (`data_processing_id`, `information_id`) VALUES (1,4),(1,1),(1,2),(2,2),(2,3),(4,3);
/*!40000 ALTER TABLE `data_processing_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `data_processing_m_application`
--

LOCK TABLES `data_processing_m_application` WRITE;
/*!40000 ALTER TABLE `data_processing_m_application` DISABLE KEYS */;
INSERT INTO `data_processing_m_application` (`data_processing_id`, `m_application_id`) VALUES (1,15),(1,3),(2,1),(4,12);
/*!40000 ALTER TABLE `data_processing_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `data_processing_process`
--

LOCK TABLES `data_processing_process` WRITE;
/*!40000 ALTER TABLE `data_processing_process` DISABLE KEYS */;
INSERT INTO `data_processing_process` (`data_processing_id`, `process_id`) VALUES (1,1),(1,2),(2,2),(3,3),(4,3);
/*!40000 ALTER TABLE `data_processing_process` ENABLE KEYS */;
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
INSERT INTO `database_logical_server` (`database_id`, `logical_server_id`) VALUES (1,2),(1,1);
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
INSERT INTO `dhcp_servers` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `address_ip`) VALUES (1,'DHCP_1','<p>Serveur DHCP primaire</p>','2020-07-23 08:34:43','2024-03-09 16:24:39',NULL,'10.10.1.1, 10.10.10.1'),(2,'DHCP_2','<p>Serveur DHCP secondaire</p>','2021-10-19 08:46:52','2024-03-09 16:25:21',NULL,'10.40.6.4, 2001:db8:3333:4444:CCCC:DDDD:EEEE:FFFF');
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
-- Dumping data for table `document_logical_server`
--

LOCK TABLES `document_logical_server` WRITE;
/*!40000 ALTER TABLE `document_logical_server` DISABLE KEYS */;
INSERT INTO `document_logical_server` (`logical_server_id`, `document_id`) VALUES (4,22);
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
INSERT INTO `documents` (`id`, `filename`, `mimetype`, `size`, `hash`, `deleted_at`, `created_at`, `updated_at`) VALUES (10,'babay beatles.jpg','image/jpeg',31240,'1a06a0d18a332d9439976baacce1bd69fd64c866d0622c340f1f01d6ed8049ff',NULL,'2023-05-08 09:19:49','2023-05-08 09:19:49'),(11,'singes.jpeg','image/jpeg',16070,'5268fc6c3500513143daee5a06e930edcbefbd078c74f81ac252e13989a9e452',NULL,'2023-05-08 09:21:45','2023-05-08 09:21:45'),(12,'babay beatles.jpg','image/jpeg',31240,'1a06a0d18a332d9439976baacce1bd69fd64c866d0622c340f1f01d6ed8049ff',NULL,'2023-05-08 09:23:15','2023-05-08 09:23:15'),(13,'babay beatles.jpg','image/jpeg',31240,'1a06a0d18a332d9439976baacce1bd69fd64c866d0622c340f1f01d6ed8049ff',NULL,'2023-05-08 09:24:12','2023-05-08 09:24:12'),(14,'babay beatles.jpg','image/jpeg',31240,'1a06a0d18a332d9439976baacce1bd69fd64c866d0622c340f1f01d6ed8049ff',NULL,'2023-05-09 11:29:30','2023-05-09 11:29:30'),(15,'babay beatles.jpg','image/jpeg',31240,'1a06a0d18a332d9439976baacce1bd69fd64c866d0622c340f1f01d6ed8049ff','2023-05-09 11:39:25','2023-05-09 11:37:40','2023-05-09 11:39:25'),(16,'singes.jpeg','image/jpeg',16070,'5268fc6c3500513143daee5a06e930edcbefbd078c74f81ac252e13989a9e452',NULL,'2023-05-09 11:38:20','2023-05-09 11:38:20'),(17,'babay beatles.jpg','image/jpeg',31240,'1a06a0d18a332d9439976baacce1bd69fd64c866d0622c340f1f01d6ed8049ff',NULL,'2023-05-09 11:51:48','2023-05-09 11:51:48'),(18,'kepi_capitain2.jpeg','image/jpeg',5955,'86fe43201f8360ec0c7bbdfd55d5ff71a71ef5fa499e58899f0ea0cd3382ec5d','2023-05-09 11:52:16','2023-05-09 11:51:56','2023-05-09 11:52:16'),(19,'valentin.jpeg','image/jpeg',8247,'188f019c0dd657fddee41660841a02de11efcd0802e22d59833084d2ab272002',NULL,'2023-05-09 11:52:06','2023-05-09 11:52:06'),(20,'bonnet-commandant-cousteau.jpg','image/jpeg',206376,'2abde9d07f4abdd4626fe5bcde9e49d4fa63c6f1e0a9d9aa8f00081d130de737','2024-01-05 10:00:15','2024-01-05 09:59:29','2024-01-05 10:00:15'),(21,'casquette-marin-204926-460x646.jpg','image/jpeg',26934,'438a24c6436daa42670132d93fcbb194ea3b55cceb6793bbec808a922b483411','2024-01-05 10:00:16','2024-01-05 09:59:41','2024-01-05 10:00:16'),(22,'casquette-marin-204926-460x646.jpg','image/jpeg',26934,'438a24c6436daa42670132d93fcbb194ea3b55cceb6793bbec808a922b483411','2024-01-05 10:01:04','2024-01-05 10:00:24','2024-01-05 10:01:04'),(23,'casquette-marin-204926-460x646.jpg','image/jpeg',26934,'438a24c6436daa42670132d93fcbb194ea3b55cceb6793bbec808a922b483411',NULL,'2024-01-05 10:00:53','2024-01-05 10:00:53'),(24,'casquette-marin-204926-460x646.jpg','image/jpeg',26934,'438a24c6436daa42670132d93fcbb194ea3b55cceb6793bbec808a922b483411',NULL,'2024-01-05 10:01:13','2024-01-05 10:01:13'),(25,'pirate.jpg','image/jpeg',160650,'b88833f4774b2cdf2e9272f2e3a2fb9b9b20bb0bd0dbcc4ff7bbb23ff2cb2487',NULL,'2024-04-02 14:59:22','2024-04-02 14:59:22'),(26,'1705914670710.jpeg','image/jpeg',117251,'8626e933982488895f47cb4632468df196e383203ab787138c670fbdde21e223',NULL,'2024-04-03 02:57:52','2024-04-03 02:57:52'),(27,'1708930283974.jpeg','image/jpeg',35788,'6c42078ff105133183739afe8552103f8029ec725b3722b602a1c718ab8b4b2a',NULL,'2024-04-04 02:47:31','2024-04-04 02:47:31'),(28,'1708930283974.jpeg','image/jpeg',35788,'6c42078ff105133183739afe8552103f8029ec725b3722b602a1c718ab8b4b2a',NULL,'2024-04-04 02:50:12','2024-04-04 02:50:12'),(29,'brain-sudo.jpeg','image/jpeg',73028,'2a710e6ffffad1a23da5606adbc30472982a09c964bb554462613a8bed5de265',NULL,'2024-04-04 02:54:49','2024-04-04 02:54:49'),(30,'pirate.jpg','image/jpeg',160650,'b88833f4774b2cdf2e9272f2e3a2fb9b9b20bb0bd0dbcc4ff7bbb23ff2cb2487',NULL,'2024-04-04 02:55:55','2024-04-04 02:55:55'),(31,'dbarzin 2023.jpeg','image/jpeg',16275,'a761febd49b4a2c5d06ad6170ced19c8a725e8bbbddca7cbf47b292392263d0a','2024-04-04 03:00:03','2024-04-04 03:00:01','2024-04-04 03:00:03'),(32,'pirate.jpg','image/jpeg',160650,'b88833f4774b2cdf2e9272f2e3a2fb9b9b20bb0bd0dbcc4ff7bbb23ff2cb2487',NULL,'2024-04-04 03:00:10','2024-04-04 03:00:10'),(33,'pirate.jpg','image/jpeg',160650,'b88833f4774b2cdf2e9272f2e3a2fb9b9b20bb0bd0dbcc4ff7bbb23ff2cb2487',NULL,'2024-04-04 03:02:07','2024-04-04 03:02:07'),(34,'pirate.jpg','image/jpeg',160650,'b88833f4774b2cdf2e9272f2e3a2fb9b9b20bb0bd0dbcc4ff7bbb23ff2cb2487',NULL,'2024-04-04 03:04:41','2024-04-04 03:04:41');
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
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
INSERT INTO `entities` (`id`, `name`, `security_level`, `contact_point`, `description`, `is_external`, `created_at`, `updated_at`, `deleted_at`, `entity_type`, `attributes`, `reference`, `parent_entity_id`) VALUES (1,'MegaNet System','<p>ISO 27001</p>','<p>Helpdek<br>27, Rue des poire&nbsp;<br>12043 Mire-en-Mare le Bains</p><p>helpdesk@meganet.org</p>','<p>Fournisseur équipement réseau</p>',1,'2020-05-21 02:30:59','2023-06-01 15:16:50',NULL,'Producer',NULL,NULL,NULL),(2,'Entité1','<p>Néant</p>','<ul><li>Commercial</li><li>Service Delivery</li><li>Helpdesk</li></ul>','<p>Entité de tests1</p>',1,'2020-05-21 02:31:17','2022-05-23 15:10:25',NULL,'Revendeur',NULL,NULL,NULL),(4,'Entité3','<p>ISO 9001</p>','<p>Point de contact de la troisième entité</p>','<p>Description de la troisième entité.</p>',1,'2020-05-21 02:44:03','2022-05-23 15:10:46',NULL,'Producteur',NULL,NULL,NULL),(5,'Entity6','<p>Néant</p>','<p>support_informatque@entite6.fr</p>','<p>Description de l\'entité six</p>',0,'2020-05-21 02:44:18','2024-04-04 03:43:04',NULL,'Distributor',NULL,NULL,NULL),(6,'Entité4','<p>ISO 27001</p>','<p>Pierre Pinon<br>Tel: 00 34 392 484 22</p>','<p>Description de l\'entté quatre</p>',0,'2020-05-21 02:45:14','2021-05-23 13:01:17',NULL,NULL,NULL,NULL,NULL),(7,'Entité5','<p>Néant</p>','<p>Servicdesk@entite5.fr</p>','<p>Description de l\'entité 5</p>',0,'2020-05-21 03:38:41','2021-05-23 13:02:16',NULL,NULL,NULL,NULL,NULL),(8,'Entité2','<p>ISO 27001</p>','<p>Point de contact de l\'entité 2</p>','<p>Description de l\'entité 2</p>',1,'2020-05-21 03:54:22','2022-05-23 14:44:34',NULL,'Legal entity',NULL,NULL,NULL),(9,'NetworkSys','<p>ISO 27001</p>','<p>support@networksys.fr</p>','<p>Description de l’entité NetworkSys</p>',0,'2020-05-21 06:25:15','2022-05-23 14:44:48',NULL,'Internal department',NULL,NULL,NULL),(12,'Pierre et fils','<p>Certifications :&nbsp;<br>- ISO 9001<br>- ISO 27001<br>- ISO 31000</p>','<p>Paul Pierre<br>Gérant<br>00 33 4943 432 423</p>','<p>Description de l\'entité de test</p>',1,'2020-07-06 13:37:54','2022-05-23 14:45:07',NULL,'Fournisseur',NULL,NULL,NULL),(13,'Nestor','<p>Haut niveau</p>','<p>Paul, Pierre et Jean</p>','<p>Description de Nestor</p>',1,'2020-08-12 16:11:31','2022-05-23 14:41:44',NULL,'Fournisseur',NULL,NULL,NULL),(18,'Acme corp.','<p>None sorry...</p>','<p>Do not call me, I will call you back.</p>','<p>Looney tunes academy</p>',0,'2021-09-07 18:07:16','2024-04-14 06:29:50',NULL,'Producer',NULL,NULL,23),(19,'HAL','<p>Top security certification</p>','<p>hal@corp.com</p>','<b>test',0,'2021-09-07 18:08:56','2021-09-07 18:09:17',NULL,NULL,NULL,NULL,NULL),(22,'Hacker Studio','<p>All SANS certificates</p>','<p>Do not call us, we will call you back.</p><p>&nbsp;</p>','<b>test',1,'2022-06-02 11:56:32','2022-06-02 11:56:32',NULL,'Fournisseur',NULL,NULL,NULL),(23,'World company','<p>Full protection</p>','<p>ping us at 256.256.256.256</p>','<p>Thebiggest compagny in the world</p>',1,'2022-06-22 17:20:11','2023-06-01 15:19:54',NULL,'Producer',NULL,NULL,NULL);
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
INSERT INTO `entity_m_application` (`m_application_id`, `entity_id`) VALUES (2,1),(1,2),(1,8),(3,8),(4,8),(4,4),(16,2),(19,2),(19,8),(19,4),(19,6),(19,7),(35,2),(37,18),(18,8);
/*!40000 ALTER TABLE `entity_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entity_process`
--

LOCK TABLES `entity_process` WRITE;
/*!40000 ALTER TABLE `entity_process` DISABLE KEYS */;
INSERT INTO `entity_process` (`process_id`, `entity_id`) VALUES (1,1),(2,1),(3,1),(1,13),(3,13),(4,1),(9,4),(2,8),(4,6),(4,7),(9,5),(1,9),(2,9),(3,9),(4,9),(9,9),(1,12),(1,2),(4,18),(3,19),(1,22),(4,23);
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
INSERT INTO `fluxes` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`, `application_source_id`, `service_source_id`, `module_source_id`, `database_source_id`, `application_dest_id`, `service_dest_id`, `module_dest_id`, `database_dest_id`, `crypted`, `bidirectional`, `nature`) VALUES (2,'FluxA','<p>Description du flux A</p>','2020-06-17 14:50:59','2023-02-28 13:56:24',NULL,1,NULL,NULL,NULL,NULL,3,NULL,NULL,0,1,'API'),(3,'FluxC','<p>Flux de test</p>','2020-07-07 13:58:22','2021-09-23 17:04:30',NULL,2,NULL,NULL,NULL,3,NULL,NULL,NULL,1,NULL,'API'),(4,'FluxB','<p>Flux de test 3</p>','2020-07-07 14:01:10','2022-10-13 16:54:12',NULL,NULL,NULL,4,NULL,NULL,NULL,5,NULL,1,1,'API'),(5,'Sync_DB','<p>Description du flux 01</p>','2020-07-23 10:44:35','2022-10-13 16:54:36',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,3,1,0,'API'),(6,'Flux_MOD_01','<p>Fuld module</p>','2020-07-23 10:48:20','2021-09-29 05:59:35',NULL,NULL,NULL,3,NULL,NULL,NULL,2,NULL,0,0,'API'),(7,'Flux_SER_01','Description du flux service 01','2020-07-23 10:51:41','2020-07-23 10:51:41',NULL,NULL,3,NULL,NULL,NULL,4,NULL,NULL,0,NULL,'API'),(8,'Fulx 07','Description du flux 07','2020-09-05 04:56:57','2020-09-05 04:57:36',NULL,NULL,1,NULL,NULL,NULL,2,NULL,NULL,1,NULL,'API'),(9,'FLux DB_02','<p>Description du flux 2</p>','2020-09-05 05:12:05','2023-01-27 16:09:23',NULL,NULL,NULL,4,NULL,NULL,NULL,2,NULL,1,0,'File copy'),(10,'SRV10_to_SRV11','<p>Transfert from SRV10 to SRV11</p>','2021-08-02 15:13:31','2021-08-02 15:13:31',NULL,NULL,10,NULL,NULL,NULL,11,NULL,NULL,0,NULL,'API'),(11,'ALL','<p>Le flux qui répond à tout</p>','2021-08-02 15:13:57','2024-02-14 11:47:38',NULL,18,NULL,NULL,NULL,NULL,NULL,2,NULL,1,1,'Manual'),(12,'SRV6_to_SRV10','<p>service 6 to service 10</p>','2021-08-02 15:14:36','2021-08-02 15:14:36',NULL,NULL,7,NULL,NULL,NULL,10,NULL,NULL,1,NULL,'API'),(13,'Syncy System',NULL,'2021-08-02 18:01:21','2021-08-02 18:01:21',NULL,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'API'),(14,'00001',NULL,'2021-09-01 07:00:09','2021-09-01 07:00:21','2021-09-01 07:00:21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'API'),(15,'0002',NULL,'2021-09-01 07:00:15','2021-09-01 07:00:21','2021-09-01 07:00:21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'API'),(16,'MainFlow','<p>The most critical flow</p>','2022-06-29 14:58:33','2022-06-29 14:58:33',NULL,1,NULL,NULL,NULL,2,NULL,NULL,NULL,0,0,'API'),(17,'TestFlux99','<p>Description du flux 99</p>','2022-07-12 13:15:11','2022-07-12 13:15:26',NULL,NULL,NULL,4,NULL,NULL,5,NULL,NULL,1,0,'API'),(18,'TEST','<p>mod4 → mod5</p>','2023-01-27 15:44:23','2023-01-27 15:46:17','2023-01-27 15:46:17',NULL,NULL,4,NULL,NULL,NULL,5,NULL,0,0,'API'),(19,'Test','<p>Test</p>','2023-01-27 16:04:36','2023-01-27 16:04:36',NULL,1,NULL,NULL,NULL,2,NULL,NULL,NULL,0,0,NULL),(20,'FluxD','<p>Flux API DB1 to DB2</p>','2023-11-30 09:53:06','2024-04-16 07:44:21',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,4,0,0,'API'),(21,'HL7_Mapping',NULL,'2024-02-14 11:40:04','2024-02-14 11:40:04',NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'HL7'),(22,'Deleted flux',NULL,'2024-02-14 11:41:58','2024-04-16 08:04:14','2024-04-16 08:04:14',2,NULL,NULL,NULL,40,NULL,NULL,NULL,0,0,'File copy');
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
INSERT INTO `information` (`id`, `name`, `description`, `owner`, `administrator`, `storage`, `security_need_c`, `sensitivity`, `constraints`, `retention`, `created_at`, `updated_at`, `deleted_at`, `security_need_i`, `security_need_a`, `security_need_t`) VALUES (1,'Information 1','<p>Description de l\'information 1</p>','Luc','Yann','externe',1,'Donnée à caractère personnel','<p>Description des contraintes règlementaires et normatives</p>',NULL,'2020-06-13 00:06:43','2024-04-28 06:39:51',NULL,3,2,2),(2,'information 2','<p>Description de l\'information</p>','Nestor','Nom de l\'administrateur','externe',2,'Donnée à caractère personnel',NULL,NULL,'2020-06-13 00:09:13','2021-08-19 16:42:53',NULL,1,1,1),(3,'information 3','<p>Descripton de l\'information 3</p>','Paul','Jean','Local',4,'Donnée à caractère personnel',NULL,NULL,'2020-06-13 00:10:07','2021-09-28 17:42:07',NULL,4,3,4),(4,'Information de test','<p>decription du test</p>','RSSI','Paul','Local',1,'Technical',NULL,NULL,'2020-07-01 15:00:37','2021-08-19 16:45:52',NULL,1,1,1),(5,'Données du client','<p>Données d\'identification du client</p>','Nestor','Paul','Local',2,'Donnée à caractère personnel','<p>RGPD</p>',NULL,'2021-05-14 10:50:09','2022-03-21 17:12:30',NULL,2,2,2);
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
INSERT INTO `lans` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'LAN_1','<p>Description goes here</p>','2020-07-22 05:42:00','2024-04-09 03:14:38',NULL),(2,'LAN_2','Second LAN','2021-06-23 19:19:38','2021-06-23 19:19:38',NULL),(3,'LAN_0','Lan zero','2021-06-23 19:20:04','2021-06-23 19:20:04',NULL);
/*!40000 ALTER TABLE `lans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_flows`
--

LOCK TABLES `logical_flows` WRITE;
/*!40000 ALTER TABLE `logical_flows` DISABLE KEYS */;
INSERT INTO `logical_flows` (`id`, `name`, `source_ip_range`, `dest_ip_range`, `source_port`, `dest_port`, `protocol`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'FLOW1','10.10.10.1/32','10.0.0.33/28',NULL,'80','TCP','<p>Description goes here</p>',NULL,'2024-04-22 14:29:17',NULL),(2,'FLOW2','10.1.1.1/32','10.2.2.2/32',NULL,'80','UDP','<p>Description2</p>','2024-04-09 03:26:40','2024-04-10 03:50:43',NULL),(3,NULL,'10.1.1.1/32','10.2.2.2/32',NULL,'22','TCP','<p>Description du flux</p>','2024-04-09 03:30:36','2024-04-10 03:50:53',NULL),(4,'QuickChat','192.168.1.0/24','192.168.2.0/24','1234','5678','tcp','<p>Rapid exchange of gossip</p>','2024-04-22 10:20:44','2024-04-22 10:27:51',NULL);
/*!40000 ALTER TABLE `logical_flows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_server_m_application`
--

LOCK TABLES `logical_server_m_application` WRITE;
/*!40000 ALTER TABLE `logical_server_m_application` DISABLE KEYS */;
INSERT INTO `logical_server_m_application` (`m_application_id`, `logical_server_id`) VALUES (18,4),(15,3),(4,5),(18,6),(35,3),(3,1),(37,7),(14,8);
/*!40000 ALTER TABLE `logical_server_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_server_physical_server`
--

LOCK TABLES `logical_server_physical_server` WRITE;
/*!40000 ALTER TABLE `logical_server_physical_server` DISABLE KEYS */;
INSERT INTO `logical_server_physical_server` (`logical_server_id`, `physical_server_id`) VALUES (3,8),(4,7),(5,8),(1,9),(3,9),(7,8);
/*!40000 ALTER TABLE `logical_server_physical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_servers`
--

LOCK TABLES `logical_servers` WRITE;
/*!40000 ALTER TABLE `logical_servers` DISABLE KEYS */;
INSERT INTO `logical_servers` (`id`, `name`, `description`, `net_services`, `configuration`, `created_at`, `updated_at`, `deleted_at`, `operating_system`, `address_ip`, `cpu`, `memory`, `environment`, `disk`, `disk_used`, `install_date`, `update_date`, `attributes`, `patching_frequency`, `next_update`, `cluster_id`) VALUES (1,'SRV-1','<p>Description du serveur 1</p>','DNS, HTTP, HTTPS','<p>Configuration du serveur 1</p>','2020-07-12 16:57:42','2024-04-23 02:51:30',NULL,'Ubuntu 20.04','10.10.10.1, 10.10.8.8','2','8','PROD',60,NULL,'2023-11-04 00:00:00','2024-03-12 00:00:00','GRP-1',6,'2024-09-12',1),(3,'SRV-3','<p>Description du serveur 3</p>','HTTP, HTTPS',NULL,'2021-08-26 14:33:03','2024-04-23 02:53:24',NULL,'Ubuntu 20.04','10.70.8.3','4','16','PROD',80,NULL,NULL,'2023-11-30 00:00:00','GRP-1',6,'2024-05-30',5),(4,'SRV-42','<p><i>The Ultimate Question of Life, the Universe and Everything</i></p>',NULL,'<p>Full configuration</p>','2021-11-15 16:03:59','2023-11-03 09:21:46',NULL,'OS 42','10.0.0.42','42','42 G','PROD',42,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,'SRV-4','<p>Description du serveur 4</p>',NULL,NULL,'2022-05-02 16:43:02','2024-03-14 16:18:31',NULL,'Ubunti 22.04 LTS','10.10.3.2','4','2','Dev',NULL,NULL,'2022-05-01 00:00:00','2023-11-30 00:00:00','GRP-1',6,'2024-05-30',2),(6,'SRV-5','<p>Description server 5</p>',NULL,'<p>configuration goes here !</p>','2022-06-27 10:27:02','2023-12-13 07:05:40',NULL,'Ubunti 22.04 LTS','10.10.43.3','18','12','Integration',500,NULL,'2022-06-27 00:00:00','2023-03-14 00:00:00',NULL,12,'2023-12-08',NULL),(7,'SRV-6','<p>Description du serveur 6</p>',NULL,'<p>Default configuration</p>','2024-01-31 08:53:15','2024-04-23 02:51:30',NULL,'Debian 34',NULL,'4','64','PROD',100,20,'2024-02-07 00:00:00','2024-01-31 00:00:00','GRP-2',6,'2024-07-31',1),(8,'SRV-7','<p>Description server 7</p>',NULL,'<p>No cluster</p>','2024-02-05 19:41:25','2024-04-23 02:53:24',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5),(9,'SRV-8','<p>Description of logical server5</p>',NULL,NULL,'2024-04-22 14:27:44','2024-04-23 02:51:30',NULL,'Ubuntu 22.04','10.0.0.32',NULL,NULL,'PROD',NULL,NULL,'2024-04-22 00:00:00',NULL,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `logical_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_events`
--

LOCK TABLES `m_application_events` WRITE;
/*!40000 ALTER TABLE `m_application_events` DISABLE KEYS */;
INSERT INTO `m_application_events` (`id`, `user_id`, `m_application_id`, `message`, `created_at`, `updated_at`) VALUES (3,1,2,'Test 2','2023-12-06 12:24:47','2023-12-06 12:24:47'),(4,1,2,'Test 3','2023-12-06 12:24:56','2023-12-06 12:24:56');
/*!40000 ALTER TABLE `m_application_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_peripheral`
--

LOCK TABLES `m_application_peripheral` WRITE;
/*!40000 ALTER TABLE `m_application_peripheral` DISABLE KEYS */;
INSERT INTO `m_application_peripheral` (`m_application_id`, `peripheral_id`) VALUES (15,1);
/*!40000 ALTER TABLE `m_application_peripheral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_physical_server`
--

LOCK TABLES `m_application_physical_server` WRITE;
/*!40000 ALTER TABLE `m_application_physical_server` DISABLE KEYS */;
INSERT INTO `m_application_physical_server` (`m_application_id`, `physical_server_id`) VALUES (18,6),(2,9);
/*!40000 ALTER TABLE `m_application_physical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_process`
--

LOCK TABLES `m_application_process` WRITE;
/*!40000 ALTER TABLE `m_application_process` DISABLE KEYS */;
INSERT INTO `m_application_process` (`m_application_id`, `process_id`) VALUES (2,1),(2,2),(3,2),(1,1),(14,2),(4,3),(12,4),(16,1),(16,2),(16,3),(16,4),(16,9),(19,3),(19,4),(35,4),(18,3);
/*!40000 ALTER TABLE `m_application_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_workstation`
--

LOCK TABLES `m_application_workstation` WRITE;
/*!40000 ALTER TABLE `m_application_workstation` DISABLE KEYS */;
INSERT INTO `m_application_workstation` (`m_application_id`, `workstation_id`) VALUES (1,1),(3,1),(15,4),(2,6),(2,12),(35,12),(3,2),(3,4),(15,11),(2,14),(18,14);
/*!40000 ALTER TABLE `m_application_workstation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_applications`
--

LOCK TABLES `m_applications` WRITE;
/*!40000 ALTER TABLE `m_applications` DISABLE KEYS */;
INSERT INTO `m_applications` (`id`, `name`, `description`, `vendor`, `product`, `security_need_c`, `responsible`, `functional_referent`, `type`, `technology`, `external`, `users`, `editor`, `created_at`, `updated_at`, `deleted_at`, `entity_resp_id`, `application_block_id`, `documentation`, `security_need_i`, `security_need_a`, `security_need_t`, `version`, `rto`, `rpo`, `install_date`, `update_date`, `attributes`, `patching_frequency`, `next_update`) VALUES (1,'central_wifimanager','<p>Description de l\'application 1</p>',NULL,NULL,NULL,'Jacques, RSSI',NULL,'logiciel','Microsoft',NULL,'> 20',NULL,'2020-06-14 09:20:15','2024-03-14 16:31:58',NULL,23,3,'//Documentation/application1.docx',1,1,1,'1.03',3120,1800,NULL,NULL,'Critical',NULL,NULL),(2,'Application 2','<p><i>Description</i> de l\'<strong>application</strong> 2</p>','microsoft','excel',3,'Jacques',NULL,'Web','Microsoft','SaaS','>10',NULL,'2020-06-14 09:31:16','2024-03-14 16:31:37',NULL,2,1,'None',4,2,2,'2002',3120,1800,NULL,'2024-02-17 00:00:00','Critical GRP-2',6,'2024-08-17'),(3,'Application 3','<p>Test application 3</p>','42',NULL,1,'RSSI',NULL,'progiciel','Microsoft','Interne','>100',NULL,'2020-06-17 17:33:41','2024-03-14 16:09:16',NULL,1,2,'Aucune',2,3,3,NULL,3120,1800,NULL,'2024-02-16 00:00:00','GRP-0',NULL,NULL),(4,'EG350','<p>Description app4</p>',NULL,NULL,2,'Jacques, Pierre',NULL,'logiciel','Microsoft','Internl','>100',NULL,'2020-08-11 14:13:02','2024-03-02 07:42:35',NULL,1,2,'None',2,3,2,'1.0',3120,1800,NULL,NULL,NULL,NULL,NULL),(12,'SuperApp','<p>Super super application !</p>',NULL,NULL,1,'RSSI',NULL,'Web','Oracle','Interne',NULL,NULL,'2021-04-12 17:10:59','2021-06-23 19:33:15',NULL,1,2,NULL,1,1,1,NULL,3120,1800,NULL,NULL,NULL,NULL,NULL),(14,'Windows Calc','<p>Calculatrice windows</p>',NULL,NULL,2,'RSSI',NULL,'logiciel','Microsoft','Internl',NULL,NULL,'2021-05-13 08:15:27','2022-03-20 17:53:29',NULL,1,3,NULL,0,0,0,NULL,3120,1800,NULL,NULL,NULL,NULL,NULL),(15,'Compta','<p>Application de comptabilité</p>',NULL,NULL,3,'RSSI',NULL,'progiciel','Microsoft','Interne','>100',NULL,'2021-05-15 07:53:15','2024-03-02 07:41:57',NULL,1,2,NULL,4,2,3,NULL,3120,1800,NULL,NULL,NULL,NULL,NULL),(16,'Queue Manager','<p>Queue manager</p>',NULL,NULL,4,'Jacques',NULL,'logiciel','Internal Dev','Interne','>100',NULL,'2021-08-02 15:17:11','2022-06-11 09:49:17',NULL,1,1,'//Portal/QueueManager.doc',4,4,4,NULL,3120,1800,NULL,NULL,NULL,NULL,NULL),(18,'Application 42','<p>The Ultimate Question of Life, the Universe and Everything</p>',NULL,NULL,1,'Johan, Marc',NULL,'logiciel','COBOL','Interne','>50',NULL,'2021-11-15 16:03:20','2024-03-14 16:31:27',NULL,18,1,NULL,1,1,1,NULL,3120,1800,'2023-10-19 00:00:00','2023-10-28 00:00:00','GRP-0 Critical',NULL,NULL),(19,'Windows Word 98','<p>Traitement de texte Word</p>',NULL,NULL,1,'Johan, Marc',NULL,'progiciel','Microsoft','Interne',NULL,NULL,'2022-06-14 11:52:36','2022-06-14 11:52:58',NULL,18,1,NULL,1,1,1,NULL,3120,1800,'2022-06-14 00:00:00',NULL,NULL,NULL,NULL),(35,'Vulnerability','<p>Vulnerable test application</p>',NULL,NULL,0,'RSSI',NULL,NULL,'Microsoft','Interne','>100',NULL,'2022-06-28 05:59:28','2023-12-02 17:18:24',NULL,4,2,NULL,0,0,0,'1.5',3120,1800,NULL,NULL,NULL,NULL,NULL),(36,'Messagerie','<p>Internal mail system</p>',NULL,NULL,3,'RSSI',NULL,'Web','Microsoft','Internl','>100',NULL,'2022-12-17 14:11:18','2022-12-17 14:12:03','2022-12-17 14:12:03',NULL,1,NULL,3,3,3,'v1.0',3120,1800,NULL,NULL,NULL,NULL,NULL),(37,'Messagerie','<p>Internal mail system</p>',NULL,NULL,3,'',NULL,'Web','Microsoft','Internl','>100',NULL,'2022-12-17 14:12:12','2022-12-17 14:12:12',NULL,18,1,NULL,3,3,3,'v1.0',3120,1800,NULL,NULL,NULL,NULL,NULL),(38,'excel',NULL,NULL,NULL,0,'',NULL,NULL,NULL,NULL,NULL,NULL,'2023-03-26 07:38:20','2023-03-26 07:38:20',NULL,NULL,NULL,NULL,0,0,0,'2019',0,0,NULL,NULL,NULL,NULL,NULL),(39,'Deleted application',NULL,NULL,NULL,0,'',NULL,NULL,NULL,NULL,NULL,NULL,'2024-02-14 11:40:54','2024-02-14 11:41:04','2024-02-14 11:41:04',NULL,1,NULL,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,NULL),(40,'Another deleted application',NULL,NULL,NULL,0,'',NULL,NULL,NULL,NULL,NULL,NULL,'2024-02-14 11:41:22','2024-02-14 11:42:12','2024-02-14 11:42:12',NULL,NULL,NULL,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,NULL);
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
INSERT INTO `network_switches` (`id`, `name`, `ip`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'LSWITCH-2','123.4.5.6','<p>Test</p>','2020-07-13 17:30:37','2023-02-01 16:05:19',NULL),(2,'LSWITCH-1','10.1.1.1','<p>Second commutateur de test</p>','2022-04-25 12:55:44','2023-02-01 16:05:09',NULL);
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
INSERT INTO `operation_task` (`operation_id`, `task_id`) VALUES (1,1),(1,2),(2,1),(3,3),(4,2),(5,1),(5,2),(5,3),(6,2),(6,1),(4,3);
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
INSERT INTO `peripherals` (`id`, `name`, `type`, `description`, `vendor`, `product`, `version`, `responsible`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `address_ip`, `domain`, `provider_id`) VALUES (1,'PER_01','IBM 4703','<p>important peripheral</p>','hal',NULL,NULL,'Marcel','2020-07-25 06:18:40','2023-10-01 08:50:54',NULL,2,4,4,NULL,'IT',8),(2,'PER_02','IBM 5600','<p>Description</p>',NULL,NULL,NULL,'Nestor','2020-07-25 06:19:18','2020-07-25 06:19:18',NULL,3,5,NULL,NULL,NULL,NULL),(3,'PER_03','HAL 8100','<p>Space device</p>',NULL,NULL,NULL,'Niel','2020-07-25 06:19:58','2020-07-25 06:20:18',NULL,3,4,NULL,NULL,NULL,NULL),(4,'PER_42','IBM 4703','<p>The peripheral</p>',NULL,NULL,NULL,'Niel','2023-10-01 08:37:26','2023-10-01 08:37:26',NULL,1,7,1,NULL,NULL,NULL);
/*!40000 ALTER TABLE `peripherals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `phones`
--

LOCK TABLES `phones` WRITE;
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;
INSERT INTO `phones` (`id`, `name`, `description`, `vendor`, `product`, `version`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `physical_switch_id`, `address_ip`) VALUES (1,'Phone 01','<p>Téléphone de test</p>',NULL,NULL,NULL,'MOTOROAL 3110','2020-07-21 05:16:46','2020-07-25 07:15:17',NULL,1,1,NULL,NULL),(2,'Phone 03','<p>Special AA phone</p>',NULL,NULL,NULL,'Top secret red phne','2020-07-21 05:18:01','2020-07-25 07:25:38',NULL,2,4,NULL,NULL),(3,'Phone 02','<p>Description phone 02</p>',NULL,NULL,NULL,'IPhone 2','2020-07-25 06:52:23','2020-07-25 07:25:19',NULL,2,3,NULL,NULL);
/*!40000 ALTER TABLE `phones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_links`
--

LOCK TABLES `physical_links` WRITE;
/*!40000 ALTER TABLE `physical_links` DISABLE KEYS */;
INSERT INTO `physical_links` (`id`, `src_port`, `dest_port`, `peripheral_src_id`, `phone_src_id`, `physical_router_src_id`, `physical_security_device_src_id`, `physical_server_src_id`, `physical_switch_src_id`, `storage_device_src_id`, `wifi_terminal_src_id`, `workstation_src_id`, `logical_server_src_id`, `network_switch_src_id`, `router_src_id`, `peripheral_dest_id`, `phone_dest_id`, `physical_router_dest_id`, `physical_security_device_dest_id`, `physical_server_dest_id`, `physical_switch_dest_id`, `storage_device_dest_id`, `wifi_terminal_dest_id`, `workstation_dest_id`, `logical_server_dest_id`, `network_switch_dest_id`, `router_dest_id`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'2','1',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 16:43:55','2023-01-11 19:26:02',NULL),(2,'3','1',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:27:27','2023-01-11 19:26:21',NULL),(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:27:46','2023-01-11 17:27:46',NULL),(4,'1','1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:28:25','2023-01-11 19:23:56',NULL),(5,'5',NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:28:43','2023-01-12 17:50:04',NULL),(6,'4',NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:29:05','2023-01-12 17:49:26',NULL),(7,'2','1',NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:29:37','2023-01-12 17:31:51',NULL),(8,'2','1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:30:13','2023-01-11 19:24:08',NULL),(9,'3','1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:30:28','2023-01-11 19:24:17',NULL),(10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:30:47','2023-01-11 17:30:47',NULL),(11,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:31:10','2023-01-11 17:31:10',NULL),(12,'4','1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:48:36','2023-01-11 19:24:29',NULL),(13,'1','1',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:49:06','2023-01-11 19:25:25',NULL),(14,'2','1',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:49:22','2023-01-11 19:25:37',NULL),(15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:49:44','2023-01-11 17:49:44',NULL),(16,'3','1',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:50:11','2023-01-11 19:25:48',NULL),(17,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 17:50:31','2023-01-11 17:51:00',NULL),(18,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'2023-01-11 19:26:55','2023-01-11 19:26:55',NULL),(19,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,'2023-01-11 19:27:09','2023-01-11 19:27:09',NULL),(20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,NULL,NULL,NULL,'2023-01-11 19:27:24','2023-01-11 19:27:24',NULL),(21,'6',NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,'2023-01-11 19:29:54','2023-01-12 17:50:20',NULL),(22,'7',NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 19:32:04','2023-01-12 17:50:30',NULL),(23,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,'2023-01-11 19:32:57','2023-01-11 19:32:57',NULL),(24,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,'2023-01-11 19:33:15','2023-01-11 19:33:15',NULL),(25,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,'2023-01-11 19:33:29','2023-01-11 19:33:29',NULL),(26,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,'2023-01-11 19:35:35','2023-01-11 19:35:35',NULL),(27,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,'2023-01-11 19:36:00','2023-01-11 19:36:00',NULL),(28,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 19:36:20','2023-01-11 19:36:20',NULL),(29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,NULL,NULL,NULL,'2023-01-11 19:37:04','2023-01-11 19:37:04',NULL),(30,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,NULL,NULL,NULL,'2023-01-11 19:37:21','2023-01-11 19:37:21',NULL),(31,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,NULL,NULL,NULL,'2023-01-11 19:37:37','2023-01-11 19:37:37',NULL),(32,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,'2023-01-11 19:38:16','2023-01-11 19:38:16',NULL),(33,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-11 19:38:29','2023-01-11 19:38:29',NULL),(34,'8',NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,NULL,NULL,NULL,'2023-01-12 17:48:22','2023-01-12 17:50:38',NULL),(35,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-02-01 15:58:34','2023-02-01 15:58:34',NULL),(37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2023-02-01 16:05:48','2023-02-01 16:09:25',NULL),(38,'1','10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'2023-12-02 15:32:37','2023-12-02 15:32:37',NULL);
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
INSERT INTO `physical_routers` (`id`, `description`, `vendor`, `product`, `version`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `name`) VALUES (1,'<p>Routeur prncipal</p>',NULL,NULL,NULL,'Fortinet','2020-07-10 06:58:53','2021-10-12 19:08:21',NULL,1,1,1,'R1'),(2,'<p>Routeur secondaire</p>',NULL,NULL,NULL,'CISCO','2020-07-10 07:19:11','2020-07-25 08:28:17',NULL,2,3,5,'R2');
/*!40000 ALTER TABLE `physical_routers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_security_devices`
--

LOCK TABLES `physical_security_devices` WRITE;
/*!40000 ALTER TABLE `physical_security_devices` DISABLE KEYS */;
INSERT INTO `physical_security_devices` (`id`, `name`, `type`, `description`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `address_ip`) VALUES (1,'Magic Gate','Gate','<p>BIG Magic Gate</p>','2021-05-20 14:40:43','2021-11-13 20:29:45',NULL,1,1,1,NULL),(2,'IDS01','Firewall','<p>The magic firewall - PT3743</p>','2021-06-07 14:56:26','2023-01-11 15:40:20',NULL,2,3,5,NULL),(3,'Sensor-1','Sensor','<p>Temperature sensor</p>','2021-11-13 20:37:14','2023-01-11 15:40:35',NULL,1,3,3,NULL);
/*!40000 ALTER TABLE `physical_security_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_servers`
--

LOCK TABLES `physical_servers` WRITE;
/*!40000 ALTER TABLE `physical_servers` DISABLE KEYS */;
INSERT INTO `physical_servers` (`id`, `name`, `description`, `vendor`, `product`, `version`, `responsible`, `configuration`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `physical_switch_id`, `type`, `address_ip`, `cpu`, `memory`, `disk`, `disk_used`, `operating_system`, `install_date`, `update_date`, `patching_group`, `paching_frequency`, `next_update`, `cluster_id`) VALUES (1,'Serveur A1','<p>Description du serveur A1</p>',NULL,NULL,NULL,'Marc','<p>OS: OS2<br>IP : 127.0.0.1<br>&nbsp;</p>','2020-06-21 05:27:02','2024-04-23 02:51:30',NULL,NULL,4,4,NULL,'System 840','128.1.61.150',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(2,'Serveur A2','<p>Description du serveur A2</p>',NULL,NULL,NULL,'Marc','<p>Configuration du serveur A<br>OS : Linux 23.4<br>RAM: 32G</p>','2020-06-21 05:27:58','2023-11-03 09:19:23',NULL,3,5,6,NULL,'System 840',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'Serveur A3','<p>Serveur mobile</p>',NULL,NULL,NULL,'Marc','<p>None</p>','2020-07-14 15:30:48','2023-11-03 09:23:39',NULL,1,1,3,NULL,'System 840',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2),(4,'ZZ99','<p>Zoro server</p>',NULL,NULL,NULL,NULL,NULL,'2020-07-14 15:37:50','2020-08-25 14:54:58','2020-08-25 14:54:58',3,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,'K01','<p>Serveur K01</p>',NULL,NULL,NULL,NULL,'<p>TOP CPU<br>TOP RAM</p>','2020-07-15 14:37:04','2020-08-29 12:08:09','2020-08-29 12:08:09',1,1,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,'Mainframe 01','<p>Central accounting system</p>',NULL,NULL,NULL,'Marc','<p>CICS / Cobol</p>','2020-09-05 08:02:49','2023-11-03 09:19:23',NULL,1,1,1,2,'Type 404','127.0.0.1','6','40','120','60','SYSTEM 42','2023-10-28 12:03:14',NULL,NULL,NULL,NULL,NULL),(7,'Mainframe T1','<p>Mainframe de test</p>',NULL,NULL,NULL,'Marc','<p>IDEM prod</p>','2020-09-05 08:22:18','2023-11-03 09:19:23',NULL,2,3,4,2,'HAL 340',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,'Serveur A4','<p>Departmental server</p>',NULL,NULL,NULL,'Marc','<p>Standard configuration</p>','2021-06-22 15:34:33','2023-11-03 09:19:23',NULL,2,3,5,NULL,'Mini 900/2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,'Test',NULL,NULL,NULL,NULL,NULL,NULL,'2023-11-07 12:16:11','2024-04-23 02:50:15',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `physical_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_switches`
--

LOCK TABLES `physical_switches` WRITE;
/*!40000 ALTER TABLE `physical_switches` DISABLE KEYS */;
INSERT INTO `physical_switches` (`id`, `name`, `description`, `vendor`, `product`, `version`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`) VALUES (1,'SW01','<p>Master test switch.</p>',NULL,NULL,NULL,'Nortel A39','2020-07-17 13:29:09','2023-01-11 15:35:46',NULL,1,2,4),(2,'SW03','<p>Description switch 2</p>',NULL,NULL,NULL,'Alcatel 430','2020-07-17 13:31:41','2023-01-11 15:36:27',NULL,1,1,1),(3,'SW02','<p>Desription du premier switch.</p>',NULL,NULL,NULL,'Nortel 2300','2020-07-25 05:27:27','2023-01-11 15:36:17',NULL,2,3,5),(4,'SW04','<p>Desciption du switch 3</p>',NULL,NULL,NULL,'Alcatel 3500','2020-07-25 07:42:51','2023-01-11 15:36:38',NULL,3,5,6),(5,'AB','<p>Test 2 chars switch</p>',NULL,NULL,NULL,NULL,'2020-08-22 04:19:45','2020-08-27 16:04:20','2020-08-27 16:04:20',NULL,NULL,NULL),(6,'SW05','<p>Description du switch 05</p>',NULL,NULL,NULL,'Alcatel 430','2023-01-11 15:38:44','2023-10-28 09:06:26',NULL,1,1,3);
/*!40000 ALTER TABLE `physical_switches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `processes`
--

LOCK TABLES `processes` WRITE;
/*!40000 ALTER TABLE `processes` DISABLE KEYS */;
INSERT INTO `processes` (`id`, `name`, `description`, `owner`, `security_need_c`, `in_out`, `created_at`, `updated_at`, `deleted_at`, `macroprocess_id`, `security_need_i`, `security_need_a`, `security_need_t`) VALUES (1,'Processus 1','<p>Description du processus 1</p>','Ched',3,'<ul><li>pommes</li><li>poires</li><li>cerise</li></ul>','2020-06-17 14:36:24','2022-07-29 05:50:09',NULL,1,2,3,1),(2,'Processus 2','<p>Description du processus 2</p>','Ched',3,'<p>1 2 3 4 5 6</p>','2020-06-17 14:36:58','2021-09-22 10:59:14',NULL,10,4,2,4),(3,'Processus 3','<p>Description du processus 3</p>','Johan',3,'<p>a,b,c</p><p>d,e,f</p>','2020-07-01 15:50:27','2021-08-17 08:22:13',NULL,2,2,3,1),(4,'Processus 4','<p>Description du processus 4</p>','Paul',4,'<ul><li>chaussettes</li><li>pantalon</li><li>chaussures</li></ul>','2020-08-18 15:00:36','2021-08-17 08:22:29',NULL,2,2,2,2),(5,'totoat','<p>tto</p>',NULL,1,'<p>sgksdùmfk</p>','2020-08-27 13:16:56','2020-08-27 13:17:01','2020-08-27 13:17:01',1,NULL,NULL,NULL),(6,'ptest','<p>description de ptest</p>',NULL,0,'<p>toto titi tutu</p>','2020-08-29 11:10:23','2020-08-29 11:10:28','2020-08-29 11:10:28',NULL,NULL,NULL,NULL),(7,'ptest2','<p>fdfsdfsdf</p>',NULL,1,'<p>fdfsdfsd</p>','2020-08-29 11:16:42','2020-08-29 11:17:09','2020-08-29 11:17:09',1,NULL,NULL,NULL),(8,'ptest3','<p>processus de test 3</p>','CHEM - Facturation',3,'<p>dsfsdf sdf sdf sd fsd fsd f s</p>','2020-08-29 11:19:13','2020-08-29 11:20:59','2020-08-29 11:20:59',1,NULL,NULL,NULL),(9,'Processus 5','<p>Description du cinquième processus</p>','Paul',4,'<ul><li>chat</li><li>chien</li><li>poisson</li></ul>','2021-05-14 07:10:02','2021-09-22 10:59:14',NULL,10,3,2,3),(10,'Proc 6',NULL,NULL,0,NULL,'2021-10-08 19:18:28','2021-10-08 19:28:38','2021-10-08 19:28:38',NULL,0,0,0),(11,'Process empty',NULL,NULL,0,NULL,'2023-12-04 08:13:37','2023-12-04 08:13:37',NULL,NULL,0,0,0);
/*!40000 ALTER TABLE `processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `relation_values`
--

LOCK TABLES `relation_values` WRITE;
/*!40000 ALTER TABLE `relation_values` DISABLE KEYS */;
INSERT INTO `relation_values` (`relation_id`, `date_price`, `price`, `created_at`, `updated_at`) VALUES (15,'2020-01-01',123.00,'2024-04-06 11:58:58','2024-04-06 11:58:58'),(4,'2023-04-06',950000.00,'2024-04-06 11:59:47','2024-04-06 11:59:47'),(4,'2024-04-01',1000000.00,'2024-04-06 11:59:47','2024-04-06 11:59:47'),(3,'0022-01-01',150000.00,'2024-04-06 12:00:55','2024-04-06 12:00:55'),(3,'2023-01-01',175000.00,'2024-04-06 12:00:55','2024-04-06 12:00:55'),(3,'2024-01-01',20000.00,'2024-04-06 12:00:55','2024-04-06 12:00:55'),(13,'2024-04-06',100.00,'2024-04-06 12:18:56','2024-04-06 12:18:56'),(13,'2020-01-01',95.00,'2024-04-06 12:18:56','2024-04-06 12:18:56'),(7,'2001-01-01',800000.00,'2024-04-11 04:56:04','2024-04-11 04:56:04'),(7,'2021-04-01',700000.00,'2024-04-11 04:56:04','2024-04-11 04:56:04'),(7,'2024-04-01',950000.00,'2024-04-11 04:56:04','2024-04-11 04:56:04');
/*!40000 ALTER TABLE `relation_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `relations`
--

LOCK TABLES `relations` WRITE;
/*!40000 ALTER TABLE `relations` DISABLE KEYS */;
INSERT INTO `relations` (`id`, `importance`, `name`, `type`, `description`, `created_at`, `updated_at`, `deleted_at`, `source_id`, `destination_id`, `attributes`, `reference`, `responsible`, `order_number`, `active`, `start_date`, `end_date`, `comments`, `security_need_c`, `security_need_i`, `security_need_a`, `security_need_t`) VALUES (1,1,'Product 43','Contrat','<p>Here is the description of this relation</p>','2020-05-20 22:49:47','2024-04-06 12:02:28',NULL,1,6,'Liquidé Signé','REF 54454','Jacques','ORDER 98978',0,'2020-01-01','2022-01-01',NULL,NULL,NULL,NULL,NULL),(2,2,'Product P232','Contrat','<p>Member description</p>','2020-05-20 23:35:11','2024-04-06 12:20:58',NULL,2,6,'Liquidé','REF 5454454','Hen, Julien','ORDER 342434',0,'2022-03-01','2023-03-01',NULL,NULL,NULL,NULL,NULL),(3,3,'Product 32','Contrat','<p>description de la relation entre A et le B</p>','2020-05-20 23:39:24','2024-04-06 12:00:55',NULL,18,19,'Signé Validé','REF 3232','Jean','ORDER 43434',1,'0023-01-01','2025-01-01',NULL,NULL,NULL,NULL,NULL),(4,2,'Outsourcing Service P1','Contrat','<p>Description du service</p>','2020-05-21 02:23:03','2024-04-06 11:59:47',NULL,18,6,'Signé','REF 434343','Paul','ORDER 4343',1,'2024-04-01','2025-04-01',NULL,NULL,NULL,NULL,NULL),(5,1,'Outsourcing Service P2','Contrat','<p>Description goes here</p>','2020-05-21 02:23:35','2024-04-06 12:01:39',NULL,2,6,'Litige','REF 54454','Marcel','ORDER 423432',1,'2021-01-01','2025-01-01',NULL,NULL,NULL,NULL,NULL),(6,2,'Software 21','Contrat','<p>Description goes here</p>','2020-05-21 02:24:35','2024-04-06 12:21:09',NULL,7,2,'Validé','REF 543543','Marc','ORDER 4545435',1,'2020-01-01','2099-01-01','<p>No comments</p>',NULL,NULL,NULL,NULL),(7,2,'Assurance Cyber P3','Assurance','<p>Description here</p>','2020-05-21 02:26:43','2024-04-11 04:56:04',NULL,4,6,'Signé Validé','REF 324242324','Paul, Piere','ORDER 23434',1,'2024-04-01','2099-04-06','<p>Commentaire sur la relation</p>',NULL,NULL,NULL,NULL),(8,3,'Product A2','Contrat','<p>Decription goes here</p>','2020-05-21 02:32:19','2024-04-06 12:03:17',NULL,1,5,'Signé','REF RE5943545','Henri','ORDER 543FD3',1,'2023-01-01','2024-01-01',NULL,NULL,NULL,NULL,NULL),(9,2,'System34','Contrat','<p>Description goes here</p>','2020-05-21 02:33:33','2024-04-06 12:22:30',NULL,9,1,'Signé','REF 65665','Paul','ORDER 43434',1,'2022-01-01','2099-02-01',NULL,NULL,NULL,NULL,NULL),(10,2,'Support APP2','Service','<p>Régelement général APD34</p>','2020-05-22 21:21:02','2024-04-06 12:21:43',NULL,1,8,'Signé','REF YI3434','Henri','ORDER 45543',1,'2023-01-01','2099-01-01',NULL,NULL,NULL,NULL,NULL),(12,1,'Server43','Contrat','<p>Analyse de risques</p>','2020-08-24 14:23:30','2024-04-06 12:16:57',NULL,2,4,'Signé','REF 53435','Thomas','ORDER 4343',1,'2020-01-01','2099-01-01',NULL,NULL,NULL,NULL,NULL),(13,1,'Support APP1','Service','<p>Description du service</p>','2020-10-14 17:06:24','2024-04-06 12:18:56',NULL,2,12,'Signé','REF 545435','Jean, Henry, Hen','ORDER 4343',1,'2024-01-01','2099-01-01',NULL,NULL,NULL,NULL,NULL),(14,2,'Product23','Contrat','<p>Description goes here</p>','2024-04-04 03:03:30','2024-04-06 12:16:19',NULL,2,4,'Signé','REF 54545','Philippe','ORDER 434324',1,'0202-01-01','2099-01-01',NULL,NULL,NULL,NULL,NULL),(15,2,'Nextor','Service',NULL,'2024-04-06 11:46:32','2024-04-06 11:58:58',NULL,8,2,'Signé',NULL,'Piere',NULL,1,'2020-01-01','2029-01-01',NULL,NULL,NULL,NULL,NULL),(16,0,'test','Assurance','<p>dsqqsd</p>','2024-04-06 19:18:04','2024-04-06 19:18:15','2024-04-06 19:18:15',2,8,'',NULL,'',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `routers`
--

LOCK TABLES `routers` WRITE;
/*!40000 ALTER TABLE `routers` DISABLE KEYS */;
INSERT INTO `routers` (`id`, `name`, `description`, `rules`, `created_at`, `updated_at`, `deleted_at`, `ip_addresses`) VALUES (1,'LROUT_00','<p>Description of master router 00</p>','<p>liste des règles dans //serveur/liste.txt</p>','2020-07-13 17:32:25','2024-03-14 16:08:35',NULL,'10.50.1.1, 10.60.1.1, 10.70.1.1'),(2,'LROUT_01','<p>Description of router 01</p>','<p>list of rules :&nbsp;</p><ul><li>a</li><li>b</li><li>c</li><li>d</li></ul>','2021-09-21 13:47:47','2023-02-01 15:58:02',NULL,'10.10.0.1, 10.20.0.1, 10.30.0.1'),(3,'LROUT_02','<p>This is the second router</p>',NULL,'2021-09-21 13:52:16','2023-02-01 15:58:09',NULL,'10.30.1.1, 10.40.1.1'),(4,'LROUT_04','<p>Description of logical router 04</p>',NULL,'2024-03-14 16:02:46','2024-03-14 16:08:11',NULL,'127.0.0.1, 127.0.0.1'),(5,'LROUT_05','<p>Description of logical router 05</p>',NULL,'2024-03-14 16:05:38','2024-03-14 16:05:38',NULL,'232.123.2.2, 1.1.1.1, 2.2.2.2');
/*!40000 ALTER TABLE `routers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `security_control_m_application`
--

LOCK TABLES `security_control_m_application` WRITE;
/*!40000 ALTER TABLE `security_control_m_application` DISABLE KEYS */;
INSERT INTO `security_control_m_application` (`security_control_id`, `m_application_id`) VALUES (169,2),(166,2),(167,2),(168,2),(170,2),(171,2),(173,2),(175,2),(178,2),(182,2),(183,2),(157,3),(158,3),(159,3),(160,3),(161,3),(162,3),(167,3),(168,3),(169,3),(174,3),(175,3),(176,3),(177,3),(178,3),(179,3),(180,3),(160,18),(161,18),(162,18),(166,18),(167,18),(174,18),(175,18),(176,18),(179,18),(180,18);
/*!40000 ALTER TABLE `security_control_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `security_control_process`
--

LOCK TABLES `security_control_process` WRITE;
/*!40000 ALTER TABLE `security_control_process` DISABLE KEYS */;
INSERT INTO `security_control_process` (`security_control_id`, `process_id`) VALUES (193,1),(191,1),(194,1),(192,1),(193,2),(191,2),(194,3),(192,3),(191,4),(194,4);
/*!40000 ALTER TABLE `security_control_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `security_controls`
--

LOCK TABLES `security_controls` WRITE;
/*!40000 ALTER TABLE `security_controls` DISABLE KEYS */;
INSERT INTO `security_controls` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (98,'5.01  Politiques de sécurité de l\'information','Assurer de manière continue la pertinence, l\'adéquation, l\'efficacité des orientations de la direction et de  son soutien à la sécurité de l\'information selon les exigences métier, légales, statutaires, réglementaires  et contractuelles.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(99,'5.02  Fonctions et responsabilités liées à la sécurité de l\'information','Établir une structure définie, approuvée et comprise pour la mise en œuvre, le fonctionnement et la  gestion de la sécurité de l\'information au sein de l\'organisation.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(100,'5.03  Séparation des tâches','Réduire le risque de fraude, d\'erreur et de contournement des mesures de sécurité de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(101,'5.04  Responsabilités de la direction','S’assurer que la direction comprend son rôle en matière de sécurité de l\'information et qu\'elle  entreprend des actions visant à garantir que tout le personnel est conscient de ses responsabilités liées  à la sécurité de l\'information et qu\'il les mène à bien.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(102,'5.05  Relations avec les autorités','Assurer la circulation adéquate de l\'information en matière de sécurité de l’information, entre  l\'organisation et les autorités légales, réglementaires et de surveillance pertinente.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(103,'5.06  Relations avec des groupes de travail spécialisés','Assurer la circulation adéquate de l\'information en matière de sécurité de l’information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(104,'5.07  Intelligence des menaces','Apporter une connaissance de l\'environnement des menaces de l\'organisation afin que les mesures  d\'atténuation appropriées puissent être prises.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(105,'5.08  Sécurité de l\'information dans la gestion de projet','Assurer que les risques de sécurité de l\'information relatifs aux projets et aux livrables sont traités  efficacement dans la gestion de projet, tout au long du cycle de vie du projet.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(106,'5.09  Inventaire des informations et des autres actifs associés','Identifier les informations et autres actifs associés de l\'organisation afin de préserver leur sécurité et  d\'en attribuer la propriété de manière appropriée.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(107,'5.10  Utilisation correcte de l\'information et des autres actifs associés','Assurer que les informations et autres actifs associés sont protégés, utilisés et traités de manière  appropriée.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(108,'5.11  Restitution des actifs','Protéger les actifs de l\'organisation dans le cadre du processus du changement ou de la fin de leur  emploi, contrat ou accord.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(109,'5.12  Classification de l\'information','Assurer l\'identification et la compréhension des besoins de protection de l\'information en fonction de son importance pour l\'organisation.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(110,'5.13  Marquage des informations','Faciliter la communication de la classification de l\'information et appuyer l\'automatisation de la gestion  et du traitement de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(111,'5.14  Transfert de l\'information','Maintenir la sécurité de l\'information transférée au sein de l\'organisation et vers toute partie intéressée  externe',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(112,'5.15  Contrôle d\'accès','Assurer l\'accès autorisé et empêcher l\'accès non autorisé aux informations et autres actifs associés.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(113,'5.16  Gestion des identités','Permettre l\'identification unique des personnes et des systèmes qui accèdent aux informations et  autres actifs associés de l\'organisation, et pour permettre l’attribution appropriée des droits d\'accès.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(114,'5.17  Informations d\'authentification','Assurer l\'authentification correcte de l\'entité et éviter les défaillances des processus d\'authentification.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(115,'5.18  Droits d\'accès','Assurer que l\'accès aux informations et aux autres actifs associés est défini et autorisé conformément aux  exigences métier',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(116,'5.19  Sécurité de l\'information dans les relations avec les fournisseurs','Maintenir le niveau de sécurité de l\'information convenu dans les relations avec les fournisseurs.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(117,'5.20  Prise en compte de la sécurité de l\'information dans les accords conclus avec les fournisseurs','Maintenir le niveau de sécurité de l\'information convenu dans les relations avec les fournisseurs.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(118,'5.21  Management de la sécurité de l\'information dans la chaîne d\'approvisionnement TIC','Maintenir le niveau de sécurité de l\'information convenu dans les relations avec les fournisseurs.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(119,'5.22  Suivi, revue et gestion des changements des services fournisseurs','Maintenir un niveau convenu de sécurité de l\'information et de prestation de services, conformément  aux accords conclus avec les fournisseurs.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(120,'5.23  Sécurité de l\'information dans l\'utilisation de services en nuage','Spécifier et gérer la sécurité de l\'information lors de l\'utilisation de services en nuage.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(121,'5.24  Planification et préparation de la gestion des incidents liés à la sécurité de l\'information','Assurer une réponse rapide, efficace, cohérente et ordonnée aux incidents de sécurité de l\'information,  notamment la communication sur les événements de sécurité de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(122,'5.25  Appréciation des événements liés à la sécurité de l\'information et prise de décision','Assurer une catégorisation et une priorisation efficaces des événements de sécurité de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(123,'5.26  Réponse aux incidents liés à la sécurité de l\'information','Assurer une réponse efficace et effective aux incidents de sécurité de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(124,'5.27  Tirer des enseignements des incidents liés à la sécurité de l\'information','Réduire la probabilité ou les conséquences des incidents futurs.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(125,'5.28  Recueil de preuves','Assurer une gestion cohérente et efficace des preuves relatives aux incidents de sécurité de l\'information  pour les besoins d\'actions judiciaires ou de disciplinaires.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(126,'5.29  Sécurité de l\'information durant une perturbation','Protéger les informations et autres actifs associés pendant une perturbation.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(127,'5.30  Préparation des TIC pour la continuité d\'activité','Assurer la disponibilité des informations et autres actifs associés de l\'organisation pendant une  perturbation.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(128,'5.31  Identification des exigences légales, statutaires, réglementaires et contractuelles','Assurer la conformité aux exigences légales, statutaires, réglementaires et contractuelles relatives à la  sécurité de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(129,'5.32  Droits de propriété intellectuelle','Assurer la conformité aux exigences légales, statutaires, réglementaires et contractuelles relatives aux  droits de propriété intellectuelle et à l\'utilisation de produits propriétaires.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(130,'5.33  Protection des enregistrements','Assurer la conformité aux exigences légales, statutaires, réglementaires et contractuelles, ainsi  qu\'aux attentes de la société ou de la communauté relatives à la protection et à la disponibilité des  enregistrements.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(131,'5.34  Vie privée et protection des DCP','Assurer la conformité aux exigences légales, statutaires, réglementaires et contractuelles relatives aux  aspects de la sécurité de l\'information portant sur la protection des DCP.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(132,'5.35  Revue indépendante de la sécurité de l\'information','S’assurer que l’approche de l’organisation pour gérer la sécurité de l’information est continuellement  adaptée, adéquate et efficace.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(133,'5.36  Conformité aux politiques et normes de sécurité de l\'information','S’assurer que la sécurité de l\'information est mise en œuvre et fonctionne conformément à la politique  de sécurité de l\'information, aux politiques spécifiques à une thématique, aux règles et aux normes de  l\'organisation.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(134,'5.37  Procédures d\'exploitation documentées','S\'assurer du fonctionnement correct et sécurisé des moyens de traitement de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(135,'6.01  Présélection','S\'assurer que tous les membres du personnel sont éligibles et adéquats pour remplir les fonctions pour lesquelles ils sont candidats, et qu\'ils le restent tout au long de leur emploi.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(136,'6.02  Conditions générales d\'embauche','S\'assurer que le personnel comprend ses responsabilités en termes de sécurité de l\'information dans le cadre des fonctions que l’organisation envisage de lui confier.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(137,'6.03  Sensibilisation, apprentissage et formation à la sécurité de l\'information','S\'assurer que le personnel et les parties intéressées pertinentes connaissent et remplissent leurs responsabilités en matière de sécurité de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(138,'6.04  Processus disciplinaire','S\'assurer que le personnel et d’autres parties intéressées pertinentes comprennent les conséquences  des violations de la politique de sécurité de l\'information, prévenir ces violations, et traiter de manière  appropriée le personnel et d’autres parties intéressées qui ont commis des violations.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(139,'6.05  Responsabilités consécutivement à la fin ou à la modification du contrat de tr','Protéger les intérêts de l\'organisation dans le cadre du processus de changement ou de fin d\'un emploi  ou d’un contrat.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(140,'6.06  Engagements de confidentialité ou de non-divulgation','Assurer la confidentialité des informations accessibles par le personnel ou des parties externes.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(141,'6.07  Travail à distance','Assurer la sécurité des informations lorsque le personnel travaille à distance.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(142,'6.08  Signalement des événements liés à la sécurité de l\'information','Permettre la déclaration des événements de sécurité de l\'information qui peuvent être identifiés par le  personnel, de manière rapide, cohérente et efficace.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(143,'7.01  Périmètre de sécurité physique','Empêcher l’accès physique non autorisé, les dommages ou interférences portant sur les informations et  autres actifs associés de l\'organisation.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(144,'7.02  Contrôles physiques des accès','Assurer que seul l\'accès physique autorisé aux informations et autres actifs associés de l\'organisation  soit possible.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(145,'7.03  Sécurisation des bureaux, des salles et des équipements','Empêcher l’accès physique non autorisé, les dommages et les interférences impactant les informations et autres actifs associés de l\'organisation dans les bureaux, salles et installations.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(146,'7.04  Surveillance de la sécurité physique','Détecter et dissuader l’accès physique non autorisé.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(147,'7.05  Protection contre les menaces physiques et environnementales','Prévenir ou réduire les conséquences des événements issus des menaces physiques ou environnementales.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(148,'7.06  Travail dans les zones sécurisées','Protéger les informations et autres actifs associés dans les zones sécurisées contre tout dommage et  contre toutes interférences non autorisées par le personnel travaillant dans ces zones.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(149,'7.07  Bureau propre et écran vide','Réduire les risques d\'accès non autorisé, de perte et d\'endommagement des informations sur les  bureaux, les écrans et dans d’autres emplacements accessibles pendant et en dehors des heures  normales de travail.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(150,'7.08  Emplacement et protection du matériel','Réduire les risques liés à des menaces physiques et environnementales, et à des accès non autorisés et  à des dommages.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(151,'7.09  Sécurité des actifs hors des locaux','Empêcher la perte, l\'endommagement, le vol ou la compromission des terminaux hors du site et  l\'interruption des activités de l\'organisation.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(152,'7.10  Supports de stockage','Assurer que seuls la divulgation, la modification, le retrait ou la destruction autorisés des informations  de l\'organisation sur des supports de stockage sont effectués.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(153,'7.11  Services généraux','Empêcher la perte, l\'endommagement ou la compromission des informations et autres actifs associés,  ou l\'interruption des activités de l\'organisation, causés par les défaillances et les perturbations des  services supports.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(154,'7.12  Sécurité du câblage','Empêcher la perte, l\'endommagement, le vol ou la compromission des informations et autres actifs  associés et l\'interruption des activités de l\'organisation liés au câblage électrique et de communications.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(155,'7.13  Maintenance du matériel','Empêcher la perte, l\'endommagement, le vol ou la compromission des informations et autres actifs  associés et l\'interruption des activités de l\'organisation causés par un manque de maintenance.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(156,'7.14  Mise au rebut ou recyclage sécurisé(e) du matériel','Éviter la fuite d\'informations à partir de matériel à éliminer ou à réutiliser.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(157,'8.01  Terminaux utilisateurs','Protéger les informations contre les risques liés à l\'utilisation de terminaux finaux des utilisateurs.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(158,'8.02  Privilèges d\'accès','S\'assurer que seuls les utilisateurs, composants logiciels et services autorisés sont dotés de droits d\'accès privilégiés.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(159,'8.03  Restriction d\'accès à l\'information','Assurer les accès autorisés seulement et empêcher les accès non autorisés aux informations et autres actifs associés.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(160,'8.04  Accès au code source','Empêcher l\'introduction d\'une fonctionnalité non autorisée, éviter les modifications non intentionnelles  ou malveillantes et préserver la confidentialité de la propriété intellectuelle importante.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(161,'8.05  Authentification sécurisée','S\'assurer qu\'un utilisateur ou une entité est authentifié de façon sécurisée lorsque l\'accès aux systèmes, applications et services lui est accordé.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(162,'8.06  Dimensionnement','Assurer les besoins en termes de moyens de traitement de l\'information, de ressources humaines, de bureaux et autres installations.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(163,'8.07  Protection contre les programmes malveillants','S’assurer que les informations et autres actifs associés sont protégés contre les programmes malveillants.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(164,'8.08  Gestion des vulnérabilités techniques','Empêcher l’exploitation des vulnérabilités techniques.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(165,'8.09  Gestion de la configuration','S\'assurer que le matériel, les logiciels, les services et les réseaux fonctionnent correctement avec les paramètres de sécurité requis, et que la configuration n’est pas altérée par des changements non autorisés ou incorrects.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(166,'8.10  Suppression d\'information','Empêcher l\'exposition inutile des informations sensibles et se conformer aux exigences légales, statutaires, réglementaires et contractuelles relatives à la suppression d\'informations.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(167,'8.11  Masquage des données','Limiter l\'exposition des données sensibles, y compris les DCP, et se conformer aux exigences légales, statutaires, réglementaires et contractuelles.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(168,'8.12  Prévention de la fuite de données','Détecter et empêcher la divulgation et l\'extraction non autorisées d\'informations par des personnes ou des systèmes.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(169,'8.13  Sauvegarde des informations','Permettre la récupération en cas de perte de données ou de systèmes.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(170,'8.14  Redondance des moyens de traitement de l\'information','S\'assurer du fonctionnement continu des moyens de traitement de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(171,'8.15  Journalisation','Enregistrer les événements, générer des preuves, assurer l\'intégrité des informations de journalisation, empêcher les accès non autorisés, identifier les événements de sécurité de l\'information qui peuvent engendrer un incident de sécurité de l\'information et assister les investigations.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(172,'8.16  Activités de surveillance','Détecter les comportements anormaux et les éventuels incidents de sécurité de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(173,'8.17  Synchronisation des horloges','Permettre la corrélation et l\'analyse d’événements de sécurité et autres données enregistrées, assister les investigations sur les incidents de sécurité de l\'information.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(174,'8.18  Utilisation de programmes utilitaires à privilèges','S\'assurer que l\'utilisation de programmes utilitaires ne nuise pas aux mesures de sécurité de  l\'information des systèmes et des applications.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(175,'8.19  Installation de logiciels sur des systèmes en exploitation','Assurer l\'intégrité des systèmes opérationnels et empêcher l\'exploitation des vulnérabilités techniques.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(176,'8.20  Mesures liées aux réseaux','Protéger les informations dans les réseaux et les moyens de traitement de l\'information support contre  les compromission via le réseau.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(177,'8.21  Sécurité des services en réseau','Assurer la sécurité lors de l\'utilisation des services réseau.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(178,'8.22  Filtrage Internet','Diviser le réseau en périmètres de sécurité et contrôler le trafic entre eux en fonction des besoins  métier.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(179,'8.23  Cloisonnement des réseaux','Protéger les systèmes contre la compromission des programmes malveillants et empêcher l\'accès aux  ressources web non autorisées.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(180,'8.24  Utilisation de la cryptographie','Assurer l’utilisation correcte et efficace de la cryptographie afin de protéger la confidentialité,  l\'authenticité ou l\'intégrité des informations conformément aux exigences métier et de sécurité de  l\'information, et en tenant compte des exigences légales, statutaires, réglementaires et contractuelles  relatives à la cryptographie.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(181,'8.25  Cycle de vie de développement sécurisé','S\'assurer que la sécurité de l\'information est conçue et mise en œuvre au cours du cycle de vie de  développement sécurisé des logiciels et des systèmes.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(182,'8.26  Exigences de sécurité des applications','S\'assurer que toutes les exigences de sécurité de l\'information sont identifiées et traitées lors du  développement ou de l’acquisition d\'applications.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(183,'8.27  Principes d\'ingénierie et d\'architecture système sécurisée','S\'assurer que les systèmes d\'information sont conçus, mis en œuvre et exploités de manière sécurisée  au cours du cycle de vie de développement.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(184,'8.28  Codage sécurisé','S\'assurer que les logiciels sont développés de manière sécurisée afin de réduire le nombre d’éventuelles  vulnérabilités de sécurité de l\'information dans les logiciels.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(185,'8.29  Tests de sécurité dans le développement et l\'acceptation','Valider le respect des exigences de sécurité de l\'information lorsque des applications ou des codes sont  déployés dans l\'environnement .',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(186,'8.30  Développement externalisé','S\'assurer que les mesures de sécurité de l\'information requises par l\'organisation sont mises en œuvre  dans le cadre du développement externalisé des systèmes.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(187,'8.31  Séparation des environnements de développement, de test et de production','Protéger l\'environnement opérationnel et les données correspondantes contre les compromissions qui  pourraient être dues aux activités de développement et de test.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(188,'8.32  Gestion des changements','Préserver la sécurité de l\'information lors de l\'exécution des changements.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(189,'8.33  Informations relatives aux tests','Assurer la pertinence des tests et la protection des informations opérationnelles utilisées pour les tests.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(190,'8.34  Protection des systèmes d\'information en cours d\'audit et de test','Minimiser l\'impact des activités d\'audit et autres activités d\'assurance sur les systèmes opérationnels  et les processus métier.',NULL,'2024-04-14 07:05:13','2024-04-14 07:05:13'),(191,'Mesures organisationnelles','Mesures de sécurité organisationnelles (27001-2022) : organisation interne, gestion des actifs, classification de l\'information, gestion des accès, relation avec les fournisseurs, gestion de la prestation de service, gestion des événements de sécurité, continuité d\'activité, conformité à la législation, respect de la propriété intellectuelle, protection des enregistrements, protection de la vie privée, revue indépendante, conformité et exploitation documentée.','2024-04-14 07:11:30','2024-04-14 07:18:44',NULL),(192,'Protections physiques','Mesures de sécurité physiques (27001-2022) : zones de sécurité physiques et protection des matériels.','2024-04-14 07:12:45','2024-04-14 07:15:45',NULL),(193,'Mesures liées aux personnes','Mesures liées aux personnes (27001-2022) : recrutement, sensibilisation à la sécurité, engagement de conidentialité','2024-04-14 07:13:03','2024-04-14 07:14:51',NULL),(194,'Mesures technologiques','Mesures de sécurité technologiques (ISO 27001-2022) ; protection des postes de travail, contrôle d\'accès, gestion de la capacité, protection contre les logiciels malveillants, gestion des vulnérabilités techniques, gestion des configurations, sauvegarde, redondance, journalisation, protection des logiciels, gestion de la sécurité réseau, utilisation de la cryptographie, exigence de sécurité, développement, gestion des changements et audit des systèmes d\'information.','2024-04-14 07:13:55','2024-04-14 07:18:24',NULL);
/*!40000 ALTER TABLE `security_controls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `security_devices`
--

LOCK TABLES `security_devices` WRITE;
/*!40000 ALTER TABLE `security_devices` DISABLE KEYS */;
INSERT INTO `security_devices` (`id`, `name`, `description`, `vendor`, `product`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'FW01','<p>Firewall proncipal</p>',NULL,NULL,NULL,'2020-07-14 17:01:21','2020-07-14 17:01:21',NULL),(2,'FW02','<p>Backup Fireall</p>',NULL,NULL,NULL,'2020-07-14 17:02:21','2020-07-14 17:02:21',NULL);
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
INSERT INTO `storage_devices` (`id`, `name`, `description`, `vendor`, `product`, `version`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `bay_id`, `physical_switch_id`, `address_ip`) VALUES (1,'DiskServer 1','<p>Description du serveur d stockage 1</p>',NULL,NULL,NULL,'2020-06-21 15:30:16','2023-01-11 15:39:40',NULL,1,2,4,NULL,NULL),(2,'Oracle Server','<p>Main oracle server</p>',NULL,NULL,NULL,'2020-06-21 15:33:51','2020-06-21 15:34:38',NULL,1,2,2,NULL,NULL);
/*!40000 ALTER TABLE `storage_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `subnetworks`
--

LOCK TABLES `subnetworks` WRITE;
/*!40000 ALTER TABLE `subnetworks` DISABLE KEYS */;
INSERT INTO `subnetworks` (`id`, `description`, `address`, `ip_allocation_type`, `responsible_exp`, `dmz`, `wifi`, `name`, `created_at`, `updated_at`, `deleted_at`, `connected_subnets_id`, `gateway_id`, `zone`, `vlan_id`, `network_id`, `default_gateway`) VALUES (1,'<p>Description du sous-réseau 1</p>','10.10.0.0/16','Static','Marc','non','non','Subnet1','2020-06-23 12:35:41','2023-07-13 10:03:37',NULL,NULL,1,'ZONE_ACCUEIL',2,1,'10.10.0.1'),(2,'<p>Description du subnet 2</p>','10.20.0.0/16','Static','Henri','Oui','Oui','Subnet2','2020-07-04 07:35:10','2022-06-02 18:16:26',NULL,NULL,5,'ZONE_WORK',1,1,'10.20.0.1'),(3,'<p>Description du quatrième subnet</p>','10.40.0.0/16','Static','Jean','non','non','Subnet4','2020-11-06 12:56:33','2022-06-02 18:16:26',NULL,2,5,'ZONE_WORK',4,1,'10.40.0.1'),(4,'<p>descrption subnet 3</p>','8.8.8.8 /  255.255.255.0',NULL,NULL,NULL,NULL,'test subnet 3','2021-02-24 11:49:16','2021-02-24 11:49:33','2021-02-24 11:49:33',NULL,NULL,NULL,NULL,NULL,NULL),(5,'<p>Troisième sous-réseau</p>','10.30.0.0/16','Static','Jean','non','non','Subnet3','2021-05-19 14:48:39','2021-08-20 07:57:01',NULL,NULL,1,'ZONE_WORK',3,1,'10.30.0.1'),(6,'<p>Description du cinquième réseau</p>','10.50.0.0/16','Fixed','Jean','Oui','non','Subnet5','2021-08-17 11:35:28','2021-08-26 15:27:41',NULL,NULL,1,'ZONE_BACKUP',5,1,'10.50.0.1'),(7,'<p>Description du sixième sous-réseau</p>','10.60.0.0/16','Fixed','Jean','non','non','Subnet6','2021-08-17 16:32:47','2021-08-26 15:27:57',NULL,2,4,'ZONE_APP',6,2,'10.60.1.1'),(8,'<p>Test</p>',NULL,NULL,NULL,NULL,NULL,'Subnet7','2021-08-18 16:05:50','2021-08-18 16:10:19','2021-08-18 16:10:19',NULL,NULL,NULL,NULL,NULL,NULL),(9,'<p>Sous-réseau numéro sept</p>','10.70.0.0/16','Static','Jean','Oui','Oui','Subnet7','2021-08-18 16:11:10','2021-08-26 15:27:30',NULL,NULL,NULL,'ZONE_BACKUP',5,2,'10.70.0.1'),(10,'<p>Sous réseau démilitarisé</p>','10.70.0.0/16','Fixed','Jean','Oui','non','Subnet8','2021-08-18 16:33:48','2023-07-13 10:03:09',NULL,NULL,1,'ZONE_DMZ',7,1,'10.70.0.1'),(11,'<p>Description subnet 9</p>','10.90.0.0/16',NULL,'Jean','non','non','Subnet9','2021-09-07 16:41:02','2023-07-13 10:03:20',NULL,NULL,NULL,'ZONE_DATA',8,1,'10.90.1.1'),(12,NULL,NULL,NULL,'Jean','Oui','Oui','Réseau d\'administration \"toto\"','2022-07-07 14:40:37','2022-07-07 15:01:07','2022-07-07 15:01:07',NULL,5,'ZONE_APP',2,1,NULL);
/*!40000 ALTER TABLE `subnetworks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Tâche 2','Descriptionde la tâche 2','2020-06-13 00:04:07','2020-06-13 00:04:07',NULL),(2,'Tache 1','<p>Description de la tâche 1</p>','2020-06-13 00:04:21','2024-04-06 14:26:07',NULL),(3,'Tâche 3','Description de la tâche 3','2020-06-13 00:04:41','2020-06-13 00:04:41',NULL),(4,'Tâche 4','<p>Description de ta <strong>tâche 4</strong></p>','2024-04-06 14:29:52','2024-04-06 14:31:00',NULL);
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
INSERT INTO `wifi_terminals` (`id`, `name`, `description`, `vendor`, `product`, `version`, `type`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `address_ip`) VALUES (1,'WIFI_01','<p>Borne wifi 01</p>',NULL,NULL,NULL,'Alcatel 3500','2020-07-22 14:44:37','2020-07-22 14:44:37',NULL,1,2,NULL),(2,'WIFI_02','<p>Borne Wifi 2</p>',NULL,NULL,NULL,'ALCALSYS 3001','2021-06-07 14:37:47','2021-06-07 14:37:47',NULL,2,1,NULL),(3,'WIFI_03','<p>Borne Wifi 3</p>',NULL,NULL,NULL,'SYSTEL 3310','2021-06-07 14:42:29','2021-06-07 14:43:18',NULL,3,4,NULL);
/*!40000 ALTER TABLE `wifi_terminals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `workstations`
--

LOCK TABLES `workstations` WRITE;
/*!40000 ALTER TABLE `workstations` DISABLE KEYS */;
INSERT INTO `workstations` (`id`, `name`, `description`, `vendor`, `product`, `version`, `created_at`, `updated_at`, `deleted_at`, `site_id`, `building_id`, `physical_switch_id`, `type`, `operating_system`, `address_ip`, `cpu`, `memory`, `disk`) VALUES (1,'Workstation compta','<p>10 stations de travail de la compta</p><p>Modèle HAL 6740</p>',NULL,NULL,NULL,'2020-06-21 15:09:04','2023-10-26 12:02:00',NULL,1,7,NULL,'ThinThink 460','Windows 11','10.10.43.2','Intel i5','4',120),(2,'Workstation accueil','<p>10 stations de travail de l\'accueil</p><p>Model HAL 2832</p>',NULL,NULL,NULL,'2020-06-21 15:09:54','2023-10-26 12:00:49',NULL,2,3,NULL,'ThinThink 410','Windows 10',NULL,'Intel i5','6',10),(3,'Workstation back-office','<p>12 Stations de travail back-office</p><p>Modèle HAL 8760</p>',NULL,NULL,NULL,'2020-06-21 15:17:57','2023-10-26 12:03:35',NULL,2,4,NULL,'ThinThink 420','Windows 10',NULL,'Intel i5','6',500),(4,'Workstation RH','<p>2 workstations RH</p><p>Modèle HAL 7690</p>',NULL,NULL,NULL,'2022-06-27 08:53:58','2023-10-26 12:04:14',NULL,3,2,NULL,'ThinThink 420','Windows 10','10.10.21.3','Intel i7','4',250),(5,'Workstations helpdesk','<p>3 workstation helpdesk</p><p>Modèle HAL 5850</p>',NULL,NULL,NULL,'2022-06-27 09:36:52','2023-10-26 12:04:49',NULL,1,7,NULL,'ThinThink 420','Windows 10','10.10.43.4','Intel i7','8',250),(6,'Workstation analyse','<p>3 workstations analyse</p><p>Model HAL 4680</p>',NULL,NULL,NULL,'2022-06-27 09:37:54','2023-10-26 12:05:35',NULL,1,2,NULL,'ThinThink 420','Windows 11','10.23.54.3','Intel i7','4',500),(7,'Workstation IT','<p>3 workstations IT</p><p>Model HAL 5730</p>',NULL,NULL,NULL,'2022-12-22 22:05:24','2023-10-26 12:06:12',NULL,1,7,NULL,'ThinThink 410','Windows 10',NULL,'Intel i5','8',500),(8,'Workstation 8',NULL,NULL,NULL,NULL,'2022-12-22 22:05:42','2023-01-11 18:41:37',NULL,3,5,NULL,'ThinThink 460',NULL,NULL,NULL,NULL,NULL),(9,'Workstation Management','<p>3 workstation management</p><p>Model HAL 4730</p>',NULL,NULL,NULL,'2022-12-22 22:06:00','2023-10-26 12:06:48',NULL,3,5,NULL,'ThinThink 460',NULL,NULL,'Intel i5','10',120),(10,'Workstations R&D','<p>5 stations de travails&nbsp;</p><p>Recherche et développement</p><p>Modèle HAL 3740</p>',NULL,NULL,NULL,'2022-12-22 22:06:17','2023-10-26 12:05:01',NULL,3,5,NULL,'ThinThink 460','Windows 10',NULL,'Intel i5','8',120),(11,'Workstation 11',NULL,NULL,NULL,NULL,'2023-01-12 17:47:14','2023-01-12 17:47:14',NULL,1,1,NULL,'ThinThink 420','Windows 11',NULL,'Intel i7',NULL,NULL),(12,'Workstation 12','<p>Description workstation 12</p>',NULL,NULL,NULL,'2023-01-14 17:57:50','2023-01-14 17:57:50',NULL,3,5,NULL,'ThinThink 420','Windows 11','10.10.12.1','Intel i7',NULL,NULL),(13,'Workstation 13','<p>Description workstation 13</p>',NULL,NULL,NULL,'2023-01-14 17:58:27','2023-01-14 17:58:27',NULL,3,5,NULL,'ThinThink 420','Windows 11',NULL,'Intel i5',NULL,NULL),(14,'Workstation deleted','<p>Teste deleted workstation</p>',NULL,NULL,NULL,'2023-12-02 15:32:05','2023-12-02 15:33:29','2023-12-02 15:33:29',1,2,NULL,'ThinThink 420','Windows 10','10.10.12.3','Intel i5','8',120);
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

-- Dump completed on 2024-04-28 10:40:29
