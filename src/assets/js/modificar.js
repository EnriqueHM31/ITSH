async function buscarUsuarios() {
    var query = document.getElementById('buscar').value;

    if (query.length >= 3) {
        $.ajax({
            url: '../../query/buscarPersonal.php',
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
                document.getElementById('clave').value = data.identificador;
                document.getElementById('nombre').value = data.nombre;
                document.getElementById('apellidos').value = data.apellidos;
                document.getElementById('carrera').value = data.carrera;
                document.getElementById('rol').value = data.cargo;
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