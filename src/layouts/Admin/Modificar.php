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
$rol = $_SESSION["rol"];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Modificar Usuarios Administrador</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Pagina para que el administrador modifique usuarios que sean administradores o jefes de carrera">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/buscador.js" defer></script>
    <script src="../../assets/js/modificar.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
    <script src="../../assets/js/opcionesSelect.js" defer></script>

</head>

<body data-modo="Modificar" data-carrera="" data-rol="<?php echo $rol ?>">

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
            <div class="contenedor">
                <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">

                <div class="contenedor_buscar">
                    <label class="contenedor_buscar">
                        <input type="search" name="buscar" id="buscar" class="buscar" placeholder="Buscar">
                    </label>
                    <div id="resultados" class="result_usuarios"></div>
                </div>



                <form class="formulario" method="post" id="form-modificar">

                    <input type="text" hidden id="clave_anterior" name="clave_anterior">

                    <label for="clave" class="contenedor_input">
                        <input readonly pattern="^ITSH_\d{4}$" class="input_pagina" type="text" name="clave" id="clave"
                            placeholder=" ">
                        <span class="nombre_input input_bloqueado">Clave<span />
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
                    <a href="https://www.facebook.com/ITSHuatusco/?locale=es_LA" target="_blank">
                        <img src="../../assets/iconos/ic_facebook.webp" alt="icono de facebook">
                    </a>
                    <a href="https://www.instagram.com/itshuatusco/?hl=es-la" target="_blank">
                        <img src="../../assets/iconos/ic_instagram.webp" alt="icono de instagram">
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

    $administrador->modificarJefedeCarrera($conexion, $_POST['clave'], $_POST['nombre'], $_POST['apellidos'], $_POST['carrera'], $_POST['rol'], $_POST['correo']);

    notificaciones($_SESSION["mensaje"]);
}
?>