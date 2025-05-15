-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 06:42 PM
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
-- Database: `testingdb2`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Logout', 'admin123 (Admin) has logged out.', '2025-04-30 01:14:40', '2025-04-30 01:14:40'),
(2, 2, 'Logout', 'owner123 (Owner) has logged out.', '2025-04-30 01:15:17', '2025-04-30 01:15:17'),
(3, 1, 'Login', 'admin123 (Admin) has logged in.', '2025-04-30 01:15:27', '2025-04-30 01:15:27'),
(4, 1, 'Create', 'admin123 (Admin) Created Category Titled Construction Materials', '2025-04-30 01:16:08', '2025-04-30 01:16:08'),
(5, 1, 'Create', 'admin123 (Admin) Created Category Titled Hardware', '2025-04-30 01:16:15', '2025-04-30 01:16:15'),
(6, 1, 'Create', 'admin123 (Admin) Created Category Titled Wall Finishing', '2025-04-30 01:16:24', '2025-04-30 01:16:24'),
(7, 1, 'Create', 'admin123 (Admin) Created Category Titled Roofing Materials', '2025-04-30 01:16:33', '2025-04-30 01:16:33'),
(8, 1, 'Create', 'admin123 (Admin) Created Category Titled Electrical Supplies', '2025-04-30 01:16:44', '2025-04-30 01:16:44'),
(9, 1, 'Create', 'admin123 (Admin) Created Category Titled Painting Tools', '2025-04-30 01:16:53', '2025-04-30 01:16:53'),
(10, 1, 'Create', 'admin123 (Admin) Created Category Titled Painting Supplies', '2025-04-30 01:17:04', '2025-04-30 01:17:04'),
(11, 1, 'Create', 'admin123 (Admin) Created Supplier ID: 1', '2025-04-30 01:18:10', '2025-04-30 01:18:10'),
(12, 1, 'Create', 'admin123 (Admin) Created Supplier ID: 2', '2025-04-30 01:18:36', '2025-04-30 01:18:36'),
(13, 1, 'Create', 'admin123 (Admin) Created Supplier ID: 3', '2025-04-30 01:19:01', '2025-04-30 01:19:01'),
(14, 1, 'Create', 'admin123 (Admin) Created Supplier ID: 4', '2025-04-30 01:19:21', '2025-04-30 01:19:21'),
(15, 1, 'Create', 'admin123 (Admin) Created Unit Titled Roll', '2025-04-30 01:19:40', '2025-04-30 01:19:40'),
(16, 1, 'Create', 'admin123 (Admin) Created Unit Titled Piece', '2025-04-30 01:19:46', '2025-04-30 01:19:46'),
(17, 1, 'Create', 'admin123 (Admin) Created Unit Titled Bag', '2025-04-30 01:19:54', '2025-04-30 01:19:54'),
(18, 1, 'Create', 'admin123 (Admin) Created Unit Titled Box', '2025-04-30 01:19:58', '2025-04-30 01:19:58'),
(19, 1, 'Create', 'admin123 (Admin) Created Unit Titled Liter', '2025-04-30 01:20:05', '2025-04-30 01:20:05'),
(20, 1, 'Create', 'admin123 (Admin) Created Product ID: 1', '2025-04-30 01:21:51', '2025-04-30 01:21:51'),
(21, 1, 'Create', 'admin123 (Admin) Created Product ID: 2', '2025-04-30 01:23:03', '2025-04-30 01:23:03'),
(22, 1, 'Create', 'admin123 (Admin) Created Product ID: 3', '2025-04-30 01:23:45', '2025-04-30 01:23:45'),
(23, 1, 'Create', 'admin123 (Admin) Created Product ID: 4', '2025-04-30 01:24:42', '2025-04-30 01:24:42'),
(24, 1, 'Create', 'admin123 (Admin) Created Product ID: 5', '2025-04-30 01:25:50', '2025-04-30 01:25:50'),
(25, 1, 'Create', 'admin123 (Admin) Created Product ID: 6', '2025-04-30 01:26:58', '2025-04-30 01:26:58'),
(26, 1, 'Create', 'admin123 (Admin) Created Product ID: 7', '2025-04-30 01:28:30', '2025-04-30 01:28:30'),
(27, 1, 'Create', 'admin123 (Admin) Created Product ID: 8', '2025-04-30 01:29:36', '2025-04-30 01:29:36'),
(28, 1, 'Create', 'admin123 (Admin) Created Product ID: 9', '2025-04-30 01:30:25', '2025-04-30 01:30:25'),
(29, 1, 'Create', 'admin123 (Admin) Created Product ID: 10', '2025-04-30 01:31:10', '2025-04-30 01:31:10'),
(30, 1, 'Create', 'admin123 (Admin) Created a new Staff', '2025-04-30 01:31:37', '2025-04-30 01:31:37'),
(31, 1, 'Create', 'admin123 (Admin) Created a new Staff', '2025-04-30 01:32:08', '2025-04-30 01:32:08'),
(32, 1, 'Logout', 'admin123 (Admin) has logged out.', '2025-04-30 01:32:18', '2025-04-30 01:32:18'),
(33, 3, 'Login', 'cashier123 (Cashier ID: 3) has logged in.', '2025-04-30 01:32:23', '2025-04-30 01:32:23'),
(34, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Customer ID: 1', '2025-04-30 01:36:43', '2025-04-30 01:36:43'),
(35, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Customer ID: 2', '2025-04-30 01:37:38', '2025-04-30 01:37:38'),
(36, 3, 'Delivery', 'A New Delivery Created for Sale ID: 1', '2025-04-30 01:38:25', '2025-04-30 01:38:25'),
(37, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 1', '2025-04-30 01:38:25', '2025-04-30 01:38:25'),
(38, 3, 'Delivery', 'A New Delivery Created for Sale ID: 2', '2025-04-30 02:07:28', '2025-04-30 02:07:28'),
(39, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 2', '2025-04-30 02:07:28', '2025-04-30 02:07:28'),
(40, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 3', '2025-04-30 02:08:59', '2025-04-30 02:08:59'),
(41, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 4', '2025-04-30 02:10:04', '2025-04-30 02:10:04'),
(42, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 5', '2025-04-30 02:14:52', '2025-04-30 02:14:52'),
(43, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 6', '2025-04-30 02:15:19', '2025-04-30 02:15:19'),
(44, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 7', '2025-04-30 02:18:39', '2025-04-30 02:18:39'),
(45, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 8', '2025-04-30 02:20:03', '2025-04-30 02:20:03'),
(46, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 9', '2025-04-30 02:21:06', '2025-04-30 02:21:06'),
(47, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 10', '2025-04-30 02:23:20', '2025-04-30 02:23:20'),
(48, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 11', '2025-04-30 02:24:03', '2025-04-30 02:24:03'),
(49, 3, 'Logout', 'cashier123 (Cashier ID: 3) has logged out.', '2025-04-30 02:24:59', '2025-04-30 02:24:59'),
(50, 4, 'Login', 'driver123 (Driver ID: 4) has logged in', '2025-04-30 02:27:19', '2025-04-30 02:27:19'),
(51, 4, 'Deliver', 'driver123 (Driver ID: 4) accepted delivery for John Doe', '2025-04-30 02:27:26', '2025-04-30 02:27:26'),
(52, 4, 'Complete Delivery', 'driver123 (Driver ID: 4) completed delivery for John Doe at PowerPoint, Dumaguete City.', '2025-04-30 02:27:49', '2025-04-30 02:27:49'),
(53, 4, 'Logout', 'driver123 (Driver ID: 4) has logged out', '2025-04-30 02:28:27', '2025-04-30 02:28:27'),
(54, 1, 'Login', 'admin123 (Admin) has logged in.', '2025-04-30 02:31:11', '2025-04-30 02:31:11'),
(55, 3, 'Login', 'cashier123 (Cashier ID: 3) has logged in.', '2025-04-30 02:35:31', '2025-04-30 02:35:31'),
(56, 1, 'Logout', 'admin123 (Admin) has logged out.', '2025-04-30 02:39:22', '2025-04-30 02:39:22'),
(57, 2, 'Login', 'owner123 (Owner) has logged in.', '2025-04-30 02:39:28', '2025-04-30 02:39:28'),
(58, 2, 'Logout', 'owner123 (Owner) has logged out.', '2025-04-30 02:42:12', '2025-04-30 02:42:12'),
(59, 1, 'Login', 'admin123 (Admin) has logged in.', '2025-04-30 02:43:03', '2025-04-30 02:43:03'),
(60, 1, 'Create', 'admin123 (Admin) Created a new Staff', '2025-04-30 02:43:36', '2025-04-30 02:43:36'),
(61, 1, 'Logout', 'admin123 (Admin) has logged out.', '2025-04-30 02:43:40', '2025-04-30 02:43:40'),
(62, 5, 'Login', 'staff123 (Staff ID: 5) has logged in.', '2025-04-30 02:43:47', '2025-04-30 02:43:47'),
(63, 5, 'Logout', 'staff123 (Staff ID: 5) has logged out.', '2025-04-30 02:54:34', '2025-04-30 02:54:34'),
(64, 1, 'Login', 'admin123 (Admin) has logged in.', '2025-04-30 02:54:47', '2025-04-30 02:54:47'),
(65, 1, 'Login', 'admin123 (Admin) has logged in.', '2025-04-30 06:58:27', '2025-04-30 06:58:27'),
(66, 3, 'Login', 'cashier123 (Cashier ID: 3) has logged in.', '2025-04-30 14:05:45', '2025-04-30 14:05:45'),
(67, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 12', '2025-04-30 14:07:07', '2025-04-30 14:07:07'),
(68, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 13', '2025-04-30 14:19:23', '2025-04-30 14:19:23'),
(69, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 14', '2025-04-30 14:20:20', '2025-04-30 14:20:20'),
(70, 1, 'Login', 'admin123 (Admin) has logged in.', '2025-04-30 14:25:00', '2025-04-30 14:25:00'),
(71, 3, 'Delivery', 'A New Delivery Created for Sale ID: 15', '2025-04-30 15:32:19', '2025-04-30 15:32:19'),
(72, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 15', '2025-04-30 15:32:19', '2025-04-30 15:32:19'),
(73, 3, 'Delivery', 'A New Delivery Created for Sale ID: 16', '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(74, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 16', '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(75, 3, 'Create', 'cashier123 (Cashier ID: 3) Created Sale ID: 17', '2025-04-30 16:25:18', '2025-04-30 16:25:18'),
(76, 1, 'Login', 'admin123 (Admin) has logged in.', '2025-04-30 16:28:14', '2025-04-30 16:28:14'),
(77, 1, 'Logout', 'admin123 (Admin) has logged out.', '2025-04-30 16:32:32', '2025-04-30 16:32:32'),
(78, 2, 'Login', 'owner123 (Owner) has logged in.', '2025-04-30 16:32:40', '2025-04-30 16:32:40'),
(79, 3, 'Logout', 'cashier123 (Cashier ID: 3) has logged out.', '2025-04-30 16:37:18', '2025-04-30 16:37:18'),
(80, 2, 'Logout', 'owner123 (Owner) has logged out.', '2025-04-30 16:37:22', '2025-04-30 16:37:22'),
(81, 4, 'Login', 'driver123 (Driver ID: 4) has logged in', '2025-04-30 16:39:44', '2025-04-30 16:39:44'),
(82, 4, 'Deliver', 'driver123 (Driver ID: 4) accepted delivery for John Doe', '2025-04-30 16:39:53', '2025-04-30 16:39:53'),
(83, 4, 'Complete Delivery', 'driver123 (Driver ID: 4) completed delivery for John Doe at PowerPoint, Dumaguete City.', '2025-04-30 16:40:27', '2025-04-30 16:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `prefix`, `created_at`, `updated_at`) VALUES
(1, 'Construction Materials', 'CNSTR_MTRLS', '2025-04-30 01:16:08', '2025-04-30 01:16:08'),
(2, 'Hardware', 'HRDWR', '2025-04-30 01:16:15', '2025-04-30 01:16:15'),
(3, 'Wall Finishing', 'WLL_FNSHN', '2025-04-30 01:16:24', '2025-04-30 01:16:24'),
(4, 'Roofing Materials', 'RFNG_MTRLS', '2025-04-30 01:16:33', '2025-04-30 01:16:33'),
(5, 'Electrical Supplies', 'LCTRC_SPPLS', '2025-04-30 01:16:44', '2025-04-30 01:16:44'),
(6, 'Painting Tools', 'PNTNG_TLS', '2025-04-30 01:16:53', '2025-04-30 01:16:53'),
(7, 'Painting Supplies', 'PNTNG_SPPLS', '2025-04-30 01:17:04', '2025-04-30 01:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `username`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'John', 'Doe', 'johndoe123', NULL, '09123234', 'PowerPoint, Dumaguete City', '2025-04-30 01:36:43', '2025-04-30 01:36:43'),
(2, 'Louie', 'Acabal', 'louie_acabal123', NULL, '09685578652', 'Siliman Avenue, Dumaguete City', '2025-04-30 01:37:38', '2025-04-30 01:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'PENDING',
  `verified` varchar(255) NOT NULL DEFAULT 'NO',
  `verification` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `sale_id`, `user_id`, `status`, `verified`, `verification`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'PENDING', 'NO', NULL, '2025-04-30 01:38:25', '2025-04-30 01:38:25'),
(2, 2, 4, 'COMPLETE', 'YES', 'deliveries/verifications/verification_2-20250430-102748.jpeg', '2025-04-30 02:07:28', '2025-04-30 02:27:49'),
(3, 15, 4, 'COMPLETE', 'YES', 'deliveries/verifications/verification_3-20250501-004026.jpeg', '2025-04-30 15:32:19', '2025-04-30 16:40:27'),
(4, 16, NULL, 'PENDING', 'NO', NULL, '2025-04-30 16:16:19', '2025-04-30 16:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000010_create_cache_table', 1),
(3, '0001_01_01_000020_create_jobs_table', 1),
(4, '2024_11_05_061030_create_categories_table', 1),
(5, '2024_11_05_061140_create_suppliers_table', 1),
(6, '2024_11_05_061150_create_units_table', 1),
(7, '2024_11_05_061160_create_products_table', 1),
(8, '2024_11_05_061270_create_activity_logs_table', 1),
(9, '2024_11_05_061480_create_customers_table', 1),
(10, '2024_11_05_061491_create_sales_table', 1),
(11, '2024_11_15_022100_create_sales_items_table', 1),
(12, '2024_11_15_032110_create_deliveries_table', 1),
(13, '2024_11_29_181120_create_stock_movements_table', 1),
(14, '2024_12_29_123130_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(15, 'App\\Models\\User', 4, 'AppLoginToken', 'f530a46c0f1860a4fd80b96da27aee176d1dd172aa15220eaa0aa0cd7ac7374a', '[\"*\"]', '2025-04-30 16:42:15', NULL, '2025-04-30 16:39:44', '2025-04-30 16:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `cost_price` decimal(7,2) NOT NULL,
  `sell_price` decimal(7,2) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reorder_level` int(10) UNSIGNED NOT NULL,
  `stock_alert_threshold` int(10) UNSIGNED DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `cost_price`, `sell_price`, `quantity`, `category_id`, `supplier_id`, `unit_id`, `reorder_level`, `stock_alert_threshold`, `expiration_date`, `created_at`, `updated_at`) VALUES
(1, 'Electrical Wires', 1200.00, 1500.00, 54, 5, 4, 1, 20, 10, NULL, '2025-04-30 01:21:51', '2025-04-30 16:16:19'),
(2, 'Galvanized Iron (GI) Sheets', 450.00, 500.00, 26, 4, 3, 2, 30, 10, NULL, '2025-04-30 01:23:03', '2025-04-30 16:16:19'),
(3, 'Skim Coat', 250.00, 300.00, 64, 3, 1, 2, 50, 20, NULL, '2025-04-30 01:23:45', '2025-04-30 14:07:07'),
(4, 'Nail S1', 2.00, 3.00, 31, 2, 2, 2, 200, 50, NULL, '2025-04-30 01:24:42', '2025-04-30 01:38:25'),
(5, 'Cement', 150.00, 200.00, 39, 1, 1, 3, 30, 10, '2025-06-21', '2025-04-30 01:25:50', '2025-04-30 16:25:18'),
(6, 'Nail S1', 100.00, 150.00, 161, 2, 2, 4, 100, 30, NULL, '2025-04-30 01:26:58', '2025-04-30 16:16:19'),
(7, 'Paint Brush S4', 75.00, 100.00, 115, 3, 2, 2, 50, 20, NULL, '2025-04-30 01:28:30', '2025-04-30 16:16:19'),
(8, 'Paint Thinner', 200.00, 250.00, 63, 7, 2, 5, 30, 10, '2025-12-21', '2025-04-30 01:29:36', '2025-04-30 16:16:19'),
(9, 'Door Knob', 150.00, 200.00, 54, 5, 2, 2, 30, 10, NULL, '2025-04-30 01:30:25', '2025-04-30 16:16:19'),
(10, '1 Gang Universal Outlet', 150.00, 200.00, 70, 5, 4, 2, 30, 10, NULL, '2025-04-30 01:31:10', '2025-04-30 16:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_fee` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `sukli` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `vat_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(255) DEFAULT NULL,
  `customer_address` varchar(255) DEFAULT NULL,
  `delivery` varchar(255) NOT NULL DEFAULT 'NO',
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `customer_id`, `delivery_fee`, `total_amount`, `paid_amount`, `sukli`, `discount`, `vat_amount`, `discount_type`, `customer_address`, `delivery`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 100.00, 400.00, 500.00, 100.00, 10.00, 0.00, 'custom', 'Siliman Avenue, Dumaguete City', 'YES', 'PENDING', '2025-04-30 01:38:25', '2025-04-30 01:38:25'),
(2, 3, 1, 100.00, 440.00, 500.00, 60.00, 110.00, 0.00, 'senior/pwd', 'PowerPoint, Dumaguete City', 'YES', 'COMPLETE', '2025-04-30 02:07:28', '2025-04-30 02:27:49'),
(3, 3, NULL, NULL, 100.00, 100.00, 0.00, 50.00, 0.00, 'custom', NULL, 'NO', 'COMPLETE', '2025-04-30 02:08:59', '2025-04-30 02:08:59'),
(4, 3, NULL, NULL, 135.00, 200.00, 65.00, 10.00, 0.00, 'custom', NULL, 'NO', 'COMPLETE', '2025-04-30 02:10:04', '2025-04-30 02:10:04'),
(5, 3, NULL, NULL, 200.00, 200.00, 0.00, 0.00, 0.00, NULL, NULL, 'NO', 'COMPLETE', '2025-04-30 02:14:52', '2025-04-30 02:14:52'),
(6, 3, NULL, NULL, 200.00, 200.00, 0.00, 0.00, 0.00, NULL, NULL, 'NO', 'COMPLETE', '2025-04-30 02:15:19', '2025-04-30 02:15:19'),
(7, 3, NULL, NULL, 200.00, 200.00, 0.00, 0.00, 0.00, NULL, NULL, 'NO', 'COMPLETE', '2025-04-30 02:18:39', '2025-04-30 02:18:39'),
(8, 3, NULL, NULL, 200.00, 200.00, 0.00, 0.00, 0.00, NULL, NULL, 'NO', 'COMPLETE', '2025-04-30 02:20:03', '2025-04-30 02:20:03'),
(9, 3, NULL, NULL, 200.00, 200.00, 0.00, 0.00, 0.00, NULL, NULL, 'NO', 'COMPLETE', '2025-04-30 02:21:06', '2025-04-30 02:21:06'),
(10, 3, NULL, NULL, 200.00, 200.00, 0.00, 0.00, 0.00, NULL, NULL, 'NO', 'COMPLETE', '2025-04-30 02:23:20', '2025-04-30 02:23:20'),
(11, 3, NULL, NULL, 160.00, 200.00, 40.00, 40.00, 0.00, 'senior/pwd', NULL, 'NO', 'COMPLETE', '2025-04-30 02:24:03', '2025-04-30 02:24:03'),
(12, 3, NULL, NULL, 270.00, 300.00, 30.00, 10.00, 0.00, 'custom', NULL, 'NO', 'COMPLETE', '2025-04-30 14:07:07', '2025-04-30 14:07:07'),
(13, 3, NULL, NULL, 90.00, 100.00, 10.00, 10.00, 0.00, 'custom', NULL, 'NO', 'COMPLETE', '2025-04-30 14:19:23', '2025-04-30 14:19:23'),
(14, 3, NULL, NULL, 225.00, 500.00, 275.00, 25.00, 0.00, 'custom', NULL, 'NO', 'COMPLETE', '2025-04-30 14:20:20', '2025-04-30 14:20:20'),
(15, 3, 1, 100.00, 1881.60, 2000.00, 118.40, 420.00, 201.60, 'senior/pwd', 'PowerPoint, Dumaguete City', 'YES', 'COMPLETE', '2025-04-30 15:32:19', '2025-04-30 16:40:27'),
(16, 3, 2, 300.00, 4200.00, 5000.00, 800.00, 250.00, 450.00, 'custom', 'Siliman Avenue, Dumaguete City', 'YES', 'PENDING', '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(17, 3, NULL, NULL, 224.00, 500.00, 276.00, 0.00, 24.00, NULL, NULL, 'NO', 'COMPLETE', '2025-04-30 16:25:18', '2025-04-30 16:25:18');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `unit` varchar(255) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `total_amount` decimal(7,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `unit`, `quantity`, `price`, `total_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'Piece', 20, 3.00, 60.00, '2025-04-30 01:38:25', '2025-04-30 01:38:25'),
(2, 1, 8, 'Liter', 1, 250.00, 250.00, '2025-04-30 01:38:25', '2025-04-30 01:38:25'),
(3, 2, 6, 'Box', 3, 150.00, 450.00, '2025-04-30 02:07:28', '2025-04-30 02:07:28'),
(4, 3, 6, 'Box', 1, 150.00, 150.00, '2025-04-30 02:08:59', '2025-04-30 02:08:59'),
(5, 4, 6, 'Box', 1, 150.00, 150.00, '2025-04-30 02:10:04', '2025-04-30 02:10:04'),
(6, 5, 5, 'Bag', 1, 200.00, 200.00, '2025-04-30 02:14:52', '2025-04-30 02:14:52'),
(7, 6, 5, 'Bag', 1, 200.00, 200.00, '2025-04-30 02:15:19', '2025-04-30 02:15:19'),
(8, 7, 5, 'Bag', 1, 200.00, 200.00, '2025-04-30 02:18:39', '2025-04-30 02:18:39'),
(9, 8, 5, 'Bag', 1, 200.00, 200.00, '2025-04-30 02:20:03', '2025-04-30 02:20:03'),
(10, 9, 5, 'Bag', 1, 200.00, 200.00, '2025-04-30 02:21:06', '2025-04-30 02:21:06'),
(11, 10, 5, 'Bag', 1, 200.00, 200.00, '2025-04-30 02:23:20', '2025-04-30 02:23:20'),
(12, 11, 5, 'Bag', 1, 200.00, 200.00, '2025-04-30 02:24:03', '2025-04-30 02:24:03'),
(13, 12, 3, 'Piece', 1, 300.00, 300.00, '2025-04-30 14:07:07', '2025-04-30 14:07:07'),
(14, 13, 7, 'Piece', 1, 100.00, 100.00, '2025-04-30 14:19:23', '2025-04-30 14:19:23'),
(15, 14, 8, 'Liter', 1, 250.00, 250.00, '2025-04-30 14:20:20', '2025-04-30 14:20:20'),
(16, 15, 5, 'Bag', 10, 200.00, 2000.00, '2025-04-30 15:32:19', '2025-04-30 15:32:19'),
(17, 16, 1, 'Roll', 1, 1500.00, 1500.00, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(18, 16, 2, 'Piece', 1, 500.00, 500.00, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(19, 16, 10, 'Piece', 5, 200.00, 1000.00, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(20, 16, 9, 'Piece', 1, 200.00, 200.00, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(21, 16, 8, 'Liter', 1, 250.00, 250.00, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(22, 16, 7, 'Piece', 1, 100.00, 100.00, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(23, 16, 6, 'Box', 1, 150.00, 150.00, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(24, 17, 5, 'Bag', 1, 200.00, 200.00, '2025-04-30 16:25:18', '2025-04-30 16:25:18');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('hJnbPrVq337WUzXsbh8aZnf91ROFfV1OE6U8I7th', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS2lXVjZKeDBmTkZva1o5c0J6emJIMHh6bWwzUjlja0FoeGpiM2FWRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1746031043),
('jJfzdiXRB7ugbiT3etBDCphxJwlf7nms2hhxk2NW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3RBcDRvejRQRU5HcldtbWNtWExsa05lbHgycW5SOWwyUFZzN1BJViI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1746031038),
('Qw8bjXnZ1pkZAbUBYFdg7x9XejoieWvmKGto4sni', NULL, '192.168.78.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoickZ2R0tsNzZpbFdIa1N4U2h2cktzNXp6amtSSXA3N1FFTGs2a0pvQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8xOTIuMTY4Ljc4LjgxOjgwMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1746031141),
('V3Xpr13ybjhXP3pvW6UjPZgGN6wvXk0T9zfjEtVz', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidHFuZTNUUk9pU2dvc1d2YXZKeVRKZnNPZW5RQ1M4ZzZuVmRqNmFOdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9yZWNlaXB0cy8xNCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo5OiJhcGlfdG9rZW4iO3M6NTE6IjEyfHhKeDh6QXAyNWFEd25wV3FwUHU0VXl5TVBXV2RuQWpsc24xdXlzMjM4NjIyYjMwYSI7fQ==', 1746023188);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('Added','Sold') NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `type`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 4, 'Sold', 20, '2025-04-30 01:38:25', '2025-04-30 01:38:25'),
(2, 8, 'Sold', 1, '2025-04-30 01:38:25', '2025-04-30 01:38:25'),
(3, 6, 'Sold', 3, '2025-04-30 02:07:28', '2025-04-30 02:07:28'),
(4, 6, 'Sold', 1, '2025-04-30 02:08:59', '2025-04-30 02:08:59'),
(5, 6, 'Sold', 1, '2025-04-30 02:10:04', '2025-04-30 02:10:04'),
(6, 5, 'Sold', 1, '2025-04-30 02:14:52', '2025-04-30 02:14:52'),
(7, 5, 'Sold', 1, '2025-04-30 02:15:19', '2025-04-30 02:15:19'),
(8, 5, 'Sold', 1, '2025-04-30 02:18:39', '2025-04-30 02:18:39'),
(9, 5, 'Sold', 1, '2025-04-30 02:20:03', '2025-04-30 02:20:03'),
(10, 5, 'Sold', 1, '2025-04-30 02:21:06', '2025-04-30 02:21:06'),
(11, 5, 'Sold', 1, '2025-04-30 02:23:20', '2025-04-30 02:23:20'),
(12, 5, 'Sold', 1, '2025-04-30 02:24:03', '2025-04-30 02:24:03'),
(13, 3, 'Sold', 1, '2025-04-30 14:07:07', '2025-04-30 14:07:07'),
(14, 7, 'Sold', 1, '2025-04-30 14:19:23', '2025-04-30 14:19:23'),
(15, 8, 'Sold', 1, '2025-04-30 14:20:20', '2025-04-30 14:20:20'),
(16, 5, 'Sold', 10, '2025-04-30 15:32:19', '2025-04-30 15:32:19'),
(17, 1, 'Sold', 1, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(18, 2, 'Sold', 1, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(19, 10, 'Sold', 5, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(20, 9, 'Sold', 1, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(21, 8, 'Sold', 1, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(22, 7, 'Sold', 1, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(23, 6, 'Sold', 1, '2025-04-30 16:16:19', '2025-04-30 16:16:19'),
(24, 5, 'Sold', 1, '2025-04-30 16:25:18', '2025-04-30 16:25:18');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Construction Supplier', NULL, '09123345', 'ConstructionSupplierAddress', '2025-04-30 01:18:10', '2025-04-30 01:18:10'),
(2, 'Hardware Supplier', 'hardware@gmail.com', '09123123234', 'HardwareSupplierAddress', '2025-04-30 01:18:36', '2025-04-30 01:18:36'),
(3, 'Steel Supplier', NULL, '0912334547', 'SteelSupplierAddress', '2025-04-30 01:19:01', '2025-04-30 01:19:01'),
(4, 'Electrical Supplier', 'electrical@gmail.com', '092345467', 'ElectricalSupplierAddress', '2025-04-30 01:19:21', '2025-04-30 01:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Roll', '2025-04-30 01:19:40', '2025-04-30 01:19:40'),
(2, 'Piece', '2025-04-30 01:19:46', '2025-04-30 01:19:46'),
(3, 'Bag', '2025-04-30 01:19:54', '2025-04-30 01:19:54'),
(4, 'Box', '2025-04-30 01:19:58', '2025-04-30 01:19:58'),
(5, 'Liter', '2025-04-30 01:20:05', '2025-04-30 01:20:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `usertype` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Offline',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `usertype`, `phone`, `address`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Louie', 'Acabal', 'admin123', 'admin@admin', 'Admin', '09123456789', 'Tanjay City', 'Offline', NULL, '$2y$12$RZMyeNNS4gP96p8IHTXDuOdYEVMt27cjMzCS/C2Dqa99V.LdtAWpW', NULL, '2025-04-30 01:14:16', '2025-04-30 16:32:32'),
(2, 'John', 'Acabal', 'owner123', 'owner@owner', 'Owner', '09123456789', 'Tanjay City', 'Offline', NULL, '$2y$12$r/rZEzDLKd4t5l13d8X9aO8pu2PCeitQH5vKz06ODbSh/JqtFq5OC', NULL, '2025-04-30 01:15:11', '2025-04-30 16:37:22'),
(3, 'Louie', 'John', 'cashier123', NULL, 'Cashier', NULL, NULL, 'Offline', NULL, '$2y$12$hnXRFHcDfq6dtahUei7Toei1ezSrjIyn9AuDfuF4Gg/RGRt6GsYYy', NULL, '2025-04-30 01:31:37', '2025-04-30 16:37:18'),
(4, 'Juan', 'Cruz', 'driver123', NULL, 'Driver', NULL, NULL, 'Online', NULL, '$2y$12$cWI4/PdgXiUCekZlwtzYB.E5js6RYDlS8E4Qfwt4jz4RWUTm2Um1C', NULL, '2025-04-30 01:32:08', '2025-04-30 16:39:44'),
(5, 'Antonio', 'Pedro', 'staff123', NULL, 'Staff', NULL, NULL, 'Offline', NULL, '$2y$12$CfLPBLeHx5PpcBQUWSZwc.xCKY8h6v9uBVO.lLxd5Ne1kb3nBE4Nu', NULL, '2025-04-30 02:43:36', '2025-04-30 02:54:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_prefix_unique` (`prefix`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_username_unique` (`username`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deliveries_sale_id_foreign` (`sale_id`),
  ADD KEY `deliveries_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_supplier_id_foreign` (`supplier_id`),
  ADD KEY `products_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_user_id_foreign` (`user_id`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_product_id_foreign` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deliveries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
