const btn = document.getElementById('button');
const formulario = document.getElementById('form');


$(document).ready(function () {
	$('#form').on('submit', function (e) {
		e.preventDefault();
		console.log('entro');
		const correo = $('#correo').val();
		const id_usuario = $('#id_usuario').val();
		const contraseñaNueva = generarContraseña();

		if (correo === '' || id_usuario === '') {
			mostrarTemplate(
				'Ingresa todos los campos',
				'../../assets/iconos/ic_error.webp',
				'var(--rojo)',
				'miTemplate'
			)
			return false; // Detiene la ejecución
		}

		$.ajax({
			url: '../../utils/envioCorreo.php',
			method: 'POST',
			data: {
				id_usuario: id_usuario,
				correo: correo,
				contraseñaNueva: contraseñaNueva
			},
			dataType: 'json',
			success: function (response) {
				const { success, message } = response;
				if (success === true) {
					mostrarTemplate(
						message || 'Correo enviado con éxito.',
						'../../assets/iconos/ic_correcto.webp',
						'var(--verde)',
						'miTemplate'
					)
					formulario.reset();
				} else {
					mostrarTemplate(
						message || 'Hubo un error al enviar el correo.',
						'../../assets/iconos/ic_error.webp',
						'var(--rojo)',
						'miTemplate'
					)
				}
			},
			error: function (xhr, status, error) {
				mostrarTemplate(
					error || 'Hubo un error al enviar el correo.',
					'../../assets/iconos/ic_error.webp',
					'var(--rojo)',
					'miTemplate'
				)
			}
		});

	});
});



function generarContraseña() {
	const caracteres =
		'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	let contraseña = '';
	for (let i = 0; i < 8; i++) {
		const randomIndex = Math.floor(Math.random() * caracteres.length);
		contraseña += caracteres[randomIndex];
	}
	return contraseña;
}

function crearContraseñaInput(nuevaContraseña) {
	const contraseñaInput = document.createElement('input');
	contraseñaInput.classList.add('password');
	contraseñaInput.type = 'hidden';
	contraseñaInput.name = 'password';
	contraseñaInput.value = nuevaContraseña;
	return contraseñaInput;
}

function contraseñaCreada() {
	let nuevaContraseña = '';
	nuevaContraseña = generarContraseña();
	return nuevaContraseña;
}
