<?php


class Jefe
{

    public function __construct()
    {
    }

    public function ponerGruposJefeCarrera($carrera)
    {
        $carreraGrupos = Variables::GRUPOS_DISPONIBLES[$carrera];
        $indiceGrupo = $carreraGrupos["numero"];

        for ($i = 1; $i <= $carreraGrupos["cantidad"]; $i++) {

            $grupo = $indiceGrupo . '0' . $i;
            echo "<option value='$grupo'>  $grupo </option>";
        }
    }

    public function realizarOperacionFormAñadirEstudiantes($conexion, $matricula, $contraseña, $rol, $nombre, $apellidos, $correo, $id_modalidad, $id_carrera, $grupo)
    {

        $archivo_cargado = isset($_FILES['archivo_csv']) && $_FILES['archivo_csv']['error'] === UPLOAD_ERR_OK;
        $archivo_tiene_contenido = $archivo_cargado && $_FILES['archivo_csv']['size'] > 0;

        $campos_completos = !empty($matricula) && !empty($contraseña) && !empty($nombre) && !empty($apellidos) && !empty($correo) && !empty($id_modalidad) && !empty($id_carrera) && !empty($rol) && !empty($grupo);

        $opcion = validacionCamposYArchivoCSV($campos_completos, $archivo_tiene_contenido);

        $opcion === 'Campos' ? $this->operacionInsertarEstudiante($conexion, $matricula, $contraseña, $rol, $nombre, $apellidos, $correo, $id_modalidad, $id_carrera, $grupo) : '';
        $opcion === "Archivo" ? $this->añadirPorCSVEstudiantes($conexion) : '';
    }

    public function operacionInsertarEstudiante($conexion, $matricula, $contraseña, $rol, $nombre, $apellidos, $correo, $id_modalidad, $id_carrera, $grupo)
    {

        if (restriccionKeyDuplicadaEstudiante($matricula, $correo, $conexion)) {
            return;
        }

        mysqli_begin_transaction($conexion);

        try {

            if (insertarUsuario($conexion, $matricula, $contraseña, $correo, $rol)) {




                if (!insertarEstudiante($conexion, $matricula, $nombre, $apellidos, $id_carrera, $id_modalidad, $grupo)) {
                    estructuraMensaje("Ocurrio un problema con la BD", "../../assets/iconos/ic_error.webp", "--rojo");
                }
                mysqli_commit($conexion);

                estructuraMensaje("Registro del estudiante exitoso", "../../assets/iconos/ic_correcto.webp", "--verde");
                return;

            }
        } catch (Exception $e) {
            estructuraMensaje("Error al añadir el registro", "../../assets/iconos/ic_error.webp", "--rojo");
        }


    }

    public function añadirPorCSVEstudiantes($conexion)
    {
    }
}