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



// CONSULTA PARA OBTENER LA FILA COMPLETA UN ARRAY CLAVE VALOR*/
function getResultDataTabla($conexion, $tabla, $columna, $campo)
{
    $sql = $conexion->prepare("SELECT * FROM " . $tabla . " WHERE " . $columna . " = ?");
    $sql->bind_param("s", $campo);
    $sql->execute();
    $result = $sql->get_result();
    return $result->fetch_assoc();
}
// CONSULTA PARA OBTENER EL RESULTADO DE LA CONSULTA ID DUPLICADA
function getResultIDUsuarioDuplicado($conexion, $id)
{
    $sql = $conexion->prepare("SELECT * FROM " . Variables::TABLA_BD_USUARIO . " WHERE " . Variables::CAMPO_ID_USUARIO . " = ?");
    $sql->bind_param("s", $id);
    $sql->execute();
    return $sql->get_result();
}
// CONSULTA PARA OBTENER EL RESULTADO DE LA CONSULTA CORREO DUPLICADO   
function getResultCorreoDuplicado($conexion, $correo)
{
    $sql = $conexion->prepare("SELECT * FROM " . Variables::TABLA_BD_USUARIO . " WHERE " . Variables::CAMPO_CORREO . " = ?");
    $sql->bind_param("s", $correo);
    $sql->execute();
    return $sql->get_result();
}
// CONSULTA PARA OBTENER EL NOMBRE DE ÑA CARRERA DE LA TABLA CARRERA
function getResultCarrera($conexion, $id_carrera)
{
    $sqlIdCarrera = $conexion->prepare("SELECT " . Variables::CAMPO_CARRERA . " FROM " . Variables::TABLA_BD_CARRERA . " WHERE " . Variables::CAMPO_ID_CARRERA . " = ?");
    $sqlIdCarrera->bind_param("s", $id_carrera);
    $sqlIdCarrera->execute();
    $result = $sqlIdCarrera->get_result();
    $carrera = $result->fetch_assoc();

    return $carrera[Variables::CAMPO_CARRERA];
}
// CONSULTA PARA OBTENER EL RESULTADO SI HAY UNA CARRERA DUPLICADA
function getResultCarreraDuplicada($tabla, $conexion, $id_carrera)
{
    $sqlCarreraDuplicada = $conexion->prepare("SELECT * FROM " . $tabla . " WHERE " . Variables::CAMPO_ID_CARRERA . " = ?");
    $sqlCarreraDuplicada->bind_param("i", $id_carrera);
    $sqlCarreraDuplicada->execute();
    return $sqlCarreraDuplicada->get_result();
}

// CONSULTA PARA OBTENER EL ID DE ROL
function obtenerIDRol($conexion, $rol)
{
    $sql = $conexion->prepare("SELECT " . Variables::CAMPO_ID_ROL . " FROM " . Variables::TABLA_BD_ROL . " WHERE " . Variables::CAMPO_ROL . " = ?");
    $sql->bind_param("s", $rol);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[Variables::CAMPO_ID_ROL];
}
// CONSULTA PARA OBTENER EL ROL
function obtenerRol($conexion, $id_rol)
{
    $sql = $conexion->prepare("SELECT " . Variables::CAMPO_ROL . " FROM " . Variables::TABLA_BD_ROL . " WHERE " . Variables::CAMPO_ID_ROL . " = ?");
    $sql->bind_param("s", $id_rol);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[Variables::CAMPO_ROL];
}
// CONSULTA PARA OBTENER EL ID DE CARRERA
function obtenerIDCarrera($conexion, $carrera)
{
    $sql = $conexion->prepare("SELECT " . Variables::CAMPO_ID_CARRERA . " FROM " . Variables::TABLA_BD_CARRERA . " WHERE " . Variables::CAMPO_CARRERA . " = ?");
    $sql->bind_param("s", $carrera);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[Variables::CAMPO_ID_CARRERA];
}

//CONSULTAS PARA INSERTAR DATOS EN LA TABLA USUARIO
function insertarUsuario($conexion, $clave_empleado, $contraseña, $correo, $cargo)
{
    $rol = obtenerIDRol($conexion, $cargo);
    $usuario = $conexion->prepare("INSERT INTO " . Variables::TABLA_BD_USUARIO . " (" . Variables::CAMPO_ID_USUARIO . "," . Variables::CAMPO_CONTRASEÑA . "," . Variables::CAMPO_CORREO . "," . Variables::CAMPO_ID_ROL . ") VALUES (?, ?, ?, ?)");

    $usuario->bind_param("sssi", $clave_empleado, $contraseña, $correo, $rol);

    return $usuario->execute();
}
//CONSULTA PARA INSERTAR DATOS EN LA TABLA ADMINISTRADOR
function insertarAdministrador($conexion, $identificador, $nombre, $apellidos)
{
    $admin = $conexion->prepare("INSERT INTO " . Variables::TABLA_BD_AdMINISTRADOR . " (" . Variables::CAMPO_CLAVE_EMPLEADO_ADMIN . "," . Variables::CAMPO_NOMBRE . "," . Variables::CAMPO_APELLIDOS . ") VALUES (?, ?, ?)");

    $admin->bind_param("sss", $identificador, $nombre, $apellidos);
    return $admin->execute();
}
// CONSULTA PARA INSERTAR DATOS EN LA TABLA JEFE
function insertarJefedeCarrera($conexion, $identificador, $nombre, $apellidos, $carrera)
{
    $IDCarrera = obtenerIDCarrera($conexion, $carrera);

    $jefe = $conexion->prepare("INSERT INTO " . Variables::TABLA_BD_JEFE . " (" . Variables::CAMPO_CLAVE_EMPLEADO_JEFE . "," . Variables::CAMPO_NOMBRE . "," . Variables::CAMPO_APELLIDOS . "," . Variables::CAMPO_ID_CARRERA . ") VALUES (?, ?, ?, ?)");
    $jefe->bind_param("sssi", $identificador, $nombre, $apellidos, $IDCarrera);

    return $jefe->execute();
}
// CONSULTAS UPDATE PARA LOS USUARIOS ADMINISTRADOR Y JEFE DE CARRERA
function modificarPersonal($conexion, $id, $nombre, $apellidos, $carrera, $cargo, $correo)
{
    return EliminarPersonal($conexion, $id);
}
// CONSULTA PARA ELIMINAR DATOS DE LA TABLA USUARIO
function EliminarPersonal($conexion, $id)
{
    $sql = "DELETE FROM " . Variables::TABLA_BD_USUARIO . " WHERE " . Variables::CAMPO_ID_USUARIO . " = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $id);
    return $stmt->execute();
}

//CONSULTA PARA OBTENER LA ID DE LA MODALIDAD 
function obtenerIdModalidad($conexion, $modalidad)
{
    $sql = $conexion->prepare("SELECT " . Variables::CAMPO_ID_MODALIDAD . " FROM " . Variables::tABLA_BD_MODALIDAD . " WHERE " . Variables::CAMPO_MODALIDAD . " = ?");
    $resultIDModalidad = $sql->bind_param("s", $modalidad);
    $resultIDModalidad->execute();
    $result = $resultIDModalidad->get_result()->fetch_assoc();

    return $result[Variables::CAMPO_ID_MODALIDAD];
}