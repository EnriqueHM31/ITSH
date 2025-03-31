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
            let mensajeError = "Error desconocido";
            try {
                // Intentar convertir el responseText en JSON
                let jsonStart = xhr.responseText.indexOf("{");
                if (jsonStart !== -1) {
                    let jsonString = xhr.responseText.substring(jsonStart);
                    let jsonData = JSON.parse(jsonString);
                    mensajeError = jsonData["sin_error"] || "Error en el servidor";
                } else {
                    mensajeError = xhr.responseText; // En caso de que no sea JSON válido
                }
            } catch (e) {
                console.error("Error al procesar la respuesta del servidor:", e);
            }
            mostrarTemplate(
                mensajeError,
                '../../assets/iconos/ic_error.webp',
                'var(--rojo)',
                'miTemplate'
            )
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