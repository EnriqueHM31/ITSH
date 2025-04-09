<?php

function componenteTemplateCambiarContraseña()
{
    echo
        <<<HTML
        <template id="plantilla_cambiar-contraseña">
            <div class="overlay_cambiar-contraseña overlay_ventana">
                <form class="formulario" method="post">
                    <h2 class="titulo">Cambiar Contraseña</h2>
                    <div class="inputs-cambio-contraseña">
                        <label for="contraseña_actual" class="contenedor_input">
                            <input class="input_login" type="text" name="contraseña_actual" id="contraseña_actual"
                                placeholder=" " autocomplete="current-password">
                            <span class="nombre_input">Contraseña actual</span>

                        </label>
                        <label for="contraseña_nueva" class="contenedor_input">
                            <input class="input_login" type="password" name="contraseña_nueva" id="contraseña_nueva"
                                placeholder=" " autocomplete="new-password">
                            <span class="nombre_input">Contraseña nueva</span>
                        </label>
                    </div>
                    <input type="submit" name="formulario" class="btn-submit btn_login" value="Cambiar">
                    <img class="close" id="cerrar" src="../../assets/iconos/ic_close.webp"
                        alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">
                </form>
            </div>
        </template>
    HTML;
}

function componenteNavegacionLayout($rol)
{
    global $ADMIN, $JEFE, $ESTUDIANTE;
    $ADMIN_LINKS = [["Admin.php", "Inicio"], ["Añadir.php", "Añadir"], ["Modificar.php", "Modificar"], ["Admin.php?EliminarPersonal=true", "Eliminar"], ["Configuracion.php", "Configuracion"]];

    $JEFE_LINKS = [["JefeCarrera.php", "Inicio"], ["Añadir.php", "Añadir"], ["Modificar.php", "Modificar"], ["JefeCarrera.php?EliminarEstudiante=true", "Eliminar"], ["Solicitudes.php", "Solicitudes"]];

    $ESTUDIANTE_LINKS = [["alumno.php", "Inicio"], ["CrearSolicitud.php", "Crear Solicitud"], ["HistorialAlumno.php", "Historial"]];

    $LINKS = [];
    if ($rol === $ADMIN) {
        $LINKS = $ADMIN_LINKS;
    } else if ($rol === $JEFE) {
        $LINKS = $JEFE_LINKS;
    } else if ($rol === $ESTUDIANTE) {
        $LINKS = $ESTUDIANTE_LINKS;
    }
    echo
        <<<HTML
        <nav class="navegacion">
            <div class="gobierno">
                <img src="../../assets/iconos/ic_gobierno.webp" alt="icono del gobierno de Mexico">
                <div class="texto_gobierno">
                    <h3>Gobierno de</h3>
                    <h4>Mexico</h4>
                </div>
            </div>
            <ul class="menu">
    HTML;

    $i = 0;
    while ($i < count($LINKS)) {
        echo <<<HTML
                <li class="menu-item"><a href='{$LINKS[$i][0]}' class="link">{$LINKS[$i][1]}</a></li>
            HTML;
        $i++;
    }
    echo <<<HTML

                <li class="menu-item"><a href="../../conexion/cerrar_sesion.php" class="link"><img
                            src="../../assets/iconos/ic_cerrar_sesion.webp" alt="icono de cerrar sesion"></a></li>
                <li class="menu-item close_contenedor"><img class="close_menu" src="../../assets/iconos/ic_close.webp"
                        alt="Imagen para cerrar el menu movil"></li>
            </ul>
            <img src="../../assets/iconos/ic_menu_movil.webp" alt="icono para el menu en movil" class="icono_menu">
        </nav>
    HTML;
}


function componenteFooter()
{
    echo
        <<<HTML
            <footer class="footer">
                <div class="contenido_footer">
                    <div class="siguenos">
                        <p>Siguenos en</p>
                        <div class="redes">
                            <a href="https://www.facebook.com/ITSHuatusco/?locale=es_LA" target="_blank">
                                <img src="../../assets/iconos/ic_facebook.webp" alt="icono de facebook">
                            </a>
                            <a href="https://www.instagram.com/itshuatusco/?hl=es-la" target="_blank">
                                <img src="../../assets/iconos/ic_instagram.webp" alt="icono de facebook">
                            </a>
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
        HTML;
}