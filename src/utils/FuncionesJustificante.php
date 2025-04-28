<?php
function obtenerFechaActual()
{
    global $MESES;
    $date = new DateTime(); // Obtiene la fecha actual
    $dia = $date->format('d'); // Día en dos dígitos
    $mes = $MESES[intval($date->format('n')) - 1];
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

function obtenerCodigoQR($id_unico, $id_justificante, $id_estudiante, $fecha)
{
    // IMAGEN DEL QR
    $qr_text = $id_justificante . '_' . $id_estudiante . '_' . str_replace('-', '_', $fecha);
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

function guardarArchivoPDF($conexion, $data, $id_justificante, $id_estudiante)
{
    global $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $CAMPO_ID_CARRERA;
    // Ruta donde se guardará el archivo
    $dataEstudiante = ObtenerDatosDeUnaTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $id_estudiante);
    $carrera = ObtenerNombreCarrera($conexion, $dataEstudiante[$CAMPO_ID_CARRERA]);
    $carrera = str_replace(" ", "", $carrera);


    $rutaGuardado = "../layouts/Alumno/justificantes/$carrera/";
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
    global $MESES;
    $array = explode("-", $fecha);
    $mes = $MESES[intval($array[1][1])];
    return $array[0] . " de " . ucfirst($mes) . " de " . $array[2];
}


function EliminarCodigoQR($id_solicitud, $id_estudiante, $fecha_codigo, $id_unico)
{
    $qr_text = $id_solicitud - 1 . '_' . $id_estudiante . '_' . str_replace('-', '_', $fecha_codigo);

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

function obtenerIniciales($texto)
{
    // Dividir el texto en palabras
    $palabras = explode(' ', $texto);
    $iniciales = '';

    foreach ( $palabras as $palabra ) {
        if (!empty($palabra)) {
            // Tomar la primera letra y convertirla en mayúscula
            $iniciales .= strtoupper($palabra[0]);
        }
    }

    return $iniciales;
}

function obtenerAñoActual()
{
    return date('Y');
}



?>