<?php

function EstructuraMensaje($mensaje, $icono, $color)
{
    $_SESSION["mensaje"] = $mensaje;
    $_SESSION["icono"] = $icono;
    $_SESSION["color_mensaje"] = "var(" . $color . ")";
}

function MostrarNotificacion($notificacion)
{
    if ($notificacion) {
        $mensaje = $_SESSION["mensaje"];
        $icono = $_SESSION["icono"];
        $color = $_SESSION["color_mensaje"];
        MensajeNotificacion($mensaje, $icono, $color);
    }
}

function MensajeNotificacion($mensaje, $imagen, $color)
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



// INSERTAR DATOS
function InsertarUsuarioDB($conexion, $id_usuario, $nombre, $apellidos, $correo, $contraseña, $cargo)
{
    global $TABLA_USUARIO, $CAMPO_ID_USUARIO, $CAMPO_NOMBRE, $CAMPO_APELLIDOS, $CAMPO_CONTRASEÑA, $CAMPO_CORREO, $CAMPO_ID_ROL;

    $rol = ObtenerIDRolUsuario($conexion, $cargo);
    $consulta = "INSERT INTO $TABLA_USUARIO ($CAMPO_ID_USUARIO, $CAMPO_NOMBRE, $CAMPO_APELLIDOS, $CAMPO_CORREO, $CAMPO_CONTRASEÑA, $CAMPO_ID_ROL) VALUES (?, ?, ?, ?, ?, ?)";
    $usuario = $conexion->prepare($consulta);
    $usuario->bind_param("sssssi", $id_usuario, $nombre, $apellidos, $contraseña, $correo, $rol);
    return $usuario->execute();
}

function InsertarJefeDeCarreraDB($conexion, $identificador, $carrera)
{
    global $TABLA_JEFE, $CAMPO_ID_CARRERA, $CAMPO_ID_USUARIO;

    $IDCarrera = ObtenerIDCarrera($conexion, $carrera);
    $jefe = $conexion->prepare("INSERT INTO $TABLA_JEFE ($CAMPO_ID_USUARIO, $CAMPO_ID_CARRERA) VALUES (?, ?)");
    $jefe->bind_param("si", $identificador, $IDCarrera);

    return $jefe->execute();
}

function InsertarEstudianteDB($conexion, $matricula, $nombre, $apellidos, $id_carrera, $id_modalidad, $grupo)
{
    global $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $CAMPO_GRUPO, $CAMPO_ID_CARRERA, $CAMPO_ID_MODALIDAD;

    $sql = $conexion->prepare("INSERT INTO $TABLA_ESTUDIANTE ($CAMPO_ID_USUARIO, $CAMPO_ID_CARRERA,$CAMPO_ID_MODALIDAD, $CAMPO_GRUPO) VALUES (?, ?, ?, ?)");
    $sql->bind_param("siis", $matricula, $id_carrera, $id_modalidad, $grupo);
    return $sql->execute();

}

function InsertarTablaJustificanteDB($conexion, $id_solicitud, $id_estudiante, $id_jefe, $id_codigo, $nombre_justificante)
{
    global $TABLA_JUSTIFICANTES, $CAMPO_ID_SOLICITUD, $CAMPO_ID_ESTUDIANTE, $CAMPO_ID_JEFE, $CAMPO_ID_CODIGO, $CAMPO_NOMBRE_JUSTIFICANTE;

    $sql = "INSERT INTO $TABLA_JUSTIFICANTES ( $CAMPO_ID_SOLICITUD, $CAMPO_ID_ESTUDIANTE, $CAMPO_ID_JEFE, $CAMPO_ID_CODIGO, $CAMPO_NOMBRE_JUSTIFICANTE) VALUES (?, ?, ?, ?, ?)";
    $smtm = $conexion->prepare($sql);
    $smtm->bind_param('issss', $id_solicitud, $id_estudiante, $id_jefe, $id_codigo, $nombre_justificante);

    return $smtm->execute();
}

function InsertarCodigoQRDB($conexion, $qr_text, $url_verificacion)
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

