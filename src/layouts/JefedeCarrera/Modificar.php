<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../utils/functionGlobales.php";
include "../../clases/jefe.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_jefe.php";
include "../../query/obtenerGrupos.php";
include "../../validaciones/Validaciones.php";
include "../../Components/Usuario.php";
include "../../Components/Layout.php";



$usuario = new usuario();
$jefe = new jefe();
$rol = $_SESSION["rol"];
$data = getResultDataTabla($conexion, $TABLA_JEFE, $CAMPO_ID_USUARIO, $_SESSION["id"]);
$id_carrera = $data[$CAMPO_ID_CARRERA];
$carreraJefe = getResultCarrera($conexion, $id_carrera);
$seccion = "Modificar";
$GruposCarrera = obtenerGrupos($conexion, $id_carrera);
$grupos = $GruposCarrera[0][0];
$modalidades = $GruposCarrera[1][0]["Modalidades"];
$id_grupos = $grupos["clave_grupo"];
$Numero_grupos = $grupos["numero_grupos"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Modificar Usuarios Jefe De Carrera</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Pagina donde los jefes de carrera pueden modificar datos de los usuarios de tipo estudiante">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/buscador.js"></script>
    <script src="../../assets/js/modificar.js" defer></script>
    <script src="../../assets/js/opcionesSelect.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
</head>

<body data-carrera="<?php echo $carreraJefe ?>" data-modo="<?php echo $seccion ?>" data-rol="<?php echo $rol ?>">

    <?php
    componenteNavegacionLayout($rol);
    ?>

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



                <form class="formulario" method="post">

                    <input type="hidden" name="clave_anterior" id="clave_anterior">
                    <label for="clave" class="contenedor_input">
                        <input class="input_pagina select_info" type="text" name="clave" id="clave"
                            style="background-color: var(--vino);" readonly>
                        <span class="nombre_input">Matricula</span>
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

                    <label for="modalidad" class="contenedor_input">
                        <select class="input_pagina select_info" id="modalidad" name="modalidad"
                            data-modalidades="<?php echo $modalidades ?>">


                        </select>
                        <span class="nombre_input">Modalidad</span>
                    </label>

                    <label for="grupo" class="contenedor_input">
                        <select class="input_pagina select_info" name="grupo" id="grupo"
                            data-carrera='<?php echo $carreraJefe ?>' data-id_grupos="<?php echo $id_grupos ?>"
                            data-numero_grupos="<?php echo $Numero_grupos ?>">

                        </select>

                        <span class="nombre_input">Grupos</span>
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

    <?php
    componenteFooter();
    ?>

    <?php
    componenteTemplateModalNormal();
    ?>

</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jefe->actualizarUsuario($conexion, $_POST["clave_anterior"], $_POST);
    notificaciones($_SESSION["mensaje"]);
}