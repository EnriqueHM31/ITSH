<?php
session_start();
include("../conexion/conexion.php");
include("../clases/usuario.php");
include("../clases/administrador.php");
$usuario = new usuario();
$administrador = new administrador();
$carreraJefe =  $_SESSION["carrera_jefe"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Sistema de Justificantes ITSH</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pagina añadir del jefe de carrera">
    <link rel="shortcut icon" href="../img/logo_ITSH.png" type="image/x-icon">
    <link rel="stylesheet" href="../Tipografia/fonts.css">
    <link rel="stylesheet" href="../styles/plantilla.css">
    <link rel="stylesheet" href="../styles/notificacion.css">
    <link rel="stylesheet" href="../styles/Añadir.css">
    <script src="../js/index.js" defer></script>
    <script src="../js//añadir_admin.js" defer></script>
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
            <li class="menu-item"><a href="#" class="link">Eliminar</a></li>
            <li class="menu-item"><a href="../conexion/cerrar_sesion.php" class="link"><img src="../img/iconos/ic_cerrar_sesion.png" alt="icono de cerrar sesion"></a></li>
        </ul>
    </nav>

    <main class="main">
        <div class="contenedor_logo">
            <img src="../img/logo_ITSH.png" alt="logo del ITSH">
        </div>

        <div class="contenedor_main">


            <img src="../img/encabezado.png" alt="los encabezados de la pagina">

            <form class="formulario" method="post" enctype="multipart/form-data">
                <label for="clave" class="contenedor_input">
                    <input /*pattern="^ITSH_\d{4}$" */ class="input_pagina" type="text" name="clave" id="clave" placeholder=" ">
                    <span class="nombre_input">Matricula</span>
                </label>

                <label for="nombre" class="contenedor_input">
                    <input pattern="^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$" class="input_pagina" type="text" name="nombre" id="nombre" placeholder=" ">
                    <span class="nombre_input">Nombre</span>
                </label>

                <label for="apellidos" class="contenedor_input">
                    <input pattern="^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$" class="input_pagina" type="text" name="apellidos" id="apellidos" placeholder=" ">
                    <span class="nombre_input">Apellidos</span>
                </label>

                <label for="carrera" class="contenedor_input">
                    <select class="input_pagina select_info" id="carrera" name="carrera">
                        <option class="opcion_select" value="<?php echo $carreraJefe; ?>"><?= explode(' ', $carreraJefe)[0]; ?></option>
                    </select>
                    <span class="nombre_input">Carrera</span>
                </label>

                <label for="rol" class="contenedor_input">
                    <select class="input_pagina select_info" id="rol" name="rol">
                        <option class="opcion_select" value="Jefe de Carrera">Estudiante</option>
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
                    <input type="file" name="archivo_csv" id="archivo" class="archivo" accept=".pdf,.csv">

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
                    <a href="https://www.facebook.com/ITSHuatusco/?locale=es_LA" target="_blank"><img src="../img/iconos/ic_facebbok.png" alt="icono de facebook"></a>
                    <a href="https://www.instagram.com/itshuatusco/?hl=es-la" target="_blank"><img src="../img/iconos/ic_instagram.png" alt="icono de instagrams"></a>
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


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $administrador->realizarOperacionFormAñadir($conexion);
    $usuario->notificaciones($_SESSION["mensaje"]);

    $conexion->close();
}
?>