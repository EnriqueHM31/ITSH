function buscarUsuarios() {
    var query = document.getElementById('buscar').value;
    if (query.length >= 0) {
        $.ajax({
            url: '../../query/buscar.php',
            method: 'GET',
            data: {
                q: query
            },
            success: function (data) {

                document.getElementById('resultados').innerHTML = data;
                seleccionar()
            }
        });

    } else {
        document.getElementById('resultados').innerHTML = '';
    }
}

function seleccionar() {
    const elements = document.querySelectorAll('.result p');

    elements.forEach(element => {
        element.addEventListener('click', () => {
            elements.forEach(e => {
                e.style.backgroundColor = 'var(--blanco)';
                e.style.color = 'var(--vino)';
            });
            element.style.backgroundColor = 'var(--vino)';
            element.style.color = 'var(--blanco)';

        });
    });
}

const btnContraseña = document.getElementById("btn_contraseña")
const template = document.getElementById('plantilla_cambiar-contraseña');

btnContraseña.addEventListener("click", () => {
    const content = template.content.cloneNode(true);
    document.querySelector("body").appendChild(content)
    cerrarVentana(".close")
})

const params = new URLSearchParams(window.location.search);
if (params.get('Eliminar') === 'true') {
    const modalTemplate = document.getElementById('plantilla_eliminar-personal');
    const modalContainer = document.querySelector('body');
    const modalClone = modalTemplate.content.cloneNode(true);
    modalContainer.appendChild(modalClone);

    const botonEliminar = document.getElementById("eliminar_registro")
    botonEliminar.addEventListener("click", () => {
        const seleccion = RegistroSeleccionado();
        cargarUsuario(seleccion)
        cerrarVentanaEliminar()
    })

    const plantilla = document.querySelector('.overlay .formulario');
    plantilla.querySelector(".close").addEventListener('click', () => {
        document.querySelector(".overlay").remove();
        cerrarVentanaEliminar()
    });
}

function cerrarVentana(boton) {
    const btnClose = document.querySelector(boton)

    if (btnClose !== null) {
        const overlayVentana = document.querySelector(".overlay_ventana");
        btnClose.addEventListener('click', () => {
            overlayVentana.remove();
        });
    }
}

function RegistroSeleccionado() {

    const elements = document.querySelectorAll('.result p');
    if (elements !== null) {
        const seleccionado = Array.from(elements).filter(e => {
            const computedStyle = getComputedStyle(e);
            return computedStyle.backgroundColor === 'rgb(97, 18, 50)';
        });
        console.log(elements)
        console.log(seleccionado[0])
        if (seleccionado[0] === undefined) return ""
        return seleccionado[0].dataset.id
    } else {
        return console.log("No hay resultados");
    }
}

function cargarUsuario(id) {
    $.ajax({
        url: '../../query/obtenerRegistro.php',
        method: 'POST',
        data: {
            id: id
        },
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                alert(data.error);
            } else {
                mostrarDatosParaEliminar(data)
                cerrarVentana(".close_eliminar")
            }
        },
        error: function (data) {
            mostrarDatosParaEliminar(data)
            cerrarVentana(".close_eliminar")
        }
    });
}

function mostrarDatosParaEliminar(data) {


    if (data.identificador === undefined) {
        const modalTemplate = document.getElementById('plantilla_usuario-seleccionado-error');
        const modalContainer = document.querySelector('body');
        const modalClone = modalTemplate.content.cloneNode(true);

        modalClone.getElementById("detalles_eliminar_error").innerText = "Se necesita que busque un registro primero"

        modalContainer.appendChild(modalClone);
    } else {
        const modalTemplate = document.getElementById('plantilla_usuario-seleccionado');
        const modalContainer = document.querySelector('body');
        const modalClone = modalTemplate.content.cloneNode(true);

        modalClone.getElementById("clave_info").innerText = data.identificador
        modalClone.getElementById("nombre_info").innerText = data.nombre
        modalClone.getElementById("apellidos_info").innerText = data.apellidos
        modalClone.getElementById("carrera_info").innerText = data.carrera
        modalClone.getElementById("cargo_info").innerText = data.cargo
        modalClone.getElementById("identificador").value = data.identificador

        modalContainer.appendChild(modalClone);
    }
}

function cerrarVentanaEliminar() {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.delete('Eliminar');
    window.history.replaceState({}, '', currentUrl);
}