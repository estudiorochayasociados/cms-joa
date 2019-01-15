<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
$template->set("title", "Pinturería Ariel | Contacto");
$template->set("description", "Contacto Pinturería Ariel");
$template->set("keywords", "Contacto Pinturería Ariel");
$template->set("favicon", LOGO);
$template->themeInit();
$usuarios = new Clases\Usuarios();
$usuarioSesion = $usuarios->view_sesion();
if (count($usuarioSesion) == 0) {
    $funciones->headerMove(URL . "/index");
}
?>
<body id="bd" class="cms-index-index2 header-style2 prd-detail sns-contact-us cms-simen-home-page-v2 default cmspage">
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
                                    <span>Panel de usuario</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END BREADCRUMBS -->
    <div id="sns_content" class="wrap layout-m">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="wrap-in">
                        <div class="block block-blog-inner">
                            <div class="block-content">
                                <div class="menu-categories">
                                    <div class="block-title">
                                        <strong>Panel</strong>
                                    </div>
                                    <ul>
                                        <li>
                                            <a href="<?= URL ?>/sesion/cuenta">
                                                <span class="no_line_h"><i class="fa fa-user"
                                                                           aria-hidden="true"></i></span> Mi cuenta
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= URL ?>/sesion/pedidos">
                                                <span class="no_line_h"><i class="fa fa-bookmark"
                                                                           aria-hidden="true"></i></span> Mis
                                                pedidos
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= URL ?>/sesion/logout">
                                                <span class="no_line_h"><i class="fa fa-sign-out" aria-hidden="true"></i></span>Salir
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $op = isset($_GET["op"]) ? $_GET["op"] : 'pedidos';
                if ($op != '') {
                    include("assets/inc/sesion/" . $op . ".php");
                }
                ?>

            </div>
        </div>
    </div>
</div>
</body>
<?php
$template->themeEnd();
?>
