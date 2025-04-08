<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/administrador.php";
include "../../utils/functionGlobales.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_admin.php";
include "../../Components/Admin.php";

$administrador = new administrador();
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$correo = $_SESSION["correo"];

$carreras = obtenerDatosColumnaTabla($conexion, Variables::CAMPO_CARRERA, Variables::TABLA_BD_CARRERA);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Configuracion Administrador</title>
    <meta charset="UTF-8">
    <meta name="description" content="Pagina principal del administrador">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="preload" href="/src/assets/Fonts/fonts/Poppins/Poppins-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/src/assets/Fonts/fonts/Manrope/Manrope-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/templates.css">
    <link rel="stylesheet" href="../../assets/styles/Configuracion.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
    <script src="../../assets/js/configuracion.js" defer></script>

</head>

<body data-modo="Eliminar" data-carrera="">

    <nav class="navegacion">

        <div class="gobierno">
            <img src="../../assets/iconos/ic_gobierno.webp" alt="icono del gobierno de Mexico">

            <div class="texto_gobierno">
                <h3>Gobierno de</h3>
                <h4>Mexico</h4>
            </div>
        </div>

        <ul class="menu">
            <li class="menu-item"><a href="Admin.php" class="link">Inicio</a></li>
            <li class="menu-item"><a href="Añadir.php" class="link">Añadir</a></li>
            <li class="menu-item"><a href="Modificar.php" class="link">Modificar</a></li>
            <li class="menu-item"><a href="Admin.php?EliminarPersonal=true" class="link">Eliminar</a></li>
            <li class="menu-item"><a href="Configuracion.php" class="link">Configuracion</a></li>

            <li class="menu-item"><a href="../../conexion/cerrar_sesion.php" class="link"><img
                        src="../../assets/iconos/ic_cerrar_sesion.webp" alt="icono de cerrar sesion"></a></li>
            <li class="menu-item close_contenedor"><img class="close_menu" src="../../assets/iconos/ic_close.webp"
                    alt="Imagen para cerrar el menu movil"></li>
        </ul>

        <img src="../../assets/iconos/ic_menu_movil.webp" alt="icono para el menu en movil" class="icono_menu">
    </nav>

    <main class="main">
        <div class="contenedor_main">
            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">

            <div class="contenido_configuracion">

                <div class="contenedor_configuracion">
                    <h2>Configuracion de la carreras</h2>
                    <div class="opciones-configuracion">
                        <button class="btn-configuracion" id="agregar_carrera"
                            data-accion="agregar_carrera">Agregar</button>
                        <button class="btn_listar_usuarios btn-configuracion">Listar Usuarios</button>
                    </div>
                    <div class="contenedor-carreras">
                        <?php
                        // Asegúrate de que la consulta esté siendo ejecutada y $carreras tenga resultados
                        while ($registro = mysqli_fetch_array($carreras)) {
                            componenteCarrerasConfiguracion($registro);
                        }
                        ?>
                    </div>
                </div>
            </div>
    </main>

    <footer class="footer">
        <div class="contenido_footer">
            <div class="siguenos">
                <p>Siguenos en</p>
                <div class="redes">
                    <a href="https://www.facebook.com/ITSHuatusco/?locale=es_LA" target="_blank">
                        <img src="../../assets/iconos/ic_facebook.webp" alt="icono de facebook">
                    </a>
                    <a href="https://www.instagram.com/itshuatusco/?hl=es-la" target="_blank">
                        <img src="../../assets/iconos/ic_instagram.webp" alt="icono de facebook">
                    </a>
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

    <template id="plantilla_configurar_carrera">
        <div class="overlay_cambiar-contraseña overlay_ventana">
            <form class="formulario" method="post">
                <h2 class="titulo">Configurar Carrera</h2>
                <div class="inputs-cambio-contraseña">

                    <input hidden type="text" id="carrera_antigua" name="carrera_antigua">
                    <label for="carrera_modificar" class="contenedor_input">
                        <input class="input_login" type="text" name="carrera_nueva" id="carrera_modificar"
                            placeholder=" " autocomplete="current-password">
                        <span class="nombre_input">Carrera</span>
                    </label>

                    <div class="opciones_carrera">
                        <label for="cantidad_grupos" class="contenedor_numerico">
                            <span class="nombre_opcion">Cantidad de Grupos</span>
                            <input min="1" max="15" step="1" value="1" class="input_num" type="number"
                                name="grupos_nueva_carrera" id="cantidad_grupos" placeholder=" "
                                autocomplete="current-password">
                        </label>

                        <label for="id_grupo" class="contenedor_numerico">
                            <span class="nombre_opcion">Id de la Carrera</span>
                            <input min="1" max="10" step="1" value="1" class="input_num" type="number"
                                name="id_carrera_nueva" id="id_grupo" placeholder=" " autocomplete="current-password">
                        </label>
                    </div>

                </div>
                <input type="submit" name="formulario" class="btn-submit btn_login" value="Modificar Carrera">


                <img class="close" id="cerrar" src="../../assets/iconos/ic_close.webp"
                    alt="icono para cerrar la ventana de agregar carrera" loading="lazy">
            </form>
        </div>
    </template>

    <template id="plantilla_agregar_carrera">
        <div class="overlay_cambiar-contraseña overlay_ventana">
            <form class="formulario" method="post">
                <h2 class="titulo">Agregar Carrera</h2>
                <div class="inputs-cambio-contraseña">

                    <section class="seccion_tipo">

                        <label for="contraseña_actual" class="contenedor_input">
                            <input class="input_login" type="text" name="carrera_nueva" id="contraseña_actual"
                                placeholder=" " autocomplete="current-password">
                            <span class="nombre_input">Escriba la Carrera</span>
                        </label>
                        <div class="opciones_carrera">
                            <label for="cantidad_grupos" class="contenedor_numerico">
                                <span class="nombre_opcion">Cantidad de Grupos</span>
                                <input min="1" max="15" step="1" value="1" class="input_num" type="number"
                                    name="grupos_nueva_carrera" id="cantidad_grupos" placeholder=" "
                                    autocomplete="current-password">
                            </label>

                            <label for="id_grupo" class="contenedor_numerico">
                                <span class="nombre_opcion">Id de la Carrera</span>
                                <input min="1" max="10" step="1" value="1" class="input_num" type="number"
                                    name="id_carrera_nueva" id="id_grupo" placeholder=" "
                                    autocomplete="current-password">
                            </label>
                        </div>
                    </section>

                    <section class="seccion_tipo">
                        <label for="tipo_carrera" class="select_carrera">
                            <span class="nombre_select">Escoga el Tipo de Carrera</span>
                            <select name="tipo_carrera" id="tipo_carrera">
                                <option value="Licenciatura">Licenciatura</option>
                                <option value="Ingenieria">Ingenieria</option>
                            </select>
                        </label>

                        <label for="modalidades" class="select_carrera">
                            <span class="nombre_select" id="modalidades">Escoga las modalidades</span>
                            <div class="contenedor_radio">
                                <input type="radio" name="modalidad" value="Escolarizado">
                                <span>Escolarizado</span>
                            </div>
                            <div class="contenedor_radio">
                                <input type="radio" name="modalidad" value="Flexible">
                                <span>Flexible</span>
                            </div>
                        </label>
                    </section>

                </div>
                <input type="submit" name="formulario" class="btn-submit btn_login" value="Agregar Carrera">


                <img class="close" id="cerrar" src="../../assets/iconos/ic_close.webp"
                    alt="icono para cerrar la ventana de agregar carrera" loading="lazy">
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
    <template id="miTemplate_cargar">

        <div class="overlay" id="overlay">
            <div class="notificacion">
                <img class="img_notificacion" src="" alt="icono de notificacion" id="imagen">
                <div class="contenido_notificacion ">
                    <p id="mensaje"></p>
                </div>
                <button class="btn_mensaje" id="btn_mensaje" onclick="cerrarTemplate('cargar')">Cerrar</button>
            </div>
        </div>

    </template>
</body>

</html>

<?php


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['formulario'])) {

        if ($_POST['formulario'] === 'Agregar Carrera') {
            $carreraNueva = trim($_POST['carrera_nueva']);
            $Numero_grupos = trim($_POST['grupos_nueva_carrera']);
            $id_carrera = trim($_POST['id_carrera_nueva']);
            $administrador->AgregarCarrera($conexion, $carreraNueva, $Numero_grupos, $id_carrera);
        }
        if ($_POST['formulario'] === 'Modificar Carrera') {
            $carreraAntigua = trim($_POST['carrera_antigua']);
            $carreraNueva = trim($_POST['carrera_nueva']);
            $Numero_grupos = trim($_POST['grupos_nueva_carrera']);
            $id_carrera = trim($_POST['id_carrera_nueva']);
            $administrador->modificarCarrera($conexion, $carreraAntigua, $carreraNueva, $Numero_grupos, $id_carrera);
        }
    }
    notificaciones($_SESSION["mensaje"]);
}