<?php

function componenteSinJustificantes()
{
    echo <<<HTML
        <p class='sin_justificantes'>No hay justificantes disponibles</p>
    HTML;
}

function componenteJustificanteJefe($conexion, $index, $fila, $tiempo_fecha)
{
    global $CAMPO_NOMBRE, $TABLA_USUARIO, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $MESES, $CAMPO_ID_CARRERA;

    $dataEstudiante = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $fila["id_estudiante"]);
    $mes_nombre = $MESES[intval($tiempo_fecha[1]) - 1];

    $dataTablaEstudiante = ObtenerDatosDeUnaTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $fila["id_estudiante"]);

    $carrera = ObtenerNombreCarrera($conexion, $dataTablaEstudiante[$CAMPO_ID_CARRERA]);
    $carrera = str_replace(" ", "", $carrera);

    $fecha = "$tiempo_fecha[2] de $mes_nombre $tiempo_fecha[0]";
    echo <<<HTML
    <a href='../Alumno/justificantes/{$carrera}/{$fila["nombre_justificante"]}' class='archivo' target='_blank'>
        <h2> Folio $index </h2>
        <p> {$fila["id_estudiante"]} </p>
        <p> {$dataEstudiante[$CAMPO_NOMBRE]} </p>
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

function componenteFilaSolicitud($conexion, $indexFila, $fila, $id, $clase, $fecha)
{
    global $TABLA_USUARIO, $TABLA_ESTUDIANTE, $CAMPO_ID_SOLICITUD, $CAMPO_ID_USUARIO, $CAMPO_NOMBRE, $CAMPO_APELLIDOS, $CAMPO_GRUPO, $CAMPO_ID_ESTADO, $CAMPO_EVIDENCIA, $CAMPO_MOTIVO, $CAMPO_ID_CARRERA;

    $sql = "SELECT u.*, e.* 
        FROM $TABLA_USUARIO u 
        JOIN $TABLA_ESTUDIANTE e 
        ON u.$CAMPO_ID_USUARIO = e.$CAMPO_ID_USUARIO 
        WHERE u.$CAMPO_ID_USUARIO = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $fila["id_estudiante"]);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $html = "";
    while ($row = $resultado->fetch_assoc()) {

        $carrera = ObtenerNombreCarrera($conexion, $row[$CAMPO_ID_CARRERA]);
        $carrera = str_replace(" ", "", $carrera);
        $html .= <<<HTML
        <tr>
            <td data-id={$fila[$CAMPO_ID_SOLICITUD]}> $indexFila </td>
            <td> {$row[$CAMPO_ID_USUARIO]}</td>
            <td> {$row[$CAMPO_NOMBRE]}</td>
            <td> {$row[$CAMPO_APELLIDOS]}</td>
            <td> {$row[$CAMPO_GRUPO]}</td>
            <td> {$fila[$CAMPO_MOTIVO]}</td>
            <td> {$fecha[2]}-{$fecha[1]}-{$fecha[0]}</td>
            <td>
                <a href="../Alumno/evidencias/{$carrera}/{$fila[$CAMPO_EVIDENCIA]}" target='_blank' class='link_evidencia'>
                    {$fila[$CAMPO_EVIDENCIA]}
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

    return $html;
}


