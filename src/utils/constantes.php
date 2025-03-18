<?php
class Variables
{
    // VARIABLES PARA LA CONEXION
    public const HOST = "localhost";
    public const USERNAME = "root";
    public const CONTRASEÑA = "1234";
    public const DATABASE = "itsh";

    // TABLAS DE LAS BASES DE DATOS
    public const TABLA_BD_USUARIO = "usuario";
    public const TABLA_BD_ROL = "rol";
    public const TABLA_BD_CARRERA = "carrera";
    public const TABLA_BD_AdMINISTRADOR = "Administrador";
    public const TABLA_BD_JEFE = "Jefe";
    public const TABLA_BD_ESTUDIANTE = "Estudiante";
    public const tABLA_BD_MODALIDAD = "Modalidad";
    public const TABLA_SOLICITUDES = "solicitudes";
    public const TABLA_BD_JUSTIFICANTES = "justificantes";


    // CAMPOS DE LA TABLA USUARIO
    public const CAMPO_ID_USUARIO = "id_usuario";
    public const CAMPO_CONTRASEÑA = "contraseña";
    public const CAMPO_ID_ROL = "id_rol";

    // CAMPOS DE LA TABLA ROL
    public const CAMPO_ROL = "rol";
    public const MENU_DE_ROLES = [
        "Administrador",
        "Jefe de Carrera",
        "Estudiante",
    ];

    // CAMPOS DE LA TABLA ADMINISTRADOR
    public const CAMPO_CLAVE_EMPLEADO_ADMIN = "clave_empleado";

    // CAMPOS DE LA TABLA JEFE
    public const CAMPO_CLAVE_EMPLEADO_JEFE = "clave_empleado";
    public const CAMPO_CARRERA = "carrera";
    public const CAMPO_ID_CARRERA = "id_carrera";

    // CAMPOS DE LA TABLA ESTUDIANTE
    public const CAMPO_MATRICULA = "matricula";
    public const CAMPO_GRUPO = "grupo";
    public const CAMPO_ID_MODALIDAD = "id_modalidad";
    public const CAMPO_MODALIDAD = "modalidad";

    public const CAMPO_NOMBRE = "nombre";
    public const CAMPO_APELLIDOS = "apellidos";
    public const CAMPO_CORREO = "correo";

    public const GRUPOS_DISPONIBLES = [
        "Industrial" => ["numero" => 1, "cantidad" => 8],
        "Insdustrias Alimentarias" => ["numero" => 2, "cantidad" => 7],
        "Electromecanica" => ["numero" => 3, "cantidad" => 9],
        "Sistemas Computacionales" => ["numero" => 4, "cantidad" => 9],
        "Gestion Empresarial" => ["numero" => 5, "cantidad" => 9],
        "Contador Publico" => ["numero" => 6, "cantidad" => 9],
        "Quimica" => ["numero" => 7, "cantidad" => 8],
        "Ambiental" => ["numero" => 1, "cantidad" => 7],
    ];

    public const ID_SOLICITUD = "solicitud";
    public const ESTADO = "estado";


    public const CAMPO_J_ID = "id";
    public const CAMPO_J_MATRICULA = "matricula_alumno";
    public const CAMPO_J_NOMBRE = "nombre_alumno";
    public const CAMPO_J_APELLIDOS = "apellidos_alumno";
    public const CAMPO_J_MOTIVO = "motivo";
    public const CAMPO_J_GRUPO = "grupo";
    public const CAMPO_J_CARRERA = "carrera";
    public const CAMPO_J_NOMBRE_JEFE = "nombre_jefe";
    public const CAMPO_J_FECHA = "fecha_creacion";
    public const CAMPO_J_JUSTIFICANTE = "justificante_pdf";


}

