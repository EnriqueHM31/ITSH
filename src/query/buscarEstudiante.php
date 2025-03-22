<?php
include("../utils/constantes.php");
include("../conexion/conexion.php");
include "../utils/functionGlobales.php";

$query = isset($_GET['q']) ? $_GET['q'] : '';
$carrera = isset($_GET['carrera']) ? $_GET['carrera'] : '';

if (!empty($query) && !empty($_GET['carrera'])) {

    $id_carrera = obtenerIDCarrera($conexion, $carrera);

    $sql = "SELECT " . Variables::CAMPO_MATRICULA . "," . Variables::CAMPO_NOMBRE . " FROM " . Variables::TABLA_BD_ESTUDIANTE . " 
    WHERE (" . Variables::CAMPO_MATRICULA . " LIKE ? OR " . Variables::CAMPO_NOMBRE . " LIKE ?) AND " . Variables::CAMPO_ID_CARRERA . " = ?";

    $stmt = $conexion->prepare($sql);
    $param = "%$query%";
    $stmt->bind_param('ssi', $param, $param, $id_carrera);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $clave = $row[Variables::CAMPO_MATRICULA];
            $nombre = $row[Variables::CAMPO_NOMBRE];

            echo "<div class='result'>
                    <p data-id=" . $clave . " >" . $nombre . "<span> " . $clave . "</span> " . "</p>
                </div>";
        }
    } else {
        echo "<div class='sin_resultados'>No se encontraron resultados.</div>";
    }

    $stmt->close();
}

$conexion->close();
