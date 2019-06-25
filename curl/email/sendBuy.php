<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runCurl();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
$pedido = new Clases\Pedidos();
$config = new Clases\Config();
$emailData = $config->viewEmail();

$codPedido = $funciones->antihack_mysqli(isset($_POST['cod']) ? $_POST['cod'] : '');

if (!empty($codPedido)) {
    $pedido->set("cod", $codPedido);
    $pedidoData = $pedido->view();
    if (!empty($pedidoData)) {
        $carroTotal = 0;
        $mensaje_carro = '<table border="1" style="text-align:left;width:100%;font-size:13px !important">';
        $mensaje_carro .= "<thead><th>Nombre producto</th><th>Cantidad</th><th>Precio</th><th>Total</th></thead>";
        foreach ($pedidoData['detail'] as $detail) {
            $unserialized = unserialize($detail['variable2']);
            if (!empty($unserialized) && isset($unserialized['cod'])) {
                $descuentoCod = $unserialized["cod"];
                $descuentoMonto = $unserialized["monto"];
                $descuentoPrecio = $unserialized["precio-antiguo"];
            } else {
                $descuentoCod = '';
                $descuentoMonto = '';
                $descuentoPrecio = '';
            }
            $opciones = '';
            if (!empty($detail['variable3'])) {
                $opciones = "<br>" . $detail['variable3'];
            }
            $carroTotal += $detail['cantidad'] * $detail['precio'];
            $mensaje_carro .= "<tr>";
            $mensaje_carro .= "<td>" . $detail['producto'] . " <b>" . $descuentoMonto . "</b>" . $opciones . "</td>";
            $mensaje_carro .= "<td>" . $detail["cantidad"] . "</td>";
            if ($detail['precio'] != 0) {
                $mensaje_carro .= "<td>" . $detail['precio'] . " <span style='text-decoration: line-through'>" . $descuentoPrecio . "</span></td>";
            } else {
                $mensaje_carro .= "<td></td>";
            }
            $mensaje_carro .= "<td>" . $detail['cantidad'] * $detail['precio'] . "</td>";
            $mensaje_carro .= "</tr>";

        }
        $mensaje_carro .= '<tr><td></td><td></td><td></td><td>' . $carroTotal . '</td></tr>';
        $mensaje_carro .= '</table>';

        //MENSAJE = DATOS USUARIO COMPRADOR
        $datos_usuario = "<b>Nombre y apellido:</b> " . $pedidoData['user']['data']["nombre"] . $pedidoData['user']['data']["apellido"] . "<br/>";
        $datos_usuario .= "<b>Email:</b> " . $pedidoData['user']['data']["email"] . "<br/>";
        $datos_usuario .= "<b>Provincia:</b> " . $pedidoData['user']['data']["provincia"] . "<br/>";
        $datos_usuario .= "<b>Localidad:</b> " . $pedidoData['user']['data']["localidad"] . "<br/>";
        $datos_usuario .= "<b>Dirección:</b> " . $pedidoData['user']['data']["direccion"] . "<br/>";
        $datos_usuario .= "<b>Teléfono:</b> " . $pedidoData['user']['data']["telefono"] . "<br/>";

        if ($pedidoData['data']["estado"] == 1 || $pedidoData['data']["estado"] == 2) {
            //USUARIO EMAIL
            $mensajeCompraUsuario = '¡Muchas gracias por tu nueva compra!<br/>';
            $mensajeCompraUsuario .= "En el transcurso de las 24 hs un operador se estará contactando con usted para pactar la entrega y/o pago del pedido. A continuación te dejamos el pedido que nos realizaste.<hr/>";
            $mensajeCompraUsuario .= "<h3>Pedido realizado:</h3>";
            $mensajeCompraUsuario .= $mensaje_carro;

            $mensajeCompraUsuario .= '<br/><hr/>';
            $mensajeCompraUsuario .= '<h3>MÉTODO DE PAGO ELEGIDO: ' . mb_strtoupper($pedidoData['data']["tipo"]) . '</h3>';
            $mensajeCompraUsuario .= '<h3>' . mb_strtoupper($pedidoData['data']["detalle"]) . '</h3>';
            $mensajeCompraUsuario .= '<br/><hr/>';
            $mensajeCompraUsuario .= '<h3>Tus datos:</h3>';
            $mensajeCompraUsuario .= $datos_usuario;

            $enviar->set("asunto", "Muchas gracias por tu nueva compra");
            $enviar->set("receptor", $pedidoData['user']['data']["email"]);
            $enviar->set("emisor", $emailData['data']['remitente']);
            $enviar->set("mensaje", $mensajeCompraUsuario);
            $enviar->emailEnviarCurl();

            //ADMIN EMAIL
            $mensajeCompra = '¡Nueva compra desde la web!<br/>A continuación te dejamos el detalle del pedido.<hr/> <h3>Pedido realizado:</h3>';
            $mensajeCompra .= $mensaje_carro;

            $mensajeCompra .= '<br/><hr/>';
            $mensajeCompra .= '<h3>MÉTODO DE PAGO ELEGIDO: ' . mb_strtoupper($pedidoData['data']["tipo"]) . '</h3>';
            $mensajeCompra .= '<h3>' . mb_strtoupper($pedidoData['data']["detalle"]) . '</h3>';
            $mensajeCompra .= '<br/><hr/>';
            $mensajeCompra .= '<h3>Datos de usuario:</h3>';
            $mensajeCompra .= $datos_usuario;

            $enviar->set("asunto", "NUEVA COMPRA ONLINE");
            $enviar->set("receptor", $emailData['data']['remitente']);
            $enviar->set("emisor", $emailData['data']['remitente']);
            $enviar->set("mensaje", $mensajeCompra);
            $enviar->emailEnviarCurl();
        }
    }
}