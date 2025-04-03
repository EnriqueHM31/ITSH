<?php



function obtenerGrupos($conexion, $id_carrera)
{
    $dataGrupos = [];
    $resultGrupos = obtenerIDAndNumerosGrupos($conexion, $id_carrera);
    $resultModalidades = obtenerModalidades($conexion, $id_carrera);

    while ($row = $resultGrupos->fetch_assoc()) {
        $dataGrupos[] = $row;
    }
    while ($row = $resultModalidades->fetch_assoc()) {
        $dataModalidades[] = $row;
    }

    return [$dataGrupos, $dataModalidades];
}