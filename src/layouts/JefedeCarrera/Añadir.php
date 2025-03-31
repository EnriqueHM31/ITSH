<?php
session_start();

include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../clases/jefe.php";
include "../../utils/functionGlobales.php";
include "../../validaciones/Validaciones.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_jefe.php";

$usuario = new usuario();
$jefe = new jefe();
$data = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, $_SESSION["id"]);
$id_carrera = $data[Variables::CAMPO_ID_CARRERA];
$carreraJefe = getResultCarrera($conexion, $id_carrera);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Anadir Usuarios Jefe De Carrera</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Pagina para que el jefe de carrera pueda añadir usuarios de tipo estudiante y que solo sean de su carrera">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/Añadir.css">
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/opcionesSelect.js" defer></script>
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

            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina" >

            <form class="formulario" method="post" enctype="multipart/form-data">
                <label for="clave" class="contenedor_input">
                    <input /*pattern="^ITSH_\d{4}$" */ class="input_pagina" type="text" name="clave" id="clave"
                        placeholder=" ">
                    <span class="nombre_input">Matricula</span>
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

                <label for="modalidad" class="contenedor_input">
                    <select class="input_pagina select_info" id="modalidad" name="modalidad">
                        <option class="opcion_select" value="Escolarizado">
                            Escolarizado
                        </option>
                        <option class="opcion_select" value="Flexible">
                            Flexible
                        </option>
                    </select>
                    <span class="nombre_input">Modalidad</span>
                </label>

                <label for="grupo" class="contenedor_input">
                    <select class="input_pagina select_info" name="grupo" id="grupo"
                        data-carrera="<?php echo $carreraJefe ?>">

                    </select>

                    <span class="nombre_input">Grupos</span>
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
                    <a href="https://www.facebook.com/ITSHuatusco/?locale=es_LA" target="_blank"><img
                            src="../../assets/iconos/ic_facebook.webp" alt="icono de facebook"></a>
                    <a href="https://www.instagram.com/itshuatusco/?hl=es-la" target="_blank"><img
                            src="../../assets/iconos/ic_instagram.webp" alt="icono de instagrams"></a>
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

    $matricula = trim($_POST["clave"]);
    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);
    $id_modalidad = trim(obtenerIdModalidad($conexion, $_POST["modalidad"]));
    $correo = trim($_POST["correo"]);
    $rol = Variables::MENU_DE_ROLES[2];
    $id_carrera = obtenerIDCarrera($conexion, $carreraJefe);
    $grupo = trim($_POST["grupo"]);
    $contraseña = 'Aa12345%';
    $jefe->realizarOperacionFormAñadirEstudiantes($conexion, $matricula, $contraseña, $rol, $nombre, $apellidos, $correo, $id_modalidad, $id_carrera, $grupo);
    notificaciones($_SESSION["mensaje"]);

    $conexion->close();
}
?>