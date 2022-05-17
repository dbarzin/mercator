-- MySQL dump 10.13  Distrib 8.0.29, for Linux (x86_64)
--
-- Host: localhost    Database: mercator
-- ------------------------------------------------------
-- Server version	8.0.29-0ubuntu0.21.10.2
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,ANSI' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table "activities"
--

LOCK TABLES "activities" WRITE;
/*!40000 ALTER TABLE "activities" DISABLE KEYS */;
INSERT INTO "activities" ("id", "name", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'Activité 1','<p>Description de l\'activité 1</p>','2020-06-10 13:20:42','2020-06-10 13:20:42',NULL),(2,'Activité 2','<p>Description de l\'activité de test</p>','2020-06-10 15:44:26','2020-06-13 04:03:26',NULL),(3,'Activité 3','<p>Description de l\'activité 3</p>','2020-06-13 04:57:08','2020-06-13 04:57:08',NULL),(4,'Activité 4','<p>Description de l\'acivité 4</p>','2020-06-13 04:57:24','2020-06-13 04:57:24',NULL),(5,'Activité principale','<p>Description de l\'activité principale</p>','2020-08-15 04:19:53','2020-08-15 04:19:53',NULL),(6,'AAA','test a1','2021-03-22 19:06:55','2021-03-22 19:07:00','2021-03-22 19:07:00'),(7,'AAA','test AAA','2021-03-22 19:13:43','2021-03-22 19:14:05','2021-03-22 19:14:05'),(8,'AAA','test 2 aaa','2021-03-22 19:14:16','2021-03-22 19:14:45','2021-03-22 19:14:45'),(9,'AAA1','test 3 AAA','2021-03-22 19:14:40','2021-03-22 19:19:09','2021-03-22 19:19:09'),(10,'Activité 0','<p>Description de l\'activité zéro</p>',NULL,'2021-05-15 07:40:16',NULL),(11,'test','dqqsd','2021-08-02 20:03:46','2021-09-22 10:59:48','2021-09-22 10:59:48');
/*!40000 ALTER TABLE "activities" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "activity_operation"
--

LOCK TABLES "activity_operation" WRITE;
/*!40000 ALTER TABLE "activity_operation" DISABLE KEYS */;
INSERT INTO "activity_operation" ("activity_id", "operation_id") VALUES (2,3),(1,1),(1,2),(4,3),(3,1),(1,5),(5,1),(6,1),(10,1);
/*!40000 ALTER TABLE "activity_operation" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "activity_process"
--

LOCK TABLES "activity_process" WRITE;
/*!40000 ALTER TABLE "activity_process" DISABLE KEYS */;
INSERT INTO "activity_process" ("process_id", "activity_id") VALUES (1,1),(1,2),(2,3),(2,4),(3,2),(3,5),(4,5),(5,4),(6,4),(7,3),(8,4),(9,3),(1,10);
/*!40000 ALTER TABLE "activity_process" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "actor_operation"
--

LOCK TABLES "actor_operation" WRITE;
/*!40000 ALTER TABLE "actor_operation" DISABLE KEYS */;
INSERT INTO "actor_operation" ("operation_id", "actor_id") VALUES (2,1),(1,1),(1,4),(2,5),(3,6),(5,4);
/*!40000 ALTER TABLE "actor_operation" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "actors"
--

LOCK TABLES "actors" WRITE;
/*!40000 ALTER TABLE "actors" DISABLE KEYS */;
INSERT INTO "actors" ("id", "name", "nature", "type", "contact", "created_at", "updated_at", "deleted_at") VALUES (1,'Jean','Personne','interne','jean@testdomain.org','2020-06-14 11:02:22','2021-05-16 17:37:49',NULL),(2,'Service 1','Groupe','interne',NULL,'2020-06-14 11:02:39','2020-06-17 14:43:42','2020-06-17 14:43:42'),(3,'Service 2','Groupe','Interne',NULL,'2020-06-14 11:02:54','2020-06-17 14:43:46','2020-06-17 14:43:46'),(4,'Pierre','Personne','interne','email : pierre@testdomain.com','2020-06-17 14:44:01','2021-05-16 17:38:19',NULL),(5,'Jacques','personne','interne','Téléphone 1234543423','2020-06-17 14:44:23','2020-06-17 14:44:23',NULL),(6,'Fournisseur 1','entité','externe','Tel : 1232 32312','2020-06-17 14:44:50','2020-06-17 14:44:50',NULL);
/*!40000 ALTER TABLE "actors" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "annuaires"
--

LOCK TABLES "annuaires" WRITE;
/*!40000 ALTER TABLE "annuaires" DISABLE KEYS */;
INSERT INTO "annuaires" ("id", "name", "description", "solution", "created_at", "updated_at", "deleted_at", "zone_admin_id") VALUES (1,'AD01','<p>Annuaire principal&nbsp;</p>','Acive Directory','2020-07-03 07:49:37','2022-03-22 19:33:39',NULL,1),(2,'Mercator','<p>Cartographie du système d\'information</p>','Logiciel développé maison','2020-07-03 10:24:48','2020-07-13 15:12:59',NULL,1);
/*!40000 ALTER TABLE "annuaires" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "application_blocks"
--

LOCK TABLES "application_blocks" WRITE;
/*!40000 ALTER TABLE "application_blocks" DISABLE KEYS */;
INSERT INTO "application_blocks" ("id", "name", "description", "responsible", "created_at", "updated_at", "deleted_at") VALUES (1,'Bloc applicatif 1','<p>Description du bloc applicatif</p>','Jean Pierre','2020-06-13 04:09:01','2020-06-13 04:09:01',NULL),(2,'Bloc applicatif 2','<p>Second bloc applicatif.</p>','Marcel pierre','2020-06-13 04:10:52','2020-06-17 16:13:33',NULL),(3,'Bloc applicatif 3','<p>Description du block applicatif 3</p>','Nestor','2020-08-29 12:00:10','2022-03-20 17:53:29',NULL);
/*!40000 ALTER TABLE "application_blocks" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "application_module_application_service"
--

LOCK TABLES "application_module_application_service" WRITE;
/*!40000 ALTER TABLE "application_module_application_service" DISABLE KEYS */;
INSERT INTO "application_module_application_service" ("application_service_id", "application_module_id") VALUES (4,1),(4,2),(3,3),(2,4),(1,5),(1,6),(5,2),(5,3),(6,2),(6,3),(7,2),(7,3),(8,2),(8,3),(9,2),(9,3),(10,2),(10,3),(11,2),(11,3);
/*!40000 ALTER TABLE "application_module_application_service" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "application_modules"
--

LOCK TABLES "application_modules" WRITE;
/*!40000 ALTER TABLE "application_modules" DISABLE KEYS */;
INSERT INTO "application_modules" ("id", "name", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'Module 1','<p>Description du module 1</p>','2020-06-13 09:55:34','2020-06-13 09:55:34',NULL),(2,'Module 2','<p>Description du module 2</p>','2020-06-13 09:55:45','2020-06-13 09:55:45',NULL),(3,'Module 3','<p>Description du module 3</p>','2020-06-13 09:56:00','2020-06-13 09:56:00',NULL),(4,'Module 4','<p>Description du module 4</p>','2020-06-13 09:56:10','2020-06-13 09:56:10',NULL),(5,'Module 5','<p>Description du module 5</p>','2020-06-13 09:56:20','2020-06-13 09:56:20',NULL),(6,'Module 6','<p>Description du module 6</p>','2020-06-13 09:56:32','2020-06-13 09:56:32',NULL);
/*!40000 ALTER TABLE "application_modules" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "application_service_m_application"
--

LOCK TABLES "application_service_m_application" WRITE;
/*!40000 ALTER TABLE "application_service_m_application" DISABLE KEYS */;
INSERT INTO "application_service_m_application" ("m_application_id", "application_service_id") VALUES (2,3),(2,4),(1,3),(15,2),(15,3),(1,1),(4,11),(4,5),(2,7),(4,7),(1,10),(16,10),(16,11),(16,5),(16,6),(16,7),(16,9),(16,1),(16,2),(16,3),(16,4),(16,8);
/*!40000 ALTER TABLE "application_service_m_application" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "application_services"
--

LOCK TABLES "application_services" WRITE;
/*!40000 ALTER TABLE "application_services" DISABLE KEYS */;
INSERT INTO "application_services" ("id", "description", "exposition", "name", "created_at", "updated_at", "deleted_at") VALUES (1,'<p>Descrition du service applicatif 1</p>','cloud','SRV-1','2020-06-13 09:35:31','2021-08-03 18:50:33',NULL),(2,'<p>Description du service 2</p>','local','Service 2','2020-06-13 09:35:48','2020-06-13 09:35:48',NULL),(3,'<p>Description du service 3</p>','local','Service 3','2020-06-13 09:36:04','2020-06-13 09:43:05',NULL),(4,'<p>Description du service 4</p>','local','Service 4','2020-06-13 09:36:17','2020-06-13 09:36:17',NULL),(5,'<p>Service applicatif 4</p>','Extranet','SRV-4','2021-08-02 14:11:43','2021-08-17 08:24:10',NULL),(6,'<p>Service applicatif 4</p>',NULL,'SRV-5','2021-08-02 14:12:19','2021-08-02 14:12:19',NULL),(7,'<p>Service applicatif 4</p>',NULL,'SRV-6','2021-08-02 14:12:56','2021-08-02 14:12:56',NULL),(8,'<p>The service 99</p>','local','SRV-99','2021-08-02 14:13:39','2021-09-07 16:53:36',NULL),(9,'<p>Service applicatif 4</p>',NULL,'SRV-9','2021-08-02 14:14:27','2021-08-02 14:14:27',NULL),(10,'<p>Service applicatif 4</p>','Extranet','SRV-10','2021-08-02 14:15:21','2021-08-17 08:24:20',NULL),(11,'<p>Service applicatif 4</p>','Extranet','SRV-11','2021-08-02 14:16:34','2021-08-17 08:24:28',NULL);
/*!40000 ALTER TABLE "application_services" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "bay_wifi_terminal"
--

LOCK TABLES "bay_wifi_terminal" WRITE;
/*!40000 ALTER TABLE "bay_wifi_terminal" DISABLE KEYS */;
/*!40000 ALTER TABLE "bay_wifi_terminal" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "bays"
--

LOCK TABLES "bays" WRITE;
/*!40000 ALTER TABLE "bays" DISABLE KEYS */;
INSERT INTO "bays" ("id", "name", "description", "created_at", "updated_at", "deleted_at", "room_id") VALUES (1,'BAIE 101','<p>Description de la baie 101</p>','2020-06-21 04:56:01','2021-10-19 16:45:21',NULL,7),(2,'BAIE 102','<p>Desciption baie 102</p>','2020-06-21 04:56:20','2020-06-21 04:56:20',NULL,1),(3,'BAIE 103','<p>Descripton baid 103</p>','2020-06-21 04:56:38','2020-06-21 04:56:38',NULL,1),(4,'BAIE 201','<p>Description baie 201</p>','2020-06-21 04:56:55','2020-06-21 04:56:55',NULL,2),(5,'BAIE 301','<p>Baie 301</p>','2020-07-15 18:03:07','2020-07-15 18:03:07',NULL,3),(6,'BAIE 501','<p>Baie 501</p>','2020-07-15 18:10:23','2020-07-15 18:10:23',NULL,5);
/*!40000 ALTER TABLE "bays" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "buildings"
--

LOCK TABLES "buildings" WRITE;
/*!40000 ALTER TABLE "buildings" DISABLE KEYS */;
INSERT INTO "buildings" ("id", "name", "description", "created_at", "updated_at", "deleted_at", "site_id", "camera", "badge") VALUES (1,'Building 1','<p>Description du building 1</p>','2020-06-21 04:37:21','2020-06-21 04:47:41',NULL,1,NULL,NULL),(2,'Building 2','<p>Description du building 2</p>','2020-06-21 04:37:36','2020-07-25 06:26:13',NULL,1,NULL,NULL),(3,'Building 3','<p>Description du building 3</p>','2020-06-21 04:37:48','2020-07-25 06:26:03',NULL,2,NULL,NULL),(4,'Building 4','<p>Description du building 4</p>','2020-06-21 04:38:03','2020-07-25 06:25:54',NULL,2,NULL,NULL),(5,'Building 5','<p>Descripion du building 5</p>','2020-06-21 04:38:16','2020-07-25 06:26:26',NULL,3,NULL,NULL),(6,'Test building','<p>Description</p>','2020-07-24 19:12:48','2020-07-24 19:14:08','2020-07-24 19:14:08',4,NULL,NULL),(7,'Building 0','<p>Le building zéro</p>','2020-08-21 13:10:15','2020-10-02 07:38:55',NULL,1,NULL,NULL),(8,'test','<p>test</p>','2020-11-06 13:44:22','2020-11-06 14:26:18','2020-11-06 14:26:18',NULL,1,0),(9,'test2','<p>test2</p>','2020-11-06 13:59:45','2020-11-06 14:06:50','2020-11-06 14:06:50',NULL,NULL,NULL),(10,'test3','<p>fdfsdfsd</p>','2020-11-06 14:07:07','2020-11-06 14:26:18','2020-11-06 14:26:18',NULL,NULL,NULL),(11,'test4',NULL,'2020-11-06 14:25:52','2020-11-06 14:26:18','2020-11-06 14:26:18',NULL,0,0);
/*!40000 ALTER TABLE "buildings" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "cartographer_m_application"
--

LOCK TABLES "cartographer_m_application" WRITE;
/*!40000 ALTER TABLE "cartographer_m_application" DISABLE KEYS */;
/*!40000 ALTER TABLE "cartographer_m_application" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "certificate_logical_server"
--

LOCK TABLES "certificate_logical_server" WRITE;
/*!40000 ALTER TABLE "certificate_logical_server" DISABLE KEYS */;
INSERT INTO "certificate_logical_server" ("certificate_id", "logical_server_id") VALUES (4,1),(5,2),(1,1),(2,1),(3,1),(7,1);
/*!40000 ALTER TABLE "certificate_logical_server" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "certificate_m_application"
--

LOCK TABLES "certificate_m_application" WRITE;
/*!40000 ALTER TABLE "certificate_m_application" DISABLE KEYS */;
INSERT INTO "certificate_m_application" ("certificate_id", "m_application_id") VALUES (8,4);
/*!40000 ALTER TABLE "certificate_m_application" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "certificates"
--

LOCK TABLES "certificates" WRITE;
/*!40000 ALTER TABLE "certificates" DISABLE KEYS */;
INSERT INTO "certificates" ("id", "name", "type", "description", "start_validity", "end_validity", "created_at", "updated_at", "deleted_at", "status") VALUES (1,'CERT01','DES3','<p>Certificat 01</p>','2020-10-27','2022-01-01','2021-07-14 08:28:47','2022-02-08 15:25:10',NULL,0),(2,'CERT02','AES 256','<p>Certificat numéro 02</p>','2021-07-14','2021-07-17','2021-07-14 08:33:33','2021-07-14 14:14:12',NULL,NULL),(3,'CERT03','AES 256','<p>Certificat numéro 3</p>','2021-09-23','2021-11-11','2021-07-14 10:35:41','2021-09-23 14:11:34',NULL,NULL),(4,'CERT04','DES3','<p>Certificat interne DES 3</p>',NULL,NULL,'2021-07-14 10:40:15','2021-07-14 10:40:15',NULL,NULL),(5,'CERT05','RSA 128','<p>Clé 05 avec RSA</p>',NULL,NULL,'2021-07-14 10:45:00','2021-07-14 10:45:00',NULL,NULL),(6,'CERT07','DES3','<p>cert 7</p>',NULL,NULL,'2021-07-14 12:44:12','2021-07-14 12:44:12',NULL,NULL),(7,'CERT08','DES3','<p>Master cert 08</p>','2021-06-15','2022-08-11','2021-08-11 18:33:42','2021-08-11 18:33:42',NULL,NULL),(8,'CERT09','DES3','<p>Test cert nine</p>','2021-09-25','2021-09-26','2021-09-23 14:17:20','2021-09-23 14:17:20',NULL,NULL);
/*!40000 ALTER TABLE "certificates" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "database_entity"
--

LOCK TABLES "database_entity" WRITE;
/*!40000 ALTER TABLE "database_entity" DISABLE KEYS */;
INSERT INTO "database_entity" ("database_id", "entity_id") VALUES (1,1),(3,1),(4,1),(5,1),(6,1);
/*!40000 ALTER TABLE "database_entity" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "database_information"
--

LOCK TABLES "database_information" WRITE;
/*!40000 ALTER TABLE "database_information" DISABLE KEYS */;
INSERT INTO "database_information" ("database_id", "information_id") VALUES (1,1),(1,2),(1,3),(3,2),(3,3),(5,1),(4,2),(6,2),(6,3),(5,5);
/*!40000 ALTER TABLE "database_information" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "database_m_application"
--

LOCK TABLES "database_m_application" WRITE;
/*!40000 ALTER TABLE "database_m_application" DISABLE KEYS */;
INSERT INTO "database_m_application" ("m_application_id", "database_id") VALUES (2,3),(3,4),(3,1),(4,5),(4,6),(15,5),(15,4),(16,1);
/*!40000 ALTER TABLE "database_m_application" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "databases"
--

LOCK TABLES "databases" WRITE;
/*!40000 ALTER TABLE "databases" DISABLE KEYS */;
INSERT INTO "databases" ("id", "name", "description", "responsible", "type", "security_need_c", "external", "created_at", "updated_at", "deleted_at", "entity_resp_id", "security_need_i", "security_need_a", "security_need_t") VALUES (1,'Database 1','<p>Description Database 1</p>','Paul','MySQL',1,'Interne','2020-06-17 14:18:48','2021-05-14 10:19:45',NULL,2,2,3,4),(3,'Database 2','<p>Description database 2</p>','Paul','MySQL',1,'Interne','2020-06-17 14:19:24','2021-05-14 10:29:47',NULL,1,1,1,1),(4,'MainDB','<p>description de la base de données</p>','Paul','Oracle',2,'Interne','2020-07-01 15:08:57','2021-08-20 01:52:23',NULL,1,2,2,2),(5,'DB Compta','<p>Base de donnée de la compta</p>','Paul','MariaDB',2,'Interne','2020-08-24 15:58:23','2022-03-21 17:13:10',NULL,18,2,2,2),(6,'Data Warehouse','<p>Base de données du datawarehouse</p>','Jean','Oracle',2,'Interne','2021-05-14 10:24:02','2022-03-21 17:13:24',NULL,1,2,2,2);
/*!40000 ALTER TABLE "databases" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "dhcp_servers"
--

LOCK TABLES "dhcp_servers" WRITE;
/*!40000 ALTER TABLE "dhcp_servers" DISABLE KEYS */;
INSERT INTO "dhcp_servers" ("id", "name", "description", "created_at", "updated_at", "deleted_at", "address_ip") VALUES (1,'DHCP_1','<p>Serveur DHCP primaire</p>','2020-07-23 08:34:43','2021-10-19 09:03:07',NULL,'10.10.121.2'),(2,'DHCP_2','<p>Serveur DHCP secondaire</p>','2021-10-19 08:46:52','2021-10-19 09:23:36',NULL,'10.40.6.4');
/*!40000 ALTER TABLE "dhcp_servers" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "dnsservers"
--

LOCK TABLES "dnsservers" WRITE;
/*!40000 ALTER TABLE "dnsservers" DISABLE KEYS */;
INSERT INTO "dnsservers" ("id", "name", "description", "created_at", "updated_at", "deleted_at", "address_ip") VALUES (1,'DNS_1','<p>Serveur DNS primaire</p>','2020-07-23 08:31:39','2021-11-16 16:55:11',NULL,'10.10.3.4'),(2,'DNS_2','<p>Serveur DNS secondaire</p>','2020-07-23 08:31:50','2021-10-19 09:10:45',NULL,'10.30.2.3'),(3,'DNS_3','<p>Troisième serveur DNS</p>','2021-10-19 08:39:25','2021-10-19 08:41:09',NULL,'10.10.10.1');
/*!40000 ALTER TABLE "dnsservers" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "domaine_ad_forest_ad"
--

LOCK TABLES "domaine_ad_forest_ad" WRITE;
/*!40000 ALTER TABLE "domaine_ad_forest_ad" DISABLE KEYS */;
INSERT INTO "domaine_ad_forest_ad" ("forest_ad_id", "domaine_ad_id") VALUES (1,1),(2,1),(1,3),(2,5),(1,4);
/*!40000 ALTER TABLE "domaine_ad_forest_ad" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "domaine_ads"
--

LOCK TABLES "domaine_ads" WRITE;
/*!40000 ALTER TABLE "domaine_ads" DISABLE KEYS */;
INSERT INTO "domaine_ads" ("id", "name", "description", "domain_ctrl_cnt", "user_count", "machine_count", "relation_inter_domaine", "created_at", "updated_at", "deleted_at") VALUES (1,'Dom1','<p>Domaine AD1</p>',3,2000,800,'Non','2020-07-03 07:51:06','2020-07-03 07:51:06',NULL),(2,'test domain','<p>this is a test</p>',NULL,NULL,NULL,NULL,'2021-05-27 13:24:52','2021-05-27 13:29:15','2021-05-27 13:29:15'),(3,'Dom2','<p>Second domaine active directory</p>',2,100,1,'Néant','2021-05-27 13:29:43','2021-05-27 13:29:43',NULL),(4,'Dom5','<p>Domaine cinq</p>',NULL,NULL,NULL,NULL,'2021-05-27 13:39:08','2021-05-27 13:39:08',NULL),(5,'Dom4','<p>Domaine quatre</p>',NULL,NULL,NULL,NULL,'2021-05-27 13:39:20','2021-05-27 13:39:20',NULL);
/*!40000 ALTER TABLE "domaine_ads" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "entities"
--

LOCK TABLES "entities" WRITE;
/*!40000 ALTER TABLE "entities" DISABLE KEYS */;
INSERT INTO "entities" ("id", "name", "security_level", "contact_point", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'MegaNet System','<p>ISO 27001</p>','<p>Helpdek<br>27, Rue des poire&nbsp;<br>12043 Mire-en-Mare le Bains</p><p>helpdes@menetsys.org</p>','<p>Fournisseur équipement réseau</p>','2020-05-21 02:30:59','2021-10-27 09:28:17',NULL),(2,'Entité1','<p>Néant</p>','<ul><li>Commercial</li><li>Service Delivery</li><li>Helpdesk</li></ul>','<p>Entité de tests1</p>','2020-05-21 02:31:17','2021-05-23 12:59:11',NULL),(3,'CHdN','3','RSSI du CHdN','<p>Centre Hospitalier du Nord</p>','2020-05-21 02:43:41','2021-05-13 08:20:32','2021-05-13 08:20:32'),(4,'Entité3','<p>ISO 9001</p>','<p>Point de contact de la troisième entité</p>','<p>Description de la troisième entité.</p>','2020-05-21 02:44:03','2021-07-20 19:20:37',NULL),(5,'entité6','<p>Néant</p>','<p>support_informatque@entite6.fr</p>','<p>Description de l\'entité six</p>','2020-05-21 02:44:18','2021-05-23 13:03:15',NULL),(6,'Entité4','<p>ISO 27001</p>','<p>Pierre Pinon<br>Tel: 00 34 392 484 22</p>','<p>Description de l\'entté quatre</p>','2020-05-21 02:45:14','2021-05-23 13:01:17',NULL),(7,'Entité5','<p>Néant</p>','<p>Servicdesk@entite5.fr</p>','<p>Description de l\'entité 5</p>','2020-05-21 03:38:41','2021-05-23 13:02:16',NULL),(8,'Entité2','<p>ISO 27001</p>','<p>Point de contact de l\'entité 2</p>','<p>Description de l\'entité 2</p>','2020-05-21 03:54:22','2021-05-23 13:00:03',NULL),(9,'NetworkSys','<p>ISO 27001</p>','<p>support@networksys.fr</p>','<p>Description de l’entité NetworkSys</p>','2020-05-21 06:25:15','2021-05-23 13:04:06',NULL),(10,'Agence eSanté','<p>Néant</p>','<p>helpdesk@esante.lu</p>','<p>Agence Nationale des information partagées dans le domaine de la santé</p><ul><li>a</li><li>b</li><li>c</li></ul><p>+-------+<br>+ TOTO +<br>+-------+</p><p>&lt;&lt;&lt;&lt;&lt;&lt; &gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;</p>','2020-05-21 06:25:26','2021-05-13 08:20:32','2021-05-13 08:20:32'),(11,'Test','4',NULL,'<p>Test</p>','2020-07-02 15:37:29','2020-07-02 15:37:44','2020-07-02 15:37:44'),(12,'Pierre et fils','<p>Certifications :&nbsp;<br>- ISO 9001<br>- ISO 27001<br>- ISO 31000</p>','<p>Paul Pierre<br>Gérant<br>00 33 4943 432 423</p>','<p>Description de l\'entité de test</p>','2020-07-06 13:37:54','2022-04-19 14:58:37',NULL),(13,'Nestor','<p>Haut niveau</p>','<p>Paul, Pierre et Jean</p>','<p>Description de Nestor</p>','2020-08-12 16:11:31','2020-08-12 16:12:13',NULL),(14,'0001',NULL,NULL,'<p>rrzerze</p>','2021-06-15 15:16:31','2021-06-15 15:17:08','2021-06-15 15:17:08'),(15,'002',NULL,NULL,'<p>sdqsfsd</p>','2021-06-15 15:16:41','2021-06-15 15:17:08','2021-06-15 15:17:08'),(16,'003',NULL,NULL,'<p>dsqdsq</p>','2021-06-15 15:16:51','2021-06-15 15:17:08','2021-06-15 15:17:08'),(17,'004',NULL,NULL,'<p>dqqqsdqs</p>','2021-06-15 15:17:01','2021-06-15 15:17:08','2021-06-15 15:17:08'),(18,'Acme corp.','<p>None sorry...</p>','<p>Do not call me, I will call you back.</p>','<p>Looney tunes academy</p>','2021-09-07 18:07:16','2022-05-06 08:45:29',NULL),(19,'HAL','<p>Top security certification</p>','<p>hal@corp.com</p>','<p>Very big HAL corporation</p>','2021-09-07 18:08:56','2021-09-07 18:09:17',NULL),(20,'ATest1',NULL,NULL,NULL,'2022-04-25 12:43:46','2022-04-25 12:44:02','2022-04-25 12:44:02'),(21,'ATest2',NULL,NULL,NULL,'2022-04-25 12:43:56','2022-04-25 12:44:02','2022-04-25 12:44:02');
/*!40000 ALTER TABLE "entities" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "entity_m_application"
--

LOCK TABLES "entity_m_application" WRITE;
/*!40000 ALTER TABLE "entity_m_application" DISABLE KEYS */;
INSERT INTO "entity_m_application" ("m_application_id", "entity_id") VALUES (2,1),(5,1),(7,2),(9,1),(10,1),(2,2),(11,1),(1,2),(1,8),(3,8),(4,8),(4,4),(16,2);
/*!40000 ALTER TABLE "entity_m_application" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "entity_process"
--

LOCK TABLES "entity_process" WRITE;
/*!40000 ALTER TABLE "entity_process" DISABLE KEYS */;
INSERT INTO "entity_process" ("process_id", "entity_id") VALUES (1,1),(2,1),(3,1),(1,13),(3,13),(4,1),(7,3),(9,4),(2,8),(4,6),(4,7),(9,5),(1,9),(2,9),(3,9),(4,9),(9,9),(1,12),(1,2),(4,18),(3,19);
/*!40000 ALTER TABLE "entity_process" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "external_connected_entities"
--

LOCK TABLES "external_connected_entities" WRITE;
/*!40000 ALTER TABLE "external_connected_entities" DISABLE KEYS */;
INSERT INTO "external_connected_entities" ("id", "name", "responsible_sec", "contacts", "created_at", "updated_at", "deleted_at") VALUES (1,'Entité externe 1','Nestor','Marcel','2020-07-23 07:59:25','2020-07-23 07:59:25',NULL),(2,'Entité externe 2','Philippe','it@external.corp','2021-08-17 17:52:26','2021-08-17 17:52:26',NULL);
/*!40000 ALTER TABLE "external_connected_entities" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "external_connected_entity_network"
--

LOCK TABLES "external_connected_entity_network" WRITE;
/*!40000 ALTER TABLE "external_connected_entity_network" DISABLE KEYS */;
INSERT INTO "external_connected_entity_network" ("external_connected_entity_id", "network_id") VALUES (1,1),(2,2);
/*!40000 ALTER TABLE "external_connected_entity_network" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "fluxes"
--

LOCK TABLES "fluxes" WRITE;
/*!40000 ALTER TABLE "fluxes" DISABLE KEYS */;
INSERT INTO "fluxes" ("id", "name", "description", "created_at", "updated_at", "deleted_at", "application_source_id", "service_source_id", "module_source_id", "database_source_id", "application_dest_id", "service_dest_id", "module_dest_id", "database_dest_id", "crypted", "bidirectional") VALUES (2,'FluxA','<p>Description du flux A</p>','2020-06-17 14:50:59','2021-09-29 06:02:26',NULL,1,NULL,NULL,NULL,2,NULL,NULL,NULL,0,1),(3,'FluxC','<p>Flux de test</p>','2020-07-07 13:58:22','2021-09-23 17:04:30',NULL,2,NULL,NULL,NULL,3,NULL,NULL,NULL,1,NULL),(4,'FluxB','<p>Flux de test 3</p>','2020-07-07 14:01:10','2021-09-29 06:07:54',NULL,NULL,NULL,4,NULL,2,NULL,NULL,NULL,1,1),(5,'Sync_DB','<p>Description du flux 01</p>','2020-07-23 10:44:35','2021-10-10 11:16:32',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,1,0),(6,'Flux_MOD_01','<p>Fuld module</p>','2020-07-23 10:48:20','2021-09-29 05:59:35',NULL,NULL,NULL,3,NULL,NULL,NULL,2,NULL,0,0),(7,'Flux_SER_01','Description du flux service 01','2020-07-23 10:51:41','2020-07-23 10:51:41',NULL,NULL,3,NULL,NULL,NULL,4,NULL,NULL,0,NULL),(8,'Fulx 07','Description du flux 07','2020-09-05 04:56:57','2020-09-05 04:57:36',NULL,NULL,1,NULL,NULL,NULL,2,NULL,NULL,1,NULL),(9,'FLux DB_02','<p>Description du flux 2</p>','2020-09-05 05:12:05','2021-09-29 05:59:19',NULL,2,NULL,NULL,NULL,NULL,NULL,2,NULL,0,0),(10,'SRV10_to_SRV11','<p>Transfert from SRV10 to SRV11</p>','2021-08-02 15:13:31','2021-08-02 15:13:31',NULL,NULL,10,NULL,NULL,NULL,11,NULL,NULL,0,NULL),(11,'SRV4_to_SRV10',NULL,'2021-08-02 15:13:57','2021-08-02 15:13:57',NULL,NULL,5,NULL,NULL,NULL,10,NULL,NULL,1,NULL),(12,'SRV6_to_SRV10','<p>service 6 to service 10</p>','2021-08-02 15:14:36','2021-08-02 15:14:36',NULL,NULL,7,NULL,NULL,NULL,10,NULL,NULL,1,NULL),(13,'Syncy System',NULL,'2021-08-02 18:01:21','2021-08-02 18:01:21',NULL,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL),(14,'00001',NULL,'2021-09-01 07:00:09','2021-09-01 07:00:21','2021-09-01 07:00:21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL),(15,'0002',NULL,'2021-09-01 07:00:15','2021-09-01 07:00:21','2021-09-01 07:00:21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL);
/*!40000 ALTER TABLE "fluxes" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "forest_ads"
--

LOCK TABLES "forest_ads" WRITE;
/*!40000 ALTER TABLE "forest_ads" DISABLE KEYS */;
INSERT INTO "forest_ads" ("id", "name", "description", "created_at", "updated_at", "deleted_at", "zone_admin_id") VALUES (1,'AD1','<p>Foret de l\'AD 1</p>','2020-07-03 07:50:07','2020-07-03 07:50:29',NULL,1),(2,'AD2','<p>Foret de l\'AD2</p>','2020-07-03 07:50:19','2020-07-03 07:50:19',NULL,1);
/*!40000 ALTER TABLE "forest_ads" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "gateways"
--

LOCK TABLES "gateways" WRITE;
/*!40000 ALTER TABLE "gateways" DISABLE KEYS */;
INSERT INTO "gateways" ("id", "name", "description", "ip", "authentification", "created_at", "updated_at", "deleted_at") VALUES (1,'GW01','<p>Gateway 01 vers réseau médor</p>','123.5.6.4/12','Carte à puce','2020-07-13 17:34:45','2020-07-13 17:34:45',NULL),(2,'Workspace One','<p>Test workspace One</p>','10.10.10.1','Token','2021-04-17 19:32:57','2021-04-17 19:40:31','2021-04-17 19:40:31'),(3,'PubicGW','<p>Public Gateway</p>','10.10.10.1','Token','2021-04-17 19:39:04','2021-04-17 19:40:25','2021-04-17 19:40:25'),(4,'PublicGW','<p>Public gateway</p>','8.8.8.8','Token','2021-04-17 19:40:48','2021-04-17 19:48:34',NULL),(5,'GW02','<p>Second gateway</p>',NULL,'Token','2021-05-18 18:27:13','2021-08-18 18:04:23',NULL);
/*!40000 ALTER TABLE "gateways" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "information"
--

LOCK TABLES "information" WRITE;
/*!40000 ALTER TABLE "information" DISABLE KEYS */;
INSERT INTO "information" ("id", "name", "description", "owner", "administrator", "storage", "security_need_c", "sensitivity", "constraints", "created_at", "updated_at", "deleted_at", "security_need_i", "security_need_a", "security_need_t") VALUES (1,'Information 1','<p>Description de l\'information 1</p>','Luc',NULL,'externe',1,'Donnée à caractère personnel','<p>Description des contraintes règlementaires et normatives</p>','2020-06-13 00:06:43','2021-11-04 07:43:27',NULL,3,2,2),(2,'information 2','<p>Description de l\'information</p>','Nestor','Nom de l\'administrateur','externe',2,'Donnée à caractère personnel',NULL,'2020-06-13 00:09:13','2021-08-19 16:42:53',NULL,1,1,1),(3,'information 3','<p>Descripton de l\'information 3</p>','Paul','Jean','Local',4,'Donnée à caractère personnel',NULL,'2020-06-13 00:10:07','2021-09-28 17:42:07',NULL,4,3,4),(4,'Information de test','<p>decription du test</p>','RSSI','Paul','Local',1,'Technical',NULL,'2020-07-01 15:00:37','2021-08-19 16:45:52',NULL,1,1,1),(5,'Données du client','<p>Données d\'identification du client</p>','Nestor','Paul','Local',2,'Donnée à caractère personnel','<p>RGPD</p>','2021-05-14 10:50:09','2022-03-21 17:12:30',NULL,2,2,2);
/*!40000 ALTER TABLE "information" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "information_process"
--

LOCK TABLES "information_process" WRITE;
/*!40000 ALTER TABLE "information_process" DISABLE KEYS */;
INSERT INTO "information_process" ("information_id", "process_id") VALUES (3,2),(4,3),(4,4),(4,1),(1,4),(2,9),(5,1),(5,2),(5,4),(5,9);
/*!40000 ALTER TABLE "information_process" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "lan_man"
--

LOCK TABLES "lan_man" WRITE;
/*!40000 ALTER TABLE "lan_man" DISABLE KEYS */;
INSERT INTO "lan_man" ("man_id", "lan_id") VALUES (1,1),(2,1),(2,2),(2,3);
/*!40000 ALTER TABLE "lan_man" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "lan_wan"
--

LOCK TABLES "lan_wan" WRITE;
/*!40000 ALTER TABLE "lan_wan" DISABLE KEYS */;
INSERT INTO "lan_wan" ("wan_id", "lan_id") VALUES (1,1);
/*!40000 ALTER TABLE "lan_wan" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "lans"
--

LOCK TABLES "lans" WRITE;
/*!40000 ALTER TABLE "lans" DISABLE KEYS */;
INSERT INTO "lans" ("id", "name", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'LAN_1','Lan principal','2020-07-22 05:42:00','2020-07-22 05:42:00',NULL),(2,'LAN_2','Second LAN','2021-06-23 19:19:38','2021-06-23 19:19:38',NULL),(3,'LAN_0','Lan zero','2021-06-23 19:20:04','2021-06-23 19:20:04',NULL);
/*!40000 ALTER TABLE "lans" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "logical_server_m_application"
--

LOCK TABLES "logical_server_m_application" WRITE;
/*!40000 ALTER TABLE "logical_server_m_application" DISABLE KEYS */;
INSERT INTO "logical_server_m_application" ("m_application_id", "logical_server_id") VALUES (2,1),(2,2),(3,2),(1,1),(18,4),(15,3),(4,2),(4,5);
/*!40000 ALTER TABLE "logical_server_m_application" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "logical_server_physical_server"
--

LOCK TABLES "logical_server_physical_server" WRITE;
/*!40000 ALTER TABLE "logical_server_physical_server" DISABLE KEYS */;
INSERT INTO "logical_server_physical_server" ("logical_server_id", "physical_server_id") VALUES (2,1),(2,2),(1,1),(1,7),(3,8),(4,7),(5,8);
/*!40000 ALTER TABLE "logical_server_physical_server" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "logical_servers"
--

LOCK TABLES "logical_servers" WRITE;
/*!40000 ALTER TABLE "logical_servers" DISABLE KEYS */;
INSERT INTO "logical_servers" ("id", "name", "description", "net_services", "configuration", "created_at", "updated_at", "deleted_at", "operating_system", "address_ip", "cpu", "memory", "environment", "disk", "install_date", "update_date") VALUES (1,'SRV-1','<p>Description du serveur 1</p>','DNS, HTTP, HTTPS','<p>Configuration du serveur 1</p>','2020-07-12 16:57:42','2021-08-17 13:13:21',NULL,'Windows 3.1','10.10.1.1, 10.10.10.1','2','8','PROD',60,NULL,NULL),(2,'SRV-2','<p>Description du serveur 2</p>','HTTPS, SSH','<p>Configuration par défaut</p>','2020-07-30 10:00:16','2021-08-17 18:17:41',NULL,'Windows 10','10.50.1.2','2','5','PROD',100,NULL,NULL),(3,'SRV-3','<p>Description du serveur 3</p>','HTTP, HTTPS',NULL,'2021-08-26 14:33:03','2021-08-26 14:33:38',NULL,'Ubuntu 20.04','10.70.8.3','4','16','PROD',80,NULL,NULL),(4,'SRV-42','<p><i>The Ultimate Question of Life, the Universe and Everything</i></p>',NULL,'<p>Full configuration</p>','2021-11-15 16:03:59','2022-03-20 11:39:54',NULL,'OS 42','10.0.0.42','42','42 G','PROD',42,NULL,NULL),(5,'SRV-4','<p>Description du serveur 4</p>',NULL,NULL,'2022-05-02 16:43:02','2022-05-02 16:49:34',NULL,'Ubunti 22.04 LTS','10.10.3.2','4','2','Dev',NULL,'2022-05-01 20:47:41','2022-05-02 20:47:47');
/*!40000 ALTER TABLE "logical_servers" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "m_application_events"
--

LOCK TABLES "m_application_events" WRITE;
/*!40000 ALTER TABLE "m_application_events" DISABLE KEYS */;
/*!40000 ALTER TABLE "m_application_events" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "m_application_process"
--

LOCK TABLES "m_application_process" WRITE;
/*!40000 ALTER TABLE "m_application_process" DISABLE KEYS */;
INSERT INTO "m_application_process" ("m_application_id", "process_id") VALUES (2,1),(2,2),(3,2),(1,1),(14,2),(4,3),(12,4),(16,1),(16,2),(16,3),(16,4),(16,9);
/*!40000 ALTER TABLE "m_application_process" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "m_applications"
--

LOCK TABLES "m_applications" WRITE;
/*!40000 ALTER TABLE "m_applications" DISABLE KEYS */;
INSERT INTO "m_applications" ("id", "name", "description", "security_need_c", "responsible", "functional_referent", "type", "technology", "external", "users", "editor", "created_at", "updated_at", "deleted_at", "entity_resp_id", "application_block_id", "documentation", "security_need_i", "security_need_a", "security_need_t", "version", "install_date", "update_date") VALUES (1,'Application 1','<p>Description de l\'application 1</p>',1,'RSSI',NULL,'logiciel','Microsoft',NULL,'> 20',NULL,'2020-06-14 09:20:15','2022-03-20 17:53:29',NULL,2,3,'//Documentation/application1.docx',1,1,1,'1.2',NULL,NULL),(2,'Application 2','<p><i>Description</i> de l\'<strong>application</strong> 2</p>',2,'RSSI',NULL,'progiciel','martian','SaaS','>100',NULL,'2020-06-14 09:31:16','2022-02-06 15:52:36',NULL,18,1,'None',2,2,2,'1.0',NULL,NULL),(3,'Application 3','<p>Test application 3</p>',1,'RSSI',NULL,'progiciel','Microsoft','Interne','>100',NULL,'2020-06-17 17:33:41','2021-05-15 08:06:53',NULL,12,2,'Aucune',2,3,3,NULL,NULL,NULL),(4,'Application 4','<p>Description app4</p>',2,'RSSI',NULL,'logiciel','Microsoft','Internl','>100',NULL,'2020-08-11 14:13:02','2021-07-11 08:59:57',NULL,1,2,'None',2,3,2,NULL,NULL,NULL),(5,'CUST AP01','<p>Customer appication</p>',0,NULL,NULL,NULL,'web',NULL,NULL,NULL,'2020-08-22 04:58:18','2020-08-26 14:56:20','2020-08-26 14:56:20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,'totototo',NULL,0,NULL,NULL,NULL,'totottoo',NULL,NULL,NULL,'2020-08-22 04:59:26','2020-08-22 04:59:43','2020-08-22 04:59:43',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,'Windows Word','<p>Description de l\'application</p>',3,'Nestor',NULL,'artificiel','client lourd',NULL,'>100',NULL,'2020-08-23 08:20:34','2020-08-26 14:56:23','2020-08-26 14:56:23',10,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,'Application 99',NULL,1,'André',NULL,'progiciel','client lourd','SaaS','>100',NULL,'2020-08-23 10:08:02','2020-08-26 14:56:13','2020-08-26 14:56:13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,'Test33','<p>fsfsdfsd</p>',0,'Nestor',NULL,'progiciel','martian',NULL,NULL,NULL,'2020-08-26 14:54:05','2020-08-26 14:54:35','2020-08-26 14:54:35',10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,'Test33R','<p>fsfsdfsd</p>',0,'Nestor',NULL,'progiciel','martian',NULL,NULL,NULL,'2020-08-26 14:54:28','2020-08-26 14:54:39','2020-08-26 14:54:39',10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,'SuperApp','<p>Supper application</p>',0,'RSSI',NULL,'logiciel','martian',NULL,NULL,NULL,'2021-04-12 14:54:57','2021-04-12 17:10:44','2021-04-12 17:10:44',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(12,'SuperApp','<p>Super super application !</p>',1,'RSSI',NULL,'Web','Oracle','Interne',NULL,NULL,'2021-04-12 17:10:59','2021-06-23 19:33:15',NULL,1,2,NULL,1,1,1,NULL,NULL,NULL),(13,'test application',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-05-07 08:23:59','2021-05-07 08:24:03','2021-05-07 08:24:03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,'Windows Calc','<p>Calculatrice windows</p>',2,'RSSI',NULL,'logiciel','Microsoft','Internl',NULL,NULL,'2021-05-13 08:15:27','2022-03-20 17:53:29',NULL,1,3,NULL,0,0,0,NULL,NULL,NULL),(15,'Compta','<p>Application de comptabilité</p>',3,'RSSI',NULL,'progiciel','Microsoft','Interne','>100',NULL,'2021-05-15 07:53:15','2021-05-15 07:53:15',NULL,1,2,NULL,4,2,3,NULL,NULL,NULL),(16,'Queue Manager','<p>Queue manager</p>',4,'RSSI',NULL,'logiciel','Internal Dev','Interne','>100',NULL,'2021-08-02 15:17:11','2021-08-02 15:18:32',NULL,1,1,'//Portal/QueueManager.doc',4,4,4,NULL,NULL,NULL),(17,'test',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-10-10 11:03:24','2021-10-10 11:03:24',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL),(18,'Application 42','<p>The Ultimate Question of Life, the Universe and Everything</p>',-1,'Nestor',NULL,NULL,NULL,NULL,NULL,NULL,'2021-11-15 16:03:20','2021-12-11 10:06:18',NULL,NULL,NULL,NULL,-1,-1,0,NULL,NULL,NULL);
/*!40000 ALTER TABLE "m_applications" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "macro_processuses"
--

LOCK TABLES "macro_processuses" WRITE;
/*!40000 ALTER TABLE "macro_processuses" DISABLE KEYS */;
INSERT INTO "macro_processuses" ("id", "name", "description", "io_elements", "security_need_c", "owner", "created_at", "updated_at", "deleted_at", "security_need_i", "security_need_a", "security_need_t") VALUES (1,'Macro-Processus 1','<p>Description du macro-processus de test.</p>','<p>Entrant :</p><ul><li>donnée 1</li><li>donnée 2</li><li>donnée 3</li></ul><p>Sortant :</p><ul><li>donnée 4</li><li>donnée 5</li></ul>',4,'Nestor','2020-06-10 07:02:16','2021-05-14 13:29:36',NULL,3,2,1),(2,'Macro-Processus 2','<p>Description du macro-processus</p>','<p>Valeur de test</p>',1,'Simon','2020-06-13 01:03:42','2021-05-14 07:21:10',NULL,2,3,4),(3,'Valeur de test','<p>Valeur de test</p>','<p>Valeur de test</p>',3,'All','2020-08-09 05:32:37','2020-08-24 14:45:57','2020-08-24 14:45:57',NULL,NULL,NULL),(4,'Proc3','<p>dfsdf</p>','<p>dsfsdf</p>',0,NULL,'2020-08-31 14:13:55','2020-08-31 14:31:29','2020-08-31 14:31:29',NULL,NULL,NULL),(5,'Proc4','<p>dfsdf</p>','<p>dsfsdf</p>',0,NULL,'2020-08-31 14:19:32','2020-08-31 14:31:29','2020-08-31 14:31:29',NULL,NULL,NULL),(6,'Proc5','<p>dfsdf</p>','<p>dsfsdf</p>',0,NULL,'2020-08-31 14:29:20','2020-08-31 14:31:29','2020-08-31 14:31:29',NULL,NULL,NULL),(7,'MP1','<p>sdfsdfs</p>',NULL,0,NULL,'2020-08-31 14:31:40','2020-08-31 14:38:31','2020-08-31 14:38:31',NULL,NULL,NULL),(8,'MP2','<p>sdfsdfs</p>',NULL,0,NULL,'2020-08-31 14:37:39','2020-08-31 14:38:31','2020-08-31 14:38:31',NULL,NULL,NULL),(9,'MP3','<p>sdfsdfs</p>',NULL,0,NULL,'2020-08-31 14:38:06','2020-08-31 14:38:31','2020-08-31 14:38:31',NULL,NULL,NULL),(10,'Macro-Processus 3','<p>Description du troisième macro-processus</p>','<ul><li>un</li><li>deux</li><li>trois</li><li>quatre</li></ul>',2,'Nestor','2020-11-24 08:21:38','2021-05-14 07:20:55',NULL,2,2,2),(11,'Macro-Processus 4','<p>Description du macro processus quatre</p>','<ul><li>crayon</li><li>stylos</li><li>gommes</li></ul>',1,'Pirre','2021-05-14 07:19:51','2021-09-22 11:00:08','2021-09-22 11:00:08',1,1,1);
/*!40000 ALTER TABLE "macro_processuses" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "man_wan"
--

LOCK TABLES "man_wan" WRITE;
/*!40000 ALTER TABLE "man_wan" DISABLE KEYS */;
INSERT INTO "man_wan" ("wan_id", "man_id") VALUES (1,1);
/*!40000 ALTER TABLE "man_wan" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "mans"
--

LOCK TABLES "mans" WRITE;
/*!40000 ALTER TABLE "mans" DISABLE KEYS */;
INSERT INTO "mans" ("id", "name", "created_at", "updated_at", "deleted_at") VALUES (1,'MAN_1','2020-08-22 04:17:20','2020-08-22 04:17:20',NULL),(2,'MAN_2','2021-05-07 08:14:27','2021-05-07 08:23:23',NULL),(3,'Test1','2022-04-25 12:43:02','2022-04-25 12:52:49','2022-04-25 12:52:49'),(4,'Test2','2022-04-25 12:43:09','2022-04-25 12:52:49','2022-04-25 12:52:49');
/*!40000 ALTER TABLE "mans" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "media"
--

LOCK TABLES "media" WRITE;
/*!40000 ALTER TABLE "media" DISABLE KEYS */;
/*!40000 ALTER TABLE "media" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "network_switches"
--

LOCK TABLES "network_switches" WRITE;
/*!40000 ALTER TABLE "network_switches" DISABLE KEYS */;
INSERT INTO "network_switches" ("id", "name", "ip", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'Switch de test','123.4.5.6','<p>Test</p>','2020-07-13 17:30:37','2020-07-13 17:30:37',NULL),(2,'Second switch','10.1.1.1','<p>Second commutateur de test</p>','2022-04-25 12:55:44','2022-04-25 12:55:44',NULL);
/*!40000 ALTER TABLE "network_switches" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "networks"
--

LOCK TABLES "networks" WRITE;
/*!40000 ALTER TABLE "networks" DISABLE KEYS */;
INSERT INTO "networks" ("id", "name", "protocol_type", "responsible", "responsible_sec", "security_need_c", "description", "created_at", "updated_at", "deleted_at", "security_need_i", "security_need_a", "security_need_t") VALUES (1,'Réseau 1','TCP','Pierre','Paul',1,'<p>Description du réseau 1</p>','2020-06-23 12:34:14','2021-09-22 10:20:11',NULL,2,3,4),(2,'Réseau 2','TCP','Johan','Jean-Marc',1,'<p>Description du réseau 2</p>','2020-07-01 15:45:41','2021-09-22 10:21:23',NULL,1,1,1),(3,'test',NULL,NULL,NULL,4,'<p>réseau test</p>','2021-09-22 10:30:23','2021-09-22 10:30:29','2021-09-22 10:30:29',4,4,4);
/*!40000 ALTER TABLE "networks" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "oauth_access_tokens"
--

LOCK TABLES "oauth_access_tokens" WRITE;
/*!40000 ALTER TABLE "oauth_access_tokens" DISABLE KEYS */;
INSERT INTO "oauth_access_tokens" ("id", "user_id", "client_id", "name", "scopes", "revoked", "created_at", "updated_at", "expires_at") VALUES ('076f40465ba93950056a81361b447636bd462db1e4bd29ac91f8242598d6a9e347b5b38702fd6dd9',1,1,'admin@admin.com authToken 2022-02-23 09:44:38','[]',0,'2022-02-23 08:44:39','2022-02-23 08:44:39','2023-02-23 09:44:39'),('0bee2d982b1decec4a7a205016b680a61b05cfbbd6fcc58174e3271f258b9d89c04b92ec4f979493',1,1,'admin@admin.com authToken 2022-02-23 09:37:03','[]',0,'2022-02-23 08:37:03','2022-02-23 08:37:03','2023-02-23 09:37:03'),('0dedaf015f5f0c29ae86ba56cd25452d439322fae79ba9244e7a74646897e11a66f7d56aa3fd3044',1,1,'admin@admin.com authToken 2022-02-22 22:04:21','[]',0,'2022-02-22 21:04:21','2022-02-22 21:04:21','2023-02-22 22:04:21'),('0f24a64d72541352303f9bbd467b7fd97312400bfcc9d0177ed59d105ec9c0c6f583207a9af29280',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:35:56','2022-02-20 15:35:56','2023-02-20 16:35:56'),('1033f49491489db60ed8d779d82f81889c4d674eb8b315fe02e8825a108f5f1ddab4fa014837f372',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 13:26:37','2022-02-20 13:26:37','2023-02-20 14:26:37'),('18ba10eb7784cb74aed36d4bd94944393f46a06bdb05718ce54ff57203e2bab000aa2134a16815e3',1,1,'admin@admin.com authToken 2022-02-23 09:52:40','[]',0,'2022-02-23 08:52:40','2022-02-23 08:52:40','2023-02-23 09:52:40'),('1dbb84f6892c5bca72ec03ace0ae20bfea2810cae80b45a43ca6ff582a2f0faf940f637ae31a91e2',1,1,'admin@admin.com authToken 2022-02-22 22:38:01','[]',0,'2022-02-22 21:38:01','2022-02-22 21:38:01','2023-02-22 22:38:01'),('24596e7301b0fbf7e30397834d5a6d4376041357bea021ab84a020e9af34db8dc28debda0f503ed3',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:43:03','2022-02-20 15:43:03','2023-02-20 16:43:03'),('281f4fadc9718d1dc9b72c945493c8bf8cb2c439f45d416afd95d508339cae1d97924be1e992e731',1,1,'admin@admin.com authToken 2022-02-23 09:35:47','[]',0,'2022-02-23 08:35:47','2022-02-23 08:35:47','2023-02-23 09:35:47'),('2820756a167d5feb50adf48d2828939ac0ef28a32ecea47bacfcad5c1a5edeb8c0e1b78f0d41de0a',1,1,'admin@admin.com authToken 2022-02-23 09:42:46','[]',0,'2022-02-23 08:42:46','2022-02-23 08:42:46','2023-02-23 09:42:46'),('2acc1f787eeee9656dad2d53599a09f2a2ba80278053b4d745c01c8535fbef83ebe2c8d949b16019',1,1,'Laravel Password Grant Client','[]',0,'2022-02-21 19:06:12','2022-02-21 19:06:12','2023-02-21 20:06:12'),('2bf82b9508c3b05562e435e31a3b0c27a26e5c79ede38e230be03107950e7022dd3d6bb812c1e35f',1,1,'admin@admin.com authToken 2022-02-23 08:46:40','[]',0,'2022-02-23 07:46:40','2022-02-23 07:46:40','2023-02-23 08:46:40'),('2cdc5f669916196c24157847e5f4ebcd4930afc404adfa30e724a695a57e3eb7a9be55619f90c3c3',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:42:45','2022-02-20 15:42:45','2023-02-20 16:42:45'),('2f2e9fe6b39604aa41413b16efebfe48f5cfa9a9fe17466df646c5085ca5222f49703bdaf09451ba',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 11:46:31','2022-02-20 11:46:31','2023-02-20 12:46:31'),('2f2ec31d3fee519cf90d24a7572957a9be97909b6ae2641057f8a035371be151d180996a49d3d923',1,1,'Laravel Password Grant Client','[]',0,'2022-02-21 19:09:31','2022-02-21 19:09:31','2023-02-21 20:09:31'),('313f69dc0f374a1b41a81e76cacbe54cc753708a199154c927bad2fd796b44e87aa5f32615b2918c',1,1,'Laravel Password Grant Client','[]',0,'2022-02-21 19:15:04','2022-02-21 19:15:04','2023-02-21 20:15:04'),('32314fa74b9f127a0199c28c5c89494e17cff04e752403b98c5380632dcd857b9dae63f19ff2655f',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 13:25:14','2022-02-20 13:25:14','2023-02-20 14:25:14'),('340c285fc14e8d9fcdab15db27a33e6eb9b68ea98a8e7c8492cc8e9e53adf86ab429d1674a9804ae',1,1,'admin@admin.com authToken 2022-02-23 09:51:42','[]',0,'2022-02-23 08:51:42','2022-02-23 08:51:42','2023-02-23 09:51:42'),('347d3dd7d1e6ed21dba508570543df19dc1f9a5fb58237bfbb215327309efe95070f425a5c01b516',1,1,'admin@admin.com authToken 2022-02-23 09:43:51','[]',0,'2022-02-23 08:43:51','2022-02-23 08:43:51','2023-02-23 09:43:51'),('3862746d7088409ccb3e06ce0ceece408b7fe726d50676056743f54483c451ac99d901faa3443679',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 13:25:44','2022-02-20 13:25:44','2023-02-20 14:25:44'),('3ed338802883fadec0b78b5c72af5556715735bdb3fe2f5d9a434e1177bffcb1caaaf22f19a25df9',1,1,'admin@admin.com authToken 2022-02-22 22:04:33','[]',0,'2022-02-22 21:04:33','2022-02-22 21:04:33','2023-02-22 22:04:33'),('3eeb6400809ed2aea338a8021f9b7449d5c9e9768f4af081cf05541ef9b25ec4336d6be9a1dfb70c',1,1,'admin@admin.com authToken 2022-02-23 09:04:41','[]',0,'2022-02-23 08:04:41','2022-02-23 08:04:41','2023-02-23 09:04:41'),('3eeec77cbbadf2d22e83703ed0e88dc58c414d52812860c56879d4b965caef0f8de2c2fc6412033f',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 13:22:44','2022-02-20 13:22:44','2023-02-20 14:22:44'),('4221dc5bb145a53427fb0f9c0ad6239df1f2f275e2700f37c403c3ba94cf2d5ef26452aedb74b1e5',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:44:34','2022-02-20 15:44:34','2023-02-20 16:44:34'),('48d9137a42092f16b46df567ed44c0b025653ffe61aae3e3fd11ea8a71fb9b8f1e8f5987dbb56242',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 13:23:34','2022-02-20 13:23:34','2023-02-20 14:23:34'),('4afac8619d10f171b75464f920171447af782cb4e41b5800cf50bcdee483b99a253849fb6ee5d92a',1,1,'admin@admin.com authToken 2022-02-23 09:12:47','[]',0,'2022-02-23 08:12:47','2022-02-23 08:12:47','2023-02-23 09:12:47'),('4ce075ba2b686c91b749fd9622271926a6bf0d2ec0ef803775fdc402e8a7255484b40faebc3b719a',1,1,'admin@admin.com authToken 2022-02-23 09:33:05','[]',0,'2022-02-23 08:33:05','2022-02-23 08:33:05','2023-02-23 09:33:05'),('53013df97e5483bfce79940ac5326e80d50ed9759724a6ec4c433ea434afaa501aecab9619a397d2',1,1,'admin@admin.com authToken 2022-02-23 09:44:58','[]',0,'2022-02-23 08:44:58','2022-02-23 08:44:58','2023-02-23 09:44:58'),('5a71faecbd4c17794d636e94886785132e6e7f442c6b7238d44b13ba7ace633bc4b4f6411069f545',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 13:25:41','2022-02-20 13:25:41','2023-02-20 14:25:41'),('5bca1abaa8f8f9de12b0bfca0f62b7f75758367dd281fb443a8eb554a72626a137b19fe005eb8924',1,1,'admin@admin.com authToken 2022-02-23 09:37:23','[]',0,'2022-02-23 08:37:23','2022-02-23 08:37:23','2023-02-23 09:37:23'),('5c0c409e5e985b2af4a69e882ee66b7ea5767c4682fdc3fcc209a2cd71e52be41d3957fb8f3f6f97',1,1,'admin@admin.com authToken 2022-02-23 09:50:03','[]',0,'2022-02-23 08:50:03','2022-02-23 08:50:03','2023-02-23 09:50:03'),('5ccd0b7d9f1600feb532f72f91d1f2e7157b836915a9aa9f930818c1858478a6fd3ca5398cc1b327',1,1,'admin@admin.com authToken 2022-02-22 22:34:38','[]',0,'2022-02-22 21:34:38','2022-02-22 21:34:38','2023-02-22 22:34:38'),('63d5cb6ee76b548ea7dee59da883444fc7bbf776443101675635fc44326bbce3e61862f81b496e0a',1,1,'admin@admin.com authToken 2022-02-23 09:14:54','[]',0,'2022-02-23 08:14:54','2022-02-23 08:14:54','2023-02-23 09:14:54'),('6972a51036c7901f112cb2ec16d93362683c647176d18f46f5dc558e15d4983934142f45b8c15a2e',1,1,'admin@admin.com authToken 2022-02-22 22:10:57','[]',0,'2022-02-22 21:10:57','2022-02-22 21:10:57','2023-02-22 22:10:57'),('6a6a33db03d356f0e40fb7911cf7b2e1a46b3a108bd979724b7b5931c0c133c86a67b7daf7af3a6c',1,1,'Laravel Password Grant Client','[]',0,'2022-02-21 19:00:14','2022-02-21 19:00:14','2023-02-21 20:00:14'),('6ae8df9303d879c05c16705d834dc0e33ae7f98f5a6007f8cbe1724a90a426bedd1f62ffa8788647',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:43:42','2022-02-20 15:43:42','2023-02-20 16:43:42'),('6b809e532df319a6d8b9441deb5bae7ffabbc45963c3077dfb6b2354f7b45edee245a94a63739ebf',1,1,'admin@admin.com authToken 2022-02-23 20:45:18','[]',0,'2022-02-23 19:45:18','2022-02-23 19:45:18','2023-02-23 20:45:18'),('73b6ba47b2705996dbcd9d36c90da28cacbf6bf1bcf530361dfa0cfa69f5f2f0175aee76d8b85c16',1,1,'Laravel Password Grant Client','[]',0,'2022-02-21 19:02:39','2022-02-21 19:02:39','2023-02-21 20:02:39'),('743035bf701902f024964ec1013d6cda1f0fd215721303ae625184b49dd8030b661c59124f3f81d5',1,1,'admin@admin.com authToken 2022-02-23 09:50:19','[]',0,'2022-02-23 08:50:19','2022-02-23 08:50:19','2023-02-23 09:50:19'),('75aec682cf7b6890e48e9f4f57bfa2905cc5b39e5819755271b1b18b2edbefa8b8d77341127acc8c',1,1,'admin@admin.com authToken 2022-02-23 09:35:00','[]',0,'2022-02-23 08:35:00','2022-02-23 08:35:00','2023-02-23 09:35:00'),('7819ef55f3da6d2c09455e2d51e1e0f6732031a8b86d2f6ecf53aad4f35ad232dae22116dedaaefa',1,1,'admin@admin.com authToken 2022-02-22 22:09:22','[]',0,'2022-02-22 21:09:22','2022-02-22 21:09:22','2023-02-22 22:09:22'),('788ce90a117be076a7fbced340cc06e318c051866511c42bf2ca61367708712caa2e59eb41fd7251',1,1,'admin@admin.com authToken 2022-02-22 22:37:48','[]',0,'2022-02-22 21:37:48','2022-02-22 21:37:48','2023-02-22 22:37:48'),('7a7f20c4c3ecddb6f731c55d8b105986be630918153730e888e62c518e92179070256624377a438b',1,1,'admin@admin.com authToken 2022-02-22 21:48:10','[]',0,'2022-02-22 20:48:10','2022-02-22 20:48:10','2023-02-22 21:48:10'),('8149001c4b87e86a5e5401dc4a479b485252a4460ab7def68eadc7a3a08e1cf87d4f508f43b73f4f',1,1,'admin@admin.com authToken 2022-02-23 09:50:47','[]',0,'2022-02-23 08:50:47','2022-02-23 08:50:47','2023-02-23 09:50:47'),('85b370d1dcde5eec9095799dfd7247087af8dec0e60aac031dc8026a4757f299c170736718e25ba1',1,1,'admin@admin.com authToken 2022-02-22 22:33:36','[]',0,'2022-02-22 21:33:36','2022-02-22 21:33:36','2023-02-22 22:33:36'),('85fbef941a3e301f0a566a9aab04ce2ba44401a2462ab1e30f406dd73ef8134873dc227b90f79ca2',1,1,'admin@admin.com authToken 2022-02-23 09:52:19','[]',0,'2022-02-23 08:52:19','2022-02-23 08:52:19','2023-02-23 09:52:19'),('87228afe3ba2db961e4048bf271a687c077a035fc4496368a09119f9ce7c2c7be7bcf29176494247',1,1,'admin@admin.com authToken 2022-02-23 09:47:23','[]',0,'2022-02-23 08:47:23','2022-02-23 08:47:23','2023-02-23 09:47:23'),('8ecafa943a4d9dcb5851aee2e1ad026f80a8d243dd3f0efb198b30e139f315bedc71c1f201c298bd',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 13:22:38','2022-02-20 13:22:38','2023-02-20 14:22:38'),('8f5087f6bcab70279a3df4b8fdf8464fce547ac4b9ac1d7e9747806d36cc6ca11f9f1d6c2fc5b9a7',1,1,'admin@admin.com authToken 2022-02-23 08:45:03','[]',0,'2022-02-23 07:45:03','2022-02-23 07:45:03','2023-02-23 08:45:03'),('96c5b7133cfdd202a34e15c12b42aeb1f3152ddbcbe4dae8e5bed050b1fb09e0815c350171d20bee',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:33:34','2022-02-20 15:33:34','2023-02-20 16:33:34'),('96d1c9ac67fc4e6fb6a83a3c43206a7488ff2ef1e3cfa870f8e450a0cb4f8fba8110c304e238c904',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:33:21','2022-02-20 15:33:21','2023-02-20 16:33:21'),('98b6694b0739979f3e083c7a8ebd339897053095954b1cef962d9f24eac8d9f0f865ce652832fb5d',1,1,'admin@admin.com authToken 2022-02-23 09:37:47','[]',0,'2022-02-23 08:37:48','2022-02-23 08:37:48','2023-02-23 09:37:48'),('9d7dcf82b26e8f78fca858c913c9d3274a87107133965c728fe6edaa5fe8d792b425f23b088d0ccc',1,1,'admin@admin.com authToken 2022-02-23 09:51:03','[]',0,'2022-02-23 08:51:03','2022-02-23 08:51:03','2023-02-23 09:51:03'),('a64e353b9675e6a94921909221c3c23f5e209cd274c6bfcb7ee339143236550f87b6af259cf7bfed',1,1,'admin@admin.com authToken 2022-02-23 09:47:04','[]',0,'2022-02-23 08:47:04','2022-02-23 08:47:04','2023-02-23 09:47:04'),('a7a1b89f26e9ac514145024a333fb2564294fd969bfc925ebd716e88bb6aca71fbe9c69367d88c60',1,1,'admin@admin.com authToken 2022-02-23 09:49:40','[]',0,'2022-02-23 08:49:40','2022-02-23 08:49:40','2023-02-23 09:49:40'),('aacc051cb4d432dba18ff70731cd45439cd352ea27bc98ceb59365dff99f740844562f7a1169d4bf',1,1,'admin@admin.com authToken 2022-02-22 22:39:08','[]',0,'2022-02-22 21:39:08','2022-02-22 21:39:08','2023-02-22 22:39:08'),('adf7e6beec2a4e0774d6ecd206d356749c87eaa5ebf99c2275cf39e074495520fc40330b15a6a54e',1,1,'admin@admin.com authToken 2022-02-22 22:10:09','[]',0,'2022-02-22 21:10:09','2022-02-22 21:10:09','2023-02-22 22:10:09'),('b240468ac4287b14ed0bc0b8fb83bba301a59b492888af9af8ca0cc9bb6bee9d3fd3858b9da77b67',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:37:12','2022-02-20 15:37:12','2023-02-20 16:37:12'),('b7cbb43e4e9304df811ec02679cf93802dfd074d820d4d2e263ec29529764ae3f775a0f9a33f85b0',1,1,'admin@admin.com authToken 2022-02-22 22:34:12','[]',0,'2022-02-22 21:34:12','2022-02-22 21:34:12','2023-02-22 22:34:12'),('b838b39b411b8ec4549c79b94354360a3fa257b0ad778b37f2862668f92aec6a5933548c47500aaa',1,1,'Laravel Password Grant Client','[]',0,'2022-02-21 19:04:48','2022-02-21 19:04:48','2023-02-21 20:04:48'),('bc4e2ad76f7c550b1ced12f0348949ff9f605f50fa7c55dd12673cfe040a2b88d3707ae93484c3e2',1,1,'admin@admin.com authToken 2022-02-23 09:51:27','[]',0,'2022-02-23 08:51:27','2022-02-23 08:51:27','2023-02-23 09:51:27'),('becc9d7d09ae2b8295cb7e94eca0784513ac8c4f9166543869e7ef1d70bfcf9c860038ae3868a291',1,1,'admin@admin.com authToken 2022-02-23 09:45:22','[]',0,'2022-02-23 08:45:22','2022-02-23 08:45:22','2023-02-23 09:45:22'),('bef329665dd1909410f285d527e0c55d13e7956ca0bcbc45a487d7949fe5f2df3e5812aafb62f400',1,1,'admin@admin.com authToken 2022-02-22 22:37:28','[]',0,'2022-02-22 21:37:28','2022-02-22 21:37:28','2023-02-22 22:37:28'),('bfda9ca846720a47b9fe110d7ff26bc042b757b4ebefd5fbdb930aafa67d122346683f0b4404d435',1,1,'admin@admin.com authToken 2022-02-23 09:07:35','[]',0,'2022-02-23 08:07:35','2022-02-23 08:07:35','2023-02-23 09:07:35'),('c13ce63f9d09d71ba72784bdc6056a3a713c88548fbb75dd5efd35bbb29b7329294b015cfe626a6e',1,1,'admin@admin.com authToken 2022-02-23 09:05:41','[]',0,'2022-02-23 08:05:41','2022-02-23 08:05:41','2023-02-23 09:05:41'),('c202daaa79df53c2b088721406340f19a741d768f6a46b1ecc7a1d4f1f0eb32b98220906f7056ddf',1,1,'admin@admin.com authToken 2022-02-23 09:37:14','[]',0,'2022-02-23 08:37:14','2022-02-23 08:37:14','2023-02-23 09:37:14'),('c2d92896fc38473b6ddc648da3a2f50680a8798f3860f27401f93ed053e6ca7de31b6b212d8fb340',1,1,'admin@admin.com authToken 2022-02-23 09:08:13','[]',0,'2022-02-23 08:08:13','2022-02-23 08:08:13','2023-02-23 09:08:13'),('c75ddd9cdfd1a704e78e27297030abde094f06d545e8bd89126193739b1b510b5bd8d69798565217',1,1,'admin@admin.com authToken 2022-02-22 22:33:02','[]',0,'2022-02-22 21:33:02','2022-02-22 21:33:02','2023-02-22 22:33:02'),('ca983293951e4c15d5609026fcd089e5c6995fd0069bc015d901863c9ea2567715f997dcbe34a31c',1,1,'admin@admin.com authToken 2022-02-22 21:52:55','[]',0,'2022-02-22 20:52:55','2022-02-22 20:52:55','2023-02-22 21:52:55'),('cc0dc71d5408bf5f134d22df66a3dca34b20d11c6c74d013e59b5e8bd7fafff216af6ba02863d59b',1,1,'admin@admin.com authToken 2022-02-23 09:04:14','[]',0,'2022-02-23 08:04:14','2022-02-23 08:04:14','2023-02-23 09:04:14'),('cde06dac4d382b143625422d6480c6f0ec9e4571906a82524f4ec3f45bc2f9962e574f094ba6b391',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:43:08','2022-02-20 15:43:08','2023-02-20 16:43:08'),('d3e7f94332c4da66f93638b0e8be6c5e7e8e48257234e713f3523436805207f558e13eb380574b04',1,1,'admin@admin.com authToken 2022-02-23 09:32:19','[]',0,'2022-02-23 08:32:19','2022-02-23 08:32:19','2023-02-23 09:32:19'),('d56f92a0d0a14c77e9e1a0fd6a67e7b4c9b5d868a7e6ca73b1c7d0259d09235b6d45b1ada33063a1',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:44:49','2022-02-20 15:44:49','2023-02-20 16:44:49'),('d87d89856ad722cfc5d771e9f0dead725c1d343d1dcfac25b78ae137c86b18ad7733d559f101172d',1,1,'admin@admin.com authToken 2022-02-23 09:15:37','[]',0,'2022-02-23 08:15:37','2022-02-23 08:15:37','2023-02-23 09:15:37'),('db4d97d6b031e70ba0ee1dec3ded89022ee3a1754c17417d89d7521be0ea6e5d4beb259498473c04',1,1,'admin@admin.com authToken 2022-02-23 09:44:22','[]',0,'2022-02-23 08:44:22','2022-02-23 08:44:22','2023-02-23 09:44:22'),('dd46fd69a65f8bb840d2f316dd70276fe6736bfd6d6fc4fad3b14115945978164739627effb2b3cb',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:42:04','2022-02-20 15:42:04','2023-02-20 16:42:04'),('e12601308373d42d0039480432c18e6e9d235ce140cefa01351579bae6e047fe0c88dba13d829718',1,1,'admin@admin.com authToken 2022-02-23 08:20:07','[]',0,'2022-02-23 07:20:07','2022-02-23 07:20:07','2023-02-23 08:20:07'),('e16149b8fb8911fdedc66139f87b85b6cc358145a1bf455ee827bb55591234ce6247343240c1dced',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:43:20','2022-02-20 15:43:20','2023-02-20 16:43:20'),('e35067879bc5c00d3b40957f5f86dcb66dd7ae0b72bbfb604aaa6c89aca0fea76b3d3feb06868cb7',1,1,'admin@admin.com authToken 2022-02-22 22:02:56','[]',0,'2022-02-22 21:02:56','2022-02-22 21:02:56','2023-02-22 22:02:56'),('e79ae028ee35b60ae4ca2c15ce31c8a151881136dabc99bcbb8bc2e26ba52f33da45ee9e141911d7',1,1,'admin@admin.com authToken 2022-02-22 22:05:14','[]',0,'2022-02-22 21:05:14','2022-02-22 21:05:14','2023-02-22 22:05:14'),('edcc0d770768e2f542332006c1ddbed1134a2a7e67a872f5b9db1be7929bc7c30682345b418141ca',1,1,'admin@admin.com authToken 2022-02-22 22:38:28','[]',0,'2022-02-22 21:38:28','2022-02-22 21:38:28','2023-02-22 22:38:28'),('ee0dcee5ef0c46b4900f7fcfb3808a54f96cab422e743f362c4b4d4e51a18efd73155a0945ddaadd',1,1,'admin@admin.com authToken 2022-02-23 09:43:19','[]',0,'2022-02-23 08:43:19','2022-02-23 08:43:19','2023-02-23 09:43:19'),('ef7a99f67a8c87eca2cc3626441403473009a3cbf9f7b2507bc11e80d3d7009984d255b5cff7b0f1',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:36:04','2022-02-20 15:36:04','2023-02-20 16:36:04'),('f33dfbf92aa23991160a7f75a311bc5957c9790567b5a68c089ec026ca5a07d6d2c7ef897f2afabe',1,1,'admin@admin.com authToken 2022-02-23 08:45:53','[]',0,'2022-02-23 07:45:53','2022-02-23 07:45:53','2023-02-23 08:45:53'),('f37117c86f2d48fcc83b6c1fabf9a0aeac62b28a8397e612805db6fb33bfb0157e356537aa708146',1,1,'admin@admin.com authToken 2022-02-22 22:18:01','[]',0,'2022-02-22 21:18:01','2022-02-22 21:18:01','2023-02-22 22:18:01'),('f4762ccf511467c731bcf31e282b249d85d0034ca69a70ffe8173e353a45227d95634f62dffa6d74',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:32:29','2022-02-20 15:32:29','2023-02-20 16:32:29'),('f482d1f2c009a838ed86f3937ca08257f8c1b21d229a6b8d3ae086f7a4c2d12f6901f8c9add9a6c9',1,1,'admin@admin.com authToken 2022-02-23 09:48:44','[]',0,'2022-02-23 08:48:44','2022-02-23 08:48:44','2023-02-23 09:48:44'),('f8ededb06c331173eafe3ae857bff2ca1fc03207c30f690072169d8bcdcfb38ec9b51af6b40eb6d1',1,1,'Laravel Password Grant Client','[]',0,'2022-02-20 15:42:36','2022-02-20 15:42:36','2023-02-20 16:42:36'),('fc32912ffcb1843b0c8f13e7f9ca8f2c4ca3fa094e82b8ec9557bca74d80cf481747f54e870f54e1',1,1,'admin@admin.com authToken 2022-02-23 09:52:23','[]',0,'2022-02-23 08:52:23','2022-02-23 08:52:23','2023-02-23 09:52:23'),('fd36581c0d0fbf34ac903e220bee80be4a2dfbbfae58d9a53e73cca9e95c87e47228558b0529ac52',1,1,'admin@admin.com authToken 2022-02-23 13:35:32','[]',0,'2022-02-23 12:35:32','2022-02-23 12:35:32','2023-02-23 13:35:32'),('fdeb2a9abfa8aa0bc161759ae90a68bccf9ebec73a7d9edb3b402684c58e90870be4bfacc5c86a9c',1,1,'admin@admin.com authToken 2022-02-23 08:47:23','[]',0,'2022-02-23 07:47:23','2022-02-23 07:47:23','2023-02-23 08:47:23'),('ffc1b04fec806e3f4801a3881d8c02bcfed79c590c67309dd072fd811f799d302f74dcb5aa795c0a',1,1,'admin@admin.com authToken 2022-02-23 09:48:16','[]',0,'2022-02-23 08:48:16','2022-02-23 08:48:16','2023-02-23 09:48:16');
/*!40000 ALTER TABLE "oauth_access_tokens" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "oauth_auth_codes"
--

LOCK TABLES "oauth_auth_codes" WRITE;
/*!40000 ALTER TABLE "oauth_auth_codes" DISABLE KEYS */;
/*!40000 ALTER TABLE "oauth_auth_codes" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "oauth_clients"
--

LOCK TABLES "oauth_clients" WRITE;
/*!40000 ALTER TABLE "oauth_clients" DISABLE KEYS */;
INSERT INTO "oauth_clients" ("id", "user_id", "name", "secret", "provider", "redirect", "personal_access_client", "password_client", "revoked", "created_at", "updated_at") VALUES (1,NULL,'Laravel Personal Access Client','Ppsdr9IT9chEUXM87zie6WHcI7AoFdiUFIOajd4G',NULL,'http://localhost',1,0,0,'2022-02-20 11:46:16','2022-02-20 11:46:16'),(2,NULL,'Laravel Password Grant Client','LDmbqXFAlzi2yoJbRkfK9LzTYjIkVmfYNQx9fo6X','users','http://localhost',0,1,0,'2022-02-20 11:46:16','2022-02-20 11:46:16');
/*!40000 ALTER TABLE "oauth_clients" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "oauth_personal_access_clients"
--

LOCK TABLES "oauth_personal_access_clients" WRITE;
/*!40000 ALTER TABLE "oauth_personal_access_clients" DISABLE KEYS */;
INSERT INTO "oauth_personal_access_clients" ("id", "client_id", "created_at", "updated_at") VALUES (1,1,'2022-02-20 11:46:16','2022-02-20 11:46:16');
/*!40000 ALTER TABLE "oauth_personal_access_clients" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "oauth_refresh_tokens"
--

LOCK TABLES "oauth_refresh_tokens" WRITE;
/*!40000 ALTER TABLE "oauth_refresh_tokens" DISABLE KEYS */;
/*!40000 ALTER TABLE "oauth_refresh_tokens" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "operation_task"
--

LOCK TABLES "operation_task" WRITE;
/*!40000 ALTER TABLE "operation_task" DISABLE KEYS */;
INSERT INTO "operation_task" ("operation_id", "task_id") VALUES (1,1),(1,2),(2,1),(3,3),(4,2),(5,1),(5,2),(5,3);
/*!40000 ALTER TABLE "operation_task" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "operations"
--

LOCK TABLES "operations" WRITE;
/*!40000 ALTER TABLE "operations" DISABLE KEYS */;
INSERT INTO "operations" ("id", "name", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'Operation 1','<p>Description de l\'opération</p>','2020-06-13 00:02:42','2020-06-13 00:02:42',NULL),(2,'Operation 2','<p>Description de l\'opération</p>','2020-06-13 00:02:58','2020-06-13 00:02:58',NULL),(3,'Operation 3','<p>Desciption de l\'opération</p>','2020-06-13 00:03:11','2020-07-15 14:34:52',NULL),(4,'Operation 4',NULL,'2020-07-15 14:34:02','2020-07-15 14:34:02',NULL),(5,'Master operation','<p>Opération maitre</p>','2020-08-15 04:01:40','2020-08-15 04:01:40',NULL);
/*!40000 ALTER TABLE "operations" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "password_resets"
--

LOCK TABLES "password_resets" WRITE;
/*!40000 ALTER TABLE "password_resets" DISABLE KEYS */;
INSERT INTO "password_resets" ("email", "token", "created_at") VALUES ('didier@localhost','$2y$10$5XvIYVdy0enc1ZouEg0TR.ri0eK9OeGP6zSFGnUHRPtpz9coRuk7u','2021-06-14 17:27:27');
/*!40000 ALTER TABLE "password_resets" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "peripherals"
--

LOCK TABLES "peripherals" WRITE;
/*!40000 ALTER TABLE "peripherals" DISABLE KEYS */;
INSERT INTO "peripherals" ("id", "name", "type", "description", "responsible", "created_at", "updated_at", "deleted_at", "site_id", "building_id", "bay_id") VALUES (1,'PER_01','IBM 3400','<p>important peripheral</p>','Marcel','2020-07-25 06:18:40','2020-07-25 06:19:46',NULL,1,2,NULL),(2,'PER_02','IBM 5600','<p>Description</p>','Nestor','2020-07-25 06:19:18','2020-07-25 06:19:18',NULL,3,5,NULL),(3,'PER_03','HAL 8100','<p>Space device</p>','Niel','2020-07-25 06:19:58','2020-07-25 06:20:18',NULL,3,4,NULL);
/*!40000 ALTER TABLE "peripherals" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "permission_role"
--

LOCK TABLES "permission_role" WRITE;
/*!40000 ALTER TABLE "permission_role" DISABLE KEYS */;
INSERT INTO "permission_role" ("role_id", "permission_id") VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(1,31),(1,32),(1,33),(1,34),(1,35),(1,36),(1,37),(1,38),(1,39),(1,40),(1,41),(1,42),(1,43),(1,44),(1,45),(1,46),(1,47),(1,48),(1,49),(1,50),(1,51),(1,52),(1,53),(1,54),(1,55),(1,56),(1,57),(1,58),(1,59),(1,60),(1,61),(1,62),(1,63),(1,64),(1,65),(1,66),(1,67),(1,68),(1,69),(1,70),(1,71),(1,72),(1,73),(1,74),(1,75),(1,76),(1,77),(1,78),(1,79),(1,80),(1,81),(1,82),(1,83),(1,84),(1,85),(1,86),(1,87),(1,88),(1,89),(1,90),(1,91),(1,92),(1,93),(1,94),(1,95),(1,96),(1,97),(1,98),(1,99),(1,100),(1,101),(1,102),(1,103),(1,104),(1,105),(1,106),(1,107),(1,108),(1,109),(1,110),(1,111),(1,112),(1,113),(1,114),(1,115),(1,116),(1,117),(1,118),(1,119),(1,120),(1,121),(1,122),(1,123),(1,124),(1,125),(1,126),(1,127),(1,128),(1,129),(1,130),(1,131),(1,132),(1,133),(1,134),(1,135),(1,136),(1,137),(1,138),(1,139),(1,140),(1,141),(1,142),(1,143),(1,144),(1,145),(1,146),(1,147),(1,148),(1,149),(1,150),(1,151),(1,152),(1,153),(1,154),(1,155),(1,156),(1,157),(1,158),(1,159),(1,160),(1,161),(1,162),(1,163),(1,164),(1,165),(1,166),(1,167),(1,168),(1,169),(1,170),(1,171),(1,172),(1,173),(1,174),(1,175),(1,176),(1,177),(1,178),(1,179),(1,180),(1,181),(1,182),(1,183),(1,184),(1,185),(1,186),(1,187),(1,188),(1,189),(1,190),(1,191),(1,192),(1,193),(1,194),(1,195),(1,196),(1,197),(1,198),(1,199),(1,200),(1,201),(1,202),(1,203),(1,204),(1,205),(1,206),(1,207),(1,208),(1,209),(1,210),(1,211),(1,212),(1,213),(1,214),(1,215),(1,216),(1,217),(1,218),(1,219),(1,220),(1,221),(1,222),(1,223),(1,224),(1,225),(1,226),(1,227),(1,228),(1,229),(1,230),(1,231),(1,232),(1,233),(1,234),(1,235),(1,236),(1,237),(1,238),(1,239),(1,240),(1,241),(1,242),(1,243),(1,244),(1,245),(1,246),(1,247),(1,248),(1,249),(1,250),(1,251),(1,252),(1,253),(1,254),(1,255),(1,256),(1,257),(1,258),(1,259),(1,260),(1,261),(1,262),(2,17),(2,18),(2,19),(2,20),(2,21),(2,22),(2,23),(2,24),(2,25),(2,26),(2,27),(2,28),(2,29),(2,30),(2,31),(2,32),(2,33),(2,34),(2,35),(2,36),(2,37),(2,38),(2,39),(2,40),(2,41),(2,42),(2,43),(2,44),(2,45),(2,46),(2,47),(2,48),(2,49),(2,50),(2,51),(2,52),(2,53),(2,54),(2,55),(2,56),(2,57),(2,58),(2,59),(2,60),(2,61),(2,62),(2,63),(2,64),(2,65),(2,66),(2,67),(2,68),(2,69),(2,70),(2,71),(2,72),(2,73),(2,74),(2,75),(2,76),(2,77),(2,78),(2,79),(2,80),(2,81),(2,82),(2,83),(2,84),(2,85),(2,86),(2,87),(2,88),(2,89),(2,90),(2,91),(2,92),(2,93),(2,94),(2,95),(2,96),(2,97),(2,98),(2,99),(2,100),(2,101),(2,102),(2,103),(2,104),(2,105),(2,106),(2,107),(2,108),(2,109),(2,110),(2,111),(2,112),(2,113),(2,114),(2,115),(2,116),(2,117),(2,118),(2,119),(2,120),(2,121),(2,122),(2,123),(2,124),(2,125),(2,126),(2,127),(2,128),(2,129),(2,130),(2,131),(2,132),(2,133),(2,134),(2,135),(2,136),(2,137),(2,138),(2,139),(2,140),(2,141),(2,142),(2,143),(2,144),(2,145),(2,146),(2,147),(2,148),(2,149),(2,150),(2,151),(2,152),(2,153),(2,154),(2,155),(2,156),(2,157),(2,158),(2,159),(2,160),(2,161),(2,162),(2,163),(2,164),(2,165),(2,166),(2,167),(2,168),(2,169),(2,170),(2,171),(2,172),(2,173),(2,174),(2,175),(2,176),(2,177),(2,178),(2,179),(2,180),(2,181),(2,182),(2,183),(2,184),(2,185),(2,186),(2,187),(2,188),(2,189),(2,190),(2,191),(2,192),(2,193),(2,194),(2,195),(2,196),(2,197),(2,198),(2,199),(2,200),(2,201),(2,202),(2,203),(2,204),(2,205),(2,206),(2,207),(2,208),(2,209),(2,210),(2,211),(2,212),(2,213),(2,214),(2,215),(2,216),(2,217),(2,218),(2,219),(2,220),(2,221),(2,222),(2,223),(2,224),(2,225),(2,226),(2,227),(2,228),(2,229),(2,230),(2,231),(2,232),(2,233),(2,234),(2,235),(2,236),(2,237),(2,238),(2,239),(2,240),(2,241),(2,242),(2,243),(2,244),(2,245),(2,246),(2,247),(2,248),(2,249),(2,250),(2,251),(2,252),(2,253),(2,254),(2,255),(2,256),(2,257),(2,258),(2,259),(2,260),(2,261),(2,262),(3,19),(3,21),(3,22),(3,25),(3,27),(3,30),(3,32),(3,33),(3,36),(3,38),(3,41),(3,43),(3,46),(3,48),(3,51),(3,53),(3,56),(3,58),(3,61),(3,63),(3,66),(3,68),(3,69),(3,72),(3,74),(3,77),(3,79),(3,82),(3,84),(3,87),(3,89),(3,92),(3,94),(3,95),(3,98),(3,100),(3,103),(3,105),(3,108),(3,110),(3,111),(3,114),(3,116),(3,119),(3,121),(3,124),(3,126),(3,129),(3,131),(3,134),(3,136),(3,139),(3,141),(3,144),(3,146),(3,149),(3,151),(3,154),(3,156),(3,159),(3,161),(3,162),(3,165),(3,167),(3,170),(3,172),(3,175),(3,177),(3,180),(3,182),(3,185),(3,187),(3,190),(3,192),(3,195),(3,197),(3,200),(3,202),(3,205),(3,207),(3,210),(3,212),(3,215),(3,217),(3,220),(3,222),(3,225),(3,227),(3,230),(3,232),(3,235),(3,237),(3,240),(3,242),(3,245),(3,247),(3,248),(3,249),(3,252),(3,254),(3,255),(3,259),(3,261);
/*!40000 ALTER TABLE "permission_role" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "phones"
--

LOCK TABLES "phones" WRITE;
/*!40000 ALTER TABLE "phones" DISABLE KEYS */;
INSERT INTO "phones" ("id", "name", "description", "type", "created_at", "updated_at", "deleted_at", "site_id", "building_id", "physical_switch_id") VALUES (1,'Phone 01','<p>Téléphone de test</p>','MOTOROAL 3110','2020-07-21 05:16:46','2020-07-25 07:15:17',NULL,1,1,NULL),(2,'Phone 03','<p>Special AA phone</p>','Top secret red phne','2020-07-21 05:18:01','2020-07-25 07:25:38',NULL,2,4,NULL),(3,'Phone 02','<p>Description phone 02</p>','IPhone 2','2020-07-25 06:52:23','2020-07-25 07:25:19',NULL,2,3,NULL);
/*!40000 ALTER TABLE "phones" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "physical_router_vlan"
--

LOCK TABLES "physical_router_vlan" WRITE;
/*!40000 ALTER TABLE "physical_router_vlan" DISABLE KEYS */;
INSERT INTO "physical_router_vlan" ("physical_router_id", "vlan_id") VALUES (1,1),(1,3),(2,3);
/*!40000 ALTER TABLE "physical_router_vlan" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "physical_routers"
--

LOCK TABLES "physical_routers" WRITE;
/*!40000 ALTER TABLE "physical_routers" DISABLE KEYS */;
INSERT INTO "physical_routers" ("id", "description", "type", "created_at", "updated_at", "deleted_at", "site_id", "building_id", "bay_id", "name") VALUES (1,'<p>Routeur prncipal</p>','Fortinet','2020-07-10 06:58:53','2021-10-12 19:08:21',NULL,1,1,1,'R1'),(2,'<p>Routeur secondaire</p>','CISCO','2020-07-10 07:19:11','2020-07-25 08:28:17',NULL,2,3,5,'R2');
/*!40000 ALTER TABLE "physical_routers" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "physical_security_devices"
--

LOCK TABLES "physical_security_devices" WRITE;
/*!40000 ALTER TABLE "physical_security_devices" DISABLE KEYS */;
INSERT INTO "physical_security_devices" ("id", "name", "type", "description", "created_at", "updated_at", "deleted_at", "site_id", "building_id", "bay_id") VALUES (1,'Magic Gate','Gate','<p>BIG Magic Gate</p>','2021-05-20 14:40:43','2021-11-13 20:29:45',NULL,1,1,1),(2,'Magic Firewall','Firewall','<p>The magic firewall - PT3743</p>','2021-06-07 14:56:26','2021-11-13 20:29:32',NULL,2,3,5),(3,'Sensor-1','Sensor','<p>Temperature sensor</p>','2021-11-13 20:37:14','2021-11-13 20:37:56',NULL,1,3,NULL);
/*!40000 ALTER TABLE "physical_security_devices" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "physical_servers"
--

LOCK TABLES "physical_servers" WRITE;
/*!40000 ALTER TABLE "physical_servers" DISABLE KEYS */;
INSERT INTO "physical_servers" ("id", "name", "description", "responsible", "configuration", "created_at", "updated_at", "deleted_at", "site_id", "building_id", "bay_id", "physical_switch_id", "type") VALUES (1,'Serveur A1','<p>Description du serveur A1</p>','Marc','<p>OS: OS2<br>IP : 127.0.0.1<br>&nbsp;</p>','2020-06-21 05:27:02','2021-11-27 11:12:00',NULL,1,2,4,NULL,'System 840'),(2,'Serveur A2','<p>Description du serveur A2</p>','Marc','<p>Configuration du serveur A<br>OS : Linux 23.4<br>RAM: 32G</p>','2020-06-21 05:27:58','2021-11-27 11:12:12',NULL,3,5,6,NULL,'System 840'),(3,'Serveur A3','<p>Serveur mobile</p>','Marc','<p>None</p>','2020-07-14 15:30:48','2021-11-27 11:12:24',NULL,1,1,3,NULL,'System 840'),(4,'ZZ99','<p>Zoro server</p>',NULL,NULL,'2020-07-14 15:37:50','2020-08-25 14:54:58','2020-08-25 14:54:58',3,5,NULL,NULL,NULL),(5,'K01','<p>Serveur K01</p>',NULL,'<p>TOP CPU<br>TOP RAM</p>','2020-07-15 14:37:04','2020-08-29 12:08:09','2020-08-29 12:08:09',1,1,3,NULL,NULL),(6,'Mainframe 01','<p>Central accounting system</p>','Marc','<p>40G RAM<br>360P Disk<br>CICS / Cobol</p>','2020-09-05 08:02:49','2021-11-27 11:11:43',NULL,1,1,1,2,'Type 404'),(7,'Mainframe T1','<p>Mainframe de test</p>','Marc','<p>IDEM prod</p>','2020-09-05 08:22:18','2021-11-27 11:11:01',NULL,2,3,4,2,'HAL 340'),(8,'Serveur A4','<p>Departmental server</p>','Marc','<p>Standard configuration</p>','2021-06-22 15:34:33','2021-11-27 11:12:50',NULL,2,3,5,NULL,'Mini 900/2');
/*!40000 ALTER TABLE "physical_servers" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "physical_switches"
--

LOCK TABLES "physical_switches" WRITE;
/*!40000 ALTER TABLE "physical_switches" DISABLE KEYS */;
INSERT INTO "physical_switches" ("id", "name", "description", "type", "created_at", "updated_at", "deleted_at", "site_id", "building_id", "bay_id") VALUES (1,'Switch de test','<p>Master test switch.</p>','Nortel A39','2020-07-17 13:29:09','2022-04-25 16:22:02',NULL,1,2,4),(2,'Switch 2','<p>Description switch 2</p>','Alcatel 430','2020-07-17 13:31:41','2020-07-17 13:31:41',NULL,1,1,1),(3,'Switch 1','<p>Desription du premier switch.</p>','Nortel 2300','2020-07-25 05:27:27','2022-04-25 12:55:06',NULL,2,3,5),(4,'Switch 3','<p>Desciption du switch 3</p>','Alcatel 3500','2020-07-25 07:42:51','2020-07-25 07:43:21',NULL,3,5,6),(5,'AB','<p>Test 2 chars switch</p>',NULL,'2020-08-22 04:19:45','2020-08-27 16:04:20','2020-08-27 16:04:20',NULL,NULL,NULL);
/*!40000 ALTER TABLE "physical_switches" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "processes"
--

LOCK TABLES "processes" WRITE;
/*!40000 ALTER TABLE "processes" DISABLE KEYS */;
INSERT INTO "processes" ("id", "identifiant", "description", "owner", "security_need_c", "in_out", "dummy", "created_at", "updated_at", "deleted_at", "macroprocess_id", "security_need_i", "security_need_a", "security_need_t") VALUES (1,'Processus 1','<p>Description du processus 1</p>','Ched',3,'<ul><li>pommes</li><li>poires</li><li>cerise</li></ul><p>&lt;test</p>',NULL,'2020-06-17 14:36:24','2021-09-22 11:38:57',NULL,1,2,3,1),(2,'Processus 2','<p>Description du processus 2</p>','Ched',3,'<p>1 2 3 4 5 6</p>',NULL,'2020-06-17 14:36:58','2021-09-22 10:59:14',NULL,10,4,2,4),(3,'Processus 3','<p>Description du processus 3</p>','Johan',3,'<p>a,b,c</p><p>d,e,f</p>',NULL,'2020-07-01 15:50:27','2021-08-17 08:22:13',NULL,2,2,3,1),(4,'Processus 4','<p>Description du processus 4</p>','Paul',4,'<ul><li>chaussettes</li><li>pantalon</li><li>chaussures</li></ul>',NULL,'2020-08-18 15:00:36','2021-08-17 08:22:29',NULL,2,2,2,2),(5,'totoat','<p>tto</p>',NULL,1,'<p>sgksdùmfk</p>',NULL,'2020-08-27 13:16:56','2020-08-27 13:17:01','2020-08-27 13:17:01',1,NULL,NULL,NULL),(6,'ptest','<p>description de ptest</p>',NULL,0,'<p>toto titi tutu</p>',NULL,'2020-08-29 11:10:23','2020-08-29 11:10:28','2020-08-29 11:10:28',NULL,NULL,NULL,NULL),(7,'ptest2','<p>fdfsdfsdf</p>',NULL,1,'<p>fdfsdfsd</p>',NULL,'2020-08-29 11:16:42','2020-08-29 11:17:09','2020-08-29 11:17:09',1,NULL,NULL,NULL),(8,'ptest3','<p>processus de test 3</p>','CHEM - Facturation',3,'<p>dsfsdf sdf sdf sd fsd fsd f s</p>',NULL,'2020-08-29 11:19:13','2020-08-29 11:20:59','2020-08-29 11:20:59',1,NULL,NULL,NULL),(9,'Processus 5','<p>Description du cinquième processus</p>','Paul',4,'<ul><li>chat</li><li>chien</li><li>poisson</li></ul>',NULL,'2021-05-14 07:10:02','2021-09-22 10:59:14',NULL,10,3,2,3),(10,'Proc 6',NULL,NULL,0,NULL,NULL,'2021-10-08 19:18:28','2021-10-08 19:28:38','2021-10-08 19:28:38',NULL,0,0,0);
/*!40000 ALTER TABLE "processes" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "relations"
--

LOCK TABLES "relations" WRITE;
/*!40000 ALTER TABLE "relations" DISABLE KEYS */;
INSERT INTO "relations" ("id", "importance", "name", "type", "description", "created_at", "updated_at", "deleted_at", "source_id", "destination_id") VALUES (1,1,'Membre','Fourniture de service','<p>Here is the description of this relation</p>','2020-05-20 22:49:47','2021-08-17 08:20:46',NULL,1,6),(2,2,'Membre','Fournisseur de service','<p>Member description</p>','2020-05-20 23:35:11','2021-09-19 11:12:19',NULL,2,6),(3,1,'Fournisseur','Fournisseur de service','<p>description de la relation entre A et le B</p>','2020-05-20 23:39:24','2021-08-17 08:20:59',NULL,7,1),(4,2,'Membre','Fourniture de service','<p>Description du service</p>','2020-05-21 02:23:03','2021-05-23 13:06:05',NULL,2,6),(5,0,'Membre','Fournisseur de service',NULL,'2020-05-21 02:23:35','2021-05-23 13:05:18',NULL,2,6),(6,0,'Fournisseur','fourniture de service',NULL,'2020-05-21 02:24:35','2020-05-21 02:24:35',NULL,7,2),(7,0,'Membre','fourniture de service',NULL,'2020-05-21 02:26:43','2020-05-21 02:26:43',NULL,4,6),(8,3,'Rapporte',NULL,NULL,'2020-05-21 02:32:19','2020-07-05 10:10:01',NULL,1,5),(9,0,'Fournisseur','fourniture de service',NULL,'2020-05-21 02:33:33','2020-05-21 02:33:33',NULL,9,1),(10,2,'Rapporte','Fournisseur de service','<p>Régelement général APD34</p>','2020-05-22 21:21:02','2020-08-24 14:31:29',NULL,1,8),(11,2,'toto',NULL,NULL,'2020-07-05 10:14:15','2020-07-05 10:14:55','2020-07-05 10:14:55',3,2),(12,1,'Fournisseur','Fournisseur de service','<p>Analyse de risques</p>','2020-08-24 14:23:30','2020-08-24 14:23:48',NULL,2,4),(13,1,'Fournisseur','Fourniture de service','<p>Description du service</p>','2020-10-14 17:06:24','2021-05-23 13:06:34',NULL,2,12);
/*!40000 ALTER TABLE "relations" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "role_user"
--

LOCK TABLES "role_user" WRITE;
/*!40000 ALTER TABLE "role_user" DISABLE KEYS */;
INSERT INTO "role_user" ("user_id", "role_id") VALUES (1,1),(2,1),(3,2),(8,3),(8,1),(8,2),(9,2);
/*!40000 ALTER TABLE "role_user" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "routers"
--

LOCK TABLES "routers" WRITE;
/*!40000 ALTER TABLE "routers" DISABLE KEYS */;
INSERT INTO "routers" ("id", "name", "description", "rules", "created_at", "updated_at", "deleted_at", "ip_addresses") VALUES (1,'ROUT_00','<p>Description du routeur 1</p>','<p>liste des règles dans //serveur/liste.txt</p>','2020-07-13 17:32:25','2021-09-21 15:00:36',NULL,'10.50.1.1, 10.60.1.1, 10.70.1.1'),(2,'ROUT_01','<p>Description of router 01</p>','<p>list of rules :&nbsp;</p><ul><li>a</li><li>b</li><li>c</li><li>d</li></ul>','2021-09-21 13:47:47','2021-09-21 14:53:35',NULL,'10.10.0.1, 10.20.0.1, 10.30.0.1'),(3,'ROUT_02','<p>This is the second router</p>',NULL,'2021-09-21 13:52:16','2021-09-21 15:01:18',NULL,'10.30.1.1, 10.40.1.1');
/*!40000 ALTER TABLE "routers" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "security_devices"
--

LOCK TABLES "security_devices" WRITE;
/*!40000 ALTER TABLE "security_devices" DISABLE KEYS */;
INSERT INTO "security_devices" ("id", "name", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'FW01','<p>Firewall proncipal</p>','2020-07-14 17:01:21','2020-07-14 17:01:21',NULL),(2,'FW02','<p>Backup Fireall</p>','2020-07-14 17:02:21','2020-07-14 17:02:21',NULL);
/*!40000 ALTER TABLE "security_devices" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "sites"
--

LOCK TABLES "sites" WRITE;
/*!40000 ALTER TABLE "sites" DISABLE KEYS */;
INSERT INTO "sites" ("id", "name", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'Site A','<p>Description du site A</p>','2020-06-21 04:36:41','2020-06-21 04:36:41',NULL),(2,'Site B','<p>Description du site B</p>','2020-06-21 04:36:53','2020-06-21 04:36:53',NULL),(3,'Site C','<p>Description du Site C</p>','2020-06-21 04:37:05','2020-06-21 04:37:05',NULL),(4,'Test1','<p>site de test</p>','2020-07-24 19:12:29','2020-07-24 19:12:56','2020-07-24 19:12:56'),(5,'testsite','<p>description here</p>','2021-04-12 15:31:40','2021-04-12 15:32:04','2021-04-12 15:32:04'),(6,'Site Z',NULL,'2021-06-18 05:36:03','2021-10-19 16:51:22','2021-10-19 16:51:22'),(7,'Site 0',NULL,'2021-06-18 05:36:12','2021-08-17 17:52:52','2021-08-17 17:52:52');
/*!40000 ALTER TABLE "sites" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "storage_devices"
--

LOCK TABLES "storage_devices" WRITE;
/*!40000 ALTER TABLE "storage_devices" DISABLE KEYS */;
INSERT INTO "storage_devices" ("id", "name", "description", "created_at", "updated_at", "deleted_at", "site_id", "building_id", "bay_id", "physical_switch_id") VALUES (1,'DiskServer 1','<p>Description du serveur d stockage 1</p>','2020-06-21 15:30:16','2020-06-21 15:30:16',NULL,1,2,3,NULL),(2,'Oracle Server','<p>Main oracle server</p>','2020-06-21 15:33:51','2020-06-21 15:34:38',NULL,1,2,2,NULL);
/*!40000 ALTER TABLE "storage_devices" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "subnetworks"
--

LOCK TABLES "subnetworks" WRITE;
/*!40000 ALTER TABLE "subnetworks" DISABLE KEYS */;
INSERT INTO "subnetworks" ("id", "description", "address", "ip_allocation_type", "responsible_exp", "dmz", "wifi", "name", "created_at", "updated_at", "deleted_at", "connected_subnets_id", "gateway_id", "zone", "vlan_id", "network_id", "default_gateway") VALUES (1,'<p>Description du sous-réseau 1</p>','10.10.0.0 /16','Static','Marc','non','non','Subnet1','2020-06-23 12:35:41','2021-11-16 20:24:29',NULL,NULL,1,'ZONE_ACCUEIL',2,1,'10.10.0.1'),(2,'<p>Description du subnet 2</p>','10.20.0.0/16','Static','Henri','Oui','Oui','Subnet2','2020-07-04 07:35:10','2021-09-21 14:52:35',NULL,NULL,5,'ZONE_WORK',1,1,'10.20.0.1'),(3,'<p>Description du quatrième subnet</p>','10.40.0.0/16','Static','Jean','non','non','Subnet4','2020-11-06 12:56:33','2021-08-20 07:56:50',NULL,2,5,'ZONE_WORK',4,1,'10.40.0.1'),(4,'<p>descrption subnet 3</p>','8.8.8.8 /  255.255.255.0',NULL,NULL,NULL,NULL,'test subnet 3','2021-02-24 11:49:16','2021-02-24 11:49:33','2021-02-24 11:49:33',NULL,NULL,NULL,NULL,NULL,NULL),(5,'<p>Troisième sous-réseau</p>','10.30.0.0/16','Static','Jean','non','non','Subnet3','2021-05-19 14:48:39','2021-08-20 07:57:01',NULL,NULL,1,'ZONE_WORK',3,1,'10.30.0.1'),(6,'<p>Description du cinquième réseau</p>','10.50.0.0/16','Fixed','Jean','Oui','non','Subnet5','2021-08-17 11:35:28','2021-08-26 15:27:41',NULL,NULL,1,'ZONE_BACKUP',5,1,'10.50.0.1'),(7,'<p>Description du sixième sous-réseau</p>','10.60.0.0/16','Fixed','Jean','non','non','Subnet6','2021-08-17 16:32:47','2021-08-26 15:27:57',NULL,2,4,'ZONE_APP',6,2,'10.60.1.1'),(8,'<p>Test</p>',NULL,NULL,NULL,NULL,NULL,'Subnet7','2021-08-18 16:05:50','2021-08-18 16:10:19','2021-08-18 16:10:19',NULL,NULL,NULL,NULL,NULL,NULL),(9,'<p>Sous-réseau numéro sept</p>','10.70.0.0/16','Static','Jean','Oui','Oui','Subnet7','2021-08-18 16:11:10','2021-08-26 15:27:30',NULL,NULL,NULL,'ZONE_BACKUP',5,2,'10.70.0.1'),(10,'<p>Sous réseau démilitarisé</p>','10.70.0.0/32','Fixed','Jean','Oui','non','Subnet8','2021-08-18 16:33:48','2021-08-26 15:28:10',NULL,NULL,1,'ZONE_DMZ',7,1,'10.70.0.1'),(11,'<p>Description subnet 9</p>','10.90.0.0/32',NULL,'Jean','non','non','Subnet9','2021-09-07 16:41:02','2021-09-07 16:41:02',NULL,NULL,NULL,'ZONE_DATA',8,1,'10.90.1.1');
/*!40000 ALTER TABLE "subnetworks" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "tasks"
--

LOCK TABLES "tasks" WRITE;
/*!40000 ALTER TABLE "tasks" DISABLE KEYS */;
INSERT INTO "tasks" ("id", "nom", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'Tâche 2','Descriptionde la tâche 2','2020-06-13 00:04:07','2020-06-13 00:04:07',NULL),(2,'Tache 1','Description de la tâche 1','2020-06-13 00:04:21','2020-06-13 00:04:21',NULL),(3,'Tâche 3','Description de la tâche 3','2020-06-13 00:04:41','2020-06-13 00:04:41',NULL);
/*!40000 ALTER TABLE "tasks" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "vlans"
--

LOCK TABLES "vlans" WRITE;
/*!40000 ALTER TABLE "vlans" DISABLE KEYS */;
INSERT INTO "vlans" ("id", "name", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'VLAN_2','VLAN Wifi','2020-07-07 14:31:53','2020-07-07 14:39:10',NULL),(2,'VLAN_1','VLAN publc','2020-07-07 14:34:30','2020-07-07 14:38:53',NULL),(3,'VLAN_3','VLAN application','2020-07-07 14:38:41','2020-07-08 19:35:53',NULL),(4,'VLAN_4','Vlan Client','2020-07-08 19:34:11','2020-07-08 19:36:06',NULL),(5,'VLAN_5','Test Production','2020-07-11 17:12:03','2021-08-18 17:35:54',NULL),(6,'VLAN_6','VLAN démilitarisé','2020-07-11 17:14:55','2021-08-18 17:36:12',NULL),(7,'VLAN_7','Description du VLAN 7','2021-09-07 16:35:28','2021-09-07 16:35:28',NULL),(8,'VLAN_8','Description du VLAN 8','2021-09-07 16:36:20','2021-09-07 16:36:20',NULL);
/*!40000 ALTER TABLE "vlans" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "wans"
--

LOCK TABLES "wans" WRITE;
/*!40000 ALTER TABLE "wans" DISABLE KEYS */;
INSERT INTO "wans" ("id", "name", "created_at", "updated_at", "deleted_at") VALUES (1,'WAN01','2021-05-21 10:58:42','2021-05-21 10:58:42',NULL);
/*!40000 ALTER TABLE "wans" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "wifi_terminals"
--

LOCK TABLES "wifi_terminals" WRITE;
/*!40000 ALTER TABLE "wifi_terminals" DISABLE KEYS */;
INSERT INTO "wifi_terminals" ("id", "name", "description", "type", "created_at", "updated_at", "deleted_at", "site_id", "building_id") VALUES (1,'WIFI_01','<p>Borne wifi 01</p>','Alcatel 3500','2020-07-22 14:44:37','2020-07-22 14:44:37',NULL,1,2),(2,'WIFI_02','<p>Borne Wifi 2</p>','ALCALSYS 3001','2021-06-07 14:37:47','2021-06-07 14:37:47',NULL,2,1),(3,'WIFI_03','<p>Borne Wifi 3</p>','SYSTEL 3310','2021-06-07 14:42:29','2021-06-07 14:43:18',NULL,3,4);
/*!40000 ALTER TABLE "wifi_terminals" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "workstations"
--

LOCK TABLES "workstations" WRITE;
/*!40000 ALTER TABLE "workstations" DISABLE KEYS */;
INSERT INTO "workstations" ("id", "name", "description", "created_at", "updated_at", "deleted_at", "site_id", "building_id", "physical_switch_id", "type") VALUES (1,'Workstation 1','<p>Station de travail compta</p>','2020-06-21 15:09:04','2022-03-20 11:37:13',NULL,1,7,NULL,'ThinThink 460'),(2,'Workstation 2','<p>Station de travail accueil</p>','2020-06-21 15:09:54','2021-10-20 07:14:59',NULL,2,3,NULL,'ThinThink 410'),(3,'Workstation 3','<p>Station de travail back-office</p>','2020-06-21 15:17:57','2021-10-20 07:15:25',NULL,2,4,NULL,'ThinThink 420');
/*!40000 ALTER TABLE "workstations" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table "zone_admins"
--

LOCK TABLES "zone_admins" WRITE;
/*!40000 ALTER TABLE "zone_admins" DISABLE KEYS */;
INSERT INTO "zone_admins" ("id", "name", "description", "created_at", "updated_at", "deleted_at") VALUES (1,'Enreprise','<p>Zone d\'administration de l\'entreprise</p>','2020-07-03 07:49:03','2021-05-23 13:07:18',NULL);
/*!40000 ALTER TABLE "zone_admins" ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-17  4:48:52
