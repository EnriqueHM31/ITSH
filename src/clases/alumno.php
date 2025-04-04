<?php
class alumno
{
    public function __alumno()
    {
    }

    public function ponerDatosFormulario($conexion, $id)
    {
        global $TABLA_ESTUDIANTE, $CAMPO_MATRICULA;

        return getResultDataTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_MATRICULA, $id);
    }

    public function esFechaValida($fecha)
    {
        $fecha_actual = date("Y-m-d");
        return $fecha <= $fecha_actual;
    }

    public function enviarSolicitud($conexion)
    {
        mysqli_begin_transaction($conexion);

        if ($this->verificarPOST()) {
            return;
        }

        $identificador = $_POST['identificador'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $grupo = $_POST['grupo'];
        $carrera = $_POST['carrera'];
        $motivo = $_POST['motivo'];
        $fecha = $_POST['fecha_ausencia'];
        $estado = 2;

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

        $fecha_nombre = str_replace("-", "", $fecha);
        $identificador_archivo = $fecha_nombre . ".pdf";
        $archivo_destino = 'evidencias/' . $identificador_archivo;

        if (!move_uploaded_file($archivo_tmp, $archivo_destino)) {
            estructuraMensaje("Ocurrió un error con el archivo", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (!insertarSolicitudBD($conexion, $identificador, $nombre, $apellidos, $grupo, $carrera, $motivo, $fecha, $identificador_archivo, $estado)) {
            estructuraMensaje("Ocurrió un error con el envío de la solicitud", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        mysqli_commit($conexion);
        estructuraMensaje("Se ha enviado la solicitud a tu jefe de carrera", "../../assets/iconos/ic_correcto.webp", "--verde");

    }

    public function verificarPOST()
    {

        if ($_FILES["archivo_evidencia"]["size"] > 0) {
            foreach ( $_POST as $value ) {
                if (empty($value)) {
                    estructuraMensaje("Rellene todos los campos para registrar", "../../assets/iconos/ic_error.webp", "--rojo");
                    return true;
                }
            }
        } else {
            estructuraMensaje("Rellene todos los campos para registrar", "../../assets/iconos/ic_error.webp", "--rojo");
            return true;
        }
    }

    public function HistorialJustificantes($conexion, $matricula)
    {
        global $CAMPO_FECHA_AUSE, $CAMPO_ID_SOLICITUD, $CAMPO_MATRICULA, $CAMPO_ESTADO;

        $dataJustificantesAlumno = buscarHistorialJustificantesAlumno($conexion, $matricula);

        if ($dataJustificantesAlumno->num_rows === 0) {
            echo "<p class='sin_justificantes'>No hay solicitudes disponibles</p>";
        } else {
            $i = 0;
            while ($fila = $dataJustificantesAlumno->fetch_assoc()) {
                $i++;
                $tiempo_fecha = explode("-", $fila[$CAMPO_FECHA_AUSE]);
                echo "
                    <div class='archivo' data-id='{$fila[$CAMPO_ID_SOLICITUD]}'>
                        <h2> Solicitud {$i} </h2>
                        <p> {$fila[$CAMPO_MATRICULA]} </p>
                        <p> {$fila[$CAMPO_ESTADO]} </p>
                        <span> {$tiempo_fecha[2]} / {$tiempo_fecha[1]} / {$tiempo_fecha[0]} </span>
                    </div>
                ";
            }
        }


    }
}

