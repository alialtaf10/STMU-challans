-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table cms_challan.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table cms_challan.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table cms_challan.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table cms_challan.fee_types
CREATE TABLE IF NOT EXISTS `fee_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.fee_types: ~2 rows (approximately)
DELETE FROM `fee_types`;
INSERT INTO `fee_types` (`id`, `title`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'regular', 1, '2025-05-23 06:16:39', '2025-05-23 06:16:33'),
	(2, 'varied', 1, '2025-05-23 06:16:30', '2025-05-23 06:16:31');

-- Dumping structure for table cms_challan.installments
CREATE TABLE IF NOT EXISTS `installments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `term_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_installments__students` (`student_id`),
  KEY `FK_installments__terms` (`term_id`),
  CONSTRAINT `FK_installments__students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_installments__terms` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table cms_challan.installments: ~0 rows (approximately)
DELETE FROM `installments`;

-- Dumping structure for table cms_challan.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table cms_challan.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table cms_challan.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.migrations: ~10 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_05_21_084258_create_terms_table', 1),
	(5, '2025_05_21_084313_create_semesters_table', 1),
	(6, '2025_05_21_084324_create_fee_types_table', 1),
	(7, '2025_05_21_084338_create_scholarship_types_table', 1),
	(8, '2025_05_21_084355_create_students_table', 1),
	(9, '2025_05_21_084411_create_semester_fees_table', 1),
	(10, '2025_05_21_084426_create_scholarships_table', 1);

-- Dumping structure for table cms_challan.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table cms_challan.scholarships
CREATE TABLE IF NOT EXISTS `scholarships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `scholarship_type_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scholarships_student_id_foreign` (`student_id`),
  KEY `scholarships_scholarship_type_id_foreign` (`scholarship_type_id`),
  CONSTRAINT `scholarships_scholarship_type_id_foreign` FOREIGN KEY (`scholarship_type_id`) REFERENCES `scholarship_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `scholarships_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.scholarships: ~0 rows (approximately)
DELETE FROM `scholarships`;

-- Dumping structure for table cms_challan.scholarship_types
CREATE TABLE IF NOT EXISTS `scholarship_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `waiver` varchar(255) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.scholarship_types: ~7 rows (approximately)
DELETE FROM `scholarship_types`;
INSERT INTO `scholarship_types` (`id`, `name`, `status`, `waiver`, `created_at`, `updated_at`) VALUES
	(1, 'Merit Scholarship', 1, '', '2025-05-23 06:25:14', '2025-05-23 06:25:15'),
	(2, 'Need Base', 1, '', '2025-05-23 06:26:26', '2025-05-23 06:26:28'),
	(3, 'Ihsan Trust Case ', 1, '', '2025-05-26 07:05:06', '2025-05-26 07:05:07'),
	(4, 'Shifa Family', 1, '20', '2025-05-26 07:05:23', '2025-05-26 07:05:23'),
	(5, 'Others', 1, '', '2025-05-26 07:05:53', '2025-05-26 07:05:51'),
	(6, 'Siblings Discount', 1, '25', '2025-06-12 07:10:24', '2025-06-12 07:10:24'),
	(7, 'Orphan Discount', 1, '25', '2025-06-16 09:54:58', '2025-06-16 09:55:01');

