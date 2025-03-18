<?php
include "../../utils/constantes.php";
include "../../conexion/conexion.php";
include "../../clases/usuario.php";
include "../../utils/functionGlobales.php";

$usuario = new usuario();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Sistema de Justificantes ITSH</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pagina de modificar del administrador">
    <link rel="shortcut icon" href="../../assets/extra/logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/Fonts/fonts.css">
    <link rel="stylesheet" href="../../assets/styles/plantilla.css">
    <link rel="stylesheet" href="../../assets/styles/notificacion.css">
    <link rel="stylesheet" href="../../assets/styles/Añadir.css">
    <link rel="stylesheet" href="../../assets/styles/Modificar.css">
    <link rel="stylesheet" href="../../assets/styles/tablaSolicitudes.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="../../assets/js/index.js" defer></script>
</head>

<body>

    <nav class="navegacion">

        <div class="gobierno">
            <img src="../../assets/iconos/ic_gobierno.webp" alt="icono del gobierno de Mexico">

            <div class="texto_gobierno">
                <h3>Gobierno de</h3>
                <h4>Mexico</h4>
            </div>
        </div>

        <ul class="menu">
            <li class="menu-item"><a href="JefeCarrera.php" class="link">Inicio</a></li>
            <li class="menu-item"><a href="Añadir.php" class="link">Añadir</a></li>
            <li class="menu-item"><a href="Modificar.php" class="link">Modificar</a></li>
            <li class="menu-item"><a href="JefeCarrera.php?Eliminar=true" class="link">Eliminar</a></li>
            <li class="menu-item"><a href="Solicitudes.php" class="link">Solicitudes</a></li>

            <li class="menu-item"><a href="../../conexion/cerrar_sesion.php" class="link"><img
                        src="../../assets/iconos/ic_cerrar_sesion.webp" alt="icono de cerrar sesion"></a></li>
        </ul>
    </nav>

    <main class="main">
        <div class="contenedor_logo">
            <img src="../../assets/extra/logo.svg" alt="logo del ITSH">
        </div>

        <div class="contenedor_main">
            <div class="contenedor">
                <img src="../../assets/extra/encabezado.webp" alt="los encabezados de la pagina" width="1000px"
                    height="164">
            </div>
            <div class="contenido_solicitudes">
                <a href="" class="btn_historial">
                    Justificantes
                </a>

                <table>
                    <tr>
                        <th>Solicitud</th>
                        <th>Matricula</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Grupo</th>
                        <th>Motivo</th>
                        <th>Fecha</th>
                        <th>Evidencia</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                    <?php
                    $sql = "SELECT * FROM solicitudes";
                    $resultado = mysqli_query($conexion, $sql);
                    while ($fila = mysqli_fetch_array($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $fila['solicitud'] . "</td>";
                        echo "<td>" . $fila['matricula'] . "</td>";
                        echo "<td>" . $fila['nombre'] . "</td>";
                        echo "<td>" . $fila['apellidos'] . "</td>";
                        echo "<td>" . $fila['grupo'] . "</td>";
                        echo "<td>" . $fila['motivo'] . "</td>";
                        echo "<td>" . $fila['fecha_ausencia'] . "</td>";
                        echo "<td>" .
                            "<a href='../Alumno/evidencias/" . $fila['evidencia'] . "' target='_blank' class='link_evidencia'>" .
                            $fila['evidencia'] .
                            "</a>" .
                            "</td>";
                        if ($fila['estado'] == 1) {
                            echo "<td class='aceptada'></td>";
                        } else if ($fila['estado'] == 2) {
                            echo "<td class='pendiente'></td>";
                        } else {
                            echo "<td class='rechazada'></td>";
                        }
                        echo "<td>";
                        echo "<div class='opciones'>";
                        echo "
                        <img src='../../assets/iconos/ic_correcto.webp' alt='icono de verificar'>
                        ";
                        echo "<img src='../../assets/iconos/ic_error.webp' alt='icono de verificar'>";
                        echo "
                        <button onclick='eliminarFila(this)'>
                        <img src='../../assets/iconos/ic_eliminar.webp' alt='icono de verificar'>
                        </button>"
                        ;


                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>

    </main>


    <footer class="footer">
        <div class="contenido_footer">
            <div class="siguenos">
                <p>Siguenos en</p>
                <div class="redes">
                    <a href="https://www.facebook.com/ITSHuatusco/?locale=es_LA" target="_blank"><img
                            src="../../assets/iconos/ic_facebook.webp" alt="icono de facebook"></a>
                    <a href="https://www.instagram.com/itshuatusco/?hl=es-la" target="_blank"><img
                            src="../../assets/iconos/ic_instagram.webp" alt="icono de instagram"></a>
                </div>
            </div>

            <div class="definicion">
                <span>¿Que esto?</span>
                <p>Un sistema de justificantes para el Instituto Tecnologico Superior de Huatusco</p>
            </div>

            <div class="terminos">
                <a href="../Terminos/Terminos y Condiciones.php">Terminos y Condiciones</a>
            </div>
        </div>

        <div class="footer_gobierno">
            <img src="../../assets/iconos/ic_gobierno.webp" alt="icono del gobierno de Mexico">

            <div class="texto_gobierno">
                <p>Gobierno de Mexico</p>

            </div>
        </div>

    </footer>
</body>

</html>


<script>
    async function eliminarSolicitud(id) {
        $.ajax({
            url: '../../query/eliminarSolicitud.php',
            method: 'POST',
            data: {
                id: id
            },
        });
    }

    function eliminarFila(objeto) {
        let fila = objeto.closest("tr");
        id = fila.querySelectorAll("td")[0].innerText
        fila.remove()
        eliminarSolicitud(id)
    }


</script>

<?php
notificaciones($_SESSION["mensaje"]);
