<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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
$row = $alumno->ponerDatosFormulario($conexion, $id);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Crear Solicitud</title>
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
    <link rel="stylesheet" href="../../assets/styles/solicitud.css">
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
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
            <li class="menu-item">
                <a href="../../conexion/cerrar_sesion.php" class="link">
                    <img src="../../assets/iconos/ic_cerrar_sesion.webp" alt="icono de cerrar sesion">
                </a>
            </li>
            <li class="menu-item close_contenedor">
                <img class="close_menu" src="../../assets/iconos/ic_close.webp" alt="Imagen para cerrar el menu movil">
            </li>
        </ul>

        <img src="../../assets/iconos/ic_menu_movil.webp" alt="icono para el menu en movil" class="icono_menu">
    </nav>

    <main class="main">

        <div class="contenedor_main">
            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">


            <h2>Crear Solicitud</h2>

            <form class="formulario_solicitud" method="POST" enctype="multipart/form-data">

                <div class="contenedor_info-solicitud">
                    <p>Matricula:</p>
                    <input type="text" id="id" name="identificador" value="<?php echo $row[$CAMPO_MATRICULA]; ?>"
                        readonly>

                    <input type="text" id="grupo" name="grupo" value="<?php echo $row[$CAMPO_GRUPO]; ?>" readonly>
                </div>

                <div class="contenedor_info-solicitud">
                    <p>Nombre:</p>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $row[$CAMPO_NOMBRE]; ?>" readonly>
                </div>

                <div class="contenedor_info-solicitud">
                    <p>Apellidos:</p>
                    <input type="text" id="apellidos" name="apellidos" value="<?php echo $row[$CAMPO_APELLIDOS]; ?>"
                        readonly>
                </div>

                <div class="contenedor_info-solicitud">
                    <p for="email">Carrera:</p>
                    <input type="text" id="carrera" name="carrera"
                        value="<?php echo getResultCarrera($conexion, $row[$CAMPO_ID_CARRERA]); ?>" readonly>
                </div>

                <div class="contenedor_info-solicitud">
                    <p>Motivo:</p>
                    <select name="motivo" id="motivo">
                        <option value="Personal">Personal</option>
                        <option value="Laboral">Laboral</option>
                        <option value="Salud">Salud</option>
                    </select>
                </div>

                <div class="contenedor_info-solicitud">
                    <p>Ausente el: </p>
                    <input class="fecha_ausencia" type="date" name="fecha_ausencia" id="fecha_de_ausencia">
                </div>

                <label for="archivo" class="btn_archivo">
                    <input type="file" name="archivo_evidencia" id="archivo" class="archivo" accept="application/pdf">

                    <span id="nombreArchivo">Cargar evidencia</span>
                </label>

                <input type="submit" value="Enviar Solicitud" class="btn_enviar-solicitud">
                <div class="contenedor_ITSH">
                    <img src="../../assets/extra//logo.svg" alt="Logo del ITSH">
                </div>
            </form>

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

</body>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alumno->enviarSolicitud($conexion);
    notificaciones($_SESSION["mensaje"]);
}
?>