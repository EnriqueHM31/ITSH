<?php

include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "./creacionQR.php";
include "./FuncionesJustificante.php";


if (isset($_POST["id_solicitud"]) && isset($_POST['matricula'], $_POST['nombre'], $_POST['apellidos'], $_POST['grupo'], $_POST['motivo'], $_POST['fecha'])) {
    try {
        mysqli_begin_transaction($conexion);

        $id_justificante = ObtenerNumeroJustificantesJefeCarrera($conexion, $_POST['id_jefe']);
        $id_solicitud = $_POST["id_solicitud"];
        $nombre = $_POST['nombre'];
        $fecha = $_POST['fecha'];
        $id_estudiante = $_POST['matricula'];
        $apellidos = $_POST['apellidos'];
        $grupo = $_POST['grupo'];
        $motivo = $_POST['motivo'];
        $id_jefe = $_POST['id_jefe'];
        $fecha_codigo = $fecha;

        $fechaCreacion = date('Y-m-d');

        [$id_unico, $id_codigo] = generarCodigo($conexion, $id_justificante, $id_estudiante, $fechaCreacion);

        $datos_jefeUser = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id_jefe);

        $datos_jefe = ObtenerDatosDeUnaTabla($conexion, $TABLA_JEFE, $CAMPO_ID_USUARIO, $id_jefe);

        $nombre_jefe = $datos_jefeUser[$CAMPO_NOMBRE];
        $apellidos_jefe = $datos_jefeUser[$CAMPO_APELLIDOS];

        $dataCarrera = ObtenerDatosDeUnaTabla($conexion, $TABLA_CARRERAS, $CAMPO_ID_CARRERA, $datos_jefe[$CAMPO_ID_CARRERA]);

        $carrera = $dataCarrera[$CAMPO_CARRERA];
        $tipoCarrera = ObtenerNombreTipoCarrera($conexion, $dataCarrera[$CAMPO_ID_TIPO_CARRERA]);

        $fecha_actual = obtenerFechaActual();

        $src = obtenerIMGLogos();

        $src_qr = obtenerCodigoQR($id_unico, $id_justificante, $id_estudiante, $fechaCreacion);


        $fecha_ausencia_pdf = $fecha;

        $IdCarreraTipo = obtenerIniciales("$tipoCarrera $carrera");

        $id_justificante++;
        $Nojustificante = (strlen($id_justificante) == 1) ? "0$id_justificante" : $id_justificante;
        $añoActual = obtenerAñoActual();

    } catch (Exception $e) {

        return json_encode(["success" => "Error: {$e->getMessage()}"]);
    }

    ob_start();
} else {
    header("location: ../layouts/Errores/404.php");
    exit;
}
?>

<html>

<head>
    <meta charset="UTF-8">
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
            <p class="carrera"> <?php echo "$tipoCarrera en $carrera" ?></p>
        </div>



        <h2>JUSTIFICANTE DE INASISTENCIA DE CLASES</h2>

        <p class="folio"><strong>Folio: </strong>
            <span>
                <?php

                echo "$IdCarreraTipo/$Nojustificante/$añoActual";
                ?>
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
                            <p><strong>Nombre del Alumno(a):</strong> <?php echo "$nombre  $apellidos" ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p><strong>Grupo:</strong> <?php echo $grupo ?></p>
                        </td>
                        <td>
                            <p><strong>Num. de Control:</strong> <?php echo $id_estudiante ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p><strong>Carrera: </strong><?php echo $carrera ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p><strong>Fecha de Falta: </strong><?php echo $fecha_ausencia_pdf ?></p>
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

            <p>MRT(A). <?php echo "$nombre_jefe $apellidos_jefe" ?></p>

            <p>JEFE(A) DE LA DIVISION DE <?php echo "$tipoCarrera en $carrera" ?></p>

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

    $nombre_justificante = guardarArchivoPDF($conexion, $data, $id_justificante, $id_estudiante);

    ModificarEstadoSolicitudDB($conexion, $id_solicitud);

    if (InsertarTablaJustificanteDB($conexion, $id_solicitud, $id_estudiante, $id_jefe, $id_codigo, $nombre_justificante)) {
        echo json_encode(["success" => True]);
    }

    EliminarCodigoQR($id_justificante, $id_estudiante, $fechaCreacion, $id_unico);

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
        "success" => "Error al crear el PDF: " . $e->getMessage(),
    ]);
    exit();
}


