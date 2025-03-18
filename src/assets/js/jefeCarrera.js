
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
				mostrarTemplate("Ocurrio un error al eliminar la solicitud", '../../assets/iconos/ic_error.webp', 'var(--rojo)');
			} else {
				mostrarTemplate("Se ha eliminado la solicitud", '../../assets/iconos/ic_correcto.webp', 'var(--verde)');
			}
		},
		error: function (e) {
			mostrarTemplate("Ocurrio un error con la evidencia de la solicitud" + e.responseText, '../../assets/iconos/ic_error.webp', 'var(--rojo)');
		},
	});
}


function mostrarTemplate(mensaje, urlImagen, color) {


	const template = document.getElementById('miTemplate')
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

	console.log(estado)
	if (estado == "aceptada") {
		mostrarTemplate("La solicitud ya ha sido aceptada", '../../assets/iconos/ic_error.webp', 'var(--rojo)');
		return
	}

	if (estado == "rechazada") {
		mostrarTemplate("La solicitud ya ha sido rechazada", '../../assets/iconos/ic_error.webp', 'var(--rojo)');
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
				mostrarTemplate("Se ha rechazado la solicitud", '../../assets/iconos/ic_correcto.webp', 'var(--verde)');
				console.log(opciones)
				opciones.forEach(element => {
					element.remove()
				});

			} else {
				console.log("ocurrio un error")
				console.log(data["sin_error"])
				mostrarTemplate("Ocurrio un error al rechazar la solicitud", '../../assets/iconos/ic_error.webp', 'var(--rojo)');
			}
		},
		error: function (data) {
			mostrarTemplate("Ocurrio un error al rechazar la solicitud", '../../assets/iconos/ic_error.webp', 'var(--rojo)');
		}
	})
}