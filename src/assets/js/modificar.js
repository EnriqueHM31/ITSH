
async function cargarUsuarioModificar(id, nombre) {
    if (id == null) {
        mostrarTemplate(
            'Busca y seleccione a un usuario',
            '../../assets/iconos/ic_error.webp',
            'var(--rojo)',
            'miTemplate',
        );
        return; // Terminar la ejecución si no hay ID
    }

    $.ajax({
        url: `../../query/${nombre}`,
        method: 'POST',
        data: {
            id: id,
        },
        dataType: 'json',
        success: function (data) {
            resultadosBusqueda = document.getElementById('resultados')
            inputBuscar = document.getElementById('buscar')

            nombreUsuario = document.getElementById('nombre');
            apellidosUsuario = document.getElementById('apellidos');
            correoUsuario = document.getElementById('correo');

            clave = document.getElementById('clave');
            claveAnterior = document.getElementById('clave_anterior');
            rolAntiguo = document.getElementById('rol_antiguo');
            carreraAntigua = document.getElementById('carrera_antigua');


            modalidad = document.getElementById('modalidad')
            grupo = document.getElementById('grupo');
            carreras = document.getElementById('carrera');
            rol = document.getElementById('rol');

            nombreUsuario.value = data.nombre;
            apellidosUsuario.value = data.apellidos;
            correoUsuario.value = data.correo;


            if (nombre === 'getInfoPersonal.php') {
                clave.value = data.clave_empleado;
                claveAnterior.value = data.clave_empleado;
                rol.value = data.rol;
                rolAntiguo.value = data.rol;


                if (data.rol === 'Administrador') {
                    actualizarCargo();

                } else if (data.rol === 'Jefe de Carrera') {
                    actualizarCargo();
                    // Esperar a que las opciones estén completamente cargadas
                    setTimeout(() => {
                        const carreras = document.getElementById('carrera');
                        carreras.value = data.carrera;
                        carreraAntigua.value = data.carrera;

                        if (!carreras.value) {
                            for (let i = 0; i < carreras.options.length; i++) {
                                if (carreras.options[i].value === data.carrera) {
                                    carreras.selectedIndex = i;
                                    break;
                                }
                            }
                        }
                    }, 500);
                }
            }

            if (nombre === 'getInfoEstudiante.php') {
                clave.value = data.matricula;
                claveAnterior.value = data.matricula;
                modalidad.value = data.id_modalidad;
                actualizarGrupos();
                grupo.value = data.grupo;
            }

            // Limpiar resultados anteriores y el campo de búsqueda
            resultadosBusqueda.innerHTML = '';
            inputBuscar.value = '';
        },
        error: function (xhr, status, error) {
            mostrarErrorAjax(xhr);
        }
    });
}
