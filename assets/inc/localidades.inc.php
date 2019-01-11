<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runSitio2();
$funciones = new Clases\PublicFunction();
$funciones->localidades();
?>