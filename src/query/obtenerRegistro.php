<?php
include("../utils/constantes.php");
include("../conexion/conexion.php");

// Establecer encabezado para JSON
header('Content-Type: application/json');

if ($_POST['id'] === "") {
    estructuraMensaje("Id no proporcionado", "../../assets/iconos/ic_error.webp", "--rojo");
}

// Verificar si se envió el ID
if (isset($_POST['id'])) {

    $sql = "SELECT * FROM personal WHERE identificador = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'Usuario no encontrado' . $result]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'ID no proporcionado']);
}

$conexion->close();
