<?php
session_start();
include("../conexion/conexion.php");
include("../clases/usuario.php");
include("../clases/alumno.php");

$usuario = new usuario();
$alumno = new alumno();
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
    <link rel="stylesheet" href="../styles/notificacion.css">
    <link rel="stylesheet" href="../styles/solicitud.css">
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
            <li class="menu-item"><a href="alumno.php" class="link">Inicio</a></li>
            <li class="menu-item"><a href="Crear Solicitud.php" class="link">Crear Solicitud</a></li>
            <li class="menu-item"><a href="Historial Alumno.php" class="link">Historial</a></li>
            <li class="menu-item"><a href="../conexion/cerrar_sesion.php" class="link">Cerrar Sesion</a></li>
            <li class="menu-item close_contenedor"><img class="close_menu" src="../img/iconos/ic_close.png" alt="Imagen para cerrar el menu movil"></li>
        </ul>

        <img src="../img/iconos/ic_menu_movil.png" alt="icono para el menu en movil" class="icono_menu">
    </nav>

    <main class="main">
        <div class="contenedor_main">

            <h2>Crear Solicitud</h2>

            <?php
            $sql = "SELECT * FROM personal WHERE identificador = '$id'";
            $result = $conexion->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) :
            ?>
                    <form class="formulario_solicitud" method="POST" enctype="multipart/form-data">

                        <div class="contenedor_info-solicitud">
                            <p>Matricula:</p>
                            <input type="text" id="id" name="identificador" value="<?php echo $row['identificador']; ?>" readonly>

                            <input type="text" id="grupo" name="grupo" value="<?php echo $row['identificador']; ?>" readonly>
                        </div>

                        <div class="contenedor_info-solicitud">
                            <p>Nombre:</p>
                            <input type="text" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>" readonly>
                        </div>

                        <div class="contenedor_info-solicitud">
                            <p>Apellidos:</p>
                            <input type="text" id="apellidos" name="apellidos" value="<?php echo $row['apellidos']; ?>" readonly>
                        </div>

                        <div class="contenedor_info-solicitud">
                            <p for="email">Carrera:</p>
                            <input type="text" id="carrera" name="carrera" value="<?php echo $row['carrera']; ?>" readonly>
                        </div>

                        <div class="contenedor_info-solicitud">
                            <p>Motivo:</p>
                            <select name="motivo" id="motivo">
                                <option value="Personal">Personal</option>
                                <option value="Laboral">Laboral</option>
                                <option value="Salud">Salud</option>
                            </select>
                        </div>

                        <div class="contenedor_info-solicitud">
                            <p>Fecha de Ausencia: </p>
                            <input class="fecha_ausencia" type="date" name="fecha_ausencia" id="fecha_de_ausencia">
                        </div>

                        <label for="archivo" class="btn_archivo">
                            <input type="file" name="archivo_evidencia" id="archivo" class="archivo" accept="application/pdf">

                            <span id="nombreArchivo">Cargar evidencia</span>
                        </label>

                        <input type="submit" value="Enviar Solicitud" class="btn_enviar-solicitud">
                    </form>
            <?php
                endwhile;
            }
            ?>

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

</body>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alumno->enviarSolicitud($conexion);
    $usuario->notificaciones($_SESSION["mensaje"]);
}
?>