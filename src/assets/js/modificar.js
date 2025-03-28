async function cargarUsuarioModificar(id, nombre) {
    if (id == null) {
        mostrarTemplate(
            'Busca y seleccione a un usuario',
            '../../assets/iconos/ic_error.webp',
            'var(--rojo)',
            'miTemplate'
        )
    }
    $.ajax({
        url: `../../query/${nombre}`,
        method: 'POST',
        data: {
            id: id
        },
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                console.log(data.error)
            } else {
                document.getElementById('nombre').value = data.nombre;
                document.getElementById('apellidos').value = data.apellidos;
                document.getElementById('correo').value = data.correo;

                if (nombre === 'getInfoPersonal.php') {

                    document.getElementById('clave').value = data.clave_empleado;
                    document.getElementById('clave_anterior').value = data.clave_empleado;
                    document.getElementById('rol').value = data.rol;

                    if (data.rol === "Administrador") {
                        actualizarCargo();
                    } else {
                        actualizarCargo();
                        document.getElementById('carrera').value = data.carrera;
                    }

                }
                if (nombre === 'getInfoEstudiante.php') {
                    document.getElementById('clave').value = data.matricula;
                    document.getElementById('clave_anterior').value = data.matricula;
                    document.getElementById('modalidad').value = data.id_modalidad;
                    actualizarGrupos();
                    document.getElementById('grupo').value = data.grupo;

                }

                document.getElementById("resultados").innerHTML = ""
                document.getElementById("buscar").value = ""
            }
        },
        error: function () {
            new throwError("Fallo con los datos")
        }
    });

}
