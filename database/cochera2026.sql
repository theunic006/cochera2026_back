-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-09-2025 a las 18:00:50
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cochera2026`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
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
-- Estructura de tabla para la tabla `jobs`
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
-- Estructura de tabla para la tabla `job_batches`
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
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_24_154249_create_suscribers_table', 2),
(5, '2025_09_24_155230_create_personal_access_tokens_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '3a3784d36067d1504eacd18de20d11966c8875c6b467c22026ec6ad4784a6e7a', '[\"*\"]', NULL, NULL, '2025-09-25 00:04:58', '2025-09-25 00:04:58'),
(2, 'App\\Models\\User', 3, 'auth_token', '85ec31fe8b6a174bef509119cabeecfe95a51c30ab898cbc4dd786d367b8f49a', '[\"*\"]', NULL, NULL, '2025-09-25 00:05:32', '2025-09-25 00:05:32'),
(3, 'App\\Models\\User', 1, 'auth_token', '1b087b32d1dea8f328ada5cb545450be2766a2bf5916b7af52696b8bbb507464', '[\"*\"]', NULL, NULL, '2025-09-25 00:09:50', '2025-09-25 00:09:50'),
(4, 'App\\Models\\User', 1, 'auth_token', 'e6f895a3abe8048dc4239e24d23bf1366cdf9a67e5e98fa0f5d754fc279c8555', '[\"*\"]', NULL, NULL, '2025-09-25 09:52:10', '2025-09-25 09:52:10'),
(5, 'App\\Models\\User', 1, 'auth_token', '4560911f2c3de7276c4fda83715737c9ad96aad8d47ed21fcb83c99ab24ab251', '[\"*\"]', NULL, NULL, '2025-09-25 09:55:30', '2025-09-25 09:55:30'),
(6, 'App\\Models\\User', 1, 'auth_token', '9cae477dfa27be18945fbcec13a432d352ab1d63b56f68e1c9d2c0a011a0fc06', '[\"*\"]', NULL, NULL, '2025-09-25 09:56:27', '2025-09-25 09:56:27'),
(7, 'App\\Models\\User', 1, 'auth_token', '5001d770cce1ac0ba6efa64e791a68b32946903f6c5647910c349f855c51d1b7', '[\"*\"]', NULL, NULL, '2025-09-25 09:58:44', '2025-09-25 09:58:44'),
(8, 'App\\Models\\User', 1, 'auth_token', 'ba7b41cbc9dcc7640c1b30571361969078ae5d96301b56bee668c556ec6e7b5f', '[\"*\"]', NULL, NULL, '2025-09-25 09:59:11', '2025-09-25 09:59:11'),
(9, 'App\\Models\\User', 1, 'auth_token', '0343c136ad36df48190d7ce4b86fe477182758ce541e59a4797bfc0c99f1ce4e', '[\"*\"]', NULL, NULL, '2025-09-25 10:05:34', '2025-09-25 10:05:34'),
(10, 'App\\Models\\User', 1, 'auth_token', 'ca60d467487983fec8180f42f8114875608b8dc147235b83f05d8a7d941a6091', '[\"*\"]', NULL, NULL, '2025-09-25 10:09:22', '2025-09-25 10:09:22'),
(11, 'App\\Models\\User', 1, 'auth_token', '154050331023e32d88b607ac6ebd7418371e29f5ae4aaafa52ae02a1ed54c941', '[\"*\"]', NULL, NULL, '2025-09-25 10:13:53', '2025-09-25 10:13:53'),
(12, 'App\\Models\\User', 1, 'auth_token', '06605d06f9499fec018ce822d14c6d96419aa3f1ba7ee0efd637926d175d2e2b', '[\"*\"]', NULL, NULL, '2025-09-25 10:14:07', '2025-09-25 10:14:07'),
(13, 'App\\Models\\User', 1, 'auth_token', '2d84e777042a5fb9b95a195197c2b19aa0417cef2229b725025f77e262ffbcde', '[\"*\"]', NULL, NULL, '2025-09-25 10:25:06', '2025-09-25 10:25:06'),
(14, 'App\\Models\\User', 1, 'auth_token', 'be37b564b8304f77e2f9cb6a90322f4d80a7efbebb08fb0af8f325d75cce3ca7', '[\"*\"]', NULL, NULL, '2025-09-25 10:31:58', '2025-09-25 10:31:58'),
(15, 'App\\Models\\User', 1, 'auth_token', 'c8572a84efc20c240d101ea66c0e0a6d94984b580b8f82b381687cb21938dbde', '[\"*\"]', NULL, NULL, '2025-09-25 10:35:25', '2025-09-25 10:35:25'),
(16, 'App\\Models\\User', 1, 'auth_token', 'b47f1f18059fe9bc043b0aa83c64f93c6e4a77a7fca0026ab0b1bb77473e19ec', '[\"*\"]', NULL, NULL, '2025-09-25 10:36:09', '2025-09-25 10:36:09'),
(17, 'App\\Models\\User', 1, 'auth_token', '0b7e6ce36ff37174d91630761bdf7001403928ea4b5649061ad54ffa80b6c3bf', '[\"*\"]', NULL, NULL, '2025-09-25 10:45:56', '2025-09-25 10:45:56'),
(18, 'App\\Models\\User', 1, 'auth_token', '12018510a048a889175faaf4a5b714a0dac6c73178e5ac5e894e5929bf5d2fba', '[\"*\"]', '2025-09-25 20:55:19', NULL, '2025-09-25 11:03:34', '2025-09-25 20:55:19'),
(19, 'App\\Models\\User', 1, 'auth_token', '4d22fcbba4201f02aa00afc3c5dde3bf3293adc8605359f711bbf5d2ce057cf8', '[\"*\"]', '2025-09-25 11:20:05', NULL, '2025-09-25 11:06:50', '2025-09-25 11:20:05'),
(20, 'App\\Models\\User', 1, 'auth_token', 'cdfb3e8be788d20eb09f1b80d9122c61606093fed8cdaf856f59450e9fff256a', '[\"*\"]', NULL, NULL, '2025-09-25 11:11:00', '2025-09-25 11:11:00'),
(21, 'App\\Models\\User', 1, 'auth_token', '3fdf38e548eff170305c685e18460df30bd5d2e699be2cc55e0765a734376948', '[\"*\"]', NULL, NULL, '2025-09-25 11:20:35', '2025-09-25 11:20:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
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
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AyNi0q6o9pWoX2qLs6rRCC1R669YvaGT4fM8PVlA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUVV2TVhoOXpUVXNZdnE1cG5BVGNQbWVKSFRmcnhqb1ZCR1BCRVN3SCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758815671),
('dIrd4z4oPLFz7ABkNZDBCGOmcNIvyQep2ro3Upxl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaTJ3eElxOU1hWVpybGhTRHlvM0c2MGpHMXdOY2g1V1I3dXdTVFNqTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758729567),
('lMkLqgRoz4X0vvntqgoODx4dxTMMXmC0yrbQ39eN', 1, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNmEzZHcwdkR0clpiUU9RMzRsSldLSWtMazFSRm5MQ2hWdjk4WW1yNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvYXV0aC90ZXN0LXNlc3Npb24iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1758741378);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscribers`
--

