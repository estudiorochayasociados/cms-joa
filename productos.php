<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$template->set("title", "Pinturería Ariel | Productos");
$template->set("description", "");
$template->set("keywords", "");
$template->set("favicon", LOGO);
$template->themeInit();
//Clases
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$categorias = new Clases\Categorias();
$subcategorias = new Clases\Subcategorias();
$banners = new Clases\Banner();
$rubros = new Clases\Rubros();

$linea = isset($_GET["linea"]) ? $_GET["linea"] : '';
$rubro = isset($_GET["rubro"]) ? $_GET["rubro"] : '';
$id = isset($_GET["id"]) ? $_GET["id"] : '';
$buscar = isset($_GET["buscar"]) ? $_GET["buscar"] : '';
$orden_pagina = isset($_GET["order"]) ? $_GET["order"] : '';
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : '0';

$filter = array();

//COMIENZO FILTRO POR LINEA Y RUBRO
if (!empty($linea)) {
    $rubros->set("id", $id);
    $rubrosData = $rubros->view();
    if (!empty($rubro)) {
        $filterRubros = array("categoria = '" . $rubrosData['categoria'] . "'", "subcategoria = '" . $rubrosData['subcategoria'] . "'");
        $rubrosArray = $rubros->list($filterRubros, "", "");
        foreach ($rubrosArray as $key => $value) {
            $stringLineaRubro[] = "cod_producto LIKE '" . $value["linea"] . "/" . $value["rubro"] . "%'";
        }
    } else {
        $filterCategorias = array("categoria = '" . $rubrosData['categoria'] . "'");
        $categorias_filter = $rubros->list($filterCategorias, "", "");
        foreach ($categorias_filter as $key => $value) {
            $stringLineaRubro[] = "cod_producto LIKE '" . $value["linea"] . "/%'";
        }
    }

    $stringFiltro = implode(" OR ", $stringLineaRubro);
    $filter = array($stringFiltro);
}
//FIN FILTRO POR LINEA Y RUBRO

//FILTRO POR BUSQUEDA
if (!empty($buscar)) {
    $filter = array("MATCH(titulo) AGAINST ('$buscar')");
}
//FIN FILTRO POR BUSQUEDA

if ($pagina > 0) {
    $pagina = $pagina - 1;
}

if (@count($filter) == 0) {
    $filter = '';
}

if (@count($_GET) == 0) {
    $anidador = "?";
} else {
    if ($pagina >= 0) {
        $anidador = "&";
    } else {
        $anidador = "?";
    }
}

//Banners
$categoriasData = $categorias->list('');
foreach ($categoriasData as $valor) {
    if ($valor['titulo'] == 'Pie' && $valor['area'] == 'banners') {
        $banners->set("categoria", $valor['cod']);
        $banDataPie = $banners->listForCategory();
    }

    if ($valor['titulo'] == 'Side' && $valor['area'] == 'banners') {
        $banners->set("categoria", $valor['cod']);
        $banDataSide = $banners->listForCategory();
    }
}

//Productos
$filterRubrosCategorias = array("categoria != '' GROUP BY categoria");
$rubrosArrayCategorias = $rubros->list($filterRubrosCategorias, "categoria ASC", "");

switch ($orden_pagina) {
    case "mayor":
        $order_final = "precio DESC";
        break;
    case "menor":
        $order_final = "precio ASC";
        break;
    case "ultimos":
        $order_final = "id DESC";
        break;
    default:
        $order_final = "id DESC";
        break;
}

