<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();


$template->set("title", "Compra finalizada");
$template->set("description", "Compra finalizada");
$template->set("keywords", "Compra finalizada");
$template->set("favicon", LOGO);
$template->themeInit();
$estado_get = isset($_GET["estado"]) ? $_GET["estado"] : '';
$pedidos = new Clases\Pedidos();
$carritos = new Clases\Carrito();
$contenido = new Clases\Contenidos();
$correo = new Clases\Email();
$cod_pedido = $_SESSION["cod_pedido"];
$pedidos->set("cod", $cod_pedido);
$pedido_info = $pedidos->info();

if (count($_SESSION["carrito"]) == 0) {
    //$funciones->headerMove(URL . "/index");
}

if ($estado_get != '') {
    $pedidos->set("estado", $estado_get);
    $pedidos->cambiar_estado();
    $pedidos->set("cod", $cod_pedido);
    $pedido_info = $pedidos->info();
}

switch ($pedido_info["estado"]) {
    case 0:
        $estado = "CARRITO NO CERRADO";
        break;
    case 1:
        $estado = "PENDIENTE";
        break;
    case 2:
        $estado = "APROBADO";
        break;
    case 3:
        $estado = "RECHAZADO";
        break;

}

$carro = $carritos->return();
$carroTotal = 0;

//MENSAJE = ARMADO CARRITO
$mensaje_carro = '<table border="1" style="text-align:left;width:100%;font-size:13px !important"><thead><th>Nombre producto</th><th>Cantidad</th><th>Precio</th><th>Total</th></thead>';
foreach ($carro as $carroItemEmail) {
    $carroTotal += $carroItemEmail["cantidad"] * $carroItemEmail["precio"];
    $mensaje_carro .= '<tr><td>' . $carroItemEmail["titulo"] . '</td><td>' . $carroItemEmail["cantidad"] . '</td><td>' . $carroItemEmail["precio"] . '</td><td>' . $carroItemEmail["cantidad"] * $carroItemEmail["precio"] . '</td></tr>';
}
$mensaje_carro .= '<tr><td></td><td></td><td></td><td>' . $carroTotal . '</td></tr>';
$mensaje_carro .= '</table>';

//MENSAJE = DATOS USUARIO COMPRADOR
$datos_usuario = "<b>Nombre y apellido:</b> " . $_SESSION["usuarios"]["nombre"] . "<br/>";
$datos_usuario .= "<b>Email:</b> " . $_SESSION["usuarios"]["email"] . "<br/>";
$datos_usuario .= "<b>Provincia:</b> " . $_SESSION["usuarios"]["provincia"] . "<br/>";
$datos_usuario .= "<b>Localidad:</b> " . $_SESSION["usuarios"]["localidad"] . "<br/>";
$datos_usuario .= "<b>Dirección:</b> " . $_SESSION["usuarios"]["direccion"] . "<br/>";
$datos_usuario .= "<b>Teléfono:</b> " . $_SESSION["usuarios"]["telefono"] . "<br/>";
if($_SESSION["usuarios"]["doc"] != '') {
    $datos_usuario .= "<b>SOLICITÓ FACTURA A CON EL CUIT:</b> " . $_SESSION["usuarios"]["doc"] . "<br/>";
    $pedidos->set("detalle","<b>SOLICITÓ FACTURA A CON EL CUIT:</b> " . $_SESSION["usuarios"]["doc"]);
    $pedidos->cambiar_valor("detalle");
}


//USUARIO EMAIL
$mensajeCompraUsuario = '¡Muchas gracias por tu nueva compra!<br/>En el transcurso de las 24 hs un operador se estará contactando con usted para pactar la entrega y/o pago del pedido. A continuación te dejamos el pedido que nos realizaste.<hr/> <h3>Pedido realizado:</h3>';
$mensajeCompraUsuario .= $mensaje_carro;
$mensajeCompraUsuario .= '<br/><hr/>';
$mensajeCompraUsuario .= '<h3>MÉTODO DE PAGO ELEGIDO: ' . mb_strtoupper($pedido_info["tipo"]) . '</h3>';
$mensajeCompraUsuario .= '<br/><hr/>';
$mensajeCompraUsuario .= '<h3>Tus datos:</h3>';
$mensajeCompraUsuario .= $datos_usuario;

