<?php

include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../clases/usuario.php";
include "../clases/alumno.php";
include "../validaciones/Validaciones.php";
include "../utils/functionGlobales.php";


header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $consulta = "DELETE FROM " . Variables::TABLA_SOLICITUDES . " WHERE " . Variables::ID_SOLICITUD . " = ?";

    $sql = $conexion->prepare($consulta);
    $sql->bind_param("s", $id);
    if ($sql->execute()) {
        $_SESSION["mensaje"] = "Se ha eliminado la solicitud";
        $_SESSION["icono"] = "../../assets/iconos/ic_correcto.webp";
        $_SESSION["color_mensaje"] = "var(--verde)";
    } else {
        $_SESSION["mensaje"] = "Ocurrio un error al eliminar la solicitud";
        $_SESSION["icono"] = "../../assets/iconos/ic_error.webp";
        $_SESSION["color_mensaje"] = "var(--rojo)";
    }
}