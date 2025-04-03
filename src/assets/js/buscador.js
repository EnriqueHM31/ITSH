// Carga la funcion para buscar usuarios
$(document).ready(function () {
    const carrera = $("body").data("carrera");
    if (carrera === "") {
        buscarUsuarios("buscarPersonal.php");
    } else {
        buscarUsuarios("buscarEstudiante.php");
    }
});

// Funcion paa buscar usuarios y que realiza una accion dependiendo en que modo esta Modificar o Eliminar
function buscarUsuarios(nombre) {
    let timer;
    let carrera = document.body.dataset.carrera;
    let modo = document.body.dataset.modo;

    $("#buscar").on("keyup", function () {
        clearTimeout(timer);
        let query = $(this).val().trim();


        if (query.length >= 0) {
            timer = setTimeout(() => {
                $.ajax({
                    url: `../../query/${nombre}`,
                    method: "GET",
                    data: {
                        q: query,
                        carrera: carrera,
                        modo: modo
                    },

                    success: function (data) {
                        document.getElementById('resultados').innerHTML = data;

                        if (modo === "Eliminar") {
                            seleccionar();
                            return;
                        }
                        if (modo === "Modificar" && nombre === 'buscarPersonal.php') {
                            mostrar("getInfoPersonal.php")
                            return;
                        }
                        if (modo === "Modificar" && nombre === 'buscarEstudiante.php') {
                            mostrar("getInfoEstudiante.php")
                            return
                        }
                    }
                });
            }, 300);
        } else {
            $("#resultados").empty();
        }
    });
}
// Funcion para traer los datos del usuario a eliminar 
function cargarUsuarioEliminar(id, nombre) {
    if (id === "") {
        mostrarTemplate(
            'Busca y seleccione a un usuario',
            '../../assets/iconos/ic_error.webp',
            'var(--rojo)',
            'miTemplate'
        )
        return
    }
    $.ajax({
        url: `../../query/${nombre}`,
        method: 'POST',
        data: {
            id: id,
        },
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                alert(data.error);
            } else {
                mostrarDatosParaEliminar(data);
                cerrarVentana('.close_eliminar');
            }
        },
        error: function (xhr) {
            mostrarErrorAjax(xhr);
        },
    });
}

function seleccionar() {
    const elements = document.querySelectorAll('.result p')

    elements.forEach(element => {
        element.addEventListener('click', () => {
            elements.forEach(e => {
                e.style.backgroundColor = 'var(--blanco)'
                e.style.color = 'var(--vino)'
            })
            element.style.backgroundColor = 'var(--vino)'
            element.style.color = 'var(--blanco)'
        })
    })
}

function mostrar(tableData) {
    document.querySelectorAll('.result').forEach(element => {
        element.addEventListener("click", () => {
            const id = element.querySelector("p").dataset.id;
            cargarUsuarioModificar(id, tableData);

        });
    })
}

function cerrarVentana(boton) {
    const btnClose = document.querySelector(boton)

    if (btnClose !== null) {
        const overlayVentana = document.querySelector('.overlay_ventana')
        btnClose.addEventListener('click', () => {
            overlayVentana.remove()
        })
    }
}