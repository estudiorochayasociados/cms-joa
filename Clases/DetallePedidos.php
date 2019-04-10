<?php

namespace Clases;

class DetallePedidos
{

    //Atributos
    public $id;
    public $cod;
    public $producto;
    public $cantidad;
    public $precio;
    public $variable1;
    public $variable2;
    public $variable3;
    public $variable4;
    public $variable5;

    private $con;


    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $sql = "INSERT INTO `detalle_pedidos`(`cod`, `producto`,`cantidad`,`precio`, `variable1`, `variable2`, `variable3`, `variable4`, `variable5`) 
                VALUES ('{$this->cod}', 
                        '{$this->producto}',
                        '{$this->cantidad}',
                        '{$this->precio}', 
                        '{$this->variable1}', 
                        '{$this->variable2}', 
                        '{$this->variable3}', 
                        '{$this->variable4}', 
                        '{$this->variable5}')";
        $query = $this->con->sql($sql);
        return true;
    }

    public function delete($cod)
    {
        $sql   = "DELETE FROM `detalle_pedidos` WHERE `cod`  = '{$cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    function list($cod)
    {
        $array = array();
        $sql = "SELECT * FROM `detalle_pedidos` WHERE `cod`  = '{$cod}' ORDER BY id ASC";
        $result = $this->con->sqlReturn($sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $array[] = $row;
            }
            return $array;
        }
    }
}
