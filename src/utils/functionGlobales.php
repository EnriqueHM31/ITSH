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
                <button style="background-color: ' . $_SESSION["color_mensaje"] . '" class="btn_mensaje_php btn_mensaje">Cerrar</button>
            </div>
        </div>    
            ';
        unset($_SESSION['mensaje']);
        unset($_SESSION['icono']);
        unset($_SESSION['color_mensaje']);
    }
}

function obtenerDatosColumnaTabla($conexion, $columna, $tabla)
{
    $sql = $conexion->prepare("SELECT $columna FROM " . $tabla);
    $sql->execute();
    return $sql->get_result();
}

// CONSULTA PARA OBTENER LA FILA COMPLETA UN ARRAY CLAVE VALOR*/
function getResultDataTabla($conexion, $tabla, $columna, $campo)
{
    $sql = $conexion->prepare("SELECT * FROM $tabla WHERE $columna = ?");
    $sql->bind_param("s", $campo);
    $sql->execute();
    $result = $sql->get_result();
    return $result->fetch_assoc();
}

// CONSULTA PARA OBTENER EL RESULTADO DE LA CONSULTA ID DUPLICADA
function getResultIDUsuarioDuplicado($conexion, $id)
{
    global $TABLA_USUARIO;
    global $CAMPO_ID_USUARIO;

    $sql = $conexion->prepare("SELECT * FROM $TABLA_USUARIO WHERE $CAMPO_ID_USUARIO = ?");
    $sql->bind_param("s", $id);
    $sql->execute();
    return $sql->get_result();
}

// CONSULTA PARA OBTENER EL RESULTADO DE LA CONSULTA CORREO DUPLICADO   
function getResultCorreoDuplicado($conexion, $correo)
{
    global $TABLA_USUARIO, $CAMPO_CORREO;

    $sql = $conexion->prepare("SELECT * FROM $TABLA_USUARIO WHERE $CAMPO_CORREO = ?");
    $sql->bind_param("s", $correo);
    $sql->execute();
    return $sql->get_result();
}

// CONSULTA PARA OBTENER EL NOMBRE DE LA CARRERA DE LA TABLA CARRERA
function getResultCarrera($conexion, $id_carrera)
{
    global $TABLA_CARRERAS, $CAMPO_ID_CARRERA, $CAMPO_CARRERA;

    $sqlIdCarrera = $conexion->prepare("SELECT $CAMPO_CARRERA FROM $TABLA_CARRERAS WHERE $CAMPO_ID_CARRERA = ?");
    $sqlIdCarrera->bind_param("s", $id_carrera);
    $sqlIdCarrera->execute();
    $result = $sqlIdCarrera->get_result();
    $carrera = $result->fetch_assoc();

    return $carrera[$CAMPO_CARRERA];
}

// CONSULTA PARA OBTENER EL RESULTADO SI HAY UNA CARRERA DUPLICADA
function getResultCarreraDuplicada($tabla, $conexion, $id_carrera)
{
    global $CAMPO_ID_CARRERA;
    $sqlCarreraDuplicada = $conexion->prepare("SELECT * FROM $tabla WHERE $CAMPO_ID_CARRERA = ?");
    $sqlCarreraDuplicada->bind_param("i", $id_carrera);
    $sqlCarreraDuplicada->execute();
    return $sqlCarreraDuplicada->get_result();
}

// CONSULTA PARA OBTENER EL ID DE ROL
function obtenerIDRol($conexion, $rol)
{
    global $TABLA_ROL, $CAMPO_ID_ROL, $CAMPO_ROL;

    $sql = $conexion->prepare("SELECT $CAMPO_ID_ROL FROM $TABLA_ROL WHERE $CAMPO_ROL = ?");
    $sql->bind_param("s", $rol);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[$CAMPO_ID_ROL];
}

// CONSULTA PARA OBTENER EL ROL
function obtenerRol($conexion, $id_rol)
{
    global $TABLA_ROL, $CAMPO_ID_ROL, $CAMPO_ROL;

    $sql = $conexion->prepare("SELECT $CAMPO_ROL FROM $TABLA_ROL WHERE $CAMPO_ID_ROL = ?");
    $sql->bind_param("s", $id_rol);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[$CAMPO_ROL];
}

