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
    public $img;
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


    public function import_meli()
    {
        $productos = $this->listWithOps("", "", "0,300");
        foreach ($productos as $producto) {
            $meli = $this->funciones->curl("GET", "https://api.mercadolibre.com/sites/MLA/category_predictor/predict?title=" . $this->funciones->normalizar_meli($producto["titulo"]) . "", "");
            $meli = json_decode($meli, true);
            $meli_categoria = $meli["id"];
            $data = '{
                "title": "' . $producto["titulo"] . '",
                "category_id": "' . $meli_categoria . '",
                "price": ' . $producto["precio"] . ',
                "currency_id": "ARS",
                "available_quantity": 50,
                "buying_mode": "buy_it_now",
                "listing_type_id": "gold_special",
                "condition": "new",
                "description": {"plain_text": "Características: Látex Acrílico premium línea eco exteriores e interior de acabado mate, de máximo poder cubritivo, resistente a la formación de hongos y algas y de rápido secado.Se aplica sobre mampostería, revoque, yeso, papel, ladrillos, fribrocemento entro otros. Jupi decoradora la superficie en su amplia gama de colores por muchos años, su formulación le permite conservar permeabilidad al vapor de la mampostería sin ampollarse.Rendimiento: De 10 a 12 m2 por litro y por mano, variando según el color y la absorción de la superficie.Secado: Al tacto 1 hora no repintar antes de las 4 horas, secado final entre las 12 y 24 hs.mprimación preparación de la superficie: En superficies vírgenes, entizadas o muy absorbentes, aplicar previamente una mano de enduido con un 20% de agua y aplicar a modo de imprimación. En superficies repintadas eliminar con espátula y/o cepillo de alambre las partes flojas o descascaradas previamente a la aplicación de la mano de la imprimación. Eliminar hongos con agua y detergente.Aplicación: Se aplica a pincel o rodillo en dos manos sin diluir dejando secar entre manos.Primeros Auxilios: No ingerir, evitar inhalación prologada de los vapores. Mantener alejado del alcance de los niños.Ventilar ambientes en caso de inhalación prolongada. En caso de ingestión accidental consultar a un médico.Producto no infamable.Consultar stock antes de ofertar.Consultar precio por cantidad."},
                "tags": [
                "immediate_payment"
                ],
                "pictures" : [{"source":"https://assets.trome.pe/files/ec_article_multimedia_gallery/uploads/2018/04/17/5ad609d27c1a7.jpeg"}]
                }';
            $meli = $this->funciones->curl("POST", "https://api.mercadolibre.com/items?access_token=" . $_SESSION["access_token"], $data);
            var_dump($meli);
        }
    }

    public function add_meli()
    {
        $meli = $this->funciones->curl("GET", "https://api.mercadolibre.com/sites/MLA/category_predictor/predict?title=" . $this->funciones->normalizar_meli($this->titulo) . "", "");
        $meli = json_decode($meli, true);
        $meli_categoria = $meli["id"];

        $data = '{
        "title": "' . $this->titulo . '",
        "category_id": "' . $meli_categoria . '",
        "price": ' . $this->precio . ',
        "currency_id": "ARS",
        "available_quantity": ' . $this->stock . ',
        "buying_mode": "buy_it_now",
        "listing_type_id": "gold_special",
        "condition": "new",
        "description": {"plain_text": "' . strip_tags($this->desarrollo) . '"},
        "tags": [
        "immediate_payment"
        ],
        "pictures": [' . $this->img . ']
        }';

        //echo $data;
        $meli = $this->funciones->curl("POST", "https://api.mercadolibre.com/items?access_token=" . $_SESSION["access_token"], $data);
        $meli = json_decode($meli, true);
        return $meli;
    }


    public function edit_meli()
    {
            $data = '{
        "title": "' . $this->titulo . '",  
        "price": ' . $this->precio . ', 
        "available_quantity": ' . $this->stock . ',      
        "pictures": [' . $this->img . ',{"source":"http://pintureriasariel.com.ar/Images/logo-grande.png"}]
        }';
        $meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data);
        return $meli;
    }

    public function delete_meli()
    {
        $data_status = '{ "status":"closed" }';
        $data_delete = '{ "deleted":"true" }';
        $meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data_status);
        $meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data_delete);
        return $meli;
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