-- Dumping structure for table cms_challan.semesters
CREATE TABLE IF NOT EXISTS `semesters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.semesters: ~14 rows (approximately)
DELETE FROM `semesters`;
INSERT INTO `semesters` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
	(1, '1st', 1, '2025-05-21 10:40:35', '2025-05-21 10:40:36'),
	(2, '2nd', 1, '2025-05-21 10:40:43', '2025-05-21 10:40:44'),
	(3, '3rd', 1, '2025-05-21 10:40:49', '2025-05-21 10:40:50'),
	(4, '4th', 1, '2025-05-21 10:40:55', '2025-05-21 10:40:55'),
	(5, '5th', 1, '2025-05-21 10:41:01', '2025-05-21 10:41:01'),
	(6, '6th', 1, '2025-05-21 10:41:07', '2025-05-21 10:41:07'),
	(7, '7th', 1, '2025-05-21 10:41:13', '2025-05-21 10:41:13'),
	(8, '8th', 1, '2025-05-21 10:41:18', '2025-05-21 10:41:19'),
	(9, '9th', 1, '2025-05-21 10:41:24', '2025-05-21 10:41:25'),
	(10, '10th', 1, '2025-05-21 10:41:30', '2025-05-21 10:41:31'),
	(11, '11th', 1, '2025-05-21 10:41:42', '2025-05-21 10:41:43'),
	(12, '12th', 1, '2025-05-21 10:41:47', '2025-05-21 10:41:47'),
	(13, '13th', 1, '2025-05-21 10:41:53', '2025-05-21 10:41:53'),
	(14, '14th', 1, '2025-05-21 10:41:59', '2025-05-21 10:42:00');

-- Dumping structure for table cms_challan.semester_fees
CREATE TABLE IF NOT EXISTS `semester_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fee_type_id` bigint(20) unsigned NOT NULL,
  `term_id` bigint(20) unsigned NOT NULL,
  `tuition_fee` decimal(10,2) DEFAULT NULL,
  `admission_fee` decimal(10,2) DEFAULT NULL,
  `university_registration_fee` decimal(10,2) DEFAULT NULL,
  `security_deposit` decimal(10,2) DEFAULT NULL,
  `medical_checkup` decimal(10,2) DEFAULT NULL,
  `semester_enrollment_fee` decimal(10,2) DEFAULT NULL,
  `examination_tuition_fee` decimal(10,2) DEFAULT NULL,
  `co_curricular_activities_fee` decimal(10,2) DEFAULT NULL,
  `hostel_fee` decimal(10,2) DEFAULT NULL,
  `pmc_registration` decimal(10,2) DEFAULT NULL,
  `pharmacy_council_reg_fee` decimal(10,2) DEFAULT NULL,
  `clinical_charge` decimal(10,2) DEFAULT NULL,
  `transport_charge` decimal(10,2) DEFAULT NULL,
  `library_fee` decimal(10,2) DEFAULT NULL,
  `migration_fee` decimal(10,2) DEFAULT NULL,
  `document_verification_fee` decimal(10,2) DEFAULT NULL,
  `application_prospectus_fee` decimal(10,2) DEFAULT NULL,
  `degree_convocation_fee` decimal(10,2) DEFAULT NULL,
  `research_thesis` decimal(10,2) DEFAULT NULL,
  `others_specify` decimal(10,2) DEFAULT NULL,
  `late_fee` decimal(10,2) DEFAULT NULL,
  `tuition_fee_discount` decimal(10,2) DEFAULT NULL,
  `special_discount` decimal(10,2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `semester_fees_fee_type_id_foreign` (`fee_type_id`),
  KEY `semester_fees_term_id_foreign` (`term_id`),
  CONSTRAINT `semester_fees_fee_type_id_foreign` FOREIGN KEY (`fee_type_id`) REFERENCES `fee_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `semester_fees_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.semester_fees: ~2 rows (approximately)
DELETE FROM `semester_fees`;
INSERT INTO `semester_fees` (`id`, `fee_type_id`, `term_id`, `tuition_fee`, `admission_fee`, `university_registration_fee`, `security_deposit`, `medical_checkup`, `semester_enrollment_fee`, `examination_tuition_fee`, `co_curricular_activities_fee`, `hostel_fee`, `pmc_registration`, `pharmacy_council_reg_fee`, `clinical_charge`, `transport_charge`, `library_fee`, `migration_fee`, `document_verification_fee`, `application_prospectus_fee`, `degree_convocation_fee`, `research_thesis`, `others_specify`, `late_fee`, `tuition_fee_discount`, `special_discount`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 114125.00, NULL, NULL, NULL, NULL, 6600.00, 7200.00, 1200.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
	(2, 2, 2, 6534.00, NULL, NULL, NULL, NULL, 6600.00, 7200.00, 1200.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL);

-- Dumping structure for table cms_challan.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.sessions: ~3 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('npNkSgHi1Y8yZLf1jGkR0HxzzgxSWTm2KiKVOtLH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibnlVRjRuS2VuNHhNcFp0enpwcXByb3FSd1d2Yk9TdzNvRVR5YkJnSyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0OToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3N0dWRlbnRzLzE3MjAvY2hhbGxhbnMvMTcxNiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1750366972),
	('O7XeQvQMum6G0CaT3oSGHq9GmZaclFzjjtSuWSQO', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidGpSVmRYU2RjcTlpcVIySlVaaG9na3FvVW1mbzVSVkVvWWhFRFlTcCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdHVkZW50cy8xODgzL2NoYWxsYW5zLzE4NzkiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1750329874),
	('XUx5sqCsBoe87t1xhOBWO5RRwlHmhtLv7rVp1Jw7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoialBrV3N3VmI0SkozNjJtVmR3d2JsOHNwdXltQ3R6Zkd1QTk3MDFlbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750392195);

-- Dumping structure for table cms_challan.students
CREATE TABLE IF NOT EXISTS `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `reg_no` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `semester_id` bigint(20) unsigned NOT NULL,
  `credit_hrs` int(11) NOT NULL,
  `gpa` decimal(4,2) NOT NULL,
  `hssc_marks` int(11) NOT NULL,
  `term_id` bigint(20) unsigned NOT NULL,
  `arrears` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_email_unique` (`email`),
  UNIQUE KEY `students_reg_no_unique` (`reg_no`),
  KEY `students_semester_id_foreign` (`semester_id`),
  KEY `students_term_id_foreign` (`term_id`) USING BTREE,
  CONSTRAINT `FK_students_terms` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `students_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1932 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.students: ~0 rows (approximately)
DELETE FROM `students`;

