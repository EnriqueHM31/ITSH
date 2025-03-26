const btn = document.getElementById('button')
const formulario = document.getElementById('form')
var emailjs

formulario.addEventListener('submit', function (event) {
	event.preventDefault()
	const Matricula = document.getElementById('Matricula').value.trim()
	const user_email = document.getElementById('user_email').value.trim()

	// Verificar si algún campo está vacío
	if (Matricula === '' || user_email === '') {
		mostrarTemplate(
			'Llena los datos del formulario',
			'../../assets/iconos/ic_error.webp',
			'var(--rojo)',
			'miTemplate'
		)
		return
	}

	btn.value = 'Enviando...'

	let password
	password = contraseñaCreada()
	const Inputcontraseña = crearContraseñaInput(password)

	this.appendChild(Inputcontraseña)

	$.ajax({
		url: '../../utils/CambioContraseña.php',
		method: 'POST',
		data: {
			Matricula: Matricula,
			user_email: user_email,
			password: password,
		},
		dataType: 'json',
		success: function (data) {
			if (data.valido) {
				const serviceID = 'default_service'
				const templateID = 'template_d151xen'

				emailjs.sendForm(serviceID, templateID, formulario).then(
					() => {
						btn.value = 'Enviar'
						mostrarTemplate(
							'Tu contraseña fue enviada a tu correo',
							'../../assets/iconos/ic_correcto.webp',
							'var(--verde)',
							'miTemplate'
						)
						formulario.querySelector('.password').remove()
						formulario.reset()
					},
					() => {
						btn.value = 'Enviar'
						mostrarTemplate(
							'Ocurrio un error al enviarte tu contraseña' +
							'../../assets/iconos/ic_error.webp',
							'var(--rojo)',
							'miTemplate'
						)
						formulario.querySelector('.password').remove()
						formulario.reset()
					},
				)
			}
		},
		error: function () {
			btn.value = 'Enviar'
			mostrarTemplate(
				'Ocurrio un error con el servicio' +
				'../../assets/iconos/ic_error.webp',
				'var(--rojo)',
				'miTemplate'
			)
			formulario.querySelector('.password').remove()
			formulario.reset()
		},
	})
})

function generarContraseña() {
	const caracteres =
		'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
	let contraseña = ''
	for (let i = 0; i < 8; i++) {
		const randomIndex = Math.floor(Math.random() * caracteres.length)
		contraseña += caracteres[randomIndex]
	}
	return contraseña
}


function crearContraseñaInput(nuevaContraseña) {
	const contraseñaInput = document.createElement('input')
	contraseñaInput.classList.add('password')
	contraseñaInput.type = 'hidden'
	contraseñaInput.name = 'password'
	contraseñaInput.value = nuevaContraseña
	return contraseñaInput
}

function contraseñaCreada() {
	let nuevaContraseña = ''
	nuevaContraseña = generarContraseña()
	return nuevaContraseña
}
