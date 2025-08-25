<?php

use PHPUnit\Framework\TestCase;
require_once "./src/clases/alumno.php";
require_once "./src/utils/constantes.php";
require_once "./src/utils/functionGlobales.php";

class AlumnoTest extends TestCase
{
    private $alumno;

    protected function setUp(): void
    {
        $this->alumno = new Alumno();
        $_POST = [];
        $_FILES = [];
    }

    /*public function testEnviarSolicitudAlumno()
    {
        // Simular entradas del formulario
        global $TABLA_SOLICITUDES, $CAMPO_ID_JEFE;
        $TABLA_SOLICITUDES = "solicitudes";
        $CAMPO_ID_JEFE = "id_jefe";

        $_POST['identificador'] = '223z0428';
        $_POST['motivo'] = 'Personal';
        $_POST['fecha_ausencia'] = date('Y-m-d');
        $_POST['carrera'] = 'SistemasComputacionales';
        $id_jefe = 'ITSH_0000';

        $dummyPath = __DIR__ . '\evidencia1.pdf';
        file_put_contents($dummyPath, '%PDF-1.4 dummy');  // contenido mínimo para simular PDF

        $_FILES['archivo_evidencia'] = [
            'name' => 'evidencia1.pdf',
            'type' => 'application/pdf',
            'tmp_name' => $dummyPath,
            'error' => 0,
            'size' => filesize($dummyPath),
        ];

        // Crear conexión real a la base de datos
        $conexion = new mysqli('localhost', 'root', '1234', 'sistema');

        // Ejecutar la función
        $resultado = $this->alumno->enviarSolicitud($conexion, $id_jefe);


        // Verificar el resultado esperado
        $this->assertEquals("Se ha enviado la solicitud a tu jefe de carrera", $resultado);
    }*/


    public function testVerificarPOSTCamposCompletos()
    {
        $_POST = [
            'identificador' => '223z0428',
            'motivo' => 'Personal',
            'fecha_ausencia' => '2025-05-29',
            'carrera' => 'Sistemas Computacionales'
        ];

        $_FILES['archivo_evidencia'] = [
            'name' => 'dummy.pdf',
            'type' => 'application/pdf',
            'tmp_name' => __DIR__ . '/dummy.pdf',
            'error' => 0,
            'size' => 1000,
        ];

        $alumno = new alumno();
        $resultado = $alumno->verificarPOST();
        $this->assertEquals(false, $resultado); // No debe haber errores
    }

    public function testVerificarPOSTCampoVacio()
    {
        $_POST = [
            'identificador' => '',
            'motivo' => 'Personal',
            'fecha_ausencia' => '2025-05-29',
            'carrera' => 'Sistemas Computacionales'
        ];

        $_FILES['archivo_evidencia'] = [
            'name' => 'dummy.pdf',
            'type' => 'application/pdf',
            'tmp_name' => __DIR__ . '/dummy.pdf',
            'error' => 0,
            'size' => 1000,
        ];

        $alumno = new alumno();
        $resultado = $alumno->verificarPOST();
        $this->assertEquals(true, $resultado); // No debe haber errores
    }

    public function testVerificarPOSTSinArchivo()
    {
        $_POST = [
            'identificador' => '223z0428',
            'motivo' => 'Personal',
            'fecha_ausencia' => '2025-05-29',
            'carrera' => 'Sistemas Computacionales'
        ];

        $_FILES['archivo_evidencia'] = [
            'name' => 'dummy.pdf',
            'type' => 'application/pdf',
            'tmp_name' => __DIR__ . '/dummy.pdf',
            'error' => 0,
            'size' => 0, // Simula que no se subió nada
        ];

        $alumno = new alumno();
        $resultado = $alumno->verificarPOST();
        $this->assertEquals(true, $resultado); // No debe haber errores
    }


}
