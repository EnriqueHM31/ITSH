function mostrarModal() {
    const template = document.getElementById("modal_seguridad")
    const clone = document.importNode(template.content, true)
    document.body.appendChild(clone)
}

function mostrarTemplate(mensaje, urlImagen, color, nombre) {
    const template = document.getElementById(nombre)
    const clone = document.importNode(template.content, true)
    clone.getElementById('mensaje').innerText = mensaje
    clone.getElementById('imagen').src = urlImagen
    clone.getElementById('btn_mensaje').style.backgroundColor = color
    document.body.appendChild(clone)
}

function cerrarTemplate() {
    const dialogContainer = document.getElementById('overlay')
    const notificacionIMG = document.querySelector('.img_notificacion')
    if (dialogContainer) {
        notificacionIMG.remove()
        dialogContainer.remove()
    }
}

function obtenerTemplate(templateID) {
    const modalTemplate = document.getElementById(templateID)
    const modalContainer = document.querySelector('body')
    const modalClone = modalTemplate.content.cloneNode(true)
    return { modalClone, modalContainer }
}


function cerrarTemplate(opcion) {
    const dialogContainer = document.getElementById('overlay')
    if (dialogContainer) {
        dialogContainer.remove()
        if (opcion == "cargar") {
            location.reload()
        }
    }
}

const btnClose = document.querySelector('.btn_mensaje_php')
if (btnClose !== null) {
    const overlayVentana = document.querySelector('.overlay')
    btnClose.addEventListener('click', () => {
        overlayVentana.remove()
    })
}


const btnCloseModal = document.querySelector('.close')
if (btnCloseModal !== null) {
    const modal = document.querySelector('.overlay')
    btnCloseModal.addEventListener('click', () => {
        console.log("cierra modal")
        modal.remove()
    })
}