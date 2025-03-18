async function buscarUsuarios(nombre) {
    var query = document.getElementById('buscar').value;

    if (query.length >= 3) {
        $.ajax({
            url: `../../query/${nombre}`,
            method: 'GET',
            data: {
                q: query
            },
            success: function (data) {
                document.getElementById('resultados').innerHTML = data;
                mostrar()
            }
        });
    } else {
        document.getElementById('resultados').innerHTML = '';
    }
}

function mostrar() {
    document.querySelectorAll('.result').forEach(element => {
        element.addEventListener("click", () => {
            const id = element.querySelector("p").dataset.id;
            cargarUsuario(id);

        });
    })
}

async function cargarUsuario(id) {
    $.ajax({
        url: '../../query/getInfoPersonal.php',
        method: 'POST',
        data: {
            id: id
        },
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                new throwError("Datos invalidos")
            } else {
                document.getElementById('clave').value = data.clave_empleado;
                document.getElementById('nombre').value = data.nombre;
                document.getElementById('apellidos').value = data.apellidos;
                if (data.rol == "Administrador") {
                    document.getElementById('carrera').value = "null";
                } else {
                    document.getElementById('carrera').value = data.carrera;

                }
                document.getElementById('rol').value = data.rol;
                document.getElementById('correo').value = data.correo;

                document.getElementById("buscar").value = ""
                document.getElementById("resultados").innerHTML = ""
            }
        },
        error: function () {
            new throwError("Fallo con los datos")
        }
    });

}

buscarUsuarios();