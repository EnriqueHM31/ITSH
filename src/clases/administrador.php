<?php


class administrador
{
    public const ADMIN = Variables::MENU_DE_ROLES[0];
    public const JEFE_DE_CARRERA = Variables::MENU_DE_ROLES[1];
    public const ESTUDIANTE = Variables::MENU_DE_ROLES[2];

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

        mysqli_begin_transaction($conexion);

        try {

            if (insertarUsuario($conexion, $clave_empleado, $contraseña, $correo, $cargo)) {

                if ($cargo === administrador::ADMIN) {

                    if (!insertarAdministrador($conexion, $clave_empleado, $nombre, $apellidos)) {
                        estructuraMensaje("Ocurrio un problema con la BD", "../../assets/iconos/ic_error.webp", "--rojo");
                    }

                    mysqli_commit($conexion);
                    estructuraMensaje("Registro de administrador exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
                    return;
                }

                if ($cargo === administrador::JEFE_DE_CARRERA) {

                    if (!insertarJefedeCarrera($conexion, $clave_empleado, $nombre, $apellidos, $carrera)) {
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
        $id = trim($id) == null ? "" : $id;
        if ($id == "") {
            estructuraMensaje("Busque y seleccione a un usuario", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (EliminarUsuario($conexion, $id)) {
            estructuraMensaje("El registro fue eliminado de forma exitosa", "../../assets/iconos/ic_correcto.webp", "--verde");

        } else {
            estructuraMensaje("Ocurrio un error al eliminarlo", "../../assets/iconos/ic_error.webp", "--rojo");
        }

    }

    public function añadirPorCSV($conexion)
    {
        mysqli_begin_transaction($conexion);

        $archivo = $_FILES["archivo_csv"]["tmp_name"];

        if (($handle = fopen($archivo, "r")) !== FALSE) {
            fgetcsv($handle);

            while (($datos = fgetcsv($handle, 1000, ",")) !== FALSE) {

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

                insertarUsuario($conexion, $clave_empleado, $contraseña, $correo, $cargo);

                if ($cargo === Usuario::ADMIN) {
                    insertarAdministrador($conexion, $clave_empleado, $nombre, $apellidos);

                } else if ($cargo === Usuario::JEFE_DE_CARRERA) {
                    if (revisionDeCarreras($carrera) || RestriccionJefedeCarrera($carrera, $cargo, $conexion)) {
                        return;
                    }
                    insertarJefedeCarrera($conexion, $clave_empleado, $nombre, $apellidos, $carrera);
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


    public function AgregarCarrera($conexion, $carrera, $numeros_grupos, $id_carrera_nueva)
    {
        mysqli_begin_transaction($conexion);

        if (empty($carrera)) {
            estructuraMensaje("Debes ingresar una carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (!insertarCarrerasDB($conexion, $carrera)) {
            estructuraMensaje("Error al agregar la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $id_carrera = obtenerIDCarrera($conexion, $carrera);

        if (!insertarNumeroIdGruposDB($conexion, $id_carrera, $numeros_grupos, $id_carrera_nueva)) {
            estructuraMensaje("Error al agregar la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        mysqli_commit($conexion);
        estructuraMensaje("Se agregó otra carrera al sistema", "../../assets/iconos/ic_correcto.webp", "--verde");

    }

    public function ModificarCarrera($conexion, $carreraAntigua, $carreraNueva, $numeros_grupos, $id_carrera_nueva)
    {
        mysqli_begin_transaction($conexion);

        if (empty($carreraNueva) || empty($numeros_grupos) || empty($id_carrera_nueva)) {
            estructuraMensaje("Faltan datos para su modificacion", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $id_carrera = obtenerIDCarrera($conexion, $carreraAntigua);

        if (!modificarNombreCarreraDB($conexion, $carreraNueva, $id_carrera)) {
            estructuraMensaje("Error al modificar la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (!modificarNumeroGruposDB($conexion, $id_carrera, $numeros_grupos, $id_carrera_nueva)) {
            estructuraMensaje("Error al modificar los grupos de la carrera", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        mysqli_commit($conexion);
        estructuraMensaje("Se modificó la carrera en el sistema", "../../assets/iconos/ic_correcto.webp", "--verde");
    }
}