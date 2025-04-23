<?php
class alumno
{
    public function __alumno()
    {
    }

    public function ponerDatosFormulario($conexion, $id)
    {
        global $TABLA_USUARIO, $CAMPO_ID_USUARIO;
        return getResultDataTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);
    }

    public function esFechaValida($fecha)
    {
        $fecha_actual = date("Y-m-d");
        return $fecha <= $fecha_actual;
    }

    public function enviarSolicitud($conexion, $id_jefe)
    {
        mysqli_begin_transaction($conexion);

        if ($this->verificarPOST()) {
            return;
        }

        $identificador = $_POST['identificador'];
        $motivo = $_POST['motivo'];
        $fecha = $_POST['fecha_ausencia'];
        $id_estado = 2;
        try {
            if (!$this->esFechaValida($fecha)) {
                estructuraMensaje("La fecha no puede ser posterior al día actual", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }

            if (!isset($_FILES['archivo_evidencia']) || $_FILES['archivo_evidencia']['error'] !== 0) {
                estructuraMensaje("Sube tu evidencia", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }

            $archivo_tmp = $_FILES['archivo_evidencia']['tmp_name'];
            $archivo_tipo = mime_content_type($archivo_tmp);

            if ($archivo_tipo !== 'application/pdf') {
                estructuraMensaje("Tu evidencia debe estar en formato PDF", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }

            $sql = "SELECT COUNT(*) as total FROM solicitud";
            $smtm = $conexion->prepare($sql);
            $smtm->execute();
            $result = $smtm->get_result();
            $totalEvidencia = $result->fetch_assoc();
            $NumeroEvidencia = $totalEvidencia['total'] + 1;

            $identificador_archivo = "evidencia$NumeroEvidencia.pdf";
            $archivo_destino = 'evidencias/' . $identificador_archivo;

            if (!move_uploaded_file($archivo_tmp, $archivo_destino)) {
                estructuraMensaje("Ocurrió un error con el archivo", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }
            if (!insertarSolicitudBD($conexion, $identificador, $id_jefe, $motivo, $fecha, $identificador_archivo, $id_estado)) {
                estructuraMensaje("Ocurrió un error con el envío de la solicitud", "../../assets/iconos/ic_error.webp", "--rojo");
                return;
            }

            mysqli_commit($conexion);
            estructuraMensaje("Se ha enviado la solicitud a tu jefe de carrera", "../../assets/iconos/ic_correcto.webp", "--verde");
        } catch (Exception $e) {

            if (file_exists($archivo_destino)) {
                unlink($archivo_destino);
            }

            estructuraMensaje("Ocurrió un error con el envío de la solicitud", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }
    }

    public function verificarPOST()
    {

        if ($_FILES["archivo_evidencia"]["size"] > 0) {
            foreach ( $_POST as $value ) {
                if (empty($value)) {
                    estructuraMensaje("Rellene todos los campos para registrar $value", "../../assets/iconos/ic_error.webp", "--rojo");
                    return true;
                }
            }
        } else {
            estructuraMensaje("Rellene todos los campos para registrar {$_FILES['archivo_evidencia']['tmp_name']}", "../../assets/iconos/ic_error.webp", "--rojo");
            return true;
        }
    }

    public function HistorialJustificantes($conexion, $matricula)
    {
        global $CAMPO_FECHA_AUSE;

        $dataJustificantesAlumno = buscarHistorialJustificantesAlumno($conexion, $matricula);

        if ($dataJustificantesAlumno->num_rows === 0) {
            componenteSinSolicitudes();
        } else {
            $i = 0;
            while ($fila = $dataJustificantesAlumno->fetch_assoc()) {
                $i++;
                $tiempo_fecha = explode("-", $fila[$CAMPO_FECHA_AUSE]);
                componenteJustificanteHistorial($conexion, $fila, $i, $tiempo_fecha);
            }
        }


    }
}

