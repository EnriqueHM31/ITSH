<?php
include("../conexion/conexion.php");
include("../clases/usuario.php");

$usuario = new usuario();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Sistema de Justificantes ITSH</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pagina de modificar del administrador">
    <link rel="shortcut icon" href="../img/logo_ITSH.png" type="image/x-icon">
    <link rel="stylesheet" href="../Tipografia/fonts.css">
    <link rel="stylesheet" href="../styles/plantilla.css">
    <link rel="stylesheet" href="../styles/notificacion.css">
    <link rel="stylesheet" href="../styles/Añadir.css">
    <link rel="stylesheet" href="../styles/Modificar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="../js/index.js" defer></script>
    <script src="../js/modificar.js" defer></script>
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
            <li class="menu-item"><a href="Admin.php" class="link">Inicio</a></li>
            <li class="menu-item"><a href="Añadir.php" class="link">Añadir</a></li>
            <li class="menu-item"><a href="Modificar.php" class="link">Modificar</a></li>
            <li class="menu-item"><a href="Admin.php?Eliminar=true" class="link">Eliminar</a></li>
            <li class="menu-item"><a href="../conexion/cerrar_sesion.php" class="link"><img src="../img/iconos/ic_cerrar_sesion.png" alt="icono de cerrar sesion"></a></li>
        </ul>
    </nav>

    <main class="main">
        <div class="contenedor_logo">
            <img src="../img/logo_ITSH.png" alt="logo del ITSH">
        </div>

        <div class="contenedor_main">
            <div class="contenedor">
                <img src="../img/encabezado.png" alt="los encabezados de la pagina">

                <div class="contenedor_buscar">
                    <label class="contenedor_buscar">
                        <input type="search" name="buscar" id="buscar" class="buscar" placeholder="Buscar" onkeyup="buscarUsuarios()">
                    </label>
                    <div id="resultados" class="result_usuarios"></div>
                </div>



                <form class="formulario" method="post">

                    <input type="hidden" name="clave_anterior" id="clave_anterior">
                    <label for="clave" class="contenedor_input">
                        <input readonly pattern="^ITSH_\d{4}$" class="input_pagina" type="text" name="clave" id="clave" placeholder=" ">
                        <span class="nombre_input">Clave</span>
                    </label>

                    <label for="nombre" class="contenedor_input">
                        <input pattern="^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$" class="input_pagina" type="text" name="nombre" id="nombre" placeholder=" ">
                        <span class="nombre_input">Nombre</span>
                    </label>

                    <label for="apellidos" class="contenedor_input">
                        <input class="input_pagina" type="text" name="apellidos" id="apellidos" placeholder=" ">
                        <span class="nombre_input">Apellidos</span>
                    </label>

                    <label for="carrera" class="contenedor_input">
                        <select class="input_pagina select_info" id="carrera" name="carrera">
                            <option class="opcion_select" value="Sistemas Computacionales">Sistemas</option>
                            <option class="opcion_select" value="Quimica">Quimica</option>
                            <option class="opcion_select" value="Ambiental">Ambiental</option>
                            <option class="opcion_select" value="Gestion Empresarial">Gestion</option>
                            <option class="opcion_select" value="Contaduria">Contaduria</option>
                            <option class="opcion_select" value="Industrial">Industrial</option>
                            <option class="opcion_select" value="Alimentarias">Alimentarias</option>
                            <option class="opcion_select" value="Electromecanica">Electromecanica</option>
                            <option class="opcion_select" value="null">Null</option>
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
                    <a href="https://www.facebook.com/ITSHuatusco/?locale=es_LA" target="_blank"><img src="../img/iconos/ic_facebbok.png" alt="icono de facebook"></a>
                    <a href="https://www.instagram.com/itshuatusco/?hl=es-la" target="_blank"><img src="../img/iconos/ic_instagram.png" alt="icono de instagram"></a>
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
</body>

</html>