<?php
include("../utils/constantes.php");
include("../conexion/conexion.php");
include("../utils/functionGlobales.php");

if (isset($_POST["id"]) && isset($_POST['matricula'], $_POST['nombre'], $_POST['apellidos'], $_POST['grupo'], $_POST['motivo'], $_POST['fecha'])) {
    // Asignar variables recibidas
    $id = $_POST['id'];
    $matricula = $_POST['matricula'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $grupo = $_POST['grupo'];
    $motivo = $_POST['motivo'];
    $fecha = $_POST['fecha'];
    $id_jefe = $_POST['id_jefe'];

    // Capturar el HTML en un buffer
    ob_start();
} else {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Faltan parámetros requeridos."]);
}

?>

<html>

<body>
    <h2 style="text-align: center;">Justificante</h2>
    <p><strong>Matricula:</strong> <?php echo $id; ?></p>
    <p><strong>Matrícula:</strong> <?php echo $matricula; ?></p>
    <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
    <p><strong>Apellidos:</strong> <?php echo $apellidos; ?></p>
    <p><strong>Grupo:</strong> <?php echo $grupo; ?></p>
    <p><strong>Motivo:</strong> <?php echo $motivo; ?></p>
    <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
</body>

</html>

<?php


$html = ob_get_clean(); // Captura el contenido y limpia el buffer

// Cargar DOMPDF
require_once "./dompdf/autoload.inc.php";
use Dompdf\Dompdf;

$pdf = new Dompdf();
$options = $pdf->getOptions();
$options->set(array("isRemoteEnabled" => true)); // Habilitar imágenes remotas
$pdf->setOptions($options);

$pdf->loadHtml($html); // Cargar el HTML generado
$pdf->setPaper("letter"); // Tamaño carta
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


$sql = "INSERT INTO " . Variables::TABLA_BD_JUSTIFICANTES . "("
    . Variables::CAMPO_J_MATRICULA . ", "
    . Variables::CAMPO_J_NOMBRE . ", "
    . Variables::CAMPO_J_APELLIDOS . ", "
    . Variables::CAMPO_J_MOTIVO . ", "
    . Variables::CAMPO_J_GRUPO . ", "
    . Variables::CAMPO_J_CARRERA . ", "
    . Variables::CAMPO_J_NOMBRE_JEFE . ", "
    . Variables::CAMPO_J_JUSTIFICANTE . ") VALUES (?,?,?,?,?,?,?,?)";

$smtm = $conexion->prepare($sql);
$smtm->bind_param('ssssssss', $matricula, $nombre, $apellidos, $motivo, $grupo, $carrera, $id_jefe_, $nombreArchivo);
$smtm->execute();


echo json_encode(["sin_error" => True]);


