<?php
// Incluir la conexión a la base de datos (ajusta la ruta según tu estructura)
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../conexion/verificar acceso.php";


// Verificar que se haya enviado el parámetro con el texto del QR
if (isset($_GET['qr_text'])) {
    $qr_text = $_GET['qr_text'];

    // Preparar la consulta para buscar el código QR en la base de datos
    $sql = "SELECT " . Variables::CAMPO_Q_VALIDO . " FROM " . Variables::TABLA_BD_CODIGOS_QR . " WHERE " . Variables::CAMPO_Q_TEXTO . " = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $qr_text);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró el código
    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();

        // Si el código aún es válido (valor 1)
        if ($row['valido'] == 1) {
            // Actualizar a 2 para marcarlo como ya escaneado
            $update = "UPDATE " . Variables::TABLA_BD_CODIGOS_QR . " SET " . Variables::CAMPO_Q_VALIDO . " = 2 WHERE " . Variables::CAMPO_Q_TEXTO . " = ?";

            $stmt_update = $conexion->prepare($update);
            $stmt_update->bind_param("s", $qr_text);
            if ($stmt_update->execute()) {
                $src = "../assets/iconos/ic_correcto.webp";
                $codigo_valido = "El codigo es valido";
            } else {
                $src = "../assets/iconos/ic_error.webp";

                $codigo_valido = "El codigo es invalido";
            }
            $stmt_update->close();
        } else {
            $src = "../assets/iconos/ic_error.webp";
            $codigo_valido = "Código inválido: ya fue escaneado previamente";
        }
    } else {
        $src = "../assets/iconos/ic_error.webp";
        $codigo_valido = "Código no encontrado";
    }
    $stmt->close();
} else {
    $src = "../assets/iconos/ic_error.webp";
    $codigo_valido = "No se proporcionó información del código QR";
}

// Cierra la conexión
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
        <p><?php echo $codigo_valido ?></p>
    </div>
</body>

</html>