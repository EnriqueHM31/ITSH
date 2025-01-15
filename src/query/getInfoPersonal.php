<?php
include("../utils/constantes.php");
include("../conexion/conexion.php");
include("../utils/functionGlobales.php");

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];


    $dataUser = getResultDataTabla($conexion, Variables::TABLA_BD_USUARIO, Variables::CAMPO_ID_USUARIO, $id);
    $idUser = $dataUser[Variables::CAMPO_ID_USUARIO];
    $correo = $dataUser[Variables::CAMPO_CORREO];
    $rol = obtenerRol($conexion, $dataUser[Variables::CAMPO_ID_ROL]);


    if ($rol === "Administrador") {
        $dataAdministrador = getResultDataTabla($conexion, Variables::TABLA_BD_AdMINISTRADOR, Variables::CAMPO_CLAVE_EMPLEADO_ADMIN, $id);
        $nombre = $dataAdministrador[Variables::CAMPO_NOMBRE];
        $apellidos = $dataAdministrador[Variables::CAMPO_APELLIDOS];

        $data = [
            "identificador" => $idUser,
            "nombre" => $nombre,
            "apellidos" => $apellidos,
            "carrera" => "null",
            "cargo" => $rol,
            "correo" => $correo
        ];

        echo json_encode($data);
    } else if ($rol === "Jefe de Carrera") {
        $dataJefe = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, campo: $id);
        $nombre = $dataJefe[Variables::CAMPO_NOMBRE];
        $apellidos = $dataJefe[Variables::CAMPO_APELLIDOS];
        $carrera = getResultCarrera($conexion, $dataJefe[Variables::CAMPO_ID_CARRERA]);

        $data = [
            "identificador" => $idUser,
            "nombre" => $nombre,
            "apellidos" => $apellidos,
            "carrera" => $carrera,
            "cargo" => $rol,
            "correo" => $correo
        ];
        echo json_encode($data);

    } else {
        echo json_encode(["error" => "ID no proporcionado"]);
    }



}

