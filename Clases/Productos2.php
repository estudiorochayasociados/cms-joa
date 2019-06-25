<?php


namespace Clases;


class Productos2
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
        if (!empty($valor)) {
            $valor = "'" . $valor . "'";
        } else {
            $valor = "NULL";
        }
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function editSingle($atributo, $valor)
    {
        $sql = "UPDATE `productos` SET `$atributo` = {$valor} WHERE `cod`={$this->cod}";
        $this->con->sql($sql);
    }

    function listMeli($filter, $order, $limit)
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

        $sql = "SELECT cod FROM `productos` $filterSql ORDER BY $orderSql $limitSql";
        $producto = $this->con->sqlReturn($sql);
        if ($producto) {
            while ($row = mysqli_fetch_assoc($producto)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }

    /**
     ** Use API Model MERCADOLIBRE
     **/

    public function validateItem()
    {
        $url = 'https://api.mercadolibre.com/items/' . $this->meli;
        $response = $this->funciones->curl("", $url, '');
        $data = json_decode($response, true);
        if (is_array($data)) {
            if (isset($data["status"])) {
                if (is_numeric($data["status"])) {
                    $result = array("status" => false, "text" => "El código de producto en MercadoLibre ingresado es incorrecto.");
                    return $result;
                } else {
                    if ($_SESSION["user_id"] == $data["seller_id"]) {
                        if ($data['status'] != 'closed') {
                            $result = array("status" => true, "substatus" => true, "data" => $data);
                            return $result;
                        } else {
                            $result = array("status" => true, "substatus" => false, "text" => "El producto con este código se encuentra eliminado.");
                            return $result;
                        }
                    } else {
                        $result = array("status" => false, "text" => "El código ingresado es de otro usuario, usted no puedo modificarlo.");
                        return $result;
                    }
                }
            } else {
                $result = array("status" => false, "text" => "El código de producto en MercadoLibre ingresado es incorrecto.");
                return $result;
            }
        };
    }

    public function addMeli()
    {
        $meli = $this->funciones->curl("GET", "https://api.mercadolibre.com/sites/MLA/category_predictor/predict?title=" . $this->funciones->normalizar_meli($this->titulo) . "", "");
        $meli = json_decode($meli, true);
        if (empty($meli)) {
            $meli = $this->funciones->curl("GET", "https://api.mercadolibre.com/sites/MLA/category_predictor/predict?title=otros", "");
            $meli = json_decode($meli, true);
        }
        $meli_categoria = $meli["id"];

        $data = '{
            "title": ' . $this->titulo . ',
            "category_id": ' . $meli_categoria . ',
            "price": ' . $this->precio . ',
            "currency_id": "ARS",
            "available_quantity": ' . $this->stock . ',
            "buying_mode": "buy_it_now",
            "listing_type_id": "gold_pro",
            "condition": "new",
            "description": {"plain_text": ' . strip_tags($this->desarrollo) . '},
            "tags": [
            "immediate_payment"
            ],
            "video_id": "",
            "attributes": [
            {
            "id": "EAN",
            "value_name": "123212451323",
            },
            {
            "id": "ITEM_CONDITION",
            "name": "Condición del ítem",
            "value_id": "2230284",
            "value_name": "Nuevo",
            "value_struct": null,
            "attribute_group_id": "OTHERS",
            "attribute_group_name": "Otros"
            }
            ],
            "pictures": [' . $this->img . '{"source":"' . LOGO . '"}],
            "shipping": {
            "mode": "me2",
            "local_pick_up": true,
            "free_shipping": false,
            "free_methods": []
            }
            }';

        $meli = $this->funciones->curl("POST", "https://api.mercadolibre.com/items?access_token=" . $_SESSION["access_token"], $data);
        $meli = json_decode($meli, true);

        if (isset($meli)) {
            if (isset($meli['error'])) {
                if (!empty($meli['error'])) {
                    if (!empty($meli['cause'])) {
                        $error = array("status" => "false", "error" => $meli["cause"]);
                        return $error;
                    } else {
                        $error = array("status" => "false", "error" => $meli["error"]);
                        return $error;
                    }
                }
            } else {
                $meli_ = array("status" => "true", "data" => $meli);
                return $meli_;
            }
        }
    }

    public function editMeli()
    {
        $variations_array = json_decode($this->getVariationsMeli($this->meli));
        if (is_array($variations_array)) {
            foreach ($variations_array as $value) {
                $this->deleteVariationsMeli($this->meli, $value->id);
            }
        }

        if (empty($this->img)) {
            $this->img = '';
        }

        $data = '{
                "title": ' . $this->titulo . ',  
                "price": ' . $this->precio . ', 
                "available_quantity": ' . $this->stock . ',      
                "pictures": [' . $this->img . '{"source":"' . LOGO . '"}]
        }';
        $meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data);
        $meli = json_decode($meli, true);

        if (isset($meli)) {
            if (isset($meli['error'])) {
                if (!empty($meli['error'])) {
                    if (!empty($meli['cause'])) {
                        $error = array("status" => "false", "error" => $meli["cause"]);
                        return $error;
                    } else {
                        $error = array("status" => "false", "error" => $meli["error"]);
                        return $error;
                    }
                }
            } else {
                $meli_ = array("status" => "true", "data" => $meli);
                if ($meli['available_quantity'] > 0) {
                    if ($meli['status'] == 'paused') {
                        $this->activateMeli();
                    }
                }
                return $meli_;
            }
        }
    }

    public function viewMeli($id)
    {
        $meli = $this->funciones->curl("GET", "https://api.mercadolibre.com/items/$id?access_token=" . $_SESSION["access_token"], "");
        return json_decode($meli);
    }


    public function getVariationsMeli($id)
    {
        $meli = $this->funciones->curl("GET", "https://api.mercadolibre.com/items/$id/variations?access_token=" . $_SESSION["access_token"], "");
        return $meli;
    }

    public function deleteVariationsMeli($id, $variation)
    {
        $meli = $this->funciones->curl("DELETE", "https://api.mercadolibre.com/items/$id/variations/$variation?access_token=" . $_SESSION["access_token"], "");
        return $meli;
    }

    public function activateMeli()
    {
        $data_status = '{ "status":"active" }';
        $meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data_status);
        return $meli;
    }
    public function pauseMeli()
    {
        $data_status = '{ "status":"paused" }';
        $meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data_status);
        return $meli;
    }

    public function deleteMeli()
    {
        $data_status = '{ "status":"closed" }';
        //$data_delete = '{ "deleted":"true" }';
        $meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data_status);
        //$meli = $this->funciones->curl("PUT", "https://api.mercadolibre.com/items/$this->meli?access_token=" . $_SESSION["access_token"], $data_delete);
        return $meli;
    }

    public function viewProductMeli()
    {
        $sql = "SELECT * FROM `productos` WHERE  cod = {$this->cod} LIMIT 1";
        $productos = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($productos);
        $array = array("data" => $row);
        return $array;
    }
}