<?php
$TABLA_USUARIO = Variables::TABLA_BD_USUARIO;
$TABLA_ADMIN = Variables::TABLA_BD_AdMINISTRADOR;
$TABLA_JEFE = Variables::TABLA_BD_JEFE;
$TABLA_ESTUDIANTE = Variables::TABLA_BD_ESTUDIANTE;
$TABLA_CARRERAS = Variables::TABLA_BD_CARRERA;
$TABLA_ROL = Variables::TABLA_BD_ROL;
$TABLA_MODALIDADES = Variables::tABLA_BD_MODALIDAD;
$TABLA_GRUPO = Variables::TABLA_BD_GRUPO;
$TABLA_SOLICITUDES = Variables::TABLA_BD_SOLICITUDES;
$TABLA_JUSTIFICANTES = Variables::TABLA_BD_JUSTIFICANTES;
$TABLA_CODIGOQR = Variables::TABLA_BD_CODIGOS_QR;
$TABLA_CARRERA_MODALIDAD = Variables::TABLA_BD_CARRERA_MODALIDAD;

$CAMPO_ID_USUARIO = Variables::CAMPO_ID_USUARIO;
$CAMPO_CONTRASEÑA = Variables::CAMPO_CONTRASEÑA;
$CAMPO_CORREO = Variables::CAMPO_CORREO;

$CAMPO_CLAVE_EMPLEADO_ADMIN = Variables::CAMPO_CLAVE_EMPLEADO_ADMIN;
$CAMPO_CLAVE_EMPLEADO_JEFE = Variables::CAMPO_CLAVE_EMPLEADO_JEFE;
$CAMPO_MATRICULA = VARIABLES::CAMPO_MATRICULA;

$CAMPO_NOMBRE = Variables::CAMPO_NOMBRE;
$CAMPO_APELLIDOS = Variables::CAMPO_APELLIDOS;

$CAMPO_ID_CARRERA = Variables::CAMPO_ID_CARRERA;
$CAMPO_CARRERA = Variables::CAMPO_CARRERA;
$CAMPO_CORREO = Variables::CAMPO_CORREO;

$CAMPO_ID_ROL = Variables::CAMPO_ID_ROL;
$CAMPO_ROL = Variables::CAMPO_ROL;

$CAMPO_ID_MODALIDAD = Variables::CAMPO_ID_MODALIDAD;
$CAMPO_MODALIDAD = Variables::CAMPO_MODALIDAD;
$CAMPO_GRUPO = Variables::CAMPO_GRUPO;

$CAMPO_ID_GRUPOS = Variables::CAMPO_G_ID_GRUPO;
$CAMPO_NUMERO_GRUPOS = Variables::CAMPO_G_NUMERO_GRUPOS;

$CAMPO_ESTADO = Variables::CAMPO_S_ESTADO;
$CAMPO_ID_SOLICITUD = Variables::CAMPO_S_ID_SOLICITUD;
$CAMPO_MOTIVO = Variables::CAMPO_S_MOTIVO;
$CAMPO_FECHA_AUSE = Variables::CAMPO_S_FECHA_AUSENCIA;

$CAMPO_J_ID_JUSTIFICANTE = Variables::CAMPO_J_ID;
$CAMPO_J_ID_SOLICITUD = Variables::CAMPO_J_ID_SOLICITUD;
$CAMPO_J_MATRICULA = Variables::CAMPO_J_MATRICULA;
$CAMPO_J_NOMBRE = Variables::CAMPO_J_NOMBRE;
$CAMPO_J_APELLIDOS = Variables::CAMPO_J_APELLIDOS;
$CAMPO_J_MOTIVO = Variables::CAMPO_J_MOTIVO;
$CAMPO_J_GRUPO = Variables::CAMPO_J_GRUPO;
$CAMPO_J_CARRERA = Variables::CAMPO_J_CARRERA;
$CAMPO_J_NOMBRE_JEFE = Variables::CAMPO_J_NOMBRE_JEFE;
$CAMPO_J_JUSTIFICANTE = Variables::CAMPO_J_JUSTIFICANTE;
$CAMPO_J_FECHA_CREACION = Variables::CAMPO_J_FECHA;


