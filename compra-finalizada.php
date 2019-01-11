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

if ($estado_get != '') {
    $pedidos->set("estado", $estado_get);
     $pedidos->cambiar_estado();
}

$pedido_info = $pedidos->info();

switch ($pedido_info["estado"]) {
    case 1:
        $estado = "Pendiente";
        break;
    case 2:
        $estado = "Aprobado";
        break;
}

switch ($pedido_info["tipo"]) {
    case 0:
        $tipo = "Transferencia/Depósito Bancario";
        break;
    case 1:
        $tipo = "Coordinar con vendedor";
        break;
    case 2:
        $tipo = "Tarjeta de crédito/tarjeta de deébito";
        break;
}

$carro = $carritos->return();
$carroTotal = 0;

//USUARIO EMAIL
$mensajeCompraUsuario = '¡Muchas gracias por tu nueva compra!<br/>En el transcurso de las 24 hs un operador se estará contactando con usted para pactar la entrega y/o pago del pedido. A continuación te dejamos el pedido que nos realizaste.<hr/> <h3>Pedido realizado:</h3>';
$mensajeCompraUsuario .= '<table border="1" style="text-align:left;width:100%;font-size:13px !important"><thead><th>Nombre producto</th><th>Cantidad</th><th>Precio</th><th>Total</th></thead>';
foreach ($carro as $carroItemEmail):
    $carroTotal += $carroItemEmail["cantidad"] * $carroItemEmail["precio"];
    $mensajeCompraUsuario .= '<tr><td>' . $carroItemEmail["titulo"] . '</td><td>' . $carroItemEmail["cantidad"] . '</td><td>' . $carroItemEmail["precio"] . '</td><td>' . $carroItemEmail["cantidad"] * $carroItemEmail["precio"] . '</td></tr>';
endforeach;
$mensajeCompraUsuario .= '<tr><td></td><td></td><td></td><td>' . $carroTotal . '</td></tr>';
$mensajeCompraUsuario .= '</table>';
if ($pedido_info["tipo"] == 0):
    $mensajeCompraUsuario .= '<br/><hr/>';
    $mensajeCompraUsuario .= '<h3>Datos de cuenta bancaria:</h3>';
    $contenido->set("id", 9);
    $contenidoData = $contenido->view();
    $mensajeCompraUsuario .= $contenidoData['contenido'];
endif;
$mensajeCompraUsuario .= '<br/><hr/>';
$mensajeCompraUsuario .= '<h3>Tipo de pago:</h3>';
$mensajeCompraUsuario .= '<b>' . $tipo . '</b>';
$mensajeCompraUsuario .= '<br/><hr/>';
$mensajeCompraUsuario .= '<h3>Tus datos:</h3>';
$mensajeCompraUsuario .= "<b>Nombre y apellido</b>: " . $_SESSION["usuarios"]["nombre"] . "<br/>";
$mensajeCompraUsuario .= "<b>Email</b>: " . $_SESSION["usuarios"]["email"] . "<br/>";
$mensajeCompraUsuario .= "<b>País</b>: " . $_SESSION["usuarios"]["pais"] . "<br/>";
$mensajeCompraUsuario .= "<b>Provincia</b>: " . $_SESSION["usuarios"]["provincia"] . "<br/>";
$mensajeCompraUsuario .= "<b>Localidad</b>: " . $_SESSION["usuarios"]["localidad"] . "<br/>";
$mensajeCompraUsuario .= "<b>Dirección</b>: " . $_SESSION["usuarios"]["direccion"] . "<br/>";
$mensajeCompraUsuario .= "<b>Teléfono</b>: " . $_SESSION["usuarios"]["telefono"] . "<br/>";

$correo->set("asunto", "MUCHAS GRACIAS POR TU COMPRA");
$correo->set("receptor", $_SESSION["usuarios"]["email"]);
$correo->set("emisor", EMAIL);
$correo->set("mensaje", $mensajeCompraUsuario);
$correo->emailEnviar();

//ADMIN EMAIL
$mensajeCompra = '¡Nueva compra desde la web!<br/>A continuación te dejamos el detalle del pedido.<hr/> <h3>Pedido realizado:</h3>';
$mensajeCompra .= '<table border="1" style="text-align:left;width:100%;font-size:13px !important"><thead><th>Nombre producto</th><th>Cantidad</th><th>Precio</th><th>Total</th></thead>';
foreach ($carro as $carroItemEmail):
    $carroTotal += $carroItemEmail["cantidad"] * $carroItemEmail["precio"];
    $mensajeCompra .= '<tr><td>' . $carroItemEmail["titulo"] . '</td><td>' . $carroItemEmail["cantidad"] . '</td><td>' . $carroItemEmail["precio"] . '</td><td>' . $carroItemEmail["cantidad"] * $carroItemEmail["precio"] . '</td></tr>';
endforeach;
$mensajeCompra .= '<tr><td></td><td></td><td></td><td>' . $carroTotal . '</td></tr>';
$mensajeCompra .= '</table>';
$mensajeCompra .= '<br/><hr/>';
$mensajeCompra .= '<h3>Tipo de pago:</h3>';
$mensajeCompra .= '<b>' . $tipo . '</b>';
$mensajeCompra .= '<br/><hr/>';
$mensajeCompra .= '<h3>Datos de usuario:</h3>';
$mensajeCompra .= "<b>Nombre y apellido</b>: " . $_SESSION["usuarios"]["nombre"] . "<br/>";
$mensajeCompra .= "<b>Email</b>: " . $_SESSION["usuarios"]["email"] . "<br/>";
$mensajeCompra .= "<b>País</b>: " . $_SESSION["usuarios"]["pais"] . "<br/>";
$mensajeCompra .= "<b>Provincia</b>: " . $_SESSION["usuarios"]["provincia"] . "<br/>";
$mensajeCompra .= "<b>Localidad</b>: " . $_SESSION["usuarios"]["localidad"] . "<br/>";
$mensajeCompra .= "<b>Dirección</b>: " . $_SESSION["usuarios"]["direccion"] . "<br/>";
$mensajeCompra .= "<b>Teléfono</b>: " . $_SESSION["usuarios"]["telefono"] . "<br/>";

$correo->set("asunto", "COMPRA WEB");
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
                                <b>Método de pago:</b> <?= $tipo ?>
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
                                    if ($carroItem["id"] == "Envio-Seleccion") {
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
                            <?php if ($pedido_info["tipo"] == 0): ?>
                                <br/>
                                <b>CUENTA BANCARIA: </b><br/>
                                <?php $contenido->set("id", 9); ?>
                                <?php $contenidoData = $contenido->view(); ?>
                                <?= $contenidoData['contenido'] ?>
                            <?php endif; ?>
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