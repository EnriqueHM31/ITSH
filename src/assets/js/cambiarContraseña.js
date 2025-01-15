const btn = document.getElementById('button')
var emailjs



document.getElementById('form')
    .addEventListener('submit', function (event) {

        event.preventDefault()

        const matricula = document.getElementById('Matricula').value.trim();
        const correo = document.getElementById('user_email').value.trim();

        // Verificar si algún campo está vacío
        if (matricula === "" || correo === "") {
            mostrarTemplate(
                "Llena los datos del formulario",
                "../../assets/iconos/ic_error.webp",
                "var(--rojo)"
            )
            return
        }

        btn.value = 'Enviando...';

        let nuevaContraseña
        nuevaContraseña = contraseñaCreada()
        const Inputcontraseña = crearContraseñaInput(nuevaContraseña)

        this.appendChild(Inputcontraseña);

        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                matricula: matricula,
                correo: correo,
                nuevaContraseña: nuevaContraseña
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.valido) {
                    /*const templateParams = {
                        user_email: correo,
                        Matricula: matricula,
                        password: nuevaContraseña,
                    };*/

                    const serviceID = 'default_service';
                    const templateID = 'template_d151xen';

                    emailjs.sendForm(serviceID, templateID, this)
                        .then(() => {
                            btn.value = 'Enviar';
                            mostrarTemplate(
                                "Tu contraseña fue enviada a tu correo",
                                "../../assets/iconos/ic_correcto.webp",
                                "var(--verde)"
                            )
                            this.querySelector('.password').remove();
                            this.reset();

                        }, () => {
                            btn.value = 'Enviar';
                            mostrarTemplate(
                                "Ocurrio un error al enviarte tu contraseña",
                                "../../assets/iconos/ic_error.webp",
                                "var(--rojo)"
                            )
                            this.querySelector('.password').remove();
                            this.reset();
                        });
                } else {
                    btn.value = 'Enviar';
                    mostrarTemplate(
                        "Datos incorrectos",
                        "../../assets/iconos/ic_error.webp",
                        "var(--rojo)"
                    )
                    this.querySelector('.password').remove();
                    this.reset();
                }
            })
            .catch(error => {
                mostrarTemplate(
                    `Error: ${error}`,
                    "../../assets/iconos/ic_error.webp",
                    "var(--rojo)"
                )
            });

    });

function generarContraseña() {
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let contraseña = '';
    for (let i = 0; i < 8; i++) {
        const randomIndex = Math.floor(Math.random() * caracteres.length);
        contraseña += caracteres[randomIndex];
    }
    return contraseña;
}

function mostrarTemplate(mensaje, urlImagen, color) {
    const template = document.getElementById('miTemplate');
    const clone = document.importNode(template.content, true);

    clone.getElementById('mensaje').innerText = mensaje;
    clone.getElementById('imagen').src = urlImagen;
    clone.getElementById('btn_mensaje').style.backgroundColor = color;

    document.body.appendChild(clone);
}

function cerrarTemplate() {
    const dialogContainer = document.getElementById('overlay');
    const notificacionIMG = document.querySelector(".img_notificacion")
    if (dialogContainer) {
        notificacionIMG.remove()
        dialogContainer.remove();
    }
}

function crearContraseñaInput(nuevaContraseña) {

    const contraseñaInput = document.createElement('input');
    contraseñaInput.classList.add("password")
    contraseñaInput.type = 'hidden';
    contraseñaInput.name = 'password';
    contraseñaInput.value = nuevaContraseña;
    return contraseñaInput
}

function contraseñaCreada() {
    let nuevaContraseña = ""
    nuevaContraseña = generarContraseña();
    return nuevaContraseña
}