$CAMPO_FOLIO_QR = Variables::CAMPO_Q_FOLIO_JUSTIFICANTE;
$CAMPO_TEXTO_QR = Variables::CAMPO_Q_TEXTO;
$CAMPO_VALIDO_QR = Variables::CAMPO_Q_VALIDO;
$CAMPO_URL_QR = Variables::CAMPO_Q_URL_VERIFICACION;



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
    $sql = $conexion->prepare("SELECT * FROM " . $tabla . " WHERE " . $columna . " = ?");
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
    global $TABLA_USUARIO;
    global $CAMPO_CORREO;

    $sql = $conexion->prepare("SELECT * FROM $TABLA_USUARIO WHERE $CAMPO_CORREO = ?");
    $sql->bind_param("s", $correo);
    $sql->execute();
    return $sql->get_result();
}

// CONSULTA PARA OBTENER EL NOMBRE DE ÑA CARRERA DE LA TABLA CARRERA
function getResultCarrera($conexion, $id_carrera)
{
    global $TABLA_CARRERAS;
    global $CAMPO_ID_CARRERA;
    global $CAMPO_CARRERA;

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
    $sqlCarreraDuplicada = $conexion->prepare("SELECT * FROM " . $tabla . " WHERE " . Variables::CAMPO_ID_CARRERA . " = ?");
    $sqlCarreraDuplicada->bind_param("i", $id_carrera);
    $sqlCarreraDuplicada->execute();
    return $sqlCarreraDuplicada->get_result();
}

// CONSULTA PARA OBTENER EL ID DE ROL
function obtenerIDRol($conexion, $rol)
{
    global $TABLA_ROL;
    global $CAMPO_ID_ROL;
    global $CAMPO_ROL;

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
    global $TABLA_ROL;
    global $CAMPO_ID_ROL;
    global $CAMPO_ROL;

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
    global $TABLA_CARRERAS;
    global $CAMPO_ID_CARRERA;
    global $CAMPO_CARRERA;

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
    global $TABLA_MODALIDADES;
    global $CAMPO_ID_MODALIDAD;
    global $CAMPO_MODALIDAD;

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
    global $TABLA_MODALIDADES;
    global $CAMPO_ID_MODALIDAD;
    global $CAMPO_MODALIDAD;

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
    global $TABLA_USUARIO;
    global $CAMPO_CONTRASEÑA;
    global $CAMPO_ID_USUARIO;

    $sqlComprobacionContraseña = "SELECT $CAMPO_CONTRASEÑA FROM $TABLA_USUARIO WHERE $CAMPO_ID_USUARIO= ?";
    $stmt = $conexion->prepare($sqlComprobacionContraseña);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $resultContraseña = $stmt->get_result()->fetch_assoc();
    return $resultContraseña[$CAMPO_CONTRASEÑA];
}

//CONSULTAS PARA INSERTAR DATOS EN LA TABLA USUARIO
function insertarUsuario($conexion, $id_usuario, $contraseña, $correo, $cargo)
{
    global $TABLA_USUARIO;
    global $CAMPO_ID_USUARIO;
    global $CAMPO_CONTRASEÑA;
    global $CAMPO_CORREO;
    global $CAMPO_ID_ROL;

    $rol = obtenerIDRol($conexion, $cargo);
    $consulta = "INSERT INTO $TABLA_USUARIO ($CAMPO_ID_USUARIO, $CAMPO_CONTRASEÑA, $CAMPO_CORREO, $CAMPO_ID_ROL) VALUES (?, ?, ?, ?)";

    $usuario = $conexion->prepare($consulta);
    $usuario->bind_param("sssi", $id_usuario, $contraseña, $correo, $rol);
    return $usuario->execute();
}

//CONSULTA PARA INSERTAR DATOS EN LA TABLA ADMINISTRADOR
function insertarAdministrador($conexion, $identificador, $nombre, $apellidos)
{
    global $TABLA_ADMIN;
    global $CAMPO_CLAVE_EMPLEADO_ADMIN;
    global $CAMPO_NOMBRE;
    global $CAMPO_APELLIDOS;
    $stmt = $conexion->prepare("INSERT INTO $TABLA_ADMIN ($CAMPO_CLAVE_EMPLEADO_ADMIN, $CAMPO_NOMBRE, $CAMPO_APELLIDOS) VALUES (?, ?, ?)");

    $stmt->bind_param("sss", $identificador, $nombre, $apellidos);
    return $stmt->execute();
}

