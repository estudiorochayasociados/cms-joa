<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$producto = new Clases\Productos();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$combinacion = new Clases\Combinaciones();
$detalleCombinacion = new Clases\DetalleCombinaciones();

$product = $funciones->antihack_mysqli(isset($_POST['product']) ? $_POST['product'] : '');
$amount = $funciones->antihack_mysqli(isset($_POST['amount']) ? $_POST['amount'] : '');

if (!empty($product)) {
    if (isset($_POST['combination'])) {
        $atributo->set("productoCod", $product);
        $atributosData = $atributo->list();
        $combinacion->set("codProducto", $product);
        $codOnlyComb = $combinacion->listFrontCart();

        $array = array();

        foreach ($atributosData as $atributosData_) {
            if (isset($_POST['atribute'][$atributosData_['atribute']['cod']])) {
                if ($_POST['atribute'][$atributosData_['atribute']['cod']] != '') {
                    array_push($array, $_POST['atribute'][$atributosData_['atribute']['cod']]);
                }
            }
        }
        asort($array);

        $resultValidate = 0;
        $combination = '';
        $implodeArray = implode(",", $array);

        foreach ($codOnlyComb as $key => $codOnly_) {
            asort($codOnly_["combination"]);
            $implodeCod = implode(",", $codOnly_["combination"]);
            if ($implodeArray === $implodeCod) {
                $resultValidate = 1;
                $combination = $key;
            }
        }

        if ($resultValidate === 1) {
            $carroEnvio = $carrito->checkEnvio();
            if ($carroEnvio != '') {
                $carrito->delete($carroEnvio);
            }

            $carroPago = $carrito->checkPago();
            if ($carroPago != '') {
                $carrito->delete($carroPago);
            }
            $producto->set("cod", $product);
            $productoData = $producto->view();

            $carrito->set("id", $productoData['data']['cod']);
            $carrito->set("cantidad", $amount);
            $carrito->set("titulo", $productoData['data']['titulo']);

            $opcion = '| ';
            foreach ($_POST['atribute'] as $key => $atrib) {
                $atributo->set("cod", $key);
                $titulo = $atributo->view()['atribute']['value'];
                $subatributo->set("cod", $atrib);
                $sub = $subatributo->view()['data']['value'];
                $opcion .= "<strong>$titulo: </strong>$sub | ";
            }

            $detalleCombinacion->set("codCombinacion", $combination);
            $detalleData = $detalleCombinacion->view();
            if (!empty($detalleData)) {
                if (isset($_SESSION["usuarios"])) {
                    if (!empty($_SESSION['usuarios'])) {
                        if ($_SESSION["usuarios"]["minorista"] == 1) {
                            $carrito->set("precio", $detalleData['precio']);
                        } else {
                            if (!empty($detalleData['mayorista'])) {
                                $carrito->set("precio", $detalleData['mayorista']);
                            } else {
                                $carrito->set("precio", $detalleData['precio']);
                            }
                        }
                    }else {
                        $carrito->set("precio", $detalleData['precio']);
                    }
                } else {
                    $carrito->set("precio", $detalleData['precio']);
                }

                $opciones = array("texto" => $opcion, "combinacion" => $detalleData);

                $carrito->set("stock", $detalleData['stock']);
                $carrito->set("peso", (int)$productoData['data']['peso']);
                $carrito->set("opciones", $opciones);

                if ($amount <= $detalleData['stock']) {
                    if ($carrito->add()) {
                        $result = array("status" => true);
                        echo json_encode($result);
                    } else {
                        $result = array("status" => false, "message" => "LO SENTIMOS NO CONTAMOS CON ESA CANTIDAD EN STOCK, COMPRUEBE SI YA POSEE ESTE PRODUCTO EN SU CARRITO.");
                        echo json_encode($result);
                    }
                } else {
                    $result = array("status" => false, "message" => "LO SENTIMOS NO CONTAMOS CON ESA CANTIDAD EN STOCK.");
                    echo json_encode($result);
                }
            } else {
                $result = array("status" => false, "message" => "Ocurrió un error, intente nuevamente.");
                echo json_encode($result);
            }
        } else {
            $result = array("status" => false, "message" => "LO SENTIMOS NO HAY PRODUCTOS CON ESOS ATRIBUTOS.");
            echo json_encode($result);
        }
    } else {
        $carroEnvio = $carrito->checkEnvio();
        if ($carroEnvio != '') {
            $carrito->delete($carroEnvio);
        }

        $carroPago = $carrito->checkPago();
        if ($carroPago != '') {
            $carrito->delete($carroPago);
        }
        $producto->set("cod", $product);
        $productoData = $producto->view();

        $carrito->set("id", $productoData['data']['cod']);
        $carrito->set("cantidad", $amount);
        $carrito->set("titulo", $productoData['data']['titulo']);

        if (isset($_POST['atribute'])) {
            $opcion = '| ';
            $atri;
            foreach ($_POST['atribute'] as $key => $atrib) {
                $atributo->set("cod", $key);
                $titulo = $atributo->view()['atribute']['value'];
                $subatributo->set("cod", $atrib);
                $sub = $subatributo->view()['data']['value'];
                $opcion .= "<strong>$titulo: </strong>$sub | ";
                $atri[] = array($titulo => $sub);
            }
            $opciones = array("texto" => $opcion, "subatributos" => $atri);
        } else {
            $opciones = '';
        }

        if (isset($_SESSION["usuarios"])) {
            if (!empty($_SESSION['usuarios'])){
                if ($_SESSION["usuarios"]["minorista"]==1) {
                    $carrito->set("precio", $productoData['data']['precio']);
                } else {
                    if (!empty($productoData['data']['precio_mayorista'])) {
                        $carrito->set("precio", $productoData['data']['precio_mayorista']);
                    } else {
                        $carrito->set("precio", $productoData['data']['precio']);
                    }
                }
            } else {
                $carrito->set("precio", $productoData['data']['precio']);
            }
        } else {
            $carrito->set("precio", $productoData['data']['precio']);
        }

        $carrito->set("stock", $productoData['data']['stock']);
        $carrito->set("peso", (int)$productoData['data']['peso']);
        $carrito->set("opciones", $opciones);
        if ($amount <= $productoData['data']['stock']) {
            if ($carrito->add()) {
                $result = array("status" => true);
                echo json_encode($result);
            } else {
                $result = array("status" => false, "message" => "LO SENTIMOS NO CONTAMOS CON ESA CANTIDAD EN STOCK, COMPRUEBE SI YA POSEE ESTE PRODUCTO EN SU CARRITO.");
                echo json_encode($result);
            }
        } else {
            $result = array("status" => false, "message" => "LO SENTIMOS NO CONTAMOS CON ESA CANTIDAD EN STOCK.");
            echo json_encode($result);
        }
    }
}