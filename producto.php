<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
//Clases
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$categorias = new Clases\Categorias();
$banners = new Clases\Banner();
$carrito = new Clases\Carrito();
//Productos

$id = $funciones->antihack_mysqli(isset($_GET["id"]) ? $_GET["id"] : '');
$productos->set("id", $id);
$productData = $productos->view();

$filter = array("categoria ='" . $productData['categoria'] . "'");
$productDataRel = $productos->list_with_options($filter, '', '0,12');
if (($key = array_search($productData, $productDataRel)) !== false) {
    unset($productDataRel[$key]);
}
//
//Banners
$categoriasData = $categorias->list('');
foreach ($categoriasData as $val) {

    if ($val['titulo'] == 'Side' && $val['area'] == 'banners') {
        $banners->set("categoria", $val['cod']);
        $banDataSide = $banners->listForCategory();
    }
}

$carro = $carrito->return();
$carroEnvio = $carrito->checkEnvio();
$carroPago = $carrito->checkPago();

$template->set("title", ucfirst($productData['titulo']));
$template->set("description", $productData['description']);
$template->set("keywords", $productData['keywords']);
$template->set("favicon", LOGO);
$template->themeInit();
//
?>
    <body id="bd" class="cms-index-index2 header-style2 prd-detail sns-products-detail1 cms-simen-home-page-v2 default cmspage">
    <div id="sns_wrapper">
        <?php $template->themeNav(); ?>

        <!-- BREADCRUMBS -->
        <div id="sns_breadcrumbs" class="wrap mb-20">
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
                                        <span><?= ucfirst($productData['titulo']); ?></span>
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
        <div id="sns_content" class="wrap layout-m ">
            <div class="container">
                <div class="row">
                    <div id="sns_main" class="col-md-12 col-main mb-60">
                        <div id="sns_mainmidle">
                            <div class="product-view sns-product-detail">
                                <div class="product-essential clearfix">
                                    <div class="row row-img">

                                        <div class="product-img-box col-md-4 col-sm-5">
                                            <?php $cod_productoExp = explode("/", $productData['cod_producto']); ?>
                                            <?php $cod_productoRemp = str_replace("/", "-", $productData['cod_producto']); ?>
                                            <?php $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                            <?php if ($funciones->fileExists($urlImg) === true) { ?>
                                                <?php $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                            <?php } else { ?>
                                                <?php $rutaImg = URL . '/assets/archivos/sin_imagen.jpg'; ?>
                                            <?php } ?>
                                            <div class="detail-img">
                                                <img id="imgFront" src="<?= $rutaImg; ?>" alt="<?= $productData['titulo']; ?>">
                                            </div>

                                        </div>
                                        <div id="product_shop" class="product-shop col-md-8 col-sm-7">
                                            <div class="item-inner product_list_style">
                                                <div class="item-info">
                                                    <div class="item-title">
                                                        <h1 class="fs-20"><?= ucfirst($productData['titulo']); ?></h1>
                                                    </div>
                                                    <div class="item-price">
                                                        <div class="price-box">
                                                    <span class="regular-price">
                                                        <span class="price">
                                                             <?php
                                                             if (@$_SESSION["usuarios"]["descuento"] == 1) {
                                                                 if ($productData['precio'] != $productData['precio_mayorista']) {
                                                                     ?>
                                                                     <span class="precio1">$ <?= $productData['precio_mayorista']; ?></span>
                                                                     <span class="precio2">$ <?= $productData['precio']; ?></span>
                                                                     <?php
                                                                 } else {
                                                                     ?>
                                                                     <span class="precio1">$ <?= $productData['precio']; ?></span>
                                                                     <?php
                                                                 }
                                                             } else {
                                                                 ?>
                                                                 <span class="precio1">$ <?= $productData['precio']; ?></span>
                                                                 <?php
                                                             }
                                                             ?>
                                                    </span>
                                                    </span>
                                                        </div>
                                                    </div>
                                                    <div class="availability">
                                                        <?php
                                                        if ($productData['stock'] > 0) {
                                                            echo '<p class="style1">Unidades: Disponible</p>';
                                                        } else {
                                                            echo '<p class="style1">Unidades: No disponible</p>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="std">
                                                        <?php if ($productData['description'] != '') {
                                                            echo "<h5>Breve descripción</h5>" . $productData['description'];
                                                        }
                                                        ?>
                                                    </div>

                                                    <div class="actions">
                                                        <?php
                                                        if (isset($_POST["enviar"])) {
                                                            if ($carroEnvio != '') {
                                                                $carrito->delete($carroEnvio);
                                                            }

                                                            if ($carroPago != '') {
                                                                $carrito->delete($carroPago);
                                                            }

                                                            $carrito->set("id", $productData['id']);
                                                            $carrito->set("cantidad", $_POST["cantidad"]);
                                                            $carrito->set("titulo", $productData['titulo']);
                                                            if (($productData['precioDescuento'] <= 0) || $productData["precioDescuento"] == '') {
                                                                $carrito->set("precio", $productData['precio']);
                                                            } else {
                                                                $carrito->set("precio", $productData['precioDescuento']);
                                                            }


                                                            if (@$_SESSION["usuarios"]["descuento"] == 1) {
                                                                if ($productData['precio'] != $productData['precio_mayorista']) {
                                                                    $carrito->set("precio", $productData['precio_mayorista']);
                                                                } else {
                                                                    $carrito->set("precio", $productData['precio']);
                                                                }
                                                            } else {
                                                                $carrito->set("precio", $productData['precio']);
                                                            }

                                                            $carrito->add();
                                                            $funciones->headerMove(CANONICAL . "?success");
                                                        }
                                                        if (strpos(CANONICAL, "success") == true) {
                                                            echo "<div class='alert alert-success'>Agregaste un producto a tu carrito, querés <a href='" . URL . "/carrito'><b>pasar por caja</b></a> o <a href='" . URL . "/productos'><b>seguir comprando</b></a></div>";
                                                        }
                                                        ?>
                                                        <form method="post">
                                                            <label class="gfont" for="qty">Cantidad : </label>
                                                            <div class="qty-container">
                                                                <button class="qty-decrease" onclick="var qty_el = document.getElementById('qty'); var qty = qty_el.value; if( !isNaN( qty ) && qty > 1 ) qty_el.value--;return false;" type="button"></button>
                                                                <input id="qty" class="input-text qty" type="text" title="Qty" value="1" name="cantidad">
                                                                <button class="qty-increase" onclick="var qty_el = document.getElementById('qty'); var qty = qty_el.value; if( !isNaN( qty )) qty_el.value++;return false;" type="button"></button>
                                                            </div>
                                                            <button class="btn-cart" title="Add to Cart" name="enviar" data-id="qv_item_8">
                                                                Añadir
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <div>
                                                        <div class="mt-5 mb-50">
                                                            <!-- AddToAny BEGIN -->
                                                            <label class="mt-20"><b>Compartir en:</b></label>
                                                            <!-- AddToAny BEGIN -->
                                                            <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                                                                <a class="a2a_button_facebook"></a>
                                                                <a class="a2a_button_twitter"></a>
                                                                <a class="a2a_button_google_plus"></a>
                                                                <a class="a2a_button_pinterest"></a>
                                                                <a class="a2a_button_whatsapp"></a>
                                                                <a class="a2a_button_facebook_messenger"></a>
                                                            </div>
                                                            <script async src="https://static.addtoany.com/menu/page.js"></script>
                                                            <!-- AddToAny END -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bottom row">
                    <div class="2coloum-left">
                        <div id="sns_left" class="col-md-3">
                            <?php
                            if (count($banDataSide) >= 2) {
                                $banRandSide = $banDataSide[array_rand($banDataSide)];
                                $imagenes->set("cod", $banRandSide['cod']);
                                $imgRandSide = $imagenes->view();
                                $banners->set("id", $banRandSide['id']);
                                $value = $banRandSide['vistas'] + 1;
                                $banners->set("vistas", $value);
                                $banners->increaseViews();
                                ?>
                                <div class="block block-banner banner5">
                                    <a href="<?= $banRandSide['link'] ?>">
                                        <img src="<?= URL . '/' . $imgRandSide['ruta'] ?>" alt="<?= $banRandSide['nombre'] ?>">
                                    </a>
                                </div>
                                <?php
                                if (($key = array_search($banRandSide, $banDataSide)) !== false) {
                                    unset($banDataSide[$key]);
                                }
                                $banRandSide2 = $banDataSide[array_rand($banDataSide)];
                                $imagenes->set("cod", $banRandSide2['cod']);
                                $imgRandSide2 = $imagenes->view();
                                $banners->set("id", $banRandSide2['id']);
                                $value = $banRandSide2['vistas'] + 1;
                                $banners->set("vistas", $value);
                                $banners->increaseViews();
                                ?>
                                <div class="block block-banner banner5 mt-40">
                                    <a href="<?= $banRandSide2['link'] ?>">
                                        <img src="<?= URL . '/' . $imgRandSide2['ruta'] ?>" alt="<?= $banRandSide2['nombre'] ?>">
                                    </a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div id="sns_mainm" class="col-md-9 mt-20">
                            <?php if ($productData['desarrollo'] != '') { ?>
                                <div id="sns_description" class="description mt-15">
                                    <div class="sns_producttaps_wraps1">
                                        <h3 class="detail-none">Descripción
                                            <i class="fa fa-align-justify"></i>
                                        </h3>
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="active style-detail"><a aria-controls="home" role="tab" data-toggle="tab">Descripción</a></li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane active" id="home">
                                                <div class="style1">
                                                    <p class="top">
                                                        <?= $productData['desarrollo']; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="products-upsell mt-40">
                                <div class="detai-products1">
                                    <div class="title visible-md visible-lg">
                                        <h3>Productos relacionados</h3>
                                    </div>
                                    <div class="title visible-xs">
                                        <h3>Relacionados</h3>
                                    </div>
                                    <div class="products-grid">
                                        <div id="related_upsell" class="item-row owl-carousel owl-theme" style="display: inline-block">
                                            <?php
                                            foreach ($productDataRel as $rel) {
                                                $productosRel1 = $productDataRel[array_rand($productDataRel)];
                                                ?>
                                                <?php $cod_productoExp = explode("/", $productosRel1['cod_producto']); ?>
                                                <?php $cod_productoRemp = str_replace("/", "-", $productosRel1['cod_producto']); ?>
                                                <?php $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                <?php if ($funciones->fileExists($urlImg) === true) { ?>
                                                    <?php $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                <?php } else { ?>
                                                    <?php $rutaImg = URL . '/assets/archivos/sin_imagen.jpg'; ?>
                                                <?php } ?>
                                                <div class="item">
                                                    <div class="item-inner">
                                                        <div class="prd">
                                                            <div class="item-img clearfix">
                                                                <a class="product-image have-additional" href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosRel1['titulo']) . "/" . $productosRel1['id'] ?>">
                                                                    <span class="img-main" style="height:200px;background:url(<?= $rutaImg; ?>) no-repeat center center/contain;">
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <div class="item-info">
                                                                <div class="info-inner">
                                                                    <div class="item-title">
                                                                        <a href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosRel1['titulo']) . "/" . $productosRel1['id'] ?>">
                                                                            <?= ucfirst($productosRel1['titulo']) ?>
                                                                        </a>
                                                                    </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box">
                                                                            <span class="regular-price">
                                                                                <span class="price">
                                                                                    <span class="precio1">$ <?= $productosRel1['precio']; ?></span>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                if (($key = array_search($productosRel1, $productDataRel)) !== false) {
                                                    unset($productDataRel[$key]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    <!-- AND CONTENT -->
<?php
$template->themeEnd();
?>
<?php                                     