<?php namespace Clases;

class Conexion
{
    //private $datos = array("host"=> "localhost","user"=> "root","pass"=> "","db"  => "pintureria_ariel");
    //private $datos = array("host"=> "162.144.180.63","user"=> "estudfh2_ariel","pass"=> "faAr2010","db"  => "estudfh2_ariel");
    private $datos = array("host"=> "localhost","user"=> "root","pass"=> "","db"  => "pintureria-ariel");
    private $con;

    public function __construct()
    {
        //$this->con = mysqli_connect($this->datos["host"], $this->datos["user"], $this->datos["pass"], $this->datos["db"]);
        //mysqli_set_charset($this->con,'utf8');

    }
    public function con()
    {
        $conexion = mysqli_connect($this->datos["host"], $this->datos["user"], $this->datos["pass"], $this->datos["db"]);
        mysqli_set_charset($conexion,'utf8');
        return $conexion;
    }

    public function sql($query)
    {
        $conexion = mysqli_connect($this->datos["host"], $this->datos["user"], $this->datos["pass"], $this->datos["db"]);
        mysqli_set_charset($conexion,'utf8');
        $conexion->query($query);
        $conexion->close();
    }

    public function sqlReturn($query)
    {
        $conexion = mysqli_connect($this->datos["host"], $this->datos["user"], $this->datos["pass"], $this->datos["db"]);
        mysqli_set_charset($conexion,'utf8');
        $dato =  $conexion->query($query);
        $conexion->close();
        return $dato;
    }


    public function backup()
    {
        return $this->datos;
    }
}
