<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$template->set("title", "Admin");
$template->set("description", "Admin");
$template->set("keywords", "Inicio");
$template->set("favicon", LOGO);
$template->themeInit();

$cod_pedido = isset($_GET["cod_pedido"]) ? $_GET["cod_pedido"] : '';
$tipo_pedido = isset($_GET["tipo_pedido"]) ? $_GET["tipo_pedido"] : '';
$carrito = new Clases\Carrito();
$pedidos = new Clases\Pedidos();
$pedidos->set("cod", $cod_pedido);
$pedido = $pedidos->view();
$usuarios = new Clases\Usuarios();
$usuarioSesion = $usuarios->view_sesion();
$carro = $carrito->return();
$precio = $carrito->precioFinal();
$pedidos = new Clases\Pedidos();
?>
    <body id="bd" class="cms-index-index2 header-style2 prd-detail sns-products-detail1 cms-simen-home-page-v2 default cmspage">
    <div id="sns_wrapper">
        <?php $template->themeNav(); ?>
    </div>
    <?php
    if (is_array($pedido)) {
        $pedidos->set("cod", $cod_pedido);
        $pedidos->delete();
        foreach ($carro as $carroItem) {
            $pedidos->set("cod", $cod_pedido);
            $pedidos->set("producto", $carroItem["titulo"]);
            $pedidos->set("cantidad", $carroItem["cantidad"]);
            $pedidos->set("precio", $carroItem["precio"]);
            $pedidos->set("estado", 0);
            $pedidos->set("tipo", $tipo_pedido);
            $pedidos->set("usuario", $usuarioSesion["cod"]);
            $pedidos->set("detalle", "");
            $pedidos->set("fecha", date('Y-d-m'));
            $pedidos->add();
        }
    } else {
        foreach ($carro as $carroItem) {
            $pedidos->set("cod", $cod_pedido);
            $pedidos->set("producto", $carroItem["titulo"]);
            $pedidos->set("cantidad", $carroItem["cantidad"]);
            $pedidos->set("precio", $carroItem["precio"]);
            $pedidos->set("estado", 0);
            $pedidos->set("tipo", $tipo_pedido);
            $pedidos->set("usuario", $usuarioSesion["cod"]);
            $pedidos->set("detalle", "");
            $pedidos->set("fecha", date('Y-d-m'));
            $pedidos->add();
        }
    }

    switch ($tipo_pedido) {
        case 0:
            //Transferencia o depósito bancario
            $pedidos->set("cod", $cod_pedido);
            $pedidos->set("estado", 1);
            $pedidos->cambiar_estado();
            $funciones->headerMove(URL . "/compra-finalizada.php");
            break;
        case 1:
            //Coordinar con el vendedor
            $pedidos->set("cod", $cod_pedido);
            $pedidos->set("estado", 1);
            $pedidos->cambiar_estado();
            $funciones->headerMove(URL . "/compra-finalizada.php");
            break;
        case 2:
            include("vendor/mercadopago/sdk/lib/mercadopago.php");
            $mp = new MP ("3087431389449841", "8V6jXmINfMLcpoEqV1cnVGQbEMnVwyjK");
            $preference_data = array(
                "items" => array(
                    array(
                        "id" => $cod_pedido,
                        "title" => "COMPRA CÓDIGO N°:" . $cod_pedido,
                        "quantity" => 1,
                        "currency_id" => "ARS",
                        "unit_price" => $precio
                    )
                ),
                "payer" => array(
                    "name" => $usuarioSesion["nombre"],
                    "surname" => $usuarioSesion["apellido"],
                    "email" => $usuarioSesion["email"]
                ),
                "back_urls" => array(
                    "success" => "/compra-finalizada.php",
                    "pending" => "/compra-finalizada.php",
                    "failure" => "/compra-finalizada.php"
                ),
                "external_reference" => $cod_pedido,
                "auto_return" => "all",
                //"client_id" => $usuarioSesion["cod"],
                "payment_methods" => array(
                    "excluded_payment_methods" => array(),
                    "excluded_payment_types" => array(
                        array("id" => "ticket"),
                        array("id" => "atm")
                    )
                )
            );
            $preference = $mp->create_preference($preference_data);
            //$funciones->headerMove($preference["response"]["sandbox_init_point"]);
            echo "<iframe src='" . $preference["response"]["sandbox_init_point"] . "' width='100%' height='700px'></iframe>";
            break;
    }
    ?>
    </body>
<?php
$template->themeEnd();
?>