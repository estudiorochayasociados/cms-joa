<?php
$funcionesFooter = new Clases\PublicFunction();
$enviarFooter = new Clases\Email();
?>

<!-- PARTNERS -->
<div id="sns_partners" class="wrap">
    <div class="container">
        <div class="slider-wrap">
            <div class="partners_slider_in">
                <div id="partners_slider1" class="our_partners owl-carousel owl-theme owl-loaded"
                     style="display: inline-block">
                    <?php
                    $galerias = new Clases\Galerias();
                    $imgsFooter = new Clases\Imagenes();
                    $galeriasData = $galerias->list('');
                    foreach ($galeriasData as $gal) {
                        $imgsFooter->set("cod", $gal['cod']);
                        $imgFooter = $imgsFooter->view();
                        ?>
                        <div class="item" style=" height: 150px; background: url(<?= URL . '/' . $imgFooter['ruta'] ?>) no-repeat center center/70%;">
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- AND PARTNERS -->

    <hr class="hrfoot"/>
    <!-- FOOTER MD LG -->
    <div id="sns_footer" class="footer_style vesion2 wrap">
        <div id="sns_footer_top" class="footer">
            <div class="container">
                <div class="container_in">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xs-12 column0">
                            <img src="<?= URL ?>/assets/archivos/logo-grande.png"/>
                        </div>
                        <div class="col-md-5 col-sm-6 col-xs-12 column0">
                            <div class="contact_us">
                                <h6 class="mb-5">Contactanos</h6>
                                <ul>
                                    <li class="pd-right">
                                        <div class="clearfix">
                                            <div class="divfoot1">
                                                <img src="<?=URL?>/assets/images/iconos/marker.png" width="55%">
                                            </div>
                                            <div class="divfoot2">
                                                <h5>Las Malvinas 930 - San Francisco (CBA)</h5>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="clearfix">
                                            <div class="divfoot1">
                                                <img src="<?=URL?>/assets/images/iconos/call.png" width="50%">
                                            </div>
                                            <div class="divfoot2">
                                                <h5>(03564) 438484 / (03564) 443393</h5>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="clearfix">
                                            <div class="divfoot1">
                                                <img src="<?=URL?>/assets/images/iconos/mail.png" width="50%">
                                            </div>
                                            <div class="divfoot2">
                                                <h5>
                                                    <a href="mailto:marketing@pintureriasariel.com.ar">marketing@pintureriasariel.com.ar</a>
                                                </h5>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-phone-12 col-xs-12 col-sm-6 col-md-4 column column4">
                            <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/shell.js"></script>
                            <script>
                                hbspt.forms.create({
                                    portalId: "4852794",
                                    formId: "5d84996b-5f58-471a-b513-cb4bda58acd2"
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="hrfoot"/>

        <div id="sns_footer_bottom" class="footer">
            <div class="container">
                <div class="row">
                    <div class="bottom-pd1 col-sm-12 text-right">
                        <div class="sns-copyright">
                            © 2018 Todos los derechos reservados, Pinturería Ariel. Desarrollado por <a href="http://www.estudiorochayasoc.com.ar" target="_blank">Estudio Rocha & Asociados</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- AND FOOTER MD LG-->
</div>

<script src="<?= URL ?>/assets/js/bootstrap.min.js"></script>
<script src="<?= URL ?>/assets/js/less.min.js"></script>
<script src="<?= URL ?>/assets/js/owl-carousel/owl.carousel.min.js"></script>
<script src="<?= URL ?>/assets/js/sns-extend.js"></script>
<script src="<?= URL ?>/assets/js/custom.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="<?= URL ?>/assets/js/list-grid.js"></script>

<div style="position: fixed;bottom:20px;left:15px;z-index: 999">
    <a target="_blank" href="https://m.me/pintureria.ariel"
       style="vertical-align:middle;box-shadow:0px 0px 10px #333;font-size:14px;padding:10px;border-radius:5px;background-color:#1787fb;color:white;text-shadow:none;">
        <span class="hidden-xs hidden-sm"><i class="ifoot fa fa-facebook" aria-hidden="true"></i> Comunicate vía</span>
        Facebook
    </a> &nbsp;
    <a target="_blank" href="https://wa.me/543564335294"
       style="vertical-align:middle;box-shadow:0px 0px 10px #333;font-size:14px;padding:10px;border-radius:5px;background-color:#369317;color:white;text-shadow:none;">
        <span class="hidden-xs hidden-sm"><i class="ifoot fa fa-whatsapp" aria-hidden="true"></i> Comunicate vía</span>
        WhatsApp
    </a>
</div>

<script>
    $("#provincia").change(function () {
        $("#provincia option:selected").each(function () {
            elegido = $(this).val();
            $.ajax({
                type: "GET",
                url: "<?=URL ?>/assets/inc/localidades.inc.php",
                data: "elegido=" + elegido,
                dataType: "html",
                success: function (data) {
                    $('#localidad option').remove();
                    var substr = data.split(';');
                    for (var i = 0; i < substr.length; i++) {
                        var value = substr[i];
                        $("#localidad").append(
                            $("<option></option>").attr("value", value).text(value)
                        );
                    }
                }
            });
        });
    })
</script>


<?php include("login.inc.php"); ?>


</html>