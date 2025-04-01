<?php

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

include "../conexion/conexion.php";
require_once "./dompdf/autoload.inc.php";

use Dompdf\Dompdf;
use Dompdf\Options;


// Consulta para obtener todos los usuarios de las tablas
$query = "
    SELECT 
    u.id_usuario, 
    u.contraseña, 
    u.correo, 
    r.rol,  
    e.nombre AS nombre_estudiante, 
    e.apellidos AS apellidos_estudiante, 
    j.nombre AS nombre_jefe, 
    j.apellidos AS apellidos_jefe, 
    a.nombre AS nombre_administrador, 
    a.apellidos AS apellidos_administrador
FROM usuario u
LEFT JOIN estudiante e ON u.id_usuario = e.matricula
LEFT JOIN jefe j ON u.id_usuario = j.clave_empleado
LEFT JOIN administrador a ON u.id_usuario = a.clave_empleado
LEFT JOIN rol r ON u.id_rol = r.id_rol
ORDER BY 
    CASE 
        WHEN r.rol = 'Administrador' THEN 1
        WHEN r.rol = 'Jefe de Carrera' THEN 2
        WHEN r.rol = 'Estudiante' THEN 3
        ELSE 4 
    END;
";

$resultado = $conexion->query($query);

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
                    <th>Apellidos</th>
                    <th>Rol</th>
                    <th>Correo</th>
                    <th>Contraseña</th>

                </tr>
            </thead>
            <tbody>';

            while ($row = $resultado->fetch_assoc()) {
                // Aquí aseguramos que solo se muestre un rol por usuario
                $rol = $row['rol']; // El nombre del rol viene de la tabla rol
            
                // Verificamos qué rol tiene el usuario
                $nombre = '';
                $apellidos = '';
            
                if ($rol == 'Estudiante') {
                    $nombre = $row['nombre_estudiante'];
                    $apellidos = $row['apellidos_estudiante'];
                } elseif ($rol == 'Jefe de Carrera') {
                    $nombre = $row['nombre_jefe'];
                    $apellidos = $row['apellidos_jefe'];
                } elseif ($rol == 'Administrador') {
                    $nombre = $row['nombre_administrador'];
                    $apellidos = $row['apellidos_administrador'];
                }
            
                // Agregar la fila con los datos correctos
                $html .= '
                    <tr>
                        <td>' . $row['id_usuario'] . '</td>
                        <td>' . $nombre . '</td>
                        <td>' . $apellidos . '</td>
                        <td>' . $rol . '</td>
                        <td>' . $row['correo'] . '</td>
                        <td>' . $row['contraseña'] . '</td>
                    </tr>';
            }
            

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
?>