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

const btnContraseña = document.getElementById('btn_contraseña')
const template = document.getElementById('plantilla_cambiar-contraseña')

btnContraseña.addEventListener('click', () => {
	const content = template.content.cloneNode(true)
	document.querySelector('body').appendChild(content)
	cerrarVentana('.close')
})

const params = new URLSearchParams(window.location.search)
if (params.get('Eliminar') === 'true') {
	const { modalClone, modalContainer } = obtenerTemplate(
		'plantilla_eliminar-personal',
	)
	modalContainer.appendChild(modalClone)

	const botonEliminar = document.getElementById('eliminar_registro')
	botonEliminar.addEventListener('click', () => {
		const seleccion = RegistroSeleccionado()
		cargarUsuario(seleccion)
		cerrarVentanaEliminar()
	})

	const plantilla = document.querySelector('.overlay .formulario')
	const btnClose = plantilla.querySelector('.close')
	btnClose.addEventListener('click', () => {
		document.querySelector('.overlay').remove()
		cerrarVentanaEliminar()
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
		modalError()
		return
	}
}

function cerrarVentanaEliminar() {
	const currentUrl = new URL(window.location.href)
	currentUrl.searchParams.delete('Eliminar')
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
		modalClone.getElementById('id_modalidad-info').innerText =
			data.id_modalidad
		modalClone.getElementById('rol-info').innerText = data.rol
		modalClone.getElementById('correo-info').innerText = data.correo
		modalClone.getElementById('identificador').value = data.matricula
		modalContainer.appendChild(modalClone)
	}
}

function modalError() {
	const { modalClone, modalContainer } = obtenerTemplate(
		'plantilla_usuario-seleccionado-error',
	)

	modalClone.getElementById('detalles_eliminar_error').innerText =
		'Se necesita que busque un registro primero'

	modalContainer.appendChild(modalClone)
}

function obtenerTemplate(templateID) {
	const modalTemplate = document.getElementById(templateID)
	const modalContainer = document.querySelector('body')
	const modalClone = modalTemplate.content.cloneNode(true)

	return { modalClone, modalContainer }
}