function InsertarSolicitudDB($conexion, $matricula, $id_jefe, $motivo, $fecha, $identificador_archivo, $id_estado)
{
    global $TABLA_SOLICITUDES, $CAMPO_ID_ESTUDIANTE, $CAMPO_ID_JEFE, $CAMPO_MOTIVO, $CAMPO_FECHA_AUSE, $CAMPO_ID_ESTADO, $CAMPO_EVIDENCIA;


    $sql = "INSERT INTO $TABLA_SOLICITUDES ($CAMPO_ID_ESTUDIANTE, $CAMPO_ID_JEFE, $CAMPO_MOTIVO, $CAMPO_FECHA_AUSE,$CAMPO_ID_ESTADO, $CAMPO_EVIDENCIA ) 
    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssss", $matricula, $id_jefe, $motivo, $fecha, $id_estado, $identificador_archivo);
    return $stmt->execute();
}

function InsertarCarreraDB($conexion, $carrera, $id_tipo_carrera)
{
    global $TABLA_CARRERAS, $CAMPO_CARRERA, $CAMPO_ID_TIPO_CARRERA;
    $sql = "INSERT INTO $TABLA_CARRERAS ($CAMPO_CARRERA, $CAMPO_ID_TIPO_CARRERA) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $carrera, $id_tipo_carrera);
    return $stmt->execute();
}

function InsertarCarreraModalidadDB($conexion, $id_carrera, $id_modalidad)
{
    global $TABLA_CARRERA_MODALIDAD, $CAMPO_ID_CARRERA, $CAMPO_ID_MODALIDAD;
    $sql = "INSERT INTO $TABLA_CARRERA_MODALIDAD ($CAMPO_ID_CARRERA, $CAMPO_ID_MODALIDAD) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $id_carrera, $id_modalidad);
    return $stmt->execute();

}

function InsertarNumeroIdGruposDB($conexion, $id_carrera, $numeros_grupos, $id_carrera_nueva)
{
    global $TABLA_GRUPO, $CAMPO_ID_CARRERA, $CAMPO_NUMERO_GRUPOS, $CAMPO_CLAVE_GRUPO;

    $sql = "INSERT INTO $TABLA_GRUPO ($CAMPO_ID_CARRERA, $CAMPO_NUMERO_GRUPOS, $CAMPO_CLAVE_GRUPO) VALUES (?, ?, ?)";
    $sql = $conexion->prepare($sql);
    $sql->bind_param("sss", $id_carrera, $numeros_grupos, $id_carrera_nueva);
    return $sql->execute();
}


// MODIFICAR DATOS
function ModificarLaContraseñaActualPaginaInicioDB($conexion, $id, $contraseña_nueva)
{
    global $TABLA_USUARIO, $CAMPO_CONTRASEÑA, $CAMPO_ID_USUARIO;

    $sql = "UPDATE $TABLA_USUARIO SET $CAMPO_CONTRASEÑA = ? WHERE $CAMPO_ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $contraseña_nueva, $id);
    return $stmt->execute();
}

function ModificarEstadoSolicitudDB($conexion, $id_solicitud)
{
    global $TABLA_SOLICITUDES, $CAMPO_ID_ESTADO, $CAMPO_ID_SOLICITUD;
    $sql = "UPDATE $TABLA_SOLICITUDES SET $CAMPO_ID_ESTADO = ? WHERE $CAMPO_ID_SOLICITUD = ?";
    $id_estado = 1;
    $smtm = $conexion->prepare($sql);
    $smtm->bind_param("ii", $id_estado, $id_solicitud);
    $smtm->execute();
}

function ModificarLaValidacionCodigoQRDB($conexion, $qr_text)
{
    global $TABLA_CODIGOQR, $CAMPO_ID_ESTADO, $CAMPO_DATOS_CODIGO;
    $update = "UPDATE $TABLA_CODIGOQR SET $CAMPO_ID_ESTADO = 3 WHERE $CAMPO_DATOS_CODIGO = ?";

    $stmt_update = $conexion->prepare($update);
    $stmt_update->bind_param("s", $qr_text);
    return $stmt_update->execute();
}

