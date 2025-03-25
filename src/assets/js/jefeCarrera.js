
function buscarUsuarios(carrera) {
	var query = document.getElementById('buscar').value;
	if (query.length >= 0) {
		$.ajax({
			url: '../../query/buscarEstudiante.php',
			method: 'GET',
			data: {
				q: query,
				carrera: carrera
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
				console.log(data)
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
		error: function (xhr) {
			console.log(xhr);

			let mensajeError = "Error desconocido";

			try {
				// Intentar convertir el responseText en JSON
				let jsonStart = xhr.responseText.indexOf("{");
				if (jsonStart !== -1) {
					let jsonString = xhr.responseText.substring(jsonStart);
					let jsonData = JSON.parse(jsonString);
					mensajeError = jsonData["sin_error"] || "Error en el servidor";
				} else {
					mensajeError = xhr.responseText; // En caso de que no sea JSON válido
				}
			} catch (e) {
				console.error("Error al procesar la respuesta del servidor:", e);
			}

			mostrarTemplate(mensajeError, '../../assets/iconos/ic_error.webp', 'var(--rojo)', 'miTemplate');
		}
	})
}

function aceptarSolicitud(objeto) {

	let id_jefe = objeto.getAttribute("data-id")

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
			id_solicitud: id,
			matricula: matricula,
			nombre: nombre,
			apellidos: apellidos,
			grupo: grupo,
			motivo: motivo,
			fecha: fecha
		},
		dataType: 'json',
		success: function (data) {
			console.log(data);
			if (data["sin_error"] === true) {
				mostrarTemplate("Se ha creado y enviado el justificante", '../../assets/iconos/ic_correcto.webp', 'var(--verde)', 'miTemplate_cargar');
			} else {
				mostrarTemplate(data["sin_error"], '../../assets/iconos/ic_error.webp', 'var(--rojo)', 'miTemplate');
			}
		},
		error: function (xhr) {
			console.log(xhr);

			let mensajeError = "Error desconocido";

			try {
				// Intentar convertir el responseText en JSON
				let jsonStart = xhr.responseText.indexOf("{");
				if (jsonStart !== -1) {
					let jsonString = xhr.responseText.substring(jsonStart);
					let jsonData = JSON.parse(jsonString);
					mensajeError = jsonData["sin_error"] || "Error en el servidor";
				} else {
					mensajeError = xhr.responseText; // En caso de que no sea JSON válido
				}
			} catch (e) {
				console.error("Error al procesar la respuesta del servidor:", e);
			}

			mostrarTemplate(mensajeError, '../../assets/iconos/ic_error.webp', 'var(--rojo)', 'miTemplate');
		}
	});

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


//BUSCAR JUSTIFICANTES EN EL HISTORIAL
$(document).ready(function () {
	let timer;

	$("#search-justificantes").on("keyup", function () {
		clearTimeout(timer); // Limpia el temporizador anterior

		let query = $(this).val().trim();


		timer = setTimeout(function () {
			$.ajax({
				url: "../../query/buscarJustificante.php",
				method: "GET",
				data: { q: query },
				success: function (response) {
					$("#historial").html(response);
				}
			});
		}, 300);
	});
});


function reiniciarFolio() {
	$.ajax({
		url: '../../query/justificantes.php',
		method: 'POST',
		data: {
			reiniciar: "Si"
		},
		dataType: 'json',
		success: function (data) {
			mostrarTemplate(data.mensaje, '../../assets/iconos/ic_correcto.webp', 'var(--verde)', "miTemplate_cargar", "miTemplate_cargar");
		},
		error: function (data) {
			mostrarTemplate(data.mensaje, '../../assets/iconos/ic_correcto.webp', 'var(--verde)', "miTemplate_cargar", "miTemplate_cargar");
		}
	})
}

function mostrarModal() {
	const template = document.getElementById("modal_seguridad")
	const clone = document.importNode(template.content, true)

	document.body.appendChild(clone)
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
	if (dialogContainer) {
		dialogContainer.remove()
		if (opcion == "cargar") {
			location.reload()
		}
	}
}

