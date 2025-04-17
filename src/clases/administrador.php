<?php


class administrador
{

    public function __administrador()
    {
    }

    public function realizarOperacionFormAñadir($conexion)
    {
        $id = trim($_POST["clave"] ?? "");
        $nombre = trim($_POST["nombre"] ?? "");
        $apellidos = trim($_POST["apellidos"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $cargo = $_POST['rol'];
        $carrera = $_POST['carrera'];
        $contraseña = 'Aa12345%';

        $archivo_cargado = isset($_FILES['archivo_csv']) && $_FILES['archivo_csv']['error'] === UPLOAD_ERR_OK;
        $archivo_tiene_contenido = $archivo_cargado && $_FILES['archivo_csv']['size'] > 0;

        $campos_completos = !empty($id) && !empty($nombre) && !empty($apellidos) && !empty($correo);

        $opcion = validacionCamposYArchivoCSV($campos_completos, $archivo_tiene_contenido);

        $opcion === 'Campos' ? $this->operacionInsertarAdministrador($id, $nombre, $apellidos, $cargo, $correo, $carrera, $contraseña, $conexion) : '';
        $opcion === "Archivo" ? $this->añadirPorCSV($conexion) : '';
    }

    public function operacionInsertarAdministrador($id, $nombre, $apellidos, $cargo, $correo, $carrera, $contraseña, $conexion)
    {
        if (restriccionAdministrador($carrera, $cargo)) {
            return;
        }
        if (restriccionJefedeCarrera($carrera, $cargo, $conexion)) {
            return;
        }
        if (restriccionKeyDuplicada($id, $correo, $conexion)) {
            return;
        }

        $this->consultaAñadirPorAdministrador($id, $nombre, $apellidos, $cargo, $carrera, $correo, $contraseña, $conexion);
    }

    public function consultaAñadirPorAdministrador($clave_empleado, $nombre, $apellidos, $cargo, $carrera, $correo, $contraseña, $conexion)
    {
        global $ADMIN, $JEFE;
        mysqli_begin_transaction($conexion);

        try {

            if (insertarUsuario($conexion, $clave_empleado, $nombre, $apellidos, $contraseña, $correo, $cargo)) {

                if ($cargo === $ADMIN) {

                    if ($cargo === $ADMIN) {
                        estructuraMensaje("Ocurrio un problema con la BD", "../../assets/iconos/ic_error.webp", "--rojo");
                    }

                    mysqli_commit($conexion);
                    estructuraMensaje("Registro de administrador exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
                    return;
                }

                if ($cargo === $JEFE) {

                    if (!insertarJefedeCarrera($conexion, $clave_empleado, $carrera)) {
                        estructuraMensaje("Ocurrio un problema con la BD", "../../assets/iconos/ic_error.webp", "--rojo");
                    }

                    mysqli_commit($conexion);
                    estructuraMensaje("Registro Jefe de carrera exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
                    return;
                }

            } else {
                throw new Exception("No se pudo añadir el registro a la BD");
            }

        } catch (Exception $e) {
            estructuraMensaje("Error al añadir el registro" . $e, "../../assets/iconos/ic_error.webp", "--rojo");
        }


    }

    public function modificarJefedeCarrera($conexion, $id, $nombre, $apellidos, $carrera, $cargo, $correo)
    {

        if (empty($id)) {
            estructuraMensaje("Tienes que elegir a un usuario para modificar su informacion", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (RestriccionAdministrador($carrera, $cargo)) {
            return;
        }
        if (RestriccionJefedeCarrera($carrera, $cargo, $conexion)) {
            return;
        }



    }

    public function eliminarRegistro($conexion, $id)
    {
        mysqli_begin_transaction($conexion);

        $id = trim($id) == null ? "" : $id;
        if ($id == "") {
            estructuraMensaje("Busque y seleccione a un usuario", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (EliminarUsuario($conexion, $id)) {
            mysqli_commit($conexion);
            estructuraMensaje("El registro fue eliminado de forma exitosa", "../../assets/iconos/ic_correcto.webp", "--verde");
        } else {
            estructuraMensaje("Ocurrio un error al eliminarlo", "../../assets/iconos/ic_error.webp", "--rojo");
        }

    }

    public function añadirPorCSV($conexion)
    {
        global $ADMIN, $JEFE;
        mysqli_begin_transaction($conexion);

        $archivo = $_FILES["archivo_csv"]["tmp_name"];

        if (($handle = fopen($archivo, "r")) !== FALSE) {
            fgetcsv(
                $handle,
                1000,
                ",",
                '"',
                "\\"
            );

            while (
                ($datos = fgetcsv(
                    $handle,
                    1000,
                    ",",
                    '"',
                    "\\"
                )) !== FALSE
            ) {

                $clave_empleado = trim($datos[0]);
                $nombre = trim($datos[1]);
                $apellidos = trim($datos[2]);
                $cargo = trim($datos[3]);
                $carrera = trim($datos[4]);
                $correo = trim($datos[5]);
                $contraseña = "Aa12345%";

                if ($this->validarRowsCSV($conexion, $clave_empleado, $nombre, $apellidos, $correo, $carrera, $cargo)) {
                    return;
                }

                insertarUsuario($conexion, $clave_empleado, $nombre, $apellidos, $contraseña, $correo, $cargo);

                if ($cargo === $ADMIN) {
                    continue;
                } else if ($cargo === $JEFE) {
                    if (revisionDeCarreras($carrera) || RestriccionJefedeCarrera($carrera, $cargo, $conexion)) {
                        return;
                    }
                    insertarJefedeCarrera($conexion, $clave_empleado, $carrera);
                }
            }

            fclose($handle);
            mysqli_commit($conexion);
            estructuraMensaje("Datos insertados correctamente", "../../assets/iconos/ic_correcto.webp", "--verde");
            return;
        } else {
            estructuraMensaje("Error al abrir el archivo", "../../assets/iconos/ic_error.webp", "--rojo");
        }
    }

    public function validarRowsCSV($conexion, $clave_empleado, $nombre, $apellidos, $correo, $carrera, $cargo)
    {
        if (empty($clave_empleado) || empty($nombre) || empty($apellidos) || empty($correo) || empty($carrera) || empty($cargo)) {
            estructuraMensaje("Faltan datos obligatorios en la fila del CSV", "../../assets/iconos/ic_error.webp", "var(--rojo)");
            return true;
        }
        if (revisionCorreo($correo)) {
            return true;
        }
        if (revisionCargo($cargo)) {
            return true;
        }
        if (revisionNombreCompleto($nombre, $apellidos)) {
            return true;
        }
        if (revisionIdentificadorPersonal($clave_empleado)) {
            return true;
        }
        if (restriccionKeyDuplicada($clave_empleado, $correo, $conexion)) {
            return true;
        }
        if (RestriccionAdministrador($carrera, $cargo)) {
            return true;
        }
    }


    public function AgregarCarrera($conexion, $carrera, $numeros_grupos, $id_carrera_nueva, $id_tipo_carrera, $modalidadEscolarizada, $modalidadFlexible)
    {
        global $TABLA_GRUPO, $CAMPO_CLAVE_GRUPO;
        mysqli_begin_transaction($conexion);


        if (empty($carrera)) {
            estructuraMensaje("Debes ingresar una carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if ($modalidadEscolarizada == "" && $modalidadFlexible == "") {
            estructuraMensaje("Debes seleccionar la modalidad escolarizada o flexible", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (!insertarCarrerasDB($conexion, $carrera, $id_tipo_carrera)) {
            estructuraMensaje("Error al agregar la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $id_carrera = obtenerIDCarrera($conexion, $carrera);

        $existeClaveGrupo = getResultDataTabla($conexion, $TABLA_GRUPO, $CAMPO_CLAVE_GRUPO, $id_carrera_nueva);

        if ($existeClaveGrupo) {
            estructuraMensaje("Esa clave de grupo ya existe", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (!insertarNumeroIdGruposDB($conexion, $id_carrera, $numeros_grupos, $id_carrera_nueva)) {
            estructuraMensaje("Error al agregar la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if ($modalidadEscolarizada != '') {

            $id_modalidad = obtenerIdModalidad($conexion, $modalidadEscolarizada);
            if (!insertarCarreraModalidadDB($conexion, $id_carrera, $id_modalidad)) {
                estructuraMensaje("Error al agregar la carrera con la modalidad escolarizado", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
        }

        if ($modalidadFlexible != '') {
            $id_modalidad = obtenerIdModalidad($conexion, $modalidadFlexible);
            if (!insertarCarreraModalidadDB($conexion, $id_carrera, $id_modalidad)) {
                estructuraMensaje("Error al agregar la carrera con la modalidad flexible", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
        }

        mysqli_commit($conexion);
        estructuraMensaje("Se agregó otra carrera al sistema", "../../assets/iconos/ic_correcto.webp", "--verde");

    }

    public function ModificarCarrera($conexion, $carreraAntigua, $claveGrupoAntigua, $carreraNueva, $id_tipo_carrera_nueva, $numeros_grupos, $id_carrera_nueva, $modalidadEscolarizada, $modalidadFlexible)
    {
        global $TABLA_GRUPO, $CAMPO_CLAVE_GRUPO, $TABLA_CARRERAS, $CAMPO_CARRERA;

        mysqli_begin_transaction($conexion);

        if (empty($carreraNueva) || empty($numeros_grupos) || empty($id_carrera_nueva)) {
            estructuraMensaje("Faltan datos para su modificación", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if ($modalidadEscolarizada == "" && $modalidadFlexible == "") {
            estructuraMensaje("Debes seleccionar la modalidad escolarizada o flexible", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if ($carreraNueva != $carreraAntigua) {
            $existeCarrera = getResultDataTabla($conexion, $TABLA_CARRERAS, $CAMPO_CARRERA, $carreraNueva);
            if ($existeCarrera) {
                estructuraMensaje("Esa carrera ya existe", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
        }

        $idCarreraAntigua = obtenerIDCarrera($conexion, $carreraAntigua);

        if (!modificarNombreTipoCarreraDB($conexion, $carreraNueva, $id_tipo_carrera_nueva, $idCarreraAntigua)) {
            estructuraMensaje("Error al modificar la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if ($claveGrupoAntigua != $id_carrera_nueva) {
            $existeClave = getResultDataTabla($conexion, $TABLA_GRUPO, $CAMPO_CLAVE_GRUPO, $id_carrera_nueva);
            if ($existeClave) {
                estructuraMensaje("Esa clave grupo ya existe", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
        }

        if (!modificarNumeroGruposDB($conexion, $idCarreraAntigua, $numeros_grupos, $id_carrera_nueva)) {
            estructuraMensaje("Error al modificar los grupos de la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $modalidades = obtenerModalidadesCarrera($conexion, $idCarreraAntigua);

        $modalidadInputs = [
            "Escolarizado" => $modalidadEscolarizada,
            "Flexible" => $modalidadFlexible
        ];

        foreach ( $modalidadInputs as $tipo => $valor ) {
            $id_modalidad = obtenerIdModalidad($conexion, $tipo);

            if (empty($valor)) {
                if (in_array($tipo, $modalidades)) {
                    eliminarCarreraModalidadDB($conexion, $idCarreraAntigua, $id_modalidad);
                }
            } else {
                if (!in_array($tipo, $modalidades)) {
                    insertarCarreraModalidadDB($conexion, $idCarreraAntigua, $id_modalidad);
                }
            }
        }

        mysqli_commit($conexion);
        estructuraMensaje("Se modificó la carrera en el sistema", "../../assets/iconos/ic_correcto.webp", "--verde");
    }
}