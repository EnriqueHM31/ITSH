<?php

include "../conexion/conexion.php";
include "../utils/constantes.php";
include "../utils/functionGlobales.php";
require_once "./dompdf/autoload.inc.php";

function obtenerIMGLogos()
{
    // LOGOS DE LA ESCUELA
    $ruta_logos = $_SERVER['DOCUMENT_ROOT'] . "/src/assets/justificantes/logos.jpg";
    // Verificar si existe el logo
    if (!file_exists($ruta_logos)) {
        throw new Exception("Archivo de logos no encontrado: $ruta_logos");
    }
    $imagen_base64 = base64_encode(file_get_contents($ruta_logos));
    return 'data:image/jpeg;base64,' . $imagen_base64;
}

use Dompdf\Dompdf;
use Dompdf\Options;

$ADMINISTRADOR = "Administrador";
$JEFE_CARRERA = "Jefe de Carrera";
$ESTUDIANTE = "Estudiante";


// Consulta para obtener todos los usuarios de las tablas
$query = "
    SELECT 
    u.$CAMPO_ID_USUARIO, 
    u.$CAMPO_CORREO, 
    r.$CAMPO_ROL,  
    e.$CAMPO_NOMBRE AS nombre_estudiante, 
    e.$CAMPO_APELLIDOS AS apellidos_estudiante, 
    j.$CAMPO_NOMBRE AS nombre_jefe, 
    j.$CAMPO_APELLIDOS AS apellidos_jefe, 
    a.$CAMPO_NOMBRE AS nombre_administrador, 
    a.$CAMPO_APELLIDOS AS apellidos_administrador,
    COALESCE(c.$CAMPO_CARRERA, 'No aplica') AS carrera
    FROM $TABLA_USUARIO u
    LEFT JOIN $TABLA_ESTUDIANTE e ON u.$CAMPO_ID_USUARIO = e.$CAMPO_MATRICULA
    LEFT JOIN $TABLA_JEFE j ON u.$CAMPO_ID_USUARIO  = j.$CAMPO_CLAVE_EMPLEADO_JEFE
    LEFT JOIN $TABLA_ADMIN a ON u.$CAMPO_ID_USUARIO  = a.$CAMPO_CLAVE_EMPLEADO_ADMIN
    LEFT JOIN $TABLA_ROL r ON u.$CAMPO_ID_ROL = r.$CAMPO_ID_ROL
    LEFT JOIN $TABLA_CARRERAS c ON c.$CAMPO_ID_CARRERA = COALESCE(e.$CAMPO_ID_CARRERA, j.$CAMPO_ID_CARRERA)
    ORDER BY 
    CASE 
        WHEN r.$CAMPO_ROL = '$ADMINISTRADOR' THEN 1
        WHEN r.$CAMPO_ROL = '$JEFE_CARRERA' THEN 2
        WHEN r.$CAMPO_ROL = '$ESTUDIANTE' THEN 3
        ELSE 4 
    END;
";

$query = "
    SELECT $CAMPO_ID_USUARIO FROM $TABLA_USUARIO
";
$stmt = $conexion->prepare($query);
$stmt->execute();
$resultado = $stmt->get_result();


$src = obtenerIMGLogos();

// Verificar si se obtuvieron resultados
if ($resultado->num_rows > 0) {
    // HTML para la tabla
    $html = '
    <html>
    <head>
        <style>
            *{
                font-family: Arial, Helvetica, sans-serif;
            }  
            h2{
                text-align:center;
                font-size: 25px;
                display:inline-block;
                margin-left: 40px;
                margin-top: 40px;
                padding-top: 30px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }

            th{
                background-color: rgb(97, 18, 50);
                color: #fff;
            }
            img {
                width: 40%;
                margin-top:20px;
            }
        </style>
    </head>
    <body>
        <img src="' . $src . '">
        <h2>Usuarios en el Sistema de Justificantes</h2>
        <table>
            <thead>
                <tr>
                    <th>id_usuario</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Carrera</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>';

    ponerDatosTabla($resultado);

    $html .= '
            </tbody>
        </table>
    </body>
    </html>';

    // Inicializar DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

    // (Opcional) Establecer el tamaño del papel y la orientación
    $dompdf->setPaper('A4', 'landscape'); // O 'portrait' si prefieres vertical

    // Renderizar el PDF
    $dompdf->render();

    // Enviar el PDF generado al navegador sin forzar la descarga
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=usuarios_en_sistema.pdf");
    echo $dompdf->output(); // Genera y manda el PDF al navegador

} else {
    echo "No se encontraron usuarios en el sistema.";
}

// Cerrar la conexión
$conexion->close();



function ponerDatosTabla($resultado)
{
    global $ADMINISTRADOR, $JEFE_CARRERA, $ESTUDIANTE, $CAMPO_ID_ROL, $html, $CAMPO_ID_USUARIO, $CAMPO_CORREO;
    while ($row = $resultado->fetch_assoc()) {
        // Asegurar que solo se muestre un rol por usuario
        $rol = $row[$CAMPO_ID_ROL]; // El nombre del rol viene de la tabla rol

        // Variables para almacenar datos dinámicos
        $nombre = '';
        $apellidos = '';
        $carrera = 'No aplica'; // Valor predeterminado para administradores

        // Verificar el rol del usuario y asignar los valores correspondientes
        if ($rol == $ESTUDIANTE) {
            $nombre = $row['nombre_estudiante'];
            $apellidos = $row['apellidos_estudiante'];
            $carrera = $row['carrera']; // Asigna la carrera del estudiante
        } elseif ($rol == $JEFE_CARRERA) {
            $nombre = $row['nombre_jefe'];
            $apellidos = $row['apellidos_jefe'];
            $carrera = $row['carrera']; // Asigna la carrera del jefe
        } elseif ($rol == $ADMINISTRADOR) {
            $nombre = $row['nombre_administrador'];
            $apellidos = $row['apellidos_administrador'];
            $carrera = 'No aplica'; // Los administradores no tienen carrera
        }

        $nombre_completo = "$nombre $apellidos";

        // Construcción del HTML con la columna de carrera
        $html .= '
            <tr>
                <td>' . $row[$CAMPO_ID_USUARIO] . '</td>
                <td>' . $nombre_completo . '</td>
                <td>' . $rol . '</td>
                <td>' . $carrera . '</td> 
                <td>' . $row[$CAMPO_CORREO] . '</td>
            </tr>';
    }
}