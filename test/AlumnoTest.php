<?php

use PHPUnit\Framework\TestCase;
require_once "./src/clases/alumno.php";

class AlumnoTest extends TestCase
{
        private $alumno;

        public function setUp(): void{
            $this->alumno = new Alumno();
        }

        public function testValidarFechaActual(){

            $fechaHoy = date('Y-m-d');
            $this->
        }



        $fechaPasada = date('Y-m-d', strtotime('-1 day'));
        $this->assertTrue($alumno->esFechaValida($fechaPasada));

        $this->assertTrue($alumno->esFechaValida($fechaHoy));

        $fechaFutura = date('Y-m-d', strtotime('+1 day'));
        $this->assertFalse($alumno->esFechaValida($fechaFutura));
}
