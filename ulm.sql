-- MySQL dump 10.13  Distrib 8.0.35, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: ultimateloanmanager
-- ------------------------------------------------------
-- Server version	8.0.35-0ubuntu0.22.04.1

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
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
                                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                `log_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                `subject_id` bigint unsigned DEFAULT NULL,
                                `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `event` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `causer_id` bigint unsigned DEFAULT NULL,
                                `causer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `properties` text COLLATE utf8mb4_unicode_ci,
                                `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`),
                                KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_depreciation`
--

DROP TABLE IF EXISTS `asset_depreciation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_depreciation` (
                                      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                      `asset_id` bigint unsigned DEFAULT NULL,
                                      `year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `beginning_value` decimal(65,2) DEFAULT NULL,
                                      `depreciation_value` decimal(65,2) DEFAULT NULL,
                                      `rate` decimal(65,2) DEFAULT NULL,
                                      `cost` decimal(65,2) DEFAULT NULL,
                                      `accumulated` decimal(65,2) DEFAULT NULL,
                                      `ending_value` decimal(65,2) DEFAULT NULL,
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_depreciation`
--

LOCK TABLES `asset_depreciation` WRITE;
/*!40000 ALTER TABLE `asset_depreciation` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_depreciation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_files`
--

DROP TABLE IF EXISTS `asset_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_files` (
                               `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                               `asset_id` bigint unsigned DEFAULT NULL,
                               `file` text COLLATE utf8mb4_unicode_ci,
                               `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `date` date DEFAULT NULL,
                               `notes` text COLLATE utf8mb4_unicode_ci,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_files`
--

LOCK TABLES `asset_files` WRITE;
/*!40000 ALTER TABLE `asset_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_maintenance`
--

DROP TABLE IF EXISTS `asset_maintenance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_maintenance` (
                                     `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                     `created_by_id` bigint unsigned DEFAULT NULL,
                                     `asset_maintenance_type_id` bigint unsigned DEFAULT NULL,
                                     `asset_id` bigint unsigned DEFAULT NULL,
                                     `performed_by` text COLLATE utf8mb4_unicode_ci,
                                     `date` date DEFAULT NULL,
                                     `amount` decimal(65,2) DEFAULT NULL,
                                     `mileage` decimal(65,2) DEFAULT NULL,
                                     `record_expense` tinyint NOT NULL DEFAULT '0',
                                     `notes` text COLLATE utf8mb4_unicode_ci,
                                     `created_at` timestamp NULL DEFAULT NULL,
                                     `updated_at` timestamp NULL DEFAULT NULL,
                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_maintenance`
--

LOCK TABLES `asset_maintenance` WRITE;
/*!40000 ALTER TABLE `asset_maintenance` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_maintenance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_maintenance_types`
--

DROP TABLE IF EXISTS `asset_maintenance_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_maintenance_types` (
                                           `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                           `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                           `notes` text COLLATE utf8mb4_unicode_ci,
                                           PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_maintenance_types`
--

LOCK TABLES `asset_maintenance_types` WRITE;
/*!40000 ALTER TABLE `asset_maintenance_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_maintenance_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_notes`
--

DROP TABLE IF EXISTS `asset_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_notes` (
                               `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                               `created_by_id` int unsigned DEFAULT NULL,
                               `asset_id` bigint unsigned DEFAULT NULL,
                               `attachment` text COLLATE utf8mb4_unicode_ci,
                               `notes` text COLLATE utf8mb4_unicode_ci,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_notes`
--

LOCK TABLES `asset_notes` WRITE;
/*!40000 ALTER TABLE `asset_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_pictures`
--

DROP TABLE IF EXISTS `asset_pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_pictures` (
                                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                  `asset_id` bigint unsigned DEFAULT NULL,
                                  `created_by_id` bigint unsigned DEFAULT NULL,
                                  `primary_picture` tinyint NOT NULL DEFAULT '0',
                                  `picture` text COLLATE utf8mb4_unicode_ci,
                                  `date_taken` date DEFAULT NULL,
                                  `notes` text COLLATE utf8mb4_unicode_ci,
                                  `created_at` timestamp NULL DEFAULT NULL,
                                  `updated_at` timestamp NULL DEFAULT NULL,
                                  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_pictures`
--

LOCK TABLES `asset_pictures` WRITE;
/*!40000 ALTER TABLE `asset_pictures` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_pictures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_types`
--

DROP TABLE IF EXISTS `asset_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asset_types` (
                               `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                               `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `type` enum('current','fixed','intangible','investment','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `chart_of_account_fixed_asset_id` int DEFAULT NULL,
                               `chart_of_account_asset_id` int DEFAULT NULL,
                               `chart_of_account_contra_asset_id` int DEFAULT NULL,
                               `chart_of_account_expense_id` int DEFAULT NULL,
                               `chart_of_account_liability_id` int DEFAULT NULL,
                               `chart_of_account_income_id` int DEFAULT NULL,
                               `notes` text COLLATE utf8mb4_unicode_ci,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_types`
--

LOCK TABLES `asset_types` WRITE;
/*!40000 ALTER TABLE `asset_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assets` (
                          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                          `created_by_id` bigint unsigned DEFAULT NULL,
                          `asset_type_id` bigint unsigned DEFAULT NULL,
                          `branch_id` bigint unsigned DEFAULT NULL,
                          `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `purchase_date` date DEFAULT NULL,
                          `purchase_price` decimal(65,2) DEFAULT NULL,
                          `replacement_value` decimal(65,2) DEFAULT NULL,
                          `value` decimal(65,2) DEFAULT NULL,
                          `life_span` int DEFAULT NULL,
                          `salvage_value` decimal(65,2) DEFAULT NULL,
                          `serial_number` text COLLATE utf8mb4_unicode_ci,
                          `bought_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `purchase_year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `notes` text COLLATE utf8mb4_unicode_ci,
                          `status` enum('active','inactive','sold','damaged','written_off') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `active` tinyint DEFAULT '0',
                          `created_at` timestamp NULL DEFAULT NULL,
                          `updated_at` timestamp NULL DEFAULT NULL,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assets`
--

LOCK TABLES `assets` WRITE;
/*!40000 ALTER TABLE `assets` DISABLE KEYS */;
/*!40000 ALTER TABLE `assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branch_users`
--

DROP TABLE IF EXISTS `branch_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branch_users` (
                                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                `created_by_id` int DEFAULT NULL,
                                `user_id` int NOT NULL,
                                `branch_id` int NOT NULL,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch_users`
--

LOCK TABLES `branch_users` WRITE;
/*!40000 ALTER TABLE `branch_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `branch_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
                            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                            `created_by_id` int DEFAULT NULL,
                            `parent_id` int DEFAULT NULL,
                            `manager_id` int DEFAULT NULL,
                            `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `open_date` date DEFAULT NULL,
                            `notes` text COLLATE utf8mb4_unicode_ci,
                            `active` tinyint NOT NULL DEFAULT '1',
                            `is_system` tinyint NOT NULL DEFAULT '0',
                            `created_at` timestamp NULL DEFAULT NULL,
                            `updated_at` timestamp NULL DEFAULT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (1,NULL,NULL,NULL,'Default','2023-11-08',NULL,1,1,'2023-11-08 14:34:37','2023-11-08 14:34:37');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chart_of_accounts`
--

DROP TABLE IF EXISTS `chart_of_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chart_of_accounts` (
                                     `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                     `parent_id` int DEFAULT NULL,
                                     `name` text COLLATE utf8mb4_unicode_ci,
                                     `gl_code` int DEFAULT NULL,
                                     `account_type` enum('asset','expense','equity','liability','income') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'asset',
                                     `allow_manual` tinyint NOT NULL DEFAULT '0',
                                     `active` tinyint NOT NULL DEFAULT '1',
                                     `notes` text COLLATE utf8mb4_unicode_ci,
                                     `created_at` timestamp NULL DEFAULT NULL,
                                     `updated_at` timestamp NULL DEFAULT NULL,
                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chart_of_accounts`
--

LOCK TABLES `chart_of_accounts` WRITE;
/*!40000 ALTER TABLE `chart_of_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `chart_of_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_file_types`
--

DROP TABLE IF EXISTS `client_file_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_file_types` (
                                     `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                     `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `created_at` timestamp NULL DEFAULT NULL,
                                     `updated_at` timestamp NULL DEFAULT NULL,
                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_file_types`
--

LOCK TABLES `client_file_types` WRITE;
/*!40000 ALTER TABLE `client_file_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_file_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_files`
--

DROP TABLE IF EXISTS `client_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_files` (
                                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                `created_by_id` bigint unsigned DEFAULT NULL,
                                `client_id` bigint unsigned DEFAULT NULL,
                                `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `description` text COLLATE utf8mb4_unicode_ci,
                                `size` int DEFAULT NULL,
                                `link` text COLLATE utf8mb4_unicode_ci,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                `client_file_type_id` bigint unsigned DEFAULT NULL,
                                `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_files`
--

LOCK TABLES `client_files` WRITE;
/*!40000 ALTER TABLE `client_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_identification`
--

DROP TABLE IF EXISTS `client_identification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_identification` (
                                         `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                         `created_by_id` bigint unsigned DEFAULT NULL,
                                         `client_id` bigint unsigned DEFAULT NULL,
                                         `client_identification_type_id` bigint unsigned DEFAULT NULL,
                                         `identification_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                         `description` text COLLATE utf8mb4_unicode_ci,
                                         `size` int DEFAULT NULL,
                                         `link` text COLLATE utf8mb4_unicode_ci,
                                         `created_at` timestamp NULL DEFAULT NULL,
                                         `updated_at` timestamp NULL DEFAULT NULL,
                                         `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_identification`
--

LOCK TABLES `client_identification` WRITE;
/*!40000 ALTER TABLE `client_identification` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_identification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_identification_types`
--

DROP TABLE IF EXISTS `client_identification_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_identification_types` (
                                               `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                               `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_identification_types`
--

LOCK TABLES `client_identification_types` WRITE;
/*!40000 ALTER TABLE `client_identification_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_identification_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_next_of_kin`
--

DROP TABLE IF EXISTS `client_next_of_kin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_next_of_kin` (
                                      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                      `created_by_id` bigint unsigned DEFAULT NULL,
                                      `client_id` bigint unsigned DEFAULT NULL,
                                      `client_relationship_id` bigint unsigned DEFAULT NULL,
                                      `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `middle_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `gender` enum('male','female','other','unspecified') COLLATE utf8mb4_unicode_ci DEFAULT 'unspecified',
                                      `marital_status` enum('married','single','divorced','widowed','unspecified','other') COLLATE utf8mb4_unicode_ci DEFAULT 'unspecified',
                                      `country_id` bigint unsigned DEFAULT NULL,
                                      `title_id` bigint unsigned DEFAULT NULL,
                                      `profession_id` bigint unsigned DEFAULT NULL,
                                      `mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `dob` date DEFAULT NULL,
                                      `address` text COLLATE utf8mb4_unicode_ci,
                                      `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `zip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `employer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `notes` text COLLATE utf8mb4_unicode_ci,
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_next_of_kin`
--

LOCK TABLES `client_next_of_kin` WRITE;
/*!40000 ALTER TABLE `client_next_of_kin` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_next_of_kin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_relationships`
--

DROP TABLE IF EXISTS `client_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_relationships` (
                                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                        `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_relationships`
--

LOCK TABLES `client_relationships` WRITE;
/*!40000 ALTER TABLE `client_relationships` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_types`
--

DROP TABLE IF EXISTS `client_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_types` (
                                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_types`
--

LOCK TABLES `client_types` WRITE;
/*!40000 ALTER TABLE `client_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_users`
--

DROP TABLE IF EXISTS `client_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_users` (
                                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                `created_by_id` bigint unsigned DEFAULT NULL,
                                `client_id` bigint unsigned DEFAULT NULL,
                                `user_id` bigint unsigned DEFAULT NULL,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_users`
--

LOCK TABLES `client_users` WRITE;
/*!40000 ALTER TABLE `client_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
                           `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                           `created_by_id` bigint unsigned DEFAULT NULL,
                           `branch_id` bigint unsigned DEFAULT NULL,
                           `loan_officer_id` bigint unsigned DEFAULT NULL,
                           `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `middle_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `gender` enum('male','female','other','unspecified') COLLATE utf8mb4_unicode_ci DEFAULT 'unspecified',
                           `status` enum('pending','active','inactive','deceased','unspecified','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                           `marital_status` enum('married','single','divorced','widowed','unspecified','other') COLLATE utf8mb4_unicode_ci DEFAULT 'unspecified',
                           `country_id` bigint unsigned DEFAULT NULL,
                           `title_id` bigint unsigned DEFAULT NULL,
                           `profession_id` bigint unsigned DEFAULT NULL,
                           `client_type_id` bigint unsigned DEFAULT NULL,
                           `mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `external_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `dob` date DEFAULT NULL,
                           `address` text COLLATE utf8mb4_unicode_ci,
                           `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `zip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `employer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `notes` text COLLATE utf8mb4_unicode_ci,
                           `signature` text COLLATE utf8mb4_unicode_ci,
                           `created_date` date DEFAULT NULL,
                           `joined_date` date DEFAULT NULL,
                           `activation_date` date DEFAULT NULL,
                           `created_at` timestamp NULL DEFAULT NULL,
                           `updated_at` timestamp NULL DEFAULT NULL,
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `communication_campaign_attachment_types`
--

DROP TABLE IF EXISTS `communication_campaign_attachment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `communication_campaign_attachment_types` (
                                                           `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                                           `name` text COLLATE utf8mb4_unicode_ci,
                                                           `translated_name` text COLLATE utf8mb4_unicode_ci,
                                                           `is_trigger` tinyint NOT NULL DEFAULT '0',
                                                           `active` tinyint NOT NULL DEFAULT '1',
                                                           PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `communication_campaign_attachment_types`
--

LOCK TABLES `communication_campaign_attachment_types` WRITE;
/*!40000 ALTER TABLE `communication_campaign_attachment_types` DISABLE KEYS */;
INSERT INTO `communication_campaign_attachment_types` VALUES (1,'Loan Schedule',NULL,0,1),(2,'Client Statement',NULL,0,1),(3,'Saving Mini Statement',NULL,0,0);
/*!40000 ALTER TABLE `communication_campaign_attachment_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `communication_campaign_business_rules`
--

DROP TABLE IF EXISTS `communication_campaign_business_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `communication_campaign_business_rules` (
                                                         `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                                         `name` text COLLATE utf8mb4_unicode_ci,
                                                         `translated_name` text COLLATE utf8mb4_unicode_ci,
                                                         `description` text COLLATE utf8mb4_unicode_ci,
                                                         `active` tinyint NOT NULL DEFAULT '1',
                                                         `is_trigger` tinyint NOT NULL DEFAULT '0',
                                                         PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `communication_campaign_business_rules`
--

LOCK TABLES `communication_campaign_business_rules` WRITE;
/*!40000 ALTER TABLE `communication_campaign_business_rules` DISABLE KEYS */;
INSERT INTO `communication_campaign_business_rules` VALUES (1,'Active Clients',NULL,'All clients with the status ‘Active’',1,0),(2,'Prospective Clients',NULL,'All clients with the status ‘Active’ who have never had a loan before',1,0),(3,'Active Loan Clients',NULL,'All clients with an outstanding loan',1,0),(4,'Loans in arrears',NULL,'All clients with an outstanding loan in arrears between X and Y days',1,0),(5,'Loans disbursed to clients',NULL,'All clients who have had a loan disbursed to them in the last X to Y days',1,0),(6,'Loan payments due',NULL,'All clients with an unpaid installment due on their loan between X and Y days',1,0),(7,'Dormant Prospects',NULL,'All individuals who have not yet received a loan but were also entered into the system more than 3 months',0,0),(8,'Loan Payments Due (Overdue Loans)',NULL,'Loan Payments Due between X to Y days for clients in arrears between X and Y days',0,0),(9,'Loan Payments Received (Active Loans)',NULL,'Payments received in the last X to Y days for any loan with the status Active (on-time)',0,0),(10,'Loan Payments Received (Overdue Loans) ',NULL,'Payments received in the last X to Y days for any loan with the status Overdue (arrears) between X and Y days',0,0),(11,'Happy Birthday',NULL,'This sends a message to all clients with the status Active on their Birthday',0,0),(12,'Loan Fully Repaid',NULL,'All loans that have been fully repaid (Closed or Overpaid) in the last X to Y days',0,0),(13,'Loans Outstanding after final instalment date',NULL,'All active loans (with an outstanding balance) between X to Y days after the final instalment date on their loan schedule',0,0),(14,'Past Loan Clients',NULL,'Past Loan Clients who have previously had a loan but do not currently have one and finished repaying their most recent loan in the last X to Y days.',0,0),(15,'Loan Submitted',NULL,'Loan and client data of submitted loan',1,1),(16,'Loan Rejected',NULL,'Loan and client data of rejected loan',1,1),(17,'Loan Approved',NULL,'Loan and client data of approved loan',1,1),(18,'Loan Disbursed',NULL,'Loan Disbursed',1,1),(19,'Loan Rescheduled',NULL,'Loan Rescheduled',1,1),(20,'Loan Closed',NULL,'Loan Closed',1,1),(21,'Loan Repayment',NULL,'Loan Repayment',1,1),(22,'Savings Submitted',NULL,'Savings and client data of submitted savings',1,1),(23,'Savings Rejected',NULL,'Savings and client data of rejected savings',1,1),(24,'Savings Approved',NULL,'Savings and client data of approved savings',1,1),(25,'Savings Activated',NULL,'Savings Activated',1,1),(26,'Savings Dormant',NULL,'Savings Dormant',1,1),(27,'Savings Inactive',NULL,'Savings Inactive',1,1),(28,'Savings Closed',NULL,'Savings Closed',1,1),(29,'Savings Deposit',NULL,'Savings Deposit',1,1),(30,'Savings Withdrawal',NULL,'Savings Withdrawal',1,1);
/*!40000 ALTER TABLE `communication_campaign_business_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `communication_campaign_logs`
--

DROP TABLE IF EXISTS `communication_campaign_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `communication_campaign_logs` (
                                               `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                               `created_by_id` bigint unsigned DEFAULT NULL,
                                               `client_id` bigint unsigned DEFAULT NULL,
                                               `sms_gateway_id` bigint DEFAULT NULL,
                                               `communication_campaign_id` bigint DEFAULT NULL,
                                               `campaign_type` enum('sms','email') COLLATE utf8mb4_unicode_ci NOT NULL,
                                               `description` text COLLATE utf8mb4_unicode_ci,
                                               `send_to` text COLLATE utf8mb4_unicode_ci,
                                               `campaign_name` text COLLATE utf8mb4_unicode_ci,
                                               `status` enum('pending','sent','delivered','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                                               `created_at` timestamp NULL DEFAULT NULL,
                                               `updated_at` timestamp NULL DEFAULT NULL,
                                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `communication_campaign_logs`
--

LOCK TABLES `communication_campaign_logs` WRITE;
/*!40000 ALTER TABLE `communication_campaign_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `communication_campaign_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `communication_campaigns`
--

DROP TABLE IF EXISTS `communication_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `communication_campaigns` (
                                           `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                           `created_by_id` bigint unsigned DEFAULT NULL,
                                           `sms_gateway_id` bigint unsigned DEFAULT NULL,
                                           `communication_campaign_business_rule_id` bigint unsigned DEFAULT NULL,
                                           `communication_campaign_attachment_type_id` bigint unsigned DEFAULT NULL,
                                           `branch_id` bigint unsigned DEFAULT NULL,
                                           `loan_officer_id` bigint unsigned DEFAULT NULL,
                                           `loan_product_id` bigint unsigned DEFAULT NULL,
                                           `subject` text COLLATE utf8mb4_unicode_ci,
                                           `name` text COLLATE utf8mb4_unicode_ci,
                                           `campaign_type` enum('sms','email') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email',
                                           `trigger_type` enum('direct','schedule','triggered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'direct',
                                           `scheduled_date` date DEFAULT NULL,
                                           `scheduled_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                           `schedule_frequency` int DEFAULT NULL,
                                           `schedule_frequency_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'days',
                                           `scheduled_next_run_date` date DEFAULT NULL,
                                           `scheduled_last_run_date` date DEFAULT NULL,
                                           `from_x` int DEFAULT NULL,
                                           `to_y` int DEFAULT NULL,
                                           `cycle_x` int DEFAULT NULL,
                                           `cycle_y` int DEFAULT NULL,
                                           `overdue_x` int DEFAULT NULL,
                                           `overdue_y` int DEFAULT NULL,
                                           `active` tinyint NOT NULL DEFAULT '0',
                                           `status` enum('pending','active','inactive','closed','done') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                                           `description` text COLLATE utf8mb4_unicode_ci,
                                           `notes` text COLLATE utf8mb4_unicode_ci,
                                           `created_at` timestamp NULL DEFAULT NULL,
                                           `updated_at` timestamp NULL DEFAULT NULL,
                                           PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `communication_campaigns`
--

LOCK TABLES `communication_campaigns` WRITE;
/*!40000 ALTER TABLE `communication_campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `communication_campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
                             `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                             `sortname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                             `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                             `created_at` timestamp NULL DEFAULT NULL,
                             `updated_at` timestamp NULL DEFAULT NULL,
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'AF','Afghanistan',NULL,NULL),(2,'AL','Albania',NULL,NULL),(3,'DZ','Algeria',NULL,NULL),(4,'AS','American Samoa',NULL,NULL),(5,'AD','Andorra',NULL,NULL),(6,'AO','Angola',NULL,NULL),(7,'AI','Anguilla',NULL,NULL),(8,'AQ','Antarctica',NULL,NULL),(9,'AG','Antigua And Barbuda',NULL,NULL),(10,'AR','Argentina',NULL,NULL),(11,'AM','Armenia',NULL,NULL),(12,'AW','Aruba',NULL,NULL),(13,'AU','Australia',NULL,NULL),(14,'AT','Austria',NULL,NULL),(15,'AZ','Azerbaijan',NULL,NULL),(16,'BS','Bahamas The',NULL,NULL),(17,'BH','Bahrain',NULL,NULL),(18,'BD','Bangladesh',NULL,NULL),(19,'BB','Barbados',NULL,NULL),(20,'BY','Belarus',NULL,NULL),(21,'BE','Belgium',NULL,NULL),(22,'BZ','Belize',NULL,NULL),(23,'BJ','Benin',NULL,NULL),(24,'BM','Bermuda',NULL,NULL),(25,'BT','Bhutan',NULL,NULL),(26,'BO','Bolivia',NULL,NULL),(27,'BA','Bosnia and Herzegovina',NULL,NULL),(28,'BW','Botswana',NULL,NULL),(29,'BV','Bouvet Island',NULL,NULL),(30,'BR','Brazil',NULL,NULL),(31,'IO','British Indian Ocean Territory',NULL,NULL),(32,'BN','Brunei',NULL,NULL),(33,'BG','Bulgaria',NULL,NULL),(34,'BF','Burkina Faso',NULL,NULL),(35,'BI','Burundi',NULL,NULL),(36,'KH','Cambodia',NULL,NULL),(37,'CM','Cameroon',NULL,NULL),(38,'CA','Canada',NULL,NULL),(39,'CV','Cape Verde',NULL,NULL),(40,'KY','Cayman Islands',NULL,NULL),(41,'CF','Central African Republic',NULL,NULL),(42,'TD','Chad',NULL,NULL),(43,'CL','Chile',NULL,NULL),(44,'CN','China',NULL,NULL),(45,'CX','Christmas Island',NULL,NULL),(46,'CC','Cocos (Keeling) Islands',NULL,NULL),(47,'CO','Colombia',NULL,NULL),(48,'KM','Comoros',NULL,NULL),(49,'CG','Congo',NULL,NULL),(50,'CD','Congo The Democratic Republic Of The',NULL,NULL),(51,'CK','Cook Islands',NULL,NULL),(52,'CR','Costa Rica',NULL,NULL),(53,'CI','Cote D\'Ivoire (Ivory Coast)',NULL,NULL),(54,'HR','Croatia (Hrvatska)',NULL,NULL),(55,'CU','Cuba',NULL,NULL),(56,'CY','Cyprus',NULL,NULL),(57,'CZ','Czech Republic',NULL,NULL),(58,'DK','Denmark',NULL,NULL),(59,'DJ','Djibouti',NULL,NULL),(60,'DM','Dominica',NULL,NULL),(61,'DO','Dominican Republic',NULL,NULL),(62,'TP','East Timor',NULL,NULL),(63,'EC','Ecuador',NULL,NULL),(64,'EG','Egypt',NULL,NULL),(65,'SV','El Salvador',NULL,NULL),(66,'GQ','Equatorial Guinea',NULL,NULL),(67,'ER','Eritrea',NULL,NULL),(68,'EE','Estonia',NULL,NULL),(69,'ET','Ethiopia',NULL,NULL),(70,'XA','External Territories of Australia',NULL,NULL),(71,'FK','Falkland Islands',NULL,NULL),(72,'FO','Faroe Islands',NULL,NULL),(73,'FJ','Fiji Islands',NULL,NULL),(74,'FI','Finland',NULL,NULL),(75,'FR','France',NULL,NULL),(76,'GF','French Guiana',NULL,NULL),(77,'PF','French Polynesia',NULL,NULL),(78,'TF','French Southern Territories',NULL,NULL),(79,'GA','Gabon',NULL,NULL),(80,'GM','Gambia The',NULL,NULL),(81,'GE','Georgia',NULL,NULL),(82,'DE','Germany',NULL,NULL),(83,'GH','Ghana',NULL,NULL),(84,'GI','Gibraltar',NULL,NULL),(85,'GR','Greece',NULL,NULL),(86,'GL','Greenland',NULL,NULL),(87,'GD','Grenada',NULL,NULL),(88,'GP','Guadeloupe',NULL,NULL),(89,'GU','Guam',NULL,NULL),(90,'GT','Guatemala',NULL,NULL),(91,'XU','Guernsey and Alderney',NULL,NULL),(92,'GN','Guinea',NULL,NULL),(93,'GW','Guinea-Bissau',NULL,NULL),(94,'GY','Guyana',NULL,NULL),(95,'HT','Haiti',NULL,NULL),(96,'HM','Heard and McDonald Islands',NULL,NULL),(97,'HN','Honduras',NULL,NULL),(98,'HK','Hong Kong S.A.R.',NULL,NULL),(99,'HU','Hungary',NULL,NULL),(100,'IS','Iceland',NULL,NULL),(101,'IN','India',NULL,NULL),(102,'ID','Indonesia',NULL,NULL),(103,'IR','Iran',NULL,NULL),(104,'IQ','Iraq',NULL,NULL),(105,'IE','Ireland',NULL,NULL),(106,'IL','Israel',NULL,NULL),(107,'IT','Italy',NULL,NULL),(108,'JM','Jamaica',NULL,NULL),(109,'JP','Japan',NULL,NULL),(110,'XJ','Jersey',NULL,NULL),(111,'JO','Jordan',NULL,NULL),(112,'KZ','Kazakhstan',NULL,NULL),(113,'KE','Kenya',NULL,NULL),(114,'KI','Kiribati',NULL,NULL),(115,'KP','Korea North',NULL,NULL),(116,'KR','Korea South',NULL,NULL),(117,'KW','Kuwait',NULL,NULL),(118,'KG','Kyrgyzstan',NULL,NULL),(119,'LA','Laos',NULL,NULL),(120,'LV','Latvia',NULL,NULL),(121,'LB','Lebanon',NULL,NULL),(122,'LS','Lesotho',NULL,NULL),(123,'LR','Liberia',NULL,NULL),(124,'LY','Libya',NULL,NULL),(125,'LI','Liechtenstein',NULL,NULL),(126,'LT','Lithuania',NULL,NULL),(127,'LU','Luxembourg',NULL,NULL),(128,'MO','Macau S.A.R.',NULL,NULL),(129,'MK','Macedonia',NULL,NULL),(130,'MG','Madagascar',NULL,NULL),(131,'MW','Malawi',NULL,NULL),(132,'MY','Malaysia',NULL,NULL),(133,'MV','Maldives',NULL,NULL),(134,'ML','Mali',NULL,NULL),(135,'MT','Malta',NULL,NULL),(136,'XM','Man (Isle of)',NULL,NULL),(137,'MH','Marshall Islands',NULL,NULL),(138,'MQ','Martinique',NULL,NULL),(139,'MR','Mauritania',NULL,NULL),(140,'MU','Mauritius',NULL,NULL),(141,'YT','Mayotte',NULL,NULL),(142,'MX','Mexico',NULL,NULL),(143,'FM','Micronesia',NULL,NULL),(144,'MD','Moldova',NULL,NULL),(145,'MC','Monaco',NULL,NULL),(146,'MN','Mongolia',NULL,NULL),(147,'MS','Montserrat',NULL,NULL),(148,'MA','Morocco',NULL,NULL),(149,'MZ','Mozambique',NULL,NULL),(150,'MM','Myanmar',NULL,NULL),(151,'NA','Namibia',NULL,NULL),(152,'NR','Nauru',NULL,NULL),(153,'NP','Nepal',NULL,NULL),(154,'AN','Netherlands Antilles',NULL,NULL),(155,'NL','Netherlands The',NULL,NULL),(156,'NC','New Caledonia',NULL,NULL),(157,'NZ','New Zealand',NULL,NULL),(158,'NI','Nicaragua',NULL,NULL),(159,'NE','Niger',NULL,NULL),(160,'NG','Nigeria',NULL,NULL),(161,'NU','Niue',NULL,NULL),(162,'NF','Norfolk Island',NULL,NULL),(163,'MP','Northern Mariana Islands',NULL,NULL),(164,'NO','Norway',NULL,NULL),(165,'OM','Oman',NULL,NULL),(166,'PK','Pakistan',NULL,NULL),(167,'PW','Palau',NULL,NULL),(168,'PS','Palestinian Territory Occupied',NULL,NULL),(169,'PA','Panama',NULL,NULL),(170,'PG','Papua new Guinea',NULL,NULL),(171,'PY','Paraguay',NULL,NULL),(172,'PE','Peru',NULL,NULL),(173,'PH','Philippines',NULL,NULL),(174,'PN','Pitcairn Island',NULL,NULL),(175,'PL','Poland',NULL,NULL),(176,'PT','Portugal',NULL,NULL),(177,'PR','Puerto Rico',NULL,NULL),(178,'QA','Qatar',NULL,NULL),(179,'RE','Reunion',NULL,NULL),(180,'RO','Romania',NULL,NULL),(181,'RU','Russia',NULL,NULL),(182,'RW','Rwanda',NULL,NULL),(183,'SH','Saint Helena',NULL,NULL),(184,'KN','Saint Kitts And Nevis',NULL,NULL),(185,'LC','Saint Lucia',NULL,NULL),(186,'PM','Saint Pierre and Miquelon',NULL,NULL),(187,'VC','Saint Vincent And The Grenadines',NULL,NULL),(188,'WS','Samoa',NULL,NULL),(189,'SM','San Marino',NULL,NULL),(190,'ST','Sao Tome and Principe',NULL,NULL),(191,'SA','Saudi Arabia',NULL,NULL),(192,'SN','Senegal',NULL,NULL),(193,'RS','Serbia',NULL,NULL),(194,'SC','Seychelles',NULL,NULL),(195,'SL','Sierra Leone',NULL,NULL),(196,'SG','Singapore',NULL,NULL),(197,'SK','Slovakia',NULL,NULL),(198,'SI','Slovenia',NULL,NULL),(199,'XG','Smaller Territories of the UK',NULL,NULL),(200,'SB','Solomon Islands',NULL,NULL),(201,'SO','Somalia',NULL,NULL),(202,'ZA','South Africa',NULL,NULL),(203,'GS','South Georgia',NULL,NULL),(204,'SS','South Sudan',NULL,NULL),(205,'ES','Spain',NULL,NULL),(206,'LK','Sri Lanka',NULL,NULL),(207,'SD','Sudan',NULL,NULL),(208,'SR','Suriname',NULL,NULL),(209,'SJ','Svalbard And Jan Mayen Islands',NULL,NULL),(210,'SZ','Swaziland',NULL,NULL),(211,'SE','Sweden',NULL,NULL),(212,'CH','Switzerland',NULL,NULL),(213,'SY','Syria',NULL,NULL),(214,'TW','Taiwan',NULL,NULL),(215,'TJ','Tajikistan',NULL,NULL),(216,'TZ','Tanzania',NULL,NULL),(217,'TH','Thailand',NULL,NULL),(218,'TG','Togo',NULL,NULL),(219,'TK','Tokelau',NULL,NULL),(220,'TO','Tonga',NULL,NULL),(221,'TT','Trinidad And Tobago',NULL,NULL),(222,'TN','Tunisia',NULL,NULL),(223,'TR','Turkey',NULL,NULL),(224,'TM','Turkmenistan',NULL,NULL),(225,'TC','Turks And Caicos Islands',NULL,NULL),(226,'TV','Tuvalu',NULL,NULL),(227,'UG','Uganda',NULL,NULL),(228,'UA','Ukraine',NULL,NULL),(229,'AE','United Arab Emirates',NULL,NULL),(230,'GB','United Kingdom',NULL,NULL),(231,'US','United States',NULL,NULL),(232,'UM','United States Minor Outlying Islands',NULL,NULL),(233,'UY','Uruguay',NULL,NULL),(234,'UZ','Uzbekistan',NULL,NULL),(235,'VU','Vanuatu',NULL,NULL),(236,'VA','Vatican City State (Holy See)',NULL,NULL),(237,'VE','Venezuela',NULL,NULL),(238,'VN','Vietnam',NULL,NULL),(239,'VG','Virgin Islands (British)',NULL,NULL),(240,'VI','Virgin Islands (US)',NULL,NULL),(241,'WF','Wallis And Futuna Islands',NULL,NULL),(242,'EH','Western Sahara',NULL,NULL),(243,'YE','Yemen',NULL,NULL),(244,'YU','Yugoslavia',NULL,NULL),(245,'ZM','Zambia',NULL,NULL),(246,'ZW','Zimbabwe',NULL,NULL);
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currencies` (
                              `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                              `created_by_id` int DEFAULT NULL,
                              `rate` decimal(65,8) DEFAULT NULL,
                              `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `symbol` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `position` enum('left','right') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
                              `created_at` timestamp NULL DEFAULT NULL,
                              `updated_at` timestamp NULL DEFAULT NULL,
                              PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (1,NULL,1.00000000,'USD','United States dollar','$','left',NULL,NULL);
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_fields`
--

DROP TABLE IF EXISTS `custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_fields` (
                                 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                 `created_by_id` bigint unsigned DEFAULT NULL,
                                 `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `name` text COLLATE utf8mb4_unicode_ci,
                                 `label` text COLLATE utf8mb4_unicode_ci,
                                 `options` text COLLATE utf8mb4_unicode_ci,
                                 `class` text COLLATE utf8mb4_unicode_ci,
                                 `db_columns` text COLLATE utf8mb4_unicode_ci,
                                 `rules` text COLLATE utf8mb4_unicode_ci,
                                 `default_values` text COLLATE utf8mb4_unicode_ci,
                                 `required` tinyint NOT NULL DEFAULT '0',
                                 `active` tinyint NOT NULL DEFAULT '1',
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL,
                                 `include_in_report` tinyint NOT NULL DEFAULT '0',
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_fields`
--

LOCK TABLES `custom_fields` WRITE;
/*!40000 ALTER TABLE `custom_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_fields_meta`
--

DROP TABLE IF EXISTS `custom_fields_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_fields_meta` (
                                      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                      `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `parent_id` int NOT NULL,
                                      `custom_field_id` bigint unsigned NOT NULL,
                                      `value` text COLLATE utf8mb4_unicode_ci,
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_fields_meta`
--

LOCK TABLES `custom_fields_meta` WRITE;
/*!40000 ALTER TABLE `custom_fields_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_fields_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_types`
--

DROP TABLE IF EXISTS `expense_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expense_types` (
                                 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                 `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `expense_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `asset_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL,
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_types`
--

LOCK TABLES `expense_types` WRITE;
/*!40000 ALTER TABLE `expense_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `expense_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
                            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                            `created_by_id` bigint unsigned DEFAULT NULL,
                            `expense_type_id` bigint unsigned DEFAULT NULL,
                            `expense_chart_of_account_id` bigint unsigned DEFAULT NULL,
                            `currency_id` bigint unsigned DEFAULT NULL,
                            `branch_id` bigint unsigned DEFAULT NULL,
                            `asset_chart_of_account_id` bigint unsigned DEFAULT NULL,
                            `amount` decimal(65,2) NOT NULL DEFAULT '0.00',
                            `date` date DEFAULT NULL,
                            `recurring` tinyint NOT NULL DEFAULT '0',
                            `recur_frequency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '31',
                            `recur_start_date` date DEFAULT NULL,
                            `recur_end_date` date DEFAULT NULL,
                            `recur_next_date` date DEFAULT NULL,
                            `recur_type` enum('day','week','month','year') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'month',
                            `notes` text COLLATE utf8mb4_unicode_ci,
                            `description` text COLLATE utf8mb4_unicode_ci,
                            `files` text COLLATE utf8mb4_unicode_ci,
                            `created_at` timestamp NULL DEFAULT NULL,
                            `updated_at` timestamp NULL DEFAULT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
                               `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                               `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
                               `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
                               `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                               `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                               `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `funds`
--

DROP TABLE IF EXISTS `funds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `funds` (
                         `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                         `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funds`
--

LOCK TABLES `funds` WRITE;
/*!40000 ALTER TABLE `funds` DISABLE KEYS */;
/*!40000 ALTER TABLE `funds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `income`
--

DROP TABLE IF EXISTS `income`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `income` (
                          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                          `created_by_id` bigint unsigned DEFAULT NULL,
                          `income_type_id` bigint unsigned DEFAULT NULL,
                          `currency_id` bigint unsigned DEFAULT NULL,
                          `branch_id` bigint unsigned DEFAULT NULL,
                          `income_chart_of_account_id` bigint unsigned DEFAULT NULL,
                          `asset_chart_of_account_id` bigint unsigned DEFAULT NULL,
                          `amount` decimal(65,2) NOT NULL DEFAULT '0.00',
                          `date` date DEFAULT NULL,
                          `recurring` tinyint NOT NULL DEFAULT '0',
                          `recur_frequency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '31',
                          `recur_start_date` date DEFAULT NULL,
                          `recur_end_date` date DEFAULT NULL,
                          `recur_next_date` date DEFAULT NULL,
                          `recur_type` enum('day','week','month','year') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'month',
                          `notes` text COLLATE utf8mb4_unicode_ci,
                          `description` text COLLATE utf8mb4_unicode_ci,
                          `files` text COLLATE utf8mb4_unicode_ci,
                          `created_at` timestamp NULL DEFAULT NULL,
                          `updated_at` timestamp NULL DEFAULT NULL,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `income`
--

LOCK TABLES `income` WRITE;
/*!40000 ALTER TABLE `income` DISABLE KEYS */;
/*!40000 ALTER TABLE `income` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `income_types`
--

DROP TABLE IF EXISTS `income_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `income_types` (
                                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                `income_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                `asset_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `income_types`
--

LOCK TABLES `income_types` WRITE;
/*!40000 ALTER TABLE `income_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `income_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                        `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                        `attempts` tinyint unsigned NOT NULL,
                        `reserved_at` int unsigned DEFAULT NULL,
                        `available_at` int unsigned NOT NULL,
                        `created_at` int unsigned NOT NULL,
                        PRIMARY KEY (`id`),
                        KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_entries`
--

DROP TABLE IF EXISTS `journal_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal_entries` (
                                   `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                   `created_by_id` bigint unsigned DEFAULT NULL,
                                   `transaction_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `payment_detail_id` bigint unsigned DEFAULT NULL,
                                   `branch_id` bigint unsigned DEFAULT NULL,
                                   `currency_id` bigint unsigned DEFAULT NULL,
                                   `chart_of_account_id` bigint unsigned DEFAULT NULL,
                                   `transaction_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `transaction_sub_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `name` text COLLATE utf8mb4_unicode_ci,
                                   `date` date DEFAULT NULL,
                                   `month` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `client_id` int DEFAULT NULL,
                                   `debit` decimal(65,4) DEFAULT NULL,
                                   `credit` decimal(65,4) DEFAULT NULL,
                                   `balance` decimal(65,4) DEFAULT NULL,
                                   `active` tinyint NOT NULL DEFAULT '1',
                                   `reversed` tinyint NOT NULL DEFAULT '0',
                                   `reversible` tinyint NOT NULL DEFAULT '1',
                                   `manual_entry` tinyint NOT NULL DEFAULT '0',
                                   `receipt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `notes` text COLLATE utf8mb4_unicode_ci,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL,
                                   PRIMARY KEY (`id`),
                                   KEY `branch_id_index` (`branch_id`),
                                   KEY `chart_of_account_id_index` (`chart_of_account_id`),
                                   KEY `currency_id_index` (`currency_id`),
                                   KEY `created_by_id_index` (`created_by_id`),
                                   KEY `client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_entries`
--

LOCK TABLES `journal_entries` WRITE;
/*!40000 ALTER TABLE `journal_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_applications`
--

DROP TABLE IF EXISTS `loan_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_applications` (
                                     `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                     `branch_id` bigint unsigned DEFAULT NULL,
                                     `created_by_id` bigint unsigned DEFAULT NULL,
                                     `client_id` bigint unsigned NOT NULL,
                                     `loan_id` bigint unsigned DEFAULT NULL,
                                     `loan_product_id` bigint unsigned NOT NULL,
                                     `amount` decimal(65,4) NOT NULL DEFAULT '0.0000',
                                     `status` enum('approved','pending','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                                     `notes` text COLLATE utf8mb4_unicode_ci,
                                     `created_at` timestamp NULL DEFAULT NULL,
                                     `updated_at` timestamp NULL DEFAULT NULL,
                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_applications`
--

LOCK TABLES `loan_applications` WRITE;
/*!40000 ALTER TABLE `loan_applications` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_charge_options`
--

DROP TABLE IF EXISTS `loan_charge_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_charge_options` (
                                       `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                       `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                       `translated_name` text COLLATE utf8mb4_unicode_ci,
                                       `active` tinyint NOT NULL DEFAULT '1',
                                       PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_charge_options`
--

LOCK TABLES `loan_charge_options` WRITE;
/*!40000 ALTER TABLE `loan_charge_options` DISABLE KEYS */;
INSERT INTO `loan_charge_options` VALUES (1,'Flat','Flat',1),(2,'Principal due on installment','Principal due on installment',1),(3,'Principal + Interest due on installment','Principal + Interest due on installment',1),(4,'Interest due on installment','Interest due on installment',1),(5,'Total Outstanding Loan Principal','Total Outstanding Loan Principal',1),(6,'Percentage of Original Loan Principal per Installment','Percentage of Original Loan Principal per Installment',1),(7,'Original Loan Principal','Original Loan Principal',1);
/*!40000 ALTER TABLE `loan_charge_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_charge_types`
--

DROP TABLE IF EXISTS `loan_charge_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_charge_types` (
                                     `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                     `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `translated_name` text COLLATE utf8mb4_unicode_ci,
                                     `active` tinyint NOT NULL DEFAULT '1',
                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_charge_types`
--

LOCK TABLES `loan_charge_types` WRITE;
/*!40000 ALTER TABLE `loan_charge_types` DISABLE KEYS */;
INSERT INTO `loan_charge_types` VALUES (1,'Disbursement','Disbursement',1),(2,'Specified Due Date','Specified Due Date',1),(3,'Installment Fees','Installment Fees',1),(4,'Overdue Installment Fee','Overdue Installment Fee',1),(5,'Disbursement - Paid With Repayment','Disbursement - Paid With Repayment',1),(6,'Loan Rescheduling Fee','Loan Rescheduling Fee',1),(7,'Overdue On Loan Maturity','Overdue On Loan Maturity',1),(8,'Last installment fee','Last installment fee',1);
/*!40000 ALTER TABLE `loan_charge_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_charges`
--

DROP TABLE IF EXISTS `loan_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_charges` (
                                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                `created_by_id` bigint unsigned DEFAULT NULL,
                                `currency_id` bigint unsigned NOT NULL,
                                `loan_charge_type_id` bigint unsigned NOT NULL,
                                `loan_charge_option_id` bigint unsigned NOT NULL,
                                `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                `amount` decimal(65,6) NOT NULL,
                                `min_amount` decimal(65,6) DEFAULT NULL,
                                `max_amount` decimal(65,6) DEFAULT NULL,
                                `payment_mode` enum('regular','account_transfer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
                                `schedule` tinyint DEFAULT '0',
                                `schedule_frequency` int DEFAULT NULL,
                                `schedule_frequency_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `is_penalty` tinyint DEFAULT '0',
                                `active` tinyint NOT NULL DEFAULT '0',
                                `allow_override` tinyint NOT NULL DEFAULT '0',
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_charges`
--

LOCK TABLES `loan_charges` WRITE;
/*!40000 ALTER TABLE `loan_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_collateral`
--

DROP TABLE IF EXISTS `loan_collateral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_collateral` (
                                   `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                   `created_by_id` bigint unsigned DEFAULT NULL,
                                   `loan_id` bigint unsigned NOT NULL,
                                   `loan_collateral_type_id` bigint unsigned NOT NULL,
                                   `description` text COLLATE utf8mb4_unicode_ci,
                                   `value` decimal(65,6) DEFAULT NULL,
                                   `link` text COLLATE utf8mb4_unicode_ci,
                                   `status` enum('active','repossessed','sold','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL,
                                   PRIMARY KEY (`id`),
                                   KEY `loan_collateral_loan_id_index` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_collateral`
--

LOCK TABLES `loan_collateral` WRITE;
/*!40000 ALTER TABLE `loan_collateral` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_collateral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_collateral_types`
--

DROP TABLE IF EXISTS `loan_collateral_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_collateral_types` (
                                         `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                         `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_collateral_types`
--

LOCK TABLES `loan_collateral_types` WRITE;
/*!40000 ALTER TABLE `loan_collateral_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_collateral_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_credit_checks`
--

DROP TABLE IF EXISTS `loan_credit_checks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_credit_checks` (
                                      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                      `created_by_id` bigint unsigned DEFAULT NULL,
                                      `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                      `translated_name` text COLLATE utf8mb4_unicode_ci,
                                      `security_level` enum('block','pass','warning') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'warning',
                                      `rating_type` enum('boolean','score') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'boolean',
                                      `pass_min_amount` decimal(65,6) DEFAULT NULL,
                                      `pass_max_amount` decimal(65,6) DEFAULT NULL,
                                      `warn_min_amount` decimal(65,6) DEFAULT NULL,
                                      `warn_max_amount` decimal(65,6) DEFAULT NULL,
                                      `fail_min_amount` decimal(65,6) DEFAULT NULL,
                                      `fail_max_amount` decimal(65,6) DEFAULT NULL,
                                      `general_error_msg` text COLLATE utf8mb4_unicode_ci,
                                      `user_friendly_error_msg` text COLLATE utf8mb4_unicode_ci,
                                      `general_warning_msg` text COLLATE utf8mb4_unicode_ci,
                                      `user_friendly_warning_msg` text COLLATE utf8mb4_unicode_ci,
                                      `general_success_msg` text COLLATE utf8mb4_unicode_ci,
                                      `user_friendly_success_msg` text COLLATE utf8mb4_unicode_ci,
                                      `active` tinyint NOT NULL DEFAULT '0',
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      KEY `loan_credit_checks_created_by_id_foreign` (`created_by_id`),
                                      CONSTRAINT `loan_credit_checks_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_credit_checks`
--

LOCK TABLES `loan_credit_checks` WRITE;
/*!40000 ALTER TABLE `loan_credit_checks` DISABLE KEYS */;
INSERT INTO `loan_credit_checks` VALUES (1,NULL,'Client Written-Off Loans Check','Client Written-Off Loans Check','block','boolean',NULL,NULL,NULL,NULL,NULL,NULL,'The client has one or more written-off loans','The client has one or more written-off loans','The client has one or more written-off loans','The client has one or more written-off loans','The client has one or more written-off loans','The client has one or more written-off loans',0,NULL,NULL),(2,NULL,'Same Product Outstanding','Same Product Outstanding','block','boolean',NULL,NULL,NULL,NULL,NULL,NULL,'The client has an active loan for the same product','The client has an active loan for the same product','The client has an active loan for the same product','The client has an active loan for the same product','The client has an active loan for the same product','The client has an active loan for the same product',0,NULL,NULL),(3,NULL,'Client Arrears','Client Arrears','block','boolean',NULL,NULL,NULL,NULL,NULL,NULL,'Client has arrears on existing loans','Client has arrears on existing loans','Client has arrears on existing loans','Client has arrears on existing loans','Client has arrears on existing loans','Client has arrears on existing loans',0,NULL,NULL),(4,NULL,'Outstanding Loan Balance','Outstanding Loan Balance','block','boolean',NULL,NULL,NULL,NULL,NULL,NULL,'Client has outstanding balance on existing loans','Client has outstanding balance on existing loans','Client has outstanding balance on existing loans','Client has outstanding balance on existing loans','Client has outstanding balance on existing loans','Client has outstanding balance on existing loans',0,NULL,NULL),(5,NULL,'Rejected and withdrawn loans','Rejected and withdrawn loans','block','boolean',NULL,NULL,NULL,NULL,NULL,NULL,'This client has had one or more rejected or withdrawn loans','This client has had one or more rejected or withdrawn loans','This client has had one or more rejected or withdrawn loans','This client has had one or more rejected or withdrawn loans','This client has had one or more rejected or withdrawn loans','This client has had one or more rejected or withdrawn loans',0,NULL,NULL),(6,NULL,'Total collateral items value','Total collateral items value','block','boolean',NULL,NULL,NULL,NULL,NULL,NULL,'The total value of collateral items for this loan is less than the principal loanamount','The total value of collateral items for this loan is less than the principal loanamount','The total value of collateral items for this loan is less than the principal loanamount','The total value of collateral items for this loan is less than the principal loanamount','The total value of collateral items for this loan is less than the principal loanamount','The total value of collateral items for this loan is less than the principal loanamount',0,NULL,NULL);
/*!40000 ALTER TABLE `loan_credit_checks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_disbursement_channels`
--

DROP TABLE IF EXISTS `loan_disbursement_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_disbursement_channels` (
                                              `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                              `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                              `is_system` tinyint NOT NULL DEFAULT '0',
                                              `created_at` timestamp NULL DEFAULT NULL,
                                              `updated_at` timestamp NULL DEFAULT NULL,
                                              PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_disbursement_channels`
--

LOCK TABLES `loan_disbursement_channels` WRITE;
/*!40000 ALTER TABLE `loan_disbursement_channels` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_disbursement_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_file_types`
--

DROP TABLE IF EXISTS `loan_file_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_file_types` (
                                   `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                   `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL,
                                   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_file_types`
--

LOCK TABLES `loan_file_types` WRITE;
/*!40000 ALTER TABLE `loan_file_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_file_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_files`
--

DROP TABLE IF EXISTS `loan_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_files` (
                              `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                              `created_by_id` bigint unsigned DEFAULT NULL,
                              `loan_id` bigint unsigned DEFAULT NULL,
                              `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `description` text COLLATE utf8mb4_unicode_ci,
                              `size` int DEFAULT NULL,
                              `link` text COLLATE utf8mb4_unicode_ci,
                              `created_at` timestamp NULL DEFAULT NULL,
                              `updated_at` timestamp NULL DEFAULT NULL,
                              `loan_file_type_id` bigint unsigned DEFAULT NULL,
                              `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                              PRIMARY KEY (`id`),
                              KEY `loan_files_loan_id_index` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_files`
--

LOCK TABLES `loan_files` WRITE;
/*!40000 ALTER TABLE `loan_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_guarantors`
--

DROP TABLE IF EXISTS `loan_guarantors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_guarantors` (
                                   `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                   `created_by_id` bigint unsigned DEFAULT NULL,
                                   `branch_id` bigint unsigned DEFAULT NULL,
                                   `loan_id` bigint unsigned DEFAULT NULL,
                                   `is_client` tinyint NOT NULL DEFAULT '0',
                                   `client_id` bigint unsigned DEFAULT NULL,
                                   `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `middle_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `gender` enum('male','female','other','unspecified') COLLATE utf8mb4_unicode_ci DEFAULT 'unspecified',
                                   `status` enum('pending','active','inactive','deceased','unspecified','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                                   `marital_status` enum('married','single','divorced','widowed','unspecified','other') COLLATE utf8mb4_unicode_ci DEFAULT 'unspecified',
                                   `country_id` bigint unsigned DEFAULT NULL,
                                   `title_id` bigint unsigned DEFAULT NULL,
                                   `profession_id` bigint unsigned DEFAULT NULL,
                                   `client_relationship_id` bigint unsigned DEFAULT NULL,
                                   `mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `dob` date DEFAULT NULL,
                                   `address` text COLLATE utf8mb4_unicode_ci,
                                   `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `zip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `employer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `notes` text COLLATE utf8mb4_unicode_ci,
                                   `created_date` date DEFAULT NULL,
                                   `joined_date` date DEFAULT NULL,
                                   `guaranteed_amount` decimal(65,6) DEFAULT NULL,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL,
                                   PRIMARY KEY (`id`),
                                   KEY `loan_guarantors_loan_id_index` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_guarantors`
--

LOCK TABLES `loan_guarantors` WRITE;
/*!40000 ALTER TABLE `loan_guarantors` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_guarantors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_history`
--

DROP TABLE IF EXISTS `loan_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_history` (
                                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                `loan_id` bigint unsigned NOT NULL,
                                `created_by_id` bigint unsigned NOT NULL,
                                `action` text COLLATE utf8mb4_unicode_ci,
                                `user` text COLLATE utf8mb4_unicode_ci,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`),
                                KEY `loan_history_loan_id_index` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_history`
--

LOCK TABLES `loan_history` WRITE;
/*!40000 ALTER TABLE `loan_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_linked_charges`
--

DROP TABLE IF EXISTS `loan_linked_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_linked_charges` (
                                       `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                       `loan_id` bigint unsigned NOT NULL,
                                       `loan_charge_id` bigint unsigned NOT NULL,
                                       `loan_charge_type_id` bigint unsigned DEFAULT NULL,
                                       `loan_charge_option_id` bigint unsigned DEFAULT NULL,
                                       `loan_transaction_id` bigint unsigned DEFAULT NULL,
                                       `name` text COLLATE utf8mb4_unicode_ci,
                                       `amount` decimal(65,6) NOT NULL,
                                       `calculated_amount` decimal(65,6) DEFAULT NULL,
                                       `amount_paid_derived` decimal(65,6) DEFAULT NULL,
                                       `amount_waived_derived` decimal(65,6) DEFAULT NULL,
                                       `amount_written_off_derived` decimal(65,6) DEFAULT NULL,
                                       `amount_outstanding_derived` decimal(65,6) DEFAULT NULL,
                                       `is_penalty` tinyint NOT NULL DEFAULT '0',
                                       `waived` tinyint NOT NULL DEFAULT '0',
                                       `is_paid` tinyint NOT NULL DEFAULT '0',
                                       `created_at` timestamp NULL DEFAULT NULL,
                                       `updated_at` timestamp NULL DEFAULT NULL,
                                       PRIMARY KEY (`id`),
                                       KEY `loan_linked_charges_loan_id_index` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_linked_charges`
--

LOCK TABLES `loan_linked_charges` WRITE;
/*!40000 ALTER TABLE `loan_linked_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_linked_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_linked_credit_checks`
--

DROP TABLE IF EXISTS `loan_linked_credit_checks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_linked_credit_checks` (
                                             `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                             `loan_id` bigint unsigned NOT NULL,
                                             `loan_credit_check_id` bigint unsigned NOT NULL,
                                             `created_at` timestamp NULL DEFAULT NULL,
                                             `updated_at` timestamp NULL DEFAULT NULL,
                                             PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_linked_credit_checks`
--

LOCK TABLES `loan_linked_credit_checks` WRITE;
/*!40000 ALTER TABLE `loan_linked_credit_checks` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_linked_credit_checks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_notes`
--

DROP TABLE IF EXISTS `loan_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_notes` (
                              `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                              `created_by_id` bigint unsigned DEFAULT NULL,
                              `loan_id` bigint unsigned DEFAULT NULL,
                              `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `description` text COLLATE utf8mb4_unicode_ci,
                              `created_at` timestamp NULL DEFAULT NULL,
                              `updated_at` timestamp NULL DEFAULT NULL,
                              PRIMARY KEY (`id`),
                              KEY `loan_notes_loan_id_index` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_notes`
--

LOCK TABLES `loan_notes` WRITE;
/*!40000 ALTER TABLE `loan_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_officer_history`
--

DROP TABLE IF EXISTS `loan_officer_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_officer_history` (
                                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                        `loan_id` bigint unsigned NOT NULL,
                                        `created_by_id` bigint unsigned NOT NULL,
                                        `loan_officer_id` bigint unsigned NOT NULL,
                                        `start_date` date DEFAULT NULL,
                                        `end_date` date DEFAULT NULL,
                                        `created_at` timestamp NULL DEFAULT NULL,
                                        `updated_at` timestamp NULL DEFAULT NULL,
                                        PRIMARY KEY (`id`),
                                        KEY `loan_officer_history_loan_id_index` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_officer_history`
--

LOCK TABLES `loan_officer_history` WRITE;
/*!40000 ALTER TABLE `loan_officer_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_officer_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_product_linked_charges`
--

DROP TABLE IF EXISTS `loan_product_linked_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_product_linked_charges` (
                                               `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                               `loan_product_id` bigint unsigned NOT NULL,
                                               `loan_charge_id` bigint unsigned NOT NULL,
                                               `created_at` timestamp NULL DEFAULT NULL,
                                               `updated_at` timestamp NULL DEFAULT NULL,
                                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_product_linked_charges`
--

LOCK TABLES `loan_product_linked_charges` WRITE;
/*!40000 ALTER TABLE `loan_product_linked_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_product_linked_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_product_linked_credit_checks`
--

DROP TABLE IF EXISTS `loan_product_linked_credit_checks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_product_linked_credit_checks` (
                                                     `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                                     `loan_product_id` bigint unsigned NOT NULL,
                                                     `loan_credit_check_id` bigint unsigned NOT NULL,
                                                     `check_order` int DEFAULT NULL,
                                                     `created_at` timestamp NULL DEFAULT NULL,
                                                     `updated_at` timestamp NULL DEFAULT NULL,
                                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_product_linked_credit_checks`
--

LOCK TABLES `loan_product_linked_credit_checks` WRITE;
/*!40000 ALTER TABLE `loan_product_linked_credit_checks` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_product_linked_credit_checks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_products`
--

DROP TABLE IF EXISTS `loan_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_products` (
                                 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                 `created_by_id` bigint unsigned DEFAULT NULL,
                                 `currency_id` bigint unsigned NOT NULL,
                                 `loan_disbursement_channel_id` bigint unsigned DEFAULT NULL,
                                 `loan_transaction_processing_strategy_id` bigint unsigned NOT NULL,
                                 `fund_id` bigint unsigned NOT NULL,
                                 `fund_source_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `loan_portfolio_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `interest_receivable_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `penalties_receivable_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `fees_receivable_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `fees_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `overpayments_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `suspended_income_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `income_from_interest_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `income_from_penalties_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `income_from_fees_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `income_from_recovery_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `losses_written_off_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `interest_written_off_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                 `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `short_name` text COLLATE utf8mb4_unicode_ci,
                                 `description` text COLLATE utf8mb4_unicode_ci,
                                 `decimals` int DEFAULT NULL,
                                 `instalment_multiple_of` int DEFAULT '1',
                                 `minimum_principal` decimal(65,6) NOT NULL,
                                 `default_principal` decimal(65,6) NOT NULL,
                                 `maximum_principal` decimal(65,6) NOT NULL,
                                 `minimum_loan_term` int NOT NULL,
                                 `default_loan_term` int NOT NULL,
                                 `maximum_loan_term` int NOT NULL,
                                 `repayment_frequency` int NOT NULL,
                                 `repayment_frequency_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `minimum_interest_rate` decimal(65,6) NOT NULL,
                                 `default_interest_rate` decimal(65,6) NOT NULL,
                                 `maximum_interest_rate` decimal(65,6) NOT NULL,
                                 `interest_rate_type` enum('day','week','month','year','principal') COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `enable_balloon_payments` tinyint NOT NULL DEFAULT '0',
                                 `allow_schedule_adjustments` tinyint NOT NULL DEFAULT '0',
                                 `grace_on_principal_paid` int NOT NULL DEFAULT '0',
                                 `grace_on_interest_paid` int NOT NULL DEFAULT '0',
                                 `grace_on_interest_charged` int NOT NULL DEFAULT '0',
                                 `allow_custom_grace_period` tinyint NOT NULL DEFAULT '0',
                                 `allow_topup` tinyint NOT NULL DEFAULT '0',
                                 `interest_methodology` enum('flat','declining_balance') COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `interest_recalculation` tinyint NOT NULL DEFAULT '0',
                                 `amortization_method` enum('equal_installments','equal_principal_payments') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `interest_calculation_period_type` enum('daily','same') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `days_in_year` enum('actual','360','365','364') COLLATE utf8mb4_unicode_ci DEFAULT 'actual',
                                 `days_in_month` enum('actual','30','31') COLLATE utf8mb4_unicode_ci DEFAULT 'actual',
                                 `include_in_loan_cycle` tinyint NOT NULL DEFAULT '0',
                                 `lock_guarantee_funds` tinyint NOT NULL DEFAULT '0',
                                 `auto_allocate_overpayments` tinyint NOT NULL DEFAULT '0',
                                 `allow_additional_charges` tinyint NOT NULL DEFAULT '0',
                                 `auto_disburse` tinyint NOT NULL DEFAULT '0',
                                 `require_linked_savings_account` tinyint NOT NULL DEFAULT '0',
                                 `min_amount` decimal(65,6) DEFAULT NULL,
                                 `max_amount` decimal(65,6) DEFAULT NULL,
                                 `accounting_rule` enum('none','cash','accrual_periodic','accrual_upfront') COLLATE utf8mb4_unicode_ci DEFAULT 'none',
                                 `npa_overdue_days` int NOT NULL DEFAULT '0',
                                 `npa_suspend_accrued_income` tinyint NOT NULL DEFAULT '0',
                                 `active` tinyint NOT NULL DEFAULT '0',
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL,
                                 `deduct_interest_from_principal` tinyint NOT NULL DEFAULT '0',
                                 `disallow_interest_rate_adjustment` tinyint NOT NULL DEFAULT '0',
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_products`
--

LOCK TABLES `loan_products` WRITE;
/*!40000 ALTER TABLE `loan_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_provisioning`
--

DROP TABLE IF EXISTS `loan_provisioning`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_provisioning` (
                                     `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                     `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `lower_limit` int DEFAULT NULL,
                                     `upper_limit` int DEFAULT NULL,
                                     `percentage` double(65,2) DEFAULT NULL,
                                     `description` text COLLATE utf8mb4_unicode_ci,
                                     `created_at` timestamp NULL DEFAULT NULL,
                                     `updated_at` timestamp NULL DEFAULT NULL,
                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_provisioning`
--

LOCK TABLES `loan_provisioning` WRITE;
/*!40000 ALTER TABLE `loan_provisioning` DISABLE KEYS */;
INSERT INTO `loan_provisioning` VALUES (1,'Current',0,89,0.00,NULL,NULL,NULL),(2,'Substandard',90,179,20.00,NULL,NULL,NULL),(3,'Doubtful',180,359,50.00,NULL,NULL,NULL),(4,'Loss',360,NULL,100.00,NULL,NULL,NULL);
/*!40000 ALTER TABLE `loan_provisioning` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_purposes`
--

DROP TABLE IF EXISTS `loan_purposes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_purposes` (
                                 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                 `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_purposes`
--

LOCK TABLES `loan_purposes` WRITE;
/*!40000 ALTER TABLE `loan_purposes` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_purposes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_repayment_schedules`
--

DROP TABLE IF EXISTS `loan_repayment_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_repayment_schedules` (
                                            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                            `created_by_id` bigint unsigned DEFAULT NULL,
                                            `loan_id` bigint unsigned DEFAULT NULL,
                                            `paid_by_date` date DEFAULT NULL,
                                            `from_date` date DEFAULT NULL,
                                            `due_date` date NOT NULL,
                                            `installment` int DEFAULT NULL,
                                            `principal` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `principal_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `principal_written_off_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `interest` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `interest_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `interest_written_off_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `interest_waived_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `fees` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `fees_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `fees_written_off_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `fees_waived_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `penalties` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `penalties_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `penalties_written_off_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `penalties_waived_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `total_due` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                            `month` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                            `year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                            `created_at` timestamp NULL DEFAULT NULL,
                                            `updated_at` timestamp NULL DEFAULT NULL,
                                            PRIMARY KEY (`id`),
                                            KEY `loan_repayment_schedules_loan_id_index` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_repayment_schedules`
--

LOCK TABLES `loan_repayment_schedules` WRITE;
/*!40000 ALTER TABLE `loan_repayment_schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_repayment_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_transaction_processing_strategies`
--

DROP TABLE IF EXISTS `loan_transaction_processing_strategies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_transaction_processing_strategies` (
                                                          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                                          `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                                          `translated_name` text COLLATE utf8mb4_unicode_ci,
                                                          `active` tinyint NOT NULL DEFAULT '1',
                                                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_transaction_processing_strategies`
--

LOCK TABLES `loan_transaction_processing_strategies` WRITE;
/*!40000 ALTER TABLE `loan_transaction_processing_strategies` DISABLE KEYS */;
INSERT INTO `loan_transaction_processing_strategies` VALUES (1,'Penalties, Fees, Interest, Principal order','Penalties, Fees, Interest, Principal order',1),(2,'Principal, Interest, Penalties, Fees Order','Principal, Interest, Penalties, Fees Order',1),(3,'Interest, Principal, Penalties, Fees Order','Interest, Principal, Penalties, Fees Order',1);
/*!40000 ALTER TABLE `loan_transaction_processing_strategies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_transaction_types`
--

DROP TABLE IF EXISTS `loan_transaction_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_transaction_types` (
                                          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                          `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `translated_name` text COLLATE utf8mb4_unicode_ci,
                                          `active` tinyint NOT NULL DEFAULT '1',
                                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_transaction_types`
--

LOCK TABLES `loan_transaction_types` WRITE;
/*!40000 ALTER TABLE `loan_transaction_types` DISABLE KEYS */;
INSERT INTO `loan_transaction_types` VALUES (1,'Disbursement','Disbursement',1),(2,'Repayment','Repayment',1),(3,'Contra','Contra',1),(4,'Waive Interest','Waive Interest',1),(5,'Repayment At Disbursement','Repayment At Disbursement',1),(6,'Write Off','Write Off',1),(7,'Marked for Rescheduling','Marked for Rescheduling',1),(8,'Recovery Repayment','Recovery Repayment',1),(9,'Waive Charges','Waive Charges',1),(10,'Apply Charges','Apply Charges',1),(11,'Apply Interest','Apply Interest',1);
/*!40000 ALTER TABLE `loan_transaction_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_transactions`
--

DROP TABLE IF EXISTS `loan_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_transactions` (
                                     `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                     `loan_id` bigint unsigned NOT NULL,
                                     `created_by_id` bigint unsigned DEFAULT NULL,
                                     `branch_id` bigint unsigned DEFAULT NULL,
                                     `payment_detail_id` bigint unsigned DEFAULT NULL,
                                     `name` text COLLATE utf8mb4_unicode_ci,
                                     `amount` decimal(65,6) NOT NULL,
                                     `credit` decimal(65,6) DEFAULT NULL,
                                     `debit` decimal(65,6) DEFAULT NULL,
                                     `principal_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                     `interest_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                     `fees_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                     `penalties_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                     `loan_transaction_type_id` bigint unsigned NOT NULL,
                                     `reversed` tinyint NOT NULL DEFAULT '0',
                                     `reversible` tinyint NOT NULL DEFAULT '0',
                                     `submitted_on` date DEFAULT NULL,
                                     `due_date` date DEFAULT NULL,
                                     `created_on` date DEFAULT NULL,
                                     `status` enum('pending','approved','declined') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `gateway_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `description` text COLLATE utf8mb4_unicode_ci,
                                     `payment_gateway_data` text COLLATE utf8mb4_unicode_ci,
                                     `online_transaction` tinyint NOT NULL DEFAULT '0',
                                     `created_at` timestamp NULL DEFAULT NULL,
                                     `updated_at` timestamp NULL DEFAULT NULL,
                                     PRIMARY KEY (`id`),
                                     KEY `loan_transactions_loan_id_index` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_transactions`
--

LOCK TABLES `loan_transactions` WRITE;
/*!40000 ALTER TABLE `loan_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loans`
--

DROP TABLE IF EXISTS `loans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loans` (
                         `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                         `created_by_id` bigint unsigned DEFAULT NULL,
                         `client_type` enum('client','group') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
                         `client_id` bigint unsigned DEFAULT NULL,
                         `group_id` bigint unsigned DEFAULT NULL,
                         `branch_id` bigint unsigned DEFAULT NULL,
                         `currency_id` bigint unsigned NOT NULL,
                         `loan_product_id` bigint unsigned NOT NULL,
                         `loan_transaction_processing_strategy_id` bigint unsigned NOT NULL,
                         `fund_id` bigint unsigned NOT NULL,
                         `loan_purpose_id` bigint unsigned NOT NULL,
                         `loan_officer_id` bigint unsigned NOT NULL,
                         `linked_savings_id` bigint unsigned DEFAULT NULL,
                         `loan_disbursement_channel_id` bigint unsigned DEFAULT NULL,
                         `submitted_on_date` date DEFAULT NULL,
                         `submitted_by_user_id` bigint unsigned DEFAULT NULL,
                         `approved_on_date` date DEFAULT NULL,
                         `approved_by_user_id` bigint unsigned DEFAULT NULL,
                         `approved_notes` text COLLATE utf8mb4_unicode_ci,
                         `expected_disbursement_date` date DEFAULT NULL,
                         `expected_first_payment_date` date DEFAULT NULL,
                         `first_payment_date` date DEFAULT NULL,
                         `expected_maturity_date` date DEFAULT NULL,
                         `disbursed_on_date` date DEFAULT NULL,
                         `disbursed_by_user_id` bigint unsigned DEFAULT NULL,
                         `disbursed_notes` text COLLATE utf8mb4_unicode_ci,
                         `rejected_on_date` date DEFAULT NULL,
                         `rejected_by_user_id` bigint unsigned DEFAULT NULL,
                         `rejected_notes` text COLLATE utf8mb4_unicode_ci,
                         `written_off_on_date` date DEFAULT NULL,
                         `written_off_by_user_id` bigint unsigned DEFAULT NULL,
                         `written_off_notes` text COLLATE utf8mb4_unicode_ci,
                         `closed_on_date` date DEFAULT NULL,
                         `closed_by_user_id` bigint unsigned DEFAULT NULL,
                         `closed_notes` text COLLATE utf8mb4_unicode_ci,
                         `rescheduled_on_date` date DEFAULT NULL,
                         `rescheduled_by_user_id` bigint unsigned DEFAULT NULL,
                         `rescheduled_notes` text COLLATE utf8mb4_unicode_ci,
                         `withdrawn_on_date` date DEFAULT NULL,
                         `withdrawn_by_user_id` bigint unsigned DEFAULT NULL,
                         `withdrawn_notes` text COLLATE utf8mb4_unicode_ci,
                         `external_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `principal` decimal(65,6) NOT NULL,
                         `applied_amount` decimal(65,6) DEFAULT NULL,
                         `approved_amount` decimal(65,6) DEFAULT NULL,
                         `interest_rate` decimal(65,6) NOT NULL,
                         `decimals` int DEFAULT NULL,
                         `instalment_multiple_of` int DEFAULT '1',
                         `loan_term` int NOT NULL,
                         `repayment_frequency` int NOT NULL,
                         `repayment_frequency_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci NOT NULL,
                         `interest_rate_type` enum('day','week','month','year','principal') COLLATE utf8mb4_unicode_ci NOT NULL,
                         `enable_balloon_payments` tinyint NOT NULL DEFAULT '0',
                         `allow_schedule_adjustments` tinyint NOT NULL DEFAULT '0',
                         `grace_on_principal_paid` int NOT NULL DEFAULT '0',
                         `grace_on_interest_paid` int NOT NULL DEFAULT '0',
                         `grace_on_interest_charged` int NOT NULL DEFAULT '0',
                         `allow_custom_grace_period` tinyint NOT NULL DEFAULT '0',
                         `allow_topup` tinyint NOT NULL DEFAULT '0',
                         `interest_methodology` enum('flat','declining_balance') COLLATE utf8mb4_unicode_ci NOT NULL,
                         `interest_recalculation` tinyint NOT NULL DEFAULT '0',
                         `amortization_method` enum('equal_installments','equal_principal_payments') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `interest_calculation_period_type` enum('daily','same') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `days_in_year` enum('actual','360','365','364') COLLATE utf8mb4_unicode_ci DEFAULT 'actual',
                         `days_in_month` enum('actual','30','31') COLLATE utf8mb4_unicode_ci DEFAULT 'actual',
                         `include_in_loan_cycle` tinyint NOT NULL DEFAULT '0',
                         `lock_guarantee_funds` tinyint NOT NULL DEFAULT '0',
                         `auto_allocate_overpayments` tinyint NOT NULL DEFAULT '0',
                         `allow_additional_charges` tinyint NOT NULL DEFAULT '0',
                         `auto_disburse` tinyint NOT NULL DEFAULT '0',
                         `status` enum('pending','approved','active','withdrawn','rejected','closed','rescheduled','written_off','overpaid','submitted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
                         `disbursement_charges` decimal(65,6) DEFAULT NULL,
                         `principal_disbursed_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `principal_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `principal_written_off_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `principal_outstanding_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `interest_disbursed_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `interest_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `interest_written_off_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `interest_waived_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `interest_outstanding_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `fees_disbursed_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `fees_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `fees_written_off_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `fees_waived_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `fees_outstanding_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `penalties_disbursed_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `penalties_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `penalties_written_off_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `penalties_waived_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `penalties_outstanding_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `total_disbursed_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `total_repaid_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `total_written_off_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `total_waived_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `total_outstanding_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         `classification` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `deduct_interest_from_principal` tinyint NOT NULL DEFAULT '0',
                         `loan_provisioning_id` bigint unsigned DEFAULT NULL,
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `loans_external_id_unique` (`external_id`),
                         UNIQUE KEY `loans_account_number_unique` (`account_number`),
                         KEY `loans_client_id_index` (`client_id`),
                         KEY `loans_loan_officer_id_index` (`loan_officer_id`),
                         KEY `loans_loan_product_id_index` (`loan_product_id`),
                         KEY `loans_branch_id_index` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loans`
--

LOCK TABLES `loans` WRITE;
/*!40000 ALTER TABLE `loans` DISABLE KEYS */;
/*!40000 ALTER TABLE `loans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
                         `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                         `menu_id` bigint DEFAULT NULL,
                         `parent_id` bigint DEFAULT NULL,
                         `parent_slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `is_parent` tinyint NOT NULL DEFAULT '0',
                         `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `description` text COLLATE utf8mb4_unicode_ci,
                         `menu_order` int DEFAULT NULL,
                         `url` text COLLATE utf8mb4_unicode_ci,
                         `permissions` text COLLATE utf8mb4_unicode_ci,
                         `icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `module` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,NULL,NULL,'',1,'Settings',NULL,'settings',NULL,31,'setting','setting.setting.index','fas fa-cogs','Setting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(2,NULL,1,'settings',0,'Organisation Settings',NULL,'organisation_settings',NULL,32,'setting/organisation','setting.setting.index','far fa-circle','Setting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(3,NULL,1,'settings',0,'General Settings',NULL,'general_settings',NULL,33,'setting/general','setting.setting.index','far fa-circle','Setting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(4,NULL,1,'settings',0,'Email Settings',NULL,'email_settings',NULL,34,'setting/email','setting.setting.index','far fa-circle','Setting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(5,NULL,1,'settings',0,'SMS Settings',NULL,'sms_settings',NULL,35,'setting/sms','setting.setting.index','far fa-circle','Setting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(6,NULL,1,'settings',0,'System Settings',NULL,'system_settings',NULL,36,'setting/system','setting.setting.index','far fa-circle','Setting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(7,NULL,1,'settings',0,'System Update',NULL,'system_update',NULL,37,'setting/system_update','setting.setting.index','far fa-circle','Setting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(8,NULL,1,'settings',0,'Other Settings',NULL,'other_settings',NULL,38,'setting/other','setting.setting.index','far fa-circle','Setting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(9,NULL,NULL,'',1,'Manage Modules',NULL,'modules',NULL,30,'module','core.modules.index','fas fa-plug','Core','2023-11-08 14:34:44','2023-11-08 14:34:44'),(10,NULL,NULL,'',1,'Manage Menu',NULL,'menu',NULL,31,'menu','core.menu.index','fas fa-bars','Core','2023-11-08 14:34:44','2023-11-08 14:34:44'),(11,NULL,1,'settings',0,'Payment Gateways',NULL,'menu',NULL,32,'settings/payment_gateway','core.payment_gateways.index','fas fa-money-bill','Core','2023-11-08 14:34:44','2023-11-08 14:34:44'),(12,NULL,NULL,'',1,'Themes',NULL,'themes',NULL,40,'theme','core.themes.index','fas fa-palette','Core','2023-11-08 14:34:44','2023-11-08 14:34:44'),(13,NULL,NULL,'',1,'Users',NULL,'users',NULL,27,'user','','fas fa-users','User','2023-11-08 14:34:44','2023-11-08 14:34:44'),(14,NULL,13,'users',0,'View Users',NULL,'view_loans',NULL,28,'user','user.users.index','far fa-circle','User','2023-11-08 14:34:44','2023-11-08 14:34:44'),(15,NULL,13,'users',0,'Create User',NULL,'create_loan',NULL,29,'user/create','user.users.create','far fa-circle','User','2023-11-08 14:34:44','2023-11-08 14:34:44'),(16,NULL,13,'users',0,'Manage Roles',NULL,'manage_roles',NULL,30,'user/role','user.roles.index','far fa-circle','User','2023-11-08 14:34:44','2023-11-08 14:34:44'),(17,NULL,NULL,'',1,'Dashboard',NULL,'dashboard',NULL,0,'dashboard','dashboard.index','fas fa-home','Dashboard','2023-11-08 14:34:44','2023-11-08 14:34:44'),(18,NULL,NULL,'',1,'Branches',NULL,'branches',NULL,6,'branch','','fas fa-building','Branch','2023-11-08 14:34:44','2023-11-08 14:34:44'),(19,NULL,18,'branches',0,'View Branches',NULL,'view_branches',NULL,7,'branch','branch.branches.index','far fa-circle','Branch','2023-11-08 14:34:44','2023-11-08 14:34:44'),(20,NULL,18,'branches',0,'Create Branch',NULL,'create_branch',NULL,8,'branch/create','branch.branches.create','far fa-circle','Branch','2023-11-08 14:34:44','2023-11-08 14:34:44'),(21,NULL,NULL,'',1,'Accounting',NULL,'accounting',NULL,3,'accounting','','fas fa-money-bill','Accounting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(22,NULL,21,'accounting',0,'View Charts of Accounts',NULL,'view_charts_of_accounts',NULL,4,'accounting/chart_of_account','accounting.chart_of_accounts.index','far fa-circle','Accounting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(23,NULL,21,'accounting',0,'Journal Entries',NULL,'journal_entries',NULL,5,'accounting/journal_entry','accounting.journal_entries.index','far fa-circle','Accounting','2023-11-08 14:34:44','2023-11-08 14:34:44'),(24,NULL,NULL,'',1,'Assets',NULL,'assets',NULL,30,'asset','asset.assets.index','fas fa-building','Asset','2023-11-08 14:34:45','2023-11-08 14:34:45'),(25,NULL,24,'assets',0,'View Assets',NULL,'view_assets',NULL,7,'asset','asset.assets.index','far fa-circle','Asset','2023-11-08 14:34:45','2023-11-08 14:34:45'),(26,NULL,24,'assets',0,'Create Asset',NULL,'create_asset',NULL,8,'asset/create','asset.assets.create','far fa-circle','Asset','2023-11-08 14:34:45','2023-11-08 14:34:45'),(27,NULL,24,'assets',0,'Manage Asset Types',NULL,'manage_asset_types',NULL,9,'asset/type','asset.assets.types.index','far fa-circle','Asset','2023-11-08 14:34:45','2023-11-08 14:34:45'),(28,NULL,NULL,'',1,'Clients',NULL,'clients',NULL,10,'client','','fas fa-users','Client','2023-11-08 14:34:46','2023-11-08 14:34:46'),(29,NULL,28,'clients',0,'View Clients',NULL,'view_clients',NULL,11,'client','client.clients.index','far fa-circle','Client','2023-11-08 14:34:46','2023-11-08 14:34:46'),(30,NULL,28,'clients',0,'Create Client',NULL,'create_client',NULL,12,'client/create','client.clients.create','far fa-circle','Client','2023-11-08 14:34:46','2023-11-08 14:34:46'),(31,NULL,NULL,'',1,'Savings',NULL,'savings',NULL,25,'savings','','fas fa-university','Savings','2023-11-08 14:34:47','2023-11-08 14:34:47'),(32,NULL,31,'savings',0,'View Savings',NULL,'view_savings',NULL,26,'savings','savings.savings.index','far fa-circle','Savings','2023-11-08 14:34:47','2023-11-08 14:34:47'),(33,NULL,31,'savings',0,'Create Savings',NULL,'create_savings',NULL,27,'savings/create','savings.savings.create','far fa-circle','Savings','2023-11-08 14:34:47','2023-11-08 14:34:47'),(34,NULL,31,'savings',0,'Manage Products',NULL,'manage_products',NULL,28,'savings/product','savings.savings.products.index','far fa-circle','Savings','2023-11-08 14:34:47','2023-11-08 14:34:47'),(35,NULL,31,'savings',0,'Manage Charges',NULL,'manage_charges',NULL,29,'savings/charge','savings.savings.charges.index','far fa-circle','Savings','2023-11-08 14:34:47','2023-11-08 14:34:47'),(36,NULL,NULL,'',1,'Loans',NULL,'loans',NULL,18,'loan','','fas fa-money-bill','Loan','2023-11-08 14:34:49','2023-11-08 14:34:49'),(37,NULL,36,'loans',0,'View Loans',NULL,'view_loans',NULL,19,'loan','loan.loans.index','far fa-circle','Loan','2023-11-08 14:34:49','2023-11-08 14:34:49'),(38,NULL,36,'loans',0,'View Applications',NULL,'view_applications',NULL,20,'loan/application','loan.loans.index','far fa-circle','Loan','2023-11-08 14:34:49','2023-11-08 14:34:49'),(39,NULL,36,'loans',0,'Loan Repayments',NULL,'view_loan_repayments',NULL,21,'loan/repayment','loan.loans.transactions.index','far fa-circle','Loan','2023-11-08 14:34:49','2023-11-08 14:34:49'),(40,NULL,36,'loans',0,'Create Loan',NULL,'create_loan',NULL,21,'loan/create','loan.loans.create','far fa-circle','Loan','2023-11-08 14:34:49','2023-11-08 14:34:49'),(41,NULL,36,'loans',0,'Manage Products',NULL,'manage_products',NULL,22,'loan/product','loan.loans.products.index','far fa-circle','Loan','2023-11-08 14:34:49','2023-11-08 14:34:49'),(42,NULL,36,'loans',0,'Manage Charges',NULL,'manage_charges',NULL,23,'loan/charge','loan.loans.charges.index','far fa-circle','Loan','2023-11-08 14:34:49','2023-11-08 14:34:49'),(43,NULL,36,'loans',0,'Loan Calculator',NULL,'loan_calculator',NULL,24,'loan/calculator','loan.loans.index','far fa-circle','Loan','2023-11-08 14:34:49','2023-11-08 14:34:49'),(44,NULL,36,'loans',0,'Loan Provisioning',NULL,'loan_provisioning',NULL,25,'loan/loan_provisioning','loan.loans.loan_provisioning.index','far fa-circle','Loan','2023-11-08 14:34:49','2023-11-08 14:34:49'),(45,NULL,NULL,'report',1,'Reports',NULL,'reports',NULL,20,'report','reports.index','fas fa-chart-bar','Report','2023-11-08 14:34:49','2023-11-08 14:34:49'),(46,NULL,NULL,'',1,'Communication',NULL,'communication',NULL,13,'communication','','fas fa-envelope','Communication','2023-11-08 14:34:49','2023-11-08 14:34:49'),(47,NULL,46,'communication',0,'View Campaigns',NULL,'view_campaigns',NULL,14,'communication/campaign','communication.campaigns.index','far fa-circle','Communication','2023-11-08 14:34:49','2023-11-08 14:34:49'),(48,NULL,46,'communication',0,'Create Campaign',NULL,'create_campaign',NULL,15,'communication/campaign/create','communication.campaigns.create','far fa-circle','Communication','2023-11-08 14:34:49','2023-11-08 14:34:49'),(49,NULL,46,'communication',0,'View Logs',NULL,'view_logs',NULL,16,'communication/log','communication.logs.index','far fa-circle','Communication','2023-11-08 14:34:49','2023-11-08 14:34:49'),(50,NULL,46,'communication',0,'Manage SMS Gateways',NULL,'manage_sms_gateways',NULL,17,'communication/sms_gateway','communication.sms_gateways.index','far fa-circle','Communication','2023-11-08 14:34:49','2023-11-08 14:34:49'),(51,NULL,NULL,'',1,'Payroll',NULL,'payroll',NULL,10,'payroll','payroll.payroll.index','fab fa-paypal','Payroll','2023-11-08 14:34:50','2023-11-08 14:34:50'),(52,NULL,51,'payroll',0,'View Payroll',NULL,'view_payroll',NULL,11,'payroll','payroll.payroll.index','far fa-circle','Payroll','2023-11-08 14:34:50','2023-11-08 14:34:50'),(53,NULL,51,'payroll',0,'Create Payroll',NULL,'create_payroll',NULL,12,'payroll/create','payroll.payroll.create','far fa-circle','Payroll','2023-11-08 14:34:50','2023-11-08 14:34:50'),(54,NULL,51,'payroll',0,'Manage Payroll Items',NULL,'manage_payroll_items',NULL,12,'payroll/item','payroll.payroll.items.index','far fa-circle','Payroll','2023-11-08 14:34:50','2023-11-08 14:34:50'),(55,NULL,51,'payroll',0,'Manage Payroll Templates',NULL,'manage_payroll_templates',NULL,12,'payroll/template','payroll.payroll.templates.index','far fa-circle','Payroll','2023-11-08 14:34:50','2023-11-08 14:34:50'),(56,NULL,NULL,'',1,'Custom Fields',NULL,'custom_fields',NULL,25,'custom_field','','fas fa-list','CustomField','2023-11-08 14:34:50','2023-11-08 14:34:50'),(57,NULL,56,'custom_fields',0,'View Custom Fields',NULL,'view_custom_fields',NULL,26,'custom_field','customfield.custom_fields.index','far fa-circle','Savings','2023-11-08 14:34:50','2023-11-08 14:34:50'),(58,NULL,56,'custom_fields',0,'Create Custom Field',NULL,'create_custom_fields',NULL,27,'custom_field/create','customfield.custom_fields.create','far fa-circle','Savings','2023-11-08 14:34:50','2023-11-08 14:34:50'),(59,NULL,NULL,'',1,'Expenses',NULL,'expenses',NULL,20,'expense','expense.expenses.index','fas fa-share','Expense','2023-11-08 14:34:50','2023-11-08 14:34:50'),(60,NULL,59,'expenses',0,'View Expenses',NULL,'view_expenses',NULL,0,'expense','expense.expenses.index','far fa-circle','Expense','2023-11-08 14:34:50','2023-11-08 14:34:50'),(61,NULL,59,'expenses',0,'Create Expense',NULL,'create_expense',NULL,1,'expense/create','expense.expenses.create','far fa-circle','Expense','2023-11-08 14:34:50','2023-11-08 14:34:50'),(62,NULL,59,'expenses',0,'Manage Expense Types',NULL,'manage_expense_types',NULL,2,'expense/type','expense.expenses.types.index','far fa-circle','Expense','2023-11-08 14:34:50','2023-11-08 14:34:50'),(63,NULL,NULL,'',1,'Income',NULL,'income',NULL,25,'income','income.income.index','fas fa-money-bill','Income','2023-11-08 14:34:51','2023-11-08 14:34:51'),(64,NULL,63,'income',0,'View Income',NULL,'view_assets',NULL,7,'income','income.income.index','far fa-circle','Income','2023-11-08 14:34:51','2023-11-08 14:34:51'),(65,NULL,63,'income',0,'Create Income',NULL,'create_asset',NULL,8,'income/create','income.income.create','far fa-circle','Income','2023-11-08 14:34:51','2023-11-08 14:34:51'),(66,NULL,63,'income',0,'Manage Income Types',NULL,'manage_asset_types',NULL,9,'income/type','income.income.types.index','far fa-circle','Income','2023-11-08 14:34:51','2023-11-08 14:34:51'),(67,NULL,1,'settings',0,'System Upgrade',NULL,'system_upgrade',NULL,40,'upgrade','upgrade.upgrades.index','fa fa-circle-o','Setting','2023-11-08 14:34:51','2023-11-08 14:34:51'),(68,NULL,NULL,'',1,'Shares',NULL,'shares',NULL,30,'share','share.shares.index','fas fa-database','Share','2023-11-08 14:34:52','2023-11-08 14:34:52'),(69,NULL,68,'shares',0,'View Shares',NULL,'view_shares',NULL,1,'share','share.shares.index','far fa-circle','Share','2023-11-08 14:34:52','2023-11-08 14:34:52'),(70,NULL,68,'shares',0,'Create Share',NULL,'create_share',NULL,2,'share/create','share.shares.create','far fa-circle','Share','2023-11-08 14:34:52','2023-11-08 14:34:52'),(71,NULL,68,'shares',0,'Manage Products',NULL,'manage_share_products',NULL,3,'share/product','share.shares.products.index','far fa-circle','Share','2023-11-08 14:34:52','2023-11-08 14:34:52'),(72,NULL,68,'shares',0,'Manage Charges',NULL,'manage_share_charges',NULL,3,'share/charge','share.shares.charges.index','far fa-circle','Share','2023-11-08 14:34:52','2023-11-08 14:34:52'),(73,NULL,NULL,'',1,'Activity Logs',NULL,'activity_log',NULL,60,'activity_log','activitylog.activity_logs.index','fa fa-database','Activitylog','2023-11-08 14:34:52','2023-11-08 14:34:52');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
                              `id` int unsigned NOT NULL AUTO_INCREMENT,
                              `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `batch` int NOT NULL,
                              PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_07_06_133107_create_settings_table',1),(2,'2018_08_08_100000_create_telescope_entries_table',2),(3,'2019_07_06_135714_create_countries_table',2),(4,'2019_07_06_140045_create_currencies_table',2),(5,'2019_07_06_140908_create_timezones_table',2),(6,'2019_07_07_110645_create_payment_types_table',2),(7,'2019_07_07_110717_create_payment_details_table',2),(8,'2019_07_10_225923_create_notifications_table',2),(9,'2019_07_27_080313_create_jobs_table',2),(10,'2019_09_07_001758_create_menus_table',2),(11,'2019_11_04_152950_create_tax_rates_table',2),(12,'2020_01_14_114056_create_failed_jobs_table',2),(13,'2014_10_12_000000_create_users_table',3),(14,'2014_10_12_100000_create_password_resets_table',3),(15,'2019_07_01_214429_create_permission_tables',3),(16,'2019_08_03_085238_create_widgets_table',4),(17,'2019_07_06_111125_create_branches_table',5),(18,'2019_07_06_111419_create_branch_users_table',5),(19,'2019_07_07_093258_create_chart_of_accounts_table',6),(20,'2019_07_07_093648_create_journal_entries_table',6),(21,'2019_09_26_153830_create_asset_types_table',7),(22,'2019_09_26_153906_create_assets_table',7),(23,'2019_09_26_153953_create_asset_notes_table',7),(24,'2019_09_26_153954_create_asset_maintenance_types_table',7),(25,'2019_09_26_154012_create_asset_maintenance_table',7),(26,'2019_09_26_154050_create_asset_files_table',7),(27,'2019_09_26_154103_create_asset_pictures_table',7),(28,'2019_09_27_063335_create_asset_depreciation_table',7),(29,'2019_07_08_130052_create_titles_table',8),(30,'2019_07_08_130053_create_client_relationships_table',8),(31,'2019_07_08_130533_create_professions_table',8),(32,'2019_07_08_150347_create_client_types_table',8),(33,'2019_07_08_151636_create_client_identification_types_table',8),(34,'2019_07_08_182818_create_clients_table',8),(35,'2019_07_08_182911_create_client_files_table',8),(36,'2019_07_08_182938_create_client_identification_table',8),(37,'2019_07_08_183031_create_client_next_of_kin_table',8),(38,'2019_07_11_180428_create_client_users_table',8),(39,'2022_02_02_095410_add_status_to_client_files_table',8),(40,'2022_02_02_095838_create_client_file_types_table',8),(41,'2022_02_02_100625_add_status_to_client_identification_table',8),(42,'2022_10_21_131227_add_client_inactive_days_to_settings_table',8),(43,'2019_08_05_093954_create_savings_charge_options_table',9),(44,'2019_08_05_094221_create_savings_charge_types_table',9),(45,'2019_08_05_094458_create_savings_charges_table',9),(46,'2019_08_05_094544_create_savings_transaction_types_table',9),(47,'2019_08_05_094956_create_savings_products_table',9),(48,'2019_08_05_095030_create_savings_table',9),(49,'2019_08_05_095031_create_savings_linked_charges_table',9),(50,'2019_08_05_095048_create_savings_transactions_table',9),(51,'2019_08_05_095148_create_savings_product_linked_charges_table',9),(52,'2022_08_25_175418_create_savings_files_table',9),(53,'2022_08_25_182108_create_savings_file_types_table',9),(54,'2019_07_15_094401_create_loan_transaction_processing_strategies_table',10),(55,'2019_07_15_100526_create_loan_charge_types_table',10),(56,'2019_07_15_100649_create_loan_charge_options_table',10),(57,'2019_07_15_104331_create_loan_credit_checks_table',10),(58,'2019_07_15_141230_create_loan_purposes_table',10),(59,'2019_07_15_201056_create_loan_transaction_types_table',10),(60,'2019_07_15_214326_create_funds_table',10),(61,'2019_07_15_214410_create_loan_charges_table',10),(62,'2019_07_15_214940_create_loan_products_table',10),(63,'2019_07_15_215017_create_loan_product_linked_charges_table',10),(64,'2019_07_15_215107_create_loan_disbursement_channels_table',10),(65,'2019_07_15_215314_create_loan_collateral_types_table',10),(66,'2019_07_15_215355_create_loans_table',10),(67,'2019_07_15_215451_create_loan_collateral_table',10),(68,'2019_07_15_215546_create_loan_repayment_schedules_table',10),(69,'2019_07_15_215604_create_loan_transactions_table',10),(70,'2019_07_15_221258_create_loan_linked_charges_table',10),(71,'2019_07_17_130522_create_loan_product_linked_credit_checks_table',10),(72,'2019_07_17_130536_create_loan_linked_credit_checks_table',10),(73,'2019_07_17_162121_create_loan_guarantors_table',10),(74,'2019_07_17_194223_create_loan_officer_history_table',10),(75,'2019_07_17_194247_create_loan_history_table',10),(76,'2019_07_17_194817_create_loan_files_table',10),(77,'2019_07_17_194827_create_loan_notes_table',10),(78,'2019_08_30_074012_create_loan_applications_table',10),(79,'2022_02_02_095622_add_status_to_loan_files_table',10),(80,'2022_02_02_095909_create_loan_file_types_table',10),(81,'2022_10_14_112319_add_classification_to_loans_table',10),(82,'2022_10_14_115558_add_simple_interest_to_loan_products_table',10),(83,'2022_10_21_133419_create_loan_provisioning_table',10),(84,'2022_10_21_140121_add_loan_provisioning_id_to_loans_table',10),(85,'2019_07_27_161835_create_communication_campaign_business_rules_table',11),(86,'2019_07_27_161902_create_communication_campaign_attachment_types_table',11),(87,'2019_07_28_150020_create_sms_gateways_table',11),(88,'2019_07_28_150053_create_communication_campaigns_table',11),(89,'2019_07_28_161940_create_communication_campaign_logs_table',11),(90,'2016_06_01_000001_create_oauth_auth_codes_table',12),(91,'2016_06_01_000002_create_oauth_access_tokens_table',12),(92,'2016_06_01_000003_create_oauth_refresh_tokens_table',12),(93,'2016_06_01_000004_create_oauth_clients_table',12),(94,'2016_06_01_000005_create_oauth_personal_access_clients_table',12),(95,'2019_09_22_080345_create_payroll_items_table',13),(96,'2019_09_22_081303_create_payroll_templates_table',13),(97,'2019_09_22_081304_create_payroll_template_items_table',13),(98,'2019_09_22_081326_create_payroll_table',13),(99,'2019_09_22_081441_create_payroll_items_meta_table',13),(100,'2019_09_22_082657_create_payroll_payments_table',13),(101,'2019_09_15_164302_create_custom_fields_table',14),(102,'2019_09_15_164434_create_custom_fields_meta_table',14),(103,'2022_10_21_130720_add_view_in_report_to_custom_fields_table',14),(104,'2020_02_24_114006_create_expense_types_table',15),(105,'2020_02_24_114018_create_expenses_table',15),(106,'2020_02_24_114052_create_income_types_table',16),(107,'2020_02_24_114104_create_income_table',16),(108,'2020_09_10_171351_create_share_charge_options_table',17),(109,'2020_09_10_171936_create_share_transaction_types_table',17),(110,'2020_09_10_171940_create_share_charge_types_table',17),(111,'2020_09_10_171940_create_share_charges_table',17),(112,'2020_09_10_171959_create_share_products_table',17),(113,'2020_09_10_172033_create_share_product_linked_charges_table',17),(114,'2020_09_10_172054_create_shares_table',17),(115,'2020_09_10_172110_create_share_linked_charges_table',17),(116,'2020_09_10_172120_create_share_transactions_table',17),(117,'2020_09_10_172155_create_share_market_periods_table',17),(118,'2019_07_15_125704_create_activity_log_table',18),(119,'2022_07_13_135728_add_event_column_to_activity_log_table',18),(120,'2022_07_13_135729_add_batch_uuid_column_to_activity_log_table',18),(121,'2020_08_31_141646_create_wallets_table',19),(122,'2020_08_31_150716_create_wallet_transactions_table',19);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
                                         `permission_id` int unsigned NOT NULL,
                                         `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                         `model_id` bigint unsigned NOT NULL,
                                         PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
                                         KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
                                         CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
                                   `role_id` int unsigned NOT NULL,
                                   `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `model_id` bigint unsigned NOT NULL,
                                   PRIMARY KEY (`role_id`,`model_id`,`model_type`),
                                   KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
                                   CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'Modules\\User\\Entities\\User',1);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
                                 `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `notifiable_id` bigint unsigned NOT NULL,
                                 `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `read_at` timestamp NULL DEFAULT NULL,
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL,
                                 PRIMARY KEY (`id`),
                                 KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
                                       `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                       `user_id` int DEFAULT NULL,
                                       `client_id` int unsigned NOT NULL,
                                       `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                       `scopes` text COLLATE utf8mb4_unicode_ci,
                                       `revoked` tinyint(1) NOT NULL,
                                       `created_at` timestamp NULL DEFAULT NULL,
                                       `updated_at` timestamp NULL DEFAULT NULL,
                                       `expires_at` datetime DEFAULT NULL,
                                       PRIMARY KEY (`id`),
                                       KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_auth_codes` (
                                    `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                    `user_id` int NOT NULL,
                                    `client_id` int unsigned NOT NULL,
                                    `scopes` text COLLATE utf8mb4_unicode_ci,
                                    `revoked` tinyint(1) NOT NULL,
                                    `expires_at` datetime DEFAULT NULL,
                                    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_clients` (
                                 `id` int unsigned NOT NULL AUTO_INCREMENT,
                                 `user_id` int DEFAULT NULL,
                                 `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `personal_access_client` tinyint(1) NOT NULL,
                                 `password_client` tinyint(1) NOT NULL,
                                 `revoked` tinyint(1) NOT NULL,
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL,
                                 PRIMARY KEY (`id`),
                                 KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_personal_access_clients` (
                                                 `id` int unsigned NOT NULL AUTO_INCREMENT,
                                                 `client_id` int unsigned NOT NULL,
                                                 `created_at` timestamp NULL DEFAULT NULL,
                                                 `updated_at` timestamp NULL DEFAULT NULL,
                                                 PRIMARY KEY (`id`),
                                                 KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_refresh_tokens` (
                                        `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `revoked` tinyint(1) NOT NULL,
                                        `expires_at` datetime DEFAULT NULL,
                                        PRIMARY KEY (`id`),
                                        KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
                                   `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_details`
--

DROP TABLE IF EXISTS `payment_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_details` (
                                   `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                   `created_by_id` int DEFAULT NULL,
                                   `payment_type_id` int DEFAULT NULL,
                                   `transaction_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `reference` int DEFAULT NULL,
                                   `cheque_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `receipt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `routing_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `description` text COLLATE utf8mb4_unicode_ci,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL,
                                   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_details`
--

LOCK TABLES `payment_details` WRITE;
/*!40000 ALTER TABLE `payment_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_types`
--

DROP TABLE IF EXISTS `payment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_types` (
                                 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                 `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `system_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `description` text COLLATE utf8mb4_unicode_ci,
                                 `is_cash` tinyint NOT NULL DEFAULT '0',
                                 `is_online` tinyint NOT NULL DEFAULT '0',
                                 `is_system` tinyint NOT NULL DEFAULT '0',
                                 `active` tinyint NOT NULL DEFAULT '1',
                                 `position` int DEFAULT NULL,
                                 `options` text COLLATE utf8mb4_unicode_ci,
                                 `unique_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL,
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_types`
--

LOCK TABLES `payment_types` WRITE;
/*!40000 ALTER TABLE `payment_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll`
--

DROP TABLE IF EXISTS `payroll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll` (
                           `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                           `branch_id` bigint unsigned DEFAULT NULL,
                           `currency_id` bigint unsigned DEFAULT NULL,
                           `created_by_id` bigint unsigned DEFAULT NULL,
                           `payroll_template_id` bigint unsigned DEFAULT NULL,
                           `user_id` bigint unsigned DEFAULT NULL,
                           `employee_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `comments` text COLLATE utf8mb4_unicode_ci,
                           `work_duration` decimal(65,2) NOT NULL DEFAULT '0.00',
                           `duration_unit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `amount_per_duration` decimal(65,2) NOT NULL DEFAULT '0.00',
                           `total_duration_amount` decimal(65,2) NOT NULL DEFAULT '0.00',
                           `gross_amount` decimal(65,2) NOT NULL DEFAULT '0.00',
                           `total_allowances` decimal(65,2) NOT NULL DEFAULT '0.00',
                           `total_deductions` decimal(65,2) NOT NULL DEFAULT '0.00',
                           `date` date DEFAULT NULL,
                           `year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `month` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `recurring` tinyint DEFAULT '0',
                           `recur_frequency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '31',
                           `recur_start_date` date DEFAULT NULL,
                           `recur_end_date` date DEFAULT NULL,
                           `recur_next_date` date DEFAULT NULL,
                           `recur_type` enum('day','week','month','year') COLLATE utf8mb4_unicode_ci DEFAULT 'month',
                           `created_at` timestamp NULL DEFAULT NULL,
                           `updated_at` timestamp NULL DEFAULT NULL,
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll`
--

LOCK TABLES `payroll` WRITE;
/*!40000 ALTER TABLE `payroll` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_items`
--

DROP TABLE IF EXISTS `payroll_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll_items` (
                                 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                 `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `type` enum('allowance','deduction') COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `amount_type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `amount` decimal(65,2) NOT NULL,
                                 `description` text COLLATE utf8mb4_unicode_ci,
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL,
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_items`
--

LOCK TABLES `payroll_items` WRITE;
/*!40000 ALTER TABLE `payroll_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_items_meta`
--

DROP TABLE IF EXISTS `payroll_items_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll_items_meta` (
                                      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                      `payroll_id` bigint unsigned DEFAULT NULL,
                                      `payroll_item_id` bigint unsigned DEFAULT NULL,
                                      `percentage` decimal(65,2) DEFAULT NULL,
                                      `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `type` enum('allowance','deduction') COLLATE utf8mb4_unicode_ci NOT NULL,
                                      `amount_type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL,
                                      `amount` decimal(65,2) NOT NULL,
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_items_meta`
--

LOCK TABLES `payroll_items_meta` WRITE;
/*!40000 ALTER TABLE `payroll_items_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_items_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_payments`
--

DROP TABLE IF EXISTS `payroll_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll_payments` (
                                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                    `created_by_id` bigint unsigned DEFAULT NULL,
                                    `branch_id` bigint unsigned DEFAULT NULL,
                                    `payroll_id` bigint unsigned DEFAULT NULL,
                                    `payment_type_id` bigint unsigned DEFAULT NULL,
                                    `payment_detail_id` bigint unsigned DEFAULT NULL,
                                    `amount` decimal(65,2) NOT NULL,
                                    `submitted_on` date DEFAULT NULL,
                                    `notes` text COLLATE utf8mb4_unicode_ci,
                                    `created_at` timestamp NULL DEFAULT NULL,
                                    `updated_at` timestamp NULL DEFAULT NULL,
                                    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_payments`
--

LOCK TABLES `payroll_payments` WRITE;
/*!40000 ALTER TABLE `payroll_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_template_items`
--

DROP TABLE IF EXISTS `payroll_template_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll_template_items` (
                                          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                          `payroll_template_id` bigint unsigned DEFAULT NULL,
                                          `payroll_item_id` bigint unsigned DEFAULT NULL,
                                          `created_at` timestamp NULL DEFAULT NULL,
                                          `updated_at` timestamp NULL DEFAULT NULL,
                                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_template_items`
--

LOCK TABLES `payroll_template_items` WRITE;
/*!40000 ALTER TABLE `payroll_template_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_template_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_templates`
--

DROP TABLE IF EXISTS `payroll_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll_templates` (
                                     `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                     `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `work_duration` decimal(65,2) NOT NULL DEFAULT '0.00',
                                     `duration_unit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `amount_per_duration` decimal(65,2) NOT NULL DEFAULT '0.00',
                                     `total_duration_amount` decimal(65,2) NOT NULL DEFAULT '0.00',
                                     `picture` text COLLATE utf8mb4_unicode_ci,
                                     `description` text COLLATE utf8mb4_unicode_ci,
                                     `items` text COLLATE utf8mb4_unicode_ci,
                                     `created_at` timestamp NULL DEFAULT NULL,
                                     `updated_at` timestamp NULL DEFAULT NULL,
                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_templates`
--

LOCK TABLES `payroll_templates` WRITE;
/*!40000 ALTER TABLE `payroll_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
                               `id` int unsigned NOT NULL AUTO_INCREMENT,
                               `parent_id` int DEFAULT NULL,
                               `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `module` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `description` text COLLATE utf8mb4_unicode_ci,
                               `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=299 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,NULL,'setting.setting.index','View settings','Setting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(2,NULL,'setting.setting.edit','Edit Settings','Setting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(3,NULL,'core.payment_types.index','View Payment Types','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(4,NULL,'core.payment_types.create','Create Payment Types','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(5,NULL,'core.payment_types.edit','Edit Payment Types','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(6,NULL,'core.payment_types.destroy','Delete Payment Types','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(7,NULL,'core.currencies.index','View Payment Details','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(8,NULL,'core.currencies.create','Create Currencies','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(9,NULL,'core.currencies.edit','Edit Currencies','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(10,NULL,'core.currencies.destroy','Delete Currencies','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(11,NULL,'core.modules.index','View Modules','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(12,NULL,'core.modules.create','Create Modules','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(13,NULL,'core.modules.destroy','Delete Modules','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(14,NULL,'core.menu.index','Manage Menu','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(15,NULL,'core.themes.index','Themes','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(16,NULL,'core.payment_gateways.index','View Payment Gateway','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(17,NULL,'core.payment_gateways.create','Create Payment Gateway','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(18,NULL,'core.payment_gateways.edit','Edit Payment Gateway','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(19,NULL,'core.payment_gateways.destroy','Delete Payment Gateway','Core',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(20,NULL,'user.users.index','View Users','User',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(21,NULL,'user.users.create','Create Users','User',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(22,NULL,'user.users.edit','Edit Users','User',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(23,NULL,'user.users.destroy','Delete Users','User',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(24,NULL,'user.roles.index','View Roles','User',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(25,NULL,'user.roles.create','Create Roles','User',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(26,NULL,'user.roles.edit','Edit Roles','User',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(27,NULL,'user.roles.destroy','Delete Roles','User',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(28,NULL,'user.reports.index','View Reports','User',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(29,NULL,'user.reports.performance','View Performance Reports','User',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(30,NULL,'dashboard.index','View Dashboard','Dashboard',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(31,NULL,'dashboard.edit','Edit Dashboard','Dashboard',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(32,NULL,'branch.branches.index','View Branches','Branch',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(33,NULL,'branch.branches.create','Create Branches','Branch',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(34,NULL,'branch.branches.edit','Edit Branches','Branch',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(35,NULL,'branch.branches.destroy','Delete Branches','Branch',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(36,NULL,'branch.branches.assign_user','Assign Users','Branch',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(37,NULL,'accounting.chart_of_accounts.index','View Chart of accounts','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(38,NULL,'accounting.chart_of_accounts.create','Create Chart of accounts','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(39,NULL,'accounting.chart_of_accounts.edit','Edit Chart of accounts','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(40,NULL,'accounting.chart_of_accounts.destroy','Delete Chart of accounts','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(41,NULL,'accounting.journal_entries.index','View Journal Entries','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(42,NULL,'accounting.journal_entries.create','Create Journal Entries','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(43,NULL,'accounting.journal_entries.edit','Edit Journal Entries','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(44,NULL,'accounting.journal_entries.reverse','Reverse Journal Entries','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(45,NULL,'accounting.reports.balance_sheet','View Balance Sheet','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(46,NULL,'accounting.reports.trial_balance','View Trial Balance','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(47,NULL,'accounting.reports.income_statement','View Income Statement','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(48,NULL,'accounting.reports.ledger','View Ledger','Accounting',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(49,NULL,'asset.assets.index','View Assets','Asset',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(50,NULL,'asset.assets.create','Create Assets','Asset',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(51,NULL,'asset.assets.edit','Edit Assets','Asset',NULL,'web','2023-11-08 14:34:44','2023-11-08 14:34:44'),(52,NULL,'asset.assets.destroy','Delete Assets','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(53,NULL,'asset.assets.types.index','View Asset Types','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(54,NULL,'asset.assets.types.create','Create Asset Types','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(55,NULL,'asset.assets.types.edit','Edit Asset Types','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(56,NULL,'asset.assets.types.destroy','Delete Asset Types','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(57,NULL,'asset.assets.notes.index','View Asset Notes','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(58,NULL,'asset.assets.notes.create','Create Asset Notes','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(59,NULL,'asset.assets.notes.edit','Edit Asset Notes','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(60,NULL,'asset.assets.notes.destroy','Delete Asset Notes','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(61,NULL,'asset.assets.maintenance.index','View Asset Maintenance','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(62,NULL,'asset.assets.maintenance.create','Create Asset Maintenance','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(63,NULL,'asset.assets.maintenance.edit','Edit Asset Maintenance','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(64,NULL,'asset.assets.maintenance.destroy','Delete Asset Maintenance','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(65,NULL,'asset.assets.files.index','View Asset Files','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(66,NULL,'asset.assets.files.create','Create Asset Files','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(67,NULL,'asset.assets.files.edit','Edit Asset Files','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(68,NULL,'asset.assets.files.destroy','Delete Asset Files','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(69,NULL,'asset.assets.pictures.index','View Asset Pictures','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(70,NULL,'asset.assets.pictures.create','Create Asset Pictures','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(71,NULL,'asset.assets.pictures.edit','Edit Asset Pictures','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(72,NULL,'asset.assets.pictures.destroy','Delete Asset Pictures','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(73,NULL,'asset.assets.valuations.index','View Asset Valuations','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(74,NULL,'asset.assets.valuations.create','Create Asset Valuations','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(75,NULL,'asset.assets.valuations.edit','Edit Asset Valuations','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(76,NULL,'asset.assets.valuations.destroy','Delete Asset Valuations','Asset',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(77,NULL,'client.clients.index','View Clients','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(78,NULL,'client.clients.index_own','View Own Clients Only','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(79,NULL,'client.clients.create','Create Clients','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(80,NULL,'client.clients.edit','Edit Clients','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(81,NULL,'client.clients.edit_own','Edit Own Clients','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(82,NULL,'client.clients.activate','Activate Clients','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(83,NULL,'client.clients.activate_own','Activate Own Clients','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(84,NULL,'client.clients.destroy','Delete Clients','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(85,NULL,'client.clients.destroy_own','Delete Own Clients','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(86,NULL,'client.clients.user.create','Create Client Users','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(87,NULL,'client.clients.user.destroy','Delete Client Users','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(88,NULL,'client.clients.titles.index','View Titles','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(89,NULL,'client.clients.titles.create','Create Titles','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(90,NULL,'client.clients.titles.edit','Edit Titles','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(91,NULL,'client.clients.titles.destroy','Delete Titles','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(92,NULL,'client.clients.professions.index','View Professions','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(93,NULL,'client.clients.professions.create','Create Professions','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(94,NULL,'client.clients.professions.edit','Edit Professions','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(95,NULL,'client.clients.professions.destroy','Delete Professions','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(96,NULL,'client.clients.client_relationships.index','View Client Relationships','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(97,NULL,'client.clients.client_relationships.create','Create Client Relationships','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(98,NULL,'client.clients.client_relationships.edit','Edit Client Relationships','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(99,NULL,'client.clients.client_relationships.destroy','Delete Client Relationships','Client',NULL,'web','2023-11-08 14:34:45','2023-11-08 14:34:45'),(100,NULL,'client.clients.client_types.index','View Client Types','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(101,NULL,'client.clients.client_types.create','Create Client Types','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(102,NULL,'client.clients.client_types.edit','Edit Client Types','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(103,NULL,'client.clients.client_types.destroy','Delete Client Types','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(104,NULL,'client.clients.identification_types.index','View Identification Types','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(105,NULL,'client.clients.identification_types.create','Create Identification Types','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(106,NULL,'client.clients.identification_types.edit','Edit Identification Types','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(107,NULL,'client.clients.identification_types.destroy','Delete Identification Types','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(108,NULL,'client.clients.files.index','View Files','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(109,NULL,'client.clients.files.create','Create Files','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(110,NULL,'client.clients.files.edit','Edit Files','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(111,NULL,'client.clients.files.destroy','Delete Files','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(112,NULL,'client.clients.next_of_kin.index','View Next of kin','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(113,NULL,'client.clients.next_of_kin.create','Create Next of kin','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(114,NULL,'client.clients.next_of_kin.edit','Edit Next of kin','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(115,NULL,'client.clients.next_of_kin.destroy','Delete Next of kins','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(116,NULL,'client.clients.identification.index','View Identification','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(117,NULL,'client.clients.identification.create','Create Identification','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(118,NULL,'client.clients.identification.edit','Edit Identification','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(119,NULL,'client.clients.identification.destroy','Delete Identification','Client',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(120,NULL,'savings.savings.index','View Savings','Savings',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(121,NULL,'savings.savings.create','Create Savings','Savings',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(122,NULL,'savings.savings.edit','Edit Savings','Savings',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(123,NULL,'savings.savings.destroy','Delete Savings','Savings',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(124,NULL,'savings.savings.approve_savings','Approve/Reject Savings','Savings',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(125,NULL,'savings.savings.activate_savings','Activate Savings','Savings',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(126,NULL,'savings.savings.withdraw_savings','Withdraw Savings','Savings',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(127,NULL,'savings.savings.inactive_savings','Inactive Savings','Savings',NULL,'web','2023-11-08 14:34:46','2023-11-08 14:34:46'),(128,NULL,'savings.savings.dormant_savings','Dormant Savings','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(129,NULL,'savings.savings.close_savings','Close Savings','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(130,NULL,'savings.savings.products.index','View savings Products','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(131,NULL,'savings.savings.products.create','Create savings Products','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(132,NULL,'savings.savings.products.edit','Edit savings Products','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(133,NULL,'savings.savings.products.destroy','Delete savings Products','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(134,NULL,'savings.savings.transactions.index','View Transactions','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(135,NULL,'savings.savings.transactions.create','Create Transactions','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(136,NULL,'savings.savings.transactions.edit','Edit Transactions','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(137,NULL,'savings.savings.transactions.destroy','Delete/Reverse Transactions','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(138,NULL,'savings.savings.charges.index','View Charges','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(139,NULL,'savings.savings.charges.create','Create Charges','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(140,NULL,'savings.savings.charges.edit','Edit Charges','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(141,NULL,'savings.savings.charges.destroy','Delete Charges','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(142,NULL,'savings.savings.files.index','View Files','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(143,NULL,'savings.savings.files.create','Create Files','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(144,NULL,'savings.savings.files.edit','Edit Files','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(145,NULL,'savings.savings.files.destroy','Delete Files','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(146,NULL,'savings.savings.reports.transactions','View Transactions Report','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(147,NULL,'savings.savings.reports.balances','View Balances Report','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(148,NULL,'savings.savings.reports.accounts','View Accounts Report','Savings',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(149,NULL,'loan.loans.index','View Loans','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(150,NULL,'loan.loans.create','Create Loans','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(151,NULL,'loan.loans.edit','Edit Loans','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(152,NULL,'loan.loans.destroy','Delete Loans','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(153,NULL,'loan.loans.approve_loan','Approve/Reject Loans','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(154,NULL,'loan.loans.disburse_loan','Disburse Loans','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(155,NULL,'loan.loans.withdraw_loan','Withdraw Loans','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(156,NULL,'loan.loans.write_off_loan','Write Off Loans','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(157,NULL,'loan.loans.reschedule_loan','Reschedule Loans','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(158,NULL,'loan.loans.close_loan','Close Loans','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(159,NULL,'loan.loans.calculator','Use Loan Calculator','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(160,NULL,'loan.loans.loan_history','View Loan History','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(161,NULL,'loan.loans.products.index','View Loan Products','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(162,NULL,'loan.loans.products.create','Create Loan Products','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(163,NULL,'loan.loans.products.edit','Edit Loan Products','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(164,NULL,'loan.loans.products.destroy','Delete Loan Products','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(165,NULL,'loan.loans.transactions.index','View Transactions','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(166,NULL,'loan.loans.transactions.create','Create Transactions','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(167,NULL,'loan.loans.transactions.edit','Edit Transactions','Loan',NULL,'web','2023-11-08 14:34:47','2023-11-08 14:34:47'),(168,NULL,'loan.loans.transactions.destroy','Delete/Reverse Transactions','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(169,NULL,'loan.loans.charges.index','View Charges','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(170,NULL,'loan.loans.charges.create','Create Charges','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(171,NULL,'loan.loans.charges.edit','Edit Charges','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(172,NULL,'loan.loans.charges.destroy','Delete Charges','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(173,NULL,'loan.loans.funds.index','View Funds','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(174,NULL,'loan.loans.funds.create','Create Funds','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(175,NULL,'loan.loans.funds.edit','Edit Funds','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(176,NULL,'loan.loans.funds.destroy','Delete Funds','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(177,NULL,'loan.loans.credit_checks.index','View Credit Checks','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(178,NULL,'loan.loans.credit_checks.create','Create Credit Checks','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(179,NULL,'loan.loans.credit_checks.edit','Edit Credit Checks','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(180,NULL,'loan.loans.credit_checks.destroy','Delete Credit Checks','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(181,NULL,'loan.loans.collateral.index','View Collateral','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(182,NULL,'loan.loans.collateral.create','Create Collateral','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(183,NULL,'loan.loans.collateral.edit','Edit Collateral','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(184,NULL,'loan.loans.collateral.destroy','Delete Collateral','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(185,NULL,'loan.loans.collateral_types.index','View Collateral Types','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(186,NULL,'loan.loans.collateral_types.create','Create Collateral Types','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(187,NULL,'loan.loans.collateral_types.edit','Edit Collateral Types','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(188,NULL,'loan.loans.collateral_types.destroy','Delete Collateral Types','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(189,NULL,'loan.loans.loan_provisioning.index','View Loan Provisioning','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(190,NULL,'loan.loans.loan_provisioning.create','Create Loan Provisioning','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(191,NULL,'loan.loans.loan_provisioning.edit','Edit Loan Provisioning','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(192,NULL,'loan.loans.loan_provisioning.destroy','Delete Loan Provisioning','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(193,NULL,'loan.loans.purposes.index','View Purposes','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(194,NULL,'loan.loans.purposes.create','Create Purposes','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(195,NULL,'loan.loans.purposes.edit','Edit Purposes','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(196,NULL,'loan.loans.purposes.destroy','Delete Purposes','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(197,NULL,'loan.loans.files.index','View Files','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(198,NULL,'loan.loans.files.create','Create Files','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(199,NULL,'loan.loans.files.edit','Edit Files','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(200,NULL,'loan.loans.files.destroy','Delete Files','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(201,NULL,'loan.loans.guarantors.index','View Guarantors','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(202,NULL,'loan.loans.guarantors.create','Create Guarantors','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(203,NULL,'loan.loans.guarantors.edit','Edit Guarantors','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(204,NULL,'loan.loans.guarantors.destroy','Delete Guarantors','Loan',NULL,'web','2023-11-08 14:34:48','2023-11-08 14:34:48'),(205,NULL,'loan.loans.notes.index','View Notes','Loan',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(206,NULL,'loan.loans.notes.create','Create Notes','Loan',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(207,NULL,'loan.loans.notes.edit','Edit Notes','Loan',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(208,NULL,'loan.loans.notes.destroy','Delete Notes','Loan',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(209,NULL,'loan.loans.reports.collection_sheet','View Collection Sheet Reports','Loan',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(210,NULL,'loan.loans.reports.repayments','View Repayments Report','Loan',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(211,NULL,'loan.loans.reports.expected_repayments','View Expected Repayments Report','Loan',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(212,NULL,'loan.loans.reports.arrears','View Arrears Reports','Loan',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(213,NULL,'loan.loans.reports.disbursements','View Disbursements Report','Loan',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(214,NULL,'reports.index','View Reports','Report',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(215,NULL,'communication.index','View Communication','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(216,NULL,'communication.campaigns.index','View Campaigns','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(217,NULL,'communication.campaigns.create','Create Campaigns','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(218,NULL,'communication.campaigns.edit','Edit Campaigns','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(219,NULL,'communication.campaigns.destroy','Delete Campaigns','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(220,NULL,'communication.logs.index','View Logs','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(221,NULL,'communication.logs.create','Create Logs','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(222,NULL,'communication.logs.edit','Edit Logs','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(223,NULL,'communication.logs.destroy','Delete Logs','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(224,NULL,'communication.sms_gateways.index','View SMS Gateways','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(225,NULL,'communication.sms_gateways.create','Create SMS Gateways','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(226,NULL,'communication.sms_gateways.edit','Edit SMS Gateways','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(227,NULL,'communication.sms_gateways.destroy','Delete SMS Gateways','Communication',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(228,NULL,'payroll.payroll.index','View Payroll','Payroll',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(229,NULL,'payroll.payroll.create','Create Payroll','Payroll',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(230,NULL,'payroll.payroll.edit','Edit Payroll','Payroll',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(231,NULL,'payroll.payroll.destroy','Delete Payroll','Payroll',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(232,NULL,'payroll.payroll.items.index','View Payroll Items','Payroll',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(233,NULL,'payroll.payroll.items.create','Create Payroll Items','Payroll',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(234,NULL,'payroll.payroll.items.edit','Edit Payroll Items','Payroll',NULL,'web','2023-11-08 14:34:49','2023-11-08 14:34:49'),(235,NULL,'payroll.payroll.items.destroy','Delete Payroll Items','Payroll',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(236,NULL,'payroll.payroll.templates.index','View Templates','Payroll',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(237,NULL,'payroll.payroll.templates.create','Create Templates','Payroll',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(238,NULL,'payroll.payroll.templates.edit','Edit Templates','Payroll',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(239,NULL,'payroll.payroll.templates.destroy','Delete Templates','Payroll',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(240,NULL,'customfield.custom_fields.index','View Custom Fields','CustomField',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(241,NULL,'customfield.custom_fields.create','Create Custom Field','CustomField',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(242,NULL,'customfield.custom_fields.edit','Edit Custom Field','CustomField',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(243,NULL,'customfield.custom_fields.destroy','Delete Custom Field','CustomField',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(244,NULL,'expense.expenses.index','View Expenses','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(245,NULL,'expense.expenses.create','Create Expenses','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(246,NULL,'expense.expenses.edit','Edit Expenses','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(247,NULL,'expense.expenses.destroy','Delete Expenses','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(248,NULL,'expense.expenses.types.index','View Expense Types','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(249,NULL,'expense.expenses.types.create','Create Expense Types','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(250,NULL,'expense.expenses.types.edit','Edit Expense Types','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(251,NULL,'expense.expenses.types.destroy','Delete Expense Types','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(252,NULL,'expense.expenses.notes.index','View Expense Notes','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(253,NULL,'expense.expenses.notes.create','Create Expense Notes','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(254,NULL,'expense.expenses.notes.edit','Edit Expense Notes','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(255,NULL,'expense.expenses.notes.destroy','Delete Expense Notes','Expense',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(256,NULL,'income.income.index','View Income','Income',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(257,NULL,'income.income.create','Create Income','Income',NULL,'web','2023-11-08 14:34:50','2023-11-08 14:34:50'),(258,NULL,'income.income.edit','Edit Income','Income',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(259,NULL,'income.income.destroy','Delete Income','Income',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(260,NULL,'income.income.types.index','View Income Types','Income',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(261,NULL,'income.income.types.create','Create Income Types','Income',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(262,NULL,'income.income.types.edit','Edit Income Types','Income',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(263,NULL,'income.income.types.destroy','Delete Income Types','Income',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(264,NULL,'income.income.notes.index','View Income Notes','Income',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(265,NULL,'income.income.notes.create','Create Income Notes','Income',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(266,NULL,'income.income.notes.edit','Edit Income Notes','Income',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(267,NULL,'income.income.notes.destroy','Delete Income Notes','Income',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(268,NULL,'upgrade.upgrades.index','View Upgrade Page','Upgrade',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(269,NULL,'upgrade.upgrades.manage','Manage Upgrades','Upgrade',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(270,NULL,'share.shares.index','View Shares','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(271,NULL,'share.shares.create','Create Shares','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(272,NULL,'share.shares.edit','Edit Shares','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(273,NULL,'share.shares.destroy','Delete Shares','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(274,NULL,'share.shares.approve_shares','Approve Shares','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(275,NULL,'share.shares.activate_shares','Activate Shares','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(276,NULL,'share.shares.close_shares','Close Shares','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(277,NULL,'share.shares.products.index','View Share Products','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(278,NULL,'share.shares.products.create','Create Share Products','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(279,NULL,'share.shares.products.edit','Edit Share Products','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(280,NULL,'share.shares.products.destroy','Delete Share Products','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(281,NULL,'share.shares.transactions.index','View Transactions','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(282,NULL,'share.shares.transactions.create','Create Transactions','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(283,NULL,'share.shares.transactions.edit','Edit Transactions','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(284,NULL,'share.shares.transactions.destroy','Delete/Reverse Transactions','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(285,NULL,'share.shares.notes.index','View Share Notes','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(286,NULL,'share.shares.notes.create','Create Share Notes','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(287,NULL,'share.shares.notes.edit','Edit Share Notes','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(288,NULL,'share.shares.notes.destroy','Delete Share Notes','Share',NULL,'web','2023-11-08 14:34:51','2023-11-08 14:34:51'),(289,NULL,'share.shares.charges.index','View Share Charges','Share',NULL,'web','2023-11-08 14:34:52','2023-11-08 14:34:52'),(290,NULL,'share.shares.charges.create','Create Share Charges','Share',NULL,'web','2023-11-08 14:34:52','2023-11-08 14:34:52'),(291,NULL,'share.shares.charges.edit','Edit Share Charges','Share',NULL,'web','2023-11-08 14:34:52','2023-11-08 14:34:52'),(292,NULL,'share.shares.charges.destroy','Delete Share Charges','Share',NULL,'web','2023-11-08 14:34:52','2023-11-08 14:34:52'),(293,NULL,'share.shares.files.index','View Share Files','Share',NULL,'web','2023-11-08 14:34:52','2023-11-08 14:34:52'),(294,NULL,'share.shares.files.create','Create Share Files','Share',NULL,'web','2023-11-08 14:34:52','2023-11-08 14:34:52'),(295,NULL,'share.shares.files.edit','Edit Share Files','Share',NULL,'web','2023-11-08 14:34:52','2023-11-08 14:34:52'),(296,NULL,'share.shares.files.destroy','Delete Share Files','Share',NULL,'web','2023-11-08 14:34:52','2023-11-08 14:34:52'),(297,NULL,'activitylog.activity_logs.index','View Activity Logs','Activitylog',NULL,'web','2023-11-08 14:34:52','2023-11-08 14:34:52'),(298,NULL,'activitylog.activity_logs.destroy','Delete Activity Logs','Activitylog',NULL,'web','2023-11-08 14:34:52','2023-11-08 14:34:52');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `professions`
--

DROP TABLE IF EXISTS `professions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `professions` (
                               `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                               `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professions`
--

LOCK TABLES `professions` WRITE;
/*!40000 ALTER TABLE `professions` DISABLE KEYS */;
/*!40000 ALTER TABLE `professions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
                                        `permission_id` int unsigned NOT NULL,
                                        `role_id` int unsigned NOT NULL,
                                        PRIMARY KEY (`permission_id`,`role_id`),
                                        KEY `role_has_permissions_role_id_foreign` (`role_id`),
                                        CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
                                        CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(69,1),(70,1),(71,1),(72,1),(73,1),(74,1),(75,1),(76,1),(77,1),(78,1),(79,1),(80,1),(81,1),(82,1),(83,1),(84,1),(85,1),(86,1),(87,1),(88,1),(89,1),(90,1),(91,1),(92,1),(93,1),(94,1),(95,1),(96,1),(97,1),(98,1),(99,1),(100,1),(101,1),(102,1),(103,1),(104,1),(105,1),(106,1),(107,1),(108,1),(109,1),(110,1),(111,1),(112,1),(113,1),(114,1),(115,1),(116,1),(117,1),(118,1),(119,1),(120,1),(121,1),(122,1),(123,1),(124,1),(125,1),(126,1),(127,1),(128,1),(129,1),(130,1),(131,1),(132,1),(133,1),(134,1),(135,1),(136,1),(137,1),(138,1),(139,1),(140,1),(141,1),(142,1),(143,1),(144,1),(145,1),(146,1),(147,1),(148,1),(149,1),(150,1),(151,1),(152,1),(153,1),(154,1),(155,1),(156,1),(157,1),(158,1),(159,1),(160,1),(161,1),(162,1),(163,1),(164,1),(165,1),(166,1),(167,1),(168,1),(169,1),(170,1),(171,1),(172,1),(173,1),(174,1),(175,1),(176,1),(177,1),(178,1),(179,1),(180,1),(181,1),(182,1),(183,1),(184,1),(185,1),(186,1),(187,1),(188,1),(189,1),(190,1),(191,1),(192,1),(193,1),(194,1),(195,1),(196,1),(197,1),(198,1),(199,1),(200,1),(201,1),(202,1),(203,1),(204,1),(205,1),(206,1),(207,1),(208,1),(209,1),(210,1),(211,1),(212,1),(213,1),(214,1),(215,1),(216,1),(217,1),(218,1),(219,1),(220,1),(221,1),(222,1),(223,1),(224,1),(225,1),(226,1),(227,1),(228,1),(229,1),(230,1),(231,1),(232,1),(233,1),(234,1),(235,1),(236,1),(237,1),(238,1),(239,1),(240,1),(241,1),(242,1),(243,1),(244,1),(245,1),(246,1),(247,1),(248,1),(249,1),(250,1),(251,1),(252,1),(253,1),(254,1),(255,1),(256,1),(257,1),(258,1),(259,1),(260,1),(261,1),(262,1),(263,1),(264,1),(265,1),(266,1),(267,1),(268,1),(269,1),(270,1),(271,1),(272,1),(273,1),(274,1),(275,1),(276,1),(277,1),(278,1),(279,1),(280,1),(281,1),(282,1),(283,1),(284,1),(285,1),(286,1),(287,1),(288,1),(289,1),(290,1),(291,1),(292,1),(293,1),(294,1),(295,1),(296,1),(297,1),(298,1);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
                         `id` int unsigned NOT NULL AUTO_INCREMENT,
                         `is_system` tinyint NOT NULL DEFAULT '0',
                         `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,1,'admin','web','2023-11-08 14:34:37','2023-11-08 14:34:37'),(2,1,'client','web','2023-11-08 14:34:37','2023-11-08 14:34:37');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings`
--

DROP TABLE IF EXISTS `savings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings` (
                           `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                           `created_by_id` bigint unsigned DEFAULT NULL,
                           `currency_id` bigint unsigned NOT NULL,
                           `savings_officer_id` bigint unsigned DEFAULT NULL,
                           `savings_product_id` bigint unsigned DEFAULT NULL,
                           `client_type` enum('client','group') COLLATE utf8mb4_unicode_ci DEFAULT 'client',
                           `client_id` bigint unsigned DEFAULT NULL,
                           `group_id` bigint unsigned DEFAULT NULL,
                           `branch_id` bigint unsigned DEFAULT NULL,
                           `account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `external_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `decimals` int DEFAULT NULL,
                           `interest_rate` decimal(65,6) NOT NULL,
                           `interest_rate_type` enum('day','week','month','year') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'year',
                           `compounding_period` enum('daily','weekly','monthly','biannual','annually') COLLATE utf8mb4_unicode_ci NOT NULL,
                           `interest_posting_period_type` enum('daily','weekly','monthly','biannual','annually') COLLATE utf8mb4_unicode_ci NOT NULL,
                           `interest_calculation_type` enum('daily_balance','average_daily_balance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'daily_balance',
                           `balance_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                           `total_deposits_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                           `total_withdrawals_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                           `total_interest_posted_derived` decimal(65,6) NOT NULL DEFAULT '0.000000',
                           `minimum_balance_for_interest_calculation` decimal(65,6) NOT NULL DEFAULT '0.000000',
                           `lockin_period` int NOT NULL DEFAULT '0',
                           `lockin_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'days',
                           `automatic_opening_balance` decimal(65,6) NOT NULL DEFAULT '0.000000',
                           `allow_overdraft` tinyint NOT NULL DEFAULT '0',
                           `overdraft_limit` decimal(65,6) DEFAULT NULL,
                           `overdraft_interest_rate` decimal(65,6) DEFAULT NULL,
                           `minimum_overdraft_for_interest` decimal(65,6) DEFAULT NULL,
                           `submitted_on_date` date DEFAULT NULL,
                           `submitted_by_user_id` bigint unsigned DEFAULT NULL,
                           `approved_on_date` date DEFAULT NULL,
                           `approved_by_user_id` bigint unsigned DEFAULT NULL,
                           `approved_notes` text COLLATE utf8mb4_unicode_ci,
                           `activated_on_date` date DEFAULT NULL,
                           `activated_by_user_id` bigint unsigned DEFAULT NULL,
                           `activated_notes` text COLLATE utf8mb4_unicode_ci,
                           `rejected_on_date` date DEFAULT NULL,
                           `rejected_by_user_id` bigint unsigned DEFAULT NULL,
                           `rejected_notes` text COLLATE utf8mb4_unicode_ci,
                           `dormant_on_date` date DEFAULT NULL,
                           `dormant_by_user_id` bigint unsigned DEFAULT NULL,
                           `dormant_notes` text COLLATE utf8mb4_unicode_ci,
                           `closed_on_date` date DEFAULT NULL,
                           `closed_by_user_id` bigint unsigned DEFAULT NULL,
                           `closed_notes` text COLLATE utf8mb4_unicode_ci,
                           `inactive_on_date` date DEFAULT NULL,
                           `inactive_by_user_id` bigint unsigned DEFAULT NULL,
                           `inactive_notes` text COLLATE utf8mb4_unicode_ci,
                           `withdrawn_on_date` date DEFAULT NULL,
                           `withdrawn_by_user_id` bigint unsigned DEFAULT NULL,
                           `withdrawn_notes` text COLLATE utf8mb4_unicode_ci,
                           `status` enum('pending','approved','active','withdrawn','rejected','closed','inactive','dormant','submitted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
                           `start_interest_posting_date` date DEFAULT NULL,
                           `next_interest_posting_date` date DEFAULT NULL,
                           `start_interest_calculation_date` date DEFAULT NULL,
                           `next_interest_calculation_date` date DEFAULT NULL,
                           `last_interest_calculation_date` date DEFAULT NULL,
                           `last_interest_posting_date` date DEFAULT NULL,
                           `calculated_interest` decimal(65,6) DEFAULT NULL,
                           `created_at` timestamp NULL DEFAULT NULL,
                           `updated_at` timestamp NULL DEFAULT NULL,
                           PRIMARY KEY (`id`),
                           KEY `savings_client_id_index` (`client_id`),
                           KEY `savings_branch_id_index` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings`
--

LOCK TABLES `savings` WRITE;
/*!40000 ALTER TABLE `savings` DISABLE KEYS */;
/*!40000 ALTER TABLE `savings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_charge_options`
--

DROP TABLE IF EXISTS `savings_charge_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_charge_options` (
                                          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                          `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `translated_name` text COLLATE utf8mb4_unicode_ci,
                                          `active` tinyint NOT NULL DEFAULT '1',
                                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_charge_options`
--

LOCK TABLES `savings_charge_options` WRITE;
/*!40000 ALTER TABLE `savings_charge_options` DISABLE KEYS */;
INSERT INTO `savings_charge_options` VALUES (1,'Flat','Flat',1),(2,'Percentage of amount','Percentage of amount',1),(3,'Percentage of savings balance','Percentage of savings balance',1);
/*!40000 ALTER TABLE `savings_charge_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_charge_types`
--

DROP TABLE IF EXISTS `savings_charge_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_charge_types` (
                                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                        `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `translated_name` text COLLATE utf8mb4_unicode_ci,
                                        `active` tinyint NOT NULL DEFAULT '1',
                                        PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_charge_types`
--

LOCK TABLES `savings_charge_types` WRITE;
/*!40000 ALTER TABLE `savings_charge_types` DISABLE KEYS */;
INSERT INTO `savings_charge_types` VALUES (1,'Savings Activation','Savings Activation',1),(2,'Specified Due Date','Specified Due Date',1),(3,'Withdrawal Fee','Withdrawal Fee',1),(4,'Annual Fee','Annual Fee',1),(5,'Monthly Fee','Monthly Fee',1),(6,'Inactivity Fee','Inactivity Fee',1),(7,'Quarterly Fee','Quarterly Fee',1);
/*!40000 ALTER TABLE `savings_charge_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_charges`
--

DROP TABLE IF EXISTS `savings_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_charges` (
                                   `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                   `created_by_id` bigint unsigned DEFAULT NULL,
                                   `currency_id` bigint unsigned NOT NULL,
                                   `savings_charge_type_id` bigint unsigned NOT NULL,
                                   `savings_charge_option_id` bigint unsigned NOT NULL,
                                   `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `amount` decimal(65,6) NOT NULL,
                                   `min_amount` decimal(65,6) DEFAULT NULL,
                                   `max_amount` decimal(65,6) DEFAULT NULL,
                                   `payment_mode` enum('regular','account_transfer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
                                   `schedule` tinyint NOT NULL DEFAULT '0',
                                   `schedule_frequency` int DEFAULT NULL,
                                   `schedule_frequency_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `active` tinyint NOT NULL DEFAULT '0',
                                   `allow_override` tinyint NOT NULL DEFAULT '0',
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL,
                                   PRIMARY KEY (`id`),
                                   KEY `savings_charges_currency_id_foreign` (`currency_id`),
                                   KEY `savings_charges_savings_charge_type_id_foreign` (`savings_charge_type_id`),
                                   KEY `savings_charges_savings_charge_option_id_foreign` (`savings_charge_option_id`),
                                   KEY `savings_charges_created_by_id_foreign` (`created_by_id`),
                                   CONSTRAINT `savings_charges_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
                                   CONSTRAINT `savings_charges_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
                                   CONSTRAINT `savings_charges_savings_charge_option_id_foreign` FOREIGN KEY (`savings_charge_option_id`) REFERENCES `savings_charge_options` (`id`),
                                   CONSTRAINT `savings_charges_savings_charge_type_id_foreign` FOREIGN KEY (`savings_charge_type_id`) REFERENCES `savings_charge_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_charges`
--

LOCK TABLES `savings_charges` WRITE;
/*!40000 ALTER TABLE `savings_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `savings_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_file_types`
--

DROP TABLE IF EXISTS `savings_file_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_file_types` (
                                      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                      `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_file_types`
--

LOCK TABLES `savings_file_types` WRITE;
/*!40000 ALTER TABLE `savings_file_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `savings_file_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_files`
--

DROP TABLE IF EXISTS `savings_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_files` (
                                 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                 `created_by_id` bigint unsigned DEFAULT NULL,
                                 `savings_file_type_id` bigint unsigned DEFAULT NULL,
                                 `savings_id` bigint unsigned DEFAULT NULL,
                                 `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `description` text COLLATE utf8mb4_unicode_ci,
                                 `size` int DEFAULT NULL,
                                 `link` text COLLATE utf8mb4_unicode_ci,
                                 `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL,
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_files`
--

LOCK TABLES `savings_files` WRITE;
/*!40000 ALTER TABLE `savings_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `savings_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_linked_charges`
--

DROP TABLE IF EXISTS `savings_linked_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_linked_charges` (
                                          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                          `created_by_id` bigint unsigned DEFAULT NULL,
                                          `currency_id` bigint unsigned DEFAULT NULL,
                                          `savings_id` bigint unsigned DEFAULT NULL,
                                          `savings_charge_id` bigint unsigned DEFAULT NULL,
                                          `savings_charge_type_id` bigint unsigned DEFAULT NULL,
                                          `savings_charge_option_id` bigint unsigned DEFAULT NULL,
                                          `savings_transaction_id` bigint unsigned DEFAULT NULL,
                                          `name` text COLLATE utf8mb4_unicode_ci,
                                          `amount` decimal(65,6) NOT NULL,
                                          `calculated_amount` decimal(65,6) DEFAULT NULL,
                                          `paid_amount` decimal(65,6) DEFAULT NULL,
                                          `active` tinyint NOT NULL DEFAULT '0',
                                          `waived` tinyint NOT NULL DEFAULT '0',
                                          `is_paid` tinyint NOT NULL DEFAULT '0',
                                          `submitted_on` date DEFAULT NULL,
                                          `created_at` timestamp NULL DEFAULT NULL,
                                          `updated_at` timestamp NULL DEFAULT NULL,
                                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_linked_charges`
--

LOCK TABLES `savings_linked_charges` WRITE;
/*!40000 ALTER TABLE `savings_linked_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `savings_linked_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_product_linked_charges`
--

DROP TABLE IF EXISTS `savings_product_linked_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_product_linked_charges` (
                                                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                                  `savings_product_id` bigint unsigned NOT NULL,
                                                  `savings_charge_id` bigint unsigned NOT NULL,
                                                  `created_at` timestamp NULL DEFAULT NULL,
                                                  `updated_at` timestamp NULL DEFAULT NULL,
                                                  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_product_linked_charges`
--

LOCK TABLES `savings_product_linked_charges` WRITE;
/*!40000 ALTER TABLE `savings_product_linked_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `savings_product_linked_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_products`
--

DROP TABLE IF EXISTS `savings_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_products` (
                                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                    `created_by_id` bigint unsigned DEFAULT NULL,
                                    `currency_id` bigint unsigned NOT NULL,
                                    `savings_reference_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                    `overdraft_portfolio_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                    `savings_control_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                    `interest_on_savings_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                    `write_off_savings_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                    `income_from_fees_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                    `income_from_penalties_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                    `income_from_interest_overdraft_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                    `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                    `short_name` text COLLATE utf8mb4_unicode_ci,
                                    `description` text COLLATE utf8mb4_unicode_ci,
                                    `decimals` int DEFAULT NULL,
                                    `savings_category` enum('voluntary','compulsory') COLLATE utf8mb4_unicode_ci NOT NULL,
                                    `auto_create` tinyint NOT NULL DEFAULT '0',
                                    `minimum_interest_rate` decimal(65,6) NOT NULL,
                                    `default_interest_rate` decimal(65,6) NOT NULL,
                                    `maximum_interest_rate` decimal(65,6) NOT NULL,
                                    `interest_rate_type` enum('day','week','month','year') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'year',
                                    `compounding_period` enum('daily','weekly','monthly','biannual','annually') COLLATE utf8mb4_unicode_ci NOT NULL,
                                    `interest_posting_period_type` enum('daily','weekly','monthly','biannual','annually') COLLATE utf8mb4_unicode_ci NOT NULL,
                                    `interest_calculation_type` enum('daily_balance','average_daily_balance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'daily_balance',
                                    `automatic_opening_balance` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                    `minimum_balance_for_interest_calculation` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                    `lockin_period` int NOT NULL DEFAULT '0',
                                    `lockin_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'days',
                                    `minimum_balance` decimal(65,6) NOT NULL DEFAULT '0.000000',
                                    `allow_overdraft` tinyint NOT NULL DEFAULT '0',
                                    `overdraft_limit` decimal(65,6) DEFAULT NULL,
                                    `overdraft_interest_rate` decimal(65,6) DEFAULT NULL,
                                    `minimum_overdraft_for_interest` decimal(65,6) DEFAULT NULL,
                                    `days_in_year` enum('actual','360','365','364') COLLATE utf8mb4_unicode_ci DEFAULT '365',
                                    `days_in_month` enum('actual','30','31') COLLATE utf8mb4_unicode_ci DEFAULT '30',
                                    `accounting_rule` enum('none','cash') COLLATE utf8mb4_unicode_ci DEFAULT 'none',
                                    `active` tinyint NOT NULL DEFAULT '0',
                                    `created_at` timestamp NULL DEFAULT NULL,
                                    `updated_at` timestamp NULL DEFAULT NULL,
                                    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_products`
--

LOCK TABLES `savings_products` WRITE;
/*!40000 ALTER TABLE `savings_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `savings_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_transaction_types`
--

DROP TABLE IF EXISTS `savings_transaction_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_transaction_types` (
                                             `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                             `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                             `translated_name` text COLLATE utf8mb4_unicode_ci,
                                             `active` tinyint NOT NULL DEFAULT '1',
                                             PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_transaction_types`
--

LOCK TABLES `savings_transaction_types` WRITE;
/*!40000 ALTER TABLE `savings_transaction_types` DISABLE KEYS */;
INSERT INTO `savings_transaction_types` VALUES (1,'Deposit','Deposit',1),(2,'Withdrawal','Withdrawal',1),(3,'Dividend','Dividend',1),(4,'Waive Interest','Waive Interest',1),(5,'Guarantee','Guarantee',1),(6,'Guarantee Restored','Guarantee Restored',1),(7,'Loan Repayment','Loan Repayment',1),(8,'Transfer','Transfer',1),(9,'Waive Charges','Waive Charges',1),(10,'Apply Charges','Apply Charges',1),(11,'Apply Interest','Apply Interest',1),(12,'Pay Charge','Pay Charge',1);
/*!40000 ALTER TABLE `savings_transaction_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `savings_transactions`
--

DROP TABLE IF EXISTS `savings_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `savings_transactions` (
                                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                        `savings_id` bigint unsigned NOT NULL,
                                        `created_by_id` bigint unsigned DEFAULT NULL,
                                        `branch_id` bigint unsigned DEFAULT NULL,
                                        `savings_linked_charge_id` bigint unsigned DEFAULT NULL,
                                        `payment_detail_id` bigint unsigned DEFAULT NULL,
                                        `name` text COLLATE utf8mb4_unicode_ci,
                                        `amount` decimal(65,6) NOT NULL,
                                        `credit` decimal(65,6) DEFAULT NULL,
                                        `debit` decimal(65,6) DEFAULT NULL,
                                        `balance` decimal(65,6) DEFAULT NULL,
                                        `savings_transaction_type_id` bigint unsigned NOT NULL,
                                        `reversed` tinyint NOT NULL DEFAULT '0',
                                        `reversible` tinyint NOT NULL DEFAULT '0',
                                        `submitted_on` date DEFAULT NULL,
                                        `created_on` date DEFAULT NULL,
                                        `description` text COLLATE utf8mb4_unicode_ci,
                                        `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                        `gateway_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                        `payment_gateway_data` text COLLATE utf8mb4_unicode_ci,
                                        `online_transaction` tinyint NOT NULL DEFAULT '0',
                                        `status` enum('pending','approved','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                                        `created_at` timestamp NULL DEFAULT NULL,
                                        `updated_at` timestamp NULL DEFAULT NULL,
                                        PRIMARY KEY (`id`),
                                        KEY `savings_transactions_savings_id_index` (`savings_id`),
                                        KEY `savings_transactions_branch_id_index` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `savings_transactions`
--

LOCK TABLES `savings_transactions` WRITE;
/*!40000 ALTER TABLE `savings_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `savings_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
                            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                            `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                            `setting_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `module` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                            `setting_value` text COLLATE utf8mb4_unicode_ci,
                            `order` int DEFAULT NULL,
                            `category` enum('email','sms','general','system','update','other') COLLATE utf8mb4_unicode_ci NOT NULL,
                            `type` enum('text','textarea','number','select','radio','date','select_db','radio_db','select_multiple','select_multiple_db','checkbox','checkbox_db','file','info') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
                            `options` text COLLATE utf8mb4_unicode_ci,
                            `rules` text COLLATE utf8mb4_unicode_ci,
                            `class` text COLLATE utf8mb4_unicode_ci,
                            `required` tinyint NOT NULL DEFAULT '0',
                            `db_columns` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                            `info` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                            `displayed` tinyint NOT NULL DEFAULT '1',
                            `created_at` timestamp NULL DEFAULT NULL,
                            `updated_at` timestamp NULL DEFAULT NULL,
                            PRIMARY KEY (`id`),
                            UNIQUE KEY `settings_setting_key_unique` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'Company Name','core.company_name','Core','Ultimate Loan Manager',NULL,'general','text','','','',1,'',NULL,1,NULL,NULL),(2,'Company Address','core.company_address','Core','',NULL,'general','textarea','','','',0,'',NULL,1,NULL,NULL),(3,'Company Country','core.company_country','Core','',NULL,'general','select_db','countries','','select2',0,'id,name',NULL,1,NULL,NULL),(4,'Timezone','core.timezone','Core','1',NULL,'general','select_db','timezones','','select2',1,'id,zone_name',NULL,1,NULL,NULL),(5,'System Version','core.system_version','Core','3.0',NULL,'update','info','','','',1,'',NULL,1,NULL,NULL),(6,'Company Email','core.company_email','Core','nonreply@company.com',NULL,'general','text','','','',1,'',NULL,1,NULL,NULL),(7,'Company Logo','core.company_logo','Core','',NULL,'general','file','jpeg,jpg,bmp,png','nullable|file|mimes:jpeg,jpg,bmp,png','',0,'',NULL,1,NULL,NULL),(8,'Site Online','core.site_online','Core','yes',NULL,'system','select','yes,no','','',1,'',NULL,1,NULL,NULL),(9,'Console Last Run','core.console_last_run','Core','',NULL,'system','info','','','',1,'',NULL,1,NULL,NULL),(10,'Update Url','core.update_url','Core','http://webstudio.co.zw/ulm/update',NULL,'general','info','','','',1,'',NULL,0,NULL,NULL),(11,'Auto Download Update','core.auto_download_update','Core','no',NULL,'system','select','yes,no','','',1,'',NULL,1,NULL,NULL),(12,'Update last checked','core.update_last_checked','Core','',NULL,'system','info','','','',1,'',NULL,1,NULL,NULL),(13,'Extra Javascript','core.extra_javascript','Core','',NULL,'system','textarea','','','',0,'',NULL,1,NULL,NULL),(14,'Extra Styles','core.extra_styles','Core','',NULL,'system','textarea','','','',0,'',NULL,1,NULL,NULL),(15,'Demo Mode','core.demo_mode','Core','no',NULL,'system','select','yes,no','','',1,'',NULL,1,NULL,NULL),(16,'Purchase Code','core.purchase_code','Core','',NULL,'system','text','','','',0,'',NULL,1,NULL,NULL),(17,'Purchase Code Type','core.purchase_code_type','Core','',NULL,'system','text','','','',0,'',NULL,0,NULL,NULL),(18,'Installed IP Address','core.installed_ip_address','Core','',NULL,'system','text','','','',0,'',NULL,0,NULL,NULL),(19,'License Details','core.license_details','Core','',NULL,'system','text','','','',0,'',NULL,0,NULL,NULL),(20,'Active Theme','core.active_theme','Core','AdminLTE',NULL,'system','text','','','',0,'',NULL,0,NULL,NULL),(21,'Registration Enabled','user.enable_registration','User','no',NULL,'system','select','yes,no',NULL,'',1,'',NULL,1,NULL,NULL),(22,'Enable Google recaptcha','user.enable_google_recaptcha','User','no',NULL,'system','select','yes,no',NULL,'',1,'',NULL,1,NULL,NULL),(23,'Google recaptcha site key','user.google_recaptcha_site_key','User','',NULL,'system','text','',NULL,'',0,'',NULL,1,NULL,NULL),(24,'Google recaptcha secret key','user.google_recaptcha_secret_key','User','',NULL,'system','text','',NULL,'',0,'',NULL,1,NULL,NULL),(25,'Client In-Active Days','client.inactive_days','Client','',NULL,'general','text','','','',0,'',NULL,1,NULL,NULL),(26,'Client Reference Prefix','client.reference_prefix','Client','CL',NULL,'system','text','','','',0,'',NULL,1,NULL,NULL),(27,'Client Reference Format','client.reference_format','Client','YEAR/Sequence Number (SL/2014/001)',NULL,'system','select','YEAR/Sequence Number (SL/2014/001),YEAR/MONTH/Sequence Number (SL/2014/08/001),Sequence Number,Random Number,Branch Sequence Number','','',1,'',NULL,1,NULL,NULL),(28,'Savings Reference Prefix','savings.reference_prefix','Savings','',NULL,'system','text','','','',0,'',NULL,1,NULL,NULL),(29,'Savings Reference Format','savings.reference_format','Savings','YEAR/Sequence Number (SL/2014/001)',NULL,'system','select','YEAR/Sequence Number (SL/2014/001),YEAR/MONTH/Sequence Number (SL/2014/08/001),Sequence Number,Random Number,Branch Product Sequence Number','','',1,'',NULL,1,NULL,NULL),(30,'Loan Reference Prefix','loan.reference_prefix','Loan','L',NULL,'system','text','','','',0,'',NULL,1,NULL,NULL),(31,'Loan Reference Format','loan.reference_format','Loan','YEAR/Sequence Number (SL/2014/001)',NULL,'system','select','YEAR/Sequence Number (SL/2014/001),YEAR/MONTH/Sequence Number (SL/2014/08/001),Sequence Number,Random Number,Branch Product Sequence Number','','',1,'',NULL,1,NULL,NULL),(32,'SMS Enabled','communication.sms_enabled','Communication','no',NULL,'sms','select','yes,no','','',1,'',NULL,1,NULL,NULL),(33,'Active SMS Gateway','communication.active_sms_gateway','Communication','1',NULL,'sms','select_db','sms_gateways','','select2',0,'id,name',NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `share_charge_options`
--

DROP TABLE IF EXISTS `share_charge_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `share_charge_options` (
                                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `translated_name` text COLLATE utf8mb4_unicode_ci,
                                        `active` tinyint NOT NULL DEFAULT '1',
                                        `created_at` timestamp NULL DEFAULT NULL,
                                        `updated_at` timestamp NULL DEFAULT NULL,
                                        PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `share_charge_options`
--

LOCK TABLES `share_charge_options` WRITE;
/*!40000 ALTER TABLE `share_charge_options` DISABLE KEYS */;
INSERT INTO `share_charge_options` VALUES (1,'Flat','Flat',1,NULL,NULL),(2,'Percentage of amount','Percentage of amount',1,NULL,NULL);
/*!40000 ALTER TABLE `share_charge_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `share_charge_types`
--

DROP TABLE IF EXISTS `share_charge_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `share_charge_types` (
                                      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                      `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                      `translated_name` text COLLATE utf8mb4_unicode_ci,
                                      `active` tinyint NOT NULL DEFAULT '1',
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `share_charge_types`
--

LOCK TABLES `share_charge_types` WRITE;
/*!40000 ALTER TABLE `share_charge_types` DISABLE KEYS */;
INSERT INTO `share_charge_types` VALUES (1,'Share Account Activation','Share Account Activation',1,NULL,NULL),(2,'Share Purchase','Share Purchase',1,NULL,NULL),(3,'Share Redeem','Share Redeem',1,NULL,NULL);
/*!40000 ALTER TABLE `share_charge_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `share_charges`
--

DROP TABLE IF EXISTS `share_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `share_charges` (
                                 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                 `created_by_id` bigint unsigned DEFAULT NULL,
                                 `currency_id` bigint unsigned NOT NULL,
                                 `share_charge_type_id` bigint unsigned NOT NULL,
                                 `share_charge_option_id` bigint unsigned NOT NULL,
                                 `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `amount` decimal(65,6) NOT NULL,
                                 `min_amount` decimal(65,6) DEFAULT NULL,
                                 `max_amount` decimal(65,6) DEFAULT NULL,
                                 `active` tinyint NOT NULL DEFAULT '0',
                                 `allow_override` tinyint NOT NULL DEFAULT '0',
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL,
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `share_charges`
--

LOCK TABLES `share_charges` WRITE;
/*!40000 ALTER TABLE `share_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `share_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `share_linked_charges`
--

DROP TABLE IF EXISTS `share_linked_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `share_linked_charges` (
                                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                        `created_by_id` bigint unsigned DEFAULT NULL,
                                        `currency_id` bigint unsigned DEFAULT NULL,
                                        `share_id` bigint unsigned DEFAULT NULL,
                                        `share_charge_id` bigint unsigned DEFAULT NULL,
                                        `share_charge_type_id` bigint unsigned DEFAULT NULL,
                                        `share_charge_option_id` bigint unsigned DEFAULT NULL,
                                        `share_transaction_id` bigint unsigned DEFAULT NULL,
                                        `name` text COLLATE utf8mb4_unicode_ci,
                                        `amount` decimal(65,6) NOT NULL,
                                        `calculated_amount` decimal(65,6) DEFAULT NULL,
                                        `paid_amount` decimal(65,6) DEFAULT NULL,
                                        `active` tinyint NOT NULL DEFAULT '0',
                                        `waived` tinyint NOT NULL DEFAULT '0',
                                        `is_paid` tinyint NOT NULL DEFAULT '0',
                                        `submitted_on` date DEFAULT NULL,
                                        `created_at` timestamp NULL DEFAULT NULL,
                                        `updated_at` timestamp NULL DEFAULT NULL,
                                        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `share_linked_charges`
--

LOCK TABLES `share_linked_charges` WRITE;
/*!40000 ALTER TABLE `share_linked_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `share_linked_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `share_market_periods`
--

DROP TABLE IF EXISTS `share_market_periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `share_market_periods` (
                                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                        `created_by_id` bigint unsigned DEFAULT NULL,
                                        `share_product_id` bigint unsigned DEFAULT NULL,
                                        `from_date` date DEFAULT NULL,
                                        `to_date` date DEFAULT NULL,
                                        `nominal_price` decimal(65,6) DEFAULT NULL,
                                        `created_at` timestamp NULL DEFAULT NULL,
                                        `updated_at` timestamp NULL DEFAULT NULL,
                                        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `share_market_periods`
--

LOCK TABLES `share_market_periods` WRITE;
/*!40000 ALTER TABLE `share_market_periods` DISABLE KEYS */;
/*!40000 ALTER TABLE `share_market_periods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `share_product_linked_charges`
--

DROP TABLE IF EXISTS `share_product_linked_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `share_product_linked_charges` (
                                                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                                `share_product_id` bigint unsigned NOT NULL,
                                                `share_charge_id` bigint unsigned NOT NULL,
                                                `created_at` timestamp NULL DEFAULT NULL,
                                                `updated_at` timestamp NULL DEFAULT NULL,
                                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `share_product_linked_charges`
--

LOCK TABLES `share_product_linked_charges` WRITE;
/*!40000 ALTER TABLE `share_product_linked_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `share_product_linked_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `share_products`
--

DROP TABLE IF EXISTS `share_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `share_products` (
                                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                  `created_by_id` bigint unsigned DEFAULT NULL,
                                  `currency_id` bigint unsigned NOT NULL,
                                  `share_reference_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                  `share_suspense_control_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                  `equity_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                  `income_from_fees_chart_of_account_id` bigint unsigned DEFAULT NULL,
                                  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                  `short_name` text COLLATE utf8mb4_unicode_ci,
                                  `description` text COLLATE utf8mb4_unicode_ci,
                                  `decimals` int DEFAULT NULL,
                                  `total_shares` int DEFAULT NULL,
                                  `shares_to_be_issued` int DEFAULT NULL,
                                  `nominal_price` decimal(65,6) DEFAULT NULL,
                                  `capital_value` decimal(65,6) DEFAULT NULL,
                                  `minimum_shares_per_client` decimal(65,6) DEFAULT NULL,
                                  `default_shares_per_client` decimal(65,6) DEFAULT NULL,
                                  `maximum_shares_per_client` decimal(65,6) DEFAULT NULL,
                                  `minimum_active_period` int DEFAULT NULL,
                                  `minimum_active_period_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci DEFAULT 'days',
                                  `lockin_period` int NOT NULL DEFAULT '0',
                                  `lockin_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'days',
                                  `allow_dividends_for_inactive_clients` tinyint NOT NULL DEFAULT '0',
                                  `accounting_rule` enum('none','cash') COLLATE utf8mb4_unicode_ci DEFAULT 'none',
                                  `active` tinyint NOT NULL DEFAULT '0',
                                  `created_at` timestamp NULL DEFAULT NULL,
                                  `updated_at` timestamp NULL DEFAULT NULL,
                                  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `share_products`
--

LOCK TABLES `share_products` WRITE;
/*!40000 ALTER TABLE `share_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `share_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `share_transaction_types`
--

DROP TABLE IF EXISTS `share_transaction_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `share_transaction_types` (
                                           `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                           `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                           `translated_name` text COLLATE utf8mb4_unicode_ci,
                                           `active` tinyint NOT NULL DEFAULT '1',
                                           `created_at` timestamp NULL DEFAULT NULL,
                                           `updated_at` timestamp NULL DEFAULT NULL,
                                           PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `share_transaction_types`
--

LOCK TABLES `share_transaction_types` WRITE;
/*!40000 ALTER TABLE `share_transaction_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `share_transaction_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `share_transactions`
--

DROP TABLE IF EXISTS `share_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `share_transactions` (
                                      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                      `share_id` bigint unsigned NOT NULL,
                                      `created_by_id` bigint unsigned DEFAULT NULL,
                                      `branch_id` bigint unsigned DEFAULT NULL,
                                      `share_linked_charge_id` bigint unsigned DEFAULT NULL,
                                      `payment_detail_id` bigint unsigned DEFAULT NULL,
                                      `name` text COLLATE utf8mb4_unicode_ci,
                                      `amount` decimal(65,6) NOT NULL,
                                      `total_shares` decimal(65,6) DEFAULT NULL,
                                      `charge_amount` decimal(65,6) DEFAULT NULL,
                                      `credit` decimal(65,6) DEFAULT NULL,
                                      `debit` decimal(65,6) DEFAULT NULL,
                                      `balance` decimal(65,6) DEFAULT NULL,
                                      `share_transaction_type_id` bigint unsigned NOT NULL,
                                      `reversed` tinyint NOT NULL DEFAULT '0',
                                      `reversible` tinyint NOT NULL DEFAULT '0',
                                      `submitted_on` date DEFAULT NULL,
                                      `created_on` date DEFAULT NULL,
                                      `description` text COLLATE utf8mb4_unicode_ci,
                                      `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `gateway_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `payment_gateway_data` text COLLATE utf8mb4_unicode_ci,
                                      `online_transaction` tinyint NOT NULL DEFAULT '0',
                                      `status` enum('pending','approved','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      KEY `share_transactions_share_id_index` (`share_id`),
                                      KEY `share_transactions_branch_id_index` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `share_transactions`
--

LOCK TABLES `share_transactions` WRITE;
/*!40000 ALTER TABLE `share_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `share_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shares`
--

DROP TABLE IF EXISTS `shares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shares` (
                          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                          `created_by_id` bigint unsigned DEFAULT NULL,
                          `currency_id` bigint unsigned NOT NULL,
                          `share_officer_id` bigint unsigned DEFAULT NULL,
                          `share_product_id` bigint unsigned DEFAULT NULL,
                          `savings_id` bigint unsigned DEFAULT NULL,
                          `client_type` enum('client','group') COLLATE utf8mb4_unicode_ci DEFAULT 'client',
                          `client_id` bigint unsigned DEFAULT NULL,
                          `group_id` bigint unsigned DEFAULT NULL,
                          `branch_id` bigint unsigned DEFAULT NULL,
                          `account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `external_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `decimals` int DEFAULT NULL,
                          `total_shares` int DEFAULT NULL,
                          `nominal_price` decimal(65,6) DEFAULT NULL,
                          `minimum_active_period` int DEFAULT NULL,
                          `minimum_active_period_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci DEFAULT 'days',
                          `lockin_period` int NOT NULL DEFAULT '0',
                          `lockin_type` enum('days','weeks','months','years') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'days',
                          `allow_dividends_for_inactive_clients` tinyint NOT NULL DEFAULT '0',
                          `submitted_on_date` date DEFAULT NULL,
                          `application_date` date DEFAULT NULL,
                          `submitted_by_user_id` bigint unsigned DEFAULT NULL,
                          `approved_on_date` date DEFAULT NULL,
                          `approved_by_user_id` bigint unsigned DEFAULT NULL,
                          `approved_notes` text COLLATE utf8mb4_unicode_ci,
                          `activated_on_date` date DEFAULT NULL,
                          `activated_by_user_id` bigint unsigned DEFAULT NULL,
                          `activated_notes` text COLLATE utf8mb4_unicode_ci,
                          `rejected_on_date` date DEFAULT NULL,
                          `rejected_by_user_id` bigint unsigned DEFAULT NULL,
                          `rejected_notes` text COLLATE utf8mb4_unicode_ci,
                          `dormant_on_date` date DEFAULT NULL,
                          `dormant_by_user_id` bigint unsigned DEFAULT NULL,
                          `dormant_notes` text COLLATE utf8mb4_unicode_ci,
                          `closed_on_date` date DEFAULT NULL,
                          `closed_by_user_id` bigint unsigned DEFAULT NULL,
                          `closed_notes` text COLLATE utf8mb4_unicode_ci,
                          `inactive_on_date` date DEFAULT NULL,
                          `inactive_by_user_id` bigint unsigned DEFAULT NULL,
                          `inactive_notes` text COLLATE utf8mb4_unicode_ci,
                          `withdrawn_on_date` date DEFAULT NULL,
                          `withdrawn_by_user_id` bigint unsigned DEFAULT NULL,
                          `withdrawn_notes` text COLLATE utf8mb4_unicode_ci,
                          `status` enum('pending','approved','active','withdrawn','rejected','closed','inactive','dormant','submitted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
                          `created_at` timestamp NULL DEFAULT NULL,
                          `updated_at` timestamp NULL DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          KEY `shares_client_id_index` (`client_id`),
                          KEY `shares_branch_id_index` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shares`
--

LOCK TABLES `shares` WRITE;
/*!40000 ALTER TABLE `shares` DISABLE KEYS */;
/*!40000 ALTER TABLE `shares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_gateways`
--

DROP TABLE IF EXISTS `sms_gateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_gateways` (
                                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                `created_by_id` bigint unsigned DEFAULT NULL,
                                `name` text COLLATE utf8mb4_unicode_ci,
                                `to_name` text COLLATE utf8mb4_unicode_ci,
                                `url` text COLLATE utf8mb4_unicode_ci,
                                `msg_name` text COLLATE utf8mb4_unicode_ci,
                                `active` tinyint NOT NULL DEFAULT '0',
                                `is_current` tinyint NOT NULL DEFAULT '0',
                                `http_api` tinyint NOT NULL DEFAULT '1',
                                `class_name` text COLLATE utf8mb4_unicode_ci,
                                `key_one` text COLLATE utf8mb4_unicode_ci,
                                `key_one_description` text COLLATE utf8mb4_unicode_ci,
                                `key_two` text COLLATE utf8mb4_unicode_ci,
                                `key_two_description` text COLLATE utf8mb4_unicode_ci,
                                `key_three` text COLLATE utf8mb4_unicode_ci,
                                `key_three_description` text COLLATE utf8mb4_unicode_ci,
                                `key_four` text COLLATE utf8mb4_unicode_ci,
                                `key_four_description` text COLLATE utf8mb4_unicode_ci,
                                `notes` text COLLATE utf8mb4_unicode_ci,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`),
                                KEY `sms_gateways_created_by_id_foreign` (`created_by_id`),
                                CONSTRAINT `sms_gateways_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_gateways`
--

LOCK TABLES `sms_gateways` WRITE;
/*!40000 ALTER TABLE `sms_gateways` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_rates`
--

DROP TABLE IF EXISTS `tax_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax_rates` (
                             `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                             `name` text COLLATE utf8mb4_unicode_ci,
                             `code` text COLLATE utf8mb4_unicode_ci,
                             `amount` decimal(65,2) DEFAULT NULL,
                             `type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
                             `active` tinyint NOT NULL DEFAULT '1',
                             `description` text COLLATE utf8mb4_unicode_ci,
                             `created_at` timestamp NULL DEFAULT NULL,
                             `updated_at` timestamp NULL DEFAULT NULL,
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_rates`
--

LOCK TABLES `tax_rates` WRITE;
/*!40000 ALTER TABLE `tax_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_entries`
--

DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries` (
                                     `sequence` bigint unsigned NOT NULL AUTO_INCREMENT,
                                     `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `family_hash` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
                                     `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `created_at` datetime DEFAULT NULL,
                                     PRIMARY KEY (`sequence`),
                                     UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
                                     KEY `telescope_entries_batch_id_index` (`batch_id`),
                                     KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`),
                                     KEY `telescope_entries_family_hash_index` (`family_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_entries`
--

LOCK TABLES `telescope_entries` WRITE;
/*!40000 ALTER TABLE `telescope_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_entries_tags`
--

DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries_tags` (
                                          `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                          KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
                                          KEY `telescope_entries_tags_tag_index` (`tag`),
                                          CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_entries_tags`
--

LOCK TABLES `telescope_entries_tags` WRITE;
/*!40000 ALTER TABLE `telescope_entries_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_monitoring`
--

DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_monitoring` (
    `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_monitoring`
--

LOCK TABLES `telescope_monitoring` WRITE;
/*!40000 ALTER TABLE `telescope_monitoring` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_monitoring` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timezones`
--

DROP TABLE IF EXISTS `timezones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `timezones` (
                             `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                             `zone_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                             `country_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                             `zone_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=426 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timezones`
--

LOCK TABLES `timezones` WRITE;
/*!40000 ALTER TABLE `timezones` DISABLE KEYS */;
INSERT INTO `timezones` VALUES (1,'1','AD','Europe/Andorra'),(2,'2','AE','Asia/Dubai'),(3,'3','AF','Asia/Kabul'),(4,'4','AG','America/Antigua'),(5,'5','AI','America/Anguilla'),(6,'6','AL','Europe/Tirane'),(7,'7','AM','Asia/Yerevan'),(8,'8','AO','Africa/Luanda'),(9,'9','AQ','Antarctica/McMurdo'),(10,'10','AQ','Antarctica/Casey'),(11,'11','AQ','Antarctica/Davis'),(12,'12','AQ','Antarctica/DumontDUrville'),(13,'13','AQ','Antarctica/Mawson'),(14,'14','AQ','Antarctica/Palmer'),(15,'15','AQ','Antarctica/Rothera'),(16,'16','AQ','Antarctica/Syowa'),(17,'17','AQ','Antarctica/Troll'),(18,'18','AQ','Antarctica/Vostok'),(19,'19','AR','America/Argentina/Buenos_Aires'),(20,'20','AR','America/Argentina/Cordoba'),(21,'21','AR','America/Argentina/Salta'),(22,'22','AR','America/Argentina/Jujuy'),(23,'23','AR','America/Argentina/Tucuman'),(24,'24','AR','America/Argentina/Catamarca'),(25,'25','AR','America/Argentina/La_Rioja'),(26,'26','AR','America/Argentina/San_Juan'),(27,'27','AR','America/Argentina/Mendoza'),(28,'28','AR','America/Argentina/San_Luis'),(29,'29','AR','America/Argentina/Rio_Gallegos'),(30,'30','AR','America/Argentina/Ushuaia'),(31,'31','AS','Pacific/Pago_Pago'),(32,'32','AT','Europe/Vienna'),(33,'33','AU','Australia/Lord_Howe'),(34,'34','AU','Antarctica/Macquarie'),(35,'35','AU','Australia/Hobart'),(36,'36','AU','Australia/Currie'),(37,'37','AU','Australia/Melbourne'),(38,'38','AU','Australia/Sydney'),(39,'39','AU','Australia/Broken_Hill'),(40,'40','AU','Australia/Brisbane'),(41,'41','AU','Australia/Lindeman'),(42,'42','AU','Australia/Adelaide'),(43,'43','AU','Australia/Darwin'),(44,'44','AU','Australia/Perth'),(45,'45','AU','Australia/Eucla'),(46,'46','AW','America/Aruba'),(47,'47','AX','Europe/Mariehamn'),(48,'48','AZ','Asia/Baku'),(49,'49','BA','Europe/Sarajevo'),(50,'50','BB','America/Barbados'),(51,'51','BD','Asia/Dhaka'),(52,'52','BE','Europe/Brussels'),(53,'53','BF','Africa/Ouagadougou'),(54,'54','BG','Europe/Sofia'),(55,'55','BH','Asia/Bahrain'),(56,'56','BI','Africa/Bujumbura'),(57,'57','BJ','Africa/Porto-Novo'),(58,'58','BL','America/St_Barthelemy'),(59,'59','BM','Atlantic/Bermuda'),(60,'60','BN','Asia/Brunei'),(61,'61','BO','America/La_Paz'),(62,'62','BQ','America/Kralendijk'),(63,'63','BR','America/Noronha'),(64,'64','BR','America/Belem'),(65,'65','BR','America/Fortaleza'),(66,'66','BR','America/Recife'),(67,'67','BR','America/Araguaina'),(68,'68','BR','America/Maceio'),(69,'69','BR','America/Bahia'),(70,'70','BR','America/Sao_Paulo'),(71,'71','BR','America/Campo_Grande'),(72,'72','BR','America/Cuiaba'),(73,'73','BR','America/Santarem'),(74,'74','BR','America/Porto_Velho'),(75,'75','BR','America/Boa_Vista'),(76,'76','BR','America/Manaus'),(77,'77','BR','America/Eirunepe'),(78,'78','BR','America/Rio_Branco'),(79,'79','BS','America/Nassau'),(80,'80','BT','Asia/Thimphu'),(81,'81','BW','Africa/Gaborone'),(82,'82','BY','Europe/Minsk'),(83,'83','BZ','America/Belize'),(84,'84','CA','America/St_Johns'),(85,'85','CA','America/Halifax'),(86,'86','CA','America/Glace_Bay'),(87,'87','CA','America/Moncton'),(88,'88','CA','America/Goose_Bay'),(89,'89','CA','America/Blanc-Sablon'),(90,'90','CA','America/Toronto'),(91,'91','CA','America/Nipigon'),(92,'92','CA','America/Thunder_Bay'),(93,'93','CA','America/Iqaluit'),(94,'94','CA','America/Pangnirtung'),(95,'95','CA','America/Atikokan'),(96,'96','CA','America/Winnipeg'),(97,'97','CA','America/Rainy_River'),(98,'98','CA','America/Resolute'),(99,'99','CA','America/Rankin_Inlet'),(100,'100','CA','America/Regina'),(101,'101','CA','America/Swift_Current'),(102,'102','CA','America/Edmonton'),(103,'103','CA','America/Cambridge_Bay'),(104,'104','CA','America/Yellowknife'),(105,'105','CA','America/Inuvik'),(106,'106','CA','America/Creston'),(107,'107','CA','America/Dawson_Creek'),(108,'108','CA','America/Fort_Nelson'),(109,'109','CA','America/Vancouver'),(110,'110','CA','America/Whitehorse'),(111,'111','CA','America/Dawson'),(112,'112','CC','Indian/Cocos'),(113,'113','CD','Africa/Kinshasa'),(114,'114','CD','Africa/Lubumbashi'),(115,'115','CF','Africa/Bangui'),(116,'116','CG','Africa/Brazzaville'),(117,'117','CH','Europe/Zurich'),(118,'118','CI','Africa/Abidjan'),(119,'119','CK','Pacific/Rarotonga'),(120,'120','CL','America/Santiago'),(121,'121','CL','America/Punta_Arenas'),(122,'122','CL','Pacific/Easter'),(123,'123','CM','Africa/Douala'),(124,'124','CN','Asia/Shanghai'),(125,'125','CN','Asia/Urumqi'),(126,'126','CO','America/Bogota'),(127,'127','CR','America/Costa_Rica'),(128,'128','CU','America/Havana'),(129,'129','CV','Atlantic/Cape_Verde'),(130,'130','CW','America/Curacao'),(131,'131','CX','Indian/Christmas'),(132,'132','CY','Asia/Nicosia'),(133,'133','CY','Asia/Famagusta'),(134,'134','CZ','Europe/Prague'),(135,'135','DE','Europe/Berlin'),(136,'136','DE','Europe/Busingen'),(137,'137','DJ','Africa/Djibouti'),(138,'138','DK','Europe/Copenhagen'),(139,'139','DM','America/Dominica'),(140,'140','DO','America/Santo_Domingo'),(141,'141','DZ','Africa/Algiers'),(142,'142','EC','America/Guayaquil'),(143,'143','EC','Pacific/Galapagos'),(144,'144','EE','Europe/Tallinn'),(145,'145','EG','Africa/Cairo'),(146,'146','EH','Africa/El_Aaiun'),(147,'147','ER','Africa/Asmara'),(148,'148','ES','Europe/Madrid'),(149,'149','ES','Africa/Ceuta'),(150,'150','ES','Atlantic/Canary'),(151,'151','ET','Africa/Addis_Ababa'),(152,'152','FI','Europe/Helsinki'),(153,'153','FJ','Pacific/Fiji'),(154,'154','FK','Atlantic/Stanley'),(155,'155','FM','Pacific/Chuuk'),(156,'156','FM','Pacific/Pohnpei'),(157,'157','FM','Pacific/Kosrae'),(158,'158','FO','Atlantic/Faroe'),(159,'159','FR','Europe/Paris'),(160,'160','GA','Africa/Libreville'),(161,'161','GB','Europe/London'),(162,'162','GD','America/Grenada'),(163,'163','GE','Asia/Tbilisi'),(164,'164','GF','America/Cayenne'),(165,'165','GG','Europe/Guernsey'),(166,'166','GH','Africa/Accra'),(167,'167','GI','Europe/Gibraltar'),(168,'168','GL','America/Godthab'),(169,'169','GL','America/Danmarkshavn'),(170,'170','GL','America/Scoresbysund'),(171,'171','GL','America/Thule'),(172,'172','GM','Africa/Banjul'),(173,'173','GN','Africa/Conakry'),(174,'174','GP','America/Guadeloupe'),(175,'175','GQ','Africa/Malabo'),(176,'176','GR','Europe/Athens'),(177,'177','GS','Atlantic/South_Georgia'),(178,'178','GT','America/Guatemala'),(179,'179','GU','Pacific/Guam'),(180,'180','GW','Africa/Bissau'),(181,'181','GY','America/Guyana'),(182,'182','HK','Asia/Hong_Kong'),(183,'183','HN','America/Tegucigalpa'),(184,'184','HR','Europe/Zagreb'),(185,'185','HT','America/Port-au-Prince'),(186,'186','HU','Europe/Budapest'),(187,'187','ID','Asia/Jakarta'),(188,'188','ID','Asia/Pontianak'),(189,'189','ID','Asia/Makassar'),(190,'190','ID','Asia/Jayapura'),(191,'191','IE','Europe/Dublin'),(192,'192','IL','Asia/Jerusalem'),(193,'193','IM','Europe/Isle_of_Man'),(194,'194','IN','Asia/Kolkata'),(195,'195','IO','Indian/Chagos'),(196,'196','IQ','Asia/Baghdad'),(197,'197','IR','Asia/Tehran'),(198,'198','IS','Atlantic/Reykjavik'),(199,'199','IT','Europe/Rome'),(200,'200','JE','Europe/Jersey'),(201,'201','JM','America/Jamaica'),(202,'202','JO','Asia/Amman'),(203,'203','JP','Asia/Tokyo'),(204,'204','KE','Africa/Nairobi'),(205,'205','KG','Asia/Bishkek'),(206,'206','KH','Asia/Phnom_Penh'),(207,'207','KI','Pacific/Tarawa'),(208,'208','KI','Pacific/Enderbury'),(209,'209','KI','Pacific/Kiritimati'),(210,'210','KM','Indian/Comoro'),(211,'211','KN','America/St_Kitts'),(212,'212','KP','Asia/Pyongyang'),(213,'213','KR','Asia/Seoul'),(214,'214','KW','Asia/Kuwait'),(215,'215','KY','America/Cayman'),(216,'216','KZ','Asia/Almaty'),(217,'217','KZ','Asia/Qyzylorda'),(218,'218','KZ','Asia/Qostanay'),(219,'219','KZ','Asia/Aqtobe'),(220,'220','KZ','Asia/Aqtau'),(221,'221','KZ','Asia/Atyrau'),(222,'222','KZ','Asia/Oral'),(223,'223','LA','Asia/Vientiane'),(224,'224','LB','Asia/Beirut'),(225,'225','LC','America/St_Lucia'),(226,'226','LI','Europe/Vaduz'),(227,'227','LK','Asia/Colombo'),(228,'228','LR','Africa/Monrovia'),(229,'229','LS','Africa/Maseru'),(230,'230','LT','Europe/Vilnius'),(231,'231','LU','Europe/Luxembourg'),(232,'232','LV','Europe/Riga'),(233,'233','LY','Africa/Tripoli'),(234,'234','MA','Africa/Casablanca'),(235,'235','MC','Europe/Monaco'),(236,'236','MD','Europe/Chisinau'),(237,'237','ME','Europe/Podgorica'),(238,'238','MF','America/Marigot'),(239,'239','MG','Indian/Antananarivo'),(240,'240','MH','Pacific/Majuro'),(241,'241','MH','Pacific/Kwajalein'),(242,'242','MK','Europe/Skopje'),(243,'243','ML','Africa/Bamako'),(244,'244','MM','Asia/Yangon'),(245,'245','MN','Asia/Ulaanbaatar'),(246,'246','MN','Asia/Hovd'),(247,'247','MN','Asia/Choibalsan'),(248,'248','MO','Asia/Macau'),(249,'249','MP','Pacific/Saipan'),(250,'250','MQ','America/Martinique'),(251,'251','MR','Africa/Nouakchott'),(252,'252','MS','America/Montserrat'),(253,'253','MT','Europe/Malta'),(254,'254','MU','Indian/Mauritius'),(255,'255','MV','Indian/Maldives'),(256,'256','MW','Africa/Blantyre'),(257,'257','MX','America/Mexico_City'),(258,'258','MX','America/Cancun'),(259,'259','MX','America/Merida'),(260,'260','MX','America/Monterrey'),(261,'261','MX','America/Matamoros'),(262,'262','MX','America/Mazatlan'),(263,'263','MX','America/Chihuahua'),(264,'264','MX','America/Ojinaga'),(265,'265','MX','America/Hermosillo'),(266,'266','MX','America/Tijuana'),(267,'267','MX','America/Bahia_Banderas'),(268,'268','MY','Asia/Kuala_Lumpur'),(269,'269','MY','Asia/Kuching'),(270,'270','MZ','Africa/Maputo'),(271,'271','NA','Africa/Windhoek'),(272,'272','NC','Pacific/Noumea'),(273,'273','NE','Africa/Niamey'),(274,'274','NF','Pacific/Norfolk'),(275,'275','NG','Africa/Lagos'),(276,'276','NI','America/Managua'),(277,'277','NL','Europe/Amsterdam'),(278,'278','NO','Europe/Oslo'),(279,'279','NP','Asia/Kathmandu'),(280,'280','NR','Pacific/Nauru'),(281,'281','NU','Pacific/Niue'),(282,'282','NZ','Pacific/Auckland'),(283,'283','NZ','Pacific/Chatham'),(284,'284','OM','Asia/Muscat'),(285,'285','PA','America/Panama'),(286,'286','PE','America/Lima'),(287,'287','PF','Pacific/Tahiti'),(288,'288','PF','Pacific/Marquesas'),(289,'289','PF','Pacific/Gambier'),(290,'290','PG','Pacific/Port_Moresby'),(291,'291','PG','Pacific/Bougainville'),(292,'292','PH','Asia/Manila'),(293,'293','PK','Asia/Karachi'),(294,'294','PL','Europe/Warsaw'),(295,'295','PM','America/Miquelon'),(296,'296','PN','Pacific/Pitcairn'),(297,'297','PR','America/Puerto_Rico'),(298,'298','PS','Asia/Gaza'),(299,'299','PS','Asia/Hebron'),(300,'300','PT','Europe/Lisbon'),(301,'301','PT','Atlantic/Madeira'),(302,'302','PT','Atlantic/Azores'),(303,'303','PW','Pacific/Palau'),(304,'304','PY','America/Asuncion'),(305,'305','QA','Asia/Qatar'),(306,'306','RE','Indian/Reunion'),(307,'307','RO','Europe/Bucharest'),(308,'308','RS','Europe/Belgrade'),(309,'309','RU','Europe/Kaliningrad'),(310,'310','RU','Europe/Moscow'),(311,'311','UA','Europe/Simferopol'),(312,'312','RU','Europe/Kirov'),(313,'313','RU','Europe/Astrakhan'),(314,'314','RU','Europe/Volgograd'),(315,'315','RU','Europe/Saratov'),(316,'316','RU','Europe/Ulyanovsk'),(317,'317','RU','Europe/Samara'),(318,'318','RU','Asia/Yekaterinburg'),(319,'319','RU','Asia/Omsk'),(320,'320','RU','Asia/Novosibirsk'),(321,'321','RU','Asia/Barnaul'),(322,'322','RU','Asia/Tomsk'),(323,'323','RU','Asia/Novokuznetsk'),(324,'324','RU','Asia/Krasnoyarsk'),(325,'325','RU','Asia/Irkutsk'),(326,'326','RU','Asia/Chita'),(327,'327','RU','Asia/Yakutsk'),(328,'328','RU','Asia/Khandyga'),(329,'329','RU','Asia/Vladivostok'),(330,'330','RU','Asia/Ust-Nera'),(331,'331','RU','Asia/Magadan'),(332,'332','RU','Asia/Sakhalin'),(333,'333','RU','Asia/Srednekolymsk'),(334,'334','RU','Asia/Kamchatka'),(335,'335','RU','Asia/Anadyr'),(336,'336','RW','Africa/Kigali'),(337,'337','SA','Asia/Riyadh'),(338,'338','SB','Pacific/Guadalcanal'),(339,'339','SC','Indian/Mahe'),(340,'340','SD','Africa/Khartoum'),(341,'341','SE','Europe/Stockholm'),(342,'342','SG','Asia/Singapore'),(343,'343','SH','Atlantic/St_Helena'),(344,'344','SI','Europe/Ljubljana'),(345,'345','SJ','Arctic/Longyearbyen'),(346,'346','SK','Europe/Bratislava'),(347,'347','SL','Africa/Freetown'),(348,'348','SM','Europe/San_Marino'),(349,'349','SN','Africa/Dakar'),(350,'350','SO','Africa/Mogadishu'),(351,'351','SR','America/Paramaribo'),(352,'352','SS','Africa/Juba'),(353,'353','ST','Africa/Sao_Tome'),(354,'354','SV','America/El_Salvador'),(355,'355','SX','America/Lower_Princes'),(356,'356','SY','Asia/Damascus'),(357,'357','SZ','Africa/Mbabane'),(358,'358','TC','America/Grand_Turk'),(359,'359','TD','Africa/Ndjamena'),(360,'360','TF','Indian/Kerguelen'),(361,'361','TG','Africa/Lome'),(362,'362','TH','Asia/Bangkok'),(363,'363','TJ','Asia/Dushanbe'),(364,'364','TK','Pacific/Fakaofo'),(365,'365','TL','Asia/Dili'),(366,'366','TM','Asia/Ashgabat'),(367,'367','TN','Africa/Tunis'),(368,'368','TO','Pacific/Tongatapu'),(369,'369','TR','Europe/Istanbul'),(370,'370','TT','America/Port_of_Spain'),(371,'371','TV','Pacific/Funafuti'),(372,'372','TW','Asia/Taipei'),(373,'373','TZ','Africa/Dar_es_Salaam'),(374,'374','UA','Europe/Kiev'),(375,'375','UA','Europe/Uzhgorod'),(376,'376','UA','Europe/Zaporozhye'),(377,'377','UG','Africa/Kampala'),(378,'378','UM','Pacific/Midway'),(379,'379','UM','Pacific/Wake'),(380,'380','US','America/New_York'),(381,'381','US','America/Detroit'),(382,'382','US','America/Kentucky/Louisville'),(383,'383','US','America/Kentucky/Monticello'),(384,'384','US','America/Indiana/Indianapolis'),(385,'385','US','America/Indiana/Vincennes'),(386,'386','US','America/Indiana/Winamac'),(387,'387','US','America/Indiana/Marengo'),(388,'388','US','America/Indiana/Petersburg'),(389,'389','US','America/Indiana/Vevay'),(390,'390','US','America/Chicago'),(391,'391','US','America/Indiana/Tell_City'),(392,'392','US','America/Indiana/Knox'),(393,'393','US','America/Menominee'),(394,'394','US','America/North_Dakota/Center'),(395,'395','US','America/North_Dakota/New_Salem'),(396,'396','US','America/North_Dakota/Beulah'),(397,'397','US','America/Denver'),(398,'398','US','America/Boise'),(399,'399','US','America/Phoenix'),(400,'400','US','America/Los_Angeles'),(401,'401','US','America/Anchorage'),(402,'402','US','America/Juneau'),(403,'403','US','America/Sitka'),(404,'404','US','America/Metlakatla'),(405,'405','US','America/Yakutat'),(406,'406','US','America/Nome'),(407,'407','US','America/Adak'),(408,'408','US','Pacific/Honolulu'),(409,'409','UY','America/Montevideo'),(410,'410','UZ','Asia/Samarkand'),(411,'411','UZ','Asia/Tashkent'),(412,'412','VA','Europe/Vatican'),(413,'413','VC','America/St_Vincent'),(414,'414','VE','America/Caracas'),(415,'415','VG','America/Tortola'),(416,'416','VI','America/St_Thomas'),(417,'417','VN','Asia/Ho_Chi_Minh'),(418,'418','VU','Pacific/Efate'),(419,'419','WF','Pacific/Wallis'),(420,'420','WS','Pacific/Apia'),(421,'421','YE','Asia/Aden'),(422,'422','YT','Indian/Mayotte'),(423,'423','ZA','Africa/Johannesburg'),(424,'424','ZM','Africa/Lusaka'),(425,'425','ZW','Africa/Harare');
/*!40000 ALTER TABLE `timezones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `titles`
--

DROP TABLE IF EXISTS `titles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `titles` (
                          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                          `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `titles`
--

LOCK TABLES `titles` WRITE;
/*!40000 ALTER TABLE `titles` DISABLE KEYS */;
/*!40000 ALTER TABLE `titles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
                         `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                         `created_by_id` int DEFAULT NULL,
                         `branch_id` int DEFAULT NULL,
                         `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `email_verified_at` timestamp NULL DEFAULT NULL,
                         `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `api_token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `last_login` timestamp NULL DEFAULT NULL,
                         `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `address` text COLLATE utf8mb4_unicode_ci,
                         `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `enable_google2fa` tinyint NOT NULL DEFAULT '0',
                         `google2fa_secret` text COLLATE utf8mb4_unicode_ci,
                         `otp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `otp_expiry_date` timestamp NULL DEFAULT NULL,
                         `notes` text COLLATE utf8mb4_unicode_ci,
                         `photo` text COLLATE utf8mb4_unicode_ci,
                         `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `users_email_unique` (`email`),
                         UNIQUE KEY `users_api_token_unique` (`api_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,1,'Admin','admin','admin@localhost.com','2023-11-08 14:34:37','$2y$10$NuUcgqvBDO1LXs1zoV0Ce.8nDcy9pdpgVFhhuSJcK17O8K6RtvgUm','pkOAXySek4LTV4wXH5e8AzWlnncVLoNdqKrmUdcmj8ySb5RqF3FCmwyuhddO',NULL,'Admin','Admin',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'2023-11-08 14:34:37','2023-11-08 14:34:37');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_transactions`
--

DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_transactions` (
                                       `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                       `wallet_id` bigint unsigned NOT NULL,
                                       `created_by_id` bigint unsigned DEFAULT NULL,
                                       `branch_id` bigint unsigned DEFAULT NULL,
                                       `payment_detail_id` bigint unsigned DEFAULT NULL,
                                       `transaction_type` enum('deposit','withdrawal','savings_transfer','loan_transfer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'deposit',
                                       `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                       `amount` decimal(65,6) NOT NULL,
                                       `credit` decimal(65,6) DEFAULT NULL,
                                       `debit` decimal(65,6) DEFAULT NULL,
                                       `balance` decimal(65,6) DEFAULT NULL,
                                       `reversed` tinyint NOT NULL DEFAULT '0',
                                       `reversible` tinyint NOT NULL DEFAULT '0',
                                       `submitted_on` date DEFAULT NULL,
                                       `created_on` date DEFAULT NULL,
                                       `description` text COLLATE utf8mb4_unicode_ci,
                                       `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                       `gateway_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                       `payment_gateway_data` text COLLATE utf8mb4_unicode_ci,
                                       `online_transaction` tinyint NOT NULL DEFAULT '0',
                                       `status` enum('pending','approved','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
                                       `created_at` timestamp NULL DEFAULT NULL,
                                       `updated_at` timestamp NULL DEFAULT NULL,
                                       PRIMARY KEY (`id`),
                                       KEY `wallet_transactions_wallet_id_index` (`wallet_id`),
                                       KEY `wallet_transactions_branch_id_index` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_transactions`
--

LOCK TABLES `wallet_transactions` WRITE;
/*!40000 ALTER TABLE `wallet_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallets` (
                           `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                           `currency_id` bigint unsigned NOT NULL,
                           `created_by_id` bigint unsigned DEFAULT NULL,
                           `client_type` enum('client','group') COLLATE utf8mb4_unicode_ci DEFAULT 'client',
                           `client_id` bigint unsigned DEFAULT NULL,
                           `group_id` bigint unsigned DEFAULT NULL,
                           `branch_id` bigint unsigned DEFAULT NULL,
                           `status` enum('pending','active','inactive','closed','suspended','rejected','approved') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
                           `balance` decimal(65,6) DEFAULT NULL,
                           `decimals` int NOT NULL DEFAULT '2',
                           `description` text COLLATE utf8mb4_unicode_ci,
                           `submitted_on_date` date DEFAULT NULL,
                           `submitted_by_user_id` bigint unsigned DEFAULT NULL,
                           `approved_on_date` date DEFAULT NULL,
                           `approved_by_user_id` bigint unsigned DEFAULT NULL,
                           `approved_notes` text COLLATE utf8mb4_unicode_ci,
                           `rejected_on_date` date DEFAULT NULL,
                           `rejected_by_user_id` bigint unsigned DEFAULT NULL,
                           `rejected_notes` text COLLATE utf8mb4_unicode_ci,
                           `closed_on_date` date DEFAULT NULL,
                           `closed_by_user_id` bigint unsigned DEFAULT NULL,
                           `closed_notes` text COLLATE utf8mb4_unicode_ci,
                           `activated_on_date` date DEFAULT NULL,
                           `activated_by_user_id` bigint unsigned DEFAULT NULL,
                           `activated_notes` text COLLATE utf8mb4_unicode_ci,
                           `suspended_on_date` date DEFAULT NULL,
                           `suspended_by_user_id` bigint unsigned DEFAULT NULL,
                           `suspended_notes` text COLLATE utf8mb4_unicode_ci,
                           `inactive_on_date` date DEFAULT NULL,
                           `inactive_by_user_id` bigint unsigned DEFAULT NULL,
                           `inactive_notes` text COLLATE utf8mb4_unicode_ci,
                           `created_at` timestamp NULL DEFAULT NULL,
                           `updated_at` timestamp NULL DEFAULT NULL,
                           PRIMARY KEY (`id`),
                           KEY `wallets_client_id_index` (`client_id`),
                           KEY `wallets_branch_id_index` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallets`
--

LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widgets`
--

DROP TABLE IF EXISTS `widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widgets` (
                           `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                           `user_id` bigint unsigned NOT NULL,
                           `widgets` text COLLATE utf8mb4_unicode_ci,
                           `created_at` timestamp NULL DEFAULT NULL,
                           `updated_at` timestamp NULL DEFAULT NULL,
                           PRIMARY KEY (`id`),
                           KEY `widgets_user_id_foreign` (`user_id`),
                           CONSTRAINT `widgets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widgets`
--

LOCK TABLES `widgets` WRITE;
/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;
/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-08 16:36:10
