<?php
function obtenerFechaActual()
{
    $date = new DateTime(); // Obtiene la fecha actual
    $dia = $date->format('d'); // Día en dos dígitos
    $mes = Variables::MESES[$date->format('n') - 1]; // Obtiene el nombre del mes
    $año = $date->format('Y'); // Año en cuatro dígitos
    return "$dia de $mes de $año";
}

function obtenerIMGLogos()
{
    // LOGOS DE LA ESCUELA
    $ruta_logos = $_SERVER['DOCUMENT_ROOT'] . "/src/assets/justificantes/logos.jpg";
    // Verificar si existe el logo
    if (!file_exists($ruta_logos)) {
        throw new Exception("Archivo de logos no encontrado: $ruta_logos");
    }
    $imagen_base64 = base64_encode(file_get_contents($ruta_logos));
    return 'data:image/jpeg;base64,' . $imagen_base64;
}

function obtenerCodigoQR($id_unico, $id_justificante, $nombre, $fecha)
{
    // IMAGEN DEL QR
    $qr_text = $id_justificante . '_' . str_replace(' ', '_', $nombre) . '_' . str_replace('-', '_', $fecha);
    $filename = $id_unico . "_" . $qr_text . '.png';
    $ruta_imagen = $_SERVER['DOCUMENT_ROOT'] . "/src/layouts/Alumno/justificantes/codigos_qr/$filename";

    // Verificar si existe el QR antes de leerlo
    if (!file_exists($ruta_imagen)) {
        throw new Exception("Archivo QR no generado");
    }

    $imagen_contenido = file_get_contents($ruta_imagen);
    if ($imagen_contenido === false) {
        throw new Exception("Error al leer el archivo QR");
    }

    $imagen_base64 = base64_encode($imagen_contenido);
    return "data:image/png;base64,$imagen_base64";
}

function guardarArchivoPDF($data, $id_justificante, $id_estudiante)
{
    // Ruta donde se guardará el archivo
    $rutaGuardado = "../layouts/Alumno/justificantes/";
    if (!file_exists($rutaGuardado)) {
        mkdir($rutaGuardado, 0777, true);
    }

    $nombreArchivo = "justificante_" . $id_justificante . "_" . $id_estudiante . "_" . date("Ymd_His") . ".pdf";
    $rutaArchivo = $rutaGuardado . $nombreArchivo;

    // Guardar el PDF en la carpeta especificada
    file_put_contents($rutaArchivo, $data);

    return $nombreArchivo;
}

function estructurarFechaAusencia($fecha)
{
    $array = explode("-", $fecha);
    return $array[0] . " de " . ucfirst(Variables::MESES[$array[1][1]]) . " de " . $array[2];
}


function EliminarCodigoQR($id_solicitud, $nombre, $fecha_codigo, $id_unico)
{
    $qr_text = $id_solicitud - 1 . '_' . str_replace(' ', '_', $nombre) . '_' . str_replace('-', '_', $fecha_codigo);

    // Directorio para guardar la imagen del QR (se crea si no existe)
    $dir = '../layouts/Alumno/justificantes/codigos_qr/';
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
    // Ruta de archivo para guardar el QR generado
    $filename = $dir . $id_unico . "_" . $qr_text . '.png';
    if (file_exists($filename)) {
        unlink($filename);
    }
}