// CONSULTA PARA OBTENER EL ID DE CARRERA
function obtenerIDCarrera($conexion, $carrera)
{
    global $TABLA_CARRERAS, $CAMPO_ID_CARRERA, $CAMPO_CARRERA;

    $sql = $conexion->prepare("SELECT $CAMPO_ID_CARRERA FROM $TABLA_CARRERAS WHERE $CAMPO_CARRERA = ?");
    $sql->bind_param("s", $carrera);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[$CAMPO_ID_CARRERA];
}

//CONSULTA PARA OBTENER LA ID DE LA MODALIDAD 
function obtenerIdModalidad($conexion, $modalidad)
{
    global $TABLA_MODALIDADES, $CAMPO_ID_MODALIDAD, $CAMPO_MODALIDAD;

    $sql = $conexion->prepare("SELECT $CAMPO_ID_MODALIDAD FROM $TABLA_MODALIDADES WHERE $CAMPO_MODALIDAD = ?");
    $sql->bind_param("s", $modalidad);
    $sql->execute();
    $result = $sql->get_result();
    $response = $result->fetch_assoc();
    return $response[$CAMPO_ID_MODALIDAD];
}

//CONSULTA PARA OBTENER LA MODALIDAD
function obtenerModalidad($conexion, $id_modalidad)
{
    global $TABLA_MODALIDADES, $CAMPO_ID_MODALIDAD, $CAMPO_MODALIDAD;

    $sql = $conexion->prepare("SELECT $CAMPO_MODALIDAD FROM $TABLA_MODALIDADES WHERE $CAMPO_ID_MODALIDAD = ?");
    $sql->bind_param("s", $id_modalidad);
    $sql->execute();
    $result = $sql->get_result();
    $response = $result->fetch_assoc();

    return $response[$CAMPO_MODALIDAD];
}

// CONSULTA PARA OBTENER LA CONTRASEÑA ACTUAL DESDE LA BD A PARTIR DE UNA ID
function obtenerContraseñaActualBD($conexion, $id)
{
    global $TABLA_USUARIO, $CAMPO_CONTRASEÑA, $CAMPO_ID_USUARIO;

    $sqlComprobacionContraseña = "SELECT $CAMPO_CONTRASEÑA FROM $TABLA_USUARIO WHERE $CAMPO_ID_USUARIO= ?";
    $stmt = $conexion->prepare($sqlComprobacionContraseña);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $resultContraseña = $stmt->get_result()->fetch_assoc();
    return $resultContraseña[$CAMPO_CONTRASEÑA];
}

//CONSULTAS PARA INSERTAR DATOS EN LA TABLA USUARIO
function insertarUsuario($conexion, $id_usuario, $nombre, $apellidos, $correo, $contraseña, $cargo)
{
    global $TABLA_USUARIO, $CAMPO_ID_USUARIO, $CAMPO_NOMBRE, $CAMPO_APELLIDOS, $CAMPO_CONTRASEÑA, $CAMPO_CORREO, $CAMPO_ID_ROL;

    $rol = obtenerIDRol($conexion, $cargo);
    $consulta = "INSERT INTO $TABLA_USUARIO ($CAMPO_ID_USUARIO, $CAMPO_NOMBRE, $CAMPO_APELLIDOS, $CAMPO_CORREO, $CAMPO_CONTRASEÑA, $CAMPO_ID_ROL) VALUES (?, ?, ?, ?, ?, ?)";
    $usuario = $conexion->prepare($consulta);
    $usuario->bind_param("sssssi", $id_usuario, $nombre, $apellidos, $contraseña, $correo, $rol);
    return $usuario->execute();
}



