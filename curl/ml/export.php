<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos2();

$product = $funciones->antihack_mysqli(isset($_POST['product']) ? $_POST['product'] : '');

if (isset($_SESSION['access_token'])) {
    $producto->set("cod", $product);
    $productData = $producto->viewProductMeli();

    $precio = number_format(($productData['data']["precio"] * 0.80), 2, ".", "");
    $producto->set("titulo", $productData['data']['titulo']);
    $producto->set("precio", $precio);
    $producto->set("stock", $productData['data']['stock']);
    $producto->set("desarrollo", $productData['data']["titulo"] . '\n Hacemos envíos a todo el país, y recibimos todas las tarjeta.\n Consultanos por stock y precio de tu producto.\n Trabajamos con una alta responsabilidad, que nos respaldan nuestros compradores.\n Pinturerias Ariel.\n San Francisco, Córdoba.\n Encontranos en nuestro sistema web');
    $cod_producto = str_replace("/", "-", $productData['data']["cod_producto"]);
    $categoria = explode("/", $productData['data']["cod_producto"]);
    $producto->img = '{"source":"http://www.pintureriasariel.com.ar/assets/archivos/img_productos/' . $categoria[0] . '/' . $cod_producto . '.jpg"},';
    $success = '';
    $error = '';

    if (!empty($productData['data']["meli"])) {
        $producto->meli = $productData['data']["meli"];
        $data = $producto->viewMeli($productData['data']['meli']);
        $response = json_decode(json_encode($data), true);

        if (isset($response['status'])) {
            switch ($response['status']) {
                case "closed":
                    $error .= "CÓDIGO ML: " . $productData['data']['meli'] . "<br>";
                    if (!empty($cod_producto)) {
                        $error .= "CÓDIGO: " . $cod_producto . "<br>";
                    }
                    $error .= "TITULO: " . $productData['data']['titulo'] . "<br>";
                    $error .= "ERROR MESSAGE: ESTE PRODUCTO FUE ELIMINADO DE MERCADOLIBRE<br>";
                    break;
                default:
                    if ($productData['data']['stock'] > 0) {
                        $producto->activateMeli();
                        $edit_meli = $producto->editMeli();
                        if (isset($edit_meli['error'])) {
                            $error .= "CÓDIGO ML: " . $productData['data']['meli'] . "<br>";
                            if (!empty($cod_producto)) {
                                $error .= "CÓDIGO: " . $cod_producto . "<br>";
                            }
                            $error .= "TITULO: " . $productData['data']['titulo'] . "<br>";
                            if (is_array($edit_meli['error'])) {
                                foreach ($edit_meli['error'] as $err) {
                                    $error .= "ERROR MESSAGE: " . $err['message'] . "<br>";
                                }
                            } else {
                                $error .= "ERROR MESSAGE: " . $edit_meli['error'] . "<br>";
                            }
                        } else {
                            if ($edit_meli['status']) {
                                $success .= "Se editó correctamente el producto: <br>";
                                $success .= "CÓDIGO ML: " . $productData['data']['meli'] . "<br>";
                                if (!empty($cod_producto)) {
                                    $success .= "CÓDIGO: " . $cod_producto . "<br>";
                                }
                                $success .= $productData['data']['titulo'];
                            }
                        }
                    } else {
                        $producto->pauseMeli();
                        $producto->stock=0;
                        $edit_meli = $producto->editMeli();
                        if (isset($edit_meli['error'])) {
                            $error .= "CÓDIGO ML: " . $productData['data']['meli'] . "<br>";
                            if (!empty($cod_producto)) {
                                $error .= "CÓDIGO: " . $cod_producto . "<br>";
                            }
                            $error .= "TITULO: " . $productData['data']['titulo'] . "<br>";
                            if (is_array($edit_meli['error'])) {
                                foreach ($edit_meli['error'] as $err) {
                                    $error .= "ERROR MESSAGE: " . $err['message'] . "<br>";
                                }
                            } else {
                                $error .= "ERROR MESSAGE: " . $edit_meli['error'] . "<br>";
                            }
                        } else {
                            if ($edit_meli['status']) {
                                $success .= "Se editó correctamente el producto: <br>";
                                $success .= "CÓDIGO ML: " . $productData['data']['meli'] . "<br>";
                                if (!empty($cod_producto)) {
                                    $success .= "CÓDIGO: " . $cod_producto . "<br>";
                                }
                                $success .= $productData['data']['titulo'];
                            }
                        }
                    }
                    break;
            }
        }
    } else {
        if ($productData['data']['stock'] > 0) {
            $add_meli = $producto->addMeli();

            if (isset($add_meli['data']["id"])) {
                $producto->set("cod", $productData['data']['cod']);
                $producto->editSingle("meli", "'" . $add_meli['data']["id"] . "'");
            }

            if (!empty($add_meli['error'])) {
                if (!empty($cod_producto)) {
                    $error .= "CÓDIGO: " . $cod_producto . "<br>";
                }
                $error .= "TITULO: " . $productData['data']['titulo'] . "<br>";
                if (is_array($add_meli['error'])) {
                    foreach ($add_meli['error'] as $err) {
                        $error .= "ERROR MESSAGE: " . $err['message'] . "<br>";
                    }
                } else {
                    $error .= "ERROR MESSAGE: " . $add_meli['error'] . "<br>";
                }
            }
        } else {
            if (!empty($cod_producto)) {
                $error .= "CÓDIGO: " . $cod_producto . "<br>";
            }
            $error .= "TITULO: " . $productData['data']['titulo'] . "<br>";
            $error .= "No se puede publicar este producto porque su stock es 0. <br>";
        }
    }

    if (!empty($error)) {
        $result = array("response" => true, "status" => false, "text" => $error);
        echo json_encode($result);
    } else {
        if (!empty($success)) {
            $result = array("response" => true, "status" => true, "text" => $success);
            echo json_encode($result);
        } else {
            if (!empty($cod_producto)) {
                $success .= "CÓDIGO: " . $cod_producto . "<br>";
            }
            $success .= $productData['data']['titulo'] . ": Se subió correctamente";
            $result = array("response" => true, "status" => true, "text" => $success);
            echo json_encode($result);
        }
    }
} else {
    $result = array("response" => false);
    echo json_encode($result);
}

