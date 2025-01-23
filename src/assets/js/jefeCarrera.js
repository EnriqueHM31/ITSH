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