// CONSULTA PARA INSERTAR DATOS EN LA TABLA JEFE
function insertarJefedeCarrera($conexion, $identificador, $carrera)
{
    global $TABLA_JEFE, $CAMPO_ID_CARRERA, $CAMPO_ID_USUARIO;

    $IDCarrera = obtenerIDCarrera($conexion, $carrera);
    $jefe = $conexion->prepare("INSERT INTO $TABLA_JEFE ($CAMPO_ID_USUARIO, $CAMPO_ID_CARRERA) VALUES (?, ?)");
    $jefe->bind_param("si", $identificador, $IDCarrera);

    return $jefe->execute();
}

//CONSULTA PARA INSERTAR DATOS EN LA TABLA ESTUDIANTE
function insertarEstudiante($conexion, $matricula, $nombre, $apellidos, $id_carrera, $id_modalidad, $grupo)
{
    global $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $CAMPO_GRUPO, $CAMPO_ID_CARRERA, $CAMPO_ID_MODALIDAD;

    $sql = $conexion->prepare("INSERT INTO $TABLA_ESTUDIANTE ($CAMPO_ID_USUARIO, $CAMPO_ID_CARRERA,$CAMPO_ID_MODALIDAD, $CAMPO_GRUPO) VALUES (?, ?, ?, ?)");
    $sql->bind_param("siis", $matricula, $id_carrera, $id_modalidad, $grupo);
    return $sql->execute();

}

// CONSULTA OARA MODIFICAR LA CONTRASEÑA ACTUAL DESDE LA PAGINA INICIO (DENTRO DEL SISTEMA)
function modificarLaContraseñaActualPaginaInicio($conexion, $id, $contraseña_nueva)
{
    global $TABLA_USUARIO, $CAMPO_CONTRASEÑA, $CAMPO_ID_USUARIO;

    $sql = "UPDATE $TABLA_USUARIO SET $CAMPO_CONTRASEÑA = ? WHERE $CAMPO_ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $contraseña_nueva, $id);
    return $stmt->execute();
}

// CONSULTA PARA ELIMINAR DATOS DE LA TABLA USUARIO
function EliminarUsuario($conexion, $id)
{
    global $TABLA_USUARIO, $CAMPO_ID_USUARIO;
    $sql = "DELETE FROM $TABLA_USUARIO WHERE $CAMPO_ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $id);
    return $stmt->execute();

}

function EliminarCarrera($conexion, $carreraNueva)
{
    global $TABLA_CARRERAS, $CAMPO_CARRERA;

    $consulta = "DELETE FROM $TABLA_CARRERAS WHERE $CAMPO_CARRERA = ?";
    $sql = $conexion->prepare($consulta);
    $sql->bind_param("s", $carreraNueva);
    return $sql->execute();
}

function ModificarEstadoSolicitud($conexion, $id_solicitud)
{
    global $TABLA_SOLICITUDES, $CAMPO_ID_ESTADO, $CAMPO_ID_SOLICITUD;
    $sql = "UPDATE $TABLA_SOLICITUDES SET $CAMPO_ID_ESTADO = ? WHERE $CAMPO_ID_SOLICITUD = ?";
    $id_estado = 1;
    $smtm = $conexion->prepare($sql);
    $smtm->bind_param("ii", $id_estado, $id_solicitud);
    $smtm->execute();
}

function InsertarTablaJustificante($conexion, $id_solicitud, $id_estudiante, $id_jefe, $id_codigo, $nombre_justificante)
{
    global $TABLA_JUSTIFICANTES, $CAMPO_ID_SOLICITUD, $CAMPO_ID_ESTUDIANTE, $CAMPO_ID_JEFE, $CAMPO_ID_CODIGO, $CAMPO_NOMBRE_JUSTIFICANTE;

    $sql = "INSERT INTO $TABLA_JUSTIFICANTES ( $CAMPO_ID_SOLICITUD, $CAMPO_ID_ESTUDIANTE, $CAMPO_ID_JEFE, $CAMPO_ID_CODIGO, $CAMPO_NOMBRE_JUSTIFICANTE) VALUES (?, ?, ?, ?, ?)";
    $smtm = $conexion->prepare($sql);
    $smtm->bind_param('issss', $id_solicitud, $id_estudiante, $id_jefe, $id_codigo, $nombre_justificante);

    return $smtm->execute();
}

