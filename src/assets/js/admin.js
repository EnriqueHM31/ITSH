const nombre = document.getElementById('nombre');
const apellidos = document.getElementById('apellidos');
const correo = document.getElementById('correo');

// Función para actualizar el correo automáticamente
function actualizarCorreo() {
    const nombreValor = nombre.value.trim().toLowerCase();
    const apellidoValor = apellidos.value.trim().toLowerCase()[0];

    if (nombreValor || apellidoValor) {
        const inicialApellido = apellidoValor.charAt(0);
        correo.value = `${nombreValor}${inicialApellido}@huatusco.tecnm.mx`;
    } else {
        correo.value = "@huatusco.tecnm.mx";
    }
}

// Escuchar cuando el usuario escribe
nombre.addEventListener('input', actualizarCorreo);
apellidos.addEventListener('input', actualizarCorreo);

// Al cargar la página puedes poner un valor base
window.addEventListener('DOMContentLoaded', () => {
    correo.value = "@huatusco.tecnm.mx";
});
