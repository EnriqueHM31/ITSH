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
            echo "<option value=\  $grupo  \>  $grupo </option>";
        }
    }

    public function realizarOperacionFormAñadirEstudiantes($conexion)
    {
        $id = trim($_POST["clave"] ?? "");
        $nombre = trim($_POST["nombre"] ?? "");
        $apellidos = trim($_POST["apellidos"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $cargo = trim($_POST['correo']);
        $id_modalidad = trim($_POST['modalidad']);
        $contraseña = 'Aa12345%';

        $archivo_cargado = isset($_FILES['archivo_csv']) && $_FILES['archivo_csv']['error'] === UPLOAD_ERR_OK;
        $archivo_tiene_contenido = $archivo_cargado && $_FILES['archivo_csv']['size'] > 0;

        $campos_completos = !empty($id) && !empty($nombre) && !empty($apellidos) && !empty($correo);

        $opcion = validacionCamposYArchivoCSV($campos_completos, $archivo_tiene_contenido);

        $opcion === 'Campos' ? 1 : 1;
        $opcion === "Archivo" ? 2 : 2;
    }
}