function insertarCodigoQR($conexion, $qr_text, $valido, $url_verificacion)
{

    global $TABLA_CODIGOQR, $CAMPO_DATOS_CODIGO, $CAMPO_URL, $CAMPO_ID_ESTADO;
    $sql = "INSERT INTO $TABLA_CODIGOQR ($CAMPO_DATOS_CODIGO, $CAMPO_URL, $CAMPO_ID_ESTADO) VALUES (?, ?, ?)";

    $smtm = $conexion->prepare($sql);
    $valido = 1;
    $smtm->bind_param("sss", $qr_text, $url_verificacion, $valido);
    $smtm->execute();
    $ultimo_id = $conexion->insert_id;
    return $ultimo_id;

}


function obtenerCodigoQVerificacion($conexion, $qr_text)
{
    global $TABLA_CODIGOQR, $CAMPO_ID_ESTADO, $CAMPO_DATOS_CODIGO;

    $sql = "SELECT $CAMPO_ID_ESTADO FROM $TABLA_CODIGOQR WHERE $CAMPO_DATOS_CODIGO = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $qr_text);
    $stmt->execute();
    return $stmt->get_result();
}

function actualizarValidacionCodigoQR($conexion, $qr_text)
{
    global $TABLA_CODIGOQR, $CAMPO_ID_ESTADO, $CAMPO_DATOS_CODIGO;
    $update = "UPDATE $TABLA_CODIGOQR SET $CAMPO_ID_ESTADO = 3 WHERE $CAMPO_DATOS_CODIGO = ?";

    $stmt_update = $conexion->prepare($update);
    $stmt_update->bind_param("s", $qr_text);
    return $stmt_update->execute();
}

function modificarSolicitudRechazado($conexion, $id_solicitud)
{
    global $TABLA_SOLICITUDES, $CAMPO_ID_ESTADO, $CAMPO_ID_SOLICITUD;
    $sql = "UPDATE $TABLA_SOLICITUDES SET $CAMPO_ID_ESTADO = ? WHERE $CAMPO_ID_SOLICITUD = ?";
    $smtm = $conexion->prepare($sql);
    $rechazada = 3;
    $smtm->bind_param("is", $rechazada, $id_solicitud);
    return $smtm->execute();
}

