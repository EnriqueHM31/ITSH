<?php

class Usuario
{
    public const ADMIN = Variables::MENU_DE_ROLES[0];
    public const JEFE_DE_CARRERA = Variables::MENU_DE_ROLES[1];
    public const ESTUDIANTE = Variables::MENU_DE_ROLES[2];

    public function __usuario()
    {
    }

    public function Verificacion($conexion, $id, $contraseña)
    {
        global $CAMPO_CONTRASEÑA, $CAMPO_ID_ROL, $CAMPO_ID_USUARIO, $CAMPO_CORREO;
        if (empty($id) || empty($contraseña)) {
            estructuraMensaje("Llena todos los campos", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $resultUsuario = obtenerDataUsuarioPorIDBD($conexion, $id);

        if ($resultUsuario->num_rows < 1) {
            estructuraMensaje("Usuario Invalido", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $usuario = $resultUsuario->fetch_assoc();
        $contraseñaBD = $usuario[$CAMPO_CONTRASEÑA];
        $rolBD = $usuario[$CAMPO_ID_ROL];

        if ($contraseñaBD !== $contraseña) {
            estructuraMensaje("Contraseña Incorrecta", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }


        $rol = obtenerRol($conexion, $usuario[$CAMPO_ID_ROL]);

        if ($rol === Usuario::ADMIN) {
            $this->asignarDatosInicioSesion($usuario, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $rol, "Admin/Admin.php");
        }
        if ($rol === Usuario::JEFE_DE_CARRERA) {
            $this->asignarDatosInicioSesion($usuario, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $rol, "JefedeCarrera/JefeCarrera.php");
        }
        if ($rol === Usuario::ESTUDIANTE) {
            $this->asignarDatosInicioSesion($usuario, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $rol, "Alumno/alumno.php");
        } else {
            estructuraMensaje("Ocurrio un error con la pagina", "./src/assets/iconos/ic_error.webp", "--rojo");
        }
    }

    public function asignarDatosInicioSesion($usuario, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $rol, $redireccion)
    {
        session_start();
        $_SESSION["id"] = $usuario[$CAMPO_ID_USUARIO];
        $_SESSION["correo"] = $usuario[$CAMPO_CORREO];
        $_SESSION["rol"] = $rol;
        header("location: src/layouts/$redireccion");

    }

    public function cambiarContraseña($conexion, $contraseña_actual, $contraseña_nueva, $id)
    {

        if (empty($contraseña_nueva) || empty($contraseña_actual)) {
            estructuraMensaje("Llena todos los campos", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (strlen($contraseña_nueva) < 8) {
            estructuraMensaje("Contraseña debe ser 8 caracteres", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $contraseña = obtenerContraseñaActualBD($conexion, $id);

        if ($contraseña_actual !== $contraseña) {
            estructuraMensaje("La contraseña actual no es la correcta", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (modificarLaContraseñaActualPaginaInicio($conexion, $id, $contraseña_nueva)) {
            estructuraMensaje("Se cambio la contraseña", "../../assets/iconos/ic_correcto.webp", "--verde");
        } else {
            estructuraMensaje("Error al actualizar la contraseña", "../../assets/iconos/ic_error.webp", "--rojo");
        }

        $conexion->close();
    }

    public function verificarIdentidadCorreoIdentificador($id_usuario, $correoDB, $conexion)
    {

        $sql = "SELECT * FROM " . Variables::TABLA_BD_USUARIO .
            " WHERE " . Variables::CAMPO_CORREO . " = '$correoDB' AND " .
            Variables::CAMPO_ID_USUARIO . " = '" . $id_usuario . "'";

        return mysqli_query($conexion, $sql);
    }

    public function cambiarContraseñaEnBD($conexion, $id_usuario, $nuevaContraseña)
    {
        $sql = "UPDATE " . Variables::TABLA_BD_USUARIO . " SET "
            . Variables::CAMPO_CONTRASEÑA . " = '$nuevaContraseña' WHERE " .
            Variables::CAMPO_ID_USUARIO . " = '" . $id_usuario . "'";

        return mysqli_query($conexion, $sql);
    }

    public function escribirDatosDelUsuario($conexion, $id, $rol, $correo)
    {
        if ($rol === Usuario::ADMIN) {
            $usuario = getResultDataTabla($conexion, Variables::TABLA_BD_AdMINISTRADOR, Variables::CAMPO_CLAVE_EMPLEADO_ADMIN, $id);

            echo "<div class='contenedor-datos'>";
            echo "<p><strong>Clave:</strong> " . $usuario[Variables::CAMPO_CLAVE_EMPLEADO_ADMIN] . "</p>";
            echo "<p><strong>Nombre:</strong> " . $usuario[Variables::CAMPO_NOMBRE] . "</p>";
            echo "<p><strong>Apellidos:</strong> " . $usuario[Variables::CAMPO_APELLIDOS] . "</p>";
            echo "<p><strong>Correo:</strong> " . $correo . "</p>";
            echo "<button id='btn_contraseña' class='btn_vino'>Cambiar contraseña</button>";
            echo "</div>";
            return;
        }

        if ($rol === Usuario::JEFE_DE_CARRERA) {

            $usuario = getResultDataTabla($conexion, Variables::TABLA_BD_JEFE, Variables::CAMPO_CLAVE_EMPLEADO_JEFE, $id);

            $carrera = getResultCarrera($conexion, $usuario[Variables::CAMPO_ID_CARRERA]);

            echo "<div class='contenedor-datos'>";
            echo "<p><strong>Identificador:</strong> " . $usuario[Variables::CAMPO_CLAVE_EMPLEADO_JEFE] . "</p>";
            echo "<p><strong>Nombre:</strong> " . $usuario[Variables::CAMPO_NOMBRE] . "</p>";
            echo "<p><strong>Apellidos:</strong> " . $usuario[Variables::CAMPO_APELLIDOS] . "</p>";
            echo "<p><strong>Carrera:</strong> " . $carrera . "</p>";
            echo "<p><strong>Correo:</strong> " . $correo . "</p>";
            echo "<button id='btn_contraseña' class='btn_vino'>Cambiar contraseña</button>";
            echo "</div>";

            return;
        }

        if ($rol === Usuario::ESTUDIANTE) {
            $usuario = getResultDataTabla($conexion, Variables::TABLA_BD_ESTUDIANTE, Variables::CAMPO_MATRICULA, $id);
            $carrera = getResultCarrera($conexion, $usuario[Variables::CAMPO_ID_CARRERA]);


            echo "<div class='contenedor-datos'>";
            echo "<p><strong>Identificador:</strong> " . $usuario[Variables::CAMPO_MATRICULA] . "</p>";
            echo "<p><strong>Nombre:</strong> " . $usuario[Variables::CAMPO_NOMBRE] . "</p>";
            echo "<p><strong>Apellidos:</strong> " . $usuario[Variables::CAMPO_APELLIDOS] . "</p>";
            echo "<p><strong>Carrera:</strong> " . $carrera . "</p>";
            echo "<p><strong>Grupo:</strong> " . $usuario[Variables::CAMPO_GRUPO] . "</p>";
            echo "<p><strong>Correo:</strong> " . $correo . "</p>";
            echo "<button id='btn_contraseña' class='btn_vino'>Cambiar contraseña</button>";
            echo "</div>";
        }
    }
}
