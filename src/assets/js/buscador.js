function buscarUsuarios(nombre, carrera) {
    var query = document.getElementById('buscar').value;
    if (query.length >= 0) {
        $.ajax({
            url: `../../query/${nombre}`,
            method: 'GET',
            data: {
                q: query,
                carrera: carrera
            },
            success: function (data) {
                document.getElementById('resultados').innerHTML = data;
                seleccionar();
            },
        });
    } else {
        document.getElementById('resultados').innerHTML = '';
    }
}

function cargarUsuario(id, nombre) {
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

function cerrarVentana(boton) {
    const btnClose = document.querySelector(boton)

    if (btnClose !== null) {
        const overlayVentana = document.querySelector('.overlay_ventana')
        btnClose.addEventListener('click', () => {
            overlayVentana.remove()
        })
    }
}