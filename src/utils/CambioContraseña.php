<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";
include "../clases/usuario.php";
/** @var mysqli $conexion */
$usuario = new usuario();


header('Content-Type: application/json');

$correo = trim($_POST['user_email']);
$id_usuario = trim($_POST['Matricula']);
$nuevaContraseña = trim($_POST['password']);
$result = verificarIdentidadCorreoIdentificador($id_usuario, $correo, $conexion);

if (mysqli_num_rows($result) > 0) {

    $resultadoModificar = $usuario->cambiarContraseñaEnBD($conexion, $id_usuario, $nuevaContraseña);

    if ($resultadoModificar) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => "Ocurrio un problema al cambiar la contraseña"]);
    }
} else {
    echo json_encode(['success' => "Ocurrio un problema con tus credenciales de usuario"]);
    exit();
}
$conexion->close();
exit();
