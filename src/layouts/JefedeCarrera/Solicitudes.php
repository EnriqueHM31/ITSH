<?php

session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../utils/functionGlobales.php";
include "../../clases/jefe.php";
include "../../conexion/verificar acceso.php";

$usuario = new usuario();
$jefeCarrera = new Jefe();
$id = $_SESSION["id"];
$data = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, $_SESSION["id"]);
$id_carrera = $data[Variables::CAMPO_ID_CARRERA];
$carreraJefe = getResultCarrera($conexion, $id_carrera);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Solicitudes De Los Alumnos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Pagina donde el jefe de carrera puede ver las solicitudes para justificantes de los estudiantes">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/Añadir.css">
    <link rel="stylesheet" href="../../assets/styles/Modificar.css">
    <link rel="stylesheet" href="../../assets/styles/tablaSolicitudes.css">
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

            <li class="menu-item"><a href="../../conexion/cerrar_sesion.php" class="link"><img
                        src="../../assets/iconos/ic_cerrar_sesion.webp" alt="icono de cerrar sesion"></a></li>
        </ul>
    </nav>

    <main class="main">
        <div class="contenedor_logo">
            <img src="../../assets/extra/logo.svg" alt="logo del ITSH">
        </div>

        <div class="contenedor_main">
            <div class="contenedor">
                <img src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina" width="1000px"
                    height="164">
            </div>
            <div class="contenido_solicitudes">
                <a href="./HistorialJustificantes.php" class="btn_historial">
                    Justificantes
                </a>

                <?php
                $data = $jefeCarrera->TablaSolicitudesRegistros($conexion, $carreraJefe);
                if ($data->num_rows > 0) {
                    ?>

                    <table>
                        <tr>
                            <th>Solicitud</th>
                            <th>Matricula</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Grupo</th>
                            <th>Motivo</th>
                            <th>Fecha</th>
                            <th>Evidencia</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                        <?php
                        $jefeCarrera->MostrarSolicitudes($data, $id);

                } else {
                    echo "<p class='sin_solicitudes'>No hay solicitudes</p>";
                }
                ?>
                </table>
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