<!-- PARTNERS -->
<div id="sns_partners" class="wrap">
    <div class="container">
        <div class="slider-wrap">
            <div class="partners_slider_in">
                <div id="partners_slider1" class="our_partners owl-carousel owl-theme owl-loaded" style="display: inline-block">
                    <?php
                    $galerias = new Clases\Galerias();
                    $imgsFooter = new Clases\Imagenes();
                    $galeriasData = $galerias->list('');
                    foreach ($galeriasData as $gal) {
                        $imgsFooter->set("cod", $gal['cod']);
                        $imgFooter = $imgsFooter->view();
                        ?>
                        <div class="item">
                            <a class="banner11" href="#" target="_blank">
                                <img alt="" src="<?= URL . '/' . $imgFooter['ruta'] ?>">
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- AND PARTNERS -->

    <!-- FOOTER MD LG -->
    <div id="sns_footer" class="footer_style vesion2 wrap visible-md visible-lg">
        <div id="sns_footer_top" class="footer">
            <div class="container">
                <div class="container_in">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xs-12 column0">
                            <div class="contact_us">
                                <h6>Contactanos</h6>
                                <ul class="fa-ul">
                                    <li class="pd-right">
                                        <i class="fa-li fa fw fa-home"> </i>
                                        Las Malvinas 930 - San Francisco (CBA)
                                    </li>
                                    <li>
                                        <i class="fa-li fa fw fa-phone"> </i>
                                        <p>(03564) 438484</p>
                                        <p>(03564) 443393</p>
                                    </li>
                                    <li>
                                        <i class="fa-li fa fw fa-envelope"> </i>
                                        <p>
                                            <a href="mailto:marketing@pintureriasariel.com.ar">marketing@pintureriasariel.com.ar</a>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-phone-12 col-xs-6 col-sm-3 col-md-2 column column1">
                            <h6>Service</h6>
                            <ul>
                                <li>
                                    <a href="#">rices & Currencies</a>
                                </li>
                                <li>
                                    <a href="#">Secure Payment</a>
                                </li>
                                <li>
                                    <a href="#">Delivery Times & Costs</a>
                                </li>
                                <li>
                                    <a href="#">Returns & Exchanges</a>
                                </li>
                                <li>
                                    <a href="#">FAQ's</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-phone-12 col-xs-6 col-sm-3 col-md-2 column column2">
                            <h6>account</h6>
                            <ul>
                                <li>
                                    <a href="#">My account</a>
                                </li>
                                <li>
                                    <a href="#">Wishlist</a>
                                </li>
                                <li>
                                    <a href="#">Order history</a>
                                </li>
                                <li>
                                    <a href="#">Specials</a>
                                </li>
                                <li>
                                    <a href="#">Gift vouchers</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-phone-12 col-xs-6 col-sm-3 col-md-2 column column3">
                            <h6>information</h6>
                            <ul>
                                <li>
                                    <a href="#">My account</a>
                                </li>
                                <li>
                                    <a href="#">Wishlist</a>
                                </li>
                                <li>
                                    <a href="#">Order history</a>
                                </li>
                                <li>
                                    <a href="#">Specials</a>
                                </li>
                                <li>
                                    <a href="#">Gift vouchers</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-phone-12 col-xs-6 col-sm-3 col-md-3 column column4">
                            <div class="subcribe-footer">
                                <div class="block_border block-subscribe">
                                    <div class="block_head">
                                        <h6>Newsletter</h6>
                                        <p>Register your email for news</p>
                                    </div>
                                    <form id="newsletter-validate-detail">
                                        <div class="block_content">
                                            <div class="input-box">
                                                <div class="input_warp">
                                                    <input id="newsletter" class="input-text required-entry validate-email" type="text" title="Sign up for our newsletter" placeholder="Your email here" name="email">
                                                </div>
                                                <div class="button_warp">
                                                    <button class="button gfont" title="Subcribe" type="submit">
                                                                <span>
                                                                    <span>Subscribe</span>
                                                                </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="sns_footer_bottom" class="footer">
            <div class="container">
                <div class="row">
                    <div class="bottom-pd1 col-sm-12">
                        <div class="sns-copyright">
                            © 2018 Todos los derechos reservados, Pinturerías Ariel. Copyright by <a href="http://www.estudiorochayasoc.com" target="_blank">Estudio Rocha & Asociados</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- AND FOOTER MD LG-->

    <!-- FOOTER XS-->
    <div id="sns_footer" class="footer_style vesion2 wrap visible-xs ">
        <div id="sns_footer_top" class="footer">
            <div class="container">
                <div class="container_in">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xs-12 column0">
                            <div class="contact_us">
                                <h6>Contactanos</h6>
                                <ul class="fa-ul">
                                    <li class="pd-right">
                                        <i class="fa-li fa fw fa-home"> </i>
                                        Las Malvinas 930 - San Francisco (CBA)
                                    </li>
                                    <li>
                                        <i class="fa-li fa fw fa-phone"> </i>
                                        <p>(03564) 438484</p>
                                        <p>(03564) 443393</p>
                                    </li>
                                    <li>
                                        <i class="fa-li fa fw fa-envelope"> </i>
                                        <p>
                                            <a href="mailto:marketing@pintureriasariel.com.ar">marketing@pintureriasariel.com.ar</a>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="sns_footer_bottom" class="footer">
            <div class="container">
                <div class="row">
                    <div class="bottom-pd1 col-sm-12">
                        <div class="sns-copyright">
                            © 2018 Todos los derechos reservados, Pinturerías Ariel. Copyright by <a href="http://www.estudiorochayasoc.com" target="_blank">Estudio Rocha & Asociados</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- AND FOOTER MD LG-->
</div>

<script src="<?= URL ?>/assets/js/jquery-1.9.1.min.js"></script>
<script src="<?= URL ?>/assets/js/bootstrap.min.js"></script>
<script src="<?= URL ?>/assets/js/less.min.js"></script>
<script src="<?= URL ?>/assets/js/owl-carousel/owl.carousel.min.js"></script>
<script src="<?= URL ?>/assets/js/sns-extend.js"></script>
<script src="<?= URL ?>/assets/js/custom.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="<?= URL ?>/assets/js/list-grid.js"></script>
</html>