<?php
include "../utils/constantes.php";
include "../conexion/conexion.php";
include "../conexion/verificar acceso.php";

$query = isset($_GET['q']) ? $_GET['q'] : '';


    if (strlen($query) > 0) {
        $sql = "SELECT * FROM " . Variables::TABLA_BD_JUSTIFICANTES . " 
        WHERE " . Variables::CAMPO_J_MATRICULA . " LIKE ? OR " . Variables::CAMPO_J_NOMBRE . " LIKE ? ";
        ;
        $stmt = $conexion->prepare($sql);
        $param = "%$query%";
        $stmt->bind_param('ss', $param, $param);

    } else {
        $sql = "SELECT * FROM " . Variables::TABLA_BD_JUSTIFICANTES;
        $stmt = $conexion->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $salida = ""; // Inicializar variable para almacenar el HTML

    if ($result->num_rows == 0) {
        $salida = "<p class='sin_justificantes'>No se encontraron justificantes</p>";
        echo $salida;
        return;
    }

    while ($fila = $result->fetch_assoc()) {
       $tiempo = explode(" ", $fila['fecha_creacion']);

        $tiempo_fecha = explode("-", $tiempo[0]);
        echo "
            <a href='../Alumno/justificantes/{$fila['justificante_pdf']}' class='archivo' target='_blank'>
                <h2> Folio {$fila['id']} </h2>
                <p> {$fila['matricula_alumno']} </p>
                <p> {$fila['nombre_alumno']} </p>
                <span>Hora: {$tiempo[1]} </span>
                <span>Fecha: {$tiempo_fecha[2]} de " . Variables::MESES[$tiempo_fecha[1][1] - 1] . " de " . $tiempo_fecha[0] . " </span>
            </a>
        ";
    }
    echo $salida;

    $stmt->close();