function obtenerIDAndNumerosGrupos($conexion, $id_carrera)
{
    global $CAMPO_CARRERA, $CAMPO_CLAVE_GRUPO, $CAMPO_NUMERO_GRUPOS, $TABLA_GRUPO, $TABLA_CARRERAS, $CAMPO_ID_CARRERA;


    $sql = "SELECT  c.$CAMPO_CARRERA, g.$CAMPO_CLAVE_GRUPO, g.$CAMPO_NUMERO_GRUPOS FROM $TABLA_GRUPO g JOIN $TABLA_CARRERAS c ON g.$CAMPO_ID_CARRERA = c.$CAMPO_ID_CARRERA WHERE g.$CAMPO_ID_CARRERA = ? ";
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("i", $id_carrera);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerModalidades($conexion, $id_carrera)
{
    global $TABLA_CARRERA_MODALIDAD, $CAMPO_ID_CARRERA;

    $sql = "SELECT COUNT(*) as Modalidades FROM $TABLA_CARRERA_MODALIDAD WHERE $CAMPO_ID_CARRERA = ? GROUP BY $CAMPO_ID_CARRERA";

    $stmtModalidades = $conexion->prepare($sql);
    $stmtModalidades->bind_param("i", $id_carrera);
    $stmtModalidades->execute();
    return $stmtModalidades->get_result();
}

function obtenerAllCarreras($conexion)
{
    global $TABLA_CARRERAS, $CAMPO_CARRERA;
    $sql = "SELECT $CAMPO_CARRERA FROM $TABLA_CARRERAS ORDER BY $CAMPO_CARRERA";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function EliminarDatosTablaJustificante($conexion, $id_jefe)
{
    global $TABLA_JUSTIFICANTES, $CAMPO_ID_JEFE;
    $sql = "DELETE FROM $TABLA_JUSTIFICANTES WHERE $CAMPO_ID_JEFE = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $id_jefe);
    return $stmt->execute();
}

function EliminarSolicitudID($conexion, $id)
{
    global $TABLA_SOLICITUDES, $CAMPO_ID_SOLICITUD;
    $consulta = "DELETE FROM $TABLA_SOLICITUDES WHERE $CAMPO_ID_SOLICITUD = ?";
    $sql = $conexion->prepare($consulta);
    $sql->bind_param("s", $id);
    return $sql->execute();
}

function buscarPersonalBD($conexion, $query)
{
    global $TABLA_USUARIO, $CAMPO_ID_USUARIO, $CAMPO_ID_ROL, $CAMPO_NOMBRE;

    $sql = "SELECT $CAMPO_ID_USUARIO, $CAMPO_NOMBRE FROM $TABLA_USUARIO WHERE ($CAMPO_ID_USUARIO LIKE ? OR $CAMPO_NOMBRE LIKE ?) AND ($CAMPO_ID_ROL = ? OR $CAMPO_ID_ROL = ?)";

    $stmt = $conexion->prepare($sql);
    $param = "%$query%";
    $RolAdmin = 1;
    $RolJefe = 2;
    $stmt->bind_param('ssii', $param, $param, $RolAdmin, $RolJefe);

    $stmt->execute();
    return $stmt->get_result();
}

function buscarEstudianteBD($conexion, $query, $id_carrera)
{
    global $TABLA_USUARIO, $TABLA_ESTUDIANTE, $CAMPO_NOMBRE, $CAMPO_ID_CARRERA, $CAMPO_ID_USUARIO, $CAMPO_ID_ROL;

    $sql = "SELECT u.$CAMPO_ID_USUARIO, u.$CAMPO_NOMBRE FROM $TABLA_USUARIO u JOIN $TABLA_ESTUDIANTE e ON u.$CAMPO_ID_USUARIO = e.$CAMPO_ID_USUARIO WHERE (u.$CAMPO_ID_USUARIO LIKE ? OR u.$CAMPO_NOMBRE LIKE ?) AND e.$CAMPO_ID_CARRERA = ? AND u.$CAMPO_ID_ROL = ?";
    $stmt = $conexion->prepare($sql);
    $param = "%$query%";
    $id_estudiante = 3;
    $stmt->bind_param('ssii', $param, $param, $id_carrera, $id_estudiante);
    $stmt->execute();
    return $stmt->get_result();
}

function buscarJustificantes($conexion, $query)
{
    global $TABLA_JUSTIFICANTES, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $CAMPO_NOMBRE, $CAMPO_ID_ESTUDIANTE;
    if (strlen($query) > 0) {
        $sql = "SELECT j.*, u.$CAMPO_NOMBRE
                    FROM $TABLA_JUSTIFICANTES AS j
                    JOIN $TABLA_USUARIO AS u ON j.$CAMPO_ID_ESTUDIANTE = u.$CAMPO_ID_USUARIO
                    WHERE j.$CAMPO_ID_ESTUDIANTE LIKE ? OR u.$CAMPO_NOMBRE LIKE ?";

        $stmt = $conexion->prepare($sql);
        $param = "%$query%";
        $stmt->bind_param('ss', $param, $param);

    } else {
        $sql = "SELECT j.*, u.$CAMPO_NOMBRE FROM $TABLA_JUSTIFICANTES j JOIN $TABLA_USUARIO u ON j.$CAMPO_ID_ESTUDIANTE = u.$CAMPO_ID_USUARIO";
        $stmt = $conexion->prepare($sql);
    }

    $stmt->execute();
    return $stmt->get_result();
}
function insertarSolicitudBD($conexion, $matricula, $id_jefe, $motivo, $fecha, $identificador_archivo, $id_estado)
{
    global $TABLA_SOLICITUDES, $CAMPO_ID_ESTUDIANTE, $CAMPO_ID_JEFE, $CAMPO_MOTIVO, $CAMPO_FECHA_AUSE, $CAMPO_ID_ESTADO, $CAMPO_EVIDENCIA;

    $sql = "INSERT INTO $TABLA_SOLICITUDES ($CAMPO_ID_ESTUDIANTE, $CAMPO_ID_JEFE, $CAMPO_MOTIVO, $CAMPO_FECHA_AUSE,$CAMPO_ID_ESTADO, $CAMPO_EVIDENCIA ) 
    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssss", $matricula, $id_jefe, $motivo, $fecha, $id_estado, $identificador_archivo);
    return $stmt->execute();
}

function buscarHistorialJustificantesAlumno($conexion, $id)
{
    global $TABLA_TRIGGER_SOLICITUD, $CAMPO_ID_ESTUDIANTE;
    $sql = "SELECT * FROM $TABLA_TRIGGER_SOLICITUD WHERE $CAMPO_ID_ESTUDIANTE = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function insertarCarrerasDB($conexion, $carrera, $id_tipo_carrera)
{
    global $TABLA_CARRERAS, $CAMPO_CARRERA, $CAMPO_ID_TIPO_CARRERA;
    $sql = "INSERT INTO $TABLA_CARRERAS ($CAMPO_CARRERA, $CAMPO_ID_TIPO_CARRERA) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $carrera, $id_tipo_carrera);
    return $stmt->execute();
}

function insertarCarreraModalidadDB($conexion, $id_carrera, $id_modalidad)
{
    global $TABLA_CARRERA_MODALIDAD, $CAMPO_ID_CARRERA, $CAMPO_ID_MODALIDAD;
    $sql = "INSERT INTO $TABLA_CARRERA_MODALIDAD ($CAMPO_ID_CARRERA, $CAMPO_ID_MODALIDAD) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $id_carrera, $id_modalidad);
    return $stmt->execute();

}

function insertarNumeroIdGruposDB($conexion, $id_carrera, $numeros_grupos, $id_carrera_nueva)
{
    global $TABLA_GRUPO, $CAMPO_ID_CARRERA, $CAMPO_NUMERO_GRUPOS, $CAMPO_CLAVE_GRUPO;

    $sql = "INSERT INTO $TABLA_GRUPO ($CAMPO_ID_CARRERA, $CAMPO_NUMERO_GRUPOS, $CAMPO_CLAVE_GRUPO) VALUES (?, ?, ?)";
    $sql = $conexion->prepare($sql);
    $sql->bind_param("sss", $id_carrera, $numeros_grupos, $id_carrera_nueva);
    return $sql->execute();
}

function modificarNombreTipoCarreraDB($conexion, $nombreNuevo, $id_tipo_carrera_nueva, $id_carrera)
{
    global $TABLA_CARRERAS, $CAMPO_ID_CARRERA, $CAMPO_CARRERA, $CAMPO_ID_TIPO_CARRERA;

    $sql = "UPDATE $TABLA_CARRERAS SET $CAMPO_CARRERA = ?, $CAMPO_ID_TIPO_CARRERA = ? WHERE $CAMPO_ID_CARRERA = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sii", $nombreNuevo, $id_tipo_carrera_nueva, $id_carrera);
    return $stmt->execute();
}

function modificarNumeroGruposDB($conexion, $id_carrera, $numeros_grupos, $id_carrera_nueva)
{
    global $TABLA_GRUPO, $CAMPO_ID_CARRERA, $CAMPO_NUMERO_GRUPOS, $CAMPO_CLAVE_GRUPO;

    $sql = "UPDATE $TABLA_GRUPO SET $CAMPO_NUMERO_GRUPOS = ?, $CAMPO_CLAVE_GRUPO = ? WHERE $CAMPO_ID_CARRERA = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $numeros_grupos, $id_carrera_nueva, $id_carrera);
    return $stmt->execute();
}

function modificarDatosEstudianteDB($conexion, $id_usuario, $correo, $nombre, $apellidos, $grupo, $id_modalidad, $matricula)
{
    global $TABLA_USUARIO, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $CAMPO_MATRICULA, $CAMPO_CORREO, $CAMPO_NOMBRE, $CAMPO_APELLIDOS, $CAMPO_GRUPO, $CAMPO_ID_MODALIDAD;

    $sql = " UPDATE $TABLA_USUARIO SET $CAMPO_NOMBRE = ?, $CAMPO_APELLIDOS = ?, $CAMPO_CORREO = ? WHERE $CAMPO_ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $apellidos, $correo, $matricula);
    if (!$stmt->execute()) {
        return false;
    }

    $sql = " UPDATE $TABLA_ESTUDIANTE SET  $CAMPO_GRUPO = ?, $CAMPO_ID_MODALIDAD = ? WHERE $CAMPO_ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sis", $grupo, $id_modalidad, $id_usuario);

    if (!$stmt->execute()) {
        return false;
    }
    return true;
}


function modificarDatosPersonal($conexion, $clave_empleado, $nombre, $apellidos, $carrera, $carreraAntigua, $rol, $rolAntiguo, $correo)
{
    $id_rol = obtenerIDRol($conexion, $rol);

    if (!actualizarUsuario($conexion, $clave_empleado, $nombre, $apellidos, $correo, $id_rol)) {
        return "Error: Ocurrió un error al actualizar datos del usuario";
    }

    if (!manejarCambioDeRol($conexion, $clave_empleado, $rol, $rolAntiguo, $carrera, $carreraAntigua)) {
        return "Error: Ocurrió un error al modificar el rol del usuario";
    }

    return true;
}

function actualizarUsuario($conexion, $clave_empleado, $nombre, $apellidos, $correo, $id_rol)
{
    global $TABLA_USUARIO, $CAMPO_ID_USUARIO, $CAMPO_ID_ROL, $CAMPO_CORREO, $CAMPO_NOMBRE, $CAMPO_APELLIDOS;

    $sql = "UPDATE $TABLA_USUARIO SET $CAMPO_NOMBRE = ?, $CAMPO_APELLIDOS = ?, $CAMPO_CORREO = ?, $CAMPO_ID_ROL = ? WHERE $CAMPO_ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssis", $nombre, $apellidos, $correo, $id_rol, $clave_empleado);

    return $stmt->execute();
}

