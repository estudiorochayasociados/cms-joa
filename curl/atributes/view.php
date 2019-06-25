<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$combinacion = new Clases\Combinaciones();
$detalleCombinacion = new Clases\DetalleCombinaciones();

$product = $funciones->antihack_mysqli(isset($_POST['cod']) ? $_POST['cod'] : '');
if (!empty($product)) {
    $producto->set("cod", $product);
    $productoData = $producto->view();
    if (!empty($productoData)) {
        $atributo->set("productoCod", $productoData['data']['cod']);
        $atributosData = $atributo->list();
        $response = '';
        if (!empty($atributosData)) {
            $combinacion->set("codProducto", $productoData['data']['cod']);
            $combinacionData = $combinacion->listByProductCod();
            if (!empty($combinacionData)) {
                $response .= "<hr>Este producto tiene las siguientes combinaciones:<br>";
                foreach ($combinacionData as $comb) {
                    foreach ($comb['combination'] as $comb_) {
                        $response .= $comb_['value'] . " | ";
                    }
                    $response .= "<strong>Precio: </strong>$" . $comb['detail']['precio'] . " <strong>Stock: </strong>" . $comb['detail']['stock'];
                    if ($comb['detail']['mayorista'] > 0) {
                        $response .= "<strong> Precio Mayorista:</strong> $" . $comb['detail']['mayorista'];
                    } else {
                        $response .= "<strong> Precio Mayorista:</strong> No Posee";
                    }
                }
                $response .= "<hr><br>";
            }
            $response .= "<form id='cartForm$product'>";
            $response .= "<input type='hidden' name='product' value='$product'>";
            if (!empty($combinacionData)) {
                $response .= "<input type='hidden' name='combination'>";
            }

            foreach ($atributosData as $atrib) {
                $response .= "<b>" . $atrib['atribute']['value'] . "</b>";
                $cod = $atrib['atribute']['cod'];
                $response .= "<select class='form-control' name='atribute[$cod]' required>";
                foreach ($atrib['atribute']['subatributes'] as $sub) {
                    $subCod = $sub['cod'];
                    $subVal = $sub['value'];
                    $response .= "<option value='$subCod'>$subVal</option>";
                }
                $response .= "</select>";
            }
            $response .= "<div class='mt-10'>";
            $response .= "<b>Cantidad: </b>";
            $response .= "<input type='number' id='amount' name='amount' min='1' class='form-control' value='1' required>";
            $response .= "</div>";
            $response .= "</form>";
            $result = array("status" => true, "response" => $response);
            echo json_encode($result);
        } else {
            $precio = $productoData['data']['precio'];
            $response .= "<form id='cartForm$product'>";
            $response .= "<input type='hidden' name='product' value='$product'>";
            $response .= "<div class='row'>";
            $response .= "<div class='col-md-6 mt-10'>";
            $response .= "<b>Precio: </b>";
            $response .= "<input type='text' readonly name='precio' class='form-control' value='$precio' required>";
            $response .= "</div>";
            $response .= "<div class='col-md-6 mt-10'>";
            $response .= "<b>Cantidad: </b>";
            $response .= "<input type='number' id='amount' name='amount' min='1' class='form-control' value='1' required>";
            $response .= "</div>";
            $response .= "</div>";
            $response .= "</form>";
            $result = array("status" => true, "response" => $response);
            echo json_encode($result);
        }
    } else {
        $result = array("status" => false, "message" => "Ocurrió un error, recarge la página.");
        echo json_encode($result);
    }
} else {
    $result = array("status" => false, "message" => "Ocurrió un error, recarge la página.");
    echo json_encode($result);
}