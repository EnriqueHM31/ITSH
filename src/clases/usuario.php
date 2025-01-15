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
            header("location: src/layouts/Admin/Admin.php");
        }
        if ($rol === Usuario::JEFE_DE_CARRERA) {
            session_start();
            $_SESSION["id"] = $usuario[Variables::CAMPO_ID_USUARIO];
            $_SESSION["correo"] = $usuario[Variables::CAMPO_CORREO];
            $_SESSION["rol"] = $rol[Variables::CAMPO_ROL];
            header("location: src/layouts/JefedeCarrera/JefeCarrera.php");
        }
        if ($rol === Usuario::ESTUDIANTE) {
            session_start();
            $_SESSION["id"] = $usuario[Variables::CAMPO_ID_USUARIO];
            $_SESSION["correo"] = $usuario[Variables::CAMPO_CORREO];
            $_SESSION["rol"] = $rol[Variables::CAMPO_ROL];
            header("location: src/layouts/Alumno/alumno.php");
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

        $sqlComprobacionContraseña = "SELECT " . Variables::CAMPO_CONTRASEÑA . " FROM " . Variables::TABLA_BD_USUARIO . " WHERE " . Variables::CAMPO_ID_USUARIO . " = ?";
        $stmt = $conexion->prepare($sqlComprobacionContraseña);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $resultContraseña = $stmt->get_result()->fetch_assoc();
        $contraseña = $resultContraseña['contraseña'];


        if ($contraseña_actual !== $contraseña) {
            estructuraMensaje("La contraseña actual no es la correcta", "../../assets/iconos/ic_error.webp", "--rojo");
            return;
        }

        $sqlUpdateContraseña = "UPDATE " . Variables::TABLA_BD_USUARIO . " SET " . Variables::CAMPO_CONTRASEÑA . " = " . $contraseña_nueva . " WHERE " . Variables::CAMPO_ID_USUARIO . " = ?";
        $stmtUpdate = $conexion->prepare($sqlUpdateContraseña);
        $stmtUpdate->bind_param("s", $id);

        if ($stmtUpdate->execute()) {
            estructuraMensaje("Se cambio la contraseña", "../../assets/iconos/ic_correcto.webp", "--verde");
        } else {
            estructuraMensaje("Error al actualizar la contraseña", "../../assets/iconos/ic_error.webp", "--rojo");
        }

        $conexion->close();
    }

    public function verificarIdentidadCorreoIdentificador($matriculaDB, $correoDB, $conexion)
    {
        $correo = $correoDB;
        $identificador = $matriculaDB;

        $sql = "SELECT * FROM " . Variables::TABLA_BD_USUARIO .
            " WHERE " . Variables::CAMPO_CORREO . " = '$correo' AND " .
            Variables::CAMPO_ID_USUARIO . " = '" . $identificador . "'";

        return mysqli_query($conexion, $sql);
    }

    public function cambiarContraseñaEnBD($nuevaContraseña, $correo, $conexion)
    {
        $sql = "UPDATE " . Variables::TABLA_BD_USUARIO . " SET "
            . Variables::CAMPO_CONTRASEÑA . " = '$nuevaContraseña' AND " .
            Variables::CAMPO_CORREO . " = '" . $correo . "'";

        return mysqli_query($conexion, $sql);
    }

    public function escribirDatosDelUsuario($conexion, $id, $rol, $correo)
    {
        if ($rol === Usuario::ADMIN) {
            $sql = "SELECT * FROM " . Usuario::ADMIN . " WHERE " . Variables::CAMPO_CLAVE_EMPLEADO_ADMIN . " = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $data = $stmt->get_result();
            echo "<div class='contenedor-datos'>";
            while ($row = $data->fetch_assoc()) {
                echo "<p><strong>Clave:</strong> " . $row[Variables::CAMPO_CLAVE_EMPLEADO_ADMIN] . "</p>";
                echo "<p><strong>Nombre:</strong> " . $row[Variables::CAMPO_NOMBRE] . "</p>";
                echo "<p><strong>Apellidos:</strong> " . $row[Variables::CAMPO_APELLIDOS] . "</p>";
                echo "<p><strong>Correo:</strong> " . $correo . "</p>";
            }
            echo "<button id='btn_contraseña' class='btn_vino'>Cambiar contraseña</button>";
            echo "</div>";
            return;
        }

        if ($rol === Usuario::JEFE_DE_CARRERA) {
            $sql = "SELECT * FROM " . Usuario::JEFE_DE_CARRERA . " WHERE " . Variables::CAMPO_CLAVE_EMPLEADO_JEFE . " = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $data = $stmt->get_result();
            echo "<div class='contenedor-datos'>";
            while ($row = $data->fetch_assoc()) {
                echo "<p><strong>Identificador:</strong> " . $row[Variables::CAMPO_CLAVE_EMPLEADO_JEFE] . "</p>";
                echo "<p><strong>Nombre:</strong> " . $row[Variables::CAMPO_NOMBRE] . "</p>";
                echo "<p><strong>Apellidos:</strong> " . $row[Variables::CAMPO_APELLIDOS] . "</p>";
                echo "<p><strong>Carrera:</strong> " . $row[Variables::CAMPO_CARRERA] . "</p>";
                echo "<p><strong>Correo:</strong> " . $correo . "</p>";

            }
            echo "<button id='btn_contraseña' class='btn_vino'>Cambiar contraseña</button>";
            echo "</div>";

            return;
        }

        if ($rol === Usuario::ESTUDIANTE) {
            $sql = "SELECT * FROM " . Usuario::ESTUDIANTE . " WHERE " . Variables::CAMPO_MATRICULA . " = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $data = $stmt->get_result();
            echo "<div class='contenedor-datos'>";
            while ($row = $data->fetch_assoc()) {
                echo "<p><strong>Identificador:</strong> " . $row[Variables::CAMPO_MATRICULA] . "</p>";
                echo "<p><strong>Nombre:</strong> " . $row[Variables::CAMPO_NOMBRE] . "</p>";
                echo "<p><strong>Apellidos:</strong> " . $row[Variables::CAMPO_APELLIDOS] . "</p>";
                echo "<p><strong>Carrera:</strong> " . $row[Variables::CAMPO_CARRERA] . "</p>";
                echo "<p><strong>Grupo:</strong> " . $row[Variables::CAMPO_GRUPO] . "</p>";
                echo "<p><strong>Correo:</strong> " . $correo . "</p>";
            }
            echo "<button id='btn_contraseña' class='btn_vino'>Cambiar contraseña</button>";
            echo "</div>";
        }
    }
}