function ModificarLaSolicitudARechazadoDB($conexion, $id_solicitud)
{
    global $TABLA_SOLICITUDES, $CAMPO_ID_ESTADO, $CAMPO_ID_SOLICITUD;
    $sql = "UPDATE $TABLA_SOLICITUDES SET $CAMPO_ID_ESTADO = ? WHERE $CAMPO_ID_SOLICITUD = ?";
    $smtm = $conexion->prepare($sql);
    $rechazada = 3;
    $smtm->bind_param("is", $rechazada, $id_solicitud);
    return $smtm->execute();
}

function ModificarNombreTipoCarreraDB($conexion, $nombreNuevo, $id_tipo_carrera_nueva, $id_carrera)
{
    global $TABLA_CARRERAS, $CAMPO_ID_CARRERA, $CAMPO_CARRERA, $CAMPO_ID_TIPO_CARRERA;

    $sql = "UPDATE $TABLA_CARRERAS SET $CAMPO_CARRERA = ?, $CAMPO_ID_TIPO_CARRERA = ? WHERE $CAMPO_ID_CARRERA = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sii", $nombreNuevo, $id_tipo_carrera_nueva, $id_carrera);
    return $stmt->execute();
}

function ModificarNumeroGruposDB($conexion, $id_carrera, $numeros_grupos, $id_carrera_nueva)
{
    global $TABLA_GRUPO, $CAMPO_ID_CARRERA, $CAMPO_NUMERO_GRUPOS, $CAMPO_CLAVE_GRUPO;

    $sql = "UPDATE $TABLA_GRUPO SET $CAMPO_NUMERO_GRUPOS = ?, $CAMPO_CLAVE_GRUPO = ? WHERE $CAMPO_ID_CARRERA = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $numeros_grupos, $id_carrera_nueva, $id_carrera);
    return $stmt->execute();
}

function ModificarDatosEstudianteDB($conexion, $id_usuario, $correo, $nombre, $apellidos, $grupo, $id_modalidad, $matricula)
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

function ModificarDatosPersonalDB($conexion, $clave_empleado, $nombre, $apellidos, $carrera, $carreraAntigua, $rol, $rolAntiguo, $correo)
{
    $id_rol = ObtenerIDRolUsuario($conexion, $rol);

    if (!ModificarUsuarioDB($conexion, $clave_empleado, $nombre, $apellidos, $correo, $id_rol)) {
        return "Error: Ocurrió un error al actualizar datos del usuario";
    }

    $mensaje = ManejoDeModificacionCambioDeRol($conexion, $clave_empleado, $rol, $rolAntiguo, $carrera, $carreraAntigua);
    if ($mensaje !== true) {
        return $mensaje;
    }

    return true;
}

function ModificarUsuarioDB($conexion, $clave_empleado, $nombre, $apellidos, $correo, $id_rol)
{
    global $TABLA_USUARIO, $CAMPO_ID_USUARIO, $CAMPO_ID_ROL, $CAMPO_CORREO, $CAMPO_NOMBRE, $CAMPO_APELLIDOS;

    $sql = "UPDATE $TABLA_USUARIO SET $CAMPO_NOMBRE = ?, $CAMPO_APELLIDOS = ?, $CAMPO_CORREO = ?, $CAMPO_ID_ROL = ? WHERE $CAMPO_ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssis", $nombre, $apellidos, $correo, $id_rol, $clave_empleado);

    return $stmt->execute();
}

function ManejoDeModificacionCambioDeRol($conexion, $clave_empleado, $rol, $rolAntiguo, $carrera, $carreraAntigua)
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
                return "Esa carrera ya tiene un jefe de carrera vinculado";
            }
            return InsertarJefeDeCarreraDB($conexion, $clave_empleado, $carrera);

        } elseif ($rolAntiguo == "Jefe de Carrera" && $carreraAntigua != $carrera) {
            if (restriccionModificarAJefedeCarrera($carrera, $rol, $conexion) > 0) {
                return "Esa carrera ya tiene un jefe de carrera vinculado";
            }
            $idCarreraNueva = ObtenerIDCarrera($conexion, $carrera);
            $sql = "UPDATE $TABLA_JEFE SET $CAMPO_ID_CARRERA = ? WHERE $CAMPO_ID_USUARIO = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("is", $idCarreraNueva, $clave_empleado);
            return $stmt->execute();
        }
    }

    return true;
}

