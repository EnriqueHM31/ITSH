<?php

session_start();
include "src/utils/constantes.php";
include "src/conexion/conexion.php";
include "src/utils/functionGlobales.php";
include "src/clases/usuario.php";
$usuario = new usuario();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Sistema de Justificantes ITSH</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pagina de login del sistema">
    <link rel="shortcut icon" href="src/assets/extra/logo.svg" type="image/x-icon">
    <link rel="preload" href="/src/assets/Fonts/fonts/Poppins/Poppins-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/src/assets/Fonts/fonts/Manrope/Manrope-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="stylesheet" href="src/assets/Fonts/fonts.css">
    <link rel="stylesheet" href="src/assets/styles/index.css">
    <link rel="stylesheet" href="src/assets/styles/notificacion.css">
    <script src="src/assets/js/index.js" defer></script>
    <script src="src/assets/js/mostrarTemplate.js" defer></script>
</head>

<body>

    <div class="logo">
        <img src="src/assets/extra/logo.svg" alt="Logo de la escuela ITSH">
    </div>

    <form autocomplete="off" action="/submit" class="formulario" method="post" autocomplete="off">

        <h2 class="titulo">Bienvenido</h2>

        <label for="id" class="contenedor_input">
            <input autocomplete="username" class="input_login" type="text" name="identificador" id="id" placeholder=" ">
            <span class="nombre_input">Identificador</span>
        </label>

        <label for="contraseña" class="contenedor_input">
            <input autocomplete="new-password" class="input_login contraseña" type="password" name="contraseña"
                id="contraseña" placeholder=" ">
            <span class=" nombre_input">Contraseña</span>
            <img class="img-ver" src="./src/assets/extra/invisible.webp" alt="Icono para hacer invisible la contraseña">
        </label>

        <a href="src/layouts/CambiarContraseña/CambiarContraseña.php" class="enlaces">Has olvidado la contraseña</a>


        <input type="submit" value="Ingresar" class="btn_login">
    </form>

</body>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = trim($_POST["identificador"]);
    $contraseña = trim($_POST["contraseña"]);

    $usuario->Verificacion($conexion, $id, $contraseña);

    notificaciones($_SESSION["mensaje"]);
}

?>