<?php
session_start();

include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/jefe.php";
include "../../utils/functionGlobales.php";
include "../../validaciones/Validaciones.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_jefe.php";
include "../../query/obtenerGrupos.php";
include "../../Components/Layout.php";

$jefe = new jefe();
$rol = $_SESSION["rol"];
$dataJefeCarrera = getResultDataTabla($conexion, $TABLA_JEFE, $CAMPO_CLAVE_EMPLEADO_JEFE, $_SESSION["id"]);
$id_carrera = $dataJefeCarrera[$CAMPO_ID_CARRERA];
$carreraJefe = getResultCarrera($conexion, $id_carrera);
[$id_grupos, $Numero_grupos, $modalidades] = $jefe->ObtenerGruposDeLaCarrera($conexion, $id_carrera);
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
    <link rel="preload" href="/src/assets/Fonts/fonts/Poppins/Poppins-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/src/assets/Fonts/fonts/Manrope/Manrope-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/Añadir.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/opcionesSelect.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
</head>

<body data-rol="<?php echo $rol ?>">

    <?php
    componenteNavegacionLayout($rol);
    ?>

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
                    <select class="input_pagina select_info" id="modalidad" name="modalidad"
                        data-modalidades="<?php echo $modalidades ?>">

                    </select>
                    <span class="nombre_input">Modalidad</span>
                </label>

                <label for="grupo" class="contenedor_input">
                    <select class="input_pagina select_info" name="grupo" id="grupo"
                        data-carrera="<?php echo $carreraJefe ?>" data-id_grupos="<?php echo $id_grupos ?>"
                        data-numero_grupos="<?php echo $Numero_grupos ?>">

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

    <?php
    componenteFooter();
    ?>
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