// ELIMINAR DATOS
function EliminarUsuarioDB($conexion, $id)
{

    global $TABLA_USUARIO, $CAMPO_ID_USUARIO;
    $sql = "DELETE FROM $TABLA_USUARIO WHERE $CAMPO_ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $id);
    return $stmt->execute();

}

function EliminarCarreraDB($conexion, $carrera)
{
    global $TABLA_CARRERAS, $CAMPO_ID_CARRERA;

    $id_carrera = ObtenerIDCarrera($conexion, $carrera);
    $consulta = "DELETE FROM $TABLA_CARRERAS WHERE $CAMPO_ID_CARRERA = ?";
    $sql = $conexion->prepare($consulta);
    $sql->bind_param("s", $id_carrera);
    return $sql->execute();
}

function EliminarDatosTablaJustificanteDB($conexion, $id_jefe, $carrera)
{
    global $TABLA_SOLICITUDES, $TABLA_TRIGGER_SOLICITUD, $CAMPO_ID_JEFE;
    $sql = "DELETE FROM $TABLA_SOLICITUDES WHERE $CAMPO_ID_JEFE = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $id_jefe);
    if (!$stmt->execute()) {
        return "Ocurrio un error al eliminar los justificantes de la BD";

    }

    $sql = "DELETE FROM $TABLA_TRIGGER_SOLICITUD WHERE $CAMPO_ID_JEFE = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $id_jefe);
    if (!$stmt->execute()) {
        return "Ocurrio un error al eliminar los justificantes de la BD";
    }
    $rutaEvidencia = "../layouts/Alumno/evidencias/$carrera";
    $rutaJustificantes = "../layouts/Alumno/justificantes/$carrera";
    $rutaPapelera = "../layouts/Alumno/papelera/$carrera";

    $mensajeEvidencia = EliminarArchivosConRuta($rutaEvidencia);
    if ($mensajeEvidencia != 1) {
        return $mensajeEvidencia;
    }

    $mensajeJustificantes = EliminarArchivosConRuta($rutaJustificantes);
    if ($mensajeJustificantes != 1) {
        return $mensajeJustificantes;
    }

    $mensajePapelera = EliminarArchivosConRuta($rutaPapelera);
    if ($mensajePapelera != 1) {
        return $mensajePapelera;
    }





    return true;
}

function EliminarArchivosConRuta($ruta)
{

    // Verificar si la ruta existe y es válida
    if (!file_exists($ruta)) {
        return "La ruta no existe.";
    }
    if (!is_dir($ruta)) {
        return "No es una ruta válida.";
    }
    if (!is_writable($ruta)) {
        return "La ruta no tiene permisos de escritura.";
    }

    // Abrir el directorio
    $archivos = scandir($ruta);

    foreach ( $archivos as $archivo ) {
        // Omitir los directorios . y ..
        if ($archivo === '.' || $archivo === '..') {
            continue;
        }

        $rutaCompleta = $ruta . DIRECTORY_SEPARATOR . $archivo;

        // Eliminar si es un archivo
        if (is_file($rutaCompleta)) {
            if (!unlink($rutaCompleta)) {
                return "Error al eliminar: $rutaCompleta";
            }
        }

    }
    return true;

}

function EliminarSolicitudPorIDDB($conexion, $id)
{
    global $TABLA_SOLICITUDES, $CAMPO_ID_SOLICITUD;
    $consulta = "DELETE FROM $TABLA_SOLICITUDES WHERE $CAMPO_ID_SOLICITUD = ?";
    $sql = $conexion->prepare($consulta);
    $sql->bind_param("s", $id);
    return $sql->execute();
}

function EliminarCarreraModalidadDB($conexion, $id_carrera, $id_modalidad)
{
    global $TABLA_CARRERA_MODALIDAD, $CAMPO_ID_MODALIDAD, $CAMPO_ID_CARRERA;
    $sql = "DELETE FROM $TABLA_CARRERA_MODALIDAD WHERE $CAMPO_ID_CARRERA = ? AND $CAMPO_ID_MODALIDAD = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id_carrera, $id_modalidad);
    return $stmt->execute();
}


