<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../clases/alumno.php";
include "../../validaciones/Validaciones.php";
include "../../utils/functionGlobales.php";
include "../../conexion/verificar acceso.php";
include "../../Components/Alumno.php";
include "../../Components/Usuario.php";
include "../../Components/Layout.php";

$usuario = new usuario();
$alumno = new alumno();
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Historial de Solicitudes</title>
    <meta charset="UTF-8">
    <meta name="description" content="Pagina para crear las solicitudes para los justificantes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="preload" href="/src/assets/Fonts/fonts/Poppins/Poppins-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/src/assets/Fonts/fonts/Manrope/Manrope-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/historialJustificantes.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>

    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
    <script src="../../assets/js/historial_justificantes.js" defer></script>
</head>

<body>

    <?php
    componenteNavegacionLayout($rol);
    ?>

    <main class="main">
        <div class="contenedor_main">
            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">

            <h2>Historial de tus justificantes</h2>

            <div class="contenido_historial" id="historial">
                <?php
                $alumno->HistorialJustificantes($conexion, $id);
                ?>
            </div>
            <div class="contenedor_ITSH">
                <img src="../../assets/extra//logo.svg" alt="Logo del ITSH">
            </div>
        </div>


    </main>

    <?php
    componenteFooter();
    ?>

    <?php
    componenteTemplateModalNormal();
    ?>

</body>

</html>

<?php
