<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";

if (isset($_POST["id"]) && isset($_POST['matricula'], $_POST['nombre'], $_POST['apellidos'], $_POST['grupo'], $_POST['motivo'], $_POST['fecha'])) {
    // Asignar variables recibidas
    $id = $_POST['id'];
    $id = strlen($id) == 1 ? '0' . $id : $id;
    $matricula = $_POST['matricula'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $grupo = $_POST['grupo'];
    $motivo = $_POST['motivo'];
    $fecha = $_POST['fecha'];
    $id_jefe = $_POST['id_jefe'];
    $data = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, $id_jefe);

    $nombre_jefe = $data[Variables::CAMPO_NOMBRE];
    $apellidos_jefe = $data[Variables::CAMPO_APELLIDOS];
    $id_carrera = $data[Variables::CAMPO_ID_CARRERA];
    $carrera = getResultCarrera($conexion, $id_carrera);

    $meses = [
        "enero",
        "febrero",
        "marzo",
        "abril",
        "mayo",
        "junio",
        "julio",
        "agosto",
        "septiembre",
        "octubre",
        "noviembre",
        "diciembre"
    ];

    $date = new DateTime(); // Obtiene la fecha actual
    $dia = $date->format('d'); // Día en dos dígitos
    $mes = $meses[$date->format('n') - 1]; // Obtiene el nombre del mes
    $año = $date->format('Y'); // Año en cuatro dígitos

    $fecha_actual = "$dia de $mes de $año";
    $ruta_logos = $_SERVER['DOCUMENT_ROOT'] . "/src/assets/justificantes/logos.jpg";
    $imagen_base64 = base64_encode(file_get_contents($ruta_logos));
    $src = 'data:image/jpeg;base64,' . $imagen_base64;


    $tipo_carrera = [
        "Industrial" => "Ingenieria",
        "Insdustrias Alimentarias" => "Ingenieria",
        "Electromecanica" => "Ingenieria",
        "Sistemas Computacionales" => "Ingenieria",
        "Gestion Empresarial" => "Ingenieria",
        "Contador Publico" => "Liceciatura",
        "Quimica" => "Ingenieria",
        "Ambiental" => "Ingenieria",
    ];
    // Capturar el HTML en un buffer
    ob_start();
} else {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Faltan parámetros requeridos."]);
}

// ?>

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
            <p class="carrera"> <?php echo $tipo_carrera[$carrera] . " " . $carrera ?></p>
        </div>



        <h2>JUSTIFICANTE DE INASISTENCIA DE CLASES</h2>

        <p class="folio"><strong>Folio: </strong><span>ISC/<?php echo $id ?>/2025</span></p>

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

        <div class="datos_jefe">
            <p>ATENTAMENTE</p>

            <p>MRT(A). <?php echo $nombre_jefe . " " . $apellidos_jefe ?></p>

            <p>JEFE(A) DE LA DIVISION DE <?php echo $tipo_carrera[$carrera] . " en " . $carrera ?></p>

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


$html = ob_get_clean(); // Captura el contenido y limpia el buffer

// Cargar DOMPDF
require_once "./dompdf/autoload.inc.php";
use Dompdf\Dompdf;

$pdf = new Dompdf();
$options = $pdf->getOptions();
$options->set("isRemoteEnabled", true);  // <-- ¡Sin array!
$pdf->setOptions($options);

$pdf->loadHtml($html); // Cargar el HTML generado
$pdf->setPaper("letter", "portrait");
$pdf->set_option('log_output_file', './dompdf.log');
$pdf->set_option('enable_remote', true);
$pdf->render();

// Ruta donde se guardará el archivo
$rutaGuardado = "../layouts/Alumno/justificantes/";
if (!file_exists($rutaGuardado)) {
    mkdir($rutaGuardado, 0777, true); // Crea la carpeta si no existe
}

$nombreArchivo = "justificante_" . $matricula . "_" . date("Ymd_His") . ".pdf";
$rutaArchivo = $rutaGuardado . $nombreArchivo;

// Guardar el PDF en la carpeta especificada
file_put_contents($rutaArchivo, $pdf->output());
$sql = "UPDATE " . Variables::TABLA_SOLICITUDES . " SET " . Variables::ESTADO . " = 'Aceptada' WHERE " . Variables::ID_SOLICITUD . " = ?";
$smtm = $conexion->prepare($sql);
$smtm->bind_param("i", $id); // Aquí "d" significa que esperas un parámetro tipo decimal o flotante
$smtm->execute();

$data_jefe = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, $id_jefe);

$carrera = getResultCarrera($conexion, $data_jefe[Variables::CAMPO_ID_CARRERA]);


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

$smtm = $conexion->prepare($sql);
$smtm->bind_param('sssssssss', $id, $matricula, $nombre, $apellidos, $motivo, $grupo, $carrera, $data_jefe[Variables::CAMPO_NOMBRE], $nombreArchivo);
$smtm->execute();

echo json_encode(["sin_error" => True]);


