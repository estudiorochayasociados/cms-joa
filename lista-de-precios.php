<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$productos = new Clases\Productos();
$usuario = new Clases\Usuarios();
$funciones = new Clases\PublicFunction();

include "vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require "vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php";

$usuarioData = $usuario->view_sesion();
if (count($usuarioData) != 0) {
    //var_dump($usuarioData);
    if ($usuarioData["descuento"] == 1) {

        $filename = "LISTA DE PRECIOS " . date("d-m-Y") . ".xls";

        $productosTotal = $productos->list("");

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', "CODIGO PRODUCTO");
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', "TITULO");
        //$objPHPExcel->getActiveSheet()->SetCellValue('C1', "PRECIO");
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', "PRECIO MAYORISTA");
        //$objPHPExcel->getActiveSheet()->SetCellValue('E1', "MELI");

        $rowCount = 2;

        foreach ($productosTotal as $producto) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $producto["cod_producto"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $producto["titulo"]);
            //$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $producto["precio"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "$" . $producto["precio_mayorista"]);
            //$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $producto["meli"]);
            $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

        $objWriter->save($filename);

        $_SESSION['lista-de-precios'] = $filename;

        $funciones->headerMove(URL."/sesion?d");
    } else {
        $funciones->headerMove(URL . "/index.php");
    }
} else {
    $funciones->headerMove(URL . "/index.php");
}
