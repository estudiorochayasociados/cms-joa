<?php

namespace Clases;
class Productos
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $precio;
    public $peso;
    public $precioDescuento;
    public $stock;
    public $desarrollo;
    public $categoria;
    public $subcategoria;
    public $keywords;
    public $description;
    public $fecha;
    public $meli;
    public $url;
    private $con;
    private $funciones;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->funciones = new PublicFunction();
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
        $sql = "INSERT INTO `productos`(`cod`, `titulo`,`cod_producto`, `precio`, `peso`, `precioDescuento`, `stock`, `desarrollo`, `categoria`, `subcategoria`, `keywords`, `description`, `fecha`, `meli`, `url`) VALUES ('{$this->cod}', '{$this->titulo}','{$this->cod_producto}', '{$this->precio}', '{$this->peso}', '{$this->precioDescuento}', '{$this->stock}', '{$this->desarrollo}', '{$this->categoria}', '{$this->subcategoria}', '{$this->keywords}', '{$this->description}', '{$this->fecha}', '{$this->meli}', '{$this->url}')";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql = "UPDATE `productos` SET
        `cod` = '{$this->cod}',
        `titulo` = '{$this->titulo}',
        `precio` = '{$this->precio}',
        `peso` = '{$this->peso}',
        `cod_producto` = '{$this->cod_producto}',
        `precioDescuento` = '{$this->precioDescuento}',
        `stock` = '{$this->stock}',
        `desarrollo` = '{$this->desarrollo}',
        `categoria` = '{$this->categoria}',
        `subcategoria` = '{$this->subcategoria}',
        `keywords` = '{$this->keywords}',
        `description` = '{$this->description}',
        `fecha` = '{$this->fecha}',
        `meli` = '{$this->meli}',
        `url` = '{$this->url}'
        WHERE `id`='{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql = "DELETE FROM `productos` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }


    public function add_meli()
    {
        $meli = $this->funciones->curl("GET", "https://api.mercadolibre.com/sites/MLA/category_predictor/predict?title=" . $this->funciones->normalizar_meli($this->titulo) . "", "");
        $meli = json_decode($meli, true);
        $meli_categoria = $meli["id"];

        $cod_p = explode("/", $this->cod_producto);
        $data = '{
        "title": "' . $this->titulo . '",
        "category_id": "' . $meli_categoria . '",
        "price": ' . $this->precio . ',
        "currency_id": "ARS",
        "available_quantity": 1,
        "buying_mode": "buy_it_now",
        "listing_type_id": "gold_special",
        "condition": "new",
        "description": "' . $this->titulo . '",
        "tags": [
        "immediate_payment"
        ],
        "pictures": [
            {
                "source": "http://c1361264.ferozo.com/assets/archivos/img_productos/' . $cod_p[0] . '/' . str_replace("/", "-", $this->cod_producto) . '.jpg";
            }]
        }';

        $meli = $this->funciones->curl("POST", "https://api.mercadolibre.com/items?access_token=" . $_SESSION["access_token"], $data);
        var_dump($meli);
    }


    public function edit_meli()
    {

        $meli = $this->funciones->curl("GET", "https://api.mercadolibre.com/sites/MLA/category_predictor/predict?title=" . $this->funciones->normalizar_meli($this->titulo) . "", "");
        $meli = json_decode($meli, true);
        $meli_categoria = $meli["path_from_root"][0]["id"];
        echo $meli_categoria;
        $cod_p = explode("/", $this->cod_producto);
        $data = '{
        "title": "' . $this->titulo . '",
        "category_id": "' . $meli_categoria . '",
        "price": ' . $this->precio . ',
        "currency_id": "ARS",
        "available_quantity": 1,
        "buying_mode": "buy_it_now",
        "listing_type_id": "gold_special",
        "condition": "new",
        "description": "' . $this->titulo . '",
        "tags": [
        "immediate_payment"
        ],
        "pictures": [
            {
                "source": "http://c1361264.ferozo.com/assets/archivos/img_productos/' . $cod_p[0] . '/' . str_replace("/", "-", $this->cod_producto) . '.jpg";
            }]
        }';
        $meli = $this->funciones->curl("POST", "https://api.mercadolibre.com/items?access_token=" . $_SESSION["access_token"], $data);
        var_dump($meli);
    }


    public function view()
    {
        $sql = "SELECT * FROM `productos` WHERE id = '{$this->id}' ||  cod = '{$this->cod}' ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($notas);
        return $row;
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

        $sql = "SELECT * FROM `productos` $filterSql  ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
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

        $sql = "SELECT * FROM `productos` $filterSql  ORDER BY $orderSql $limitSql";

        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }

    function paginador($filter, $cantidad)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }
        $sql = "SELECT * FROM `productos` $filterSql";
        $contar = $this->con->sqlReturn($sql);
        $total = mysqli_num_rows($contar);
        $totalPaginas = $total / $cantidad;
        return ceil($totalPaginas);
    }
}
