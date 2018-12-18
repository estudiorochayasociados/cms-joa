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
$id = isset($_GET["id"]) ? $_GET["id"] : '';
$productos->set("id", $id);
$productData = $productos->view();
$imagenes->set("cod", $productData['cod']);
$imagenesData = $imagenes->listForProduct();
$filter = array("categoria ='" . $productData['categoria'] . "'");
$productDataRel = $productos->listWithOps($filter, '', '');
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

$template->set("title", "Pinturería Ariel | " . ucfirst($productData['titulo']));
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
        <div id="sns_content" class="wrap layout-m">
            <div class="container">
                <div class="row">
                    <div id="sns_main" class="col-md-12 col-main">
                        <div id="sns_mainmidle">
                            <div class="product-view sns-product-detail">
                                <div class="product-essential clearfix">
                                    <div class="row row-img">

                                        <div class="product-img-box col-md-4 col-sm-5">
                                            <?php
                                            if (count($imagenesData) > 1) {
                                                ?>
                                                <div class="detail-img">
                                                    <img id="imgFront" src="<?= URL . '/' . $imagenesData[0]['ruta'] ?>" alt="<?= $productData['titulo']; ?>">
                                                </div>
                                                <div class="small-img">
                                                    <div id="sns_thumbail" class="owl-carousel owl-theme">
                                                        <?php
                                                        foreach ($imagenesData as $imgM) {
                                                            ?>
                                                            <div class="item" style="background:url('<?= URL . '/' . $imgM['ruta'] ?>') no-repeat center center/contain;height:100px;width:100px;overflow:hidden" onclick="$('#imgFront').attr('src','<?= URL . '/' . $imgM['ruta'] ?>')"></div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="detail-img">
                                                    <img src="<?= URL . '/assets/archivos/sin_imagen.jpg' ?>" alt="<?= $productData['titulo']; ?>">
                                                </div>
                                                <?php
                                            }
                                            ?>

                                        </div>
                                        <div id="product_shop" class="product-shop col-md-8 col-sm-7">
                                            <div class="item-inner product_list_style">
                                                <div class="item-info">
                                                    <div class="item-title">
                                                        <a><?= ucfirst($productData['titulo']); ?></a>
                                                    </div>
                                                    <div class="item-price">
                                                        <div class="price-box">
                                                    <span class="regular-price">
                                                        <span class="price">
                                                            <?php
                                                            if ($productData['precioDescuento'] > 0) {
                                                                ?>
                                                                <span class="precioD1">$ <?= $productData['precioDescuento']; ?></span>
                                                                <span class="precioD2">$ <?= $productData['precio']; ?></span>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <span class="precioD1">$ <?= $productData['precio']; ?></span>
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
                                                    <div class="desc std">
                                                        <h5>Breve descripción</h5>
                                                        <p><?= $productData['description']; ?></p>
                                                    </div>

                                                    <div class="actions">
                                                        <?php
                                                        if (isset($_POST["enviar"])) {
                                                            if ($carroEnvio != '') {
                                                                $carrito->delete($carroEnvio);
                                                            }

                                                            $carrito->set("id", $productData['id']);
                                                            $carrito->set("cantidad", $_POST["cantidad"]);
                                                            $carrito->set("titulo", $productData['titulo']);
                                                            if (($productData['precioDescuento'] <= 0) || $productData["precioDescuento"] == '') {
                                                                $carrito->set("precio", $productData['precio']);
                                                            } else {
                                                                $carrito->set("precio", $productData['precioDescuento']);
                                                            }
                                                            $carrito->add();
                                                            $funciones->headerMove(CANONICAL . "?success");
                                                        }
                                                        if (strpos(CANONICAL, "success") == true) {
                                                            echo "<div class='alert alert-success'>Agregaste un producto a tu carrito, querés <a href='" . URL . "/carrito'>pasar por caja</a> o <a href='" . URL . "/productos'>seguir comprando</a></div>";
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
                                                        <!--
                                                <div style="display: inline-block;">
                                                    <div class="fb-share-button" data-href="<?= URL . '/producto/' . $funciones->normalizar_link($producData['titulo']) . "/" . $productData['id'] ?>" data-layout="button" data-size="large" data-mobile-iframe="false"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.estudiorochayasoc.com%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
                                                </div>
                                                <div  style="display: inline-block;">
                                                    <a class="twitter-share-button" data-size="large"
                                                       href="https://twitter.com/intent/tweet">
                                                        Tweet</a>
                                                </div>-->
                                                        <div class="mt-5">
                                                            <!-- AddToAny BEGIN -->
                                                            <label>Compartir en:</label>
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
                                <div class="block block-banner banner5">
                                    <a href="<?= $banRandSide2['link'] ?>">
                                        <img src="<?= URL . '/' . $imgRandSide2['ruta'] ?>" alt="<?= $banRandSide2['nombre'] ?>">
                                    </a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div id="sns_mainm" class="col-md-9 mt-20">
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
                            <div class="products-upsell">
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
                                                $imagenes->set("cod", $productosRel1['cod']);
                                                $imgProRel1 = $imagenes->view();
                                                ?>
                                                <div class="item">
                                                    <div class="item-inner">
                                                        <div class="prd">
                                                            <div class="item-img clearfix">
                                                                <div class="ico-label">
                                                                    <?php
                                                                    if ($productosRel1['precioDescuento'] > 0) {
                                                                        ?>
                                                                        <span class="ico-product ico-sale">Promo</span>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <a class="product-image have-additional" href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosRel1['titulo']) . "/" . $productosRel1['id'] ?>">
                                                                    <span class="img-main" style="height:200px;background:url(<?= URL . '/' . $imgProRel1['ruta'] ?>) no-repeat center center/contain;">
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <div class="item-info">
                                                                <div class="info-inner">
                                                                    <div class="item-title">
                                                                        <a href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosRel1['titulo']) . "/" . $productosRel1['id'] ?>">
                                                                            <?= ucfirst($productosRel1['titulo']) ?> </a>
                                                                    </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box">
                                                                            <span class="regular-price">
                                                                                <span class="price">
                                                                                    <?php
                                                                                    if ($productosRel1['precioDescuento'] > 0) {
                                                                                        ?>
                                                                                        <span class="precio1">$ <?= $productosRel1['precioDescuento']; ?></span>
                                                                                        <span class="precio2">$ <?= $productosRel1['precio']; ?></span>
                                                                                        <?php
                                                                                    } else {
                                                                                        ?>
                                                                                        <span class="precio1">$ <?= $productosRel1['precio']; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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