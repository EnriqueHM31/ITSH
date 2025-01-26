const btnContraseña = document.getElementById('btn_contraseña')
const cambiarContraseña = document.getElementById(
	'plantilla_cambiar-contraseña',
)

btnContraseña.addEventListener('click', () => {
	const content = cambiarContraseña.content.cloneNode(true)
	document.querySelector('body').appendChild(content)
	cerrarVentana('.close')
})

function cerrarVentana(boton) {
	const btnClose = document.querySelector(boton)

	if (btnClose !== null) {
		const overlayVentana = document.querySelector('.overlay_ventana')
		btnClose.addEventListener('click', () => {
			overlayVentana.remove()
		})
	}
}
