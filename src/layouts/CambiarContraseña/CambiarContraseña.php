<?php
include "../../utils/constantes.php";
include "../../utils/functionGlobales.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../Components/Usuario.php";

$usuario = new usuario();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Sistema de Justificantes ITSH</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pagina de cambiar contraseña del sistema">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="preload" href="/src/assets/Fonts/fonts/Poppins/Poppins-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/src/assets/Fonts/fonts/Manrope/Manrope-Regular.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="stylesheet" rel="preload" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" rel="preload" href="../../assets/styles/index.css">
    <link rel="stylesheet" rel="preload" href="../../assets/styles/notificacion.css">
    <script src="../../assets/js/index.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script type="text/javascript">
        emailjs.init('h-GFfAEPfkpyGuQjm')
    </script>
    <script src="../../assets/js/cambiarContraseña.js" defer></script>
    <script src="../../assets/js/mostrarTemplate.js" defer></script>
</head>

<body>

    <div class="logo">
        <img src="../../assets/extra/logo.svg" alt="Logo de la escuela ITSH">
    </div>

    <a href="../../../../index.php" class="regresar">
        <img class="boton_regresar" src="../../assets/iconos/ic_regresar.webp"
            alt="Icono para regresar a la ventana de login">
    </a>

    <form class="formulario" method="post" id="form">

        <h2 class="titulo">Recupera tu contraseña</h2>

        <label for="Matricula" class="contenedor_input">
            <input pattern="^ITSH_\d{4}$|^\d{3}[a-z]\d{4}$|^\d{3}[a-z]\d{3}$" class="input_login" type="text"
                name="Matricula" id="Matricula" placeholder=" ">
            <span class="nombre_input">Identificador</span>
        </label>

        <label for="user_email" class="contenedor_input">
            <input pattern="^(?:[a-z]+|[0-9]{3}[a-z][0-9]{3,4})@(alum\.)?huatusco\.tecnm\.mx$" class="input_login"
                type="email" name="user_email" id="user_email" placeholder=" ">
            <span class="nombre_input">Correo Institucional</span>
        </label>

        <input type="submit" value="Enviar" class="btn_login" id="button">
    </form>

    <?php
    componenteTemplateModalNormal();
    ?>

</body>

</html>