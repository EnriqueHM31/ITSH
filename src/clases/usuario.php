<?php

class Usuario
{

    public function __usuario()
    {
    }

    public function Verificacion($conexion, $id, $contraseña)
    {
        global $TABLA_USUARIO, $CAMPO_CONTRASEÑA, $CAMPO_ID_ROL, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $ADMIN, $JEFE, $ESTUDIANTE;
        if (empty($id) || empty($contraseña)) {
            estructuraMensaje("Llena todos los campos", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $resultUsuario = getResultDataTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);


        if (!$resultUsuario) {
            estructuraMensaje("Usuario Invalido", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $contraseñaBD = $resultUsuario[$CAMPO_CONTRASEÑA];
        $rolBD = $resultUsuario[$CAMPO_ID_ROL];

        if ($contraseñaBD !== $contraseña) {
            estructuraMensaje("Contraseña Incorrecta", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }


        $rol = obtenerRol($conexion, $rolBD);

        if ($rol === $ADMIN) {
            $this->asignarDatosInicioSesion($resultUsuario, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $rol, "Admin/Admin.php");
        }
        if ($rol === $JEFE) {
            $this->asignarDatosInicioSesion($resultUsuario, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $rol, "JefedeCarrera/JefeCarrera.php");
        }
        if ($rol === $ESTUDIANTE) {
            $this->asignarDatosInicioSesion($resultUsuario, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $rol, "Alumno/alumno.php");
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
        global $TABLA_USUARIO, $CAMPO_CORREO, $CAMPO_ID_USUARIO;
        $sql = "SELECT * FROM $TABLA_USUARIO WHERE $CAMPO_CORREO = ? AND $CAMPO_ID_USUARIO = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ss', $correoDB, $id_usuario);

        return $stmt->execute();
    }

    public function cambiarContraseñaEnBD($conexion, $id_usuario, $nuevaContraseña)
    {
        global $TABLA_USUARIO, $CAMPO_CONTRASEÑA, $CAMPO_ID_USUARIO;
        $sql = "UPDATE $TABLA_USUARIO SET $CAMPO_CONTRASEÑA = ? WHERE $CAMPO_ID_USUARIO= ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ss', $nuevaContraseña, $id_usuario);

        return $stmt->execute();

    }

    public function escribirDatosDelUsuario($conexion, $id, $rol, $correo)
    {
        global $ADMIN, $JEFE, $ESTUDIANTE, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $TABLA_JEFE, $CAMPO_CLAVE_EMPLEADO_JEFE, $TABLA_ESTUDIANTE, $CAMPO_GRUPO, $CAMPO_ID_CARRERA;

        if ($rol === $ADMIN) {
            $usuario = getResultDataTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);
            componenteDatosUsuarioInicioAdministrador($usuario, $correo);

            return;
        }

        if ($rol === $JEFE) {

            $usuario = getResultDataTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);
            $jefe = getResultDataTabla($conexion, $TABLA_JEFE, $CAMPO_ID_USUARIO, $id);
            $carrera = getResultCarrera($conexion, $jefe[$CAMPO_ID_CARRERA]);
            componenteDatosUsuarioInicioJefeCarrera($usuario, $carrera, $correo);

            return;
        }

        if ($rol === $ESTUDIANTE) {
            $usuario = getResultDataTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);
            $estudiante = getResultDataTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $id);

            $carrera = getResultCarrera($conexion, $estudiante[$CAMPO_ID_CARRERA]);
            $grupo = $estudiante[$CAMPO_GRUPO];
            componenteDatosUsuarioInicioAlumno($usuario, $carrera, $correo, $grupo);
        }
    }
}
