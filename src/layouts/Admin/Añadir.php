<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../clases/administrador.php";
include "../../validaciones/Validaciones.php";
include "../../utils/functionGlobales.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_admin.php";


$usuario = new usuario();
$administrador = new administrador();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Agregar Usuarios Administrador</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Pagina para que el administrador pueda añadir usuarios administradores o jefes de carrera">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="preload" href="/src/assets/Fonts/fonts/Poppins/Poppins-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/src/assets/Fonts/fonts/Manrope/Manrope-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/Añadir.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
    <script src="../../assets/js/opcionesSelect.js" defer></script>
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

        <div class="contenedor_logo">
            <img src="../../assets/extra/logo.svg" alt="logo del ITSH">
        </div>

        <div class="contenedor_main">


            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">

            <form class="formulario" method="post" enctype="multipart/form-data">
                <label for="clave" class="contenedor_input">
                    <input /*pattern="^ITSH_\d{4}$" */ class="input_pagina" type="text" name="clave" id="clave"
                        placeholder=" ">
                    <span class="nombre_input">Clave</span>
                </label>

                <label for="nombre" class="contenedor_input">
                    <input pattern="^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$" class="input_pagina" type="text" name="nombre" id="nombre"
                        placeholder=" ">
                    <span class="nombre_input">Nombre</span>
                </label>

                <label for="apellidos" class="contenedor_input">
                    <input pattern="^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$" class="input_pagina" type="text" name="apellidos"
                        id="apellidos" placeholder=" ">
                    <span class="nombre_input">Apellidos</span>
                </label>

                <label for="carrera" class="contenedor_input">
                    <select class="input_pagina select_info" id="carrera" name="carrera">
                    </select>
                    <span class="nombre_input">Carrera</span>
                </label>

                <label for="rol" class="contenedor_input">
                    <select class="input_pagina select_info" id="rol" name="rol">
                        <option class="opcion_select" value="Jefe de Carrera">Jefe de Carrera</option>
                        <option class="opcion_select" value="Administrador">Administrador</option>
                    </select>
                    <span class="nombre_input">Cargo</span>
                </label>

                <label for="correo" class="contenedor_input">
                    <input class="input_pagina" type="text" name="correo" id="correo" placeholder=" ">
                    <span class="nombre_input">Correo</span>
                </label>

                <input id="añadir" class="btn_pagina" type="submit" value="Registrar">

                <label for="archivo" class="btn_archivo">
                    <input type="file" name="archivo_csv" id="archivo" class="archivo" accept=".csv">

                    <span>Cargar</span>
                    <span id="nombreArchivo"></span>
                </label>

            </form>

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
                        <img src="../../assets/iconos/ic_instagram.webp" alt="icono de instagrams">
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
</body>

</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $administrador->realizarOperacionFormAñadir($conexion);
    notificaciones($_SESSION["mensaje"]);
}

$conexion->close();

?>