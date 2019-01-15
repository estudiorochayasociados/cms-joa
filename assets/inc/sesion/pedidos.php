<?php
//Clases
$usuario = new Clases\Usuarios();
$pedidos = new Clases\Pedidos();

$usuario->set("cod", $_SESSION["usuarios"]["cod"]);
$usuarioData = $usuario->view();

$filterPedidosAgrupados = array("usuario = '" . $usuarioData['cod'] . "' GROUP BY cod");
$pedidosArrayAgrupados = $pedidos->list($filterPedidosAgrupados);

$filterPedidosSinAgrupar = array("usuario = '" . $usuarioData['cod'] . "'");
$pedidosArraySinAgrupar = $pedidos->list($filterPedidosSinAgrupar);
asort($pedidosArraySinAgrupar);
?>
<div class="col-md-9">
    <?php if (empty($pedidosArrayAgrupados)): ?>
        <?php echo "<h4>No has realizado ningún pedido todavía.</h4>"; ?>
    <?php else: ?>
        <?php foreach ($pedidosArrayAgrupados as $key => $value): ?>
            <?php $precioTotal = 0; ?>
            <?php $fecha = explode(" ", $value["fecha"]); ?>
            <?php $fecha1 = explode("-", $fecha[0]); ?>
            <?php $fecha1 = $fecha1[2] . '-' . $fecha1[1] . '-' . $fecha1[0] . '-'; ?>
            <?php $fecha = $fecha1 . $fecha[1]; ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading">
                    <h5 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $value["cod"] ?>"
                           aria-expanded="false" aria-controls="collapse<?= $value["cod"] ?>" class="collapsed">
                            Pedido <?= $value["cod"] ?>
                            <span class="hidden-xs hidden-sm">- Fecha <?= $fecha ?></span>
                            <?php if ($value["estado"] == 0): ?>
                                <span style="padding:5px;font-size:13px;margin-top:-3px;border-radius: 10px;"
                                      class="btn-primary pull-right">
                            Estado: Carrito no cerrado
                             </span>
                            <?php elseif ($value["estado"] == 1): ?>
                                <span style="padding:5px;font-size:13px;margin-top:-3px;border-radius: 10px;"
                                      class="btn-warning pull-right">
                            Estado: Pago pendiente
                             </span>
                            <?php elseif ($value["estado"] == 2): ?>
                                <span style="padding:5px;font-size:13px;margin-top:-3px;border-radius: 10px;"
                                      class="btn-success pull-right">
                            Estado: Pago aprobado
                             </span>
                            <?php elseif ($value["estado"] == 3): ?>
                                <span style="padding:5px;font-size:13px;margin-top:-3px;border-radius: 10px;"
                                      class="btn-info pull-right">
                            Estado: Pago enviado
                             </span>
                            <?php elseif ($value["estado"] == 4): ?>
                                <span style="padding:5px;font-size:13px;margin-top:-3px;border-radius: 10px;"
                                      class="btn-danger pull-right">
                            Estado: Pago rechazado
                             </span>
                            <?php endif; ?>
                        </a>
                    </h5>
                </div>
                <div id="collapse<?= $value["cod"] ?>" class="panel-collapse collapse" role="tabpanel"
                     aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                    <div class="panel-body">
                        <table class="table table-striped table-responsive table-hover">
                            <thead>
                            <tr>
                                <th>
                                    Producto
                                </th>
                                <th>
                                    Cantidad
                                </th>
                                <th class="hidden-xs hidden-sm">
                                    Precio
                                </th>
                                <th>
                                    Precio Final
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pedidosArraySinAgrupar as $key2 => $value2): ?>
                                <?php if ($value2['cod'] == $value["cod"]): ?>
                                    <tr>
                                        <td><?= $value2["producto"] ?></td>
                                        <td><?= $value2["cantidad"] ?></td>
                                        <td>$<?= $value2["precio"] ?></td>
                                        <td>$<?= $value2["precio"] * $value2["cantidad"] ?></td>
                                        <?php $precioTotal = $precioTotal + ($value2["precio"] * $value2["cantidad"]); ?>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <tr>
                                <td><b>TOTAL DE LA COMPRA</b></td>
                                <td></td>
                                <td></td>
                                <td><b>$<?= $precioTotal ?></b></td>
                            </tr>
                            </tbody>
                        </table>
                        <hr>
                        <span style="font-size:16px">
                    <b>FORMA DE PAGO</b>
                    <span class="alert-info" style="border-radius: 10px; padding: 10px;">
                        <?= $value["tipo"] ?>
                    </span>
                </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>