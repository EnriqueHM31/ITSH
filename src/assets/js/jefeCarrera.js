
function buscarUsuarios() {
	var query = document.getElementById('buscar').value;
	if (query.length >= 0) {
		$.ajax({
			url: '../../query/buscarEstudiante.php',
			method: 'GET',
			data: {
				q: query,
			},
			success: function (data) {
				document.getElementById('resultados').innerHTML = data;
				seleccionar();
			},
		});
	} else {
		document.getElementById('resultados').innerHTML = '';
	}
}

function cargarUsuario(id) {
	$.ajax({
		url: '../../query/getInfoEstudiante.php',
		method: 'POST',
		data: {
			id: id,
		},
		dataType: 'json',
		success: function (data) {
			if (data['False']) {
				alert(data.error);
			} else {
				mostrarDatosParaEliminar(data);
				cerrarVentana('.close_eliminar');
			}
		},
		error: function (data) {
			mostrarDatosParaEliminar(data);
			cerrarVentana('.close_eliminar');
		},
	});
}


function eliminarFila(objeto) {
	let fila = objeto.closest("tr");
	id = fila.querySelectorAll("td")[0].innerText
	nombreArchivo = fila.querySelectorAll("td")[7].innerText
	eliminarSolicitud(id, nombreArchivo)
	fila.remove()
}


async function eliminarSolicitud(id, nombreArchivo) {
	$.ajax({
		url: '../../query/eliminarSolicitud.php',
		method: 'POST',
		data: {
			id: id,
			nombreArchivo: nombreArchivo
		},
		dataType: 'json',
		success: function (data) {
			if (data["error"] === "False") {
				mostrarTemplate("Ocurrio un error al eliminar la solicitud", '../../assets/iconos/ic_error.webp', 'var(--rojo)', "miTemplate");
			} else {
				mostrarTemplate("Se ha eliminado la solicitud", '../../assets/iconos/ic_correcto.webp', 'var(--verde)', "miTemplate");
			}
		},
		error: function (e) {
			mostrarTemplate("Ocurrio un error con la evidencia de la solicitud" + e.responseText, '../../assets/iconos/ic_error.webp', 'var(--rojo)', "miTemplate");
		},
	});
}


function mostrarTemplate(mensaje, urlImagen, color, nombre) {
	const template = document.getElementById(nombre)
	const clone = document.importNode(template.content, true)
	clone.getElementById('mensaje').innerText = mensaje
	clone.getElementById('imagen').src = urlImagen
	clone.getElementById('btn_mensaje').style.backgroundColor = color
	document.body.appendChild(clone)
}

function cerrarTemplate(opcion) {
	const dialogContainer = document.getElementById('overlay')
	const notificacionIMG = document.querySelector('.img_notificacion')
	if (dialogContainer) {
		notificacionIMG.remove()
		dialogContainer.remove()
		if (opcion == "cargar") {
			location.reload()
		}
	}
}

function rechazarSolicitud(objeto) {

	let fila = objeto.closest("tr");
	id = fila.querySelectorAll("td")[0].innerText
	nombreArchivo = fila.querySelectorAll("td")[7].innerText
	estado = fila.querySelectorAll("td")[8].className
	opciones = fila.querySelectorAll("td")[9].querySelector("div").querySelectorAll("button")

	if (verificarOpciones(estado)) {
		return
	}

	$.ajax({
		url: '../../query/rechazarSolicitud.php',
		method: 'POST',
		data: {
			id: id,
			nombreArchivo: nombreArchivo
		},
		dataType: 'json',
		success: function (data) {
			if (data["sin_error"] === "True") {
				mostrarTemplate("Se ha rechazado la solicitud", '../../assets/iconos/ic_correcto.webp', 'var(--verde)', 'miTemplate_cargar');

			} else {
				mostrarTemplate("Ocurrio un error al rechazar la solicitud", '../../assets/iconos/ic_error.webp', 'var(--rojo)', 'miTemplate');
			}
		},
		error: function (data) {
			mostrarTemplate("Ocurrio un error al rechazar la solicitud", '../../assets/iconos/ic_error.webp', 'var(--rojo)', 'miTemplate');
		}
	})
}

function aceptarSolicitud(objeto, id_jefe) {

	let fila = objeto.closest("tr");
	id = fila.querySelectorAll("td")[0].innerText
	matricula = fila.querySelectorAll("td")[1].innerText
	nombre = fila.querySelectorAll("td")[2].innerText
	apellidos = fila.querySelectorAll("td")[3].innerText
	grupo = fila.querySelectorAll("td")[4].innerText
	motivo = fila.querySelectorAll("td")[5].innerText
	fecha = fila.querySelectorAll("td")[6].innerText
	estado = fila.querySelectorAll("td")[8].className
	opciones = fila.querySelectorAll("td")[9].querySelector("div").querySelectorAll("button")

	if (verificarOpciones(estado)) {
		return
	}

	$.ajax({
		url: '../../utils/creacionJustificantes.php',
		method: 'POST',
		data: {
			id_jefe: id_jefe,
			id: id,
			matricula: matricula,
			nombre: nombre,
			apellidos: apellidos,
			grupo: grupo,
			motivo: motivo,
			fecha: fecha
		},
		dataType: 'json',
		success: function (data) {
			console.log(data)
			if (data["sin_error"] !== "True") {
				mostrarTemplate("Se ha creado y enviado el justificante", '../../assets/iconos/ic_correcto.webp', 'var(--verde)', 'miTemplate_cargar');

			} else {
				mostrarTemplate("Ocurrio un error al rechazar la solicitud", '../../assets/iconos/ic_error.webp', 'var(--rojo)', 'miTemplate');
			}
		},
		error: function (data) {
			mostrarTemplate("Ocurrio un error", '../../assets/iconos/ic_error.webp', 'var(--rojo)', 'miTemplate');
		}
	})
}


function verificarOpciones(estado) {
	if (estado == "aceptada") {
		mostrarTemplate("La solicitud ya ha sido aceptada", '../../assets/iconos/ic_error.webp', 'var(--rojo)', 'miTemplate');
		return true
	}

	if (estado == "rechazada") {
		mostrarTemplate("La solicitud ya ha sido rechazada", '../../assets/iconos/ic_error.webp', 'var(--rojo)', 'miTemplate');
		return true
	}
}