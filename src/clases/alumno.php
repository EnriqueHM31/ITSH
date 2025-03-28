<?php
include "../utils/constantes.php";

class alumno
{
    public function __alumno()
    {
    }

    public function ponerDatosFormulario($conexion, $id)
    {
        $sql = "SELECT * FROM " . Variables::TABLA_BD_ESTUDIANTE . " WHERE " . Variables::CAMPO_MATRICULA . " = '$id'";
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
                    $sql = "INSERT INTO " . Variables::TABLA_BD_SOLICITUDES . " (" . Variables::CAMPO_S_ID_SOLICITUD . ", " . Variables::CAMPO_S_MATRICULA . ", " . Variables::CAMPO_S_NOMBRE . ", " . Variables::CAMPO_S_APELLIDOS . ", " . Variables::CAMPO_S_GRUPO . ", " . Variables::CAMPO_S_CARRERA . ", " . Variables::CAMPO_S_MOTIVO . ", " . Variables::CAMPO_S_FECHA_AUSENCIA . ", " . Variables::CAMPO_S_EVIDENCIA . ", " . Variables::CAMPO_S_ESTADO . ") 
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

    public function HistorialJustificantes($conexion, $id)
    {
        $sql = "SELECT * FROM " . Variables::TABLA_BD_SOLICITUDES . " 
        WHERE " . Variables::CAMPO_S_MATRICULA . " = '$id'";


        $resultado = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($resultado) == 0) {
            echo "<p class='sin_justificantes'>No hay justificantes disponibles</p>";
        }
        $datos = mysqli_fetch_all($resultado, MYSQLI_ASSOC); // Obtener todos los datos como array asociativo

        for ($i = 0; $i < count($datos); $i++) {
            $fila = $datos[$i];
            $tiempo_fecha = explode("-", $fila["fecha_ausencia"]);
            echo "
                <div class='archivo' data-id='$fila[solicitud]'>
                    <h2> Solicitud " . ($i + 1) . " </h2>
                    <p> {$fila['matricula']} </p>
                    <p> {$fila['estado']} </p>
                    <span> {$tiempo_fecha[2]} / {$tiempo_fecha[1]} / {$tiempo_fecha[0]} </span>
                </div>";
        }
    }
}
