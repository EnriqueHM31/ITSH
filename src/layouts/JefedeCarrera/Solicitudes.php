<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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


$usuario = new usuario();
$jefeCarrera = new Jefe();
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$data = ObtenerDatosDeUnaTabla($conexion, $TABLA_JEFE, $CAMPO_ID_USUARIO, $_SESSION["id"]);
$id_carrera = $data[$CAMPO_ID_CARRERA];
$carreraJefe = ObtenerNombreCarrera($conexion, $id_carrera);

$dataSolicitudes = ObtenerSolicitudesDelJefeCarrera($conexion, $_SESSION["id"]);
$sin_resultados = "";
if ($dataSolicitudes) {
    $arraysDatos = $jefeCarrera->MostrarSolicitudes($conexion, $dataSolicitudes, $id);
} else {
    $sin_resultados = "<p class='sin_solicitudes'>No hay solicitudes</p>";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Solicitudes De Los Alumnos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Pagina donde el jefe de carrera puede ver las solicitudes para justificantes de los estudiantes">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="preload" href="/src/assets/Fonts/fonts/Poppins/Poppins-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/src/assets/Fonts/fonts/Manrope/Manrope-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/Añadir.css">
    <link rel="stylesheet" href="../../assets/styles/Modificar.css">
    <link rel="stylesheet" href="../../assets/styles/tablaSolicitudes.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/jefeCarrera.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
</head>

<body>


    <?php
    componenteNavegacionLayout($rol);
    ?>

    <main class="main">
        <div class="contenedor_logo">
            <img src="../../assets/extra/logo.svg" alt="logo del ITSH">
        </div>

        <div class="contenedor_main">
            <div class="contenedor">
                <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">
            </div>
            <div class="contenido_solicitudes">
                <a href="./HistorialJustificantes.php" class="btn_historial">
                    Justificantes
                </a>


                <table id="table">
                </table>
                <div id="lista-solicitudes-details">
                </div>

                <?php
                if (strlen($sin_resultados) != 0) {
                    echo $sin_resultados;
                }
                ?>
            </div>
        </div>

    </main>


    <?php
    componenteFooter();
    ?>

    <?php
    componenteTemplateModalCargar();
    componenteTemplateModalNormal();
    ?>
</body>

</html>

<script defer>

    function ajustarContenido(array) {
        const screenWidth = window.innerWidth;

        const tabla = document.getElementById("table");
        const detalles = document.getElementById("lista-solicitudes-details");

        // Limpiar los contenidos
        if (detalles) detalles.innerHTML = "";
        if (tabla) tabla.innerHTML = "";

        if (screenWidth > 1000) {
            let contenidoTabla = array[0][array[0].length - 1]; // Primer contenido
            for (let i = 0; i < array[0].length - 1; i++) {
                contenidoTabla += array[0][i];  // Añadir el resto
            }
            tabla.innerHTML = contenidoTabla; // Asignar todo el contenido de una vez
        } else {
            let contenidoDetalles = "";
            for (let i = 0; i < array[1].length; i++) {
                contenidoDetalles += array[1][i];  // Crear el contenido completo
            }
            detalles.innerHTML = contenidoDetalles;  // Asignar todo el contenido de una vez
        }
    }

    function mostrarDatosResize(array) {
        document.addEventListener("DOMContentLoaded", function () {
            ajustarContenido(array);  // Ejecutar directamente cuando la página carga
        });

        // Ejecutar al redimensionar la ventana
        window.addEventListener("resize", function () {
            ajustarContenido(array);  // Ejecutar nuevamente al redimensionar
        });
    }

    mostrarDatosResize(<?php echo json_encode($arraysDatos) ?>) 
</script>