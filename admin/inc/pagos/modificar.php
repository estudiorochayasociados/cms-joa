<?php
$pagos = new Clases\Pagos();
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$pagos->set("cod", $cod);
$pagos_ = $pagos->view();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $pagos_["cod"];
    $pagos->set("cod", $cod);
    $pagos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $pagos->set("leyenda", $funciones->antihack_mysqli(isset($_POST["leyenda"]) ? $_POST["leyenda"] : ''));
    $pagos->edit();
    $funciones->headerMove(URL . "/index.php?op=pagos&accion=modificar&cod=$cod");
}
?>

<div class="col-md-12 ">
    <h4>
        Pagos
    </h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">
            Título:<br/>
            <input type="text" value="<?= $pagos_["titulo"] ?>" name="titulo">
        </label>
        <label class="col-md-12">Descripción del método de pago:<br/>
            <textarea name="leyenda"><?= $pagos_["leyenda"] ?></textarea>
        </label>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Pagos"/>
        </div>
    </form>
</div>