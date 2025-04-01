<?php
function obtenerGrupos($conexion, $id_carrera)
{

    $stmt = $conexion->prepare("SELECT  c.carrera, g.id_grupos, g.Numero_grupos FROM grupo g JOIN carrera c ON g.id_carrera = c.id_carrera WHERE g.id_carrera = $id_carrera ");
    $stmt->execute();
    $result = $stmt->get_result();
    $dataGrupos = [];

    $stmtModalidades = $conexion->prepare("SELECT COUNT(*) as Modalidades FROM Carrera_Modalidad WHERE id_carrera = $id_carrera GROUP BY id_carrera");
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