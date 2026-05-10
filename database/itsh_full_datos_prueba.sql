-- =============================================================================
-- ITSH — Script completo: esquema + datos de prueba
-- Alineado con:
--   src/utils/constantes.php (nombres de tablas, roles, modalidades)
--   src/utils/functionGlobales.php (columnas y FK usadas en INSERT/SELECT)
--   src/conexion/conexion.php → base de datos: Sistema (utf8)
--
-- Nomenclatura de grupo (src/assets/js/opcionesSelect.js):
--   Escolarizado: i + '0' + clave_grupo + 'A'  → ej. 404A (semestre 4, carrera 4)
--   Flexible:     i + '0' + clave_grupo + 'B'  → ej. 404B
-- Grupo.numero_grupos = cantidad de semestres generados (1..N).
-- Grupo.clave_grupo   = dígito(s) de carrera en el código (ej. '4' para Sistemas).
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `Sistema`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `Sistema`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Estado` (
  `id_estado` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_estado` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TipoCarrera` (
  `id_tipo_carrera` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_tipo_carrera` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_tipo_carrera`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Constantes: $ESCOLARIZADO / $FLEXIBLE en constantes.php (ids usados en código vía nombre)
CREATE TABLE `Modalidad` (
  `id_modalidad` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_modalidad` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_modalidad`),
  UNIQUE KEY `uk_modalidad_nombre` (`nombre_modalidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `fecha_ausencia` VARCHAR(200) NOT NULL,
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

DROP VIEW IF EXISTS `AlumnoJustificante`;
DROP VIEW IF EXISTS `AlumnoSolicitud`;

CREATE VIEW `AlumnoSolicitud` AS
SELECT * FROM `Solicitud`;

CREATE VIEW `AlumnoJustificante` AS
SELECT * FROM `Justificante`;

-- -----------------------------------------------------------------------------
-- Catálogo base (coincide con $ADMIN, $JEFE, $ESTUDIANTE, estados de solicitud/QR)
-- -----------------------------------------------------------------------------
INSERT INTO `Rol` (`id_rol`, `nombre_rol`) VALUES
  (1, 'Administrador'),
  (2, 'Jefe de Carrera'),
  (3, 'Estudiante');

INSERT INTO `Estado` (`id_estado`, `nombre_estado`) VALUES
  (1, 'Aprobado'),
  (2, 'Pendiente'),
  (3, 'Rechazado');

-- InsertarCodigoQRDB usa id_estado = 1 para código válido; consumido → id_estado 3
INSERT INTO `Modalidad` (`id_modalidad`, `nombre_modalidad`) VALUES
  (1, 'Escolarizado'),
  (2, 'Flexible');

INSERT INTO `TipoCarrera` (`id_tipo_carrera`, `nombre_tipo_carrera`) VALUES
  (1, 'Ingeniería');

-- -----------------------------------------------------------------------------
-- Carreras (nombre exacto para ObtenerIDCarrera / selects)
-- Clave de grupo '4' → 404A, 404B, 504A… (tercer dígito = carrera Sistemas en tu ejemplo)
-- Clave '5' → segunda carrera (405A, 505B…)
-- -----------------------------------------------------------------------------
INSERT INTO `Carrera` (`id_carrera`, `nombre_carrera`, `id_tipo_carrera`) VALUES
  (1, 'Ingeniería en Sistemas Computacionales', 1),
  (2, 'Ingeniería Industrial', 1);

-- Ambas modalidades por carrera (formulario admin / ObtenerIdModalidadesCarrera)
INSERT INTO `CarreraModalidad` (`id_carrera`, `id_modalidad`) VALUES
  (1, 1), (1, 2),
  (2, 1), (2, 2);

-- numero_grupos = 6 → semestres 1..6 → grupos 104x … 604x (A/B según modalidad en JS)
INSERT INTO `Grupo` (`id_grupo`, `id_carrera`, `numero_grupos`, `clave_grupo`) VALUES
  (1, 1, '6', '4'),
  (2, 2, '6', '5');

-- -----------------------------------------------------------------------------
-- Usuarios de prueba (contraseñas en texto plano como en el proyecto)
-- Rol: BuscarEstudianteBD usa id_rol = 3; BuscarPersonalBD usa id_rol 1 y 2
-- -----------------------------------------------------------------------------
INSERT INTO `Usuario` (`id_usuario`, `nombre`, `apellidos`, `correo`, `contraseña`, `id_rol`) VALUES
  ('ITSH_0000', 'Admin', 'Sistema', 'admin@huatusco.tecnm.mx', '12345678', 1),
  ('ITSH_1111', 'Coordinación', 'Jefe de Carrera ISC', '223z0428@alum.huatusco.tecnm.mx', '123456781', 2),
  ('ITSH_2222', 'Hugo', 'Tono Martínez', 'htono@alum.huatusco.tecnm.mx', '123456782', 2),
  ('223z0428', 'Luis Enrique', 'Hernandez Marin', 'enrique@alum.huatusco.tecnm.mx', '123456783', 3);

-- Un jefe por carrera (restricción uk_jefe_carrera)
INSERT INTO `JefeCarrera` (`id_usuario`, `id_carrera`) VALUES
  ('ITSH_1111', 1),
  ('ITSH_2222', 2);

-- Estudiante: ISC, Escolarizado (1), grupo 404A (semestre 4 + 0 + carrera 4 + A)
INSERT INTO `Estudiante` (`id_usuario`, `id_carrera`, `id_modalidad`, `grupo`) VALUES
  ('223z0428', 1, 1, '404A');

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- Resumen de accesos (login con id_usuario y contraseña)
-- =============================================================================
-- | id_usuario | Rol           | Contraseña |
-- |------------|---------------|------------|
-- | ITSH_0000  | Administrador | 12345678   |
-- | ITSH_1111  | Jefe ISC      | 123456781  |
-- | ITSH_2222  | Jefe Industrial | 123456782 |
-- | 223z0428   | Estudiante    | 123456783  |
--
-- Ajusta admin@huatusco.tecnm.mx si prefieres otro correo para ITSH_0000.
-- =============================================================================