CREATE TABLE `suscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `suscribers`
--

INSERT INTO `suscribers` (`id`, `name`, `email`, `active`, `created_at`, `updated_at`) VALUES
(1, 'juan', 'juan@correo.com', 1, '2025-09-24 21:08:51', '2025-09-24 21:08:51'),
(2, 'chicho', 'chicho@gmail.com', 1, '2025-09-24 23:27:38', '2025-09-24 23:27:38'),
(3, 'chicho2', 'chichoho@gmail.com', 1, '2025-09-24 23:28:36', '2025-09-24 23:28:36'),
(4, 'fff', 'fff@gmail.com', 1, '2025-09-24 23:29:07', '2025-09-24 23:29:07'),
(5, 'wiwi', 'ddd@gmail.com', 1, '2025-09-24 23:30:20', '2025-09-24 23:30:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@gmail.com', NULL, '$2y$12$CTIMwgSAOEuaArRLv8AkMuDMpNoduGfIMkS9M2hhdUAA0ifyMJTuW', 'ErW3S02RI6JoLlTtHNWy20Bu1sptGiMvC71xv2rWxXX0LqSON2DwU9sTWgvy', '2025-09-24 23:44:48', '2025-09-24 23:44:48'),
(2, 'chicho', 'chicho@gmail.com', NULL, '$2y$12$Twn4w9RRGg9QHQ7bDFA2aeE6kA43ax0HKdXQnBI9ORo..Ycks75e.', NULL, '2025-09-24 23:55:04', '2025-09-24 23:55:04'),
(3, 'Juan Pérez', 'juan@example.com', NULL, '$2y$12$6.w4kSg0SWAhlOWWvlcCdu/86yyIUbmfYEXD285OUpYBCWisUZxQO', NULL, '2025-09-25 00:05:32', '2025-09-25 00:05:32'),
(4, 'Nicolás Navarrete', 'ainhoa19@example.net', NULL, '$2y$12$r/X6zwjTrgOEy0MwKTKji.aAvulNZmVLxKcWb1/F9ozmbxXGH91pW', NULL, '2025-09-25 11:26:47', '2025-09-25 11:26:47'),
(5, 'Silvia Meza', 'yolanda96@example.org', NULL, '$2y$12$oQ2112HDf1mKvDPDZjhGueS7ZQm54B0P9RD7Ttoe0dNPUPGN4U8bq', NULL, '2025-09-25 11:26:47', '2025-09-25 11:26:47'),
(6, 'Gonzalo Ojeda', 'noa.carrera@example.org', NULL, '$2y$12$kN8aKByEA2sgTPHZY1/tAOkJJ7B.RZrP9RzmnYzRSVeq8yV74XOtS', NULL, '2025-09-25 11:26:47', '2025-09-25 11:26:47'),
(7, 'Aitana Guardado', 'jesquivel@example.com', NULL, '$2y$12$l7yQrlB6V5PXIv5QD6oI4u688uQhXNqPRRH4eNTNs4EOEROtrbQsy', NULL, '2025-09-25 11:26:48', '2025-09-25 11:26:48'),
(8, 'Héctor Borrego', 'lorena.escamilla@example.com', NULL, '$2y$12$.aX4REfWyMA3eMDeoflXTe7W.pk7U7ig6bn35x5GaTX6XB2meQo5y', NULL, '2025-09-25 11:26:48', '2025-09-25 11:26:48'),
(9, 'Leo De Jesús', 'zlara@example.org', NULL, '$2y$12$qajGq93/HiSQBbBwlZT4gepw8/dsNrv.its4xHXbKDfwKkzx.VpMq', NULL, '2025-09-25 11:26:48', '2025-09-25 11:26:48'),
(10, 'Olivia Valdés', 'galarza.alejandro@example.net', NULL, '$2y$12$Q.D85QGLpFG2Fg6t2OJjYOvbCRMzRnDrpYGbo.tqaVbtW9z0g8.yy', NULL, '2025-09-25 11:26:48', '2025-09-25 11:26:48'),
(11, 'Biel Arias', 'oesteban@example.org', NULL, '$2y$12$rEyKxgN3zlBnIA4vDf42JOwQ6qp5GAzmiBFvmT3TXcrvp5xSO4waO', NULL, '2025-09-25 11:26:48', '2025-09-25 11:26:48'),
(12, 'Lara Ornelas', 'pablo.sarabia@example.org', NULL, '$2y$12$ed0TvUlfa3ix.M/gO3.qgejR2LfuyAxo9rulC04wXJLngfoFX75.S', NULL, '2025-09-25 11:26:49', '2025-09-25 11:26:49'),
(13, 'Erik Domínguez', 'romo.carla@example.net', NULL, '$2y$12$3MpkURkrbbqN7Ik9XHcoZeQUCy7g0XLG9qwBlHQeFq5ygNMSLO9c2', NULL, '2025-09-25 11:26:49', '2025-09-25 11:26:49'),
(14, 'Omar Galván', 'fatima66@example.org', NULL, '$2y$12$k3/6Fp3hQWd467OODnBixeN/EkHtCgCmrMuo5YmzrKwsnFEIgiHPe', NULL, '2025-09-25 11:26:49', '2025-09-25 11:26:49'),
(15, 'Antonio Araña', 'erik.godoy@example.org', NULL, '$2y$12$m9.aMy/ItMtdhm8qQ07rceK6twUcSNUYVc0F6dBpPCYh3P4rBDjC2', NULL, '2025-09-25 11:26:49', '2025-09-25 11:26:49'),
(16, 'Nahia Bautista', 'sierra.ismael@example.com', NULL, '$2y$12$x7FxCa4OH.7LK7my6z3Xi.Wj8Cf0GnLIEzUR2zUi/xfSr/wjNpARG', NULL, '2025-09-25 11:26:50', '2025-09-25 11:26:50'),
(17, 'Rosario Leal', 'mariadolores99@example.com', NULL, '$2y$12$1YPeCyXA9w5wHjG1apORpOtc.0Jmq3N3LCxwZl4rnrzJOyFzJdLPC', NULL, '2025-09-25 11:26:50', '2025-09-25 11:26:50'),
(18, 'Santiago Antón', 'pmercado@example.com', NULL, '$2y$12$VhGmfsJs3x5MdHvJJ8f4Lethe44YhdBb8MUcuJjyV4/lkP.qItBxC', NULL, '2025-09-25 11:26:50', '2025-09-25 11:26:50'),
(19, 'Cristian Cano', 'unai.villalpando@example.com', NULL, '$2y$12$O44yvxxs1aoINZMFa3/Gdua/HAi9vGxvEh4fVnd1CY9uMkNNG8ATS', NULL, '2025-09-25 11:26:50', '2025-09-25 11:26:50'),
(20, 'Natalia Echevarría', 'mariacarmen.gurule@example.com', NULL, '$2y$12$.7ugBEJgtBUfd/Y9g.1/c.8jiLI2kZapxsb7AEVeKQIugK48xjcm.', NULL, '2025-09-25 11:26:50', '2025-09-25 11:26:50'),
(21, 'Guillermo Solorzano', 'arredondo.arnau@example.com', NULL, '$2y$12$S55Vcty5avw1R9KoLwxRUOo474OYxzhwuwDCTiLlFKSLFcORhmTKG', NULL, '2025-09-25 11:26:51', '2025-09-25 11:26:51'),
(22, 'Aitana Suárez', 'benito.josemanuel@example.net', NULL, '$2y$12$h5tRja9Xm.J9EByG8c4GL.JTOLdYHO5t3CmFokLpmG0DxJObhjMHC', NULL, '2025-09-25 11:26:51', '2025-09-25 11:26:51'),
(23, 'Ona Ureña', 'ana61@example.net', NULL, '$2y$12$Kd.RetNPBkBOqa9KTz6BvudEYBodVOk6Q9vJ9aPanxFr4wb.1o9lO', NULL, '2025-09-25 11:26:51', '2025-09-25 11:26:51'),
(24, 'Alejandra Roca', 'navarro.iker@example.com', NULL, '$2y$12$LUasWwVE9V7hPbjH4RkyGOtc7.PoUuizP/EQENlJSayNojduPK.5u', NULL, '2025-09-25 11:26:51', '2025-09-25 11:26:51'),
(25, 'Carlos Zúñiga', 'pau.aranda@example.net', NULL, '$2y$12$27vTHs5wJNIfpwtYRbX3aOD0eUk4ocFpx/jbdApis7TJsIljgg2te', NULL, '2025-09-25 11:26:52', '2025-09-25 11:26:52'),
(26, 'Adam Montaño', 'jan.sisneros@example.net', NULL, '$2y$12$ZKSOxmlzVo/0p13cGe6aVOiLhDooDEKhKf39o/jobWPPmh/L7jNiG', NULL, '2025-09-25 11:26:52', '2025-09-25 11:26:52'),
(27, 'Ana Isabel Adorno', 'ydelatorre@example.com', NULL, '$2y$12$TniJE5QfBQ1nf1Z/IkWlquNvRMZ5j9x1ft4Q22spdP4GgTx63Kr4e', NULL, '2025-09-25 11:26:52', '2025-09-25 11:26:52'),
(28, 'Jesús Carmona', 'elena.gonzales@example.org', NULL, '$2y$12$cnv1IFlGKFp8qPb1cy/lueHRGVA4KxOjVz9eArVFZb/R6t2GWsBd.', NULL, '2025-09-25 11:26:52', '2025-09-25 11:26:52'),
(29, 'Alejandra Román', 'jmelendez@example.com', NULL, '$2y$12$041nKuQWFEEsl9jQuVeM0eDf21XKEihw/sLqwI3P4yFiLRkhxMDKS', NULL, '2025-09-25 11:26:52', '2025-09-25 11:26:52'),
(30, 'Bruno Costa', 'paredes.gloria@example.org', NULL, '$2y$12$AIOJQiWCPJBgVx/wpLJPa.pzXRR972ZREHsKFyBW3jI7.1y3iLG1a', NULL, '2025-09-25 11:26:53', '2025-09-25 11:26:53'),
(31, 'Pol Serrano', 'hector23@example.com', NULL, '$2y$12$erCJjVE8TFizJFsC2LV/4uszVwfdiajafknk4mgsDl7yLkNmOCR4q', NULL, '2025-09-25 11:26:53', '2025-09-25 11:26:53'),
(32, 'Alberto Granado', 'irene.anguiano@example.com', NULL, '$2y$12$H3Gd1fzCQYMGrRd.GGQJEecwmPQrRLFEgydYFV/qcNFl1pOhwYrqu', NULL, '2025-09-25 11:26:53', '2025-09-25 11:26:53'),
(33, 'Clara Plaza', 'cpalomo@example.org', NULL, '$2y$12$GY5kOHiXM5KDDsxnz0V7o.XO6AQc0kP4mumfSbF0j08WVzcmxB3bq', NULL, '2025-09-25 11:26:53', '2025-09-25 11:26:53'),
(34, 'Gael Benavides', 'tsotelo@example.org', NULL, '$2y$12$tavphR2wQFGlXNNM7rqLwOMJR/LpsXvDClcgwodJfA3SrYdeoTfSe', NULL, '2025-09-25 11:26:53', '2025-09-25 11:26:53'),
(35, 'Inmaculada Zambrano', 'medina.pilar@example.net', NULL, '$2y$12$27ts42Xpg0jlkDRKk29x.eg2WpT.L7qvE.JtZxy8XS8drOJ57yTLW', NULL, '2025-09-25 11:26:54', '2025-09-25 11:26:54'),
(36, 'Alexandra Oliver', 'alex.santamaria@example.org', NULL, '$2y$12$HrwWJr7VuTRTWaclK/4XUO7e.PdBeyBGEDnYJxDr.U1R2rC/Nsbba', NULL, '2025-09-25 11:26:54', '2025-09-25 11:26:54'),
(37, 'Francisca Alcaráz', 'lola40@example.net', NULL, '$2y$12$KF.kkaG.CtKurTZexEgQDuumclZscO59Z1T9OxqCzCf1phppzSnJe', NULL, '2025-09-25 11:26:54', '2025-09-25 11:26:54'),
(38, 'Ainara Martos', 'santiago.grijalva@example.com', NULL, '$2y$12$wsij85cMF1mZFClXMgGa.uWGWZBrNtgr1leleERgnr7267nIr8Fke', NULL, '2025-09-25 11:26:54', '2025-09-25 11:26:54'),
(39, 'Dario Puga', 'oserrato@example.net', NULL, '$2y$12$q6NU3yVcdCuVEeyxDIp7JO/yLDPOIhOde2thr0EDN6ebWJCgXUk2W', NULL, '2025-09-25 11:26:55', '2025-09-25 11:26:55'),
(40, 'Leo Ballesteros', 'igrijalva@example.net', NULL, '$2y$12$hNBq7303u6rYlLBGtNzve.j9c.28PDfXhPFQN4eRHEIZCkZwF.NsG', NULL, '2025-09-25 11:26:55', '2025-09-25 11:26:55'),
(41, 'Aitor Mena', 'rodriquez.rodrigo@example.org', NULL, '$2y$12$nH4UsAvBWo0XmY236hUVHOF98UYrFnaeY02nbYj1V5SCx/H2.A7/6', NULL, '2025-09-25 11:26:55', '2025-09-25 11:26:55'),
(42, 'Aitana Torres', 'bcovarrubias@example.com', NULL, '$2y$12$NFcmLjyjSfP7Uil7gQM9.ew9hXn7Nzj8TJbqgDWRnscxYhtgdwuE2', NULL, '2025-09-25 11:26:55', '2025-09-25 11:26:55'),
(43, 'Omar Arce', 'victor54@example.com', NULL, '$2y$12$nTUzoI4WycfG69peNJnObeIWpi81ei6VHDgUFq6oQuVYQWWf3tyCy', NULL, '2025-09-25 11:26:55', '2025-09-25 11:26:55'),
(44, 'Pablo Beltrán', 'carmen.chavarria@example.org', NULL, '$2y$12$9tqKm2HeH4H2fdrqNjpfYukBqK6h07vC/UR6T9FASrZqWWwoD3Gf.', NULL, '2025-09-25 11:26:56', '2025-09-25 11:26:56'),
(45, 'Mateo Cervántez', 'xprado@example.org', NULL, '$2y$12$bcNS0s2yScEamZnDvz3GdOEwdta4cK4E9kxfwWQ2BISeliTgWNmvS', NULL, '2025-09-25 11:26:56', '2025-09-25 11:26:56'),
(46, 'Alba Gil', 'casillas.cesar@example.net', NULL, '$2y$12$SMwJXslFjNQQxfy9sqMphehX/8S6qcV7haze/3VvyHjHQktKCepZ6', NULL, '2025-09-25 11:26:56', '2025-09-25 11:26:56'),
(47, 'Ángel Ulibarri', 'briones.ruben@example.org', NULL, '$2y$12$EOpyKxoUgdvNxvn5ZcEQ1.sF4DwwcMtKvyqhW.iJum2tIGwUO2h92', NULL, '2025-09-25 11:26:56', '2025-09-25 11:26:56'),
(48, 'Yago Cano', 'diego74@example.org', NULL, '$2y$12$EytBeyKjQ7sffYtMWhHKyu9AE9F7ZxR5MabOlk6ABfv7fmZZGmMvq', NULL, '2025-09-25 11:26:56', '2025-09-25 11:26:56'),
(49, 'Yolanda Longoria', 'josemanuel.domingo@example.net', NULL, '$2y$12$liMsLeHIY/yuSlphxikYqeHGkMcI9jngWuB.wBo255JKkZn9eUhsG', NULL, '2025-09-25 11:26:57', '2025-09-25 11:26:57'),
(50, 'Iker Haro', 'ncastillo@example.net', NULL, '$2y$12$Ed7ELtHbH6U6X5ClBrWns.xowL5XKhM03ioFNl6oMOtxD0mGYEWr.', NULL, '2025-09-25 11:26:57', '2025-09-25 11:26:57'),
(51, 'Alejandro Jaime', 'kaparicio@example.com', NULL, '$2y$12$JQQlNqJp1VEYauXGKli7vu1Wl44vP09SLpC.VPHtfD2voJgGVXLra', NULL, '2025-09-25 11:26:57', '2025-09-25 11:26:57'),
(52, 'Aleix Cobo', 'casares.luis@example.net', NULL, '$2y$12$2EpF8PNw4gCMdAbyIPspNOeLsgE4Oius27Ng66tse/zWxUcu3xWE.', NULL, '2025-09-25 11:26:57', '2025-09-25 11:26:57'),
(53, 'Teresa Ayala', 'urias.aitor@example.org', NULL, '$2y$12$AATmVbi5CM1irJXBAPgZmOxQgauDtRKqi5Etl.4WL4DXCzKqR.cDG', NULL, '2025-09-25 11:26:58', '2025-09-25 11:26:58'),
(54, 'Gerard Escalante', 'jordi70@example.net', NULL, '$2y$12$Qqsj0wI3grZ.ftK.DbOzFuxCBAj8NFChTMJdHF7Z.5A.RtQorcAGi', NULL, '2025-09-25 11:26:58', '2025-09-25 11:26:58'),
(55, 'Juana Abrego', 'joel08@example.com', NULL, '$2y$12$HJF3JPrwsxx5NbVZWiXDLe/Iose0H1OOHLKqFi9t7Okga1.sIKs3a', NULL, '2025-09-25 11:26:58', '2025-09-25 11:26:58'),
(56, 'Saúl Padilla', 'carretero.pau@example.com', NULL, '$2y$12$CyVg78bnLZK0nPvqW.p5.e8g4A15KJM/1YLoGSqUMsl7o1ji16L1i', NULL, '2025-09-25 11:26:58', '2025-09-25 11:26:58'),
(57, 'Gabriela Páez', 'aurora.uribe@example.com', NULL, '$2y$12$YAHbDvamyoMbwyzs2PmcX.gnFYvSaTahtrtPRUrckbXYTuWDFzNWq', NULL, '2025-09-25 11:26:58', '2025-09-25 11:26:58'),
(58, 'Leo Brito', 'zmarco@example.com', NULL, '$2y$12$NzPhEQI7DXRXIpKPv3oJfeS9bmF6k8hBMIfxSRnEwAdYElkCBVIne', NULL, '2025-09-25 11:26:59', '2025-09-25 11:26:59'),
(59, 'Malak Segura', 'uprieto@example.net', NULL, '$2y$12$9XgmhiM/e27MpkGxmjr/uOB7preX6kWrnosz7kpOk75uAauHmZBkK', NULL, '2025-09-25 11:26:59', '2025-09-25 11:26:59'),
(60, 'Gerard Leiva', 'santiago.arnau@example.org', NULL, '$2y$12$dCDRvPiZuUCrm/DAndiP.uMZyfyezBKqStV5QSC3dQpiDOgaYwFy6', NULL, '2025-09-25 11:26:59', '2025-09-25 11:26:59'),
(61, 'Rodrigo Ayala', 'vidal.mateo@example.com', NULL, '$2y$12$t0NXvmF1ZpGh98oAy1yi1.Z9pBQJFxz/avoYbprLWhyzarvz/IjbG', NULL, '2025-09-25 11:26:59', '2025-09-25 11:26:59'),
(62, 'Jon Pedroza', 'ursula.casarez@example.com', NULL, '$2y$12$bP8EC.CUdFlWaOvSHlGqQ.kyQQgChOImYq/XpJw5oOhm5435Z/seS', NULL, '2025-09-25 11:27:00', '2025-09-25 11:27:00'),
(63, 'Amparo Segovia', 'qmata@example.net', NULL, '$2y$12$cQJhhusxlr7ANWPKAmiWCuOiTlYjRjX4nUyg2VQmL0Cph85essw9u', NULL, '2025-09-25 11:27:00', '2025-09-25 11:27:00'),
(64, 'Yeray Castaño', 'fvillalba@example.org', NULL, '$2y$12$dze6OoljmGhyryN1/h.tLOsqOS5xNIUZzL9RHG7evuF/DH7t/AGCq', NULL, '2025-09-25 11:27:00', '2025-09-25 11:27:00'),
(65, 'Alejandro Izquierdo', 'josemanuel.elizondo@example.com', NULL, '$2y$12$uKJysACTL1X9ejMWVFWtcu6b8Osu/t6JuFKCZHEQU7vqRqA3jX7DC', NULL, '2025-09-25 11:27:00', '2025-09-25 11:27:00'),
(66, 'Nayara Vidal', 'mar13@example.org', NULL, '$2y$12$8JhmAin2KMvifX5Of97OpOvZ0X4Mp4NFk79.SvbKhIffs4aUroo2C', NULL, '2025-09-25 11:27:00', '2025-09-25 11:27:00'),
(67, 'Claudia Domingo', 'bayala@example.net', NULL, '$2y$12$hK1GPdVwAQVxFYC5XrjJWObHu14dyI.DxUjRXEOW2Z12LXuZsBiw6', NULL, '2025-09-25 11:27:01', '2025-09-25 11:27:01'),
(68, 'Alba Escobar', 'asier94@example.net', NULL, '$2y$12$9zocjFwIAQqre4C48mopgebUgMZ/adccyc2QIAeKydbaTLdMUBHsa', NULL, '2025-09-25 11:27:01', '2025-09-25 11:27:01'),
(69, 'Sara Ozuna', 'lucas.orta@example.com', NULL, '$2y$12$6dVZRzlO3roNLLw083zN4eO1IGphQBN03Tap1JuSDqiU40tOvl.wu', NULL, '2025-09-25 11:27:01', '2025-09-25 11:27:01'),
(70, 'Álvaro Pichardo', 'enrique82@example.org', NULL, '$2y$12$GROG7LFDgPxguIVUs.x7GeWkaUlUaf8KNxkambALNM8V3POuQgdDW', NULL, '2025-09-25 11:27:01', '2025-09-25 11:27:01'),
(71, 'Carmen Orosco', 'alaniz.carlos@example.org', NULL, '$2y$12$sUhcFlvk5mSH0wp5lNjgwe1JB4T7ERsM4aO93TQGrQ74u5.gJ6bzG', NULL, '2025-09-25 11:27:01', '2025-09-25 11:27:01'),
(72, 'María Carmen Palomo', 'lcastellano@example.com', NULL, '$2y$12$E0m888ACHARFsP7saqFIDOHez/xzUJfR6t8genk4Q4LyBcxiHuO6.', NULL, '2025-09-25 11:27:02', '2025-09-25 11:27:02'),
(73, 'Isabel Arias', 'gonzalo03@example.org', NULL, '$2y$12$Xk2dohPofiwsQfuGgPBYsuLXopZbgpz6jqFhIgC8R8L3YdvkvdcQm', NULL, '2025-09-25 11:27:02', '2025-09-25 11:27:02'),
(74, 'Andrés Reynoso', 'adriana02@example.com', NULL, '$2y$12$G5RzbtjcwKw.5faUUrxRcujzwH0O9g/ofogG2wxHk1vdtzVF6HIiy', NULL, '2025-09-25 11:27:02', '2025-09-25 11:27:02'),
(75, 'Rosario Lemus', 'wtijerina@example.com', NULL, '$2y$12$3Zo4UMQx1YpHOjzhDX0wueE/iLvHfvHWmPsKT2Kw7elsmab9yGrt6', NULL, '2025-09-25 11:27:02', '2025-09-25 11:27:02'),
(76, 'Alberto Collado', 'marcos23@example.org', NULL, '$2y$12$jnSGiZ9D84uzk2.Qe3QmaupFXkCwYRTSR0p.bmbrRT0sfq//c0Oz.', NULL, '2025-09-25 11:27:03', '2025-09-25 11:27:03'),
(77, 'Óscar Anaya', 'gvicente@example.net', NULL, '$2y$12$P4RSGOgI5g7PE3YcVUNl6.dZK4K/ejVMUxK4115hLCa4DICBwtdma', NULL, '2025-09-25 11:27:03', '2025-09-25 11:27:03'),
(78, 'Blanca Caballero', 'ydejesus@example.org', NULL, '$2y$12$WhMEpAmmBwwy4sugebfR2O7kH1hJqWUIR0Fl9bqRxqDOUj2fwqQ7S', NULL, '2025-09-25 11:27:03', '2025-09-25 11:27:03'),
(79, 'Gerard Alba', 'plozada@example.org', NULL, '$2y$12$3LeIYsXumKmlnGX99ncFzOBqDsIrxnlvQNKfA6wvL0TEPG3jBwsQS', NULL, '2025-09-25 11:27:03', '2025-09-25 11:27:03'),
(80, 'Alejandro Rodarte', 'jaime.ponce@example.net', NULL, '$2y$12$89Tjx3Xw6xchGAfOj6FQPOI/87TkoFbL4JBxJ7sbulT6wbbZpnftS', NULL, '2025-09-25 11:27:03', '2025-09-25 11:27:03'),
(81, 'Verónica Soliz', 'mpozo@example.net', NULL, '$2y$12$niMx4t3eHzuQtR5sVVjA8OJXX8aBSr8hvJbWkugcRsvDXrcoqlDDO', NULL, '2025-09-25 11:27:04', '2025-09-25 11:27:04'),
(82, 'Jan Madera', 'fierro.ines@example.com', NULL, '$2y$12$WjMnC/IKoeIZfrB.62Y/Lu97TZCRNEgri.IALLd46ZbTG0/3rTQ8W', NULL, '2025-09-25 11:27:04', '2025-09-25 11:27:04'),
(83, 'Ángeles Carrera', 'lsantos@example.org', NULL, '$2y$12$mFeYhw//XMdp1aJDirg3BefWUOaTS37vBGx88x21GCsHedQBj0B/.', NULL, '2025-09-25 11:27:04', '2025-09-25 11:27:04'),
(84, 'Ian Gálvez', 'fatima30@example.com', NULL, '$2y$12$SXTVQhavemkB7Lk12BPlbOqkIDKPFJgqpUS9sZuSltOGBgFXnB9FW', NULL, '2025-09-25 11:27:04', '2025-09-25 11:27:04'),
(85, 'Carolina Adorno', 'candela.pastor@example.org', NULL, '$2y$12$x86JBYkyFdC755gC/OvdXuxF/lUXaOc7LaW36Tl8zemWCxPRm/jFq', NULL, '2025-09-25 11:27:04', '2025-09-25 11:27:04'),
(86, 'Rafael Mora', 'aleix37@example.com', NULL, '$2y$12$LfaqdKnVZ6JSsNvNEkbXkeDe7pDqGcmCIdwNZZ4FOhglZ4B4BghFS', NULL, '2025-09-25 11:27:05', '2025-09-25 11:27:05'),
(87, 'Saúl Sáenz', 'meraz.silvia@example.com', NULL, '$2y$12$ipZA9YXP8o1k0QuFwPeTmerDtPlD9e7lITgw8O5MLcqT8bz8XaTWC', NULL, '2025-09-25 11:27:05', '2025-09-25 11:27:05'),
(88, 'Silvia Mata', 'mara66@example.org', NULL, '$2y$12$IRz0p37KAMUTjWLP0GA/Ru7SG7lHYKQRF7hfF/twogprZhsQlSbRy', NULL, '2025-09-25 11:27:05', '2025-09-25 11:27:05'),
(89, 'Paola Carretero', 'klucas@example.com', NULL, '$2y$12$ztMusgaKV1uRpjv./2BUdOUNTXAS0VxzilUvEbVSFpE0FeyngYE6O', NULL, '2025-09-25 11:27:05', '2025-09-25 11:27:05'),
(90, 'Noa Flores', 'yago.sosa@example.net', NULL, '$2y$12$fO3q6SBcXHjVAYRG4K4a4umaukXvR0Wn.4uwWV3.D8kyncffku.lO', NULL, '2025-09-25 11:27:06', '2025-09-25 11:27:06'),
(91, 'Lara Zambrano', 'qybarra@example.com', NULL, '$2y$12$Zs131/oOKaYXM4TJnZXDOuz6/C5rmZNHo51IY1eC9pItUqHSMxXmm', NULL, '2025-09-25 11:27:06', '2025-09-25 11:27:06'),
(92, 'Francisco Montes', 'marta86@example.org', NULL, '$2y$12$Ki2AUJ9PtWUoZSvoyuYf4OZJbIull.k7JcSrmcyaDgi9DENZwh/Ri', NULL, '2025-09-25 11:27:06', '2025-09-25 11:27:06'),
(93, 'Silvia Melgar', 'ssaucedo@example.net', NULL, '$2y$12$rbM8rwxfaUTyNHLTNb7TOe/k2s5emJLoptZBGd6qF37UEccB.CFY.', NULL, '2025-09-25 11:27:06', '2025-09-25 11:27:06'),
(94, 'Victoria Ulibarri', 'nerea.palacios@example.com', NULL, '$2y$12$Oalx1QQbxYsGOojwy4fRfe0xpoTws1UstupMmzo7tVRC9gVMTl12C', NULL, '2025-09-25 11:27:06', '2025-09-25 11:27:06'),
(95, 'Lucía Ruiz', 'nahia62@example.com', NULL, '$2y$12$vjuSC4WyRa9InCuo5NWzEu.tCrRQG62Sqqk5ojp6rMR.U9aiKGO1W', NULL, '2025-09-25 11:27:07', '2025-09-25 11:27:07'),
(96, 'Alberto Valadez', 'lalcala@example.com', NULL, '$2y$12$hTdIneItHNjSeDpKUH4IaOQkt3eBn2MVtQmrXImGcb0CdHwDQ0boO', NULL, '2025-09-25 11:27:07', '2025-09-25 11:27:07'),
(97, 'Victoria Baca', 'eva.murillo@example.net', NULL, '$2y$12$sWzLLqLYMrrjveXWq3mF.e8On8VjDetCQpFgyPQtyoJqkSQM52amq', NULL, '2025-09-25 11:27:07', '2025-09-25 11:27:07'),
(98, 'Nadia Treviño', 'olga.ornelas@example.com', NULL, '$2y$12$WHFq59rN5Sr9AFtp6ahVZ.PWEsRYYTrqFoguIa/pD8dqkUvDX21Se', NULL, '2025-09-25 11:27:07', '2025-09-25 11:27:07'),
(100, 'Lidia Bermúdez', 'andrea.vera@example.org', NULL, '$2y$12$q5ioPsdwJKfHdmVOfCwFyeLAxVE7GAcw7hYlTUNhp/w/KQZaKt.L.', NULL, '2025-09-25 11:27:08', '2025-09-25 11:27:08'),
(101, 'Mateo Romo', 'marcos12@example.net', NULL, '$2y$12$/hcX/xb/8ZD6BHD.MaIDkOyX9RjrJFZxTPC3qMv/X/ifB0TOgzt5.', NULL, '2025-09-25 11:27:08', '2025-09-25 11:27:08'),
(102, 'Clara Colunga', 'joseantonio14@example.org', NULL, '$2y$12$z3M.PWI/jmopqN.emcoW2.A2PE07ujuxtD6ydqvwuqvqqavgIQFeK', NULL, '2025-09-25 11:27:08', '2025-09-25 11:27:08'),
(103, 'Bruno Gurule', 'santiago.celia@example.org', NULL, '$2y$12$My.Dy/nO7/.7fxh8dyEhU.u.8S.E1Dnndpcg88fjQZfsRWBRlUM7u', NULL, '2025-09-25 11:27:08', '2025-09-25 11:27:08');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `suscribers`
--
ALTER TABLE `suscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suscribers_email_unique` (`email`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `suscribers`
--
ALTER TABLE `suscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
