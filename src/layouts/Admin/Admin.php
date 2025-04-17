<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../clases/administrador.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_admin.php";
include "../../Components/Usuario.php";
include "../../Components/Admin.php";
include "../../Components/Layout.php";
include "../../utils/functionGlobales.php";

$usuario = new usuario();
$administrador = new administrador();
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$correo = $_SESSION["correo"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Paginal Principal Administrador</title>
    <meta charset="UTF-8">
    <meta name="description" content="Pagina principal del administrador">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="preload" href="/src/assets/Fonts/fonts/Poppins/Poppins-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/src/assets/Fonts/fonts/Manrope/Manrope-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/Inicio.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/templates.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
    <script src="../../assets/js/eliminar.js" defer></script>
    <script src="../../assets/js/buscador.js" defer></script>
    <script src="../../assets/js/cambiarContraseñaInicio.js" defer></script>


</head>

<body data-modo="Eliminar" data-carrera="">

    <?php
    componenteNavegacionLayout($rol);
    ?>

    <main class="main">
        <div class="contenedor_main">
            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">
            <h2>Bienvenido Administrador</h2>
            <div class="informacion_usuario">


                <div class="contenido_informacion">
                    <?php
                    $usuario->escribirDatosDelUsuario($conexion, $id, $rol, $correo);
                    ?>
                </div>

                <div class="contenedor_ITSH">
                    <img src="../../assets/extra/logo.svg" alt="Logo del ITSH">
                </div>
            </div>

        </div>


    </main>

    <?php
    componenteFooter();
    ?>

    <?php
    componenteTemplateCambiarContraseña();
    componenteTemplateEliminar();
    componenteTemplateUsuarioAdminSeleccionado();
    componenteTemplateUsuarioJefeSeleccionado();
    componenteTemplateModalNormal();
    ?>

</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['formulario'])) {

        if ($_POST['formulario'] === 'Cambiar') {
            $contraseña_actual = trim($_POST['contraseña_actual']);
            $contraseña_nueva = trim($_POST['contraseña_nueva']);

            $usuario->cambiarContraseña($conexion, $contraseña_actual, $contraseña_nueva, $id);
        } elseif ($_POST['formulario'] === 'Eliminar') {
            $id = $_POST['identificador'];

            $administrador->eliminarRegistro($conexion, $id);
        }
    }
    notificaciones($_SESSION["mensaje"]);
}

?>