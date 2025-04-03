const archivos = document.querySelectorAll('.archivo');

archivos.forEach(element => {
    element.addEventListener('click', () => {
        const id = element.dataset.id;

        mostrarDatosSolicitud(id);
    })
})

function mostrarDatosSolicitud(id) {
    $.ajax({
        url: `../../query/DatosSolicitud.php`,
        method: 'POST',
        data: {
            id: id
        },
        dataType: 'json',
        success: function (data) {
            mostrarDatos(data)
        },
        error: function (xhr) {
            mostrarErrorAjax(xhr);
        },
    });
}

function mostrarDatos(data) {

    fecha = data.fecha_ausencia.split("-").reverse().join("/");

    if (data.justificante !== "") {
        link = `<a target="_blank" href="../../layouts/Alumno/justificantes/${data.justificante}/">Justificante${data.id_justificante}.pdf</a>`
    } else {
        link = ""
    }

    contenido = `
            <div class="datos_solicitud">
                <h2>Solicitud <br> ${fecha}</h2>
                <p>Motivo: ${data.motivo}</p>
                <p>Estado: ${data.estado}</p>
                ${link}
                <img class="close" src="../../assets/iconos/ic_close.webp"
                    alt="icono para cerrar la ventana de cerrar contraseña" loading="lazy">
                    
        </div>
    `

    const modal = document.createElement('div')
    modal.classList.add('overlay')
    modal.innerHTML = contenido

    document.body.appendChild(modal)
    cerrarVentana('.close')

}



function cerrarVentana(boton) {
    const btnClose = document.querySelector(boton)

    if (btnClose !== null) {
        const overlayVentana = document.querySelector('.overlay')
        btnClose.addEventListener('click', () => {
            overlayVentana.remove()
        })
    }
}