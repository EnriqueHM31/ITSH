<?php
session_start();
include("../conexion/conexion.php");
include("../clases/usuario.php");

$usuario = new usuario();
$id = $_SESSION["id"];



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Sistema de Justificantes ITSH</title>
    <meta charset="UTF-8">
    <meta name="description" content="Pagina principal del administrador">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/logo_ITSH.png" type="image/x-icon">
    <link rel="stylesheet" href="../Tipografia/fonts.css">
    <link rel="stylesheet" href="../styles/plantilla.css">
    <link rel="stylesheet" href="../styles/Admin.css">
    <link rel="stylesheet" href="../styles/notificacion.css">

    <script src="../js/index.js" defer></script>
</head>

<body>

    <nav class="navegacion">

        <div class="gobierno">
            <img src="../img/iconos/ic_gobierno.png" alt="icono del gobierno de Mexico">

            <div class="texto_gobierno">
                <h3>Gobierno de</h3>
                <h4>Mexico</h4>
            </div>
        </div>

        <ul class="menu">
            <li class="menu-item"><a href="JefeCarrera.php" class="link">Inicio</a></li>
            <li class="menu-item"><a href="Añadir.php" class="link">Añadir</a></li>
            <li class="menu-item"><a href="Modificar.php" class="link">Modificar</a></li>
            <li class="menu-item"><a href="Admin.php?Eliminar=true" class="link">Eliminar</a></li>
            <li class="menu-item"><a href="../conexion/cerrar_sesion.php" class="link"><img src="../img/iconos/ic_cerrar_sesion.png" alt="icono de cerrar sesion"></a></li>
            <li class="menu-item close_contenedor"><img class="close_menu" src="../img/iconos/ic_close.png" alt="Imagen para cerrar el menu movil"></li>
        </ul>

        <img src="../img/iconos/ic_menu_movil.png" alt="icono para el menu en movil" class="icono_menu">
    </nav>

    <main class="main">
        <div class="contenedor_main">
            <img src="../img/encabezado.png" alt="los encabezados de la pagina">
            <h2>Bienvenido Jefe de Carrera</h2>
            <div class="informacion_usuario">


                <div class="contenido_informacion">
                    <?php
                    $sql = "SELECT * FROM personal WHERE identificador = '$id'";
                    $result = $conexion->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<div class='contenedor-datos'>";

                        while ($row = $result->fetch_assoc()) {
                            echo "<p><strong>Identificador:</strong> " . $row["identificador"] . "</p>";
                            echo "<p><strong>Nombre:</strong> " . $row["nombre"] . "</p>";
                            echo "<p><strong>Apellidos:</strong> " . $row["apellidos"] . "</p>";
                            echo "<p><strong>Correo:</strong> " . $row["correo"] . "</p>";
                        }
                        echo "<button id='btn_contraseña' class='btn_vino'>Cambiar contraseña</button>";
                        echo "</div>";
                    } else {
                        echo "no hay datos";
                    }
                    ?>


                </div>

                <div class="contenedor_ITSH">
                    <img src="../img/logo_ITSH.png" alt="Logo del ITSH">
                </div>
            </div>

        </div>


    </main>

    <footer class="footer">
        <div class="contenido_footer">
            <div class="siguenos">
                <p>Siguenos en</p>
                <div class="redes">
                    <a href="https://www.facebook.com/ITSHuatusco/?locale=es_LA" target="_blank"><img src="../img/iconos/ic_facebbok.png" alt="icono de facebook"></a>
                    <a href="https://www.instagram.com/itshuatusco/?hl=es-la" target="_blank"><img src="../img/iconos/ic_instagram.png" alt="icono de facebook"></a>
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
            <img src="../img/iconos/ic_gobierno.png" alt="icono del gobierno de Mexico">

            <div class="texto_gobierno">
                <p>Gobierno de Mexico</p>

            </div>
        </div>

    </footer>

    <template id="plantilla_cambiar-contraseña">
        <div class="overlay_cambiar-contraseña overlay_ventana">
            <form class="formulario" method="post">
                <h2 class="titulo">Cambiar Contraseña</h2>
                <label for="contraseña_actual" class="contenedor_input">
                    <input class="input_login" type="text" name="contraseña_actual" id="contraseña_actual" placeholder=" " autocomplete="current-password">
                    <span class="nombre_input">Contraseña actual</span>
                </label>

                <label for="contraseña_nueva" class="contenedor_input">
                    <input class="input_login" type="password" name="contraseña_nueva" id="contraseña_nueva" placeholder=" " autocomplete="new-password">
                    <span class="nombre_input">Contraseña nueva</span>
                </label>

                <input type="submit" name="formulario" class="btn-submit btn_login" value="Cambiar">

                <img class="close" id="cerrar" src="../img/iconos/ic_close.png" alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">
            </form>
        </div>
    </template>

    <template id="plantilla_eliminar-personal">
        <div class=" overlay overlay_eliminar">
            <form class="formulario form_eliminar" method="post">
                <h2 class="titulo titulo_eliminar">Eliminar Registro</h2>
                <label for="buscar" class="contenedor_input">
                    <input class="input_buscar" type="search" name="buscar" id="buscar" placeholder="Buscar" onkeyup="buscarUsuarios()">

                </label>
                <div id="resultados"></div>
                <img class="close" src="../img/iconos/ic_close.png" alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">

                <button type="button" class="btn_eliminar" id="eliminar_registro">Eliminar</button>
            </form>

        </div>
    </template>

    <template id="plantilla_usuario-seleccionado">
        <div class=" overlay overlay_eliminar overlay_ventana">
            <form id="formulario_eliminar" class="formulario form_eliminar form_verificar_eliminar" method="post">

                <h2 class="titulo_eliminar">Estas seguro de eliminar a: </h2>

                <p class="info_usuario_eliminar">Clave: <span id="clave_info"></span></p>
                <p class="info_usuario_eliminar">Nombre: <span id="nombre_info"></span></p>
                <p class="info_usuario_eliminar">Apellidos: <span id="apellidos_info"></span></p>
                <p class="info_usuario_eliminar">Carrera: <span id="carrera_info"></span></p>
                <p class="info_usuario_eliminar">Cargo: <span id="cargo_info"></span></p>
                <input type="hidden" name="identificador" value="" id="identificador">

                <img class="close close_eliminar" src="../img/iconos/ic_close.png" alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">

                <input type="submit" name="formulario" value="Eliminar" class="btn_eliminar btn_ventana-eliminar">
            </form>

        </div>
    </template>


</body>

</html>
