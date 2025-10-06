-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-10-2025 a las 20:38:57
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
-- Estructura de tabla para la tabla `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('activo','suspendido','inactivo','pendiente') NOT NULL DEFAULT 'activo' COMMENT 'Estado de la company: activo, suspendido, inactivo, pendiente',
  `capacidad` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `companies`
--

INSERT INTO `companies` (`id`, `nombre`, `ubicacion`, `logo`, `descripcion`, `estado`, `capacidad`, `created_at`, `updated_at`) VALUES
(4, 'chicho', 'los claveles', 'companies/QYn2JJiuKPHWavQWL1HZTHuPPknGSY7IQYlHId7I.png', 'ninguno', 'activo', 95, '2025-10-02 00:00:00', '2025-10-03 05:59:09'),
(5, 'chicho3', 'ninguno', NULL, 'niniguno', 'activo', 30, '2025-10-02 00:00:23', '2025-10-02 00:00:23'),
(9, 'DOMINIO', 'NINGUNO', NULL, 'NINGUNO', 'activo', 34, '2025-10-02 16:21:19', '2025-10-02 16:21:19'),
(10, 'pablo', 'ninguno', NULL, 'ningubno', 'activo', 100, '2025-10-02 16:33:23', '2025-10-02 16:33:23'),
(11, '432ew', '32432', NULL, '432', 'activo', 23, '2025-10-02 23:02:26', '2025-10-02 23:02:26'),
(12, 'Cochera Central', 'Av. Principal 123', 'companies/logo123.png', 'Cochera techada y segura', 'activo', 50, '2025-10-03 04:51:03', '2025-10-03 04:51:03'),
(13, 'oilo', '213', 'companies/lLPXx2itiTBOBdZLPiCK10WWakaGxfyDEjZIMBHz.png', '321', 'suspendido', 23, '2025-10-03 05:03:43', '2025-10-03 06:03:48');

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
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `hora_ingreso` time NOT NULL,
  `id_user` bigint(20) DEFAULT NULL,
  `id_empresa` bigint(20) DEFAULT NULL,
  `id_vehiculo` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id`, `fecha_ingreso`, `hora_ingreso`, `id_user`, `id_empresa`, `id_vehiculo`, `created_at`, `updated_at`) VALUES
