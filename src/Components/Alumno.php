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
        <div class='archivo' data-id='{$fila[$CAMPO_ID_SOLICITUD]}'data-id_justificante = {$i} >
            <h2> Solicitud {$i} </h2>
            <p> {$estado} </p>
            <p> {$fila[$CAMPO_MOTIVO]} </p>
            <span> {$tiempo_fecha}</span>
        </div>
    HTML;
}

function componenteRangoFechas()
{
    echo <<<HTML
    <template id="modal-template">
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
        <h2>Asignar Rango de Fechas</h2>
        <input type="date" id="fechaInicio" />
        <input type="date" id="fechaFin" />
        <div class="actions">
            <button class="cancel-btn" onclick="closeModal()">Cancelar</button>
            <button class="save-btn" onclick="guardarFechas()">Guardar</button>
        </div>
        </div>
    </div>
    </template>
    HTML;
}


