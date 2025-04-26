<?php

function componenteSinSolicitudes()
{
    echo <<<HTML
        <p class='sin_justificantes'>No hay solicitudes disponibles</p>
    HTML;
}

function componenteJustificanteHistorial($conexion, $fila, $i, $tiempo_fecha)
{
    global $CAMPO_ID_SOLICITUD, $CAMPO_ID_ESTADO, $CAMPO_ID_ESTADO, $CAMPO_MOTIVO;

    $estado = ObtenerNombreEstado($conexion, $fila[$CAMPO_ID_ESTADO]);

    echo <<<HTML
        <div class='archivo' data-id='{$fila[$CAMPO_ID_SOLICITUD]}'data-id_justificante = {$i}>
            <h2> Solicitud {$i} </h2>
            <p> {$estado} </p>
            <p> {$fila[$CAMPO_MOTIVO]} </p>
            <span> {$tiempo_fecha[2]} / {$tiempo_fecha[1]} / {$tiempo_fecha[0]} </span>
        </div>
    HTML;
}
?>