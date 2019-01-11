<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones= new Clases\PublicFunction();

//Clases
$imagenes = new Clases\Imagenes();
$novedades = new Clases\Novedades();
$banners = new Clases\Banner();
//Productos
$id       = isset($_GET["id"]) ? $_GET["id"] : '';
$novedades->set("id",$id);
$novedadData = $novedades->view();
$imagenes->set("cod",$novedadData['cod']);
$imagenData = $imagenes->view();
$novedadesData = $novedades->list('');
$fecha = explode("-", $novedadData['fecha']);
$template->set("title", ucfirst($novedadData['titulo']));
$template->set("description", $novedadData['description']);
$template->set("keywords", $novedadData['keywords']);
$template->set("imagen", URL."/".$imagenData['ruta']);
$template->set("favicon", LOGO);
$template->themeInit();
//
?>
    <body id="bd" class="cms-index-index2 header-style2 prd-detail blog-pagev1 detail cms-simen-home-page-v2 default cmspage">
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
                                    <a href="<?=URL . '/blogs' ?>">
                                        <i class="fa fa-home"></i>
                                        <span>Blogs</span>
                                    </a>
                                </li>
                                <li class="category3 last">
                                    <span>Blog</span>
                                </li>
                                <li class="category3 last">
                                    <span><?= ucfirst($novedadData['titulo']); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- AND BREADCRUMBS -->
<div id="sns_content" class="wrap">
                <div class="container">
                    <div class="row">
                        <?php $template->themeSideBlog(); ?>
                        <div id="sns_main" class="col-md-9 col-main">
                            <div id="sns_mainmidle">
                                <div class="blogs-page">
                                    <div class="postWrapper v1">
                                        <div class="post-title">
                                            <a><?= ucfirst($novedadData['titulo']); ?></a><hr/>
                                        </div>
                                        <a class="post-img">
                                            <img src="<?= URL. '/' . $imagenData['ruta']; ?>" alt="<?= $novedadData['titulo']; ?>">
                                        </a>
                                        <br>
                                        <div class="date">
                                            <span class="poster"><?php echo $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0] ?></span>
                                        </div>

                                        <div class="post-content">
                                            <p class="text1">
                                                    <?= $novedadData['desarrollo']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5" >
                                <!-- AddToAny BEGIN -->
                                <label >Compartir en:</label>
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
    </body>
<?php
$template->themeEnd();
?>