function componenteDetailSolicitud($conexion, $fila, $clase, $id)
{
    global $TABLA_USUARIO, $TABLA_ESTUDIANTE, $CAMPO_ID_SOLICITUD, $CAMPO_ID_USUARIO, $CAMPO_NOMBRE, $CAMPO_APELLIDOS, $CAMPO_GRUPO, $CAMPO_ID_ESTADO, $CAMPO_EVIDENCIA, $CAMPO_FECHA_AUSE, $CAMPO_MOTIVO, $CAMPO_ID_CARRERA;

    $sql = "SELECT u.*, e.* 
    FROM $TABLA_USUARIO u 
    JOIN $TABLA_ESTUDIANTE e 
    ON u.$CAMPO_ID_USUARIO = e.$CAMPO_ID_USUARIO 
    WHERE u.$CAMPO_ID_USUARIO = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $fila["id_estudiante"]);
    $stmt->execute();
    $resultado = $stmt->get_result();


    while ($row = $resultado->fetch_assoc()) {

        $nombre_estado = ObtenerNombreEstado($conexion, $fila[$CAMPO_ID_ESTADO]);
        $carrera = ObtenerNombreCarrera($conexion, $row[$CAMPO_ID_CARRERA]);
        $carrera = str_replace(" ", "", $carrera);

        return <<<HTML
    <details class='detalles_solicitudes' 
        data-datos='{$fila[$CAMPO_ID_SOLICITUD]}, {$row[$CAMPO_ID_USUARIO]}, {$row[$CAMPO_NOMBRE]}, {$row[$CAMPO_APELLIDOS]}, {$row[$CAMPO_GRUPO]}, {$nombre_estado}, {$fila[$CAMPO_FECHA_AUSE]}, {$clase}, {$fila[$CAMPO_EVIDENCIA]}'>
        <summary>
            <div class='detalles'>
                <p>Solicitud: {$fila[$CAMPO_ID_SOLICITUD]}</p>
            </div>
            <div class='{$clase} estado'></div>
        </summary>
        <div class='contenido_solicitudes'>
            <div class='detalle'><strong>Matrícula:</strong><p>{$row[$CAMPO_ID_USUARIO]}</p></div>
            <div class='detalle'><strong>Nombre:</strong><p>{$row[$CAMPO_NOMBRE]}</p></div>
            <div class='detalle'><strong>Apellidos:</strong><p>{$row[$CAMPO_APELLIDOS]}</p></div>
            <div class='detalle'><strong>Grupo:</strong><p>{$row[$CAMPO_GRUPO]}</p></div>
            <div class='detalle'><strong>Motivo:</strong><p>{$fila[$CAMPO_MOTIVO]}</p></div>
            <div class='detalle'><strong>Ausencia:</strong><p>{$fila[$CAMPO_FECHA_AUSE]}</p></div>
            <div class='detalle'><strong>Evidencia:</strong>
                <a href='../Alumno/evidencias/{$carrera}/{$fila[$CAMPO_EVIDENCIA]}' target='_blank'>
                    {$fila[$CAMPO_EVIDENCIA]}
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

}

function componenteTemplateUsuarioEstudianteEliminar()
{
    echo <<<HTML
        <template id="plantilla_eliminar-estudiante">
            <div class=" overlay overlay_eliminar">
                <form class="formulario form_eliminar" method="post">
                    <h2 class="titulo titulo_eliminar">Eliminar Registro</h2>
                    <div class="buscador-usuarios">
                        <label for="buscar" class="contenedor_input">
                            <input class="input_buscar" type="search" name="buscar" id="buscar" placeholder="Buscar">
                        </label>
                        <div id="resultados">
                        </div>
                    </div>

                    <img class="close" src="../../assets/iconos/ic_close.webp"
                        alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">

                    <button type="button" class="btn_eliminar" id="eliminar_registro">Eliminar</button>
                </form>

            </div>
        </template>
    HTML;
}

function componenteTemplateUsuarioEstudianteSeleccionado()
{
    echo <<<HTML
        <template id="plantilla_usuario-seleccionado-estudiante">
            <div class=" overlay overlay_eliminar overlay_ventana">
                <form id="formulario_eliminar" class="formulario form_eliminar form_verificar_eliminar" method="post">
    
                    <div class="informacion-usuario-eliminar">
                        <h2 class="titulo_eliminar-modal">Estas seguro de eliminar a: </h2>
                        <p class="info_usuario_eliminar">Clave: <span id="matricula-info"></span></p>
                        <p class="info_usuario_eliminar">Nombre: <span id="nombre-info"></span></p>
                        <p class="info_usuario_eliminar">Apellidos: <span id="apellidos-info"></span></p>
                        <p class="info_usuario_eliminar">Grupo: <span id="grupo-info"></span></p>
                        <p class="info_usuario_eliminar">Carrera: <span id="carrera-info"></span></p>
                        <p class="info_usuario_eliminar">Modalidad: <span id="id_modalidad-info"></span></p>
                        <p class="info_usuario_eliminar">Correo: <span id="correo-info"></span></p>
                        <input type="hidden" name="identificador" value="" id="identificador">
                    </div>
    
                    <img class="close close_eliminar" src="../../assets/iconos/ic_close.webp"
                        alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">
    
                    <input type="submit" name="formulario" value="Eliminar" class="btn_eliminar btn_ventana-eliminar">
                </form>
    
            </div>
        </template>
    HTML;
}

function componenteModalSeguridadFolio($id_jefe)
{
    echo
        <<<HTML
        <template id="modal_seguridad">
            <div class=" overlay overlay_eliminar overlay_ventana" id="overlay">

                <div class="modal">
                    <h2>¿Esta seguro de reiniciar el Folio</h2>
                    <p>Se borraran todos los justificantes creados hasta el momento</p>

                    <div class="opciones_decision">
                        <button class="btn_opcion" onclick="reiniciarFolio('{$id_jefe}')">Si</button>
                        <button class="btn_opcion" onclick="cerrarTemplate()">No</button>
                    </div>
                </div>

            </div>
        </template>
    HTML;
}
?>