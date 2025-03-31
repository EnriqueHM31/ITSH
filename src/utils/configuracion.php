<?php 

include "../conexion/conexion.php";
include "../utils/constantes.php";
include "../utils/functionGlobales.php";

if(isset($_POST["id_carrera_nueva"])){

    $carreraNueva = $_POST["id_carrera_nueva"];
    $sql = $conexion->prepare("DELETE FROM ".Variables::TABLA_BD_CARRERA." WHERE ".Variables::CAMPO_CARRERA." = ?");
    $sql->bind_param("s", $carreraNueva);
    if($sql->execute()){
        echo json_encode(["sin_error" => true]) ;
    }else{
        echo json_encode(["sin_error" => "Error al eliminar la carrera"]) ;
    }
}


if(isset($_POST["obtener_datos_carrera"])){

    $carreraModificar = $_POST["obtener_datos_carrera"];

    $id_carrera = obtenerIDCarrera($conexion, $carreraModificar);

    $DataGrupos = getResultDataTabla($conexion, Variables::TABLA_BD_GRUPO, Variables::CAMPO_G_CARRERA, $id_carrera);

    $Numero_grupos = $DataGrupos[Variables::CAMPO_G_NUMERO_GRUPOS];
    $id_grupos = $DataGrupos[Variables::CAMPO_G_ID_GRUPO];

    echo json_encode(["sin_error"=> [$Numero_grupos, $id_grupos]]);

}