-- Dumping structure for table cms_challan.student_fees
CREATE TABLE IF NOT EXISTS `student_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `challan_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `kuickpay_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `fee_type_id` bigint(20) unsigned DEFAULT NULL,
  `semester_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `term_id` bigint(20) unsigned DEFAULT NULL,
  `tuition_fee` decimal(10,2) unsigned DEFAULT NULL,
  `admission_fee` decimal(10,2) unsigned DEFAULT NULL,
  `univeristy_registration_fee` decimal(10,2) unsigned DEFAULT NULL,
  `security_deposit` decimal(10,2) unsigned DEFAULT NULL,
  `medical_checkup` decimal(10,2) unsigned DEFAULT NULL,
  `semester_enrollment_fee` decimal(10,2) unsigned DEFAULT NULL,
  `examination_tuition_fee` decimal(10,2) unsigned DEFAULT NULL,
  `co_curricular_activities_fee` decimal(10,2) unsigned DEFAULT NULL,
  `hostel_fee` decimal(10,2) unsigned DEFAULT NULL,
  `pmc_registration` decimal(10,2) unsigned DEFAULT NULL,
  `pharmacy_council_reg_fee` decimal(10,2) unsigned DEFAULT NULL,
  `clinical_charge` decimal(10,2) unsigned DEFAULT NULL,
  `transport_charge` decimal(10,2) unsigned DEFAULT NULL,
  `library_fee` decimal(10,2) unsigned DEFAULT NULL,
  `migration_fee` decimal(10,2) unsigned DEFAULT NULL,
  `document_verification_fee` decimal(10,2) unsigned DEFAULT NULL,
  `application_prospectus_fee` decimal(10,2) unsigned DEFAULT NULL,
  `degree_convocation_fee` decimal(10,2) unsigned DEFAULT NULL,
  `research_thesis` decimal(10,2) unsigned DEFAULT NULL,
  `others_specify` decimal(10,2) unsigned DEFAULT NULL,
  `late_fee` decimal(10,2) unsigned DEFAULT NULL,
  `tuition_fee_discount` decimal(10,2) unsigned DEFAULT NULL,
  `special_discount` decimal(10,2) unsigned DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_student_fees__students` (`student_id`),
  KEY `FK_student_fees__fee_types` (`fee_type_id`),
  KEY `FK_student_fees__semester_id` (`semester_id`),
  KEY `FK_student_fees_created_by` (`created_by`),
  KEY `FK_student_fees_updated_by` (`updated_by`),
  KEY `FK_student_fees_term_id` (`term_id`),
  CONSTRAINT `FK_student_fees__fee_types` FOREIGN KEY (`fee_type_id`) REFERENCES `fee_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_student_fees__semester_id` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_student_fees__students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_student_fees_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_student_fees_term_id` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_student_fees_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1928 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table cms_challan.student_fees: ~0 rows (approximately)
DELETE FROM `student_fees`;

-- Dumping structure for table cms_challan.terms
CREATE TABLE IF NOT EXISTS `terms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_code` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.terms: ~8 rows (approximately)
DELETE FROM `terms`;
INSERT INTO `terms` (`id`, `name`, `short_code`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Spring 22', '22S', 1, '2025-05-21 10:36:32', '2025-05-21 10:36:32'),
	(2, 'Fall 22', '22F', 1, '2025-05-21 10:37:36', '2025-05-21 10:37:38'),
	(3, 'Spring 23', '23S', 1, '2025-05-21 10:37:58', '2025-05-21 10:37:59'),
	(4, 'Fall 23', '23F', 1, '2025-05-21 10:38:15', '2025-05-21 10:38:15'),
	(5, 'Spring 24', '24S', 1, '2025-05-21 10:38:44', '2025-05-21 10:38:47'),
	(6, 'Fall 24', '24F', 1, '2025-05-21 10:38:58', '2025-05-21 10:38:58'),
	(7, 'Spring 25', '25S', 1, '2025-05-21 10:39:27', '2025-05-21 10:39:27'),
	(8, 'Fall 25', '25F', 1, '2025-05-21 10:40:07', '2025-05-21 10:40:08');

-- Dumping structure for table cms_challan.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cms_challan.users: ~2 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'admin.challans@stmu.edu.pk', '2025-06-02 09:58:58', '$2y$12$1OF1UCF9U8zeDj1J6CqnuexqzyoHme0UzAxTkjX7BQPmFbsQg9owW', 'admin', NULL, '2025-05-21 04:10:11', '2025-06-03 02:23:42'),
	(2, 'Student Affairs', 'student_affairs@stmu.edu.pk', '2025-06-02 09:57:24', '$2y$12$5Xjvfx8FpWnaNO0a2KXjF.9d4GHDk.MjB5Q.ifLRB9fF4dldJb1R2', 'student_affairs', NULL, '2025-06-02 09:58:47', '2025-06-02 09:58:49');

-- Dumping structure for table cms_challan.workload
CREATE TABLE IF NOT EXISTS `workload` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `fee_type_id` bigint(20) unsigned DEFAULT NULL,
  `term_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_workload__students` (`student_id`),
  KEY `FK_workload__fee_types` (`fee_type_id`),
  KEY `FK_workload__terms` (`term_id`),
  CONSTRAINT `FK_workload__fee_types` FOREIGN KEY (`fee_type_id`) REFERENCES `fee_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_workload__students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_workload__terms` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table cms_challan.workload: ~0 rows (approximately)
DELETE FROM `workload`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
