<?php

include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "./creacionQR.php";


if (isset($_POST["id_solicitud"]) && isset($_POST['matricula'], $_POST['nombre'], $_POST['apellidos'], $_POST['grupo'], $_POST['motivo'], $_POST['fecha'])) {
    try {
        mysqli_begin_transaction($conexion);

        $id_folio = obtenerNumeroFolio($conexion);
        $id_solicitud = $_POST["id_solicitud"];
        $nombre = $_POST['nombre'];
        $fecha = $_POST['fecha'];
        $matricula = $_POST['matricula'];
        $apellidos = $_POST['apellidos'];
        $grupo = $_POST['grupo'];
        $motivo = $_POST['motivo'];
        $id_jefe = $_POST['id_jefe'];
        $fecha_codigo = $fecha;

        $id_unico = generarCodigo($conexion, $id_folio, $nombre, $fecha, true);

        $datos_jefe = ObtenerDatosJustificanteJefe($conexion, $id_jefe);
        $nombre_jefe = $datos_jefe[0];
        $apellidos_jefe = $datos_jefe[1];
        $carrera = $datos_jefe[2];

        $fecha_actual = obtenerFechaActual();

        $src = obtenerIMGLogos();
        $src_qr = obtenerCodigoQR($id_unico, $id_folio, $nombre, $fecha);

        $fecha = estructurarFechaAusencia($fecha);


    } catch (Exception $e) {
        return json_encode(["sin_error" => $e->getMessage()]);
    }

    // Capturar el HTML en un buffer
    ob_start();
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}
?>

<html>

