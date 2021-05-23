-- MySQL dump 10.13  Distrib 8.0.25, for Linux (x86_64)
--
-- Host: localhost    Database: mercator
-- ------------------------------------------------------
-- Server version	8.0.25-0ubuntu0.20.04.1

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
INSERT INTO `activities` VALUES (1,'Activité 1','<p>Description de l\'activité 1</p>','2020-06-10 13:20:42','2020-06-10 13:20:42',NULL),(2,'Activité 2','<p>Description de l\'activité de test</p>','2020-06-10 15:44:26','2020-06-13 04:03:26',NULL),(3,'Activité 3','<p>Description de l\'activité 3</p>','2020-06-13 04:57:08','2020-06-13 04:57:08',NULL),(4,'Activité 4','<p>Description de l\'acivité 4</p>','2020-06-13 04:57:24','2020-06-13 04:57:24',NULL),(5,'Activité principale','<p>Description de l\'activité principale</p>','2020-08-15 04:19:53','2020-08-15 04:19:53',NULL),(6,'AAA','test a1','2021-03-22 19:06:55','2021-03-22 19:07:00','2021-03-22 19:07:00'),(7,'AAA','test AAA','2021-03-22 19:13:43','2021-03-22 19:14:05','2021-03-22 19:14:05'),(8,'AAA','test 2 aaa','2021-03-22 19:14:16','2021-03-22 19:14:45','2021-03-22 19:14:45'),(9,'AAA1','test 3 AAA','2021-03-22 19:14:40','2021-03-22 19:19:09','2021-03-22 19:19:09'),(10,'Activité 0','<p>Description de l\'activité zéro</p>','2021-03-23 07:39:55','2021-05-15 07:40:16',NULL);
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_operation`
--

LOCK TABLES `activity_operation` WRITE;
/*!40000 ALTER TABLE `activity_operation` DISABLE KEYS */;
INSERT INTO `activity_operation` VALUES (2,3),(1,1),(1,2),(4,3),(3,1),(1,5),(5,1),(6,1),(10,1);
/*!40000 ALTER TABLE `activity_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_process`
--

LOCK TABLES `activity_process` WRITE;
/*!40000 ALTER TABLE `activity_process` DISABLE KEYS */;
INSERT INTO `activity_process` VALUES (1,1),(1,2),(2,3),(2,4),(3,2),(3,5),(4,5),(5,4),(6,4),(7,3),(8,4),(9,3),(1,10);
/*!40000 ALTER TABLE `activity_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `actor_operation`
--

