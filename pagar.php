<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template  = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$template->set("title", "Admin");
$template->set("description", "Admin");
$template->set("keywords", "Inicio");
$template->set("favicon", LOGO);
$template->themeInit();
$carrito    = new Clases\Carrito();
$usuarios    = new Clases\Usuarios();
$usuarioSesion = $usuarios->view_sesion();
$cod_pedido   = $_SESSION["cod_pedido"];
$tipo_pedido = isset($_POST["metodos-pago"]) ? $_POST["metodos-pago"] : '';
if($tipo_pedido == '') {
    $funciones->headerMove(URL . "/carrito");
}
?>
<body id="bd" class="cms-index-index2 header-style2 prd-detail sns-products-detail1 cms-simen-home-page-v2 default cmspage">
    <div id="sns_wrapper">
        <?php $template->themeNav(); ?>
        <div class="container mt-30">
            <h3><b>Compra N°: <?= $cod_pedido ?></b><br/>Llená el siguiente formulario para poder finalizar tu compra <span class="em em---1"></span></h3>
            <?php
            if(count($usuarioSesion) != 0) {
                $funciones->headerMove(URL."/checkout/".$cod_pedido."/".$tipo_pedido);
            }
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
                $invitado = $funciones->antihack_mysqli(isset($_POST["invitado"]) ? $_POST["invitado"] : '0');        
                $descuento = $funciones->antihack_mysqli(isset($_POST["descuento"]) ? $_POST["descuento"] : '');        
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

                if($invitado == 1) {
                    if($password1 != $password2) {
                        $error = 1;
                        echo "Error las contraseñas no coinciden.<br/>";
                    } else {
                        $error = 0;
                        $usuarios->add();
                    }
                } else {
                    if($error == 0) {            
                        $usuarios->invitado_sesion();   
                    }            
                }

                $funciones->headerMove(URL."/checkout/".$cod_pedido."/".$tipo_pedido);
            }        
            ?>
            <div class="col-md-12">
                <form method="post" class="row" >
                  <div class="row">
                      <input type="hidden" value="<?= $tipo_pedido ?>" name="metodos-pago"  />
                    <div class="col-md-6">Nombre:<br/>
                        <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["nombre"]) ? $_POST["nombre"] : '' ?>" placeholder="Escribir nombre" name="nombre" required />
                    </div>
                    <div class="col-md-6">Apellido:<br/>
                        <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["apellido"]) ? $_POST["apellido"] : '' ?>" placeholder="Escribir apellido" name="apellido" required />
                    </div>
                    <div class="col-md-12">Email:<br/>
                        <input class="form-control  mb-10" type="email" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : '' ?>" placeholder="Escribir email" name="email" required />
                    </div>     
                    <div class="col-md-12">Teléfono:<br/>
                        <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["telefono"]) ? $_POST["telefono"] : '' ?>" placeholder="Escribir telefono"  name="telefono" required />
                    </div>  
                    <div class="col-md-4">Provincia:<br/>
                        <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["provincia"]) ? $_POST["provincia"] : '' ?>" placeholder="Escribir provincia"  name="provincia" required />
                    </div> 
                    <div class="col-md-4">Localidad:<br/>
                        <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["localidad"]) ? $_POST["localidad"] : '' ?>" placeholder="Escribir localidad"  name="localidad" required />
                    </div>
                    <div class="col-md-4">Dirección:<br/>
                        <input class="form-control  mb-10" type="text" value="<?php echo isset($_POST["direccion"]) ? $_POST["direccion"] : '' ?>" placeholder="Escribir dirección" name="direccion" required />
                    </div>
                    <label  class="col-md-12 col-xs-12 mt-10 mb-10 crear" style="font-size:16px">
                        <input type="checkbox" name="invitado" value="1" onchange="$('.password').slideToggle()"> ¿Deseas crear una cuenta de usuario y dejar tus datos grabados para la próxima compra?
                    </label>       
                    <div class="col-md-6 col-xs-6 password" style="display: none;">Contraseña:<br/>
                        <input class="form-control  mb-10" type="password" value="<?php echo isset($_POST["password1"]) ? $_POST["password1"] : '' ?>" placeholder="Escribir password"  name="password1" />
                    </div>
                    <div class="col-md-6 col-xs-6 password" style="display: none;">Repetir Contraseña:<br/>
                        <input class="form-control  mb-10" type="password" value="<?php echo isset($_POST["password2"]) ? $_POST["password2"] : '' ?>" placeholder="Escribir repassword"  name="password2" />
                    </div>  

                    <label  class="col-md-12 col-xs-12 mt-10 mb-10" style="font-size:16px">
                        <input type="checkbox" name="factura" value="0" onchange="$('.factura').slideToggle()"> Solicitar FACTURA A
                    </label>       
                    <div class="col-md-12 col-xs-12 factura" style="display: none;">CUIT:<br/>
                        <input class="form-control  mb-10" type="number" value="<?php echo isset($_POST["doc"]) ? $_POST["doc"] : '' ?>" placeholder="Escribir CUIT"  name="doc"  />
                    </div>         
                    <div class="col-md-12 col-xs-12 mb-50">
                        <input class="btn btn-success" type="submit" value="¡Finalizar la compra!" name="registrarmeBtn" />
                    </div>      
                </div>
            </form>
        </div>
    </div>
    <?php
    $template->themeEnd();
    ?>