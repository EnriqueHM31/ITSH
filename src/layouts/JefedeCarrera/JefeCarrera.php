<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../utils/functionGlobales.php";
include "../../clases/jefe.php";

$usuario = new usuario();
$jefe = new Jefe();

$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$correo = $_SESSION["correo"];

$data = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, $_SESSION["id"]);
$id_carrera = $data[Variables::CAMPO_ID_CARRERA];
$carreraJefe = getResultCarrera($conexion, $id_carrera);
$seccion = "Eliminar";


$mostrar_modal = isset($_GET['mostrar_modal']) && $_GET['mostrar_modal'] === 'true';



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Pagina Principal Jefe De Carrera</title>
    <meta charset="UTF-8">
    <meta name="description" content="Pagina principal del Jefe de carrera">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/Inicio.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/templates.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
    <script src="../../assets/js/eliminar.js" defer></script>
    <script src="../../assets/js/jefeCarrera.js" defer></script>
    <script src="../../assets/js/cambiarContraseñaInicio.js" defer></script>
    <script src="../../assets/js/buscador.js" defer></script>
</head>

<body data-carrera="<?php echo $carreraJefe ?>" data-modo="<?php echo $seccion ?>">

    <nav class="navegacion">

        <div class="gobierno">
            <img src="../../assets/iconos/ic_gobierno.webp" alt="icono del gobierno de Mexico">

            <div class="texto_gobierno">
                <h3>Gobierno de</h3>
                <h4>Mexico</h4>
            </div>
        </div>

        <ul class="menu">
            <li class="menu-item"><a href="JefeCarrera.php" class="link">Inicio</a></li>
            <li class="menu-item"><a href="Añadir.php" class="link">Añadir</a></li>
            <li class="menu-item"><a href="Modificar.php" class="link">Modificar</a></li>
            <li class="menu-item"><a href="JefeCarrera.php?EliminarEstudiante=true" class="link">Eliminar</a></li>
            <li class="menu-item"><a href="Solicitudes.php" class="link">Solicitudes</a></li>
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
            <img src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina" width="1000px"
                height="164">
            <h2>Bienvenido Jefe de Carrera</h2>
            <div class="informacion_usuario">


                <div class="contenido_informacion">
                    <?php
                    $usuario->escribirDatosDelUsuario($conexion, $id, $rol, $correo);
                    ?>


                </div>

                <div class="contenedor_ITSH">
                    <img src="../../assets/extra/logo.svg" alt="Logo del ITSH">
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

    <template id="plantilla_eliminar-estudiante">
        <div class=" overlay overlay_eliminar">
            <form class="formulario form_eliminar" method="post">
                <h2 class="titulo titulo_eliminar">Eliminar Registro</h2>
                <div class="buscador-usuarios">
                    <label for="buscar" class="contenedor_input">
                        <input class="input_buscar" type="search" name="buscar" id="buscar" placeholder="Buscar">
                    </label>
                    <div id="resultados">
                    </div>
                </div>

                <img class="close" src="../../assets/iconos/ic_close.webp"
                    alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">

                <button type="button" class="btn_eliminar" id="eliminar_registro">Eliminar</button>
            </form>

        </div>
    </template>

    <template id="plantilla_usuario-seleccionado-estudiante">
        <div class=" overlay overlay_eliminar overlay_ventana">
            <form id="formulario_eliminar" class="formulario form_eliminar form_verificar_eliminar" method="post">

                <div class="informacion-usuario-eliminar">
                    <h2 class="titulo_eliminar-modal">Estas seguro de eliminar a: </h2>
                    <p class="info_usuario_eliminar">Clave: <span id="matricula-info"></span></p>
                    <p class="info_usuario_eliminar">Nombre: <span id="nombre-info"></span></p>
                    <p class="info_usuario_eliminar">Apellidos: <span id="apellidos-info"></span></p>
                    <p class="info_usuario_eliminar">Grupo: <span id="grupo-info"></span></p>
                    <p class="info_usuario_eliminar">Carrera: <span id="carrera-info"></span></p>
                    <p class="info_usuario_eliminar">Modalidad: <span id="id_modalidad-info"></span></p>
                    <p class="info_usuario_eliminar">Rol: <span id="rol-info"></span></p>
                    <p class="info_usuario_eliminar">Correo: <span id="correo-info"></span></p>
                    <input type="hidden" name="identificador" value="" id="identificador">
                </div>

                <img class="close close_eliminar" src="../../assets/iconos/ic_close.webp"
                    alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">

                <input type="submit" name="formulario" value="Eliminar" class="btn_eliminar btn_ventana-eliminar">
            </form>

        </div>
    </template>

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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['formulario'])) {

        if ($_POST['formulario'] === 'Cambiar') {
            $contraseña_actual = trim($_POST['contraseña_actual']);
            $contraseña_nueva = trim($_POST['contraseña_nueva']);

            $usuario->cambiarContraseña($conexion, $contraseña_actual, $contraseña_nueva, $id);
        } elseif ($_POST['formulario'] === 'Eliminar') {
            $id = $_POST['identificador'];
            $jefe->eliminarRegistroEstudiante($conexion, $id);
        }
        notificaciones($_SESSION["mensaje"]);
    }
}