<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            width: 75%;
            margin: 0 auto;
            padding: 20px;
            padding-right: 40px;
        }

        .justificante {
            width: 100%;
            background-color: white;
            padding: 20px;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .escuela_detalle {
            float: right;
        }

        .escuela {
            font-size: 12px;
            margin-top: 2px;
            float: right;
            text-align: end;

        }

        .carrera {
            font-size: 12px;
            margin-top: 18px;
            float: right;
            text-align: end;
        }

        .contenido_justificante {
            width: 100%;
            margin: 20px 0;
        }

        h2 {
            font-size: 16px;
            text-align: center;
            display: block;
            margin-top: 40px;
        }

        .folio {
            text-align: right;
            margin: 10px 0;
            margin-top: 20px;
        }

        .folio span {
            background-color: #666;
            color: white;
            padding: 3px 10px;
            font-size: 16px;
        }

        .detalles {
            width: 100%;
            margin: 10px 0;
            text-align: right;
        }

        .data_justificante {
            width: 100%;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 12px;
        }

        .aclaracion {
            margin-bottom: 5px;
        }

        table td {
            border: 1px solid #000;
            padding: 8px;
        }


        footer {
            font-size: 14px;
            text-align: center;
            margin-top: 30px;
        }

        .datos_jefe {
            font-size: 14px;
            text-align: center;
            margin-top: 30px;
            text-transform: uppercase;
            font-weight: bold;
        }

        footer>* {
            margin-top: 4px;
        }

        .datos_jefe p:nth-child(3) {
            font-weight: normal;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="justificante">

        <img src="<?php echo $src; ?>" style="width: 50%; display: block;">

        <div class="escuela_detalle">
            <p class="escuela">Instituto Tecnologico Superior de Huatusco</p>
            <p class="carrera"> <?php echo Variables::TIPO_CARRERA[$carrera] . " " . $carrera ?></p>
        </div>



        <h2>JUSTIFICANTE DE INASISTENCIA DE CLASES</h2>

        <p class="folio"><strong>Folio: </strong>
            <span>
                ISC/
                <?php
                $id_folio = $id_folio + 1;
                echo (strlen($id_folio) == 1) ? "0" . $id_folio : $id_folio; ?>
                /2025
            </span>
        </p>

        <div class="detalles">
            <p>Huatusco de Chicuellar, Ver a <?php echo $fecha_actual ?></p>
        </div>

        <div class="data_justificante">
            <p>Por este conducto le saludo amablemente y solicito justifique la inasistencia de: </p>

            <table>
                <tbody>

                    <tr>
                        <td colspan="2">
                            <p><strong>Nombre del Alumno(a):</strong> <?php echo $nombre . " " . $apellidos ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p><strong>Grupo:</strong> <?php echo $grupo ?></p>
                        </td>
                        <td>
                            <p><strong>Num. de Control:</strong> <?php echo $matricula ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p><strong>Carrera: </strong><?php echo $carrera ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p><strong>Fecha de Falta: </strong><?php echo $fecha ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p><strong>Motivo de Falta: </strong><?php echo $motivo ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p class="aclaracion">Dejando a su consideracion las entregas de trabajo, presentacion de exposiciones,
                realizacion de examenes
                o cualquier otra actividad correspondiente de su materia.</p>

            <p>Sin mas por el momento, reciba un cordial saludo</p>
        </div>


        <div style="text-align: center;">
            <img src='<?php echo $src_qr ?>' alt="Código QR" style="margin: 0 auto;">
        </div>
        <div class="datos_jefe">
            <p>ATENTAMENTE</p>

            <p>MRT(A). <?php echo $nombre_jefe . " " . $apellidos_jefe ?></p>

            <p>JEFE(A) DE LA DIVISION DE <?php echo Variables::TIPO_CARRERA[$carrera] . " en " . $carrera ?></p>

        </div>

    </div>

    <footer>
        <p>Av .25 Poniente No. 100. Colonia Reserva Territorial, Huatusco, Veracruz</p>
        <p>94106, Tel. 273 734 4000 Ext. 238</p>
        <p>e-mail: pesistemas@huatusco.tecnm.mx www.itshuatusco.edu.mex</p>
    </footer>
</body>

</html>

<?php

require_once "./dompdf/autoload.inc.php";
use Dompdf\Dompdf;
try {
    $html = ob_get_clean(); // Captura el contenido y limpia el buffer

    // Cargar DOMPDF

    $pdf = new Dompdf();
    $options = $pdf->getOptions();
    $options->set("isRemoteEnabled", true);  
    $pdf->setOptions($options);

    $pdf->loadHtml($html); // Cargar el HTML generado
    $pdf->setPaper("letter", "portrait");
    $pdf->render();

    $data = $pdf->output();

    $nombreArchivo = guardarArchivoPDF($data, $id_solicitud, $matricula);

    ModificarEstadoSolicitud($conexion, $id_solicitud);

    InsertarTablaJustificante($conexion, $id_solicitud, $matricula, $nombre, $apellidos, $motivo, $grupo, $carrera, $nombre_jefe, $apellidos_jefe, $nombreArchivo);

    EliminarCodigoQR($id_folio, $nombre, $fecha_codigo, $id_unico);

    mysqli_commit($conexion);

} catch (Exception $e) {
    // Rollback en caso de error
    $conexion->rollback();
    // Eliminar archivos generados
    if (isset($ruta_imagen) && file_exists($ruta_imagen)) {
        unlink($ruta_imagen);
    }
    if (isset($rutaArchivo) && file_exists($rutaArchivo)) {
        unlink($rutaArchivo);
    }
    // Respuesta de error
    echo json_encode([
        "sin_error" => "Error al crear el PDF: " . $e->getMessage(),
    ]);
    exit();
}


function obtenerNumeroFolio($conexion)
{
    $sql = "SELECT COUNT(*) AS total FROM Justificante";
    $result = $conexion->query($sql);
    $row = $result->fetch_assoc();
    return $row["total"];
}

function ObtenerDatosJustificanteJefe($conexion, $id_jefe)
{
    try {

        $data = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, $id_jefe);

        $nombre_jefe = $data[Variables::CAMPO_NOMBRE];
        $apellidos_jefe = $data[Variables::CAMPO_APELLIDOS];
        $id_carrera = $data[Variables::CAMPO_ID_CARRERA];
        $carrera = getResultCarrera($conexion, $id_carrera);

        return [$nombre_jefe, $apellidos_jefe, $carrera];
    } catch (Exception $e) {
        echo json_encode([
            "sin_error" => "Error en la obtencion de datos del jefe(a) de carrera"
        ]);
        exit();
    }
}

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

function obtenerCodigoQR($id_unico, $id_folio, $nombre, $fecha)
{
    // IMAGEN DEL QR
    $qr_text = $id_folio . '_' . str_replace(' ', '_', $nombre) . '_' . str_replace('-', '_', $fecha);
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
    return 'data:image/png;base64,' . $imagen_base64;
}

function guardarArchivoPDF($data, $id_solicitud, $matricula)
{
    // Ruta donde se guardará el archivo
    $rutaGuardado = "../layouts/Alumno/justificantes/";
    if (!file_exists($rutaGuardado)) {
        mkdir($rutaGuardado, 0777, true);
    }

    $nombreArchivo = "justificante_" . $id_solicitud . "_" . $matricula . "_" . date("Ymd_His") . ".pdf";
    $rutaArchivo = $rutaGuardado . $nombreArchivo;

    // Guardar el PDF en la carpeta especificada
    file_put_contents($rutaArchivo, $data);

    return $nombreArchivo;
}

function estructurarFechaAusencia($fecha)
{
    $array = explode("-", $fecha);
    return $array[2] . " de " . ucfirst(Variables::MESES[$array[1][1]]) . " de " . $array[0];
}

function ModificarEstadoSolicitud($conexion, $id_solicitud)
{
    $sql = "UPDATE " . Variables::TABLA_BD_SOLICITUDES . " SET " . Variables::CAMPO_S_ESTADO . " = 'Aceptada' WHERE " . Variables::CAMPO_S_ID_SOLICITUD . " = ?";

    $smtm = $conexion->prepare($sql);
    $smtm->bind_param("i", $id_solicitud);
    $smtm->execute();
}

function InsertarTablaJustificante($conexion, $id_solicitud, $matricula, $nombre, $apellidos, $motivo, $grupo, $carrera, $nombre_jefe, $apellidos_jefe, $nombreArchivo)
{
    $sql = "INSERT INTO " . Variables::TABLA_BD_JUSTIFICANTES . "("
        . Variables::CAMPO_J_ID_SOLICITUD . ", "
        . Variables::CAMPO_J_MATRICULA . ", "
        . Variables::CAMPO_J_NOMBRE . ", "
        . Variables::CAMPO_J_APELLIDOS . ", "
        . Variables::CAMPO_J_MOTIVO . ", "
        . Variables::CAMPO_J_GRUPO . ", "
        . Variables::CAMPO_J_CARRERA . ", "
        . Variables::CAMPO_J_NOMBRE_JEFE . ", "
        . Variables::CAMPO_J_JUSTIFICANTE . ") VALUES (?,?,?,?,?,?,?,?,?)";

    $nombre_jefe_completo = $nombre_jefe . " " . $apellidos_jefe;

    $smtm = $conexion->prepare($sql);
    $smtm->bind_param('sssssssss', $id_solicitud, $matricula, $nombre, $apellidos, $motivo, $grupo, $carrera, $nombre_jefe_completo, $nombreArchivo);

    if ($smtm->execute()) {
        
        echo json_encode(["sin_error" => True]);
    }
}


function EliminarCodigoQR($id_solicitud, $nombre, $fecha_codigo, $id_unico){
    $qr_text = $id_solicitud-1 . '_' . str_replace(' ', '_', $nombre) . '_' . str_replace('-', '_', $fecha_codigo);

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