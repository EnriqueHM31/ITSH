<?php
include("../utils/constantes.php");
include("../conexion/conexion.php");

$query = isset($_GET['q']) ? $_GET['q'] : '';

if (strlen($query) > 0) {
    $sql = "SELECT * FROM " . Variables::TABLA_BD_JUSTIFICANTES . " 
        WHERE " . Variables::CAMPO_J_MATRICULA . " LIKE ? OR " . Variables::CAMPO_J_NOMBRE . " LIKE ? ";
    ;
    $stmt = $conexion->prepare($sql);
    $param = "%$query%";
    $stmt->bind_param('ss', $param, $param);

} else {
    $sql = "SELECT * FROM " . Variables::TABLA_BD_JUSTIFICANTES;
    $stmt = $conexion->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$salida = ""; // Inicializar variable para almacenar el HTML

if ($result->num_rows == 0) {
    $salida = "<p class='sin_justificantes'>No se encontraron justificantes</p>";
    return $salida;
}

while ($fila = $result->fetch_assoc()) {
    $salida .= "
        <a href='../Alumno/justificantes/{$fila['justificante_pdf']}' class='archivo' target='_blank'>
            <h2> Folio {$fila['id']} </h2>
            <p> {$fila['matricula_alumno']} </p>
            <p> {$fila['nombre_alumno']} </p>
            <span> {$fila['fecha_creacion']} </span>
        </a>
    ";
}

$stmt->close();

$conexion->close();
// Retornar todos los resultados concatenados
echo $salida;

