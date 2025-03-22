<?php


class Jefe
{

    public function __construct()
    {
    }

    public function realizarOperacionFormAñadirEstudiantes($conexion, $matricula, $contraseña, $rol, $nombre, $apellidos, $correo, $id_modalidad, $id_carrera, $grupo)
    {

        $archivo_cargado = isset($_FILES['archivo_csv']) && $_FILES['archivo_csv']['error'] === UPLOAD_ERR_OK;
        $archivo_tiene_contenido = $archivo_cargado && $_FILES['archivo_csv']['size'] > 0;

        $campos_completos = !empty($matricula) && !empty($contraseña) && !empty($nombre) && !empty($apellidos) && !empty($correo) && !empty($id_modalidad) && !empty($id_carrera) && !empty($rol) && !empty($grupo);

        $opcion = validacionCamposYArchivoCSV($campos_completos, $archivo_tiene_contenido);

        $opcion === 'Campos' ? $this->operacionInsertarEstudiante($conexion, $matricula, $contraseña, $rol, $nombre, $apellidos, $correo, $id_modalidad, $id_carrera, $grupo) : '';
        $opcion === "Archivo" ? $this->añadirPorCSVEstudiantes($conexion, $rol, $id_carrera) : '';
    }

    public function operacionInsertarEstudiante($conexion, $matricula, $contraseña, $rol, $nombre, $apellidos, $correo, $id_modalidad, $id_carrera, $grupo)
    {

        if (restriccionKeyDuplicadaEstudiante($matricula, $correo, $conexion)) {
            return;
        }

        mysqli_begin_transaction($conexion);

        try {

            if (insertarUsuario($conexion, $matricula, $contraseña, $correo, $rol)) {

                if (!insertarEstudiante($conexion, $matricula, $nombre, $apellidos, $id_carrera, $id_modalidad, $grupo)) {
                    estructuraMensaje("Ocurrio un problema con la BD", "../../assets/iconos/ic_error.webp", "--rojo");
                }
                mysqli_commit($conexion);

                estructuraMensaje("Registro del estudiante exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
                return;

            }
        } catch (Exception $e) {
            estructuraMensaje("Error al añadir el registro", "../../assets/iconos/ic_error.webp", "--rojo");
        }


    }

    public function añadirPorCSVEstudiantes($conexion, $rol, $id_carrera)
    {
        mysqli_begin_transaction($conexion);

        $archivo = $_FILES["archivo_csv"]["tmp_name"];

        if (($handle = fopen($archivo, "r")) !== FALSE) {
            fgetcsv($handle);

            while (($datos = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $matricula = trim($datos[0]);
                $nombre = trim($datos[1]);
                $apellidos = trim($datos[2]);
                $grupo = trim($datos[3]);
                $id_modalidad = obtenerIdModalidad($conexion, trim($datos[4]));
                $correo = trim($datos[5]);
                $contraseña = "Aa12345%";

                if ($this->validarRowsCSV($conexion, $matricula, $nombre, $apellidos, $grupo, $id_modalidad, $rol, $correo, $id_carrera)) {
                    return;
                }

                insertarUsuario($conexion, $matricula, $contraseña, $correo, $rol);

                insertarEstudiante($conexion, $matricula, $nombre, $apellidos, $id_carrera, $id_modalidad, $grupo);
            }

            mysqli_commit($conexion);
            estructuraMensaje("Datos insertados correctamente", "../../assets/iconos/ic_correcto.webp", "--verde");
            return;
        } else {
            estructuraMensaje("Error al abrir el archivo", "../../assets/iconos/ic_error.webp", "--rojo");
        }
    }

    public function validarRowsCSV($conexion, $matricula, $nombre, $apellidos, $grupo, $id_modalidad, $rol, $correo, $id_carrera)
    {
        if (empty($matricula) || empty($nombre) || empty($apellidos) || empty($grupo) || empty($id_modalidad) || empty($rol) || empty($correo) || empty($id_carrera)) {
            estructuraMensaje("Faltan datos obligatorios en la fila del CSV", "../../assets/iconos/ic_error.webp", "var(--rojo)");
            return true;
        }
        if (revisionCorreoEstudiante($correo)) {
            return true;
        }
        if (revisionNombreCompleto($nombre, $apellidos)) {
            return true;
        }
        if (revisionIdentificadorEstudiante($matricula)) {
            return true;
        }
        if (restriccionKeyDuplicada($matricula, $correo, $conexion)) {
            return true;
        }

    }

    public function eliminarRegistroEstudiante($conexion, $id)
    {
        $id = trim($id);
        if (!isset($_POST['identificador'])) {
            estructuraMensaje("Busque y seleccione a un usuario", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (EliminarUsuario($conexion, $id)) {
            estructuraMensaje("El registro fue eliminado de forma exitosa", "../../assets/iconos/ic_correcto.webp", "--verde");
        } else {
            estructuraMensaje("Ocurrio un error al eliminarlo", "../../assets/iconos/ic_error.webp", "--rojo");
        }

    }

    public function TablaSolicitudesRegistros($conexion)
    {
        $sql = "SELECT * FROM solicitudes";
        $resultado = mysqli_query($conexion, $sql);

        return $resultado;
    }

    public function MostrarSolicitudes($resultado, $id)
    {
        while ($fila = mysqli_fetch_array($resultado)) {
            if ($fila['estado'] == "Aceptada") {
                $clase = "aceptada";
            } else if ($fila['estado'] == "Pendiente") {
                $clase = "pendiente";
            } else {
                $clase = "rechazada";
            }
            echo "
            <tr>
            <td> {$fila['solicitud']}</td>
            <td> {$fila['matricula']}</td>
            <td> {$fila['nombre']}</td>
            <td> {$fila['apellidos']}</td>
            <td> {$fila['grupo']}</td>
            <td> {$fila['motivo']}</td>
            <td> {$fila['fecha_ausencia']}</td>
            <td>
                <a href='../Alumno/evidencias/{$fila['evidencia']}' target='_blank' class='link_evidencia'>
                    {$fila['evidencia']}
                </a> 
            </td>
            <td class='{$clase}'></td>
            <td>
                <div class='opciones'>
                    <button class='btn_opciones_solicitudes' data-id='$id' onclick='aceptarSolicitud(this)'>
                        <img src='../../assets/iconos/ic_correcto.webp' alt='icono para aceptar la solicitud para el justificante'>
                    </button>

                    <button class='btn_opciones_solicitudes' onclick='rechazarSolicitud(this)'>
                        <img src='../../assets/iconos/ic_error.webp' alt='icono para rechazar la solicitud para el justificante'>
                    </button>

                    <button class='btn_opciones_solicitudes' onclick='eliminarFila(this)'>
                        <img src='../../assets/iconos/ic_eliminar.webp' alt='icono para eliminar la solicitud para el justificante'>
                    </button>
                </div>
            </td>
            </tr>
            ";
        }
    }

    public function HistorialJustificantes($conexion, $carrera)
    {
        $sql = "SELECT * FROM " . Variables::TABLA_BD_JUSTIFICANTES . " 
        WHERE " . Variables::CAMPO_J_CARRERA . " = '$carrera'";


        $resultado = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($resultado) == 0) {
            echo "<p class='sin_justificantes'>No hay justificantes disponibles</p>";
        }
        while ($fila = mysqli_fetch_array($resultado)) {

            $tiempo = explode(" ", $fila['fecha_creacion']);
            echo "
            <a href='../Alumno/justificantes/{$fila['justificante_pdf']}' class='archivo' target='_blank'>
                <h2> Folio {$fila['id']} </h2>
                <p> {$fila['matricula_alumno']} </p>
                <p> {$fila['nombre_alumno']} </p>
                <span>Hora: {$tiempo[1]} </span>
                <span>Fecha: {$tiempo[0]} </span>
            </a>
            ";
        }
    }


    public function reiniciarFolio($conexion)
    {
        try {
            $sql = "TRUNCATE TABLE " . Variables::TABLA_BD_JUSTIFICANTES;
            $stmt = $conexion->prepare($sql);

            if ($stmt->execute()) {
                estructuraMensaje("Se ha reiniciado el folio", "../../assets/iconos/ic_correcto.webp", "--verde");
            } else {
                estructuraMensaje("Ocurrió un error al reiniciar el folio", "../../assets/iconos/ic_error.webp", "--rojo");
            }

            $stmt->close(); // Cerrar el statement
        } catch (Exception $e) {
            estructuraMensaje("Error: " . $e->getMessage(), "../../assets/iconos/ic_error.webp", "--rojo");
        }
    }

}