<?php

include('phpqrcode/qrlib.php');


function generarCodigo($conexion, $id, $nombre, $fecha)
{
    // Parámetros para generar el string QR (puedes obtener estos valores dinámicamente, por ejemplo, de un formulario)
    $id = 123; // Ejemplo de ID
    $nombre = "Juan Perez"; // Ejemplo de nombre
    $fecha = date('Y-m-d'); // Fecha actual en formato Año-Mes-Día

    // Generar el texto para el QR combinando ID, nombre (sin espacios) y fecha
    $qr_text = $id . '_' . str_replace(' ', '_', $nombre) . '_' . $fecha;

    // Directorio para guardar la imagen del QR (se crea si no existe)
    $dir = '../layouts/Alumno/justificantes/codigos_qr';
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
    $filename = $dir . 'qr_' . $qr_text . '.png';

    // Generar el código QR y guardarlo en el archivo indicado
// Parámetros: contenido, archivo destino, nivel de corrección de error y tamaño del punto
    QRcode::png($qr_text, $filename, QR_ECLEVEL_L, 4);

    // Conexión a la base de datos (modifica host, dbname, usuario y contraseña según tu configuración)
    try {
        $sql = "INSERT INTO CODIGOS_QR (texto, valido) VALUES (:texto, :valido)";
        $smtm = $conexion->prepare($sql);
        $smtm->bind_param("ss", $qr_text, 1);
        if ($smtm->execute()) {
            return "true";
        } else {
            return "false";
        }



    } catch (PDOException $e) {
        echo "Error en la conexión o inserción: " . $e->getMessage();
    }
}
?>