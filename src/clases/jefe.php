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
            if (!insertarUsuario($conexion, $matricula, $contraseña, $correo, $rol)) {
                throw new Exception("Error al insertar el usuario");
            }

            if (!insertarEstudiante($conexion, $matricula, $nombre, $apellidos, $id_carrera, $id_modalidad, $grupo)) {
                throw new Exception("Ocurrió un problema con la BD al insertar estudiante");
            }

            mysqli_commit($conexion);
            estructuraMensaje("Registro del estudiante exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
            return;

        } catch (Exception $e) {
            mysqli_rollback($conexion);
            estructuraMensaje($e, "../../assets/iconos/ic_error.webp", "--rojo");
        }
    }

    public function añadirPorCSVEstudiantes($conexion, $rol, $id_carrera)
    {
        mysqli_begin_transaction($conexion);

        $archivo = $_FILES["archivo_csv"]["tmp_name"];

        if (($handle = fopen($archivo, "r")) === false) {
            estructuraMensaje("Error al abrir el archivo", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        fgetcsv($handle); // Saltar encabezado

        try {
            while (($datos = fgetcsv($handle, 1000, ",")) !== false) {
                $matricula = trim($datos[0]);
                $nombre = trim($datos[1]);
                $apellidos = trim($datos[2]);
                $grupo = trim($datos[3]);
                $id_modalidad = obtenerIdModalidad($conexion, trim($datos[4]));
                $correo = trim($datos[5]);
                $contraseña = "Aa12345%";

                if ($this->validarRowsCSV($conexion, $matricula, $nombre, $apellidos, $grupo, $id_modalidad, $rol, $correo, $id_carrera)) {
                    throw new Exception("Error en los datos del CSV para la matrícula: $matricula");
                }

                if (!insertarUsuario($conexion, $matricula, $contraseña, $correo, $rol)) {
                    throw new Exception("Error al insertar usuario: $matricula");
                }

                if (!insertarEstudiante($conexion, $matricula, $nombre, $apellidos, $id_carrera, $id_modalidad, $grupo)) {
                    throw new Exception("Error al insertar estudiante: $matricula");
                }
            }

            mysqli_commit($conexion);
            estructuraMensaje("Datos insertados correctamente", "../../assets/iconos/ic_correcto.webp", "--verde");
        } catch (Exception $e) {
            mysqli_rollback($conexion);
            estructuraMensaje($e, "../../assets/iconos/ic_error.webp", "--rojo");
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

    function actualizarUsuario($conexion, $matricula, $nuevosDatos)
    {
        global $TABLA_USUARIO, $CAMPO_ID_USUARIO, $CAMPO_CORREO;

        mysqli_begin_transaction($conexion);

        if (empty($matricula)) {
            estructuraMensaje("Tienes que elegir a un usuario para modificar su informacion", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        // Obtener datos actuales del usuario (incluyendo 'matricula')
        $usuarioActual = getResultDataTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $matricula);

        if (!$usuarioActual) {
            estructuraMensaje("Usuario no está en el sistema", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }
        $correoAntiguo = $usuarioActual[$CAMPO_CORREO];
        $correoNuevo = $nuevosDatos['correo'];

        $stmt = revisarModificacionCorreoEstudiante($conexion, $correoNuevo, $correoAntiguo, $matricula);

        if ($stmt && $stmt->num_rows > 0) {
            estructuraMensaje("El correo ya está asociado con otro usuario", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $matriculaAntigua = $usuarioActual[$CAMPO_ID_USUARIO];
        $matriculaNueva = $nuevosDatos["clave"];

        $stmt = revisarModificacionMatriculaEstudiante($conexion, $stmt, $matriculaNueva, $matriculaAntigua);

        if ($stmt && $stmt->num_rows > 0) {
            estructuraMensaje("Esta matrícula ya está registrada", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $id_usuario = $nuevosDatos['clave'];
        $nombre = $nuevosDatos['nombre'];
        $apellidos = $nuevosDatos['apellidos'];
        $correo = $nuevosDatos['correo'];
        $id_modalidad = $nuevosDatos['modalidad'] == "Escolarizado" ? 1 : 2;
        $grupo = $nuevosDatos['grupo'];



        if (!modificarDatosEstudianteDB($conexion, $id_usuario, $correo, $nombre, $apellidos, $grupo, $id_modalidad, $matricula)) {
            estructuraMensaje("Ocurrio un problema con los datos personales", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }


        mysqli_commit($conexion);
        estructuraMensaje("Se ha modificado los datos en la base de datos", "../../assets/iconos/ic_correcto.webp", "--verde");

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



    public function MostrarSolicitudes($resultado, $id)
    {
        $tablaArray = [];
        $detallesArray = [];

        $tablaHead = "
        <tr>
            <th>Solicitud</th>
            <th>Matricula</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Grupo</th>
            <th>Motivo</th>
            <th>Fecha</th>
            <th>Evidencia</th>
            <th>Estado</th>
            <th>Opciones</th>
        </tr>
        ";

        while ($fila = $resultado->fetch_assoc()) {
            if ($fila['estado'] == "Aceptada") {
                $clase = "aceptada";
            } else if ($fila['estado'] == "Pendiente") {
                $clase = "pendiente";
            } else {
                $clase = "rechazada";
            }

            $fecha = explode("-", $fila['fecha_ausencia']);

            $tabla = "";

            $tabla .= "
            <tr>
            <td> {$fila['solicitud']}</td>
            <td> {$fila['matricula']}</td>
            <td> {$fila['nombre']}</td>
            <td> {$fila['apellidos']}</td>
            <td> {$fila['grupo']}</td>
            <td> {$fila['motivo']}</td>
            <td> {$fecha[2]}-{$fecha[1]}-{$fecha[0]}</td>
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

            $detalles = "
                <details class='detalles_solicitudes' 
                data-datos='{$fila['solicitud']}, {$fila['matricula']}, {$fila['nombre']},{$fila['apellidos']}, {$fila['grupo']}, {$fila['motivo']}, {$fila['fecha_ausencia']}, {$clase}, {$fila['evidencia']} '>
                    <summary>
                        <div class='detalles'>
                            <p>Solicitud: {$fila['solicitud']}</p>
                        </div>
                        

                        <div class='{$clase} estado'></div>
                    </summary>
                    <div class='contenido_solicitudes'>
                        <div class='detalle'><strong>Matricula:</strong><p>{$fila['matricula']}</p></div>
                        <div class='detalle'><strong>Nombre:</strong><p>{$fila['nombre']}</p></div>
                        <div class='detalle'><strong>Apellidos:</strong><p>{$fila['apellidos']}</p></div>
                        <div class='detalle'><strong>Grupo:</strong><p>{$fila['grupo']}</p></div>
                        <div class='detalle'><strong>Motivo:</strong><p>{$fila['motivo']}</p></div>
                        <div class='detalle'><strong>Ausencia:</strong><p>{$fila['fecha_ausencia']}</p></div>

                        <div class='detalle'><strong>Evidencia:</strong>
                            <a href='../Alumno/evidencias/{$fila['evidencia']}' target='_blank' >
                                {$fila['evidencia']}
                            </a>
                        </div>
                        <div class='opciones'>
                                <button class='btn_opciones_solicitudes' data-id='$id' onclick='aceptarSolicitud(this)'>
                                    Aceptar
                                </button>

                                <button class='btn_opciones_solicitudes' onclick='rechazarSolicitud(this)'>
                                    Rechazar
                                </button>

                                <button class='btn_opciones_solicitudes' onclick='eliminarFila(this)'>
                                    Eliminar
                                </button>
                            </div>
                    </div>
                </details>
            ";
            array_push($tablaArray, $tabla);
            array_push($detallesArray, $detalles);
        }

        array_push($tablaArray, $tablaHead);

        return [$tablaArray, $detallesArray];


    }

    public function HistorialJustificantes($conexion, $carrera)
    {
        global $TABLA_JUSTIFICANTES, $CAMPO_J_CARRERA;
        $resultado = getResultDataTabla($conexion, $TABLA_JUSTIFICANTES, $CAMPO_J_CARRERA, $carrera);

        if ($resultado->num_rows == 0) {
            echo "<p class='sin_justificantes'>No hay justificantes disponibles</p>";
        } else {
            while ($fila = $resultado->fetch_array()) {
                $tiempo = explode(" ", $fila['fecha_creacion']);
                $tiempo_fecha = explode("-", $tiempo[0]);

                echo "
        <a href='../Alumno/justificantes/{$fila['justificante_pdf']}' class='archivo' target='_blank'>
            <h2> Folio {$fila['id']} </h2>
            <p> {$fila['matricula_alumno']} </p>
            <p> {$fila['nombre_alumno']} </p>
            <span>Hora: {$tiempo[1]} </span>
            <span>Fecha: {$tiempo_fecha[2]} de " . Variables::MESES[$tiempo_fecha[1][1] - 1] . " de " . $tiempo_fecha[0] . " </span>
        </a>
        ";
            }
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


    public function ObtenerGruposDeLaCarrera($conexion, $id_carrera)
    {
        $GruposCarrera = obtenerGrupos($conexion, $id_carrera);
        $grupos = $GruposCarrera[0][0];
        $modalidades = $GruposCarrera[1][0]["Modalidades"];
        $id_grupos = $grupos["id_grupos"];
        $Numero_grupos = $grupos["Numero_grupos"];

        return [$id_grupos, $Numero_grupos, $modalidades];
    }

}