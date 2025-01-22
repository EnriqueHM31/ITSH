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
			if (data.error) {
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

function mostrarDatosParaEliminar(data) {
	console.log(data);
	if (data.identificador === undefined) {
		const modalTemplate = document.getElementById(
			'plantilla_usuario-seleccionado-error',
		);
		const modalContainer = document.querySelector('body');
		const modalClone = modalTemplate.content.cloneNode(true);

		modalClone.getElementById('detalles_eliminar_error').innerText =
			'Se necesita que busque un registro primero';

		modalContainer.appendChild(modalClone);
	} else {
		const modalTemplate = document.getElementById(
			'plantilla_usuario-seleccionado',
		);
		const modalContainer = document.querySelector('body');
		const modalClone = modalTemplate.content.cloneNode(true);

		modalClone.getElementById('clave_info').innerText = data.identificador;
		modalClone.getElementById('nombre_info').innerText = data.nombre;
		modalClone.getElementById('apellidos_info').innerText = data.apellidos;
		modalClone.getElementById('carrera_info').innerText = data.carrera;
		modalClone.getElementById('cargo_info').innerText = data.cargo;
		modalClone.getElementById('identificador').value = data.identificador;

		modalContainer.appendChild(modalClone);
	}
}
