<?php

namespace Clases;

class Sliders
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $subtitulo;
    public $categoria;
    public $link;
    public $fecha;
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
        $sql   = "INSERT INTO `sliders`(`cod`, `titulo`, `subtitulo`,  `categoria`,  `link`, `fecha`) VALUES ('{$this->cod}', '{$this->titulo}', '{$this->subtitulo}', '{$this->categoria}' , '{$this->link}','{$this->fecha}')";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql   = "UPDATE `sliders` SET cod = '{$this->cod}', titulo = '{$this->titulo}', subtitulo = '{$this->subtitulo}', categoria = '{$this->categoria}', link = '{$this->link}', fecha = '{$this->fecha}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql   = "DELETE FROM `sliders` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql   = "SELECT * FROM `sliders` WHERE cod = '{$this->cod}' ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        $row   = mysqli_fetch_assoc($notas);
        return $row;
    }

    function list($filter) {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        $sql   = "SELECT * FROM `sliders` $filterSql  ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }
    function listForCategory() {
        $array = array();
        $sql = "SELECT * FROM `sliders` WHERE categoria = '{$this->categoria}'  ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }
}
