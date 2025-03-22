async function buscarUsuarios(nombre, carrera) {
    var query = document.getElementById('buscar').value;

    if (query.length >= 3) {
        $.ajax({
            url: `../../query/${nombre}`,
            method: 'GET',
            data: {
                q: query,
                carrera: carrera
            },
            success: function (data) {
                document.getElementById('resultados').innerHTML = data;
                if (nombre === 'buscarPersonal.php') {
                    mostrar("getInfoPersonal.php")
                }
                else if (nombre === 'buscarEstudiante.php') {
                    mostrar("getInfoEstudiante.php")
                }
            } // Elimina la coma aquí
        });
    } else {
        document.getElementById('resultados').innerHTML = '';
    }
}


function mostrar(tableData) {
    document.querySelectorAll('.result').forEach(element => {
        element.addEventListener("click", () => {
            const id = element.querySelector("p").dataset.id;
            cargarUsuario(id, tableData);

        });
    })
}

async function cargarUsuario(id, nombre) {
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
                    if (data.rol == "Administrador") {
                        document.getElementById('carrera').value = "null";
                    } else {
                        document.getElementById('carrera').value = data.carrera;

                    }
                    document.getElementById('rol').value = data.rol;
                }
                if (nombre === 'getInfoEstudiante.php') {
                    document.getElementById('clave').value = data.matricula;
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

buscarUsuarios();