$productData = $productos->listWithOps($filter, $order_final, (24 * $pagina) . ',' . 24);
$productDataSide = $productos->listWithOps($filter, 'titulo ASC', '8');
$productosPaginador = $productos->paginador($filter, 24);
?>
<body id="bd"
      class="cms-index-index2 header-style2 prd-detail cms-index-index  products-grid1 cms-simen-home-page-v2 default cmspage">
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
                                    <span>Todos los productos</span>
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
    <div id="sns_content" class="wrap layout-lm">
        <div class="container">
            <div class="row">
                <!-- sns_left -->
                <div class="col-md-3">
                    <div class="wrap-in">
                        <div class="block block-layered-nav block-layered-nav--no-filters">
                            <div class="block-title">
                                <strong>
                                    <span>Categorías</span>
                                </strong>
                            </div>
                            <div class="block-content toggle-content">
                                <dl id="narrow-by-list">
                                    <ol class="catLista">
                                        <?php
                                        foreach ($rubrosArrayCategorias as $key => $value) {
                                            if ($value['id'] == $id) {
                                                $mostrarCollapse = $id;
                                            }
                                            ?>
                                            <li>
                                                <a data-toggle="collapse" href="#collapse<?= $value['id'] ?>" role="button" aria-expanded="false" aria-controls="collapse<?= $value['id'] ?>">
                                                    <span class="catCirculo">+</span>
                                                </a>
                                                <a href="<?= URL ?>/productos?linea=<?= strtolower($funciones->normalizar_link($value['categoria'])) ?>&id=<?= $value['id'] ?>" class="fs-13">
                                                    <b>
                                                        <?= $value['categoria'] ?>
                                                    </b>
                                                </a>
                                            </li>

                                            <div class="collapse" id="collapse<?= $value['id'] ?>">
                                                <div class="card card-body pl-15">
                                                    <?php $filterRubrosSubcategorias = array("categoria = '" . $value['categoria'] . "' GROUP BY subcategoria"); ?>
                                                    <?php $rubrosArraySubcategorias = $rubros->list($filterRubrosSubcategorias, "subcategoria ASC", ""); ?>
                                                    <?php foreach ($rubrosArraySubcategorias as $key2 => $value2): ?>
                                                        <?php
                                                        if ($value2['id'] == $id) {
                                                            $mostrarCollapse = $value['id'];
                                                        }
                                                        ?>
                                                        <li>
                                                            <a href="<?= URL ?>/productos?linea=<?= strtolower($funciones->normalizar_link($value['categoria'])); ?>&rubro=<?= strtolower($funciones->normalizar_link($value2['subcategoria'])); ?>&id=<?= $value2['id'] ?>" class="fs-12">
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
                        <?php
                        if (count($banDataSide) != '') {
                            $banRandSide = $banDataSide[array_rand($banDataSide)];
                            $imagenes->set("cod", $banRandSide['cod']);
                            $imgRandSide = $imagenes->view();
                            $banners->set("id", $banRandSide['id']);
                            $value = $banRandSide['vistas'] + 1;
                            $banners->set("vistas", $value);
                            $banners->increaseViews();
                            ?>
                            <div class="block block_cat visible-lg visible-md">
                                <a class="banner5" href="<?= $banRandSide['link'] ?>">
                                    <img src="<?= URL . '/' . $imgRandSide['ruta'] ?>" alt="<?= $banRandSide['nombre'] ?>">
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- sns_left -->
                <div id="sns_main" class="col-md-9 col-main">
                    <div id="sns_mainmidle">
                        <?php
                        if (count($banDataPie) != '') {
                            $banRandPie = $banDataPie[array_rand($banDataPie)];
                            $imagenes->set("cod", $banRandPie['cod']);
                            $imgRandPie = $imagenes->view();
                            $banners->set("id", $banRandPie['id']);
                            $valuePie = $banRandPie['vistas'] + 1;
                            $banners->set("vistas", $valuePie);
                            $banners->increaseViews();
                            ?>
                            <div class="category-cms-block"></div>
                            <p class="category-image banner5">
                                <a href="<?= $banRandPie['link'] ?>">
                                    <img src="<?= URL . '/' . $imgRandPie['ruta'] ?>"
                                         alt="<?= $banRandPie['nombre'] ?>">
                                </a>
                            </p>
                            <?php
                        }
                        ?>

                        <div class="category-products">

                            <!-- toolbar clearfix -->

                            <div class="toolbar clearfix">
                                <div class="toolbar-inner">
                                    <div class="sort-by">
                                        <label class="mt-10">Buscar por</label>
                                        <form method="get" class="pull-right">
                                            <?php
                                            foreach ($_GET as $key => $value) {
                                                if ($key != "order" && $key != "pagina") {
                                                    echo "<input type='hidden' name='" . $key . "' value='" . $value . "' />";
                                                }
                                            }
                                            ?>
                                            <select name="order" class="form-control" onchange="this.form.submit()">
                                                <option selected disabled></option>
                                                <option value="ultimos" <?php if ($orden_pagina == "ultimos") {
                                                    echo "selected";
                                                } ?>> Últimos
                                                </option>
                                                <option value="mayor" <?php if ($orden_pagina == "mayor") {
                                                    echo "selected";
                                                } ?>> Mayor precio
                                                </option>
                                                <option value="menor" <?php if ($orden_pagina == "menor") {
                                                    echo "selected";
                                                } ?>> Menor precio
                                                </option>
                                            </select>
                                        </form>
                                    </div>
                                    <div class="pager visible-md visible-lg">
                                        <p class="amount">
                                            <?= count($productData) ?> productos (s)
                                        </p>
                                        <div class="pages">
                                            <strong>Páginas:</strong>
                                            <ol>
                                                <?php
                                                if ($productosPaginador != 1 && $productosPaginador != 0) {
                                                    $url_final = $funciones->eliminar_get(CANONICAL, "pagina");
                                                    $links = '';
                                                    $links .= "<li><a href='" . $url_final . $anidador . "pagina=1'>1</a></li>";
                                                    $i = max(2, $pagina - 5);

                                                    if ($i > 2) {
                                                        $links .= "<li><a href='#'>...</a></li>";
                                                    }

                                                    for (; $i <= min($pagina + 6, $productosPaginador); $i++) {
                                                        $links .= "<li><a href='" . $url_final . $anidador . "pagina=" . $i . "'>" . $i . "</a></li>";
                                                    }

                                                    if ($i - 1 != $productosPaginador) {
                                                        $links .= "<li><a href='#'>...</a></li>";
                                                        $links .= "<li><a href='" . $url_final . $anidador . "pagina=" . $productosPaginador . "'>" . $productosPaginador . "</a></li>";
                                                    }
                                                    echo $links;
                                                    echo "";
                                                }
                                                ?>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- toolbar clearfix -->

                            <!-- sns-products-container -->
                            <div class="sns-products-container clearfix">
                                <div class="products-grid row style_grid">
                                    <?php foreach ($productData as $productos) { ?>
                                        <?php $cod_productoExp = explode("/", $productos['cod_producto']); ?>
                                        <?php $cod_productoRemp = str_replace("/", "-", $productos['cod_producto']); ?>
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
                                                        <a class="product-image have-additional"
                                                           href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productos['titulo']) . "/" . $productos['id'] ?>">
                                                            <span class="img-main">
                                                             <div style="height:200px;background:url(<?= $rutaImg ?>)no-repeat center center/contain;">
                                                             </div>
                                                         </span>
                                                        </a>
                                                    </div>
                                                    <div class="item-info">
                                                        <div class="info-inner">
                                                            <div class="item-title">
                                                                <a href="<?php echo URL . '/producto/' . $funciones->normalizar_link($productos['titulo']) . "/" . $productos['id'] ?>">
                                                                    <?= $productos['titulo'] ?> </a>
                                                            </div>
                                                            <div class="item-price">
                                                                <div class="price-box">
                                                                <span class="regular-price">
                                                                    <span class="price">
                                                                        <?php
                                                                        if (@$_SESSION["usuarios"]["descuento"] == 1) {
                                                                            if ($productos['precio'] != $productos['precio_mayorista']) {
                                                                                ?>
                                                                                <span class="precio1">$ <?= $productos['precio_mayorista']; ?></span>
                                                                                <span class="precio2">$ <?= $productos['precio']; ?></span>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <span class="precio1">$ <?= $productos['precio']; ?></span>
                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            ?>
                                                                            <span class="precio1">$ <?= $productos['precio']; ?></span>
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
                            <!-- sns-products-container -->


                            <!-- toolbar clearfix  bottom-->
                            <div class="toolbar clearfix">
                                <div class="toolbar-inner">
                                    <div class="pager">
                                        <p class="amount">
                                            <?= count($productData) ?> productos (s)
                                        </p>
                                        <div class="pages">
                                            <strong>Páginas:</strong>
                                            <ol>
                                                <?php
                                                if ($productosPaginador != 1 && $productosPaginador != 0) {
                                                    $url_final = $funciones->eliminar_get(CANONICAL, "pagina");
                                                    $links = '';
                                                    $links .= "<li><a href='" . $url_final . $anidador . "pagina=1'>1</a></li>";
                                                    $i = max(2, $pagina - 5);

                                                    if ($i > 2) {
                                                        $links .= "<li><a href='#'>...</a></li>";
                                                    }
                                                    for (; $i <= min($pagina + 6, $productosPaginador); $i++) {
                                                        $links .= "<li><a href='" . $url_final . $anidador . "pagina=" . $i . "'>" . $i . "</a></li>";
                                                    }
                                                    if ($i - 1 != $productosPaginador) {
                                                        $links .= "<li><a href='#'>...</a></li>";
                                                        $links .= "<li><a href='" . $url_final . $anidador . "pagina=" . $productosPaginador . "'>" . $productosPaginador . "</a></li>";
                                                    }
                                                    echo $links;
                                                    echo "";
                                                }
                                                ?>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- toolbar clearfix bottom -->
                        </div>
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
<?php if ($linea != ''): ?>
    <script>
        $(document).ready(function () {
            $('#collapse<?=$mostrarCollapse?>').collapse("toggle");
        });
    </script>
<?php else: ?>
<?php endif; ?>

