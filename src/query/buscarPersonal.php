<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";


$query = isset($_GET['q']) ? $_GET['q'] : '';

if (!empty($query)) {
    $resultadoBusquedaPersonal = buscarPersonalBD($conexion, $query);

    if ($resultadoBusquedaPersonal->num_rows > 0) {
        while ($row = $resultadoBusquedaPersonal->fetch_assoc()) {

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