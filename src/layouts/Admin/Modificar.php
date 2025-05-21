<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../clases/administrador.php";
include "../../validaciones/Validaciones.php";
include "../../utils/functionGlobales.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_admin.php";
include "../../Components/Usuario.php";
include "../../Components/Layout.php";



$usuario = new usuario();
$administrador = new administrador();
$rol = $_SESSION["rol"];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Modificar Usuarios Administrador</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Pagina para que el administrador modifique usuarios que sean administradores o jefes de carrera">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/buscador.js" defer></script>
    <script src="../../assets/js/modificar.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
    <script src="../../assets/js/opcionesSelect.js" defer></script>
    <script src="../../assets/js/admin.js" defer></script>

</head>

<body data-modo="Modificar" data-carrera="" data-rol="<?php echo $rol ?>">

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
                <h2>Modificar Usuario</h2>

                <div class="contenedor_buscar">
                    <label class="contenedor_buscar">
                        <input type="search" name="buscar" id="buscar" class="buscar" placeholder="Buscar">
                    </label>
                    <div id="resultados" class="result_usuarios"></div>
                </div>

                <form class="formulario" method="post" id="form-modificar">

                    <input type="text" hidden id="clave_anterior" name="clave_anterior">
                    <input type="text" hidden name="rol_antiguo" id="rol_antiguo">
                    <input type="text" hidden name="carrera_antigua" id="carrera_antigua">



                    <label for="clave" class="contenedor_input">
                        <input readonly pattern="^ITSH_\d{4}$" class="input_pagina select_info" type="text" name="clave"
                            id="clave">
                        <span class="nombre_input">Clave<span />
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

                    <label for="carrera" class="contenedor_input">
                        <select class="input_pagina select_info" id="carrera" name="carrera">
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

                    <input class="btn_pagina" style="margin-bottom: 20px;" type="submit" value="Modificar">

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
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $administrador->ModificarUsuarioDB($conexion, $_POST["clave_anterior"], $_POST);

    MostrarNotificacion($_SESSION["mensaje"]);
}
?>