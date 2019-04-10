<?php
$pedidos = new Clases\Pedidos();
$detalle = new Clases\DetallePedidos();
$productos = new Clases\Productos();
$carrito = new Clases\Carrito();
$envios = new Clases\Envios();
$pagos = new Clases\Pagos();
$usuarios = new Clases\Usuarios();
$carro_return = $carrito->return();
$carroEnvio = $carrito->checkEnvio();
$carroPago = $carrito->checkPago();
$productos_array = $productos->list_with_options("", "id desc", "50");

if (isset($_POST["buscar"])) {
    $titulo = $funciones->antihack_mysqli(isset($_POST["buscar"]) ? $_POST["buscar"] : '');
    $titulo = explode(" ", $titulo);
    $buscar = '';
    foreach ($titulo as $tit) {
        $buscar .= "titulo like '%$tit%' AND ";
    }
    $productos_array = $productos->list_with_options(array(substr($buscar, 0, -4)), "", "");
}

if (isset($_POST["id_carrito"])) {
    $carrito->delete($carroEnvio);
    $carrito->delete($carroPago);
    $carrito->set("id", $_POST['id_carrito']);
    $carrito->set("cantidad", $_POST["cantidad"]);
    $carrito->set("titulo", $_POST['titulo']);
    $carrito->set("precio", $_POST['precio']);
    $carrito->add();
    $funciones->headerMove(CANONICAL . "#success");
    $carro_return = $carrito->return();
    $carroEnvio = $carrito->checkEnvio();
    $carroPago = $carrito->checkPago();
}
?>
<div class="mt-20 col-md-12">
    <div class="row">
        <div class="col-md-8">
            <h4>Agregar Pedido</h4>

        </div>

        <div class="col-md-3">
            <form method="post">
                <input type="text" placeholder="buscar en productos" name="buscar" value="<?= isset($_POST["buscar"]) ? $_POST["buscar"] : '' ?>"/>
            </form>

        </div>
        <div class='pull-right'>
            <a href="<?= URL ?>/index.php?op=pedidos&accion=ver" class="btn btn-success">VER PEDIDOS</a>
        </div>
    </div>
    <div class="clearfix"></div>
    <hr/>