function manejarCambioDeRol($conexion, $clave_empleado, $rol, $rolAntiguo, $carrera, $carreraAntigua)
{
    global $TABLA_JEFE, $CAMPO_ID_USUARIO, $CAMPO_ID_CARRERA;

    if ($rol == "Administrador" && $rolAntiguo == "Jefe de Carrera") {
        $sql = "DELETE FROM $TABLA_JEFE WHERE $CAMPO_ID_USUARIO = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $clave_empleado);
        return $stmt->execute();

    } elseif ($rol == "Jefe de Carrera") {
        if ($rolAntiguo == "Administrador") {
            if (restriccionModificarAJefedeCarrera($carrera, $rol, $conexion) > 0) {
                return false;
            }
            return insertarJefedeCarrera($conexion, $clave_empleado, $carrera);

        } elseif ($rolAntiguo == "Jefe de Carrera" && $carreraAntigua != $carrera) {
            if (restriccionModificarAJefedeCarrera($carrera, $rol, $conexion) > 0) {
                return false;
            }
            $idCarreraNueva = obtenerIDCarrera($conexion, $carrera);
            $sql = "UPDATE $TABLA_JEFE SET $CAMPO_ID_CARRERA = ? WHERE $CAMPO_ID_USUARIO = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("is", $idCarreraNueva, $clave_empleado);
            return $stmt->execute();
        }
    }

    return true;
}



