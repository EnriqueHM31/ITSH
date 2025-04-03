<?php
function obtenerGrupos($conexion, $id_carrera)
{

    $stmt = $conexion->prepare("SELECT  c." . Variables::CAMPO_CARRERA . ", g." . Variables::CAMPO_G_ID_GRUPO . ", g." . Variables::CAMPO_G_NUMERO_GRUPOS . " FROM " . Variables::TABLA_BD_GRUPO . " g JOIN " . Variables::TABLA_BD_CARRERA . " c ON g." . Variables::CAMPO_ID_CARRERA . " = c." . Variables::CAMPO_ID_CARRERA . " WHERE g." . Variables::CAMPO_ID_CARRERA . " = ? ");

    $stmt->bind_param("i", $id_carrera);
    $stmt->execute();
    $result = $stmt->get_result();
    $dataGrupos = [];

    $stmtModalidades = $conexion->prepare("SELECT COUNT(*) as Modalidades FROM " . Variables::TABLA_BD_CARRERA_MODALIDAD . " WHERE " . Variables::CAMPO_ID_CARRERA . " = ? GROUP BY " . Variables::CAMPO_ID_CARRERA);
    $stmtModalidades->bind_param("i", $id_carrera);
    $stmtModalidades->execute();
    $resultModalidades = $stmtModalidades->get_result();

    while ($row = $result->fetch_assoc()) {
        $dataGrupos[] = $row;
    }

    while ($row = $resultModalidades->fetch_assoc()) {
        $dataModalidades[] = $row;
    }



    return [$dataGrupos, $dataModalidades];
}