</div>
<div class="col-md-12">
    <div class="row">
        <!-- CARRITO -->
        <div class="col-md-4">
            <h5>Pedido</h5>

            <table class="table table-bordered table-condensed table-hover">
                <thead>
                <th>PRODUCTO</th>
                <th>PRECIO</th>
                <th>CANTIDAD</th>
                <th>TOTAL</th>
                <th></th>
                </thead>
                <tbody>
                <?php
                if (isset($_GET["remover"])) {
                    $carroPago = $carrito->checkPago();
                    if ($carroPago != '') {
                        $carrito->delete($carroPago);
                    }
                    $carroEnvio = $carrito->checkEnvio();
                    if ($carroEnvio != '') {
                        $carrito->delete($carroEnvio);
                    }
                    $carrito->delete($_GET["remover"]);
                    $funciones->headerMove(URL . "/index.php?op=pedidos&accion=agregar");
                }

                $i = 0;
                $precio = 0;
                foreach ($carro_return as $key => $carroItem) {
                    $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
                    ?>
                    <tr>
                        <td><b><?= mb_strtoupper($carroItem["titulo"]); ?></b></td>
                        <td><span class="<?= $none ?>"><?= "$" . $carroItem["precio"]; ?></span></td>
                        <td><span class="<?= $none ?>"><?= $carroItem["cantidad"]; ?></span></td>
                        <td>
                            <?php
                            if ($carroItem["precio"] != 0) {
                                echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                            } else {
                                echo "¡Gratis!";
                            }
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-danger btn-sm" href="<?= CANONICAL ?>&remover=<?= $key ?>"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                <tr>
                    <td><b>TOTAL</b></td>
                    <td></td>
                    <td></td>
                    <td><b>$<?= number_format($carrito->precio_total(), "2", ",", "."); ?></b></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
            <div class="envio" id="formulario-envio">
                <?php
                $metodos_de_envios = $envios->list(array("peso >= " . $carrito->peso_final() . " OR peso = 0"));
                if ($carroEnvio == '') {
                    echo "<b>Seleccioná el envió que más te convenga:</b>";
                    if (isset($_POST["envio"])) {
                        if ($carroEnvio != '') {
                            $carrito->delete($carroEnvio);
                        }
                        $envio_final = $_POST["envio"];
                        $envios->set("cod", $envio_final);
                        $envio_final_ = $envios->view();
                        $carrito->set("id", "Envio-Seleccion");
                        $carrito->set("cantidad", 1);
                        $carrito->set("titulo", $envio_final_["titulo"]);
                        $carrito->set("precio", $envio_final_["precio"]);
                        $carrito->add();
                        $funciones->headerMove(CANONICAL . "");
                    }
                    ?>
                    <form method="post" id="envio">
                        <select name="envio" class="form-control" id="envio" onchange="this.form.submit()">
                            <option value="" selected disabled>Elegir envío</option>
                            <?php
                            foreach ($metodos_de_envios as $metodos_de_envio_) {
                                if ($metodos_de_envio_["precio"] == 0) {
                                    $metodos_de_envio_precio = "¡Gratis!";
                                } else {
                                    $metodos_de_envio_precio = "$" . $metodos_de_envio_["precio"];
                                }
                                echo "<option value='" . $metodos_de_envio_["cod"] . "'>" . mb_strtoupper($metodos_de_envio_["titulo"]) . " -> " . $metodos_de_envio_precio . "</option>";
                            }
                            ?>
                        </select>
                    </form>
                    <hr/>
                    <?php
                }
                ?>
            </div>
            <div class="pago" id="formulario-pago">
                <form method="post">
                    <?php
                    if ($carroPago == '') {
                        echo "<b>Seleccioná el método de pago que más te convenga:</b>";
                        $metodo = $funciones->antihack_mysqli(isset($_POST["metodos-pago"]) ? $_POST["metodos-pago"] : '');
                        $metodo_get = $funciones->antihack_mysqli(isset($_GET["metodos-pago"]) ? $_GET["metodos-pago"] : '');
                        if ($metodo != '') {
                            $key_metodo = $carrito->checkPago();
                            $carrito->delete($key_metodo);
                            $pagos->set("cod", $metodo);
                            $pago__ = $pagos->view();
                            $precio_final_metodo = $carrito->precio_total();
                            if ($pago__["aumento"] != 0 || $pago__["disminuir"] != '') {
                                if ($pago__["aumento"]) {
                                    $numero = (($precio_final_metodo * $pago__["aumento"]) / 100);
                                    $carrito->set("id", "Metodo-Pago");
                                    $carrito->set("cantidad", 1);
                                    $carrito->set("titulo", "CARGO +" . $pago__['aumento'] . "% / " . mb_strtoupper($pago__["titulo"]));
                                    $carrito->set("precio", $numero);
                                    $carrito->add();
                                    $funciones->headerMove(CANONICAL . "");
                                } else {
                                    $numero = (($precio_final_metodo * $pago__["disminuir"]) / 100);
                                    $carrito->set("id", "Metodo-Pago");
                                    $carrito->set("cantidad", 1);
                                    $carrito->set("titulo", "DESCUENTO -" . $pago__['disminuir'] . "% / " . mb_strtoupper($pago__["titulo"]));
                                    $carrito->set("precio", "-" . $numero);
                                    $carrito->add();
                                    $funciones->headerMove(CANONICAL . "");
                                }
                            }
                        }
                        ?>
                        <div class="form-bd">
                            <?php $lista_pagos = $pagos->list(array(" estado = 0 ")); ?>
                            <select name="metodos-pago" class="form-control" id="metodos-pago" onchange="this.form.submit()">
                                <option value="" selected disabled>Elegir metodo de pago</option>
                                <?php
                                foreach ($lista_pagos as $pago) {
                                    echo "<option value='" . $pago["cod"] . "'>" . mb_strtoupper($pago["titulo"]) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                    }
                    ?>
                </form>
            </div>
            <div class="usuario" id="formulario-usuario">
                <form method="post" class="row">
                    <?php
                    if ($carroPago != '' && $carroEnvio != '') {
                        ?>
                        <h3 class="col-md-12"><b>Compra N°: <?= $_SESSION["cod_pedido"] ?></b>
                            <hr/>
                        </h3>
                        <div class="clearfix"></div>
                        <?php
                        if (isset($_POST["registrarmeBtn"])) {
                            $error = 0;
                            $cod = substr(md5(uniqid(rand())), 0, 10);
                            $nombre = $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : '');
                            $apellido = $funciones->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : '');
                            $doc = $funciones->antihack_mysqli(isset($_POST["doc"]) ? $_POST["doc"] : '');
                            $email = $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
                            $password1 = $funciones->antihack_mysqli(isset($_POST["password1"]) ? $_POST["password1"] : '');
                            $password2 = $funciones->antihack_mysqli(isset($_POST["password2"]) ? $_POST["password2"] : '');
                            $postal = $funciones->antihack_mysqli(isset($_POST["postal"]) ? $_POST["postal"] : '');
                            $localidad = $funciones->antihack_mysqli(isset($_POST["localidad"]) ? $_POST["localidad"] : '');
                            $provincia = $funciones->antihack_mysqli(isset($_POST["provincia"]) ? $_POST["provincia"] : '');
                            $pais = $funciones->antihack_mysqli(isset($_POST["pais"]) ? $_POST["pais"] : '');
                            $telefono = $funciones->antihack_mysqli(isset($_POST["telefono"]) ? $_POST["telefono"] : '');
                            $celular = $funciones->antihack_mysqli(isset($_POST["celular"]) ? $_POST["celular"] : '');
                            $invitado = $funciones->antihack_mysqli(isset($_POST["invitado"]) ? $_POST["invitado"] : 0);
                            $descuento = $funciones->antihack_mysqli(isset($_POST["descuento"]) ? $_POST["descuento"] : 0);
                            $fecha = $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d"));

                            $usuarios->set("cod", $cod);
                            $usuarios->set("nombre", $nombre);
                            $usuarios->set("apellido", $apellido);
                            $usuarios->set("doc", $doc);
                            $usuarios->set("email", $email);
                            $usuarios->set("password1", $password1);
                            $usuarios->set("postal", $postal);
                            $usuarios->set("localidad", $localidad);
                            $usuarios->set("provincia", $provincia);
                            $usuarios->set("pais", $pais);
                            $usuarios->set("telefono", $telefono);
                            $usuarios->set("celular", $celular);
                            $usuarios->set("invitado", $invitado);
                            $usuarios->set("descuento", $descuento);
                            $usuarios->set("fecha", $fecha);

                            $precio = $carrito->precio_total();

                            if ($invitado == 1) {
                                if ($password1 != $password2) {
                                    $error = 1;
                                    echo "Error las contraseñas no coinciden.<br/>";
                                } else {
                                    $error = 0;
                                    $usuarios->add();
                                    $pedidos->set("cod", $_SESSION["cod_pedido"]);
                                    $pedidos->set("total", $precio);
                                    $pedidos->set("estado", 1);
                                    $pedidos->set("tipo", $carro_return[$carroPago]["titulo"]);
                                    $pedidos->set("usuario", $cod);
                                    $pedidos->set("detalle", "");
                                    $pedidos->set("fecha", $fecha);
                                    $pedidos->add();

                                    foreach ($carro_return as $carroItem) {
                                        $detalle->set("cod", $_SESSION["cod_pedido"]);
                                        $detalle->set("producto", $carroItem["titulo"]);
                                        $detalle->set("cantidad", $carroItem["cantidad"]);
                                        $detalle->set("precio", $carroItem["precio"]);
                                        $detalle->add();
                                    }
                                }
                            } else {
                                if ($error == 0) {
                                    $usuarios->invitado_sesion();
                                    $pedidos->set("cod", $_SESSION["cod_pedido"]);
                                    $pedidos->set("total", $precio);
                                    $pedidos->set("estado", 1);
                                    $pedidos->set("tipo", $carro_return[$carroPago]["titulo"]);
                                    $pedidos->set("usuario", $cod);
                                    $pedidos->set("detalle", "");
                                    $pedidos->set("fecha", $fecha);
                                    $pedidos->add();

                                    foreach ($carro_return as $carroItem) {
                                        $detalle->set("cod", $_SESSION["cod_pedido"]);
                                        $detalle->set("producto", $carroItem["titulo"]);
                                        $detalle->set("cantidad", $carroItem["cantidad"]);
                                        $detalle->set("precio", $carroItem["precio"]);
                                        $detalle->add();
                                    }
                                }
                            }

                            unset($_SESSION["cod_pedido"]);
                            $carrito->destroy();
                            echo "<script>alert('Perfecto, tu carrito fue cargado exitosamente');</script>";
                            $funciones->headerMove(URL . "/index.php?op=pedidos&accion=agregar");
                        }
                        ?>
                        <input type="hidden" name="metodos-pago"/>
                        <div class="col-md-6">Nombre:<br/>
                            <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["nombre"]) ? $_POST["nombre"] : '' ?>" placeholder="Escribir nombre" name="nombre" required/>
                        </div>
                        <div class="col-md-6">Apellido:<br/>
                            <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["apellido"]) ? $_POST["apellido"] : '' ?>" placeholder="Escribir apellido" name="apellido" required/>
                        </div>
                        <div class="col-md-6">Email:<br/>
                            <input class="form-control  mb-10" type="email" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : '' ?>" placeholder="Escribir email" name="email" required/>
                        </div>
                        <div class="col-md-6">Teléfono:<br/>
                            <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["telefono"]) ? $_POST["telefono"] : '' ?>" placeholder="Escribir telefono" name="telefono" required/>
                        </div>
                        <div class="col-md-4">Provincia:<br/>
                            <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["provincia"]) ? $_POST["provincia"] : '' ?>" placeholder="Escribir provincia" name="provincia" required/>
                        </div>
                        <div class="col-md-4">Localidad:<br/>
                            <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["localidad"]) ? $_POST["localidad"] : '' ?>" placeholder="Escribir localidad" name="localidad" required/>
                        </div>
                        <div class="col-md-4">Dirección:<br/>
                            <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["direccion"]) ? $_POST["direccion"] : '' ?>" placeholder="Escribir dirección" name="direccion" required/>
                        </div>
                        <label class="col-md-12 col-xs-12 mt-10 mb-10 crear" style="font-size:16px">
                            <input type="checkbox" name="invitado" value="1" onchange="$('.password').slideToggle()"> ¿Deseas crear una cuenta de usuario y dejar tus datos grabados para la próxima compra?
                        </label>
                        <div class="col-md-6 col-xs-6 password" style="display: none;">Contraseña:<br/>
                            <input class="form-control  mb-10" type="password" value="<?php echo isset($_POST["password1"]) ? $_POST["password1"] : '' ?>" placeholder="Escribir password" name="password1"/>
                        </div>
                        <div class="col-md-6 col-xs-6 password" style="display: none;">Repetir Contraseña:<br/>
                            <input class="form-control  mb-10" type="password" value="<?php echo isset($_POST["password2"]) ? $_POST["password2"] : '' ?>" placeholder="Escribir repassword" name="password2"/>
                        </div>

                        <label class="col-md-12 col-xs-12 mt-10 mb-10" style="font-size:16px">
                            <input type="checkbox" name="factura" value="0" onchange="$('.factura').slideToggle()"> Solicitar FACTURA A
                        </label>
                        <div class="col-md-12 col-xs-12 factura" style="display: none;">CUIT:<br/>
                            <input class="form-control  mb-10" type="number" value="<?php echo isset($_POST["doc"]) ? $_POST["doc"] : '' ?>" placeholder="Escribir CUIT" name="doc"/>
                        </div>
                        <div class="col-md-12 col-xs-12 mb-50">
                            <input class="btn btn-success" type="submit" value="¡Finalizar la compra!" name="registrarmeBtn"/>
                        </div>
                        <?php
                    }
                    ?>
                </form>
            </div>
        </div>
        <!-- FIN CARRITO -->
        <!-- LISTADO Y MODAL -->
        <div class="col-md-8">
            <h5>Productos</h5>
            <table class="table table-bordered table-condensed table-hover">
                <thead>
                <th>PRODUCTO</th>
                <th>STOCK</th>
                <th>PRECIO</th>
                <th></th>
                </thead>
                <tbody>

                <?php
                foreach ($productos_array as $producto_) {
                    echo "<tr>";
                    echo "<td>" . $producto_["titulo"] . "</td>";
                    echo "<td>" . $producto_["stock"] . "</td>";
                    echo "<td>$" . $producto_["precio"] . "</td>";
                    ?>
                    <td>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal<?= $producto_["id"]; ?>"><i class="fa fa-shopping-cart"></i> Agregar carrito</button>
                    </td>
                    <?php
                    echo "</tr>";
                    ?>
                    <div id="myModal<?= $producto_["id"]; ?>" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="pull-left modal-title">Agregar a Carrito</h4>
                                </div>
                                <div class="modal-body" id="contenidoForm">
                                    <form class="agregarACarrito" method="post" action="<?= CANONICAL ?>">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <b>Título: </b>
                                                <input type="hidden" name="id_carrito" class="form-control" value="<?= $producto_["id"]; ?>"/>
                                                <input type="text" name="titulo" readonly value="<?= $producto_["titulo"]; ?>"/>
                                            </div>
                                            <div class="col-md-6 mt-10">
                                                <b>Precio: </b>
                                                <input type="text" readonly name="precio" class="form-control" value="<?= $producto_["precio"]; ?>" required="">
                                            </div>
                                            <div class="col-md-6 mt-10">
                                                <b>Cantidad: </b>
                                                <input type="number" name="cantidad" min="1" class="form-control" value="1" max="<?= $producto_["stock"]; ?>" required="">
                                            </div>
                                            <div class="col-md-12">
                                                <hr/>
                                            </div>
                                            <div class="col-md-6 mt-10">
                                                <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal"> Cancelar</button>
                                            </div>
                                            <div class="col-md-6 mt-10">
                                                <button type="submit" class="pull-right btn btn-success btn-sm" name="enviar">Agregar a carrito</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <!-- FIN LISTADO Y MODAL -->
    </div>
</div>
