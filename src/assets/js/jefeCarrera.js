function ajustarContenido(array) {
	const screenWidth = window.innerWidth;

	const tabla = document.getElementById('table');
	const detalles = document.getElementById('lista-solicitudes-details');

	// Limpiar los contenidos
	if (detalles) detalles.innerHTML = '';
	if (tabla) tabla.innerHTML = '';

	if (screenWidth > 1000) {
		let contenidoTabla = array[0][array[0].length - 1]; // Primer contenido
		for (let i = 0; i < array[0].length - 1; i++) {
			contenidoTabla += array[0][i]; // Añadir el resto
		}
		tabla.innerHTML = contenidoTabla; // Asignar todo el contenido de una vez
	} else {
		let contenidoDetalles = '';
		for (let i = 0; i < array[1].length; i++) {
			contenidoDetalles += array[1][i]; // Crear el contenido completo
		}
		detalles.innerHTML = contenidoDetalles; // Asignar todo el contenido de una vez
	}
}

function mostrarDatosResize(array) {
	document.addEventListener('DOMContentLoaded', function () {
		ajustarContenido(array); // Ejecutar directamente cuando la página carga
	});

	// Ejecutar al redimensionar la ventana
	window.addEventListener('resize', function () {
		ajustarContenido(array); // Ejecutar nuevamente al redimensionar
	});
}

function eliminarFila(objeto) {
	const datosSolicitud = obtenerDatosSolicitud(objeto);
	({ id_solicitud, evidencia, elemento } = datosSolicitud);
	eliminarSolicitud(id_solicitud, evidencia, elemento);
}

async function eliminarSolicitud(id, nombreArchivo, elemento) {
	$.ajax({
		url: '../../query/eliminarSolicitud.php',
		method: 'POST',
		data: {
			id: id,
			nombreArchivo: nombreArchivo,
		},
		dataType: 'json',
		success: function (data) {
			if (data['success'] === true) {
				mostrarTemplate(
					'Se ha eliminado la solicitud',
					'../../assets/iconos/ic_correcto.webp',
					'var(--verde)',
					'miTemplate_cargar',
				);
				elemento.remove();
			} else {
				mostrarTemplate(
					data['error'],
					'../../assets/iconos/ic_error.webp',
					'var(--rojo)',
					'miTemplate_cargar',
				);
			}
		},
		error: function (xhr) {
			mostrarErrorAjax(xhr);
		},
	});
}

function rechazarSolicitud(objeto) {
	id_jefe = objeto.getAttribute('data-id');

	const datosSolicitud = obtenerDatosSolicitud(objeto);
	({ id_solicitud, matricula, nombre, apellidos, grupo, motivo, fecha, estado } = datosSolicitud);

	if (verificarOpciones(estado)) {
		return;
	}

	$.ajax({
		url: '../../query/rechazarSolicitud.php',
		method: 'POST',
		data: {
			id: id_solicitud,
			nombreArchivo: estado,
		},
		dataType: 'json',
		success: function (data) {
			if (data['success'] === 'True') {
				mostrarTemplate(
					'Se ha rechazado la solicitud',
					'../../assets/iconos/ic_correcto.webp',
					'var(--verde)',
					'miTemplate_cargar',
				);
			} else {
				mostrarTemplate(
					'Ocurrio un error al rechazar la solicitud',
					'../../assets/iconos/ic_error.webp',
					'var(--rojo)',
					'miTemplate',
				);
			}
		},
		error: function (xhr) {
			mostrarErrorAjax(xhr);
		},
	});
}

function ObtenerDatosDetails(objeto) {
	detalle = objeto.closest('details');
	DataSolicitud = detalle.getAttribute('data-datos').split(',');
	id_solicitud = DataSolicitud[0].trim();
	matricula = DataSolicitud[1].trim();
	nombre = DataSolicitud[2].trim();
	apellidos = DataSolicitud[3].trim();
	grupo = DataSolicitud[4].trim();
	motivo = DataSolicitud[5].trim();
	fecha = DataSolicitud[6].trim();
	estado = DataSolicitud[7].trim();
	evidencia = DataSolicitud[8].trim();

	return { id_solicitud, matricula, nombre, apellidos, grupo, motivo, fecha, evidencia, estado, elemento: detalle };
}

