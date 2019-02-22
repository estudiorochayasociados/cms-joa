<?php
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$funciones = new Clases\PublicFunction();
$pagina = $funciones->antihack_mysqli(isset($_GET["pagina"]) ? $_GET["pagina"] : '0');
$filter = array();

if ($pagina > 0) {
    $pagina = $pagina - 1;
}

if (@count($filter) == 0) {
    $filter = '';
}

if (@count($_GET) == 0) {
    $anidador = "?";
} else {
    if ($pagina >= 0) {
        $anidador = "&";
    } else {
        $anidador = "?";
    }
}

$data = $productos->listWithOps("", "", (75 * $pagina) . ',' . 75);
$productosPaginador = $productos->paginador("", 75);
?>
    <div class="mt-20">
        <div class="col-lg-12 col-md-12">
            <h4>Productos <a class="btn btn-success pull-right" href="<?= URL ?>/index.php?op=productos&accion=agregar">AGREGAR PRODUCTOS</a></h4>
            <hr/>
            <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
            <hr/>
            <table class="table  table-bordered  ">
                <thead>
                <th>Cod</th>
                <th>Título</th>
                <th>Mercadolibre</th>
                <th>Ajustes</th>
                </thead>
                <tbody>
                <?php
                if (is_array($data)) {
                    for ($i = 0; $i < count($data); $i++) {
                        echo "<tr>";
                        echo "<td>" . strtoupper($data[$i]["cod_producto"]) . "</td>";
                        echo "<td>" . strtoupper($data[$i]["titulo"]) . "</td>";
                        echo "<td>" . strtoupper($data[$i]["meli"]) . "</td>";
                        echo "<td>";
                        echo '<a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="' . URL . '/index.php?op=productos&accion=modificar&cod=' . $data[$i]["cod"] . '">
                    <i class="fa fa-cog"></i></a>';

                        echo '<a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="' . URL . '/index.php?op=productos&accion=ver&borrar=' . $data[$i]["cod"] . '">
                    <i class="fa fa-trash"></i></a>';
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination ">
                    <?php
                    if ($productosPaginador != 1 && $productosPaginador != 0) {
                        $url_final = $funciones->eliminar_get(CANONICAL, "pagina");
                        $links = '';
                        $links .= "<li class='page-item' ><a class='page-link' href='" . $url_final . $anidador . "pagina=1'>1</a></li>";
                        $i = max(2, $pagina - 5);

                        if ($i > 2) {
                            $links .= "<li class='page-item' ><a class='page-link' href='#'>...</a></li>";
                        }
                        for (; $i <= min($pagina + 35, $productosPaginador); $i++) {
                            $links .= "<li class='page-item' ><a class='page-link' href='" . $url_final . $anidador . "pagina=" . $i . "'>" . $i . "</a></li>";
                        }
                        if ($i - 1 != $productosPaginador) {
                            $links .= "<li class='page-item' ><a class='page-link' href='#'>...</a></li>";
                            $links .= "<li class='page-item' ><a class='page-link' href='" . $url_final . $anidador . "pagina=" . $productosPaginador . "'>" . $productosPaginador . "</a></li>";
                        }
                        echo $links;
                        echo "";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
<?php
if (isset($_GET["borrar"])) {
    $cod = $funciones->antihack_mysqli(isset($_GET["borrar"]) ? $_GET["borrar"] : '');
    $productos->set("cod", $cod);
    $producto = $productos->view("cod = '$cod'");
    if ($producto["meli"] != '') {
        $productos->set("meli", $producto["meli"]);
        $_meli = $productos->delete_meli();
    }
    $imagenes->set("cod", $cod);
    $productos->delete();
    $imagenes->deleteAll();
    $funciones->headerMove(URL . "/index.php?op=productos");
}
?>