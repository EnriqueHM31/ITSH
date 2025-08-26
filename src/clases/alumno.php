<?php
class alumno
{
    public function __alumno() {}

    public function ponerDatosFormulario($conexion, $id)
    {
        global $TABLA_USUARIO, $CAMPO_ID_USUARIO;
        return ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);
    }

    public function esFechaValida($fecha)
    {
        $fecha_actual = date("Y-m-d");
        return $fecha <= $fecha_actual;
    }

    /*public function enviarSolicitud($conexion, $id_jefe)
    {
        global $TABLA_SOLICITUDES, $CAMPO_ID_JEFE;
        mysqli_begin_transaction($conexion);

        $identificador = $_POST['identificador'];
        $motivo = $_POST['motivo'];
        if ($_POST['fecha_ausencia'] != null) {
            $fecha = $_POST['fecha_ausencia'];

            if (!$this->esFechaValida($fecha)) {
                EstructuraMensaje("La fecha no puede ser posterior al día actual", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
        } else if ($_POST['rango_fechas']) {
            $fecha = $_POST['rango_fechas'];
        } else {
            EstructuraMensaje("La fecha que ingresaste esta mal, vuelve a intentarlo?", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $carrera = str_replace(' ', '', $_POST['carrera']);

        $id_estado = 2;
        try {

            if (!isset($_FILES['archivo_evidencia']) || $_FILES['archivo_evidencia']['error'] !== 0) {
                EstructuraMensaje("Sube tu evidencia", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }

            $archivo_tmp = $_FILES['archivo_evidencia']['tmp_name'];
            $archivo_tipo = mime_content_type($archivo_tmp);

            if ($archivo_tipo !== 'application/pdf') {
                EstructuraMensaje("Tu evidencia debe estar en formato PDF", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }

            $sql = "SELECT COUNT(*) as total FROM $TABLA_SOLICITUDES WHERE $CAMPO_ID_JEFE = ?";
            $smtm = $conexion->prepare($sql);
            $smtm->bind_param("s", $id_jefe);
            $smtm->execute();
            $result = $smtm->get_result();
            $totalEvidencia = $result->fetch_assoc();
            $NumeroEvidencia = $totalEvidencia['total'] + 1;

            $nombre_carpeta = "evidencias/$carrera"; // Nombre de la carpeta que quieres crear

            // Verificar si la carpeta ya existe
            if (!is_dir($nombre_carpeta)) {
                // Crear la carpeta con permisos 0777 (lectura, escritura y ejecución para todos)
                mkdir($nombre_carpeta, 0777, true);
            } else {
            }


            $identificador_archivo = "evidencia$NumeroEvidencia.pdf";
            $archivo_destino = "$nombre_carpeta/$identificador_archivo";

            if (!move_uploaded_file($archivo_tmp, $archivo_destino)) {
                EstructuraMensaje("Ocurrió un error con el archivo", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
            if (!InsertarSolicitudDB($conexion, $identificador, $id_jefe, $motivo, $fecha, $identificador_archivo, $id_estado)) {
                EstructuraMensaje("Ocurrió un error con el envío de la solicitud", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }

            mysqli_commit($conexion);
            EstructuraMensaje("Se ha enviado la solicitud a tu jefe de carrera", "../../assets/iconos/ic_correcto.webp", "--verde");
        } catch (Exception $e) {

            if (file_exists($archivo_destino)) {
                unlink($archivo_destino);
            }

            EstructuraMensaje("Ocurrió un error con el envío de la solicitud", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }
    }*/

    public function enviarSolicitud($conexion, $id_jefe, $matricula, $grupo, $nombre, $apellidos, $carrera, $motivo, $fecha, $archivo)
    {
        global $TABLA_SOLICITUDES, $CAMPO_ID_JEFE;
        $id_estado = 2;


        if (empty($fecha)) {
            EstructuraMensaje("Debes rellenar todos los campos", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        mysqli_begin_transaction($conexion);
        try {

            $archivo_tmp = $archivo['tmp_name'];
            $archivo_tipo = mime_content_type($archivo_tmp);

            if ($archivo_tipo !== 'application/pdf') {
                EstructuraMensaje("Tu evidencia debe estar en formato PDF", "../../assets/iconos/ic_error.webp", "--rojo");
                return "Tu evidencia debe estar en formato PDF";
            }

            $sql = "SELECT COUNT(*) as total FROM $TABLA_SOLICITUDES WHERE $CAMPO_ID_JEFE = ?";
            $smtm = $conexion->prepare($sql);
            $smtm->bind_param("s", $id_jefe);
            $smtm->execute();
            $result = $smtm->get_result();
            $totalEvidencia = $result->fetch_assoc();
            $NumeroEvidencia = $totalEvidencia['total'] + 1;

            $rutaGuardado = "../../layouts/Alumno/evidencias/$carrera/";

            $identificador_archivo = "evidencia$NumeroEvidencia.pdf";

            if (!guardarEvidencia($archivo, $rutaGuardado, $identificador_archivo)) {
                EstructuraMensaje("Ocurrió un error al guardar el archivo.", "../../assets/iconos/ic_error.webp", "--rojo");
            }

            var_dump($fecha);


            if (!InsertarSolicitudDB($conexion, $matricula, $id_jefe, $motivo, $fecha, $identificador_archivo, $id_estado)) {
                EstructuraMensaje("Ocurrió un error con el envío de la solicitud", "../../assets/iconos/ic_error.webp", "--rojo");
                return "Ocurrió un error con el envío de la solicitud 3";
            }

            mysqli_commit($conexion);
            EstructuraMensaje("Se ha enviado la solicitud a tu jefe de carrera", "../../assets/iconos/ic_correcto.webp", "--verde");
            return "Se ha enviado la solicitud a tu jefe de carrera";
        } catch (Exception $e) {

            EstructuraMensaje($e->getMessage(), "../../assets/iconos/ic_error.webp", "--rojo");
        }
    }

    public function verificarPOST()
    {

        if ($_FILES["archivo_evidencia"]["size"] > 0) {
            foreach ($_POST as $value) {
                if (empty($value)) {
                    EstructuraMensaje("Rellene todos los campos para registrar $value", "../../assets/iconos/ic_error.webp", "--rojo");
                    return true;
                }
            }
        } else {
            EstructuraMensaje("Rellene todos los campos para registrar {$_FILES['archivo_evidencia']['tmp_name']}", "../../assets/iconos/ic_error.webp", "--rojo");
            return true;
        }
        return false;
    }

    public function HistorialJustificantes($conexion, $matricula)
    {
        global $CAMPO_FECHA_AUSE;

        $dataJustificantesAlumno = BuscarHistorialJustificantesAlumno($conexion, $matricula);

        if ($dataJustificantesAlumno->num_rows === 0) {
            componenteSinSolicitudes();
        } else {
            $i = 0;
            while ($fila = $dataJustificantesAlumno->fetch_assoc()) {
                $i++;
                $valorFecha = $fila[$CAMPO_FECHA_AUSE];

                if (strpos($valorFecha, '/') !== false) {
                    // Ya es un rango con "/"
                    $fechaFormateada = $valorFecha;
                } elseif (strpos($valorFecha, ' al ') !== false) {
                    // Es un rango con formato "DD-MM-YYYY al DD-MM-YYYY"
                    $fechas = explode('al', $valorFecha);

                    // Reemplazar guiones por barras en ambas fechas
                    $inicio = str_replace('-', '/', trim($fechas[0]));
                    $fin = str_replace('-', '/', trim($fechas[1]));

                    $fechaFormateada = $inicio . ' al ' . $fin;
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $valorFecha)) {
                    // Fecha tipo YYYY-MM-DD
                    $fecha = DateTime::createFromFormat('Y-m-d', $valorFecha);
                    $fechaFormateada = $fecha ? $fecha->format('d/m/Y') : $valorFecha;
                } else {
                    // Otro formato, solo reemplazar guiones por barras si los hay
                    $fechaFormateada = str_replace('-', '/', $valorFecha);
                }

                componenteJustificanteHistorial($conexion, $fila, $i, $fechaFormateada);
            }
        }
    }
}
