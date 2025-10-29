-- =====================================================
-- SCHEMA PARA BASE DE DATOS: factura2026
-- Sistema de Facturación Independiente
-- =====================================================

-- Crear la base de datos (si no existe)
CREATE DATABASE IF NOT EXISTS `factura2026` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `factura2026`;

-- =====================================================
-- TABLA: empresa
-- =====================================================
CREATE TABLE IF NOT EXISTS `empresa` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruc` varchar(11) NOT NULL,
  `razon_social` varchar(255) NOT NULL,
  `nombre_comercial` varchar(255) DEFAULT NULL,
  `direccion` varchar(500) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empresa_ruc_unique` (`ruc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: clientes
-- =====================================================
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo_documento` varchar(10) NOT NULL COMMENT 'DNI, RUC, CE, etc.',
  `numero_documento` varchar(20) NOT NULL,
  `nombres` varchar(255) DEFAULT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `direccion` varchar(500) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clientes_numero_documento_unique` (`numero_documento`),
  KEY `clientes_tipo_documento_index` (`tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: series
-- =====================================================
CREATE TABLE IF NOT EXISTS `series` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_comprobante` varchar(50) NOT NULL COMMENT 'FACTURA, BOLETA, NOTA_CREDITO, NOTA_DEBITO',
  `serie` varchar(10) NOT NULL COMMENT 'F001, B001, etc.',
  `correlativo_actual` int(11) NOT NULL DEFAULT 0,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `series_empresa_id_foreign` (`empresa_id`),
  KEY `series_tipo_comprobante_index` (`tipo_comprobante`),
  CONSTRAINT `series_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: comprobantes
-- =====================================================
CREATE TABLE IF NOT EXISTS `comprobantes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `serie_id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_comprobante` varchar(50) NOT NULL COMMENT 'FACTURA, BOLETA, NOTA_CREDITO, NOTA_DEBITO',
  `serie` varchar(10) NOT NULL,
  `correlativo` varchar(20) NOT NULL,
  `numero_completo` varchar(50) NOT NULL COMMENT 'F001-00000001',
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'PEN' COMMENT 'PEN, USD, EUR',
  `tipo_cambio` decimal(10,2) DEFAULT 1.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `igv` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` varchar(50) DEFAULT 'EMITIDO' COMMENT 'EMITIDO, ANULADO, PAGADO',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comprobantes_numero_completo_unique` (`numero_completo`),
  KEY `comprobantes_serie_id_foreign` (`serie_id`),
  KEY `comprobantes_cliente_id_foreign` (`cliente_id`),
  KEY `comprobantes_fecha_emision_index` (`fecha_emision`),
  KEY `comprobantes_estado_index` (`estado`),
  CONSTRAINT `comprobantes_serie_id_foreign` FOREIGN KEY (`serie_id`) REFERENCES `series` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comprobantes_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DATOS DE PRUEBA (OPCIONAL)
-- =====================================================

-- Insertar empresa de prueba
INSERT INTO `empresa` (`ruc`, `razon_social`, `nombre_comercial`, `direccion`, `telefono`, `email`, `estado`, `created_at`, `updated_at`) VALUES
('20123456789', 'EMPRESA DEMO SAC', 'DEMO', 'Av. Principal 123, Lima', '987654321', 'contacto@demo.com', 1, NOW(), NOW());

-- Insertar clientes de prueba
INSERT INTO `clientes` (`tipo_documento`, `numero_documento`, `nombres`, `apellidos`, `razon_social`, `direccion`, `telefono`, `email`, `estado`, `created_at`, `updated_at`) VALUES
('DNI', '12345678', 'Juan', 'Pérez García', NULL, 'Jr. Los Olivos 456, Lima', '999888777', 'juan@ejemplo.com', 1, NOW(), NOW()),
('RUC', '20987654321', NULL, NULL, 'CLIENTE CORPORATIVO SAC', 'Av. Industrial 789, Lima', '988777666', 'ventas@cliente.com', 1, NOW(), NOW());

-- Insertar series de prueba
INSERT INTO `series` (`empresa_id`, `tipo_comprobante`, `serie`, `correlativo_actual`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'FACTURA', 'F001', 0, 1, NOW(), NOW()),
(1, 'BOLETA', 'B001', 0, 1, NOW(), NOW());

-- Insertar comprobante de prueba
INSERT INTO `comprobantes` (`serie_id`, `cliente_id`, `tipo_comprobante`, `serie`, `correlativo`, `numero_completo`, `fecha_emision`, `fecha_vencimiento`, `moneda`, `tipo_cambio`, `subtotal`, `igv`, `total`, `estado`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, 'FACTURA', 'F001', '00000001', 'F001-00000001', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'PEN', 1.00, 100.00, 18.00, 118.00, 'EMITIDO', 'Comprobante de prueba', NOW(), NOW());

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================
