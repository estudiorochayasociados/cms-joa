<?php
$imagenesIncSide = new Clases\Imagenes();
$productosIncSide = new Clases\Productos();
$categoriasIncSide = new Clases\Categorias();
$bannersIncSide = new Clases\Banner();
$funcionesIncSide = new Clases\PublicFunction();
$rubros = new Clases\Rubros();
//Banners
$categoriasIncSide->set("area", "banners");
$categoriasIncSideData = $categoriasIncSide->listForArea('');
foreach ($categoriasIncSideData as $valIncSide) {
    if ($valIncSide['titulo'] == 'Side' && $valIncSide['area'] == 'banners') {
        $bannersIncSide->set("categoria", $valIncSide['cod']);
        $banDataIncSide = $bannersIncSide->listForCategory();
    }
}
//Productos
$filterRubrosCategorias = array("categoria != '' GROUP BY categoria");
$rubrosArrayCategorias = $rubros->list($filterRubrosCategorias, "categoria ASC", "");

$productDataIncSide = $productosIncSide->listWithOps('', ' RAND() ', '4');
$novedades = new Clases\Novedades();
$novedadesData = $novedades->list('');
?>
<!-- sns_left -->
<div id="sns_left" class="col-md-3 visible-md visible-lg">
    <div class="wrap-in">
        <div class="block block-layered-nav block-layered-nav--no-filters">
            <div class="block-title">
                <strong>
                    <span>Categorías</span>
                </strong>
            </div><br/>
            <div class="block-content toggle-content">
                <dl id="narrow-by-list">
                    <ol class="catLista">
                        <?php
                        foreach ($rubrosArrayCategorias as $key => $value) {
                            ?>
                            <li>
                                <a data-toggle="collapse"
                                   href="#collapse<?= $value['id'] ?>"
                                   role="button" aria-expanded="false"
                                   aria-controls="collapse<?= $value['id'] ?>"><span
                                            class="catCirculo">+</span></a>
                                <a href="<?= URL ?>/productos?linea=<?= strtolower($funcionesIncSide->normalizar_link($value['categoria'])) ?>&id=<?= $value['id'] ?>">
                                    <?= $value['categoria'] ?>
                                </a>
                            </li>

                            <div class="collapse" id="collapse<?= $value['id'] ?>">
                                <div class="card card-body pl-15">
                                    <?php $filterRubrosSubcategorias = array("categoria = '" . $value['categoria'] . "' GROUP BY subcategoria"); ?>
                                    <?php $rubrosArraySubcategorias = $rubros->list($filterRubrosSubcategorias, "subcategoria ASC", ""); ?>
                                    <?php foreach ($rubrosArraySubcategorias as $key2 => $value2): ?>

                                        <li>
                                            <a href="<?= URL ?>/productos?linea=<?= strtolower($funcionesIncSide->normalizar_link($value['categoria'])); ?>&rubro=<?= strtolower($funcionesIncSide->normalizar_link($value2['subcategoria'])); ?>&id=<?= $value2['id'] ?>">
                                                - <?= $value2['subcategoria'] ?>
                                            </a>
                                        </li>

                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php
                        }
                        ?>
                    </ol>
                </dl>
            </div>
        </div>

        <!-- Banner 270x350 -->
        <?php
        if (count($banDataIncSide) != '') {
            $banRandIncSide = $banDataIncSide[array_rand($banDataIncSide)];
            $imagenesIncSide->set("cod", $banRandIncSide['cod']);
            $imgRandIncSide = $imagenesIncSide->view();
            $bannersIncSide->set("id", $banRandIncSide['id']);
            $value = $banRandIncSide['vistas'] + 1;
            $bannersIncSide->set("vistas", $value);
            $bannersIncSide->increaseViews();
            ?>
            <div class="block banner_left2 block_cat">
                <a class="banner5" href="<?= $banRandIncSide['link'] ?>">
                    <img src="<?= URL . '/' . $imgRandIncSide['ruta'] ?>" alt="<?= $banRandIncSide['nombre'] ?>">
                </a>
            </div>
            <?php

        }
        ?>
        <!-- EndBanner -->
        <!-- Productos random -->
        <div class="block bestsale">
            <div class="title">
                <h3>Recomendados</h3>
            </div>
            <div class="content">
                <div class="products-slider12  owl-theme" style="display: inline-block">
                    <?php
                    if (count($productDataIncSide) >= 5) {
                        $contIncSide = 5;
                    } else {
                        $contIncSide = count($productDataIncSide);
                    }
                    for ($i = 0; $i < $contIncSide; $i++) {
                        $proRandIncSide = $productDataIncSide[array_rand($productDataIncSide)];
                        if (($key = array_search($proRandIncSide, $productDataIncSide)) !== false) {
                            unset($productDataIncSide[$key]);
                        }
                        ?>
                        <?php $cod_productoExp = explode("/", $proRandIncSide['cod_producto']); ?>
                        <?php $cod_productoRemp = str_replace("/", "-", $proRandIncSide['cod_producto']); ?>
                        <?php $urlImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                        <?php if ($funcionesIncSide->fileExists($urlImg) === true) { ?>
                            <?php $rutaImg = URL . '/assets/archivos/img_productos/' . $cod_productoExp[0] . '/' . $cod_productoRemp . '.jpg'; ?>
                        <?php } else { ?>
                            <?php $rutaImg = URL . '/assets/archivos/sin_imagen.jpg'; ?>
                        <?php } ?>
                        <div class="item">
                            <div class="item-inner">
                                <div class="prd">
                                    <div class="item-img clearfix">
                                        <a class="product-image have-additional"
                                           href="<?php echo URL . '/producto/' . $funcionesIncSide->normalizar_link($proRandIncSide['titulo']) . "/" . $proRandIncSide['id'] ?>">
                                                    <span class="img-main"
                                                          style="width:80px;height:80px;background:url('<?= $rutaImg; ?>') no-repeat center center/contain;">
                                                    </span>
                                        </a>
                                    </div>
                                    <div class="item-info">
                                        <div class="info-inner">
                                            <div class="item-title" title="<?= ucfirst($proRandIncSide['titulo']); ?>">
                                                <a href="<?php echo URL . '/producto/' . $funcionesIncSide->normalizar_link($proRandIncSide['titulo']) . "/" . $proRandIncSide['id'] ?>"> <?= substr(ucfirst($proRandIncSide['titulo']), 0, 10) ?>
                                                    ... </a>
                                            </div>
                                            <div class="item-price">
                                                        <span class="price">
                                                            <?php
                                                            if ($proRandIncSide['precioDescuento'] > 0) {
                                                                ?>
                                                                <span class="precioS1">$ <?= $proRandIncSide['precioDescuento']; ?></span>
                                                                <span class="precioS2">$ <?= $proRandIncSide['precio']; ?></span>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <span class="precioS1">$ <?= $proRandIncSide['precio']; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </span>
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

        <div class="block sns-latestblog">
            <div class="block-title">
                <h3>Últimos blogs</h3>
            </div>
            <div class="content">
                <div id="latestblog1333" class=" slider-left9  latestblog-content owl-carousel owl-theme owl-loaded"
                     style="display: inline-block">
                    <?php
                    foreach ($novedadesData as $novSide) {
                        $imagenesIncSide->set("cod", $novSide['cod']);
                        $imgNovSide = $imagenesIncSide->view();
                        $fechaSide = explode("-", $novSide['fecha']);
                        ?>
                        <div class="item banner5">
                            <div style="height:200px;background:url(<?= URL . '/' . $imgNovSide['ruta'] ?>)no-repeat center center/contain;"></div>
                            <div class="blog-page pb-30 mt-5">
                                <div class="blog-left">
                                    <p class="text1"><?= $fechaSide[2]; ?></p>
                                    <p class="text2"><?= $fechaSide[1]; ?></p>
                                </div>
                                <div class="blog-right">
                                    <p class="style2"><?= substr(ucfirst($novSide['titulo']), 0, 60) ?>...</p>
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