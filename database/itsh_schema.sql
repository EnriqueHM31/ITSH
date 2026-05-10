-- =============================================================================
-- ITSH - Esquema MySQL/MariaDB alineado con src/utils/constantes.php y
--        consultas en src/utils/functionGlobales.php
-- Charset: utf8mb4 (compatibilidad con mysqli utf8 del proyecto)
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Crear base (ajusta el nombre si usas otro en conexion.php)
CREATE DATABASE IF NOT EXISTS `Sistema`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `Sistema`;

-- -----------------------------------------------------------------------------
-- Tablas base (sin dependencias externas al esquema)
-- -----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Justificante`;
DROP TABLE IF EXISTS `Solicitud`;
DROP TABLE IF EXISTS `CodigoQR`;
DROP TABLE IF EXISTS `Grupo`;
DROP TABLE IF EXISTS `CarreraModalidad`;
DROP TABLE IF EXISTS `Estudiante`;
DROP TABLE IF EXISTS `JefeCarrera`;
DROP TABLE IF EXISTS `Carrera`;
DROP TABLE IF EXISTS `TipoCarrera`;
DROP TABLE IF EXISTS `Modalidad`;
DROP TABLE IF EXISTS `Usuario`;
DROP TABLE IF EXISTS `Estado`;
DROP TABLE IF EXISTS `Rol`;

CREATE TABLE `Rol` (
  `id_rol` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_rol` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `uk_rol_nombre` (`nombre_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Valores usados en código: Administrador, Jefe de Carrera, Estudiante';

CREATE TABLE `Estado` (
  `id_estado` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_estado` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Solicitud: 1=Aprobado, 2=Pendiente (nueva), 3=Rechazado. CodigoQR usa 1=válido, 3=consumido';

CREATE TABLE `TipoCarrera` (
  `id_tipo_carrera` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_tipo_carrera` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_tipo_carrera`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Modalidad` (
  `id_modalidad` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_modalidad` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_modalidad`),
  UNIQUE KEY `uk_modalidad_nombre` (`nombre_modalidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Escolarizado, Flexible';

CREATE TABLE `Carrera` (
  `id_carrera` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_carrera` VARCHAR(200) NOT NULL,
  `id_tipo_carrera` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_carrera`),
  KEY `fk_carrera_tipo` (`id_tipo_carrera`),
  CONSTRAINT `fk_carrera_tipo`
    FOREIGN KEY (`id_tipo_carrera`) REFERENCES `TipoCarrera` (`id_tipo_carrera`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `CarreraModalidad` (
  `id_carrera` INT UNSIGNED NOT NULL,
  `id_modalidad` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_carrera`, `id_modalidad`),
  KEY `fk_cm_modalidad` (`id_modalidad`),
  CONSTRAINT `fk_cm_carrera`
    FOREIGN KEY (`id_carrera`) REFERENCES `Carrera` (`id_carrera`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_cm_modalidad`
    FOREIGN KEY (`id_modalidad`) REFERENCES `Modalidad` (`id_modalidad`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Usuario` (
  `id_usuario` VARCHAR(50) NOT NULL COMMENT 'Matrícula o clave de empleado',
  `nombre` VARCHAR(120) NOT NULL,
  `apellidos` VARCHAR(120) NOT NULL,
  `correo` VARCHAR(180) NOT NULL,
  `contraseña` VARCHAR(255) NOT NULL,
  `id_rol` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuario_rol` (`id_rol`),
  CONSTRAINT `fk_usuario_rol`
    FOREIGN KEY (`id_rol`) REFERENCES `Rol` (`id_rol`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Grupo` (
  `id_grupo` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_carrera` INT UNSIGNED NOT NULL,
  `numero_grupos` VARCHAR(50) NOT NULL,
  `clave_grupo` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_grupo`),
  KEY `fk_grupo_carrera` (`id_carrera`),
  CONSTRAINT `fk_grupo_carrera`
    FOREIGN KEY (`id_carrera`) REFERENCES `Carrera` (`id_carrera`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Estudiante` (
  `id_usuario` VARCHAR(50) NOT NULL,
  `id_carrera` INT UNSIGNED NOT NULL,
  `id_modalidad` INT UNSIGNED NOT NULL,
  `grupo` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_est_carrera` (`id_carrera`),
  KEY `fk_est_modalidad` (`id_modalidad`),
  CONSTRAINT `fk_est_usuario`
    FOREIGN KEY (`id_usuario`) REFERENCES `Usuario` (`id_usuario`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_est_carrera`
    FOREIGN KEY (`id_carrera`) REFERENCES `Carrera` (`id_carrera`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_est_modalidad`
    FOREIGN KEY (`id_modalidad`) REFERENCES `Modalidad` (`id_modalidad`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `JefeCarrera` (
  `id_usuario` VARCHAR(50) NOT NULL,
  `id_carrera` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `uk_jefe_carrera` (`id_carrera`),
  KEY `fk_jefe_carrera` (`id_carrera`),
  CONSTRAINT `fk_jefe_usuario`
    FOREIGN KEY (`id_usuario`) REFERENCES `Usuario` (`id_usuario`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_jefe_carrera`
    FOREIGN KEY (`id_carrera`) REFERENCES `Carrera` (`id_carrera`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Un jefe por carrera (uk_jefe_carrera) y un registro por usuario jefe';

CREATE TABLE `CodigoQR` (
  `id_codigo` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `datos_codigo` TEXT NOT NULL,
  `url` VARCHAR(500) NOT NULL,
  `id_estado` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_codigo`),
  KEY `fk_codigo_estado` (`id_estado`),
  CONSTRAINT `fk_codigo_estado`
    FOREIGN KEY (`id_estado`) REFERENCES `Estado` (`id_estado`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Solicitud` (
  `id_solicitud` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_estudiante` VARCHAR(50) NOT NULL,
  `id_jefe` VARCHAR(50) NOT NULL,
  `motivo` TEXT NOT NULL,
  `fecha_ausencia` VARCHAR(200) NOT NULL COMMENT 'Fecha o rango según formulario (InsertarSolicitudDB usa string)',
  `id_estado` INT UNSIGNED NOT NULL,
  `evidencia` VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`id_solicitud`),
  KEY `fk_sol_est` (`id_estudiante`),
  KEY `fk_sol_jefe` (`id_jefe`),
  KEY `fk_sol_estado` (`id_estado`),
  CONSTRAINT `fk_sol_est`
    FOREIGN KEY (`id_estudiante`) REFERENCES `Usuario` (`id_usuario`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_sol_jefe`
    FOREIGN KEY (`id_jefe`) REFERENCES `Usuario` (`id_usuario`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_sol_estado`
    FOREIGN KEY (`id_estado`) REFERENCES `Estado` (`id_estado`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Justificante` (
  `id_justificante` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_solicitud` INT UNSIGNED NOT NULL,
  `id_estudiante` VARCHAR(50) NOT NULL,
  `id_jefe` VARCHAR(50) NOT NULL,
  `id_codigo` INT UNSIGNED NOT NULL,
  `nombre_justificante` VARCHAR(255) NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_justificante`),
  UNIQUE KEY `uk_just_solicitud` (`id_solicitud`),
  KEY `fk_just_est` (`id_estudiante`),
  KEY `fk_just_jefe` (`id_jefe`),
  KEY `fk_just_codigo` (`id_codigo`),
  CONSTRAINT `fk_just_solicitud`
    FOREIGN KEY (`id_solicitud`) REFERENCES `Solicitud` (`id_solicitud`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_just_est`
    FOREIGN KEY (`id_estudiante`) REFERENCES `Usuario` (`id_usuario`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_just_jefe`
    FOREIGN KEY (`id_jefe`) REFERENCES `Usuario` (`id_usuario`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_just_codigo`
    FOREIGN KEY (`id_codigo`) REFERENCES `CodigoQR` (`id_codigo`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Vistas usadas en código como TABLA_TRIGGER_* (misma estructura lógica)
-- Eliminar datos en la vista delega en Solicitud / Justificante (MySQL compatible)
-- -----------------------------------------------------------------------------

DROP VIEW IF EXISTS `AlumnoJustificante`;
DROP VIEW IF EXISTS `AlumnoSolicitud`;

CREATE VIEW `AlumnoSolicitud` AS
SELECT * FROM `Solicitud`;

CREATE VIEW `AlumnoJustificante` AS
SELECT * FROM `Justificante`;

-- -----------------------------------------------------------------------------
-- Datos iniciales (roles, estados, modalidades — coinciden con constantes.php)
-- -----------------------------------------------------------------------------

INSERT INTO `Rol` (`id_rol`, `nombre_rol`) VALUES
  (1, 'Administrador'),
  (2, 'Jefe de Carrera'),
  (3, 'Estudiante');

INSERT INTO `Estado` (`id_estado`, `nombre_estado`) VALUES
  (1, 'Aprobado'),
  (2, 'Pendiente'),
  (3, 'Rechazado');

INSERT INTO `Modalidad` (`id_modalidad`, `nombre_modalidad`) VALUES
  (1, 'Escolarizado'),
  (2, 'Flexible');

INSERT INTO `TipoCarrera` (`id_tipo_carrera`, `nombre_tipo_carrera`) VALUES
  (1, 'Ingeniería');

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------------------------------
-- Notas
-- -----------------------------------------------------------------------------
-- 1) Añade carreras desde la app (InsertarCarreraDB) o manualmente en Carrera +
--    CarreraModalidad + Grupo.
-- 2) Las contraseñas en el proyecto suelen ir en texto plano; en producción usa
--    password_hash y amplía VARCHAR de contraseña si hace falta.
-- 3) Si id_solicitud en tu BD existente es distinto (p. ej. VARCHAR), ajusta
--    este script antes de importar datos viejos.
