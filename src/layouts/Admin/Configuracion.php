<?php
session_start();
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/administrador.php";
include "../../utils/functionGlobales.php";
include "../../conexion/verificar acceso.php";
include "../../conexion/verificar_rol_admin.php";
include "../../Components/Admin.php";
include "../../Components/Usuario.php";
include "../../Components/Layout.php";


$administrador = new administrador();
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$correo = $_SESSION["correo"];

$carreras = obtenerAllCarreras($conexion);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Configuracion Administrador</title>
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
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/templates.css">
    <link rel="stylesheet" href="../../assets/styles/Configuracion.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../assets/js/index.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
    <script src="../../assets/js/configuracion.js" defer></script>

</head>

<body data-modo="Eliminar" data-carrera="">

    <?php
    componenteNavegacionLayout($rol);
    ?>


    <main class="main">
        <div class="contenedor_main">
            <img class="encabezado" src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina">

            <div class="contenido_configuracion">

                <div class="contenedor_configuracion">
                    <h2>Configuracion de la carreras</h2>
                    <div class="opciones-configuracion">
                        <button class="btn-configuracion" id="agregar_carrera"
                            data-accion="agregar_carrera">Agregar</button>
                        <button class="btn_listar_usuarios btn-configuracion">Listar Usuarios</button>
                    </div>
                    <div class="contenedor-carreras">
                        <?php
                        // Asegúrate de que la consulta esté siendo ejecutada y $carreras tenga resultados
                        while ($registro = mysqli_fetch_array($carreras)) {
                            componenteCarrerasConfiguracion($registro);
                        }
                        ?>
                    </div>
                </div>
            </div>
    </main>

    <?php
    componenteFooter();
    ?>


    <?php
    componenteTemplateAgregarCarrera();
    componenteTemplateConfigurarCarrera();
    componenteTemplateModalNormal();
    componenteTemplateModalCargar();
    ?>

</body>

</html>

<?php


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['formulario'])) {

        if ($_POST['formulario'] === 'Agregar Carrera') {
            $carreraNueva = trim($_POST['carrera_nueva']);
            $Numero_grupos = trim($_POST['grupos_nueva_carrera']);
            $id_carrera = trim($_POST['id_carrera_nueva']);
            $id_tipo_carrera = obtenerIDTipoCarrera($conexion, trim($_POST["tipo_carrera"]));
            $administrador->AgregarCarrera($conexion, $carreraNueva, $Numero_grupos, $id_carrera, $id_tipo_carrera);
        }
        if ($_POST['formulario'] === 'Modificar Carrera') {
            $carreraAntigua = trim($_POST['carrera_antigua']);
            $carreraNueva = trim($_POST['carrera_nueva']);
            $Numero_grupos = trim($_POST['grupos_nueva_carrera']);
            $id_carrera = trim($_POST['id_carrera_nueva']);
            $id_tipo_carrera_nueva = obtenerIDTipoCarrera($conexion, trim($_POST["tipo_carrera"]));

            $administrador->modificarCarrera($conexion, $carreraAntigua, $carreraNueva, $id_tipo_carrera_nueva, $Numero_grupos, $id_carrera);
        }
    }
    notificaciones($_SESSION["mensaje"]);
}