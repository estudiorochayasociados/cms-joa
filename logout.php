<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$usuario = new Clases\Usuarios();
$usuario->logout();
?>