// OBTENER DATOS
function ObtenerDatosColumnaTabla($conexion, $columna, $tabla)
{
    $sql = $conexion->prepare("SELECT $columna FROM " . $tabla);
    $sql->execute();
    return $sql->get_result();
}

function ObtenerDatosDeUnaTabla($conexion, $tabla, $columna, $campo)
{
    $sql = $conexion->prepare("SELECT * FROM $tabla WHERE $columna = ?");
    $sql->bind_param("s", $campo);
    $sql->execute();
    $result = $sql->get_result();
    return $result->fetch_assoc();
}

function ObtenerIDRolUsuario($conexion, $rol)
{
    global $TABLA_ROL, $CAMPO_ID_ROL, $CAMPO_ROL;

    $sql = $conexion->prepare("SELECT $CAMPO_ID_ROL FROM $TABLA_ROL WHERE $CAMPO_ROL = ?");
    $sql->bind_param("s", $rol);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[$CAMPO_ID_ROL];
}

function ObtenerRolUsuario($conexion, $id_rol)
{
    global $TABLA_ROL, $CAMPO_ID_ROL, $CAMPO_ROL;
    $TABLA_ROL = 'rol';
    $CAMPO_ID_ROL = 'id_rol';
    $CAMPO_ROL = 'nombre_rol';

    $sql = $conexion->prepare("SELECT $CAMPO_ROL FROM $TABLA_ROL WHERE $CAMPO_ID_ROL = ?");
    $sql->bind_param("s", $id_rol);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[$CAMPO_ROL];
}

function ObtenerIDCarrera($conexion, $carrera)
{
    global $TABLA_CARRERAS, $CAMPO_ID_CARRERA, $CAMPO_CARRERA;

    $sql = $conexion->prepare("SELECT $CAMPO_ID_CARRERA FROM $TABLA_CARRERAS WHERE $CAMPO_CARRERA = ?");
    $sql->bind_param("s", $carrera);
    $sql->execute();
    $resultado = $sql->get_result();
    $response = $resultado->fetch_assoc();

    return $response[$CAMPO_ID_CARRERA];
}

function ObtenerNombreCarrera($conexion, $id_carrera)
{
    global $TABLA_CARRERAS, $CAMPO_ID_CARRERA, $CAMPO_CARRERA;

    $sqlIdCarrera = $conexion->prepare("SELECT $CAMPO_CARRERA FROM $TABLA_CARRERAS WHERE $CAMPO_ID_CARRERA = ?");
    $sqlIdCarrera->bind_param("s", $id_carrera);
    $sqlIdCarrera->execute();
    $result = $sqlIdCarrera->get_result();
    $carrera = $result->fetch_assoc();

    return $carrera[$CAMPO_CARRERA];
}

function ObtenerIdModalidad($conexion, $modalidad)
{
    global $TABLA_MODALIDADES, $CAMPO_ID_MODALIDAD, $CAMPO_MODALIDAD;

    $sql = $conexion->prepare("SELECT $CAMPO_ID_MODALIDAD FROM $TABLA_MODALIDADES WHERE $CAMPO_MODALIDAD = ?");
    $sql->bind_param("s", $modalidad);
    $sql->execute();
    $result = $sql->get_result();
    $response = $result->fetch_assoc();
    return $response[$CAMPO_ID_MODALIDAD];
}

function ObtenerModalidad($conexion, $id_modalidad)
{
    global $TABLA_MODALIDADES, $CAMPO_ID_MODALIDAD, $CAMPO_MODALIDAD;

    $sql = $conexion->prepare("SELECT $CAMPO_MODALIDAD FROM $TABLA_MODALIDADES WHERE $CAMPO_ID_MODALIDAD = ?");
    $sql->bind_param("s", $id_modalidad);
    $sql->execute();
    $result = $sql->get_result();
    $response = $result->fetch_assoc();

    return $response[$CAMPO_MODALIDAD];
}

