<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../utils/functionGlobales.php";
include "../../clases/jefe.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_jefe.php";
include "../../Components/JefeCarrera.php";
include "../../Components/Usuario.php";
include "../../Components/Layout.php";


$jefe = new Jefe();

$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$dataJefe = ObtenerDatosDeUnaTabla($conexion, $TABLA_JEFE, $CAMPO_ID_USUARIO, $id);
$carrera = ObtenerNombreCarrera($conexion, $dataJefe[$CAMPO_ID_CARRERA])
    ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Historial De Justificantes</title>
    <meta charset="UTF-8">
    <meta name="description" content="Pagina donde el jefe de carrera puede ver todos los justificantes aprobados">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="preload" href="/src/assets/Fonts/fonts/Poppins/Poppins-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/src/assets/Fonts/fonts/Manrope/Manrope-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/Inicio.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/templates.css">
    <link rel="stylesheet" href="../../assets/styles/historialJustificantes.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/jefeCarrera.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
    <script src="../../assets/js/historial_jefe.js" defer></script>
</head>

<body>

    <?php
    componenteNavegacionLayout($rol);
    ?>

    <main class="main">
        <div class="contenedor_main">
            <div class="contenedor_logo">
                <img src="../../assets/extra/logo.svg" alt="logo del ITSH">
            </div>
            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">

            <div class="contenido_opciones">
                <button class="btn_reiniciar" onclick="mostrarModal()">Reiniciar Folio</button>
                <input type="search" name="buscar_folio" id="search-justificantes">
            </div>

            <div class="contenido_historial" id="historial">
                <?php
                $jefe->HistorialJustificantes($conexion, $id);
                ?>
            </div>



    </main>

    <?php
    componenteFooter();
    ?>


    <?php
    componenteTemplateModalNormal();
    componenteModalSeguridadFolio($id);
    componenteTemplateModalCargar();
    ?>
</body>

</html>