<?php
namespace config;
class autoload
{

    public static function runSitio()
    {
        require_once "Config/Minify.php";
        session_start();
        ini_set('display_startup_errors', true);
        error_reporting(E_ALL);
        ini_set('display_errors', true);
        $_SESSION["cod_pedido"] = mb_strtoupper(isset($_SESSION["cod_pedido"]) ? $_SESSION["cod_pedido"] : substr(md5(uniqid(rand())), 0, 10));
        define('URL', "https://" . $_SERVER['HTTP_HOST'] . "/pintureria-ariel");
        define('CANONICAL', "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        define('GOOGLE_TAG', "GTM-M4M4KJJ");
        define('TITULO', "Pintureria Ariel");
        define('TELEFONO', "5555555");
        define('CIUDAD', "San Francisco");
        define('PROVINCIA', "Cordoba");
        define('EMAIL', "web@estudiorochayasoc.com.ar");
        define('PASS_EMAIL', "weAr2010");
        define('SMTP_EMAIL', "cs1008.webhostbox.net");
        define('DIRECCION', "asdasdas a22");
        define('SALT',hash("sha256","salt@estudiorochayasoc.com.ar"));
        define('LOGO', URL . "/assets/images/logo.png");
        define('APP_ID_FB', "");
        spl_autoload_register(
            function ($clase) {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once $ruta;
            }
        );
    }

    public static function runCurl()
    {
        session_start();
        $_SESSION["cod_pedido"] = isset($_SESSION["cod_pedido"]) ? $_SESSION["cod_pedido"] : strtoupper(substr(md5(uniqid(rand())), 0, 7));
        define('URL', "https://" . $_SERVER['HTTP_HOST'] . "/pintureria-ariel");
        define('LOGO', URL . "/assets/images/logo.png");
        define('TITULO', '');
        spl_autoload_register(function ($clase) {
            $ruta = str_replace("\\", "/", $clase) . ".php";
            include_once "../../" . $ruta;
        });
    }

    public static function runSitio2()
    {
        spl_autoload_register(
            function ($clase) {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once "../../" . $ruta;
            }
        );
    }

    public static function runAdmin()
    {
        require_once "../Config/Minify.php";
        session_start();
        $_SESSION["cod_pedido"] = mb_strtoupper(isset($_SESSION["cod_pedido"]) ? $_SESSION["cod_pedido"] : substr(md5(uniqid(rand())), 0, 10));
        define('SALT',hash("sha256","salt@estudiorochayasoc.com.ar"));
        define('URLSITE', "https://" . $_SERVER['HTTP_HOST'] . "/pintureria-ariel");
        define('URL', "https://" . $_SERVER['HTTP_HOST'] . "/pintureria-ariel/admin");
        define('CANONICAL', "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        require_once "../Clases/Zebra_Image.php";
        spl_autoload_register(
            function ($clase) {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once "../" . $ruta;
            }
        );
    }
}
