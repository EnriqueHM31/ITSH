<?php

function componenteCarrerasConfiguracion($registro)
{
    global $CAMPO_CARRERA;
    echo <<<HTML
        <article class='contenido_carreras'>
            <diV class='carrera'> 
                <p>{$registro[$CAMPO_CARRERA]}</p>
            </diV>
            <div class='menu_carreras'>
                <button data-id='{$registro[$CAMPO_CARRERA]}' class='eliminar_carrera boton_eliminar'>
                    <img src='../../assets/iconos/ic_eliminar.webp'>
                </button>
                <button data-accion='configurar_carrera' id='agregar_carrera' data-id='{$registro[$CAMPO_CARRERA]}' class='eliminar_carrera'>
                    <img src='../../assets/iconos/ic_configuracion.webp'>
                </button>
            </div>
        </article>
    HTML;
}

function componenteTemplateEliminar()
{
    echo
        <<<HTML
        <template id="plantilla_eliminar-personal">
            <div class=" overlay overlay_eliminar">
                <form class="formulario form_eliminar" method="post">
                    <h2 class="titulo titulo_eliminar">Eliminar Registro</h2>
                    <div class="buscador-usuarios">
                        <label for="buscar" class="contenedor_input">
                            <input class="input_buscar" type="search" name="buscar" id="buscar" placeholder="Buscar">
                        </label>
                        <div id="resultados"></div>
                    </div>
                    <img class="close" src="../../assets/iconos/ic_close.webp"
                        alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">

                    <button type="button" class="btn_eliminar" id="eliminar_registro">Eliminar</button>
                </form>
            </div>
        </template>
    HTML;
}


function componenteTemplateUsuarioAdminSeleccionado()
{
    echo
        <<<HTML
        <template id="plantilla_usuario-seleccionado-administrador">
            <div class=" overlay">
                <form id="formulario_eliminar" class="formulario form_eliminar form_verificar_eliminar" method="post">

                    <div class="informacion-usuario-eliminar">
                        <h2 class="titulo_eliminar-modal">Estas seguro de eliminar a: </h2>
                        <p class="info_usuario_eliminar">Clave: <span id="clave-info"></span></p>
                        <p class="info_usuario_eliminar">Nombre: <span id="nombre-info"></span></p>
                        <p class="info_usuario_eliminar">Apellidos: <span id="apellidos-info"></span></p>
                        <p class="info_usuario_eliminar">Rol: <span id="rol-info"></span></p>
                        <p class="info_usuario_eliminar">Correo: <span id="correo-info"></span></p>
                        <input type="hidden" name="identificador" value="" id="identificador">
                        <input type="submit" name="formulario" value="Eliminar" class="btn_eliminar btn_ventana-eliminar">
                    </div>

                    <img class="close close_eliminar" src="../../assets/iconos/ic_close.webp"
                        alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">

                </form>

            </div>
        </template>
    HTML;
}

function componenteTemplateUsuarioJefeSeleccionado()
{
    echo
        <<<HTML
        <template id="plantilla_usuario-seleccionado-jefe">
            <div class=" overlay">
                <form id="formulario_eliminar" class="formulario form_eliminar form_verificar_eliminar" method="post">
                    <div class="informacion-usuario-eliminar">
                        <h2 class="titulo_eliminar-modal">Estas seguro de eliminar a: </h2>
                        <p class="info_usuario_eliminar">Clave: <span id="clave-info"></span></p>
                        <p class="info_usuario_eliminar">Nombre: <span id="nombre-info"></span></p>
                        <p class="info_usuario_eliminar">Apellidos: <span id="apellidos-info"></span></p>
                        <p class="info_usuario_eliminar">Carrera: <span id="carrera-info"></span></p>
                        <p class="info_usuario_eliminar">Rol: <span id="rol-info"></span></p>
                        <p class="info_usuario_eliminar">Correo: <span id="correo-info"></span></p>
                        <input type="hidden" name="identificador" value="" id="identificador">
                        <input type="submit" name="formulario" value="Eliminar" class="btn_eliminar btn_ventana-eliminar">
                    </div>
                    <img class="close close_eliminar" src="../../assets/iconos/ic_close.webp"
                        alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">

                </form>

            </div>
        </template>

    HTML;
}

