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