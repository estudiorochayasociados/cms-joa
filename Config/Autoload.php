<?php
namespace config;
class autoload
{
    public static function runSitio()
    {
        session_start();
        $_SESSION["cod_pedido"] = mb_strtoupper(isset($_SESSION["cod_pedido"]) ? $_SESSION["cod_pedido"] : substr(md5(uniqid(rand())), 0, 10));
        define('URL', "http://".$_SERVER['HTTP_HOST']."/pintureria%20ariel");
        define('CANONICAL', "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
        define('GOOGLE_TAG', "GTM-M4M4KJJ");
        define('TITULO', "Pintureria Ariel");
        define('TELEFONO', "5555555");
        define('CIUDAD', "San Francisco");
        define('PROVINCIA', "Cordoba");
        define('EMAIL', "web@estudiorochayasoc.com.ar");
        define('PASS_EMAIL', "weAr2010");
        define('SMTP_EMAIL', "estudiorochayasoc.com.ar");
        define('DIRECCION', "asdasdas a22");
        define('LOGO', URL . "/assets/images/logo.png");
        define('APP_ID_FB', "");
        spl_autoload_register(
            function($clase)
            {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once $ruta;
            }
        );
    }

    public static function runSitio2()
    {
        spl_autoload_register(
            function($clase)
            {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once "../../".$ruta;
            }
        );
    }

    public static function runAdmin()
    {
        session_start();
        define('URLSITE',"http://".$_SERVER['HTTP_HOST']."/pintureria%20ariel");
        define('URL', "http://".$_SERVER['HTTP_HOST']."/pintureria ariel/admin");
        define('CANONICAL', "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
        require_once "../Clases/Zebra_Image.php";
        spl_autoload_register(
            function ($clase)
            {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once "../" . $ruta;
            }
        );
    }
}
