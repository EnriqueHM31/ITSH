<?php

class Usuario
{

    public function __Usuario()
    {
    }

    public function Verificacion($conexion, $id, $contraseña)
    {
        global $TABLA_USUARIO, $CAMPO_CONTRASEÑA, $CAMPO_ID_ROL, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $ADMIN, $JEFE, $ESTUDIANTE;
        if (empty($id) || empty($contraseña)) {
            EstructuraMensaje("Llena todos los campos", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $resultUsuario = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);


        if (!$resultUsuario) {
            EstructuraMensaje("Usuario Invalido", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $contraseñaBD = $resultUsuario[$CAMPO_CONTRASEÑA];
        $rolBD = $resultUsuario[$CAMPO_ID_ROL];

        if ($contraseñaBD !== $contraseña) {
            EstructuraMensaje("Contraseña Incorrecta", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }


        $rol = ObtenerRolUsuario($conexion, $rolBD);

        if ($rol === $ADMIN) {
            $this->asignarDatosInicioSesion($resultUsuario, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $rol, "Admin/Admin.php");
        }
        if ($rol === $JEFE) {
            $this->asignarDatosInicioSesion($resultUsuario, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $rol, "JefedeCarrera/JefeCarrera.php");
        }
        if ($rol === $ESTUDIANTE) {
            $this->asignarDatosInicioSesion($resultUsuario, $CAMPO_ID_USUARIO, $CAMPO_CORREO, $rol, "Alumno/alumno.php");
        } else {
            EstructuraMensaje("Ocurrio un error con la pagina", "./src/assets/iconos/ic_error.webp", "--rojo");
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
            EstructuraMensaje("Llena todos los campos", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (strlen($contraseña_nueva) < 8) {
            EstructuraMensaje("Contraseña debe ser 8 caracteres", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $contraseña = ObtenerContraseñaActualdb($conexion, $id);

        if ($contraseña_actual !== $contraseña) {
            EstructuraMensaje("La contraseña actual no es la correcta", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        if (ModificarLaContraseñaActualPaginaInicioDB($conexion, $id, $contraseña_nueva)) {
            EstructuraMensaje("Se cambio la contraseña", "../../assets/iconos/ic_correcto.webp", "--verde");
        } else {
            EstructuraMensaje("Error al actualizar la contraseña", "../../assets/iconos/ic_error.webp", "--rojo");
        }

        $conexion->close();
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
            $usuario = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);
            componenteDatosUsuarioInicioAdministrador($usuario, $correo);

            return;
        }

        if ($rol === $JEFE) {

            $usuario = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);
            $jefe = ObtenerDatosDeUnaTabla($conexion, $TABLA_JEFE, $CAMPO_ID_USUARIO, $id);
            $carrera = ObtenerNombreCarrera($conexion, $jefe[$CAMPO_ID_CARRERA]);
            componenteDatosUsuarioInicioJefeCarrera($usuario, $carrera, $correo);

            return;
        }

        if ($rol === $ESTUDIANTE) {
            $usuario = ObtenerDatosDeUnaTabla($conexion, $TABLA_USUARIO, $CAMPO_ID_USUARIO, $id);
            $estudiante = ObtenerDatosDeUnaTabla($conexion, $TABLA_ESTUDIANTE, $CAMPO_ID_USUARIO, $id);

            $carrera = ObtenerNombreCarrera($conexion, $estudiante[$CAMPO_ID_CARRERA]);
            $grupo = $estudiante[$CAMPO_GRUPO];
            componenteDatosUsuarioInicioAlumno($usuario, $carrera, $correo, $grupo);
        }
    }
}
