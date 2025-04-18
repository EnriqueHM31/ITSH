<?php
include('phpqrcode/qrlib.php');
include "../conexion/verificar acceso.php";


function generarCodigo($conexion, $id, $nombre, $fecha, $valido)
{
    // Parámetros para generar el string QR (puedes obtener estos valores dinámicamente, por ejemplo, de un formulario)
    try {
        // Generar el texto para el QR combinando ID, nombre (sin espacios) y fecha
        $qr_text = $id . '_' . str_replace(' ', '_', $nombre) . '_' . str_replace('-', '_', $fecha);

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
        $url_verificacion = "http://192.168.1.104:8000/src/utils/verificarQR.php?qr_text=" . urlencode($qr_text);

        if (!class_exists('QRcode')) {
            throw new Exception("Faltan librerias para la creacion de codigos QR.");
        }

        QRcode::png($url_verificacion, $filename, QR_ECLEVEL_L, 4, true);

        $id_codigo = insertarCodigoQR($conexion, $qr_text, $valido, $url_verificacion);

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