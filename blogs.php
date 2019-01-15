<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones= new Clases\PublicFunction();
$template->set("title", "Pinturería Ariel | Blogs");
$template->set("description", "");
$template->set("keywords", "");
$template->set("favicon", LOGO);
$template->themeInit();
$novedades = new Clases\Novedades();
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : '0';
$novedadesData = $novedades->listWithOps('', '', (3 * $pagina) . ',' . 3);
$novedadesPaginador = $novedades->paginador('', 3);
$imagenes = new Clases\Imagenes();

if(@count($_GET) == 0) {
    $anidador = "?";
} else {
    $anidador = "&";
}
?>
    <body id="bd" class="cms-index-index2 header-style2 prd-detail blog-pagev1 cms-simen-home-page-v2 default cmspage">
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
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- AND BREADCRUMBS -->

            <!-- CONTENT -->
            <div id="sns_content" class="wrap">
                <div class="container">
                    <div class="row">

                        <?php $template->themeSideBlog(); ?>

                        <div id="sns_main" class="col-md-9 col-main">
                            <div id="sns_mainmidle">
                                <div class="blogs-page">
                                    <?php
                                    foreach ($novedadesData as $nov ) {
                                        $imagenes->set("cod",$nov['cod']);
                                        $img=$imagenes->view();
                                        $fecha = explode("-", $nov['fecha']);
                                        ?>
                                        <div class="postWrapper v1">
                                            <a class="post-img" href="<?php echo URL . '/blog/' . $funciones->normalizar_link($nov['titulo']) . "/" . $nov['cod'] ?>"
                                               style="height:440px;background:url(<?= URL . '/' . $img['ruta'] ?>)center/cover;">
                                            </a>
                                            <div class="date">
                                                <span class="poster"><?php echo $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0] ?></span>
                                            </div>
                                            <div class="post-title">
                                                <a href="<?php echo URL . '/blog/' . $funciones->normalizar_link($nov['titulo']) . "/" . $nov['cod'] ?>"><?= ucfirst($nov['titulo']) ?></a>
                                            </div>
                                            <div class="post-content">
                                                <p><?php echo strip_tags(substr($nov["desarrollo"],0,400)); ?>...</p>
                                            </div>
                                            <div class="link-readmore">
                                                <a title="Leer más" href="<?php echo URL . '/blog/' . $funciones->normalizar_link($nov['titulo']) . "/" . $nov['cod'] ?>">Leer más</a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>


                                </div>
                                <div class="blog-toolbar">
                                    <div class="toolbar clearfix">
                                        <div class="toolbar-inner">
                                            <div class="pager">
                                                <div class="pages">
                                                    <ol>
                                                        <!--
                                                        <li class="current">1</li>
                                                        <li>
                                                            <a href="#">2</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">3</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">4</a>
                                                        </li>
                                                        <li>
                                                            <a class="next i-next" title="Next" href="#"></a>
                                                        </li>
                                                        -->
                                                        <?php
                                                        if ($novedadesPaginador != 1 && $novedadesPaginador != 0) {
                                                            $links = '';
                                                            $links .= "<li><a href='" . CANONICAL."".$anidador."pagina=1'>1</a></li>";
                                                            $i = max(2, $pagina - 5);

                                                            if ($i > 2) {
                                                                $links .= "<li><a href='#'>...</a></li>";
                                                            }
                                                            for (; $i < min($pagina + 6, $novedadesPaginador); $i++) {
                                                                $links .= "<li ><a href='" . CANONICAL."".$anidador."pagina=". $i . "'>" . $i . "</a></li>";
                                                            }
                                                            if ($i != $novedadesPaginador) {
                                                                $links .= "<li><a href='#'>...</a></li>";
                                                                $links .= "<li><a href='" . CANONICAL."".$anidador."pagina=". $novedadesPaginador . "'>" . $novedadesPaginador . "</a></li>";
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
                                </div>
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