<?php



function obtenerGrupos($conexion, $id_carrera)
{
    $dataGrupos = [];
    $resultGrupos = ObtenerIDYNumerosGrupos($conexion, $id_carrera);
    $resultModalidades = ObtenerModalidadesDeUnaCarrera($conexion, $id_carrera);

    while ($row = $resultGrupos->fetch_assoc()) {
        $dataGrupos[] = $row;
    }
    while ($row = $resultModalidades->fetch_assoc()) {
        $dataModalidades[] = $row;
    }

    return [$dataGrupos, $dataModalidades];
}