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
			document.querySelector('.menu').style.transform = 'translateX(0)'
			document
				.querySelector('.close_contenedor')
				.addEventListener('click', () => {
					document.querySelector('.menu').style.transform = 'translateX(100dvw)'
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

function mostrarErrorAjax(xhr) {
	let mensajeError = 'Error desconocido';
	try {
		let jsonStart = xhr.responseText.indexOf('{');
		if (jsonStart !== -1) {
			let jsonString = xhr.responseText.substring(jsonStart);
			let jsonData = JSON.parse(jsonString);
			mensajeError = jsondata['success'] || 'Error en el servidor';
		} else {
			mensajeError = xhr.responseText;
		}
	} catch (e) {
		mostrarTemplate(
			'Error al procesar la respuesta del servidor',
			'../../assets/iconos/ic_error.webp',
			'var(--rojo)',
			'miTemplate',
		);
		return;
	}

	mostrarTemplate(
		mensajeError,
		'../../assets/iconos/ic_error.webp',
		'var(--rojo)',
		'miTemplate',
	);
}


const button = document.querySelector('.plazo');
const container = document.querySelector('.fecha_ausencia_container');

if (button) {

	button.addEventListener('mouseenter', () => {
		container.classList.add('show-tooltip');
	});

	button.addEventListener('mouseleave', () => {
		container.classList.remove('show-tooltip');
	});

}


const template = document.getElementById('modal-template');
const dateActual = document.getElementById('fecha_de_ausencia');

if (template) {
	const clone = template.content.cloneNode(true);
	document.body.appendChild(clone);

	function openModal() {
		document.getElementById('modalOverlay').classList.add('active');
		document.getElementById('fecha_de_ausencia').hidden = true;
		document.getElementById('rangoFechas').hidden = false;
	}

	function closeModal() {
		const rango = document.getElementById('rangoFechas').value;

		if (rango === "") {
			document.getElementById('fecha_de_ausencia').hidden = false;
			document.getElementById('rangoFechas').hidden = true;
		}
		document.getElementById('modalOverlay').classList.remove('active');
	}

	function guardarFechas() {
		const inicio = document.getElementById('fechaInicio').value;
		const fin = document.getElementById('fechaFin').value;

		if (!inicio || !fin) {
			mostrarTemplate("Por favor selecciona ambas fechas.", "../../assets/iconos/ic_error.webp", "--rojo", "miTemplate");
			return;
		}

		const fechaInicio = new Date(inicio);
		const fechaFin = new Date(fin);
		const hoy = new Date();
		hoy.setHours(0, 0, 0, 0); // Elimina horas para comparación exacta

		if (fechaInicio > fechaFin) {
			mostrarTemplate(
				"La fecha de inicio no puede ser posterior a la fecha de fin.",
				"../../assets/iconos/ic_error.webp",
				"var(--rojo)",
				"miTemplate",
			);
			return;
		}

		if (fechaFin > hoy) {
			mostrarTemplate(
				"La fecha de fin no puede ser posterior a la fecha actual.",
				"../../assets/iconos/ic_error.webp",
				"var(--rojo)",
				"miTemplate",
			);
			return;
		}

		const formatear = fecha => {
			const d = new Date(fecha);
			const dia = String(d.getDate()).padStart(2, '0');
			const mes = String(d.getMonth() + 1).padStart(2, '0');
			const año = d.getFullYear();
			return `${dia}-${mes}-${año}`;
		};

		const resultado = `${formatear(fechaInicio)} al ${formatear(fechaFin)}`;
		document.getElementById('rangoFechas').value = resultado;

		console.log("Guardado:", resultado);
		closeModal();
	}
}

