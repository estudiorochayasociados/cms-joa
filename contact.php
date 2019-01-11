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
//
?>
    <body id="bd"
          class="cms-index-index2 header-style2 prd-detail sns-contact-us cms-simen-home-page-v2 default cmspage">
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
                                        <span>Contacto</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- AND BREADCRUMBS -->
        <div id="sns_content" class="wrap layout-m">
            <div class="container">
                <div class="row">
                    <div id="contact_gmap" class="col-md-12">
                        <div class="page-title">
                            <h1>Contactanos</h1>
                        </div>

                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3404.501697097214!2d-62.0759642848517!3d-31.42785248140028!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95cad7e1edd2fdf9%3A0x80e062808a2833c7!2sPintureria+Ariel!5e0!3m2!1ses!2sar!4v1543502523334"
                                width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>

                        <div class="row clearfix">
                            <div class="col-md-4 contact-info mt-40">
                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-map-marker"></i> Las Malvinas 930 - San Francisco (CBA.)</li>
                                    <li><i class="fa-li fa fa-phone"></i> 03564 - 438484</li>
                                    <li><i class="fa-li fa fa-phone"></i> 03564 - 443393</li>
                                    <li><i class="fa-li fa fa-phone"></i> 03564 - 479003</li>
                                    <li><i class="fa-li fa fa-envelope-o"></i><a href="mailto:marketing@pintureriasariel.com.ar">marketing@pintureriasariel.com.ar</a></li>
                                </ul>
                            </div>
                            <div class="col-md-8">
                                <h4>Deja tu Mensaje</h4>
                                <form method="post">
                                    <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/shell.js"></script>
                                    <script>
                                        hbspt.forms.create({
                                            portalId: "4852794",
                                            formId: "60f59902-84f3-4137-b859-a4bd1a0bc54b"
                                        });
                                    </script>
                                </form>
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