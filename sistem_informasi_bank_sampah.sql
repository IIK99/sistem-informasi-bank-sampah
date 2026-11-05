-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: sistem_informasi_bank_sampah
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
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
-- Table structure for table `harga_pengepuls`
--

DROP TABLE IF EXISTS `harga_pengepuls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `harga_pengepuls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pengepul_id` bigint unsigned NOT NULL,
  `jenis_sampah_id` bigint unsigned NOT NULL,
  `satuan_id` bigint unsigned NOT NULL,
  `harga_beli` decimal(12,2) NOT NULL,
  `tanggal_berlaku` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `harga_pengepuls_pengepul_id_foreign` (`pengepul_id`),
  KEY `harga_pengepuls_jenis_sampah_id_foreign` (`jenis_sampah_id`),
  KEY `harga_pengepuls_satuan_id_foreign` (`satuan_id`),
  CONSTRAINT `harga_pengepuls_jenis_sampah_id_foreign` FOREIGN KEY (`jenis_sampah_id`) REFERENCES `jenis_sampahs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `harga_pengepuls_pengepul_id_foreign` FOREIGN KEY (`pengepul_id`) REFERENCES `pengepuls` (`id`) ON DELETE CASCADE,
  CONSTRAINT `harga_pengepuls_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `satuans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `harga_pengepuls`
--

