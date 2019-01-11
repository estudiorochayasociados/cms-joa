<?php
$pagos = new Clases\Pagos();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod   = substr(md5(uniqid(rand())), 0, 10);
    $pagos->set("cod", $cod);
    $pagos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $pagos->set("leyenda", $funciones->antihack_mysqli(isset($_POST["leyenda"]) ? $_POST["leyenda"] : ''));
    $pagos->add();
    $funciones->headerMove(URL . "/index.php?op=pagos");
}
?>

<div class="col-md-12 ">
    <h4>Pagos</h4>
    <hr/>
    <form method="post" class="row"  >
        <label class="col-md-12">Método de pago:<br/>
            <input type="text" name="titulo">
        </label>
        <label class="col-md-12">Descripción del método de pago:<br/>
            <textarea name="leyenda"></textarea>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Pagos" />
        </div>
    </form>
</div>
