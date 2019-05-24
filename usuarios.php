<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
$usuario = new Clases\Usuarios();

$template->set("title", "Pinturería Ariel | Usuarios");
$template->set("description", "Registrate y comprá online en nuestra plataforma");
$template->set("keywords", "compra online de pintura, registro de usuario, registro de usuario para compra online de pintura");
$template->set("favicon", LOGO);
$template->themeInit();

$sesion = $usuario->view_sesion();

if(!empty($sesion)) {
    $funciones->headerMove(URL."/sesion");
}

?>
    <body id="bd" class="cms-index-index2 header-style2 prd-detail sns-contact-us cms-simen-home-page-v2 default cmspage">
    <div id="sns_wrapper">
        <?php $template->themeNav(); ?>
        <div class="container mb-150">
            <div class="col-md-6">
                <h3 class="mt-40 fs-20">Iniciar Sesión
                    <hr/>
                </h3>
                <?php
                if (isset($_POST["login_usuarios"])) {
                    $email = $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
                    $password = $funciones->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');

                    $usuario->set("email", $email);
                    $usuario->set("password", $password);

                    if (!$usuario->login()) {
                        ?>
                        <div class="alert alert-warning" role="alert">Email o contraseña incorrecta.</div>
                        <?php
                    } else {
                        $funciones->headerMove(CANONICAL);
                    }
                }
                ?>
                <form method="post">
                    <div class="row">
                        <div class="col-md-12 mb-15">
                            Email
                            <div class="input-group">
                                <input class="form-control h40" type="email" placeholder="Correo electrónico" name="email"
                                       required/>
                                <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-envelope"></i></span>
                            </div>
                        </div>
                        <div class="col-md-12 mb-15">
                            Contraseña
                            <div class="input-group">
                                <input class="form-control h40" type="password" placeholder="Contraseña" name="password"
                                       required/>
                                <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-lock"></i></span>
                            </div>
                        </div>
                        <br/>
                        <div class="col-md-12 mb-15">
                            <button type="submit" name="login_usuarios" class="btn btn-default">Ingresar</button>
                            <br/><br/>
                            <div class="text-left"> <a href="#">¿Olvidaste tu contraseña?</a> </div>
                        </div>
                    </div>
                </form>
            </div><!-- End modal -->
            <!-- REGISTRAR -->
            <div class="col-md-6">
                <h3 class="mt-40 fs-20">Registro
                    <hr/>
                </h3>
                <?php
                if (isset($_POST["registrar_usuarios"])) {
                    if ($_POST["password"] == $_POST["password2"]) {
                        $nombre = $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : '');
                        $apellido = $funciones->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : '');
                        $email = $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
                        $password = $funciones->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');
                        $cod = substr(md5(uniqid(rand())), 0, 10);
                        $fecha = getdate();
                        $fecha = $fecha['year'] . '-' . $fecha['mon'] . '-' . $fecha['mday'];

                        $usuario->set("cod", $cod);
                        $usuario->set("nombre", $nombre);
                        $usuario->set("apellido", $apellido);
                        $usuario->set("email", $email);
                        $usuario->set("password", $password);
                        $usuario->set("fecha", $fecha);
                        $usuario->set("invitado", 0);
                        $usuario->set("descuento", 0);
                        $add = $usuario->add();
                        if ($add == false) {
                            ?>
                            <div class="alert alert-warning" role="alert">El email ya está registrado.</div>
                            <?php
                        } else {
                            $usuario->set("email", $email);
                            $usuario->set("password", $password);
                            $usuario->login();
                            $funciones->headerMove(URL . "/sesion");
                        }
                    } else {
                        ?>
                        <div class="alert alert-warning" role="alert">Las contraseñas no coinciden.</div>
                        <?php
                    }
                }
                ?>
                <form method="post">
                    <div class="row">
                        <div class="col-md-12">
                            Nombre
                            <div class="input-group mb-15">
                                <input class="form-control h40" type="text" placeholder="Nombre" name="nombre" required/>
                                <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-user"></i></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            Apellido
                            <div class="input-group mb-15">
                                <input class="form-control h40" type="text" placeholder="Apellido" name="apellido" required/>
                                <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-user"></i></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            Email
                            <div class="input-group mb-15">
                                <input class="form-control h40" type="email" placeholder="Email" name="email" required/>
                                <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-envelope"></i></span>

                            </div>
                        </div>
                        <div class="col-md-6">
                            Contraseña
                            <div class="input-group mb-15">
                                <input class="form-control h40" type="password" placeholder="Contraseña" name="password" required/>
                                <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-lock"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            Reescribir Contraseña
                            <div class="input-group mb-15">
                                <input class="form-control h40" type="password" placeholder="Confirmar Contraseña" name="password2" required/>
                                <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-lock"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" name="registrar_usuarios" class="btn btn-default">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
<?php $template->themeEnd() ?>