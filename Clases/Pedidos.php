<?php

namespace Clases;

class Pedidos
{

    //Atributos
    public $id;
    public $cod;
    public $producto;
    public $cantidad;
    public $total;
    public $estado;
    public $tipo;
    public $usuario;
    public $detalle;
    public $fecha;
    public $hub_cod;

    private $con;
    private $detallePedido;
    private $user;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->detallePedido = new DetallePedidos();
        $this->user = new Usuarios();
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
        $sql = "INSERT INTO `pedidos`(`cod`,`total`, `estado`, `tipo`, `usuario`, `detalle`, `fecha`, `hub_cod`) 
                  VALUES ('{$this->cod}',
                          '{$this->total}',
                          '{$this->estado}',
                          '{$this->tipo}',
                          '{$this->usuario}',
                          '{$this->detalle}',
                          '{$this->fecha}',
                          '{$this->hub_cod}')";
        $query = $this->con->sql($sql);
        return true;
    }

    public function changeState(){
        $sql = "UPDATE `pedidos` SET `estado`='{$this->estado}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function changeValue($key)
    {
        $sql   = "UPDATE `pedidos` SET `$key`='{$this->$key}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql = "DELETE FROM `pedidos` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        $this->detallePedido->delete($this->cod);
        return $query;
    }

    public function view()
    {
        $sql = "SELECT * FROM `pedidos` WHERE cod = '{$this->cod}' ORDER BY id DESC";
        $pedidos = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($pedidos);
        $details = $this->detallePedido->list($this->cod);
        $this->user->set("cod",$row['usuario']);
        $user = $this->user->view();
        $array = array("data"=>$row, "user" => $user, "detail" => $details);
        return $array;
    }

    function listWithOps($filter, $order, $limit)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        if ($order != '') {
            $orderSql = $order;
        } else {
            $orderSql = "id DESC";
        }

        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }
        $sql = "SELECT * FROM `pedidos` $filterSql  ORDER BY $orderSql $limitSql";
        $result = $this->con->sqlReturn($sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $details = $this->detallePedido->list($row['cod']);
                $this->user->set("cod", $row['usuario']);
                $user = $this->user->view();
                $array[] = array("data" => $row, "user" => $user, "detail" => $details);
            }
            return $array;
        }
    }
}
