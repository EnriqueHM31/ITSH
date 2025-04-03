const modalidadSelect = document.getElementById('modalidad');
const grupoSelect = document.getElementById('grupo');

if (grupoSelect !== null) {
	function actualizarGrupos() {

		const modalidad = modalidadSelect.value;
		const id_grupos = grupoSelect.dataset.id_grupos;
		const numero_grupos = grupoSelect.dataset.numero_grupos;

		opciones = [];

		if (modalidad === 'Escolarizado') {
			for (let i = 1; i <= numero_grupos; i++) {
				opciones.push(i + '0' + id_grupos + 'A');
			}
		} else if (modalidad === 'Flexible') {
			for (let i = 1; i <= id_grupos; i++) {
				opciones.push(i + '0' + id_grupos + 'B');
			}
		}
		grupoSelect.innerHTML = '';
		opciones.forEach((opcion) => {
			const optionElement = document.createElement('option');
			optionElement.value = opcion;
			optionElement.textContent = opcion;
			grupoSelect.appendChild(optionElement);
		});
	}


	// Inicializar con la modalidad seleccionada por defecto
	modalidadSelect.addEventListener('change', actualizarGrupos);

	// Llamar a la función para cargar los grupos iniciales
}

const cargo = document.getElementById('rol');
const groupCarrera = document.getElementById('carrera');

if (cargo !== null) {
	// Función para actualizar los grupos según la modalidad
	function actualizarCargo() {
		// Obtener la modalidad seleccionada
		const cargoActivo = cargo.value;

		const opciones = [];

		if (cargoActivo === 'Administrador') {
			opciones.push('Null');
			ponerDatosOptions(opciones, groupCarrera);
		} else if (cargoActivo === 'Jefe de Carrera') {
			$.ajax({
				url: '../../query/obtenerCarreras.php',
				method: 'GET',
				data: {
					carreras: true,
				},
				dataType: 'json',
				success: function (data) {
					data.result.forEach((opcion) => {
						opciones.push(opcion['carrera'].trim());
					});
					ponerDatosOptions(opciones, groupCarrera);
				},
				error: function (xhr) {
					mostrarErrorAjax(xhr);
				},
			});
		}
	}

	function ponerDatosOptions(opciones, groupCarrera) {
		groupCarrera.innerHTML = '';
		opciones.forEach((opcion) => {
			const optionElement = document.createElement('option');
			if (opcion.trim() === 'Sistemas Computacionales') {
				optionElement.textContent = 'Sistemas'.trim();
			} else {
				optionElement.textContent = opcion.trim();
			}
			optionElement.value = opcion.trim();
			groupCarrera.appendChild(optionElement);
		});
	}

	// Inicializar con la modalidad seleccionada por defecto
	cargo.addEventListener('change', actualizarCargo);

	// Llamar a la función para cargar los grupos iniciales
	actualizarCargo();
}


function crearModalidad(modalidad, modalidadSelect) {
	// Crear la opción modalidad
	let modalidadOption = document.createElement('option');
	modalidadOption.value = modalidad;
	modalidadOption.textContent = modalidad;
	modalidadSelect.appendChild(modalidadOption);
}


function ponerModalidades() {

	const modalidades = document.getElementById('modalidad').dataset.modalidades;
	const modalidadSelect = document.getElementById('modalidad');

	if (modalidades == 2) {
		crearModalidad('Escolarizado', modalidadSelect);
		crearModalidad('Flexible', modalidadSelect);
	} if (modalidades == 1) {
		crearModalidad('Escolarizado', modalidadSelect);
	}
}

ponerModalidades();

if (document.querySelector("body").dataset.rol === 'Jefe de Carrera') {
	actualizarGrupos();
}