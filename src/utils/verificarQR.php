<?php
// Incluir la conexión a la base de datos (ajusta la ruta según tu estructura)
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../utils/functionGlobales.php";


// Verificar que se haya enviado el parámetro con el texto del QR
$qr_text = $_GET['qr_text'];
date_default_timezone_set('America/Mexico_City');

$arrayDatos = explode("_", $qr_text);


$id_estudiante = $arrayDatos[1];
$fecha_data = "$arrayDatos[2]-$arrayDatos[3]-$arrayDatos[4]";

$dataEstudiante = ObtenerDatosDeUnaTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $id_estudiante);
$dataUsuario = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id_estudiante);
$tipo_usuario = ObtenerModalidad($conexion, $dataEstudiante[$CAMPO_ID_MODALIDAD]);

$modalidad = ObtenerModalidad($conexion, $dataEstudiante[$CAMPO_ID_MODALIDAD]);
$carreraEstudiante = ObtenerNombreCarrera($conexion, $dataEstudiante[$CAMPO_ID_CARRERA]);



$fecha_creacion = strtotime($fecha_data);
$dia_semana = date('N', $fecha_creacion); // 1=Lunes, 7=Domingo

// Calcular fecha límite
$fecha_limite = null;

if ($tipo_usuario == 'Escolarizado') {
    if ($dia_semana >= 1 && $dia_semana <= 4) { // Lunes a Jueves
        $fecha_limite = strtotime(date('Y-m-d 23:59:59', $fecha_creacion));
    } elseif ($dia_semana == 5 || $dia_semana == 6 || $dia_semana == 7) { // Viernes o Sábado
        // Sumar días hasta lunes
        $dias_hasta_lunes = 8 - $dia_semana; // (8-5=3 días)
        $fecha_lunes = strtotime("+$dias_hasta_lunes days", $fecha_creacion);
        $fecha_limite = strtotime(date('Y-m-d 23:59:59', $fecha_lunes));
    }
} elseif ($tipo_usuario == 'Flexible') {
    $dias_hasta_sabado = 6 - $dia_semana;  // 6 es sábado

    if ($dias_hasta_sabado <= 0) {
        $dias_hasta_sabado = 7;
    }
    $fecha_sabado = strtotime("+$dias_hasta_sabado days", $fecha_creacion);
    $fecha_limite = strtotime(date('Y-m-d 23:59:59', $fecha_sabado));  // 23:59:59 para el final del sábado

}

// Validar fecha actual vs fecha límite
$fecha_actual = time();

$fecha_limite_date = date('Y-m-d H:i:s', $fecha_limite);

if ($fecha_actual <= $fecha_limite) {
    if (ModificarLaValidacionCodigoQRDB($conexion, $qr_text)) {
        $src = "../assets/iconos/ic_correcto.webp";
    } else {
        $src = "../assets/iconos/ic_error.webp";
        $mensaje_validacion = "El codigo es invalido";
    }
} else {
    $src = "../assets/iconos/ic_error.webp";
    $mensaje_validacion = "El codigo expiro";
}



?>


<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">  
    <meta name="description" content="Página de verificación del código QR">
    <meta name="keywords" content="código QR, verificación, estudiante, carrera, modalidad, grupo">
    <meta name="author" content="Enrique H. Méndez Pérez">

    <title>Verificación del código QR</title>
    <link rel="shortcut icon" href="../assets/extra/logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/fonts/fonts.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Manrope', sans-serif;
        }

        body {
            width: 100%;
            background: rgb(97, 18, 50);
            display: flex;
            align-items: center;
            flex-direction: column;
            height: 100dvh;
            gap: 2rem;
        }

        .logo img {
            width: 15rem;
        }

        .mensaje {
            background-color: white;
            display: flex;
            gap: 1rem;
            width: 100%;
            max-width: 40%;
            border-radius: 2rem;
            padding: 1.2rem;
            flex-direction: column;
        }

        .mensaje img {
            width: 15rem;
            margin: 0 auto;
        }

        strong {
            font-weight: bold;
            color: rgb(97, 18, 50);
        }

        p {
            font-size: clamp(.7rem, 4vw, 1.1rem);
            font-weight: bold;
        }

        @media screen and (max-width: 1000px) {
            body {
                gap: 1rem;
            }

            .logo img {
                height: 6rem;
                width: 6rem;
            }

            .mensaje {
                max-width: 95%;
            }


            .mensaje img {
                height:  5rem;
                width: 5rem;
            }

            p {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>
    <div class="logo">
        <img style="aspect-ratio: 16 / 9 ;" src="../assets/extra/logo.svg" alt="Logo de la escuela ITSH">
    </div>
    <div class="mensaje">
        <img style="aspect-ratio: 16 / 9 ; object-fit: contain;" src=<?php echo $src ?>
            alt="Logo que representa el error o correcto del codigo">
        <p><strong>Estudiante: </strong> <?php echo $id_estudiante ?></p>
        <p><strong>Nombre: </strong> <?php echo "$dataUsuario[$CAMPO_NOMBRE] $dataUsuario[$CAMPO_APELLIDOS]" ?></p>
        <p><strong>Grupo: </strong> <?php echo "$dataEstudiante[$CAMPO_GRUPO]" ?></p>
        <p><strong>Modalidad: </strong> <?php echo $modalidad ?></p>
        <p><strong>Carrera: </strong> <?php echo $carreraEstudiante ?></p>

        <span><strong>Tiempo de validez: </strong><br><p id="reloj">00:00:00:00</p></span>
        
    </div>
</body>

</html>


<script>
    // Función para iniciar la cuenta regresiva con la fecha pasada como parámetro
    function iniciarCuentaRegresiva(fechaDestino) {
        // Asegurarse de que la fecha sea válida
        var fechaFinal = new Date(fechaDestino);

        if (isNaN(fechaFinal.getTime())) {
            document.getElementById('reloj').innerHTML = "Fecha inválida";
            return;
        }

        // Actualizar el reloj cada segundo
        var intervalo = setInterval(function () {
            // Fecha actual
            var fechaActual = new Date();

            // Calcular la diferencia en milisegundos
            var diferencia = fechaFinal - fechaActual;

            // Si la fecha ya pasó, detener el intervalo
            if (diferencia <= 0) {
                clearInterval(intervalo);
                document.getElementById('reloj').innerHTML = "¡La fecha ha pasado!";
                return;
            }

            // Calcular días, horas, minutos y segundos restantes
            var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
            var horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
            var segundos = Math.floor((diferencia % (1000 * 60)) / 1000);

            // Mostrar el tiempo restante en formato DD:HH:MM:SS
            document.getElementById('reloj').innerHTML =
                (dias < 10 ? "0" : "") + dias + ":" +
                (horas < 10 ? "0" : "") + horas + ":" +
                (minutos < 10 ? "0" : "") + minutos + ":" +
                (segundos < 10 ? "0" : "") + segundos;
        }, 1000); // Actualiza cada 1 segundo
    }

    // Llamar a la función con el parámetro de la fecha
    // Puedes pasar la fecha en formato 'YYYY-MM-DD HH:MM:SS'
    iniciarCuentaRegresiva('<?php echo $fecha_limite_date ?>'); // Aquí puedes cambiar la fecha destino
</script>