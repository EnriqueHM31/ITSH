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
