<?php
// ROLES DENTRO DEL SISTEMA
$ADMIN = "Administrador";
$JEFE = "Jefe de Carrera";
$ESTUDIANTE = "Estudiante";

// NOMBRES DE LAS MODALIDADES
$ESCOLARIZADO = "Escolarizado";
$FLEXIBLE = "Flexible";

//MODALIDAD TIPO LETRA
$LETRA_ESCOLARIZADO = "A";
$LETRA_FLEXIBLE = "B";


$TABLA_CARRERA_MODALIDAD = "CarreraModalidad";
$TABLA_CARRERAS = "Carrera";
$TABLA_CODIGOQR = "CodigoQR";
$TABLA_ESTADO = "Estado";
$TABLA_ESTUDIANTE = "Estudiante";
$TABLA_GRUPO = "Grupo";
$TABLA_JEFE = "JefeCarrera";
$TABLA_JUSTIFICANTES = "Justificante";
$TABLA_MODALIDADES = "Modalidad";
$TABLA_ROL = "Rol";
$TABLA_SOLICITUDES = "Solicitud";
$TABLA_TIPO_CARRERA = "TipoCarrera";
$TABLA_USUARIO = "Usuario";
$TABLA_TRIGGER_SOLICITUD = "AlumnoSolicitud";
$TABLA_TRIGGER_JUSTIFICANTE = "AlumnoJustificante";

//CAMPOS DE LA TABLA ROL
$CAMPO_ID_ROL = "id_rol";
$CAMPO_ROL = "nombre_rol";


// CAMPOS DE LA TABLA USUARIO
$CAMPO_ID_USUARIO = "id_usuario";
$CAMPO_NOMBRE = "nombre";
$CAMPO_APELLIDOS = "apellidos";
$CAMPO_CONTRASEÑA = "contraseña";
$CAMPO_CORREO = "correo";

// CAMPOS DE LA TABLA TIPOCARRERA
$CAMPO_ID_TIPO_CARRERA = "id_tipo_carrera";
$CAMPO_TIPO_CARRERA = "nombre_tipo_carrera";

// CAMPOS DE LA TABLA CARRERA
$CAMPO_ID_CARRERA = "id_carrera";
$CAMPO_CARRERA = "nombre_carrera";

// CAMPOS DE LA TABLA GRUPO
$CAMPO_ID_GRUPO = "id_grupo";
$CAMPO_NUMERO_GRUPOS = "numero_grupos";
$CAMPO_CLAVE_GRUPO = "clave_grupo";

// CAMPOS DE LA TABLA MODALIDAD
$CAMPO_ID_MODALIDAD = "id_modalidad";
$CAMPO_MODALIDAD = "nombre_modalidad";

//CAMPOS DE LA TABLA CARRERAMODALIDAD
//Foreign key id_carrera y id_modalidad

// CAMPOS DE LA TABLA JEFECARRERA
// Foreign key id_carrera y id_usuario

// CAMPOS DE LA TABLA ESTUDIANTE
//foreign key de id_usuario, $id_carrera y $id_modalidad
$CAMPO_GRUPO = "grupo";

// CAMPOS DE LA TABLA ESTADO
$CAMPO_ID_ESTADO = "id_estado";
$CAMPO_ESTADO = "nombre_estado";

// CAMPOS DE LA TABLA SOLICITUD
$CAMPO_ID_SOLICITUD = "id_solicitud";
$CAMPO_ID_ESTUDIANTE = "id_estudiante";
$CAMPO_ID_JEFE = "id_jefe";
$CAMPO_MOTIVO = "motivo";
$CAMPO_EVIDENCIA = "evidencia";
$CAMPO_FECHA_AUSE = "fecha_ausencia";

// CAMPOS DE LA TABLA CODIGOQR
$CAMPO_ID_CODIGO = "id_codigo";
$CAMPO_DATOS_CODIGO = "datos_codigo";
$CAMPO_URL = "url";

// CAMPOS DE LA TABLA JUSTIFICANTE
$CAMPO_ID_JUSTIFICANTE = "id_justificante";
$CAMPO_FECHA_CREACION = "fecha_creacion";
$CAMPO_NOMBRE_JUSTIFICANTE = "nombre_justificante";

$MESES = [
    "enero",
    "febrero",
    "marzo",
    "abril",
    "mayo",
    "junio",
    "julio",
    "agosto",
    "septiembre",
    "octubre",
    "noviembre",
    "diciembre"
];






