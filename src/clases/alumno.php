<?php


class alumno
{
    public function __alumno() {}

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
        $estado = 0;

        if (isset($_FILES['archivo_evidencia']) && $_FILES['archivo_evidencia']['error'] == 0) {
            $archivo_tmp = $_FILES['archivo_evidencia']['tmp_name'];
            $archivo_tipo = mime_content_type($archivo_tmp);

            if ($archivo_tipo == 'application/pdf') {
                $identificador_archivo = uniqid() . ".pdf";
                $archivo_destino = 'evidencias/' . $identificador_archivo;

                if (move_uploaded_file($archivo_tmp, $archivo_destino)) {
                    $sql = "INSERT INTO solicitudes (folio, identificador, nombre, apellidos, grupo, carrera, motivo, fecha_ausencia, evidencia, estado) 
                        VALUES (null, '$identificador', '$nombre', '$apellidos', '$grupo', '$carrera', '$motivo', '$fecha', '$identificador_archivo', '$estado')";

                    if ($conexion->query($sql) === TRUE) {
                        $_SESSION["mensaje"] = "Se ha enviado la solicitud a tu jefe de carrera";
                        $_SESSION["icono"] = "../img/iconos/ic_correcto.png";
                        $_SESSION["color_mensaje"] = "var(--verde)";
                    } else {
                        $_SESSION["mensaje"] = "Ocurrio un error con el envio de la solicitud";
                        $_SESSION["icono"] = "../img/iconos/ic_error.png";
                        $_SESSION["color_mensaje"] = "var(--rojo)";
                    }
                } else {
                    $_SESSION["mensaje"] = "Ocurrio un error con el archivo";
                    $_SESSION["icono"] = "../img/iconos/ic_error.png";
                    $_SESSION["color_mensaje"] = "var(--rojo)";
                }
            } else {
                $_SESSION["mensaje"] = "Tu evidencia debe estar en formato PDF";
                $_SESSION["icono"] = "../img/iconos/ic_error.png";
                $_SESSION["color_mensaje"] = "var(--rojo)";
            }
        } else {
            $_SESSION["mensaje"] = "Sube tu evidencia";
            $_SESSION["icono"] = "../img/iconos/ic_error.png";
            $_SESSION["color_mensaje"] = "var(--rojo)";
        }
    }

    public function verificarPOST()
    {

        if ($_FILES["archivo_evidencia"]["size"] > 0) {
            foreach ($_POST as $value) {
                if (empty($value)) {
                    $_SESSION["mensaje"] = "Rellene todos los campos para registrar";
                    $_SESSION["icono"] = "../img/iconos/ic_error.png";
                    $_SESSION["color_mensaje"] = "var(--rojo)";
                    return true;
                }
            }
        } else {
            $_SESSION["mensaje"] = "Rellene todos los campos para registrar";
            $_SESSION["icono"] = "../img/iconos/ic_error.png";
            $_SESSION["color_mensaje"] = "var(--rojo)";
            return true;
        }
    }
}
