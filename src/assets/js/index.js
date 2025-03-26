if (window.history.replaceState) {
	window.history.replaceState(null, null, window.location.href)
}

document.addEventListener('DOMContentLoaded', () => {

	// Pagina index Inicio sesion para ver la contraseña
	const btnVerContraseña = document.querySelector('.img-ver')
	if (btnVerContraseña !== null) {
		btnVerContraseña.addEventListener('click', () => {
			const inputContraseña = document.querySelector('.contraseña')

			inputContraseña.type =
				inputContraseña.type === 'text' ? 'password' : 'text'
			src = btnVerContraseña.src.split('/')
			src = src[src.length - 1]
			newSRC = src === 'visible.webp' ? 'invisible.webp' : 'visible.webp'
			btnVerContraseña.src = `./src/assets/extra/${newSRC}`
		})
	}

	//Funcion para el boton cuando es mobile
	if (document.querySelector('.icono_menu') !== null) {
		document.querySelector('.icono_menu').addEventListener('click', () => {
			document.querySelector('.menu').style.display = 'flex'

			document
				.querySelector('.close_contenedor')
				.addEventListener('click', () => {
					document.querySelector('.menu').style.display = 'none'
				})
		})
	}

	// Funcion para los input file y sea el nombre del archivo
	var archivoInput = document.getElementById('archivo')
	if (archivoInput !== null) {
		archivoInput.addEventListener('change', () => {
			var archivoNombre = archivoInput.files[0]
				? archivoInput.files[0].name
				: 'Ningún archivo seleccionado'
			document.getElementById('nombreArchivo').textContent = archivoNombre
		})
	}
})