function ObtenerContraseñaActualdb($conexion, $id)
{
    global $TABLA_USUARIO, $CAMPO_CONTRASEÑA, $CAMPO_ID_USUARIO;

    $sqlComprobacionContraseña = "SELECT $CAMPO_CONTRASEÑA FROM $TABLA_USUARIO WHERE $CAMPO_ID_USUARIO= ?";
    $stmt = $conexion->prepare($sqlComprobacionContraseña);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $resultContraseña = $stmt->get_result()->fetch_assoc();
    return $resultContraseña[$CAMPO_CONTRASEÑA];
}

function ObtenerCodigoQRVerificacion($conexion, $qr_text)
{
    global $TABLA_CODIGOQR, $CAMPO_ID_ESTADO, $CAMPO_DATOS_CODIGO;

    $sql = "SELECT $CAMPO_ID_ESTADO FROM $TABLA_CODIGOQR WHERE $CAMPO_DATOS_CODIGO = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $qr_text);
    $stmt->execute();
    return $stmt->get_result();
}

function ObtenerIDYNumerosGrupos($conexion, $id_carrera)
{
    global $CAMPO_CARRERA, $CAMPO_CLAVE_GRUPO, $CAMPO_NUMERO_GRUPOS, $TABLA_GRUPO, $TABLA_CARRERAS, $CAMPO_ID_CARRERA;


    $sql = "SELECT  c.$CAMPO_CARRERA, g.$CAMPO_CLAVE_GRUPO, g.$CAMPO_NUMERO_GRUPOS FROM $TABLA_GRUPO g JOIN $TABLA_CARRERAS c ON g.$CAMPO_ID_CARRERA = c.$CAMPO_ID_CARRERA WHERE g.$CAMPO_ID_CARRERA = ? ";
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("i", $id_carrera);
    $stmt->execute();
    return $stmt->get_result();
}

function ObtenerModalidadesDeUnaCarrera($conexion, $id_carrera)
{
    global $TABLA_CARRERA_MODALIDAD, $CAMPO_ID_CARRERA;

    $sql = "SELECT COUNT(*) as Modalidades FROM $TABLA_CARRERA_MODALIDAD WHERE $CAMPO_ID_CARRERA = ? GROUP BY $CAMPO_ID_CARRERA";

    $stmtModalidades = $conexion->prepare($sql);
    $stmtModalidades->bind_param("i", $id_carrera);
    $stmtModalidades->execute();
    return $stmtModalidades->get_result();
}

