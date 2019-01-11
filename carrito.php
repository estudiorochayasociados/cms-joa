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
//Clases
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$categorias = new Clases\Categorias();
$banners = new Clases\Banner();
$carrito = new Clases\Carrito();
$envios = new Clases\Envios();
$pagos = new Clases\Pagos();
$carro = $carrito->return();
$carroEnvio = $carrito->checkEnvio();

?>
    <body id="bd" class="cms-index-index2 header-style2 prd-detail sns-products-detail1 cms-simen-home-page-v2 default cmspage">
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
                                    <a title="Go to Home Page" href="#">
                                        <i class="fa fa-home"></i>
                                        <span>Home</span>
                                    </a>
                                </li>
                                <li class="category3 last">
                                    <span>Tu carrito</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- AND BREADCRUMBS -->

    <!-- CONTENT -->
    <div id="sns_content" class="wrap layout-m">
        <div class="container">
            <div class="row">
                <div class="shoppingcart">
                    <div class="sptitle col-md-12">
                        <h3>Tu carrito</h3>
                    </div>
                    <div class="col-md-12">
                        <div class="envio">
                            <?php
                            $metodos_de_envios = $envios->list(array("peso >= " . $carrito->peso_final() . " OR peso = 0"));
                            if ($carroEnvio == '') {
                                echo "<h3>Seleccioná el envió que más te convenga:</h3>";
                                if (isset($_POST["envio"])) {
                                    if ($carroEnvio != '') {
                                        $carrito->delete($carroEnvio);
                                    }
                                    $envio_final = $_POST["envio"];
                                    $envios->set("cod", $envio_final);
                                    $envio_final_ = $envios->view();
                                    $carrito->set("id", "Envio-Seleccion");
                                    $carrito->set("cantidad", 1);
                                    $carrito->set("titulo", $envio_final_["titulo"]);
                                    $carrito->set("precio", $envio_final_["precio"]);
                                    $carrito->add();
                                    $funciones->headerMove(CANONICAL . "");
                                }
                                ?>
                                <form method="post" id="envio">
                                    <select name="envio" class="form-control" id="envio" onchange="this.form.submit()">
                                        <option value="" selected disabled>Elegir envío</option>
                                        <?php
                                        foreach ($metodos_de_envios as $metodos_de_envio_) {
                                            if ($metodos_de_envio_["precio"] == 0) {
                                                $metodos_de_envio_precio = "¡Gratis!";
                                            } else {
                                                $metodos_de_envio_precio = "$" . $metodos_de_envio_["precio"];
                                            }
                                            echo "<option value='" . $metodos_de_envio_["cod"] . "'>" . $metodos_de_envio_["titulo"] . " -> " . $metodos_de_envio_precio . "</option>";
                                        }
                                        ?>
                                    </select>
                                </form>
                                <hr/>
                                <?php
                            }
                            ?>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <th>PRODUCTO</th>
                            <th>PRECIO UNITARIO</th>
                            <th>CANTIDAD</th>
                            <th>TOTAL</th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($_POST["eliminarCarrito"])) {
                                $carrito->delete($_POST["eliminarCarrito"]);
                            }

                            $i = 0;
                            $precio = 0;
                            foreach ($carro as $key => $carroItem) {
                                $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
                                $opciones = @implode(" - ", $carroItem["opciones"]);
                                if ($carroItem["id"] == "Envio-Seleccion") {
                                    $clase = "text-bold";
                                    $none = "hidden";
                                } else {
                                    $clase;
                                    $none;
                                }
                                ?>
                                <tr class="<?= $clase ?>">
                                    <td><b><?= $carroItem["titulo"]; ?></b><br/><?= $opciones ?></td>
                                    <td><span class="<?= $none ?>"><?= "$" . $carroItem["precio"]; ?></span></td>
                                    <td><span class="<?= $none ?>"><?= $carroItem["cantidad"]; ?></span></td>
                                    <td>
                                        <?php
                                        if ($carroItem["precio"] != 0) {
                                            echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                                        } else {
                                            echo "¡Gratis!";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?= URL ?>/carrito.php?remover=<?= $key ?>"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <form class="col-md-4 hidden">
                        <div class="form-bd">
                            <h3>DISCOUNT CODES</h3>
                            <p class="formbd2">Enter your coupon code if you have one.</p>
                            <input class="styleip" type="text" value="" size="30"/>
                            <span class="style-bd">Apply coupon</span>
                        </div>
                    </form>
                    <form class="form-right pull-right col-md-6" method="post" action="<?= URL ?>/pagar">
                        <div class="form-bd">
                            <h3 class="mb-0">
                                <span class="text3">TOTAL:</span>
                                <span class="text4">$<?= number_format($carrito->precio_total(), "2", ",", "."); ?></span>
                            </h3>
                            <?php if ($carroEnvio == '') { ?>
                                <span class="style-bd" onclick="$('#envio').addClass('alert alert-danger');">¿CÓMO PEREFERÍS EL ENVÍO DEL PEDIDO?</span>
                                <p class="checkout text-bold">¡Necesitamos que nos digas como querés realizar <br/>tu envío para que lo tengas listo cuanto antes!</p>
                                <?php
                            } else {
                                $lista_pagos = $pagos->list(array(" estado = 0 "));
                                foreach ($lista_pagos as $pago) {
                                    ?>
                                    <div class="radioButtonPay mb-10">
                                        <input type="radio" id="<?= mb_strtoupper($pago["cod"]) ?>" name="metodos-pago" value="<?= mb_strtoupper($pago["cod"]) ?>">
                                        <label for="<?= mb_strtoupper($pago["cod"]) ?>"><b><?= mb_strtoupper($pago["titulo"]) ?></b></label>
                                    </div>
                                    <?php
                                }
                                ?>
                                <button type="submit" name="pagar" class="mb-40 btn btn-success">PAGAR EL CARRITO</button>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- AND CONTENT -->
<?php
$template->themeEnd();
?>