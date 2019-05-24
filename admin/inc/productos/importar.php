<?php
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$conexion = new Clases\Conexion();
$con = $conexion->con();
include "../vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require "../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php";
$sql_variables = array('cod', 'titulo', 'precio', 'peso', 'precio_mayorista', 'precioDescuento', 'stock', 'desarrollo', 'categoria', 'subcategoria', 'keywords', 'description', 'fecha', 'meli', 'url', 'cod_producto');
?>
<div class="col-md-12">
    <form action="index.php?op=productos&accion=importar_phpexcel" method="post" enctype="multipart/form-data">
        <h3>Importar productos de Excel a la Web (<a href="upload/modelo.xlsx" target="_blank">descargar modelo</a>)
        </h3>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                <input type="file" name="uploadFile" class="form-control" value=""/><br/>
            </div>
            <div class="col-md-6">
                <input type="submit" name="submit" value="Ver archivo de Excel" class='btn  btn-info'/>
            </div>
        </div>
    </form>
    <?php
    if (isset($_POST['submit'])) {
        if (isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "") {
            $allowedExtensions = array("xls", "xlsx");
            $objPHPExcel = PHPEXCEL_IOFactory::load($_FILES['uploadFile']['tmp_name']);

            $objPHPExcel->setActiveSheetIndex(0);
            $numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
            $numCols = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
            $numCols = (ord(strtolower($numCols)) - 96);
            if ($numCols == 10) {
                //$productos->truncate();
                for ($row = 2; $row <= $numRows; $row++) {
                    $cod = substr(md5(uniqid(rand())), 0, 5);
                    $titulo = $objPHPExcel->getActiveSheet()->getCell("A" . $row)->getCalculatedValue();
                    $precio = $objPHPExcel->getActiveSheet()->getCell("B" . $row)->getCalculatedValue();
                    $peso = $objPHPExcel->getActiveSheet()->getCell("C" . $row)->getCalculatedValue();
                    $precio_mayorista = $objPHPExcel->getActiveSheet()->getCell("D" . $row)->getCalculatedValue();
                    $precioDescuento = $objPHPExcel->getActiveSheet()->getCell("E" . $row)->getCalculatedValue();
                    $stock = $objPHPExcel->getActiveSheet()->getCell("F" . $row)->getCalculatedValue();
                    $desarrollo = $objPHPExcel->getActiveSheet()->getCell("G" . $row)->getCalculatedValue();
                    $categoria = $objPHPExcel->getActiveSheet()->getCell("H" . $row)->getCalculatedValue();
                    $subcategoria = $objPHPExcel->getActiveSheet()->getCell("I" . $row)->getCalculatedValue();
                    $cod_producto = $objPHPExcel->getActiveSheet()->getCell("j" . $row)->getCalculatedValue();

                    //$productos->set("cod", isset($cod) ? $cod : substr(md5(uniqid(rand())), 0, 5));
                    //$productos->set("titulo", isset($titulo) ? $titulo : '');
                    //$productos->set("precio", isset($precio) ? $precio : 0);
                    //$productos->set("peso", isset($peso) ? $peso : 0);
                    //$productos->set("precio_mayorista", isset($precio_mayorista) ? $precio_mayorista : 0);
                    //$productos->set("precioDescuento", isset($precioDescuento) ? $precioDescuento :0);
                    //$productos->set("stock", isset($stock) ? $stock : 0);
                    //$productos->set("desarrollo", isset($desarrollo) ? $desarrollo : '');
                    //$productos->set("categoria", isset($categoria) ? $categoria : 0);
                    //$productos->set("subcategoria", isset($subcategoria) ? $subcategoria : 0);
                    //$productos->set("cod_producto", isset($cod_producto) ? $cod_producto : 0);
                    //$productos->set("fecha", date('Y-m-d'));
                    //$productos->add();

                    echo $cod."<br/>";
                    echo $titulo."<br/>";
                    echo $precio."<br/>";
                    echo $peso."<br/>";
                    echo $precio_mayorista."<br/>";
                    echo $precioDescuento."<br/>";
                    echo $stock."<br/>";
                    echo $desarrollo."<br/>";
                    echo $categoria."<br/>";
                    echo $subcategoria."<br/>";
                    echo $cod_producto."<br/>";
                }
            } else {
                echo '<span class="alert alert-danger">Hay errores en el excel que subis. Descargar aqui el ejemplo</span>';
            }
        } else {
            echo '<span class="alert alert-danger">Seleccionar primero el archivo a subir.</span>';
        }
    }

    ?>
</div>