$correo->set("asunto", "Muchas gracias por tu nueva compra");
$correo->set("receptor", $_SESSION["usuarios"]["email"]);
$correo->set("emisor", EMAIL);
$correo->set("mensaje", $mensajeCompraUsuario);
$correo->emailEnviar();

//ADMIN EMAIL
$mensajeCompra = '¡Nueva compra desde la web!<br/>A continuación te dejamos el detalle del pedido.<hr/> <h3>Pedido realizado:</h3>';
$mensajeCompra .= $mensaje_carro;
$mensajeCompra .= '<br/><hr/>';
$mensajeCompra .= '<h3>MÉTODO DE PAGO ELEGIDO: ' . mb_strtoupper($pedido_info["tipo"]) . '</h3>';
$mensajeCompra .= '<br/><hr/>';
$mensajeCompra .= '<h3>Datos de usuario:</h3>';
$mensajeCompra .= $datos_usuario;

$correo->set("asunto", "NUEVA COMPRA ONLINE");
$correo->set("receptor", EMAIL);
$correo->set("emisor", EMAIL);
$correo->set("mensaje", $mensajeCompra);
$correo->emailEnviar();
?>

    <body id="bd"
          class="cms-index-index2 header-style2 prd-detail sns-contact-us cms-simen-home-page-v2 default cmspage">
    <div id="sns_wrapper">
        <?php $template->themeNav(); ?>
        <!-- BREADCRUMBS -->
        <div id="sns_breadcrumbs" class="wrap">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div id="sns_titlepage"></div>
                        <div id="sns_pathway" class="clearfix">
                            <div class="pathway-inner">
                                <span class="icon-pointer "></span>
                                <ul class="breadcrumbs">
                                    <li class="home">
                                        <a href="<?= URL . '/index' ?>">
                                            <i class="fa fa-home"></i>
                                            <span>Inicio</span>
                                        </a>
                                    </li>
                                    <li class="category3 last">
                                        <span>¡Compra finalizada!</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END BREADCRUMBS -->
        <div id="sns_content" class="wrap layout-m">
            <div class="container">
                <div class="ps-404">
                    <div class="container">
                        <div class="well well-lg pt-50 pb-50">
                            <h2>COMPRA FINALIZADA
                                <hr/>
                                CÓDIGO: <span> <?= $cod_pedido ?></span></h2>
                            <p>
                                <b>Estado:</b> <?= $estado ?><br/>
                                <b>Método de pago:</b> <?= mb_strtoupper($pedido_info["tipo"]); ?>
                            </p>
                            <table class="table table-hover text-left">
                                <thead>
                                <th><b>PRODUCTO</b></th>
                                <th><b>PRECIO UNITARIO</b></th>
                                <th><b>CANTIDAD</b></th>
                                <th><b>TOTAL</b></th>
                                </thead>
                                <tbody>
                                <?php
                                $precio = 0;
                                foreach ($carro as $carroItem) {
                                    $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
                                    if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
                                        $clase = "text-bold";
                                        $none = "hidden";
                                    } else {
                                        $clase = '';
                                        $none = '';
                                    }
                                    ?>
                                    <tr class="<?= $clase ?>">
                                        <td><?= $carroItem["titulo"]; ?></td>
                                        <?php
                                        if ($carroItem["precio"] != 0) {
                                            ?>
                                            <td><span class="<?= $none ?>"><?= "$" . $carroItem["precio"]; ?></span>
                                            </td>
                                            <td><span class="<?= $none ?>"><?= $carroItem["cantidad"]; ?></span></td>
                                            <td><?= "$" . ($carroItem["precio"] * $carroItem["cantidad"]); ?></td>
                                            <?php
                                        } else {
                                            echo '<td></td><td></td>';
                                            echo "<td>¡Gratis!</td>";
                                        }
                                        ?>

                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <td><h3>TOTAL</h3></td>
                                    <td></td>
                                    <td></td>
                                    <td><h3>$<?= number_format($precio, "2", ",", ".") ?></h3></td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>

<?php
$carritos->destroy();
unset($_SESSION["cod_pedido"]);
$template->themeEnd();
?>