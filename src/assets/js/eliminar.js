function mostrarPanelEliminacion(nombreTemplate, nombreArchivo, nombreGET) {
	const { modalClone, modalContainer } = obtenerTemplate(nombreTemplate)
	modalContainer.appendChild(modalClone)

	const botonEliminar = document.getElementById('eliminar_registro')
	botonEliminar.addEventListener('click', () => {
		const seleccion = RegistroSeleccionado()
		cargarUsuarioEliminar(seleccion, nombreArchivo)
		cerrarVentanaEliminar(nombreGET)
	})

	const plantilla = document.querySelector('.overlay .formulario')
	const btnClose = plantilla.querySelector('.close')
	btnClose.addEventListener('click', () => {
		document.querySelector('.overlay').remove()
		cerrarVentanaEliminar(nombreGET)
	})
}

const params = new URLSearchParams(window.location.search)
if (params.get('EliminarPersonal') === 'true') {
	mostrarPanelEliminacion('plantilla_eliminar-personal', 'getInfoPersonal.php', "EliminarPersonal")
}

if (params.get('EliminarEstudiante') === 'true') {
	mostrarPanelEliminacion('plantilla_eliminar-estudiante', 'getInfoEstudiante.php', "EliminarEstudiante")
}


function RegistroSeleccionado() {
	const elements = document.querySelectorAll('.result p')
	if (elements !== null) {
		const seleccionado = Array.from(elements).filter(e => {
			const computedStyle = getComputedStyle(e)
			return computedStyle.backgroundColor === 'rgb(97, 18, 50)'
		})
		if (seleccionado[0] === undefined) return ''
		return seleccionado[0].dataset.id
	} else {
		modalErrorEliminar()
		return
	}
}

function modalErrorEliminar() {
	const { modalClone, modalContainer } = obtenerTemplate('plantilla_usuario-seleccionado-error')
	modalClone.getElementById('detalles_eliminar_error').innerText = 'Se necesita que busque un registro primero'
	modalContainer.appendChild(modalClone)
}

function cerrarVentanaEliminar(nombre) {
	const currentUrl = new URL(window.location.href)
	currentUrl.searchParams.delete(nombre)
	window.history.replaceState({}, '', currentUrl)
}

function mostrarDatosParaEliminar(data) {
	if (data.rol === 'Administrador') {
		const { modalClone, modalContainer } = obtenerTemplate(
			'plantilla_usuario-seleccionado-administrador',
		)
		modalClone.getElementById('clave-info').innerText = data.clave_empleado
		modalClone.getElementById('nombre-info').innerText = data.nombre
		modalClone.getElementById('apellidos-info').innerText = data.apellidos
		modalClone.getElementById('rol-info').innerText = data.rol
		modalClone.getElementById('correo-info').innerText = data.correo
		modalClone.getElementById('identificador').value = data.clave_empleado
		modalContainer.appendChild(modalClone)
		return
	} else if (data.rol === 'Jefe de Carrera') {
		const { modalClone, modalContainer } = obtenerTemplate(
			'plantilla_usuario-seleccionado-jefe',
		)

		modalClone.getElementById('clave-info').innerText = data.clave_empleado
		modalClone.getElementById('nombre-info').innerText = data.nombre
		modalClone.getElementById('apellidos-info').innerText = data.apellidos
		modalClone.getElementById('carrera-info').innerText = data.carrera
		modalClone.getElementById('rol-info').innerText = data.rol
		modalClone.getElementById('correo-info').innerText = data.correo
		modalClone.getElementById('identificador').value = data.clave_empleado
		modalContainer.appendChild(modalClone)
	} else if (data.rol === 'Estudiante') {
		const { modalClone, modalContainer } = obtenerTemplate(
			'plantilla_usuario-seleccionado-estudiante',
		)
		modalClone.getElementById('matricula-info').innerText = data.matricula
		modalClone.getElementById('nombre-info').innerText = data.nombre
		modalClone.getElementById('apellidos-info').innerText = data.apellidos
		modalClone.getElementById('grupo-info').innerText = data.grupo
		modalClone.getElementById('carrera-info').innerText = data.carrera
		modalClone.getElementById('id_modalidad-info').innerText = data.id_modalidad
		modalClone.getElementById('correo-info').innerText = data.correo
		modalClone.getElementById('identificador').value = data.matricula
		modalContainer.appendChild(modalClone)
	}
}