LOCK TABLES `harga_pengepuls` WRITE;
/*!40000 ALTER TABLE `harga_pengepuls` DISABLE KEYS */;
/*!40000 ALTER TABLE `harga_pengepuls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jenis_sampahs`
--

DROP TABLE IF EXISTS `jenis_sampahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_sampahs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_jenis` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori_id` bigint unsigned NOT NULL,
  `satuan_id` bigint unsigned NOT NULL,
  `nama_jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jenis_sampahs_kategori_id_foreign` (`kategori_id`),
  KEY `jenis_sampahs_satuan_id_foreign` (`satuan_id`),
  CONSTRAINT `jenis_sampahs_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_sampahs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jenis_sampahs_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `satuans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jenis_sampahs`
--

LOCK TABLES `jenis_sampahs` WRITE;
/*!40000 ALTER TABLE `jenis_sampahs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jenis_sampahs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
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
-- Table structure for table `kategori_sampahs`
--

DROP TABLE IF EXISTS `kategori_sampahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_sampahs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_sampahs`
--

LOCK TABLES `kategori_sampahs` WRITE;
/*!40000 ALTER TABLE `kategori_sampahs` DISABLE KEYS */;
/*!40000 ALTER TABLE `kategori_sampahs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` json NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'Manajemen Pengguna','bi-people',NULL,'[\"admin\", \"petugas\"]',2,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(2,'Master Data','bi-box-seam',NULL,'[\"admin\"]',3,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(3,'Transaksi','bi-wallet2',NULL,'[\"admin\", \"petugas\"]',4,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(4,'Keuangan','bi-cash-coin',NULL,'[\"admin\", \"petugas\"]',5,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(5,'Laporan','bi-file-earmark-bar-graph',NULL,'[\"admin\", \"petugas\"]',6,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(6,'Pengaturan','bi-gear',NULL,'[\"admin\"]',7,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(7,'Akun','bi-person-circle',NULL,'[\"admin\", \"petugas\", \"nasabah\"]',8,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(8,'Saldo','bi-cash-stack',NULL,'[\"nasabah\"]',10,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(9,'Setoran','bi-box-arrow-in-down',NULL,'[\"nasabah\"]',11,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(10,'Penarikan Nasabah','bi-arrow-down-circle',NULL,'[\"nasabah\"]',12,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(11,'Riwayat Transaksi','bi-receipt',NULL,'[\"nasabah\"]',13,'2026-10-10 21:03:56','2026-10-10 21:03:56');
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
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_09_11_145319_create_menus_table',1),(5,'2026_09_11_145333_create_sub_menus_table',1),(6,'2026_09_14_153714_create_role_permissions_table',1),(7,'2026_09_25_120018_create_penarikan_saldos_table',1),(8,'2026_09_25_120029_add_saldo_to_users_table',1),(9,'2026_09_30_172303_create_satuans_table',1),(10,'2026_09_30_172305_create_kategori_sampahs_table',1),(11,'2026_09_30_172307_create_jenis_sampahs_table',1),(12,'2026_09_30_172309_create_setoran_sampahs_table',1),(13,'2026_10_01_170044_create_pengepuls_table',1),(14,'2026_10_01_170046_create_harga_pengepuls_table',1),(15,'2026_10_09_143106_add_kode_jenis_to_jenis_sampahs_table',1),(16,'2026_10_09_162248_create_roles_table',1),(17,'2026_10_09_164923_alter_role_column_in_users_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penarikan_saldos`
--

DROP TABLE IF EXISTS `penarikan_saldos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penarikan_saldos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_penarikan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `status` enum('menunggu','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_nasabah` text COLLATE utf8mb4_unicode_ci,
  `catatan_petugas` text COLLATE utf8mb4_unicode_ci,
  `petugas_id` bigint unsigned DEFAULT NULL,
  `tanggal_proses` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penarikan_saldos_kode_penarikan_unique` (`kode_penarikan`),
  KEY `penarikan_saldos_user_id_foreign` (`user_id`),
  KEY `penarikan_saldos_petugas_id_foreign` (`petugas_id`),
  CONSTRAINT `penarikan_saldos_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `penarikan_saldos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penarikan_saldos`
--

LOCK TABLES `penarikan_saldos` WRITE;
/*!40000 ALTER TABLE `penarikan_saldos` DISABLE KEYS */;
/*!40000 ALTER TABLE `penarikan_saldos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengepuls`
--

DROP TABLE IF EXISTS `pengepuls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengepuls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_pengepul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengepuls`
--

LOCK TABLES `pengepuls` WRITE;
/*!40000 ALTER TABLE `pengepuls` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengepuls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_menu_id` bigint unsigned NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '0',
  `can_create` tinyint(1) NOT NULL DEFAULT '0',
  `can_update` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permissions_role_sub_menu_id_unique` (`role`,`sub_menu_id`),
  KEY `role_permissions_sub_menu_id_foreign` (`sub_menu_id`),
  CONSTRAINT `role_permissions_sub_menu_id_foreign` FOREIGN KEY (`sub_menu_id`) REFERENCES `sub_menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,'admin',1,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(2,'petugas',1,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(3,'nasabah',1,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(4,'admin',2,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(5,'petugas',2,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(6,'nasabah',2,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(7,'admin',3,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(8,'petugas',3,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(9,'nasabah',3,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(10,'admin',4,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(11,'petugas',4,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(12,'nasabah',4,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(13,'admin',5,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(14,'petugas',5,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(15,'nasabah',5,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(16,'admin',6,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(17,'petugas',6,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(18,'nasabah',6,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(19,'admin',7,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(20,'petugas',7,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(21,'nasabah',7,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(22,'admin',8,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(23,'petugas',8,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(24,'nasabah',8,0,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(25,'admin',9,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(26,'petugas',9,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(27,'nasabah',9,1,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(28,'admin',10,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(29,'petugas',10,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(30,'nasabah',10,1,1,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(31,'admin',11,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(32,'petugas',11,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(33,'nasabah',11,1,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(34,'admin',12,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(35,'petugas',12,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(36,'nasabah',12,1,0,0,0,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(37,'admin',13,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(38,'petugas',13,1,1,1,1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(39,'nasabah',13,1,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(40,'admin',14,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(41,'petugas',14,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(42,'nasabah',14,1,1,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(43,'admin',15,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(44,'petugas',15,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(45,'nasabah',15,1,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(46,'admin',16,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(47,'petugas',16,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(48,'nasabah',16,0,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(49,'admin',17,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(50,'petugas',17,0,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(51,'nasabah',17,0,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(52,'admin',18,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(53,'petugas',18,0,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(54,'nasabah',18,0,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(55,'admin',19,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(56,'petugas',19,0,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(57,'nasabah',19,0,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(58,'admin',20,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(59,'petugas',20,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(60,'nasabah',20,1,0,1,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(61,'admin',21,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(62,'petugas',21,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(63,'nasabah',21,0,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(64,'admin',22,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(65,'petugas',22,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(66,'nasabah',22,1,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(67,'admin',23,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(68,'petugas',23,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(69,'nasabah',23,1,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(70,'admin',24,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(71,'petugas',24,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(72,'nasabah',24,1,1,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(73,'admin',25,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(74,'petugas',25,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(75,'nasabah',25,1,1,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(76,'admin',26,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(77,'petugas',26,1,1,1,1,'2026-10-10 21:03:57','2026-10-10 21:03:57'),(78,'nasabah',26,1,0,0,0,'2026-10-10 21:03:57','2026-10-10 21:03:57');
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `satuans`
--

DROP TABLE IF EXISTS `satuans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `satuans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_satuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `simbol` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `satuans`
--

LOCK TABLES `satuans` WRITE;
/*!40000 ALTER TABLE `satuans` DISABLE KEYS */;
/*!40000 ALTER TABLE `satuans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('H5ll35vTnZNht2q9iPRQRvbfiPbfLDFCr2t2ybfk',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJEMkQ5OHI3RjVNWGJPSk8xREM3V1p4bkFiV2R2T0lBeGdFcXFJQXJoIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==',1791691486);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setoran_sampahs`
--

DROP TABLE IF EXISTS `setoran_sampahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `setoran_sampahs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_setoran` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `petugas_id` bigint unsigned DEFAULT NULL,
  `jenis_sampah_id` bigint unsigned NOT NULL,
  `satuan_id` bigint unsigned NOT NULL,
  `jumlah` decimal(8,2) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setoran_sampahs_kode_setoran_unique` (`kode_setoran`),
  KEY `setoran_sampahs_user_id_foreign` (`user_id`),
  KEY `setoran_sampahs_petugas_id_foreign` (`petugas_id`),
  KEY `setoran_sampahs_jenis_sampah_id_foreign` (`jenis_sampah_id`),
  KEY `setoran_sampahs_satuan_id_foreign` (`satuan_id`),
  CONSTRAINT `setoran_sampahs_jenis_sampah_id_foreign` FOREIGN KEY (`jenis_sampah_id`) REFERENCES `jenis_sampahs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `setoran_sampahs_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `setoran_sampahs_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `satuans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `setoran_sampahs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setoran_sampahs`
--

LOCK TABLES `setoran_sampahs` WRITE;
/*!40000 ALTER TABLE `setoran_sampahs` DISABLE KEYS */;
/*!40000 ALTER TABLE `setoran_sampahs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_menus`
--

DROP TABLE IF EXISTS `sub_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sub_menus_menu_id_foreign` (`menu_id`),
  CONSTRAINT `sub_menus_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_menus`
--

LOCK TABLES `sub_menus` WRITE;
/*!40000 ALTER TABLE `sub_menus` DISABLE KEYS */;
INSERT INTO `sub_menus` VALUES (1,1,'Admin','/admin/kelola-admin','[\"admin\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(2,1,'Petugas','/admin/kelola-petugas','[\"admin\"]',2,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(3,1,'Nasabah','/nasabah','[\"admin\", \"petugas\"]',3,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(4,2,'Kategori Sampah','/admin/kategori-sampah','[\"admin\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(5,2,'Satuan','/admin/satuan','[\"admin\"]',2,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(6,2,'Jenis Sampah','/admin/jenis-sampah','[\"admin\"]',3,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(7,2,'Pengepul','/admin/pengepul','[\"admin\"]',4,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(8,2,'Harga Sampah','/admin/harga-sampah','[\"admin\"]',5,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(9,3,'Setoran Sampah','/setoran','[\"admin\", \"petugas\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(10,3,'Penarikan Saldo','/penarikan','[\"admin\", \"petugas\"]',2,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(11,4,'Saldo','/saldo','[\"admin\", \"petugas\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(12,4,'Riwayat Transaksi','/riwayat-transaksi','[\"admin\", \"petugas\"]',2,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(13,5,'Laporan Setoran','/laporan-setoran','[\"admin\", \"petugas\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(14,5,'Laporan Penarikan','/laporan-penarikan','[\"admin\", \"petugas\"]',2,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(15,5,'Laporan Saldo','/laporan-saldo','[\"admin\", \"petugas\"]',3,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(16,5,'Laporan Sampah','/laporan-sampah','[\"admin\", \"petugas\"]',4,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(17,6,'Pengaturan Sistem','/admin/pengaturan','[\"admin\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(18,6,'Kelola Izin','/admin/role-permissions','[\"admin\"]',2,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(19,6,'Manajemen Menu','/admin/menus','[\"admin\"]',3,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(20,7,'Profil','/profil','[\"admin\", \"petugas\", \"nasabah\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(21,7,'Logout','/logout','[\"admin\", \"petugas\", \"nasabah\"]',2,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(22,8,'Saldo Saya','/saldo','[\"nasabah\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(23,9,'Riwayat Setoran','/riwayat-setoran','[\"nasabah\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(24,10,'Ajukan Penarikan','/penarikan/create','[\"nasabah\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(25,10,'Riwayat Penarikan','/penarikan','[\"nasabah\"]',2,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(26,11,'Riwayat Transaksi','/riwayat-transaksi','[\"nasabah\"]',1,'2026-10-10 21:03:56','2026-10-10 21:03:56');
/*!40000 ALTER TABLE `sub_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldo` decimal(12,2) NOT NULL DEFAULT '0.00',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin','admin@banksampah.test','$2y$12$anb7vkgHhT645qKy5xs7seiNBDijoIRDwsXU3R.6yk0srdBRWUsjK','admin',0.00,NULL,'2026-10-10 21:03:55','2026-10-10 21:03:55'),(2,'Petugas Lapangan','petugas','petugas@banksampah.test','$2y$12$qJFhWJdwLvddiQsf27Qx8unP6QGW7kQg4j1YiuiSeWobfyxxnPnwa','petugas',0.00,NULL,'2026-10-10 21:03:56','2026-10-10 21:03:56'),(3,'Nasabah Warga','nasabah','nasabah@banksampah.test','$2y$12$SHTV/6eZuKmjj8W3PCrabebIooG.jIjdf15SZlFpWnvKsDBxLNxKG','nasabah',0.00,NULL,'2026-10-10 21:03:56','2026-10-10 21:03:56');
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

-- Dump completed on 2026-10-11 11:08:25
