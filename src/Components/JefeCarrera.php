<?php

function componenteSinJustificantes()
{
    echo <<<HTML
        <p class='sin_justificantes'>No hay justificantes disponibles</p>
    HTML;
}

function componenteJustificanteJefe($fila, $tiempo_fecha)
{
    global $CAMPO_J_JUSTIFICANTE, $CAMPO_J_ID_JUSTIFICANTE, $CAMPO_J_MATRICULA, $CAMPO_J_NOMBRE;
    $fecha = "$tiempo_fecha[2] de " . Variables::MESES[$tiempo_fecha[1][1] - 1] . " $tiempo_fecha[0]";
    echo <<<HTML
    <a href='../Alumno/justificantes/{$fila[$CAMPO_J_JUSTIFICANTE]}' class='archivo' target='_blank'>
        <h2> Folio {$fila[$CAMPO_J_ID_JUSTIFICANTE]} </h2>
        <p> {$fila[$CAMPO_J_MATRICULA]} </p>
        <p> {$fila[$CAMPO_J_NOMBRE]} </p>
        <span>{$fecha}</span>
    </a>
    HTML;
}

function componenteCabeceraTablaSolicitudes()
{
    return <<<HTML
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
    HTML;
}

function componenteFilaSolicitud($fila, $id, $clase, $fecha)
{
    global $CAMPO_ID_SOLICITUD, $CAMPO_S_MATRICULA, $CAMPO_S_NOMBRE, $CAMPO_S_APELLIDOS, $CAMPO_S_GRUPO, $CAMPO_MOTIVO, $CAMPO_S_EVIDENCIA;
    return <<<HTML
    <tr>
    <td> {$fila[$CAMPO_ID_SOLICITUD]}</td>
    <td> {$fila[$CAMPO_S_MATRICULA]}</td>
    <td> {$fila[$CAMPO_S_NOMBRE]}</td>
    <td> {$fila[$CAMPO_S_APELLIDOS]}</td>
    <td> {$fila[$CAMPO_S_GRUPO]}</td>
    <td> {$fila[$CAMPO_MOTIVO]}</td>
    <td> {$fecha[2]}-{$fecha[1]}-{$fecha[0]}</td>
    <td>
        <a href='../Alumno/evidencias/{$fila[$CAMPO_S_EVIDENCIA]}' target='_blank' class='link_evidencia'>
            {$fila[$CAMPO_S_EVIDENCIA]}
        </a> 
    </td>
    <td class='{$clase}'></td>
    <td>
        <div class='opciones'>
            <button class='btn_opciones_solicitudes' data-id='$id' onclick='aceptarSolicitud(this)'>
                <img src='../../assets/iconos/ic_correcto.webp' alt='icono para aceptar la solicitud para el justificante'>
            </button>
            <button class='btn_opciones_solicitudes' onclick='rechazarSolicitud(this)'>
                <img src='../../assets/iconos/ic_error.webp' alt='icono para rechazar la solicitud para el justificante'>
            </button>
            <button class='btn_opciones_solicitudes' onclick='eliminarFila(this)'>
                <img src='../../assets/iconos/ic_eliminar.webp' alt='icono para eliminar la solicitud para el justificante'>
            </button>
        </div>
    </td>
    </tr>
    HTML;
}


function componenteDetailSolicitud($fila, $clase, $id)
{
    global $CAMPO_ID_SOLICITUD, $CAMPO_S_MATRICULA, $CAMPO_S_NOMBRE, $CAMPO_S_APELLIDOS, $CAMPO_S_GRUPO, $CAMPO_MOTIVO, $CAMPO_S_EVIDENCIA, $CAMPO_FECHA_AUSE;

    return <<<HTML
        <details class='detalles_solicitudes' 
        data-datos='{$fila[$CAMPO_ID_SOLICITUD]}, {$fila[$CAMPO_S_MATRICULA]}, {$fila[$CAMPO_S_NOMBRE]}, {$fila[$CAMPO_S_APELLIDOS]}, {$fila[$CAMPO_S_GRUPO]}, {$fila[$CAMPO_MOTIVO]}, {$fila['fecha_ausencia']}, {$clase}, {$fila[$CAMPO_S_EVIDENCIA]}'>
            <summary>
                <div class='detalles'>
                    <p>Solicitud: {$fila[$CAMPO_ID_SOLICITUD]}</p>
                </div>
            
                <div class='{$clase} estado'></div>
            </summary>
            <div class='contenido_solicitudes'>
                <div class='detalle'><strong>Matricula:</strong><p>{$fila[$CAMPO_S_MATRICULA]}</p></div>
                <div class='detalle'><strong>Nombre:</strong><p>{$fila[$CAMPO_S_NOMBRE]}</p></div>
                <div class='detalle'><strong>Apellidos:</strong><p>{$fila[$CAMPO_S_APELLIDOS]}</p></div>
                <div class='detalle'><strong>Grupo:</strong><p>{$fila[$CAMPO_S_GRUPO]}</p></div>
                <div class='detalle'><strong>Motivo:</strong><p>{$fila[$CAMPO_MOTIVO]}</p></div>
                <div class='detalle'><strong>Ausencia:</strong><p>{$fila[$CAMPO_FECHA_AUSE]}</p></div>
                <div class='detalle'><strong>Evidencia:</strong>
                    <a href='../Alumno/evidencias/{$fila[$CAMPO_S_EVIDENCIA]}' target='_blank'>
                        {$fila[$CAMPO_S_EVIDENCIA]}
                    </a>
                </div>
                <div class='opciones'>
                    <button class='btn_opciones_solicitudes' data-id='$id' onclick='aceptarSolicitud(this)'>
                        Aceptar
                    </button>
                    <button class='btn_opciones_solicitudes' onclick='rechazarSolicitud(this)'>
                        Rechazar
                    </button>
                    <button class='btn_opciones_solicitudes' onclick='eliminarFila(this)'>
                        Eliminar
                    </button>
                </div>
            </div>
        </details>
    HTML;
}

?>