function ObtenerTodasLasCarreras($conexion)
{
    global $TABLA_CARRERAS, $CAMPO_CARRERA;
    $sql = "SELECT $CAMPO_CARRERA FROM $TABLA_CARRERAS ORDER BY $CAMPO_CARRERA";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function ObtenerSolicitudesDelJefeCarrera($conexion, $id_jefe)
{
    global $TABLA_SOLICITUDES, $CAMPO_ID_JEFE;
    $sql = "SELECT * FROM $TABLA_SOLICITUDES WHERE $CAMPO_ID_JEFE = ?";
    $resultado = $conexion->prepare($sql);
    $resultado->bind_param("s", $id_jefe);
    $resultado->execute();
    return $resultado->get_result();
}

function ObtenerJustificantesJefeCarrera($conexion, $id_jefe)
{
    global $TABLA_JUSTIFICANTES, $CAMPO_ID_JEFE;
    $sql = "SELECT * FROM $TABLA_JUSTIFICANTES WHERE $CAMPO_ID_JEFE = ?";
    $resultado = $conexion->prepare($sql);
    $resultado->bind_param("s", $id_jefe);
    $resultado->execute();
    return $resultado->get_result();
}

function ObtenerIDTipoCarrera($conexion, $tipo_carrera)
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

function ObtenerNombreTipoCarrera($conexion, $id_tipo_carrera)
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

function ObtenerIdModalidadesCarrera($conexion, $id_carrera)
{
    global $TABLA_CARRERA_MODALIDAD, $CAMPO_ID_CARRERA, $CAMPO_ID_MODALIDAD;

    $sql = "SELECT $CAMPO_ID_MODALIDAD FROM $TABLA_CARRERA_MODALIDAD WHERE $CAMPO_ID_CARRERA = ?";
    $prepare = $conexion->prepare($sql);
    $prepare->bind_param("i", $id_carrera);
    $prepare->execute();
    $resultado = $prepare->get_result();
    if ($resultado->num_rows == 1) {
        $row = $resultado->fetch_assoc();
        return [ObtenerModalidad($conexion, $row[$CAMPO_ID_MODALIDAD])];
    } else if ($resultado->num_rows == 2) {
        $data = [];
        while ($row = $resultado->fetch_assoc()) {
            $data[] = ObtenerModalidad($conexion, $row[$CAMPO_ID_MODALIDAD]);
        }
        return $data;

    }
}

function ObtenerNombreEstado($conexion, $id_estado)
{
    global $CAMPO_ID_ESTADO, $TABLA_ESTADO, $CAMPO_ESTADO;

    $sql = "SELECT $CAMPO_ESTADO FROM $TABLA_ESTADO WHERE $CAMPO_ID_ESTADO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_estado);
    $stmt->execute();
    $result = $stmt->get_result();
    $response = $result->fetch_assoc();
    return $response[$CAMPO_ESTADO];
}

function ObtenerNumeroJustificantesJefeCarrera($conexion, $id_jefe)
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



// BUSQQUEDAS
function BuscarPersonalBD($conexion, $query)
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

function BuscarEstudianteBD($conexion, $query, $id_carrera)
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

function BuscarJustificantes($conexion, $query)
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

function BuscarHistorialJustificantesAlumno($conexion, $id)
{
    global $TABLA_TRIGGER_SOLICITUD, $CAMPO_ID_ESTUDIANTE;
    $sql = "SELECT * FROM $TABLA_TRIGGER_SOLICITUD WHERE $CAMPO_ID_ESTUDIANTE = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    return $stmt->get_result();
}


// VALIDACION A LA HORA DE MODIFICAR USUARIOS
function ExisteIdUsuarioDuplicado($conexion, $id)
{
    global $TABLA_USUARIO;
    global $CAMPO_ID_USUARIO;

    $sql = $conexion->prepare("SELECT * FROM $TABLA_USUARIO WHERE $CAMPO_ID_USUARIO = ?");
    $sql->bind_param("s", $id);
    $sql->execute();
    return $sql->get_result();
}

function ExisteCorreoDuplicado($conexion, $correo)
{
    global $TABLA_USUARIO, $CAMPO_CORREO;

    $sql = $conexion->prepare("SELECT * FROM $TABLA_USUARIO WHERE $CAMPO_CORREO = ?");
    $sql->bind_param("s", $correo);
    $sql->execute();
    return $sql->get_result();
}

function ExisteCarreraDuplicada($tabla, $conexion, $id_carrera)
{
    global $CAMPO_ID_CARRERA;
    $sqlCarreraDuplicada = $conexion->prepare("SELECT * FROM $tabla WHERE $CAMPO_ID_CARRERA = ?");
    $sqlCarreraDuplicada->bind_param("i", $id_carrera);
    $sqlCarreraDuplicada->execute();
    return $sqlCarreraDuplicada->get_result();
}

// CAMBIAR CONTRASEÑA
function verificarIdentidadCorreoIdentificador($id_usuario, $correoDB, $conexion)
{
    global $TABLA_USUARIO, $CAMPO_CORREO, $CAMPO_ID_USUARIO;
    $sql = "SELECT * FROM $TABLA_USUARIO WHERE $CAMPO_CORREO = ? AND $CAMPO_ID_USUARIO = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ss', $correoDB, $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    return $resultado;
}

function cambiarContraseñaEnBD($conexion, $id_usuario, $nuevaContraseña)
{
    global $TABLA_USUARIO, $CAMPO_CONTRASEÑA, $CAMPO_ID_USUARIO;
    $sql = "UPDATE $TABLA_USUARIO SET $CAMPO_CONTRASEÑA = ? WHERE $CAMPO_ID_USUARIO= ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ss', $nuevaContraseña, $id_usuario);

    return $stmt->execute();

}