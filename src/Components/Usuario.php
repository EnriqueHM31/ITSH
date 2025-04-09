<?php

function componenteDatosUsuarioInicioAdministrador($usuario, $correo)
{
    global $CAMPO_CLAVE_EMPLEADO_ADMIN, $CAMPO_NOMBRE, $CAMPO_APELLIDOS;
    echo
        <<<HTML
        <div class='contenedor-datos'>
            <p><strong>Clave:</strong> {$usuario[$CAMPO_CLAVE_EMPLEADO_ADMIN]}</p>
            <p><strong>Nombre:</strong> {$usuario[$CAMPO_NOMBRE]}</p>
            <p><strong>Apellidos:</strong> {$usuario[$CAMPO_APELLIDOS]}</p>
            <p><strong>Correo:</strong> {$correo}</p>
            <button id='btn_contraseña' class='btn_vino'>Cambiar contraseña</button>
        </div>
        HTML;
}

function componenteDatosUsuarioInicioJefeCarrera($usuario, $carrera, $correo)
{
    global $CAMPO_CLAVE_EMPLEADO_JEFE, $CAMPO_NOMBRE, $CAMPO_APELLIDOS;
    echo
        <<<HTML
        <div class='contenedor-datos'>
            <p><strong>Identificador:</strong> {$usuario[$CAMPO_CLAVE_EMPLEADO_JEFE]}</p>
            <p><strong>Nombre:</strong> {$usuario[$CAMPO_NOMBRE]}</p>
            <p><strong>Apellidos:</strong> {$usuario[$CAMPO_APELLIDOS]}</p>
            <p><strong>Carrera:</strong> {$carrera}</p>
            <p><strong>Correo:</strong> {$correo}</p>
            <button id='btn_contraseña' class='btn_vino'>Cambiar contraseña</button>
        </div>
        HTML;
}

function componenteDatosUsuarioInicioAlumno($usuario, $carrera, $correo)
{
    global $CAMPO_MATRICULA, $CAMPO_NOMBRE, $CAMPO_APELLIDOS, $CAMPO_GRUPO;
    echo
        <<<HTML
        <div class='contenedor-datos'>
            <p><strong>Identificador:</strong>$usuario[$CAMPO_MATRICULA]</p>
            <p><strong>Nombre:</strong>$usuario[$CAMPO_NOMBRE]</p>
            <p><strong>Apellidos:</strong>$usuario[$CAMPO_APELLIDOS]</p>
            <p><strong>Carrera:</strong>$carrera</p>
            <p><strong>Grupo:</strong>$usuario[$CAMPO_GRUPO]</p>
            <p><strong>Correo:</strong>$correo</p>
            <button id='btn_contraseña' class='btn_vino'>Cambiar contraseña</button>
        </div>
        HTML;
}

function componenteTemplateModalNormal()
{
    echo
        <<<HTML
        <template id="miTemplate">
            <div class="overlay" id="overlay">
                <div class="notificacion">
                    <img class="img_notificacion" src="" alt="icono de notificacion" id="imagen">
                    <div class="contenido_notificacion ">
                        <p id="mensaje"></p>
                    </div>
                    <button class="btn_mensaje" id="btn_mensaje" onclick="cerrarTemplate()">Cerrar</button>
                </div>
            </div>
        </template>
    HTML;
}

function componenteTemplateModalCargar()
{
    echo
        <<<HTML
        <template id="miTemplate_cargar">
            <div class="overlay" id="overlay">
                <div class="notificacion">
                    <img class="img_notificacion" src="" alt="icono de notificacion" id="imagen">
                    <div class="contenido_notificacion ">
                        <p id="mensaje"></p>
                    </div>
                    <button class="btn_mensaje" id="btn_mensaje" onclick="cerrarTemplate('cargar')">Cerrar</button>
                </div>
            </div>
        </template>
    HTML;
}