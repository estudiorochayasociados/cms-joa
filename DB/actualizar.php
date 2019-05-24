<?php
require_once "../Config/Autoload.php";
Config\Autoload::runAdmin();
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$conexion = new Clases\Conexion();
$con = $conexion->con();
include "../vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require "../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php";
$sql_variables = array('cod', 'titulo', 'precio', 'peso', 'precio_mayorista', 'precioDescuento', 'stock', 'desarrollo', 'categoria', 'subcategoria', 'keywords', 'description', 'fecha', 'meli', 'url', 'cod_producto');
$productos_total = $productos->list("");
$productos->editStock0();
?>
<div class="col-md-12">
    <table>
        <?php

        /*
         * LINEA: A
         * RUBRO: B
         * ARTICULO: C
         * DESCRIPCIÓN: D
         * PESO: E
         * MARCA: G
         * PRECIO MINORISTA: Y
         * PRECIO MAYORISTA: AB
         * STOCK: AD
         *
         * Si stock tiene 0 no se sube.
         * Si el producto ya existe se edita
         */

        $allowedExtensions = array("xls", "xlsx");
        $objPHPExcel = PHPEXCEL_IOFactory::load("productosImportados.xls");

        $objPHPExcel->setActiveSheetIndex(0);
        $numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $numCols = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
        $numCols = (ord(strtolower($numCols)) - 96);

        for ($row = 2; $row <= $numRows; $row++) {
            if ($objPHPExcel->getActiveSheet()->getCell("D" . $row)->getCalculatedValue() != '') {
                $cod = substr(md5(uniqid(rand())), 0, 20);
                $titulo = $objPHPExcel->getActiveSheet()->getCell("D" . $row)->getCalculatedValue();
                $precio = $objPHPExcel->getActiveSheet()->getCell("Y" . $row)->getCalculatedValue();
                $peso = $objPHPExcel->getActiveSheet()->getCell("E" . $row)->getCalculatedValue();
                $precio_mayorista = $objPHPExcel->getActiveSheet()->getCell("Z" . $row)->getCalculatedValue();
                $stock = $objPHPExcel->getActiveSheet()->getCell("AD" . $row)->getCalculatedValue();
                $desarrollo = $objPHPExcel->getActiveSheet()->getCell("D" . $row)->getCalculatedValue();
                $categoria = $objPHPExcel->getActiveSheet()->getCell("A" . $row)->getCalculatedValue();
                $subcategoria = $objPHPExcel->getActiveSheet()->getCell("B" . $row)->getCalculatedValue();
                $cod_producto = str_pad($objPHPExcel->getActiveSheet()->getCell("A" . $row)->getCalculatedValue(), 3, "0", STR_PAD_LEFT) . "/" . str_pad($objPHPExcel->getActiveSheet()->getCell("B" . $row)->getCalculatedValue(), 3, "0", STR_PAD_LEFT) . "/" . str_pad($objPHPExcel->getActiveSheet()->getCell("C" . $row)->getCalculatedValue(), 4, "0", STR_PAD_LEFT);

                $productos->set("cod", isset($cod) ? $cod : substr(md5(uniqid(rand())), 0, 10));
                $productos->set("titulo", isset($titulo) ? $titulo : '');
                $productos->set("precio", isset($precio) ? $precio : 0);
                $productos->set("peso", isset($peso) ? $peso : 0);
                $productos->set("precio_mayorista", isset($precio_mayorista) ? $precio_mayorista : 0);
                $productos->set("precioDescuento", 0);
                $productos->set("stock", isset($stock) ? $stock : 0);
                $productos->set("desarrollo", isset($desarrollo) ? $desarrollo : '');
                $productos->set("categoria", isset($categoria) ? $categoria : 0);
                $productos->set("subcategoria", isset($subcategoria) ? $subcategoria : 0);
                $productos->set("cod_producto", isset($cod_producto) ? $cod_producto : 0);
                $productos->set("fecha", date('Y-m-d'));

                $buscar = array_search($cod_producto, array_column($productos_total, 'cod_producto'));
                if ($buscar) {
                    $productos->set("cod_producto", $cod_producto);
                    echo $cod_producto." - EDITAR<br/>";
                    $productos->editSingle("titulo", $titulo);
                    $productos->editSingle("precio", $precio);
                    $productos->editSingle("precio_mayorista", $precio_mayorista);
                    $productos->editSingle("stock", $stock);
                } else {
                    echo $cod_producto." - AGREGAR<br/>";
                    $productos->add();
                }
            }
        }

        ?>
    </table>
</div>