// CONSULTA PARA INSERTAR DATOS EN LA TABLA JEFE
function insertarJefedeCarrera($conexion, $identificador, $nombre, $apellidos, $carrera)
{
    global $TABLA_JEFE;
    global $CAMPO_CLAVE_EMPLEADO_JEFE;
    global $CAMPO_NOMBRE;
    global $CAMPO_APELLIDOS;
    global $CAMPO_ID_CARRERA;

    $IDCarrera = obtenerIDCarrera($conexion, $carrera);

    $jefe = $conexion->prepare("INSERT INTO $TABLA_JEFE ($CAMPO_CLAVE_EMPLEADO_JEFE, $CAMPO_NOMBRE, $CAMPO_APELLIDOS, $CAMPO_ID_CARRERA) VALUES (?, ?, ?, ?)");
    $jefe->bind_param("sssi", $identificador, $nombre, $apellidos, $IDCarrera);

    return $jefe->execute();
}

//CONSULTA PARA INSERTAR DATOS EN LA TABLA ESTUDIANTE
function insertarEstudiante($conexion, $matricula, $nombre, $apellidos, $id_carrera, $id_modalidad, $grupo)
{
    global $TABLA_ESTUDIANTE;
    global $CAMPO_MATRICULA;
    global $CAMPO_NOMBRE;
    global $CAMPO_APELLIDOS;
    global $CAMPO_GRUPO;
    global $CAMPO_ID_CARRERA;
    global $CAMPO_ID_MODALIDAD;

    $sql = $conexion->prepare("INSERT INTO $TABLA_ESTUDIANTE ($CAMPO_MATRICULA, $CAMPO_NOMBRE , $CAMPO_APELLIDOS, $CAMPO_GRUPO, $CAMPO_ID_CARRERA, $CAMPO_ID_MODALIDAD) VALUES (?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssssii", $matricula, $nombre, $apellidos, $grupo, $id_carrera, $id_modalidad);
    return $sql->execute();

}

// CONSULTA OARA MODIFICAR LA CONTRASEÑA ACTUAL DESDE LA PAGINA INICIO (DENTRO DEL SISTEMA)
function modificarLaContraseñaActualPaginaInicio($conexion, $id, $contraseña_nueva)
{
    global $TABLA_USUARIO;
    global $CAMPO_CONTRASEÑA;
    global $CAMPO_ID_USUARIO;

    $sql = "UPDATE $TABLA_USUARIO SET $CAMPO_CONTRASEÑA = ? WHERE $CAMPO_ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $contraseña_nueva, $id);
    return $stmt->execute();
}

// CONSULTA PARA ELIMINAR DATOS DE LA TABLA USUARIO
function EliminarUsuario($conexion, $id)
{
    global $TABLA_USUARIO;
    global $CAMPO_ID_USUARIO;
    if ($id) {
        $sql = "DELETE FROM $TABLA_USUARIO WHERE $CAMPO_ID_USUARIO = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $id);
        return $stmt->execute();
    } else {
        return false;
    }

}






function EliminarCarrera($conexion, $carreraNueva)
{
    global $TABLA_CARRERAS;
    global $CAMPO_CARRERA;

    $consulta = "DELETE FROM $TABLA_CARRERAS WHERE $CAMPO_CARRERA = ?";
    $sql = $conexion->prepare($consulta);
    $sql->bind_param("s", $carreraNueva);
    return $sql->execute();
}

function ModificarEstadoSolicitud($conexion, $id_solicitud)
{
    global $TABLA_SOLICITUDES;
    global $CAMPO_ESTADO;
    global $CAMPO_ID_SOLICITUD;
    $sql = "UPDATE $TABLA_SOLICITUDES SET $CAMPO_ESTADO = 'Aceptada' WHERE $CAMPO_ID_SOLICITUD = ?";

    $smtm = $conexion->prepare($sql);
    $smtm->bind_param("i", $id_solicitud);
    $smtm->execute();
}

function obtenerNumeroFolio($conexion)
{
    global $TABLA_JUSTIFICANTES;
    $sql = "SELECT COUNT(*) AS total FROM $TABLA_JUSTIFICANTES";
    $result = $conexion->query($sql);
    $row = $result->fetch_assoc();
    return $row["total"];
}

