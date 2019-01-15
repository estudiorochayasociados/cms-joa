<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$usuario = new Clases\Usuarios();
$funciones = new Clases\PublicFunction();
$usuario->logout();
$funciones->headerMove(URL);
?>