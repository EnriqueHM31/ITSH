<?php
header('Content-Type: application/json');

include "./constantes.php";
include "./functionGlobales.php";
include "../conexion/conexion.php";
include "../clases/usuario.php";

require "./PHPMailer/PHPMailer.php";
require "./PHPMailer/SMTP.php";
require "./PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$usuario = new usuario();

$host = 'smtp.gmail.com';
$username = 'luisenriquehernandezmarin0@gmail.com';
$password = 'ykqtwzbimafxrkow';
$puerto = 587;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['correo']) && isset($_POST["id_usuario"]) && isset($_POST['contraseñaNueva'])) {
    mysqli_begin_transaction($conexion);

    $destinatario = $_POST['correo'];
    $id_usuario = $_POST['id_usuario'];
    $contraseñaNueva = $_POST['contraseñaNueva'];

    $existeUsuario = verificarIdentidadCorreoIdentificador($id_usuario, $destinatario, $conexion);
    if ($existeUsuario->num_rows == 0) {
        echo json_encode(["success" => false, "message" => "Los datos que ingresaste son erróneos."]);
        exit;
    }

    $usuario = $existeUsuario->fetch_assoc();
    $nombreUsuario = $usuario[$CAMPO_NOMBRE];
    $apellidosUsuario = $usuario[$CAMPO_APELLIDOS];


    $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f6f8;
                        margin: 0;
                        padding: 0;
                        color: #333;
                    }
                    .container {
                        max-width: 600px;
                        margin: 40px auto;
                        background-color: #ffffff;
                        border-radius: 10px;
                        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                        padding: 30px;
                        text-align: center;
                    }
                    .logo {
                        width: 100px;
                        margin-bottom: 20px;
                    }
                    .title {
                        font-size: 28px;
                        color: #005a9c;
                        margin-bottom: 20px;
                    }
                    .message {
                        font-size: 18px;
                        margin-bottom: 30px;
                    }
                    .btn {
                        display: inline-block;
                        background-color: #005a9c;
                        color: #fff;
                        padding: 12px 25px;
                        border-radius: 5px;
                        text-decoration: none;
                        font-weight: bold;
                    }
                    .footer {
                        font-size: 16px;
                        color: #888;
                        margin-top: 40px;
                    }

                    .contraseña{
                        font-size: 22px;
                        background-color:   #005a9c;
                        text-color: white;
                        padding: 10px 20px;
                        border-radius: 20px
                    }

                    .texto-mensaje{
                        font-size: 20px;
                        margin-bottom: 20px
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <img src="https://itshuatusco.edu.mx/inicio/ARCHIVOS/2020/SERVICIOS%20_ESCOLARES/Logo%20ITSH.png" alt="Logo ITSH" class="logo">
                    <div class="title">Recuperación de contraseña</div>
                    <div class="message">
                        <p class="texto-mensaje">Has solicitado recuperar tu contraseña <br></p>
                        <p class="texto-mensaje"><b>' . $id_usuario . '<b><br></p>
                        <p class="texto-mensaje"><b>' . $nombreUsuario . ' ' . $apellidosUsuario . '<b><br></p>
                    </div>
                    <div class="footer">
                        Tu contraseña es la siguiente:<br>
                        <p class="contraseña">' . $contraseñaNueva . '</p>
                    </div>
                </div>
            </body>
            </html>';

    if (cambiarContraseñaEnBD($conexion, $id_usuario, $contraseñaNueva)) {

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $puerto;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($username, 'Institución ITSH');
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña - ITSH';
            $mail->Body = $html;
            $mail->AltBody = 'Has solicitado recuperar tu contraseña. Ve a este enlace para continuar: SistemaJITSH.com';

            $mail->send();


            mysqli_commit($conexion);

            echo json_encode(["success" => true, "message" => "Correo enviado con éxito."]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Error al enviar el correo: " . $mail->ErrorInfo]);
        }
        exit;
    } else {
        echo json_encode(["success" => false, "message" => "No se proporcionaron los datos requeridos."]);
        exit;
    }

}


function verificarIdentidadCorreoIdentificador($id_usuario, $correoDB, $conexion)
{
    global $TABLA_USUARIO, $CAMPO_CORREO, $CAMPO_ID_USUARIO;
    $sql = "SELECT * FROM $TABLA_USUARIO WHERE $CAMPO_CORREO = ? AND $CAMPO_ID_USUARIO = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ss', $correoDB, $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    return $resultado;
}

function cambiarContraseñaEnBD($conexion, $id_usuario, $nuevaContraseña)
{
    global $TABLA_USUARIO, $CAMPO_CONTRASEÑA, $CAMPO_ID_USUARIO;
    $sql = "UPDATE $TABLA_USUARIO SET $CAMPO_CONTRASEÑA = ? WHERE $CAMPO_ID_USUARIO= ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ss', $nuevaContraseña, $id_usuario);

    return $stmt->execute();

}