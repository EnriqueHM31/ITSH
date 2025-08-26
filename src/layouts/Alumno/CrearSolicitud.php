<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../clases/alumno.php";
include "../../validaciones/Validaciones.php";
include "../../utils/functionGlobales.php";
include "../../conexion/verificar acceso.php";
include "../../Components/Layout.php";
include "../../Components/Alumno.php";
include "../../Components/Usuario.php";


$usuario = new usuario();
$alumno = new alumno();
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$row = $alumno->ponerDatosFormulario($conexion, $id);
$estudianteData = ObtenerDatosDeUnaTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $id);

$dataJefe = ObtenerDatosDeUnaTabla($conexion, $TABLA_JEFE, $CAMPO_ID_CARRERA, $estudianteData[$CAMPO_ID_CARRERA]);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Crear Solicitud</title>
    <meta charset="UTF-8">
    <meta name="description" content="Pagina para crear las solicitudes para los justificantes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="preload" href="/src/assets/Fonts/fonts/Poppins/Poppins-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/src/assets/Fonts/fonts/Manrope/Manrope-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/solicitud.css">
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
</head>

<body>

    <?php
    componenteNavegacionLayout($rol);
    ?>

    <main class="main">

        <div class="contenedor_main">
            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">


            <h2>Crear Solicitud</h2>

            <form class="formulario_solicitud" method="POST" enctype="multipart/form-data">
                <div class="contenedor_info-solicitud">
                    <div>
                        <p>Matricula:</p>
                        <input type="text" id="id" name="identificador" value="<?php echo $row[$CAMPO_ID_USUARIO]; ?>"
                            readonly>
                    </div>

                    <div>
                        <p>Grupo:</p>
                        <input type="text" id="grupo" name="grupo" value="<?php echo $estudianteData[$CAMPO_GRUPO]; ?>"
                            readonly>
                    </div>

                </div>

                <div class="contenedor_info-solicitud">
                    <p>Nombre:</p>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $row[$CAMPO_NOMBRE]; ?>" readonly>
                </div>

                <div class="contenedor_info-solicitud">
                    <p>Apellidos:</p>
                    <input type="text" id="apellidos" name="apellidos" value="<?php echo $row[$CAMPO_APELLIDOS]; ?>"
                        readonly>
                </div>

                <div class="contenedor_info-solicitud">
                    <p for="email">Carrera:</p>
                    <input type="text" id="carrera" name="carrera"
                        value="<?php echo ObtenerNombreCarrera($conexion, $estudianteData[$CAMPO_ID_CARRERA]); ?>"
                        readonly>
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
                    <p>Ausente el: </p>
                    <div class="fecha_ausencia_container">
                        <input class="fecha_ausencia" type="text" id="rangoFechas" hidden name="rango_fechas" />
                        <input class="fecha_ausencia" type="date" name="fecha_ausencia" id="fecha_de_ausencia">
                        <button type="button" class="plazo" onclick="openModal()">...</button>
                    </div>

                </div>

                <label for="archivo" class="btn_archivo">
                    <input type="file" name="archivo_evidencia" id="archivo" class="archivo" accept="application/pdf">

                    <span id="nombreArchivo">Cargar evidencia</span>
                </label>

                <input type="submit" value="Enviar Solicitud" class="btn_enviar-solicitud">
            </form>

        </div>


    </main>

    <?php
    componenteFooter();
    componenteRangoFechas();
    componenteTemplateModalNormal()
    ?>

</body>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $matricula = $_POST['identificador'];
    $grupo = $_POST['grupo'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $carrera = $_POST['carrera'];
    $motivo = $_POST['motivo'];
    $fecha = "";
    if ($_POST['fecha_ausencia'] != null) {
        $fecha = $_POST['fecha_ausencia'];
    } else {
        $fecha = $_POST['rango_fechas'];
    }
    $archivo = $_FILES['archivo_evidencia'];

    $alumno->enviarSolicitud($conexion, $dataJefe[$CAMPO_ID_USUARIO], $matricula, $grupo, $nombre, $apellidos, $carrera, $motivo, $fecha, $archivo);

    if (isset($_SESSION["mensaje"])) {
        MostrarNotificacion("Se ha enviado la solicitud");
    }
}
?>