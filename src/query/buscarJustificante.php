<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../conexion/verificar acceso.php";
include "../utils/functionGlobales.php";

$query = isset($_GET['q']) ? $_GET['q'] : '';


$grupoJustificantes = buscarJustificantes($conexion, $query);

$salida = ""; // Inicializar variable para almacenar el HTML

if ($grupoJustificantes->num_rows == 0) {
    $salida = "<p class='sin_justificantes'>No se encontraron justificantes</p>";
    echo $salida;
    return;
}

while ($fila = $grupoJustificantes->fetch_assoc()) {
    $tiempo = explode(" ", $fila[$CAMPO_J_FECHA_CREACION]);
    $tiempo_fecha = explode("-", $tiempo[0]);
    echo "
            <a href='../Alumno/justificantes/{$fila[$CAMPO_J_JUSTIFICANTE]}' class='archivo' target='_blank'>
                <h2> Folio {$fila[$CAMPO_J_ID_JUSTIFICANTE]} </h2>
                <p> {$fila[$CAMPO_J_MATRICULA]} </p>
                <p> {$fila[$CAMPO_J_NOMBRE]} </p>
                <span>Hora: {$tiempo[1]} </span>
                <span>Fecha: {$tiempo_fecha[2]} de " . Variables::MESES[$tiempo_fecha[1][1] - 1] . " de " . $tiempo_fecha[0] . " </span>
            </a>
        ";
}
echo $salida;




