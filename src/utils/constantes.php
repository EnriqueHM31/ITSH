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

    public const CAMPO_CLAVE_EMPLEADO_JEFE = "clave_empleado";
    public const CAMPO_CARRERA = "carrera";

    public const CAMPO_MATRICULA = "matricula";
    public const CAMPO_GRUPO = "grupo";
    public const CAMPO_MODALIDAD = "id_modalidad";


    public const CAMPO_ID_CARRERA = "id_carrera";

    public const CAMPO_NOMBRE = "nombre";
    public const CAMPO_APELLIDOS = "apellidos";
    public const CAMPO_CORREO = "correo";
}
