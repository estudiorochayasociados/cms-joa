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

        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '<?= GOOGLE_TAG ?>');
        </script>

        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="<?= URL ?>/assets/font/font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="<?= URL ?>/assets/js/owl-carousel/owl.carousel.min.css">
        <link rel="stylesheet" href="<?= URL ?>/assets/js/owl-carousel/owl.theme.min.css">
        <link rel="stylesheet" href="<?= URL ?>/assets/css/style.min.css"/>
        <link rel="stylesheet" href="<?= URL ?>/assets/css/estilos.min.css">
        <meta name="viewport" content="width=device-width"/>
        <link rel="shortcut icon" href="<?= URL ?>/assets/images/favicon.ico">
         <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/4852794.js"></script>
        <script type='text/javascript' data-cfasync='false'>window.purechatApi = { l: [], t: [], on: function () { this.l.push(arguments); } }; (function () { var done = false; var script = document.createElement('script'); script.async = true; script.type = 'text/javascript'; script.src = 'https://app.purechat.com/VisitorWidget/WidgetScript'; document.getElementsByTagName('HEAD').item(0).appendChild(script); script.onreadystatechange = script.onload = function (e) { if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) { var w = new PCWidget({c: '225158be-d376-4357-b284-119878634be3', f: true }); done = true; } }; })();</script>
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
