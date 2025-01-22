function seleccionar() {
	const elements = document.querySelectorAll('.result p');

	elements.forEach(element => {
		element.addEventListener('click', () => {
			elements.forEach(e => {
				e.style.backgroundColor = 'var(--blanco)';
				e.style.color = 'var(--vino)';
			});
			element.style.backgroundColor = 'var(--vino)';
			element.style.color = 'var(--blanco)';
		});
	});
}

const btnContraseña = document.getElementById('btn_contraseña');
const template = document.getElementById('plantilla_cambiar-contraseña');

btnContraseña.addEventListener('click', () => {
	const content = template.content.cloneNode(true);
	document.querySelector('body').appendChild(content);
	cerrarVentana('.close');
});

const params = new URLSearchParams(window.location.search);
if (params.get('Eliminar') === 'true') {
	const modalTemplate = document.getElementById(
		'plantilla_eliminar-personal',
	);
	const modalContainer = document.querySelector('body');
	const modalClone = modalTemplate.content.cloneNode(true);
	modalContainer.appendChild(modalClone);

	const botonEliminar = document.getElementById('eliminar_registro');
	botonEliminar.addEventListener('click', () => {
		const seleccion = RegistroSeleccionado();
		cargarUsuario(seleccion);
		cerrarVentanaEliminar();
	});

	const plantilla = document.querySelector('.overlay .formulario');
	plantilla.querySelector('.close').addEventListener('click', () => {
		document.querySelector('.overlay').remove();
		cerrarVentanaEliminar();
	});
}

function cerrarVentana(boton) {
	const btnClose = document.querySelector(boton);

	if (btnClose !== null) {
		const overlayVentana = document.querySelector('.overlay_ventana');
		btnClose.addEventListener('click', () => {
			overlayVentana.remove();
		});
	}
}

function RegistroSeleccionado() {
	const elements = document.querySelectorAll('.result p');
	if (elements !== null) {
		const seleccionado = Array.from(elements).filter(e => {
			const computedStyle = getComputedStyle(e);
			return computedStyle.backgroundColor === 'rgb(97, 18, 50)';
		});
		if (seleccionado[0] === undefined) return '';
		return seleccionado[0].dataset.id;
	} else {
		return console.log('No hay resultados');
	}
}

function cerrarVentanaEliminar() {
	const currentUrl = new URL(window.location.href);
	currentUrl.searchParams.delete('Eliminar');
	window.history.replaceState({}, '', currentUrl);
}
