<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../conexion/verificar acceso.php";

$query = isset($_GET['q']) ? $_GET['q'] : '';
$carrera = isset($_GET['carrera']) ? $_GET['carrera'] : '';

if (!empty($query) && !empty($carrera)) {

    $id_carrera = ObtenerIDCarrera($conexion, $carrera);

    $resultadoEstudiantes = BuscarEstudianteBD($conexion, $query, $id_carrera);

    if ($resultadoEstudiantes->num_rows > 0) {
        while ($row = $resultadoEstudiantes->fetch_assoc()) {

            $clave = $row[$CAMPO_ID_USUARIO];
            $nombre = $row[$CAMPO_NOMBRE];

            echo "<div class='result'>
                    <p data-id=$clave>$nombre<span>$clave</span></p>
                </div>";
        }
    } else {
        echo "<div class='sin_resultados'>No se encontraron resultados.</div>";
    }

    $conexion->close();
}

