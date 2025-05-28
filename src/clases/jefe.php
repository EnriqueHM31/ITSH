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

        $opcion === 'Campos' ? $this->operacionInsertarEstudianteDB($conexion, $matricula, $contraseña, $rol, $nombre, $apellidos, $correo, $id_modalidad, $id_carrera, $grupo) : '';
        $opcion === "Archivo" ? $this->añadirPorCSVEstudiantes($conexion, $rol, $id_carrera) : '';
    }

    public function operacionInsertarEstudianteDB($conexion, $matricula, $contraseña, $rol, $nombre, $apellidos, $correo, $id_modalidad, $id_carrera, $grupo)
    {
        if (restriccionKeyDuplicadaEstudiante($matricula, $correo, $conexion)) {
            return;
        }

        mysqli_begin_transaction($conexion);

        try {
            if (!InsertarUsuarioDB($conexion, $matricula, $nombre, $apellidos, $contraseña, $correo, $rol)) {
                throw new Exception("Error al insertar el usuario");
            }

            if (!InsertarEstudianteDB($conexion, $matricula, $nombre, $apellidos, $id_carrera, $id_modalidad, $grupo)) {
                throw new Exception("Ocurrió un problema con la BD al insertar estudiante");
            }

            mysqli_commit($conexion);
            EstructuraMensaje("Registro del estudiante exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
            return;

        } catch (Exception $e) {
            mysqli_rollback($conexion);
            EstructuraMensaje($e->getMessage(), "../../assets/iconos/ic_error.webp", "--rojo");
        }
    }

    public function añadirPorCSVEstudiantes($conexion, $rol, $id_carrera)
    {
        mysqli_begin_transaction($conexion);

        $archivo = $_FILES["archivo_csv"]["tmp_name"];

        if (($handle = fopen($archivo, "r")) === false) {
            EstructuraMensaje("Error al abrir el archivo", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        fgetcsv($handle); // Saltar encabezado

        try {
            while (($datos = fgetcsv($handle, 1000, ",")) !== false) {
                $matricula = trim($datos[0]);
                $nombre = trim($datos[1]);
                $apellidos = trim($datos[2]);
                $grupo = trim($datos[3]);
                $id_modalidad = ObtenerIdModalidad($conexion, trim($datos[4]));
                $correo = trim($datos[5]);
                $contraseña = "Aa12345%";

                $salidaValidacion = $this->validarRowsCSV($conexion, $matricula, $nombre, $apellidos, $grupo, $id_modalidad, $rol, $correo, $id_carrera);

                if ($salidaValidacion != "true") {
                    throw new Exception("Error: $salidaValidacion ($matricula)");
                }

                if (!InsertarUsuarioDB($conexion, $matricula, $nombre, $apellidos, $contraseña, $correo, $rol)) {
                    throw new Exception("Error al insertar usuario: $matricula");
                }

                if (!InsertarEstudianteDB($conexion, $matricula, $nombre, $apellidos, $id_carrera, $id_modalidad, $grupo)) {
                    throw new Exception("Error al insertar estudiante: $matricula");
                }
            }

            mysqli_commit($conexion);
            EstructuraMensaje("Datos insertados correctamente", "../../assets/iconos/ic_correcto.webp", "--verde");
        } catch (Exception $e) {
            mysqli_rollback($conexion);
            EstructuraMensaje($e->getMessage(), "../../assets/iconos/ic_error.webp", "--rojo");
        }

    }

    public function validarRowsCSV($conexion, $matricula, $nombre, $apellidos, $grupo, $id_modalidad, $rol, $correo, $id_carrera)
    {
        if (empty($matricula) || empty($nombre) || empty($apellidos) || empty($grupo) || empty($id_modalidad) || empty($rol) || empty($correo) || empty($id_carrera)) {
            return "Faltan datos obligatorios en la fila del CSV $matricula, $nombre, $apellidos, $grupo, $id_modalidad, $rol, $correo, $id_carrera ";
        }
        if (revisionCorreoEstudiante($correo)) {
            return "El correo ya existe";
        }
        if (revisionNombreCompleto($nombre, $apellidos)) {
            return "El nombre o apellidos no son validos";
        }
        if (revisionIdentificadorEstudiante($matricula)) {
            return "Esa matricula no es valida";
        }
        if (restriccionKeyDuplicada($matricula, $correo, $conexion)) {
            return "Ya existe la matricula o el correo";
        }
        if (revisarGrupoModalidadCSV($conexion, $id_modalidad, $grupo)) {
            return "El grupo no pertenece a la modalidad esperada";
        }
        return "true";
    }

    function ModificarUsuarioDB($conexion, $matricula, $nuevosDatos)
    {
        global $TABLA_USUARIO, $CAMPO_ID_USUARIO, $CAMPO_CORREO;

        mysqli_begin_transaction($conexion);

        if (empty($matricula)) {
            EstructuraMensaje("Tienes que elegir a un usuario para modificar su informacion", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        // Obtener datos actuales del usuario (incluyendo 'matricula')
        $usuarioActual = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $matricula);

        if (!$usuarioActual) {
            EstructuraMensaje("Usuario no está en el sistema", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $correoAntiguo = $usuarioActual[$CAMPO_CORREO];
        $correoNuevo = $nuevosDatos['correo'];

        $stmt = revisarModificacionCorreoEstudiante($conexion, $correoNuevo, $correoAntiguo, $matricula);

        if ($stmt && $stmt->num_rows > 0) {
            EstructuraMensaje("El correo ya está asociado con otro usuario", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $id_usuario = $nuevosDatos['clave'];
        $nombre = $nuevosDatos['nombre'];
        $apellidos = $nuevosDatos['apellidos'];
        $correo = $nuevosDatos['correo'];
        $id_modalidad = $nuevosDatos['modalidad'] == "Escolarizado" ? 1 : 2;
        $grupo = $nuevosDatos['grupo'];



        if (!ModificarDatosEstudianteDB($conexion, $id_usuario, $correo, $nombre, $apellidos, $grupo, $id_modalidad, $matricula)) {
            EstructuraMensaje("Ocurrio un problema con los datos personales", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }


        mysqli_commit($conexion);
        EstructuraMensaje("Se ha modificado los datos en la base de datos", "../../assets/iconos/ic_correcto.webp", "--verde");

    }

    public function eliminarRegistroEstudiante($conexion, $id)
    {
        $id = trim($id);
        if (!isset($_POST['identificador'])) {
            EstructuraMensaje("Busque y seleccione a un usuario", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (EliminarUsuarioDB($conexion, $id)) {
            EstructuraMensaje("El registro fue eliminado de forma exitosa", "../../assets/iconos/ic_correcto.webp", "--verde");
        } else {
            EstructuraMensaje("Ocurrio un error al eliminarlo", "../../assets/iconos/ic_error.webp", "--rojo");
        }

    }


    public function MostrarSolicitudes($conexion, $resultado, $id)
    {
        global $CAMPO_ID_ESTADO, $CAMPO_FECHA_AUSE;
        $tablaArray = [];
        $detallesArray = [];

        $tablaHead = componenteCabeceraTablaSolicitudes();

        $i = 1;
        while ($fila = $resultado->fetch_assoc()) {
            $indexFila = $i++;

            if ($fila[$CAMPO_ID_ESTADO] == "1") {
                $clase = "aceptada";
            } else if ($fila[$CAMPO_ID_ESTADO] == "2") {
                $clase = "pendiente";
            } else {
                $clase = "rechazada";
            }

            $valorFecha = $fila[$CAMPO_FECHA_AUSE];

            if (strpos($valorFecha, '/') !== false) {
                // Ya es un rango con "/"
                $fecha = $valorFecha;

            } elseif (strpos($valorFecha, ' al ') !== false) {
                // Es un rango con formato "DD-MM-YYYY al DD-MM-YYYY"
                $fechas = explode(' al ', $valorFecha);

                // Reemplazar guiones por barras en ambas fechas
                $inicio = str_replace('-', '/', trim($fechas[0]));
                $fin = str_replace('-', '/', trim($fechas[1]));

                $fecha = $inicio . ' al ' . $fin;

            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $valorFecha)) {
                // Fecha tipo YYYY-MM-DD
                $fecha = DateTime::createFromFormat('Y-m-d', $valorFecha);
                $fecha = $fecha ? $fecha->format('d/m/Y') : $valorFecha;

            } else {
                // Otro formato, solo reemplazar guiones por barras si los hay
                $fecha = str_replace('-', '/', $valorFecha);
            }


            $tabla = "";

            $tabla .= componenteFilaSolicitud($conexion, $indexFila, $fila, $id, $clase, $fecha);

            $detalles = componenteDetailSolicitud($conexion, $fila, $clase, $id);
            array_push($tablaArray, $tabla);
            array_push($detallesArray, $detalles);
        }

        array_push($tablaArray, $tablaHead);

        return [$tablaArray, $detallesArray];


    }

    public function HistorialJustificantes($conexion, $id_jefe)
    {
        global $CAMPO_FECHA_CREACION;
        $resultado = ObtenerJustificantesJefeCarrera($conexion, $id_jefe);

        if ($resultado->num_rows == 0) {
            componenteSinJustificantes();
        } else {
            $i = 1;
            while ($fila = $resultado->fetch_array()) {
                $index = $i++;
                $tiempo = explode(" ", $fila[$CAMPO_FECHA_CREACION]);
                $tiempo_fecha = explode("-", $tiempo[0]);

                componenteJustificanteJefe($conexion, $index, $fila, $tiempo_fecha);
            }
        }
    }

    public function ObtenerGruposDeLaCarrera($conexion, $id_carrera)
    {
        global $CAMPO_ID_GRUPOS, $CAMPO_NUMERO_GRUPOS;
        $GruposCarrera = obtenerGrupos($conexion, $id_carrera);
        $grupos = $GruposCarrera[0][0];
        $modalidades = $GruposCarrera[1][0]["Modalidades"];
        $id_grupos = $grupos["clave_grupo"];
        $Numero_grupos = $grupos["numero_grupos"];

        return [$id_grupos, $Numero_grupos, $modalidades];
    }

}