(15, '2025-10-03', '22:37:48', 5, 4, 20, '2025-10-04 03:37:48', '2025-10-04 03:37:48'),
(17, '2025-10-03', '22:37:59', 5, 4, 14, '2025-10-04 03:37:59', '2025-10-04 03:37:59'),
(19, '2025-10-03', '22:38:07', 5, 4, 16, '2025-10-04 03:38:07', '2025-10-04 03:38:07'),
(20, '2025-10-03', '22:38:12', 5, 4, 17, '2025-10-04 03:38:12', '2025-10-04 03:38:12'),
(21, '2025-10-03', '22:38:18', 5, 4, 21, '2025-10-04 03:38:18', '2025-10-04 03:38:18'),
(22, '2025-10-03', '22:38:22', 5, 4, 22, '2025-10-04 03:38:22', '2025-10-04 03:38:22'),
(23, '2025-10-03', '22:38:26', 5, 4, 23, '2025-10-04 03:38:26', '2025-10-04 03:38:26'),
(24, '2025-10-03', '22:38:30', 5, 4, 24, '2025-10-04 03:38:30', '2025-10-04 03:38:30'),
(25, '2025-10-03', '22:38:35', 5, 4, 25, '2025-10-04 03:38:35', '2025-10-04 03:38:35'),
(27, '2025-10-03', '22:41:30', 5, 4, 27, '2025-10-04 03:41:30', '2025-10-04 03:41:30'),
(28, '2025-10-03', '22:41:56', 5, 4, 18, '2025-10-04 03:41:56', '2025-10-04 03:41:56'),
(29, '2025-10-04', '11:56:08', 11, 10, 28, '2025-10-04 16:56:08', '2025-10-04 16:56:08'),
(30, '2025-10-04', '12:13:56', 5, 4, 29, '2025-10-04 17:13:56', '2025-10-04 17:13:56'),
(31, '2025-10-04', '12:14:06', 11, 10, 30, '2025-10-04 17:14:06', '2025-10-04 17:14:06'),
(32, '2025-10-04', '12:24:41', 1, 4, 31, '2025-10-04 17:24:41', '2025-10-04 17:24:41'),
(33, '2025-10-04', '12:39:59', 1, 4, 32, '2025-10-04 17:39:59', '2025-10-04 17:39:59'),
(34, '2025-10-04', '12:42:03', 1, 4, 33, '2025-10-04 17:42:03', '2025-10-04 17:42:03'),
(35, '2025-10-04', '12:42:22', 5, 4, 34, '2025-10-04 17:42:22', '2025-10-04 17:42:22'),
(36, '2025-10-04', '12:43:36', 11, 10, 35, '2025-10-04 17:43:36', '2025-10-04 17:43:36'),
(37, '2025-10-04', '12:47:09', 5, 4, 36, '2025-10-04 17:47:09', '2025-10-04 17:47:09'),
(38, '2025-10-05', '12:21:06', 11, 10, 37, '2025-10-05 17:21:06', '2025-10-05 17:21:06');

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
(4, '2025_09_24_154249_create_suscribers_table', 1),
(5, '2025_09_24_155230_create_personal_access_tokens_table', 1),
(6, '2025_09_25_160721_create_empresas_table', 1),
(7, '2025_09_25_162144_add_columns_to_empresas_table', 1),
(8, '2025_09_25_162350_rename_empresas_table_to_companies', 1),
(9, '2025_09_25_164918_create_roles_table', 1),
(10, '2025_09_25_170325_add_missing_columns_to_users_table', 1),
(11, '2025_09_25_175206_add_estado_column_to_companies_table', 1),
(12, '2025_09_25_175210_add_estado_column_to_companies_table', 1),
(13, '2025_09_25_183354_add_estado_column_to_roles_table', 1),
(14, '2025_09_25_211717_create_tipo_vehiculos_table', 1),
(15, '2025_09_25_212450_add_nombre_valor_columns_to_tipo_vehiculos_table', 1),
(16, '2025_09_25_212519_add_unique_index_to_nombre_in_tipo_vehiculos', 1),
(17, '2025_09_25_213239_create_propietarios_table', 1),
(18, '2025_09_25_213258_create_vehiculos_table', 1),
(19, '2025_09_25_214101_add_columns_to_propietarios_table', 1),
(20, '2025_09_25_214105_fix_vehiculos_table', 1),
(21, '2025_09_25_214651_create_vehiculo_propietario_table', 1),
(22, '2025_09_25_214655_create_tolerancia_table', 1),
(23, '2025_09_26_002043_remove_tipo_vehiculo_relationship_from_tolerancia_table', 1),
(24, '2025_09_27_190000_create_registros_table', 1),
(25, '2025_09_27_200000_create_ingresos_table', 1),
(26, '2025_10_01_000000_create_observaciones_table', 1),
(27, '2025_10_01_000000_create_salidas_table', 1),
(28, '2025_10_04_uniq_tolerancia_descripcion_empresa', 2),
(29, '2025_10_04_make_tolerancia_notnull', 3),
(30, '2025_10_04_force_uniq_tolerancia_descripcion_empresa', 4),
(31, '2025_10_04_uniq_tipo_vehiculos_nombre_empresa', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `observaciones`
--

CREATE TABLE `observaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `id_vehiculo` bigint(20) DEFAULT NULL,
  `id_usuario` bigint(20) DEFAULT NULL,
  `id_empresa` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `observaciones`
--

INSERT INTO `observaciones` (`id`, `tipo`, `descripcion`, `id_vehiculo`, `id_usuario`, `id_empresa`, `created_at`, `updated_at`) VALUES
(1, 'Advertencia', 'Ninguno', 7, 5, 8, NULL, NULL),
(2, 'Advertencia', 'El vehículo estaba mal estacionado.', 7, 5, 4, '2025-10-02 01:45:54', '2025-10-02 01:45:54'),
(3, 'Advertencia', 'ctm no me guarda xD', 7, 5, 4, '2025-10-02 01:51:23', '2025-10-02 01:51:23'),
(4, 'Peligro', 'El vehículo estaba mal estacionado.', 7, 5, 4, '2025-10-02 01:53:22', '2025-10-02 01:53:22'),
(5, 'GRAVE', 'Choco a otro carro', 7, 1, 4, '2025-10-02 02:56:01', '2025-10-02 02:56:01'),
(6, 'Grave', 'NO PAGA COMPLETO', 8, 1, 10, '2025-10-02 03:04:53', '2025-10-02 03:04:53'),
(8, 'Ninguno', 'HA CHOCADO ESTE PENDEJO Y NO QUIERE PAGAR NADA SE QUEJO CON EL YOTI PERO EL YOTI LE LLEGO AL CHOMPIRAS ASIESQUE NO SE ARREGLO, CUANDO VENGA NUEVAMENTE LE MANDARE A LA MIERCOLES JAJAJ', 8, 1, 10, '2025-10-02 03:11:28', '2025-10-02 03:11:28'),
(9, 'Ninguno', 'DDD', 8, 1, 10, '2025-10-02 03:14:54', '2025-10-02 03:14:54');

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
(1, 'App\\Models\\User', 1, 'auth_token', '0cf83478d6a53379d4952ab6db1bac1b2fe0f2676f2f5611a55acf01b043cf04', '[\"*\"]', NULL, NULL, '2025-10-01 23:46:24', '2025-10-01 23:46:24'),
(2, 'App\\Models\\User', 1, 'auth_token', '05596d40ad55d8066b5f165d990003306b14f684fafee93c59700e9f551e2871', '[\"*\"]', '2025-10-01 23:47:32', NULL, '2025-10-01 23:46:51', '2025-10-01 23:47:32'),
(3, 'App\\Models\\User', 1, 'auth_token', 'cbbc8c45ef3619fdf40848b8834f5a351993061a117c0eb69cba77a553d990ec', '[\"*\"]', '2025-10-01 23:51:23', NULL, '2025-10-01 23:50:18', '2025-10-01 23:51:23'),
(4, 'App\\Models\\User', 1, 'auth_token', 'bccef617887e1752cf0d24ab5c5e0c7936b00b7da75f065d304e2e5e0f8bee18', '[\"*\"]', '2025-10-02 00:10:14', NULL, '2025-10-01 23:52:46', '2025-10-02 00:10:14'),
(5, 'App\\Models\\User', 1, 'auth_token', 'b0a2a196e302c653c9194a502823e7f28468ca55e7974341004db87c8d9d9966', '[\"*\"]', '2025-10-03 05:48:05', NULL, '2025-10-01 23:53:00', '2025-10-03 05:48:05'),
(6, 'App\\Models\\User', 1, 'auth_token', '90857ffb3dd8a3ab4b212aa659bbdca2b3e83e840630062f1b3a16a7d7938706', '[\"*\"]', '2025-10-02 00:14:58', NULL, '2025-10-02 00:12:09', '2025-10-02 00:14:58'),
(7, 'App\\Models\\User', 1, 'auth_token', '1f7dcf730f883b351ee65f7c38f9a59c1bfd9ea55c58a1edd7c6e284b1eef121', '[\"*\"]', '2025-10-02 05:26:51', NULL, '2025-10-02 00:16:41', '2025-10-02 05:26:51'),
(8, 'App\\Models\\User', 1, 'auth_token', '12ba724c52126ff41e5ee9c05dee767129b33ac28d15410d00b2ae684e680253', '[\"*\"]', NULL, NULL, '2025-10-02 00:42:35', '2025-10-02 00:42:35'),
(9, 'App\\Models\\User', 1, 'auth_token', '425292422c3d22b7d4855d9e0e4a47596d24677a844eddb6f568ccd99b507d06', '[\"*\"]', '2025-10-02 02:55:31', NULL, '2025-10-02 02:03:49', '2025-10-02 02:55:31'),
(10, 'App\\Models\\User', 1, 'auth_token', '67dc6bb337b1a28e577742eb23398a70b8f179b0650b8f455316b3c333a6b09f', '[\"*\"]', '2025-10-02 05:54:23', NULL, '2025-10-02 05:34:08', '2025-10-02 05:54:23'),
(11, 'App\\Models\\User', 1, 'auth_token', 'f87520ff7b9e3f148dd46e32f13bc9c682c3c6d94ab31db5564609227c90b57c', '[\"*\"]', '2025-10-02 15:56:20', NULL, '2025-10-02 15:56:04', '2025-10-02 15:56:20'),
(12, 'App\\Models\\User', 1, 'auth_token', 'c7dad839fdd13437bab5a33ec394edb282ac83ed27132a8477367a6f9d82e81d', '[\"*\"]', '2025-10-02 16:21:34', NULL, '2025-10-02 15:56:38', '2025-10-02 16:21:34'),
(13, 'App\\Models\\User', 1, 'auth_token', '324897e03939de524b4314119bbef77c7e839e1fe8a4abce6e5be48ef86cfa36', '[\"*\"]', '2025-10-03 06:17:34', NULL, '2025-10-02 16:02:23', '2025-10-03 06:17:34'),
(14, 'App\\Models\\User', 1, 'auth_token', 'd8930114be7d650906ffa95dc381188df19807133bbec7a379a5b0bfde66d9ee', '[\"*\"]', '2025-10-02 16:21:50', NULL, '2025-10-02 16:21:48', '2025-10-02 16:21:50'),
(15, 'App\\Models\\User', 10, 'auth_token', '75fb62b77de053c98adf3be0e6675b2164d08c014c7554b66d2367a93ebb0b17', '[\"*\"]', '2025-10-02 16:26:39', NULL, '2025-10-02 16:26:37', '2025-10-02 16:26:39'),
(16, 'App\\Models\\User', 10, 'auth_token', 'd4eeb688e7b1df1135f1d0beac9f1abbfb0508927a2dc10e08ac26c8f8a24962', '[\"*\"]', '2025-10-02 16:27:25', NULL, '2025-10-02 16:26:58', '2025-10-02 16:27:25'),
(17, 'App\\Models\\User', 5, 'auth_token', '45087a6c684fd069c55b21875d36beba50ff3bb563c2397d6d60dac8a5a407bc', '[\"*\"]', '2025-10-02 16:33:24', NULL, '2025-10-02 16:27:34', '2025-10-02 16:33:24'),
(18, 'App\\Models\\User', 11, 'auth_token', '0edc482cc6ab6f3501dbcaec8c68d425961a5189fec23f98c20b286a0cb0201c', '[\"*\"]', '2025-10-02 16:33:35', NULL, '2025-10-02 16:33:34', '2025-10-02 16:33:35'),
(19, 'App\\Models\\User', 1, 'auth_token', '530d1247dc62b9ba1fa14d07358f5b92c46f2601568e02d8cae38f5aa53f014b', '[\"*\"]', '2025-10-02 16:38:19', NULL, '2025-10-02 16:38:17', '2025-10-02 16:38:19'),
(20, 'App\\Models\\User', 1, 'auth_token', '663be9b2aeb50bc2bc9b8179c0e0861ac66fd9ffc8d9f9253de067cb720cef8c', '[\"*\"]', '2025-10-02 16:40:26', NULL, '2025-10-02 16:40:24', '2025-10-02 16:40:26'),
(21, 'App\\Models\\User', 1, 'auth_token', '71b0700f46d764fac5c45045cebda36c2f3f052d2db0e40ba712a0fa5e005cce', '[\"*\"]', '2025-10-02 21:41:21', NULL, '2025-10-02 16:41:29', '2025-10-02 21:41:21'),
(22, 'App\\Models\\User', 1, 'auth_token', 'bfcd77e7c232d0e04da57093c3c6289f8fb989388b0106252f051ead75b4616b', '[\"*\"]', '2025-10-02 21:42:16', NULL, '2025-10-02 21:42:14', '2025-10-02 21:42:16'),
(23, 'App\\Models\\User', 5, 'auth_token', '8b7ee1c8bc65df70a6d7246523f6aa97f9be74c5bd6eb71963c425f9da588742', '[\"*\"]', '2025-10-02 21:50:12', NULL, '2025-10-02 21:42:31', '2025-10-02 21:50:12'),
(24, 'App\\Models\\User', 1, 'auth_token', '31649bead3a4a98ce4bc2cc763b2f6835e175a3799f9913edcc7dacd3b2f1d83', '[\"*\"]', '2025-10-02 21:51:25', NULL, '2025-10-02 21:50:59', '2025-10-02 21:51:25'),
(25, 'App\\Models\\User', 5, 'auth_token', '67442a8e5039d7f432f009c80588b92abc2a47c6fec89ea35a540335b0010dc5', '[\"*\"]', '2025-10-02 21:51:44', NULL, '2025-10-02 21:51:32', '2025-10-02 21:51:44'),
(26, 'App\\Models\\User', 1, 'auth_token', 'fad97fd810de97c9f25ff178af6261d9d6cd5d673cbd5ca27c892b19a051c496', '[\"*\"]', '2025-10-02 21:54:36', NULL, '2025-10-02 21:51:49', '2025-10-02 21:54:36'),
(27, 'App\\Models\\User', 1, 'auth_token', '22fbc745941c913ad61184b7f2eeb805dd28e619a48400092df659289e092279', '[\"*\"]', '2025-10-03 02:39:45', NULL, '2025-10-02 21:57:06', '2025-10-03 02:39:45'),
(28, 'App\\Models\\User', 5, 'auth_token', '0706aaa3ac97e3826e44921414bb0a7568a5543f99b80449b59de963a1541068', '[\"*\"]', '2025-10-03 04:47:13', NULL, '2025-10-03 02:39:54', '2025-10-03 04:47:13'),
(29, 'App\\Models\\User', 5, 'auth_token', '7495483687290c2871f31ad5d3b80b1703e838879a9e2fd1c1d343bc37e73c62', '[\"*\"]', '2025-10-03 05:15:55', NULL, '2025-10-03 04:47:20', '2025-10-03 05:15:55'),
(30, 'App\\Models\\User', 14, 'auth_token', '0867e595e3cd0d51beb6b2d80d61e32065c3ea4106a6526f3b1c943f0337004a', '[\"*\"]', '2025-10-03 05:59:10', NULL, '2025-10-03 05:16:04', '2025-10-03 05:59:10'),
(31, 'App\\Models\\User', 1, 'auth_token', 'd4a1bfb623bc7c4291a06e0c0f9511a1c59423dcdff0e898fd4d45970397c524', '[\"*\"]', '2025-10-03 06:07:35', NULL, '2025-10-03 05:59:16', '2025-10-03 06:07:35'),
(32, 'App\\Models\\User', 14, 'auth_token', '9cdd9079b8c0705247ee14c525c9959942fcfd75daab7c5958b9bf95c574e251', '[\"*\"]', '2025-10-03 06:08:48', NULL, '2025-10-03 06:08:46', '2025-10-03 06:08:48'),
(33, 'App\\Models\\User', 1, 'auth_token', 'bd4b29a246f52ae63fb8eb69541965685fde8750b05f4cf41a1f4a63b924d1be', '[\"*\"]', '2025-10-03 17:00:45', NULL, '2025-10-03 06:08:53', '2025-10-03 17:00:45'),
(34, 'App\\Models\\User', 1, 'auth_token', 'f4fbb93a90a83949fb6d86e51a2d3b456706940f353bff818e7cdbb3a2309fb5', '[\"*\"]', '2025-10-04 16:40:13', NULL, '2025-10-03 06:22:31', '2025-10-04 16:40:13'),
(35, 'App\\Models\\User', 5, 'auth_token', 'bde29769a83d36418331e2d4914f630f64556da69e1777539b1cf70807e3cb59', '[\"*\"]', '2025-10-03 17:17:02', NULL, '2025-10-03 17:00:55', '2025-10-03 17:17:02'),
(36, 'App\\Models\\User', 1, 'auth_token', 'bef7459699375fa32dc6f748df2042e80dba80dcc621f71d041d5e472b65a0a6', '[\"*\"]', '2025-10-03 17:17:16', NULL, '2025-10-03 17:17:10', '2025-10-03 17:17:16'),
(37, 'App\\Models\\User', 5, 'auth_token', 'd132bc964bb74a81cd2ef96f54523c3e75d6884a4b73131de7c3fe4d6e24ce01', '[\"*\"]', '2025-10-03 18:53:04', NULL, '2025-10-03 17:17:26', '2025-10-03 18:53:04'),
(38, 'App\\Models\\User', 1, 'auth_token', '634583ce940d380d943eb0572063b22cf222dcb3987ff7090f7388ea018da7b2', '[\"*\"]', NULL, NULL, '2025-10-03 18:01:09', '2025-10-03 18:01:09'),
(39, 'App\\Models\\User', 5, 'auth_token', '372590a146221f401dfbb0095bb5fcb1e474c73a65b6879b5bc87306764a85ad', '[\"*\"]', '2025-10-04 16:33:36', NULL, '2025-10-04 03:02:18', '2025-10-04 16:33:36'),
(40, 'App\\Models\\User', 1, 'auth_token', '0a0cc26e612e6a9d37b44c9a08838a1abc326f0cb46ca66016e167bc2d0efce7', '[\"*\"]', '2025-10-04 03:59:59', NULL, '2025-10-04 03:02:29', '2025-10-04 03:59:59'),
(41, 'App\\Models\\User', 1, 'auth_token', '3148894ca548a297187ef561b4249fb73395d59752aa5b48c563be236918c054', '[\"*\"]', '2025-10-04 18:20:15', NULL, '2025-10-04 16:26:22', '2025-10-04 18:20:15'),
(42, 'App\\Models\\User', 5, 'auth_token', '0c395f9a31f11d7640c59235a5e05ae25e0cd83c2d3321dcf6a29268d17108ff', '[\"*\"]', '2025-10-04 16:45:18', NULL, '2025-10-04 16:33:42', '2025-10-04 16:45:18'),
(43, 'App\\Models\\User', 11, 'auth_token', '681909a2bf09adc8b5dfe86d31f62847ed3b5fde517250d7670e87b22d52b2e9', '[\"*\"]', '2025-10-04 16:52:52', NULL, '2025-10-04 16:46:21', '2025-10-04 16:52:52'),
(44, 'App\\Models\\User', 1, 'auth_token', 'cf44a26603f9ea996c0f7e854194916b5e261cebfbaa6897c0117c4918ef202c', '[\"*\"]', '2025-10-04 18:00:47', NULL, '2025-10-04 16:47:17', '2025-10-04 18:00:47'),
(45, 'App\\Models\\User', 5, 'auth_token', '634381ba5ae75a09e588b432abab9058477c2d7c0a14b6141f96644603a47cd6', '[\"*\"]', '2025-10-05 17:48:49', NULL, '2025-10-04 16:53:23', '2025-10-05 17:48:49'),
(46, 'App\\Models\\User', 11, 'auth_token', '3a9077121c69f37b603acd1c12d900e7901bc54beaa5f54e194bf0335788210a', '[\"*\"]', '2025-10-05 17:48:48', NULL, '2025-10-04 16:55:50', '2025-10-05 17:48:48'),
(47, 'App\\Models\\User', 1, 'auth_token', 'ad6310cf338ec03dc25a66a57d52822d32a6d277a5e5f230e2f7d13cb8f77010', '[\"*\"]', NULL, NULL, '2025-10-04 17:03:59', '2025-10-04 17:03:59'),
(48, 'App\\Models\\User', 1, 'auth_token', '94e59808e5a12130dcf566023e3038272c4c05deff555e81629eafdf901749f7', '[\"*\"]', '2025-10-04 17:44:06', NULL, '2025-10-04 17:23:55', '2025-10-04 17:44:06'),
(49, 'App\\Models\\User', 1, 'auth_token', '9ccb4bd30eea3932c6f1808276a12207c685e1363f497bb5d944589f16fe3e52', '[\"*\"]', '2025-10-05 17:48:48', NULL, '2025-10-05 16:53:17', '2025-10-05 17:48:48'),
(50, 'App\\Models\\User', 1, 'auth_token', '6130b1480788129ec25222f89a74acc212cd02d71ffca7a2395d6fb534a7eba5', '[\"*\"]', '2025-10-05 17:35:25', NULL, '2025-10-05 17:35:08', '2025-10-05 17:35:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propietarios`
--

CREATE TABLE `propietarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombres` varchar(100) NOT NULL COMMENT 'Nombres del propietario',
  `apellidos` varchar(100) DEFAULT NULL COMMENT 'Apellidos del propietario',
  `documento` varchar(20) DEFAULT NULL COMMENT 'Documento de identidad único',
  `telefono` varchar(15) DEFAULT NULL COMMENT 'Número de teléfono',
  `email` varchar(100) DEFAULT NULL COMMENT 'Correo electrónico único',
  `direccion` text DEFAULT NULL COMMENT 'Dirección completa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `hora_ingreso` time DEFAULT NULL,
  `id_vehiculo` bigint(20) DEFAULT NULL,
  `id_user` bigint(20) DEFAULT NULL,
  `id_empresa` bigint(20) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `registros`
--

INSERT INTO `registros` (`id`, `fecha_ingreso`, `hora_ingreso`, `id_vehiculo`, `id_user`, `id_empresa`, `fecha`, `created_at`, `updated_at`) VALUES
(10, '2025-10-01', '23:01:35', 9, 1, 4, '2025-10-02 00:04:31', '2025-10-02 05:04:31', '2025-10-02 05:04:31'),
(11, '2025-10-01', '19:25:00', 8, 1, 4, '2025-10-02 00:19:29', '2025-10-02 05:19:29', '2025-10-02 05:19:29'),
(12, '2025-10-01', '12:00:00', 7, 1, 4, '2025-10-02 00:20:06', '2025-10-02 05:20:06', '2025-10-02 05:20:06'),
(13, '2025-10-02', '00:21:35', 17, 1, 4, '2025-10-02 10:56:59', '2025-10-02 15:56:59', '2025-10-02 15:56:59'),
(14, '2025-10-02', '00:21:30', 16, 1, 4, '2025-10-02 10:59:04', '2025-10-02 15:59:04', '2025-10-02 15:59:04'),
(15, '2025-10-02', '00:21:26', 15, 1, 4, '2025-10-02 10:59:08', '2025-10-02 15:59:08', '2025-10-02 15:59:08'),
(16, '2025-10-02', '00:21:21', 14, 1, 4, '2025-10-02 10:59:12', '2025-10-02 15:59:12', '2025-10-02 15:59:12'),
(17, '2025-10-02', '00:20:54', 7, 1, 4, '2025-10-02 10:59:15', '2025-10-02 15:59:15', '2025-10-02 15:59:15'),
(18, '2025-10-02', '23:48:39', 18, 5, 4, '2025-10-03 02:14:05', '2025-10-03 07:14:05', '2025-10-03 07:14:05'),
(19, '2025-10-03', '13:41:23', 19, 5, 4, '2025-10-03 22:37:09', '2025-10-04 03:37:09', '2025-10-04 03:37:09'),
(20, '2025-10-03', '22:38:42', 26, 5, 4, '2025-10-03 23:20:03', '2025-10-04 04:20:03', '2025-10-04 04:20:03'),
(21, '2025-10-03', '22:37:52', 7, 5, 4, '2025-10-03 23:20:10', '2025-10-04 04:20:10', '2025-10-04 04:20:10'),
(22, '2025-10-03', '22:38:03', 15, 5, 4, '2025-10-03 23:20:14', '2025-10-04 04:20:14', '2025-10-04 04:20:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `estado` enum('activo','suspendido','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado del rol: activo, suspendido, inactivo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Superusuario', 'activo', '2025-10-01 23:59:33', '2025-10-01 23:59:33'),
(2, 'Administrador General', 'activo', '2025-10-01 23:59:45', '2025-10-01 23:59:45'),
(3, 'Supervisor', 'activo', '2025-10-01 23:59:55', '2025-10-01 23:59:55'),
(4, 'Cajero', 'activo', '2025-10-03 06:13:17', '2025-10-03 06:13:17'),
(5, 'Vigilante', 'activo', '2025-10-03 06:13:25', '2025-10-03 06:13:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas`
--

CREATE TABLE `salidas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha_salida` date NOT NULL,
  `hora_salida` time NOT NULL,
  `tiempo` time DEFAULT NULL,
  `precio` double DEFAULT NULL,
  `tipo_pago` varchar(20) DEFAULT NULL,
  `id_registro` bigint(20) UNSIGNED DEFAULT NULL,
  `id_user` bigint(20) DEFAULT NULL,
  `id_empresa` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `salidas`
--

INSERT INTO `salidas` (`id`, `fecha_salida`, `hora_salida`, `tiempo`, `precio`, `tipo_pago`, `id_registro`, `id_user`, `id_empresa`, `created_at`, `updated_at`) VALUES
(6, '2025-10-02', '00:04:31', '01:30:00', 7.5, 'EFECTIVO', 10, 1, 4, '2025-10-02 05:04:31', '2025-10-02 05:04:31'),
(7, '2025-10-02', '00:19:29', '04:54:28', 25, 'YAPE', 11, 1, 4, '2025-10-02 05:19:29', '2025-10-02 05:19:29'),
(8, '2025-10-02', '00:20:06', '12:20:06', 260, 'YAPE', 12, 1, 4, '2025-10-02 05:20:06', '2025-10-02 05:20:06'),
(9, '2025-10-02', '10:56:59', '10:35:24', 33, 'YAPE', 13, 1, 4, '2025-10-02 15:56:59', '2025-10-02 15:56:59'),
(10, '2025-10-02', '10:59:04', '10:37:33', 33, 'YAPE', 14, 1, 4, '2025-10-02 15:59:04', '2025-10-02 15:59:04'),
(11, '2025-10-02', '10:59:08', '10:37:41', 33, 'EFECTIVO', 15, 1, 4, '2025-10-02 15:59:08', '2025-10-02 15:59:08'),
(12, '2025-10-02', '10:59:12', '10:37:50', 33, 'YAPE', 16, 1, 4, '2025-10-02 15:59:12', '2025-10-02 15:59:12'),
(13, '2025-10-02', '10:59:15', '10:38:21', 220, 'EFECTIVO', 17, 1, 4, '2025-10-02 15:59:15', '2025-10-02 15:59:15'),
(14, '2025-10-03', '02:14:05', '02:25:26', 9, 'YAPE', 18, 5, 10, '2025-10-03 07:14:05', '2025-10-03 07:14:05'),
(15, '2025-10-03', '22:37:09', '08:55:45', 27, 'YAPE', 19, 5, 10, '2025-10-04 03:37:09', '2025-10-04 03:37:09'),
(16, '2025-10-03', '23:20:03', '00:41:20', 3, 'EFECTIVO', 20, 5, 10, '2025-10-04 04:20:03', '2025-10-04 04:20:03'),
(17, '2025-10-03', '23:20:10', '00:42:18', 20, 'EFECTIVO', 21, 5, 10, '2025-10-04 04:20:10', '2025-10-04 04:20:10'),
(18, '2025-10-03', '23:20:14', '00:42:11', 5, 'EFECTIVO', 22, 5, 4, '2025-10-04 04:20:14', '2025-10-04 04:20:14');

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
('mCLBdQe46Fs1nfiliDwYTcbJVFoHGAWskiez0iHm', NULL, '127.0.0.1', 'PostmanRuntime/7.48.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemNtNU5RMXE4dTBETFZEVVlFOEFlQklvanRpNXFaS2dtT3ZLdnI5ZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759366158),
('pl3OMadsOUWnfVetu1rEqtESHMN9LI4lFdlAqiDa', NULL, '127.0.0.1', 'PostmanRuntime/7.48.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFFaYjRzdnh6OHdJbzRlQ3Zxd3E3QUdZUHp4cXN4Y3JBUnZrTGxTdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759473273),
('V9zvoIDoZ1K0kyqNbKHRhiGYUqzqFtL1JHhwImy2', NULL, '127.0.0.1', 'PostmanRuntime/7.48.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQU9KUXhKaEltcFByVzRzNEZ2VWhUZjJ6Y3pQRjZPTERpbmpVUUhCWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759442153),
('Wi3icqB8rjsOaJBEYwAUc03JtYxuHX1EpmkxYPY5', NULL, '127.0.0.1', 'PostmanRuntime/7.48.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZExxOUhxM3psRFpZSGFqZXBxZ01qaklkNzhjek1hbEc3aTdaRmprZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759602015);

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_vehiculos`
--

CREATE TABLE `tipo_vehiculos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo de vehículo',
  `valor` double DEFAULT NULL COMMENT 'Valor asociado al tipo de vehículo',
  `id_empresa` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_vehiculos`
--

INSERT INTO `tipo_vehiculos` (`id`, `nombre`, `valor`, `id_empresa`, `created_at`, `updated_at`) VALUES
(1, 'Nuevo', 3, 4, '2025-10-02 00:09:58', '2025-10-02 00:09:58'),
(2, 'Auto', 3, 4, '2025-10-02 00:25:55', '2025-10-02 00:25:55'),
(3, 'Moto', 2, 4, '2025-10-02 00:26:02', '2025-10-02 00:26:02'),
(4, 'Furgon', 20, 10, '2025-10-02 00:26:12', '2025-10-02 00:26:12'),
(5, 'Camioneta', 5, 10, '2025-10-02 00:26:22', '2025-10-02 00:26:22'),
(6, 'Canoa', 7, 10, '2025-10-02 00:26:31', '2025-10-02 00:26:31'),
(8, 'Canoa', 15, 4, '2025-10-05 17:11:26', '2025-10-05 17:14:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tolerancia`
--

CREATE TABLE `tolerancia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `minutos` int(11) DEFAULT NULL COMMENT 'Minutos de tolerancia',
  `descripcion` varchar(100) NOT NULL,
  `id_empresa` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tolerancia`
--

INSERT INTO `tolerancia` (`id`, `minutos`, `descripcion`, `id_empresa`, `created_at`, `updated_at`) VALUES
(1, 15, 'General', 4, '2025-10-02 00:09:45', '2025-10-02 00:09:45'),
(5, 15, 'jjj', 10, '2025-10-04 18:00:01', '2025-10-04 18:00:01'),
(6, 15, 'fggg', 10, '2025-10-04 18:07:19', '2025-10-04 18:07:19'),
(7, 23, 'Generald', 4, '2025-10-04 18:09:34', '2025-10-04 18:09:34'),
(8, 15, 'jjj', 4, '2025-10-04 18:23:58', '2025-10-04 18:23:58');

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
  `idrol` bigint(20) DEFAULT NULL,
  `id_company` bigint(20) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `idrol`, `id_company`, `estado`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$rV9O8jTRn4iIy/L4TyCxN.tpAtE4CSWoS/EFsW6CgmILdJ5OX3jCS', 1, 4, 'ACTIVO', NULL, '2025-10-01 23:46:24', '2025-10-01 23:46:24'),
(5, 'Admin chicho', 'admin@chicho.com', NULL, '$2y$12$/Co2JmtbUp5S2zJTscJiD.YqZ5od.ad5k3wCLEqmXcrmQYKZ2tv6i', 2, 4, 'SUSPENDIDO', NULL, '2025-10-02 00:00:00', '2025-10-03 07:12:47'),
(6, 'Admin chicho3', 'admin@chicho3.com', NULL, '$2y$12$4OIGmOMoxMILp41pcJ38TeZ.aITWjYQQ.dHxUZYSGRUUzCM2xtENy', 2, 5, 'ACTIVO', NULL, '2025-10-02 00:00:23', '2025-10-02 00:00:23'),
(10, 'Admin DOMINIO', 'admin@dominio.com', NULL, '$2y$12$rV9O8jTRn4iIy/L4TyCxN.tpAtE4CSWoS/EFsW6CgmILdJ5OX3jCS', 2, 9, 'ACTIVO', NULL, '2025-10-02 16:21:19', '2025-10-02 16:21:19'),
(11, 'Admin pablo', 'admin@pablo.com', NULL, '$2y$12$Hl4J/Syoqr7INyxXKmh/oeg2g5N6Xh7R9Y57irhbF6h2PiCVyw432', 2, 10, 'ACTIVO', NULL, '2025-10-02 16:33:23', '2025-10-02 16:33:23'),
(12, 'Admin 432ew', 'admin@432ew.com', NULL, '$2y$12$VO/VM/YAJt9.zCFuPPKaHOETi.C7ucQZgpsSdH4xHjOAV6qxegrQy', 2, 11, 'ACTIVO', NULL, '2025-10-02 23:02:26', '2025-10-02 23:02:26'),
(13, 'Admin Cochera Central', 'admin@cochera-central.com', NULL, '$2y$12$OORnTSHJfDNmBXQS58DRX.RvlVSONUw.04AXAKfL7e/v2RrAi6TbW', 2, 12, 'ACTIVO', NULL, '2025-10-03 04:51:03', '2025-10-03 04:51:03'),
(14, 'Admin oilo', 'admin@oilo.com', NULL, '$2y$12$GnhOViFKO070zOzNzes/Xug9zxEfvbLDblc0DElIJJ8HNQmrWyRjq', 2, 13, 'ACTIVO', NULL, '2025-10-03 05:03:43', '2025-10-03 05:03:43'),
(15, 'secretario', 'admin@gmail.com.com', NULL, '$2y$12$bzJjdsyTnjYOfDln/cLso.A/OcGlimfCW4Y43nuxYw3ghlJrmch5C', 2, 4, 'SUSPENDIDO', NULL, '2025-10-03 06:11:24', '2025-10-03 07:12:38'),
(16, 'Juan Perez', 'juan@correo.com', NULL, '$2y$12$uvx3omR65DEELX5cFqV12uHRQ8hRwmS/b5OQnTTSASDmVT6N/b0FS', 2, 4, 'SUSPENDIDO', NULL, '2025-10-03 06:26:16', '2025-10-03 07:12:32'),
(17, 'Juan Perez', 'juan@corDreo.com', NULL, '$2y$12$M1CNSV9zXjoO7q6CMnWNZOUL5wiqgu39iUmjztJKkcpI/cj21ff/K', 2, 4, 'ACTIVO', NULL, '2025-10-03 06:28:05', '2025-10-03 16:56:36'),
(18, 'Juan Perez', 'juan@555.com', NULL, '$2y$12$Q9b1ssBT0KGdG2b/0YYfeeoQKL/vYYnICeqZ1ytlxtzoYXZY1sNGe', 5, 4, 'ACTIVO', NULL, '2025-10-03 06:30:32', '2025-10-03 07:12:15'),
(19, 'Juan Pereza', 'juuan@coErDreo.com', NULL, '$2y$12$YBmd/fSQC9qnxZhWdSaA3u/Y2pTvJEoBesS4ncPSkw5WNav3MFJNq', 2, 4, 'ACTIVO', NULL, '2025-10-03 06:32:12', '2025-10-03 06:34:38'),
(20, 'ctm', 'ctm@ggg.com', NULL, '$2y$12$aJ1vl9eEuO0fSwH4FrjAx.lyaWPBRymlIF2zrWOFyajbZxtEYRiWy', NULL, NULL, 'ACTIVO', NULL, '2025-10-03 07:01:28', '2025-10-03 07:01:28'),
(21, 'ctm2', 'ctm@ctm33.com', NULL, '$2y$12$4r0uqN2ed5YnZU87kOGZkOauiRAIVZIGabIa6qopMTD4In0dbu6H6', 4, 4, 'ACTIVO', NULL, '2025-10-03 07:05:51', '2025-10-03 07:05:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `placa` varchar(15) DEFAULT NULL COMMENT 'Placa del vehículo (única)',
  `modelo` varchar(50) DEFAULT NULL COMMENT 'Modelo del vehículo',
  `marca` varchar(50) DEFAULT NULL COMMENT 'Marca del vehículo',
  `color` varchar(30) DEFAULT NULL COMMENT 'Color del vehículo',
  `anio` year(4) DEFAULT NULL COMMENT 'Año del vehículo',
  `frecuencia` int(11) DEFAULT NULL,
  `tipo_vehiculo_id` bigint(20) DEFAULT NULL COMMENT 'Relación con tipo de vehículo',
  `id_empresa` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `placa`, `modelo`, `marca`, `color`, `anio`, `frecuencia`, `tipo_vehiculo_id`, `id_empresa`, `created_at`, `updated_at`) VALUES
(7, 'AAA-AAA', NULL, NULL, NULL, NULL, 3, 4, 4, '2025-10-02 00:22:24', '2025-10-04 03:37:52'),
(8, 'WWW-FFF', NULL, NULL, NULL, NULL, 1, 5, 4, '2025-10-02 00:25:00', '2025-10-02 03:30:42'),
(9, 'YYY-UUU', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-02 04:01:35', '2025-10-02 04:01:35'),
(10, 'OOO-OOO', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-02 04:06:26', '2025-10-02 04:06:26'),
(11, '888-999', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-02 04:10:37', '2025-10-02 04:10:37'),
(12, '777-888', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-02 04:15:09', '2025-10-02 04:15:09'),
(13, 'RRR-OOO', NULL, NULL, NULL, NULL, 1, 6, 4, '2025-10-02 04:17:44', '2025-10-02 04:19:34'),
(14, 'BBB-BBB', NULL, NULL, NULL, NULL, 2, 6, 4, '2025-10-02 05:21:21', '2025-10-04 03:39:20'),
(15, 'CCC-CCC', NULL, NULL, NULL, NULL, 2, 5, 4, '2025-10-02 05:21:26', '2025-10-04 03:39:27'),
(16, 'DDD-DDD', NULL, NULL, NULL, NULL, 2, 3, 4, '2025-10-02 05:21:30', '2025-10-04 03:39:35'),
(17, 'EEE-EEE', NULL, NULL, NULL, NULL, 2, 1, 4, '2025-10-02 05:21:35', '2025-10-04 03:38:12'),
(18, 'TTT-YYY', NULL, NULL, NULL, NULL, 2, 4, 4, '2025-10-03 04:48:39', '2025-10-04 03:42:34'),
(19, '333-TTT', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-03 18:41:23', '2025-10-03 18:41:23'),
(20, 'JJJ-LLL', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-04 03:37:48', '2025-10-04 03:37:48'),
(22, 'GGG-GGG', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-04 03:38:22', '2025-10-04 03:38:22'),
(23, 'HHH-HHH', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-04 03:38:26', '2025-10-04 03:38:26'),
(24, 'III-III', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-04 03:38:30', '2025-10-04 03:38:30'),
(25, 'JJJ-JJJ', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-04 03:38:35', '2025-10-04 03:38:35'),
(26, 'KKK-KKK', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-04 03:38:42', '2025-10-04 03:38:42'),
(27, 'TTT-III', NULL, NULL, NULL, NULL, 1, 1, 4, '2025-10-04 03:41:30', '2025-10-04 03:41:30'),
(28, 'TTT-AAA', NULL, NULL, NULL, NULL, 2, 1, 4, '2025-10-04 16:56:08', '2025-10-04 16:56:30'),
(29, 'FFF-MMM', NULL, NULL, NULL, NULL, 1, 1, NULL, '2025-10-04 17:13:56', '2025-10-04 17:13:56'),
(30, 'GGG-RRR', NULL, NULL, NULL, NULL, 1, 1, NULL, '2025-10-04 17:14:06', '2025-10-04 17:14:06'),
(31, 'TTT-AAB', '2023', 'Honda', 'Negro', '2023', 2, 1, NULL, '2025-10-04 17:24:41', '2025-10-04 17:38:16'),
(32, 'TTT-CCC', '2023', 'Honda', 'Negro', '2023', 2, 1, NULL, '2025-10-04 17:39:59', '2025-10-04 17:41:56'),
(33, 'TTT-SSS', '2023', 'Honda', 'Negro', '2023', 1, 1, 4, '2025-10-04 17:42:03', '2025-10-04 17:42:03'),
(34, 'YYY-FFF', NULL, NULL, NULL, NULL, 2, 1, 4, '2025-10-04 17:42:22', '2025-10-04 17:42:43'),
(35, 'UUU-BBT', NULL, NULL, NULL, NULL, 3, 4, 10, '2025-10-04 17:43:36', '2025-10-04 17:49:47'),
(36, 'UUU-BBB', NULL, NULL, NULL, NULL, 1, 5, 4, '2025-10-04 17:47:09', '2025-10-04 17:48:36'),
(37, 'AAA-AAA', NULL, NULL, NULL, NULL, 1, 1, 10, '2025-10-05 17:21:06', '2025-10-05 17:21:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo_propietario`
--

CREATE TABLE `vehiculo_propietario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehiculo_id` bigint(20) UNSIGNED NOT NULL,
  `propietario_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_inicio` date NOT NULL COMMENT 'Fecha de inicio de la relación vehículo-propietario',
  `fecha_fin` date DEFAULT NULL COMMENT 'Fecha de fin de la relación (null = vigente)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indices de la tabla `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingresos_id_user_index` (`id_user`),
  ADD KEY `ingresos_id_empresa_index` (`id_empresa`);

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
-- Indices de la tabla `observaciones`
--
ALTER TABLE `observaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `observaciones_id_vehiculo_index` (`id_vehiculo`),
  ADD KEY `observaciones_id_empresa_index` (`id_empresa`);

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
-- Indices de la tabla `propietarios`
--
ALTER TABLE `propietarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `propietarios_documento_index` (`documento`),
  ADD KEY `propietarios_email_index` (`email`),
  ADD KEY `propietarios_apellidos_nombres_index` (`apellidos`,`nombres`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registros_id_vehiculo_index` (`id_vehiculo`),
  ADD KEY `registros_id_user_index` (`id_user`),
  ADD KEY `registros_id_empresa_index` (`id_empresa`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salidas_id_ingreso_index` (`id_registro`),
  ADD KEY `salidas_id_user_index` (`id_user`),
  ADD KEY `salidas_id_empresa_index` (`id_empresa`);

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
-- Indices de la tabla `tipo_vehiculos`
--
ALTER TABLE `tipo_vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo_vehiculos_nombre_id_empresa_unique` (`nombre`,`id_empresa`);

--
-- Indices de la tabla `tolerancia`
--
ALTER TABLE `tolerancia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tolerancia_descripcion_id_empresa_unique` (`descripcion`,`id_empresa`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_idrol_index` (`idrol`),
  ADD KEY `users_id_company_index` (`id_company`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehiculos_tipo_vehiculo_id_index` (`tipo_vehiculo_id`);

--
-- Indices de la tabla `vehiculo_propietario`
--
ALTER TABLE `vehiculo_propietario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_vehiculo_propietario_fecha` (`vehiculo_id`,`propietario_id`,`fecha_inicio`),
  ADD KEY `vehiculo_propietario_propietario_id_foreign` (`propietario_id`),
  ADD KEY `vehiculo_propietario_vehiculo_id_propietario_id_index` (`vehiculo_id`,`propietario_id`),
  ADD KEY `vehiculo_propietario_fecha_inicio_fecha_fin_index` (`fecha_inicio`,`fecha_fin`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `observaciones`
--
ALTER TABLE `observaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `propietarios`
--
ALTER TABLE `propietarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `salidas`
--
ALTER TABLE `salidas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `suscribers`
--
ALTER TABLE `suscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_vehiculos`
--
ALTER TABLE `tipo_vehiculos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tolerancia`
--
ALTER TABLE `tolerancia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `vehiculo_propietario`
--
ALTER TABLE `vehiculo_propietario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD CONSTRAINT `fk_salidas_registros` FOREIGN KEY (`id_registro`) REFERENCES `registros` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vehiculo_propietario`
--
ALTER TABLE `vehiculo_propietario`
  ADD CONSTRAINT `vehiculo_propietario_propietario_id_foreign` FOREIGN KEY (`propietario_id`) REFERENCES `propietarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehiculo_propietario_vehiculo_id_foreign` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
