<?php
$funcionesNav = new Clases\PublicFunction();
//Clases
$imagenesNav = new Clases\Imagenes();
$usuario = new Clases\Usuarios();
$categoriasNav = new Clases\Categorias();
$bannersNav = new Clases\Banner();
$carrito = new Clases\Carrito();
$rubros = new Clases\Rubros();
//Banners
$categoriasDataNav = $categoriasNav->list('');
foreach ($categoriasDataNav as $valNav) {
    if ($valNav['titulo'] == 'Botonera' && $valNav['area'] == 'banners') {
        $bannersNav->set("categoria", $valNav['cod']);
        $banDataBotonera = $bannersNav->listForCategory();
    }
}
$carro = $carrito->return();

$filterRubrosCategorias = array("categoria != '' GROUP BY categoria");
$rubrosArrayCategorias = $rubros->list($filterRubrosCategorias, "categoria ASC", "");

$buscar = isset($_GET["buscar"]) ? $_GET["buscar"] : '';
?>

<div id="sns_wrapper">
    <!-- HEADER -->
    <div id="sns_header" class="wrap">
        <!-- Header Top -->
        <div class="sns_header_top">
            <div class="container">
                <div class="sns_module">

                    <div class="header-setting">
                        <div class="module-setting visible-lg visible-md mt-10">
                            <span style="color: white;"> (03564) 438484-443393 , Las Malvinas 930 - San Francisco (CBA).</span>
                        </div>
                        <div class="module-setting visible-xs mt-10">
                            <span style="color: white;"> (03564) 438484-443393</span>
                        </div>
                    </div>
                    <div class="header-account">
                        <div class="myaccount">
                            <div class="tongle">
                                <i class="fa fa-user"></i>
                                <span>Mi cuenta</span>
                                <i class="fa fa-angle-down"></i>
                            </div>
                            <div class="customer-ct content">
                                <ul class="links">
                                    <?php if (isset($_SESSION["usuarios"])): ?>
                                        <li>
                                            <a class="top-link-myaccount" title="cuenta" href="<?= URL ?>/panel">Mi
                                                cuenta</a>
                                        </li>
                                        <li>
                                            <a class="top-link-login" title="salir" href="<?= URL ?>/logout">Salir</a>
                                        </li>
                                    <?php else: ?>
                                        <li class=" last">
                                            <a class="top-link-login" data-toggle="modal" data-target="#login"
                                               title="Iniciar sesion" href="#">Iniciar sesión</a>
                                        </li>
                                        <li class=" last">
                                            <a class="top-link-login" data-toggle="modal" data-target="#registrar"
                                               title="Registrar" href="#">Registrar</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Logo -->
        <div id="sns_header_logo">
            <div class="container">
                <div class="container_in">
                    <div class="row">
                        <h1 id="logo" class=" responsv col-md-3">
                            <a href="<?= URL . '/index' ?>">
                                <img alt="" src="<?= URL ?>/assets/archivos/logo-grande.png">
                            </a>
                        </h1>
                        <div class="col-md-9 policy">
                            <?php
                            if (count($banDataBotonera) != '') {
                                $banRandBotonera = $banDataBotonera[array_rand($banDataBotonera)];
                                $imagenesNav->set("cod", $banRandBotonera['cod']);
                                $imgRandBotonera = $imagenesNav->view();
                                $bannersNav->set("id", $banRandBotonera['id']);
                                $valueNav = $banRandBotonera['vistas'] + 1;
                                $bannersNav->set("vistas", $valueNav);
                                $bannersNav->increaseViews();
                                ?>
                                <div class="block banner_left2 block_cat text-right mt-20" >
                                    <a class="banner5" href="<?= $banRandBotonera['link'] ?>">
                                        <img src="<?= URL . '/' . $imgRandBotonera['ruta'] ?>" alt="<?= $banRandBotonera['nombre'] ?>" style="width:70%;">
                                    </a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu -->
        <div id="sns_menu"  >
            <div class="container">
                <div class="sns_mainmenu" >
                    <div id="sns_mainnav" class="col-md-5">
                        <div id="sns_custommenu" class="visible-md visible-lg visible-sm">
                            <ul class="mainnav">
                                <li class="level0 custom-item active">
                                    <a class="menu-title-lv0 pd-menu116" href="<?= URL . '/index' ?>" target="_self">
                                        <span class="title">Inicio</span>
                                    </a>
                                </li>
                                <li class="level0 nav-2 no-group drop-submenu parent">
                                    <a class=" menu-title-lv0" href="<?= URL . '/productos' ?>">
                                        <span class="title">Todos los productos</span>
                                    </a>
                                    <div class="wrap_submenu">
                                        <ul class="level0">
                                            <?php foreach ($rubrosArrayCategorias as $key => $value): ?>
                                                <li class="level1 nav-1-3 parent">
                                                    <a class=" menu-title-lv1"
                                                       href="<?= URL ?>/productos?linea=<?= strtolower($funcionesNav->normalizar_link($value['categoria'])) ?>&id=<?= $value['id'] ?>">
                                                        <span class="title"><?= $value['categoria'] ?></span>
                                                    </a>
                                                    <div class="wrap_submenu">
                                                        <ul class="level1">
                                                            <?php $filterRubrosSubcategorias = array("categoria = '" . $value['categoria'] . "' GROUP BY subcategoria"); ?>
                                                            <?php $rubrosArraySubcategorias = $rubros->list($filterRubrosSubcategorias, "subcategoria ASC", ""); ?>
                                                            <?php foreach ($rubrosArraySubcategorias as $key2 => $value2): ?>
                                                                <li class="level2 nav-1-3-16 first">
                                                                    <a class=" menu-title-lv2"
                                                                       href="<?= URL ?>/productos?linea=<?= strtolower($funcionesNav->normalizar_link($value['categoria'])); ?>&rubro=<?= strtolower($funcionesNav->normalizar_link($value2['subcategoria'])); ?>&id=<?= $value2['id'] ?>">
                                                                        <span class="title"><?= $value2['subcategoria'] ?></span>
                                                                    </a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </li>
                                <li class="level0 custom-item">
                                    <a class="menu-title-lv0" href="<?= URL . '/blogs' ?>">
                                        <span class="title">Blog</span>
                                    </a>
                                </li>
                                <li class="level0 custom-item">
                                    <a class="menu-title-lv0" href="<?= URL . '/contacto' ?>">
                                        <span class="title">Contactanos</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div id="sns_mommenu" class="menu-offcanvas hidden-md hidden-lg ">
                            <div id="sns_mommenu" class="menu-offcanvas hidden-md hidden-lg">

                                <span class="btn2 btn-navbar offcanvas">
                                        <i class="fa fa-align-justify"></i>
                                        <span class="overlay"></span>
                                    </span>
                                <span class="btn2 btn-navbar rightsidebar">
                                        <i class="fa fa-align-right"></i>
                                        <span class="overlay"></span>
                                    </span>
                                <div id="menu_offcanvas" class="offcanvas">
                                    <ul class="mainnav">
                                        <li class="level0 custom-item">
                                            <div class="accr_header">
                                                <a class="menu-title-lv0" href="<?= URL . '/inicio' ?>">
                                                    <span class="title">Inicio</span>
                                                </a>
                                            </div>
                                        </li>

                                        <li class="level0 nav-5 first active">
                                            <div class="accr_header">
                                                <a class=" menu-title-lv0" href="<?= URL . '/productos' ?>">
                                                    <span>Todos los productos</span>
                                                </a>
                                            </div>
                                        </li>

                                        <li class="level0 nav-5 first active">
                                            <div class="accr_header">
                                                <a class=" menu-title-lv0" href="<?= URL . '/blogs' ?>">
                                                    <span>Blog</span>
                                                </a>
                                            </div>
                                        </li>
                                        <li class="level0 nav-5 first active">
                                            <div class="accr_header">
                                                <a class=" menu-title-lv0" href="<?= URL . '/contacto' ?>">
                                                    <span>Contacto</span>
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sns_menu_right col-md-7">
                        <div class="row">
                            <div class="col-md-8">
                                <form id="buscador" method="get" action="<?= URL ?>/productos">
                                    <div class="form-search mt-5">
                                        <div class="input-group">
                                            <input class="form-control h40" type="text" placeholder="Buscar.."
                                                   name="buscar" value="<?php if($buscar != ''){ echo $buscar; }; ?>"/>
                                            <span class="input-group-addon"> <i
                                                        class="login_icon glyphicon glyphicon-search"></i></span>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="block_topsearch col-md-3">
                                <div class="top-cart">
                                    <div class="mycart mini-cart">
                                        <div class="block-minicart">
                                            <div class="tongle">
                                                <i class="fa fa-shopping-cart"></i>
                                                <div class="summary">
                                            <span class="amount">
                                                <a href="#">
                                                    <span><?= count($carro) ?></span>
                                                    ( items )
                                                </a>
                                            </span>
                                                </div>
                                            </div>
                                            <div class="block-content content">
                                                <div class="block-inner">
                                                    <?php if (isset($_POST["eliminarCarrito"])): ?>
                                                        <?php $carrito->delete($_POST["eliminarCarrito"]); ?>
                                                        <?php $funcionesNav->headerMove(CANONICAL); ?>
                                                    <?php endif; ?>

                                                    <?php if (empty($carro)): ?>
                                                        <?php echo "<p>El carrito se encuentra vacío.</p>"; ?>
                                                    <?php else: ?>

                                                        <ol id="cart-sidebar" class="mini-products-list">
                                                            <?php $i = 0; ?>
                                                            <?php $precio = 0; ?>
                                                            <?php foreach ($carro as $carroItem): ?>
                                                                <?php $precio += ($carroItem["precio"] * $carroItem["cantidad"]); ?>
                                                                <li class="item odd">
                                                                    <div class="product-details">
                                                                        <form method="post">
                                                                            <input type="hidden" name="eliminarCarrito"
                                                                                   value="<?= $i ?>">
                                                                            <button class="btn-remove"
                                                                                    onclick="return confirm('¿Vas a eliminar un producto del carrito?').;"
                                                                                    type="submit">Eliminar
                                                                            </button>
                                                                        </form>
                                                                        <p class="product-name">
                                                                            <?= $carroItem["titulo"]; ?>
                                                                            (<?= $carroItem["cantidad"]; ?>)
                                                                        </p>
                                                                        <span class="price"><?= "$" . $carroItem["precio"]; ?></span>
                                                                    </div>
                                                                </li>
                                                                <?php $i++; ?>
                                                            <?php endforeach; ?>
                                                        </ol>
                                                        <p class="cart-subtotal">
                                                            <span class="label">Total:</span>
                                                            <span class="price">$ <?= $precio ?></span>
                                                        </p>
                                                        <div class="actions">
                                                            <a href="<?= URL . "/carrito" ?>">Pasar por caja</a>
                                                            <a class="button gfont go-to-cart"
                                                               href="<?= URL . "/productos" ?>">Seguir
                                                                comprando</a>
                                                        </div>
                                                    <?php endif; ?>
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
    </div>
</div>