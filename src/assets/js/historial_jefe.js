const archivos = document.querySelectorAll('.archivo');

archivos.forEach(element => {
    element.addEventListener('click', () => {
        const id = element.dataset.id;
        const index_justificante = element.dataset.id_justificante;

        mostrarDatosSolicitud(id, index_justificante);
    })
})

function mostrarDatosSolicitud(id, index_justificante) {
    $.ajax({
        url: `../../query/DatosJustificanteAlumno.php`,
        method: 'POST',
        data: {
            id: id
        },
        dataType: 'json',
        success: function (data) {
            mostrarDatos(data, index_justificante)
        },
        error: function (xhr) {
            mostrarErrorAjax(xhr);
        },
    });
}

function mostrarDatos(data, index_justificante) {
    console.log(data);

    const fecha = data.fecha_creacion

    console.log(data)
    if (data.justificante !== "") {
        link = `<a target="_blank" href="../../layouts/Alumno/justificantes/${data.carrera}/${data.justificante}/">Justificante${index_justificante}.pdf</a>`
    } else {
        link = ""
    }

    contenido = `
            <div class="datos_justificante">    
                <div class="titulo_justificante">
                    <h2>Justificante</h2>
                </div>
                <p><strong>Folio:</strong><span> ${index_justificante}</span></p>
                
                
                <p><strong>Nombre: </strong> <span>${data.nombre_alumno}</span></p>
                <p><strong>Motivo: </strong> <span>${data.motivo}</span></p>
                <p><strong>Fecha de Creación: </strong><span>${fecha}</span></p>
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