<?php

include "../conexion/conexion.php";
include "../utils/constantes.php";
include "../utils/functionGlobales.php";

if (isset($_POST["id_carrera_nueva"])) {

    $carreraNueva = $_POST["id_carrera_nueva"];

    if (EliminarCarrera($conexion, $carreraNueva)) {
        echo json_encode(["sin_error" => true]);
    } else {
        echo json_encode(["sin_error" => "Error al eliminar la carrera"]);
    }
}


if (isset($_POST["obtener_datos_carrera"])) {

    $carreraModificar = $_POST["obtener_datos_carrera"];

    $id_carrera = obtenerIDCarrera($conexion, $carreraModificar);

    $DataGrupos = getResultDataTabla($conexion, $TABLA_GRUPO, $CAMPO_CARRERA, $id_carrera);

    $Numero_grupos = $DataGrupos[$CAMPO_NUMERO_GRUPOS];
    $id_grupos = $DataGrupos[$CAMPO_ID_GRUPOS];

    echo json_encode(["sin_error" => [$Numero_grupos, $id_grupos]]);

}
