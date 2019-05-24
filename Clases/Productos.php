<?php

namespace Clases;
class Productos
{

    //Atributos
    public $id;
    public $cod;
    public $cod_producto;
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
        $sql = "INSERT INTO `productos`(`cod`, `titulo`,`cod_producto`, `precio`, `peso`, `precio_mayorista`, `stock`, `desarrollo`, `categoria`, `subcategoria`, `keywords`, `description`, `fecha`, `meli`, `url`) VALUES ('{$this->cod}', '{$this->titulo}','{$this->cod_producto}', '{$this->precio}', '{$this->peso}', '{$this->precio_mayorista}', '{$this->stock}', '{$this->desarrollo}', '{$this->categoria}', '{$this->subcategoria}', '{$this->keywords}', '{$this->description}', '{$this->fecha}', '{$this->meli}', '{$this->url}')";
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
        `precio_mayorista` = '{$this->precio_mayorista}',
        `stock` = '{$this->stock}',
        `desarrollo` = '{$this->desarrollo}',
        `categoria` = '{$this->categoria}',
        `subcategoria` = '{$this->subcategoria}',
        `keywords` = '{$this->keywords}',
        `description` = '{$this->description}',
        `fecha` = '{$this->fecha}',
        `meli` = '{$this->meli}',
        `url` = '{$this->url}'
        WHERE `id`='{$this->id}' OR `cod_producto`='{$this->cod_producto}'";
        $query = $this->con->sql($sql);
        return $query;
    }


    public function editSingle($atributo, $valor)
    {
        $sql = "UPDATE `productos` SET `$atributo` = '$valor' WHERE `cod_producto`='{$this->cod_producto}'";
        $this->con->sql($sql);
        echo $sql;
    }

    public function delete()
    {
        $sql = "DELETE FROM `productos` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function truncate()
    {
        $sql = "TRUNCATE `productos`";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function editStock0()
    {
        $sql = "DELETE FROM `productos` WHERE `meli` = ''";
        $query = $this->con->sql($sql);
        $sql = "UPDATE `productos` SET `stock` = 0 WHERE meli != ''";
        $query = $this->con->sql($sql);
        return $query;
    }


    public function import_meli()
    {
        $productos = $this->list_with_options(array("precio > 0"), "", "150");

        foreach ($productos as $producto) {
            $this->cod_producto = $producto["cod_producto"];
            $this->titulo = $producto["titulo"];
            $this->precio = number_format(($producto["precio"] * 0.80), 2, ".", "");
            $this->stock = $producto["stock"];
            $this->desarrollo = $producto["titulo"].'\n Hacemos envíos a todo el país, y recibimos todas las tarjeta.\n Consultanos por stock y precio de tu producto.\n Trabajamos con una alta responsabilidad, que nos respaldan nuestros compradores.\n Pinturerias Ariel.\n San Francisco, Córdoba.\n Encontranos en nuestro sistema web';
            //$this->desarrollo = $producto["titulo"];
            $cod_producto = str_replace("/", "-", $producto["cod_producto"]);
            $categoria = explode("/", $producto["cod_producto"]);
            $this->img = '{"source":"http://c1361264.ferozo.com/assets/archivos/img_productos/' . $categoria[0] . '/' . $cod_producto . '.jpg"}';
            if ($producto["meli"] != '') {
                $this->meli = $producto["meli"];
                $edit_meli = $this->edit_meli();
                if (isset($edit_meli["message"])) {
                    echo $cod_producto . " | ERROR MESSAGE: " . $edit_meli["message"] . "<hr/>";
                }
            } else {
                $add_meli = $this->add_meli();
                if (isset($add_meli["id"])) {
                    echo $add_meli["id"] . "<hr/>";
                    $this->cod_producto = $producto["cod_producto"];
                    $this->editSingle("meli", $add_meli["id"]);
                }

                if (isset($add_meli["message"])) {
                    echo $cod_producto . " | ERROR MESSAGE: " . $add_meli["message"] . "<hr/>";
                }
            }
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
        "listing_type_id": "bronze",
        "condition": "new",
        "description": {"plain_text": "' . strip_tags($this->desarrollo) . '"},
        "tags": ["immediate_payment"],
        "shipping": {
           "mode": "me2",
           "local_pick_up": true,
           "free_shipping": false,
           "free_methods": []
         },
        "pictures": [' . $this->img . ',{"source":"http://c1361264.ferozo.com/assets/images/logo.png"}],
        "attributes": [
        {
          "id": "BRAND",
          "name": "Marca",
          "value_id": null,
          "value_name": "Pintureria Ariel",
          "value_struct": null,
          "attribute_group_id": "OTHERS",
          "attribute_group_name": "Otros"
         },{
          "id": "EAN",
          "name": "EAN",
          "value_name": "978020137962",
          "type": "product_identifier",
           "value_type": "string",
           "value_max_length": 60,
           "attribute_group_id": "DFLT",
           "attribute_group_name": "Otros"
        }]        
        }';

        echo $data."<hr/>";

        $meli = $this->funciones->curl("POST", "https://api.mercadolibre.com/items?access_token=" . $_SESSION["access_token"], $data);
        $meli = json_decode($meli, true);
        return $meli;
    }


    public function edit_meli()
    {
        $variations_array = json_decode($this->get_variations($this->meli));
        if (count($variations_array) > 0) {
            foreach ($variations_array as $value) {
                $this->delete_variations($this->meli, $value->id);
            }
        }
        $data = '{
                "title": "' . $this->titulo . '",  
                "price": ' . $this->precio . ', 
                "available_quantity": ' . $this->stock . ',      
                "pictures": [' . $this->img . ',{"source":"http://c1361264.ferozo.com/assets/images/logo.png"}]
        }';

        //echo $data."<hr/>";

        $meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data);
        return $meli;
    }

    public function view_meli($id)
    {
        $meli = $this->funciones->curl("GET", "https://api.mercadolibre.com/items/$id?access_token=" . $_SESSION["access_token"], "");
        return $meli;
    }


    public function get_variations($id)
    {
        $meli = $this->funciones->curl("GET", "https://api.mercadolibre.com/items/$id/variations?access_token=" . $_SESSION["access_token"], "");
        return $meli;
    }

    public function delete_variations($id, $variation)
    {
        $meli = $this->funciones->curl("DELETE", "https://api.mercadolibre.com/items/$id/variations/$variation?access_token=" . $_SESSION["access_token"], "");
        return $meli;
    }


    public function delete_meli()
    {
        $data_status = '{ "status":"closed" }';
        //$data_delete = '{ "deleted":"true" }';
        $meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data_status);
        //$meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data_delete);
        return $meli;
    }


    public function view()
    {
        $sql = "SELECT * FROM `productos` WHERE id = '{$this->id}' ||  cod = '{$this->cod}' ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($notas);
        return $row;
    }

    public function list($filter)
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

    function list_with_options($filter, $order, $limit)
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

        $sql = "SELECT id,cod_producto,titulo,precio,precio_mayorista,stock,meli FROM `productos` $filterSql  ORDER BY $orderSql $limitSql";

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
