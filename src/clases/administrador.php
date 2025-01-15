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

    public function consultaAñadirPorAdministrador($identificador, $nombre, $apellidos, $cargo, $carrera, $correo, $contraseña, $conexion)
    {

        mysqli_begin_transaction($conexion);

        try {

            if (insertarUsuario($conexion, $cargo, $identificador, $contraseña, $correo)) {

                if ($cargo === administrador::ADMIN) {

                    if (!insertarAdministrador($conexion, $identificador, $nombre, $apellidos)) {
                        estructuraMensaje("Ocurrio un problema con la BD", "../../assets/iconos/ic_error.webp", "--rojo");
                    }
                    mysqli_commit($conexion);

                    estructuraMensaje("Registro de administrador exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
                    return;
                }

                if ($cargo === administrador::JEFE_DE_CARRERA) {


                    if (!insertarJefedeCarrera($conexion, $identificador, $nombre, $apellidos, $carrera)) {
                        estructuraMensaje("Ocurrio un problema con la BD", "../../assets/iconos/ic_error.webp", "--rojo");
                    }
                    mysqli_commit($conexion);

                    estructuraMensaje("Registro Jefe de carrera exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
                    return;
                }

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

        if (modificarPersonal($conexion, $id, $nombre, $apellidos, $carrera, $cargo, $correo)) {
            estructuraMensaje("Se han modificado los datos", "../../assets/iconos/ic_correcto.webp", "--verde");
        } else {
            estructuraMensaje("Ocurrio un error en la base de datos", "../../assets/iconos/ic_error.webp", "--rojo");
        }

    }

    public function eliminarRegistro($conexion, $id)
    {
        $id = trim($id) ?? "";
        if (!isset($_POST['identificador'])) {
            estructuraMensaje("Busque y seleccione a un usuario", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (EliminarPersonal($conexion, $id)) {
            estructuraMensaje("El registro fue eliminado de forma exitosa", "../../assets/iconos/ic_correcto.webp", "--verde");
        } else {
            estructuraMensaje("Ocurrio un error al eliminarlo", "../../assets/iconos/ic_error.webp", "--rojo");
        }

    }

    public function añadirPorCSV($conexion)
    {
        $archivo = $_FILES["archivo_csv"]["tmp_name"];

        if (($handle = fopen($archivo, "r")) !== FALSE) {
            fgetcsv($handle);

            $stmt = $conexion->prepare("INSERT INTO personal 
                (identificador, nombre, apellidos, cargo, correo, carrera) VALUES (?, ?, ?, ?, ?, ?)");

            while (($datos = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $identificador = trim($datos[0]);
                $nombre = trim($datos[1]);
                $apellidos = trim($datos[2]);
                $cargo = trim($datos[3]);
                $correo = trim($datos[4]);
                $carrera = trim($datos[5]);

                if (revisionCorreo($correo)) {
                    return;
                }
                if (revisionCargo($cargo)) {
                    return;
                }
                if (revisionNombreCompleto($nombre, $apellidos)) {
                    return;
                }
                if (revisionIdentificadorPersonal($identificador)) {
                    return;
                }
                if (revisionDeCarreras($carrera)) {
                    return;
                }
                if (restriccionKeyDuplicada($identificador, $correo, $conexion)) {
                    return;
                }
                if (RestriccionAdministrador($carrera, $cargo)) {
                    return;
                }
                if (RestriccionJefedeCarrera($carrera, $cargo, $conexion)) {
                    return;
                }
            }

            rewind($handle);
            fgetcsv($handle);

            while (($datos = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $identificador = trim($datos[0]);
                $nombre = trim($datos[1]);
                $apellidos = trim($datos[2]);
                $cargo = trim($datos[3]);
                $correo = trim($datos[4]);
                $carrera = trim($datos[5]);

                $stmt->bind_param("ssssss", $identificador, $nombre, $apellidos, $cargo, $correo, $carrera);
                $stmt->execute();
            }

            fclose($handle);
            estructuraMensaje("Datos insertados correctamente", "../../assets/iconos/ic_correcto.webp", "--verde");

            return;
        } else {
            estructuraMensaje("Error al abrir el archivo", "../../assets/iconos/ic_error.webp", "--rojo");
        }
    }


}