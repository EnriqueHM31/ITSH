<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../utils/functionGlobales.php";
include "../../clases/jefe.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_jefe.php";


$jefe = new Jefe();

$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$id_carrera = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, $id);
$carrera = getResultCarrera($conexion, $id_carrera[Variables::CAMPO_ID_CARRERA])
    ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Historial De Justificantes</title>
    <meta charset="UTF-8">
    <meta name="description" content="Pagina donde el jefe de carrera puede ver todos los justificantes aprobados">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/Inicio.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/templates.css">
    <link rel="stylesheet" href="../../assets/styles/historialJustificantes.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/jefeCarrera.js" defer></script>
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
            <li class="menu-item close_contenedor"><img class="close_menu" src="../../assets/iconos/ic_close.webp"
                    alt="Imagen para cerrar el menu movil"></li>
        </ul>

        <img src="../../assets/iconos/ic_menu_movil.webp" alt="icono para el menu en movil" class="icono_menu">
    </nav>

    <main class="main">
        <div class="contenedor_main">
            <div class="contenedor_logo">
                <img src="../../assets/extra/logo.svg" alt="logo del ITSH">
            </div>
            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">

            <div class="contenido_opciones">
                <button class="btn_reiniciar" onclick="mostrarModal()">Reiniciar Folio</button>
                <input type="search" name="buscar_folio" id="search-justificantes">
            </div>

            <div class="contenido_historial" id="historial">
                <?php
                $jefe->HistorialJustificantes($conexion, $carrera);
                ?>
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


    <template id="modal_seguridad">
        <div class=" overlay overlay_eliminar overlay_ventana" id="overlay">

            <div class="modal">
                <h2>¿Esta seguro de reiniciar el Folio</h2>
                <p>Se borraran todos los justificantes creados hasta el momento</p>

                <div class="opciones_decision">
                    <button class="btn_opcion" onclick="reiniciarFolio()">Si</button>
                    <button class="btn_opcion" onclick="cerrarTemplate()">No</button>
                </div>

                <span>
                    Se aguardan en el respaldo por si se requieren<br>
                    Se recomienda hacerlo cada inicio de semestre
                </span>
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