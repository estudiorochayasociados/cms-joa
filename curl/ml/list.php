<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos2();

$type = $funciones->antihack_mysqli(isset($_POST['type']) ? $_POST['type'] : '1');

if (isset($_SESSION['access_token'])) {
    switch ($type) {
        //Todos
        default:
            $productos = $producto->listMeli(array("precio > 0"), "id ASC", "5");
            break;
        case 2:
            $productos = $producto->listMeli(array("precio > 0", "meli=''"), "id ASC", "");
            break;
        case 3:
            $productos = $producto->listMeli(array("precio > 0", "meli!=''"), "id ASC", "");
            break;
    }
    $response = array();
    foreach ($productos as $producto_) {
        $response[] = $producto_['data']['cod'];
    }
    $result = array("status" => true, "products" => $response);
    echo json_encode($result);
} else {
    $result = array("status" => false);
    echo json_encode($result);
}