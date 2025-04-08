<?php


function componenteSinSolicitudes()
{
    echo <<<HTML
        <p class='sin_justificantes'>No hay solicitudes disponibles</p>
    HTML;
}

function componenteJustificanteHistorial($fila, $i, $tiempo_fecha)
{
    global $CAMPO_ID_SOLICITUD, $CAMPO_MATRICULA, $CAMPO_ESTADO;
    echo <<<HTML
        <div class='archivo' data-id='{$fila[$CAMPO_ID_SOLICITUD]}'>
            <h2> Solicitud {$i} </h2>
            <p> {$fila[$CAMPO_MATRICULA]} </p>
            <p> {$fila[$CAMPO_ESTADO]} </p>
            <span> {$tiempo_fecha[2]} / {$tiempo_fecha[1]} / {$tiempo_fecha[0]} </span>
        </div>
    HTML;
}
?>