<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../utils/functionGlobales.php";
include "../../clases/jefe.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_jefe.php";
include "../../query/obtenerGrupos.php";
include "../../validaciones/Validaciones.php";



$usuario = new usuario();
$jefe = new jefe();
$rol = $_SESSION["rol"];
$data = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, $_SESSION["id"]);
$id_carrera = $data[Variables::CAMPO_ID_CARRERA];
$carreraJefe = getResultCarrera($conexion, $id_carrera);
$seccion = "Modificar";
$GruposCarrera = obtenerGrupos($conexion, $id_carrera);
$grupos = $GruposCarrera[0][0];
$modalidades = $GruposCarrera[1][0]["Modalidades"];
$id_grupos = $grupos["id_grupos"];
$Numero_grupos = $grupos["Numero_grupos"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Modificar Usuarios Jefe De Carrera</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Pagina donde los jefes de carrera pueden modificar datos de los usuarios de tipo estudiante">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/buscador.js"></script>
    <script src="../../assets/js/modificar.js" defer></script>
    <script src="../../assets/js/opcionesSelect.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
</head>

<body data-carrera="<?php echo $carreraJefe ?>" data-modo="<?php echo $seccion ?>" data-rol="<?php echo $rol ?>">

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
        <div class="contenedor_logo">
            <img src="../../assets/extra/logo.svg" alt="logo del ITSH">
        </div>

        <div class="contenedor_main">
            <div class="contenedor">
                <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">

                <div class="contenedor_buscar">
                    <label class="contenedor_buscar">
                        <input type="search" name="buscar" id="buscar" class="buscar" placeholder="Buscar">
                    </label>
                    <div id="resultados" class="result_usuarios"></div>
                </div>



                <form class="formulario" method="post">

                    <input type="hidden" name="clave_anterior" id="clave_anterior">
                    <label for="clave" class="contenedor_input">
                        <input class="input_pagina" type="text" name="clave" id="clave" placeholder=" ">
                        <span class="nombre_input">Clave</span>
                    </label>

                    <label for="nombre" class="contenedor_input">
                        <input pattern="^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$" class="input_pagina" type="text" name="nombre"
                            id="nombre" placeholder=" ">
                        <span class="nombre_input">Nombre</span>
                    </label>

                    <label for="apellidos" class="contenedor_input">
                        <input class="input_pagina" type="text" name="apellidos" id="apellidos" placeholder=" ">
                        <span class="nombre_input">Apellidos</span>
                    </label>

                    <label for="modalidad" class="contenedor_input">
                        <select class="input_pagina select_info" id="modalidad" name="modalidad"
                            data-modalidades="<?php echo $modalidades ?>">


                        </select>
                        <span class="nombre_input">Modalidad</span>
                    </label>

                    <label for="grupo" class="contenedor_input">
                        <select class="input_pagina select_info" name="grupo" id="grupo"
                            data-carrera='<?php echo $carreraJefe ?>' data-id_grupos="<?php echo $id_grupos ?>"
                            data-numero_grupos="<?php echo $Numero_grupos ?>">

                        </select>

                        <span class="nombre_input">Grupos</span>
                    </label>

                    <label for="correo" class="contenedor_input">
                        <input class="input_pagina" type="text" name="correo" id="correo" placeholder=" ">
                        <span class="nombre_input">Correo</span>
                    </label>

                    <input class="btn_pagina" type="submit" value="Modificar">

                </form>
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
                            src="../../assets/iconos/ic_instagram.webp" alt="icono de instagram"></a>
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jefe->actualizarUsuario($conexion, $_POST["clave_anterior"], $_POST);
    notificaciones($_SESSION["mensaje"]);
}