<?php
$public = new Clases\PublicFunction();
$productos = new Clases\Productos();

include "../vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require "../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php";

$filename = "LISTA DE PRECIOS " . date("d-m-Y") . ".xls";

$productosTotal = $productos->list("");

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', "CODIGO PRODUCTO");
$objPHPExcel->getActiveSheet()->SetCellValue('B1', "TITULO");
$objPHPExcel->getActiveSheet()->SetCellValue('C1', "PRECIO");
$objPHPExcel->getActiveSheet()->SetCellValue('D1', "PRECIO MAYORISTA");
$objPHPExcel->getActiveSheet()->SetCellValue('E1', "MELI");

$rowCount = 2;

foreach ($productosTotal as $producto) {
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $producto["cod_producto"]);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $producto["titulo"]);
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $producto["precio"]);
    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $producto["precio_mayorista"]);
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $producto["meli"]);
    $rowCount++;
}

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

$objWriter->save($filename);

$public->headerMove($filename);