LOCK TABLES `actor_operation` WRITE;
/*!40000 ALTER TABLE `actor_operation` DISABLE KEYS */;
INSERT INTO `actor_operation` VALUES (2,1),(1,1),(1,4),(2,5),(3,6),(5,4);
/*!40000 ALTER TABLE `actor_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `actors`
--

LOCK TABLES `actors` WRITE;
/*!40000 ALTER TABLE `actors` DISABLE KEYS */;
INSERT INTO `actors` VALUES (1,'Jean','Personne','interne','jean@testdomain.org','2020-06-14 11:02:22','2021-05-16 17:37:49',NULL),(2,'Service 1','Groupe','interne',NULL,'2020-06-14 11:02:39','2020-06-17 14:43:42','2020-06-17 14:43:42'),(3,'Service 2','Groupe','Interne',NULL,'2020-06-14 11:02:54','2020-06-17 14:43:46','2020-06-17 14:43:46'),(4,'Pierre','Personne','interne','email : pierre@testdomain.com','2020-06-17 14:44:01','2021-05-16 17:38:19',NULL),(5,'Jacques','personne','interne','Téléphone 1234543423','2020-06-17 14:44:23','2020-06-17 14:44:23',NULL),(6,'Fournisseur 1','entité','externe','Tel : 1232 32312','2020-06-17 14:44:50','2020-06-17 14:44:50',NULL);
/*!40000 ALTER TABLE `actors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `annuaires`
--

LOCK TABLES `annuaires` WRITE;
/*!40000 ALTER TABLE `annuaires` DISABLE KEYS */;
INSERT INTO `annuaires` VALUES (1,'AD01','<p>Anniaure principal du CHEM</p>','Acive Directory','2020-07-03 07:49:37','2021-05-23 13:07:36',NULL,1),(2,'Mercator','<p>Cartographie du système d\'information</p>','Logiciel développé maison','2020-07-03 10:24:48','2020-07-13 15:12:59',NULL,1);
/*!40000 ALTER TABLE `annuaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_blocks`
--

LOCK TABLES `application_blocks` WRITE;
/*!40000 ALTER TABLE `application_blocks` DISABLE KEYS */;
INSERT INTO `application_blocks` VALUES (1,'Bloc applicatif 1','<p>Description du bloc applicatif</p>','Jean Pierre','2020-06-13 04:09:01','2020-06-13 04:09:01',NULL),(2,'Bloc applicatif 2','<p>Second bloc applicatif.</p>','Marcel pierre','2020-06-13 04:10:52','2020-06-17 16:13:33',NULL),(3,'Test block1','<p>toto</p>','Nestor','2020-08-29 12:00:10','2020-08-29 12:00:10',NULL);
/*!40000 ALTER TABLE `application_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_module_application_service`
--

LOCK TABLES `application_module_application_service` WRITE;
/*!40000 ALTER TABLE `application_module_application_service` DISABLE KEYS */;
INSERT INTO `application_module_application_service` VALUES (4,1),(4,2),(3,3),(2,4),(1,5),(1,6);
/*!40000 ALTER TABLE `application_module_application_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_modules`
--

LOCK TABLES `application_modules` WRITE;
/*!40000 ALTER TABLE `application_modules` DISABLE KEYS */;
INSERT INTO `application_modules` VALUES (1,'Module 1','<p>Description du module 1</p>','2020-06-13 09:55:34','2020-06-13 09:55:34',NULL),(2,'Module 2','<p>Description du module 2</p>','2020-06-13 09:55:45','2020-06-13 09:55:45',NULL),(3,'Module 3','<p>Description du module 3</p>','2020-06-13 09:56:00','2020-06-13 09:56:00',NULL),(4,'Module 4','<p>Description du module 4</p>','2020-06-13 09:56:10','2020-06-13 09:56:10',NULL),(5,'Module 5','<p>Description du module 5</p>','2020-06-13 09:56:20','2020-06-13 09:56:20',NULL),(6,'Module 6','<p>Description du module 6</p>','2020-06-13 09:56:32','2020-06-13 09:56:32',NULL);
/*!40000 ALTER TABLE `application_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_service_m_application`
--

LOCK TABLES `application_service_m_application` WRITE;
/*!40000 ALTER TABLE `application_service_m_application` DISABLE KEYS */;
INSERT INTO `application_service_m_application` VALUES (2,3),(2,4),(1,3);
/*!40000 ALTER TABLE `application_service_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_services`
--

LOCK TABLES `application_services` WRITE;
/*!40000 ALTER TABLE `application_services` DISABLE KEYS */;
INSERT INTO `application_services` VALUES (1,'<p>Descrition du service applicatif 1</p>','cloud','Service 1','2020-06-13 09:35:31','2020-06-13 09:42:56',NULL),(2,'<p>Description du service 2</p>','local','Service 2','2020-06-13 09:35:48','2020-06-13 09:35:48',NULL),(3,'<p>Description du service 3</p>','local','Service 3','2020-06-13 09:36:04','2020-06-13 09:43:05',NULL),(4,'<p>Description du service 4</p>','local','Service 4','2020-06-13 09:36:17','2020-06-13 09:36:17',NULL);
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
INSERT INTO `bays` VALUES (1,'BAIE 101','<p>Description de la baie 101</p>','2020-06-21 04:56:01','2020-06-21 05:05:53',NULL,1),(2,'BAIE 102','<p>Desciption baie 102</p>','2020-06-21 04:56:20','2020-06-21 04:56:20',NULL,1),(3,'BAIE 103','<p>Descripton baid 103</p>','2020-06-21 04:56:38','2020-06-21 04:56:38',NULL,1),(4,'BAIE 201','<p>Description baie 201</p>','2020-06-21 04:56:55','2020-06-21 04:56:55',NULL,2),(5,'BAIE 301','<p>Baie 301</p>','2020-07-15 18:03:07','2020-07-15 18:03:07',NULL,3),(6,'BAIE 501','<p>Baie 501</p>','2020-07-15 18:10:23','2020-07-15 18:10:23',NULL,5);
/*!40000 ALTER TABLE `bays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `buildings`
--

LOCK TABLES `buildings` WRITE;
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
INSERT INTO `buildings` VALUES (1,'Building 1','<p>Description du building 1</p>','2020-06-21 04:37:21','2020-06-21 04:47:41',NULL,1,NULL,NULL),(2,'Building 2','<p>Description du building 2</p>','2020-06-21 04:37:36','2020-07-25 06:26:13',NULL,1,NULL,NULL),(3,'Building 3','<p>Description du building 3</p>','2020-06-21 04:37:48','2020-07-25 06:26:03',NULL,2,NULL,NULL),(4,'Building 4','<p>Description du building 4</p>','2020-06-21 04:38:03','2020-07-25 06:25:54',NULL,2,NULL,NULL),(5,'Building 5','<p>Descripion du building 5</p>','2020-06-21 04:38:16','2020-07-25 06:26:26',NULL,3,NULL,NULL),(6,'Test building','<p>Description</p>','2020-07-24 19:12:48','2020-07-24 19:14:08','2020-07-24 19:14:08',4,NULL,NULL),(7,'Building 0','<p>Le building zéro</p>','2020-08-21 13:10:15','2020-10-02 07:38:55',NULL,1,NULL,NULL),(8,'test','<p>test</p>','2020-11-06 13:44:22','2020-11-06 14:26:18','2020-11-06 14:26:18',NULL,1,0),(9,'test2','<p>test2</p>','2020-11-06 13:59:45','2020-11-06 14:06:50','2020-11-06 14:06:50',NULL,NULL,NULL),(10,'test3','<p>fdfsdfsd</p>','2020-11-06 14:07:07','2020-11-06 14:26:18','2020-11-06 14:26:18',NULL,NULL,NULL),(11,'test4',NULL,'2020-11-06 14:25:52','2020-11-06 14:26:18','2020-11-06 14:26:18',NULL,0,0);
/*!40000 ALTER TABLE `buildings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_entity`
--

LOCK TABLES `database_entity` WRITE;
/*!40000 ALTER TABLE `database_entity` DISABLE KEYS */;
INSERT INTO `database_entity` VALUES (1,1),(3,1),(4,1),(5,1),(6,1);
/*!40000 ALTER TABLE `database_entity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_information`
--

LOCK TABLES `database_information` WRITE;
/*!40000 ALTER TABLE `database_information` DISABLE KEYS */;
INSERT INTO `database_information` VALUES (1,1),(1,2),(1,3),(3,2),(3,3),(5,1),(4,2),(6,2),(6,3);
/*!40000 ALTER TABLE `database_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `database_m_application`
--

LOCK TABLES `database_m_application` WRITE;
/*!40000 ALTER TABLE `database_m_application` DISABLE KEYS */;
INSERT INTO `database_m_application` VALUES (2,3),(3,4),(3,1),(4,5),(4,6);
/*!40000 ALTER TABLE `database_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `databases`
--

LOCK TABLES `databases` WRITE;
/*!40000 ALTER TABLE `databases` DISABLE KEYS */;
INSERT INTO `databases` VALUES (1,'Database 1','<p>Description Database 1</p>','Paul','MySQL',1,'Interne','2020-06-17 14:18:48','2021-05-14 10:19:45',NULL,1,2,3,4),(3,'Database 2','<p>Description database 2</p>','Paul','MySQL',1,'Interne','2020-06-17 14:19:24','2021-05-14 10:29:47',NULL,1,1,1,1),(4,'MainDB','<p>description de la base de données</p>','Paul','Oracle',2,'Interne','2020-07-01 15:08:57','2020-08-24 15:43:49',NULL,4,NULL,NULL,NULL),(5,'DB Compta','<p>BAse de donnée de la compta</p>','Paul','MariaDB',1,'Interne','2020-08-24 15:58:23','2020-08-24 16:03:34',NULL,1,NULL,NULL,NULL),(6,'Data Warehouse','<p>Base de données du datawarehouse</p>','Jean','Oracle',4,'Interne','2021-05-14 10:24:02','2021-05-14 10:24:02',NULL,1,3,4,3);
/*!40000 ALTER TABLE `databases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `dhcp_servers`
--

LOCK TABLES `dhcp_servers` WRITE;
/*!40000 ALTER TABLE `dhcp_servers` DISABLE KEYS */;
INSERT INTO `dhcp_servers` VALUES (1,'DHCP_1','<p>Serveur DHCP primaire</p>','2020-07-23 08:34:43','2020-07-23 08:34:43',NULL);
/*!40000 ALTER TABLE `dhcp_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `dnsservers`
--

LOCK TABLES `dnsservers` WRITE;
/*!40000 ALTER TABLE `dnsservers` DISABLE KEYS */;
INSERT INTO `dnsservers` VALUES (1,'DNS_1','<p>Serveur DNS primaire</p>','2020-07-23 08:31:39','2020-07-23 08:31:39',NULL),(2,'DNS_2','<p>Serveur DNS secondaire</p>','2020-07-23 08:31:50','2020-07-23 08:31:50',NULL);
/*!40000 ALTER TABLE `dnsservers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `domaine_ad_forest_ad`
--

LOCK TABLES `domaine_ad_forest_ad` WRITE;
/*!40000 ALTER TABLE `domaine_ad_forest_ad` DISABLE KEYS */;
INSERT INTO `domaine_ad_forest_ad` VALUES (1,1),(2,1);
/*!40000 ALTER TABLE `domaine_ad_forest_ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `domaine_ads`
--

LOCK TABLES `domaine_ads` WRITE;
/*!40000 ALTER TABLE `domaine_ads` DISABLE KEYS */;
INSERT INTO `domaine_ads` VALUES (1,'Dom1','<p>Domaine AD1</p>',3,2000,800,'Non','2020-07-03 07:51:06','2020-07-03 07:51:06',NULL);
/*!40000 ALTER TABLE `domaine_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entities`
--

LOCK TABLES `entities` WRITE;
/*!40000 ALTER TABLE `entities` DISABLE KEYS */;
INSERT INTO `entities` VALUES (1,'MegaNet System','<p>ISO 27001</p>','<p>Helpdek<br>27, Rue des poire&nbsp;<br>12043 Mire-en-Mare le Bains</p><p>helpdes@menetsys.org</p><p>&nbsp;</p>','<p>Fournisseur équipement réseau</p>','2020-05-21 02:30:59','2021-05-23 12:58:42',NULL),(2,'Entité1','<p>Néant</p>','<ul><li>Commercial</li><li>Service Delivery</li><li>Helpdesk</li></ul>','<p>Entité de tests1</p>','2020-05-21 02:31:17','2021-05-23 12:59:11',NULL),(3,'CHdN','3','RSSI du CHdN','<p>Centre Hospitalier du Nord</p>','2020-05-21 02:43:41','2021-05-13 08:20:32','2021-05-13 08:20:32'),(4,'Entité3','<p>ISO 9001</p>','<p>Point de contact de la troisième entoté</p>','<p>Description de la troisième entoté</p>','2020-05-21 02:44:03','2021-05-23 13:00:37',NULL),(5,'entité6','<p>Néant</p>','<p>support_informatque@entite6.fr</p>','<p>Description de l\'entité six</p>','2020-05-21 02:44:18','2021-05-23 13:03:15',NULL),(6,'Entité4','<p>ISO 27001</p>','<p>Pierre Pinon<br>Tel: 00 34 392 484 22</p>','<p>Description de l\'entté quatre</p>','2020-05-21 02:45:14','2021-05-23 13:01:17',NULL),(7,'Entité5','<p>Néant</p>','<p>Servicdesk@entite5.fr</p>','<p>Description de l\'entité 5</p>','2020-05-21 03:38:41','2021-05-23 13:02:16',NULL),(8,'Entité2','<p>ISO 27001</p>','<p>Point de contact de l\'entité 2</p>','<p>Description de l\'entité 2</p>','2020-05-21 03:54:22','2021-05-23 13:00:03',NULL),(9,'NetworkSys','<p>ISO 27001</p>','<p>support@networksys.fr</p>','<p>Description de l’entité NetworkSys</p>','2020-05-21 06:25:15','2021-05-23 13:04:06',NULL),(10,'Agence eSanté','<p>Néant</p>','<p>helpdesk@esante.lu</p>','<p>Agence Nationale des information partagées dans le domaine de la santé</p><ul><li>a</li><li>b</li><li>c</li></ul><p>+-------+<br>+ TOTO +<br>+-------+</p><p>&lt;&lt;&lt;&lt;&lt;&lt; &gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;</p>','2020-05-21 06:25:26','2021-05-13 08:20:32','2021-05-13 08:20:32'),(11,'Test','4',NULL,'<p>Test</p>','2020-07-02 15:37:29','2020-07-02 15:37:44','2020-07-02 15:37:44'),(12,'Entité de test','<p>Néant</p>','<p>Paul Pierre<br>Gérant<br>00 33 4943 432 423</p>','<p>Description de l\'entité de test</p>','2020-07-06 13:37:54','2021-05-23 13:04:47',NULL),(13,'Nestor','<p>Haut niveau</p>','<p>Paul, Pierre et Jean</p>','<p>Description de Nestor</p>','2020-08-12 16:11:31','2020-08-12 16:12:13',NULL);
/*!40000 ALTER TABLE `entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entity_m_application`
--

LOCK TABLES `entity_m_application` WRITE;
/*!40000 ALTER TABLE `entity_m_application` DISABLE KEYS */;
INSERT INTO `entity_m_application` VALUES (2,1),(5,1),(7,2),(9,1),(10,1),(2,2),(11,1),(1,2),(1,8),(3,8);
/*!40000 ALTER TABLE `entity_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entity_process`
--

LOCK TABLES `entity_process` WRITE;
/*!40000 ALTER TABLE `entity_process` DISABLE KEYS */;
INSERT INTO `entity_process` VALUES (1,1),(2,1),(3,1),(1,13),(3,13),(4,1),(7,3),(9,4),(2,8),(4,6),(4,7),(9,5),(1,9),(2,9),(3,9),(4,9),(9,9),(1,2),(1,12);
/*!40000 ALTER TABLE `entity_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `external_connected_entities`
--

LOCK TABLES `external_connected_entities` WRITE;
/*!40000 ALTER TABLE `external_connected_entities` DISABLE KEYS */;
INSERT INTO `external_connected_entities` VALUES (1,'Entité externe 1','Nestor','Marcel','2020-07-23 07:59:25','2020-07-23 07:59:25',NULL);
/*!40000 ALTER TABLE `external_connected_entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `external_connected_entity_network`
--

LOCK TABLES `external_connected_entity_network` WRITE;
/*!40000 ALTER TABLE `external_connected_entity_network` DISABLE KEYS */;
INSERT INTO `external_connected_entity_network` VALUES (1,1);
/*!40000 ALTER TABLE `external_connected_entity_network` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fluxes`
--

LOCK TABLES `fluxes` WRITE;
/*!40000 ALTER TABLE `fluxes` DISABLE KEYS */;
INSERT INTO `fluxes` VALUES (2,'FluxA','Description du flux A','2020-06-17 14:50:59','2020-07-07 14:01:37',NULL,1,NULL,NULL,NULL,2,NULL,NULL,NULL,0),(3,'FluxTest','Flux de test','2020-07-07 13:58:22','2020-07-07 14:01:23',NULL,2,NULL,NULL,NULL,3,NULL,NULL,NULL,1),(4,'FluxA','Flux de test 3','2020-07-07 14:01:10','2020-09-05 05:11:49',NULL,NULL,NULL,4,NULL,2,NULL,NULL,NULL,1),(5,'Flux_DB_01','Description du flux 01','2020-07-23 10:44:35','2020-07-23 10:44:35',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,3,1),(6,'Flux_MOD_01','Fuld module','2020-07-23 10:48:20','2020-07-23 10:48:20',NULL,NULL,NULL,3,NULL,NULL,NULL,2,NULL,1),(7,'Flux_SER_01','Description du flux service 01','2020-07-23 10:51:41','2020-07-23 10:51:41',NULL,NULL,3,NULL,NULL,NULL,4,NULL,NULL,0),(8,'Fulx 07','Description du flux 07','2020-09-05 04:56:57','2020-09-05 04:57:36',NULL,NULL,1,NULL,NULL,NULL,2,NULL,NULL,1),(9,'FLux DB_02','Description du flux 2','2020-09-05 05:12:05','2020-12-10 10:13:36',NULL,1,NULL,NULL,1,NULL,NULL,NULL,3,1);
/*!40000 ALTER TABLE `fluxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `forest_ads`
--

LOCK TABLES `forest_ads` WRITE;
/*!40000 ALTER TABLE `forest_ads` DISABLE KEYS */;
INSERT INTO `forest_ads` VALUES (1,'AD1','<p>Foret de l\'AD 1</p>','2020-07-03 07:50:07','2020-07-03 07:50:29',NULL,1),(2,'AD2','<p>Foret de l\'AD2</p>','2020-07-03 07:50:19','2020-07-03 07:50:19',NULL,1);
/*!40000 ALTER TABLE `forest_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `gateways`
--

LOCK TABLES `gateways` WRITE;
/*!40000 ALTER TABLE `gateways` DISABLE KEYS */;
INSERT INTO `gateways` VALUES (1,'GW01','<p>Gateway 01 vers réseau médor</p>','123.5.6.4/12','Carte à puce','2020-07-13 17:34:45','2020-07-13 17:34:45',NULL),(2,'Workspace One','<p>Test workspace One</p>','10.10.10.1','Token','2021-04-17 19:32:57','2021-04-17 19:40:31','2021-04-17 19:40:31'),(3,'PubicGW','<p>Public Gateway</p>','10.10.10.1','Token','2021-04-17 19:39:04','2021-04-17 19:40:25','2021-04-17 19:40:25'),(4,'PublicGW','<p>Public gateway</p>','8.8.8.8','Token','2021-04-17 19:40:48','2021-04-17 19:48:34',NULL),(5,'GW02','<p>Segonde gateway</p>',NULL,'Token','2021-05-18 18:27:13','2021-05-18 18:27:13',NULL);
/*!40000 ALTER TABLE `gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `information`
--

LOCK TABLES `information` WRITE;
/*!40000 ALTER TABLE `information` DISABLE KEYS */;
INSERT INTO `information` VALUES (1,'Information 1','<p>Description de l\'information 1</p>','CHEM','Nom de l\'administrateur','externe',1,'Donnée à caractère personnel','<p>Description des contraintes règlementaires et normatives</p>','2020-06-13 00:06:43','2020-11-24 09:53:24',NULL,NULL,NULL,NULL),(2,'information 2','<p>Description de l\'information</p>','CHEM','Nom de l\'administrateur','externe',2,'Donnée à caractère personnel',NULL,'2020-06-13 00:09:13','2020-11-24 09:55:16',NULL,NULL,NULL,NULL),(3,'information 3','<p>Descripton de l\'information 3</p>','Proriétaire',NULL,NULL,4,NULL,NULL,'2020-06-13 00:10:07','2020-07-01 14:50:29',NULL,NULL,NULL,NULL),(4,'Information de test','<p>decription du test</p>','RSSI',NULL,NULL,2,NULL,NULL,'2020-07-01 15:00:37','2020-07-01 15:00:58',NULL,NULL,NULL,NULL),(5,'Données du client','<p>Données d\'identification du client</p>','CHEM','Paul','Local',4,'Donnée à caractère personnel','<p>RGPD</p>','2021-05-14 10:50:09','2021-05-14 10:59:39',NULL,3,4,3);
/*!40000 ALTER TABLE `information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `information_process`
--

LOCK TABLES `information_process` WRITE;
/*!40000 ALTER TABLE `information_process` DISABLE KEYS */;
INSERT INTO `information_process` VALUES (3,2),(4,3),(4,4),(4,7),(4,8),(4,1),(1,4),(2,9),(5,1),(5,2),(5,4),(5,9);
/*!40000 ALTER TABLE `information_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `lan_man`
--

LOCK TABLES `lan_man` WRITE;
/*!40000 ALTER TABLE `lan_man` DISABLE KEYS */;
INSERT INTO `lan_man` VALUES (1,1),(2,1);
/*!40000 ALTER TABLE `lan_man` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `lan_wan`
--

LOCK TABLES `lan_wan` WRITE;
/*!40000 ALTER TABLE `lan_wan` DISABLE KEYS */;
INSERT INTO `lan_wan` VALUES (1,1);
/*!40000 ALTER TABLE `lan_wan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `lans`
--

LOCK TABLES `lans` WRITE;
/*!40000 ALTER TABLE `lans` DISABLE KEYS */;
INSERT INTO `lans` VALUES (1,'LAN_1','Lan principal','2020-07-22 05:42:00','2020-07-22 05:42:00',NULL);
/*!40000 ALTER TABLE `lans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_server_m_application`
--

LOCK TABLES `logical_server_m_application` WRITE;
/*!40000 ALTER TABLE `logical_server_m_application` DISABLE KEYS */;
INSERT INTO `logical_server_m_application` VALUES (2,1),(2,2),(3,2),(1,1);
/*!40000 ALTER TABLE `logical_server_m_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_server_physical_server`
--

LOCK TABLES `logical_server_physical_server` WRITE;
/*!40000 ALTER TABLE `logical_server_physical_server` DISABLE KEYS */;
INSERT INTO `logical_server_physical_server` VALUES (2,1),(2,2),(1,1);
/*!40000 ALTER TABLE `logical_server_physical_server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `logical_servers`
--

LOCK TABLES `logical_servers` WRITE;
/*!40000 ALTER TABLE `logical_servers` DISABLE KEYS */;
INSERT INTO `logical_servers` VALUES (1,'SRV-1','<p>Description du serveur 1</p>','DNS, HTTP, HTTPS','<p>Configuration</p>','2020-07-12 16:57:42','2020-09-03 13:41:47',NULL,'Windows 3.1',NULL,'2','8','PROD',NULL),(2,'SRV-2','<p>Description du serveur 2</p>','HTTPS, SSH','<p>default</p>','2020-07-30 10:00:16','2020-08-29 13:20:26',NULL,'Windows 10',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `logical_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_application_process`
--

LOCK TABLES `m_application_process` WRITE;
/*!40000 ALTER TABLE `m_application_process` DISABLE KEYS */;
INSERT INTO `m_application_process` VALUES (2,1),(2,2),(3,2),(1,1),(14,2),(4,3),(12,4);
/*!40000 ALTER TABLE `m_application_process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `m_applications`
--

LOCK TABLES `m_applications` WRITE;
/*!40000 ALTER TABLE `m_applications` DISABLE KEYS */;
INSERT INTO `m_applications` VALUES (1,'Application 1','<p>Description de l\'application 1</p>',1,'RSSI','logiciel','Microsoft','Interne','> 20','2020-06-14 09:20:15','2021-05-15 07:54:01',NULL,1,3,'Docuemntaton/application1.docx',1,1,1),(2,'Application 2','<p><i>Description</i> de l\'<strong>application</strong> 2</p>',2,'RSSI','progiciel','martian','SaaS','>100','2020-06-14 09:31:16','2021-05-15 08:05:57',NULL,1,1,'None',2,2,2),(3,'Application 3','<p>Test application 3</p>',1,'RSSI','progiciel','Microsoft','Interne','>100','2020-06-17 17:33:41','2021-05-15 08:06:53',NULL,8,2,'Aucune',2,3,3),(4,'Application 4','<p>Description app4</p>',2,'RSSI','logiciel','Microsoft','Internl',NULL,'2020-08-11 14:13:02','2021-05-13 08:17:41',NULL,NULL,2,NULL,NULL,NULL,NULL),(5,'CUST AP01','<p>Customer appication</p>',0,NULL,NULL,'web',NULL,NULL,'2020-08-22 04:58:18','2020-08-26 14:56:20','2020-08-26 14:56:20',1,NULL,NULL,NULL,NULL,NULL),(6,'totototo',NULL,0,NULL,NULL,'totottoo',NULL,NULL,'2020-08-22 04:59:26','2020-08-22 04:59:43','2020-08-22 04:59:43',NULL,NULL,NULL,NULL,NULL,NULL),(7,'Windows Word','<p>Description de l\'application</p>',3,'Nestor','artificiel','client lourd',NULL,'>100','2020-08-23 08:20:34','2020-08-26 14:56:23','2020-08-26 14:56:23',10,2,NULL,NULL,NULL,NULL),(8,'Application 99',NULL,1,'André','progiciel','client lourd','SaaS','>100','2020-08-23 10:08:02','2020-08-26 14:56:13','2020-08-26 14:56:13',NULL,NULL,NULL,NULL,NULL,NULL),(9,'Test33','<p>fsfsdfsd</p>',0,'Nestor','progiciel','martian',NULL,NULL,'2020-08-26 14:54:05','2020-08-26 14:54:35','2020-08-26 14:54:35',10,NULL,NULL,NULL,NULL,NULL),(10,'Test33R','<p>fsfsdfsd</p>',0,'Nestor','progiciel','martian',NULL,NULL,'2020-08-26 14:54:28','2020-08-26 14:54:39','2020-08-26 14:54:39',10,NULL,NULL,NULL,NULL,NULL),(11,'SuperApp','<p>Supper application</p>',0,'RSSI','logiciel','martian',NULL,NULL,'2021-04-12 14:54:57','2021-04-12 17:10:44','2021-04-12 17:10:44',NULL,NULL,NULL,NULL,NULL,NULL),(12,'SuperApp','<p>Super super application !</p>',1,'RSSI','Web','Oracle','Interne',NULL,'2021-04-12 17:10:59','2021-05-15 08:03:19',NULL,NULL,NULL,NULL,1,1,1),(13,'test application',NULL,0,NULL,NULL,NULL,NULL,NULL,'2021-05-07 08:23:59','2021-05-07 08:24:03','2021-05-07 08:24:03',NULL,NULL,NULL,NULL,NULL,NULL),(14,'Windows Calc','<p>Calculatrice windows</p>',2,'RSSI','logiciel','Microsoft','Internl',NULL,'2021-05-13 08:15:27','2021-05-13 08:15:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,'Compta','<p>Application de comptabilité</p>',3,'RSSI','progiciel','Microsoft','Interne','>100','2021-05-15 07:53:15','2021-05-15 07:53:15',NULL,2,2,NULL,4,2,3);
/*!40000 ALTER TABLE `m_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `macro_processuses`
--

LOCK TABLES `macro_processuses` WRITE;
/*!40000 ALTER TABLE `macro_processuses` DISABLE KEYS */;
INSERT INTO `macro_processuses` VALUES (1,'Macro-Processus 1','<p>Description du macro-processus de test.</p>','<p>Entrant :</p><ul><li>donnée 1</li><li>donnée 2</li><li>donnée 3</li></ul><p>Sortant :</p><ul><li>donnée 4</li><li>donnée 5</li></ul>',4,'Nestor','2020-06-10 07:02:16','2021-05-14 13:29:36',NULL,3,2,1),(2,'Macro-Processus 2','<p>Description du macro-processus</p>','<p>Valeur de test</p>',1,'Simon','2020-06-13 01:03:42','2021-05-14 07:21:10',NULL,2,3,4),(3,'Valeur de test','<p>Valeur de test</p>','<p>Valeur de test</p>',3,'All','2020-08-09 05:32:37','2020-08-24 14:45:57','2020-08-24 14:45:57',NULL,NULL,NULL),(4,'Proc3','<p>dfsdf</p>','<p>dsfsdf</p>',0,NULL,'2020-08-31 14:13:55','2020-08-31 14:31:29','2020-08-31 14:31:29',NULL,NULL,NULL),(5,'Proc4','<p>dfsdf</p>','<p>dsfsdf</p>',0,NULL,'2020-08-31 14:19:32','2020-08-31 14:31:29','2020-08-31 14:31:29',NULL,NULL,NULL),(6,'Proc5','<p>dfsdf</p>','<p>dsfsdf</p>',0,NULL,'2020-08-31 14:29:20','2020-08-31 14:31:29','2020-08-31 14:31:29',NULL,NULL,NULL),(7,'MP1','<p>sdfsdfs</p>',NULL,0,NULL,'2020-08-31 14:31:40','2020-08-31 14:38:31','2020-08-31 14:38:31',NULL,NULL,NULL),(8,'MP2','<p>sdfsdfs</p>',NULL,0,NULL,'2020-08-31 14:37:39','2020-08-31 14:38:31','2020-08-31 14:38:31',NULL,NULL,NULL),(9,'MP3','<p>sdfsdfs</p>',NULL,0,NULL,'2020-08-31 14:38:06','2020-08-31 14:38:31','2020-08-31 14:38:31',NULL,NULL,NULL),(10,'Macro-Processus 3','<p>Description du troisième macro-processus</p>','<ul><li>un</li><li>deux</li><li>trois</li><li>quatre</li></ul>',2,'Nestor','2020-11-24 08:21:38','2021-05-14 07:20:55',NULL,2,2,2),(11,'Macro-Processus 4','<p>Description du macro processus quatre</p>','<ul><li>crayon</li><li>stylos</li><li>gommes</li></ul>',1,'Pirre','2021-05-14 07:19:51','2021-05-14 07:19:51',NULL,1,1,1);
/*!40000 ALTER TABLE `macro_processuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `man_wan`
--

LOCK TABLES `man_wan` WRITE;
/*!40000 ALTER TABLE `man_wan` DISABLE KEYS */;
INSERT INTO `man_wan` VALUES (1,1);
/*!40000 ALTER TABLE `man_wan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `mans`
--

LOCK TABLES `mans` WRITE;
/*!40000 ALTER TABLE `mans` DISABLE KEYS */;
INSERT INTO `mans` VALUES (1,'MAN_1','2020-08-22 04:17:20','2020-08-22 04:17:20',NULL),(2,'MAN_2','2021-05-07 08:14:27','2021-05-07 08:23:23',NULL);
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
-- Dumping data for table `network_subnetwork`
--

LOCK TABLES `network_subnetwork` WRITE;
/*!40000 ALTER TABLE `network_subnetwork` DISABLE KEYS */;
INSERT INTO `network_subnetwork` VALUES (1,1),(2,2),(2,5);
/*!40000 ALTER TABLE `network_subnetwork` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `network_switches`
--

LOCK TABLES `network_switches` WRITE;
/*!40000 ALTER TABLE `network_switches` DISABLE KEYS */;
INSERT INTO `network_switches` VALUES (1,'Switch de test','123.4.5.6','<p>Test</p>','2020-07-13 17:30:37','2020-07-13 17:30:37',NULL);
/*!40000 ALTER TABLE `network_switches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `networks`
--

LOCK TABLES `networks` WRITE;
/*!40000 ALTER TABLE `networks` DISABLE KEYS */;
INSERT INTO `networks` VALUES (1,'Réseau 1','TCP','Pierre','Paul',3,'<p>Nom du réseau 1</p>','2020-06-23 12:34:14','2020-07-01 15:23:29',NULL),(2,'Réseau 2','TCP','CHEM','Jean-Marc',1,'<p>Description du réseau 2</p>','2020-07-01 15:45:41','2021-05-23 13:17:43',NULL);
/*!40000 ALTER TABLE `networks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `operation_task`
--

LOCK TABLES `operation_task` WRITE;
/*!40000 ALTER TABLE `operation_task` DISABLE KEYS */;
INSERT INTO `operation_task` VALUES (1,1),(1,2),(2,1),(3,3),(4,2),(5,1),(5,2),(5,3);
/*!40000 ALTER TABLE `operation_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `operations`
--

LOCK TABLES `operations` WRITE;
/*!40000 ALTER TABLE `operations` DISABLE KEYS */;
INSERT INTO `operations` VALUES (1,'Operation 1','<p>Description de l\'opération</p>','2020-06-13 00:02:42','2020-06-13 00:02:42',NULL),(2,'Operation 2','<p>Description de l\'opération</p>','2020-06-13 00:02:58','2020-06-13 00:02:58',NULL),(3,'Operation 3','<p>Desciption de l\'opération</p>','2020-06-13 00:03:11','2020-07-15 14:34:52',NULL),(4,'Operation 4',NULL,'2020-07-15 14:34:02','2020-07-15 14:34:02',NULL),(5,'Master operation','<p>Opération maitre</p>','2020-08-15 04:01:40','2020-08-15 04:01:40',NULL);
/*!40000 ALTER TABLE `operations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `peripherals`
--

LOCK TABLES `peripherals` WRITE;
/*!40000 ALTER TABLE `peripherals` DISABLE KEYS */;
INSERT INTO `peripherals` VALUES (1,'PER_01','IBM 3400','<p>important peripheral</p>','Marcel','2020-07-25 06:18:40','2020-07-25 06:19:46',NULL,1,2,NULL),(2,'PER_02','IBM 5600','<p>Description</p>','Nestor','2020-07-25 06:19:18','2020-07-25 06:19:18',NULL,3,5,NULL),(3,'PER_03','HAL 8100','<p>Space device</p>','Niel','2020-07-25 06:19:58','2020-07-25 06:20:18',NULL,3,4,NULL);
/*!40000 ALTER TABLE `peripherals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `phones`
--

LOCK TABLES `phones` WRITE;
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;
INSERT INTO `phones` VALUES (1,'Phone 01','<p>Téléphone de test</p>','MOTOROAL 3110','2020-07-21 05:16:46','2020-07-25 07:15:17',NULL,1,1,NULL),(2,'Phone 03','<p>Special AA phone</p>','Top secret red phne','2020-07-21 05:18:01','2020-07-25 07:25:38',NULL,2,4,NULL),(3,'Phone 02','<p>Description phone 02</p>','IPhone 2','2020-07-25 06:52:23','2020-07-25 07:25:19',NULL,2,3,NULL);
/*!40000 ALTER TABLE `phones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_router_vlan`
--

LOCK TABLES `physical_router_vlan` WRITE;
/*!40000 ALTER TABLE `physical_router_vlan` DISABLE KEYS */;
INSERT INTO `physical_router_vlan` VALUES (1,1),(1,3),(1,4),(2,3),(1,2),(1,5),(2,5),(1,6),(2,6);
/*!40000 ALTER TABLE `physical_router_vlan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_routers`
--

LOCK TABLES `physical_routers` WRITE;
/*!40000 ALTER TABLE `physical_routers` DISABLE KEYS */;
INSERT INTO `physical_routers` VALUES (1,'<p>Routeur prncipal</p>','Fortinet','2020-07-10 06:58:53','2020-07-25 08:26:01',NULL,1,1,1,'R1'),(2,'<p>Routeur secondaire</p>','CISCO','2020-07-10 07:19:11','2020-07-25 08:28:17',NULL,2,3,5,'R2');
/*!40000 ALTER TABLE `physical_routers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_security_devices`
--

LOCK TABLES `physical_security_devices` WRITE;
/*!40000 ALTER TABLE `physical_security_devices` DISABLE KEYS */;
INSERT INTO `physical_security_devices` VALUES (1,'Dispositif01','Sure securure type','<p>Description</p>','2021-05-20 14:40:43','2021-05-20 14:40:43',NULL,1,1,1);
/*!40000 ALTER TABLE `physical_security_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_servers`
--

LOCK TABLES `physical_servers` WRITE;
/*!40000 ALTER TABLE `physical_servers` DISABLE KEYS */;
INSERT INTO `physical_servers` VALUES (1,'Serveur A1','<p>Description du serveur A1</p>','IT CHEM','<p>OS: OS2<br>IP : 127.0.0.1<br>&nbsp;</p>','2020-06-21 05:27:02','2020-07-15 18:08:30',NULL,1,2,4,NULL),(2,'Serveur A2','<p>Description du serveur A2</p>','IT CHEM','<p>Configuration du serveur A<br>OS : Linux 23.4<br>RAM: 32G</p>','2020-06-21 05:27:58','2020-07-15 18:10:42',NULL,3,5,6,NULL),(3,'Serveur A3','<p>Serveur mobile</p>','IT CHEM','<p>None</p>','2020-07-14 15:30:48','2020-10-06 14:26:51',NULL,1,1,NULL,NULL),(4,'ZZ99','<p>Zoro server</p>',NULL,NULL,'2020-07-14 15:37:50','2020-08-25 14:54:58','2020-08-25 14:54:58',3,5,NULL,NULL),(5,'K01','<p>Serveur K01</p>',NULL,'<p>TOP CPU<br>TOP RAM</p>','2020-07-15 14:37:04','2020-08-29 12:08:09','2020-08-29 12:08:09',1,1,3,NULL),(6,'Mainframe 01','<p>IBM AS 400</p>','IT CHEM','<p>4G RAM</p><p>2T Disk</p><p>Cobol</p>','2020-09-05 08:02:49','2020-09-05 08:16:07',NULL,1,1,1,2),(7,'Mainframe T1','<p>Mainframe de test</p>','IT CHEM','<p>IDEM prod</p>','2020-09-05 08:22:18','2020-09-05 08:22:18',NULL,2,3,4,2);
/*!40000 ALTER TABLE `physical_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `physical_switches`
--

LOCK TABLES `physical_switches` WRITE;
/*!40000 ALTER TABLE `physical_switches` DISABLE KEYS */;
INSERT INTO `physical_switches` VALUES (1,'Switch de test','<p>test</p>','Nortel 201','2020-07-17 13:29:09','2020-07-17 13:31:54',NULL,1,2,4),(2,'Switch 2','<p>Description switch 2</p>','Alcatel 430','2020-07-17 13:31:41','2020-07-17 13:31:41',NULL,1,1,1),(3,'Switch 1','<p>Desription du switch 1</p>','Nortel 2300','2020-07-25 05:27:27','2020-07-25 07:41:44',NULL,2,3,5),(4,'Switch 3','<p>Desciption du switch 3</p>','Alcatel 3500','2020-07-25 07:42:51','2020-07-25 07:43:21',NULL,3,5,6),(5,'AB','<p>Test 2 chars switch</p>',NULL,'2020-08-22 04:19:45','2020-08-27 16:04:20','2020-08-27 16:04:20',NULL,NULL,NULL);
/*!40000 ALTER TABLE `physical_switches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `processes`
--

LOCK TABLES `processes` WRITE;
/*!40000 ALTER TABLE `processes` DISABLE KEYS */;
INSERT INTO `processes` VALUES (1,'Processus 1','<p>Description du processus 1</p>','CHEM',3,'<ul><li>pommes</li><li>poires</li><li>cerises</li></ul>',NULL,'2020-06-17 14:36:24','2021-05-14 13:29:36',NULL,1,2,3,1),(2,'Processus 2','<p>Description du processus 2</p>','CHEM - Admission',3,'<p>1 2 3 4 5 6</p>',NULL,'2020-06-17 14:36:58','2021-05-14 13:29:36',NULL,1,4,2,4),(3,'Processus 3','<p>Description du processus 3</p>','CHEM',3,'<p>a,b,c</p><p>d,e,f</p>',NULL,'2020-07-01 15:50:27','2021-05-14 11:34:15',NULL,2,2,3,1),(4,'Processus 4','<p>Description du processus 4</p>','CHEM',4,'<ul><li>chaussettes</li><li>pantalon</li><li>chaussures</li></ul>',NULL,'2020-08-18 15:00:36','2021-05-14 11:35:01',NULL,2,2,2,2),(5,'totoat','<p>tto</p>',NULL,1,'<p>sgksdùmfk</p>',NULL,'2020-08-27 13:16:56','2020-08-27 13:17:01','2020-08-27 13:17:01',1,NULL,NULL,NULL),(6,'ptest','<p>description de ptest</p>',NULL,0,'<p>toto titi tutu</p>',NULL,'2020-08-29 11:10:23','2020-08-29 11:10:28','2020-08-29 11:10:28',NULL,NULL,NULL,NULL),(7,'ptest2','<p>fdfsdfsdf</p>',NULL,1,'<p>fdfsdfsd</p>',NULL,'2020-08-29 11:16:42','2020-08-29 11:17:09','2020-08-29 11:17:09',1,NULL,NULL,NULL),(8,'ptest3','<p>processus de test 3</p>','CHEM - Facturation',3,'<p>dsfsdf sdf sdf sd fsd fsd f s</p>',NULL,'2020-08-29 11:19:13','2020-08-29 11:20:59','2020-08-29 11:20:59',1,NULL,NULL,NULL),(9,'Processus 5','<p>Description du cinquième processus</p>','CHEM',4,'<ul><li>chat</li><li>chien</li><li>poisson</li></ul>',NULL,'2021-05-14 07:10:02','2021-05-14 07:20:55',NULL,10,3,2,3);
/*!40000 ALTER TABLE `processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `relations`
--

LOCK TABLES `relations` WRITE;
/*!40000 ALTER TABLE `relations` DISABLE KEYS */;
INSERT INTO `relations` VALUES (1,1,'Membre','Fourniture de service','<p>Le CHEM est membre de LuxITH</p>','2020-05-20 22:49:47','2020-07-05 10:09:35',NULL,1,6),(2,2,'Membre','Fournisseur de service',NULL,'2020-05-20 23:35:11','2021-05-23 13:05:07',NULL,2,6),(3,1,'Fournisseur','Fournisseur de service','<p>description de la relation entre Maincare et le CHEM</p>','2020-05-20 23:39:24','2020-06-13 05:18:49',NULL,7,1),(4,2,'Membre','Fourniture de service','<p>Description du service</p>','2020-05-21 02:23:03','2021-05-23 13:06:05',NULL,2,6),(5,0,'Membre','Fournisseur de service',NULL,'2020-05-21 02:23:35','2021-05-23 13:05:18',NULL,2,6),(6,0,'Fournisseur','fourniture de service',NULL,'2020-05-21 02:24:35','2020-05-21 02:24:35',NULL,7,2),(7,0,'Membre','fourniture de service',NULL,'2020-05-21 02:26:43','2020-05-21 02:26:43',NULL,4,6),(8,3,'Rapporte',NULL,NULL,'2020-05-21 02:32:19','2020-07-05 10:10:01',NULL,1,5),(9,0,'Fournisseur','fourniture de service',NULL,'2020-05-21 02:33:33','2020-05-21 02:33:33',NULL,9,1),(10,2,'Rapporte','Fournisseur de service','<p>Régelement général APD34</p>','2020-05-22 21:21:02','2020-08-24 14:31:29',NULL,1,8),(11,2,'toto',NULL,NULL,'2020-07-05 10:14:15','2020-07-05 10:14:55','2020-07-05 10:14:55',3,2),(12,1,'Fournisseur','Fournisseur de service','<p>Analyse de risques</p>','2020-08-24 14:23:30','2020-08-24 14:23:48',NULL,2,4),(13,1,'Fournisseur','Fourniture de service','<p>Description du service</p>','2020-10-14 17:06:24','2021-05-23 13:06:34',NULL,2,12);
/*!40000 ALTER TABLE `relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `routers`
--

LOCK TABLES `routers` WRITE;
/*!40000 ALTER TABLE `routers` DISABLE KEYS */;
INSERT INTO `routers` VALUES (1,'Routeur 1','<p>Description du routeur 1</p>','<p>liste des règles dans //serveur/liste.txt</p>','2020-07-13 17:32:25','2020-07-13 17:32:25',NULL);
/*!40000 ALTER TABLE `routers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `security_devices`
--

LOCK TABLES `security_devices` WRITE;
/*!40000 ALTER TABLE `security_devices` DISABLE KEYS */;
INSERT INTO `security_devices` VALUES (1,'FW01','<p>Firewall proncipal</p>','2020-07-14 17:01:21','2020-07-14 17:01:21',NULL),(2,'FW02','<p>Backup Fireall</p>','2020-07-14 17:02:21','2020-07-14 17:02:21',NULL);
/*!40000 ALTER TABLE `security_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sites`
--

LOCK TABLES `sites` WRITE;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
INSERT INTO `sites` VALUES (1,'Site A','<p>Description du site A</p>','2020-06-21 04:36:41','2020-06-21 04:36:41',NULL),(2,'Site B','<p>Description du site B</p>','2020-06-21 04:36:53','2020-06-21 04:36:53',NULL),(3,'Site C','<p>Description du Site C</p>','2020-06-21 04:37:05','2020-06-21 04:37:05',NULL),(4,'Test1','<p>site de test</p>','2020-07-24 19:12:29','2020-07-24 19:12:56','2020-07-24 19:12:56'),(5,'testsite','<p>description here</p>','2021-04-12 15:31:40','2021-04-12 15:32:04','2021-04-12 15:32:04');
/*!40000 ALTER TABLE `sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `storage_devices`
--

LOCK TABLES `storage_devices` WRITE;
/*!40000 ALTER TABLE `storage_devices` DISABLE KEYS */;
INSERT INTO `storage_devices` VALUES (1,'DiskServer 1','<p>Description du serveur d stockage 1</p>','2020-06-21 15:30:16','2020-06-21 15:30:16',NULL,1,2,3,NULL),(2,'Oracle Server','<p>Main oracle server</p>','2020-06-21 15:33:51','2020-06-21 15:34:38',NULL,1,2,2,NULL);
/*!40000 ALTER TABLE `storage_devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `subnetworks`
--

LOCK TABLES `subnetworks` WRITE;
/*!40000 ALTER TABLE `subnetworks` DISABLE KEYS */;
INSERT INTO `subnetworks` VALUES (1,'<p>Description du sous-réseau 1</p>','1234567/21','312132213-312312312','static','CHEM','non','non','Subnet1','2020-06-23 12:35:41','2021-05-23 13:17:03',NULL,NULL,4),(2,'<p>Description du subnet 2</p>','123456/12','123456-1234567','Statc','Henri','Non','Oui','Subnet2','2020-07-04 07:35:10','2021-05-18 18:27:13',NULL,1,5),(3,'<p>Description du quatrième subnet</p>',NULL,NULL,NULL,NULL,NULL,NULL,'Subnet4','2020-11-06 12:56:33','2021-05-23 13:21:49',NULL,NULL,5),(4,'<p>descrption subnet 3</p>','8.8.8.8 /  255.255.255.0',NULL,NULL,NULL,NULL,NULL,'test subnet 3','2021-02-24 11:49:16','2021-02-24 11:49:33','2021-02-24 11:49:33',NULL,NULL),(5,'<p>Troisième sous-réseau</p>',NULL,NULL,NULL,NULL,NULL,NULL,'Subnet3','2021-05-19 14:48:39','2021-05-23 13:17:59',NULL,1,1);
/*!40000 ALTER TABLE `subnetworks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (1,'Tâche 2','Descriptionde la tâche 2','2020-06-13 00:04:07','2020-06-13 00:04:07',NULL),(2,'Tache 1','Description de la tâche 1','2020-06-13 00:04:21','2020-06-13 00:04:21',NULL),(3,'Tâche 3','Description de la tâche 3','2020-06-13 00:04:41','2020-06-13 00:04:41',NULL);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `vlans`
--

LOCK TABLES `vlans` WRITE;
/*!40000 ALTER TABLE `vlans` DISABLE KEYS */;
INSERT INTO `vlans` VALUES (1,'VLAN_2','VLAN Wifi','2020-07-07 14:31:53','2020-07-07 14:39:10',NULL,'123.4.0.0','/12','1',NULL),(2,'VLAN_1','VLAN publc','2020-07-07 14:34:30','2020-07-07 14:38:53',NULL,'123.0.0.1','/12','1',NULL),(3,'VLAN_3','VLAN application','2020-07-07 14:38:41','2020-07-08 19:35:53',NULL,'125.6.0.0','/12','2','125.6.0.2'),(4,'VLAN_4','Vlan Client','2020-07-08 19:34:11','2020-07-08 19:36:06',NULL,'142.3.5 - 123.32.43.2','/18','1','123.43.5.2'),(5,'VLAN_5','Test vlan 5','2020-07-11 17:12:03','2020-07-11 17:14:13',NULL,'123.4.5.0','255.255.255.0','1','123.4.5.1'),(6,'VLAN_6',NULL,'2020-07-11 17:14:55','2020-07-11 17:14:55',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `vlans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `wans`
--

LOCK TABLES `wans` WRITE;
/*!40000 ALTER TABLE `wans` DISABLE KEYS */;
INSERT INTO `wans` VALUES (1,'WAN01','2021-05-21 10:58:42','2021-05-21 10:58:42',NULL);
/*!40000 ALTER TABLE `wans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `wifi_terminals`
--

LOCK TABLES `wifi_terminals` WRITE;
/*!40000 ALTER TABLE `wifi_terminals` DISABLE KEYS */;
INSERT INTO `wifi_terminals` VALUES (1,'WIFI_01','<p>Borne wifi 01</p>','Alcatel 3500','2020-07-22 14:44:37','2020-07-22 14:44:37',NULL,1,2,NULL);
/*!40000 ALTER TABLE `wifi_terminals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `workstations`
--

LOCK TABLES `workstations` WRITE;
/*!40000 ALTER TABLE `workstations` DISABLE KEYS */;
INSERT INTO `workstations` VALUES (1,'Workstation 1','<p>Type de workstation 1</p>','2020-06-21 15:09:04','2020-07-25 05:53:27',NULL,3,5,NULL),(2,'Workstation 2','<p>Description Workstation type 2</p>','2020-06-21 15:09:54','2020-07-25 05:53:09',NULL,2,3,NULL),(3,'Workstation 3','<p>Description de la workstation 3</p>','2020-06-21 15:17:57','2020-07-19 13:25:40',NULL,2,4,NULL);
/*!40000 ALTER TABLE `workstations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `zone_admins`
--

LOCK TABLES `zone_admins` WRITE;
/*!40000 ALTER TABLE `zone_admins` DISABLE KEYS */;
INSERT INTO `zone_admins` VALUES (1,'Enreprise','<p>Zone d\'administration de l\'entreprise</p>','2020-07-03 07:49:03','2021-05-23 13:07:18',NULL);
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

-- Dump completed on 2021-05-23 18:02:10
