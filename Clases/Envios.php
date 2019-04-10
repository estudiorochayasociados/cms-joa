<?php

namespace Clases;

class Envios
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $peso;
    public $precio;
    public $estado;
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
        $sql = "INSERT INTO `envios`(`cod`, `titulo`, `peso`, `precio`, `estado`) VALUES ('{$this->cod}', '{$this->titulo}','{$this->peso}','{$this->precio}', '{$this->estado}')";
        $query = $this->con->sql($sql);
        return true;
    }

    public function edit()
    {
        $sql = "UPDATE `envios` SET  `titulo`='{$this->titulo}',`peso`='{$this->peso}',`precio`='{$this->precio}',`estado`='{$this->estado}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return true;
    }

    public function cambiar_estado()
    {
        $sql = "UPDATE `envios` SET `estado`='{$this->estado}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql = "DELETE FROM `envios` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql = "SELECT * FROM `envios` WHERE cod = '{$this->cod}' ORDER BY id DESC";
        $envios = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($envios);
        return $row;
    }

    public function peso($peso){
        if ($peso <= 1) {
            return $tope = 1;
        } elseif ($peso > 1 && $peso <= 3) {
            return $tope = 3;
        } elseif ($peso > 3 && $peso <= 5) {
            return $tope = 5;
        } elseif ($peso > 5 && $peso <= 10) {
            return $tope = 10;
        } elseif ($peso > 10 && $peso <= 15) {
            return $tope = 15;
        } elseif ($peso > 15 && $peso <= 20) {
            return $tope = 20;
        } elseif ($peso > 20 && $peso <= 25) {
            return $tope = 25;
        } elseif ($peso > 25 && $peso <= 30) {
            return $tope = 30;
        }
    }

    function list($filter)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        $sql = "SELECT * FROM `envios` $filterSql  ORDER BY precio ASC";
        $envios = $this->con->sqlReturn($sql);
        if ($envios) {
            while ($row = mysqli_fetch_assoc($envios)) {
                $array[] = $row;
            }
            return $array;
        }
    }
}
