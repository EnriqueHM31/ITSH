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

            if (InsertarUsuarioDB($conexion, $clave_empleado, $nombre, $apellidos, $contraseña, $correo, $cargo)) {

                if ($cargo === $ADMIN) {

                    if ($cargo === $ADMIN) {
                        EstructuraMensaje("Ocurrio un problema con la BD", "../../assets/iconos/ic_error.webp", "--rojo");
                    }

                    mysqli_commit($conexion);
                    EstructuraMensaje("Registro de administrador exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
                    return;
                }

                if ($cargo === $JEFE) {

                    if (!InsertarJefeDeCarreraDB($conexion, $clave_empleado, $carrera)) {
                        EstructuraMensaje("Ocurrio un problema con la BD", "../../assets/iconos/ic_error.webp", "--rojo");
                    }

                    mysqli_commit($conexion);
                    EstructuraMensaje("Registro Jefe de carrera exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
                    return;
                }

            } else {
                throw new Exception("No se pudo añadir el registro a la BD");
            }

        } catch (Exception $e) {
            EstructuraMensaje("Error al añadir el registro" . $e, "../../assets/iconos/ic_error.webp", "--rojo");
        }


    }

    public function modificarJefedeCarrera($conexion, $id, $nombre, $apellidos, $carrera, $cargo, $correo)
    {

        if (empty($id)) {
            EstructuraMensaje("Tienes que elegir a un usuario para modificar su informacion", "../../assets/iconos/ic_error.webp", "--rojo");
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
            EstructuraMensaje("Busque y seleccione a un usuario", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (EliminarUsuarioDB($conexion, $id)) {
            mysqli_commit($conexion);
            EstructuraMensaje("El registro fue eliminado de forma exitosa", "../../assets/iconos/ic_correcto.webp", "--verde");
        } else {
            EstructuraMensaje("Ocurrio un error al eliminarlo", "../../assets/iconos/ic_error.webp", "--rojo");
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

                InsertarUsuarioDB($conexion, $clave_empleado, $nombre, $apellidos, $contraseña, $correo, $cargo);

                if ($cargo === $ADMIN) {
                    continue;
                } else if ($cargo === $JEFE) {
                    if (RestriccionJefedeCarrera($carrera, $cargo, $conexion)) {
                        return;
                    }
                    InsertarJefeDeCarreraDB($conexion, $clave_empleado, $carrera);
                }
            }

            fclose($handle);
            mysqli_commit($conexion);
            EstructuraMensaje("Datos insertados correctamente", "../../assets/iconos/ic_correcto.webp", "--verde");
            return;
        } else {
            EstructuraMensaje("Error al abrir el archivo", "../../assets/iconos/ic_error.webp", "--rojo");
        }
    }

    public function validarRowsCSV($conexion, $clave_empleado, $nombre, $apellidos, $correo, $carrera, $cargo)
    {
        if (empty($clave_empleado) || empty($nombre) || empty($apellidos) || empty($correo) || empty($carrera) || empty($cargo)) {
            EstructuraMensaje("Faltan datos obligatorios en la fila del CSV", "../../assets/iconos/ic_error.webp", "var(--rojo)");
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
            EstructuraMensaje("Debes ingresar una carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if ($modalidadEscolarizada == "" && $modalidadFlexible == "") {
            EstructuraMensaje("Debes seleccionar la modalidad escolarizada o flexible", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }


        $id_carrera = ObtenerIDCarrera($conexion, $carrera);

        $existeClaveGrupo = ObtenerDatosDeUnaTabla($conexion, $TABLA_GRUPO, $CAMPO_CLAVE_GRUPO, $id_carrera_nueva);

        if ($existeClaveGrupo) {
            EstructuraMensaje("Esa clave de grupo ya existe", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (!InsertarCarreraDB($conexion, $carrera, $id_tipo_carrera)) {
            EstructuraMensaje("Error al agregar la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }


        if (!InsertarNumeroIdGruposDB($conexion, $id_carrera_nueva, $numeros_grupos, $id_carrera_nueva)) {
            EstructuraMensaje("Error al agregar la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if ($modalidadEscolarizada != '') {

            $id_modalidad = ObtenerIdModalidad($conexion, $modalidadEscolarizada);
            if (!InsertarCarreraModalidadDB($conexion, $id_carrera, $id_modalidad)) {
                EstructuraMensaje("Error al agregar la carrera con la modalidad escolarizado", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
        }

        if ($modalidadFlexible != '') {
            $id_modalidad = ObtenerIdModalidad($conexion, $modalidadFlexible);
            if (!InsertarCarreraModalidadDB($conexion, $id_carrera, $id_modalidad)) {
                EstructuraMensaje("Error al agregar la carrera con la modalidad flexible", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
        }

        mysqli_commit($conexion);
        EstructuraMensaje("Se agregó otra carrera al sistema", "../../assets/iconos/ic_correcto.webp", "--verde");

    }


    public function ModificarCarrera($conexion, $carreraAntigua, $claveGrupoAntigua, $carreraNueva, $id_tipo_carrera_nueva, $numeros_grupos, $id_carrera_nueva, $modalidadEscolarizada, $modalidadFlexible)
    {
        global $TABLA_GRUPO, $CAMPO_CLAVE_GRUPO, $TABLA_CARRERAS, $CAMPO_CARRERA;
        global $TABLA_USUARIO, $CAMPO_ID_USUARIO, $CAMPO_CORREO;

        mysqli_begin_transaction($conexion);

        if (empty($carreraNueva) || empty($numeros_grupos) || empty($id_carrera_nueva)) {
            EstructuraMensaje("Faltan datos para su modificación", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if ($modalidadEscolarizada == "" && $modalidadFlexible == "") {
            EstructuraMensaje("Debes seleccionar la modalidad escolarizada o flexible", "../../assets/iconos/ic_error.webp", "--rojo");
        }


        if ($carreraNueva != $carreraAntigua) {
            $existeCarrera = ObtenerDatosDeUnaTabla($conexion, $TABLA_CARRERAS, $CAMPO_CARRERA, $carreraNueva);
            if ($existeCarrera) {
                EstructuraMensaje("Esa carrera ya existe", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
        }

        $idCarreraAntigua = ObtenerIDCarrera($conexion, $carreraAntigua);


        if (!ModificarNombreTipoCarreraDB($conexion, $carreraNueva, $id_tipo_carrera_nueva, $idCarreraAntigua)) {
            EstructuraMensaje("Error al modificar la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
        }


        if ($claveGrupoAntigua != $id_carrera_nueva) {
            $existeClave = ObtenerDatosDeUnaTabla($conexion, $TABLA_GRUPO, $CAMPO_CLAVE_GRUPO, $id_carrera_nueva);
            if ($existeClave) {
                EstructuraMensaje("Esa clave grupo ya existe", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
        }

        if (!ModificarNumeroGruposDB($conexion, $idCarreraAntigua, $numeros_grupos, $id_carrera_nueva)) {
            EstructuraMensaje("Error al modificar los grupos de la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
        }


        $modalidades = ObtenerIdModalidadesCarrera($conexion, $idCarreraAntigua);

        $modalidadInputs = [
            "Escolarizado" => $modalidadEscolarizada,
            "Flexible" => $modalidadFlexible
        ];


        foreach ( $modalidadInputs as $tipo => $valor ) {
            $id_modalidad = ObtenerIdModalidad($conexion, $tipo);

            if (empty($valor)) {
                if (in_array($tipo, $modalidades)) {
                    EliminarCarreraModalidadDB($conexion, $idCarreraAntigua, $id_modalidad);
                }
            } else {
                if (!in_array($tipo, $modalidades)) {
                    InsertarCarreraModalidadDB($conexion, $idCarreraAntigua, $id_modalidad);
                }
            }

            mysqli_commit($conexion);
            EstructuraMensaje("Se modificó la carrera en el sistema", "../../assets/iconos/ic_correcto.webp", "--verde");
        }
    }


    public function ModificarUsuarioDB($conexion, $clave_empleado, $nuevosDatos)
    {
        global $TABLA_USUARIO, $CAMPO_ID_USUARIO, $CAMPO_CORREO;

        mysqli_begin_transaction($conexion);

        if (empty($clave_empleado)) {
            EstructuraMensaje("Tienes que elegir a un usuario para modificar su informacion", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $usuarioActual = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $clave_empleado);

        if (!$usuarioActual) {
            EstructuraMensaje("Usuario no está en el sistema", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $correoAntiguo = $usuarioActual[$CAMPO_CORREO];
        $correoNuevo = $nuevosDatos['correo'];

        $stmt = revisarModificacionCorreoEmpleado($conexion, $correoNuevo, $correoAntiguo, $clave_empleado);

        if ($stmt && $stmt->num_rows > 0) {
            EstructuraMensaje("El correo ya está asociado con otro usuario", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $nombre = $nuevosDatos['nombre'];
        $apellidos = $nuevosDatos['apellidos'];
        $correo = $nuevosDatos['correo'];
        $carrera = $nuevosDatos['carrera'];
        $rol = $nuevosDatos['rol'];
        $rolAntiguo = $nuevosDatos['rol_antiguo'];
        $carreraAntigua = $nuevosDatos['carrera_antigua'];


        $mensaje = ModificarDatosPersonalDB($conexion, $clave_empleado, $nombre, $apellidos, $carrera, $carreraAntigua, $rol, $rolAntiguo, $correo);
        if ($mensaje != 1) {
            EstructuraMensaje($mensaje, '../../assets/iconos/ic_error.webp', '--rojo');
            return;
        }

        mysqli_commit($conexion);
        EstructuraMensaje("Se ha modificado los datos en la base de datos", "../../assets/iconos/ic_correcto.webp", "--verde");
    }
}