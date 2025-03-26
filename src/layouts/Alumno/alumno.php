<?php
session_start();
include "../../utils/constantes.php";
include "../../utils/functionGlobales.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";

$usuario = new usuario();
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$correo = $_SESSION["correo"];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Pagina Principal Alumno</title>
    <meta charset="UTF-8">
    <meta name="description" content="Pagina principal del alumno">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/Inicio.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/templates.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/cambiarContraseñaInicio.js" defer></script>
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
            <li class="menu-item"><a href="Crear Solicitud.php" class="link">Crear Solicitud</a></li>
            <li class="menu-item"><a href="Historial Alumno.php" class="link">Historial</a></li>
            <li class="menu-item"><a href="../../conexion/cerrar_sesion.php" class="link">Cerrar Sesion</a></li>
            <li class="menu-item close_contenedor"><img class="close_menu" src="../../assets/iconos/ic_close.webp"
                    alt="Imagen para cerrar el menu movil"></li>
        </ul>

        <img src="../../assets/iconos/ic_menu_movil.webp" alt="icono para el menu en movil" class="icono_menu">
    </nav>

    <main class="main">
        <div class="contenedor_main">
            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">
            <h2>Bienvenido Estudiante</h2>
            <div class="informacion_usuario">


                <div class="contenido_informacion">
                    <?php
                    $usuario->escribirDatosDelUsuario($conexion, $id, $rol, $correo)
                        ?>


                </div>

                <div class="contenedor_ITSH">
                    <img src="../../assets/extra//logo.svg" alt="Logo del ITSH">
                </div>
            </div>

        </div>


    </main>

    <footer class="footer">
        <div class="contenido_footer">
            <div class="siguenos">
                <p>Siguenos en</p>
                <div class="redes">
                    <a href="https://www.facebook.com/ITSHuatusco/?locale=es_LA" target="_blank"><img
                            src="../../assets/iconos/ic_facebook.webp" alt="icono de facebook"></a>
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

    <template id="plantilla_cambiar-contraseña">
        <div class="overlay_cambiar-contraseña overlay_ventana">
            <form class="formulario" method="post">
                <h2 class="titulo">Cambiar Contraseña</h2>
                <div class="inputs-cambio-contraseña">

                    <label for="contraseña_actual" class="contenedor_input">
                        <input class="input_login" type="text" name="contraseña_actual" id="contraseña_actual"
                            placeholder=" " autocomplete="current-password">
                        <span class="nombre_input">Contraseña actual</span>
                    </label>

                    <label for="contraseña_nueva" class="contenedor_input">
                        <input class="input_login" type="password" name="contraseña_nueva" id="contraseña_nueva"
                            placeholder=" " autocomplete="new-password">
                        <span class="nombre_input">Contraseña nueva</span>
                    </label>
                </div>

                <input type="submit" name="formulario" class="btn-submit btn_login" value="Cambiar">

                <img class="close" id="cerrar" src="../../assets/iconos/ic_close.webp"
                    alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">
            </form>
        </div>
    </template>

</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['formulario'])) {
        if ($_POST['formulario'] === 'Cambiar') {
            $contraseña_actual = trim($_POST['contraseña_actual']);
            $contraseña_nueva = trim($_POST['contraseña_nueva']);

            $usuario->cambiarContraseña($conexion, $contraseña_actual, $contraseña_nueva, $id);
            notificaciones($_SESSION["mensaje"]);
        }
    }
}

?>