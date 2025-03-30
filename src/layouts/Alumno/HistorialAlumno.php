<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../clases/alumno.php";
include "../../validaciones/Validaciones.php";
include "../../utils/functionGlobales.php";
include "../../conexion/verificar acceso.php";

$usuario = new usuario();
$alumno = new alumno();
$id = $_SESSION["id"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Crear Solicitud</title>
    <meta charset="UTF-8">
    <meta name="description" content="Pagina para crear las solicitudes para los justificantes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
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

    <nav class="navegacion">

        <div class="gobierno">
            <img src="../../assets/iconos/ic_gobierno.webp" alt="icono del gobierno de Mexico">

            <div class="texto_gobierno">
                <h3>Gobierno de</h3>
                <h4>Mexico</h4>
            </div>
        </div>

        <ul class="menu">
            <li class="menu-item"><a href="alumno.php" class="link">Inicio</a></li>
            <li class="menu-item"><a href="CrearSolicitud.php" class="link">Crear Solicitud</a></li>
            <li class="menu-item"><a href="HistorialAlumno.php" class="link">Historial</a></li>
            <li class="menu-item"><a href="../../conexion/cerrar_sesion.php" class="link">Cerrar Sesion</a></li>
            <li class="menu-item close_contenedor"><img class="close_menu" src="../../assets/iconos/ic_close.webp"
                    alt="Imagen para cerrar el menu movil"></li>
        </ul>

        <img src="../../assets/iconos/ic_menu_movil.webp" alt="icono para el menu en movil" class="icono_menu">
    </nav>

    <main class="main">
        <div class="contenedor_main">
            <img src="../../assets/extra/encabezado.webp" width="1000px" height="164"
                alt="los encabezados de la pagina">
            <div class="contenedor_logo">
                <img src="../../assets/extra/logo.svg" alt="logo del ITSH">
            </div>
            <h2>Historial de tus justificantes</h2>

            <div class="contenido_historial" id="historial">
                <?php
                $alumno->HistorialJustificantes($conexion, $id);
                ?>
            </div>

        </div>


    </main>

    <footer class="footer">
        <div class="contenido_footer">
            <div class="siguenos">
                <p>Siguenos en</p>
                <div class="redes">
                    <a href="https://www.facebook.com/ITSHuatusco/?locale=es_LA" target="_blank"><img
                            src="../../assets//iconos/ic_facebook.webp" alt="icono de facebook"></a>
                    <a href="https://www.instagram.com/itshuatusco/?hl=es-la" target="_blank"><img
                            src="../../assets/iconos/ic_instagram.webp" alt="icono de facebook"></a>
                </div>
            </div>

            <div class="definicion">
                <span>¿Que esto?</span>
                <p>Un sistema de justificantes para el Instituto Tecnologico Superior de Huatusco</p>
            </div>

            <div class="terminos">
                <a href="../Terminos/Terminos y Condiciones.php">Terminos y Condiciones</a>
            </div>
        </div>

        <div class="footer_gobierno">
            <img src="../../assets/iconos/ic_gobierno.webp" alt="icono del gobierno de Mexico">

            <div class="texto_gobierno">
                <p>Gobierno de Mexico</p>

            </div>
        </div>

    </footer>

    <template id="miTemplate">
        <div class="overlay" id="overlay">
            <div class="notificacion">
                <img class="img_notificacion" src="" alt="icono de notificacion" id="imagen">
                <div class="contenido_notificacion ">
                    <p id="mensaje"></p>
                </div>
                <button class="btn_mensaje" id="btn_mensaje" onclick="cerrarTemplate()">Cerrar</button>
            </div>
        </div>
    </template>

</body>

</html>

<?php
