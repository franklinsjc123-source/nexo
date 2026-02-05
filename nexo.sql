-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 05, 2026 at 01:26 PM
-- Server version: 8.0.45-0ubuntu0.24.04.1
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nexo`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(225) NOT NULL,
  `delivery_charge` decimal(10,2) NOT NULL,
  `company_address` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pincode` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pan_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gst_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `company_name`, `phone`, `email`, `delivery_charge`, `company_address`, `state`, `pincode`, `pan_no`, `gst_no`, `logo`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(2, 'NEXO', '999999999', 'nexocart@gamil.com', 40.00, '108 East Street ,Rajapalayam', 'Tamil Nadu', '603105', 'AAGCS4350J', '33AAGCS4350J1ZD', 'http://127.0.0.1:8000/uploads/company_logo/company_logo_1770204963_country1764139513_1.png', 1, '2025-12-05 10:19:22', '2026-02-04 06:06:23', '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `fc_expiry` int DEFAULT NULL,
  `insurance_expiry` int NOT NULL,
  `permit_expiry` int NOT NULL,
  `license_expiry` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `fc_expiry`, `insurance_expiry`, `permit_expiry`, `license_expiry`, `created_at`, `updated_at`) VALUES
(1, 10, 10, 20, 10, '2025-12-12 05:36:18', '2026-01-22 06:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_12_23_140345_create_oauth_auth_codes_table', 2),
(5, '2024_12_23_140346_create_oauth_access_tokens_table', 2),
(6, '2024_12_23_140347_create_oauth_refresh_tokens_table', 2),
(7, '2024_12_23_140348_create_oauth_clients_table', 2),
(8, '2024_12_23_140349_create_oauth_personal_access_clients_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'pvPNSgWfDmmTNRVf3rj2zUhes4Z12aDCjNdZaZkc', NULL, 'http://localhost', 1, 0, 0, '2024-12-23 08:33:50', '2024-12-23 08:33:50'),
(2, NULL, 'Laravel Password Grant Client', 'G8KedOWkToe2xkJiAh58siFj1M4PNWtBv52OKopO', 'users', 'http://localhost', 0, 1, 0, '2024-12-23 08:33:50', '2024-12-23 08:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-12-23 08:33:50', '2024-12-23 08:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `category` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `category`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Company', 'Company', NULL, '2025-12-30 00:54:59', '2025-12-30 00:58:21'),
(2, 1, 'Route', 'Route', NULL, '2025-12-30 00:58:54', '2025-12-30 00:59:24'),
(3, 1, 'Driver', 'Driver', NULL, '2025-12-30 01:00:12', '2025-12-30 01:01:42'),
(4, 1, 'Vehicle', 'Vehicle', NULL, '2025-12-30 01:02:53', '2025-12-30 01:02:53'),
(5, 1, 'Shift', 'Shift', NULL, '2025-12-30 01:03:17', '2025-12-30 01:03:17'),
(6, 1, 'Staff', 'Staff', NULL, '2025-12-30 01:03:32', '2025-12-30 01:03:32'),
(7, 1, 'permission', 'Permission', NULL, '2025-12-30 02:46:16', '2025-12-30 02:46:16'),
(8, 6, 'diesel-entry-report', 'Diesel-entry-report', NULL, '2025-12-30 08:27:03', '2025-12-30 08:27:03'),
(9, 6, 'daywise-log-entry', 'Daywise-log-entry', NULL, '2025-12-30 09:19:07', '2025-12-30 09:19:07'),
(10, 2, 'Log-Book-Entry', 'Log-Book-Entry', NULL, '2025-12-30 09:28:34', '2025-12-30 09:28:34'),
(11, 2, 'Staff-Attendance', 'Staff-Attendance', NULL, '2025-12-30 09:29:16', '2025-12-30 09:29:30'),
(12, 2, 'Diesel-Entry', 'Diesel-Entry', NULL, '2025-12-30 09:30:20', '2025-12-30 09:30:20'),
(13, 2, 'Driver-Attendance', 'Driver-Attendance', NULL, '2025-12-30 09:30:39', '2025-12-30 09:30:39'),
(14, 2, 'Staff-Attendance-Report', 'Staff-Attendance-Report', NULL, '2025-12-30 09:31:20', '2025-12-30 09:31:20'),
(15, 2, 'Driver-Attendance-Report', 'Driver-Attendance-Report', NULL, '2025-12-30 09:32:29', '2025-12-30 09:32:29'),
(16, 3, 'Oil-Service', 'Oil-Service', NULL, '2025-12-30 09:33:00', '2025-12-30 09:33:00'),
(17, 3, 'Grease', 'Grease', NULL, '2025-12-30 09:33:14', '2025-12-30 09:33:14'),
(18, 3, 'Tyre', 'Tyre', NULL, '2025-12-30 09:33:34', '2025-12-30 09:33:34'),
(19, 4, 'FC-Entry', 'FC-Entry', NULL, '2025-12-30 09:34:12', '2025-12-30 09:34:12'),
(20, 4, 'Insurance-Entry', 'Insurance-Entry', NULL, '2025-12-30 09:34:32', '2025-12-30 09:34:32'),
(21, 4, 'Permit-Entry', 'Permit-Entry', NULL, '2025-12-30 09:34:59', '2025-12-30 09:34:59'),
(22, 4, 'Vehicle-Status', 'Vehicle-Status', NULL, '2025-12-30 09:35:26', '2025-12-30 09:35:26'),
(34, 4, 'Vehicle-Alert', 'Vehicle-Alert', NULL, '2025-12-30 13:44:42', '2025-12-30 13:44:42'),
(35, 1, 'User-Management', 'User-Management', NULL, '2025-12-31 11:23:00', '2025-12-31 11:23:00'),
(36, 2, 'LogBook-Abstract', 'LogBook-Abstract', NULL, '2026-01-08 07:02:28', '2026-01-08 07:02:28'),
(37, 2, 'Driver-Attendance-Abstract', 'Driver-Attendance-Abstract', NULL, '2026-01-08 07:04:02', '2026-01-08 07:04:02'),
(38, 2, 'Driver-Advance', 'Driver-Advance', NULL, '2026-01-08 10:41:51', '2026-01-08 10:41:51');

-- --------------------------------------------------------

--
-- Table structure for table `permission_category`
--

CREATE TABLE `permission_category` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permission_category`
--

INSERT INTO `permission_category` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Masters', '2024-01-03 10:28:03', '2026-02-03 13:29:08'),
(2, 'Transactions', '2024-01-03 10:28:03', '2026-02-03 13:29:08'),
(3, 'Maintanance', '2024-01-03 10:28:22', '2026-02-03 13:29:08'),
(4, 'Vehicle-Details', '2024-01-03 10:28:22', '2026-02-03 13:29:08'),
(6, 'Reports', '2024-01-09 10:28:18', '2026-02-03 13:29:08'),
(7, 'Logistics', '2024-03-11 07:18:02', '2026-02-03 13:29:08'),
(8, 'HR', '2024-03-12 04:55:12', '2026-02-03 13:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

CREATE TABLE `permission_user` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_user`
--

INSERT INTO `permission_user` (`permission_id`, `user_id`, `user_type`) VALUES
(1, 1, 'App\\Models\\User'),
(1, 2, 'App\\Models\\User'),
(1, 33, 'App\\Models\\User'),
(1, 34, 'App\\Models\\User'),
(1, 35, 'App\\Models\\User'),
(1, 37, 'App\\Models\\User'),
(1, 44, 'App\\Models\\User'),
(2, 1, 'App\\Models\\User'),
(2, 2, 'App\\Models\\User'),
(2, 33, 'App\\Models\\User'),
(2, 34, 'App\\Models\\User'),
(2, 35, 'App\\Models\\User'),
(2, 37, 'App\\Models\\User'),
(3, 1, 'App\\Models\\User'),
(3, 2, 'App\\Models\\User'),
(3, 33, 'App\\Models\\User'),
(3, 34, 'App\\Models\\User'),
(3, 35, 'App\\Models\\User'),
(3, 36, 'App\\Models\\User'),
(3, 37, 'App\\Models\\User'),
(3, 38, 'App\\Models\\User'),
(3, 39, 'App\\Models\\User'),
(3, 40, 'App\\Models\\User'),
(3, 41, 'App\\Models\\User'),
(3, 42, 'App\\Models\\User'),
(3, 43, 'App\\Models\\User'),
(4, 1, 'App\\Models\\User'),
(4, 2, 'App\\Models\\User'),
(4, 33, 'App\\Models\\User'),
(4, 34, 'App\\Models\\User'),
(4, 35, 'App\\Models\\User'),
(4, 36, 'App\\Models\\User'),
(4, 37, 'App\\Models\\User'),
(4, 38, 'App\\Models\\User'),
(4, 39, 'App\\Models\\User'),
(4, 40, 'App\\Models\\User'),
(4, 41, 'App\\Models\\User'),
(4, 42, 'App\\Models\\User'),
(4, 43, 'App\\Models\\User'),
(5, 1, 'App\\Models\\User'),
(5, 2, 'App\\Models\\User'),
(5, 33, 'App\\Models\\User'),
(5, 34, 'App\\Models\\User'),
(5, 35, 'App\\Models\\User'),
(5, 37, 'App\\Models\\User'),
(5, 44, 'App\\Models\\User'),
(6, 1, 'App\\Models\\User'),
(6, 2, 'App\\Models\\User'),
(6, 33, 'App\\Models\\User'),
(6, 34, 'App\\Models\\User'),
(6, 35, 'App\\Models\\User'),
(6, 36, 'App\\Models\\User'),
(6, 37, 'App\\Models\\User'),
(6, 38, 'App\\Models\\User'),
(6, 39, 'App\\Models\\User'),
(6, 40, 'App\\Models\\User'),
(6, 41, 'App\\Models\\User'),
(6, 42, 'App\\Models\\User'),
(6, 43, 'App\\Models\\User'),
(7, 1, 'App\\Models\\User'),
(7, 2, 'App\\Models\\User'),
(7, 33, 'App\\Models\\User'),
(7, 34, 'App\\Models\\User'),
(7, 37, 'App\\Models\\User'),
(8, 1, 'App\\Models\\User'),
(8, 2, 'App\\Models\\User'),
(8, 33, 'App\\Models\\User'),
(8, 34, 'App\\Models\\User'),
(8, 35, 'App\\Models\\User'),
(8, 37, 'App\\Models\\User'),
(9, 1, 'App\\Models\\User'),
(9, 2, 'App\\Models\\User'),
(9, 33, 'App\\Models\\User'),
(9, 34, 'App\\Models\\User'),
(9, 35, 'App\\Models\\User'),
(9, 37, 'App\\Models\\User'),
(10, 1, 'App\\Models\\User'),
(10, 2, 'App\\Models\\User'),
(10, 33, 'App\\Models\\User'),
(10, 34, 'App\\Models\\User'),
(10, 35, 'App\\Models\\User'),
(10, 36, 'App\\Models\\User'),
(10, 37, 'App\\Models\\User'),
(10, 38, 'App\\Models\\User'),
(10, 39, 'App\\Models\\User'),
(10, 40, 'App\\Models\\User'),
(10, 41, 'App\\Models\\User'),
(10, 42, 'App\\Models\\User'),
(10, 43, 'App\\Models\\User'),
(11, 1, 'App\\Models\\User'),
(11, 2, 'App\\Models\\User'),
(11, 33, 'App\\Models\\User'),
(11, 34, 'App\\Models\\User'),
(11, 35, 'App\\Models\\User'),
(11, 36, 'App\\Models\\User'),
(11, 37, 'App\\Models\\User'),
(11, 38, 'App\\Models\\User'),
(11, 39, 'App\\Models\\User'),
(11, 40, 'App\\Models\\User'),
(11, 41, 'App\\Models\\User'),
(11, 42, 'App\\Models\\User'),
(11, 43, 'App\\Models\\User'),
(12, 1, 'App\\Models\\User'),
(12, 2, 'App\\Models\\User'),
(12, 33, 'App\\Models\\User'),
(12, 34, 'App\\Models\\User'),
(12, 35, 'App\\Models\\User'),
(12, 36, 'App\\Models\\User'),
(12, 37, 'App\\Models\\User'),
(12, 38, 'App\\Models\\User'),
(12, 39, 'App\\Models\\User'),
(12, 40, 'App\\Models\\User'),
(12, 41, 'App\\Models\\User'),
(12, 42, 'App\\Models\\User'),
(13, 1, 'App\\Models\\User'),
(13, 2, 'App\\Models\\User'),
(13, 33, 'App\\Models\\User'),
(13, 34, 'App\\Models\\User'),
(13, 35, 'App\\Models\\User'),
(13, 36, 'App\\Models\\User'),
(13, 37, 'App\\Models\\User'),
(13, 38, 'App\\Models\\User'),
(13, 39, 'App\\Models\\User'),
(13, 40, 'App\\Models\\User'),
(13, 41, 'App\\Models\\User'),
(13, 42, 'App\\Models\\User'),
(13, 43, 'App\\Models\\User'),
(14, 1, 'App\\Models\\User'),
(14, 2, 'App\\Models\\User'),
(14, 33, 'App\\Models\\User'),
(14, 34, 'App\\Models\\User'),
(14, 35, 'App\\Models\\User'),
(14, 37, 'App\\Models\\User'),
(15, 1, 'App\\Models\\User'),
(15, 2, 'App\\Models\\User'),
(15, 33, 'App\\Models\\User'),
(15, 34, 'App\\Models\\User'),
(15, 35, 'App\\Models\\User'),
(15, 37, 'App\\Models\\User'),
(16, 1, 'App\\Models\\User'),
(16, 2, 'App\\Models\\User'),
(16, 33, 'App\\Models\\User'),
(16, 34, 'App\\Models\\User'),
(16, 37, 'App\\Models\\User'),
(17, 1, 'App\\Models\\User'),
(17, 2, 'App\\Models\\User'),
(17, 33, 'App\\Models\\User'),
(17, 34, 'App\\Models\\User'),
(17, 37, 'App\\Models\\User'),
(18, 1, 'App\\Models\\User'),
(18, 2, 'App\\Models\\User'),
(18, 33, 'App\\Models\\User'),
(18, 34, 'App\\Models\\User'),
(18, 37, 'App\\Models\\User'),
(19, 1, 'App\\Models\\User'),
(19, 2, 'App\\Models\\User'),
(19, 33, 'App\\Models\\User'),
(19, 34, 'App\\Models\\User'),
(19, 35, 'App\\Models\\User'),
(19, 37, 'App\\Models\\User'),
(20, 1, 'App\\Models\\User'),
(20, 2, 'App\\Models\\User'),
(20, 33, 'App\\Models\\User'),
(20, 34, 'App\\Models\\User'),
(20, 35, 'App\\Models\\User'),
(20, 37, 'App\\Models\\User'),
(21, 1, 'App\\Models\\User'),
(21, 2, 'App\\Models\\User'),
(21, 33, 'App\\Models\\User'),
(21, 34, 'App\\Models\\User'),
(21, 35, 'App\\Models\\User'),
(21, 37, 'App\\Models\\User'),
(22, 1, 'App\\Models\\User'),
(22, 2, 'App\\Models\\User'),
(22, 33, 'App\\Models\\User'),
(22, 34, 'App\\Models\\User'),
(22, 35, 'App\\Models\\User'),
(22, 37, 'App\\Models\\User'),
(34, 1, 'App\\Models\\User'),
(34, 2, 'App\\Models\\User'),
(34, 33, 'App\\Models\\User'),
(34, 34, 'App\\Models\\User'),
(34, 35, 'App\\Models\\User'),
(34, 37, 'App\\Models\\User'),
(35, 1, 'App\\Models\\User'),
(35, 2, 'App\\Models\\User'),
(35, 33, 'App\\Models\\User'),
(35, 34, 'App\\Models\\User'),
(35, 37, 'App\\Models\\User'),
(36, 1, 'App\\Models\\User'),
(36, 36, 'App\\Models\\User'),
(36, 37, 'App\\Models\\User'),
(36, 38, 'App\\Models\\User'),
(36, 39, 'App\\Models\\User'),
(36, 40, 'App\\Models\\User'),
(36, 41, 'App\\Models\\User'),
(36, 42, 'App\\Models\\User'),
(36, 43, 'App\\Models\\User'),
(37, 1, 'App\\Models\\User'),
(37, 2, 'App\\Models\\User'),
(37, 35, 'App\\Models\\User'),
(37, 36, 'App\\Models\\User'),
(37, 37, 'App\\Models\\User'),
(37, 38, 'App\\Models\\User'),
(37, 39, 'App\\Models\\User'),
(37, 40, 'App\\Models\\User'),
(37, 41, 'App\\Models\\User'),
(37, 42, 'App\\Models\\User'),
(37, 43, 'App\\Models\\User'),
(38, 1, 'App\\Models\\User'),
(38, 2, 'App\\Models\\User'),
(38, 35, 'App\\Models\\User'),
(38, 36, 'App\\Models\\User'),
(38, 37, 'App\\Models\\User'),
(38, 38, 'App\\Models\\User'),
(38, 39, 'App\\Models\\User'),
(38, 40, 'App\\Models\\User'),
(38, 41, 'App\\Models\\User'),
(38, 42, 'App\\Models\\User'),
(38, 43, 'App\\Models\\User');

-- --------------------------------------------------------

--
-- Table structure for table `pincode`
--

CREATE TABLE `pincode` (
  `id` int NOT NULL,
  `pincode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pincode`
--

INSERT INTO `pincode` (`id`, `pincode`, `status`, `created_at`, `updated_at`) VALUES
(6, '223233', 1, '2026-02-04 08:33:25', '2026-02-04 08:34:21');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `role_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `types` int DEFAULT NULL COMMENT '1->daily,2->weekly,3->monthly',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role_name`, `types`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, '2023-12-28 09:36:59', '2026-02-03 13:29:08'),
(2, 'Sales', NULL, '2023-12-28 09:36:59', '2026-02-03 13:29:08'),
(3, 'Marketing', NULL, '2023-12-28 09:37:09', '2026-02-03 13:29:08'),
(4, 'New Role', NULL, '2024-01-02 07:56:26', '2026-02-03 13:29:08'),
(5, 'Sales Manager', 1, '2024-01-03 04:40:28', '2026-02-03 13:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `file_path`, `status`, `created_at`, `updated_at`) VALUES
(3, 'http://127.0.0.1:8000/uploads/slider/slider_1770200051_Screenshot_from_2026-02-03_13-01-48.png', 1, '2026-02-04 10:05:12', '2026-02-04 10:19:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_level` int NOT NULL COMMENT '1 => admin , 2 => user',
  `status` int NOT NULL DEFAULT '1',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `country`, `mobile`, `email`, `otp`, `auth_level`, `status`, `password`, `device_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, '9999999999', 'admin@nexo.com', NULL, 1, 1, '$2y$12$GKWoGeVAc2h8S3Xr258iweGxqpE18iagIWxm.oMGWlam28ZD0e..C', NULL, NULL, NULL, '2026-02-04 04:52:04'),
(2, 'Manager', NULL, '9944644557', 'Manager@yoheshtravel.com', '3588', 2, 1, '$2y$12$GKWoGeVAc2h8S3Xr258iweGxqpE18iagIWxm.oMGWlam28ZD0e..C', NULL, NULL, NULL, '2026-01-02 04:18:35'),
(35, 'Rakesh', NULL, '6369663125', 'rakesh', NULL, 2, 1, '$2y$12$jfF0prAPzkTD6.Htdy//JuMQUTc6PxBQk5uw/pcLU4bsPCcj9AodW', NULL, NULL, '2026-01-02 06:31:28', '2026-01-29 04:17:56'),
(36, 'Perumal', NULL, '7448673726', 'perumal', NULL, 2, 1, '$2y$12$w9YdHG6mIM8VyLEgbHx7B.YvWa1e37aj7KZn2.GUlu8XrBw598coy', NULL, NULL, '2026-01-02 06:33:44', '2026-01-02 06:33:44'),
(37, 'Emila', NULL, '9500418271', 'Emila', NULL, 2, 1, '$2y$12$uDO9PEaR8mTJtipEvZGEleufKC3/coiV1hTH6dt1myhiLckjzVQPi', NULL, NULL, '2026-01-02 06:37:38', '2026-01-02 06:37:38'),
(38, 'Rajesh', NULL, '9444840232', 'rajesh', NULL, 2, 1, '$2y$12$e95N48M9tuFCaODJi0f2QORzJWOFfZQ.dgv.yqS8PbxNe.9QfxJwi', NULL, NULL, '2026-01-02 07:08:24', '2026-01-02 07:08:24'),
(39, 'Santhoshkumar', NULL, '9786690923', 'santhosh', NULL, 2, 1, '$2y$12$T500KABxUWGh.0s3HPSgxObg.TZ1Tgr6tvQnGEaFTcEVdjPBlbPXm', NULL, NULL, '2026-01-02 07:12:58', '2026-01-02 07:12:58'),
(40, 'Arunkumar', NULL, '6381943561', 'arun', NULL, 2, 1, '$2y$12$Tx55E57.tImCm5qq081q2uAEDNl.o.y6vj6HYRNu1Gn7opRd4IVNy', NULL, NULL, '2026-01-02 07:16:14', '2026-01-02 07:16:14'),
(41, 'Srinivasan', NULL, '9042411192', 'srini', NULL, 2, 1, '$2y$12$8eWc4v.QgmT0PC1ANksAJuCZHEftEc3NcH8wJfwm6ZlmHN8yGOM5K', NULL, NULL, '2026-01-02 07:18:26', '2026-01-02 07:18:26'),
(42, 'Ramraj', NULL, '8925396028', 'ramraj', NULL, 2, 1, '$2y$12$9GPyxIA8uBS.0R.g92myG.jdp/7VC.6..mUKqEib20.qVUEnpYpia', NULL, NULL, '2026-01-02 07:20:26', '2026-01-02 07:20:26'),
(43, 'SELVARAJ', NULL, '9626191817', 'selvam', NULL, 2, 1, '$2y$12$ZnxYwR1N/FEvzKuVW9xTZOj3K6itm93b/k4eBEZPMMV69Ebq8bVge', NULL, NULL, '2026-01-09 04:02:34', '2026-01-09 04:02:34'),
(44, 'Franklin', NULL, '9786181435', 'franklun@yoheshtravel.com', NULL, 2, 1, '$2y$12$Whkdb7mqmpf3SiJ0LwU6OeSBjs3Lb.Hmx8hNcRLcbJoKbWigoRwZ6', NULL, NULL, '2026-02-04 05:58:21', '2026-02-04 05:58:35');

-- --------------------------------------------------------

--
-- Table structure for table `zone`
--

CREATE TABLE `zone` (
  `id` int NOT NULL,
  `zone_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `zone`
--

INSERT INTO `zone` (`id`, `zone_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ZONAL 1', 1, '2025-12-05 13:46:48', '0000-00-00 00:00:00'),
(6, 'ZONAL 2', 1, '2025-12-09 05:42:27', '2025-12-09 05:42:27'),
(7, 'ZONAL 3', 1, '2025-12-09 05:42:37', '2025-12-10 09:05:46'),
(8, 'ZONAL 4', 1, '2025-12-12 11:57:54', '2025-12-12 11:57:54'),
(11, 'ZONAL 5', 1, '2025-12-17 05:42:30', '2025-12-17 05:42:30'),
(12, 'ZONAL 6', 1, '2025-12-18 09:26:35', '2025-12-18 09:26:35'),
(13, 'SPARE', 1, '2026-01-23 09:36:06', '2026-01-23 09:36:06'),
(14, 'ON CALL', 1, '2026-01-27 04:28:17', '2026-01-27 04:28:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_category`
--
ALTER TABLE `permission_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`user_id`,`permission_id`,`user_type`),
  ADD KEY `permission_user_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `pincode`
--
ALTER TABLE `pincode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`,`user_type`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `zone`
--
ALTER TABLE `zone`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `permission_category`
--
ALTER TABLE `permission_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pincode`
--
ALTER TABLE `pincode`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `zone`
--
ALTER TABLE `zone`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