function obtenerDatosTable(objeto) {
	fila = objeto.closest('tr');
	id_solicitud = fila.querySelectorAll('td')[0].dataset.id;
	matricula = fila.querySelectorAll('td')[1].innerText;
	nombre = fila.querySelectorAll('td')[2].innerText;
	apellidos = fila.querySelectorAll('td')[3].innerText;
	grupo = fila.querySelectorAll('td')[4].innerText;
	motivo = fila.querySelectorAll('td')[5].innerText;
	fecha = fila.querySelectorAll('td')[6].innerText;
	evidencia = fila.querySelectorAll('td')[7].innerText;
	estado = fila.querySelectorAll('td')[8].className;
	return { id_solicitud, matricula, nombre, apellidos, grupo, motivo, fecha, evidencia, estado, elemento: fila };
}

function obtenerDatosSolicitud(objeto) {

	if (document.getElementById('table').innerText !== '') {
		const datosSolicitudTable = obtenerDatosTable(objeto);
		return datosSolicitudTable
	} else if (document.getElementById('lista-solicitudes-details').innerText !== '') {
		const datosSolicitudDetail = ObtenerDatosDetails(objeto);
		return datosSolicitudDetail
	}
}

function aceptarSolicitud(objeto) {

	id_jefe = objeto.getAttribute('data-id');

	const datosSolicitud = obtenerDatosSolicitud(objeto);
	({ id_solicitud, matricula, nombre, apellidos, grupo, motivo, fecha, estado } = datosSolicitud);

	if (verificarOpciones(estado)) {
		return;
	}

	$.ajax({
		url: '../../utils/creacionJustificantes.php',
		method: 'POST',
		data: {
			id_jefe: id_jefe,
			id_solicitud: id_solicitud,
			matricula: matricula,
			nombre: nombre,
			apellidos: apellidos,
			grupo: grupo,
			motivo: motivo,
			fecha: fecha,
		},
		dataType: 'json',
		success: function (data) {
			if (data['success'] === true) {
				mostrarTemplate(
					'Se ha creado y enviado el justificante',
					'../../assets/iconos/ic_correcto.webp',
					'var(--verde)',
					'miTemplate_cargar',
				);
			} else {
				mostrarTemplate(
					data['success'],
					'../../assets/iconos/ic_error.webp',
					'var(--rojo)',
					'miTemplate',
				);
			}
		},
		error: function (xhr) {
			mostrarErrorAjax(xhr);
		},
	});
}

function verificarOpciones(estado) {
	if (estado == 'aceptada') {
		mostrarTemplate(
			'La solicitud ya ha sido aceptada',
			'../../assets/iconos/ic_error.webp',
			'var(--rojo)',
			'miTemplate',
		);
		return true;
	}

	if (estado == 'rechazada') {
		mostrarTemplate(
			'La solicitud ya ha sido rechazada',
			'../../assets/iconos/ic_error.webp',
			'var(--rojo)',
			'miTemplate',
		);
		return true;
	}
}

//BUSCAR JUSTIFICANTES EN EL HISTORIAL
$(document).ready(function () {
	let timer;

	$('#search-justificantes').on('keyup', function () {
		clearTimeout(timer); // Limpia el temporizador anterior

		let query = $(this).val().trim();

		timer = setTimeout(function () {
			$.ajax({
				url: '../../query/buscarJustificante.php',
				method: 'GET',
				data: { q: query },
				success: function (response) {
					$('#historial').html(response);
				},
			});
		}, 300);
	});
});

function reiniciarFolio() {
	$.ajax({
		url: '../../query/justificantes.php',
		method: 'POST',
		data: {
			reiniciar: 'Si',
		},
		dataType: 'json',
		success: function (data) {

			if (data.mensaje[0] === true) {
				mostrarTemplate(
					data.mensaje[1],
					'../../assets/iconos/ic_correcto.webp',
					'var(--verde)',
					'miTemplate_cargar',
					'miTemplate_cargar',
				);
			}
			if (data.mensaje[0] === false) {
				mostrarTemplate(
					data.mensaje[1],
					'../../assets/iconos/ic_error.webp',
					'var(--rojo)',
					'miTemplate_cargar',
					'miTemplate_cargar',
				);
			}
		},
		error: function (xhr) {
			mostrarErrorAjax(xhr);
		},
	});
}
