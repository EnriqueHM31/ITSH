<?php

function validacionCamposYArchivoCSV($campos_completos, $archivo_tiene_contenido)
{
    if ($campos_completos && !$archivo_tiene_contenido) {

        return 'Campos';
    } elseif (!$campos_completos && $archivo_tiene_contenido) {

        return 'Archivo';
    } elseif ($campos_completos && $archivo_tiene_contenido) {
        return EstructuraMensaje("No puedes poner un archivo si hay campos llenos", "../../assets/iconos/ic_error.webp", "--rojo");
    } elseif (!$campos_completos && !$archivo_tiene_contenido) {
        return EstructuraMensaje("Llena los campos o carga un archivo", "../../assets/iconos/ic_error.webp", "--rojo");
    }
}

function restriccionAdministrador($carrera, $cargo)
{
    if ($carrera !== 'Null' && $cargo === "Administrador") {
        EstructuraMensaje("Un administrador no puede tener una carrera", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }
}

function restriccionJefedeCarrera($carrera, $cargo, $conexion)
{
    global $TABLA_JEFE;

    if ($cargo == "Administrador") {
        return false;
    }

    // Obtiene todas las carreras válidas
    $resultado = ObtenerTodasLasCarreras($conexion);
    $data = [];
    while ($row = $resultado->fetch_assoc()) {
        $data[] = $row["nombre_carrera"];
    }

    if ($cargo === "Jefe de Carrera" && !in_array($carrera, $data, false)) {
        EstructuraMensaje("Un jefe de carrera debe tener una carrera vinculada: $carrera", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }


    $resultadoIdCarrera = ObtenerIDCarrera($conexion, $carrera);

    if ($resultadoIdCarrera < 0) {
        EstructuraMensaje("Error: Esa carrera no existe", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }


    if ($cargo === "Jefe de Carrera") {

        $resultadoDuplicado = ExisteCarreraDuplicada($TABLA_JEFE, $conexion, $resultadoIdCarrera);

        if ($resultadoDuplicado->num_rows > 0) {
            EstructuraMensaje("La carrera $carrera ya tiene un jefe de carrera ", "../../assets/iconos/ic_error.webp", "--rojo");
            return true;
        }
    }
}


function restriccionModificarAJefedeCarrera($carrera, $cargo, $conexion)
{
    global $TABLA_JEFE, $CAMPO_ID_CARRERA;


    $resultadoIdCarrera = ObtenerIDCarrera($conexion, $carrera);


    $sql = "SELECT * FROM $TABLA_JEFE WHERE $CAMPO_ID_CARRERA = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $resultadoIdCarrera);
    $stmt->execute();
    $resultadoDuplicado = $stmt->get_result();

    return $resultadoDuplicado->num_rows;



}

function restriccionKeyDuplicada($identificador, $correo, $conexion)
{
    $resultado = ExisteIdUsuarioDuplicado($conexion, $identificador);

    if ($resultado->num_rows > 0) {
        EstructuraMensaje("La clave ya existe", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }

    $resultado = ExisteCorreoDuplicado($conexion, $correo);

    if ($resultado->num_rows > 0) {
        EstructuraMensaje("Ese correo ya esta vinculado a un usuario", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }
}

function restriccionKeyDuplicadaEstudiante($matricula, $correo, $conexion)
{
    $resultado = ExisteIdUsuarioDuplicado($conexion, $matricula);

    if ($resultado->num_rows > 0) {
        EstructuraMensaje("La matricula ya existe", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }

    $resultado = ExisteCorreoDuplicado($conexion, $correo);

    if ($resultado->num_rows > 0) {
        EstructuraMensaje("Ese correo ya esta vinculado a un usuario", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }
}

function restriccionKey($identificador, $conexion)
{
    $sql = $conexion->prepare("SELECT * FROM personal WHERE identificador = ?");
    $sql->bind_param("s", $identificador);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($resultado->num_rows > 0) {
        EstructuraMensaje("Esa clave ya esta ocupada por un usuario", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }

    $sql->close();
}



function revisionIdentificadorPersonal($identificador)
{
    $patron = '/^ITSH_\d{4}$/';

    if (!(preg_match($patron, $identificador))) {
        EstructuraMensaje("El identificador no es valido <p style='color:var(--rojo)'>" . $identificador . "</p>", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }
}

function revisionIdentificadorEstudiante($identificador)
{
    $patron = '/^\d{3}Z\d{4}$/';

    if (!(preg_match($patron, $identificador))) {
        EstructuraMensaje("El identificador no es valido <p style='color:var(--rojo)'>" . $identificador . "</p>", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }
}

function revisionNombreCompleto($nombre, $apellidos)
{
    $nombreCompleto = $nombre . " " . $apellidos;
    $patron = '/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/';

    if (!(preg_match($patron, $nombreCompleto))) {
        EstructuraMensaje("El nombre no es valido <p style='color:var(--rojo)'>" . $nombreCompleto . "</p>", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }
}

function revisionCargo($cargo)
{
    $diccionario = [
        "administrador",
        "jefe de carrera",
    ];

    if (!(in_array(strtolower(trim($cargo)), $diccionario))) {
        EstructuraMensaje("Este cargo no es valido <p style='color:var(--rojo)'>" . $cargo . "</p>", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }
}

function revisionCorreo($correo)
{
    $patron = '/^[a-zA-Z]+@huatusco\.tecnm\.mx$/';

    if (!(preg_match($patron, $correo))) {
        EstructuraMensaje("Este correo no es valido <p style='color:var(--rojo)'>" . $correo . "</p>", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }
}

function revisionCorreoEstudiante($correo)
{
    $patron = '/^\d{3}z\d{4}+@alum.huatusco\.tecnm\.mx$/';

    if (!(preg_match($patron, $correo))) {
        EstructuraMensaje("Este correo no es valido <p style='color:var(--rojo)'>" . $correo . "</p>", "../../assets/iconos/ic_error.webp", "--rojo");
        return true;
    }
}

function revisarModificacionCorreoEstudiante($conexion, $correoNuevo, $correoAntiguo, $matricula)
{

    global $CAMPO_ID_USUARIO, $TABLA_USUARIO, $CAMPO_CORREO;

    if (isset($correoNuevo) && $correoNuevo !== $correoAntiguo) {
        $sql = "SELECT $CAMPO_ID_USUARIO FROM $TABLA_USUARIO WHERE $CAMPO_CORREO = ? AND $CAMPO_ID_USUARIO != ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $correoNuevo, $matricula);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }
    return FALSE;
}

function revisarModificacionCorreoEmpleado($conexion, $correoNuevo, $correoAntiguo, $clave_empleado)
{

    global $CAMPO_ID_USUARIO, $TABLA_USUARIO, $CAMPO_CORREO;

    if (isset($correoNuevo) && $correoNuevo !== $correoAntiguo) {
        $sql = "SELECT $CAMPO_ID_USUARIO FROM $TABLA_USUARIO WHERE $CAMPO_CORREO = ? AND $CAMPO_ID_USUARIO != ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $correoNuevo, $clave_empleado);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }
    return FALSE;
}

function revisarModificacionMatriculaEstudiante($conexion, $stmt, $matriculaNueva, $matriculaAntigua)
{
    global $CAMPO_ID_USUARIO, $TABLA_USUARIO;
    if (isset($matriculaNueva) && $matriculaNueva !== $matriculaAntigua) {

        $sql = "SELECT $CAMPO_ID_USUARIO FROM $TABLA_USUARIO WHERE $CAMPO_ID_USUARIO = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $matriculaNueva); // Nueva matrícula = futuro ID
        $stmt->execute();
        $stmt->store_result();

        return $stmt;
    }
    return FALSE;
}

function revisarModificacionClaveEmpleado($conexion, $stmt, $matriculaNueva, $matriculaAntigua)
{
    global $CAMPO_ID_USUARIO, $TABLA_USUARIO;
    if (isset($matriculaNueva) && $matriculaNueva !== $matriculaAntigua) {

        $sql = "SELECT $CAMPO_ID_USUARIO FROM $TABLA_USUARIO WHERE $CAMPO_ID_USUARIO = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $matriculaNueva); // Nueva matrícula = futuro ID
        $stmt->execute();
        $stmt->store_result();

        return $stmt;
    }
    return FALSE;
}

function revisarGrupoModalidadCSV($conexion, $id_modalidad, $grupo)
{
    $modalidad = ObtenerIdModalidad($conexion, $id_modalidad);
    global $ESCOLARIZADO, $LETRA_ESCOLARIZADO, $FLEXIBLE, $LETRA_FLEXIBLE;
    if ($modalidad === $ESCOLARIZADO) {
        if ($grupo[3] != $LETRA_ESCOLARIZADO) {
            return true;
        }
    } else if ($modalidad == $FLEXIBLE) {
        if ($grupo[3] !== $LETRA_FLEXIBLE) {
            return true;
        }
    }
}