-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2024 at 03:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dts`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `activity` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `document_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `validation` varchar(255) DEFAULT NULL,
  `suggestions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `custom_fields` text DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents_tags`
--

CREATE TABLE `documents_tags` (
  `document_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `file` varchar(500) NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `file_type_id` int(10) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `custom_fields` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_types`
--

CREATE TABLE `file_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `no_of_files` int(11) NOT NULL,
  `labels` varchar(255) NOT NULL,
  `file_validations` varchar(255) NOT NULL,
  `file_maxsize` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `file_types`
--

INSERT INTO `file_types` (`id`, `name`, `no_of_files`, `labels`, `file_validations`, `file_maxsize`, `created_at`, `updated_at`) VALUES
(1, 'GENERAL', 2, 'File', 'mimes:jpeg,bmp,png,jpg,pdf,docx', 8, '2024-03-20 21:35:39', '2024-04-22 03:03:45'),
(2, 'LETTER', 2, 'For Letters', 'mimes:jpeg,bmp,png,jpg,pdf,docx', 10, '2024-04-22 03:04:51', '2024-04-22 03:06:26'),
(3, 'LEAVE REQUEST', 1, 'File', 'mimes:jpeg,bmp,png,jpg,pdf,docx', 10, '2024-04-22 03:08:10', '2024-04-22 03:08:10'),
(4, 'PURCHASE REQUEST', 3, 'Files', 'mimes:jpeg,bmp,png,jpg,pdf,docx', 10, '2024-04-22 03:09:05', '2024-04-22 03:09:05');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_11_09_055735_create_settings_table', 1),
(5, '2019_11_11_170438_create_custom_fields_table', 1),
(6, '2019_11_12_122144_create_file_types_table', 1),
(7, '2019_11_12_155907_create_tags_table', 1),
(8, '2019_11_13_150331_create_documents_table', 1),
(9, '2019_11_14_144921_create_documents_tags_table', 1),
(10, '2019_11_15_122537_create_files_table', 1),
(11, '2019_11_18_143946_create_permission_tables', 1),
(12, '2019_11_20_155709_create_activities_table', 1),
(13, '2019_11_21_085158_update_custom_fields_add_field', 1),
(14, '2019_11_21_122845_update_activities_add_field_document_id', 1),
(15, '2024_04_29_090156_create_received_documents_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'create users', 'web', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(2, 'read users', 'web', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(3, 'update users', 'web', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(4, 'delete users', 'web', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(5, 'user manage permission', 'web', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(6, 'create tags', 'web', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(7, 'read tags', 'web', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(8, 'update tags', 'web', '2024-03-20 21:35:39', '2024-03-20 21:35:39'),
(9, 'delete tags', 'web', '2024-03-20 21:35:39', '2024-03-20 21:35:39'),
(10, 'create documents', 'web', '2024-03-20 21:35:39', '2024-03-20 21:35:39'),
(11, 'read documents', 'web', '2024-03-20 21:35:39', '2024-03-20 21:35:39'),
(12, 'update documents', 'web', '2024-03-20 21:35:39', '2024-03-20 21:35:39'),
(13, 'delete documents', 'web', '2024-03-20 21:35:39', '2024-03-20 21:35:39'),
(14, 'verify documents', 'web', '2024-03-20 21:35:39', '2024-03-20 21:35:39'),
(15, 'read documents in tag 1', 'web', '2024-03-20 21:36:43', '2024-03-20 21:36:43'),
(16, 'create documents in tag 1', 'web', '2024-03-20 21:36:43', '2024-03-20 21:36:43'),
(17, 'update documents in tag 1', 'web', '2024-03-20 21:36:43', '2024-03-20 21:36:43'),
(18, 'delete documents in tag 1', 'web', '2024-03-20 21:36:43', '2024-03-20 21:36:43'),
(19, 'verify documents in tag 1', 'web', '2024-03-20 21:36:43', '2024-03-20 21:36:43'),
(20, 'read documents in tag 2', 'web', '2024-03-20 21:37:44', '2024-03-20 21:37:44'),
(21, 'create documents in tag 2', 'web', '2024-03-20 21:37:44', '2024-03-20 21:37:44'),
(22, 'update documents in tag 2', 'web', '2024-03-20 21:37:44', '2024-03-20 21:37:44'),
(23, 'delete documents in tag 2', 'web', '2024-03-20 21:37:44', '2024-03-20 21:37:44'),
(24, 'verify documents in tag 2', 'web', '2024-03-20 21:37:44', '2024-03-20 21:37:44'),
(25, 'read documents in tag 3', 'web', '2024-03-20 21:38:17', '2024-03-20 21:38:17'),
(26, 'create documents in tag 3', 'web', '2024-03-20 21:38:17', '2024-03-20 21:38:17'),
(27, 'update documents in tag 3', 'web', '2024-03-20 21:38:17', '2024-03-20 21:38:17'),
(28, 'delete documents in tag 3', 'web', '2024-03-20 21:38:17', '2024-03-20 21:38:17'),
(29, 'verify documents in tag 3', 'web', '2024-03-20 21:38:17', '2024-03-20 21:38:17'),
(30, 'read documents in tag 4', 'web', '2024-03-20 21:38:43', '2024-03-20 21:38:43'),
(31, 'create documents in tag 4', 'web', '2024-03-20 21:38:44', '2024-03-20 21:38:44'),
(32, 'update documents in tag 4', 'web', '2024-03-20 21:38:44', '2024-03-20 21:38:44'),
(33, 'delete documents in tag 4', 'web', '2024-03-20 21:38:44', '2024-03-20 21:38:44'),
(34, 'verify documents in tag 4', 'web', '2024-03-20 21:38:44', '2024-03-20 21:38:44'),
(35, 'read documents in tag 5', 'web', '2024-03-20 21:39:08', '2024-03-20 21:39:08'),
(36, 'create documents in tag 5', 'web', '2024-03-20 21:39:08', '2024-03-20 21:39:08'),
(37, 'update documents in tag 5', 'web', '2024-03-20 21:39:08', '2024-03-20 21:39:08'),
(38, 'delete documents in tag 5', 'web', '2024-03-20 21:39:08', '2024-03-20 21:39:08'),
(39, 'verify documents in tag 5', 'web', '2024-03-20 21:39:08', '2024-03-20 21:39:08'),
(40, 'read documents in tag 6', 'web', '2024-03-20 21:40:05', '2024-03-20 21:40:05'),
(41, 'create documents in tag 6', 'web', '2024-03-20 21:40:05', '2024-03-20 21:40:05'),
(42, 'update documents in tag 6', 'web', '2024-03-20 21:40:05', '2024-03-20 21:40:05'),
(43, 'delete documents in tag 6', 'web', '2024-03-20 21:40:05', '2024-03-20 21:40:05'),
(44, 'verify documents in tag 6', 'web', '2024-03-20 21:40:05', '2024-03-20 21:40:05'),
(45, 'read documents in tag 7', 'web', '2024-03-20 21:40:54', '2024-03-20 21:40:54'),
(46, 'create documents in tag 7', 'web', '2024-03-20 21:40:54', '2024-03-20 21:40:54'),
(47, 'update documents in tag 7', 'web', '2024-03-20 21:40:54', '2024-03-20 21:40:54'),
(48, 'delete documents in tag 7', 'web', '2024-03-20 21:40:54', '2024-03-20 21:40:54'),
(49, 'verify documents in tag 7', 'web', '2024-03-20 21:40:54', '2024-03-20 21:40:54'),
(50, 'read documents in tag 8', 'web', '2024-03-20 21:41:35', '2024-03-20 21:41:35'),
(51, 'create documents in tag 8', 'web', '2024-03-20 21:41:35', '2024-03-20 21:41:35'),
(52, 'update documents in tag 8', 'web', '2024-03-20 21:41:35', '2024-03-20 21:41:35'),
(53, 'delete documents in tag 8', 'web', '2024-03-20 21:41:35', '2024-03-20 21:41:35'),
(54, 'verify documents in tag 8', 'web', '2024-03-20 21:41:35', '2024-03-20 21:41:35'),
(55, 'read documents in tag 9', 'web', '2024-03-20 21:42:10', '2024-03-20 21:42:10'),
(56, 'create documents in tag 9', 'web', '2024-03-20 21:42:10', '2024-03-20 21:42:10'),
(57, 'update documents in tag 9', 'web', '2024-03-20 21:42:10', '2024-03-20 21:42:10'),
(58, 'delete documents in tag 9', 'web', '2024-03-20 21:42:10', '2024-03-20 21:42:10'),
(59, 'verify documents in tag 9', 'web', '2024-03-20 21:42:10', '2024-03-20 21:42:10'),
(60, 'read documents in tag 10', 'web', '2024-03-20 21:42:53', '2024-03-20 21:42:53'),
(61, 'create documents in tag 10', 'web', '2024-03-20 21:42:53', '2024-03-20 21:42:53'),
(62, 'update documents in tag 10', 'web', '2024-03-20 21:42:53', '2024-03-20 21:42:53'),
(63, 'delete documents in tag 10', 'web', '2024-03-20 21:42:53', '2024-03-20 21:42:53'),
(64, 'verify documents in tag 10', 'web', '2024-03-20 21:42:53', '2024-03-20 21:42:53'),
(65, 'read documents in tag 11', 'web', '2024-03-20 21:43:55', '2024-03-20 21:43:55'),
(66, 'create documents in tag 11', 'web', '2024-03-20 21:43:55', '2024-03-20 21:43:55'),
(67, 'update documents in tag 11', 'web', '2024-03-20 21:43:55', '2024-03-20 21:43:55'),
(68, 'delete documents in tag 11', 'web', '2024-03-20 21:43:55', '2024-03-20 21:43:55'),
(69, 'verify documents in tag 11', 'web', '2024-03-20 21:43:55', '2024-03-20 21:43:55'),
(70, 'read documents in tag 12', 'web', '2024-03-20 21:44:38', '2024-03-20 21:44:38'),
(71, 'create documents in tag 12', 'web', '2024-03-20 21:44:38', '2024-03-20 21:44:38'),
(72, 'update documents in tag 12', 'web', '2024-03-20 21:44:38', '2024-03-20 21:44:38'),
(73, 'delete documents in tag 12', 'web', '2024-03-20 21:44:38', '2024-03-20 21:44:38'),
(74, 'verify documents in tag 12', 'web', '2024-03-20 21:44:38', '2024-03-20 21:44:38'),
(75, 'read documents in tag 13', 'web', '2024-03-20 21:45:02', '2024-03-20 21:45:02'),
(76, 'create documents in tag 13', 'web', '2024-03-20 21:45:02', '2024-03-20 21:45:02'),
(77, 'update documents in tag 13', 'web', '2024-03-20 21:45:02', '2024-03-20 21:45:02'),
(78, 'delete documents in tag 13', 'web', '2024-03-20 21:45:02', '2024-03-20 21:45:02'),
(79, 'verify documents in tag 13', 'web', '2024-03-20 21:45:02', '2024-03-20 21:45:02'),
(80, 'read documents in tag 14', 'web', '2024-03-20 21:45:36', '2024-03-20 21:45:36'),
(81, 'create documents in tag 14', 'web', '2024-03-20 21:45:36', '2024-03-20 21:45:36'),
(82, 'update documents in tag 14', 'web', '2024-03-20 21:45:36', '2024-03-20 21:45:36'),
(83, 'delete documents in tag 14', 'web', '2024-03-20 21:45:36', '2024-03-20 21:45:36'),
(84, 'verify documents in tag 14', 'web', '2024-03-20 21:45:36', '2024-03-20 21:45:36'),
(89, 'read document 2', 'web', '2024-03-20 21:56:59', '2024-03-20 21:56:59'),
(90, 'update document 2', 'web', '2024-03-20 21:56:59', '2024-03-20 21:56:59'),
(91, 'delete document 2', 'web', '2024-03-20 21:56:59', '2024-03-20 21:56:59'),
(92, 'verify document 2', 'web', '2024-03-20 21:56:59', '2024-03-20 21:56:59'),
(93, 'read documents in tag 15', 'web', '2024-05-01 22:43:21', '2024-05-01 22:43:21'),
(94, 'create documents in tag 15', 'web', '2024-05-01 22:43:21', '2024-05-01 22:43:21'),
(95, 'update documents in tag 15', 'web', '2024-05-01 22:43:21', '2024-05-01 22:43:21'),
(96, 'delete documents in tag 15', 'web', '2024-05-01 22:43:21', '2024-05-01 22:43:21'),
(97, 'verify documents in tag 15', 'web', '2024-05-01 22:43:21', '2024-05-01 22:43:21'),
(98, 'read documents in tag 16', 'web', '2024-05-01 22:46:45', '2024-05-01 22:46:45'),
(99, 'create documents in tag 16', 'web', '2024-05-01 22:46:45', '2024-05-01 22:46:45'),
(100, 'update documents in tag 16', 'web', '2024-05-01 22:46:45', '2024-05-01 22:46:45'),
(101, 'delete documents in tag 16', 'web', '2024-05-01 22:46:45', '2024-05-01 22:46:45'),
(102, 'verify documents in tag 16', 'web', '2024-05-01 22:46:45', '2024-05-01 22:46:45'),
(103, 'read documents in tag 17', 'web', '2024-05-01 22:52:02', '2024-05-01 22:52:02'),
(104, 'create documents in tag 17', 'web', '2024-05-01 22:52:02', '2024-05-01 22:52:02'),
(105, 'update documents in tag 17', 'web', '2024-05-01 22:52:02', '2024-05-01 22:52:02'),
(106, 'delete documents in tag 17', 'web', '2024-05-01 22:52:02', '2024-05-01 22:52:02'),
(107, 'verify documents in tag 17', 'web', '2024-05-01 22:52:02', '2024-05-01 22:52:02');

-- --------------------------------------------------------

--
-- Table structure for table `received_documents`
--

CREATE TABLE `received_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'system_title', 'Ospital ng Imus', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(2, 'system_logo', 'logo.png', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(3, 'tags_label_singular', 'tag', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(4, 'tags_label_plural', 'tags', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(5, 'document_label_singular', 'document', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(6, 'document_label_plural', 'documents', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(7, 'file_label_singular', 'file', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(8, 'file_label_plural', 'files', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(9, 'default_file_validations', 'mimes:jpeg,bmp,png,jpg', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(10, 'default_file_maxsize', '8', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(11, 'image_files_resize', '300,500,700', '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(12, 'show_missing_files_errors', 'true', '2024-03-20 21:35:38', '2024-03-20 21:35:38');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `custom_fields` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `color`, `created_by`, `custom_fields`, `created_at`, `updated_at`) VALUES
(1, 'Chief of Hospital ll', '#3b8e3f', 1, NULL, '2024-03-20 21:36:43', '2024-03-20 21:36:43'),
(2, 'OIC - Chief Administrative Ofiicer', '#3b8e3f', 1, NULL, '2024-03-20 21:37:44', '2024-03-20 21:37:44'),
(3, 'Head - Budget Office', '#3b8e3f', 1, NULL, '2024-03-20 21:38:17', '2024-03-20 21:38:17'),
(4, 'Head - Cash Operations', '#3b8e3f', 1, NULL, '2024-03-20 21:38:43', '2024-03-20 21:38:43'),
(5, 'OIC- Human Resources', '#3b8e3f', 1, NULL, '2024-03-20 21:39:08', '2024-03-20 21:39:08'),
(6, 'Head - Procurement, Property, and Supply', '#3b8e3f', 1, NULL, '2024-03-20 21:40:05', '2024-03-20 21:40:05'),
(7, 'Head - Information Technology', '#3b8e3f', 1, NULL, '2024-03-20 21:40:54', '2024-03-20 21:40:54'),
(8, 'Head - Civil Security Unit', '#3b8e3f', 1, NULL, '2024-03-20 21:41:34', '2024-03-20 21:41:34'),
(9, 'OIC - Engineering and Facilities', '#3b8e3f', 1, NULL, '2024-03-20 21:42:10', '2024-03-20 21:42:10'),
(10, 'Head - Quality Management Services', '#3b8e3f', 1, NULL, '2024-03-20 21:42:53', '2024-03-20 21:42:53'),
(11, 'Head - Engineering and Facilities Management Housekeeping', '#3b8e3f', 1, NULL, '2024-03-20 21:43:55', '2024-03-20 21:43:55'),
(12, 'Head - Accounting / Finance', '#3b8e3f', 1, NULL, '2024-03-20 21:44:38', '2024-03-20 21:44:38'),
(13, 'Head - Claims Section', '#3b8e3f', 1, NULL, '2024-03-20 21:45:02', '2024-03-20 21:45:02'),
(14, 'Head - Billing Section', '#3b8e3f', 1, NULL, '2024-03-20 21:45:36', '2024-05-01 22:42:06'),
(15, 'OIC - Chief of Medical Professional Staff', '#3b8e3f', 1, NULL, '2024-05-01 22:43:21', '2024-05-01 22:43:21'),
(16, 'Chief Nurse', '#3b8e3f', 1, NULL, '2024-05-01 22:46:45', '2024-05-01 22:46:45'),
(17, 'Head - Dialysis', '#3b8e3f', 1, NULL, '2024-05-01 22:52:02', '2024-05-01 22:52:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `address`, `description`, `email_verified_at`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', NULL, 'super', NULL, NULL, NULL, '$2y$10$JQI2Alxr7B3.kZinKBYSaupsfppNV/v.UjaWQe83yR9rDxB3z7O/K', 'ACTIVE', NULL, '2024-03-20 21:35:38', '2024-03-20 21:35:38'),
(2, 'Gabriel G. Gabriel, MD, FPCEM, MMHoA', 'gg@gmail.com', 'ggg123', 'Imus', NULL, NULL, '$2y$10$Jp9qeEbDZiDSHCnFFJJQD.JtfDzhm1YA81L4G3CwjiiX106dL/1ym', 'ACTIVE', NULL, '2024-03-20 21:54:15', '2024-03-20 21:54:15'),
(3, 'Joseph M. Padlan, ECE, LPT', 'joseph@gmail.com', 'joseph123', 'Imus', NULL, NULL, '$2y$10$umkuxyxaV9eQN7DigTqjwO4obHxdUn4vKtofrPEfWskdHerNHosHa', 'ACTIVE', NULL, '2024-03-20 21:55:19', '2024-03-20 21:55:19'),
(4, 'Wilfred Delos Reyes', 'wil@gmail.com', 'wil123', 'Bacoor', NULL, NULL, '$2y$10$7lvYoMLHf8exms.BHc2M9uks7xQm69Bm2eizFjvywQ6BhXeHowmuS', 'ACTIVE', NULL, '2024-03-20 21:56:13', '2024-03-20 21:56:13'),
(5, 'Marco Aldrin N. Andaya, MD, DPPS, MMHoA', 'marco@gmail.com', 'marco123', 'imus', NULL, NULL, '$2y$10$3HEwZ2r6zQdGWqzcOM7ete5r80dOG0IA/bWBYV8xfPpdJnmGr9JP2', 'ACTIVE', NULL, '2024-05-01 22:36:21', '2024-05-01 22:36:21'),
(6, 'Mary Del V. Agarin-Bathan, MD, FPOGS, MMHoA', 'mary@gmail.com', 'mary123', 'imus', NULL, NULL, '$2y$10$xlyMbZlvmb79f7UKPIgGd..wQ3z6dpGs2JOFS27BuwAH9DtG/6lJ.', 'ACTIVE', NULL, '2024-05-01 22:41:52', '2024-05-01 22:41:52'),
(7, 'Alicia C. Camama, RN, MAN', 'alicia@gmail.com', 'alicia123', 'imus', NULL, NULL, '$2y$10$tJ2dZRTrai1Gz3NRFOgrI.USwjjI6yOFHtseaxHEeKlDTtEOPbR6y', 'ACTIVE', NULL, '2024-05-01 22:46:26', '2024-05-01 22:46:26'),
(8, 'Georgette E. Lee, MD, FICS, DPAMS', 'georgette@gmail.com', 'georgette123', 'imus', NULL, NULL, '$2y$10$Ik3LmG4qOD5aM9Pp7PR.WOcBqf/6X3UnMdi1mpcTbs/4SOJ8vczgS', 'ACTIVE', NULL, '2024-05-01 22:51:08', '2024-05-01 22:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_has_permissions`
--

CREATE TABLE `user_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_has_permissions`
--

INSERT INTO `user_has_permissions` (`permission_id`, `model_type`, `user_id`) VALUES
(5, 'App\\User', 3),
(5, 'App\\User', 5),
(5, 'App\\User', 6),
(5, 'App\\User', 7),
(5, 'App\\User', 8),
(10, 'App\\User', 4),
(11, 'App\\User', 4),
(12, 'App\\User', 4),
(13, 'App\\User', 4),
(15, 'App\\User', 2),
(16, 'App\\User', 2),
(17, 'App\\User', 2),
(18, 'App\\User', 2),
(19, 'App\\User', 2),
(20, 'App\\User', 5),
(21, 'App\\User', 5),
(22, 'App\\User', 5),
(23, 'App\\User', 5),
(24, 'App\\User', 5),
(45, 'App\\User', 3),
(46, 'App\\User', 3),
(47, 'App\\User', 3),
(48, 'App\\User', 3),
(49, 'App\\User', 3),
(93, 'App\\User', 6),
(94, 'App\\User', 6),
(95, 'App\\User', 6),
(96, 'App\\User', 6),
(97, 'App\\User', 6),
(98, 'App\\User', 7),
(99, 'App\\User', 7),
(100, 'App\\User', 7),
(101, 'App\\User', 7),
(102, 'App\\User', 7),
(103, 'App\\User', 8),
(104, 'App\\User', 8),
(105, 'App\\User', 8),
(106, 'App\\User', 8),
(107, 'App\\User', 8);

-- --------------------------------------------------------

--
-- Table structure for table `user_has_roles`
--

CREATE TABLE `user_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_created_by_foreign` (`created_by`),
  ADD KEY `activities_document_id_foreign` (`document_id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_created_by_foreign` (`created_by`),
  ADD KEY `documents_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `documents_tags`
--
ALTER TABLE `documents_tags`
  ADD PRIMARY KEY (`document_id`,`tag_id`),
  ADD KEY `documents_tags_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_document_id_foreign` (`document_id`),
  ADD KEY `files_file_type_id_foreign` (`file_type_id`),
  ADD KEY `files_created_by_foreign` (`created_by`);

--
-- Indexes for table `file_types`
--
ALTER TABLE `file_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `received_documents`
--
ALTER TABLE `received_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `received_documents_document_id_foreign` (`document_id`),
  ADD KEY `received_documents_creator_id_foreign` (`creator_id`),
  ADD KEY `received_documents_sender_id_foreign` (`sender_id`),
  ADD KEY `received_documents_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_name_unique` (`name`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tags_created_by_foreign` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_has_permissions`
--
ALTER TABLE `user_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`user_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`user_id`,`model_type`);

--
-- Indexes for table `user_has_roles`
--
ALTER TABLE `user_has_roles`
  ADD PRIMARY KEY (`role_id`,`user_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`user_id`,`model_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `file_types`
--
ALTER TABLE `file_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `received_documents`
--
ALTER TABLE `received_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `activities_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `documents_tags`
--
ALTER TABLE `documents_tags`
  ADD CONSTRAINT `documents_tags_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `documents_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `files_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `files_file_type_id_foreign` FOREIGN KEY (`file_type_id`) REFERENCES `file_types` (`id`);

--
-- Constraints for table `received_documents`
--
ALTER TABLE `received_documents`
  ADD CONSTRAINT `received_documents_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `received_documents_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `received_documents_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `tags` (`id`),
  ADD CONSTRAINT `received_documents_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tags_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_has_permissions`
--
ALTER TABLE `user_has_permissions`
  ADD CONSTRAINT `user_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_has_roles`
--
ALTER TABLE `user_has_roles`
  ADD CONSTRAINT `user_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
