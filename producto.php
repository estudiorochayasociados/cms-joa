<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones= new Clases\PublicFunction();
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
//Productos
$id       = isset($_GET["id"]) ? $_GET["id"] : '';
$productos->set("id",$id);
$productData = $productos->view();
$imagenes->set("cod",$productData['cod']);
$imagenesData = $imagenes->listForProduct();
$filter = array("categoria ='".$productData['categoria']."'");
$productDataRel = $productos->listWithOps($filter,'','');
if (($key = array_search($productData, $productDataRel)) !== false) {
    unset($productDataRel[$key]);
}
//
//Banners
$categoriasData = $categorias->list('');
foreach($categoriasData as $val){
    
    if($val['titulo']=='Side' && $val['area']=='banners'){
        $banners->set("categoria",$val['cod']);
        $banDataSide = $banners->listForCategory();  
    }
}
//
?>
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
                                                if (count($imagenesData)>1) {
                                                ?>
                                                <div class="detail-img" >
                                                    <img id="imgFront" src="<?=URL. '/' . $imagenesData[0]['ruta'] ?>" alt="">
                                                </div>
                                                <div class="small-img">
                                                    <div id="sns_thumbail" class="owl-carousel owl-theme">
                                                        <?php
                                                        foreach ($imagenesData as $imgM ) {
                                                        ?>
                                                        <div class="item" style="background:url('<?=URL. '/' . $imgM['ruta'] ?>') no-repeat center center/contain;height:100px;width:100px;overflow:hidden" onclick="$('#imgFront').attr('src','<?=URL. '/' . $imgM['ruta'] ?>')"></div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <?php
                                                }else{
                                                    ?>
                                                    <div class="detail-img" >
                                                    <img src="<?=URL. '/assets/archivos/sin_imagen.jpg'?>" alt="">
                                                </div>
                                                <?php
                                                }
                                                ?>
                                                
                                            </div>
                                            <div id="product_shop" class="product-shop col-md-8 col-sm-7">
                                                <div class="item-inner product_list_style">
                                                    <div class="item-info">
                                                        <div class="item-title">
                                                            <a ><?= ucfirst($productData['titulo']); ?></a>
                                                        </div>
                                                        <div class="item-price">
                                                            <div class="price-box">
                                                                <span class="regular-price">
                                                                    <span class="price">
                                                                    <?php
                                                                        if ($productData['precioDescuento']>0) {
                                                                        ?>
                                                                           <span class="precioD1">$ <?= $productData['precioDescuento']; ?></span>
                                                                           <span class="precioD2">$ <?= $productData['precio']; ?></span>
                                                                        <?php
                                                                        }else {
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
                                                            if( $productData['stock']>0){
                                                                echo '<p class="style1">Unidades: Disponible</p>';
                                                            }else{
                                                                echo '<p class="style1">Unidades: No disponible</p>';
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="desc std">
                                                            <h5>Breve descripción</h5>
                                                            <p><?= $productData['description']; ?></p>
                                                        </div>

                                                        <div class="actions">
                                                            <label class="gfont" for="qty">Cantidad : </label>
                                                            <div class="qty-container">
                                                                <button class="qty-decrease" onclick="var qty_el = document.getElementById('qty'); var qty = qty_el.value; if( !isNaN( qty ) && qty > 1 ) qty_el.value--;return false;" type="button"></button>
                                                                <input id="qty" class="input-text qty" type="text" title="Qty" value="1" name="qty">
                                                                <button class="qty-increase" onclick="var qty_el = document.getElementById('qty'); var qty = qty_el.value; if( !isNaN( qty )) qty_el.value++;return false;" type="button"></button>
                                                            </div>
                                                            
                                                            <button class="btn-cart" title="Add to Cart" data-id="qv_item_8">
                                                                Añadir
                                                            </button>
                                                        </div>
                                                            <div class="addthis_native_toolbox"></div>
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
                            if (count($banDataSide)>=2) {
                                    $banRandSide = $banDataSide[array_rand($banDataSide)];
                                    $imagenes->set("cod",$banRandSide['cod']);
                                    $imgRandSide = $imagenes->view();
                                    $banners->set("id",$banRandSide['id']);
                                    $value=$banRandSide['vistas']+1;
                                    $banners->set("vistas",$value);
                                    $banners->increaseViews();
                                    ?>
                                    <div class="block block-banner banner5">
                                    <a href="<?= $banRandSide['link'] ?>">
                                        <img src="<?=URL. '/' . $imgRandSide['ruta'] ?>" alt="<?= $banRandSide['nombre']?>">
                                    </a>
                                    </div>
                                    <?php
                                     if (($key = array_search($banRandSide, $banDataSide)) !== false) {
                                         unset($banDataSide[$key]);
                                     }
                                     $banRandSide2 = $banDataSide[array_rand($banDataSide)];
                                     $imagenes->set("cod",$banRandSide2['cod']);
                                     $imgRandSide2 = $imagenes->view();
                                     $banners->set("id",$banRandSide2['id']);
                                     $value=$banRandSide2['vistas']+1;
                                     $banners->set("vistas",$value);
                                     $banners->increaseViews();
                                     ?>
                                     <div class="block block-banner banner5">
                                    <a href="<?= $banRandSide2['link'] ?>">
                                        <img src="<?=URL. '/' . $imgRandSide2['ruta'] ?>" alt="<?= $banRandSide2['nombre']?>">
                                    </a>
                                </div>
                                    <?php
                                }
                            ?>
                            </div>
                            <div id="sns_mainm" class="col-md-9">
                                <div id="sns_description" class="description mt-15">
                                    <div class="sns_producttaps_wraps1">
                                        <h3 class="detail-none">Descripción
                                            <i class="fa fa-align-justify"></i>
                                        </h3>
                                          <!-- Nav tabs -->
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="active style-detail"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Descripción</a></li>
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
                                        <div class="title">
                                            <h3>Productos relacionados</h3>
                                        </div>
                                        <div class="products-grid">
                                            <div id="related_upsell" class="item-row owl-carousel owl-theme" style="display: inline-block">
                                            <?php
                                            foreach ($productDataRel as $rel) {
                                                $productosRel1 = $productDataRel[array_rand($productDataRel)];
                                                $imagenes->set("cod",$productosRel1['cod']);
                                                $imgProRel1 = $imagenes->view();
                                            ?>
                                                 <div class="item">
                                                    <div class="item-inner">
                                                        <div class="prd">
                                                            <div class="item-img clearfix">
                                                                <div class="ico-label">
                                                                <?php
                                                                    if ($productosRel1['precioDescuento']>0) {
                                                                    ?>
                                                                       <span class="ico-product ico-sale">Promo</span>
                                                                    <?php
                                                                    }
                                                                ?>
                                                                </div>
                                                                <a class="product-image have-additional" href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosRel1['titulo']) . "/" . $productosRel1['id'] ?>">
                                                                    <span class="img-main" style="height:200px;background:url(<?= URL. '/' . $imgProRel1['ruta'] ?>)center/contain;">
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <div class="item-info">
                                                                <div class="info-inner">
                                                                    <div class="item-title">
                                                                        <a href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosRel1['titulo']) . "/" . $productosRel1['id'] ?>" > 
                                                                        <?= ucfirst($productosRel1['titulo']) ?> </a>
                                                                    </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box">
                                                                            <span class="regular-price">
                                                                                <span class="price">
                                                                                <?php
                                                                                    if ($productosRel1['precioDescuento']>0) {
                                                                                    ?>
                                                                                       <span class="precio1">$ <?= $productosRel1['precioDescuento']; ?></span>
                                                                                       <span class="precio2">$ <?= $productosRel1['precio']; ?></span>
                                                                                    <?php
                                                                                    }else {
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
                                                            <div class="action-bot">
                                                                <div class="wrap-addtocart">
                                                                    <button class="btn-cart">
                                                                        <i class="fa fa-shopping-cart"></i>
                                                                        <span>Añadir</span>
                                                                    </button>
                                                                </div>
                                                                <div class="actions">
                                                                    <ul class="add-to-links">
                                                                        <li class="wrap-quickview" data-id="qv_item_7">
                                                                            <div class="quickview-wrap">
                                                                                <a class="sns-btn-quickview qv_btn" href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productosRel1['titulo']) . "/" . $productosRel1['id'] ?>">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </a>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
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
            <!-- AND CONTENT -->
<?php
$template->themeEnd();
?>
<?php                                     