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
        url: `../../query/DatosSolicitud.php`,
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

function formatearFecha(fechaStr) {
    // Si ya tiene "/", asume que está bien formateado
    if (fechaStr.includes("/")) {
        return fechaStr;
    }

    // Si es un rango con " al "
    if (fechaStr.includes(" al ")) {
        const partes = fechaStr.split(" al ");
        if (partes.length === 2) {
            // Formatear cada fecha
            const inicio = formatearFechaSimple(partes[0].trim());
            const fin = formatearFechaSimple(partes[1].trim());
            return `${inicio} al ${fin}`;
        }
    }

    // Si no es rango, formatear fecha simple
    return formatearFechaSimple(fechaStr);
}

function formatearFechaSimple(fecha) {
    // Si está en formato YYYY-MM-DD o DD-MM-YYYY con guiones
    const partes = fecha.split("-");
    if (partes.length === 3) {
        // Detectar si es YYYY-MM-DD (parte0 es año) o DD-MM-YYYY (parte2 es año)
        if (partes[0].length === 4) {
            // YYYY-MM-DD a DD/MM/YYYY
            return `${partes[2]}/${partes[1]}/${partes[0]}`;
        } else if (partes[2].length === 4) {
            // DD-MM-YYYY a DD/MM/YYYY (solo reemplazar guiones)
            return `${partes[0]}/${partes[1]}/${partes[2]}`;
        }
    }

    // Si no cumple, devuelve la fecha original
    return fecha;
}



function mostrarDatos(data, index_justificante) {

    fecha = formatearFecha(data.fecha_ausencia);

    if (data.justificante !== "") {
        link = `<a target="_blank" href="../../layouts/Alumno/justificantes/${data.carrera}/${data.justificante}">Justificante${index_justificante}.pdf</a>`
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