function obtenerSolicitudesJefeCarrera($conexion, $id_jefe)
{
    global $TABLA_SOLICITUDES, $CAMPO_ID_JEFE;
    $sql = "SELECT * FROM $TABLA_SOLICITUDES WHERE $CAMPO_ID_JEFE = ?";
    $resultado = $conexion->prepare($sql);
    $resultado->bind_param("s", $id_jefe);
    $resultado->execute();
    return $resultado->get_result();
}

function obtenerJustificantesJefeCarrera($conexion, $id_jefe)
{
    global $TABLA_JUSTIFICANTES, $CAMPO_ID_JEFE;
    $sql = "SELECT * FROM $TABLA_JUSTIFICANTES WHERE $CAMPO_ID_JEFE = ?";
    $resultado = $conexion->prepare($sql);
    $resultado->bind_param("s", $id_jefe);
    $resultado->execute();
    return $resultado->get_result();
}


function obtenerIDTipoCarrera($conexion, $tipo_carrera)
{
    global $TABLA_TIPO_CARRERA, $CAMPO_ID_TIPO_CARRERA, $CAMPO_TIPO_CARRERA;

    $sql = "SELECT $CAMPO_ID_TIPO_CARRERA FROM $TABLA_TIPO_CARRERA WHERE $CAMPO_TIPO_CARRERA = ?";
    $prepare = $conexion->prepare($sql);
    $prepare->bind_param("s", $tipo_carrera);
    $prepare->execute();
    $resultado = $prepare->get_result();
    $response = $resultado->fetch_assoc();
    return $response[$CAMPO_ID_TIPO_CARRERA];
}