function InsertarTablaJustificante($conexion, $id_solicitud, $matricula, $nombre, $apellidos, $motivo, $grupo, $carrera, $nombre_jefe, $apellidos_jefe, $nombreArchivo)
{
    global $TABLA_JUSTIFICANTES;
    global $CAMPO_J_ID_SOLICITUD;
    global $CAMPO_J_MATRICULA;
    global $CAMPO_J_NOMBRE;
    global $CAMPO_J_APELLIDOS;
    global $CAMPO_J_MOTIVO;
    global $CAMPO_J_GRUPO;
    global $CAMPO_J_CARRERA;
    global $CAMPO_J_NOMBRE_JEFE;
    global $CAMPO_J_JUSTIFICANTE;
    $sql = "INSERT INTO $TABLA_JUSTIFICANTES ( $CAMPO_J_ID_SOLICITUD, $CAMPO_J_MATRICULA, $CAMPO_J_NOMBRE, $CAMPO_J_APELLIDOS, $CAMPO_J_MOTIVO, $CAMPO_J_GRUPO, $CAMPO_J_CARRERA, $CAMPO_J_NOMBRE_JEFE, $CAMPO_J_JUSTIFICANTE) VALUES (?,?,?,?,?,?,?,?,?)";

    $nombre_jefe_completo = "$nombre_jefe  $apellidos_jefe";

    $smtm = $conexion->prepare($sql);
    $smtm->bind_param('sssssssss', $id_solicitud, $matricula, $nombre, $apellidos, $motivo, $grupo, $carrera, $nombre_jefe_completo, $nombreArchivo);

    return $smtm->execute();
}

function insertarCodigoQR($conexion, $id, $qr_text, $valido, $url_verificacion)
{

    global $TABLA_CODIGOQR;
    global $CAMPO_FOLIO_QR;
    global $CAMPO_TEXTO_QR;
    global $CAMPO_VALIDO_QR;
    global $CAMPO_URL_QR;
    $sql = "INSERT INTO $TABLA_CODIGOQR ($CAMPO_FOLIO_QR, $CAMPO_TEXTO_QR, $CAMPO_VALIDO_QR, $CAMPO_URL_QR) VALUES (?, ?, ?, ?)";

    $smtm = $conexion->prepare($sql);
    $valido = 1;
    $smtm->bind_param("isss", $id, $qr_text, $valido, $url_verificacion);
    return $smtm->execute();
}

function obtenerCodigoQVerificacion($conexion, $qr_text)
{
    global $TABLA_CODIGOQR;
    global $CAMPO_VALIDO_QR;
    global $CAMPO_TEXTO_QR;

    $sql = "SELECT $CAMPO_VALIDO_QR FROM $TABLA_CODIGOQR WHERE $CAMPO_TEXTO_QR = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $qr_text);
    $stmt->execute();
    return $stmt->get_result();
}

function actualizarValidacionCodigoQR($conexion, $qr_text)
{
    // Actualizar a 2 para marcarlo como ya escaneado
    $update = "UPDATE " . Variables::TABLA_BD_CODIGOS_QR . " SET " . Variables::CAMPO_Q_VALIDO . " = 2 WHERE " . Variables::CAMPO_Q_TEXTO . " = ?";

    $stmt_update = $conexion->prepare($update);
    $stmt_update->bind_param("s", $qr_text);
    return $stmt_update->execute();
}

function modificarSolicitudRechazado($conexion, $id_solicitud)
{
    global $TABLA_SOLICITUDES;
    global $CAMPO_ESTADO;
    global $CAMPO_ID_SOLICITUD;
    $sql = "UPDATE $TABLA_SOLICITUDES SET $CAMPO_ESTADO = 'Rechazada' WHERE $CAMPO_ID_SOLICITUD = ?";


    $smtm = $conexion->prepare($sql);
    $smtm->bind_param("s", $id_solicitud);
    return $smtm->execute();
}

