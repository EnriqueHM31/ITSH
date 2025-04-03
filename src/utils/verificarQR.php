<?php
// Incluir la conexión a la base de datos (ajusta la ruta según tu estructura)
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";


// Verificar que se haya enviado el parámetro con el texto del QR
$qr_text = $_GET['qr_text'];

$resultado_consulta = obtenerCodigoQVerificacion($conexion, $qr_text);

// Verificar si se encontró el código
if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();

    if ($row[$CAMPO_VALIDO_QR] == 1) {

        if (actualizarValidacionCodigoQR($conexion, $qr_text)) {
            $src = "../assets/iconos/ic_correcto.webp";
            $mensaje_validacion = "El codigo es valido";
        } else {
            $src = "../assets/iconos/ic_error.webp";
            $mensaje_validacion = "El codigo es invalido";
        }
        $stmt_update->close();
    } else {
        $src = "../assets/iconos/ic_error.webp";
        $mensaje_validacion = "Código inválido: ya fue escaneado previamente";
    }
} else {
    $src = "../assets/iconos/ic_error.webp";
    $mensaje_validacion = "Código no encontrado";
}
$stmt->close();
$conexion->close();
?>


<html>

<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            width: 100%;
            height: 100dvh;
            background: rgb(97, 18, 50);
            display: flex;
            align-items: center;
            flex-direction: column;
            gap: 8rem;
            margin-top: 4rem;
        }

        .logo img {
            max-width: 100%;
        }

        .mensaje {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            width: min(50rem, 90%);
            height: 60dvh;
            border-radius: 2rem;
            padding: 2.5rem;
            flex-direction: column;
        }

        .mensaje img {
            width: 100%;
            max-width: 60%;
            ;
        }

        p {
            font-size: clamp(5rem, 5vw, 9rem);
            font-weight: bold;
            text-align: center;
            padding: 1rem 2rem;
        }
    </style>
</head>

<body>
    <div class="logo">
        <img src="../assets/extra/logo.svg" alt="Logo de la escuela ITSH">
    </div>
    <div class="mensaje">
        <img src=<?php echo $src ?> alt="Logo que representa el error o correcto del codigo">
        <p><?php echo $mensaje_validacion ?></p>
    </div>
</body>

</html>