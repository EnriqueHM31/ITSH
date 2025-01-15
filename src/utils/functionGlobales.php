<?php

function estructuraMensaje($mensaje, $icono, $color)
{
    $_SESSION["mensaje"] = $mensaje;
    $_SESSION["icono"] = $icono;
    $_SESSION["color_mensaje"] = "var(" . $color . ")";
}

function notificaciones($notificacion)
{
    if ($notificacion) {
        $mensaje = $_SESSION["mensaje"];
        $icono = $_SESSION["icono"];
        $color = $_SESSION["color_mensaje"];
        mensajeNotificacion($mensaje, $icono, $color);
    }
}

function mensajeNotificacion($mensaje, $imagen, $color)
{
    if (isset($mensaje) && isset($imagen) && isset($color)) {

        echo '
        <div class="overlay">
            <div class="notificacion">
                <img src= "' . $_SESSION["icono"] . '" alt="icono de notificacion" class="alert-svg">
                    <div class="contenido_notificacion ">
                    <p>
                        ' . $_SESSION["mensaje"] . '
                    </p>
                    </div>
                <button style="background-color: ' . $_SESSION["color_mensaje"] . '" class="btn_mensaje">Cerrar</button>
            </div>
        </div>    
            ';
        unset($_SESSION['mensaje']);
        unset($_SESSION['icono']);
        unset($_SESSION['color_mensaje']);
    }
}


// CONSULTAS PARA REUTILIZAR CODIGO LLEGA HASTA $SQL->GET_RESULT()

function getResultDataTabla($conexion, $tabla, $columna, $campo)
{
    $sql = $conexion->prepare("SELECT * FROM " . $tabla . " WHERE " . $columna . " = ?");
    $sql->bind_param("s", $campo);
    $sql->execute();
    $result = $sql->get_result();
    return $result->fetch_assoc();
}

function getResultIDUsuarioDuplicado($conexion, $id)
{
    $sql = $conexion->prepare("SELECT * FROM " . Variables::TABLA_BD_USUARIO . " WHERE " . Variables::CAMPO_ID_USUARIO . " = ?");
    $sql->bind_param("s", $id);
    $sql->execute();
    return $sql->get_result();
}

function getResultCorreoDuplicado($conexion, $correo)
{
    $sql = $conexion->prepare("SELECT * FROM " . Variables::TABLA_BD_USUARIO . " WHERE " . Variables::CAMPO_CORREO . " = ?");
    $sql->bind_param("s", $correo);
    $sql->execute();
    return $sql->get_result();
}

function getResultCarrera($conexion, $id_carrera)
{
    $sqlIdCarrera = $conexion->prepare("SELECT " . Variables::CAMPO_CARRERA . " FROM " . Variables::TABLA_BD_CARRERA . " WHERE " . Variables::CAMPO_ID_CARRERA . " = ?");
    $sqlIdCarrera->bind_param("s", $id_carrera);
    $sqlIdCarrera->execute();
    $result = $sqlIdCarrera->get_result();
    $carrera = $result->fetch_assoc();

    return $carrera[Variables::CAMPO_CARRERA];
}

function getResultCarreraDuplicada($tabla, $conexion, $id_carrera)
{
    $sqlCarreraDuplicada = $conexion->prepare("SELECT * FROM " . $tabla . " WHERE " . Variables::CAMPO_ID_CARRERA . " = ?");
    $sqlCarreraDuplicada->bind_param("i", $id_carrera);
    $sqlCarreraDuplicada->execute();
    return $sqlCarreraDuplicada->get_result();
}

// CONSULTAS OARA OBTENER UN VALOR
function obtenerIDRol($conexion, $rol)
{
    $sql = $conexion->prepare("SELECT " . Variables::CAMPO_ID_ROL . " FROM " . Variables::TABLA_BD_ROL . " WHERE " . Variables::CAMPO_ROL . " = ?");
    $sql->bind_param("s", $rol);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[Variables::CAMPO_ID_ROL];
}

function obtenerRol($conexion, $id_rol)
{
    $sql = $conexion->prepare("SELECT " . Variables::CAMPO_ROL . " FROM " . Variables::TABLA_BD_ROL . " WHERE " . Variables::CAMPO_ID_ROL . " = ?");
    $sql->bind_param("s", $id_rol);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[Variables::CAMPO_ROL];
}

function obtenerIDCarrera($conexion, $carrera)
{
    $sql = $conexion->prepare("SELECT " . Variables::CAMPO_ID_CARRERA . " FROM " . Variables::TABLA_BD_CARRERA . " WHERE " . Variables::CAMPO_CARRERA . " = ?");
    $sql->bind_param("s", $carrera);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();
    return $response[Variables::CAMPO_ID_CARRERA];
}

//CONSULTAS PARA INSERTAR DATOS 
function insertarUsuario($conexion, $cargo, $identificador, $contraseña, $correo)
{
    $rol = obtenerIDRol($conexion, $cargo);
    $usuario = $conexion->prepare("INSERT INTO " . Variables::TABLA_BD_USUARIO . " (" . Variables::CAMPO_ID_USUARIO . "," . Variables::CAMPO_CONTRASEÑA . "," . Variables::CAMPO_CORREO . "," . Variables::CAMPO_ID_ROL . ") VALUES (?, ?, ?, ?)");

    $usuario->bind_param("sssi", $identificador, $contraseña, $correo, $rol);

    return $usuario->execute();
}

function insertarAdministrador($conexion, $identificador, $nombre, $apellidos)
{
    $admin = $conexion->prepare("INSERT INTO " . Variables::TABLA_BD_AdMINISTRADOR . " (" . Variables::CAMPO_CLAVE_EMPLEADO_ADMIN . "," . Variables::CAMPO_NOMBRE . "," . Variables::CAMPO_APELLIDOS . ") VALUES (?, ?, ?)");

    $admin->bind_param("sss", $identificador, $nombre, $apellidos);
    return $admin->execute();
}

function insertarJefedeCarrera($conexion, $identificador, $nombre, $apellidos, $carrera)
{
    $IDCarrera = obtenerIDCarrera($conexion, $carrera);

    $jefe = $conexion->prepare("INSERT INTO " . Variables::TABLA_BD_JEFE . " (" . Variables::CAMPO_CLAVE_EMPLEADO_JEFE . "," . Variables::CAMPO_NOMBRE . "," . Variables::CAMPO_APELLIDOS . "," . Variables::CAMPO_ID_CARRERA . ") VALUES (?, ?, ?, ?)");
    $jefe->bind_param("sssi", $identificador, $nombre, $apellidos, $IDCarrera);

    return $jefe->execute();
}

