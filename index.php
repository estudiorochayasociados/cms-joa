    <?php
     require_once "Config/Autoload.php";
    Config\Autoload::runSitio();
    $template = new Clases\TemplateSite();
    $funciones = new Clases\PublicFunction();
    $template->set("title", "Pinturería Ariel | Inicio");
    $template->set("description", "");
    $template->set("keywords", "");
    $template->set("favicon", LOGO);
    $template->themeInit();
    //Clases
    $productos = new Clases\Productos();
    $imagenes = new Clases\Imagenes();
    $categorias = new Clases\Categorias();
    $banners = new Clases\Banner();
    $slider = new Clases\Sliders();
    //Banners
    $categoriasData = $categorias->list('');
    foreach ($categoriasData as $val) {
        if ($val['titulo'] == 'Pie' && $val['area'] == 'banners') {
            $banners->set("categoria", $val['cod']);
            $banDataPie = $banners->listForCategory();
        }

        if ($val['titulo'] == 'Pie 1/2' && $val['area'] == 'banners') {
            $banners->set("categoria", $val['cod']);
            $banDataPieMedio = $banners->listForCategory();
        }

        if ($val['titulo'] == 'Side' && $val['area'] == 'banners') {
            $banners->set("categoria", $val['cod']);
            $banDataSide = $banners->listForCategory();
        }
        if ($val['area'] == 'sliders') {
            $slider->set("categoria", $val['cod']);
            $sliderData = $slider->listForCategory();
        }
    }
    //Productos
    $categorias->set("area", "productos");
    $categoriasParaProductos = $categorias->listForArea('');
    $productDataCenter1 = $productos->listWithOps('', ' RAND() ', '16');
    $productDataCenter2 = $productos->listWithOps('', ' RAND() ', '8');
    $productDataCenter3 = $productos->listWithOps('', ' RAND() ', '8');
    $productDataSide = $productos->listWithOps('', ' RAND() ', '4');
    $productDataRel = $productos->listWithOps('', ' RAND() ', '');

    ?>

        <body id="bd" class=" cms-index-index2 header-style2 cms-simen-home-page-v2 default cmspage">
         <div id="sns_wrapper">
            <?php $template->themeNav(); ?>
            <!-- CONTENT -->
            <div id="sns_content" class="wrap layout-m">
                <div class="container">
                    <!-- sns_left -->
                    <div class="row">
                        <?php
                        $template->themeSideIndex();
                        ?>
                        <!-- sns_main -->
                        <div id="sns_main" class="col-md-9 col-main">
                            <div id="sns_mainmidle">
                                <div id="sns_slideshow2">
                                    <div id="header-slideshow">
                                        <div class="row">
                                            <div class="slideshows col-md-8 col-sm-8">
                                                <?php
                                                if (count($sliderData) > 1) {
                                                    ?>
                                                    <div id="slider123" class="owl-carousel owl-theme"
                                                         style="overflow: hidden;width: 100% !important">
                                                        <?php
                                                        foreach ($sliderData as $sli) {
                                                            $imagenes->set("cod", $sli['cod']);
                                                            $imgCarousel = $imagenes->view();
                                                            ?>
                                                            <div class=" style1">
                                                                <img src="<?= URL . '/' . $imgCarousel['ruta'] ?>"
                                                                     alt="<?= $sli['titulo']; ?>">
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php

                                                } else {
                                                    ?>
                                                    <div style="overflow: hidden;width: 100% !important">
                                                        <?php
                                                        foreach ($sliderData as $sli) {
                                                            $imagenes->set("cod", $sli['cod']);
                                                            $imgCarousel = $imagenes->view();
                                                            ?>
                                                            <div class=" style1">
                                                                <img src="<?= URL . '/' . $imgCarousel['ruta'] ?>"
                                                                     alt="<?= $sli['titulo']; ?>">
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <!-- Banner -->
                                            <div class="banner-right col-md-4 col-sm-4">
                                                <div class="banner6 pdno col-md-12 col-sm-12">
                                                    <?php
                                                    if (count($banDataSide) >= 2) {
                                                        $banRandSide2 = $banDataSide[array_rand($banDataSide)];
                                                        $imagenes->set("cod", $banRandSide2['cod']);
                                                        $imgRandSide2 = $imagenes->view();
                                                        $banners->set("id", $banRandSide2['id']);
                                                        $value = $banRandSide2['vistas'] + 1;
                                                        $banners->set("vistas", $value);
                                                        $banners->increaseViews();
                                                        ?>
                                                        <div class="banner7 banner6  banner5 col-md-12 col-sm-12">
                                                            <a href="<?= $banRandSide2['link'] ?>">
                                                                <img src="<?= URL . '/' . $imgRandSide2['ruta'] ?>"
                                                                     alt="<?= $banRandSide2['nombre'] ?>">
                                                            </a>
                                                        </div>
                                                        <?php
                                                        if (($key = array_search($banRandSide2, $banDataSide)) !== false) {
                                                            unset($banDataSide[$key]);
                                                        }
                                                        $banRandSide3 = $banDataSide[array_rand($banDataSide)];
                                                        $imagenes->set("cod", $banRandSide3['cod']);
                                                        $imgRandSide3 = $imagenes->view();
                                                        $banners->set("id", $banRandSide3['id']);
                                                        $value = $banRandSide3['vistas'] + 1;
                                                        $banners->set("vistas", $value);
                                                        $banners->increaseViews();
                                                        ?>
                                                        <div class="banner8 banner6  banner5 col-md-12 col-sm-12">
                                                            <a href="<?= $banRandSide3['link'] ?>">
                                                                <img src="<?= URL . '/' . $imgRandSide3['ruta'] ?>"
                                                                     alt="<?= $banRandSide3['nombre'] ?>">
                                                            </a>
                                                        </div>
                                                        <?php
                                                        if (($key = array_search($banRandSide3, $banDataSide)) !== false) {
                                                            unset($banDataSide[$key]);
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <!-- End Banner -->
                                        </div>
                                    </div>
                                </div>


                                <div id="sns_producttaps1" class="sns_producttaps_wraps">
                                    <!-- Tab panes -->
                                    <div class="products-upsell">
                                        <div class="detai-products1">
                                            <div class="title">
                                                <h3>Productos más recientes</h3>
                                            </div>
                                            <div class="products-grid">
                                                <div id="related_upsell" class="item-row owl-carousel owl-theme" style="display: inline-block">
                                                    <?php
                                                    foreach ($productDataCenter1 as $productosCenter1) {
                                                        $cod_productoExp = explode("/", $productosCenter1['cod_producto']);
                                                        $cod_productoRemp = str_replace("/", "-", $productosCenter1['cod_producto']);
                                                        $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg';
                                                        if ($funciones->fileExists($urlImg) === true) {
                                                            $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg';
                                                        } else {
                                                            $rutaImg = URL . '/assets/archivos/sin_imagen.jpg';
                                                        }
                                                        ?>
                                                        <div class="item">
                                                            <div class="item-inner">
                                                                <div class="prd">
                                                                    <div class="item-img clearfix">
                                                                        <div class="ico-label">
                                                                            <?php
                                                                            if ($productosCenter1['precioDescuento'] > 0) {
                                                                                ?>
                                                                                <span class="ico-product ico-sale">Promo</span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                        <a class="product-image have-additional"
                                                                           href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter1['titulo']) . "/" . $productosCenter1['id'] ?>">
                                                                <span class="img-main"
                                                                      style="height:200px;background:url(<?= $rutaImg; ?>) no-repeat center center/70%;">
                                                                </span>
                                                                        </a>
                                                                    </div>
                                                                    <div class="item-info">
                                                                        <div class="info-inner">
                                                                            <div class="item-title">
                                                                                <a href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter1['titulo']) . "/" . $productosCenter1['id'] ?>">
                                                                                    <?= ucfirst($productosCenter1['titulo']) ?> </a>
                                                                            </div>
                                                                            <div class="item-price">
                                                                                <div class="price-box">
                                                                            <span class="regular-price">
                                                                                <span class="price">
                                                                                    <?php
                                                                                    if ($productosCenter1['precioDescuento'] > 0) {
                                                                                        ?>
                                                                                        <span class="precio1">$ <?= $productosCenter1['precioDescuento']; ?></span>
                                                                                        <span class="precio2">$ <?= $productosCenter1['precio']; ?></span>
                                                                                        <?php

                                                                                    } else {
                                                                                        ?>
                                                                                        <span class="precio1">$ <?= $productosCenter1['precio']; ?></span>
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
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                //Banner 870x110
                                if (count($banDataPie) != '') {
                                    $banRandPie = $banDataPie[array_rand($banDataPie)];
                                    $imagenes->set("cod", $banRandPie['cod']);
                                    $imgRandPie = $imagenes->view();
                                    $banners->set("id", $banRandPie['id']);
                                    $valuePie = $banRandPie['vistas'] + 1;
                                    $banners->set("vistas", $valuePie);
                                    $banners->increaseViews();
                                    ?>
                                    <div class="sns_banner_page2">
                                        <div class="banner5">
                                            <a href="<?= $banRandPie['link'] ?>">
                                                <img src="<?= URL . '/' . $imgRandPie['ruta'] ?>"
                                                     alt="<?= $banRandPie['nombre'] ?>" style="width:100%;">
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                                <div id="sns_slider1_page2"
                                     class="sns-slider-wraps sns_producttaps_wraps visible-md visible-lg">

                                    <h3 class="precar">ÚLTIMOS PRODUCTOS</h3>

                                    <div class="tab-content visible-lg ">
                                        <div class="content-loading"></div>
                                        <div role="tabpanel" class="tab-pane active" id="chair">
                                            <div class="taps-slider1 products-grid row style_grid owl-carousel owl-theme owl-loaded">
                                                <div class="taps-slider">
                                                    <?php
                                                    $cont = 0;
                                                    foreach ($productDataCenter2 as $productosCenter2) {
                                                        if ($cont < 4) {
                                                            ?>
                                                            <?php $cod_productoExp = explode("/", $productosCenter2['cod_producto']); ?>
                                                            <?php $cod_productoRemp = str_replace("/", "-", $productosCenter2['cod_producto']); ?>
                                                            <?php $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php if ($funciones->fileExists($urlImg) === true) { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php } else { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/sin_imagen.jpg'; ?>
                                                            <?php } ?>
                                                            <div class="item col-lg-3 col-md-4 col-sm-4 col-xs-6 col-phone-12">
                                                                <div class="item-inner">
                                                                    <div class="prd">
                                                                        <div class="item-img clearfix">
                                                                            <div class="ico-label">
                                                                                <?php
                                                                                if ($productosCenter2['precioDescuento'] > 0) {
                                                                                    ?>
                                                                                    <span class="ico-product ico-sale">Promo</span>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <a class="product-image have-additional"
                                                                               href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter2['titulo']) . "/" . $productosCenter2['id'] ?>">
                                                            <span class="img-main"
                                                                  style="height:200px;background:url(<?= $rutaImg; ?>)no-repeat center center/70%;">
                                                            </span>
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-info">
                                                                            <div class="info-inner">
                                                                                <div class="item-title">
                                                                                    <a title="Modular Modern"
                                                                                       href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter2['titulo']) . "/" . $productosCenter2['id'] ?>">
                                                                                        <?= ucfirst($productosCenter2['titulo']) ?> </a>
                                                                                </div>
                                                                                <div class="item-price">
                                                                                    <div class="price-box">
                                                                <span class="regular-price">
                                                                    <span class="price">
                                                                        <?php
                                                                        if ($productosCenter2['precioDescuento'] > 0) {
                                                                            ?>
                                                                            <span class="precio1">$ <?= $productosCenter2['precioDescuento']; ?></span>
                                                                            <span class="precio2">$ <?= $productosCenter2['precio']; ?></span>
                                                                            <?php

                                                                        } else {
                                                                            ?>
                                                                            <span class="precio1">$ <?= $productosCenter2['precio']; ?></span>
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
                                                            $cont++;
                                                            if (($key = array_search($productosCenter2, $productDataCenter2)) !== false) {
                                                                unset($productDataCenter2[$key]);
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <div class="taps-slider">
                                                    <?php
                                                    $cont = 0;
                                                    foreach ($productDataCenter2 as $productosCenter2) {
                                                        if ($cont < 4) {
                                                            $cod_productoExp = explode("/", $productosCenter2['cod_producto']);
                                                            $cod_productoRemp = str_replace("/", "-", $productosCenter2['cod_producto']);
                                                            $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg';
                                                            if ($funciones->fileExists($urlImg) === true) {
                                                                $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg';
                                                            } else {
                                                                $rutaImg = URL . '/assets/archivos/sin_imagen.jpg';
                                                            }
                                                            ?>
                                                            <div class="item col-lg-3 col-md-4 col-sm-4 col-xs-6 col-phone-12">
                                                                <div class="item-inner">
                                                                    <div class="prd">
                                                                        <div class="item-img clearfix">
                                                                            <div class="ico-label">
                                                                                <?php
                                                                                if ($productosCenter2['precioDescuento'] > 0) {
                                                                                    ?>
                                                                                    <span class="ico-product ico-sale">Promo</span>
                                                                                    <?php

                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <a class="product-image have-additional" href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter2['titulo']) . "/" . $productosCenter2['id'] ?>">
                                                                                    <span class="img-main"
                                                                                          style="height:200px;background:url(<?= $rutaImg; ?>)no-repeat center center/contain;">
                                                                                    </span>
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-info">
                                                                            <div class="info-inner">
                                                                                <div class="item-title">
                                                                                    <a href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter2['titulo']) . "/" . $productosCenter2['id'] ?>">
                                                                                        <?= ucfirst($productosCenter2['titulo']) ?>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="item-price">
                                                                                    <div class="price-box">
                                                                                        <span class="regular-price">
                                                                                            <span class="price">
                                                                                                <?php
                                                                                                if ($productosCenter2['precioDescuento'] > 0) {
                                                                                                    ?>
                                                                                                    <span class="precio1">$ <?= $productosCenter2['precioDescuento']; ?></span>
                                                                                                    <span class="precio2">$ <?= $productosCenter2['precio']; ?></span>
                                                                                                    <?php

                                                                                                } else {
                                                                                                    ?>
                                                                                                    <span class="precio1">$ <?= $productosCenter2['precio']; ?></span>
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
                                                            $cont++;
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-content visible-md">
                                        <div class="content-loading"></div>
                                        <div role="tabpanel" class="tab-pane active" id="chair">
                                            <div class="taps-slider1 products-grid row style_grid owl-carousel owl-theme owl-loaded">
                                                <div class="taps-slider">
                                                    <?php
                                                    $cont = 0;
                                                    foreach ($productDataCenter2 as $productosCenter2) {
                                                        if ($cont < 3) {
                                                            $cod_productoExp = explode("/", $productosCenter2['cod_producto']);
                                                            $cod_productoRemp = str_replace("/", "-", $productosCenter2['cod_producto']);
                                                            $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg';
                                                            if ($funciones->fileExists($urlImg) === true) {
                                                                $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg';
                                                            } else {
                                                                $rutaImg = URL . '/assets/archivos/sin_imagen.jpg';
                                                            }
                                                            ?>
                                                            <div class="item col-lg-3 col-md-4 col-sm-4 col-xs-6 col-phone-12">
                                                                <div class="item-inner">
                                                                    <div class="prd">
                                                                        <div class="item-img clearfix">
                                                                            <div class="ico-label">
                                                                                <?php
                                                                                if ($productosCenter2['precioDescuento'] > 0) {
                                                                                    ?>
                                                                                    <span class="ico-product ico-sale">Promo</span>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <a class="product-image have-additional" href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter2['titulo']) . "/" . $productosCenter2['id'] ?>">
                                                                                <span class="img-main"
                                                                                      style="height:200px;background:url(<?= URL . '/' . $imgProCenter2['ruta'] ?>)no-repeat center center/70%;">
                                                                                </span>
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-info">
                                                                            <div class="info-inner">
                                                                                <div class="item-title">
                                                                                    <a title="Modular Modern"
                                                                                       href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter2['titulo']) . "/" . $productosCenter2['id'] ?>">
                                                                                        <?= ucfirst($productosCenter2['titulo']) ?> </a>
                                                                                </div>
                                                                                <div class="item-price">
                                                                                    <div class="price-box">
                                                                                        <span class="regular-price">
                                                                                            <span class="price">
                                                                                                <?php
                                                                                                if ($productosCenter2['precioDescuento'] > 0) {
                                                                                                    ?>
                                                                                                    <span class="precio1">$ <?= $productosCenter2['precioDescuento']; ?></span>
                                                                                                    <span class="precio2">$ <?= $productosCenter2['precio']; ?></span>
                                                                                                    <?php

                                                                                                } else {
                                                                                                    ?>
                                                                                                    <span class="precio1">$ <?= $productosCenter2['precio']; ?></span>
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
                                                            $cont++;
                                                            if (($key = array_search($productosCenter2, $productDataCenter2)) !== false) {
                                                                unset($productDataCenter2[$key]);
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <div class="taps-slider">
                                                    <?php
                                                    $cont = 0;
                                                    foreach ($productDataCenter2 as $productosCenter2) {
                                                        if ($cont < 3) {
                                                            ?>
                                                            <?php $cod_productoExp = explode("/", $productosCenter2['cod_producto']); ?>
                                                            <?php $cod_productoRemp = str_replace("/", "-", $productosCenter2['cod_producto']); ?>
                                                            <?php $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php if ($funciones->fileExists($urlImg) === true) { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php } else { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/sin_imagen.jpg'; ?>
                                                            <?php } ?>
                                                            <div class="item col-lg-3 col-md-4 col-sm-4 col-xs-6 col-phone-12">
                                                                <div class="item-inner">
                                                                    <div class="prd">
                                                                        <div class="item-img clearfix">
                                                                            <div class="ico-label">
                                                                                <?php
                                                                                if ($productosCenter2['precioDescuento'] > 0) {
                                                                                    ?>
                                                                                    <span class="ico-product ico-sale">Promo</span>
                                                                                    <?php

                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <a class="product-image have-additional"  href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter2['titulo']) . "/" . $productosCenter2['id'] ?>">
                                                                                <span class="img-main"
                                                                                      style="height:200px;background:url(<?= $rutaImg; ?>)no-repeat center center/contain;">
                                                                                </span>
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-info">
                                                                            <div class="info-inner">
                                                                                <div class="item-title">
                                                                                    <a
                                                                                            href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter2['titulo']) . "/" . $productosCenter2['id'] ?>">
                                                                                        <?= ucfirst($productosCenter2['titulo']) ?> </a>
                                                                                </div>
                                                                                <div class="item-price">
                                                                                    <div class="price-box">
                                                                                        <span class="regular-price">
                                                                                            <span class="price">
                                                                                                <?php
                                                                                                if ($productosCenter2['precioDescuento'] > 0) {
                                                                                                    ?>
                                                                                                    <span class="precio1">$ <?= $productosCenter2['precioDescuento']; ?></span>
                                                                                                    <span class="precio2">$ <?= $productosCenter2['precio']; ?></span>
                                                                                                    <?php

                                                                                                } else {
                                                                                                    ?>
                                                                                                    <span class="precio1">$ <?= $productosCenter2['precio']; ?></span>
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
                                                            $cont++;
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Banner 425x110 -->
                                <?php
                                if (count($banDataPieMedio) >= 2) {
                                    $banRandPieMedio = $banDataPieMedio[array_rand($banDataPieMedio)];
                                    $imagenes->set("cod", $banRandPieMedio['cod']);
                                    $imgRandPieMedio = $imagenes->view();
                                    $banners->set("id", $banRandPieMedio['id']);
                                    $value = $banRandPieMedio['vistas'] + 1;
                                    $banners->set("vistas", $value);
                                    $banners->increaseViews();
                                    ?>
                                    <div class="sns_banner1">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6">
                                                <div class="banner-content banner5">
                                                    <a href="<?= $banRandPieMedio['link'] ?>">
                                                        <img src="<?= URL . '/' . $imgRandPieMedio['ruta'] ?>"
                                                             alt="<?= $banRandPieMedio['nombre'] ?>">
                                                    </a>
                                                </div>
                                            </div>
                                            <?php
                                            if (($key = array_search($banRandPieMedio, $banDataPieMedio)) !== false) {
                                                unset($banDataPieMedio[$key]);
                                            }
                                            $banRandPieMedio2 = $banDataPieMedio[array_rand($banDataPieMedio)];
                                            $imagenes->set("cod", $banRandPieMedio2['cod']);
                                            $imgRandPieMedio2 = $imagenes->view();
                                            $banners->set("id", $banRandPieMedio2['id']);
                                            $value = $banRandPieMedio2['vistas'] + 1;
                                            $banners->set("vistas", $value);
                                            $banners->increaseViews();
                                            ?>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="banner-content banner5 style-banner2">
                                                    <a href="<?= $banRandPieMedio2['link'] ?>">
                                                        <img src="<?= URL . '/' . $imgRandPieMedio2['ruta'] ?>"
                                                             alt="<?= $banRandPieMedio2['nombre'] ?>">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if (($key = array_search($banRandPieMedio2, $banDataPieMedio)) !== false) {
                                        unset($banDataPieMedio[$key]);
                                    }
                                }
                                ?>


                                <div id="sns_slider2_page2"
                                     class="sns-slider-wraps sns_producttaps_wraps visible-md visible-lg">
                                    <h3 class="precar">Promociones</h3>

                                    <!-- Tab panes -->
                                    <div class="tab-content visible-lg">
                                        <div class="content-loading"></div>
                                        <div role="tabpanel" class="tab-pane active" id="chair">
                                            <div class="taps-slider1 products-grid row style_grid owl-carousel owl-theme owl-loaded">
                                                <div class="taps-slider">
                                                    <?php
                                                    $cont = 0;
                                                    foreach ($productDataCenter3 as $productosCenter3) {
                                                        if ($cont < 4) {
                                                            ?>
                                                            <?php $cod_productoExp = explode("/", $productosCenter3['cod_producto']); ?>
                                                            <?php $cod_productoRemp = str_replace("/", "-", $productosCenter3['cod_producto']); ?>
                                                            <?php $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php if ($funciones->fileExists($urlImg) === true) { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php } else { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/sin_imagen.jpg'; ?>
                                                            <?php } ?>
                                                            <div class="item col-lg-3 col-md-4 col-sm-4 col-xs-6 col-phone-12">
                                                                <div class="item-inner">
                                                                    <div class="prd">
                                                                        <div class="item-img clearfix">
                                                                            <div class="ico-label">
                                                                                <?php
                                                                                if ($productosCenter3['precioDescuento'] > 0) {
                                                                                    ?>
                                                                                    <span class="ico-product ico-sale">Promo</span>
                                                                                    <?php

                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <a class="product-image have-additional"
                                                                               href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter3['titulo']) . "/" . $productosCenter3['id'] ?>">
                                            <span class="img-main"
                                                  style="height:200px;background:url(<?= $rutaImg; ?>)no-repeat center center/70%;">
                                            </span>
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-info">
                                                                            <div class="info-inner">
                                                                                <div class="item-title">
                                                                                    <a
                                                                                            href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter3['titulo']) . "/" . $productosCenter3['id'] ?>">
                                                                                        <?= ucfirst($productosCenter3['titulo']) ?> </a>
                                                                                </div>
                                                                                <div class="item-price">
                                                                                    <div class="price-box">
                                                <span class="regular-price">
                                                    <span class="price">
                                                        <?php
                                                        if ($productosCenter3['precioDescuento'] > 0) {
                                                            ?>
                                                            <span class="precio1">$ <?= $productosCenter3['precioDescuento']; ?></span>
                                                            <span class="precio2">$ <?= $productosCenter3['precio']; ?></span>
                                                            <?php

                                                        } else {
                                                            ?>
                                                            <span class="precio1">$ <?= $productosCenter3['precio']; ?></span>
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
                                                            $cont++;
                                                            if (($key = array_search($productosCenter3, $productDataCenter3)) !== false) {
                                                                unset($productDataCenter3[$key]);
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                                <div class="taps-slider">
                                                    <?php
                                                    $cont = 0;
                                                    foreach ($productDataCenter3 as $productosCenter3) {
                                                        if ($cont < 4) {
                                                            ?>
                                                            <?php $cod_productoExp = explode("/", $productosCenter3['cod_producto']); ?>
                                                            <?php $cod_productoRemp = str_replace("/", "-", $productosCenter3['cod_producto']); ?>
                                                            <?php $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php if ($funciones->fileExists($urlImg) === true) { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php } else { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/sin_imagen.jpg'; ?>
                                                            <?php } ?>
                                                            <div class="item col-lg-3 col-md-4 col-sm-4 col-xs-6 col-phone-12">
                                                                <div class="item-inner">
                                                                    <div class="prd">
                                                                        <div class="item-img clearfix">
                                                                            <div class="ico-label">
                                                                                <?php
                                                                                if ($productosCenter3['precioDescuento'] > 0) {
                                                                                    ?>
                                                                                    <span class="ico-product ico-sale">Promo</span>
                                                                                    <?php

                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <a class="product-image have-additional"
                                                                               href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter3['titulo']) . "/" . $productosCenter3['id'] ?>">
                        <span class="img-main"
                              style="height:200px;background:url(<?= $rutaImg; ?>)no-repeat center center/contain;">
                        </span>
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-info">
                                                                            <div class="info-inner">
                                                                                <div class="item-title">
                                                                                    <a
                                                                                            href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter3['titulo']) . "/" . $productosCenter3['id'] ?>">
                                                                                        <?= ucfirst($productosCenter3['titulo']) ?> </a>
                                                                                </div>
                                                                                <div class="item-price">
                                                                                    <div class="price-box">
                            <span class="regular-price">
                                <span class="price">
                                    <?php
                                    if ($productosCenter3['precioDescuento'] > 0) {
                                        ?>
                                        <span class="precio1">$ <?= $productosCenter3['precioDescuento']; ?></span>
                                        <span class="precio2">$ <?= $productosCenter3['precio']; ?></span>
                                        <?php

                                    } else {
                                        ?>
                                        <span class="precio1">$ <?= $productosCenter3['precio']; ?></span>
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
                                                            $cont++;
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-content visible-md">
                                        <div class="content-loading"></div>
                                        <div role="tabpanel" class="tab-pane active" id="chair">
                                            <div class="taps-slider1 products-grid row style_grid owl-carousel owl-theme owl-loaded">
                                                <div class="taps-slider">
                                                    <?php
                                                    $cont = 0;
                                                    foreach ($productDataCenter3 as $productosCenter3) {
                                                        if ($cont < 3) {
                                                            ?>
                                                            <?php $cod_productoExp = explode("/", $productosCenter3['cod_producto']); ?>
                                                            <?php $cod_productoRemp = str_replace("/", "-", $productosCenter3['cod_producto']); ?>
                                                            <?php $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php if ($funciones->fileExists($urlImg) === true) { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php } else { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/sin_imagen.jpg'; ?>
                                                            <?php } ?>
                                                            <div class="item col-lg-3 col-md-4 col-sm-4 col-xs-6 col-phone-12">
                                                                <div class="item-inner">
                                                                    <div class="prd">
                                                                        <div class="item-img clearfix">
                                                                            <div class="ico-label">
                                                                                <?php
                                                                                if ($productosCenter3['precioDescuento'] > 0) {
                                                                                    ?>
                                                                                    <span class="ico-product ico-sale">Promo</span>
                                                                                    <?php

                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <a class="product-image have-additional"
                                                                               href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter3['titulo']) . "/" . $productosCenter3['id'] ?>">
                                            <span class="img-main"
                                                  style="height:200px;background:url(<?= $rutaImg; ?>)no-repeat center center/contain;">
                                            </span>
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-info">
                                                                            <div class="info-inner">
                                                                                <div class="item-title">
                                                                                    <a
                                                                                            href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter3['titulo']) . "/" . $productosCenter3['id'] ?>">
                                                                                        <?= ucfirst($productosCenter3['titulo']) ?> </a>
                                                                                </div>
                                                                                <div class="item-price">
                                                                                    <div class="price-box">
                                                <span class="regular-price">
                                                    <span class="price">
                                                        <?php
                                                        if ($productosCenter3['precioDescuento'] > 0) {
                                                            ?>
                                                            <span class="precio1">$ <?= $productosCenter3['precioDescuento']; ?></span>
                                                            <span class="precio2">$ <?= $productosCenter3['precio']; ?></span>
                                                            <?php

                                                        } else {
                                                            ?>
                                                            <span class="precio1">$ <?= $productosCenter3['precio']; ?></span>
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
                                                            $cont++;
                                                            if (($key = array_search($productosCenter3, $productDataCenter3)) !== false) {
                                                                unset($productDataCenter3[$key]);
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                                <div class="taps-slider">
                                                    <?php
                                                    $cont = 0;
                                                    foreach ($productDataCenter3 as $productosCenter3) {
                                                        if ($cont < 3) {
                                                            ?>
                                                            <?php $cod_productoExp = explode("/", $productosCenter3['cod_producto']); ?>
                                                            <?php $cod_productoRemp = str_replace("/", "-", $productosCenter3['cod_producto']); ?>
                                                            <?php $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php if ($funciones->fileExists($urlImg) === true) { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                                                            <?php } else { ?>
                                                                <?php $rutaImg = URL . '/assets/archivos/sin_imagen.jpg'; ?>
                                                            <?php } ?>
                                                            <div class="item col-lg-3 col-md-4 col-sm-4 col-xs-6 col-phone-12">
                                                                <div class="item-inner">
                                                                    <div class="prd">
                                                                        <div class="item-img clearfix">
                                                                            <div class="ico-label">
                                                                                <?php
                                                                                if ($productosCenter3['precioDescuento'] > 0) {
                                                                                    ?>
                                                                                    <span class="ico-product ico-sale">Promo</span>
                                                                                    <?php

                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <a class="product-image have-additional"
                                                                               href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter3['titulo']) . "/" . $productosCenter3['id'] ?>">
                        <span class="img-main"
                              style="height:200px;background:url(<?= $rutaImg; ?>)no-repeat center center/contain;">
                        </span>
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-info">
                                                                            <div class="info-inner">
                                                                                <div class="item-title">
                                                                                    <a
                                                                                            href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosCenter3['titulo']) . "/" . $productosCenter3['id'] ?>">
                                                                                        <?= ucfirst($productosCenter3['titulo']) ?> </a>
                                                                                </div>
                                                                                <div class="item-price">
                                                                                    <div class="price-box">
                            <span class="regular-price">
                                <span class="price">
                                    <?php
                                    if ($productosCenter3['precioDescuento'] > 0) {
                                        ?>
                                        <span class="precio1">$ <?= $productosCenter3['precioDescuento']; ?></span>
                                        <span class="precio2">$ <?= $productosCenter3['precio']; ?></span>
                                        <?php

                                    } else {
                                        ?>
                                        <span class="precio1">$ <?= $productosCenter3['precio']; ?></span>
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
                                                            $cont++;
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
                    <div class="row bottom visible-md visible-lg">

                        <div class="col-md-12">
                            <!-- Banner 570x110 -->
                            <?php
                            if (count($banDataPieMedio) >= 2) {
                                $banRandPieMedio5 = $banDataPieMedio[array_rand($banDataPieMedio)];
                                $imagenes->set("cod", $banRandPieMedio5['cod']);
                                $imgRandPieMedio5 = $imagenes->view();
                                $banners->set("id", $banRandPieMedio5['id']);
                                $value = $banRandPieMedio5['vistas'] + 1;
                                $banners->set("vistas", $value);
                                $banners->increaseViews();
                                ?>
                                <div class="sns_banner3">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="banner-content banner5">
                                                <a href="<?= $banRandPieMedio5['link'] ?>">
                                                    <img src="<?= URL . '/' . $imgRandPieMedio5['ruta'] ?>"
                                                         alt="<?= $banRandPieMedio5['nombre'] ?>">
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                        if (($key = array_search($banRandPieMedio5, $banDataPieMedio)) !== false) {
                                            unset($banDataPieMedio[$key]);
                                        }
                                        $banRandPieMedio6 = $banDataPieMedio[array_rand($banDataPieMedio)];
                                        $imagenes->set("cod", $banRandPieMedio6['cod']);
                                        $imgRandPieMedio6 = $imagenes->view();
                                        $banners->set("id", $banRandPieMedio6['id']);
                                        $value = $banRandPieMedio6['vistas'] + 1;
                                        $banners->set("vistas", $value);
                                        $banners->increaseViews();
                                        ?>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="banner-content banner5 style-banner2">
                                                <a href="<?= $banRandPieMedio6['link'] ?>">
                                                    <img src="<?= URL . '/' . $imgRandPieMedio6['ruta'] ?>"
                                                         alt="<?= $banRandPieMedio6['nombre'] ?>">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (($key = array_search($banRandPieMedio6, $banDataPieMedio)) !== false) {
                                    unset($banDataPieMedio[$key]);
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- AND CONTENT -->

        </div>
        </body>
    <?php
    $template->themeEnd();
    ?>

    <script>
        $("a.nav").each(function (){
            console.log(this.href);
        });
    </script>



