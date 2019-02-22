<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();

$template->set("title", "Pinturería Ariel | Gracias por enviarnos un mensaje");
$template->set("description", "Gracias por enviarnos tu mensaje, pronto responderemos");
$template->set("keywords", "enviar contacto, especialistas en pinturas, contactar pintureria");
$template->set("favicon", LOGO);
$template->themeInit();
//
?>
    <body id="bd" class="cms-index-index2 header-style2 prd-detail blog-pagev1 detail cms-simen-home-page-v2 default cmspage">
    <div id="sns_wrapper">
        <div id="sns_breadcrumbs" class="wrap mb-20" style="background: #176BB3">
            <div class="container">
                <div class="row">
                    <div class="col-md-2 col-xs-6 pt-20 pb-10">
                        <img src="<?= LOGO ?>" width="100%"/>
                    </div>
                    <div class="col-md-4 hidden-xs hidden-sm pt-20 pb-10"></div>
                    <div class="col-md-6 col-xs-6 pt-20 pb-10 text-right">
                        <a href="<?= URL ?>/productos" class="mt-10 btn btn-default btn-sm fs-12"><b>IR A PRODUCTOS</b></a>
                    </div>
                </div>
            </div>
        </div>
        <div id="sns_content" class="wrap">
            <div class="container text-center pt-50">
                <h1>¡GRACIAS POR CONTACTARTE CON NOSOTROS!</h1>
                <h4>En pocos minutos un operador se estará comunicando con vos.</h4>
                <i class="fa fa-check-circle fs-50" style="font-size:80px !important;color:green"></i>
            </div>
        </div>
    </div>
    </body>
<?php
$template->themeEnd();
?>