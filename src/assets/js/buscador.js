$(document).ready(function () {
    // Obtener carrera desde el atributo data del body
    const carrera = document.body.dataset.carrera === null ? "" : document.body.dataset.carrera;
    const modo = document.body.dataset.modo;

    console.log(carrera)
    console.log(modo)
    if (carrera === "") {
        buscarUsuarios("buscarPersonal.php");
    } else {
        console.log(carrera)
        buscarUsuarios("buscarEstudiante.php");
    }
});

function buscarUsuarios(nombre) {
    let timer;
    let carrera = document.body.dataset.carrera;
    let modo = document.body.dataset.modo;
    console.log(carrera)
    console.log(modo)

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
                        console.log(data)
                        console.log(nombre)
                        console.log(modo)


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
function cargarUsuarioEliminar(id, nombre) {
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
        error: function (data) {
            mostrarDatosParaEliminar(data);
            cerrarVentana('.close_eliminar');
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