function obtenerIDAndNumerosGrupos($conexion, $id_carrera)
{
    global $CAMPO_CARRERA;
    global $CAMPO_ID_GRUPOS;
    global $CAMPO_NUMERO_GRUPOS;
    global $TABLA_GRUPO;
    global $TABLA_CARRERAS;
    global $CAMPO_ID_CARRERA;


    $sql = "SELECT  c.$CAMPO_CARRERA, g.$CAMPO_ID_GRUPOS, g.$CAMPO_NUMERO_GRUPOS FROM $TABLA_GRUPO g JOIN $TABLA_CARRERAS c ON g.$CAMPO_ID_CARRERA = c.$CAMPO_ID_CARRERA WHERE g.$CAMPO_ID_CARRERA = ? ";
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("i", $id_carrera);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerModalidades($conexion, $id_carrera)
{
    global $TABLA_CARRERA_MODALIDAD;
    global $CAMPO_ID_CARRERA;

    $sql = "SELECT COUNT(*) as Modalidades FROM $TABLA_CARRERA_MODALIDAD WHERE $CAMPO_ID_CARRERA = ? GROUP BY $CAMPO_ID_CARRERA";

    $stmtModalidades = $conexion->prepare($sql);
    $stmtModalidades->bind_param("i", $id_carrera);
    $stmtModalidades->execute();
    return $stmtModalidades->get_result();
}

function obtenerAllCarreras($conexion)
{
    global $TABLA_CARRERAS;
    global $CAMPO_CARRERA;
    $sql = "SELECT $CAMPO_CARRERA FROM $TABLA_CARRERAS";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function EliminarDatosTablaJustificante($conexion)
{
    global $TABLA_JUSTIFICANTES;
    $sql = "TRUNCATE TABLE $TABLA_JUSTIFICANTES";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute();
}

function EliminarSolicitudID($conexion, $id)
{
    global $TABLA_SOLICITUDES;
    global $CAMPO_ID_SOLICITUD;
    $consulta = "DELETE FROM $TABLA_SOLICITUDES WHERE $CAMPO_ID_SOLICITUD = ?";

    $sql = $conexion->prepare($consulta);
    $sql->bind_param("s", $id);
    return $sql->execute();
}

function buscarPersonalBD($conexion, $query)
{
    global $TABLA_ADMIN;
    global $TABLA_JEFE;
    global $CAMPO_CLAVE_EMPLEADO_ADMIN;
    global $CAMPO_CLAVE_EMPLEADO_JEFE;
    global $CAMPO_NOMBRE;

    $sql = "SELECT $CAMPO_CLAVE_EMPLEADO_ADMIN, $CAMPO_NOMBRE FROM $TABLA_ADMIN
    WHERE $CAMPO_CLAVE_EMPLEADO_ADMIN LIKE ? OR $CAMPO_NOMBRE LIKE ? UNION
    SELECT $CAMPO_CLAVE_EMPLEADO_JEFE, $CAMPO_NOMBRE FROM $TABLA_JEFE
    WHERE $CAMPO_CLAVE_EMPLEADO_JEFE LIKE ? OR $CAMPO_NOMBRE LIKE ?";
    ;
    $stmt = $conexion->prepare($sql);
    $param = "%$query%";
    $stmt->bind_param('ssss', $param, $param, $param, $param);

    $stmt->execute();
    return $stmt->get_result();
}

function buscarEstudianteBD($conexion, $query, $id_carrera)
{
    global $TABLA_ESTUDIANTE;
    global $CAMPO_MATRICULA;
    global $CAMPO_NOMBRE;
    global $CAMPO_ID_CARRERA;

    $sql = "SELECT $CAMPO_MATRICULA, $CAMPO_NOMBRE FROM $TABLA_ESTUDIANTE WHERE ($CAMPO_MATRICULA LIKE ? OR $CAMPO_NOMBRE LIKE ?) AND $CAMPO_ID_CARRERA = ?";

    $stmt = $conexion->prepare($sql);
    $param = "%$query%";
    $stmt->bind_param('ssi', $param, $param, $id_carrera);

    $stmt->execute();
    return $stmt->get_result();
}

function buscarJustificantes($conexion, $query)
{
    global $TABLA_JUSTIFICANTES;
    global $CAMPO_J_MATRICULA;
    global $CAMPO_J_NOMBRE;
    if (strlen($query) > 0) {
        $sql = "SELECT * FROM $TABLA_JUSTIFICANTES WHERE $CAMPO_J_MATRICULA LIKE ? OR $CAMPO_J_NOMBRE LIKE ? ";
        ;
        $stmt = $conexion->prepare($sql);
        $param = "%$query%";
        $stmt->bind_param('ss', $param, $param);

    } else {
        $sql = "SELECT * FROM $TABLA_JUSTIFICANTES";
        $stmt = $conexion->prepare($sql);
    }

    $stmt->execute();
    return $stmt->get_result();
}