function componenteTemplateAgregarCarrera()
{
    echo
        <<<HTML
        <template id="plantilla_agregar_carrera">
            <div class="overlay_cambiar-contraseña overlay_ventana">
                <form class="formulario" method="post">
                    <h2 class="titulo">Agregar Carrera</h2>
                    <div class="inputs-cambio-contraseña">

                        <section class="seccion_tipo">

                            <label for="carrera_nueva" class="contenedor_input">
                                <input class="input_login" type="text" name="carrera_nueva" id="carrera_nueva"
                                    placeholder=" " autocomplete="current-password">
                                <span class="nombre_input">Escriba la Carrera</span>
                            </label>
                            <div class="opciones_carrera">
                                <label for="cantidad_grupos" class="contenedor_numerico">
                                    <span class="nombre_opcion">Cantidad de Grupos</span>
                                    <input min="1" max="15" step="1" value="1" class="input_num" type="number"
                                        name="grupos_nueva_carrera" id="cantidad_grupos" placeholder=" "
                                        autocomplete="current-password">
                                </label>

                                <label for="id_grupo" class="contenedor_numerico">
                                    <span class="nombre_opcion">Id de la Carrera</span>
                                    <input min="1" max="10" step="1" value="1" class="input_num" type="number"
                                        name="id_carrera_nueva" id="id_grupo" placeholder=" "
                                        autocomplete="current-password">
                                </label>
                            </div>
                        </section>

                        <section class="seccion_tipo">
                            <label for="tipo_carrera" class="select_carrera">
                                <span class="nombre_select">Escoga el Tipo de Carrera</span>
                                <select name="tipo_carrera" id="tipo_carrera">
                                    <option value="Licenciatura">Licenciatura</option>
                                    <option value="Ingenieria">Ingenieria</option>
                                </select>
                            </label>

                            <label class="select_carrera">
                                <span class="nombre_select">Escoga las modalidades</span>
                                
                                <label class="switch">
                                    <input name="escolarizado" type="checkbox" id="modalidad_escolarizado">
                                    <div class="slider">
                                        <div class="circle">
                                            
                                        </div>
                                    </div>
                                    <span>Escolarizado</span>
                                </label>

                                <label class="switch">
                                    <input type="checkbox" name="flexible" id="modalidad_flexible">
                                    <div class="slider">
                                        <div class="circle">
                                            
                                        </div>
                                    </div>
                                    <span>Flexible</span>
                                </label>
                            
                            </label>
                        </section>

                    </div>
                    <input type="submit" name="formulario" class="btn-submit btn_login" value="Agregar Carrera">


                    <img class="close" id="cerrar" src="../../assets/iconos/ic_close.webp"
                        alt="icono para cerrar la ventana de agregar carrera" loading="lazy">
                </form>
            </div>
        </template>
    HTML;
}

function componenteTemplateConfigurarCarrera()
{
    echo
        <<<HTML
        <template id="plantilla_configurar_carrera">
            <div class="overlay_cambiar-contraseña overlay_ventana">
                <form class="formulario" method="post">
                    <h2 class="titulo">Agregar Carrera</h2>
                    <div class="inputs-cambio-contraseña">
    
                        <section class="seccion_tipo">
                            <input type="hidden" name="carrera_antigua" id="carrera_antigua">
                            <input type="hidden" name="clave_grupo_antigua" id="clave_grupo_antigua">
    
                            <label for="carrera_modificar" class="contenedor_input">
                                <input class="input_login" type="text" name="carrera_nueva" id="carrera_modificar"
                                    placeholder=" " autocomplete="current-password">
                                <span class="nombre_input">Escriba la Carrera</span>
                            </label>
                            <div class="opciones_carrera">
                                <label for="cantidad_grupos" class="contenedor_numerico">
                                    <span class="nombre_opcion">Cantidad de Grupos</span>
                                    <input min="1" max="15" step="1" value="1" class="input_num" type="number"
                                        name="grupos_nueva_carrera" id="cantidad_grupos" placeholder=" "
                                        autocomplete="current-password">
                                </label>
    
                                <label for="id_grupo" class="contenedor_numerico">
                                    <span class="nombre_opcion">Id de la Carrera</span>
                                    <input min="1" max="10" step="1" value="1" class="input_num" type="number"
                                        name="id_carrera_nueva" id="id_grupo" placeholder=" "
                                        autocomplete="current-password">
                                </label>
                            </div>
                        </section>
    
                        <section class="seccion_tipo">
                            <label for="tipo_carrera" class="select_carrera">
                                <span class="nombre_select">Escoga el Tipo de Carrera</span>
                                <select name="tipo_carrera" id="tipo_carrera">
                                    <option value="Licenciatura">Licenciatura</option>
                                    <option value="Ingenieria">Ingenieria</option>
                                </select>
                            </label>
    
                            <label class="select_carrera">
                                <span class="nombre_select">Escoga las modalidades</span>
                                
                                <label class="switch">
                                    <input name="escolarizado" value="Escolarizado" type="checkbox" id="modalidad_escolarizado">
                                    <div class="slider">
                                        <div class="circle">
                                            
                                        </div>
                                    </div>
                                    <span>Escolarizado</span>
                                </label>

                                <label class="switch">
                                    <input type="checkbox" name="flexible" value="Flexible" id="modalidad_flexible">
                                    <div class="slider">
                                        <div class="circle">
                                            
                                        </div>
                                    </div>
                                    <span>Flexible</span>
                                </label>
                            
                            </label>
                        </section>
    
                    </div>
                    <input type="submit" name="formulario" class="btn-submit btn_login" value="Modificar Carrera">
    
    
                    <img class="close" id="cerrar" src="../../assets/iconos/ic_close.webp"
                        alt="icono para cerrar la ventana de agregar carrera" loading="lazy">
                </form>
            </div>
        </template>

    HTML;
}