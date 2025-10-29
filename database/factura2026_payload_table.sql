-- Tabla payload para almacenar respuestas de SUNAT
CREATE TABLE IF NOT EXISTS `payload` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_comprobante` INT(11) NOT NULL,
  `estado` VARCHAR(50) NULL DEFAULT NULL COMMENT 'ACEPTADO, RECHAZADO, etc',
  `hash` VARCHAR(255) NULL DEFAULT NULL,
  `xml` VARCHAR(500) NULL DEFAULT NULL COMMENT 'URL del XML',
  `cdr` VARCHAR(500) NULL DEFAULT NULL COMMENT 'URL del CDR',
  `ticket` VARCHAR(500) NULL DEFAULT NULL COMMENT 'URL del ticket PDF',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_comprobante` (`id_comprobante` ASC),
  CONSTRAINT `fk_payload_comprobante`
    FOREIGN KEY (`id_comprobante`)
    REFERENCES `comprobantes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
