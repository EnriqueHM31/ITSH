<?php
include('phpqrcode/qrlib.php');
include "../conexion/verificar acceso.php";


function generarCodigo($conexion, $id, $id_estudiante, $fecha)
{
    global $URL_VERIFICAR_CODIGO_QR;
    // Parámetros para generar el string QR (puedes obtener estos valores dinámicamente, por ejemplo, de un formulario)
    try {
        // Generar el texto para el QR combinando ID, nombre (sin espacios) y fecha
        $fecha_nombre = str_replace('-', '_', $fecha);
        $qr_text = $id . "_" . $id_estudiante . "_" . $fecha_nombre;

        // Directorio para guardar la imagen del QR (se crea si no existe)
        $dir = '../layouts/Alumno/justificantes/codigos_qr/';
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $id_unico = uniqid();
        // Ruta de archivo para guardar el QR generado
        $filename = $dir . $id_unico . "_" . $qr_text . '.png';

        // Generar el código QR y guardarlo en el archivo indicado

        // URL de verificación (esto será el enlace a la página de verificación)
        $data = urlencode($qr_text);
        $url_verificacion = "$URL_VERIFICAR_CODIGO_QR/src/utils/verificarQR.php?qr_text='$data'";

        if (!class_exists('QRcode')) {
            throw new Exception("Faltan librerias para la creacion de codigos QR.");
        }

        QRcode::png($url_verificacion, $filename, QR_ECLEVEL_L, 4, true);

        $id_codigo = InsertarCodigoQRDB($conexion, $qr_text, $url_verificacion);

        if ($id_codigo) {
            return [$id_unico, $id_codigo];
        } else {
            throw new Exception("Ocurrio un error al insertar los datos en la DB");

        }
    } catch (Exception $e) {
        echo json_encode(["success" => "Error: {$e->getMessage()}"]);
    }

}
?>