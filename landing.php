<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();

//Clases
$imagenes = new Clases\Imagenes();
$landing = new Clases\Landing();
$categorias = new Clases\Categorias();
//Productos
$cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
$landing->set("cod", $cod);
$landing_ = $landing->view();
$imagenes->set("cod", $landing_['cod']);
$imagenData = $imagenes->list(array("cod = '" . $landing_['cod'] . "'"));
$landingData = $landing->list('');
$landingData = $landing->list('');
$categorias->set("cod", $landing_["categoria"]);
$categoria = $categorias->view();
$i = 0;
$fecha = explode("-", $landing_['fecha']);
$template->set("title", ucfirst($landing_['titulo']));
$template->set("description", $landing_['description']);
$template->set("keywords", $landing_['keywords']);
$template->set("favicon", LOGO);
$template->themeInit();

switch ($categoria["titulo"]) {
    case "Informacion":
        $titulo_form = "Solicitá más información";
        $boton_form = "¡Pedir más info!";
        break;
    case "Compra":
        $titulo_form = "Llená el formulario y comprá online";
        $boton_form = "¡Comprar!";
        break;
    case "Sorteo":
        $titulo_form = "Participá del sorteo";
        $boton_form = "¡Participar!";
        break;
    case "Evento":
        $titulo_form = "Inscribite al evento";
        $boton_form = "¡Inscribirme!";
        break;
    default:
        $titulo_form = "Completar el formulario";
        $boton_form = "¡Enviar mis datos!";
        break;
}
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
            <div class="container">
                <div class="row">
                    <div id="sns_main" class="col-main">
                        <div id="sns_mainmidle">
                            <div class="blogs-page col-md-8">
                                <div class="postWrapper v1">
                                    <div class="post-title">
                                        <h3><?= ucfirst($landing_['titulo']); ?></h3>
                                        <hr/>
                                    </div>
                                    <?php if (count($imagenData) >= 1) { ?>
                                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                            <div class="carousel-inner" role="listbox">
                                                <?php foreach ($imagenData as $img) { ?>
                                                    <div class="item <?php if ($i == 0) {
                                                        echo "active";
                                                    } ?>">
                                                        <img src="<?= URL . '/' . $img['ruta']; ?>" alt="<?= $landing_['titulo']; ?>" width="100%">
                                                    </div>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                            </div>
                                            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <div class="post-content fs-16">
                                        <p class="fs-20">
                                            <?= $landing_['desarrollo']; ?>
                                        </p>
                                        <div class="mt-15">
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
                            <div class="blogs-page col-md-4 ">
                                <div>
                                    <h3><?= $titulo_form ?></h3>
                                    <hr/>
                                    <form method="post" class="row" id="Formulario de Contacto" action="<?= URL ?>/gracias">
                                        <label class="col-xs-12 col-sm-12 col-md-6">
                                            Nombre <span style="color:red">(*)</span>:<br/>
                                            <input type="text" name="nombre" class="form-control" required/>
                                        </label>
                                        <label class="col-xs-12 col-sm-12 col-md-6">
                                            Apellido <span style="color:red">(*)</span>:<br/>
                                            <input type="text" name="apellido" class="form-control" required/>
                                        </label>
                                        <label class="col-xs-12 col-sm-12 col-md-12">
                                            Landing:<br/>
                                            <input type="text" readonly name="landing" class="form-control" value="<?= mb_strtoupper($landing_["titulo"]) ?>"/>
                                        </label>
                                        <label class="col-xs-12 col-sm-12 col-md-12">
                                            Teléfono:<br/>
                                            <input type="text" name="telefono" class="form-control"/>
                                        </label>
                                        <label class="col-xs-12 col-sm-12 col-md-12">
                                            Email <span style="color:red">(*)</span>:<br/>
                                            <input type="email" name="email" class="form-control" required/>
                                        </label>
                                        <label class="col-xs-12 col-sm-12 col-md-12">
                                            Mensaje:<br/>
                                            <textarea name="mensaje" class="form-control"></textarea>
                                        </label>
                                        <label class="col-xs-12 col-sm-12  col-md-12">
                                            <input type="submit" name="enviar" class="btn btn-block btn-success" value="<?= $boton_form ?>"/>
                                        </label>
                                    </form>
                                    <hr/>
                                </div>
                                <div class="mt-40 text-center">
                                    <h5><b>Comunicate también por:</b></h5>
                                    <div>
                                        <a target="_blank" href="https://wa.me/543564335294" class="btn btn-block btn-success fs-18">
                                            <i class="ifoot fa fa-whatsapp" aria-hidden="true"></i> WhatsApp
                                        </a>
                                        <a target="_blank" href="tel:543564335294" class="btn btn-block btn-info fs-19">
                                            <i class="ifoot fa fa-mobile" aria-hidden="true"></i> 3564 335294
                                        </a>
                                    </div>
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