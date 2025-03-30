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
        if (empty($id) || empty($contraseña)) {
            estructuraMensaje("Llena todos los campos", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $sqlUsuario = "SELECT * FROM " . Variables::TABLA_BD_USUARIO .
            " WHERE " . Variables::CAMPO_ID_USUARIO . " = ?";

        $prepareUsuario = $conexion->prepare($sqlUsuario);
        $prepareUsuario->bind_param("s", $id);
        $prepareUsuario->execute();
        $resultUsuario = $prepareUsuario->get_result();

        if ($resultUsuario->num_rows < 1) {
            estructuraMensaje("Usuario Invalido", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $usuario = $resultUsuario->fetch_assoc();
        $contraseñaBD = $usuario[Variables::CAMPO_CONTRASEÑA];

        if ($contraseñaBD !== $contraseña) {
            estructuraMensaje("Contraseña Incorrecta", "./src/assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $rolBD = $usuario[Variables::CAMPO_ID_ROL];

        $sqlRol = "SELECT " . Variables::CAMPO_ROL . " FROM " . Variables::TABLA_BD_ROL .
            " WHERE " . Variables::CAMPO_ID_ROL . " = ?";

        $prepareRol = $conexion->prepare($sqlRol);
        $prepareRol->bind_param("s", $rolBD);
        $prepareRol->execute();
        $resultRol = $prepareRol->get_result();
        $rol = $resultRol->fetch_assoc();

        if ($rol[Variables::CAMPO_ROL] === Usuario::ADMIN) {
            session_start();
            $_SESSION["id"] = $usuario[Variables::CAMPO_ID_USUARIO];
            $_SESSION["correo"] = $usuario[Variables::CAMPO_CORREO];
            $_SESSION["rol"] = $rol[Variables::CAMPO_ROL];
            header("Location: src/layouts/Admin/Admin.php");

        }
        if ($rol[Variables::CAMPO_ROL] === Usuario::JEFE_DE_CARRERA) {
            session_start();
            $_SESSION["id"] = $usuario[Variables::CAMPO_ID_USUARIO];
            $_SESSION["correo"] = $usuario[Variables::CAMPO_CORREO];
            $_SESSION["rol"] = $rol[Variables::CAMPO_ROL];
            header("location: src/layouts/JefedeCarrera/JefeCarrera.php");
        }
        if ($rol[Variables::CAMPO_ROL] === Usuario::ESTUDIANTE) {
            session_start();
            $_SESSION["id"] = $usuario[Variables::CAMPO_ID_USUARIO];
            $_SESSION["correo"] = $usuario[Variables::CAMPO_CORREO];
            $_SESSION["rol"] = $rol[Variables::CAMPO_ROL];
            header("location: src/layouts/Alumno/alumno.php");
        } else {
            estructuraMensaje("Ocurrio un error con la pagina", "./src/assets/iconos/ic_error.webp", "--rojo");
        }

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