function obtenerTipoCarrera($conexion, $id_tipo_carrera)
{
    global $TABLA_TIPO_CARRERA, $CAMPO_ID_TIPO_CARRERA, $CAMPO_TIPO_CARRERA;

    $sql = "SELECT $CAMPO_TIPO_CARRERA FROM $TABLA_TIPO_CARRERA WHERE $CAMPO_ID_TIPO_CARRERA = ?";
    $prepare = $conexion->prepare($sql);
    $prepare->bind_param("i", $id_tipo_carrera);
    $prepare->execute();
    $resultado = $prepare->get_result();
    $response = $resultado->fetch_assoc();
    return $response[$CAMPO_TIPO_CARRERA];
}

function obtenerModalidadesCarrera($conexion, $id_carrera)
{
    global $TABLA_CARRERA_MODALIDAD, $CAMPO_ID_CARRERA, $CAMPO_ID_MODALIDAD;

    $sql = "SELECT $CAMPO_ID_MODALIDAD FROM $TABLA_CARRERA_MODALIDAD WHERE $CAMPO_ID_CARRERA = ?";
    $prepare = $conexion->prepare($sql);
    $prepare->bind_param("i", $id_carrera);
    $prepare->execute();
    $resultado = $prepare->get_result();
    if ($resultado->num_rows == 1) {
        $row = $resultado->fetch_assoc();
        return [obtenerModalidad($conexion, $row[$CAMPO_ID_MODALIDAD])];
    } else if ($resultado->num_rows == 2) {
        $data = [];
        while ($row = $resultado->fetch_assoc()) {
            $data[] = obtenerModalidad($conexion, $row[$CAMPO_ID_MODALIDAD]);
        }
        return $data;

    }
}

function obtenerNombreEstado($conexion, $id_estado)
{
    global $CAMPO_ID_ESTADO, $TABLA_ESTADO, $CAMPO_ESTADO;

    $sql = "SELECT nombre_estado FROM $TABLA_ESTADO WHERE $CAMPO_ID_ESTADO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_estado);
    $stmt->execute();
    $result = $stmt->get_result();
    $response = $result->fetch_assoc();
    return $response[$CAMPO_ESTADO];
}

function eliminarCarreraModalidadDB($conexion, $id_carrera, $id_modalidad)
{
    global $TABLA_CARRERA_MODALIDAD, $CAMPO_ID_MODALIDAD, $CAMPO_ID_CARRERA;
    $sql = "DELETE FROM $TABLA_CARRERA_MODALIDAD WHERE $CAMPO_ID_CARRERA = ? AND $CAMPO_ID_MODALIDAD = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id_carrera, $id_modalidad);
    return $stmt->execute();
}

function obtenerNumeroJustificantesJefe($conexion, $id_jefe)
{
    global $TABLA_JUSTIFICANTES, $CAMPO_ID_JEFE;
    $sql = "SELECT COUNT(*) AS total FROM $TABLA_JUSTIFICANTES WHERE $CAMPO_ID_JEFE = ?";
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("s", $id_jefe);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $data = $resultado->fetch_assoc();

    return $data['total'];
}