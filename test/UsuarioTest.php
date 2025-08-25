<?php

use PHPUnit\Framework\TestCase;

require_once "./src/clases/usuario.php";
require_once "./src/utils/constantes.php";
require_once "./src/utils/functionGlobales.php";

class UsuarioTest extends TestCase
{
    private $usuario;

    protected function setUp(): void
    {
        $this->usuario = new Usuario();
    }

    public function testVerificacionEstudianteExitosa()
    {
        // Simular conexión real (si estás probando contra la BD directamente, aunque no es lo ideal para unit tests)
        $conexion = new mysqli('localhost', 'root', '1234', 'sistema');

        // Ejecutar método
        $resultado = $this->usuario->Verificacion($conexion, '223z9999', '12345678');

        // Verificar comportamiento esperado
        $this->assertEquals("Usuario invalido", $resultado);
    }
}
