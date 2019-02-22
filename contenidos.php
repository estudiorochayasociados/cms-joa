<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();

//Clases
$contenidos = new Clases\Contenidos();
//Productos

$id = $funciones->antihack_mysqli(isset($_GET["id"]) ? $_GET["id"] : '');
$id = str_replace("-", " ", $id);
$contenidos->set("cod", $id);
$contenido = $contenidos->view();
$template->set("title", "Pinturería Ariel | ".ucfirst($contenido['cod']));
$template->set("description", "");
$template->set("keywords", "");
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
                                        <a href="<?= URL . '/blogs' ?>">
                                            <i class="fa fa-home"></i>
                                            <span> </span>
                                        </a>
                                    </li>
                                    <li class="category3 last">
                                        <span><?= ucfirst($contenido['cod']); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- AND BREADCRUMBS -->
        <div id="sns_content" class="wrap mb-40">
            <div class="container">
                <div class="row">
                    <div id="sns_main" class="col-md-12 col-main">
                        <div id="sns_mainmidle">
                            <div class="blogs-page">
                                <div class="postWrapper v1">
                                    <div class="post-title">
                                        <h1><b><?= mb_strtoupper($contenido['cod']); ?></b></h1>
                                        <hr/>
                                    </div>
                                    <br>
                                    <?= $contenido['contenido']; ?>
                                </div>
                            </div>
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