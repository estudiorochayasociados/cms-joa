<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$productos = new Clases\Productos();
$usuario = new Clases\Usuarios();
$funciones = new Clases\PublicFunction();
$usuarioData = $usuario->view_sesion();
if (count($usuarioData) != 0) {
    //var_dump($usuarioData);
    if ($usuarioData["descuento"] == 1) {
        $filename = "LISTA DE PRECIOS ".date("d-m-Y").".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . $filename);

        $productosTotal = $productos->list("");
        echo "<table>";
        echo "<thead><th>CODIGO</th><th>TITULO</th><th>PRECIO</th></thead><tbody>";
        foreach ($productosTotal as $producto) {
            echo "<tr>";
            echo "<td style='text-align: left'>" . $producto["cod_producto"] . "</td>";
            echo "<td style='text-align: left'>" . $producto["titulo"] . "</td>";
            echo "<td style='text-align: left'>$" . $producto["precio_mayorista"] . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        $funciones->headerMove(URL . "/index.php");
    }
}else {
    $funciones->headerMove(URL . "/index.php");
}