const modalidadSelect = document.getElementById('modalidad');
const grupoSelect = document.getElementById('grupo');

if (grupoSelect !== null) {
	// Función para actualizar los grupos según la modalidad
	function actualizarGrupos() {
		// Limpiar el select de grupos
		grupoSelect.innerHTML = '';

		// Obtener la modalidad seleccionada
		const modalidad = modalidadSelect.value;

		let carrera = grupoSelect.dataset.carrera;

		const carreras = [
			{ 'Gestion Empresarial': 1 },
			{ Contaduria: 2 },
			{ Industrial: 3 },
			{ 'Sistemas Computacionales': 4 },
			{ Electromecanica: 5 },
			{ Alimentarias: 6 },
			{ Quimica: 7 },
			{ Ambiental: 8 },
		];

		const carreraEncontrada = carreras.find(
			(obj) => obj[carrera] !== undefined,
		);
		const numeroGrupo = carreraEncontrada
			? carreraEncontrada[carrera]
			: '-';

		// Según la modalidad, definir los grupos
		opciones = [];
		if (modalidad === 'Escolarizado') {
			for (let i = 1; i <= 9; i++) {
				opciones.push(i + '0' + numeroGrupo + 'A');
			}
		} else if (modalidad === 'Flexible') {
			for (let i = 1; i <= 9; i++) {
				opciones.push(i + '0' + numeroGrupo + 'B');
			}
		}

		// Llenar el select de grupos con las opciones correspondientes
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
	actualizarGrupos();
}

const cargo = document.getElementById('rol');
const groupCarrera = document.getElementById('carrera');

if (cargo !== null) {
	// Función para actualizar los grupos según la modalidad
	function actualizarCargo() {
		// Limpiar el select de grupos
		groupCarrera.innerHTML = '';

		// Obtener la modalidad seleccionada
		const cargoActivo = cargo.value;

		const opciones = [];

		if (cargoActivo === 'Administrador') {
			opciones.push('Null');
			ponerDatosOptions(opciones);
			return;
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
						opciones.push(opcion['carrera']);
					});
					ponerDatosOptions(opciones);
				},
				error: function (xhr) {
					mostrarTemplate(
						'Error al obtener las carreras',
						'../../assets/iconos/ic_error.webp',
						'var(--rojo)',
						'miTemplate',
					);
				},
			});
		}
	}

	function ponerDatosOptions(opciones) {
		opciones.forEach((opcion) => {
			const optionElement = document.createElement('option');
			if (opcion.trim() === 'Sistemas Computacionales') {
				optionElement.textContent = 'Sistemas';
			} else {
                optionElement.textContent = opcion;
            }
            optionElement.value = opcion;
			groupCarrera.appendChild(optionElement);
		});
	}

	// Inicializar con la modalidad seleccionada por defecto
	cargo.addEventListener('change', actualizarCargo);

	// Llamar a la función para cargar los grupos iniciales
	actualizarCargo();
}
