<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$carrito = new Clases\Carrito();
$carro = $carrito->return();

if (!empty($carro)) {
    $response = "<h3>Carrito</h3>";
    $response .= "<table class='table table-striped'>";
    $response .= "<thead>";
    $response .= "<th width='50%'>Nombre producto</th>";
    $response .= "<th>Precio Unidad</th>";
    $response .= "<th>Precio Total</th>";
    $response .= "<th></th>";
    $precio = $carrito->totalPrice();

    foreach ($carro as $key => $carroItem) {
        $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
        if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
            $clase = "text-bold";
            $none = "hidden";
        } else {
            $clase;
            $none = "";
        }
        $response .= "<tr>";
        $response .= "<td>";
        $response .= mb_strtoupper($carroItem["titulo"]);
        if (isset($carroItem["descuento"]["monto"])) {
            $response .= "<b class='descuento-monto'>" . $carroItem['descuento']['monto'] . "</b>";
        }
        $response .= "<span class='amount $none'>Cantidad: " . $carroItem['cantidad'] . "</span>";
        $response .= "</td>";
        $response .= "<td>";
        $response .= "<span class='amount $none'>$" . $carroItem['precio'] . "</span>";
        if (isset($carroItem["descuento"]["precio-antiguo"])) {
            $response .= "<span class='descuento-precio'>" . $carroItem["descuento"]["precio-antiguo"] . "</span>";
        }
        $response .= "</td>";
        $response .= "<td>";
        if ($carroItem["precio"] != 0) {
            $response .= "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
        } else {
            $response .= "¡Gratis!";
        }
        $response .= "</td>";
        $response .= "<td>";
        $url = URL;
        $response .= "<a href='$url/tienda?remover=$key'>";
        $response .= "<i class='fa fa-times' aria-hidden='true'></i>";
        $response .= "</a>";
        $response .= "</td>";
        $response .= "</tr>";
    }
    $response .= "</table>";
    $response .= "<table class='table table-striped'>";
    $response .= "<thead>";
    $response .= "<th width='80%'>TOTAL</th>";
    $response .= "<th width='20%' style='text-align: right;'>$" . $precio . "</th>";
    $response .= "</thead>";
    $response .= "</table>";
    $result = array("status" => true, "texto" => $response);
    echo json_encode($result);
} else {
    $response = "<div>";
    $response .= "<i class='fa fa-shopping-cart fs-70' style='color:gray'> Vacío</i>";
    $response .= "</div>";
    $result = array("status" => false, "texto" => $response);
    echo json_encode($result);
}