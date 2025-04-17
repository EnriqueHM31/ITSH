<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../conexion/verificar acceso.php";
include "../utils/functionGlobales.php";
include "../Components/JefeCarrera.php";

$query = isset($_GET['q']) ? $_GET['q'] : '';


$grupoJustificantes = buscarJustificantes($conexion, $query);
$salida = ""; // Inicializar variable para almacenar el HTML

if ($grupoJustificantes->num_rows == 0) {
    $salida = "<p class='sin_justificantes'>No se encontraron justificantes</p>";
    echo $salida;
    return;
}

while ($fila = $grupoJustificantes->fetch_assoc()) {
    $tiempo = explode(" ", $fila[$CAMPO_FECHA_CREACION]);
    $tiempo_fecha = explode("-", $tiempo[0]);
    componenteJustificanteJefe($conexion, $fila, $tiempo_fecha);
}
echo $salida;




