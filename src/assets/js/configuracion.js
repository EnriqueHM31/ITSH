const botonesOpciones = document.querySelectorAll('#agregar_carrera');
const plantillaCarrera = document.getElementById('plantilla_agregar_carrera');
const plantillaGrupo = document.getElementById('plantilla_configurar_carrera');


if (botonesOpciones !== null) {
	botonesOpciones.forEach((btnOpcion) => {
		btnOpcion.addEventListener('click', () => {
			const accion = btnOpcion.dataset.accion;
			const id = btnOpcion.dataset.id;
			content = null;
			if (plantillaCarrera !== null && accion === 'agregar_carrera') {
				content = plantillaCarrera.content.cloneNode(true);
			} else {
				content = plantillaGrupo.content.cloneNode(true);
				obtenerDatosCarrera(id);
			}
			document.querySelector('body').appendChild(content);
			cerrarVentana('.close');
		});
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

const btnEliminarCarrera = document.querySelectorAll('.boton_eliminar');
if (btnEliminarCarrera !== null) {
	btnEliminarCarrera.forEach((element) => {
		element.addEventListener('click', () => {
			const id = element.dataset.id;

			$.ajax({
				url: '../../utils/configuracion.php',
				method: 'POST',
				data: {
					id_carrera_nueva: id,
				},
				dataType: 'json',
				success: function (data) {
					if (data['success'] === true) {
						mostrarTemplate(
							'Se ha eliminado la carrera',
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
		});
	});
}

function obtenerDatosCarrera(id) {
	$.ajax({
		url: '../../utils/configuracion.php',
		method: 'POST',
		data: {
			obtener_datos_carrera: id,
		},
		dataType: 'json',
		success: function (data) {
			numeroGrupos = data['success'][0];
			idGrupo = data['success'][1];
			tipoCarrera = data['success'][2];
			modalidades = data['success'][3];

			document.querySelector('#carrera_antigua').value = id;
			document.querySelector('#clave_grupo_antigua').value = idGrupo;

			document.querySelector('#carrera_modificar').value = id;

			document.querySelector('#cantidad_grupos').value = numeroGrupos;
			document.querySelector('#id_grupo').value = idGrupo;
			document.querySelector('#tipo_carrera').value = tipoCarrera

			if (modalidades.includes('Escolarizado')) {
				document.querySelector('#modalidad_escolarizado').checked = true
			}

			if (modalidades.includes('Flexible')) {
				document.querySelector('#modalidad_flexible').checked = true
			}

		},
		error: function (xhr) {
			mostrarErrorAjax(xhr);
		},
	});
}




const btnCrearPDFUsuarios = document.querySelector('.btn_listar_usuarios');

btnCrearPDFUsuarios !== null ?
	btnCrearPDFUsuarios.addEventListener('click', () => {
		$.ajax({
			url: '../../utils/ListarUsuarios.php',
			method: 'GET',
			dataType: 'html',
			success: function (data) {
				const element = document.createElement('a')
				element.href = '../../utils/ListarUsuarios.php'
				element.download = 'usuarios_en_sistema.pdf'
				element.click();
			},
			error: function (xhr) {
				mostrarErrorAjax(xhr);
			},
		});
	}) : null;