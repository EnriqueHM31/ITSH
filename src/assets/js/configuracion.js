const btnAgregar = document.getElementById('agregar_carrera');
const plantillaCarrera = document.getElementById('plantilla_agregar_carrera');

if (btnAgregar !== null) {
	btnAgregar.addEventListener('click', () => {
		const content = plantillaCarrera.content.cloneNode(true);
		document.querySelector('body').appendChild(content);
		cerrarVentana('.close');
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


const btnEliminarCarrera = document.querySelectorAll('.eliminar_carrera');

if (btnEliminarCarrera !== null) {
	btnEliminarCarrera.forEach(element => {
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
                    if (data['sin_error'] === true) {
                        mostrarTemplate(
                            'Se ha eliminado la carrera',
                            '../../assets/iconos/ic_correcto.webp',
                            'var(--verde)',
                            'miTemplate_cargar',
                        );
                    } else {
                        mostrarTemplate(
                            data['sin_error'],
                            '../../assets/iconos/ic_error.webp',
                            'var(--rojo)',
                            'miTemplate',
                        );
                    }
                },
                error: function (xhr) {
                    let mensajeError = 'Error desconocido';
        
                    try {
                        let jsonStart = xhr.responseText.indexOf('{');
                        if (jsonStart !== -1) {
                            let jsonString = xhr.responseText.substring(jsonStart);
                            let jsonData = JSON.parse(jsonString);
                            mensajeError =
                                jsonData['sin_error'] || 'Error en el servidor';
                        } else {
                            mensajeError = xhr.responseText;
                        }
                    } catch (e) {
                        mostrarTemplate(
                            "Error al procesar la respuesta del servidor: " + e,
                            '../../assets/iconos/ic_error.webp',
                            'var(--rojo)',
                            'miTemplate',
                        );
                        return
                    }
        
                    mostrarTemplate(
                        mensajeError,
                        '../../assets/iconos/ic_error.webp',
                        'var(--rojo)',
                        'miTemplate',
                    );
                },
            });
		});
	});
}
