<?php


class alumno
{
    public function __alumno()
    {
    }

    public function ponerDatosFormulario($conexion, $id)
    {
        $sql = "SELECT * FROM estudiante WHERE matricula = '$id'";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    }

    public function enviarSolicitud($conexion)
    {

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

        if (isset($_FILES['archivo_evidencia']) && $_FILES['archivo_evidencia']['error'] == 0) {
            $archivo_tmp = $_FILES['archivo_evidencia']['tmp_name'];
            $archivo_tipo = mime_content_type($archivo_tmp);

            if ($archivo_tipo == 'application/pdf') {
                $fecha_nombre = str_replace("-", "", $fecha);
                $identificador_archivo = $fecha_nombre . ".pdf";
                $archivo_destino = 'evidencias/' . $identificador_archivo;

                if (move_uploaded_file($archivo_tmp, $archivo_destino)) {
                    $sql = "INSERT INTO solicitudes (solicitud, matricula, nombre, apellidos, grupo, carrera, motivo, fecha_ausencia, evidencia, estado) 
                        VALUES (null, '$identificador', '$nombre', '$apellidos', '$grupo', '$carrera', '$motivo', '$fecha', '$identificador_archivo', '$estado')";

                    if ($conexion->query($sql) === TRUE) {
                        estructuraMensaje("Se ha enviado la solicitud a tu jefe de carrera", "../../assets/iconos/ic_correcto.webp", "--verde");
                    } else {
                        estructuraMensaje("Ocurrio un error con el envio de la solicitud", "../../assets/iconos/ic_error.webp", "--rojo");
                    }
                } else {
                    estructuraMensaje("Ocurrio un error con el archivo", "../../assets/iconos/ic_error.webp", "--rojo");
                }
            } else {
                estructuraMensaje("Tu evidencia debe estar en formato PDF", "../../assets/iconos/ic_error.webp", "--rojo");
            }
        } else {
            estructuraMensaje("Sube tu evidencia", "../../assets/iconos/ic_error.webp", "--rojo");
        }
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


}
