<?php

include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../clases/usuario.php";

$usuario = new usuario();


header('Content-Type: application/json');

$correo = trim($_POST['user_email']);
$id_usuario = trim($_POST['Matricula']);
$nuevaContraseña = trim($_POST['password']);
$result = $usuario->verificarIdentidadCorreoIdentificador($id_usuario, $correo, $conexion);

if (mysqli_num_rows($result) > 0) {

    $resultadoModificar = $usuario->cambiarContraseñaEnBD($conexion, $id_usuario, $nuevaContraseña);

    if ($resultadoModificar) {
        echo json_encode(['valido' => true]);
    } else {
        echo json_encode(['valido' => false]);
    }
} else {
    echo json_encode(['error' => "nose pudo"]);
    exit();
}
$conexion->close();
exit();