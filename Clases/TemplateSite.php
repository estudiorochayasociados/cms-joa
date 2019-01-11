<?php

namespace Clases;

class TemplateSite
{

    public $title;
    public $keywords;
    public $description;
    public $favicon;
    public $canonical;
    public $autor;
    public $made;
    public $copy;
    public $pais;
    public $place;
    public $position;
    public $imagen;

    public function themeInit()
    {
        ?>
        <!DOCTYPE html>
        <html lang="es">
    <head>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="<?= URL ?>/assets/font/font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="<?= URL ?>/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= URL ?>/assets/js/owl-carousel/owl.carousel.css">
        <link rel="stylesheet" href="<?= URL ?>/assets/js/owl-carousel/owl.theme.css">
        <link rel="stylesheet" href="<?= URL ?>/assets/css/style.css"/>
        <link rel="stylesheet" href="<?= URL ?>/assets/css/estilos.css">
        <meta name="viewport" content="width=device-width"/>
        <link rel="shortcut icon" href="<?= URL ?>/assets/images/favicon.ico">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
        <!-- Start of HubSpot Embed Code -->
        <script src="<?= URL ?>/assets/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/4852794.js"></script>
        <!-- End of HubSpot Embed Code -->

        <script type='text/javascript' data-cfasync='false'>window.purechatApi = {
                l: [], t: [], on: function () {
                    this.l.push(arguments);
                }
            };
            (function () {
                var done = false;
                var script = document.createElement('script');
                script.async = true;
                script.type = 'text/javascript';
                script.src = 'https://app.purechat.com/VisitorWidget/WidgetScript';
                document.getElementsByTagName('HEAD').item(0).appendChild(script);
                script.onreadystatechange = script.onload = function (e) {
                    if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
                        var w = new PCWidget({c: '225158be-d376-4357-b284-119878634be3', f: true});
                        done = true;
                    }
                };
            })();
        </script>

        <?php

        echo '<meta charset="utf-8"/>';
        echo '<meta name="author" lang="es" content="' . $this->autor . '" />';
        echo '<link rel="author" href="' . $this->made . '" rel="nofollow" />';
        echo '<meta name="copyright" content="' . $this->copy . '" />';
        echo '<link rel="canonical" href="' . $this->canonical . '" />';
        echo '<meta name="distribution" content="global" />';
        echo '<meta name="robots" content="all" />';
        echo '<meta name="rating" content="general" />';
        echo '<meta name="content-language" content="es-ar" />';
        echo '<meta name="DC.identifier" content="' . $this->canonical . '" />';
        echo '<meta name="DC.format" content="text/html" />';
        echo '<meta name="DC.coverage" content="' . $this->pais . '" />';
        echo '<meta name="DC.language" content="es-ar" />';
        echo '<meta http-equiv="window-target" content="_top" />';
        echo '<meta name="robots" content="all" />';
        echo '<meta http-equiv="content-language" content="es-ES" />';
        echo '<meta name="google" content="notranslate" />';
        echo '<meta name="geo.region" content="AR-X" />';
        echo '<meta name="geo.placename" content="' . $this->place . '" />';
        echo '<meta name="geo.position" content="' . $this->position . '" />';
        echo '<meta name="ICBM" content="' . $this->position . '" />';
        echo '<meta content="public" name="Pragma" />';
        echo '<meta http-equiv="pragma" content="public" />';
        echo '<meta http-equiv="cache-control" content="public" />';
        echo '<meta property="og:url" content="' . $this->canonical . '" />';
        echo '<meta charset="utf-8">';
        echo '<meta content="IE=edge" http-equiv="X-UA-Compatible">';
        echo '<meta content="width=device-width, initial-scale=1" name="viewport">';
        echo '<meta name="language" content="Spanish">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />';
        echo '<title>' . $this->title . '</title>';
        echo '<meta http-equiv="title" content="' . $this->title . '" />';
        echo '<meta name="description" lang=es content="' . $this->description . '" />';
        echo '<meta name="keywords" lang=es content="' . $this->keywords . '" />';
        echo '<link href="' . $this->imagen . '" rel="Shortcut Icon" />';
        echo '<meta name="DC.title" content="' . $this->title . '" />';
        echo '<meta name="DC.subject" content="' . $this->description . '" />';
        echo '<meta name="DC.description" content="' . $this->description . '" />';
        echo '<meta property="og:title" content="' . $this->title . '" />';
        echo '<meta property="og:description" content="' . $this->description . '" />';
        echo '<meta property="og:image" content="' . $this->imagen . '" />';

        ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-127300251-18"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', 'UA-127300251-18');
        </script>

    </head>
        <?php
    }

    public function themeNav()
    {
        include 'assets/inc/nav.inc.php';
    }

    public function themeSideIndex()
    {
        include 'assets/inc/sideIndex.inc.php';
    }

    public function themeSideBlog()
    {
        include 'assets/inc/sideBlog.inc.php';
    }

    public function themeEnd()
    {
        include 'assets/inc/footer.inc.php';
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }
}
