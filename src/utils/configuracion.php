<?php

include "../conexion/conexion.php";
include "../utils/constantes.php";
include "../utils/functionGlobales.php";

if (isset($_POST["id_carrera_nueva"])) {

    $carreraNueva = $_POST["id_carrera_nueva"];

    if (EliminarCarrera($conexion, $carreraNueva)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => "Error al eliminar la carrera"]);
    }
}


if (isset($_POST["obtener_datos_carrera"])) {

    $carreraModificar = $_POST["obtener_datos_carrera"];

    $id_carrera = obtenerIDCarrera($conexion, $carreraModificar);
    $DataGrupos = getResultDataTabla($conexion, $TABLA_GRUPO, $CAMPO_ID_CARRERA, $id_carrera);

    $carrera = getResultDataTabla($conexion, $TABLA_CARRERAS, $CAMPO_ID_CARRERA, $id_carrera);
    $tipo_carrera = obtenerTipoCarrera($conexion, $carrera[$CAMPO_ID_TIPO_CARRERA]);

    $data_modalidades = obtenerModalidadesCarrera($conexion, $id_carrera);


    $Numero_grupos = $DataGrupos[$CAMPO_NUMERO_GRUPOS];
    $id_grupos = $DataGrupos[$CAMPO_CLAVE_GRUPO];

    echo json_encode(["success" => [$Numero_grupos, $id_grupos, $tipo_carrera, $data_modalidades]]);

}
