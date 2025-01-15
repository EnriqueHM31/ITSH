var archivoInput = document.getElementById("archivo");


archivoInput.addEventListener("change", () => {
    var archivoNombre = archivoInput.files[0] ? archivoInput.files[0].name : "Ningún archivo seleccionado";

    document.getElementById("nombreArchivo").textContent = archivoNombre
})
